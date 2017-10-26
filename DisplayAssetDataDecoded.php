<?php
/**
 * Created by PhpStorm.
 * User: bramV
 * Date: 23/10/2017
 * Time: 11:12
 */

class DisplayAssetDataDecoded
{
    private $txID;

    /**
     * DisplayAssetDataDecoded constructor.
     * @param $txID
     */

    function showAssestDecoded($txID){
        $ch = curl_init('http://localhost:6869/transactions/info/'.$txID);
        curl_setopt_array($ch, array(
            CURLOPT_RETURNTRANSFER => TRUE,
            CURLOPT_HTTPHEADER => array(
                'Content-Type: application/json'
            ),
        ));
        $response = curl_exec($ch);
        if ($response === FALSE) {
            die(curl_error($ch));
        }
        $responseData = json_decode($response, TRUE);

        return $responseData;
    }

}
