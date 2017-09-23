<?php

/* 
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */
include 'simple_html_dom.php';


$baseUrl='http://www.asklaila.com/search/Delhi-NCR/South-Delhi/boutique/';
//$baseUrl='http://www.asklaila.com/search/Delhi-NCR/Gurgaon/boutique/';
//$baseUrl='http://www.asklaila.com/search/Delhi-NCR/-/Boutiques/';

$page=0;
$flag=TRUE;

/*echo '<?xml version="1.0"?><items>';*/
$dbname="omg-scraping";
$m = new MongoClient("mongodb://localhost");
        $db = $m->$dbname;
//         $response = $db->drop();
$db = $m->$dbname;
$postAr=Array(
        "type"=> "html",
    "map"=>Array(
"url"=>Array(
            "selector"=> ".cardData a",
            "extract"=> "href"
        )
    ));

while($flag)
{
    $postAr['url']="http://m.asklaila.com/search/Delhi-NCR/-/Boutiques/".$page;
     $jsonString=  json_encode($postAr);
    $response=postRequest("http://localhost:8888/", $jsonString);
    $response=  json_decode($response, true);
//       $html = file_get_html($baseUrl.$page);
//       if(strpos($html,"Sorry, we do not have businesses listed")>1)
//       {$flag=false; break;}
       
       $response=$response[0]['results'];
//       print_r($response); die();
        foreach($response['url'] as $store)
        {
//           $url=$store->find("a",0)->href;
           $storeDetail= getStore($store);
        }
        $page=$page+10;
 if($page>201)
break;       
}
echo '</items>';
//getStore("http://yellowpages.sulekha.com//ghaziabad/gulabo-the-boutique-model-town-ghaziabad_contact-address");
function getStore($url)
{
    $postAr=Array(
    "url"=> "http://yellowpages.sulekha.com/delhi/time-overseas-karol-bagh-delhi_contact-address",
    "type"=>"html",
    "map"=> Array(
"title"=> Array(
            "selector"=> "span h1",
            "extract"=> "text"
        ),
"address"=> Array(
            "selector"=> ".adr",
            "extract"=> "text"
        ),
        "locality"=> Array(
            "selector"=> "span[itemprop='addressLocality']",
            "extract"=> "text"
        ),
"city"=> Array(
            "selector"=> "span[itemprop='addressRegion']",
            "extract"=> "text"
        ),
        "mobileNo"=>Array(
            "selector"=>"span[itemprop='telephone']",
            "extract"=>"text"
        ),
        "latitude"=>Array(
            "selector"=>"meta[itemprop='latitude']",
            "extract"=>"content"
        ),
"longitude"=>Array(
            "selector"=>"meta[itemprop='longitude']",
            "extract"=>"content"
        )


    )
        );

//    $postAr=  json_decode($postStr,true);
    
    $postAr["url"]=$url;
    $jsonString=  json_encode($postAr);
    $response=postRequest("http://localhost:8888/", $jsonString);
    $response=  json_decode($response, true);
    $response=$response[0]['results'];
    $response['location']=$response['longitude'][0].",".$response['latitude'][0];
    $response['url']=$url;
      
    $response["_id"] = new MongoId();
    $store= Array();
    $store["_id"] = new MongoId();
    $store["url"]=$response['url'];
    $store["title"]=$response['title'][0];
    $store["address"]=$response['address'][0];
    $store["locality"]=$response['locality'][0];
    $store["city"]=$response['city'][0];
    $store["primaryNo"]=$response['mobileNo'][0];
    $store["mobileNo"]=$response['mobileNo'][1];
    
    if(empty($store["title"]) && empty($store["primaryNo"]) && $store["mobileNo"])
   return; 
    $store["location"]="";
    unset($store["location"]);
    $store["longitude"]=$response['longitude'][0];
    $store["latitude"]=$response['latitude'][0];
    if(isset($response['longitude'][0]) && isset($response['latitude'][0]))
    {
//        "geo" : { "lat" : 45.52318, "lng" : -122.6878289 }
        $store["geo"]=Array( "lng"=> $response['longitude'][0], "lat"=>$response['latitude'][0]);//$response['location'];
        $store["location"]=Array( "type"=> "Point", "coordinates"=> Array(0+$response['longitude'][0], 0+$response['latitude'][0]));//$response['location'];
    }
    $store['createdByScript']=true;
    print_r($store); 
    saveInMongo($store,"Merchant-asklaila");

//echo "<item><name>".$response['name'][0]."</name><address>".$response['address'][0]."</address><locality>".$response['locality'][0]."</locality><region>".$response['region'][0],"</region><mobile>".$response['mobile'][0]."</mobile></item>";
    
}
function saveInMongo($document,$dbName)
{
    global $db;

        $c_offers = $db->$dbName;

        // Insert this new document into the users collection
        $c_offers->save($document);
       

}

die;
//exec('sudo rm output.txt');

//echo $html->find('.cat1dropdown',0)->plaintext;
function postRequest($url,$jsonString)
{
    $jsonString;
    $baseUpdateUrl=$url;


    $ch = curl_init($baseUpdateUrl);
    curl_setopt($ch, CURLOPT_VERBOSE, 1);
    curl_setopt($ch, CURLOPT_CUSTOMREQUEST, "POST");
    curl_setopt($ch, CURLOPT_POSTFIELDS, $jsonString);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
//    curl_setopt($ch, CURLOPT_FAILONERROR, 1);
    curl_setopt($ch, CURLOPT_FOLLOWLOCATION, 1);// allow redirects
    curl_setopt($ch, CURLOPT_RETURNTRANSFER,1); // return into a variable
    $cookie_file = "cookie1.txt";
//curl_setopt($ch, CURLOPT_COOKIESESSION, true);
//curl_setopt($ch, CURLOPT_COOKIEFILE, $cookie_file);
//curl_setopt($ch, CURLOPT_COOKIEJAR, $cookie_file);
    curl_setopt($ch,CURLOPT_USERAGENT,'Mozilla/5.0 (Windows; U; Windows NT 5.1; en-US; rv:1.8.1.13) Gecko/20080311 Firefox/2.0.0.13');
    curl_setopt($ch, CURLOPT_HTTPHEADER, array(
    'Content-Type: application/json',
    'Content-Length: ' . strlen($jsonString))
);
 date_default_timezone_set("Asia/Calcutta");
$result = curl_exec($ch);
$headers = curl_getinfo($ch, CURLINFO_HEADER_OUT);
print_r($headers);
print_r($result); 
return $result;
}

