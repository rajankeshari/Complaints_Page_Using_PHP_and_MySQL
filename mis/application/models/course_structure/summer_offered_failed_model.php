<?php
class Summer_offered_failed_model extends CI_Model
{
	function __construct()
	{
		parent::__construct();
	}
	function get_fail_subject_list($ses_year,$dept_id){
// 		$sql="select v.*,s.name from
// (select  y.session_yr,y.session,y.dept,y.course,y.branch,y.semester ,   fd.sub_code, fd.mis_sub_id,  fd.grade , cs.aggr_id
// from  


// (select x.* from

// (select a.session_yr,a.session,a.admn_no,a.dept,a.course,a.branch,a.semester,a.id from final_semwise_marks_foil_freezed as a
// where a.session_yr= '$ses_year' and a.session<>'Summer' and   a.dept='$dept_id'   order by   a.semester,a.admn_no,a.actual_published_on desc )x
// group by x.admn_no)y
// join 
// final_semwise_marks_foil_desc_freezed fd  on fd.foil_id=y.id  
// join 
// course_structure cs on cs.id=fd.mis_sub_id and  fd.admn_no=y.admn_no  and  fd.grade='F'
// GROUP BY fd.mis_sub_id
// order by y.session_yr,y.dept,y.course,y.branch,y.semester,fd.sub_code)v
// left join subjects s on s.id=v.mis_sub_id";
		$sql="select v.*,s.name ,cc.name as cname,cb.name as bname from
(select  y.session_yr,y.session,y.dept,y.course,y.branch,y.semester ,   fd.sub_code, fd.mis_sub_id,  fd.grade , cs.aggr_id,
if((select count(*) from summer_offered where id=fd.mis_sub_id and aggr_id=cs.aggr_id and session_year=y.session_yr)>0,'y','n') as status
from  


(select x.* from

(select a.session_yr,a.session,a.admn_no,a.dept,a.course,a.branch,a.semester,a.id from final_semwise_marks_foil_freezed as a
where a.session_yr= '$ses_year' and a.session<>'Summer' and   a.dept='$dept_id'   order by   a.semester,a.admn_no,a.actual_published_on desc )x
group by x.admn_no)y
join 
final_semwise_marks_foil_desc_freezed fd  on fd.foil_id=y.id  
join 
course_structure cs on cs.id=fd.mis_sub_id

and  fd.admn_no=y.admn_no  and  fd.grade='F'
GROUP BY fd.mis_sub_id
order by y.session_yr,y.dept,y.course,y.branch,y.semester,fd.sub_code)v
left join subjects s on s.id=v.mis_sub_id
left join cs_courses cc on cc.id=v.course
left join cs_branches cb on cb.id=v.branch
";
		$query=$this->db->query($sql);
		return $query->result();
	}

	function count_summer_data($val){
		$sql="SELECT * FROM `summer_offered` WHERE aggr_id='$val[0]' AND id='$val[1]' AND session_year='$val[2]'";
		$result=$this->db->query($sql);
		return $result->num_rows();
	}

	function insert_summer_offer_fail_sub($val,$date){
		$sql="INSERT INTO `summer_offered` VALUES ('$val[0]','$val[1]','','$val[2]','Summer','$date')";
		// echo '<pre>';
		// print_r($val);

		if($this->db->query($sql))
			return true;
		else
			return false;
	}


	function get_summer_data($dept,$sess_yr){
		$sql="SELECT * FROM `summer_offered` WHERE `aggr_id` LIKE '%$dept%' AND `session_year`='$sess_yr'";
		$result=$this->db->query($sql);
		return $result->result();
	}

	function delete_summer_data($aggr_id,$id){
		$sql="DELETE FROM `summer_offered` WHERE `aggr_id`='$aggr_id' AND `id`='$id'";
		$this->db->query($sql);
	}
}