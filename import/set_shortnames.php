<?php

require_once('initialise.php');
require_once('functions.php');

//Fetch rows from MYSQL into data object
$select = "SELECT * FROM ff_data.shortnames";

$results =CRM_Core_DAO::executeQuery($select);
$i=0;

while($results->fetch()){
		$params = array();
		$shortname = trimString($results->short_name_1);
		$school_id = trimString($results->internal_contact_id_3);
		if(!($shortname AND $school_id)){
			continue;
		}
        
    //Set the short name to the real short name                
    $params = array('version'   => '3',
                    'debug' =>  '1',
                    'contact_id'   => $school_id,
                    'contact_type' => 'Organization',
                    'custom_26'     => $shortname);
    $result=civicrm_api('contact', 'create',$params);                    
    handle_errors($result);                
		print_r($school_id);
		echo "  ";
		$i++;
    //if ($i==5) { break; }
}

?>