<?php
require_once '../drupal/sites/all/modules/civicrm/civicrm.config.php' ;
require_once  'CRM/Core/Config.php';
CRM_Core_Config::singleton( );

$dupes = CRM_Dedupe_Finder::dupes(8);
$dupeGroup = 116;
foreach($dupes as $contact){
  @$contacts[$contact[0]]++;
  @$contacts[$contact[1]]++;
}

$contacts = array_keys($contacts);
print_r($contacts);
print_r(count($contacts));

foreach($contacts as $contact){

  $result = civicrm_api("GroupContact", "Create", array('version' => 3, 'contact_id' => $contact, 'group_id' => $dupeGroup));
  print_r($result);
}

