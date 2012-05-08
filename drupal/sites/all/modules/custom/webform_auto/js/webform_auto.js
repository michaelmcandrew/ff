(function ($) {
Drupal.behaviors.webform_auto = {
attach: function(context, settings) {

$(document).ready(function(){  
    if(document.getElementById('webform-client-form-2')){
       $("#edit-submitted-civicrm-1-contact-1-fieldset-fieldset-civicrm-1-contact-1-address-street-address").attr('readonly', true);
 $("#edit-submitted-civicrm-1-contact-1-fieldset-fieldset-civicrm-1-contact-1-address-city").attr('readonly', true);

 $("#edit-submitted-civicrm-1-contact-1-fieldset-fieldset-civicrm-1-contact-1-address-postal-code").attr('readonly', true);

$("#edit-submitted-civicrm-1-contact-1-fieldset-fieldset-civicrm-1-contact-1-email-email").attr('readonly', true);
	       
      //autoload functionality
    var crmURL ="/civicrm/ajax/rest?className=CRM_Contact_Page_AJAX&fnName=getContactList&json=1&context=school&org=1";
     
var xhr =  $("#edit-submitted-civicrm-1-contact-1-fieldset-fieldset-civicrm-1-contact-1-contact-organization-name").autocomplete(  crmURL,{                           
                                      width        : 250,
                                      selectFirst  : false,
    				      			  minChars     : 2,
                                      matchContains: true
	  }).result( function(event, data, formatted) {
	       orgDetails=data[1].split("::");
	       if(orgDetails[1]!='streetaddr'){
		 $("#edit-submitted-civicrm-1-contact-1-fieldset-fieldset-civicrm-1-contact-1-address-street-address").val(orgDetails[1]);
		 $("#edit-submitted-civicrm-1-contact-1-fieldset-fieldset-civicrm-1-contact-1-address-street-address").attr('readonly', true);
	       }
	       else{
		 $("#edit-submitted-civicrm-1-contact-1-fieldset-fieldset-civicrm-1-contact-1-address-street-address").val('');
		 $("#edit-submitted-civicrm-1-contact-1-fieldset-fieldset-civicrm-1-contact-1-address-street-address").attr('readonly', true);
	       }
	       if(orgDetails[2]!='city'){
		 $("#edit-submitted-civicrm-1-contact-1-fieldset-fieldset-civicrm-1-contact-1-address-city").val(orgDetails[2]);
	      $("#edit-submitted-civicrm-1-contact-1-fieldset-fieldset-civicrm-1-contact-1-address-city").attr('readonly', true);
	       }
	       else{
		 $("#edit-submitted-civicrm-1-contact-1-fieldset-fieldset-civicrm-1-contact-1-address-city").val('');
		$("#edit-submitted-civicrm-1-contact-1-fieldset-fieldset-civicrm-1-contact-1-address-city").attr('readonly', true);
	       }
	       if(orgDetails[3]!='postal_code'){
		 $("#edit-submitted-civicrm-1-contact-1-fieldset-fieldset-civicrm-1-contact-1-address-postal-code").val(orgDetails[3]);
	         $("#edit-submitted-civicrm-1-contact-1-fieldset-fieldset-civicrm-1-contact-1-address-postal-code").attr('readonly', true);
	       }
	       else{
		 $("#edit-submitted-civicrm-1-contact-1-fieldset-fieldset-civicrm-1-contact-1-address-postal-code").val('');
		 $("#edit-submitted-civicrm-1-contact-1-fieldset-fieldset-civicrm-1-contact-1-address-postal-code").attr('readonly', true);
	       }	      
	       if(orgDetails[4]!='contryid'){
		 $("#edit-submitted-civicrm-1-contact-1-fieldset-fieldset-civicrm-1-contact-1-address-country-id").val(orgDetails[4]);
		 $("#edit-submitted-civicrm-1-contact-1-fieldset-fieldset-civicrm-1-contact-1-address-country-id").attr('disabled', true);
	       }
	       else{
		 $("#edit-submitted-civicrm-1-contact-1-fieldset-fieldset-civicrm-1-contact-1-address-country-id").val('');
	         $("#edit-submitted-civicrm-1-contact-1-fieldset-fieldset-civicrm-1-contact-1-address-country-id").attr('disabled', true);
	       }
	       if(orgDetails[5]!='stateprovi'){
		 $("#edit-submitted-civicrm-1-contact-1-fieldset-fieldset-civicrm-1-contact-1-address-state-province-id").val(orgDetails[5]);
	         $("#edit-submitted-civicrm-1-contact-1-fieldset-fieldset-civicrm-1-contact-1-address-state-province-id").attr('disabled', true);
	       }
	       else{
		 $("#edit-submitted-civicrm-1-contact-1-fieldset-fieldset-civicrm-1-contact-1-address-state-province-id").val('');
		 $("#edit-submitted-civicrm-1-contact-1-fieldset-fieldset-civicrm-1-contact-1-address-state-province-id").attr('disabled', true);
		 
	       }
	     
        }).focus( );
      

    }
  });





  }
};
 })(jQuery); 
