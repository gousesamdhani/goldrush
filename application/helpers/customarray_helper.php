<?php

	/**
	    * search for value in multidimensional array and return  
	    * @param $array array 
	    * @param $key string
	    * @param $value string 
	    * @return $result array which consists of lifetime savings  
	*/
    function searchMultiArray($array,$key,$value)
    {
         if(count($array)>0)
         {
            foreach($array as $a)
            {
               if($a[$key]==$value)
               return $a;  
            }   
         }  
         return false;
    }


?>