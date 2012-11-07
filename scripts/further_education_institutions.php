<?php
require_once '../drupal/sites/all/modules/civicrm/civicrm.config.php' ;
require_once  'CRM/Core/Config.php';
CRM_Core_Config::singleton( );

$filename = "./data/Further Education Institutions.csv";

// Ensure there is ContactType with the label a "Further Education Institution"
$fei_field_result = civicrm_api(
	"ContactType",
	"get",
	array('version' => '3','sequential' => '1', 'label' => 'Further Education Institution')
);

if($fei_field_result["count"] != "1") {
	die("Could not find the Further Education Institution field\n");
}
$fei_field_value = $fei_field_result["values"][0];

if (($handle = fopen($filename, "r")) !== FALSE) {
	while (($data = fgetcsv($handle, 0, ",")) !== FALSE) {
		
		$param = array(
			"version" => 3,
			"id" => $data[0]
		);

		$result = civicrm_api("Contact", "Get", $param);

		if($result["count"] == 1) {
			
			$value = $result["values"][$result["id"]];

			$updateParam = array(
				"version" => 3,
				"id" => $value["id"],
				"contact_sub_type" => array("School", $fei_field_value["name"])
			);

			civicrm_api("Contact", "update", $updateParam);
			
		} else {
			echo "There are " . $result["count"] . " records for \"" . $data[1] . "\"\n";
		}
		
	}
	
	fclose($handle);
}
?>