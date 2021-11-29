<?php

class Leave_all_kind_application_model extends CI_Model {

    function __construct() {
        parent::__construct();
    }

    function get_nature_of_leave_details() {

        $myquery = "select nature_of_leave,description from leave_types_master";

        $query = $this->db->query($myquery);

        if ($query->num_rows() > 0) {
            return $query->result();
        } else {
            return false;
        }
    }
	
	function get_authority_types() {

        //$myquery = "select * from auth_types order by type";
	  $myquery = "select * from auth_types where id in ('hod','dean_fnp','dean_rnd','dean_acad','dean_fnp','hos','rg','dsw','ddt','dt','rg','dean_infra','dean_iraa','dean_is','jee_c','piclib') order by type";


        $query = $this->db->query($myquery);

        if ($query->num_rows() > 0) {
            return $query->result();
        } else {
            return false;
        }
    }
	function get_official_tour_purpose_of_visit() {

        $myquery = "select purpose_of_visit from leave_official_tour_purpose_of_visit";

        $query = $this->db->query($myquery);

        if ($query->num_rows() > 0) {
            return $query->result();
        } else {
            return false;
        }
    }

    function get_emp_name($uid) {

        $myquery = "select ud.salutation, ud.first_name, ud.middle_name, ud.last_name from user_details ud  where ud.id=?";

        $query = $this->db->query($myquery, $uid);

        if ($query->num_rows() > 0) {
            return $query->result();
        } else {
            return false;
        }
    }

    function get_department($uid) {

        $myquery = "select name from departments  where id=(select dept_id from user_details where id=?)";

        $query = $this->db->query($myquery, $uid);

        if ($query->num_rows() > 0) {
            return $query->result();
        } else {
            return false;
        }
    }

    function get_designation($uid) {

        $myquery = "select name from designations where id=(select designation from emp_basic_details where emp_no=?)";

        $query = $this->db->query($myquery, $uid);

        if ($query->num_rows() > 0) {
            return $query->result();
        } else {
            return false;
        }
    }

    function get_archive($uid) {

        /* $myquery = "select x.* from
          (select lakd.ref_id,lakd.created_on,lat.status from leave_all_kind_details lakd
          inner join leave_auth_table lat on lakd.ref_id=lat.ref_id where lakd.empno=?
          order by lat.level desc limit 10000000 )x
          group by x.ref_id "; */
        $myquery = "select x.* from
(select lakd.ref_id,lakd.created_on,lakd.created_by,lat.status,lat.reason from leave_all_kind_details lakd
inner join leave_auth_table lat on lakd.ref_id=lat.ref_id where lakd.empno=?
 order by lat.level desc limit 10000000 )x
 group by x.ref_id order by x.created_on desc";

        $query = $this->db->query($myquery, $uid);

        if ($query->num_rows() > 0) {
            return $query->result();
        } else {
            return false;
        }
    }
	
	function get_admin_side_archive($uid) {

       /*$myquery = "select x.* from
(select lakd.empno, lakd.ref_id,lakd.created_on,lat.status from leave_all_kind_details lakd
inner join leave_auth_table lat on lakd.ref_id=lat.ref_id
 order by lat.level desc limit 10000000 )x
 group by x.ref_id order by x.created_on desc";*/
 
 $myquery="select y.* from (select x.* from (select lat.ref_id, lat.submitted_TS, lakd.empno, ud.salutation, ud.first_name, ud.middle_name, ud.last_name, dpt.name, desg.name as designation,lat.status, lat.level, lat.submitted_to, lat.reason
from leave_auth_table lat
inner join leave_all_kind_details lakd on lat.ref_id=lakd.ref_id
inner join user_details ud on lakd.empno=ud.id
inner join departments dpt on ud.dept_id=dpt.id
inner join emp_basic_details ebs on lakd.empno=ebs.emp_no
inner join designations desg on ebs.designation=desg.id
where lat.ref_id in (select distinct lat.ref_id from leave_auth_table lat where lat.status='approved') order by  lat.`level` desc limit 1000000000000000)x group by x.ref_id)y where y.submitted_to=? and y.level<>'0' and y.reason<>'Updated' order by y.submitted_TS desc";

        $query = $this->db->query($myquery, $uid);

        if ($query->num_rows() > 0) {
            return $query->result();
        } else {
            return false;
        }
    }
	
	function get_admin_side_archive_history($uid){
		/*$myquery="select y.* from (select x.* from (select lat.ref_id, lat.submitted_TS, lakd.empno, ud.salutation, ud.first_name, ud.middle_name, ud.last_name, dpt.name, desg.name as designation,lat.status, lat.level, lat.submitted_to, lat.reason
from leave_auth_table lat
inner join leave_all_kind_details lakd on lat.ref_id=lakd.ref_id
inner join user_details ud on lakd.empno=ud.id
inner join departments dpt on ud.dept_id=dpt.id
inner join emp_basic_details ebs on lakd.empno=ebs.emp_no
inner join designations desg on ebs.designation=desg.id
where lat.ref_id in (select distinct lat.ref_id from leave_auth_table lat where lat.status='approved') order by  lat.`level` desc limit 1000)x group by x.ref_id)y where y.submitted_to=? and y.level<>'0' and y.reason='Updated' order by y.submitted_TS desc";*/

$myquery="select y.* from (select x.* from (select lakd.created_by,lat.ref_id, lat.submitted_TS, lakd.empno, ud.salutation, ud.first_name, ud.middle_name, ud.last_name, dpt.name, desg.name as designation,lat.status, lat.level, lat.submitted_to, lat.reason
from leave_auth_table lat
inner join leave_all_kind_details lakd on lat.ref_id=lakd.ref_id
inner join user_details ud on lakd.empno=ud.id
inner join departments dpt on ud.dept_id=dpt.id
inner join emp_basic_details ebs on lakd.empno=ebs.emp_no
inner join designations desg on ebs.designation=desg.id
where lat.ref_id in (select distinct lat.ref_id from leave_auth_table lat where lat.status='approved') order by  lat.`level` desc limit 1000000000000000)x group by x.ref_id)y where y.submitted_to=? and y.reason='Updated' order by y.submitted_TS desc";

        $query = $this->db->query($myquery, $uid);

        if ($query->num_rows() > 0) {
            return $query->result();
        } else {
            return false;
        }
	}

    function get_basic_leave_details($ref_id) {
        $myquery = "select * from leave_all_kind_details where ref_id=?";
        $query = $this->db->query($myquery, $ref_id);
        if ($query->num_rows() > 0) {
            return $query->result();
        } else {
            return false;
        }
    }
	
	function get_leave_description($description) {
        $myquery = "select description from leave_types_master where nature_of_leave=?";
        $query = $this->db->query($myquery, $description);
        if ($query->num_rows() > 0) {
            return $query->result();
        } else {
            return false;
        }
    }
	
	

    function get_tour_details($ref_id) {
        $myquery = "select * from leave_tour_details where ref_id=?";
        $query = $this->db->query($myquery, $ref_id);
        if ($query->num_rows() > 0) {
            return $query->result();
        } else {
            return false;
        }
    }

    function get_ltc_details($ref_id) {
        $myquery = "select * from leave_ltc_details where ref_id=?";
        $query = $this->db->query($myquery, $ref_id);
        if ($query->num_rows() > 0) {
            return $query->result();
        } else {
            return false;
        }
    }

    function get_auth_details1($ref_id) {


        /* $myquery = "select ud.salutation,ud.first_name,ud.middle_name,ud.last_name,auth.type from leave_auth_table lat
          inner join leave_all_kind_details lakd on lat.ref_id=lakd.ref_id
          inner join user_details ud on lat.submitted_to=ud.id
          inner join user_auth_types uat on lat.submitted_to=uat.id
          inner join auth_types auth on uat.auth_id=auth.id
          where lat.ref_id=? group by lat.submitted_to order by lat.level asc"; */
        $myquery = "select ud.salutation,ud.first_name,ud.middle_name,ud.last_name,lat.submitted_TS from leave_auth_table lat
inner join leave_all_kind_details lakd on lat.ref_id=lakd.ref_id
inner join user_details ud on lat.submitted_to=ud.id
where lat.ref_id=? group by lat.submitted_to order by lat.level asc";
        $query = $this->db->query($myquery, $ref_id);
        if ($query->num_rows() > 0) {
            return $query->result();
        } else {
            return false;
        }
    }

    function get_auth_details2($ref_id) {


        /* $myquery = "select ud.salutation,ud.first_name,ud.middle_name,ud.last_name,auth.type from leave_auth_table lat
          inner join leave_all_kind_details lakd on lat.ref_id=lakd.ref_id
          inner join user_details ud on lat.submitted_to=ud.id
          inner join user_auth_types uat on lat.submitted_to=uat.id
          inner join auth_types auth on uat.auth_id=auth.id
          where lat.ref_id=? order by lat.level asc"; */
        $myquery = "select ud.salutation,ud.first_name,ud.middle_name,ud.last_name,lat.submitted_TS from leave_auth_table lat
inner join leave_all_kind_details lakd on lat.ref_id=lakd.ref_id
inner join user_details ud on lat.submitted_to=ud.id
where lat.ref_id=? order by lat.level asc";
        $query = $this->db->query($myquery, $ref_id);
        if ($query->num_rows() > 0) {
            return $query->result();
        } else {
            return false;
        }
    }
	public function get_submitted_to_data(){
		 $myquery="
  select y.* from

 (select x.* from (select lat.ref_id, lat.submitted_TS, lakd.empno, ud.salutation, ud.first_name, ud.middle_name, ud.last_name, dpt.name, desg.name as designation,lat.`status`, lat.level, lat.submitted_to
from leave_auth_table lat
inner join leave_all_kind_details lakd on lat.ref_id=lakd.ref_id
inner join user_details ud on lakd.empno=ud.id
inner join departments dpt on ud.dept_id=dpt.id
inner join emp_basic_details ebs on lakd.empno=ebs.emp_no
inner join designations desg on ebs.designation=desg.id
order by  lat.`level` desc limit 1000)x group by x.ref_id)y where y.submitted_to=?
  and ( y.status='pending' or y.status='forwarded') 
 order by y.submitted_TS desc
 ";
 

        $query = $this->db->query($myquery, '1106');
//echo $this->db->last_query();
        if ($query->num_rows() > 0) {
            return $query->result();
        } else {
            return false;
        }
	}

    function get_submitted_to($uid) {

/*        $myquery = "select y.* from (select x.* from (select lat.ref_id, lat.submitted_TS, lakd.empno, ud.salutation, ud.first_name, ud.middle_name, ud.last_name, dpt.name, desg.name as designation,lat.`status`, lat.level, lat.submitted_to
from leave_auth_table lat
inner join leave_all_kind_details lakd on lat.ref_id=lakd.ref_id
inner join user_details ud on lakd.empno=ud.id
inner join departments dpt on ud.dept_id=dpt.id
inner join emp_basic_details ebs on lakd.empno=ebs.emp_no
inner join designations desg on ebs.designation=desg.id
where lat.ref_id not in (select distinct lat.ref_id from leave_auth_table lat where lat.status='approved' or lat.status='rejected') order by  lat.`level` desc limit 1000)x group by x.ref_id)y where y.submitted_to=? order by y.submitted_TS desc";*/

/*$myquery = "select lat.ref_id, lat.submitted_TS, lakd.empno, ud.salutation, ud.first_name, ud.middle_name, ud.last_name, dpt.name, desg.name as designation,lat.`status`, lat.level, lat.submitted_by
from leave_auth_table lat
inner join leave_all_kind_details lakd on lat.ref_id=lakd.ref_id
inner join user_details ud on lakd.empno=ud.id
inner join departments dpt on ud.dept_id=dpt.id
inner join emp_basic_details ebs on lakd.empno=ebs.emp_no
inner join designations desg on ebs.designation=desg.id
 and lat.submitted_to='101' and (lat.status='forwarded' or lat.status='pending')";*/
 
 
 $myquery="
  select y.* from

 (select x.* from (select lat.ref_id, lat.submitted_TS, lakd.empno, ud.salutation, ud.first_name, ud.middle_name, ud.last_name, dpt.name, desg.name as designation,lat.`status`, lat.level, lat.submitted_to
from leave_auth_table lat
inner join leave_all_kind_details lakd on lat.ref_id=lakd.ref_id
inner join user_details ud on lakd.empno=ud.id
inner join departments dpt on ud.dept_id=dpt.id
inner join emp_basic_details ebs on lakd.empno=ebs.emp_no
inner join designations desg on ebs.designation=desg.id
order by  lat.`level` desc limit 1000000000000000)x group by x.ref_id)y where y.submitted_to=?
  and ( y.status='pending' or y.status='forwarded' or y.status='back' ) /*and y.submitted_to<>y.empno*/
 order by y.submitted_TS desc
 ";
 
 /*$myquery="select y.* from(
select x.*,  lakd.empno, ud.salutation, ud.first_name, ud.middle_name, ud.last_name, dpt.name, desg.name as designation from(
select b.* from leave_auth_table b where b.id in
(select max(a.id) from leave_auth_table a group by a.ref_id)) x
INNER JOIN leave_all_kind_details lakd ON x.ref_id=lakd.ref_id
INNER JOIN user_details ud ON lakd.empno=ud.id
INNER JOIN departments dpt ON ud.dept_id=dpt.id
INNER JOIN emp_basic_details ebs ON lakd.empno=ebs.emp_no
INNER JOIN designations desg ON ebs.designation=desg.id) y
where y.submitted_to=? and y.status='pending' or y.status='forwarded'";*/
 

        $query = $this->db->query($myquery, $uid);
//echo $this->db->last_query();
        if ($query->num_rows() > 0) {
            return $query->result();
        } else {
            return false;
        }
    }

    function get_history_of_authority($uid) {

       /* $myquery = "select y.* from (select x.* from (select lat.ref_id, lat.submitted_TS, lakd.empno, ud.salutation, ud.first_name, ud.middle_name, ud.last_name, dpt.name, desg.name as designation,lat.`status`, lat.level, lat.submitted_by
from leave_auth_table lat
inner join leave_all_kind_details lakd on lat.ref_id=lakd.ref_id
inner join user_details ud on lakd.empno=ud.id
inner join departments dpt on ud.dept_id=dpt.id
inner join emp_basic_details ebs on lakd.empno=ebs.emp_no
inner join designations desg on ebs.designation=desg.id
where lat.ref_id in (select distinct lat.ref_id from leave_auth_table lat where lat.status='approved' or lat.status='rejected' or lat.status='forwarded' or lat.status='back') order by  lat.`level` desc limit 1000)x group by x.ref_id)y where y.submitted_by=?";*/


/*$myquery="select x.* from (select lat.ref_id, lat.submitted_TS, lakd.empno, ud.salutation, ud.first_name, ud.middle_name, ud.last_name, dpt.name, desg.name as designation,lat.`status`, lat.level, lat.submitted_by
from leave_auth_table lat
inner join leave_all_kind_details lakd on lat.ref_id=lakd.ref_id
inner join user_details ud on lakd.empno=ud.id
inner join departments dpt on ud.dept_id=dpt.id
inner join emp_basic_details ebs on lakd.empno=ebs.emp_no
inner join designations desg on ebs.designation=desg.id
 and (lat.submitted_to=? or lat.submitted_by=?)
and (lat.status='approved' or lat.status='rejected' or lat.status='forwarded' or lat.status='back') order by  lat.ref_id,lat.`level` desc limit 1000)x group by x.ref_id
order by x.submitted_TS desc";*/

$myquery="select y.* from

  (select lat.ref_id, lat.submitted_TS, lakd.empno, ud.salutation, ud.first_name, ud.middle_name, ud.last_name, dpt.name, desg.name as designation,lat.`status`, lat.level, lat.submitted_by
from leave_auth_table lat
inner join leave_all_kind_details lakd on lat.ref_id=lakd.ref_id
inner join user_details ud on lakd.empno=ud.id
inner join departments dpt on ud.dept_id=dpt.id
inner join emp_basic_details ebs on lakd.empno=ebs.emp_no
inner join designations desg on ebs.designation=desg.id
)y where y.submitted_by=? 
  and ( y.status='approved' or y.status='forwarded' or y.status='rejected'or y.status='back') 
  group by y.ref_id
 order by y.submitted_TS desc";


        $query = $this->db->query($myquery, array($uid,$uid));

        if ($query->num_rows() > 0) {
            return $query->result();
        } else {
            return false;
        }
    }
	
	
	function get_station_leave_history_for_dt($uid) {

       

$myquery="SELECT lakd.ref_id, lakd.empno, ud.salutation, ud.first_name, ud.middle_name, ud.last_name, dpt.name, desg.name AS designation,lakd.nature_of_leave, lakd.leave_period_from_date, lakd.leave_period_to_date FROM leave_all_kind_details lakd INNER JOIN user_details ud ON lakd.empno=ud.id INNER JOIN departments dpt ON ud.dept_id=dpt.id INNER JOIN emp_basic_details ebs ON lakd.empno=ebs.emp_no INNER JOIN designations desg ON ebs.designation=desg.id WHERE lakd.nature_of_leave='SL'";


        $query = $this->db->query($myquery, array($uid,$uid));

        if ($query->num_rows() > 0) {
            return $query->result();
        } else {
            return false;
        }
    }

    function get_application_details($id) {
		
//var_dump($id);die();
        $myquery = "select * from leave_all_kind_details where ref_id=?";

        $query = $this->db->query($myquery, $id);
		//echo $this->db->last_query();die();
        if ($query->num_rows() > 0) {
            return $query->result();
        } else {
            return false;
        }
    }

    function get_applicant_empno($id) {

        $myquery = "select empno from leave_all_kind_details where ref_id=?";

        $query = $this->db->query($myquery, $id);

        if ($query->num_rows() > 0) {
            return $query->result();
        } else {
            return false;
        }
    }

    function get_level_no($ref_id) {
        $myquery = "select level from leave_auth_table where ref_id=? order by level desc limit 1";
        $query = $this->db->query($myquery, $ref_id);
        if ($query->num_rows() > 0) {
            return $query->row()->level;
        } else {
            return false;
        }
    }
	
	function get_estt_auth_id() {
        $myquery = "select id from user_auth_types where auth_id='est_ar'";
        $query = $this->db->query($myquery);
        if ($query->num_rows() > 0) {
            return $query->row()->id;
		
        } else {
            return false;
        }
    }
	
	

    function get_previous_sender($ref_id, $my_id) {
        // $myquery = "select submitted_by from leave_auth_table where ref_id=? order by level desc limit 1";
        $myquery = "select submitted_by from leave_auth_table where ref_id=? and status<>'back' and submitted_to=?";
        $query = $this->db->query($myquery, array($ref_id, $my_id));

        if ($query->num_rows() > 0) {
            return $query->row()->submitted_by;
        } else {
            return false;
        }
    }

    function get_application_position($ref_id) {
        $myquery = "select * from leave_auth_table where ref_id=? order by level desc limit 1";
        $query = $this->db->query($myquery, $ref_id);
        if ($query->num_rows() > 0) {
            return $query->result();
        } else {
            return false;
        }
    }

    function get_approval_level($ref_id) {

        $myquery = "select * from leave_auth_table where ref_id=? order by level desc limit 1";

        $query = $this->db->query($myquery, $ref_id);

        if ($query->num_rows() > 0) {
            return $query->result();
        } else {
            return false;
        }
    }

    function insert_leave_application($data) {

        if ($this->db->insert('leave_all_kind_details', $data))
            return $this->db->insert_id();
        else
            return FALSE;
    }

    function update_leave_application($data) {

        $this->db->where('ref_id', $data['ref_id']);
        if ($this->db->update('leave_all_kind_details', $data))
        //     return $this->db->insert_id();
            return true;
        else
            return FALSE;
    }

    function insert_leave_tour_details($tour) {

        if ($this->db->insert('leave_tour_details', $tour))
            return $this->db->insert_id();
        else
            return FALSE;
    }

    function update_leave_tour_details($tour) {

        $this->db->where('ref_id', $tour['ref_id']);
        if ($this->db->update('leave_tour_details', $tour))
        //    return $this->db->insert_id();
            return true;
        else
            return FALSE;
    }

    function insert_leave_ltc_details($ltc) {

        if ($this->db->insert('leave_ltc_details', $ltc))
            return $this->db->insert_id();
        else
            return FALSE;
    }

    function update_leave_ltc_details($ltc) {

        $this->db->where('ref_id', $ltc['ref_id']);
        if ($this->db->update('leave_ltc_details', $ltc)) {

            //return $this->db->insert_id();
            return true;
        } else {

            return FALSE;
        }
    }
	
	function update_duration_leave_all_kind_details($data){
		$value=array('nature_of_leave'=>$data['nature_of_leave'],'leave_period_from_date'=>$data['leave_period_from_date'],'leave_period_to_date'=>$data['leave_period_to_date'],'leave_count'=>$data['leave_count']);
		$this->db->where('ref_id', $data['ref_id']);
        if ($this->db->update('leave_all_kind_details', $value)) {

            //return $this->db->insert_id();
            return true;
        } else {

            return FALSE;
        }
	}
	
	function insert_leave_all_kind_details_backup($previous) {

        if ($this->db->insert('leave_updated_data', $previous))
            return $this->db->insert_id();
        else
            return FALSE;
    }

    function insert_leave_auth_details($auth) {

        if ($this->db->insert('leave_auth_table', $auth))
            return $this->db->insert_id();
        else
            return FALSE;
    }

    function update_leave_auth_details($auth) {

        $this->db->where('ref_id', $auth['ref_id']);
        if ($this->db->update('leave_auth_table', $auth)) {
            return true;
            // return $this->db->insert_id();
        } else {

            return FALSE;
        }
    }

    function delete_leave_all_kind_details($ref_id) {
        $this->db->where('ref_id', $ref_id);
        $this->db->delete('leave_all_kind_details');
    }

    function delete_leave_tour_details($ref_id) {
        $this->db->where('ref_id', $ref_id);
        $this->db->delete('leave_tour_details');
    }

    function delete_leave_ltc_details($ref_id) {
        $this->db->where('ref_id', $ref_id);
        $this->db->delete('leave_ltc_details');
    }

    function delete_leave_auth_table_details($ref_id) {
        $this->db->where('ref_id', $ref_id);
        $this->db->delete('leave_auth_table');
    }

    function check_tour_status($ref_id) {

        $myquery = "select * from  leave_tour_details where ref_id=?";
        $query = $this->db->query($myquery, $ref_id);
        if ($query->num_rows() > 0) {
            return $query->row();
        } else {
            return FALSE;
        }
    }

    function check_ltc_status($ref_id) {

        $myquery = "select * from  leave_ltc_details where ref_id=?";
        $query = $this->db->query($myquery, $ref_id);
        if ($query->num_rows() > 0) {
            return $query->row();
        } else {
            return FALSE;
        }
    }

	function get_mobile_number($receiver) {
        $myquery = "select mobile_no from user_other_details where id=?";
        $query = $this->db->query($myquery, $receiver);
        if ($query->num_rows() > 0) {
            return $query->row()->mobile_no;
        } else {
            return false;
        }
    }




}
