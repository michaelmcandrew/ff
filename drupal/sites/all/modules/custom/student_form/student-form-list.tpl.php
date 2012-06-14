


<p>To sign up to your school's Future First Network, start typing the name of your school below.</p>
<p>Select your school from the dropdown list that will appear and then click the 'sign up' button. You'll then be taken to your school's sign up page where you can fill in your contact details and join your alumni network.</p>
<p>If you can't find your school or would like any assistance signing up then give the Future First team a call on 020 7239 8933 or email <a href="mailto:networks@futurefirst.org.uk">networks@futurefirst.org.uk</a></p>
<input id='student_form_list_autocomplete' size='45'>
<input type='button' id='student_form_list_go' value='Sign up'>
<script type="text/javascript">
var schools = [ <?php foreach($schools as $school) echo '"'.$school['name'].'", '; ?> ];
var links = [];
<?php foreach($schools as $school){
  echo "links[\"{$school['name']}\"]='{$school['link']}';\n"; 
}
?> 
</script>
