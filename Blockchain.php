<?php
/**
 * Created by PhpStorm.
 * User: bramV
 * Date: 24/10/2017
 * Time: 18:26
 */

class Blockchain
{
    private $add;

    /**
     * Blockchain constructor.
     * @param $add
     */
    public function __construct($add)
    {
        $this->add = $add;
    }

    function getAllTransfers(){
        $ch = curl_init('http://localhost:6869/transactions/address/'.$this->add.'/limit/10000');
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