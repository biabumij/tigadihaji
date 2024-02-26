<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Produksi extends Secure_Controller {

	public function __construct()
	{
        parent::__construct();
        // Your own constructor code
        $this->load->model(array('admin/m_admin','crud_global','m_themes','pages/m_pages','menu/m_menu','admin_access/m_admin_access','DB_model','member_back/m_member_back','m_member','pmm/pmm_model','admin/Templates','pmm/pmm_finance','produk/m_produk'));
        $this->load->library('enkrip');
		$this->load->library('filter');
		$this->load->library('waktu');
		$this->load->library('session');
    }	
	
	public function cetak_stock_opname()
	{
		$this->load->library('pdf');
	

		$pdf = new Pdf('P', 'mm', 'A4', true, 'UTF-8', false);
        $pdf->setPrintHeader(true);
        
        $pdf->SetFont('helvetica','',7); 
        $tagvs = array('div' => array(0 => array('h' => 0, 'n' => 0), 1 => array('h' => 0, 'n'=> 0)));
		$pdf->setHtmlVSpace($tagvs);
		        $pdf->AddPage('P');

		$arr_date = $this->input->get('filter_date');
		if(empty($arr_date)){
			$filter_date = '-';
		}else {
			$arr_filter_date = explode(' - ', $arr_date);
			$start_date = date('Y-m-d',strtotime($arr_filter_date[0]));
			$end_date = date('Y-m-d',strtotime($arr_filter_date[1]));
			$filter_date = date('d F Y',strtotime($arr_filter_date[0])).' - '.date('d F Y',strtotime($arr_filter_date[1]));
		}
		$data['filter_date'] = $filter_date;
		$data['start_date'] = $start_date;
		$data['end_date'] = $end_date;
		$data['date1'] = date('d F Y',strtotime($arr_filter_date[0]));
		$data['date2'] = date('d F Y',strtotime($arr_filter_date[1]));
		$filter_date = $this->input->get('filter_date');
		if(!empty($filter_date)){
			$arr_date = explode(' - ', $filter_date);
			$this->db->where('date >=',date('Y-m-d',strtotime($arr_date[0])));
			$this->db->where('date <=',date('Y-m-d',strtotime($arr_date[1])));
		}
		$query = $this->db->get('pmm_remaining_materials_cat');
		$data['data'] = $query->result_array();
		$data['custom_date'] = $this->input->get('custom_date');
        $html = $this->load->view('produksi/cetak_stock_opname',$data,TRUE);

        
        $pdf->SetTitle('Stock Opname');
        $pdf->nsi_html($html);
        $pdf->Output('Stock Opname.pdf', 'I');
	
	}

	public function form_hpp_bahan_baku()
	{
		$check = $this->m_admin->check_login();
		if ($check == true) {
			$data['products'] = $this->db->select('*')->get_where('produk', array('status' => 'PUBLISH', 'kategori_produk' => 1))->result_array();
			$this->load->view('produksi/form_hpp_bahan_baku', $data);
		} else {
			redirect('admin');
		}
	}

	public function submit_hpp_bahan_baku()
	{
		$date_hpp = $this->input->post('date_hpp');
		$semen = str_replace('.', '', $this->input->post('semen'));
		$pasir = str_replace('.', '', $this->input->post('pasir'));
		$batu1020 = str_replace('.', '', $this->input->post('batu1020'));
		$batu2030 = str_replace('.', '', $this->input->post('batu2030'));
		$solar = str_replace('.', '', $this->input->post('solar'));

		$this->db->trans_start(); # Starting Transaction
		$this->db->trans_strict(FALSE); # See Note 01. If you wish can remove as well 

		$arr_insert = array(
			'date_hpp' => date('Y-m-d', strtotime($date_hpp)),
			'semen' => $semen,
			'pasir' => $pasir,
			'batu1020' => $batu1020,
			'batu2030' => $batu2030,
			'solar' => $solar,
			'semen_custom' => 0,
			'semen_custom_nilai' => $semen,
			'status' => 'PUBLISH',
			'created_by' => $this->session->userdata('admin_id'),
			'created_on' => date('Y-m-d H:i:s')
		);

		$this->db->insert('hpp_bahan_baku', $arr_insert);

		if ($this->db->trans_status() === FALSE) {
			# Something went wrong.
			$this->db->trans_rollback();
			$this->session->set_flashdata('notif_error','<b>Data Gagal Disimpan</b>');
			redirect('/kunci_&_approval/hpp_bahan_baku');
		} else {
			# Everything is Perfect. 
			# Committing data to the database.
			$this->db->trans_commit();
			$this->session->set_flashdata('notif_success','<b>Data Berhasil Disimpan</b>');
			redirect('admin/kunci_&_approval');
		}
	}

	public function table_hpp_bahan_baku()
	{   
        $data = array();
		$filter_date = $this->input->post('filter_date');
		if(!empty($filter_date)){
			$arr_date = explode(' - ', $filter_date);
			$this->db->where('pp.date_hpp >=',date('Y-m-d',strtotime($arr_date[0])));
			$this->db->where('pp.date_hpp <=',date('Y-m-d',strtotime($arr_date[1])));
		}
        $this->db->select('pp.id, pp.date_hpp, pp.semen, pp.pasir, pp.batu1020, pp.batu2030, pp.solar, pp.status, pp.created_by, pp.created_on');
		$this->db->order_by('pp.date_hpp','desc');
		$query = $this->db->get('hpp_bahan_baku pp');
		
       if($query->num_rows() > 0){
			foreach ($query->result_array() as $key => $row) {
                $row['no'] = $key+1;
                $row['date_hpp'] = date('d F Y',strtotime($row['date_hpp']));
                $row['semen'] = number_format($row['semen'],0,',','.');
				$row['pasir'] = number_format($row['pasir'],0,',','.');
				$row['batu1020'] = number_format($row['batu1020'],0,',','.');
				$row['batu2030'] = number_format($row['batu2030'],0,',','.');
				$row['solar'] = number_format($row['solar'],0,',','.');
				$row['status'] = $row['status'];
				$row['admin_name'] = $this->crud_global->GetField('tbl_admin',array('admin_id'=>$row['created_by']),'admin_name');
                $row['created_on'] = date('d/m/Y H:i:s',strtotime($row['created_on']));

				if($this->session->userdata('admin_group_id') == 1 || $this->session->userdata('admin_group_id') == 5 || $this->session->userdata('admin_group_id') == 6 || $this->session->userdata('admin_group_id') == 16){
				$row['actions'] = '<a href="javascript:void(0);" onclick="DeleteDataHppBahanBaku('.$row['id'].')" class="btn btn-danger" style="font-weight:bold; border-radius:10px;"><i class="fa fa-close"></i> </a>';
				}else {
					$row['actions'] = '<button type="button" class="btn btn-danger" style="font-weight:bold; border-radius:10px;"><i class="fa fa-ban"></i> No Access</button>';
				}
				
                
                $data[] = $row;
            }

        }
        echo json_encode(array('data'=>$data));
    }

	public function delete_hpp_bahan_baku()
	{
		$output['output'] = false;
		$id = $this->input->post('id');
		if(!empty($id)){
			$this->db->delete('hpp_bahan_baku',array('id'=>$id));
			{
				$output['output'] = true;
			}
		}
		echo json_encode($output);
	}

	public function form_akumulasi()
	{
		$check = $this->m_admin->check_login();
		if ($check == true) {
			$data['products'] = $this->db->select('*')->get_where('produk', array('status' => 'PUBLISH', 'kategori_produk' => 1))->result_array();
			$this->load->view('produksi/form_akumulasi_bahan_baku', $data);
		} else {
			redirect('admin');
		}
	}

	public function submit_akumulasi()
	{
		$date_akumulasi = $this->input->post('date_akumulasi');
		$total_nilai_keluar = str_replace('.', '', $this->input->post('total_nilai_keluar'));
		$total_nilai_keluar_2 = str_replace('.', '', $this->input->post('total_nilai_keluar_2'));

		$this->db->trans_start(); # Starting Transaction
		$this->db->trans_strict(FALSE); # See Note 01. If you wish can remove as well 

		$arr_insert = array(
			'date_akumulasi' => date('Y-m-d', strtotime($date_akumulasi)),
			'total_nilai_keluar' => $total_nilai_keluar,
			'total_nilai_keluar_2' => $total_nilai_keluar_2,
			'status' => 'PUBLISH',
			'unit_head' => 48,
			'logistik' => 39,
			'admin' => 42,
			'keu' => 42,
			'created_by' => $this->session->userdata('admin_id'),
			'created_on' => date('Y-m-d H:i:s')
		);

		$this->db->insert('akumulasi', $arr_insert);

		if ($this->db->trans_status() === FALSE) {
			# Something went wrong.
			$this->db->trans_rollback();
			$this->session->set_flashdata('notif_error','<b>Data Gagal Disimpan</b>');
			redirect('/kunci_&_approval/akumulasi');
		} else {
			# Everything is Perfect. 
			# Committing data to the database.
			$this->db->trans_commit();
			$this->session->set_flashdata('notif_success','<b>Data Berhasil Disimpan</b>');
			redirect('admin/kunci_&_approval');
		}
	}

	public function table_akumulasi()
	{   
        $data = array();
		$filter_date = $this->input->post('filter_date');
		if(!empty($filter_date)){
			$arr_date = explode(' - ', $filter_date);
			$this->db->where('pp.date_akumulasi >=',date('Y-m-d',strtotime($arr_date[0])));
			$this->db->where('pp.date_akumulasi <=',date('Y-m-d',strtotime($arr_date[1])));
		}
        $this->db->select('pp.id, pp.date_akumulasi, pp.total_nilai_keluar, pp.total_nilai_keluar_2, pp.status, pp.created_by, pp.created_on');
		$this->db->order_by('pp.date_akumulasi','desc');
		$query = $this->db->get('akumulasi pp');
		
       if($query->num_rows() > 0){
			foreach ($query->result_array() as $key => $row) {
                $row['no'] = $key+1;
                $row['date_akumulasi'] = date('d F Y',strtotime($row['date_akumulasi']));
                $row['total_nilai_keluar'] = number_format($row['total_nilai_keluar'],0,',','.');
				$row['total_nilai_keluar_2'] = number_format($row['total_nilai_keluar_2'],0,',','.');
				$row['status'] = $row['status'];
				$row['admin_name'] = $this->crud_global->GetField('tbl_admin',array('admin_id'=>$row['created_by']),'admin_name');
                $row['created_on'] = date('d/m/Y H:i:s',strtotime($row['created_on']));

				if($this->session->userdata('admin_group_id') == 1 || $this->session->userdata('admin_group_id') == 5 || $this->session->userdata('admin_group_id') == 6 || $this->session->userdata('admin_group_id') == 16){
				$row['actions'] = '<a href="javascript:void(0);" onclick="DeleteDataAkumulasi('.$row['id'].')" class="btn btn-danger" style="font-weight:bold; border-radius:10px;"><i class="fa fa-close"></i> </a>';
				}else {
					$row['actions'] = '<button type="button" class="btn btn-danger" style="font-weight:bold; border-radius:10px;"><i class="fa fa-ban"></i> No Access</button>';
				}
                
                $data[] = $row;
            }

        }
        echo json_encode(array('data'=>$data));
    }

	public function delete_akumulasi()
	{
		$output['output'] = false;
		$id = $this->input->post('id');
		if(!empty($id)){
			$this->db->delete('akumulasi',array('id'=>$id));
			{
				$output['output'] = true;
			}
		}
		echo json_encode($output);
	}

	public function form_approval()
	{
		$check = $this->m_admin->check_login();
		if ($check == true) {
			$data['products'] = $this->db->select('*')->get_where('produk', array('status' => 'PUBLISH', 'kategori_produk' => 1))->result_array();
			$this->load->view('produksi/form_approval', $data);
		} else {
			redirect('admin');
		}
	}

	public function submit_approval()
	{
		$date_approval = $this->input->post('date_approval');
		$approval = $this->input->post('approval');

		$this->db->trans_start(); # Starting Transaction
		$this->db->trans_strict(FALSE); # See Note 01. If you wish can remove as well 

		$arr_insert = array(
			'date_approval' => date('Y-m-d', strtotime($date_approval)),
			'approval' => $approval,
			'ttd_1' => 'uploads/ttd_gery.png',
			'ttd_2' => 'uploads/ttd_theresia.png',
			'status' => 'PUBLISH',
			'created_by' => $this->session->userdata('admin_id'),
			'created_on' => date('Y-m-d H:i:s')
		);

		$this->db->insert('ttd_laba_rugi', $arr_insert);

		if ($this->db->trans_status() === FALSE) {
			# Something went wrong.
			$this->db->trans_rollback();
			$this->session->set_flashdata('notif_error', 'Gagal Menyetujui BUA, Diskonto & Persiapan !!');
			redirect('/kunci_&_approval/table_approval');
		} else {
			# Everything is Perfect. 
			# Committing data to the database.
			$this->db->trans_commit();
			$this->session->set_flashdata('notif_success', 'Berhasil Menyetujui BUA, Diskonto & Persiapan !!');
			redirect('admin/kunci_&_approval');
		}
	}

	public function table_approval()
	{   
        $data = array();
		$filter_date = $this->input->post('filter_date');
		if(!empty($filter_date)){
			$arr_date = explode(' - ', $filter_date);
			$this->db->where('ttd.date_approval >=',date('Y-m-d',strtotime($arr_date[0])));
			$this->db->where('ttd.date_approval <=',date('Y-m-d',strtotime($arr_date[1])));
		}
        $this->db->select('ttd.id, ttd.date_approval, a.admin_name, ttd.created_on, ttd.status');
		$this->db->join('tbl_admin a','ttd.created_by = a.admin_id','left');
		$this->db->order_by('ttd.date_approval','desc');
		$query = $this->db->get('ttd_evaluasi_bua ttd');
		
		
       if($query->num_rows() > 0){
			foreach ($query->result_array() as $key => $row) {
                $row['no'] = $key+1;
                $row['date_approval'] = date('d F Y',strtotime($row['date_approval']));
				$row['admin_name'] = $row['admin_name'];
				$row['created_on'] = date('d/m/Y H:i:s',strtotime($row['created_on']));
				$row['status'] = $row['status'];

				if($this->session->userdata('admin_group_id') == 1 || $this->session->userdata('admin_group_id') == 5 || $this->session->userdata('admin_group_id') == 10 || $this->session->userdata('admin_group_id') == 15){
				$row['actions'] = '<a href="javascript:void(0);" onclick="DeleteDataApproval('.$row['id'].')" class="btn btn-danger"><i class="fa fa-close"></i> </a>';
				}else {
					$row['actions'] = '-';
				}

                $data[] = $row;
            }

        }
        echo json_encode(array('data'=>$data));
    }

	
	public function delete_approval()
	{
		$output['output'] = false;
		$id = $this->input->post('id');
		if(!empty($id)){
			$this->db->delete('ttd_laba_rugi',array('id'=>$id));
			{
				$output['output'] = true;
			}
		}
		echo json_encode($output);
	}

	public function form_approval_laporan()
	{
		$check = $this->m_admin->check_login();
		if ($check == true) {
			$data['products'] = $this->db->select('*')->get_where('produk', array('status' => 'PUBLISH', 'kategori_produk' => 1))->result_array();
			$this->load->view('produksi/form_approval_laporan', $data);
		} else {
			redirect('admin');
		}
	}

	public function submit_approval_laporan()
	{
		$date_approval = $this->input->post('date_approval');
		$approval = $this->input->post('approval');

		$this->db->trans_start(); # Starting Transaction
		$this->db->trans_strict(FALSE); # See Note 01. If you wish can remove as well 

		$arr_insert = array(
			'date_approval' => date('Y-m-d', strtotime($date_approval)),
			'approval' => $approval,
			'ttd_1' => 'uploads/ttd_erika.png',
			'ttd_2' => 'uploads/ttd_debby.png',
			'status' => 'PUBLISH',
			'unit_head' => 48,
			'created_by' => $this->session->userdata('admin_id'),
			'created_on' => date('Y-m-d H:i:s')
		);

		$this->db->insert('ttd_laporan', $arr_insert);

		if ($this->db->trans_status() === FALSE) {
			# Something went wrong.
			$this->db->trans_rollback();
			$this->session->set_flashdata('notif_error','<b>Data Gagal Disimpan</b>');
			redirect('admin/kunci_&_approval');
		} else {
			# Everything is Perfect. 
			# Committing data to the database.
			$this->db->trans_commit();
			$this->session->set_flashdata('notif_success','<b>Data Berhasil Disimpan</b>');
			redirect('admin/kunci_&_approval');
		}
	}

	public function table_approval_laporan()
	{   
        $data = array();
		$filter_date = $this->input->post('filter_date');
		if(!empty($filter_date)){
			$arr_date = explode(' - ', $filter_date);
			$this->db->where('ttd.date_approval >=',date('Y-m-d',strtotime($arr_date[0])));
			$this->db->where('ttd.date_approval <=',date('Y-m-d',strtotime($arr_date[1])));
		}
        $this->db->select('ttd.id, ttd.date_approval, a.admin_name, ttd.created_on, ttd.status');
		$this->db->join('tbl_admin a','ttd.created_by = a.admin_id','left');
		$this->db->order_by('ttd.date_approval','desc');
		$query = $this->db->get('ttd_laporan ttd');
		
		
       if($query->num_rows() > 0){
			foreach ($query->result_array() as $key => $row) {
                $row['no'] = $key+1;
                $row['date_approval'] = date('d F Y',strtotime($row['date_approval']));
				$row['admin_name'] = $row['admin_name'];
				$row['created_on'] = date('d/m/Y H:i:s',strtotime($row['created_on']));
				$row['status'] = $row['status'];

				if($this->session->userdata('admin_group_id') == 1 || $this->session->userdata('admin_group_id') == 4 || $this->session->userdata('admin_group_id') == 11 || $this->session->userdata('admin_group_id') == 15){
				$row['actions'] = '<a href="javascript:void(0);" onclick="DeleteDataApprovalLaporan('.$row['id'].')" class="btn btn-danger" style="font-weight:bold; border-radius:10px;"><i class="fa fa-close"></i> </a>';
				}else {
					$row['actions'] = '-';
				}

                $data[] = $row;
            }

        }
        echo json_encode(array('data'=>$data));
    }

	public function delete_approval_laporan()
	{
		$output['output'] = false;
		$id = $this->input->post('id');
		if(!empty($id)){
			$this->db->delete('ttd_laporan',array('id'=>$id));
			{
				$output['output'] = true;
			}
		}
		echo json_encode($output);
	}

}
?>