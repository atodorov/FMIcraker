<script>
$(document).ready(function() {
    $('#tos').click(function() {
        if($(this).next().attr("id") == "jv_error")
            $(this).next().remove();
        if(!$(this).is(":checked"))
            $(this).after(jv_err_open+'<?=$lang['agree_to_tos']?>'+jv_err_close);
    });
    $('form').submit(function(event) {
        if(!$("#tos").is(":checked"))
        {
            if($("#tos").next().attr("id") == "jv_error")
                $("#tos").next().remove();
            $("#tos").after(jv_err_open+'<?=$lang['agree_to_tos']?>'+jv_err_close);
            event.preventDefault();
        }   
    })
});
jv.pass_match = function()
{
    if($('#pass_missmatch').children("#jv_error"))
        $('#pass_missmatch').children().remove();
    if($('input[name="password1"]').val() != $('input[name="password2"]').val())
        $('#pass_missmatch').html(jv_err_open+'<?=$lang['pass_missmatch_error']?>'+jv_err_close);
};
jv.username_chars = function()
{
    if($(jv_cur_check).val() && ($(jv_cur_check).val().indexOf("/") != -1 || $(jv_cur_check).val().indexOf("#") != -1))
        return "<?=$lang['username_chars_error']?>";
}
function reloadImage()
{
    document.getElementById('siimage').src = '<?=$site_link?>/securimage/securimage_show.php?sid=' + Math.random(); this.blur();
    if($('input[name="captcha"]').next().attr("id") == "jv_error")
        $('input[name="captcha"]').next().remove();
    $('input[name="captcha"]').after(jv_err_open+'<?=$lang['wrong_captcha']?>'+jv_err_close);
    return false;
}
</script>
<?php if($error){?>
<p><?=$error?></p>
<?php } 
if($success){?>
<p><?=$lang['activation_sent']?>: <?=$email?></p>
<?php }else{?>
<form method="post">
<?=$lang['email']?>
<input type="text" data-jv="required email file(valid_email_q)" name="email"><br>
<?=$lang['username']?>
<input type="text" data-jv="required no_trim username_chars min_length(<?=$config['username_min_length']?>) max_length(<?=$config['username_max_length']?>) file(valid_username_q)" name="username"><br>
<div id="pass_missmatch"></div>
<?=$lang['password']?>
<input type="password" data-jv="required min_length(<?=$config['password_min_length']?>) max_length(<?=$config['password_max_length']?>) no_trim pass_match" name="password1"><br>
<?=$lang['confirm_password']?>
<input type="password" data-jv="required pass_match" name="password2"><br>
  <p>
    <img id="siimage" style="border: 1px solid #000; margin-right: 15px" src="/securimage/securimage_show.php?sid=<?php echo md5(uniqid()) ?>" alt="CAPTCHA Image" align="left" />
    <object type="application/x-shockwave-flash" data="/securimage/securimage_play.swf?bgcol=#ffffff&amp;icon_file=/securimage/images/audio_icon.png&amp;audio_file=/securimage/securimage_play.php" height="32" width="32">
    <param name="movie" value="/securimage/securimage_play.swf?bgcol=#ffffff&amp;icon_file=/securimage/images/audio_icon.png&amp;audio_file=/securimage/securimage_play.php" />
    </object>
    <a style="padding-left:15px" tabindex="-1" style="border-style: none;" title="Refresh Image" onclick="reloadImage()"><img src="/securimage/images/refresh.png" alt="Reload Image" height="32" width="32" onclick="this.blur()" align="bottom" border="0" /></a><br />
    <strong><?=$lang['verification_code']?></strong><br />
    <input type="text" autocomplete="off" name="captcha" data-jv="required file(captcha)" size="12" maxlength="8" />
  </p>
    <?=$lang['i_agree_to_the']?> <a href='/tos'><?=$lang['terms_and_conditions']?></a>.<input type="checkbox" autocomplete="off" name="tos" id="tos"/><br>
<input type="submit" value="<?=$lang['register']?>">
</form>
<?php } ?>