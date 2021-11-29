<?php

/* Author @Ritu Raj
  Module: Fellowsip Model
  Description:  CRUD required for fellowship processing module */

Class Fellow_academic extends CI_Model {

    private $course = 'courses';

    function __construct() {
        parent::__construct();
    }

    //************* external guide *****************//
    function insert_data($data) {
        $this->db->insert('fellow_external_co_guide', $data);

        return TRUE;
    }

    function getExtCoGuide() {
        $query = $this->db->get('fellow_external_co_guide');
        if ($query->num_rows() > 0)
            return $query->result_array();
        return false;
    }

    function getExtCoGuideById($id) {
        $query = $this->db->get_where('fellow_external_co_guide', ['id' => $id]);
        if ($query->num_rows() > 0)
            return $query->row();
        return false;
    }

    // List  of fellows to be populated to DDA(forwarded)
    function get_fd_fellow_list_to_dda($actor, $target_degree, $mon, $yr, $qualifying_degree_type) {


        if (in_array("dept_da5", $this->session->userdata('auth')) && $actor == "dept_da") {
            $where_clause_var = " and fbm.attd_forwarding_by_dda ";
            $select_clause_var = " DATE_FORMAT(fbm.attd_forwarding_by_dda_time,'%d/%m/%Y %r') ";
            $dept = " and u.dept_id=? ";
            $secure_array = array($this->session->userdata('dept_id'), 'Y', $qualifying_degree_type, $mon, $yr, "Y", $target_degree);
        }

        $sql = "SELECT DATE_FORMAT(st.admn_date,'%d %b %Y')as admn_date, fm.stud_reg_no, fm.guide,  stother.account_no, u.salutation, u.dept_id,
      u.first_name, u.middle_name, u.last_name, concat_ws(' ', x.salutation, x.first_name, x.middle_name, x.last_name) as guide_name,
      fbpm.target_degree_type_yr,fbpm.target_degree_type ,fqd.qualifying_degree,fbpm.fellowship_amt,
      flv.leave_permissible_yrly,COALESCE(fbm.leave_balance,0)as lb ,COALESCE(fbm.days_absent,0)as days_absent,COALESCE(fbm.admissible_leave,0)as admissible_leave,fbm.present_yr_fellowship,
      COALESCE(fbm.attd_forwarding_by_dda,'N')as  attd_forwarding_by_dda, COALESCE(fbm.recovery_amt,0)as recovery_amt,null  as auto_rejected_remark, null as rejected_by,
      " . $select_clause_var . "  as dt
        FROM
    (`fellow_master` fm)
   JOIN `stu_details` st ON `st`.`admn_no` = `fm`.`stud_reg_no`
   JOIN `stu_other_details` stother ON `stother`.`admn_no` = `fm`.`stud_reg_no`
   JOIN `user_details` u ON `u`.`id` = `stother`.`admn_no` " . $dept . "
   JOIN `fellow_dsc_process_control` fdpc ON `fdpc`.`stud_reg_no` = `fm`.`stud_reg_no` and fdpc.fellow_eligibility=?  and fdpc.qualifying_degree_type=?
   JOIN  `fellow_bill_master`  fbm ON `fbm`.`stud_reg_no` = `fm`.`stud_reg_no`    and `fbm`.`target_degree` = `fm`.`target_degree` and fbm.processing_mon=? and  fbm.processing_yr=? " . $where_clause_var . " =?
   JOIN   fellow_amt_payable_master fbpm  ON `fbpm`.`qualifying_degree` = `fdpc`.`qualifying_degree`  and `fbpm`.target_degree_type=?   and  `fbpm`.target_degree_type_yr=`fbm`.`present_yr_fellowship`
   JOIN `fellow_leave_master` flv ON `flv`.`target_degree_type` = `fbpm`.`target_degree_type`
   LEFT JOIN `fellow_qualifying_degree` fqd ON `fqd`.`id`=`fdpc`.`qualifying_degree`
   LEFT JOIN `user_details` x ON `x`.`id`=`fm`.`guide`
   group by fm.`stud_reg_no`  ORDER BY  dt desc
";


        $query = $this->db->query($sql, $secure_array);
        // echo $this->db->last_query();
        if ($query->num_rows() > 0)
            return $query->result_array();
        else
            return FALSE;
    }

    // List  of fellows to be populated to DDA(new active)
    function get_active_fellow_list_to_dda($actor, $target_degree, $mon, $yr, $qualifying_degree_type) {


        if (in_array("dept_da5", $this->session->userdata('auth')) && $actor == "dept_da") {
            $dept = " and u.dept_id=? ";
            $secure_array = array($this->session->userdata('dept_id'), 'Y', $qualifying_degree_type, $mon, $yr, $target_degree, $yr, $mon, $yr, $mon, $yr, $mon, $yr, $mon, $yr, $mon, $yr, $mon, $yr, $mon, $yr, $mon, $yr, $mon, $yr, $mon, $yr, $mon, $yr, $mon, $yr, $mon, $yr, $mon);
        }

        $sql = "
select z.* ,(z.leave_permissible_yrly-sum(COALESCE (fbm_parent.admissible_leave,0)) ) as lb ,null  as auto_rejected_remark, null as rejected_by, '0' as recovery_amt,'0' as admissible_leave, '0' as days_absent ,'N' as attd_forwarding_by_dda,null as dt
from(
select x.* ,fbpm.target_degree_type_yr as present_yr_fellowship,`fbpm`.`fellowship_amt` from(

select A.* from
(
 SELECT DATE_FORMAT(st.admn_date,'%d %b %Y')as admn_date, st.admn_date as adm_dt ,`fm`.`target_degree`,st.admn_date as admdt, fm.stud_reg_no, fm.guide, stother.account_no, u.salutation, u.dept_id, u.first_name, u.middle_name, u.last_name, concat_ws(' ', x.salutation, x.first_name, x.middle_name, x.last_name) as guide_name, `fqd`.`id`,
 fqd.qualifying_degree,flv.leave_permissible_yrly,`flv`.`target_degree_type` FROM (`fellow_master` fm)

 JOIN `stu_details` st ON `st`.`admn_no` = `fm`.`stud_reg_no`
 JOIN `stu_other_details` stother ON `stother`.`admn_no` = `fm`.`stud_reg_no`
 JOIN `user_details` u ON `u`.`id` = `stother`.`admn_no` and u.dept_id=?
 JOIN `fellow_dsc_process_control` fdpc ON `fdpc`.`stud_reg_no` = `fm`.`stud_reg_no` and fdpc.fellow_eligibility=? and fdpc.qualifying_degree_type=?
 LEFT JOIN `fellow_leave_master` flv ON `flv`.`target_degree_type` = `fm`.`target_degree`
 LEFT JOIN `fellow_qualifying_degree` fqd ON `fqd`.`id`=`fdpc`.`qualifying_degree`
 LEFT JOIN `user_details` x ON `x`.`id`=`fm`.`guide`

 )A
 where not exists (select `fbm`.`stud_reg_no` from `fellow_bill_master` fbm where `fbm`.`stud_reg_no` = A.`stud_reg_no` and `fbm`.`target_degree` = A.`target_degree` and fbm.processing_mon=? and fbm.processing_yr=?
)
)x
  left join fellow_amt_payable_master fbpm  ON `fbpm`.`qualifying_degree` = `x`.id  and `fbpm`.target_degree_type=?
     and
        (case
               when (UNIX_TIMESTAMP(x.adm_dt)>= UNIX_TIMESTAMP(DATE_SUB(concat_ws('-',?,?,date_format(DATE_SUB(x.adm_dt,INTERVAL 1 day),'%d')) , INTERVAL 4 year)) && UNIX_TIMESTAMP(x.adm_dt) < UNIX_TIMESTAMP(DATE_SUB(concat_ws('-',?,?,date_format(DATE_SUB(x.adm_dt,INTERVAL 1 day),'%d')) , INTERVAL 3 year))) then  fbpm.target_degree_type_yr = 4
                 when ( UNIX_TIMESTAMP(x.adm_dt)>=UNIX_TIMESTAMP(DATE_SUB(concat_ws('-',?,?,date_format(DATE_SUB(x.adm_dt,INTERVAL 1 day),'%d')) , INTERVAL 3 year)) && UNIX_TIMESTAMP(x.adm_dt)<UNIX_TIMESTAMP(DATE_SUB(concat_ws('-',?,?,date_format(DATE_SUB(x.adm_dt,INTERVAL 1 day),'%d')) , INTERVAL 2 year)) ) then fbpm.target_degree_type_yr =  3
                    when ( UNIX_TIMESTAMP(x.adm_dt)>=UNIX_TIMESTAMP(DATE_SUB(concat_ws('-',?,?,date_format(DATE_SUB(x.adm_dt,INTERVAL 1 day),'%d')) , INTERVAL 2 year)) && UNIX_TIMESTAMP(x.adm_dt)<UNIX_TIMESTAMP(DATE_SUB(concat_ws('-',?,?,date_format(DATE_SUB(x.adm_dt,INTERVAL 1 day),'%d')) , INTERVAL 1 year))) then fbpm.target_degree_type_yr = 2
                       when (UNIX_TIMESTAMP(x.adm_dt)>=UNIX_TIMESTAMP(DATE_SUB(concat_ws('-',?,?,date_format(DATE_SUB(x.adm_dt,INTERVAL 1 day),'%d')) , INTERVAL 1 year) ) ) then fbpm.target_degree_type_yr = 1
        ELSE 1=1
        end)
 )z
  left join `fellow_bill_master` fbm_parent on fbm_parent.`stud_reg_no`=z.`stud_reg_no` and fbm_parent.present_yr_fellowship=z.present_yr_fellowship
  group by z.`stud_reg_no`,z.present_yr_fellowship order by z.adm_dt ";



        $query = $this->db->query($sql, $secure_array);
        // echo $this->db->last_query();
        if ($query->num_rows() > 0) {
            return $query->result_array();
            //print_r($query->result_array()); //die();
        } else
            return FALSE;
    }

    function get_rejected_fellow_list($actor, $target_degree, $mon, $yr, $qualifying_degree_type) {   //rejected
        if (in_array("dept_da5", $this->session->userdata('auth')) && $actor == "dept_da") {
            $where_clause_var = " and (fbm.attd_forwarding_by_dda=? or fbm.attd_forwarding_by_dda=?) ";
            $select_clause_var = " DATE_FORMAT(fbm.attd_forwarding_by_dda_time,'%d/%m/%Y %r') ";
            $dept = " and u.dept_id=? ";
            $secure_array = array($this->session->userdata('dept_id'), 'Y', $qualifying_degree_type, $mon, $yr, "R", "C", $target_degree);
        } else if (in_array("gd", $this->session->userdata('auth')) && $actor == "gd") {
            $where_clause_var = " and ( fbm.attd_approval_by_gd=? or fbm.attd_approval_by_gd=?) ";
            $select_clause_var = "  DATE_FORMAT(fbm.attd_approval_by_gd_time,'%d/%m/%Y %r')  ";
            $dept = " and u.dept_id=? ";
            $secure_array = array($this->session->userdata('dept_id'), 'Y', $qualifying_degree_type, $mon, $yr, "R", "C", $target_degree);
        } else if (in_array("fic_jrf", $this->session->userdata('auth')) && $actor == "fic_jrf") {
            $where_clause_var = " and (fbm.attd_forward_by_fic=? or fbm.attd_forward_by_fic=?) ";
            $select_clause_var = " DATE_FORMAT(fbm.attd_forward_by_fic_time,'%d/%m/%Y %r') ";
            $dept = " and u.dept_id=? ";
            $secure_array = array($this->session->userdata('dept_id'), 'Y', $qualifying_degree_type, $mon, $yr, "R", "C", $target_degree);
        } else if (in_array("hod", $this->session->userdata('auth')) && $actor == "hod") {
            $where_clause_var = " and (fbm.attd_approval_by_hod=?  or fbm.attd_approval_by_hod=?) ";
            $select_clause_var = " DATE_FORMAT(fbm.attd_approval_by_hod_time,'%d/%m/%Y %r') ";
            $dept = " and u.dept_id=? ";
            $secure_array = array($this->session->userdata('dept_id'), 'Y', $qualifying_degree_type, $mon, $yr, "R", "C", $target_degree);
        } else if (in_array("acad_da5", $this->session->userdata('auth')) && $actor == "acad_da") {
            $where_clause_var = " and (fbm.attd_forwarding_by_acad_da =? or fbm.attd_forwarding_by_acad_da=?) ";
            $select_clause_var = " DATE_FORMAT(fbm.attd_forwarding_by_acad_da_time,'%d/%m/%Y %r') ";
            $dept = "";
            $secure_array = array('Y', $qualifying_degree_type, $mon, $yr, "R", "C", $target_degree);
        } else if (in_array("acad_ar", $this->session->userdata('auth')) && $actor == "acad_ar") {
            $where_clause_var = " and (fbm.attd_approval_by_acad_ar =? or fbm.attd_approval_by_acad_ar=?) ";
            $select_clause_var = " DATE_FORMAT(fbm.attd_approval_by_acad_ar_time,'%d/%m/%Y %r') ";
            $dept = "";
            $secure_array = array('Y', $qualifying_degree_type, $mon, $yr, "R", "C", $target_degree);
        } else if (in_array("acc_da5", $this->session->userdata('auth')) && $actor == "acc_da") {
            $where_clause_var = " and (fbm.attd_forwarding_by_acc_da=?  or fbm.attd_forwarding_by_acc_da=?) ";
            $select_clause_var = " DATE_FORMAT(fbm.attd_forwarding_by_acc_da_time,'%d/%m/%Y %r') ";
            $dept = "";
            $secure_array = array('Y', $qualifying_degree_type, $mon, $yr, "R", "C", $target_degree);
        } else if (in_array("acc_ar_ga", $this->session->userdata('auth')) && $actor == "acc_ar_ga") {
            $where_clause_var = " and (fbm.attd_approval_by_acc_ar=? or attd_approval_by_acc_ar=?) ";
            $select_clause_var = " DATE_FORMAT(fbm.attd_approval_by_acc_ar_time,'%d/%m/%Y %r') ";
            $dept = "";
            $secure_array = array('Y', $qualifying_degree_type, $mon, $yr, "R", "C", $target_degree);
        } else if (in_array("rg", $this->session->userdata('auth')) && $actor == "rg") {
            $where_clause_var = " and ( fbm.bill_approval_by_rg =? or fbm.bill_approval_by_rg =?) ";
            $select_clause_var = " DATE_FORMAT(fbm.bill_approval_by_rg_time,'%d/%m/%Y %r') ";
            $dept = "";
            $secure_array = array('Y', $qualifying_degree_type, $mon, $yr, "R", "C", $target_degree);
        }
        $sql = "SELECT DATE_FORMAT(st.admn_date,'%d %b %Y')as admn_date, fm.stud_reg_no, fm.guide,  stother.account_no, u.salutation, u.dept_id,
      u.first_name, u.middle_name, u.last_name, concat_ws(' ', x.salutation, x.first_name, x.middle_name, x.last_name) as guide_name,
     fbpm.target_degree_type ,fqd.qualifying_degree,fbpm.fellowship_amt,
      flv.leave_permissible_yrly,COALESCE(fbm.leave_balance,0)as lb ,COALESCE(fbm.days_absent,0)as days_absent,COALESCE(fbm.admissible_leave,0)as admissible_leave,fbm.present_yr_fellowship  ,fbm.recovery_amt,fbm.net_amt_payable ,
      COALESCE(fbm.attd_forwarding_by_dda,'N')as  attd_forwarding_by_dda,COALESCE(fbm.recovery_amt,0)as recovery_amt,
      COALESCE(fbm.attd_approval_by_gd,'N') as attd_approval_by_gd,
      COALESCE(fbm.attd_forward_by_fic,'N') as attd_forward_by_fic,
      COALESCE(fbm.attd_approval_by_hod,'N') as attd_approval_by_hod,
      COALESCE(fbm.attd_forwarding_by_acad_da,'N') as attd_forwarding_by_acad_da,
      COALESCE(fbm.attd_approval_by_acad_ar,'N') as attd_approval_by_acad_ar,
      COALESCE(fbm.attd_forwarding_by_acc_da,'N') as attd_forwarding_by_acc_da,
      COALESCE(fbm.attd_approval_by_acc_ar,'N') as attd_approval_by_acc_ar,

      " . $select_clause_var . "  as dt,
      (case when (fbm.attd_forward_by_fic='R') then attd_forward_by_fic_remark
             when (fbm.attd_approval_by_hod='R') then attd_approval_by_hod_remark
             when (fbm.attd_forwarding_by_acad_da='R') then attd_forwarding_by_acad_da_remark
             when (fbm.attd_approval_by_acad_ar='R') then attd_approval_by_acad_ar_remark
             when (fbm.attd_forwarding_by_acc_da='R') then attd_forwarding_by_acc_da_remark
             when (fbm.attd_approval_by_acc_ar='R') then attd_approval_by_acc_ar_remark
                  else  null
        end) as  auto_rejected_remark,

       (case when (fbm.attd_forward_by_fic='R') then 'FIC'
              when (fbm.attd_approval_by_hod='R') then 'HOD'
               when (fbm.attd_forwarding_by_acad_da='R') then 'ACAD-DEALING-ASSTT'
               when (fbm.attd_approval_by_acad_ar='R') then 'ACAD-ASSTT-REGISTRAR'
               when (fbm.attd_forwarding_by_acc_da='R') then 'ACC-DEALING-ASSTT'
               when (fbm.attd_approval_by_acc_ar='R') then 'ACC-ASSTT-REGISTRAR'
                  else  null
        end) as  rejected_by,
       (case when (fbm.attd_forward_by_fic='R')  then DATE_FORMAT(fbm.attd_forward_by_fic_time,'%d/%m/%Y %r')
             when (fbm.attd_approval_by_hod='R') then DATE_FORMAT(fbm.attd_approval_by_hod_time,'%d/%m/%Y %r')
              when (fbm.attd_forwarding_by_acad_da='R') then DATE_FORMAT(fbm.attd_forwarding_by_acad_da_time,'%d/%m/%Y %r')
               when (fbm.attd_approval_by_acad_ar='R') then DATE_FORMAT(fbm.attd_approval_by_acad_ar_time,'%d/%m/%Y %r')
               when (fbm.attd_forwarding_by_acc_da='R') then DATE_FORMAT(fbm.attd_forwarding_by_acc_da_time,'%d/%m/%Y %r')
               when (fbm.attd_approval_by_acc_ar='R') then DATE_FORMAT(fbm.attd_approval_by_acc_ar_time,'%d/%m/%Y %r')
                  else null
        end) as  auto_rejected_time
 FROM
 (`fellow_master` fm)
   JOIN `stu_details` st ON `st`.`admn_no` = `fm`.`stud_reg_no`
   JOIN `stu_other_details` stother ON `stother`.`admn_no` = `fm`.`stud_reg_no`
   JOIN `user_details` u ON `u`.`id` = `stother`.`admn_no` " . $dept . "
   JOIN `fellow_dsc_process_control` fdpc ON `fdpc`.`stud_reg_no` = `fm`.`stud_reg_no` and fellow_eligibility=?  and fdpc.qualifying_degree_type=?
   JOIN  `fellow_bill_master`  fbm ON `fbm`.`stud_reg_no` = `fm`.`stud_reg_no`    and  `fbm`.`target_degree` = `fm`.`target_degree` and fbm.processing_mon=? and  fbm.processing_yr=? " . $where_clause_var . "
   JOIN   fellow_amt_payable_master fbpm  ON `fbpm`.`qualifying_degree` = `fdpc`.`qualifying_degree`  and `fbpm`.target_degree_type=?    and  `fbpm`.target_degree_type_yr=`fbm`.`present_yr_fellowship`
   JOIN `fellow_leave_master` flv ON `flv`.`target_degree_type` = `fbpm`.`target_degree_type`

   LEFT JOIN `fellow_qualifying_degree` fqd ON `fqd`.`id`=`fdpc`.`qualifying_degree`
   LEFT JOIN `user_details` x ON `x`.`id`=`fm`.`guide`
   group by fm.`stud_reg_no`  ORDER BY  dt desc
";


        $query = $this->db->query($sql, $secure_array);
        //   echo $this->db->last_query();
        if ($query->num_rows() > 0)
            return $query->result_array();
        else
            return FALSE;
    }

    function get_suspended_fellow_list($actor, $target_degree, $mon, $yr, $qualifying_degree_type) {   //suspended
        $where_clause_var = " and  fbm.attd_approval_by_gd=? ";
        if (in_array("gd", $this->session->userdata('auth')) && $actor == "gd") {
            $select_clause_var = "  DATE_FORMAT(fbm.attd_approval_by_gd_time,'%d/%m/%Y %r')  ";
            $dept = " and u.dept_id=? ";
            $secure_array = array($this->session->userdata('dept_id'), 'Y', $qualifying_degree_type, $mon, $yr, "S", $target_degree);
        } else if (in_array("fic_jrf", $this->session->userdata('auth')) && $actor == "fic_jrf") {

            $select_clause_var = " DATE_FORMAT(fbm.attd_forward_by_fic_time,'%d/%m/%Y %r') ";
            $dept = " and u.dept_id=? ";
            $secure_array = array($this->session->userdata('dept_id'), 'Y', $qualifying_degree_type, $mon, $yr, "S", $target_degree);
        } else if (in_array("hod", $this->session->userdata('auth')) && $actor == "hod") {

            $select_clause_var = " DATE_FORMAT(fbm.attd_approval_by_hod_time,'%d/%m/%Y %r') ";
            $dept = " and u.dept_id=? ";
            $secure_array = array($this->session->userdata('dept_id'), 'Y', $qualifying_degree_type, $mon, $yr, "S", $target_degree);
        } else if (in_array("acad_da5", $this->session->userdata('auth')) && $actor == "acad_da") {

            $select_clause_var = " DATE_FORMAT(fbm.attd_forwarding_by_acad_da_time,'%d/%m/%Y %r') ";
            $dept = "";
            $secure_array = array('Y', $qualifying_degree_type, $mon, $yr, "S", $target_degree);
        } else if (in_array("acad_ar", $this->session->userdata('auth')) && $actor == "acad_ar") {

            $select_clause_var = " DATE_FORMAT(fbm.attd_approval_by_acad_ar_time,'%d/%m/%Y %r') ";
            $dept = "";
            $secure_array = array('Y', $qualifying_degree_type, $mon, $yr, "S", $target_degree);
        } else if (in_array("acc_da5", $this->session->userdata('auth')) && $actor == "acc_da") {

            $select_clause_var = " DATE_FORMAT(fbm.attd_forwarding_by_acc_da_time,'%d/%m/%Y %r') ";
            $dept = "";
            $secure_array = array('Y', $qualifying_degree_type, $mon, $yr, "S", $target_degree);
        } else if (in_array("acc_ar_ga", $this->session->userdata('auth')) && $actor == "acc_ar_ga") {

            $select_clause_var = " DATE_FORMAT(fbm.attd_approval_by_acc_ar_time,'%d/%m/%Y %r') ";
            $dept = "";
            $secure_array = array('Y', $qualifying_degree_type, $mon, $yr, "S", $target_degree);
        } else if (in_array("rg", $this->session->userdata('auth')) && $actor == "rg") {

            $select_clause_var = " DATE_FORMAT(fbm.bill_approval_by_rg_time,'%d/%m/%Y %r') ";
            $dept = "";
            $secure_array = array('Y', $qualifying_degree_type, $mon, $yr, "S", $target_degree);
        }
        $sql = "SELECT DATE_FORMAT(st.admn_date,'%d %b %Y')as admn_date, fm.stud_reg_no, fm.guide,  stother.account_no, u.salutation, u.dept_id,
      u.first_name, u.middle_name, u.last_name, concat_ws(' ', x.salutation, x.first_name, x.middle_name, x.last_name) as guide_name,
     fbpm.target_degree_type ,fqd.qualifying_degree,fbpm.fellowship_amt,
      flv.leave_permissible_yrly,COALESCE(fbm.leave_balance,0)as leave_balance ,COALESCE(fbm.days_absent,0)as days_absent,COALESCE(fbm.admissible_leave,0)as admissible_leave,fbm.present_yr_fellowship  as target_degree_type_yr,fbm.recovery_amt,fbm.net_amt_payable ,

       COALESCE(fbm.attd_forwarding_by_dda,'N')as  attd_forwarding_by_dda,
      COALESCE(fbm.attd_approval_by_gd,'N') as attd_approval_by_gd,
      COALESCE(fbm.attd_forward_by_fic,'N') as attd_forward_by_fic,
      COALESCE(fbm.attd_approval_by_hod,'N') as attd_approval_by_hod,
      COALESCE(fbm.attd_forwarding_by_acad_da,'N') as attd_forwarding_by_acad_da,
      COALESCE(fbm.attd_approval_by_acad_ar,'N') as attd_approval_by_acad_ar,
      COALESCE(fbm.attd_forwarding_by_acc_da,'N') as attd_forwarding_by_acc_da,
      COALESCE(fbm.attd_approval_by_acc_ar,'N') as attd_approval_by_acc_ar,

      " . $select_clause_var . "  as dt,
        fbm.attd_approval_by_gd_remark as  auto_suspended_remark,
        'GUIDE' as  suspended_by,
       DATE_FORMAT(fbm.attd_approval_by_gd_time,'%d/%m/%Y %r') as  suspended_time
 FROM
 (`fellow_master` fm)
   JOIN `stu_details` st ON `st`.`admn_no` = `fm`.`stud_reg_no`
   JOIN `stu_other_details` stother ON `stother`.`admn_no` = `fm`.`stud_reg_no`
   JOIN `user_details` u ON `u`.`id` = `stother`.`admn_no` " . $dept . "
   JOIN `fellow_dsc_process_control` fdpc ON `fdpc`.`stud_reg_no` = `fm`.`stud_reg_no` and fellow_eligibility=?  and fdpc.qualifying_degree_type=?
   JOIN  `fellow_bill_master`  fbm ON `fbm`.`stud_reg_no` = `fm`.`stud_reg_no`    and  `fbm`.`target_degree` = `fm`.`target_degree` and fbm.processing_mon=? and  fbm.processing_yr=? " . $where_clause_var . "
   JOIN   fellow_amt_payable_master fbpm  ON `fbpm`.`qualifying_degree` = `fdpc`.`qualifying_degree`  and `fbpm`.target_degree_type=?    and  `fbpm`.target_degree_type_yr=`fbm`.`present_yr_fellowship`
   JOIN `fellow_leave_master` flv ON `flv`.`target_degree_type` = `fbpm`.`target_degree_type`

   LEFT JOIN `fellow_qualifying_degree` fqd ON `fqd`.`id`=`fdpc`.`qualifying_degree`
   LEFT JOIN `user_details` x ON `x`.`id`=`fm`.`guide`
   group by fm.`stud_reg_no`  ORDER BY suspended_time
";


        $query = $this->db->query($sql, $secure_array);
        //echo $this->db->last_query();
        if ($query->num_rows() > 0)
            return $query->result_array();
        else
            return FALSE;
    }

    //******************@Module :Fellow List(With all req.column) Population ************************//
    /**
     * @desc: Get fellow List(name,reg.No.) those are enrolled in junior research fellow(short code=>jrf) and to be shown to respective actors
     *
     * @access	public
     * @param	String	      (junior research fellow(short code=>jrf))
     * @param	String	      (respective department)
     * @return	string array  (return Fellow List)
     */
    function get_fellow_details_by_param($actor, $target_degree, $mon, $yr, $qualifying_degree_type) {   // All case actibe & blocked
        if (in_array("dept_da5", $this->session->userdata('auth')) && $actor == "dept_da") {
            $select_clause_var = " DATE_FORMAT(fbm.attd_forwarding_by_dda_time,'%d/%m/%Y %r') ";
            $dept = " and u.dept_id=? ";
            $secure_array = array($this->session->userdata('dept_id'), 'Y', $qualifying_degree_type, $target_degree, $yr, $mon, $yr, $mon, $yr, $mon, $yr, $mon, $yr, $mon, $yr, $mon, $yr, $mon, $mon, $yr);
        } else if (in_array("gd", $this->session->userdata('auth')) && $actor == "gd") {
            $select_clause_var = "  DATE_FORMAT(fbm.attd_approval_by_gd_time,'%d/%m/%Y %r')  ";
            $dept = " and u.dept_id=? ";
            $secure_array = array($this->session->userdata('dept_id'), 'Y', $qualifying_degree_type, $target_degree, $yr, $mon, $yr, $mon, $yr, $mon, $yr, $mon, $yr, $mon, $yr, $mon, $yr, $mon, $mon, $yr);
        } else if (in_array("fic_jrf", $this->session->userdata('auth')) && $actor == "fic_jrf") {
            $select_clause_var = " DATE_FORMAT(fbm.attd_forward_by_fic_time,'%d/%m/%Y %r') ";
            $dept = " and u.dept_id=? ";
            $secure_array = array($this->session->userdata('dept_id'), 'Y', $qualifying_degree_type, $target_degree, $yr, $mon, $yr, $mon, $yr, $mon, $yr, $mon, $yr, $mon, $yr, $mon, $yr, $mon, $mon, $yr);
        } else if (in_array("hod", $this->session->userdata('auth')) && $actor == "hod") {
            $select_clause_var = " DATE_FORMAT(fbm.attd_approval_by_hod_time,'%d/%m/%Y %r') ";
            $dept = " and u.dept_id=? ";
            $secure_array = array($this->session->userdata('dept_id'), 'Y', $qualifying_degree_type, $target_degree, $yr, $mon, $yr, $mon, $yr, $mon, $yr, $mon, $yr, $mon, $yr, $mon, $yr, $mon, $mon, $yr);
        } else if (in_array("acad_da5", $this->session->userdata('auth')) && $actor == "acad_da") {
            $select_clause_var = " DATE_FORMAT(fbm.attd_forwarding_by_acad_da_time,'%d/%m/%Y %r') ";
            $dept = "";
            $secure_array = array('Y', $qualifying_degree_type, $target_degree, $yr, $mon, $yr, $mon, $yr, $mon, $yr, $mon, $yr, $mon, $yr, $mon, $yr, $mon, $mon, $yr);
        } else if (in_array("acad_ar", $this->session->userdata('auth')) && $actor == "acad_ar") {
            $select_clause_var = " DATE_FORMAT(fbm.attd_approval_by_acad_ar_time,'%d/%m/%Y %r') ";
            $dept = "";
            $secure_array = array('Y', $qualifying_degree_type, $target_degree, $yr, $mon, $yr, $mon, $yr, $mon, $yr, $mon, $yr, $mon, $yr, $mon, $yr, $mon, $mon, $yr);
        } else if (in_array("acc_da5", $this->session->userdata('auth')) && $actor == "acc_da") {
            $select_clause_var = " DATE_FORMAT(fbm.attd_forwarding_by_acc_da_time,'%d/%m/%Y %r') ";
            $dept = "";
            $secure_array = array('Y', $qualifying_degree_type, $target_degree, $yr, $mon, $yr, $mon, $yr, $mon, $yr, $mon, $yr, $mon, $yr, $mon, $yr, $mon, $mon, $yr);
        } else if (in_array("acc_ar_ga", $this->session->userdata('auth')) && $actor == "acc_ar_ga") {
            $select_clause_var = " DATE_FORMAT(fbm.attd_approval_by_acc_ar_time,'%d/%m/%Y %r') ";
            $dept = "";
            $secure_array = array('Y', $qualifying_degree_type, $target_degree, $yr, $mon, $yr, $mon, $yr, $mon, $yr, $mon, $yr, $mon, $yr, $mon, $yr, $mon, $mon, $yr);
        } else if (in_array("rg", $this->session->userdata('auth')) && $actor == "rg") {
            $select_clause_var = " DATE_FORMAT(fbm.bill_approval_by_rg_time,'%d/%m/%Y %r') ";
            $dept = "";
            $secure_array = array('Y', $qualifying_degree_type, $target_degree, $yr, $mon, $yr, $mon, $yr, $mon, $yr, $mon, $yr, $mon, $yr, $mon, $yr, $mon, $mon, $yr);
        }

        $sql = "SELECT DATE_FORMAT(st.admn_date,'%d %b %Y')as admn_date, fm.stud_reg_no, fm.guide,  stother.account_no, u.salutation, u.dept_id,
      u.first_name, u.middle_name, u.last_name, concat_ws(' ', x.salutation, x.first_name, x.middle_name, x.last_name) as guide_name,
      fbpm.target_degree_type_yr as present_yr_fellowship ,fbpm.target_degree_type ,fqd.qualifying_degree,fbpm.fellowship_amt,fbm.recovery_amt,
      flv.leave_permissible_yrly,COALESCE(fbm.leave_balance,0)as lb ,COALESCE(fbm.days_absent,0)as days_absent,COALESCE(fbm.admissible_leave,0)as admissible_leave,
      COALESCE(fbm.attd_forwarding_by_dda,'N')as  attd_forwarding_by_dda,
      COALESCE(fbm.attd_approval_by_gd,'N')as attd_approval_by_gd ,
      COALESCE(fbm.attd_forward_by_fic,'N') as attd_forward_by_fic,
      COALESCE(fbm.attd_approval_by_hod,'N') as attd_approval_by_hod,
      COALESCE(fbm.attd_forwarding_by_acad_da,'N') as attd_forwarding_by_acad_da,
      COALESCE(fbm.attd_approval_by_acad_ar,'N') as attd_approval_by_acad_ar,
      COALESCE(fbm.attd_forwarding_by_acc_da,'N') as attd_forwarding_by_acc_da,
      COALESCE(fbm.attd_approval_by_acc_ar,'N') as attd_approval_by_acc_ar,

      " . $select_clause_var . "  as dt,
      (case when (fbm.attd_forward_by_fic='R') then attd_forward_by_fic_remark
             when (fbm.attd_approval_by_hod='R') then attd_approval_by_hod_remark
             when (fbm.attd_forwarding_by_acad_da='R') then attd_forwarding_by_acad_da_remark
             when (fbm.attd_approval_by_acad_ar='R') then attd_approval_by_acad_ar_remark
             when (fbm.attd_forwarding_by_acc_da='R') then attd_forwarding_by_acc_da_remark
             when (fbm.attd_approval_by_acc_ar='R') then attd_approval_by_acc_ar_remark
                  else  null
        end) as  auto_rejected_remark,

       (case when (fbm.attd_forward_by_fic='R') then 'FIC'
              when (fbm.attd_approval_by_hod='R') then 'HOD'
               when (fbm.attd_forwarding_by_acad_da='R') then 'ACAD-DEALING-ASSTT'
               when (fbm.attd_approval_by_acad_ar='R') then 'ACAD-ASSTT-REGISTRAR'
               when (fbm.attd_forwarding_by_acc_da='R') then 'ACC-DEALING-ASSTT'
               when (fbm.attd_approval_by_acc_ar='R') then 'ACC-ASSTT-REGISTRAR'
                  else  null
        end) as  rejected_by,
       (case when (fbm.attd_forward_by_fic='R')  then DATE_FORMAT(fbm.attd_forward_by_fic_time,'%d/%m/%Y %r')
             when (fbm.attd_approval_by_hod='R') then DATE_FORMAT(fbm.attd_approval_by_hod_time,'%d/%m/%Y %r')
              when (fbm.attd_forwarding_by_acad_da='R') then DATE_FORMAT(fbm.attd_forwarding_by_acad_da_time,'%d/%m/%Y %r')
               when (fbm.attd_approval_by_acad_ar='R') then DATE_FORMAT(fbm.attd_approval_by_acad_ar_time,'%d/%m/%Y %r')
               when (fbm.attd_forwarding_by_acc_da='R') then DATE_FORMAT(fbm.attd_forwarding_by_acc_da_time,'%d/%m/%Y %r')
               when (fbm.attd_approval_by_acc_ar='R') then DATE_FORMAT(fbm.attd_approval_by_acc_ar_time,'%d/%m/%Y %r')
                  else null
        end) as  auto_rejected_time,
        (case when (fbm.attd_approval_by_gd='S')  then DATE_FORMAT(fbm.attd_approval_by_gd_time,'%d/%m/%Y %r')
             else null
         end) as  suspended_time
 FROM
 (`fellow_master` fm)
   JOIN `stu_details` st ON `st`.`admn_no` = `fm`.`stud_reg_no`
   JOIN `stu_other_details` stother ON `stother`.`admn_no` = `fm`.`stud_reg_no`
   JOIN `user_details` u ON `u`.`id` = `stother`.`admn_no` " . $dept . "
   JOIN `fellow_dsc_process_control` fdpc ON `fdpc`.`stud_reg_no` = `fm`.`stud_reg_no` and fellow_eligibility=?  and fdpc.qualifying_degree_type=?
   JOIN   fellow_amt_payable_master fbpm  ON `fbpm`.`qualifying_degree` = `fdpc`.`qualifying_degree`  and `fbpm`.target_degree_type=?
     and
        (case
               when (UNIX_TIMESTAMP(st.admn_date)>= UNIX_TIMESTAMP(DATE_SUB(concat_ws('-',?,?,date_format(DATE_SUB(st.admn_date,INTERVAL 1 day),'%d')) , INTERVAL 4 year)) && UNIX_TIMESTAMP(st.admn_date) < UNIX_TIMESTAMP(DATE_SUB(concat_ws('-',?,?,date_format(DATE_SUB(st.admn_date,INTERVAL 1 day),'%d')) , INTERVAL 3 year))) then  fbpm.target_degree_type_yr = 4
                 when ( UNIX_TIMESTAMP(st.admn_date)>=UNIX_TIMESTAMP(DATE_SUB(concat_ws('-',?,?,date_format(DATE_SUB(st.admn_date,INTERVAL 1 day),'%d')) , INTERVAL 3 year)) && UNIX_TIMESTAMP(st.admn_date)<UNIX_TIMESTAMP(DATE_SUB(concat_ws('-',?,?,date_format(DATE_SUB(st.admn_date,INTERVAL 1 day),'%d')) , INTERVAL 2 year)) ) then fbpm.target_degree_type_yr =  3
                    when ( UNIX_TIMESTAMP(st.admn_date)>=UNIX_TIMESTAMP(DATE_SUB(concat_ws('-',?,?,date_format(DATE_SUB(st.admn_date,INTERVAL 1 day),'%d')) , INTERVAL 2 year)) && UNIX_TIMESTAMP(st.admn_date)<UNIX_TIMESTAMP(DATE_SUB(concat_ws('-',?,?,date_format(DATE_SUB(st.admn_date,INTERVAL 1 day),'%d')) , INTERVAL 1 year))) then fbpm.target_degree_type_yr = 2
                       when (UNIX_TIMESTAMP(st.admn_date)>=UNIX_TIMESTAMP(DATE_SUB(concat_ws('-',?,?,date_format(DATE_SUB(st.admn_date,INTERVAL 1 day),'%d')) , INTERVAL 1 year) ) ) then fbpm.target_degree_type_yr = 1
                           ELSE 1=1
      end)
   JOIN `fellow_leave_master` flv ON `flv`.`target_degree_type` = `fbpm`.`target_degree_type`
   LEFT JOIN `fellow_qualifying_degree` fqd ON `fqd`.`id`=`fdpc`.`qualifying_degree`
   LEFT JOIN `user_details` x ON `x`.`id`=`fm`.`guide`
   LEFT JOIN `fellow_bill_master`  fbm ON `fbm`.`stud_reg_no` = `fm`.`stud_reg_no`    and `fbm`.`target_degree` = `fbpm`.`target_degree_type`  and `fbm`.processing_mon=? and  `fbm`.processing_yr=?

   group by fm.`stud_reg_no`
   ORDER BY admn_date
";


        $query = $this->db->query($sql, $secure_array);
        //     echo $this->db->last_query();
        if ($query->num_rows() > 0)
            return $query->result_array();
        else
            return FALSE;
    }

    // blocked due to  Not Action (N)
    function get_Blocked_fellow_details_by_param($actor, $target_degree, $mon, $yr, $qualifying_degree_type) {

        if (in_array("gd", $this->session->userdata('auth')) && $actor == "gd") {
            $where_clause_var = " and fbm.attd_forwarding_by_dda ";
            $select_clause_var = "  DATE_FORMAT(fbm.attd_approval_by_gd_time,'%d/%m/%Y %r')  ";
            $dept = " and u.dept_id=? ";
            $secure_array = array($this->session->userdata('dept_id'), 'Y', $qualifying_degree_type, $mon, $yr, "N", $target_degree);
        } else if (in_array("fic_jrf", $this->session->userdata('auth')) && $actor == "fic_jrf") {
            $where_clause_var = " and fbm.attd_approval_by_gd ";
            $select_clause_var = " DATE_FORMAT(fbm.attd_forward_by_fic_time,'%d/%m/%Y %r') ";
            $dept = " and u.dept_id=? ";
            $secure_array = array($this->session->userdata('dept_id'), 'Y', $qualifying_degree_type, $mon, $yr, "N", $target_degree);
        } else if (in_array("hod", $this->session->userdata('auth')) && $actor == "hod") {
            $where_clause_var = "and fbm.attd_forward_by_fic ";
            $select_clause_var = " DATE_FORMAT(fbm.attd_approval_by_hod_time,'%d/%m/%Y %r') ";
            $dept = " and u.dept_id=? ";
            $secure_array = array($this->session->userdata('dept_id'), 'Y', $qualifying_degree_type, $mon, $yr, "N", $target_degree);
        } else if (in_array("acad_da5", $this->session->userdata('auth')) && $actor == "acad_da") {
            $where_clause_var = " and fbm.attd_approval_by_hod";
            $select_clause_var = " DATE_FORMAT(fbm.attd_forwarding_by_acad_da_time,'%d/%m/%Y %r') ";
            $dept = "";
            $secure_array = array('Y', $qualifying_degree_type, $mon, $yr, "Y", $target_degree);
        } else if (in_array("acad_ar", $this->session->userdata('auth')) && $actor == "acad_ar") {
            $where_clause_var = " and fbm.attd_forwarding_by_acad_da";
            $select_clause_var = " DATE_FORMAT(fbm.attd_approval_by_acad_ar_time,'%d/%m/%Y %r') ";
            $dept = "";
            $secure_array = array('Y', $qualifying_degree_type, $mon, $yr, "N", $target_degree);
        } else if (in_array("acc_da5", $this->session->userdata('auth')) && $actor == "acc_da") {
            $where_clause_var = " and fbm.attd_approval_by_acad_ar";
            $select_clause_var = " DATE_FORMAT(fbm.attd_forwarding_by_acc_da_time,'%d/%m/%Y %r') ";
            $dept = "";
            $secure_array = array('Y', $qualifying_degree_type, $mon, $yr, "N", $target_degree);
        } else if (in_array("acc_ar_ga", $this->session->userdata('auth')) && $actor == "acc_ar_ga") {
            $where_clause_var = " and fbm.attd_forwarding_by_acc_da";
            $select_clause_var = " DATE_FORMAT(fbm.attd_approval_by_acc_ar_time,'%d/%m/%Y %r') ";
            $dept = "";
            $secure_array = array('Y', $qualifying_degree_type, $mon, $yr, "N", $target_degree);
        } else if (in_array("rg", $this->session->userdata('auth')) && $actor == "rg") {
            $select_clause_var = " DATE_FORMAT(fbm.attd_approval_by_rg_time,'%d/%m/%Y %r') ";
            $where_clause_var = " and fbm.attd_approval_by_acc_ar ";
            $dept = "";
            $secure_array = array('Y', $qualifying_degree_type, $mon, $yr, "N", $target_degree);
        }

        $sql = "SELECT DATE_FORMAT(st.admn_date,'%d %b %Y')as admn_date, fm.stud_reg_no, fm.guide, fm.co_guide, stother.account_no, u.salutation, u.dept_id,
      u.first_name, u.middle_name, u.last_name, concat_ws(' ', x.salutation, x.first_name, x.middle_name, x.last_name) as guide_name,
      flv.leave_permissible_yrly,fbpm.target_degree_type ,fqd.qualifying_degree,fbpm.fellowship_amt,
      COALESCE(fbm.leave_balance,0)as leave_balance ,COALESCE(fbm.days_absent,0)as tot_absent,fbm.present_yr_fellowship  as target_degree_type_yr,fbm.recovery_amt,fbm.net_amt_payable ,
      COALESCE(fbm.attd_forwarding_by_dda,'N')as  attd_forwarding_by_dda,
      COALESCE(fbm.attd_approval_by_gd,'N')as attd_approval_by_gd ,
      COALESCE(fbm.attd_forward_by_fic,'N') as attd_forward_by_fic,
      COALESCE(fbm.attd_approval_by_hod,'N') as attd_approval_by_hod,
      COALESCE(fbm.attd_forwarding_by_acad_da,'N') as attd_forwarding_by_acad_da,
      COALESCE(fbm.attd_approval_by_acad_ar,'N') as attd_approval_by_acad_ar,
      COALESCE(fbm.attd_forwarding_by_acc_da,'N') as attd_forwarding_by_acc_da,
      COALESCE(fbm.attd_approval_by_acc_ar,'N') as attd_approval_by_acc_ar,

      " . $select_clause_var . "  as dt,
      (case when (fbm.attd_forward_by_fic='R') then attd_forward_by_fic_remark
             when (fbm.attd_approval_by_hod='R') then attd_approval_by_hod_remark
             when (fbm.attd_forwarding_by_acad_da='R') then attd_forwarding_by_acad_da_remark
             when (fbm.attd_approval_by_acad_ar='R') then attd_approval_by_acad_ar_remark
             when (fbm.attd_forwarding_by_acc_da='R') then attd_forwarding_by_acc_da_remark
             when (fbm.attd_approval_by_acc_ar='R') then attd_approval_by_acc_ar_remark
                  else  null
        end) as  auto_rejected_remark,

       (case when (fbm.attd_forward_by_fic='R') then 'FIC'
              when (fbm.attd_approval_by_hod='R') then 'HOD'
               when (fbm.attd_forwarding_by_acad_da='R') then 'ACAD-DEALING-ASSTT'
               when (fbm.attd_approval_by_acad_ar='R') then 'ACAD-ASSTT-REGISTRAR'
               when (fbm.attd_forwarding_by_acc_da='R') then 'ACC-DEALING-ASSTT'
               when (fbm.attd_approval_by_acc_ar='R') then 'ACC-ASSTT-REGISTRAR'
                  else  null
        end) as  rejected_by,
       (case when (fbm.attd_forward_by_fic='R')  then DATE_FORMAT(fbm.attd_forward_by_fic_time,'%d/%m/%Y %r')
             when (fbm.attd_approval_by_hod='R') then DATE_FORMAT(fbm.attd_approval_by_hod_time,'%d/%m/%Y %r')
              when (fbm.attd_forwarding_by_acad_da='R') then DATE_FORMAT(fbm.attd_forwarding_by_acad_da_time,'%d/%m/%Y %r')
               when (fbm.attd_approval_by_acad_ar='R') then DATE_FORMAT(fbm.attd_approval_by_acad_ar_time,'%d/%m/%Y %r')
               when (fbm.attd_forwarding_by_acc_da='R') then DATE_FORMAT(fbm.attd_forwarding_by_acc_da_time,'%d/%m/%Y %r')
               when (fbm.attd_approval_by_acc_ar='R') then DATE_FORMAT(fbm.attd_approval_by_acc_ar_time,'%d/%m/%Y %r')
                  else null
        end) as  auto_rejected_time,
        (case when (fbm.attd_approval_by_gd='S')  then DATE_FORMAT(fbm.attd_approval_by_gd_time,'%d/%m/%Y %r')
             else null
         end) as  suspended_time
 FROM
 (`fellow_master` fm)
   JOIN `stu_details` st ON `st`.`admn_no` = `fm`.`stud_reg_no`
   JOIN `stu_other_details` stother ON `stother`.`admn_no` = `fm`.`stud_reg_no`
   JOIN `user_details` u ON `u`.`id` = `stother`.`admn_no` " . $dept . "
   JOIN `fellow_dsc_process_control` fdpc ON `fdpc`.`stud_reg_no` = `fm`.`stud_reg_no` and fellow_eligibility=?  and fdpc.qualifying_degree_type=?
   JOIN `fellow_bill_master` fbm ON `fbm`.`stud_reg_no` = `fm`.`stud_reg_no` and `fbm`.`target_degree` = `fm`.`target_degree` and `fbm`.processing_mon=? and `fbm`.processing_yr=?  " . $where_clause_var . " = ?
   JOIN   fellow_amt_payable_master fbpm  ON `fbpm`.`qualifying_degree` = `fdpc`.`qualifying_degree`  and `fbpm`.target_degree_type=? and  `fbpm`.target_degree_type_yr=`fbm`.`present_yr_fellowship`
   JOIN `fellow_leave_master` flv ON `flv`.`target_degree_type` = `fbpm`.`target_degree_type`
   LEFT JOIN `fellow_qualifying_degree` fqd ON `fqd`.`id`=`fdpc`.`qualifying_degree`
   LEFT JOIN `user_details` x ON `x`.`id`=`fm`.`guide`
    group by fbm.`stud_reg_no`
   ORDER BY admn_date
";


        $query = $this->db->query($sql, $secure_array);
        //  echo $this->db->last_query();
        if ($query->num_rows() > 0)
            return $query->result_array();
        else
            return FALSE;
    }

    // list of active to be processed(new & forwarded/approved) fellow appeard to current  Actor dynamically

    function get_Active_fellow_details_by_param($actor, $target_degree, $mon, $yr, $qualifying_degree_type, $action) {
        $where_clause_var2 = "";
        switch ($action) {
            case 'active':
                $p_satus = "Y";
                $not = "!";

                $order_by = "admn_date";
                break;
            case 'approved':
            case 'forwarded':
                $p_satus = "Y";
                $not = "";

                $order_by = "dt desc";
                break;
        }

        if (in_array("gd", $this->session->userdata('auth')) && $actor == "gd") {
            $select_clause_var = " DATE_FORMAT(fbm.attd_approval_by_gd_time,'%d/%m/%Y %r') as dt ,COALESCE(fbm.attd_approval_by_gd,'N') as attd_approval_by_gd";
            $where_clause_var = " and fbm.attd_forwarding_by_dda ";
            $where_clause_var1 = " and fbm.attd_approval_by_gd ";
            $dept = " and u.dept_id=? ";
            if ($action == "active") {
                $where_clause_var2 = " and fbm.attd_approval_by_gd!=? ";
                $secure_array = array($this->session->userdata('dept_id'), 'Y', $qualifying_degree_type, $mon, $yr, "Y", $p_satus, "S", $target_degree);
            } else {
                $secure_array = array($this->session->userdata('dept_id'), 'Y', $qualifying_degree_type, $mon, $yr, "Y", $p_satus, $target_degree);
            }
        } else if (in_array("fic_jrf", $this->session->userdata('auth')) && $actor == "fic_jrf") {
            $select_clause_var = " DATE_FORMAT(fbm.attd_forward_by_fic_time,'%d/%m/%Y %r') as dt ,COALESCE(fbm.attd_forward_by_fic,'N') as attd_forward_by_fic";
            $where_clause_var = " and fbm.attd_approval_by_gd ";
            $where_clause_var1 = " and fbm.attd_forward_by_fic ";
            $dept = " and u.dept_id=? ";
            $secure_array = array($this->session->userdata('dept_id'), 'Y', $qualifying_degree_type, $mon, $yr, "Y", $p_satus, $target_degree);
        } else if (in_array("hod", $this->session->userdata('auth')) && $actor == "hod") {
            $select_clause_var = " DATE_FORMAT(fbm.attd_approval_by_hod_time,'%d/%m/%Y %r') as dt ,COALESCE(fbm.attd_approval_by_hod,'N') as attd_approval_by_hod";
            $where_clause_var = "and fbm.attd_forward_by_fic ";
            $where_clause_var1 = " and fbm.attd_approval_by_hod ";
            $dept = " and u.dept_id=? ";
            $secure_array = array($this->session->userdata('dept_id'), 'Y', $qualifying_degree_type, $mon, $yr, "Y", $p_satus, $target_degree);
        } else if (in_array("acad_da5", $this->session->userdata('auth')) && $actor == "acad_da") {
            $select_clause_var = " DATE_FORMAT(fbm.attd_forwarding_by_acad_da_time,'%d/%m/%Y %r') as dt ,COALESCE(fbm.attd_forwarding_by_acad_da,'N') as attd_forwarding_by_acad_da ";
            $where_clause_var = " and fbm.attd_approval_by_hod";
            $where_clause_var1 = " and fbm.attd_forwarding_by_acad_da ";
            $dept = "";
            $secure_array = array('Y', $qualifying_degree_type, $mon, $yr, "Y", $p_satus, $target_degree);
        } else if (in_array("acad_ar", $this->session->userdata('auth')) && $actor == "acad_ar") {
            $select_clause_var = " DATE_FORMAT(fbm.attd_approval_by_acad_ar_time,'%d/%m/%Y %r') as dt ,COALESCE(fbm.attd_approval_by_acad_ar,'N') as attd_approval_by_acad_ar";
            $where_clause_var = " and fbm.attd_forwarding_by_acad_da";
            $where_clause_var1 = " and fbm.attd_approval_by_acad_ar ";
            $dept = "";
            $secure_array = array('Y', $qualifying_degree_type, $mon, $yr, "Y", $p_satus, $target_degree);
        } else if (in_array("acc_da5", $this->session->userdata('auth')) && $actor == "acc_da") {
            $select_clause_var = " DATE_FORMAT(fbm.attd_forwarding_by_acc_da_time,'%d/%m/%Y %r') as dt ,COALESCE(fbm.attd_forwarding_by_acc_da,'N') as attd_forwarding_by_acc_da";
            $where_clause_var = " and fbm.attd_approval_by_acad_ar";
            $where_clause_var1 = " and fbm.attd_forwarding_by_acc_da ";
            $dept = "";
            $secure_array = array('Y', $qualifying_degree_type, $mon, $yr, "Y", $p_satus, $target_degree);
        } else if (in_array("acc_ar_ga", $this->session->userdata('auth')) && $actor == "acc_ar_ga") {
            $select_clause_var = " DATE_FORMAT(fbm.attd_approval_by_acc_ar_time,'%d/%m/%Y %r') as dt ,COALESCE(fbm.attd_approval_by_acc_ar,'N') as attd_approval_by_acc_ar";
            $where_clause_var = " and fbm.attd_forwarding_by_acc_da";
            $where_clause_var1 = " and fbm.attd_approval_by_acc_ar ";
            $dept = "";
            $secure_array = array('Y', $qualifying_degree_type, $mon, $yr, "Y", $p_satus, $target_degree);
        } else if (in_array("rg", $this->session->userdata('auth')) && $actor == "rg") {
            $select_clause_var = " DATE_FORMAT(fbm.bill_approval_by_rg_time,'%d/%m/%Y %r') as dt, COALESCE(fbm.bill_approval_by_rg,'N') as bill_approval_by_rg ";
            $where_clause_var = " and fbm.attd_approval_by_acc_ar ";
            $where_clause_var1 = " and fbm.bill_approval_by_rg ";
            $dept = "";
            $secure_array = array('Y', $qualifying_degree_type, $mon, $yr, "Y", $p_satus, $target_degree);
        }
        $sql = "SELECT DATE_FORMAT(st.admn_date,'%d %b %Y')as admn_date, fm.stud_reg_no, fm.guide, stother.account_no, u.salutation, u.dept_id, u.first_name, u.middle_name, u.last_name,
concat_ws(' ', x.salutation, x.first_name, x.middle_name, x.last_name) as guide_name,
fbm.present_yr_fellowship as target_degree_type_yr,flv.leave_permissible_yrly,fbpm.target_degree_type ,fqd.qualifying_degree,
 fbpm.fellowship_amt, COALESCE(fbm.leave_balance,0)as leave_balance ,fbm.recovery_amt,fbm.net_amt_payable,
  COALESCE(fbm.days_absent,0)as days_absent,COALESCE(fbm.admissible_leave,0)as admissible_leave,
   " . $select_clause_var . "
  FROM (`fellow_master` fm) JOIN `stu_details` st ON `st`.`admn_no` = `fm`.`stud_reg_no`
  JOIN `stu_other_details` stother ON `stother`.`admn_no` = `fm`.`stud_reg_no`
  JOIN `user_details` u ON `u`.`id` = `stother`.`admn_no` " . $dept . "
  JOIN `fellow_dsc_process_control` fdpc ON `fdpc`.`stud_reg_no` = `fm`.`stud_reg_no` and `fdpc`.fellow_eligibility=? and fdpc.qualifying_degree_type=?
  JOIN `fellow_bill_master` fbm ON `fbm`.`stud_reg_no` = `fm`.`stud_reg_no` and `fbm`.`target_degree` = `fm`.`target_degree` and `fbm`.processing_mon=? and `fbm`.processing_yr=?  " . $where_clause_var . "=?  " . $where_clause_var1 . " " . $not . "=?    " . $where_clause_var2 . "
  JOIN fellow_amt_payable_master fbpm ON `fbpm`.`qualifying_degree` = `fdpc`.`qualifying_degree` and `fbpm`.target_degree_type=?   and  `fbpm`.target_degree_type_yr=`fbm`.`present_yr_fellowship`
  JOIN `fellow_leave_master` flv ON `flv`.`target_degree_type` = `fbpm`.`target_degree_type`
  LEFT JOIN `fellow_qualifying_degree` fqd ON `fqd`.`id`=`fdpc`.`qualifying_degree`
   LEFT JOIN `user_details` x ON `x`.`id`=`fm`.`guide`
   group by fbm.`stud_reg_no`  ORDER BY  " . $order_by . "
";


        $query = $this->db->query($sql, $secure_array);
        // echo $this->db->last_query();
        if ($query->num_rows() > 0)
            return $query->result_array();
        else
            return FALSE;
    }

    //---------------------------------Pay-order----------------------------

    public function get_payorder_slots_not_append_by_c_da($target_degree, $mon, $yr) {
        $select = $this->db->select('slot')->where(array('target_degree' => $target_degree, 'processing_mon' => (int) $mon, 'processing_yr' => $yr, 'bill_forwarding_by_acc_da' => "Y", 'bill_forwarding_by_acc_ar' => "Y", 'bill_approval_by_rg' => "Y", 'payorder_fwd_by_acc_da' => "Y", 'payorder_appd_by_acc_ar' => "Y", 'payorder_append_by_c_da !=' => "Y"))->order_by('payorder_appd_by_acc_ar_time', 'asc')->get('fellow_grp_transaction');

        if ($select->num_rows()) {
            return $select->result_array();
        } else {
            $select = $this->db->select('count(1)+1 as slot')->where(array('target_degree' => $target_degree, 'processing_mon' => (int) $mon, 'processing_yr' => $yr, 'bill_forwarding_by_acc_da' => "Y", 'bill_forwarding_by_acc_ar' => "Y", 'bill_approval_by_rg' => "Y", 'payorder_fwd_by_acc_da' => "Y", 'payorder_appd_by_acc_ar' => "Y", 'payorder_append_by_c_da' => "Y"))->get('fellow_grp_transaction');
            if ($select->num_rows()) {
                return $select->result_array();
            } else {
                return 0;
            }
        }
    }

    public function get_default_payorder_slots_not_append_by_c_da($target_degree, $mon, $yr) {
        $select = $this->db->select('slot', false)->where(array('target_degree' => $target_degree, 'processing_mon' => (int) $mon, 'processing_yr' => $yr, 'bill_forwarding_by_acc_da' => "Y", 'bill_forwarding_by_acc_ar' => "Y", 'bill_approval_by_rg' => "Y", 'payorder_fwd_by_acc_da' => "Y", 'payorder_appd_by_acc_ar' => "Y", 'payorder_append_by_c_da !=' => "Y"))->order_by('payorder_appd_by_acc_ar_time', 'asc')->limit('1')->get('fellow_grp_transaction');

        if ($select->num_rows()) {
            $row = $select->result_array();
            return $row[0]['slot'];
        } else {
            return 0;
        }
    }

    public function get_payorder_slots_not_approved_by_acc_ar($target_degree, $mon, $yr) {
        $select = $this->db->select('slot')->where(array('target_degree' => $target_degree, 'processing_mon' => (int) $mon, 'processing_yr' => $yr, 'bill_forwarding_by_acc_da' => "Y", 'bill_forwarding_by_acc_ar' => "Y", 'bill_approval_by_rg' => "Y", 'payorder_fwd_by_acc_da' => "Y", 'payorder_appd_by_acc_ar !=' => "Y"))->order_by('payorder_fwd_by_acc_da_time', 'asc')->get('fellow_grp_transaction');

        if ($select->num_rows()) {
            return $select->result_array();
        } else {
            $select = $this->db->select('count(1)+1 as slot')->where(array('target_degree' => $target_degree, 'processing_mon' => (int) $mon, 'processing_yr' => $yr, 'bill_forwarding_by_acc_da' => "Y", 'bill_forwarding_by_acc_ar' => "Y", 'bill_approval_by_rg' => "Y", 'payorder_fwd_by_acc_da' => "Y", 'payorder_appd_by_acc_ar' => "Y"))->get('fellow_grp_transaction');
            if ($select->num_rows()) {
                return $select->result_array();
            } else {
                return 0;
            }
        }
    }

    public function get_default_payorder_slots_not_approved_by_acc_ar($target_degree, $mon, $yr) {
        $select = $this->db->select('slot', false)->where(array('target_degree' => $target_degree, 'processing_mon' => (int) $mon, 'processing_yr' => $yr, 'bill_forwarding_by_acc_da' => "Y", 'bill_forwarding_by_acc_ar' => "Y", 'bill_approval_by_rg' => "Y", 'payorder_fwd_by_acc_da' => "Y", 'payorder_appd_by_acc_ar !=' => "Y"))->order_by('payorder_fwd_by_acc_da_time', 'asc')->limit('1')->get('fellow_grp_transaction');

        if ($select->num_rows()) {
            $row = $select->result_array();
            return $row[0]['slot'];
        } else {
            return 0;
        }
    }

    /*  public function getMAX_Plus_payorder_slot($target_degree,$mon,$yr) {
      $select = $this->db->select('count(1)+1 as slot')->where(array('target_degree'=>$target_degree,'processing_mon'=>(int)$mon,'processing_yr'=>$yr,'attd_forwarding_by_acc_ar'=>'Y','bill_forwarding_by_acc_ar'=>"Y",'bill_approval_by_rg'=>"Y",'payorder_fwd_by_acc_da'=>"Y"))->get('fellow_grp_transaction');
      if ($select->num_rows()) {
      $row = $select->result_array();
      return $row[0]['slot'];
      }
      else
      return false;
      }
     */

    public function get_payorder_slots_not_fd_by_acc_da($target_degree, $mon, $yr) {
        $select = $this->db->select('slot')->where(array('target_degree' => $target_degree, 'processing_mon' => (int) $mon, 'processing_yr' => $yr, 'bill_forwarding_by_acc_da' => "Y", 'bill_forwarding_by_acc_ar' => "Y", 'bill_approval_by_rg' => "Y", 'payorder_fwd_by_acc_da !=' => "Y"))->order_by('bill_approval_by_rg_time', 'asc')->get('fellow_grp_transaction');
        if ($select->num_rows()) {
            return $select->result_array();
        } else {
            $select = $this->db->select('count(1)+1 as slot')->where(array('target_degree' => $target_degree, 'processing_mon' => (int) $mon, 'processing_yr' => $yr, 'attd_forwarding_by_acc_ar' => 'Y', 'bill_forwarding_by_acc_ar' => "Y", 'bill_approval_by_rg' => "Y", 'payorder_fwd_by_acc_da' => "Y"))->get('fellow_grp_transaction');
            if ($select->num_rows()) {
                return $select->result_array();
            } else {
                return 0;
            }
        }
    }

    public function get_default_payorder_slots_not_fd_by_acc_da($target_degree, $mon, $yr) {
        $select = $this->db->select('slot', false)->where(array('target_degree' => $target_degree, 'processing_mon' => (int) $mon, 'processing_yr' => $yr, 'bill_forwarding_by_acc_ar' => "Y", 'bill_approval_by_rg' => "Y", 'payorder_fwd_by_acc_da !=' => "Y"))->order_by('bill_approval_by_rg_time', 'asc')->limit('1')->get('fellow_grp_transaction');

        if ($select->num_rows()) {
            $row = $select->result_array();
            return $row[0]['slot'];
        } else {
            return 0;
        }
    }

    //------------------------------------------------
    //-------------------------------------bill slot---------------
    /* public function getMAX_Plus_bill_slot($target_degree,$mon,$yr) {
      $select = $this->db->select('count(1)+1 as slot')->where(array('target_degree'=>$target_degree,'processing_mon'=>(int)$mon,'processing_yr'=>$yr,'attd_forwarding_by_acc_ar'=>'Y','attd_approval_by_rg'=>'Y','bill_forwarding_by_acc_da'=>"Y"))->get('fellow_grp_transaction');
      if ($select->num_rows()) {
      $row = $select->result_array();
      return $row[0]['slot'];
      }
      else
      return false;
      }
     */
    public function get_bill_slots_not_fd_by_acc_da($target_degree, $mon, $yr) {
        $select = $this->db->select('slot')->where(array('target_degree' => $target_degree, 'processing_mon' => (int) $mon, 'processing_yr' => $yr, 'attd_forwarding_by_acc_ar' => 'Y', 'attd_approval_by_rg' => 'Y', 'bill_forwarding_by_acc_da' => "N"))->order_by('attd_approval_by_rg_time', 'asc')->get('fellow_grp_transaction');
        if ($select->num_rows()) {
            return $select->result_array();
        } else {
            $select = $this->db->select('count(1)+1 as slot')->where(array('target_degree' => $target_degree, 'processing_mon' => (int) $mon, 'processing_yr' => $yr, 'attd_forwarding_by_acc_ar' => 'Y', 'attd_approval_by_rg' => 'Y', 'bill_forwarding_by_acc_da' => "Y"))->get('fellow_grp_transaction');
            if ($select->num_rows()) {
                return $select->result_array();
            } else {
                return 0;
            }
        }
    }

    public function get_default_bill_slots_not_fd_by_acc_da($target_degree, $mon, $yr) {
        $select = $this->db->select('slot', false)->where(array('target_degree' => $target_degree, 'processing_mon' => (int) $mon, 'processing_yr' => $yr, 'attd_forwarding_by_acc_ar' => 'Y', 'attd_approval_by_rg' => 'Y', 'bill_forwarding_by_acc_da' => "N"))->order_by('attd_approval_by_rg_time', 'asc')->limit('1')->get('fellow_grp_transaction');

        if ($select->num_rows()) {
            $row = $select->result_array();
            return $row[0]['slot'];
        } else {
            return 0;
        }
    }

    public function get_bill_slots_not_approved_by_acc_ar($target_degree, $mon, $yr) {
        $select = $this->db->select('slot')->where(array('target_degree' => $target_degree, 'processing_mon' => (int) $mon, 'processing_yr' => $yr, 'attd_forwarding_by_acc_ar' => 'Y', 'attd_approval_by_rg' => 'Y', 'bill_forwarding_by_acc_da' => "Y", 'bill_forwarding_by_acc_ar' => "N"))->order_by('bill_forwarding_by_acc_da_time', 'asc')->get('fellow_grp_transaction');
        if ($select->num_rows()) {
            return $select->result_array();
        } else {
            $select = $this->db->select('count(1)+1 as slot')->where(array('target_degree' => $target_degree, 'processing_mon' => (int) $mon, 'processing_yr' => $yr, 'attd_forwarding_by_acc_ar' => 'Y', 'attd_approval_by_rg' => 'Y', 'bill_forwarding_by_acc_da' => "Y", 'bill_forwarding_by_acc_ar' => "Y"))->get('fellow_grp_transaction');
            if ($select->num_rows()) {
                return $select->result_array();
            } else {
                return 0;
            }
        }
    }

    public function get_default_bill_slots_not_approved_by_acc_ar($target_degree, $mon, $yr) {
        $select = $this->db->select('slot', false)->where(array('target_degree' => $target_degree, 'processing_mon' => (int) $mon, 'processing_yr' => $yr, 'attd_forwarding_by_acc_ar' => 'Y', 'attd_approval_by_rg' => 'Y', 'bill_forwarding_by_acc_da' => "Y", 'bill_forwarding_by_acc_ar' => "N"))->order_by('bill_forwarding_by_acc_da_time', 'asc')->limit('1')->get('fellow_grp_transaction');

        if ($select->num_rows()) {
            $row = $select->result_array();
            return $row[0]['slot'];
        } else {
            return 0;
        }
    }

    public function get_bill_slots_not_approved_by_rg($target_degree, $mon, $yr) {
        $select = $this->db->select('slot')->where(array('target_degree' => $target_degree, 'processing_mon' => (int) $mon, 'processing_yr' => $yr, 'attd_forwarding_by_acc_ar' => 'Y', 'attd_approval_by_rg' => 'Y', 'bill_forwarding_by_acc_da' => "Y", 'bill_forwarding_by_acc_ar' => "Y", 'bill_approval_by_rg' => "N"))->order_by('bill_forwarding_by_acc_ar_time', 'asc')->get('fellow_grp_transaction');
        if ($select->num_rows()) {
            return $select->result_array();
        } else {
            $select = $this->db->select('count(1)+1 as slot')->where(array('target_degree' => $target_degree, 'processing_mon' => (int) $mon, 'processing_yr' => $yr, 'attd_forwarding_by_acc_ar' => 'Y', 'attd_approval_by_rg' => 'Y', 'bill_forwarding_by_acc_da' => "Y", 'bill_forwarding_by_acc_ar' => "Y", 'bill_approval_by_rg' => "Y"))->get('fellow_grp_transaction');
            if ($select->num_rows()) {
                return $select->result_array();
            } else {
                return 0;
            }
        }
    }

    public function get_default_bill_slots_not_approved_by_rg($target_degree, $mon, $yr) {
        $select = $this->db->select('slot', false)->where(array('target_degree' => $target_degree, 'processing_mon' => (int) $mon, 'processing_yr' => $yr, 'attd_forwarding_by_acc_ar' => 'Y', 'attd_approval_by_rg' => 'Y', 'bill_forwarding_by_acc_da' => "Y", 'bill_forwarding_by_acc_ar' => "Y", 'bill_approval_by_rg' => "N"))->order_by('bill_forwarding_by_acc_ar_time', 'asc')->limit('1')->get('fellow_grp_transaction');

        if ($select->num_rows()) {
            $row = $select->result_array();
            return $row[0]['slot'];
        } else {
            return 0;
        }
    }

    //------------------------------------------------------------------Attd. slot---------------------------------------------
    public function getMAX_Plus_slot($target_degree, $mon, $yr) {
        $select = $this->db->select('count(1)+1 as slot')->where(array('target_degree' => $target_degree, 'processing_mon' => (int) $mon, 'processing_yr' => $yr, 'attd_forwarding_by_acc_ar' => 'Y'))->get('fellow_grp_transaction');
        if ($select->num_rows()) {
            $row = $select->result_array();
            return $row[0]['slot'];
        } else
            return false;
    }

    public function get_slots_not_approved_by_rg($target_degree, $mon, $yr) {
        $select = $this->db->select('slot')->where(array('target_degree' => $target_degree, 'processing_mon' => (int) $mon, 'processing_yr' => $yr, 'attd_forwarding_by_acc_ar' => 'Y', 'attd_approval_by_rg' => 'N'))->order_by('attd_forwarding_by_acc_ar_time', 'asc')->get('fellow_grp_transaction');
        if ($select->num_rows()) {
            return $select->result_array();
        } else {
            $select = $this->db->select('count(1)+1 as slot')->where(array('target_degree' => $target_degree, 'processing_mon' => (int) $mon, 'processing_yr' => $yr, 'attd_forwarding_by_acc_ar' => 'Y', 'attd_approval_by_rg' => 'Y'))->get('fellow_grp_transaction');
            if ($select->num_rows()) {
                return $select->result_array();
            } else {
                return 0;
            }
        }
    }

    public function get_default_slots_not_approved_by_rg($target_degree, $mon, $yr) {
        $select = $this->db->select('slot', false)->where(array('target_degree' => $target_degree, 'processing_mon' => (int) $mon, 'processing_yr' => $yr, 'attd_forwarding_by_acc_ar' => 'Y', 'attd_approval_by_rg' => 'N'))->order_by('attd_forwarding_by_acc_ar_time', 'asc')->limit('1')->get('fellow_grp_transaction');

        if ($select->num_rows()) {
            $row = $select->result_array();
            return $row[0]['slot'];
        } else {
            return 0;
        }
    }

    //------    ------------------------------------------------------------------------------------------------------------------

    function get_final_fellow_attd_list_by_param($actor, $target_degree, $mon, $yr, $qualifying_degree_type, $slot) {

        if (in_array("acc_ar_ga", $this->session->userdata('auth')) && $actor == "acc_ar_ga") {
            $select_clause_var = " DATE_FORMAT(fbm.attd_approval_by_acc_ar_time,'%d/%m/%Y %r') as dt ,COALESCE(fbm.attd_approval_by_acc_ar,'N') as attd_approval_by_acc_ar";
            $where_clause_var = " and fbm.attd_approval_by_acc_ar ";  //Y
            $where_clause_var1 = " and fbm.grp_attd_fd_by_acc_ar ";   //N
            $secure_array = array('Y', $qualifying_degree_type, $mon, $yr, "Y", "N", $target_degree);
            $sql = "

                        SELECT DATE_FORMAT(st.admn_date,'%d %b %Y')as admn_date, fm.stud_reg_no, fm.guide, stother.account_no, u.salutation, u.dept_id, u.first_name, u.middle_name, u.last_name,
                        concat_ws(' ', x.salutation, x.first_name, x.middle_name, x.last_name) as guide_name,
                        fbm.present_yr_fellowship as target_degree_type_yr,fbpm.target_degree_type ,fqd.qualifying_degree,
                        fbpm.fellowship_amt, flv.leave_permissible_yrly,COALESCE(fbm.leave_balance,0)as leave_balance ,COALESCE(fbm.admissible_leave,0)as admissible_leave,fbm.recovery_amt,fbm.net_amt_payable,
                        COALESCE(fbm.days_absent,0)as days_absent," . $select_clause_var . "
                          FROM (`fellow_master` fm) JOIN `stu_details` st ON `st`.`admn_no` = `fm`.`stud_reg_no`
                          JOIN `stu_other_details` stother ON `stother`.`admn_no` = `fm`.`stud_reg_no`
                          JOIN `user_details` u ON `u`.`id` = `stother`.`admn_no`
                          JOIN `fellow_dsc_process_control` fdpc ON `fdpc`.`stud_reg_no` = `fm`.`stud_reg_no` and `fdpc`.fellow_eligibility=? and fdpc.qualifying_degree_type=?
                          JOIN `fellow_bill_master` fbm ON `fbm`.`stud_reg_no` = `fm`.`stud_reg_no` and `fbm`.`target_degree` = `fm`.`target_degree` and `fbm`.processing_mon=? and `fbm`.processing_yr=?  " . $where_clause_var . " = ?      " . $where_clause_var1 . " = ?
                          JOIN fellow_amt_payable_master fbpm ON `fbpm`.`qualifying_degree` = `fdpc`.`qualifying_degree` and `fbpm`.target_degree_type=?   and  `fbpm`.target_degree_type_yr=`fbm`.`present_yr_fellowship`

                          JOIN `fellow_leave_master` flv ON `flv`.`target_degree_type` = `fbpm`.`target_degree_type`
                          LEFT JOIN `fellow_qualifying_degree` fqd ON `fqd`.`id`=`fdpc`.`qualifying_degree`
                          LEFT JOIN `user_details` x ON `x`.`id`=`fm`.`guide`
                          group by fm.`stud_reg_no` ORDER BY dt desc
                        ";
        } else if (in_array("rg", $this->session->userdata('auth')) && $actor == "rg") {
            $select_clause_var = " COALESCE(fgt.attd_forwarding_by_acc_ar,'N') as  grp_attd_forwarding_by_acc_ar, DATE_FORMAT(fgt.attd_forwarding_by_acc_ar_time,'%d/%m/%Y %r') as grp_dt_prev,COALESCE(fgt.attd_approval_by_rg,'N') as  grp_attd_approval_by_rg, DATE_FORMAT(fgt.attd_approval_by_rg_time,'%d/%m/%Y %r') as grp_dt , DATE_FORMAT(fbm.attd_approval_by_acc_ar_time,'%d/%m/%Y %r') as dt ,COALESCE(fbm.attd_approval_by_acc_ar,'N') as attd_approval_by_acc_ar";
            $where_clause_var = " and fbm.attd_approval_by_acc_ar "; //Y
            $where_clause_var1 = " and fbm.grp_attd_fd_by_acc_ar ";   //Y
            $where_clause_var2 = " and fbm.attd_approval_by_rg ";   //N
            $where_slot = " and fbm.slot ";
            $left_append = "  and fgt.attd_forwarding_by_acc_ar ";
            $left_append2 = " and fgt.attd_approval_by_rg ";
            $secure_array = array('Y', $qualifying_degree_type, $mon, $yr, "Y", "Y", "N", $slot, $target_degree, "Y", "N");
            $sql = "SELECT DATE_FORMAT(st.admn_date,'%d %b %Y')as admn_date, fm.stud_reg_no, fm.guide, stother.account_no, u.salutation, u.dept_id, u.first_name, u.middle_name, u.last_name,
                        concat_ws(' ', x.salutation, x.first_name, x.middle_name, x.last_name) as guide_name,
                        fbm.present_yr_fellowship as target_degree_type_yr,fbpm.target_degree_type ,fqd.qualifying_degree,
                         fbpm.fellowship_amt, flv.leave_permissible_yrly,COALESCE(fbm.leave_balance,0)as leave_balance , fbm.recovery_amt,fbm.net_amt_payable,
                          COALESCE(fbm.days_absent,0)as tot_absent,
                           " . $select_clause_var . "
                          FROM (`fellow_master` fm) JOIN `stu_details` st ON `st`.`admn_no` = `fm`.`stud_reg_no`
                          JOIN `stu_other_details` stother ON `stother`.`admn_no` = `fm`.`stud_reg_no`
                          JOIN `user_details` u ON `u`.`id` = `stother`.`admn_no`
                           JOIN `fellow_dsc_process_control` fdpc ON `fdpc`.`stud_reg_no` = `fm`.`stud_reg_no` and `fdpc`.fellow_eligibility=? and fdpc.qualifying_degree_type=?
                          JOIN `fellow_bill_master` fbm ON `fbm`.`stud_reg_no` = `fm`.`stud_reg_no` and `fbm`.`target_degree` = `fm`.`target_degree` and `fbm`.processing_mon=? and `fbm`.processing_yr=?  " . $where_clause_var . " = ?      " . $where_clause_var1 . " = ?    " . $where_clause_var2 . " = ?   " . $where_slot . " = ?
                          JOIN fellow_amt_payable_master fbpm ON `fbpm`.`qualifying_degree` = `fdpc`.`qualifying_degree` and `fbpm`.target_degree_type=?   and  `fbpm`.target_degree_type_yr=`fbm`.`present_yr_fellowship`
                          JOIN `fellow_leave_master` flv ON `flv`.`target_degree_type` = `fbpm`.`target_degree_type`

                           JOIN `fellow_grp_transaction` fgt ON  `fgt`.`target_degree` = `fbm`.`target_degree` and fgt.processing_mon=fbm.processing_mon and fgt.processing_yr=fbm.processing_yr and  `fgt`.`slot`= `fbm`.`slot`  " . $left_append . "=?  " . $left_append2 . "=?
                           LEFT JOIN `fellow_qualifying_degree` fqd ON `fqd`.`id`=`fdpc`.`qualifying_degree`
                           LEFT JOIN `user_details` x ON `x`.`id`=`fm`.`guide`
                           group by fm.`stud_reg_no` ORDER BY dt desc
                        ";
        }


        $query = $this->db->query($sql, $secure_array);
        //  echo $this->db->last_query();
        if ($query->num_rows() > 0)
            return $query->result_array();
        else
            return FALSE;
    }

    function get_blocked_transfr_attd_list_by_param($actor, $slot, $target_degree, $mon, $yr, $qualifying_degree_type) {

        if (in_array("acc_ar_ga", $this->session->userdata('auth')) && $actor == "acc_ar_ga") {
            $select_clause_var = " COALESCE(fgt.attd_forwarding_by_acc_ar,'N') as  grp_attd_forwarding_by_acc_ar, DATE_FORMAT(fgt.attd_forwarding_by_acc_ar_time,'%d/%m/%Y %r') as grp_dt,COALESCE(fgt.attd_approval_by_rg,'N') as  grp_attd_approval_by_rg, DATE_FORMAT(fgt.attd_approval_by_rg_time,'%d/%m/%Y %r') as grp_dt_next ";
            $where_clause_var = " and fbm.attd_approval_by_acc_ar ";
            $left_append = " and fgt.attd_forwarding_by_acc_ar ";
        } else if (in_array("rg", $this->session->userdata('auth')) && $actor == "rg") {
            $select_clause_var = " COALESCE(fgt.attd_forwarding_by_acc_ar,'N') as  grp_attd_forwarding_by_acc_ar, DATE_FORMAT(fgt.attd_forwarding_by_acc_ar_time,'%d/%m/%Y %r') as grp_dt_prev,COALESCE(fgt.attd_approval_by_rg,'N') as  grp_attd_approval_by_rg, DATE_FORMAT(fgt.attd_approval_by_rg_time,'%d/%m/%Y %r') as grp_dt  ";
            $where_clause_var = " and fbm.attd_approval_by_acc_ar ";
            $left_append = "  and fgt.attd_approval_by_rg ";
        }
        if ($slot == "") {
            $where_slot = "";
            $secure_array = array('Y', $qualifying_degree_type, $mon, $yr, "Y", $target_degree, "Y");
            $grp_by = " fbm.`slot` ";
            $order_by = " fbm.`slot` desc ";
            $select_grp = " count(`fbm`.`stud_reg_no`) as tot_fellow, ";
        } else {
            $grp_by = " `fm`.`stud_reg_no` ";
            $order_by = " grp_dt desc ";
            $select_grp = "";
            $where_slot = " and slot=? ";
            $secure_array = array('Y', $qualifying_degree_type, $mon, $yr, "Y", $slot, $target_degree, "Y");
        }

        $sql = "SELECT " . $select_grp . " DATE_FORMAT(st.admn_date,'%d %b %Y')as admn_date, fm.stud_reg_no, fm.guide, stother.account_no, u.salutation, u.dept_id, u.first_name, u.middle_name, u.last_name,
concat_ws(' ', x.salutation, x.first_name, x.middle_name, x.last_name) as guide_name,
fbm.present_yr_fellowship as target_degree_type_yr,fbpm.target_degree_type ,fqd.qualifying_degree,
 fbpm.fellowship_amt, flv.leave_permissible_yrly,COALESCE(fbm.leave_balance,0)as leave_balance , fbm.recovery_amt,fbm.net_amt_payable,
  COALESCE(fbm.days_absent,0)as tot_absent,fbm.slot,
   " . $select_clause_var . "
 FROM (`fellow_master` fm) JOIN `stu_details` st ON `st`.`admn_no` = `fm`.`stud_reg_no`
  JOIN `stu_other_details` stother ON `stother`.`admn_no` = `fm`.`stud_reg_no`
  JOIN `user_details` u ON `u`.`id` = `stother`.`admn_no`
  JOIN `fellow_dsc_process_control` fdpc ON `fdpc`.`stud_reg_no` = `fm`.`stud_reg_no` and fellow_eligibility=? and fdpc.qualifying_degree_type=?
  JOIN `fellow_bill_master` fbm ON `fbm`.`stud_reg_no` = `fm`.`stud_reg_no` and `fbm`.`target_degree` = `fm`.`target_degree` and `fbm`.processing_mon=? and `fbm`.processing_yr=?  " . $where_clause_var . " = ?   " . $where_slot . "
  JOIN fellow_amt_payable_master fbpm ON `fbpm`.`qualifying_degree` = `fdpc`.`qualifying_degree` and `fbpm`.target_degree_type=?  and  `fbpm`.target_degree_type_yr=`fbm`.`present_yr_fellowship`

   JOIN `fellow_leave_master` flv ON `flv`.`target_degree_type` = `fbpm`.`target_degree_type`

   JOIN `fellow_grp_transaction` fgt ON  `fgt`.`target_degree` = `fbm`.`target_degree` and fgt.processing_mon=fbm.processing_mon and fgt.processing_yr=fbm.processing_yr and  `fgt`.`slot`= `fbm`.`slot` " . $left_append . "=?
   LEFT JOIN `fellow_qualifying_degree` fqd ON `fqd`.`id`=`fdpc`.`qualifying_degree`
   LEFT JOIN `user_details` x ON `x`.`id`=`fm`.`guide`
   group by " . $grp_by . " ORDER BY " . $order_by . "
";


        $query = $this->db->query($sql, $secure_array);
        //  echo $this->db->last_query();
        if ($query->num_rows() > 0)
            return $query->result_array();
        else
            return FALSE;
    }

    // view bill  to  all respective  actors  (rg,acc_da,acc_ar_ga)
    function get_bill_by_param($actor, $slot, $target_degree, $mon, $yr) {
        if ($slot != "") {
            $grp_by = " A.stud_reg_no ";
            $order_by = " A.dept_id ";
        } else {
            $grp_by = " A.slot ";
            $order_by = " A.slot desc ";
        }

        $select_clause_var = " COALESCE(fgt.bill_forwarding_by_acc_da,'N') as  bill_forwarding_by_acc_da, DATE_FORMAT(fgt.bill_forwarding_by_acc_da_time,'%d/%m/%Y %r') as  acc_da_dt,
                               COALESCE(fgt.bill_forwarding_by_acc_ar,'N') as  bill_forwarding_by_acc_ar, DATE_FORMAT(fgt.bill_forwarding_by_acc_ar_time,'%d/%m/%Y %r') as acc_ar_dt,
                               COALESCE(fgt.bill_approval_by_rg,'N') as  bill_approval_by_rg, DATE_FORMAT(fgt.bill_approval_by_rg_time,'%d/%m/%Y %r') as rg_dt ";

        if (in_array("acc_da5", $this->session->userdata('auth')) && $actor == "acc_da") {
            $where_clause_var = " and  fgt.attd_approval_by_rg =? and fgt.bill_forwarding_by_acc_da=? ";
            if ($slot == "") {
                $where_slot = "";
                $secure_array = array($target_degree, $mon, $yr, "Y", "Y", "Y");
            } else {
                $where_slot = " and fbm.slot=? ";
                $secure_array = array($target_degree, $mon, $yr, "Y", $slot, "Y", "Y");
            }
        } else if (in_array("acc_ar_ga", $this->session->userdata('auth')) && $actor == "acc_ar_ga") {

            $where_clause_var = " and  fgt.attd_approval_by_rg =? and fgt.bill_forwarding_by_acc_da=? and fgt.bill_forwarding_by_acc_ar=? ";

            if ($slot == "") {
                $where_slot = "";
                $secure_array = array($target_degree, $mon, $yr, "Y", "Y", "Y", "Y");
            } else {
                $where_slot = " and fbm.slot=? ";
                $secure_array = array($target_degree, $mon, $yr, "Y", $slot, "Y", "Y", "Y");
            }
        } else if (in_array("rg", $this->session->userdata('auth')) && $actor == "rg") {

            $where_clause_var = " and  fgt.attd_approval_by_rg =? and fgt.bill_forwarding_by_acc_da=? and fgt.bill_forwarding_by_acc_ar=? and fgt.bill_approval_by_rg=?";
            if ($slot == "") {
                $where_slot = "";
                $secure_array = array($target_degree, $mon, $yr, "Y", "Y", "Y", "Y", "Y");
            } else {
                $where_slot = " and fbm.slot=? ";
                $secure_array = array($target_degree, $mon, $yr, "Y", $slot, "Y", "Y", "Y", "Y");
            }
        }



        $sql = "select " . $select_clause_var . ", A.*,sum(A.net_amt_payable) as tot,concat_ws(' ', u.salutation, u.first_name, u.middle_name, u.last_name) as fellow_name,stother.account_no from
    (select fbm.target_degree,fbm.stud_reg_no,fbm.dept_id,fbm.net_amt_payable ,fbm.processing_mon,fbm.processing_yr,fbm.slot  from  fellow_bill_master fbm  where  `fbm`.`target_degree` = ?
    and fbm.processing_mon=? and fbm.processing_yr=?  and attd_approval_by_rg=?  " . $where_slot . "  )A
    JOIN  stu_other_details stother ON  `stother`.`admn_no` =  `A`.`stud_reg_no`
    JOIN `fellow_grp_transaction` fgt ON  `fgt`.`target_degree` = `A`.`target_degree` and `fgt`.processing_mon=`A`.processing_mon and fgt.processing_yr=`A`.processing_yr and  `fgt`.`slot`= `A`.`slot` " . $where_clause_var . "
   LEFT JOIN `user_details` u ON `u`.`id`=`A`.`stud_reg_no` group by " . $grp_by . " order by " . $order_by . " ";

        $query = $this->db->query($sql, $secure_array);
        // echo $this->db->last_query();
        if ($query->num_rows() > 0)
            return $query->result_array();
        else
            return FALSE;
    }

    // payorder genration & forwrading & approval & appending  by acc_da , acc_ar_ga, acc_da4 respectively
    function get_payorder_gen_param($actor, $target_degree, $mon, $yr, $slot) {

        $select_clause_var = " COALESCE(fgt.payorder_fwd_by_acc_da,'N') as  payorder_fwd_by_acc_da, DATE_FORMAT(fgt.payorder_fwd_by_acc_da_time,'%d/%m/%Y %r') as  acc_da_dt,
                               COALESCE(fgt.payorder_appd_by_acc_ar,'N') as  payorder_appd_by_acc_ar, DATE_FORMAT(fgt.payorder_appd_by_acc_ar_time,'%d/%m/%Y %r') as acc_ar_dt,
                               COALESCE(fgt.payorder_append_by_c_da,'N') as  payorder_append_by_c_da, DATE_FORMAT(fgt.payorder_append_by_c_da_time,'%d/%m/%Y %r') as  c_da_dt ";

        if (in_array("acc_da5", $this->session->userdata('auth')) && $actor == "acc_da") {
            $where_clause_var = " and   fgt.bill_forwarding_by_acc_da=? and fgt.bill_forwarding_by_acc_ar=? and fgt.bill_approval_by_rg=?  and fgt.payorder_appd_by_acc_ar <> ?";

            $secure_array = array($target_degree, $mon, $yr, $slot, "Y", "Y", "Y", "Y");
        } else if (in_array("acc_ar_ga", $this->session->userdata('auth')) && $actor == "acc_ar_ga") {

            $where_clause_var = " and   fgt.bill_forwarding_by_acc_da=? and fgt.bill_forwarding_by_acc_ar=? and fgt.bill_approval_by_rg=?  and fgt.payorder_fwd_by_acc_da <> ? and fgt.payorder_appd_by_acc_ar <> ?";


            $secure_array = array($target_degree, $mon, $yr, $slot, "Y", "Y", "Y", "N", "Y");
        } else if (in_array("acc_da4", $this->session->userdata('auth')) && $actor == "acc_da4") {
            $where_clause_var = " and   fgt.bill_forwarding_by_acc_da=? and fgt.bill_forwarding_by_acc_ar=? and fgt.bill_approval_by_rg=? and fgt.payorder_fwd_by_acc_da = ? and fgt.payorder_appd_by_acc_ar = ? and fgt.payorder_append_by_c_da <> ?";

            $secure_array = array($target_degree, $mon, $yr, $slot, "Y", "Y", "Y", "Y", "Y", "Y");
        }


        $sql = "select A.* ,`pmm`.`name` from(select  DATE_FORMAT(`fgt`.`ref_no_date`,'%d %b %Y') as `ref_no_date`,`fgt`.`payment_mode`,`fgt`.`debit_head_type`,`fgt`.`bill_amt`,`fgt`.`slot`,`fgt`.`target_degree`, `fgt`.`ref_no` ," . $select_clause_var . "
     from
    `fellow_grp_transaction` fgt  where  `fgt`.`target_degree` = ? and fgt.processing_mon=?  and fgt.processing_yr=?  and fgt.slot=?   " . $where_clause_var . "
     )A left join fellow_payment_mode_master pmm  on `A`.`payment_mode`=`pmm`.`id`";

        $query = $this->db->query($sql, $secure_array);

        if ($query->num_rows() > 0)
            return $query->result_array();
        else
            return FALSE;
    }

    function payorder_generated_list_by_param($actor, $target_degree, $mon, $yr, $slot) {

        $select_clause_var = " COALESCE(fgt.payorder_fwd_by_acc_da,'N') as  payorder_fwd_by_acc_da, DATE_FORMAT(fgt.payorder_fwd_by_acc_da_time,'%d/%m/%Y %r') as  acc_da_dt,
                                COALESCE(fgt.payorder_appd_by_acc_ar,'N') as  payorder_appd_by_acc_ar, DATE_FORMAT(fgt.payorder_appd_by_acc_ar_time,'%d/%m/%Y %r') as acc_ar_dt ,
                                COALESCE(fgt.payorder_append_by_c_da,'N') as  payorder_append_by_c_da, DATE_FORMAT(fgt.payorder_append_by_c_da_time,'%d/%m/%Y %r') as  c_da_dt ";


        if (in_array("acc_ar_ga", $this->session->userdata('auth')) && $actor == "acc_ar_ga") {
            $where_clause_var = " and  fgt.bill_approval_by_rg=?  and fgt.payorder_fwd_by_acc_da =? and fgt.payorder_appd_by_acc_ar =? ";

            if ($slot == "") {
                $where_slot = "";
                $secure_array = array($target_degree, $mon, $yr, "Y", "Y", "Y");
            } else {
                $where_slot = " and fgt.slot=? ";
                $secure_array = array($target_degree, $mon, $yr, "Y", "Y", "Y", $slot);
            }
        } else if (in_array("acc_da5", $this->session->userdata('auth')) && $actor == "acc_da") {
            $where_clause_var = " and  fgt.bill_approval_by_rg=?  and fgt.payorder_fwd_by_acc_da =? and fgt.payorder_appd_by_acc_ar !=? ";

            if ($slot == "") {
                $where_slot = "";
                $secure_array = array($target_degree, $mon, $yr, "Y", "Y", "R");
            } else {
                $where_slot = " and fgt.slot=? ";
                $secure_array = array($target_degree, $mon, $yr, "Y", "Y", "R", $slot);
            }
        } else if (in_array("acc_da4", $this->session->userdata('auth')) && $actor == "acc_da4") {
            $where_clause_var = " and  fgt.bill_approval_by_rg=?  and fgt.payorder_fwd_by_acc_da =? and fgt.payorder_appd_by_acc_ar =? and fgt.payorder_append_by_c_da !=? ";
            if ($slot == "") {
                $where_slot = "";
                $secure_array = array($target_degree, $mon, $yr, "Y", "Y", "Y", "N");
            } else {
                $where_slot = " and fgt.slot=? ";
                $secure_array = array($target_degree, $mon, $yr, "Y", "Y", "Y", "N", $slot);
            }
        }


        $sql = "select A.* ,`pmm`.`name` , `dh`.`name` as  `debit_head_name` from(select `fgt`.`payorder_id`,`fgt`.`ref_no`,DATE_FORMAT(`fgt`.`ref_no_date`,'%d %b %Y') as `ref_no_date`,`fgt`.`payment_mode`,`fgt`.`debit_head_type`,`fgt`.`bill_amt`,`fgt`.`slot`,`fgt`.`processing_yr`,`fgt`.`processing_mon`,`fgt`.`target_degree`, " . $select_clause_var . "
     from
    `fellow_grp_transaction` fgt  where  `fgt`.`target_degree` = ? and fgt.processing_mon=?  and fgt.processing_yr=?    " . $where_clause_var . " " . $where_slot . "
     )A
     left join fellow_payment_mode_master pmm  on `A`.`payment_mode`=`pmm`.`id`
     left join fellow_debit_head_master dh  on `A`.`debit_head_type`=`dh`.`id` order by A.slot desc";

        $query = $this->db->query($sql, $secure_array);
        // echo $this->db->last_query();
        if ($query->num_rows() > 0)
            return $query->result_array();
        else
            return FALSE;
    }

    function get_payment_mode() {
        $this->db->select("id,name", false);
        $this->db->where("status", "Y");
        $this->db->from('fellow_payment_mode_master');
        $query = $this->db->get();
        if ($query->num_rows() > 0)
            return $query->result_array();
        else
            return FALSE;
    }

    function get_debit_head() {
        $this->db->select("id,name", false);
        $this->db->where("status", "Y");
        $this->db->from('fellow_debit_head_master');
        $query = $this->db->get();
        if ($query->num_rows() > 0)
            return $query->result_array();
        else
            return FALSE;
    }

    function get_qfd() {
        $this->db->select("id ,qualifying_degree", false);
        $this->db->from('fellow_qualifying_degree');
        $query = $this->db->get();
        if ($query->num_rows() > 0)
            return $query->result_array();
        else
            return FALSE;
    }

    function get_admission_type() {
        $this->db->select("id ,type", false);
        $this->db->from('fellow_admission_type_master');
        $query = $this->db->get();
        if ($query->num_rows() > 0)
            return $query->result_array();
        else
            return FALSE;
    }

    // new  slot for  bill  to be forwarded/forwarded/approved by acc_da/acc_ar/rg respectively
    function get_eligible_fellow_bill_list_by_param($actor, $target_degree, $mon, $yr, $slot) {
        $where_slot = "";

        if (in_array("acc_da5", $this->session->userdata('auth')) && $actor == "acc_da") {
            $where_clause_var = " and  fgt.attd_approval_by_rg =? and fgt.bill_forwarding_by_acc_da=? ";
            if ($slot != "") {
                $where_slot = " and fgt.slot=? ";
                $secure_array = array($target_degree, $mon, $yr, "Y", "Y", "N", $slot);
            } else {
                $secure_array = array($target_degree, $mon, $yr, "Y", "Y", "N");
            }
        } else if (in_array("acc_ar_ga", $this->session->userdata('auth')) && $actor == "acc_ar_ga") {
            $where_clause_var = " and  fgt.attd_approval_by_rg =? and fgt.bill_forwarding_by_acc_da=? and fgt.bill_forwarding_by_acc_ar=? ";
            if ($slot != "") {
                $where_slot = " and fgt.slot=? ";
                $secure_array = array($target_degree, $mon, $yr, "Y", "Y", "Y", "N", $slot);
            } else {
                $secure_array = array($target_degree, $mon, $yr, "Y", "Y", "Y", "N");
            }
        } else if (in_array("rg", $this->session->userdata('auth')) && $actor == "rg") {
            $where_clause_var = " and  fgt.attd_approval_by_rg =? and fgt.bill_forwarding_by_acc_da=? and fgt.bill_forwarding_by_acc_ar=? and fgt.bill_approval_by_rg=?";
            if ($slot != "") {
                $where_slot = " and fgt.slot=? ";
                $secure_array = array($target_degree, $mon, $yr, "Y", "Y", "Y", "Y", "N", $slot);
            } else {
                $secure_array = array($target_degree, $mon, $yr, "Y", "Y", "Y", "Y", "N");
            }
        }
        $sql = "select  A.*,concat_ws(' ', u.salutation, u.first_name, u.middle_name, u.last_name) as fellow_name,stother.account_no from
    (select  fbm.target_degree,fbm.stud_reg_no,fbm.dept_id,fbm.net_amt_payable ,fbm.processing_mon,fbm.processing_yr,fbm.slot from  fellow_bill_master fbm  where  `fbm`.`target_degree` = ? and fbm.processing_mon=? and fbm.processing_yr=? and attd_approval_by_rg=? )A
     JOIN  stu_other_details stother ON  `stother`.`admn_no` =  `A`.`stud_reg_no`
     JOIN `fellow_grp_transaction` fgt ON  `fgt`.`target_degree` = `A`.`target_degree` and `fgt`.processing_mon=`A`.processing_mon and fgt.processing_yr=`A`.processing_yr and  `fgt`.`slot`= `A`.`slot`   " . $where_clause_var . "  " . $where_slot . "
     LEFT JOIN `user_details` u ON `u`.`id`=`A`.`stud_reg_no` group by A.dept_id,A.stud_reg_no ";
        $query = $this->db->query($sql, $secure_array);
        //  echo $this->db->last_query();
        if ($query->num_rows() > 0)
            return $query->result_array();
        else
            return FALSE;
    }

    //******************End of @Module :Fellow List(With all req.column) Population ************************//
    //******************@Module :Fellow List(name,reg.No.) Population ************************//
    /**
     * @desc: Get fellow List(name,reg.No.) those are enrolled in junior research fellow(short code=>jrf) and to be shown to respective actors
     *
     * @access	public
     * @param	String	      (junior research fellow(short code=>jrf))
     * @param	String	      (respective department)
     * @return	string array  (return Fellow List)
     */
    function get_fellow_name_by_Pending($type = '', $dept_id = '', $mon, $yr) {
        if (isset($type) && isset($dept_id)) {
            $secure_array = array($type, $dept_id, $yr);
            $sql = " select A.* from( select '0' as No_opr, st.admn_no ,DATE_FORMAT(st.admn_date,'%d %b %Y') as admn_date, st.stu_type, null as dt,  u.salutation,u.first_name,u.middle_name,u.last_name, null as status, null as co_guide_status,'N' as dsc_formation  from stu_details st
                     join  user_details u on u.id = st.admn_no 
					 join users us on us.id=u.id  and us.status='A'
					 and st.stu_type=? and u.dept_id=? and date_format(st.admn_date,'%Y')=? order by st.admn_date )A
                     where not exists
                   (select `fm`.`stud_reg_no`
                    from `fellow_master` fm  where  `fm`.`stud_reg_no` = A.`admn_no` )";
            $query = $this->db->query($sql, $secure_array);
            //   echo $this->db->last_query();
            if ($query->num_rows() > 0)
                return $query->result_array();
            else
                return FALSE;
        }
    }

    function get_fellow_name_by_param($type = '', $dept_id = '', $mon, $yr) {
        if (isset($type) && isset($dept_id)) {

            $this->db->select("count(fmml.stud_reg_no) as No_opr,st.admn_no,DATE_FORMAT(st.admn_date,'%d %b %Y') as admn_date,DATE_FORMAT(fm.created,'%d/%m/%Y %r') as dt, st.stu_type, u.salutation,u.first_name,u.middle_name,u.last_name,fm.guide as status,fm.co_guide as co_guide_status,fdpc.dsc_formation", false);
            $this->db->from('stu_details st');
            $this->db->join('user_details u', 'u.id = st.admn_no  and st.stu_type="' . $type . '"  and  u.dept_id="' . $dept_id . '" and date_format(st.admn_date,"%Y")="' . $yr . '" ');
			$this->db->join('users us', 'us.id = u.id and  us.status="A"');
            $this->db->join('fellow_master fm', 'fm.stud_reg_no = u.id and fm.guide_assigned_status="Y"');
            $this->db->join('fellow_dsc_process_control fdpc', 'fdpc.stud_reg_no = fm.stud_reg_no ', 'left');
            $this->db->join('fellow_master_mapping_log fmml', 'fmml.stud_reg_no = fm.stud_reg_no  group by u.id order by st.admn_date', 'left');
            $query = $this->db->get();
            //    echo $this->db->last_query();
            if ($query->num_rows() > 0)
                return $query->result_array();
            else
                return FALSE;
        } else
            return FALSE;
    }

    //******************End of @Module :Fellow List(name,reg.No.) Population ************************//

    function get_fgm_List_Data($yr, $ActionOnStatus) {

        $this->db->select("count(fml.stud_reg_no) as no_reschedule,d.final_last_member,DATE_FORMAT(pc.dsc_approval_date,'%d/%m/%Y %r') as appv_dt,DATE_FORMAT(pc.dsc_forwarding_by_HOD_time,'%d/%m/%Y %r') as fd_dt,DATE_FORMAT(st.admn_date,'%d %b %Y')as admn_date,DATE_FORMAT(pc.fellow_eligibility_date,'%d/%m/%Y %r') as dt,dept4.name as user_dept_name ,concat_ws(' ',x.salutation,x.first_name,x.middle_name,x.last_name) as guide_name ,concat_ws(' ',y.salutation,y.first_name,y.middle_name,y.last_name) as co_guide_name,concat_ws(' ',z.salutation,z.first_name,z.middle_name,z.last_name) as fellow_name, fm.stud_reg_no as admn_no,fm.guide,fm.co_guide,d.chairperson,d.first_member_same_dept,d.second_member_same_dept,d.first_member_sis_dept,d.second_member_sis_dept,d.third_member_sis_dept,d.first_sister_dept,d.second_sister_dept,d.third_sister_dept,d.final_last_member,dept1.name as sis_dept_name1 ,dept2.name as sis_dept_name2,dept3.name as sis_dept_name3,pc.dsc_meeting_scheduled,pc.dsc_meeting_org,DATE_FORMAT(pc.dsc_meeting_date,'%d %b %Y') as  dsc_meeting_date,pc.fellow_eligibility,pc.dsc_mom_upload,pc.dsc_mom,pc.dsc_approval,pc.dsc_forwarding_by_HOD,pc.thesis_title,pc.remark_by_HOD,pc.qualifying_degree_type,pc.qualifying_degree", false);
        $this->db->from('fellow_master fm');
        $this->db->join('fellow_dsc d', 'd.stud_reg_no=fm.stud_reg_no');
        $this->db->join('fellow_dsc_process_control pc', 'pc.stud_reg_no=d.stud_reg_no and fellow_eligibility="' . $ActionOnStatus . '"  and  fm.processing_yr="' . $yr . '"');
        $this->db->join('`stu_details` st', 'st.admn_no=fm.stud_reg_no');
        $this->db->join('user_details x', 'x.id=fm.guide', 'left');
        $this->db->join('user_details y', 'y.id=fm.co_guide', 'left');
        //$this->db->join('user_details z', 'z.id=fm.stud_reg_no','left');
        $this->db->join('user_details z', 'z.id=fm.stud_reg_no and z.dept_id="' . $this->session->userdata('dept_id') . '"');
        $this->db->join('departments dept1', 'dept1.id=d.first_sister_dept', 'left');
        $this->db->join('departments dept2', 'dept2.id=d.second_sister_dept', 'left');
        $this->db->join('departments dept3', 'dept3.id=d.third_sister_dept', 'left');
        $this->db->join('departments dept4', 'dept4.id=z.dept_id', 'left');
        $this->db->join('fellow_dsc_meeting_log fml', 'fml.stud_reg_no = pc.stud_reg_no  group by fm.stud_reg_no', 'left');
        $this->db->order_by('admn_date');
        $query = $this->db->get();
        //    echo  $this->db->last_query();
        if ($query->num_rows() > 0)
            return $query->result_array();
        else {
            return false;
        }
    }

    //  new dsc to be  made & prrocessed
    function get_fgm_List_Data_new($yr) {
        /*  $this->db->select("dept4.name as user_dept_name ,concat_ws(' ',x.salutation,x.first_name,x.middle_name,x.last_name) as guide_name ,concat_ws(' ',y.salutation,y.first_name,y.middle_name,y.last_name) as co_guide_name,"
          . "concat_ws(' ',z.salutation,z.first_name,z.middle_name,z.last_name) as fellow_name, fm.stud_reg_no as admn_no,fm.guide,fm.co_guide,"
          . "null as chairperson,null as first_member_same_dept,null as second_member_same_dept,null as first_member_sis_dept,null as second_member_sis_dept,"
          . "null as third_member_sis_dept,null as first_sister_dept,"
          . "null as second_sister_dept,null as third_sister_dept,null as final_last_member,"
          . "null as sis_dept_name1,null as sis_dept_name2,null as sis_dept_name3,"
          . "'N' as  dsc_meeting_scheduled,'N' as dsc_meeting_org,null as dsc_meeting_date,'N' as fellow_eligibility,'N' as dsc_mom_upload,null as dsc_mom,"
          . "'N' as dsc_approval,'N' as dsc_forwarding_by_HOD,null as thesis_title,null as remark_by_HOD,null as qualifying_degree_type,null as qualifying_degree", false);
          $this->db->from('fellow_master fm');
          $this->db->join('user_details x', 'x.id=fm.guide' );
          $this->db->join('user_details y', 'y.id=fm.co_guide');
          $this->db->join('user_details z', 'z.id=fm.stud_reg_no');
          $this->db->join('departments dept4', 'dept4.id=z.dept_id  and  fm.processing_yr="'.$yr.'"');
         */
        $sql = " select A.* from(
                                 SELECT DATE_FORMAT(st.admn_date,'%d %b %Y')as admn_date,null as dt,null as appv_dt,null as fd_dt,'0' as no_reschedule,dept4.name as user_dept_name, concat_ws(' ', x.salutation, x.first_name, x.middle_name, x.last_name) as guide_name,
                                 concat_ws(' ', y.salutation, y.first_name, y.middle_name, y.last_name) as co_guide_name,
                                 concat_ws(' ', z.salutation, z.first_name, z.middle_name, z.last_name) as fellow_name, fm.stud_reg_no as admn_no, fm.guide, fm.co_guide,
                                 null as chairperson, null as first_member_same_dept, null as second_member_same_dept, null as first_member_sis_dept, null as second_member_sis_dept,
                                 null as third_member_sis_dept, null as first_sister_dept, null as second_sister_dept, null as third_sister_dept, null as final_last_member,
                                 null as sis_dept_name1, null as sis_dept_name2, null as sis_dept_name3, 'N' as dsc_meeting_scheduled, 'N' as dsc_meeting_org, null as dsc_meeting_date,
                                 'N' as fellow_eligibility, 'N' as dsc_mom_upload, null as dsc_mom, 'N' as dsc_approval, 'N' as dsc_forwarding_by_HOD, null as thesis_title,
                                 null as remark_by_HOD, 1 as qualifying_degree_type, 1 as qualifying_degree
                                 FROM (`fellow_master` fm)
                                 JOIN `stu_details` st ON `st`.`admn_no` = `fm`.`stud_reg_no`
                                 left JOIN `user_details` x ON `x`.`id`=`fm`.`guide`
                                 left JOIN `user_details` y ON `y`.`id`=`fm`.`co_guide`
                                 JOIN `user_details` z ON `z`.`id`=`fm`.`stud_reg_no`  and z.dept_id=?
								 JOIN `departments` dept4 ON `dept4`.`id`=`z`.`dept_id` and fm.guide_assigned_status=? and
                                 fm.processing_yr=?  order by admn_date)A
                                 where not exists(select `pc`.`stud_reg_no` from `fellow_dsc_process_control` pc where `pc`.`stud_reg_no` = A.`admn_no` ) ";
        $query = $this->db->query($sql, array($this->session->userdata('dept_id'), "Y", $yr));

        //       echo  $this->db->last_query();
        if ($query->num_rows() > 0)
            return $query->result_array();
        else {
            return false;
        }
    }

    function get_JRF_subjects($id) {
        $this->db->select("A.subj_id,A.subj_name,A.offering_dept,dept2.name", true);
        $this->db->from('fellow_sub_jrf_master A');
        $this->db->join('departments dept2', 'dept2.id=A.offering_dept and A.status="Y" and  A.offering_dept="' . $id . '"');
        $query = $this->db->get();
        //echo $this->db->last_query();
        if ($query->num_rows() > 0)
            return $query->result_array();
    }

    function get_JRF_deptt() {
        $this->db->select("A.offering_dept,dept2.name", true);
        $this->db->from('fellow_sub_jrf_master A');
        //   $this->db->where('status','Y');
        $this->db->join('departments dept2', 'dept2.id=A.offering_dept and A.status="Y"', 'left');
        $query = $this->db->get();
        if ($query->num_rows() > 0)
            return $query->result_array();
    }

    function get_fgm_Data_by_param($id = "") {

        $this->db->select("concat_ws(' ',x.salutation,x.first_name,x.middle_name,x.last_name) as guide_name ,"
                . "concat_ws(' ',y.salutation,y.first_name,y.middle_name,y.last_name) as co_guide_name,"
                . "concat_ws(' ',z.salutation,z.first_name,z.middle_name,z.last_name) as fellow_name,"
                . "concat_ws(' ',p.salutation,p.first_name,p.middle_name,p.last_name) as last_member,"
                . "dept1.name as sis_dept_name1 ,dept2.name as sis_dept_name2,dept3.name as sis_dept_name3,"
                . "concat_ws(' ',j.salutation,j.first_name,j.middle_name,j.last_name) as f_m_same_dept,concat_ws(' ',k.salutation,k.first_name,"
                . "k.middle_name,k.last_name) as s_m_same_dept ,concat_ws(' ',l.salutation,l.first_name,l.middle_name,l.last_name) as f_m_other_dept,"
                . "concat_ws(' ',m.salutation,m.first_name,m.middle_name,m.last_name) as s_m_other_dept,concat_ws(' ',n.salutation,n.first_name,"
                . "n.middle_name,n.last_name) as th_m_other_dept,concat_ws(' ',o.salutation,o.first_name,o.middle_name,o.last_name) as chpname", false);
        $this->db->from('fellow_master fm');
        $this->db->join('fellow_dsc d', 'd.stud_reg_no=fm.stud_reg_no and d.stud_reg_no="' . $id . '" ');
        $this->db->join('user_details x', 'x.id=fm.guide', 'left');
        $this->db->join('user_details y', 'y.id=fm.co_guide', 'left');
        $this->db->join('user_details z', 'z.id=fm.stud_reg_no', 'left');
        $this->db->join('user_details j', 'j.id=d.first_member_same_dept', 'left');
        $this->db->join('user_details k', 'k.id=d.second_member_same_dept', 'left');
        $this->db->join('user_details l', 'l.id=d.first_member_sis_dept', 'left');
        $this->db->join('user_details m', 'm.id=d.second_member_sis_dept', 'left');
        $this->db->join('user_details n', 'n.id=d.third_member_sis_dept', 'left');
        $this->db->join('user_details o', 'o.id=d.chairperson', 'left');
        $this->db->join('user_details p', 'p.id=d.final_last_member', 'left');
        $this->db->join('departments dept1', 'dept1.id=d.first_sister_dept', 'left');
        $this->db->join('departments dept2', 'dept2.id=d.second_sister_dept', 'left');
        $this->db->join('departments dept3', 'dept3.id=d.third_sister_dept', 'left');
        $query = $this->db->get();

        if ($query->num_rows() > 0)
            return $query->result_array();
        else {
            return array('result' => 'Failed', 'error' => 'No Record Found');
        }
    }

    function show_subjects() {
        if ($this->input->post('field') == "") {
            $sql = "select A.type,d.name as dept,course,COALESCE(b.name,'N/A') as branch,A.semester,COALESCE(sb.name,sjm.subj_name)  as subject,sb.subject_id from (select  type,subject_id,dept_id,course,branch,semester  from  fellow_dsc_subject where stud_reg_no =?)A "
                    . "left join subjects sb on A.subject_id=sb.id "
                    . "left join departments d on d.id=A.dept_id "
                    . "left join branches b on b.id=A.branch "
                    . "left join fellow_sub_jrf_master sjm on sjm.subj_id=A.subject_id "
                    . " order by d.name,sb.name";
        } else {
            $sql = "select A.subject_id as id ,sb.name  from (select  subject_id from  fellow_dsc_subject   where stud_reg_no =?)A left join subjects sb on A.subject_id=sb.id ";
        }
        $query = $this->db->query($sql, $this->input->post('stud_reg_no'));
        // echo $this->db->last_query();
        if ($query->num_rows() > 0)
            return $query->result_array();
        else
            return array('result' => 'Failed', 'error' => 'No Subject Chosen');
    }

    function get_full_fgm_Data_by_param($id = "") {

        $this->db->select("count(sb.stud_reg_no) as No_sub, fpdc.thesis_title,fm.guide_assigned_status,DATE_FORMAT(fm.created,'%d %b %Y %r') as assign_date,fpdc.fellow_eligibility_remark,fpdc.dsc_formation,DATE_FORMAT(fpdc.dsc_formation_time,'%d %b %Y %r') as  dsc_formation_time,fpdc.dsc_meeting_scheduled,dsc_meeting_org,"
                . "DATE_FORMAT(fpdc.dsc_forwarding_by_HOD_time,'%d %b %Y %r') as  dsc_forwarding_by_HOD_time,"
                . "DATE_FORMAT(fpdc.dsc_course_chosen_date,'%d %b %Y %r') as  dsc_course_chosen_date,fpdc.dsc_course_chosen,"
                . "DATE_FORMAT(fpdc.dsc_meeting_date,'%d %b %Y')as dsc_meeting_date,"
                . "fpdc.dsc_forwarding_by_HOD,DATE_FORMAT(fpdc.dsc_approval_date,'%d %b %Y %r') as dsc_approval_date,fpdc.dsc_approval,"
                . "DATE_FORMAT(fpdc.dsc_mom_upload_date,'%d %b %Y %r') as dsc_mom_upload_date,fpdc.dsc_mom_upload,fpdc.dsc_mom,"
                . "DATE_FORMAT(fpdc.fellow_eligibility_date,'%d %b %Y %r') as fellow_eligibility_date,fpdc.fellow_eligibility,"
                . "concat_ws(' ',x.salutation,x.first_name,x.middle_name,x.last_name) as guide_name ,"
                . "concat_ws(' ',y.salutation,y.first_name,y.middle_name,y.last_name) as co_guide_name,"
                . "concat_ws(' ',z.salutation,z.first_name,z.middle_name,z.last_name) as fellow_name,"
                . "concat_ws(' ',p.salutation,p.first_name,p.middle_name,p.last_name) as last_member,"
                . "dept1.name as sis_dept_name1 ,dept2.name as sis_dept_name2,dept3.name as sis_dept_name3,"
                . "concat_ws(' ',j.salutation,j.first_name,j.middle_name,j.last_name) as f_m_same_dept,concat_ws(' ',k.salutation,k.first_name,"
                . "k.middle_name,k.last_name) as s_m_same_dept ,concat_ws(' ',l.salutation,l.first_name,l.middle_name,l.last_name) as f_m_other_dept,"
                . "concat_ws(' ',m.salutation,m.first_name,m.middle_name,m.last_name) as s_m_other_dept,concat_ws(' ',n.salutation,n.first_name,"
                . "n.middle_name,n.last_name) as th_m_other_dept,concat_ws(' ',o.salutation,o.first_name,o.middle_name,o.last_name) as chpname", false);
        $this->db->from('fellow_master fm');
        $this->db->join('fellow_dsc d', 'd.stud_reg_no=fm.stud_reg_no and d.stud_reg_no="' . $id . '" ');
        $this->db->join('fellow_dsc_process_control fpdc', 'fpdc.stud_reg_no=d.stud_reg_no', 'left');
        $this->db->join('user_details x', 'x.id=fm.guide', 'left');
        $this->db->join('user_details y', 'y.id=fm.co_guide', 'left');
        $this->db->join('user_details z', 'z.id=fm.stud_reg_no', 'left');
        $this->db->join('user_details j', 'j.id=d.first_member_same_dept', 'left');
        $this->db->join('user_details k', 'k.id=d.second_member_same_dept', 'left');
        $this->db->join('user_details l', 'l.id=d.first_member_sis_dept', 'left');
        $this->db->join('user_details m', 'm.id=d.second_member_sis_dept', 'left');
        $this->db->join('user_details n', 'n.id=d.third_member_sis_dept', 'left');
        $this->db->join('user_details o', 'o.id=d.chairperson', 'left');
        $this->db->join('user_details p', 'p.id=d.final_last_member', 'left');
        $this->db->join('departments dept1', 'dept1.id=d.first_sister_dept', 'left');
        $this->db->join('departments dept2', 'dept2.id=d.second_sister_dept', 'left');
        $this->db->join('departments dept3', 'dept3.id=d.third_sister_dept', 'left');
        $this->db->join('fellow_dsc_subject sb', 'sb.stud_reg_no=d.stud_reg_no', 'left');


        $query = $this->db->get();
        // echo $this->db->last_query();
        if ($query->num_rows() > 0)
            return $query->result_array();
        else {
            return array('result' => 'Failed', 'error' => 'No Record Found');
        }
    }

    function getScheduleHistoryList_by_param($id = "") {
        $this->db->select("dsc_meeting_org,dsc_meeting_scheduled,schedule_remark,DATE_FORMAT(dsc_meeting_date,'%d %b %Y') as  dsc_meeting_date,fellow_eligibility,fellow_eligibility_remark,dsc_mom_upload,dsc_mom,DATE_FORMAT(dsc_mom_upload_date,'%d %b %Y %r') as dsc_mom_upload_date,thesis_title", false);
        $this->db->from('fellow_dsc_meeting_log');
        $this->db->where("stud_reg_no", '' . $id . '');
        $this->db->order_by('created', 'desc');
        $query = $this->db->get();
        // echo $this->db->last_query();
        if ($query->num_rows() > 0)
            return $query->result_array();
        else {
            return array('result' => 'Failed', 'error' => 'No Record Found');
        }
    }

    //******************@Module :Guide List Population ************************//
    /**
     * @desc: Get faculty(short code=>ft) List those are guide to be populate in list box in the form
     *
     * @access	public
     * @param	String	      (faculty(short code=>ft))
     * @param	String	      (department_id)
     * @return	string array  (return Faculty List)
     */
    /* function get_GuideList_by_param($type = '', $dept_id = '') {
      if (isset($type) && isset($dept_id)) {
      $this->db->select('u.id,u.salutation,u.first_name,u.middle_name,u.last_name');
      $this->db->from('emp_basic_details emp');
      $this->db->join('user_details u', 'u.id = emp.emp_no  and emp.auth_id="' . $type . '"  and u.dept_id="' . $dept_id . '"  order by u.first_name');
      $query = $this->db->get();
      if ($query->num_rows() > 0)
      return $query->result_array();
      else
      return FALSE;
      } else
      return FALSE;
      } */

    //******************End of @Module :Guide List Population ************************//

    function get_DeptList_by_param($dept_type) {

        $this->db->select('name,id');
        $this->db->from('departments');
        $this->db->where('type', '' . $dept_type . '');
        $this->db->order_by('name');
        $query = $this->db->get();
        if ($query->num_rows() > 0)
            return $query->result_array();
        else
            return FALSE;
    }

    // Getting Guide & co_guide Mapping Log for  selected YR
    function get_ReAssignList_by_param() {
        if ($this->input->post('stud_reg_no') && $this->input->post('yr')) {
            /*  $sql = "select concat_ws(' ',x.salutation,x.first_name,x.middle_name,x.last_name) as guide_name ,date_format(y.dt,'%d-%m-%Y %r') as dt1 ,x.processing_yr,x.reassign_remark, concat_ws(' ',y.salutation,y.first_name,y.middle_name,y.last_name) as co_guide_name,date_format(x.cdate,'%d-%m-%Y %r') as assigned_date " .
              "from(" .
              " select u.id as guide_id,fmml.stud_reg_no,fmml.old_co_guide as xco_guide,fmml.created as cdate,fmml.processing_yr,fmml.reassign_remark ,u.id,u.salutation,u.first_name,u.middle_name,u.last_name  from fellow_master_mapping_log fmml " .
              " inner join " .
              " user_details u " .
              " on " .
              " u.id = fmml.old_guide  and fmml.stud_reg_no=? and processing_yr=? " .
              " )x  left join " .
              " (select u.id as co_guide_id ,fmml.old_guide as guide_id,fmml.processing_yr,fmml.stud_reg_no,fmml.created as dt,u.salutation,u.first_name,u.middle_name,u.last_name  from fellow_master_mapping_log fmml " .
              " INNER JOIN  " .
              " user_details u " .
              " on " .
              " u.id=fmml.old_co_guide)y " .
              " on x.stud_reg_no=y.stud_reg_no and   x.guide_id=y.guide_id and    x.xco_guide=y.co_guide_id and  x.processing_yr=y.processing_yr    order by y.dt desc ";
             */
            $sql = "
                        select date_format(z.cdate,'%d-%m-%Y %r') as assigned_date ,z.processing_yr,z.reassign_remark,
                       concat_ws(' ',u.salutation,u.first_name,u.middle_name,u.last_name) as guide_name ,
                       concat_ws(' ',u2.salutation,u2.first_name,u2.middle_name,u2.last_name) as co_guide_name
                       from(
                                select fmml.stud_reg_no,fmml.old_guide ,fmml.old_co_guide,fmml.created as cdate,fmml.processing_yr,fmml.reassign_remark
                                from fellow_master_mapping_log fmml where     fmml.stud_reg_no=?  and fmml.processing_yr=? )z
                       left join  user_details u  on   u.id = z.old_guide
                       left join  user_details u2 on   u2.id = z.old_co_guide
                       order by z.cdate desc";


            // $this->db->protect_identifiers(arrray('user_details','fellow_master_mapping_log'));
            $query = $this->db->query($sql, array($this->input->post('stud_reg_no'), $this->input->post('yr')));
            // echo $this->db->last_query();
            if ($query->num_rows() > 0) {
                return $query->result_array();
            } else {
                return array('result' => 'Failed', 'error' => 'No Record Found');
            }
        } else {
            return array('result' => 'Failed', 'error' => "Student Registration No. not provided");
        }
    }

    //******************@Module :fellow_guide_mapping ************************//
    /**
     * @desc:Storing the Mapped fellows & guides and or co-guide combination(accomplished by HOD), into database.
     *
     * @access	public
     * @param	$_POST	(form's element guide,co-guide,target degree,fellow registration no.)
     * @return	string  (return message  after insertion operation)
     */
    /* public function save_fw_gd_mapping() {

      $data = array(
      'stud_reg_no' => $this->input->post('stud_reg_no'),
      'guide' => $this->input->post('guide'),
      'co_guide' => $this->input->post('co_guide'),
      'target_degree' => $this->input->post('target_degree'),
      'guide_assigned_status' => "Y",
      'guide_assigned_by' => $this->input->post('guide_assigned_by'),
      'processing_yr'=>$this->input->post('yr')
      );
      //(BAcklog case)
      if((in_array("dept_da5", $this->session->userdata('auth')) &&  $this->input->post('actor') == "dept_da")||(in_array("fic_jrf", $this->session->userdata('auth')) && $this->input->post('actor') == "fic_jrf")) {
      date_default_timezone_set("Asia/Calcutta");
      $data['created']  =  date('Y-m-d H:i:s', strtotime($this->input->post('admn_date') .' +3 days'));   // assuming  max +3 days after yr of admission  (BAcklog case)
      $data['backlog_entry'] = "Y";
      $data['backlog_entry_date'] = date("Y-m-d H:i:s");
      }
      //end
      if ($this->db->insert('fellow_master', $data)) {
      $returntmsg = "success";
      return $returntmsg;
      } else {
      $returntmsg = $this->db->_error_message();
      return $returntmsg;
      }
      } */
    public function save_fw_gd_mapping() {
		// try catch block start @ author: rituraj @dated:17-5-20
	try{
		$this->db->trans_begin(); // // transaction code start @ author: rituraj @dated:17-5-20
		$returntmsg='';
		$affected=null;
        $data = array(
            'stud_reg_no' => $this->input->post('stud_reg_no'),
            'guide' => $this->input->post('guide'),
            'co_guide' => $this->input->post('co_guide')==''? null:$this->input->post('co_guide'), // @dated:17-5-20 @author:ritu raj @desc:co_guide is not compulsory
            'target_degree' => $this->input->post('target_degree'),
            'guide_assigned_status' => "Y",
            'guide_assigned_by' => $this->input->post('guide_assigned_by'),
            'processing_yr' => $this->input->post('yr')
        );
        if ($this->input->post('dept') == 'ext')
            $data['co_guide_type'] = 'E';
        //(BAcklog case)
        if ((in_array("dept_da5", $this->session->userdata('auth')) && $this->input->post('actor') == "dept_da") || (in_array("fic_jrf", $this->session->userdata('auth')) && $this->input->post('actor') == "fic_jrf")) {
            date_default_timezone_set("Asia/Calcutta");
            $data['created'] = date('Y-m-d H:i:s', strtotime($this->input->post('admn_date') . ' +3 days'));   // assuming  max +3 days after yr of admission  (BAcklog case)
            $data['backlog_entry'] = "Y";
            $data['backlog_entry_date'] = date("Y-m-d H:i:s");
        }
        //end

        /**
         * Below code written by shyam for store project Guide module
         */
           $project_guide_tbl = "project_guide";
       // if ($this->db->insert('fellow_master', $data)) {//shyam inject code
	       $this->db->insert('fellow_master', $data);
	       $affected[] = $this->db->affected_rows();
            $return_data = $this->get_name_from_id("user_details", "dept_id", "id", $this->input->post('stud_reg_no'));

            $insertTable = array();

            $insertTable["academic_year"] = $this->input->post('yr') . "-" . ($this->input->post('yr') + 1);
            $insertTable["guide"] = $this->input->post('guide');
            $insertTable["co_guide"] =  $this->input->post('co_guide')==''?null:$this->input->post('co_guide'); // @dated:17-5-20 @author:ritu raj @desc:co_guide is not compulsory
            $insertTable["admn_no"] = $this->input->post('stud_reg_no');
            $insertTable["type"] = "RS";
            $insertTable["rs_type"] = null;
            $insertTable["sole_shared"] = ($this->input->post('co_guide') == "" ? "sole" : "shared");
            $insertTable["department"] = $return_data['dept_id'];
            $insertTable["course"] = "JRF";
            $insertTable["branch"] = "JRF";
            $insertTable["status"] = "registered";
            $insertTable["created_by"] = $this->session->userdata('id');
            $insertTable["created_date"] = date("Y-m-d H:i:s");
            $this->db->set($insertTable);
            $this->db->insert($project_guide_tbl);
			 $affected[] = $this->db->affected_rows();
			
			  if (in_array(0, $affected) || ($this->db->trans_status() === FALSE)) {                    
                    $this->db->trans_rollback();
                    $returntmsg = "failed";
                } else {
                    $returntmsg = "success";
                    $this->db->trans_commit();
                }
				 if($returntmsg == "failed") {  echo  $this->db->last_query();  echo $this->db->_error_message(); die();}
			  return $returntmsg;	
			
           } catch (Exception $e) { //echo 'exception generated:'. $e->getMessage(); die();                   		 
		      throw new Exception($e->getMessage() == null ? 'Internal error occurred' : 'error:'.$e->getMessage());
           }
 			
           // commented old  mishra code without transaction
           // $returntmsg = "success";            
        /*} else {
            $returntmsg = $this->db->_error_message();
            return $returntmsg;
        }*/
    }

    //******************End of @Module :fellow_guide_mapping ************************//
    //******************@Module :fellow_guide_mapping ************************//
    /**
     * @desc:Modify the Mapped fellows & guides and or co-guide combination(accomplished by HOD), and storing into database.
     *
     * @access	public
     * @param	$_POST	(form's element guide,co-guide,target degree,fellow registration no.)
     * @return	string  (return message  after insertion operation)
     */
    function modify_fw_gd_mapping() {
			// try catch block start @ author: rituraj @dated:17-5-20
	try{
		$this->db->trans_begin(); // // transaction code start @ author: rituraj @dated:17-5-20
		$returntmsg='';
		$affected=null;
        date_default_timezone_set("Asia/Calcutta");
        $returntmsg = "";
        $select = $this->db->select('stud_reg_no,guide as old_guide,co_guide as old_co_guide,reassign_remark,processing_yr')->where(array('stud_reg_no' => $this->input->post('stud_reg_no'), 'processing_yr' => $this->input->post('yr')))->get('fellow_master');
        if ($select->num_rows()) {
            $this->db->trans_start();
            $row = $select->result_array();
            //if (!$this->db->insert('fellow_master_mapping_log', $row[0])){
			   $this->db->insert('fellow_master_mapping_log', $row[0]);
				$affected[] = $this->db->affected_rows();
               // $returntmsg .= $this->db->_error_message() . ",";
			//}

            $data = array(
                'guide' => $this->input->post('guide'),
                'co_guide' => $this->input->post('co_guide')==''?null:$this->input->post('co_guide'), // @dated:17-5-20 @author:ritu raj @desc:co_guide is not compulsory
                'target_degree' => $this->input->post('target_degree'),
                'guide_assigned_status' => "Y",
                'guide_assigned_by' => $this->input->post('guide_assigned_by'),
                'updated_by' => $this->session->userdata('id'),
                'reassign_remark' => $this->input->post('remark') ? $this->input->post('remark') : ""
            );
            //(BAcklog case)
            if ((in_array("dept_da5", $this->session->userdata('auth')) && $this->input->post('actor') == "dept_da") || (in_array("fic_jrf", $this->session->userdata('auth')) && $this->input->post('actor') == "fic_jrf")) {

                $data['updated'] = date('Y-m-d H:i:s', strtotime($this->input->post('admn_date') . ' +3 days')); // assuming  max +3 days after yr of admission  (BAcklog case)
            }
            //end
            $this->db->where('stud_reg_no', $this->input->post('stud_reg_no'));
            //if ($this->db->update('fellow_master', $data)) {
				
                $this->db->update('fellow_master', $data);
                $project_guide_tbl = "project_guide";
               
                $this->db->query('UPDATE `' . $project_guide_tbl . '` SET `is_status`= "Inactive",`modify_by`="' . $this->session->userdata('id') . '" WHERE `admn_no` = "' . $this->input->post('stud_reg_no') . '" and `is_status` = "Active";');
                $affected[] = $this->db->affected_rows();
				$return_data = $this->get_name_from_id("user_details", "dept_id", "id", $this->input->post('stud_reg_no'));

                $insertTable = array();

                $insertTable["academic_year"] = $this->input->post('yr') . "-" . ($this->input->post('yr') + 1);
                $insertTable["guide"] = $this->input->post('guide');
                $insertTable["co_guide"] =$this->input->post('co_guide')==''?null:$this->input->post('co_guide'); // @dated:17-5-20 @author:ritu raj @desc:co_guide is not compulsory
                $insertTable["admn_no"] = $this->input->post('stud_reg_no');
                $insertTable["type"] = "RS";
                $insertTable["rs_type"] = null;
                $insertTable["sole_shared"] = ($this->input->post('co_guide') == "" ? "sole" : "shared");
                $insertTable["department"] = $return_data['dept_id'];
                $insertTable["course"] = "JRF";
                $insertTable["branch"] = "JRF";
                $insertTable["status"] = "registered";
                $insertTable["created_by"] = $this->session->userdata('id');
                $insertTable["created_date"] = date("Y-m-d H:i:s");
                $this->db->set($insertTable);
                $this->db->insert($project_guide_tbl);
				$affected[] = $this->db->affected_rows();
            /*} else {
                $returntmsg .= $this->db->_error_message() . ",";
            }*/

             if (in_array(0, $affected) || ($this->db->trans_status() === FALSE)) {                    
                    $this->db->trans_rollback();
                    $returntmsg = "failed";
                } else {
                    $returntmsg = "success";
                    $this->db->trans_commit();
                }
				 if($returntmsg == "failed") {  echo  $this->db->last_query();  echo $this->db->_error_message(); die();}			     			        
          } else 
                $returntmsg = "Internal Error found. ID not found";
           return $returntmsg;
		
	  } catch (Exception $e) { //echo 'exception generated:'. $e->getMessage(); die();                   		 
		      throw new Exception($e->getMessage() == null ? 'Internal error occurred' : 'error:'.$e->getMessage());
        }
		
        
    }

    public function get_name_from_id($table, $name, $id, $value) {

        $this->db->where($id, $value);
        $this->db->from($table);
        return $this->db->get()->row_array();
    }

    //******************End of @Module :fellow_guide_mapping ************************//
    //******************@Module :DSC formation ************************//
    /**
     * @desc:Storing the DSC prepared by guide against each fellow into database.
     *
     * @access	public
     * @param	$_POST	(form's element guide,co-guide,memeber list,fellow registration no.)
     * @return	string  (return message  after insertion operation)
     */
    public function save_DSC() {
        date_default_timezone_set("Asia/Calcutta");
        $returntmsg = "";

        $data = array(
            'stud_reg_no' => $this->input->post('stud_reg_no'),
            'guide' => $this->input->post('guide'),
            'co_guide' => $this->input->post('co_guide'),
            'chairperson' => $this->input->post('chairperson'),
            'first_member_same_dept' => $this->input->post('first_member_same_dept'),
            'second_member_same_dept' => $this->input->post('second_member_same_dept'),
            'first_sister_dept' => $this->input->post('first_sister_dept'),
            'second_sister_dept' => $this->input->post('second_sister_dept'),
            'third_sister_dept' => $this->input->post('third_sister_dept'),
            'first_member_sis_dept' => $this->input->post('first_member_sis_dept'),
            'second_member_sis_dept' => $this->input->post('second_member_sis_dept'),
            'third_member_sis_dept' => $this->input->post('third_member_sis_dept'),
            'created_by' => $this->input->post('created_by'),
        );
        //(BAcklog case)
        if ((in_array("dept_da5", $this->session->userdata('auth')) && $this->input->post('actor') == "dept_da") || (in_array("fic_jrf", $this->session->userdata('auth')) && $this->input->post('actor') == "fic_jrf")) {

            $data['created'] = date('Y-m-d H:i:s', strtotime($this->input->post('admn_date') . ' +3 days')); // assuming  max +3 days after yr of admission  (BAcklog case)
            $data['final_last_member'] = $this->input->post('last_member');
        }
        //en
        $this->db->trans_start();
        if (!$this->db->insert('fellow_dsc', $data))
            $returntmsg .= $this->db->_error_message() . ",";

        $data2 = array('stud_reg_no' => $this->input->post('stud_reg_no'),
            'dsc_formation' => 'Y'
        );
        if ((in_array("dept_da5", $this->session->userdata('auth')) && $this->input->post('actor') == "dept_da") || (in_array("fic_jrf", $this->session->userdata('auth')) && $this->input->post('actor') == "fic_jrf")) {
            $data2['dsc_formation_time'] = $dsc_formation_time = date('Y-m-d H:i:s', strtotime($this->input->post('admn_date') . ' +3 days')); // assuming  max +3 days after yr of admission  (BAcklog case)
            $data2['dsc_forwarding_by_HOD'] = 'Y';
            $data2['dsc_forwarding_by_HOD_time'] = $dsc_formation_time;
            $data2['dsc_forwarding_by'] = $this->session->userdata('id');
            $data2['dsc_approval'] = 'Y';
            $data2['dsc_approval_date'] = $dsc_formation_time;
            $data2['dsc_approved_by'] = $this->session->userdata('id');
        } else {
            $data2['dsc_formation_time'] = date('Y-m-d H:i:s');
        }


        if (!$this->db->insert('fellow_dsc_process_control', $data2))
            $returntmsg .= $this->db->_error_message() . ",";

        $this->db->trans_complete();

        if ($this->db->trans_status() === FALSE) {
            $returntmsg .=$this->db->_error_message();
        } else {
            $returntmsg = "success";
        }
        return $returntmsg;
    }

    function update_DSC_meeting($data, $id) {
        if ($this->db->update('fellow_dsc_process_control', $data, array('stud_reg_no' => $id))) {
            $returntmsg = "success";
            return $returntmsg;
        } else {
            $returntmsg = $this->db->_error_message();
            return $returntmsg;
        }
    }

    public function save_DSC_meeting_log() {
        $select = $this->db->select('stud_reg_no,dsc_meeting_org,dsc_meeting_scheduled,dsc_meeting_date,dsc_mom_upload,dsc_mom,dsc_mom_upload_date,fellow_eligibility,fellow_eligibility_remark,schedule_remark')->where('stud_reg_no', $this->input->post('id'))->get('fellow_dsc_process_control');
        if ($select->num_rows()) {
            $row = $select->result_array();
            // $data=  array_merge($row[0],array('schedule_remark'=>$this->input->post('remark')));
            $insertOpr = $this->db->insert('fellow_dsc_meeting_log', $row[0]);
        }
        if ($insertOpr) {
            $returntmsg = "success";
            return $returntmsg;
        } else {
            $returntmsg = $this->db->_error_message();
            return $returntmsg;
        }
    }

    public function DSC_save_eligibility_log() {
        $select = $this->db->select('stud_reg_no,dsc_meeting_org,dsc_meeting_scheduled,dsc_meeting_date,dsc_mom_upload,dsc_mom,fellow_eligibility,fellow_eligibility_remark,schedule_remark,fellow_eligibility_by,thesis_title,qualifying_degree,qualifying_degree_type')->where('stud_reg_no', $this->input->post('stud_reg_no'))->get('fellow_dsc_process_control');
        if ($select->num_rows()) {
            $row = $select->result_array();
            if ($row[0]['fellow_eligibility'] == 'F')
                $insertOpr = $this->db->insert('fellow_dsc_meeting_log', $row[0]);
            else
                $insertOpr = "NotRequire";
        }
        if ($insertOpr) {
            $returntmsg = "success";
            return $returntmsg;
        } else {
            $returntmsg = $this->db->_error_message();
            return $returntmsg;
        }
    }

    public function DSC_save_eligibility() {
        date_default_timezone_set("Asia/Calcutta");
        //  echo $this->input->post('actor');
        $returntmsg = "";
        $select = $this->db->select('stud_reg_no,dsc_meeting_org,dsc_meeting_scheduled,dsc_meeting_date,dsc_mom_upload,dsc_mom,fellow_eligibility,fellow_eligibility_remark,schedule_remark,fellow_eligibility_by,thesis_title,qualifying_degree,qualifying_degree_type')->where('stud_reg_no', $this->input->post('stud_reg_no'))->get('fellow_dsc_process_control');
        if ($select->num_rows()) {
            $this->db->trans_start();
            $row = $select->result_array();
            if ($row[0]['fellow_eligibility'] == 'F') {
                if ((in_array("dept_da5", $this->session->userdata('auth')) && $this->input->post('actor') == "dept_da") || (in_array("fic_jrf", $this->session->userdata('auth')) && $this->input->post('actor') == "fic_jrf")) {
                    $row[0]['created'] = date('Y-m-d H:i:s', strtotime($this->input->post('admn_date')));
                }
                if (!$insertOpr = $this->db->insert('fellow_dsc_meeting_log', $row[0]))
                    $returntmsg .= $this->db->_error_message() . ",";
            }


            $data = array(
                'thesis_title' => $this->input->post('thesis_title'),
                'qualifying_degree' => $this->input->post('qfd'),
                'qualifying_degree_type' => $this->input->post('admissType'),
                'fellow_eligibility_remark' => $this->input->post('eligibleStatus') == "F" ? $this->input->post('remark') : "",
                'fellow_eligibility' => $this->input->post('eligibleStatus') == "Y" ? "Y" : "F",
                'fellow_eligibility_by' => $this->input->post('logged_user'),
                'fellow_eligibility_gd' => $this->input->post('eligibleStatus') == "Y" ? "Y" : "F",
                'fellow_eligibility_gd_by' => $this->input->post('logged_user'),
            );
            if ((in_array("dept_da5", $this->session->userdata('auth')) && $this->input->post('actor') == "dept_da") || (in_array("fic_jrf", $this->session->userdata('auth')) && $this->input->post('actor') == "fic_jrf")) {
                $data['fellow_eligibility_date'] = date('Y-m-d H:i:s', strtotime($this->input->post('admn_date')));
                $data['fellow_eligibility_gd_date'] = date('Y-m-d H:i:s', strtotime($this->input->post('admn_date')));
            } else {
                $data['fellow_eligibility_date'] = date('Y-m-d H:i:s');
                $data['fellow_eligibility_gd_date'] = date('Y-m-d H:i:s');
            }
            $this->db->where('stud_reg_no', $this->input->post('stud_reg_no'));
            if (!$this->db->update('fellow_dsc_process_control', $data)) {
                $returntmsg .= $this->db->_error_message() . ",";
            }

            $this->db->trans_complete();
            if ($this->db->trans_status() === FALSE) {
                $returntmsg .=$this->db->_error_message();
                throw new Exception($this->db->_error_message());
            } else {
                $returntmsg = "success";
            }
        } else {
            $returntmsg = " Error Occured.No Id found";
        }
        return $returntmsg;
    }

    public function save_mom_upload($name, $stud_reg_no, $entry_date) {
        date_default_timezone_set("Asia/Calcutta");
        //      echo'entry'. $entry_date;
        $data = array(
            'dsc_mom_upload' => "Y",
            'dsc_meeting_org' => "Y",
            'dsc_mom_upload_date' => date('Y-m-d H:i:s', strtotime($entry_date)),
            'dsc_mom' => $name,
        );

        if ($this->db->update('fellow_dsc_process_control', $data, array('stud_reg_no' => $stud_reg_no))) {
            $returntmsg = "success";
            return $returntmsg;
        } else {
            $returntmsg = $this->db->_error_message();
            return $returntmsg;
        }
    }

    //******************End of @Module :DSC formation ************************//



    public function DSC_save_HodAction_log() {
        $select = $this->db->select('stud_reg_no,remark_by_HOD,dsc_forwarding_by_HOD,dsc_forwarding_by_HOD_time,dsc_forwarding_by')->where('stud_reg_no', $this->input->post('stud_reg_no'))->get('fellow_dsc_process_control');
        if ($select->num_rows()) {
            $row = $select->result_array();
            if ($row[0]['dsc_forwarding_by_HOD'] == 'R')
                $insertOpr = $this->db->insert('fellow_dsc_hodaction_log', $row[0]);
            else
                $insertOpr = "NotRequire";
        }
        if ($insertOpr) {
            $returntmsg = "success";
            return $returntmsg;
        } else {
            $returntmsg = $this->db->_error_message();
            return $returntmsg;
        }
    }

    public function DSC_save_HodAction() {
        date_default_timezone_set("Asia/Calcutta");
        $data = array(
            'remark_by_HOD' => $this->input->post('remark') ? $this->input->post('remark') : "",
            'dsc_forwarding_by_HOD_time' => date('Y-m-d H:i:s'),
            'dsc_forwarding_by_HOD' => ($this->input->post('action') == 'reject' ? "R" : "Y"),
            'dsc_forwarding_by' => $this->input->post('logged_user')
        );
        $this->db->where('stud_reg_no', $this->input->post('stud_reg_no'));
        if ($this->db->update('fellow_dsc_process_control', $data)) {
            $returntmsg = "success";
            return $returntmsg;
        } else {
            $returntmsg = $this->db->_error_message();
            return $returntmsg;
        }
    }

    public function DSC_save_ADARAction_log() {
        $select = $this->db->select('stud_reg_no,remark_by_ADAR,dsc_approval,dsc_approval_date,dsc_approved_by')->where('stud_reg_no', $this->input->post('stud_reg_no'))->get('fellow_dsc_process_control');
        if ($select->num_rows()) {
            $row = $select->result_array();
            if ($row[0]['dsc_approval'] == 'R')
                $insertOpr = $this->db->insert('fellow_dsc_adaraction_log', $row[0]);
            else
                $insertOpr = "NotRequire";
        }
        if ($insertOpr) {
            $returntmsg = "success";
            return $returntmsg;
        } else {
            $returntmsg = $this->db->_error_message();
            return $returntmsg;
        }
    }

    public function DSC_save_ADARAction() {
        date_default_timezone_set("Asia/Calcutta");
        $data = array(
            'remark_by_ADAR' => $this->input->post('remark') ? $this->input->post('remark') : "",
            'dsc_approval_date' => date('Y-m-d H:i:s'),
            'dsc_approval' => ($this->input->post('action') == 'reject' ? "R" : "Y"),
            'dsc_approved_by' => $this->input->post('logged_user')
        );
        $this->db->where('stud_reg_no', $this->input->post('stud_reg_no'));
        if ($this->db->update('fellow_dsc_process_control', $data)) {
            if ($this->input->post('action') == 'approve') {
                $data1 = array('final_last_member' => $this->input->post('last_member'));
                $this->db->where('stud_reg_no', $this->input->post('stud_reg_no'));
                $this->db->update('fellow_dsc', $data1);
            }
            $returntmsg = "success";
            return $returntmsg;
        } else {
            $returntmsg = $this->db->_error_message();
            return $returntmsg;
        }
    }

    //******************@Module :DSC modification ************************//
    /**
     * @desc: Modification of DSC prepared by guide against each fellow into database  And to be done by guide himself.
     *
     * @access	public
     * @param	$_POST	(form's element guide,co-guide,memeber list,fellow registration no.)
     * @return	string  (return message  after insertion operation)
     */
    public function modify_DSC() {
        date_default_timezone_set("Asia/Calcutta");
        $returntmsg = "";
        $select = $this->db->select('stud_reg_no,chairperson,first_member_same_dept,second_member_same_dept,first_sister_dept ,second_sister_dept,third_sister_dept,first_member_sis_dept,second_member_sis_dept,third_member_sis_dept,remark,created')->where('stud_reg_no', $this->input->post('stud_reg_no'))->get('fellow_dsc');
        if ($select->num_rows()) {
            $this->db->trans_start();
            $row = $select->result_array();
            if (!$this->db->insert('fellow_dsc_log', $row[0]))
                $returntmsg .= $this->db->_error_message() . ",";

            $data = array(
                'chairperson' => $this->input->post('chairperson'),
                'first_member_same_dept' => $this->input->post('first_member_same_dept'),
                'second_member_same_dept' => $this->input->post('second_member_same_dept'),
                'first_sister_dept' => $this->input->post('first_sister_dept'),
                'second_sister_dept' => $this->input->post('second_sister_dept'),
                'third_sister_dept' => $this->input->post('third_sister_dept'),
                'first_member_sis_dept' => $this->input->post('first_member_sis_dept'),
                'second_member_sis_dept' => $this->input->post('second_member_sis_dept'),
                'third_member_sis_dept' => $this->input->post('third_member_sis_dept'),
                'updated_by' => $this->input->post('created_by'),
                'remark' => $this->input->post('remark') ? $this->input->post('remark') : "",
            );
            //(BAcklog case)
            if ((in_array("dept_da5", $this->session->userdata('auth')) && $this->input->post('actor') == "dept_da") || (in_array("fic_jrf", $this->session->userdata('auth')) && $this->input->post('actor') == "fic_jrf")) {
                //echo  $this->input->post('last_member');
                $data['updated'] = date('Y-m-d H:i:s', strtotime($this->input->post('admn_date') . ' +3 days')); // assuming  max +3 days after yr of admission  (BAcklog case)
                $data['final_last_member'] = $this->input->post('last_member');
            }
            //end

            $this->db->where('stud_reg_no', $this->input->post('stud_reg_no'));
            if (!$this->db->update('fellow_dsc', $data))
                $returntmsg .= $this->db->_error_message() . ",";

            $data2 = array();
            if ((in_array("dept_da5", $this->session->userdata('auth')) && $this->input->post('actor') == "dept_da") || (in_array("fic_jrf", $this->session->userdata('auth')) && $this->input->post('actor') == "fic_jrf")) {
                $data2['dsc_formation_time'] = $dsc_formation_time = date('Y-m-d H:i:s', strtotime($this->input->post('admn_date') . ' +3 days')); // assuming  max +3 days after yr of admission  (BAcklog case)
                $data2['dsc_forwarding_by_HOD'] = 'Y';
                $data2['dsc_forwarding_by_HOD_time'] = $dsc_formation_time;
                $data2['dsc_forwarding_by'] = $this->session->userdata('id');
                $data2['dsc_approval'] = 'Y';
                $data2['dsc_approval_date'] = $dsc_formation_time;
                $data2['dsc_approved_by'] = $this->session->userdata('id');
            } else {
                $data2['dsc_formation_time'] = date('Y-m-d H:i:s');
            }
            $this->db->where('stud_reg_no', $this->input->post('stud_reg_no'));
            if (!$this->db->update('fellow_dsc_process_control', $data2))
                $returntmsg .= $this->db->_error_message() . ",";

            $this->db->trans_complete();
            if ($this->db->trans_status() === FALSE) {
                $returntmsg .=$this->db->_error_message();
            } else {
                $returntmsg = "success";
            }
        } else {
            $returntmsg .="Internal Error,Id not found";
        }
        return $returntmsg;
    }

    //******************End  ************************//



    public function DSC_save_course() {
        date_default_timezone_set("Asia/Calcutta");
        $returntmsg = "";
        $i = 0;
        if ($this->input->post('type')) {
            foreach ($this->input->post('type') as $value) {
                $jrf_sub[] = $value;
            }
        }
        //print_r($jrf_sub);
        foreach ($this->input->post('subjects') as $key => $value) {
            if ($jrf_sub[$i] == "pg") {
                $data[] = array('subject_id' => $value, 'stud_reg_no' => $this->input->post('stud_reg_no'), 'dept_id' => $this->input->post('dept_id'), 'type' => $jrf_sub[$i],
                    'course' => $this->input->post('course'), 'branch' => $this->input->post('branch'), 'semester' => $this->input->post('semester'),
                    'passing' => $this->input->post('passing'), 'grp' => $this->input->post('group'));
            } else if ($jrf_sub[$i] == "jrf") {
                $data[] = array('subject_id' => $value, 'stud_reg_no' => $this->input->post('stud_reg_no'), 'dept_id' => $this->input->post('jrf_dept_id'), 'type' => $jrf_sub[$i], 'course' => null, 'branch' => null, 'semester' => null,
                    'passing' => null, 'grp' => null);
            }
            ++$i;
        }
        // print_r($data);
        $this->db->trans_start();
        /* if($this->input->post('prev_chosen_sub')!=""){
          $select = $this->db->select('*')->where('stud_reg_no', $this->input->post('stud_reg_no'))->get('fellow_dsc_subject');
          if ($select->num_rows()){
          $row = $select->result_array();
          if (!$this->db->insert_batch('fellow_dsc_subject_log', $row)) {
          $returntmsg .= $this->db->_error_message(0) . ",";
          }
          if(!$this->db->delete('fellow_dsc_subject', array('stud_reg_no' => $this->input->post('stud_reg_no'))))
          $returntmsg .= $this->db->_error_message() . ",";
          }
          } */
        if (!$this->db->insert_batch('fellow_dsc_subject', $data)) {
            $returntmsg .= $this->db->_error_message() . ",";
        }
        //echo "xxx=".$this->input->post('entry_type'); die();
        if ((in_array("dept_da5", $this->session->userdata('auth')) && $this->input->post('entry_type') == "backlog" ) || (in_array("fic_jrf", $this->session->userdata('auth')) && $this->input->post('entry_type') == "backlog" )) {
            $master_data = array('dsc_course_chosen' => "Y", 'dsc_course_chosen_date' => date('Y-m-d H:i:s', strtotime($this->input->post('admn_date'))));
        } else {
            $master_data = array('dsc_course_chosen' => "Y", 'dsc_course_chosen_date' => date('Y-m-d H:i:s'));
        }
        $this->db->where('stud_reg_no', $this->input->post('stud_reg_no'));
        if (!$this->db->update('fellow_dsc_process_control', $master_data)) {
            $returntmsg .=$this->db->_error_message() . ",";
        }
        $this->db->trans_complete();

        if ($this->db->trans_status() === FALSE) {
            throw new Exception($this->db->_error_message());
            //return $returntmsg;
        } else {
            $returntmsg = "success";
            return $returntmsg;
        }
    }

    function check_mom_upload($id) {
        $result = $this->db->get_where('fellow_dsc_process_control', array('stud_reg_no' => $id))->row();
        if (strtotime($result->dsc_meeting_date) < time()) {
            return true;
        }
        return false;
    }

    function getCourseDurationById($id) {

        $q = $this->db->get_where($this->course, array('id' => $id));
        if ($q->row()->duration)
            $sem = ($q->row()->duration * 2);
        return $sem;
    }

    //******************Fellowship Attendance Modules ************************//
    //******************@Module : Attendance save by dda  ***********************//
    /**
     * @desc:Attendance filling for current month by DDA(Deptt. Dealing Asst.) for each  fellow
     *
     * @access	public
     * @param	n/a
     * @return	JSON(staus msg after insertion)
     */
    public function save_attd_action_by_dda() {
        date_default_timezone_set("Asia/Calcutta");
        $data = array(
            'stud_reg_no' => $this->input->post('stud_reg_no'),
            'target_degree' => $this->input->post('target_degree'),
            'processing_mon' => $this->input->post('processing_mon'),
            'processing_yr' => $this->input->post('processing_yr'),
            'present_yr_fellowship' => $this->input->post('present_yr_fellowship'),
            'days_absent' => $this->input->post('days_absent'),
            'admissible_leave' => $this->input->post('admissible_leave'),
            'leave_balance' => $this->input->post('leave_balance'),
            'recovery_amt' => $this->input->post('recovery_amt'),
            'net_amt_payable' => $this->input->post('net_amt_payable'),
            'attd_forwarding_by_dda' => 'Y',
            'attd_forwarding_by_dda_time' => date('Y-m-d H:i:s'),
            'attd_forwarding_by_dda_id' => $this->session->userdata('id'),
            'dept_id' => $this->session->userdata('dept_id')
        );

        if ($this->db->insert('fellow_bill_master', $data)) {
            $returntmsg = "success";
        } else {
            $returntmsg = $this->db->_error_message();
        }
        return $returntmsg;
    }

    public function save_attd_action_log_by_dda() {
        date_default_timezone_set("Asia/Calcutta");
        $returntmsg = "";
        $select = $this->db->select('stud_reg_no,target_degree,processing_mon,processing_yr,days_absent ,admissible_leave,leave_balance,recovery_amt,net_amt_payable,attd_forwarding_by_dda,attd_forwarding_by_dda_time,attd_forwarding_by_dda_remark')->where(array('stud_reg_no' => $this->input->post('stud_reg_no'), 'processing_mon' => (int) $this->input->post('processing_mon'), 'processing_yr' => $this->input->post('processing_yr')))->get('fellow_bill_master');
        if ($select->num_rows()) {
            $row = $select->result_array();
            $this->db->trans_start();
            if (!$this->db->insert('fellow_attd_log', $row[0]))
                $returntmsg .= $this->db->_error_message() . ",";
            $data = array(
                'days_absent' => $this->input->post('days_absent'),
                'admissible_leave' => $this->input->post('admissible_leave'),
                'leave_balance' => $this->input->post('leave_balance'),
                'recovery_amt' => $this->input->post('recovery_amt'),
                'net_amt_payable' => $this->input->post('net_amt_payable'),
                'attd_forwarding_by_dda' => 'Y',
                'attd_forwarding_by_dda_time' => date('Y-m-d H:i:s'),
                'attd_forwarding_by_dda_remark' => $this->input->post('remark'),
                'attd_forwarding_by_dda_id' => $this->session->userdata('id'),
            );
            $this->db->where('stud_reg_no', $this->input->post('stud_reg_no'));
            if (!$this->db->update('fellow_bill_master', $data))
                $returntmsg .= $this->db->_error_message() . ",";
            $this->db->trans_complete();
            if ($this->db->trans_status() != FALSE) {
                $returntmsg = "success";
            }
        } else {
            $returntmsg .="Internal Error occured.No record found for modification";
        }
        return $returntmsg;
    }

    public function save_attd_action_by_gd() {
        $returntmsg = "";
        date_default_timezone_set("Asia/Calcutta");
        $select = $this->db->select('stud_reg_no,target_degree,processing_mon,processing_yr,attd_approval_by_gd,attd_approval_by_gd_time,attd_approval_by_gd_remark')->where(array('stud_reg_no' => $this->input->post('stud_reg_no'), 'processing_mon' => (int) $this->input->post('processing_mon'), 'processing_yr' => $this->input->post('processing_yr')))->get('fellow_bill_master');
        if ($select->num_rows()) {
            $row = $select->result_array();
            $this->db->trans_start();
            if ($row[0]['attd_approval_by_gd'] == 'C' || $row[0]['attd_approval_by_gd'] == 'S') {
                if (!($this->db->insert('fellow_attd_action_log_gd', $row[0])))
                    $returntmsg .= $this->db->_error_message() . ",";
            }
            $data = array(
                'attd_approval_by_gd' => 'Y',
                'attd_approval_by_gd_time' => date('Y-m-d H:i:s'),
                'attd_approval_by_gd_id' => $this->session->userdata('id')
            );
            $this->db->where(array('stud_reg_no' => $this->input->post('stud_reg_no'), 'processing_mon' => (int) $this->input->post('processing_mon'), 'processing_yr' => $this->input->post('processing_yr')));
            if (!$this->db->update('fellow_bill_master', $data))
                $returntmsg .= $this->db->_error_message() . ",";
//           echo $this->db->last_query();;
            $this->db->trans_complete();
            if ($this->db->trans_status() != FALSE) {
                $returntmsg = "success";
            }
        } else {
            $returntmsg .="Internal Error occured. No record Found";
        }
        return $returntmsg;
    }

    public function save_attd_action_log_by_gd() {
        date_default_timezone_set("Asia/Calcutta");
        $returntmsg = "";
        $select = $this->db->select('stud_reg_no,target_degree,processing_mon,processing_yr,attd_approval_by_gd,attd_approval_by_gd_time,attd_approval_by_gd_remark')->where(array('stud_reg_no' => $this->input->post('stud_reg_no'), 'processing_mon' => (int) $this->input->post('processing_mon'), 'processing_yr' => $this->input->post('processing_yr')))->get('fellow_bill_master');
        if ($select->num_rows()) {
            $row = $select->result_array();
            $this->db->trans_start();
            if ($row[0]['attd_approval_by_gd'] == 'S' || $row[0]['attd_approval_by_gd'] == 'C')
                if (!($this->db->insert('fellow_attd_action_log_gd', $row[0])))
                    $returntmsg .= $this->db->_error_message() . ",";
            $data = array(
                'attd_approval_by_gd' => 'S',
                'attd_approval_by_gd_time' => date('Y-m-d H:i:s'),
                'attd_approval_by_gd_id' => $this->session->userdata('id'),
                'attd_approval_by_gd_remark' => $this->input->post('remark')
            );
            $this->db->where(array('stud_reg_no' => $this->input->post('stud_reg_no'), 'processing_mon' => $this->input->post('processing_mon'), 'processing_yr' => $this->input->post('processing_yr')));
            if (!$this->db->update('fellow_bill_master', $data))
                $returntmsg .= $this->db->_error_message() . ",";

            $this->db->trans_complete();
            if ($this->db->trans_status() != FALSE) {
                $returntmsg = "success";
            }
        } else {
            $returntmsg .="Internal Error occured.No record found for Suspension";
        }
        return $returntmsg;
    }

    public function save_attd_action_by_fic() {
        date_default_timezone_set("Asia/Calcutta");
        $returntmsg = "";
        $select = $this->db->select('stud_reg_no,target_degree,processing_mon,processing_yr,attd_forward_by_fic,attd_forward_by_fic_time,attd_forward_by_fic_remark')->where(array('stud_reg_no' => $this->input->post('stud_reg_no'), 'processing_mon' => (int) $this->input->post('processing_mon'), 'processing_yr' => $this->input->post('processing_yr')))->get('fellow_bill_master');
        if ($select->num_rows()) {
            $row = $select->result_array();
            $this->db->trans_start();
            if ($row[0]['attd_forward_by_fic'] == 'C' || $row[0]['attd_forward_by_fic'] == 'R') {
                if (!($this->db->insert('fellow_attd_action_log_fic', $row[0])))
                    $returntmsg .= $this->db->_error_message() . ",";
            }
            $data = array(
                'attd_forward_by_fic' => 'Y',
                'attd_forward_by_fic_time' => date('Y-m-d H:i:s'),
                'attd_forward_by_fic_id' => $this->session->userdata('id')
            );
            $this->db->where(array('stud_reg_no' => $this->input->post('stud_reg_no'), 'processing_mon' => (int) $this->input->post('processing_mon'), 'processing_yr' => $this->input->post('processing_yr')));
            if (!$this->db->update('fellow_bill_master', $data))
                $returntmsg .= $this->db->_error_message() . ",";
            $this->db->trans_complete();
            if ($this->db->trans_status() != FALSE) {
                $returntmsg = "success";
            }
        } else {
            $returntmsg .="Internal Error occured. No record Found";
        }
        return $returntmsg;
    }

    public function save_attd_action_log_by_fic() {
        date_default_timezone_set("Asia/Calcutta");
        $returntmsg = "";
        $select = $this->db->select('stud_reg_no,target_degree,processing_mon,processing_yr,days_absent, admissible_leave,leave_balance,recovery_amt,net_amt_payable,attd_forwarding_by_dda,attd_forwarding_by_dda_time,attd_forwarding_by_dda_remark,attd_forward_by_fic,attd_forward_by_fic_time,attd_forward_by_fic_remark,attd_approval_by_gd,attd_approval_by_gd_time,attd_approval_by_gd_remark')->where(array('stud_reg_no' => $this->input->post('stud_reg_no'), 'processing_mon' => (int) $this->input->post('processing_mon'), 'processing_yr' => $this->input->post('processing_yr')))->get('fellow_bill_master');
        if ($select->num_rows()) {
            $row = $select->result_array();

            $fic_data = array('stud_reg_no' => $row[0]['stud_reg_no'], 'target_degree' => $row[0]['target_degree'], 'processing_mon' => $row[0]['processing_mon'],
                'processing_yr' => $row[0]['processing_yr'], 'attd_forward_by_fic' => $row[0]['attd_forward_by_fic'],
                'attd_forward_by_fic_time' => $row[0]['attd_forward_by_fic_time'], 'attd_forward_by_fic_remark' => $row[0]['attd_forward_by_fic_remark']);

            $gd_data = array('stud_reg_no' => $row[0]['stud_reg_no'], 'target_degree' => $row[0]['target_degree'], 'processing_mon' => $row[0]['processing_mon'],
                'processing_yr' => $row[0]['processing_yr'], 'attd_approval_by_gd' => $row[0]['attd_approval_by_gd'],
                'attd_approval_by_gd_time' => $row[0]['attd_approval_by_gd_time'], 'attd_approval_by_gd_remark' => $row[0]['attd_approval_by_gd_remark']);

            $dda_data = array('stud_reg_no' => $row[0]['stud_reg_no'], 'target_degree' => $row[0]['target_degree'], 'processing_mon' => $row[0]['processing_mon'],
                'processing_yr' => $row[0]['processing_yr'], 'days_absent' => $row[0]['days_absent'], 'admissible_leave' => $row[0]['admissible_leave'], 'leave_balance' => $row[0]['leave_balance'],
                'recovery_amt' => $row[0]['recovery_amt'], 'net_amt_payable' => $row[0]['net_amt_payable'], 'attd_forwarding_by_dda' => $row[0]['attd_forwarding_by_dda'],
                'attd_forwarding_by_dda_time' => $row[0]['attd_forwarding_by_dda_time'], 'attd_forwarding_by_dda_remark' => $row[0]['attd_forwarding_by_dda_remark']);

            $this->db->trans_start();
            if ($row[0]['attd_forward_by_fic'] == 'R' || $row[0]['attd_forward_by_fic'] == 'C')
                if (!($this->db->insert('fellow_attd_action_log_fic', $fic_data)))
                    $returntmsg .= $this->db->_error_message() . ",";

            if (!($this->db->insert('fellow_attd_action_log_gd', $gd_data)))
                $returntmsg .= $this->db->_error_message() . ",";

            if (!($this->db->insert('fellow_attd_log', $dda_data)))
                $returntmsg .= $this->db->_error_message() . ",";
            $data = array(
                'attd_forwarding_by_dda' => 'R',
                'attd_forwarding_by_dda_time' => date('Y-m-d H:i:s'),
                'attd_forwarding_by_dda_id' => '1', // By system
                'attd_forwarding_by_dda_remark' => $this->input->post('remark'),
                'attd_approval_by_gd' => 'C',
                'attd_approval_by_gd_time' => date('Y-m-d H:i:s'),
                'attd_approval_by_gd_id' => '1', // By system
                'attd_approval_by_gd_remark' => $this->input->post('remark'),
                'attd_forward_by_fic' => 'R',
                'attd_forward_by_fic_time' => date('Y-m-d H:i:s'),
                'attd_forward_by_fic_id' => $this->session->userdata('id'),
                'attd_forward_by_fic_remark' => $this->input->post('remark')
            );
            $this->db->where(array('stud_reg_no' => $this->input->post('stud_reg_no'), 'processing_mon' => $this->input->post('processing_mon'), 'processing_yr' => $this->input->post('processing_yr')));
            if (!$this->db->update('fellow_bill_master', $data))
                $returntmsg .= $this->db->_error_message() . ",";

            $this->db->trans_complete();
            if ($this->db->trans_status() != FALSE) {
                $returntmsg = "success";
            }
        } else {
            $returntmsg .="Internal Error occured.No record found for Rejection";
        }
        return $returntmsg;
    }

    public function save_attd_action_by_hod() {
        date_default_timezone_set("Asia/Calcutta");
        $returntmsg = "";
        $select = $this->db->select('stud_reg_no,target_degree,processing_mon,processing_yr,attd_approval_by_hod,attd_approval_by_hod_time,attd_approval_by_hod_remark')->where(array('stud_reg_no' => $this->input->post('stud_reg_no'), 'processing_mon' => (int) $this->input->post('processing_mon'), 'processing_yr' => $this->input->post('processing_yr')))->get('fellow_bill_master');
        if ($select->num_rows()) {
            $row = $select->result_array();
            $this->db->trans_start();
            if ($row[0]['attd_approval_by_hod'] == 'C' || $row[0]['attd_approval_by_hod'] == 'R') {
                if (!($this->db->insert('fellow_attd_action_log_hod', $row[0])))
                    $returntmsg .= $this->db->_error_message() . ",";
            }

            $data = array(
                'attd_approval_by_hod' => 'Y',
                'attd_approval_by_hod_time' => date('Y-m-d H:i:s'),
                'attd_approval_by_hod_id' => $this->session->userdata('id')
            );
            $this->db->where(array('stud_reg_no' => $this->input->post('stud_reg_no'), 'processing_mon' => $this->input->post('processing_mon'), 'processing_yr' => $this->input->post('processing_yr')));
            if (!$this->db->update('fellow_bill_master', $data))
                $returntmsg .= $this->db->_error_message() . ",";
            $this->db->trans_complete();
            if ($this->db->trans_status() != FALSE) {
                $returntmsg = "success";
            }
        } else {
            $returntmsg .="Internal Error occured. No record Found";
        }
        return $returntmsg;
    }

    public function save_attd_action_log_by_hod() {
        date_default_timezone_set("Asia/Calcutta");
        $returntmsg = "";
        $select = $this->db->select('stud_reg_no,target_degree,processing_mon,processing_yr,days_absent ,admissible_leave,leave_balance,recovery_amt,net_amt_payable,attd_forwarding_by_dda,attd_forwarding_by_dda_time,attd_forwarding_by_dda_remark,attd_forward_by_fic,attd_forward_by_fic_time,attd_forward_by_fic_remark,attd_approval_by_gd,attd_approval_by_gd_time,attd_approval_by_gd_remark,attd_approval_by_hod,attd_approval_by_hod_time,attd_approval_by_hod_remark')->where(array('stud_reg_no' => $this->input->post('stud_reg_no'), 'processing_mon' => (int) $this->input->post('processing_mon'), 'processing_yr' => $this->input->post('processing_yr')))->get('fellow_bill_master');
        if ($select->num_rows()) {
            $row = $select->result_array();
            $hod_data = array('stud_reg_no' => $row[0]['stud_reg_no'], 'target_degree' => $row[0]['target_degree'], 'processing_mon' => $row[0]['processing_mon'],
                'processing_yr' => $row[0]['processing_yr'], 'attd_approval_by_hod' => $row[0]['attd_approval_by_hod'],
                'attd_approval_by_hod_time' => $row[0]['attd_approval_by_hod_time'], 'attd_approval_by_hod_remark' => $row[0]['attd_approval_by_hod_remark']);

            $fic_data = array('stud_reg_no' => $row[0]['stud_reg_no'], 'target_degree' => $row[0]['target_degree'], 'processing_mon' => $row[0]['processing_mon'],
                'processing_yr' => $row[0]['processing_yr'], 'attd_forward_by_fic' => $row[0]['attd_forward_by_fic'],
                'attd_forward_by_fic_time' => $row[0]['attd_forward_by_fic_time'], 'attd_forward_by_fic_remark' => $row[0]['attd_forward_by_fic_remark']);

            $gd_data = array('stud_reg_no' => $row[0]['stud_reg_no'], 'target_degree' => $row[0]['target_degree'], 'processing_mon' => $row[0]['processing_mon'],
                'processing_yr' => $row[0]['processing_yr'], 'attd_approval_by_gd' => $row[0]['attd_approval_by_gd'],
                'attd_approval_by_gd_time' => $row[0]['attd_approval_by_gd_time'], 'attd_approval_by_gd_remark' => $row[0]['attd_approval_by_gd_remark']);

            $dda_data = array('stud_reg_no' => $row[0]['stud_reg_no'], 'target_degree' => $row[0]['target_degree'], 'processing_mon' => $row[0]['processing_mon'],
                'processing_yr' => $row[0]['processing_yr'], 'days_absent' => $row[0]['days_absent'], 'admissible_leave' => $row[0]['admissible_leave'], 'leave_balance' => $row[0]['leave_balance'],
                'recovery_amt' => $row[0]['recovery_amt'], 'net_amt_payable' => $row[0]['net_amt_payable'], 'attd_forwarding_by_dda' => $row[0]['attd_forwarding_by_dda'],
                'attd_forwarding_by_dda_time' => $row[0]['attd_forwarding_by_dda_time'], 'attd_forwarding_by_dda_remark' => $row[0]['attd_forwarding_by_dda_remark']);


            $this->db->trans_start();
            if ($row[0]['attd_approval_by_hod'] == 'R' || $row[0]['attd_approval_by_hod'] == 'C')
                if (!($this->db->insert('fellow_attd_action_log_hod', $hod_data)))
                    $returntmsg .= $this->db->_error_message() . ",";

            if (!($this->db->insert('fellow_attd_action_log_fic', $fic_data)))
                $returntmsg .= $this->db->_error_message() . ",";

            if (!($this->db->insert('fellow_attd_action_log_gd', $gd_data)))
                $returntmsg .= $this->db->_error_message() . ",";

            if (!($this->db->insert('fellow_attd_log', $dda_data)))
                $returntmsg .= $this->db->_error_message() . ",";
            $data = array(
                'attd_forwarding_by_dda' => 'R',
                'attd_forwarding_by_dda_time' => date('Y-m-d H:i:s'),
                'attd_forwarding_by_dda_id' => '1', // By system
                'attd_forwarding_by_dda_remark' => $this->input->post('remark'),
                'attd_approval_by_gd' => 'C',
                'attd_approval_by_gd_time' => date('Y-m-d H:i:s'),
                'attd_approval_by_gd_id' => '1', // By system
                'attd_approval_by_gd_remark' => $this->input->post('remark'),
                'attd_forward_by_fic' => 'C',
                'attd_forward_by_fic_time' => date('Y-m-d H:i:s'),
                'attd_forward_by_fic_id' => '1',
                'attd_forward_by_fic_remark' => $this->input->post('remark'),
                'attd_approval_by_hod' => 'R',
                'attd_approval_by_hod_time' => date('Y-m-d H:i:s'),
                'attd_approval_by_hod_id' => $this->session->userdata('id'),
                'attd_approval_by_hod_remark' => $this->input->post('remark')
            );
            $this->db->where(array('stud_reg_no' => $this->input->post('stud_reg_no'), 'processing_mon' => $this->input->post('processing_mon'), 'processing_yr' => $this->input->post('processing_yr')));
            if (!$this->db->update('fellow_bill_master', $data))
                $returntmsg .= $this->db->_error_message() . ",";

            $this->db->trans_complete();
            if ($this->db->trans_status() != FALSE) {
                $returntmsg = "success";
            }
        } else {
            $returntmsg .="Internal Error occured.No record found.";
        }
        return $returntmsg;
    }

    public function save_attd_action_by_acad_da() {
        date_default_timezone_set("Asia/Calcutta");
        $returntmsg = "";
        $select = $this->db->select('stud_reg_no,target_degree,processing_mon,processing_yr,attd_forwarding_by_acad_da,attd_forwarding_by_acad_da_time,attd_forwarding_by_acad_da_remark')->where(array('stud_reg_no' => $this->input->post('stud_reg_no'), 'processing_mon' => (int) $this->input->post('processing_mon'), 'processing_yr' => $this->input->post('processing_yr')))->get('fellow_bill_master');
        if ($select->num_rows()) {
            $row = $select->result_array();

            $this->db->trans_start();
            if ($row[0]['attd_forwarding_by_acad_da'] == 'C' || $row[0]['attd_forwarding_by_acad_da'] == 'R') {
                if (!($this->db->insert('fellow_attd_action_log_acad_da', $row[0])))
                    $returntmsg .= $this->db->_error_message() . ",";
            }

            $data = array(
                'attd_forwarding_by_acad_da' => 'Y',
                'attd_forwarding_by_acad_da_time' => date('Y-m-d H:i:s'),
                'attd_forwarding_by_acad_da_id' => $this->session->userdata('id')
            );
            $this->db->where(array('stud_reg_no' => $this->input->post('stud_reg_no'), 'processing_mon' => $this->input->post('processing_mon'), 'processing_yr' => $this->input->post('processing_yr')));
            if (!$this->db->update('fellow_bill_master', $data))
                $returntmsg .= $this->db->_error_message() . ",";

            $this->db->trans_complete();
            if ($this->db->trans_status() != FALSE) {
                $returntmsg = "success";
            }
        } else {
            $returntmsg .="Internal Error occured.No record found for Rejection";
        }
        return $returntmsg;
    }

    public function save_attd_action_log_by_acad_da() {
        date_default_timezone_set("Asia/Calcutta");
        $returntmsg = "";
        $select = $this->db->select('stud_reg_no,target_degree,processing_mon,processing_yr,days_absent ,admissible_leave,leave_balance,recovery_amt,net_amt_payable,attd_forwarding_by_dda,attd_forwarding_by_dda_time,attd_forwarding_by_dda_remark,attd_forward_by_fic,attd_forward_by_fic_time,attd_forward_by_fic_remark,attd_approval_by_gd,attd_approval_by_gd_time,attd_approval_by_hod,attd_approval_by_hod_time,attd_approval_by_hod_remark,attd_approval_by_gd_remark,attd_forwarding_by_acad_da,attd_forwarding_by_acad_da_time,attd_forwarding_by_acad_da_remark')->where(array('stud_reg_no' => $this->input->post('stud_reg_no'), 'processing_mon' => (int) $this->input->post('processing_mon'), 'processing_yr' => $this->input->post('processing_yr')))->get('fellow_bill_master');
        if ($select->num_rows()) {
            $row = $select->result_array();

            $acad_da_data = array('stud_reg_no' => $row[0]['stud_reg_no'], 'target_degree' => $row[0]['target_degree'], 'processing_mon' => $row[0]['processing_mon'],
                'processing_yr' => $row[0]['processing_yr'], 'attd_forwarding_by_acad_da' => $row[0]['attd_forwarding_by_acad_da'],
                'attd_forwarding_by_acad_da_time' => $row[0]['attd_forwarding_by_acad_da_time'], 'attd_forwarding_by_acad_da_remark' => $row[0]['attd_forwarding_by_acad_da_remark']);

            $hod_data = array('stud_reg_no' => $row[0]['stud_reg_no'], 'target_degree' => $row[0]['target_degree'], 'processing_mon' => $row[0]['processing_mon'],
                'processing_yr' => $row[0]['processing_yr'], 'attd_approval_by_hod' => $row[0]['attd_approval_by_hod'],
                'attd_approval_by_hod_time' => $row[0]['attd_approval_by_hod_time'], 'attd_approval_by_hod_remark' => $row[0]['attd_approval_by_hod_remark']);

            $fic_data = array('stud_reg_no' => $row[0]['stud_reg_no'], 'target_degree' => $row[0]['target_degree'], 'processing_mon' => $row[0]['processing_mon'],
                'processing_yr' => $row[0]['processing_yr'], 'attd_forward_by_fic' => $row[0]['attd_forward_by_fic'],
                'attd_forward_by_fic_time' => $row[0]['attd_forward_by_fic_time'], 'attd_forward_by_fic_remark' => $row[0]['attd_forward_by_fic_remark']);

            $gd_data = array('stud_reg_no' => $row[0]['stud_reg_no'], 'target_degree' => $row[0]['target_degree'], 'processing_mon' => $row[0]['processing_mon'],
                'processing_yr' => $row[0]['processing_yr'], 'attd_approval_by_gd' => $row[0]['attd_approval_by_gd'],
                'attd_approval_by_gd_time' => $row[0]['attd_approval_by_gd_time'], 'attd_approval_by_gd_remark' => $row[0]['attd_approval_by_gd_remark']);

            $dda_data = array('stud_reg_no' => $row[0]['stud_reg_no'], 'target_degree' => $row[0]['target_degree'], 'processing_mon' => $row[0]['processing_mon'],
                'processing_yr' => $row[0]['processing_yr'], 'days_absent' => $row[0]['days_absent'], 'leave_balance' => $row[0]['leave_balance'], 'admissible_leave' => $row[0]['admissible_leave'],
                'recovery_amt' => $row[0]['recovery_amt'], 'net_amt_payable' => $row[0]['net_amt_payable'], 'attd_forwarding_by_dda' => $row[0]['attd_forwarding_by_dda'],
                'attd_forwarding_by_dda_time' => $row[0]['attd_forwarding_by_dda_time'], 'attd_forwarding_by_dda_remark' => $row[0]['attd_forwarding_by_dda_remark']);

            $this->db->trans_start();
            if ($row[0]['attd_forwarding_by_acad_da'] == 'R' || $row[0]['attd_forwarding_by_acad_da'] == 'C')
                if (!($this->db->insert('fellow_attd_action_log_acad_da', $acad_da_data)))
                    $returntmsg .= $this->db->_error_message() . ",";
            if (!($this->db->insert('fellow_attd_action_log_hod', $hod_data)))
                $returntmsg .= $this->db->_error_message() . ",";

            if (!($this->db->insert('fellow_attd_action_log_fic', $fic_data)))
                $returntmsg .= $this->db->_error_message() . ",";

            if (!($this->db->insert('fellow_attd_action_log_gd', $gd_data)))
                $returntmsg .= $this->db->_error_message() . ",";

            if (!($this->db->insert('fellow_attd_log', $dda_data)))
                $returntmsg .= $this->db->_error_message() . ",";
            $data = array(
                'attd_forwarding_by_dda' => 'R',
                'attd_forwarding_by_dda_time' => date('Y-m-d H:i:s'),
                'attd_forwarding_by_dda_id' => '1', // By system
                'attd_forwarding_by_dda_remark' => $this->input->post('remark'),
                'attd_approval_by_gd' => 'C',
                'attd_approval_by_gd_time' => date('Y-m-d H:i:s'),
                'attd_approval_by_gd_id' => '1', // By system
                'attd_approval_by_gd_remark' => $this->input->post('remark'),
                'attd_forward_by_fic' => 'C',
                'attd_forward_by_fic_time' => date('Y-m-d H:i:s'),
                'attd_forward_by_fic_id' => '1',
                'attd_forward_by_fic_remark' => $this->input->post('remark'),
                'attd_approval_by_hod' => 'C',
                'attd_approval_by_hod_time' => date('Y-m-d H:i:s'),
                'attd_approval_by_hod_id' => '1',
                'attd_approval_by_hod_remark' => $this->input->post('remark'),
                'attd_forwarding_by_acad_da' => 'R',
                'attd_forwarding_by_acad_da_time' => date('Y-m-d H:i:s'),
                'attd_forwarding_by_acad_da_id' => $this->session->userdata('id'),
                'attd_forwarding_by_acad_da_remark' => $this->input->post('remark')
            );
            $this->db->where(array('stud_reg_no' => $this->input->post('stud_reg_no'), 'processing_mon' => $this->input->post('processing_mon'), 'processing_yr' => $this->input->post('processing_yr')));
            if (!$this->db->update('fellow_bill_master', $data))
                $returntmsg .= $this->db->_error_message() . ",";

            $this->db->trans_complete();
            if ($this->db->trans_status() != FALSE) {
                $returntmsg = "success";
            }
        } else {
            $returntmsg .="Internal Error occured.No record found for Rejection";
        }
        return $returntmsg;
    }

    public function save_attd_action_by_acad_ar() {
        date_default_timezone_set("Asia/Calcutta");
        $returntmsg = "";
        $select = $this->db->select('stud_reg_no,target_degree,processing_mon,processing_yr,attd_approval_by_acad_ar,attd_approval_by_acad_ar_time,attd_approval_by_acad_ar_remark')->where(array('stud_reg_no' => $this->input->post('stud_reg_no'), 'processing_mon' => (int) $this->input->post('processing_mon'), 'processing_yr' => $this->input->post('processing_yr')))->get('fellow_bill_master');
        if ($select->num_rows()) {
            $row = $select->result_array();

            $this->db->trans_start();
            if ($row[0]['attd_approval_by_acad_ar'] == 'C' || $row[0]['attd_approval_by_acad_ar'] == 'R') {
                if (!($this->db->insert('fellow_attd_action_log_acad_ar', $row[0])))
                    $returntmsg .= $this->db->_error_message() . ",";
            }


            $data = array(
                'attd_approval_by_acad_ar' => 'Y',
                'attd_approval_by_acad_ar_time' => date('Y-m-d H:i:s'),
                'attd_approval_by_acad_ar_id' => $this->session->userdata('id')
            );
            $this->db->where(array('stud_reg_no' => $this->input->post('stud_reg_no'), 'processing_mon' => (int) $this->input->post('processing_mon'), 'processing_yr' => $this->input->post('processing_yr')));
            if (!$this->db->update('fellow_bill_master', $data))
                $returntmsg .= $this->db->_error_message() . ",";

            $this->db->trans_complete();
            if ($this->db->trans_status() != FALSE) {
                $returntmsg = "success";
            }
        } else {
            $returntmsg .="Internal Error occured.No record found.";
        }
        return $returntmsg;
    }

    public function save_attd_action_log_by_acad_ar() {
        date_default_timezone_set("Asia/Calcutta");
        $returntmsg = "";
        $select = $this->db->select('stud_reg_no,target_degree,processing_mon,processing_yr,days_absent ,admissible_leave,leave_balance,recovery_amt,net_amt_payable,attd_forwarding_by_dda,attd_forwarding_by_dda_time,attd_forwarding_by_dda_remark,attd_forward_by_fic,attd_forward_by_fic_time,attd_forward_by_fic_remark,attd_approval_by_gd,attd_approval_by_gd_time,attd_approval_by_hod,attd_approval_by_hod_time,attd_approval_by_hod_remark,attd_approval_by_gd_remark,attd_forwarding_by_acad_da,attd_forwarding_by_acad_da_time,attd_forwarding_by_acad_da_remark,attd_approval_by_acad_ar,attd_approval_by_acad_ar_time,attd_approval_by_acad_ar_remark')->where(array('stud_reg_no' => $this->input->post('stud_reg_no'), 'processing_mon' => (int) $this->input->post('processing_mon'), 'processing_yr' => $this->input->post('processing_yr')))->get('fellow_bill_master');
        if ($select->num_rows()) {
            $row = $select->result_array();
            $acad_ar_data = array('stud_reg_no' => $row[0]['stud_reg_no'], 'target_degree' => $row[0]['target_degree'], 'processing_mon' => $row[0]['processing_mon'],
                'processing_yr' => $row[0]['processing_yr'], 'attd_approval_by_acad_ar' => $row[0]['attd_approval_by_acad_ar'],
                'attd_approval_by_acad_ar_time' => $row[0]['attd_approval_by_acad_ar_time'], 'attd_approval_by_acad_ar_remark' => $row[0]['attd_approval_by_acad_ar_remark']);
            $acad_da_data = array('stud_reg_no' => $row[0]['stud_reg_no'], 'target_degree' => $row[0]['target_degree'], 'processing_mon' => $row[0]['processing_mon'],
                'processing_yr' => $row[0]['processing_yr'], 'attd_forwarding_by_acad_da' => $row[0]['attd_forwarding_by_acad_da'],
                'attd_forwarding_by_acad_da_time' => $row[0]['attd_forwarding_by_acad_da_time'], 'attd_forwarding_by_acad_da_remark' => $row[0]['attd_forwarding_by_acad_da_remark']);
            $hod_data = array('stud_reg_no' => $row[0]['stud_reg_no'], 'target_degree' => $row[0]['target_degree'], 'processing_mon' => $row[0]['processing_mon'],
                'processing_yr' => $row[0]['processing_yr'], 'attd_approval_by_hod' => $row[0]['attd_approval_by_hod'],
                'attd_approval_by_hod_time' => $row[0]['attd_approval_by_hod_time'], 'attd_approval_by_hod_remark' => $row[0]['attd_approval_by_hod_remark']);

            $fic_data = array('stud_reg_no' => $row[0]['stud_reg_no'], 'target_degree' => $row[0]['target_degree'], 'processing_mon' => $row[0]['processing_mon'],
                'processing_yr' => $row[0]['processing_yr'], 'attd_forward_by_fic' => $row[0]['attd_forward_by_fic'],
                'attd_forward_by_fic_time' => $row[0]['attd_forward_by_fic_time'], 'attd_forward_by_fic_remark' => $row[0]['attd_forward_by_fic_remark']);

            $gd_data = array('stud_reg_no' => $row[0]['stud_reg_no'], 'target_degree' => $row[0]['target_degree'], 'processing_mon' => $row[0]['processing_mon'],
                'processing_yr' => $row[0]['processing_yr'], 'attd_approval_by_gd' => $row[0]['attd_approval_by_gd'],
                'attd_approval_by_gd_time' => $row[0]['attd_approval_by_gd_time'], 'attd_approval_by_gd_remark' => $row[0]['attd_approval_by_gd_remark']);
            $dda_data = array('stud_reg_no' => $row[0]['stud_reg_no'], 'target_degree' => $row[0]['target_degree'], 'processing_mon' => $row[0]['processing_mon'],
                'processing_yr' => $row[0]['processing_yr'], 'days_absent' => $row[0]['days_absent'], 'admissible_leave' => $row[0]['admissible_leave'], 'leave_balance' => $row[0]['leave_balance'],
                'recovery_amt' => $row[0]['recovery_amt'], 'net_amt_payable' => $row[0]['net_amt_payable'], 'attd_forwarding_by_dda' => $row[0]['attd_forwarding_by_dda'],
                'attd_forwarding_by_dda_time' => $row[0]['attd_forwarding_by_dda_time'], 'attd_forwarding_by_dda_remark' => $row[0]['attd_forwarding_by_dda_remark']);


            $this->db->trans_start();
            if ($row[0]['attd_approval_by_acad_ar'] == 'R' || $row[0]['attd_approval_by_acad_ar'] == 'C')
                if (!($this->db->insert('fellow_attd_action_log_acad_ar', $acad_ar_data)))
                    $returntmsg .= $this->db->_error_message() . ",";

            if (!($this->db->insert('fellow_attd_action_log_acad_da', $acad_da_data)))
                $returntmsg .= $this->db->_error_message() . ",";
            if (!($this->db->insert('fellow_attd_action_log_hod', $hod_data)))
                $returntmsg .= $this->db->_error_message() . ",";

            if (!($this->db->insert('fellow_attd_action_log_fic', $fic_data)))
                $returntmsg .= $this->db->_error_message() . ",";

            if (!($this->db->insert('fellow_attd_action_log_gd', $gd_data)))
                $returntmsg .= $this->db->_error_message() . ",";

            if (!($this->db->insert('fellow_attd_log', $dda_data)))
                $returntmsg .= $this->db->_error_message() . ",";
            $data = array(
                'attd_forwarding_by_dda' => 'R',
                'attd_forwarding_by_dda_time' => date('Y-m-d H:i:s'),
                'attd_forwarding_by_dda_id' => '1', // By system
                'attd_forwarding_by_dda_remark' => $this->input->post('remark'),
                'attd_approval_by_gd' => 'C',
                'attd_approval_by_gd_time' => date('Y-m-d H:i:s'),
                'attd_approval_by_gd_id' => '1', // By system
                'attd_approval_by_gd_remark' => $this->input->post('remark'),
                'attd_forward_by_fic' => 'C',
                'attd_forward_by_fic_time' => date('Y-m-d H:i:s'),
                'attd_forward_by_fic_id' => '1',
                'attd_forward_by_fic_remark' => $this->input->post('remark'),
                'attd_approval_by_hod' => 'C',
                'attd_approval_by_hod_time' => date('Y-m-d H:i:s'),
                'attd_approval_by_hod_id' => '1',
                'attd_approval_by_hod_remark' => $this->input->post('remark'),
                'attd_forwarding_by_acad_da' => 'C',
                'attd_forwarding_by_acad_da_time' => date('Y-m-d H:i:s'),
                'attd_forwarding_by_acad_da_id' => '1',
                'attd_forwarding_by_acad_da_remark' => $this->input->post('remark'),
                'attd_approval_by_acad_ar' => 'R',
                'attd_approval_by_acad_ar_time' => date('Y-m-d H:i:s'),
                'attd_approval_by_acad_ar_id' => $this->session->userdata('id'),
                'attd_approval_by_acad_ar_remark' => $this->input->post('remark')
            );
            $this->db->where(array('stud_reg_no' => $this->input->post('stud_reg_no'), 'processing_mon' => $this->input->post('processing_mon'), 'processing_yr' => $this->input->post('processing_yr')));
            if (!$this->db->update('fellow_bill_master', $data))
                $returntmsg .= $this->db->_error_message() . ",";

            $this->db->trans_complete();
            if ($this->db->trans_status() != FALSE) {
                $returntmsg = "success";
            }
        } else {
            $returntmsg .="Internal Error occured.No record found for Rejection";
        }
        return $returntmsg;
    }

    public function save_attd_action_by_acc_da() {
        date_default_timezone_set("Asia/Calcutta");
        $returntmsg = "";
        $select = $this->db->select('stud_reg_no,target_degree,processing_mon,processing_yr,attd_forwarding_by_acc_da,attd_forwarding_by_acc_da_time,attd_forwarding_by_acc_da_remark')->where(array('stud_reg_no' => $this->input->post('stud_reg_no'), 'processing_mon' => (int) $this->input->post('processing_mon'), 'processing_yr' => $this->input->post('processing_yr')))->get('fellow_bill_master');
        if ($select->num_rows()) {
            $row = $select->result_array();
            $this->db->trans_start();
            if ($row[0]['attd_forwarding_by_acc_da'] == 'C' || $row[0]['attd_forwarding_by_acc_da'] == 'R') {
                if (!($this->db->insert('fellow_attd_action_log_acc_da', $row[0])))
                    $returntmsg .= $this->db->_error_message() . ",";
            }


            $data = array(
                'attd_forwarding_by_acc_da' => 'Y',
                'attd_forwarding_by_acc_da_time' => date('Y-m-d H:i:s'),
                'attd_forwarding_by_acc_da_id' => $this->session->userdata('id')
            );
            $this->db->where(array('stud_reg_no' => $this->input->post('stud_reg_no'), 'processing_mon' => $this->input->post('processing_mon'), 'processing_yr' => $this->input->post('processing_yr')));
            if (!$this->db->update('fellow_bill_master', $data))
                $returntmsg .= $this->db->_error_message() . ",";

            $this->db->trans_complete();
            if ($this->db->trans_status() != FALSE) {
                $returntmsg = "success";
            }
        } else {
            $returntmsg .="Internal Error occured.No record found.";
        }
        return $returntmsg;
    }

    public function save_attd_action_log_by_acc_da() {
        date_default_timezone_set("Asia/Calcutta");
        $returntmsg = "";
        $select = $this->db->select('stud_reg_no,target_degree,processing_mon,processing_yr,days_absent ,admissible_leave,leave_balance,recovery_amt,net_amt_payable,attd_forwarding_by_dda,attd_forwarding_by_dda_time,attd_forwarding_by_dda_remark,attd_forward_by_fic,attd_forward_by_fic_time,attd_forward_by_fic_remark,attd_approval_by_gd,attd_approval_by_gd_time,attd_approval_by_hod,attd_approval_by_hod_time,attd_approval_by_hod_remark,attd_approval_by_gd_remark,attd_forwarding_by_acad_da,attd_forwarding_by_acad_da_time,attd_forwarding_by_acad_da_remark,attd_approval_by_acad_ar,attd_approval_by_acad_ar_time,attd_approval_by_acad_ar_remark,attd_forwarding_by_acc_da,attd_forwarding_by_acc_da_time,attd_forwarding_by_acc_da_remark')->where(array('stud_reg_no' => $this->input->post('stud_reg_no'), 'processing_mon' => (int) $this->input->post('processing_mon'), 'processing_yr' => $this->input->post('processing_yr')))->get('fellow_bill_master');
        if ($select->num_rows()) {
            $row = $select->result_array();

            $acc_da_data = array('stud_reg_no' => $row[0]['stud_reg_no'], 'target_degree' => $row[0]['target_degree'], 'processing_mon' => $row[0]['processing_mon'],
                'processing_yr' => $row[0]['processing_yr'], 'attd_forwarding_by_acc_da' => $row[0]['attd_forwarding_by_acc_da'],
                'attd_forwarding_by_acc_da_time' => $row[0]['attd_forwarding_by_acc_da_time'], 'attd_forwarding_by_acc_da_remark' => $row[0]['attd_forwarding_by_acc_da_remark']);

            $acad_ar_data = array('stud_reg_no' => $row[0]['stud_reg_no'], 'target_degree' => $row[0]['target_degree'], 'processing_mon' => $row[0]['processing_mon'],
                'processing_yr' => $row[0]['processing_yr'], 'attd_approval_by_acad_ar' => $row[0]['attd_approval_by_acad_ar'],
                'attd_approval_by_acad_ar_time' => $row[0]['attd_approval_by_acad_ar_time'], 'attd_approval_by_acad_ar_remark' => $row[0]['attd_approval_by_acad_ar_remark']);
            $acad_da_data = array('stud_reg_no' => $row[0]['stud_reg_no'], 'target_degree' => $row[0]['target_degree'], 'processing_mon' => $row[0]['processing_mon'],
                'processing_yr' => $row[0]['processing_yr'], 'attd_forwarding_by_acad_da' => $row[0]['attd_forwarding_by_acad_da'],
                'attd_forwarding_by_acad_da_time' => $row[0]['attd_forwarding_by_acad_da_time'], 'attd_forwarding_by_acad_da_remark' => $row[0]['attd_forwarding_by_acad_da_remark']);
            $hod_data = array('stud_reg_no' => $row[0]['stud_reg_no'], 'target_degree' => $row[0]['target_degree'], 'processing_mon' => $row[0]['processing_mon'],
                'processing_yr' => $row[0]['processing_yr'], 'attd_approval_by_hod' => $row[0]['attd_approval_by_hod'],
                'attd_approval_by_hod_time' => $row[0]['attd_approval_by_hod_time'], 'attd_approval_by_hod_remark' => $row[0]['attd_approval_by_hod_remark']);

            $fic_data = array('stud_reg_no' => $row[0]['stud_reg_no'], 'target_degree' => $row[0]['target_degree'], 'processing_mon' => $row[0]['processing_mon'],
                'processing_yr' => $row[0]['processing_yr'], 'attd_forward_by_fic' => $row[0]['attd_forward_by_fic'],
                'attd_forward_by_fic_time' => $row[0]['attd_forward_by_fic_time'], 'attd_forward_by_fic_remark' => $row[0]['attd_forward_by_fic_remark']);

            $gd_data = array('stud_reg_no' => $row[0]['stud_reg_no'], 'target_degree' => $row[0]['target_degree'], 'processing_mon' => $row[0]['processing_mon'],
                'processing_yr' => $row[0]['processing_yr'], 'attd_approval_by_gd' => $row[0]['attd_approval_by_gd'],
                'attd_approval_by_gd_time' => $row[0]['attd_approval_by_gd_time'], 'attd_approval_by_gd_remark' => $row[0]['attd_approval_by_gd_remark']);

            $dda_data = array('stud_reg_no' => $row[0]['stud_reg_no'], 'target_degree' => $row[0]['target_degree'], 'processing_mon' => $row[0]['processing_mon'],
                'processing_yr' => $row[0]['processing_yr'], 'days_absent' => $row[0]['days_absent'], 'admissible_leave' => $row[0]['admissible_leave'], 'leave_balance' => $row[0]['leave_balance'],
                'recovery_amt' => $row[0]['recovery_amt'], 'net_amt_payable' => $row[0]['net_amt_payable'], 'attd_forwarding_by_dda' => $row[0]['attd_forwarding_by_dda'],
                'attd_forwarding_by_dda_time' => $row[0]['attd_forwarding_by_dda_time'], 'attd_forwarding_by_dda_remark' => $row[0]['attd_forwarding_by_dda_remark']);

            $this->db->trans_start();
            if ($row[0]['attd_forwarding_by_acc_da'] == 'R' || $row[0]['attd_forwarding_by_acc_da'] == 'C')
                if (!($this->db->insert('fellow_attd_action_log_acc_da', $acc_da_data)))
                    $returntmsg .= $this->db->_error_message() . ",";

            if (!($this->db->insert('fellow_attd_action_log_acad_ar', $acad_ar_data)))
                $returntmsg .= $this->db->_error_message() . ",";

            if (!($this->db->insert('fellow_attd_action_log_acad_da', $acad_da_data)))
                $returntmsg .= $this->db->_error_message() . ",";
            if (!($this->db->insert('fellow_attd_action_log_hod', $hod_data)))
                $returntmsg .= $this->db->_error_message() . ",";

            if (!($this->db->insert('fellow_attd_action_log_fic', $fic_data)))
                $returntmsg .= $this->db->_error_message() . ",";

            if (!($this->db->insert('fellow_attd_action_log_gd', $gd_data)))
                $returntmsg .= $this->db->_error_message() . ",";
            if (!($this->db->insert('fellow_attd_log', $dda_data)))
                $returntmsg .= $this->db->_error_message() . ",";
            $data = array(
                'attd_forwarding_by_dda' => 'R',
                'attd_forwarding_by_dda_time' => date('Y-m-d H:i:s'),
                'attd_forwarding_by_dda_id' => '1', // By system
                'attd_forwarding_by_dda_remark' => $this->input->post('remark'),
                'attd_approval_by_gd' => 'C',
                'attd_approval_by_gd_time' => date('Y-m-d H:i:s'),
                'attd_approval_by_gd_id' => '1', // By system
                'attd_approval_by_gd_remark' => $this->input->post('remark'),
                'attd_forward_by_fic' => 'C',
                'attd_forward_by_fic_time' => date('Y-m-d H:i:s'),
                'attd_forward_by_fic_id' => '1',
                'attd_forward_by_fic_remark' => $this->input->post('remark'),
                'attd_approval_by_hod' => 'C',
                'attd_approval_by_hod_time' => date('Y-m-d H:i:s'),
                'attd_approval_by_hod_id' => '1',
                'attd_approval_by_hod_remark' => $this->input->post('remark'),
                'attd_forwarding_by_acad_da' => 'C',
                'attd_forwarding_by_acad_da_time' => date('Y-m-d H:i:s'),
                'attd_forwarding_by_acad_da_id' => '1',
                'attd_forwarding_by_acad_da_remark' => $this->input->post('remark'),
                'attd_approval_by_acad_ar' => 'C',
                'attd_approval_by_acad_ar_time' => date('Y-m-d H:i:s'),
                'attd_approval_by_acad_ar_id' => '1',
                'attd_approval_by_acad_ar_remark' => $this->input->post('remark'),
                'attd_forwarding_by_acc_da' => 'R',
                'attd_forwarding_by_acc_da_time' => date('Y-m-d H:i:s'),
                'attd_forwarding_by_acc_da_id' => $this->session->userdata('id'),
                'attd_forwarding_by_acc_da_remark' => $this->input->post('remark')
            );
            $this->db->where(array('stud_reg_no' => $this->input->post('stud_reg_no'), 'processing_mon' => $this->input->post('processing_mon'), 'processing_yr' => $this->input->post('processing_yr')));
            if (!$this->db->update('fellow_bill_master', $data))
                $returntmsg .= $this->db->_error_message() . ",";

            $this->db->trans_complete();
            if ($this->db->trans_status() != FALSE) {
                $returntmsg = "success";
            }
        } else {
            $returntmsg .="Internal Error occured.No record found for Rejection";
        }
        return $returntmsg;
    }

    public function save_attd_action_by_acc_ar() {
        date_default_timezone_set("Asia/Calcutta");
        $returntmsg = "";
        $select = $this->db->select('stud_reg_no,target_degree,processing_mon,processing_yr,attd_approval_by_acc_ar,attd_approval_by_acc_ar_time,attd_approval_by_acc_ar_remark')->where(array('stud_reg_no' => $this->input->post('stud_reg_no'), 'processing_mon' => (int) $this->input->post('processing_mon'), 'processing_yr' => $this->input->post('processing_yr')))->get('fellow_bill_master');
        if ($select->num_rows()) {
            $row = $select->result_array();
            $this->db->trans_start();
            if ($row[0]['attd_approval_by_acc_ar'] == 'C' || $row[0]['attd_approval_by_acc_ar'] == 'R') {
                if (!($this->db->insert('fellow_attd_action_log_acc_ar', $row[0])))
                    $returntmsg .= $this->db->_error_message() . ",";
            }


            $data = array(
                'attd_approval_by_acc_ar' => 'Y',
                'attd_approval_by_acc_ar_time' => date('Y-m-d H:i:s'),
                'attd_approval_by_acc_ar_id' => $this->session->userdata('id')
            );
            $this->db->where(array('stud_reg_no' => $this->input->post('stud_reg_no'), 'processing_mon' => $this->input->post('processing_mon'), 'processing_yr' => $this->input->post('processing_yr')));
            if (!$this->db->update('fellow_bill_master', $data))
                $returntmsg .= $this->db->_error_message() . ",";

            $this->db->trans_complete();
            if ($this->db->trans_status() != FALSE) {
                $returntmsg = "success";
            }
        } else {
            $returntmsg .="Internal Error occured.No record found ";
        }
        return $returntmsg;
    }

    public function save_attd_action_log_by_acc_ar() {
        date_default_timezone_set("Asia/Calcutta");
        $returntmsg = "";
        $select = $this->db->select('stud_reg_no,target_degree,processing_mon,processing_yr,days_absent ,admissible_leave,leave_balance,recovery_amt,net_amt_payable,attd_forwarding_by_dda,attd_forwarding_by_dda_time,attd_forwarding_by_dda_remark,attd_forward_by_fic,attd_forward_by_fic_time,attd_forward_by_fic_remark,attd_approval_by_gd,attd_approval_by_gd_time,attd_approval_by_hod,attd_approval_by_hod_time,attd_approval_by_hod_remark,attd_approval_by_gd_remark,attd_forwarding_by_acad_da,attd_forwarding_by_acad_da_time,attd_forwarding_by_acad_da_remark,attd_approval_by_acad_ar,attd_approval_by_acad_ar_time,attd_approval_by_acad_ar_remark,attd_forwarding_by_acc_da,attd_forwarding_by_acc_da_time,attd_forwarding_by_acc_da_remark,attd_approval_by_acc_ar,attd_approval_by_acc_ar_time,attd_approval_by_acc_ar_remark')->where(array('stud_reg_no' => $this->input->post('stud_reg_no'), 'processing_mon' => (int) $this->input->post('processing_mon'), 'processing_yr' => $this->input->post('processing_yr')))->get('fellow_bill_master');
        if ($select->num_rows()) {
            $row = $select->result_array();
            $acc_ar_data = array('stud_reg_no' => $row[0]['stud_reg_no'], 'target_degree' => $row[0]['target_degree'], 'processing_mon' => $row[0]['processing_mon'],
                'processing_yr' => $row[0]['processing_yr'], 'attd_approval_by_acc_ar' => $row[0]['attd_approval_by_acc_ar'],
                'attd_approval_by_acc_ar_time' => $row[0]['attd_approval_by_acc_ar_time'], 'attd_approval_by_acc_ar_remark' => $row[0]['attd_approval_by_acc_ar_remark']);

            $acc_da_data = array('stud_reg_no' => $row[0]['stud_reg_no'], 'target_degree' => $row[0]['target_degree'], 'processing_mon' => $row[0]['processing_mon'],
                'processing_yr' => $row[0]['processing_yr'], 'attd_forwarding_by_acc_da' => $row[0]['attd_forwarding_by_acc_da'],
                'attd_forwarding_by_acc_da_time' => $row[0]['attd_forwarding_by_acc_da_time'], 'attd_forwarding_by_acc_da_remark' => $row[0]['attd_forwarding_by_acc_da_remark']);

            $acad_ar_data = array('stud_reg_no' => $row[0]['stud_reg_no'], 'target_degree' => $row[0]['target_degree'], 'processing_mon' => $row[0]['processing_mon'],
                'processing_yr' => $row[0]['processing_yr'], 'attd_approval_by_acad_ar' => $row[0]['attd_approval_by_acad_ar'],
                'attd_approval_by_acad_ar_time' => $row[0]['attd_approval_by_acad_ar_time'], 'attd_approval_by_acad_ar_remark' => $row[0]['attd_approval_by_acad_ar_remark']);
            $acad_da_data = array('stud_reg_no' => $row[0]['stud_reg_no'], 'target_degree' => $row[0]['target_degree'], 'processing_mon' => $row[0]['processing_mon'],
                'processing_yr' => $row[0]['processing_yr'], 'attd_forwarding_by_acad_da' => $row[0]['attd_forwarding_by_acad_da'],
                'attd_forwarding_by_acad_da_time' => $row[0]['attd_forwarding_by_acad_da_time'], 'attd_forwarding_by_acad_da_remark' => $row[0]['attd_forwarding_by_acad_da_remark']);
            $hod_data = array('stud_reg_no' => $row[0]['stud_reg_no'], 'target_degree' => $row[0]['target_degree'], 'processing_mon' => $row[0]['processing_mon'],
                'processing_yr' => $row[0]['processing_yr'], 'attd_approval_by_hod' => $row[0]['attd_approval_by_hod'],
                'attd_approval_by_hod_time' => $row[0]['attd_approval_by_hod_time'], 'attd_approval_by_hod_remark' => $row[0]['attd_approval_by_hod_remark']);

            $fic_data = array('stud_reg_no' => $row[0]['stud_reg_no'], 'target_degree' => $row[0]['target_degree'], 'processing_mon' => $row[0]['processing_mon'],
                'processing_yr' => $row[0]['processing_yr'], 'attd_forward_by_fic' => $row[0]['attd_forward_by_fic'],
                'attd_forward_by_fic_time' => $row[0]['attd_forward_by_fic_time'], 'attd_forward_by_fic_remark' => $row[0]['attd_forward_by_fic_remark']);

            $gd_data = array('stud_reg_no' => $row[0]['stud_reg_no'], 'target_degree' => $row[0]['target_degree'], 'processing_mon' => $row[0]['processing_mon'],
                'processing_yr' => $row[0]['processing_yr'], 'attd_approval_by_gd' => $row[0]['attd_approval_by_gd'],
                'attd_approval_by_gd_time' => $row[0]['attd_approval_by_gd_time'], 'attd_approval_by_gd_remark' => $row[0]['attd_approval_by_gd_remark']);

            $dda_data = array('stud_reg_no' => $row[0]['stud_reg_no'], 'target_degree' => $row[0]['target_degree'], 'processing_mon' => $row[0]['processing_mon'],
                'processing_yr' => $row[0]['processing_yr'], 'days_absent' => $row[0]['days_absent'], 'leave_balance' => $row[0]['leave_balance'],
                'recovery_amt' => $row[0]['recovery_amt'], 'net_amt_payable' => $row[0]['net_amt_payable'], 'attd_forwarding_by_dda' => $row[0]['attd_forwarding_by_dda'],
                'attd_forwarding_by_dda_time' => $row[0]['attd_forwarding_by_dda_time'], 'attd_forwarding_by_dda_remark' => $row[0]['attd_forwarding_by_dda_remark']);


            $this->db->trans_start();
            if ($row[0]['attd_approval_by_acc_ar'] == 'R' || $row[0]['attd_approval_by_acc_ar'] == 'C')
                if (!($this->db->insert('fellow_attd_action_log_acc_ar', $acc_ar_data)))
                    $returntmsg .= $this->db->_error_message() . ",";
            if (!($this->db->insert('fellow_attd_action_log_acc_da', $acc_da_data)))
                $returntmsg .= $this->db->_error_message() . ",";

            if (!($this->db->insert('fellow_attd_action_log_acad_ar', $acad_ar_data)))
                $returntmsg .= $this->db->_error_message() . ",";

            if (!($this->db->insert('fellow_attd_action_log_acad_da', $acad_da_data)))
                $returntmsg .= $this->db->_error_message() . ",";
            if (!($this->db->insert('fellow_attd_action_log_hod', $hod_data)))
                $returntmsg .= $this->db->_error_message() . ",";

            if (!($this->db->insert('fellow_attd_action_log_fic', $fic_data)))
                $returntmsg .= $this->db->_error_message() . ",";

            if (!($this->db->insert('fellow_attd_action_log_gd', $gd_data)))
                $returntmsg .= $this->db->_error_message() . ",";

            if (!($this->db->insert('fellow_attd_log', $dda_data)))
                $returntmsg .= $this->db->_error_message() . ",";
            $data = array(
                'attd_forwarding_by_dda' => 'R',
                'attd_forwarding_by_dda_time' => date('Y-m-d H:i:s'),
                'attd_forwarding_by_dda_id' => '1', // By system
                'attd_forwarding_by_dda_remark' => $this->input->post('remark'),
                'attd_approval_by_gd' => 'C',
                'attd_approval_by_gd_time' => date('Y-m-d H:i:s'),
                'attd_approval_by_gd_id' => '1', // By system
                'attd_approval_by_gd_remark' => $this->input->post('remark'),
                'attd_forward_by_fic' => 'C',
                'attd_forward_by_fic_time' => date('Y-m-d H:i:s'),
                'attd_forward_by_fic_id' => '1',
                'attd_forward_by_fic_remark' => $this->input->post('remark'),
                'attd_approval_by_hod' => 'C',
                'attd_approval_by_hod_time' => date('Y-m-d H:i:s'),
                'attd_approval_by_hod_id' => '1',
                'attd_approval_by_hod_remark' => $this->input->post('remark'),
                'attd_forwarding_by_acad_da' => 'C',
                'attd_forwarding_by_acad_da_time' => date('Y-m-d H:i:s'),
                'attd_forwarding_by_acad_da_id' => '1',
                'attd_forwarding_by_acad_da_remark' => $this->input->post('remark'),
                'attd_approval_by_acad_ar' => 'C',
                'attd_approval_by_acad_ar_time' => date('Y-m-d H:i:s'),
                'attd_approval_by_acad_ar_id' => '1',
                'attd_approval_by_acad_ar_remark' => $this->input->post('remark'),
                'attd_forwarding_by_acc_da' => 'C',
                'attd_forwarding_by_acc_da_time' => date('Y-m-d H:i:s'),
                'attd_forwarding_by_acc_da_id' => '1',
                'attd_forwarding_by_acc_da_remark' => $this->input->post('remark'),
                'attd_approval_by_acc_ar' => 'R',
                'attd_approval_by_acc_ar_time' => date('Y-m-d H:i:s'),
                'attd_approval_by_acc_ar_id' => $this->session->userdata('id'),
                'attd_approval_by_acc_ar_remark' => $this->input->post('remark')
            );
            $this->db->trans_start();
            $this->db->where(array('stud_reg_no' => $this->input->post('stud_reg_no'), 'processing_mon' => $this->input->post('processing_mon'), 'processing_yr' => $this->input->post('processing_yr')));
            if (!$this->db->update('fellow_bill_master', $data))
                $returntmsg .= $this->db->_error_message() . ",";

            $this->db->trans_complete();
            if ($this->db->trans_status() != FALSE) {
                $returntmsg = "success";
            }
        } else {
            $returntmsg .="Internal Error occured.No record found for Rejection";
        }
        return $returntmsg;
    }

    // grp attendance List forward  from acc_ar_ga => rg( Grp  List forwarding starting point)
    public function save_grp_attd_action_by_acc_ar() {
        date_default_timezone_set("Asia/Calcutta");
        $select = $this->db->select('count(1)+1 as slot')->where(array('target_degree' => $this->input->post('target_degree'), 'processing_mon' => (int) $this->input->post('processing_mon'), 'processing_yr' => $this->input->post('processing_yr'), 'attd_forwarding_by_acc_ar' => 'Y'))->get('fellow_grp_transaction');
        if ($select->num_rows()) {
            $row = $select->result_array();
            $data2 = array(
                'grp_attd_fd_by_acc_ar' => 'Y',
                'grp_attd_fd_by_acc_ar_time' => date('Y-m-d H:i:s'),
                'slot' => $row[0]['slot'],
                'attd_approval_by_rg' => 'Y',
                'attd_approval_by_rg_time' => date('Y-m-d H:i:s'),
                'attd_approval_by_rg_id' => $this->session->userdata('id')
            );

            $data = array(
                'target_degree' => $this->input->post('target_degree'),
                'processing_mon' => $this->input->post('processing_mon'),
                'processing_yr' => $this->input->post('processing_yr'),
                'attd_forwarding_by_acc_ar' => 'Y',
                'attd_forwarding_by_acc_ar_time' => date('Y-m-d H:i:s'),
                'slot' => $row[0]['slot'],
                'attd_approval_by_rg' => 'Y',
                'attd_approval_by_rg_time' => date('Y-m-d H:i:s')
            );

            $this->db->trans_start();
            if (!$this->db->insert('fellow_grp_transaction', $data))
                $returntmsg .= $this->db->_error_message() . ",";
            $this->db->where(array('target_degree' => $this->input->post('target_degree'), 'processing_mon' => (int) $this->input->post('processing_mon'), 'processing_yr' => $this->input->post('processing_yr'), 'grp_attd_fd_by_acc_ar' => 'N', 'slot' => 0));
            if (!$this->db->update('fellow_bill_master', $data2))
                $returntmsg .= $this->db->_error_message() . ",";
            // echo $this->db->last_query();
            $this->db->trans_complete();
            if ($this->db->trans_status() != FALSE) {
                $returntmsg = "success";
            }
        } else
            $returntmsg .="Internal Error occured.";
        return $returntmsg;
    }

    // grp attendance List approval  by rg( Grp  List approval after grp attendance List forwarding  by acc_ar_ga  )
    public function save_grp_attd_action_by_rg() {
        date_default_timezone_set("Asia/Calcutta");
        $data2 = array(
            'attd_approval_by_rg' => 'Y',
            'attd_approval_by_rg_time' => date('Y-m-d H:i:s'),
            'attd_approval_by_rg_id' => $this->session->userdata('id')
        );
        $data = array(
            'attd_approval_by_rg' => 'Y',
            'attd_approval_by_rg_time' => date('Y-m-d H:i:s')
        );
        $this->db->trans_start();
        $this->db->where(array('target_degree' => $this->input->post('target_degree'), 'processing_mon' => $this->input->post('processing_mon'), 'processing_yr' => $this->input->post('processing_yr'), 'slot' => $this->input->post('slot'), 'attd_forwarding_by_acc_ar' => 'Y', 'attd_approval_by_rg' => 'N'));
        if (!$this->db->update('fellow_grp_transaction', $data))
            $returntmsg .= $this->db->_error_message() . ",";
        $this->db->where(array('target_degree' => $this->input->post('target_degree'), 'processing_mon' => $this->input->post('processing_mon'), 'processing_yr' => $this->input->post('processing_yr'), 'slot' => $this->input->post('slot'), 'attd_approval_by_acc_ar' => 'Y'));
        if (!$this->db->update('fellow_bill_master', $data2))
            $returntmsg .= $this->db->_error_message() . ",";
        $this->db->trans_complete();
        if ($this->db->trans_status() != FALSE) {
            $returntmsg = "success";
        }

        return $returntmsg;
    }

    public function save_bill_forward_by_acc_da5() {
        date_default_timezone_set("Asia/Calcutta");
        $data = array(
            'bill_forwarding_by_acc_da' => 'Y',
            'bill_forwarding_by_acc_da_time' => date('Y-m-d H:i:s'),
            'bill_forwarding_by_acc_da_id' => $this->session->userdata('id'),
            'bill_amt' => $this->input->post('bill_amt')
        );
        $this->db->where(array('target_degree' => $this->input->post('target_degree'), 'processing_mon' => $this->input->post('processing_mon'), 'processing_yr' => $this->input->post('processing_yr'), 'slot' => $this->input->post('slot'), 'attd_approval_by_rg' => 'Y', 'bill_forwarding_by_acc_da' => 'N'));
        if ($this->db->update('fellow_grp_transaction', $data)) {
            $returntmsg = "success";
        } else {
            $returntmsg = $this->db->_error_message();
        }
        //  echo $this->db->last_query();
        return $returntmsg;
    }

    public function save_bill_forward_by_acc_ar_ga() {
        date_default_timezone_set("Asia/Calcutta");
        $data = array(
            'bill_forwarding_by_acc_ar' => 'Y',
            'bill_forwarding_by_acc_ar_time' => date('Y-m-d H:i:s'),
            'bill_forwarding_by_acc_ar_id' => $this->session->userdata('id')
        );
        $this->db->where(array('target_degree' => $this->input->post('target_degree'), 'processing_mon' => $this->input->post('processing_mon'), 'processing_yr' => $this->input->post('processing_yr'), 'slot' => $this->input->post('slot'), 'attd_approval_by_rg' => 'Y', 'bill_forwarding_by_acc_da' => 'Y', 'bill_forwarding_by_acc_ar' => 'N'));
        if ($this->db->update('fellow_grp_transaction', $data)) {
            $returntmsg = "success";
        } else {
            $returntmsg = $this->db->_error_message();
        }
        return $returntmsg;
    }

    public function save_bill_approved_by_rg() {
        date_default_timezone_set("Asia/Calcutta");
        $data = array(
            'bill_approval_by_rg' => 'Y',
            'bill_approval_by_rg_time' => date('Y-m-d H:i:s'),
            'bill_approval_by_rg_id' => $this->session->userdata('id')
        );
        $this->db->where(array('target_degree' => $this->input->post('target_degree'), 'processing_mon' => $this->input->post('processing_mon'), 'processing_yr' => $this->input->post('processing_yr'), 'slot' => $this->input->post('slot'), 'attd_approval_by_rg' => 'Y', 'bill_forwarding_by_acc_da' => 'Y', 'bill_forwarding_by_acc_ar' => 'Y', 'bill_approval_by_rg' => 'N'));
        if ($this->db->update('fellow_grp_transaction', $data)) {
            $returntmsg = "success";
        } else {
            $returntmsg = $this->db->_error_message();
        }

        return $returntmsg;
    }

    public function save_payorder_forward_by_acc_da5() {
        date_default_timezone_set("Asia/Calcutta");
        $data = array(
            'payorder_fwd_by_acc_da' => 'Y',
            'payorder_fwd_by_acc_da_time' => date('Y-m-d H:i:s'),
            //'ref_no'=>$this->input->post('ref_no'),
            //'ref_no_date'=> date('Y-m-d', strtotime($this->input->post('ref_no_date'))),
            // 'payment_mode'=>$this->input->post('payment_mode'),
            'debit_head_type' => $this->input->post('debit_head_type'),
            'payorder_id' => $this->input->post('target_degree') . "_" . $this->input->post('processing_yr') . "_" . $this->input->post('processing_mon') . "_" . $this->input->post('slot')
        );
        $this->db->where(array('target_degree' => $this->input->post('target_degree'), 'processing_mon' => $this->input->post('processing_mon'), 'processing_yr' => $this->input->post('processing_yr'), 'slot' => $this->input->post('slot'), 'bill_approval_by_rg' => 'Y', 'payorder_fwd_by_acc_da !=' => 'Y', 'ref_no' => null));
        if ($this->db->update('fellow_grp_transaction', $data)) {
            $returntmsg = "success";
        } else {
            $returntmsg = $this->db->_error_message();
        }
        //    echo $this->db->last_query();
        return $returntmsg;
    }

    function save_payorder_forward_log_by_acc_da5() {
        date_default_timezone_set("Asia/Calcutta");
        $returntmsg = "";
        $select = $this->db->select('target_degree,bill_amt,processing_mon,processing_yr,slot,debit_head_type,payorder_fwd_by_acc_da,payorder_fwd_by_acc_da_time,payorder_fwd_by_acc_da_remark')->where(array('target_degree' => $this->input->post('target_degree'), 'processing_mon' => $this->input->post('processing_mon'), 'processing_yr' => $this->input->post('processing_yr'), 'slot' => $this->input->post('slot'), 'bill_approval_by_rg' => 'Y', 'payorder_fwd_by_acc_da !=' => 'N'))->get('fellow_grp_transaction');
        //echo $this->db->last_query();
        if ($select->num_rows()) {
            $row = $select->result_array();
            $this->db->trans_start();
            if (!$this->db->insert('fellow_payorder_action_log_acc_da', $row[0]))
                $returntmsg .= $this->db->_error_message() . ",";
            $data = array(
                'payorder_fwd_by_acc_da' => 'Y',
                'payorder_fwd_by_acc_da_time' => date('Y-m-d H:i:s'),
                'ref_no' => $this->input->post('ref_no'),
                'ref_no_date' => date('Y-m-d', strtotime($this->input->post('ref_no_date'))),
                'payment_mode' => (int) $this->input->post('payment_mode'),
                'debit_head_type' => (int) $this->input->post('debit_head_type'),
                'payorder_fwd_by_acc_da_remark' => $this->input->post('remark')
            );
            $this->db->where(array('target_degree' => $this->input->post('target_degree'), 'processing_mon' => $this->input->post('processing_mon'), 'processing_yr' => $this->input->post('processing_yr'), 'slot' => $this->input->post('slot'), 'bill_approval_by_rg' => 'Y', 'payorder_fwd_by_acc_da !=' => 'N'));
            if (!$this->db->update('fellow_grp_transaction', $data)) {
                $returntmsg .= $this->db->_error_message() . ",";
            }
            $this->db->trans_complete();
            if ($this->db->trans_status() != FALSE) {
                $returntmsg = "success";
            }
        } else {
            $returntmsg .="Internal Error occured.No Pay-Order Found";
        }
        return $returntmsg;
    }

    function save_payorder_approved_by_acc_ar_ga() {
        date_default_timezone_set("Asia/Calcutta");
        $returntmsg = "";
        $data = array(
            'payorder_appd_by_acc_ar' => 'Y',
            'payorder_appd_by_acc_ar_time' => date('Y-m-d H:i:s')
        );
        $data2 = array(
            'final_status' => 'Y',
            'final_status_date' => date('Y-m-d H:i:s')
        );

        $this->db->trans_start();
        $this->db->where(array('target_degree' => $this->input->post('target_degree'), 'processing_mon' => $this->input->post('processing_mon'), 'processing_yr' => $this->input->post('processing_yr'), 'slot' => $this->input->post('slot'), 'bill_approval_by_rg' => 'Y', 'payorder_fwd_by_acc_da' => 'Y', 'payorder_appd_by_acc_ar !=' => 'Y'));
        if (!$this->db->update('fellow_grp_transaction', $data)) {
            $returntmsg .= $this->db->_error_message() . ",";
        }
        $this->db->where(array('target_degree' => $this->input->post('target_degree'), 'processing_mon' => $this->input->post('processing_mon'), 'processing_yr' => $this->input->post('processing_yr'), 'slot' => $this->input->post('slot'), 'attd_approval_by_rg' => 'Y'));
        if (!$this->db->update('fellow_bill_master', $data2))
            $returntmsg .= $this->db->_error_message() . ",";
        $this->db->trans_complete();
        if ($this->db->trans_status() != FALSE) {
            $returntmsg = "success";
        }
        return $returntmsg;
    }

    function save_payorder_approved_log_by_acc_ar_ga() {
        date_default_timezone_set("Asia/Calcutta");
        $returntmsg = "";
        $select = $this->db->select('target_degree,bill_amt,processing_mon,processing_yr,slot,debit_head_type,payorder_appd_by_acc_ar,payorder_appd_by_acc_ar_time,payorder_appd_by_acc_ar_remark')->where(array('target_degree' => $this->input->post('target_degree'), 'processing_mon' => $this->input->post('processing_mon'), 'processing_yr' => $this->input->post('processing_yr'), 'slot' => $this->input->post('slot'), 'bill_approval_by_rg' => 'Y', 'payorder_fwd_by_acc_da' => 'Y', 'payorder_appd_by_acc_ar !=' => 'Y'))->get('fellow_grp_transaction');
        if ($select->num_rows()) {
            $row = $select->result_array();
            $this->db->trans_start();
            if ($row[0]['payorder_appd_by_acc_ar'] == 'R')
                if (!$this->db->insert('fellow_payorder_action_log_acc_ar', $row[0]))
                    $returntmsg .= $this->db->_error_message() . ",";
            $data = array(
                'payorder_appd_by_acc_ar' => 'R',
                'payorder_fwd_by_acc_da' => 'R',
                'payorder_fwd_by_acc_da_remark' => $this->input->post('remark'),
                'payorder_appd_by_acc_ar_time' => date('Y-m-d H:i:s'),
                'payorder_appd_by_acc_ar_remark' => $this->input->post('remark')
            );
            $this->db->where(array('target_degree' => $this->input->post('target_degree'), 'processing_mon' => $this->input->post('processing_mon'), 'processing_yr' => $this->input->post('processing_yr'), 'slot' => $this->input->post('slot'), 'bill_approval_by_rg' => 'Y', 'payorder_fwd_by_acc_da' => 'Y', 'payorder_appd_by_acc_ar !=' => 'Y'));
            if (!$this->db->update('fellow_grp_transaction', $data)) {
                $returntmsg .= $this->db->_error_message() . ",";
            }
            $this->db->trans_complete();
            if ($this->db->trans_status() != FALSE) {
                $returntmsg = "success";
            }
        } else {
            $returntmsg .="Internal Error occured.No Pay-Order Found";
        }
        return $returntmsg;
    }

    public function save_payorder_append_by_acc_da4() {
        date_default_timezone_set("Asia/Calcutta");
        $data = array(
            'payorder_append_by_c_da' => 'Y',
            'payorder_append_by_c_da_time' => date('Y-m-d H:i:s'),
            'ref_no' => $this->input->post('ref_no'),
            'ref_no_date' => date('Y-m-d', strtotime($this->input->post('ref_no_date'))),
            'payment_mode' => $this->input->post('payment_mode'),
            'payorder_append_by_c_da_id' => $this->session->userdata('id'),
        );
        $this->db->where(array('target_degree' => $this->input->post('target_degree'), 'processing_mon' => $this->input->post('processing_mon'), 'processing_yr' => $this->input->post('processing_yr'), 'slot' => $this->input->post('slot'), 'bill_approval_by_rg' => 'Y', 'payorder_fwd_by_acc_da' => 'Y', 'payorder_appd_by_acc_ar' => 'Y', 'payorder_append_by_c_da !=' => 'Y'));
        if ($this->db->update('fellow_grp_transaction', $data)) {
            $returntmsg = "success";
        } else {
            $returntmsg = $this->db->_error_message();
        }
        //    echo $this->db->last_query();
        return $returntmsg;
    }

    function save_payorder_append_log_by_acc_da4() {
        date_default_timezone_set("Asia/Calcutta");
        $returntmsg = "";
        $select = $this->db->select('target_degree,bill_amt,processing_mon,processing_yr,slot,payment_mode,debit_head_type,ref_no,ref_date,payorder_append_by_c_da,payorder_append_by_c_da_time,payorder_append_by_c_da_remark')->where(array('target_degree' => $this->input->post('target_degree'), 'processing_mon' => $this->input->post('processing_mon'), 'processing_yr' => $this->input->post('processing_yr'), 'slot' => $this->input->post('slot'), 'bill_approval_by_rg' => 'Y', 'payorder_fwd_by_acc_da' => 'Y', 'payorder_appd_by_acc_ar' => 'Y', 'payorder_append_by_c_da !=' => 'N'))->get('fellow_grp_transaction');
        //echo $this->db->last_query();
        if ($select->num_rows()) {
            $row = $select->result_array();
            $this->db->trans_start();
            if (!$this->db->insert('fellow_payorder_action_log_c_da', $row[0]))
                $returntmsg .= $this->db->_error_message() . ",";
            $data = array(
                'payorder_append_by_c_da' => 'Y',
                'payorder_append_by_c_da_time' => date('Y-m-d H:i:s'),
                'ref_no' => $this->input->post('ref_no'),
                'ref_no_date' => date('Y-m-d', strtotime($this->input->post('ref_no_date'))),
                'payment_mode' => (int) $this->input->post('payment_mode'),
                'payorder_append_by_c_da_remark' => $this->input->post('remark')
            );
            $this->db->where(array('target_degree' => $this->input->post('target_degree'), 'processing_mon' => $this->input->post('processing_mon'), 'processing_yr' => $this->input->post('processing_yr'), 'slot' => $this->input->post('slot'), 'bill_approval_by_rg' => 'Y', 'payorder_fwd_by_acc_da' => 'Y', 'payorder_appd_by_acc_ar' => 'Y', 'payorder_append_by_c_da !=' => 'N'));
            if (!$this->db->update('fellow_grp_transaction', $data)) {
                $returntmsg .= $this->db->_error_message() . ",";
            }
            $this->db->trans_complete();
            if ($this->db->trans_status() != FALSE) {
                $returntmsg = "success";
            }
        } else {
            $returntmsg .="Internal Error occured.No Pay-Order Found";
        }
        return $returntmsg;
    }

    function getAttd_action_gd_log_by_param($actor) {
        if ($actor == "gd") {
            $this->db->select("DATE_FORMAT(created,'%d %b %Y %r')as created,processing_mon,processing_yr,DATE_FORMAT(attd_approval_by_gd_time,'%d %b %Y %r') as attd_action_dt,attd_approval_by_gd,attd_approval_by_gd_remark as remark", false);
            $this->db->from('fellow_attd_action_log_gd');
        } else if ($actor == "fic") {
            $this->db->select("DATE_FORMAT(created,'%d %b %Y %r')as created,processing_mon,processing_yr,DATE_FORMAT(attd_forward_by_fic_time,'%d %b %Y %r') as attd_action_dt,attd_forward_by_fic,attd_forward_by_fic_remark as remark", false);
            $this->db->from('fellow_attd_action_log_fic');
        } else if ($actor == "hod") {

            $this->db->select("DATE_FORMAT(created,'%d %b %Y %r')as created,processing_mon,processing_yr,DATE_FORMAT(attd_approval_by_hod_time,'%d %b %Y %r') as attd_action_dt,attd_approval_by_hod,attd_approval_by_hod_remark as remark", false);
            $this->db->from('fellow_attd_action_log_hod');
        } else if ($actor == "acad_da") {

            $this->db->select("DATE_FORMAT(created,'%d %b %Y %r')as created,processing_mon,processing_yr,DATE_FORMAT(attd_forwarding_by_acad_da_time,'%d %b %Y %r') as attd_action_dt,attd_forwarding_by_acad_da,attd_forwarding_by_acad_da_remark as remark", false);
            $this->db->from('fellow_attd_action_log_acad_da');
        } else if ($actor == "acad_ar") {

            $this->db->select("DATE_FORMAT(created,'%d %b %Y %r')as created,processing_mon,processing_yr,DATE_FORMAT(attd_approval_by_acad_ar_time,'%d %b %Y %r') as attd_action_dt,attd_approval_by_acad_ar,attd_approval_by_acad_ar_remark as remark", false);
            $this->db->from('fellow_attd_action_log_acad_ar');
        } else if ($actor == "acc_da") {

            $this->db->select("DATE_FORMAT(created,'%d %b %Y %r')as created,processing_mon,processing_yr,DATE_FORMAT(attd_forwarding_by_acc_da_time,'%d %b %Y %r') as attd_action_dt,attd_forwarding_by_acc_da,attd_forwarding_by_acc_da_remark as remark", false);
            $this->db->from('fellow_attd_action_log_acc_da');
        } else if ($actor == "acc_ar") {

            $this->db->select("DATE_FORMAT(created,'%d %b %Y %r')as created,processing_mon,processing_yr,DATE_FORMAT(attd_approval_by_acc_ar_time,'%d %b %Y %r') as attd_action_dt,attd_approval_by_acc_ar,attd_approval_by_acc_ar_remark as remark", false);
            $this->db->from('fellow_attd_action_log_acc_ar');
        }
        $this->db->where(array('stud_reg_no' => $this->input->post('stud_reg_no'), 'processing_mon' => (int) $this->input->post('processing_mon'), 'processing_yr' => $this->input->post('processing_yr')));
        $this->db->order_by('created', 'desc');
        $query = $this->db->get();
        // echo $this->db->last_query();
        if ($query->num_rows() > 0)
            return $query->result_array();
        else {
            return array('result' => 'Failed', 'error' => 'No Record Found');
        }
    }

    function getPayorder_action_log_by_param($actor) {
        if ($actor == "acc_da") {

            $sql = " select A.*,pmm.name , dh.name as debit_head_name  from (select DATE_FORMAT(log.created,'%d %b %Y %r') as created,log.target_degree,log.processing_mon,log.processing_yr,log.slot,log.payment_mode,log.debit_head_type,log.ref_no,DATE_FORMAT(log.ref_no_date,'%d %b %Y') as ref_no_date,DATE_FORMAT(log.payorder_fwd_by_acc_da_time,'%d %b %Y %r') as action_dt,payorder_fwd_by_acc_da as status,log.payorder_fwd_by_acc_da_remark as remark
       from  fellow_payorder_action_log_acc_da  log
        where  log.target_degree = ? and log.processing_mon=? and log.processing_yr=? and log.slot=?
        )A left join fellow_payment_mode_master pmm  on A.payment_mode = pmm.id
            left join fellow_debit_head_master dh  on A.debit_head_type= dh.id
             order by A.created desc ";
        } else if ($actor == "acc_ar_ga") {
            $sql = "select A.*,pmm.name , dh.name as  debit_head_name from ( select DATE_FORMAT(log.created,'%d %b %Y %r') as created,log.target_degree,log.processing_mon,log.processing_yr,log.slot,log.payment_mode,log.debit_head_type,log.ref_no,DATE_FORMAT(log.ref_no_date,'%d %b %Y') as ref_no_date,DATE_FORMAT(log.payorder_appd_by_acc_ar_time,'%d %b %Y %r') as action_dt,log.payorder_appd_by_acc_ar as status,log.payorder_appd_by_acc_ar_remark as remark
             from
            fellow_payorder_action_log_acc_ar  log
            where  `log`.`target_degree` = ? and log.processing_mon=? and log.processing_yr=? and log.slot=?
            )A
            left join fellow_payment_mode_master pmm  on A.payment_mode = pmm.id
            left join fellow_debit_head_master dh  on A.debit_head_type= dh.id
             order by A.created desc ";
        } else if ($actor == "acc_da4") {
            $sql = "select A.*,pmm.name , dh.name as  debit_head_name from ( select DATE_FORMAT(log.created,'%d %b %Y %r') as created,log.target_degree,log.processing_mon,log.processing_yr,log.slot,log.payment_mode,log.debit_head_type,log.ref_no,DATE_FORMAT(log.ref_no_date,'%d %b %Y') as ref_no_date,DATE_FORMAT(log.payorder_append_by_c_da_time,'%d %b %Y %r') as action_dt,log.payorder_append_by_c_da as status,log.payorder_append_by_c_da_remark as remark
             from
            fellow_payorder_action_log_c_da  log
            where  `log`.`target_degree` = ? and log.processing_mon=? and log.processing_yr=? and log.slot=?
            )A
            left join fellow_payment_mode_master pmm  on A.payment_mode = pmm.id
            left join fellow_debit_head_master dh  on A.debit_head_type= dh.id
             order by A.created desc ";
        }
        $secure_array = array($this->input->post('target_degree'), $this->input->post('processing_mon'), $this->input->post('processing_yr'), $this->input->post('slot'));
        $query = $this->db->query($sql, $secure_array);
        //echo $this->db->last_query();
        if ($query->num_rows() > 0)
            return $query->result_array();
        else {
            return array('result' => 'Failed', 'error' => 'No Record Found');
        }
    }

    function get_eligible_fellow_bill_list_by_slot($actor, $target_degree, $mon, $yr, $slot) {
        if (in_array("acc_da5", $this->session->userdata('auth')) && $actor == "acc_da") {
            $where_clause_var = "";
            $secure_array = array($target_degree, $mon, $yr, "Y", $slot);
        } else if (in_array("rg", $this->session->userdata('auth')) && $actor == "rg") {
            //$where_clause_var=" and  final_status=? ";
            $where_clause_var = "";
            $secure_array = array($target_degree, $mon, $yr, "Y", $slot);
        } else if (in_array("acc_ar_ga", $this->session->userdata('auth')) && $actor == "acc_ar_ga") {
            //$where_clause_var=" and  final_status=? ";
            $where_clause_var = "";
            $secure_array = array($target_degree, $mon, $yr, "Y", $slot);
        } else if (in_array("acc_da4", $this->session->userdata('auth')) && $actor == "acc_da4") {
            $where_clause_var = " and  final_status=? ";
            $secure_array = array($target_degree, $mon, $yr, "Y", "Y", $slot);
        }

        $sql = "select  A.*,concat_ws(' ', u.salutation, u.first_name, u.middle_name, u.last_name) as fellow_name,stother.account_no from
    (select  fbm.target_degree,fbm.stud_reg_no,fbm.dept_id,fbm.net_amt_payable from  fellow_bill_master fbm  where  `fbm`.`target_degree` = ?
    and fbm.processing_mon=? and fbm.processing_yr=? and attd_approval_by_rg = ?  " . $where_clause_var . " and slot=?)A
    JOIN  stu_other_details stother ON  `stother`.`admn_no` =  `A`.`stud_reg_no`
    LEFT JOIN `user_details` u ON `u`.`id`=`A`.`stud_reg_no` group by A.dept_id,A.stud_reg_no ";
        $query = $this->db->query($sql, $secure_array);

        if ($query->num_rows() > 0)
            return $query->result_array();
        else {
            return array('result' => 'Failed', 'error' => 'No Record Found');
        }
    }

    function getAttdEditHistoryList_by_param() {
        $this->db->select("DATE_FORMAT(created,'%d %b %Y %r')as created,processing_mon,processing_yr,days_absent ,leave_balance,recovery_amt,net_amt_payable,DATE_FORMAT(attd_forwarding_by_dda_time,'%d %b %Y %r') as attd_fd_dt,attd_forwarding_by_dda_remark as remark", false);
        $this->db->from('fellow_attd_log');
        $this->db->where(array('stud_reg_no' => $this->input->post('stud_reg_no'), 'processing_mon' => $this->input->post('processing_mon'), 'processing_yr' => $this->input->post('processing_yr')));
        $this->db->order_by('created', 'desc');
        $query = $this->db->get();
        // echo $this->db->last_query();
        if ($query->num_rows() > 0)
            return $query->result_array();
        else {
            return array('result' => 'Failed', 'error' => 'No Record Found');
        }
    }

    //******************@Module :Guide List Population ************************//
    /**
     * @desc: Get faculty(short code=>ft) List those are guide to be populate in list box in the form
     *
     * @access	public
     * @param	String	      (faculty(short code=>ft))
     * @param	String	      (department_id)
     * @return	string array  (return Faculty List)
     */
    function get_GuideList_by_param($type = '', $dept_id = '') {
        if (isset($type) && isset($dept_id)) {
            $this->db->select('u.id,u.salutation,u.first_name,u.middle_name,u.last_name');
            $this->db->from('emp_basic_details emp');
			$this->db->join('users v', 'v.id = emp.emp_no and v.status="A"');
            $this->db->join('user_details u', 'u.id = emp.emp_no  and emp.auth_id="' . $type . '"  and u.dept_id="' . $dept_id . '"  order by u.first_name');
            $query = $this->db->get();
            if ($query->num_rows() > 0)
                return $query->result_array();
            else
                return FALSE;
        } else
            return FALSE;
    }

}

?>
