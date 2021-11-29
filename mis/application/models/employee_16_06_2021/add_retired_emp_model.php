<?php

if (!defined('BASEPATH'))
    exit('No direct script access allowed');

class Add_retired_emp_model extends CI_Model {

    public function __construct() {
        parent::__construct();
    }


   /* function check_medi_issue_vs_counter($pid,$prel,$pvdate) {
        $query = $this->db->query("select x.* from(
SELECT  a.pid, a.prel,a.m_status,a.visit_date,a.mid,a.mqty,
CASE WHEN b.cs_qty is null THEN 0 ELSE b.cs_qty END as cs_qty,
CASE WHEN a.mqty > b.cs_qty THEN 'false' WHEN b.cs_qty is null THEN 'false' ELSE 'true' END as medi_status,c.m_name
 FROM hc_patient a
left join hc_counter_master b on b.m_id=a.mid
inner join hc_medicine c on c.m_id=a.mid
WHERE a.pid='".$pid."' AND a.prel='".$prel."' AND DATE(a.visit_date)= '".$pvdate."')x
where x.medi_status='false'");


        if ($query->num_rows() > 0) {
            return $query->result();
        } else {
            return false;
        }
    }
*/
	// INSERT INTO `retired_emp_details` (`id_no`, `employee_id`, `salutation`, `first_name`, `middle_name`, `last_name`, `dob`,
	// `membership_valid`, `profile_pic`) VALUES (NULL, 'hjghjghjghj645645', 'hjghjghjghj645645', 'hjghjghjghj645645', 'hjghjghjghj645645',
	// 'hjghjghjghj645645', 'hjghjghjghj645645', 'vhjghjghjghj645645', 'hjghjghjghj645645');
	
	function validateEmpid( $employee_id){
		
		$user_exists = "SELECT `id` FROM `mis_40_50`.`user_details` WHERE id='$employee_id'"; //die();
        $query =$this->db->query($user_exists);
		    //= $this->db->get('roles');
		    $num = $query->num_rows();
    		if ($num>0){
    		return true;
    		}
    		else{
    		return false;
    		}
		
	}

    function insert_details($id_no, $employee_id, $salutation, $first_name, $middle_name, $last_name, $dob,
	$membership_valid, $profile_pic)
	{
		/*
    $sql = " INSERT INTO `retired_emp_details` (`id_no`, `employee_id`, `salutation`, `first_name`, `middle_name`, `last_name`, `dob`,
    `membership_valid`, `profile_pic`) values ('$id_no', '$employee_id', '$salutation', '$first_name', '$middle_name', '$last_name', '$dob',
    '$membership_valid', '$profile_pic')";
        $this->db->query($sql);
        */

    $user_details = "INSERT INTO `mis_40_50`.`user_details` (`id`, `salutation`, `first_name`, `middle_name`, `last_name`, `sex`, `category`, `allocated_category`,
       `dob`, `email`, `photopath`, `marital_status`, `physically_challenged`, `dept_id`) VALUES ('$employee_id', '$salutation', '$first_name', '$middle_name', '$last_name',
          'na', 'na', 'na', '$dob',
         'na', '$profile_pic', 'na', 'na', 'admin')"; //die();
            $this->db->query($user_details);
	}



    function insert_family_details($employee_id,$name,$relation,$dob,$profile_pic,$i)
    {
      /*
       $sql = " INSERT INTO `family_details`(`employee_id`, `depennm`, `relationship`, `dob`, `profile_pic`) VALUES ('$employee_id','$name','$relation','$dob','$pic') ";
       $this->db->query($sql);
    */
        $i=$i+1;
       $emp_dependent_images="INSERT INTO `mis_40_50`.`emp_dependent_images` ( `emp_no`, `sno`, `new_photopath`, `status`, `validated_by`, `validated_timestamp`) VALUES
                                  ('$employee_id', '$i', '$profile_pic', 'pending', '1142', CURRENT_TIMESTAMP)";
       $this->db->query($emp_dependent_images);

      # INSERT INTO `emp_basic_details` (`emp_no`, `auth_id`, `designation`, `office_no`, `fax`, `joining_date`, `retirement_ext`, `retirement_date`, `employment_nature`) VALUES ('r0002', 'ft', 'astprf', NULL, NULL, '2018-07-01', '0', '2018-07-02', 'parmanent')
      $emp_basic_details ="INSERT INTO `emp_basic_details` (`emp_no`, `auth_id`, `designation`, `office_no`, `fax`, `joining_date`, `retirement_ext`, `retirement_date`, `employment_nature`) VALUES
                                                ('$employee_id', 'retd_emp', 'others', NULL, NULL, '1926-01-01', '0', '1976-01-01', 'permanent')";
      $this->db->query($emp_basic_details);

      # INSERT INTO `emp_family_details` (`emp_no`, `sno`, `name`, `relationship`, `profession`, `present_post_addr`, `photopath`, `dob`, `active_inactive`, `emp_dep_allergy`, `emp_dep_disease`) VALUES ('r0001', '1', 'srinu', 'son', 'student', 'na', 'employee/images.jpg', '2018-07-19', 'active', NULL, NULL);
      $emp_family_details ="INSERT INTO `emp_family_details` (`emp_no`, `sno`, `name`, `relationship`, `profession`, `present_post_addr`, `photopath`, `dob`, `active_inactive`, `emp_dep_allergy`,
         `emp_dep_disease`) VALUES ('$employee_id', '$i', '$name', '$relation', 'na', 'na', '$profile_pic', '$dob', 'active', NULL, NULL);";
      $this->db->query($emp_family_details);
    }

}
?>
