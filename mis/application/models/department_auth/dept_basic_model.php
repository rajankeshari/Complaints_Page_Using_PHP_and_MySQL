<?php

class Dept_basic_model extends CI_Model
{
    private $dep = 'dept_auth';
       
        function __construct() {
            parent::__construct();
        }
        
       function getFacultyByDept($id){
           $q=$this->db->query("select `ud`.`id`,CONCAT_WS(' ',`ud`.`salutation`,`ud`.`first_name`,`ud`.`middle_name`,`ud`.`last_name`, ' [',`ud`.`id`,']') as name
		   from `emp_basic_details` as fd 
		   join `user_details` as ud on `fd`.`emp_no`=`ud`.`id` 
		   join `users` as u on `u`.`id`=`ud`.`id` 
		   where `ud`.`dept_id`='".$id."' and `fd`.`auth_id`='ft' and `u`.`status`='A'
		   order by `name` ASC");
       
           $data=$q->result_array();
           $d =array(''=>'Select Faculty Member');
           foreach($data as  $v){
               $d[$v['id']] = $v['name']; 
           }
           return $d;
       }
       
       function getDAbyEmpId($id, $v = '',$auth='fa') {
		   
        $q = $this->db->get_where($this->dep, array('emp_id' => $id,'status'=>'Active','auth_id'=>$auth));
        if ($v == 1) {
            if ($q->num_rows() > 0) {
                return $q->result();
            }
        } else {
            if ($q->num_rows() > 0) {
                return true;
            }
            return false;
        }
    }

    function insertDA($data){
           $this->db->insert($this->dep,$data);
           return true;
       }
       function insertAuth($data){
           $this->db->insert('user_auth_types',$data);
           return true;
       }
       
       function delAuth($aid,$auth=''){
           if($auth){
                 $this->db->delete('user_auth_types', array('auth_id'=>$auth,'id'=>$aid), 1);
           }else{
           $this->db->delete('user_auth_types', array('auth_id'=>'fa','id'=>$aid), 1);
           }
           return true;
       }
       
       function UpdateDA($id,$set){
           $this->db->update($this->dep, $set, array('id'=>$id));
           return true;
       }
       
               
       function getAuthByEmpID($id){
           
           if($this->getDAbyEmpId($id)){
               
               $auth= $this->db->get_where('user_details',array('id'=>$id))->row()->dept_id;
               return $auth;
           }else{
               return false;
           }
           
       }
       
       function getDAByDeptId($id,$auth=''){
           if($auth){
           $q=$this->db->get_where($this->dep,array('dept_id'=>$id,'auth_id'=>$auth));
           }else{
           $q=$this->db->get_where($this->dep,array('dept_id'=>$id));
           }
           if($q->num_rows() > 0){
              return $q->result();
           }else{
               return false;
           }
       }
       
       function getEmpNameById($id){
            $q=$this->db->get_where('user_details',array('id'=>$id));
           if($q->num_rows() > 0){
              return $q->row();
           }else{
               return false;
           }
       }
       
        function getDAById($id) {
            $q = $this->db->get_where($this->dep,array('id'=>$id));
            return $q->row();
        }
        
        function getDAByDCBS($c,$b,$s,$v=''){
            $q=$this->db->get_where($this->dep,array('course_id'=>$c,'branch_id'=>$b,'semester'=>$s,'auth_id'=>'fa','status'=>'Active'));
            
        if ($v = 1) {
            if ($q->num_rows() > 0) {
             return  $q->row();
            } 
        } else {
            if ($q->num_rows() > 0) {
                return true;
            } else {
                return false;
            }
        }
    }
    function getAsignedRole($id,$auth){
          $q=$this->db->get_where($this->dep,array('emp_id'=>$id, 'auth_id'=>$auth,'status'=>'Active'));
          if($q->num_rows() > 0){
              return $q->row();
          }
          return false;
    }
	function getDAbyEmpId1($id, $v = '',$auth='jcord') 
    {
       
        $q = $this->db->get_where($this->dep, array('emp_id' => $id,'status'=>'Active','auth_id'=>$auth));
        if ($v == 1) 
            {
                if ($q->num_rows() > 0) 
                {
                    return $q->result();
                }
            } 
        else 
        {
            if ($q->num_rows() > 0)
            {
                return true;
            }
        return false;
        }
    }
}
?>