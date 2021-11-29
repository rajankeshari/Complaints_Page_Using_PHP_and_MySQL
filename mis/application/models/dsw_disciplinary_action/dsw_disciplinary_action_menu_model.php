<?php if(!defined("BASEPATH")){ exit("No direct script access allowed"); }

class Dsw_disciplinary_action_menu_model extends CI_Model
{
	function __construct()
	{
		// Call the Model constructor
		parent::__construct();
	}

	function getMenu()
	{
		$menu=[
		//auth ==> emp

			'dsw'=>
			[   'DSW Disciplinary Action'=>
				[
					'Take an Action'=>site_url('dsw_disciplinary_action/get_student_details'),
					/*'Take an Action'=>site_url('Dsw_disciplinary_action/get_student_details/Take_an_action'),*/
					'View Actions Taken'=>site_url('dsw_disciplinary_action/get_student_details/view_all_actions_taken'),
					'Update Actions'=>
					                 [             
					           'Remove Actions Taken'=>site_url('dsw_disciplinary_action/get_student_details/remove_action'),
					           'Edit  Actions Taken'=>site_url('dsw_disciplinary_action/get_student_details/edit_action'),
				                        ],
					'Add/Remove Punishment'=>site_url('dsw_disciplinary_action/get_student_details/punishment'),
					'History'=>site_url('dsw_disciplinary_action/get_student_details/history'),
				],
			],
			
			'sw_ar'=>
			[   'DSW Disciplinary Action'=>
				[
					'Take an Action'=>site_url('dsw_disciplinary_action/get_student_details'),
					/*'Take an Action'=>site_url('Dsw_disciplinary_action/get_student_details/Take_an_action'),*/
					'View Actions Taken'=>site_url('dsw_disciplinary_action/get_student_details/view_all_actions_taken'),
				/*	'Update Actions'=>
					                 [             
					           'Remove Actions Taken'=>site_url('dsw_disciplinary_action/get_student_details/remove_action'),
					           'Edit  Actions Taken'=>site_url('dsw_disciplinary_action/get_student_details/edit_action'),
				                        ],*/
					'Add/Remove Punishment'=>site_url('dsw_disciplinary_action/get_student_details/punishment'),
					'History'=>site_url('dsw_disciplinary_action/get_student_details/history'),
				],
			],
			
			'stu'=>
			[   
					'Disciplinary Action'=>
				[
					'Actions Taken'=>site_url('dsw_disciplinary_action/get_student_details/student_actions'),
			
				],
			],

			'ft'=>
			[   
					'Disciplinary Status'=>
				[
					'Search Student Details'=>site_url('dsw_disciplinary_action/faculty_search/faculty_search_students_details'),
				]
			],

			/*'hod'=>
			[   
					'DSW Disciplinary Action'=>
				[
					'View All Student Actions '=>site_url('dsw_disciplinary_action/hod_students_actions/all_actions_by_department'),
				],
			],*/
			
			];


		return $menu;
	}
}