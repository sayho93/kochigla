<? include_once $_SERVER["DOCUMENT_ROOT"] . "/mygift/shared/public/classes/BoardRoute.php" ?>
<?
$router = new BoardRoute();
//    $list = $router->getNoticeList();
$list = $router->searchList();
?>
<?foreach($list as $item){
    ?>
    <div noticeID="<?=$item["id"]?>" class="col-twelve jDetail text-right"
         style="padding:0px 10px;font-size:13px;border: 1px #BBBBBB solid; border-radius:5px;margin-bottom: 10px;">
        <div class="text-left">
            <h5 style="margin-top: 12px; font-size: 15px; margin-bottom: 5px;">
                <i class="fa fa-dot-circle"></i>&nbsp;<?=$item["title"]?></h5>
        </div>
        <hr style="margin:0;" />
        <div class="align-left">
            <p><?="[{$item["rendezvousPoint"]}]"?></p>
            <b><?=$item["content"]?></b>
        </div>
        <div class="align-right">
            <i class="fa fa-list"></i>&nbsp;<span><?=$item["id"]?></span>&nbsp;
            <i class="fa fa-calendar"></i>&nbsp;<span><?=$item["regDate"]?></span>&nbsp;
            <i class="fa fa-eye"></i>&nbsp;<span><?=$item["hit"]?></span>
            <p style="margin-bottom: 12px;"></p>
        </div>
    </div>
<?}?>
