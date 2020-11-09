<?php

include_once $_SERVER["DOCUMENT_ROOT"]."/mygift/shared/public/classes/Routable.php";

class WebRoute extends Routable {

    function getFaqList(){
        return $this->getArray("SELECT * FROM tblFaq ORDER BY `title` ASC");
    }

    function getShowOffValue(){
        $sql = "SELECT
                (SELECT COUNT(*) FROM tblUniv) AS univCount,
                (SELECT COUNT(*) FROM tblDept) AS deptCount
                FROM DUAL 
                ";
        return $this->getRow($sql);
    }

    function getNoticeList(){
        $page = $_REQUEST["page"] == "" ? 1 : $_REQUEST["page"];
        $query = $_REQUEST["query"];
        $whereStmt = "1=1 ";
        if($query != ""){
            $whereStmt .= " AND `title` LIKE '%{$query}%'";
        }

        $startLimit = ($page - 1) * 5;
        $slt = "SELECT `id`, `title`, `madeBy`, `filePath`, `uptDate`, `regDate`, `hit` 
                FROM tblNotice WHERE {$whereStmt}
                ORDER BY `regDate` DESC LIMIT {$startLimit}, 5";
        return $this->getArray($slt);
    }

    function getPayList(){
        if(!AuthUtil::isLoggedIn()) return null;

        $userId = AuthUtil::getLoggedInfo()->id;

        $page = $_REQUEST["page"] == "" ? 1 : $_REQUEST["page"];

        $startLimit = ($page - 1) * 5;
        $slt = "SELECT * 
                FROM tblPointStack WHERE userId='{$userId}'
                ORDER BY `regDate` DESC LIMIT {$startLimit}, 5";
        return $this->getArray($slt);
    }

    function getTopNotice($cnt){
        $slt = "SELECT `id`, `title`, DATE(`regDate`) AS dt 
                FROM tblNotice
                ORDER BY `regDate` DESC LIMIT {$cnt}";
        return $this->getArray($slt);
    }

    function saveQuery(){
        $userId = $_REQUEST["userId"];
        $budget = $_REQUEST["budget"];
        $title = $_REQUEST["title"];
        $content = $_REQUEST["content"];
        $classId = $_REQUEST["classId"];
        $ins = "INSERT INTO tblQuery(`userId`, `title`, `budget`, `content`, `classId`, `regDate`)
                VALUES ('{$userId}', '{$title}', '{$budget}', '{$content}', '{$classId}', NOW())";
        $this->update($ins);

        return self::response(1, "저장되었습니다.");
    }

    function getClassList(){
        $slt = "SELECT * FROM tblClass ORDER BY className ASC";
        return $this->getArray($slt);
    }

    function getQueryList(){
        $page = $_REQUEST["page"] == "" ? 1 : $_REQUEST["page"];
        $query = $_REQUEST["query"];
        $whereStmt = "1=1 ";
        if($query != ""){
            $whereStmt .= " AND `title` LIKE '%{$query}%'";
        }

        $startLimit = ($page - 1) * 5;
        $slt = "SELECT *,
                (SELECT `className` FROM tblClass WHERE `id`=`classId` LIMIT 1) AS className 
                FROM tblQuery WHERE {$whereStmt}
                ORDER BY `regDate` DESC LIMIT {$startLimit}, 5";
        return $this->getArray($slt);
    }

    function getNotice(){
        $slt = "SELECT *
                FROM tblNotice WHERE `id`='{$_REQUEST["id"]}'";
        return $this->getRow($slt);
    }

    function updateNoticeHit(){
        $id = $_REQUEST["id"];
        $slt = "SELECT `hit` FROM tblNotice WHERE `id` = '{$id}'";
        $hitVal = $this->getValue($slt, "hit") + 1;
        $upt = "UPDATE tblNotice SET `hit` = '{$hitVal}' WHERE `id` = '{$id}'";
        $this->update($upt);
    }

    function writeNews(){
        if(!AuthUtil::isLoggedIn()){
            return self::response(-1, "세션이 만료되었습니다.");
        }
        $userId = AuthUtil::getLoggedInfo()->id;
        $univId = $_REQUEST["univId"];
        $message = $_REQUEST["message"];
        $recurId = $_REQUEST["recurId"] == "" ? 0 : $_REQUEST["recurId"];

        $sql = "INSERT INTO tblNews(userId, `message`, regDate) 
                VALUES('{$userId}', '{$message}', NOW())";

        $this->update($sql);
        return self::response(1, "등록되었습니다.");
    }

    function getNews(){
        $sql = "SELECT * FROM tblNews WHERE `id` = '{$_REQUEST["id"]}'";
        return $this->getRow($sql);
    }

    function deleteNews(){
        if(!AuthUtil::isLoggedIn()){
            return self::response(-1, "세션이 만료되었습니다.");
        }
        $id = $_REQUEST["id"];
        $item = $this->getNews();

        if($item["userId"] == AuthUtil::getLoggedInfo()->id){
            $sql = "DELETE FROM tblNews WHERE `id`='{$id}'";
            $this->update($sql);
            return self::response(1, "삭제되었습니다.");
        }else{
            return self::response(-2, "요청이 거부되었습니다.");
        }
    }

    function getUserProfile(){
        $id = $_REQUEST["id"];
        $sql = "SELECT
                `id`, `name`, `email`, `univId`, `deptId`, `accessDate`,
                (SELECT CONCAT(`title`, '(', campusType, ')') FROM tblUniv WHERE `id` = `univId`) AS univName,
                (SELECT deptName FROM tblDept WHERE `id`=`deptId`) AS deptName
                FROM tblUser WHERE `id` = '{$id}'";
        $row = $this->getRow($sql);
        return self::response(1, "로드되었습니다.", $row);
    }

    function getTopNewsList(){
        $slt = "SELECT *, UNIX_TIMESTAMP(regDate) as tt,
                (SELECT `name` FROM tblUser WHERE `id` = `userId`) as `userName`
                FROM tblNews
                ORDER BY `regDate` DESC LIMIT 6";

        return $this->getArray($slt);
    }

    function getNewsList(){
        $userId = $_REQUEST["userId"];

        $page = $_REQUEST["page"] == "" ? 1 : $_REQUEST["page"];

        $whereStmt = "1=1 ";
        if($userId != ""){
            $whereStmt .= " AND `userId` = '{$userId}'";
        }

        $startLimit = ($page - 1) * 20;
        $slt = "SELECT *, UNIX_TIMESTAMP(regDate) as tt,
                (SELECT `name` FROM tblUser WHERE `id` = `userId`) as `userName`
                FROM tblNews WHERE {$whereStmt}
                ORDER BY `regDate` DESC LIMIT {$startLimit}, 20";

        $arr = $this->getArray($slt);

        return $arr;
    }

    function getBoardInfo(){
        $id = $_REQUEST["id"];

        $sql = "
            SELECT * FROM tblBoard WHERE `id` = '{$id}' LIMIT 1
        ";
        $boardInfo = $this->getRow($sql);

        $sql = "
            SELECT * 
            FROM tblFileBinder FB JOIN tblFiles F ON FB.fileId = F.id
            WHERE FB.boardId = '{$id}'
        ";
        $fileInfo = $this->getArray($sql);
        $boardInfo["fileInfo"] = $fileInfo;
        return $boardInfo;
    }

    //TODO fileBinder index work & redundancy check
    function upsertBoard(){
        $boardIdx = $_REQUEST["id"] == "" ? 0 : $_REQUEST["id"];

        //TODO redundancy check
        if($boardIdx != 0){

        }else{

        }
        $response = FileUtil::uploadFile($_FILES["testFile"], $this->PF_FILE_PATH);

        if($response["returnCode"] == 1){
            $data = $response["data"];
            $fileIdxArr = Array();

            foreach($data as $item){
                $sql = "
                    INSERT INTO tblFiles(`fileName`, `filePath`, `ext`, `regDate`)
                    VALUES(
                      '{$item["fileName"]}',
                      '{$item["filePath"]}',
                      '{$item["fileExt"]}',
                      NOW()
                    )
                 ";
                $this->update($sql);

                $fileIdx = $this->mysql_insert_id();
                array_push($fileIdxArr, $fileIdx);
            }

            $sql = "
                INSERT INTO tblBoard(`id`, `userId`, `type`, `title`, `content`, `regDate`)
                VALUES(
                  '{$boardIdx}',
                  '{$_REQUEST["userId"]}',
                  '{$_REQUEST["type"]}',
                  '{$_REQUEST["title"]}',
                  '{$_REQUEST["content"]}',
                  NOW()
                )
                ON DUPLICATE KEY UPDATE
                  `userId` = '{$_REQUEST["userId"]}',
                  `type` = '{$_REQUEST["type"]}',
                  `title` = '{$_REQUEST["title"]}',
                  `content` = '{$_REQUEST["content"]}'
            ";
            $this->update($sql);
            $boardIdx = $this->mysql_insert_id();

            //TODO redundancy check
            $i=1;
            foreach($fileIdxArr as $idx){
                $sql = "
                    INSERT INTO tblFileBinder(`fileId`, `boardId`, `order`, `regDate`)
                    VALUES(
                      '{$idx}',
                      '{$boardIdx}',
                      '{$i}',
                      NOW()
                    )
                ";
                $i++;
                $this->update($sql);
            }

            return Routable::response(1, "succ");
        } else return Routable::response(-1, $response["returnMessage"]);
    }
}
