<?php

include_once $_SERVER["DOCUMENT_ROOT"]."/mygift/shared/public/classes/Routable.php";

class AdminRoute extends Routable {
    function getDashboardInfo(){
        $sql = "
            SELECT 
              (SELECT COUNT(*) FROM tblUser WHERE isAdmin != '1' AND `status` = '1') AS userTotal,
              (SELECT COUNT(*) FROM tblUser WHERE isAdmin != '1' AND `status` = '2') AS inapprovedTotal,
              (SELECT COUNT(*) FROM tblUser WHERE isAdmin = '1') AS adminTotal
            FROM DUAL
            WHERE 1=1;
        ";
        return self::getRow($sql);
    }

    function upsertNotice(){
        if(AuthUtil::getLoggedInfo()->isAdmin != 1){
            return self::response(0, "Permission Denied");
        }

        $sql = "
            INSERT INTO tblNotice(`id`, `title`, `desc`, `regDate`)
            VALUES(
              '{$_REQUEST["id"]}',
              '{$_REQUEST["title"]}',
              '{$_REQUEST["desc"]}',
              NOW()
            )
            ON DUPLICATE KEY UPDATE `title` = '{$_REQUEST["title"]}', `desc` = '{$_REQUEST["desc"]}'
        ";
        self::update($sql);
        return self::response(1, "저장되었습니다.");
    }

    function upsertFaq(){
        if(AuthUtil::getLoggedInfo()->isAdmin != 1){
            return self::response(0, "Permission Denied");
        }

        $id = $_REQUEST["id"];
        $title = $_REQUEST["title"];
        $content = $_REQUEST["content"];
        $ins = "
                INSERT INTO tblFaq(`id`, `title`, `content`, `regDate`)
                VALUES ('{$id}', '{$title}', '{$content}', NOW())
                ON DUPLICATE KEY UPDATE `title` = '{$title}', `content` = '{$content}';
        ";
        $this->update($ins);

        return self::response(1, "저장되었습니다.");
    }

    function deleteFaq(){
        if(AuthUtil::getLoggedInfo()->isAdmin != 1){
            return self::response(0, "Permission Denied");
        }

        $id = $_REQUEST["id"];
        $dlt = "DELETE FROM tblFaq WHERE `id` = '{$id}'";
        $this->update($dlt);
        
        return self::response(1, "삭제되었습니다.");
    }



}
