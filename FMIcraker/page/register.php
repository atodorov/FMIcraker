<?php
$securimage = new Securimage();
$error = "";
$success = false;
echo User::isUsernameTaken("ss");
if($reg_ret = $user->tryRegister())
{
    if(is_string($reg_ret))
        $error = $reg_ret;
    else
    {
        $success = true;
        $template->assign("email",$post['email']);
    }
}
$template->assign("error", $error);
$template->assign("success", $success);
?>