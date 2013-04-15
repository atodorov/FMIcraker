<form method="post">
<?=$lang['name']?> <input type="text" name="name"><br>
<?=$lang['seo_url']?> <input type="text" name="seo_url" data-jv="required seo_taken"><br>
<?=$lang['parent']?> <select name="parent">
<option value=""><?=htmlentities("<")?><?=$lang['none']?><?=htmlentities(">")?></option>
<?php foreach($parents as $parent){ ?>
    <option value="<?=$parent['id']?>"><?=$parent['url']?></option>
<?php } ?>
</select>
<textarea name="contents" id="contents"></textarea>
<input type="submit" value="<?=$lang['done']?>">
</form>
<script>
jv.seo_taken = function()
{
    var error = false;
    var check = $(jv_cur_check).val();
    if($("[name='parent'] option:selected").val())
        check = check+"/"+$("[name='parent'] option:selected").html();
    $("[name='parent']").children().each(function(){
        if(check == $(this).html())
            error = true;
    })
    if(error)
        return "<?=$lang['seo_url_taken']?>";
}
$("[name='parent']").change(function(){
    var backup_check = jv_cur_check;
    jv_check_field($("[name='seo_url']"));
    jv_cur_check = backup_check;
})
CKEDITOR.replace("contents");
</script>