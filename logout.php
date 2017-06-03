<?php


require_once('session.php');
require_once('dbconfig.php');

if ($userlogin->is_loggedin() != "") {
    $userlogin->redirect('admin_page.php');
}
if (isset($_GET['logout']) && $_GET['logout'] == "true") {
    $userlogin->logout();
    $userlogin->redirect('index.php');
}

