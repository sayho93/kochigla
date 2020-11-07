<?php

include_once $_SERVER["DOCUMENT_ROOT"]."/mygift/shared/public/classes/Routable.php";

class ChatRoute extends Routable {

    /**
     * On Sending Message
     * Only For This Project
     */
    function sendMessage(){
        if(!AuthUtil::isLoggedIn()){
            return self::response(-1, "비정상적인 요청입니다.");
        }

        $userId = AuthUtil::getLoggedInfo()->id;
        $groupId = $_REQUEST["groupId"];
        $type = $_REQUEST["type"] == "" ? 0 : $_REQUEST["type"];
        $msg = $_REQUEST["msg"];

        $sql = "INSERT INTO tblChatMsg(userId, groupId, `type`, msg) VALUES('{$userId}', '{$groupId}', '{$type}', '{$msg}')";
        $this->update($sql);

        return self::response(1, "메시지가 전송되었습니다.");
    }

    /**
     * On Initial Load
     * Only For This Project
     */
    function getLastestMessage(){
        $count = $_REQUEST["count"] == "" ? 30 : $_REQUEST["count"];
        $groupId = $_REQUEST["groupId"];

        if($groupId == ""){
            return self::response(-2, "그룹 인덱스 정보가 누락되었습니다.");
        }

        $sql = "SELECT * FROM 
                (SELECT *, (SELECT `name` FROM tblUser WHERE `id`=userId) AS userName
                 FROM tblChatMsg WHERE groupId='{$groupId}' ORDER BY regDate DESC LIMIT {$count}) temp
                ORDER BY regDate ASC";
        $array = $this->getArray($sql);

        return self::response(1, "메시지가 로드되었습니다.", $array);
    }

    /**
     * On Loading Previous Message (On Scroll to Top)
     */
    function loadPreviousMessage(){
        $count = $_REQUEST["count"] == "" ? 30 : $_REQUEST["count"];
        $groupId = $_REQUEST["groupId"];
        $firstIndex = $_REQUEST["firstIndex"];

        if($groupId == ""){
            return self::response(-2, "그룹 인덱스 정보가 누락되었습니다.");
        }

        if($firstIndex == ""){
            return self::response(-1, "인덱스 정보가 누락되었습니다.");
        }

        $sql = "SELECT * FROM 
                (SELECT *, (SELECT `name` FROM tblUser WHERE `id`=userId) AS userName
                 FROM tblChatMsg WHERE groupId='{$groupId}' AND `id` < '{$firstIndex}' ORDER BY regDate DESC LIMIT {$count}) temp
                ORDER BY regDate ASC";
        $array = $this->getArray($sql);

        return self::response(1, "메시지가 로드되었습니다.", $array);
    }

    /**
     * On Polling
     */
    function onPolling(){
        $groupId = $_REQUEST["groupId"];
        $lastIndex = $_REQUEST["lastIndex"];

        if($groupId == ""){
            return self::response(-2, "그룹 인덱스 정보가 누락되었습니다.");
        }

        if($lastIndex == ""){
            return self::response(-1, "인덱스 정보가 누락되었습니다.");
        }

        $sql = "SELECT * FROM 
                (SELECT *, (SELECT `name` FROM tblUser WHERE `id`=userId) AS userName
                 FROM tblChatMsg WHERE groupId='{$groupId}' AND `id` > '{$lastIndex}' ORDER BY regDate DESC) temp
                ORDER BY regDate ASC";
        $array = $this->getArray($sql);

        return self::response(1, "메시지가 로드되었습니다.", $array);
    }

    /**
     * On Listing Groups of an user
     */
    function getMyGroupList(){
        $userId = $_REQUEST["userId"];
        $sql = "SELECT * FROM tblChatGroup WHERE `id` IN (SELECT groupId FROM tblGroupBinder WHERE userId='{$userId}') ORDER BY groupName DESC";
        $array = $this->getArray($sql);

        return self::response(1, "그룹목록이 로드되었습니다.", $array);
    }

    /**
     * On Listing Groups of a logged-in user
     * Only For This Project
     */
    function getMyAuthGroupList(){
        if(!AuthUtil::isLoggedIn()){
            return self::response(-1, "접근 권한이 없습니다.");
        }
        
        $userId = AuthUtil::getLoggedInfo()->id;
        $sql = "SELECT *,
                (SELECT COUNT(*) FROM tblGroupBinder WHERE `groupId` = tblChatGroup.id) AS members
                FROM tblChatGroup WHERE `id` IN (SELECT groupId FROM tblGroupBinder WHERE userId='{$userId}') ORDER BY groupName DESC";
        return $this->getArray($sql);
    }

    /**
     * Checking an User belongs to a group
     */
    function isMemberOf($userId, $groupId){
        $sql = "SELECT COUNT(*) AS flag FROM tblGroupBinder WHERE userId='{$userId}' AND groupId='{$groupId}'";
        $val = $this->getValue($sql, "flag");

        if($val > 0) return true;
        else return false;
    }

    /**
     * On Loading Member List of Group
     */
    function getMemberList(){
        $groupId = $_REQUEST["groupId"];

        if($groupId == ""){
            return self::response(-2, "그룹 인덱스 정보가 누락되었습니다.");
        }

        $sql = "SELECT `id`, `email`, `name`, `isAdmin` FROM tblUser 
                WHERE `id` IN (SELECT userId FROM tblGroupBinder WHERE groupId='{$groupId}')
                ORDER BY `name` ASC";
        $array = $this->getArray($sql);

        return self::response(1, "멤버리스트가 로드되었습니다.", $array);
    }

    /**
     * Getting a group
     */
    function getGroup($groupId){
        $sql = "SELECT *
                FROM tblChatGroup WHERE `id` = '{$groupId}'";
        return $this->getRow($sql);
    }

    /**
     * On Quit a group
     */
    function exitGroup($userId, $groupId){
        $sql = "DELETE FROM tblGroupBinder WHERE userId='{$userId}' AND groupId='{$groupId}'";
        $this->update($sql);

        $sql = "SELECT COUNT(*) AS members FROM tblGroupBinder WHERE groupId='{$groupId}'";
        $val = $this->getValue($sql, "members");

        if($val <= 1){
            $sql = "DELETE FROM tblChatGroup WHERE `id` = '{$groupId}'";
            $this->update($sql);
            $sql = "DELETE FROM tblGroupBinder WHERE `groupId` = '{$groupId}'";
            $this->update($sql);
            $sql = "DELETE FROM tblChatMsg WHERE `groupId` = '{$groupId}'";
            $this->update($sql);
        }
    }

    /**
     * On Quit a group
     */
    function exitGroupForAjax(){
        $this->exitGroup($_REQUEST["userId"], $_REQUEST["groupId"]);
        return self::response(1, "처리되었습니다.");
    }

    /**
     * On Quit a group for ajax call
     */
    function exitGroupAjax(){
        $this->exitGroup($_REQUEST["userId"], $_REQUEST["groupId"]);
        return self::response(1, "처리되었습니다.");
    }

    /**
     * On Creation of a group
     * Only For This Project
     */
    function createGroup($groupName = "새 그룹", $userIds){
        if(!AuthUtil::isLoggedIn()){
            return false;
        }

        for($i = 0; $i < sizeof($userIds); $i++){
            if(AuthUtil::getLoggedInfo()->id == $userIds[$i]){
                return false;
            }
        }

        $sql = "INSERT INTO tblChatGroup(groupName) VALUES('{$groupName}')";
        $this->update($sql);
        $newGID = $this->mysql_insert_id();

        $myId = AuthUtil::getLoggedInfo()->id;
        $groupId = $this->mysql_insert_id();

        $sql = "INSERT INTO tblGroupBinder(userId, groupId) 
                VALUES ('{$myId}', '{$groupId}')";
        for($i = 0; $i < sizeof($userIds); $i++){
            $sql .= " ,('{$userIds[$i]}', '{$groupId}')";
        }
        $sql .= " ON DUPLICATE KEY UPDATE userId=userId;";

        $this->update($sql);

        return $newGID;
    }

    /**
     * Creation of a group for ajax [Kochigla]
     * Only For this Project
     */
    function createGroupAjaxK(){
        $user = AuthUtil::getLoggedInfo();
        $userId = $_REQUEST["userId"];
        $info = self::getRow("SELECT * FROM tblUser WHERE id = '{$userId}' AND status=1 LIMIT 1");
        $groupName = $user->name . ", " . $info["name"];
        $userIds = Array();
        array_push($userIds, $info["id"]);

        $flag = $this->createGroup($groupName, $userIds);
        if($flag > 0) return self::response(1, "처리되었습니다.");
        else return self::response(-1, "비정상적인 요청입니다.");
    }


    /**
     * On Creation of a group for ajax
     * Only For This Project
     */
    function createGroupAjax(){
        $flag = $this->createGroup($_REQUEST["groupName"], $_REQUEST["userIds"]);
        if($flag > 0) return self::response(1, "처리되었습니다.");
        else return self::response(-1, "비정상적인 요청입니다.");
    }

    /**
     * Checking if the group which has 2 params as 1:1 Chat exists.
     */
    function isAlreadyExistingForTwo($user1, $user2){
        $sql = "
        SELECT groupId FROM tblGroupBinder
        WHERE groupId IN 
        (
        SELECT groupId FROM
        (
		SELECT * FROM tblGroupBinder
		WHERE groupId IN
		(SELECT groupId FROM tblGroupBinder WHERE userId='{$user1}')
        ) temp
        WHERE userId='{$user2}'
        ) GROUP BY groupId HAVING COUNT(*)=2
        ";
        $val = $this->getValue($sql, "groupId");
        if($val != "" && $val > 0) return true;
        return false;
    }

    /**
     * Retrieving a group which has two members as bound members
     * Only For This Project
     */
    function getExistingForTwo($user1, $user2){
        $sql = "
        SELECT groupId FROM tblGroupBinder
        WHERE groupId IN 
        (
        SELECT groupId FROM
        (
		SELECT * FROM tblGroupBinder
		WHERE groupId IN
		(SELECT groupId FROM tblGroupBinder WHERE userId='{$user1}')
        ) temp
        WHERE userId='{$user2}'
        ) GROUP BY groupId HAVING COUNT(*)=2
        ";
        return $this->getValue($sql, "groupId");
    }

    /**
     * Creating a group for two users without duplication.
     */
    function createGroupForTwo(){
        $user = $_REQUEST["userId"];

        if($user == ""){
            return self::response(-5, "사용자 인덱스가 누락되었습니다.");
        }
        
        if(!AuthUtil::isLoggedIn()){
            return self::response(-1, "로그인이 필요한 서비스입니다.");
        }

        if(AuthUtil::getLoggedInfo()->id == $user){
            return self::response(-3, "본인에게는 메시지를 보낼 수 없습니다.");
        }
        
        if($this->isAlreadyExistingForTwo(AuthUtil::getLoggedInfo()->id, $user)){
            return self::response(1, "처리되었습니다.",
                $this->getExistingForTwo(AuthUtil::getLoggedInfo()->id, $user));
        }

        $groupName = $this->makeGroupNameForTwo(AuthUtil::getLoggedInfo()->id, $user);
        $flag = $this->createGroup($groupName, array($user));

        if($flag > 0) return self::response(1, "처리되었습니다.", $flag);
        else return self::response(-4, "오류가 발생하였습니다.");
    }

    /**
     * Generating a group name for two
     */
    function makeGroupNameForTwo($user1, $user2){
        $sql = "SELECT `name` FROM tblUser WHERE `id` IN ('{$user1}', '{$user2}') ORDER BY `name` ASC;";
        $arr = $this->getArray($sql);

        if(sizeof($arr) < 2){
            return "새 그룹";
        }
        return $arr[0]["name"]."&".$arr[1]["name"];
    }

}
