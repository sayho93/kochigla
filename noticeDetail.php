<? include_once $_SERVER["DOCUMENT_ROOT"]."/mygift/inc/header.php"; ?>
<? include_once $_SERVER["DOCUMENT_ROOT"] . "/mygift/shared/public/classes/BoardRoute.php"?>
<?
    $route->updateNoticeHit();
//    $item = $route->getNotice();

    $bRoute = new BoardRoute();
    $item = $bRoute->getBoard($_REQUEST["id"]);
    $attached = $bRoute->getAttachedFiles($_REQUEST["id"]);
?>
    <script>
        $(document).ready(function(){
            buttonLink(".jBack", "notice.php");
        });
    </script>
    <!-- Main -->
    <div id="main" class="wrapper style1">
        <div class="container">
            <header class="major">
                <h2>공지사항 상세</h2>
                <p><?=$item["title"]?></p>
            </header>
            <div class="col-12 align-right">
                <i class="icon fa-eye"></i> <?=$item["hit"]?> &nbsp;&nbsp;
                <i class="icon fa-calendar"></i> <?=$item["regDate"]?>
            </div>
            <section id="content">
                <hr/>
                <p><?=$item["content"]?></p>
                <div class="col-12 align center">
                    <?foreach($attached as $imgItem){
                        $tmp = explode("/", $imgItem["filePath"]);
                        ?>
                        <img src="<?=$route->PF_FILE_DISPLAY_PATH . "/" . $tmp[sizeof($tmp) - 1]?>" width="100%"/>
                    <?}?>
                </div>
                <hr/>
                <div class="col-12 align-center">
                    <br/>
                    <a href="#" class="jBack button icon fa-list small">목록으로</a>
                </div>
            </section>

        </div>
    </div>

    <!-- Footer -->
<? include_once $_SERVER["DOCUMENT_ROOT"]."/mygift/inc/footer.php"; ?>
