<?php

class Offer_subject_model extends CI_Model {

    function __construct() {
        parent::__construct();
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
public function getbranch($course_id){

    $query=$this->db->query("SELECT a.*,b.name,b.id from course_branch a INNER join cbcs_branches b on b.id=a.branch_id  where a.course_id='$course_id' order BY b.name asc ");
    return $query->result_array();
}


    public function get_student_list($year,$session,$course,$branch,$sem,$group){

      if($course=='comm'){
      /*  if($group=='Group1'){
          $group='1';
        }else{
            $group='2';
        }*/
        $sql = "select * from reg_regular_form a where a.session_year='$year' and a.`session`='$session' and
         a.section='$group' and a.semester='$sem';";
      }else{
        $sql = "select * from reg_regular_form a where a.session_year='$year' and a.`session`='$session' and
        a.course_id='$course' and a.branch_id='$branch' and a.semester='$sem';";
      }

      $query = $this->db->query($sql);
       //  echo  $this->db->last_query(); die();
        if ($this->db->affected_rows() > 0) {

            return $query->result();
        } else {
            return false;
        }

    }
    public function save_opted_subject($year,$session,$course,$branch,$sem,$group,$formid,$admin_no){

      if($course=='comm'){
      $sql = "select * from cbcs_subject_offered a where a.session_year='$year' and a.`session`='$session' and a.course_id='$course' and a.branch_id='$branch'  and a.semester='$sem' and a.sub_group = '$group' union all
      select * from cbcs_subject_offered a where a.session_year='$year' and a.`session`='$session'
       and a.semester='$sem' and a.sub_group like '%comm%';";
      $query = $this->db->query($sql);
  //   echo  $this->db->last_query(); exit;
        if ($this->db->affected_rows() > 0) {
            foreach($query->result() as $result){
              $subname=$result->sub_name;
              $subcode=$result->sub_code;
              $cntrow = $this->db->query("SELECT * FROM cbcs_stu_course where form_id='$formid' and admn_no='$admin_no' and subject_code='$subcode' and sub_category='$result->sub_category' and session_year='$year' and session='$session'");
			 // echo  $this->db->last_query(); exit;
              $cnt= $cntrow->num_rows();
				//echo "cnt rows".$cnt;
			  //echo  $this->db->last_query(); exit;
              if($cnt=='0'){
              $sql = "insert into cbcs_stu_course (form_id,admn_no,subject_code,subject_name,sub_category,course,branch,session_year,session)
              values ('$formid','$admin_no','$subcode','$subname','$result->sub_category','$course','$branch','$year','$session')";
              $this->db->query($sql);
            }
            }
          //  print_r($query->result());exit;
            //  echo $this->db->last_query();die();

            return true;
        } else {
          $this->session->set_flashdata('error','Offered Subjects not found for this acadmic year.');
          redirect('/cbcs_offered_subjects/offer_subject/opted_subject/', 'refresh');
        }
      }else{
        $sql = "select * from cbcs_subject_offered a where a.session_year='$year' and a.`session`='$session' and
        a.course_id='$course' and a.branch_id='$branch' and a.semester='$sem'";
        //  $this->db->last_query();die();
        $query = $this->db->query($sql);

      #  echo"not common ".  $this->db->last_query(); exit;

          if ($this->db->affected_rows() > 0) {
            $cnt=0;
              foreach($query->result() as $result){
                $subname=$result->sub_name;
                $subcode=$result->sub_code;
                $sub_category=$result->sub_category;
                $cntrow = $this->db->query("SELECT * FROM cbcs_stu_course where form_id='$formid' and admn_no='$admin_no' and subject_code='$subcode'
                  and course='$course' and sub_category='$sub_category' and branch='$branch' and session_year='$year' and session='$session'");
                $cntrows= $cntrow->num_rows();
              //echo  $this->db->last_query();
              if($cntrows==0){
              $sql = "insert into cbcs_stu_course (form_id,admn_no,subject_code,subject_name,priority,sub_category,sub_category_cbcs_offered,course,branch,session_year,session)
              values ('$formid','$admin_no','$subcode','$subname','0','$sub_category','','$course','$branch','$year','$session')";
                $this->db->query($sql);
                $cnt=$cnt+1;
              }

              }
            //  print_r($query->result());exit;
              //  echo $this->db->last_query();die();
              return true;
          } else {
            $this->session->set_flashdata('error','Offered Subjects not found for this Acadmic Year.');
            redirect('/cbcs_offered_subjects/offer_subject/opted_subject/', 'refresh');
          }
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

    function get_component_list_details($cid,$bid,$sem){

//and a.course_component like 'D%' has been added later, when decided eso will not part of semester

        $sql = "select a.course_component,d.name,a.sequence,count(a.course_component)as countsub from cbcs_coursestructure_policy a inner join cbcs_curriculam_policy b on b.cbcs_credit_points_policy_id=a.cbcs_curriculam_policy_id
inner join cbcs_credit_points_policy c on c.id=b.cbcs_credit_points_policy_id inner join cbcs_course_component d on d.id=a.course_component
inner join cbcs_credit_points_master e on e.course_id=c.course_id where a.course_id=? and e.branch_id=? and a.sem=? /*and a.course_component like 'D%'*/ group by a.course_component";
        $query = $this->db->query($sql,array($cid,$bid,$sem));
        if ($this->db->affected_rows() > 0) {
           return $query->result();
        } else {
           return false;
        }


    }

    function get_component_wise_list($sy,$sess,$cid,$bid,$sem,$ftype){

//         $sql = "select a.course_component,d.name,a.sequence,a.status from cbcs_coursestructure_policy a inner join cbcs_curriculam_policy b on b.cbcs_credit_points_policy_id=b.id
// inner join cbcs_credit_points_policy c on c.id=b.cbcs_credit_points_policy_id inner join cbcs_course_component d on d.id=a.course_component
// inner join cbcs_credit_points_master e on e.course_id=c.course_id where a.course_id=? and e.branch_id=? and a.sem=? and a.course_component=?";
//         $query = $this->db->query($sql,array($cid,$bid,$sem,$ftype));
//         if ($this->db->affected_rows() > 0) {
//            return $query->result();
//         } else {
//            return false;
//         }

        $sql="SELECT a.course_component,d.name,a.sequence,f.sub_code,f.sub_name,f.sub_type,a.status,f.id,GROUP_CONCAT(distinct CONCAT_WS(' ',h.salutation,h.first_name,h.middle_name,h.last_name),' / ',CASE WHEN g.coordinator='1' THEN 'Yes' WHEN g.coordinator='0' THEN 'No' END SEPARATOR '<br>') AS fname
FROM cbcs_coursestructure_policy a
INNER JOIN cbcs_curriculam_policy b ON b.cbcs_credit_points_policy_id=a.cbcs_curriculam_policy_id
INNER JOIN cbcs_credit_points_policy c ON c.id=b.cbcs_credit_points_policy_id
INNER JOIN cbcs_course_component d ON d.id=a.course_component
INNER JOIN cbcs_credit_points_master e ON e.course_id=c.course_id
LEFT join cbcs_subject_offered f on f.session_year=? and f.`session`=? and f.course_id=a.course_id and f.branch_id=e.branch_id
and f.semester=a.sem and f.sub_category=concat(a.course_component,a.sequence)
LEFT join cbcs_subject_offered_desc g on g.sub_offered_id=f.id
LEFT join user_details h on h.id=g.emp_no


WHERE a.course_id=? AND e.branch_id=? AND a.sem=? AND a.course_component=?
group by a.sequence,f.sub_code
order by a.sequence";

/*$sql="SELECT a.course_component,d.name,a.sequence,f.sub_code,f.sub_name,f.sub_type,a.status,f.id,
CONCAT_WS(' ',h.salutation,h.first_name,h.middle_name,h.last_name) AS fname, 
CASE WHEN g.coordinator='1' THEN 'Yes' WHEN g.coordinator='0' THEN 'No' END   AS uploadright
FROM cbcs_coursestructure_policy a 
INNER JOIN cbcs_curriculam_policy b ON b.cbcs_credit_points_policy_id=a.cbcs_curriculam_policy_id 
INNER JOIN cbcs_credit_points_policy c ON c.id=b.cbcs_credit_points_policy_id 
INNER JOIN cbcs_course_component d ON d.id=a.course_component 
INNER JOIN cbcs_credit_points_master e ON e.course_id=c.course_id LEFT join cbcs_subject_offered f on f.session_year='2019-2020' and f.`session`='Monsoon' and f.course_id=a.course_id and f.branch_id=e.branch_id and f.semester=a.sem and f.sub_category=concat(a.course_component,a.sequence) LEFT join cbcs_subject_offered_desc g on g.sub_offered_id=f.id LEFT join user_details h on h.id=g.emp_no WHERE a.course_id='m.tech' AND e.branch_id='cse' AND a.sem='1' AND a.course_component='DC' group by a.sequence,f.sub_code order by a.sequence";*/
        $query = $this->db->query($sql,array($sy,$sess,$cid,$bid,$sem,$ftype));
         if ($this->db->affected_rows() > 0) {
            return $query->result();
         } else {
            return false;
         }


    }

    /*function get_subject_master_list($cid="",$bid=""){
        if(!empty($cid) && !empty($bid)){
            $sql = "select a.* from cbcs_subject_master a where a.course_id=? and a.branch_id=? GROUP BY a.sub_name,a.lecture,a.tutorial,a.practical order by trim(a.sub_name)";
             $query = $this->db->query($sql,array($cid,$bid));
        }else{
            $sql = "select a.* from cbcs_subject_master a GROUP BY a.sub_name,a.lecture,a.tutorial,a.practical order by trim(a.sub_name)";
             $query = $this->db->query($sql);
        }


        if ($this->db->affected_rows() > 0) {
           return $query->result();
        } else {
           return false;
        }


    }*/
	
	function get_subject_master_list($did=""){
		
        if(!empty($did)){
			
            $sql = "select a.* from cbcs_course_master a where a.dept_id=? GROUP BY a.sub_name,a.lecture,a.tutorial,a.practical order by trim(a.sub_name)";
             $query = $this->db->query($sql,array($did));
        }else{
            $sql = "select a.* from cbcs_course_master a GROUP BY a.sub_name,a.lecture,a.tutorial,a.practical order by trim(a.sub_name)";
             $query = $this->db->query($sql);
        }


        if ($this->db->affected_rows() > 0) {
           return $query->result();
        } else {
           return false;
        }


    }

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
	
	//==========get subject name
    function get_sub_name($sub_code){
        $sql="SELECT sub_name FROM cbcs_course_master WHERE sub_code='$sub_code'";
        $result=$this->db->query($sql);
        return $result->result();
    }


     function get_offered_sub_list($sy,$sess,$dept)
    {

      $sql = "select a.session_year,a.`session`,a.dept_id,a.course_id,a.branch_id,a.semester,a.sub_code,a.sub_name,a.lecture
,a.tutorial,a.practical,a.credit_hours,a.contact_hours,a.sub_type,a.pre_requisite,a.pre_requisite_subcode,a.sub_category,
a.sub_group,a.criteria,b.part,b.emp_no,
CASE WHEN b.coordinator='1' THEN 'Yes' ELSE 'No' END as marks_up_rt,
c.name as dname,d.name as cname,e.name as bname,
concat_ws(' ',f.first_name,f.middle_name,f.last_name) as fname,a.minstu,a.maxstu
 from cbcs_subject_offered a
inner join cbcs_subject_offered_desc b on b.sub_offered_id=a.id
inner join cbcs_departments c on c.id=a.dept_id and c.`status`='1'
left join cbcs_courses d on d.id=a.course_id and d.`status`='1'
left join cbcs_branches e on e.id=a.branch_id and e.`status`='1'
inner join user_details f on f.id=b.emp_no
where a.session_year=? and a.`session`=? and a.dept_id=?";


        $query = $this->db->query($sql,array($sy,$sess,$dept));
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
