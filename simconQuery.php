<?php
/* 
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */


function getConnectionCount(){
    $endpoint = 'https://app.samburu.at/simcon/simcon.php';
    $params = array('action' => 'getConnections');
    $url = $endpoint . '?' . http_build_query($params);
    $ch = curl_init($url);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1); //Return data instead printing directly in Browser
    curl_setopt($ch, CURLOPT_CONNECTTIMEOUT , 2); //Timeout after 7 seconds
    curl_setopt($ch, CURLOPT_USERAGENT , "Mozilla/4.0 (compatible; MSIE 8.0; Windows NT 6.1)");
    curl_setopt($ch, CURLOPT_HEADER, 0);   
    curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);  
    $result = curl_exec($ch);
    if(curl_errno($ch)){
     echo 'Curl error: ' . curl_error($ch);
    }   
    curl_close($ch);   
    return $result;
}
//test
echo "connections: ".getConnectionCount();



?>

