<?php

class CRM_Chainsms_Form_Add extends CRM_Core_Form {

  function preProcess(){
  }

  function setDefaultValues(){

    //if we have been past an answer ID, then add those defaults to the form
    
    if(($msg_template_id = CRM_Utils_Array::value('msg_template_id', $_GET)) && CRM_Utils_Array::value('action', $_GET)=='add'){
      
      return array('msg_template' => $msg_template_id);

    }
    if(($id = CRM_Utils_Array::value('id', $_GET)) && (CRM_Utils_Array::value('action', $_GET)=='delete' ||CRM_Utils_Array::value('action', $_GET)=='update')){
      //if we are passed an id and this is an update

      $query = "SELECT * FROM civicrm_chainsms_answer WHERE id=%1";
      $params[1] = array($id, 'Integer');
      $result = CRM_Core_DAO::executeQuery($query, $params);
      $result->fetch();
      
      //TODO: if we can't find an answer ID, then error

      $this->assign('answer_for_delete', $result->answer);
      $this->assign('msg_template_for_delete', $result->msg_template_id);
      $this->assign('next_msg_template_for_delete', $result->next_msg_template_id);
      return array(
        'msg_template' => $result->msg_template_id,
        'next_msg_template' => $result->next_msg_template_id,
        'answer' => $result->answer,
        'id' => $result->id,
      );
    }
  }

  function buildQuickForm() {
    
    $this->assign('action', 'action');
    $this->add( 'hidden', 'id');
    if(CRM_Utils_Array::value('action', $_GET)=='add' || CRM_Utils_Array::value('action', $_GET)=='update'){
      $this->templates = array(0 => '- select -') + CRM_Core_BAO_MessageTemplates::getMessageTemplates(FALSE);
      $this->add( 'select', 'msg_template', ts('Initial message'), $this->templates, FALSE);
      $this->add( 'text', 'answer', ts('Answer'), $this->templates, FALSE);
      $this->add( 'select', 'next_msg_template', ts('Next message'), $this->templates, FALSE);
      $buttons[] = array(
        'name' => ts('Save'),
        'type' => 'submit',
        'isDefault' => TRUE,
      );

      $buttons[] = array(
        'type' => 'cancel',
        'name' => ts('Cancel'),
      );

    }elseif(CRM_Utils_Array::value('action', $_GET)=='delete'){
      $buttons[] = array(
        'name' => ts('Delete'),
        'type' => 'submit',
        'isDefault' => TRUE,
      );

      $buttons[] = array(
        'type' => 'cancel',
        'name' => ts('Cancel'),
      );
    }
    $this->addButtons($buttons);
  }
  function postProcess(){


    $submittedValues = $this->getSubmitValues();
    if($this->_action == CRM_Core_Action::ADD || $this->_action == CRM_Core_Action::UPDATE){
      $params[1] = array($submittedValues['msg_template'], 'Integer');
      $params[2] = array($submittedValues['answer'], 'String');
      $params[3] = array($submittedValues['next_msg_template'], 'Integer');
    }
    if($this->_action == CRM_Core_Action::DELETE || $this->_action == CRM_Core_Action::UPDATE){
      $params[4] = array($submittedValues['id'], 'Integer');
    }
    //if we are updating, update
    
    if($this->_action == CRM_Core_Action::ADD){
      $query = "INSERT INTO civicrm_chainsms_answer SET
        msg_template_id = %1,
        answer = %2,
        next_msg_template_id = %3";
      $result = CRM_Core_DAO::executeQuery($query, $params);
      CRM_Core_Session::setStatus('Your answer has been added');
      CRM_Utils_System::redirect('/civicrm/sms/chains');
      ////set message and redirect
    }elseif($this->_action == CRM_Core_Action::UPDATE){
      $query = "UPDATE civicrm_chainsms_answer SET
        msg_template_id = %1,
        answer = %2,
        next_msg_template_id = %3
        WHERE id=%4";
      $result = CRM_Core_DAO::executeQuery($query, $params);
      CRM_Core_Session::setStatus('Your answer has been updated');
      CRM_Utils_System::redirect('/civicrm/sms/chains');

    }elseif($this->_action == CRM_Core_Action::DELETE){
      $query = "DELETE FROM civicrm_chainsms_answer
        WHERE id=%4";
      $result = CRM_Core_DAO::executeQuery($query, $params);
      CRM_Core_Session::setStatus('Your answer has been deleted');
      CRM_Utils_System::redirect('/civicrm/sms/chains');
    }
  }
}
