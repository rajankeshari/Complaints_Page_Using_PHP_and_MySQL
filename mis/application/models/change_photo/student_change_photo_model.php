<?php
Class Student_change_photo_model extends CI_Model{
	function __construct(){
		parent::__construct();
	}

	function show_photo($id){
		$sql="select photopath from user_details WHERE id='$id'";
		$result=$this->db->query($sql);
		return $result->result();
	}

	function update_photo($id,$path){
		$sql="UPDATE user_details set photopath='$path' WHERE id='$id'";
		if($this->db->query($sql))
			return true;
		else
			return false;
	}	
/*	function show_signature($id){
		
		$sql="select signpath from stu_prev_certificate WHERE admn_no='$id'";
		$result=$this->db->query($sql);
		return $result->result();
	}
*/
// Above is original function which does not work properly if the record is not available

		function show_signature($id){
		
		$sql="select signpath from stu_prev_certificate WHERE admn_no=?";
    	if($query=$this->db->query($sql,$id)){
    		if($query->num_rows()==0){
    		//echo "no record"; 
			
			$sql1="INSERT INTO stu_prev_certificate (admn_no, sno, marks_sheet, certificate, specialization, signpath, sub, jee_adv_rollno, jam_reg_id) VALUES ('$id', '1', '', '', '', '', '', '', NULL)";	
				
			//echo $sql1;
			$result1=$this->db->query($sql1);
			$sql="select signpath from stu_prev_certificate WHERE admn_no='$id'";
			$result=$this->db->query($sql);
			return $result->result();
			//die();
    		}
    		else{
    			//echo "yes available"; die();
				$sql="select signpath from stu_prev_certificate WHERE admn_no='$id'";
				$result=$this->db->query($sql);
				return $result->result();
    		}
    	}
    	else{
			$sql="select signpath from stu_prev_certificate WHERE admn_no='$id'";
			$result=$this->db->query($sql);
			return $result->result();
    		
    	}
		
		
	}
	
/*	function show_signature($id){
		
		//$sql="select signpath from stu_prev_certificate WHERE admn_no='.$id.' ";
		$sql1="select signpath from stu_prev_certificate WHERE admn_no='$id'";
		
		echo $sql1 ; die();
		if($this->db->sql->num_rows() == 0)
		{
		$sql="INSERT INTO (`admn_no`, `sno`, `marks_sheet`, `certificate`, `specialization`, `signpath`, `sub`, `jee_adv_rollno`, `jam_reg_id`) VALUES ('$id', '', '', '', '', '', '', '', NULL);
		";	
		$result-insert=$this->db->query($sql);
		$sql1="select signpath from stu_prev_certificate WHERE admn_no='$id'";
		$result=$this->db->query($sql1);
		return $result->result();
		}
		else
		{
		$result=$this->db->query($sql);
		return $result->result();	
		}
		
	}
*/	
	function update_signature($id,$path){
		$sql="UPDATE stu_prev_certificate set signpath='$path' WHERE admn_no='$id'";
		if($this->db->query($sql))
			return true;
		else
			return false;

	}
	
	function search_admno($id) {

        $myquery = "select * from  user_details WHERE id='$id'";

        $query = $this->db->query($myquery);

        if ($query->num_rows() > 0) {

            return $query->row();
        } else {
            return FALSE;
        }
    }
	
	function get_details_for_id_card($id){
		
		/*$sql="SELECT upper(CONCAT_WS(' ',a.first_name,a.middle_name,a.last_name))AS name,
UPPER(a.id)AS admn_no,
DATE_FORMAT(a.dob, '%d-%m-%Y') AS dob,
CASE WHEN a.sex='m' THEN UPPER('MALE') WHEN a.sex='f' THEN UPPER('FEMALE')  ELSE 'OTHER' END AS gender,
upper(c.name) AS course,
UPPER(d.name)AS branch,
UPPER(e.blood_group)AS blood_group,
upper(CONCAT_WS(' ',g.line1,g.line2,g.city,g.state,g.pincode,g.country)) AS 'Permanent_Address',
a.email,
upper(h.father_name)AS father_name,
e.parent_mobile_no AS 'Fathers_Contact_No',
h.mobile_no AS 'Students_Contact_No',
upper(a.allocated_category)AS allocated_category,
a.photopath,
i.signpath,
CONCAT('IIT(ISM)/2019/',right(a.id,3)) AS 'ID_NO',
CONCAT('01-06-',(2019+c.duration)) AS 'Validity',
CONCAT_WS('\n',
upper (CONCAT_WS(' ',a.first_name,a.middle_name,a.last_name)),
UPPER(a.id),DATE_FORMAT(a.dob, '%d-%m-%Y'),
CASE WHEN a.sex='m' THEN UPPER('MALE') WHEN a.sex='f' THEN UPPER('FEMALE')  ELSE 'OTHER' END,
upper(c.name),UPPER(d.name),UPPER(e.blood_group),'\r\n',
upper(CONCAT_WS(' ',g.line1,g.line2,g.city,g.state,g.pincode,g.country)),
a.email,h.mobile_no,e.parent_mobile_no,upper(a.allocated_category),CONCAT('01-06-',(2019+c.duration)),CONCAT('IIT(ISM)/2019/',right(a.id,3))
)as QR

FROM user_details a
INNER JOIN stu_academic b ON b.admn_no=a.id
left JOIN cs_courses c ON c.id=b.course_id
left JOIN cs_branches d ON d.id=b.branch_id
INNER JOIN stu_details e ON e.admn_no=a.id
INNER JOIN stu_other_details f ON f.admn_no=a.id
INNER JOIN user_address g ON g.id=a.id
INNER JOIN user_other_details h ON h.id=a.id
INNER JOIN stu_prev_certificate i ON i.admn_no=a.id
WHERE a.id ='$id' AND g.`type`='permanent' AND i.sno=1 
group by a.id
ORDER BY a.dept_id,b.course_id,b.branch_id,a.id";*/

$sql="SELECT UPPER(CONCAT_WS(' ',a.first_name,a.middle_name,a.last_name)) AS name, UPPER(a.id) AS admn_no, DATE_FORMAT(a.dob, '%d-%m-%Y') AS dob, CASE WHEN a.sex='m' THEN UPPER('MALE') WHEN a.sex='f' THEN UPPER('FEMALE') ELSE 'OTHER' END AS gender, UPPER(c.name) AS course, UPPER(d.name) AS branch, UPPER(e.blood_group) AS blood_group, UPPER(CONCAT_WS(' ',g.line1,g.line2,g.city,g.state,g.pincode,g.country)) AS 'Permanent_Address', a.email, UPPER(h.father_name) AS father_name, e.parent_mobile_no AS 'Fathers_Contact_No', h.mobile_no AS 'Students_Contact_No', UPPER(a.allocated_category) AS allocated_category, a.photopath, i.signpath, null  AS 'ID NO', null AS 'Validity', CONCAT_WS(' ', UPPER (CONCAT_WS(' ',a.first_name,a.middle_name,a.last_name)), UPPER(a.id), DATE_FORMAT(a.dob, '%d-%m-%Y'), CASE WHEN a.sex='m' THEN UPPER('MALE') WHEN a.sex='f' THEN UPPER('FEMALE') ELSE 'OTHER' END, UPPER(c.name), UPPER(d.name), UPPER(e.blood_group),' ', UPPER(CONCAT_WS(' ',g.line1,g.line2,g.city,g.state,g.pincode,g.country)), a.email,h.mobile_no,e.parent_mobile_no, UPPER(a.allocated_category) ) AS QR
FROM user_details a
INNER JOIN stu_academic b ON b.admn_no=a.id
LEFT JOIN cs_courses c ON c.id=b.course_id
LEFT JOIN cs_branches d ON d.id=b.branch_id
INNER JOIN stu_details e ON e.admn_no=a.id
INNER JOIN stu_other_details f ON f.admn_no=a.id
INNER JOIN user_address g ON g.id=a.id
INNER JOIN user_other_details h ON h.id=a.id
INNER JOIN stu_prev_certificate i ON i.admn_no=a.id
WHERE a.id ='$id' AND g.`type`='permanent' AND i.sno=1
GROUP BY a.id
ORDER BY a.dept_id,b.course_id,b.branch_id,a.id";

		$result=$this->db->query($sql);
		return $result->result();
	}
	
	
	function get_details_for_id_card_jrf($id){
		
		

$sql="SELECT UPPER(CONCAT_WS(' ',a.first_name,a.middle_name,a.last_name)) AS name, UPPER(a.id) AS admn_no, DATE_FORMAT(a.dob, '%d-%m-%Y') AS dob, CASE WHEN a.sex='m' THEN UPPER('MALE') WHEN a.sex='f' THEN UPPER('FEMALE') ELSE 'OTHER' END AS gender, UPPER(c.name) AS course, UPPER(d.name) AS branch, UPPER(e.blood_group) AS blood_group, UPPER(CONCAT_WS(' ',g.line1,g.line2,g.city,g.state,g.pincode,g.country)) AS 'Permanent_Address', a.email, UPPER(h.father_name) AS father_name, e.parent_mobile_no AS 'Fathers_Contact_No', h.mobile_no AS 'Students_Contact_No', UPPER(a.allocated_category) AS allocated_category, a.photopath, i.signpath, null  AS 'ID NO', null AS 'Validity', CONCAT_WS(' ', UPPER (CONCAT_WS(' ',a.first_name,a.middle_name,a.last_name)), UPPER(a.id), DATE_FORMAT(a.dob, '%d-%m-%Y'), CASE WHEN a.sex='m' THEN UPPER('MALE') WHEN a.sex='f' THEN UPPER('FEMALE') ELSE 'OTHER' END, UPPER(c.name), UPPER(d.name), UPPER(e.blood_group),' ', UPPER(CONCAT_WS(' ',g.line1,g.line2,g.city,g.state,g.pincode,g.country)), a.email,h.mobile_no,e.parent_mobile_no, UPPER(a.allocated_category) ) AS QR
FROM user_details a
INNER JOIN stu_academic b ON b.admn_no=a.id
LEFT JOIN cs_courses c ON c.id=b.course_id
LEFT JOIN cbcs_departments d ON d.id=a.dept_id
INNER JOIN stu_details e ON e.admn_no=a.id
INNER JOIN stu_other_details f ON f.admn_no=a.id
INNER JOIN user_address g ON g.id=a.id
INNER JOIN user_other_details h ON h.id=a.id
INNER JOIN stu_prev_certificate i ON i.admn_no=a.id
WHERE a.id ='$id' AND g.`type`='permanent' AND i.sno=1
GROUP BY a.id
ORDER BY a.dept_id,b.course_id,b.branch_id,a.id";

		$result=$this->db->query($sql);
		return $result->result();
	}
	
	function get_course_id($id){
		$sql="select course_id from reg_regular_form WHERE admn_no='$id'";
		$result=$this->db->query($sql);
		return $result->result();
	}

}