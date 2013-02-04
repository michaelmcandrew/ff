<?php
class CRM_ChainSMS_Translator_FFNov12 {

  function translate($contact){
    $this->contact = $contact;
    //create an empty array for the data
    $this->data = array();

    //process the texts
    $this->texts = $contact->texts;
    while ($interaction = $this->getInteraction()){
      $this->process($interaction);
    }
    $this->contact->data = $this->data;
  }

  function getInteraction(){
    //check that the first text is outbound
    
    $firstText = current($this->texts);
    next($this->texts);
    if($firstText['direction'] != 'outbound'){
      //TODO set as invalid
      return 0;
    }else{
      $interaction['outbound'] = $firstText;
    }
    $secondText = current($this->texts);
    next($this->texts);
    if($secondText['direction'] != 'inbound'){
      //TODO set as invalid
      return 0;
    }else{
      $interaction['inbound'] = $secondText;
    }
    return $interaction;
  }

  function process($interaction){
    $functionMap = array(
      '75' => 'Year11Start',
      '76' => 'Working',
      '77' => 'Education',
      '78' => 'Apprenticeship',
      '79' => 'Other',
      '83' => 'Year13Start',
      '84' => 'University',
      '85' => 'Year12UnknownStart',
      '86' => 'ConfirmYearGroup'
    );
    if(in_array($interaction['outbound']['msg_template_id'], array_keys($functionMap))){
      call_user_func(array($this, 'process'.$functionMap[$interaction['outbound']['msg_template_id']]), $interaction['inbound']['text']);
    }
  }

  function cleanLetterResponse($response){
    return strtolower(trim($response));
  }

  function processYear12UnknownStart($response){
    $response = self::cleanLetterResponse($response);
    $answerMap = array(
      'a' => 'education-not-uni',
      'b' => 'university',
      'c' => 'apprenticeship',
      'd' => 'work',
      'e' => 'something-else',
      'f' => 'have-not-left'
    );
    if(in_array($response, array_keys($answerMap))){
      $this->data['current_occupation'] = $answerMap[$response];
    }
  }
  function processYear13Start($response){
    $response = self::cleanLetterResponse($response);
    $answerMap = array(
      'a' => 'university',
      'b' => 'education-not-uni',
      'c' => 'apprenticeship',
      'd' => 'work',
      'e' => 'something-else'
    );
    if(in_array($response, array_keys($answerMap))){
      $this->data['current_occupation'] = $answerMap[$response];
    }
  }

  function processUniversity($response){
    //count number of commas in text
    $split = explode(',', $response);
    if(count($split) == 2){
      $this->data['uni']['institution'] = trim($split[0]); //TODO Validate institution
      $this->data['uni']['subject'] = trim($split[1]);

      // add the subject and the institution
    }else{
      $this->contact->errors[] = 'Could not split the uni reply into exactly one university and subject';
    }
  }

  function processWorking($response){
    //count number of commas in text
    $split = explode(',', $response);
    if(count($split) == 2){
      $this->data['job']['job-title'] = trim($split[0]);
      $this->data['job']['employer'] = trim($split[1]);
      // add the subject and the institution
    }else{
      $this->contact->errors[] = 'Could not split the job reply into exactly one employer and job title';
    }
  }

  function processEducation($response){
    //count number of commas in text
    $split = explode(',', $response);
    if(count($split) == 2){
      $this->data['education']['course'] = trim($split[0]); //TODO Validate institution
      $this->data['education']['institution'] = trim($split[1]);
      // add the subject and the institution
    }else{
      $this->contact->errors[] = 'Could not split the job reply into exactly one employer and job title';
    }
  }
  function processApprenticeship($response){
    $this->data['apprenticeship'] = $response;
  }
  
  function processOther($response){
    $this->data['other'] = $response;
  }

  function processConfirmYearGroup($response){
    $this->data['current-school']['year-group'] = $response; //TODO Validate / clean year groups  
  }

}
