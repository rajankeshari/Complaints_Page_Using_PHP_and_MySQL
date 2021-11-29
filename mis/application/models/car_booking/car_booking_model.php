<?php

/**
 * Author: Anuj
*/
class Car_booking_model extends CI_Model {
    
    

    function __construct() {
        parent::__construct();
    }

    function getEmpDetails($id)
    {
    	$q="select concat(ud.salutation,' ',ud.first_name,' ',ud.middle_name,' ',ud.last_name ) as name,(select ucase(A.name) from designations A where A.id=ebd.designation) as desig,(select ucase(B.name) from departments B where B.id=ud.dept_id) as dept from user_details ud inner join emp_basic_details ebd on ud.id=ebd.emp_no where id=?";
    	if($query=$this->db->query($q,$id)){
    		if($query->num_rows()>0){
    			return $query->row();
    		}
    		else{
    			return false;
    		}
    	}
    	else{
    		return false;
    	}
    }

    function getDeptId($id){
        $q="select A.dept_id from user_details A where A.id= ?";
        if($query=$this->db->query($q,$id)){
            if($query->num_rows()>0){
                return $query->result();
            }
            else{
                return false;
            }
        }
        else{
            return false;
        }
    }

    function gethodbydept($id)
    {
        $q="select A.id as id from user_details A inner join user_auth_types B on A.id=B.id where auth_id = 'hod' and dept_id = ?";
        if($query=$this->db->query($q,$id)){
            return $query->result();
        }
        else{
            return false;
        }
    }

    function save_personal($data)
    {
       if($this->db->insert('vehicle_booking',$data))
       {
            $insertId = $this->db->insert_id();
            return  $insertId;  
       }
       else
       {
            echo"Gello1";die();
			return false;
       }
    }

    function save_other($data)
    {
        if($this->db->insert('vehicle_booking',$data))
        {
            $insertId = $this->db->insert_id();
            return  $insertId;  
       }
       else
       {
            echo"Gello1";die();
			return false;
       }
       
    }

    function save_official($data)
    {
        $this->db->where($data);
        if($query1=$this->db->get('vehicle_booking')){
            //echo $this->db->last_query();die();
            if($query1->num_rows()>0){
                //echo"exist";die();
                return false;   
            }
            else
            {
                if($this->db->insert('vehicle_booking',$data))
                {
                    $insertId = $this->db->insert_id();
                    return  $insertId;
                    // $this->db->where($data);
                    // if($query=$this->db->get('vehicle_booking'))
           //          {
                    //  if($query->num_rows()>0)
           //              {
                    //      $result=$query->row();
                    //      return $result->id;
                    //  }
                    //  else
           //              {
                    //      echo"Gello";die();
                    //      return false;
                    //  }
                    // }
                }
                else
                {
                    //echo"Gello1";die();
                    return false;
                }
            }
        }
        else{
            //echo "Gello2";die();
            return false;
        }
    }

    function getNoticeResult($id){
        if(isset($id)){
            $this->db->where('id',$id);
        }
    	if($query=$this->db->get('vehicle_booking')){
    		if($query->num_rows()>0){
    			return $query->row();
    		}
    		else{
    			return false;
    		}
    	}
    }
    function getNoticeEmployeeId($auth_type){
			$myquery="select * from user_auth_types where auth_id=?";
			$query=$this->db->query($myquery,$auth_type);
			//echo $this->db->last_query();die();
			return $query->result();
	}

    


    function getDriverName(){
        $q="select ud.id,concat(ud.first_name,' ',ud.middle_name,' ',ud.last_name,' ','(Permanent)') as name from user_details ud right join (select * from emp_basic_details A where A.designation='dvr' or A.designation='sdvr') as ebd on ebd.emp_no=ud.id";
        $darr = array();
        $fll=0;
        $pdriver=array();
        if($query=$this->db->query($q)){
            if($query->num_rows()>0){
                  $pdriver=$query->result();
                  $fll++;
            }
        }
        $tdriver=array();
        $q="select cd.d_cid as id,concat(cd.d_name,' ','(Contractual)') as name from contract_driver cd where cd.d_c_to >= CURDATE() and cd.d_c_from <= CURDATE()";
        if($query=$this->db->query($q)){
            if($query->num_rows()>0){
                    $tdriver= $query->result();
                  $fll++;
            }
        }
        $darr = array_merge($pdriver,$tdriver);
        if($fll = 0)
            return false;
        else
            return $darr;
    }

    function getDriverNameById($idd){
        $q="select concat(ud.first_name,' ',ud.middle_name,' ',ud.last_name) as name from user_details ud where ud.id= ? limit 1";
        if($query=$this->db->query($q,$idd))
        {
            if($query->num_rows()>0)
            {
                $result=$query->row();
                return $result->name;
               // var_dump($result);die();
            }
            else
            {
                $q1='select cd.d_name as name from contract_driver cd where cd.d_cid=? limit 1';

                if($query1=$this->db->query($q1,$idd))
                {
                    if($query1->num_rows()>0)
                        {
                            $result= $query1->row();
                            return $result->name;
                        }
                    else
                        return false;
                }   
            }
        }
    }

    function getUsersByDeptAuth2($dept = 'all',$auth = 'all',$req_emp_id='')
    {        
    
     
        /*$query = $this->db->select('user_details.id, salutation, first_name, middle_name, last_name, departments.name as dept_name,auth_types.type')->from('user_details')->join('departments','user_details.dept_id = departments.id');   
        $x='mm';                                                     
        if($auth != 'all'){
                    if (strpos($auth,',')) { 
                        if($req_emp_id==""  || $req_emp_id==NULL)
                             $query->join('user_auth_types','user_details.id = user_auth_types.id and user_auth_types.auth_id in ('.$auth.')     and user_auth_types.id != "'.$x.'" ')->join('auth_types','auth_types.id = user_auth_types.auth_id');
                        else
                             $query->join('user_auth_types','user_details.id = user_auth_types.id and user_auth_types.auth_id in ('.$auth.')     and user_auth_types.id != "'.$x.'"  and user_auth_types.id != "'.$req_emp_id.'"')->join('auth_types','auth_types.id = user_auth_types.auth_id');
                    }else{
                         if($req_emp_id==""  || $req_emp_id==NULL)
                             $query->join('user_auth_types','user_details.id = user_auth_types.id and user_auth_types.auth_id="'.$auth.'"  and user_auth_types.id != "'.$x.'"' )->join('auth_types','auth_types.id = user_auth_types.auth_id');
                         else
                             $query->join('user_auth_types','user_details.id = user_auth_types.id and user_auth_types.auth_id="'.$auth.'"  and user_auth_types.id != "'.$x.'"  and user_auth_types.id != "'.$req_emp_id.'"' )->join('auth_types','auth_types.id = user_auth_types.auth_id');
                    }
                }
        if($dept != 'all')  $query->where('user_details.dept_id',$dept);                
                $query->order_by("user_details.first_name"); */


$query = $this->db->select('user_details.id, salutation, first_name, middle_name, last_name, departments.name as dept_name,auth_types.type')->from('user_details')->join('departments','user_details.dept_id = departments.id');   
        $x='mm';                                                     
        if($auth != 'all'){
                    if (strpos($auth,',')) { 
                        if($req_emp_id==""  || $req_emp_id==NULL){
                             $query->join('user_auth_types','user_details.id = user_auth_types.id and user_auth_types.auth_id in ('.$auth.')     and user_auth_types.id != "'.$x.'" ')->join('auth_types','auth_types.id = user_auth_types.auth_id');
					     $where_clause=" select a.assign_by from delegated_power a where  a.status='1'";	 
						  $query->where("user_details.id NOT IN ($where_clause)", NULL, FALSE);
						}
							 	
                        else{
                             $query->join('user_auth_types','user_details.id = user_auth_types.id and user_auth_types.auth_id in ('.$auth.')     and user_auth_types.id != "'.$x.'"  and user_auth_types.id != "'.$req_emp_id.'"')->join('auth_types','auth_types.id = user_auth_types.auth_id');
						  $where_clause=" select a.assign_by from delegated_power a where  a.status='1'";	 
						$query->where("user_details.id NOT IN ($where_clause)", NULL, FALSE);
						}
                    }else{
                         if($req_emp_id==""  || $req_emp_id==NULL){
                             $query->join('user_auth_types','user_details.id = user_auth_types.id and user_auth_types.auth_id="'.$auth.'"  and user_auth_types.id != "'.$x.'"' )->join('auth_types','auth_types.id = user_auth_types.auth_id');
						  $where_clause=" select a.assign_by from delegated_power a where  a.status='1'";	 
						  $query->where("user_details.id NOT IN ($where_clause)", NULL, FALSE);
                         }else{
                             $query->join('user_auth_types','user_details.id = user_auth_types.id and user_auth_types.auth_id="'.$auth.'"  and user_auth_types.id != "'.$x.'"  and user_auth_types.id != "'.$req_emp_id.'"' )->join('auth_types','auth_types.id = user_auth_types.auth_id');
						  $where_clause=" select a.assign_by from delegated_power a where  a.status='1'";	 
						 $query->where("user_details.id NOT IN ($where_clause)", NULL, FALSE);
					}
                    }
                }
        if($dept != 'all')  $query->where('user_details.dept_id',$dept);                
                $query->order_by("user_details.first_name");       				
                 
               
 		
        return $query->get()->result();
    }



    function non_official_approve($data,$id){
        /* $this->db->set($data);
        $this->db->where('id',$id);
        if($this->db->update('vehicle_booking')){
            return true;
        }
        else{
            return false;
        } */
		
		return $this->db
                ->where('id', $id)
                ->update('vehicle_booking', $data);
    }

    function getAllBooking($status){

        $q="select * from vehicle_booking vb where vb.status=? order by vb.date desc";
        if($query=$this->db->query($q,$status)){
            if($query->num_rows()>0){
                return $query->result();
            }
            else{
                return false;
            }
        }
    }

    function getBookingsofDate($d){
        $q="select * from vehicle_booking vb where vb.date=? and vb.status=1";
          if($query=$this->db->query($q,$d)){
            if($query->num_rows()>0){
                // echo"<pre>";
                // print_r($query->result());
                // exit;
                return $query->result();
            }
            else{
                return false;
            }
        }
    }

    function getAllBookingforhod($status){
        $q="select * from vehicle_booking vb where vb.status_hod=? and vb.hod_id=? order by vb.date desc";
        $id=$this->session->userdata('id');
        if($query=$this->db->query($q,array($status,$id))){
            if($query->num_rows()>0){
                return $query->result();
            }
            else{
                return false;
            }
        }
    }

    function getAllBookingfordir($status){
        $q="select * from vehicle_booking vb where vb.status_dir=? and vb.dir_id=? order by vb.date desc";
        $id=$this->session->userdata('id');
        if($query=$this->db->query($q,array($status,$id))){
            if($query->num_rows()>0){
                return $query->result();
            }
            else{
                return false;
            }
        }
    }

    function get_holidays()
    {
        $q="select * from official_holidays";
        if($query=$this->db->query($q))
        {
            return $query->result();
        }
        else
            return false;
    }

    function getContact($id){
        $q='select uod.mobile_no from user_other_details uod where uod.id=? limit 1';
        if($query=$this->db->query($q,$id))
        {
            if($query->num_rows()>0)
            {
                $result=$query->row();
                return $result->mobile_no;
                //var_dump($result);die();
            }
            else
            {
                $q1='select cd.d_mob from contract_driver cd where cd.d_cid=? limit 1';
                if($query1=$this->db->query($q1,$id))
                {
                    if($query1->num_rows()>0)
                        {
                            $result= $query1->row();
                            return $result->d_mob;
                        }
                    else
                        return false;
                }   
            }
        }

    }

    function getBookingDetailsById($id){
        $q="select vb.* from vehicle_booking vb where vb.id=? limit 1";
        if($query=$this->db->query($q,$id)){
            if($query->num_rows()>0){
                //$result=$query->row();
                return $query->row();
               // var_dump($result);die();
            }
            else{
                return false;
            }
        }
    }

    function getBookingDetails_id($data){
        $q="select * from vehicle_booking vb where vb.emp_no=?";
        if(isset($data['status'])){
            $q.=" and vb.status=?";
        }

        if(isset($data['dfrom'])){
            $q.=' and vb.date >= ?';
        }

        if(isset($data['dto'])){
            $q.=' and vb.date <= ?';
        }


        if($query=$this->db->query($q,array($data['id'],$data['status'],$data['dfrom'],$data['dto']))){
            if($query->num_rows()){
                return $query->result();
            }
            else{
                return false;
            }
        }
        else{
            return false;
        }
    }

    function getBookingDetails($data){


        $q="select * from vehicle_booking vb where 1=1";
        if(isset($data['status'])){
            $q.=" and vb.status=?";
        }

        if(isset($data['dfrom'])){
            $q.=' and vb.date >= ?';
        }

        if(isset($data['dto'])){
            $q.=' and vb.date <= ?';
        }

        if($query=$this->db->query($q,array($data['status'],$data['dfrom'],$data['dto']))){
            if($query->num_rows()){
                return $query->result();
            }
            else{
                return false;
            }
        }
        else{
            return false;
        }
    }
    
    function viewBookingStatus($id){
        $q="select * from vehicle_booking where emp_no=?";
        if($query=$this->db->query($q,$id)){
            if($query->num_rows()>0){
                return $query->result();
            }
            else{
                return false;
            }
        }
        else{
            return false;
        }
    }

    function viewBookingCarSup(){
        $q="select * from vehicle_booking where booked_self=0";
        if($query=$this->db->query($q)){
            if($query->num_rows()>0){
                return $query->result();
            }
            else{
                return false;
            }
        }
        else{
            return false;
        }
    }


    function get_booking_details($b_id){
        $q="select vb.*,ud.mobile_no from vehicle_booking vb left join user_other_details ud on ud.id=vb.emp_no where vb.id=?";
        if($query=$this->db->query($q,$b_id)){
            if($query->num_rows()>0){
                return $query->row();
            }
            else{
                return false;
            }
        }
        else{
            return false;
        }
    }

    function getIndBookingDetails($id){
        $q="select * from vehicle_booking vb where vb.id=?";
        if($query=$this->db->query($q,$id)){
            if($query->num_rows()>0){
                return $query->row();
            }
            else{
                return false;
            }
        }
        else{
            return false;
        }

    }

    function avail($data){
        $cond=array('id'=>$data['id']);
        $set=array('status'=>3,'charge'=>$data['amount']);
        $this->db->set($set);
        $this->db->where($cond);
        if($this->db->update('vehicle_booking')){
            return true;
        }
        else{
            return false;
        }
    }

     function not_avail($data)
     {
        $cond=array('id'=>$data['id']);
        $set=array('status'=>4,'charge'=>0);
        $this->db->set($set);
        $this->db->where($cond);
        if($this->db->update('vehicle_booking')){
            return true;
        }
        else{
            return false;
        }
     }

    

    function getBIDWithEmp(){
        $q="select vb.id,concat(ud.salutation,' ',ud.first_name,' ',ud.middle_name,' ',ud.last_name) as name from vehicle_booking vb left join user_details ud on ud.id=vb.emp_no where vb.`status`=1";
        if($query=$this->db->query($q)){
            if($query->num_rows()>0){
                return $query->result();
            }
            else{
                return false;
            }
        }
        else{
            return false;
        }
    }
    
     function add_tempdriver($post)
    {
         return $this->db->insert( 'contract_driver', $post);
    }

    function getAllContractDrivers(){
        $q="select * from contract_driver";
        if($query=$this->db->query($q)){
            if($query->num_rows()>0){
                return $query->result();
            }
            else{
                return false;
            }
        }
    }

    function getContractDriver($id){ 
        if(isset($id)){
            $this->db->where('d_cid',$id);
        }
        if($query=$this->db->get('contract_driver')){
            if($query->num_rows()>0){
                return $query->row();
            }
            else{
                return false;
            }
        }
    }

    public function update_tempdriver($array)
    {
        return $this->db
                ->where('d_cid', $array['d_cid'])
                ->update('contract_driver', $array);
    }

    //Booking status for employee
    function getBStatus(){

    }


    public function showAlldest(){
        //$this->db->order_by('created_at', 'desc');
        $query = $this->db->get('vb_destination');
        if($query->num_rows() > 0){
            return $query->result();
        }else{
            return false;
        }
    }

    public function adddest(){
        $field = array(
            'dest_name'=>$this->input->post('txtdestName'),
            'status'=>$this->input->post('txtstatus'),
            'added_by'=>$this->session->userdata('id'),
            'add_time'=>mdate("%Y-%m-%d %H:%i:%s"),
            );
        $this->db->insert('vb_destination', $field);
        if($this->db->affected_rows() > 0){
            return true;
        }else{
            return false;
        }
    }

    public function getActiveDestinations()
    {
        $q="select * from vb_destination vbd where vbd.status=1 order by vbd.id";
        if($query=$this->db->query($q)){
            if($query->num_rows()>0){
                return $query->result();
            }
            else{
                return false;
            }
        }
        else{
            return false;
        }
    }

    public function getActiveVehicle(){
        $q="select registration_no, concat(registration_no,' ',vehicle_type) as vehicle from vb_vehicles where status=1";
        if($query=$this->db->query($q)){
            if($query->num_rows()>0){
                return $query->result();
            }
            else{
                return false;
            }
        }
        else{
            return false;
        }
    }

    public function getActiveVehicleType(){
        $q="select distinct vehicle_type from vb_vehicles where status=1";
        if($query=$this->db->query($q)){
            if($query->num_rows()>0){
                return $query->result();
            }
            else{
                return false;
            }
        }
        else{
            return false;
        }
    }

    public function getActiveVehicleTypeOfficial(){
        $q="select distinct vehicle_type from vb_vehicles where status=1 and used_for<>1";
        if($query=$this->db->query($q)){
            if($query->num_rows()>0){
                return $query->result();
            }
            else{
                return false;
            }
        }
        else{
            return false;
        }
    }

     public function getActiveVehicleTypePersonal(){
        $q="select distinct vehicle_type from vb_vehicles where status=1 and used_for<>2";
        if($query=$this->db->query($q)){
            if($query->num_rows()>0){
                return $query->result();
            }
            else{
                return false;
            }
        }
        else{
            return false;
        }
    }

    public function editdest(){
        $id = $this->input->get('id');
        $this->db->where('id', $id);
        $query = $this->db->get('vb_destination');
        if($query->num_rows() > 0){
            return $query->row();
        }else{
            return false;
        }
    }

    public function updatedest(){
        $id = $this->input->post('txtId3');
        $field = array(
        'dest_name'=>$this->input->post('txtdestName'),
        'status'=>$this->input->post('txtstatus'),
        'added_by'=>$this->session->userdata('id'),
        'add_time'=>mdate("%Y-%m-%d %H:%i:%s"),
        );
    
         return $this->db
                ->where('id', $id)
                ->update('vb_destination', $field);

        // $this->db->where('id', $id);
        // $this->db->update('vb_destination', );
        // if($this->db->affected_rows() > 0){
        //     return true;
        // }else{
        //     return false;
        // }
    }



    // function deletedest(){
    //     $id = $this->input->get('id');
    //     $this->db->where('id', $id);
    //     $this->db->delete('vb_destination');
    //     if($this->db->affected_rows() > 0){
    //         return true;
    //     }else{
    //         return false;
    //     }
    // }

    public function showAllvehicle(){
        //$this->db->order_by('created_at', 'desc');
        $query = $this->db->get('vb_vehicles');
        if($query->num_rows() > 0){
            return $query->result();
        }else{
            return false;
        }
    }

    public function addvehicle($dat){
        $field = array(
            'vehicle_type'=>$this->input->post('txtVType'),
            'registration_no'=>$this->input->post('txtReg'),
            'used_for'=>$this->input->post('usedfor'),
            'status'=>$this->input->post('txtstatus'),
            'added_by'=>$this->session->userdata('id'),
            'add_time'=>mdate("%Y-%m-%d %H:%i:%s"),
            );
        
        // print_r($field);
        $this->db->insert('vb_vehicles', $field);
        if($this->db->affected_rows() > 0){
            return true;
        }else{
            return false;
        }
        // return $this->db->insert( 'vb_vehicles', $field);

    }

    // public function getActiveDestinations()
    // {
    //     $q="select * from vb_vehicles vbd where vbd.status=1 order by vbd.dest_name";
    //     if($query=$this->db->query($q)){
    //         if($query->num_rows()>0){
    //             return $query->result();
    //         }
    //         else{
    //             return false;
    //         }
    //     }
    //     else{
    //         return false;
    //     }
    // }

    public function editvehicle(){
        $id = $this->input->get('id');
        $this->db->where('id', $id);
        $query = $this->db->get('vb_vehicles');
        if($query->num_rows() > 0){
            return $query->row();
        }else{
            return false;
        }
    }

    public function updatevehicle(){
        $id = $this->input->post('txtId4');
        $field = array(
        'vehicle_type'=>$this->input->post('txtVType'),
        'registration_no'=>$this->input->post('txtReg'),
        'used_for'=>$this->input->post('usedfor'),
        'status'=>$this->input->post('txtstatus'),
        'added_by'=>$this->session->userdata('id'),
        'add_time'=>mdate("%Y-%m-%d %H:%i:%s"),
        );
    
         return $this->db
                ->where('id', $id)
                ->update('vb_vehicles', $field);

        // $this->db->where('id', $id);
        // $this->db->update('vb_vehicles', );
        // if($this->db->affected_rows() > 0){
        //     return true;
        // }else{
        //     return false;
        // }
    }

    function getUsersByDeptAuth3($auth)
    {
            
    
        //echo "<br>";print_r( $this->session->userdata); //die();
        $query = $this->db->select('user_details.id, user_details.salutation, user_details.first_name, user_details.middle_name, user_details.last_name, departments.name as dept_name,auth_types.type')->from('user_details')->join('departments','user_details.dept_id = departments.id');   
        $query->join('user_auth_types', 'user_auth_types.id=user_details.id');
		$query->join('auth_types',  'auth_types.id=user_auth_types.auth_id');
		$query->where('user_auth_types.auth_id',$auth); 
		$query->order_by("user_details.first_name");
		//echo $query; die();
		/*$x='mm';                                                     
        if($auth != 'all'){
                    if (strpos($auth,',')) { 
                        if($req_emp_id==""  || $req_emp_id==NULL)
                             $query->join('user_auth_types','user_details.id = user_auth_types.id and user_auth_types.auth_id in ('.$auth.')     and user_auth_types.id != "'.$x.'" ')->join('auth_types','auth_types.id = user_auth_types.auth_id');
                        else
                             $query->join('user_auth_types','user_details.id = user_auth_types.id and user_auth_types.auth_id in ('.$auth.')     and user_auth_types.id != "'.$x.'"  and user_auth_types.id != "'.$req_emp_id.'"')->join('auth_types','auth_types.id = user_auth_types.auth_id');
                    }else{
                         if($req_emp_id==""  || $req_emp_id==NULL)
                             $query->join('user_auth_types','user_details.id = user_auth_types.id and user_auth_types.auth_id="'.$auth.'"  and user_auth_types.id != "'.$x.'"' )->join('auth_types','auth_types.id = user_auth_types.auth_id');
                         else
                             $query->join('user_auth_types','user_details.id = user_auth_types.id and user_auth_types.auth_id="'.$auth.'"  and user_auth_types.id != "'.$x.'"  and user_auth_types.id != "'.$req_emp_id.'"' )->join('auth_types','auth_types.id = user_auth_types.auth_id');
                    }
                }
        if($dept != 'all')  $query->where('user_details.dept_id',$dept);                
                $query->order_by("user_details.first_name");                
                 
               
 		echo  $auth.$query->last_query(); die();
        print_r($query->get()->result());
		*/
		//echo $query;
		//print_r($query->get()->result()); die();
        return $query->get()->result();
    }


}