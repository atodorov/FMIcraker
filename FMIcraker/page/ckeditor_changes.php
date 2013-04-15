<?php
if($post->isPosting() && $user->hasPriv("pages"))
{
    if($post['id'] == "home")
        $file = "/home";
    else
        $file = "/page/".Page::getStruct($post['id']);
    $fp = fopen("template/".$config['template'].$file.".php", "w");
    fwrite($fp, $post['data']);
    fclose($fp);
}
?>