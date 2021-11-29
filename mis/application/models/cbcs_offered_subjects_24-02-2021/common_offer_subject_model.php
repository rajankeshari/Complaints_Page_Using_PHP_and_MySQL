<?php

class Common_offer_subject_model extends CI_Model {

    function __construct() {
        parent::__construct();
    }
//copy previous year course by @bhijeet start
function InsertSubjectDiscription($insertCourseDescription){
  $selectValue=array(
    "sub_offered_id"=>$insertCourseDescription['sub_offered_id'],
    "emp_no"=>$insertCourseDescription['emp_no'],
    "coordinator"=>$insertCourseDescription['coordinator'],
    "sub_id"=>$insertCourseDescription['sub_id'],
    "section"=>$insertCourseDescription['section'],
  );
  $this->db->select('*');
  $this->db->from('cbcs_subject_offered_desc');
  $this->db->where($selectValue);
  $cnt = $this->db->get()->num_rows();
  if($cnt==0){
  $this->db->insert('cbcs_subject_offered_desc', $insertCourseDescription);
  if($this->db->affected_rows() > 0){
    return true;
  }else{
    return false;
  }
  }else{
  return false;
  }
}

function getSubjectDiscription($id){
  $sql="select * from cbcs_subject_offered_desc a where a.sub_offered_id='$id'";
        $query = $this->db->query($sql);
        if ($this->db->affected_rows() > 0) {
      //   echo  $this->db->last_query();
            return $query->result();
        } else {
            return false;
        }
}
function insertCourseOffered($insertCourse){
  $selectValue=array(
    "session"=>$insertCourse['session'],
    "session_year"=>$insertCourse['session_year'],
    "sub_code"=>$insertCourse['sub_code'],
    "sub_group"=>$insertCourse['sub_group'],
    "course_id"=>$insertCourse['course_id'],
    "branch_id"=>$insertCourse['branch_id'],
    "semester"=>$insertCourse['semester'],
  );
  $this->db->select('*');
  $this->db->from('cbcs_subject_offered');
  $this->db->where($selectValue);
  $cnt = $this->db->get()->num_rows();
  if($cnt==0){
  $this->db->insert('cbcs_subject_offered', $insertCourse);
  if($this->db->affected_rows() > 0){
    return $this->db->insert_id();
  }else{
    return false;
  }
  }else{
  return false;
  }
}


function copyofferedCourse($session_year,$session,$course_id,$branch_id,$semester,$credit_policy_id){
  $session_yrData=explode("-",$session_year);
  $s1=$session_yrData[0]-1;
  $s2=$session_yrData[1]-1;
  $prevSession_year=$s1."-".$s2;

  $sql="select * from cbcs_subject_offered a where a.session_year='$prevSession_year' and a.`session`='$session'
        and a.semester='$semester' and a.course_id='$course_id' and a.branch_id='$branch_id'";
        $query = $this->db->query($sql);
        if ($this->db->affected_rows() > 0) {
      //   echo  $this->db->last_query();
            return $query->result();
        } else {
            return false;
        }

}

function checkTemp($session_year,$session,$course_id,$branch_id,$semester){
  $session_yrData=explode("-",$session_year);
  $s1=$session_yrData[0];
  $s2=$session_yrData[1];
//  $sqlreg="select * from reg_regular_form a where a.session_year='' and a.`session`='' and a.course_id='' and a.branch_id='' and a.semester=''";
  $sqlreg="select * from reg_regular_form a where a.session_year='$session_year' and a.`session`='$session' and a.course_id='$course_id' and a.branch_id='$branch_id' and a.semester='$semester'";

  $queryreg = $this->db->query($sqlreg);
  $numRows=$queryreg->num_rows();
  if($numRows==0){
  $sql="select * from cbcs_institute_core a where a.session_year='$session_year' order by id desc limit 1";
  $query = $this->db->query($sql);
//   echo  $this->db->last_query(); die();
  if ($this->db->affected_rows() > 0) {

    $result=$query->result();
    $wef=$result[0]->session_year;
    $credit_policy_id=$result[0]->id;
    $session_yrWef=explode("-",$wef);
    $wef1=$session_yrWef[0];
  /*  if($wef1==$s1){
      return false;
    }else{
      return $credit_policy_id;
    } */
    return false;
    } else {
      return true;
  }
}else{
  return false;
}
}


function get_current_offered_details($session_year,$session,$dept_id,$course_id,$branch_id,$semester){
  $session_yrData=explode("-",$session_year);
  $s1=$session_yrData[0]-1;
  $s2=$session_yrData[1]-1;
  $prevSession_year=$s1."-".$s2;

$sql="(select a.id,a.session_year,a.`session`,a.dept_id,a.course_id,a.branch_id,a.sub_code from cbcs_subject_offered a
      inner join cbcs_subject_offered_desc b on a.id=b.sub_offered_id
      where a.course_id='$course_id' and a.branch_id='$branch_id' and a.semester='$semester' and a.session_year='$session_year' and a.`session`='$session'
      group by b.sub_offered_id)";
      $query = $this->db->query($sql);
    //  echo $this->db->last_query(); die();
    //  $numRows=$this->db->last_query(); die();
      $numRows=$query->num_rows();

    if($numRows > 0)
    {

    }else{
      $sqlprev="(select a.id,a.session_year,a.`session`,a.dept_id,a.course_id,a.branch_id,a.sub_code from cbcs_subject_offered a
            inner join cbcs_subject_offered_desc b on a.id=b.sub_offered_id
            where a.course_id='$course_id' and a.branch_id='$branch_id' and a.semester='$semester' and a.session_year='$prevSession_year' and a.`session`='$session'
            group by b.sub_offered_id)";
            $queryprev = $this->db->query($sqlprev);
          //  echo $this->db->last_query(); die();
            $numRowss=$queryprev->num_rows();
            if($numRowss > 0){
              return  "1";
            }else{
              return "0";
            }
    }

  }
        
//     function get_dcb_list($id)
//     {
          
//       $sql = "SELECT a.dept_id,b.course_id,b.branch_id,c.name AS dname,
// d.name AS cname,e.name AS bname
// FROM dept_course a
// INNER JOIN course_branch b ON b.course_branch_id=a.course_branch_id
// INNER JOIN cbcs_departments c ON c.id=a.dept_id
// INNER JOIN cbcs_courses d ON d.id=b.course_id
// INNER JOIN cbcs_branches e ON e.id=b.branch_id
// WHERE a.dept_id=? AND c.status=1 AND d.status=1 AND e.status=1
// GROUP BY c.id,d.id,e.id";
        
        
//         $query = $this->db->query($sql,array($id));

       
//         if ($this->db->affected_rows() > 0) {
//             return $query->result();
//         } else {
//             return false;
//         }
//     }

    function get_dept_list()
    {
          
      $sql = "select a.* from cbcs_departments a where a.`type`='academic' and a.status=1 order by a.name;";
        
        
        $query = $this->db->query($sql);

       
        if ($this->db->affected_rows() > 0) {
            return $query->result();
        } else {
            return false;
        }
    }

function get_dname($id){
    $sql = "select a.name from cbcs_departments a where a.id=? and a.status=1";
    $query = $this->db->query($sql,array($id));
    if ($this->db->affected_rows() > 0) {
       return $query->row()->name;
    } else {
       return false;
    }
}
    

function get_cname($id){
    $sql = "select a.name from cbcs_courses a where a.id=? and a.status=1";
    $query = $this->db->query($sql,array($id));
    if ($this->db->affected_rows() > 0) {
       return $query->row()->name;
    } else {
       return false;
    }
}

function get_bname($id){
    $sql = "select a.name from cbcs_branches a where a.id=? and a.status=1";
    $query = $this->db->query($sql,array($id));
    if ($this->db->affected_rows() > 0) {
       return $query->row()->name;
    } else {
       return false;
    }
}
function get_session_year($id){
    $sql = "select * from mis_session_year order by id desc";
    $query = $this->db->query($sql,array($id));
    if ($this->db->affected_rows() > 0) {
       return $query->result();
    } else {
       return false;
    }
}

function get_session($id){
    $sql = "select * from mis_session";
    $query = $this->db->query($sql,array($id));
    if ($this->db->affected_rows() > 0) {
       return $query->result();
    } else {
       return false;
    }
}
function get_paper_types(){
     $sql = "select * from mis_paper_type order by id";
    $query = $this->db->query($sql);
    if ($this->db->affected_rows() > 0) {
       return $query->result();
    } else {
       return false;
    }


}

function sub_master_insert($data) {
        if ($this->db->insert('cbcs_subject_master', $data))
        return $this->db->insert_id();
            //return TRUE;
        else
            return FALSE;
    }

    function get_sub_master_lastrow($id){

        $sql = "select * from cbcs_subject_master where id=?";
        $query = $this->db->query($sql,array($id));
        if ($this->db->affected_rows() > 0) {
           return $query->row();
        } else {
           return false;
        }

    }
    function get_sub_master_all(){
            $sql = "select * from cbcs_subject_master order by sub_name";
        $query = $this->db->query($sql,array($id));
        if ($this->db->affected_rows() > 0) {
           return $query->result();
        } else {
           return false;
        }

    }

    // function insert_backup($id,$action){
        
    //     $sql = "insert into cbcs_subject_master_backup select * from cbcs_subject_master where id=?";
    //     $query = $this->db->query($sql,array($id));

    //     $sql = "update cbcs_subject_master_backup set action='".$action."' where id=".$id;
    //     $query = $this->db->query($sql);

    //     if($action=='modify'){
    //         $sql = "update cbcs_subject_master set action='".$action."' where id=".$id;
    //         $query = $this->db->query($sql);
    //     }

    //     if ($this->db->affected_rows() > 0) {
    //         return TRUE;
    //     } else {
    //         return false;
    //     }


    // }
    //  function delete_rowid($id) {
    //     $this->db->where('id', $id);
    //     $this->db->delete('cbcs_subject_master');
    // }

    function sub_master_update($data,$con)
    {
        $con1['id'] = $con;
         if($this->db->update('cbcs_subject_master',$data,$con1))
         {
                    return true;
         } 
            return false;
            
    }

    function get_courses(){

        $sql = "select * from cbcs_courses";
        $query = $this->db->query($sql,array($id));
        if ($this->db->affected_rows() > 0) {
           return $query->result();
        } else {
           return false;
        }



    }

//     function get_branch_bycourse($cid){

//         $query = $this->db->query("select a.* from cbcs_branches a
// inner join course_branch b on b.branch_id=a.id
// where b.course_id='".$cid."'
// order by a.name");
//         if($query->num_rows() > 0)
//             return $query->result();
//         else
//             return false;


//     }

    function get_component_list_details($cid,$gid,$sem){

//and a.course_component like 'D%' has been added later, when decided eso will not part of semester

        /*$sql = "select a.course_component,d.name,a.sequence,count(a.course_component)as countsub from cbcs_coursestructure_policy a inner join cbcs_curriculam_policy b on b.cbcs_credit_points_policy_id=b.id
inner join cbcs_credit_points_policy c on c.id=b.cbcs_credit_points_policy_id inner join cbcs_course_component d on d.id=a.course_component
inner join cbcs_credit_points_master e on e.course_id=c.course_id where a.course_id=? and e.branch_id=? and a.sem=?  group by a.course_component";*/
$sql="SELECT a.course_component,a.sequence, COUNT(a.course_component) AS countsub
FROM cbcs_comm_coursestructure_policy a
WHERE a.course_id='$cid' AND a.cbcs_curriculam_policy_id='$gid' and a.sem='$sem'";
        $query = $this->db->query($sql,array($cid,$gid,$sem));
		//echo $this->db->last_query();die();
        if ($this->db->affected_rows() > 0) {
           return $query->result();
        } else {
           return false;
        }


    }

    function get_component_wise_list($sy,$sess,$cid,$sem,$g){
	//	echo $ftype; exit;
	/*if($group==1)
		$g='group1';
	elseif($group==2)
		$g='group2';
*/

        /*$sql="SELECT a.course_component,'Institute Core' AS `name`,a.sequence,a.status,a.cbcs_curriculam_policy_id
FROM cbcs_comm_coursestructure_policy a where a.cbcs_curriculam_policy_id='group1'";*/
       // $query = $this->db->query($sql,array($sy,$sess,$cid,$bid,$sem,$group,$ftype));
	   /*$sql="SELECT a.course_component,'Institute Core' AS `name`,a.sequence,a.status,a.cbcs_curriculam_policy_id
FROM cbcs_comm_coursestructure_policy a where a.cbcs_curriculam_policy_id='$g'";*/
	$sql="SELECT a.course_component,d.name,a.cbcs_curriculam_policy_id,a.sequence,f.sub_code,f.sub_name,f.sub_type,a.status,f.id, GROUP_CONCAT(DISTINCT CONCAT_WS(' ',h.salutation,h.first_name,h.middle_name,h.last_name),' (',g.section,') ',' / ', CASE WHEN g.coordinator='1' THEN 'Yes' WHEN g.coordinator='0' THEN 'No' END SEPARATOR '<br>
') AS fname
FROM cbcs_comm_coursestructure_policy a
INNER JOIN cbcs_course_component d ON d.id=a.course_component

LEFT JOIN cbcs_subject_offered f ON f.session_year='$sy' AND f.`session`='$sess' AND f.course_id=a.course_id AND f.semester=a.sem AND f.sub_category= CONCAT(a.course_component,a.sequence)  AND f.sub_group=a.cbcs_curriculam_policy_id
LEFT JOIN cbcs_subject_offered_desc g ON g.sub_offered_id=f.id
LEFT JOIN user_details h ON h.id=g.emp_no
WHERE a.course_id='$cid' AND a.sem='$sem' AND a.course_component='IC' AND a.cbcs_curriculam_policy_id='$g'
GROUP BY a.sequence,f.sub_code
ORDER BY a.sequence";
        $query = $this->db->query($sql,array($group));
		//echo $this->db->last_query(); die();
         if ($this->db->affected_rows() > 0) {
            return $query->result();
         } else {
            return false;
         }


    }

    function get_subject_master_list($did=""){
        
        $sql = "select a.* from cbcs_course_master a WHERE dept_id=? GROUP BY a.sub_name,a.lecture,a.tutorial,a.practical
 order by trim(a.sub_name)";
             $query = $this->db->query($sql,array($did));

       
        if ($this->db->affected_rows() > 0) {
           return $query->result();
        } else {
           return false;
        }


    }

    /*function get_subject_by_id($cid,$bid,$sid){

        if( $cid!='NA' && $bid!='NA'){

            $sql = "select * from cbcs_subject_master where course_id=? and branch_id=? and sub_code=? order by trim(sub_name)";
             $query = $this->db->query($sql,array($cid,$bid,$sid));
        }
        
        else{
             $sql = "select * from cbcs_subject_master where sub_code=? order by trim(sub_name)";
             $query = $this->db->query($sql,array($sid));
        }
        

        if ($this->db->affected_rows() > 0) {
           return $query->result();
        } else {
           return false;
        }

    }*/
	
	function get_subject_by_id($did,$sid){

        if( !empty($did)){

            $sql = "select * from cbcs_course_master where  dept_id=? and sub_code=? order by trim(sub_name)";
             $query = $this->db->query($sql,array($did,$sid));
        }

        else{
             $sql = "select * from cbcs_subject_master where sub_code=? order by trim(sub_name)";
             $query = $this->db->query($sql,array($sid));
        }


        if ($this->db->affected_rows() > 0) {
           return $query->result();
        } else {
           return false;
        }

    }
/*
    function get_subject_details($course_id,$branch_id,$scode,$wef){

         if( $course_id!='NA' && $branch_id!='NA'){

        $sql = "select a.* from cbcs_subject_master a where a.course_id=? and a.branch_id=?  and a.sub_code=? and a.wef_year=? limit 1";
        $query = $this->db->query($sql,array($course_id,$branch_id,$scode,$wef));
        }else{

            $sql = "select a.* from cbcs_subject_master a where  a.wef_year=? limit 1";
            $query = $this->db->query($sql,array($wef));

        }
        
        if ($this->db->affected_rows() > 0) {
           return $query->row();
        } else {
           return false;
        }


    }*/
	
	function get_subject_details($dept_id,$scode,$wef){

         if( !empty($dept_id)){

        $sql = "select a.* from cbcs_course_master a where a.dept_id=?  and a.sub_code=? and a.wef_year=? ";
        $query = $this->db->query($sql,array($dept_id,$scode,$wef));
        }else{

            $sql = "select a.* from cbcs_subject_master a where  a.wef_year=? ";
            $query = $this->db->query($sql,array($wef));

        }

        if ($this->db->affected_rows() > 0) {
           return $query->row();
        } else {
           return false;
        }


    }

    //==========get subject name
    function get_sub_name($sub_code){
        $sql="SELECT sub_name FROM cbcs_course_master WHERE sub_code='$sub_code'";
        $result=$this->db->query($sql);
        return $result->result();
    }

    //==========Insert into subject offered table

    function insert_subject_offered($data)
    {
        if($this->db->insert('cbcs_subject_offered',$data))
            //return TRUE;
                        return $this->db->insert_id();
        else
            return FALSE;
    }



    //================Insert into subject offered desc
    function insert_batch_subject_offered_child($data)
    {
        if($this->db->insert_batch('cbcs_subject_offered_desc',$data))
            return TRUE;
        else
            return FALSE;
    }
   




    //Insert into subject mapping table

    function insert_subject_mapping($data)
    {
        if($this->db->insert('subject_mapping',$data))
            //return TRUE;
                        return $this->db->insert_id();
        else
            return FALSE;
    }



    //insert into subject mapping description tabl

     function insert_batch_subject_mapping_desc($data)
    {
        if($this->db->insert_batch('subject_mapping_des',$data))
            return TRUE;
        else
            return FALSE;
    }
    //==================================Delete Row of subject offered table======================================

    function insert_backup($id,$action){
        
        $sql = "insert into cbcs_subject_offered_backup select * from cbcs_subject_offered where id=?";
        $query = $this->db->query($sql,array($id));

        $sql = "insert into cbcs_subject_offered_desc_backup select * from cbcs_subject_offered_desc where sub_offered_id=?";
        $query = $this->db->query($sql,array($id));

        if($action=='delete'){
        $sql = "update cbcs_subject_offered_backup set action='".$action."' where id=".$id;
        $query = $this->db->query($sql);
        }

        if($action=='modify'){
            $sql = "update cbcs_subject_master set action='".$action."' where id=".$id;
            $query = $this->db->query($sql);
        }

        if ($this->db->affected_rows() > 0) {
            return TRUE;
        } else {
            return false;
        }


    }
     function delete_rowid_subject_offered_table($id) {
        $this->db->where('id', $id);
        $this->db->delete('cbcs_subject_offered');
    }

    function delete_rowid_subject_offered_desc_table($id) {
        $this->db->where('sub_offered_id', $id);
        $this->db->delete('cbcs_subject_offered_desc');
    }

    function get_subject_offered_desc_ft_details($id){

          $sql = "select a.*,b.dept_id from cbcs_subject_offered_desc a  inner join cbcs_subject_offered b on b.id=a.sub_offered_id where b.id=?";
        $query = $this->db->query($sql,array($id));
        if ($this->db->affected_rows() > 0) {
           return $query->result();
        } else {
           return false;
        }


    }

    function get_offered_subject_by_id($id){

          $sql = "select * from cbcs_subject_offered where id=?";
        $query = $this->db->query($sql,array($id));
        if ($this->db->affected_rows() > 0) {
           return $query->row();
        } else {
           return false;
        }


    }

     //function get_offered_sub_list($sy,$sess)
	  function get_offered_sub_list($dept_id)
    {
          
      $sql = "SELECT a.session_year,a.`session`,a.dept_id,a.course_id,a.branch_id,a.semester,a.sub_code,a.sub_name,a.lecture
,a.tutorial,a.practical,a.credit_hours,a.contact_hours,a.sub_type,a.pre_requisite,a.pre_requisite_subcode,a.sub_category,
a.sub_group,a.criteria,b.part,b.emp_no, CASE WHEN b.coordinator='1' THEN 'Yes' ELSE 'No' END AS marks_up_rt,
c.name AS dname,d.name AS cname,e.name AS bname, CONCAT_WS(' ',f.first_name,f.middle_name,f.last_name) AS fname,a.minstu,a.maxstu
FROM cbcs_subject_offered a
INNER JOIN cbcs_subject_offered_desc b ON b.sub_offered_id=a.id
INNER JOIN cbcs_departments c ON c.id=a.dept_id AND c.`status`='1'
LEFT JOIN cbcs_courses d ON d.id=a.course_id AND d.`status`='1'
LEFT JOIN cbcs_branches e ON e.id=a.branch_id AND e.`status`='1'
INNER JOIN user_details f ON f.id=b.emp_no
WHERE a.dept_id='$dept_id' AND a.course_id='comm' AND a.branch_id='comm' /*and a.session_year=? and a.`session`=?*/ ";
        
        
        $query = $this->db->query($sql,array($sy,$sess));
//echo $this->db->last_query();die();
       
        if ($this->db->affected_rows() > 0) {
            return $query->result();
        } else {
            return false;
        }
    }

    //==================================================================================================================


    

}

?>