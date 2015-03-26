<?php


function posse_logout(){
    $userinfo = get_currentuserinfo();

    if($userinfo){
    }
    // log out symfony user
//    Posse::logoutAll();
//    wp_redirect(home_url());
//    die();
}