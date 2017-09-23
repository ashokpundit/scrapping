<?php
include 'simple_html_dom.php';
/* 
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */
date_default_timezone_set("Asia/Calcutta");
$dbhost = 'localhost';
	$dbname='redtape';

	// Connect to test database
//	$m = new MongoClient("mongodb://$dbhost");
//	$db = $m->$dbname;
  //       $response = $db->drop();
//$db = $m->$dbname;

scrapeRedtape();
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
function scrapeRedtape()
{
    
 $catResponse=postRequest('http://redtape.com/Services/ProductCats.asmx/GetCategoriesList','{CategoryId: 0}');
$catResponse=json_decode($catResponse,true);
//print_r($catResponse['d']);
$catResponse=$catResponse['d'];
$categoryArr=Array();
foreach($catResponse as $cat)
{
    $categoryArr[$cat['SubCategoryId']]=$cat['SubCategory'];

}

echo '<?xml version="1.0"?>
<items>';
$baseUrl='http://redtape.com/productdetails.aspx?productid=';
for($i=0;$i<1000;$i++)
{

$productDetails=postrequest('http://redtape.com/Services/ProductCats.asmx/GetProductDetails','{CategoryId: 0,ProductId: "'.$i.'"}');
$productDetails=json_decode($productDetails,true);
$productDetails=$productDetails['d'][0];

if(count($productDetails['ProductImageURLlst'])<1)
continue;

//die;
$pid=$oid=$i;
$sku=$title=$productDetails['ProductDeatilslst'][0]['ProductCode'];//$html->find('.prdct_dtal_hdng',0)->plaintext;
$price=$productDetails['ProductDeatilslst'][0]['SalePrice'];;//$html->find('.t_price',0)->plaintext;
$description=$productDetails['ProductDeatilslst'][0]['Description'];;//$html->find('.description',0)->plaintext;
$color=$productDetails['ProductDeatilslst'][0]['Colour'];//$html->find('.pcolor',0)->plaintext;
$sizes=Array();
$sizeStr='';
foreach($productDetails['Sizelst'] as $size )
{
    $sizes[]=$size['AttributeValue'];
    $sizeStr.='<tag>'.$size['AttributeValue'].'</tag>';
}
$imageArr=$productDetails['ProductImageURLlst'];
$mainImage='';
$thmbImage='';
$imageStr='';
foreach($imageArr as $image)
{
    $imageStr.="<tag>http://redtape.com/".$image['imageURL']."</tag>";
if($image['imageTypeId']==6)
$mainImage="http://redtape.com/".$image['imageURL'];
else
$thmbImage="http://redtape.com/".$image['imageURL'];
}
$categoryArr;
$parentCategory=$productDetails['ProductDeatilslst'][0]['ParentCategoryId'];
$parentCategory=$categoryArr[$parentCategory];
$category=$productDetails['ProductDeatilslst'][0]['CategoryId'];//$html->find('.categ',0)->plaintext;
$category=$categoryArr[$category];
echo "<item><id>redtape-".$pid."</id> <color>".$color."</color><url>".$baseUrl.$i."</url><oid>redtape-".$oid."</oid><image_url>".$mainImage."</image_url><sku>".$sku."</sku><gender>men</gender><host>http://redtape.com</host><brand>radetape</brand><rank>0</rank> <breadcrumbs>Home &gt; ".$category."&gt; ".$title."</breadcrumbs><category>
      <level>Home</level><level>".$parentCategory."</level><level>".$category."</level></category><name>".$title."</name><currency>INR</currency><price>".$price."</price><discount>0</discount><inserted>2014-12-16T16:06:17Z</inserted><out>false</out><deleted>false</deleted><info>".$description."</info><tags>";
if(!empty($imageStr))
echo "<images>".$imageStr."</images>";
if(!empty($sizeStr))
        echo  "<sizes>".$sizeStr."</sizes>";

echo "<thumbs><tag>".$thmbImage."</tag></thumbs></tags></item>";
    }
    
         echo '</items>';                                           
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
function postRequest($url,$jsonString)
{
//    echo "json=".$jsonString;
    $baseUpdateUrl=$url;//'http://10.66.39.93:8080/solr/allinone/update?commit=true';


    $ch = curl_init($baseUpdateUrl);
    curl_setopt($ch, CURLOPT_CUSTOMREQUEST, "POST");
    curl_setopt($ch, CURLOPT_POSTFIELDS, $jsonString);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($ch, CURLOPT_HTTPHEADER, array(
    'Content-Type: application/json',
    'Content-Length: ' . strlen($jsonString))
);
 date_default_timezone_set("Asia/Calcutta");
return $result = curl_exec($ch);
//echo "solr result=";
//print_r($result);
//echo "date=".date('D, d M Y H:i:s')."\r\n";
//file_put_contents('/home/ashokdev/yebhi/output.txt', $jsonString." ".  date('D, d M Y H:i:s')." \r\n", FILE_APPEND | LOCK_EX);
}

