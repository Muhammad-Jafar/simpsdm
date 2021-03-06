<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class M_DaftarIzin extends CI_Model {

	public function izin_list_all() {
		$q=$this->db->select('p.nama, ic.id_izin, ic.id, ic.id_namaizin, ic.tempat, ic.tglawal, ic.tglakhir, ic.status, c.nama_izin, c.type')
				->from('tb_izin as ic')
				->join('tb_pegawai as p', 'p.id = ic.id', 'LEFT')
				->join('tb_namaizin as c', 'c.id_namaizin = ic.id_namaizin', 'LEFT')
				->where('ic.id', $this->session->userdata('user_id'))
				->get();
		return $q->result();
	}

	// YANG BARU
	public function dkebutuhan_list_all() {
		$q=$this->db->select(  'b.nama_bidang, 
								bt.type, bt.nama_kebutuhan, 
								db.id_dkebutuhan, db.nama_lengkap, db.alamat, db.nowa, db.nim, db.nip_nidn, db.prodi, 
								db.fakultas, db.tgl_pengajuan, db.tgl_mulai, db.tgl_akhir, db.status')
								
				->from('tb_dkebutuhan as db')
				->join('tb_bidang as b', 'b.id_bidang = db.id_bidang', 'LEFT')
				->join('tb_kebutuhan as bt', 'bt.id_kebutuhan = db.id_kebutuhan', 'LEFT')
				->get();
		return $q->result();
	}

	// YANG BARU
	public function dkebutuhan_list_ajax($json) 
	{
		$new_arr=array();$i=1;
		foreach ($json as $key => $value) 
		{
			$value->no=$i;
			switch ($value->status) 
			{
				case 'waiting':
					$label = 'warning';
					break;
				case 'rejected':
					$label = 'danger';
					break;
				case 'approved':
					$label = 'success';
					break;
			};
			// $diff  = date_diff( date_create($value->tgl_mulai), date_create($value->tgl_akhir) );
			// $value->lama_izin = $diff->format('%d hari');
			$value->tgl_mulai = date_format( date_create($value->tgl_mulai), 'd/m/Y');
			$value->tgl_akhir = date_format( date_create($value->tgl_akhir), 'd/m/Y');
			$value->status = '<label class="badge badge-'.$label.' text-uppercase">'.$value->status.'</label>';
			$new_arr[]=$value;
			$i++;
		}
		return $new_arr;
	}

	public function izin_list_ajax($json) {
		$new_arr=array();$i=1;
		foreach ($json as $key => $value) {
			$value->no=$i;
			switch ($value->status) {
				case 'waiting':
					$label = 'warning';
					break;
				case 'rejected':
					$label = 'danger';
					break;
				case 'approved':
					$label = 'success';
					break;
			};
			$diff  = date_diff( date_create($value->tglawal), date_create($value->tglakhir) );
			$value->lama_izin = $diff->format('%d hari');
			$value->tglawal = date_format( date_create($value->tglawal), 'd/m/Y');
			$value->tglakhir = date_format( date_create($value->tglakhir), 'd/m/Y');
			$value->n_status = $value->status;
			$value->status = '<label class="badge badge-'.$label.' text-uppercase">'.$value->status.'</label>';
			$new_arr[]=$value;
			$i++;
		}
		return $new_arr;
	}

	public function get_namaizin($type) {
		$q=$this->db->select('id_namaizin, nama_izin')->from('tb_namaizin')->where('type', $type)->get();
		return $q->result();
	}

	public function add_new($id_namaizin,$tglawal,$tglakhir,$tempat) {
		$d_t_d = array(
			'id_namaizin' => $id_namaizin,
			'id' => $this->session->userdata('user_id'),
			'tglawal' => $tglawal,
			'tglakhir' => $tglakhir,
			'tempat' => $tempat,
			'status' => 'waiting',
		);
		$this->db->insert('tb_izin', $d_t_d);
		$this->session->set_flashdata('msg_alert', 'Izin berhasil diajukan');
	}

	public function update($id_izin,$id_namaizin,$tglawal,$tglakhir,$tempat) {
		$d_t_d = array(
			'id_namaizin' => $id_namaizin,
			'tglawal' => $tglawal,
			'tglakhir' => $tglakhir,
			'tempat' => $tempat
		);
		$this->db->where( 'id_izin', $id_izin )->update('tb_izin', $d_t_d);
		$this->session->set_flashdata('msg_alert', 'Data izin berhasil diubah');
	}

	public function get_data_izin($id_izin) {
		$q=$this->db->select('b.nama_bidang, j.nama_jabatan, p.tanggal_lahir, p.nip, p.tempat_lahir, p.alamat, p.nama, ic.id_izin, ic.id, ic.id_namaizin, c.nama_izin, ic.tempat, ic.tglawal, ic.tglakhir, ic.status, c.type')
				->from('tb_izin as ic')
				->join('tb_pegawai as p', 'p.id = ic.id', 'LEFT')
				->join('tb_jabatan as j', 'j.id_jabatan = p.id_jabatan', 'LEFT')
				->join('tb_bidang as b', 'b.id_bidang = p.id_bidang', 'LEFT')
				->join('tb_namaizin as c', 'c.id_namaizin = ic.id_namaizin', 'LEFT')
				->where( 'ic.id_izin', $id_izin )
				->limit(1)->get();
		if( $q->num_rows() < 1 ) {
			redirect( base_url('/data_izin') );
		}
		return $q->row();
	}

	public function delete($id_izin) {
		$this->db->delete('tb_izin', array('id_izin' => $id_izin));
	}
	
}

/* End of file M_DaftarIzin.php */
/* Location: ./application/models/M_DaftarIzin.php */