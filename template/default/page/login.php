<?php if($login_error) {?> <?=$login_error?><br> <?php } ?>
<form method="post">
<?=$lang['username']?>: <input name="username" type="text" data-jv="required"><br>
<?=$lang['password']?>: <input name="password" type="password" data-jv="required"><br>
<?=$lang['remember_me']?>: <input name="remember_me" type="checkbox"><br>
<input type="submit" value="<?=$lang['login']?>">
</form>