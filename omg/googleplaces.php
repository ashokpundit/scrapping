<?php

/* 
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */
include 'simple_html_dom.php';
$dbname="omg-scraping";
$m = new MongoClient("mongodb://localhost");
        $db = $m->$dbname;
//         $response = $db->drop();
$db = $m->$dbname;

$next_page_token="";
   
     do
     {
          //$mainUrl="https://maps.googleapis.com/maps/api/place/nearbysearch/json?keyword=boutiques&type=clothing_store&radius=10000&location=28.4659573,77.0287582&key=AIzaSyDT2H5kpbsl8wjg5CLKF60tyie6b59yIpg&pagetoken=".$next_page_token;
         $mainUrl="https://maps.googleapis.com/maps/api/place/nearbysearch/json?keyword=boutiques&type=clothing_store&radius=20000&location=28.6353080,77.2249600&key=AIzaSyDT2H5kpbsl8wjg5CLKF60tyie6b59yIpg&pagetoken=".$next_page_token;
    
            $response=postRequest($mainUrl,null);
             $response=  json_decode($response, true);
             print_r($response);
             if(isset($response['next_page_token']))
             $next_page_token=$response['next_page_token'];
             else
                 $next_page_token=NULL;
            $results=$response['results'];
            foreach ($results as $result)
            {
                echo $result['place_id'];
                savePlace($result['place_id']);
            }
     }while($next_page_token);
     
     
function savePlace($placeId)
{
    $placeUrl="https://maps.googleapis.com/maps/api/place/details/json?placeid=".$placeId."&key=AIzaSyDT2H5kpbsl8wjg5CLKF60tyie6b59yIpg";
    $response=postRequest($placeUrl,null);
     $response=  json_decode($response, true);
     $result=$response['result'];
     saveInMongo($result, "google_places");
}
     
     
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

function saveInMongo($document,$collectionName)
{
    global $db;

        $c_collection = $db->$collectionName;

        // Insert this new document into the users collection
        $c_collection->save($document);
       

}