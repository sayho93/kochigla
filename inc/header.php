<? include_once $_SERVER["DOCUMENT_ROOT"]."/mygift/shared/public/classes/WebRoute.php"; ?>
<? include_once $_SERVER["DOCUMENT_ROOT"]."/mygift/shared/public/classes/PayRoute.php"; ?>
<? include_once $_SERVER["DOCUMENT_ROOT"]."/mygift/shared/public/classes/StoreRoute.php"; ?>
<?
    $PC_IS_INDEX = false;
    if(basename($_SERVER['PHP_SELF']) == "index.php") $PC_IS_INDEX = true;

    $route = new WebRoute();
    $payRoute = new PayRoute();
    $storeRoute = new StoreRoute();

    $API_URL = $route->PF_API;
    $categoryList = $storeRoute->getCategoryList();

    $email = $route->getProperty("WEB_EMAIL");
    $link_fb = $route->getProperty("WEB_FACEBOOK");
    $hit = $route->getProperty("WEB_HIT");

    $balance = 0;
    if(AuthUtil::isLoggedIn()){
        $balance = $payRoute->getPoint(AuthUtil::getLoggedInfo()->id);
    }
?>
<!DOCTYPE HTML>
<html>
<head>
    <title>Kochigla :: 여행의 동반자</title>
    <meta charset="utf-8" />
    <meta name="theme-color" content="#272833">
    <meta name="msapplication-TileColor" content="#272833">
    <meta name="msapplication-navbutton-color" content="#272833">
    <meta name="apple-mobile-web-app-status-bar-style" content="#272833">
    <meta name="og:title" content="풀어줘 - Solve me!">
    <meta name="og:description" content="여행 속 동행을 구해보세요">
    <meta name="og:image" content="http://picklecode.co.kr/mygift.png">
    <meta name="description" content="당신이 빛나던 순간을 함께할 나만의 여행 동행을 구해 보세요">
    <meta name="keywords" content="여행, 동행, 동료, 국내여행, 제주도여행">
    <meta name="author" content="PickleCode">
    <meta name="viewport" content="width=device-width, initial-scale=1, user-scalable=no" />
    <link rel="stylesheet" href="assets/css/main.css" />
    <noscript><link rel="stylesheet" href="assets/css/noscript.css" /></noscript>
</head>
<body class="is-preload <?=$PC_IS_INDEX ? "landing" : ""?>">
<div id="page-wrapper">
    <!-- Header -->
    <header id="header">
<!--        <h1 id="logo"><a href="index.php" class=""><img src="images/main_light.png" height="45px" alt="" /></a></h1>-->
        <h1 id="logo"><a href="index.php" class=""><img src="images/kochigla_logo.png" height="55px" alt="" /></a></h1>
        <nav id="nav">
            <ul>
                <li><a href="index.php">홈</a></li>
                <li>
                    <a href="search.php?type=A">동행검색</a>
<!--                    <ul>-->
<!--                        <li><a href="search.php">전체</a></li>-->
<!--                        --><?//foreach($categoryList as $categoryItem){?>
<!--                        <li><a href="search.php?id=--><?//=$categoryItem["id"]?><!--">-->
<!--                                --><?//=$categoryItem["categoryName"]?><!--(--><?//=$categoryItem["alterName"]?><!--)-->
<!--                            </a></li>-->
<!--                        --><?//}?>
<!--                    </ul>-->
                </li>
                <li>
                    <a href="news.php">뉴스피드</a>
                </li>
                <li><a href="faq.php">FAQ</a></li>
                <li><a href="notice.php">공지사항</a></li>
                <?
                if(AuthUtil::isLoggedIn()){
                    $displayName = AuthUtil::getLoggedInfo()->name;
                ?>
                <li>
                    <a href="#"><i class="icon fa-user"></i> <?=$displayName?>님</a>
                    <ul>
                        <li><a href="balance.php" class="icon fa-database"> 내 포인트 : <?=$balance?>P</a></li>
                        <li><a href="profile.php" class="">마이페이지</a></li>
                        <li>
                            <a href="data.php?type=R" class="">
                                내가 받은 동행신청 -
                                <i class="fa-stack fa-xs" style="font-size: 0.8rem">
                                    <i class="fa fa-circle-o fa-stack-2x"></i>
                                    <strong class="fa-stack-1x">12</strong>
                                </i>

                            </a>

                        </li>
                        <li>
                            <a href="data.php?type=A" class="">
                                내가 보낸 동행신청 -
                                <i class="fa-stack fa-xs" style="font-size: 0.8rem">
                                    <i class="fa fa-circle-o fa-stack-2x"></i>
                                    <strong class="fa-stack-1x">8</strong>
                                </i>
                            </a>

                        </li>
                        <li><a href="logout.php" class="jLogoutNav">로그아웃</a></li>
                    </ul>
                </li>
                <?}else{?>
                <li><a href="login.php" class="button primary">로그인</a></li>
                <?}?>
            </ul>
        </nav>
    </header>
    <!-- Scripts -->
    <link rel="stylesheet" type="text/css" href="js/snackbar.min.css" />

    <!-- BootPay -->
    <script src="https://cdn.bootpay.co.kr/js/bootpay-2.0.20.min.js" type="application/javascript"></script>

    <script src="js/snackbar.min.js"></script>
<!--    <script src="js/sweetalert.min.js"></script>-->
    <script src="js/sweetalert2.min.js"></script>
    <script src='https://www.google.com/recaptcha/api.js'></script>
    <script src="assets/js/jquery.min.js"></script>
    <script type="text/javascript" src="js/jquery.form.js"></script>
    <script type="text/javascript" src="js/AjaxUtil.js"></script>
    <script type="text/javascript" src="js/payUtil.js"></script>

    <!-- PolyFill -->
<!--    <script src="shared/lib/polyfill/es6-promise.auto.min.js"></script>-->
<!--    <script src="shared/lib/polyfill/browser-polyfill.min.js"></script>-->
<!--    <script src="shared/lib/filepond/filepond-polyfill.js"></script>-->
<!--    <script src="shared/lib/polyfill/babel.min.js"></script>-->

    <!-- Filepond -->
    <link rel="stylesheet" type="text/css" href="shared/lib/filepond/filepond.css" />
    <link href="shared/lib/filepond/filepond-plugin-image-preview.css" rel="stylesheet">

    <script src="shared/lib/filepond/filepond.js"></script>
    <script src="shared/lib/filepond/filepond-plugin-file-validate-size.js"></script>
    <script src="shared/lib/filepond/filepond-plugin-image-validate-size.js"></script>
    <script src="shared/lib/filepond/filepond-plugin-image-exif-orientation.js"></script>
    <script src="shared/lib/filepond/filepond-plugin-image-preview.js"></script>

<!--    <script src="assets/bootstrap/js/bootstrap.bundle.min.js"></script>-->
<!--    <link href="assets/bootstrap/css/bootstrap.min.css" rel="stylesheet">-->
    <script src="assets/js/jquery.datetimepicker.js"></script>
    <link rel="stylesheet" type="text/css" href="assets/css/jquery.datetimepicker.css"/>

    <style>
        .filepond--drop-label{
            color : #777;
        }

        .filepond--drop-label label{
            color : #777;
        }
    </style>

    <script>
        var pond = null;

        $(document).ready(function(){

            $(".jLogoutNav").click(function(){
                callJson(
                    "<?="{$API_URL}UserAuthRoute.requestLogout"?>",
                    null, function(data){
                        if(data.returnCode == 1){
                            location.href = "index.php";
                        }else{
                            alert("오류가 발생하였습니다.\n관리자에게 문의하세요.");
                        }
                    }
                );
            });

            var coll = $(".collapsible");
            for (var i = 0; i < coll.length; i++){
                coll[i].addEventListener("click", function(){
                    this.classList.toggle("active");
                    var content = this.nextElementSibling;

                    if (content.style.maxHeight)
                        content.style.maxHeight = null;
                    else
                        content.style.maxHeight = content.scrollHeight + "px";
                });
            }
        });
        function showSnackBar(text){
            Snackbar.show({pos: 'bottom-left', duration:30000, text: text, actionText:'닫기', actionTextColor:'#e44c65'});
        }

        function hideFooter(){
            $("#footer").hide();
        }
    </script>
<? if(AuthUtil::isLoggedIn()){ include_once $_SERVER["DOCUMENT_ROOT"]."/mygift/inc/float_action.php"; } ?>
<? include_once $_SERVER["DOCUMENT_ROOT"]."/mygift/inc/popups.php"; ?>