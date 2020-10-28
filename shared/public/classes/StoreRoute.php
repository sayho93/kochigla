<?php

include_once $_SERVER["DOCUMENT_ROOT"]."/mygift/shared/public/classes/Routable.php";
include_once $_SERVER["DOCUMENT_ROOT"] . "/mygift/shared/public/classes/BoardRoute.php";

class StoreRoute extends BoardRoute {
    function addProduct(){

    }

    function getStore($id){
        $sql = "SELECT * FROM tblStore WHERE `id`='{$id}'";
        return $this->getRow($sql);
    }

    function getStoreList(){
        $page = $_REQUEST["page"] == "" ? 1 : $_REQUEST["page"];
        $query = $_REQUEST["query"];
        $whereStmt = "1=1 ";
        if($query != ""){
            $whereStmt .= " AND `title` LIKE '%{$query}%'";
        }

        $startLimit = ($page - 1) * 10;
        $slt = "SELECT `id`, title, campusType, addr, url FROM tblStore 
                WHERE {$whereStmt}
                ORDER BY `title` ASC LIMIT {$startLimit}, 10";
        return $this->getArray($slt);
    }

    function getCategoryList($parentId = 0){
        $sql = "SELECT * FROM tblCategory WHERE parentId='{$parentId}' ORDER BY categoryName";
        $list = $this->getArray($sql);

        return $list;
    }

    function productList(){
        $page = $_REQUEST["page"] == "" ? 1 : $_REQUEST["page"];
        $query = $_REQUEST["query"];
        $whereStmt = "1=1";
        if($_REQUEST["categoryId"] != "") $whereStmt .= " AND `categoryId` = '{$_REQUEST["categoryId"]}' ";
        if($query != "") $whereStmt .= " AND `name` LIKE '%{$query}%'";

        $startLimit = ($page - 1) * 20;
        $slt = "
            SELECT *, (SELECT categoryName FROM tblCategory WHERE id = P.categoryId) as categoryName
            FROM tblProduct P WHERE {$whereStmt}
            ORDER BY `regDate` DESC LIMIT {$startLimit}, 20               
        ";
        return self::getArray($slt);
    }

    function getProductInfo(){
        $sql = "
            SELECT * 
            FROM tblProduct P JOIN tblCategory C ON P.categoryId = C.id 
            WHERE P.`id`= '{$_REQUEST["id"]}' LIMIT 1;
        ";
        return self::getRow($sql);
    }

}
