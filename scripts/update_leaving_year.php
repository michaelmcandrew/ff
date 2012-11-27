<?php
require_once '../drupal/sites/all/modules/civicrm/civicrm.config.php' ;
require_once  'CRM/Core/Config.php';
CRM_Core_Config::singleton( );

$filename = "./data/Alumni Database - London Schools - email only (upload).csv";

$handle = fopen($filename, "r");
if (($handle = fopen($filename, "r")) !== FALSE) {
	
	$data = fgetcsv($handle, 0, ",");

	$email_key = array_search('Email', $data);
	$leaving_date_key = array_search('Leaving date', $data);

	if(!is_numeric($email_key)) { die("No email column found\n"); }
	if(!is_numeric($leaving_date_key)) { die("No leaving date column found\n"); }
	
	while (($data = fgetcsv($handle, 0, ",")) !== FALSE) {
		
		$email = trim($data[$email_key]);
		$leaving_date = trim($data[$leaving_date_key]);
		
		if($leaving_date == "") {
			
			if(strlen($email) > 0) {
			
				$param = array(
					"version" => 3,
					"email" => $email,
				);
				
				$result = civicrm_api("Contact", "Get", $param);
				
				if($result["count"] == 1) {
		
					$sql = "UPDATE civicrm_value_contact_reference_9 " . 
							" SET leaving_year_32=NULL " .
							" WHERE entity_id='" . $result["id"] . "'";
					
					CRM_Core_DAO::executeQuery($sql);
					
				} else {
					echo "There are " . $result["count"] . " records for email \"" . $email . "\"\n";
				}
				
			}

		}
		
	}
	
	fclose($handle);
}
?>