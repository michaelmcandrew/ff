jQuery(document).ready(function() {
    
  jQuery("input#student_form_list_autocomplete").tokenInput(list,   {tokenLimit: 1});

  jQuery("input#student_form_list_go").click(function(){
      window.open(links[jQuery("input#student_form_list_autocomplete").val()], '_self');
  })
});
