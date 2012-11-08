<?php
require_once '../drupal/sites/all/modules/civicrm/civicrm.config.php' ;
require_once  'CRM/Core/Config.php';

$rowCount = 1000;
$totalProcessed = 0;
$totalUpdated = 0;

$param = array(
	"version" => 3,
	"option.limit" => $rowCount,
	"option.offset" => 0,
);

$result = civicrm_api("Phone", "Get", $param);

while (
	is_array($result) &&
	$result["count"] > 0
) {
	echo "Processing " . $result["count"] . " rows... \n";

	foreach($result["values"] as $value) {
	
		// Original phone value
		$originalPhone = $value["phone"];
		
		// If there is any whitespace in the mobile number then
		// remove it.
		if (strpos($value["phone"], ' ') !== false) {
			$value["phone"] = str_replace(' ', '', $value["phone"]);
		}
		
		// If the mobile number is 10 digits long and begins with a 7,
		// add a leading 0.
		if (
			strlen($value["phone"]) == 10 &&
			substr($value["phone"], 0, 1) == "7"
		) {
			$value["phone"] = "0" . $value["phone"];
		}
		
		// If after all that we consider the number to be a mobile,
		// set it's phone type to be a mobile.
		if(
			substr($value["phone"], 0, 2) == "07" &&
			strlen($value["phone"]) == 11
		) {

			if(
				$originalPhone != $value["phone"] ||
				$value["phone_type_id"] != "2"
			) {
				
				echo "Original was " . $value["phone"] . " (type: " . $value["phone_type_id"] . ")";
				echo " Updated to " . $value["phone"] . "...";
				echo "\n";
				
				$updateParam = array(
					"version" => 3,
					"id" => $value["id"],
					"phone" => $value["phone"],
					"phone_type_id" => "2",
				);
				
				civicrm_api("Phone", "update", $updateParam);
			
				$totalUpdated++;
			
			}
		
		} else if($value["phone_type_id"] == "2") {
			
			// If we don't recognise this number as being a mobile format,
			// then we should move it to being a landline
			
			echo "Original was " . $value["phone"] . " (type: " . $value["phone_type_id"] . ")";
			echo " Moved " . $value["phone"] . " to being a landline...";
			echo "\n";
			
			$updateParam = array(
				"version" => 3,
				"id" => $value["id"],
				"phone_type_id" => "1",
			);
			
			civicrm_api("Phone", "update", $updateParam);
				
			$totalUpdated++;
				
			
		}
		
		$totalProcessed++;
		
	}

	$param["option.offset"] += $rowCount;
	$result = civicrm_api("Phone", "Get", $param);

}

echo "Total rows processed: $totalProcessed, Total updated: $totalUpdated";
echo "\n";
?>