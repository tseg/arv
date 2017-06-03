<?php

session_start();

require_once 'dbconfig.php';


// if user session is not active(not loggedin) this page will help 'home.php and profile.php' to redirect to login page
// put this file within secured pages that users (users can't access without login)

if (!$userlogin->is_loggedin()) {
    // session no set redirects to login page
    $user->redirect('index.php');
}

	
        
