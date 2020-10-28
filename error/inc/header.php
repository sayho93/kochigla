<? include_once $_SERVER["DOCUMENT_ROOT"]."/mygift/shared/public/classes/WebRoute.php"; ?>
<?

$PC_IS_INDEX = false;
if(basename($_SERVER['PHP_SELF']) == "index.php") $PC_IS_INDEX = true;

$route = new WebRoute();
$email = $route->getProperty("WEB_EMAIL");
$link_fb = $route->getProperty("WEB_FACEBOOK");
$ports = $route->getPortfolioList();
$comms = $route->getCustomerComment();
$hit = $route->getProperty("WEB_HIT");
?>
<!DOCTYPE HTML>
<html>
<head>
    <title>풀어줘 :: 우리 일상 속 숨겨진 보물</title>
    <meta charset="utf-8" />
    <meta name="theme-color" content="#272833">
    <meta name="msapplication-TileColor" content="#272833">
    <meta name="msapplication-navbutton-color" content="#272833">
    <meta name="apple-mobile-web-app-status-bar-style" content="#272833">
    <meta name="og:title" content="풀어줘 - Solve me!">
    <meta name="og:description" content="우리 일상 속 숨겨진 보물을 찾아보세요">
    <meta name="og:image" content="http://picklecode.co.kr/mygift.png">
    <meta name="description" content="전국 모든 대학의 맞춤형 솔루션을 제공합니다.">
    <meta name="keywords" content="대학, 대학원, 시험, 중간고사, 기말고사, 족보, 시험지, 과제, 풀이, 솔루션">
    <meta name="author" content="PickleCode">
    <meta name="viewport" content="width=device-width, initial-scale=1, user-scalable=no" />
    <link rel="stylesheet" href="../assets/css/main.css" />
    <noscript><link rel="stylesheet" href="../assets/css/noscript.css" /></noscript>
</head>
<body class="is-preload <?=$PC_IS_INDEX ? "landing" : ""?>">
<div id="page-wrapper">

    <!-- Header -->
    <header id="header">
        <h1 id="logo"><a href="index.php" class=""><img src="../images/main_light.png" height="45px" alt="" /></a></h1>
        <nav id="nav">
            <ul>
            </ul>
        </nav>
    </header>
    <!-- Scripts -->

    <script src="../assets/js/jquery.min.js"></script>
    <script type="text/javascript" src="../js/AjaxUtil.js"></script>
    <script>
        $(document).ready(function(){
            $(".jGoback").click(function(){
                history.back();
            });
        });
    </script>