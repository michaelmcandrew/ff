<?php
require_once '../drupal/sites/all/modules/civicrm/civicrm.config.php' ;
require_once  'CRM/Core/Config.php';
CRM_Core_Config::singleton( );


$live=0;



$params = array(
  'version' => 3,
  'json' => 1,
  'entity' => 'Contact',
  'action' => 'sms',
  );

if($live===1){
  $params['group_id'] = $group_id = $argv[1];
  $params['msg_template_id'] = getTemplate($group_id);
}else{
  $params['contact_id'] = 7768;
  $params['msg_template_id'] = 83;
}

print_r($params);

//$result = curl_civicrm_api("http://networks.futurefirst.org.uk/civicrm/ajax/rest", $params);
//print_r(json_decode($result));


function getTemplate($group_id){
  $query = "SELECT title FROM civicrm_group WHERE id = %1";
  $params[1] = array($group_id, 'Integer');
  $result = CRM_Core_DAO::executeQuery($query, $params);
  $result->fetch();
  if(preg_match('/y11/', $result->title)){
    return 75;
  }
  if(preg_match('/y12/', $result->title)){
    return 85;
  }
  if(preg_match('/y13/', $result->title)){
    return 83;
  }
}


function curl_civicrm_api($url, $postData){

  $ch = curl_init();
  curl_setopt($ch, CURLOPT_URL, $url);
  // Should cURL return or print out the data? (true = return, false = print)
  curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);

  curl_setopt($ch, CURLOPT_TIMEOUT, 60*60);
  
  curl_setopt($ch, CURLOPT_POSTFIELDS, $postData);
  
  // Download the given URL, and return output
  $output = curl_exec($ch);

  // Close the cURL resource, and free system resources
  curl_close($ch);

  return $output;
}

