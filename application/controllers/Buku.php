<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Buku extends CI_Controller
{

	public function __construct()
	{
		parent::__construct();
		logged_in();
	}

	public function index()
	{
		$data = array(
			'title' => 'Buku',
		);

		$this->include->layout('index_buku', $data);
	}

	public function list_buku()
	{
		$this->load->model('BukuModel', 'buku');
		$bulider = $this->buku->getDataTable();
		$data 	 = array();
		$start 	 = $this->input->post('start');
		$no  	 = $start > 0 ? $start + 1 : 1;
		foreach ($bulider['dataTable'] as $field) {
			$start++;
			$row 	= array();

			$kode_buku 		= $field->kode_buku ? $field->kode_buku : '-';
			$nama_buku 		= $field->nama_buku ? $field->nama_buku : '-';
			$jumlah_buku 	= $field->jumlah_buku ? $field->jumlah_buku : 0;
			$buku_tersedia 	= $jumlah_buku - count($this->buku->findBukuTersedia($field->id_buku));

			$aksi = '<div class="btn-group btn-group-sm">';
			$aksi .= '<button type="button" onclick="edit_data(' . $field->id_buku . ')" class="btn btn-success"><i class="fas fa-edit"></i></button>';
			$aksi .= '<button type="button" onclick="delete_data(' . "'" . base64_encode($field->id_buku) . "'" . ')" class="btn btn-danger"><i class="fas fa-trash"></i></button>';
			$aksi .= '</div>';
			$aksi .= '<input type="hidden" name="kode_buku_' . $field->id_buku . '" value="' . $field->kode_buku . '">';
			$aksi .= '<input type="hidden" name="nama_buku_' . $field->id_buku . '" value="' . $field->nama_buku . '">';
			$aksi .= '<input type="hidden" name="jumlah_buku_' . $field->id_buku . '" value="' . $field->jumlah_buku . '">';

			$row[]  = '<div style="text-align: center;">' . $no++ . '</div>';
			$row[]  = '<div style="text-align: left;">' . $kode_buku . '</div>';
			$row[]  = '<div style="text-align: left;">' . $nama_buku . '</div>';
			$row[]  = '<div style="text-align: left;">' . $jumlah_buku . '</div>';
			$row[]  = '<div style="text-align: left;">' . $buku_tersedia . '</div>';
			$row[]  = '<div style="text-align: center;">' . $aksi . '</div>';

			$data[]	= $row;
		}

		$output = array(
			'draw' 				=> $this->input->post('draw'),
			'recordsTotal'		=> $bulider['recordsTotal'],
			'recordsFiltered' 	=> $bulider['recordsFiltered'],
			'data' 				=> $data,
		);

		echo json_encode($output);
	}

	public function save_buku()
	{
		$query = $this->db->get_where('buku', ['id_buku' => $this->input->post('id_buku')])->row();

		if (isset($query->id_buku)) {
			$unique_kode_buku =  $query->kode_buku != $this->input->post('kode_buku') ? '|is_unique[buku.kode_buku]' : '';
		} else {
			$unique_kode_buku = '|is_unique[buku.kode_buku]';
		}

		$this->load->library('form_validation');

		$list_fields = array(
			'kode_buku' 	=> ['Kode Buku' => 'trim|required|numeric' . $unique_kode_buku],
			'nama_buku' 	=> ['Nama Buku' => 'trim|required'],
			'jumlah_buku' 	=> ['Jumlah Buku' => 'trim|required|numeric|is_natural'],
		);

		$this->form_validation->set_error_delimiters('', '');
		foreach ($list_fields as $key1 => $value1) {
			foreach ($value1 as $label => $rules) {
				$this->form_validation->set_rules($key1, $label, $rules);
			}
		}

		$this->form_validation->set_message('required', '{field} harus diisi.');
		$this->form_validation->set_message('numeric', '{field} hanya boleh berisi angka.');
		$this->form_validation->set_message('is_natural', '{field} hanya boleh berisi angka.');
		$this->form_validation->set_message('is_unique', '{field} sudah terdaftar.');

		if ($this->form_validation->run() == FALSE) {

			$list_errors = array();
			foreach ($list_fields as $key => $value) {
				$list_errors[$key] = form_error($key);
			}

			$output = array(
				'status'	=> false,
				'errors'	=> $list_errors,
			);
		} else {

			$field_names = array_keys($list_fields);
			$data = array();
			for ($i = 0; $i < count($field_names); $i++) {
				$data[$field_names[$i]] = $this->input->post($field_names[$i]) ? $this->input->post($field_names[$i]) : null;
			}

			if (count($data) >= 1) {
				if (isset($query->id_buku)) {
					$this->db->update('buku', $data, ['id_buku' => $query->id_buku]);
					$message = 'Mengubah Buku Berhasil!';
				} else {
					$this->db->insert('buku', $data);
					$message = 'Menambah Buku Berhasil!';
				}
			}

			$output = array(
				'status' 	=> true,
				'message'	=> $message,
			);
		}

		echo json_encode($output);
	}

	public function delete_buku($id_buku = null)
	{
		$query = $this->db->get_where('buku', ['id_buku' => base64_decode($id_buku)])->row();
		if (empty($query->id_buku)) {
			show_404();
		}
		$this->db->delete('buku', ['id_buku' => $query->id_buku]);
		echo json_encode([
			'status'	=> true,
			'message'	=> 'Menghapus Buku Berhasil!'
		]);
	}

	public function import_excel()
	{
		$this->load->library('excel');
		$this->load->library('user_agent');

		if ($_FILES['file_excel']['name']) {
			$path 	= $_FILES['file_excel']['tmp_name'];
			$object = PHPExcel_IOFactory::load($path);
			$sheet  = $object->getActiveSheet()->toArray(true, true, true, true, true, true, true, true, true, true, true, true, true, true, true, true);

			$insert = array();

			foreach ($sheet as $key => $value) {
				if ($key < 2) {
					continue;
				}

				$id_buku = $value['A'];

				if ($id_buku) {

					$cek_id_buku = $this->db->get_where('buku', ['id_buku' => $id_buku])->row();


					if (empty($cek_id_buku->id_buku)) {

						$data = array(
							'id_buku' 				=> $id_buku,
							'kode_buku' 			=> $value['B'] != null ? htmlspecialchars($value['B']) : null,
							'nama_buku' 			=> $value['C'] != null ? htmlspecialchars($value['C']) : null,
							'jumlah_buku' 			=> $value['D'] != null ? htmlspecialchars($value['D']) : null,
						);

						$this->db->set($data);
						$buku = $this->db->insert('buku', $data);
						if ($buku < 0) {
							$insert[] = $this->db->insert([
								'id_buku' => $this->db->insert_id(),
							]);
						}
					}
				}
			}

			if (count($insert) > 0) {
				$this->session->set_flashdata('success', '<div class="alert alert-success alert-dismissible" id="alert"><span style="color: #31CE36; font-weight: bold;">Berhasil Menambah Buku</span></div>');
			}

			redirect('buku');
		} else {
			show_404();
		}
	}
}
/* End of file Buku.php */
/* Location: ./application/controllers/Buku.php */
