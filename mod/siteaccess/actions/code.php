<?php
    global $CONFIG;

    $tmp = get_input('c');
    $code = siteaccess_generate_captcha($tmp);
    $image = ImageCreateFromJPEG($CONFIG->pluginspath . "/siteaccess/images/code.jpg");
    $text_color = ImageColorAllocate($image, 80, 80, 80);
    Header("Content-type: image/jpeg");
    ImageString ($image, 5, 12, 2, $code, $text_color);
    ImageJPEG($image, '', 75);
    ImageDestroy($image);
    die();

?>
