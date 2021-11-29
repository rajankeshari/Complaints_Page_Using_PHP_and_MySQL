<?php

class cbcs_monitor_marks_submission_model extends CI_Model {

    function __construct() {
        parent::__construct();
    }


// grade sta faculty wise start

public function get_depts()
{
  $query = $this->db->get_where('cbcs_departments', array('type'=>'academic','status'=>'1'));
  return $query->result();
}

public function get_faculty_by_depid($dept_id)
{
  $query = $this->db->query("SELECT * FROM `user_details` AS ud INNER JOIN `emp_basic_details` AS ebd ON ud.id=ebd.emp_no INNER JOIN `users` AS us ON ud.id=us.id WHERE ebd.auth_id='ft' AND us.`status`='A' AND ud.dept_id='".$dept_id."' ORDER BY ud.first_name ASC");

  if($query->num_rows() > 0)
    return $query->result();
  else
    return false;
}

public function get_table_data($dept_id,$fac_id)
{
  // $sql="SELECT mm.id AS mid,sb.name,sb.subject_id,sm.`session`,sm.session_year,sm.course_id,sm.branch_id,(SELECT COUNT(id) FROM marks_subject_description WHERE marks_master_id=mid ORDER BY marks_master_id) AS tns,(SELECT COUNT(id) FROM marks_subject_description WHERE marks_master_id=mid AND grade='A+') AS aplus,(SELECT COUNT(*) FROM marks_subject_description WHERE marks_master_id=mid AND grade='A') AS aonly,(SELECT COUNT(*) FROM marks_subject_description WHERE marks_master_id=mid AND grade='B+') AS bplus,(SELECT COUNT(*) FROM marks_subject_description WHERE marks_master_id=mid AND grade='B') AS bonly,(SELECT COUNT(*) FROM marks_subject_description WHERE marks_master_id=mid AND grade='C+') AS cplus,(SELECT COUNT(*) FROM marks_subject_description WHERE marks_master_id=mid AND grade='C') AS conly,(SELECT COUNT(*) FROM marks_subject_description WHERE marks_master_id=mid AND grade='D') AS donly,(SELECT COUNT(*) FROM marks_subject_description WHERE marks_master_id=mid AND grade='F') AS fonly FROM marks_master AS mm INNER JOIN subjects AS sb ON mm.subject_id=sb.id INNER JOIN subject_mapping AS sm ON mm.sub_map_id=sm.map_id  WHERE mm.emp_id='".$fac_id."' AND sm.dept_id='".$dept_id."' AND mm.`status`='Y'";
  // $sql="SELECT mm.id AS mid,sb.name,sb.subject_id,sm.`session`,sm.session_year,sm.course_id,sm.branch_id,(SELECT COUNT(id) FROM marks_subject_description WHERE marks_master_id=mid ORDER BY marks_master_id) AS tns FROM marks_master AS mm INNER JOIN subjects AS sb ON mm.subject_id=sb.id INNER JOIN subject_mapping AS sm ON mm.sub_map_id=sm.map_id  WHERE mm.emp_id='".$fac_id."' AND sm.dept_id='".$dept_id."' AND mm.`status`='Y'";

  $sql="(select x.*,count(z.grade) as tns, count(if(z.grade='A+',1,NULL)) 'aplus',count(if(z.grade='A',1,NULL)) 'aonly',
      count(if(z.grade='B+',1,NULL)) 'bplus',count(if(z.grade='B',1,NULL)) 'bonly',count(if(z.grade='C+',1,NULL)) 'cplus',
      count(if(z.grade='C',1,NULL)) 'conly',count(if(z.grade='D',1,NULL)) 'donly',count(if(z.grade='F',1,NULL)) 'fonly'
      from (SELECT mm.id AS id,sb.name as sub_name,sb.subject_id,sm.`session`,sm.session_year,sm.course_id,sm.branch_id
      FROM marks_master AS mm	INNER JOIN subjects AS sb ON mm.subject_id=sb.id INNER JOIN subject_mapping AS sm ON mm.sub_map_id=sm.map_id
      WHERE mm.emp_id='".$fac_id."' AND sm.dept_id='".$dept_id."' AND mm.`status`='Y') x inner join marks_subject_description z on
       x.id=z.marks_master_id group by x.id)

      union

      (select x.*,count(cmsd.grade) as tns,count(if(cmsd.grade='A+',1,NULL)) 'aplus',count(if(cmsd.grade='A',1,NULL)) 'aonly',
      count(if(cmsd.grade='B+',1,NULL)) 'bplus',count(if(cmsd.grade='B',1,NULL)) 'bonly',count(if(cmsd.grade='C+',1,NULL)) 'cplus',
      count(if(cmsd.grade='C',1,NULL)) 'conly',count(if(cmsd.grade='D',1,NULL)) 'donly',count(if(cmsd.grade='F',1,NULL)) 'fonly' from
      (select distinct(cmm.id) as id,cmu.subject_name as sub_name,cmu.subject_code as subject_id,cmu.`session`,cmu.session_year,cmu.course_id,
      cmu.branch_id from cbcs_marks_upload_description as cmud inner join cbcs_marks_upload as cmu on cmud.marks_id=cmu.id inner join
      cbcs_marks_master as cmm on cmu.sub_offered_id=cmm.sub_map_id where cmud.uploaded_by='".$fac_id."') as x inner join cbcs_marks_subject_description
      as cmsd on x.id=cmsd.marks_master_id group by x.id)";

  // $sql="select x.*,z.grade,count(z.grade) as tns, count(if(z.grade='A+',1,NULL)) 'aplus',count(if(z.grade='A',1,NULL)) 'aonly',count(if(z.grade='B+',1,NULL)) 'bplus',count(if(z.grade='B',1,NULL)) 'bonly',count(if(z.grade='C+',1,NULL)) 'cplus',count(if(z.grade='C',1,NULL)) 'conly',count(if(z.grade='D',1,NULL)) 'donly',count(if(z.grade='F',1,NULL)) 'fonly' from (SELECT mm.id AS MID,sb.name,sb.subject_id,sm.`session`,sm.session_year,sm.course_id,sm.branch_id
  // 	FROM marks_master AS mm	INNER JOIN subjects AS sb ON mm.subject_id=sb.id INNER JOIN subject_mapping AS sm ON mm.sub_map_id=sm.map_id WHERE mm.emp_id='".$fac_id."' AND sm.dept_id='".$dept_id."' AND mm.`status`='Y') x inner join marks_subject_description z on x.MID=z.marks_master_id
  // 	group by x.mid";
  $query=$this->db->query($sql);
  $aplus=array();

  if($query->num_rows()>0){
    return $query->result();

  }
  else{
    return false;
  }
}

public function get_basic_detail($dept_id,$fac_id){
  $id=$dept_id;
  $fac_id;
  $basic=array();

  $query = $this->db->get_where('cbcs_departments', array('type'=>'academic','status'=>'1','id'=>$dept_id));
  $val=$query->row();

  $basic['fac_id']=$fac_id;
  $basic['dep_name']=$val->name;

  $query1 = $this->db->query("SELECT * FROM `user_details` AS ud INNER JOIN `emp_basic_details` AS
                ebd ON ud.id=ebd.emp_no INNER JOIN `users` AS us ON ud.id=us.id
                WHERE ebd.auth_id='ft' AND us.`status`='A' AND ud.dept_id='".$dept_id."' and ebd.emp_no='".$fac_id."'");
  $val2=$query1->row();
  $basic['fac_name']=$val2->salutation.' '.$val2->first_name.' '.$val2->middle_name.' '.$val2->last_name;

  return $basic;

}



// grade sta faculty wise end

    function get_data_template_wise1($syear,$sess)
    {



        $sql=" SELECT t1.lecture,t1.sub_code,
t1.sub_type,t1.subject_id,t1.stu_cnt,t1.emp_name,
t1.required_1,
t1.ctr_A_A_plus_temp_1,
t1.required_2,t1.ctr_B_B_plus_temp_1,t1.required_3,t1.ctr_C_C_plus_temp_1,
t1.required_4,t1.ctr_D_F_plus_temp_1
 FROM(
select  n.lecture,n.sub_code,n.sub_type, v.* from
(select y.subject_id, sum(y.tot) as stu_cnt,  (select     concat(concat_ws(' ',u.salutation,u.first_name,u.middle_name,u.last_name),'[',y.emp_id,']')   from user_details u where u.id=y.emp_id) as emp_name,
       'A+,A : TOP 15-25 %'   as  required_1,
      if( sum(y.tot)>50 , sum( ( case  when y.grade like 'A%' then  y.tot  end) )*100/sum(y.tot),'N/A' ) as  ctr_A_A_plus_temp_1,
      'B+,B : NEXT 35-45 %'   as  required_2,
        if(   sum(y.tot)>50 ,  sum( ( case  when y.grade like 'B%' then  y.tot  end)  )*100/sum(y.tot),'N/A' ) as  ctr_B_B_plus_temp_1,
        'C+,C : NEXT 25-35 %'   as  required_3,
              if( sum(y.tot)>50 , sum( ( case  when y.grade like 'C%' then  y.tot  end)  )*100/sum(y.tot),'N/A' ) as  ctr_C_C_plus_temp_1,
              'D,F : NEXT 5-15 %'   as  required_4,
                     if( sum(y.tot)>50 ,  sum( ( case  when (y.grade like 'D' or y.grade like 'F')  then  y.tot  end ))*100/sum(y.tot),'N/A' )  as  ctr_D_F_plus_temp_1,

          'A+,A,B+ : TOP 40-50 %'   as  required_less_1,
                  if( sum(y.tot)<50 , sum( ( case  when (y.grade like 'A%' or y.grade like 'B+')  then  y.tot  end)  )*100/sum(y.tot) ,'N/A' )as  ctr_A_A_plus_B_plus_temp_2,
                'B,C+,C : NEXT 40-50 %'   as  required_less_2,
if( sum(y.tot)<50 ,  sum( ( case  when (y.grade like 'C%' or y.grade like 'B')  then  y.tot  end)  )*100/sum(y.tot) ,'N/A' )as  ctr_C_C_plus_B_temp_2,
               'D,F : NEXT 5-15 %'   as  required_less_3,
 if( sum(y.tot)<50 ,  sum( ( case  when (y.grade like 'D' or y.grade like 'F')  then  y.tot  end ))*100/sum(y.tot),'N/A' )  as  ctr_D_F_plus2_temp_2

 from
( select x.*,  count(x.admn_no)  as tot
 from
(select a.subject_id,b.grade,b.admn_no,a.emp_id
from cbcs_marks_master a
inner join cbcs_marks_subject_description b on a.id=b.marks_master_id
where b.grade IS NOT NULL  AND a.session_year=?  AND a.`session`=? and b.grade<>'I'
order by a.subject_id)x group by x.subject_id,x.grade) y
group by y.subject_id

     ) v

right join

( select  a.sub_code,a.sub_type,a.lecture   from  cbcs_subject_offered a  where  a.sub_type not in('Practical','Non-Contact','Audit')
  union
  select  a.sub_code,a.sub_type,a.lecture from  old_subject_offered a   where a.sub_type not in('Practical','Non-Contact','Audit')
) n

on n.sub_code=v.subject_id)t1 WHERE t1.stu_cnt>50 AND t1.lecture>=1
";


        $query = $this->db->query($sql,array($syear,$sess));

       //echo $this->db->last_query(); die();
        if ($this->db->affected_rows() >= 0) {
            return $query->result();
        } else {
            return false;
        }
    }
	//Template 2

	function get_data_template_wise2($syear,$sess)
    {



        $sql=" SELECT t1.lecture,t1.sub_code,
t1.sub_type,t1.subject_id,t1.stu_cnt,t1.emp_name,
t1.required_less_1,
t1.ctr_A_A_plus_B_plus_temp_2,
t1.required_less_2,
t1.ctr_C_C_plus_B_temp_2,
t1.required_less_3,
t1.ctr_D_F_plus2_temp_2

 FROM(
select  n.lecture,n.sub_code,n.sub_type, v.* from
(select y.subject_id, sum(y.tot) as stu_cnt,  (select     concat(concat_ws(' ',u.salutation,u.first_name,u.middle_name,u.last_name),'[',y.emp_id,']')   from user_details u where u.id=y.emp_id) as emp_name,
       'A+,A : TOP 15-25 %'   as  required_1,
      if( sum(y.tot)>50 , sum( ( case  when y.grade like 'A%' then  y.tot  end) )*100/sum(y.tot),'N/A' ) as  ctr_A_A_plus_temp_1,
      'B+,B : NEXT 35-45 %'   as  required_2,
        if(   sum(y.tot)>50 ,  sum( ( case  when y.grade like 'B%' then  y.tot  end)  )*100/sum(y.tot),'N/A' ) as  ctr_B_B_plus_temp_1,
        'C+,C : NEXT 25-35 %'   as  required_3,
              if( sum(y.tot)>50 , sum( ( case  when y.grade like 'C%' then  y.tot  end)  )*100/sum(y.tot),'N/A' ) as  ctr_C_C_plus_temp_1,
              'D,F : NEXT 5-15 %'   as  required_4,
                     if( sum(y.tot)>50 ,  sum( ( case  when (y.grade like 'D' or y.grade like 'F')  then  y.tot  end ))*100/sum(y.tot),'N/A' )  as  ctr_D_F_plus_temp_1,

          'A+,A,B+ : TOP 40-50 %'   as  required_less_1,
                  if( sum(y.tot)<50 , sum( ( case  when (y.grade like 'A%' or y.grade like 'B+')  then  y.tot  end)  )*100/sum(y.tot) ,'N/A' )as  ctr_A_A_plus_B_plus_temp_2,
                'B,C+,C : NEXT 40-50 %'   as  required_less_2,
if( sum(y.tot)<50 ,  sum( ( case  when (y.grade like 'C%' or y.grade like 'B')  then  y.tot  end)  )*100/sum(y.tot) ,'N/A' )as  ctr_C_C_plus_B_temp_2,
               'D,F : NEXT 5-15 %'   as  required_less_3,
 if( sum(y.tot)<50 ,  sum( ( case  when (y.grade like 'D' or y.grade like 'F')  then  y.tot  end ))*100/sum(y.tot),'N/A' )  as  ctr_D_F_plus2_temp_2

 from
( select x.*,  count(x.admn_no)  as tot
 from
(select a.subject_id,b.grade,b.admn_no,a.emp_id
from cbcs_marks_master a
inner join cbcs_marks_subject_description b on a.id=b.marks_master_id
where b.grade IS NOT NULL  AND a.session_year=?  AND a.`session`=? and b.grade<>'I'
order by a.subject_id)x group by x.subject_id,x.grade) y
group by y.subject_id

     ) v

right join

( select  a.sub_code,a.sub_type,a.lecture   from  cbcs_subject_offered a  where  a.sub_type not in('Practical','Non-Contact','Audit')
  union
  select  a.sub_code,a.sub_type,a.lecture from  old_subject_offered a   where a.sub_type not in('Practical','Non-Contact','Audit')
) n

on n.sub_code=v.subject_id)t1 WHERE t1.stu_cnt BETWEEN 16 AND 50 AND t1.lecture>=1
";


        $query = $this->db->query($sql,array($syear,$sess));

       //echo $this->db->last_query(); die();
        if ($this->db->affected_rows() >= 0) {
            return $query->result();
        } else {
            return false;
        }
    }
	//Template 3

	function get_data_template_wise3($syear,$sess)
    {



        $sql=" SELECT tt.* FROM(
SELECT t1.*,(
SELECT CONCAT(CONCAT_WS(' ',u.salutation,u.first_name,u.middle_name,u.last_name),'[',t1.emp_id,']')
FROM user_details u
WHERE u.id=t1.emp_id) AS emp_name
FROM (
SELECT p.*, /*o.sub_type AS old_sub_type, c.sub_type AS cbcs_sub_type,*/
(CASE WHEN CONCAT('o',o.id)=p.sub_map_id THEN o.sub_type ELSE c.sub_type END) AS sub_code,
(CASE WHEN CONCAT('o',o.id)=p.sub_map_id THEN o.lecture ELSE c.lecture END) AS lecture
FROM (
SELECT a.emp_id,a.sub_map_id,a.subject_id, COUNT(b.id) AS total_stu, SUM(IF(b.grade= NULL OR b.grade='',1,0)) AS gradingStatus, SUM(IF(b.grade='A+',1,0))*100/ COUNT(b.id) AS 'AP', SUM(IF(b.grade='A',1,0))*100/ COUNT(b.id) AS 'A', SUM(IF(b.grade='B+',1,0))*100/ COUNT(b.id) AS 'BP', SUM(IF(b.grade='B',1,0))*100/ COUNT(b.id) AS 'B', SUM(IF(b.grade='C+',1,0))*100/ COUNT(b.id) AS 'CP', SUM(IF(b.grade='C',1,0))*100/ COUNT(b.id) AS 'C', SUM(IF(b.grade='D',1,0))*100/ COUNT(b.id) AS 'D', SUM(IF(b.grade='F',1,0))*100/ COUNT(b.id) AS 'F', SUM(IF(b.grade='I',1,0))*100/ COUNT(b.id) AS 'I'
FROM cbcs_marks_master a
INNER JOIN cbcs_marks_subject_description b ON b.marks_master_id=a.id
WHERE a.session_year=? AND a.`session`=? and b.grade<>'I'
GROUP BY a.sub_map_id)p
LEFT JOIN old_subject_offered o ON CONCAT('o',o.id)=p.sub_map_id
LEFT JOIN cbcs_subject_offered c ON CONCAT('c',c.id)=p.sub_map_id)t1
WHERE
(
(t1.sub_code='Theory'  || t1.sub_code='Sessional'  ||  (t1.sub_code='Modular'  &&    t1.lecture>=1    ) )
 AND (t1.total_stu>1 && t1.total_stu<16))

		OR(!(t1.sub_code='Theory'  || t1.sub_code='Sessional'||   (t1.sub_code='Modular'  &&    t1.lecture>=1    )  )
		  )

)tt ";


        $query = $this->db->query($sql,array($syear,$sess));

       //echo $this->db->last_query(); die();
        if ($this->db->affected_rows() >= 0) {
            return $query->result();
        } else {
            return false;
        }
    }


}

?>
