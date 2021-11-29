<?php

class Validation_model extends CI_Model {
    
    private $form = 'reg_exam_rc_form';
    private $subject ='reg_exam_rc_subject';
    private $fee ='reg_exam_rc_fee';
    
    function __construct() {
        parent::__construct();
    }
    
    function checkDateOpen(){
        $this->load->model('student_sem_form/sbasic_model', '', TRUE);
     // var_dump($this->sbasic_model->checkDate(2)); die;
	
        return $this->sbasic_model->checkDate(2);
       
    }
	function getOCDate(){
		 $this->load->model('student_sem_form/sbasic_model', '', TRUE);
     //   var_dump($this->sbasic_model->checkDate(2)); die;
        return $this->sbasic_model->getOcdatedes(2);
	}
  
    function checkRefillValidation($id,$admn_no=''){
        if(!$admn_no){
            $admn_no = $this->session->userdata('id');
        }
        $q=$this->db->get_where($this->form,array('admn_no'=> $admn_no,'re_id'=>$id));
         if($q->num_rows() > 0){
            return true;
        }
            return false;
    }
   
    
    function checkStuStatus($admn_no=''){
         if(!$admn_no){
            $admn_no = $this->session->userdata('id');
        }
        $q=$this->db->get_where($this->form,array('admn_no'=> $admn_no,'type'=>'R','session'=>'Winter','session_year'=>'2016-2017'));
        if($q->num_rows() > 0){
            return true;
        }
            return false;
    }
    
    function checkStuRejectHODStatus($admn_no=''){
         if(!$admn_no){
            $admn_no = $this->session->userdata('id');
        }
          $q=$this->db->get_where($this->form,array('admn_no'=>$admn_no,'hod_status'=>2));
        if($q->num_rows() > 0){
           return $this->db->row();
        }
            return false;
    }
   function checkStuRejectACDStatus($admn_no=''){
        if(!$admn_no){
            $admn_no = $this->session->userdata('id');
        }
         $q=$this->db->get_where($this->form,array('admn_no'=>$admn_no,'acad_status'=>2));
        if($q->num_rows() > 0){
           return $this->db->row();
        }
            return false;
   }
   
   function datelatefee(){
       $this->load->model('student_sem_form/sbasic_model', '', TRUE);
        $data= $this->sbasic_model->getOcdatedes(2);
        if($data[0]->type == 2){
            return true;
        }else{
            false;
        }
   }
   
   function getStudentDetails($id){
       $q=$this->db->query('select * from user_details as a join stu_academic as b on a.id=b.admn_no where a.id=?',array($id));
       if($q->num_rows() > 0){
           return $q->row();
       }
       return false;
   }
}

?>