
<?php if (!defined('BASEPATH')) exit ('No direct script access allowed!');


class Faculty extends MY_Controller
{
	function __construct()
	{
		parent::__construct(array('ft'));
		$this->addJS('feedback/feedback.js');
		$this->load->model('feedback/faculty_model');
	}

	public function index()
	{
		$this->drawHeader();
		$this->load->view('feedback/faculty/faculty_input_session');
		$this->drawFooter();
	}
	public function submit_session()
	{

		$data = array();
		$data['session_year'] = $this->input->post('session_year');
		$data['session'] = $this->input->post('session');
		$data['id'] = $this->session->userdata('id');
		$subjects = $this->faculty_model->get_faculty_subjects($data);

		$emp_no=$this->session->userdata('id');
		$teacher_name = $this->faculty_model->get_user_name_by_user_id($this->session->userdata('id'));
		$data['teacher_name'] = $teacher_name[0]->name;


		$data['subject'] = array();
		$data['feedback'] = array();
		$data['groups'] = array();
		$data['parameters'] = array();
		$groups = $this->faculty_model->get_feedback_groups();
		$parameters = $this->faculty_model->get_feedback_parameters();
		$i = 1;
		foreach($groups as $result)
		{
			$data['groups'][$i] = array();
			$data['groups'][$i]['group_no'] = $result->group_no;
			$data['groups'][$i]['group_name'] = $result->group_name;
			$i++;
		}
		$data['groups']['count'] = $i - 1;
		$i = 1;
		foreach($parameters as $result)
		{
			$data['parameters'][$i] = array();
			$data['parameters'][$i]['parameter_no'] = $result->parameter_no;
			$data['parameters'][$i]['group_no'] = $result->group_no;
			$data['parameters'][$i]['parameter_name'] = $result->parameter_name;
			$i++;
		}
		$data['parameter_group_mapping'] = array();
		foreach ($parameters as $result)
		{
			$parameter_no = $result->parameter_no;
			$data['parameter_group_mapping'][$parameter_no] = $result->group_no;
		}
		$data['parameters']['count'] = $i -1;
		$i = 1;

		$map = array(); // When same teacher+subject combination mentioned multiple times;
		foreach($subjects as $result)
		{
			$subject_id = $result->subject_id;
			//echo $subject_id."<br>";
			$subject_info = $this->faculty_model->get_subject_info_from_subject_id($subject_id);
			if (array_key_exists($subject_info[0]->id,$map))
				continue;
			$map[$subject_info[0]->subject_id] = 1;
			$data['subject'][$i] = array();
			$data['subject'][$i]['subject_id'] = $result->subject_id;


			// echo $subject_id." ".$this->session->userdata('id');
			$map_id_temp = $this->faculty_model->get_map_id_by_subject_id($subject_id,$emp_no);

			//$map_id = $map_id_temp[0]->map_id;
			foreach($map_id_temp as $temp_map_id)
			{
				//echo $map_id."<br>";
				$map_id=$temp_map_id->map_id;
				$temp = $this->faculty_model->get_subject_details($map_id);
                                //echo $this->db->last_query();die();
				if(count($temp)==0)
					continue;
				$data['subject'][$i]['session'] = $temp[0]->session;
				$data['subject'][$i]['session_year'] = $temp[0]->session_year;
				if(!($data['subject'][$i]['session']==$data['session'] && $data['subject'][$i]['session_year']==$data['session_year']))
					continue;
				$data['subject'][$i]['dept_id'] = $temp[0]->dept_id;
				$data['subject'][$i]['branch'] = $temp[0]->branch_id;
				$data['subject'][$i]['course_id'] = $temp[0]->course_id;
				$data['subject'][$i]['semester'] = $temp[0]->semester;
                                $xyz=  explode('_', $temp[0]->aggr_id);
                                $data['subject'][$i]['aggrid'] = $xyz[2].'_'.$xyz[3];

				$dept_name = $this->faculty_model->get_department_name_by_department_id($data['subject'][$i]['dept_id']);
				if (!empty($dept_name))
					$data['subject'][$i]['dept_name'] = $dept_name[0]->name;
				else
					$data['subject'][$i]['dept_name'] = "Department name not available";

				$branch = $this->faculty_model->get_branch_name_by_branch_id($data['subject'][$i]['branch']);
				if (!empty($branch))
					$data['subject'][$i]['branch'] = $branch[0]->name;
				else
					$data['subject'][$i]['branch'] = "Branch name not available";

				$course_name = $this->faculty_model->get_course_name_by_course_id($data['subject'][$i]['course_id']);
				if (!empty($course_name))
					$data['subject'][$i]['course_name'] = $course_name[0]->name;
				else
					$data['subject'][$i]['course_name'] = "Minor";


				$data['subject'][$i]['real_subject_id'] = $subject_info[0]->subject_id;
				$data['subject'][$i]['subject_id'] = $subject_info[0]->id;


				$data['subject'][$i]['subject_name'] = $subject_info[0]->name;
				$temp_feedback = $this->faculty_model->get_feedback($subject_id,$this->session->userdata('id'),$map_id);
				$count = 1;
				foreach($temp_feedback as $result1)
				{
					$data['subject'][$i][$count] = array();
					$data['subject'][$i][$count]['gpa'] = $result1->gpa;
					$data['subject'][$i][$count]['comment'] = $result1->comment;
					$data['subject'][$i][$count]['feedback_id'] = $result1->feedback_id;

					$ratings = $this->faculty_model->get_ratings($data['subject'][$i][$count]['feedback_id']);
					foreach ($ratings as $ratings_result)
					{
						$parameter_no = $ratings_result->parameter_no;
						$parameter = 'parameter_'.$data['parameter_group_mapping'][$parameter_no].'_'.$parameter_no;
						$data['subject'][$i][$count][$parameter] = $ratings_result->rating;
					}
					$count++;
				}
				//Number of feedback for the current feedback.
				$data['subject'][$i]['count'] = $count - 1;
				$i++;
			}
		}
		$data['subject']['count'] = $i - 1;

		if ($i == 1)
		{
			$this->session->set_flashdata("flashError","No Subjects for given Session");
			//redirect('feedback/faculty');
		}
		$photo_path = $this->faculty_model->get_photo_path_by_faculty_id($data['id']);
		$data['photo_path'] = $photo_path[0]->photopath;
		$this->drawHeader('Semester Feedback');
		$this->load->view('feedback/faculty/view_feedback',$data);
		$this->drawFooter();
	}
	public function select_running_semester_session()
	{
		$this->drawHeader();
		$this->load->view('feedback/faculty/select_running_semester_session');
		$this->drawFooter();
	}
	public function submit_session_running()
	{
		$data = array();
		$data['session_year'] = $this->input->post('session_year');
		$data['session'] = $this->input->post('session');

		$data['id'] = $this->session->userdata('id');
		$subjects = $this->faculty_model->get_faculty_subjects($data);


		$teacher_name = $this->faculty_model->get_user_name_by_user_id($this->session->userdata('id'));
		$data['teacher_name'] = $teacher_name[0]->name;


		$data['subject'] = array();
		$data['feedback'] = array();
		$data['groups'] = array();
		$data['parameters'] = array();
		$groups = $this->faculty_model->get_feedback_groups_fbr();
		$parameters = $this->faculty_model->get_feedback_parameters_fbr();
		$i = 1;
		foreach($groups as $result)
		{
			$data['groups'][$i] = array();
			$data['groups'][$i]['group_no'] = $result->group_no;
			$data['groups'][$i]['group_name'] = $result->group_name;
			$i++;
		}
		$data['groups']['count'] = $i - 1;
		$i = 1;
		foreach($parameters as $result)
		{
			$data['parameters'][$i] = array();
			$data['parameters'][$i]['parameter_no'] = $result->parameter_no;
			$data['parameters'][$i]['group_no'] = $result->group_no;
			$data['parameters'][$i]['parameter_name'] = $result->parameter_name;
			$i++;
		}
		foreach ($parameters as $result)
		{
			$parameter_no = $result->parameter_no;
			$data['parameter_group_mapping'][$parameter_no] = $result->group_no;
		}
		$data['parameters']['count'] = $i -1;
		$i = 1;
		foreach($subjects as $result)
		{
			$data['subject'][$i] = array();
			$data['subject'][$i]['subject_id'] = $result->subject_id;
			$subject_id = $data['subject'][$i]['subject_id'];


			$map_id_temp = $this->faculty_model->get_map_id_by_subject_id($subject_id);
			$map_id = $map_id_temp[0]->map_id;
			$temp = $this->faculty_model->get_subject_details($map_id);

			$data['subject'][$i]['session'] = $temp[0]->session;
			$data['subject'][$i]['session_year'] = $temp[0]->session_year;
			$data['subject'][$i]['dept_id'] = $temp[0]->dept_id;
			$data['subject'][$i]['course_id'] = $temp[0]->course_id;
			$data['subject'][$i]['semester'] = $temp[0]->semester;

			$dept_name = $this->faculty_model->get_department_name_by_department_id($data['subject'][$i]['dept_id']);
			$data['subject'][$i]['dept_name'] = $dept_name[0]->name;

			$course_name = $this->faculty_model->get_course_name_by_course_id($data['subject'][$i]['course_id']);
			$data['subject'][$i]['course_name'] = $course_name[0]->name;

			$subject_info = $this->faculty_model->get_subject_info_from_subject_id($subject_id);
			$data['subject'][$i]['real_subject_id'] = $subject_info[0]->subject_id;
			$data['subject'][$i]['subject_name'] = $subject_info[0]->name;
			$temp_feedback = $this->faculty_model->get_feedback_fbr($subject_id);
			$count = 1;
			foreach($temp_feedback as $result1)
			{
				$data['subject'][$i][$count] = array();
				$data['subject'][$i][$count]['gpa'] = $result1->gpa;
				$data['subject'][$i][$count]['comment'] = $result1->comment;
				$data['subject'][$i][$count]['feedback_id'] = $result1->feedback_id;

				$ratings = $this->faculty_model->get_ratings_fbr($data['subject'][$i][$count]['feedback_id']);
				foreach ($ratings as $ratings_result)
				{
					$parameter_no = $ratings_result->parameter_no;
					$parameter = 'parameter_'.$data['parameter_group_mapping'][$parameter_no].'_'.$parameter_no;
					$data['subject'][$i][$count][$parameter] = $ratings_result->rating;
				}
				$count++;
			}
			$data['subject'][$i]['count'] = $count - 1;
			$i++;
		}
		$data['subject']['count'] = $i - 1;
		if ($i == 1)
		{
			$this->session->set_flashdata("flashError","No Subjects for given Session");
			redirect('feedback/faculty/select_running_semester_session');
		}
		$photo_path = $this->faculty_model->get_photo_path_by_faculty_id($data['id']);
		$data['photo_path'] = $photo_path[0]->photopath;
		$this->drawHeader('Feedback');
		$this->load->view('feedback/faculty/view_running_semester_feedback',$data);
		$this->drawFooter();
	}
}
