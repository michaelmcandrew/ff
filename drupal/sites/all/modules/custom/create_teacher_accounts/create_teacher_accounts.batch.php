<?php
error_reporting(E_ALL);
ini_set('display_errors', TRUE);
ini_set('display_startup_errors', TRUE);

//set the working directory 
chdir('/projects/ff/git/drupal/');
define('DRUPAL_ROOT', getcwd());

//Load Drupal
require_once './includes/bootstrap.inc';
drupal_bootstrap(DRUPAL_BOOTSTRAP_FULL); 


// init civi
require_once '/projects/ff/git/drupal/sites/default/civicrm.settings.php';
require_once 'CRM/Core/Config.php';
CRM_Core_Config::singleton( );
require_once('create_teacher_accounts.module');


if(!isset($_GET['key'])){
	echo 'please supply key';
	exit;
}
if($_GET['key']=='test'){
	if(!isset($_GET['id'])){
		echo 'please supply id';
		exit;
	}
	create_teacher_accounts_run($_GET['id']);
}
if($_GET['key']!='forreal'){
	exit;
}

// find all members without logins and call this
$params=array();
$query="
SELECT
        teacher.id,
        teacher.display_name,
        email.email,
	uf.uf_id
FROM
        civicrm_contact AS teacher
JOIN
        civicrm_relationship AS rel ON teacher.id = rel.contact_id_a
JOIN
        civicrm_contact AS school ON school.id=rel.contact_id_b
JOIN
        civicrm_email AS email ON teacher.id = email.contact_id
JOIN
        civicrm_membership AS member ON school.id = member.contact_id
LEFT JOIN
        civicrm_uf_match AS uf ON uf.contact_id = teacher.id
LEFT JOIN
	civicrm_entity_tag AS tag ON school.id=tag.entity_id AND tag_id=6
WHERE
	tag.id IS NULL

GROUP BY
        teacher.id
HAVING
        uf_id IS NULL
LIMIT 50
";
$result = CRM_Core_DAO::executeQuery( $query, $params );
while($result->fetch()){
	print_r("<br />$result->display_name<br />");
	create_teacher_accounts_run($result->id);

}
