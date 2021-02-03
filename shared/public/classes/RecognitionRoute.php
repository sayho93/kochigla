<?php
include_once $_SERVER["DOCUMENT_ROOT"]."/mygift/shared/public/classes/Routable.php";
require $_SERVER["DOCUMENT_ROOT"] . "/mygift/assets/sdk/aws.phar";
use Aws\Rekognition\RekognitionClient;

class RecognitionRoute extends Routable{
    function recognition(){
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
}