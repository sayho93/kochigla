<? include_once $_SERVER["DOCUMENT_ROOT"]."/mygift/inc/header.php"; ?>
<?
//$showOff = $route->getShowOffValue();
$newsList = $route->getTopNewsList();
?>
    <script>
        $(document).ready(function(){
            if("<?=$_REQUEST["msg"] != ""?>"){
                alert("<?=$_REQUEST["msg"]?>");
                location.href="index.php";
            }

            <?if(AuthUtil::isLoggedIn()){?>
            if("<?=AuthUtil::getLoggedInfo()->univId?>" == "0"){
                showSnackBar("학교를 등록하고 맞춤형 서비스를 받아보세요!");
            }
            <?}?>
        });
    </script>
    <!-- Banner -->
    <section id="banner">
        <div class="content">
            <header>
                <h2>여행 속 동행을 구해보세요!</h2>
                <p>당신이 빛나던 순간을 함께할<br />
                    나만의 여행 동행을 구해 보세요!</p>
            </header>
<!--            <span class="image"><img src="images/main_icon.png" alt="" /></span>-->
            <div class="container" style="margin:0;">
                <header>
                </header>
            </div>
            <div class="row gtr-uniform gtr-50">
                <?if(!AuthUtil::isLoggedIn()){?>
                    <div class="col-12 col-12-xsmall">
                        <a href="join.php" class="button icon fa-pencil small">간편 회원가입</a>
                    </div>
                <?}else{?>
                    <div class="col-12 col-12-xsmall">
                        <a href="profile.php" class="button icon fa-user small">마이페이지</a>
                        <a href="data.php" class="button icon fa-list small">동행 찾아보기</a>
                    </div>
                <?}?>
            </div>
        </div>
        <a href="#one" class="goto-next scrolly">Next</a>
    </section>
    <section id="one" class="wrapper style2 special fade-up" style="padding:20px 10px;">
        <div class="container">
            <header class="">
                <?
                $loggedName = "반가워요 :)";
                $loggedMsg = "로그인하고 맞춤 서비스를 받아보세요!";
                if(AuthUtil::isLoggedIn()){
                    $greetingArr = array(
                        "기분 좋은 하루되세요! :)",
                        "좋은 하루 보내고 계시죠?!",
                        "오늘도 기분 좋은 하루 보내세요 :)",
                        "좋은 하루 보내고 계신가요?!",
                        "오늘도 좋은 하루되세요!"
                    );

                    $greeting = $greetingArr[mt_rand(0, sizeof($greetingArr) - 1)];

                    $loggedName = "안녕하세요, ".AuthUtil::getLoggedInfo()->name."님!";
                    $loggedMsg = $greeting;
                }
                ?>
                <h3><?=$loggedName?></h3>
                <p style="margin-bottom: 0;"><?=$loggedMsg?></p>
                <br/>
                <?if(AuthUtil::isLoggedIn()){?>
                    <p style="margin-bottom: 0;"><a href="profile.php" class="button primary icon fa-sign-in small">마이페이지로 이동</a></p>
                <?}else{?>
                    <p style="margin-bottom: 0;"><a href="login.php" class="button primary icon fa-sign-in small">로그인 페이지로 이동</a></p>
                <?}?>
            </header>
        </div>
    </section>
    <section id="two" class="wrapper style1 special fade-up" style="padding: 35px 10px;">
        <div class="container">
            <?
            if(AuthUtil::isLoggedIn()){
            ?>
            <div class="box alt">
                <div class="row gtr-uniform">
                    <?foreach($newsList as $item){
                        ?>
                        <?
                        $uB = "";
                        if($item["uId"] != "0") {
                            $uB = " - ".$item["uBelong"];
                        }?>


                        <section class="col-2 col-4-medium col-6-xsmall">
                            <span style="margin:0;" class="icon alt major fa-comment"></span>
                            <h5 style="margin:5px 0;" class="jUserTag" userId="<?=$item["userId"]?>"><i class="icon fa-user"></i> <?=$item["userName"].$uB?></h5>
                            <p style="margin:0;"><?=$item["message"]?></p>
                            <?if($item["univId"] > 0){?>
                                <p style="margin:0;font-size: 12px;">
                                    @<a style="color:white;" href="news.php?univId=<?=$item["univId"]?>"><?=$item["uName"]?></a>
                                </p>
                            <?}else{?>
                                <p style="margin:0;font-size: 12px;">
                                    @<a style="color:white;" href="news.php?univId=0">전체</a>
                                </p>
                            <?}?>
                            <p style="margin:0;font-size: 12px;"><i class="icon fa-clock-o"></i> <?=$route::toRelativeTime($item["tt"])?></p>
                        </section>
                    <?}?>
                </div>
            </div>
            <?}else{?>
                <div>
                    <h3>아직 회원이 아니신가요?!</h3>
                    <p><a href="join.php" class="button primary icon fa-pencil small">회원가입</a></p>
                </div>
            <?}?>
        </div>
        <hr style="margin-bottom: 0;" />
    </section>

    <section id="three" class="wrapper style1 special fade-up" style="padding-top: 20px;">
        <div class="container">
            <header class="major">
                <h2>바로 지금!</h2>
                <p>매일매일 새로운 소식을 만나보세요!</p>
            </header>
            <div class="box alt">
                <div class="row gtr-uniform">
                    <?foreach($newsList as $item){
                        ?>
                        <?
                        $uB = "";
                        if($item["uId"] != "0") {
                            $uB = " - ".$item["uBelong"];
                        }?>
                        <section class="col-2 col-4-medium col-6-xsmall">
                            <span style="margin:0;" class="icon alt major fa-comment"></span>
                            <h5 style="margin:5px 0;" class="jUserTag" userId="<?=$item["userId"]?>"><i class="icon fa-user"></i> <?=$item["userName"].$uB?></h5>
                            <p style="margin:0;"><?=$item["message"]?></p>
                            <?if($item["univId"] > 0){?>
                                <p style="margin:0;font-size: 12px;">
                                    @<a style="color:white;" href="news.php?univId=<?=$item["univId"]?>"><?=$item["uName"]?></a>
                                </p>
                            <?}else{?>
                                <p style="margin:0;font-size: 12px;">
                                    @<a style="color:white;" href="news.php?univId=0">전체</a>
                                </p>
                            <?}?>
                            <p style="margin:0;font-size: 12px;"><i class="icon fa-clock-o"></i> <?=$route::toRelativeTime($item["tt"])?></p>
                        </section>
                    <?}?>
                </div>
            </div>
            <footer class="major">
                <ul class="actions special">
                    <li><a href="news.php" class="button icon fa-list">뉴스피드 더보기</a></li>
                </ul>
            </footer>
        </div>
    </section>

    <!-- Three -->
<!--    <section id="three" class="spotlight style3 left">-->
<!--        <span class="image fit main bottom"><img src="images/pic04.jpg" alt="" /></span>-->
<!--        <div class="content">-->
<!--            <header>-->
<!--                <h2>Interdum felis blandit praesent sed augue</h2>-->
<!--                <p>Accumsan integer ultricies aliquam vel massa sapien phasellus</p>-->
<!--            </header>-->
<!--            <p>Feugiat accumsan lorem eu ac lorem amet ac arcu phasellus tortor enim mi mi nisi praesent adipiscing. Integer mi sed nascetur cep aliquet augue varius tempus lobortis porttitor lorem et accumsan consequat adipiscing lorem.</p>-->
<!--            <ul class="actions">-->
<!--                <li><a href="#" class="button">Learn More</a></li>-->
<!--            </ul>-->
<!--        </div>-->
<!--        <a href="#four" class="goto-next scrolly">Next</a>-->
<!--    </section>-->

    <!-- Four -->
    <section id="four" class="wrapper style1 special fade-up">
        <div class="container">
            <header class="major">
                <h2>Accumsan sed tempus adipiscing blandit</h2>
                <p>Iaculis ac volutpat vis non enim gravida nisi faucibus posuere arcu consequat</p>
            </header>
            <div class="box alt">
                <div class="row gtr-uniform">
                    <section class="col-4 col-6-medium col-12-xsmall">
                        <span class="icon alt major fa-area-chart"></span>
                        <h3>Ipsum sed commodo</h3>
                        <p>Feugiat accumsan lorem eu ac lorem amet accumsan donec. Blandit orci porttitor.</p>
                    </section>
                    <section class="col-4 col-6-medium col-12-xsmall">
                        <span class="icon alt major fa-comment"></span>
                        <h3>Eleifend lorem ornare</h3>
                        <p>Feugiat accumsan lorem eu ac lorem amet accumsan donec. Blandit orci porttitor.</p>
                    </section>
                    <section class="col-4 col-6-medium col-12-xsmall">
                        <span class="icon alt major fa-flask"></span>
                        <h3>Cubilia cep lobortis</h3>
                        <p>Feugiat accumsan lorem eu ac lorem amet accumsan donec. Blandit orci porttitor.</p>
                    </section>
                    <section class="col-4 col-6-medium col-12-xsmall">
                        <span class="icon alt major fa-paper-plane"></span>
                        <h3>Non semper interdum</h3>
                        <p>Feugiat accumsan lorem eu ac lorem amet accumsan donec. Blandit orci porttitor.</p>
                    </section>
                    <section class="col-4 col-6-medium col-12-xsmall">
                        <span class="icon alt major fa-file"></span>
                        <h3>Odio laoreet accumsan</h3>
                        <p>Feugiat accumsan lorem eu ac lorem amet accumsan donec. Blandit orci porttitor.</p>
                    </section>
                    <section class="col-4 col-6-medium col-12-xsmall">
                        <span class="icon alt major fa-lock"></span>
                        <h3>Massa arcu accumsan</h3>
                        <p>Feugiat accumsan lorem eu ac lorem amet accumsan donec. Blandit orci porttitor.</p>
                    </section>
                </div>
            </div>
            <footer class="major">
                <ul class="actions special">
                    <li><a href="#" class="button">Magna sed feugiat</a></li>
                </ul>
            </footer>
        </div>
    </section>

    <!-- Five -->
<!--    <section id="five" class="wrapper style2 special fade">-->
<!--        <div class="container">-->
<!--            <header>-->
<!--                <h2>Magna faucibus lorem diam</h2>-->
<!--                <p>Ante metus praesent faucibus ante integer id accumsan eleifend</p>-->
<!--            </header>-->
<!--            <form method="post" action="#" class="cta">-->
<!--                <div class="row gtr-uniform gtr-50">-->
<!--                    <div class="col-8 col-12-xsmall"><input type="email" name="email" id="email" placeholder="Your Email Address" /></div>-->
<!--                    <div class="col-4 col-12-xsmall"><input type="submit" value="Get Started" class="fit primary" /></div>-->
<!--                </div>-->
<!--            </form>-->
<!--        </div>-->
<!--    </section>-->
<? include_once $_SERVER["DOCUMENT_ROOT"]."/mygift/inc/footer.php"; ?>