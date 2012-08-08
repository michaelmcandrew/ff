<?php

require_once('initialise.php');
require_once('functions.php');

//Fetch rows from MYSQL into data object
$select = "SELECT * FROM ff_data.shortnames";

$results =CRM_Core_DAO::executeQuery($select);
$i=0;

while($results->fetch()){
		$params = array();
		$shortname = trimString($results->short_name_0);
		$school_id = trimString($results->internal_contact_id_3);
		if(!($shortname AND $school_id)){
			continue;
		}
		
	  //First set the school shortname to the school id
    $params = array('version'   => '3',
                    'contact_id'   => $school_id,
                    'contact_type' => 'Organization',
                    'custom_26'     => $school_id);
    $result=civicrm_api('contact', 'create',$params);
    handle_errors($result);
		
		print_r($school_id);
		echo "  ";
		
			$i++;
    //if ($i==5) { break; }
}

?>