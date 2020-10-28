<? include_once $_SERVER["DOCUMENT_ROOT"]."/mygift/shared/public/classes/WebRoute.php"; ?>
<?
$router = new WebRoute();
$list = $router->getNewsList();

?>
<?foreach($list as $item){
    ?>
    <div noticeID="<?=$item["id"]?>" class="col-12 jDetail text-right jFeedItem speech-bubble" style="">
        <div class="col-12 jFeedItemPadding">
            <p class="jUserTag" userId="<?=$item["userId"]?>" style="margin:0;"><i class="fa-user icon"></i> <?=$item["userName"]?>
                <br/><?=$item["message"]?></p>
        </div>
        <hr style="margin:0;"/>
        <div class="align-right jFeedItemPadding">
            <i class="fa fa-clock-o"></i>&nbsp;<span><?=$router::toRelativeTime($item["tt"])?></span>&nbsp;
            <?if(AuthUtil::isLoggedIn() && $item["userId"] == AuthUtil::getLoggedInfo()->id){?>
                <i class="fa fa-times jDelThis" nid="<?=$item["id"]?>"></i>
            <?}?>
            <p style="margin-bottom: 12px;"></p>
        </div>
    </div>
<?}?>
