<?php

class Validation_model extends CI_Model {
    
    private $form = 'reg_other_form';
    private $subject ='reg_other_subject';
    private $fee ='reg_other_fee';
    
    function __construct() {
        parent::__construct();
    }
    
    function checkDateOpen(){
        $this->load->model('student_sem_form/sbasic_model', '', TRUE);
        return $this->sbasic_model->checkDate();
       
    }
  
    function checkRefillValidation($id){
        $q=$this->db->get_where($this->form,array('admn_no'=> $this->session->userdata('id'),'re_id'=>$id));
         if($q->num_rows() > 0){
            return true;
        }
            return false;
    }
   
    
    function checkStuStatus($sy=null,$sess=null){
        $adm=$this->session->userdata('id');
        $sql = "SELECT * FROM (`reg_other_form`) WHERE `admn_no` = ? AND `session_year` = ? and `session` = ? and hod_status<>'2' and acad_status<>'2'";

        $query = $this->db->query($sql,array($adm,$sy,$sess));

       if ($this->db->affected_rows() > 0) {
            return true;
        } else {
            return false;
        }
        
        
        
   /* $q=$this->db->get_where($this->form,array('admn_no'=> $this->session->userdata('id'),'session'=>$sess, 'session_year'=>$sy));
       echo $this->db->last_query();die();
        if($q->num_rows() > 0){
            return true;
        }
            return false;*/
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
   
   function get_current_sess()
   {
       $sql="select * from mis_current_session order by session desc limit 1";

            $query = $this->db->query($sql);

           
            if ($this->db->affected_rows() >= 0) {
                return $query->row();
            } else {
                return false;
            }
   }
}

?>