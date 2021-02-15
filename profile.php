<? include_once $_SERVER["DOCUMENT_ROOT"]."/mygift/inc/header.php"; ?>
<? include_once $_SERVER["DOCUMENT_ROOT"]."/mygift/shared/public/classes/PayRoute.php"; ?>
<? include_once $_SERVER["DOCUMENT_ROOT"]."/mygift/shared/public/classes/StoreRoute.php"; ?>
<? include_once $_SERVER["DOCUMENT_ROOT"]."/mygift/shared/public/classes/UserAuthRoute.php"; ?>
<?
    if(!AuthUtil::isLoggedIn()){
        echo "<script>alert('비정상적인 접근입니다.'); location.href='index.php';</script>";
    }

    $uRoute = new UserAuthRoute();
    $pRoute = new PayRoute();
    $sRoute = new StoreRoute();
    $user = $uRoute->getUserNPic(AuthUtil::getLoggedInfo()->id);
    $profile = $user[1];
    $user = $user[0];
    $pUnit = $pRoute->getPoint($user["id"]);



    $greetingArr = array(
            "안녕하세요!", "반가워요!", "좋은 하루 보내고 계신가요?", "안녕하세요 :)"
    );

    $greeting = $greetingArr[mt_rand(0, sizeof($greetingArr) - 1)];
?>

        <script src="shared/lib/polyfill/es6-promise.auto.min.js"></script>
        <script src="shared/lib/polyfill/browser-polyfill.min.js"></script>
        <script src="shared/lib/filepond/filepond-polyfill.js"></script>
        <script src="shared/lib/polyfill/babel.min.js"></script>
    <script>
        $(document).ready(function(){
            buttonLink(".jGoBalance", "balance.php");
            buttonLink(".jModifyStore", "profile_u.php");
            buttonLink(".jModifyMajor", "profile_m.php");
            let idx = <?=$profile != "" ? 1 + sizeof($profile) : 1?>;

            $(document).on("click", ".browse", function(){
                var idx = $(this).attr("idx");
                console.log(idx);
                console.log($(this).parents().find(".file").eq(idx).html());
                var file = $(this).parents().find(".file").eq(idx);
                file.trigger("click");
            });

            $(document).on("click", ".browseThumb", function(){
                $("#thumbFile").trigger("click");
            });

            $(".browseAuth").click(() => {
                $("#authFile").trigger("click");
            });

            $(document).on("change", "input.authImg[type=file]", (event) => {
                var fileName = event.target.files[0].name;
                console.log(fileName);

                if(fileName != null){
                    var data = new FormData($("#userForm")[0]);
                    $.ajax({
                        type: "POST",
                        enctype: 'multipart/form-data',
                        url : "/mygift/shared/public/route.php?F=RecognitionRoute.authImg",
                        cache : false,
                        async : true,
                        method : "post",
                        dataType : "json",
                        contentType: false,
                        processData: false,
                        data : data,
                        success : function(data){
                            console.log("[AJAX RESPONSE] " + data);
                            var retData = JSON.parse(data);
                            if(retData.returnCode > 0){
                                if(retData.returnCode > 1){
                                }else{
                                    Swal.fire({
                                        title: "알림",
                                        text: retData.returnMessage,
                                        icon: "success",
                                        closeOnClickOutside: false,
                                    }).then((result) => {
                                        if(result) location.reload();
                                    });
                                }
                            }else{
                                Swal.fire("알림" ,  "오류가 발생하였습니다.\n관리자에게 문의하세요.", "error");
                            }
                        },
                        error : function(req, stat, err){
                            console.log("[AJAX ERROR] REQUEST : " + req + " / STATUS : " + stat + " / ERROR : " + err);
                        }
                    });
                }
            });

            $(document).on("change", "input.imgFile[type=file]", (event) => {
                var idx = $(event.target).attr("idx");
                console.log(event.target);
                var fileName = event.target.files[0].name;
                console.log(fileName);
                $("#file" + idx).val(fileName);

                var reader = new FileReader();
                reader.onload = (event) => {
                    $("#preview" + idx).attr("src", event.target.result);
                    $("#preview" + idx).fadeIn();
                };
                reader.readAsDataURL(event.target.files[0]);
            })
            // $('input.imgFile[type=file]').change(function(e){
            //
            // });

            $(".jSave").click(function(){
                var data = new FormData($("#userForm")[0]);
                data.append("sex", $(".jSex option:selected").val());

                $.ajax({
                    type: "POST",
                    enctype: 'multipart/form-data',
                    url : "/mygift/shared/public/route.php?F=UserAuthRoute.updateUser",
                    cache : false,
                    async : true,
                    method : "post",
                    dataType : "json",
                    contentType: false,
                    processData: false,
                    data : data,
                    success : function(data){
                        console.log("[AJAX RESPONSE] " + data);
                        var retData = JSON.parse(data);
                        if(retData.returnCode > 0){
                            if(retData.returnCode > 1){
                            }else{
                                Swal.fire({
                                    title: "알림",
                                    text: retData.returnMessage,
                                    icon: "success",
                                    closeOnClickOutside: false,
                                }).then((result) => {
                                    if(result) location.reload();
                                });
                            }
                        }else{
                            Swal.fire("알림" ,  "오류가 발생하였습니다.\n관리자에게 문의하세요.", "error");
                        }
                    },
                    error : function(req, stat, err){
                        console.log("[AJAX ERROR] REQUEST : " + req + " / STATUS : " + stat + " / ERROR : " + err);
                    }
                });
            });

            $(".jToggle").click(function(event){
                event.preventDefault();
                console.log($(this).html());
                if($(this).hasClass("primary")) $(this).removeClass("primary");
                else $(this).addClass("primary");
            });

            $(".jAdd").click(() => {
                if(idx === 4){
                    swal.fire("알림", "사진은 최대 4장까지만 추가 가능합니다.", "error");
                    return;
                }
                let template = $("#jTemplate").html();
                template = template.replace(/#{idx}/gi, idx);
                template = template.replace(/#{fileName}/gi, "");
                $(".jTarg").append(template);
                idx++;
            })
        });
    </script>
			<!-- Main -->
				<div id="main" class="wrapper style1">
					<div class="container">
						<header class="major">
							<h2>마이페이지</h2>
						</header>

						<!-- Content -->
							<section id="content">
                                <h4><?=$user["name"]?>님, <?=$greeting?></h4>
                                <?if($user["isAuthorized"]){?>
                                    <button type="button" class="button primary" style="margin-bottom: 1rem; background-color: forestgreen">인증 회원</button>
                                <?}else{?>
                                    <button type="button" class="browseAuth button primary" style="margin-bottom: 1rem;">인증하기</button>
                                    <b> &nbsp;&nbsp;* 본인의 셀카 사진을 업로드하여 인증해주세요!</b>
                                <?}?>

                                <h4 class="button fit"><?=$user["email"]?></h4>
                                <h5 class="button fit jGoBalance"><i class="icon fa-database"></i> 내 포인트 : <?=$pUnit?>P</h5>

                                <form id="userForm" method="post" enctype="multipart/form-data">
                                    <input type="hidden" name="id" value="<?=$user["id"]?>"/>
                                    <input type="hidden" name="thumbId" value="<?=$user["thumbId"]?>"/>
                                    <input type="file" name="authImg[]" class="file authImg" id="authFile" accept="image/*" style="display: none;">
                                    <div class="row gtr-uniform gtr-50" style="margin-top: 1.0rem">
                                        <div class="col-4 col-12-xsmall" style="margin-bottom: 0.5rem">
                                            <img src="<?="/mygift/shared/public/route.php?F=FileRoute.downloadFileById&id=" . $user["thumbId"]?>" id="preview0" class="img-thumbnail text-center" style="width: 100%; display: <?=$user["thumbId"] ? "" : "none"?>"/>
                                        </div>

                                        <div class="col-12 col-12-xsmall">
                                            <label for="file0">프로필 사진</label>
                                            <input type="file" name="img[]" class="file imgFile" idx="0" accept="image/*" style="display: none;">
                                            <input type="text" class="form-control jImg" disabled placeholder="<?=$user["thumbId"] ? $user["thumbName"] : "업로드 파일명"?>" id="file0" />
                                        </div>
                                        <div class="col-12 col-12-xsmall" style="margin-bottom: 1.0rem">
                                            <div align="center">
                                                <button type="button" class="browse button primary" idx="0">파일 선택</button>
                                                <button type="button" class="button jAdd">+</button>
                                            </div>
                                        </div>

                                        <div class="jTarg col-12 col-12-xsmall">
                                            <?if($profile != ""){
                                                $idx = 0;
                                                ?>
                                                <?foreach($profile as $item){
                                                    $idx++;
                                                    ?>
                                                    <div class="col-4 col-12-xsmall" style="margin-bottom: 0.5rem">
                                                        <img src="/mygift/shared/public/route.php?F=FileRoute.downloadFileById&id=<?=$item["id"]?>" id="preview<?=$idx?>" class="img-thumbnail text-center" style="width: 100%"/>
                                                    </div>

                                                    <div class="col-12 col-12-xsmall">
                                                        <label for="file0">추가 사진</label>
                                                        <input type="file" name="imgAdd[]" class="file imgFile" idx="<?=$idx?>" accept="image/*" style="display: none;">
                                                        <input type="text" class="form-control jImg" disabled placeholder="<?=$item["originName"]?>" id="<?=$idx?>" />
                                                    </div>
                                                    <div class="col-12 col-12-xsmall" style="margin-bottom: 1.0rem">
                                                        <div align="center">
                                                            <button type="button" class="browse button primary" idx="<?=$idx?>" style="margin-top:1.0rem">파일 선택</button>
                                                        </div>
                                                    </div>
                                                <?}?>
                                            <?}?>
                                        </div>

                                        <div class="col-12 col-12-xsmall">
                                            <input type="email" name="phone" id="phone" value="<?=$user["phone"]?>" placeholder="휴대전화" />
                                        </div>

                                        <div class="col-12 col-12-xsmall">
                                            <label for="jAge">나이</label>
                                            <input class="jAge" type="text" name="age" value="<?=$user["age"]?>" placeholder="나이" />
                                        </div>

                                        <div class="col-12 col-12-xsmall">
                                            <label for="jSex">성별</label>
                                            <select class="jSex">
                                                <option value="-1" <?=$user["sex"] == -1 ? "selected" : ""?>>선택</option>
                                                <option value="1" <?=$user["sex"] == 1 ? "selected" : ""?>>남자</option>
                                                <option value="0" <?=$user["sex"] == 0 ? "selected" : ""?>>여자</option>
                                            </select>
                                        </div>

                                        <div class="row">
                                            <div class="col-12 col-12-xsmall align-center">
                                                <a href="#" class="button small jToggle" style="border-radius: 10px; margin-left: 0.2rem; margin-top: 0.2rem" value="INTJ">#INTJ</a>
                                                <a href="#" class="button small jToggle" style="border-radius: 10px; margin-left: 0.2rem; margin-top: 0.2rem" value="INTP">#INTP</a>
                                                <a href="#" class="button small jToggle" style="border-radius: 10px; margin-left: 0.2rem; margin-top: 0.2rem" value="ENTJ">#ENTJ</a>
                                                <a href="#" class="button small jToggle" style="border-radius: 10px; margin-left: 0.2rem; margin-top: 0.2rem" value="ENTP">#ENTP</a>
                                                <a href="#" class="button small jToggle" style="border-radius: 10px; margin-left: 0.2rem; margin-top: 0.2rem" value="ISTJ">#ISTJ</a>
                                                <a href="#" class="button small jToggle" style="border-radius: 10px; margin-left: 0.2rem; margin-top: 0.2rem" value="ESTJ">#ESTJ</a>
                                                <a href="#" class="button small jToggle" style="border-radius: 10px; margin-left: 0.2rem; margin-top: 0.2rem" value="ESFJ">#ESFJ</a>
                                                <a href="#" class="button small jToggle" style="border-radius: 10px; margin-left: 0.2rem; margin-top: 0.2rem" value="ISFJ">#ISFJ</a>
                                                <a href="#" class="button small jToggle" style="border-radius: 10px; margin-left: 0.2rem; margin-top: 0.2rem" value="ESTP">#ESTP</a>
                                                <a href="#" class="button small jToggle" style="border-radius: 10px; margin-left: 0.2rem; margin-top: 0.2rem" value="ISTP">#ISTP</a>
                                                <a href="#" class="button small jToggle" style="border-radius: 10px; margin-left: 0.2rem; margin-top: 0.2rem" value="ESFP">#ESFP</a>
                                                <a href="#" class="button small jToggle" style="border-radius: 10px; margin-left: 0.2rem; margin-top: 0.2rem" value="ISFP">#ISFP</a>
                                                <a href="#" class="button small jToggle" style="border-radius: 10px; margin-left: 0.2rem; margin-top: 0.2rem" value="ENFJ">#ENFJ</a>
                                                <a href="#" class="button small jToggle" style="border-radius: 10px; margin-left: 0.2rem; margin-top: 0.2rem" value="INFJ">#INFJ</a>
                                                <a href="#" class="button small jToggle" style="border-radius: 10px; margin-left: 0.2rem; margin-top: 0.2rem" value="INFP">#INFP</a>
                                                <a href="#" class="button small jToggle" style="border-radius: 10px; margin-left: 0.2rem; margin-top: 0.2rem" value="ENFP">#ENFP</a>
<!--                                                --><?//for($i=0; $i<=27; $i++){?>
<!--                                                    <a href="#" class="button small jToggle" style="border-radius: 10px; margin-left: 0.2rem; margin-top: 0.2rem">#활발함</a>-->
<!--                                                --><?//}?>
                                            </div>
                                        </div>
                                        <div class="col-12 align-center">
                                            <a href="#" class="jSave button icon fa-sign-in small">저장하기</a>
                                        </div>
                                    </div>
                                </form>
							</section>

					</div>
				</div>

                <div id="jTemplate" style="display: none">
                    <div class="col-4 col-12-xsmall" style="margin-bottom: 0.5rem">
                        <img src="/mygift/shared/public/route.php?F=FileRoute.downloadFileById&id=#{fileId}" id="preview#{idx}" class="img-thumbnail text-center" style="width: 100%; display: none"/>
                    </div>

                    <div class="col-12 col-12-xsmall">
                        <label for="file0">추가 사진</label>
                        <input type="file" name="imgAdd[]" class="file imgFile" idx="#{idx}" accept="image/*" style="display: none;">
                        <input type="text" class="form-control jImg" disabled placeholder="#{fileName}" id="file#{idx}" />
                    </div>
                    <div class="col-12 col-12-xsmall" style="margin-bottom: 1.0rem">
                        <div align="center">
                            <button type="button" class="browse button primary" idx="#{idx}">파일 선택</button>
                        </div>
                    </div>
                </div>

			<!-- Footer -->
<? include_once $_SERVER["DOCUMENT_ROOT"]."/mygift/inc/footer.php"; ?>