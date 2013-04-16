<?php
if($post->isPosting())
{
    if(is_numeric($page->param(0)))
    {
        Page::delete((int)$page->param(0));
        Page::create();
    }
    else
    {
        $fp = fopen("template/".$config['template'].'/'.$page->param(0).'.php', 'w');
        fwrite($fp, $post['contents']);
        fclose($fp);
    }
}
if(is_numeric($page->param(0)))
{
    $row = $db->page->findOne(array("from_ckeditor" => (int)$page->param(0)));
    $page_name = $db->page_name->findOne(array("page_id" => $row['_id']));
    $content = file_get_contents("template/".$config['template']."/page/".Page::getStruct($row['_id']).'.php', 'w');  
    $page->setNameParam($page_name['name']);
    $parents = array();
    $parents_q = $db->page->find();
    foreach($parents_q as $parent)
        $parents[] = array("id" => $parent['_id']->Id, "url" => Page::getStruct($parent['_id']));
    $template->assign("parents", $parents);
    $template->assign("name", $page_name['name']);
    $template->assign("seo_url", $row['file']);
    $template->assign("selected_id", $row['_id']->Id);
    $template->assign("content", $content);
}
else
{
    $content = file_get_contents("template/".$config['template']."/".$page->param(0).".php", 'w');
    $page->name($lang['edit_home_page']);
    $template->assign("is_home", true); 
    $template->assign("content", $content);
}
?>