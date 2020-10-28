<?php

include_once $_SERVER["DOCUMENT_ROOT"]."/mygift/shared/public/classes/Routable.php";

class FileRoute extends Routable {

    static function createDir($path) {
        if (is_dir($path)) return true;
        $prev_path = substr($path, 0, strrpos($path, '/', -2) + 1 );
        $return = self::createDir($prev_path);
        return ($return && is_writable($prev_path)) ? mkdir($path) : false;
    }

    function procFiles($file, $userKey, $boardId = 0){
        $fileInfo = array();
        $fileIds = array();

        for($e = 0; $e < count($file["name"]); $e++){
            $tmp_name = $file["tmp_name"][$e];
            $rawName = basename($file["name"][$e]);
            $ext = pathinfo($rawName,PATHINFO_EXTENSION);

            if($tmp_name == ""){
                break;
            }
            // Ext check
            $size = $file["size"][$e];

            $targetDir = $this->PF_FILE_TEMP_PATH;
            $shortTargetDir = $this->PF_FILE_TEMP_SHORT;
            if(!self::createDir($targetDir)){
                return self::response(-99, "파일 처리 중 경로 오류가 발생하였습니다.");
            }
            $fName = $this->makeFileName();
            $targetPath = $targetDir."/".$fName;
            $short = $shortTargetDir."/".$fName;
            $movedFlag = move_uploaded_file($tmp_name, $targetPath);
            if($movedFlag){
                $tmp_name = $targetPath;
            }else{
                return self::response(-98, "파일 처리 중 오류가 발생하였습니다.", $movedFlag);
            }

            $fileId = $this->applyUploadedData($rawName, $tmp_name, $ext, $userKey, $short, $size, $e);

            $mime = mime_content_type($targetPath);
            // Cannot cover the situation with File Name Conflict
            $fileIds[$e] = $fileId;
            $fileInfo[$file["name"][$e]]["id"] = $fileId;
            $fileInfo[$file["name"][$e]]["name"] = $rawName;
            $fileInfo[$file["name"][$e]]["size"] = filesize($targetPath);
            $fileInfo[$file["name"][$e]]["path"] = $targetPath;
            $fileInfo[$e]["data"] = 'data:'.$mime.';base64,'.base64_encode(file_get_contents($targetPath));
        }

        $this->updateBoardIds($boardId, $fileIds);

        return $fileInfo;
    }

    function updateBoardIds($boardId, $fileIds){
        $idsString = $fileIds;
        if(is_array($fileIds)){
            $idsString = implode(',', $fileIds);
        }

        $sql = "UPDATE tblFile SET boardId = '{$boardId}' 
                WHERE `id` IN (".$idsString.")";
        $this->update($sql);
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

    function applyUploadedData($originName, $filePath, $extension, $userKey, $short, $size = 0, $order = 0){
        $sql = "
                INSERT INTO `tblFile` 
                (
                `originName`, 
                `path`,
                `shortPath`, 
                `ext`,
                `size`,
                `order`,
                `userKey`
                )
                VALUES
                ( 
                '{$originName}', 
                '{$filePath}', 
                '{$short}',
                '{$extension}',
                '{$size}',
                '{$order}',
                '{$userKey}'
                );
            ";
        $this->update($sql);
        return $this->mysql_insert_id();
    }

//    function applyUploadedData($originName, $filePath, $extension, $onUpdateId = 0){
//        if($onUpdateId != 0){
//            $sql = "UPDATE `tblFiles` SET
//                    `filePath` = '{$filePath}' WHERE `id`='{$onUpdateId}'";
//            $this->update($sql);
//            return $onUpdateId;
//        }else{
//            $sql = "
//                INSERT INTO `tblFiles`
//                (
//                `fileName`,
//                `filePath`,
//                `ext`,
//                `regDate`
//                )
//                VALUES
//                (
//                '{$originName}',
//                '{$filePath}',
//                '{$extension}',
//                NOW()
//                );
//            ";
//            $this->update($sql);
//            return $this->mysql_insert_id();
//        }
//    }

    function downloadFile($fileName, $filePath, $disposition = "attachment"){
        $home = $this->PF_URL;
        if(strstr($_SERVER["HTTP_REFERER"], $home) != false){
            $fileName = urlencode($fileName);
            header("charset:utf-8");
            header("Content-Disposition: ".$disposition."; filename=\"".$fileName."\"");
            header('Content-type: application/octet-stream');
            header('Content-Description: File Transfer');
            header("Content-Transfer-Encoding: binary");
            readfile($filePath);
        }else{
            return self::response(-1, "abnormal approach detected");
        }
    }

    function getFile($id){
        $sql = "SELECT * FROM tblFile WHERE `id`='{$id}'";
        return $this->getRow($sql);
    }

    function downloadFileById(){
        $id = $_REQUEST["id"];
        $file = $this->getFile($id);
        return $this->downloadFile($file["originName"], $file["path"]);
    }

}
