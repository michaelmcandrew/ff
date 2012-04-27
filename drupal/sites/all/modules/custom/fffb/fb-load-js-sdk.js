window.fbAsyncInit = function() {
	FB.init({
		appId      : '434425766583934',
		status     : true, 
		cookie     : true,
		xfbml      : true,
		oauth      : true,
	});
};
(function(d){
	var js, id = 'facebook-jssdk'; if (d.getElementById(id)) {return;}
	js = d.createElement('script'); js.id = id; js.async = true;
	js.src = "//connect.facebook.net/en_US/all.js";
	d.getElementsByTagName('head')[0].appendChild(js);
}(document));

