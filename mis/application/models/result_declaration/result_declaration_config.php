<?php
/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
*/


class Result_declaration_config{ 
   static $DRP_incl=true;
   static $AB_incl=true;
   static $DEF_incl=true;
   static $DEB_incl=true;
   static $UFM_incl=true;   
   static $ogpa_show_final_yr=true;
   static $ogpa_show_final_yr_effective_from='2015-2016';
   static  $opaeration_access=true;
   static  $grade_published_on=true;// true if publish date require else false
   
   // getting studentds whose redeclaration gone wrong for monsoon/winter 18-19 thus ogpa will be calaculated  based on based_on_publish_date,however students not falls on this category will be  based on actual published_on
static $hons_wrong_redec_list=array(     
'14JE000748','15JE001229','15JE001404','16JE001929','16JE001940','16JE001950','16JE001972','16JE001981','16JE001987','16JE002025','16JE002026','16JE002030','16JE002038','16JE002101','16JE002104','16JE002122','16JE002155','16JE002174','16JE002182','16JE002266','16JE002298','16JE002339','16JE002410','16JE002413','16JE002415','16JE002420','16JE002447','16JE002460','16JE002461','16JE002480','16JE002511','16JE002514','16JE002515','16JE002521','16JE002534','16JE002539','16JE002552','16JE002565','16JE002631','16JE002708','16JE002714','2013JE0013');


static  $exception_degree_conversion= array('14JE000786','15JE001310')/* array('admn_no'=>'14JE000786','nbranch'=>'Mineral Engineering','ncrs'=>'Bachelor Of Technology')*/;
static $exception_sem_session_mapping= array('14je000505');

static $result_dec_override=false;



/*function  Result_declaration_config($config=null) {		
         foreach($config as $key => $value) 
          $prr[$key]=self::$$key = $value;       										
	  return $prr;
    }*/  
   public static function inti($config=null){
	   foreach($config as $key => $value) 
              $prr[$key]=self::$$key = $value;       
	  return $prr;
   }

   public function  effctive_from_init($session_yr){
	 self::$ogpa_show_final_yr= $session_yr>=self::$ogpa_show_final_yr_effective_from?false:true;   
   }
   
   
   
   }