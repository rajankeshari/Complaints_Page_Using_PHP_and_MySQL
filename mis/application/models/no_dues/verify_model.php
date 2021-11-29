<?php
/**
 * Created by PhpStorm.
 * User: Jay Doshi
 * Date: 25/5/15
 * Time: 11:01 AM
 */
class Verify_model extends CI_Model{
    public function get_admn_no($no)
    {
        $q = "SELECT admn_no from no_dues_current WHERE id= '$no'";
        $res= $this->db->query($q)->result_array();
        return $res;
    }
    public function get_dept_type($dept_id){
        $q = "SELECT type FROM departments WHERE id = '$dept_id'";
        $res = $this->db->query($q)->result_array();
        if(count($res) == 0) return 'undf';
        return $res[0]['type'];
    }
    public function get_dept($admn_no){
        $r = $this->stu_data($admn_no);
        $sem = $r[0]['semester'];
        $course_id = $r[0]['course_id'];
        $q_l = "SELECT duration FROM courses WHERE id = '$course_id'";
        $leave_year = $this->db->query($q_l)->result_array();

        $dur = (int)$leave_year[0]['duration'] * 2;

        if ((int)$sem == $dur){
            /*
             * for the students that are attending their last semesters.
             */
            $q_dept = "SELECT dept_id FROM no_dues_dept";
        }
        else{
            /*
             * for the students that are not in their final semesters.
             */
            $q_dept = "SELECT dept_id FROM no_dues_dept WHERE valid_for = '0'";
        }
        $dept = $this->db->query($q_dept)->result_array();
        $res = array();
        for ($i = 0; $i < count($dept); $i++){
            $type = $this->get_dept_type($dept[$i]['dept_id']);

            if ($type == 'academic' && $dept[$i]['dept_id'] == $r[0]['branch_id']){
                array_push($res, array("dept_id" => $r[0]['branch_id']));
            }
            if ($type != 'academic'){

                array_push($res, array("dept_id"=>$dept[$i]['dept_id']));
            }
        }
        return $res;
    }
    public function get_name($admn_no)
    {
        $qn = "SELECT first_name, middle_name, last_name FROM user_details WHERE id = '$admn_no'";
        $resn = $this->db->query($qn)->result_array();
        $name = $resn[0]['first_name'];
        if (strlen($resn[0]['middle_name']) != 0){
            $name = $name." ".$resn[0]['middle_name'];
        }
        if (strlen($resn[0]['last_name']) != 0){
            $name = $name." ".$resn[0]['last_name'];
        }
        return $name;
    }
    public function stu_data($admn_no)
    {

        $q ="SELECT course_id,branch_id,semester FROM stu_academic WHERE admn_no = '$admn_no'";

        $res=$this->db->query($q)->result_array();
        return $res;
    }
}