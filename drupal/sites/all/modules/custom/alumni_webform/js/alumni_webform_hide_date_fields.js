//find the fields on the page and set their vaules to 1st jan and hide them

dayFieldId = "#edit-submitted-civicrm-1-contact-1-fieldset-fieldset-civicrm-1-contact-1-cg9-custom-32-day"
monthFieldId = "#edit-submitted-civicrm-1-contact-1-fieldset-fieldset-civicrm-1-contact-1-cg9-custom-32-month"

jQuery(document).ready(function() {

  //set date to some value why not 1st Jan
  jQuery(monthFieldId).val('1')
  jQuery(dayFieldId).val('1')

  //hide day & month selector
  jQuery(dayFieldId).hide();
  jQuery(monthFieldId).hide();

});
