<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class KelasModel extends CI_Model {

	private $table      = 'kelas';
	private $primaryKey = 'id_kelas';

	private function _setBuilder()
	{
		$columnOrder	= [$this->primaryKey];
		$columnSearch	= $this->db->list_fields($this->table);
		$orderBy		= [$this->primaryKey => 'desc'];

		$this->db->from($this->table);
		$this->include->setDataTables($columnOrder, $columnSearch, $orderBy);
	}

	public function getDataTable()
	{
		return array(
			'dataTable' 		=> $this->include->getResult($this->_setBuilder()),
			'recordsTotal'		=> $this->db->count_all_results($this->table),
			'recordsFiltered'	=> $this->db->get($this->_setBuilder())->num_rows(),
		);
	}

}

/* End of file KelasModel.php */
/* Location: ./application/models/KelasModel.php */