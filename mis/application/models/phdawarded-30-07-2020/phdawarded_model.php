<?php

class Phdawarded_model extends CI_Model {

    function __construct() {
        parent::__construct();
    }

        
    function get_basic_details($id)
    {
       
        $sql="SELECT a.id, CONCAT_WS(' ',a.first_name,a.middle_name,a.last_name) AS sname,a.sex, 
b.father_name,b.mother_name,b.mobile_no,a.email,a.dept_id,c.name AS dname,d.branch_id,a.photopath
FROM user_details a
INNER JOIN user_other_details b ON b.id=a.id
INNER JOIN departments c ON c.id=a.dept_id
inner join stu_academic d on d.admn_no=a.id
WHERE a.id=?";

        
        $query = $this->db->query($sql,array($id));

       
        if ($this->db->affected_rows() >= 0) {
            return $query->row();
        } else {
            return false;
        }
    }
    function get_guide()
    {
          
     
        
        $sql="select a.id,concat_ws(' ',a.first_name,a.middle_name,a.last_name)as ftname,a.dept_id from user_details a 
inner join emp_basic_details b on b.emp_no=a.id
where b.auth_id='ft' order by a.first_name,a.middle_name,a.last_name";

        
        $query = $this->db->query($sql);

       
        if ($this->db->affected_rows() >= 0) {
            return $query->result();
        } else {
            return false;
        }
    }
    function get_departments(){
        $sql="select * from departments where type='academic'";
 
        
        $query = $this->db->query($sql);

       
        if ($this->db->affected_rows() >= 0) {
            return $query->result();
        } else {
            return false;
        }
    }
    function get_faculty_dept_wise_list($id){
        $sql="select a.id,concat_ws(' ',a.first_name,a.middle_name,a.last_name)as ftname,a.dept_id from user_details a 
inner join emp_basic_details b on b.emp_no=a.id
inner join users c on c.id=a.id
where b.auth_id='ft' and c.status='A' and a.dept_id=? order by a.first_name,a.middle_name,a.last_name";

        
        $query = $this->db->query($sql,array($id));

       
        if ($this->db->affected_rows() >= 0) {
            return $query->result();
        } else {
            return false;
        }
    }
    function add_external_guide($data){
        if($this->db->insert('fellow_external_co_guide',$data))
			return $this->db->insert_id();
		else
			return FALSE;
        
    }
    
    function get_external_organization()
    {
        $sql="select * from fellow_external_co_guide";

        
        $query = $this->db->query($sql);

       
        if ($this->db->affected_rows() > 0) {
            return $query->result();
        } else {
            return false;
        }
    }
    
    function get_guide_list($id){
        $sql="select * from  fellow_external_co_guide where id=?";

        
        $query = $this->db->query($sql,array($id));

       
        if ($this->db->affected_rows() >= 0) {
            return $query->result();
        } else {
            return false;
        }
        
    }
    function add_phd_awarded_details($data){
         if($this->db->insert('phd_awarded',$data))
			return $this->db->insert_id();
		else
			return FALSE;
    }
    
    
    function get_current_phd_student(){
        $sql="select x.* from(
select a.id,concat_ws(' ',a.first_name,a.middle_name,a.last_name)as sname,c.name as dname,
CASE
    WHEN b.other_rank='fulltime' THEN 'Full Time'
    WHEN b.other_rank='parttime' THEN 'Part Time'
    ELSE ' '
END  as 'stu_type', 'Ongoing' as status,
a.sex ,
a.category,
e.religion,
a.physically_challenged,
e.nationality
from user_details a 
inner join stu_academic b on a.id=b.admn_no
inner join departments c on c.id=a.dept_id
inner join users d on d.id=a.id
INNER JOIN user_other_details e ON e.id=a.id
where b.auth_id='jrf' and d.status='A'
order by a.dept_id,a.id,a.first_name,a.middle_name,a.last_name)x
where x.id not in (select admn_no from phd_awarded);
";

        
        $query = $this->db->query($sql);

       
        if ($this->db->affected_rows() > 0) {
            return $query->result();
        } else {
            return false;
        }
        
    }
    function get_awarded_phd_student(){
        $sql="select a.*,concat_ws(' ',b.first_name,b.middle_name,b.last_name)as sname,c.name as dname, 
CASE
    WHEN d.other_rank='fulltime'  THEN 'Full Time' 
    WHEN d.other_rank='parttime' THEN 'Part Time'
    ELSE ' '
END  as 'stu_type', 'Completed' as status

from phd_awarded a
inner join user_details b on b.id=a.admn_no
inner join departments c on c.id=a.dept_id
inner join stu_academic d on d.admn_no=a.admn_no 
inner join users e on e.id=b.id
where e.status='P'";

        
        $query = $this->db->query($sql);

       
        if ($this->db->affected_rows() > 0) {
            return $query->result();
        } else {
            return false;
        }
    }
    
        function get_guide_coguide($id){
        $sql="select concat_ws(' ',a.salutation,a.first_name,a.middle_name,a.last_name)as fname from user_details a where a.id=?";
        $query = $this->db->query($sql,array($id));
        if ($this->db->affected_rows() > 0) {
            return $query->row();
        } else {
            return false;
        }
    }
    //==========external guide=======
    
    function get_external($id){
        $sql="select a.ext_name from fellow_external_co_guide a where a.id=?";
        $query = $this->db->query($sql,array($id));
        if ($this->db->affected_rows() > 0) {
            return $query->row();
        } else {
            return false;
        }
    }
    function check_exists($id){
        $sql="select * from phd_awarded a where a.admn_no=?";
        $query = $this->db->query($sql,array($id));
        if ($this->db->affected_rows() > 0) {
            return $query->row();
        } else {
            return false;
        }
    }
    
    function update_stu_academic_branchid($admnno,$phdtype){
        $sql = "update stu_academic set other_rank='".$phdtype."' where admn_no='".$admnno."'";
        $query = $this->db->query($sql);
        if ($this->db->affected_rows() > 0) {
            return TRUE;
        } else {
            return FALSE;
        }
    }
	
	function check_user_details($id){
        $sql="select a.* from user_details a where a.id=?";
        $query = $this->db->query($sql,array($id));
        if ($this->db->affected_rows() > 0) {
            return $query->row();
        } else {
            return FALSE;
        }
    }
	
	function checkjrf($id){
		$sql="select a.auth_id from stu_academic a where a.admn_no=?";
        $query = $this->db->query($sql,array($id));
        if ($this->db->affected_rows() > 0) {
            return $query->row()->auth_id;
        } else {
            return FALSE;
        }
	}
	
	 function update_users_table_status_phd($admn_no){
	 $sql = "UPDATE users SET STATUS='P' WHERE id=?";
        $query = $this->db->query($sql,array($admn_no));
        
        if ($this->db->affected_rows() > 0) {
            return TRUE;
        } else {
            return FALSE;
        }
	 
 }
 
 function get_address($id,$type){
	 $sql = "SELECT a.* FROM user_address a WHERE a.id=? AND a.`type`=?";
        $query = $this->db->query($sql,array($id,$type));
        
        if ($this->db->affected_rows() > 0) {
            return $query->row();
        } else {
            return FALSE;
        }
	 
 }

}

?>