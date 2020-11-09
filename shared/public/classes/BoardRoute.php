<?php

include_once $_SERVER["DOCUMENT_ROOT"]."/mygift/shared/public/classes/FileRoute.php";

class BoardRoute extends FileRoute {

    function getFileIds($boardId){
        $sql = "SELECT fileId FROM tblFileBinder WHERE `boardId`='{$boardId}'";
        $ret = array();
        $arr = $this->getArray($sql);

        for($i = 0; $i < sizeof($arr); $i++){
            $ret[$i] = $arr[$i]["fileId"];
        }

        return $ret;
    }

    function revertDeletedItems($boardId, $newArray){
        $origin = $this->getFileIds($boardId);
        $computed = array_diff($origin, $newArray);

        foreach ($computed as $key => $value){
            $this->revertUploadData($value);
        }
        return true;
    }

    function addBoard(){
        if(!AuthUtil::isLoggedIn()){
            return self::response(-2, "비정상적인 요청입니다.");
        }

        if($_REQUEST["type"] == 1 && AuthUtil::getLoggedInfo()->isAdmin != 1) return self::response(-3, "관리자 권한이 필요합니다.");

        $ids = $_REQUEST["attachFiles"];
        $flag = $this->moveTemporaryFiles($ids);

        if($flag == true){
            $id = $_REQUEST["id"] == "" ? 0 : $_REQUEST["id"];
            $userId = AuthUtil::getLoggedInfo()->id;
            $type = $_REQUEST["type"] == "" ? 0 : $_REQUEST["type"];
            $title = $this->escapeString($_REQUEST["title"]);
            $content = $this->escapeString($_REQUEST["content"]);

            $sql = "
                INSERT INTO `tblBoard` 
                    (`id`,
                    `userId`, 
                    `type`, 
                    `title`, 
                    `content`, 
                    `regDate`
                    )
                    VALUES
                    ('{$id}', 
                    '{$userId}', 
                    '{$type}', 
                    '{$title}', 
                    '{$content}', 
                    NOW()
                    )
                    ON DUPLICATE KEY UPDATE 
                    `type`='{$type}', `title`='{$title}', `content`='{$content}', uptDate=NOW();
                ";
            self::update($sql);

            $boardId = self::mysql_insert_id();

            if(is_array($ids) && sizeof($ids) > 0){
                if($id != ""){
                    $this->revertDeletedItems($id, $ids);
                }

                $dsql = "DELETE FROM tblFileBinder WHERE `boardId` = '{$boardId}';";
                self::update($dsql);

                $fsql = "INSERT INTO tblFileBinder(fileId, boardId, `order`, regDate) VALUES ";
                for($i = 0; $i < sizeof($ids); $i++){
                    if($i > 0){
                        $fsql .= " ,";
                    }
                    $fsql .= "('{$ids[$i]}', '{$boardId}', '{$i}', NOW())";
                }
                $fsql .= " ON DUPLICATE KEY UPDATE uptDate=NOW();";

                self::update($fsql);
            }

            return self::response(1, "업로드가 완료되었습니다.", $boardId);
        }
        return self::response(-1, "업로드 중 오류가 발생하였습니다.", $flag);
    }

    function getBoardList(){
        $page = $_REQUEST["page"] == "" ? 1 : $_REQUEST["page"];
        $query = $_REQUEST["query"];
        $whereStmt = "`type` = '{$_REQUEST["type"]}' ";
        if($query != ""){
            $whereStmt .= " AND `title` LIKE '%{$query}%'";
        }

        $startLimit = ($page - 1) * 5;
        $slt = "SELECT * 
                FROM tblBoard WHERE {$whereStmt}
                ORDER BY `regDate` DESC LIMIT {$startLimit}, 5";
        return $this->getArray($slt);
    }

    function getBoard($id){
        $sql = "SELECT *,
                (SELECT COUNT(*) FROM tblFileBinder WHERE `boardId` = tblBoard.id) as files
                FROM tblBoard WHERE `id`='{$id}'";
        return $this->getRow($sql);
    }

    function getAttachedFiles($boardId){
        $sql = "
          SELECT * 
          FROM tblFileBinder FB JOIN tblFiles F ON FB.fileId = F.id 
          WHERE `boardId` = '{$boardId}' 
          ORDER BY `order` ASC";
        return $this->getArray($sql);
    }


    function upsertSearch(){
        $id = $_REQUEST["id"] == null ? 0 : $_REQUEST["id"];
        $userId = AuthUtil::getLoggedInfo()->id;
        $rendezvousPoint  = $_REQUEST["rendezvousPoint"];
        $latitude = $_REQUEST["latitude"];
        $longitude = $_REQUEST["longitude"];
        $startDate = $_REQUEST["startDate"];
        $endDate = $_REQUEST["endDate"];
        $sex = $_REQUEST["sex"];
        $title = $_REQUEST["title"];
        $content = $_REQUEST["content"];
        $companion = $_REQUEST["companion"];
        $originCompanion = $_REQUEST["originCompanion"];

        self::update("
            INSERT INTO tblSearch(`id`, `userId`, rendezvousPoint, `latitude`, `longitude`, `startDate`, `endDate`, `sex`, `originCompanion`, `companion`, `title`, `content`)
            VALUES('{$id}', '{$userId}', '{$rendezvousPoint}', '{$latitude}', '{$longitude}', '{$startDate}', '{$endDate}', '{$sex}', '{$originCompanion}','{$companion}', '{$title}', '{$content}')
            ON DUPLICATE KEY UPDATE    
                rendezvousPoint = '{$rendezvousPoint}',
                `latitude` = '{$latitude}',
                `longitude` = '{$longitude}',
                `startDate` = '{$startDate}',
                `endDate` = '{$endDate}',
                `sex` = '{$sex}',
                `title` = '{$title}',
                `content` = '{$content}',
                `originCompanion` = '{$originCompanion}',
                `companion` = '{$companion}'
        ");
        return self::response(1, "저장되었습니다.");
    }

    function searchList(){
        $page = $_REQUEST["page"] == "" ? 1 : $_REQUEST["page"];
        $query = $_REQUEST["query"];
        $whereStmt = "1=1 ";
        $userId = AuthUtil::getLoggedInfo()->id;
        if($_REQUEST["user"] == true) $whereStmt .= " AND userId = '{$userId}'";
        if($query != "") $whereStmt .= " AND (`title` LIKE '%{$query}%' OR rendezvousPoint LIKE '%{$query}%')";

        $startLimit = ($page - 1) * 5;
        $slt = "SELECT * 
                FROM tblSearch WHERE {$whereStmt}
                ORDER BY `regDate` DESC LIMIT {$startLimit}, 5";

        return $this->getArray($slt);
    }

    function searchInfo(){
        return self::getRow("SELECT * FROM tblSearch WHERE id = '{$_REQUEST["id"]}' LIMIT 1");
    }

    function applyMatch(){
        $id = $_REQUEST["id"];
        $userId = AuthUtil::getLoggedInfo()->id;
        $searchUser = self::getValue("SELECT userId FROM tblSearch WHERE id = '{$id}'", "userId");
        if($searchUser == $userId) return self::response(-2, "본인의 게시물에는 지원할 수 없습니다.");
        $check = self::getRow("SELECT * FROM tblMatch WHERE userId = '{$userId}' AND searchId = '{$id}'");
        if($check != "") return self::response("-1", "이미 지원한 게시물입니다.");
        self::update("INSERT INTO tblMatch(userId, searchId) VALUES('{$userId}', '{$id}')");
        return self::response(1, "성공적으로 지원되었습니다.");
    }

    function updateMatchStat(){
        $searchId = $_REQUEST["searchId"];
        $userId = $_REQUEST["userId"];
        $status = $_REQUEST["status"];
        self::update("UPDATE tblMatch SET `status` = '{$status}' WHERE userId = '{$userId}' AND searchId = '{$searchId}'");
        return self::response(1, "저장되었습니다");
    }

    function matchRequest(){
        $type = $_REQUEST["type"];

        $userId = AuthUtil::getLoggedInfo()->id;
        $whereStmt = "1=1";
        if($type == "R") $whereStmt .= " AND M.status = 0 AND S.userId = '{$userId}'";
        else $whereStmt .= " AND M.userId = '{$userId}'";
        $ins = "
            SELECT *, M.userId as requestUserId, M.status as matchStatus
            FROM tblMatch M 
                JOIN tblUser U ON M.userId = U.id 
                JOIN tblSearch S ON M.searchId = S.id
            WHERE {$whereStmt}
            ORDER BY M.regDate DESC;
        ";
        return self::getArray($ins);
    }

    function changeMatchStatus(){
        $userId = $_REQUEST["userId"];
        $searchId = $_REQUEST["searchId"];
        $status = $_REQUEST["status"];
        self::update("UPDATE tblMatch SET `status` = '{$status}' WHERE `userId` = '{$userId}' AND `searchId` = '{$searchId}'");
        return self::response(1, "변경되었습니다.");
    }


}
