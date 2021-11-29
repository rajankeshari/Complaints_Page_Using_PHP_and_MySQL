<?php

class Cbcs_common_model extends CI_Model {

    function __construct() {
        parent::__construct();
    }

        function get_dept_list()
    {
          
      $sql = "select * from cbcs_departments where type='academic' and status=1 order by name";
        
        
        $query = $this->db->query($sql);

       
        if ($this->db->affected_rows() > 0) {
            return $query->result();
        } else {
            return false;
        }
    }
	 function credit_policy_year($id){
      $sql = "select * from cbcs_credit_points_policy where course_id='$id'";
        $query = $this->db->query($sql);
      //  echo $this->db->last_query();
        if ($this->db->affected_rows() > 0) {
            return $query->result();
        } else {
            return false;
        }
    }
	
	
	function get_dept($id)
    {
          
      $sql = "select * from cbcs_departments where type='academic' and status=1 and id='$id' order by name ";
        
        
        $query = $this->db->query($sql);

       
        if ($this->db->affected_rows() > 0) {
            return $query->result();
        } else {
            return false;
        }
    }



    function get_course_list()
    {
          
      $sql = "select * from cbcs_courses where status=1 order by name";
        
        
        $query = $this->db->query($sql);

       
        if ($this->db->affected_rows() > 0) {
            return $query->result();
        } else {
            return false;
        }
    }
	
	
	
	/* Commented by CK as per Shobhan Request on 24 April 2020
	function get_course_list_pg(){
		$sql = "select * from cbcs_courses where status=1 and duration in ('2','3') order by name";       
        $query = $this->db->query($sql);       
        if ($this->db->affected_rows() > 0) {
            return $query->result();
        } else {
            return false;
        }
	} */
	
	function get_course_list_pg(){

        //$sql = "select * from cbcs_courses where status=1 and (duration in ('2','3') or (duration='5' and id != 'dualdegree')) order by name";    
        $sql = "select * from cbcs_courses where status=1 and (duration in ('2','3') or (duration='5' and id not in ('dualdegree','int.m.tech'))) order by name";
        $query = $this->db->query($sql);      
        if ($this->db->affected_rows() > 0) {
            return $query->result();
        } else {
            return false;
        }
	}
	
	/* Commented by CK as per Shobhan Request on 24 April 2020
	function get_course_list_ug(){
		$sql = "select * from cbcs_courses where status=1 and duration in ('4','5') order by name";       
        $query = $this->db->query($sql);       
        if ($this->db->affected_rows() > 0) {
            return $query->result();
        } else {
            return false;
        }
	} */
	
	function get_course_list_ug(){
   
        //$sql = "select * from cbcs_courses where status=1 and (duration in ('4') or (duration='5' and id = 'dualdegree')) order by name";
        $sql = "select * from cbcs_courses where status=1 and (duration in ('4') or (duration='5' and id in ('dualdegree','int.m.tech'))) order by name";
        $query = $this->db->query($sql);      
        if ($this->db->affected_rows() > 0) {
            return $query->result();
        } else {
            return false;
        }
	}


     function get_branch_list()
    {
          
      $sql = "select * from cbcs_branches where status=1 order by name";
        
        
        $query = $this->db->query($sql);

       
        if ($this->db->affected_rows() > 0) {
            return $query->result();
        } else {
            return false;
        }
    }
	
	function get_braches_by_id($id)
    {
          
      $sql = "select a.id, a.name from cbcs_branches a inner join course_branch b on b.branch_id=a.id where b.course_id='$id' order by a.id";
        
        
        $query = $this->db->query($sql);

       
        if ($this->db->affected_rows() > 0) {
            return $query->result();
        } else {
            return false;
        }
    }

    //===============================================copy of report model to fetch course and branch

    function get_course_bydept_cs($dept_id)
    {
        
        $query = $this->db->query("SELECT DISTINCT course_branch.course_id,id,name,duration FROM 
cbcs_courses INNER JOIN course_branch ON course_branch.course_id = cbcs_courses.id INNER JOIN dept_course ON 
dept_course.course_branch_id = course_branch.course_branch_id WHERE dept_course.dept_id = '".$dept_id."' and cbcs_courses.`status`=1");
         //   echo  $this->db->last_query();    die();
                if($query->num_rows() > 0)
            return $query->result();
        else
            return false;
    }
    // end
    function get_branch_bycourse($course)
    {
        
        $query = $this->db->query("SELECT DISTINCT id,name,dept_course.course_branch_id FROM cbcs_branches INNER JOIN course_branch ON course_branch.branch_id = cbcs_branches.id INNER JOIN dept_course ON dept_course.course_branch_id = course_branch.course_branch_id WHERE course_branch.course_id = '".$course."'  and cbcs_branches.`status`=1");
        if($query->num_rows() > 0)
            return $query->result();
        else
            return false;
    }
	function get_branch_by_course($course,$dept)
    {
        
        $query = $this->db->query("SELECT DISTINCT id,name,dept_course.course_branch_id FROM cbcs_branches INNER JOIN course_branch ON course_branch.branch_id = cbcs_branches.id INNER JOIN dept_course ON dept_course.course_branch_id = course_branch.course_branch_id WHERE course_branch.course_id = '".$course."'  and cbcs_branches.`status`=1 and dept_course.dept_id='$dept'");
        if($query->num_rows() > 0)
            return $query->result();
        else
            return false;
    }

    function get_branch_bycourse_dept($course,$dept)
    {
        
        $query = $this->db->query("SELECT DISTINCT id,name,dept_course.course_branch_id FROM cbcs_branches INNER JOIN course_branch ON course_branch.branch_id = cbcs_branches.id INNER JOIN dept_course ON dept_course.course_branch_id = course_branch.course_branch_id WHERE course_branch.course_id = '".$course."' and dept_course.dept_id='".$dept."' and cbcs_branches.`status`=1");
        if($query->num_rows() > 0)
            return $query->result();
        else
            return false;
    }


    //================================================copied from marks submission control model=======================

    function get_session_year()
    {
       $sql = "select * from mis_session_year order by session_year desc";

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

    function get_paper_types(){
     $sql = "select * from mis_paper_type order by id";
        $query = $this->db->query($sql);
        if ($this->db->affected_rows() > 0) {
           return $query->result();
        } else {
           return false;
        }
    }

     function get_paper_types_new(){
         $sql = "select * from mis_paper_type where paper_type != 'jrf' order by id";
        $query = $this->db->query($sql);
        if ($this->db->affected_rows() > 0) {
           return $query->result();
        } else {
           return false;
        }
    }

//============================================================================================
    function get_dcb_list($id)
    {
          
      $sql = "SELECT b.course_id,d.name AS cname
FROM dept_course a
INNER JOIN course_branch b ON b.course_branch_id=a.course_branch_id
INNER JOIN cbcs_departments c ON c.id=a.dept_id
INNER JOIN cbcs_courses d ON d.id=b.course_id
INNER JOIN cbcs_branches e ON e.id=b.branch_id
WHERE a.dept_id=? AND c.status=1 AND d.status=1 AND e.status=1
GROUP BY b.course_id";
        
        
        $query = $this->db->query($sql,array($id));

       
        if ($this->db->affected_rows() > 0) {
            return $query->result();
        } else {
            return false;
        }
    }

    

function get_dname($id){
	
    $sql = "select a.name from cbcs_departments a where a.id=? and a.status=1";
    $query = $this->db->query($sql,array($id));
    if ($this->db->affected_rows() > 0) {
       return $query->row()->name;
    } else {
       return false;
    }
}
    

function get_cname($id){
    $sql = "select a.name from cbcs_courses a where a.id=? and a.status=1";
    $query = $this->db->query($sql,array($id));
    if ($this->db->affected_rows() > 0) {
       return $query->row()->name;
    } else {
       return false;
    }
}

function get_bname($id){
    $sql = "select a.name from cbcs_branches a where a.id=? and a.status=1";
    $query = $this->db->query($sql,array($id));
    if ($this->db->affected_rows() > 0) {
       return $query->row()->name;
    } else {
       return false;
    }
}


function get_semester_by_courseid($id){

    $sql = "select duration*2 as semester from cbcs_courses  where id=? and status='1'";
    $query = $this->db->query($sql,array($id));
    if ($this->db->affected_rows() > 0) {
       return $query->row()->semester;
    } else {
       return false;
    }



}

public function get_course_component_list(){
        $result=$this->db->query("SELECT * FROM `cbcs_course_component`");
        $result=$result->result();
        return $result;
    }

    

    function get_dept_faculty($dept_id){

        $sql = "select a.id,concat(a.first_name,' ',a.middle_name,' ',a.last_name,' (',a.id,')')as fname from user_details a
inner join emp_basic_details b on b.emp_no=a.id where a.dept_id=? and b.auth_id='ft' order by a.first_name,a.middle_name,a.last_name";
        $query = $this->db->query($sql,array($dept_id));
        if ($this->db->affected_rows() > 0) {
           return $query->result();
        } else {
           return false;
        }


    }
	
function get_session_year_active()
    {
       $sql = "select * from mis_session_year where active='1'";

        $query = $this->db->query($sql);

        if ($this->db->affected_rows() >= 0) {
            return $query->result();
        } else {
            return false;
        }

    }
    function get_session_active()
    {
        $sql = "select * from mis_session where active='1'";

        $query = $this->db->query($sql);

        if ($this->db->affected_rows() >= 0) {
            return $query->result();
        } else {
            return false;
        }
    }

    


    

}

?>