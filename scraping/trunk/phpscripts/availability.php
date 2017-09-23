<?php
//error_reporting(E_ALL);
//ini_set('display_errors', 1);
 date_default_timezone_set("Asia/Calcutta");
 
//echo "result=".$result=  getPriceFromAmzon('http://www.amazon.in/gp/product/B00NF8VHCO/ref=s9_al_bw_g309_i4?pf_rd_m=A1VBAL9TL5WCBF&pf_rd_s=merchandised-search-6&pf_rd_r=0ZF47HXEAWRJ31VMKSXT&pf_rd_t=101&pf_rd_p=540412487&pf_rd_i=1983519031');
//die;
//echo "result=".$result=getPriceFromSnapDeal('http://www.snapdeal.com//product//redfort-redfort031-blue-shoulder-bags//1303787233');
//die;
// getPriceFromZovi('http://zovi.com/slim-fit-casual-cotton-blue-premium-solid-shirt-with-contrast-detailing-full-sleeves--10325205101?misc_ref_code=reco_todays_hot_sellers');
//;
//die;
//print_r($argv);die;
$mainUrl=$argv[1];
//parse_str(implode('&', array_slice($argv, 1)), $_GET);
$womefashion='http://www.yebhi.com//online-shopping/women.html?fq=For=Women';
//$url = "http://www.flipkart.com/lee-sleeveless-solid-men-s-jacket/p/itmdzkp5yrxjk9ch";
 $start=time();
 echo "url=".$mainUrl;
$links=getLinks($mainUrl); 
//print_r($links); 
$index=0;
$result=Array();
$jsonString='[';//[{"id":"710635","out":{"set":"false"}}]
foreach ($links[1] as $link) {
    
    
    $data=getDataFromURL($link);
	$link=  stripcslashes($link);

    if(isset($data) && $data===0)
    {
        $jsonString.='{"id":"'.$links[2][$index].'","out":{"set":"true"}},';
        echo $link. " ".$links[2][$index]."\n";
//        break;
    }
    
//    $result[$link[1]]=Array();
//    $result[$link[1]]['av']=$data;
//    $result[$link[1]]['url']=$link[0];
//    echo "\n".$link[0]." ".$data."\n";
    $index++;
}
$jsonString=trim($jsonString,",");
    $jsonString.=']';
updateInSolr($jsonString);
 $end=time();
// echo "time=".($end-$start);
//print_r($result);
die;


//$response = getPriceFromFlipkart($url);
// 
//echo json_encode($response);
 
/* Returns the response in JSON format */
 
function getLinks($yebhiUrl)
{
        $yebhiflipkart="http://www.yebhi.com/online-shopping/Apparels/flipkart.html?fq=vendor=flipkart";
        $yebhimyntra="http://www.yebhi.com/online-shopping/Apparels/myntra.html?fq=vendor=myntra";
        $yebhijabong="http://www.yebhi.com/online-shopping/search.html?q=jabong";
        
        $curl = curl_init($yebhiUrl);
	curl_setopt($curl, CURLOPT_USERAGENT, "Mozilla/5.0 (Windows; U; Windows NT 10.10; ) ");
	curl_setopt($curl, CURLOPT_FAILONERROR, true);
	curl_setopt($curl, CURLOPT_FOLLOWLOCATION, true);
	curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
	 $html = curl_exec($curl);
	curl_close($curl);
// die;
	$regex = '/url="([^"]*)" id="anchor_[0-9]{2}_([0-9]*)"/i';
	preg_match_all($regex, $html, $url,PREG_PATTERN_ORDER);
//        print_r($url);
//        die;
        return $url;
        
         
}

function getDataFromURL($url)
{
    $start=time();
    if(strpos($url,'www.jabong.com')>0)    
    $r=getPriceFromJabong( $url);
    else if(strpos($url,'www.myntra.com')>0)    
    $r=  getPriceFromMyntra( $url);
    else if(strpos($url,'www.flipkart.com')>0)    
    $r= getPriceFromFlipkart( $url);
    else if(strpos($url,'zovi.com')>0)    
    $r= getPriceFromZovi( $url);
    else if(strpos($url,'www.asos.com')>0)    
    $r= getPriceFromAsos( $url);
    else if(strpos($url,'www.snapdeal.com')>0)    
    $r= getPriceFromSnapDeal( $url);
    else if(strpos($url,'www.kvoos.com')>0)    
    $r= getPriceFromKoovs( $url);
    else if(strpos($url,'www.amazon.in')>0)    
    $r= getPriceFromAmazon( $url);
    else if(strpos($url,'www.trendin.com')>0)    
    $r= getPriceFromTrendin( $url);
    else if(strpos($url,'www.yepme.com')>0)    
    $r= getPriceFromYepme( $url);
    else if(strpos($url, "www.basicslife.com"))
    $r=getPriceFromBasicsLife($url);
    
//    else if(strpos($url,'www.fashionandyou.com')>0)    
//    $r= getPriceFromFashionAndYou( $url);
    
    
    
    
     
//    echo "url=".$url;
//    print_r($r);
    $end=time();
//    echo "time=".$timeTaken=$end-$start;
    if(isset($r))
	return $r;
    else
        return -1;
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
function getPriceFromFlipkart($url) {
  usleep(100000);
 $url=  stripcslashes($url);
// echo "url=".$url;
	$html=makeCurlRequest($url);
 if(empty($html))
    return -1;
//	$regex = '/<meta itemprop="price" content="([^"]*)"/';
//	preg_match($regex, $html, $price);
// 
//	$regex = '/<h1[^>]*>([^<]*)<\/h1>/';
//	preg_match($regex, $html, $title);
// 
//	$regex = '/data-src="([^"]*)"/i';
//	preg_match($regex, $html, $image);
        $regex = '/data-evar14="([^"]*)"/i';
	preg_match($regex, $html, $avail);
        
        if($avail[1]=="In Stock")
            return 1;
        else
            return 0;
        
        echo "\n". $url." av= ".$avail[1];        
	if ( $title && $image) {
            $response = array("url"=>$url,"price" => "$price[1]", "availability" =>$avail[1]);
//		$response = array("price" => "Rs. $price[1].00", "image" => $image[1], "title" => $title[1],"availability" =>$avail[1], "status" => "200");
	} else {
		$response = array("status" => "404", "error" => "We could not find the product details on Flipkart $url");
	}
 
	return $response;
}

function getPriceFromMyntra($url) {
     usleep(100000);
// echo "url=".$url;
  $start=time();
 $url=  stripcslashes($url);
	$html=makeCurlRequest($url);
 if(empty($html))
    return -1;
	
//	$regex = '/"priceData":(.*),/';
//        preg_match($regex, $html, $data);
        $regex = '/"productInStock":(.*)}/';
        preg_match($regex, $html, $availability);
        if(!isset($availability[1]))
        {
            return 0;
        }
        return $availability[1];
//        $regex = '/"price":([0-9]*),/';
//        preg_match($regex, $html, $mrp);
//        $regex = '/"discountedPrice":([0-9]*),/';
//        preg_match($regex, $html, $price);
        
            echo "\n". $url." av= ".$availability[1];        
            
// print_r($data);
                
	if ($data ) {
            $response = array("url"=>$url,"price"=>$price[1],"mrp"=>$mrp[1],/*"data" => $data[1],*/"availability" =>$availability[1]);
//		$response = array("price" => "Rs. $price[1].00", "image" => $image[1], "title" => $title[1],"availability" =>$avail[1], "status" => "200");
	} else {
		$response = array("status" => "404", "error" => "We could not find the product details on Flipkart $url");
	}
  $end=time();
//        echo "time=".$timeTaken=$end-$start;
	return $response;
}


function getPriceFromJabong($url) {
// echo "url=".$url;
  $start=time();
 $url=  stripcslashes($url);
 usleep(100000);
	$html=makeCurlRequest($url);
if(empty($html))
    return -1;
	
//	$regex = '/sku_code:(.*),/';
//        preg_match($regex, $html, $sku);
//        $regex = '/id:(.*),/';
//        preg_match($regex, $html, $id);
//         $regex = '/price:(.*),/';
//        preg_match($regex, $html, $price);
//         $regex = '/listPrice:(.*),/';
//        preg_match($regex, $html, $mrp);
//         $regex = '/category:(.*),/';
//        preg_match($regex, $html, $category);
//          $regex = '/subcategory:(.*),/';
//        preg_match($regex, $html, $subcategory);
//          $regex = '/brand:(.*),/';
//        preg_match($regex, $html, $brand);
//          $regex = '/gender:(.*),/';
//        preg_match($regex, $html, $gender);
//        $regex = '/discount:(.*),/';
//        preg_match($regex, $html, $discount);
        
        $regex = '/add-to-cart/';
        preg_match($regex, $html, $sizes);
//        print_r($sizes);
        if(count($sizes)>0)
        {
            return 1;
        }
        return 0;
//        $regex = '/availability:"([a-zA-Z]*)"/';
//        preg_match($regex, $html, $availability);
//        
//        print_r($availability);
//        if(!isset($availability[1]))
//        {
//            return 0;
//        }
//        if($availability[1]=='Yes')
//            return 1;
//        else
//            return 0;
         $regex = '/url:(.*),/';
        preg_match($regex, $html, $url1);
         $regex = '/description:(.*),/';
        preg_match($regex, $html, $description);
        $regex = '/image:(.*),/';
        preg_match($regex, $html, $image);

        
            
            echo "\n". $url." av= ".$availability[1];  
// print_r($data);
                
	if ($mrp ) {
            $response = array("url"=>$url,"price"=>$price[1],"mrp"=>$mrp[1],/*"data" => $data[1],*/"availability" =>$availability[1]);
//		$response = array("price" => "Rs. $price[1].00", "image" => $image[1], "title" => $title[1],"availability" =>$avail[1], "status" => "200");
	} else {
		$response = array("url"=>$url,"status" => "404", "error" => "We could not find the product details on Flipkart $url");
	}
  $end=time();
//        echo "time=".$timeTaken=$end-$start;
	return $response;
}

function getPriceFromZovi($url) {
     usleep(100000);
// echo "url=".$url;
  $start=time();
 $url=  stripcslashes($url);
	$html=makeCurlRequest($url);
 if(empty($html))
    return -1;
        $regex = '/var optionDetails=(.*);/';
        preg_match($regex, $html, $json);
        $responseAr=json_decode($json[1], true);
//	print_r($responseAr['sizes']);
        foreach ($responseAr['sizes'] as $sizes)
        {
            if($sizes['avl']=='true')
                return 1;
        }
        return 0;
        
        echo "\n". $url." av= ".$availability[1];  
            
        print_r($json);
                
	if ($mrp ) {
            $response = array("url"=>$url,"price"=>$price[1],"mrp"=>$mrp[1],/*"data" => $data[1],*/"availability" =>$availability[1]);
//		$response = array("price" => "Rs. $price[1].00", "image" => $image[1], "title" => $title[1],"availability" =>$avail[1], "status" => "200");
	} else {
		$response = array("url"=>$url,"status" => "404", "error" => "We could not find the product details on Flipkart $url");
	}
  $end=time();
//        echo "time=".$timeTaken=$end-$start;
	return $response;
}

function getPriceFromAsos($url) {
     usleep(100000);
// echo "url=".$url;
  $start=time();
 $url=  stripcslashes($url);
	$html=makeCurlRequest($url);
 if(empty($html))
    return -1;
        $regex = '/"AvailableSizes":(.*),/';
        preg_match($regex, $html, $sizes);
        
        if($sizes==0)
        {
            return 0;
        }
        return 1;
        
         echo "\n". $url." av= ".$availability[1];  
            
        print_r($json);
                
	if ($mrp ) {
            $response = array("url"=>$url,"price"=>$price[1],"mrp"=>$mrp[1],/*"data" => $data[1],*/"availability" =>$availability[1]);
//		$response = array("price" => "Rs. $price[1].00", "image" => $image[1], "title" => $title[1],"availability" =>$avail[1], "status" => "200");
	} else {
		$response = array("url"=>$url,"status" => "404", "error" => "We could not find the product details on Flipkart $url");
	}
  $end=time();
//        echo "time=".$timeTaken=$end-$start;
	return $response;
}

function getPriceFromSnapDeal($url) {
     usleep(100000);
// echo "url=".$url;
  $start=time();
 $url=  stripcslashes($url);
	$html=makeCurlRequest($url);
 if(empty($html))
    return -1;
        $regex = '/itemprop="availability"/';
        preg_match($regex, $html, $sizes);
//        print_r($sizes);
        if(count($sizes)>0)
        {
            return 1;
        }
        return 0;
        
         echo "\n". $url." av= ".$availability[1];  
            
        print_r($json);
                
	if ($mrp ) {
            $response = array("url"=>$url,"price"=>$price[1],"mrp"=>$mrp[1],/*"data" => $data[1],*/"availability" =>$availability[1]);
//		$response = array("price" => "Rs. $price[1].00", "image" => $image[1], "title" => $title[1],"availability" =>$avail[1], "status" => "200");
	} else {
		$response = array("url"=>$url,"status" => "404", "error" => "We could not find the product details on Flipkart $url");
	}
  $end=time();
//        echo "time=".$timeTaken=$end-$start;
	return $response;
}


function getPriceFromAmazon($url) {
     usleep(100000);
// echo "url=".$url;
  $start=time();
 $url=  stripcslashes($url);
	$html=makeCurlRequest($url);
 if(empty($html))
    return -1;
        $regex = '/id="add-to-cart-button"/';
        preg_match($regex, $html, $sizes);
//        print_r($sizes);
        if(count($sizes)>0)
        {
            return 1;
        }
        return 0;
        
         echo "\n". $url." av= ".$availability[1];  
            
        print_r($json);
                
	if ($mrp ) {
            $response = array("url"=>$url,"price"=>$price[1],"mrp"=>$mrp[1],/*"data" => $data[1],*/"availability" =>$availability[1]);
//		$response = array("price" => "Rs. $price[1].00", "image" => $image[1], "title" => $title[1],"availability" =>$avail[1], "status" => "200");
	} else {
		$response = array("url"=>$url,"status" => "404", "error" => "We could not find the product details on Flipkart $url");
	}
  $end=time();
//        echo "time=".$timeTaken=$end-$start;
	return $response;
}

function getPriceFromKoovs($url) {
     usleep(100000);
// echo "url=".$url;
  $start=time();
 $url=  stripcslashes($url);
	$html=makeCurlRequest($url);
 if(empty($html))
    return -1;
        $regex = '/"oos":false,/';
        preg_match($regex, $html, $sizes);
//        print_r($sizes);
        if(count($sizes)>0)
        {
            return 1;
        }
        return 0;
        
         echo "\n". $url." av= ".$availability[1];  
            
        print_r($json);
                
	if ($mrp ) {
            $response = array("url"=>$url,"price"=>$price[1],"mrp"=>$mrp[1],/*"data" => $data[1],*/"availability" =>$availability[1]);
//		$response = array("price" => "Rs. $price[1].00", "image" => $image[1], "title" => $title[1],"availability" =>$avail[1], "status" => "200");
	} else {
		$response = array("url"=>$url,"status" => "404", "error" => "We could not find the product details on Flipkart $url");
	}
  $end=time();
//        echo "time=".$timeTaken=$end-$start;
	return $response;
}



function getPriceFromTrendin($url) {
     usleep(100000);
// echo "url=".$url;
  $start=time();
 $url=  stripcslashes($url);
	$html=makeCurlRequest($url);
 if(empty($html))
    return -1;
        $regex = '/id="add_to_cart"/';
        preg_match($regex, $html, $sizes);
//        print_r($sizes);
        if(count($sizes)>0)
        {
            return 1;
        }
        return 0;
        
         echo "\n". $url." av= ".$availability[1];  
            
        print_r($json);
                
	if ($mrp ) {
            $response = array("url"=>$url,"price"=>$price[1],"mrp"=>$mrp[1],/*"data" => $data[1],*/"availability" =>$availability[1]);
//		$response = array("price" => "Rs. $price[1].00", "image" => $image[1], "title" => $title[1],"availability" =>$avail[1], "status" => "200");
	} else {
		$response = array("url"=>$url,"status" => "404", "error" => "We could not find the product details on Flipkart $url");
	}
  $end=time();
//        echo "time=".$timeTaken=$end-$start;
	return $response;
}


function getPriceFromFashionAndYou($url) {
     usleep(100000);
// echo "url=".$url;
     
  $start=time();
 $url=  stripcslashes($url);
 	$html=makeCurlRequest($url);
if(empty($html))
    return -1;
        $regex = '/id="addToCart"/';
        preg_match($regex, $html, $sizes);
//        print_r($sizes);
        if(count($sizes)>0)
        {
            return 1;
        }
        return 0;
        
         echo "\n". $url." av= ".$availability[1];  
            
        print_r($json);
                
	if ($mrp ) {
            $response = array("url"=>$url,"price"=>$price[1],"mrp"=>$mrp[1],/*"data" => $data[1],*/"availability" =>$availability[1]);
//		$response = array("price" => "Rs. $price[1].00", "image" => $image[1], "title" => $title[1],"availability" =>$avail[1], "status" => "200");
	} else {
		$response = array("url"=>$url,"status" => "404", "error" => "We could not find the product details on Flipkart $url");
	}
  $end=time();
//        echo "time=".$timeTaken=$end-$start;
	return $response;
}

function getPriceFromBasicsLife($url) {
     usleep(100000);
// echo "url=".$url;
  $start=time();
 $url=  stripcslashes($url);
 $html=makeCurlRequest($url);
 if(empty($html))
    return -1;
        $regex = '/add-to-cart"/';
        preg_match($regex, $html, $sizes);
//        print_r($sizes);
        if(count($sizes)>0)
        {
            return 1;
        }
        return 0;
        
        
}

function getPriceFromYepme($url) {
     usleep(100000);
// echo "url=".$url;
  $start=time();
 $url=  stripcslashes($url);
	$html=makeCurlRequest($url);
 if(empty($html))
    return -1;
        $regex = '/id="imgbtnaddtocart"/';
        preg_match($regex, $html, $sizes);
//        print_r($sizes);
        if(count($sizes)>0)
        {
            return 1;
        }
        return 0;
        
}


function updateInSolr($jsonString)
{
//    echo "json=".$jsonString;
    $baseUpdateUrl='http://10.66.39.93:8080/solr/allinone/update?commit=true';
                                                                                  

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
echo "solr result=";
print_r($result);
echo "date=".date('D, d M Y H:i:s')."\r\n";
//file_put_contents('/home/ashokdev/yebhi/output.txt', $jsonString." ".  date('D, d M Y H:i:s')." \r\n", FILE_APPEND | LOCK_EX);
}
