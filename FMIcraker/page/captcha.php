<?php
if(!sizeof($_POST))
    return;
$securimage = new Securimage();
$vals = array_values($_POST);
if(!$securimage->check($vals[0]))
    echo $lang["wrong_captcha"];
?>