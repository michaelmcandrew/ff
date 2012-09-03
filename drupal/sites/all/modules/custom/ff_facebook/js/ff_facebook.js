(function(d){
	var js, id = 'facebook-jssdk', ref = d.getElementsByTagName('script')[0];
	if (d.getElementById(id)) {return;}
	js = d.createElement('script'); js.id = id; js.async = true;
	js.src = "//connect.facebook.net/en_US/all.js";
	ref.parentNode.insertBefore(js, ref);
}(document));

window.fbAsyncInit = function() {
	FB.init({
		appId      : '434425766583934',
		status     : true, 
		cookie     : true,
		xfbml      : true,
		oauth      : true,
	});

	FB.Event.subscribe('auth.statusChange', function(response) {
	if (response.authResponse) {
		// user has auth'd your app and is logged into Facebook
		FB.api('/me', function(me){
			populateFields(me);
			if (me.name) {
				jQuery('#auth-displayname').html(me.name);
			}
			})
                	jQuery('#auth-loggedout').hide();
                	jQuery('#auth-loggedin').show();
		} else {
			// user has not auth'd your app, or is not logged into Facebook
                	jQuery('#auth-loggedout').show();
                	jQuery('#auth-loggedin').hide();
		}
	});

	jQuery('#auth-loginlink').click(function(){
		FB.login(function(response) {
   			// handle the response
 		}, {scope: 'email, user_education_history, user_work_history'});

	});
	jQuery('#auth-logoutlink').click(function(){
		FB.logout();
		jQuery('#auth-loggedout').show();
		jQuery('#auth-loggedin').hide();
	})
        jQuery('#edit-submit').click(function(){
		FB.logout();
	});
};

function populateFields(me){
	window.FB.api('/me', function(me){
		jQuery('#edit-submitted-civicrm-1-contact-1-fieldset-fieldset-basic-info-civicrm-1-contact-1-contact-first-name').val(me.first_name);
		jQuery('#edit-submitted-civicrm-1-contact-1-fieldset-fieldset-basic-info-civicrm-1-contact-1-contact-last-name').val(me.last_name);
		jQuery('#edit-submitted-civicrm-1-contact-1-fieldset-fieldset-basic-info-civicrm-1-contact-1-email-email').val(me.email);
		jQuery('#edit-submitted-civicrm-1-contact-1-fieldset-fieldset-basic-info-civicrm-1-contact-1-cg10-custom-25').val(me.id);
	})
}

