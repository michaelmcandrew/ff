<?php
require_once 'CRM/Report/Form.php';
require_once 'CRM/Core/BAO/CustomOption.php';
class CRM_Report_Form_Alumni_Alumni extends CRM_Report_Form {

    function getCustomDataOptions($id){
        $options=CRM_Core_BAO_CustomOption::getCustomOption($id);
        $return[null]='- select -';
        foreach($options as $option){
            $return[$option['value']]=$option['label'];
        }
        return $return;
    }
    
    function __construct() {
        
        $this->_columns = array(
            'student' => array(
                'dao' => 'CRM_Contact_DAO_Contact',
                'fields' => array(
                    'id' => array(
                        'title' => 'id',
                        'no_display' => true
                    ),
                    'name' => array(
                        'title' => 'Name',
                        'no_display' => true
                    )
                ),
                'filters' => array(
                    'name' => array(
                        'title' => 'Name',
                        'operatorType' => CRM_Report_Form::OP_STRING,
                        'options' => range('1900','2012')
                    ),                    
                    'year' => array(
                        'title' => 'Year',
                        'operatorType' => CRM_Report_Form::OP_SELECT,
                        'options' => range('1900','2012')
                    ),                    
                    'a-levels' => array(
                        'title' => 'A-levels',
                        'operatorType' => CRM_Report_Form::OP_SELECT,
                        'options' => $this->getCustomDataOptions('45')
                    ), 
                    'further-education' => array(
                        'title' => 'Further education',
                        'operatorType' => CRM_Report_Form::OP_SELECT,
                        'options' => $this->getCustomDataOptions('46')
                    ),                    
                    'undergrad' => array(
                        'title' => 'Undergraduate subject',
                        'operatorType' => CRM_Report_Form::OP_SELECT,
                        'options' => $this->getCustomDataOptions('54')
                    ),                    
                    'postgrad' => array(
                        'title' => 'Postgraduate subject',
                        'operatorType' => CRM_Report_Form::OP_SELECT,
                        'options' => $this->getCustomDataOptions('50')
                    ),                    
                    'institution' => array(
                        'title' => 'University attended (both undergrad and postgrad)',
                        'operatorType' => CRM_Report_Form::OP_SELECT,
                        'options' => range('1900','2012')
                    ),                    
                    'job-sector' => array(
                        'title' => 'Job sector',
                        'operatorType' => CRM_Report_Form::OP_SELECT,
                        'options' => $this->getCustomDataOptions('44')
                    ),                    
                    'current-occupation' => array(
                        'title' => 'Current occupation',
                        'operatorType' => CRM_Report_Form::OP_SELECT,
                        'options' => $this->getCustomDataOptions('33')
                    ),                    
                    'potential-involvement' => array(
                        'title' => 'Potential involvement',
                        'operatorType' => CRM_Report_Form::OP_SELECT,
                        'options' => $this->getCustomDataOptions('42')
                    ),                    
                    'local' => array(
                        'title' => 'Local to school',
                        'operatorType' => CRM_Report_Form::OP_SELECT,
                        'options' => array(true=>'yes',false=>'no')
                    ),                    
                    'job-title' => array(
                        'title' => 'Job title',
                        'operatorType' => CRM_Report_Form::OP_STRING,
                        'options' => range('1900','2012')
                    ),                    
                    'employer' => array(
                        'title' => 'Name of employer',
                        'operatorType' => CRM_Report_Form::OP_STRING,
                        'options' => range('1900','2012')
                    ),                    
                                       
                )
            ),
            'email' => array(
                'dao' => 'CRM_Core_DAO_Email',
                'fields' => array(
                    'mobile' => array(
                        'title' => 'Mobile',
                        'no_display' => true
                    )
                )
            ),
            'phone' => array(
                'dao' => 'CRM_Core_DAO_Phone',
                'fields' => array(
                    'mobile' => array(
                        'title' => 'Mobile',
                        'no_display' => true
                    )
                )
            )
        );

        parent::__construct();
    }
    
    function select(){
        $this->_select = 'SELECT *';
    }

    function from(){
        $this->_from = "FROM civicrm_contact{$this->_aclFrom}";        
    }
}

?>