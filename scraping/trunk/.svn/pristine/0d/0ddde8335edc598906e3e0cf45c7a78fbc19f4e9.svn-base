<?php
include 'simple_html_dom.php';
/* 
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */
date_default_timezone_set("Asia/Calcutta");
$dbhost = 'localhost';
	$dbname = 'partneroffers';

	// Connect to test database
	$m = new MongoClient("mongodb://$dbhost");
	$db = $m->$dbname;
         $response = $db->drop();
$db = $m->$dbname;

myntraDeals();
flipkartDeals();
snapdealDeals();
function snapdealDeals()
{
//    $html = file_get_html('http://www.snapdeal.com/offers/deal-of-the-day');
       $html = makeCurlRequest('http://www.snapdeal.com/offers/deal-of-the-day');
   $html=str_get_html($html);
   $startPos=strpos($html, "var mobileslist = [")+  strlen("var mobileslist = [");
   $endPos=strpos($html, "];",$startPos);
   $str=  substr($html, $startPos, $endPos-$startPos);
   $regex = '/TargetDate = "([^"]*)";/i';
    preg_match($regex, $html, $list);
    $date= new DateTime($list[1]);
   $timeExp= $date->getTimestamp();
//        print_r($list);
//         die;
 $url='http://offers.snapdeal.com/api/json/rest/getProductByIds?pogIds='.$str.'&lang=en';
       $html = makeCurlRequest($url);
   $html=str_get_html($html);
    $dataAr=  json_decode($html, true);
//    print_r($dataAr);die;
foreach($dataAr as $product) {
        $item['partner']     =   "snapdeal";
        $item['image']     =   "http://n.sdlcdn.com/".$product['image'];
        $item['title']    =   $product['name'];
        $item['mrp'] =    $product['price'];;
        $item['price'] =    $product['displayPrice'];;
        $item['link'] = "http://www.snapdeal.com/".$product['pageUrl'];
        $item['type']="DEAL OF THE DAY";
        $item['expireTime']=$timeExp;
	$item["_id"] = new MongoId();
        saveInMongo($item,"snapdeal");
        $snapealdod[] = $item;
    }
    print_r($snapealdod);
}
function makeCurlRequest($url)
{
    $curl = curl_init($url);
	curl_setopt($curl, CURLOPT_USERAGENT, "Mozilla/5.0 (Windows; U; Windows NT 10.10; ) ");
	curl_setopt($curl, CURLOPT_FAILONERROR, true);
//        curl_setopt($curl, CURLOPT_CONNECTTIMEOUT_MS ,500); 
//        curl_setopt($curl, CURLOPT_TIMEOUT_MS, 1200); 
	curl_setopt($curl, CURLOPT_FOLLOWLOCATION, true);
	curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
	$html = curl_exec($curl);
	curl_close($curl);
        return $html;
}
function flipkartDeals()
{
    $html = makeCurlRequest('http://www.flipkart.com/offers');
   $html=str_get_html($html);
     $flipkartdodTime= $html->find('.deals-timer',0)->attr['data-timestamp']+0; 
     foreach($html->find('.product-unit') as $product) {
        $item['image']     =   $product->find(".pu-image",0)->find('img',0)->attr['data-src'];
        $item['title']    = $product->find(".pu-title",0)->plaintext;
        $price=$product->find(".pu-price",0);
        if(!isset($price ) )
        {
                continue;
        }
        $mrp=$price->find('.unit',0);
        if(isset($mrp))
        {
           
        
            $item['mrp'] = $product->find(".pu-price",0)->find('.unit',0)->find('div',0)->plaintext;
            $item['price'] = $product->find(".pu-price",0)->find('.lastUnit',0)->find('div',0)->plaintext;
         }
        $item['partner']     =   "flipkart";
        $item['link'] = "http://www.flipkart.com/".$product->href;
        $item['discount'] = $product->find(".pu-offer",0)->plaintext;
        $item['type']="DEAL OF THE DAY";
        $item['expireTime']=$flipkartdodTime;
	$item["_id"] = new MongoId();
        saveInMongo($item,"flipkart");
        $fkdod[] = $item;
    }
    
    print_r($fkdod);
                                                    
}
function myntraDeals()
{
    $html = file_get_html('http://www.myntra.com/deals');
    $myntradodTime= $html->find('#dealOfTheDay-started-timer',0)->ends+0; 
    
//    echo date('D, d M Y H:i:s',$time);die;
    foreach($html->find('.deals-block') as $product) {
        $item['partner']     =   "myntra";
        $item['image']     =   $product->find('a',0)->find(".img",0)->find('img',0)->src;
        $item['title']    = $product->find(".desc",0)->find("a",0)->find('h2',0)->plaintext;
        $item['price'] = $product->find(".desc",0)->find('.price',0)->plaintext;
        $item['link'] = "http://www.myntra.com/".$product->find(".desc",0)->find('a',0)->href;
        $item['discount'] = $product->find(".desc",0)->find('.disc-info',0)->plaintext;
        $item['type']="DEAL OF THE DAY";
        $item['expireTime']=$myntradodTime;
	$item["_id"] = new MongoId();
        saveInMongo($item,"myntra");
//        $myntradod[] = $item;
    }
//    print_r($myntradod);
    
//    $myntraeventTime= $html->find('#dealOfTheDay-started-timer',0)->ends+0; 
    foreach($html->find('.events-block') as $product) {
        $item['partner']     =   "myntra";
        $item['image']     =   $product->find('a',0)->find(".img",0)->find('img',0)->src;
        $item['title']    = $product->find(".desc",0)->find('h3',0)->plaintext;
        $item['link'] = "http://www.myntra.com/".$product->find('a',0)->href;
        $item['expireTime'] = $product->find('.deal-sec',0)->find('.timer',0)->ends+0;
        $item['type'] = "SALE EVENT";
	 $item["_id"] = new MongoId();
        saveInMongo($item,"myntra");
//        $myntraevent[] = $item;
    }
     
//    print_r($myntraevent);
    
    $myntrahhTime= $html->find('#happyHourDeal-started-timer',0)->ends+0; 
    
        foreach($html->find('.hh-products',0)->find('li') as $product) {
        $item['partner']     =   "myntra";
        $item['image']     =   $product->find('a',0)->find(".img",0)->find('img',0)->src;
        $item['title']    = $product->find(".desc",0)->find('h4',0)->plaintext;
        $item['link'] ="http://www.myntra.com/". $product->find('a',0)->href;
        $item['mrp'] = $product->find(".desc",0)->find('.price',0)->find('span',0)->plaintext;
        $item['price'] = $product->find(".desc",0)->find('.price',0)->find('div',0)->plaintext;
        $item['discount'] = $product->find(".desc",0)->find('.disc',0)->plaintext;
        $item['expireTime'] = $myntrahhTime;
        $item['type'] = "HAPPY HOUR";
	 $item["_id"] = new MongoId();
//        $myntrahh[] = $item;
        saveInMongo($item,"myntra");
    }
//    print_r($myntrahh);
       
        

}

function saveInMongo($document,$partner)
{
    global $db;
	
	$c_offers = $db->$partner;

	// Insert this new document into the users collection
	$c_offers->insert($document);
        $partner="all";
        $c_offers = $db->$partner;

	// Insert this new document into the users collection
	$c_offers->insert($document);
        
}


