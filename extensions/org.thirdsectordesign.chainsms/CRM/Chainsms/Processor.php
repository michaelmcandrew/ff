<?php
class CRM_Chainsms_Processor{
    
    function __construct(){
        $this->ChainedSMSTableName = civicrm_api("CustomGroup","getvalue", array ('version' => '3', 'name' =>'Chained_SMS', 'return' =>'table_name'));
        $this->ChainedSMSColumnName = civicrm_api("CustomField","getvalue", array ('version' => '3', 'name' =>'message_template_id', 'return' =>'column_name'));
        $this->OutboundSMSActivityTypeId = CRM_Core_OptionGroup::getValue('activity_type', 'SMS', 'name');
        $this->InboundSMSActivityTypeId = CRM_Core_OptionGroup::getValue('activity_type', 'Inbound SMS', 'name');
    }

    function inbound($activity){

        // work out whether this is an answer to a question...
        
        // find the most recent outbound text to this person that could be considered a question
        $mostRecentOutboundChainSMS = $this->mostRecentOutboundChainSMS($activity['source_contact_id']);
        
        //if there is no most recent question, then stop inbound processing
        if(!$mostRecentOutboundChainSMS){
            return 1;
        }
        

        
        $mostRecentOutboundChainSMSDate = new DateTime($mostRecentOutboundChainSMS->activity_date_time);
        $inboundSMSDate = new DateTime($activity['activity_date_time']);
    
        //TODO: if the reply was send longer ago that the response_time_limit then there is no more processing to do
        //~ if(($inboundSMSDate - $mostRecentOutboundChainSMSDate['date']) > $an_amount_of_time){
            //~ return 1;
        //~ }
//        error_log(print_r($activity->details, true));



        // Has the question been answered already?
        
        $penultimateInboundSMS = $this->penultimateInboundSMS($activity['source_contact_id']);
        $penultimateInboundSMSDate = new DateTime($penultimateInboundSMS->activity_date_time);

        //if an inbound has been received before this one and it was after we sent the most recent question, then consider this question answered
        if(is_object($penultimateInboundSMS) && $penultimateInboundSMSDate > $mostRecentOutboundChainSMSDate){
            error_log('the question has been answered already');
            return 1;
        }
        error_log('i need to answer the question');

        // if it is waiting for a reply, then this inbound message should be treated as a reply to that question
        // TODO - mark that this is an answer
                
        $nextMessageQuery = "
            SELECT
                next_msg_template_id,
                answer
            FROM
                civicrm_chainsms_answer
            WHERE
                msg_template_id = %1";
        
        $nextMessageParams[1]=array($mostRecentOutboundChainSMS->message_template_id, 'Integer');
        //$nextMessageParams=array();
        
        $nextMessageResult = CRM_Core_DAO::executeQuery($nextMessageQuery, $nextMessageParams);

        while($nextMessageResult->fetch()){
            if(strtolower($nextMessageResult->answer) == strtolower($activity[details]) || $nextMessageResult->answer==''){
                civicrm_api('Contact', 'sms', array('version'=>'3','contact_id' => $activity['source_contact_id'], 'msg_template_id'=>$nextMessageResult->next_msg_template_id));
            }
        }

        return 1;
    }
                
    function mostRecentOutboundChainSMS($target_contact_id){

        $query="
        SELECT
            ca.id,
            ca.activity_date_time,
            cd.{$this->ChainedSMSColumnName} AS message_template_id
        FROM
            civicrm_activity AS ca
        JOIN
            {$this->ChainedSMSTableName} AS cd ON ca.id=cd.entity_id
        JOIN
            civicrm_activity_target AS cat ON cat.activity_id=ca.id
        WHERE
            target_contact_id=%1 AND
            activity_type_id={$this->OutboundSMSActivityTypeId}
        ORDER BY
            activity_date_time DESC
        LIMIT 1;
        ";

        $params[1]=array($target_contact_id, 'Integer');
        $activity = CRM_Core_DAO::executeQuery($query, $params);
        if($activity->fetch()){
            return $activity;
        }else{
            return 0; 
        }
    }

    function penultimateInboundSMS($source_contact_id){
        $query="
        SELECT
            *
        FROM 
            civicrm_activity
        WHERE
            activity_type_id={$this->InboundSMSActivityTypeId} AND
            source_contact_id=%1
        ORDER BY
            activity_date_time DESC
        LIMIT 1,1        
        ";

        $params[1]=array($source_contact_id, 'Integer');
        $activity = CRM_Core_DAO::executeQuery($query, $params);
        if($activity->fetch()){
            return $activity;
        }else{
            return 0; 
        }
    }
    
    function outbound($question_id){
        $this->addMessage('out', $this->questions[$question_id]['text'], $question_id);
        echo "OUTBOUND: {$this->questions[$question_id]['text']}\n";
    }


    function addMessage($type, $text, $question_id=null){
        return $this->messages[]=array('date' => mktime(), 'type' => $type, 'text' => $text, 'question_id' => $question_id);
    }
    
    function printMessages(){
        print_r($this->messages);
    }

}
