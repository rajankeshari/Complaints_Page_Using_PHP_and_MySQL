<?php

class Marks_submission_control_model extends CI_Model
{

    function __construct() {
        // Call the Model constructor
        parent::__construct();
    }

    function get_session_year()
    {
       $sql = "select * from mis_session_year";

        $query = $this->db->query($sql);
     
        if ($this->db->affected_rows() >= 0) {
            return $query->result();
        } else {
            return false;
        }
    
    }
    function get_session()
    {
        $sql = "select * from mis_session";

        $query = $this->db->query($sql);
     
        if ($this->db->affected_rows() >= 0) {
            return $query->result();
        } else {
            return false;
        }
    }
        function get_exam_type()
    {
        $sql = "select * from mis_exam_type";

        $query = $this->db->query($sql);
     
        if ($this->db->affected_rows() >= 0) {
            return $query->result();
        } else {
            return false;
        }
    }
    function insert_open_close_date($data)
    {
          if($this->db->insert('marks_sub_control_tbl',$data))
            return TRUE;
          else
            return FALSE;
    }
    function insert_open_close_date_batch($data)
    {
        if($this->db->insert_batch('marks_sub_control_tbl',$data))
			return TRUE;
		else
			return FALSE;
    }
    function fetch_record_all()
    {
     $myquery="select * from marks_sub_control_tbl where `type`='all'";
     $query = $this->db->query($myquery);
 
       if ($query->num_rows() > 0) {
            return $query->result();
        } else {
            return FALSE;
        }
        
    }
    function fetch_record_specific()
    {
        $myquery="select * from marks_sub_control_tbl where `type`='specific'";
     $query = $this->db->query($myquery);
 
       if ($query->num_rows() > 0) {
            return $query->result();
        } else {
            return FALSE;
        }
    }
    
    function get_row_details_byID($id)
    {
        $myquery="select * from marks_sub_control_tbl where id=".$id;
     $query = $this->db->query($myquery);
 
       if ($query->num_rows() > 0) {
            return $query->result();
        } else {
            return FALSE;
        }
    }
    function update_open_close_date_all($reason,$cdate,$odate,$per_by,$per_on,$row_id)
    {
        $myquery="update marks_sub_control_tbl set reason=?,per_ct_date=?,per_st_date=?,st_permitted_by=?,st_permitted_on=? where id=?";
        $query = $this->db->query($myquery,array($reason,$cdate,$odate,$per_by,$per_on,$row_id));
 
       if ($this->db->affected_rows() > 0) {
            return TRUE;
           //return $this->db->insert_id();
        } else {
            return FALSE;
        }
    }
    function insert_to_backup_table($row_id)
    {
        $myquery="INSERT INTO marks_sub_control_tbl_backup
                    SELECT * FROM marks_sub_control_tbl WHERE id = ?";
        $query = $this->db->query($myquery,array($row_id));
 
       if ($this->db->affected_rows() > 0) {
            return TRUE;
        } else {
            return FALSE;
        }
    }
    
    
   /* function check_permission_for_individual($sy,$sess,$dt,$eid)
    {
        $myquery="select a.* from marks_sub_control_tbl a where a.`type`='specific' and a.sess_year=? and a.`session`=? and a.per_ct_date >=? and a.ft_id=?";
        $query = $this->db->query($myquery,array($sy,$sess,$dt,$eid));
 
       if ($query->num_rows() > 0) {
            return '1';
        } else {
            return '0';
        }
    }
    
    function check_permission_for_all($sy,$sess,$dt)
    {
        $myquery="select a.* from marks_sub_control_tbl a
                    where a.sess_year=? and a.`session`=? and a.`type`='all'
                    and a.per_ct_date >=?";
        $query = $this->db->query($myquery,array($sy,$sess,$dt));

       if ($query->num_rows() > 0) {
            return '1';
        } else {
            return '0';
        }
    }*/
	
	// 1-nov-17 desc-marks submission must be stop before  marks upload date opening  by dr exam
	function check_permission_for_individual($sy,$sess,$dt,$eid)
    {
        $myquery="select a.* from marks_sub_control_tbl a where a.`type`='specific' and a.sess_year=? and a.`session`=? and (? between  a.per_st_date and  a.per_ct_date) and a.ft_id=?";
        $query = $this->db->query($myquery,array($sy,$sess,$dt,$eid));
 
       if ($query->num_rows() > 0) {
            return $query->result();
        } else {
            return FALSE;
        }
    }
    // 1-nov-17 desc-marks submission must be stop before  marks upload date opening  by dr exam
    function check_permission_for_all($sy,$sess,$dt)
    {
        $myquery="select a.* from marks_sub_control_tbl a
                    where a.sess_year=? and a.`session`=? and a.`type`='all'
                    and (? between  a.per_st_date and  a.per_ct_date)";
        
      
        $query = $this->db->query($myquery,array($sy,$sess,$dt));

       if ($query->num_rows() > 0) {
            return $query->result();
        } else {
            return FALSE;
        }
    }
    // 1-nov-17 desc-marks submission must be stop before  marks upload date opening  by dr exam
    function check_permission_test()
    {
        $myquery="select a.sess_year,a.`session` from marks_sub_control_tbl a order by a.sess_year desc limit 1";
        
      
        $query = $this->db->query($myquery);

       if ($query->num_rows() > 0) {
            return $query->row();
        } else {
            return false;
        }
    }
    
        

}
?>
