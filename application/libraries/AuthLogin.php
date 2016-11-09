<?php defined('BASEPATH') or exit('No direct script access allowed');

class AuthLogin
{
   public function checkAuthUser($username,$password)
   {
       $ci =& get_instance();
	   $credentials_array=$ci->config->item('rest_valid_logins');
       $key=key($credentials_array);
	   $default_username=$key;
       $default_password=$credentials_array[$key];     	   
	   
	   //echo $default_username." & ".$default_password;exit;
	   //echo $username." & ".$password;exit;
	   if($username!="" && $password!="" && $username==$default_username && $default_password==$password)
	   {
	      return true; 
	   }
	   else
	   {
	     return false;
	   }
	   
   
   }
    
}
