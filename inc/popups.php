<style>

    .popup-shade{
        background-color: rgba(0, 0, 0, 0.5);
        position : fixed;
        top : 0;
        left : 0;
        width: 100vw;
        height: 100vh;
        z-index: 9998;
        display: none;
    }

    .popup-medium{
        border : 1px solid white;
        width : 400px;
        height : auto;
        position : fixed;
        top : 20vh;
        left : calc(50% - 200px);
        z-index: 9999;
        border-radius: 5px;
        background-color: #272833;
    }

    .popup-medium .content-area{
        padding : 10px 10px;
    }

    .popup-medium .content-area p{
        margin : 5px 0px;
        font-size: 14px;
    }

    .popup-medium .content-area h4{
        margin : 5px 0px;
        font-size: 14px;
        text-align: left;
    }

    .popup-medium .ribbon-bar{
        /*background-color: #e44c65;*/
        border-bottom: 1px solid white;
    }

    .popup-medium .ribbon-bar .popup-button{
        position: absolute;
        top : 10px;
        right : 15px;
    }

    .popup-medium .ribbon-bar p{
        text-align: left;
        margin : 10px;
        font-size: 12px;
    }

    @media screen and (max-width: 1280px) {
        .popup-medium {
            width : 400px;
            left : calc(50% - 200px);
        }
    }

    @media screen and (max-width: 980px) {
        .popup-medium {
            width : 400px;
            left : calc(50% - 200px);
        }
    }

    @media screen and (max-width: 720px){
        .popup-medium {
            width : 380px;
            left : calc(50% - 190px);
        }
    }

    @media screen and (max-width: 400px){
        .popup-medium {
            width : 80%;
            left : 10%;
        }
    }

</style>

<script>
    $(document).ready(function(){
        $(".pop-closer").click(function(e){
            e.preventDefault();
            $(this).parent().parent().parent().fadeOut();
        });

        $(".popup-shade").click(function(e){
            e.stopPropagation();
            $(this).fadeOut();
        });

        $(".popup-medium").click(function(e){
            e.stopPropagation();
        });

        var popupUserInfo = $(".jUserInfo");

        function showUserInfo(userId){
            callJson(
                "/mygift/shared/public/route.php?F=WebRoute.getUserProfile",
                {id : userId},
                function(data){
                    var row = data.data;
                    $(".userNameForm").text(row.name);
                    $(".userEmailForm").text(row.email);
                    $(".userEmailForm").attr("email", row.email);
                    $(".jStartChat").attr("userId", row.id);
                    if(row.univId == 0){
                        $(".userUnivForm").text("미설정");
                    }else{
                        $(".userUnivForm").text(row.univName);
                    }
                    if(row.deptId == 0){
                        $(".userDeptForm").text("미설정");
                    }else{
                        $(".userDeptForm").text(row.deptName);
                    }
                    popupUserInfo.fadeIn();
            });
        }

        $(".jStartChat").click(function(e){
            e.preventDefault();
            e.stopPropagation();
            callJson(
                "/mygift/shared/public/route.php?F=ChatRoute.createGroupForTwo",
                {userId : $(this).attr("userId")},
                function(data){
                    if(data.returnCode == 1){
                        location.href = "chat.php?groupId=" + data.data;
                    }else{
                        swal("알림", data.returnMessage, "error");
                    }
                }
            );

        });

        $(".userEmailForm").click(function(e){
            e.preventDefault();
            location.href = "mailto:" + $(this).attr("email");
        });

        $(document).on("click", ".jUserTag", function(e){
            e.preventDefault();
            var id = $(this).attr("userId");
            showUserInfo(id);
        });
    });
</script>

<div class="popup-shade jUserInfo">
    <div class="align-center popup-medium">
        <div class="ribbon-bar">
            <p><i class="icon fa-user"></i> &nbsp;사용자 프로필</p>
            <i class="popup-button icon fa-times pop-closer"></i>
        </div>
        <div class="content-area">
            <h4><i class="icon fa-info"></i> &nbsp;사용자</h4>
            <p class="button fit icon fa-user userNameForm">사용자 성명</p>
            <h4><i class="icon fa-info"></i> &nbsp;계정 정보</h4>
            <p class="button fit icon fa-envelope userEmailForm" email="">사용자 이메일</p>
            <h4><i class="icon fa-info"></i> &nbsp;학교 정보</h4>
            <p class="button fit icon fa-university userUnivForm">사용자 등록 학교</p>
            <h4><i class="icon fa-info"></i> &nbsp;학과/전공 정보</h4>
            <p class="button fit icon fa-book userDeptForm">사용자 등록 학과/전공</p>
            <p class="button fit primary icon fa-comment jStartChat" userId="">메시지 보내기</p>
        </div>
    </div>
</div>
