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
    $user = $uRoute->getUser(AuthUtil::getLoggedInfo()->id);
    $pUnit = $pRoute->getPoint($user["id"]);

    $greetingArr = array(
            "안녕하세요!", "반가워요!", "좋은 하루 보내고 계신가요?", "안녕하세요 :)"
    );

    $greeting = $greetingArr[mt_rand(0, sizeof($greetingArr) - 1)];
?>

    <script>
        $(document).ready(function(){
            buttonLink(".jGoBalance", "balance.php");
            buttonLink(".jModifyStore", "profile_u.php");
            buttonLink(".jModifyMajor", "profile_m.php");

            $(document).on("click", ".browse", function(){
                var idx = $(this).attr("idx");
                var file = $(this).parents().find(".file").eq(idx);
                file.trigger("click");
            });

            $(document).on("click", ".browseThumb", function(){
                $("#thumbFile").trigger("click");
            });

            $('input.imgFile[type=file]').change(function(e){
                var idx = $(this).attr("idx");
                var fileName = e.target.files[0].name;
                $("#file" + idx).val(fileName);

                var reader = new FileReader();
                reader.onload = function(e){
                    $("#preview" + idx).attr("src", e.target.result);
                    $("#preview" + idx).fadeIn();
                };
                reader.readAsDataURL(this.files[0]);
            });

            $(".jSave").click(function(){
                alert();
            });
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

                                <h4 class="button fit"><?=$user["email"]?></h4>
                                <h5 class="button fit jGoBalance"><i class="icon fa-database"></i> 내 포인트 : <?=$pUnit?>P</h5>

                                <form method="post" action="#">
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
                                            </div>
                                        </div>

                                        <div class="col-12 col-12-xsmall">
                                            <input type="email" name="phone" id="phone" value="<?=$user["phone"]?>" placeholder="휴대전화" />
                                        </div>

                                        <div class="col-12 col-12-xsmall">
                                            <label for="jAge">나이</label>
                                            <input class="jAge" type="text" name="age" placeholder="<?=$user["age"]?>" />
                                        </div>

                                        <div class="col-12 col-12-xsmall">
                                            <label for="jSex">성별</label>
                                            <select class="jSex">
                                                <option value="">선택</option>
                                                <option value="1" <?=$user["sex"] == 1 ? "selected" : ""?>>남자</option>
                                                <option value="0" <?=$user["sex"] == 0 ? "selected" : ""?>>여자</option>
                                            </select>
                                        </div>

                                        <div class="col-12 align-center">
                                            <a href="#" class="jSave button icon fa-sign-in small">저장하기</a>
                                        </div>
                                    </div>
                                </form>
							</section>

					</div>
				</div>

			<!-- Footer -->
<? include_once $_SERVER["DOCUMENT_ROOT"]."/mygift/inc/footer.php"; ?>