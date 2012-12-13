<?php
require_once '../drupal/sites/all/modules/civicrm/civicrm.config.php' ;
require_once  'CRM/Core/Config.php';
CRM_Core_Config::singleton( );

$params=array();
$query="
SELECT civicrm_value_contact_reference_9.entity_id
FROM civicrm_contact AS school
LEFT JOIN civicrm_membership ON school.id = civicrm_membership.contact_id
JOIN civicrm_value_contact_reference_9 ON school.id =  contact_reference_21
WHERE school.contact_sub_type LIKE '%School%'
AND civicrm_membership.id IS NULL
AND leaving_year_32 BETWEEN '2012-01-01' AND '2012-12-31'
";

$contacts=CRM_Core_DAO::executeQuery($query, $params);
while($contacts->fetch()){
  $result = civicrm_api("GroupContact", "Create", array('version' => 3, 'contact_id' => $contacts->entity_id, 'group_id' => 163));
  if($result['is_error']){
    print_r($result);
  }
  $addedCount++;
}
