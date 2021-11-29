<?php
/* Result Declaration process
 * Copyright (c) IIT-ISM dhanbad * 
 * @category   php 
 * @package    result_declaration model
 * @copyright  Copyright (c) 2014 - 2015 Ism dhanbad
 * @license    http://www.gnu.org/licenses/old-licenses/lgpl-2.1.txt    LGPL
 * @version    ##0.1##, #26/11/15#
 * @Author     Ritu raj<rituraj00@rediffmail.com>
 */?>
<?php 
class Result_declaration_model_old extends CI_Model {

   
 
    function __construct() {
        // Call the Model constructor
        parent::__construct();
       
         
    }

    function get_depart_details($sy, $sess, $did, $etype, $dec_type = 'F', $sec = null) {

        if ($etype == 'regular' || $etype == 'jrf' || $etype == 'jrf_spl' || $etype == 'prep') {
            if ($did == 'all' && $etype == 'prep') {
                $did = 'PREP';
                $prep_replace = "";
                $prep_replace2 = "";
            } else {
                $prep_replace = "  and a.dept_id='" . $did . "'";
                $prep_replace2 = " and A.dept_id=B.dept_id ";
            }
            if ($etype == 'regular') {
                $str_crs_replace = " and a.course_id!='honour' and a.course_id!='jrf' and  a.course_id!='prep' ";
                $semester_replace1 = " , semester ";
                $semester_replace2 = " and A.semester=B.semester ";
                $semester_replace3 = ",A.semester ";
            } else if ($etype == 'jrf' || $etype == 'jrf_spl' || $etype == 'prep') {
                $str_crs_replace = ($etype == 'prep' ? " and a.course_id='prep'" : " and a.branch_id = 'jrf' and a.course_id='jrf'" );
                $semester_replace1 = "";
                $semester_replace2 = "";
                $semester_replace3 = "";
            }

            if (in_array("hod", $this->session->userdata('auth')) or in_array("ft", $this->session->userdata('auth'))) {
                $left = 'inner ';
                $replace = " status=1   and  type= '" . $dec_type . "'  and ";
                if ($did == 'comm') {
                    $replace2_1 = ',section';
                    $replace2_2 = ',A.section';
                    $replace2_3 = ' ,section ';
                    $replace2_4 = ' and A.section=B.section ';
                    if (( $sec != NULL && $sec == 'all') or ( $sec == NULL ))
                        $where_add = "";
                    if ($sec != NULL && $sec != 'all')
                        $where_add = "  and  section = '" . $sec . "' ";
                }else {
                    $replace2_1 = '';
                    $replace2_2 = '';
                    $replace2_3 = '';
                    $replace2_4 = '';
                    $where_add = '';
                }
            } else if (in_array("exam_dr", $this->session->userdata('auth'))) {
                $left = 'inner ';


                if ($did == 'comm') {
                    $replace2_1 = ',section';
                    $replace2_2 = ',A.section';
                    $replace2_3 = ' ,section ';
                    $replace2_4 = ' and A.section=B.section ';
                    if (( $sec != NULL && $sec == 'all') or ( $sec == NULL ))
                        $where_add = "";
                    if ($sec != NULL && $sec != 'all')
                        $where_add = "  and  section = '" . $sec . "' ";
                }else {
                    $replace2_1 = '';
                    $replace2_2 = '';
                    $replace2_3 = '';
                    $replace2_4 = '';
                    $where_add = '';
                }
            }
            if ($dec_type == 'W') {
                $replace = " type= 'F'  and ";
                $myquery = " select A.*, null as status, null as id, null as published_on  from  
               (select a.*, b.name  as course_nm,  c.name  as branch_nm from subject_mapping a 
               inner join  cs_courses b on a.course_id=b.id
               inner join  cs_branches c on a.branch_id=c.id
               where a.session_year='" . $sy . "' and a.session='" . $sess . "' " . $prep_replace . "  " . $where_add . "  " . $str_crs_replace . "  group by  a.dept_id,a.course_id,a.branch_id " . $semester_replace1 . " " . $replace2_1 . "  )A   
               where not exists (select B.id  from result_declaration_log B 
               where " . $replace . "  B.exam_type='" . $etype . "' and A.session_year=B.s_year and A.session=B.session " . $prep_replace2 . " and A.course_id=B.course_id and A.branch_id=B.branch_id 
               " . $semester_replace2 . "  " . $replace2_4 . "   
                ) order by A.course_nm,A.branch_nm " . $semester_replace3 . " " . $replace2_2 . "";
            } else {
                $replace = " type= '" . $dec_type . "'  and ";
                $myquery = "select A.*, B.status,B.id,B.published_on from  
(select a.*, b.name as course_nm,  c.name as branch_nm from subject_mapping a 
inner join  cs_courses b on a.course_id=b.id
inner join  cs_branches c on a.branch_id=c.id
where a.session_year='" . $sy . "' and a.session='" . $sess . "'
" . $prep_replace . "  " . $where_add . "  " . $str_crs_replace . "  group by  a.dept_id,a.course_id,a.branch_id " . $semester_replace1 . " " . $replace2_1 . "    )A
" . $left . "  join 
(select  id,published_on,s_year,dept_id,course_id,branch_id,semester,session ,status " . $replace2_3 . " from result_declaration_log  where   " . $replace . "  exam_type='" . $etype . "')B

 on A.session_year=B.s_year and A.session=B.session " . $prep_replace2 . "  and A.course_id=B.course_id and A.branch_id=B.branch_id " . $semester_replace2 . "  " . $replace2_4 . "   order by A.course_nm,A.branch_nm " . $semester_replace3 . " " . $replace2_2 . "
";
            }
        } else if ($etype == 'other' || $etype == 'spl' || $etype == 'espl') {
            if ($etype == 'other')
                $type = 'O';
            else if ($etype == 'spl')
                $type = 'S';
            else if ($etype == 'espl')
                $type = 'E';

            if ($dec_type == 'W') {
                /* if (in_array("hod", $this->session->userdata('auth')) or in_array("ft", $this->session->userdata('auth'))) {                
                  $replace = " B.status=1   and  B.type= '".$dec_type."'  and ";
                  } else */ if (in_array("exam_dr", $this->session->userdata('auth'))) {
                    //$replace = " B.type= '".$dec_type."'  and ";
                    $replace = " B.type= 'F'  and ";
                }
                $myquery = " select A.course as course_id,A.branch as branch_id,A.semester,A.dept,A.session_yr,A.session, A.course_nm,A.branch_nm,null as status, null as id, null as published_on   from  
				(select x.*,b.name as course_nm,c.name as branch_nm from 
(SELECT f.*
FROM final_semwise_marks_foil f
union
SELECT y.* from 
   alumni_final_semwise_marks_foil y)x
  
               inner join  cs_courses b on x.course=b.id  
               inner join  cs_branches c on x.branch=c.id  and  x.session_yr='" . $sy . "' AND x.session='" . $sess . "' AND x.dept='" . $did . "'  AND x.`type`='" . $type . "'   
               GROUP BY x.session_yr,x.session,x.dept,x.course,x.branch,x.semester)A   
               where not exists (select B.id  from result_declaration_log B 
               where " . $replace . "  B.exam_type='" . $etype . "' and A.session_yr=B.s_year and A.session=B.session and A.dept=B.dept_id  and A.course=B.course_id and A.branch=B.branch_id  and A.semester=B.semester  
              ) order by A.course_nm,A.branch_nm,A.semester";
            } else {
                if (in_array("hod", $this->session->userdata('auth')) or in_array("ft", $this->session->userdata('auth'))) {
                    $left = 'inner ';
                    $replace = " status=1   and  type= '" . $dec_type . "'  and ";
                } else if (in_array("exam_dr", $this->session->userdata('auth'))) {
                    $left = 'inner ';
                    $replace = " type= '" . $dec_type . "'  and ";
                }

                $myquery = "  select A.course as course_id,A.branch as branch_id,A.semester,A.dept,A.session_yr,A.session, A.course_nm,A.branch_nm,B.status,B.id ,B.published_on  from  
				(select x.*,b.name as course_nm,c.name as branch_nm from
(SELECT f.*
FROM final_semwise_marks_foil f
union
SELECT y.* from 
   alumni_final_semwise_marks_foil y)x
  
                 inner join  cs_courses b on x.course=b.id inner join  cs_branches c on x.branch=c.id  and  x.session_yr='" . $sy . "' AND x.session='" . $sess . "' AND x.dept='" . $did . "'  AND x.`type`='" . $type . "'    GROUP BY x.session_yr,x.session,x.dept,x.course,x.branch,x.semester)A   
                 " . $left . "  join 
                (select id,published_on,s_year,dept_id,course_id,branch_id,semester,session ,status  from result_declaration_log  where   " . $replace . "  exam_type='" . $etype . "')B
                 on A.session_yr=B.s_year and A.session=B.session and A.dept=B.dept_id  and A.course=B.course_id and A.branch=B.branch_id  and A.semester=B.semester order by A.course_nm,A.branch_nm,A.semester ";
            }
        }
        $query = $this->db->query($myquery);
       //    echo $this->db->last_query(); die();

        if ($query->num_rows() > 0) {
            return $query->result();
        } else {
            return FALSE;
        }
    }

    function insert_update_result_declaration($data) {
        //print_r($data); die();
        $arr = array('s_year' => $data['s_year'],
            'session' => $data['session'],
            'upper(dept_id)' => strtoupper($data['dept_id']),
            'upper(course_id)' => strtoupper($data['course_id']),
            'upper(branch_id)' => strtoupper($data['branch_id']),
            'upper(exam_type)' => strtoupper($data['exam_type']),
            // 'status' =>'0',
            'semester' => $data['semester'],
                //'type'=>'W'   
        );
        if ($data['dept_id'] == 'comm')
            $arr['section'] = $data['section'];

        $this->db->select('id');
        $this->db->from('result_declaration_log');
        $this->db->where($arr);
        $this->db->order_by("id", "desc");
        $this->db->limit(1);

        $select = $this->db->get();
        if ($select->num_rows()) {
            $row = $select->result_array();

            $data1 = array('published_by' => $data['published_by'],
                'published_on' => $data['published_on'],
                'actual_published_on' => $data['actual_published_on'],
                'status' => $data['status'],
                'type' => $data['type']);

            $this->db->where(array_merge($arr, array('id' => $row[0]['id'])));
            if (!$this->db->update('result_declaration_log', $data1))
            //$returntmsg .= $this->db->_error_message() . ",";      
                return /* $this->db->last_query(); */false;
            else
                return true;
        }else {
            //print_r($data); 
            if (!$this->db->insert('result_declaration_log', $data))
            //<!-- added for pre-with held condition implementation @9-dec-16-->
                return /* $this->db->last_query(); */ false;
            else
                return true;
        }
    }

    function insert_result_declaration($data, $rdec_type = null) {
        //pint_r($data); die();
        $arr = array('s_year' => $data['s_year'],
            'session' => $data['session'],
            'upper(dept_id)' => strtoupper($data['dept_id']),
            'upper(course_id)' => strtoupper($data['course_id']),
            'upper(branch_id)' => strtoupper($data['branch_id']),
            'upper(exam_type)' => strtoupper($data['exam_type']),
            'semester' => $data['semester']
        );
        if ($data['dept_id'] == 'comm')
            $arr['section'] = $data['section'];
        $this->db->select('id');
        $this->db->from('result_declaration_log');
        $this->db->where($arr);
        $this->db->order_by("id", "desc");
        $this->db->limit(1);
        $select = $this->db->get();
        if ($select->num_rows()) {
            $row = $select->result_array();
            return $row[0]['id'];
        } else {
            if (!($this->db->insert('result_declaration_log', $data)))
                return FALSE;
            else
                return $this->db->insert_id();
        }
    }

    function get_published_status($sy, $sess, $did, $cid, $bid, $sem, $etype, $grp, $section) {
        /* $myquery = "select * from result_declaration_log where s_year='".$sy."'
          and session='".$sem."' and exam_type='".$et."' and dept_id='".$did."' "; */
        //echo 'did'.$did;
        if ($did == "comm") {
            $where_add = " and  section= '" . $section . "' ";
        } else {
            $where_add = "";
        }
        if ($cid != 'jrf') {
            $semester_replace = " and semester='" . $sem . "' ";
        } else {
            $semester_replace = "";
        }

        $myquery = "select * from result_declaration_log where s_year='" . $sy . "'
and session='" . $sess . "' and upper(dept_id)='" . strtoupper($did) . "'  and upper(course_id)='" . strtoupper($cid) . "'
and upper(branch_id)='" . strtoupper($bid) . "' " . $semester_replace . "  and upper(exam_type)='" . strtoupper($etype) . "'  " . $where_add . "  order by id desc limit 1";


        $query = $this->db->query($myquery);
//echo $this->db->last_query(); die();
        if ($query->num_rows() > 0) {
            return $query->result_array();
        } else {
            return FALSE;
        }
    }

    function update_decl_status($id) {
        $myquery = "update result_declaration_log set status=0 ,type='W' where id=" . $id;

        $query = $this->db->query($myquery);

        if ($query->num_rows() > 0) {
            return $query->result();
        } else {
            return FALSE;
        }
    }

    /// Get stop Stu

    function get_stop_stu($id, $rdec_type = null) {
        //echo $id; die();
        if ($rdec_type == 'prerd')
            $rdec_type = 1;
        else if ($rdec_type == 'postrd')
            $rdec_type = 2;
        else
            $rdec_type = null;

        if ($rdec_type <> '' and $rdec_type <> null) {
            $replace = " and rdec_type=? ";
            $arr = array($id, $rdec_type);
        } else {
            $replace = "";
            $arr = array($id);
        }
        $q = "select * from result_declaration_log_partial_details where res_dec_id=? " . $replace . "  and (status='M' OR status='P')";
        $q = $this->db->query($q, $arr);
        //echo $this->db->last_query();
        if ($q->num_rows() > 0) {
            return $q->result_array();
        }
        return array();
    }

    function get_stop_stu_by_id($id) {
        $q = $this->db->get_where('result_declaration_log_partial_details', array('id' => $id));
        if ($q->num_rows() > 0) {
            return $q->row();
        }
    }

    function get_result_for_view($sy, $sess, $etype, $deptid, $sem, $cid, $bid, $sec_name, $stop_student_status, $admn_no = null) {
        $this->load->model('student_grade_sheet/student_grade_model', '', TRUE);
        //   if($sess=='Summer') $table_inner_join=" reg_summer_form "; else $table_inner_join= "reg_regular_form " ;
       // if($admn_no) echo     $sy .','. $sess.','.  $etype.','.  $deptid.','. $sem.','. $cid.','. $bid.','. $sec_name. ','. $admn_no; 
		//2016-2017,Summer,R,comm,1,b.tech,pe,,16je002296
        $admn_no = preg_replace('/\s+/', '', $admn_no);
        
        if ($admn_no == null) {
            $where_add2 = "";
        } else {
            if (substr_count($admn_no, ',') > 0) {
                $admn_no = "'" . implode("','", explode(',', $admn_no)) . "'";
                $where_add2 = " and a.admn_no in(" . $admn_no . ") ";
            } else {
                $where_add2 = " and a.admn_no='" . $admn_no . "' ";
            }
        }
        if ($etype == 'R' && $sess <> 'Summer') {
            if ($cid == 'minor') {
                // $where_add1 = " and  lower(rf.course_id)='" . strtolower($cid) . "'  and   lower(rf.branch_id)='" . strtolower($bid) . "'";
                $myquery = "	 
                               SELECT  CONCAT_WS(' ',COALESCE(null , alu_u.first_name,u.first_name), COALESCE(null ,alu_u.middle_name, u.middle_name), COALESCE(null , alu_u.last_name, u.last_name)) AS st_name , dpt.name as dept_name,  G.*
                               FROM (
                                    SELECT x.admn_no,
		                      SUM(IF((x.course_id= 'minor'), x.totcrdpts, NULL)) AS core_tot, 
   	 SUM(IF((x.course_id= 'minor'), x.credit_hours, NULL)) AS core_crdthr,
  	    IF 
			 (COUNT(DISTINCT CASE WHEN (x.course_id= 'minor' AND x.grade = 'F') THEN 1 END),'INC', 
			 (SUM(IF((x.course_id= 'minor'), x.totcrdpts, NULL)) / SUM(IF((x.course_id='minor'), x.credit_hours, NULL)))) AS core_GPA, 
         /*IF(COUNT(DISTINCT CASE WHEN (x.course_id ='minor' AND x.grade = 'F') THEN 1 END),'FAIL', 'PASS') AS core_status,*/
         IF(COUNT(DISTINCT CASE WHEN (x.course_id= 'minor' and x.grade = 'F') THEN 1  END),'FAIL',(IF((sum( IF(( x.course_id= 'minor'  ), x.totcrdpts,null) )/sum( IF((x.course_id= 'minor'  ), x.credit_hours,null) )  )<5,'FAIL','PASS')) )  as  core_status,
		     /*SUM(IF ((x.course_id= 'minor' AND (x.grade = 'F' or x.grade is null) ), 1, 0)) AS count_core_failed_sub, */
                     SUM(IF ((x.course_id= 'minor' AND x.grade = 'F'  ), 1, 0)) AS count_core_failed_sub, 
                   /*SUM(IF ((x.course_id= 'minor' AND (x.grade <> 'F' and  x.grade  is not  null) ), 1, 0)) AS count_core_passed_sub,*/
                   SUM(IF ((x.course_id= 'minor' AND x.grade <> 'F'  ), 1, 0)) AS count_core_passed_sub,
                  /*GROUP_CONCAT((IF((x.course_id= 'minor' AND( x.grade = 'F' or x.grade is null)),x.sub, NULL)) SEPARATOR ',') AS core_fail_sub_list,*/
                  GROUP_CONCAT((IF((x.course_id= 'minor' AND x.grade = 'F' ),x.sub, NULL)) SEPARATOR ',') AS core_fail_sub_list,
                 /*GROUP_CONCAT( concat( x.sub,(',totcrdpts,')','(',x.grade,')') SEPARATOR ',') as sublist*/
          GROUP_CONCAT((CONCAT( REPLACE(x.name, ',', '') ,'#',x.sub,'#',COALESCE(x.theory,''),'#',COALESCE(x.sessional,''),'#',COALESCE(x.practical,''),'#',COALESCE(x.total,''),'#',x.totcrdpts,'#',x.credit_hours,'#',CAST(x.LTP AS CHAR CHARACTER SET utf8),'#',x.grade)) SEPARATOR ',') AS sublist
                 ,x.course_id,x.dept_id ,x.session,x.session_year 
                 
		 
		 
		 
FROM (
SELECT grade_points.points, (grp.credit_hours*grade_points.points) AS totcrdpts, grp.*
FROM (
SELECT A.admn_no,A.grade,c.name,c.subject_id AS sub,c.credit_hours, CONCAT(c.lecture,'-',c.tutorial,'-',c.practical) as LTP,d.aggr_id,d.semester,d.sequence, e.course_id,e.dept_id,A.session,A.session_year,A.sessional,A.theory,A.practical,A.total
FROM ((
SELECT a.admn_no,a.grade,b.subject_id, b.sub_map_id,b.session,b.session_year,a.sessional,a.theory,a.practical,a.total
FROM marks_subject_description AS a
INNER JOIN marks_master AS b ON a.marks_master_id=b.id
WHERE  b.status='Y'  and b.session_year='" . $sy . "' and  b.session='" . $sess . "' and b.`type`='" . $etype . "'  " . $where_add2 . " )
union 
(
SELECT a.admn_no,a.grade,b.subject_id, b.sub_map_id,b.session,b.session_year,a.sessional,a.theory,a.practical,a.total
FROM alumni_marks_subject_description AS a
INNER JOIN marks_master AS b ON a.marks_master_id=b.id
WHERE  b.status='Y'  and b.session_year='" . $sy . "' and  b.session='" . $sess . "' and b.`type`='" . $etype . "'  " . $where_add2 . " )
) A
INNER JOIN subjects AS c ON A.subject_id=c.id
INNER JOIN course_structure AS d ON A.subject_id=d.id
INNER JOIN subject_mapping AS e ON A.sub_map_id = e.map_id
WHERE e.dept_id='" . $deptid . "' and e.semester='" . $sem . "'  AND ( e.course_id='" . $cid . "' ) and e.branch_id='" . $bid . "'
GROUP BY A.admn_no,A.subject_id)grp
LEFT JOIN grade_points ON grade_points.grade=grp.grade
ORDER BY grp.admn_no, grp.semester,grp.sequence ASC) x
GROUP BY x.admn_no
)G
LEFT JOIN user_details u ON u.id=G.admn_no
  left join alumni_user_details alu_u on alu_u.id=G.admn_no 
LEFT JOIN departments dpt ON dpt.id=u.dept_id 
INNER join ( select hf2.admn_no,hm_minor_details.branch_id from hm_form hf2  
                    inner join hm_minor_details on hm_minor_details.form_id=hf2.form_id 
                          and hm_minor_details.offered='1' and hf2.minor='1' and hf2.minor_hod_status='Y'
          )K 
on G.admn_no=K.admn_no
left join reg_regular_form rf  on rf.admn_no=G.admn_no and rf.session_year=G.session_year and rf.session=G.session  and rf.acad_status='1' and rf.hod_status='1'  and
	  rf.semester='" . $sem . "' 
left join alumni_reg_regular_form alu_rf   on alu_rf.admn_no=G.admn_no and alu_rf.session_year=G.session_year and alu_rf.session=G.session  and alu_rf.acad_status='1' and alu_rf.hod_status='1'  and
	  alu_rf.semester='" . $sem . "' 

ORDER BY G.admn_no
";
            } else {

                    // added section case when since  for repaeter we store old record to ext
                if ($deptid == 'comm') {
                    $where_add = " and  e.section='" . $sec_name . "'   inner join  stu_section_data ssd on ssd.admn_no=A.admn_no and ( case   when (ssd.section ='' or ssd.section is null)   then  ssd.ext=e.section else ssd.section=e.section end) and ssd.session_year=A.session_year ";
                    $where_add1 = '';
                } else {
                    $where_add = "";
                    //$where_add1 = "and  rf.course_id='" . $cid . "'  and rf.branch_id='" . $bid . "'";
                    $where_add1 = "and  lower(rf.course_id)='" . strtolower($cid) . "'  and   lower(rf.branch_id)='" . strtolower($bid) . "'";
					$where_add_alu = "and  lower(alu_rf.course_id)='" . strtolower($cid) . "'  and   lower(alu_rf.branch_id)='" . strtolower($bid) . "'";
                }


                if ($sem >= 5 and $cid == 'b.tech')
                    $replace = "(e.course_id='" . $cid . "' or  e.course_id='honour')";
                else
                    $replace = "e.course_id='" . $cid . "' ";

                $myquery = " select EEE.* from (
  select 
 /*(CASE WHEN (f.status = 'FAIL' and f.hstatus<>'Y' ) THEN 'INC'  WHEN (f.core_status = 'FAIL' and f.hstatus='Y' ) THEN 'INC'      ELSE (CASE WHEN ((f.hstatus='Y')) THEN f.core_cgpa ELSE f.cgpa END) END) AS cgpa,
  (CASE WHEN (f.hstatus='Y') then (CASE WHEN (f.status = 'FAIL') THEN 'INC' ELSE f.cgpa END) else 'N/A' end) AS H_cgpa
  
  ,f.hstatus,
  (case  when(f.hstatus<>'Y' and  (G.GPA_with_H2='INC' or G.GPA_with_H2 is null)) then   'N/A'  else  G.GPA_with_H2  end )as GPA_with_H  ,
  (case  when(f.hstatus<>'Y' and G.H_status2='FAIL') then   'N/A'  else  G.H_status2  end )as H_status,
  concat_ws(' ',u.first_name, u.middle_name, u.last_name)  AS st_name */ 
 
 (CASE WHEN ( COALESCE(null , alu_f.`status`,f.status) = 'FAIL' AND   COALESCE(null , alu_f.hstatus , f.hstatus ) <>'Y') THEN 'INC' WHEN (  COALESCE(null , alu_f.core_status,f.core_status) = 'FAIL' AND  COALESCE(null , alu_f.hstatus , f.hstatus )='Y') THEN 'INC' ELSE (CASE WHEN (( COALESCE(null , alu_f.hstatus , f.hstatus )='Y')) THEN COALESCE(null ,alu_f.core_cgpa, f.core_cgpa) ELSE COALESCE(null ,alu_f.cgpa, f.cgpa) END) END) AS cgpa,
 (CASE WHEN ( COALESCE(null , alu_f.hstatus , f.hstatus )='Y') THEN (CASE WHEN ( COALESCE(null , alu_f.`status`,f.status)  = 'FAIL') THEN 'INC' ELSE COALESCE(null ,alu_f.cgpa, f.cgpa) END) ELSE 'N/A' END) AS H_cgpa,
 COALESCE(null , alu_f.hstatus , f.hstatus )  as hstatus, 
 (CASE WHEN(COALESCE(null , alu_f.hstatus , f.hstatus )<>'Y' AND (G.GPA_with_H2='INC' OR G.GPA_with_H2 IS NULL)) THEN 'N/A' ELSE G.GPA_with_H2 END) AS GPA_with_H, 
 (CASE WHEN(COALESCE(null , alu_f.hstatus , f.hstatus )<>'Y' AND G.H_status2='FAIL') THEN 'N/A' ELSE G.H_status2 END) AS H_status, 
  
  CONCAT_WS(' ',COALESCE(null , alu_u.first_name,u.first_name), COALESCE(null ,alu_u.middle_name, u.middle_name), COALESCE(null , alu_u.last_name, u.last_name)) AS st_name ,G.* from
( select  x.admn_no, 	 
  sum( IF((x.course_id!= 'honour' and x.course_id!= 'minor'  ), x.totcrdpts,null) )  as core_tot,	    
  sum( IF((x.course_id!= 'honour' and x.course_id!= 'minor'  ), x.credit_hours,null) )  as core_crdthr,	    

 IF(COUNT(DISTINCT CASE WHEN (x.course_id!= 'honour' and x.course_id!= 'minor'  and x.grade = 'F') THEN 1  END),'INC',
	(  sum( IF((x.course_id!= 'honour' and x.course_id!= 'minor'  ), x.totcrdpts,null) ) / sum( IF((x.course_id!= 'honour' and x.course_id!= 'minor'  ), x.credit_hours,null))  )
	) as core_GPA,  
	  	    	    		    	
  /*IF(COUNT(DISTINCT CASE WHEN (x.course_id!= 'honour' and x.course_id!= 'minor'  and x.grade = 'F') THEN 1  END),'FAIL', 'PASS')  as  core_status,	*/
  	  	    	    		    	
  IF(COUNT(DISTINCT CASE WHEN (x.course_id!= 'honour' and x.course_id!= 'minor'  and x.grade = 'F') THEN 1  END),'FAIL',(IF((sum( IF((x.course_id!= 'honour' and x.course_id!= 'minor'  ), x.totcrdpts,null) )/sum( IF((x.course_id!= 'honour' and x.course_id!= 'minor'  ), x.credit_hours,null) )  )<5,'FAIL','PASS')) )  as  core_status,
  
  SUM( IF ( (x.course_id!= 'honour' and x.course_id!= 'minor'  and  x.grade = 'F'), 1, 0))as  count_core_failed_sub,		  
  SUM( IF ( (x.course_id!= 'honour' and x.course_id!= 'minor'  and  x.grade <> 'F'), 1, 0))as  count_core_passed_sub,
  
  GROUP_CONCAT(( IF( (x.course_id!= 'honour' and x.course_id!= 'minor'  and x.grade = 'F'),x.sub,null) ) SEPARATOR ',') as  core_fail_sub_list,
  
   sum( IF((x.course_id= 'honour'), x.totcrdpts,null) )  as H_only,
   
   ( sum( IF((x.course_id!= 'honour' and x.course_id!= 'minor'  ), x.totcrdpts,null) ) + 
     sum( IF((x.course_id= 'honour'), x.totcrdpts,null) ) 
	)  as H_tot	,
	
	 sum( IF((x.course_id= 'honour'), x.credit_hours,null) )  as H_crdthr,
	(sum( IF((x.course_id!= 'honour' and x.course_id!= 'minor'  ), x.credit_hours,null)) +   sum( IF((x.course_id= 'honour'), x.credit_hours,null) )  ) as crdthr_with_H,
	
	    IF(COUNT(DISTINCT CASE WHEN ((x.course_id= 'honour' and x.grade = 'F')or (x.course_id!= 'honour' and x.course_id!= 'minor'  and x.grade = 'F' )) THEN 1 END), 
		

(  IF( COUNT(DISTINCT CASE WHEN ((x.course_id= 'honour' AND x.grade = 'F')) THEN 1 end  ),'INC',
    IF(COUNT(DISTINCT CASE WHEN (((x.course_id!= 'honour' AND x.course_id!= 'minor') AND x.grade = 'F')) THEN 1 end  ) ,'INC','N/A' ) ) ), 
		
  ( ( sum( IF((x.course_id!= 'honour' and x.course_id!= 'minor' ), x.totcrdpts,null) ) + sum( IF((x.course_id= 'honour'), x.totcrdpts,null) ) ) / (sum( IF((x.course_id!= 'honour' and x.course_id!= 'minor' ), x.credit_hours,null)) + sum( IF((x.course_id= 'honour'), x.credit_hours,null) ) ) ) ) as GPA_with_H2,
  
  
   IF(COUNT(DISTINCT CASE WHEN ((x.course_id= 'honour' and x.grade = 'F')or (x.course_id!= 'honour' and x.course_id!= 'minor'  and x.grade = 'F' )) THEN 1 END),   
    (  IF(COUNT(DISTINCT CASE WHEN ((x.course_id= 'honour' AND x.grade = 'F')) THEN 1 end  ),'FAIL',
     IF(COUNT(DISTINCT CASE WHEN (((x.course_id!= 'honour' AND x.course_id!= 'minor') AND x.grade = 'F')) THEN 1 end  ) ,'FAIL','N/A' )  ) ),
   IF(COUNT(DISTINCT CASE WHEN (x.course_id= 'honour' and x.grade <> 'F') THEN 1 END),'PASS','N/A') ) as H_status2, 	
     /* if( x.course_id= 'honour',SUM( IF ( (x.course_id= 'honour' and  x.grade = 'F'), 1, 0)) ,'N/A') as  count_H_failed_sub,*/	
		SUM( IF ( (x.course_id= 'honour' and  x.grade = 'F'), 1, 0))  as  count_H_failed_sub,	 
      COALESCE(  GROUP_CONCAT(( IF( (x.course_id= 'honour' and x.grade = 'F'),x.sub,null) ) SEPARATOR ','),'N/A') as  H_fail_sub_list	,
      /*GROUP_CONCAT( (concat( x.sub,'(',x.grade,')')) SEPARATOR ',') as sublist*/
       GROUP_CONCAT((CONCAT( REPLACE(x.name, ',', '') ,'#',x.sub,'#',COALESCE(x.theory,''),'#',COALESCE(x.sessional,''),'#',COALESCE(x.practical,''),'#',COALESCE(x.total,''),'#',x.totcrdpts,'#',x.credit_hours,'#',CAST(x.LTP AS CHAR CHARACTER SET utf8),'#',x.grade)) SEPARATOR ',') AS sublist,
      x.course_id,x.dept_id  ,x.session,x.session_year  	    		    	
	
	    

     from (
      select grade_points.points ,  (grp.credit_hours*grade_points.points)  as totcrdpts , grp.* from
     (select A.admn_no,A.grade,c.name,c.subject_id as  sub,c.credit_hours, CONCAT(c.lecture,'-',c.tutorial,'-',c.practical) as LTP,  d.aggr_id,d.semester,d.sequence,e.course_id,e.dept_id,A.session,A.session_year,A.sessional,A.theory,A.practical,A.total from 
     ((select a.admn_no,a.grade,b.subject_id, b.sub_map_id,b.session,b.session_year,a.sessional,a.theory,a.practical,a.total  from marks_subject_description as a
     inner join marks_master as b on a.marks_master_id=b.id where  b.status='Y'  and  b.session_year='" . $sy . "' and  b.session='" . $sess . "' and b.`type`='" . $etype . "'  " . $where_add2 . "
	 )
	 union
	 (select a.admn_no,a.grade,b.subject_id, b.sub_map_id,b.session,b.session_year,a.sessional,a.theory,a.practical,a.total  from alumni_marks_subject_description as a
     inner join marks_master as b on a.marks_master_id=b.id where  b.status='Y'  and  b.session_year='" . $sy . "' and  b.session='" . $sess . "' and b.`type`='" . $etype . "'  " . $where_add2 . ")
	 
	 ) A 
     inner join subjects as c on A.subject_id=c.id
	  inner join course_structure as d on A.subject_id=d.id   
	  inner join subject_mapping as e on A.sub_map_id = e.map_id  and e.dept_id='" . $deptid . "' and e.semester='" . $sem . "'  and   " . $replace . "        and e.branch_id='" . $bid . "'     " . $where_add . "  
	   group by A.admn_no,A.subject_id )grp
      left join grade_points on grade_points.grade=grp.grade  order by grp.admn_no, grp.semester,grp.sequence asc	  ) x
      group by x.admn_no  order by x.admn_no )G
      left join user_details u on u.id=G.admn_no
	  left join alumni_user_details alu_u on alu_u.id=G.admn_no 
	  left join final_semwise_marks_foil f on f.admn_no=G.admn_no and f.session_yr='" . $sy . "' and  f.`session`='" . $sess . "' and f.`type`='" . $etype . "'
	  and f.`semester`='" . $sem . "' 
	  and  (CASE WHEN ((f.hstatus<>'Y')) THEN lower(f.course)=lower(G.course_id) else 1=1 END) 
	  left join alumni_final_semwise_marks_foil alu_f on alu_f.admn_no=G.admn_no and alu_f.session_yr='" . $sy . "' and  alu_f.`session`='" . $sess . "' and alu_f.`type`='" . $etype . "'  and alu_f.`semester`='" . $sem . "' 
	  and  (CASE WHEN ((alu_f.hstatus<>'Y')) THEN lower(alu_f.course)=lower(G.course_id) else 1=1 END) 
	  LEFT join reg_regular_form rf  on rf.admn_no=G.admn_no and rf.session_year=G.session_year and rf.session=G.session  and rf.acad_status='1' and rf.hod_status='1'  and
	  rf.semester='" . $sem . "' " . $where_add1 . "
      LEFT join reg_regular_form alu_rf  on alu_rf.admn_no=G.admn_no and alu_rf.session_year=G.session_year and alu_rf.session=G.session  and alu_rf.acad_status='1' and alu_rf.hod_status='1'  and
	  alu_rf.semester='" . $sem . "' " . $where_add_alu .
	  "
          
            
	  ) EEE
	 group by EEE.admn_no 
		order by  EEE.admn_no ASC
	  ";
            }
        }
        else if ($etype == 'prep' || $etype == 'other' || $etype == 'spl' || $etype == 'espl' || ($etype == 'R' && $sess == 'Summer')) {
            if ($deptid == 'all' && $etype == 'prep')
                $deptid = 'PREP';
            if (($etype == 'R' && $sess == 'Summer')) {
                $add_cond_summer = " inner join reg_summer_form rf  on rf.admn_no=x.admn_no and rf.session_year=x.session_yr and rf.session=x.session  and rf.acad_status='1' and rf.hod_status='1' group by  rf.admn_no,rf.session_year,rf.session,rf.course_id,rf.branch_id,x.semester ";
				
				
				$add_cond_summer_alu = " inner join alumni_reg_summer_form rf  on rf.admn_no=x.admn_no and rf.session_year=x.session_yr and rf.session=x.session  and rf.acad_status='1' and rf.hod_status='1' group by  rf.admn_no,rf.session_year,rf.session,rf.course_id,rf.branch_id,x.semester ";

                $pred = " select x.* from (";
                $succ = " )z ";
                $var_dot = "z";
				$alu_x_1=  " )x ";
				$alu_x_2_1=  " ";
				$alu_x_2_2=  " ) ";
				
				
            } else {
                $add_cond_summer = '';
                $pred = " ";
                $succ = " ";
                $var_dot = "x";
				$alu_x_1=  "  ";
				$alu_x_2_1=  " ) ";
				$alu_x_2_2=  " ";
				
				
            }
            if ($etype == 'other')
                $type = 'O';
            else if ($etype == 'spl')
                $type = 'S';
            else if ($etype == 'espl')
                $type = 'E';
            else
                $type = 'R';
            $myquery = "   select CONCAT_WS(' ',COALESCE(null , alu_u.first_name,u.first_name), COALESCE(null ,alu_u.middle_name, u.middle_name), COALESCE(null , alu_u.last_name, u.last_name)) AS st_name, dpt.name AS dept_name," . $var_dot . ".* 
FROM 
((
" . $pred . "
SELECT G.*,
/*group_concat((IF((b.mis_sub_id ),concat( b.sub_code,'(',b.grade,')'), NULL)) SEPARATOR ',')  as sublist, */
/*GROUP_CONCAT((CONCAT(b.sub_code,'(',b.grade,')')) SEPARATOR ',') AS sublist, */ 
 GROUP_CONCAT((CONCAT( REPLACE(c.name, ',', ''),'#',b.sub_code,'#',COALESCE(b.theory,''),'#',COALESCE(b.sessional,''),'#',/*COALESCE( 'NA' as b.practical,'')*/   COALESCE(if(c.type='Practical',b.total,'N/A'),'')     ,'#',COALESCE(b.total,''),'#',b.cr_pts,'#',b.cr_hr,'#',CAST(CONCAT(c.lecture,'-',c.tutorial,'-',c.practical) AS CHAR CHARACTER SET utf8),'#',b.grade)) SEPARATOR ',') AS sublist,
SUM(IF (  (b.grade = 'F'            
     and
        (case  
               when  b.mis_sub_id <>''  then  d.aggr_id NOT LIKE 'honour%'                                                          
               else 1=1			       
        end)
		 
), 1, 0)) AS count_core_failed_sub,

SUM(IF ((b.grade = 'F'and  (d.aggr_id like 'honour%')), 1, 0)) AS count_H_failed_sub,

GROUP_CONCAT((IF((b.grade= 'F' 
	  and
        (case  
               when  b.mis_sub_id <>''  then  	  d.aggr_id NOT LIKE 'honour%'
               else 1=1			       
        end)	          
		  
),b.sub_code, NULL)) SEPARATOR ',') AS core_fail_sub_list,

 	 SUM(IF ((b.grade <> 'F'  
      and 
	     (case  
               when  b.mis_sub_id <>''  then  	  	  d.aggr_id NOT LIKE 'honour%'
               else 1=1			       
        end)	
	  
	  ), 1, 0)) AS count_core_passed_sub,null as GPA_with_H2, null as H_status2

FROM
(
  SELECT a.admn_no, a.id, a.tot_cr_hr AS core_crdthr,a.tot_cr_pts AS core_tot,
/*(CASE WHEN (a.status = 'FAIL') THEN 'INC' ELSE 
 ( CASE WHEN ((a.hstatus='Y') ) THEN a.core_gpa ELSE  a.gpa end) END) AS core_GPA,
   (CASE WHEN (a.hstatus='Y') then (CASE WHEN (a.status = 'FAIL') THEN 'INC' ELSE a.gpa END) else 'N/A' end) AS GPA_with_H,
   */
   (CASE WHEN (a.status = 'FAIL') THEN 'INC' ELSE ( CASE WHEN ((a.hstatus='Y') ) THEN FORMAT(a.core_gpa,5)  ELSE FORMAT(a.gpa,5) end) END) AS core_GPA, 
   (CASE WHEN (a.hstatus='Y') then (CASE WHEN (a.status = 'FAIL') THEN 'INC' ELSE FORMAT(a.gpa,5) END) else 'N/A' end) AS GPA_with_H, 
 
 ( CASE WHEN ((a.hstatus='Y') ) THEN a.core_status ELSE  a.status end) AS core_status,
   (CASE WHEN (a.hstatus='Y') then a.status  else 'N/A' end) AS H_status, 
   /*(CASE WHEN (a.status = 'FAIL') THEN 'INC' ELSE  ( CASE WHEN ((a.hstatus='Y') ) THEN a.core_cgpa ELSE  a.cgpa end) END) AS cgpa,*/
   (CASE WHEN (a.status = 'FAIL' and a.hstatus<>'Y' ) THEN 'INC'  WHEN (a.core_status = 'FAIL' and a.hstatus='Y' ) THEN 'INC'      ELSE (CASE WHEN ((a.hstatus='Y')) THEN a.core_cgpa ELSE a.cgpa END) END) AS cgpa,
   
  (CASE WHEN (a.hstatus='Y') then (CASE WHEN (a.status = 'FAIL') THEN 'INC' ELSE a.cgpa END) else 'N/A' end) AS H_cgpa,a.session_yr,a.session,a.semester,a.hstatus,a.course as course_id

FROM final_semwise_marks_foil a
WHERE a.session_yr='" . $sy . "' AND a.`session`='" . $sess . "' AND a.dept='" . $deptid . "' AND a.course='" . $cid . "' AND a.branch='" . (($deptid == 'comm' && (($etype == 'R' && $sess == 'Summer'))) ? $sec_name : $bid ) . "' AND a.semester='" . $sem . "'
 AND a.`type`='" . $type . "'  " . $where_add2 . "    )G
INNER JOIN final_semwise_marks_foil_desc b ON G.id=b.foil_id AND G.admn_no=b.admn_no 
LEFT JOIN course_structure d ON d.id=b.mis_sub_id 
LEFT JOIN subjects AS c ON b.mis_sub_id=c.id
group by b.foil_id  order by b.admn_no 
".$alu_x_1."
" . $add_cond_summer . " 
) union 
(
" . $pred . "
SELECT G.*,
/*group_concat((IF((b.mis_sub_id ),concat( b.sub_code,'(',b.grade,')'), NULL)) SEPARATOR ',')  as sublist, */
/*GROUP_CONCAT((CONCAT(b.sub_code,'(',b.grade,')')) SEPARATOR ',') AS sublist, */ 
GROUP_CONCAT((CONCAT( REPLACE(c.name, ',', ''),'#',b.sub_code,'#',COALESCE(b.theory,''),'#',COALESCE(b.sessional,''),'#',/*COALESCE( 'NA' as b.practical,'')*/   COALESCE(if(c.type='Practical',b.total,'N/A'),'')     ,'#',COALESCE(b.total,''),'#',b.cr_pts,'#',b.cr_hr,'#',CAST(CONCAT(c.lecture,'-',c.tutorial,'-',c.practical) AS CHAR CHARACTER SET utf8),'#',b.grade)) SEPARATOR ',') AS sublist,
SUM(IF (  (b.grade = 'F'            
     and
        (case  
               when  b.mis_sub_id <>''  then  d.aggr_id NOT LIKE 'honour%'                                                          
               else 1=1			       
        end)
		 
), 1, 0)) AS count_core_failed_sub,

SUM(IF ((b.grade = 'F'and  (d.aggr_id like 'honour%')), 1, 0)) AS count_H_failed_sub,

GROUP_CONCAT((IF((b.grade= 'F' 
	  and
        (case  
               when  b.mis_sub_id <>''  then  	  d.aggr_id NOT LIKE 'honour%'
               else 1=1			       
        end)	          
		  
),b.sub_code, NULL)) SEPARATOR ',') AS core_fail_sub_list,

 	 SUM(IF ((b.grade <> 'F'  
      and 
	     (case  
               when  b.mis_sub_id <>''  then  	  	  d.aggr_id NOT LIKE 'honour%'
               else 1=1			       
        end)	
	  
	  ), 1, 0)) AS count_core_passed_sub,null as GPA_with_H2, null as H_status2

FROM
(
SELECT a.admn_no, a.id, a.tot_cr_hr AS core_crdthr,a.tot_cr_pts AS core_tot,
/*(CASE WHEN (a.status = 'FAIL') THEN 'INC' ELSE 
 ( CASE WHEN ((a.hstatus='Y') ) THEN a.core_gpa ELSE  a.gpa end) END) AS core_GPA,
   (CASE WHEN (a.hstatus='Y') then (CASE WHEN (a.status = 'FAIL') THEN 'INC' ELSE a.gpa END) else 'N/A' end) AS GPA_with_H,
   */
   (CASE WHEN (a.status = 'FAIL') THEN 'INC' ELSE ( CASE WHEN ((a.hstatus='Y') ) THEN FORMAT(a.core_gpa,5)  ELSE FORMAT(a.gpa,5) end) END) AS core_GPA, 
(CASE WHEN (a.hstatus='Y') then (CASE WHEN (a.status = 'FAIL') THEN 'INC' ELSE FORMAT(a.gpa,5) END) else 'N/A' end) AS GPA_with_H, 
 
 ( CASE WHEN ((a.hstatus='Y') ) THEN a.core_status ELSE  a.status end) AS core_status,
   (CASE WHEN (a.hstatus='Y') then a.status  else 'N/A' end) AS H_status, 
   /*(CASE WHEN (a.status = 'FAIL') THEN 'INC' ELSE  ( CASE WHEN ((a.hstatus='Y') ) THEN a.core_cgpa ELSE  a.cgpa end) END) AS cgpa,*/
   (CASE WHEN (a.status = 'FAIL' and a.hstatus<>'Y' ) THEN 'INC'  WHEN (a.core_status = 'FAIL' and a.hstatus='Y' ) THEN 'INC'      ELSE (CASE WHEN ((a.hstatus='Y')) THEN a.core_cgpa ELSE a.cgpa END) END) AS cgpa,
   
  (CASE WHEN (a.hstatus='Y') then (CASE WHEN (a.status = 'FAIL') THEN 'INC' ELSE a.cgpa END) else 'N/A' end) AS H_cgpa,a.session_yr,a.session,a.semester,a.hstatus,a.course as course_id

FROM alumni_final_semwise_marks_foil a
WHERE a.session_yr='" . $sy . "' AND a.`session`='" . $sess . "' AND a.dept='" . $deptid . "' AND a.course='" . $cid . "' AND a.branch='" . (($deptid == 'comm' && (($etype == 'R' && $sess == 'Summer'))) ? $sec_name : $bid ) . "' AND a.semester='" . $sem . "'
 AND a.`type`='" . $type . "'  " . $where_add2 . "    )G
INNER JOIN alumni_final_semwise_marks_foil_desc b ON G.id=b.foil_id AND G.admn_no=b.admn_no 
LEFT JOIN course_structure d ON d.id=b.mis_sub_id 
LEFT JOIN subjects AS c ON b.mis_sub_id=c.id
group by b.foil_id  order by b.admn_no 
) ".$alu_x_2_1."  x
" . $add_cond_summer_alu . " ".$alu_x_2_2."

" . $succ . "
LEFT JOIN user_details u ON u.id=" . $var_dot . ".admn_no
  left join alumni_user_details alu_u on alu_u.id=" . $var_dot . ".admn_no 
LEFT JOIN departments dpt ON dpt.id=u.dept_id  
ORDER BY " . $var_dot . ".admn_no";
 }
 else if ($etype == 'jrf' || $etype == 'jrf_spl') {
         $type_x = ( $etype == 'jrf_spl' ? 'JS' : 'J');
 $myquery = " SELECT G.*
FROM (
SELECT x.st_name,x.admn_no, SUM(IF((x.course_id='jrf'), x.totcrdpts, NULL)) AS core_tot,
 SUM(IF((x.course_id= 'jrf'), x.credit_hours, NULL)) AS core_crdthr, 
 IF(COUNT(DISTINCT CASE WHEN (x.course_id='jrf' AND ( x.grade = 'F'|| x.grade = 'D')) THEN 1 END),'INC', 
 (SUM(IF((x.course_id='jrf'), x.totcrdpts, NULL)) / SUM(IF((x.course_id='jrf'), x.credit_hours, NULL)))) AS core_GPA,
  IF(COUNT(DISTINCT CASE WHEN (x.course_id='jrf' AND ( x.grade = 'F'|| x.grade = 'D')) THEN 1 END),'FAIL', 'PASS') AS core_status, 
  SUM(IF ((x.course_id='jrf' AND ( x.grade = 'F'|| x.grade = 'D')), 1, 0)) AS count_core_failed_sub, 
  SUM(IF ((x.course_id='jrf' AND ( x.grade <> 'F' and  x.grade <> 'D')), 1, 0)) AS count_core_passed_sub, 
  GROUP_CONCAT((IF((x.course_id='jrf' AND ( x.grade = 'F'|| x.grade = 'D')),x.sub, NULL)) SEPARATOR ',') AS core_fail_sub_list, 
  GROUP_CONCAT((CONCAT(x.name,'(',x.sub,')',' / ',x.grade)) SEPARATOR '\\n') AS sublist,  
  GROUP_CONCAT((CONCAT( REPLACE(x.name, ',', '') ,'#',x.sub,'#',COALESCE(x.theory,''),'#',COALESCE(x.sessional,''),'#',COALESCE(x.practical,''),'#',COALESCE(x.total,''),'#',x.totcrdpts,'#',x.credit_hours,'#',CAST(x.LTP AS CHAR CHARACTER SET utf8),'#',x.grade)) SEPARATOR ',') AS sublist2  
  ,x.course_id,x.dept_id 

FROM (
SELECT grade_points.points, (grp.credit_hours*grade_points.points) AS totcrdpts, grp.*
FROM (
SELECT A.admn_no,A.grade,A.st_name,c.name,c.subject_id AS sub,c.credit_hours,CONCAT(c.lecture,'-',c.tutorial,'-',c.practical) as LTP, 'jrf' as course_id,A.dept_id,A.sessional,A.theory,A.practical,A.total
FROM (
(SELECT a.admn_no,a.grade,b.subject_id, b.sub_map_id,CONCAT_WS(' ',u.first_name, u.middle_name, u.last_name) AS st_name,u.dept_id,a.sessional,a.theory,a.practical,a.total
FROM marks_subject_description AS a
INNER JOIN marks_master AS b ON a.marks_master_id=b.id
inner  JOIN user_details u ON u.id=a.admn_no 
WHERE u.dept_id= '" . $deptid . "'    and  b.status='Y' and  b.session_year='" . $sy . "' AND b.session='" . $sess . "' AND b.`type`='" . $type_x . "'  " . $where_add2 . "
)
union
(SELECT a.admn_no,a.grade,b.subject_id, b.sub_map_id,CONCAT_WS(' ',u.first_name, u.middle_name, u.last_name) AS st_name,u.dept_id,a.sessional,a.theory,a.practical,a.total
FROM alumni_marks_subject_description AS a
INNER JOIN marks_master AS b ON a.marks_master_id=b.id
inner  JOIN alumni_user_details u ON u.id=a.admn_no 
WHERE u.dept_id= '" . $deptid . "'    and  b.status='Y' and  b.session_year='" . $sy . "' AND b.session='" . $sess . "' AND b.`type`='" . $type_x . "'  " . $where_add2 . ")
) A
left JOIN subjects AS c ON A.subject_id=c.id

/*INNER JOIN subject_mapping AS e ON A.sub_map_id = e.map_id
inner join user_details u on u.dept_id= e.dept_id and  u.dept_id= '" . $deptid . "'
 WHERE e.dept_id='" . $deptid . "' AND  e.course_id='" . $cid . "' AND e.branch_id='" . $bid . "'
*/
GROUP BY A.admn_no,A.subject_id)grp

LEFT JOIN grade_points ON grade_points.grade=grp.grade
ORDER BY grp.admn_no ASC) x
 inner join reg_exam_rc_form  erf on erf.admn_no=x.admn_no and erf.course_id='jrf' and erf.branch_id='jrf' and  erf.session_year='" . $sy . "' and erf.hod_status<>'2' and erf.acad_status<>'2' and erf.`session`='" . $sess . "' and erf.`type`='R'
GROUP BY x.admn_no
ORDER BY x.admn_no)G
";
        }
        $query = $this->db->query($myquery);
    //echo   $this->db->last_query(); die();
        //echo '<pre>'; print_r($query->result()); echo '</pre>';  die();
        

        if ($query->num_rows() > 0) {
            if (!$stop_student_status) {
                return $query->result();
            } else {
				 //die();
                switch ($etype) {
                    case 'R': case 'regular' :((strtoupper($cid) == 'JRF' && $sess = 'Winter') ? $type = 'jrf_spl' : $type = 'regular');
                        break;
                    case 'S': case 'spl':$type = 'spl';
                        break;
                    case 'O': case 'other':$type = 'other';
                        break;
                    case 'E': case 'espl':$type = 'espl';
                        break;
                    case 'J': case 'jrf':$type = 'jrf';
                        break;
                }
                $rdec = $this->student_grade_model->get_result_declaration($deptid, $cid, ($cid == 'COMM' ? 'comm' : $bid), (($cid == 'JRF' || $cid == 'PREP') ? '-1' : $sem), ($type), $sy, $sess, ($sec = ($cid == 'COMM' || $cid == 'comm') ? $sec_name : null), 'both');
             //   echo  $this->db->last_query().'<br/>'; die();
                $Hrow[] = (object) array();
                $h = 0;
                $check = true;
                foreach ($query->result() as $qq) {
                    // added to have checking validation whether result declration done then only result  to be shown  wherever rq.	 				 
                    if (!empty($rdec)) {
                        $chk_partial = $chk_partial = $this->student_grade_model->check_partial_stu_multi($rdec->id, $qq->admn_no); // may be multiple row come  basd on pre declared or post declared
                        // echo  $this->db->last_query().'<br/>';                              
                        //print_r($data['chk_partial']);die();
                        $check = true;
                        foreach ($chk_partial as $chk_partial_row) {
                            if ($chk_partial_row->status == 'P' || $chk_partial_row->status == 'D') {
                                $check = false;
                            }
                        }
                    } else {
                        $rdec = 1;
                    }
                    if (!empty($rdec) && $check) {
                        $Hrow[$h]->cgpa = $qq->cgpa;
                        $Hrow[$h]->H_cgpa = $qq->H_cgpa;
                        $Hrow[$h]->hstatus = $qq->hstatus;
                        $Hrow[$h]->GPA_with_H = $qq->GPA_with_H;
                        $Hrow[$h]->H_status = $qq->H_status;
                        $Hrow[$h]->st_name = $qq->st_name;
                        $Hrow[$h]->admn_no = $qq->admn_no;
                        $Hrow[$h]->core_tot = $qq->core_tot;
                        $Hrow[$h]->core_crdthr = $qq->core_crdthr;
                        $Hrow[$h]->core_GPA = $qq->core_GPA;
                        $Hrow[$h]->core_status = $qq->core_status;
                        $Hrow[$h]->count_core_failed_sub = $qq->count_core_failed_sub;
                        $Hrow[$h]->count_core_passed_sub = $qq->count_core_passed_sub;
                        $Hrow[$h]->core_fail_sub_list = $qq->core_fail_sub_list;


                        $Hrow[$h]->H_only = $qq->H_only;
                        $Hrow[$h]->H_tot = $qq->H_tot;
                        $Hrow[$h]->H_crdthr = $qq->H_crdthr;
                        $Hrow[$h]->crdthr_with_H = $qq->crdthr_with_H;
                        $Hrow[$h]->GPA_with_H2 = $qq->GPA_with_H2;
                        $Hrow[$h]->H_status2 = $qq->H_status2;
                        $Hrow[$h]->count_H_failed_sub = $qq->count_H_failed_sub;
                        $Hrow[$h]->H_fail_sub_list = $qq->H_fail_sub_list;
                        $Hrow[$h]->sublist = $qq->sublist;
                        $Hrow[$h]->sublist2 = $qq->sublist2;
                        $Hrow[$h]->course_id = $qq->course_id;
                        $Hrow[$h]->dept_id = $qq->dept_id;
                        $Hrow[$h]->session = $qq->session;
                        $Hrow[$h]->session_year = $qq->session_year;
                        $Hrow[$h]->dept_name = $qq->dept_name;

                        $h++;
                    }
                }
                //echo '<pre>'; print_r($Hrow); echo '</pre>';  die();// 
                return ($Hrow == null ? '0' : $Hrow);
            }
        } else {
            return FALSE;
        }
    }

    function show_data_for_view_redeclared($id, $pub) {
        $q = "select   GROUP_CONCAT(x.admn_no) as admn_no from  result_declaration_log_partial_details x  where x.res_dec_id=?  and x.published_on=? and x.status='D'";
        $q = $this->db->query($q, array($id, $pub));
        if ($q->num_rows() > 0)
            return $q->row()->admn_no;
        return false;
    }

    function insert_re_declared_data($data) {
        /*  $sql = "insert into result_declaration_log_details (res_dec_id,admn_no)values (?, ?)";
          $this-> db-> query($sql,array($id,$admn_no));
          return TRUE; */
        //print_r($data); die();
        if (count($data) > 0) {
            if (!$this->db->insert_batch('result_declaration_log_partial_details', $data)) {
                return false;
            } else {
                return true;
            }
        } else {
            return false;
        }
    }

    function update_re_declared_data($data, $con) {

        $this->db->update('result_declaration_log_partial_details', $data, $con);
        if ($this->db->affected_rows() > 0) {
            return true;
        }
        return false;
    }

    function get_result_data_marks_master($adm, $s, $sy, $sem, $c, $b) {
        $q = "SELECT b.sessional,b.theory,b.practical,b.total,b.grade,c.subject_id,c.name
                  FROM marks_master AS a
                  JOIN marks_subject_description AS b ON a.id=b.marks_master_id
                  JOIN subjects AS c ON a.subject_id=c.id
                  JOIN subject_mapping AS d ON d.map_id=a.sub_map_id
                  WHERE b.admn_no=? AND d.`session`=? AND d.session_year=? AND d.semester=? AND  d.course_id=? AND d.branch_id=?";
        $q = $this->db->query($q, array($adm, $s, $sy, $sem, $c, $b));
        if ($q->num_rows() > 0) {
            return $q->result();
        }
        return false;
    }

    function get_result_data_marks_master_bkp($adm, $s, $sy, $sem, $c, $b) {
        $q = "SELECT b.sessional,b.theory,b.practical,b.total,b.grade,c.subject_id,c.name
                  FROM marks_master AS a
                  JOIN marks_subject_description_backup AS b ON a.id=b.marks_master_id
                  JOIN subjects AS c ON a.subject_id=c.id
                  JOIN subject_mapping AS d ON d.map_id=a.sub_map_id
                  WHERE b.admn_no=? AND d.`session`=? AND d.session_year=? AND d.semester=?  AND d.course_id=? AND d.branch_id=?";
        $q = $this->db->query($q, array($adm, $s, $sy, $sem, $c, $b));
        if ($q->num_rows() > 0) {
            return $q->result();
        }
        return false;
    }

    function get_with_held_republished_date_id($id) {
        $q = " select   * from  result_declaration_log_partial_details x  where x.res_dec_id=?  and x.status='D'  group by x.published_on order by x.id ";
        $q = $this->db->query($q, array($id));
        if ($q->num_rows() > 0) {
            return $q->result();
        }
        return array();
    }
	

function sync_result_dec_to_freezed_foil($data, $result_pub_id, $admn_no = null,$rdec_type=null,$partial_log_id=null){
     //   echo $admn_no; die();
        $this->load->model('student_grade_sheet/student_grade_model', '', TRUE);     
        $this->db->select('actual_published_on,published_on');
        $this->db->where( (array('id' => $result_pub_id ) ));
        
        $select =($this->db->get('result_declaration_log'));
        if ($select->num_rows()) {
            $row = $select->result_array();
            $actual = $row[0]['actual_published_on'];
            $pubdate = $row[0]['published_on'];
        }

        $data1 = array('result_dec_id' => $result_pub_id,
            'published_on' => $pubdate,
            'actual_published_on' => $actual
        );
        //    echo $this->db->last_query(); 
        //print_r($data1); die();
       
        switch ($data['exam_type']) {
            case 'regular' : case 'jrf_spl': case 'jrf': case 'prep': $var = 'R';
                break;
            case 'spl' : $var = 'S';
                break;
            case 'other' : $var = 'O';
                break;
            case 'espl' :$var = 'E';
                break;
        }
        $arr = array('session_yr' => $data['s_year'],
            'session' => $data['session'],
            'upper(dept)' => strtoupper($data['dept_id']),
            'upper(course)' => strtoupper($data['course_id']),
            'upper(branch)' => (strtoupper($data['course_id']) == 'COMM' ? $data['section'] : strtoupper($data['branch_id'])),
            'type' => $var,
            'semester' => (strtoupper($data['course_id']) == 'JRF' ? '0' : (strtoupper($data['course_id']) == 'PREP' ? '-1' : $data['semester'])),
            'admn_no'=> $admn_no 
        );
        /*if ($admn_no <> null) {
            $arr['admn_no'] = $admn_no;     //  $this->db->select('`session_yr`,	`session`, 	`dept`, 	`course`,	`branch`, 	`semester`,	`admn_no` ,	`tot_cr_hr`,	`tot_cr_pts` ,	`core_tot_cr_hr` ,	`core_tot_cr_pts`,             * 	`ctotcrpts` ,`core_ctotcrpts` ,	`ctotcrhr` ,	`core_ctotcrhr` ,	`gpa` ,	`core_gpa` ,	`cgpa` ,	`core_cgpa` ,	`status` ,	`core_status`,	`hstatus` ,	`repeater`,	`type` ,	`exam_type`,	`final_status` 	');  
        }*/
        $this->db->select('*');
        $this->db->where($arr);
        $select = $this->db->get('final_semwise_marks_foil');
        // print_r($select->result_array());  echo $result_pub_id; die();
        if ($select->num_rows()) {
            $arr2 = null;
            $l = 0; 
            foreach ($select->result_array() as $row) {
                
                       
                 //   if($admn_no==null)
                $arr2[$l]['old_id'] = $row['id'];
                //   else
                // $prev=$row['id'] ;                                                               
                $arr2[$l]['session_yr'] = $row['session_yr'];
                $arr2[$l]['session'] = $row['session'];
                $arr2[$l]['dept'] = $row['dept'];
                $arr2[$l]['course'] = $row['course'];
                $arr2[$l]['branch'] = $row['branch'];
                $arr2[$l]['semester'] = $row['semester'];
                $arr2[$l]['admn_no'] = $row['admn_no'];
                $arr2[$l]['tot_cr_hr'] = $row['tot_cr_hr'];
                $arr2[$l]['tot_cr_pts'] = $row['tot_cr_pts'];
                $arr2[$l]['core_tot_cr_hr'] = $row['core_tot_cr_hr'];
                $arr2[$l]['core_tot_cr_pts'] = $row['core_tot_cr_pts'];

                $arr2[$l]['ctotcrpts'] = $row['ctotcrpts'];
                $arr2[$l]['core_ctotcrpts'] = $row['core_ctotcrpts'];
                $arr2[$l]['ctotcrhr'] = $row['ctotcrhr'];
                $arr2[$l]['core_ctotcrhr'] = $row['core_ctotcrhr'];
                $arr2[$l]['gpa'] = $row['gpa'];
                $arr2[$l]['core_gpa'] = $row['core_gpa'];
                $arr2[$l]['cgpa'] = $row['cgpa'];
                $arr2[$l]['core_cgpa'] = $row['core_cgpa'];
                $arr2[$l]['status'] = $row['status'];
                $arr2[$l]['core_status'] = $row['core_status'];
                $arr2[$l]['hstatus'] = $row['hstatus'];
                $arr2[$l]['repeater'] = $row['repeater'];
                $arr2[$l]['type'] = $row['type'];
                $arr2[$l]['exam_type'] = $row['exam_type'];
                $arr2[$l]['final_status'] = $row['final_status'];
               
                


                //foreach ($select->result_array() as $res) {
                $this->db->insert("final_semwise_marks_foil_freezed", $arr2[$l]);
                /* if($admn_no<>null) */$curr = $this->db->insert_id();
                /*  query to update  published_date and  result_declaration_id for each combination */

                $this->db->where(array_merge($arr, array('id' => $curr)));
              $this->db->update('final_semwise_marks_foil_freezed', $data1);
                $this->db->where(array('foil_id' => $row['id'], 'admn_no' => $admn_no) );
                $select_desc = $this->db->get('final_semwise_marks_foil_desc');
                //     echo $this->db->last_query();
                if ($select_desc->num_rows()) {
                    $arr1 = null;
                    $k = 0;
                    foreach ($select_desc->result_array() as $row1) {
                        //if($admn_no==null)
                        $arr1[$k]['old_foil_id'] = $row1['foil_id'];
                        $arr1[$k]['foil_id'] = $curr;
                        //else 
                        //$arr1[$k]['foil_id']=$curr;                                                         
                        $arr1[$k]['admn_no'] = $row1['admn_no'];
                        $arr1[$k]['sub_code'] = $row1['sub_code'];
                        $arr1[$k]['mis_sub_id'] = $row1['mis_sub_id'];
                        $arr1[$k]['cr_hr'] = $row1['cr_hr'];
                        $arr1[$k]['sessional'] = $row1['sessional'];
                        $arr1[$k]['theory'] = $row1['theory'];
                        $arr1[$k]['total'] = $row1['total'];
                        $arr1[$k]['grade'] = $row1['grade'];
                        $arr1[$k]['cr_pts'] = $row1['cr_pts'];
                        $arr1[$k]['current_exam'] = $row1['current_exam'];
                        $arr1[$k]['remark'] = $row1['remark'];
                        $k++;
                    }
                    //print_r($arr1); die();
                    $this->db->insert_batch("final_semwise_marks_foil_desc_freezed", /* $select_desc->result_array() */ $arr1);
                }
                $l++;
            }
            
        }
    }












}

?>
