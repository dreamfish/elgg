<?php 
// This JS is inserted to the bottom of the registration page.
// It goes through and submits appends two form elements to a new, hidden form,
// and submits that form instead of the displayed one.
?>
<script language="javascript" type="text/javascript">
var form = $("form");
form.append('<input type="hidden" name="uncaptcha_code" value="<?php echo uncaptcha_generate_code(); ?>" />');
form.append('<input type="text" id="<?php echo get_plugin_setting('trick_field_name', 'uncaptcha'); ?>" name="<?php echo get_plugin_setting('trick_field_name', 'uncaptcha'); ?>" />');
$('captcha').hide();

function submitForm() {
	inputs = $(":input");
	action = form[0].attributes.action.value;
	method = form[0].attributes.method.value;

	newForm = document.createElement("form");
	newForm.action = action;
	newForm.method = method;
    
    for (i=0; i<inputs.length; i++) {
        // check for select elements, textarea.
        varType = inputs[i].type;
        switch (varType) {
            case 'button':
            case 'submit':
                continue;
            case 'radio':
                if (!inputs[i].checked) {
                    continue;
                }

            default:
                varName = inputs[i].name;
                varValue = inputs[i].value;
                break;
        }

        newInput = document.createElement('input');
        //newInput.type = 'hidden';
        newInput.name = varName;
        newInput.value = varValue;
        newForm.appendChild(newInput);
    }

    newForm.style.display = 'none';
    document.body.appendChild(newForm);
    newForm.submit();
    
    // prevent from using "real" form submit.
    return false;
}

// override the default submit function.
form.submit(function() { submitForm(); return false;});
</script>