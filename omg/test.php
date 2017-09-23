<?php
 $ch = curl_init();
    $headers = array(
                        "Content-type: text/plain;charset=\"utf-8\"",
                        "Accept: */*",
                        "Accept-Encoding: gzip, deflate", 
                        "Cache-Control: no-cache",
                        "Accept-Language: en-US,en;q=0.8",
                        "Pragma: no-cache",
                        "Connection: keep-alive",
                        "Cookie: sails.sid=s%3A9tdHSGNGiFALaxAgSL7KCkv0.4pkjc26hPXhO0bscUExjNc60ASjtVpBHzmHM0jUqHi0; _ga=GA1.2.727892680.1424086918",
                        "Origin: chrome-extension://fdmmgilgnpjigdojojpjoooidkmcomcm" 
                    );
$jsonString='{  "displayName": "chetan",  "email": "chetan171@gmail.com",  "password": "11111111",  "gender": "f",  "letsintern": true,  "_csrf": "3PZIgkkYAY/4i5FMn7EIbcXvkGYfd5cs/I4+M="}'; 
     curl_setopt($ch, CURLOPT_URL, "https://www.thirstt.com/register");
    curl_setopt($ch,CURLOPT_USERAGENT,'Mozilla/5.0 (X11; Linux x86_64) AppleWebKit/537.36 (KHTML, like Gecko)         Chrome/42.0.2311.90 Safari/537.36');
    curl_setopt($ch, CURLOPT_HEADER, true);
    curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
    curl_setopt($ch, CURLOPT_POST, 1);
    curl_setopt($ch, CURLOPT_POSTFIELDS,$jsonString);
     curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
     $result = curl_exec($ch);
print_r( $result);
?>
