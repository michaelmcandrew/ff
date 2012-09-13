<?php
require_once 'CRM/Report/Form.php';
require_once 'CRM/Core/BAO/CustomOption.php';
class CRM_Report_Form_Alumni_Alumni extends CRM_Report_Form {

    protected $_exposeContactID = false;

    protected $_instanceButtonName = 'test';
    protected $_createNewButtonName = 'test';
    protected $_printButtonName    = 'test';
    protected $_pdfButtonName      = 'test';
    protected $_add2groupSupported = false;

    function getHigherEd(){
        $ret[null]='- select -';
        $higherEds=civicrm_api("Contact","get", array ('version' =>'3', 'contact_sub_type' =>'Higher_Education_Institution', 'rowCount' =>'1000'));
        foreach($higherEds['values'] as $id => $higherEd){
            $ret[$id] = $higherEd['display_name'];
        }
        return $ret;
    }

    function getCustomDataOptions($id){
        $options=CRM_Core_BAO_CustomOption::getCustomOption($id);
        $return[null]='- select -';

        $max_length=32;
        foreach($options as $option){
            //no need to manually trim any more since katy can do this...
            // if(strlen($option['label'])>$max_length){
            //     $option['label']=substr($option['label'], 0, $max_length).'...';
            // }
            $return[$option['value']]=$option['label'];
        }
        return $return;
    }
    
    function __construct() {
        
        $this->_columns = array(
            'alumni' => array(
                'dao' => 'CRM_Contact_DAO_Contact',
                'alias' => 'alumni',
                'fields' => array(
                    'id' => array(
                        'no_display'=> true, 
                        'required'  => true,
                    ),
                    'display_name' => array(
                        'title' => 'Name',
                        'dbAlias' => 'display_name',
                        'required' => true,
                        
                    )
                ),
                'filters' => array(
                    'display_name' => array(
                        'title' => 'Name',
                        'dbAlias' => 'display_name',
                        'operatorType' => CRM_Report_Form::OP_STRING,
                    ),                    
                ),
            ),
            'school' => array(
                'dao' => 'CRM_Contact_DAO_Contact',
                'alias' => 'school',
                
                
                'filters' => array(
                    'year' => array(
                        'title' => 'Leaving year',
                        'operatorType' => CRM_Report_Form::OP_SELECT,
                        'mmFilterType' => 'custom',
                        'options' => $this->getAlumniYears(true),
                        'type' => CRM_Utils_Type::T_INT
                    ),
                    'potential-involvement' => array(
                        'title' => 'Potential involvement',
                        'dbAlias' => 'how_do_you_want_to_help_your_sch_42',
                        'operatorType' => CRM_Report_Form::OP_SELECT,
                        'options' => $this->getCustomDataOptions('42')
                    ),                    
                    'local' => array(
                        'title' => 'Local to school',
                        'dbAlias' => 'live_local_to_school_58',
                        'operatorType' => CRM_Report_Form::OP_SELECT,
                        'options' => array(null=> '- select -', 1=>'yes',0=>'no')
                    ),                    
                    
                ),
            ),
            'education' => array(
                'dao' => 'CRM_Contact_DAO_Contact',
                'alias' => 'education',
                
                'filters' => array(
                    'undergrad' => array(
                        'title' => 'Undergraduate subject',
                        'dbAlias' => 'undergraduate_subject_54',
                        'operatorType' => CRM_Report_Form::OP_SELECT,
                        'options' => $this->getCustomDataOptions('54')
                    ),                    
                    'postgrad' => array(
                        'title' => 'Postgraduate subject',
                        'dbAlias' => 'postgraduate_institution_subject_50',
                        'operatorType' => CRM_Report_Form::OP_SELECT,
                        'options' => $this->getCustomDataOptions('50')
                    ),                    
                    'institution' => array(
                        'title' => 'University attended (both undergrad and postgrad)',
                        'mmFilterType' => 'custom',
                        'operatorType' => CRM_Report_Form::OP_SELECT,
                        'options' => $this->getHigherEd()
                    ),                    
                    'further-education' => array(
                        'dbAlias' => 'further_education_subject_46',
                        'title' => 'Further education',
                        'operatorType' => CRM_Report_Form::OP_SELECT,
                        'options' => $this->getCustomDataOptions('46')
                    ),                    
                    'a-levels' => array(
                        'title' => 'A-level',
                        'dbAlias' => 'a_levels_45',
                        'operatorType' => CRM_Report_Form::OP_SELECT,
                        'options' => $this->getCustomDataOptions('45')
                    ), 
                    'a-levels-2' => array(
                        'title' => 'A-level',
                        'dbAlias' => 'a_levels_45',
                        'operatorType' => CRM_Report_Form::OP_SELECT,
                        'options' => $this->getCustomDataOptions('45')
                    ), 
                ),
            ),
            'employment' => array(
                'dao' => 'CRM_Contact_DAO_Contact',
                
                'filters' => array(
                    'job-sector' => array(
                        'title' => 'Job sector',
                        'dbAlias' => 'employment_sector_44',
                        'operatorType' => CRM_Report_Form::OP_SELECT,
                        'options' => $this->getCustomDataOptions('44')
                    ),                    
                ),
            ),
            'current' => array(
                'dao' => 'CRM_Contact_DAO_Contact',
                
                'filters' => array(
                    'current-occupation' => array(
                        'title' => 'Current occupation',
                        'dbAlias' => 'what_are_you_doing_now__33',
                        'operatorType' => CRM_Report_Form::OP_SELECT,
                        'options' => $this->getCustomDataOptions('33')
                    ),                                                           
                ),
                
            ),
            'email' => array(
                'alias' => 'email',
                'dao' => 'CRM_Core_DAO_Email',
                'fields' => array(
                    'email' => array(
                        'title' => 'Email',
                        'required' => true
                    )
                )
            ),
            'phone' => array(
                'alias' => 'phone',
                'dao' => 'CRM_Core_DAO_Phone',
                'fields' => array(
                    'mobile' => array(
                        'dbAlias' => 'phone',
                        'title' => 'Mobile',
                        'required' => true
                    )
                )
            )
        );

        parent::__construct();
    }

    function preProcess() {
    
      parent::preProcess();
    }

    function postProcess( ) {
        
        $this->_recent = CRM_Utils_Array::value( 'recent', $_GET ) ? 1 : 0 ;
        
        $this->beginPostProcess( );
        
        $this->buildACLClause( $this->_aliases['alumni'] );

        $sql = $this->buildQuery( true );
        
//        echo $sql;exit;
        
        $rows = array();

        $this->buildRows ( $sql, $rows );
        
        $this->formatDisplay( $rows );
        
        
        $this->doTemplateAssignment( $rows );
        
        // hide actions and email addresses from csv export
        if($this->_outputMode=='csv'){
            unset($this->_columnHeaders['email_email']);
            unset($this->_columnHeaders['actions']);
            foreach($rows as &$row){
                unset($row['email_email']);
                unset($row['actions']);
            }
        }
        
        $this->endPostProcess( $rows );
    }

    
    // function select(){
    //     $this->_select = 'SELECT *';
    // }

    function from() {

        $this->_from = " 
        FROM civicrm_contact AS {$this->_aliases['alumni']} 
        LEFT JOIN civicrm_email AS {$this->_aliases['email']} ON {$this->_aliases['email']}.contact_id={$this->_aliases['alumni']}.id 

        LEFT JOIN civicrm_phone AS {$this->_aliases['phone']} ON {$this->_aliases['phone']}.contact_id={$this->_aliases['alumni']}.id AND phone_type_id=2

        LEFT JOIN civicrm_value_contact_reference_9 AS {$this->_aliases['school']}
            ON {$this->_aliases['school']}.entity_id={$this->_aliases['alumni']}.id
        
        LEFT JOIN civicrm_value_education_3 AS {$this->_aliases['education']}
            ON {$this->_aliases['education']}.entity_id={$this->_aliases['alumni']}.id
        
        LEFT JOIN civicrm_value_employment_13 AS {$this->_aliases['employment']}
            ON {$this->_aliases['employment']}.entity_id={$this->_aliases['alumni']}.id
        
        LEFT JOIN civicrm_value_current_activities_11 AS {$this->_aliases['current']}
            ON {$this->_aliases['current']}.entity_id={$this->_aliases['alumni']}.id
            
        {$this->_aclFrom}
        ";
        if($this->_recent){
            $this->_from .= " LEFT JOIN civicrm_log ON ({$this->_aliases['alumni']}.id = civicrm_log.entity_id AND civicrm_log.entity_table = 'civicrm_contact') ";
        }

    }

    function where(){

        if(!$this->_recent){

            $whereClausesToAdd = array(
                'alumni',
                'school',
                'current',
                'employment',
                'education'
            );

            foreach($whereClausesToAdd as $wc){
                foreach($this->_columns[$wc]['filters'] as $name => $filter){
                    if(!(isset($filter['mmFilterType']) && $filter['mmFilterType']=='custom') && strlen($this->_params["{$name}_value"])){
                        $value = $this->_params["{$name}_value"];
                        $where[] = " {$filter['alias']}.{$filter['dbAlias']} LIKE '%{$value}%' ";
                    }
                }
            }

            if(strlen($this->_params['year_value'])){
                $where[]="(leaving_year_32 >= '{$this->_params['year_value']}-01-01' AND leaving_year_32 <= '{$this->_params['year_value']}-12-31')";
            }

            if(strlen($this->_params['institution_value'])){
                $where[]="(postgraduate_institution_48 = {$this->_params['institution_value']} OR undergraduate_institution_49 = {$this->_params['institution_value']})";
            }

        }

        $where[]="contact_sub_type LIKE '%Student%'";
        $where[]="{$this->_aclWhere}";
        $this->_where = ' WHERE '.implode(' AND ', $where).' ';
    }

    function groupBy(){
        $this->_groupBy = "GROUP BY {$this->_aliases['alumni']}.id";        
    }
    function limit(){
        if($this->_recent){
            $this->_limit = " LIMIT 5 ";
        }
    }

    function orderBy(){
        if(!$this->_recent){
            $where[]="contact_sub_type LIKE '%Student%'";
            if(strlen($this->_params['year_value'])){
                $this->_orderBy = "ORDER BY last_name";        
            }
        } else {
            $this->_orderBy = "ORDER BY civicrm_log.modified_date DESC";        
            
        }
    }
    
    
    function modifyColumnHeaders(){
        $this->_columnHeaders['actions']=array('title'=>'Actions');
        
    }
    
    function getAlumniYears($with_select=false){
        if($with_select){
            $ret['']="- select -";
        }
        $this->buildACLClause( 'civicrm_contact' );        
        $sql = "
            SELECT YEAR(`leaving_year_32`) AS year, count(*) AS count
            FROM civicrm_contact
            INNER JOIN civicrm_value_contact_reference_9 ON civicrm_contact.id=civicrm_value_contact_reference_9.entity_id
            {$this->_aclFrom}
            WHERE {$this->_aclWhere} AND leaving_year_32 IS NOT NULL AND contact_sub_type = 'Student'
            GROUP BY YEAR(`leaving_year_32`)
            ORDER BY leaving_year_32 DESC
        ";
        $sql;
        $result = CRM_Core_DAO::ExecuteQuery($sql);
        while($result->fetch()){
            $ret[$result->year]="{$result->year} ($result->count alumni)";
        }
        return $ret;
    
    }
    function getTotalStudents($with_select=false){
        if($with_select){
            $ret['']="- select -";
        }
        $this->buildACLClause( 'civicrm_contact' );        
        $sql = "
            SELECT count(*) AS count
            FROM civicrm_contact
            {$this->_aclFrom}
            WHERE {$this->_aclWhere}
        ";
        return CRM_Core_DAO::singleValueQuery($sql);

    
    }
    
    function alterDisplay( &$rows ) {
        foreach($rows as &$row){
            
            // add actions
            $row['actions']="<div>
                <a href='/school-dashboard/alumni/view?reset=1&gid=15&id={$row['alumni_id']}'>view</a><a href='/school-dashboard/alumni/edit?reset=1&gid=14&id={$row['alumni_id']}'>edit</a>
            </div>
            ";
            
            //add spaces to phone numbers
            if(!strpos($row['phone_mobile'], ' ')){
                $row['phone_mobile']=substr($row['phone_mobile'],0, -6).' '.substr($row['phone_mobile'],-6);
            }
        }
    }
    
}

?>