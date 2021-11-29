<?php
/**
 *
 */
class Form_fetch_model extends CI_Model
{
  var $reg_regular_form='reg_regular_form';
  var $reg_regular_fee='reg_regular_fee';
  var $stu_details='stu_details';
  var $user_details='user_details';
  var $reg_other_form='reg_other_form';
  var $reg_summer_form='reg_summer_form';
  var $reg_idle_form='reg_idle_form';

  function __construct()
  {
    parent::__construct();
  }

  function checkRegular($sess,$sess_year){
    //$query=$this->db->query("SELECT form_id FROM reg_regular_form WHERE session=".$sess." AND session_year=".$sess_year." AND hod_status='0'");
    $dep=$this->session->userdata('dept_id');
    $this->db->select('admn_no');
    $this->db->from('reg_regular_form');
    $this->db->join('user_details','reg_regular_form.admn_no = user_details.id');
    $this->db->where('session',$sess);
    $this->db->where('session_year',$sess_year);
    $this->db->where('user_details.dept_id',$dep);
	$this->db->where('reg_regular_form.hod_status','0');
	
    $query=$this->db->get();
	//	echo $this->db->last_query();
    if($query->num_rows() > 0) return 1;
    return 0;
  }
  function checkSummer($sess,$sess_year){
    //$query=$this->db->query("SELECT form_id FROM reg_summer_form WHERE session=".$sess." AND session_year=".$sess_year." AND hod_status='0'");
    $dep=$this->session->userdata('dept_id');
  //   $query=$this->db->get_where($this->reg_summer_form,array('session'=>$sess, 'session_year'=>$sess_year,  'hod_status'=>'0'));
	 
	 $this->db->select('admn_no');
    $this->db->from('reg_summer_form');
    $this->db->join('user_details','reg_summer_form.admn_no = user_details.id');
    $this->db->where('session',$sess);
    $this->db->where('session_year',$sess_year);
    $this->db->where('user_details.dept_id',$dep);
	$this->db->where('reg_summer_form.hod_status','0');	
    $query=$this->db->get();
	
    if($query->num_rows() > 0) return 1;
    return 0;
  }
 /* function checkOther($sess,$sess_year){
    //$query=$this->db->query("SELECT form_id FROM reg_other_form WHERE session_year=".$sess_year." AND hod_status='0'");
    $dep=$this->session->userdata('dept_id');
  //  $query=$this->db->get_where($this->reg_other_form,array('session'=>$sess,'session_year'=>$sess_year,  'hod_status'=>'0'));
   
	 $this->db->select('admn_no');
    $this->db->from('reg_other_form');
    $this->db->join('user_details','reg_other_form.admn_no = user_details.id');
    $this->db->where('session',$sess);
    $this->db->where('session_year',$sess_year);
    $this->db->where('user_details.dept_id',$dep);
	$this->db->where('reg_other_form.hod_status','0');	
    $query=$this->db->get();
  
    if($query->num_rows() > 0) return 1;
    return 0;
  }*/
  function checkOther($sess,$sess_year){
      $dep=$this->session->userdata('dept_id');
      $sql = "(select a.* from reg_other_form a
inner join user_details b on a.admn_no=b.id
where  a.`session`=? and a.session_year=?
/*and a.`type`='R'*/ and a.hod_status='0' and b.dept_id=?)
union
(select a.* from reg_exam_rc_form a
inner join user_details b on a.admn_no=b.id
where  a.`session`=? and a.session_year=?
/*and a.`type`='R'*/ and a.hod_status='0' and b.dept_id=?)";

        $query = $this->db->query($sql,array($sess,$sess_year,$dep,$sess,$sess_year,$dep));

       //echo $this->db->last_query(); die();
        if ($this->db->affected_rows() > 0) {
            return 1;
        } else {
            return 0;
        }
      
      
  }
  function checkIdle($sess,$sess_year){
    //$query=$this->db->query("SELECT form_id FROM reg_idle_form WHERE session=".$sess." AND session_year=".$sess_year." AND hod_status='0'");
    $dep=$this->session->userdata('dept_id');
    //$query=$this->db->get_where($this->reg_idle_form,array('session'=>$sess, 'session_year'=>$sess_year,  'hod_status'=>'0'));
	
		 $this->db->select('admn_no');
    $this->db->from('reg_idle_form');
    $this->db->join('user_details','reg_idle_form.admn_no = user_details.id');
    $this->db->where('session',$sess);
    $this->db->where('session_year',$sess_year);
    $this->db->where('user_details.dept_id',$dep);
	$this->db->where('reg_idle_form.hod_status','0');	
    $query=$this->db->get();
	
    if($query->num_rows() > 0) return 1;
    return 0;
  }

  function getRegularForms($session,$session_year){
    //redirect("a".$session);
    $dep=$this->session->userdata('dept_id');
    // var_dump($session);
    // var_dump($session_year);
    // var_dump($branch);
    //$query=$this->db->get_where($this->reg_regular_form,array('session'=>$session, 'session_year'=>$session_year, 'branch_id'=>$dep));
//$query= $this->db->query("select * from ".$this->reg_regular_form." as sf, stu_details as sd, user_details as ud, ".$this->reg_regular_fee." as srf where dept_id='".$dep."' and srf.form_id = sf.form_id and sd.admn_no = sf.admn_no and ud.id = sf.admn_no and sf.session='".$session."' and sf.session_year='".$session_year."'");
    //$query=$this->db->get_where($this->reg_regular_form as sf,$this->reg_regular_fee as srf,$this->stu_details as sd, $this->user_details as ud,array('dept_id'=>$dep,'srf.form_id'=>'sf.form_id', 'sd.admn_no'=>'sf.admn_no', 'ud.id'=>'sf.admn_no', 'sf.session'=>$session, 'sf.session_year'=>$session_year));
    $this->db->select('*');
    $this->db->from('reg_regular_form');
    $this->db->join('user_details','reg_regular_form.admn_no = user_details.id');
    $this->db->where('session',$session);
    $this->db->where('session_year',$session_year);
    $this->db->where('user_details.dept_id',$dep);
    $query=$this->db->get();
    return $query->result();
  }
  function getSummerForms($session,$session_year){
    var_dump($session);
    $dep=$this->session->userdata('dept_id');
    $this->db->select('*');
    $this->db->from('reg_summer_form');
    $this->db->join('user_details','reg_summer_form.admn_no = user_details.id');
    $this->db->where('session',$session);
    $this->db->where('session_year',$session_year);
    $this->db->where('user_details.dept_id',$dep);
    $query=$this->db->get();
    return $query->result();
  }
 /* function getOtherForms($session,$session_year){
    $dep=$this->session->userdata('dept_id');
    $this->db->select('*');
    $this->db->from('reg_other_form');
    $this->db->join('user_details','reg_other_form.admn_no = user_details.id');
    $this->db->where('session',$session);
    $this->db->where('session_year',$session_year);
     $this->db->where('user_details.dept_id',$dep);
    $query=$this->db->get();
    return $query->result();
  }*/
  function getOtherForms($session,$session_year){
      $dep=$this->session->userdata('dept_id');
      $sql = "(select a.*,b.* from reg_other_form a
inner join user_details b on a.admn_no=b.id
where  a.`session`=? and a.session_year=?
/*and a.`type`='R' and a.hod_status='0'*/ and b.dept_id=? and course_id<>'jrf')
union
(select a.*,b.* from reg_exam_rc_form a
inner join user_details b on a.admn_no=b.id
where  a.`session`=? and a.session_year=?
/*and a.`type`='R' and a.hod_status='0' */and b.dept_id=? and course_id<>'jrf')";

        $query = $this->db->query($sql,array($session,$session_year,$dep,$session,$session_year,$dep));

       //echo $this->db->last_query(); die();
        if ($this->db->affected_rows() >= 0) {
            return $query->result();
        } else {
            return false;
        }
  }
  function getIdleForms($session,$session_year){
    $dep=$this->session->userdata('dept_id');
    $this->db->select('*');
    $this->db->from('reg_idle_form');
    $this->db->join('user_details','reg_idle_form.admn_no = user_details.id');
    $this->db->where('session',$session);
    $this->db->where('session_year',$session_year);
    $this->db->where('user_details.dept_id',$dep);
    $query=$this->db->get();
    return $query->result();
  }
  // function getSessions(){
  //   $tables = array();
  //   $tables[] = 'reg_other_form';
  //   $tables[] = 'reg_idle_form';
  //   $tables[] = 'reg_summer_form';
  //   $tables[] = 'reg_regular_form';
  //   $subqueries = array();
  //   foreach ($tables as $t) {
  //   // Change these queries accordingly:
  //       $this->db
  //           ->select('session_year')
  //           ->from($t);
  //       $subqueries[] = '('.$this->db->_compile_select().')';
  //   }
  //   //$sql = implode(' UNION ', $subqueries);
  //   // or if necessary:
  //   $sql = implode(' UNION DISTINCT ', $subqueries);
  //   $result = $this->db->query($sql)->result();
  //   return $result;
  // }
}
?>
