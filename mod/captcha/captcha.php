<html>
  <body>
    <form action="" method="post">
<script>
var RecaptchaOptions = {
   theme : 'white'
};
</script>

<?php


require_once('../../mod/captcha/recaptchalib.php');

// Get a key from http://recaptcha.net/api/getkey
$publickey = get_plugin_setting('api_publickey', 'captcha');
$privatekey = get_plugin_setting('api_privatekey', 'captcha');

# the response from reCAPTCHA
$resp = null;
# the error code from reCAPTCHA, if any
$error = null;

# was there a reCAPTCHA response?
if ($_POST["recaptcha_response_field"]) {
        $resp = recaptcha_check_answer ($privatekey,
                                        $_SERVER["REMOTE_ADDR"],
                                        $_POST["recaptcha_challenge_field"],
                                        $_POST["recaptcha_response_field"]);

        if ($resp->is_valid) {
                echo "You got it!";
        } else {
                # set the error code so that we can display it
                $error = $resp->error;
        }
}
echo recaptcha_get_html($publickey, $error);
?>
    <br/>
    <input type="submit" value="submit" />
    </form>
  </body>
</html>
