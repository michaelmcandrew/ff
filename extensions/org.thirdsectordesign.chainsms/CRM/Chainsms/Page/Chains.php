<?php

class CRM_Chainsms_Page_Chains extends CRM_Core_Page {
  function run() {
    // Example: Set the page-title dynamically; alternatively, declare a static title in xml/Menu/*.xml
    CRM_Utils_System::setTitle(ts('Chain SMS settings'));

    $query = "SELECT

      cmt.id AS cmt_id,
      cmt.msg_title AS cmt_msg_title,
      cmt.msg_text AS cmt_msg_text,

      cca.answer AS cca_answer,
      cca.id AS cca_id,
      
      cnmt.id AS cnmt_id,
      cnmt.msg_title AS cnmt_msg_title,
      cnmt.msg_text AS cnmt_msg_text

      FROM civicrm_chainsms_answer  AS cca

      JOIN civicrm_msg_template AS cmt
      ON cca.msg_template_id = cmt.id

      JOIN civicrm_msg_template AS cnmt
      ON cca.next_msg_template_id = cnmt.id
      
      ORDER BY cmt_id, cca_answer";

    $result = CRM_Core_DAO::executeQuery($query);

    while($result->fetch()){
      $templates[$result->cmt_id]['answers'][]= array(
          'cnmt_id' => $result->cnmt_id,
          'cnmt_msg_title' => $result->cnmt_msg_title,
          'cnmt_msg_text' => $result->cnmt_msg_text,
          'cca_id' => $result->cca_id,
          'cca_answer' => $result->cca_answer == '' ? '[all]' : $result->cca_answer, 
      );
      $templates[$result->cmt_id]['cmt_id']=$result->cmt_id;
      $templates[$result->cmt_id]['cmt_msg_title']=$result->cmt_msg_title;
      $templates[$result->cmt_id]['cmt_msg_text']=$result->cmt_msg_text;

    }
    //print_r($templates);exit; 
    // Example: Assign a variable for use in a template
    $this->assign('templates', $templates);

    parent::run();
  }
}
  
