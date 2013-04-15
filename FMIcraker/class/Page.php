<?php
class Page
{
    private $page;
    private $inner;
    private $name;
    private $file;
    private $breadcrumbs = array();
    private $scripts = array();
    private $page_id;
    private $params = array();
    private $path_arr = array();
    private $wrapper = NULL;
    private $from_ckeditor = false;
    function __construct()
    {
        global $db, $config, $lang;
        if($config['maintenance'])
        {
            $this->page("maintenance", true);
            return;
        }

        $request = $_SERVER['REQUEST_URI'];
        if($request[strlen($request)-1] == "/") //Remove "/" at end if exists
            $request = substr($request,0,-1);
        if(!$request)
        {
            $this->page("home", true);
            $this->page_id = "home";
            return;
        }
        $request = substr($request,1);
        $request_arr = explode("?", $request);
        $request = $request_arr[0];
        $request_arr = explode("/", $request);
        $this->page = "";
        $parent = 0;
        $page = NULL;
        foreach($request_arr as $key => $request_element)
        {
            $pages_q = $db->prepare("SELECT * FROM page WHERE file=:element AND parent=:parent");
            $pages_q->execute(array(":element" => $request_element, ":parent" => $parent));
            $page_r = $pages_q->fetch();
            if($page_r)
            {
                $this->path_arr[] = $page_r['file'];
                if(isset($page_r['script']))
                    $this->scripts[] = $page_r['script'];
                $this->file .= $request_element."/";
                $parent = $page_r['_id'];
                $page = $page_r;
                $this->breadcrumbs[] = array("id" => $page_r['_id'], "url" => $this->page);
                $request = str_replace_first($request_element, '', $request);
                unset($request_arr[$key]);
            }
            else
                break;
        }
        $this->file = substr($this->file,0,-1);
        if($page)
        {
            $this->page_id = $page['_id'];
            if($request_arr)
                $this->params = array_combine(range(0, count($request_arr)-1), array_values($request_arr));
            if(isset($page['wrapper']))
                $this->wrapper($page['wrapper']);
            if($this->wrapper() === NULL || $this->wrapper())
            {
                $name_r = $db->page_name->findOne(array("language" => $config['language'], "page_id" => $page['_id']));
                if(!$name_r)
                {
                    logError("Page name (id: ".$page['_id'].") for language ".$config['language']." does not exist.");
                    if($config['language'] != $config['default_language'])
                    {
                        $name_r = $this->db->page_name->findOne(array("language" => $config['default_language'], "page_id" => $page['_id']));
                        if(!$name_r)
                            logError("Page name (id: ".$page['_id'].") for language ".$config['default_language']." does not exist.");
                    }
                }
                $this->name = $name_r ? $name_r['name'] : "";
                if(isset($page['from_ckeditor']))
                    $this->from_ckeditor = true;
            }
        }
        else
            $this->page("not_found", true);
    }
    public static function getStruct($id)
    {
        global $db;
        if(!($id instanceof MongoId))
            $id = new MongoId($id);
        $struct = "";
        $first = true;
        while($id)
        {
            $parent_page = $db->page->findOne(array("_id" => $id));
            $id = isset($parent_page['parent']) ? $parent_page['parent'] : 0;
            if($first)
                $first = false;
            else
                $struct = "/".$struct;
            $struct = $parent_page['file'].$struct;
        }
        return $struct;
    }
    public function Id()
    {
        return $this->page_id;
    }
    public function getPathArr()
    {
        return $this->path_arr;
    }
    public function getScripts()
    {
        return $this->scripts;
    }
    public function wrapper($new = NULL)
    {
        if($new === NULL)
            return $this->wrapper;
        $this->wrapper = $new;
    }
    public function file()
    {
        return $this->file;
    }
    public function param($key)
    {
        return isset($this->params[$key]) ? $this->params[$key] : "";
    }
    public function setNameParam($value)
    {
        $this->name = str_replace_first('$', $value, $this->name);
    }
    public function page($new = NULL, $inner = false)
    {
        global $lang, $db, $config;
        if($new === NULL)
            return $this->page;
        if($inner)
        {
            $page_q = $db->prepare("SELECT * FROM page WHERE file=:file");
            $page_q->execute(array(":file" => $new));
            $page_r = $page_q->fetch();
            $name_q = $db->prepare("SELECT * FROM page_name WHERE language=:language AND page_id = :page_id");
            $name_q->execute(array(":language" => $config['language'], ":page_id" => $page_r['id']));
            $name_r = $name_q->fetch();
            if(!$name_r)
            {
                logError("Inner page ".$new." for language ".$config['language']." does not exist.");
                if($config['language'] != $config['default_language'])
                {
                    $name_r = $this->db->page_name->findOne(array("language" => $config['default_language'], "page_id" => $page_r['_id'], "inner" => true));
                    if(!$name_r)
                        logError("Inner page ".$new." for language ".$config['default_language']." does not exist.");
                }
            }
            $this->name = $name_r ? $name_r['name'] : "";
        }
        $this->file = $this->page = $new;
        $this->inner = $inner;
    }
    public function inner($new = NULL)
    {
        if($new === NULL)
            return $this->page;
        $this->inner = $new;
    }
    public function getBreadcrumbs()
    {
        return sizeof($this->breadcrumbs) ? $this->breadcrumbs : NULL;
    }
    public function name($new = NULL)
    {
        if($new !== NULL)
            $this->name = $new;
        else
            return $this->name;
    }
}
?>