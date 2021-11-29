<?php 
    class Sec_inside_model extends CI_Model{

    	public function insert_security_question($arr,$id){

            $q = "SELECT * FROM security_question WHERE id='$id' " ;
            $query = $this->db->query($q);
            if($query->num_rows()==0)
            {
                $this->db->insert('security_question',$arr);
                return 0;
            }
            else
            {
                $this->db->where('id', $id);
                $this->db->update('security_question', $arr);
                return 1;
            }
    	}

    	public function check_if_reset($adm_no){

            $q = "SELECT * FROM security_question WHERE id='$adm_no' " ;
            $query = $this->db->query($q);
            if($query->num_rows()>0)
              return 1;
            else
              return 0;
        }
        public function retrieve_old_questions($adm_no){

            $q = "SELECT * FROM security_question WHERE id='$adm_no' " ;
            $query = $this->db->query($q);
            return $query->result();
        }
    }
?>