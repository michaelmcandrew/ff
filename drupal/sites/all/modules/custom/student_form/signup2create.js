(function ($) {
  Drupal.behaviors.fffb = {
    attach: function(context, settings) {
      if($('body').hasClass('not-logged-in') && FB._userStatus=='connected'){
	//load('/fbconnect/register/create');
	//window.location.reload();
	load(window.location.href='/fbconnect/register/create';};
    }
  }
})(jQuery);
