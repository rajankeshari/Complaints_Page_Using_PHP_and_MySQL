<?php 
    class Reset_pass_model extends CI_Model{

        public function check_adm($adm_no){

            $q = "SELECT * FROM user_details WHERE id='$adm_no' " ;
            $query = $this->db->query($q);
            if($query->num_rows()>0)
            return 1;
            else
            return 0;
        }
        public function check_if_reset($adm_no){
            $q = "SELECT * FROM security_question WHERE id='$adm_no' " ;
            $query = $this->db->query($q);
            //echo $this->db->last_query();die();
            return $query->result();
            // if($query->num_rows()>0)
            //  return 1;
            //  else
            //  return 0;
        }
        public function retrieve_name($adm_no){
            $q = "SELECT * FROM user_details WHERE id='$adm_no' " ;
            $query = $this->db->query($q);
            //echo $this->db->last_query();die();
            if($query->num_rows()>0)
            return $query->result();

        }
        public function verify_data($data){

            //var_dump($data);
            $ans=1;
            $adm_no = $data['adm_no'];
            $dob = $data['dob'];
            $email = $data['email'];
            $acc_no = $data['acc_no'];
            $percentage = $data['percentage'];


            $q = "SELECT * FROM user_details WHERE id='$adm_no' and dob='$dob' " ;
            $query = $this->db->query($q);
            if($query->num_rows()>0)
                $ans*=1;
            else
                $ans*=0;

            $q = "SELECT * FROM user_details WHERE id='$adm_no' and email='$email' " ;
            $query = $this->db->query($q);
            if($query->num_rows()>0)
                $ans*=1;
            else
                $ans*=0;

            $q = "SELECT * FROM stu_prev_education WHERE admn_no='$adm_no' and specialization='12' and grade='$percentage' " ;
            $query = $this->db->query($q);
            if($query->num_rows()>0)
                $ans*=1;
            else
                $ans*=0;

            $q = "SELECT * FROM stu_other_details WHERE admn_no='$adm_no' and account_no='$acc_no' " ;
            $query = $this->db->query($q);
            if($query->num_rows()>0)
                $ans*=1;
            else
                $ans*=0;

            return $ans;

        }
    

    public function insert_security_question($arr){
        $this->db->insert('security_question',$arr);
    }
     public function change_pass($pass,$adm_no)
     {
         $this->db->where('id',$adm_no);
         $query = $this->db->get('users');
         $old_pass=$query->result()[0]->password;
             $date = date('Y-m-d H:i:s', time());//date('Y-m-d H:i:s')
           $data = array('id' => $adm_no,'old_password' => $old_pass,'time_stamp' => $date );
            $this->db->insert('change_password_log',$data);

            $arr = array('password' => $pass );
           $this->db->where('id', $adm_no);
            $this->db->update('users', $arr); 
     }
    public function check_hints($arr){

        $this->db->where($arr);
        //$q = "SELECT * FROM security_question WHERE id='$adm_no' " ;
        $query = $this->db->get('security_question');

        if($query->num_rows()>0)
            return 1;
        else
            return 0;
    }
    public function change_question_database($id,$arr){
    	//var_dump($arr);
    	$this->db->where('id', $id);
		$this->db->update('security_question', $arr); 
    }

    function getAnswerHints($id){
        $q="select sq.*,ud.mobile_no from user_other_details ud inner join security_question sq on ud.id=sq.id where ud.id=?";
        if($query=$this->db->query($q,$id)){
            //echo $this->db->last_query();die();
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
}
?>

