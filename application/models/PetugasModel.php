<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class PetugasModel extends CI_Model {

	private $table      = 'petugas';
	private $primaryKey = 'id_petugas';

	private function _setBuilder()
	{
		$columnOrder	= [$this->primaryKey];
		$columnSearch	= $this->db->list_fields($this->table);
		$orderBy		= [$this->primaryKey => 'desc'];

		$this->db->where('id_petugas !=', $this->session->id_petugas);
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

/* End of file PetugasModel.php */
/* Location: ./application/models/PetugasModel.php */