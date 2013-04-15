<?php
if($post->isPosting())
{
    Page::delete((int)$post['del_id']);
}
?>