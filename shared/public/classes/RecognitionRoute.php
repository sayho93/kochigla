<?php
include_once $_SERVER["DOCUMENT_ROOT"]."/mygift/shared/public/classes/Routable.php";
require $_SERVER["DOCUMENT_ROOT"] . "/mygift/assets/sdk/aws.phar";
use Aws\Rekognition\RekognitionClient;

class RecognitionRoute extends FileRoute{
    function test(){
        $credentials = new Aws\Credentials\Credentials('AKIA2FH62F4Z5HZOM5WD', 'B/WET3y7ubKNrqrPN3+lpdgsWy/qH/Cp31l9h3Qb');

        $rekognitionClient = RekognitionClient::factory(array(
            'region'    => "ap-northeast-2",
            'version'   => 'latest',
            'credentials' => $credentials
        ));

        $compareFaceResults = $rekognitionClient->compareFaces([
            'SimilarityThreshold' => 80,
            'SourceImage' => [
                'Bytes' => file_get_contents($_SERVER["DOCUMENT_ROOT"] . "/mygift/images/1-1.png")
            ],
            'TargetImage' => [
                'Bytes' => file_get_contents($_SERVER["DOCUMENT_ROOT"] . "/mygift/images/1-2.png")
            ],
        ]);

        $FaceMatchesResult = $compareFaceResults['FaceMatches'];
        $SimilarityResult =  $FaceMatchesResult[0]['Similarity']; //Here You will get similarity
        $sourceImageFace = $compareFaceResults['SourceImageFace'];
        $sourceConfidence = $sourceImageFace['Confidence']; // //Here You will get confidence of the picture

        echo $compareFaceResults;
        echo "<br>";
        echo ":::";
        echo "Similarity: " . $SimilarityResult;
        echo "<br>";
        echo ":::";
        echo "Confidence: " . $sourceConfidence;
    }

    function recognition($first, $second){
        $credentials = new Aws\Credentials\Credentials('AKIA2FH62F4Z5HZOM5WD', 'B/WET3y7ubKNrqrPN3+lpdgsWy/qH/Cp31l9h3Qb');

        $rekognitionClient = RekognitionClient::factory(array(
            'region'    => "ap-northeast-2",
            'version'   => 'latest',
            'credentials' => $credentials
        ));

        $compareFaceResults = $rekognitionClient->compareFaces([
            'SimilarityThreshold' => 80,
            'SourceImage' => [
                'Bytes' => file_get_contents($first)
            ],
            'TargetImage' => [
                'Bytes' => file_get_contents($second)
            ],
        ]);

        $FaceMatchesResult = $compareFaceResults['FaceMatches'];
        $SimilarityResult =  $FaceMatchesResult[0]['Similarity']; //Here You will get similarity
        $sourceImageFace = $compareFaceResults['SourceImageFace'];
        $sourceConfidence = $sourceImageFace['Confidence']; // //Here You will get confidence of the picture

        return array("similarity" => $SimilarityResult, "confidence" => $sourceConfidence);
    }

    function authImg(){
        $id = $_REQUEST["id"];
        $thumbId = $_REQUEST["thumbId"];
        $img = $_FILES["authImg"];

        $thumb = self::getRow("SELECT * FROM tblFile WHERE `id` = '{$thumbId}'");

        if($img["tmp_name"][0] != ""){
            $tmp = self::procFiles($img, 0);
            $thumbId = $tmp[$img["name"][0]]["id"];
        }

        $res = self::recognition($thumb["path"], $tmp[$img["name"][0]]["path"]);

        if($res["similarity"] >= 99){
            $ins = "
                UPDATE tblUser 
                SET `isAuthorized` = '1'
                WHERE `id` = '{$id}'
            ";
            self::update($ins);
            return self::response(1, "인증되었습니다.");
        }else{
            return self::response(-1, "프로필 사진과 매치되지 않습니다.");
        }
    }
}