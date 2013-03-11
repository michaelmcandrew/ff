<?php
class CRM_Chainsms_Contact{

  function __construct($id){
    $this->id = $id;
    $this->errors = array();
  }

  function addText($activity_id, $type, $date, $msg_template_id = NULL, $text = NULL){
    $this->texts[$activity_id] = array(
      'id' => $activity_id,
      'direction' => $type,
      'date' => $date,
      'msg_template_id' => $msg_template_id,
      'text' => $text
    );

  }  
}
