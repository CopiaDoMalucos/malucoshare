<?php
############################################################
#######                                             ########
#######                                             ########
#######           www.brshares.com 2.0              ########
#######                                             ########
#######                                             ########
############################################################
 
 # For Security Purposes.
 if ( $_SERVER['PHP_SELF'] != $_SERVER['REQUEST_URI'] ) die; 
 
 require_once("backend/functions.php");
 dbconn();
 
 logoutcookie();
 header("Location: index.php");
?>