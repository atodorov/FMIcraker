<?php
if($post->isPosting())
{
    $login_try = $user->tryLogin($post['username'], $post['password'], $post['remember_me']);
    if(is_string($login_try))
        $template->assign("login_error", $login_try);
    else
    {
        header("location: /profile");
        exit;
    }
}
?>