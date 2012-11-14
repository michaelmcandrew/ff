<?php
require_once '../drupal/sites/all/modules/civicrm/civicrm.config.php' ;
require_once  'CRM/Core/Config.php';

$rowCount = 100;
$totalProcessed = 0;
$totalUpdated = 0;

$param = array(
	"version" => 3,
	"option.limit" => $rowCount,
	"option.offset" => 0,
);

$result = civicrm_api("Contact", "Get", $param);

while (
	is_array($result) &&
	$result["count"] > 0
) {
	echo "Processing " . $result["count"] . " rows... ".$param["option.offset"]." completed\n";

	$value = $result["values"];
	
	foreach($value as $contact_id => $contact) {
		
		$newFirstName = ucfirst($contact["first_name"]);
		
		if($newFirstName != $contact["first_name"]) {
			
			$updateParam = array(
				"version" => 3,
				"id" => $contact_id,
				"first_name" => $newFirstName,
			);
			
			echo $contact["first_name"] . " updated to " . $newFirstName . " (ID: $contact_id)\n";
			
			civicrm_api("Contact", "update", $updateParam);
			
			$totalUpdated++;
			
		}
		
		$totalProcessed++;
		
	}
	
	$param["option.offset"] += $rowCount;
	$result = civicrm_api("Contact", "Get", $param);
	
}

echo "Total rows processed: $totalProcessed, Total updated: $totalUpdated";
echo "\n";
?>