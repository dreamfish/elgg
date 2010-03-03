<?php
//This JS is inserted to the bottom of the registration page.
//It adds additional fields to the standard registration form
//Also, it adds some validation to the form
?>
<script language="javascript" type="text/javascript">
//var form = $("form");
//form.append('<input type="checkbox" name="terms" value="terms" />');

function validateForm() {
	var ok = true;
	var regform = document.forms[0];
//	var form = $("form");

	if ( regform.yes_dreamfish.checked == false ) {
		alert("Please confirm that you read the terms and the guidelines!");
		ok = false;
	}

	if (regform.name.value == "") {
		alert("Please fill in your name");
		ok = false;
	}

	if (regform.email.value == "") {
		alert("Please provide an email address");
		ok = false;
	}

	if (regform.username.value == "") {
		alert("Please provide an username");
		ok = false;
	}

	if (regform.password.value == "") {
		alert("Password cannot be empty");
		ok = false;
	}

	if (regform.password2.value == "") {
		alert("Password repetition cannot be empty");
		ok = false;
	}
	if (regform.password.value != regform.password2.value) {
		alert("Passwords do not match!");
		ok = false;
	}

	if (ok) {
		regform.submit();
	} 
}
// override the default submit function.
//form.submit(function() { submitForm(); return false;});

</script>
	
	

