<?php
/* 
 * It contain menu model class for sport section
 * hosp is auth type of head of sport
 * 
 */
class Spo_section_menu_model extends CI_Model
{
	function __construct()
	{
		// Call the Model constructor
		parent::__construct();
	}

	function getMenu()
	{
		
		$menu =
		[    
			// Designing the menu model for sport officer
			'hosp' =>	
			[   
				"Sports"=>
				[
	 				// store purchase menu
					'Manage Store Stock'	=>
					[
						'Manage Supplier' =>
						[
							'View Supplier Detail'  => 	site_url('spo_section/supplier/view_supplier'),
							'Add New Supplier'		=>  site_url('spo_section/supplier/add_supplier')
						],

						// Menu for category and brands
						'Manage Store Item'	=>
						[
							
								'Add Category'	=> site_url('spo_section/category_brand/add_category'),
								'View Category' => site_url('spo_section/category_brand/view_category'),
							

							
								'Add Brand'	=> site_url('spo_section/category_brand/add_brand'),
								'View Brand' => site_url('spo_section/category_brand/view_brand'),
							

						],

						// menu for managing the stock purchase by sports officer

						'Register Stock '	=>
						[
							// Consumable Item  menu

							
								'Stock History'		=> site_url('spo_section/stock/stock_history/consumable'),
								'New Stock Entry'	=> site_url('spo_section/stock/new_stock_entry/consumable'),
								'Purchase Report'	=> site_url('spo_section/stock/purchase_report_csmb'),   //  consumable
							

						],

						'Current Stock Report'		=>site_url('spo_section/stock/current_stock_report_csmb'),  //csmb consumable
					],

					// menu for managing the issuing of item

					'Manage Students' =>
					[
						'Approve Request'		=> 	site_url('spo_section/spo_student/issue_request_list/stu'),
						'Manage Group'		=> 	site_url('spo_section/spo_student/dissolve_group/stu'),
					
						// 'No Dues' =>
						// [
						// 	"View Dues"  => site_url('spo_section/spo_student/select_view_dues'),
						// 	"clear Dues" => site_url('spo_section/spo_student/clear_dues'),
						// ],
					],

					'Manage Employee' =>
					[
						'Approve Request'		=> 	site_url('spo_section/spo_student/issue_request_list/emp'),
						
						// 'No Dues' =>
						// [
						// 	"View Dues"  => site_url('spo_section/spo_student/select_view_dues'),
						// 	"clear Dues" => site_url('spo_section/spo_student/clear_dues'),
						// ],
					],
					'Sports Officer'	=>
					[
						'Issue Item'	=>	site_url('spo_section/spo_officer/select_issue_item'),
						'Return Item'	=>	site_url('spo_section/spo_officer/select_return_item'),
						"View Dues"		=>	site_url('spo_section/spo_officer/select_view_dues'),
					],
					'Manage Items'=>
					[
						'Return Item'			=>	site_url('spo_section/spo_student/select_return_item'),
						'Issue Item'			=>	site_url('spo_section/spo_student/select_issue_item'),
						"View Dues"  			=> site_url('spo_section/spo_student/select_view_dues'),
					],

					'Generate Report'	=> site_url('spo_section/spo_student/issue_report'),
				],
				
			],


			//Designing the menu model for student
		/*	'stu' =>
			[
				
				
					"Sports" =>
					[
						'Group'=>
						[
							'Create Group'			=>	site_url('spo_section/group'),
							'Group request'  =>  site_url('spo_section/group/group_request_list'),
							'View Rejected Group' =>site_url('spo_section/group/view_rejected_group'),
							'View Group'			=>  site_url('spo_section/group/view_group'),
						],

						// 'View Dues'	 => site_url('spo_section/dues/view_dues'),
						'Issue New Item' => site_url('spo_section/issue_item'),
						'View History'	=>  site_url('spo_section/issue_history/view_current_history'),
					],
			], */

			//Designing the menu model for Employee
			/*'emp' =>
			[	
				
					"Sports" =>
					[
						// 'View Dues'	 => site_url('spo_section/dues/view_dues'),
						'Issue New Item' => site_url('spo_section/employee'),
						'View History'	=>  site_url('spo_section/employee/view_history'),
					],
			],*/

			// Designing the menu model for dealing assistant

			'spo_da' =>
			[
				"Sports"=>
				[
	 				// store purchase menu
					'Manage Store Stock'	=>
					[
						'Manage Supplier' =>
						[
							'View Supplier Detail'  => 	site_url('spo_section/supplier/view_supplier'),
							'Add New Supplier'		=>  site_url('spo_section/supplier/add_supplier')
						],

						// Menu for category and brands
						'Manage Store Item'	=>
						[
							
								'Add Category'	=> site_url('spo_section/category_brand/add_category'),
								'View Category' => site_url('spo_section/category_brand/view_category'),
							

							
								'Add Brand'	=> site_url('spo_section/category_brand/add_brand'),
								'View Brand' => site_url('spo_section/category_brand/view_brand'),
							

						],

						// menu for managing the stock purchase by sports officer

						'Register Stock '	=>
						[
							// Consumable Item  menu

							
								'Stock History'		=> site_url('spo_section/stock/stock_history/consumable'),
								'New Stock Entry'	=> site_url('spo_section/stock/new_stock_entry/consumable'),
								'Purchase Report'	=> site_url('spo_section/stock/purchase_report_csmb'),   //  consumable
						

						],

						'Current Stock Report'		=>site_url('spo_section/stock/current_stock_report_csmb'),  //csmb consumable
					],

					// menu for managing the issuing of item

					'Manage Students' =>
					[
						'Approve Request'		=> 	site_url('spo_section/spo_student/issue_request_list/stu'),
						'Manage Group'		=> 	site_url('spo_section/spo_student/dissolve_group/stu'),
					
						// 'No Dues' =>
						// [
						// 	"View Dues"  => site_url('spo_section/spo_student/select_view_dues'),
						// 	"clear Dues" => site_url('spo_section/spo_student/clear_dues'),
						// ],
					],

					'Manage Employee' =>
					[
						'Approve Request'		=> 	site_url('spo_section/spo_student/issue_request_list/emp'),
						
						// 'No Dues' =>
						// [
						// 	"View Dues"  => site_url('spo_section/spo_student/select_view_dues'),
						// 	"clear Dues" => site_url('spo_section/spo_student/clear_dues'),
						// ],
					],

					'Manage Items'=>
					[
						'Return Item'			=>	site_url('spo_section/spo_student/select_return_item'),
						'Issue Item'			=>	site_url('spo_section/spo_student/select_issue_item'),
						"View Dues"  			=> site_url('spo_section/spo_student/select_view_dues'),
					],

					'Generate Report'	=> site_url('spo_section/spo_student/issue_report'),
				],
			],

		];

						

						
						return $menu;
					}
				}
				/* End of file spo_section_menu_models.php */
/* Location: mis/application/models/spo_section/spo_section_menu_model.php */