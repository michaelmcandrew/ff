<?php
require_once '../drupal/sites/default/civicrm.settings.php';
require_once 'CRM/Core/Config.php';
CRM_Core_Config::singleton( );

$results=CRM_Core_DAO::executeQuery( "SELECT display_name FROM civicrm_contact WHERE contact_sub_type='school'" );
while($results->fetch()){
	$name_array=explode(' ',$results->display_name);
	$first_three=array($name_array[0], $name_array[1],$name_array[2]);
	$count[implode(' ', $first_three)]++;
}
asort($count);
print_r($count);
?>

