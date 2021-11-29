    <?php
class Admin_panel_email_model extends CI_Model{

    /*
        Description : This function goes through the permanent table
        for students (emaildata) and updates the values in the column 
        labeled as “Active” from 0 to 1, if and only if they are set 
        to 0 initially. Doing this means that the email requests which 
        were submitted by the students have been successfully created 
        by Google and are now ready for use. If the number of rows 
        affected by this update is 0 then this function returns a 
        false else, it returns a true.
    */
    public function validate_student_emails(){

        /*
            $values:- (datatype: associative array) , 
            stores the array with key labeled as “Active” 
            and corresponding value as 1.
        */
        $values=array('Active'=>1);
        $this->db->where('Active',0)->update('emaildata',$values);
        if($this->db->affected_rows()>0){
            
            return True;
        }
        else{

            return false;
        }
    }
    /*
        Description : This function goes through the permanent 
        table for employees (emaildata_emp) and updates the 
        values in the column labeled as “Active” from 0 to 1, 
        if and only if they are set to 0 initially. Doing this 
        means that the email requests which were submitted by 
        the employees have been successfully created by Google 
        and are now ready for use. If the number of rows affected 
        by this update is 0 then this function returns a false else, 
        it returns a true.
    */
    public function validate_employee_emails(){

        /*
            $values:- (datatype: associative array) , stores 
            the array with key labeled as “Active” and corresponding 
            value as 1.
        */
        $values=array('Active'=>1);
        
        $this->db->where('Active',0)->update('emaildata_emp',$values);
        if($this->db->affected_rows()>0){
            
            return True;
        }
        else{

            return false;
        }
    }
    /*
        Description : This function runs a SQL query to empty the 
        temporary table for student email requests. It returns 
        true if the number of rows affected by the query in the 
        database is more than 0 else it returns false.
    */
    public function delete_student_emails_from_temp_table(){

        $this->db->empty_table('email_form');
        if($this->db->affected_rows()>0){
            
            return True;
        }
        else{

            return false;
        }
    }
    /*
        Description : This function runs a SQL query to empty the temporary table for employee email requests. It returns true if the number of rows affected by the query in the database is more than 0 else it returns false.
    */
    public function delete_employee_emails_from_temp_table(){

        $this->db->empty_table('email_form_emp');
        if($this->db->affected_rows()>0){
            
            return True;
        }
        else{

            return false;
        }
    }
    /*
        Description : This function deletes the existing student email Id(s) of students which have graduated from the college from the permanent data table (emaildata).
    */
    public function delete_expired_email(){

        /*
            (datatype: int) The current system year
        */
        $year = (int) date("Y");
        /*
            $result:- (datatype: bool) This contains the return value of the executed query of deleting the expired emails
        */
        $result = $this->db->query('DELETE FROM emaildata WHERE year_of_passing<'.$year);
        if($this->db->affected_rows()>0){

            return true;
        }
        else{
            
            return false;
        }
    } 
    
    /*
        Description : This function runs a SQL query to get an associative array of all the entries in the permanent array which have not yet been validated i.e., their “Active” labeled columns are still set to 0.
    */
    public function get_list_of_inactive_rows_permanent_table_student(){

        /*
            $sql :- (datatype: associative array), stores the associative array obtained after running the SQL query to get the records of the students from the permanent table which have not yet been validated
        */
        $sql = $this->db->query('SELECT stu.admission_no,stu.password,stu.domain_name,stu.first_name,stu.middle_name,stu.last_name,stu.course,stu.branch,stu.department,stu.year_of_passing FROM emaildata AS stu INNER JOIN email_form AS stuf ON stuf.email = stu.domain_name')->result_array();
        return $sql;
    }
    
    /*
        Description : This function runs a SQL query to get an associative array of all the entries in the permanent table for employees(emaildata_emp) which have not yet been validated i.e., their “Active” labeled columns are still set to 0.
    */
    public function get_list_of_inactive_rows_permanent_table_employee(){

        /*
            $sql :- (datatype: associative array), stores the associative array obtained after running the SQL query to get the records of the students from the permanent table which have not yet been validated.
        */
        $sql = $this->db->query('SELECT emp.emp_id,emp.password,emp.domain_name,emp.first_name,emp.middle_name,emp.last_name,emp.designation,emp.department,emp.faculty FROM emaildata_emp AS emp INNER JOIN email_form_emp AS empf ON empf.email = emp.domain_name')->result_array();
        /*
		To check the exact query details
		echo $this->db->last_query(); die();
		*/
		//echo $this->db->last_query(); die();
		return $sql;
    }
    
    /*
        Description : This function gets the  list of entries in the student and employee temporary tables (email_form and email_form_emp respectively).
    */
    public function get_list_of_inactive_rows_emp_send_to_google(){

        /*
            $sql :- (datatype: associative array) Contains the query result for obtaining the email requests currently in the employee tables (email_form_emp).
        */
        $sql = $this->db->query('SELECT * FROM email_form_emp')->result_array();
        return $sql;
    }

	 /*
        Description : This function gets the  list of entries in the student and employee temporary tables (email_form and email_form_emp respectively).
    */
    public function get_list_of_inactive_rows_student_send_to_google(){

        /*
            $sql :- (datatype: associative array) Contains the query result for obtaining the email requests currently in the student tables (email_form ).
        */
        $sql = $this->db->query('SELECT * FROM email_form')->result_array();
        return $sql;
    }
		
	 /*
        Description : This function gets the  list of entries in the student and employee temporary tables (email_form and email_form_emp respectively).
    */
    public function get_list_of_inactive_rows_send_to_google(){

        /*
            $sql :- (datatype: associative array) Contains the query result for obtaining the email requests currently in the student and employee tables (email_form and email_form_emp respectively).
        */
        $sql = $this->db->query('SELECT * FROM email_form UNION ALL SELECT * FROM email_form_emp')->result_array();
        return $sql;
    }
	
	/*
        Description : This function gets the  list of all student from table emaildata. This is required to download all students data with email and year of passing
		Added by Rajesh Mishra
	*/
    public function get_list_of_all_rows_student(){

        /*
            $sql :- (datatype: associative array) Contains the query result for obtaining the email requests currently in the student and employee tables (email_form and email_form_emp respectively).
        */
        $sql = $this->db->query('SELECT * FROM emaildata')->result_array();
        return $sql;
    }

    /*
        Description : This function runs SQL queries into the student temporary table (email_form) and student temporary table(email_form_emp) to delete the entries with the email value same as the passed argument.
    */
    public function delete_email_row($email){

        /*
            $sql_for_student :- (datatype: bool) stores the value returned by the query to delete the entry in the student temporary table (email_form) with the email value same as the passed argument
        */
        $sql_for_student = $this->db->where('email',$email)->delete('email_form');
        /*
            $sql_for_employee :- (datatype: bool) stores the value returned by the query to delete the entry in the employee temporary table (email_form_emp) with the email value same as the passed argument
        */
        $sql_for_employee = $this->db->where('email',$email)->delete('email_form_emp');
    }
} 
?>