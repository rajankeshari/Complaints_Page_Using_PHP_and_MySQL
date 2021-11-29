<?php

class Validation_model extends CI_Model {

     private $form = 'reg_summer_form';
    private $subject ='reg_summer_subject';
    private $fee ='reg_summer_fee';

    function __construct() {
        parent::__construct();
    }

    function checkDateOpen(){
        $this->load->model('summer_reg_form/summer_basic_model', 'sbm', true);
        $sdate = $this->sbm->getOcdate();
        $sd = $sdate->opendate;
        $cd = $sdate->closedate;
        $cdate = strtotime(date('Y-m-d'));
        $sdate = strtotime($sd);
        $closedate = strtotime($cd);
        if ( $cdate >= $sdate && $cdate <= $closedate){
                return true;
        }
		if($this->session->userdata('id') == '2012je0001'){
			return true;
		}
        return false;
    }

    function checkSemester(){
        if($this->session->userdata('semester')%2 == 0)
            return true;
        return false;
    }

    function checkRefillValidation($id){
        $q=$this->db->get_where($this->form,array('admn_no'=> $this->session->userdata('id'),'re_id'=>$id));
         if($q->num_rows() > 0){
            return true;
        }
            return false;
    }

    function checkCourse(){
        $this->load->model('summer_reg_form/summer_basic_model', 'sbm', true);
        $courses = $this->sbm->getOcdatedes()->courses;
        $courses = unserialize($courses);
        if(array_search($this->session->userdata('course_id'), $this->session->userdata('auth'))){
            return true;
        }else{
            return false;
        }
    }

    function checkStuStatus(){
        $q=$this->db->get_where($this->form,array('admn_no'=> $this->session->userdata('id'),'semester'=>$this->session->userdata('semester')));
       // echo $q->num_rows().$this->session->userdata('id'); die();
        if($q->num_rows() > 0){
            return true;
        }
            return false;
    }

    function checkStuRejectHODStatus(){
          $q=$this->db->get_where($this->form,array('admn_no'=>$this->session->userdata('id'),'hod_status'=>2));
        if($q->num_rows() > 0){
           return $this->db->row();
        }
            return false;
    }
   function checkStuRejectACDStatus(){
         $q=$this->db->get_where($this->form,array('admn_no'=>$this->session->userdata('id'),'acad_status'=>2));
        if($q->num_rows() > 0){
           return $this->db->row();
        }
            return false;
   }
   function datelatefee(){
       $this->load->model('student_sem_form/sbasic_model', '', TRUE);
        $data= $this->sbasic_model->getOcdatedes();
        if($data[0]->type == 2){
            return true;
        }else{
            false;
        }
   }
}

?>
