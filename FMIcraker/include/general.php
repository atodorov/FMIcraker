<?php
include($_SERVER['DOCUMENT_ROOT'].SUBFOLDER."/include/functions.php");
spl_autoload_register(autoload);
$config = new Config();  
try
{
    $db = new PDO($config['db_driver'].':host='.$config['db_host'].';dbname='.$config['db'], $config['db_username'], $config['db_password']);
    $db->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
}
catch(Exception $e)
{
    echo $e->getMessage();
    return;
}
$config->getFrom($db);   
$lang = new Lang();
?>