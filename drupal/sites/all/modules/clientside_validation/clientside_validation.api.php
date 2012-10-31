<?php
/**
 * @file
 * This is the api documentation for clientside validation hooks.
 */

/**
 * Some modules allow users to define extra validation rules defined in a hook.
 * (e.g hook_webform_validation_validators()). To support these custom rules,
 * clientside validation has its own hook, hook_clientside_validation_rule_alter.
 * We had to use an 'alter' hook because module_invoke and module_invoke_all
 * does not support passing arguments by reference, drupal_alter does.
 *
 * If you want to add support for your custom validation rules defined for
 * webform_validation, fapi_validation or field_validation, you can use this
 * hook. Every validation rule that is defined for any of these modules and
 * is not implemented as standard by clientside_validation is passed through to
 * this hook (given that the related clientside validation module is enabled and
 * that clientside validation is enabled for the specific form).
 *
 * The first parameter to this hook is an array. This array, consitently named
 * $js_rules, is passed by reference throughout the entire module and is the key
 * to the entire functionality of this module.
 *
 * The second parameter is the form element or webform component (dependend on
 * third parameter)
 *
 * The third parameter, named $context, is a structured array. It consists of
 * the following keys:
 *  'type':       The type of form validation we are dealing with. Can be either
 *                'webform', 'fapi', 'field_validation' or 'element_validate'.
 *  'rule':       An array representing the webform validation, fapi validation or
 *                field validation rule. Only present when 'type' is 'webform', 'fapi'
 *                or 'field_validation'.
 *  'functions':  An array of functions found in the '#element_validate' of the element.
 *                Only present if type is 'element_validate'.
 *  'message':    The default error message for when this rule does not pass
 *                validation.Only present when 'type' is 'webform', 'fapi'
 *                or 'field_validation'.
 *
 * @param array $js_rules
 * An array structured like this:
 * $js_rules[$inputname][$rulename] = $parameters;
 * $js_rules[$inputname]['messages'][$rulename] = $message;
 * Where $inputname is the name attribute of the input element, $rulename is
 * the name of the rule (e.g. 'email'), $parameters is either TRUE (e.g. for
 * 'email') or an array (e.g. for 'range': array(2, 10) or 'max_length' array(10)) and
 * $message is the error message displayed when the validation does not pass.
 * @param array $element
 * Either a form element or a webform component.
 * @param array $context
 * A structured array consiting of the following keys:
 *  'type':       The type of form validation we are dealing with. Can be either
 *                'webform', 'fapi', 'field_validation' or 'element_validate'.
 *  'rule':       An array representing the webform validation, fapi validation or
 *                field validation rule. Only present when 'type' is 'webform', 'fapi'
 *                or 'field_validation'.
 *  'functions':  An array of functions found in the '#element_validate' of the element.
 *                Only present if type is 'element_validate'.
 *  'message':    The default error message for when this rule does not pass
 *                validation.Only present when 'type' is 'webform', 'fapi'
 *                or 'field_validation'.
 *
 * In the example below we use validations that are already implemented as usage
 * examples. In the example the following rules are implemented for clientside
 * validation:
 *  - minimum length for webform_validation
 *  - specific characters for fapi_validation
 *  - regular expression for field_validation
 */
function hook_clientside_validation_rule_alter(&$js_rules, $element, $context) {
  switch ($context['type']) {
    case 'webform':
      if ($context['rule']['validator'] == 'min_length') {
        _clientside_validation_set_minmaxlength($component['element_name'], $component['element_title'], $context['rule']['data'], '', $js_rules, $context['message']);
      }
      break;

    case 'fapi':
      if ($context['rule']['callback'] == 'fapi_validation_rule_chars') {
        _clientside_validation_set_specific_values($element['#name'], $element['#title'], $context['params'], $js_rules);
      }
      break;

    case 'field_validation':
      if ($context['rule']['validator'] == 'regex') {
        _clientside_validation_set_regex($element['#name'], $element['#title'], $js_rules, $context['rule']['data'], $context['message']);
      }
      break;

    case 'element_validate':
      if (in_array('_container_validate', $context['functions'])) {
        _clientside_validation_set_not_equal(
          $element['textfield_one']['#name'],
          $element['textfield_one']['#title'],
          array(
            array(
            'form_key' => $element['textfield_two']['#name'],
            'name' => $element['textfield_two']['#title']
            ),
          ),
          $js_rules,
          t("The two fields cannot have the same value")
        );
      }
      break;

    default:
      break;
  }
}

/**
 * Translatable strings for localize.drupal.org
 */
t('!title field is required.', array('!title' => 'title'));
t('!title field accepts only numbers.', array('!title' => 'title'));
t('!title field accepts only numbers (use a \'.\' as decimal point).', array('!title' => 'title'));
t('!title field accepts only numbers (use a \',\' as decimal point).', array('!title' => 'title'));
t('!title field has to be between !min and !max.', array('!title' => 'title', '!min' => 1, '!max' => 5));
t('!title field has to be greater than !min.', array('!title' => 'title', '!min' => 1));
t('!title field has to be smaller than !max.', array('!title' => 'title', '!max' => 5));
t('!title field has to have between !min and !max values.', array('!title' => 'title', '!min' => 1, '!max' => 5));
t('!title field has to have minimal !min values.', array('!title' => 'title', '!min' => 1));
t('!title field has to have maximum !max values.', array('!title' => 'title', '!max' => 5));
t('!title field has to have between !min and !max words.', array('!title' => 'title', '!min' => 1, '!max' => 5));
t('!title field has to have minimal !min words.', array('!title' => 'title', '!min' => 1));
t('!title field has to have maximum !max words.', array('!title' => 'title', '!max' => 5));
t('!title field can not contain any HTML tags', array('!title' => 'title'));
t('!title field can not contain any HTML tags exept !allowed', array('!title' => 'title', '!allowed' => 'a'));
t('!title field has to be equal to !firstone.', array('!title' => 'title', '!firstone' => 'title2'));
t('!title field has to different from !firstone', array('!title' => 'title', '!firstone' => 'title2'));
t('!title field has to be one of the following values: !values.', array('!title' => 'title', '!values' => 'value1, value2'));
t('!title field must consist of following elements only: !elements.', array('!title' => 'title', '!elements' => 'element1, element2'));
t('!title field can not consist of following elements: !elements.', array('!title' => 'title', '!elements' => 'element1, element2'));
t('!title field is not a valid EAN number.', array('!title' => 'title'));
t('!title field does not match the required pattern.', array('!title' => 'title'));
t("Only files with a %exts extension are allowed.", array('%exts' => 'png, jpeg, gif'));
t('You can select no more than !max values for !title.', array('!title' => 'title', '!max' => 5));
t('You must select at least !min values for !title.', array('!title' => 'title', '!min' => 1));
t('You must select between !min and !max values for !title.', array('!title' => 'title', '!min' => 1, '!max' => 5));
t('The value in !title is not a valid email address.', array('!title' => 'title'));
t('The value in !title is not a valid url.', array('!title' => 'title'));
t('The value in !title is not a valid phone number.', array('!title' => 'title'));
t('The value in !title is not a valid date.', array('!title' => 'title'));
t('Wrong answer for !title.', array('!title' => 'title'));
t('!title field has to be greater than !min with steps of !step and smaller than !max.', array('!title' => 'title', '!min' => 1, '!step' => 1, '!max' => 5));
t('!title field has to be greater than !min with steps of !step.', array('!title' => 'title', '!min' => 1, '!step' => 1));
t('!title field has to be smaller than !max and must be dividable by !step.', array('!title' => 'title', '!step' => 1, '!max' => 5));
t('!title field must be a valid color code.', array('!title' => 'title'));

