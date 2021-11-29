<?php

class Ar_acad_attendance_model extends CI_Model {

    public function __construct() {
        parent::__construct();
    }

    public function courses() {
        $this->load->database();
        $result1 = $this->db->query("SELECT id FROM courses  ");
        $result1 = $result1->result();
        $result = array();
        foreach ($result1 as $row) {
            $result[$row->id] = 0;
        }
        return $result;
    }

    public function depart() {
        $this->load->database();
        $result = $this->db->query("SELECT * FROM departments WHERE type = 'academic' ");
        $result = $result->result();
        return $result;
    }

    public function get_branches_Only_jrf($data) {
        $this->load->database();
        if ($data['depart_id'] == "All") {
            $result = $this->db->query("select  DISTINCT C.map_id,C.semester
from subject_mapping C
left join course_branch A on  C.course_id = A.course_id
left JOIN dept_course AS B ON A.course_branch_id = B.course_branch_id
WHERE C.session_year = '$data[session_year]' AND C.session = '$data[session]' and C.course_id='jrf'
ORDER BY C.course_id, C.branch_id,C.semester
");




            $result = $result->result();
        } else {
            $result = $this->db->query("  select  DISTINCT C.map_id,C.semester
from subject_mapping C
left join course_branch A on  C.course_id = A.course_id
left JOIN dept_course AS B ON A.course_branch_id = B.course_branch_id
WHERE C.dept_id = '$data[depart_id]' AND C.session_year = '$data[session_year]' AND C.session = '$data[session]' and C.course_id='jrf'
ORDER BY C.course_id, C.branch_id,C.semester");




            $result = $result->result();
        }
        return $result;
    }

    public function get_branches($data) {
        $this->load->database();
        if ($data['depart_id'] == "All") {
            $result = $this->db->query("
                                        SELECT cso.id as map_id,cso.semester, cso.course_id,cso.branch_id FROM cbcs_subject_offered cso WHERE cso.session_year='$data[session_year]' AND cso.`session`='$data[session]'
                                         union
	                                     SELECT cso.id as map_id,cso.semester, cso.course_id,cso.branch_id FROM old_subject_offered cso WHERE cso.session_year='$data[session_year]' AND cso.`session`='$data[session]' 
								
           									ORDER BY course_id ,branch_id ,semester");
            $result = $result->result();
        } else {
            $result = $this->db->query("
                                        SELECT cso.id as map_id,cso.semester ,cso.course_id,cso.branch_id FROM cbcs_subject_offered cso WHERE cso.session_year='$data[session_year]' AND cso.`session`='$data[session]'  and cso.dept_id='$data[depart_id]'
                                         union 
	                                     SELECT cso.id as map_id,cso.semester ,course_id,branch_id FROM old_subject_offered cso WHERE cso.session_year='$data[session_year]' AND cso.`session`='$data[session]'  and  cso.dept_id='$data[depart_id]'
								
           									ORDER BY course_id ,branch_id ,semester")
										 ;
            $result = $result->result();
        }
        return $result;
    }
	
	
	
	 public function get_branches_non_cbcs($data) {
        $this->load->database();
        if ($data['depart_id'] == "All") {
            $result = $this->db->query("SELECT DISTINCT C.map_id ,C.semester
									FROM course_branch as A
									INNER JOIN dept_course as B
									ON A.course_branch_id = B.course_branch_id
									INNER JOIN subject_mapping as C
									ON C.branch_id = A.branch_id
									AND C.course_id = A.course_id
									WHERE C.session_year = '$data[session_year]'
									AND C.session = '$data[session]'
									ORDER BY C.course_id , C.branch_id ,C.semester");
            $result = $result->result();
        } else {
            $result = $this->db->query("SELECT DISTINCT C.map_id ,C.semester
										FROM course_branch as A
										INNER JOIN dept_course as B
										ON A.course_branch_id = B.course_branch_id
										INNER JOIN subject_mapping as C
										ON C.branch_id = A.branch_id
										AND C.course_id = A.course_id
										WHERE B.dept_id = '$data[depart_id]'
										AND C.session_year = '$data[session_year]'
										AND C.session = '$data[session]'
										ORDER BY C.course_id , C.branch_id ,C.semester");
            $result = $result->result();
        }
        return $result;
    }
	
	
	
	function getCoursebyDept($data) {
        if ($data['depart_id'] == "All") {
            $result = $this->db->query("
SELECT p.* FROM(
SELECT a.course_id FROM cbcs_subject_offered a WHERE  a.session_year='".$data['session_year']."' AND a.`session`='".$data['session']."'
UNION
SELECT a.course_id FROM old_subject_offered a WHERE  a.session_year='".$data['session_year']."' AND a.`session`='".$data['session']."'
)p
GROUP BY p.course_id
ORDER BY p.course_id");
            return $result->result();
            return false;
        } else {
            $result = $this->db->query("
SELECT p.* FROM(
SELECT a.course_id FROM cbcs_subject_offered a WHERE a.dept_id='".$data['depart_id']."' and a.session_year='".$data['session_year']."' AND a.`session`='".$data['session']."'
UNION
SELECT a.course_id FROM old_subject_offered a WHERE a.dept_id='".$data['depart_id']."' and a.session_year='".$data['session_year']."' AND a.`session`='".$data['session']."'
)p
GROUP BY p.course_id
ORDER BY p.course_id");

            if ($result->num_rows() > 0)
                return $result->result();
            return false;
        }
    }
	
// being commented due to cbcs
    function getCoursebyDept_non_cbcs($data) {
        if ($data['depart_id'] == "All") {
            $result = $this->db->query("SELECT DISTINCT C.course_id
									FROM course_branch as A
									INNER JOIN dept_course as B
									ON A.course_branch_id = B.course_branch_id
									INNER JOIN subject_mapping as C
									ON C.branch_id = A.branch_id
									AND C.course_id = A.course_id
									WHERE C.session_year = '$data[session_year]'
									AND C.session = '$data[session]'
									ORDER BY C.course_id , C.branch_id ,C.semester");
            return $result->result();
            return false;
        } else {
            $result = $this->db->query("SELECT DISTINCT C.course_id
										FROM course_branch as A
										INNER JOIN dept_course as B
										ON A.course_branch_id = B.course_branch_id
										INNER JOIN subject_mapping as C
										ON C.branch_id = A.branch_id
										AND C.course_id = A.course_id
										WHERE B.dept_id = '$data[depart_id]'
										AND C.session_year = '$data[session_year]'
										AND C.session = '$data[session]'
										ORDER BY C.course_id , C.branch_id ,C.semester");

            if ($result->num_rows() > 0)
                return $result->result();
            return false;
        }
    }



 public function getStudents_non_cbcs($data, $link_from = 2) {


        if ($link_from == 2) {
            $link_from = " a.status = 2 ";
        } else {
            $link_from = " (a.status = 2 or  a.status = 1) ";
        }

        $session_year = $data['session_year'];
        $session = $data['session'];
        $depart = $data['depart_id'];

        $this->load->database();

    //    print_r($data); die();
        $i = 0;
        foreach ($data['map_id'] as $row) {

            /*     $result1 = $this->db->query("select A.*,r.total_class from(
              SELECT  a.session_id, a.admn_no as admission_id ,count(a.date) as total_absent,  a.sub_id as subject_id , b.subject_id as sub_code, b.name  as sub_name ,a.map_id,d.dept_id,d.course_id,d.branch_id,d.semester
              FROM absent_table_defaulter a
              join subjects b on a.sub_id = b.id
              join subject_mapping d on a.map_id=d.map_id
              join reg_regular_form c on a.admn_no = c.admn_no and d.semester = c.semester and d.session_year = c.session_year and d.session = c.session

              WHERE a.status = $link_from  and c.hod_status='1' and c.acad_status='1'
              AND  a.map_id = '$row->map_id'
              group by a.admn_no,a.sub_id
              ORDER BY admission_id) A

              join total_class_table r on A.map_id = r.map_id
              where r.sub_id =A.subject_id order by A.admission_id ");
              if ($result1->num_rows() > 0)
              $result[] = $result1->result();
              echo  $this->db->last_query().'<br>';

              // getting other registered students

              $result2 = $this->db->query("select A.*,r.total_class from(
              SELECT  a.session_id, a.admn_no as admission_id ,count(a.date) as total_absent,  a.sub_id as subject_id , b.subject_id as sub_code, b.name  as sub_name ,a.map_id,d.dept_id,d.course_id,d.branch_id,d.semester
              FROM absent_table_defaulter a
              join subjects b on a.sub_id = b.id
              join subject_mapping d on a.map_id=d.map_id
              join reg_other_form c on a.admn_no = c.admn_no and d.semester = c.semester and d.session_year = c.session_year and d.session = c.session

              WHERE a.status = $link_from  and c.hod_status='1' and c.acad_status='1'
              AND  a.map_id = '$row->map_id'
              group by a.admn_no,a.sub_id
              ORDER BY admission_id) A

              join total_class_table r on A.map_id = r.map_id
              where r.sub_id =A.subject_id  ORDER BY A.admission_id");

              if ($result2->num_rows() > 0)
              $result[] = $result2->result();
              // end

             */

            if ($session <> 'Summer') {


                $result1 = $this->db->query("select A.* from(
SELECT a.`status`,a.percentage,a.session_id, a.admn_no as admission_id,a.tot_absent as total_absent, a.total_class, a.sub_id as subject_id , b.subject_id as sub_code, b.name  as sub_name ,a.map_id,d.dept_id,d.course_id,d.branch_id,d.semester
                                         FROM absent_table_defaulter a
                                         join subjects b on a.sub_id = b.id
                                         join subject_mapping d on a.map_id=d.map_id
                                         join reg_regular_form c on a.admn_no = c.admn_no and d.semester = c.semester and d.session_year = c.session_year and d.session = c.session

                                         WHERE $link_from  and c.hod_status='1' and c.acad_status='1'
                                         AND  a.map_id = '$row->map_id' and (a.admn_no   not like  '%dp%'  and  a.admn_no   not like  '%DP%'  and  a.admn_no    not like  '%dr%'  and a.admn_no   not like  '%DR%' )
                                         group by a.admn_no,a.sub_id
                                         ORDER BY admission_id) A

                                         order by A.admission_id ");
                if ($result1->num_rows() > 0)
                    $result[] = $result1->result();
                //  echo  $this->db->last_query().'<br>';
                // getting other registered students

                $result2 = $this->db->query("select A.* from(
SELECT a.`status`, a.percentage,a.session_id, a.admn_no as admission_id ,a.tot_absent as total_absent, a.total_class,  a.sub_id as subject_id , b.subject_id as sub_code, b.name  as sub_name ,a.map_id,d.dept_id,d.course_id,d.branch_id,d.semester
                                         FROM absent_table_defaulter a
                                         join subjects b on a.sub_id = b.id
                                         join subject_mapping d on a.map_id=d.map_id
                                         join reg_other_form c on a.admn_no = c.admn_no and d.semester = c.semester and d.session_year = c.session_year and d.session = c.session

                                         WHERE $link_from  and c.hod_status='1' and c.acad_status='1'
                                         AND  a.map_id = '$row->map_id' and (a.admn_no   not like  '%dp%'  and  a.admn_no   not like  '%DP%'  and  a.admn_no    not like  '%dr%'  and a.admn_no   not like  '%DR%' )
                                         group by a.admn_no,a.sub_id
                                         ORDER BY admission_id) A

                                       ORDER BY A.admission_id");

                if ($result2->num_rows() > 0)
                    $result[] = $result2->result();
                // end



                $result3 = $this->db->query("select A.* from(
SELECT a.`status`, a.percentage,a.session_id, a.admn_no as admission_id ,a.tot_absent as total_absent, a.total_class,  a.sub_id as subject_id , b.subject_id as sub_code, b.name  as sub_name ,a.map_id,d.dept_id,/*d.course_id*/ 'jrf' as course_id ,/*d.branch_id*/ 'jrf' as branch_id,d.semester
                                         FROM absent_table_defaulter a
                                         join subjects b on a.sub_id = b.id
                                         join subject_mapping d on a.map_id=d.map_id
                                         join reg_exam_rc_form c on a.admn_no = c.admn_no  and d.session_year = c.session_year and d.session = c.session

                                         WHERE  $link_from  and c.hod_status='1' and c.acad_status='1'
                                         AND  a.map_id = '$row->map_id'
                                         group by a.admn_no,a.sub_id
                                         ORDER BY admission_id) A

                                       ORDER BY A.admission_id");

                if ($result3->num_rows() > 0) {
                    $result[] = $result3->result();
                    // echo  $this->db->last_query().'<br>';
                }
            } else { // start of summer
                $result1 = $this->db->query("select A.* from(
SELECT a.`status`,a.percentage,a.session_id, a.admn_no as admission_id,a.tot_absent as total_absent, a.total_class, a.sub_id as subject_id , b.subject_id as sub_code, b.name  as sub_name ,a.map_id,d.dept_id,d.course_id,d.branch_id,d.semester
                                         FROM absent_table_defaulter a
                                         join subjects b on a.sub_id = b.id
                                         join subject_mapping d on a.map_id=d.map_id
                                         join reg_summer_form c on a.admn_no = c.admn_no /*and d.semester = c.semester*/ and d.session_year = c.session_year and d.session = c.session

                                         WHERE $link_from  and c.hod_status='1' and c.acad_status='1'
                                         AND  a.map_id = '$row->map_id' and (a.admn_no   not like  '%dp%'  and  a.admn_no   not like  '%DP%'  and  a.admn_no    not like  '%dr%'  and a.admn_no   not like  '%DR%' )
                                         group by a.admn_no,a.sub_id
                                         ORDER BY admission_id) A

                                         order by A.admission_id ");

                if ($result1->num_rows() > 0)
                    $result[] = $result1->result();
                // end
                //   echo $this->db->last_query() . '<br>';


                $result3 = $this->db->query("select A.* from(
SELECT a.`status`, a.percentage,a.session_id, a.admn_no as admission_id ,a.tot_absent as total_absent, a.total_class,  a.sub_id as subject_id , b.subject_id as sub_code, b.name  as sub_name ,a.map_id,d.dept_id,/*d.course_id*/ 'jrf' as course_id ,/*d.branch_id*/ 'jrf' as branch_id,d.semester
                                         FROM absent_table_defaulter a
                                         join subjects b on a.sub_id = b.id
                                         join subject_mapping d on a.map_id=d.map_id
                                         join reg_exam_rc_form c on a.admn_no = c.admn_no  and d.session_year = c.session_year and d.session = c.session

                                         WHERE  $link_from  and c.hod_status='1' and c.acad_status='1'
                                         AND  a.map_id = '$row->map_id'
                                         group by a.admn_no,a.sub_id
                                         ORDER BY admission_id) A

                                       ORDER BY A.admission_id");

                if ($result3->num_rows() > 0) {
                    $result[] = $result3->result();
                    // echo  $this->db->last_query().'<br>';
                }
            }
        }

        //  echo $this->db->last_query();


        $i = 0;
        foreach ($result as $va) {
            foreach ($va as $key => $val) {
                $res = $this->getModFromtech($val->subject_id, $val->admission_id, $val->session_id);
                if ($res) {
                    $percent = ((($val->total_class) - ($val->total_absent)) + ($res->count)) * 100;
                } else {
                    $percent = ((($val->total_class) - ($val->total_absent))) * 100;
                }

                $percent = (float) (($percent / $val->total_class));
                $percent = round($percent, 2);

                $result[$i][$key]->percent = $percent;
                $result[$i][$key]->total_class = $val->total_class;
                $result[$i][$key]->tot_absent = $val->total_absent;
                $result[$i][$key]->percentage = $val->percentage;

                $temp1 = $this->db->query("SELECT first_name , middle_name , last_name
									   FROM user_details
									   WHERE id = '$val->admission_id'  ");
                ($temp1 = $temp1->result());
                $result[$i][$key]->stu_name = $temp1[0]->first_name . ' ' . $temp1[0]->middle_name . ' ' . $temp1[0]->last_name;
            }

            $i++;
        }
        //echo '<pre>';print_r($result);echo '</pre>';
        //echo $this->db->last_query();
        //print_r($result); die();
        // foreach ($result as $row)
        // {
        // 	$query = $this->db->query("SELECT name as sub_name , subject_id as sub_code
        // 							   FROM subjects
        // 							   WHERE id = '$row->subject_id '  ");
        // 	($query = $query->result()) ;
        // 	$row->sub_name = $query[0]->sub_name;
        // 	$row->sub_code = $query[0]->sub_code;
        // }
        //print_r($result);
        //echo '<br>' ;
        // foreach ($result as $row)
        // {
        // 	$query = $this->db->query("SELECT course_id , branch_id , semester as semster
        // 							   FROM reg_regular_form
        // 							   WHERE admn_no = '$row->admission_id' and session_year='$session_year' and session='$session'");
        // 	($query = $query->result()) ;
        // 	$row->course_id = $query[0]->course_id;
        // 	$row->branch_id = $query[0]->branch_id;
        // 	$row->semester = $query[0]->semster;
        // }
        //print_r($result);
        // foreach ($result as $row)
        // {
        // 	$temp = $this->db->query("SELECT total_class
        // 					FROM total_class_table
        // 					WHERE map_id = $row->map_id
        // 					AND sub_id = '$row->subject_id' ");
        // 	$temp = $temp->result();
        // 	$row->total_class = $temp[0]->total_class;
        // 	$query=$this->db->query("SELECT count(date) as date FROM absent_table
        // 							 WHERE map_id = $row->map_id AND sub_id = '$row->subject_id'
        // 							 AND admn_no = '$row->admission_id'
        // 							 AND Remark='none'
        // 							 AND status = 2 ");
        // 	($temp_1 = $query->result()) ;
        // 			$res=$this->getModFromtech($row->subject_id,$row->admission_id);
        // 	$percent = (($temp[0]->total_class - $temp_1[0]->date)+($res->count)) *100;
        // 	$percent = (float)(($percent/$temp[0]->total_class));
        // 	$percent = round($percent,2);
        // 	$row->percent = $percent;
        // }
        //print_r($result);
        // foreach ($result as $row)
        // {
        // 	$temp1 = $this->db->query("SELECT first_name , middle_name , last_name
        // 							   FROM user_details
        // 							   WHERE id = '$row->admission_id'  ");
        // 	//$row->total_class = $temp[0]->total_class;
        // 	($temp1 = $temp1->result()) ;
        // 	$row->stu_name = $temp1[0]->first_name.' '.$temp1[0]->middle_name.' '.$temp1[0]->last_name;
        //  }
        // print_r($result);
        return ($result);
    }



    public function getStudents($data, $link_from = 2) {

//print_r($data);die();

        if ($link_from == 2) {
            $link_from = " a.def_status='y' ";
        } else {
            $link_from = " (a.def_status='y' /* or  a.def_status='i'*/) ";
        }
		
		if($data['depart_id']!='All'){
			$and = " where  q.dept_id=? ";
			$secure_array=array($data['session_year'],$data['session'],$data['depart_id']);
			}
		else{
			$and = "";
          $secure_array=array($data['session_year'],$data['session']);
		}			

        $session_year = $data['session_year'];
        $session = $data['session'];
        $depart = $data['depart_id'];

        $this->load->database();

        //print_r($data); die();
        $i = 0;
    


            if ($session <> 'Summer') {
				
			

                $result1 = $this->db->query("SELECT q.def_status AS `status`,
q.ins_per as percentage,
NULL as session_id,
q.admn_no as admission_id,
q.absent_classes as tot_absent,
q.tot_classes as total_class,
format(((q.tot_classes-q.absent_classes)*100/q.tot_classes),2)as percent,
 q.sub_code as subject_id ,
 q.sub_code, 
				q.sub_name ,
				q.sub_offered_id as map_id,
				q.dept_id,
				q.course_id,
				q.branch_id,
				q.semester,q.sname AS stu_name

FROM(
SELECT p.* ,
(case when  p.rstatus='o' then o.dept_id  ELSE c.dept_id  END) AS dept_id,
(case when  p.rstatus='o' then o.course_id  ELSE c.course_id  end)AS course_id,
(case when  p.rstatus='o' then o.branch_id  ELSE c.branch_id  end)AS branch_id,
(case when  p.rstatus='o' then o.sub_name  ELSE c.sub_name  end)AS sub_name,
(case when  p.rstatus='o' then o.semester  ELSE c.semester  end)AS semester,
sc.auth_id,ud.dept_id AS sdept_id,sc.course_id AS scourse_id,sc.branch_id AS sbranch_id,
CONCAT_WS(' ',ud.first_name,ud.middle_name,ud.last_name)AS sname
FROM(
SELECT a.*,SUBSTRING(a.sub_offered_id,1,1)AS rstatus,SUBSTRING(a.sub_offered_id,2)AS subid
FROM cbcs_absent_table_defaulter a 
WHERE $link_from AND a.session_year=? AND a.session=?)p
LEFT  JOIN 
old_subject_offered o ON CONCAT('o',o.id)=p.sub_offered_id
LEFT  JOIN 
cbcs_subject_offered c ON CONCAT('c',c.id)=p.sub_offered_id
left JOIN stu_academic sc ON sc.admn_no=p.admn_no
left JOIN user_details ud ON ud.id=p.admn_no)q
$and
 ",$secure_array);
 

		 if ($result1->num_rows() > 0)
                    $result[] = $result1->result();
                
			   
			   
			   
        return ($result);
    } else{
		// summer case to be handled later on
	}
	}

    public function get_remarks($data) {
        //print_r($data['result']);
        $this->load->database();
        foreach ($data['result'] as $row) {
            $temp = $this->db->query("SELECT enrollment_year as year
			                 FROM stu_academic
			                 WHERE admn_no = '$row->admission_id'
			                  ");
            $temp = $temp->result();
            $year = (int) $temp[0]->year;
            //$cmp = (int)$
            if ($year >= 2013) {

                $temp_new = $this->db->query("SELECT Remark
				                         FROM defaulter_remark_table
				                         WHERE sl_no = 1 ");
                $temp_new = $temp_new->result();
                $remark = $temp_new[0]->Remark;
                $row->remark = $remark . ' ' . $data['session_year'];
            } elseif (0) {

            } else {
                if ($row->percent < 60) {
                    //echo  'hello';
                    $temp_new = $this->db->query("SELECT Remark
					                         FROM defaulter_remark_table
					                         WHERE sl_no = 2 ");
                    $temp_new = $temp_new->result();
                    $remark = $temp_new[0]->Remark;
                    $row->remark = $remark;
                } else {
                    $temp_new = $this->db->query("SELECT Remark
					                         FROM defaulter_remark_table
					                         WHERE sl_no = 3 ");
                    $temp_new = $temp_new->result();
                    $remark = $temp_new[0]->Remark;
                    $row->remark = $remark;
                }
            }
        }
        //return $data;
    }

    public function get_remarks_new($result) {
        //print_r($data['result']);
        $this->load->database();
        foreach ($result as $row) {
            $temp = $this->db->query("SELECT enrollment_year as year
			                 FROM stu_academic
			                 WHERE admn_no = '$row->admission_id'
			                  ");
            $temp = $temp->result();
            $year = (int) $temp[0]->year;
            //$cmp = (int)$
            if ($year >= 2013) {

                $temp_new = $this->db->query("SELECT Remark
				                         FROM defaulter_remark_table
				                         WHERE sl_no = 1 ");
                $temp_new = $temp_new->result();
                $remark = $temp_new[0]->Remark;
                $row->remark = $remark . ' ' . $data['session_year'];
            } elseif (0) {

            } else {
                if ($row->percent < 60) {
                    //echo  'hello';
                    $temp_new = $this->db->query("SELECT Remark
					                         FROM defaulter_remark_table
					                         WHERE sl_no = 2 ");
                    $temp_new = $temp_new->result();
                    $remark = $temp_new[0]->Remark;
                    $row->remark = $remark;
                } else {
                    $temp_new = $this->db->query("SELECT Remark
					                         FROM defaulter_remark_table
					                         WHERE sl_no = 3 ");
                    $temp_new = $temp_new->result();
                    $remark = $temp_new[0]->Remark;
                    $row->remark = $remark;
                }
            }
        }
        //return $data;
    }

    /* function getModFromtech($subid, $stuid) {
      $q = $this->db->get_where('Attendance_remark_table', ['sub_id' => $subid, 'admn_no' => $stuid]);
      if ($q->num_rows() > 0)
      return $q->row();
      return false;
      } */

    //@14-4-18 @ rituraj & anuj as per sum of remark table's count is required
    function getModFromtech($subid, $stuid, $session_id) {
        $q = $this->db->select_sum('count')->get_where('Attendance_remark_table', ['sub_id' => $subid, 'admn_no' => $stuid, 'session_id' => $session_id]);
        if ($q->num_rows() > 0)
            return $q->row();
        return false;
    }

    function get_dept_name($id) {

        $sql = "select name from departments where id=?";

        $query = $this->db->query($sql, array($id));

        //echo $this->db->last_query(); die();
        if ($this->db->affected_rows() >= 0) {
            return $query->row()->name;
        } else {
            return false;
        }
    }

    function get_course_name($id) {

        $sql = "select name from cs_courses where id=?";

        $query = $this->db->query($sql, array($id));

        //echo $this->db->last_query(); die();
        if ($this->db->affected_rows() >= 0) {
            return $query->row()->name;
        } else {
            return false;
        }
    }

    function get_branch_name($id) {

        $sql = "select b.name from stu_academic a inner join cs_branches b on a.branch_id=b.id where a.admn_no=?";

        $query = $this->db->query($sql, array($id));

        //echo $this->db->last_query(); die();
        if ($this->db->affected_rows() >= 0) {
            return $query->row()->name;
        } else {
            return false;
        }
    }

    function show_action_archive($crs_id) {
        /* $sql = " select c.percent ,a.status,b.course_id, b.session_year,  s.subject_id as subject_code,a.sub_id,a.map_id, s.name as  subject_name ,br.name AS branch_name, CONCAT_WS(' ',ud.first_name,ud.middle_name,ud.last_name) AS name, a.admn_no,b.branch_id,b.semester,a.sub_id from absent_table a
          join  subject_mapping b on a.map_id=b.map_id
          join cbcs_absent_table_log c on a.map_id=c.map_id and a.sub_id=c.sub_id and a.admn_no=c.admn_no
          and b.`session_year`=?  and b.`session`=?  and b.dept_id=? and  b.course_id=?  and a.Remark=?
          LEFT JOIN user_details ud ON ud.id=a.admn_no
          LEFT JOIN cs_branches br ON br.id=b.branch_id
          left join subjects s on s.id=a.sub_id
          group by a.map_id,a.admn_no,a.sub_id"; */



        /*$sql = "
            select DATE_FORMAT(c.created_on,'%b %d %Y %h:%i %p') as created,c.percent,x.*,s.subject_id as subject_code,s.name as  subject_name ,br.name AS branch_name,
			  CONCAT_WS(' ',ud.first_name,ud.middle_name,ud.last_name) AS name  from
             (select  subject_mapping.branch_id,subject_mapping.semester,  atd.admn_no, atd.sub_id,atd.map_id,atd.status, subject_mapping. course_id, subject_mapping.session_year
             from  subject_mapping  inner join cbcs_absent_table_defaulter atd on subject_mapping.map_id=atd.map_id  and subject_mapping.`session_year`=?  and subject_mapping.`session`=?
             and subject_mapping.dept_id=? and  subject_mapping.course_id=?  and atd.Remark=? group by atd.map_id,atd.admn_no,atd.sub_id )x
             join cbcs_absent_table_log c on x.map_id=c.map_id and x.sub_id=c.sub_id and x.admn_no=c.admn_no
           LEFT JOIN user_details ud ON ud.id=x.admn_no
           LEFT JOIN cs_branches br ON br.id=x.branch_id
           left join subjects s on s.id=x.sub_id

            ";*/

         $sql="
		    select f.* from
			(select DATE_FORMAT(c.created_on,'%b %d %Y %h:%i %p') as created,c.percent,x.*,br.name AS branch_name,
			  CONCAT_WS(' ',ud.first_name,ud.middle_name,ud.last_name) AS name,c.sub_id  from
		 
		 (SELECT q.def_status AS `status`, q.admn_no ,q.sub_code as subject_code, q.sub_offered_id as map_id,q.dept_id,q.course_id,q.branch_id,q.semester,q.sub_name as subject_name,q.session_year
FROM(
SELECT p.* ,
(case when  p.rstatus='o' then o.dept_id  ELSE c.dept_id  END) AS dept_id,
(case when  p.rstatus='o' then o.course_id  ELSE c.course_id  end)AS course_id,
(case when  p.rstatus='o' then o.branch_id  ELSE c.branch_id  end)AS branch_id,
(case when  p.rstatus='o' then o.semester  ELSE c.semester  end)AS semester,
  (case when  p.rstatus='o' then o.sub_name  ELSE c.sub_name  end)AS sub_name
  
FROM(
SELECT a.*,SUBSTRING(a.sub_offered_id,1,1)AS rstatus,SUBSTRING(a.sub_offered_id,2)AS subid
FROM cbcs_absent_table_defaulter a 
WHERE  a.session_year=? AND a.session=?    and a.remark=?

)p
LEFT  JOIN 
old_subject_offered o ON CONCAT('o',o.id)=p.sub_offered_id 
LEFT  JOIN 
cbcs_subject_offered c ON CONCAT('c',c.id)=p.sub_offered_id
)q   where  q.dept_id=? and q.course_id=? ) x

join 
cbcs_absent_table_log c on x.map_id=c.map_id and x.subject_code=c.sub_id and x.admn_no=c.admn_no
 LEFT JOIN cs_branches br ON br.id=x.branch_id
 LEFT JOIN user_details ud ON ud.id=x.admn_no
 order by  c.admn_no ,c.sub_id,c.created_on desc)f
group by f.admn_no,f.sub_id 
 ";

         
		 
		 



        $query = $this->db->query($sql, array(
            ($this->input->post('session_year') != null ? $this->input->post('session_year') : $this->session->userdata('session_year')),
            ($this->input->post('session') != null ? $this->input->post('session') : $this->session->userdata('session')),
			$this->session->userdata('id'),
            ($this->input->post('depart_id') != null ? $this->input->post('depart_id') : $this->session->userdata('depart_id')),
            $crs_id
            
                )
        );

     //   echo $this->db->last_query(); die();
        if ($query->num_rows() > 0)
            return $query->result_array();
        else {
            return array('result' => 'Failed', 'error' => 'No Record Found');
        }
    }

   function show_action_archive_non_cbcs($crs_id) {
        /* $sql = " select c.percent ,a.status,b.course_id, b.session_year,  s.subject_id as subject_code,a.sub_id,a.map_id, s.name as  subject_name ,br.name AS branch_name, CONCAT_WS(' ',ud.first_name,ud.middle_name,ud.last_name) AS name, a.admn_no,b.branch_id,b.semester,a.sub_id from absent_table a
          join  subject_mapping b on a.map_id=b.map_id
          join absent_table_log c on a.map_id=c.map_id and a.sub_id=c.sub_id and a.admn_no=c.admn_no
          and b.`session_year`=?  and b.`session`=?  and b.dept_id=? and  b.course_id=?  and a.Remark=?
          LEFT JOIN user_details ud ON ud.id=a.admn_no
          LEFT JOIN cs_branches br ON br.id=b.branch_id
          left join subjects s on s.id=a.sub_id
          group by a.map_id,a.admn_no,a.sub_id"; */



        $sql = "
            select DATE_FORMAT(c.created_on,'%b %d %Y %h:%i %p') as created,c.percent,x.*,s.subject_id as subject_code,s.name as  subject_name ,br.name AS branch_name,
			  CONCAT_WS(' ',ud.first_name,ud.middle_name,ud.last_name) AS name  from
             (select  subject_mapping.branch_id,subject_mapping.semester,  atd.admn_no, atd.sub_id,atd.map_id,atd.status, subject_mapping. course_id, subject_mapping.session_year
             from  subject_mapping  inner join absent_table_defaulter atd on subject_mapping.map_id=atd.map_id  and subject_mapping.`session_year`=?  and subject_mapping.`session`=?
             and subject_mapping.dept_id=? and  subject_mapping.course_id=?  and atd.Remark=? group by atd.map_id,atd.admn_no,atd.sub_id )x
             join absent_table_log c on x.map_id=c.map_id and x.sub_id=c.sub_id and x.admn_no=c.admn_no
           LEFT JOIN user_details ud ON ud.id=x.admn_no
           LEFT JOIN cs_branches br ON br.id=x.branch_id
           left join subjects s on s.id=x.sub_id

            ";




        $query = $this->db->query($sql, array(
            ($this->input->post('session_year') != null ? $this->input->post('session_year') : $this->session->userdata('session_year')),
            ($this->input->post('session') != null ? $this->input->post('session') : $this->session->userdata('session')),
            ($this->input->post('depart_id') != null ? $this->input->post('depart_id') : $this->session->userdata('depart_id')),
            $crs_id,
            $this->session->userdata('id')
                )
        );

        //echo $this->db->last_query(); die();
        if ($query->num_rows() > 0)
            return $query->result_array();
        else {
            return array('result' => 'Failed', 'error' => 'No Record Found');
        }
    }








    //------------------------------- starts of sending to acdemic at once irrespective  of department----------------------------
    function send_exam_section_two($syear, $sess) {
        $this->load->database();
        $this->db->query("UPDATE cbcs_absent_table_defaulter  SET def_status='y' where def_status='y'");
        $affected[] = $this->db->affected_rows();
        return (in_array(0, $affected) ? false : true);
    }

    //-------------------------------  end  of sending to acdemic at once irrespective  of department----------------------------





    public function send_exam_section($result) {
        // echo '<pre>';print_r($result);echo '</pre>';
        $this->load->database();
        foreach ($result as $ro) {
            foreach ($ro as $row) {
                //echo '<pre>';print_r($row);echo '</pre>';
                $data_where = array(
                    'admn_no' => $row['admission_id'],
                    'sub_id' => $row['subject_id'],
                    'map_id' => $row['map_id']
                );

                $this->db->where($data_where);
                if (!$this->db->update('cbcs_absent_table_defaulter', array('def_status' => 'y'))) {
                    $returntmsg .= $this->db->_error_message() . ",";
                }

                echo $this->db->last_query();
                $affected[] = $this->db->affected_rows();

                echo $returntmsg;

                print_r($affected);
            }
        }
    }

    function save_allowed($data_input, $type = null) {
        try {
            //print_r($data_input); die();
            if (!empty($data_input)) {
                $returntmsg = "";
                //    $this->db->trans_start();
                $this->db->trans_begin();
                if (!$this->db->insert('cbcs_absent_table_log', $data_input))
                    $returntmsg .= $this->db->_error_message() . ",";
                $affected[] = $this->db->affected_rows();
                $data_put = array(
                    'def_status' => ($type == 'allowed' ? 'n' : (in_array("dean_acad", $this->session->userdata('auth')) || in_array("adpg", $this->session->userdata('auth')) || in_array("adug", $this->session->userdata('auth')) ? 'y' : 'y')),
                    'remark' => $data_input['created_by']
                  
                );
               /* $data_put_old = array(
                    'status' => ($type == 'allowed' ? 'n' : (in_array("dsw", $this->session->userdata('auth')) || in_array("adsw", $this->session->userdata('auth')) || in_array("sw_ar", $this->session->userdata('auth')) ?'i' : 'y'))
                        //'remark' =>$data_input['created_by']
                );*/

                $data_where = array(
                   'def_status' => ($type == 'allowed' ? (in_array("dean_acad", $this->session->userdata('auth')) || in_array("adpg", $this->session->userdata('auth')) || in_array("adug", $this->session->userdata('auth')) ? 'y' : 'y') : 'n'),
                    'admn_no' => $data_input['admn_no'],
                    'sub_code' => $data_input['sub_id'],
                    'sub_offered_id' => $data_input['map_id'],
                );

                $this->db->where($data_where);
                if (!$this->db->update('cbcs_absent_table_defaulter', $data_put)) {
                    $returntmsg .= $this->db->_error_message() . ",";
                }
				// echo $this->db->last_query();
				
                $affected[] = $this->db->affected_rows();


               /* if (in_array("exam_dr", $this->session->userdata('auth'))) {
                    $this->db->where($data_where);
                    if (!$this->db->update('absent_table', $data_put_old)) {
                        $returntmsg .= $this->db->_error_message() . ",";
                    }
                    $affected[] = $this->db->affected_rows();
                    //  echo $this->db->last_query();
                }*/

               //  echo 'ddd'.print_r($affected);die();
                //$this->db->trans_complete();
                if (in_array(0, $affected) || ($this->db->trans_status() === FALSE)) {
                    //if($this->db->trans_status()!= FALSE ) {
                    $this->db->trans_rollback();
                    $returntmsg = "failed";
                } else {
                    $returntmsg = "success";
                    $this->db->trans_commit();
                }
            } else {
                $returntmsg = 'Input data missing';
            }

            return $returntmsg;
        } catch (Exception $e) {

            return $e->getMessage() == null ? 'Internal error ocuured' : $e->getMessage();
        }
    }
	
	
	
    function save_allowed_non_cbcs($data_input, $type = null) {
        try {
            //print_r($data_input); die();
            if (!empty($data_input)) {
                $returntmsg = "";
                //    $this->db->trans_start();
                $this->db->trans_begin();
                if (!$this->db->insert('absent_table_log', $data_input))
                    $returntmsg .= $this->db->_error_message() . ",";
                $affected[] = $this->db->affected_rows();
                $data_put = array(
                    'status' => ($type == 'allowed' ? '0' : (in_array("dsw", $this->session->userdata('auth')) || in_array("adsw", $this->session->userdata('auth')) || in_array("sw_ar", $this->session->userdata('auth')) ? 1 : 2)),
                    'remark' => $data_input['created_by'],
                    'def_status' => ($type == 'allowed' ? 'n' : 'y')
                );
                $data_put_old = array(
                    'status' => ($type == 'allowed' ? '0' : (in_array("dsw", $this->session->userdata('auth')) || in_array("adsw", $this->session->userdata('auth')) || in_array("sw_ar", $this->session->userdata('auth')) ? 1 : 2))
                        //'remark' =>$data_input['created_by']
                );

                $data_where = array(
                    'status' => ($type == 'allowed' ? (in_array("dsw", $this->session->userdata('auth')) || in_array("adsw", $this->session->userdata('auth')) || in_array("sw_ar", $this->session->userdata('auth')) ? 1 : 2) : '0'),
                    'admn_no' => $data_input['admn_no'],
                    'sub_id' => $data_input['sub_id'],
                    'map_id' => $data_input['map_id'],
                );

                $this->db->where($data_where);
                if (!$this->db->update('absent_table_defaulter', $data_put)) {
                    $returntmsg .= $this->db->_error_message() . ",";
                }
                $affected[] = $this->db->affected_rows();


                if (in_array("exam_dr", $this->session->userdata('auth'))) {
                    $this->db->where($data_where);
                    if (!$this->db->update('absent_table', $data_put_old)) {
                        $returntmsg .= $this->db->_error_message() . ",";
                    }
                    $affected[] = $this->db->affected_rows();
                    //  echo $this->db->last_query();
                }

                // echo 'ddd'.print_r($affected);
                //$this->db->trans_complete();
                if (in_array(0, $affected) || ($this->db->trans_status() === FALSE)) {
                    //if($this->db->trans_status()!= FALSE ) {
                    $this->db->trans_rollback();
                    $returntmsg = "failed";
                } else {
                    $returntmsg = "success";
                    $this->db->trans_commit();
                }
            } else {
                $returntmsg = 'Input data missing';
            }

            return $returntmsg;
        } catch (Exception $e) {

            return $e->getMessage() == null ? 'Internal error ocuured' : $e->getMessage();
        }
    }
	
	
	
	
	
	

}

?>