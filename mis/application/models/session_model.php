<?php

class Session_model extends CI_Model
{
       
    function getSession(){
        if(date('m') < '07' && date('m') > '01' ){
            $y= (date('Y')-1)."-".date("Y");
            return $y;
        }
           $y= date('Y')."-".(date("Y")+1);
         //   return $y;
        return "2014-2015";
    }
}
?>

