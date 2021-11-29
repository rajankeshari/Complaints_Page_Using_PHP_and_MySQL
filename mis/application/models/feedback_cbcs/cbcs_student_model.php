<?php
class Cbcs_student_model extends CI_Model
{

	public function get_sem_form_id($data)
	{
		$session_year = $data['session_year'];
		$session = $data['session'];
		$admission_no = $data['admission_no'];
		$basic_query = " SELECT form_id,course_id,branch_id,semester,session,course_aggr_id FROM reg_regular_form WHERE session_year = '$session_year' AND session = '$session' AND admn_no = '$admission_no' AND hod_status = '1' and acad_status='1' ";
		return $this->db->query($basic_query)->result();
	}


	public function get_subject_id_by_sem_form_id($data)
	{
		$sem_form_id = $data['sem_form_id'];
                $sy=$data['session_year'];
                $sess=$data['session'];


                $basic_query = "
SELECT a.sub_id
FROM

 (SELECT reg_regular_elective_opted.sub_id,subjects.subject_id
FROM reg_regular_elective_opted
inner join subjects on reg_regular_elective_opted.sub_id=subjects.id where  form_id ='".$sem_form_id."') a
 inner join

 (select b.faculty_id,b.sub_id  ,subjects.subject_id,cs.aggr_id from  fb_student_subject_main c
 inner join fb_student_subject_desc b on c.id=b.main_id inner join subjects on b.sub_id=subjects.id
 inner join course_structure cs on cs.id=subjects.id
 and c.session_year='".$sy."' and c.`session`='".$sess."'  and c.admn_no='".$this->session->userdata('id')."'

 )y
 on   /*y.sub_id=a.sub_id*/   y.subject_id=a.subject_id




inner join

(select e.map_id , d.sub_id,d.emp_no,subjects.subject_id,e.aggr_id from
(select map_id,aggr_id from  subject_mapping where session_year='".$sy."' and `session`='".$sess."'  ) e
 inner join subject_mapping_des d on   e.map_id=d.map_id
 inner join subjects on d.sub_id=subjects.id
)x
  on /*x.sub_id=a.sub_id */  x.subject_id=a.subject_id and y.aggr_id=x.aggr_id and y.faculty_id=x.emp_no ";




                return $this->db->query($basic_query)->result(); //echo $this->db->last_query(); die();
	}

	public function get_map_id_by_sub_id($sub_id,$sy,$sess,$sec=null)
	{

            if($sec==null){


                $sql="select x.*,y.* from (select c.sub_id,c.faculty_id,b.subject_id,cs.aggr_id from fb_student_subject_main a
inner join fb_student_subject_desc c on c.main_id=a.id inner join subjects b on b.id=c.sub_id
inner join course_structure cs on cs.id=b.id
where a.session_year=? and a.`session`=?
and a.admn_no=? and a.semester=?  and b.elective<>0) x
inner join
(SELECT a.map_id,a.emp_no AS teacher_id, subjects.subject_id,b.aggr_id
FROM subject_mapping_des a
INNER JOIN subject_mapping b ON a.map_id=b.map_id
INNER JOIN subjects ON  a.sub_id =subjects.id
WHERE  b.session_year=?  AND b.`session`=?   and subjects.elective<>0 and a.emp_no<>0
 )y
  on x.subject_id=y.subject_id and y.aggr_id=x.aggr_id and x.faculty_id=y.teacher_id ";
             $query = $this->db->query($sql,array($sy,$sess,$this->session->userdata('id'),$this->session->userdata('semester'),$sy,$sess));

            }else{
                 $sql = "SELECT a.map_id,a.emp_no AS teacher_id,a.sub_id FROM subject_mapping_des a
inner join subject_mapping b on a.map_id=b.map_id

inner join fb_student_subject_desc c on c.sub_id=a.sub_id
inner join fb_student_subject_main d on d.id=c.main_id and d.session_year=b.session_year
and d.`session`=b.`session` and d.semester=b.semester
WHERE a.sub_id = ? and b.session_year=? and b.`session`=? and b.section=?  and a.emp_no<>0 group by a.map_id,a.emp_no";

        $query = $this->db->query($sql,array($sub_id,$sy,$sess,$sec));
            }

       //echo $this->db->last_query(); die();
        if ($this->db->affected_rows() >= 0) {
            return $query->result();
        } else {
            return false;
        }

	}
	public function get_user_name_by_user_id($id)
	{
		$basic_query = " SELECT salutation,concat(first_name,' ',middle_name,' ',last_name) AS name FROM user_details WHERE id = '$id' ";
		return $this->db->query($basic_query)->result();
	}
	public function get_sub_name_and_actual_sub_id($sub_id)
	{
		$basic_query = " SELECT subject_id,name FROM subjects WHERE id = '$sub_id' ";
		return $this->db->query($basic_query)->result();
	}
	public function get_group_details_fbs()
	{
		$basic_query = " SELECT * FROM fbs_group WHERE status = '1' ";
		return $this->db->query($basic_query)->result();
	}
	public function get_parameter_details_fbs()
	{
		$basic_query = " SELECT * FROM fbs_parameter WHERE status = '1' ";
		return $this->db->query($basic_query)->result();
	}

	public function check_comment_status_fbs()
	{
		$basic_query = " SELECT value FROM fb_details WHERE description = 'fbs_comment' ";
		$status = $this->db->query($basic_query)->result();
		return $status[0]->value;
	}
	public function insert_feedback_fbs($data)
	{
		$gpa = ceil($data['gpa']);
		$lock_table_query = " LOCK TABLES fbs_feedback_details WRITE,fbs_feedback_details AS t READ ";
		$this->db->query($lock_table_query);


		$count_query = " SELECT count(*) AS total_count FROM fbs_feedback_details AS t";
		$temp_count = $this->db->query($count_query)->result();
		if ($temp_count[0]->total_count == 0)
			$count = 0;
		else{
			$temp_query = " SELECT MAX(feedback_id) AS cur_feedback_id FROM fbs_feedback_details AS t";
			$temp_count = $this->db->query($temp_query)->result();
			$count = $temp_count[0]->cur_feedback_id;
		}

		$basic_query = " INSERT INTO fbs_feedback_details (feedback_id,map_id,subject_id,teacher_id,gpa";
		if ($data['comment_status'] == 1)
			$basic_query .= ",comment) VALUES";
		else
			$basic_query .= ") VALUES";

		for ($i = 1; $i <= $data['count_subject']; $i++)
		{
			$map_id = $data['map_id'][$i];
			$subject_id = $data['subject_id'][$i];
			$teacher_id = $data['teacher_id'][$i];
			$feedback_id = $count + $i;
			$basic_query .= " ('$feedback_id','$map_id','$subject_id','$teacher_id','$gpa'";

			if ($data['comment_status'] == 1){
				$comment = $data['comment'][$i];
				$basic_query .= ",'$comment'";
			}
			$basic_query .= ")";
			if ($i < $data['count_subject'])
				$basic_query .= ",";
		}
		$this->db->query($basic_query);
		$temp_query = " SELECT MAX(feedback_id) AS cur_feedback_id FROM fbs_feedback_details ";
		$result = $this->db->query($temp_query)->result();
		$max_id = $result[0]->cur_feedback_id;

		$unlock_table_query = " UNLOCK TABLES ";
		$this->db->query($unlock_table_query);


		$flag = 0;
		$basic_query = " INSERT INTO fbs_feedback (feedback_id,parameter_no,rating) VALUES ";

		for ($i = 1; $i <= $data['count_subject']; $i++)
		{
			$max_id = $count + $i;
			for ($j = 1; $j <= $data['count_group']; $j++)
			{
				for ($k = 1; $k <= $data['count_parameter']; $k++)
				{
					if ($data['group'][$j] == $data['parameter_group'][$k])
					{
						$parameter_no = $data['parameter'][$k];
						$rating = $data[$i][$j][$k];
						if ($flag == 0)
						{
							$basic_query .= "('$max_id','$parameter_no','$rating') ";
							$flag = 1;
						}
						else
							$basic_query .= ",('$max_id','$parameter_no','$rating') ";
					}
				}
			}
		}
		$this->db->query($basic_query);
	}
	public function get_feedback_submitted_subjects_fbs($admn_no,$syear,$sess)
	{


		//$column = 'semester_'.$semester;
		$basic_query = " SELECT feedback_papers FROM fb_student_feedback_cbcs WHERE admn_no = '$admn_no' and session_year='$syear' and session='$sess' ";

		return  $this->db->query($basic_query)->result();

	}
	public function insert_ref_id($data)
	{
		$semester=$data['semester'];
		$admn_no=$data['admn_no'];
		$subjects=$this->get_feedback_submitted_subjects_fbs($semester,$admn_no);
		$ref_id=$data['ref_id'];
		$date=date("Y/m/d");
		$query="INSERT INTO `fbs_ref_id`(`ref_id`, `admn_no`, `semester`, `date`) VALUES ('$ref_id','$admn_no','$semester','$date')";
		$this->db->query($query);
		//return $lol;
	}
	public function confirm_feedback_submission_fbs($data)
	{
		$curr_status = $this->cbcs_check_feedback_subjects_model->get_student_latest_status($this->session->userdata('id'));

		$string = $data['session_year'].",".$data['session'].",";
		for ($i = 1; $i <= $data['count_subject']; $i++)
		{
			$flag = true;
			for ($j = 1; $j < $i; $j++)
				if ($data['subject_id'][$i] == $data['subject_id'][$j])
					$flag = false;
			if ($flag == true)
				$string .= $data['subject_id'][$i].",";
		}
		$column = "semester_".$data['semester'];
		$admn_no = $data['admn_no'];

		$data1['session_year']=$data['session_year'];
		$data1['session']=$data['session'];
		$data1['admn_no']=$data['admn_no'];
		$data1['course_id']=$curr_status->course_id;
		$data1['branch_id']=$curr_status->branch_id;
		$data1['semester']=$data['semester'];
		$data1['feedback_type']="sem_feedback";
		$data1['feedback_papers']=$string;
		$this->db->insert('fb_student_feedback_cbcs',$data1);

		//$basic_query = " UPDATE fb_student_feedback SET $column = '$string' WHERE admn_no = '$admn_no'";
		//$this->db->query($basic_query);
	}
	public function check_available_fbs($admn_no,$semester)
	{
		$basic_query = " SELECT value FROM fb_details WHERE description = 'fbs_feedback' ";
		$result = $this->db->query($basic_query)->result();
		$column = 'semester_'.$semester;
		$basic_query = " SELECT $column FROM fb_student_feedback WHERE admn_no = '$admn_no' ";
		$result1 = $this->db->query($basic_query)->result();

		if ($this->db->query($basic_query)->num_rows() != 0 && $result[0]->value == 1 && $result1[0]->$column == NULL && $result1[0]->$column == "")
			return 1;
		else if ($this->db->query($basic_query)->num_rows() != 0 && $result1[0]->$column != NULL && strlen($result1[0]->$column) < 20)
			return 3;
		else if ($this->db->query($basic_query)->num_rows() != 0 && $result1[0]->$column != NULL)
			return 2;
		else
			return 0;
	}

	public function set_to_null($admn_no, $semester)
	{
		$column = 'semester_'.$semester;
		$basic_query = "UPDATE fb_student_feedback SET $column = NULL WHERE admn_no = '$admn_no' ";
		$this->db->query($basic_query);
	}

	public function insert_error_report($feedback_type,$admn_no,$session,$session_year,$semester,$startTimeForSubmission)
	{
		$insertionTime = date("Y-m-d H:i:s",time());
		$basic_query = " INSERT INTO fb_error_log (feedback_type,admn_no,session,session_year,semester,start_time,insertion_time) VALUES ('$feedback_type','$admn_no','$session','$session_year','$semester','$startTimeForSubmission','$insertionTime') ";
		$this->db->query($basic_query);
	}

	public function get_subject_details($subject)
	{
		$basic_query = " SELECT subject_id,name FROM subjects WHERE id = '$subject' ";
		echo $basic_query ;
		return $this->db->query($basic_query)->result();
	}
	public function get_subject_details_cbcs($subject,$form_id,$admn_no)
	{
		$tmp=explode('_',$subject);
		if($tmp[0]=='c'){ $tbl="cbcs_stu_course";}
		if($tmp[0]=='o'){ $tbl="old_stu_course";}
		$basic_query = " SELECT * FROM ".$tbl." WHERE form_id=".$form_id." AND admn_no='".$admn_no."' AND subject_code='".$tmp[1]."'";

		return $this->db->query($basic_query)->result();
	}
	public function get_course_type_of_user($id)
	{
		$basic_query = " SELECT stu_type FROM stu_details WHERE admn_no = '$id' ";
		return $this->db->query($basic_query)->result();
	}
	public function get_section($id,$session_year)
	{
		$basic_query = " SELECT section FROM stu_section_data WHERE admn_no = '$id' AND session_year = '$session_year' ";
		$result = $this->db->query($basic_query)->result();
		if(count($result)==0)
		{
			return NULL;
		}
		else
		return $result[0]->section;
	}
	public function get_compulsory_subject($data)
	{

		$course_id = $data['course_id'];
		$branch_id = $data['branch_id'];
		$session_year = $data['session_year'];
		$session = $data['session'];
		$semester = $data['semester'];
		$type = $data['type'];
		$dept_id = $data['dept_id'];
		$section = $data['section'];
		$aggr_id = $data['course_aggr_id'];


		if (($type == 'ug' || $type == 'prep') && $semester <= 2 && $aggr_id!='be_pe_2017_2018')
		{

			//if dept_id is considered as comm then ->
			$map_id_query = " SELECT map_id FROM subject_mapping WHERE dept_id = 'comm' AND branch_id = 'comm' AND course_id = 'comm' AND session_year = '$session_year' AND session = '$session' AND semester = '$semester' AND section = '$section' AND aggr_id = '$aggr_id' ";
			//else if dept_id is considered as actual dept_id then ->

			/*$map_id_query = " SELECT map_id FROM subject_mapping WHERE dept_id = '$dept_id' AND branch_id = 'comm' AND course_id = 'comm' AND session_year = '$session_year' AND session = '$session' AND semester = '$semester' AND section = '$section' AND aggr_id = '$aggr_id' ";*/

		}
		else
			$map_id_query = " SELECT map_id FROM subject_mapping WHERE dept_id = '$dept_id' AND branch_id = '$branch_id' AND course_id = '$course_id' AND session_year = '$session_year' AND session = '$session' AND semester = '$semester' AND aggr_id = '$aggr_id' AND (NOT course_id = 'honour') ";

		$map_id_result = $this->db->query($map_id_query)->result();

		if($this->db->query($map_id_query)->num_rows()==0)
			{
				return NULL;
			}
		$map_id = $map_id_result[0]->map_id;
                $map_details=$this->get_subject_mapid($map_id);

                if($map_details->course_id=='comm'){
                    $sec=$this->get_section($data['admission_no'],$session_year);

                    /*$subject_query="SELECT map.map_id AS map_id,map.sub_id AS subject_id, c.faculty_id AS emp_id,subjects.subject_id AS real_subject_id,subjects.name AS name
FROM subject_mapping_des AS map
INNER JOIN subjects ON map.sub_id = subjects.id
inner join subject_mapping a on a.map_id=map.map_id
inner join fb_student_subject_main b on (b.session_year=a.session_year and b.`session`=a.`session` and a.course_id='comm' and a.branch_id='comm' and b.semester=a.semester)
inner join fb_student_subject_desc c on c.main_id=b.id
inner join reg_regular_form d on d.admn_no=b.admn_no and  b.session_year=d.session_year and b.`session`=d.`session` and a.semester=d.semester and a.group=d.section and a.section='".$sec."'
WHERE map.map_id = '".$map_id."' AND subjects.elective = 0 AND (subjects.type = 'Theory' OR subjects.type = 'Sessional')
and b.admn_no='".$data['admission_no']."' and c.sub_id=map.sub_id
group by c.sub_id,c.faculty_id";*/
//query modified by @rituraj on 4dec17
$subject_query="
SELECT map.map_id AS map_id,map.sub_id AS subject_id, x.faculty_id AS emp_id,subjects.subject_id AS real_subject_id,subjects.name AS name
from
(select map_id,sub_id,emp_no
FROM subject_mapping_des  where  map_id = '".$map_id."')map

INNER JOIN subjects ON map.sub_id = subjects.id  and  subjects.elective = 0 AND (subjects.type = 'Theory' OR subjects.type = 'Sessional')
INNER JOIN subject_mapping a ON a.map_id=map.map_id AND a.section='".$sec."'
inner join
(select c.sub_id,c.faculty_id,b.session_year,b.`session`,b.admn_no ,b.course_id,b.branch_id,b.semester from  fb_student_subject_main b join fb_student_subject_desc c
on  c.main_id=b.id  and b.admn_no='".$data['admission_no']."' )x

on x.session_year=a.session_year AND x.`session`=a.`session` AND a.course_id='comm' AND a.branch_id='comm'  AND x.semester=a.semester and x.faculty_id=map.emp_no
 AND x.sub_id=map.sub_id
INNER JOIN reg_regular_form d ON d.admn_no=x.admn_no AND x.session_year=d.session_year AND x.`session`=d.`session` AND a.semester=d.semester AND a.group=d.section

GROUP BY x.sub_id,x.faculty_id ";

                }else{
		//$subject_query = " SELECT map.map_id AS map_id,map.sub_id AS subject_id, map.emp_no AS emp_id,subjects.subject_id AS real_subject_id,subjects.name AS name FROM  subject_mapping_des AS map INNER JOIN subjects ON map.sub_id = subjects.id WHERE map.map_id = '$map_id' AND subjects.elective = 0 AND (subjects.type = 'Theory' OR subjects.type = 'Sessional') ";
		/*$subject_query="SELECT map.map_id AS map_id,map.sub_id AS subject_id, c.faculty_id AS emp_id,subjects.subject_id AS real_subject_id,subjects.name AS name
FROM subject_mapping_des AS map
INNER JOIN subjects ON map.sub_id = subjects.id
inner join subject_mapping a on a.map_id=map.map_id
inner join fb_student_subject_main b on (b.session_year=a.session_year and b.`session`=a.`session` and b.course_id=a.course_id and b.branch_id=a.branch_id and b.semester=a.semester)
inner join fb_student_subject_desc c on c.main_id=b.id
WHERE map.map_id = '".$map_id."' AND subjects.elective = 0 AND (subjects.type = 'Theory' OR subjects.type = 'Sessional')
and b.admn_no='".$data['admission_no']."' and c.sub_id=map.sub_id
group by c.sub_id,c.faculty_id";*/

//query modified by @rituraj on 4dec17
$subject_query= " SELECT map.map_id AS map_id,map.sub_id AS subject_id, x.faculty_id AS emp_id,subjects.subject_id AS real_subject_id,subjects.name AS name
from
(select map_id,sub_id, emp_no
FROM subject_mapping_des  where  map_id = '".$map_id."')map
INNER JOIN subjects ON map.sub_id = subjects.id  AND subjects.elective = 0 AND  (subjects.type = 'Theory' OR subjects.type = 'Sessional')
INNER JOIN subject_mapping a ON a.map_id=map.map_id
inner join
(select c.sub_id,c.faculty_id,b.session_year,b.`session`,b.admn_no ,b.course_id,b.branch_id,b.semester from  fb_student_subject_main b join fb_student_subject_desc c on  c.main_id=b.id  and b.admn_no='".$data['admission_no']."' )x
on x.session_year=a.session_year AND x.`session`=a.`session` AND x.course_id=a.course_id AND x.branch_id=a.branch_id AND x.semester=a.semester and x.faculty_id=map.emp_no
  AND x.sub_id=map.sub_id
 GROUP BY x.sub_id,x.faculty_id ";
                }
                return $this->db->query($subject_query)->result();
	}
	public function check_subject_teacher_combination_validity($subject_id,$teacher_id)
	{
		$basic_query = " SELECT * FROM subject_mapping_des WHERE emp_no = '$teacher_id' AND sub_id = '$subject_id' ";
		return $this->db->query($basic_query)->num_rows();
	}
	public function check_honour_subject_taken($admn_no)
	{
		$basic_query = " SELECT honour_hod_status FROM hm_form WHERE admn_no = '$admn_no' ";
		$result = $this->db->query($basic_query)->result();

		if ($this->db->query($basic_query)->num_rows() == 0)
			return 0;
		if ($result[0]->honour_hod_status == 'Y')
			return 1;
		return 0;
	}
	public function check_minor_subject_taken($admn_no)
	{
		$basic_query = " SELECT minor_hod_status FROM hm_form WHERE admn_no = '$admn_no' ";
		$result = $this->db->query($basic_query)->result();
                //echo $this->db->last_query();die();
		if ($this->db->query($basic_query)->num_rows() == 0)
			return false;
		if ($result[0]->minor_hod_status == 'Y')
			return true;
		return false;
	}
	public function get_minor_form_id($admn_no)
	{
		$basic_query = " SELECT form_id FROM hm_form WHERE admn_no = '$admn_no' ";
		$result = $this->db->query($basic_query)->result();
		return $result[0]->form_id;
	}
	public function get_minor_aggr_id_by_form_id($minor_form_id)
	{
		$basic_query = " SELECT minor_agg_id FROM hm_minor_details WHERE form_id = '$minor_form_id' AND offered = '1' ";
		$result = $this->db->query($basic_query)->result();
		return $result[0]->minor_agg_id;
	}
	public function get_branch_id_by_admn_no($admn_no)
	{
		$basic_query = " SELECT branch_id FROM stu_academic WHERE admn_no = '$admn_no' ";
		$result = $this->db->query($basic_query)->result();
		return $result[0]->branch_id;
	}
	public function get_admn_year_by_admn_no($admn_no)
	{
		$basic_query = " SELECT admn_date FROM stu_details WHERE admn_no = '$admn_no' ";
		$result = $this->db->query($basic_query)->result();
		$date = DateTime::createFromFormat("Y-m-d", $result[0]->admn_date);
		return $date->format("Y");
	}
	public function get_honour_subject($aggr_id,$semester, $admn_year_session)
	{
		//$basic_query = " SELECT map_id FROM subject_mapping WHERE branch_id = '$aggr_id' AND semester = '$semester' AND session_year = '$admn_year_session' AND course_id='honour' order by map_id desc limit 1";
	$basic_query = " SELECT map_id FROM subject_mapping WHERE aggr_id = '$aggr_id' AND semester = '$semester' AND session_year = '$admn_year_session' AND course_id='honour' order by map_id desc limit 1 ";
            $result = $this->db->query($basic_query)->result();
               // echo $this->db->last_query();die();
		if ($this->db->query($basic_query)->num_rows() == 0)
			return $arr = array();
		$map_id = $result[0]->map_id;

		//$subject_query = " SELECT map.map_id AS map_id,map.sub_id AS subject_id, map.emp_no AS emp_id,subjects.subject_id AS real_subject_id,subjects.name AS name FROM  subject_mapping_des AS map INNER JOIN subjects ON map.sub_id = subjects.id WHERE map.map_id = '$map_id' AND subjects.elective = 0 AND (subjects.type = 'Theory' OR subjects.type = 'Sessional') ";


		/*$subject_query="SELECT map.map_id AS map_id,map.sub_id AS subject_id, c.faculty_id AS emp_id,subjects.subject_id AS real_subject_id,subjects.name AS name
FROM subject_mapping_des AS map
INNER JOIN subjects ON map.sub_id = subjects.id
INNER JOIN subject_mapping a ON a.map_id=map.map_id
INNER JOIN fb_student_subject_main b ON
(b.session_year=a.session_year AND b.`session`=a.`session` AND a.course_id='honour' AND b.branch_id=a.branch_id AND b.semester=a.semester)
INNER JOIN fb_student_subject_desc c ON c.main_id=b.id
WHERE map.map_id = '".$map_id."' AND subjects.elective = 0 AND
(subjects.type = 'Theory' OR subjects.type = 'Sessional') AND b.admn_no='".$this->session->userdata('id')."' AND c.sub_id=map.sub_id
GROUP BY c.sub_id,c.faculty_id";*/

//query modified by @rituraj on 4dec17
$subject_query="
SELECT map.map_id AS map_id,map.sub_id AS subject_id, x.faculty_id AS emp_id,subjects.subject_id AS real_subject_id,subjects.name AS name
FROM (
SELECT map_id,sub_id, emp_no
FROM subject_mapping_des
WHERE map_id = '".$map_id."' )map
INNER JOIN subjects ON map.sub_id = subjects.id AND subjects.elective = 0 AND (subjects.type = 'Theory' OR subjects.type = 'Sessional')
INNER JOIN subject_mapping a ON a.map_id=map.map_id
INNER JOIN (
SELECT c.sub_id,c.faculty_id,b.session_year,b.`session`,b.admn_no,b.course_id,b.branch_id,b.semester
FROM fb_student_subject_main b
JOIN fb_student_subject_desc c ON c.main_id=b.id AND b.admn_no='".$this->session->userdata('id')."')x
ON x.session_year=a.session_year AND x.`session`=a.`session` AND a.course_id='honour' /*AND a.branch_id=(case x.branch_id when 'cse+cse' then 'cse' else x.branch_id end)*/ AND x.semester=a.semester and x.faculty_id=map.emp_no
 AND x.sub_id=map.sub_id
GROUP BY x.sub_id,x.faculty_id";

                return $this->db->query($subject_query)->result();
	}
	public function get_minor_subject($aggr_id,$semester,$admn_year_session)
	{
		$basic_query = " SELECT map_id FROM subject_mapping WHERE aggr_id = '$aggr_id' AND  semester = '$semester' AND session_year = '$admn_year_session' AND course_id='minor' order by map_id desc limit 1";
		$result = $this->db->query($basic_query)->result();
		if ($this->db->query($basic_query)->num_rows() == 0)
			return $arr = array();
		$map_id = $result[0]->map_id;

		//$subject_query = " SELECT map.map_id AS map_id,map.sub_id AS subject_id, map.emp_no AS emp_id,subjects.subject_id AS real_subject_id,subjects.name AS name FROM  subject_mapping_des AS map INNER JOIN subjects ON map.sub_id = subjects.id WHERE map.map_id = '$map_id' AND subjects.elective = 0 AND (subjects.type = 'Theory' OR subjects.type = 'Sessional') ";


		/*$subject_query="SELECT map.map_id AS map_id,map.sub_id AS subject_id, c.faculty_id AS emp_id,subjects.subject_id AS real_subject_id,subjects.name AS name
FROM subject_mapping_des AS map
INNER JOIN subjects ON map.sub_id = subjects.id
INNER JOIN subject_mapping a ON a.map_id=map.map_id
INNER JOIN fb_student_subject_main b ON
b.session_year=a.session_year AND b.`session`=a.`session` AND a.course_id='minor'  AND b.semester=a.semester
INNER JOIN fb_student_subject_desc c ON c.main_id=b.id
WHERE map.map_id = '".$map_id."' AND subjects.elective = 0 AND
(subjects.type = 'Theory' OR subjects.type = 'Sessional') AND b.admn_no='".$this->session->userdata('id')."' AND c.sub_id=map.sub_id
GROUP BY c.sub_id,c.faculty_id";                */

//query modified by @rituraj on 4dec17


$subject_query = "
SELECT map.map_id AS map_id,map.sub_id AS subject_id, x.faculty_id AS emp_id,subjects.subject_id AS real_subject_id,subjects.name AS name
FROM (
SELECT map_id,sub_id, emp_no
FROM subject_mapping_des
WHERE map_id =  '".$map_id."')map
INNER JOIN subjects ON map.sub_id = subjects.id /*AND subjects.elective = 0*/ AND (subjects.type = 'Theory' OR subjects.type = 'Sessional')
INNER JOIN subject_mapping a ON a.map_id=map.map_id
INNER JOIN (
SELECT c.sub_id,c.faculty_id,b.session_year,b.`session`,b.admn_no,b.course_id,b.branch_id,b.semester
FROM fb_student_subject_main b
JOIN fb_student_subject_desc c ON c.main_id=b.id AND b.admn_no='".$this->session->userdata('id')."')x
ON x.session_year=a.session_year AND x.`session`=a.`session` AND a.course_id='minor'  AND x.semester=a.semester and x.faculty_id=map.emp_no
 and x.sub_id=map.sub_id
GROUP BY x.sub_id,x.faculty_id";

		return $this->db->query($subject_query)->result();
	}
	public function get_dept_id_by_user_id($user_id)
	{
		$basic_query = " SELECT dept_id FROM user_details WHERE id = '$user_id' ";
		return $this->db->query($basic_query)->result();
	}
	public function get_course_name_by_course_id($id)
	{
		$basic_query = " SELECT name FROM courses WHERE id = '$id' ";
		return $this->db->query($basic_query)->result();
	}
	public function get_course_duration_by_course_id($id)
	{
		$basic_query = " SELECT duration FROM courses WHERE id = '$id' ";
		$result = $this->db->query($basic_query)->result();
		return $result[0]->duration;
	}
	public function get_branch_name_by_branch_id($id)
	{
		$basic_query = " SELECT name FROM branches WHERE id = '$id' ";
		return $this->db->query($basic_query)->result();
	}
	public function get_group_details_fbr()
	{
		$basic_query = " SELECT * FROM fbr_group ";
		return $this->db->query($basic_query)->result();
	}
	public function get_parameter_details_fbr()
	{
		$basic_query = " SELECT * FROM fbr_parameter ";
		return $this->db->query($basic_query)->result();
	}
	public function check_comment_status_fbr()
	{
		$basic_query = " SELECT value FROM fb_details WHERE description = 'fbr_comment' ";
		$status = $this->db->query($basic_query)->result();
		return $status[0]->value;
	}
	public function get_group_info_fbe()
	{
		$basic_query = " SELECT * FROM fbe_group WHERE status = '1' ";
		return $this->db->query($basic_query)->result();
	}
	public function get_parameter_info_fbe()
	{
		$basic_query = " SELECT * FROM fbe_parameter WHERE status = '1' ";
		return $this->db->query($basic_query)->result();
	}
	public function get_user_name_by_user_id_fbe($id)
	{
		$basic_query = " SELECT salutation,concat(first_name,' ',middle_name,' ',last_name) AS name FROM user_details WHERE id = '$id' ";
		return $this->db->query($basic_query)->result();
	}
	public function get_sem_form_id_fbe($data)
	{
		$session_year = $data['session_year'];
		$session = $data['session'];
		$admission_no = $data['admission_no'];
		$basic_query = " SELECT form_id,course_id,branch_id,semester FROM reg_regular_form WHERE session_year = '$session_year' AND session = '$session' AND admission_id = '$admission_no' ";
		return $this->db->query($basic_query)->result();
	}
	public function get_gpa_fbe()
	{

	}
	public function check_submitted_fbe($admn_no)
	{
		$basic_query = " SELECT exit_feedback FROM fb_student_feedback WHERE admn_no = '$admn_no' ";
		$result = $this->db->query($basic_query)->result();
		return $result[0]->exit_feedback;
	}
	public function insert_feedback_fbe($data)
	{
		$current_year = date("Y");
		$student_id = $data['student_id'];
		$student_details = " SELECT course_id,branch_id FROM stu_academic WHERE admn_no = '$student_id' ";
		$result = $this->db->query($student_details)->result();
		$course_id = $result[0]->course_id;
		$branch_id = $result[0]->branch_id;
		$lock = " LOCK TABLE fbe_batch_details WRITE ,fbe_batch_details as t READ ;";
		$this->db->query($lock);
		$check_dublicates = " SELECT batch_id FROM fbe_batch_details WHERE course = '$course_id' AND branch = '$branch_id' AND year_of_graduation = '$current_year' ";
		$result = $this->db->query($check_dublicates);
		if ($result->num_rows() == 0)
		{
			$query = " INSERT INTO fbe_batch_details (course,year_of_graduation,branch) VALUES ('$course_id','$current_year','$branch_id') ";
			$this->db->query($query);
			$new_query = "SELECT batch_id FROM fbe_batch_details WHERE course = '$course_id' AND branch = '$branch_id' AND year_of_graduation = '$current_year' ";
			$new_result = $this->db->query($new_query)->result();
			$batch_id = $new_result[0]->batch_id;
		}
		else
		{
			$result1 = $result->result();
			$batch_id = $result1[0]->batch_id;
		}

		$unlock = " UNLOCK TABLES ";
		$this->db->query($unlock);

		$gpa = ceil($data['gpa']);
		$comment = $data['comment'];
		$basic_query = " INSERT INTO fbe_feedback_details (batch_id,gpa,comment)";
		$basic_query .= " VALUES ('$batch_id','$gpa','$comment')";
		$this->db->query($basic_query);
		$temp_query = " SELECT MAX(feedback_id) AS cur_feedback_id FROM fbe_feedback_details ";
		$result = $this->db->query($temp_query)->result();
		$max_id = $result[0]->cur_feedback_id;

		$basic_query = " INSERT INTO fbe_feedback (feedback_id,parameter_no,rating) VALUES ";
		$flag = 0;
		for ($i = 1; $i <= $data['count_group']; $i++)
		{
			for ($j = 1; $j <= $data['count_parameter']; $j++)
			{
				if ($data['group'][$i] == $data['parameter_group'][$j])
				{
					$parameter_no = $data['parameter'][$j];
					$rating = $data[$i][$j];
					if ($flag == 0)
					{
						$basic_query .= " ('$max_id','$parameter_no','$rating') ";
						$flag = 1;
					}
					else
						$basic_query .= " ,('$max_id','$parameter_no','$rating') ";
				}
			}
		}
		$this->db->query($basic_query);
	}
	public function check_available_fbr()
	{
		$basic_query = " SELECT value FROM fb_details WHERE description = 'fbr_feedback' ";
		$result = $this->db->query($basic_query)->result();
		return $result[0]->value;
	}
	public function get_teacher_id_by_subject_id($subject_id)
	{
		$basic_query = " SELECT emp_no AS teacher_id FROM subject_mapping_des WHERE sub_id = '$subject_id' ";
		$result = $this->db->query($basic_query)->result();
		return $result[0]->teacher_id;
	}
	public function get_previous_sem_gpa($data)
	{
		$session_year=$data['session_year'];
		$session=$data['session'];
		$admission_no=$data['admission_no'];
		$course_id=$data['course_id'];
		$branch_id=$data['branch_id'];
		$semester=$data['semester'];
		if($semester<=2)
		{
			$session_year=$data['session_year'];
			$session=$data['session'];
			$admission_no=$data['admission_no'];
			$course_id="COMM";
			$branch_id=$data['branch_id'];
			$semester=$data['semester'];
		}
		//$basic_query="SELECT * FROM final_semwise_marks_foil WHERE semester = '$semester' AND branch = '$branch_id' AND course = '$course_id' AND session = '$session' AND session_yr = '$session_year' AND admn_no='$admission_no' ";
		$basic_query="SELECT * FROM final_semwise_marks_foil WHERE semester = '$semester' AND session = '$session' AND session_yr = '$session_year' AND course = '$course_id' AND admn_no='$admission_no' ";
		return $this->db->query($basic_query)->result();
	}
	public function insert_running_feedback($data)
	{

		$subject_id = $data['subject_id'];
		$map_id = $data['map_id'];
		$teacher_id = $data['teacher_id'];
		$gpa = ceil($data['gpa']);
		$insertion_details = " INSERT INTO fbr_feedback_details (map_id,subject_id,gpa";
		$insertion_values = " values ('$map_id','$subject_id','$gpa'";
		if ($data['comment_status'] == 1)
		{
			$comment = $data['comment'];
			$insertion_details .= ",comment";
			$insertion_values .= ",'$comment'";
		}
		$insertion_details .= ")";
		$insertion_values .= ")";
		$basic_query = $insertion_details.$insertion_values;
		$this->db->query($basic_query);
		$temp_query = " SELECT MAX(feedback_id) AS cur_feedback_id FROM fbr_feedback_details ";
		$result = $this->db->query($temp_query)->result();
		$max_id = $result[0]->cur_feedback_id;
		$flag = 0;
		$basic_query = " INSERT INTO fbr_feedback (feedback_id,parameter_no,rating) VALUES ";
		for ($i = 1; $i <= $data['count_group']; $i++)
		{
			for ($j = 1; $j <= $data['count_parameter']; $j++)
			{
				if ($data['group'][$i] == $data['parameter_group'][$j])
				{
					$parameter_no = $data['parameter'][$j];
					$rating = $data[$i][$j];
					if ($flag == 0)
					{
						$basic_query .= " ('$max_id','$parameter_no','$rating')";
						$flag = 1;
					}
					else
						$basic_query .= " ,('$max_id','$parameter_no','$rating')";
				}
			}
		}
		$this->db->query($basic_query);
	}
	public function get_course_length($id)
	{
		$basic_query = " SELECT duration FROM courses WHERE id IN (SELECT course_id FROM stu_academic WHERE admn_no = '$id' )";
		$result = $this->db->query($basic_query)->result();
		return $result[0]->duration;
	}
	public function get_running_semester_subject_feedback($semester,$admn_no)
	{
		$column = 'running_'.$semester;
		$basic_query = " SELECT $column FROM fb_student_feedback WHERE admn_no = '$admn_no' ";
		$result = $this->db->query($basic_query)->result();
		return $result[0]->$column;
	}
	public function update_running_semester_subject_feedback($string,$semester,$admn_no)
	{
		$column = 'running_'.$semester;
		$basic_query = " UPDATE fb_student_feedback SET $column = '$string' WHERE admn_no = '$admn_no' ";
		$this->db->query($basic_query);
	}
	public function check_available_fbe($admn_no)
	{
		$basic_query = " SELECT value FROM fb_details WHERE description = 'fbe_feedback' ";
		$result = $this->db->query($basic_query)->result();
		$basic_query = " SELECT exit_feedback FROM fb_student_feedback WHERE admn_no = '$admn_no' ";
		$result1 = $this->db->query($basic_query)->result();
		if ($result[0]->value == 1 && $result1[0]->exit_feedback == NULL)
			return true;
		else return false;
	}

	public function get_current_semester($admn_no)
	{
		//$basic_query = " SELECT max(semester) AS current_semester FROM reg_regular_form WHERE admn_no = '$admn_no' ";
		$basic_query = " SELECT semester AS current_semester FROM stu_academic WHERE admn_no = '$admn_no' ";
		$result = $this->db->query($basic_query)->result();

		return $result[0]->current_semester;
	// The problem of getting wrong semester is due to the fact that in reg_regular_form-->semester field is kept as varchar and max does not work properly in varchar
	// If we select current_semester from stu_academic-->semester this problem can be resolved.


	}
	public function get_student_course_and_branch($admn_no)
	{
		$basic_query = " SELECT course_id,branch_id FROM stu_academic WHERE admn_no = '$admn_no' ";
		return $this->db->query($basic_query)->result();
	}
	public function confirm_feedback_submission_fbe($admn_no)
	{
		$value = date("Y");
		$basic_query = " UPDATE fb_student_feedback SET exit_feedback = '$value' WHERE admn_no = '$admn_no' ";
		$this->db->query($basic_query);
	}
	public function get_exit_feedback_data($admn_no)
	{
		$basic_query = " SELECT exit_feedback FROM fb_student_feedback WHERE admn_no = '$admn_no' ";
		$result = $this->db->query($basic_query)->result();
		return $result[0]->exit_feedback;
	}
	public function get_photo_path_by_faculty_id($id)
	{
		$basic_query = " SELECT photopath FROM user_details WHERE id = '$id' ";
		return $this->db->query($basic_query)->result();
	}
	public function get_teacher_details()
	{
		$basic_query = " SELECT id,salutation,concat(first_name,' ',middle_name,' ',last_name) AS name,photopath FROM user_details WHERE id IN (SELECT id FROM users WHERE auth_id = 'emp')";
		return $this->db->query($basic_query)->result();
	}
	public function get_map_id($data)
	{
		$semester = $data['semester'];
		$branch_id = $data['branch_id'];
		$course_id = $data['course_id'];
		$session = $data['session'];
		$session_year = $data['session_year'];
		$basic_query = " SELECT map_id FROM subject_mapping WHERE semester = '$semester' AND branch_id = '$branch_id' AND course_id = '$course_id' AND session = '$session' AND session_year = '$session_year' order by timestamp desc limit 1";
		$result = $this->db->query($basic_query)->result();
		if (!empty($result))
			return $result[0]->map_id;
		return 0;
	}
	public function get_subjects_for_map_id($map_id)
	{
		$basic_query = " SELECT sub_id FROM subject_mapping_des WHERE map_id = '$map_id' ";
		return $this->db->query($basic_query)->result();
	}
	public function get_actual_sub_id($sub_id)
	{
		$basic_query = " SELECT subject_id FROM subjects WHERE id = '$sub_id' ";
		$result = $this->db->query($basic_query)->result();
		return $result[0]->subject_id;
	}
	function get_result($admn_no)
	{
		$this->load->database();
		$q = "SELECT DISTINCT stu_name,adm_no,crdhrs,subje_name,subje_code,tabulation1.grade,sem,f.points
			  FROM tabulation1
			  INNER JOIN dip_m_semcode on tabulation1.sem_code = dip_m_semcode.semcode
			  INNER JOIN grade_points as f on tabulation1.grade = f.grade
			  WHERE adm_no = '$admn_no' ORDER BY sem";
		$query = $this->db->query($q);
		return $query->result();
	}
	function grade_sheet_details($admn_no)
	{
		$sql = "select C.*,f.points,e.dept_id,e.aggr_id,e.course_id,e.branch_id,e.semester,e.`group` from
		(select B.*,d.sequence  as seq from
		(select A.*,c.name,c.subject_id as sid,c.credit_hours,c.`type` from
		(select a.total,a.grade,a.stu_status,a.sub_type,b.subject_id,b.`session`,b.session_year,b.sub_map_id from marks_subject_description as a
		inner join marks_master as b on a.marks_master_id=b.id  where a.admn_no='".$admn_no."' and b.`status`='Y' ) A
		inner join subjects as c on A.subject_id=c.id ) B
		inner join course_structure as d on B.subject_id=d.id ) C
		inner join subject_mapping as e on C.sub_map_id = e.map_id
		inner join grade_points as f on C.grade = f.grade
		 group by C.sid order by e.semester,C.seq+0 asc";



		        $query = $this->db->query($sql);

		        if ($this->db->affected_rows() >= 0) {
		            return $query->result();
		        } else {
		            return false;
		        }
	}
	function new_table_fail($adm_no)
	{
		$sql = "SELECT final_semwise_marks_foil.semester, final_semwise_marks_foil.status,
						final_semwise_marks_foil_desc.sub_code, e.name
				from final_semwise_marks_foil
				inner join final_semwise_marks_foil_desc on final_semwise_marks_foil.id = final_semwise_marks_foil_desc.foil_id
				inner join subjects as e on final_semwise_marks_foil_desc.mis_sub_id = e.id

				where final_semwise_marks_foil.admn_no = '$adm_no'";



		        $query = $this->db->query($sql);

		        if ($this->db->affected_rows() >= 0) {
		            return $query->result();
		        } else {
		            return false;
		        }
	}
	function get_others_sub_marks($adm_no,$sem)
    {
        /*$sql="Select A.*,s.name from (SELECT * FROM final_semwise_marks_foil_desc WHERE foil_id=(SELECT id FROM final_semwise_marks_foil
              WHERE admn_no='".$adm_no."' AND semester like '%".$sem."%' and type='".$type."' ))A
               inner join subjects s on s.subject_id=A.sub_code
                 group by A.sub_code";*/
				 $sql ="select A.*,concat_ws('',s.subje_name,c.name) as name from (SELECT * FROM final_semwise_marks_foil_desc WHERE foil_id=(SELECT id FROM final_semwise_marks_foil
              WHERE admn_no='".$adm_no."' AND semester like '%".$sem."%'))A
               left join tabulation1 s on s.subje_code=A.sub_code
               left join subjects as c on A.mis_sub_id=c.id
                 group by A.sub_code";
        $query = $this->db->query($sql);
        // echo $this->db->last_query(); die();
        if ($this->db->affected_rows() > 0) {
            return $query->result();
        } else {
            return FALSE;
        }
    }
    function get_section_year_back_student($id){

        $sql ="select section from stu_section_data where admn_no=?";
        $query = $this->db->query($sql,array($id));
         if ($this->db->affected_rows() > 0) {
            return $query->row()->section;
        } else {
            return FALSE;
        }

    }

    function check_available_fbs_year_back_status($semester,$admn_no){

        $sql ="SELECT semester_? as sem FROM fb_student_feedback WHERE admn_no =?";
        $query = $this->db->query($sql,array((int)$semester,$admn_no));
         if ($this->db->affected_rows() > 0) {
            return $query->row();
        } else {
            return FALSE;
        }
    }
    function update_fb_student_feedback($semester,$admn_no){
        $sql ="update fb_student_feedback set semester_?=null where admn_no =?";
        $query = $this->db->query($sql,array((int)$semester,$admn_no));
         if ($this->db->affected_rows() > 0) {
           return TRUE;
        } else {
            return FALSE;
        }
    }

    function get_honours_aggr_id($admn_no){
        $sql ="select honours_agg_id from hm_form where admn_no=?";
        $query = $this->db->query($sql,array($admn_no));
         if ($this->db->affected_rows() > 0) {
            return $query->row()->honours_agg_id;
        } else {
            return FALSE;
        }
    }
    function get_elective_type($sub_id){
        $sql ="select
CASE
    WHEN a.aggr_id like '%honour%' THEN 'Elective(Honour)'
    ELSE 'Elective'
END as stype
from course_structure a where a.id=?";
        $query = $this->db->query($sql,array($sub_id));

         if ($this->db->affected_rows() > 0) {
            return $query->row();
        } else {
            return FALSE;
        }
    }

    function student_current_status($sy,$sess,$sem,$admn_no){
        $sql ="select a.* from reg_regular_form a where a.session_year=? and a.session=? and a.semester=?
and a.admn_no=?  and a.hod_status='1' and a.acad_status='1'";
        $query = $this->db->query($sql,array($sy,$sess,$sem,$admn_no));

         if ($this->db->affected_rows() > 0) {
            return $query->row();
        } else {
            return FALSE;
        }
    }
    function student_feedback_saved_subject_status($sy,$sess,$sem,$admn_no){
        $sql ="select a.* from fb_student_subject_main a where a.session_year=? and a.session=? and a.semester=?
and a.admn_no=? ";
        $query = $this->db->query($sql,array($sy,$sess,$sem,$admn_no));

         if ($this->db->affected_rows() > 0) {
            return $query->row();
        } else {
            return FALSE;
        }
    }
    function student_feedback_saved_subject_list($id){
        $sql ="select a.sub_id from fb_student_subject_desc a where a.main_id=?";
        $query = $this->db->query($sql,array($id));

         if ($this->db->affected_rows() > 0) {
            return $query->result();
        } else {
            return FALSE;
        }

    }
    function get_subject_mapid($id){
        $sql ="select * from subject_mapping where map_id=?";
        $query = $this->db->query($sql,array($id));

         if ($this->db->affected_rows() > 0) {
            return $query->row();
        } else {
            return FALSE;
        }

    }
    function get_student_auth($id){
        $sql ="select a.* from stu_academic a where a.admn_no=?";
        $query = $this->db->query($sql,array($id));

         if ($this->db->affected_rows() > 0) {
            return $query->row();
        } else {
            return FALSE;
        }

    }

}
