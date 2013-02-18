<?php
class CRM_ChainSMS_Translator_FFNov12 {

  function __construct(){
    $this->generateMapping();
  }
  function generateMapping() {

    // Load all higher institutions out of Civi
    $param = array(
      "version" => 3,
      "contact_type" => "Organization",
      "contact_sub_type" => "Higher_Education_Institution",
      "rowCount" => 10000,
    );

    $result = civicrm_api("Contact", "Get", $param);

    $universityMap = array();

    foreach($result["values"] as $value) {
      $universityName = self::cleanTextResponse($value["display_name"]);
      $universityName = self::cleanUniversityName($universityName);
      if(array_key_exists($universityName, $universityMap)) {
        //echo "University clash " . $universityMap[$universityName] . " with " . $value["contact_id"] . "\n";
      }
      $universityMap[$universityName] = $value["contact_id"];
    }

    // Add some manual entries to the mapping
    $universityMap["newcastle"] = 7951;
    $universityMap["ucl"] = 8007;
    $universityMap["leeds trinity"] = 7933;
    $universityMap["northumbria"] = 7955;
    $universityMap["uclan"] = 7884;
    $universityMap["uwe"] = 8009;
    $universityMap["west england"] = 8009;
    $universityMap["lse"] = 7945;
    $universityMap["imperial"] = 7923;
    $universityMap["imperial college london"] = 7923;
    $universityMap["mmu"] = 7948;
    $universityMap["queen marys"] = 34696;
    $universityMap["queen mary"] = 34696;
    $universityMap["queen mary london"] = 34696;
    $universityMap["ljmu"] = 7937;
    $universityMap["goldsmiths"] = 7914;
    $universityMap["westminister"] = 8012;
    $universityMap["dmu"] = 7895;
    $universityMap["demontfort"] = 7895;
    $universityMap["st marys"] = 7982;
    $universityMap["st georges"] = 7981;
    $universityMap["st georges london"] = 7981;
    $universityMap["royal holloway"] = 7977;
    $universityMap["city london"] = 7888;
    $universityMap["wales newport"] = 7953;
    $universityMap["uea"] = 7899;
    $universityMap["edgehill"] = 7901;
    $universityMap["bedford"] = 7863;

    $this->mapping["universityMap"] = $universityMap;

    // Load all schools and colleges from Civi
    $param = array(
      "version" => 3,
      "contact_type" => "Organization",
      "contact_sub_type" => "School",
      "rowCount" => 10000,
    );

    $result = civicrm_api("Contact", "Get", $param);

    $collegeMap = array();
    $collegeDuplicate = array();

    foreach($result["values"] as $value) {
      $collegeName = self::cleanTextResponse($value["display_name"]);
      $collegeName = self::cleanCollegeName($collegeName);
      if(array_key_exists($collegeName, $collegeMap)) {
        //echo "College clash " . $collegeMap[$collegeName] . " with " . $value["contact_id"] . "\n";
        unset($collegeMap[$collegeName]);
        $collegeDuplicate[] = $collegeName;
      } else if(!in_array($collegeName, $collegeDuplicate)) {
        $collegeMap[$collegeName] = $value["contact_id"];
      }
    }

    $this->mapping["collegeMap"] = $collegeMap;
  }

  function translate($contact){

    $this->contact = $contact;
    
    //create an empty array for the data
    $this->data = array();

    //process the texts
    $this->texts = $contact->texts;

    //check for bad words
    $this->checkForBadWords();

    //maybe we shouldcheck for who is this?


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

  function cleanTextResponse($response) {
    $response = strtolower(trim(preg_replace("/[^a-zA-Z 0-9\,]+/", "", $response)));
    return $response;
  }

  function cleanUniversityName($response) {
    // Needed so we can also match at the end of the string
    $response .= " ";

    // Replace "uni" in parts of the string
    $response = str_ireplace("uni ", "university ", $response);

    // Replace common university slang/mispellings
    $response = str_ireplace("met ", "metropolitan ", $response);

    // Now remove common phrases from the university
    $phrases = array("university ", "the ", "of ");
    $response = str_ireplace($phrases, "", $response);

    $response = trim($response);

    return $response;
  }

  function cleanCollegeName($response) {
    // Needed so we can also match at the end of the string
    $response .= " ";

    // Now remove common phrases from the college name
    $phrases = array("college ", "collage ", "the ", "of ", "6th form ", "sixthform ", "sixth form ", "school ");
    $response = str_ireplace($phrases, "", $response);

    $response = trim($response);

    return $response;
  }

  function processYear11Start($response){
    $response = self::cleanLetterResponse($response);
    $answerMap = array(
      'a' => 'education-not-uni',
      'b' => 'apprenticeship',
      'c' => 'work',
      'd' => 'something-else',
      'e' => 'have-not-left'
    );
    if(in_array($response, array_keys($answerMap))){
      $this->data['CurrentOccupation'] = $answerMap[$response];
    }
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
      $this->data['CurrentOccupation'] = $answerMap[$response];
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
      $this->data['currentOccupation'] = $answerMap[$response];
    }
  }

  function processUniversity($response){
    $response = self::cleanTextResponse($response);
    //count number of commas in text
    $split = explode(',', $response);
    if(count($split) == 2){
      
      //set the institution and subject in the data object
      $this->data['University']['institution'] = trim($split[0]); //TODO Validate institution
      $this->data['University']['subject'] = trim($split[1]);
      
      //try and identify the university
      $this->data['University']['institution'] = self::cleanUniversityName( $this->data['uni']['institution']);
      if(array_key_exists($this->data['uni']['institution'], $this->mapping["universityMap"])){
        $this->data['University']['institution_id'] = $this->mapping["universityMap"][$this->data['uni']['institution']];
      }else{
        $this->contact->errors[] = 'Cannot find a contact in CiviCRM for this university';
      }
      // add the subject and the institution
    }else{
      $this->contact->errors[] = 'Could not split the uni reply into exactly one university and subject';
    }
  }

  function processWorking($response){
    $response = self::cleanTextResponse($response);
    //count number of commas in text
    $split = explode(',', $response);
    if(count($split) == 2){
      $this->data['Job']['job-title'] = trim($split[0]);
      $this->data['Job']['employer'] = trim($split[1]);
      // add the subject and the institution
    }else{
      $this->contact->errors[] = 'Could not split the job reply into exactly one employer and job title';
    }
  }

  function processEducation($response){
    $response = self::cleanTextResponse($response);
    //count number of commas in text
    $split = explode(',', $response);
    if(count($split) == 2){
      $this->data['Education']['institution'] = trim($split[1]);
      $this->data['Education']['institution'] = self::cleanCollegeName(
        $this->data['Education']['institution']
      );
      $this->data['Education']['course'] = trim($split[0]);
      // add the subject and the institution

      if(array_key_exists($this->data['Education']['institution'], $this->mapping["collegeMap"])){
        $this->data['Education']['institution_id'] = $this->mapping["collegeMap"][
          $this->data['Education']['institution']
          ];
      }
    }else{
      $this->contact->errors[] = 'Could not split the education reply into exactly one institution and course';
    }
  }
  function processApprenticeship($response){
    $this->data['Apprenticeship'] = $response;
  }

  function processOther($response){
    $this->data['Other'] = $response;
  }

  function processConfirmYearGroup($response){
    $this->data['CurrentSchool']['year-group'] = $response; //TODO Validate / clean year groups  
  }

  function update($contact){
    foreach($contact->data as $key => $nada){
      call_user_func(array($this, 'update'.$key));
    }
  }
  
  function updateCurrentOccupation(){
  }

  function updateEducation(){
  }
  
  function updateUniversity(){
  }
  
  function updateJob(){
  }

  function updateOther(){
  }

  function updateApprenticeship(){
  }
  
  function updateCurrentSchool(){
  }




  function checkForBadWords(){
    $badWords = array(
      'fuck',
      'shit',
      'twat',
      'dick',
      'cunt',
      'bollock'
    );

    foreach($this->texts as $text){
      if($text['direction']=='inbound'){
        $replacement = str_replace($badWords, 'fluffy-kitten', $text['text']);
        if($replacement != $text['text']){
          $this->contact->errors[] = "Rude word alert!: {$text['text']}\n";
        }
      }
    }
  }
}
