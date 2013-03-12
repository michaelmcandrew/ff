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
  function getErrors(){
    $errorText = '';
    foreach($this->errors as $error){
      $errorText .= "{$error['text']} ({$error['type']})\n";
    }
    return $errorText;
  }  

  function addError($text = NULL, $type = 'error'){
    $this->errors[] = array(
      'text' => $text,
      'type' => $type,
    );

  }  

  function getDate(){
    $mostRecent= 0;
    foreach($this->texts as $text){
      $curDate = strtotime($text['date']);
      if($curDate > $mostRecent) {
        $mostRecent = $curDate;
      }
    }
    return date('Y-m-d H:i:s', $mostRecent);
  }
}
