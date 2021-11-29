<?php

class Drop_student_model extends CI_Model {

    function __construct() {
        parent::__construct();
    }

        
    function add_drop_student_details($data){
         if($this->db->insert('drop_student_status',$data))
            return $this->db->insert_id();
        else
            return FALSE;
    }

    function check_exists($id){
        $sql="select * from drop_student_status a where a.admn_no=?";
        $query = $this->db->query($sql,array($id));
        if ($this->db->affected_rows() > 0) {
            return $query->row();
        } else {
            return false;
        }
    }
    function get_all_drop_tables(){
              $sql="SELECT table_name FROM INFORMATION_SCHEMA.tables  WHERE table_schema='mis_40_50' and TABLE_NAME LIKE 'drop%'";
              $query = $this->db->query($sql);

              if ($this->db->affected_rows() > 0) {
                    return $query->result();
                } else {
                    return false;
                }
         
         }

         //===================================================================================================================================================

         function move_to_drop($tbl_name,$admn_no)   {
            
            $main_tbl=substr($tbl_name, strpos($tbl_name, "_") + 1);   
            
            if($tbl_name=='drop_user_address' || $tbl_name=='drop_user_details' || $tbl_name=='drop_user_other_details' || $tbl_name=='drop_change_password_log' || $tbl_name=='drop_users'){
                $test_con="id";
            }
            else if($tbl_name=='drop_tabulation1'){
                $test_con="adm_no";
            }
            else if($tbl_name=='drop_complaint' || $tbl_name=='drop_swimming_medical_report' || $tbl_name=='drop_swimming_student_details' ){
                $test_con="user_id";
            }
            else if($tbl_name=='drop_user_notifications' ){
                $test_con="user_to";
            }
            else {
                $test_con="admn_no";
            }
            
            if($main_tbl!='reg_regular_elective_opted' && $main_tbl!='reg_summer_subject' && $main_tbl!='reg_other_subject' && $main_tbl!='reg_idle_fee' && $main_tbl!='reg_exam_rc_subject' && $main_tbl!='hm_minor_details' && $main_tbl!='change_branch_option' && $main_tbl!='student_status' ){

             $sql="insert into ".$tbl_name."    
select * from ".$main_tbl." where ".$test_con." = ?";
        $query = $this->db->query($sql,array($admn_no));

//echo $sql;echo '<br>';
               //echo $this->db->last_query(); 
                if ($this->db->affected_rows() > 0) {
                    return true;
                } else {
                    return false;
                }

        }



                //echo $sql;die();
                
             
         
         }

         //=================other table to move==================
         function move_to_drop_reg_regular_elective_opted($admn_no)   {
                        
             $sql="insert into drop_reg_regular_elective_opted
select a.* from reg_regular_elective_opted a where a.form_id in
(
    select b.form_id from reg_regular_form b where b.admn_no =?
)";
                //echo $sql;die();
                $query = $this->db->query($sql,array($admn_no));

               //echo $this->db->last_query(); die();
                if ($this->db->affected_rows() > 0) {
                    return true;
                } else {
                    return false;
                }
            }
            
            function move_to_drop_reg_summer_subject($admn_no){
                 $sql="insert into drop_reg_summer_subject
select a.* from reg_summer_subject a where a.form_id in
(
    select b.form_id from reg_summer_form b where b.admn_no =?
)";
                //echo $sql;die();
                $query = $this->db->query($sql,array($admn_no));

               //echo $this->db->last_query(); die();
                if ($this->db->affected_rows() > 0) {
                    return true;
                } else {
                    return false;
                }
                
            }
            
            function move_to_drop_reg_other_subject($admn_no){
                 $sql="insert into drop_reg_other_subject
select a.* from reg_other_subject a where a.form_id in
(
    select b.form_id from reg_other_form b where b.admn_no =?
)";
                //echo $sql;die();
                $query = $this->db->query($sql,array($admn_no));

               //echo $this->db->last_query(); die();
                if ($this->db->affected_rows() > 0) {
                    return true;
                } else {
                    return false;
                }
                
            }
            
            function move_to_drop_reg_idle_fee($admn_no){
                $sql="insert into drop_reg_idle_fee
select a.* from reg_idle_fee a where a.form_id in
(
    select b.form_id from reg_idle_form b where b.admn_no =?
)";
                //echo $sql;die();
                $query = $this->db->query($sql,array($admn_no));

              //echo $this->db->last_query(); 
                if ($this->db->affected_rows() > 0) {
                    return true;
                } else {
                    return false;
                }
            }
            function move_to_drop_reg_exam_rc_subject($admn_no){
                    $sql="insert into drop_reg_exam_rc_subject
select a.* from reg_exam_rc_subject a where a.form_id in
(
    select b.form_id from reg_exam_rc_form b where b.admn_no =?
)";
                //echo $sql;die();
                $query = $this->db->query($sql,array($admn_no));

               //echo $this->db->last_query(); 
               
                if ($this->db->affected_rows() > 0) {
                    return true;
                } else {
                    return false;
                }
                
            }
            
            function move_to_drop_hm_minor_details($admn_no){
                    $sql="insert into drop_hm_minor_details
select a.* from hm_minor_details a where a.form_id in
(
    select b.form_id from hm_form b where b.admn_no =?
)";
                //echo $sql;die();
                $query = $this->db->query($sql,array($admn_no));

               //echo $this->db->last_query(); 
                if ($this->db->affected_rows() > 0) {
                    return true;
                } else {
                    return false;
                }
            
            }
            function move_to_drop_change_branch_log($admn_no){
                $sql="insert into drop_change_branch_option            
select a.* from change_branch_option a where a.cb_log_id in
(
    select b.id from change_branch_log b where b.admn_no = ?
)";
                //echo $sql;die();
                $query = $this->db->query($sql,array($admn_no));

               //echo $this->db->last_query(); 
                if ($this->db->affected_rows() > 0) {
                    return true;
                } else {
                    return false;
                }
                
                
                
                
            }

         //======================================================DROP===================================================================

function drop_from_main_table($tbl_name,$admn_no)   {
	
	$main_tbl=substr($tbl_name, strpos($tbl_name, "_") + 1);   
            
            if($tbl_name=='drop_user_address' || $tbl_name=='drop_user_details'  || $tbl_name=='drop_user_other_details' || $tbl_name=='drop_change_password_log' || $tbl_name=='drop_users'){
                $test_con="id";
            }
            else if($tbl_name=='drop_tabulation1'){
                $test_con="adm_no";
            }
            else if($tbl_name=='drop_complaint' ||$tbl_name=='drop_swimming_medical_report' || $tbl_name=='drop_swimming_student_details' ){
                $test_con="user_id";
            }
            else if($tbl_name=='drop_user_notifications' ){
                $test_con="user_to";
            }
            else {
                $test_con="admn_no";
            }

            //-----------------------------------------------------------------------
if($tbl_name!='drop_reg_regular_elective_opted' && $tbl_name!='drop_reg_summer_subject' && $tbl_name!='drop_reg_other_subject' && $tbl_name!='drop_reg_idle_fee' && $tbl_name!='drop_reg_exam_rc_subject' && $tbl_name!='drop_hm_minor_details' && $tbl_name!='drop_change_branch_option' && $tbl_name!='drop_student_status' ){

$sql=" delete from ".$main_tbl." where ".$test_con." =?";

                //echo $sql;
                $query = $this->db->query($sql,array($admn_no));

               //echo $this->db->last_query(); 
                if ($this->db->affected_rows() > 0) {
                    return true;
                } else {
                    return false;
                }
            

        }


            //-----------------------------------------------------------------------
            
             
             
         
         }


         //===================================Drop other=====================================================

function delete_from_reg_regular_elective_opted($admn_no)   {
                        
             $sql="delete from reg_regular_elective_opted  where form_id in
(
    select b.form_id from reg_regular_form b where b.admn_no =?
)";
                //echo $sql;die();
                $query = $this->db->query($sql,array($admn_no));

               //echo $this->db->last_query(); die();
                if ($this->db->affected_rows() > 0) {
                    return true;
                } else {
                    return false;
                }
            }
            
            function delete_from_reg_summer_subject($admn_no){
                 $sql="delete from reg_summer_subject  where form_id in
(
    select b.form_id from reg_summer_form b where b.admn_no =?
)";
                //echo $sql;die();
                $query = $this->db->query($sql,array($admn_no));

               //echo $this->db->last_query(); die();
                if ($this->db->affected_rows() > 0) {
                    return true;
                } else {
                    return false;
                }
                
            }
            
            function delete_from_reg_other_subject($admn_no){
                 $sql="delete from reg_other_subject  where form_id in
(
    select b.form_id from reg_other_form b where b.admn_no =?
)";
                //echo $sql;die();
                $query = $this->db->query($sql,array($admn_no));

               //echo $this->db->last_query(); die();
                if ($this->db->affected_rows() > 0) {
                    return true;
                } else {
                    return false;
                }
                
            }
            
            function delete_from_reg_idle_fee($admn_no){
                $sql="delete from reg_idle_fee  where form_id in
(
    select b.form_id from reg_idle_form b where b.admn_no =?
)";
                //echo $sql;die();
                $query = $this->db->query($sql,array($admn_no));

              //echo $this->db->last_query(); 
                if ($this->db->affected_rows() > 0) {
                    return true;
                } else {
                    return false;
                }
            }
            function delete_from_reg_exam_rc_subject($admn_no){
                    $sql="delete from reg_exam_rc_subject where form_id in
(
    select b.form_id from reg_exam_rc_form b where b.admn_no =?
)";
                //echo $sql;die();
                $query = $this->db->query($sql,array($admn_no));

               //echo $this->db->last_query(); 
               
                if ($this->db->affected_rows() > 0) {
                    return true;
                } else {
                    return false;
                }
                
            }
            
            function delete_from_hm_minor_details($admn_no){
                    $sql="delete from hm_minor_details  where form_id in
(
    select b.form_id from hm_form b where b.admn_no =?
)";
                //echo $sql;die();
                $query = $this->db->query($sql,array($admn_no));

               //echo $this->db->last_query(); 
                if ($this->db->affected_rows() > 0) {
                    return true;
                } else {
                    return false;
                }
            
            }
            function delete_from_change_branch_log($admn_no){
                $sql="delete from change_branch_option  where cb_log_id in
(
    select b.id from change_branch_log b where b.admn_no =?
)";
                //echo $sql;die();
                $query = $this->db->query($sql,array($admn_no));

               //echo $this->db->last_query(); 
                if ($this->db->affected_rows() > 0) {
                    return true;
                } else {
                    return false;
                }
                
                
                
                
            }


         //=====================================================================================================


function get_all_drop_student_list(){

    $sql="select * from drop_student_status";
              $query = $this->db->query($sql);

              if ($this->db->affected_rows() > 0) {
                    return $query->result();
                } else {
                    return false;
                }

}


}

?>