<?php if (!defined("BASEPATH")) {
    exit("No direct script access allowed");
}

class Student_registration_model extends CI_Model {

function __construct() {
    parent::__construct();
    
}

function get_session_year_list(){
	$sql="select a.session_year from mis_session_year a order by a.id desc ";
	$result=$this->db->query($sql);
	return $result->result();
}

function get_session_list(){
	$sql="select a.`session` from mis_session a";
	$result=$this->db->query($sql);
	return $result->result();
}

function get_stu_details($sy,$session,$admn){
	
// $sql="select x.* from (select a.*,c.semester,c.form_id,d.name as course_name,e.name as branch_name,f.name as dept_name,c.hod_status,c.hod_remark,c.acad_status,c.acad_remark,c.course_id as stu_course,c.branch_id
// from user_details a
// join users b on b.id=a.id
// join reg_regular_form c on c.admn_no=a.id
// join cbcs_courses d on d.id=c.course_id
// join cbcs_branches e on e.id=c.branch_id
// join cbcs_departments f on f.id=a.dept_id
// where a.id='$admn' and b.auth_id='stu' and c.session_year='$sy' and c.`session`='$session'
// order by c.form_id desc limit 100)x
// group by x.id
// union
// select x.* from (select a.*,c.semester,c.form_id,d.name as course_name,e.name as branch_name,f.name as dept_name,c.hod_status,c.hod_remark,c.acad_status,c.acad_remark,c.course_id as stu_course,c.branch_id
// from user_details a
// join users b on b.id=a.id
// join reg_other_form c on c.admn_no=a.id
// join cbcs_courses d on d.id=c.course_id
// join cbcs_branches e on e.id=c.branch_id
// join cbcs_departments f on f.id=a.dept_id
// where a.id='$admn' and b.auth_id='stu' and c.session_year='$sy' and c.`session`='$session'
// order by c.form_id desc limit 100)x
// group by x.id
// union
// select x.* from (select a.*,c.semester,c.form_id,d.name as course_name,e.name as branch_name,f.name as dept_name,c.hod_status,c.hod_remark,c.acad_status,c.acad_remark,c.course_id as stu_course,c.branch_id
// from user_details a
// join users b on b.id=a.id
// join reg_exam_rc_form c on c.admn_no=a.id
// join cbcs_courses d on d.id=c.course_id
// join cbcs_branches e on e.id=c.branch_id
// join cbcs_departments f on f.id=a.dept_id
// where a.id='$admn' and b.auth_id='stu' and c.session_year='$sy' and c.`session`='$session'
// order by c.form_id desc limit 100)x
// group by x.id";
	$sql="select x.* from (select a.*,c.form_id,c.semester,d.name as course_name,e.name as branch_name,f.name as dept_name,c.hod_status,c.hod_remark,c.acad_status,c.acad_remark,c.course_id as stu_course,c.branch_id,g.domain_name
from user_details a
join users b on b.id=a.id
join reg_regular_form c on c.admn_no=a.id
join cbcs_courses d on d.id=c.course_id
join cbcs_branches e on e.id=c.branch_id
join cbcs_departments f on f.id=a.dept_id
JOIN emaildata g ON g.admission_no=a.id
where a.id='$admn' and b.auth_id='stu' and c.session_year='$sy' and c.`session`='$session'
order by c.form_id desc limit 100)x
group by x.id
union
select x.* from (select a.*,c.form_id,c.semester,d.name as course_name,e.name as branch_name,f.name as dept_name,c.hod_status,c.hod_remark,c.acad_status,c.acad_remark,c.course_id as stu_course,c.branch_id,g.domain_name
from user_details a
join users b on b.id=a.id
join reg_other_form c on c.admn_no=a.id
join cbcs_courses d on d.id=c.course_id
join cbcs_branches e on e.id=c.branch_id
join cbcs_departments f on f.id=a.dept_id
JOIN emaildata g ON g.admission_no=a.id
where a.id='$admn' and b.auth_id='stu' and c.session_year='$sy' and c.`session`='$session'
order by c.form_id desc limit 100)x
group by x.id
union
select x.* from (select a.*,c.form_id,c.semester,d.name as course_name,e.name as branch_name,f.name as dept_name,c.hod_status,c.hod_remark,c.acad_status,c.acad_remark,c.course_id as stu_course,c.branch_id,g.domain_name
from user_details a
join users b on b.id=a.id
join reg_exam_rc_form c on c.admn_no=a.id
join cbcs_courses d on d.id=c.course_id
join cbcs_branches e on e.id=c.branch_id
join cbcs_departments f on f.id=a.dept_id
JOIN emaildata g ON g.admission_no=a.id
where a.id='$admn' and b.auth_id='stu' and c.session_year='$sy' and c.`session`='$session'
order by c.form_id desc limit 100)x
group by x.id";
	$result=$this->db->query($sql);
	//echo $this->db->last_query();exit;
	return $result->result();
}

function get_stu_course($sy,$session,$admn,$hide,$form_id){

$val='';
if($hide=='hide'){
	$val="and a.course != 'comm' and a.branch!='comm'";
}
$sql="select x.* from ((select a.id,a.admn_no,a.subject_code,a.subject_name,a.sub_category,a.sub_category_cbcs_offered,b.name as course_name,c.name as branch_name,concat_ws(' ',d.salutation,d.first_name,d.middle_name,d.last_name) as stu_name,e.name as dept_name,f.semester,
'cbcs' as type, g.lecture,g.tutorial,g.practical,'CBCS' as stu_type
from pre_stu_course a
join cbcs_courses b on b.id=a.course
join cbcs_branches c on c.id=a.branch
join user_details d on d.id=a.admn_no 
join cbcs_departments e on e.id=d.dept_id
join reg_regular_form f on f.admn_no=a.admn_no and f.form_id=a.form_id and f.session_year='$sy' and f.`session`='$session'
join cbcs_subject_offered g on g.id=a.sub_offered_id or g.sub_code=a.subject_code /*and g.session_year=a.session_year and a.`session`=a.`session` and g.dept_id=d.dept_id *//*and g.course_id=a.course and g.branch_id=a.branch*/
where a.admn_no='$admn' and a.session_year='$sy' and a.`session`='$session' and f.hod_status='1' and f.acad_status='1' /*and f.form_id='$form_id'*/ $val and a.remark1 is null
group by a.id)
union(
select a.id,a.admn_no,a.subject_code,a.subject_name,a.sub_category,a.sub_category_cbcs_offered,b.name as course_name,c.name as branch_name,concat_ws(' ',d.salutation,d.first_name,d.middle_name,d.last_name) as stu_name,e.name as dept_name,f.semester,
'old' as type, g.lecture,g.tutorial,g.practical,'OLD' as stu_type
from pre_stu_course a
join cbcs_courses b on b.id=a.course
join cbcs_branches c on c.id=a.branch
join user_details d on d.id=a.admn_no 
join cbcs_departments e on e.id=d.dept_id
join reg_regular_form f on f.admn_no=a.admn_no and f.form_id=a.form_id and f.session_year='$sy' and f.`session`='$session'
join old_subject_offered g on g.id=a.sub_offered_id or g.sub_code=a.subject_code /*and g.session_year=a.session_year and a.`session`=a.`session` and g.dept_id=d.dept_id *//*and g.course_id=a.course and g.branch_id=a.branch*/
where a.admn_no='$admn' and a.session_year='$sy' and a.`session`='$session' and f.hod_status='1' and f.acad_status='1' /*and f.form_id='$form_id'*/ $val and a.remark1 is null
group by a.id)) x 
group by x.id";
	$result=$this->db->query($sql);
	//echo $this->db->last_query();exit;
	return $result->result();
	
}

function get_stu_course_other($sy,$session,$admn){

$sql="(select a.*,b.sub_seq,b.sub_id,c.subject_id,c.name,c.lecture,c.tutorial,c.practical,'Present' as type
from reg_other_form a
join reg_other_subject b on b.form_id=a.form_id
join subjects c on c.id=b.sub_id
where a.session_year='$sy' and a.`session`='$session' and a.admn_no='$admn' and a.hod_status='1' and a.acad_status='1')
union
(select a.*,b.sub_seq,b.sub_id,c.subject_id,c.name,c.lecture,c.tutorial,c.practical,'Deleted' as type
from reg_other_form a
join reg_other_subject_backup b on b.form_id=a.form_id
join subjects c on c.id=b.sub_id
where a.session_year='$sy' and a.`session`='$session' and a.admn_no='$admn' and a.hod_status='1' and a.acad_status='1')";
	$result=$this->db->query($sql);
	//echo $this->db->last_query();exit;
	return $result->result();
}

function get_stu_course_exam($sy,$session,$admn){
	$sql="(select a.*,b.sub_seq,b.sub_id,c.subject_id,c.name,c.lecture,c.tutorial,c.practical,'Present' as type
from reg_exam_rc_form a
join reg_exam_rc_subject b on b.form_id=a.form_id
join subjects c on c.id=b.sub_id
where a.session_year='$sy' and a.`session`='$session' and a.admn_no='$admn' and a.hod_status='1' and a.acad_status='1')
union
(select a.*,b.sub_seq,b.sub_id,c.subject_id,c.name,c.lecture,c.tutorial,c.practical,'Deleted' as type
from reg_exam_rc_form a
join reg_exam_rc_subject_backup b on b.form_id=a.form_id
join subjects c on c.id=b.sub_id
where a.session_year='$sy' and a.`session`='$session' and a.admn_no='$admn' and a.hod_status='1' and a.acad_status='1')";
 	$result=$this->db->query($sql);
 	return $result->result();
}

function get_department_list(){
	$sql="select a.* from cbcs_departments a where a.`type`='academic' and status='1'";
	$result=$this->db->query($sql);
	return $result->result();
}


function get_offered_subject_list($sy,$session,$dept_id,$course,$branch,$branch_id,$course_id,$admn,$stu_auth,$en_year,$form_id){
	//$dept_id=$this->session->userdata('dept_id');
	//$sql="select a.* from cbcs_subject_offered a where a.session_year='$sy' and a.`session`='$session' and a.dept_id='$dept_id'";
	$q='';
	if(($en_year>='2019' && $stu_auth!='prep') || ($stu_auth=='prep' && $en_year[0]=='2018')){
		if($branch_id != '' && $course_id != ''){
			$q="select a.*,'' as map_id,'CBCS' as type,b.name as bname 
		from cbcs_subject_offered a 
		inner join cbcs_branches b on b.id=a.branch_id
		JOIN reg_regular_form r ON r.section=a.sub_group
		where a.session_year='$sy' and a.`session`='$session' and a.course_id='$course_id' and a.branch_id='$branch_id' AND r.admn_no='$admn' AND a.sub_category not LIKE 'OE%' AND a.sub_category NOT LIKE 'ESO%' and r.form_id='$form_id'
	GROUP BY a.id";
		}
		else{
			$q="select a.*,'' as map_id,'CBCS' as type,b.name as bname 
		from cbcs_subject_offered a 
		inner join cbcs_branches b on b.id=a.branch_id
		where a.session_year='$sy' and a.`session`='$session' /*and a.dept_id='$dept_id'*/ and a.course_id='$course' and a.branch_id='$branch' AND a.sub_category not LIKE 'OE%' AND a.sub_category NOT LIKE 'ESO%' order by a.sub_category";
		}
	}
	else{



		$q="
		select a.*,'OLD' as type,b.name as bname 
		from old_subject_offered a 
		inner join cbcs_branches b on b.id=a.branch_id
		where a.session_year='$sy' and a.`session`='$session' and a.dept_id='$dept_id' and a.course_id='$course' and a.branch_id='$branch'";
	}
	//echo $q;
	$result=$this->db->query($q);
	//echo $this->db->last_query();exit;
	return $result->result();
}


function get_offered_subject_elective_list($sy,$session,$dept_id,$course,$branch,$branch_id,$course_id,$admn,$form_id){
	
	$q='';
	if($branch_id != '' && $course_id != ''){
		$q="select a.*,'' as map_id,'CBCS' as type,b.name as bname 
	from cbcs_subject_offered a 
	inner join cbcs_branches b on b.id=a.branch_id
	JOIN reg_regular_form r ON r.section=a.sub_group
	where a.session_year='$sy' and a.`session`='$session' and a.course_id='$course_id' /*and a.branch_id='$branch_id'*/ AND r.admn_no='$admn' AND r.form_id='$form_id' AND (a.sub_category LIKE 'DE%' || a.sub_category LIKE 'OE%')
GROUP BY a.id order by a.sub_code";
	}
	else{
		$q="select a.*,'' as map_id,'CBCS' as type,b.name as bname 
	from cbcs_subject_offered a 
	inner join cbcs_branches b on b.id=a.branch_id
	where a.session_year='$sy' and a.`session`='$session' /*and a.dept_id='$dept_id'*/ and a.course_id='$course' /*and a.branch_id='$branch'*/ AND (a.sub_category LIKE 'DE%' || a.sub_category LIKE 'OE%') order by a.sub_code";
	}

	
	$result=$this->db->query($q);
	//echo $this->db->last_query();exit;
	return $result->result();
}

public function get_oe_list($admn,$sy,$sess,$form_id){
	$sql="SELECT a.sub_category
FROM cbcs_subject_offered a
JOIN reg_regular_form b ON b.course_id=a.course_id AND b.branch_id=a.branch_id
WHERE b.admn_no='$admn' and b.form_id='$form_id' AND a.session_year='$sy' AND a.`session`='$sess' AND a.sub_category LIKE 'OE%'
GROUP BY a.sub_category";
$result=$this->db->query($sql);
	//echo $this->db->last_query();exit;
	return $result->result();

}

public function get_oe_count($admn,$sy,$sess,$oe_type,$form_id){
	$sql="SELECT a.*
FROM pre_stu_course a
WHERE a.admn_no='$admn' AND a.session_year='$sy' AND a.`session`='$sess' AND 
a.sub_category_cbcs_offered='$oe_type' and a.remark2 in (null,0,1,'') AND a.form_id='$form_id'";
	$query=$this->db->query($sql);
	return $query->num_rows();
}

public function check_subject_permission($sy,$sess,$admn,$form_id,$sub){
	$sql="SELECT a.*
FROM cbcs_marks_upload a
WHERE a.form_id='$form_id' AND a.admn_no='$admn' AND a.session_year='$sy' AND a.`session`='$sess' AND a.subject_code='$sub'";
	$query=$this->db->query($sql);
	return $query->num_rows();

}

public function delete_stu_course($id,$type,$pre_type){
	$user_id=$this->session->userdata('id');
	$date=date('Y-m-d H:i:s');
	if($pre_type=='drop'){
		$this->db->query("INSERT INTO stu_exam_absent_mark (form_id,admn_no,course_aggr_id,semester,session_year,session,sub_id,ex_type,status,
		timestamp,userid) 
		SELECT a.form_id,a.admn_no,/*CONCAT('c',a.sub_offered_id)*/'' as course_aggr_id,c.semester,a.session_year,a.`session`,a.subject_code as sub_id,'regular' AS ex_type,'B' AS STATUS,
		'$date' AS `timestamp`,'$user_id' AS userid
		FROM pre_stu_course a
		JOIN reg_regular_form c ON c.form_id=a.form_id AND c.admn_no=a.admn_no
		WHERE a.id='$id'");
	}
	$pre_type=$pre_type.'|'.$user_id.'|'.$date;

	if($this->db->query("UPDATE pre_stu_course SET remark1='$pre_type',remark2='2' WHERE id='$id'")){
		$p_sql="SELECT a.* from pre_stu_course a WHERE a.id='$id'";
		$data=$this->db->query($p_sql);
		$data=$data->result();
		//echo '<br>'.$this->db->last_query().'<br>';//die();
		if(strpos($data[0]->sub_category_cbcs_offered, 'OE') !== false || strpos($data[0]->sub_category_cbcs_offered, 'ESO') !== false){
			$admn_no=$data[0]->admn_no;
			$form_id=$data[0]->form_id;
			$sub_category_cbcs_offered=$data[0]->sub_category_cbcs_offered;
			for($i=$data[0]->priority;$i<8;$i++){
				$priority=$i+1;
				
				$u_sql="UPDATE pre_stu_course set priority='$i' WHERE admn_no='$admn_no' AND form_id='$form_id' and sub_category_cbcs_offered='$sub_category_cbcs_offered' and priority='$priority'";
				$data=$this->db->query($u_sql);
				//echo $this->db->last_query().'<br>';
			}
		}//exit;
		return TRUE;
	}
	else{
		//echo '2<br>';echo $this->db->last_query();die();
		return FALSE;
	}
}

public function delete_stu_course_other($id,$sequence,$admn,$sy,$sess){
	$user_id=$this->session->userdata('id');
	$date=date('Y-m-d H:i:s');

	$this->db->query("INSERT INTO reg_other_subject_backup SELECT a.*,'$admn' as admn_no,'$user_id' as deleted_by,'$date' as deleted_time FROM reg_other_subject a WHERE a.form_id='$id' and a.sub_seq='$sequence'");



	$this->db->query("INSERT INTO stu_exam_absent_mark (form_id,admn_no,course_aggr_id,semester,session_year,session,sub_id,ex_type,status,
timestamp,userid) 
SELECT a.form_id,'$admn' AS admn_no, CONCAT_WS('_',c.course_id,c.branch_id,
REPLACE('$sy', '-', '_')) as course_aggr_id,c.semester,'$sy' as session_year,'$sess' as session,a.sub_id,'other' AS ex_type,'B' AS STATUS, '$date' AS `timestamp`,'$user_id' AS userid
from reg_other_subject a
join subjects b on b.id=a.sub_id
join reg_other_form c on c.form_id=a.form_id
where a.form_id='$id' and a.sub_seq='$sequence'");

	//echo $this->db->last_query();die();

	if($this->db->query("DELETE FROM reg_other_subject  WHERE form_id='$id' and sub_seq='$sequence'"))
		return TRUE;
	else
		return FALSE;
}

public function delete_stu_course_exam($id,$sequence,$admn,$sy,$sess){
	$user_id=$this->session->userdata('id');
	$date=date('Y-m-d H:i:s');
	
	$this->db->query("INSERT INTO reg_exam_rc_subject_backup SELECT a.*,'$admn' as admn_no,'$user_id' as deleted_by,'$date' as deleted_time FROM reg_exam_rc_subject a WHERE a.form_id='$id' and a.sub_seq='$sequence'");
	//echo $this->load->last_query();die();
	$this->db->query("INSERT INTO stu_exam_absent_mark (form_id,admn_no,course_aggr_id,semester,session_year,session,sub_id,ex_type,status,
timestamp,userid) 
SELECT a.form_id,'$admn' AS admn_no, CONCAT_WS('_',c.course_id,c.branch_id,
REPLACE('$sy', '-', '_')) as course_aggr_id,c.semester,'$sy' as session_year,'$sess' as session,a.sub_id,'exam' AS ex_type,'B' AS STATUS, '$date' AS `timestamp`,'$user_id' AS userid
from reg_exam_rc_subject a
join subjects b on b.id=a.sub_id
join reg_exam_rc_form c on c.form_id=a.form_id
where a.form_id='$id' and a.sub_seq='$sequence'");
	
	if($this->db->query("DELETE FROM reg_exam_rc_subject  WHERE form_id='$id' and sub_seq='$sequence'"))
		return TRUE;
	else
		return FALSE;
}
public function get_offered_subject_details($stu_type,$admn,$sub_offer_id,$sub_type,$sy,$sess,$sem,$sub_course,$stu_course,$s_code,$branch,$s_sem,$form_id){
	
	$query=$this->db->query("SELECT a.*,if(INSTR (a.remark1,'drop'),'drop',if(INSTR (a.remark1,'delete'),'delete','')) as type from pre_stu_course a where a.subject_code='$s_code' and a.admn_no='$admn' and a.form_id='$form_id' and a.session_year='$sy' and a.`session`='$sess'");

	return $query->result();
}
public function insert_offered_subject($stu_type,$admn,$sub_offer_id,$sub_type,$sy,$sess,$sem,$sub_course,$stu_course,$s_code,$branch,$s_sem,$s_cat,$form_id){
	$user_id=$this->session->userdata('id');
	$date=date('Y-m-d H:i:s');
	$sql='';
	$data['priority']=0;
	//if($sub_type=='CBCS' || $stu_course == 'jrf'){ 
	if($sub_type=='CBCS'){
		$tbl1='old_subject_offered';
		$tbl2='cbcs_subject_offered';
		$tbl3='cbcs_stu_course';
		$tbl4='old_subject_offered_desc';
		$tbl5='cbcs_subject_offered_desc';
		/*if(strpos($s_cat, 'OE') !== false){ 
			$find=$this->db->query("select max(a.priority) as prio from pre_stu_course a where a.admn_no='$admn' and a.sub_category_cbcs_offered='$s_cat'");
			//echo $this->db->last_query();
			$f=$find->result();
			$data['sub_category_cbcs_offered']=$s_cat;
			$data['priority']=$f[0]->prio + 1;
		}*/
		
	}
	else{
		$tbl1='cbcs_subject_offered';
		$tbl2='old_subject_offered';
		$tbl3='old_stu_course';
		$tbl4='cbcs_subject_offered_desc';
		$tbl5='old_subject_offered_desc';
		$dept=$this->session->userdata('dept_id');
	
	}


        $query=$this->db->query("select a.* from stu_academic a where a.admn_no='$admn'");
       // echo $this->db->last_query();exit;
        $academic=$query->result();
		/*echo '<pre>';
		print_r($academic);
		echo '</pre>';*/
        $stu_type=$academic[0]->enrollment_year;
		if(($academic[0]->auth_id=='ug' && $academic[0]->semester==1) || ($academic[0]->auth_id=='prep' && $academic[0]->semester==1)){
            $data['branch']=$branch='comm';
            $data['course']=$course='comm';
        }else{
			$data['course']=$course=$academic[0]->course_id;
			$data['branch']=$branch=$academic[0]->branch_id;
		}
		
	
	

	$result=$this->db->query("select * from $tbl2 where id='$sub_offer_id'");
		$s_offered=$result->result();
		$sy=$s_offered[0]->session_year;
		$sess=$s_offered[0]->session;
		$result1=$this->db->query("select a.* from reg_regular_form a where a.admn_no='$admn' and form_id='$form_id' and a.session_year='$sy' and a.`session`='$sess' and a.hod_status='1' and a.acad_status='1'");
		$stu=$result1->result();
		//echo $this->db->last_query();exit;
		if($sub_type == 'OLD'){
			$sub_offer_id='o'.$sub_offer_id;
		}
		elseif($sub_type == 'CBCS'){
			$sub_offer_id='c'.$sub_offer_id;
		}
		$data['form_id']=$stu[0]->form_id;
		$data['admn_no']=$admn;
		$data['sub_offered_id']=$sub_offer_id;
		$data['subject_code']=$s_offered[0]->sub_code;
		// if($stu_type=='OLD'){
		// 	$data['course_aggr_id']=$stu[0]->course_id.'_'.$stu[0]->branch_id.'_'.str_replace('-','_',$s_offered[0]->session_year);
		// }
		// if($sub_type=='OLD' && $stu_course != 'jrf'){
		// 	$data['course_aggr_id']=$stu[0]->course_id.'_'.$stu[0]->branch_id.'_'.str_replace('-','_',$s_offered[0]->session_year);
		// }
		$data['course_aggr_id']=$stu[0]->course_id.'_'.$stu[0]->branch_id.'_'.str_replace('-','_',$s_offered[0]->session_year);
		$data['subject_name']=$s_offered[0]->sub_name;
		$data['sub_category']=$s_offered[0]->sub_category;
		//$data['course']=$stu[0]->course_id;
		//$data['branch']=$stu[0]->branch_id;
		$data['session_year']=$s_offered[0]->session_year;
		$data['session']=$s_offered[0]->session;
		if(strpos($s_cat, 'DC') !== false || strpos($s_cat, 'DP') !== false){
			$data['remark2']=1;
		}else{
			$data['remark2']=null;
		}
		$data['remark3']='add|'.$user_id.'|'.$date;
		$data['updated_at']=$date;
		/*echo "<pre>";
		print_r($data);
		echo "</pre>";*/
		//echo $result1->num_rows();die();
		
		if($result->num_rows()>0){
			//echo 1234;
			//$this->db->insert($tbl3,$data);
			/*echo "<pre>";
		print_r($data);
		echo "</pre>";*/
			$this->db->insert('pre_stu_course',$data);
			//echo $this->db->last_query();die();
		}
		//echo $this->db->last_query();//die();
	
}

public function insert_offered_subject_oe($stu_type,$admn,$sub_offer_id,$sub_type,$sy,$sess,$sem,$sub_course,$stu_course,$s_code,$branch,$s_sem,$s_cat,$l,$form_id){
	$user_id=$this->session->userdata('id');
	$date=date('Y-m-d H:i:s');
	$sql='';
	$data['priority']=0;
	//if($sub_type=='CBCS' || $stu_course == 'jrf'){ 
	if($sub_type=='CBCS'){
		$tbl1='old_subject_offered';
		$tbl2='cbcs_subject_offered';
		$tbl3='cbcs_stu_course';
		$tbl4='old_subject_offered_desc';
		$tbl5='cbcs_subject_offered_desc';
		/*if(strpos($s_cat, 'OE') !== false){ */
			$find=$this->db->query("select max(a.priority) as prio from pre_stu_course a where a.admn_no='$admn' AND a.form_id=$form_id AND a.session_year='$sy' AND a.`session`='$sess' and a.sub_category_cbcs_offered='$l'");
			//echo $this->db->last_query();
			$f=$find->result();
			/*$data['sub_category_cbcs_offered']=$s_cat;
			$data['priority']=$f[0]->prio + 1;
		}*/
		$data['sub_category_cbcs_offered']=$l;
		$data['priority']=$f[0]->prio+1;
		 
		
	}
	else{
		$tbl1='cbcs_subject_offered';
		$tbl2='old_subject_offered';
		$tbl3='old_stu_course';
		$tbl4='cbcs_subject_offered_desc';
		$tbl5='old_subject_offered_desc';
		$dept=$this->session->userdata('dept_id');
	
	}


        $query=$this->db->query("select a.* from stu_academic a where a.admn_no='$admn'");
       // echo $this->db->last_query();exit;
        $academic=$query->result();
		/*echo '<pre>';
		print_r($academic);
		echo '</pre>';*/
        $stu_type=$academic[0]->enrollment_year;
		if(($academic[0]->auth_id=='ug' && $academic[0]->semester==1) || ($academic[0]->auth_id=='prep' && $academic[0]->semester==1)){
            $data['branch']=$branch='comm';
            $data['course']=$course='comm';
        }else{
			$data['course']=$course=$academic[0]->course_id;
			$data['branch']=$branch=$academic[0]->branch_id;
		}
		
	
	

	$result=$this->db->query("select * from $tbl2 where id='$sub_offer_id'");
		$s_offered=$result->result();
		$sy=$s_offered[0]->session_year;
		$sess=$s_offered[0]->session;
		$result1=$this->db->query("select a.* from reg_regular_form a where a.admn_no='$admn' and a.form_id='$form_id' and a.session_year='$sy' and a.`session`='$sess' and a.hod_status='1' and a.acad_status='1'");
		$stu=$result1->result();
		//echo $this->db->last_query();exit;
		if($sub_type == 'OLD'){
			$sub_offer_id='o'.$sub_offer_id;
		}
		elseif($sub_type == 'CBCS'){
			$sub_offer_id='c'.$sub_offer_id;
		}
		$data['form_id']=$stu[0]->form_id;
		$data['admn_no']=$admn;
		$data['sub_offered_id']=$sub_offer_id;
		$data['subject_code']=$s_offered[0]->sub_code;
		
		$data['course_aggr_id']=$stu[0]->course_id.'_'.$stu[0]->branch_id.'_'.str_replace('-','_',$s_offered[0]->session_year);
		$data['subject_name']=$s_offered[0]->sub_name;
		$data['sub_category']=$s_offered[0]->sub_category;
		//$data['course']=$stu[0]->course_id;
		//$data['branch']=$stu[0]->branch_id;
		$data['session_year']=$s_offered[0]->session_year;
		$data['session']=$s_offered[0]->session;
		$data['remark1']=null;
		$data['remark2']=null;
		$data['remark3']='add|'.$user_id.'|'.$date;
		$data['updated_at']=$date;
		
		
		if($result->num_rows()>0){
			
			$this->db->insert('pre_stu_course',$data);
			//echo $this->db->last_query();die();
		}
		//echo $this->db->last_query();//die();
	
}

function get_stu_course_dropped($sy,$session,$admn,$hide,$form_id){
	$val='';
if($hide=='hide'){
	$val="and a.course != 'comm' and a.branch!='comm'";
}
$sql="select x.* from ((
SELECT a.id,a.admn_no,a.subject_code,a.subject_name,a.sub_category,a.sub_category_cbcs_offered,if(INSTR (a.remark1,'drop'),'drop','delete') as remark_1,b.name AS course_name,c.name AS branch_name, CONCAT_WS(' ',d.salutation,d.first_name,d.middle_name,d.last_name) AS stu_name,e.name AS dept_name,f.semester, 'cbcs' AS TYPE, g.lecture,g.tutorial,g.practical,'CBCS' AS stu_type
FROM pre_stu_course a
JOIN cbcs_courses b ON b.id=a.course
JOIN cbcs_branches c ON c.id=a.branch
JOIN user_details d ON d.id=a.admn_no
JOIN cbcs_departments e ON e.id=d.dept_id
join reg_regular_form f on f.admn_no=a.admn_no and f.form_id=a.form_id and f.session_year='$sy' and f.`session`='$session'
join cbcs_subject_offered g on g.id=a.sub_offered_id or g.sub_code=a.subject_code /*and g.session_year=a.session_year and a.`session`=a.`session` and g.dept_id=d.dept_id *//*and g.course_id=a.course and g.branch_id=a.branch*/
where a.admn_no='$admn' and a.session_year='$sy' and a.`session`='$session' and f.hod_status='1' and f.acad_status='1' $val and a.remark1 is not null /*and f.form_id='$form_id'*/
GROUP BY a.id) UNION (
SELECT a.id,a.admn_no,a.subject_code,a.subject_name,a.sub_category,a.sub_category_cbcs_offered,if(INSTR (a.remark1,'drop'),'drop','delete') as remark_1,b.name AS course_name,c.name AS branch_name, CONCAT_WS(' ',d.salutation,d.first_name,d.middle_name,d.last_name) AS stu_name, e.name AS dept_name,f.semester,'old' AS TYPE,g.lecture,g.tutorial,g.practical,'OLD' AS stu_type
FROM pre_stu_course a
JOIN cbcs_courses b ON b.id=a.course
JOIN cbcs_branches c ON c.id=a.branch
JOIN user_details d ON d.id=a.admn_no
JOIN cbcs_departments e ON e.id=d.dept_id
join reg_regular_form f on f.admn_no=a.admn_no and f.form_id=a.form_id and f.session_year='$sy' and f.`session`='$session'
join old_subject_offered g on g.id=a.sub_offered_id or g.sub_code=a.subject_code /*and g.session_year=a.session_year and a.`session`=a.`session` and g.dept_id=d.dept_id*/ /*and g.course_id=a.course and g.branch_id=a.branch*/
where a.admn_no='$admn' and a.session_year='$sy' and a.`session`='$session' and f.hod_status='1' and f.acad_status='1' $val and a.remark1 is not null /*and f.form_id='$form_id'*/
GROUP BY a.id)) x 
group by x.id,x.subject_code";
	$result=$this->db->query($sql);
	//echo $this->db->last_query();
	return $result->result();
	
}

function add_subject_again($id,$a_type){
	$user_id=$this->session->userdata('id');
	$date=date('Y-m-d H:i:s');
	$remark3='add|'.$user_id.'|'.$date;
	$query=$this->db->query("SELECT a.* FROM pre_stu_course a WHERE a.id='$id'");
	$details=$query->result();
	$form_id=$details[0]->form_id;
	$admn_no=$details[0]->admn_no;
	$session_year=$details[0]->session_year;
	$session=$details[0]->session;
	$subject_code=$details[0]->subject_code;
	$sub_category_cbcs_offered=$details[0]->sub_category_cbcs_offered;
	$priority=0;
	if(strpos($sub_category_cbcs_offered, 'OE') !== false || strpos($sub_category_cbcs_offered, 'ESO') !== false){
		$find=$this->db->query("SELECT max(a.priority) as prio from pre_stu_course a where a.form_id='$form_id' AND a.admn_no='$admn_no' AND a.session_year='$session_year' AND a.`session`='$session' and a.sub_category_cbcs_offered='$sub_category_cbcs_offered'");
			//echo $this->db->last_query();
		$f=$find->result();
		$priority=$f[0]->prio+1;
	}
	if($this->db->query("UPDATE pre_stu_course set priority='$priority',remark1=null,remark2=null,remark3='$remark3' where id='$id'")){
		if($a_type=='drop'){
			/*$query=$this->db->query("SELECT a.* FROM pre_stu_course a WHERE a.id='$id'");
			$details=$query->result();
			$form_id=$details[0]->form_id;
			$admn_no=$details[0]->admn_no;
			$session_year=$details[0]->session_year;
			$session=$details[0]->session;
			$subject_code=$details[0]->subject_code;*/
			$this->db->query("DELETE FROM `stu_exam_absent_mark` WHERE form_id='$form_id' AND admn_no='$admn_no' AND session_year='$session_year' AND session='$session' AND sub_id='$subject_code' and status='B'");
		}
		return true;
	}else{
		return false;
	}

}

function subject_check($sub_type,$sub_offer_id,$admn,$s_code,$form_id){
	// if($sub_type=='CBCS')
	// 	$table='cbcs_subject_offered';
	// else
	// 	$table='old_subject_offered';
	// $query=$this->db->query("SELECT * from $table where id='$sub_offer_id'");

	// $result=$query->result();
	// $sub_code=$result[0]->sub_code;
	//echo "SELECT * FROM pre_stu_course WHERE subject_code='$sub_code' and admn_no='$admn' and (remark1 is null or remark1='')";
	$query=$this->db->query("SELECT * FROM pre_stu_course WHERE subject_code='$s_code' and admn_no='$admn' and form_id='$form_id'");
	$row=$query->num_rows();
	if($row > 0){
		//echo $this->db->last_query();exit;
		return false;
	}
	else
	{
		return true;
	}
}

function subject_check_oe($sub_type,$sub_offer_id,$admn,$s_code){
	// if($sub_type=='CBCS')
	// 	$table='cbcs_subject_offered';
	// else
	// 	$table='old_subject_offered';
	// $query=$this->db->query("SELECT * from $table where id='$sub_offer_id'");

	// $result=$query->result();
	// $sub_code=$result[0]->sub_code;
	//echo "SELECT * FROM pre_stu_course WHERE subject_code='$sub_code' and admn_no='$admn' and (remark1 is null or remark1='')";
	$query=$this->db->query("SELECT * FROM pre_stu_course WHERE subject_code='$s_code' and admn_no='$admn' and form_id='$form_id' /*and remark2='1'*/ /*and sub_category_cbcs_offered like 'OE%'*/");
	$row=$query->num_rows();
	if($row > 0){
		//echo $this->db->last_query();exit;
		return false;
	}
	else
	{
		return true;
	}
}

function check_subject_add($id,$admn){
	$sql="SELECT * from pre_stu_course where id='$id'";
	$query=$this->db->query($sql);
	$sub=$query[0]->subject_code;
	if($this->db->query("SELECT * FROM pre_stu_course where admn_no='$admn' and $subject_code='$sub' and (remark1 is null or remark1='')")){
		return false;
	}else{
		return true;
	}
}

	function get_enrollment_year($id){
	    $sql="select a.* from stu_academic a where a.admn_no='$id'";
	    $query=$this->db->query($sql);
	//echo $this->db->last_query();
	    return $query->result();
	}

	function verified_checking($sy,$session,$admn,$form_id){
		/*$sql="SELECT a.*
FROM pre_stu_course a
WHERE a.admn_no='$admn' AND a.session_year='$sy' AND a.`session`='$session'
AND a.course_aggr_id LIKE 'verified|%'";*/
		$sql="SELECT a.*
FROM reg_regular_form a
WHERE a.admn_no='$admn' AND a.session_year='$sy' AND a.`session`='$session' AND a.`status`='1' AND a.form_id='$form_id'";
 $query=$this->db->query($sql);
	//echo $this->db->last_query();
	    return $query->result();
	}

	function check_open_elective_found($admn,$sy,$sess,$l,$sub_offer_id,$sub_type){
		if($sub_type=='CBCS'){
			$sub_id='c'.$sub_offer_id;
		}
		elseif($sub_type=='OLD'){
			$sub_id='o'.$sub_offer_id;
		}
		else{
			$sub_type='';
		}

		$sql="SELECT a.*
	FROM pre_stu_course a
	WHERE a.admn_no='$admn' AND a.session_year='$sy' AND a.`session`='$sess' AND a.sub_category_cbcs_offered='$l' 	/*AND a.sub_offered_id='$sub_id'*/ AND a.remark2='1'";

		$query=$this->db->query($sql);
		return $query->num_rows();
		
	}

	function list_show_of_oe($admn){
		$sql="SELECT a.* FROM stu_academic a WHERE a.admn_no='$admn' AND a.enrollment_year>='2019' AND a.auth_id NOT IN ('ug','prep')";
		$query=$this->db->query($sql);
		return $query->num_rows();
	}

	function get_eso_list($dept_id,$course_id,$branch_id,$sess,$sy){
        $sql="SELECT * from
(SELECT a.sub_category FROM cbcs_subject_offered a WHERE /*a.dept_id='cse' AND */a.course_id='$course_id' /*AND 
a.branch_id='cse' */AND a.session_year='$sy' AND a.`session`='$sess' AND a.sub_category LIKE 'eso%'
UNION  
SELECT a.eso_type as sub_category FROM cbcs_guided_eso a WHERE a.dept_id='$dept_id' AND a.course_id='$course_id' AND 
a.branch_id='$branch_id' AND a.session_year='$sy' AND a.`session`='$sess' AND a.eso_type LIKE 'eso%') X 
GROUP BY X.sub_category";
    $query=$this->db->query($sql);
    //echo $this->db->last_query();exit;
    return $query->result();
    }

    function get_e_so_subject_from_guided_eso($dept_id,$course_id,$branch_id,$sess,$sy,$et){
        $sql="SELECT b.*,a.eso_type,1 as remark,c.name AS bname
FROM cbcs_guided_eso a 
JOIN cbcs_subject_offered b ON b.id=a.sub_offered_id
JOIN cbcs_branches c ON a.branch_id=c.id
WHERE a.session_year='$sy' AND a.`session`='$sess' AND a.dept_id='$dept_id' AND a.course_id='$course_id' AND a.branch_id='$branch_id'
AND a.eso_type='$et'";
    $query=$this->db->query($sql);
//echo $this->db->last_query();
    return $query->result();
    }

    function get_e_so_subject_from_offered_eso($course_id,$sess,$sy,$et){
        /*$sql="SELECT a.*,a.sub_category as eso_type,0 as remark,c.name AS bname
FROM cbcs_subject_offered a
JOIN cbcs_branches c ON a.branch_id=c.id
WHERE a.session_year='$sy' AND a.`session`='$sess' AND a.course_id='$course_id' AND a.sub_category='$et'";*/
$sql="SELECT a.*,'$et' as eso_type,0 as remark,c.name AS bname
FROM cbcs_subject_offered a
JOIN cbcs_branches c ON a.branch_id=c.id
WHERE a.session_year='$sy' AND a.`session`='$sess' /*AND a.course_id='$course_id'*/ AND a.sub_category like 'ESO%'
AND a.id NOT IN (SELECT b.sub_offered_id
FROM cbcs_guided_eso b
WHERE b.session_year='$sy' AND b.`session`='$sess' AND b.dept_id='$dept_id' AND b.course_id='$course_id'
AND b.branch_id='$branch_id' AND b.eso_type LIKE 'ESO%')
ORDER BY a.sub_code";
    $query=$this->db->query($sql);
//echo $this->db->last_query();exit;
    return $query->result();
    }

    function get_backlog_subject_list($sy,$session,$dept_id,$course,$branch,$branch_id,$course_id,$admn,$stu_auth,$en_year){
	$q="SELECT v.*,IFNULL(cso.id,oso.id) as sub_offered_id,
/*if(v.sub_code=oso.sub_code,concat('o',oso.id),if(v.sub_code=cso.sub_code,concat('c',cso.id),'')) as sub_offered_id,*/
 IF((CASE WHEN cso.sub_code IS NULL THEN oso.sub_code ELSE cso.sub_code END) IS NULL, s.subject_id,
(CASE WHEN cso.sub_code IS NULL THEN oso.sub_code ELSE cso.sub_code END)) AS subcode,
 IF((CASE WHEN cso.sub_code IS NULL THEN oso.sub_name ELSE cso.sub_name END) IS NULL,
     s.name, (CASE WHEN cso.sub_code IS NULL THEN oso.sub_name ELSE cso.sub_name END)) AS subname,
         IF((CASE WHEN cso.sub_code IS NULL THEN oso.lecture ELSE cso.lecture END) IS NULL, s.lecture,
             (CASE WHEN cso.sub_code IS NULL THEN oso.lecture ELSE cso.lecture END)) AS lecture,
                 IF((CASE WHEN cso.sub_code IS NULL THEN oso.practical ELSE cso.practical END) IS NULL, s.practical, (CASE WHEN cso.sub_code IS NULL THEN oso.practical ELSE cso.practical END)) AS practical, IF((CASE WHEN cso.sub_code IS NULL THEN oso.tutorial ELSE cso.tutorial END) IS NULL, s.tutorial, (CASE WHEN cso.sub_code IS NULL THEN oso.tutorial ELSE cso.tutorial END)) AS tutorial, IF((CASE WHEN cso.sub_code IS NULL THEN oso.sub_type ELSE cso.sub_type END) IS NULL, s.`type`, (CASE WHEN cso.sub_code IS NULL THEN oso.sub_type ELSE cso.sub_type END)) AS sub_type, IF((CASE WHEN cso.sub_code IS NULL THEN oso.credit_hours ELSE cso.credit_hours END) IS NULL, s.credit_hours, (CASE WHEN cso.sub_code IS NULL THEN oso.credit_hours ELSE cso.credit_hours END)) AS credit_hours, IF((CASE WHEN cso.sub_code IS NULL THEN oso.contact_hours ELSE cso.contact_hours END) IS NULL, s.contact_hours, (CASE WHEN cso.sub_code IS NULL THEN oso.contact_hours ELSE cso.contact_hours END)) AS contact_hours,
                 (CASE WHEN cso.sub_code IS NULL THEN 'OLD' ELSE 'CBCS' END) AS type
FROM
(
SELECT y.session_yr,y.session,y.dept,y.course,y.branch,y.semester, fd.mis_sub_id, fd.sub_code, fd.grade,y.admn_no
FROM


(
SELECT x.*
FROM

(
SELECT a.session_yr,a.session,a.admn_no,a.dept,a.course,a.branch,a.semester,a.id,a.`status`
FROM final_semwise_marks_foil_freezed AS a
WHERE a.admn_no='$admn'
ORDER BY a.semester,a.admn_no,a.actual_published_on DESC
LIMIT 10000)x
GROUP BY x.semester) y
JOIN
final_semwise_marks_foil_desc_freezed fd ON fd.foil_id=y.id AND fd.admn_no=y.admn_no AND fd.grade='F'
GROUP BY fd.sub_code
ORDER BY y.session_yr,y.dept,y.course,y.branch,y.semester,fd.sub_code)v

LEFT JOIN cbcs_subject_offered cso ON cso.sub_code=v.sub_code AND v.course=cso.course_id AND  (case when v.course<>'comm' then   v.branch=cso.branch_id  else 1=1  end ) and  v.session_yr=cso.session_year and v.session=cso.session
LEFT JOIN old_subject_offered oso ON oso.sub_code=v.sub_code AND v.course=oso.course_id AND v.branch=oso.branch_id and  v.session_yr=oso.session_year and v.session=oso.session
/* LEFT JOIN subjects s ON (CASE WHEN v.mis_sub_id IS NOT NULL THEN s.id ELSE s.subject_id END)=(CASE WHEN v.mis_sub_id IS NOT NULL THEN v.mis_sub_id ELSE v.sub_code END)*/
LEFT JOIN subjects s ON
(CASE WHEN v.mis_sub_id IS NOT NULL    THEN s.id  when  v.mis_sub_id is null  and (cso.sub_code is  null   and  oso.sub_code is   null) then s.subject_id END)=
(CASE WHEN v.mis_sub_id IS NOT NULL  THEN  v.mis_sub_id  when  v.mis_sub_id is null  and (cso.sub_code is  null   and  oso.sub_code is  null) then v.sub_code END)";
	$result=$this->db->query($q);
	//echo $this->db->last_query();exit;
	return $result->result();

	}

	function alternate_course_check($sess,$sy,$id){
	$sql="SELECT a.subject_id,
	if(d.id IS NOT NULL, d.id,if(e.id IS NOT NULL,e.id,null)) AS sub_offer_id, if(d.id IS NOT NULL, 'CBCS',if(e.id IS NOT NULL,'OLD',null)) AS type,
	/*ifnull(d.id,e.id) as sub_offered_id,*/b.alternate_subject_code AS new_code1,b.alternate_subject_name AS new_sub1,b.alternate_subject_lecture AS l1,b.alternate_subject_tutorial AS t1,b.alternate_subject_practical AS p1,c.alternate_subject_code AS new_code2,c.alternate_subject_name AS new_sub2,c.alternate_subject_lecture AS l2,c.alternate_subject_tutorial AS t2,c.alternate_subject_practical AS p2
	FROM subjects a
	LEFT JOIN cbcs_subject_offered d on trim(a.subject_id)=d.sub_code
	LEFT JOIN old_subject_offered e on trim(a.subject_id)=e.sub_code
	LEFT JOIN alternate_course b ON b.admn_no='$id' AND b.old_subject_code=a.subject_id AND b.session_year='$sy' AND b.`session`='$sess'
	LEFT JOIN alternate_course_all c ON a.subject_id=c.old_subject_code  group by trim(a.subject_id)";

	$query=$this->db->query($sql);
	//echo  $this->db->last_query(); exit;
	    return $query->result();
	}

	function course_offer_check($sess,$sy,$id){
    $sql="(select a.sub_code,a.id,'CBCS' AS type from cbcs_subject_offered a where a.session_year='$sy' and a.`session`='$sess')
	union
	(select a.sub_code,a.id,'OLD' AS type from old_subject_offered a where a.session_year='$sy' and a.`session`='$sess')";

	$query=$this->db->query($sql);
	//echo $this->db->last_query();exit;
	    return $query->result();
	}

	function get_pass_papers($id){
    $myquery="SELECT v.*,IFNULL(cso.id,oso.id) as sub_offered_id,
if(v.sub_code=oso.sub_code,concat('o',oso.id),if(v.sub_code=cso.sub_code,concat('c',cso.id),'')) as sub_offered_id,
 IF((CASE WHEN cso.sub_code IS NULL THEN oso.sub_code ELSE cso.sub_code END) IS NULL, s.subject_id,
(CASE WHEN cso.sub_code IS NULL THEN oso.sub_code ELSE cso.sub_code END)) AS subcode,
 IF((CASE WHEN cso.sub_code IS NULL THEN oso.sub_name ELSE cso.sub_name END) IS NULL,
     s.name, (CASE WHEN cso.sub_code IS NULL THEN oso.sub_name ELSE cso.sub_name END)) AS subname,
         IF((CASE WHEN cso.sub_code IS NULL THEN oso.lecture ELSE cso.lecture END) IS NULL, s.lecture,
             (CASE WHEN cso.sub_code IS NULL THEN oso.lecture ELSE cso.lecture END)) AS lecture,
                 IF((CASE WHEN cso.sub_code IS NULL THEN oso.practical ELSE cso.practical END) IS NULL, s.practical, (CASE WHEN cso.sub_code IS NULL THEN oso.practical ELSE cso.practical END)) AS practical, IF((CASE WHEN cso.sub_code IS NULL THEN oso.tutorial ELSE cso.tutorial END) IS NULL, s.tutorial, (CASE WHEN cso.sub_code IS NULL THEN oso.tutorial ELSE cso.tutorial END)) AS tutorial, IF((CASE WHEN cso.sub_code IS NULL THEN oso.sub_type ELSE cso.sub_type END) IS NULL, s.`type`, (CASE WHEN cso.sub_code IS NULL THEN oso.sub_type ELSE cso.sub_type END)) AS sub_type, IF((CASE WHEN cso.sub_code IS NULL THEN oso.credit_hours ELSE cso.credit_hours END) IS NULL, s.credit_hours, (CASE WHEN cso.sub_code IS NULL THEN oso.credit_hours ELSE cso.credit_hours END)) AS credit_hours, IF((CASE WHEN cso.sub_code IS NULL THEN oso.contact_hours ELSE cso.contact_hours END) IS NULL, s.contact_hours, (CASE WHEN cso.sub_code IS NULL THEN oso.contact_hours ELSE cso.contact_hours END)) AS contact_hours,
                 (CASE WHEN cso.sub_code IS NULL THEN 'OLD' ELSE 'CBCS' END) AS type
FROM
(
SELECT y.session_yr,y.session,y.dept,y.course,y.branch,y.semester, fd.mis_sub_id, fd.sub_code, fd.grade,y.admn_no
FROM


(
SELECT x.*
FROM

(
SELECT a.session_yr,a.session,a.admn_no,a.dept,a.course,a.branch,a.semester,a.id,a.`status`
FROM final_semwise_marks_foil_freezed AS a
WHERE a.admn_no='$id'
ORDER BY a.semester,a.admn_no,a.actual_published_on DESC
LIMIT 10000)x
GROUP BY x.semester) y
JOIN
final_semwise_marks_foil_desc_freezed fd ON fd.foil_id=y.id AND fd.admn_no=y.admn_no AND fd.grade<>'F'
GROUP BY fd.sub_code
ORDER BY y.session_yr,y.dept,y.course,y.branch,y.semester,fd.sub_code)v

LEFT JOIN cbcs_subject_offered cso ON cso.sub_code=v.sub_code AND v.course=cso.course_id AND  (case when v.course<>'comm' then   v.branch=cso.branch_id  else 1=1  end ) and  v.session_yr=cso.session_year and v.session=cso.session
LEFT JOIN old_subject_offered oso ON oso.sub_code=v.sub_code AND v.course=oso.course_id AND v.branch=oso.branch_id and  v.session_yr=oso.session_year and v.session=oso.session
/* LEFT JOIN subjects s ON (CASE WHEN v.mis_sub_id IS NOT NULL THEN s.id ELSE s.subject_id END)=(CASE WHEN v.mis_sub_id IS NOT NULL THEN v.mis_sub_id ELSE v.sub_code END)*/
LEFT JOIN subjects s ON
(CASE WHEN v.mis_sub_id IS NOT NULL    THEN s.id  when  v.mis_sub_id is null  and (cso.sub_code is  null   and  oso.sub_code is   null) then s.subject_id END)=
(CASE WHEN v.mis_sub_id IS NOT NULL  THEN  v.mis_sub_id  when  v.mis_sub_id is null  and (cso.sub_code is  null   and  oso.sub_code is  null) then v.sub_code END)";

        $query = $this->db->query($myquery,$id);
//echo $this->db->last_query();
        if ($query->num_rows() > 0) {
            return $query->result();
        } else {
            return false;
        }
}


//subject offer in current semester.
function present_offered_subject($id){
    $sql="(select x.subject_code from
    (SELECT a.*
    FROM cbcs_stu_course a
    WHERE a.admn_no='$id'
    ) x
    join
    (SELECT a.session_year,a.session
    FROM reg_regular_form a
    WHERE a.admn_no='$id' order by a.semester desc limit 1) y
    on y.session_year=x.session_year and y.session=x.session)
    union
    (select x.subject_code from
    (SELECT a.*
    FROM old_stu_course a
    WHERE a.admn_no='$id'
    ) x
    join
    (SELECT a.session_year,a.session
    FROM reg_regular_form a
    WHERE a.admn_no='$id' order by a.semester desc limit 1) y
    on y.session_year=x.session_year and y.session=x.session)";
    $query=$this->db->query($sql);
		//echo $this->db->last_query();die();
    return $query->result();
}

    function get_drop_paper($id,$sy,$sess){//get subject not in pass, fail list.
        $sql="SELECT a.*,if(strcmp(s.id,a.sub_id)=0,s.subject_id,a.sub_id) as sub_id,IF((CASE WHEN cso.sub_code IS NULL THEN oso.sub_name ELSE cso.sub_name END) IS NULL,
 s.name, (CASE WHEN cso.sub_code IS NULL THEN oso.sub_name ELSE cso.sub_name END)) AS subname,IF((CASE WHEN cso.sub_code IS NULL THEN oso.lecture ELSE cso.lecture END) IS NULL, s.lecture,(CASE WHEN cso.sub_code IS NULL THEN oso.lecture ELSE cso.lecture END)) AS lecture,
IF((CASE WHEN cso.sub_code IS NULL THEN oso.practical ELSE cso.practical END) IS NULL, s.practical, (CASE WHEN cso.sub_code IS NULL THEN oso.practical ELSE cso.practical END)) AS practical,
IF((CASE WHEN cso.sub_code IS NULL THEN oso.tutorial ELSE cso.tutorial END) IS NULL, s.tutorial, (CASE WHEN cso.sub_code IS NULL THEN oso.tutorial ELSE cso.tutorial END)) AS tutorial,
IF((CASE WHEN cso.sub_code IS NULL THEN oso.sub_type ELSE cso.sub_type END) IS NULL, s.`type`, (CASE WHEN cso.sub_code IS NULL THEN oso.sub_type ELSE cso.sub_type END)) AS sub_type, IF((CASE WHEN cso.sub_code IS NULL THEN oso.credit_hours ELSE cso.credit_hours END) IS NULL, s.credit_hours, (CASE WHEN cso.sub_code IS NULL THEN oso.credit_hours ELSE cso.credit_hours END)) AS credit_hours, IF((CASE WHEN cso.sub_code IS NULL THEN oso.contact_hours ELSE cso.contact_hours END) IS NULL, s.contact_hours, (CASE WHEN cso.sub_code IS NULL THEN oso.contact_hours ELSE cso.contact_hours END)) AS contact_hours,
IFNULL(cso.id,oso.id) as sub_offered_id,(CASE WHEN cso.sub_code IS NULL THEN 'OLD' ELSE 'CBCS' END) AS type
FROM stu_exam_absent_mark a
left join subjects s on /*s.subject_id=a.sub_id*/ (s.id=a.sub_id or s.subject_id=a.sub_id)
left join cbcs_subject_offered cso on cso.sub_code=a.sub_id AND cso.session_year='$sy' AND cso.`session`='$sess'
left join old_subject_offered oso on oso.sub_code=a.sub_id AND oso.session_year='$sy' AND oso.`session`='$sess'
WHERE a.admn_no='$id' AND a.`status`='B' and if(strcmp(s.id,a.sub_id)=0,s.subject_id,a.sub_id) not in(SELECT v.sub_code
FROM
(
SELECT y.session_yr,y.session,y.dept,y.course,y.branch,y.semester, fd.mis_sub_id, fd.sub_code, fd.grade,y.admn_no
FROM


(
SELECT x.*
FROM

(
SELECT a.session_yr,a.session,a.admn_no,a.dept,a.course,a.branch,a.semester,a.id,a.`status`
FROM final_semwise_marks_foil_freezed AS a
WHERE a.admn_no='$id'
ORDER BY a.semester,a.admn_no,a.actual_published_on DESC
LIMIT 10000)x
GROUP BY x.semester) y
JOIN
final_semwise_marks_foil_desc_freezed fd ON fd.foil_id=y.id AND fd.admn_no=y.admn_no
GROUP BY fd.sub_code
ORDER BY y.session_yr,y.dept,y.course,y.branch,y.semester,fd.sub_code)v

)
GROUP BY a.sub_id";

$query=$this->db->query($sql);
    //echo $this->db->last_query();
        return $query->result();
    }

  	function all_offered_courses($sy,$session,$dept_id,$course,$branch,$branch_id,$course_id,$admn,$stu_auth,$en_year,$form_id){
  		$sql="SELECT * FROM ((SELECT a.*,'' AS map_id,'CBCS' AS type,b.name AS bname
FROM cbcs_subject_offered a
INNER JOIN cbcs_branches b ON b.id=a.branch_id
WHERE a.session_year='$sy' AND a.`session`='$session' AND a.course_id='$course' 
ORDER BY a.sub_code)
UNION 
(select a.*,'OLD' as type,b.name as bname 
from old_subject_offered a 
inner join cbcs_branches b on b.id=a.branch_id
where a.session_year='$sy' AND a.`session`='$session' AND a.course_id='$course' 
ORDER BY a.sub_code)) x ";
		$query=$this->db->query($sql);
    //echo $this->db->last_query();exit;
        return $query->result();
  	}
}


