<?php
require_once '/projects/ff/drupal/sites/all/modules/civicrm/civicrm.config.php' ;
require_once  'CRM/Core/Config.php';
CRM_Core_Config::singleton( );

//include('Translator.php');
//include('Translator/FFNov12.php');
//include('Contact.php');

// * Create a cleaner object

$translator = new CRM_Chainsms_Translator;

// * Add the data definition to the cleaner object

$definition = new stdClass;

$translator->setDefinition($definition);

// * Add the data to be cleaned to the cleaner object

// ** define a group of contacts that were part of the cleanup

// 219: all people that replied to a text
// 193: year-11-7
// 185: year-12-unknown-7
// 170: year-13-7
//$translator->setGroups(array(170,185,193));
$translator->setGroups(array(219));
//$translator->setGroups(array(164));
// ** define a start date for activities

$translator->setStartDate('2012-11-01');

// ** define an end date for activties

$translator->setEndDate('2013-02-08');

$translator->prepContacts();

// * Run the cleaning script
$translator->setTranslatorClass("CRM_Chainsms_Translator_FFNov12");

$translator->translate();

$noErrors = 0;
foreach($translator->contacts as $contact){
  if(isset($contact->data['uni']['institution'])){
    $uni[$contact->data['uni']['institution']]++;
  }
  if(isset($contact->errors)){
    print_r($contact);
  }else{
    $noErrors++;
  }
}
print_r($uni);
echo "{$noErrors} contacts passed with no errors";

// * Import the clean interactions into CiviCRM as completed SMS interactions
// * Import the messy interactions into CiviCRM as scheduled SMS interactions
