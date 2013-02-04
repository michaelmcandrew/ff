<?php
require_once '/projects/ff/drupal/sites/all/modules/civicrm/civicrm.config.php' ;
require_once  'CRM/Core/Config.php';
CRM_Core_Config::singleton( );

//include('Translator.php');
//include('Translator/FFNov12.php');
//include('Contact.php');

// * Create a cleaner object

$Translator = new CRM_Chainsms_Translator;

// * Add the data definition to the cleaner object

$definition = new stdClass;

$Translator->setDefinition($definition);

// * Add the data to be cleaned to the cleaner object

// ** define a group of contacts that were part of the cleanup

// 219: all people that replied to a text
// 193: year-11-7
// 185: year-12-unknown-7
// 170: year-13-7
$Translator->setGroups(array(170,185,193));
//$Translator->setGroups(array(219));
//$Translator->setGroups(array(164));
// ** define a start date for activities

$Translator->setStartDate('2012-11-01');

// ** define an end date for activties

$Translator->setEndDate('2013-02-08');

$Translator->prepContacts();

// * Run the cleaning script
$Translator->setTranslatorClass("CRM_Chainsms_Translator_FFNov12");

$Translator->translate();

// * Import the clean interactions into CiviCRM as completed SMS interactions
// * Import the messy interactions into CiviCRM as scheduled SMS interactions
