<?php

class Cgpa_correction_model extends CI_Model {

    function __construct() {
        parent::__construct();
    }

    function saveAlternative($alternatArray){
        $whereClouse=array(
          "session_year"=>$alternatArray['session_year'],
          "session"=>$alternatArray['session'],
          "admn_no"=>$alternatArray['admn_no'],
          "old_subject_code"=>$alternatArray['old_subject_code'],
          "alternate_subject_code"=>$alternatArray['alternate_subject_code'],
        );
        $this->db->select('*');
        $this->db->from('alternate_course');
        $this->db->where($whereClouse);
        $cnt=$this->db->get()->num_rows();
        if($cnt==0){
          if($this->db->insert('alternate_course', $alternatArray)){
             return 1;
          }else{
             return 0;
          }
        }else{
             return 2;
        }
    }
    function saveUpdateLog($dataArray){
        $this->db->insert('final_gpa_cgpa_correction_log', $dataArray);
    //    echo $this->db->last_query();
          if ($this->db->affected_rows() > 0) {
            return true;
          }else{
            return false;
          }
    }
    function getAlternatenew($sub_code,$dept_id,$course_id,$branch_id){
      $sql="SELECT a.dept_id,a.course_id,a.branch_id,a.semester,a.sub_code,a.sub_name,a.lecture,a.tutorial,a.practical,a.sub_type,a.credit_hours,a.contact_hours
  FROM old_subject_offered a
  WHERE a.sub_code='$sub_code'  and a.`session`='Monsoon' /*AND a.session_year ='2020-2021' */ AND
   (a.dept_id='$dept_id' or a.dept_id='comm') /* AND (a.course_id ='$course_id' or a.course_id ='comm') AND (a.branch_id='$branch_id' or a.branch_id='comm') */
  UNION
  SELECT a.dept_id,a.course_id,a.branch_id,a.semester,a.sub_code,a.sub_name,a.lecture,a.tutorial,a.practical,a.sub_type,a.credit_hours,a.contact_hours
  FROM cbcs_subject_offered a
  WHERE a.sub_code='$sub_code'  and a.`session`='Monsoon' /*AND a.session_year ='2020-2021' */ AND
  (a.dept_id='$dept_id' or a.dept_id='comm')/* AND (a.course_id ='$course_id' or a.course_id ='comm') AND (a.branch_id='$branch_id' or a.branch_id='comm') */
  ";
    $query = $this->db->query($sql);

    //echo $this->db->last_query(); die();
    if ($this->db->affected_rows() >= 0) {
        return $query->result();
    } else {
        return false;
    }
    }
    function getAlternateold($sub_code,$dept_id,$course_id,$branch_id){
      $sql="select * from (SELECT a.dept_id,a.course_id,a.branch_id,a.semester,a.sub_code,a.sub_name,a.lecture,a.tutorial,a.practical,a.sub_type,a.credit_hours,a.contact_hours
FROM old_subject_offered a
WHERE a.sub_code='$sub_code' AND a.`session`='Monsoon' AND a.session_year ='2020-2021' AND
 (a.dept_id='$dept_id' OR a.dept_id='comm') /* AND (a.course_id ='$course_id' or a.course_id ='comm') AND (a.branch_id='$branch_id' or a.branch_id='comm') */ UNION
SELECT a.dept_id,a.course_id,a.branch_id,a.semester,a.sub_code,a.sub_name,a.lecture,a.tutorial,a.practical,a.sub_type,a.credit_hours,a.contact_hours
FROM cbcs_subject_offered a
WHERE a.sub_code='$sub_code' AND a.`session`='Monsoon' AND a.session_year ='2020-2021' AND
(a.dept_id='$dept_id' OR a.dept_id='comm')/* AND (a.course_id ='$course_id' or a.course_id ='comm') AND (a.branch_id='$branch_id' or a.branch_id='comm') */

 UNION
select a.dept_id,a.course_id,a.branch_id,a.semester,a.sub_code,a.sub_name,a.lecture,a.tutorial,a.practical,a.sub_type,a.credit_hours,a.contact_hours from old_subject_offered a
    where a.sub_code='$sub_code' and a.session_year !='2020-2021' and a.dept_id='$dept_id' and a.course_id ='$course_id' and a.branch_id='$branch_id'
    union
    select '$dept_id' as dept_id,'$course_id' as course_id,'pe' as branch_id,c.semester,b.subject_id,b.name,b.lecture,b.tutorial,b.practical,b.`type`,b.credit_hours,b.contact_hours
    from subjects b
    inner join course_structure c on b.id=c.id
    where b.subject_id='$sub_code')z
group by z.sub_code";
    $query = $this->db->query($sql,array($sub_code,$dept_id,$course_id,$branch_id,$sub_code));

    //echo $this->db->last_query(); die();
    if ($this->db->affected_rows() >= 0) {
        return $query->result();
    } else {
        return false;
    }
    }
    function get_student_details($id){
        $sql = "SELECT UPPER(a.id) AS admn_no,a.dept_id,b.enrollment_year, UPPER(CONCAT_WS(' ',a.first_name,a.middle_name,a.last_name)) AS stu_name,
        CASE b.course_id WHEN  'exemtech' THEN b.course_id /*'M.TECH 3 YR'*/ ELSE b.course_id END as course_id,b.branch_id,c.name,
        UPPER(CONCAT(CASE b.course_id WHEN  'exemtech' THEN 'M.TECH 3 YR' ELSE b.course_id END,' ( ',c.name,' ) ')) AS discipline,
        a.photopath
        , d.name as cname,c.name as bname
        FROM user_details a
        INNER JOIN stu_academic b ON b.admn_no=a.id
        inner join cs_courses d on d.id=b.course_id
        INNER JOIN cs_branches c ON c.id=b.branch_id
        WHERE a.id=?";
        $query = $this->db->query($sql,array($id));
        //echo $this->db->last_query();die();
        if ($this->db->affected_rows() >= 0) {
            return $query->row();
        } else {
            return false;
        }
      }

    function checkAlternateExists($sub_code1,$sub_code2,$admn_no){
      $sql="select * from (select a.old_subject_code,a.alternate_subject_code from alternate_course a
      where a.old_subject_code='$sub_code1' and a.alternate_subject_code='$sub_code2' and a.admn_no='$admn_no'
      union
      select a.old_subject_code,a.alternate_subject_code from alternate_course_all a
      where a.old_subject_code='$sub_code1' and a.alternate_subject_code='$sub_code2') z";
      $query = $this->db->query($sql);
      return $query->num_rows();
    }

    function checkalternate($admn_no){
      $sql="
      SELECT *
FROM (
SELECT GROUP_CONCAT(DISTINCT CONCAT_WS('-',x.sub_code,x.sub_names,x.grade)
ORDER BY x.foil_id ASC) AS alternate, COUNT(DISTINCT (x.sub_code)) AS sub_code_cnt,x.*
FROM (
SELECT b.sub_code, IF(c.sub_name IS NULL,d.name,c.sub_name) AS sub_names, IF(c.sub_name IS NULL,
REPLACE(LOWER(d.name),' ',''),
REPLACE(LOWER(c.sub_name),' ','')) AS sub_name,b.grade,x.*
FROM (
SELECT x.*
FROM (
SELECT a.session_yr,a.session,(CASE WHEN a.`session`='Monsoon' THEN 1 WHEN a.`session`='Winter' THEN 2 ELSE 3 END) AS s_session, a.admn_no,a.dept,a.course,a.branch,a.semester,a.old_id,a.id AS foil_id, a.semester AS sem, a.published_on, IF(a.actual_published_on IS NULL,'Result not Declared',a.actual_published_on) AS actual_published_on,a.`type`
FROM final_semwise_marks_foil_freezed AS a
WHERE a.admn_no='$admn_no' AND UPPER(a.course)<>'MINOR' AND (a.semester!= '0' AND a.semester!='-1') AND a.course<>'jrf'
ORDER BY a.admn_no, /*a.session_yr,a.session */ a.semester,a.actual_published_on DESC
LIMIT 100000000)x
GROUP BY x.admn_no, x.session_yr,x.session,x.sem/* IFNULL(x.sem, x.session_yr)*/ /*having x.semester<= x.reg_sem*/
ORDER BY x.admn_no,x.semester,x.actual_published_on desc
LIMIT 100000000) x
INNER JOIN final_semwise_marks_foil_desc_freezed b ON x.foil_id=b.foil_id AND (CASE WHEN x.session='Summer' OR x.type='O' AND x.session_yr < '2019-2020' THEN b.current_exam='Y' ELSE 1=1 END)
LEFT JOIN ((
SELECT a.sub_code,a.sub_name
FROM old_subject_offered a
GROUP BY a.sub_code) UNION (
SELECT a.sub_code,a.sub_name
FROM cbcs_subject_offered a
GROUP BY a.sub_code)) c ON b.sub_code=c.sub_code
LEFT JOIN subjects d ON b.sub_code=d.subject_id AND d.subject_id NOT IN (
SELECT *
FROM ((
SELECT a.sub_code
FROM old_subject_offered a
GROUP BY a.sub_code) UNION (
SELECT a.sub_code
FROM cbcs_subject_offered a
GROUP BY a.sub_code))x)
GROUP BY b.foil_id,b.sub_code
ORDER BY x.session_yr,x.s_session,x.sem,x.actual_published_on ASC
LIMIT 10000000) x
GROUP BY LOWER(TRIM(x.sub_name)))x
WHERE x.sub_code_cnt > 1
      ";

$query = $this->db->query($sql);
//  echo "<br><br><br>". $this->db->last_query();
if ($query->num_rows() > 0)
return $query->result();
  else
return 0;

    }
    function updateOpga($admn_no,$freeze_id,$foil_id,$new_gpa,$new_core_gpa){
      $updateWhere=array(
        "old_id"=>$foil_id,
        "id"=>$freeze_id,
        "admn_no"=>$admn_no
      );
      $updateWherefoil=array(
        "id"=>$foil_id,
        "admn_no"=>$admn_no
      );
    //  echo $updateWhere;
      $updatevalue=array(
        "gpa"=>$new_gpa,
        "core_gpa"=>$new_core_gpa
      );
      $this->db->where($updateWhere);
      $this->db->update('final_semwise_marks_foil_freezed',$updatevalue);

      $this->db->where($updateWherefoil);
      $this->db->update('final_semwise_marks_foil',$updatevalue);
    //  echo $this->db->last_query();
      if($this->db->affected_rows()>0){
          return true;
      }else{
          return false;
      }
    }

    function updateCpga($admn_no,$freeze_id,$foil_id,$new_cgpa,$new_core_cgpa){
      $updateWhere=array(
        "old_id"=>$foil_id,
        "id"=>$freeze_id,
        "admn_no"=>$admn_no
      );
      $updateWherefoil=array(
        "id"=>$foil_id,
        "admn_no"=>$admn_no
      );
    //  echo $updateWhere;
      $updatevalue=array(
        "cgpa"=>$new_cgpa,
        "core_cgpa"=>$new_core_cgpa
      );
      $this->db->where($updateWhere);
      $this->db->update('final_semwise_marks_foil_freezed',$updatevalue);

      $this->db->where($updateWherefoil);
      $this->db->update('final_semwise_marks_foil',$updatevalue);
    //  echo $this->db->last_query();
      if($this->db->affected_rows()>0){
          return true;
      }else{
          return false;
      }
    }
    function get_Expected_SGPA($admn_no,$sem,$foil_id,$session,$session_year){
    //  echo $sem;
      $sql="(SELECT SUM(IF((z.grade='I' OR z.grade='F'), 0, z.cr_pts)) AS ctotcrpts, SUM(z.cr_hr) AS ctotcrhr, FORMAT((SUM(IF((z.grade='I' OR z.grade='F'), 0, z.cr_pts))/ SUM(z.cr_hr)),5) AS cgpa, SUM(IF (((z.course_id<> 'honour' OR z.course_id IS NULL) AND (z.grade<>'I' AND z.grade<>'F')), z.cr_pts, 0)) AS core_ctotcrpts, SUM(IF (((z.course_id<> 'honour' OR z.course_id IS NULL)), z.cr_hr, 0)) AS core_ctotcrhr, FORMAT((SUM(IF (((z.course_id<> 'honour' OR z.course_id IS NULL) AND (z.grade<>'I' AND z.grade<>'F')), z.cr_pts, 0))/ SUM(IF (((z.course_id<> 'honour' OR z.course_id IS NULL)), z.cr_hr, 0))),5) AS core_cgpa,
z.admn_no,z.semester,z.foil_id
FROM
(
SELECT z1.*
FROM
(
SELECT v.*
FROM (
SELECT y.foil_id, y.session_yr,y.session,y.dept,y.course,y.branch,y.semester,fd.sub_code, fd.grade,
(case when ((y.session_yr='2019-2020' and  (y.session='Winter' or y.session='Summer')) or  (y.session_yr='2020-2021' and  (y.session='Monsoon' /*or  y.session='Winter' or  y.session='Summer' */)) ) then (fd.cr_pts)/2 else fd.cr_pts end) as cr_pts,
  (case when ((y.session_yr='2019-2020' and  (y.session='Winter' or y.session='Summer')) or  (y.session_yr='2020-2021' and  (y.session='Monsoon' /*or  y.session='Winter' or  y.session='Summer' */)) ) then (fd.cr_hr)/2 else fd.cr_hr end) as cr_hr,
  fd.mis_sub_id,
y.admn_no, y.ctotcrpts AS stored_ctotcrpts,y.ctotcrhr AS stored_ctotcrhr,y.core_ctotcrpts AS stored_core_ctotcrpts,y.core_ctotcrhr AS stored_core_ctotcrhr, IF(ac.alternate_subject_code IS NOT NULL,ac.old_subject_code, IF(acl.alternate_subject_code IS NOT NULL,acl.old_subject_code,fd.sub_code)) AS newsub, IF(o.course_id IS NULL, IF(cs.id IS NOT NULL, 'honour', NULL),o.course_id) AS course_id,cs.id
FROM (
SELECT x.*
FROM (
SELECT a.session_yr,a.session,a.admn_no,a.dept,a.course,a.branch,a.semester,a.id AS foil_id,a.`status`,
a.ctotcrpts,a.ctotcrhr, a.core_ctotcrpts,a.core_ctotcrhr, a.tot_cr_hr,a.tot_cr_pts, a.core_tot_cr_hr,a.core_tot_cr_pts, a.semester AS sem,a.`type`,
 a.published_on, a.actual_published_on
FROM final_semwise_marks_foil_freezed AS a
WHERE a.admn_no='$admn_no' AND UPPER(a.course)<>'MINOR' AND (a.semester!= '0' AND a.semester!='-1') AND a.course<>'jrf'  having
 (a.semester!= '0' AND a.semester!='-1') AND a.course<>'jrf' and a.id='$foil_id' and a.`session`='$session' and a.session_yr='$session_year'
ORDER BY a.admn_no,a.semester,a.actual_published_on DESC
LIMIT 100000000)x
GROUP BY x.admn_no,x.session_yr,x.session,x.sem/*, IFNULL(x.sem, x.session_yr)*/
ORDER BY x.admn_no,x.session_yr,x.session,x.actual_published_on DESC
LIMIT 100000000) y
JOIN final_semwise_marks_foil_desc_freezed fd ON fd.foil_id=y.foil_id AND
fd.admn_no=y.admn_no  and (case when y.session='Summer' or y.type='O' and y.session_yr < '2019-2020' then fd.current_exam='Y' else 1=1 end)
LEFT JOIN alternate_course ac ON ac.session_year=y.session_yr AND ac.`session`=y.session AND
ac.admn_no=y.admn_no AND ac.alternate_subject_code=fd.sub_code
LEFT JOIN alternate_course_all acl ON acl.session_year=y.session_yr AND acl.`session`=y.session AND
acl.alternate_subject_code=fd.sub_code
LEFT JOIN old_subject_offered o ON o.sub_code=fd.sub_code AND
o.session_year=y.session_yr AND o.`session`=y.session AND o.dept_id=y.dept AND (CASE WHEN o.course_id='honour' THEN 'honour' ELSE y.course END)=o.course_id AND o.branch_id=y.branch
LEFT JOIN subjects s ON s.id = fd.mis_sub_id AND y.session_yr<'2019-2020'
LEFT JOIN course_structure cs ON cs.id= fd.mis_sub_id AND cs.aggr_id LIKE '%honour%' AND cs.semester=y.semester AND y.session_yr<'2019-2020'
ORDER BY y.admn_no, newsub, fd.cr_pts DESC,y.session_yr DESC
LIMIT 10000000)v
GROUP BY v.admn_no, v.newsub
ORDER BY v.admn_no, v.session_yr,v.dept,v.course,v.branch,v.semester,v.newsub
LIMIT 10000000) z1
GROUP BY z1.admn_no,z1.sub_code order by z1.foil_id desc)z
GROUP BY
z.admn_no)";
      $query = $this->db->query($sql);
    //  echo "<br><br><br>". $this->db->last_query();
      if ($query->num_rows() > 0)
      return $query->result();
        else
      return false;
      //return $admn_no.$sem.$foil_id;
    }
	
	function get_Expected_CGPA($admn_no,$sem,$foil_id,$session,$session_year,$actual_published_on,$ids){
        $sql="(
SELECT SUM(IF((z.grade='I' OR z.grade='F'), 0, z.cr_pts)) AS ctotcrpts, SUM(z.cr_hr) AS ctotcrhr, FORMAT((SUM(IF((z.grade='I' OR z.grade='F'), 0, z.cr_pts))/ SUM(z.cr_hr)),5) AS cgpa, SUM(IF (((z.course_id<> 'honour' OR z.course_id IS NULL) AND (z.grade<>'I' AND z.grade<>'F')), z.cr_pts, 0)) AS core_ctotcrpts, SUM(IF (((z.course_id<> 'honour' OR z.course_id IS NULL)), z.cr_hr, 0)) AS core_ctotcrhr, FORMAT((SUM(IF (((z.course_id<> 'honour' OR z.course_id IS NULL) AND (z.grade<>'I' AND z.grade<>'F')), z.cr_pts, 0))/ SUM(IF (((z.course_id<> 'honour' OR z.course_id IS NULL)), z.cr_hr, 0))),5) AS core_cgpa, z.admn_no,z.semester,z.foil_id
FROM (
SELECT z1.*
FROM (
SELECT v.*
FROM (
SELECT y.foil_id, y.session_yr,y.session,y.dept,y.course,y.branch,y.semester,fd.sub_code, fd.grade, (CASE WHEN ((y.session_yr='2019-2020' AND (y.session='Winter' OR y.session='Summer')) OR (y.session_yr='2020-2021' AND (y.session='Monsoon' /*or y.session='Winter' or y.session='Summer' */))) THEN (fd.cr_pts)/2 ELSE fd.cr_pts END) AS cr_pts, (CASE WHEN ((y.session_yr='2019-2020' AND (y.session='Winter' OR y.session='Summer')) OR (y.session_yr='2020-2021' AND (y.session='Monsoon' /*or y.session='Winter' or y.session='Summer' */))) THEN ((CASE WHEN fd.sub_code IN ((
SELECT a.sub_code
FROM cbcs_subject_offered a
WHERE a.sub_type='online' UNION
SELECT a.sub_code
FROM old_subject_offered a
WHERE a.sub_type='online')) THEN 0 ELSE fd.cr_hr END))/2 ELSE (CASE WHEN fd.sub_code IN ((
SELECT a.sub_code
FROM cbcs_subject_offered a
WHERE a.sub_type='online' UNION
SELECT a.sub_code
FROM old_subject_offered a
WHERE a.sub_type='online')) THEN 0 ELSE fd.cr_hr END) END) AS cr_hr, fd.mis_sub_id, y.admn_no, y.ctotcrpts AS stored_ctotcrpts,y.ctotcrhr AS stored_ctotcrhr,y.core_ctotcrpts AS stored_core_ctotcrpts,y.core_ctotcrhr AS stored_core_ctotcrhr, ac.alternate_subject_code,acl.old_subject_code, IF(ac.alternate_subject_code IS NOT NULL,ac.old_subject_code, IF(acl.alternate_subject_code IS NOT NULL,acl.old_subject_code,fd.sub_code)) AS newsub, IF(o.course_id IS NULL, IF(cs.id IS NOT NULL, 'honour', NULL),o.course_id) AS course_id,cs.id
FROM(
SELECT *
FROM (
SELECT *
FROM (
SELECT x.*
FROM (
SELECT a.session_yr,a.session,a.admn_no,a.dept,a.course,a.branch,a.semester,a.id AS foil_id,a.`status`, a.ctotcrpts,a.ctotcrhr, a.core_ctotcrpts,a.core_ctotcrhr, a.tot_cr_hr,a.tot_cr_pts, a.core_tot_cr_hr,a.core_tot_cr_pts, a.semester AS sem,a.`type`, a.published_on, a.actual_published_on
FROM final_semwise_marks_foil_freezed AS a
WHERE a.id IN ($ids) AND UPPER(a.course)<>'MINOR' AND (a.semester!= '0' AND a.semester!='-1') AND a.course<>'jrf' AND a.admn_no='$admn_no' /*a.admn_no='16JE001988'
 AND UPPER(a.course)<>'MINOR' AND (a.semester!= '0' AND a.semester!='-1') AND a.course<>'jrf' and a.semester<='$sem' #and a.id='3231' and a.`session`='Monsoon' and a.session_yr='2017-2018' */ ORDER BY a.admn_no,a.semester,a.actual_published_on DESC LIMIT 100000000)x GROUP BY x.admn_no,x.session_yr,x.session,x.sem/*, IFNULL(x.sem, x.session_yr)*/ ORDER BY x.admn_no,x.session_yr,x.session,x.actual_published_on DESC LIMIT 100000000) y order by y.session_yr desc,y.semester,y.actual_published_on desc limit 10000000)x
group by x.semester,x.admn_no, IF(/*x.semester<= x.reg_sem and*/ x.session_yr>='2019-2020',x.session_yr,
NULL), IF(/*x.semester<= x.reg_sem and*/ x.session_yr>='2019-2020',x.session, NULL)) y
JOIN final_semwise_marks_foil_desc_freezed fd ON fd.foil_id=y.foil_id AND fd.admn_no=y.admn_no /* and (case when y.session='Summer' or y.type='O' and y.session_yr < '2019-2020' then fd.current_exam='Y' else 1=1 end) */
LEFT JOIN alternate_course ac ON TRIM(ac.admn_no)= TRIM(y.admn_no) AND TRIM(ac.alternate_subject_code)= TRIM(fd.sub_code)
LEFT JOIN alternate_course_all acl ON /*acl.session_year=y.session_yr AND acl.`session`=y.session AND*/ acl.alternate_subject_code=fd.sub_code
LEFT JOIN old_subject_offered o ON o.sub_code=fd.sub_code AND o.session_year=y.session_yr AND o.`session`=y.session AND o.dept_id=y.dept AND (CASE WHEN o.course_id='honour' THEN 'honour' ELSE y.course END)=o.course_id AND o.branch_id=y.branch
LEFT JOIN subjects s ON s.id = fd.mis_sub_id AND y.session_yr<'2019-2020'
LEFT JOIN course_structure cs ON cs.id= fd.mis_sub_id AND cs.aggr_id LIKE '%honour%' AND cs.semester=y.semester AND y.session_yr<'2019-2020'
ORDER BY y.session_yr DESC,y.admn_no,newsub, fd.cr_pts DESC,y.semester
LIMIT 10000000) v
GROUP BY v.admn_no, v.newsub
ORDER BY v.admn_no, v.session_yr,v.dept,v.course,v.branch,v.semester,v.newsub DESC
LIMIT 10000000) z1
GROUP BY z1.admn_no,z1.sub_code
ORDER BY z1.foil_id DESC)z
GROUP BY z.admn_no)";
        $query = $this->db->query($sql);
      //  echo "<br><br><br>". $this->db->last_query();// exit;
        if ($query->num_rows() > 0)
        return $query->result();
          else
        return false;
      }
	
	function get_Expected_CGPA_old($admn_no,$sem,$foil_id,$session,$session_year,$actual_published_on,$ids){
      //echo $ids;
      $sql="(SELECT SUM(IF((z.grade='I' OR z.grade='F'), 0, z.cr_pts)) AS ctotcrpts, SUM(z.cr_hr) AS ctotcrhr, FORMAT((SUM(IF((z.grade='I' OR z.grade='F'), 0, z.cr_pts))/ SUM(z.cr_hr)),5) AS cgpa, SUM(IF (((z.course_id<> 'honour' OR z.course_id IS NULL) AND (z.grade<>'I' AND z.grade<>'F')), z.cr_pts, 0)) AS core_ctotcrpts, SUM(IF (((z.course_id<> 'honour' OR z.course_id IS NULL)), z.cr_hr, 0)) AS core_ctotcrhr, FORMAT((SUM(IF (((z.course_id<> 'honour' OR z.course_id IS NULL) AND (z.grade<>'I' AND z.grade<>'F')), z.cr_pts, 0))/ SUM(IF (((z.course_id<> 'honour' OR z.course_id IS NULL)), z.cr_hr, 0))),5) AS core_cgpa,
z.admn_no,z.semester,z.foil_id
FROM
(
SELECT z1.*
FROM
(
SELECT v.*
FROM (
SELECT y.foil_id, y.session_yr,y.session,y.dept,y.course,y.branch,y.semester,fd.sub_code, fd.grade,
(case when ((y.session_yr='2019-2020' and  (y.session='Winter' or y.session='Summer')) or  (y.session_yr='2020-2021' and  (y.session='Monsoon' /*or  y.session='Winter' or  y.session='Summer' */)) ) then (fd.cr_pts)/2 else fd.cr_pts end) as cr_pts,
  (case when ((y.session_yr='2019-2020' and  (y.session='Winter' or y.session='Summer')) or  (y.session_yr='2020-2021' and  (y.session='Monsoon' /*or  y.session='Winter' or  y.session='Summer' */)) ) then ((case when fd.sub_code in ((select a.sub_code from cbcs_subject_offered a
where a.sub_type='online'
union
select a.sub_code from old_subject_offered a
where a.sub_type='online')) then 0 else fd.cr_hr end))/2 else (case when fd.sub_code in ((select a.sub_code from cbcs_subject_offered a
where a.sub_type='online'
union
select a.sub_code from old_subject_offered a
where a.sub_type='online')) then 0 else fd.cr_hr end) end) as cr_hr,
  fd.mis_sub_id,
y.admn_no, y.ctotcrpts AS stored_ctotcrpts,y.ctotcrhr AS stored_ctotcrhr,y.core_ctotcrpts AS stored_core_ctotcrpts,y.core_ctotcrhr AS stored_core_ctotcrhr, IF(ac.alternate_subject_code IS NOT NULL,ac.old_subject_code, IF(acl.alternate_subject_code IS NOT NULL,acl.old_subject_code,fd.sub_code)) AS newsub, IF(o.course_id IS NULL, IF(cs.id IS NOT NULL, 'honour', NULL),o.course_id) AS course_id,cs.id
FROM (
SELECT x.*
FROM (
SELECT a.session_yr,a.session,a.admn_no,a.dept,a.course,a.branch,a.semester,a.id AS foil_id,a.`status`,
a.ctotcrpts,a.ctotcrhr, a.core_ctotcrpts,a.core_ctotcrhr, a.tot_cr_hr,a.tot_cr_pts, a.core_tot_cr_hr,a.core_tot_cr_pts, a.semester AS sem,a.`type`,
 a.published_on, a.actual_published_on
FROM final_semwise_marks_foil_freezed AS a
WHERE a.id in ($ids) AND UPPER(a.course)<>'MINOR' AND (a.semester!= '0' AND a.semester!='-1') AND a.course<>'jrf'  and a.admn_no='$admn_no'

 /*a.admn_no='$admn_no' AND UPPER(a.course)<>'MINOR' AND
(a.semester!= '0' AND a.semester!='-1') AND a.course<>'jrf' and a.semester<='$sem' #and a.id='$foil_id' and a.`session`='$session' and a.session_yr='$session_year' */
ORDER BY a.admn_no,a.semester,a.actual_published_on DESC
LIMIT 100000000)x
GROUP BY x.admn_no,x.session_yr,x.session,x.sem/*, IFNULL(x.sem, x.session_yr)*/
ORDER BY x.admn_no,x.session_yr,x.session,x.actual_published_on DESC
LIMIT 100000000) y
JOIN final_semwise_marks_foil_desc_freezed fd ON fd.foil_id=y.foil_id AND
fd.admn_no=y.admn_no /* and (case when y.session='Summer' or y.type='O' and y.session_yr < '2019-2020' then fd.current_exam='Y' else 1=1 end) */
LEFT JOIN alternate_course ac ON /*ac.session_year=y.session_yr AND ac.`session`=y.session AND */
ac.admn_no=y.admn_no AND ac.alternate_subject_code=fd.sub_code
LEFT JOIN alternate_course_all acl ON /*acl.session_year=y.session_yr AND acl.`session`=y.session AND*/
acl.alternate_subject_code=fd.sub_code
LEFT JOIN old_subject_offered o ON o.sub_code=fd.sub_code AND
o.session_year=y.session_yr AND o.`session`=y.session AND o.dept_id=y.dept AND (CASE WHEN o.course_id='honour' THEN 'honour' ELSE y.course END)=o.course_id AND o.branch_id=y.branch
LEFT JOIN subjects s ON s.id = fd.mis_sub_id AND y.session_yr<'2019-2020'
LEFT JOIN course_structure cs ON cs.id= fd.mis_sub_id AND cs.aggr_id LIKE '%honour%' AND cs.semester=y.semester AND y.session_yr<'2019-2020'
ORDER BY y.admn_no, newsub, fd.cr_pts DESC,y.session_yr DESC
LIMIT 10000000)v
GROUP BY v.admn_no, v.newsub
ORDER BY v.admn_no, v.session_yr,v.dept,v.course,v.branch,v.semester,v.newsub
LIMIT 10000000) z1
GROUP BY z1.admn_no,z1.sub_code order by z1.foil_id desc)z
GROUP BY
z.admn_no)";
      $query = $this->db->query($sql);
  //   echo "<br><br><br>". $this->db->last_query();// exit;
      if ($query->num_rows() > 0)
      return $query->result();
        else
      return false;
      //return $admn_no.$sem.$foil_id;
    }
	
    function get_Expected_CGPA_20_01_2020($admn_no,$sem,$foil_id,$session,$session_year,$actual_published_on,$ids){
      //echo $ids;
      $sql="(SELECT SUM(IF((z.grade='I' OR z.grade='F'), 0, z.cr_pts)) AS ctotcrpts, SUM(z.cr_hr) AS ctotcrhr, FORMAT((SUM(IF((z.grade='I' OR z.grade='F'), 0, z.cr_pts))/ SUM(z.cr_hr)),5) AS cgpa, SUM(IF (((z.course_id<> 'honour' OR z.course_id IS NULL) AND (z.grade<>'I' AND z.grade<>'F')), z.cr_pts, 0)) AS core_ctotcrpts, SUM(IF (((z.course_id<> 'honour' OR z.course_id IS NULL)), z.cr_hr, 0)) AS core_ctotcrhr, FORMAT((SUM(IF (((z.course_id<> 'honour' OR z.course_id IS NULL) AND (z.grade<>'I' AND z.grade<>'F')), z.cr_pts, 0))/ SUM(IF (((z.course_id<> 'honour' OR z.course_id IS NULL)), z.cr_hr, 0))),5) AS core_cgpa,
z.admn_no,z.semester,z.foil_id
FROM
(
SELECT z1.*
FROM
(
SELECT v.*
FROM (
SELECT y.foil_id, y.session_yr,y.session,y.dept,y.course,y.branch,y.semester,fd.sub_code, fd.grade,
(case when ((y.session_yr='2019-2020' and  (y.session='Winter' or y.session='Summer')) or  (y.session_yr='2020-2021' and  (y.session='Monsoon' /*or  y.session='Winter' or  y.session='Summer' */)) ) then (fd.cr_pts)/2 else fd.cr_pts end) as cr_pts,
  (case when ((y.session_yr='2019-2020' and  (y.session='Winter' or y.session='Summer')) or  (y.session_yr='2020-2021' and  (y.session='Monsoon' /*or  y.session='Winter' or  y.session='Summer' */)) ) then (fd.cr_hr)/2 else fd.cr_hr end) as cr_hr,
  fd.mis_sub_id,
y.admn_no, y.ctotcrpts AS stored_ctotcrpts,y.ctotcrhr AS stored_ctotcrhr,y.core_ctotcrpts AS stored_core_ctotcrpts,y.core_ctotcrhr AS stored_core_ctotcrhr, IF(ac.alternate_subject_code IS NOT NULL,ac.old_subject_code, IF(acl.alternate_subject_code IS NOT NULL,acl.old_subject_code,fd.sub_code)) AS newsub, IF(o.course_id IS NULL, IF(cs.id IS NOT NULL, 'honour', NULL),o.course_id) AS course_id,cs.id
FROM (
SELECT x.*
FROM (
SELECT a.session_yr,a.session,a.admn_no,a.dept,a.course,a.branch,a.semester,a.id AS foil_id,a.`status`,
a.ctotcrpts,a.ctotcrhr, a.core_ctotcrpts,a.core_ctotcrhr, a.tot_cr_hr,a.tot_cr_pts, a.core_tot_cr_hr,a.core_tot_cr_pts, a.semester AS sem,a.`type`,
 a.published_on, a.actual_published_on
FROM final_semwise_marks_foil_freezed AS a
WHERE a.id in ($ids) AND UPPER(a.course)<>'MINOR' AND (a.semester!= '0' AND a.semester!='-1') AND a.course<>'jrf'  and a.admn_no='$admn_no'

 /*a.admn_no='$admn_no' AND UPPER(a.course)<>'MINOR' AND
(a.semester!= '0' AND a.semester!='-1') AND a.course<>'jrf' and a.semester<='$sem' #and a.id='$foil_id' and a.`session`='$session' and a.session_yr='$session_year' */
ORDER BY a.admn_no,a.semester,a.actual_published_on DESC
LIMIT 100000000)x
GROUP BY x.admn_no,x.session_yr,x.session,x.sem/*, IFNULL(x.sem, x.session_yr)*/
ORDER BY x.admn_no,x.session_yr,x.session,x.actual_published_on DESC
LIMIT 100000000) y
JOIN final_semwise_marks_foil_desc_freezed fd ON fd.foil_id=y.foil_id AND
fd.admn_no=y.admn_no /* and (case when y.session='Summer' or y.type='O' and y.session_yr < '2019-2020' then fd.current_exam='Y' else 1=1 end) */
LEFT JOIN alternate_course ac ON /*ac.session_year=y.session_yr AND ac.`session`=y.session AND */
ac.admn_no=y.admn_no AND ac.alternate_subject_code=fd.sub_code
LEFT JOIN alternate_course_all acl ON /*acl.session_year=y.session_yr AND acl.`session`=y.session AND*/
acl.alternate_subject_code=fd.sub_code
LEFT JOIN old_subject_offered o ON o.sub_code=fd.sub_code AND
o.session_year=y.session_yr AND o.`session`=y.session AND o.dept_id=y.dept AND (CASE WHEN o.course_id='honour' THEN 'honour' ELSE y.course END)=o.course_id AND o.branch_id=y.branch
LEFT JOIN subjects s ON s.id = fd.mis_sub_id AND y.session_yr<'2019-2020'
LEFT JOIN course_structure cs ON cs.id= fd.mis_sub_id AND cs.aggr_id LIKE '%honour%' AND cs.semester=y.semester AND y.session_yr<'2019-2020'
ORDER BY y.admn_no, newsub, fd.cr_pts DESC,y.session_yr DESC
LIMIT 10000000)v
GROUP BY v.admn_no, v.newsub
ORDER BY v.admn_no, v.session_yr,v.dept,v.course,v.branch,v.semester,v.newsub
LIMIT 10000000) z1
GROUP BY z1.admn_no,z1.sub_code order by z1.foil_id desc)z
GROUP BY
z.admn_no)";
      $query = $this->db->query($sql);
    //  echo "<br><br><br>". $this->db->last_query();// exit;
      if ($query->num_rows() > 0)
      return $query->result();
        else
      return false;
      //return $admn_no.$sem.$foil_id;
    }

    function getName($admn_no){
      $sql="select a.id,concat_ws(' ',a.first_name,a.middle_name,a.last_name) as name from user_details a
            where a.id='$admn_no'";
              $query = $this->db->query($sql);
            if ($query->num_rows() > 0)
            return $query->result();
              else
            return false;
    }
    function getupdateLog(){
      $sql="select a.*,if(a.isUpdated=1,'CGPA/SGPA affected','CGPA/SGPA not affected') as isAffected from final_gpa_cgpa_correction_log a
            order by a.id desc";
            $query = $this->db->query($sql);
            if ($query->num_rows() > 0)
            return $query->result();
            else
            return false;
    }
    function getResults($admn_no){
      $sql="SELECT x.*, GROUP_CONCAT(CONCAT_WS('|',b.sub_code,b.cr_hr,b.cr_pts,b.grade)) AS sub_details
FROM (
SELECT x.*
FROM (
SELECT a.session_yr,a.session,(case when a.`session`='Monsoon' then 1 when a.`session`='Winter' then 2  else 3 end) as s_session,
a.admn_no,a.dept,a.course,a.branch,a.semester,a.old_id,a.id AS foil_id,
IF(a.`status` IS NULL,'NA',a.`status`) AS status, IF(a.ctotcrpts IS NULL,'NA',a.ctotcrpts) AS ctotcrpts,
IF(a.ctotcrhr IS NULL,'NA',a.ctotcrhr) AS ctotcrhr, IF(a.core_ctotcrpts IS NULL,'NA',a.core_ctotcrpts) AS core_ctotcrpts,
IF(a.core_ctotcrhr IS NULL,'NA',a.core_ctotcrhr) AS core_ctotcrhr, IF(a.tot_cr_hr IS NULL,'NA',a.tot_cr_hr) AS tot_cr_hr,
IF(a.tot_cr_pts IS NULL,'NA',a.tot_cr_pts) AS tot_cr_pts, IF(a.core_tot_cr_hr IS NULL,'NA',a.core_tot_cr_hr) AS core_tot_cr_hr,
IF(a.core_tot_cr_pts IS NULL,'NA',a.core_tot_cr_pts) AS core_tot_cr_pts, IF(a.gpa IS NULL,'NA',a.gpa) AS gpa,
IF(a.core_gpa IS NULL,'NA',a.core_gpa) AS core_gpa, IF(a.cgpa IS NULL,'NA',a.cgpa) AS cgpa, IF(a.core_cgpa IS NULL,'NA',a.core_cgpa) AS core_cgpa,
a.semester AS sem, a.published_on,
IF(a.actual_published_on IS NULL,'Result not Declared',a.actual_published_on) AS actual_published_on,a.`type`
FROM final_semwise_marks_foil_freezed AS a
WHERE a.admn_no='$admn_no' AND UPPER(a.course)<>'MINOR' AND (a.semester!= '0' AND a.semester!='-1') AND a.course<>'jrf'
ORDER BY a.admn_no, /*a.session_yr,a.session */ a.semester,a.actual_published_on DESC
LIMIT 100000000)x
GROUP BY x.admn_no, x.session_yr,x.session,x.sem/* IFNULL(x.sem, x.session_yr)*/ /*having x.semester<= x.reg_sem*/
ORDER BY x.admn_no,x.semester,x.actual_published_on DESC
LIMIT 100000000)x
INNER JOIN final_semwise_marks_foil_desc_freezed b ON x.foil_id=b.foil_id and (case when x.session='Summer' or x.type='O' and x.session_yr < '2019-2020' then b.current_exam='Y' else 1=1 end)
GROUP BY b.foil_id
ORDER BY x.session_yr,x.s_session,x.sem,x.actual_published_on ASC";
      $query = $this->db->query($sql);
    // echo $this->db->last_query(); die();
      if ($query->num_rows() > 0)
      return $query->result();
        else
      return false;
    }
    function checkAdmnNo($admn_no){
      $sql="select * from user_details where id='$admn_no'";
      $query = $this->db->query($sql);
      if ($query->num_rows() > 0)
      return true;
        else
      return false;
    }
}
?>
