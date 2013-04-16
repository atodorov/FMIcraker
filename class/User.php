<?php
class User
{
    function __construct()
    {
        global $db;
        if(isset($_COOKIE['remember']))
        {
            $remember_r = $db->login_remember->findOne(array("token" => $_COOKIE['remember']));
            if($remember_r)
                $this->setLoginInfo($db->user->find(array("_id" => $remember_r['logged_id'])));
        }
    }
    public function hasPriv($priv)
    {
        return isset($_SESSION['login_info']) && isset($_SESSION['login_info']['privs']) &&  in_array($priv, $_SESSION['login_info']['privs']);
    }
    public function isAdmin()
    {
        return $this->hasPriv("admin");
    }
    public function username()
    {
        return $_SESSION['login_info']['username'];
    }
    public function isLoggedIn()
    {
        return isset($_SESSION['login_info']);
    }
    public function setLoginInfo($query)
    {
        $_SESSION['login_info'] = array("logged_id" => $query['_id'], "username" => $query['username'], "privilege" => $query['privilege'], "privs" => isset($query['privs']) ? $query['privs'] : array());
    }
    public function getLoginInfo()
    {
        return $_SESSION['login_info'];
    }
    public function tryRegister()
    {
        global $post, $config, $securimage, $lang, $db;
        if($post->isPosting())
        {
            if(strlen($post['username']) < $config['username_min_length'] || strlen($post['username']) > $config['username_max_length'] || !validEmail($post['email']) || $post['password1'] != $post['password2'] || strlen($post['password1']) < $config['password_min_length'] || strlen($post['password1']) > $config['password_max_length'] || !$securimage->check($post['captcha']) || self::isEmailTaken($post['email']) || self::isUsernameTaken($post['username']) || strpos($post['username'], "/") !== false || strpos($post['username'], "#") !== false)
                return $lang['processing_error'];
            else
            {
                $pass_salt = randomStr();
                $activation_key = randomStr();
                $pass_hash = myHash($post['password1'].$pass_salt);
                $seo_username = $this->seoUsername($post['username']);
                $db->account->insert(array("username" => $post['username'], "email" => $post['email'], "pass_hash" => $pass_hash, "pass_salt" => $pass_salt, "activation_key" => $activation_key, "seo_username" => $seo_username));
                mail($post['email'],$config['site_name']." - ".$lang['activation_link'], $lang['activation_explain'].": ".$config['protocol']."://".$config['site_url'].'/activate/'.$seo_username.'/'.$activation_key, 'From: '.$config['webmaster_email']);
                $securimage->delCode();
                return true;
            }
        }
        return false;
    }
    public function seoUsername($username)
    {
        global $db;
        $seo_username = str_replace(" ", "_", $username);
        for($i = 1;;++$i)
        {
            $availible_name = $seo_username.($i == 1 ? "" : $i);
            if(!$db->account->findOne(array("seo_username" => $availible_name)))
                return $availible_name;
        }
    }
    public function tryLogin($username, $password, $remember)
    {
        global $post, $lang, $config, $db;
        $acc_r = $db->account->findOne(array("username" => $username));
        if(!$acc_r || myHash($password.$acc_r['pass_salt']) != $acc_r['pass_hash'])
            return $lang['login_failed'];
        $this->setLoginInfo($acc_r);
        if($remember)
        {
            $token = randomStr();
            setcookie("remember", $token, time() + $config['remember_expire']);
            $db->login_remember->insert(array("token" => $token, "logged_id" => $acc_r['_id'], "expire" => time() + $config['remember_expire']));
        }
        return true;
    }
    public static function isUsernameTaken($username)
    {
        global $db;
        if($db->account->findOne(array("username" => new MongoRegex("/".$username."/i"))))
            return true;
        return false;
    }
    public static function isEmailTaken($email)
    {
        global $db;
        if($db->account->findOne(array("email" => new MongoRegex("/".$email."/i"))))
            return true;
        return false;
    }
    public function loggedId()
    {
        return isset($_SESSION['login_info']) ? $_SESSION['login_info']['logged_id'] : 0;
    }
    public function logout()
    {
        unset($_SESSION['login_info']);
        if(isset($_COOKIE['remember']))
            setcookie("remember", "", 1);
    }
}
?>