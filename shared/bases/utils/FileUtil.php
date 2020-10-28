<?php
/**
 * Created by PhpStorm.
 * User: 전세호
 * Date: 2019-01-09
 * Time: 오후 6:03
 */

class FileUtil{
    static function makeFileName(){
        srand((double)microtime()*1000000) ;
        $Rnd = rand(1000000,2000000) ;
        $Temp = date("Ymdhis") ;
        return $Temp.$Rnd;
    }

    static function checkExtension($ext){
        $filename = strtolower ($ext);
        if(strpos($filename, ".php") || strpos($filename, ".html")){
            return false;
        }
        return true;
    }

    static function uploadFile($file, $path){
        $retArr = Array();
        for($i=0; $i<sizeof($file["size"]); $i++){
            $check = getimagesize($file["tmp_name"][$i]);
            if($check !== false){
                if(!FileUtil::checkExtension($file["name"][$i])) return Routable::response(-2, "해당 확장자의 업로드는 제한됩니다.");
                $ext = pathinfo(basename($file["name"][$i]),PATHINFO_EXTENSION);
                $name = FileUtil::makeFileName() . "." . $ext;
                $targetDir = $_SERVER["DOCUMENT_ROOT"] . $path . $name;
                if(!move_uploaded_file($file["tmp_name"][$i], $targetDir))
                    return Routable::response(-1, "파일을 저장하는 과정에서 오류가 발생했습니다.");
            }
            $retArr[$i]["fileName"] = $file["name"][$i];
            $retArr[$i]["filePath"] = $name;
            $retArr[$i]["fileExt"] = $ext;
        }
        return Routable::response(1, "succ", $retArr);
    }
}