<?PHP

if (!defined('BASEPATH'))
    exit('No direct script access allowed');

class Transcript_bunch_model extends CI_Model {

    public function __construct() {
        parent::__construct();
    }

        function get_all_student($syear,$stu_sess,$did, $cid, $bid, $sem,$etype)
        {
           // echo $syear; echo $etype; echo $did;echo $cid;echo $bid;echo $sem;echo $stu_sess;die();
            
            if($etype=="regular"){ $et='R';}
            if($etype=="other"){ $et='O';}
            if($etype=="special"){ $et='S';}
            if($etype=="earlyspecial"){ $et='E';}
            if($etype=="jrf"){ $et='J';}
if($cid<>'minor'){            
    $sql = "select a.admn_no,a.course,a.branch,a.semester,CONCAT_WS(' ',b.first_name,b.middle_name,b.last_name) AS stu_name ,
e.name AS dname,if(f.admn_no is not null,concat_ws(' ',c.name,'[Honours]'),c.name)as cname,d.name AS bname
from final_semwise_marks_foil a 
inner join user_details b on b.id=a.admn_no
INNER JOIN cs_courses c ON c.id=a.course
INNER JOIN cs_branches d ON d.id=a.branch
INNER JOIN departments e ON e.id=b.dept_id
left join hm_form f on (f.admn_no=a.admn_no and f.honours='1' and f.honour_hod_status='Y')
where  a.session_yr=?
and a.`session`=? and b.dept_id=? and a.course=? and a.branch=? and a.semester=? and a.`type`=? 
ORDER BY a.admn_no";
    $query = $this->db->query($sql, array($syear,$stu_sess,$did, $cid, $bid, $sem,$et));
}
else
{
    $sql = "SELECT a.admn_no,a.course,a.branch,a.semester, CONCAT_WS(' ',b.first_name,b.middle_name,b.last_name) AS stu_name, e.name AS dname, c.name AS cname,d.name AS bname
FROM final_semwise_marks_foil a
INNER JOIN user_details b ON b.id=a.admn_no
INNER JOIN cs_courses c ON c.id=a.course
INNER JOIN cs_branches d ON d.id=a.branch
INNER JOIN departments e ON e.id=b.dept_id
LEFT JOIN hm_form f ON (f.admn_no=a.admn_no AND f.honours='1' AND f.honour_hod_status='Y')
WHERE a.session_yr=? AND a.`session`=? AND a.dept=? AND a.course=?
AND a.branch=? AND a.semester=? AND a.`type`='R' and a.status='PASS'
ORDER BY a.admn_no";
    $query = $this->db->query($sql, array($syear,$stu_sess,$did, $cid, $bid, $sem,$et));
}
            



        

        //echo $this->db->last_query(); die();
        if ($this->db->affected_rows() >= 0) {
            return $query->result();
        } else {
            return false;
        }
    }
    
    function get_all_student_individual($syear,$etype,$did, $cid, $bid, $sem,$admn_no=null)
        {
			if($cid=='comm'){
				if($sem%2==0){ $stu_sess='Winter';}else{$stu_sess='Monsoon';}	
				$sql = "SELECT a.admn_no,a.course,a.branch,a.semester, CONCAT_WS(' ',b.first_name,b.middle_name,b.last_name) AS stu_name, e.name AS dname,/*c.name as cname*/ IF(f.admn_no IS NOT NULL, CONCAT_WS(' ',c.name,'[Honours]'),c.name) AS cname,d.name AS bname
FROM final_semwise_marks_foil a
INNER JOIN user_details b ON b.id=a.admn_no
left JOIN cs_courses c ON c.id=a.course
left JOIN cs_branches d ON d.id='comm'
left JOIN departments e ON e.id=b.dept_id
LEFT JOIN hm_form f ON (f.admn_no=a.admn_no AND f.honours='1' AND f.honour_hod_status='Y')
WHERE a.session_yr='".$syear."' AND a.`session`='".$stu_sess."' AND a.dept='comm' AND a.course='comm'  AND a.semester='".$sem."' AND a.admn_no='".$admn_no."'
GROUP BY admn_no
ORDER BY a.admn_no"; 
    
		$query = $this->db->query($sql);
        //echo $this->db->last_query(); die();
        if ($this->db->affected_rows() >= 0) {
            return $query->result();
        } else {
            return false;
        }
			}else{
				        if($sem%2==0){ $stu_sess='Winter';}else{$stu_sess='Monsoon';}		
         $admn_no=preg_replace('/\s+/', '',$admn_no);
		 
         if( $admn_no !=null){
             if(substr_count($admn_no, ',')>0){                               
                $admn_no="'". implode("','", explode(',', $admn_no)) ."'";
                $replacer1="  and a.admn_no in(".$admn_no.") ";                                         
                $secure_array=array($syear,$did, $cid, $bid, $sem);    
             }else{  
                $replacer1=" and a.admn_no=? ";
                 $secure_array=array($syear,$did, $cid, $bid, $sem,$admn_no);    
             } 
           }
           else{
            $replacer1="";
           $secure_array=array($syear,$did, $cid, $bid, $sem); 
        }
        $sql = "select a.admn_no,a.course,a.branch,a.semester,CONCAT_WS(' ',b.first_name,b.middle_name,b.last_name) AS stu_name ,
e.name AS dname,/*c.name as cname*/if(f.admn_no is not null,concat_ws(' ',c.name,'[Honours]'),c.name)as cname,d.name AS bname
from final_semwise_marks_foil a 
inner join user_details b on b.id=a.admn_no
INNER JOIN cs_courses c ON c.id=a.course
INNER JOIN cs_branches d ON d.id=a.branch
INNER JOIN departments e ON e.id=b.dept_id
left join hm_form f on (f.admn_no=a.admn_no and f.honours='1' and f.honour_hod_status='Y')
where  a.session_yr=?
and a.`session`='".$stu_sess."' and a.dept=? and a.course=? and a.branch=? and a.semester=? 
 ".$replacer1."  group by admn_no ORDER BY a.admn_no"; 
    
 $query = $this->db->query($sql,$secure_array);
        
        if ($this->db->affected_rows() >= 0) {
            return $query->result();
        } else {
            return false;
        }
			}
			

    }
    function alumni_get_all_student_individual($syear,$etype,$did, $cid, $bid, $sem,$admn_no=null)
        {
        if($sem%2==0){ $stu_sess='Winter';}else{$stu_sess='Monsoon';}		
         $admn_no=preg_replace('/\s+/', '',$admn_no);
		 
         if( $admn_no !=null){
             if(substr_count($admn_no, ',')>0){                               
                $admn_no="'". implode("','", explode(',', $admn_no)) ."'";
                $replacer1="  and a.admn_no in(".$admn_no.") ";                                         
                $secure_array=array($syear,$did, $cid, $bid, $sem);    
             }else{  
                $replacer1=" and a.admn_no=? ";
                 $secure_array=array($syear,$did, $cid, $bid, $sem,$admn_no);    
             } 
           }
           else{
            $replacer1="";
           $secure_array=array($syear,$did, $cid, $bid, $sem); 
        }
        $sql = "select a.admn_no,a.course,a.branch,a.semester,CONCAT_WS(' ',b.first_name,b.middle_name,b.last_name) AS stu_name ,
e.name AS dname,/*c.name as cname*/if(f.admn_no is not null,concat_ws(' ',c.name,'[Honours]'),c.name)as cname,d.name AS bname
from alumni_final_semwise_marks_foil a 
inner join alumni_details b on b.id=a.admn_no
INNER JOIN cs_courses c ON c.id=a.course
INNER JOIN cs_branches d ON d.id=a.branch
INNER JOIN departments e ON e.id=b.dept_id
left join hm_form f on (f.admn_no=a.admn_no and f.honours='1' and f.honour_hod_status='Y')
where  a.session_yr=?
and a.`session`='".$stu_sess."' and a.dept=? and a.course=? and a.branch=? and a.semester=? 
 ".$replacer1."  group by admn_no ORDER BY a.admn_no"; 
    
 $query = $this->db->query($sql,$secure_array);
       // echo $this->db->last_query(); die();
        if ($this->db->affected_rows() >= 0) {
            return $query->result();
        } else {
            return false;
        }
    }

    
    function ger_cgpa($adno,$sem,$cid,$type='R')
        {
          $sql = "select ctotcrhr,ctotcrpts,gpa,cgpa FROM final_semwise_marks_foil
WHERE id=(SELECT id FROM final_semwise_marks_foil WHERE admn_no=? and semester=? and course=? and  type=? order by id desc limit 1)";
         
        $query = $this->db->query($sql, array($adno,$sem,$cid, $type));

        //echo $this->db->last_query(); die();
        if ($this->db->affected_rows() >= 0) {
            return $query->result();
        } else {
            return false;
        }
    }
    
    
    //==================================MINOR Certificate=====================================
    
    function get_all_student_mc($syear, $sess='Winter', $did, $course_id, $bid, $sem, $etype)
        {
        if($etype=="regular"){ $et='R';}
            if($etype=="other"){ $et='O';}
            if($etype=="special"){ $et='S';}
            if($etype=="earlyspecial"){ $et='E';}
            if($etype=="jrf"){ $et='J';}
        
        
          $sql = "select a.* from final_semwise_marks_foil a
where a.session_yr=? and a.`session`=?  
and a.dept=? and a.course=? and a.branch=?
and a.semester=? and a.`type`=? and a.`status`='PASS'
order by a.admn_no";
         
        $query = $this->db->query($sql, array($syear, $sess, $did, $course_id, $bid, $sem, $et));

        //echo $this->db->last_query(); die();
        if ($this->db->affected_rows() >= 0) {
            return $query->result();
        } else {
            return false;
        }
    }
    function get_all_student_mc_ind($syear,  $did, $course_id, $bid, $sem, $etype,$admn)
        {
        if($etype=="regular"){ $et='R';}
            if($etype=="other"){ $et='O';}
            if($etype=="special"){ $et='S';}
            if($etype=="earlyspecial"){ $et='E';}
            if($etype=="jrf"){ $et='J';}
        
        
          $sql = "select a.* from final_semwise_marks_foil a
where a.session_yr=? 
and a.dept=? and a.course=? and a.branch=?
and a.semester=? and a.`type`=? and a.`status`='PASS' and a.admn_no=?
order by a.admn_no";
         
        $query = $this->db->query($sql, array($syear, $did, $course_id, $bid, $sem, $et,$admn));

        //echo $this->db->last_query(); die();
        if ($this->db->affected_rows() >= 0) {
            return $query->result();
        } else {
            return false;
        }
    }
    function get_personal_mc($id)
        {
        
        
          $sql = "select upper(concat_ws(' ',a.salutation,a.first_name,a.middle_name,a.last_name))as stu_name, 
upper(a.id) as admn_no,
upper(d.name) as cname,
upper(b.course_id) as course_id,
upper(b.branch_id) as branch_id,
upper(a.dept_id)as dept_id,
upper(c.name) as dname,
upper(e.name) as bname
 from user_details a
inner join stu_academic b on a.id=b.admn_no
inner join departments c on c.id=a.dept_id
inner join cs_courses d on d.id=b.course_id
inner join cs_branches e on e.id=b.branch_id
where a.id=?";
         
        $query = $this->db->query($sql, array($id));

        //echo $this->db->last_query(); die();
        if ($this->db->affected_rows() >= 0) {
            return $query->result();
        } else {
            return false;
        }
    }
    function get_details_mc($id)
        {
        
        
         /* $sql = "select b.*,c.id,c.semester,d.subject_id,d.name,e.name as dname,f.grade,g.`status` from hm_form a 
inner join hm_minor_details b on a.form_id=b.form_id
inner join course_structure c on c.aggr_id=b.minor_agg_id
inner join subjects d on d.id=c.id
inner join departments e on e.id=b.dept_id
inner join final_semwise_marks_foil_desc f on (f.admn_no=a.admn_no and f.mis_sub_id=d.id)
inner join final_semwise_marks_foil g on g.id=f.foil_id
where  a.admn_no=? and a.minor='1' and a.minor_hod_status='Y' and d.`type`='Theory'
and b.offered='1' and g.course='MINOR'
order by c.semester";*/
        $mid=$this->get_minor_aggr_id($id);
        $sql="select a.*,b.subject_id,b.session_year,b.`session`,b.`status`,c.dept_id,c.course_id,c.branch_id,c.semester,
c.aggr_id,d.subject_id,d.name,e.dept_id,f.name as dname,d.credit_hours,concat_ws('-',d.lecture,d.tutorial,d.practical) as ltp,g.name as bname from marks_subject_description a
inner join marks_master b on a.marks_master_id=b.id
inner join subject_mapping c on c.map_id=b.sub_map_id
inner join subjects d on d.id=b.subject_id
inner join dept_course e on e.aggr_id=?
inner join cs_branches g on g.id=c.branch_id
inner join departments f on f.id=e.dept_id
and c.aggr_id=? and a.admn_no=?
/*and d.`type`='Theory'*/";
         
        $query = $this->db->query($sql, array($mid->minor_agg_id,$mid->minor_agg_id,$id));

        //echo $this->db->last_query(); die();
        if ($this->db->affected_rows() >= 0) {
            return $query->result();
        } else {
            return false;
        }
    }
    function get_minor_aggr_id($id){
        $sql="select b.* from hm_form a 
inner join hm_minor_details b on b.form_id=a.form_id
where a.admn_no=? and a.minor='1'
and a.minor_hod_status='Y' and b.offered='1'";
        
        $query = $this->db->query($sql, array($id));

        //echo $this->db->last_query(); die();
        if ($this->db->affected_rows() >= 0) {
            return $query->row();
        } else {
            return false;
        }
        
        
        
    }
    function get_honours_status($id,$sy,$sess)
        {
          $sql = "select hstatus from final_semwise_marks_foil where admn_no=?
and session_yr=? and session=? and course<>'MINOR'";
         
        $query = $this->db->query($sql, array($id,$sy,$sess));

        //echo $this->db->last_query(); die();
        if ($this->db->affected_rows() >= 0) {
            return $query->row()->hstatus;
        } else {
            return false;
        }
    }
    function get_stu_session_year_for_minor($id,$sem){
        $mid=$this->get_minor_aggr_id($id);
        $sql = "select b.session_year,b.`session`from marks_subject_description a
inner join marks_master b on a.marks_master_id=b.id
inner join subject_mapping c on c.map_id=b.sub_map_id
inner join subjects d on d.id=b.subject_id
inner join dept_course e on e.aggr_id=?
inner join departments f on f.id=e.dept_id
and c.aggr_id=? and a.admn_no=?
and c.semester=? limit 1
";
         
        $query = $this->db->query($sql, array($mid->minor_agg_id,$mid->minor_agg_id,$id,$sem));

        //echo $this->db->last_query(); die();
        if ($this->db->affected_rows() >= 0) {
            return $query->row();
        } else {
            return false;
        }
    }
    function get_minor_sub_marks($id, $sem, $sy, $sess){
        
        $mid=$this->get_minor_aggr_id($id);
        $sql = "select a.*,b.subject_id,b.session_year,b.`session`,b.`status`,c.dept_id,c.course_id,c.branch_id,c.semester,
c.aggr_id,d.subject_id,d.name,e.dept_id,f.name as dname,d.credit_hours as cr_hr,SUBSTRING_INDEX('minor_fm_2013_2014','_',1) AS subtype from marks_subject_description a
inner join marks_master b on a.marks_master_id=b.id
inner join subject_mapping c on c.map_id=b.sub_map_id
inner join subjects d on d.id=b.subject_id
inner join dept_course e on e.aggr_id=?
inner join departments f on f.id=e.dept_id
and c.aggr_id=? and a.admn_no=?
and c.semester=? and b.session_year=? and b.`session`=? and b.status='Y'";
         
        $query = $this->db->query($sql, array($mid->minor_agg_id,$mid->minor_agg_id,$id,$sem,$sy, $sess));

        //echo $this->db->last_query(); die();
        if ($this->db->affected_rows() >= 0) {
            return $query->result();
        } else {
            return false;
        }
        
    }
    

}

?>