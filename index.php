<?php
require 'Blockchain.php';
require_once('vendor/autoload.php');
$assetId='3MbLY1vNvecmHexUQfubEnNMeMZeEn4Hi1KvroM84wVA';
$add='3P6o6H66hPrSbtLwvox8Bss4krSnKmyFkum';
$yesVotes=0;
$noVotes=0;
$falseVotes=0;
$doubleVotes=0;
$membersVoted=0;
$voted=[];
$lowest = 0;
$highest = 9999999999999999999999999999999999;

//the richlist is done with an api call, an asset call, with the situation of itemx .

$json = file_get_contents("richlist.json");
$mydata = json_decode($json,true);
echo 'Voting is happening with on base off the TurleNode richlist.<br>To vote please send yes or no as attachment to: '.$add;
echo '<br>Please be aware there is a tx-fee for voting.';

$block = new Blockchain($add);

$transfers = $block->getAllTransfers();

$base = new StephenHill\Base58();
foreach($transfers[0] as $items){
    //echo json_encode($items, JSON_PRETTY_PRINT);
    //$items['assetId']=='3MbLY1vNvecmHexUQfubEnNMeMZeEn4Hi1KvroM84wVA'&&
    if( $items['timestamp'] > $lowest && $items['timestamp'] < $highest) {
        if (isset($mydata[$items['sender']])) {
            if ($items['type'] == 4 && !in_array($items['sender'], $voted)) {

                $dec = $base->decode($items["attachment"]);
                if (strpos(strtoupper($dec), 'YES') !== false) {
                    $yesVotes += $mydata[$items['sender']];
                    $voted[] = $items['sender'];
                    $membersVoted++;
                } elseif (strpos(strtoupper($dec), 'NO') !== false) {
                    $noVotes += $mydata[$items['sender']];
                    $voted[] = $items['sender'];
                    $membersVoted++;
                } else {
                    $falseVotes += $mydata[$items['sender']];
                }
            } elseif (in_array($items['sender'], $voted)) {
                $doubleVotes++;
            }
        }
    }
}

//echo '<br>There were '.$yesVotes.' yes votes, '.$noVotes.' no votes and '.$falseVotes.' false votes.';
//echo'<br>'.$doubleVotes.' people tried to cheat and sent more then 1 vote.';
$total = $yesVotes+$noVotes+$falseVotes;
echo '<br>';
echo '<br>Votes Count';
echo '<br>Yes: '.$yesVotes.' '.round(($yesVotes/$total)*100,3).'%';
echo '<br>No: '.$noVotes.' '.round(($noVotes/$total)*100,3).'%';
echo '<br>False: '.$falseVotes.' '.round(($falseVotes/$total)*100,3).'%';
echo '<br>Filtered double votes: '.$doubleVotes;
echo '<br>Amount yes and no voters: '.$membersVoted;
