<?php
if(!sizeof($_POST))
    return;
$vals = array_values($_POST);
if(User::isUsernameTaken($vals[0]))
    echo $lang["username_taken"];
?>