<?php

include_once $_SERVER["DOCUMENT_ROOT"]."/mygift/shared/public/classes/Routable.php";

class FileRoute extends Routable {

    static function createDir($path) {
        if (is_dir($path)) return true;
        $prev_path = substr($path, 0, strrpos($path, '/', -2) + 1 );
        $return = self::createDir($prev_path);
        return ($return && is_writable($prev_path)) ? mkdir($path) : false;
    }

    function processFilePond(){
        $file = $_FILES["attachFiles"];
        $tmp_name = $file["tmp_name"][0]; //$this->escapeString($file["tmp_name"][0]); // FIlepond Sends one file for a single request
        $rawName = basename($file["name"][0]);
        $ext = pathinfo($rawName,PATHINFO_EXTENSION);

        /**
         * Move to temporary directory
         */
        $targetDir = $this->PF_FILE_TEMP_PATH;
        if(!self::createDir($targetDir)){
            return self::response(-99, "파일 처리 중 경로 오류가 발생하였습니다.");
        }
        $targetPath = $targetDir."/".$this->makeFileName();
        $movedFlag = move_uploaded_file($tmp_name, $targetPath);
        if($movedFlag){
            $tmp_name = $targetPath;
        }else{
            return self::response(-98, "파일 처리 중 오류가 발생하였습니다.", $movedFlag);
        }

        $fileId = $this->applyUploadedData($rawName, $tmp_name, $ext);

        return $fileId;
    }

    function moveTemporaryFiles($ids){
        if(is_array($ids) && sizeof($ids) > 0){
            foreach ($ids as $id){
                $sql = "SELECT * FROM tblFiles WHERE `id` = '{$id}'";
                $fileUnit = $this->getRow($sql);

                $filePath = $fileUnit["filePath"];
                $ext = $fileUnit["ext"];
                $isExisting = file_exists($filePath);
                $uniqueId = $this->makeFileName();
                $processedName = $uniqueId . "." . $ext;
                $targetDir = $this->PF_FILE_PATH;
                $targetPath = $targetDir ."/". $processedName;

                if(!self::createDir($targetDir)){
                    return -1;
                }

                if($isExisting){
                    if(rename($filePath, $targetPath)){
                        $this->applyUploadedData("", $targetPath, "", $id);
                    }else{
                        return -2;
                    }
                }else{
                    return -3;
                }
            }
        }

        return true;
    }

    function revertFilePond(){
        if($_SERVER["REQUEST_METHOD"] == 'DELETE'){
            $fileId = file_get_contents('php://input');
            return $this->revertUploadData($fileId);
        }else{
            return false;
        }
    }

    function restoreFilePond(){
        $this->loadFilePond();
    }

    function fetchFilePond(){
        // Do nothing - fetch not supported.
        return "";
    }

    function revertUploadData($id){
        $sql = "SELECT * FROM tblFiles WHERE `id`='{$id}'";
        $file = $this->getRow($sql);
        $filePath = $file["filePath"];

        if($filePath == "" || $file == ""){
            // file not exists
        }else{
            unlink($filePath);
        }

        $sql = "DELETE FROM tblFiles WHERE `id`='{$id}'";
        $this->update($sql);

        return true;
    }

    function loadFilePond(){
        $uniqueId = $_REQUEST["fileSource"];
        $sql = "SELECT * FROM tblFiles WHERE `id`='{$uniqueId}' LIMIT 1";
        $file = $this->getRow($sql);
        $this->downloadFile(
            $file["fileName"],
            $file["filePath"],
            "inline"
            );
    }

    function applyUploadedData($originName, $filePath, $extension, $onUpdateId = 0){
        if($onUpdateId != 0){
            $sql = "UPDATE `tblFiles` SET 
                    `filePath` = '{$filePath}' WHERE `id`='{$onUpdateId}'";
            $this->update($sql);
            return $onUpdateId;
        }else{
            $sql = "
                INSERT INTO `tblFiles` 
                (
                `fileName`, 
                `filePath`, 
                `ext`,  
                `regDate`
                )
                VALUES
                ( 
                '{$originName}', 
                '{$filePath}', 
                '{$extension}', 
                NOW()
                );
            ";
            $this->update($sql);
            return $this->mysql_insert_id();
        }
    }

    function downloadFile($fileName, $filePath, $disposition = "attachment"){
        $fileName = urlencode($fileName);
        header("charset:utf-8");
        header("Content-Disposition: ".$disposition."; filename=\"".$fileName."\"");
        header('Content-type: application/octet-stream');
        header('Content-Description: File Transfer');
        header("Content-Transfer-Encoding: binary");
        readfile($filePath);
    }

}
