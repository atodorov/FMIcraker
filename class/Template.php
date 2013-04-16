<?php
class Template
{
    private $vars = array();
    private $header = "";
    private $footer = "";
    function __construct()
    {
        
    }
    public function assign($key, $value)
    {
        $this->vars[$key] = $value;
    }
    public function header($path)
    {
        $this->header = $path;
    }
    public function footer($path)
    {
        $this->footer = $path;
    }
    public function show($path)
    {
        global $config;
        foreach($this->vars as $_key => $_var)
            $$_key = $_var;
            
        if($this->header)
            include("template/".$this->header);
            
        include("template/".$path);
        if($this->footer)
            include("template/".$this->footer);
    }
}
?>