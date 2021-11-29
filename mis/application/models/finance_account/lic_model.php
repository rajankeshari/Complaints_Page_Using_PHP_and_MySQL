<?php

/**
 * Author: Anuj
*/
class Lic_model extends CI_Model {
    
    

    function __construct() {
        parent::__construct();
    }

   function get_lic_account(){
    $myquery="select  al.EMPNO,concat(ud.salutation,' ',ud.first_name,' ',ud.middle_name,' ',ud.last_name) as name, (select dpt.name from departments dpt where dpt.id=ud.dept_id) as dept, al.POLICY_NO,al.PREMIUM_AMOUNT,al.ACC_STATUS from acc_lic_account al left join user_details ud on al.EMPNO=ud.id order by al.ACC_STATUS";
    $query=$this->db->query($myquery);
    if($query->num_rows()>0){
        return $query->result();
    }
    else{
        return false;
    }
   }

   function delete_lic_account($cond){
       $this->db->where($cond);
       if($this->db->delete('acc_lic_account')){
        return true;
       }
       else{
        return false;
       }
   }

   function get_individual_lic_details($empno){
    $myquery="select al.EMPNO,al.POLICY_NO,al.PREMIUM_AMOUNT,al.ACC_STATUS from acc_lic_account al where al.EMPNO=? ";
    $query=$this->db->query($myquery,$empno);
    if($query->num_rows()>0){
        return $query->result();
    }
    else{
        return false;
    }
   }

   function check_status($cond){
    $this->db->where($cond);
    $query=$this->db->get('acc_lic_account');
    if($query->num_rows()>0){
        return true;
    }
    else{
        return false;
    }
   }

   function change_acc_status($cond,$set){
    $this->db->set($set);
    $this->db->where($cond);
    $this->db->update('acc_lic_account');
   }
   function insert_lic_account($data){
        $this->db->insert('acc_lic_account',$data);
       // echo $this->db->last_query();die();
   }

   function generate_current_month_lic_details(){
    if($this->delete_from_acc_lic_premium_details_temp()){
       
        if($this->inser_into_acc_lic_premium_details_temp()){
            return true;
        }
        else{
            return false;
        }
    }
    else{
         echo $this->db->last_query();
        return false;
    }
   }

   function delete_from_acc_lic_premium_details_temp(){
    $myquery="delete from acc_lic_premium_details_temp";
    if($this->db->query($myquery)){
        return true;
    }
    else{

        return false;
    }
   }

   function inser_into_acc_lic_premium_details_temp(){
     //echo"Hello";die();
     $arr=$this->getMonthYear();
     $myquery="insert into acc_lic_premium_details_temp (EMPNO,POLICY_NO,PREMIUM_AMOUNT)  select al.EMPNO,al.POLICY_NO,ceil(al.PREMIUM_AMOUNT) from acc_lic_account al where al.ACC_STATUS='A'";
     if($this->db->query($myquery)){
        $mon_yr=array('MON'=>$arr[0]->MON,'YR'=>$arr[0]->YR);
        $this->db->set($mon_yr);
        $this->db->update('acc_lic_premium_details_temp');
        return true;
     }
     else{
        return false;
     }
   }
   function getMonthYear(){
    $myquery="select (select distinct(MON)  from acc_pay_details_temp ) as MON,(select distinct(YR) from acc_pay_details_temp) as YR  from acc_pay_details_temp limit 1";
    $query=$this->db->query($myquery);
    //echo $this->db->last_query();die();
    if($query->num_rows()>0){
        return $query->result();
    }
    else{
        return false;
    }
   }

   function getCurretnMonthLicDetails(){
    $myquery="select al.*, concat(ud.salutation,' ',ud.first_name,' ',ud.middle_name,' ',ud.last_name) as name, (select dpt.name from departments dpt where dpt.id=ud.dept_id) as dept from acc_lic_premium_details_temp al left join user_details ud on ud.id=al.EMPNO";
    if($query=$this->db->query($myquery)){

        if($query->num_rows()>0){
            return $query->result();
        }
        else{
            return false;
        }
    }
   }

   function getCurrentMonthLicDetailsToUpdate(){
    $myquery="select distinct(al.EMPNO), al.MON,al.YR, concat(ud.salutation,' ',ud.first_name,' ',ud.middle_name,' ',ud.last_name) as name, (select  ceil(sum(ala.PREMIUM_AMOUNT)) from acc_lic_account ala where ala.EMPNO=al.EMPNO AND ala.ACC_STATUS='A') AS AMOUNT,(select dpt.name from departments dpt where dpt.id=ud.dept_id) as dept,(select group_concat(al1.POLICY_NO) from acc_lic_premium_details_temp al1 where al1.EMPNO=al.EMPNO) as POLICY_NO from  acc_lic_premium_details_temp al left join user_details ud on ud.id=al.EMPNO ";
    if($query=$this->db->query($myquery)){
        if($query->num_rows()>0){
            return $query->result();
        }
        else{
            return false;
        }
    }
    else{
        return false;
    }
   }

   function merge_lic(){
    //echo "hello";die();
    $myquery="select distinct(ald.EMPNO),(select sum(ald1.PREMIUM_AMOUNT) from acc_lic_premium_details_temp ald1 where ald.EMPNO=ald1.EMPNO ) as LIC from acc_lic_premium_details_temp ald";
    $query=$this->db->query($myquery);
    //echo $this->db->last_query();
    if($query->num_rows()>0){
        $result=$query->result();
        foreach($result as $r){
           $this->db->set('LIC',$r->LIC);
           $this->db->where('EMPNO',$r->EMPNO);
           $this->db->update('acc_pay_details_temp');
           //echo $this->db->last_query();
        }
         $arr=$this->getMonthYear();
         $myquery="select count(*) from acc_lic_premium_details al where al.MON=? and al.YR=?";
         $query=$this->db->query($myquery,array($arr[0]->MON,$arr[0]->YR));
         if($query->num_rows()>0){
            $cond=array('MON'=>$arr[0]->MON,'YR'=>$arr[0]->YR);
            $this->db->where($cond);
            if($this->db->delete('acc_lic_premium_details')){
                $myquery="insert into acc_lic_premium_details (MON,YR,EMPNO,POLICY_NO,PREMIUM_AMOUNT) SELECT alt.MON,alt.YR,alt.EMPNO,alt.POLICY_NO,alt.PREMIUM_AMOUNT FROM acc_lic_premium_details_temp alt";
                if($this->db->query($myquery)){
                    return true;
                }
                else{
                    return false;
                }
            }
         }
    }
    else{
        return false;
    }
   }

   function getMonForQuery(){
    $myquery="select distinct(MON) from acc_lic_premium_details order by MON";
    if($query=$this->db->query($myquery)){
        if($query->num_rows()>0){
            return $query->result();
        }
        else{
            return false;
        }
    }
   }

   function getYrForQuery(){
    $myquery="select distinct(YR) from acc_lic_premium_details order by YR";
    if($query=$this->db->query($myquery)){
        if($query->num_rows()>0){
            return $query->result();
        }
        else{
            return false;
        }
    }
   }


   function getReport($arr){
    $q="select concat(ud.salutation,' ',ud.first_name,' ',ud.middle_name,' ',ud.last_name) as name,al.*,(select dpt.name from departments dpt where dpt.id=ud.dept_id) as dept from acc_lic_premium_details al left join user_details ud on ud.id=al.EMPNO where 1=1";
    foreach ($arr as $key => $value) {
        $q.=" and ".$key."=".$value;
    }
    $query=$this->db->query($q);
    //echo $this->db->last_query();
    if($query->num_rows()>0){
        return $query->result();
    }
    else{
        return false;
    }
   }

   function updateAccount($cond,$set){
    $this->db->where($cond);
    $this->db->set($set);
    if($this->db->update('acc_lic_account')){
      return true;
    }
    else{
      return false;
    }
   }

    
}