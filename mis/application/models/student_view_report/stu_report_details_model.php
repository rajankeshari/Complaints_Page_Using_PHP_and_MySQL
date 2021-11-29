<?php

class Stu_Report_details_model extends CI_Model
{
	var $table = 'stu_details';

	function __construct()
	{
		// Call the Model constructor
		parent::__construct();
	}

	
	//*******************Department and Course************
	function getData($dept_nm,$course_nm,$branch_nm,$sem_nm,$state_nm,$marks,$op_type,$category,$bgroup,$year,$gender )
	{
		/*$sql= "SELECT stu_details.admn_no, user_details.religion, user_details.first_name, user_details.middle_name, user_details.last_name, user_details.category, user_details.email, user_details.dept_id, user_other_details.mobile_no, stu_academic.course_id, stu_academic.branch_id, stu_academic.semester, user_address.state, stu_details.blood_group, stu_admn_fee.payment_made_on
FROM ((((user_details INNER JOIN user_other_details ON user_details.id = user_other_details.id) INNER JOIN stu_academic ON user_details.id = stu_academic.admn_no) INNER JOIN user_address ON user_details.id = user_address.id) INNER JOIN stu_details ON user_details.id = stu_details.admn_no) INNER JOIN stu_admn_fee ON user_details.id = stu_admn_fee.admn_no  where 1=1
 ";*/
            $sql="SELECT 
			user_other_details.religion,
			user_other_details.father_name,
  `stu_details`.`admn_no`,
  `user_details`.`first_name`,
  `user_details`.`middle_name`,
  `user_details`.`last_name`,
  `user_details`.`category`,
  `user_details`.`email`,
  `user_details`.`dept_id`,
   DATE_FORMAT(`user_details`.`dob`, '%d-%m-%Y') as  dob,
  `user_other_details`.`mobile_no`,
  `stu_academic`.`auth_id` as  auth, 
  `stu_academic`.`course_id`,
  `stu_academic`.`branch_id`,
  `stu_academic`.`semester`,
  `user_address`.`state`,
   concat_ws(`user_address`.`line1`,`user_address`.`line2`,`user_address`.`city`,`user_address`.`state`,'-',`user_address`.`pincode`,`user_address`.`country`) as full_address,
  `stu_details`.`blood_group`,
  `stu_admn_fee`.`payment_made_on`,
  `users`.`auth_id`,
  b.name as br_name,d.name as dept_name ,c.name as course_name,`stu_academic`.`enrollment_year`,`user_details`.`sex`
FROM
  ((((`user_details`
  INNER JOIN `user_other_details` ON `user_details`.`id` =
    `user_other_details`.`id`)
  INNER JOIN `stu_academic` ON `user_details`.`id` = `stu_academic`.`admn_no`)
  INNER JOIN `user_address` ON `user_details`.`id` = `user_address`.`id`)
  INNER JOIN `stu_details` ON `user_details`.`id` = `stu_details`.`admn_no`)
  INNER JOIN `stu_admn_fee` ON `user_details`.`id` = `stu_admn_fee`.`admn_no`
  INNER JOIN `users` ON `user_details`.`id` = `users`.`id`
  left join cbcs_departments d on d.id=user_details.dept_id 
  left join cbcs_branches b on b.id=stu_academic.branch_id 
  left join cbcs_courses c on c.id=stu_academic.course_id 
WHERE
  `users`.`auth_id` = 'stu' AND `users`.`status` = 'A' AND
  1 = 1 ";
		
		
			
			if ($dept_nm)
			{
					$sql .= " AND user_details.dept_id='".$dept_nm."'";
			}
			if ($course_nm)
			{
				if($course_nm=='jrf'){
                                     $sql .= " AND stu_academic.auth_id='".$course_nm."'";
                                }else{	
                                $sql .= " AND stu_academic.course_id='".$course_nm."'";
                                }
			}
			if ($branch_nm)
			{
					$sql .= " AND stu_academic.branch_id='".$branch_nm."'";
			}
			if ($sem_nm)
			{
					$sql .= " AND stu_academic.semester='".$sem_nm."'";
			}
			if ($state_nm)
			{
					$sql .= " AND user_address.state='".$state_nm."' And user_address.type='permanent'";
			}
			if ($category)
			{
				if($category=='pc'){
                                $sql .= " AND user_details.physically_challenged='yes'";
                                }
                                else{
                                    $sql .= " AND user_details.category='".$category."'";
                                }
			}
			if ($bgroup)
			{
					$sql .= " AND stu_details.blood_group='".$bgroup."'";
			}
			if ($year)
			{
					$sql .= " AND stu_academic.enrollment_year='".$year."'";
			}
                        if ($gender)
			{
					$sql .= " AND user_details.sex='".$gender."'";
			}
			
			
			
			
			
			
			
			$query = $this->db->query("$sql group by stu_details.admn_no   order by  `user_details`.`dept_id`,`stu_academic`.`course_id`,
  `stu_academic`.`branch_id`,stu_details.admn_no");

                       // echo $this->db->last_query();die();
			if($query->num_rows() == 0)	return FALSE;
			return $query->result();
		
	}
	
}
?>
