<? include_once $_SERVER["DOCUMENT_ROOT"]."/mygift/shared/public/classes/WebRoute.php"; ?>
<?
$router = new WebRoute();
$list = $router->getPayList();
?>
<?foreach($list as $item){
    ?>
    <div noticeID="<?=$item["id"]?>"
         style="padding:0px 10px;font-size:13px;border: 1px #BBBBBB solid; border-radius:5px;margin-bottom: 10px;">
        <div class="align-left">
            <h5 style="margin-top: 12px; font-size: 15px; margin-bottom: 5px;">
                <i class="icon fa-comment"></i> &nbsp;<?=$item["comment"]?></h5>
        </div>
        <hr style="margin:0;" />
        <div class="align-right">
            <i class="fa fa-info"></i>&nbsp;<span> ID <?=$item["id"]?></span>&nbsp;
            <i class="fa fa-calendar"></i>&nbsp;<span><?=$item["regDate"]?></span>&nbsp;
            <?if($item["receiptId"] == "S"){?>
                <i class="fa fa-database"></i>&nbsp;<span> <?=$item["amount"]?>P</span>
            <?}else{?>
                <i class="fa fa-database"></i>&nbsp;<span> <?=$item["price"]?>원</span>
            <?}?>
            <p style="margin-bottom: 12px;"></p>
        </div>
    </div>
<?}?>
