<?php

include_once $_SERVER["DOCUMENT_ROOT"]."/mygift/shared/public/classes/Routable.php";
include_once $_SERVER["DOCUMENT_ROOT"]."/mygift/shared/public/classes/FileRoute.php";

class UserAuthRoute extends FileRoute {

    function requestLogin(){
        $email = $_REQUEST["email"];
        $pwd = $this->encryptAES256($_REQUEST["pwd"]);

        $val = $this->getRow("SELECT * FROM tblUser WHERE email='{$email}' AND email != 'Unknown' AND `password`='{$pwd}' LIMIT 1");
        if($val != null){
            if($val["status"] == "2"){
                return Routable::response(3, "인증 대기중인 계정입니다.\n인증 후 이용해주세요.");
            }else{
                AuthUtil::requestLogin($val);
                $upt = "UPDATE tblUser SET accessDate=NOW() WHERE `id`='{$val["id"]}'";
                $this->update($upt);
                return Routable::response(1, "정상적으로 로그인되었습니다.");
            }
        }else{
            return Routable::response(2, "일치하는 회원 정보를 찾을 수 없습니다.");
        }
    }

    function requestNALogin(){
        $id = $_REQUEST["id"];
        $token = $_REQUEST["accessToken"];

        $val = $this->getRow("SELECT * FROM tblUser WHERE oAuthId = '{$id}' AND status = 1 LIMIT 1");
        if($val != null){
            AuthUtil::requestLogin($val);
            self::update("UPDATE tblUser SET accessDate = NOW(), `accessToken` = '{$token}' WHERE `id` = '{$val["id"]}'");
            return Routable::response(1, "정상적으로 로그인 되었습니다.");
        }
    }

    function invalidate(){
        $flag = AuthUtil::invalidateCurrentInfo($this->getUser(AuthUtil::getLoggedInfo()->id));
        if($flag) return self::response(1, "갱신되었습니다.");
        else return self::response(-1, "비정상적인 요청입니다.");
    }

    function authMail(){
        $email = $this->decryptAES256($_REQUEST["authCode"]);
        $val = $this->getRow("SELECT * FROM tblUser WHERE email='{$email}' LIMIT 1");
        if($val != null){
            $upt = "UPDATE tblUser SET `status`=1 WHERE `id`='{$val["id"]}'";
            $this->update($upt);
            $retVal = array(
                "redirect" => true,
                "url" => "http://".$_SERVER["HTTP_HOST"]."/mygift/index.php?msg=인증이%20완료되었습니다."
            );
        }else{
            $retVal = array(
                "redirect" => true,
                "url" => "http://".$_SERVER["HTTP_HOST"]."/mygift/index.php?msg=유효하지%20않은%20요청입니다."
            );
        }
        return $retVal;
    }

    function getUserByReq(){
        return $this->getUser($_REQUEST["id"]);
    }

    function getUser($no){
        $slt = "SELECT *, (SELECT originName FROM tblFile WHERE id = thumbId) as thumbName FROM tblUser WHERE `id`='{$no}'";
        return $this->getRow($slt);
    }

    function getUserNPic($no){
        $slt = "SELECT *, (SELECT originName FROM tblFile WHERE id = thumbId) as thumbName FROM tblUser WHERE `id`='{$no}'";
        $info = $this->getRow($slt);
        $slt = "SELECT * FROM tblFile WHERE id != '{$info["thumbId"]}' AND userKey = '{$no}' ORDER BY regDate DESC LIMIT 3";
        return array($info, self::getArray($slt));
    }

    function joinUser(){
        $email = $_REQUEST["email"];
        $pwd = $_REQUEST["pwd"] != "" ?  $this->encryptAES256($_REQUEST["pwd"]) : "";
        $name = $_REQUEST["name"];
        $age = $_REQUEST["age"] != "" ? $_REQUEST["age"] : 0;
        $sex = $_REQUEST["sex"];
        $from = $_REQUEST["from"];
        $recaptcha = $_REQUEST["recaptcha"];
        // Not necessary in server side
        $phone = $_REQUEST["phone"];
        $oAuthId = $_REQUEST["oAuthId"];
        $accessToken = $_REQUEST["accessToken"];

        $val = $this->getRow("SELECT * FROM tblUser WHERE email='{$email}' AND email != 'Unknown' AND `status` != 0 LIMIT 1");
        if($val != null && $from != "NA") return Routable::response(2, "이미 존재하는 이메일 계정입니다.");

        $ins = "INSERT INTO tblUser(`email`, `password`, `name`, `phone`, `age`, `sex`, `from`, `oAuthId`, `accessToken`, regDate)
                    VALUES ('{$email}', '{$pwd}', '{$name}', '{$phone}', '{$age}', '{$sex}', '{$from}', '{$oAuthId}', '{$accessToken}', NOW())";
        $this->update($ins);
        $id = self::mysql_insert_id();

        $thumb = $_FILES["img"];
        if($thumb["tmp_name"][0] != ""){
            $tmp = self::procFiles($thumb, $id);
            $thumbId = $tmp[$thumb["name"][0]]["id"];
            self::update("UPDATE tblUser SET `thumbId` = '{$thumbId}' WHERE id = '{$id}'");
        }

        if($from != "NA"){
            $link = "http://".$_SERVER["HTTP_HOST"]."{$this->PF_API}UserAuthRoute.authMail&authCode=".urlencode($this->encryptAES256($email));
            $sender = new EmailSender();
            $sender->sendMailTo(
                "[Kochigla] 회원가입 인증 메일입니다.",
                "아래 링크를 클릭하여 인증을 완료해주세요.<br/><a href='$link'>인증 링크</a><br/>본 서비스를 신청하지 않으셨다면 즉시 본 이메일로 회신바랍니다.",
                $email, $name
            );
            return Routable::response(1, "가입 처리가 완료되었습니다.\n입력하신 이메일로 인증 링크가 발송되었습니다.");
        }else{
            self::update("UPDATE tblUser SET `status` = 1 WHERE `oAuthId` = '{$oAuthId}'");
            return Routable::response(1, "가입 처리가 완료되었습니다.");
        }
    }

    function checkUser(){
        $oAuthId = $_REQUEST["oAuthId"];
        $ins = "SELECT * FROM tblUser WHERE `oAuthId` = '{$oAuthId}' AND `status` = 1 LIMIT 1";
        $val = self::getRow($ins);
        if($val != null) return Routable::response(-1, "이미 존재하는 계정입니다.");
        else return Routable::response(1, "사용할 수 있는 계정입니다.");
    }

    function updateUser(){
        $id = $_REQUEST["id"];
        $age = $_REQUEST["age"];
        $sex = $_REQUEST["sex"];
        $phone = $_REQUEST["phone"];

        $thumbId = $_REQUEST["thumbId"];
        $thumb = $_FILES["img"];
        if($thumb["tmp_name"][0] != ""){
            $tmp = self::procFiles($thumb, $id);
            $thumbId = $tmp[$thumb["name"][0]]["id"];
        }

        $additional = $_FILES["imgAdd"];
        if($additional["tmp_name"][0] != ""){
            $tmp = self::procFiles($additional, $id);
        }

        $ins = "
            UPDATE tblUser 
            SET
                `age` = '{$age}',
                `sex` = '{$sex}',
                `phone` = '{$phone}',
                `thumbId` = '{$thumbId}'
            WHERE `id` = '{$id}'
        ";
        self::update($ins);
        return self::response(1, "저장되었습니다.");
    }

    function requestLogout(){
        AuthUtil::requestLogout();
        return Routable::response(1, "정상적으로 로그아웃되었습니다.");
    }

}
