<?php
require_once '../drupal/sites/all/modules/civicrm/civicrm.config.php' ;
require_once  'CRM/Core/Config.php';
CRM_Core_Config::singleton( );

$filename = "./data/Shortened school names for texting.csv";

// Determine the id of the custom field "Short name for SMS"
$custom_field_result = civicrm_api(
	"CustomField",
	"get",
	array('version' => '3','sequential' => '1', 'name' => 'Short_name_for_SMS')
);

if($custom_field_result["count"] != "1") {
	die("Could not determine the custom field ID for Short name for SMS\n");
}
$custom_field_value = $custom_field_result["values"][0];

// Find more information from the custom group table
$custom_group_result = civicrm_api(
	"CustomGroup",
	"get",
	array('version' => '3','sequential' => '1', 'id' => $custom_field_value['custom_group_id'])
);

if($custom_group_result["count"] != "1") {
	die("Could not load the custom group\n");
}
$custom_group_value = $custom_group_result["values"][0];

if (($handle = fopen($filename, "r")) !== FALSE) {
	while (($data = fgetcsv($handle, 0, ",")) !== FALSE) {
		
		$param = array(
			"version" => 3,
			"contact_type" => "Organization",
			"contact_sub_type" => "School",
			"rowCount" => 0,
			"organization_name" => str_replace("'", '%', $data[0]),
			("return.custom_" . $custom_field_result["id"]) => 1,
		);
		
		$result = civicrm_api("Contact", "Get", $param);

		$organisationName = "";
		foreach($result["values"] as $value) {
			if($data[0] == $value["organization_name"]) {
				$organisationName = $value["organization_name"];
			}
		}
	
		if(
			$result["count"] == 1 &&
			$organisationName != "" &&
			$data[0] == $organisationName
		) {

			/*
			$sql = "UPDATE " . $custom_group_value["table_name"] . 
					" SET " . $custom_field_value["column_name"] . "='".addslashes($data[0])."'
					WHERE entity_id='" . $result["id"] . "'";
			CRM_Core_DAO::executeQuery($sql);
			*/
			
		} else {
			echo "There are " . $result["count"] . " records for \"" . $data[0] . "\"\n";
		}
		
	}
	
	fclose($handle);
	
}

die("end");
?>