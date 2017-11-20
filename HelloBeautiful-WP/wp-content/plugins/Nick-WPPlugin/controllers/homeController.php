<?php
// require_once('../../../../wp-load.php');
// require_once('../../../../wp-load.php');
include_once ($_SERVER['DOCUMENT_ROOT'] . '/wp-load.php');
include_once ($_SERVER['DOCUMENT_ROOT'] . '/wp-content/plugins/Nick-WPPlugin/utils/DataAccess.php');


global $wpdb;

$id = $_GET["id"];  
$method = $_GET["method"];
 
if(is_callable($method)){
    $method($id, $wpdb);
}else{
    defaultResponse(); //or some kind of error message
}


function Gethomepage(){
   
    $user = wp_get_current_user();
   
    
    wp_send_json($user->data);
}












