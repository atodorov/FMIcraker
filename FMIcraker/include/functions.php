<?php
function autoload($class)
{
    include $_SERVER['DOCUMENT_ROOT'].SUBFOLDER.'/class/' . $class . '.php';
}
function randomStr($len = 20)
{
    return substr(hash("whirlpool", substr(uniqid(rand(), TRUE), 0, 10).time()), 45, $len);
}
function myHash($input, $len = 20)
{
    return substr(hash("whirlpool", $input), 0, $len);
}
function logError($info)
{
    global $db;
    $error_q = $db->prepare("INSERT INTO error_log (info,ip,`time`) VALUES (:info,:ip,:time)");
    $error_q->execute(array(':info' => $info, ':ip' => $_SERVER['REMOTE_ADDR'], ':time' => date("c")));
}
function logInfo($info)
{
    global $db;
    $error_q = $db->prepare("INSERT INTO info_log (info,ip,`time`) VALUES (:info,:ip,:time)");
    $error_q->execute(array(':info' => $info, ':ip' => $_SERVER['REMOTE_ADDR'], ':time' => date("c")));
}
function str_replace_first($search, $replace, $subject) {
    $pos = strpos($subject, $search);
    if ($pos !== false) {
        $subject = substr_replace($subject, $replace, $pos, strlen($search));
    }
    return $subject;
}
function endc( $array ) { return end( $array ); }
function validEmail($email)
{
if(preg_match("/^[_\.0-9a-zA-Z-]+@([0-9a-zA-Z][0-9a-zA-Z-]+\.)+[a-zA-Z]{2,6}$/i", $email))
    return true;
else
    return false;
}
?>