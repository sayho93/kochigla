<?php
/**
 * Created by PhpStorm.
 * User: 전세호
 * Date: 2019-01-16
 * Time: 오후 8:46
 */
?>
<? include_once $_SERVER["DOCUMENT_ROOT"] . "/mygift/shared/public/classes/BoardRoute.php" ?>
<? include_once  $_SERVER["DOCUMENT_ROOT"] . "/mygift/shared/public/classes/UserAuthRoute.php"?>
<?
    $router = new BoardRoute();
    $uRoute = new UserAuthRoute();
    $list = $router->getBoardList();
?>

<?foreach($list as $item){?>
    <tr noticeID="<?=$item["id"]?>" class="jDetail" style="cursor:pointer;">
        <td><?=$item["title"]?></td>
        <td class="text-center"><?=$item["id"]?></td>
        <td class="text-center"><?=($uRoute->getUser($item["userId"]))["name"]?></td>
        <td class="text-center"><?=$item["regDate"]?></td>
        <td class="text-center"><label class="badge badge-primary"><?=$item["hit"]?></label></td>
    </tr>
    <tr noticeID="<?=$item["id"]?>" class="jDetail" style="cursor:pointer;">
        <td><?=$item["title"]?></td>
        <td class="text-center"><?=$item["id"]?></td>
        <td class="text-center"><?=($uRoute->getUser($item["userId"]))["name"]?></td>
        <td class="text-center"><?=$item["regDate"]?></td>
        <td class="text-center"><label class="badge badge-primary"><?=$item["hit"]?></label></td>
    </tr>
    <tr noticeID="<?=$item["id"]?>" class="jDetail" style="cursor:pointer;">
        <td><?=$item["title"]?></td>
        <td class="text-center"><?=$item["id"]?></td>
        <td class="text-center"><?=($uRoute->getUser($item["userId"]))["name"]?></td>
        <td class="text-center"><?=$item["regDate"]?></td>
        <td class="text-center"><label class="badge badge-primary"><?=$item["hit"]?></label></td>
    </tr>
    <tr noticeID="<?=$item["id"]?>" class="jDetail" style="cursor:pointer;">
        <td><?=$item["title"]?></td>
        <td class="text-center"><?=$item["id"]?></td>
        <td class="text-center"><?=($uRoute->getUser($item["userId"]))["name"]?></td>
        <td class="text-center"><?=$item["regDate"]?></td>
        <td class="text-center"><label class="badge badge-primary"><?=$item["hit"]?></label></td>
    </tr>
<?}?>
