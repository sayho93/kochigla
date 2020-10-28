<?php
include_once $_SERVER["DOCUMENT_ROOT"]."/mygift/shared/bases/Databases.php";
include_once $_SERVER["DOCUMENT_ROOT"]."/mygift/shared/bases/utils/AuthUtil.php";
include_once $_SERVER["DOCUMENT_ROOT"]."/mygift/shared/bases/utils/FileUtil.php";
include_once $_SERVER["DOCUMENT_ROOT"]."/mygift/shared/bases/modules/email/EmailSender.php";

class Routable extends Databases {

    static function response($returnCode, $returnMessage = "", $data = ""){
        $retVal = array("returnCode" => $returnCode, "returnMessage" => $returnMessage, "data" => $data);
        return json_encode($retVal);
    }

    static function toRelativeTime($timestamp = ""){
        if (empty($timestamp)) {
            return false;
        }

        $diff = time() - $timestamp;

        $s = 60; //1분 = 60초
        $h = $s * 60; //1시간 = 60분
        $d = $h * 24; //1일 = 24시간
        $y = $d * 365; //1년 = 1일 * 10일

        if ($diff < $s) {
            $result = $diff . '초 전';
        } else if ($h > $diff && $diff >= $s) {
            $result = round($diff/$s) . '분 전';
        } else if ($d > $diff && $diff >= $h) {
            $result = round($diff/$h) . '시간 전';
        } else if ($y > $diff && $diff >= $d) {
            $result = round($diff/$d) . '일 전';
        } else {
            $result = date('Y.m.d', $timestamp);
        }

        return $result;
    }

    function test(){
        $sql = "SELECT 1 FROM DUAL";
        return $this->getRow($sql);
    }

    function getProperty($name){
        $sql = "SELECT `value` FROM tblProperty WHERE propertyName='{$name}';";
        return $this->getValue($sql, "value");
    }

    function getProperties($prefix, $loc){
        $sql = "SELECT * FROM tblProperty WHERE lang = '{$loc}' AND propertyName LIKE '{$prefix}%';";
        return $this->getArray($sql);
    }

    function getPropertyLoc($name, $loc){
        $sql = "SELECT `value` FROM tblProperty WHERE propertyName='{$name}' AND lang='{$loc}'";
        return $this->getValue($sql, "value");
    }

    function getPropertyLocAjax(){
        return $this->getPropertyLoc($_REQUEST["name"], $_REQUEST["lang"]);
    }

    function setPropertyAjax(){
        return $this->setProperty($_REQUEST["name"], $_REQUEST["value"]);
    }

    function setPropertyLocAjax(){
        return $this->setPropertyLoc($_REQUEST["name"], $_REQUEST["lang"], $_REQUEST["value"]);
    }

    function setPropertyLoc($name, $loc, $value){
        $sql = "
            INSERT INTO tblProperty(propertyName, `desc`, `lang`, `value`) VALUES('{$name}', '', '{$loc}', '{$value}')
            ON DUPLICATE KEY UPDATE `value` = '{$value}'
            ";
        $this->update($sql);
        return Routable::response(1, "succ");
    }

    function getRecommendation($key, $table, $col, $count = 10){
        $slt = "SELECT `{$col}` FROM `{$table}` WHERE `{$col}` LIKE '%{$key}%' ORDER BY `{$col}` ASC LIMIT {$count}";
        $arr = $this->getArray($slt);

        if(sizeof($arr) == 0) return array();

        $retVal = array();
        $cursor = 0;
        foreach ($arr as $unit){
            $retVal[$cursor++] = $unit[$col];
        }
        return $retVal;
    }

    function getData($actionUrl, $request=array()){
        $url = $actionUrl . "?" . http_build_query($request, '', '&');
        $curl_obj = curl_init();
        curl_setopt($curl_obj, CURLOPT_URL, $url);
        curl_setopt($curl_obj, CURLOPT_SSL_VERIFYPEER, true);
        curl_setopt($curl_obj, CURLOPT_RETURNTRANSFER, true);
        return  (curl_exec($curl_obj));
    }

    function postData($actionUrl, $postData){
        $curl_obj = curl_init();
        curl_setopt($curl_obj, CURLOPT_URL, $actionUrl);
        curl_setopt($curl_obj, CURLOPT_SSL_VERIFYPEER, true);
        curl_setopt($curl_obj, CURLOPT_POST, true);
        curl_setopt($curl_obj, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($curl_obj, CURLOPT_POSTFIELDS, $postData);
        return  (curl_exec($curl_obj));
    }

    function encryptAES256($str){
        $res = openssl_encrypt($str, "AES-256-CBC", AES_KEY_256, 0, AES_KEY_256);
        return $res;
    }

    function decryptAES256($str){
        $res = openssl_decrypt($str, "AES-256-CBC", AES_KEY_256, 0, AES_KEY_256);
        return $res;
    }

    function makeFileName(){
        srand((double)microtime()*1000000);
        $Rnd = rand(1000000,2000000);
        $Temp = date("Ymdhis");
        return $Temp.$Rnd;
    }

}

?>
