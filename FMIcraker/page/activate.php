<?php
$acc_r = $db->account->findOne(array("seo_username" => $page->param(0)));
$error = "";
$success = "";
if(!$acc_r || $acc_r['activation_key'] != $page->param(1))
    $error = $lang['error_incorrect_activation_link'];
else if(isset($acc_r['activated']))
    $error = $lang['error_account_activated'];
else
{
    $db->account->update(array("_id" => $acc_r['_id']), array('$set' => array('activated' => true)));
    $success = $lang['account_now_active'];
}
$template->assign("error", $error);
$template->assign("success", $success);
?>