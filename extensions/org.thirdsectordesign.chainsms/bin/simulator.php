<?php
class MessageContainer{
    
    var $response_time_limit = 60; // amount of time in seconds to consider this a response
    
    var $messages=array();

    var $questions=array(
        '1'=>array('id' =>1, 'text' => "Hi, which of the following best describes the main thing you’re up to? A:working, B:education, C:training or D:other"),
        '2'=>array('id' =>2, 'text' => 'Great thanks, we hope you’re enjoying it. One final question, job title or place of work?'),
        '3'=>array('id' =>3, 'text' => 'Great thanks, one final question what sort of further education are you doing? Text 1 for university, 2 for A levels, 3 for BTEC, 4 for other?'),
        '4'=>array('id' =>4, 'text' => 'Great thanks, we hope you’re enjoying it. One final question what are you doing your training in?'),
        '5'=>array('id' =>5, 'text' => 'Great thanks, can you give us any more details about what you’re up to?'),
        '6'=>array('id' =>6, 'text' => 'Nice one - ttyl')
    );

    var $answers=array(
        '1'=>array('question_id' => '1', 'answer' => 'A', 'next_message_id' => '2'),
        '2'=>array('question_id' => '1', 'answer' => 'B', 'next_message_id' => '3'),
        '3'=>array('question_id' => '1', 'answer' => 'C', 'next_message_id' => '4'),
        '4'=>array('question_id' => '1', 'answer' => 'D', 'next_message_id' => '5'),
        '5'=>array('question_id' => '2', 'answer' => '', 'next_message_id' => '6'),
        '6'=>array('question_id' => '3', 'answer' => '', 'next_message_id' => '6'),
        '7'=>array('question_id' => '4', 'answer' => '', 'next_message_id' => '6'),
        '8'=>array('question_id' => '5', 'answer' => '', 'next_message_id' => '6'),

    );


    function inbound(){
        echo "INBOUND:  ";
        $inbound_text = trim(fgets(STDIN));
        if($inbound_text=='\x'){
            return 0;
        }
        elseif($inbound_text=='\a'){
            $this->printMessages();
            return 1;
        }else{
            $new_message = $this->addMessage('in', $inbound_text);

            // work out whether this is an answer to a question...
            
            // find the most recent outbound text that could be considered a question
            $most_recent_question = $this->most_recent_question();
            
            //if there is no most recent question, then stop inbound processing
            if(!$most_recent_question){
                return 1;
            }
            
            //if the reply was send longer ago that the response_time_limit then there is no more processing to do
            if(($new_message['date'] - $most_recent_question['date']) > $this->response_time_limit){
                return 1;
            }
            
            // has the question been answered already?
            
            $last_inbound = $this->recent_inbound(2);
            
            //if an inbound has been received before this one and it was after we sent the most recent question, then consider this question answered
            if($last_inbound && $last_inbound['date'] > $most_recent_question['date']){
                return 1;
            }
            
            // if it is waiting for a reply, then this inbound message should be treated as a reply to that question
            // TODO - mark that this is an answer
            
            foreach ($this->answers as $answer){
                if($answer['question_id'] == $most_recent_question['question_id'] && (strtolower($inbound_text) == strtolower($answer['answer']) || $answer['answer']=='')){
                    $this->outbound($answer['next_message_id']);
                }
            }
            // now that we have answered the question, do we need to send another question?
            
            return 1;
        }
    }
                
    function most_recent_question(){
        $messages=$this->messages;
        while($message = array_pop($messages)){
            if($message['type']=='out' and $message['question_id']){
                return $message;
            }
        }
    }

    function recent_inbound($back){
        $messages=$this->messages;
        while($message = array_pop($messages)){
            if($message['type']=='in'){
                $back--;
                if(!$back){
                    return $message;
                }
            }
        }
        return 0;
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

$mc = new MessageContainer;

foreach($mc->questions as $question){
    echo "INSERT INTO\n";
}


$mc->outbound('1');
while($mc->inbound());
