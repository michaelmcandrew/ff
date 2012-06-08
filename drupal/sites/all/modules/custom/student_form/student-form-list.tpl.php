<p>To sign up to your school's Future First Network, start typing the name of your school below.  Once you've found your school, hit the sign up button to go to your school's sign up page. If you can't find your school or would like any assistance signing up then give the Future First team a call on 020 7239 8933 or email <a href="mailto:networks@futurefirst.org.uk">networks@futurefirst.org.uk</a></p>
<input id='student_form_list_autocomplete'></input>
<input type='button' id='student_form_list_go' value='sign up'>
<script type="text/javascript">
var schools = [ <?php foreach($schools as $school) echo '"'.$school['name'].'", '; ?> ];
var links = [];
<?php foreach($schools as $school){
  echo "links[\"{$school['name']}\"]='{$school['link']}';\n"; 
}
?> 
</script>
