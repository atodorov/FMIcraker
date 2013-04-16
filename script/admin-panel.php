<?php
if($user->isLoggedIn())
{
    $path_arr = $page->getPathArr();
    if((isset($path_arr[1]) && !$user->hasPriv($path_arr[1])) || !$user->isAdmin())
        $page->page("forbidden", true);
}
else
{
    header("location: /login");
    exit;
}
?>