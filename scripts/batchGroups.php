<?php
require_once '../drupal/sites/all/modules/civicrm/civicrm.config.php' ;
require_once  'CRM/Core/Config.php';
CRM_Core_Config::singleton( );

$groupsToBatch = array(58,59,62,63);

$query="
SELECT group_id, cg.title AS group_title, ccg.contact_id
FROM civicrm_group_contact AS ccg
JOIN civicrm_group AS cg ON cg.id = ccg.group_id
WHERE group_id = %1
";

foreach($groupsToBatch as $group){
  $params[1] = array($group, 'Integer');
  $contacts=CRM_Core_DAO::executeQuery($query, $params);
	print_r("Number of contacts in this group: {$contacts->N}\n");
  $addedCount=0;
  $batchNumber=0;
  while($contacts->fetch()){
    if($addedCount==0 || $addedCount==500){
      $addedCount=0;
      $batchNumber++;
      $group_id = add_group("{$contacts->group_title}-{$batchNumber}");
    }
    // print_r("adding a contact to {$group_id}\n");
    $result = civicrm_api("GroupContact", "Create", array('version' => 3, 'contact_id' => $contacts->contact_id, 'group_id' => $group_id));
    if($result['is_error']){
      print_r($result);
      exit;
    }
    $addedCount++;
  }
}

function add_group($title){
 
  print_r("adding a group called {$title}\n");

  $result = civicrm_api("Group", "Create", array('version' => 3, 'title' => $title));// add the group add API call h
  if($result['is_error']){
    print_r($result);
    exit;
  }
  return $result['id'];
}
