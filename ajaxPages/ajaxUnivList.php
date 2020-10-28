<? include_once $_SERVER["DOCUMENT_ROOT"]."/mygift/shared/public/classes/StoreRoute.php"; ?>
<?
$uroute = new StoreRoute();
$list = $uroute->getUnivList();
?>
<? foreach ($list as $item){ ?>
    <div class="collapsible jDetail" uid="<?=$item["id"]?>">
                                <span style="font-size: 14px;">
                                    <i class="fa fa-university"></i>&nbsp;&nbsp;<?=$item["title"]."(".$item["campusType"].")"?>
                                </span>
    </div>
<?}?>