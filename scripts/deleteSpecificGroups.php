<?php
require_once '../drupal/sites/all/modules/civicrm/civicrm.config.php' ;
require_once  'CRM/Core/Config.php';
CRM_Core_Config::singleton( );

$groupsToDelete = range(120,160);
foreach($groupsToDelete as $group){
  print_r(civicrm_api("Group", "Delete", array('version' => 3, 'id' => $group)));
}
