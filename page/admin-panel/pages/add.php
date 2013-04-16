<?php
if($post->isPosting())
{
    Page::create();
}
$pages = array();
foreach($db->page->find() as $one_page)
{  
    $pages[] = array("id" => $one_page['_id'], "url" => Page::getStruct($one_page['_id']));
}
$template->assign("parents", $pages);
?>