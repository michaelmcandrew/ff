/**
 * File:        clientside_validation_ife.js
 * Version:     7.x-1.x
 * Description: Add clientside validation rules
 * Author:      Attiks
 * Language:    Javascript
 * Project:     clientside_validation ife
 * @module clientside_validation
 */

(/** @lends Drupal */function ($) {
  /**
   * Drupal.behaviors.clientsideValidationHtml5.
   *
   * Attach clientside validation to the page for HTML5.
   */
  Drupal.behaviors.clientsideValidationIfe = {
    attach: function (context) {
      $(document).bind('clientsideValidationInitialized', function (event, formid){
        /**
         * IFE specific rules.
         * @name _bindIfeRules
         * @memberof Drupal.clientsideValidation
         * @method
         * @private
         */
        jQuery.each(Drupal.myClientsideValidation.forms, function (formid) {
          Drupal.myClientsideValidation.validators[formid].showErrors(Drupal.settings.clientsideValidation.forms[formid].serverSideErrors);
        });
      });
    }
  }
})(jQuery);