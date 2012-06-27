/**
 * File:        clientside_validation_html5.js
 * Version:     7.x-1.x
 * Description: Add clientside validation rules
 * Author:      Attiks
 * Language:    Javascript
 * Project:     clientside_validation html5
 * @module clientside_validation
 */

(/** @lends Drupal */function ($) {
  /**
   * Drupal.behaviors.clientsideValidationHtml5.
   *
   * Attach clientside validation to the page for HTML5.
   */
  Drupal.behaviors.clientsideValidationHtml5 = {
    attach: function (context) {
      $(document).bind('clientsideValidationAddCustomRules', function(event){
        /**
         * HTML5 specific rules.
         * @name _bindHTML5Rules
         * @memberof Drupal.clientsideValidation
         * @method
         * @private
         */
        function _getMultiplier(a, b, c) {
          var inta = Number(parseInt(a));
          var mula = a.length - inta.toString().length - 1;

          var intb = parseInt(b);
          var mulb = b.toString().length - intb.toString().length - 1;

          var intc = parseInt(c);
          var mulc = c.toString().length - intc.toString().length - 1;

          var multiplier = Math.pow(10, Math.max(c, Math.max(mula, mulb)));
          return (multiplier > 1) ? multiplier : 1;
        }

        jQuery.validator.addMethod("Html5Min", function(value, element, param) {
          //param[0] = min, param[1] = step;
          var min = param[0];
          var step = param[1];
          var multiplier = _getMultiplier(value, min, step);

          value = parseInt(parseFloat(value) * multiplier);
          min = parseInt(parseFloat(min) * multiplier);

          var mismatch = 0;
          if (param[1] != 'any') {
            var step = parseInt(parseFloat(param[1]) * multiplier);
            mismatch = (value - min) % step
          }
          return this.optional(element) || (mismatch == 0 && value >= min);
        }, jQuery.format('Value must be greater than {0} with steps of {1}.'));

        jQuery.validator.addMethod("Html5Max", function(value, element, param) {
          //param[0] = max, param[1] = step;
          var max = param[0];
          var step = param[1];
          var multiplier = _getMultiplier(value, max, step);

          value = parseInt(parseFloat(value) * multiplier);
          max = parseInt(parseFloat(max) * multiplier);

          var mismatch = 0;
          if (param[1] != 'any') {
            var step = parseInt(parseFloat(param[1]) * multiplier);
            mismatch = (max - value) % step
          }
          return this.optional(element) || (mismatch == 0 && value <= max);
        }, jQuery.format('Value must be smaller than {0} and must be dividable by {1}.'));

        jQuery.validator.addMethod("Html5Range", function(value, element, param) {
          //param[0] = min, param[1] = max, param[2] = step;
          var min = param[0];
          var max = param[1];
          var step = param[2]
          var multiplier = _getMultiplier(value, min, step);

          value = parseInt(parseFloat(value) * multiplier);
          min = parseInt(parseFloat(min) * multiplier);
          max = parseInt(parseFloat(max) * multiplier);

          var mismatch = 0;
          if (param[2] != 'any') {
            var step = parseInt(parseFloat(param[2]) * multiplier);
            mismatch = (value - min) % step
          }
          return this.optional(element) || (mismatch == 0 && value >= min && value <= max);
        }, jQuery.format('Value must be greater than {0} with steps of {2} and smaller than {1}.'));

        jQuery.validator.addMethod("Html5Color", function(value, element, param) {
          return /^#([a-f]|[A-F]|[0-9]){6}$/.test(value);
        }, jQuery.format('Value must be a valid color code'));
      });
    }
  }
})(jQuery);
