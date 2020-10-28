<?php

include_once $_SERVER["DOCUMENT_ROOT"]."/mygift/shared/public/classes/Routable.php";
require_once($_SERVER["DOCUMENT_ROOT"]."/mygift/shared/bases/modules/bootpay/autoload.php");
spl_autoload_register('BootpayAutoload');
use Bootpay\Rest\BootpayApi;

class PayRoute extends Routable {

    function getPayUnique(){
        $unique = $_REQUEST["unique"];
        $price = $_REQUEST["price"];
        $temp = AuthUtil::getLoggedInfo()->id."-".$unique."-".$price."-".time();

        return self::response(1, "Success.", $temp);
    }

    function getPoint($userId){
        $sql = "SELECT SUM(amount) AS val FROM tblPointStack WHERE `userId`={$userId}";
        $val = $this->getValue($sql, "val");
        if($val == "") $val = 0;
        return $val;
    }

    function validatePayment(){
        $payData = $_REQUEST["data"];

        $receiptId = $payData["receipt_id"];

        $bootpay = BootpayApi::setConfig(
            '5c35df57b6d49c67f6bf7026',
            'lQ5jXyv+IryJYJgYXp2iuLScoQKVxNfwXD0aECobsFs='
//            'development' // production
        );
        $response = $bootpay->requestAccessToken();

        if ($response->status === 200) {
            $result = $bootpay->verify($receiptId);
//            var_dump($result);
            if ($result->data->price == $payData["originPrice"] && $result->data->status === 1) {
                $this->onPaySuccess($payData);
                return self::response(1, "결제가 완료되었습니다.");
            }
        }
        return self::response(-1, "결제 처리 중 오류가 발생하였습니다.", $result);
    }

    function onPaySuccess($bootpayResponse){
        $userId = AuthUtil::getLoggedInfo()->id;
        $amount = $bootpayResponse["originCharge"];
        $receiptId = $bootpayResponse["receipt_id"];
        $comment = $bootpayResponse["item_name"];
        $price = $bootpayResponse["originPrice"];
        $orderId = $bootpayResponse["order_id"];
        $methodName = $bootpayResponse["method_name"];
        $payName = $bootpayResponse["payment_name"];
        $purchasedAt = $bootpayResponse["purchased_at"];
        $reqIp = $_SERVER['REMOTE_ADDR'].":".$_SERVER['SERVER_PORT'];

        $sql = "INSERT INTO tblPointStack( 
                `userId`,
                `amount`, 
                `receiptId`, 
                `comment`, 
                `price`,
                `orderId`, 
                `methodName`, 
                `payName`, 
                `purchasedAt`, 
                `reqIp`,
                `regDate`)
                VALUES(
                  '{$userId}',
                  '{$amount}',
                  '{$receiptId}',
                  '{$comment}',
                  '{$price}',
                  '{$orderId}',
                  '{$methodName}',
                  '{$payName}',
                  '{$purchasedAt}',
                  '{$reqIp}',
                  NOW()
                )
                ";

        $this->update($sql);
    }

    function validateHash($orderId, $userId, $amount){
        $dec = $this->decryptAES256($orderId);
        $val = $userId."-".POINT_AVALANCHE."-".$amount;
        if($dec == $val) return true;
        else return false;
    }

    function addAmount($userId, $amount, $comment){
        if(AuthUtil::getLoggedInfo()->id != $userId){
            return self::response(-2, "권한이 없습니다.");
        }

        $reqIp = $_SERVER['REMOTE_ADDR'].":".$_SERVER['SERVER_PORT'];

        $orderId = $this->encryptAES256($userId."-".POINT_AVALANCHE."-".$amount);

        $sql = "INSERT INTO tblPointStack( 
                `userId`,
                `amount`, 
                `receiptId`, 
                `comment`, 
                `orderId`, 
                `methodName`, 
                `payName`, 
                `purchasedAt`, 
                `reqIp`,
                `regDate`)
                VALUES(
                  '{$userId}',
                  '{$amount}',
                  'S',
                  '{$comment}',
                  '{$orderId}',
                  '#',
                  '#',
                  NOW(),
                  '{$reqIp}',
                  NOW()
                )
                ";
        $this->update($sql);

        return self::response(1, "성공적으로 처리되었습니다.");
    }

}
