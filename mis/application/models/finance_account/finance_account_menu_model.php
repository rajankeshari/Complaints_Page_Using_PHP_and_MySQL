<?php

class Finance_account_menu_model extends CI_Model
{
	function __construct()
	{
		// Call the Model constructor
		parent::__construct();
	}

	function getMenu()
	{
		    // Salary Edit Protion: 'acc_hos','acc_da2','acc_sal'
		    // For Emp
        $menu['emp']=array();
		$menu['emp']["Finance Account"]=array();
		$menu['emp']['Finance Account']["Salary"] = site_url('finance_account/finance_account_empwise');
		$menu['emp']['Finance Account']["View GPF"] = site_url('finance_account/finance_account_empwise/viewGpf');
                //---------- Saray Edit Protion -----------------------

        $menu['acc_hos']['CURRENT MONTH SALARY']['Publish'] = site_url('finance_account/salary_edit');
        $menu['acc_da2']['CURRENT MONTH SALARY']['Manage Salary' ] = site_url('finance_account/salary_edit');
        $menu['acc_sal']['CURRENT MONTH SALARY']['Manage Salary'] = site_url('finance_account/salary_edit');
        $menu['acc_sal']['CURRENT MONTH SALARY']['Manage Electricity/AC Bill']=site_url('finance_account/salary_edit/electricity_bill');
		$menu['acc_sal']['Report']['Undeclared Report']['Salary Bill Payment'] = site_url('finance_account/pre_report/salary_ball_payment');
		$menu['acc_sal']['Report']['Undeclared Report']['Salary Deductions'] = site_url('finance_account/pre_report/salary_deductions');


            //-------------------------Salary Summery ----------------
        $menu['acc_hos']['Report']['Current Month Report'] = site_url('finance_account/salary_edit/summaryOfSalary');
        $menu['acc_da2']['Report']['Current Month Report'] = site_url('finance_account/salary_edit/summaryOfSalary');
        $menu['acc_sal']['Report']['Current Month Report'] = site_url('finance_account/salary_edit/summaryOfSalary');

        $menu['acc_hos']['Report']['Declard Month Report'] = site_url('finance_account/salary_edit/summaryDeclardOfSalary');
        $menu['acc_da2']['Report']['Declard Month Report'] = site_url('finance_account/salary_edit/summaryDeclardOfSalary');
        $menu['acc_sal']['Report']['Declard Month Report'] = site_url('finance_account/salary_edit/summaryDeclardOfSalary');
		
		// Negative Salary Part
		
		$menu['acc_hos']['Report']['Negative Salary Report'] = site_url('finance_account/finance_account_misc_report/negative_salary_report');
		$menu['acc_da2']['Report']['Negative Salary Report'] = site_url('finance_account/finance_account_misc_report/negative_salary_report');
		$menu['acc_sal']['Report']['Negative Salary Report'] = site_url('finance_account/finance_account_misc_report/negative_salary_report');


		
        //---------------Change Basic-------------------------------//
        $menu['acc_hos']['Increment']['Calculate New Basic'] = site_url('finance_account/change_basic');
        $menu['acc_sal']['Increment']['Calculate New Basic']= site_url('finance_account/change_basic');
		$menu['acc_sal']['DA Arrear']['Calculate Arrear']= site_url('finance_account/da_arrear');
        $menu['acc_sal']['DA Arrear']['Edit/View Arrear']= site_url('finance_account/da_arrear/viewToEdit');
        $menu['acc_sal']['DA Arrear']['View Arrear (Total)']= site_url('finance_account/da_arrear/viewArrear');



        //---------------Reset Current Month fields ---------------//
        $menu['acc_hos']['CURRENT MONTH SALARY']['Reset Fields'] = site_url('finance_account/finance_admin/chooseRestSectionVaule');
        $menu['acc_da2']['CURRENT MONTH SALARY']['Reset Fields'] = site_url('finance_account/finance_admin/chooseRestSectionVaule');
        $menu['acc_sal']['CURRENT MONTH SALARY']['Reset Fields'] = site_url('finance_account/finance_admin/chooseRestSectionVaule');

        $menu['acc_hos']['CURRENT MONTH SALARY']['Query'] = site_url('finance_account/salary_edit/empShow');
        $menu['acc_da2']['CURRENT MONTH SALARY']['Query'] =  site_url('finance_account/salary_edit/empShow');
        $menu['acc_sal']['CURRENT MONTH SALARY']['Query'] =  site_url('finance_account/salary_edit/empShow');

        $menu['acc_hos']['CURRENT MONTH SALARY']['Edit'] = site_url('finance_account/salary_edit/empEdit/All');
        $menu['acc_da2']['CURRENT MONTH SALARY']['Edit'] = site_url('finance_account/salary_edit/empEdit/All');
        $menu['acc_sal']['CURRENT MONTH SALARY']['Edit'] = site_url('finance_account/salary_edit/empEdit/All');
        $menu['acc_hos']['CURRENT MONTH SALARY']['Edit Individual'] = site_url('finance_account/salary_edit/empEdit');
        $menu['acc_da2']['CURRENT MONTH SALARY']['Edit Individual'] = site_url('finance_account/salary_edit/empEdit');
        $menu['acc_sal']['CURRENT MONTH SALARY']['Edit Individual'] = site_url('finance_account/salary_edit/empEdit');
        $menu['acc_sal']['Manage Salary Slip Order']['Payable Field'] = site_url('finance_account/finance_account_empwise/edit_payable_order');
        $menu['acc_sal']['Manage Salary Slip Order']['Deduction Field'] = site_url('finance_account/finance_account_empwise/edit_deduction_order');
		 $menu['acc_sal']['Salary Settings']['Manage DCPS Calculation']['Add/Edit']= site_url('finance_account/manage_field_calculation');
        $menu['acc_sal']['Salary Settings']['Manage DCPS Calculation']['View']= site_url('finance_account/manage_field_calculation/view');
		 $menu['acc_sal']['Salary Settings']['Formula Masters'] = site_url('finance_account/formula_master');

        //--------Payable and Deduction fields----------///
        $menu['acc_da2']['Salary Settings']['Manage Payable Fields'] = site_url('finance_account/payble_fields');
        $menu['acc_da2']['Salary Settings']['Manage Deduction Fields'] = site_url('finance_account/deduction_fields');
        $menu['acc_sal']['Salary Settings']['Manage Payable Fields'] = site_url('finance_account/payble_fields');
        $menu['acc_sal']['Salary Settings']['Manage Deduction Fields'] = site_url('finance_account/deduction_fields');
		
		/*----------------------------------FORM-16----------------------------*/
		$menu['acc_hos']['I.T Self Assessment']['Print Assessment Form']=site_url('finance_account/form_sixteen_generator');
        $menu['emp']['Finance Account']['IT Self Assessment']=site_url('finance_account/self_assessment');
        $menu['acc_hos']['I.T Self Assessment']['Financial Year Management']=site_url('finance_account/finance_admin/fy');
        $menu['acc_hos']['I.T Self Assessment']['Itax Rates Management']=site_url('finance_account/acc_itax_rate');
        $menu['acc_hos']['I.T Self Assessment']['Tax Payer Type Management']=site_url('finance_account/acc_taxpayer_type');
		
		$menu['acc_sal']['I.T Self Assessment']['Print Assessment Form']=site_url('finance_account/form_sixteen_generator');
        $menu['acc_sal']['I.T Self Assessment']['Finantial Year Management']=site_url('finance_account/finance_admin/fy');
        $menu['acc_sal']['I.T Self Assessment']['Itax Rates Management']=site_url('finance_account/acc_itax_rate');
        $menu['acc_sal']['I.T Self Assessment']['Tax Payer Type Management']=site_url('finance_account/acc_taxpayer_type');
		$menu['acc_sal']['I.T Self Assessment']['Cess Management']=site_url('finance_account/cess_management');
        
        $menu['acc_sal']['I.T Self Assessment']['Settings']['Add Field']=site_url('finance_account/manage_assessment_form');
        $menu['acc_sal']['I.T Self Assessment']['Settings']['Manage Field']=site_url('finance_account/manage_assessment_form/manageFields');
        $menu['acc_sal']['I.T Self Assessment']['Settings']['Enter Field Value']=site_url('finance_account/manage_assessment_form/addRecord');
		
		$menu['acc_hos']['I.T Self Assessment']['Cess Management']=site_url('finance_account/cess_management');
        
        $menu['acc_hos']['I.T Self Assessment']['Settings']['Add Field']=site_url('finance_account/manage_assessment_form');
        $menu['acc_hos']['I.T Self Assessment']['Settings']['Manage Field']=site_url('finance_account/manage_assessment_form/manageFields');
        $menu['acc_hos']['I.T Self Assessment']['Settings']['Enter Field Value']=site_url('finance_account/manage_assessment_form/addRecord');
        //--------------------------
        /******************* Preavious Salary Search***********************/
		$menu['acc_sal']['Previous Salary']= site_url('finance_account/salary_search');
		/*------------------------- Other Income---------------------------------------------------------------------*/
		/*$menu['acc_sal']['Other Income']['Consultancy']['Entry']= site_url('finance_account/other_income/consultency');
        $menu['acc_sal']['Other Income']['Consultancy']['Report']= site_url('finance_account/other_income/report_cons_view');
        $menu['acc_sal']['Other Income']['Consultancy']['Final Report']= site_url('finance_account/other_income/final_report_common/C');
        $menu['acc_sal']['Other Income']['Honorarium']['Entry']= site_url('finance_account/other_income/honorarium');
        $menu['acc_sal']['Other Income']['Honorarium']['Report']= site_url('finance_account/other_income/report_hon_view');
        $menu['acc_sal']['Other Income']['Honorarium']['Final Report']= site_url('finance_account/other_income/final_report_common/H');
		$menu['acc_sal']['Other Income']['OT Honorarium']['Entry']= site_url('finance_account/other_income/othonorarium');
        $menu['acc_sal']['Other Income']['OT Honorarium']['Report']= site_url('finance_account/other_income/report_othon_view');
        $menu['acc_sal']['Other Income']['OT Honorarium']['Final Report']= site_url('finance_account/other_income/final_report_common/OT');
        $menu['acc_sal']['Other Income']['Children Education Allowance']['Entry']= site_url('finance_account/other_income/children_education_allowance');
        $menu['acc_sal']['Other Income']['Children Education Allowance']['Report']= site_url('finance_account/other_income/report_children_education_allowance_view');
        $menu['acc_sal']['Other Income']['Children Education Allowance']['Final Report']= site_url('finance_account/other_income/final_report_common/CEA');*/
		
		//Consultancy
		$menu['acc_opr2']['Other Income']['Consultancy']['Entry']= site_url('finance_account/other_income/consultency');
        $menu['acc_opr2']['Other Income']['Consultancy']['Report']= site_url('finance_account/other_income/report_cons_view');
		//Honorarium
		$menu['acc_opr1']['Other Income']['Honorarium']['Entry']= site_url('finance_account/other_income/honorarium');
        $menu['acc_opr1']['Other Income']['Honorarium']['Report']= site_url('finance_account/other_income/report_hon_view');
        //Medical Reimbursement
        $menu['acc_opr1']['Other Income']['Medical Reimbursement']['Entry']= site_url('finance_account/other_income/medical_reimbursement');
        $menu['acc_opr1']['Other Income']['Medical Reimbursement']['Report']= site_url('finance_account/other_income/report_medical_reimbursement');
		
		//Children Education Allowance

		/*
		$menu['acc_sal']['Other Income']['Children Education Allowance']['Entry']= site_url('finance_account/other_income/children_education_allowance');
        $menu['acc_sal']['Other Income']['Children Education Allowance']['Report']= site_url('finance_account/other_income/report_children_education_allowance_view');
        */

		$menu['acc_opr_ot_hon']['Entry']= site_url('finance_account/other_income/othonorarium');
        $menu['acc_opr_ot_hon']['Report']= site_url('finance_account/other_income/report_othon_view');
        $menu['acc_opr_ot_hon']['Final Report']= site_url('finance_account/other_income/final_report_common/OT');
        //Consultancy
		$menu['acc_opr_cons']['Entry']= site_url('finance_account/other_income/consultency');
        $menu['acc_opr_cons']['Report']= site_url('finance_account/other_income/report_cons_view');
        //Honorarium
		$menu['acc_opr_hon']['Entry']= site_url('finance_account/other_income/honorarium');
        $menu['acc_opr_hon']['Report']= site_url('finance_account/other_income/report_hon_view');

        
         //Medical Reimbursment
        $menu['acc_opr_med']['Entry']= site_url('finance_account/other_income/medical_reimbursement');
        $menu['acc_opr_med']['Report']= site_url('finance_account/other_income/report_medical_reimbursement');

		//Children Education Allowance
		$menu['acc_opr_caw']['Entry']= site_url('finance_account/other_income/children_education_allowance');
        $menu['acc_opr_caw']['Report']= site_url('finance_account/other_income/report_children_education_allowance_view');
			
               //--------Electrical-------------------------- 
        $menu['da_elec_payroll']=array();
		$menu['da_elec_payroll']["AC Master"] = site_url('finance_account/ac_master');
        $menu['da_elec_payroll']["AC Status"] = site_url('finance_account/emp_ac_status');
        $menu['da_elec_payroll']["Electric Unit"] = site_url('finance_account/electric_units');
		$menu['da_elec_payroll']["Electric Unit1"] = site_url('finance_account/electric_units1');
        $menu['da_elec_payroll']["Electric Unit2"] = site_url('finance_account/electric_units2');
        $menu['da_elec_payroll']["Electric Bill Master"] = site_url('finance_account/electric_bill_master');
        $menu['da_elec_payroll']['Reports']=site_url('finance_account/electricity_report');
                //-----------------------------------------------------------------------
                
				 /*----------------LIC--------------------------------*/
         
        $menu['acc_sal']['LIC']['LIC Account'] = site_url('finance_account/lic');
        $menu['acc_sal']['LIC']['Current Month Premium Details'] = site_url('finance_account/lic/current_month_lic_details');
        $menu['acc_sal']['LIC']['Report'] = site_url('finance_account/lic/lic_query');
		/* ---------------------------------------------- GPF Module ---------------------------------------------------*/
        $menu['acc_sal']['GPF']['Manage GPF Account']['Add GPF Account'] = site_url('finance_account/acc_gpf_master/gpf_account');
        $menu['acc_sal']['GPF']['Manage GPF Account']['Edit GPF Account'] = site_url('finance_account/acc_gpf_master/edit_gpf_account');
        $menu['acc_sal']['GPF']['Manage GPF']['View GPF'] = site_url('finance_account/acc_gpf_master/view');
        $menu['acc_sal']['GPF']['Manage GPF']['Import GPF'] = site_url('finance_account/acc_gpf_master/importGPF');
        $menu['acc_sal']['GPF']['Manage GPF']['Calculate GPF Interest'] = site_url('finance_account/acc_gpf_master/calculateInterest');
                $menu['acc_sal']['GPF']['Manage GPF']['Edit GPF Interest'] = site_url('finance_account/edit_gpf/edit_gpf_interest_view');
        $menu['acc_sal']['GPF']['Manage GPF Interest Rates'] = site_url('finance_account/acc_gpf_interest_rate');
        $menu['acc_sal']['GPF']['Manage Employee GPF']['Add GPF'] = site_url('finance_account/acc_gpf_master');
                $menu['acc_sal']['GPF']['Manage Employee GPF']['Add GPF To Main'] = site_url('finance_account/acc_gpf_master/add_gpf');
                 $menu['acc_sal']['GPF']['Manage Employee GPF']['View Individual GPF'] = site_url('finance_account/acc_gpf_master/gpf_query_view');
                $menu['acc_sal']['GPF']['Manage Employee GPF']['Edit Individual GPF'] = site_url('finance_account/edit_gpf');
                $menu['acc_sal']['GPF']['Report']['Monthly Report'] = site_url('finance_account/acc_gpf_report/monthly_report_query');
        $menu['acc_sal']['GPF']['Report']['Yearly Report'] = site_url('finance_account/acc_gpf_report/yearly_report_query');
                $menu['acc_sal']['GPF']['Report']['Bunch Printing'] = site_url('finance_account/acc_gpf_report/GPFBunchPrinting');
                $menu['acc_sal']['GPF']['Report']['Bunch Printing Merged'] = site_url('finance_account/acc_gpf_report/GPFBunchPrintingMerged');
				
					$menu['acc_hos']['GPF']['Add GPF Year'] = site_url('finance_account/acc_gpf_master/addGpfYear');
		
		$menu['acc_gpf']['Manage GPF Account']['Add GPF Account'] = site_url('finance_account/acc_gpf_master/gpf_account');
        $menu['acc_gpf']['Manage GPF Account']['Edit GPF Account'] = site_url('finance_account/acc_gpf_master/edit_gpf_account');
        $menu['acc_gpf']['Manage GPF']['View GPF'] = site_url('finance_account/acc_gpf_master/view');
        $menu['acc_gpf']['Manage GPF']['Import GPF'] = site_url('finance_account/acc_gpf_master/importGPF');
        $menu['acc_gpf']['Manage GPF']['Calculate GPF Interest'] = site_url('finance_account/acc_gpf_master/calculateInterest');
		$menu['acc_gpf']['Manage GPF']['Edit GPF Interest'] = site_url('finance_account/edit_gpf/edit_gpf_interest_view');
        $menu['acc_gpf']['Manage GPF Interest Rates'] = site_url('finance_account/acc_gpf_interest_rate');
        $menu['acc_gpf']['Manage Employee GPF']['Add GPF'] = site_url('finance_account/acc_gpf_master');
		$menu['acc_gpf']['Manage Employee GPF']['Add GPF To Main'] = site_url('finance_account/acc_gpf_master/add_gpf');
		 $menu['acc_gpf']['Manage Employee GPF']['View Individual GPF'] = site_url('finance_account/acc_gpf_master/gpf_query_view');
		$menu['acc_gpf']['Manage Employee GPF']['Edit Individual GPF'] = site_url('finance_account/edit_gpf');
        $menu['acc_gpf']['Manage Employee GPF']['Edit Non Salaried GPF'] = site_url('finance_account/edit_gpf');
		$menu['acc_gpf']['Report']['Monthly Report'] = site_url('finance_account/acc_gpf_report/monthly_report_query');
        $menu['acc_gpf']['Report']['Yearly Report'] = site_url('finance_account/acc_gpf_report/yearly_report_query');
		$menu['acc_gpf']['Report']['Bunch Printing'] = site_url('finance_account/acc_gpf_report/GPFBunchPrinting');
          $menu['acc_gpf']['Report']['Bunch Printing Merged'] = site_url('finance_account/acc_gpf_report/GPFBunchPrintingMerged');
        //$menu['acc_gpf']['GPF']['Report']['Yearly Report'] = site_url('finance_account/acc_gpf_master/gpf_query_view');
        //$menu['acc_gpf']['GPF']['Report']['Individual GPF Details'] = site_url('finance_account/acc_gpf_report/monthly_report_query');
		
		//changes made by anuj starts
        //$menu['acc_gpf']['Edit Individual GPF'] = site_url('finance_account/edit_gpf');
        //$menu['acc_sal']['CURRENT MONTH SALARY']['Upload Salary'] = site_url('finance_account/upload_salary');
        /*--------------------------------------------------------------------------------------------------------------*/
		/*------------------Bank Statement------------------------------*/
        $menu['acc_sal']['Bank Statement']['Current Month Statement'] = site_url('finance_account/bank_statement');
        $menu['acc_sal']['Bank Statement']['Generate Bank Statement']['Current Month Statement'] = site_url('finance_account/bank_statement/bs');
        $menu['acc_sal']['Bank Statement']['Generate Bank Statement']['Previous Month Statement'] = site_url('finance_account/bank_statement/bs_view');
        $menu['acc_sal']['Bank Statement']['View Statement'] = site_url('finance_account/bank_statement/report');
               //--------------Establishment------------Pay and Allowance
        $menu['da_estt_payroll']=array();
	//	$menu['da_estt_payroll']["Pay and Allowance"] = site_url('finance_account/pay_allowances');
        $menu['da_estt_payroll']["Pay Scale Master"] = site_url('finance_account/pay_scale_master');
               // $menu['est_da1']["Pay Scale Master"]['Edit'] = site_url('finance_account/pay_scale_master_edit');
        $menu['da_estt_payroll']["Employee Basic Pay"] = site_url('finance_account/pay_scale_emp_master');
        $menu['da_estt_payroll']["Employee Validation Status"] = site_url('finance_account/emp_validation_status');
             //   $menu['da_estt_payroll']["Employee Global Setting"] = site_url('finance_account/emp_global_master');
                
          
              //----------------------------------------------------------------------------------
                
		/*-----------------------------Printting Salary Slip---------------------------*/
		 $menu['acc_sal']['Print Salary Slip'] = site_url('finance_account/salary_slip_printing'); 
                //--------Account--------------------------Deduction and Recovery 
        $menu['acc_da2']=array();
		$menu['acc_da2']["Deduction and Recovery"] = site_url('finance_account/deduction_recovery');
               
                //-----------------------------------------------------------------------
                
                //----------------------Head of Account End---------------------
        $menu['da_accts_payroll']=array();
        $menu['da_accts_payroll']["Electric Unit"] = site_url('finance_account/electric_units');
		$menu['da_elec_payroll']["Electric Unit1"] = site_url('finance_account/electric_units1');
        $menu['da_elec_payroll']["Electric Unit2"] = site_url('finance_account/electric_units2');
        $menu['da_accts_payroll']["AC Status"] = site_url('finance_account/emp_ac_status');
        $menu['da_elec_payroll']["AC Master"] = site_url('finance_account/ac_master');
        $menu['da_accts_payroll']["Pay and Allowance"] = site_url('finance_account/pay_allowances');
        $menu['da_accts_payroll']["Deduction and Recovery"] = site_url('finance_account/deduction_recovery');
        $menu['da_accts_payroll']["PF Status"] = site_url('finance_account/emp_pf_status');
        $menu['da_accts_payroll']["Pay Scale Master"] = site_url('finance_account/pay_scale_master');
                //$menu['acc_sal']["Pay Scale Master"]['Edit'] = site_url('finance_account/pay_scale_master_edit');
        $menu['da_accts_payroll']["Employee Basic Pay"] = site_url('finance_account/pay_scale_emp_master');
        $menu['da_accts_payroll']["Employee Validation Status"] = site_url('finance_account/emp_validation_status');
                
        //----------------------Head of Account End---------------------
        //-------------------Dealing Assistant Payrol Starts(da_est_pay)-----------------
        //$menu['da_estt_payroll']=array();
		    //$menu['da_estt_payroll']["Employee Basic Pay"] = site_url('finance_account/pay_scale_emp_master');
        //-----------------------Dealing Assistant Payrol Ends(da_est_pay)-----------------
        //-----------AR Estt Section-----------------------
        $menu['est_ar']=array();
		$menu['est_ar']['Payroll']=array();
		$menu['est_ar']["Payroll"]["Validate Pay Scale"]=site_url('finance_account/validation_payscale');
        //----------------------------------------------------------
		 /*-----------------------------Reports-------------------------*/
        $menu['acc_sal']['Report']['Monthly Report']['GIS Report'] = site_url('finance_account/finance_account_misc_report');
        $menu['acc_sal']['Report']['Monthly Report']['NEW GIS Report'] = site_url('finance_account/finance_account_misc_report/newgis');
        $menu['acc_sal']['Report']['Monthly Report']['Canara'] = site_url('finance_account/finance_account_misc_report/canara');
        $menu['acc_sal']['Report']['Monthly Report']['Bank Statement'] = site_url('finance_account/bank_statement/report');
        $menu['acc_sal']['Report']['Monthly Report']['LIC Report'] = site_url('finance_account/lic/lic_query');
        $menu['acc_sal']['Report']['Monthly Report']['Income Tax Report'] = site_url('finance_account/finance_account_misc_report/itax');
        $menu['acc_sal']['Report']['Monthly Report']['DCPS Report'] = site_url('finance_account/finance_account_misc_report/dcps');
        $menu['acc_sal']['Report']['Monthly Report']['RD Report'] = site_url('finance_account/finance_account_misc_report/rd');
        $menu['acc_sal']['Report']['Monthly Report']['Advanced Report'] = site_url('finance_account/finance_account_misc_report/advance');
        $menu['acc_sal']['Report']['Monthly Report']['Clubs Report'] = site_url('finance_account/finance_account_misc_report/clubs');
		$menu['acc_sal']['Report']['Monthly Report']['Salary Bill Payment'] = site_url('finance_account/finance_account_misc_report/salary_bill_payments');
        $menu['acc_sal']['Report']['Monthly Report']['Deductions'] = site_url('finance_account/finance_account_misc_report/salary_bill_deduction');
		$menu['acc_sal']['Report']['Monthly Report']['PF Subscription '] = site_url('finance_account/finance_account_misc_report/pf');
		$menu['acc_sal']['Report']['Monthly Report']['Professional Tax'] = site_url('finance_account/finance_account_misc_report/proftax');
        $menu['acc_sal']['Report']['Monthly Report']['Payroll Report '] = site_url('finance_account/finance_account_misc_report/payroll');
		$menu['acc_sal']['Report']['Monthly Report']['TDS Report'] = site_url('finance_account/tds_report_generator');
        return $menu;
	}
}
?>