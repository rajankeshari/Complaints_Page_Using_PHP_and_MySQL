<?PHP

if (!defined('BASEPATH'))
    exit('No direct script access allowed');

class Medicine_model extends CI_Model {

    var $table = 'hc_medicine';
    var $manuf = 'hc_manufacturer';
    var $m_receive = 'hc_medi_receive';
    var $t_po = 'hc_pur_order';
    var $hc = 'hc_indent_description';
    var $hc_mstore = 'hc_mainstore_stock';
    var $hc_mexpdt = 'hc_medi_expdate';
    var $hc_med_gr = 'hc_med_group';

    public function __construct() {
        parent::__construct();
    }

    function insert($data) {
        if ($this->db->insert($this->table, $data))
            return $this->db->insert_id();
        else
            return FALSE;
    }

    function insert_count_master($data) {
        if ($this->db->insert('hc_counter_master', $data))
            return TRUE;
        else
            return FALSE;
    }

    function insert_medi_group($data) {
        if ($this->db->insert($this->hc_med_gr, $data))
            return TRUE;
        else
            return FALSE;
    }

    //---------------Main Stock Insert and update---------
    function insert_mainstore($data) {
        if ($this->db->insert($this->hc_mstore, $data))
            return True;
        else
            return FALSE;
    }

    function update_main_stock($mid, $qty) {
        $tqty = $this->db->select('ms_qty')->get_where('hc_mainstore_stock', array('m_id' => $mid));

        if ($tqty->num_rows() > 0) {
            $data['ms_qty'] = ($tqty->row()->ms_qty + $qty);

            $this->db->update('hc_mainstore_stock', $data, array('m_id' => $mid));
        } else {
            return false;
        }


        /* $myquery="update hc_mainstore_stock set ms_qty=ms_qty+".$qty." where m_id=".$mid;

          $query = $this->db->query($myquery);


          if($query->num_rows() > 0)
          {
          return true;
          }
          else
          {
          return false;
          } */
    }

    //---------------------------------------------------------





    function get_medicine_list() {
        $this->db->from($this->table);
        $this->db->order_by('m_name');
        $result = $this->db->get();
        $return = array();
        if ($result->num_rows() > 0) {
            foreach ($result->result_array() as $row) {
                $return[$row['m_id']] = $row['m_name'];
            }
        }

        return $return;
    }

    function get_medicine_list1() {
        $this->db->from($this->table);
        $this->db->order_by('m_name');
        $query = $this->db->get();
        $query_result = $query->result();
        return $query_result;
    }

    function get_medicine_group() {

        // $this->db->distinct();
        $this->db->from('hc_med_group');
        $this->db->order_by("mgroupc");
        $query = $this->db->get();
        $query_result = $query->result();
        return $query_result;
    }

    function get_supplier_bymedicineid($med_id) {

        $query = $this->db->query("SELECT hc_supplier.s_name,hc_supplier.s_id
FROM ((hc_medicine INNER JOIN hc_manufacturer ON hc_medicine.manu_id = hc_manufacturer.manu_id) INNER JOIN hc_supp_manu ON hc_manufacturer.manu_id = hc_supp_manu.manu_id) INNER JOIN hc_supplier ON hc_supp_manu.s_id = hc_supplier.s_id
WHERE hc_medicine.m_id='" . $med_id . "'");

        if ($query->num_rows() > 0)
            return $query->result();
        else
            return false;
    }

    // For auto complete////

    function getAMed($id = '') {
        $row_set = array();
        $query = $this->db->select('m_id, m_name')->like('m_name', $id);
        $q = $query->get($this->table);

        if ($q->num_rows > 0) {
            foreach ($q->result_array() as $row) {
                $new_row['label'] = htmlentities(stripslashes($row['m_name']));
                $new_row['value'] = htmlentities(stripslashes($row['m_name']));
                $row_set[] = $new_row; //build an array
            }
        }
        echo json_encode($row_set); //format the array into json data
    }

    function getMedcineIdByName($id, $t = '') {
        $this->db->select('*');
        $this->db->from('hc_medicine a');
        $this->db->join('hc_manufacturer b', 'b.manu_id=a.manu_id', 'left');
        $this->db->where('LOWER(a.m_name)', $id);
        //  $this->db->order_by('c.track_title','asc');         
        $q = $this->db->get();


        if ($q->num_rows > 0) {
            if ($t)
                return $q->result_array();

            return $q->result();
        }
    }

    function getMedicineById($id, $t = '') {
        $this->db->select('*');
        $this->db->from('hc_medicine a');
        $this->db->join('hc_manufacturer b', 'b.manu_id=a.manu_id', 'left');
        $this->db->join('hc_medi_receive c', 'c.m_id=a.m_id', 'left');
        $this->db->where('a.m_id', $id);
        //  $this->db->order_by('c.track_title','asc');         
        $q = $this->db->get();


        if ($q->num_rows > 0) {
            if ($t)
                return $q->result_array();

            return $q->result();
        }
    }

    function get_po_indent() {
        $this->db->from($this->t_po);
        $this->db->order_by('po_refno');
        $this->db->where('DATEDIFF(now(),po_date)<=', 60);

        $result = $this->db->get();

        $return = array();
        if ($result->num_rows() > 0) {
            foreach ($result->result_array() as $row) {
                $return[$row['po_id']] = $row['po_refno'];
            }
        }

        return $return;
    }

    function get_po_outdated() {
        $this->db->from($this->t_po);
        $this->db->order_by('po_refno');
        $this->db->where('DATEDIFF(now(),po_date)>=', 60);

        $result = $this->db->get();

        $return = array();
        if ($result->num_rows() > 0) {
            foreach ($result->result_array() as $row) {
                $return[$row['po_id']] = $row['po_refno'];
            }
        }

        return $return;
    }

    function get_medi_list($id, $t = "") {
        // Working before calling Manu Id and Supplier ID
        /* $query = $this->db->query("SELECT hc_pur_order.po_id,hc_medicine.m_id,hc_medicine.m_name, hc_indent_description.ind_qty, hc_indent_description.std_pkt, hc_indent_description.app_rate
          FROM (hc_pur_order INNER JOIN hc_indent_description ON hc_pur_order.indent_id = hc_indent_description.indent_id) INNER JOIN hc_medicine ON hc_indent_description.m_id = hc_medicine.m_id
          WHERE hc_pur_order.po_id='".$id."'"); */

        $query = $this->db->query("
		SELECT 
  `hc_pur_order`.`po_id`,
  `hc_medicine`.`m_id`,
  `hc_medicine`.`m_name`,
  `hc_indent_description`.`ind_qty`,
  `hc_indent_description`.`std_pkt`,
  `hc_indent_description`.`app_rate`,
  `hc_indent`.`manu_id`,
  `hc_indent`.`s_id`,
  `hc_indent_description`.`tot_qty`
FROM
  `hc_pur_order`
  INNER JOIN `hc_indent_description` ON (`hc_pur_order`.`indent_id` = `hc_indent_description`.`indent_id`)
  INNER JOIN `hc_medicine` ON (`hc_indent_description`.`m_id` = `hc_medicine`.`m_id`)
  INNER JOIN `hc_indent` ON (`hc_indent_description`.`indent_id` = `hc_indent`.`indent_id`)

		WHERE hc_pur_order.po_id='" . $id . "'");

        if ($t == 1)
            return $query->result_array();
        else
            return $query->result();
    }

    function insertMedR($data) {
        if ($this->db->insert($this->m_receive, $data))
            return TRUE;
        else
            return FALSE;
    }

    function insert_expDetails($data, $qty, $mid, $dt) {
    //function insert_expDetails($data)

        //$tqty= $this->db->select('*')->get_where('hc_medi_expdate',array('m_id'=>$data['m_id'],'exp_date'=>$data['exp_date']));
        $tqty = $this->db->get_where('hc_medi_expdate', array('m_id' => $mid, 'exp_date' => $dt));
        if ($tqty->num_rows() > 0) {
            $data['qty'] = ($tqty->row()->qty + $qty);

            $this->db->update('hc_medi_expdate', $data, array('m_id' => $mid, 'exp_date' => $dt));
            return TRUE;
        } else {
            if ($this->db->insert($this->hc_mexpdt, $data))
                return TRUE;
        }
    }

    function get_all_invoice() {
        $sql = "select distinct invoice_no from hc_medi_receive a order by invoice_no";

        $query = $this->db->query($sql);


        if ($this->db->affected_rows() > 0) {
            return $query->result();
        } else {
            return false;
        }
    }

    function getAll_Medicine() {
        $sql = "select a.*,
b.ms_qty,
CASE WHEN c.cs_qty is null THEN 0 ELSE c.cs_qty END as cs_qty,
(b.ms_qty+CASE WHEN c.cs_qty is null THEN 0 ELSE c.cs_qty END)as total,
d.manu_name  
from hc_medicine a
inner join hc_mainstore_stock b on a.m_id=b.m_id
left join hc_counter_master c on c.m_id=a.m_id
inner join hc_manufacturer d on d.manu_id=a.manu_id order by a.m_id
";

        $query = $this->db->query($sql);


        if ($this->db->affected_rows() >= 0) {
            return $query->result();
        } else {
            return false;
        }





        /* $this->db->select('*');
          $this->db->from('hc_medicine');
          $this->db->join('hc_manufacturer', 'hc_medicine.manu_id = hc_manufacturer.manu_id');

          $query = $this->db->get();

          if($query->num_rows() > 0)
          {

          return $query->result();
          }
          else
          {
          return FALSE;
          } */
    }

    function getAll_Medicine_group() {


        $this->db->select('*');
        $this->db->from('hc_med_group');


        $query = $this->db->get();

        if ($query->num_rows() > 0) {

            return $query->result();
        } else {
            return FALSE;
        }
    }

    function getAll_Medicine_stock($medi_id) {
        $sql = "SELECT 
  `hc_medicine`.`m_id`,
  `hc_medicine`.`m_name`,
  `hc_manufacturer`.`manu_name`,
  `hc_medi_receive`.`supp_date`,
  `hc_medicine`.`rack_no`,
  `hc_medicine`.`cabi_no`,
  `hc_medicine`.`c_stock`,
  `hc_medicine`.`threshold`,
  
FROM
  `hc_medi_receive`
  INNER JOIN `hc_medicine` ON (`hc_medi_receive`.`m_id` = `hc_medicine`.`m_id`)
  INNER JOIN `hc_manufacturer` ON (`hc_medicine`.`manu_id` = `hc_manufacturer`.`manu_id`)
WHERE
  1 = 1

  ";
        if ($medi_id) {
            $sql .= " AND hc_medicine.m_id='" . $medi_id . "'";
        }


        $query = $this->db->query($sql);
        if ($query->num_rows() == 0)
            return FALSE;
        return $query->result();
    }

    function getMrecQtyById($id) {

        $q = $this->db->select_sum('mrec_qty')->get_where($this->m_receive, array('m_id' => $id));
        return $q->row();
    }

    function get_Medi_By_ManuID($id) {

        /* $this->db->select('*');
          $this->db->from($this->table);
          $this->db->join($this->manuf, $this->table.manu_id = $this->manuf.manu_id);
          $query = $this->db->get(); */

        //$query = $this->db->query("SELECT * FROM hc_medicine");
        $this->db->select('*');
        $this->db->from('hc_medicine');
        $this->db->join('hc_manufacturer', 'hc_medicine.manu_id = hc_manufacturer.manu_id');
        $this->db->where('hc_manufacturer.manu_id', $id);
        $query = $this->db->get();

        if ($query->num_rows() > 0) {

            return $query->result();
        } else {
            return FALSE;
        }
    }

    function getMqtybyPo($id, $t = "") {

        $q = $this->db->get_where($this->m_receive, array('po_id' => $id));
        if ($q->num_rows() > 0) {
            if ($t) {
                return $q->result_array();
            } else {
                return $q->result();
            }
        } else
            return false;
    }

    function getMedi_rec() {

        $query = $this->db->query("SELECT
  `hc_medicine`.`m_name`,
  `hc_indent_description`.`tot_qty`,
  `hc_medi_receive`.`mrec_qty`,
  `hc_medi_receive`.`mfg_date`,
  `hc_medi_receive`.`exp_date`,
  `hc_medi_receive`.`batch_no`,
  `hc_medi_receive`.`supp_date`,
  `hc_medi_receive`.`mrp`,
  `hc_medi_receive`.`rate_of_pur`,
  `hc_medi_receive`.`amount`,
  `hc_medi_receive`.`invoice_no`,
  `hc_medi_receive`.`m_rec_id`,
  `hc_pur_order`.`po_refno`,
  `hc_indent_description`.`std_pkt`
FROM
  `hc_medi_receive`
  INNER JOIN `hc_medicine` ON `hc_medi_receive`.`m_id` = `hc_medicine`.`m_id`
  INNER JOIN `hc_indent_description` ON `hc_medicine`.`m_id` =
`hc_indent_description`.`m_id`
  INNER JOIN `hc_pur_order` ON `hc_medi_receive`.`po_id` =
`hc_pur_order`.`po_id`
");

        if ($query->num_rows() > 0)
            return $query->result();
        else
            return false;
    }

    function show_med_recBY_id($id) {
        $this->db->select('*');
        $this->db->from('hc_medi_receive');
        $this->db->where('m_rec_id', $id);
        $query = $this->db->get();

        if ($query->num_rows() > 0) {

            return $query->result();
        } else {
            return FALSE;
        }
    }

    function update_mreceice($id, $data) {
        $this->db->where('m_rec_id', $id);
        $this->db->update('hc_medi_receive', $data);
    }

    function get_manu_name($id) {
        $this->db->select('*');
        $this->db->from('hc_medicine');
        $this->db->join('hc_manufacturer', 'hc_medicine.manu_id = hc_manufacturer.manu_id');
        $this->db->where('hc_medicine.m_id', $id);
        $query = $this->db->get();

        if ($query->num_rows() > 0) {

            return $query->result_array();
        } else {
            return FALSE;
        }
    }

    function getAll_MedicineName() {

        $this->db->from($this->table);
        $this->db->order_by('m_name');
        $result = $this->db->get();
        $return = array();
        if ($result->num_rows() > 0) {
            foreach ($result->result_array() as $row) {
                $return[$row['m_id']] = $row['m_name'];
            }
        }

        return $return;
    }

    function getMedByGroup($g) {
        $q = $this->db->get_where($this->table, array('m_generic_nm' => $g));
        if ($q->num_rows() > 0) {
            return $q->result_array();
        }
    }

    function getAll_Medi_byID($id) {
        $query = $this->db->query("SELECT
  hc_medicine.*,
  hc_manufacturer.*,
  hc_med_type.*,
  hc_med_group.*
FROM hc_medicine
  INNER JOIN hc_manufacturer
    ON hc_medicine.manu_id = hc_manufacturer.manu_id
  INNER JOIN hc_med_group
    ON hc_medicine.m_generic_nm = hc_med_group.mgroupc_id
  INNER JOIN hc_med_type
    ON hc_medicine.mtype = hc_med_type.mtype_id
WHERE hc_medicine.m_id =" . $id);

        if ($query->num_rows() > 0)
            return $query->result();
        else
            return false;
    }

    function get_Medi_Name_byID($id) {


        $this->db->select('*');
        $this->db->from('hc_medicine');
        $this->db->join('hc_manufacturer', 'hc_medicine.manu_id = hc_manufacturer.manu_id');
        $this->db->where('hc_medicine.m_id', $id);
        $query = $this->db->get();

        if ($query->num_rows() > 0) {

            return $query->result();
        } else {
            return FALSE;
        }
    }

    function update_medi($id, $data) {
        $this->db->where('m_id', $id);
        $this->db->update('hc_medicine', $data);
    }

    function delete_MediBy_id($id) {
        $this->db->where('m_id', $id);
        $this->db->delete('hc_medicine');
        if ($this->db->affected_rows() > 0) {
            return TRUE;
        } else {
            return FALSE;
        }
    }

    public function check_medi_cstock($id) {
        $this->db->where('m_id', $id);
        $query = $this->db->get('hc_medicine');
        if ($query->num_rows() > 0) {
            return $query->row()->c_stock;
        } else {
            return false;
        }
    }

    public function check_medi_mainstock($id) {
        $this->db->where('m_id', $id);
        $query = $this->db->get('hc_mainstore_stock');
        if ($query->num_rows() > 0) {
            return $query->row()->ms_qty;
        } else {
            return false;
        }
    }

    function update_medi_mainstore($id, $fstock) {
        $myquery = "update hc_mainstore_stock set ms_qty=" . $fstock . " where m_id=" . $id;
        $query = $this->db->query($myquery);
    }

    public function check_medi_exist($id) {
        $this->db->where('m_name', $id);
        $query = $this->db->get('hc_medicine');
        if ($query->num_rows() > 0) {
            return true;
        } else {
            return false;
        }
    }

    // Medicine Group Exists 

    public function check_medi_group_exist($id) {
        $this->db->where('mgroupc', $id);
        $query = $this->db->get('hc_med_group');
        if ($query->num_rows() > 0) {
            return true;
        } else {
            return false;
        }
    }

    //---------------------------------------


    function getAll_Medicine_BYID($medi_id) {


        $this->db->select('*');
        $this->db->from('hc_medicine');
        $this->db->join('hc_manufacturer', 'hc_medicine.manu_id = hc_manufacturer.manu_id');
        $this->db->where('m_id', $medi_id);

        $query = $this->db->get();

        if ($query->num_rows() > 0) {

            return $query->result();
        } else {
            return FALSE;
        }
    }

    // This function is rewritten to return the row as query->row
    function get_medicine_nmBY_Id($mid) {
        $this->db->select('m_name');
        $this->db->from('hc_medicine');

        $this->db->where('m_id', $mid);

        $query = $this->db->get();

        if ($query->num_rows() > 0) {

            return $query->row();
        } else {
            return FALSE;
        }
    }

    function get_group_nmBY_Id($id) {
        $this->db->select('*');
        $this->db->from('hc_med_group');

        $this->db->where('mgroupc_id', $id);

        $query = $this->db->get();

        if ($query->num_rows() > 0) {

            return $query->row();
        } else {
            return FALSE;
        }
    }

    // 10 August 2015

    function update_medi_group_name($id, $data) {
        $myquery = "update hc_med_group set mgroupc='" . $data . "' where mgroupc_id=" . $id;
        $query = $this->db->query($myquery);
    }

    function get_opening_stock() {

        $query = $this->db->query("SELECT * FROM `hc_medicine` INNER JOIN `hc_mainstore_stock` ON `hc_medicine`.`m_id` =
                `hc_mainstore_stock`.`m_id`");

        if ($query->num_rows() > 0)
            return $query->result();
        else
            return false;
    }

    function update_medi_table($mid, $pstock) {
        $myquery = "update hc_medicine set c_stock='" . $pstock . "' where m_id=" . $mid;
        $query = $this->db->query($myquery);
    }

    function update_medi_mainstore_table($mid, $cstock, $pstock) {
        //$myquery="update hc_mainstore_stock set ms_qty='".$pstock."' where m_id=".$mid;
        $ee = $this->medicine_model->check_medi_mainstock($this->input->post('m_id'));
        $fs = ($ee - $cstock) + $pstock;
        $myquery = "update hc_mainstore_stock set ms_qty='" . $fs . "' where m_id=" . $mid;
        $query = $this->db->query($myquery);
    }

    //-------------------Insert counter medicine receive temp and update counter master

    function Insert_counter_med_rec_Temp($data) {
        if ($this->db->insert('hc_counter_med_receive_temp', $data))
            return TRUE;
        else
            return FALSE;
    }

    //------------------------------------------------------------------------------------------

    function get_invoice_by_invoice_number($id) {

        $sql = "select b.m_name,a.mrec_qty,a.exp_date,a.batch_no,a.mrp,a.amount,a.supp_date from  hc_medi_receive a 
inner join hc_medicine b on b.m_id=a.m_id
where a.invoice_no=?";

        $query = $this->db->query($sql, array($id));


        if ($this->db->affected_rows() > 0) {
            return $query->result();
        } else {
            return false;
        }
    }
    
    
    function getAll_Products() {

        $sql = "SELECT * FROM (`hc_medicine`) ORDER BY trim(`m_name`)";

        $query = $this->db->query($sql);


        if ($this->db->affected_rows() > 0) {
            return $query->result();
        } else {
            return false;
        }
    }


}

?>