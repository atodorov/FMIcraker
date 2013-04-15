<?php
class Lang implements arrayaccess
{
    private $lang = array();
    private $default_lang = array();
    private $config;
    private $db;
    function __construct()
    {
        global $config, $db;
        $this->config = $config;
        $this->db = $db;
        $this->lang = include("lang/".$config['language']."/main.php");
        if($config['language'] != $config['default_language'])
            $this->default_lang = include("lang/".$config['default_language']."/main.php");
    }
    public function loadPage($file)
    {
        global $config;
        if(file_exists("lang/".$config['language']."/page/".$file.".php"))
            $this->lang = array_merge($this->lang, include("lang/".$config['language']."/page/".$file.".php"));
        if($config['language'] != $config['default_language'] && file_exists("lang/".$config['default_language']."/page/".$file.".php"))
            $this->default_lang = array_merge($this->lang, include("lang/".$config['default_language']."/page/".$file.".php"));
    }
    public function getLang()
    {
        return $this->lang;
    }
    // Functions needed so object be used as array.
    public function offsetSet($offset, $value) 
    {
    }
    public function offsetExists($offset) 
    {
    }
    public function offsetUnset($offset) 
    {
    }
    public function offsetGet($offset) 
    {
        if(isset($this->lang[$offset]))
            return $this->lang[$offset];
        else
        {
            if(strpos($offset, "_") === false && $this->config['language'] == "english") // Code itself is written in english
                return $offset;
            logError("Key $offset requested for language ".$this->config['language']." but does not exist.");
            if($this->config['language'] != $this->config['default_language'])
            {
                if(isset($this->default_lang[$offset]))
                    return $this->default_lang[$offset];
                else
                {
                    logError("Key $offset requested for language ".$this->config['default_language']." but does not exist.");
                    return "";
                }
            }
        }
    }
}
?>