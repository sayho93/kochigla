<? include_once $_SERVER["DOCUMENT_ROOT"]."/mygift/inc/header.php"; ?>
<? include_once $_SERVER["DOCUMENT_ROOT"]."/mygift/shared/public/classes/ChatRoute.php"; ?>
<?
$cRoute = new ChatRoute();

$groupId = $_REQUEST["groupId"];
$group = $cRoute->getGroup($groupId);

if(!AuthUtil::isLoggedIn()){
    echo "<script>history.back();</script>";
}

if(!$cRoute->isMemberOf(AuthUtil::getLoggedInfo()->id, $groupId)){
    echo "<script>history.back();</script>";
}

?>
    <style>
        .chat_container{
            max-width:100vw; margin:auto;
        }

        img{ max-width:100%;}
        .inbox_people {
            background: #f8f8f8 none repeat scroll 0 0;
            float: left;
            overflow: hidden;
            width: 20%;
            border-right:1px solid #c4c4c4;
        }
        .mesgs {
            float: left;
            padding: 0;
            width: 80%;
        }

        @media screen and (max-width: 720px){
            .inbox_people {
                display: none;
            }
            .mesgs {
                width: 100%;
            }
        }

        .inbox_msg {
            clear: both;
            overflow: hidden;
        }

        .recent_heading {float: left; width:40%;}

        .headind_srch{ padding:10px 29px 10px 20px; overflow:hidden; border-bottom:1px solid #c4c4c4;}

        .recent_heading h4 {
            color: #e44c65;
            font-size: 16px;
            margin: auto;
        }

        .chat_ib h5{ font-size:15px; color:#464646; margin:0 0 8px 0;}
        .chat_ib h5 span{ font-size:13px; float:right;}
        .chat_ib p{ font-size:14px; color:#989898; margin:auto}
        .chat_img {
            float: left;
            width: 11%;
        }
        .chat_ib {
            float: left;
            padding: 0;
            width: 88%;
        }

        .chat_people{ overflow:hidden; clear:both;}
        .chat_list {
            border-bottom: 1px solid #c4c4c4;
            margin: 0;
            padding: 10px;
        }
        .inbox_chat { height: 550px; overflow-y: scroll;}

        .sender_name {
            font-size: 12px;
            margin:0;
            /*display: inline-block;*/
            /*width: 6%;*/
        }
        .received_msg {
            display: inline-block;
            padding: 0 0 0 10px;
            vertical-align: top;
            width: 92%;
        }
        .received_withd_msg p {
            background: #ebebeb none repeat scroll 0 0;
            border-radius: 3px;
            color: #646464;
            font-size: 14px;
            margin: 0;
            padding: 5px 10px 5px 12px;
            width: 100%;
        }
        .time_date {
            color: #747474;
            display: block;
            font-size: 12px;
            margin: 8px 0 0;
        }
        .received_withd_msg { width: 50%;}

        .sent_msg p {
            background: #1e88e5 none repeat scroll 0 0;
            border-radius: 3px;
            font-size: 14px;
            margin: 0; color:#fff;
            padding: 5px 10px 5px 12px;
            width:100%;
        }
        .outgoing_msg{ overflow:hidden; margin:10px 15px;}
        .incoming_msg{
            margin:10px 15px;
        }

        .sent_msg {
            float: right;
            width: 46%;
        }
        .input_msg_write textarea {
            background: rgba(0, 0, 0, 0) none repeat scroll 0 0;
            border: medium none;
            padding:0.75em 3.5em 0.75em 1em;
            color: white;
            font-size: 15px;
            min-height: 70px;
            height:100px;
            width: 100%;
        }

        .type_msg {border-top: 1px solid #c4c4c4;position: relative;}
        .msg_send_btn {
            background: #1e88e5 none repeat scroll 0 0;
            border: medium none;
            border-radius: 50%;
            color: #fff;
            cursor: pointer;
            font-size: 17px;
            height: 33px;
            position: absolute;
            right: 10px;
            top: 11px;
            width: 33px;
        }
        .messaging { padding: 0;}
        .msg_history {
            height: 516px;
            overflow-y: auto;
        }

        #chat_shader{
            display: none;
            z-index:9999;
            position:fixed;
            top:0;
            left:0;
            background-color: rgba(255, 255, 255, 0.75);
            width: 100vw;
            height:100vh;
        }
    </style>
    <script>
        $(document).ready(function(){
            $(".float-button").hide();
            hideFooter();

            $(".jEnter").click(function(e){
                e.stopPropagation();
                e.preventDefault();

                var id = $(this).attr("groupId");
                location.href = "chat.php?groupId=" + id;
            });

            /**
             * Resizing Area - Start
             */
            function resizeChatUI(){
                var spaceForChat = $("#header").outerHeight() + $("#chat_ribbon").outerHeight() + $(".type_msg").outerHeight();
                var spaceForMember = spaceForChat + $(".headind_srch").outerHeight() - $(".type_msg").outerHeight();

                var toResizeChat = $(window).outerHeight() - spaceForChat;
                var toResizeMember = $(window).outerHeight() - spaceForMember;
                $(".msg_history").height(toResizeChat);
                $(".inbox_chat").height(toResizeMember);
                scrollBottomChat();
            }

            function scrollBottomChat(){
                $(".msg_history").animate({scrollTop : $(".msg_history")[0].scrollHeight}, 500);
            }

            resizeChatUI();

            $(window).resize(function(){
                resizeChatUI();
            });
            /**
             * resizing Area - End
             */

            /**
             * Chatting Area - Start
             */
            var INTERVAL_CHAT = 2000;
            var INTERVAL_MEMBER = 10000;
            var myId = "<?=AuthUtil::getLoggedInfo()->id?>";
            var lastIndex = 0;

            var semaphore = false;
            var lockPolling = false;

            function refresh(){
                if(lockPolling){
                    return;
                }
                if(semaphore){
                    return;
                }
                semaphore = true;
                callJson(
                    "/mygift/shared/public/route.php?F=ChatRoute.onPolling",
                    {
                        groupId : "<?=$_REQUEST["groupId"]?>",
                        lastIndex : lastIndex
                    },
                    function(data){
                        semaphore = false;
                        var arr = data.data;
                        var html = "";

                        if(arr.length == 0) return;

                        for(var e = 0; e < arr.length; e++){
                            html += makeSpeechBox(arr[e].id, arr[e].userId, arr[e].userName, arr[e].msg, arr[e].regDate);
                        }

                        $(".msg_history").append(html);
                        scrollBottomChat();
                    }
                );
            }

            var CONNECTION_TIMEOUT = 1000 * 60 * 10; // 10 Mins.
            var INTERVAL_CHECK = 5000;
            var timeout_gauge = 0;

            function pausePolling(flag){
                lockPolling = flag;
                timeout_gauge = 0;
                if(flag){
                    $("#chat_shader").fadeIn();
                }else{
                    $("#chat_shader").fadeOut();
                }
            }

            function onConnectionExpiry(){
                timeout_gauge += INTERVAL_CHECK;
                if(timeout_gauge >= CONNECTION_TIMEOUT){
                    pausePolling(true);
                }
            }

            setInterval(onConnectionExpiry, INTERVAL_CHECK);

            function startPolling(){
                setInterval(refresh, INTERVAL_CHAT);
                setInterval(loadMember, INTERVAL_MEMBER)
            }

            function makeSpeechBox(id, userId, userName, message, time){
                var html = $("#template_in").html();
                if(userId == myId) html = $("#template_out").html();

                html = html.replace("{#userId}", userId);
                html = html.replace("{#userName}", userName);
                html = html.replace("{#message}", message);
                html = html.replace("{#time}", time);

                lastIndex = id;

                return html;
            }

            function makeMemberBox(userId, userName){
                var html = $("#template_member").html();

                html = html.replace("{#userId}", userId);
                html = html.replace("{#userName}", userName);

                return html;
            }

            $(".jResumeChat").click(function(){
                pausePolling(false);
            });

            function sendMessage(){
                timeout_gauge = 0;
                var msg = $(".write_msg");
                callJson(
                    "/mygift/shared/public/route.php?F=ChatRoute.sendMessage",
                    {
                        groupId : "<?=$_REQUEST["groupId"]?>",
                        msg : msg.val()
                    },
                    function(data){
                        if(data.returnCode == 1){
                            msg.val("");
                            refresh();
                        }else{
                            showSnackBar(data.returnMessage);
                        }
                    }
                )
            }

            $(".msg_send_btn").click(function(){
                sendMessage();
            });

            $(".jGoOutChat").click(function(){
                callJson(
                    "/mygift/shared/public/route.php?F=ChatRoute.exitGroupForAjax",
                    {
                        userId : "<?=AuthUtil::getLoggedInfo()->id?>",
                        groupId : "<?=$_REQUEST["groupId"]?>"
                    },
                    function(data){
                        location.href = "post.php";
                    }
                )
            });

            $(".jGroupShow").click(function (e) {
                e.preventDefault();
                e.stopPropagation();
            });

            function loadMember(){
                if(lockPolling){
                    return;
                }
                callJson(
                    "/mygift/shared/public/route.php?F=ChatRoute.getMemberList",
                    {groupId : "<?=$_REQUEST["groupId"]?>"},
                    function(data){
                        var arr = data.data;
                        var html = "";
                        for(var e = 0; e < arr.length; e++){
                            html += makeMemberBox(arr[e].id, arr[e].name);
                        }

                        $(".inbox_chat").html(html);
                    }
                )
            }

            function initChat(){
                loadMember();
                callJson(
                    "/mygift/shared/public/route.php?F=ChatRoute.getLastestMessage",
                    {groupId : "<?=$_REQUEST["groupId"]?>"},
                    function(data){
                        var arr = data.data;
                        var html = "";
                        for(var e = 0; e < arr.length; e++){
                            html += makeSpeechBox(arr[e].id, arr[e].userId, arr[e].userName, arr[e].msg, arr[e].regDate);
                        }

                        $(".msg_history").html(html);

                        scrollBottomChat();
                        startPolling();
                    }
                );
            }

            initChat();

            /**
             * Chatting Area - End
             */

            buttonLink(".jGoPost", "post.php");
        });
    </script>

    <div id="chat_shader" class="align-center">
        <div class="align-center" style="margin-top: 40vh;">
            <p style="color:black; font-size: 14px;">일정 시간 동안 명령이 없어 연결이 중지되었습니다.</p>
            <a href="#" class="button primary jResumeChat">재연결</a>
        </div>
    </div>

    <!-- Template Area - Start -->
    <div id="template_in" style="display: none;">
        <div class="incoming_msg">
            <p class="sender_name jUserTag" userId="{#userId}">{#userName}</p>
            <div class="received_msg">
                <div class="received_withd_msg">
                    <p>{#message}</p>
                    <span class="time_date">{#time}</span></div>
            </div>
        </div>
    </div>
    <div id="template_out" style="display: none;">
        <div class="outgoing_msg">
            <div class="sent_msg">
                <p>{#message}</p>
                <span class="time_date">{#time}</span></div>
        </div>
    </div>
    <div id="template_member" style="display: none;">
        <div class="chat_list">
            <div class="chat_people">
                <div class="chat_ib">
                    <h5 class="icon fa-user jUserTag" userId="{#userId}">&nbsp;{#userName}</h5>
                </div>
            </div>
        </div>
    </div>
    <!-- Template Area - End -->

    <section id="chat_ribbon" class="wrapper style2 special fade" style="padding:0;">
        <div class="container" style="padding: 10px;">
            <div class="col-12 align-left">
                <h5 style="margin:0;"><i class="button small jGroupShow" style="font-style: normal;"><i class="icon fa-comment"></i> <?=$group["groupName"]?></i>&nbsp;&nbsp;
                    <i class="icon fa-list jGoPost" style="font-style: normal;"> 목록</i>&nbsp;|&nbsp;
                    <i class="icon fa-sign-out jGoOutChat" style="font-style: normal;"> 나가기</i>
                </h5>
            </div>
        </div>
    </section>

    <div class="chat_container">
        <div class="messaging">
            <div class="inbox_msg">
                <div class="inbox_people">
                    <div class="headind_srch">
                        <div class="recent_heading">
                            <h4>멤버</h4>
                        </div>
                    </div>
                    <div class="inbox_chat">
                    </div>
                </div>
                <div class="mesgs">
                    <div class="msg_history">
                    </div>
                    <div class="type_msg">
                        <div class="input_msg_write">
                            <textarea class="write_msg" placeholder="메시지를 입력하세요" ></textarea>
                            <button class="msg_send_btn" type="button"><i class="fa fa-paper-plane" aria-hidden="true"></i></button>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

<? include_once $_SERVER["DOCUMENT_ROOT"]."/mygift/inc/footer.php"; ?>