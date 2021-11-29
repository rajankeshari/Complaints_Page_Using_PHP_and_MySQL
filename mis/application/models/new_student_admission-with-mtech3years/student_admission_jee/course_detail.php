<?
class Course_detail extends CI_Model
{
	
	function __construct()
	{
		parent::__construct();
	}

    function get_course_detail()
	{
	   $query = $this->db->get('course_code');

       $data=array();
	   $i=0;
       foreach ($query->result_array() as $row)
       {
           $data[$i++]=$row;
       }
	   return $data;
	}	
	
	
	
	function update_detail($old_course_code,$new_course_code,$branch,$branch_id,$course,$course_id,$department,$department_id,$student_type,$admission_based_on)
	{
	    $this->db->where('course_code',strtoupper($old_course_code));
		$query=$this->db->get('course_code');
		if($query->result()==NULL)
		{
		    $this->session->set_flashData('flashError','Old Course code not matched.');
			  redirect('new_student_admission/course_edit_jee');
		}
	
	
        $arr=array('course_code'=>strtoupper($new_course_code),'branch'=>$branch,'branch_id'=>$branch_id,'course'=>$course,'course_id'=>$course_id,'department'=>$department,'department_id'=>$department_id,'student_type'=>$student_type,'admission_based_on'=>$admission_based_on);
		$this->db->where('course_code',$old_course_code);
		$this->db->update('course_code',$arr); 	
		
		
		$this->session->set_flashData('flashSuccess','Course updated successfully.');
			  redirect('new_student_admission/course_edit_jee');
		
	}
	
	
	function add_course($course_code,$branch,$branch_id,$course,$course_id,$department,$department_id,$student_type,$admission_based_on)
	{ 
	
	    $this->db->select('course_code');
		$this->db->where('course_code',$course_code);
		$query=$this->db->get('course_code');
		
		if($query->num_rows()>0)
		{
		    $this->session->set_flashData('flashError','Course code already exist.');
			  redirect('new_student_admission/course_edit_jee');
		}
	
        $arr=array('course_code'=>strtoupper($course_code),'branch'=>$branch,'branch_id'=>$branch_id,'course'=>$course,'course_id'=>$course_id,'department'=>$department,'department_id'=>$department_id,'student_type'=>$student_type,'admission_based_on'=>$admission_based_on);
		$this->db->insert('course_code',$arr);
 			
		
		$this->session->set_flashData('flashSuccess','Course added successfully.');
			  redirect('new_student_admission/course_edit_jee');
		
	}
	
	
	function delete_course($course_code)
	{
	   $this->db->delete('course_code', array('course_code' => strtoupper($course_code))); 

	 
	   $this->session->set_flashData('flashSuccess','Course Deleted Successfully.');
			  redirect('new_student_admission/course_edit_jee');
	}
	
	
	function get_course_offered($type)
	{
	    $this->db->where('department',$type);
	    $query2 = $this->db->get('course_code');
		return $query2->result();
	   
	}
	
	
	function get_branch_offered($dep,$course)
	{
	    $this->db->where('department',$dep);
		$this->db->where('course',$course);
	    $query = $this->db->get('course_code');
		return $query->result();
	   
	}
	
	
}
