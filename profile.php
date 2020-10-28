<? include_once $_SERVER["DOCUMENT_ROOT"]."/mygift/inc/header.php"; ?>
<? include_once $_SERVER["DOCUMENT_ROOT"]."/mygift/shared/public/classes/PayRoute.php"; ?>
<? include_once $_SERVER["DOCUMENT_ROOT"]."/mygift/shared/public/classes/StoreRoute.php"; ?>
<?
if(!AuthUtil::isLoggedIn()){
    echo "<script>alert('비정상적인 접근입니다.'); location.href='index.php';</script>";
}

$pRoute = new PayRoute();
$uRoute = new StoreRoute();
$pUnit = $pRoute->getPoint(AuthUtil::getLoggedInfo()->id);

$univ = "";
$major = "";
if(AuthUtil::getLoggedInfo()->univId != "0"){
    $univ = $uRoute->getStore(AuthUtil::getLoggedInfo()->univId);
}else{
    // Do nothing
}

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
        });
    </script>
			<!-- Main -->
				<div id="main" class="wrapper style1">
					<div class="container">
						<header class="major">
							<h2>마이페이지</h2>
						</header>

                        <?
                        $uvText = "학교 선택하기";
                        $mjText = "학과/전공 선택하기(학교에 관계없이 선택 가능)";
                        if($univ != ""){
                            $uvText = $univ["title"]."(".$univ["campusType"].")";
                        }
                        if($major != ""){
                            $mjText = $major["deptName"];
                        }
                        ?>
						<!-- Content -->
							<section id="content">
                                <h4><?=AuthUtil::getLoggedInfo()->name?>님, <?=$greeting?></h4>
                                <h4 class="button fit"><?=AuthUtil::getLoggedInfo()->email?></h4>
                                <h5 class="button fit jModifyStore"><i class="icon fa-university"></i> <?=$uvText?></h5>
                                <h5 class="button fit jModifyMajor"><i class="icon fa-book"></i> <?=$mjText?></h5>
                                <h5 class="button fit jGoBalance"><i class="icon fa-database"></i> 내 포인트 : <?=$pUnit?>P</h5>

                                <form method="post" action="#">
                                    <div class="row gtr-uniform gtr-50">
                                        <div class="col-12 col-12-xsmall">
                                            <input class="jEmailTxt" type="email" name="email" id="email" value="" placeholder="이메일" />
                                        </div>
                                        <div class="col-12 col-12-xsmall">
                                            <input class="jPasswordTxt" type="password" name="password" id="password" value="" placeholder="패스워드" />
                                        </div>
                                        <div class="col-12 align-center">
                                            <a href="#" class="jLogin button primary icon fa-sign-in small">이메일로 로그인</a>
                                            <a href="join.php" class="button icon fa-edit small">회원가입</a>
                                        </div>
                                        <div class="col-12 col-12-xsmall align-center">
                                            <a href="#" class="facebook button icon small fa-facebook">Facebook으로 로그인</a>
                                        </div>
                                    </div>
                                </form>
							</section>

					</div>
				</div>

			<!-- Footer -->
<? include_once $_SERVER["DOCUMENT_ROOT"]."/mygift/inc/footer.php"; ?>