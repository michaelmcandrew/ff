jQuery(document).ready(function() {
  jQuery("input#student_form_list_autocomplete").autocomplete({
    source: schools  
  });

  jQuery("input#student_form_list_go").click(function(){
    window.open(links[jQuery("input#student_form_list_autocomplete").val()]);
  })
});
