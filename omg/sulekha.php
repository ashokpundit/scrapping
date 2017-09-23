<?php

/* 
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */
include 'simple_html_dom.php';

$baseUrl='http://yellowpages.sulekha.com/boutiques-shop-stores_delhi_';
$page=9;
$flag=TRUE;
/*echo '<?xml version="1.0"?><items>';*/
while($flag)
{
       $html = file_get_html($baseUrl.$page);
       if(strpos($html,"Sorry, we do not have businesses listed")>1)
       {$flag=false; break;}
       
        foreach($html->find('.GAQ_C_BUSL') as $store)
        {
           $url=$store->href;
           $storeDetail= getStore("http://yellowpages.sulekha.com".$url);
        }
        $page++;
        
}
echo '</items>';
//getStore("http://yellowpages.sulekha.com//ghaziabad/gulabo-the-boutique-model-town-ghaziabad_contact-address");
function getStore($url)
{
    $postAr=Array(
    "url"=> "http://yellowpages.sulekha.com/delhi/time-overseas-karol-bagh-delhi_contact-address",
    "type"=>"html",
    "map"=> Array(
"name"=> Array(
            "selector"=> "h1 span",
            "extract"=> "text"
        ),
"address"=> Array(
            "selector"=> ".data-item",
            "extract"=> "text"
        ),
        "locality"=> Array(
            "selector"=> "span[itemprop='addressLocality'] a",
            "extract"=> "text"
        ),
"region"=> Array(
            "selector"=> "span[itemprop='addressRegion']",
            "extract"=> "text"
        ),
        "mobile"=>Array(
            "selector"=>"em[itemprop='telephone']",
            "extract"=>"text"
        )
    )
        );

//    $postAr=  json_decode($postStr,true);
    
    $postAr["url"]=$url;
    $jsonString=  json_encode($postAr);
    $response=postRequest("http://localhost:8888/", $jsonString);
    $response=  json_decode($response, true);
    $response=$response[0]['results'];
//    print_r($response); die;
echo "<item><name>".$response['name'][0]."</name><address>".$response['address'][0]."</address><locality>".$response['locality'][0]."</locality><region>".$response['region'][0],"</region><mobile>".$response['mobile'][0]."</mobile></item>";
    
}

die;
//exec('sudo rm output.txt');

//echo $html->find('.cat1dropdown',0)->plaintext;
function postRequest($url,$jsonString)
{
    $jsonString;
    $baseUpdateUrl=$url;


    $ch = curl_init($baseUpdateUrl);
    curl_setopt($ch, CURLOPT_CUSTOMREQUEST, "POST");
    curl_setopt($ch, CURLOPT_POSTFIELDS, $jsonString);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($ch, CURLOPT_HTTPHEADER, array(
    'Content-Type: application/json',
    'Content-Length: ' . strlen($jsonString))
);
 date_default_timezone_set("Asia/Calcutta");
$result = curl_exec($ch);
//print_r($result);
return $result;
}

