
<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Daftar_Keluhan extends CI_Controller {

	private $m_dk;

	public function __construct()
	{
		parent::__construct();
		isnt_pengguna(function() {
			redirect( base_url('auth/login') );
		});
		$this->load->model('M_DaftarKeluhan');
		$this->m_dk = $this->M_DaftarKeluhan;
	}

	public function index() {
		$data = generate_page('Daftar Keluhan', 'daftar_Keluhan', 'Pegawai');

		$data_content['title_page'] = 'Daftar Keluhan';
		$data['content'] = $this->load->view('partial/DaftarKeluhan/V_DaftarKeluhan_Read', $data_content, true);
		$this->load->view('V_DaftarIzin_Pegawai', $data);
	}

	public function list_ajax() {
		json_dump(function() {
			$result=$this->m_dk->izin_list_ajax( $this->m_dk->izin_list_all() );
			return array('data' => $result);
		});
	}

	public function nama_izin_ajax($type) {
		$this->c_type=$type;
		json_dump(function() {
			$result=$this->m_dk->get_namaizin($this->c_type);
			return $result;
		});
	}

	public function ajukan() {
		if( $_SERVER['REQUEST_METHOD'] == 'POST') {
			$id_namaizin= $this->security->xss_clean( $this->input->post('id_namaizin') );
			$tglawal= $this->security->xss_clean( $this->input->post('tglawal') );
			$tempat= $this->security->xss_clean( $this->input->post('tempat') );
			$tglakhir= $this->security->xss_clean( $this->input->post('tglakhir') );
			$this->form_validation->set_rules('type', 'Type Izin', 'required');
			$this->form_validation->set_rules('id_namaizin', 'Nama Izin', 'required');
			$this->form_validation->set_rules('tglawal', 'Tanggal Awal', 'required');
			$this->form_validation->set_rules('tempat', 'Tempat', 'required');
			$this->form_validation->set_rules('tglakhir', 'Tanggal Akhir', 'required');
			if(!$this->form_validation->run()) {
				$this->session->set_flashdata('msg_alert', validation_errors());
				redirect( base_url('daftar_izin/ajukan/' . $name) );
			}
			$this->m_dk->add_new(
				$id_namaizin,$tglawal,$tglakhir,$tempat
			);
			redirect( base_url('daftar_keluhan') );
		}
		$data = generate_page('Ajukan Keluhan', 'daftar_Keluhan/ajukan', 'Pegawai');

			$data_content['title_page'] = 'Ajukan Keluhan';
		$data['content'] = $this->load->view('partial/DaftarKeluhan/V_DaftarKeluhan_Create', $data_content, true);
		$this->load->view('V_DaftarKeluhan', $data);
	}

	public function edit($id) {
		if( $_SERVER['REQUEST_METHOD'] == 'POST') {
			$id_izin= $this->security->xss_clean( $this->input->post('id_izin') );
			$id_namaizin= $this->security->xss_clean( $this->input->post('id_namaizin') );
			$tglawal= $this->security->xss_clean( $this->input->post('tglawal') );
			$tempat= $this->security->xss_clean( $this->input->post('tempat') );
			$tglakhir= $this->security->xss_clean( $this->input->post('tglakhir') );
			$this->form_validation->set_rules('id_izin', 'ID Izin', 'required');
			$this->form_validation->set_rules('type', 'Type Izin', 'required');
			$this->form_validation->set_rules('id_namaizin', 'Nama Izin', 'required');
			$this->form_validation->set_rules('tglawal', 'Tanggal Awal', 'required');
			$this->form_validation->set_rules('tempat', 'Tempat', 'required');
			$this->form_validation->set_rules('tglakhir', 'Tanggal Akhir', 'required'); 

			if(!$this->form_validation->run()) {
				$this->session->set_flashdata('msg_alert', validation_errors());
				redirect( base_url('daftar_izin/edit/' . $name . '/' . $id) );
			}
			// to-do
			$this->m_di->update(
				$id_izin,$id_namaizin,$tglawal,$tglakhir,$tempat
			);
			redirect( base_url('daftar_izin') );
		}
		$data = generate_page('Edit Data Keluhan', 'daftar_izin/edit/' . $id, 'Pegawai');

			$data_content['title_page'] = 'Edit Data Keluhan';
			$data_content['data_izin'] = $this->m_dk->get_data_izin($id);
			$data_content['namaizin_list'] = $this->m_dk->get_namaizin( $data_content['data_izin']->type );
		$data['content'] = $this->load->view('partial/DaftarKeluhan/V_DaftarKeluhan_Edit', $data_content, true);
		$this->load->view('V_DaftarKeluhan', $data);
	}

	public function delete($id_izin) {
		$this->m_dk->delete($id_izin);
		$this->session->set_flashdata('msg_alert', 'Izin berhasil dihapus');
		redirect( base_url('daftar_izin') );
	}

}

/* End of file Daftar_Keluhan.php */
/* Location: ./application/controllers/Daftar_Keluhan.php */