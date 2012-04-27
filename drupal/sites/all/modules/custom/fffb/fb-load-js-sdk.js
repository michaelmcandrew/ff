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
                console.log("me");
                console.log(me);
                if (me.name) {
                  document.getElementById('auth-displayname').innerHTML = me.name;
                }
              })
              document.getElementById('auth-loggedout').style.display = 'none';
              document.getElementById('auth-loggedin').style.display = 'block';
            } else {
              // user has not auth'd your app, or is not logged into Facebook
              document.getElementById('auth-loggedout').style.display = 'block';
              document.getElementById('auth-loggedin').style.display = 'none';
            }
          });
};
(function(d){
	var js, id = 'facebook-jssdk'; if (d.getElementById(id)) {return;}
	js = d.createElement('script'); js.id = id; js.async = true;
	js.src = "//connect.facebook.net/en_US/all.js";
	d.getElementsByTagName('head')[0].appendChild(js);
}(document));

function populateFields(u){ jQuery('#edit-submitted-civicrm-1-contact-1-fieldset-fieldset-basic-info-civicrm-1-contact-1-contact-first-name').val(u.first_name); 
jQuery('#edit-submitted-civicrm-1-contact-1-fieldset-fieldset-basic-info-civicrm-1-contact-1-contact-last-name').val(u.last_name);   
jQuery('#edit-submitted-civicrm-1-contact-1-fieldset-fieldset-basic-info-civicrm-1-contact-1-email-email').val(u.email); 

}