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
      "rowCount" => 1000000,
    );

    $result = civicrm_api("Contact", "Get", $param);

    $collegeMap = array();
    $collegeDuplicate = array();

    foreach($result["values"] as $value) {   	
       if(
       	is_array($value["contact_sub_type"]) &&
      	(
      		in_array("School", $value["contact_sub_type"]) ||
       		in_array("Further_Education_Institution", $value["contact_sub_type"])
      	)
      ) {
	      $collegeName = self::cleanTextResponse($value["display_name"]);
	      $collegeName = self::cleanCollegeName($collegeName);
	      if(array_key_exists($collegeName, $collegeMap)) {
	      	unset($collegeMap[$collegeName]);
	        $collegeDuplicate[] = $collegeName;
	      } else if(!in_array($collegeName, $collegeDuplicate)) {
	        $collegeMap[$collegeName] = $value["contact_id"];
	      }
      }
    }

    $this->mapping["collegeMap"] = $collegeMap;
    
    // Create a manual mapping of education (other that university options), the field
    // as there are multiple fields in Civi that store this data.
    // custom_68 = "Education - current"
    // custom_74 = "Non A-Level course"
    $educationMap["alevel"]   = array("custom_68" => "Doing_A-Levels");
    $educationMap["a level"]  = array("custom_68" => "Doing_A-Levels");
    $educationMap["alevels"]  = array("custom_68" => "Doing_A-Levels");
    $educationMap["a levels"] = array("custom_68" => "Doing_A-Levels");
    
    $educationMap["aslevel"]   = array("custom_68" => "Doing_A-Levels");
    $educationMap["as level"]  = array("custom_68" => "Doing_A-Levels");
    $educationMap["aslevels"]  = array("custom_68" => "Doing_A-Levels");
    $educationMap["as levels"] = array("custom_68" => "Doing_A-Levels");
    
    $educationMap["btc"]   = array("custom_74" => "BTEC", "custom_68" => "Doing_a_course_other_than_A-levels_at_school_sixth_form_or_college");
    $educationMap["btec"]  = array("custom_74" => "BTEC", "custom_68" => "Doing_a_course_other_than_A-levels_at_school_sixth_form_or_college");
    $educationMap["btec"]  = array("custom_74" => "BTEC", "custom_68" => "Doing_a_course_other_than_A-levels_at_school_sixth_form_or_college");
    $educationMap["btecs"] = array("custom_74" => "BTEC", "custom_68" => "Doing_a_course_other_than_A-levels_at_school_sixth_form_or_college");

    $educationMap["gcses"] = array("custom_74" => "GCSEs/O-Levels", "custom_68" => "Doing_a_course_other_than_A-levels_at_school_sixth_form_or_college");
    
    $this->mapping["educationMap"] = $educationMap;
    
  }

  function translate($contact){
    $this->contact = $contact;
    
    //create an empty array for the data
    $this->contact->data = array();

    //process the texts

    //check for bad words
    $this->checkForBadWords();

    //process each interactio
    reset($this->contact->texts);
    while ($interaction = $this->getInteraction()){
      $this->process($interaction);
    }
    if(!isset($this->contact->data['CurrentOccupation'])){
      $this->autoFillCurrentOccupation();
    }
  }

  function autoFillCurrentOccupation(){
    $occupations = array(
      'University' => 'university',
      'Education' => 'educationNotUni',
      'Apprenticeship' => 'apprenticeship',
      'Work' => 'work',
      'CurrentSchool' => 'haveNotLeft',
      'Other' => 'somethingElse',
    );
    foreach($occupations as $occupation => $answer){
      if(isset($this->contact->data[$occupation])){
        $this->contact->data['CurrentOccupation'] = $answer;
      }
    }
  }


  function getInteraction(){
    //check that the first text is outbound
    
    $firstText = current($this->contact->texts);
    if($firstText['direction'] != 'outbound'){
      return FALSE;
    }else{
      $interaction['outbound'] = $firstText;
    }
    $secondText = next($this->contact->texts);
    //if we have fallen off the end of the array, that is because there are no more texts left.
    //That could be fine (if we said thankyou and they didn't reply),
    //or it could be a problem, i.e. an incomplete text.
    //Below, we check for both cases.

    if($secondText == FALSE){
      //If the second text does not exist
      if($firstText['msg_template_id'] == 80){
        //If this is a thankyou text so that is fine.
        $interaction['inbound'] = NULL;
      }else{
        //Else this is an incomplete interaction - record an error and return FALSE
        $this->contact->addError('Did not reply to text', 'incomplete');
        return FALSE;
      }
    }elseif($secondText['direction']=='inbound'){
      //if the next text is an inbound text, all is as expected, so record this inbound text
      $interaction['inbound'] = $secondText;
      //and advance the pointer ready for the next interaction grab
      next($this->contact->texts);
    }else{
      //else stop grabbing the interaction (TODO record this as an error)
      return FALSE;
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
      'a' => 'educationNotUni',
      'b' => 'apprenticeship',
      'c' => 'work',
      'd' => 'somethingElse',
      'e' => 'haveNotLeft'
    );
    if(in_array($response, array_keys($answerMap))){
      $this->contact->data['CurrentOccupation'] = $answerMap[$response];
    }else{
      $this->contact->addError('Invalid reply to initial multiple choice question', '');
    }
  }

  function processYear12UnknownStart($response){
    $response = self::cleanLetterResponse($response);
    $answerMap = array(
      'a' => 'educationNotUni',
      'b' => 'university',
      'c' => 'apprenticeship',
      'd' => 'work',
      'e' => 'somethingElse',
      'f' => 'haveNotLeft'
    );
    if(in_array($response, array_keys($answerMap))){
      $this->contact->data['CurrentOccupation'] = $answerMap[$response];
    }else{
      $this->contact->addError('Invalid reply to initial multiple choice question', 'warning');
    }
  }

  function processYear13Start($response){
    $response = self::cleanLetterResponse($response);
    $answerMap = array(
      'a' => 'university',
      'b' => 'educationNotUni',
      'c' => 'apprenticeship',
      'd' => 'work',
      'e' => 'somethingElse'
    );
    if(in_array($response, array_keys($answerMap))){
      $this->contact->data['CurrentOccupation'] = $answerMap[$response];
    }else{
      $this->contact->addError('Invalid reply to initial multiple choice question', 'warning');
    }
  }

  function processUniversity($response){
    $response = self::cleanTextResponse($response);
    //count number of commas in text
    $split = explode(',', $response);
    if(count($split) == 2){
      
      //set the institution and subject in the data object
      $this->contact->data['University']['institution'] = trim($split[0]); //TODO Validate institution
      $this->contact->data['University']['subject'] = trim($split[1]);
      
      //try and identify the university
      $this->contact->data['University']['institution'] = self::cleanUniversityName( $this->contact->data['University']['institution']);
      if(array_key_exists($this->contact->data['University']['institution'], $this->mapping["universityMap"])){
        $this->contact->data['University']['institution_id'] = $this->mapping["universityMap"][$this->contact->data['University']['institution']];
      }else{
        $this->contact->addError('Cannot find a contact in CiviCRM for this university');
      }
      // add the subject and the institution
    }else{
      $this->contact->addError('Could not split the uni reply into exactly one university and subject');
    }
  }

  function processWorking($response){
    $response = self::cleanTextResponse($response);
    //count number of commas in text
    $split = explode(',', $response);
    if(count($split) == 2){
      $this->contact->data['Job']['job-title'] = trim($split[0]);
      $this->contact->data['Job']['employer'] = trim($split[1]);
      // add the subject and the institution
    }else{
      $this->contact->addError('Could not split the job reply into exactly one employer and job title');
    }
  }

  function processEducation($response){
    $response = self::cleanTextResponse($response);
    //count number of commas in text
    $split = explode(',', $response);
    if(count($split) == 2){
      $this->contact->data['Education']['institution'] = trim($split[1]);
      $this->contact->data['Education']['institution'] = self::cleanCollegeName(
        $this->contact->data['Education']['institution']
      );
      $this->contact->data['Education']['course'] = trim($split[0]);
      
      if(array_key_exists($this->contact->data['Education']['course'], $this->mapping["educationMap"])){
      	
      	$this->contact->data['Education']['course_data'] = $this->mapping["educationMap"][
      	  $this->contact->data['Education']['course']
      	];
      }else{
      	$this->contact->addError( 'Could not determine the course');
      }
      
      if(array_key_exists($this->contact->data['Education']['institution'], $this->mapping["collegeMap"])){
        $this->contact->data['Education']['institution_id'] = $this->mapping["collegeMap"][
          $this->contact->data['Education']['institution']
        ];
      }else{
        $this->contact->addError( 'Cannot find a contact in CiviCRM for this institution');
      }
    }else{
      $this->contact->addError( 'Could not split the education reply into exactly one institution and course');
    }
  }
  
  function processApprenticeship($response){
    $this->contact->data['Apprenticeship'] = $response;
  }
  
  function processOther($response){
    $this->contact->data['Other'] = $response;
  }
  
  function processConfirmYearGroup($response){
    $this->contact->data['CurrentSchool']['year-group'] = $response; //TODO Validate / clean year groups  
  }
  
  function cleanupNecessary($contact){
    foreach($contact->errors as $error){
      if(in_array($error['type'], array('incomplete','error'))){
        return TRUE;
      }
    }
    if(!count($contact->data)){
      return TRUE;
    }
    return FALSE;
  }
  
  function update($contact){
    foreach($contact->data as $key => $nada){
      call_user_func(array($this, 'update'.$key), $contact);
    }
  }
  
  function updateCurrentOccupation($contact){
  	$updateParam = array(
  		"version" => 3,
  		"id" => $contact->id,
  	);
  	
  	switch($contact->data["CurrentOccupation"]) {
  		case "university":
  		case "educationNotUni";
  			$updateParam["custom_33"] = "In_education";
  			break;
  		case "work":
  			$updateParam["custom_33"] = "Working_including_internships";
  			break;
  		case "apprenticeship":
  			$updateParam["custom_33"] = "On_an_Apprenticeship";
  			break;
  		case "somethingElse":
  			$updateParam["custom_33"] = "Doing_something_else";
  			break;
  		case "haveNotLeft":
  			// TODO: What should we do (if anything here?)
  			break;
  	}

  	if(
  		array_key_exists("custom_33", $updateParam) &&
  		strlen($updateParam["custom_33"]) > 0
  	) {
  		civicrm_api("Contact", "update", $updateParam);
  	}

  }

  function updateEducation($contact){
  	$updateParam = array(
  		"version" => 3,
  		"id" => $contact->id,
  		"custom_76" => $contact->data['Education']['institution_id'],
  	);
  	
        if(isset($contact->data['Education']["course_data"])){
          foreach($contact->data['Education']["course_data"] as $key => $value) {
            $updateParam[$key] = $value;
          }
        }

  	civicrm_api("Contact", "update", $updateParam);
  }
  
  function updateUniversity($contact){
  	$updateParam = array(
  		"version" => 3,
  		"id" => $contact->id,
  		"custom_68" => "Doing_a_course_at_a_university",
  		"custom_49" => $contact->data['University']['institution_id'],
  		"custom_53" => $contact->data['University']['subject'],
  	);
  	civicrm_api("Contact", "update", $updateParam);
  }
  
  function updateJob($contact){
  	// TODO: What fields should this update?
  	$updateParam = array(
  		"version" => 3,
  		"id" => $contact->id,
  		"job_title" => $contact->data['Job']['job-title'],
  		"current_employer" => $contact->data['Job']['employer'],
  	);
  	civicrm_api("Contact", "update", $updateParam);
  }

  function updateOther($contact){
  	$updateParam = array(
  		"version" => 3,
  		"id" => $contact->id,
  		"custom_34" => $contact->data['Other'],
  	);
  	civicrm_api("Contact", "update", $updateParam);
  }

  function updateApprenticeship($contact){
  	$updateParam = array(
  		"version" => 3,
  		"id" => $contact->id,
  		"custom_69" => $contact->data['Apprenticeship'],
  	);
  	civicrm_api("Contact", "update", $updateParam);
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

    foreach($this->contact->texts as $text){
      if($text['direction']=='inbound'){
        $replacement = str_replace($badWords, 'fluffy-kitten', $text['text']);
        if($replacement != $text['text']){
          $this->contact->addError( "Rude word alert!: {$text['text']}\n");
        }
      }
    }
  }
}
