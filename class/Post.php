<?php
class Post implements arrayaccess
{
    public function isPosting()
    {
        return sizeof($_POST) != 0;
    }
    public function size()
    {
        return sizeof($_POST);
    }
    // Functions needed so object be used as array.
    public function offsetSet($offset, $value) 
    {
    }
    public function offsetExists($offset) 
    {
        return isset($this->vars[$offset]);
    }
    public function offsetUnset($offset) 
    {
    }
    public function offsetGet($offset) 
    {
        // Arrays are dangerous for MongoDB, use $_POST array to get those.
        if(isset($_POST[$offset]) && !is_array($_POST[$offset]))
            return $_POST[$offset];
        return "";
    }
}
?>