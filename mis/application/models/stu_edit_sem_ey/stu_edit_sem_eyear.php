<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
*/
class Stu_edit_sem_eyear extends CI_Model
{
    function __construct() 
    {
        parent::__construct();
    }
    function select_data($admn_no)
    {
       
        $sql="select sa.enrollment_year as acd_enr_year,sa.semester,se.enrollment_year,se.passout_year from stu_academic sa,stu_enroll_passout se where sa.admn_no=se.admn_no and se.admn_no='$admn_no'";
        //echo $sql;
        $query=$this->db->query($sql);
        return $query->result();
    }
    
    function update_sem_year($data)
    {
        $admn_no=$data['admn_no'];
        $stu_acd_enr_year=$data['enrol_year'];
        $sem=$data['semester'];
        $pass_enr_year=$data['pass_enr_year'];
        $pass_year=$data['passout_year'];
        
        //Updating Student Academics
        $sql="update stu_academic set enrollment_year='$stu_acd_enr_year', semester=$sem where admn_no='$admn_no'";
        $this->db->query($sql);
        //Updating Stu_enroll_passout
        
        $sql="update stu_enroll_passout set enrollment_year='$pass_enr_year', passout_year='$pass_year' where admn_no='$admn_no'";
        $this->db->query($sql);
    }
}
