<?php
class New_admission_model extends CI_Model{

private $tabulation='newadmission';
	function __construct()
	{
		// Call the Model constructor
		parent::__construct();
	}

  public function reg_form($id){
    $CI = &get_instance();
    $this->db2 = $CI->load->database($this->tabulation, TRUE);
     $userlist = $this->db2->select('*')->from('reg_form')->where("admn_no LIKE '%$id%'")->get()->result();
    //echo "<pre>";print_r($userlist);exit;
        $i=0;
        foreach ($userlist as $userlist) {
          $cntrowsuser = $this->db->query("SELECT * FROM users where id='$userlist->admn_no'");
           $cntuser= $cntrowsuser->num_rows();
          $cntrowsusedetails = $this->db->query("SELECT * FROM reg_form where admn_no='$userlist->admn_no'");
          $cnt= $cntrowsusedetails->num_rows();
          if($cntuser =='1'){
            if($cnt=='0'){
                 $this->db->insert('reg_form', $userlist);
								 if($this->db->affected_rows() != 1){
										echo "<pre>";
										echo"reg_form :".	$this->db->_error_message();

									}else{
										$i=$i+1;
									}
            }
          }
       }
   echo "<br>reg_form : ".$i;

  }

  public function hs_assigned_student_room($id){
    $CI = &get_instance();
    $this->db2 = $CI->load->database($this->tabulation, TRUE);
     $userlist = $this->db2->select('*')->from('hs_assigned_student_room')->where("admn_no LIKE '%$id%'")->get()->result();
    //echo "<pre>";print_r($userlist);exit;
        $i=0;
        foreach ($userlist as $userlist) {
					unset($userlist->id);
          $cntrowsuser = $this->db->query("SELECT * FROM users where id='$userlist->admn_no'");
           $cntuser= $cntrowsuser->num_rows();
          $cntrowsusedetails = $this->db->query("SELECT * FROM hs_assigned_student_room where admn_no='$userlist->admn_no'");
          $cnt= $cntrowsusedetails->num_rows();
          if($cntuser =='1'){
            if($cnt=='0'){
                 $this->db->insert('hs_assigned_student_room', $userlist);
								 if($this->db->affected_rows() != 1){
										echo "<pre>";
										echo"hs_assigned_student_room :".	$this->db->_error_message();

									}else{
										$i=$i+1;
									}
            }
          }
       }
   echo "<br>hs_assigned_student_room : ".$i;

  }

  /*public function fb_student_feedback($id){
    $CI = &get_instance();
    $this->db2 = $CI->load->database($this->tabulation, TRUE);
     $userlist = $this->db2->select('*')->from('fb_student_feedback')->where("admn_no LIKE '%$id%'")->get()->result();
    //echo "<pre>";print_r($userlist);exit;
        $i=0;
        foreach ($userlist as $userlist) {
          $cntrowsuser = $this->db->query("SELECT * FROM users where id='$userlist->admn_no'");
           $cntuser= $cntrowsuser->num_rows();
          $cntrowsusedetails = $this->db->query("SELECT * FROM fb_student_feedback where admn_no='$userlist->admn_no'");
          $cnt= $cntrowsusedetails->num_rows();
          if($cntuser =='1'){
            if($cnt=='0'){
                 $this->db->insert('fb_student_feedback', $userlist);
                $i=$i+1;
            }
          }
       }
   echo "<br>fb_student_feedback : ".$i;

  }
*/
  public function fb_student_feedback($id){
    $CI = &get_instance();
    $this->db2 = $CI->load->database($this->tabulation, TRUE);
     $userlist = $this->db2->select('*')->from('fb_student_feedback')->where("admn_no LIKE '%$id%'")->get()->result();
    //echo "<pre>";print_r($userlist);exit;
        $i=0;
        foreach ($userlist as $userlist) {
          $cntrowsuser = $this->db->query("SELECT * FROM users where id='$userlist->admn_no'");
           $cntuser= $cntrowsuser->num_rows();
          $cntrowsusedetails = $this->db->query("SELECT * FROM fb_student_feedback where admn_no='$userlist->admn_no'");
          $cnt= $cntrowsusedetails->num_rows();
          if($cntuser =='1'){
            if($cnt=='0'){
                 $this->db->insert('fb_student_feedback', $userlist);
								 if($this->db->affected_rows() != 1){
										echo "<pre>";
										echo"fb_student_feedback :".	$this->db->_error_message();

									}else{
										$i=$i+1;
									}
            }
          }
       }
   echo "<br>fb_student_feedback : ".$i;

  }

  public function stu_enroll_passout($id){
    $CI = &get_instance();
    $this->db2 = $CI->load->database($this->tabulation, TRUE);
     $userlist = $this->db2->select('*')->from('stu_enroll_passout')->where("admn_no LIKE '%$id%'")->get()->result();
    //echo "<pre>";print_r($userlist);exit;
        $i=0;
        foreach ($userlist as $userlist) {
          $cntrowsuser = $this->db->query("SELECT * FROM users where id='$userlist->admn_no'");
           $cntuser= $cntrowsuser->num_rows();
          $cntrowsusedetails = $this->db->query("SELECT * FROM stu_enroll_passout where admn_no='$userlist->admn_no'");
          $cnt= $cntrowsusedetails->num_rows();
          if($cntuser =='1'){
            if($cnt=='0'){
                 $this->db->insert('stu_enroll_passout', $userlist);
								 if($this->db->affected_rows() != 1){
										echo "<pre>";
										echo"stu_enroll_passout :".	$this->db->_error_message();

									}else{
										$i=$i+1;
									}
            }
          }
       }
   echo "<br>stu_enroll_passout : ".$i;

  }


  public function reg_regular_form($id){
    $CI = &get_instance();
    $this->db2 = $CI->load->database($this->tabulation, TRUE);
     $userlist = $this->db2->select('*')->from('reg_regular_form')->where("admn_no LIKE '%$id%'")->get()->result();
    //echo "<pre>";print_r($userlist);exit;
        $i=0;
        foreach ($userlist as $userlist) {
          $cntrowsuser = $this->db->query("SELECT * FROM users where id='$userlist->admn_no'");
           $cntuser= $cntrowsuser->num_rows();
          $cntrowsusedetails = $this->db->query("SELECT * FROM reg_regular_form where admn_no='$userlist->admn_no'");
          $cnt= $cntrowsusedetails->num_rows();
          if($cntuser =='1'){
            if($cnt=='0'){
                $savedata= $this->db->insert('reg_regular_form', $userlist);
								if($this->db->affected_rows() != 1){
									echo "<pre>";
									echo"reg_regular_form :".	$this->db->_error_message();

								}
								if(!empty($savedata)){
									$i=$i+1;
								}

            }
          }
       }
   echo "<br>reg_regular_form : ".$i;

  }

  public function reg_regular_fee_from_from($id){
    $CI = &get_instance();
    $this->db2 = $CI->load->database($this->tabulation, TRUE);
     $userlist = $this->db2->select('*')->from('reg_regular_fee')->where("admn_no LIKE '%$id%'")->get()->result();

	//	 	echo $admin_no=$userlist->admn_no;
//	$reg_regular_from = $this->db2->select('*')->from('reg_regular_from')->where("admn_no , '$admin_no'")->get()->result();
//	echo	$this->db->last_query();
  # echo "<pre>";print_r($userlist);exit;
        $i=0;
        foreach ($userlist as $userlist) {
          $cntrowsuser = $this->db->query("SELECT * FROM users where id='$userlist->admn_no'");
           $cntuser= $cntrowsuser->num_rows();
          $cntrowsusedetails = $this->db->query("SELECT * FROM reg_regular_fee where admn_no='$userlist->admn_no'");
          $cnt= $cntrowsusedetails->num_rows();
          if($cntuser =='1'){
            if($cnt=='0'){
							 unset($userlist->form_id);
							 $admin_no=$userlist->admn_no;

							 //inserting reg_regular_fee data
              $savedata=$this->db->insert('reg_regular_fee', $userlist);
							$lastid=$this->db->insert_id();
							if($this->db->affected_rows() != 1){
								echo "<pre>";
								echo"reg_regular_fee :".	$this->db->_error_message();

							}
							if(!empty($savedata)){
								$i=$i+1;
							}


						$reg_regular_from = $this->db2->select('*')->from('reg_regular_form')->where("admn_no =","$admin_no")->get()->result();
						//	print_r($reg_regular_from);
						foreach($reg_regular_from as $rf){
									$rf->form_id=$lastid;
								//	$i=$i+1;
						}

						//inserting reg_regular_form data
					//	$cnt1 = $this->db->query("SELECT * FROM reg_regular_form where admn_no='$userlist->admn_no'");
					//	$cnt2= $cntrowsusedetails->num_rows();
				//		if($cnt2=='0'){
					 $save_reg_regular_from=$this->db->insert('reg_regular_form', $reg_regular_from[0]);
					 if($this->db->affected_rows() != 1){
						 echo "<pre>";
						 echo"reg_regular_form :".	$this->db->_error_message();

					 }else{
						 	 $i=$i+1;
			//		 }
					}
					 	$reg_from = $this->db2->select('*')->from('reg_form')->where("admn_no =","$admin_no")->get()->result();
						if(!empty($save_reg_regular_from)){
						foreach($reg_from as $rrf){
									$rrf->form_id=$lastid;
						}

					//	$cnt3 = $this->db->query("SELECT * FROM reg_form where admn_no='$userlist->admn_no'");
					//	$cnt4= $cntrowsusedetails->num_rows();
					//	if($cnt4=='0'){
						 $savereg_from=$this->db->insert('reg_form', $reg_from[0]);
						 if($this->db->affected_rows() != 1){
							 echo "<pre>";
							 echo"reg_form :".	$this->db->_error_message();

						 }else{
								 $i=$i+1;
						 }
					// }
					 }
				}
    }
 }
   echo "<br>reg_regular_fee : ".$i;

  }

  public function stu_prep_data($id){
    $CI = &get_instance();
    $this->db2 = $CI->load->database($this->tabulation, TRUE);
     $userlist = $this->db2->select('*')->from('stu_prep_data')->where("admn_no LIKE '%$id%'")->get()->result();
    //echo "<pre>";print_r($userlist);exit;
        $i=0;
        foreach ($userlist as $userlist) {
          $cntrowsuser = $this->db->query("SELECT * FROM users where id='$userlist->admn_no'");
           $cntuser= $cntrowsuser->num_rows();
          $cntrowsusedetails = $this->db->query("SELECT * FROM stu_prep_data where admn_no='$userlist->admn_no'");
          $cnt= $cntrowsusedetails->num_rows();
          if($cntuser =='1'){
            if($cnt=='0'){
                 $this->db->insert('stu_prep_data', $userlist);
								 if($this->db->affected_rows() != 1){
										echo "<pre>";
										echo"stu_prep_data :".	$this->db->_error_message();

									}else{
										$i=$i+1;
									}
            }
          }
       }
   echo "<br>stu_prep_data : ".$i;

  }

  public function stu_prev_certificate($id){
    $CI = &get_instance();
    $this->db2 = $CI->load->database($this->tabulation, TRUE);
     $userlist = $this->db2->select('*')->from('stu_prev_certificate')->where("admn_no LIKE '%$id%'")->get()->result();
    //echo "<pre>";print_r($userlist);exit;
        $i=0;
        foreach ($userlist as $userlist) {
          $cntrowsuser = $this->db->query("SELECT * FROM users where id='$userlist->admn_no'");
           $cntuser= $cntrowsuser->num_rows();
          $cntrowsusedetails = $this->db->query("SELECT * FROM stu_prev_certificate where admn_no='$userlist->admn_no'");
          $cnt= $cntrowsusedetails->num_rows();
          if($cntuser =='1'){
          //  if($cnt=='0' || $cnt=='1' || $cnt=='2'){
                 $this->db->insert('stu_prev_certificate', $userlist);
								 if($this->db->affected_rows() != 1){
										echo "<pre>";
										echo"stu_prev_certificate :".	$this->db->_error_message();

									}else{
										$i=$i+1;
									}
          //  }
          }
       }
   echo "<br>stu_prev_certificate : ".$i;

  }

  public function stu_prev_edu($id){
    $CI = &get_instance();
    $this->db2 = $CI->load->database($this->tabulation, TRUE);
     $userlist = $this->db2->select('*')->from('stu_prev_education')->where("admn_no LIKE '%$id%'")->get()->result();
    //echo "<pre>";print_r($userlist);exit;
        $i=0;
        foreach ($userlist as $userlist) {
          $cntrowsuser = $this->db->query("SELECT * FROM users where id='$userlist->admn_no'");
           $cntuser= $cntrowsuser->num_rows();
          $cntrowsusedetails = $this->db->query("SELECT * FROM stu_prev_education where admn_no='$userlist->admn_no'");
          $cnt= $cntrowsusedetails->num_rows();
          if($cntuser =='1'){
           // if($cnt=='0' || $cnt=='1'){
                 $this->db->insert('stu_prev_education', $userlist);
								 if($this->db->affected_rows() != 1){
										echo "<pre>";
										echo"stu_prev_education :".	$this->db->_error_message();

									}else{
										$i=$i+1;
									}
           // }
          }
       }
   echo "<br>stu_pre_education : ".$i;

  }

  public function stu_admin_fee($id){
    $CI = &get_instance();
    $this->db2 = $CI->load->database($this->tabulation, TRUE);
     $userlist = $this->db2->select('*')->from('stu_admn_fee')->where("admn_no LIKE '%$id%'")->get()->result();
    //echo "<pre>";print_r($userlist);exit;
        $i=0;
        foreach ($userlist as $userlist) {
          $cntrowsuser = $this->db->query("SELECT * FROM users where id='$userlist->admn_no'");
           $cntuser= $cntrowsuser->num_rows();
          $cntrowsusedetails = $this->db->query("SELECT * FROM stu_admn_fee where admn_no='$userlist->admn_no'");
          $cnt= $cntrowsusedetails->num_rows();
          if($cntuser =='1'){
            if($cnt=='0'){
                 $this->db->insert('stu_admn_fee', $userlist);
								 if($this->db->affected_rows() != 1){
										echo "<pre>";
										echo"stu_admn_fee :".	$this->db->_error_message();

									}else{
										$i=$i+1;
									}
            }
          }
       }
   echo "<br>stu_admin_fee : ".$i;

  }

  public function stu_other_details($id){
    $CI = &get_instance();
    $this->db2 = $CI->load->database($this->tabulation, TRUE);
     $userlist = $this->db2->select('*')->from('stu_other_details')->where("admn_no LIKE '%$id%'")->get()->result();
    //echo "<pre>";print_r($userlist);exit;
        $i=0;
        foreach ($userlist as $userlist) {
          $cntrowsuser = $this->db->query("SELECT * FROM users where id='$userlist->admn_no'");
           $cntuser= $cntrowsuser->num_rows();
          $cntrowsusedetails = $this->db->query("SELECT * FROM stu_other_details where admn_no='$userlist->admn_no'");
          $cnt= $cntrowsusedetails->num_rows();
          if($cntuser =='1'){
            if($cnt=='0'){
                 $this->db->insert('stu_other_details', $userlist);
								 if($this->db->affected_rows() != 1){
										echo "<pre>";
										echo"stu_other_details :".	$this->db->_error_message();

									}else{
										$i=$i+1;
									}
            }
          }
       }
   echo "<br>stu_other_details : ".$i;

  }
  public function stu_details($id){
    $CI = &get_instance();
    $this->db2 = $CI->load->database($this->tabulation, TRUE);
     $userlist = $this->db2->select('*')->from('stu_details')->where("admn_no LIKE '%$id%'")->get()->result();
    //echo "<pre>";print_r($userlist);exit;
        $i=0;
        foreach ($userlist as $userlist) {
          $cntrowsuser = $this->db->query("SELECT * FROM users where id='$userlist->admn_no'");
           $cntuser= $cntrowsuser->num_rows();
          $cntrowsusedetails = $this->db->query("SELECT * FROM stu_details where admn_no='$userlist->admn_no'");
          $cnt= $cntrowsusedetails->num_rows();
          if($cntuser =='1'){
            if($cnt=='0'){
                 $this->db->insert('stu_details', $userlist);
								 if($this->db->affected_rows() != 1){
										echo "<pre>";
										echo"stu_details :".	$this->db->_error_message();

									}else{
										$i=$i+1;
									}
            }
          }
       }
   echo "<br>stu_details : ".$i;

  }

  public function stu_acadmic($id){
    $CI = &get_instance();
    $this->db2 = $CI->load->database($this->tabulation, TRUE);
     $userlist = $this->db2->select('*')->from('stu_academic')->where("admn_no LIKE '%$id%'")->get()->result();
  #  echo "<pre>";print_r($userlist);exit;
        $i=0;
        foreach ($userlist as $userlist) {
          $cntrowsuser = $this->db->query("SELECT * FROM users where id='$userlist->admn_no'");
           $cntuser= $cntrowsuser->num_rows();
          $cntrowsusedetails = $this->db->query("SELECT * FROM stu_academic where admn_no='$userlist->admn_no'");
          $cnt= $cntrowsusedetails->num_rows();
          if($cntuser =='1'){
            if($cnt=='0'){
                 $this->db->insert('stu_academic', $userlist);
								 if($this->db->affected_rows() != 1){
										echo "<pre>";
										echo"stu_academic :".	$this->db->_error_message();

									}else{
										$i=$i+1;
									}
            }
          }
       }
   echo "<br>stu_acadmic : ".$i;

  }

  public function populateUsersAddress($id){
    $CI = &get_instance();
    $this->db2 = $CI->load->database($this->tabulation, TRUE);
     $userlist = $this->db2->select('*')->from('user_address')->where("id LIKE '%$id%'")->get()->result();

        $i=0;
        foreach ($userlist as $userlist) {
          $cntrowsuser = $this->db->query("SELECT * FROM users where id='$userlist->id'");
           $cntuser= $cntrowsuser->num_rows();

          $cntrowsusedetails = $this->db->query("SELECT * FROM user_address where id='$userlist->id'");

         $cnt= $cntrowsusedetails->num_rows();


          if($cntuser =='1'){
            if($cnt=='0' || $cnt=='1' || $cnt=='2'){
			$line1=	preg_replace('\'', '"', '', $userlist->line1);
                $sql = "insert into user_address (id,line1,line2,city,state,pincode,country,contact_no,type)
                values ('$userlist->id','$line1','$userlist->line2','$userlist->city','$userlist->state',
                '$userlist->pincode','$userlist->country','$userlist->contact_no','$userlist->type')";
                $this->db->query($sql);
								if($this->db->affected_rows() != 1){
									 echo "<pre>";
									 echo"user_address :".	$this->db->_error_message();

								 }else{
									 $i=$i+1;
								 }
            }

          }

        }
   echo "<br>Users Address : ".$i;

  }

  public function populateUsersotherDetails($id){
    $CI = &get_instance();
    $this->db2 = $CI->load->database($this->tabulation, TRUE);
     $userlist = $this->db2->select('*')->from('user_other_details')->where("id LIKE '%$id%'")->get()->result();
        $i=0;
        foreach ($userlist as $userlist) {
          $cntrowsuser = $this->db->query("SELECT * FROM users where id='$userlist->id'");
           $cntuser= $cntrowsuser->num_rows();
          $cntrowsusedetails = $this->db->query("SELECT * FROM user_other_details where id='$userlist->id'");
         $cnt= $cntrowsusedetails->num_rows();
        if($cntuser =='1'){
            if($cnt=='0'){
                 $this->db->insert('user_other_details', $userlist);
								 if($this->db->affected_rows() != 1){
										echo "<pre>";
										echo"user_other_details :".	$this->db->_error_message();
									}else{
										$i=$i+1;
									}
            }
          }
        }
   echo "<br>Users Other Details : ".$i;
  }
  public function populateUsersDetails($id){
    $CI = &get_instance();
    $this->db2 = $CI->load->database($this->tabulation, TRUE);
     $userlist = $this->db2->select('*')->from('user_details')->where("id LIKE '%$id%'")->get()->result();
        $i=0;
        foreach ($userlist as $userlist) {
          $cntrowsuser = $this->db->query("SELECT * FROM users where id='$userlist->id'");
           $cntuser= $cntrowsuser->num_rows();
          $cntrowsusedetails = $this->db->query("SELECT * FROM user_details where id='$userlist->id'");
         $cnt= $cntrowsusedetails->num_rows();
          if($cntuser =='1'){
            if($cnt=='0'){
                $sql = "insert into user_details (id,salutation,first_name,middle_name,last_name,sex,category,allocated_category,
                dob,email,photopath,marital_status,physically_challenged,dept_id)
                values ('$userlist->id','$userlist->salutation','$userlist->first_name','$userlist->middle_name','$userlist->last_name',
                '$userlist->sex','$userlist->category','$userlist->allocated_category','$userlist->dob','$userlist->email','$userlist->photopath',
                '$userlist->marital_status','$userlist->physically_challenged','$userlist->dept_id')";
                $this->db->query($sql);
								if($this->db->affected_rows() != 1){
									 echo "<pre>";
									 echo"user_details :".	$this->db->_error_message();

								 }else{
									 $i=$i+1;
								 }

            }

          }
          // code...
        }
   echo "<br>Users Details : ".$i;
    //     echo  $this->db->last_query();
      //
  }
public function populateUsers($id){
  $CI = &get_instance();
  $this->db2 = $CI->load->database($this->tabulation, TRUE);
   $userlist = $this->db2->select('*')->from('users')->where("id LIKE '%$id%'")->get()->result();
  //echo "<pre>";
  //print_r($userlist);exit;
      $i=0;
      foreach ($userlist as $userlist) {
        //  echo "user-id".$userlist['id'];exit;
    #  echo  $userid=$userlist->id; exit;

        $cntrows = $this->db->query("SELECT * FROM users where id='$userlist->id'");

        $cnt= $cntrows->num_rows();
      #   echo  $this->db->last_query(); exit;
        if($cnt =='0'){

           $sql = "insert into users (id,password,auth_id,status,remark)
           values ('$userlist->id','$userlist->password','$userlist->auth_id','$userlist->status','$userlist->remark')";
           $this->db->query($sql);
					 if($this->db->affected_rows() != 1){
							echo "<pre>";
							echo"users :".	$this->db->_error_message();

						}else{
							$i=$i+1;
						}

        //   echo "<br>Users Added : ".$sql;
        }
        // code...
      }
 echo "<br>Users Added : ".$i;
  //     echo  $this->db->last_query();
    //
}
 }
	  ?>
