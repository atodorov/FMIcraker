<?php
session_start();
include("include/define.php");
include("include/general.php");
$post = new Post();
$user = new User();
$page = new Page();
$template = new Template();
$template->compile = true;
foreach($page->getScripts() as $script)
    include("script/".$script.".php");
$lang->loadPage($page->file());
// Include php script for page if exists
if(file_exists("page/".$page->file().".php"))
    include("page/".$page->file().".php");
$template->assign("page_id", $page->Id());
$template->assign("user",$user);
$template->assign("post",$post);
$template->assign("lang",$lang);
$template->assign("config",$config);
$template->assign("site_link",$config['protocol']."://".$config['site_url']);
if($page->wrapper() === NULL || $page->wrapper())
{
    $template->assign("page_name",$config['site_name']." - ".$page->name());
    $template->header($config['template']."/".($page->wrapper() ? $page->wrapper()."_" : "")."header.php");
    $template->footer($config['template']."/".($page->wrapper() ? $page->wrapper()."_" : "")."footer.php");
    $template->show($config['template']."/".($page->inner() ? "" : "page/").$page->file().".php");
}
?>
