<?php
if(!sizeof($_POST))
    return;
$vals = array_values($_POST);
if(User::isEmailTaken($vals[0]))
    echo $lang["email_taken"];
?>
