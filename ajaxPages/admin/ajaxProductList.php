<?php
/**
 * Created by PhpStorm.
 * User: user
 * Date: 2019-02-12
 * Time: 오전 10:08
 */

include_once $_SERVER["DOCUMENT_ROOT"] . "/mygift/shared/public/classes/StoreRoute.php";
include_once  $_SERVER["DOCUMENT_ROOT"] . "/mygift/shared/public/classes/UserAuthRoute.php";
$router = new StoreRoute();
$uRoute = new UserAuthRoute();
$list = $router->productList();
?>

<?foreach($list as $item){?>
    <tr productID="<?=$item["id"]?>" class="jDetail" style="cursor:pointer;">
        <td class="text-center">
            <?=$item["categoryName"]?>
        </td>
        <td class="text-center"><?=$item["name"]?></td>
        <td class="text-center"><?=($uRoute->getUser($item["userId"]))["name"]?></td>
        <td class="text-center"><?=$item["price"]?></td>
        <td class="text-center"><?=$item["savingRate"] . "%"?></td>
        <td class="text-center"><?=$item["uptDate"]?></td>
        <td class="text-center"><?=$item["regDate"]?></td>
        <td class="text-center"><label class="badge badge-primary"><?=$item["hit"]?></label></td>
    </tr>

<?}?>
