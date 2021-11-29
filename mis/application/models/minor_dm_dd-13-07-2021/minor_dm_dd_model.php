<?php

class Minor_dm_dd_model extends CI_Model {

    function __construct() {
        parent::__construct();
    }

        
    function get_student()
    {
      $sql="SELECT a.*,CONCAT_WS(' ',b.first_name,b.middle_name,b.last_name)AS stu_name,b.dept_id
FROM major_minor_dual_temp a INNER JOIN user_details b ON b.id=a.admn_no";

        $query = $this->db->query($sql);

       
        if ($this->db->affected_rows() > 0) {
            return $query->result();
        } else {
            return false;
        }
    }
	function get_student_deptwise_doublemajor($dept_id,$criteria,$backlog,$droplist)
    {
      $sql="SELECT a.*,CONCAT_WS(' ',b.first_name,b.middle_name,b.last_name)AS stu_name,b.dept_id,c.stu_tbl_id
FROM major_minor_dual_temp a INNER JOIN user_details b ON b.id=a.admn_no LEFT JOIN major_minor_dual_final c ON c.stu_tbl_id=a.id WHERE  a.opt_dept_id=? AND a.applied_for='doublemajor' AND a.backlog_paper=0 AND a.drop_paper='0' AND a.obtained_cgpa>=8 AND   1 = 1 ";
  
			if ($criteria)
			{
					$sql .= " AND a.obtained_cgpa>='".$criteria."'";
			}

        $query = $this->db->query($sql,array($dept_id));

       
        if ($this->db->affected_rows() > 0) {
            return $query->result();
        } else {
            return false;
        }
    }
	
	function get_student_deptwise_dualdegree($dept_id,$ddtype,$criteria,$backlog,$droplist)
    {
		if($ddtype=='a')
		{
			$ddtype='dualdegree_categoryA';
		}
		else if($ddtype=='b')
		{
			$ddtype='dualdegree_categoryB';
		}
		else if($ddtype=='c')
		{
			$ddtype='dualdegree_categoryC';
		}
      $sql="SELECT a.*,CONCAT_WS(' ',b.first_name,b.middle_name,b.last_name)AS stu_name,b.dept_id,c.stu_tbl_id
FROM major_minor_dual_temp a INNER JOIN user_details b ON b.id=a.admn_no LEFT JOIN major_minor_dual_final c ON c.stu_tbl_id=a.id WHERE  a.opt_dept_id=? AND a.applied_for=? AND a.backlog_paper=0 AND a.drop_paper='0' AND a.obtained_cgpa>=7 AND   1 = 1 ";
  
			if ($criteria)
			{
					$sql .= " AND a.obtained_cgpa>='".$criteria."'";
			}

        $query = $this->db->query($sql,array($dept_id,$ddtype));

       
        if ($this->db->affected_rows() > 0) {
            return $query->result();
        } else {
            return false;
        }
    }
	
	
	
	function get_student_deptwise_dualdegree_categoryA($dept_id,$criteria,$backlog,$droplist)
    {
      $sql="SELECT a.*,CONCAT_WS(' ',b.first_name,b.middle_name,b.last_name)AS stu_name,b.dept_id,c.stu_tbl_id
FROM major_minor_dual_temp a INNER JOIN user_details b ON b.id=a.admn_no LEFT JOIN major_minor_dual_final c ON c.stu_tbl_id=a.id WHERE  a.opt_dept_id=? AND a.applied_for='dualdegree_categoryA' AND a.backlog_paper=0 AND a.drop_paper='0' AND a.obtained_cgpa>=7 AND   1 = 1 ";
  
			if ($criteria)
			{
					$sql .= " AND a.obtained_cgpa>='".$criteria."'";
			}

        $query = $this->db->query($sql,array($dept_id));

       
        if ($this->db->affected_rows() > 0) {
            return $query->result();
        } else {
            return false;
        }
    }
	function get_student_deptwise_dualdegree_categoryB($dept_id,$criteria,$backlog,$droplist)
    {
      $sql="SELECT a.*,CONCAT_WS(' ',b.first_name,b.middle_name,b.last_name)AS stu_name,b.dept_id,c.stu_tbl_id
FROM major_minor_dual_temp a INNER JOIN user_details b ON b.id=a.admn_no LEFT JOIN major_minor_dual_final c ON c.stu_tbl_id=a.id WHERE  a.opt_dept_id=? AND a.applied_for='dualdegree_categoryB' AND a.backlog_paper=0 AND a.drop_paper='0' AND a.obtained_cgpa>=7 AND   1 = 1 ";
  
			if ($criteria)
			{
					$sql .= " AND a.obtained_cgpa>='".$criteria."'";
			}

        $query = $this->db->query($sql,array($dept_id));

       
        if ($this->db->affected_rows() > 0) {
            return $query->result();
        } else {
            return false;
        }
    }
	function get_student_deptwise_dualdegree_categoryC($dept_id,$criteria,$backlog,$droplist)
    {
      $sql="SELECT a.*,CONCAT_WS(' ',b.first_name,b.middle_name,b.last_name)AS stu_name,b.dept_id,c.stu_tbl_id
FROM major_minor_dual_temp a INNER JOIN user_details b ON b.id=a.admn_no LEFT JOIN major_minor_dual_final c ON c.stu_tbl_id=a.id WHERE  a.opt_dept_id=? AND a.applied_for='dualdegree_categoryC' AND a.backlog_paper=0 AND a.drop_paper='0' AND a.obtained_cgpa>=7 AND   1 = 1 ";
  
			if ($criteria)
			{
					$sql .= " AND a.obtained_cgpa>='".$criteria."'";
			}

        $query = $this->db->query($sql,array($dept_id));

       
        if ($this->db->affected_rows() > 0) {
            return $query->result();
        } else {
            return false;
        }
    }
	function get_student_deptwise_minor($dept_id,$criteria,$backlog,$droplist)
    {
      $sql="SELECT a.*,CONCAT_WS(' ',b.first_name,b.middle_name,b.last_name)AS stu_name,b.dept_id,c.stu_tbl_id
FROM major_minor_dual_temp a INNER JOIN user_details b ON b.id=a.admn_no LEFT JOIN major_minor_dual_final c ON c.stu_tbl_id=a.id WHERE  a.opt_dept_id=? AND a.applied_for='minor'  AND   1 = 1 ORDER BY a.admn_no,a.priority,a.opt_title";
  
			if ($criteria)
			{
					$sql .= " AND a.obtained_cgpa>='".$criteria."'";
			}

        $query = $this->db->query($sql,array($dept_id));

       
        if ($this->db->affected_rows() > 0) {
            return $query->result();
        } else {
            return false;
        }
    }
	
	function get_student_applied_for($session_year,$session,$add_prog)
	{
		$sql="SELECT * FROM major_minor_dual_temp WHERE session_year=? AND SESSION=? and applied_for LIKE '%".$add_prog."%'";
        $query = $this->db->query($sql,array($session_year,$session));
        if ($this->db->affected_rows() > 0) {
            return $query->result();
        } else {
            return false;
        }
		
	}
	
	
	function get_cgpa_indivitual($admn_no)
    {
      $sql="SELECT * FROM final_semwise_marks_foil_freezed WHERE admn_no=?
AND session_yr='2020-2021' AND SESSION='Winter' ORDER BY semester,admn_no,actual_published_on DESC 	 LIMIT 1";

        $query = $this->db->query($sql,array($admn_no));

       
        if ($this->db->affected_rows() > 0) {
            return $query->row();
        } else {
            return false;
        }
    }
	function insert($data)
	{
		if($this->db->insert('major_minor_dual_criteria',$data))
			//return TRUE;
                        return $this->db->insert_id();
		else
			return FALSE;
	}
	
	function get_criteria_data()
    {
      $sql="SELECT * FROM major_minor_dual_criteria";

        $query = $this->db->query($sql);

       
        if ($this->db->affected_rows() > 0) {
            return $query->result();
        } else {
            return false;
        }
    }
	function check_availability($sy,$sess,$prog,$status)
	{
		$sql="SELECT * FROM major_minor_dual_criteria WHERE session_year=? AND SESSION=? AND programme=? AND STATUS=? ";

        $query = $this->db->query($sql,array($sy,$sess,$prog,$status));

       
        if ($this->db->affected_rows() > 0) {
            return true;
        } else {
            return false;
        }
		
	}
	
	function get_cgpa($session_year,$session,$add_prog)
	{
		$sql="SELECT cgpa FROM major_minor_dual_criteria WHERE session_year=? AND SESSION=? AND programme=? ";

        $query = $this->db->query($sql,array($session_year,$session,$add_prog));

       
        if ($this->db->affected_rows() > 0) {
            return $query->row();
        } else {
            return false;
        }
		
	}
	function update_cgpa($session_year,$session,$add_prog,$ivalue)
	{
		$sql="UPDATE  major_minor_dual_temp SET institute_cgpa=?  WHERE session_year=? AND SESSION=? and applied_for LIKE '%".$add_prog."%'   ";

        $query = $this->db->query($sql,array($ivalue,$session_year,$session));

       
        if ($this->db->affected_rows() > 0) {
            return true;
        } else {
            return false;
        }
		
	}
	function update_cgpa_stu($cgpa,$admn_no){
		
		$sql="UPDATE  major_minor_dual_temp SET obtained_cgpa=?  WHERE admn_no=?   ";

        $query = $this->db->query($sql,array($cgpa,$admn_no));

       
        if ($this->db->affected_rows() > 0) {
            return true;
        } else {
            return false;
        }
		
	}
	
	function update_backlog_stu($cnt,$admn_no){
		
		$sql="UPDATE  major_minor_dual_temp SET backlog_paper=?  WHERE admn_no=?   ";

        $query = $this->db->query($sql,array($cnt,$admn_no));

       
        if ($this->db->affected_rows() > 0) {
            return true;
        } else {
            return false;
        }
		
	}
	
	function update_drop_stu($cnt,$admn_no){
		
		$sql="UPDATE  major_minor_dual_temp SET drop_paper=?  WHERE admn_no=?   ";

        $query = $this->db->query($sql,array($cnt,$admn_no));

       
        if ($this->db->affected_rows() > 0) {
            return true;
        } else {
            return false;
        }
		
	}
	
	function get_major_minor_dual_criteria($id)
	{
		$sql="SELECT a.* from major_minor_dual_criteria a WHERE a.session_year='2021-2022' AND a.`session`='Monsoon' AND a.programme=? ";

        $query = $this->db->query($sql,array($id));

       
        if ($this->db->affected_rows() > 0) {
            return $query->row();
        } else {
            return false;
        }
		
	}
	
	function get_major_minor_dual_criteria_dept($id,$dept)
	{
		$sql="SELECT a.* from major_minor_dual_criteria_dept a WHERE a.session_year='2021-2022' AND a.`session`='Monsoon' AND a.programme=? and dept_id=?";

        $query = $this->db->query($sql,array($id,$dept));

       
        if ($this->db->affected_rows() > 0) {
            return $query->row();
        } else {
            return false;
        }
		
	}
	
	function get_student_row_major_minor_dual($id)
	{
		$sql="SELECT a.* from major_minor_dual_temp a WHERE a.id=? ";

        $query = $this->db->query($sql,array($id));

       
        if ($this->db->affected_rows() > 0) {
            return $query->row();
        } else {
            return false;
        }
		
		
	}
	function get_student_row_major_minor_dual_final($id)
	{
		$sql="SELECT a.* FROM major_minor_dual_final a WHERE a.admn_no=?  AND a.applied_for!='minor' ";

        $query = $this->db->query($sql,array($id));

       
        if ($this->db->affected_rows() > 0) {
            return $query->row();
        } else {
            return false;
        }
		
		
	}
	function get_student_row_major_minor_dual_final_minor($id)
	{
		$sql="SELECT a.* FROM major_minor_dual_final a WHERE a.admn_no=?  AND a.applied_for='minor' ";

        $query = $this->db->query($sql,array($id));

       
        if ($this->db->affected_rows() > 0) {
            return $query->result();
        } else {
            return false;
        }
		
		
	}
	
	function insert_stu_details($data)
	{
		if($this->db->insert('major_minor_dual_final',$data))
			//return TRUE;
                        return $this->db->insert_id();
		else
			return FALSE;
	}
	function get_student_from_maintable()
	{
		$sql="SELECT a.*,CONCAT_WS(' ',b.first_name,b.middle_name,b.last_name)AS stu_name,b.dept_id
FROM major_minor_dual_temp a INNER JOIN user_details b ON b.id=a.admn_no order by a.applied_for,a.priority  ";
  
			

        $query = $this->db->query($sql,array($dept_id));

       
        if ($this->db->affected_rows() > 0) {
            return $query->result();
        } else {
            return false;
        }
		
		
		
	}
	
	function get_student_finallist_otherdept($dept_id)
	{
		$sql="SELECT a.*,CONCAT_WS(' ',b.first_name,b.middle_name,b.last_name)AS stu_name,b.dept_id
FROM major_minor_dual_final a INNER JOIN user_details b ON b.id=a.admn_no WHERE  a.opt_dept_id!=?  ";
  
			

        $query = $this->db->query($sql,array($dept_id));

       
        if ($this->db->affected_rows() > 0) {
            return $query->result();
        } else {
            return false;
        }
		
		
		
	}
	
	function get_student_finallist($dept_id)
	{
		$sql="SELECT a.*,CONCAT_WS(' ',b.first_name,b.middle_name,b.last_name)AS stu_name,b.dept_id
FROM major_minor_dual_final a INNER JOIN user_details b ON b.id=a.admn_no WHERE  a.opt_dept_id=?  ";
  
			

        $query = $this->db->query($sql,array($dept_id));

       
        if ($this->db->affected_rows() > 0) {
            return $query->result();
        } else {
            return false;
        }
		
		
		
	}
	function get_student_deptwise_minor_all($dept_id,$criteria,$backlog,$droplist)
    {
      $sql="SELECT a.*,CONCAT_WS(' ',b.first_name,b.middle_name,b.last_name)AS stu_name,b.dept_id,c.stu_tbl_id
FROM major_minor_dual_temp a INNER JOIN user_details b ON b.id=a.admn_no LEFT JOIN major_minor_dual_final c ON c.stu_tbl_id=a.id WHERE  a.opt_dept_id=?
AND a.applied_for='minor' AND  1 = 1";
  
			if ($criteria)
			{
					$sql .= " AND a.obtained_cgpa>='".$criteria."'";
			}
			if ($backlog>='0')
			{
					$sql .= " AND a.backlog_paper='".$backlog."'";
			}
			if ($droplist>='0')
			{
					$sql .= " AND a.drop_paper='".$droplist."'";
			}

        $query = $this->db->query($sql,array($dept_id));

       
        if ($this->db->affected_rows() > 0) {
            return $query->result();
        } else {
            return false;
        }
    }
	
	function check_already($id){
		
		$sql="SELECT * FROM major_minor_dual_final WHERE stu_tbl_id=? ";
  
			

        $query = $this->db->query($sql,array($id));

       
        if ($this->db->affected_rows() > 0) {
            return true;
        } else {
            return false;
        }
		
		
	}
	function delete_to_final($id){
		$sql="DELETE FROM major_minor_dual_final WHERE id=? ";
  
			

        $query = $this->db->query($sql,array($id));

       
        if ($this->db->affected_rows() > 0) {
            return '1';
        } else {
            return '0';
        }
		
	}
	
	function insert_dept_condition($data)
	{
		if($this->db->insert('major_minor_dual_criteria_dept',$data))
			//return TRUE;
                        return $this->db->insert_id();
		else
			return FALSE;
	}
	
	function check_dept_condition($sy,$sess,$dept,$prog){
		$sql="SELECT a.* FROM major_minor_dual_criteria_dept a WHERE a.session_year=? AND a.`session`=? AND a.dept_id=? AND a.programme=? ";
  
			

        $query = $this->db->query($sql,array($sy,$sess,$dept,$prog));

       
        if ($this->db->affected_rows() > 0) {
            return $query->row();
        } else {
            return false;
        }
		
		
	}
	function del_dept_condition($id)
	{
		$sql="DELETE FROM major_minor_dual_criteria_dept WHERE id=? ";
		$query = $this->db->query($sql,array($id));
        if ($this->db->affected_rows() > 0) {
            return true;
        } else {
            return false;
        }
	}
	
	function update_status($remark,$doc_path,$session_year,$session,$dept_id){
		
		$sql="UPDATE  major_minor_dual_final SET status='1' ,remark1=?,remark2=? WHERE session_year=? AND SESSION=? and opt_dept_id=?   ";

        $query = $this->db->query($sql,array($remark,$doc_path,$session_year,$session,$dept_id));

       
        if ($this->db->affected_rows() > 0) {
            return true;
        } else {
            return false;
        }
		
	}
	
	function update_major_minor_dual_final_del($data)
	{
		if($this->db->insert('major_minor_dual_final_del',$data))
			//return TRUE;
                        return $this->db->insert_id();
		else
			return FALSE;
		
	}
	
	
	
	
    

}

?>