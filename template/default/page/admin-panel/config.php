<form method="post"><?php                                                          
class Config_template{
    public $page_config = NULL;
    public function template()
    {
        ?><select name="template"><?php
        foreach($this->page_config['info'] as $template){
            ?><option value="<?=$template?>"<?php if($this->page_config['value'] == $template) echo " selected"?>><?=$template?></option><?php
        }
        ?></select><?php
    }
    public function maintenance()
    {
        ?><input type="checkbox" name="maintenance" <?php if($this->page_config['value']) echo "checked"?>><?php
    }
    public function language()
    {
        ?><select name="language"><?php
        foreach($this->page_config['info'] as $lang){
            ?><option value="<?=$lang?>"<?php if($this->page_config['value'] == $lang) echo " selected"?>><?=$lang?></option><?php
        }
        ?></select><?php
    }
    public function default_language()
    {
        ?><select name="default_language"><?php
        foreach($this->page_config['info'] as $lang){
            ?><option value="<?=$lang?>"<?php if($this->page_config['value'] == $lang) echo " selected"?>><?=$lang?></option><?php
        }
        ?></select><?php
    }
    public function debug()
    {
          ?><input type="checkbox" name="debug"<?php if($this->page_config['value']) echo "checked"?>><?php
    }
    public function expire()
    {
        global $lang;?>
        <?=$lang['seconds']?><input name="expire_seconds" data-jv="uint max_val(59)" value="<?=$this->page_config['info']['seconds']?>">
        <?=$lang['minutes']?><input name="expire_minutes" data-jv="uint max_val(59)" value="<?=$this->page_config['info']['minutes']?>">
        <?=$lang['hours']?><input name="expire_hours" data-jv="uint max_val(23)" value="<?=$this->page_config['info']['hours']?>">
        <?=$lang['days']?><input name="expire_days" data-jv="uint"  value="<?=$this->page_config['info']['days']?>"><?php
    }
    public function allow_registration()
    {
        ?><input type="checkbox" name="allow_registration"<?php if($this->page_config['value']) echo "checked"?>><?php    
    }    
}
$config_template = new Config_template();
foreach($page_configs as $page_config){
    $config_template->page_config = $page_config?>
    <?=$page_config['lang_name']?> <?php if(method_exists('Config_template',$page_config['name'])){
    call_user_method($page_config['name'],$config_template);
}else{ ?>
<input type="text" name="<?=$page_config['name']?>" value="<?=$page_config['value']?>"<?php
if($page_config['jv'])
{
    echo " data-jv='";
    $first = true;
    foreach($page_config['jv'] as $one_jv)
    {
        if($first)
            $first = false;   
        else
            echo " ";
        echo $one_jv;
    }
    echo "'";
}
?>>
<?php } ?> (<?=$page_config['comment']?>)
<br>
<?php }
?> <input type="submit" value="<?=$lang['done']?>"></form>