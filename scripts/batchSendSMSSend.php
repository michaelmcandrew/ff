<?php
require_once '../drupal/sites/all/modules/civicrm/civicrm.config.php' ;
require_once  'CRM/Core/Config.php';
CRM_Core_Config::singleton( );

$group_id = $argv[1];
$template_id = getTemplate($group_id);





$params = array( 'version'=>3, 'group_id'=>$group_id, 'template_id'=>$template_id); 

//$result = civicrm_api("Contact", "sms", $params);
//$result = civicrm_api("Contact", "sms", array('contact_id' => 7768, 'template_id' => 83);

print_r($params);
print_r($result);








function getTemplate($group_id){
  $query = "SELECT title FROM civicrm_group WHERE id = %1";
  $params[1] = array($group_id, 'Integer');
  $result = CRM_Core_DAO::executeQuery($query, $params);
  $result->fetch();
  if(preg_match('/y11/', $result->title)){
    return 75;
  }
  if(preg_match('/y13/', $result->title)){
    return 83;
  }
}

