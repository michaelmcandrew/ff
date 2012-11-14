<?php
require_once '../drupal/sites/all/modules/civicrm/civicrm.config.php' ;
require_once  'CRM/Core/Config.php';
CRM_Core_Config::singleton( );


$query="
SELECT cc.id, ce.email, cp.phone, ccc.what_year_are_you_in__12
FROM civicrm_contact AS cc
JOIN civicrm_email AS ce ON cc.id = ce.contact_id
JOIN civicrm_phone AS cp ON cc.id = cp.contact_id AND phone_type_id=2
JOIN civicrm_value_contact_reference_9 AS ccc ON cc.id = ccc.entity_id
WHERE what_year_are_you_in__12 = %1
GROUP BY cc.id
ORDER BY rand()
LIMIT 1000
";


$groups=array(
	'eleven'=>array(33,34,35,36),
	'thirteen'=>array(37,38,39,40)
);
$years=array('eleven','thirteen');
foreach($years as $year){
	$params[1]=array($year, 'String');
	$students=CRM_Core_DAO::executeQuery($query, $params);
	while($students->fetch()){
		$group=current($groups[$year]);
		print_r(civicrm_api("GroupContact","create", array ('version' => '3', 'group_id' => $group, 'contact_id' => $students->id)));
		if(!next($groups[$year])){
			reset($groups[$year]);
		}
	}
}

