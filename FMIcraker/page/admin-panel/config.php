<?php
class Config_info{
    public $page_config = NULL;
    public function template()
    {
        $info = array();
        if ($dh = opendir("template")) {
            while (($file = readdir($dh)) !== false) {
                if(filetype("template/" . $file) == "dir" && $file != "." && $file != "..")
                    $info[] = $file;
            }
            closedir($dh);
        }
        return $info;
    }  
    public function language()
    {
        $info = array();
        if ($dh = opendir("lang")) {
            while (($file = readdir($dh)) !== false) {
                if(filetype("lang/" . $file) == "dir" && $file != "." && $file != "..")
                    $info[] = $file;
            }
            closedir($dh);
        }
        return $info;
    }
    public function default_language()
    {
        return self::language();
    }
    public function expire()
    {
        $days = (int)($this->page_config['value']/(60*60*24));
        $left = $this->page_config['value']%(60*60*24);
        $hours = (int)($left/(60*24));
        $left = $left%(60*24);
        $minutes = (int)($left/60);
        $seconds = (int)($left%60);
        return array("seconds" => $seconds, "minutes" => $minutes, "hours" => $hours, "days" => $days);
    } 
}
class Parse_config{
    public $page_config = NULL;
    public function site_name()
    {
        
    }  
    public function template()
    {
        
    } 
    public function maintenance()
    {
        
    }   
    public function language()
    {

    }
    public function site_url()
    {
        
    }
    public function protocol()
    {
        
    }
    public function http()
    {
        
    }
    public function default_language()
    {

    }
    public function debug()
    {
        
    }
    public function expire()
    {
        global $db;
        $db->config->update(array("name" => "expire"), array('$set' => array("value" => 
        $_POST['expire_seconds']+$_POST['expire_minutes']*60+$_POST['expire_hours']*60*60+
        $_POST['expire_hours']*60*60*24)));                                                                                 
        return true;
    }
    public function allow_registration()
    {

    }
    public function password_max_length()
    {

    }
    public function password_min_length()
    {

    } 
    public function username_max_length()
    {

    }
    public function username_min_length()
    {

    }               
}
if(sizeof($_POST))
{
    $new = array();
    $parse_config = new Parse_config();
    foreach($db->config->find() as $one_config)
    {
        if(!call_user_func(array($parse_config, $one_config['name'])) && !is_array($_POST[$one_config['name']]))
        {
            $db->config->update(array("name" => $one_config['name']), array('$set' => array("value" => $_POST[$one_config['name']])));
        }
    }
}
$config_assign = array();
$config_info = new Config_info();
foreach($db->config->find() as $one_config)
{
    if(method_exists('Config_info', $one_config['name']))
    {
        $config_info->page_config = $one_config;
        $one_config['info'] = call_user_method($one_config['name'], $config_info);
    }
    $config_lang = $db->config_lang->findOne(array("config_id" => $one_config['_id'], "language" => $config['language']));
    if(!$config_lang)
    {
        logError("For config ".$one_config['name']." and language ".$config['language']." nothing found in config_lang");
        if($config['language'] != $config['default_language'])
        {
            $config_lang = $db->config_lang->findOne(array("config_id" => $one_config['_id'], "language" => $config['default_language']));
            if(!$config_lang)
                logError("For config ".$one_config['name']." and language ".$config['default_language']." nothing found in config_lang");
        }
    }
    if($config_lang)
    {
        $one_config['comment'] = $config_lang['comment'];
        $one_config['lang_name'] = $config_lang['name'];
    }
    else
    {
        $one_config['comment'] = "";
        $one_config['lang_name'] = $one_config['name'];
    }
    $config_assign[] = $one_config;
}
$template->assign("page_configs", $config_assign);
?>