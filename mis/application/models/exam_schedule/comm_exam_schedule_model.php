<?php
  class Comm_exam_schedule_model extends CI_Model
  {
    public function __construct()
    {
      parent::__construct();
      $this->load->database();

    }

    public function check_shift($data)
  {
    $this->db->select('*');
    $this->db->from('exam_schedule_mapping');
    $this->db->where('session_year',$data['session_year']);
    $this->db->where('session',$data['session']);
    $this->db->where('type',$data['type']);
  //  $this->db->where('section',0);
    $query=$this->db->get();
    if($query->num_rows()==0)
      return 0;
    else
    {
      $map=$query->result()[0]->map_id;
      $this->db->select('*');
      $this->db->from('exam_shift');
      $this->db->where('map_id',$map);
      $query=$this->db->get();
      if($query->num_rows()==0)
        return 0;
      else
        return $query->result();
    }
  }

  public function get_class($venue)
  {
    $this->db->select('*');
    $this->db->from('exam_seating');
    $this->db->where('dept_id',$venue);
    $query=$this->db->get();
    return $query->result();
  }

    public function check_status($data)
    {
      $array= array('session_year' =>$data[0],
                       'session' =>$data[1],
                       'section' =>$data[4],
                       'type' =>$data[3]);

      for($i=0;$i<count($data['discipline']);$i++)
      {
      $this->db->select('map_id');
      $this->db->from('exam_schedule_mapping');
      $this->db->where($array);
      $query=$this->db->get();
      $map=$query->result();
       
      if(count($map)==0)
          $status[$i]=0;
      else
      {
          $this->db->select('*');
          $this->db->from('exam_schedule');
          $this->db->where('map_id',$map[0]->map_id);
          $this->db->where('semester',$data['discipline'][$i]->semester);
          $this->db->where('course_id',$data['discipline'][$i]->course_id);
          $this->db->where('branch_id',$data['discipline'][$i]->branch_id);
          $query=$this->db->get();
          if($query->num_rows()==0)
            $status[$i]=0;
          else
            $status[$i]=1;
      }
    }
    if(count($data['discipline']))
      return $status;
    else
      return 0;
  }    

    public function get_sect($group,$session_year)
    {
      $this->db->select('section');
      $this->db->from('section_group_rel');
      $this->db->where('session_year',$session_year);
      $this->db->where('group',$group);
       $this->db->order_by('section',"asc");
      $query= $this->db->get();
      return $query->result();
    }

    public function get_group($session_year)
    {
      $this->db->distinct();
      $this->db->select('group');
      $this->db->from('section_group_rel');
      $this->db->where('session_year',$session_year);
      $this->db->order_by('group',"asc");
      $query= $this->db->get();
      return $query->result();
    }

    public function get_discipline($data)
    {
      $array= array('session_year' =>$data['session_year'],
                       'session' =>$data['session'],
                       'group' =>$data['group']);
      $this->db->select('semester,course_id,branch_id');
      $this->db->from('subject_mapping');
      $this->db->where($array);
      $query=$this->db->get();
      if($query->num_rows()>0)
      return $query->result();
      else 
        return 0;
    }

    public function insert_map($session_year,$session,$section,$type)
  {
    $array=array(
      'map_id'=>'',
      'session_year'=>$session_year,
      'session'=>$session,
      'section'=>$section,
      'type'=>$type
      );
    $query=$this->db->get_where('exam_schedule_mapping',array('session_year'=>$session_year,'session'=>$session,
      'section'=>$section,'type'=>$type));
    if($query->num_rows()==0)
      $this->db->insert('exam_schedule_mapping',$array);
    $query=$this->db->get_where('exam_schedule_mapping',array('session_year'=>$session_year,'session'=>$session,
           'section'=>$section,'type'=>$type));
    return $query->result()[0]->map_id;
  }

  public function insert_map1($session_year,$session,$type)
  {
    $array=array(
      'map_id'=>'',
      'session_year'=>$session_year,
      'session'=>$session,
    //  'section'=>'0',
      'type'=>$type
      );
    $query=$this->db->get_where('exam_schedule_mapping',array('session_year'=>$session_year,'session'=>$session,
      'type'=>$type));
    if($query->num_rows()==0)
      $this->db->insert('exam_schedule_mapping',$array);
    $query=$this->db->get_where('exam_schedule_mapping',array('session_year'=>$session_year,'session'=>$session,
                  'type'=>$type));
    return $query->result()[0]->map_id;
  }
  public function insert_shift($in)
  {
    $this->db->insert('exam_shift',$in);
  }

     public function check($arr)
       {
          $query=$this->db->get_where('exam_schedule',$arr);
          return $query->num_rows();
        }
     public function insert($in)
     {
      $this->db->insert('exam_schedule',$in);
      }

     public function update($in)
     {
      $this->db->update('exam_schedule',$in,array('map_id'=>$in['map_id'],'subject_code'=>$in['subject_code']));
        }

   
    public function get_invigilator($data)
    {
      $dept =array();
      for($i=0; $i<count($data['subject']); $i++)
      {
      
        $this->db->select('emp_no');
        $this->db->from('subject_mapping_des');
        $this->db->where('sub_id',$data['subject'][$i]->id);
        $query=$this->db->get();
        $emp_no=$query->result()[0]->emp_no;
      
        $this->db->select('dept_id');
        $this->db->from('user_details');
        $this->db->where('id',$emp_no);
        $query=$this->db->get();
        $dept[$i] =$query->result()[0]->dept_id;
      }
        return $dept;
    }

    function getGroup($data)
    {
      $this->db->select('group');
      $this->db->from('section_group_rel');
      $this->db->where('session_year', $data['session_year']);
      $this->db->where('section', $data['section']);
      $query = $this->db->get();
      return $query->result()[0]->group;
    }

    function getVenue()
    {
      $this->db->distinct();
      $this->db->select('dept_id');
      $this->db->from('exam_seating');
      $query = $this->db->get();
      return $query->result();
    }

    function get_student($data)
    {
      $session_year = $data['session_year'];
      $section = $data['section'];
      $query = $this->db->query("SELECT count(admn_no) as count
                                  FROM stu_section_data
                                  WHERE session_year = '$session_year' AND section = '$section'");
      return $query->result()[0]->count;

    }
    public function show_schedule($data)
    {
    $this->db->select('map_id');
    $this->db->from('exam_schedule_mapping');
    $this->db->where('session_year',$data['session_year']);
    $this->db->where('session',$data['session']);
    $this->db->where('section',$data['section']);
    $this->db->where('type',$data['type']);
    $q=$this->db->get();
    if(!$q->result())
      return false;
    $map=$q->result()[0]->map_id;

    $this->db->select('*');
    $this->db->from('exam_schedule');
    $this->db->where('map_id',$map);
    $query=$this->db->get();
    return $query->result();
    }

    public function load_subject($data)
      {
        $array= array('session_year' =>$data['session_year'],
                       'session' =>$data['session'],
                       'group' =>$data['group']);
        $this->db->select('map_id');
        $this->db->from('subject_mapping');
        $this->db->where($array);
        $query=$this->db->get();
        if($query->num_rows()>0)
          $map=$query->result()[0]->map_id;
        else
          return 0;

        $this->db->select('sub_id');
        $this->db->from('subject_mapping_des');
        $this->db->where('map_id',$map);
        $query=$this->db->get();
        if($query->num_rows()>0)
          $sub_tmp=$query->result();
        else
          return 0;

        for($i=0;$i<count($sub_tmp);$i++)
        $sub_id[$i]=$sub_tmp[$i]->sub_id;

        $this->db->select('id,subject_id,name');
        $this->db->from('subjects');
        $this->db->where('type','Theory');
        $this->db->where_in('id',$sub_id);
        $query=$this->db->get();
        if($query->num_rows()>0)
      return $query->result();
      else 
        return 0;
      }
  }