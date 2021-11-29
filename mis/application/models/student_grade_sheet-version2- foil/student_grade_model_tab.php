<?php

class Student_grade_model_tab extends CI_Model {

    function __construct() {
        // Call the Model constructor
        parent::__construct();
    }

    function get_tab_status_tabulation_tbl($admn_no,$sem)
    {
        
       	if($sem==10){$sem='X';}
        $sql="SELECT 
            ysession, wsms, examtype
        FROM
            tabulation1 a
         WHERE
                a.adm_no = ?
                    AND RIGHT(a.sem_code, 1) = ?
        GROUP BY a.ysession,a.examtype";
        $query = $this->db->query($sql,array($admn_no,$sem));
      //  echo $this->db->last_query();die();
            return $query->result();
        
    
    }
    function alumni_get_tab_status_tabulation_tbl($admn_no,$sem)
    {
        
       	if($sem==10){$sem='X';}
        $sql="SELECT 
            ysession, wsms, examtype
        FROM
            alumni_tabulation1 a
         WHERE
                a.adm_no = ?
                    AND RIGHT(a.sem_code, 1) = ?
        GROUP BY a.ysession,a.examtype";
        $query = $this->db->query($sql,array($admn_no,$sem));
      //  echo $this->db->last_query();die();
            return $query->result();
        
    
    }
    
    function get_result_by_type_semester($admn_no,$type,$sem){
         $sql="SELECT 
                *
            FROM
                tabulation1 a
            WHERE
                a.adm_no = ?
                    AND RIGHT(a.sem_code, 1) = ?
                    AND a.examtype=?
            ORDER BY a.examtype";
        $query = $this->db->query($sql,array($admn_no,$sem,$type));
            return $query->result();
    }
    
    function get_tab_status_reg_form_tbl($admn_no,$sem)
    {
        
     
        $sql="select P.* from(SELECT a.session_year, a.`session`
FROM reg_regular_form a
WHERE a.admn_no = ? AND a.semester = ? AND a.hod_status = '1' AND a.acad_status = '1'

union

SELECT b.session_year, b.`session`
FROM reg_summer_form b
inner join reg_summer_subject c on c.form_id=b.form_id
inner join course_structure d on c.sub_id=d.id
WHERE b.admn_no = ? AND substring(d.semester,1,1) = ? AND b.hod_status = '1' AND b.acad_status = '1'
group by b.session_year,b.admn_no)P order by P.session_year";
        $query = $this->db->query($sql,array($admn_no,$sem,$admn_no,$sem));
       // echo $this->db->last_query();die();
             return $query->result();
         
        
    
    }
    //======alumni==
    function alumni_get_tab_status_reg_form_tbl($admn_no,$sem)
    {
        
     
        $sql="select P.* from(SELECT a.session_year, a.`session`
FROM alumni_reg_regular_form a
WHERE a.admn_no = ? AND a.semester = ? AND a.hod_status = '1' AND a.acad_status = '1'

union

SELECT b.session_year, b.`session`
FROM alumni_reg_summer_form b
inner join alumni_reg_summer_subject c on c.form_id=b.form_id
inner join course_structure d on c.sub_id=d.id
WHERE b.admn_no = ? AND substring(d.semester,1,1) = ? AND b.hod_status = '1' AND b.acad_status = '1'
group by b.session_year,b.admn_no)P order by P.session_year";
        $query = $this->db->query($sql,array($admn_no,$sem,$admn_no,$sem));
       // echo $this->db->last_query();die();
             return $query->result();
         
        
    
    }
    
    function get_tab_status_exam_form_tbl($admn_no,$sem)
    {
  
        $sql="select P.* from(SELECT A.*,B.*
FROM (
SELECT a.session_year, a.`session`,a.`type`
FROM reg_other_form a
WHERE a.admn_no = '".$admn_no."' AND a.semester LIKE '%".$sem."%' AND a.hod_status = '1' AND a.acad_status = '1' UNION
SELECT b.session_year, b.`session`,b.`type`
FROM reg_exam_rc_form b
WHERE b.admn_no = '".$admn_no."' AND b.semester LIKE '%".$sem."%' AND b.hod_status = '1' AND b.acad_status = '1')A
INNER JOIN (
SELECT t.adm_no,d.sem, MAX(t.examtype) AS last_et
FROM tabulation1 t
INNER JOIN dip_m_semcode d ON d.semcode=t.sem_code
WHERE t.adm_no='".$admn_no."' AND d.sem=".$sem."
GROUP BY t.adm_no) B)P order by P.session_year";
        $query = $this->db->query($sql);
             
        //echo $this->db->last_query();die();
           return $query->result();
        
    
    }
    //===========alumni
    
    function alumni_get_tab_status_exam_form_tbl($admn_no,$sem)
    {
  
        $sql="select P.* from(SELECT A.*,B.*
FROM (
SELECT a.session_year, a.`session`,a.`type`
FROM alumni_reg_other_form a
WHERE a.admn_no = '".$admn_no."' AND a.semester LIKE '%".$sem."%' AND a.hod_status = '1' AND a.acad_status = '1' UNION
SELECT b.session_year, b.`session`,b.`type`
FROM alumni_reg_exam_rc_form b
WHERE b.admn_no = '".$admn_no."' AND b.semester LIKE '%".$sem."%' AND b.hod_status = '1' AND b.acad_status = '1')A
INNER JOIN (
SELECT t.adm_no,d.sem, MAX(t.examtype) AS last_et
FROM alumni_tabulation1 t
INNER JOIN dip_m_semcode d ON d.semcode=t.sem_code
WHERE t.adm_no='".$admn_no."' AND d.sem=".$sem."
GROUP BY t.adm_no) B)P order by P.session_year";
        $query = $this->db->query($sql);
             
        //echo $this->db->last_query();die();
           return $query->result();
        
    
    }
    
    //----------------------------------------------------------------------------------------------------
    
    function get_tab_status_exam_form_tbl_mis($admn_no,$sem)
    {
        
        $sql="select A.* from(SELECT a.session_year, a.`session`,a.`type`,a.admn_no,a.semester,a.`type` AS last_et
FROM reg_other_form a
WHERE a.admn_no = '".$admn_no."' AND a.semester LIKE '%".$sem."%' AND a.hod_status = '1' AND a.acad_status = '1' UNION
SELECT b.session_year, b.`session`,b.`type`,b.admn_no,b.semester,b.`type` AS last_et
FROM reg_exam_rc_form b
WHERE b.admn_no = '".$admn_no."' AND b.semester LIKE '%".$sem."%' AND b.hod_status = '1' AND b.acad_status = '1')A
order by A.session_year";
        $query = $this->db->query($sql,array($admn_no));
         //echo $this->db->last_query();die();     
       
           return $query->result();
        
    
    }
    //============================foil_data====================
    
    function get_tab_status_exam_form_tbl_mis_foil($admn_no,$sem)
    {
        
        $sql="select A.* from(SELECT a.session_year, a.`session`,CASE WHEN a.`type`='R' THEN 'O' WHEN a.`type`='S' THEN 'S' END as `type`,a.admn_no,a.semester,CASE WHEN a.`type`='R' THEN 'O' WHEN a.`type`='S' THEN 'S' END  AS last_et
FROM reg_other_form a
WHERE a.admn_no = '".$admn_no."' AND a.semester LIKE '%".$sem."%' AND a.hod_status = '1' AND a.acad_status = '1' UNION
SELECT b.session_year, b.`session`,CASE WHEN b.`type`='R' THEN 'O' WHEN b.`type`='S' THEN 'S' END as `type`,b.admn_no,b.semester,CASE WHEN b.`type`='R' THEN 'O' WHEN b.`type`='S' THEN 'S' END AS last_et
FROM reg_exam_rc_form b
WHERE b.admn_no = '".$admn_no."' AND b.semester LIKE '%".$sem."%' AND b.hod_status = '1' AND b.acad_status = '1')A
order by A.session_year";
        $query = $this->db->query($sql,array($admn_no));
         //echo $this->db->last_query();die();     
       
           return $query->result();
        
    
    }
    
    //===========Alumni============
    
    function alumni_get_tab_status_exam_form_tbl_mis_foil($admn_no,$sem)
    {
        
        $sql="select A.* from(SELECT a.session_year, a.`session`,CASE WHEN a.`type`='R' THEN 'O' WHEN a.`type`='S' THEN 'S' END as `type`,a.admn_no,a.semester,CASE WHEN a.`type`='R' THEN 'O' WHEN a.`type`='S' THEN 'S' END  AS last_et
FROM alumni_reg_other_form a
WHERE a.admn_no = '".$admn_no."' AND a.semester LIKE '%".$sem."%' AND a.hod_status = '1' AND a.acad_status = '1' UNION
SELECT b.session_year, b.`session`,CASE WHEN b.`type`='R' THEN 'O' WHEN b.`type`='S' THEN 'S' END as `type`,b.admn_no,b.semester,CASE WHEN b.`type`='R' THEN 'O' WHEN b.`type`='S' THEN 'S' END AS last_et
FROM alumni_reg_exam_rc_form b
WHERE b.admn_no = '".$admn_no."' AND b.semester LIKE '%".$sem."%' AND b.hod_status = '1' AND b.acad_status = '1')A
order by A.session_year";
        $query = $this->db->query($sql,array($admn_no));
         //echo $this->db->last_query();die();     
       
           return $query->result();
        
    
    }
    
    
    //=======================================================
    
    
    //----------------------------------------------------------------------------------------------------------
    
    
    
    function get_stu_minor_status_tab($adm_no,$sem)
    {
        //$sql = "select * from hm_form  where admn_no='".$adm_no."' and semester=".$sem." and minor='1' and minor_hod_status='Y'";
        $sql="select a.*,b.`session` as sess,b.session_year as sy from hm_form a 
inner join reg_regular_form b on a.admn_no=b.admn_no 
where a.admn_no='".$adm_no."' and a.semester=5 and a.minor='1' and a.minor_hod_status='Y'
and b.semester=".$sem;
        $query = $this->db->query($sql);
       if ($query->num_rows() > 0) {
            return $query->result();
        } else {
            return FALSE;
        }
    }
    
    //============almuni=========
    
        function alumni_get_stu_minor_status_tab($adm_no,$sem)
    {
        //$sql = "select * from hm_form  where admn_no='".$adm_no."' and semester=".$sem." and minor='1' and minor_hod_status='Y'";
        $sql="select a.*,b.`session` as sess,b.session_year as sy from alumni_hm_form a 
inner join alumni_reg_regular_form b on a.admn_no=b.admn_no 
where a.admn_no='".$adm_no."' and a.semester=5 and a.minor='1' and a.minor_hod_status='Y'
and b.semester=".$sem;
        $query = $this->db->query($sql);
       if ($query->num_rows() > 0) {
            return $query->result();
        } else {
            return FALSE;
        }
    }
    
    function get_aggregateID($id)
    {
        
        $sql="select substring(aggr_id,1,6) as aggid from course_structure where id=?";
        $query = $this->db->query($sql,array($id));
        //echo $this->db->last_query();die();
        return $query->row()->aggid;
    }
    
    
    
    
  
}

?>