<?php
require_once $_SERVER['DOCUMENT_ROOT'] . "/admin/include/function.php";
require_once $_SERVER['DOCUMENT_ROOT'] . "/admin/include/protect.php";

// Définition des COOKIES 
// setcookie('nom','valeur','temps en seconde, ex : 30*24*60*60')

if (isset($_GET['reset']) && $_GET['reset'] == 1) {
      foreach($_COOKIE as $key=>$value){
            if(strpos($key,'search_')===0){
                  setcookie($key,"",time()-3600);
                  unset($_COOKIE[$key]);   
            }
      }
} else {
      foreach ($_POST as $key => $value) {
            setcookie('search_' . $key, $value, time() + (30 * 24 * 60 * 60));
      }
}


redirect("index.php");