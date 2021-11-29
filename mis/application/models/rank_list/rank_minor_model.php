<?php if(!defined("BASEPATH")){ exit("No direct script access allowed"); }

class Rank_minor_model extends CI_Model{

	 function __construct(){

	 		parent::__construct();
	}

	function getTabResSemesterMinorRange($admn_no,$lastsem){

			// Semester Loop			
		for($i=5; $i<=$lastsem; $i++){

				$r[$i] = (object) array();
				$q=$this->db->query("select A.*,c.grade,d.points,sum((A.credit_hours*d.points)) as cpts,sum(A.credit_hours) as chr from (
SELECT subjects.id,subjects.subject_id,subjects.name,subjects.credit_hours,k.admn_no
FROM 
(
SELECT hf2.admn_no,hm_minor_details.branch_id
FROM hm_form hf2
INNER JOIN hm_minor_details ON hm_minor_details.form_id=hf2.form_id AND hm_minor_details.offered='1' 
AND hf2.minor='1' AND hf2.minor_hod_status='Y' AND hf2.admn_no='$admn_no'
)k
INNER JOIN course_structure cs2 ON cs2.aggr_id= CONCAT('minor','_',k.branch_id,'_2013_2014') AND cs2.semester='$i'
LEFT JOIN subjects ON subjects.id = cs2.id)A
inner join marks_master b on A.id=b.subject_id
inner join marks_subject_description c on c.marks_master_id=b.id and A.admn_no=c.admn_no
inner join grade_points d on d.grade=c.grade");
			
				$rr = $q->row();
				$r[$i]->cpts = $rr->cpts;
				$r[$i]->chr = $rr->chr;
				$r[$i]->gpa = $rr->cpts/$rr->chr;
				$r[$i]->totchr = $rr->chr +$r[$i-1]->totchr;
				$r[$i]->totcpts = $rr->cpts +$r[$i-1]->totcpts;
				$r[$i]->cgpa = round($r[$i]->totcpts / $r[$i]->totchr,2);

		}
				

			return $r;
	}
}