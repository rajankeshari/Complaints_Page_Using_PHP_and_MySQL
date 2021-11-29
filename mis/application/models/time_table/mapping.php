<?php

class Mapping extends CI_Model
{
	private $sub_mapping = 'subject_mapping';
	private $sub_m_des="subject_mapping_des"; 
    private  $course = 'cs_courses';
	private $section_group = 'section_group_rel';
	private $cbcs_subject_offered = 'cbcs_subject_offered';
	private $cbcs_subject_offered_desc = 'cbcs_subject_offered_desc';
	private $tt_map_cbcs = 'tt_map_cbcs';
	private $tt_subject_slot_map_cbcs = 'tt_subject_slot_map_cbcs';
	
	function __construct()
	{
		// Call the Model constructor
		parent::__construct();
	}

	function count(){
		return $this->db->count_all($this->sub_mapping);
	}
	
   function insert_mapping($data){
	//  print_r($data); 
	   		if($this->db->insert($this->sub_mapping,$data))
				return $this->db->insert_id();
			else
			return false;
	   }


       function get_depts()
    {
        $query = $this->db->get_where('departments', array('type'=>'academic'));
        return $query->result();
    }



    function get_emp_names_by_dept($dept = '')
    {
        if($dept == '')
            return FALSE;
        else
        {
            /*$query=$this->db->select('users.id, salutation, first_name, middle_name, last_name, dept_id')
                                ->from('user_details')
                                ->join('users','users.id = user_details.id')
                                ->where('dept_id',$dept)
                                ->where('auth_id','emp')
                                ->order_by('first_name','ASC')
                                ->get();*/
			$query=$this->db->select('users.id, salutation, first_name, middle_name, last_name, dept_id')
                                ->from('user_details')
                                ->join('users','users.id = user_details.id')
								->join('emp_basic_details','emp_basic_details.emp_no = user_details.id')
                                ->where('user_details.dept_id',$dept)
                                ->where('users.status','A')
								->where('emp_basic_details.auth_id','ft')
                                ->order_by('first_name','ASC')
                                ->get();
            return $query->result();
        }
    }

    function cbcs_get_session_years()
    {
        $this->load->database();
            /* $query=$this->db->query("SELECT DISTINCT session_year 
                                FROM cbcs_subject_offered  group by session_year
                                "); *///Modified by kumaraswamy on 01.06.2020
			$query=$this->db->query("SELECT DISTINCT session_year 
                                FROM cbcs_subject_offered  order by session_year desc limit 3
                                ");
            $result=$query->result();

            
            // print_r($result);
            return $result;
    }
    function cbcs_get_sessions()
    {
        $this->load->database();
            $query=$this->db->query("SELECT DISTINCT session
                                FROM cbcs_subject_offered 
                                ");
            $result=$query->result();

            
            // print_r($result);
            return $result;
    }

    function old_get_session_years()
    {
        $this->load->database();
          
			/* $query=$this->db->query("SELECT DISTINCT session_year 
                                FROM old_subject_offered  group by session_year
                                ");*/ //Modified by kumaraswamy on 01.06.2020
			$query=$this->db->query("SELECT DISTINCT session_year 
                                FROM old_subject_offered  order by session_year desc limit 3
                                ");
            $result=$query->result();

            
            // print_r($result);
            return $result;
    }
    function old_get_sessions()
    {
        $this->load->database();
            $query=$this->db->query("SELECT DISTINCT session
                                FROM old_subject_offered  
                                ");
            $result=$query->result();

            
            // print_r($result);
            return $result;
    }


    function get_details_by_mapid($map_id){
        $query = $this->db->query("SELECT * FROM tt_map where map_id=$map_id");

        return $query->result();

    }
     function cbcs_get_details_by_mapid($map_id){
        $query = $this->db->query("SELECT * FROM tt_map_cbcs where map_id=$map_id");

        return $query->result();

    }

    function old_get_details_by_mapid($map_id){
        $query = $this->db->query("SELECT * FROM tt_map_old where map_id=$map_id");

        return $query->result();

    }

       function insert_mapping_tt($data){
    //  print_r($data); 
        $data['created_by'] = $this->session->userdata('id');

            if($this->db->insert('tt_map',$data))
                return $this->db->insert_id();
            else
            return false;
       }
        function cbcs_insert_mapping_tt($data){
    //  print_r($data); 
        $data['created_by'] = $this->session->userdata('id');

            if($this->db->insert('tt_map_cbcs',$data))
                return $this->db->insert_id();
            else
            return false;
       }

       function old_insert_mapping_tt($data){
      //print_r($data); die();
        $data['created_by'] = $this->session->userdata('id');

            if($this->db->insert('tt_map_old',$data))
                return $this->db->insert_id();
            else
            return false;
       }

       function find_mapping_tt($row){
      //   print_r($row);
        if($row['course_id']=='comm'){
            $q=$this->db->select('map_id, tt_id')->get_where('tt_map',array('session'=>$row['session'],'session_year'=>$row['session_year'],'course_id'=>$row['course_id'],'branch_id'=>$row['branch_id'],'semester'=>$row['semester'],'course_aggr_id'=>$row['course_aggr_id'], 'group'=>$row['group'], 'section'=>$row['section']));

        }
        else
        $q=$this->db->select('map_id, tt_id')->get_where('tt_map',array('session'=>$row['session'],'session_year'=>$row['session_year'],'course_id'=>$row['course_id'],'branch_id'=>$row['branch_id'],'semester'=>$row['semester'],'course_aggr_id'=>$row['course_aggr_id']));
  //  if($row->course_id=='minor') {  echo $this->db->last_query(); die();}
       




        if($q->num_rows > 0){
            $temp = ($q->result());
            return $temp[0];
        } else {
            $temp = new stdClass();
            $temp->map_id=-1;
            return $temp;
        }
    
    }
    function cbcs_find_mapping_tt($row){
      //   print_r($row);
        if($row['course_id']=='comm'){
            $q=$this->db->select('map_id, tt_id')->get_where('tt_map_cbcs',array('session'=>$row['session'],'session_year'=>$row['session_year'],'course_id'=>$row['course_id'],'branch_id'=>$row['branch_id'],'semester'=>$row['semester'], 'group'=>$row['group'], 'section'=>$row['section']));

        }
        else
        $q=$this->db->select('map_id, tt_id')->get_where('tt_map_cbcs',array('session'=>$row['session'],'session_year'=>$row['session_year'],'course_id'=>$row['course_id'],'branch_id'=>$row['branch_id'],'semester'=>$row['semester']));
  //  if($row->course_id=='minor') {  echo $this->db->last_query(); die();}
       




        if($q->num_rows > 0){
            $temp = ($q->result());
            return $temp[0];
        } else {
            $temp = new stdClass();
            $temp->map_id=-1;
            return $temp;
        }
    
    }

    function old_find_mapping_tt($row){
      //   print_r($row);
        if($row['course_id']=='comm'){
            $q=$this->db->select('map_id, tt_id')->get_where('tt_map_old',array('session'=>$row['session'],'session_year'=>$row['session_year'],'course_id'=>$row['course_id'],'branch_id'=>$row['branch_id'],'semester'=>$row['semester'], 'group'=>$row['group'], 'section'=>$row['section']));

        }
        else
        $q=$this->db->select('map_id, tt_id')->get_where('tt_map_old',array('session'=>$row['session'],'session_year'=>$row['session_year'],'course_id'=>$row['course_id'],'branch_id'=>$row['branch_id'],'semester'=>$row['semester']));
  //  if($row->course_id=='minor') {  echo $this->db->last_query(); die();}
       




        if($q->num_rows > 0){
            $temp = ($q->result());
            return $temp[0];
        } else {
            $temp = new stdClass();
            $temp->map_id=-1;
            return $temp;
        }
    
    }



    function find_mapping_tt_obj($row){
        // print_r($row);
        
  //  if($row->course_id=='minor') {  echo $this->db->last_query(); die();}
        if($row->course_id == 'comm'){
            $q=$this->db->select('map_id')->get_where('tt_map',array('session'=>$row->session,'session_year'=>$row->session_year,'course_id'=>$row->course_id,'branch_id'=>$row->branch_id,'semester'=>$row->semester,'course_aggr_id'=>$row->course_aggr_id, 'group'=>$row->group, 'section'=>$row->section));

        }
        else
        $q=$this->db->select('map_id')->get_where('tt_map',array('session'=>$row->session,'session_year'=>$row->session_year,'course_id'=>$row->course_id,'branch_id'=>$row->branch_id,'semester'=>$row->semester,'course_aggr_id'=>$row->course_aggr_id));



        
        if($q->num_rows > 0){
            $temp = ($q->result());
            return $temp[0]->map_id;
        } else {
            return -1;
        }
    
    }
     function cbcs_find_mapping_tt_obj($row){
        // print_r($row);
        
  //  if($row->course_id=='minor') {  echo $this->db->last_query(); die();}
        if($row->course_id == 'comm'){
            $q=$this->db->select('map_id')->get_where('tt_map_cbcs',array('session'=>$row->session,'session_year'=>$row->session_year,'course_id'=>$row->course_id,'branch_id'=>$row->branch_id,'semester'=>$row->semester, 'group'=>$row->group, 'section'=>$row->section));

        }
        else
        $q=$this->db->select('map_id')->get_where('tt_map_cbcs',array('session'=>$row->session,'session_year'=>$row->session_year,'course_id'=>$row->course_id,'branch_id'=>$row->branch_id,'semester'=>$row->semester));



        
        if($q->num_rows > 0){
            $temp = ($q->result());
            return $temp[0]->map_id;
        } else {
            return -1;
        }
    
    }

    function old_find_mapping_tt_obj($row){
        // print_r($row);
        
  //  if($row->course_id=='minor') {  echo $this->db->last_query(); die();}
        if($row->course_id == 'comm'){
            $q=$this->db->select('map_id')->get_where('tt_map_old',array('session'=>$row->session,'session_year'=>$row->session_year,'course_id'=>$row->course_id,'branch_id'=>$row->branch_id,'semester'=>$row->semester, 'group'=>$row->group, 'section'=>$row->section));

        }
        else
        $q=$this->db->select('map_id')->get_where('tt_map_old',array('session'=>$row->session,'session_year'=>$row->session_year,'course_id'=>$row->course_id,'branch_id'=>$row->branch_id,'semester'=>$row->semester));



        
        if($q->num_rows > 0){
            $temp = ($q->result());
            return $temp[0]->map_id;
        } else {
            return -1;
        }
    
    }

       function insert_subject_slot_map($map_id, $subj_id, $subj_code, $ch, $ven){
            $q = $this->db->query("DELETE from tt_subject_slot_map where map_id=$map_id AND subj_id='$subj_id'");

            $cid = $this->session->userdata('id');
            $i=0;
            foreach ($ch as $c) {
                $slot = $c;

               $slot = explode("_", $slot);

            //    print_r($slot[0].'   '.$slot[1]);

                $ins = $this->db->query(" INSERT into tt_subject_slot_map values($map_id, '$subj_id', '$subj_code', $slot[0], $slot[1], $ven[$i] , '$cid', CURRENT_TIMESTAMP ) ");

               
                $i++;
            }
        }

         function cbcs_insert_subject_slot_map($map_id, $subj_code, $ch, $ven){
            $q = $this->db->query("DELETE from tt_subject_slot_map_cbcs where map_id=$map_id AND subj_code='$subj_code'");

            $cid = $this->session->userdata('id');
            $i=0;
            foreach ($ch as $c) {
                $slot = $c;

               $slot = explode("_", $slot);

            //    print_r($slot[0].'   '.$slot[1]);

                $ins = $this->db->query(" INSERT into tt_subject_slot_map_cbcs values($map_id, '$subj_code', $slot[0], $slot[1], $ven[$i] , '$cid', CURRENT_TIMESTAMP ) ");

               
                $i++;
            }
        }

         function old_insert_subject_slot_map($map_id, $subj_code, $ch, $ven){
            $q = $this->db->query("DELETE from tt_subject_slot_map_old where map_id=$map_id AND subj_code='$subj_code'");

            $cid = $this->session->userdata('id');
            $i=0;
            foreach ($ch as $c) {
                $slot = $c;

               $slot = explode("_", $slot);

            //    print_r($slot[0].'   '.$slot[1]);

                $ins = $this->db->query(" INSERT into tt_subject_slot_map_old values($map_id, '$subj_code', $slot[0], $slot[1], $ven[$i] , '$cid', CURRENT_TIMESTAMP ) ");

               
                $i++;
            }
        }


        function get_subject_slot_map($map_id){
            $q = $this->db->query(" SELECT map_id, subj_id, subj_code, day, slot_no, venue_id from tt_subject_slot_map where map_id=$map_id");

            return $q->result();


        }
          function cbcs_get_subject_slot_map($map_id){
            $q = $this->db->query(" SELECT map_id, subj_code, day, slot_no, venue_id from tt_subject_slot_map_cbcs where map_id=$map_id");

            return $q->result();


        }


         function old_get_subject_slot_map($map_id){
            $q = $this->db->query(" SELECT map_id, subj_code, day, slot_no, venue_id from tt_subject_slot_map_old where map_id=$map_id");

            return $q->result();


        }

        function get_mapped_slots_by_venue($session, $session_year, $venue_id){
            $q = $this->db->query("SELECT tt_map.map_id, tt_map.dept_id, tt_map.course_id, tt_map.branch_id, tt_map.semester, tt_map.section, tt_subject_slot_map.subj_id, tt_subject_slot_map.subj_code, tt_subject_slot_map.day, tt_subject_slot_map.slot_no, tt_subject_slot_map.venue_id  FROM tt_map JOIN tt_subject_slot_map ON tt_map.map_id = tt_subject_slot_map.map_id WHERE tt_subject_slot_map.venue_id=$venue_id AND tt_map.session='$session' AND tt_map.session_year='$session_year'");

            return $q->result();

        }
      

        function get_mapped_slots_by_emp($session, $session_year, $empno){
            $q = $this->db->query("SELECT tt_map.course_id, tt_map.branch_id, tt_map.semester, tt_map.section, tt_subject_slot_map.subj_id, tt_subject_slot_map.subj_code, tt_subject_slot_map.day, tt_subject_slot_map.slot_no, tt_subject_slot_map.venue_id, subject_mapping_des.emp_no  FROM tt_map JOIN tt_subject_slot_map JOIN subject_mapping JOIN subject_mapping_des ON tt_map.map_id = tt_subject_slot_map.map_id AND tt_map.session = subject_mapping.session AND tt_map.session_year=subject_mapping.session_year AND subject_mapping.map_id = subject_mapping_des.map_id AND tt_subject_slot_map.subj_id = subject_mapping_des.sub_id WHERE tt_map.session='$session' AND tt_map.session_year='$session_year' AND  subject_mapping_des.M=1 AND subject_mapping_des.emp_no='$empno'");

            return $q->result();


        }

         function cbcs_get_mapped_slots_by_emp($session, $session_year, $empno){
            /*$q = $this->db->query("SELECT distinct tt_map_cbcs.course_id, tt_map_cbcs.branch_id, tt_map_cbcs.semester, tt_map_cbcs.section,  tt_subject_slot_map_cbcs.subj_code, tt_subject_slot_map_cbcs.day, tt_subject_slot_map_cbcs.slot_no, tt_subject_slot_map_cbcs.venue_id, cbcs_subject_offered_desc.emp_no  FROM tt_map_cbcs JOIN tt_subject_slot_map_cbcs JOIN cbcs_subject_offered JOIN cbcs_subject_offered_desc ON tt_map_cbcs.map_id = tt_subject_slot_map_cbcs.map_id AND tt_map_cbcs.session = cbcs_subject_offered.session AND tt_map_cbcs.session_year=cbcs_subject_offered.session_year AND cbcs_subject_offered.id = cbcs_subject_offered_desc.sub_offered_id AND tt_subject_slot_map_cbcs.subj_code = cbcs_subject_offered_desc.sub_id WHERE tt_map_cbcs.session='$session' AND tt_map_cbcs.session_year='$session_year' AND   cbcs_subject_offered_desc.emp_no='$empno'");*/
			$q = $this->db->query("select distinct a.day,a.slot_no,a.venue_id,b.course_id,b.branch_id,b.semester,b.section,a.subj_code from tt_subject_slot_map_cbcs a
inner join tt_map_cbcs b on b.map_id=a.map_id
inner join cbcs_subject_offered_desc c on c.section=b.section and c.sub_id=a.subj_code
inner join cbcs_subject_offered d on d.id=c.sub_offered_id and d.session_year=b.session_year and d.session=b.session
where c.emp_no='$empno' AND d.session_year='$session_year' AND d.`session`='$session' AND d.course_id='comm' and d.dept_id='comm' and d.branch_id='comm' and b.course_id=d.course_id AND b.branch_id=d.branch_id
UNION
select distinct a.day,a.slot_no,a.venue_id,b.course_id,b.branch_id,b.semester,b.section,a.subj_code from tt_subject_slot_map_cbcs a
inner join tt_map_cbcs b on b.map_id=a.map_id
inner join cbcs_subject_offered_desc c on c.sub_id=a.subj_code
inner join cbcs_subject_offered d on d.id=c.sub_offered_id and d.session_year=b.session_year and d.session=b.session
where c.emp_no='$empno' AND d.session_year='$session_year' AND d.`session`='$session' AND d.course_id!='comm' and d.dept_id!='comm' and d.branch_id!='comm' and b.course_id=d.course_id AND b.branch_id=d.branch_id
UNION
select distinct a.day,a.slot_no,a.venue_id,b.course_id,b.branch_id,b.semester,b.section,a.subj_code from tt_subject_slot_map_old a
inner join tt_map_old b on b.map_id=a.map_id
inner join old_subject_offered_desc c on c.sub_id=a.subj_code
inner join old_subject_offered d on d.id=c.sub_offered_id and d.session_year=b.session_year and d.session=b.session
where c.emp_no='$empno' AND d.session_year='$session_year' AND d.`session`='$session' and b.course_id=d.course_id AND b.branch_id=d.branch_id");

            return $q->result();


        }
        function old_get_mapped_slots_by_emp($session, $session_year, $empno){
            $q = $this->db->query("SELECT distinct tt_map_old.course_id, tt_map_old.branch_id, tt_map_old.semester, tt_map_old.section,  tt_subject_slot_map_old.subj_code, tt_subject_slot_map_old.day, tt_subject_slot_map_old.slot_no, tt_subject_slot_map_old.venue_id, old_subject_offered_desc.emp_no  FROM tt_map_old JOIN tt_subject_slot_map_old JOIN old_subject_offered JOIN old_subject_offered_desc ON tt_map_old.map_id = tt_subject_slot_map_old.map_id AND tt_map_old.session = old_subject_offered.session AND tt_map_old.session_year=old_subject_offered.session_year AND old_subject_offered.id = old_subject_offered_desc.sub_offered_id AND tt_subject_slot_map_old.subj_code = old_subject_offered_desc.sub_id WHERE tt_map_old.session='$session' AND tt_map_old.session_year='$session_year' AND   old_subject_offered_desc.emp_no='$empno'");

            return $q->result();


        }

        function cbcs_get_mapped_slots_by_venue($session, $session_year, $venue_id){
           /* $q = $this->db->query("SELECT tt_map_cbcs.map_id, tt_map_cbcs.dept_id, tt_map_cbcs.course_id, tt_map_cbcs.branch_id, tt_map_cbcs.semester, tt_map_cbcs.section,  tt_subject_slot_map_cbcs.subj_code, tt_subject_slot_map_cbcs.day, tt_subject_slot_map_cbcs.slot_no, tt_subject_slot_map_cbcs.venue_id  FROM tt_map_cbcs JOIN tt_subject_slot_map_cbcs ON tt_map_cbcs.map_id = tt_subject_slot_map_cbcs.map_id WHERE tt_subject_slot_map_cbcs.venue_id=$venue_id AND tt_map_cbcs.session='$session' AND tt_map_cbcs.session_year='$session_year'");*/
			
			
			$q = $this->db->query("SELECT tt_map_cbcs.map_id, tt_map_cbcs.dept_id, tt_map_cbcs.course_id, tt_map_cbcs.branch_id, tt_map_cbcs.semester, tt_map_cbcs.section, tt_subject_slot_map_cbcs.subj_code, tt_subject_slot_map_cbcs.day, tt_subject_slot_map_cbcs.slot_no, tt_subject_slot_map_cbcs.venue_id
FROM tt_map_cbcs
JOIN tt_subject_slot_map_cbcs ON tt_map_cbcs.map_id = tt_subject_slot_map_cbcs.map_id
WHERE tt_subject_slot_map_cbcs.venue_id=$venue_id AND tt_map_cbcs.session='$session' AND tt_map_cbcs.session_year='$session_year'
union
SELECT tt_map_old.map_id, tt_map_old.dept_id, tt_map_old.course_id, tt_map_old.branch_id, tt_map_old.semester, tt_map_old.section, tt_subject_slot_map_old.subj_code, tt_subject_slot_map_old.day, tt_subject_slot_map_old.slot_no, tt_subject_slot_map_old.venue_id
FROM tt_map_old
JOIN tt_subject_slot_map_old ON tt_map_old.map_id = tt_subject_slot_map_old.map_id
WHERE tt_subject_slot_map_old.venue_id=$venue_id AND tt_map_old.session='$session' AND tt_map_old.session_year='$session_year'");

            return $q->result();

        }

        // function cbcs_get_mapped_slots_by_emp($session, $session_year, $empno){
        //     $q = $this->db->query("SELECT tt_map_cbcs.course_id, tt_map_cbcs.branch_id, tt_map_cbcs.semester, tt_map_cbcs.section,  tt_subject_slot_map_cbcs.subj_code, tt_subject_slot_map_cbcs.day, tt_subject_slot_map_cbcs.slot_no, tt_subject_slot_map_cbcs.venue_id, subject_mapping_des.emp_no  FROM tt_map_cbcs JOIN tt_subject_slot_map_cbcs JOIN subject_mapping JOIN subject_mapping_des ON tt_map_cbcs.map_id = tt_subject_slot_map_cbcs.map_id AND tt_map_cbcs.session = subject_mapping.session AND tt_map_cbcs.session_year=subject_mapping.session_year AND subject_mapping.map_id = subject_mapping_des.map_id AND tt_subject_slot_map_cbcs.subj_id = subject_mapping_des.sub_id WHERE tt_map.session='$session' AND tt_map.session_year='$session_year' AND  subject_mapping_des.M=1 AND subject_mapping_des.emp_no='$empno'");

        //     return $q->result();


        // }

        function old_get_mapped_slots_by_venue($session, $session_year, $venue_id){
            $q = $this->db->query("SELECT tt_map_old.map_id, tt_map_old.dept_id, tt_map_old.course_id, tt_map_old.branch_id, tt_map_old.semester, tt_map_old.section,  tt_subject_slot_map_old.subj_code, tt_subject_slot_map_old.day, tt_subject_slot_map_old.slot_no, tt_subject_slot_map_old.venue_id  FROM tt_map_old JOIN tt_subject_slot_map_old ON tt_map_old.map_id = tt_subject_slot_map_old.map_id WHERE tt_subject_slot_map_old.venue_id=$venue_id AND tt_map_old.session='$session' AND tt_map_old.session_year='$session_year'");

            return $q->result();

        }


        function get_mapped_slots_count($map_id){
            $q = $this->db->query("SELECT COUNT(*) as num from tt_subject_slot_map where map_id=$map_id");

            return $q->result()[0];

        }
          function cbcs_get_mapped_slots_count($map_id){
            $q = $this->db->query("SELECT COUNT(*) as num from tt_subject_slot_map_cbcs where map_id=$map_id");

            return $q->result()[0];

        }

        function old_get_mapped_slots_count($map_id){
            $q = $this->db->query("SELECT COUNT(*) as num from tt_subject_slot_map_old where map_id=$map_id");
            // echo $this->db->last_query();
            // exit;
            return $q->result()[0];

        }


        function get_teacher_by_subj_and_mapid($map_id, $sub_id){
            $query = $this->db->query(" SELECT emp_no from subject_mapping_des where map_id=$map_id AND sub_id='$sub_id' AND M=1 ");

            if($query->num_rows==0)
                return -1;

            return $query->result();



        }
        function cbcs_get_teacher($sub_code, $data){
   //          $session = $data->session;
   //          $session_year = $data->session_year;
   //          $dept_id = $data->dept_id;
   //          $course_id = $data->course_id;
   //          $branch_id = $data->branch_id;
   //          $sem = $data->semester;

			

   //          $query = $this->db->query("SELECT id from cbcs_subject_offered where session='$session' and session_year='$session_year' and course_id='$course_id' and dept_id='$dept_id' and branch_id='$branch_id' and semester = '$sem' and sub_code='$sub_code'");

   //          $sub_offered_id = $query->result()[0]->id;
			

   //          /*$q = $this->db->query("SELECT emp_no from cbcs_subject_offered_desc where sub_offered_id=$sub_offered_id and coordinator='1' and sub_id='$sub_code'");*/
			// $q = $this->db->query("SELECT emp_no from cbcs_subject_offered_desc where sub_offered_id=$sub_offered_id and coordinator='1' and sub_id='$sub_code'");

   //          if($q->num_rows==0)
   //              return -1;
   //          return $q->result();





            $session = $data->session;
            $session_year = $data->session_year;
            $dept_id = $data->dept_id;
            $course_id = $data->course_id;
            $branch_id = $data->branch_id;
            $sem = $data->semester;
            $sec = $data->section;
            

            $query = $this->db->query("SELECT id from cbcs_subject_offered where session='$session' and session_year='$session_year' and course_id='$course_id' and dept_id='$dept_id' and branch_id='$branch_id' and semester = '$sem' and sub_code='$sub_code'");

            $sub_offered_id = $query->result()[0]->id;
            $sub_off_ids = $query->result();

            $str="";
            
            foreach ($sub_off_ids as $sid) {
            	$str=$str.$sid->id.',';
            }

            $str=rtrim($str, ",");

            // print_r($str);

            // print_r($query->result());
            // die();
            /*$q = $this->db->query("SELECT emp_no from cbcs_subject_offered_desc where sub_offered_id=$sub_offered_id and coordinator='1' and sub_id='$sub_code'");*/
            $q = $this->db->query("SELECT sub_id, coordinator, emp_no from cbcs_subject_offered_desc where sub_offered_id IN ($str)  and sub_id='$sub_code'  and section='$sec'");

            if($q->num_rows==0)
                return -1;
           // print_r($q->result());
            return $q->result();








        }

        function old_get_teacher($sub_code, $data){
            // $session = $data->session;
            // $session_year = $data->session_year;
            // $dept_id = $data->dept_id;
            // $course_id = $data->course_id;
            // $branch_id = $data->branch_id;
            // $sem = $data->semester;
			

            // $query = $this->db->query("SELECT id from old_subject_offered where session='$session' and session_year='$session_year' and course_id='$course_id' and dept_id='$dept_id' and branch_id='$branch_id' and semester = '$sem' and sub_code='$sub_code'");

            // $sub_offered_id = $query->result()[0]->id;

            // $q = $this->db->query("SELECT emp_no from old_subject_offered_desc where sub_offered_id=$sub_offered_id and coordinator='1' and sub_id='$sub_code'");

            // if($q->num_rows==0)
            //     return -1;
            // return $q->result();




            $session = $data->session;
            $session_year = $data->session_year;
            $dept_id = $data->dept_id;
            $course_id = $data->course_id;
            $branch_id = $data->branch_id;
            $sem = $data->semester;
            $sec = $data->section;
            

            $query = $this->db->query("SELECT id from old_subject_offered where session='$session' and session_year='$session_year' and course_id='$course_id' and dept_id='$dept_id' and branch_id='$branch_id' and semester = '$sem' and sub_code='$sub_code'");

            $sub_offered_id = $query->result()[0]->id;
            $sub_off_ids = $query->result();

            $str="";
            
            foreach ($sub_off_ids as $sid) {
            	$str=$str.$sid->id.',';
            }

            $str=rtrim($str, ",");

            $q = $this->db->query("SELECT sub_id, emp_no from old_subject_offered_desc where sub_offered_id IN ($str)  and sub_id='$sub_code' and section='$sec'");

            if($q->num_rows==0)
                return -1;
            return $q->result();


        }


        function get_emp_name_by_empno($emp_no){
            // $query = $this->db->query("SELECT `First Name` as fname, `Last Name` as lname from empadsuser where `Emp no`='$emp_no'");

             $query=$this->db->select('users.id, salutation, first_name, middle_name, last_name, dept_id')
                                ->from('user_details')
                                ->join('users','users.id = user_details.id')
                                ->where('users.id',$emp_no)
                                ->where('auth_id','emp')
                                ->order_by('first_name','ASC')
                                ->get();
            // return $query->result();
                                
			//print_r($query->result());
            if($query->num_rows()==0)
                return "";
			
            return $query->result()[0];


        }

        function get_venue_by_id($id){
            $q = $this->db->query("SELECT dept_id, room_no from venue where id=$id");
			if($q->num_rows > 0){
            return $q->result();
            
        } else {
            return 0;
        }
			
			
            /*if($q->num_rows()==0)
                return 0;
            return $q->result();*/
        }

        function find_teacher_mapping_old($row){
    //     print_r($row);
        if($row->course_id == 'comm'){
            $q=$this->db->select('map_id')->get_where($this->sub_mapping,array('session'=>$row->session,'session_year'=>$row->session_year,'course_id'=>$row->course_id,'branch_id'=>$row->branch_id,'semester'=>$row->semester,'aggr_id'=>$row->course_aggr_id, 'group'=>$row->group, 'section'=>$row->section));

        }
        else
        $q=$this->db->select('map_id')->get_where($this->sub_mapping,array('session'=>$row->session,'session_year'=>$row->session_year,'course_id'=>$row->course_id,'branch_id'=>$row->branch_id,'semester'=>$row->semester,'aggr_id'=>$row->course_aggr_id));



  //  if($row->course_id=='minor') {  echo $this->db->last_query(); die();}
        if($q->num_rows > 0){
            $temp = ($q->result());
            return $temp[0]->map_id;
        } else {
            return -1;
        }
    
    }


	function get_details_regular($session,$session_year,$dept_id){

        $this->load->database();
        // $query=$this->db->query("SELECT DISTINCT semester,session,session_year,course_id, branch_id,course_aggr_id
		      //                   FROM reg_regular_form
						  //   	WHERE session='$session'
        // 						AND session_year='$session_year' 
        //                         AND hod_status='1' 
        // 						AND course_aggr_id 
								// IN (SELECT DISTINCT aggr_id
								// 	FROM dept_course
								// 	WHERE dept_id='$dept_id'
								// 	AND aggr_id NOT LIKE 'comm%') 
								// ");
        //echo $this->db->last_query();die();
         $query=$this->db->query("SELECT DISTINCT semester,session,session_year,course_id, branch_id,aggr_id as course_aggr_id
                                FROM subject_mapping
                                WHERE session='$session'
                                AND session_year='$session_year' 
                                
                                AND aggr_id 
                                IN (SELECT DISTINCT aggr_id
                                    FROM dept_course
                                    WHERE dept_id='$dept_id'
                                    AND aggr_id NOT LIKE 'comm%') 
                                ");
// echo $this->db->last_query();die();

        $result=$query->result();
      // print_r($result);
        return $result;
    }
    //too less data in the table, but this should work
    function cbcs_get_details_regular($session,$session_year,$dept_id){

        $this->load->database();
       /* 23/11/2020 $query=$this->db->query("SELECT DISTINCT semester,session,session_year,course_id, branch_id,wef_year
                                FROM cbcs_subject_offered
                                WHERE session='$session'
                                AND session_year='$session_year'
                                AND dept_id='$dept_id'
                                  
                                "); */
								
	 $query=$this->db->query("SELECT DISTINCT semester,session,session_year,course_id, branch_id
                                FROM cbcs_subject_offered
                                WHERE session='$session'
                                AND session_year='$session_year'
                                AND dept_id='$dept_id'
                                  
                                ");
		/*$query=$this->db->query("SELECT DISTINCT semester,session,session_year,course_id, branch_id,wef_year
                                FROM cbcs_subject_offered
                                WHERE session='$session'
                                AND session_year='$session_year'
                                AND dept_id='$dept_id' union  SELECT DISTINCT semester,session,session_year,course_id, branch_id,wef_year
                                FROM old_subject_offered
                                WHERE session='$session'
                                AND session_year='$session_year'
                                AND dept_id='$dept_id'                                 
                                ");*/
        //echo $this->db->last_query();die();
        $result=$query->result();
      // print_r($result);
        return $result;
    }
    function old_get_details_regular_summer($session,$session_year,$dept_id){

        $this->load->database();
        $query=$this->db->query("SELECT DISTINCT semester,session,session_year,course_id, branch_id,wef_year
                                FROM old_subject_offered
                                WHERE session='$session'
                                AND session_year='$session_year'
                                AND dept_id='$dept_id'
                                  
                                ");
        //echo $this->db->last_query();die();
        $result=$query->result();
      // print_r($result);
        return $result;
    }
	

      function old_get_details_regular($session,$session_year,$dept_id){

        $this->load->database();
        $query=$this->db->query("SELECT DISTINCT semester,session,session_year,course_id, branch_id,wef_year
                                FROM old_subject_offered
                                WHERE session='$session'
                                AND session_year='$session_year'
                                AND dept_id='$dept_id'
                                  
                                ");
        //echo $this->db->last_query();die();
        $result=$query->result();
      // print_r($result);
        return $result;
    }
	
	function get_details_honour($session,$session_year,$dept_id){

        $this->load->database();
      
        /*$query=$this->db->query("SELECT DISTINCT semester,session,session_year,honours_agg_id as course_aggr_id, 'honour' as course_id, SUBSTRING_INDEX(SUBSTRING_INDEX(honours_agg_id, '_', 2), '_', -1) as branch_id  
		                        FROM hm_form 
						    	WHERE session='' 
						    	AND session_year='' 
						    	AND honours='1' 
                                AND honour_hod_status='Y' 
						    	AND honours_agg_id 
						    	IN (SELECT DISTINCT aggr_id
									FROM dept_course
									WHERE dept_id='$dept_id'
									AND aggr_id LIKE 'honour%')   
								");*/
				$q="SELECT DISTINCT reg_regular_form.semester, honours_agg_id AS course_aggr_id, 'honour' AS course_id, SUBSTRING_INDEX(SUBSTRING_INDEX(honours_agg_id, '_', 2), '_', -1) AS branch_id,'".$session."' as session,'".$session_year."' as session_year
FROM hm_form
join reg_regular_form on reg_regular_form.admn_no=hm_form.admn_no
WHERE  honours='1' AND honour_hod_status='Y' AND honours_agg_id IN (
SELECT DISTINCT aggr_id
FROM dept_course
WHERE dept_id=? AND aggr_id LIKE 'honour%') and reg_regular_form.semester >=5 and reg_regular_form.hod_status='1'  and reg_regular_form.session=?";
			//echo $this->db->last_query();
                                $query=$this->db->query($q,array($dept_id,$session));	
										//echo $this->db->last_query();
								$result=$query->result();
								
								$q = "select DISTINCT b.semester,a.aggr_id AS course_aggr_id,e.branch_id,e.course_id,'".$session."' as session ,'".$session_year."' as session_year from optional_offered a join course_structure b on a.id=b.id 
join dept_course c on a.aggr_id=c.aggr_id
join course_branch e on c.course_branch_id=e.course_branch_id
join hm_form d on a.aggr_id=d.honours_agg_id
where a.session_year=? and a.`session`=? and a.aggr_id like 'honour%' and c.dept_id=?";
					$query=$this->db->query($q,array($session_year,$session,$dept_id));	
					$result2=$query->result();
      // print_r($result);
        return $result;
    }
    function get_details_minor($session,$session_year,$dept_id){

        $this->load->database();
       /* $query=$this->db->query("SELECT DISTINCT semester,session,session_year,minor_agg_id as course_aggr_id,branch_id,course_id  
		                        FROM hm_minor_details INNER JOIN hm_form
						    	WHERE hm_minor_details.form_id=hm_form.form_id 
						    	AND session='$session' 
        						AND session_year='$session_year' 
        						AND offered=1 
                                AND minor_hod_status='Y' 
        						AND minor_agg_id  
								IN (SELECT DISTINCT aggr_id
									FROM dept_course
									WHERE dept_id='$dept_id'
									AND aggr_id LIKE 'minor%') 
								"); */
		
		$q="SELECT DISTINCT reg_regular_form.semester, hm_minor_details.minor_agg_id AS course_aggr_id, hm_minor_details.branch_id,hm_minor_details.course_id,'".$session."' as session ,'".$session_year."' as session_year
FROM hm_minor_details
INNER JOIN hm_form
join reg_regular_form on reg_regular_form.admn_no=hm_form.admn_no
WHERE hm_minor_details.form_id=hm_form.form_id  AND offered=1 AND minor_hod_status='Y' AND minor_agg_id IN (
SELECT DISTINCT aggr_id
FROM dept_course
WHERE dept_id=? AND aggr_id LIKE 'minor%') and reg_regular_form.semester >=5  and reg_regular_form.hod_status='1' and reg_regular_form.session=?";
		$query=$this->db->query($q,array($dept_id,$session));			
        $result=$query->result();
		
		$q = "select DISTINCT b.semester,a.aggr_id AS course_aggr_id,d.branch_id,d.course_id,'".$session."' as session ,'".$session_year."' as session_year from optional_offered a join course_structure b on a.id=b.id 
join dept_course c on a.aggr_id=c.aggr_id
join hm_minor_details d on a.aggr_id=d.minor_agg_id
where a.session_year=? and a.`session`=? and a.aggr_id like 'minor%' and c.dept_id=?";
					$query=$this->db->query($q,array($session_year,$session,$dept_id));	
					$result2=$query->result();
					$result=array_merge($result,$result2);
		
      // print_r($result);
        return $result;
    }

    function getStudentAcdamicDetails($id) {
        return $this->db->get_where('stu_academic', array('admn_no' => $id))->result();
    }

	 function get_details_other($session,$session_year,$dept_id)
        {
        	$this->load->database();
        	$query=$this->db->query("SELECT DISTINCT a.semester,a.session,a.session_year,a.course_id, a.branch_id,c.aggr_id As course_aggr_id 
                                FROM reg_other_form AS a , reg_other_subject AS b , course_structure AS c 
                                WHERE a.session='$session'
                                AND a.session_year='$session_year' 
                                AND a.hod_status='1' 
                                AND a.acad_status='1' 
                                AND a.form_id=b.form_id 
                                AND b.sub_id=c.id 
                                AND a.hod_status='1' 
                                AND c.aggr_id  
                                IN (SELECT DISTINCT aggr_id
                                    FROM dept_course
                                    WHERE dept_id='$dept_id' 
                                    AND aggr_id NOT LIKE 'comm%') 
								");
        $result=$query->result();
      // print_r($result);
        return $result;

        // SELECT  concat(reg_other_form.course_id,'_',reg_other_form.branch_id,'_',stu_academic.enrollment_year,'_',stu_academic.enrollment_year+1)  AS course_aggr_id,
        // reg_other_form.form_id,reg_other_form.admn_no,reg_other_form.semester,reg_other_form.course_id,reg_other_form.branch_id,reg_other_form.semester,reg_other_form.session,reg_other_form.session_year,stu_academic.enrollment_year  
        // FROM `reg_other_form`,`stu_academic` 
        // WHERE  reg_other_form.admn_no=stu_academic.admn_no
        


// $query=$this->db->query("SELECT DISTINCT a.semester,a.session,a.session_year,a.course_id,a.branch_id,b.course_aggr_id  
//         							FROM `reg_other_form` as a INNER JOIN `reg_regular_form` as b  
//         							WHERE a.admn_no=b.admn_no
//         							AND b.session=a.session 
//         							AND b.session='$session' 
//         							AND b.branch_id=a.branch_id 
//         							AND b.course_id=a.course_id 
//         							AND b.semester=a.semester 
//         							AND a.session_year='$session_year' 
//         							AND a.hod_status='1' 
//         							AND a.acad_status='1'   
// 									AND b.course_aggr_id 
// 									IN (SELECT DISTINCT aggr_id 
// 										FROM dept_course 
// 										WHERE dept_id='$dept_id'  
// 										AND aggr_id NOT LIKE 'comm%')

// 								");


        	$result=$query->result();
        	//print_r($result);
        	return $result;
        }

    function find_mapping_by_other($row){
    //     print_r($row);
		
        $q=$this->db->select('map_id')->get_where($this->sub_mapping,array('session'=>$this->session->userdata('session'),'session_year'=>$this->session->userdata('session_year'),'course_id'=>$row->course_id,'branch_id'=>/*$this->session->userdata('session')=='Summer' && $row->course_id=='minor'?$row->branch_id_manipulated:$row->branch_id */$row->branch_id,'semester'=>$row->semester,'aggr_id'=>$row->course_aggr_id));
  //  if($row->course_id=='minor') {  echo $this->db->last_query(); die();}
        if($q->num_rows > 0){
        	$temp = ($q->result());
        	return $temp[0]->map_id;
        } else {
        	return -1;
        }
    
	}


    

   function find_mapping_des_count($map_id){
        

        $this->load->database();
        $query=$this->db->query("SELECT count(*) as c
                                FROM subject_mapping_des
                                WHERE map_id='$map_id' 
                                AND emp_no = 0  
                                ");
        $result=$query->result();
      // print_r($result);
        return $result[0]->c;
    }

         function get_details_exam($session,$session_year,$dept_id,$course_id,$branch_id,$type='R')
        {
        	$this->load->database();
		/*	$q="SELECT distinct sub_id, SESSION,session_year,d.aggr_id,f.name
FROM reg_exam_rc_form a 
INNER JOIN user_details b on a.admn_no=b.id
INNER JOIN reg_exam_rc_subject c on a.form_id=c.form_id
INNER JOIN course_structure d on c.sub_id=d.id
join subjects f on sub_id=f.id
WHERE b.dept_id=? AND SESSION=? 
AND session_year=? AND course_id=? 
AND branch_id=? AND hod_status='1'"; */
        	$query=$this->db->query("SELECT DISTINCT sub_id,session,session_year,course_structure.aggr_id,dept_course.dept_id    
		                        FROM reg_exam_rc_form INNER JOIN user_details INNER JOIN reg_exam_rc_subject INNER JOIN course_structure INNER JOIN dept_course 
								WHERE reg_exam_rc_form.admn_no=user_details.id 
								AND reg_exam_rc_form.form_id=reg_exam_rc_subject.form_id 
                                AND reg_exam_rc_subject.sub_id=course_structure.id 
                                AND course_structure.aggr_id=dept_course.aggr_id 
								AND dept_course.dept_id='$dept_id' 
        						AND session='$session' 
        						AND session_year='$session_year' 
        						AND course_id='$course_id' 
        						AND branch_id='$branch_id' 
                                AND hod_status='1' 
								AND type='$type'
								");
								
			//$query=$this->db->query($q,array($dept_id,$session,$session_year,$course_id,$branch_id));
        	$result=$query->result();
        	   // print_r($result);
        	return $result;
        }
    // function get_details_idle($session,$session_year,$dept_id)
    // {
    //     	$this->load->database();
    //     	$query=$this->db->query("SELECT DISTINCT course_id, branch_id, semester, course_aggr_id
		  //                       FROM reg_idle_form
				// 				WHERE course_aggr_id 
				// 				IN(SELECT DISTINCT aggr_id
				// 					FROM dept_course
				// 					WHERE dept_id=$dept_id)
    //     						AND session=$session
    //     						AND session_year=$session_year
				// 			");
    //    	$result=$query->result();
    //    	//print_r($result);
    //    	return $result;
    // }
        function get_details_other_summer($session_year,$dept_id,$session)
        {
            $this->load->database();
            $query=$this->db->query("SELECT DISTINCT a.semester,a.session,a.session_year,a.course_id, a.branch_id,c.aggr_id As course_aggr_id 
                                FROM reg_exam_rc_form AS a , reg_exam_rc_subject AS b , course_structure AS c 
                                WHERE a.session_year='$session_year' 
                                AND a.session='$session'
                                AND a.hod_status='1' 
                                AND a.acad_status='1'
                                AND a.course_id NOT LIKE 'jrf' 
                                AND a.branch_id NOT LIKE 'jrf' 
                                AND a.form_id=b.form_id 
                                AND b.sub_id=c.id 
                                AND c.aggr_id  
                                IN (SELECT DISTINCT aggr_id
                                    FROM dept_course
                                    WHERE dept_id='$dept_id' 
                                    AND aggr_id NOT LIKE 'comm%') 
                                ");
        $result=$query->result();
		//echo $this->db->last_query();
        return $result;
    }

        function get_subjects_for_other($sy,$session,$semester,$course, $branch,$aggr_id){
        $this->load->database();
        $query= $this->db->query("SELECT DISTINCT sub_id as id FROM reg_other_form INNER JOIN reg_other_subject 
                                  WHERE reg_other_form.form_id=reg_other_subject.form_id 
                                  AND session_year='$sy' 
                                  AND session='$session' 
                                  AND semester='$semester' 
                                  AND course_id='$course' 
                                  AND branch_id='$branch' 
                                  AND hod_status='1' 
                                 ");
        $result = $query->result();
        return $result;
    }

    function get_subjects_for_other_summer($sy,$session,$semester,$course, $branch,$aggr_id){
        $this->load->database();
        $query= $this->db->query("SELECT DISTINCT sub_id as id FROM reg_exam_rc_form INNER JOIN reg_exam_rc_subject 
                                  WHERE reg_exam_rc_form.form_id=reg_exam_rc_subject.form_id 
                                  AND session_year='$sy' 
                                  AND session='$session' 
                                  AND semester='$semester' 
                                  AND course_id='$course' 
                                  AND branch_id='$branch' 
                                  AND hod_status='1' 
                                 ");
        $result = $query->result();
        return $result;
    }
    function get_details_common($session,$session_year,$dept_id)
    {
    	$this->load->database();
        	$query=$this->db->query("SELECT DISTINCT semester,session,s.session_year,course_aggr_id,`group`,s.section, 'comm' as course_id, 'comm' as branch_id 
		                        FROM reg_regular_form as r INNER JOIN section_group_rel as s
								WHERE r.session_year=s.session_year 
        						AND session='$session' 
        						AND r.session_year='$session_year' 
        						AND course_aggr_id LIKE 'comm%' 
                                AND hod_status='1' 
								");
        	$result=$query->result();

        	
        	// print_r($result);s
        	return $result;
    }

    function cbcs_get_details_common($session,$session_year,$dept_id)
    {
       $this->load->database();
        $query=$this->db->query("SELECT DISTINCT semester,session,cbcs_subject_offered.session_year,course_id, sub_group as `group`, section, branch_id,wef_year
                                FROM cbcs_subject_offered JOIN section_group_rel ON cbcs_subject_offered.sub_group=section_group_rel.group
                                WHERE session='$session'
                                AND cbcs_subject_offered.session_year='$session_year'
                                AND dept_id='comm'
                                  
                                ");
        //echo $this->db->last_query();die();
        $result=$query->result();
      // print_r($result);
        return $result;
    }


     function get_details_prep($session,$session_year,$dept_id)
    {
        $this->load->database();
            $query=$this->db->query("SELECT DISTINCT semester,session,session_year,course_aggr_id, 'prep' as course_id, 'prep' as branch_id 
                                FROM reg_regular_form 
                                WHERE session='$session' 
                                AND session_year='$session_year' 
                                AND course_aggr_id LIKE 'prep%' 
                                AND hod_status='1' 
                                ");
            $result=$query->result();
				//echo $this->db->last_query();
            
             // print_r($result);
            return $result;
    }
	
	function old_get_details_prep($session,$session_year,$dept_id)
    {
       $this->load->database();
        $query=$this->db->query("SELECT DISTINCT semester,session,session_year,course_id, branch_id,wef_year
                                FROM old_subject_offered
                                WHERE session='$session'
                                AND session_year='$session_year'
                                AND dept_id='prep'
                                  
                                ");
        //echo $this->db->last_query();die();
        $result=$query->result();
      // print_r($result);
        return $result;
    }
    function get_session_years()
    {
    	$this->load->database();
        	$query=$this->db->query("SELECT DISTINCT session_year 
		                        FROM reg_regular_form  group by session_year
								");
        	$result=$query->result();

        	
        	// print_r($result);
        	return $result;
    }
    function get_sessions()
    {
    	$this->load->database();
        	$query=$this->db->query("SELECT DISTINCT session
		                        FROM reg_regular_form  
								");
        	$result=$query->result();

        	
        	// print_r($result);
        	return $result;
    }

    function get_sections($session_year,$group)
    {
    	$this->load->database();
        	$query=$this->db->query("SELECT DISTINCT section
		                        FROM section_group_rel
		                        WHERE session_year='$session_year'
		                        AND `group`='$group'
		                        ");

        	$result=$query->result();

        	
        	//print_r($result);
        	return $result;
    }

    function get_mapped_subjects_contact_hrs($sem,$aggr_id,$dept_id, $session, $session_year){
        $query = $this->db->query("SELECT  DISTINCT course_structure.id, contact_hours
        FROM course_structure 
        INNER JOIN dept_course ON dept_course.aggr_id = course_structure.aggr_id 

        INNER Join subjects ON course_structure.id = subjects.id 
        INNER JOIN subject_mapping ON course_structure.aggr_id = subject_mapping.aggr_id
        INNER JOIN subject_mapping_des ON subject_mapping.map_id = subject_mapping_des.map_id AND subjects.id = subject_mapping_des.sub_id
        
        WHERE  course_structure.aggr_id = '$aggr_id' AND dept_course.dept_id = '$dept_id' AND subject_mapping.session='$session' AND
        subject_mapping.session_year='$session_year' AND subject_mapping.semester='$sem'");

        $hours = 0;
        foreach ($query->result() as $c) {
            # code...
            $hours = $hours + $c->contact_hours;
        }


         return $hours;

    }


    function get_mapped_subjects_and_contact_hrs_by_sem_and_dept($sem,$aggr_id,$dept_id, $session, $session_year)
    {
        // $query = $this->db->query("SELECT course_structure.aggr_id,course_structure.id,course_structure.semester 
        // FROM course_structure 
        // INNER JOIN dept_course ON dept_course.aggr_id = course_structure.aggr_id 
        // WHERE semester = '$sem' AND course_structure.aggr_id = '$aggr_id' AND dept_course.dept_id = '$dept_id' AND ceil(sequence)=sequence 
        // ORDER BY 
        // cast(SUBSTRING_INDEX(`sequence`, '.', 1) as decimal) asc, 
        // cast(SUBSTRING_INDEX(`sequence`, '.', -1) as decimal) asc");
        // //$this->db->order_by("sequence","ASC");
        // //$query = $this->db->get_where($this->table_course_structure,array('semester'=>$sem, 'aggr_id'=>$aggr_id));
        // return $query->result();


        //complete this 

        // $q = $this->db->query("SELECT DISTINCT course_structure.id, course_structure.aggr_id, course_structure.semester
        // FROM course_structure 
        // INNER JOIN dept_course ON dept_course.aggr_id = course_structure.aggr_id 

        // INNER Join subjects ON course_structure.id = subjects.id 
        // INNER JOIN subject_mapping ON course_structure.aggr_id = subject_mapping.aggr_id
        // INNER JOIN subject_mapping_des ON subject_mapping.map_id = subject_mapping_des.map_id AND subjects.id = subject_mapping_des.sub_id
        
        // WHERE  course_structure.aggr_id = '$aggr_id' AND dept_course.dept_id = '$dept_id' AND subject_mapping.session='Winter' AND
        // subject_mapping.session_year='2018-2019' AND subject_mapping.semester='$sem'");

        //  return $query->result();



         $query = $this->db->query("SELECT  DISTINCT course_structure.id, course_structure.aggr_id ,course_structure.semester, name, sequence, contact_hours, subject_mapping.map_id
        FROM course_structure 
        INNER JOIN dept_course ON dept_course.aggr_id = course_structure.aggr_id 

        INNER Join subjects ON course_structure.id = subjects.id 
        INNER JOIN subject_mapping ON course_structure.aggr_id = subject_mapping.aggr_id
        INNER JOIN subject_mapping_des ON subject_mapping.map_id = subject_mapping_des.map_id AND subjects.id = subject_mapping_des.sub_id
        
        WHERE  course_structure.aggr_id = '$aggr_id' AND dept_course.dept_id = '$dept_id' AND subject_mapping.session='$session' AND
        subject_mapping.session_year='$session_year' AND subject_mapping.semester='$sem'");


         return $query->result();
    



    }
    function get_mapped_subjects_and_contact_hrs_by_sem_and_dept_summer($sem,$aggr_id,$dept_id, $session, $session_year)
    {
       
        $query = $this->db->query("SELECT  DISTINCT course_structure.id, course_structure.aggr_id ,course_structure.semester, name, sequence,lecture*2 as contact_hours, subject_mapping.map_id
        FROM course_structure 
        INNER JOIN dept_course ON dept_course.aggr_id = course_structure.aggr_id 
        INNER Join subjects ON course_structure.id = subjects.id 
        INNER JOIN subject_mapping ON course_structure.aggr_id = subject_mapping.aggr_id
        INNER JOIN subject_mapping_des ON subject_mapping.map_id = subject_mapping_des.map_id AND subjects.id = subject_mapping_des.sub_id
        WHERE  course_structure.aggr_id = '$aggr_id' AND dept_course.dept_id = '$dept_id' AND subject_mapping.session='$session' AND
        subject_mapping.session_year='$session_year' AND subject_mapping.semester='$sem'");
        return $query->result();
    
    }
    function cbcs_get_offered_subjects_by_sem_and_dept($session, $session_year, $dept_id, $course_id, $branch_id, $sem){

        $q = $this->db->query("SELECT distinct sub_code, sub_name, contact_hours, sub_type FROM cbcs_subject_offered WHERE session_year='$session_year' AND SESSION='$session' AND dept_id='$dept_id' AND course_id='$course_id' AND branch_id='$branch_id' AND semester='$sem'");

        return $q->result();
    }
    function cbcs_get_offered_subjects_by_sem_and_dept_summer($session, $session_year, $dept_id, $course_id, $branch_id, $sem){

        $q = $this->db->query("SELECT distinct sub_code, sub_name, lecture*2 as contact_hours, sub_type FROM cbcs_subject_offered WHERE session_year='$session_year' AND SESSION='$session' AND dept_id='$dept_id' AND course_id='$course_id' AND branch_id='$branch_id' AND semester='$sem'");

        return $q->result();
    }
    function cbcs_get_offered_subjects_by_sem_group_and_dept_summer($session, $session_year, $dept_id, $course_id, $branch_id, $sem, $group){

        $q = $this->db->query("SELECT sub_code, sub_name, lecture*2 as contact_hours, sub_type FROM cbcs_subject_offered WHERE session_year='$session_year' AND SESSION='$session' AND dept_id='$dept_id' AND course_id='$course_id' AND branch_id='$branch_id' AND semester='$sem' AND sub_group='$group'");

        return $q->result();
    }

     function cbcs_get_offered_subjects_by_sem_group_and_dept($session, $session_year, $dept_id, $course_id, $branch_id, $sem, $group){

        $q = $this->db->query("SELECT sub_code, sub_name, contact_hours, sub_type FROM cbcs_subject_offered WHERE session_year='$session_year' AND SESSION='$session' AND dept_id='$dept_id' AND course_id='$course_id' AND branch_id='$branch_id' AND semester='$sem' AND sub_group='$group'");

        return $q->result();
    }
    
    function old_get_offered_subjects_by_sem_and_dept_summer($session, $session_year, $dept_id, $course_id, $branch_id, $sem){

        $q = $this->db->query("SELECT distinct sub_code, sub_name, lecture*2 as contact_hours, sub_type FROM old_subject_offered WHERE session_year='$session_year' AND SESSION='$session' AND dept_id='$dept_id' AND course_id='$course_id' AND branch_id='$branch_id' AND semester='$sem'");

        return $q->result();
    }

    function old_get_offered_subjects_by_sem_and_dept($session, $session_year, $dept_id, $course_id, $branch_id, $sem){

        $q = $this->db->query("SELECT distinct sub_code, sub_name, contact_hours, sub_type FROM old_subject_offered WHERE session_year='$session_year' AND SESSION='$session' AND dept_id='$dept_id' AND course_id='$course_id' AND branch_id='$branch_id' AND semester='$sem'");

        return $q->result();
    }
    function old_get_offered_subjects_by_sem_and_dept_prep_summer($session, $session_year, $dept_id, $course_id, $branch_id, $sem){

        $q = $this->db->query("SELECT distinct sub_code, sub_name, lecture*2 as contact_hours, sub_type FROM old_subject_offered WHERE session_year='$session_year' AND SESSION='$session' AND dept_id='$dept_id' AND course_id='$course_id' AND branch_id='$branch_id' AND semester='$sem'");

        return $q->result();
    }
	
	 function old_get_offered_subjects_by_sem_and_dept_prep($session, $session_year, $dept_id, $course_id, $branch_id, $sem){

        $q = $this->db->query("SELECT distinct sub_code, sub_name, contact_hours, sub_type FROM old_subject_offered WHERE session_year='$session_year' AND SESSION='$session' AND dept_id='$dept_id' AND course_id='$course_id' AND branch_id='$branch_id' AND semester='$sem'");

        return $q->result();
    }


    function get_subjects_by_sem_and_dept($sem,$aggr_id,$dept_id)
	{
		$query = $this->db->query("SELECT course_structure.aggr_id,course_structure.id,course_structure.semester 
        FROM course_structure 
		INNER JOIN dept_course ON dept_course.aggr_id = course_structure.aggr_id 
		WHERE semester = '$sem' AND course_structure.aggr_id = '$aggr_id' AND dept_course.dept_id = '$dept_id' AND ceil(sequence)=sequence 
		ORDER BY 
		cast(SUBSTRING_INDEX(`sequence`, '.', 1) as decimal) asc, 
		cast(SUBSTRING_INDEX(`sequence`, '.', -1) as decimal) asc");
		//$this->db->order_by("sequence","ASC");
		//$query = $this->db->get_where($this->table_course_structure,array('semester'=>$sem, 'aggr_id'=>$aggr_id));
		return $query->result();
    



	}

    function cbcs_get_offered_subjects_contact_hrs_summer($sem,$course_id, $branch_id ,$dept_id, $session, $session_year){
        $query = $this->db->query("SELECT lecture FROM cbcs_subject_offered WHERE SESSION='$session' and session_year='$session_year' AND dept_id='$dept_id' AND course_id='$course_id' AND branch_id='$branch_id' AND semester='$sem'");

        // $hours = 0;
        // foreach ($query->result() as $c) {
        //     # code...
        //     $hours = $hours + $c->contact_hours;
        // }


         return $query->result();

    }

 function cbcs_get_offered_subjects_contact_hrs($sem,$course_id, $branch_id ,$dept_id, $session, $session_year){
        $query = $this->db->query("SELECT SUM(contact_hours) as ch FROM cbcs_subject_offered WHERE SESSION='$session' and session_year='$session_year' AND dept_id='$dept_id' AND course_id='$course_id' AND branch_id='$branch_id' AND semester='$sem'");

        // $hours = 0;
        // foreach ($query->result() as $c) {
        //     # code...
        //     $hours = $hours + $c->contact_hours;
        // }


         return $query->result()[0]->ch;

    }
    function cbcs_get_offered_subjects_contact_hrs_comm_group_summer($sem,$course_id, $branch_id ,$dept_id, $session, $session_year, $group){
        $query = $this->db->query("SELECT lecture  FROM cbcs_subject_offered WHERE SESSION='$session' and session_year='$session_year' AND dept_id='$dept_id' AND course_id='$course_id' AND branch_id='$branch_id' AND semester='$sem' AND sub_group='$group'");

        // $hours = 0;
        // foreach ($query->result() as $c) {
        //     # code...
        //     $hours = $hours + $c->contact_hours;
        // }


        return $query->result();

    }

     function cbcs_get_offered_subjects_contact_hrs_comm_group($sem,$course_id, $branch_id ,$dept_id, $session, $session_year, $group){
        $query = $this->db->query("SELECT SUM(contact_hours) as ch FROM cbcs_subject_offered WHERE SESSION='$session' and session_year='$session_year' AND dept_id='$dept_id' AND course_id='$course_id' AND branch_id='$branch_id' AND semester='$sem' AND sub_group='$group'");

        // $hours = 0;
        // foreach ($query->result() as $c) {
        //     # code...
        //     $hours = $hours + $c->contact_hours;
        // }


         return $query->result()[0]->ch;

    }
    function old_get_offered_subjects_contact_hrs_summer($sem,$course_id, $branch_id ,$dept_id, $session, $session_year){
        $query = $this->db->query("SELECT lecture FROM old_subject_offered WHERE SESSION='$session' and session_year='$session_year' AND dept_id='$dept_id' AND course_id='$course_id' AND branch_id='$branch_id' AND semester='$sem'");

        // $hours = 0;
        // foreach ($query->result() as $c) {
        //     # code...
        //     $hours = $hours + $c->contact_hours;
        // }


        return $query->result();

    }

    function old_get_offered_subjects_contact_hrs($sem,$course_id, $branch_id ,$dept_id, $session, $session_year){
        $query = $this->db->query("SELECT SUM(contact_hours) as ch FROM old_subject_offered WHERE SESSION='$session' and session_year='$session_year' AND dept_id='$dept_id' AND course_id='$course_id' AND branch_id='$branch_id' AND semester='$sem'");

        // $hours = 0;
        // foreach ($query->result() as $c) {
        //     # code...
        //     $hours = $hours + $c->contact_hours;
        // }


         return $query->result()[0]->ch;

    }
    function get_subjects_and_contact_hrs_by_sem_and_dept_summer($sem,$aggr_id,$dept_id)
    {
       
        $query = $this->db->query("SELECT course_structure.aggr_id,course_structure.id, contact_hours,course_structure.semester
        FROM course_structure 
        INNER JOIN dept_course ON dept_course.aggr_id = course_structure.aggr_id 

        INNER Join subjects ON course_structure.id = subjects.id 
        WHERE semester = '$sem' AND course_structure.aggr_id = '$aggr_id' AND dept_course.dept_id = '$dept_id' AND ceil(sequence)=sequence 
        ORDER BY 
        cast(SUBSTRING_INDEX(`sequence`, '.', 1) as decimal) asc, 
        cast(SUBSTRING_INDEX(`sequence`, '.', -1) as decimal) asc");
        return $query->result();
    }

    function get_subjects_and_contact_hrs_by_sem_and_dept($sem,$aggr_id,$dept_id)
    {
        // $query = $this->db->query("SELECT course_structure.aggr_id,course_structure.id, subjects.contact_hours,course_structure.semester 
        // FROM course_structure 
        // INNER JOIN dept_course ON dept_course.aggr_id = course_structure.aggr_id 

        // INNER Join subjects ON course_structure.id = subjects.id 
        // WHERE semester = '$sem' AND course_structure.aggr_id = '$aggr_id' AND dept_course.dept_id = '$dept_id' AND ceil(sequence)=sequence 
        // ORDER BY 
        // cast(SUBSTRING_INDEX(`sequence`, '.', 1) as decimal) asc, 
        // cast(SUBSTRING_INDEX(`sequence`, '.', -1) as decimal) asc");
        

        //$this->db->order_by("sequence","ASC");
        //$query = $this->db->get_where($this->table_course_structure,array('semester'=>$sem, 'aggr_id'=>$aggr_id));



        $query = $this->db->query("SELECT course_structure.aggr_id,course_structure.id, contact_hours,course_structure.semester
        FROM course_structure 
        INNER JOIN dept_course ON dept_course.aggr_id = course_structure.aggr_id 

        INNER Join subjects ON course_structure.id = subjects.id 
        WHERE semester = '$sem' AND course_structure.aggr_id = '$aggr_id' AND dept_course.dept_id = '$dept_id' AND ceil(sequence)=sequence 
        ORDER BY 
        cast(SUBSTRING_INDEX(`sequence`, '.', 1) as decimal) asc, 
        cast(SUBSTRING_INDEX(`sequence`, '.', -1) as decimal) asc");



        return $query->result();
    }

    function get_total_contact_hrs_by_sem_and_dept($sem,$aggr_id,$dept_id, $group = '')
    {  
        if($dept_id == 'comm'){
            $semgroup = $sem."_".$group;
            // print_r($semgroup);


            $query = $this->db->query("SELECT sum(contact_hours) as total_contact_hours
        FROM course_structure 
        INNER JOIN dept_course ON dept_course.aggr_id = course_structure.aggr_id 

        INNER Join subjects ON course_structure.id = subjects.id 
        WHERE semester = '$semgroup' AND course_structure.aggr_id = '$aggr_id' AND dept_course.dept_id = '$dept_id' 
       
        ");
        }
        else{
        $query = $this->db->query("SELECT sum(contact_hours) as total_contact_hours
        FROM course_structure 
        INNER JOIN dept_course ON dept_course.aggr_id = course_structure.aggr_id 

        INNER Join subjects ON course_structure.id = subjects.id 
        WHERE semester = '$sem' AND course_structure.aggr_id = '$aggr_id' AND dept_course.dept_id = '$dept_id' 
        AND ceil(sequence)=sequence 
        ORDER BY 
        cast(SUBSTRING_INDEX(`sequence`, '.', 1) as decimal) asc, 
        cast(SUBSTRING_INDEX(`sequence`, '.', -1) as decimal) asc

       ");
        }


        return $query->result()[0];
    }





	function select_elective_offered_by_aggr_id($aggr_id,$semester,$sy,$s)
	{
         
		$query = $this->db->query("SELECT optional_offered.aggr_id,optional_offered.id,course_structure.semester,subjects.name,subjects.subject_id   
                                    FROM optional_offered INNER JOIN course_structure ON course_structure.id = optional_offered.id INNER JOIN subjects ON subjects.id=course_structure.id 
                                    WHERE optional_offered.aggr_id = '$aggr_id' 
                                    AND course_structure.semester = '$semester' 
                                   AND optional_offered.session_year='$sy'
                                   AND optional_offered.session='$s'
                                   AND course_structure.id 
                                    order by sequence 
                                    ");
               // echo $this->db->last_query();
			return $query->result();	
	}





    function get_departments()
    {
    	$this->load->database();
      	$query=$this->db->query("SELECT DISTINCT name 
		                        FROM departments 
								WHERE type='academic'
								");
        $result=$query->result();
        	//print_r($result);
        return $result;
    }

	function insert_mapping_des($data,$option=null){
	  //print_r($data1).'</br>' ;
            if($option=='bunch'){
            if($this->db->insert_batch($this->sub_m_des,$data))
			return true;
			else
			return false; 
            }else
            {
	   		if($this->db->insert($this->sub_m_des,$data))
				return true;
			else
			return false; 
            }           
           //echo $this->db->_error_message();
	   }
	   
	function getMappingByYear($y="",$s='',$dept=''){
		
				if($y && $s){
				$b=$this->db->get_where($this->sub_mapping,array('session_year'=>$y,'session'=>$s,'course_id <>'=>'comm','dept_id'=>$dept));
                                }else if($dept){
                                    $b=$this->db->get_where($this->sub_mapping,array('dept_id'=>$dept, 'course_id <>'=>'comm'));
                                }else{
				$b=$this->db->get($this->sub_mapping);
                                }
				if($b->num_rows()>0)
				return $b->result();
		}
	function getCommSubjectByYear($y="",$s=""){
            if($y && $s){
                $b=$this->db->get_where($this->sub_mapping,array('session_year'=>$y,'session'=>$s,'course_id'=>'comm','branch_id'=>'comm'));
            }else{
				$b=$this->db->get_where($this->sub_mapping,array('course_id'=>'comm','branch_id'=>'comm'));
			}
		
		if($b->num_rows()>0)
				return $b->result();
	}
	

	 function get_jrf_details($dept, $session, $session_year) 
    {
        $sql = "select a.sub_id,c.name,d.aggr_id,c.subject_id,e.dept_id,f.emp_no 
                from  reg_exam_rc_subject as a 
                join reg_exam_rc_form as b 
                on a.form_id=b.form_id 
                join subjects as c 
                on a.sub_id=c.id
                join course_structure as d 
                on a.sub_id=d.id
                left join dept_course as e 
                on d.aggr_id=e.aggr_id 
                where b.course_id='jrf' 
                and b.session_year='".$session_year."'
                and b.`session`='".$session."'
                and e.dept_id='".$dept."'
                group by a.sub_id
                order by e.dept_id,c.name asc  ";

        $query = $this->db->query($sql);
        
        if ($this->db->affected_rows() >= 0) 
        {
            return $query->result();
        } 
        else 
        {
            return false;
        }
    }

	function checkJRFmappingSpecific($sy, $session, $dept_id,$sub_id,$course_id,$branch_id)
    {
    	$this->load->database();
        	  $q=$this->db->query("SELECT a.map_id
                  FROM subject_mapping as a INNER JOIN subject_mapping_des as b
                  WHERE a.map_id=b.map_id
                  AND session_year = '$sy' AND session = '$session' AND dept_id='$dept_id' AND course_id='$course_id' AND branch_id='$branch_id' 
                  AND sub_id='$sub_id' 
                  ");

        	  // print_r($q->result());

        	  if($q->num_rows > 0){
        	$temp = ($q->result());
        	return $temp[0]->map_id;
        } else {
        	return -1;
        }
    }

    function find_mapping_by_other_jrf($sy, $session, $dept_id,$sub_id,$course_id,$branch_id){
$this->load->database();
        	$q=$this->db->query("SELECT a.map_id
		                        FROM subject_mapping as a INNER JOIN subject_mapping_des as b 
								WHERE a.map_id=b.map_id 
								AND dept_id='$dept_id' 
        						AND session='$session' 
        						AND session_year='$sy' 
        						AND sub_id='$sub_id' 
								");
        	 // $result=$query->result();
        	   // print_r($q->num_rows());
        	// return $result[0]->map_id;
        	if($q->num_rows > 0){
        	$temp = ($q->result());
        	return $temp[0]->map_id;
        	} else {
        	return -1;
        	}
}

    function find_mapping_by_other_jrf_core($sy, $session, $dept_id,$sub_id,$course_id,$branch_id){
$this->load->database();
            $q=$this->db->query("SELECT a.map_id
                                FROM subject_mapping as a INNER JOIN subject_mapping_des as b 
                                WHERE a.map_id=b.map_id 
                                AND session='$session' 
                                AND session_year='$sy' 
                                AND sub_id='$sub_id' 
                                AND semester=-1 
                                AND aggr_id='core' 
                                AND course_id='$course_id' 
                                AND branch_id='$branch_id'
                                ");
             // $result=$query->result();
               // print_r($q->num_rows());
            // return $result[0]->map_id;
            if($q->num_rows > 0){
            $temp = ($q->result());
            return $temp;
            } else {
            return 0;
            }
}



	function find_mapping_by_other_c($row){
		
			$q=$this->db->select('map_id')->get_where($this->sub_mapping,array('session'=>$row->session,'session_year'=>$row->session_year,'course_id'=>$row->course_id,'branch_id'=>$row->branch_id,'semester'=>$row->semester,'aggr_id'=>$row->course_aggr_id,'group'=>$row->group,'section'=>$row->section));
        if($q->num_rows > 0){
        	$temp = ($q->result());
        	return $temp[0]->map_id;
        } else {
        	return -1;
        }
		
		
	}


	function checkExisting($sy,$course, $branch, $semester,$aggr_id,$group='',$section=""){
		
                if($group){
                    $q=$this->db->get_where($this->sub_mapping,array('session_year'=>$sy,'course_id'=>$course,'branch_id'=>$branch,'semester'=>$semester,'aggr_id'=>$aggr_id, 'group'=>$group,'section'=>$section));
                }else{
                $q=$this->db->get_where($this->sub_mapping,array('session_year'=>$sy,'course_id'=>$course,'branch_id'=>$branch,'semester'=>$semester,'aggr_id'=>$aggr_id));
                
                }
				
				if($q->num_rows > 0)
					return $q->row()->map_id;
			
				return false;
						
	}
	function checkExistingjrf($sy,$s,$course,$subId){
		
                
                $q=$this->db->query("select * from subject_mapping_des a join subject_mapping b on a.map_id=b.map_id where a.sub_id=? and b.session_year=? and b.`session`=? and b.course_id=?",array($subId,$sy,$s,$course));
                
                
				//echo $this->db->last_query();
				if($q->num_rows > 0)
					return $q->row()->map_id;
			
				return false;
						
	}

	function checkExistingSemester($session,$course, $branch,$department){
		 
		$re=$this->db->select('semester')->get_where($this->sub_mapping,array('session_year'=>$session,'course_id'=>$course,'branch_id'=>$branch,'dept_id'=>$department));
		//	var_dump($this->db);
			if($re->num_rows > 0)
				return $re->result();
			
			return false;
	}
	
	function getMappingById($id){
		$b=$this->db->get_where($this->sub_mapping,array('map_id'=>$id));
		return $b->result_array();
	}

	function getMappingDesByIdAndSubid($id,$sub_id)
	{
		$b=$this->db->get_where($this->sub_m_des,array('map_id'=>$id,'sub_id'=>$sub_id));
		return $b->result_array();
	}
	
	function getMappingDesById($id,$param=null){
		//echo 12; die;
	if($param==null)	
            $b=$this->db->get_where($this->sub_m_des,array('map_id'=>$id));
                else {
                $b= $this->db
              ->select('sub_id,id,map_id,emp_no,coordinator,M')
                         ->where(array('map_id'=>$id))
              ->group_by('sub_id')              
              ->get($this->sub_m_des);
                }
		return $b->result_array();
                
	}
	
	function deleteMappingById($id){
			
			$this->db->delete($this->sub_m_des,array('map_id'=>$id));	
			$this->db->delete($this->sub_mapping,array('map_id'=>$id));
			return true;
	}
	
	function updateDMapping($mapId,$subId,$teacherId,$data){
		$q=$this->db->update($this->sub_m_des, $data, array('map_id' =>$mapId,'sub_id'=>$subId,'emp_no'=>$teacherId));
		
                if($q)
		return true;

		return false;	
	}
	function getSubjectforAddTeacherBymapId(){
		
		$this->db->get_where($this->sub_m_des,array('map_id'=>$id,'M'=>'1'))->result();
	}
	
	function delMapDes($mid,$subid,$t){
		
		$this->db->delete($this->sub_m_des,array('map_id'=>$mid,'sub_id'=>$subid,'emp_no'=>$t,'M'=>'0'));
		return true;
		
	}
        
        function getCourseDurationById($id){
            
           $q=$this->db->get_where($this->course, array('id'=>$id)); 
           if($q->row()->duration)
               $sem = ($q->row()->duration * 2);
           return $sem;
        
        }
	function getGroup(){
		$q=$this->db->query("select distinct(`group`) from section_group_rel");
		if($q->num_rows() > 0){
			return $q->result();
		}
		return false;
	}
	
	function getSectionByGroup($g){
		$qu="select section from section_group_rel where `group`=?";
		$q=$this->db->query($qu,array($g));
		if($q->num_rows() > 0){
			return $q->result();
		}
		return false;
	}
        
        function getAggr_id($dept,$course,$branch){
           $q= $this->db->query("select aggr_id from dept_course as a join course_branch as b on a.course_branch_id=b.course_branch_id where a.dept_id=? 
and b.course_id=? 
and b.branch_id=?",array($dept,$course,$branch));
           
           if($q->num_rows() >0){
               return $q->result();
           }
           return false;
        }


        //////////////////////////////
        //                          //
        //  functions for summer    //
        //                          //
        //////////////////////////////

     //    function get_details_regular_summer($session_year,$dept_id){

            
     //     $this->load->database();
     //     $query=$this->db->query("SELECT DISTINCT a.semester,a.branch_id,a.course_id,b.course_aggr_id,a.session_year,'Summer' as session 
     //                             FROM reg_summer_form as a INNER JOIN reg_regular_form as b  
     //                             WHERE a.admn_no=b.admn_no 
     //                             AND b.session_year=a.session_year
     //                             AND b.branch_id=a.branch_id 
     //                             AND b.course_id=a.course_id 
     //                             AND a.session_year='$session_year'   
                    //              AND b.course_aggr_id 
                    //              IN (SELECT DISTINCT aggr_id 
                    //                  FROM dept_course 
                    //                  WHERE dept_id='$dept_id'  
                    //                  AND aggr_id NOT LIKE 'comm%')

                    //          ");
     //    $result=$query->result();
     // print_r($result);
     //    return $result;

     //    }

                function get_details_regular_summer($session_year,$dept_id){

            
            $this->load->database();
         /*   $query=$this->db->query("SELECT DISTINCT c.semester,c.aggr_id as course_aggr_id,a.branch_id,a.course_id,a.session_year,a.session 
                                    FROM `reg_summer_form` a JOIN `reg_summer_subject` b ON a.form_id=b.form_id 
                                    JOIN `course_structure` c ON b.sub_id=c.id 
                                    JOIN `subjects` d ON b.sub_id=d.id 
                                    WHERE session_year='$session_year' 
                                    AND a.hod_status='1' 
                                    AND c.aggr_id 
                                    IN ( SELECT DISTINCT aggr_id 
                                        FROM `dept_course` 
                                        WHERE dept_id='$dept_id' 
                                        AND aggr_id NOT LIKE 'comm%')

                                ");*/
        $query=$this->db->query("SELECT DISTINCT c.semester,c.aggr_id AS course_aggr_id,SUBSTRING_INDEX(SUBSTRING_INDEX(c.aggr_id,'_',2),'_',-1 )as branch_id, 
            a.branch_id as branch_id_manipulated, 
SUBSTRING_INDEX(c.aggr_id,'_',1) AS course_id,a.session_year,a.session
FROM `reg_summer_form` a
JOIN `reg_summer_subject` b ON a.form_id=b.form_id
JOIN `course_structure` c ON b.sub_id=c.id
JOIN `subjects` d ON b.sub_id=d.id
WHERE session_year='".$session_year."' AND a.hod_status='1' AND c.aggr_id IN (
SELECT DISTINCT aggr_id
FROM `dept_course`
WHERE dept_id='".$dept_id."' AND aggr_id NOT LIKE 'comm%');");
            //echo $this->db->last_query();die();
        $result=$query->result();
     // print_r($result);
        return $result;

        }
         function get_subjects_by_sem_and_dept_summer($session_year,$semester,$course_id,$branch_id,$agg_id=null)
    {
             if($agg_id<>null) $replace=    " AND c.aggr_id='$agg_id' /*and a.semester=c.semester*/ ";
			 if($course_id<>'honour' && $course_id<>'minor'){
			 
        $query = $this->db->query("SELECT DISTINCT b.sub_id as id,a.session_year,c.semester,a.course_id,a.branch_id  
                                    FROM `reg_summer_form` a JOIN `reg_summer_subject` b ON a.form_id=b.form_id 
                                    JOIN `course_structure` c ON b.sub_id=c.id 
                                    JOIN `subjects` d ON b.sub_id=d.id 
                                    WHERE a.session_year='$session_year' 
                                    AND c.semester='$semester' 
                                    AND a.course_id='$course_id' 
                                    AND a.branch_id='$branch_id' 
                                    AND a.hod_status='1' 
                                    ". $replace."
                                   
                                    ");
									
			 }	
         else if($course_id=='honour'|| $course_id=='minor'){			 
		  $query = $this->db->query("SELECT DISTINCT b.sub_id as id,a.session_year,c.semester,a.course_id,a.branch_id  
                                    FROM `reg_summer_form` a JOIN `reg_summer_subject` b ON a.form_id=b.form_id 
                                    JOIN `course_structure` c ON b.sub_id=c.id 
                                    JOIN `subjects` d ON b.sub_id=d.id 
                                    WHERE a.session_year='$session_year' 
                                    AND c.semester='$semester' 
                                /*    AND a.course_id='$course_id' 
                                    AND a.branch_id='$branch_id' */
                                    AND a.hod_status='1' 
                                    ". $replace."
                                  
                                    ");
									
		 }
          // print_r($query->result());
     
        //$this->db->order_by("sequence","ASC");
        //$query = $this->db->get_where($this->table_course_structure,array('semester'=>$sem, 'aggr_id'=>$aggr_id));
      //  echo $this->db->last_query();die();
        return $query->result();
    }
   /* function get_subjects_by_sem_and_dept_regular($session_year,$semester,$course_id,$branch_id,$aggr_id)
    {
             //if($agg_id<>null) $replace=    " AND c.aggr_id='$agg_id' ";
        $query = $this->db->query("(SELECT DISTINCT c.id,a.session_year,c.semester,a.course_id,a.branch_id
FROM `reg_regular_form` a
JOIN `course_structure` c ON c.aggr_id=a.course_aggr_id and a.semester=c.semester
JOIN `subjects` d ON d.id=c.id
WHERE a.session_year='".$session_year."' AND c.semester='".$semester."' AND a.course_id='".$course_id."' AND a.branch_id='".$branch_id."' AND a.course_aggr_id='".$aggr_id."' AND a.hod_status='1' AND d.elective = '0')
                 union     

(SELECT DISTINCT c.id,a.session_year,c.semester,a.course_id,a.branch_id
FROM `reg_regular_form` a
JOIN `course_structure` c ON c.aggr_id=a.course_aggr_id and a.semester=c.semester
JOIN `subjects` d ON d.id=c.id
INNER JOIN  reg_regular_elective_opted opt ON opt.form_id=a.form_id  and opt.sub_id=d.id
WHERE a.session_year='".$session_year."' AND c.semester='".$semester."' AND a.course_id='".$course_id."' AND a.branch_id='".$branch_id."' AND a.course_aggr_id='".$aggr_id."' AND a.hod_status='1' AND d.elective <> '0')  ");
          // print_r($query->result());
     
        //$this->db->order_by("sequence","ASC");
        //$query = $this->db->get_where($this->table_course_structure,array('semester'=>$sem, 'aggr_id'=>$aggr_id));
      //  echo $this->db->last_query();die();
        return $query->result();
    }*/
	
	 function get_subjects_by_sem_and_dept_regular($session_year,$semester,$course_id,$branch_id,$aggr_id)
    {
             //if($agg_id<>null) $replace=    " AND c.aggr_id='$agg_id' ";
        
        if($course_id<>'honour' && $course_id<>'minor'){
        $query = $this->db->query("(SELECT DISTINCT c.id,a.session_year,c.semester,a.course_id,a.branch_id
FROM `reg_regular_form` a
JOIN `course_structure` c ON c.aggr_id=a.course_aggr_id and a.semester=c.semester
JOIN `subjects` d ON d.id=c.id
WHERE a.session_year='".$session_year."' AND c.semester='".$semester."' AND a.course_id='".$course_id."' AND a.branch_id='".$branch_id."' AND a.course_aggr_id='".$aggr_id."' AND a.hod_status='1' AND d.elective = '0')
                 union     

(SELECT DISTINCT c.id,a.session_year,c.semester,a.course_id,a.branch_id
FROM `reg_regular_form` a
JOIN `course_structure` c ON c.aggr_id=a.course_aggr_id and a.semester=c.semester
JOIN `subjects` d ON d.id=c.id
INNER JOIN  reg_regular_elective_opted opt ON opt.form_id=a.form_id  and opt.sub_id=d.id
WHERE a.session_year='".$session_year."' AND c.semester='".$semester."' AND a.course_id='".$course_id."' AND a.branch_id='".$branch_id."' AND a.course_aggr_id='".$aggr_id."' AND a.hod_status='1' AND d.elective <> '0')  ");
        }
         else if($course_id=='honour'|| $course_id=='minor'){
        $query = $this->db->query("(SELECT DISTINCT c.id,a.session_year,c.semester,a.course_id,a.branch_id
FROM `reg_regular_form` a
JOIN `course_structure` c ON c.aggr_id='".$aggr_id."'   and a.semester=c.semester
JOIN `subjects` d ON d.id=c.id
WHERE a.session_year='".$session_year."' AND c.semester='".$semester."'  AND a.hod_status='1' AND d.elective = '0')
                 union     

(SELECT DISTINCT c.id,a.session_year,c.semester,a.course_id,a.branch_id
FROM `reg_regular_form` a
JOIN `course_structure` c ON c.aggr_id='".$aggr_id."'  and a.semester=c.semester
JOIN `subjects` d ON d.id=c.id
INNER JOIN  reg_regular_elective_opted opt ON opt.form_id=a.form_id  and opt.sub_id=d.id
WHERE a.session_year='".$session_year."' AND c.semester='".$semester."'  AND a.hod_status='1' AND d.elective <> '0')  ");
        }     
        
        
// print_r($query->result());
     
        //$this->db->order_by("sequence","ASC");
        //$query = $this->db->get_where($this->table_course_structure,array('semester'=>$sem, 'aggr_id'=>$aggr_id));
      //  echo $this->db->last_query();die();
        return $query->result();
    }
	
	
	
	
	//common
	function get_subjects_by_sem_and_dept_regular_comm($section ,$grp,$session_year,$semester,$course_id,$branch_id,$aggr_id)
    {
         //echo $section;echo $grp; echo $session_year; echo $semester; echo $course_id; echo $branch_id; echo $aggr_id;die();
             //if($agg_id<>null) $replace=    " AND c.aggr_id='$agg_id' ";
        $query = $this->db->query("(SELECT DISTINCT c.id,a.session_year,c.semester,'comm' as course_id,'comm' as branch_id
FROM `reg_regular_form` a
JOIN `course_structure` c ON c.aggr_id=a.course_aggr_id  AND concat_ws('_',a.semester,a.section)=c.semester
join `stu_section_data` e on e.admn_no=a.admn_no and e.session_year=a.session_year 
JOIN `subjects` d ON d.id=c.id
WHERE a.session_year='".$session_year."' AND c.semester='".$semester."_".$grp."'    and e.section='".$section."' /*AND a.course_id='".$course_id."' AND a.branch_id='".$branch_id."'*/ AND a.course_aggr_id='".$aggr_id."' AND a.hod_status='1' AND d.elective = '0')
                 union     

(SELECT DISTINCT c.id,a.session_year,c.semester,'comm' as course_id,'comm' as branch_id
FROM `reg_regular_form` a
JOIN `course_structure` c ON c.aggr_id=a.course_aggr_id  AND concat_ws('_',a.semester,a.section)=c.semester
join `stu_section_data` e on e.admn_no=a.admn_no and e.session_year=a.session_year 
JOIN `subjects` d ON d.id=c.id
INNER JOIN  reg_regular_elective_opted opt ON opt.form_id=a.form_id  and opt.sub_id=d.id
WHERE a.session_year='".$session_year."' AND c.semester='".$semester."_".$grp."' and e.section='".$section."'/*AND a.course_id='".$course_id."' AND a.branch_id='".$branch_id."'*/ AND a.course_aggr_id='".$aggr_id."' AND a.hod_status='1' AND d.elective <> '0')  ");
          //print_r($query->result());
     
        //$this->db->order_by("sequence","ASC");
        //$query = $this->db->get_where($this->table_course_structure,array('semester'=>$sem, 'aggr_id'=>$aggr_id));
       //echo $this->db->last_query();die();
        return $query->result();
    }
	
	

    function get_subjects_by_sem_and_dept_summer_ele($session_year,$semester,$course_id,$branch_id,$agg_id=null)
    {
        
        if($agg_id<>null) $replace=    " AND c.aggr_id='$agg_id' ";
        $query = $this->db->query("SELECT DISTINCT b.sub_id as id,d.subject_id,a.session_year,c.semester,a.course_id,a.branch_id,d.name  
                                    FROM `reg_summer_form` a JOIN `reg_summer_subject` b ON a.form_id=b.form_id 
                                    JOIN `course_structure` c ON b.sub_id=c.id 
                                    JOIN `subjects` d ON b.sub_id=d.id 
                                    WHERE a.session_year='$session_year' 
                                    AND c.semester='$semester' 
                                    AND a.course_id='$course_id' 
                                    AND a.branch_id='$branch_id' 
                                    AND a.hod_status='1' 
                                 ". $replace."
                                    AND d.elective <> '0' 
                                    ");
          // print_r($query->result());
     
        //$this->db->order_by("sequence","ASC");
        //$query = $this->db->get_where($this->table_course_structure,array('semester'=>$sem, 'aggr_id'=>$aggr_id));
        return $query->result();
    }
                function get_subjects_by_sem_and_dept_summer_common($session_year,$dept_id,$semester_group){

            
            $this->load->database();
            $query=$this->db->query("SELECT DISTINCT b.sub_id as id,c.aggr_id as course_aggr_id,'comm' as branch_id,'comm' as course_id,a.session_year,a.session, 0 as section,SUBSTRING_INDEX(c.semester,'_',1) as `group`  
                                    FROM `reg_summer_form` a JOIN `reg_summer_subject` b ON a.form_id=b.form_id 
                                    JOIN `course_structure` c ON b.sub_id=c.id 
                                    JOIN `subjects` d ON b.sub_id=d.id 
                                    WHERE session_year='$session_year' 
                                    AND c.semester='$semester_group' 
                                    AND c.aggr_id LIKE 'comm%' 
                                    AND a.hod_status='1' 
                                    GROUP BY b.sub_id 
                                ");
        $result=$query->result();
		
		 //echo $this->db->last_query(); die();
     // print_r($result);
        return $result;

        }
       /* function get_details_common_summer($session_year,$dept_id){

              $this->load->database();
            $query=$this->db->query("SELECT DISTINCT SUBSTRING_INDEX(c.semester,'_',-1) as semester,c.aggr_id as course_aggr_id,'comm' branch_id,'comm' as course_id,a.session_year,a.session, 0 as section,SUBSTRING_INDEX(c.semester,'_',1) as `group`,c.semester as semester_group 
                                    FROM `reg_summer_form` a JOIN `reg_summer_subject` b ON a.form_id=b.form_id 
                                    JOIN `course_structure` c ON b.sub_id=c.id 
                                    JOIN `subjects` d ON b.sub_id=d.id 
                                    WHERE session_year='$session_year' 
                                    AND c.aggr_id LIKE 'comm%' 
                                    AND a.hod_status='1' 

                                ");
        $result=$query->result();
     // print_r($result);
        return $result;



        }*/
	function get_details_common_summer($session_year,$dept_id){

              $this->load->database();
       /*     $query=$this->db->query("SELECT DISTINCT SUBSTRING_INDEX(c.semester,'_',-1) as semester,c.aggr_id as course_aggr_id,'comm' branch_id,'comm' as course_id,a.session_year,a.session, 0 as section,SUBSTRING_INDEX(c.semester,'_',1) as `group`,c.semester as semester_group,sgr.section 
                                    FROM `reg_summer_form` a JOIN `reg_summer_subject` b ON a.form_id=b.form_id 
                                    JOIN `course_structure` c ON b.sub_id=c.id 
                                    JOIN `subjects` d ON b.sub_id=d.id 
                                    join  section_group_rel sgr on sgr.`group`= SUBSTRING_INDEX(c.semester,'_',1)
                                    WHERE a.session_year='$session_year' 
                                    AND c.aggr_id LIKE 'comm%' 
                                    AND a.hod_status='1' order by SUBSTRING_INDEX(c.semester,'_',-1),SUBSTRING_INDEX(c.semester,'_',1),sgr.section

                                ");
        * 
        */
              
    $query=$this->db->query("select x.*,group_concat(x.admn_no) as admn_list,  group_concat(x.subject_code separator ',') as subject_list from 
( SELECT SUBSTRING_INDEX(c.semester,'_',1) AS semester,c.aggr_id AS course_aggr_id,'comm' branch_id,'comm' AS course_id,
a.session_year,a.session, SUBSTRING_INDEX(c.semester,'_',-1) AS `group`, c.semester AS semester_group,a.admn_no, 
group_concat(distinct(b.sub_id) separator ',') as subject_code
,i.section
FROM `reg_summer_form` a
JOIN `reg_summer_subject` b ON a.form_id=b.form_id
JOIN `course_structure` c ON b.sub_id=c.id
JOIN `subjects` d ON b.sub_id=d.id 
 and a.session_year='$session_year' AND c.aggr_id LIKE 'comm%' AND a.hod_status='1' 
 inner join stu_section_data i on i.admn_no= a.admn_no   and i.session_year=a.session_year
 group by a.admn_no,c.semester ORDER BY SUBSTRING_INDEX(c.semester,'_',-1), SUBSTRING_INDEX(c.semester,'_',1),i.section)x
 group by x.semester,x.group,x.section
 ORDER BY SUBSTRING_INDEX(x.semester,'_',1), SUBSTRING_INDEX(x.semester,'_',-1),x.section");     
              
        $result=$query->result();
       // echo $this->db->last_query();die();
      //print_r($result);die();
        return $result;



        }
        function get_subjec_desc($id){
            $sql = "select a.* from subject_mapping a where a.map_id=?";
            $query = $this->db->query($sql,array($id));
            if ($this->db->affected_rows() >= 0) {
                return $query->row();
            } else {
            return false;
            }
        }
		 //was not available added by anuj-------------------------
        
        function getPrepSubjectByYear($y="",$s=""){
            if($y && $s){
                $b=$this->db->get_where($this->sub_mapping,array('session_year'=>$y,'session'=>$s,'course_id'=>'prep','branch_id'=>'prep'));
            }else{
				$b=$this->db->get_where($this->sub_mapping,array('course_id'=>'prep','branch_id'=>'prep'));
			}
		
		if($b->num_rows()>0)
				return $b->result();
	}
        
        function get_subject_code($id){
            $sql = "select subject_id from subjects where id=?";
            $query = $this->db->query($sql,array($id));
            if ($this->db->affected_rows() > 0) {
                return $query->row()->subject_id;
            } else {
            return false;
            }
        }
        
        
        //------------------------------------------
        
        function get_subjects_by_sem_and_dept_summer_comm($section ,$grp,$session_year,$semester,$course_id,$branch_id,$aggr_id)
    {
         //echo $section;echo $grp; echo $session_year; echo $semester; echo $course_id; echo $branch_id; echo $aggr_id;die();
             //if($agg_id<>null) $replace=    " AND c.aggr_id='$agg_id' ";
        $query = $this->db->query("(SELECT DISTINCT c.id,a.session_year,c.semester,'comm' as course_id,'comm' as branch_id
FROM `reg_summer_form` a
JOIN `course_structure` c ON c.aggr_id=a.course_aggr_id  
join `stu_section_data` e on e.admn_no=a.admn_no and e.session_year=a.session_year 
JOIN `subjects` d ON d.id=c.id
WHERE a.session_year='".$session_year."' AND c.semester='".$semester."_".$grp."'    and e.section='".$section."' /*AND a.course_id='".$course_id."' AND a.branch_id='".$branch_id."'*/ AND a.course_aggr_id='".$aggr_id."' AND a.hod_status='1' /*AND d.elective = '0'*/)
  ");
        //echo ($query);  
       //  $result=$query->result();
//print_r($query->result());
     
        //$this->db->order_by("sequence","ASC");
        //$query = $this->db->get_where($this->table_course_structure,array('semester'=>$sem, 'aggr_id'=>$aggr_id));
     
        $result= $query->result();
		return $result;
          //echo $this->db->last_query();die();
    }
        
} 
?>