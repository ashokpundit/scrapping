<?php

/* 
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */
include 'simple_html_dom.php';
$html = file_get_html('http://www.yebhi.com/');
exec('sudo rm /usr/yebhi/trunk/phpscripts/output.txt');

//echo $html->find('.cat1dropdown',0)->plaintext;

foreach($html->find('.cat1dropdown') as $cat)
{
    foreach($cat->find('a') as $product) 
    {
        
        $url='http://www.yebhi.com'.$product->href;
        exec('php  /usr/yebhi/trunk/phpscripts/availability.php "'.$url.'">> /usr/yebhi/trunk/phpscripts/output.txt 2>&1 &');
    }

}

$rightSide=$html->find('.trendingLinks_right',0)->find('ul',0);
foreach($rightSide->find('li') as $cat)
{
    foreach($cat->find('a') as $product) 
    {
        
       echo $url=$product->href;
        exec('php  /usr/yebhi/trunk/phpscripts/availability.php "'.$url.'">> /usr/yebhi/trunk/phpscripts/output.txt 2>&1 &');
    }

}


$rightSide=$html->find('.trendingLinks_left',0)->find('ul',0);
foreach($rightSide->find('li') as $cat)
{
    foreach($cat->find('a') as $product) 
    {
        
        $url=$product->href;
        exec('php  /usr/yebhi/trunk/phpscripts/availability.php "'.$url.'">> /usr/yebhi/trunk/phpscripts/output.txt 2>&1 &');
    }

}

