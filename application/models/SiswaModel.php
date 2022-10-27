<?php
defined('BASEPATH') or exit('No direct script access allowed');

class SiswaModel extends CI_Model
{

	private $table      = 'siswa';
	private $primaryKey = 'id_siswa';

	private function _setBuilder()
	{
		$columnOrder	= [$this->primaryKey];
		$columnSearch	= array_merge($this->db->list_fields($this->table), $this->db->list_fields('kelas'));
		$orderBy		= [$this->primaryKey => 'desc'];

		$this->db->join('kelas', 'kelas.id_kelas = siswa.kelas_id', 'left');
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

/* End of file SiswaModel.php */
/* Location: ./application/models/SiswaModel.php */