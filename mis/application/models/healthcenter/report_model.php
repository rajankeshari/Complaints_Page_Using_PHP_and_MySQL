<?PHP

if (!defined('BASEPATH'))
    exit('No direct script access allowed');

class Report_model extends CI_Model {

    var $table = 'hc_medicine';

    public function __construct() {
        parent::__construct();
    }

    function getData($supp_id, $manu_id, $from_date, $to_date) {

        $sql = "SELECT
		`hc_medicine`.`m_id`,
  `hc_medicine`.`m_name`,
  `hc_medi_receive`.`supp_date`,
  `hc_medi_receive`.`mrec_qty`,
  `hc_manufacturer`.`manu_name`
FROM
  `hc_indent`
  INNER JOIN `hc_supplier` ON (`hc_indent`.`s_id` = `hc_supplier`.`s_id`)
  INNER JOIN `hc_pur_order` ON (`hc_indent`.`indent_id` = `hc_pur_order`.`indent_id`)
  INNER JOIN `hc_medi_receive` ON (`hc_pur_order`.`po_id` = `hc_medi_receive`.`po_id`)
  INNER JOIN `hc_medicine` ON (`hc_medi_receive`.`m_id` = `hc_medicine`.`m_id`)
  INNER JOIN `hc_manufacturer` ON (`hc_medicine`.`manu_id` = `hc_manufacturer`.`manu_id`)
WHERE
  1 = 1  ";

        //print_r($sql);
        //die;

        if ($supp_id) {
            $sql .= " AND hc_supplier.s_id='" . $supp_id . "'";
        }
        if ($manu_id) {
            $sql .= " AND hc_manufacturer.manu_id='" . $manu_id . "'";
        }


        if ($from_date != '1970-01-01' && $to_date != '1970-01-01') {
            $sql .= " AND supp_date BETWEEN CAST('" . $from_date . "' AS DATE) AND CAST('" . $to_date . "' AS DATE)";
        }

        //print_r($sql);
        //	die;
        $query = $this->db->query($sql);
        if ($query->num_rows() == 0)
            return FALSE;
        return ($query->result());


        /* $query = $this->db->query("SELECT
          `hc_medicine`.`m_name`,
          `hc_medi_receive`.`supp_date`,
          `hc_medi_receive`.`mrec_qty`,
          `hc_manufacturer`.`manu_name`
          FROM
          `hc_indent`
          INNER JOIN `hc_supplier` ON (`hc_indent`.`s_id` = `hc_supplier`.`s_id`)
          INNER JOIN `hc_pur_order` ON (`hc_indent`.`indent_id` = `hc_pur_order`.`indent_id`)
          INNER JOIN `hc_medi_receive` ON (`hc_pur_order`.`po_id` = `hc_medi_receive`.`po_id`)
          INNER JOIN `hc_medicine` ON (`hc_medi_receive`.`m_id` = `hc_medicine`.`m_id`)
          INNER JOIN `hc_manufacturer` ON (`hc_medicine`.`manu_id` = `hc_manufacturer`.`manu_id`)
          WHERE
          `hc_supplier`.`s_id` ='".$id."'");

          if($query->num_rows() > 0)
          return $query->result();
          else
          return false; */
    }

    /* function getData()
      {

      $this->db->select('*');
      $this->db->from('hc_medicine');
      $this->db->join('hc_manufacturer', 'hc_medicine.manu_id = hc_manufacturer.manu_id');
      $this->db->order_by('hc_medicine.m_name','asc');
      $query = $this->db->get();
      if($query->num_rows() > 0)
      {
      return $query->result();
      }
      else
      {
      return FALSE;
      }
      } */

    function getData_manu($manu_id, $from_date, $to_date) {

        $sql = "SELECT
   `hc_medicine`.`m_id`,
  `hc_medicine`.`m_name`,
  `hc_manufacturer`.`manu_name`,
  `hc_medi_receive`.`supp_date`,
  `hc_medi_receive`.`mrec_qty`
FROM
  `hc_medi_receive`
  INNER JOIN `hc_medicine` ON (`hc_medi_receive`.`m_id` = `hc_medicine`.`m_id`)
  INNER JOIN `hc_manufacturer` ON (`hc_medicine`.`manu_id` = `hc_manufacturer`.`manu_id`)
WHERE
  1 = 1
  ";


        if ($manu_id) {
            $sql .= " AND hc_manufacturer.manu_id='" . $manu_id . "'";
        }
        if ($from_date != '1970-01-01' && $to_date != '1970-01-01') {
            $sql .= " AND supp_date BETWEEN CAST('" . $from_date . "' AS DATE) AND CAST('" . $to_date . "' AS DATE)";
        }
        $query = $this->db->query("$sql");
        if ($query->num_rows() == 0)
            return FALSE;
        return $query->result();
    }


// The old function is collecting complete data since purchase  which is wrong. It should display only left-out stock
//in our main stock and counter stock
    function getData_Exp($from_date, $to_date) {

        $sql = "SELECT
`hc_medicine`.`m_id`,
`hc_medicine`.`m_name`,
`hc_manufacturer`.`manu_name`,
`hc_medi_receive`.`supp_date`,
`hc_medi_receive`.`mfg_date`,
`hc_medi_receive`.`exp_date`,
`hc_medi_receive`.`mrec_qty`,
`hc_mainstore_stock`.`ms_qty`,
`hc_counter_master`.`cs_qty`,

(hc_mainstore_stock.ms_qty+ hc_counter_master.cs_qty) as total_qty
FROM
`hc_medicine`
INNER JOIN `hc_manufacturer` ON (`hc_medicine`.`manu_id` = `hc_manufacturer`.`manu_id`)
INNER JOIN `hc_medi_receive` ON (`hc_medicine`.`m_id` = `hc_medi_receive`.`m_id`)
left join hc_mainstore_stock on (`hc_medicine`.`m_id` = `hc_mainstore_stock`.`m_id`)
left join hc_counter_master on (`hc_medicine`.`m_id` = `hc_counter_master`.`m_id`)
where 1=1 and
(hc_mainstore_stock.ms_qty+ hc_counter_master.cs_qty) > 0 ";

        if ($from_date != '1970-01-01' && $to_date != '1970-01-01') {
            $sql .= " AND exp_date BETWEEN CAST('" . $from_date . "' AS DATE) AND CAST('" . $to_date . "' AS DATE)";
        }
        $sql.="group by hc_medicine.m_id order by hc_medi_receive.exp_date";
      //  echo $sql ; die();
		$query = $this->db->query("$sql");

		if ($query->num_rows() == 0)
            return FALSE;
        return $query->result();
    }


/*	function getData_Exp($from_date, $to_date) {

        $sql = "SELECT
  `hc_medicine`.`m_id`,
  `hc_medicine`.`m_name`,
  `hc_manufacturer`.`manu_name`,
  `hc_medi_receive`.`supp_date`,
  `hc_medi_receive`.`mfg_date`,
  `hc_medi_receive`.`exp_date`,
  `hc_medi_receive`.`mrec_qty`
FROM
  `hc_medicine`
  INNER JOIN `hc_manufacturer` ON (`hc_medicine`.`manu_id` = `hc_manufacturer`.`manu_id`)
  INNER JOIN `hc_medi_receive` ON (`hc_medicine`.`m_id` = `hc_medi_receive`.`m_id`)
  where 1=1

  ";

        if ($from_date != '1970-01-01' && $to_date != '1970-01-01') {
            $sql .= " AND exp_date BETWEEN CAST('" . $from_date . "' AS DATE) AND CAST('" . $to_date . "' AS DATE)";
        }
        $sql.=" order by hc_medi_receive.exp_date";
        $query = $this->db->query("$sql");
        echo $this->db->last_query();
        if ($query->num_rows() == 0)
            return FALSE;
        return $query->result();
    }
*/
// The above function is collecting complete data since purchase  which is wrong. It should display only left-out stock
//in our main stock and counter stock

    function getData_medi($m_id) {

        /* $sql= "	SELECT * FROM  `hc_medicine` INNER JOIN `hc_manufacturer` ON (`hc_medicine`.`manu_id` = `hc_manufacturer`.`manu_id`)
          WHERE  `m_generic_nm` = (SELECT `hc_medicine`.`m_generic_nm` FROM `hc_medicine` WHERE `m_id` =".$m_id." )";


          $query = $this->db->query($sql); */
        $this->db->select('*');
        $this->db->from('hc_medicine');

        $this->db->join('hc_manufacturer', 'hc_medicine.manu_id=hc_manufacturer.manu_id', 'inner');
        $this->db->join('hc_med_group', 'hc_medicine.m_generic_nm=hc_med_group.mgroupc_id', 'inner');
        $this->db->join('hc_med_type', 'hc_medicine.mtype=hc_med_type.mtype_id', 'inner');
        $this->db->order_by('m_name', 'ASC');
        $this->db->where('m_id', $m_id);
        // $this->db->limit($limit, $offset);
        $query = $this->db->get();
        //return $query->result_array();
        if ($query->num_rows() == 0)
            return FALSE;
        else
            return $query->result();
    }

    function getData_group($g_id) {
        /*
          $sql= "
          SELECT *
          FROM
          `hc_medicine`
          INNER JOIN `hc_manufacturer` ON (`hc_medicine`.`manu_id` = `hc_manufacturer`.`manu_id`)
          WHERE
          `m_generic_nm` = '".$g_id."'


          ";


          $query = $this->db->query($sql);
          if($query->num_rows() == 0)	return FALSE;
          return $query->result(); */
        $this->db->select('*');
        $this->db->from('hc_medicine');

        $this->db->join('hc_manufacturer', 'hc_medicine.manu_id=hc_manufacturer.manu_id', 'inner');
        $this->db->join('hc_med_group', 'hc_medicine.m_generic_nm=hc_med_group.mgroupc_id', 'inner');
        $this->db->join('hc_med_type', 'hc_medicine.mtype=hc_med_type.mtype_id', 'inner');
        $this->db->order_by('m_name', 'ASC');
        $this->db->where('m_generic_nm', $g_id);
        // $this->db->limit($limit, $offset);
        $query = $this->db->get();
        //return $query->result_array();
        if ($query->num_rows() == 0)
            return FALSE;
        else
            return $query->result();
    }

    function getReport($opt) {

        if ($opt == "sup") {
            $sql = " Select * from hc_supplier";
        } else if ($opt == "manu") {
            $sql = " Select * from hc_manufacturer";
        } else if ($opt == "medi") {
            $sql = " Select * from hc_medicine";
        }

        $query = $this->db->query("$sql");
        if ($query->num_rows() > 0) {
            return $query->result();
        } else {
            return FALSE;
        }
    }
    function get_indent_by_supplier_id($sid,$sdate,$edate){

        $sql = "select b.*,a.s_id,a.indent_date,a.indent_ref_no,c.m_name,d.po_id,d.po_refno,e.mrec_qty,b.app_cost,e.amount,d.po_date,a.ind_type from hc_indent a
inner join hc_indent_description b on b.indent_id=a.indent_id
inner join hc_medicine c on c.m_id=b.m_id
inner join hc_pur_order d on d.indent_id=a.indent_id
left join hc_medi_receive e on e.po_id=d.po_id and e.m_id=b.m_id
where a.s_id=? and indent_date BETWEEN ? AND ?;";
        $query = $this->db->query($sql,array($sid,$sdate,$edate));
        if ($query->num_rows() > 0) {
            return $query->result();
        } else {
            return FALSE;
        }

    }

}

?>
