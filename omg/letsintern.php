<?php

/* 
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */
include 'simple_html_dom.php';

$response=postRequest("https://www.thirstt.com/", '');

print_r($response);
$jsonString='{  "displayName": "chetan",  "email": "chetan17@gmail.com",  "password": "11111111",  "gender": "f",  "letsintern": true,  "_csrf": "3PZIgkkYAY/4i5FMn7EIbcXvkGYfd5cs/I4+M="}';
 $response=postRequest("https://www.thirstt.com/register", $jsonString);
print_r($response); die;

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
curl_setopt($ch, CURLOPT_COOKIESESSION, true);
curl_setopt($ch, CURLOPT_COOKIEFILE, $cookie_file);
curl_setopt($ch, CURLOPT_COOKIEJAR, $cookie_file);
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

?>
