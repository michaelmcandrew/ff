<?php
require_once '../drupal/sites/all/modules/civicrm/civicrm.config.php' ;
require_once  'CRM/Core/Config.php';

$selects = civicrm_api("CustomField","get", array ('version' => '3', 'html_type' => 'Select'));
$radios = civicrm_api("CustomField","get", array ('version' => '3', 'html_type' => 'Radio'));
$checkboxes = civicrm_api("CustomField","get", array ('version' => '3', 'html_type' => 'CheckBox'));


//print_r($selects['count']);
//print_r($radios['count']);
//print_r($checkboxes['count']);

$audit = new AuditMultipleChoiceCustomData;
$audit->add($selects['values']);
$audit->add($radios['values']);
$audit->add($checkboxes['values'], true); 
$audit->generateInvalidOptions();
//print_r($audit->errors);



class AuditMultipleChoiceCustomData{

	function __construct(){
		$this->CustomDataGroups=civicrm_api("CustomGroup","get", array ('version' => '3'));
	}


	function add($fields, $is_multiple = false){
		foreach($fields as $field){
			if($field['data_type']=='Boolean'){
				continue;
			}
			$optionValues = $this->getOptionValues($field);
			$this->allOptionValues[$field['column_name']] = $this->getOptionValues($field);
			$result = CRM_Core_DAO::executeQuery("SELECT id, entity_id, {$field['column_name']} AS audit_column FROM {$this->CustomDataGroups['values'][$field['custom_group_id']]['table_name']}");			
			while($result->fetch()){
				if($result->audit_column=='' || $result->audit_column==''){
					continue;
				}
				if($is_multiple){
					$options = explode('', substr($result->audit_column,1,-1));
					foreach($options as $option){
						if(!$this->validOption($option, $optionValues)){
							$this->errors[$field['column_name']][$result->id]=$option;
						}
					}
				}else{
					if(!$this->validOption($result->audit_column, $optionValues)){
						$this->errors[$field['column_name']][$result->id]=$result->audit_column;
					}
				}
			} 
		}
	}
	
	function getOptionValues($field){
		$optionValuesResult = civicrm_api("OptionValue","get", array ('version' => '3', 'option_group_id'=>$field['option_group_id'], 'option.limit' => 1000));
		foreach($optionValuesResult['values'] as $optionValueResult){
			$optionValues[]=$optionValueResult['value'];
		}
		return $optionValues;
	}
	
	function validOption($value, $optionValues){
		if(in_array($value, $optionValues)){
			return true;
		}else{
			return false;
		}
	}
	
	function generateValidOptions(){
		$file = fopen('/tmp/ValidOptions.csv', 'w');
		foreach($this->allOptionValues as $fieldName => $field){
			foreach($field as $optionValue){
				fputcsv($file, array($fieldName, $optionValue));
			}
		}
		fclose($file);		
	}

	function generateInvalidOptions(){
		$output=array();
		foreach($this->errors as $fieldName => $errors){
			$output[$fieldName]=array();
			foreach($errors as $error){
				if(!in_array($error, $output[$fieldName])){
					$output[$fieldName][]=$error;
				}
			}
		}
		$file = fopen('/tmp/InvalidValues.csv', 'w');
		foreach($output as $fieldName => $field){
			foreach($field as $optionValue){
				fputcsv($file, array($fieldName, $optionValue));
			}
		}
		fclose($file);		

	}
}

