<?php

class Alternate_subject_model extends CI_Model {

    function __construct() {
        parent::__construct();
    }
function get_subject_details_by_id_cbcs($id,$did,$bid,$cid,$sem){
		
		$tmp=explode(',',$id);
		//echo '<pre>'; print_r($tmp);echo '</pre>';

        $sql="SELECT * FROM cbcs_subject_offered WHERE sub_code=? AND dept_id=? AND course_id=? AND branch_id=? and semester=?";
        	  
        $query = $this->db->query($sql,array($tmp[1],$did,$bid,$cid,$sem));
//echo $sql;

        if ($this->db->affected_rows() >= 0) {
            return $query->row();
        } else {
            return false;
        }

    }

    function get_fail_subjects($admn_no)
    {


        $sql="
select v.*, s.id,s.subject_id,s.elective, if((case when cso.sub_code IS NULL then oso.sub_code ELSE cso.sub_code end) is null , s.subject_id , (case when cso.sub_code IS NULL then oso.sub_code ELSE cso.sub_code end) ) AS sub_code , if((case when cso.sub_code IS NULL then oso.sub_name ELSE cso.sub_name end) is null , s.name , (case when cso.sub_code IS NULL then oso.sub_name ELSE cso.sub_name end) ) AS name , if((case when cso.sub_code IS NULL then oso.lecture ELSE cso.lecture end) is null , s.lecture , (case when cso.sub_code IS NULL then oso.lecture ELSE cso.lecture end) ) AS lecture , if((case when cso.sub_code IS NULL then oso.practical ELSE cso.practical end) is null , s.practical , (case when cso.sub_code IS NULL then oso.practical ELSE cso.practical end) ) AS practical, if((case when cso.sub_code IS NULL then oso.tutorial ELSE cso.tutorial end) is null , s.tutorial , (case when cso.sub_code IS NULL then oso.tutorial ELSE cso.tutorial end) ) AS tutorial, if((case when cso.sub_code IS NULL then oso.sub_type ELSE cso.sub_type end) is null , s.`type` , (case when cso.sub_code IS NULL then oso.sub_type ELSE cso.sub_type end) ) AS   'type', if((case when cso.sub_code IS NULL then oso.credit_hours ELSE cso.credit_hours end) is null , s.credit_hours , (case when cso.sub_code IS NULL then oso.credit_hours ELSE cso.credit_hours end) ) AS credit_hours, if((case when cso.sub_code IS NULL then oso.contact_hours ELSE cso.contact_hours end) is null , s.contact_hours , (case when cso.sub_code IS NULL then oso.contact_hours ELSE cso.contact_hours end) ) AS contact_hours from (select y.session_yr,y.session,y.dept,y.course,y.branch,y.semester , fd.mis_sub_id , fd.sub_code, fd.grade ,y.admn_no from (select x.* from (select a.session_yr,a.session,a.admn_no,a.dept,a.course,a.branch,a.semester,a.id,a.`status` from final_semwise_marks_foil_freezed as a WHERE a.admn_no=? order by a.semester,a.admn_no,a.actual_published_on desc limit 10000)x group by x.semester)y join final_semwise_marks_foil_desc_freezed fd on fd.foil_id=y.id and fd.admn_no=y.admn_no and fd.grade='F' GROUP BY fd.sub_code order by y.session_yr,y.dept,y.course,y.branch,y.semester,fd.sub_code)v 

LEFT JOIN cbcs_subject_offered cso ON cso.sub_code=v.sub_code AND v.course=cso.course_id AND v.branch=cso.branch_id and  v.session_yr=cso.session_year and v.session=cso.session
LEFT JOIN old_subject_offered oso ON oso.sub_code=v.sub_code AND v.course=oso.course_id AND v.branch=oso.branch_id and  v.session_yr=oso.session_year and v.session=oso.session

  /* LEFT JOIN subjects s on (case when v.mis_sub_id is not null then s.id else s.subject_id end )=(case when v.mis_sub_id is not null then v.mis_sub_id else v.sub_code end)*/
  /*LEFT JOIN subjects s ON  s.id= v.mis_sub_id */
  
  LEFT JOIN subjects s ON
(CASE WHEN v.mis_sub_id IS NOT NULL    THEN s.id  when  v.mis_sub_id is null  and (cso.sub_code is  null   and  oso.sub_code is   null) then s.subject_id END)= 
(CASE WHEN v.mis_sub_id IS NOT NULL  THEN  v.mis_sub_id  when  v.mis_sub_id is null  and (cso.sub_code is  null   and  oso.sub_code is  null) then v.sub_code END)

  

";
/*
$sql="SELECT b.id, b.session_yr,b.`session`,b.dept,b.course,b.branch,b.semester,
a.mis_sub_id,a.sub_code,a.grade,a.admn_no,c.*
 FROM final_semwise_marks_foil_desc_freezed a 
INNER JOIN final_semwise_marks_foil_freezed b ON b.id=a.foil_id
inner join subjects c on c.subject_id=a.sub_code
WHERE a.admn_no=?
AND a.grade='F' group by a.sub_code";*/
        //echo $sql;die();
        $query = $this->db->query($sql,array($admn_no));

    //  echo $this->db->last_query(); die();
        if ($this->db->affected_rows() >= 0) {
            return $query->result();
        } else {
            return false;
        }
    }

    function get_offered_subjects($did,$cid,$bid,$sem,$syear,$sess,$ptype,$section){


      if($ptype=="noncbcs"){ $tbl="old_subject_offered";}
      if($ptype=="cbcs"){ $tbl="cbcs_subject_offered";}

      if($section=='A' || $section=='B' || $section=='C' || $section=='D'){
        $group=1;
      }
      else if($section=='E' || $section=='F' || $section=='G' || $section=='H'){
        $group=2;
      }



      if($section=='na'|| $ptype=="noncbcs"){
        $sql="SELECT a.*,concat(a.sub_name,' [',a.sub_code,'] [',a.lecture,'-',a.tutorial,'-',a.practical,']') AS subject FROM $tbl a
WHERE a.dept_id=? AND a.course_id=? AND a.branch_id=? AND a.semester=? AND a.session_year=? AND a.`session`=?"  ;
        $query = $this->db->query($sql,array($did,$cid,$bid,$sem,$syear,$sess));

      }else{
      $sql="SELECT a.*,concat(a.sub_name,' [',a.sub_code,'] [',a.lecture,'-',a.tutorial,'-',a.practical,']') AS subject FROM $tbl a
WHERE a.dept_id=? AND a.course_id=? AND a.branch_id=? AND a.semester=? AND a.session_year=? AND a.`session`=? and a.sub_group=?"  ;
      $query = $this->db->query($sql,array($did,$cid,$bid,$sem,$syear,$sess,$group));
      }



//echo $sql;
      //  echo $this->db->last_query(); die();
        if ($this->db->affected_rows() >= 0) {
            return $query->result();
        } else {
            return false;
        }

    }
    //all_list

    function get_offered_subjects_all($did,$cid,$bid,$sem,$syear,$sess,$ptype,$selgroup){


      if($ptype=="noncbcs"){ $tbl="old_subject_offered";}
      if($ptype=="cbcs"){ $tbl="cbcs_subject_offered";}

      if($selgroup=='na'|| $ptype=="noncbcs"){
        $sql="SELECT a.*,concat(a.sub_name,' [',a.sub_code,'] [',a.lecture,'-',a.tutorial,'-',a.practical,']') AS subject FROM $tbl a
WHERE a.dept_id=? AND a.course_id=? AND a.branch_id=? AND a.semester=? AND a.session_year=? AND a.`session`=?"  ;
        $query = $this->db->query($sql,array($did,$cid,$bid,$sem,$syear,$sess));

      }else{
      $sql="SELECT a.*,concat(a.sub_name,' [',a.sub_code,'] [',a.lecture,'-',a.tutorial,'-',a.practical,']') AS subject FROM $tbl a
WHERE a.dept_id=? AND a.course_id=? AND a.branch_id=? AND a.semester=? AND a.session_year=? AND a.`session`=? and a.sub_group=?"  ;
      $query = $this->db->query($sql,array($did,$cid,$bid,$sem,$syear,$sess,$selgroup));
      }



//echo $sql;
      //  echo $this->db->last_query(); die();
        if ($this->db->affected_rows() >= 0) {
            return $query->result();
        } else {
            return false;
        }

    }


    function get_subject_details_by_id($ptype,$id){

      if($ptype=="noncbcs"){ $tbl="old_subject_offered";}
      if($ptype=="cbcs"){ $tbl="cbcs_subject_offered";}



        $sql="SELECT a.* FROM $tbl a where a.id=? ";
        $query = $this->db->query($sql,array($id));
//echo $sql;

        if ($this->db->affected_rows() >= 0) {
            return $query->row();
        } else {
            return false;
        }

    }
	
	function get_subject_details_by_id_old($id){
		
		$tmp=explode(',',$id);
		//echo '<pre>'; print_r($tmp);echo '</pre>';

              $sql="SELECT * FROM subjects WHERE subject_id=? LIMIT 1";
        $query = $this->db->query($sql,array($tmp[4]));
//echo $sql;

        if ($this->db->affected_rows() >= 0) {
            return $query->row();
        } else {
            return false;
        }

    }

    function get_section($admn_no,$session_year){

      $sql="SELECT section FROM stu_section_data WHERE admn_no=? AND session_year=?";
      $query = $this->db->query($sql,array($admn_no,$session_year));

      if ($this->db->affected_rows() >= 0) {
          return $query->row();
      } else {
          return false;
      }

    }

  function insert_alternate($data)
	{
		if($this->db->insert('alternate_course',$data))
			return $this->db->insert_id();
		else
			return FALSE;
	}

  function insert_alternate_all($data)
	{
		if($this->db->insert('alternate_course_all',$data))
			return $this->db->insert_id();
		else
			return FALSE;
	}


  function get_all_alternate_subjects(){
    $sql="SELECT a.id,a.admn_no,a.old_subject_name,a.old_subject_code,a.alternate_subject_name,a.alternate_subject_code FROM alternate_course a";
    $query = $this->db->query($sql);

    if ($this->db->affected_rows() >= 0) {
        return $query->result();
    } else {
        return false;
    }

  }
  function get_all_alternate_subjects_all(){
    $sql="SELECT a.id,a.old_subject_name,a.old_subject_code,a.alternate_subject_name,a.alternate_subject_code FROM alternate_course_all a";
    $query = $this->db->query($sql);

    if ($this->db->affected_rows() >= 0) {
        return $query->result();
    } else {
        return false;
    }

  }

  function fetch_alternate_subject($admn_no,$subject_code){

    $sql="SELECT a.alternate_subject_code FROM alternate_course a WHERE a.admn_no=? AND a.old_subject_code=?";
    $query = $this->db->query($sql,array($admn_no,$subject_code));

    if ($this->db->affected_rows() >= 0) {
        return $query->row();
    } else {
        return false;
    }


  }

  function delete_alternate($id){
    $query = $this->db->query("delete from alternate_course where id='".$id."'");
        //echo $this->db->last_query();
        if($this->db->affected_rows()){
          return true;
        }
        else{
          return false;
        }
  }
  function delete_alternate_all($id){
    $query = $this->db->query("delete from alternate_course_all where id='".$id."'");
        //echo $this->db->last_query();
        if($this->db->affected_rows()){
          return true;
        }
        else{
          return false;
        }
  }
  function get_details($id){
    $sql="		SELECT a.*,CONCAT_WS(' ',b.first_name,b.middle_name,b.last_name)AS sname FROM alternate_course a
		INNER JOIN user_details b ON b.id=a.admn_no
		WHERE a.id=?";
    $query = $this->db->query($sql,array($id));
    if ($this->db->affected_rows() >= 0) {
        return $query->row();
    } else {
        return false;
    }


  }

  //new logic subjects and cbcs Master
  function get_offered_subjects_all_new($id){




    if($id=="noncbcs")
    {
      $sql="SELECT CONCAT_WS(',',c.dept_id,d.course_id,d.branch_id,b.semester,a.subject_id,a.name,a.lecture,a.tutorial,a.practical,
a.`type`,a.credit_hours,a.contact_hours)AS id,
concat(a.name,' -',a.subject_id,'- [',a.lecture,'-',a.tutorial,'-',a.practical,']') AS subject
 FROM subjects a
INNER JOIN course_structure b ON b.id=a.id
INNER JOIN dept_course c ON c.aggr_id=b.aggr_id
INNER JOIN course_branch d ON d.course_branch_id=c.course_branch_id
GROUP BY a.name ORDER BY trim(a.name) "  ;
      $query = $this->db->query($sql);

    }
    if($id=="cbcs")
    {
      $sql="SELECT CONCAT_WS(',',a.dept_id, a.sub_code,a.sub_name,a.lecture,
a.tutorial,a.practical,a.sub_type,a.credit_hours,a.contact_hours)AS id,
CONCAT(a.sub_name,' [',a.sub_code,'] [',a.lecture,'-',a.tutorial,'-',a.practical,']') AS subject FROM
cbcs_course_master a  GROUP BY a.sub_code ORDER BY a.sub_name "  ;
      $query = $this->db->query($sql);

    }




//echo $sql;
    //  echo $this->db->last_query(); die();
      if ($this->db->affected_rows() >= 0) {
          return $query->result();
      } else {
          return false;
      }

  }

    function check_course($id){

      $sql="SELECT * FROM alternate_course_all WHERE old_subject_code=?";
      $query = $this->db->query($sql,array($id));
      if ($this->db->affected_rows() > 0) {
          return true;
      } else {
          return false;
      }


    }

}

?>
