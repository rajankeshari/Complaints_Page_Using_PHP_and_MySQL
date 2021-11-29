<?php 
 /**
 * 
 */
 class Swimming_menu_model extends CI_Model
 {
 	
 	function __construct()
 	{
 		parent::__construct();
 	}

 	public function getMenu()
 	{
 		$menu =
 		[
 			
 		/*	'stu'  =>
 			[
 				
	 				'Swimming'	=>
	 				[
	 					'swimming registration' =>  site_url('swimming/student_registration'),
	 					'view status'			=>  site_url('swimming/student_registration/view_status'),
	 				],
 			],*/
	 		
 		/*	'emp'  =>
 			[	
	 				'Swimming'	=>
	 				[
	 					'swimming registration' =>  site_url('swimming/emp_registration'),
	 					'view status'			=>  site_url('swimming/emp_registration/view_status'),
	 				],
	 			
 			],*/

 			'hosp' =>
 				[
 					'Swimming'	=>
 					[   
 						'Manage Group' =>
 						[
 							'View Group' 	=> site_url('swimming/swimming_section/view_swimming_group'),
 						],
 						
 						'Manage Slot' =>
 						[
 							'Create Slot' 	=> site_url('swimming/swimming_section/create_slot'),
 							'View Slot'     => site_url('swimming/swimming_section/view_slot'),
 						],
 						'Register On Other Behalf '=>
 						[
 							'Student'=>site_url('swimming/swimming_officer/student'),
 							//'Employee'=>site_url('swimming/swimming_officer/employee'),
 						],
 						'Review Application'	=>
 						[
 							'Student' => site_url('swimming/swimming_section/view_student_application'),
 							'Employee' => site_url('swimming/swimming_section/view_employee_application'),
 						],

 						'Slot Allocation' =>
 						[
 							'Student' => site_url('swimming/swimming_section/allocate_student_slot'), 
 							'Employee' => site_url('swimming/swimming_section/allocate_employee_slot'),
 						],

 						
 					],
 				],
 			'spo_da' =>
			[
				'Swimming'	=>
				[   
					'Manage Group' =>
					[
						'View Group' 	=> site_url('swimming/swimming_section/view_swimming_group'),
					],
					
					'Manage Slot' =>
					[
						'Create Slot' 	=> site_url('swimming/swimming_section/create_slot'),
						'View Slot'     => site_url('swimming/swimming_section/view_slot'),
					],

					'Review Application'	=>
					[
						'Student' => site_url('swimming/swimming_section/view_student_application'),
						'Employee' => site_url('swimming/swimming_section/view_employee_application'),
					],

					'Slot Allocation' =>
					[
						'Student' => site_url('swimming/swimming_section/allocate_student_slot'), 
						'Employee' => site_url('swimming/swimming_section/allocate_employee_slot'),
					],

					
				],
			],

 		];

 		return $menu;

 	}
 }

 ?>