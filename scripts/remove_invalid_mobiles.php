<?php
require_once '../drupal/sites/all/modules/civicrm/civicrm.config.php' ;
require_once  'CRM/Core/Config.php';

$invalidCount = 0;
$invalidNumbers = array(
	"07070707070",
	"07700000000",
	"07123456789",
	"07000000000",
	"07111111111",
	"07222222222",
	"07333333333",
	"07444444444",
	"07555555555",
	"07666666666",
	"07777777777",
	"07888888888",
	"07999999999",
	"07777777770",
	"07777777771",
	"07777777772",
	"07777777773",
	"07777777774",
	"07777777775",
	"07777777776",
	"07777777777",
	"07777777778",
	"07777777779",
);

foreach($invalidNumbers as $number) {
	
	$param = array(
		"version" => 3,
		"phone" => $number,
		"phone_type_id" => 2,
		"option.limit" => 100000,
		"option.offset" => 0,
	);
	
	$result = civicrm_api("Phone", "Get", $param);
	$invalidCount += $result["count"];
	
	foreach($result["values"] as $value) {
		echo "Phone id " . $value["id"] . " has the number " . $value["phone"];
		echo "\n";
		
		$deleteParam = array(
			"version" => 3,
			"id" => $value["id"]
		);

		$deleteResult = civicrm_api("Phone", "Delete", $deleteParam);
	}
	
	echo "Removed " . $result["count"] . " invalid numbers for " . $number;
	echo "\n\n";
	
}

die("Removed $invalidCount total invalid phone numbers\n");
?>