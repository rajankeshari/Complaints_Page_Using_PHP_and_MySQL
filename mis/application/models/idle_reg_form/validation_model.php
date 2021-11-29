<?php

class Validation_model extends CI_Model {
    
     private $form = 'reg_idle_form';
    private $fee ='reg_idle_fee';
    
    function __construct() {
        parent::__construct();
    }
    
    function checkDateOpen(){
        $this->load->model('student_sem_form/sbasic_model', '', TRUE);
        return $this->sbasic_model->getOcdate();
       
    }
  
    function checkRefillValidation($id){
        $q=$this->db->get_where($this->form,array('admn_no'=> $this->session->userdata('id'),'re_id'=>$id));
         if($q->num_rows() > 0){
            return true;
        }
            return false;
    }
   
    
    function checkStuStatus($admn_no,$sy,$sess){
        
        $sql = "select a.* from reg_idle_form a where a.admn_no=? 
and a.session_year=? and a.`session`=?  and a.hod_status<>'2' and a.acad_status<>'2'";

     $q = $this->db->query($sql,array($admn_no,$sy,$sess));
        //echo $this->db->last_query();die();
            if($q->num_rows() > 0){
                return true;
            }else{
            return false;
            }
       
        
        
        
//        $q=$this->db->get_where($this->form,array('admn_no'=> $this->session->userdata('id')));
//        echo $this->db->last_query();die();
//       // echo $q->num_rows().$this->session->userdata('id'); die();
//        if($q->num_rows() > 0){
//            return true;
//        }
//            return false;
    }
    function check_alrady_available($id,$sy,$sess){
        $sql = "
select * from reg_idle_form where admn_no=? and session_year=? and session=?";

        $query = $this->db->query($sql,array($id,$sy,$sess));

       //echo $this->db->last_query(); die();
        if ($this->db->affected_rows() >= 0) {
            return $query->result();
        } else {
            return false;
        }
    }
    
    function checkStuRejectHODStatus(){
          $q=$this->db->get_where($this->form,array('admn_no'=>$this->session->userdata('id'),'hod_status'=>2));
        if($q->num_rows() > 0){
           return $this->db->row();
        }
            return false;
    }
   function checkStuRejectACDStatus(){
         $q=$this->db->get_where($this->form,array('admn_no'=>$this->session->userdata('id'),'acdmic_status'=>2));
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
   
   function checkexisting($syear,$sess,$sem,$admn){
       $sql = "select a.* from reg_idle_form a
where a.session_year=? and a.`session`=? and a.semester=? and a.admn_no=?
and a.hod_status<>'2' and a.acad_status<>'2'";

        $query = $this->db->query($sql,array($syear,$sess,$sem,$admn));
       
        if ($this->db->affected_rows() > 0) {
            return true;
        } else {
            return false;
        }
       
       
   }
}

?>