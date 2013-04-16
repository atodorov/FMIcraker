<!DOCTYPE html>
<html>
<head>
<script type="text/javascript" src="http://code.jquery.com/jquery-1.8.3.min.js"></script>
<script type="text/javascript" src="/js/JV.js"></script>
<script src="/ckeditor/ckeditor.js"></script>
<script type="text/javascript" language="javascript" src="/datatables/media/js/jquery.dataTables.js"></script>
        <style type="text/css" title="currentStyle">
            @import "/datatables/media/css/demo_page.css";
            @import "/datatables/media/css/demo_table.css";
        </style>   
<title><?=$page_name?></title>
</head>
<body>
<?php if($user->isLoggedIn()){ ?>
<?=$lang['welcome']?>, <?=$user->username()?> (<a href="/logout"><?=$lang['logout']?></a>) | <a href="/profile"><?=$lang['profile']?></a><?php if($user->isAdmin()){ ?> | <a href="/admin-panel"><?=$lang['admin_panel']?></a><?php } ?>
<?php }else{ ?>
<a href="<?=$site_url?>/login"><?=$lang['login']?></a><?php if($config['allow_registration']) {?> | <a href="<?=$site_url?>/register"><?=$lang['register']?></a><?php } ?>
<?php } ?><br>
<div id="body"<?php if($user->hasPriv("pages") && $page_editable){?> contenteditable="true"<?php } ?>>