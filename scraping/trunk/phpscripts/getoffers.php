<?php

/* 
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

$collection='all';
if(isset($_GET['partner']))
$collection=$_GET['vendor'];

$dbhost = 'localhost';
$dbname = 'partneroffers';
$m = new MongoClient("mongodb://$dbhost");
$db = $m->$dbname;
$c_offers = $db->$collection;
 $time=time();
$rangeQuery = array('expireTime' => array( '$gt' => $time ));
//print_r($rangeQuery);
$offers=$c_offers->find($rangeQuery);  
$array = iterator_to_array($offers);
?>
<table>
    <tr><td>Title</td><td>image</td><td>MRP</td><td>price</td><td>partner</td><td>Type</td></tr>

<?php

foreach ($array as $offer)
{
       
    echo '<tr><td><a href="'.$offer['link']." >".$offer['title'].'</a></td><td><image style="width:100;" src="'.$offer['image'].'"></td><td>'.$offer['mrp'].'</td><td>'.$offer['price'].'</td><td>'.$offer['partner'].'</td><td>'.$offer['type'].'</td></tr>';
        
}

?>
    
    </table>
<?php
//echo "count=".count($array);

echo json_encode($array);
//print_r($array);
//foreach ($offers as $doc) {
//    
//    var_dump($doc);
//}


//        {expireTime:{$gt:1419920930}}
?>