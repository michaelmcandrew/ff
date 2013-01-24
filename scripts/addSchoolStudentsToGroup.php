<?php
require_once '../drupal/sites/all/modules/civicrm/civicrm.config.php' ;
require_once  'CRM/Core/Config.php';
CRM_Core_Config::singleton( );

$filename = "./data/Schools for xmas message.csv";

$group_id = add_group("Xmas message students");

$totalStudents = 0;

if (($handle = fopen($filename, "r")) !== FALSE) {
	while (($data = fgetcsv($handle, 0, ",")) !== FALSE) {
		
		if(strlen($data[1]) > 0) {
		
			$param = array(
				"id" => $data[1],
				"version" => 3,
				"contact_type" => "Organization",
				"contact_sub_type" => "School",
				"rowCount" => 0,
				"organization_name" => str_replace("'", '%', $data[0]),
			);
			
			$result = civicrm_api("Contact", "Get", $param);
	
			if(
				is_array($result) && 
				strtolower(trim($result["values"][$data[1]]["display_name"])) == strtolower(trim($data[0]))
			) {
				
				$schoolStudents = 0;
				$sql = "SELECT * FROM civicrm_value_contact_reference_9 WHERE contact_reference_21='" . $data[1] . "'";
				$studentResults = CRM_Core_DAO::executeQuery($sql);
				while($studentResults->fetch()) {
					$contact_id = $studentResults->entity_id;
					
					civicrm_api("GroupContact", "Create", array('version' => 3, 'contact_id' => $contact_id, 'group_id' => $group_id));
	
					$schoolStudents++;
					$totalStudents++;
				}
				
				echo "Added $schoolStudents students from " . $result["values"][$data[1]]["display_name"] , "\n";
				
			} else {
				echo "Not matched\n";
				echo $result["values"][$data[1]]["display_name"] . " == " . $data[0];
			}
		
		}

	}
	
	fclose($handle);
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

die("total students added: $totalStudents");
?>