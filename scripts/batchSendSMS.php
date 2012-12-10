<?php
require_once '../drupal/sites/all/modules/civicrm/civicrm.config.php' ;
require_once  'CRM/Core/Config.php';
CRM_Core_Config::singleton( );


$groupsToBatch = range(119,160);
foreach($groupsToBatch as $group){
  system("php batchSendSMSSend.php {$group}");
  sleep(5);
}

