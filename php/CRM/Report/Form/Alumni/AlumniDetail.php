<?php
require_once 'CRM/Report/Form.php';
require_once 'CRM/Core/BAO/CustomOption.php';
class CRM_Report_Form_Alumni_AlumniDetail extends CRM_Report_Form {

    protected $_exposeContactID = false;

    protected $_instanceButtonName = 'test';
    protected $_createNewButtonName = 'test';
    protected $_printButtonName    = 'test';
    protected $_pdfButtonName      = 'test';
    protected $_add2groupSupported = false;

    protected $_customGroupExtends = array('Individual');

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
                        'required' => true
                    )
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
        
        $this->beginPostProcess( );
        
        $this->buildACLClause( $this->_aliases['alumni'] );

        $sql = $this->buildQuery( true );
//        
        $rows = array();

        $this->buildRows ( $sql, $rows );
        
        $this->formatDisplay( $rows );
        
        
        $this->doTemplateAssignment( $rows );
        $this->endPostProcess( );
    }

    
    // function select(){
    //     $this->_select = 'SELECT *';
    // }

    function from() {

      $this->_from = " 
        FROM civicrm_contact AS {$this->_aliases['alumni']} 
        LEFT JOIN civicrm_email AS {$this->_aliases['email']} ON {$this->_aliases['email']}.contact_id={$this->_aliases['alumni']}.id 
        LEFT JOIN civicrm_phone AS {$this->_aliases['phone']} ON {$this->_aliases['phone']}.contact_id={$this->_aliases['alumni']}.id AND phone_type_id=2
        {$this->_aclFrom}
      "; 
    }

    function where(){
        $this->_where = "WHERE {$this->_aclWhere}";        
    }

    function groupBy(){
        $this->_groupBy = "GROUP BY {$this->_aliases['alumni']}.id";        
    }

    function orderBy(){
        $this->_orderBy = "ORDER BY last_name";        
    }
    
    
    function modifyColumnHeaders(){
        $this->_columnHeaders['actions']=array('title'=>'Actions');
        
    }
    
    function alterDisplay( &$rows ) {
        foreach($rows as &$row){
            $row['actions']="
                <a href='#' onclick='updateViaProfile({$row['alumni_id']}); return false;'>e</a>
                <a href='/civicrm/report/school-dashboard/alumni-detail?id={$row['alumni_id']}'>v</a>";
        }
    }
    
}

?>