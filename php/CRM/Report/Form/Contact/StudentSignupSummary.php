<?php

/*
 +--------------------------------------------------------------------+
 | CiviCRM version 4.1                                                |
 +--------------------------------------------------------------------+
 | Copyright CiviCRM LLC (c) 2004-2011                                |
 +--------------------------------------------------------------------+
 | This file is a part of CiviCRM.                                    |
 |                                                                    |
 | CiviCRM is free software; you can copy, modify, and distribute it  |
 | under the terms of the GNU Affero General Public License           |
 | Version 3, 19 November 2007 and the CiviCRM Licensing Exception.   |
 |                                                                    |
 | CiviCRM is distributed in the hope that it will be useful, but     |
 | WITHOUT ANY WARRANTY; without even the implied warranty of         |
 | MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.               |
 | See the GNU Affero General Public License for more details.        |
 |                                                                    |
 | You should have received a copy of the GNU Affero General Public   |
 | License and the CiviCRM Licensing Exception along                  |
 | with this program; if not, contact CiviCRM LLC                     |
 | at info[AT]civicrm[DOT]org. If you have questions about the        |
 | GNU Affero General Public License or the licensing of CiviCRM,     |
 | see the CiviCRM license FAQ at http://civicrm.org/licensing        |
 +--------------------------------------------------------------------+
*/

/**
 *
 * @package CRM
 * @copyright CiviCRM LLC (c) 2004-2011
 * $Id$
 *
 */

require_once 'CRM/Report/Form.php';

class CRM_Report_Form_Contact_StudentSignupSummary extends CRM_Report_Form {
  protected $_summary = null;
  protected $_emailField   = false;
  protected $_phoneField   = false;
  protected $_exposeContactID = false;
  protected $_customGroupExtends = array('Individual');
  function __construct() {
    $this->_columns = array(

        'school' => array(

          'dao' => 'CRM_Contact_DAO_Contact', 

          'alias' => 'school', 

          'fields' => array(
            'id' => array(
              'no_display'=> true, 
              'required'  => true,
              ),
            'display_name' => array(
              'required'=> true, 
              'title' => 'School name'
              ), 
            'student_count' => array(
              'required'=> true, 
              'title' =>'Total',
              ),
            'facebook_count' => array(
              'required'=> true, 
              'title' =>'Including facebook',
              ),
            ), 
          'grouping'  => 'contact-fields', 

          'filters' => array(
            'member' => array(
              'title' => 'Is member school?',
              'operatorType' => CRM_Report_Form::OP_SELECT,
              'options' => array(
                  '' => ts('- select -'), 
                  'member'    => ts('Member'), 
                  'non-member'  => ts('Non Member'), 
              ),
            ),
          ),
          'order_bys'  => array(
              'display_name' => array(
                'title' => 'School name', 
                'default' => '1', 
                'default_weight' => '0', 
                'default_order' => 'ASC'
                ),  
              'student_count' => array(
                'title' => 'Student count', 
                'dbAlias' => 'school_student_count', 
                'default' => '1', 
                'default_weight' => '0', 
                'default_order' => 'DESC'
                ),
              'facebook_count' => array(
                'title' => 'Facebook count', 
                'dbAlias' => 'school_facebook_count', 
                'default' => '0', 
                'default_weight' => '0', 
                'default_order' => 'DESC'
                ),
              ),
          ),
        );

    parent::__construct();
  }

  function preProcess() {
    parent::preProcess();
  }

  function select() {
    $select = array();
    foreach ($this->_columns as $tableName => $table) {
      if (array_key_exists('fields', $table)) {
        foreach ($table['fields'] as $fieldName => $field) {
          if (CRM_Utils_Array::value('required', $field) || CRM_Utils_Array::value($fieldName, $this->_params['fields'])) {
              $alias = "{$tableName}_{$fieldName}";
            if($alias=='school_student_count'){
              $select[]="count(*) AS school_student_count";
            }elseif($alias=='school_facebook_count'){
              $select[]="sum(if(facebook_id_25 > 0,1,0)) AS school_facebook_count";
            }else{
              $select[] = "{$field['dbAlias']} AS {$alias}";
            }
            $this->_columnHeaders["{$tableName}_{$fieldName}"]['type']  = CRM_Utils_Array::value('type', $field);
            $this->_columnHeaders["{$tableName}_{$fieldName}"]['title'] = $field['title'];
            $this->_selectAliases[] = $alias;
          }
        }
      }
    }
    $this->_select = "SELECT " . implode(', ', $select) . " ";
  }

  static function formRule($fields, $files, $self) {  
    $errors = $grouping = array();
    return $errors;
  }
  function where(){
    $this->_where = " WHERE {$this->_aliases['school']}.contact_sub_type LIKE '%School%' ";
    if($this->_params['member_value'] == 'member'){
        $this->_where .= "AND member.id IS NOT NULL";
    }
    if($this->_params['member_value'] == 'non-member'){
        $this->_where .=  "AND member.id IS NULL";
    }
    
  }

  function groupBy() {
    $this->_groupBy = " GROUP BY {$this->_aliases['school']}.id ";
  }

  function addCustomDataToColumns($addFields = true) {
    if (empty($this->_customGroupExtends)) {
      return;
    }
    if(!is_array($this->_customGroupExtends)) {
      $this->_customGroupExtends = array($this->_customGroupExtends);  
    }

    $sql       = "
      SELECT cg.table_name, cg.title, cg.extends, cf.id as cf_id, cf.label, 
             cf.column_name, cf.data_type, cf.html_type, cf.option_group_id, cf.time_format
               FROM   civicrm_custom_group cg 
               INNER  JOIN civicrm_custom_field cf ON cg.id = cf.custom_group_id
               WHERE cg.extends IN ('" . implode("', '", $this->_customGroupExtends) . "') AND 
               cg.is_active = 1 AND 
               cf.is_active = 1 AND 
               cf.is_searchable = 1 AND
               cg.title = 'Contact Reference'
               ORDER BY cg.weight, cf.weight";
    $customDAO = CRM_Core_DAO::executeQuery($sql);

    $curTable  = null;
    while($customDAO->fetch()) {

      if ($customDAO->table_name != $curTable) {
        $curTable  = $customDAO->table_name;
        $curFields = $curFilters = array();

        $this->_columns[$curTable]['dao']      = 'CRM_Contact_DAO_Contact'; // dummy dao object
        $this->_columns[$curTable]['extends']  = $customDAO->extends;
        $this->_columns[$curTable]['grouping'] = $customDAO->table_name;
        $this->_columns[$curTable]['group_title'] = $customDAO->title;

        foreach (array('fields', 'filters', 'group_bys') as $colKey) {
          if (! array_key_exists($colKey, $this->_columns[$curTable])) {
            $this->_columns[$curTable][$colKey] = array();
          }
        }
      }
      $fieldName = 'custom_' . $customDAO->cf_id;

      if ($addFields) {
        $curFields[$fieldName] = 
          array('name'     => $customDAO->column_name, // this makes aliasing work in favor
              'title'    => $customDAO->label, 
              'dataType' => $customDAO->data_type, 
              'htmlType' => $customDAO->html_type);
      }
      if ($this->_customGroupFilters) {
        $curFilters[$fieldName] = 
          array('name'     => $customDAO->column_name, // this makes aliasing work in favor
              'title'    => $customDAO->label, 
              'dataType' => $customDAO->data_type, 
              'htmlType' => $customDAO->html_type);
      }

      switch($customDAO->data_type) {

        case 'Date':
          // filters
          $curFilters[$fieldName]['operatorType'] = CRM_Report_Form::OP_DATE;

          $curFilters[$fieldName]['type']         = CRM_Utils_Type::T_DATE;
          // CRM-6946, show time part for datetime date fields
          if ($customDAO->time_format) {
            $curFields[$fieldName]['type'] = CRM_Utils_Type::T_TIMESTAMP;
          }
          break;

        case 'Boolean':
          $curFilters[$fieldName]['operatorType'] = CRM_Report_Form::OP_SELECT;
          $curFilters[$fieldName]['options']      = 
            array('' => ts('- select -'), 
                1  => ts('Yes'), 
                0  => ts('No'), );
          $curFilters[$fieldName]['type']         = CRM_Utils_Type::T_INT;
          break;

        case 'Int':
          $curFilters[$fieldName]['operatorType'] = CRM_Report_Form::OP_INT;
          $curFilters[$fieldName]['type']         = CRM_Utils_Type::T_INT;
          break;

        case 'Money':
          $curFilters[$fieldName]['operatorType'] = CRM_Report_Form::OP_FLOAT;
          $curFilters[$fieldName]['type']         = CRM_Utils_Type::T_MONEY;
          break;

        case 'Float':
          $curFilters[$fieldName]['operatorType'] = CRM_Report_Form::OP_FLOAT;
          $curFilters[$fieldName]['type']         = CRM_Utils_Type::T_FLOAT;
          break;

        case 'String':
          $curFilters[$fieldName]['type']  = CRM_Utils_Type::T_STRING;

          if (!empty($customDAO->option_group_id)) {
            if (in_array($customDAO->html_type, array('Multi-Select', 'AdvMulti-Select', 'CheckBox'))) {
              $curFilters[$fieldName]['operatorType'] = CRM_Report_Form::OP_MULTISELECT_SEPARATOR;
            } else {
              $curFilters[$fieldName]['operatorType'] = CRM_Report_Form::OP_MULTISELECT;
            }
            if($this->_customGroupFilters) {
              $curFilters[$fieldName]['options'] = array();
              $ogDAO = CRM_Core_DAO::executeQuery("SELECT ov.value, ov.label FROM civicrm_option_value ov WHERE ov.option_group_id = %1 ORDER BY ov.weight", array(1 => array($customDAO->option_group_id, 'Integer')));
              while($ogDAO->fetch()) {
                $curFilters[$fieldName]['options'][$ogDAO->value] = $ogDAO->label;
              }
            }
          }

          break;


        case 'StateProvince': 

          break;

        case 'Country':

          break;

        case 'ContactReference':
          $curFilters[$fieldName]['type']  = CRM_Utils_Type::T_STRING;


          $curFilters[$fieldName]['name']  = 'display_name';
          $curFilters[$fieldName]['alias'] = "contact_{$fieldName}_civireport";

          $curFields[$fieldName]['type']   = CRM_Utils_Type::T_STRING;
          $curFields[$fieldName]['name']   = 'display_name';  
          $curFields[$fieldName]['alias']  = "contact_{$fieldName}_civireport";
          $curFields[$fieldName]['required'] = true;
          $curFields[$fieldName]['title'] = 'School Name';
          break;

        default:
          $curFields [$fieldName]['type']  = CRM_Utils_Type::T_STRING;
          $curFilters[$fieldName]['type']  = CRM_Utils_Type::T_STRING;
      }

      if (! array_key_exists('type', $curFields[$fieldName])) {
        $curFields[$fieldName]['type'] = $curFilters[$fieldName]['type'];
      } 

      if ($addFields) {
        $this->_columns[$curTable]['fields'] = 
          array_merge($this->_columns[$curTable]['fields'], $curFields);
      }
      if ($this->_customGroupFilters) {
        $this->_columns[$curTable]['filters'] = 
          array_merge($this->_columns[$curTable]['filters'], $curFilters);
      }
      if ( $this->_customGroupGroupBy) {
        $this->_columns[$curTable]['group_bys'] = 
          array_merge($this->_columns[$curTable]['group_bys'], $curFields);

      } 
    }
  }

  function from() {

    $this->_from = " 
      FROM civicrm_contact AS {$this->_aliases['school']} 
      JOIN civicrm_value_contact_reference_9 AS school_data ON {$this->_aliases['school']}.id = school_data.contact_reference_21
      JOIN civicrm_contact AS student ON school_data.entity_id = student.id
      LEFT JOIN civicrm_value_facebook_10 AS facebook ON facebook.entity_id = student.id
      LEFT JOIN civicrm_membership AS member ON member.contact_id = school_data.contact_reference_21
      {$this->_aclFrom}
    "; 


  }
  
  function alterDisplay( &$rows ){
    foreach ( $rows as $rowNum => $row ) {
      $rows[$rowNum]['school_student_count_link'] = CRM_Utils_System::url( "civicrm/report/school/studentdetail", 'reset=1&force=1&id_value=' . $row['school_id'], $this->_absoluteUrl );
      $rows[$rowNum]['school_display_name_link'] = CRM_Utils_System::url( "civicrm/contact/view", 'reset=1&cid=' . $row['school_id'], $this->_absoluteUrl );
    }
  }
}
