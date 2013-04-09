<?php

class CRM_Report_Form_Activity_RecentActivity extends CRM_Report_Form{

  var $activityTypes = array(
    '48' => array(
      'name' => 'Via SMS',
    ),
    '38' => array(
      'name' => 'Via email'
    )
  );

  function __construct(){

    //i need to get the activity type, the contact name and the date

    $this->_columns = array(
      'activity' => array(
        'dao' => 'CRM_Activity_DAO_Activity',
        'alias' => 'activity',
        'fields' => array(
          'activity_type_id' => array(
            'title' => 'Update method',
            'required' => TRUE
          ),
          'activity_date_time' => array(
            'title' => 'Date',
            'required' => TRUE
          )
        )
      ),
      'activity_target' => array(
        'dao' => 'CRM_Activity_DAO_ActivityTarget',
        'fields' => array(
          'target_contact_id' => array(
            'title' => 'Target contact ID',
            'required' => TRUE,
            'no_display'=> true, 
          )
        )
      ),
      'contact' => array(
        'alias' => 'contact',
        'dao' => 'CRM_Contact_DAO_Contact',
        'fields' => array(
          'display_name' => array(
            'title' => 'Name',
            'required' => TRUE
          ),
        )
      )
    );
    parent::__construct();
  }

  function from(){
    $this->_from  ="FROM civicrm_activity AS {$this->_aliases['activity']} ";
    $this->_from .="JOIN civicrm_activity_target AS {$this->_aliases['activity_target']} ON {$this->_aliases['activity']}.id={$this->_aliases['activity_target']}.activity_id ";
    $this->_from .="JOIN civicrm_contact AS {$this->_aliases['contact']} ON {$this->_aliases['activity_target']}.target_contact_id={$this->_aliases['contact']}.id";
    $this->_from .= $this->_aclFrom;
  }

  function where(){

    $where[]="1";
    $where[]="activity_type_id IN (38,48)";
    $where[]="{$this->_aclWhere}";
    $this->_where = ' WHERE '.implode(' AND ', $where).' ';
  }
    function modifyColumnHeaders(){
        $this->_columnHeaders['actions']=array('title'=>'Actions');
        
    }
  function alterDisplay( &$rows ) {
    foreach($rows as &$row){
      // add actions
      //print_r($row);exit;
      $row['actions']="<div>
        <a href='/school-dashboard/alumni/view?reset=1&gid=15&id={$row['activity_target_target_contact_id']}'>view</a> <a href='/school-dashboard/alumni/edit?reset=1&gid=14&id={$row['activity_target_target_contact_id']}'>edit</a>
        </div>
        ";
      $row['activity_activity_type_id']=$this->activityTypes[$row['activity_activity_type_id']]['name'];

      //add spaces to phone numbers
    }
  }

  function orderBy(){
    $this->_orderBy = 'ORDER BY activity_date_time DESC'; 
  
  }


  function postProcess() {

    // get ready with post process params
    $this->beginPostProcess();

    $this->buildACLClause( $this->_aliases['contact'] );
    // build query
    $sql = $this->buildQuery();
    //echo $sql;
    // build array of result based on column headers. This method also allows
    // modifying column headers before using it to build result set i.e $rows.
    $this->buildRows($sql, $rows);

    // format result set.
    $this->formatDisplay($rows);

    // assign variables to templates
    $this->doTemplateAssignment($rows);

    // do print / pdf / instance stuff if needed
    //$this->endPostProcess($rows);
  }
}
