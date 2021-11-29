<?php
class View_attendance_sheet_model extends CI_Model
{
	public function __construct()
	{
		parent::__construct();
	}
	
	public function get_session_year()
	{
		$emp_id=$this->session->userdata('id');
		$this->load->database();
		$query=$this->db->query("SELECT DISTINCT session_year
								FROM subject_mapping_des AS A
								INNER JOIN subject_mapping AS B ON A.map_id = B.map_id 
								
								ORDER BY session_year;");
		return $query->result();
	}
	public function get_subjects($data)
	{
		$emp_id=$this->session->userdata('id');
		$session_year=$data['session_year'];
		$session=$data['session'];
		$this->load->database();
	

			$query= $this->db->query("(SELECT subject_mapping.map_id as map_id,subject_mapping.semester as semester, subjects.subject_id as sub_code, subjects.name as sub_name ,subjects.id as sub_id, subject_mapping.branch_id as branch_id ,subject_mapping.group,subject_mapping.section,subject_mapping.aggr_id,
			 cs_branches.name as branch_name,subject_mapping.course_id as course_id, 
			 cs_courses.name as course_name, subject_mapping_des.coordinator as coordinator,subjects.type as sub_type
			FROM subject_mapping 
			INNER JOIN subject_mapping_des ON subject_mapping_des.map_id = subject_mapping.map_id
			INNER JOIN subjects ON subjects.id = subject_mapping_des.sub_id
			INNER JOIN cs_branches ON subject_mapping.branch_id=cs_branches.id
			INNER JOIN cs_courses ON subject_mapping.course_id=cs_courses.id
			WHERE session_year='$session_year' and session='$session' and emp_no=$emp_id and type!='Practical' and `type`<>'Non-contact' and course_id<>'jrf') union  (SELECT p.map_id AS map_id,p.semester AS semester,p.sub_code,p.sub_name ,p.sub_id, 
p.branch_id,p.group,'0'as section,p.aggr_id, p. branch_name,
p.course_id , p.course_name, 
p.coordinator,p.type AS sub_type

from
(
SELECT x.sub_code,x.sub_name,x.sub_id,x.semester,x.group,x.branch_name, x.course_name,x.branch_id,x.course_id,x.aggr_id,y.map_id,
z.coordinator,x.type
FROM (
SELECT DISTINCT e.subject_id AS sub_code, e.name AS sub_name, e.id AS sub_id, d.semester, '0' AS 'group', 
g.name AS branch_name, c.name AS course_name, b.branch_id,b.course_id, a.honours_agg_id AS aggr_id, 
c.duration, b.session_year,b.`session`,e.`type`
FROM hm_form a
INNER JOIN reg_regular_form b ON a.admn_no=b.admn_no
INNER JOIN cs_courses c ON c.id=b.course_id
INNER JOIN course_structure d ON d.aggr_id=a.honours_agg_id
INNER JOIN subjects e ON e.id=d.id
INNER JOIN stu_academic f ON f.admn_no=b.admn_no
INNER JOIN cs_branches g ON g.id=f.branch_id
WHERE a.dept_id='".$this->session->userdata('dept_id')."' AND c.duration=5 AND b.session_year='".$data['session_year']."' AND b.`session`='".$data['session']."' AND d.semester=b.semester)x
INNER JOIN subject_mapping y ON (y.session_year=x.session_year AND y.`session`=x.session AND y.aggr_id=x.aggr_id AND y.semester=x.semester)
INNER JOIN subject_mapping_des z ON z.map_id=y.map_id AND z.sub_id=x.sub_id AND z.emp_no='".$emp_id."')p
where p.course_id <> 'jrf' AND p.type!='Practical' 
AND p.type<>'Non-contact') ");

                        
		$result= $query->result();
		//echo $this->db->last_query();die();
		return $result;
	
	}
        
        //======================================JRF========================================================
        public function get_subjects_jrf($data)
	{
		$emp_id=$this->session->userdata('id');
		$session_year=$data['session_year'];
		$session=$data['session'];
		$this->load->database();
	

			$query= $this->db->query("(SELECT subject_mapping.map_id as map_id,subject_mapping.semester as semester, subjects.subject_id as sub_code, subjects.name as sub_name ,subjects.id as sub_id, subject_mapping.branch_id as branch_id ,subject_mapping.group,subject_mapping.section,subject_mapping.aggr_id,
			 cs_branches.name as branch_name,subject_mapping.course_id as course_id, 
			 cs_courses.name as course_name, subject_mapping_des.coordinator as coordinator,subjects.type as sub_type
			FROM subject_mapping 
			INNER JOIN subject_mapping_des ON subject_mapping_des.map_id = subject_mapping.map_id
			INNER JOIN subjects ON subjects.id = subject_mapping_des.sub_id
			INNER JOIN cs_branches ON subject_mapping.branch_id=cs_branches.id
			INNER JOIN cs_courses ON subject_mapping.course_id=cs_courses.id
			WHERE session_year='$session_year' and session='$session' and emp_no=$emp_id and type!='Practical' and `type`<>'Non-contact' and course_id='jrf') union  (SELECT d.map_id,
d.semester,
e.subject_id AS sub_code,
e.name AS sub_name,
a.sub_id, 
d.branch_id,
d.`group`,
d.section,
d.aggr_id, 
f.name AS branch_name,
d.course_id,
g.name AS course_name,
c.coordinator,
e.`type` AS sub_type
FROM reg_exam_rc_subject a
INNER JOIN reg_exam_rc_form b ON b.form_id=a.form_id
INNER JOIN subject_mapping_des c ON c.sub_id=a.sub_id
INNER JOIN subject_mapping d ON d.map_id=c.map_id AND d.session_year='".$data['session_year']."' AND d.`session`='".$data['session']."' 
INNER JOIN subjects e ON e.id=a.sub_id
INNER JOIN cs_branches f ON f.id=d.branch_id
INNER JOIN cs_courses g ON g.id=d.course_id
WHERE b.session_year='".$data['session_year']."' AND b.`session`='".$data['session']."' AND b.hod_status='1' AND b.acad_status='1' AND c.emp_no='".$emp_id."'
AND e.type!='Practical' AND e.type<>'Non-contact') ");

                        
		$result= $query->result();
		//echo $this->db->last_query();die();
		return $result;
	
	}
        
        
        
        //=================================================================================================

	public function get_prac_subjects($data)
	{
		$emp_id=$this->session->userdata('id');
		$session_year=$data['session_year'];
		$session=$data['session'];
		$this->load->database();
	

			$query= $this->db->query("SELECT subject_mapping.map_id as map_id,subject_mapping.semester as semester, subjects.subject_id as sub_code, subjects.name as sub_name ,subjects.id as sub_id, subject_mapping.branch_id as branch_id ,subject_mapping.group,subject_mapping.section,subject_mapping.aggr_id,
			 cs_branches.name as branch_name,subject_mapping.course_id as course_id, 
			 cs_courses.name as course_name, subject_mapping_des.coordinator as coordinator,subjects.type as sub_type
			FROM subject_mapping 
			INNER JOIN subject_mapping_des ON subject_mapping_des.map_id = subject_mapping.map_id
			INNER JOIN subjects ON subjects.id = subject_mapping_des.sub_id
			INNER JOIN cs_branches ON subject_mapping.branch_id=cs_branches.id
			INNER JOIN cs_courses ON subject_mapping.course_id=cs_courses.id
			WHERE session_year='$session_year' and session='$session' and emp_no=$emp_id and type='Practical' AND course_id<>'jrf'  union  (SELECT p.map_id AS map_id,p.semester AS semester,p.sub_code,p.sub_name ,p.sub_id, 
p.branch_id,p.group,'0'as section,p.aggr_id,p. branch_name,
p.course_id , p.course_name, 
p.coordinator,p.type AS sub_type

from
(
SELECT x.sub_code,x.sub_name,x.sub_id,x.semester,x.group,x.branch_name, x.course_name,x.branch_id,x.course_id,x.aggr_id,y.map_id,
z.coordinator,x.type
FROM (
SELECT DISTINCT e.subject_id AS sub_code, e.name AS sub_name, e.id AS sub_id, d.semester, '0' AS 'group', 
g.name AS branch_name, c.name AS course_name, b.branch_id,b.course_id, a.honours_agg_id AS aggr_id, 
c.duration, b.session_year,b.`session`,e.`type`
FROM hm_form a
INNER JOIN reg_regular_form b ON a.admn_no=b.admn_no
INNER JOIN cs_courses c ON c.id=b.course_id
INNER JOIN course_structure d ON d.aggr_id=a.honours_agg_id
INNER JOIN subjects e ON e.id=d.id
INNER JOIN stu_academic f ON f.admn_no=b.admn_no
INNER JOIN cs_branches g ON g.id=f.branch_id
WHERE a.dept_id='".$this->session->userdata('dept_id')."' AND c.duration=5 AND b.session_year='".$data['session_year']."' AND b.`session`='".$data['session']."' AND d.semester=b.semester)x
INNER JOIN subject_mapping y ON (y.session_year=x.session_year AND y.`session`=x.session AND y.aggr_id=x.aggr_id AND y.semester=x.semester)
INNER JOIN subject_mapping_des z ON z.map_id=y.map_id AND z.sub_id=x.sub_id AND z.emp_no='".$emp_id."')p
where p.course_id <> 'jrf' AND p.type='Practical' 
AND p.type<>'Non-contact')");

		$result= $query->result();
		
		return $result;
	
	}
        
        //=============================JRF=====================================================================
        
        public function get_prac_subjects_jrf($data)
	{
		$emp_id=$this->session->userdata('id');
		$session_year=$data['session_year'];
		$session=$data['session'];
		$this->load->database();
	

			$query= $this->db->query("SELECT subject_mapping.map_id as map_id,subject_mapping.semester as semester, subjects.subject_id as sub_code, subjects.name as sub_name ,subjects.id as sub_id, subject_mapping.branch_id as branch_id ,subject_mapping.group,subject_mapping.section,subject_mapping.aggr_id,
			 cs_branches.name as branch_name,subject_mapping.course_id as course_id, 
			 cs_courses.name as course_name, subject_mapping_des.coordinator as coordinator,subjects.type as sub_type
			FROM subject_mapping 
			INNER JOIN subject_mapping_des ON subject_mapping_des.map_id = subject_mapping.map_id
			INNER JOIN subjects ON subjects.id = subject_mapping_des.sub_id
			INNER JOIN cs_branches ON subject_mapping.branch_id=cs_branches.id
			INNER JOIN cs_courses ON subject_mapping.course_id=cs_courses.id
			WHERE session_year='$session_year' and session='$session' and emp_no=$emp_id and type='Practical' AND course_id='jrf' union  (SELECT d.map_id,
d.semester,
e.subject_id AS sub_code,
e.name AS sub_name,
a.sub_id, 
d.branch_id,
d.`group`,
d.section ,
d.aggr_id,
f.name AS branch_name,
d.course_id,
 g.name AS course_name,
 c.coordinator,
 e.`type` AS sub_type
FROM reg_exam_rc_subject a
INNER JOIN reg_exam_rc_form b ON b.form_id=a.form_id
INNER JOIN subject_mapping_des c ON c.sub_id=a.sub_id
INNER JOIN subject_mapping d ON d.map_id=c.map_id AND d.session_year='".$data['session_year']."' AND d.`session`='".$data['session']."'
INNER JOIN subjects e ON e.id=a.sub_id
INNER JOIN cs_branches f ON f.id=d.branch_id
INNER JOIN cs_courses g ON g.id=d.course_id
WHERE b.session_year='".$data['session_year']."' AND b.`session`='".$data['session']."' AND b.hod_status='1' AND b.acad_status='1' AND c.emp_no='".$emp_id."'
AND e.type='Practical' AND e.type<>'Non-contact')");

		$result= $query->result();
		
		return $result;
	
	}
        
        
        
        //========================================================================================================

	public function get_branch($data,$subject)
	{
		$emp_id=$data['emp_id'];
		$this->load->database();
		$query=$this->db->query("SELECT DISTINCT branch_id
								FROM sub_mapping 
								INNER JOIN (SELECT map_id 
											FROM sub_mapping_des
											WHERE teacher_id = '$emp_id' AND subject_id='$subject') AS t
								ON t.map_id = sub_mapping.map_id ;");
		$branch_id=array();
		return $query->result();
	}

	public function get_branch_name($branch_id)
	{
		$branch_id_arr=array();
		for($i=0;$i<count($branch_id);$i++)
			$branch_id_arr[$i]=$branch_id[$i]->branch_id;
		$this->load->database();
		$this->db->select('id,name');
		$this->db->from('departments');
		$this->db->where_in('id',$branch_id_arr);
		$query=$this->db->get();
		return $query->result();
	}

	public function get_branch_name_again($branch_id)
	{
		$this->load->database();
		$this->db->select('name');
		$this->db->from('departments');
		$this->db->where_in('id',$branch_id);
		$query=$this->db->get();
		return $query->result();
	}

	public function get_record($admn_no,$map_id,$sub_id,$date,$session_id,$group_no,$class_no)
	{
       	$this->load->database();
       	$query="SELECT * FROM absent_table WHERE admn_no='$admn_no' AND map_id='$map_id' AND sub_id='$sub_id' AND date='$date' AND session_id='$session_id' AND group_no='$group_no' AND class_no='$class_no';";
   		$query=$this->db->query($query);
               // echo $this->db->last_query(); die();
   		return $query->result();
	}
        
        public function get_record_total_absent($admn_no,$map_id,$sub_id,$date,$session_id,$group_no,$class_no)
	{
       	$this->load->database();
       	$query="SELECT count(admn_no) as counter FROM absent_table WHERE admn_no='$admn_no' AND map_id='$map_id' AND sub_id='$sub_id' AND session_id='$session_id' AND group_no='$group_no' AND class_no='$class_no';";
   		$query=$this->db->query($query);
               // echo $this->db->last_query(); die();
   		return $query->result();
	}
                
                

	public function check_absent_table($admn_no,$map_id,$sub_id,$session_id,$date)
	{
		$this->load->database();
       	$query="SELECT Remark FROM absent_table WHERE admn_no='$admn_no' AND
       	                                         map_id=$map_id AND sub_id='$sub_id'
       	                                         AND date='$date' AND  session_id=$session_id ";
   		$query=$this->db->query($query);
   		
   		return $query->result();

	}

	public function update_absent_table($admn_no,$map_id,$sub_id,$session_id,$date,$remark)
	{
		$this->load->database();
		$query="UPDATE absent_table SET Remark='$remark' WHERE admn_no='$admn_no' AND map_id=$map_id AND sub_id='$sub_id' AND session_id=$session_id AND date='$date' AND status=0";
		$query=$this->db->query($query);
		//return $query->result();
	}

	public function insert_into_Attendance_remark_table($admn_no,$count,$remark,$session_id,$sub_id,$group_no)
	{
		$this->load->database();
		$query="INSERT INTO  Attendance_remark_table(admn_no,count,Remark,session_id,sub_id,group_no) VALUES('$admn_no',$count,'$remark',$session_id,'$sub_id',$group_no)";
                $this->db->query($query);
              
	}

	public function get_record_from_attendance_remark_table($admn_no,$sub_id,$session_id,$group_no)
	{
		$this->load->database();
		//$query="SELECT count,Remark FROM Attendance_remark_table WHERE admn_no='$admn_no' AND sub_id='$sub_id' AND session_id='$session_id' AND group_no='$group_no' limit 1";
                $query="SELECT sum(count)as count,group_concat(Remark) as Remark FROM Attendance_remark_table WHERE 
admn_no='".$admn_no."' AND sub_id='".$sub_id."' AND session_id='".$session_id."' AND group_no='".$group_no."' 
group by admn_no,sub_id,session_id,group_no";
		$query=$this->db->query($query);
               //echo $this->db->last_query();
		return $query->row();
	}

	public function get_check_status($admn_no,$map_id,$sub_id,$session_id,$group_no)
	{

		$this->load->database();
		//$query="SELECT status FROM absent_table WHERE admn_no='$admn_no' AND map_id='$map_id' AND sub_id='$sub_id' AND session_id='$session_id' AND group_no='$group_no'";
		$query="SELECT status FROM absent_table WHERE admn_no='".$admn_no."' AND map_id='".$map_id."' AND sub_id='".$sub_id."' AND session_id='".$session_id."' AND group_no='".$group_no."'";
		$query=$this->db->query($query);
		return $query->result();
	}
        
        public function view_attendance_model_comm_count($data,$sub_id,$group_no,$map_id)
	{

		 $stu_id = $data['stu_id'];
		 $session_year  =$data['session_year'];
		 $semester  =$data['semester'];
		 $session = $data['session'];
			$temp1= $this->db->query("SELECT  count(admn_no) as qq,admn_no
					                 FROM absent_table
					                 WHERE sub_id  = '$sub_id'
					                
					                 AND map_id = '$map_id' 
					                 AND group_no ='$group_no' group by admn_no ");
			$temp= $temp1->result();

			//echo $this->db->last_query(); die();
			
			return $temp;
	}
        public function view_attendance_model_comm_count_dd_hons($data,$sub_id,$group_no,$map_id)
	{

		 $stu_id = $data['stu_id'];
		 $session_year  =$data['session_year'];
		 $semester  =$data['semester'];
		 $session = $data['session'];
			$temp1= $this->db->query("SELECT  count(admn_no) as qq,admn_no
					                 FROM absent_table_dd_hons
					                 WHERE sub_id  = '$sub_id'
					                
					                 AND map_id = '$map_id' 
					                 AND group_no ='$group_no' group by admn_no ");
			$temp= $temp1->result();

			//echo $this->db->last_query(); die();
			
			return $temp;
	}
        
        
        public function view_attendance_model_comm($data,$sub_id,$group_no,$map_id)
	{

		 $stu_id = $data['stu_id'];
		 $session_year  =$data['session_year'];
		 $semester  =$data['semester'];
		 $session = $data['session'];
		
                       
		
	//
			$temp1= $this->db->query("SELECT  date
					                 FROM absent_table
					                 WHERE sub_id  = '$sub_id'
					                 AND admn_no = '$stu_id'
					                 AND map_id = '$map_id' 
					                 AND group_no ='$group_no' ");
			$temp= $temp1->result();
			//$count = count($temp);
			return $temp;
		


	
	}
        
        public function view_attendance_model($data,$sub_id,$group_no)
	{

		 $stu_id = $data['stu_id'];
		 $session_year  =$data['session_year'];
		 $semester  =$data['semester'];
		 $session = $data['session'];
		 $subject_id  = array();

		//print_r($data);
		/*foreach ($result['sub_name'] as $row) 
		{
			$subject[$i ++] = $row->id;
		}*/
		//print_r($subject);

		$temp = $this->db->query("SELECT DISTINCT course_id , branch_id
		                         FROM reg_regular_form
		                         WHERE semester = $semester
		                         AND session_year = '$session_year'
		                         AND session = '$session'
		                         AND admn_no = '$stu_id' ");
		$temp = $temp->result();
	//	print_r($temp);
		$course_id = $temp[0]->course_id;
		$branch_id = $temp[0]->branch_id;
		//echo $course_id;
		//echo $branch_id;
		$temp = $this->db->query("SELECT DISTINCT map_id
				                 FROM subject_mapping
				                 WHERE session = '$session'
				                 AND session_year = '$session_year'
				                 AND course_id = '$course_id'
				                 AND branch_id = '$branch_id'
				                 AND semester = '$semester'  ");
		$temp = $temp->result();
		//print_r($temp);
		$map_id = $temp[0]->map_id;

		//for ($i= 0;$i < count($subject);$i++)
		//{
		//echo $map_id;
			$temp1= $this->db->query("SELECT  date
					                 FROM absent_table
					                 WHERE sub_id  = '$sub_id'
					                 AND admn_no = '$stu_id'
					                 AND map_id = '$map_id' 
					                 AND group_no ='$group_no' ");
			$temp= $temp1->result();
			/*if($stu_id=='2013JE0102')
			print_r($temp);*/
			return $temp;
		
			//$count = count($temp);
             //$dates=array();
             //$dates[]
			/*$temp2 = $this->db->query("SELECT total_class
									FROM total_class_table
									WHERE map_id = $map_id
									AND sub_id = '$sub_id' ");
			$temp3 = $temp2->result();
			//echo $temp3->total;
			//print_r($temp3);
			if (count($temp3))
			{
				$temp['total']  = $temp3[0]->total_class ;
			}
			else
			{
			 	$temp[$i]['total'] = 'class not started';
			}
		//}*/

		//return $count;


	
	}
	public function view_attendance_model_count($data,$sub_id,$group_no)
	{

		 $stu_id = $data['stu_id'];
		 $session_year  =$data['session_year'];
		 $semester  =$data['semester'];
		 $session = $data['session'];
		$subject_id  = array();

		//print_r($data);
		/*foreach ($result['sub_name'] as $row) 
		{
			$subject[$i ++] = $row->id;
		}*/
		//print_r($subject);

		$temp = $this->db->query("SELECT DISTINCT course_id , branch_id
		                         FROM reg_regular_form
		                         WHERE semester = $semester
		                         AND session_year = '$session_year'
		                         AND session = '$session'
		                         AND admn_no = '$stu_id' ");
		$temp = $temp->result();
	//	print_r($temp);
		$course_id = $temp[0]->course_id;
		$branch_id = $temp[0]->branch_id;
		//echo $course_id;
		//echo $branch_id;
		$temp = $this->db->query("SELECT DISTINCT map_id
				                 FROM subject_mapping
				                 WHERE session = '$session'
				                 AND session_year = '$session_year'
				                 AND course_id = '$course_id'
				                 AND branch_id = '$branch_id'
				                 AND semester = '$semester'  ");
		$temp = $temp->result();
		//print_r($temp);
		$map_id = $temp[0]->map_id;

		//for ($i= 0;$i < count($subject);$i++)
		//{
		//echo $map_id;
			$temp1= $this->db->query("SELECT count(date) as qq
					                 FROM absent_table
					                 WHERE sub_id  = '$sub_id'
					                 AND admn_no = '$stu_id'
					                 AND map_id = '$map_id' 
					                 AND group_no ='$group_no' ");
			//$temp= $temp1->result();
			/*if($stu_id=='2013JE0102')
			print_r($temp);*/
			//return $temp;
		$temp= $temp1->row();
		
			$count = $temp->qq;
            // $dates=array();
            // die();
             //$dates[]
			/*$temp2 = $this->db->query("SELECT total_class
									FROM total_class_table
									WHERE map_id = $map_id
									AND sub_id = '$sub_id' ");
			$temp3 = $temp2->result();
			//echo $temp3->total;
			//print_r($temp3);
			if (count($temp3))
			{
				$temp['total']  = $temp3[0]->total_class ;
			}
			else
			{
			 	$temp[$i]['total'] = 'class not started';
			}
		//}*/

		return $count;


	
	}
        function check_defaulter_status($admn_no,$sub_id,$map_id,$session_id){
            //$q="select a.* from absent_table a where a.admn_no=? and a.sub_id=? and a.map_id=? and a.session_id=? and a.`status`='1'";
			$q="select a.* from absent_table a where a.admn_no=? and a.sub_id=? and a.map_id=? and a.session_id=? and (a.`status`='1' || a.`status`='2') ";
            $query = $this->db->query($q,array($admn_no,$sub_id,$map_id,$session_id));
            return $query->result();
        }
        function check_defaulter_status_dd_hons($admn_no,$sub_id,$map_id,$session_id){
            $q="select a.* from absent_table_dd_hons a where a.admn_no=? and a.sub_id=? and a.map_id=? and a.session_id=? and a.`status`='1'";
            $query = $this->db->query($q,array($admn_no,$sub_id,$map_id,$session_id));
            return $query->result();
        }
        function get_course_id_duration($id){
            $q="select b.duration from stu_academic a inner join cs_courses b on b.id=a.course_id where a.admn_no=?";
            $query = $this->db->query($q,array($id));
            return $query->row();
        }
        function check_crs_duration($crs) {
        //echo $crs;
        $this->load->model('student_sem_form/sbasic_model', 'sb', true);
        $x = $this->sb->getCourseDurationByIdCS(strtolower($crs));
        if (!empty($x)) {
            return $x;
        }
        return 0;
    }
	function view_attendance_model_cbcs($data){
		
		if($data[course_id]=='comm'){
			$q="SELECT a.date FROM cbcs_class_engaged a
	left JOIN cbcs_absent_table b ON b.class_engaged_id=a.id
	WHERE a.subject_offered_id=? AND a.group_no=? AND a.section=? GROUP BY a.total_class order by a.date ";
	            $query = $this->db->query($q,array($data['soid'],$data['ee_group'],$data['section']));
	            return $query->result();


		}else{
			$q="SELECT a.date FROM cbcs_class_engaged a
	left JOIN cbcs_absent_table b ON b.class_engaged_id=a.id
	WHERE a.subject_offered_id=? AND a.group_no=? GROUP BY a.total_class order by a.date ";
	            $query = $this->db->query($q,array($data['soid'],$data['ee_group']));
	            return $query->result();

		}
		
		/*$q="SELECT a.date FROM cbcs_class_engaged a
left JOIN cbcs_absent_table b ON b.class_engaged_id=a.id
WHERE a.subject_offered_id=? AND a.group_no=? GROUP BY a.total_class order by a.date ";
            $query = $this->db->query($q,array($data['soid'],$data['ee_group']));
            return $query->result();*/
		
	}
	function view_attendance_model_cbcs_absent($data,$admn_no){
		if($data[course_id]=='comm'){
			$q="SELECT a.date FROM cbcs_class_engaged a
inner JOIN cbcs_absent_table b ON b.class_engaged_id=a.id
WHERE a.subject_offered_id=? AND a.group_no=? and b.admn_no=? AND a.section=? ;";
            $query = $this->db->query($q,array($data['soid'],$data['ee_group'],$admn_no,$data['section']));
            return $query->result();
		}else{
			
			$q="SELECT a.date FROM cbcs_class_engaged a
inner JOIN cbcs_absent_table b ON b.class_engaged_id=a.id
WHERE a.subject_offered_id=? AND a.group_no=? and b.admn_no=? ;";
            $query = $this->db->query($q,array($data['soid'],$data['ee_group'],$admn_no));
            return $query->result();
			
			
		}
		
		
	}
	
}
?>