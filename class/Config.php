<?php
class Config implements arrayaccess
{
    private static $defaults = array("db" => "tttcloud_FMIcraker",
                                     "db_username" => "tttcloud",
                                     "db_password" => "3w8f,+E6KbY~",
                                     "db_driver" => "mysql");
    private $configs = array();
    function __construct()
    {
        foreach(self::$defaults as $key => $name)
            $this->configs[$key] = $name;
    }
    public function getFrom($db)
    {
        $configs_q = $db->prepare("SELECT * FROM config");
        $configs_q->execute();
        while($config = $configs_q->fetch())
            $this->configs[$config['name']] = $config['value'];
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
        return isset($this->configs[$offset]) ? $this->configs[$offset] : null;
    }
}
?>