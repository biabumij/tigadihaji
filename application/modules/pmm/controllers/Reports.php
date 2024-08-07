<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Reports extends CI_Controller {

	public function __construct()
	{
        parent::__construct();
        // Your own constructor code
        $this->load->model(array('admin/m_admin','crud_global','m_themes','pages/m_pages','menu/m_menu','admin_access/m_admin_access','DB_model','member_back/m_member_back','m_member','pmm_model','admin/Templates'));
        $this->load->model('pmm_reports');
        $this->load->library('enkrip');
		$this->load->library('filter');
		$this->load->library('waktu');
		$this->load->library('session');
		$this->m_admin->check_login();
	}

	public function revenues()
	{

		
		$arr_date = $this->input->post('filter_date');
		if(empty($filter_date)){
			$filter_date = '-';
		}else {
			$filter_date = $arr_date;
		}
		$alphas = range('A', 'Z');
		$data['alphas'] = $alphas;
		$data['clients'] = $this->db->get_where('pmm_client',array('status'=>'PUBLISH'))->result_array();
		$data['arr_date'] = $arr_date;
		$this->load->view('pmm/ajax/reports/revenues',$data);
	}

	public function receipt_materials()
	{
		
		$arr_date = $this->input->post('filter_date');
		if(empty($arr_date)){
    		$month = date('Y-m');
    		$start_date = date('Y-m',strtotime('- 1month '.$month)).'-27';
    		$end_date = $month.'-26';
    	}else {
    		$dt = explode(' - ', $arr_date);
    		$start_date = date('Y-m-d',strtotime($dt[0]));
    		$end_date = date('Y-m-d',strtotime($dt[1]));
    	}

		$data['filter_date'] = date('d F Y',strtotime($start_date)).' - '.date('d F Y',strtotime($end_date));
		$data['arr'] =  $this->pmm_reports->ReceiptMaterialTagDetails($arr_date);
		$this->load->view('pmm/ajax/reports/receipt_materials',$data);

	}

	public function receipt_material_detail()
	{
		$id = $this->input->post('id');	
		$arr_date = $this->input->post('filter_date');
		$type = $this->input->post('type');
		$data['type'] = $type;
		$data['arr'] =  $this->pmm_reports->ReceiptMaterialTagDetails($id,$arr_date);
		$data['name'] = $this->input->post('name'); 
		$this->load->view('pmm/ajax/reports/receipt_materials_detail',$data);
	}


	public function material_usage()
	{

		$product_id = $this->input->post('product_id');
		$arr_date = $this->input->post('filter_date');

		if(empty($arr_date)){
    		$month = date('Y-m');
    		$start_date = date('Y-m',strtotime('- 1month '.$month)).'-27';
    		$end_date = $month.'-26';

    		$arr_date_2 = $start_date.' - '.$end_date;

    		$total_revenue_now = $this->pmm_model->getRevenueAll($arr_date_2);
    		$total_revenue_before = $this->pmm_model->getRevenueAll($arr_date_2,true);
    	}else {



    		$dt = explode(' - ', $arr_date);
    		$start_date = date('Y-m-d',strtotime($dt[0]));
    		$end_date = date('Y-m-d',strtotime($dt[1]));

    		$total_revenue_now = $this->pmm_model->getRevenueAll($arr_date);
    		$total_revenue_before = $this->pmm_model->getRevenueAll($arr_date,true);
    	} 

		$data['filter_date'] = date('d F Y',strtotime($start_date)).' - '.date('d F Y',strtotime($end_date));

		if(!empty($product_id)){
			$data['product'] = $this->crud_global->GetField('pmm_product',array('id'=>$product_id),'product');
			$data['total_production'] = $this->pmm_reports->TotalProductions($product_id,$arr_date);
			$data['arr_compo'] = $this->pmm_reports->MaterialUsageCompoProduct($arr_date,$product_id);
			$this->load->view('pmm/ajax/reports/material_usage_product',$data);
		}else {

			$data['arr'] =  $this->pmm_reports->MaterialUsageReal($arr_date);
			$data['arr_compo'] = $this->pmm_reports->MaterialUsageCompo($arr_date);
			$data['total_revenue_now'] = $total_revenue_now;
			$data['total_revenue_before'] =  $total_revenue_before;
			$this->load->view('pmm/ajax/reports/material_usage',$data);	
		}
		
	}

	public function material_usage_detail()
	{
		$id = $this->input->post('id');	
		$arr_date = $this->input->post('filter_date');
		if(empty($arr_date)){
    		$month = date('Y-m');
    		$start_date = date('Y-m',strtotime('- 1month '.$month)).'-27';
    		$end_date = $month.'-26';
    	}else {
    		$dt = explode(' - ', $arr_date);
    		$start_date = date('Y-m-d',strtotime($dt[0]));
    		$end_date = date('Y-m-d',strtotime($dt[1]));
    	} 

		$type = $this->input->post('type');
		$product_id = $this->input->post('product_id');
		$data['type'] = $type;
		if($type == 'compo' || $type == 'compo_cost' || $type == 'compo_now'){
			$data['arr'] =  $this->pmm_reports->MaterialUsageCompoDetails($id,$arr_date,$product_id);
		}else {
			$data['arr'] =  $this->pmm_reports->MaterialUsageDetails($id,$arr_date);	
		}

		$data['filter_date'] = date('d F Y',strtotime($start_date)).' - '.date('d F Y',strtotime($end_date));
		$data['product_id'] = $product_id;
		$data['name'] = $this->input->post('name'); 
		$this->load->view('pmm/ajax/reports/material_usage_detail',$data);
	}

	public function material_remaining()
	{
		$arr_date = $this->input->post('filter_date');
		if(empty($arr_date)){
    		$month = date('Y-m');
    		$start_date = date('Y-m',strtotime('- 1month '.$month)).'-27';
    		$end_date = $month.'-26';
    	}else {
    		$dt = explode(' - ', $arr_date);
    		$start_date = date('Y-m-d',strtotime($dt[0]));
    		$end_date = date('Y-m-d',strtotime($dt[1]));
    	} 

    	$date = array($start_date,$end_date);
		$data['filter_date'] = date('d F Y',strtotime($start_date)).' - '.date('d F Y',strtotime($end_date));

		$data['arr'] =  $this->pmm_reports->MaterialRemainingReal($date);
		$data['arr_compo'] = $this->pmm_reports->MaterialRemainingCompo($date);
		$this->load->view('pmm/ajax/reports/material_remaining',$data);	
		

	}

	public function material_remaining_detail()
	{
		$id = $this->input->post('id');	
		$arr_date = $this->input->post('filter_date');
		if(empty($arr_date)){
    		$month = date('Y-m');
    		$start_date = date('Y-m',strtotime('- 1month '.$month)).'-27';
    		$end_date = $month.'-26';
    	}else {
    		$dt = explode(' - ', $arr_date);
    		$start_date = date('Y-m-d',strtotime($dt[0]));
    		$end_date = date('Y-m-d',strtotime($dt[1]));
    	} 
    	$date = array($start_date,$end_date);
		$type = $this->input->post('type');
		$data['type'] = $type;
		if($type == 'compo'){
			$data['arr'] =  $this->pmm_reports->MaterialRemainingCompoDetails($id,$date);
		}else {
			$data['arr'] =  $this->pmm_reports->MaterialRemainingDetails($id,$arr_date);	
		}

		$data['filter_date'] = date('d F Y',strtotime($start_date)).' - '.date('d F Y',strtotime($end_date));
		$data['name'] = $this->input->post('name'); 
		$this->load->view('pmm/ajax/reports/material_remaining_detail',$data);
	}

	public function equipments()
	{
		$arr_date = $this->input->post('filter_date');
		$supplier_id = $this->input->post('supplier_id');
		if(empty($arr_date)){
    		$month = date('Y-m');
    		$start_date = date('Y-m',strtotime('- 1month '.$month)).'-27';
    		$end_date = $month.'-26';
    	}else {
    		$dt = explode(' - ', $arr_date);
    		$start_date = date('Y-m-d',strtotime($dt[0]));
    		$end_date = date('Y-m-d',strtotime($dt[1]));
    	}

    	$date = array($start_date,$end_date);
    	$data['filter_date'] = date('d F Y',strtotime($start_date)).' - '.date('d F Y',strtotime($end_date));
		$data['arr'] =  $this->pmm_reports->EquipmentProd($date);
		$data['equipments'] =  $this->pmm_reports->EquipmentReports($date,$supplier_id);
		$data['solar'] =  $this->pmm_reports->EquipmentUsageReal($date,true);
		$this->load->view('pmm/ajax/reports/equipments',$data);

	}

	public function equipments_detail()
	{
		$id = $this->input->post('id');	
		$arr_date = $this->input->post('filter_date');
		if(empty($arr_date)){
    		$month = date('Y-m');
    		$start_date = date('Y-m',strtotime('- 1month '.$month)).'-27';
    		$end_date = $month.'-26';
    	}else {
    		$dt = explode(' - ', $arr_date);
    		$start_date = date('Y-m-d',strtotime($dt[0]));
    		$end_date = date('Y-m-d',strtotime($dt[1]));
    	}
    	$date = array($start_date,$end_date);
		$supplier_id = $this->input->post('supplier_id');;
		$data['equipments'] = $this->pmm_reports->EquipmentReportsDetails($id,$date,$supplier_id);
		$data['name'] = $this->input->post('name');
		$data['filter_date'] = date('d F Y',strtotime($start_date)).' - '.date('d F Y',strtotime($end_date));
		$this->load->view('pmm/ajax/reports/equipments_detail',$data);
	}


	public function equipments_data_print()
	{
		$this->load->library('pdf');
	

		$pdf = new Pdf('P', 'mm', 'A4', true, 'UTF-8', false);
        $pdf->setPrintHeader(true);
        $pdf->SetFont('helvetica','',7); 
        $tagvs = array('div' => array(0 => array('h' => 0, 'n' => 0), 1 => array('h' => 0, 'n'=> 0)));
		$pdf->setHtmlVSpace($tagvs);
		$pdf->AddPage('P');

		$arr_data = array();
		$date = $this->input->get('filter_date');
		$supplier_id = $this->input->get('supplier_id');
		$tool_id = $this->input->get('tool_id');
		if(!empty($date)){
			$arr_date = explode(' - ',$date);
			$start_date = date('Y-m-d',strtotime($arr_date[0]));
			$end_date = date('Y-m-d',strtotime($arr_date[1]));

			$filter_date = date('d F Y',strtotime($start_date)).' - '.date('d F Y',strtotime($end_date));
			$data['filter_date'] = $filter_date;
			$date = explode(' - ',$start_date.' - '.$end_date);
			$arr_data = $this->pmm_reports->EquipmentsData($date,$supplier_id,$tool_id);

			$data['data'] = $arr_data;
			$data['solar'] =  $this->pmm_reports->EquipmentUsageReal($date);
	        $html = $this->load->view('pmm/equipments_data_print',$data,TRUE);

	        
	        $pdf->SetTitle('Data Alat');
	        $pdf->nsi_html($html);
	        $pdf->Output('Data-Alat.pdf', 'I');

		}else {
			echo 'Please Filter Date First';
		}
		
	}

	public function revenues_print()
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
			$filter_date = date('d F Y',strtotime($arr_filter_date[0])).' - '.date('d F Y',strtotime($arr_filter_date[1]));
		}
		$data['filter_date'] = $filter_date;
		$alphas = range('A', 'Z');
		$data['alphas'] = $alphas;
		$data['arr_date'] = $arr_date;
		$data['clients'] = $this->db->get_where('pmm_client',array('status'=>'PUBLISH'))->result_array();
        $html = $this->load->view('pmm/revenues_print',$data,TRUE);

        
        $pdf->SetTitle('LAPORAN PENDAPATAN USAHA');
        $pdf->nsi_html($html);
        $pdf->Output('LAPORAN-PENDAPATAN-USAHA.pdf', 'I');
	
	}
	
	public function monitoring_receipt_materials_print()
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
    		$month = date('Y-m');
    		$start_date = date('Y-m',strtotime('- 1month '.$month)).'-27';
    		$end_date = $month.'-26';
    	}else {
    		$dt = explode(' - ', $arr_date);
    		$start_date = date('Y-m-d',strtotime($dt[0]));
    		$end_date = date('Y-m-d',strtotime($dt[1]));
    	}

		$data['filter_date'] = date('d F Y',strtotime($start_date)).' - '.date('d F Y',strtotime($end_date));
		$data['arr'] =  $this->pmm_reports->ReceiptMaterialTagDetails($arr_date);
        $html = $this->load->view('pmm/monitoring_receipt_materials_print',$data,TRUE);

        
        $pdf->SetTitle('LAPORAN PENERIMAAN BAHAN');
        $pdf->nsi_html($html);
        $pdf->Output('LAPORAN-PENERIMAAN-BAHAN.pdf', 'I');
	
	}

	public function material_usage_print()
	{
		$this->load->library('pdf');
	

		$pdf = new Pdf('P', 'mm', 'A4', true, 'UTF-8', false);
        $pdf->setPrintHeader(true);
        $pdf->SetFont('helvetica','',7); 
        $tagvs = array('div' => array(0 => array('h' => 0, 'n' => 0), 1 => array('h' => 0, 'n'=> 0)));
		$pdf->setHtmlVSpace($tagvs);
		        $pdf->AddPage('P');

		$product_id = $this->input->get('product_id');
		$arr_date = $this->input->get('filter_date');
		if(empty($arr_date)){
    		$month = date('Y-m');
    		$start_date = date('Y-m',strtotime('- 1month '.$month)).'-27';
    		$end_date = $month.'-26';

    		$arr_date_2 = $start_date.' - '.$end_date;

    		$total_revenue_now = $this->pmm_model->getRevenueAll($arr_date_2);
    		$total_revenue_before = $this->pmm_model->getRevenueAll($arr_date_2,true);

    	}else {
    		$dt = explode(' - ', $arr_date);
    		$start_date = date('Y-m-d',strtotime($dt[0]));
    		$end_date = date('Y-m-d',strtotime($dt[1]));

    		$total_revenue_now = $this->pmm_model->getRevenueAll($arr_date);
    		$total_revenue_before = $this->pmm_model->getRevenueAll($arr_date,true);
    	}
    	
		$data['filter_date'] = date('d F Y',strtotime($start_date)).' - '.date('d F Y',strtotime($end_date));
		if(empty($product_id)){
			$data['arr'] =  $this->pmm_reports->MaterialUsageReal($arr_date);
			$data['arr_compo'] = $this->pmm_reports->MaterialUsageCompo($arr_date);
			$data['total_revenue_now'] = $total_revenue_now;
			$data['total_revenue_before'] =  $total_revenue_before;
	        $html = $this->load->view('pmm/material_usage_print',$data,TRUE);
		}else {
			$data['product'] = $this->crud_global->GetField('pmm_product',array('id'=>$product_id),'product');
			$data['total_production'] = $this->pmm_reports->TotalProductions($product_id,$arr_date);
			$data['arr_compo'] = $this->pmm_reports->MaterialUsageCompoProduct($arr_date,$product_id);

			

	        $html = $this->load->view('pmm/material_usage_product_print',$data,TRUE);
		}
		 
        $pdf->SetTitle('LAPORAN PEMAKAIAN MATERIAL');
        $pdf->nsi_html($html);
        $pdf->Output('LAPORAN-PEMAKAIAN-MATERIAL.pdf', 'I');
	
	}

	public function material_remaining_print()
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
    		$month = date('Y-m');
    		$start_date = date('Y-m',strtotime('- 1month '.$month)).'-27';
    		$end_date = $month.'-26';
    	}else {
    		$dt = explode(' - ', $arr_date);
    		$start_date = date('Y-m-d',strtotime($dt[0]));
    		$end_date = date('Y-m-d',strtotime($dt[1]));
    	} 

    	$date = array($start_date,$end_date);
		$data['filter_date'] = date('d F Y',strtotime($start_date)).' - '.date('d F Y',strtotime($end_date));

		$data['arr'] =  $this->pmm_reports->MaterialRemainingReal($date);
		$data['arr_compo'] = $this->pmm_reports->MaterialRemainingCompo($date);

        $html = $this->load->view('pmm/material_remaining_print',$data,TRUE);

        
        $pdf->SetTitle('Materials Remaining');
        $pdf->nsi_html($html);
        $pdf->Output('Materials-Remaining.pdf', 'I');
	
	}


	public function monitoring_equipments_print()
	{
		$this->load->library('pdf');
	

		$pdf = new Pdf('P', 'mm', 'A4', true, 'UTF-8', false);
        $pdf->setPrintHeader(true);
        $pdf->SetFont('helvetica','',7); 
        $tagvs = array('div' => array(0 => array('h' => 0, 'n' => 0), 1 => array('h' => 0, 'n'=> 0)));
		$pdf->setHtmlVSpace($tagvs);
		        $pdf->AddPage('P');

		$arr_date = $this->input->get('filter_date');
		$supplier_id = $this->input->get('supplier_id');
		if(empty($arr_date)){
    		$month = date('Y-m');
    		$start_date = date('Y-m',strtotime('- 1month '.$month)).'-27';
    		$end_date = $month.'-26';
    	}else {
    		$dt = explode(' - ', $arr_date);
    		$start_date = date('Y-m-d',strtotime($dt[0]));
    		$end_date = date('Y-m-d',strtotime($dt[1]));
    	}

    	$date = array($start_date,$end_date);
    	$data['filter_date'] = date('d F Y',strtotime($start_date)).' - '.date('d F Y',strtotime($end_date));
		$data['arr'] =  $this->pmm_reports->EquipmentProd($date);
		$data['equipments'] =  $this->pmm_reports->EquipmentReports($date,$supplier_id);
		$data['supplier'] = $this->crud_global->GetField('pmm_supplier',array('id'=>$supplier_id),'name');

        $html = $this->load->view('pmm/monitoring_equipments_print',$data,TRUE);

        
        $pdf->SetTitle('LAPORAN PEMAKAIAN ALAT');
        $pdf->nsi_html($html);
        $pdf->Output('LAPORAN-PEMAKAIAN-ALAT.pdf', 'I');
	
	}

	public function general_cost_print()
	{
		$this->load->library('pdf');
	

		$pdf = new Pdf('P', 'mm', 'A4', true, 'UTF-8', false);
        $pdf->setPrintHeader(false);
        $pdf->SetTopMargin(0);
        $pdf->SetFont('helvetica','',7); 
        $tagvs = array('div' => array(0 => array('h' => 0, 'n' => 0), 1 => array('h' => 0, 'n'=> 0)));
		$pdf->setHtmlVSpace($tagvs);
		        $pdf->AddPage('P');

		$arr_date = $this->input->get('filter_date');
		$filter_type = $this->input->get('filter_type');
		if(empty($arr_date)){
    		$data['filter_date'] = '-';
    	}else {
    		$dt = explode(' - ', $arr_date);
    		$start_date = date('Y-m-d',strtotime($dt[0]));
    		$end_date = date('Y-m-d',strtotime($dt[1]));
    		$data['filter_date'] = date('d F Y',strtotime($start_date)).' - '.date('d F Y',strtotime($end_date));
    	} 

		


		if(!empty($arr_date)){
			$dt = explode(' - ', $arr_date);
    		$start_date = date('Y-m-d',strtotime($dt[0]));
    		$end_date = date('Y-m-d',strtotime($dt[1]));
    		$this->db->where('date >=',$start_date);
    		$this->db->where('date <=',$end_date);	
		}
		if(!empty($filter_type)){
			$this->db->where('type',$filter_type);
		}
		$this->db->order_by('date','desc');
		$this->db->where('status !=','DELETED');
		$arr = $this->db->get_where('pmm_general_cost');
		$data['arr'] =  $arr->result_array();

        $html = $this->load->view('pmm/general_cost_print',$data,TRUE);

        
        $pdf->SetTitle('General Cost');
        $pdf->nsi_html($html);
        $pdf->Output('General-Cost.pdf', 'I');
	
	}


	public function purchase_order_print()
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
			$filter_date = date('d F Y',strtotime($arr_filter_date[0])).' - '.date('d F Y',strtotime($arr_filter_date[1]));
		}
		$data['filter_date'] = $filter_date;
		$data['w_date'] = $arr_date;
		$data['status'] = $this->input->post('status');
		$data['supplier_id'] = $this->input->post('supplier_id');
		$this->db->select('supplier_id');
		$this->db->where('status !=','DELETED');
		if(!empty($data['status'])){
			$this->db->where('supplier_id',$data['status']);
		}
		$this->db->group_by('supplier_id');
		$this->db->order_by('created_on','desc');
		$query = $this->db->get('pmm_purchase_order');

		$data['data'] = $query->result_array();
        $html = $this->load->view('pmm/purchase_order_print',$data,TRUE);

        
        $pdf->SetTitle('Purchase Order');
        $pdf->nsi_html($html);
        $pdf->Output('Purchase-Order.pdf', 'I');
	
	}


	public function product_print()
	{
		$this->load->library('pdf');
	

		$pdf = new Pdf('P', 'mm', 'A4', true, 'UTF-8', false);
        $pdf->setPrintHeader(true);
        $pdf->SetFont('helvetica','',7); 
        $tagvs = array('div' => array(0 => array('h' => 0, 'n' => 0), 1 => array('h' => 0, 'n'=> 0)));
		$pdf->setHtmlVSpace($tagvs);
		        $pdf->AddPage('P');

		$arr_data = array();
		$tag_id = $this->input->get('product_id');

		if(!empty($tag_id)){
			$this->db->where('tag_id',$tag_id);	
		}
		$this->db->where('status !=','DELETED');
		$this->db->order_by('product','asc');
		$query = $this->db->get('pmm_product');
		if($query->num_rows() > 0){
			foreach ($query->result_array() as $key => $row) {
				$name = "'".$row['product']."'";
				$row['no'] = $key+1;
				$row['created_on'] = date('d F Y',strtotime($row['created_on']));
				$contract_price = $this->pmm_model->GetContractPrice($row['contract_price']);
				$row['contract_price'] = number_format($contract_price,2,',','.');
				$row['riel_price'] = number_format($this->pmm_model->GetRielPrice($row['id']),2,',','.');
				$row['composition'] = $this->crud_global->GetField('pmm_composition',array('id'=>$row['composition_id']),'composition_name');
				$row['tag_name'] = $this->crud_global->GetField('pmm_tags',array('id'=>$row['tag_id']),'tag_name');
				$arr_data[] = $row;
			}

		}

		$data['data'] = $arr_data;
        $html = $this->load->view('pmm/product_print',$data,TRUE);

        
        $pdf->SetTitle('Product');
        $pdf->nsi_html($html);
        $pdf->Output('Product.pdf', 'I');
	
	}

	public function product_hpp_print()
	{
		$id = $this->input->get('id');
		$name = $this->input->get('name');
		if(!empty($id)){
			$this->load->library('pdf');
		

			$pdf = new Pdf('P', 'mm', 'A4', true, 'UTF-8', false);
	        $pdf->setPrintHeader(true);
	        $pdf->SetFont('helvetica','',7); 
	        $tagvs = array('div' => array(0 => array('h' => 0, 'n' => 0), 1 => array('h' => 0, 'n'=> 0)));
			$pdf->setHtmlVSpace($tagvs);
			        $pdf->AddPage('P');

			$arr_data = array();

			$output = $this->pmm_model->GetRielPriceDetail($id);

			$data['data'] = $output;
			$data['name'] = $name;
	        $html = $this->load->view('pmm/product_hpp_print',$data,TRUE);

	        
	        $pdf->SetTitle('Product-HPP');
	        $pdf->nsi_html($html);
	        $pdf->Output('Product-HPP-'.$name.'.pdf', 'I');
		}else {
			echo "Product Not Found";
		}
		
	
	}

	public function materials_print()
	{
		$this->load->library('pdf');
	

		$pdf = new Pdf('P', 'mm', 'A4', true, 'UTF-8', false);
        $pdf->setPrintHeader(true);
        $pdf->SetFont('helvetica','',7); 
        $tagvs = array('div' => array(0 => array('h' => 0, 'n' => 0), 1 => array('h' => 0, 'n'=> 0)));
		$pdf->setHtmlVSpace($tagvs);
		
		$pdf->AddPage('P');

		$arr_data = array();
		$tag_id = $this->input->get('tag_id');

		$this->db->where('status !=','DELETED');
		if(!empty($tag_id)){
			$this->db->where('tag_id',$tag_id);
		}
		$this->db->order_by('material_name','asc');
		$query = $this->db->get('pmm_materials');
		if($query->num_rows() > 0){
			foreach ($query->result_array() as $key => $row) {
				$row['no'] = $key+1;
				$row['price'] = number_format($row['price'],2,',','.');
				$row['cost'] = number_format($row['cost'],2,',','.');
				$row['measure'] = $this->crud_global->GetField('pmm_measures',array('id'=>$row['measure']),'measure_name');
 				$row['created_on'] = date('d F Y',strtotime($row['created_on']));
 				$row['tag_name'] = $this->crud_global->GetField('pmm_tags',array('id'=>$row['tag_id']),'tag_name');
				$arr_data[] = $row;
			}

		}

		$data['data'] = $arr_data;
        $html = $this->load->view('pmm/materials_print',$data,TRUE);

        
        $pdf->SetTitle('Materials');
        $pdf->nsi_html($html);
        $pdf->Output('Materials.pdf', 'I');
	
	}

	public function tools_print()
	{
		$this->load->library('pdf');
	

		$pdf = new Pdf('P', 'mm', 'A4', true, 'UTF-8', false);
        $pdf->setPrintHeader(true);
        $pdf->SetFont('helvetica','',7); 
        $tagvs = array('div' => array(0 => array('h' => 0, 'n' => 0), 1 => array('h' => 0, 'n'=> 0)));
		$pdf->setHtmlVSpace($tagvs);
		        $pdf->AddPage('P');

		$arr_data = array();
		$this->db->where('status !=','DELETED');
		$this->db->order_by('tool','asc');
		$query = $this->db->get('pmm_tools');

		if($query->num_rows() > 0){
			foreach ($query->result_array() as $key => $row) {
				$row['no'] = $key+1;
				$name = "'".$row['tool']."'";
				$total_cost = $this->db->select('SUM(cost) as total')->get_where('pmm_tool_detail',array('status'=>'PUBLISH','tool_id'=>$row['id']))->row_array();
				$row['total_cost'] = number_format($total_cost['total'],2,',','.');
				$row['measure'] = $this->crud_global->GetField('pmm_measures',array('id'=>$row['measure_id']),'measure_name');
				$row['tag'] = $this->crud_global->GetField('pmm_tags',array('id'=>$row['tag_id']),'tag_name');
 				$row['created_on'] = date('d F Y',strtotime($row['created_on']));
				$row['actions'] = '<a href="javascript:void(0);" onclick="FormDetail('.$row['id'].','.$name.')" class="btn btn-info"><i class="fa fa-search"></i> Detail</a> <a href="javascript:void(0);" onclick="OpenForm('.$row['id'].')" class="btn btn-primary"><i class="fa fa-edit"></i> </a> <a href="javascript:void(0);" onclick="DeleteData('.$row['id'].')" class="btn btn-danger"><i class="fa fa-close"></i> </a>';
				$arr_data[] = $row;
			}

		}
		$data['data'] = $arr_data;
        $html = $this->load->view('pmm/tools_print',$data,TRUE);

        
        $pdf->SetTitle('Tools');
        $pdf->nsi_html($html);
        $pdf->Output('Tools.pdf', 'I');
	
	}

	public function measures_print()
	{
		$this->load->library('pdf');
	

		$pdf = new Pdf('P', 'mm', 'A4', true, 'UTF-8', false);
        $pdf->setPrintHeader(true);
        $pdf->SetFont('helvetica','',7); 
        $tagvs = array('div' => array(0 => array('h' => 0, 'n' => 0), 1 => array('h' => 0, 'n'=> 0)));
		$pdf->setHtmlVSpace($tagvs);
		        $pdf->AddPage('P');

		$arr_data = array();
		$this->db->where('status !=','DELETED');
		$query = $this->db->get('pmm_measures');
		$data['data'] = $query->result_array();
        $html = $this->load->view('pmm/measures_print',$data,TRUE);

        
        $pdf->SetTitle('Satuan');
        $pdf->nsi_html($html);
        $pdf->Output('satuan.pdf', 'I');
	
	}

	public function composition_print()
	{
		$this->load->library('pdf');
	

		$pdf = new Pdf('P', 'mm', 'A4', true, 'UTF-8', false);
        $pdf->setPrintHeader(true);
        $pdf->SetFont('helvetica','',7); 
        $tagvs = array('div' => array(0 => array('h' => 0, 'n' => 0), 1 => array('h' => 0, 'n'=> 0)));
		$pdf->setHtmlVSpace($tagvs);
		        $pdf->AddPage('P');

		$tag_id = $this->input->get('filter_product');
		$arr_tag = array();
		if(!empty($tag_id)){
			$query_tag = $this->db->select('id')->get_where('pmm_product',array('status'=>'PUBLISH','tag_id'=>$tag_id))->result_array();
			foreach ($query_tag as $pid) {
				$arr_tag[] = $pid['id'];
			}
		}
		$this->db->select('pc.*, pp.product');
		$this->db->where('pc.status !=','DELETED');
		if(!empty($tag_id)){
			$this->db->where_in('product_id',$arr_tag);
		}
		$this->db->join('pmm_product pp','pc.product_id = pp.id','left');
		$this->db->order_by('pc.created_on','desc');
		$query = $this->db->get('pmm_composition pc');
		$data['data'] = $query->result_array();
        $html = $this->load->view('pmm/composition_print',$data,TRUE);

        
        $pdf->SetTitle('Composition');
        $pdf->nsi_html($html);
        $pdf->Output('Composition.pdf', 'I');
	
	}

	public function supplier_print()
	{
		$this->load->library('pdf');
	

		$pdf = new Pdf('P', 'mm', 'A4', true, 'UTF-8', false);
        $pdf->setPrintHeader(true);
        $pdf->SetFont('helvetica','',7); 
        $tagvs = array('div' => array(0 => array('h' => 0, 'n' => 0), 1 => array('h' => 0, 'n'=> 0)));
		$pdf->setHtmlVSpace($tagvs);
		$pdf->AddPage('P');

		$arr_data = array();
		$this->db->where('status !=','DELETED');
		$this->db->order_by('name','asc');
		$query = $this->db->get('pmm_supplier');
		$data['data'] = $query->result_array();
        $html = $this->load->view('pmm/supplier_print',$data,TRUE);

        
        $pdf->SetTitle('Supplier');
        $pdf->nsi_html($html);
        $pdf->Output('Supplier.pdf', 'I');
	
	}

	public function client_print()
	{
		$arr_data = array();
		$this->db->where('status !=','DELETED');
		$this->db->order_by('client_name','asc');
		$query = $this->db->get('pmm_client');
		$data['data'] = $query->result_array();	
	
		$this->load->library('pdf');
		$this->pdf->setPaper('A4', 'potrait');
		$this->pdf->filename = "laporan-client.pdf";
		$this->pdf->load_view('pmm/client_print', $data);
	
	}
	
	public function slump_print()
	{
		$this->load->library('pdf');
	

		$pdf = new Pdf('P', 'mm', 'A4', true, 'UTF-8', false);
        $pdf->setPrintHeader(true);
        $pdf->SetFont('helvetica','',7); 
        $tagvs = array('div' => array(0 => array('h' => 0, 'n' => 0), 1 => array('h' => 0, 'n'=> 0)));
		$pdf->setHtmlVSpace($tagvs);
		        $pdf->AddPage('P');

		$arr_data = array();
		$this->db->where('status !=','DELETED');
		$query = $this->db->get('pmm_slump');
		$data['data'] = $query->result_array();
        $html = $this->load->view('pmm/slump_print',$data,TRUE);

        
        $pdf->SetTitle('Slump');
        $pdf->nsi_html($html);
        $pdf->Output('Slump.pdf', 'I');
	
	}

	public function tags_print()
	{
		$this->load->library('pdf');
	

		$pdf = new Pdf('P', 'mm', 'A4', true, 'UTF-8', false);
        $pdf->setPrintHeader(true);
        $pdf->SetFont('helvetica','',7); 
        $tagvs = array('div' => array(0 => array('h' => 0, 'n' => 0), 1 => array('h' => 0, 'n'=> 0)));
		$pdf->setHtmlVSpace($tagvs);
		        $pdf->AddPage('P');

		$arr_data = array();
		$type = $this->input->get('type');
		$this->db->where('status !=','DELETED');
		if(!empty($type)){
			$this->db->where('tag_type',$type);
		}
		$this->db->order_by('tag_name','asc');
		$query = $this->db->get('pmm_tags');

		if($query->num_rows() > 0){
			foreach ($query->result_array() as $key => $row) {
				$row['no'] = $key+1;
				$price = 0;
				if($row['tag_type'] == 'MATERIAL'){
					$get_price = $this->db->select('AVG(cost) as cost')->get_where('pmm_materials',array('status'=>'PUBLISH','tag_id'=>$row['id']))->row_array();
					if(!empty($get_price)){
						$price = $get_price['cost'];
					}
				}
				$row['price'] = number_format($price,2,',','.');
				$arr_data[] = $row;
			}

		}
		$data['data'] = $arr_data;
        $html = $this->load->view('pmm/tags_print',$data,TRUE);

        
        $pdf->SetTitle('Tags');
        $pdf->nsi_html($html);
        $pdf->Output('Tags.pdf', 'I');
	
	}

	public function production_planning_print()
	{
		$this->load->library('pdf');
	

		$pdf = new Pdf('P', 'mm', 'A4', true, 'UTF-8', false);
        $pdf->setPrintHeader(true);
        $pdf->SetFont('helvetica','',7); 
        $tagvs = array('div' => array(0 => array('h' => 0, 'n' => 0), 1 => array('h' => 0, 'n'=> 0)));
		$pdf->setHtmlVSpace($tagvs);
		$pdf->AddPage('P');

		$arr_data = array();
		$this->db->where('status !=','DELETED');
		$this->db->order_by('created_on','desc');
		$query = $this->db->get('pmm_schedule');
		if($query->num_rows() > 0){
			foreach ($query->result_array() as $key => $row) {
				$row['no'] = $key+1;
				$arr_date = explode(' - ', $row['schedule_date']);
				$row['schedule_name'] = $row['schedule_name'];
				$row['client_name'] = $this->crud_global->GetField('pmm_client',array('id'=>$row['client_id']),'client_name');
				$row['schedule_date'] = date('d F Y',strtotime($arr_date[0])).' - '.date('d F Y',strtotime($arr_date[1]));
				$row['created_on'] = date('d F Y',strtotime($row['created_on']));
				$row['week_1'] = $this->pmm_model->TotalSPOWeek($row['id'],1);
				$row['week_2'] = $this->pmm_model->TotalSPOWeek($row['id'],2);
				$row['week_3'] = $this->pmm_model->TotalSPOWeek($row['id'],3);
				$row['week_4'] = $this->pmm_model->TotalSPOWeek($row['id'],4);
				$row['status'] = $this->pmm_model->GetStatus($row['status']);
				
				$arr_data[] = $row;
			}

		}
		$data['data'] = $arr_data;
        $html = $this->load->view('pmm/production_planning_print',$data,TRUE);

        
        $pdf->SetTitle('cetak_poduction_planning');
        $pdf->nsi_html($html);
        $pdf->Output('production_planning.pdf', 'I');
	
	}
	
	public function receipt_matuse_print()
	{
		$this->load->library('pdf');
	

		$pdf = new Pdf('P', 'mm', 'A4', true, 'UTF-8', false);
        $pdf->setPrintHeader(true);
        $pdf->SetFont('helvetica','',7); 
        $tagvs = array('div' => array(0 => array('h' => 0, 'n' => 0), 1 => array('h' => 0, 'n'=> 0)));
		$pdf->setHtmlVSpace($tagvs);
		$pdf->AddPage('P');

		$arr_data = array();
		$supplier_id = $this->input->get('supplier_id');
		$purchase_order_no = $this->input->get('purchase_order_no');
		$filter_material = $this->input->get('filter_material');
		$start_date = false;
		$end_date = false;
		$total = 0;
		$total_convert = 0;
		$date = $this->input->get('filter_date');
		if(!empty($date)){
			$arr_date = explode(' - ',$date);
			$start_date = date('Y-m-d',strtotime($arr_date[0]));
			$end_date = date('Y-m-d',strtotime($arr_date[1]));
			$filter_date = date('d F Y',strtotime($arr_date[0])).' - '.date('d F Y',strtotime($arr_date[1]));

			
			$data['filter_date'] = $filter_date;

			$arr_filter_mats = array();

			$no = 1;
			$this->db->select('ppo.supplier_id,prm.measure,ps.name,SUM(prm.volume) as total, SUM((prm.cost / prm.convert_value) * prm.display_volume) as total_price, prm.convert_value, SUM(prm.volume * prm.convert_value) as total_convert');
			if(!empty($start_date) && !empty($end_date)){
	            $this->db->where('prm.date_receipt >=',$start_date);
	            $this->db->where('prm.date_receipt <=',$end_date);
	        }
	        if(!empty($supplier_id)){
	            $this->db->where('ppo.supplier_id',$supplier_id);
	        }
	        if(!empty($filter_material)){
	            $this->db->where_in('prm.material_id',$filter_material);
	        }
	        if(!empty($purchase_order_no)){
	            $this->db->where('prm.purchase_order_id',$purchase_order_no);
	        }
			$this->db->where('ps.status','PUBLISH');
			$this->db->join('pmm_supplier ps','ppo.supplier_id = ps.id','left');
			$this->db->join('pmm_receipt_material prm','ppo.id = prm.purchase_order_id');
			$this->db->group_by('ppo.supplier_id');
			$this->db->order_by('ps.name','asc');
			$query = $this->db->get('pmm_purchase_order ppo');
			
			if($query->num_rows() > 0){

				foreach ($query->result_array() as $key => $sups) {

					$mats = array();
					$materials = $this->pmm_model->GetReceiptMatUse($sups['supplier_id'],$purchase_order_no,$start_date,$end_date,$arr_filter_mats);
					if(!empty($materials)){
						foreach ($materials as $key => $row) {
							$arr['no'] = $key + 1;
							$arr['measure'] = $row['measure'];
							$arr['material_name'] = $row['material_name'];
							
							$arr['real'] = number_format($row['total'],2,',','.');
							$arr['convert_value'] = number_format($row['convert_value'],2,',','.');
							$arr['total_convert'] = number_format($row['total_convert'],2,',','.');
							$arr['total_price'] = number_format($row['total_price'],2,',','.');
							$mats[] = $arr;
						}
						$sups['mats'] = $mats;
						$total += $sups['total_price'];
						$total_convert += $sups['total_convert'];
						$sups['no'] =$no;
						$sups['real'] = number_format($sups['total'],2,',','.');
						$sups['convert_value'] = number_format($sups['convert_value'],2,',','.');
						$sups['total_convert'] = number_format($sups['total_convert'],2,',','.');
						$sups['total_price'] = number_format($sups['total_price'],2,',','.');
						$sups['measure'] = '';
						$arr_data[] = $sups;
						$no++;
					}
					
					
				}
			}
			if(!empty($filter_material)){
				$total_convert = number_format($total_convert,0,',','.');
			}else {
				$total_convert = '';
			}

			
			$data['data'] = $arr_data;
			$data['total'] = $total;
			$data['total_convert'] = $total_convert;
	        $html = $this->load->view('pmm/receipt_matuse_report_print',$data,TRUE);

	        
	        $pdf->SetTitle('Penerimaan Bahan');
	        $pdf->nsi_html($html);
	        $pdf->Output('Penerimaan-Bahan.pdf', 'I');
		}else {
			echo 'Please Filter Date First';
		}
	
	}

	public function data_material_usage()
	{
		$supplier_id = $this->input->post('supplier_id');
		$filter_material = $this->input->post('filter_material');
		$start_date = false;
		$end_date = false;
		$total = 0;
		$total_convert = 0;
		$query = array();
		$date = $this->input->post('filter_date');
		if(!empty($date)){
			$arr_date = explode(' - ',$date);
			$start_date = date('Y-m-d',strtotime($arr_date[0]));
			$end_date = date('Y-m-d',strtotime($arr_date[1]));
		}

    	$this->db->where(array(
    		'status'=>'PUBLISH',
    	));
    	if(!empty($filter_material)){
    		$this->db->where('id',$filter_material);
    	}
    	$this->db->order_by('nama_produk','asc');
    	$tags = $this->db->get_where('produk',array('status'=>'PUBLISH','kategori_produk'=>1))->result_array();

    	if(!empty($tags)){
    		?>
	        <table class="table table-center table-bordered table-condensed">
	        	<thead>
	        		<tr >
		        		<th class="text-center">No</th>
		        		<th class="text-center">Bahan</th>
		        		<th class="text-center">Rekanan</th>
		        		<th class="text-center">Satuan</th>
		        		<th class="text-center">Volume</th>
		        		<th class="text-center">Total</th>
		        	</tr>	
	        	</thead>
	        	<tbody>
	        		<?php
	        		$no=1;
	        		$total_total = 0;
	        		foreach ($tags as $tag) {
		    			$now = $this->pmm_reports->SumMaterialUsage($tag['id'],array($start_date,$end_date));

		    			
		    			$measure_name = $this->crud_global->GetField('pmm_measures',array('id'=>$tag['satuan']),'measure_name');
		    			if($now['volume'] > 0){
				        	
				        	?>
				        	<tr class="active" style="font-weight:bold;">
				        		<td class="text-center"><?php echo $no;?></td>
				        		<td colspan="2"><?php echo $tag['nama_produk'];?></td>
				        		<td class="text-center"><?php echo $measure_name;?></td>
				        		<td class="text-right"><?php echo number_format($now['volume'],2,',','.');?></td>
				        		<td class="text-right"><span class="pull-left">Rp. </span><?php echo number_format($now['total'],0,',','.');?></td>
				        	</tr>
				        	<?php
				        	$now_new = $this->pmm_reports->MatUseBySupp($tag['id'],array($start_date,$end_date),$now['volume'],$now['total']);
				        	if(!empty($now_new)){
				        		$no_2 = 1;
				        		foreach ($now_new as $new) {
					        		
					        		?>
					        		<!--<tr>
					        			<td class="text-center"><?= $no.'.'.$no_2;?></td>
					        			<td></td>
					        			<td><?php echo $new['supplier'];?></td>
					        			<td class="text-center"><?php echo $measure_name;?></td>
						        		<td class="text-right"><?php echo number_format($new['volume'],2,',','.');?></td>
						        		<td class="text-right"><span class="pull-left">Rp. </span><?php echo number_format($new['total'],0,',','.');?></td>
					        		</tr>-->
					        		<?php
					        		$no_2 ++;
					        	}
				        	}
				        	
				        	?>
				        	<tr style="height: 20px">
				        		<td colspan="6"></td>
				        	</tr>
				        	<?php

				        	$no++;
				        	$total_total += $now['total'];
					        
		    			}
		    		}
	        		?>
	        		<tr>
	        			<th colspan="5" class="text-right">TOTAL</th>
	        			<th class="text-right"><span class="pull-left">Rp. </span><?php echo number_format($total_total,0,',','.');?></th>
	        		</tr>
	        	</tbody>
	        </table>
	        <?php	
    	}


	}


	public function material_usage_prod_print()
	{
		$this->load->library('pdf');
	

		$pdf = new Pdf('P', 'mm', 'A4', true, 'UTF-8', false);
        $pdf->setPrintHeader(true);
        
        $pdf->SetFont('helvetica','',7); 
        $tagvs = array('div' => array(0 => array('h' => 0, 'n' => 0), 1 => array('h' => 0, 'n'=> 0)));
		$pdf->setHtmlVSpace($tagvs);
		$pdf->AddPage('P');

		$arr_data = array();
		$supplier_id = $this->input->get('supplier_id');
		$purchase_order_no = $this->input->get('purchase_order_no');
		$filter_material = $this->input->get('filter_material');
		$start_date = false;
		$end_date = false;
		$total = 0;
		$total_convert = 0;
		$date = $this->input->get('filter_date');
		if(!empty($date)){
			$arr_date = explode(' - ',$date);
			$start_date = date('Y-m-d',strtotime($arr_date[0]));
			$end_date = date('Y-m-d',strtotime($arr_date[1]));
			$filter_date = date('d F Y',strtotime($arr_date[0])).' - '.date('d F Y',strtotime($arr_date[1]));

			
			$data['filter_date'] = $filter_date;

			$no = 1;
	    	$this->db->where(array(
	    		'status'=>'PUBLISH',
				'kategori_produk'=>1,
	    	));
	    	if(!empty($filter_material)){
	    		$this->db->where('id',$filter_material);
	    	}
	    	$this->db->order_by('nama_produk','asc');
	    	$query = $this->db->get('produk');
			
			if($query->num_rows() > 0){

				foreach ($query->result_array() as $key => $tag) {

					$now = $this->pmm_reports->SumMaterialUsage($tag['id'],array($start_date,$end_date));
	    			$measure_name = $this->crud_global->GetField('pmm_measures',array('id'=>$tag['satuan']),'measure_name');
	    			if($now['volume'] > 0){
	    				$tags['tag_name'] = $tag['nama_produk'];
	    				$tags['no'] = $no;
	    				$tags['volume'] = number_format($now['volume'],2,',','.');
	    				$tags['total'] = number_format($now['total'],2,',','.');
	    				$tags['measure'] = $measure_name;

	    				$now_new = $this->pmm_reports->MatUseBySupp($tag['id'],array($start_date,$end_date),$now['volume'],$now['total']);
			        	if(!empty($now_new)){
			        		$no_2 = 1;
			        		$supps = array();
			        		foreach ($now_new as $new) {

			        			$arr_supps['no'] = $no_2;
			        			$arr_supps['supplier'] = $new['supplier'];
			        			$arr_supps['volume'] = number_format($new['volume'],2,',','.');
			        			$arr_supps['total'] = number_format($new['total'],2,',','.');
			        			$supps[] = $arr_supps;
			        			$no_2 ++;
			        		}

			        		$tags['supps'] = $supps;
			        	}

						$arr_data[] = $tags;	
						$total += $now['total'];
	    			}
					$no++;
					
				}
			}

			
			$data['data'] = $arr_data;
			$data['total'] = $total;
			$data['custom_date'] = $this->input->get('custom_date');
	        $html = $this->load->view('produksi/material_usage_prod_print',$data,TRUE);

	        
	        $pdf->SetTitle('pemakaian-material');
	        $pdf->nsi_html($html);
	        $pdf->Output('pemakaian-material', 'I');
		}else {
			echo 'Please Filter Date First';
		}
	
	}

	public function exec()
	{
		
	}

	//BATAS RUMUS LAMA//
	public function laba_rugi($arr_date)
	{
		$data = array();
		
		$arr_date = $this->input->post('filter_date');
		$arr_filter_date = explode(' - ', $arr_date);
		$date3 = '';
		$date1 = '';
		$date2 = '';

		if(count($arr_filter_date) == 2){
			$date3 	= date('2024-01-01',strtotime($date3));
			$date1 	= date('Y-m-d',strtotime($arr_filter_date[0]));
			$date2 	= date('Y-m-d',strtotime($arr_filter_date[1]));
			$filter_date = date('Y-m-d',strtotime($arr_filter_date[0])).' - '.date('Y-m-d',strtotime($arr_filter_date[1]));
			$filter_date_2 = date('Y-m-d',strtotime($date3)).' - '.date('Y-m-d',strtotime($arr_filter_date[1]));
		}
		
		?>
		
		<table class="table table-bordered" width="100%">
		 <style type="text/css">
			body {
				font-family: helvetica;
			}

			table tr.table-active{
				/*background: linear-gradient(90deg, #fdcd3b 20%, #fdcd3b 40%, #e69500 80%);*/
				background-color: #eeeeee;
				font-size: 12px;
				font-weight: bold;
			}
				
			table tr.table-active2{
				background-color: #e69500;
				font-size: 12px;
				font-weight: bold;
				color: white;
			}
				
			table tr.table-active3{
				font-size: 12px;
			}
				
			table tr.table-active4{
				/*background: linear-gradient(90deg, #eeeeee 5%, #cccccc 50%, #cccccc 100%);*/
				font-weight: bold;
				font-size: 12px;
				color: black;
			}
		 </style>
	        <tr class="table-active2">
	            <th colspan="2">Periode</th>
	            <th class="text-right" colspan="2"><?php echo $filter_date = $filter_date = date('d/m/Y',strtotime($arr_filter_date[0])).' - '.date('d/m/Y',strtotime($arr_filter_date[1]));?></th>
				<th class="text-right" colspan="2">SD. <?php echo $filter_date_2 = date('d/m/Y',strtotime($arr_filter_date[1]));?></th>
	        </tr>
			
			<?php
			//PENJUALAN
			$penjualan = $this->db->select('p.nama, pp.client_id, SUM(pp.display_price) as price, SUM(pp.display_volume) as volume, pp.convert_measure as measure')
			->from('pmm_productions pp')
			->join('penerima p', 'pp.client_id = p.id','left')
			->join('pmm_sales_po ppo', 'pp.salesPo_id = ppo.id','left')
			->where("pp.date_production between '$date1' and '$date2'")
			->where("pp.status = 'PUBLISH'")
			->where("ppo.status in ('OPEN','CLOSED')")
			->group_by("pp.client_id")
			->get()->result_array();
			
			$total_penjualan = 0;
			$total_volume = 0;

			foreach ($penjualan as $x){
				$total_penjualan += $x['price'];
				$total_volume += $x['volume'];
				$satuan = $x['measure'];
			}

			$total_penjualan_all = 0;
			$total_penjualan_all = $total_penjualan;

			//PENJUALAN_2
			$penjualan_2 = $this->db->select('p.nama, pp.client_id, SUM(pp.display_price) as price, SUM(pp.display_volume) as volume, pp.convert_measure as measure')
			->from('pmm_productions pp')
			->join('penerima p', 'pp.client_id = p.id','left')
			->join('pmm_sales_po ppo', 'pp.salesPo_id = ppo.id','left')
			->where("pp.date_production between '$date3' and '$date2'")
			->where("pp.status = 'PUBLISH'")
			->where("ppo.status in ('OPEN','CLOSED')")
			->group_by("pp.client_id")
			->get()->result_array();

			$total_penjualan_2 = 0;
			$total_volume_2 = 0;

			foreach ($penjualan_2 as $x){
				$total_penjualan_2 += $x['price'];
				$total_volume_2 += $x['volume'];
				$satuan_2 = $x['measure'];
			}

			$total_penjualan_all_2 = 0;
			$total_penjualan_all_2 = $total_penjualan_2;

			//BAHAN
			$bahan = $this->pmm_model->getBahan($date1,$date2);
			$total_nilai = $bahan;

			//BAHAN_2
			$bahan_2 = $this->db->select('date, SUM(nilai_semen + nilai_pasir + nilai_1020 + nilai_2030 + nilai_additive) as total')
			->from('kunci_bahan_baku')
			->where("(date between '$date3' and '$date2')")
			->get()->row_array();
			$total_nilai_2 = 0;
			$total_nilai_2= $bahan_2['total'];

			//ALAT
			$alat = $this->pmm_model->getAlat($date1,$date2);
			$alat = $alat;

			//ALAT_2
			$alat_2 = $this->pmm_model->getAkumulasiAlat($date3,$date2);
			$alat_2 = $alat_2;

			//OVERHEAD
			$overhead = $this->pmm_model->getOverheadLabaRugi($date1,$date2);
			$overhead = $overhead;

			//OVERHEAD
			$overhead_2 = $this->pmm_model->getOverheadAkumulasiLabaRugi($date3,$date2);
			$overhead_2 = $overhead_2;

			//DISKONTO
			$diskonto = $this->db->select('sum(pdb.jumlah) as total')
			->from('pmm_biaya pb ')
			->join('pmm_detail_biaya pdb','pb.id = pdb.biaya_id','left')
			->join('pmm_coa c','pdb.akun = c.id','left')
			->where("pdb.akun = 110")
			->where("pb.status = 'PAID'")
			->where("(pb.tanggal_transaksi between '$date1' and '$date2')")
			->get()->row_array();
			$diskonto = $diskonto['total'];

			//DISKONTO_2
			$diskonto_2 = $this->db->select('sum(pdb.jumlah) as total')
			->from('pmm_biaya pb ')
			->join('pmm_detail_biaya pdb','pb.id = pdb.biaya_id','left')
			->join('pmm_coa c','pdb.akun = c.id','left')
			->where("pdb.akun = 110")
			->where("pb.status = 'PAID'")
			->where("(pb.tanggal_transaksi between '$date3' and '$date2')")
			->get()->row_array();
			$diskonto_2 = $diskonto_2['total'];

			$bahan = $total_nilai;
			$alat = $alat;
			$overhead = $overhead;
			$diskonto = $diskonto;
			$total_biaya_operasional = $bahan + $alat + $overhead + $diskonto;
			$laba_kotor = $total_penjualan_all - $total_biaya_operasional;
			$laba_usaha = $laba_kotor;
			$persentase_laba_sebelum_pajak = ($total_penjualan_all!=0)?($laba_usaha / $total_penjualan_all) * 100:0;

			$bahan_2 = $total_nilai_2;
			$alat_2 = $alat_2;
			$overhead_2 = $overhead_2;
			$diskonto_2 = $diskonto_2;
			$total_biaya_operasional_2 = $bahan_2 + $alat_2 + $overhead_2 + $diskonto_2;
			$laba_kotor_2 = $total_penjualan_all_2 - $total_biaya_operasional_2;
			$laba_usaha_2 = $laba_kotor_2;
			$persentase_laba_sebelum_pajak_2 = ($total_penjualan_all_2!=0)?($laba_usaha_2 / $total_penjualan_all_2) * 100:0;
	        ?>

			<tr class="table-active">
	            <th width="100%" class="text-left" colspan="6">Pendapatan Penjualan</th>
	        </tr>
			<tr class="table-active3">
	            <th width="10%" class="text-center">4-40000</th>
				<th width="90%" class="text-left" colspan="5">Pendapatan</th>
	        </tr>
			<?php foreach ($penjualan_2 as $i=>$x): ?>
			<tr class="table-active3">
	            <th width="10%"></th>
				<th width="30%"><?= $penjualan[$i]['nama'] ?></th>
				<th width="12%" class="text-right"><?php echo number_format($penjualan[$i]['volume'],2,',','.');?> (<?= $penjualan[$i]['measure'];?>)</th>
	            <th width="18%" class="text-right">
					<table width="100%" border="0" cellpadding="0">
						<tr>
								<th class="text-left" width="10%">
									<span>Rp.</span>
								</th>
								<th class="text-right" width="90%">
									<span><a target="_blank" href="<?= base_url("laporan/cetak_pengiriman_penjualan?filter_date=".$filter_date = date('d F Y',strtotime($arr_filter_date[0])).' - '.date('d F Y',strtotime($arr_filter_date[1]))) ?>"><?php echo number_format($penjualan[$i]['price'],0,',','.');?></a></span>
								</th>
							</tr>
					</table>
				</th>

				<th width="12%" class="text-right"><?php echo number_format($penjualan_2[$i]['volume'],2,',','.');?> (<?= $penjualan_2[$i]['measure'];?>)</th>
				
				<th width="18%" class="text-right xxx">
					<table width="100%" border="0" cellpadding="0">
						<tr>
								<th class="text-left" width="10%">
									<span>Rp.</span>
								</th>
								<th class="text-right" width="90%">
									<span><a target="_blank" href="<?= base_url("laporan/cetak_pengiriman_penjualan?filter_date=".$filter_date_2 = date('d F Y',strtotime($date3)).' - '.date('d F Y',strtotime($arr_filter_date[1]))) ?>"><?php echo number_format($penjualan_2[$i]['price'],0,',','.');?></a></span>
								</th>
							</tr>
					</table>
				</th>
	        </tr>
			<?php endforeach; ?>
			<tr class="table-active3">
				<th class="text-left" colspan="2">Total Pendapatan</th>
				<th class="text-right"><?php echo number_format($total_volume,2,',','.');?> (<?= $satuan;?>)</th>
	            <th class="text-right">
					<table width="100%" border="0" cellpadding="0">
						<tr>
								<th class="text-left" width="10%">
									<span>Rp.</span>
								</th>
								<th class="text-right" width="90%">
									<span><a target="_blank" href="<?= base_url("laporan/cetak_pengiriman_penjualan?filter_date=".$filter_date = date('d F Y',strtotime($arr_filter_date[0])).' - '.date('d F Y',strtotime($arr_filter_date[1]))) ?>"><?php echo number_format($total_penjualan_all,0,',','.');?></a></span>
								</th>
							</tr>
					</table>
				</th>
				<th class="text-right"><?php echo number_format($total_volume_2,2,',','.');?> (<?= $satuan_2;?>)</th>
				<th class="text-right">
					<table width="100%" border="0" cellpadding="0">
						<tr>
								<th class="text-left" width="10%">
									<span>Rp.</span>
								</th>
								<th class="text-right" width="90%">
									<span><a target="_blank" href="<?= base_url("laporan/cetak_pengiriman_penjualan?filter_date=".$filter_date_2 = date('d F Y',strtotime($date3)).' - '.date('d F Y',strtotime($arr_filter_date[1]))) ?>"><?php echo number_format($total_penjualan_all_2,0,',','.');?></a></span>
								</th>
							</tr>
					</table>
				</th>
	        </tr>
			<tr class="table-active3">
				<th colspan="6"></th>
			</tr>
			<tr class="table-active">
				<th class="text-left" colspan="6">Beban Pokok Penjualan</th>
	        </tr>
			<tr class="table-active3">
	            <th class="text-center"></th>
				<th class="text-left" colspan="2">Bahan</th>
				<th class="text-right">
					<table width="100%" border="0" cellpadding="0">
						<tr>
								<th class="text-left" width="10%">
									<span>Rp.</span>
								</th>
								<th class="text-right" width="90%">
									<span><a target="_blank" href="<?= base_url("laporan/cetak_bahan?filter_date=".$filter_date = date('d F Y',strtotime($arr_filter_date[0])).' - '.date('d F Y',strtotime($arr_filter_date[1]))) ?>"><?php echo number_format($bahan,0,',','.');?></a></span>
								</th>
							</tr>
					</table>
				</th>
				<th class="text-right"></th>
				<th class="text-right">
					<table width="100%" border="0" cellpadding="0">
						<tr>
								<th class="text-left" width="10%">
									<span>Rp.</span>
								</th>
								<th class="text-right" width="90%">
								<span><a target="_blank" href="<?= base_url("laporan/cetak_bahan_2?filter_date=".$filter_date = date('d F Y',strtotime($date3)).' - '.date('d F Y',strtotime($arr_filter_date[1]))) ?>"><?php echo number_format($bahan_2,0,',','.');?></a></span>
								</th>
							</tr>
					</table>
				</th>
	        </tr>
			<tr class="table-active3">
	            <th class="text-center"></th>
				<th class="text-left" colspan="2">Alat</th>
				<th class="text-right">
					<table width="100%" border="0" cellpadding="0">
						<tr>
								<th class="text-left" width="10%">
									<span>Rp.</span>
								</th>
								<th class="text-right" width="90%">
									<span><a target="_blank" href="<?= base_url("laporan/cetak_alat?filter_date=".$filter_date = date('d F Y',strtotime($arr_filter_date[0])).' - '.date('d F Y',strtotime($arr_filter_date[1]))) ?>"><?php echo number_format($alat,0,',','.');?></a></span>
								</th>
							</tr>
					</table>
				</th>
				<th class="text-right"></th>
				<th class="text-right">
					<table width="100%" border="0" cellpadding="0">
						<tr>
								<th class="text-left" width="10%">
									<span>Rp.</span>
								</th>
								<th class="text-right" width="90%">
									<span><a target="_blank" href="<?= base_url("laporan/cetak_alat_2?filter_date=".$filter_date_2 = date('d F Y',strtotime($date3)).' - '.date('d F Y',strtotime($arr_filter_date[1]))) ?>"><?php echo number_format($alat_2,0,',','.');?></a></span>
								</th>
							</tr>
					</table>
				</th>
	        </tr>
			<tr class="table-active3">
	            <th class="text-center"></th>
				<th class="text-left" colspan="2">BUA</th>
				<th class="text-right">
					<table width="100%" border="0" cellpadding="0">
						<tr>
								<th class="text-left" width="10%">
									<span>Rp.</span>
								</th>
								<th class="text-right" width="90%">
									<span><a target="_blank" href="<?= base_url("laporan/cetak_overhead?filter_date=".$filter_date = date('d F Y',strtotime($arr_filter_date[0])).' - '.date('d F Y',strtotime($arr_filter_date[1]))) ?>"><?php echo number_format($overhead,0,',','.');?></a></span>
								</th>
							</tr>
					</table>
				</th>
				<th class="text-right"></th>
				<th class="text-right">
					<table width="100%" border="0" cellpadding="0">
						<tr>
								<th class="text-left" width="10%">
									<span>Rp.</span>
								</th>
								<th class="text-right" width="90%">
									<span><a target="_blank" href="<?= base_url("laporan/cetak_overhead?filter_date=".$filter_date_2 = date('d F Y',strtotime($date3)).' - '.date('d F Y',strtotime($arr_filter_date[1]))) ?>"><?php echo number_format($overhead_2,0,',','.');?></a></span>
								</th>
							</tr>
					</table>
				</th>
	        </tr>
			<tr class="table-active3">
	            <th class="text-center"></th>
				<th class="text-left" colspan="2">Diskonto</th>
				<th class="text-right">
					<table width="100%" border="0" cellpadding="0">
						<tr>
								<th class="text-left" width="10%">
									<span>Rp.</span>
								</th>
								<th class="text-right" width="90%">
									<span><a target="_blank" href="<?= base_url("laporan/cetak_diskonto?filter_date=".$filter_date = date('d F Y',strtotime($arr_filter_date[0])).' - '.date('d F Y',strtotime($arr_filter_date[1]))) ?>"><?php echo number_format($diskonto,0,',','.');?></a></span>
								</th>
							</tr>
					</table>
				</th>
				<th class="text-right"></th>
				<th class="text-right">
					<table width="100%" border="0" cellpadding="0">
						<tr>
								<th class="text-left" width="10%">
									<span>Rp.</span>
								</th>
								<th class="text-right" width="90%">
									<span><a target="_blank" href="<?= base_url("laporan/cetak_diskonto?filter_date=".$filter_date_2 = date('d F Y',strtotime($date3)).' - '.date('d F Y',strtotime($arr_filter_date[1]))) ?>"><?php echo number_format($diskonto_2,0,',','.');?></a></span>
								</th>
							</tr>
					</table>
				</th>
	        </tr>
			<tr class="table-active3">
				<th class="text-left" colspan="3">Total Beban Pokok Penjualan</th>
				<th class="text-right">
					<table width="100%" border="0" cellpadding="0">
						<tr>
								<th class="text-left"width="10%">
									<span>Rp.</span>
								</th>
								<th class="text-right" width="90%">
									<span><?php echo number_format($total_biaya_operasional,0,',','.');?></span>
								</th>
							</tr>
					</table>				
				</th>
				<th class="text-right"></th>
				<th class="text-right">
					<table width="100%" border="0" cellpadding="0">
						<tr>
								<th class="text-left"width="10%">
									<span>Rp.</span>
								</th>
								<th class="text-right" width="90%">
									<span><?php echo number_format($total_biaya_operasional_2,0,',','.');?></span>
								</th>
							</tr>
					</table>				
				</th>
	        </tr>
			<tr class="table-active3">
				<th colspan="6"></th>
			</tr>
			<?php
				$styleColorLabaKotor = $laba_kotor < 0 ? 'color:red' : 'color:black';
				$styleColorLabaKotor2 = $laba_kotor_2 < 0 ? 'color:red' : 'color:black';
				$styleColorSebelumPajak = $laba_usaha < 0 ? 'color:red' : 'color:black';
				$styleColorSebelumPajak2 = $laba_usaha_2 < 0 ? 'color:red' : 'color:black';
				$styleColorPresentase = $persentase_laba_sebelum_pajak < 0 ? 'color:red' : 'color:black';
				$styleColorPresentase2 = $persentase_laba_sebelum_pajak_2 < 0 ? 'color:red' : 'color:black';
			?>
			<tr class="table-active3">
				<th class="text-left" colspan="3">Laba / Rugi Kotor</th>
	            <th class="text-right" style="<?php echo $styleColorLabaKotor ?>">
					<table width="100%" border="0" cellpadding="0">
						<tr>
								<th class="text-left" width="10%">
									<span>Rp.</span>
								</th>
								<th class="text-right" width="90%">
									<span><?php echo $laba_kotor < 0 ? "(".number_format(-$laba_kotor,0,',','.').")" : number_format($laba_kotor,0,',','.');?></span>
								</th>
							</tr>
					</table>
				</th>
				<th class="text-right"></th>
				<th class="text-right" style="<?php echo $styleColorLabaKotor2 ?>">
					<table width="100%" border="0" cellpadding="0">
						<tr>
								<th class="text-left" width="10%">
									<span>Rp.</span>
								</th>
								<th class="text-right" width="90%">
									<span><?php echo $laba_kotor_2 < 0 ? "(".number_format(-$laba_kotor_2,0,',','.').")" : number_format($laba_kotor_2,0,',','.');?></span>
								</th>
							</tr>
					</table>
				</th>
	        </tr>
			<tr class="table-active3">
				<th colspan="6"></th>
			</tr>
			<tr class="table-active3">
				<th class="text-left" colspan="3">Biaya Umum & Administrasi</th>
	            <th class="text-right">
					<table width="100%" border="0" cellpadding="0">
						<tr>
								<th class="text-left" width="10%">
									<span>Rp.</span>
								</th>
								<th class="text-right" width="90%">
									<span>-</span>
								</th>
							</tr>
					</table>
				</th>
				<th class="text-right"></th>
				<th class="text-right">
					<table width="100%" border="0" cellpadding="0">
						<tr>
								<th class="text-left" width="10%">
									<span>Rp.</span>
								</th>
								<th class="text-right" width="90%">
									<span>-</span>
								</th>
							</tr>
					</table>
				</th>
	        </tr>
			<tr class="table-active3">
				<th colspan="6"></th>
			</tr>
			<tr class="table-active3">
	            <th colspan="3" class="text-left">Laba / Rugi Usaha</th>
	            <th class="text-right" style="<?php echo $styleColorSebelumPajak ?>">
					<table width="100%" border="0" cellpadding="0">
						<tr>
								<th class="text-left" width="10%">
									<span>Rp.</span>
								</th>
								<th class="text-right" width="90%">
									<span><?php echo $laba_usaha < 0 ? "(".number_format(-$laba_usaha,0,',','.').")" : number_format($laba_usaha,0,',','.');?></span>
								</th>
							</tr>
					</table>
				</th>
				<th class="text-right"></th>
				<th class="text-right" style="<?php echo $styleColorSebelumPajak2 ?>">
					<table width="100%" border="0" cellpadding="0">
						<tr>
								<th class="text-left" width="10%">
									<span>Rp.</span>
								</th>
								<th class="text-right" width="90%">
									<span><?php echo $laba_usaha_2 < 0 ? "(".number_format(-$laba_usaha_2,0,',','.').")" : number_format($laba_usaha_2,0,',','.');?></span>
								</th>
							</tr>
					</table>
				</th>
	        </tr>
			<tr class="table-active3">
	            <th colspan="3" class="text-left">Presentase</th>
	            <th class="text-right" style="<?php echo $styleColorPresentase ?>">
					<table width="100%" border="0" cellpadding="0">
						<tr>
								<th class="text-left" width="10%">
									<span>Rp.</span>
								</th>
								<th class="text-right" width="90%">
									<span><?php echo $persentase_laba_sebelum_pajak < 0 ? "(".number_format(-$persentase_laba_sebelum_pajak,2,',','.').")" : number_format($persentase_laba_sebelum_pajak,2,',','.');?> %</span>
								</th>
							</tr>
					</table>
				</th>
				<th class="text-right"></th>
				<th class="text-right" style="<?php echo $styleColorPresentase2 ?>">
					<table width="100%" border="0" cellpadding="0">
						<tr>
								<th class="text-left" width="10%">
									<span>Rp.</span>
								</th>
								<th class="text-right" width="90%">
									<span><?php echo $persentase_laba_sebelum_pajak_2 < 0 ? "(".number_format(-$persentase_laba_sebelum_pajak_2,2,',','.').")" : number_format($persentase_laba_sebelum_pajak_2,2,',','.');?> %</span>
								</th>
							</tr>
					</table>
				</th>
	        </tr>
	    </table>
		<?php
	}

	public function cash_flow($arr_date)
	{
		$data = array();
		
		$arr_date = $this->input->post('filter_date');
		$arr_filter_date = explode(' - ', $arr_date);
		$date1 = '';
		$date2 = '';

		if(count($arr_filter_date) == 2){
			$date1 	= date('Y-m-d',strtotime($arr_filter_date[0]));
			$date2 	= date('Y-m-d',strtotime($arr_filter_date[1]));
			$filter_date = date('d F Y',strtotime($arr_filter_date[0])).' - '.date('d F Y',strtotime($arr_filter_date[1]));
		}
		
		?>
		
		<table class="table table-bordered" width="100%">
			<style type="text/css">
				body {
					font-family: helvetica;
				}

				table tr.table-active-csf{
					background-color: #F0F0F0;
					font-size: 8px;
					font-weight: bold;
					color: black;
				}
					
				table tr.table-active2-csf{
					background-color: #E8E8E8;
					font-size: 8px;
					font-weight: bold;
				}
					
				table tr.table-active3-csf{
					font-size: 8px;
					background-color: #F0F0F0;
				}
					
				table tr.table-active4-csf{
					background-color: #e69500;
					font-weight: bold;
					font-size: 8px;
					color: black;
				}
				table tr.table-active5-csf{
					background-color: #E8E8E8;
					text-decoration: underline;
					font-size: 8px;
					font-weight: bold;
					color: red;
				}
				table tr.table-active6-csf{
					background-color: #A9A9A9;
					font-size: 8px;
					font-weight: bold;
				}
				table tr.table-active7-csf{
					background-color: #ffd966;
					font-weight: bold;
					font-size: 8px;
					color: black;
				}
				table tr.table-active8-csf{
					background-color: #2986cc;
					font-weight: bold;
					font-size: 8px;
					color: black;
				}
			</style>

			<?php
			//RAP
			$date_now = date('Y-m-d');
			$date_end = date('2022-12-31');

			$rencana_kerja_2022_1 = $this->db->select('r.*')
			->from('rak r')
			->where("r.tanggal_rencana_kerja between '2021-12-30' and '2021-12-30'")
			->get()->row_array();
			
			$rencana_kerja_2022_2 = $this->db->select('r.*')
			->from('rak r')
			->where("r.tanggal_rencana_kerja between '2021-12-31' and '2021-12-31'")
			->get()->row_array();
			

			$volume_rap_2022_produk_a = $rencana_kerja_2022_1['vol_produk_a'] + $rencana_kerja_2022_2['vol_produk_a'];
			$volume_rap_2022_produk_b = $rencana_kerja_2022_1['vol_produk_b'] + $rencana_kerja_2022_2['vol_produk_b'];
			$volume_rap_2022_produk_c = $rencana_kerja_2022_1['vol_produk_c'] + $rencana_kerja_2022_2['vol_produk_c'];
			$volume_rap_2022_produk_d = $rencana_kerja_2022_1['vol_produk_d'] + $rencana_kerja_2022_2['vol_produk_d'];

			$total_rap_volume_2022 = $rencana_kerja_2022_1['vol_produk_a'] + $rencana_kerja_2022_1['vol_produk_b'] + $rencana_kerja_2022_1['vol_produk_c'] + $rencana_kerja_2022_1['vol_produk_d'] + $rencana_kerja_2022_2['vol_produk_a'] + $rencana_kerja_2022_2['vol_produk_b'] + $rencana_kerja_2022_2['vol_produk_c'] + $rencana_kerja_2022_2['vol_produk_d'];
			
			$price_produk_a_1 = $rencana_kerja_2022_1['vol_produk_a'] * $rencana_kerja_2022_1['price_a'];
			$price_produk_b_1 = $rencana_kerja_2022_1['vol_produk_b'] * $rencana_kerja_2022_1['price_b'];
			$price_produk_c_1 = $rencana_kerja_2022_1['vol_produk_c'] * $rencana_kerja_2022_1['price_c'];
			$price_produk_d_1 = $rencana_kerja_2022_1['vol_produk_d'] * $rencana_kerja_2022_1['price_d'];

			$price_produk_a_2 = $rencana_kerja_2022_2['vol_produk_a'] * $rencana_kerja_2022_2['price_a'];
			$price_produk_b_2 = $rencana_kerja_2022_2['vol_produk_b'] * $rencana_kerja_2022_2['price_b'];
			$price_produk_c_2 = $rencana_kerja_2022_2['vol_produk_c'] * $rencana_kerja_2022_2['price_c'];
			$price_produk_d_2 = $rencana_kerja_2022_2['vol_produk_d'] * $rencana_kerja_2022_2['price_d'];

			$nilai_jual_all_2022 = $price_produk_a_1 + $price_produk_b_1 + $price_produk_c_1 + $price_produk_d_1 + $price_produk_a_2 + $price_produk_b_2 + $price_produk_c_2 + $price_produk_d_2;
			$total_rap_nilai_2022 = $nilai_jual_all_2022;

			//BIAYA RAP 2022
			$rencana_kerja_2022_biaya_1 = $this->db->select('r.*')
			->from('rak r')
			->where("r.tanggal_rencana_kerja between '2021-12-30' and '2021-12-30'")
			->get()->row_array();

			$rencana_kerja_2022_biaya_2 = $this->db->select('r.*')
			->from('rak r')
			->where("r.tanggal_rencana_kerja between '2021-12-31' and '2021-12-31'")
			->get()->row_array();
		
			$total_rap_2022_biaya_bahan = $rencana_kerja_2022_biaya_1['biaya_bahan'] + $rencana_kerja_2022_biaya_2['biaya_bahan'];
			$total_rap_2022_biaya_alat = $rencana_kerja_2022_biaya_1['biaya_alat'] + $rencana_kerja_2022_biaya_2['biaya_alat'];
			$total_rap_2022_biaya_bank = $rencana_kerja_2022_biaya_1['biaya_bank'] + $rencana_kerja_2022_biaya_2['biaya_bank'];
			$total_rap_2022_biaya_overhead = $rencana_kerja_2022_biaya_1['overhead'] + $rencana_kerja_2022_biaya_2['overhead'];
			$total_rap_2022_biaya_persiapan = 0;
			$total_rap_2022_pajak_keluaran = ($total_rap_nilai_2022 * 11) / 100;
			$total_rap_2022_pajak_masukan = (($total_rap_2022_biaya_bahan + $total_rap_2022_biaya_alat) * 11) / 100;
			$total_rap_2022_penerimaan_pinjaman = 1300000000;
			$total_rap_2022_pengembalian_pinjaman = 1300000000;
			$total_rap_2022_pinjaman_dana = 0;
			$total_rap_2022_piutang = 0;
			$total_rap_2022_hutang = 0;
			?>

			<?php
			//NOW
			//$last_opname_start = date('Y-m-01', (strtotime($date_now)));
			//$last_opname = date('Y-m-d', strtotime('-1 days', strtotime($last_opname_start)));

			$date_approval = $this->db->select('date_approval')->order_by('date_approval','desc')->limit(1)->get_where('ttd_laporan',array('status'=>'PUBLISH'))->row_array();
			$last_opname = date('Y-m-d', strtotime('0 days', strtotime($date_approval['date_approval'])));

			//PRODUKSI (PENJUALAN) NOW
			$penjualan_now = $this->db->select('SUM(pp.display_price) as total')
			->from('pmm_productions pp')
			->join('penerima p', 'pp.client_id = p.id','left')
			->join('pmm_sales_po ppo', 'pp.salesPo_id = ppo.id','left')
			->where("pp.date_production < '$last_opname'")
			->where("pp.status = 'PUBLISH'")
			->where("ppo.status in ('OPEN','CLOSED')")
			->group_by("pp.client_id")
			->get()->row_array();

			$pembayaran_bahan_now = $this->db->select('SUM(pm.total) as total')
			->from('pmm_pembayaran_penagihan_pembelian pm')
			->join('pmm_penagihan_pembelian ppp','pm.penagihan_pembelian_id = ppp.id','left')
			->join('pmm_purchase_order ppo','ppp.purchase_order_id = ppo.id','left')
			->where("(pm.tanggal_pembayaran <= '$last_opname')")
			->where("ppo.kategori_id = '1'")
			->where("pm.memo <> 'PPN'")
			->get()->row_array();
			$pembayaran_bahan_now = $pembayaran_bahan_now['total'];

			//ALAT NOW
			$pembayaran_alat_now = $this->db->select('SUM(pm.total) as total')
			->from('pmm_pembayaran_penagihan_pembelian pm')
			->join('pmm_penagihan_pembelian ppp','pm.penagihan_pembelian_id = ppp.id','left')
			->join('pmm_purchase_order ppo','ppp.purchase_order_id = ppo.id','left')
			->where("(pm.tanggal_pembayaran <= '$last_opname')")
			->where("ppo.kategori_id = '5'")
			->where("pm.memo <> 'PPN'")
			->get()->row_array();
			$pembayaran_alat_now = $pembayaran_alat_now['total'];

			$insentif_tm_now = $this->db->select('sum(pdb.debit) as total')
			->from('pmm_jurnal_umum pb ')
			->join('pmm_detail_jurnal pdb','pb.id = pdb.jurnal_id','left')
			->where("pdb.akun = 220")
			->where("status = 'PAID'")
			->where("(tanggal_transaksi <= '$last_opname')")
			->get()->row_array();
			$total_insentif_tm_now = $insentif_tm_now['total'];

			$insentif_wl_now = $this->db->select('sum(pdb.debit) as total')
			->from('pmm_jurnal_umum pb ')
			->join('pmm_detail_jurnal pdb','pb.id = pdb.jurnal_id','left')
			->where("pdb.akun = 221")
			->where("status = 'PAID'")
			->where("(tanggal_transaksi <= '$last_opname')")
			->get()->row_array();
			$total_insentif_wl_now = $insentif_wl_now['total'];

			$alat_now = $pembayaran_alat_now + $total_insentif_tm_now + $total_insentif_wl_now;

			//DISKONTO NOW
			$diskonto_now = $this->db->select('sum(pdb.jumlah) as total')
			->from('pmm_biaya pb ')
			->join('pmm_detail_biaya pdb','pb.id = pdb.biaya_id','left')
			->join('pmm_coa c','pdb.akun = c.id','left')
			->where("pdb.akun = 168")
			->where("pb.status = 'PAID'")
			->where("pb.tanggal_transaksi < '$last_opname'")
			->get()->row_array();
			$diskonto_now = $diskonto_now['total'];

			//OVERHEAD NOW
			$overhead_15_now = $this->db->select('sum(pdb.jumlah) as total')
			->from('pmm_biaya pb ')
			->join('pmm_detail_biaya pdb','pb.id = pdb.biaya_id','left')
			->join('pmm_coa c','pdb.akun = c.id','left')
			->where('c.coa_category',15)
			->where("c.id <> 168 ") //Biaya Diskonto Bank
			->where("c.id <> 219 ") //Biaya Alat Batching Plant 
			->where("c.id <> 220 ") //Biaya Alat Truck Mixer
			->where("c.id <> 221 ") //Biaya Alat Wheel Loader
			->where("c.id <> 228 ") //Biaya Persiapan
			->where("c.id <> 505 ") //Biaya Oli
			->where("pb.status = 'PAID'")
			->where("(pb.tanggal_transaksi <= '$last_opname')")
			->get()->row_array();

			$overhead_jurnal_15_now = $this->db->select('sum(pdb.debit) as total')
			->from('pmm_jurnal_umum pb ')
			->join('pmm_detail_jurnal pdb','pb.id = pdb.jurnal_id','left')
			->join('pmm_coa c','pdb.akun = c.id','left')
			->where('c.coa_category',15)
			->where("c.id <> 168 ") //Biaya Diskonto Bank
			->where("c.id <> 219 ") //Biaya Alat Batching Plant 
			->where("c.id <> 220 ") //Biaya Alat Truck Mixer
			->where("c.id <> 221 ") //Biaya Alat Wheel Loader
			->where("c.id <> 228 ") //Biaya Persiapan
			->where("c.id <> 505 ") //Biaya Oli
			->where("pb.status = 'PAID'")
			->where("(pb.tanggal_transaksi <= '$last_opname')")
			->get()->row_array();
			$overhead_now =  $overhead_15_now['total'] + $overhead_jurnal_15_now['total'];

			//TERMIN NOW
			$termin_now = $this->db->select('SUM(pm.total) as total')
			->from('pmm_pembayaran pm')
			->where("(pm.tanggal_pembayaran <= '$last_opname')")
			->where("pm.status = 'DISETUJUI'")
			->where("pm.memo <> 'PPN'")
			->get()->row_array();

			//PPN KELUAR NOW
			$ppn_masuk_now = $this->db->select('SUM(pm.total) as total')
			->from('pmm_pembayaran_penagihan_pembelian pm')
			->where("pm.memo = 'PPN'")
			->where("pm.tanggal_pembayaran < '$last_opname'")
			->get()->row_array();

			//PPN MASUK NOW
			$ppn_keluar_now = $this->db->select('SUM(pm.total) as total')
			->from('pmm_pembayaran pm')
			->where("pm.memo = 'PPN'")
			->where("pm.tanggal_pembayaran < '$last_opname'")
			->get()->row_array();

			//BIAYA PERSIAPAN NOW
			$biaya_persiapan_now = $this->db->select('r.*, SUM(r.biaya_persiapan) as total')
			->from('rencana_cash_flow r')
			->where("r.tanggal_rencana_kerja < '$last_opname'")
			->get()->row_array();

			//PPN KELUARAN
			$ppn_keluaran_now = $this->db->select('SUM(ppd.tax) as total')
			->from('pmm_penagihan_penjualan ppp')
			->join('pmm_penagihan_penjualan_detail ppd','ppp.id = ppd.penagihan_id','left')
			->where("ppp.tanggal_invoice < '$last_opname'")
			->get()->row_array();

			//PPN MASUKAN
			$ppn_masukan_now = $this->db->select('SUM(v.ppn) as total')
			->from('pmm_verifikasi_penagihan_pembelian v')
			->join('pmm_penagihan_pembelian ppp','v.penagihan_pembelian_id = ppp.id','left')
			->where("ppp.tanggal_invoice < '$last_opname'")
			->get()->row_array();

			//PINJAMAN DANA NOW
			$pinjaman_dana_now = $this->db->select('sum(pdb.debit) as total')
			->from('pmm_jurnal_umum pb ')
			->join('pmm_detail_jurnal pdb','pb.id = pdb.jurnal_id','left')
			->join('pmm_coa c','pdb.akun = c.id','left')
			->where("pdb.akun = 232")
			->where("pb.status = 'PAID'")
			->where("pb.tanggal_transaksi < '$last_opname'")
			->get()->row_array();

			//PIUTANG NOW
			$penerimaan_piutang_now = $this->db->select('SUM(pp.display_price) as total')
			->from('pmm_productions pp')
			->join('pmm_sales_po po','pp.salesPo_id = po.id','left')
			->where("po.status in ('OPEN','CLOSED')")
			->where("pp.date_production <= '$last_opname'")
			->get()->row_array();

			$pembayaran_piutang_now = $this->db->select('SUM(pm.total) as total')
			->from('pmm_pembayaran pm')
			->where("pm.memo <> 'PPN'")
			->where("pm.tanggal_pembayaran <= '$last_opname'")
			->get()->row_array();
			$piutang_now = $penerimaan_piutang_now['total'] - $pembayaran_piutang_now['total'];

			$piutang_now_dpp = $penjualan_now['total'] - $termin_now['total'];
			$piutang_now_ppn = $ppn_keluaran_now['total'] - $ppn_keluar_now['total'];
			$piutang_now = $piutang_now_dpp + $piutang_now_ppn;

			//HUTANG NOW
			$penerimaan_hutang_now = $this->db->select('SUM(prm.display_price) as total')
			->from('pmm_receipt_material prm')
			->join('pmm_purchase_order ppo','prm.purchase_order_id = ppo.id','left')
			->where("ppo.status in ('PUBLISH','CLOSED')")
			->where("prm.date_receipt <= '$last_opname'")
			->get()->row_array();

			$pembayaran_hutang_now = $this->db->select('SUM(pm.total) as total')
			->from('pmm_pembayaran_penagihan_pembelian pm')
			->where("pm.memo <> 'PPN'")
			->where("pm.tanggal_pembayaran <= '$last_opname'")
			->get()->row_array();
			$hutang_now = $penerimaan_hutang_now['total'] - $pembayaran_hutang_now['total'];

			$akumulasi_penerimaan_bahan = $this->db->select('SUM(prm.display_price) as total')
			->from('pmm_receipt_material prm')
			->join('pmm_purchase_order ppo','prm.purchase_order_id = ppo.id','left')
			->where("ppo.status in ('PUBLISH','CLOSED')")
			->where("prm.date_receipt <= '$last_opname'")
			->get()->row_array();

			$hutang_now_dpp = (($akumulasi_penerimaan_bahan['total'] + $total_insentif_tm_now + $total_insentif_wl_now) - ($pembayaran_bahan_now + $pembayaran_alat_now));
			$hutang_now_ppn = $ppn_masukan_now['total'] - $ppn_keluar_now['total'];
			$hutang_now = $hutang_now_dpp + $hutang_now_ppn;

			//MOS NOW
			$harga_hpp_bahan_baku_now = $this->db->select('pp.date_hpp, pp.semen, pp.pasir, pp.batu1020, pp.batu2030, pp.solar')
			->from('hpp_bahan_baku pp')
			->where("(pp.date_hpp <= '$last_opname')")
			->order_by('pp.date_hpp','desc')->limit(1)
			->get()->row_array();
			
			$stock_opname_semen_now = $this->db->select('cat.date, (cat.display_volume) as volume')
			->from('pmm_remaining_materials_cat cat ')
			->where("(cat.date <= '$last_opname')")
			->where("cat.material_id = 4")
			->where("cat.status = 'PUBLISH'")
			->order_by('cat.date','desc')->limit(1)
			->get()->row_array();
			$nilai_semen = $stock_opname_semen_now['volume'] * $harga_hpp_bahan_baku_now['semen'];

			$stock_opname_pasir_now = $this->db->select('cat.date, (cat.display_volume) as volume')
			->from('pmm_remaining_materials_cat cat ')
			->where("(cat.date <= '$last_opname')")
			->where("cat.material_id = 5")
			->where("cat.status = 'PUBLISH'")
			->order_by('cat.date','desc')->limit(1)
			->get()->row_array();
			$nilai_pasir = $stock_opname_pasir_now['volume'] * $harga_hpp_bahan_baku_now['pasir'];

			$stock_opname_batu1020_now = $this->db->select('cat.date, (cat.display_volume) as volume')
			->from('pmm_remaining_materials_cat cat ')
			->where("(cat.date <= '$last_opname')")
			->where("cat.material_id = 6")
			->where("cat.status = 'PUBLISH'")
			->order_by('cat.date','desc')->limit(1)
			->get()->row_array();
			$nilai_batu1020 = $stock_opname_batu1020_now['volume'] * $harga_hpp_bahan_baku_now['batu1020'];

			$stock_opname_batu2030_now = $this->db->select('cat.date, (cat.display_volume) as volume')
			->from('pmm_remaining_materials_cat cat ')
			->where("(cat.date <= '$last_opname')")
			->where("cat.material_id = 7")
			->where("cat.status = 'PUBLISH'")
			->order_by('cat.date','desc')->limit(1)
			->get()->row_array();
			$nilai_batu2030 = $stock_opname_batu2030_now['volume'] * $harga_hpp_bahan_baku_now['batu2030'];

			$stock_opname_solar_now = $this->db->select('cat.date, (cat.display_volume) as volume')
			->from('pmm_remaining_materials_cat cat ')
			->where("(cat.date <= '$last_opname')")
			->where("cat.material_id = 8")
			->where("cat.status = 'PUBLISH'")
			->order_by('cat.date','desc')->limit(1)
			->get()->row_array();
			$nilai_solar = $stock_opname_solar_now['volume'] * $harga_hpp_bahan_baku_now['solar'];

			$mos_now = $nilai_semen + $nilai_pasir + $nilai_batu1020 + $nilai_batu2030 + $nilai_solar;

			?>

			<?php
			//BULAN 1
			//$date_1_awal = date('Y-m-01', (strtotime($last_opname)));
			//$date_1_akhir = date('Y-m-d', strtotime('-1 days +1 months', strtotime($date_1_awal)));

			$date_1_awal = date('Y-m-01', strtotime('+1 days +1 months', strtotime($last_opname)));
			$date_1_akhir = date('Y-m-d', strtotime('-1 days +1 months', strtotime($date_1_awal)));

			$rencana_kerja_1 = $this->db->select('r.*')
			->from('rak r')
			->where("r.tanggal_rencana_kerja between '$date_1_awal' and '$date_1_akhir'")
			->get()->row_array();

			$rencana_kerja_1_biaya_cash_flow = $this->db->select('r.*')
			->from('rencana_cash_flow r')
			->where("r.tanggal_rencana_kerja between '$date_1_awal' and '$date_1_akhir'")
			->get()->row_array();

			$volume_1_produk_a = $rencana_kerja_1['vol_produk_a'];
			$volume_1_produk_b = $rencana_kerja_1['vol_produk_b'];
			$volume_1_produk_c = $rencana_kerja_1['vol_produk_c'];
			$volume_1_produk_d = $rencana_kerja_1['vol_produk_d'];

			$total_1_volume = $volume_1_produk_a + $volume_1_produk_b + $volume_1_produk_c + $volume_1_produk_d;
		
			$nilai_jual_125_1 = $volume_1_produk_a * $rencana_kerja_1['price_a'];
			$nilai_jual_225_1 = $volume_1_produk_b * $rencana_kerja_1['price_b'];
			$nilai_jual_250_1 = $volume_1_produk_c * $rencana_kerja_1['price_c'];
			$nilai_jual_250_18_1 = $volume_1_produk_d * $rencana_kerja_1['price_d'];
			$nilai_jual_all_1 = $nilai_jual_125_1 + $nilai_jual_225_1 + $nilai_jual_250_1 + $nilai_jual_250_18_1;
			
			$total_1_nilai = $nilai_jual_all_1;;
			
			$volume_rencana_kerja_1_produk_a = $rencana_kerja_1['vol_produk_a'];
			$volume_rencana_kerja_1_produk_b = $rencana_kerja_1['vol_produk_b'];
			$volume_rencana_kerja_1_produk_c = $rencana_kerja_1['vol_produk_c'];
			$volume_rencana_kerja_1_produk_d = $rencana_kerja_1['vol_produk_d'];

			$total_1_biaya_bahan = $rencana_kerja_1_biaya_cash_flow['biaya_bahan'];
			$total_1_biaya_alat = $rencana_kerja_1_biaya_cash_flow['biaya_alat'];
			$total_1_biaya_bank = $rencana_kerja_1_biaya_cash_flow['biaya_bank'];
			$total_1_biaya_overhead = $rencana_kerja_1_biaya_cash_flow['overhead'];
			$total_1_biaya_termin = $rencana_kerja_1_biaya_cash_flow['termin'];
			$total_1_biaya_persiapan = $rencana_kerja_1_biaya_cash_flow['biaya_persiapan'];

			$pinjaman_dana_1 = $this->db->select('sum(pdb.debit) as total')
			->from('pmm_jurnal_umum pb ')
			->join('pmm_detail_jurnal pdb','pb.id = pdb.jurnal_id','left')
			->join('pmm_coa c','pdb.akun = c.id','left')
			->where("pdb.akun = 232")
			->where("pb.status = 'PAID'")
			->where("pb.tanggal_transaksi between '$date_1_awal' and '$date_1_akhir'")
			->get()->row_array();

			$penerimaan_hutang_1 = $this->db->select('SUM(prm.display_price) as total')
			->from('pmm_receipt_material prm')
			->join('pmm_purchase_order ppo','prm.purchase_order_id = ppo.id','left')
			->where("ppo.status in ('PUBLISH','CLOSED')")
			->where("prm.date_receipt between '$date_1_awal' and '$date_1_akhir'")
			->get()->row_array();


			$pembayaran_hutang_1 = $this->db->select('SUM(pm.total) as total')
			->from('pmm_pembayaran_penagihan_pembelian pm')
			->where("pm.memo <> 'PPN'")
			->where("pm.tanggal_pembayaran between '$date_1_awal' and '$date_1_akhir'")
			->get()->row_array();
			$hutang_1 = $penerimaan_hutang_1['total'] - $pembayaran_hutang_1['total'];
			?>

			<?php
			//BULAN 2
			$date_2_awal = date('Y-m-d', strtotime('+1 days', strtotime($date_1_akhir)));
			$date_2_akhir = date('Y-m-d', strtotime('-1 days +1 months', strtotime($date_2_awal)));

			$rencana_kerja_2 = $this->db->select('r.*')
			->from('rak r')
			->where("r.tanggal_rencana_kerja between '$date_2_awal' and '$date_2_akhir'")
			->get()->row_array();

			$rencana_kerja_2_biaya_cash_flow = $this->db->select('r.*')
			->from('rencana_cash_flow r')
			->where("r.tanggal_rencana_kerja between '$date_2_awal' and '$date_2_akhir'")
			->get()->row_array();

			$volume_2_produk_a = $rencana_kerja_2['vol_produk_a'];
			$volume_2_produk_b = $rencana_kerja_2['vol_produk_b'];
			$volume_2_produk_c = $rencana_kerja_2['vol_produk_c'];
			$volume_2_produk_d = $rencana_kerja_2['vol_produk_d'];

			$total_2_volume = $volume_2_produk_a + $volume_2_produk_b + $volume_2_produk_c + $volume_2_produk_d;
		
			$nilai_jual_125_2 = $volume_2_produk_a * $rencana_kerja_2['price_a'];
			$nilai_jual_225_2 = $volume_2_produk_b * $rencana_kerja_2['price_b'];
			$nilai_jual_250_2 = $volume_2_produk_c * $rencana_kerja_2['price_c'];
			$nilai_jual_250_18_2 = $volume_2_produk_d * $rencana_kerja_2['price_d'];
			$nilai_jual_all_2 = $nilai_jual_125_2 + $nilai_jual_225_2 + $nilai_jual_250_2 + $nilai_jual_250_18_2;
			
			$total_2_nilai = $nilai_jual_all_2; 
			
			$volume_rencana_kerja_2_produk_a = $rencana_kerja_2['vol_produk_a'];
			$volume_rencana_kerja_2_produk_b = $rencana_kerja_2['vol_produk_b'];
			$volume_rencana_kerja_2_produk_c = $rencana_kerja_2['vol_produk_c'];
			$volume_rencana_kerja_2_produk_d = $rencana_kerja_2['vol_produk_d'];

			$total_2_biaya_bahan = $rencana_kerja_2_biaya_cash_flow['biaya_bahan'];
			$total_2_biaya_alat = $rencana_kerja_2_biaya_cash_flow['biaya_alat'];
			$total_2_biaya_bank = $rencana_kerja_2_biaya_cash_flow['biaya_bank'];
			$total_2_biaya_overhead = $rencana_kerja_2_biaya_cash_flow['overhead'];
			$total_2_biaya_termin = $rencana_kerja_2_biaya_cash_flow['termin'];
			$total_2_biaya_persiapan = $rencana_kerja_2_biaya_cash_flow['biaya_persiapan'];

			$pinjaman_dana_2 = $this->db->select('sum(pdb.debit) as total')
			->from('pmm_jurnal_umum pb ')
			->join('pmm_detail_jurnal pdb','pb.id = pdb.jurnal_id','left')
			->join('pmm_coa c','pdb.akun = c.id','left')
			->where("pdb.akun = 232")
			->where("pb.status = 'PAID'")
			->where("pb.tanggal_transaksi between '$date_2_awal' and '$date_2_akhir'")
			->get()->row_array();

			$penerimaan_hutang_2 = $this->db->select('SUM(prm.display_price) as total')
			->from('pmm_receipt_material prm')
			->join('pmm_purchase_order ppo','prm.purchase_order_id = ppo.id','left')
			->where("ppo.status in ('PUBLISH','CLOSED')")
			->where("prm.date_receipt between '$date_2_awal' and '$date_2_akhir'")
			->get()->row_array();

			$pembayaran_hutang_2 = $this->db->select('SUM(pm.total) as total')
			->from('pmm_pembayaran_penagihan_pembelian pm')
			->where("pm.memo <> 'PPN'")
			->where("pm.tanggal_pembayaran between '$date_2_awal' and '$date_2_akhir'")
			->get()->row_array();
			$hutang_2 = $penerimaan_hutang_2['total'] - $pembayaran_hutang_2['total'];
			?>

			<?php
			//BULAN 3
			$date_3_awal = date('Y-m-d', strtotime('+1 days', strtotime($date_2_akhir)));
			$date_3_akhir = date('Y-m-d', strtotime('-1 days +1 months', strtotime($date_3_awal)));

			$rencana_kerja_3 = $this->db->select('r.*')
			->from('rak r')
			->where("r.tanggal_rencana_kerja between '$date_3_awal' and '$date_3_akhir'")
			->get()->row_array();

			$rencana_kerja_3_biaya_cash_flow = $this->db->select('r.*')
			->from('rencana_cash_flow r')
			->where("r.tanggal_rencana_kerja between '$date_3_awal' and '$date_3_akhir'")
			->get()->row_array();

			$volume_3_produk_a = $rencana_kerja_3['vol_produk_a'];
			$volume_3_produk_b = $rencana_kerja_3['vol_produk_b'];
			$volume_3_produk_c = $rencana_kerja_3['vol_produk_c'];
			$volume_3_produk_d = $rencana_kerja_3['vol_produk_d'];

			$total_3_volume = $volume_3_produk_a + $volume_3_produk_b + $volume_3_produk_c + $volume_3_produk_d;
		
			$nilai_jual_125_3 = $volume_3_produk_a * $rencana_kerja_3['price_a'];
			$nilai_jual_225_3 = $volume_3_produk_b * $rencana_kerja_3['price_b'];
			$nilai_jual_250_3 = $volume_3_produk_c * $rencana_kerja_3['price_c'];
			$nilai_jual_250_18_3 = $volume_3_produk_d * $rencana_kerja_3['price_d'];
			$nilai_jual_all_3 = $nilai_jual_125_3 + $nilai_jual_225_3 + $nilai_jual_250_3 + $nilai_jual_250_18_3;
			
			$total_3_nilai = $nilai_jual_all_3;
			
			$volume_rencana_kerja_3_produk_a = $rencana_kerja_3['vol_produk_a'];
			$volume_rencana_kerja_3_produk_b = $rencana_kerja_3['vol_produk_b'];
			$volume_rencana_kerja_3_produk_c = $rencana_kerja_3['vol_produk_c'];
			$volume_rencana_kerja_3_produk_d = $rencana_kerja_3['vol_produk_d'];

			$total_3_biaya_bahan = $rencana_kerja_3_biaya_cash_flow['biaya_bahan'];
			$total_3_biaya_alat = $rencana_kerja_3_biaya_cash_flow['biaya_alat'];
			$total_3_biaya_bank = $rencana_kerja_3_biaya_cash_flow['biaya_bank'];
			$total_3_biaya_overhead = $rencana_kerja_3_biaya_cash_flow['overhead'];
			$total_3_biaya_termin = $rencana_kerja_3_biaya_cash_flow['termin'];
			$total_3_biaya_persiapan = $rencana_kerja_3_biaya_cash_flow['biaya_persiapan'];

			$pinjaman_dana_3 = $this->db->select('sum(pdb.debit) as total')
			->from('pmm_jurnal_umum pb ')
			->join('pmm_detail_jurnal pdb','pb.id = pdb.jurnal_id','left')
			->join('pmm_coa c','pdb.akun = c.id','left')
			->where("pdb.akun = 232")
			->where("pb.status = 'PAID'")
			->where("pb.tanggal_transaksi between '$date_3_awal' and '$date_3_akhir'")
			->get()->row_array();

			$penerimaan_hutang_3 = $this->db->select('SUM(prm.display_price) as total')
			->from('pmm_receipt_material prm')
			->join('pmm_purchase_order ppo','prm.purchase_order_id = ppo.id','left')
			->where("ppo.status in ('PUBLISH','CLOSED')")
			->where("prm.date_receipt between '$date_3_awal' and '$date_3_akhir'")
			->get()->row_array();

			$pembayaran_hutang_3 = $this->db->select('SUM(pm.total) as total')
			->from('pmm_pembayaran_penagihan_pembelian pm')
			->where("pm.memo <> 'PPN'")
			->where("pm.tanggal_pembayaran between '$date_3_awal' and '$date_3_akhir'")
			->get()->row_array();
			$hutang_3 = $penerimaan_hutang_3['total'] - $pembayaran_hutang_3['total'];
			?>

			<?php
			//BULAN 4
			$date_4_awal = date('Y-m-d', strtotime('+1 days', strtotime($date_3_akhir)));
			$date_4_akhir = date('Y-m-d', strtotime('-1 days +1 months', strtotime($date_4_awal)));

			$rencana_kerja_4 = $this->db->select('r.*')
			->from('rak r')
			->where("r.tanggal_rencana_kerja between '$date_4_awal' and '$date_4_akhir'")
			->get()->row_array();

			$rencana_kerja_4_biaya_cash_flow = $this->db->select('r.*')
			->from('rencana_cash_flow r')
			->where("r.tanggal_rencana_kerja between '$date_4_awal' and '$date_4_akhir'")
			->get()->row_array();

			$volume_4_produk_a = $rencana_kerja_4['vol_produk_a'];
			$volume_4_produk_b = $rencana_kerja_4['vol_produk_b'];
			$volume_4_produk_c = $rencana_kerja_4['vol_produk_c'];
			$volume_4_produk_d = $rencana_kerja_4['vol_produk_d'];

			$total_4_volume = $volume_4_produk_a + $volume_4_produk_b + $volume_4_produk_c + $volume_4_produk_d;
		
			$nilai_jual_125_4 = $volume_4_produk_a * $rencana_kerja_4['price_a'];
			$nilai_jual_225_4 = $volume_4_produk_b * $rencana_kerja_4['price_b'];
			$nilai_jual_250_4 = $volume_4_produk_c * $rencana_kerja_4['price_c'];
			$nilai_jual_250_18_4 = $volume_4_produk_d * $rencana_kerja_4['price_d'];
			$nilai_jual_all_4 = $nilai_jual_125_4 + $nilai_jual_225_4 + $nilai_jual_250_4 + $nilai_jual_250_18_4;
			
			$total_4_nilai = $nilai_jual_all_4;
			
			$volume_rencana_kerja_4_produk_a = $rencana_kerja_4['vol_produk_a'];
			$volume_rencana_kerja_4_produk_b = $rencana_kerja_4['vol_produk_b'];
			$volume_rencana_kerja_4_produk_c = $rencana_kerja_4['vol_produk_c'];
			$volume_rencana_kerja_4_produk_d = $rencana_kerja_4['vol_produk_d'];

			$total_4_biaya_bahan = $rencana_kerja_4_biaya_cash_flow['biaya_bahan'];
			$total_4_biaya_alat = $rencana_kerja_4_biaya_cash_flow['biaya_alat'];
			$total_4_biaya_bank = $rencana_kerja_4_biaya_cash_flow['biaya_bank'];
			$total_4_biaya_overhead = $rencana_kerja_4_biaya_cash_flow['overhead'];
			$total_4_biaya_termin = $rencana_kerja_4_biaya_cash_flow['termin'];
			$total_4_biaya_persiapan = $rencana_kerja_4_biaya_cash_flow['biaya_persiapan'];

			$pinjaman_dana_4 = $this->db->select('sum(pdb.debit) as total')
			->from('pmm_jurnal_umum pb ')
			->join('pmm_detail_jurnal pdb','pb.id = pdb.jurnal_id','left')
			->join('pmm_coa c','pdb.akun = c.id','left')
			->where("pdb.akun = 232")
			->where("pb.status = 'PAID'")
			->where("pb.tanggal_transaksi between '$date_4_awal' and '$date_4_akhir'")
			->get()->row_array();

			$penerimaan_hutang_4 = $this->db->select('SUM(prm.display_price) as total')
			->from('pmm_receipt_material prm')
			->join('pmm_purchase_order ppo','prm.purchase_order_id = ppo.id','left')
			->where("ppo.status in ('PUBLISH','CLOSED')")
			->where("prm.date_receipt between '$date_4_awal' and '$date_4_akhir'")
			->get()->row_array();

			$pembayaran_hutang_4 = $this->db->select('SUM(pm.total) as total')
			->from('pmm_pembayaran_penagihan_pembelian pm')
			->where("pm.memo <> 'PPN'")
			->where("pm.tanggal_pembayaran between '$date_4_awal' and '$date_4_akhir'")
			->get()->row_array();
			$hutang_4 = $penerimaan_hutang_4['total'] - $pembayaran_hutang_4['total'];
			?>

			<?php
			//BULAN 5
			$date_5_awal = date('Y-m-d', strtotime('+1 days', strtotime($date_4_akhir)));
			$date_5_akhir = date('Y-m-d', strtotime('-1 days +1 months', strtotime($date_5_awal)));

			$rencana_kerja_5 = $this->db->select('r.*')
			->from('rak r')
			->where("r.tanggal_rencana_kerja between '$date_5_awal' and '$date_5_akhir'")
			->get()->row_array();

			$rencana_kerja_5_biaya_cash_flow = $this->db->select('r.*')
			->from('rencana_cash_flow r')
			->where("r.tanggal_rencana_kerja between '$date_5_awal' and '$date_5_akhir'")
			->get()->row_array();

			$volume_5_produk_a = $rencana_kerja_5['vol_produk_a'];
			$volume_5_produk_b = $rencana_kerja_5['vol_produk_b'];
			$volume_5_produk_c = $rencana_kerja_5['vol_produk_c'];
			$volume_5_produk_d = $rencana_kerja_5['vol_produk_d'];

			$total_5_volume = $volume_5_produk_a + $volume_5_produk_b + $volume_5_produk_c + $volume_5_produk_d;
		
			$nilai_jual_125_5 = $volume_5_produk_a * $rencana_kerja_5['price_a'];
			$nilai_jual_225_5 = $volume_5_produk_b * $rencana_kerja_5['price_b'];
			$nilai_jual_250_5 = $volume_5_produk_c * $rencana_kerja_5['price_c'];
			$nilai_jual_250_18_5 = $volume_5_produk_d * $rencana_kerja_5['price_d'];
			$nilai_jual_all_5 = $nilai_jual_125_5 + $nilai_jual_225_5 + $nilai_jual_250_5 + $nilai_jual_250_18_5;
			
			$total_5_nilai = $nilai_jual_all_5;
			
			$volume_rencana_kerja_5_produk_a = $rencana_kerja_5['vol_produk_a'];
			$volume_rencana_kerja_5_produk_b = $rencana_kerja_5['vol_produk_b'];
			$volume_rencana_kerja_5_produk_c = $rencana_kerja_5['vol_produk_c'];
			$volume_rencana_kerja_5_produk_d = $rencana_kerja_5['vol_produk_d'];

			$total_5_biaya_bahan = $rencana_kerja_5_biaya_cash_flow['biaya_bahan'];
			$total_5_biaya_alat = $rencana_kerja_5_biaya_cash_flow['biaya_alat'];
			$total_5_biaya_bank = $rencana_kerja_5_biaya_cash_flow['biaya_bank'];
			$total_5_biaya_overhead = $rencana_kerja_5_biaya_cash_flow['overhead'];
			$total_5_biaya_termin = $rencana_kerja_5_biaya_cash_flow['termin'];
			$total_5_biaya_persiapan = $rencana_kerja_5_biaya_cash_flow['biaya_persiapan'];

			$pinjaman_dana_5 = $this->db->select('sum(pdb.debit) as total')
			->from('pmm_jurnal_umum pb ')
			->join('pmm_detail_jurnal pdb','pb.id = pdb.jurnal_id','left')
			->join('pmm_coa c','pdb.akun = c.id','left')
			->where("pdb.akun = 232")
			->where("pb.status = 'PAID'")
			->where("pb.tanggal_transaksi between '$date_5_awal' and '$date_5_akhir'")
			->get()->row_array();

			$penerimaan_hutang_5 = $this->db->select('SUM(prm.display_price) as total')
			->from('pmm_receipt_material prm')
			->join('pmm_purchase_order ppo','prm.purchase_order_id = ppo.id','left')
			->where("ppo.status in ('PUBLISH','CLOSED')")
			->where("prm.date_receipt between '$date_5_awal' and '$date_5_akhir'")
			->get()->row_array();

			$pembayaran_hutang_5 = $this->db->select('SUM(pm.total) as total')
			->from('pmm_pembayaran_penagihan_pembelian pm')
			->where("pm.memo <> 'PPN'")
			->where("pm.tanggal_pembayaran between '$date_5_awal' and '$date_5_akhir'")
			->get()->row_array();
			$hutang_5 = $penerimaan_hutang_5['total'] - $pembayaran_hutang_5['total'];
			?>

			<?php
			//BULAN 6
			$date_6_awal = date('Y-m-d', strtotime('+1 days', strtotime($date_5_akhir)));
			$date_6_akhir = date('Y-m-d', strtotime('-1 days +1 months', strtotime($date_6_awal)));

			$rencana_kerja_6 = $this->db->select('r.*')
			->from('rak r')
			->where("r.tanggal_rencana_kerja between '$date_6_awal' and '$date_6_akhir'")
			->get()->row_array();

			$rencana_kerja_6_biaya_cash_flow = $this->db->select('r.*')
			->from('rencana_cash_flow r')
			->where("r.tanggal_rencana_kerja between '$date_6_awal' and '$date_6_akhir'")
			->get()->row_array();

			$volume_6_produk_a = $rencana_kerja_6['vol_produk_a'];
			$volume_6_produk_b = $rencana_kerja_6['vol_produk_b'];
			$volume_6_produk_c = $rencana_kerja_6['vol_produk_c'];
			$volume_6_produk_d = $rencana_kerja_6['vol_produk_d'];

			$total_6_volume = $volume_6_produk_a + $volume_6_produk_b + $volume_6_produk_c + $volume_6_produk_d;
		
			$nilai_jual_125_6 = $volume_6_produk_a * $rencana_kerja_6['price_a'];
			$nilai_jual_225_6 = $volume_6_produk_b * $rencana_kerja_6['price_b'];
			$nilai_jual_250_6 = $volume_6_produk_c * $rencana_kerja_6['price_c'];
			$nilai_jual_250_18_6 = $volume_6_produk_d * $rencana_kerja_6['price_d'];
			$nilai_jual_all_6 = $nilai_jual_125_6 + $nilai_jual_225_6 + $nilai_jual_250_6 + $nilai_jual_250_18_6;
			
			$total_6_nilai = $nilai_jual_all_6;
			
			$volume_rencana_kerja_6_produk_a = $rencana_kerja_6['vol_produk_a'];
			$volume_rencana_kerja_6_produk_b = $rencana_kerja_6['vol_produk_b'];
			$volume_rencana_kerja_6_produk_c = $rencana_kerja_6['vol_produk_c'];
			$volume_rencana_kerja_6_produk_d = $rencana_kerja_6['vol_produk_d'];

			$total_6_biaya_bahan = $rencana_kerja_6_biaya_cash_flow['biaya_bahan'];
			$total_6_biaya_alat = $rencana_kerja_6_biaya_cash_flow['biaya_alat'];
			$total_6_biaya_bank = $rencana_kerja_6_biaya_cash_flow['biaya_bank'];
			$total_6_biaya_overhead = $rencana_kerja_6_biaya_cash_flow['overhead'];
			$total_6_biaya_termin = $rencana_kerja_6_biaya_cash_flow['termin'];
			$total_6_biaya_persiapan = $rencana_kerja_6_biaya_cash_flow['biaya_persiapan'];

			$pinjaman_dana_6 = $this->db->select('sum(pdb.debit) as total')
			->from('pmm_jurnal_umum pb ')
			->join('pmm_detail_jurnal pdb','pb.id = pdb.jurnal_id','left')
			->join('pmm_coa c','pdb.akun = c.id','left')
			->where("pdb.akun = 232")
			->where("pb.status = 'PAID'")
			->where("pb.tanggal_transaksi between '$date_6_awal' and '$date_6_akhir'")
			->get()->row_array();

			$penerimaan_hutang_6 = $this->db->select('SUM(prm.display_price) as total')
			->from('pmm_receipt_material prm')
			->join('pmm_purchase_order ppo','prm.purchase_order_id = ppo.id','left')
			->where("ppo.status in ('PUBLISH','CLOSED')")
			->where("prm.date_receipt between '$date_6_awal' and '$date_6_akhir'")
			->get()->row_array();

			$pembayaran_hutang_6 = $this->db->select('SUM(pm.total) as total')
			->from('pmm_pembayaran_penagihan_pembelian pm')
			->where("pm.memo <> 'PPN'")
			->where("pm.tanggal_pembayaran between '$date_6_awal' and '$date_6_akhir'")
			->get()->row_array();
			$hutang_6 = $penerimaan_hutang_6['total'] - $pembayaran_hutang_6['total'];
			?>

			<tr class="table-active4-csf">
				<th class="text-center" rowspan="2" style="vertical-align:middle; background-color:#55ffff;" width="5%">NO.</th>
				<th class="text-center" rowspan="2" style="vertical-align:middle; background-color:#55ffff;">URAIAN</th>
				<th class="text-center" rowspan="2" style="vertical-align:middle; background-color:#55ffff;">RAP</th>
				<th class="text-center" rowspan="2" style="text-transform:uppercase;vertical-align:middle;; background-color:#8fce00;">REALISASI SD. <?php echo $last_opname = date('F Y', strtotime('0 days', strtotime($last_opname)));?></th>
				<!--<th class="text-center" style="text-transform:uppercase;"><?php echo $date_1_awal = date("F");?></th>
				<th class="text-center" style="text-transform:uppercase;">SD. <?php echo $date_1_awal = date("F");?></th>-->
				<th class="text-center" style="text-transform:uppercase;"><?php echo $date_1_awal = date('F', strtotime('+1 days +1 months', strtotime($last_opname)));?></th>
				<th class="text-center" style="text-transform:uppercase;">SD. <?php echo $date_1_awal = date('F', strtotime('+1 days +1 months', strtotime($last_opname)));?></th>
				<th class="text-center" style="text-transform:uppercase;"><?php echo $date_2_awal = date('F', strtotime('+1 days', strtotime($date_1_akhir)));?></th>
				<th class="text-center" style="text-transform:uppercase;">SD. <?php echo $date_2_awal = date('F', strtotime('+1 days', strtotime($date_1_akhir)));?></th>
				<th class="text-center" style="text-transform:uppercase;"><?php echo $date_3_awal = date('F', strtotime('+1 days', strtotime($date_2_akhir)));?></th>
				<th class="text-center" style="text-transform:uppercase;">SD. <?php echo $date_3_awal = date('F', strtotime('+1 days', strtotime($date_2_akhir)));?></th>
				<th class="text-center" style="text-transform:uppercase;"><?php echo $date_4_awal = date('F', strtotime('+1 days', strtotime($date_3_akhir)));?></th>
				<th class="text-center" style="text-transform:uppercase;">SD. <?php echo $date_4_awal = date('F', strtotime('+1 days', strtotime($date_3_akhir)));?></th>
				<th class="text-center" style="text-transform:uppercase;"><?php echo $date_5_awal = date('F', strtotime('+1 days', strtotime($date_4_akhir)));?></th>
				<th class="text-center" style="text-transform:uppercase;">SD. <?php echo $date_5_awal = date('F', strtotime('+1 days', strtotime($date_4_akhir)));?></th>
				<th class="text-center" style="text-transform:uppercase;"><?php echo $date_6_awal = date('F', strtotime('+1 days', strtotime($date_5_akhir)));?></th>
				<th class="text-center" style="text-transform:uppercase;">SD. <?php echo $date_6_awal = date('F', strtotime('+1 days', strtotime($date_5_akhir)));?></th>
				<th class="text-center" rowspan="2" style="vertical-align:middle; background-color:#55ffff;">SISA</th>
	        </tr>
			<tr class="table-active4-csf">
				<th class="text-center"><?php echo $date_1_awal = date('Y');?></th>
				<th class="text-center"><?php echo $date_1_awal = date('Y');?></th>
				<th class="text-center"><?php echo $date_2_awal = date('Y', strtotime('+1 days', strtotime($date_1_akhir)));?></th>
				<th class="text-center"><?php echo $date_2_awal = date('Y', strtotime('+1 days', strtotime($date_1_akhir)));?></th>
				<th class="text-center"><?php echo $date_3_awal = date('Y', strtotime('+1 days', strtotime($date_2_akhir)));?></th>
				<th class="text-center"><?php echo $date_3_awal = date('Y', strtotime('+1 days', strtotime($date_2_akhir)));?></th>
				<th class="text-center"><?php echo $date_4_awal = date('Y', strtotime('+1 days', strtotime($date_3_akhir)));?></th>
				<th class="text-center"><?php echo $date_4_awal = date('Y', strtotime('+1 days', strtotime($date_3_akhir)));?></th>
				<th class="text-center"><?php echo $date_5_awal = date('Y', strtotime('+1 days', strtotime($date_4_akhir)));?></th>
				<th class="text-center"><?php echo $date_5_awal = date('Y', strtotime('+1 days', strtotime($date_4_akhir)));?></th>
				<th class="text-center"><?php echo $date_6_awal = date('Y', strtotime('+1 days', strtotime($date_5_akhir)));?></th>
				<th class="text-center"><?php echo $date_6_awal = date('Y', strtotime('+1 days', strtotime($date_5_akhir)));?></th>
	        </tr>
			<?php
			//PRESENTASE
			$presentase_now = ($penjualan_now['total'] / $total_rap_nilai_2022) * 100;
			$presentase_1 = ($total_1_nilai / $total_rap_nilai_2022) * 100;
			$presentase_2 = ($total_2_nilai / $total_rap_nilai_2022) * 100;
			$presentase_3 = ($total_3_nilai / $total_rap_nilai_2022) * 100;
			$presentase_4 = ($total_4_nilai / $total_rap_nilai_2022) * 100;
			$presentase_5 = ($total_5_nilai / $total_rap_nilai_2022) * 100;
			$presentase_6 = ($total_6_nilai / $total_rap_nilai_2022) * 100;
			$total_presentase = ($presentase_now + $presentase_1 + $presentase_2 + $presentase_3 + $presentase_4 + $presentase_5 + $presentase_6);

			//AKUMULASI PRESENTASE
			$presentase_akumulasi_1 = $presentase_now + $presentase_1;
			$presentase_akumulasi_2 = $presentase_akumulasi_1 + $presentase_2;
			$presentase_akumulasi_3 = $presentase_akumulasi_2 + $presentase_3;
			$presentase_akumulasi_4 = $presentase_akumulasi_3 + $presentase_4;
			$presentase_akumulasi_5 = $presentase_akumulasi_4 + $presentase_5;
			$presentase_akumulasi_6 = $presentase_akumulasi_5 + $presentase_6;
			
			//PRODUKSI
			$total_produksi = $penjualan_now['total'] + $total_1_nilai + $total_2_nilai + $total_3_nilai + $total_4_nilai + $total_5_nilai + $total_6_nilai;
			
			$akumulasi_penjualan_1 = $penjualan_now['total'] + $total_1_nilai;
			$akumulasi_penjualan_2 = $penjualan_now['total'] + $total_1_nilai + $total_2_nilai;
			$akumulasi_penjualan_3 = $penjualan_now['total'] + $total_1_nilai + $total_2_nilai + $total_3_nilai;
			$akumulasi_penjualan_4 = $penjualan_now['total'] + $total_1_nilai + $total_2_nilai + $total_3_nilai + $total_4_nilai;
			$akumulasi_penjualan_5 = $penjualan_now['total'] + $total_1_nilai + $total_2_nilai + $total_3_nilai + $total_4_nilai + $total_5_nilai;
			$akumulasi_penjualan_6 = $penjualan_now['total'] + $total_1_nilai + $total_2_nilai + $total_3_nilai + $total_4_nilai + $total_5_nilai + $total_6_nilai;
			$akumulasi_penjualan_total = $akumulasi_penjualan_6;
			
			//TERMIN
			$termin_1 = $rencana_kerja_1_biaya_cash_flow['termin'];
			$termin_2 = $rencana_kerja_2_biaya_cash_flow['termin'];
			$termin_3 = $rencana_kerja_3_biaya_cash_flow['termin'];
			$termin_4 = $rencana_kerja_4_biaya_cash_flow['termin'];
			$termin_5 = $rencana_kerja_5_biaya_cash_flow['termin'];
			$termin_6 = $rencana_kerja_6_biaya_cash_flow['termin'];
			$jumlah_termin = $termin_now['total'] + $termin_1 + $termin_2 + $termin_3 + $termin_4 + $termin_5 + $termin_6;

			$akumulasi_termin_1 = $termin_now['total'] + $termin_1;
			$akumulasi_termin_2 = $akumulasi_termin_1 + $termin_2;
			$akumulasi_termin_3 = $akumulasi_termin_2 + $termin_3;
			$akumulasi_termin_4 = $akumulasi_termin_3 + $termin_4;
			$akumulasi_termin_5 = $akumulasi_termin_4 + $termin_5;
			$akumulasi_termin_6 = $akumulasi_termin_5 + $termin_6;

			//PPN KELUARAN
			$ppn_keluaran_rap = ($total_rap_nilai_2022 * 11) / 100;
			$ppn_keluaran_1 = 0;
			$ppn_keluaran_2 = 0;
			$ppn_keluaran_3 = 0;
			$ppn_keluaran_4 = 0;
			$ppn_keluaran_5 = 0;
			$ppn_keluaran_6 = 0;
			$jumlah_ppn_keluaran = $ppn_keluar_now['total'] +$ppn_keluaran_1 + $ppn_keluaran_2 + $ppn_keluaran_3 + $ppn_keluaran_4 + $ppn_keluaran_5 + $ppn_keluaran_6;

			//AKUMULASI PPN KELUARAN
			$akumulasi_ppn_keluaran_1 = $ppn_keluar_now['total'] + $ppn_keluaran_1;
			$akumulasi_ppn_keluaran_2 = $akumulasi_ppn_keluaran_1 + $ppn_keluaran_2;
			$akumulasi_ppn_keluaran_3 = $akumulasi_ppn_keluaran_2 + $ppn_keluaran_3;
			$akumulasi_ppn_keluaran_4 = $akumulasi_ppn_keluaran_3 + $ppn_keluaran_4;
			$akumulasi_ppn_keluaran_5 = $akumulasi_ppn_keluaran_4 + $ppn_keluaran_5;
			$akumulasi_ppn_keluaran_6 = $akumulasi_ppn_keluaran_5 + $ppn_keluaran_6;
			
			//JUMLAH PENERIMAAN
			$jumlah_penerimaan_now = $termin_now['total'] + $ppn_keluar_now['total'];
			$jumlah_penerimaan_1 = $rencana_kerja_1_biaya_cash_flow['termin'];
			$jumlah_penerimaan_2 = $rencana_kerja_2_biaya_cash_flow['termin'];
			$jumlah_penerimaan_3 = $rencana_kerja_3_biaya_cash_flow['termin'];
			$jumlah_penerimaan_4 = $rencana_kerja_4_biaya_cash_flow['termin'];
			$jumlah_penerimaan_5 = $rencana_kerja_5_biaya_cash_flow['termin'];
			$jumlah_penerimaan_6 = $rencana_kerja_6_biaya_cash_flow['termin'];
			$jumlah_penerimaan_total = $jumlah_penerimaan_now + $jumlah_penerimaan_1 + $jumlah_penerimaan_2 + $jumlah_penerimaan_3 + $jumlah_penerimaan_4 + $jumlah_penerimaan_5 + $jumlah_penerimaan_6;
			
			//AKUMULASI PENERIMAAN
			$akumulasi_penerimaan_now =  $jumlah_penerimaan_now;
			$akumulasi_penerimaan_1 = $akumulasi_penerimaan_now + $jumlah_penerimaan_1;
			$akumulasi_penerimaan_2 = $akumulasi_penerimaan_now + $jumlah_penerimaan_1 + $jumlah_penerimaan_2;
			$akumulasi_penerimaan_3 = $akumulasi_penerimaan_now + $jumlah_penerimaan_1 + $jumlah_penerimaan_2 + $jumlah_penerimaan_3;
			$akumulasi_penerimaan_4 = $akumulasi_penerimaan_now + $jumlah_penerimaan_1 + $jumlah_penerimaan_2 + $jumlah_penerimaan_3 + $jumlah_penerimaan_4;
			$akumulasi_penerimaan_5 = $akumulasi_penerimaan_now + $jumlah_penerimaan_1 + $jumlah_penerimaan_2 + $jumlah_penerimaan_3 + $jumlah_penerimaan_4 + $jumlah_penerimaan_5;
			
			//BIAYA BAHAN
			$biaya_bahan_1 = $total_1_biaya_bahan;
			$biaya_bahan_2 = $total_2_biaya_bahan;
			$biaya_bahan_3 = $total_3_biaya_bahan;
			$biaya_bahan_4 = $total_4_biaya_bahan;
			$biaya_bahan_5 = $total_5_biaya_bahan;
			$jumlah_biaya_bahan = $pembayaran_bahan_now  + $biaya_bahan_1 + $biaya_bahan_2 + $biaya_bahan_3 + $biaya_bahan_4 + $biaya_bahan_5;

			$akumulasi_biaya_bahan_1 = $pembayaran_bahan_now + $biaya_bahan_1;
			$akumulasi_biaya_bahan_2 = $akumulasi_biaya_bahan_1 + $biaya_bahan_2;
			$akumulasi_biaya_bahan_3 = $akumulasi_biaya_bahan_2 + $biaya_bahan_3;
			$akumulasi_biaya_bahan_4 = $akumulasi_biaya_bahan_3 + $biaya_bahan_4;
			$akumulasi_biaya_bahan_5 = $akumulasi_biaya_bahan_4 + $biaya_bahan_5;
			
			//BIAYA ALAT
			$biaya_alat_1 = $total_1_biaya_alat;
			$biaya_alat_2 = $total_2_biaya_alat;
			$biaya_alat_3 = $total_3_biaya_alat;
			$biaya_alat_4 = $total_4_biaya_alat;
			$biaya_alat_5 = $total_5_biaya_alat;
			$biaya_alat_6 = $total_6_biaya_alat;
			$jumlah_biaya_alat = $alat_now + $biaya_alat_1 + $biaya_alat_2 + $biaya_alat_3 + $biaya_alat_4 + $biaya_alat_5 + $biaya_alat_6;
		
			//AKUMULASI BIAYA ALAT
			$akumulasi_biaya_alat_1 = $alat_now + $biaya_alat_1;
			$akumulasi_biaya_alat_2 = $akumulasi_biaya_alat_1 + $biaya_alat_2;
			$akumulasi_biaya_alat_3 = $akumulasi_biaya_alat_2 + $biaya_alat_3;
			$akumulasi_biaya_alat_4 = $akumulasi_biaya_alat_3 + $biaya_alat_4;
			$akumulasi_biaya_alat_5 = $akumulasi_biaya_alat_4 + $biaya_alat_5;
			$akumulasi_biaya_alat_6 = $akumulasi_biaya_alat_5 + $biaya_alat_6;
			
			//BIAYA BANK
			$biaya_bank_1 = $total_1_biaya_bank;
			$biaya_bank_2 = $total_2_biaya_bank;
			$biaya_bank_3 = $total_3_biaya_bank;
			$biaya_bank_4 = $total_4_biaya_bank;
			$biaya_bank_5 = $total_5_biaya_bank;
			$biaya_bank_6 = $total_6_biaya_bank;
			$jumlah_biaya_bank = $diskonto_now + $biaya_bank_1 + $biaya_bank_2 + $biaya_bank_3 + $biaya_bank_4 + $biaya_bank_5 + $biaya_bank_6;

			//AKUMULASI BIAYA BANK
			$akumulasi_biaya_bank_1 = $diskonto_now + $biaya_bank_1;
			$akumulasi_biaya_bank_2 = $akumulasi_biaya_bank_1 + $biaya_bank_2;
			$akumulasi_biaya_bank_3 = $akumulasi_biaya_bank_2 + $biaya_bank_3;
			$akumulasi_biaya_bank_4 = $akumulasi_biaya_bank_3 + $biaya_bank_4;
			$akumulasi_biaya_bank_5 = $akumulasi_biaya_bank_4 + $biaya_bank_5;
			$akumulasi_biaya_bank_6 = $akumulasi_biaya_bank_5 + $biaya_bank_6;
			
			//BUA
			$biaya_overhead_1 = $total_1_biaya_overhead;
			$biaya_overhead_2 = $total_2_biaya_overhead;
			$biaya_overhead_3 = $total_3_biaya_overhead;
			$biaya_overhead_4 = $total_4_biaya_overhead;
			$biaya_overhead_5 = $total_5_biaya_overhead;
			$biaya_overhead_6 = $total_6_biaya_overhead;
			$jumlah_biaya_overhead = $overhead_now + $biaya_overhead_1 + $biaya_overhead_2 + $biaya_overhead_3 + $biaya_overhead_4 + $biaya_overhead_5 + $biaya_overhead_6;

			//AKUMULASI BUA
			$akumulasi_biaya_overhead_1 = $overhead_now + $biaya_overhead_1;
			$akumulasi_biaya_overhead_2 = $akumulasi_overhead_1 + $biaya_overhead_2;
			$akumulasi_biaya_overhead_3 = $akumulasi_overhead_2 + $biaya_overhead_3;
			$akumulasi_biaya_overhead_4 = $akumulasi_overhead_3 + $biaya_overhead_4;
			$akumulasi_biaya_overhead_5 = $akumulasi_overhead_4 + $biaya_overhead_5;
			$akumulasi_biaya_overhead_6 = $akumulasi_overhead_5 + $biaya_overhead_6;

			//PPN KELUARAN
			$pajak_masukan = (($total_rap_2022_biaya_bahan + $total_rap_2022_biaya_alat) *11) / 100;
			$ppn_keluaran_1 = 0;
			$ppn_keluaran_2 = 0;
			$ppn_keluaran_3 = 0;
			$ppn_keluaran_4 = 0;
			$ppn_keluaran_5 = 0;
			$ppn_keluaran_6 = 0;
			$jumlah_ppn_keluaran = $ppn_masuk_now['total'] + $ppn_keluaran_1 + $ppn_keluaran_2 + $ppn_keluaran_3 + $ppn_keluaran_4 + $ppn_keluaran_5 + $ppn_keluaran_6;

			//AKUMULASI PPN KELUARAN
			$akumulasi_ppn_keluaran_1 = $ppn_masuk_now['total'] + $ppn_keluaran_1;
			$akumulasi_ppn_keluaran_2 = $akumulasi_ppn_keluaran_1 + $ppn_keluaran_2;
			$akumulasi_ppn_keluaran_3 = $akumulasi_ppn_keluaran_2 + $ppn_keluaran_3;
			$akumulasi_ppn_keluaran_4 = $akumulasi_ppn_keluaran_3 + $ppn_keluaran_5;
			$akumulasi_ppn_keluaran_5 = $akumulasi_ppn_keluaran_4 + $ppn_keluaran_5;
			$akumulasi_ppn_keluaran_6 = $akumulasi_ppn_keluaran_5 + $ppn_keluaran_6;

			//BIAYA PERSIAPAN
			$biaya_persiapan_1 = $total_1_biaya_persiapan;
			$biaya_persiapan_2 = $total_2_biaya_persiapan;
			$biaya_persiapan_3 = $total_3_biaya_persiapan;
			$biaya_persiapan_4 = $total_4_biaya_persiapan;
			$biaya_persiapan_5 = $total_5_biaya_persiapan;
			$biaya_persiapan_6 = $total_6_biaya_persiapan;
			$jumlah_biaya_persiapan = $biaya_persiapan_now['total'] + $biaya_persiapan_1 + $biaya_persiapan_2 + $biaya_persiapan_3 + $biaya_persiapan_4 + $biaya_persiapan_5 + $biaya_persiapan_6;

			//BIAYA PERSIAPAN
			$akumulasi_biaya_persiapan_1 = $biaya_persiapan_now['total'] + $ppn_keluaran_1;
			$akumulasi_biaya_persiapan_2 = $akumulasi_biaya_persiapan_1 + $biaya_persiapan_2;
			$akumulasi_biaya_persiapan_3 = $akumulasi_biaya_persiapan_2 + $biaya_persiapan_3;
			$akumulasi_biaya_persiapan_4 = $akumulasi_biaya_persiapan_3 + $biaya_persiapan_4;
			$akumulasi_biaya_persiapan_5 = $akumulasi_biaya_persiapan_4 + $biaya_persiapan_5;
			$akumulasi_biaya_persiapan_6 = $akumulasi_biaya_persiapan_5 + $biaya_persiapan_6;
			$sisa_biaya_persiapan = $total_rap_2022_biaya_persiapan - $jumlah_biaya_persiapan;

			//JUMLAH PENGELUARAN
			$jumlah_pengeluaran = $total_rap_2022_biaya_bahan + $total_rap_2022_biaya_alat + $total_rap_2022_biaya_bank + $total_rap_2022_biaya_overhead + $pajak_masukan + $total_rap_2022_biaya_persiapan;
			$jumlah_pengeluaran_now = $pembayaran_bahan_now + $alat_now + $diskonto_now + $overhead_now + $ppn_masuk_now['total']+ $biaya_persiapan_now['total'];
			$jumlah_pengeluaran_1 = $biaya_bahan_1 + $biaya_alat_1 + $biaya_bank_1 + $biaya_overhead_1 + $ppn_keluaran_1 + $ppn_persiapan_1;
			$jumlah_pengeluaran_2 = $biaya_bahan_2 + $biaya_alat_2 + $biaya_bank_2 + $biaya_overhead_2 + $ppn_keluaran_2 + $ppn_persiapan_2;
			$jumlah_pengeluaran_3 = $biaya_bahan_3 + $biaya_alat_3 + $biaya_bank_3 + $biaya_overhead_3 + $ppn_keluaran_3 + $ppn_persiapan_3;
			$jumlah_pengeluaran_4 = $biaya_bahan_4 + $biaya_alat_4 + $biaya_bank_4 + $biaya_overhead_4 + $ppn_keluaran_4 + $ppn_persiapan_4;
			$jumlah_pengeluaran_5 = $biaya_bahan_5 + $biaya_alat_5 + $biaya_bank_5 + $biaya_overhead_5 + $ppn_keluaran_5 + $ppn_persiapan_5;
			$jumlah_pengeluaran_6 = $biaya_bahan_6 + $biaya_alat_6 + $biaya_bank_6 + $biaya_overhead_6 + $ppn_keluaran_6 + $ppn_persiapan_6;
			$total_pengeluaran = $jumlah_pengeluaran_now + $jumlah_pengeluaran_1 + $jumlah_pengeluaran_2 + $jumlah_pengeluaran_3 + $jumlah_pengeluaran_4 + $jumlah_pengeluaran_5 + $jumlah_pengeluaran_6;
			
			//AKUMULASI PENGELUARAN
			$akumulasi_pengeluaran_1 = $jumlah_pengeluaran_now + $jumlah_pengeluaran_1;
			$akumulasi_pengeluaran_2 = $akumulasi_pengeluaran_1 + $jumlah_pengeluaran_2;
			$akumulasi_pengeluaran_3 = $akumulasi_pengeluaran_2 + $jumlah_pengeluaran_3;
			$akumulasi_pengeluaran_4 = $akumulasi_pengeluaran_3 + $jumlah_pengeluaran_4;
			$akumulasi_pengeluaran_5 = $akumulasi_pengeluaran_4 + $jumlah_pengeluaran_5;
			$akumulasi_pengeluaran_6 = $akumulasi_pengeluaran_5 + $jumlah_pengeluaran_6;

			//PAJAK KELUARAN
			$pajak_keluaran_now =  $ppn_keluaran_now['total'];
			$pajak_keluaran_1 = 0;
			$pajak_keluaran_2 = 0;
			$pajak_keluaran_3 = 0;
			$pajak_keluaran_4 = 0;
			$pajak_keluaran_5 = 0;
			$pajak_keluaran_6 = 0;
			$total_pajak_keluaran = $pajak_keluaran_now + $pajak_keluaran_1 + $pajak_keluaran_2 + $pajak_keluaran_3 + $pajak_keluaran_4 + $pajak_keluaran_5 + $pajak_keluaran_6;

			//AKUMULASI PAJAK KELUARAN
			$akumulasi_pajak_keluaran_1 = $pajak_keluaran_now + $pajak_keluaran_1;
			$akumulasi_pajak_keluaran_2 = $akumulasi_pajak_keluaran_1 + $pajak_keluaran_2;
			$akumulasi_pajak_keluaran_3 = $akumulasi_pajak_keluaran_2 + $pajak_keluaran_3;
			$akumulasi_pajak_keluaran_4 = $akumulasi_pajak_keluaran_3 + $pajak_keluaran_4;
			$akumulasi_pajak_keluaran_5 = $akumulasi_pajak_keluaran_4 + $pajak_keluaran_5;
			$akumulasi_pajak_keluaran_6 = $akumulasi_pajak_keluaran_5 + $pajak_keluaran_6;

			//PAJAK MASUKAN
			$pajak_masukan_now = $ppn_masukan_now['total'];
			$pajak_masukan_1 = 0;
			$pajak_masukan_2 = 0;
			$pajak_masukan_3 = 0;
			$pajak_masukan_4 = 0;
			$pajak_masukan_5 = 0;
			$pajak_masukan_6 = 0;
			$total_pajak_masukan = $pajak_masukan_now + $pajak_masukan_1 + $pajak_masukan_2 + $pajak_masukan_3 + $pajak_masukan_4 + $pajak_masukan_5 + $pajak_masukan_6;

			//AKUMULASI PAJAK MASUKAN
			$akumulasi_pajak_masukan_1 = $pajak_masukan_now + $pajak_masukan_1;
			$akumulasi_pajak_masukan_2 = $akumulasi_pajak_masukan_1 + $pajak_masukan_2;
			$akumulasi_pajak_masukan_3 = $akumulasi_pajak_masukan_2 + $pajak_masukan_3;
			$akumulasi_pajak_masukan_4 = $akumulasi_pajak_masukan_3 + $pajak_masukan_4;
			$akumulasi_pajak_masukan_5 = $akumulasi_pajak_masukan_4 + $pajak_masukan_5;
			$akumulasi_pajak_masukan_6 = $akumulasi_pajak_masukan_5 + $pajak_masukan_6;
			
			//PENERIMAAN PINJAMAN
			$penerimaan_pinjaman_now = 1300000000;
			$penerimaan_pinjaman_1 = 0;
			$penerimaan_pinjaman_2 = 0;
			$penerimaan_pinjaman_3 = 0;
			$penerimaan_pinjaman_4 = 0;
			$penerimaan_pinjaman_5 = 0;
			$penerimaan_pinjaman_6 = 0;
			$total_penerimaan_pinjaman = $penerimaan_pinjaman_now + $penerimaan_pinjaman_1 + $penerimaan_pinjaman_2 + $penerimaan_pinjaman_3 + $penerimaan_pinjaman_4 + $penerimaan_pinjaman_5 + $penerimaan_pinjaman_6;

			//AKUMULASI PENERIMAAN PINJAMAN
			$akumulasi_penerimaan_pinjaman_1 = $penerimaan_pinjaman_now + $penerimaan_pinjaman_1;
			$akumulasi_penerimaan_pinjaman_2 = $akumulasi_penerimaan_pinjaman_1 + $penerimaan_pinjaman_2;
			$akumulasi_penerimaan_pinjaman_3 = $akumulasi_penerimaan_pinjaman_2 + $penerimaan_pinjaman_3;
			$akumulasi_penerimaan_pinjaman_4 = $akumulasi_penerimaan_pinjaman_3 + $penerimaan_pinjaman_4;
			$akumulasi_penerimaan_pinjaman_5 = $akumulasi_penerimaan_pinjaman_4 + $penerimaan_pinjaman_5;
			$akumulasi_penerimaan_pinjaman_6 = $akumulasi_penerimaan_pinjaman_5 + $penerimaan_pinjaman_6;

			//PENGEMBALIAN PINJAMAN
			$pengembalian_pinjaman_now = 895000000;
			$pengembalian_pinjaman_1 = 0;
			$pengembalian_pinjaman_2 = 0;
			$pengembalian_pinjaman_3 = 0;
			$pengembalian_pinjaman_4 = 0;
			$pengembalian_pinjaman_5 = 0;
			$pengembalian_pinjaman_6 = 0;
			$total_pengembalian_pinjaman = $pengembalian_pinjaman_now + $pengembalian_pinjaman_1 + $pengembalian_pinjaman_2 + $pengembalian_pinjaman_3 + $pengembalian_pinjaman_4 + $pengembalian_pinjaman_5 + $pengembalian_pinjaman_6;

			//AKUMULASI PENGEMBALIAN PINJAMAN
			$akumulasi_pengembalian_pinjaman_1 = $pengembalian_pinjaman_now + $pengembalian_pinjaman_1;
			$akumulasi_pengembalian_pinjaman_2 = $akumulasi_pengembalian_pinjaman_1 + $pengembalian_pinjaman_2;
			$akumulasi_pengembalian_pinjaman_3 = $akumulasi_pengembalian_pinjaman_2 + $pengembalian_pinjaman_3;
			$akumulasi_pengembalian_pinjaman_4 = $akumulasi_pengembalian_pinjaman_3 + $pengembalian_pinjaman_4;
			$akumulasi_pengembalian_pinjaman_5 = $akumulasi_pengembalian_pinjaman_4 + $pengembalian_pinjaman_5;
			$akumulasi_pengembalian_pinjaman_6 = $akumulasi_pengembalian_pinjaman_5 + $pengembalian_pinjaman_6;

			//PINJAMAN DANA
			$pinjaman_dana_now = $pinjaman_dana_now['total'];
			$pinjaman_dana_1 = $pinjaman_dana_1['total'];
			$pinjaman_dana_2 = $pinjaman_dana_2['total'];
			$pinjaman_dana_3 = $pinjaman_dana_3['total'];
			$pinjaman_dana_4 = $pinjaman_dana_4['total'];
			$pinjaman_dana_5 = $pinjaman_dana_5['total'];
			$pinjaman_dana_6 = $pinjaman_dana_6['total'];
			$total_pinjaman_dana = $pinjaman_dana_now + $pinjaman_dana_1 + $pinjaman_dana_2 + $pinjaman_dana_3 + $pinjaman_dana_4 + $pinjaman_dana_5 + $pinjaman_dana_6;

			//AKUMULASI PINJAMAN DANA
			$akumulasi_pinjaman_dana_1 = $pinjaman_dana_now + $pinjaman_dana_1;
			$akumulasi_pinjaman_dana_2 = $akumulasi_pinjaman_dana_1 + $pinjaman_dana_2;
			$akumulasi_pinjaman_dana_3 = $akumulasi_pinjaman_dana_2 + $pinjaman_dana_3;
			$akumulasi_pinjaman_dana_4 = $akumulasi_pinjaman_dana_3 + $pinjaman_dana_4;
			$akumulasi_pinjaman_dana_5 = $akumulasi_pinjaman_dana_4 + $pinjaman_dana_5;
			$akumulasi_pinjaman_dana_6 = $akumulasi_pinjaman_dana_5 + $pinjaman_dana_6;

			//PIUTANG
			$piutang_now = $piutang_now;
			$piutang_1 = 0;
			$piutang_2 = 0;
			$piutang_3 = 0;
			$piutang_4 = 0;
			$piutang_5 = 0;
			$piutang_6 = 0;
			$total_piutang = $piutang_now + $piutang_1 + $piutang_2 + $piutang_3 + $piutang_4 + $piutang_5 + $piutang_6;

			//AKUMULASI PIUTANG
			$akumulasi_piutang_1 = $akumulasi_penjualan_1 - $akumulasi_termin_1;
			$akumulasi_piutang_2 = $akumulasi_penjualan_2 - $akumulasi_termin_2;
			$akumulasi_piutang_3 = $akumulasi_penjualan_3 - $akumulasi_termin_3;
			$akumulasi_piutang_4 = $akumulasi_penjualan_4 - $akumulasi_termin_4;
			$akumulasi_piutang_5 = $akumulasi_penjualan_5 - $akumulasi_termin_5;
			$akumulasi_piutang_6 = $akumulasi_penjualan_6 - $akumulasi_termin_6;

			//HUTANG
			$hutang_now = $hutang_now;
			$hutang_1 = 0;
			$hutang_2 = 0;
			$hutang_3 = 0;
			$hutang_4 = 0;
			$hutang_5 = 0;
			$hutang_6 = 0;
			$total_hutang = $hutang_now + $hutang_1 + $hutang_2 + $hutang_3 + $hutang_4 + $hutang_5 + $hutang_6;

			//AKUMULASI HUTANG
			$akumulasi_hutang_1 = $akumulasi_termin_1 - ($biaya_bahan_1 + $akumulasi_biaya_alat_1);
			$akumulasi_hutang_2 = $akumulasi_termin_2 - ($biaya_bahan_2 + $akumulasi_biaya_alat_2);
			$akumulasi_hutang_3 = $akumulasi_termin_3 - ($biaya_bahan_3 + $akumulasi_biaya_alat_3);
			$akumulasi_hutang_4 = $akumulasi_termin_4 - ($biaya_bahan_4 + $akumulasi_biaya_alat_4);
			$akumulasi_hutang_5 = $akumulasi_termin_5 - ($biaya_bahan_5 + $akumulasi_biaya_alat_5);
			$akumulasi_hutang_6 = $akumulasi_termin_6 - ($biaya_bahan_6 + $akumulasi_biaya_alat_6);

			//POSISI DANA
			$posisi_dana_rap = ($total_rap_nilai_2022 + $ppn_keluaran_rap) - $jumlah_pengeluaran - ($total_rap_2022_pajak_keluaran - $total_rap_2022_pajak_masukan) - ($total_rap_2022_penerimaan_pinjaman - $total_rap_2022_pengembalian_pinjaman);
			$posisi_dana_now = ($jumlah_penerimaan_now - $jumlah_pengeluaran_now - ($pajak_keluaran_now - $pajak_masukan_now) - $pengembalian_pinjaman_now - $pinjaman_dana_now - $hutang_now + ($mos_now + $piutang_now));
			$posisi_dana_1 = $jumlah_penerimaan_1 - $jumlah_pengeluaran_1 - ($pajak_keluaran_1 - $pajak_masukan_1) - ($penerimaan_pinjaman_1 - $pengembalian_pinjaman_1) - ($pinjaman_dana_1 - $pengembalian_pinjaman_dana_1);
			$posisi_dana_2 = $jumlah_penerimaan_2 - $jumlah_pengeluaran_2 - ($pajak_keluaran_2 - $pajak_masukan_2) - ($penerimaan_pinjaman_2 - $pengembalian_pinjaman_2) - ($pinjaman_dana_2 - $pengembalian_pinjaman_dana_2);
			$posisi_dana_3 = $jumlah_penerimaan_3 - $jumlah_pengeluaran_3 - ($pajak_keluaran_3 - $pajak_masukan_3) - ($penerimaan_pinjaman_3 - $pengembalian_pinjaman_3) - ($pinjaman_dana_3 - $pengembalian_pinjaman_dana_3);
			$posisi_dana_4 = $jumlah_penerimaan_4 - $jumlah_pengeluaran_4 - ($pajak_keluaran_4 - $pajak_masukan_4) - ($penerimaan_pinjaman_4 - $pengembalian_pinjaman_4) - ($pinjaman_dana_4 - $pengembalian_pinjaman_dana_4);
			$posisi_dana_5 = $jumlah_penerimaan_5 - $jumlah_pengeluaran_5 - ($pajak_keluaran_5 - $pajak_masukan_5) - ($penerimaan_pinjaman_5 - $pengembalian_pinjaman_5) - ($pinjaman_dana_5 - $pengembalian_pinjaman_dana_5);
			$posisi_dana_6 = $jumlah_penerimaan_6 - $jumlah_pengeluaran_6 - ($pajak_keluaran_6 - $pajak_masukan_6) - ($penerimaan_pinjaman_6 - $pengembalian_pinjaman_6) - ($pinjaman_dana_6 - $pengembalian_pinjaman_dana_6);
			$total_posisi_dana = $posisi_dana_now + $posisi_dana_1 + $posisi_dana_2 + $posisi_dana_3 + $posisi_dana_4 + $posisi_dana_5 + $posisi_dana_6;
			
			//AKUMULASI POSISI DANA
			$akumulasi_posisi_dana_1 = $akumulasi_penerimaan_pinjaman_1 + ($akumulasi_termin_1 + $akumulasi_ppn_keluaran_1) - $akumulasi_pengeluaran_1 - $akumulasi_pengembalian_pinjaman_1 - ($akumulasi_penerimaan_pinjaman_1 - $akumulasi_pengembalian_pinjaman_1) - ($akumulasi_pinjaman_dana_1 - $akumulasi_pengembalian_pinjaman_dana_1) + $akumulasi_piutang_1 - $akumulasi_hutang_1;
			$akumulasi_posisi_dana_2 = $akumulasi_penerimaan_pinjaman_2 + ($akumulasi_termin_2 + $akumulasi_ppn_keluaran_2) - $akumulasi_pengeluaran_2 - $akumulasi_pengembalian_pinjaman_2 - ($akumulasi_penerimaan_pinjaman_2 - $akumulasi_pengembalian_pinjaman_2) - ($akumulasi_pinjaman_dana_2 - $akumulasi_pengembalian_pinjaman_dana_2) + $akumulasi_piutang_2 - $akumulasi_hutang_2;
			$akumulasi_posisi_dana_3 = $akumulasi_penerimaan_pinjaman_3 + ($akumulasi_termin_3 + $akumulasi_ppn_keluaran_3) - $akumulasi_pengeluaran_3 - $akumulasi_pengembalian_pinjaman_3 - ($akumulasi_penerimaan_pinjaman_3 - $akumulasi_pengembalian_pinjaman_3) - ($akumulasi_pinjaman_dana_3 - $akumulasi_pengembalian_pinjaman_dana_3) + $akumulasi_piutang_3 - $akumulasi_hutang_3;
			$akumulasi_posisi_dana_4 = $akumulasi_penerimaan_pinjaman_4 + ($akumulasi_termin_4 + $akumulasi_ppn_keluaran_4) - $akumulasi_pengeluaran_4 - $akumulasi_pengembalian_pinjaman_4 - ($akumulasi_penerimaan_pinjaman_4 - $akumulasi_pengembalian_pinjaman_4) - ($akumulasi_pinjaman_dana_4 - $akumulasi_pengembalian_pinjaman_dana_4) + $akumulasi_piutang_4 - $akumulasi_hutang_4;
			$akumulasi_posisi_dana_5 = $akumulasi_penerimaan_pinjaman_5 + ($akumulasi_termin_5 + $akumulasi_ppn_keluaran_5) - $akumulasi_pengeluaran_5 - $akumulasi_pengembalian_pinjaman_5 - ($akumulasi_penerimaan_pinjaman_5 - $akumulasi_pengembalian_pinjaman_5) - ($akumulasi_pinjaman_dana_5 - $akumulasi_pengembalian_pinjaman_dana_5) + $akumulasi_piutang_5 - $akumulasi_hutang_5;
			$akumulasi_posisi_dana_6 = $akumulasi_penerimaan_pinjaman_6 + ($akumulasi_termin_6 + $akumulasi_ppn_keluaran_6) - $akumulasi_pengeluaran_6 - $akumulasi_pengembalian_pinjaman_6 - ($akumulasi_penerimaan_pinjaman_6 - $akumulasi_pengembalian_pinjaman_6) - ($akumulasi_pinjaman_dana_6 - $akumulasi_pengembalian_pinjaman_dana_6) + $akumulasi_piutang_6 - $akumulasi_hutang_6;
			?>
			<tr class="table-active3-csf">
				<th class="text-center" rowspan="3" style="vertical-align:middle">1</th>
				<th class="text-left" colspan="16"><u>PRODUKSI (EXCL. PPN)</u></th>
			</tr>
			<tr class="table-active3-csf">
				<th class="text-left"><u>AKUMULASI %</u></th>
				<th class="text-right">100%</th>
				<th class="text-right"><?php echo number_format($presentase_now,0,',','.');?>%</th>
				<th class="text-right"><?php echo number_format($presentase_1,0,',','.');?>%</th>
				<th class="text-right"><?php echo number_format($presentase_akumulasi_1,0,',','.');?>%</th>
				<th class="text-right"><?php echo number_format($presentase_2,0,',','.');?>%</th>
				<th class="text-right"><?php echo number_format($presentase_akumulasi_2,0,',','.');?>%</th>
				<th class="text-right"><?php echo number_format($presentase_3,0,',','.');?>%</th>
				<th class="text-right"><?php echo number_format($presentase_akumulasi_3,0,',','.');?>%</th>
				<th class="text-right"><?php echo number_format($presentase_4,0,',','.');?>%</th>
				<th class="text-right"><?php echo number_format($presentase_akumulasi_4,0,',','.');?>%</th>
				<th class="text-right"><?php echo number_format($presentase_5,0,',','.');?>%</th>
				<th class="text-right"><?php echo number_format($presentase_akumulasi_5,0,',','.');?>%</th>
				<th class="text-right"><?php echo number_format($presentase_6,0,',','.');?>%</th>
				<th class="text-right"><?php echo number_format($presentase_akumulasi_6,0,',','.');?>%</th>
				<th class="text-right"><?php echo number_format(100 - $total_presentase,0,',','.');?>%</th>
			</tr>
			<tr class="table-active2-csf">
				<th class="text-left">&nbsp;&nbsp;1. Produksi / Penjualan</th>
				<th class="text-right"><?php echo number_format($total_rap_nilai_2022,0,',','.');?></th>
				<th class="text-right"><?php echo number_format($penjualan_now['total'],0,',','.');?></th>
				<th class="text-right"><?php echo number_format($total_1_nilai,0,',','.');?></th>
				<th class="text-right"><?php echo number_format($akumulasi_penjualan_1,0,',','.');?></th>
				<th class="text-right"><?php echo number_format($total_2_nilai,0,',','.');?></th>
				<th class="text-right"><?php echo number_format($akumulasi_penjualan_2,0,',','.');?></th>
				<th class="text-right"><?php echo number_format($total_3_nilai,0,',','.');?></th>
				<th class="text-right"><?php echo number_format($akumulasi_penjualan_3,0,',','.');?></th>
				<th class="text-right"><?php echo number_format($total_4_nilai,0,',','.');?></th>
				<th class="text-right"><?php echo number_format($akumulasi_penjualan_4,0,',','.');?></th>
				<th class="text-right"><?php echo number_format($total_5_nilai,0,',','.');?></th>
				<th class="text-right"><?php echo number_format($akumulasi_penjualan_5,0,',','.');?></th>
				<th class="text-right"><?php echo number_format($total_6_nilai,0,',','.');?></th>
				<th class="text-right"><?php echo number_format($akumulasi_penjualan_6,0,',','.');?></th>
				<th class="text-right"><?php echo number_format($total_rap_nilai_2022 - $total_produksi,0,',','.');?></th>
			</tr>
			<tr class="table-active3-csf">
				<th class="text-center" rowspan="4" style="vertical-align:middle">2</th>
				<th class="text-left" colspan="16"><u>PENERIMAAN (EXCL. PPN)</u></th>
			</tr>
			<tr class="table-active3-csf">
				<th class="text-left">&nbsp;&nbsp;2.1 Tagihan (Realisasi)</th>
				<th class="text-right"><?php echo number_format($total_rap_nilai_2022,0,',','.');?></th>
				<th class="text-right"><?php echo number_format($termin_now['total'],0,',','.');?></th>
				<th class="text-right"><?php echo number_format($termin_1,0,',','.');?></th>
				<th class="text-right"><?php echo number_format($akumulasi_termin_1,0,',','.');?></th>
				<th class="text-right"><?php echo number_format($termin_2,0,',','.');?></th>
				<th class="text-right"><?php echo number_format($akumulasi_termin_2,0,',','.');?></th>
				<th class="text-right"><?php echo number_format($termin_3,0,',','.');?></th>
				<th class="text-right"><?php echo number_format($akumulasi_termin_3,0,',','.');?></th>
				<th class="text-right"><?php echo number_format($termin_4,0,',','.');?></th>
				<th class="text-right"><?php echo number_format($akumulasi_termin_4,0,',','.');?></th>
				<th class="text-right"><?php echo number_format($termin_5,0,',','.');?></th>
				<th class="text-right"><?php echo number_format($akumulasi_termin_5,0,',','.');?></th>
				<th class="text-right"><?php echo number_format($termin_6,0,',','.');?></th>
				<th class="text-right"><?php echo number_format($akumulasi_termin_6,0,',','.');?></th>
				<th class="text-right"><?php echo number_format($total_rap_nilai_2022 - $jumlah_termin,0,',','.');?></th>
			</tr>
			<tr class="table-active3-csf">
				<th class="text-left">&nbsp;&nbsp;2.2 PPN (Keluaran)</th>
				<th class="text-right"><?php echo number_format($ppn_keluaran_rap,0,',','.');?></th>
				<th class="text-right"><?php echo number_format($ppn_keluar_now['total'],0,',','.');?></th>
				<th class="text-right"><?php echo number_format($ppn_keluaran_1,0,',','.');?></th>
				<th class="text-right"><?php echo number_format($akumulasi_ppn_keluaran_1,0,',','.');?></th>
				<th class="text-right"><?php echo number_format($ppn_keluaran_2,0,',','.');?></th>
				<th class="text-right"><?php echo number_format($akumulasi_ppn_keluaran_2,0,',','.');?></th>
				<th class="text-right"><?php echo number_format($ppn_keluaran_3,0,',','.');?></th>
				<th class="text-right"><?php echo number_format($akumulasi_ppn_keluaran_3,0,',','.');?></th>
				<th class="text-right"><?php echo number_format($ppn_keluaran_4,0,',','.');?></th>
				<th class="text-right"><?php echo number_format($akumulasi_ppn_keluaran_4,0,',','.');?></th>
				<th class="text-right"><?php echo number_format($ppn_keluaran_5,0,',','.');?></th>
				<th class="text-right"><?php echo number_format($akumulasi_ppn_keluaran_5,0,',','.');?></th>
				<th class="text-right"><?php echo number_format($ppn_keluaran_6,0,',','.');?></th>
				<th class="text-right"><?php echo number_format($akumulasi_ppn_keluaran_6,0,',','.');?></th>
				<th class="text-right"><?php echo number_format($ppn_keluaran_rap - $jumlah_ppn_keluaran,0,',','.');?></th>
			</tr>
			<tr class="table-active2-csf">
				<th class="text-left">JUMLAH PENERIMAAN</th>
				<th class="text-right"><?php echo number_format($total_rap_nilai_2022 + $ppn_keluaran_rap,0,',','.');?></th>
				<th class="text-right"><?php echo number_format($termin_now['total'] + $ppn_keluar_now['total'],0,',','.');?></th>
				<th class="text-right"><?php echo number_format($termin_1 + $ppn_keluaran_1,0,',','.');?></th>
				<th class="text-right"><?php echo number_format($akumulasi_termin_1 + $akumulasi_ppn_keluaran_1,0,',','.');?></th>
				<th class="text-right"><?php echo number_format($termin_2 + $ppn_keluaran_2,0,',','.');?></th>
				<th class="text-right"><?php echo number_format($akumulasi_termin_2 + $akumulasi_ppn_keluaran_2,0,',','.');?></th>
				<th class="text-right"><?php echo number_format($termin_3 + $ppn_keluaran_3,0,',','.');?></th>
				<th class="text-right"><?php echo number_format($akumulasi_termin_3 + $akumulasi_ppn_keluaran_3,0,',','.');?></th>
				<th class="text-right"><?php echo number_format($termin_4 + $ppn_keluaran_4,0,',','.');?></th>
				<th class="text-right"><?php echo number_format($akumulasi_termin_4 + $akumulasi_ppn_keluaran_4,0,',','.');?></th>
				<th class="text-right"><?php echo number_format($termin_5 + $ppn_keluaran_5,0,',','.');?></th>
				<th class="text-right"><?php echo number_format($akumulasi_termin_5 + $akumulasi_ppn_keluaran_5,0,',','.');?></th>
				<th class="text-right"><?php echo number_format($termin_6 + $ppn_keluaran_6,0,',','.');?></th>
				<th class="text-right"><?php echo number_format($akumulasi_termin_6 + $akumulasi_ppn_keluaran_6,0,',','.');?></th>
				<th class="text-right"><?php echo number_format(($total_rap_nilai_2022 - $jumlah_termin) + ($ppn_keluaran_rap - $jumlah_ppn_keluaran),0,',','.');?></th>
			</tr>
			<tr class="table-active3-csf">
				<th class="text-center" rowspan="8" style="vertical-align:middle">3</th>
				<th class="text-left" colspan="16"><u>PENGELUARAN (EXCL. PPN)</u></th>
			</tr>
			<tr class="table-active3-csf">
				<th class="text-left">&nbsp;&nbsp;3.1 Biaya Bahan</th>
				<th class="text-right"><?php echo number_format($total_rap_2022_biaya_bahan,0,',','.');?></th>
				<th class="text-right"><?php echo number_format($pembayaran_bahan_now,0,',','.');?></th>
				<th class="text-right"><?php echo number_format($biaya_bahan_1,0,',','.');?></th>
				<th class="text-right"><?php echo number_format($akumulasi_biaya_bahan_1,0,',','.');?></th>
				<th class="text-right"><?php echo number_format($biaya_bahan_2,0,',','.');?></th>
				<th class="text-right"><?php echo number_format($akumulasi_biaya_bahan_2,0,',','.');?></th>
				<th class="text-right"><?php echo number_format($biaya_bahan_3,0,',','.');?></th>
				<th class="text-right"><?php echo number_format($akumulasi_biaya_bahan_3,0,',','.');?></th>
				<th class="text-right"><?php echo number_format($biaya_bahan_4,0,',','.');?></th>
				<th class="text-right"><?php echo number_format($akumulasi_biaya_bahan_4,0,',','.');?></th>
				<th class="text-right"><?php echo number_format($biaya_bahan_5,0,',','.');?></th>
				<th class="text-right"><?php echo number_format($akumulasi_biaya_bahan_5,0,',','.');?></th>
				<th class="text-right"><?php echo number_format($biaya_bahan_6,0,',','.');?></th>
				<th class="text-right"><?php echo number_format($akumulasi_biaya_bahan_6,0,',','.');?></th>
				<th class="text-right"><?php echo number_format($total_rap_2022_biaya_bahan - $jumlah_biaya_bahan,0,',','.');?></th>
			</tr>
			<tr class="table-active3-csf">
				<th class="text-left">&nbsp;&nbsp;3.2 Biaya Alat</th>
				<th class="text-right"><?php echo number_format($total_rap_2022_biaya_alat,0,',','.');?></th>
				<th class="text-right"><?php echo number_format($alat_now,0,',','.');?></th>
				<th class="text-right"><?php echo number_format($biaya_alat_1,0,',','.');?></th>
				<th class="text-right"><?php echo number_format($akumulasi_biaya_alat_1,0,',','.');?></th>
				<th class="text-right"><?php echo number_format($biaya_alat_2,0,',','.');?></th>
				<th class="text-right"><?php echo number_format($akumulasi_biaya_alat_2,0,',','.');?></th>
				<th class="text-right"><?php echo number_format($biaya_alat_3,0,',','.');?></th>
				<th class="text-right"><?php echo number_format($akumulasi_biaya_alat_3,0,',','.');?></th>
				<th class="text-right"><?php echo number_format($biaya_alat_4,0,',','.');?></th>
				<th class="text-right"><?php echo number_format($akumulasi_biaya_alat_4,0,',','.');?></th>
				<th class="text-right"><?php echo number_format($biaya_alat_5,0,',','.');?></th>
				<th class="text-right"><?php echo number_format($akumulasi_biaya_alat_5,0,',','.');?></th>
				<th class="text-right"><?php echo number_format($biaya_alat_6,0,',','.');?></th>
				<th class="text-right"><?php echo number_format($akumulasi_biaya_alat_6,0,',','.');?></th>
				<th class="text-right"><?php echo number_format($total_rap_2022_biaya_alat - $jumlah_biaya_alat,0,',','.');?></th>
			</tr>
			<tr class="table-active3-csf">
				<th class="text-left">&nbsp;&nbsp;3.3 Biaya Bank</th>
				<th class="text-right"><?php echo number_format($total_rap_2022_biaya_bank,0,',','.');?></th>
				<th class="text-right"><?php echo number_format($diskonto_now,0,',','.');?></th>
				<th class="text-right"><?php echo number_format($biaya_bank_1,0,',','.');?></th>
				<th class="text-right"><?php echo number_format($akumulasi_biaya_bank_1,0,',','.');?></th>
				<th class="text-right"><?php echo number_format($biaya_bank_2,0,',','.');?></th>
				<th class="text-right"><?php echo number_format($akumulasi_biaya_bank_2,0,',','.');?></th>
				<th class="text-right"><?php echo number_format($biaya_bank_3,0,',','.');?></th>
				<th class="text-right"><?php echo number_format($akumulasi_biaya_bank_3,0,',','.');?></th>
				<th class="text-right"><?php echo number_format($biaya_bank_4,0,',','.');?></th>
				<th class="text-right"><?php echo number_format($akumulasi_biaya_bank_4,0,',','.');?></th>
				<th class="text-right"><?php echo number_format($biaya_bank_5,0,',','.');?></th>
				<th class="text-right"><?php echo number_format($akumulasi_biaya_bank_5,0,',','.');?></th>
				<th class="text-right"><?php echo number_format($biaya_bank_6,0,',','.');?></th>
				<th class="text-right"><?php echo number_format($akumulasi_biaya_bank_6,0,',','.');?></th>
				<th class="text-right"><?php echo number_format($total_rap_2022_biaya_bank - $jumlah_biaya_bank,0,',','.');?></th>
			</tr>
			<tr class="table-active3-csf">
				<th class="text-left">&nbsp;&nbsp;3.4 BUA</th>
				<th class="text-right"><?php echo number_format($total_rap_2022_biaya_overhead,0,',','.');?></th>
				<th class="text-right"><?php echo number_format($overhead_now,0,',','.');?></th>
				<th class="text-right"><?php echo number_format($biaya_overhead_1,0,',','.');?></th>
				<th class="text-right"><?php echo number_format($akumulasi_biaya_overhead_1,0,',','.');?></th>
				<th class="text-right"><?php echo number_format($biaya_overhead_2,0,',','.');?></th>
				<th class="text-right"><?php echo number_format($akumulasi_biaya_overhead_2,0,',','.');?></th>
				<th class="text-right"><?php echo number_format($biaya_overhead_3,0,',','.');?></th>
				<th class="text-right"><?php echo number_format($akumulasi_biaya_overhead_3,0,',','.');?></th>
				<th class="text-right"><?php echo number_format($biaya_overhead_4,0,',','.');?></th>
				<th class="text-right"><?php echo number_format($akumulasi_biaya_overhead_4,0,',','.');?></th>
				<th class="text-right"><?php echo number_format($biaya_overhead_5,0,',','.');?></th>
				<th class="text-right"><?php echo number_format($akumulasi_biaya_overhead_5,0,',','.');?></th>
				<th class="text-right"><?php echo number_format($biaya_overhead_6,0,',','.');?></th>
				<th class="text-right"><?php echo number_format($akumulasi_biaya_overhead_6,0,',','.');?></th>
				<th class="text-right"><?php echo number_format($total_rap_2022_biaya_overhead - $jumlah_biaya_overhead,0,',','.');?></th>
			</tr>
			<tr class="table-active3-csf">
				<th class="text-left">&nbsp;&nbsp;3.5 PPN Rekanan</th>
				<th class="text-right"><?php echo number_format($pajak_masukan,0,',','.');?></th>
				<th class="text-right"><?php echo number_format($ppn_masuk_now['total'],0,',','.');?></th>
				<th class="text-right"><?php echo number_format($ppn_keluaran_1,0,',','.');?></th>
				<th class="text-right"><?php echo number_format($akumulasi_ppn_keluaran_1,0,',','.');?></th>
				<th class="text-right"><?php echo number_format($ppn_keluaran_2,0,',','.');?></th>
				<th class="text-right"><?php echo number_format($akumulasi_ppn_keluaran_2,0,',','.');?></th>
				<th class="text-right"><?php echo number_format($ppn_keluaran_3,0,',','.');?></th>
				<th class="text-right"><?php echo number_format($akumulasi_ppn_keluaran_3,0,',','.');?></th>
				<th class="text-right"><?php echo number_format($ppn_keluaran_4,0,',','.');?></th>
				<th class="text-right"><?php echo number_format($akumulasi_ppn_keluaran_4,0,',','.');?></th>
				<th class="text-right"><?php echo number_format($ppn_keluaran_5,0,',','.');?></th>
				<th class="text-right"><?php echo number_format($akumulasi_ppn_keluaran_5,0,',','.');?></th>
				<th class="text-right"><?php echo number_format($ppn_keluaran_6,0,',','.');?></th>
				<th class="text-right"><?php echo number_format($akumulasi_ppn_keluaran_6,0,',','.');?></th>
				<th class="text-right"><?php echo number_format($pajak_masukan - $jumlah_ppn_keluaran,0,',','.');?></th>
			</tr>
			<tr class="table-active3-csf">
				<th class="text-left">&nbsp;&nbsp;3.6 Biaya Persiapan</th>
				<th class="text-right"><?php echo number_format($total_rap_2022_biaya_persiapan,0,',','.');?></th>
				<th class="text-right"><?php echo number_format($biaya_persiapan_now['total'],0,',','.');?></th>
				<th class="text-right"><?php echo number_format($biaya_persiapan_1,0,',','.');?></th>
				<th class="text-right"><?php echo number_format($akumulasi_biaya_persiapan_1,0,',','.');?></th>
				<th class="text-right"><?php echo number_format($biaya_persiapan_2,0,',','.');?></th>
				<th class="text-right"><?php echo number_format($akumulasi_biaya_persiapan_2,0,',','.');?></th>
				<th class="text-right"><?php echo number_format($biaya_persiapan_3,0,',','.');?></th>
				<th class="text-right"><?php echo number_format($akumulasi_biaya_persiapan_3,0,',','.');?></th>
				<th class="text-right"><?php echo number_format($biaya_persiapan_4,0,',','.');?></th>
				<th class="text-right"><?php echo number_format($akumulasi_biaya_persiapan_4,0,',','.');?></th>
				<th class="text-right"><?php echo number_format($biaya_persiapan_5,0,',','.');?></th>
				<th class="text-right"><?php echo number_format($akumulasi_biaya_persiapan_5,0,',','.');?></th>
				<th class="text-right"><?php echo number_format($biaya_persiapan_6,0,',','.');?></th>
				<th class="text-right"><?php echo number_format($akumulasi_biaya_persiapan_6,0,',','.');?></th>
				<th class="text-right"><?php echo $sisa_biaya_persiapan < 0 ? "(".number_format($sisa_biaya_persiapan,0,',','.').")" : number_format($sisa_biaya_persiapan,0,',','.');?></th>
			</tr>
			<tr class="table-active2-csf">
				<th class="text-left">JUMLAH PENGELUARAN</th>
				<th class="text-right"><?php echo number_format($jumlah_pengeluaran,0,',','.');?></th>
				<th class="text-right"><?php echo number_format($jumlah_pengeluaran_now,0,',','.');?></th>
				<th class="text-right"><?php echo number_format($jumlah_pengeluaran_1,0,',','.');?></th>
				<th class="text-right"><?php echo number_format($akumulasi_pengeluaran_1,0,',','.');?></th>
				<th class="text-right"><?php echo number_format($jumlah_pengeluaran_2,0,',','.');?></th>
				<th class="text-right"><?php echo number_format($akumulasi_pengeluaran_2,0,',','.');?></th>
				<th class="text-right"><?php echo number_format($jumlah_pengeluaran_3,0,',','.');?></th>
				<th class="text-right"><?php echo number_format($akumulasi_pengeluaran_3,0,',','.');?></th>
				<th class="text-right"><?php echo number_format($jumlah_pengeluaran_4,0,',','.');?></th>
				<th class="text-right"><?php echo number_format($akumulasi_pengeluaran_4,0,',','.');?></th>
				<th class="text-right"><?php echo number_format($jumlah_pengeluaran_5,0,',','.');?></th>
				<th class="text-right"><?php echo number_format($akumulasi_pengeluaran_5,0,',','.');?></th>
				<th class="text-right"><?php echo number_format($jumlah_pengeluaran_6,0,',','.');?></th>
				<th class="text-right"><?php echo number_format($akumulasi_pengeluaran_6,0,',','.');?></th>
				<th class="text-right"><?php echo number_format($jumlah_pengeluaran - $total_pengeluaran,0,',','.');?></th>
			</tr>
			<tr class="table-active3-csf">
				<th class="text-center" rowspan="4" style="vertical-align:middle">4</th>
				<th class="text-left" colspan="16"><u>PAJAK</u></th>
			</tr>
			<tr class="table-active3-csf">
				<th class="text-left">&nbsp;&nbsp;1. Pajak Keluaran</th>
				<th class="text-right"><?php echo number_format($total_rap_2022_pajak_keluaran,0,',','.');?></th>
				<th class="text-right"><?php echo number_format($pajak_keluaran_now,0,',','.');?></th>
				<th class="text-right"><?php echo number_format($pajak_keluaran_1,0,',','.');?></th>
				<th class="text-right"><?php echo number_format($akumulasi_pajak_keluaran_1,0,',','.');?></th>
				<th class="text-right"><?php echo number_format($pajak_keluaran_2,0,',','.');?></th>
				<th class="text-right"><?php echo number_format($akumulasi_pajak_keluaran_2,0,',','.');?></th>
				<th class="text-right"><?php echo number_format($pajak_keluaran_3,0,',','.');?></th>
				<th class="text-right"><?php echo number_format($akumulasi_pajak_keluaran_3,0,',','.');?></th>
				<th class="text-right"><?php echo number_format($pajak_keluaran_4,0,',','.');?></th>
				<th class="text-right"><?php echo number_format($akumulasi_pajak_keluaran_4,0,',','.');?></th>
				<th class="text-right"><?php echo number_format($pajak_keluaran_5,0,',','.');?></th>
				<th class="text-right"><?php echo number_format($akumulasi_pajak_keluaran_5,0,',','.');?></th>
				<th class="text-right"><?php echo number_format($pajak_keluaran_6,0,',','.');?></th>
				<th class="text-right"><?php echo number_format($akumulasi_pajak_keluaran_6,0,',','.');?></th>
				<th class="text-right"><?php echo number_format($total_rap_2022_pajak_keluaran - $total_pajak_keluaran_6,0,',','.');?></th>
			</tr>
			<tr class="table-active3-csf">
				<th class="text-left">&nbsp;&nbsp;2. Pajak Masukan</th>
				<th class="text-right"><?php echo number_format($total_rap_2022_pajak_masukan,0,',','.');?></th>
				<th class="text-right"><?php echo number_format($pajak_masukan_now,0,',','.');?></th>
				<th class="text-right"><?php echo number_format($pajak_masukan_1,0,',','.');?></th>
				<th class="text-right"><?php echo number_format($akumulasi_pajak_masukan_1,0,',','.');?></th>
				<th class="text-right"><?php echo number_format($pajak_masukan_2,0,',','.');?></th>
				<th class="text-right"><?php echo number_format($akumulasi_pajak_masukan_2,0,',','.');?></th>
				<th class="text-right"><?php echo number_format($pajak_masukan_3,0,',','.');?></th>
				<th class="text-right"><?php echo number_format($akumulasi_pajak_masukan_3,0,',','.');?></th>
				<th class="text-right"><?php echo number_format($pajak_masukan_4,0,',','.');?></th>
				<th class="text-right"><?php echo number_format($akumulasi_pajak_masukan_4,0,',','.');?></th>
				<th class="text-right"><?php echo number_format($pajak_masukan_5,0,',','.');?></th>
				<th class="text-right"><?php echo number_format($akumulasi_pajak_masukan_5,0,',','.');?></th>
				<th class="text-right"><?php echo number_format($pajak_masukan_6,0,',','.');?></th>
				<th class="text-right"><?php echo number_format($akumulasi_pajak_masukan_6,0,',','.');?></th>
				<th class="text-right"><?php echo number_format($total_rap_2022_pajak_masukan - $total_pajak_masukan_6,0,',','.');?></th>
			</tr>
			<tr class="table-active2-csf">
				<th class="text-left">KURANG BAYAR PPN</th>
				<th class="text-right"><?php echo number_format($total_rap_2022_pajak_keluaran - $total_rap_2022_pajak_masukan,0,',','.');?></th>
				<th class="text-right"><?php echo number_format($pajak_keluaran_now - $pajak_masukan_now,0,',','.');?></th>
				<th class="text-right"><?php echo number_format($pajak_keluaran_1 - $pajak_masukan_1,0,',','.');?></th>
				<th class="text-right"><?php echo number_format($akumulasi_pajak_keluaran_1 - $akumulasi_pajak_masukan_1,0,',','.');?></th>
				<th class="text-right"><?php echo number_format($pajak_keluaran_2 - $pajak_masukan_2,0,',','.');?></th>
				<th class="text-right"><?php echo number_format($akumulasi_pajak_keluaran_2 - $akumulasi_pajak_masukan_2,0,',','.');?></th>
				<th class="text-right"><?php echo number_format($pajak_keluaran_3 - $pajak_masukan_3,0,',','.');?></th>
				<th class="text-right"><?php echo number_format($akumulasi_pajak_keluaran_3 - $akumulasi_pajak_masukan_3,0,',','.');?></th>
				<th class="text-right"><?php echo number_format($pajak_keluaran_4 - $pajak_masukan_4,0,',','.');?></th>
				<th class="text-right"><?php echo number_format($akumulasi_pajak_keluaran_4 - $akumulasi_pajak_masukan_4,0,',','.');?></th>
				<th class="text-right"><?php echo number_format($pajak_keluaran_5 - $pajak_masukan_5,0,',','.');?></th>
				<th class="text-right"><?php echo number_format($akumulasi_pajak_keluaran_5 - $akumulasi_pajak_masukan_5,0,',','.');?></th>
				<th class="text-right"><?php echo number_format($pajak_keluaran_6 - $pajak_masukan_6,0,',','.');?></th>
				<th class="text-right"><?php echo number_format($akumulasi_pajak_keluaran_6 - $akumulasi_pajak_masukan_6,0,',','.');?></th>
				<th class="text-right"><?php echo number_format(($total_rap_2022_pajak_keluaran - $total_pajak_keluaran_6) - ($total_rap_2022_pajak_masukan - $total_pajak_masukan_6),0,',','.');?></th>
			</tr>
			<tr class="table-active3-csf">
				<th class="text-center" rowspan="8" style="vertical-align:middle">5</th>
				<th class="text-left" colspan="16"><u>PINJAMAN</u></th>
			</tr>
			<tr class="table-active3-csf">
				<th class="text-left" colspan="16">MODAL PERSIAPAN</th>
			</tr>
			<tr class="table-active3-csf">
				<th class="text-left">&nbsp;&nbsp;1. Penerimaan Pinjaman</th>
				<th class="text-right"><?php echo number_format($total_rap_2022_penerimaan_pinjaman,0,',','.');?></th>
				<th class="text-right"><?php echo number_format($penerimaan_pinjaman_now,0,',','.');?></th>
				<th class="text-right"><?php echo number_format($penerimaan_pinjaman_1,0,',','.');?></th>
				<th class="text-right"><?php echo number_format($akumulasi_penerimaan_pinjaman_1,0,',','.');?></th>
				<th class="text-right"><?php echo number_format($penerimaan_pinjaman_2,0,',','.');?></th>
				<th class="text-right"><?php echo number_format($akumulasi_penerimaan_pinjaman_2,0,',','.');?></th>
				<th class="text-right"><?php echo number_format($penerimaan_pinjaman_3,0,',','.');?></th>
				<th class="text-right"><?php echo number_format($akumulasi_penerimaan_pinjaman_3,0,',','.');?></th>
				<th class="text-right"><?php echo number_format($penerimaan_pinjaman_4,0,',','.');?></th>
				<th class="text-right"><?php echo number_format($akumulasi_penerimaan_pinjaman_4,0,',','.');?></th>
				<th class="text-right"><?php echo number_format($penerimaan_pinjaman_5,0,',','.');?></th>
				<th class="text-right"><?php echo number_format($akumulasi_penerimaan_pinjaman_5,0,',','.');?></th>
				<th class="text-right"><?php echo number_format($penerimaan_pinjaman_6,0,',','.');?></th>
				<th class="text-right"><?php echo number_format($akumulasi_penerimaan_pinjaman_6,0,',','.');?></th>
				<th class="text-right"><?php echo number_format($total_rap_2022_penerimaan_pinjaman - $total_penerimaan_pinjaman,0,',','.');?></th>
			</tr>
			<tr class="table-active3-csf">
				<th class="text-left">&nbsp;&nbsp;2. Pengembalian Pinjaman</th>
				<th class="text-right"><?php echo number_format($total_rap_2022_pengembalian_pinjaman,0,',','.');?></th>
				<th class="text-right"><?php echo number_format($pengembalian_pinjaman_now,0,',','.');?></th>
				<th class="text-right"><?php echo number_format($pengembalian_pinjaman_1,0,',','.');?></th>
				<th class="text-right"><?php echo number_format($akumulasi_pengembalian_pinjaman_1,0,',','.');?></th>
				<th class="text-right"><?php echo number_format($pengembalian_pinjaman_2,0,',','.');?></th>
				<th class="text-right"><?php echo number_format($akumulasi_pengembalian_pinjaman_2,0,',','.');?></th>
				<th class="text-right"><?php echo number_format($pengembalian_pinjaman_3,0,',','.');?></th>
				<th class="text-right"><?php echo number_format($akumulasi_pengembalian_pinjaman_3,0,',','.');?></th>
				<th class="text-right"><?php echo number_format($pengembalian_pinjaman_4,0,',','.');?></th>
				<th class="text-right"><?php echo number_format($akumulasi_pengembalian_pinjaman_4,0,',','.');?></th>
				<th class="text-right"><?php echo number_format($pengembalian_pinjaman_5,0,',','.');?></th>
				<th class="text-right"><?php echo number_format($akumulasi_pengembalian_pinjaman_5,0,',','.');?></th>
				<th class="text-right"><?php echo number_format($pengembalian_pinjaman_6,0,',','.');?></th>
				<th class="text-right"><?php echo number_format($akumulasi_pengembalian_pinjaman_6,0,',','.');?></th>
				<th class="text-right"><?php echo number_format($total_rap_2022_pengembalian_pinjaman - $total_pengembalian_pinjaman,0,',','.');?></th>
			</tr>
			<tr class="table-active2-csf">
				<th class="text-left">SISA PINJAMAN DANA</th>
				<th class="text-right"><?php echo number_format($total_rap_2022_penerimaan_pinjaman - $total_rap_2022_pengembalian_pinjaman,0,',','.');?></th>
				<th class="text-right"><?php echo number_format($penerimaan_pinjaman_now - $pengembalian_pinjaman_now,0,',','.');?></th>
				<th class="text-right"><?php echo number_format($penerimaan_pinjaman_1 - $pengembalian_pinjaman_1,0,',','.');?></th>
				<th class="text-right"><?php echo number_format($akumulasi_penerimaan_pinjaman_1 - $akumulasi_pengembalian_pinjaman_1,0,',','.');?></th>
				<th class="text-right"><?php echo number_format($penerimaan_pinjaman_2 - $pengembalian_pinjaman_2,0,',','.');?></th>
				<th class="text-right"><?php echo number_format($akumulasi_penerimaan_pinjaman_2 - $akumulasi_pengembalian_pinjaman_2,0,',','.');?></th>
				<th class="text-right"><?php echo number_format($penerimaan_pinjaman_3 - $pengembalian_pinjaman_3,0,',','.');?></th>
				<th class="text-right"><?php echo number_format($akumulasi_penerimaan_pinjaman_3 - $akumulasi_pengembalian_pinjaman_3,0,',','.');?></th>
				<th class="text-right"><?php echo number_format($penerimaan_pinjaman_4 - $pengembalian_pinjaman_4,0,',','.');?></th>
				<th class="text-right"><?php echo number_format($akumulasi_penerimaan_pinjaman_4 - $akumulasi_pengembalian_pinjaman_4,0,',','.');?></th>
				<th class="text-right"><?php echo number_format($penerimaan_pinjaman_5 - $pengembalian_pinjaman_5,0,',','.');?></th>
				<th class="text-right"><?php echo number_format($akumulasi_penerimaan_pinjaman_5 - $akumulasi_pengembalian_pinjaman_5,0,',','.');?></th>
				<th class="text-right"><?php echo number_format($penerimaan_pinjaman_6 - $pengembalian_pinjaman_6,0,',','.');?></th>
				<th class="text-right"><?php echo number_format($akumulasi_penerimaan_pinjaman_6 - $akumulasi_pengembalian_pinjaman_6,0,',','.');?></th>
				<th class="text-right"><?php echo number_format(($total_rap_2022_penerimaan_pinjaman - $total_penerimaan_pinjaman_6) - ($total_rap_2022_pengembalian_pinjaman - $total_pengembalian_pinjaman_6),0,',','.');?></th>
			</tr>
			<tr class="table-active3-csf">
				<th class="text-left" colspan="16">PEMAKAIAN DANA</th>
			</tr>
			<tr class="table-active3-csf">
				<th class="text-left">&nbsp;&nbsp;1. Pinjaman Dana</th>
				<th class="text-right"><?php echo number_format($total_rap_2022_pinjaman_dana,0,',','.');?></th>
				<th class="text-right"><a target="_blank" href="<?= base_url("laporan/cetak_pemakaian_dana?filter_date=".$filter_date = date('2021-01-01',strtotime('2021-01-01')).' - '.date('Y-m-d',strtotime($stock_opname['date']))) ?>"><?php echo number_format($pinjaman_dana_now,0,',','.');?></a></th>
				<th class="text-right"><?php echo number_format($pinjaman_dana_1,0,',','.');?></th>
				<th class="text-right"><?php echo number_format($akumulasi_pinjaman_dana_1,0,',','.');?></th>
				<th class="text-right"><?php echo number_format($pinjaman_dana_2,0,',','.');?></th>
				<th class="text-right"><?php echo number_format($akumulasi_pinjaman_dana_2,0,',','.');?></th>
				<th class="text-right"><?php echo number_format($pinjaman_dana_3,0,',','.');?></th>
				<th class="text-right"><?php echo number_format($akumulasi_pinjaman_dana_3,0,',','.');?></th>
				<th class="text-right"><?php echo number_format($pinjaman_dana_4,0,',','.');?></th>
				<th class="text-right"><?php echo number_format($akumulasi_pinjaman_dana_4,0,',','.');?></th>
				<th class="text-right"><?php echo number_format($pinjaman_dana_5,0,',','.');?></th>
				<th class="text-right"><?php echo number_format($akumulasi_pinjaman_dana_5,0,',','.');?></th>
				<th class="text-right"><?php echo number_format($pinjaman_dana_6,0,',','.');?></th>
				<th class="text-right"><?php echo number_format($akumulasi_pinjaman_dana_6,0,',','.');?></th>
				<th class="text-right"><?php echo number_format(0,0,',','.');?></th>
			</tr>
			<tr class="table-active2-csf">
				<th class="text-left">JUMLAH PEMAKAIAN DANA</th>
				<th class="text-right"><?php echo number_format(0,0,',','.');?></th>
				<th class="text-right"><?php echo number_format($pinjaman_dana_now,0,',','.');?></th>
				<th class="text-right"><?php echo number_format($pinjaman_dana_1,0,',','.');?></th>
				<th class="text-right"><?php echo number_format($akumulasi_pinjaman_dana_1,0,',','.');?></th>
				<th class="text-right"><?php echo number_format($pinjaman_dana_2,0,',','.');?></th>
				<th class="text-right"><?php echo number_format($akumulasi_pinjaman_dana_2,0,',','.');?></th>
				<th class="text-right"><?php echo number_format($pinjaman_dana_3,0,',','.');?></th>
				<th class="text-right"><?php echo number_format($akumulasi_pinjaman_dana_3,0,',','.');?></th>
				<th class="text-right"><?php echo number_format($pinjaman_dana_4,0,',','.');?></th>
				<th class="text-right"><?php echo number_format($akumulasi_pinjaman_dana_4,0,',','.');?></th>
				<th class="text-right"><?php echo number_format($pinjaman_dana_5,0,',','.');?></th>
				<th class="text-right"><?php echo number_format($akumulasi_pinjaman_dana_5,0,',','.');?></th>
				<th class="text-right"><?php echo number_format($pinjaman_dana_6,0,',','.');?></th>
				<th class="text-right"><?php echo number_format($akumulasi_pinjaman_dana_6,0,',','.');?></th>
				<th class="text-right"><?php echo number_format(($total_rap_2022_pinjaman_dana - $total_pinjaman_dana_6),0,',','.');?></th>
			</tr>
			<tr class="table-active3-csf">
				<th class="text-center">6</th>
				<th class="text-left"><u>PIUTANG</u></th>
				<th class="text-right"><?php echo number_format($total_rap_2022_piutang,0,',','.');?></th>
				<th class="text-right"><?php echo number_format($piutang_now,0,',','.');?></th>
				<th class="text-right"><?php echo number_format($piutang_1,0,',','.');?></th>
				<th class="text-right"><?php echo number_format($akumulasi_piutang_1,0,',','.');?></th>
				<th class="text-right"><?php echo number_format($piutang_2,0,',','.');?></th>
				<th class="text-right"><?php echo number_format($akumulasi_piutang_2,0,',','.');?></th>
				<th class="text-right"><?php echo number_format($piutang_3,0,',','.');?></th>
				<th class="text-right"><?php echo number_format($akumulasi_piutang_3,0,',','.');?></th>
				<th class="text-right"><?php echo number_format($piutang_4,0,',','.');?></th>
				<th class="text-right"><?php echo number_format($akumulasi_piutang_4,0,',','.');?></th>
				<th class="text-right"><?php echo number_format($piutang_5,0,',','.');?></th>
				<th class="text-right"><?php echo number_format($akumulasi_piutang_5,0,',','.');?></th>
				<th class="text-right"><?php echo number_format($piutang_6,0,',','.');?></th>
				<th class="text-right"><?php echo number_format($akumulasi_piutang_6,0,',','.');?></th>
				<th class="text-right"><?php echo number_format(0,0,',','.');?></th>
			</tr>
			<tr class="table-active3-csf">
				<th class="text-center"></th>
				<th class="text-left">&nbsp;&nbsp;DPP</th>
				<th class="text-right"><?php echo number_format(0,0,',','.');?></th>
				<th class="text-right"><?php echo number_format($piutang_now_dpp,0,',','.');?></th>
				<th class="text-right"><?php echo number_format(0,0,',','.');?></th>
				<th class="text-right"><?php echo number_format(0,0,',','.');?></th>
				<th class="text-right"><?php echo number_format(0,0,',','.');?></th>
				<th class="text-right"><?php echo number_format(0,0,',','.');?></th>
				<th class="text-right"><?php echo number_format(0,0,',','.');?></th>
				<th class="text-right"><?php echo number_format(0,0,',','.');?></th>
				<th class="text-right"><?php echo number_format(0,0,',','.');?></th>
				<th class="text-right"><?php echo number_format(0,0,',','.');?></th>
				<th class="text-right"><?php echo number_format(0,0,',','.');?></th>
				<th class="text-right"><?php echo number_format(0,0,',','.');?></th>
				<th class="text-right"><?php echo number_format(0,0,',','.');?></th>
				<th class="text-right"><?php echo number_format(0,0,',','.');?></th>
				<th class="text-right"><?php echo number_format(0,0,',','.');?></th>
			</tr>
			<tr class="table-active3-csf">
				<th class="text-center"></th>
				<th class="text-left">&nbsp;&nbsp;PPN</th>
				<th class="text-right"><?php echo number_format(0,0,',','.');?></th>
				<th class="text-right"><?php echo number_format($piutang_now_ppn,0,',','.');?></th>
				<th class="text-right"><?php echo number_format(0,0,',','.');?></th>
				<th class="text-right"><?php echo number_format(0,0,',','.');?></th>
				<th class="text-right"><?php echo number_format(0,0,',','.');?></th>
				<th class="text-right"><?php echo number_format(0,0,',','.');?></th>
				<th class="text-right"><?php echo number_format(0,0,',','.');?></th>
				<th class="text-right"><?php echo number_format(0,0,',','.');?></th>
				<th class="text-right"><?php echo number_format(0,0,',','.');?></th>
				<th class="text-right"><?php echo number_format(0,0,',','.');?></th>
				<th class="text-right"><?php echo number_format(0,0,',','.');?></th>
				<th class="text-right"><?php echo number_format(0,0,',','.');?></th>
				<th class="text-right"><?php echo number_format(0,0,',','.');?></th>
				<th class="text-right"><?php echo number_format(0,0,',','.');?></th>
				<th class="text-right"><?php echo number_format(0,0,',','.');?></th>
			</tr>
			<tr class="table-active3-csf">
				<th class="text-center">7</th>
				<th class="text-left"><u>HUTANG</u></th>
				<th class="text-right"><?php echo number_format($total_rap_2022_hutang,0,',','.');?></th>
				<th class="text-right"><?php echo number_format($hutang_now,0,',','.');?></th>
				<th class="text-right"><?php echo number_format($hutang_1,0,',','.');?></th>
				<th class="text-right"><?php echo number_format($akumulasi_hutang_1,0,',','.');?></th>
				<th class="text-right"><?php echo number_format($hutang_2,0,',','.');?></th>
				<th class="text-right"><?php echo number_format($akumulasi_hutang_2,0,',','.');?></th>
				<th class="text-right"><?php echo number_format($hutang_3,0,',','.');?></th>
				<th class="text-right"><?php echo number_format($akumulasi_hutang_3,0,',','.');?></th>
				<th class="text-right"><?php echo number_format($hutang_4,0,',','.');?></th>
				<th class="text-right"><?php echo number_format($akumulasi_hutang_4,0,',','.');?></th>
				<th class="text-right"><?php echo number_format($hutang_5,0,',','.');?></th>
				<th class="text-right"><?php echo number_format($akumulasi_hutang_5,0,',','.');?></th>
				<th class="text-right"><?php echo number_format($hutang_6,0,',','.');?></th>
				<th class="text-right"><?php echo number_format($akumulasi_hutang_6,0,',','.');?></th>
				<th class="text-right"><?php echo number_format(0,0,',','.');?></th>
			</tr>
			<tr class="table-active3-csf">
				<th class="text-center"></th>
				<th class="text-left">&nbsp;&nbsp;DPP</th>
				<th class="text-right"><?php echo number_format(0,0,',','.');?></th>
				<th class="text-right"><?php echo number_format($hutang_now_dpp,0,',','.');?></th>
				<th class="text-right"><?php echo number_format(0,0,',','.');?></th>
				<th class="text-right"><?php echo number_format(0,0,',','.');?></th>
				<th class="text-right"><?php echo number_format(0,0,',','.');?></th>
				<th class="text-right"><?php echo number_format(0,0,',','.');?></th>
				<th class="text-right"><?php echo number_format(0,0,',','.');?></th>
				<th class="text-right"><?php echo number_format(0,0,',','.');?></th>
				<th class="text-right"><?php echo number_format(0,0,',','.');?></th>
				<th class="text-right"><?php echo number_format(0,0,',','.');?></th>
				<th class="text-right"><?php echo number_format(0,0,',','.');?></th>
				<th class="text-right"><?php echo number_format(0,0,',','.');?></th>
				<th class="text-right"><?php echo number_format(0,0,',','.');?></th>
				<th class="text-right"><?php echo number_format(0,0,',','.');?></th>
				<th class="text-right"><?php echo number_format(0,0,',','.');?></th>
			</tr>
			<tr class="table-active3-csf">
				<th class="text-center"></th>
				<th class="text-left">&nbsp;&nbsp;PPN</th>
				<th class="text-right"><?php echo number_format(0,0,',','.');?></th>
				<th class="text-right"><?php echo number_format($hutang_now_ppn,0,',','.');?></th>
				<th class="text-right"><?php echo number_format(0,0,',','.');?></th>
				<th class="text-right"><?php echo number_format(0,0,',','.');?></th>
				<th class="text-right"><?php echo number_format(0,0,',','.');?></th>
				<th class="text-right"><?php echo number_format(0,0,',','.');?></th>
				<th class="text-right"><?php echo number_format(0,0,',','.');?></th>
				<th class="text-right"><?php echo number_format(0,0,',','.');?></th>
				<th class="text-right"><?php echo number_format(0,0,',','.');?></th>
				<th class="text-right"><?php echo number_format(0,0,',','.');?></th>
				<th class="text-right"><?php echo number_format(0,0,',','.');?></th>
				<th class="text-right"><?php echo number_format(0,0,',','.');?></th>
				<th class="text-right"><?php echo number_format(0,0,',','.');?></th>
				<th class="text-right"><?php echo number_format(0,0,',','.');?></th>
				<th class="text-right"><?php echo number_format(0,0,',','.');?></th>
			</tr>
			<tr class="table-active3-csf">
				<th class="text-center">8</th>
				<th class="text-left"><u>MOS</u></th>
				<th class="text-right"><?php echo number_format(0,0,',','.');?></th>
				<th class="text-right"><?php echo number_format($mos_now,0,',','.');?></th>
				<th class="text-right"><?php echo number_format(0,0,',','.');?></th>
				<th class="text-right"><?php echo number_format(0,0,',','.');?></th>
				<th class="text-right"><?php echo number_format(0,0,',','.');?></th>
				<th class="text-right"><?php echo number_format(0,0,',','.');?></th>
				<th class="text-right"><?php echo number_format(0,0,',','.');?></th>
				<th class="text-right"><?php echo number_format(0,0,',','.');?></th>
				<th class="text-right"><?php echo number_format(0,0,',','.');?></th>
				<th class="text-right"><?php echo number_format(0,0,',','.');?></th>
				<th class="text-right"><?php echo number_format(0,0,',','.');?></th>
				<th class="text-right"><?php echo number_format(0,0,',','.');?></th>
				<th class="text-right"><?php echo number_format(0,0,',','.');?></th>
				<th class="text-right"><?php echo number_format(0,0,',','.');?></th>
				<th class="text-right"><?php echo number_format(0,0,',','.');?></th>
			</tr>
			<tr class="table-active4-csf">
				<th class="text-center">9</th>
				<th class="text-left"><u>POSISI DANA</u></th>
				<th class="text-right"><?php echo $posisi_dana_rap < 0 ? "(".number_format(-$posisi_dana_rap,0,',','.').")" : number_format($posisi_dana_rap,0,',','.');?></th>
				<th class="text-right"><?php echo $posisi_dana_now < 0 ? "(".number_format(-$posisi_dana_now,0,',','.').")" : number_format($posisi_dana_now,0,',','.');?></th>
				<th class="text-right"><?php echo $posisi_dana_1 < 0 ? "(".number_format(-$posisi_dana_1,0,',','.').")" : number_format($posisi_dana_1,0,',','.');?></th>
				<th class="text-right"><?php echo $akumulasi_posisi_dana_1 < 0 ? "(".number_format(-$akumulasi_posisi_dana_1,0,',','.').")" : number_format($akumulasi_posisi_dana_1,0,',','.');?></th>
				<th class="text-right"><?php echo $posisi_dana_2 < 0 ? "(".number_format(-$posisi_dana_2,0,',','.').")" : number_format($posisi_dana_2,0,',','.');?></th>
				<th class="text-right"><?php echo $akumulasi_posisi_dana_2 < 0 ? "(".number_format(-$akumulasi_posisi_dana_2,0,',','.').")" : number_format($akumulasi_posisi_dana_2,0,',','.');?></th>
				<th class="text-right"><?php echo $posisi_dana_3 < 0 ? "(".number_format(-$posisi_dana_3,0,',','.').")" : number_format($posisi_dana_3,0,',','.');?></th>
				<th class="text-right"><?php echo $akumulasi_posisi_dana_3 < 0 ? "(".number_format(-$akumulasi_posisi_dana_3,0,',','.').")" : number_format($akumulasi_posisi_dana_3,0,',','.');?></th>
				<th class="text-right"><?php echo $posisi_dana_4 < 0 ? "(".number_format(-$posisi_dana_4,0,',','.').")" : number_format($posisi_dana_4,0,',','.');?></th>
				<th class="text-right"><?php echo $akumulasi_posisi_dana_4 < 0 ? "(".number_format(-$akumulasi_posisi_dana_4,0,',','.').")" : number_format($akumulasi_posisi_dana_4,0,',','.');?></th>
				<th class="text-right"><?php echo $posisi_dana_5 < 0 ? "(".number_format(-$posisi_dana_5,0,',','.').")" : number_format($posisi_dana_5,0,',','.');?></th>
				<th class="text-right"><?php echo $akumulasi_posisi_dana_5 < 0 ? "(".number_format(-$akumulasi_posisi_dana_5,0,',','.').")" : number_format($akumulasi_posisi_dana_5,0,',','.');?></th>
				<th class="text-right"><?php echo $posisi_dana_6 < 0 ? "(".number_format(-$posisi_dana_6,0,',','.').")" : number_format($posisi_dana_6,0,',','.');?></th>
				<th class="text-right"><?php echo $akumulasi_posisi_dana_6 < 0 ? "(".number_format(-$akumulasi_posisi_dana_6,0,',','.').")" : number_format($akumulasi_posisi_dana_6,0,',','.');?></th>
				<th class="text-right"><?php echo number_format(0,0,',','.');?></th>
			</tr>
	    </table>
		<?php
	}

	public function evaluasi_target_produksi($arr_date)
	{
		$data = array();
		
		$arr_date = $this->input->post('filter_date');
		$arr_filter_date = explode(' - ', $arr_date);
		$date1 = '';
		$date2 = '';

		if(count($arr_filter_date) == 2){
			$date1 	= date('Y-m-d',strtotime($arr_filter_date[0]));
			$date2 	= date('Y-m-d',strtotime($arr_filter_date[1]));
			$filter_date = date('d F Y',strtotime($arr_filter_date[0])).' - '.date('d F Y',strtotime($arr_filter_date[1]));
		}
		
		?>
		
		<table class="table table-bordered" width="100%">
			<style type="text/css">
				table tr.table-active{
					background-color: #F0F0F0;
					font-size: 12px;
					font-weight: bold;
					color: black;
				}
					
				table tr.table-active2{
					background-color: #E8E8E8;
					font-size: 12px;
					font-weight: bold;
				}
					
				table tr.table-active3{
					font-size: 12px;
					background-color: #F0F0F0;
				}
					
				table tr.table-active4{
					background-color: #666666;
					font-weight: bold;
					font-size: 12px;
					color: white;
				}
				
				table tr.table-active5{
					background-color: #E8E8E8;
					text-decoration: underline;
					font-size: 12px;
					font-weight: bold;
					color: red;
				}
				table tr.table-activeago1{
					background-color: #ffd966;
					font-weight: bold;
					font-size: 12px;
					color: black;
				}
				table tr.table-activeopening{
					background-color: #2986cc;
					font-weight: bold;
					font-size: 12px;
					color: black;
				}
			</style>

			<!-- RAP -->
			<?php
			//VOLUME
			$date_now = date('Y-m-d');
			$rencana_kerja = $this->db->select('r.*')
			->from('rak r')
			->where("(r.tanggal_rencana_kerja between '$date1' and '$date2')")
			->group_by("r.id")
			->get()->result_array();

			$total_vol_a = 0;
			$total_vol_b = 0;
			$total_vol_c = 0;
			$total_vol_d = 0;
			$total_vol_e = 0;

			$total_price_a = 0;
			$total_price_b = 0;
			$total_price_c = 0;
			$total_price_d = 0;
			$total_price_e = 0;

			//$total_overhead = 0;
			//$total_diskonto = 0;

			foreach ($rencana_kerja as $x){
				$total_price_a += $x['vol_produk_a'] * $x['price_a'];
				$total_price_b += $x['vol_produk_b'] * $x['price_b'];
				$total_price_c += $x['vol_produk_c'] * $x['price_c'];
				$total_price_d += $x['vol_produk_d'] * $x['price_d'];
				$total_price_e += $x['vol_produk_e'] * $x['price_e'];

				$total_vol_a += $x['vol_produk_a'];
				$total_vol_b += $x['vol_produk_b'];
				$total_vol_c += $x['vol_produk_c'];
				$total_vol_d += $x['vol_produk_d'];
				$total_vol_e += $x['vol_produk_e'];

				//$total_overhead += $x['overhead'];
				//$total_diskonto += $x['biaya_bank'];
			}

			$volume_rap_produk_a = $total_vol_a;
			$volume_rap_produk_b = $total_vol_b;
			$volume_rap_produk_c = $total_vol_c;
			$volume_rap_produk_d = $total_vol_d;
			$volume_rap_produk_e = $total_vol_e;

			$total_rap_volume = $volume_rap_produk_a + $volume_rap_produk_b + $volume_rap_produk_c + $volume_rap_produk_d + $volume_rap_produk_e;
			
			$harga_jual_125_rap = $total_price_a;
			$harga_jual_225_rap = $total_price_b;
			$harga_jual_250_rap = $total_price_c;
			$harga_jual_250_18_rap = $total_price_d;
			$harga_jual_300_rap = $total_price_e;

			$nilai_jual_125 = $harga_jual_125_rap;
			$nilai_jual_225 = $harga_jual_225_rap;
			$nilai_jual_250 = $harga_jual_250_rap;
			$nilai_jual_250_18 = $harga_jual_250_18_rap;
			$nilai_jual_300 = $harga_jual_300_rap;
			$nilai_jual_all = $nilai_jual_125 + $nilai_jual_225 + $nilai_jual_250 + $nilai_jual_250_18 + $nilai_jual_300;
			
			$total_rap_nilai = $nilai_jual_all;

			//BIAYA
			$komposisi = $this->db->select('pp.date_production, (pp.display_volume) * pk.presentase_a as volume_a, (pp.display_volume) * pk.presentase_b as volume_b, (pp.display_volume) * pk.presentase_c as volume_c, (pp.display_volume) * pk.presentase_d as volume_d, (pp.display_volume * pk.presentase_a) * pk.price_a as nilai_a, (pp.display_volume * pk.presentase_b) * pk.price_b as nilai_b, (pp.display_volume * pk.presentase_c) * pk.price_c as nilai_c, (pp.display_volume * pk.presentase_d) * pk.price_d as nilai_d')
			->from('pmm_productions pp')
			->join('pmm_agregat pk', 'pp.komposisi_id = pk.id','left')
			->where("pp.date_production between '$date1' and '$date2'")
			->get()->result_array();

			$total_volume_a = 0;
			$total_volume_b = 0;
			$total_volume_c = 0;
			$total_volume_d = 0;

			$total_nilai_a = 0;
			$total_nilai_b = 0;
			$total_nilai_c = 0;
			$total_nilai_d = 0;

			foreach ($komposisi as $x){
				$total_volume_a += $x['volume_a'];
				$total_volume_b += $x['volume_b'];
				$total_volume_c += $x['volume_c'];
				$total_volume_d += $x['volume_d'];
				$total_nilai_a += $x['nilai_a'];
				$total_nilai_b += $x['nilai_b'];
				$total_nilai_c += $x['nilai_c'];
				$total_nilai_d += $x['nilai_d'];
				
			}

			$volume_a = $total_volume_a;
			$volume_b = $total_volume_b;
			$volume_c = $total_volume_c;
			$volume_d = $total_volume_d;

			$nilai_a = $total_nilai_a;
			$nilai_b = $total_nilai_b;
			$nilai_c = $total_nilai_c;
			$nilai_d = $total_nilai_d;

			$price_a = ($total_volume_a!=0)?$total_nilai_a / $total_volume_a * 1:0;
			$price_b = ($total_volume_b!=0)?$total_nilai_b / $total_volume_b * 1:0;
			$price_c = ($total_volume_c!=0)?$total_nilai_c / $total_volume_c * 1:0;
			$price_d = ($total_volume_d!=0)?$total_nilai_d / $total_volume_d * 1:0;

			$total_volume_komposisi = $volume_a + $volume_b + $volume_c + $volume_d;
			$total_nilai_komposisi = $nilai_a + $nilai_b + $nilai_c + $nilai_d;
			
			//BAHAN
			$total_rap_biaya_bahan = $total_nilai_komposisi;

			$total_volume = $this->db->select(' SUM(pp.display_volume) as volume')
			->from('pmm_productions pp')
			->join('produk p', 'pp.product_id = p.id','left')
			->join('pmm_sales_po ppo', 'pp.salesPo_id = ppo.id','left')
			->where("(pp.date_production between '$date1' and '$date2')")
			->where("pp.status = 'PUBLISH'")
			->where("ppo.status in ('OPEN','CLOSED')")
			->order_by('p.nama_produk','asc')
			->get()->row_array();

			$total_volume_produksi = 0;
			$total_volume_produksi = $total_volume['volume'];

			$rap_alat = $this->db->select('rap.*')
			->from('rap_alat rap')
			->where("rap.tanggal_rap_alat <= '$date2'")
			->where('rap.status','PUBLISH')
			->get()->result_array();

			$total_vol_rap_batching_plant = 0;
			$total_vol_rap_truck_mixer = 0;
			$total_vol_rap_wheel_loader = 0;
			$total_vol_rap_bbm_solar = 0;

			$total_batching_plant = 0;
			$total_truck_mixer = 0;
			$total_wheel_loader = 0;
			$total_bbm_solar = 0;

			foreach ($rap_alat as $x){
				$total_vol_rap_batching_plant += $x['vol_batching_plant'];
				$total_vol_rap_truck_mixer += $x['vol_truck_mixer'];
				$total_vol_rap_wheel_loader += $x['vol_wheel_loader'];
				$total_vol_rap_bbm_solar += $x['vol_bbm_solar'];
				$total_batching_plant = $x['harsat_batching_plant'];
				$total_truck_mixer = $x['harsat_truck_mixer'];
				$total_wheel_loader = $x['harsat_wheel_loader'];
				$total_bbm_solar = $x['harsat_bbm_solar'];
				
			}

			$vol_batching_plant = $total_vol_rap_batching_plant * $total_volume_produksi;
			$vol_truck_mixer = $total_vol_rap_truck_mixer * $total_volume_produksi;
			$vol_wheel_loader = $total_vol_rap_wheel_loader * $total_volume_produksi;
			$vol_bbm_solar = $total_vol_rap_bbm_solar * $total_volume_produksi;

			$batching_plant = $total_batching_plant * $vol_batching_plant;
			$truck_mixer = $total_truck_mixer * $vol_truck_mixer;
			$wheel_loader = $total_wheel_loader * $vol_wheel_loader;
			$transfer_semen = 0;
			$bbm_solar = $total_bbm_solar * $vol_bbm_solar;

			$harsat_batching_plant = ($vol_batching_plant!=0)?$batching_plant / $vol_batching_plant * 1:0;
			$harsat_truck_mixer = ($vol_truck_mixer!=0)?$truck_mixer / $vol_truck_mixer * 1:0;
			$harsat_wheel_loader = ($wheel_loader!=0)?$wheel_loader / $wheel_loader * 1:0;
			$harsat_bbm_solar = ($vol_bbm_solar!=0)?$bbm_solar / $vol_bbm_solar * 1:0;

			$total_nilai_rap_alat = $batching_plant + $truck_mixer + $wheel_loader + $bbm_solar;

			$total_rap_biaya_alat = $total_nilai_rap_alat;
			$total_rap_overhead = 102583 * $total_rap_volume;
			$total_rap_biaya_bank = 29620 * $total_rap_volume;

			$total_biaya_rap_biaya = $total_rap_biaya_bahan + $total_rap_biaya_alat + $total_rap_overhead + $total_rap_biaya_bank;
			?>
			<!-- RAP 2022 -->
			
			<!-- REALISASI -->
			<?php
			$penjualan_realisasi_produk_a = $this->db->select('p.nama_produk, SUM(pp.display_price) as price, SUM(pp.display_volume) as volume')
			->from('pmm_productions pp')
			->join('produk p', 'pp.product_id = p.id','left')
			->join('pmm_sales_po ppo', 'pp.salesPo_id = ppo.id','left')
			->where("(pp.date_production between '$date1' and '$date2')")
			->where("pp.status = 'PUBLISH'")
			->where("pp.product_id = 2")
			->where("ppo.status in ('OPEN','CLOSED')")
			->group_by("pp.product_id")
			->order_by('p.nama_produk','asc')
			->get()->row_array();
			$volume_realisasi_produk_a = $penjualan_realisasi_produk_a['volume'];
			$nilai_realisasi_produk_a = $penjualan_realisasi_produk_a['price'];

			$penjualan_realisasi_produk_b = $this->db->select('p.nama_produk, SUM(pp.display_price) as price, SUM(pp.display_volume) as volume')
			->from('pmm_productions pp')
			->join('produk p', 'pp.product_id = p.id','left')
			->join('pmm_sales_po ppo', 'pp.salesPo_id = ppo.id','left')
			->where("(pp.date_production between '$date1' and '$date2')")
			->where("pp.status = 'PUBLISH'")
			->where("pp.product_id = 1")
			->where("ppo.status in ('OPEN','CLOSED')")
			->group_by("pp.product_id")
			->order_by('p.nama_produk','asc')
			->get()->row_array();
			$volume_realisasi_produk_b = $penjualan_realisasi_produk_b['volume'];
			$nilai_realisasi_produk_b = $penjualan_realisasi_produk_b['price'];

			$penjualan_realisasi_produk_c = $this->db->select('p.nama_produk, SUM(pp.display_price) as price, SUM(pp.display_volume) as volume')
			->from('pmm_productions pp')
			->join('produk p', 'pp.product_id = p.id','left')
			->join('pmm_sales_po ppo', 'pp.salesPo_id = ppo.id','left')
			->where("(pp.date_production between '$date1' and '$date2')")
			->where("pp.status = 'PUBLISH'")
			->where("pp.product_id = 3")
			->where("ppo.status in ('OPEN','CLOSED')")
			->group_by("pp.product_id")
			->order_by('p.nama_produk','asc')
			->get()->row_array();
			$volume_realisasi_produk_c = $penjualan_realisasi_produk_c['volume'];
			$nilai_realisasi_produk_c = $penjualan_realisasi_produk_c['price'];

			$penjualan_realisasi_produk_d = $this->db->select('p.nama_produk, SUM(pp.display_price) as price, SUM(pp.display_volume) as volume')
			->from('pmm_productions pp')
			->join('produk p', 'pp.product_id = p.id','left')
			->join('pmm_sales_po ppo', 'pp.salesPo_id = ppo.id','left')
			->where("(pp.date_production between '$date1' and '$date2')")
			->where("pp.status = 'PUBLISH'")
			->where("pp.product_id = 11")
			->where("ppo.status in ('OPEN','CLOSED')")
			->group_by("pp.product_id")
			->order_by('p.nama_produk','asc')
			->get()->row_array();
			$volume_realisasi_produk_d = $penjualan_realisasi_produk_d['volume'];
			$nilai_realisasi_produk_d = $penjualan_realisasi_produk_d['price'];

			$penjualan_realisasi_produk_e = $this->db->select('p.nama_produk, SUM(pp.display_price) as price, SUM(pp.display_volume) as volume')
			->from('pmm_productions pp')
			->join('produk p', 'pp.product_id = p.id','left')
			->join('pmm_sales_po ppo', 'pp.salesPo_id = ppo.id','left')
			->where("(pp.date_production between '$date1' and '$date2')")
			->where("pp.status = 'PUBLISH'")
			->where("pp.product_id = 41")
			->where("ppo.status in ('OPEN','CLOSED')")
			->group_by("pp.product_id")
			->order_by('p.nama_produk','asc')
			->get()->row_array();
			$volume_realisasi_produk_e = $penjualan_realisasi_produk_e['volume'];
			$nilai_realisasi_produk_e = $penjualan_realisasi_produk_e['price'];

			$total_realisasi_volume = $volume_realisasi_produk_a + $volume_realisasi_produk_b + $volume_realisasi_produk_c + $volume_realisasi_produk_d + $volume_realisasi_produk_e;
			$total_realisasi_nilai = $nilai_realisasi_produk_a + $nilai_realisasi_produk_b + $nilai_realisasi_produk_c + $nilai_realisasi_produk_d + $nilai_realisasi_produk_e;
			?>
			<!-- REALISASI SD. SAAT INI -->

			<!-- REALISASI BIAYA -->
			<?php
			//BAHAN
			$akumulasi = $this->db->select('pp.date_akumulasi, pp.total_nilai_keluar as total_nilai_keluar')
			->from('akumulasi pp')
			->where("(pp.date_akumulasi between '$date1' and '$date2')")
			->get()->result_array();

			$total_akumulasi = 0;

			foreach ($akumulasi as $a){
				$total_akumulasi += $a['total_nilai_keluar'];
			}

			$total_bahan_akumulasi = $total_akumulasi;
			//END BAHAN
			?>

			<?php
			//ALAT
			$nilai_alat = $this->db->select('SUM(prm.display_price) as nilai')
			->from('pmm_receipt_material prm')
			->join('pmm_purchase_order po', 'prm.purchase_order_id = po.id','left')
			->join('produk p', 'prm.material_id = p.id','left')
			->where("(prm.date_receipt between '$date1' and '$date2')")
			->where("p.kategori_produk = '5'")
			->where("po.status in ('PUBLISH','CLOSED')")
			->get()->row_array();

			$akumulasi_bbm = $this->db->select('pp.date_akumulasi, pp.total_nilai_keluar_2 as total_nilai_keluar_2')
			->from('akumulasi pp')
			->where("(pp.date_akumulasi between '$date1' and '$date2')")
			->get()->result_array();

			$total_akumulasi_bbm = 0;

			foreach ($akumulasi_bbm as $b){
				$total_akumulasi_bbm += $b['total_nilai_keluar_2'];
			}

			$total_nilai_bbm = $total_akumulasi_bbm;
			$total_insentif_tm = 0;
			$insentif_tm = $this->db->select('sum(pdb.debit) as total')
			->from('pmm_jurnal_umum pb ')
			->join('pmm_detail_jurnal pdb','pb.id = pdb.jurnal_id','left')
			->where("pdb.akun = 220")
			->where("pb.status = 'PAID'")
			->where("(pb.tanggal_transaksi between '$date1' and '$date2')")
			->get()->row_array();
			$total_insentif_tm = $insentif_tm['total'];

			$total_alat_realisasi = $nilai_alat['nilai'] + $total_akumulasi_bbm + $total_insentif_tm;
			//END ALAT
			?>

			<?php
			//OVERHEAD
			$overhead_15_realisasi = $this->db->select('sum(pdb.jumlah) as total')
			->from('pmm_biaya pb ')
			->join('pmm_detail_biaya pdb','pb.id = pdb.biaya_id','left')
			->join('pmm_coa c','pdb.akun = c.id','left')
			->where('c.coa_category',15)
			->where("c.id <> 168 ") //Biaya Diskonto Bank
			->where("c.id <> 219 ") //Biaya Alat Batching Plant 
			->where("c.id <> 220 ") //Biaya Alat Truck Mixer
			->where("c.id <> 221 ") //Biaya Alat Wheel Loader
			->where("c.id <> 228 ") //Biaya Persiapan
			->where("c.id <> 505 ") //Biaya Oli
			->where("pb.status = 'PAID'")
			->where("(pb.tanggal_transaksi between '$date1' and '$date2')")
			->get()->row_array();

			$overhead_jurnal_15_realisasi = $this->db->select('sum(pdb.debit) as total')
			->from('pmm_jurnal_umum pb ')
			->join('pmm_detail_jurnal pdb','pb.id = pdb.jurnal_id','left')
			->join('pmm_coa c','pdb.akun = c.id','left')
			->where('c.coa_category',15)
			->where("c.id <> 168 ") //Biaya Diskonto Bank
			->where("c.id <> 219 ") //Biaya Alat Batching Plant 
			->where("c.id <> 220 ") //Biaya Alat Truck Mixer
			->where("c.id <> 221 ") //Biaya Alat Wheel Loader
			->where("c.id <> 228 ") //Biaya Persiapan
			->where("c.id <> 505 ") //Biaya Oli
			->where("pb.status = 'PAID'")
			->where("(pb.tanggal_transaksi between '$date1' and '$date2')")
			->get()->row_array();

			$total_overhead_realisasi =  $overhead_15_realisasi['total'] + $overhead_jurnal_15_realisasi['total'];
			?>

			<?php
			//DISKONTO
			$diskonto_realisasi = $this->db->select('sum(pdb.jumlah) as total')
			->from('pmm_biaya pb ')
			->join('pmm_detail_biaya pdb','pb.id = pdb.biaya_id','left')
			->join('pmm_coa c','pdb.akun = c.id','left')
			->where("pdb.akun = 168")
			->where("pb.status = 'PAID'")
			->where("(pb.tanggal_transaksi between '$date1' and '$date2')")
			->get()->row_array();

			$total_diskonto_realisasi = $diskonto_realisasi['total'];
			//DISKONTO
			?>

			<?php
			//PERSIAPAN
			$persiapan_biaya_realisasi = $this->db->select('sum(pdb.jumlah) as total')
			->from('pmm_biaya pb ')
			->join('pmm_detail_biaya pdb','pb.id = pdb.biaya_id','left')
			->join('pmm_coa c','pdb.akun = c.id','left')
			->where("pdb.akun = 228")
			->where("pb.status = 'PAID'")
			->where("(pb.tanggal_transaksi between '$date1' and '$date2')")
			->get()->row_array();

			$persiapan_jurnal_realisasi = $this->db->select('sum(pdb.debit) as total')
			->from('pmm_jurnal_umum pb ')
			->join('pmm_detail_jurnal pdb','pb.id = pdb.jurnal_id','left')
			->join('pmm_coa c','pdb.akun = c.id','left')
			->where("pdb.akun = 228")
			->where("pb.status = 'PAID'")
			->where("(pb.tanggal_transaksi between '$date1' and '$date2')")
			->get()->row_array();

			$total_persiapan_realisasi = $persiapan_biaya_realisasi['total'] + $persiapan_jurnal_realisasi['total'];
			$total_biaya_realisasi = $total_bahan_akumulasi + $total_alat_realisasi + $total_overhead_realisasi + $total_diskonto_realisasi;
			//END PERSIAPAN
			?>
			<!-- REALISASI BIAYA -->

			<!-- SISA -->
			<?php
			$sisa_volume_produk_a = $volume_rap_produk_a - $volume_realisasi_produk_a;
			$sisa_volume_produk_b = $volume_rap_produk_b - $volume_realisasi_produk_b;
			$sisa_volume_produk_c = $volume_rap_produk_c - $volume_realisasi_produk_c;
			$sisa_volume_produk_d = $volume_rap_produk_d - $volume_realisasi_produk_d;
			$sisa_volume_produk_e = $volume_rap_produk_e - $volume_realisasi_produk_e;

			$total_sisa_volume_all_produk = $sisa_volume_produk_a + $sisa_volume_produk_b + $sisa_volume_produk_c + $sisa_volume_produk_d + $sisa_volume_produk_e;
			$total_sisa_nilai_all_produk = $total_rap_nilai - $total_realisasi_nilai;

			$sisa_biaya_bahan = $total_rap_biaya_bahan - $total_bahan_akumulasi;
			$sisa_biaya_alat = $total_rap_biaya_alat - $total_alat_realisasi;
			$sisa_overhead = $total_rap_overhead - $total_overhead_realisasi;
			$sisa_biaya_bank = $total_rap_biaya_bank - $total_diskonto_realisasi;
			?>
			<!-- SISA -->

			<!-- TOTAL -->
			<?php
			$total_laba_rap = $total_rap_nilai - $total_biaya_rap_biaya;
			$total_laba_realisasi = $total_realisasi_nilai - $total_biaya_realisasi;

			$sisa_biaya_realisasi = $total_biaya_rap_biaya - $total_biaya_realisasi;
			$presentase_realisasi = ($total_laba_realisasi / $total_realisasi_nilai) * 100;
			?>
			<!-- TOTAL -->

			<tr class="table-active4">
				<th width="5%" class="text-center">NO.</th>
				<th class="text-center">URAIAN</th>
				<th class="text-center">SATUAN</th>
				<th class="text-center">RENCANA</th>
				<th class="text-center">REALISASI</th>
				<th class="text-center">EVALUASI</th>
	        </tr>
			<tr class="table-active2">
				<th class="text-left" colspan="6">RENCANA PRODUKSI & PENDAPATAN USAHA</th>
			</tr>
			<?php
				$styleColorA = $sisa_volume_produk_a < 0 ? 'color:red' : 'color:black';
				$styleColorB = $sisa_volume_produk_b < 0 ? 'color:red' : 'color:black';
				$styleColorC = $sisa_volume_produk_c < 0 ? 'color:red' : 'color:black';
				$styleColorD = $sisa_volume_produk_d < 0 ? 'color:red' : 'color:black';
				$styleColorE = $total_sisa_volume_all_produk < 0 ? 'color:red' : 'color:black';
				$styleColorF = $total_sisa_nilai_all_produk < 0 ? 'color:red' : 'color:black';
				$styleColorG = $sisa_biaya_bahan < 0 ? 'color:red' : 'color:black';
				$styleColorH = $sisa_biaya_alat < 0 ? 'color:red' : 'color:black';
				$styleColorI = $sisa_overhead < 0 ? 'color:red' : 'color:black';
				$styleColorJ = $sisa_biaya_bank < 0 ? 'color:red' : 'color:black';
				$styleColorL = $sisa_biaya_realisasi < 0 ? 'color:red' : 'color:black';
				$styleColorM = $total_laba_realisasi < 0 ? 'color:red' : 'color:black';
				$styleColorN = $sisa_volume_produk_e < 0 ? 'color:red' : 'color:black';
				$styleColorO = $presentase_realisasi < 0 ? 'color:red' : 'color:black';
			?>
			<tr class="table-active3">
				<th class="text-center">1.</th>
				<th class="text-left">Beton K 125 (10±2)</th>
				<th class="text-center">M3</th>
				<th class="text-right"><?php echo number_format($volume_rap_produk_a,2,',','.');?></th>
				<th class="text-right"><?php echo number_format($volume_realisasi_produk_a,2,',','.');?></th>
				<th class="text-right" style="<?php echo $styleColorA ?>"><?php echo number_format($sisa_volume_produk_a,2,',','.');?></th>
			</tr>
			<tr class="table-active3">
				<th class="text-center">2.</th>
				<th class="text-left">Beton K 225 (10±2)</th>
				<th class="text-center">M3</th>
				<th class="text-right"><?php echo number_format($volume_rap_produk_b,2,',','.');?></th>
				<th class="text-right"><?php echo number_format($volume_realisasi_produk_b,2,',','.');?></th>
				<th class="text-right" style="<?php echo $styleColorB ?>"><?php echo number_format($sisa_volume_produk_b,2,',','.');?></th>
			</tr>
			<tr class="table-active3">
				<th class="text-center">3.</th>
				<th class="text-left">Beton K 250 (10±2)</th>
				<th class="text-center">M3</th>
				<th class="text-right"><?php echo number_format($volume_rap_produk_c,2,',','.');?></th>
				<th class="text-right"><?php echo number_format($volume_realisasi_produk_c,2,',','.');?></th>
				<th class="text-right" style="<?php echo $styleColorC ?>"><?php echo number_format($sisa_volume_produk_c,2,',','.');?></th>
			</tr>
			<tr class="table-active3">
				<th class="text-center">4.</th>
				<th class="text-left">Beton K 250 (18±2)</th>
				<th class="text-center">M3</th>
				<th class="text-right"><?php echo number_format($volume_rap_produk_d,2,',','.');?></th>
				<th class="text-right"><?php echo number_format($volume_realisasi_produk_d,2,',','.');?></th>
				<th class="text-right" style="<?php echo $styleColorD ?>"><?php echo number_format($sisa_volume_produk_d,2,',','.');?></th>
			</tr>
			<tr class="table-active3">
				<th class="text-center">5.</th>
				<th class="text-left">Beton K 300 (10±2)</th>
				<th class="text-center">M3</th>
				<th class="text-right"><?php echo number_format($volume_rap_produk_e,2,',','.');?></th>
				<th class="text-right"><?php echo number_format($volume_realisasi_produk_e,2,',','.');?></th>
				<th class="text-right" style="<?php echo $styleColorN ?>"><?php echo number_format($sisa_volume_produk_e,2,',','.');?></th>
			</tr>
			<tr class="table-active2">
				<th class="text-right" colspan="2">TOTAL VOLUME</th>
				<th class="text-center">M3</th>
				<th class="text-right"><?php echo number_format($total_rap_volume,2,',','.');?></th>
				<th class="text-right"><?php echo number_format($total_realisasi_volume,2,',','.');?></th>
				<th class="text-right" style="<?php echo $styleColorE ?>"><?php echo number_format($total_sisa_volume_all_produk,2,',','.');?></th>
			</tr>
			<tr class="table-active2">
				<th class="text-right" colspan="2">PENDAPATAN USAHA</th>
				<th class="text-center"></th>
				<th class="text-right"><?php echo number_format($total_rap_nilai,0,',','.');?></th>
				<th class="text-right"><?php echo number_format($total_realisasi_nilai,0,',','.');?></th>
				<th class="text-right" style="<?php echo $styleColorF ?>"><?php echo number_format($total_sisa_nilai_all_produk,0,',','.');?></th>
			</tr>
			<tr class="table-active2">
				<th class="text-left" colspan="6">BIAYA</th>
			</tr>
			<tr class="table-active3">
				<th class="text-center">1.</th>
				<th class="text-left">Bahan</th>
				<th class="text-center">LS</th>
				<th class="text-right"><?php echo number_format($total_rap_biaya_bahan,0,',','.');?></th>
				<th class="text-right"><a target="_blank" href="<?= base_url("laporan/cetak_evaluasi_bahan?filter_date=".$filter_date = date('d F Y',strtotime($arr_filter_date[0])).' - '.date('d F Y',strtotime($arr_filter_date[1]))) ?>"><?php echo number_format($total_bahan_akumulasi,0,',','.');?></a></th>
				<th class="text-right" style="<?php echo $styleColorG ?>"><?php echo number_format($sisa_biaya_bahan,0,',','.');?></th>
			</tr>
			<tr class="table-active3">
				<th class="text-center">2.</th>
				<th class="text-left">Alat</th>
				<th class="text-center">LS</th>
				<th class="text-right"><?php echo number_format($total_rap_biaya_alat,0,',','.');?></th>
				<th class="text-right"><a target="_blank" href="<?= base_url("laporan/cetak_laporan_evaluasi_alat?filter_date=".$filter_date = date('d F Y',strtotime($arr_filter_date[0])).' - '.date('d F Y',strtotime($arr_filter_date[1]))) ?>"><?php echo number_format($total_alat_realisasi,0,',','.');?></a></th>
				<th class="text-right" style="<?php echo $styleColorH ?>"><?php echo number_format($sisa_biaya_alat,0,',','.');?></th>
			</tr>
			<tr class="table-active3">
				<th class="text-center">3.</th>
				<th class="text-left">Overhead</th>
				<th class="text-center">LS</th>
				<th class="text-right"><?php echo number_format($total_rap_overhead,0,',','.');?></th>
				<th class="text-right"><a target="_blank" href="<?= base_url("laporan/cetak_overhead?filter_date=".$filter_date = date('d F Y',strtotime($arr_filter_date[0])).' - '.date('d F Y',strtotime($arr_filter_date[1]))) ?>"><?php echo number_format($total_overhead_realisasi,0,',','.');?></a></th>
				<th class="text-right" style="<?php echo $styleColorI ?>"><?php echo number_format($sisa_overhead,0,',','.');?></th>
			</tr>
			<tr class="table-active3">
				<th class="text-center">4.</th>
				<th class="text-left">Biaya Bank</th>
				<th class="text-center">LS</th>
				<th class="text-right"><?php echo number_format($total_rap_biaya_bank,0,',','.');?></th>
				<th class="text-right"><a target="_blank" href="<?= base_url("laporan/cetak_diskonto?filter_date=".$filter_date = date('d F Y',strtotime($arr_filter_date[0])).' - '.date('d F Y',strtotime($arr_filter_date[1]))) ?>"><?php echo number_format($total_diskonto_realisasi,0,',','.');?></a></th>
				<th class="text-right" style="<?php echo $styleColorJ ?>"><?php echo number_format($sisa_biaya_bank,0,',','.');?></th>
			</tr>
			<tr class="table-active2">
				<th class="text-right" colspan="2">JUMLAH</th>
				<th class="text-center"></th>
				<th class="text-right"><?php echo number_format($total_biaya_rap_biaya,0,',','.');?></th>
				<th class="text-right"><?php echo number_format($total_biaya_realisasi,0,',','.');?></th>
				<th class="text-right" style="<?php echo $styleColorL ?>"><?php echo number_format($sisa_biaya_realisasi,0,',','.');?></th>
			</tr>
			<tr class="table-active2">
				<th class="text-right" colspan="2">LABA</th>
				<th class="text-center"></th>
				<th class="text-right"><?php echo number_format($total_laba_rap,0,',','.');?></th>
				<th class="text-right" style="<?php echo $styleColorM ?>"><?php echo number_format($total_laba_realisasi,0,',','.');?></th>
				<th class="text-right"></th>
			</tr>
			<tr class="table-active2">
				<th class="text-right" colspan="2">PRESENTASE</th>
				<th class="text-center"></th>
				<th class="text-right"><?php echo number_format(($total_laba_rap / $total_rap_nilai) * 100,2,',','.');?> %</th>
				<th class="text-right" style="<?php echo $styleColorO ?>"><?php echo number_format($presentase_realisasi,2,',','.');?> %</th>
				<th class="text-right"></th>
			</tr>	
	    </table>
		<?php
	}

	public function prognosa_produksi($arr_date)
	{
		$data = array();
		
		$arr_date = $this->input->post('filter_date');
		$arr_filter_date = explode(' - ', $arr_date);
		$date1 = '';
		$date2 = '';

		if(count($arr_filter_date) == 2){
			$date1 	= date('Y-m-d',strtotime($arr_filter_date[0]));
			$date2 	= date('Y-m-d',strtotime($arr_filter_date[1]));
			$filter_date = date('d F Y',strtotime($arr_filter_date[0])).' - '.date('d F Y',strtotime($arr_filter_date[1]));
		}
		
		?>
		
		<table class="table table-bordered" width="100%">
			<style type="text/css">
				table tr.table-active-rak{
					background-color: #F0F0F0;
					font-size: 8px;
					font-weight: bold;
					color: black;
				}
					
				table tr.table-active2-rak{
					background-color: #E8E8E8;
					font-size: 8px;
					font-weight: bold;
				}
					
				table tr.table-active3-rak{
					font-size: 8px;
					background-color: #F0F0F0;
				}
					
				table tr.table-active4-rak{
					background-color: #666666;
					font-weight: bold;
					font-size: 8px;
					color: white;
				}
				table tr.table-active5-rak{
					background-color: #E8E8E8;
					text-decoration: underline;
					font-size: 8px;
					font-weight: bold;
					color: red;
				}
				table tr.table-activeago1-rak{
					background-color: #ffd966;
					font-weight: bold;
					font-size: 8px;
					color: black;
				}
				table tr.table-activeopening-rak{
					background-color: #2986cc;
					font-weight: bold;
					font-size: 8px;
					color: black;
				}
			</style>

			<?php
			//VOLUME RAP
			$date_now = date('Y-m-d');
			$date_end = date('2022-12-31');

			$rencana_kerja_2022_1 = $this->db->select('r.*')
			->from('rak r')
			->where("r.tanggal_rencana_kerja between '2021-12-30' and '2021-12-30'")
			->get()->row_array();

			$rencana_kerja_2022_2 = $this->db->select('r.*')
			->from('rak r')
			->where("r.tanggal_rencana_kerja between '2021-12-31' and '2021-12-31'")
			->get()->row_array();

			$volume_rap_2022_produk_a = $rencana_kerja_2022_1['vol_produk_a'] + $rencana_kerja_2022_2['vol_produk_a'];
			$volume_rap_2022_produk_b = $rencana_kerja_2022_1['vol_produk_b'] + $rencana_kerja_2022_2['vol_produk_b'];
			$volume_rap_2022_produk_c = $rencana_kerja_2022_1['vol_produk_c'] + $rencana_kerja_2022_2['vol_produk_c'];
			$volume_rap_2022_produk_d = $rencana_kerja_2022_1['vol_produk_d'] + $rencana_kerja_2022_2['vol_produk_d'];
			$volume_rap_2022_produk_e = $rencana_kerja_2022_1['vol_produk_e'] + $rencana_kerja_2022_2['vol_produk_e'];
			$total_rap_volume_2022 = $rencana_kerja_2022_1['vol_produk_a'] + $rencana_kerja_2022_1['vol_produk_b'] + $rencana_kerja_2022_1['vol_produk_c'] + $rencana_kerja_2022_1['vol_produk_d'] + $rencana_kerja_2022_1['vol_produk_e'] + $rencana_kerja_2022_2['vol_produk_a'] + $rencana_kerja_2022_2['vol_produk_b'] + $rencana_kerja_2022_2['vol_produk_c'] + $rencana_kerja_2022_2['vol_produk_d'] + $rencana_kerja_2022_2['vol_produk_e'];

			$price_produk_a_1 = $rencana_kerja_2022_1['vol_produk_a'] * $rencana_kerja_2022_1['price_a'];
			$price_produk_b_1 = $rencana_kerja_2022_1['vol_produk_b'] * $rencana_kerja_2022_1['price_b'];
			$price_produk_c_1 = $rencana_kerja_2022_1['vol_produk_c'] * $rencana_kerja_2022_1['price_c'];
			$price_produk_d_1 = $rencana_kerja_2022_1['vol_produk_d'] * $rencana_kerja_2022_1['price_d'];
			$price_produk_e_1 = $rencana_kerja_2022_1['vol_produk_e'] * $rencana_kerja_2022_1['price_e'];

			$price_produk_a_2 = $rencana_kerja_2022_2['vol_produk_a'] * $rencana_kerja_2022_2['price_a'];
			$price_produk_b_2 = $rencana_kerja_2022_2['vol_produk_b'] * $rencana_kerja_2022_2['price_b'];
			$price_produk_c_2 = $rencana_kerja_2022_2['vol_produk_c'] * $rencana_kerja_2022_2['price_c'];
			$price_produk_d_2 = $rencana_kerja_2022_2['vol_produk_d'] * $rencana_kerja_2022_2['price_d'];
			$price_produk_e_2 = $rencana_kerja_2022_2['vol_produk_e'] * $rencana_kerja_2022_2['price_e'];

			$nilai_jual_all_2022 = $price_produk_a_1 + $price_produk_b_1 + $price_produk_c_1 + $price_produk_d_1 + $price_produk_e_1 + $price_produk_a_2 + $price_produk_b_2 + $price_produk_c_2 + $price_produk_d_2 + $price_produk_e_2;
			$total_rap_nilai_2022 = $nilai_jual_all_2022;

			//BIAYA RAP 2022
			$total_rap_2022_biaya_bahan = $rencana_kerja_2022_1['biaya_bahan'] + $rencana_kerja_2022_2['biaya_bahan'];
			$total_rap_2022_biaya_alat = $rencana_kerja_2022_1['biaya_alat'] + $rencana_kerja_2022_2['biaya_alat'];
			$total_rap_2022_overhead = $rencana_kerja_2022_1['overhead'] + $rencana_kerja_2022_2['overhead'];
			$total_rap_2022_diskonto = ($total_rap_nilai_2022 * 3) / 100;
			$total_biaya_rap_2022_biaya = $total_rap_2022_biaya_bahan + $total_rap_2022_biaya_alat + $total_rap_2022_overhead + $total_rap_2022_diskonto;
			?>
			
			<?php
			//AKUMULASI
			$last_opname_start = date('Y-m-01', (strtotime($date_now)));
			$last_opname = date('Y-m-d', strtotime('-1 days', strtotime($last_opname_start)));
			

			$penjualan_akumulasi_produk_a = $this->db->select('p.nama_produk, SUM(pp.display_price) as price, SUM(pp.display_volume) as volume')
			->from('pmm_productions pp')
			->join('produk p', 'pp.product_id = p.id','left')
			->join('pmm_sales_po ppo', 'pp.salesPo_id = ppo.id','left')
			->where("(pp.date_production <= '$last_opname')")
			->where("pp.status = 'PUBLISH'")
			->where("pp.product_id = 2")
			->where("ppo.status in ('OPEN','CLOSED')")
			->group_by("pp.product_id")
			->order_by('p.nama_produk','asc')
			->get()->row_array();
			$volume_akumulasi_produk_a = $penjualan_akumulasi_produk_a['volume'];
			$nilai_akumulasi_produk_a = $penjualan_akumulasi_produk_a['price'];

			$penjualan_akumulasi_produk_b = $this->db->select('p.nama_produk, SUM(pp.display_price) as price, SUM(pp.display_volume) as volume')
			->from('pmm_productions pp')
			->join('produk p', 'pp.product_id = p.id','left')
			->join('pmm_sales_po ppo', 'pp.salesPo_id = ppo.id','left')
			->where("(pp.date_production <= '$last_opname')")
			->where("pp.status = 'PUBLISH'")
			->where("pp.product_id = 1")
			->where("ppo.status in ('OPEN','CLOSED')")
			->group_by("pp.product_id")
			->order_by('p.nama_produk','asc')
			->get()->row_array();
			$volume_akumulasi_produk_b = $penjualan_akumulasi_produk_b['volume'];
			$nilai_akumulasi_produk_b = $penjualan_akumulasi_produk_b['price'];

			$penjualan_akumulasi_produk_c = $this->db->select('p.nama_produk, SUM(pp.display_price) as price, SUM(pp.display_volume) as volume')
			->from('pmm_productions pp')
			->join('produk p', 'pp.product_id = p.id','left')
			->join('pmm_sales_po ppo', 'pp.salesPo_id = ppo.id','left')
			->where("(pp.date_production <= '$last_opname')")
			->where("pp.status = 'PUBLISH'")
			->where("pp.product_id = 3")
			->where("ppo.status in ('OPEN','CLOSED')")
			->group_by("pp.product_id")
			->order_by('p.nama_produk','asc')
			->get()->row_array();
			$volume_akumulasi_produk_c = $penjualan_akumulasi_produk_c['volume'];
			$nilai_akumulasi_produk_c = $penjualan_akumulasi_produk_c['price'];

			$penjualan_akumulasi_produk_d = $this->db->select('p.nama_produk, SUM(pp.display_price) as price, SUM(pp.display_volume) as volume')
			->from('pmm_productions pp')
			->join('produk p', 'pp.product_id = p.id','left')
			->join('pmm_sales_po ppo', 'pp.salesPo_id = ppo.id','left')
			->where("(pp.date_production <= '$last_opname')")
			->where("pp.status = 'PUBLISH'")
			->where("pp.product_id = 11")
			->where("ppo.status in ('OPEN','CLOSED')")
			->group_by("pp.product_id")
			->order_by('p.nama_produk','asc')
			->get()->row_array();
			$volume_akumulasi_produk_d = $penjualan_akumulasi_produk_d['volume'];
			$nilai_akumulasi_produk_d = $penjualan_akumulasi_produk_d['price'];

			$penjualan_akumulasi_produk_e = $this->db->select('p.nama_produk, SUM(pp.display_price) as price, SUM(pp.display_volume) as volume')
			->from('pmm_productions pp')
			->join('produk p', 'pp.product_id = p.id','left')
			->join('pmm_sales_po ppo', 'pp.salesPo_id = ppo.id','left')
			->where("(pp.date_production <= '$last_opname')")
			->where("pp.status = 'PUBLISH'")
			->where("pp.product_id = 41")
			->where("ppo.status in ('OPEN','CLOSED')")
			->group_by("pp.product_id")
			->order_by('p.nama_produk','asc')
			->get()->row_array();
			$volume_akumulasi_produk_e = $penjualan_akumulasi_produk_e['volume'];
			$nilai_akumulasi_produk_e = $penjualan_akumulasi_produk_e['price'];

			$total_akumulasi_volume = $volume_akumulasi_produk_a + $volume_akumulasi_produk_b + $volume_akumulasi_produk_c + $volume_akumulasi_produk_d + $volume_akumulasi_produk_e;
			$total_akumulasi_nilai = $nilai_akumulasi_produk_a + $nilai_akumulasi_produk_b + $nilai_akumulasi_produk_c + $nilai_akumulasi_produk_d + $nilai_akumulasi_produk_e;
		
			//AKUMULASI BIAYA
			//BAHAN
			$akumulasi = $this->db->select('pp.date_akumulasi, pp.total_nilai_keluar as total_nilai_keluar')
			->from('akumulasi pp')
			->where("(pp.date_akumulasi <= '$last_opname')")
			->get()->result_array();

			$total_akumulasi = 0;

			foreach ($akumulasi as $a){
				$total_akumulasi += $a['total_nilai_keluar'];
			}

			$total_bahan_akumulasi = $total_akumulasi;
		
			//ALAT
			$nilai_alat = $this->db->select('SUM(prm.display_price) as nilai')
			->from('pmm_receipt_material prm')
			->join('pmm_purchase_order po', 'prm.purchase_order_id = po.id','left')
			->join('produk p', 'prm.material_id = p.id','left')
			->where("(prm.date_receipt <= '$last_opname')")
			->where("p.kategori_produk = '5'")
			->where("po.status in ('PUBLISH','CLOSED')")
			->get()->row_array();

			$akumulasi_bbm = $this->db->select('pp.date_akumulasi, pp.total_nilai_keluar_2 as total_nilai_keluar_2')
			->from('akumulasi pp')
			->where("(pp.date_akumulasi <= '$last_opname')")
			->get()->result_array();

			$total_akumulasi_bbm = 0;

			foreach ($akumulasi_bbm as $b){
				$total_akumulasi_bbm += $b['total_nilai_keluar_2'];
			}

			$total_nilai_bbm = $total_akumulasi_bbm;

			$total_insentif_tm = 0;
			$insentif_tm = $this->db->select('sum(pdb.debit) as total')
			->from('pmm_jurnal_umum pb ')
			->join('pmm_detail_jurnal pdb','pb.id = pdb.jurnal_id','left')
			->where("pdb.akun = 220")
			->where("pb.status = 'PAID'")
			->where("(pb.tanggal_transaksi <= '$last_opname')")
			->get()->row_array();
			$total_insentif_tm = $insentif_tm['total'];

			$insentif_wl = $this->db->select('sum(pdb.debit) as total')
			->from('pmm_jurnal_umum pb ')
			->join('pmm_detail_jurnal pdb','pb.id = pdb.jurnal_id','left')
			->where("pdb.akun = 221")
			->where("pb.status = 'PAID'")
			->where("(pb.tanggal_transaksi <= '$last_opname')")
			->get()->row_array();
			$total_insentif_wl = $insentif_wl['total'];

			$total_alat_akumulasi = $nilai_alat['nilai'] + $total_akumulasi_bbm + $total_insentif_tm + $total_insentif_wl;

			//OVERHEAD
			$overhead_15_akumulasi = $this->db->select('sum(pdb.jumlah) as total')
			->from('pmm_biaya pb ')
			->join('pmm_detail_biaya pdb','pb.id = pdb.biaya_id','left')
			->join('pmm_coa c','pdb.akun = c.id','left')
			->where('c.coa_category',15)
			->where("c.id <> 168 ") //Biaya Diskonto Bank
			->where("c.id <> 219 ") //Biaya Alat Batching Plant 
			->where("c.id <> 220 ") //Biaya Alat Truck Mixer
			->where("c.id <> 221 ") //Biaya Alat Wheel Loader
			->where("c.id <> 228 ") //Biaya Persiapan
			->where("c.id <> 505 ") //Biaya Oli
			->where("pb.status = 'PAID'")
			->where("(pb.tanggal_transaksi <= '$last_opname')")
			->get()->row_array();

			$overhead_jurnal_15_akumulasi = $this->db->select('sum(pdb.debit) as total')
			->from('pmm_jurnal_umum pb ')
			->join('pmm_detail_jurnal pdb','pb.id = pdb.jurnal_id','left')
			->join('pmm_coa c','pdb.akun = c.id','left')
			->where('c.coa_category',15)
			->where("c.id <> 168 ") //Biaya Diskonto Bank
			->where("c.id <> 219 ") //Biaya Alat Batching Plant 
			->where("c.id <> 220 ") //Biaya Alat Truck Mixer
			->where("c.id <> 221 ") //Biaya Alat Wheel Loader
			->where("c.id <> 228 ") //Biaya Persiapan
			->where("c.id <> 505 ") //Biaya Oli
			->where("pb.status = 'PAID'")
			->where("(pb.tanggal_transaksi <= '$last_opname')")
			->get()->row_array();

			//DISKONTO
			$diskonto_akumulasi = $this->db->select('sum(pdb.jumlah) as total')
			->from('pmm_biaya pb ')
			->join('pmm_detail_biaya pdb','pb.id = pdb.biaya_id','left')
			->join('pmm_coa c','pdb.akun = c.id','left')
			->where("pdb.akun = 168")
			->where("pb.status = 'PAID'")
			->where("(pb.tanggal_transaksi <= '$last_opname')")
			->get()->row_array();

			$total_overhead_akumulasi =  $overhead_15_akumulasi['total'] + $overhead_jurnal_15_akumulasi['total'];
			$total_diskonto_akumulasi =  $diskonto_akumulasi['total'];
			$total_biaya_akumulasi = $total_bahan_akumulasi + $total_alat_akumulasi + $total_overhead_akumulasi + $total_diskonto_akumulasi;
			?>
			<!-- AKUMULASI BULAN TERAKHIR -->

			<?php
			$date_now = date('Y-m-d');

			//BULAN 1
			$date_1_awal = date('Y-m-01', (strtotime($date_now)));
			$date_1_akhir = date('Y-m-d', strtotime('-1 days +1 months', strtotime($date_1_awal)));

			$rencana_kerja_1 = $this->db->select('r.*')
			->from('rak r')
			->where("r.tanggal_rencana_kerja between '$date_1_awal' and '$date_1_akhir'")
			->get()->row_array();
			
			$volume_1_produk_a = $rencana_kerja_1['vol_produk_a'];
			$volume_1_produk_b = $rencana_kerja_1['vol_produk_b'];
			$volume_1_produk_c = $rencana_kerja_1['vol_produk_c'];
			$volume_1_produk_d = $rencana_kerja_1['vol_produk_d'];
			$volume_1_produk_e = $rencana_kerja_1['vol_produk_e'];

			$total_1_volume = $volume_1_produk_a + $volume_1_produk_b + $volume_1_produk_c + $volume_1_produk_d + $volume_1_produk_e;

			$nilai_jual_125_1 = $volume_1_produk_a * $rencana_kerja_1['price_a'];
			$nilai_jual_225_1 = $volume_1_produk_b * $rencana_kerja_1['price_b'];
			$nilai_jual_250_1 = $volume_1_produk_c * $rencana_kerja_1['price_c'];
			$nilai_jual_250_18_1 = $volume_1_produk_d * $rencana_kerja_1['price_d'];
			$nilai_jual_300_1 = $volume_1_produk_e * $rencana_kerja_1['price_e'];
			$nilai_jual_all_1 = $nilai_jual_125_1 + $nilai_jual_225_1 + $nilai_jual_250_1 + $nilai_jual_250_18_1 + $nilai_jual_300_1;

			$total_1_nilai = $nilai_jual_all_1;

			//VOLUME
			$volume_rencana_kerja_1_produk_a = $rencana_kerja_1['vol_produk_a'];
			$volume_rencana_kerja_1_produk_b = $rencana_kerja_1['vol_produk_b'];
			$volume_rencana_kerja_1_produk_c = $rencana_kerja_1['vol_produk_c'];
			$volume_rencana_kerja_1_produk_d = $rencana_kerja_1['vol_produk_d'];
			$volume_rencana_kerja_1_produk_e = $rencana_kerja_1['vol_produk_e'];

			//BIAYA

			//BIAYA BAHAN
			$komposisi_125_1 = $this->db->select('(r.vol_produk_a * pk.presentase_a) as komposisi_semen_125, (vol_produk_a * pk.presentase_b) as komposisi_pasir_125, (vol_produk_a * pk.presentase_c) as komposisi_batu1020_125, (vol_produk_a * pk.presentase_d) as komposisi_batu2030_125')
			->from('rak r')
			->join('pmm_agregat pk', 'r.komposisi_125 = pk.id','left')
			->where("r.tanggal_rencana_kerja between '$date_1_awal' and '$date_1_akhir'")
			->get()->result_array();

			$total_volume_semen_125_1 = 0;
			$total_volume_pasir_125_1 = 0;
			$total_volume_batu1020_125_1 = 0;
			$total_volume_batu2030_125_1 = 0;

			foreach ($komposisi_125_1 as $x){
				$total_volume_semen_125_1 = $x['komposisi_semen_125'];
				$total_volume_pasir_125_1 = $x['komposisi_pasir_125'];
				$total_volume_batu1020_125_1 = $x['komposisi_batu1020_125'];
				$total_volume_batu2030_125_1 = $x['komposisi_batu2030_125'];
			}

			$komposisi_225_1 = $this->db->select('(r.vol_produk_b * pk.presentase_a) as komposisi_semen_225, (vol_produk_b * pk.presentase_b) as komposisi_pasir_225, (vol_produk_b * pk.presentase_c) as komposisi_batu1020_225, (vol_produk_b * pk.presentase_d) as komposisi_batu2030_225')
			->from('rak r')
			->join('pmm_agregat pk', 'r.komposisi_225 = pk.id','left')
			->where("r.tanggal_rencana_kerja between '$date_1_awal' and '$date_1_akhir'")
			->get()->result_array();

			$total_volume_semen_225_1 = 0;
			$total_volume_pasir_225_1 = 0;
			$total_volume_batu1020_225_1 = 0;
			$total_volume_batu2030_225_1 = 0;

			foreach ($komposisi_225_1 as $x){
				$total_volume_semen_225_1 = $x['komposisi_semen_225'];
				$total_volume_pasir_225_1 = $x['komposisi_pasir_225'];
				$total_volume_batu1020_225_1 = $x['komposisi_batu1020_225'];
				$total_volume_batu2030_225_1 = $x['komposisi_batu2030_225'];
			}

			$komposisi_250_1 = $this->db->select('(r.vol_produk_c * pk.presentase_a) as komposisi_semen_250, (vol_produk_c * pk.presentase_b) as komposisi_pasir_250, (vol_produk_c * pk.presentase_c) as komposisi_batu1020_250, (vol_produk_c * pk.presentase_d) as komposisi_batu2030_250')
			->from('rak r')
			->join('pmm_agregat pk', 'r.komposisi_250 = pk.id','left')
			->where("r.tanggal_rencana_kerja between '$date_1_awal' and '$date_1_akhir'")
			->get()->result_array();

			$total_volume_semen_250_1 = 0;
			$total_volume_pasir_250_1 = 0;
			$total_volume_batu1020_250_1 = 0;
			$total_volume_batu2030_250_1 = 0;

			foreach ($komposisi_250_1 as $x){
				$total_volume_semen_250_1 = $x['komposisi_semen_250'];
				$total_volume_pasir_250_1 = $x['komposisi_pasir_250'];
				$total_volume_batu1020_250_1 = $x['komposisi_batu1020_250'];
				$total_volume_batu2030_250_1 = $x['komposisi_batu2030_250'];
			}

			$komposisi_250_2_1 = $this->db->select('(r.vol_produk_d * pk.presentase_a) as komposisi_semen_250_2, (vol_produk_d * pk.presentase_b) as komposisi_pasir_250_2, (vol_produk_d * pk.presentase_c) as komposisi_batu1020_250_2, (vol_produk_d * pk.presentase_d) as komposisi_batu2030_250_2')
			->from('rak r')
			->join('pmm_agregat pk', 'r.komposisi_250_2 = pk.id','left')
			->where("r.tanggal_rencana_kerja between '$date_1_awal' and '$date_1_akhir'")
			->get()->result_array();

			$total_volume_semen_250_2_1 = 0;
			$total_volume_pasir_250_2_1 = 0;
			$total_volume_batu1020_250_2_1 = 0;
			$total_volume_batu2030_250_2_1 = 0;

			foreach ($komposisi_250_2_1 as $x){
				$total_volume_semen_250_2_1 = $x['komposisi_semen_250_2'];
				$total_volume_pasir_250_2_1 = $x['komposisi_pasir_250_2'];
				$total_volume_batu1020_250_2_1 = $x['komposisi_batu1020_250_2'];
				$total_volume_batu2030_250_2_1 = $x['komposisi_batu2030_250_2'];
			}

			$komposisi_300_1 = $this->db->select('(r.vol_produk_e * pk.presentase_a) as komposisi_semen_300_1, (vol_produk_e * pk.presentase_b) as komposisi_pasir_300_1, (vol_produk_e * pk.presentase_c) as komposisi_batu1020_300_1, (vol_produk_e * pk.presentase_d) as komposisi_batu2030_300_1')
			->from('rak r')
			->join('pmm_agregat pk', 'r.komposisi_300 = pk.id','left')
			->where("r.tanggal_rencana_kerja between '$date_1_awal' and '$date_1_akhir'")
			->get()->result_array();

			$total_volume_semen_300_1 = 0;
			$total_volume_pasir_300_1 = 0;
			$total_volume_batu1020_300_1 = 0;
			$total_volume_batu2030_300_1 = 0;

			foreach ($komposisi_300_1 as $x){
				$total_volume_semen_300_1 = $x['komposisi_semen_300_1'];
				$total_volume_pasir_300_1 = $x['komposisi_pasir_300_1'];
				$total_volume_batu1020_300_1 = $x['komposisi_batu1020_300_1'];
				$total_volume_batu2030_300_1 = $x['komposisi_batu2030_300_1'];
			}

			$total_volume_semen_1 = $total_volume_semen_125_1 + $total_volume_semen_225_1 + $total_volume_semen_250_1 + $total_volume_semen_250_2_1 + $total_volume_semen_300_1;
			$total_volume_pasir_1 = $total_volume_pasir_125_1 + $total_volume_pasir_225_1 + $total_volume_pasir_250_1 + $total_volume_pasir_250_2_1 + $total_volume_pasir_300_1;
			$total_volume_batu1020_1 = $total_volume_batu1020_125_1 + $total_volume_batu1020_225_1 + $total_volume_batu1020_250_1 + $total_volume_batu1020_250_2_1 + $total_volume_batu1020_300_1;
			$total_volume_batu2030_1 = $total_volume_batu2030_125_1 + $total_volume_batu2030_225_1 + $total_volume_batu2030_250_1 + $total_volume_batu2030_250_2_1 + $total_volume_batu2030_300_1;

			$nilai_semen_1 = $total_volume_semen_1 * $rencana_kerja_1['harga_semen'];
			$nilai_pasir_1 = $total_volume_pasir_1 * $rencana_kerja_1['harga_pasir'];
			$nilai_batu1020_1 = $total_volume_batu1020_1 * $rencana_kerja_1['harga_batu1020'];
			$nilai_batu2030_1 = $total_volume_batu2030_1 * $rencana_kerja_1['harga_batu2030'];

			//BIAYA ALAT
			$rak_alat_1 = $this->db->select('r.*, (r.vol_produk_a + r.vol_produk_b + r.vol_produk_c + r.vol_produk_d) as total_produksi')
			->from('rak r')
			->where("r.tanggal_rencana_kerja between '$date_1_awal' and '$date_1_akhir'")
			->get()->row_array();

			$rak_alat_bp_1 = $rak_alat_1['penawaran_id_bp'];
			$rak_alat_bp_2_1 = $rak_alat_1['penawaran_id_bp_2'];
			$rak_alat_bp_3_1 = $rak_alat_1['penawaran_id_bp_3'];

			$rak_alat_tm_1 = $rak_alat_1['penawaran_id_tm'];
			$rak_alat_tm_2_1 = $rak_alat_1['penawaran_id_tm_2'];
			$rak_alat_tm_3_1 = $rak_alat_1['penawaran_id_tm_3'];
			$rak_alat_tm_4_1 = $rak_alat_1['penawaran_id_tm_4'];

			$rak_alat_wl_1 = $rak_alat_1['penawaran_id_wl'];
			$rak_alat_wl_2_1 = $rak_alat_1['penawaran_id_wl_2'];
			$rak_alat_wl_3_1 = $rakrak_alat_1_alat['penawaran_id_wl_3'];

			$rak_alat_tr_1 = $rak_alat_1['penawaran_id_tr'];
			$rak_alat_tr_2_1 = $rak_alat_1['penawaran_id_tr_2'];
			$rak_alat_tr_3_1 = $rak_alat_1['penawaran_id_tr_3'];

			$rak_alat_exc_1 = $rak_alat_1['penawaran_id_exc'];
			$rak_alat_dmp_4m3_1 = $rak_alat_1['penawaran_id_dmp_4m3'];
			$rak_alat_dmp_10m3_1 = $rak_alat_1['penawaran_id_dmp_10m3'];
			$rak_alat_sc_1 = $rak_alat_1['penawaran_id_sc'];
			$rak_alat_gns_1 = $rak_alat_1['penawaran_id_gns'];
			$rak_alat_wl_sc_1 = $rak_alat_1['penawaran_id_wl_sc'];

			$produk_bp_1 = $this->db->select('p.nama_produk, ppd.price, ppd.qty, pm.measure_name, ps.nama, sum(vol_produk_a + vol_produk_b + vol_produk_c + vol_produk_d) as total_vol_produksi')
			->from('pmm_penawaran_pembelian ppp')
			->join('pmm_penawaran_pembelian_detail ppd', 'ppp.id = ppd.penawaran_pembelian_id','left')
			->join('produk p', 'ppd.material_id = p.id','left')
			->join('pmm_measures pm', 'ppd.measure = pm.id','left')
			->join('penerima ps', 'ppp.supplier_id = ps.id','left')
			->join('rak r', 'ppp.id = r.penawaran_id_bp','left')
			->where("r.tanggal_rencana_kerja between '$date_1_awal' and '$date_1_akhir'")
			->where("ppp.id = '$rak_alat_bp_1'")
			->group_by('ppd.id')
			->order_by('p.nama_produk','asc')
			->get()->result_array();

			$total_price_bp_1 = 0;
			foreach ($produk_bp_1 as $x){
				$total_price_bp_1 += $x['total_vol_produksi'] * $x['price'];
			}

			$produk_bp_2_1 = $this->db->select('p.nama_produk, ppd.price, ppd.qty, pm.measure_name, ps.nama, sum(vol_produk_a + vol_produk_b + vol_produk_c + vol_produk_d) as total_vol_produksi')
			->from('pmm_penawaran_pembelian ppp')
			->join('pmm_penawaran_pembelian_detail ppd', 'ppp.id = ppd.penawaran_pembelian_id','left')
			->join('produk p', 'ppd.material_id = p.id','left')
			->join('pmm_measures pm', 'ppd.measure = pm.id','left')
			->join('penerima ps', 'ppp.supplier_id = ps.id','left')
			->join('rak r', 'ppp.id = r.penawaran_id_bp','left')
			->where("r.tanggal_rencana_kerja between '$date_1_awal' and '$date_1_akhir'")
			->where("ppp.id = '$rak_alat_bp_2_1'")
			->group_by('ppd.id')
			->order_by('p.nama_produk','asc')
			->get()->result_array();

			$total_price_bp_2_1 = 0;
			foreach ($produk_bp_2_1 as $x){
				$total_price_bp_2_1 += $x['total_vol_produksi'] * $x['price'];
			}

			$produk_bp_3_1 = $this->db->select('p.nama_produk, ppd.price, ppd.qty, pm.measure_name, ps.nama, sum(vol_produk_a + vol_produk_b + vol_produk_c + vol_produk_d) as total_vol_produksi')
			->from('pmm_penawaran_pembelian ppp')
			->join('pmm_penawaran_pembelian_detail ppd', 'ppp.id = ppd.penawaran_pembelian_id','left')
			->join('produk p', 'ppd.material_id = p.id','left')
			->join('pmm_measures pm', 'ppd.measure = pm.id','left')
			->join('penerima ps', 'ppp.supplier_id = ps.id','left')
			->join('rak r', 'ppp.id = r.penawaran_id_bp','left')
			->where("r.tanggal_rencana_kerja between '$date_1_awal' and '$date_1_akhir'")
			->where("ppp.id = '$rak_alat_bp_3_1'")
			->group_by('ppd.id')
			->order_by('p.nama_produk','asc')
			->get()->result_array();

			$total_price_bp_3_1 = 0;
			foreach ($produk_bp_3_1 as $x){
				$total_price_bp_3_1 += $x['total_vol_produksi'] * $x['price'];
			}

			$produk_tm_1 = $this->db->select('p.nama_produk, ppd.price, ppd.qty, pm.measure_name')
			->from('pmm_penawaran_pembelian ppp')
			->join('pmm_penawaran_pembelian_detail ppd', 'ppp.id = ppd.penawaran_pembelian_id','left')
			->join('produk p', 'ppd.material_id = p.id','left')
			->join('pmm_measures pm', 'ppd.measure = pm.id','left')
			->where("ppp.id = '$rak_alat_tm_1'")
			->group_by('ppd.id')
			->order_by('p.nama_produk','asc')
			->get()->result_array();

			$total_price_tm_1 = 0;
			foreach ($produk_tm_1 as $x){
				$total_price_tm_1 += $x['qty'] * $x['price'];
			}

			$produk_tm_2_1 = $this->db->select('p.nama_produk, ppd.price, ppd.qty, pm.measure_name')
			->from('pmm_penawaran_pembelian ppp')
			->join('pmm_penawaran_pembelian_detail ppd', 'ppp.id = ppd.penawaran_pembelian_id','left')
			->join('produk p', 'ppd.material_id = p.id','left')
			->join('pmm_measures pm', 'ppd.measure = pm.id','left')
			->where("ppp.id = '$rak_alat_tm_2_1'")
			->group_by('ppd.id')
			->order_by('p.nama_produk','asc')
			->get()->result_array();

			$total_price_tm_2_1 = 0;
			foreach ($produk_tm_2_1 as $x){
				$total_price_tm_2_1 += $x['qty'] * $x['price'];
			}

			$produk_tm_3_1 = $this->db->select('p.nama_produk, ppd.price, ppd.qty, pm.measure_name')
			->from('pmm_penawaran_pembelian ppp')
			->join('pmm_penawaran_pembelian_detail ppd', 'ppp.id = ppd.penawaran_pembelian_id','left')
			->join('produk p', 'ppd.material_id = p.id','left')
			->join('pmm_measures pm', 'ppd.measure = pm.id','left')
			->where("ppp.id = '$rak_alat_tm_3_1'")
			->group_by('ppd.id')
			->order_by('p.nama_produk','asc')
			->get()->result_array();

			$total_price_tm_3_1 = 0;
			foreach ($produk_tm_3_1 as $x){
				$total_price_tm_3_1 += $x['qty'] * $x['price'];
			}

			$produk_tm_4_1 = $this->db->select('p.nama_produk, ppd.price, ppd.qty, pm.measure_name')
			->from('pmm_penawaran_pembelian ppp')
			->join('pmm_penawaran_pembelian_detail ppd', 'ppp.id = ppd.penawaran_pembelian_id','left')
			->join('produk p', 'ppd.material_id = p.id','left')
			->join('pmm_measures pm', 'ppd.measure = pm.id','left')
			->where("ppp.id = '$rak_alat_tm_4_1'")
			->group_by('ppd.id')
			->order_by('p.nama_produk','asc')
			->get()->result_array();

			$total_price_tm_4_1 = 0;
			foreach ($produk_tm_4_1 as $x){
				$total_price_tm_4_1 += $x['qty'] * $x['price'];
			}

			$produk_wl_1 = $this->db->select('p.nama_produk, ppd.price, ppd.qty, pm.measure_name')
			->from('pmm_penawaran_pembelian ppp')
			->join('pmm_penawaran_pembelian_detail ppd', 'ppp.id = ppd.penawaran_pembelian_id','left')
			->join('produk p', 'ppd.material_id = p.id','left')
			->join('pmm_measures pm', 'ppd.measure = pm.id','left')
			->where("ppp.id = '$rak_alat_wl_1'")
			->group_by('ppd.id')
			->order_by('p.nama_produk','asc')
			->get()->result_array();

			$total_price_wl_1 = 0;
			foreach ($produk_wl_1 as $x){
				$total_price_wl_1 += $x['qty'] * $x['price'];
			}

			$produk_wl_2_1 = $this->db->select('p.nama_produk, ppd.price, ppd.qty, pm.measure_name')
			->from('pmm_penawaran_pembelian ppp')
			->join('pmm_penawaran_pembelian_detail ppd', 'ppp.id = ppd.penawaran_pembelian_id','left')
			->join('produk p', 'ppd.material_id = p.id','left')
			->join('pmm_measures pm', 'ppd.measure = pm.id','left')
			->where("ppp.id = '$rak_alat_wl_2_1'")
			->group_by('ppd.id')
			->order_by('p.nama_produk','asc')
			->get()->result_array();

			$total_price_wl_2_1 = 0;
			foreach ($produk_wl_2_1 as $x){
				$total_price_wl_2_1 += $x['qty'] * $x['price'];
			}

			$produk_wl_3_1 = $this->db->select('p.nama_produk, ppd.price, ppd.qty, pm.measure_name')
			->from('pmm_penawaran_pembelian ppp')
			->join('pmm_penawaran_pembelian_detail ppd', 'ppp.id = ppd.penawaran_pembelian_id','left')
			->join('produk p', 'ppd.material_id = p.id','left')
			->join('pmm_measures pm', 'ppd.measure = pm.id','left')
			->where("ppp.id = '$rak_alat_wl_3_1'")
			->group_by('ppd.id')
			->order_by('p.nama_produk','asc')
			->get()->result_array();

			$total_price_wl_3_1 = 0;
			foreach ($produk_wl_3_1 as $x){
				$total_price_wl_3_1 += $x['qty'] * $x['price'];
			}

			$produk_tr_1 = $this->db->select('p.nama_produk, ppd.price, ppd.qty, pm.measure_name, ps.nama')
			->from('pmm_penawaran_pembelian ppp')
			->join('pmm_penawaran_pembelian_detail ppd', 'ppp.id = ppd.penawaran_pembelian_id','left')
			->join('produk p', 'ppd.material_id = p.id','left')
			->join('pmm_measures pm', 'ppd.measure = pm.id','left')
			->join('penerima ps', 'ppp.supplier_id = ps.id','left')
			->where("ppp.id = '$rak_alat_tr_1'")
			->group_by('ppd.id')
			->order_by('p.nama_produk','asc')
			->get()->result_array();

			$total_price_tr_1 = 0;
			foreach ($produk_tr_1 as $x){
				$total_price_tr_1 += $x['qty'] * $x['price'];
			}

			$produk_tr_2_1 = $this->db->select('p.nama_produk, ppd.price, ppd.qty, pm.measure_name, ps.nama')
			->from('pmm_penawaran_pembelian ppp')
			->join('pmm_penawaran_pembelian_detail ppd', 'ppp.id = ppd.penawaran_pembelian_id','left')
			->join('produk p', 'ppd.material_id = p.id','left')
			->join('pmm_measures pm', 'ppd.measure = pm.id','left')
			->join('penerima ps', 'ppp.supplier_id = ps.id','left')
			->where("ppp.id = '$rak_alat_tr_2_1'")
			->group_by('ppd.id')
			->order_by('p.nama_produk','asc')
			->get()->result_array();

			$total_price_tr_2_1 = 0;
			foreach ($produk_tr_2_1 as $x){
				$total_price_tr_2_1 += $x['qty'] * $x['price'];
			}

			$produk_tr_3_1 = $this->db->select('p.nama_produk, ppd.price, ppd.qty, pm.measure_name, ps.nama')
			->from('pmm_penawaran_pembelian ppp')
			->join('pmm_penawaran_pembelian_detail ppd', 'ppp.id = ppd.penawaran_pembelian_id','left')
			->join('produk p', 'ppd.material_id = p.id','left')
			->join('pmm_measures pm', 'ppd.measure = pm.id','left')
			->join('penerima ps', 'ppp.supplier_id = ps.id','left')
			->where("ppp.id = '$rak_alat_tr_3_1'")
			->group_by('ppd.id')
			->order_by('p.nama_produk','asc')
			->get()->result_array();

			$total_price_tr_3_1 = 0;
			foreach ($produk_tr_3_1 as $x){
				$total_price_tr_3_1 += $x['qty'] * $x['price'];
			}

			$produk_exc_1 = $this->db->select('p.nama_produk, ppd.price, ppd.qty, pm.measure_name, ps.nama')
			->from('pmm_penawaran_pembelian ppp')
			->join('pmm_penawaran_pembelian_detail ppd', 'ppp.id = ppd.penawaran_pembelian_id','left')
			->join('produk p', 'ppd.material_id = p.id','left')
			->join('pmm_measures pm', 'ppd.measure = pm.id','left')
			->join('penerima ps', 'ppp.supplier_id = ps.id','left')
			->where("ppp.id = '$rak_alat_exc_1'")
			->group_by('ppd.id')
			->order_by('p.nama_produk','asc')
			->get()->result_array();

			$total_price_exc_1 = 0;
			foreach ($produk_exc_1 as $x){
				$total_price_exc_1 += $x['qty'] * $x['price'];
			}

			$produk_dmp_4m3_1 = $this->db->select('p.nama_produk, ppd.price, ppd.qty, pm.measure_name, ps.nama')
			->from('pmm_penawaran_pembelian ppp')
			->join('pmm_penawaran_pembelian_detail ppd', 'ppp.id = ppd.penawaran_pembelian_id','left')
			->join('produk p', 'ppd.material_id = p.id','left')
			->join('pmm_measures pm', 'ppd.measure = pm.id','left')
			->join('penerima ps', 'ppp.supplier_id = ps.id','left')
			->where("ppp.id = '$rak_alat_dmp_4m3_1'")
			->group_by('ppd.id')
			->order_by('p.nama_produk','asc')
			->get()->result_array();

			$total_price_dmp_4m3_1 = 0;
			foreach ($produk_dmp_4m3_1 as $x){
				$total_price_dmp_4m3_1 += $x['qty'] * $x['price'];
			}

			$produk_dmp_10m3_1 = $this->db->select('p.nama_produk, ppd.price, ppd.qty, pm.measure_name, ps.nama')
			->from('pmm_penawaran_pembelian ppp')
			->join('pmm_penawaran_pembelian_detail ppd', 'ppp.id = ppd.penawaran_pembelian_id','left')
			->join('produk p', 'ppd.material_id = p.id','left')
			->join('pmm_measures pm', 'ppd.measure = pm.id','left')
			->join('penerima ps', 'ppp.supplier_id = ps.id','left')
			->where("ppp.id = '$rak_alat_dmp_10m3_1'")
			->group_by('ppd.id')
			->order_by('p.nama_produk','asc')
			->get()->result_array();

			$total_price_dmp_10m3_1 = 0;
			foreach ($produk_dmp_10m3_1 as $x){
				$total_price_dmp_10m3_1 += $x['qty'] * $x['price'];
			}

			$produk_sc_1 = $this->db->select('p.nama_produk, ppd.price, ppd.qty, pm.measure_name, ps.nama')
			->from('pmm_penawaran_pembelian ppp')
			->join('pmm_penawaran_pembelian_detail ppd', 'ppp.id = ppd.penawaran_pembelian_id','left')
			->join('produk p', 'ppd.material_id = p.id','left')
			->join('pmm_measures pm', 'ppd.measure = pm.id','left')
			->join('penerima ps', 'ppp.supplier_id = ps.id','left')
			->where("ppp.id = '$rak_alat_sc_1'")
			->group_by('ppd.id')
			->order_by('p.nama_produk','asc')
			->get()->result_array();

			$total_price_sc_1 = 0;
			foreach ($produk_sc_1 as $x){
				$total_price_sc_1 += $x['qty'] * $x['price'];
			}

			$produk_gns_1 = $this->db->select('p.nama_produk, ppd.price, ppd.qty, pm.measure_name, ps.nama')
			->from('pmm_penawaran_pembelian ppp')
			->join('pmm_penawaran_pembelian_detail ppd', 'ppp.id = ppd.penawaran_pembelian_id','left')
			->join('produk p', 'ppd.material_id = p.id','left')
			->join('pmm_measures pm', 'ppd.measure = pm.id','left')
			->join('penerima ps', 'ppp.supplier_id = ps.id','left')
			->where("ppp.id = '$rak_alat_gns_1'")
			->group_by('ppd.id')
			->order_by('p.nama_produk','asc')
			->get()->result_array();

			$total_price_gns_1 = 0;
			foreach ($produk_gns_1 as $x){
				$total_price_gns_1 += $x['qty'] * $x['price'];
			}

			$produk_wl_sc_1 = $this->db->select('p.nama_produk, ppd.price, ppd.qty, pm.measure_name, ps.nama')
			->from('pmm_penawaran_pembelian ppp')
			->join('pmm_penawaran_pembelian_detail ppd', 'ppp.id = ppd.penawaran_pembelian_id','left')
			->join('produk p', 'ppd.material_id = p.id','left')
			->join('pmm_measures pm', 'ppd.measure = pm.id','left')
			->join('penerima ps', 'ppp.supplier_id = ps.id','left')
			->where("ppp.id = '$rak_alat_wl_sc_1'")
			->group_by('ppd.id')
			->order_by('p.nama_produk','asc')
			->get()->result_array();

			$total_price_wl_sc_1 = 0;
			foreach ($produk_wl_sc_1 as $x){
				$total_price_wl_sc_1 += $x['qty'] * $x['price'];
			}

			$total_volume_solar_1 = $rak_alat_1['vol_bbm_solar'];

			$total_1_biaya_bahan = $nilai_semen_1 + $nilai_pasir_1 + $nilai_batu1020_1 + $nilai_batu2030_1;
			$total_1_biaya_alat = ($total_price_bp_1 + $total_price_bp_2_1 + $total_price_bp_3_1) + ($total_price_tm_1 + $total_price_tm_2_1 + $total_price_tm_3_1 + $total_price_tm_4_1) + ($total_price_wl_1 + $total_price_wl_2_1 + $total_price_wl_3_1) + ($total_price_tr_1 + $total_price_tr_2_1 + $total_price_tr_3_1) + ($total_volume_solar_1 * $rak_alat_1['harga_solar']) + $rak_alat_1['insentif'] + $total_price_exc_1 + $total_price_dmp_4m3_1 + $total_price_dmp_10m3_1 + $total_price_sc_1 + $total_price_gns_1 + $total_price_wl_sc_1;
			$total_1_overhead = $rencana_kerja_1['overhead'];
			$total_1_diskonto =  ($total_1_nilai * 3) /100;
			$total_biaya_1_biaya = $total_1_biaya_bahan + $total_1_biaya_alat + $total_1_overhead + $total_1_diskonto;
			?>

			<?php
			//BULAN 2
			$date_2_awal = date('Y-m-d', strtotime('+1 days', strtotime($date_1_akhir)));
			$date_2_akhir = date('Y-m-d', strtotime('-1 days +1 months', strtotime($date_2_awal)));

			$rencana_kerja_2 = $this->db->select('r.*')
			->from('rak r')
			->where("r.tanggal_rencana_kerja between '$date_2_awal' and '$date_2_akhir'")
			->get()->row_array();

			$volume_2_produk_a = $rencana_kerja_2['vol_produk_a'];
			$volume_2_produk_b = $rencana_kerja_2['vol_produk_b'];
			$volume_2_produk_c = $rencana_kerja_2['vol_produk_c'];
			$volume_2_produk_d = $rencana_kerja_2['vol_produk_d'];
			$volume_2_produk_e = $rencana_kerja_2['vol_produk_e'];

			$total_2_volume = $volume_2_produk_a + $volume_2_produk_b + $volume_2_produk_c + $volume_2_produk_d + $volume_2_produk_e;

			$nilai_jual_125_2 = $volume_2_produk_a * $rencana_kerja_2['price_a'];
			$nilai_jual_225_2 = $volume_2_produk_b * $rencana_kerja_2['price_b'];
			$nilai_jual_250_2 = $volume_2_produk_c * $rencana_kerja_2['price_c'];
			$nilai_jual_250_18_2 = $volume_2_produk_d * $rencana_kerja_2['price_d'];
			$nilai_jual_300_2 = $volume_2_produk_e * $rencana_kerja_2['price_e'];
			$nilai_jual_all_2 = $nilai_jual_125_2 + $nilai_jual_225_2 + $nilai_jual_250_2 + $nilai_jual_250_18_2 + $nilai_jual_300_2;

			$total_2_nilai = $nilai_jual_all_2;

			//VOLUME
			$volume_rencana_kerja_2_produk_a = $rencana_kerja_2['vol_produk_a'];
			$volume_rencana_kerja_2_produk_b = $rencana_kerja_2['vol_produk_b'];
			$volume_rencana_kerja_2_produk_c = $rencana_kerja_2['vol_produk_c'];
			$volume_rencana_kerja_2_produk_d = $rencana_kerja_2['vol_produk_d'];
			$volume_rencana_kerja_2_produk_e = $rencana_kerja_2['vol_produk_e'];

			//BIAYA

			//BIAYA BAHAN
			$komposisi_125_2 = $this->db->select('(r.vol_produk_a * pk.presentase_a) as komposisi_semen_125, (vol_produk_a * pk.presentase_b) as komposisi_pasir_125, (vol_produk_a * pk.presentase_c) as komposisi_batu1020_125, (vol_produk_a * pk.presentase_d) as komposisi_batu2030_125')
			->from('rak r')
			->join('pmm_agregat pk', 'r.komposisi_125 = pk.id','left')
			->where("r.tanggal_rencana_kerja between '$date_2_awal' and '$date_2_akhir'")
			->get()->result_array();

			$total_volume_semen_125_2 = 0;
			$total_volume_pasir_125_2 = 0;
			$total_volume_batu1020_125_2 = 0;
			$total_volume_batu2030_125_2 = 0;

			foreach ($komposisi_125_2 as $x){
				$total_volume_semen_125_2 = $x['komposisi_semen_125'];
				$total_volume_pasir_125_2 = $x['komposisi_pasir_125'];
				$total_volume_batu1020_125_2 = $x['komposisi_batu1020_125'];
				$total_volume_batu2030_125_2 = $x['komposisi_batu2030_125'];
			}

			$komposisi_225_2 = $this->db->select('(r.vol_produk_b * pk.presentase_a) as komposisi_semen_225, (vol_produk_b * pk.presentase_b) as komposisi_pasir_225, (vol_produk_b * pk.presentase_c) as komposisi_batu1020_225, (vol_produk_b * pk.presentase_d) as komposisi_batu2030_225')
			->from('rak r')
			->join('pmm_agregat pk', 'r.komposisi_225 = pk.id','left')
			->where("r.tanggal_rencana_kerja between '$date_2_awal' and '$date_2_akhir'")
			->get()->result_array();

			$total_volume_semen_225_2 = 0;
			$total_volume_pasir_225_2 = 0;
			$total_volume_batu1020_225_2 = 0;
			$total_volume_batu2030_225_2 = 0;

			foreach ($komposisi_225_2 as $x){
				$total_volume_semen_225_2 = $x['komposisi_semen_225'];
				$total_volume_pasir_225_2 = $x['komposisi_pasir_225'];
				$total_volume_batu1020_225_2 = $x['komposisi_batu1020_225'];
				$total_volume_batu2030_225_2 = $x['komposisi_batu2030_225'];
			}

			$komposisi_250_2 = $this->db->select('(r.vol_produk_c * pk.presentase_a) as komposisi_semen_250, (vol_produk_c * pk.presentase_b) as komposisi_pasir_250, (vol_produk_c * pk.presentase_c) as komposisi_batu1020_250, (vol_produk_c * pk.presentase_d) as komposisi_batu2030_250')
			->from('rak r')
			->join('pmm_agregat pk', 'r.komposisi_250 = pk.id','left')
			->where("r.tanggal_rencana_kerja between '$date_2_awal' and '$date_2_akhir'")
			->get()->result_array();

			$total_volume_semen_250_2 = 0;
			$total_volume_pasir_250_2 = 0;
			$total_volume_batu1020_250_2 = 0;
			$total_volume_batu2030_250_2 = 0;

			foreach ($komposisi_250_2 as $x){
				$total_volume_semen_250_2 = $x['komposisi_semen_250'];
				$total_volume_pasir_250_2 = $x['komposisi_pasir_250'];
				$total_volume_batu1020_250_2 = $x['komposisi_batu1020_250'];
				$total_volume_batu2030_250_2 = $x['komposisi_batu2030_250'];
			}

			$komposisi_250_2_2 = $this->db->select('(r.vol_produk_d * pk.presentase_a) as komposisi_semen_250_2, (vol_produk_d * pk.presentase_b) as komposisi_pasir_250_2, (vol_produk_d * pk.presentase_c) as komposisi_batu1020_250_2, (vol_produk_d * pk.presentase_d) as komposisi_batu2030_250_2')
			->from('rak r')
			->join('pmm_agregat pk', 'r.komposisi_250_2 = pk.id','left')
			->where("r.tanggal_rencana_kerja between '$date_2_awal' and '$date_2_akhir'")
			->get()->result_array();

			$total_volume_semen_250_2_2 = 0;
			$total_volume_pasir_250_2_2 = 0;
			$total_volume_batu1020_250_2_2 = 0;
			$total_volume_batu2030_250_2_2 = 0;

			foreach ($komposisi_250_2_2 as $x){
				$total_volume_semen_250_2_2 = $x['komposisi_semen_250_2'];
				$total_volume_pasir_250_2_2 = $x['komposisi_pasir_250_2'];
				$total_volume_batu1020_250_2_2 = $x['komposisi_batu1020_250_2'];
				$total_volume_batu2030_250_2_2 = $x['komposisi_batu2030_250_2'];
			}

			$komposisi_300_2 = $this->db->select('(r.vol_produk_e * pk.presentase_a) as komposisi_semen_300_2, (vol_produk_e * pk.presentase_b) as komposisi_pasir_300_2, (vol_produk_e * pk.presentase_c) as komposisi_batu1020_300_2, (vol_produk_e * pk.presentase_d) as komposisi_batu2030_300_2')
			->from('rak r')
			->join('pmm_agregat pk', 'r.komposisi_300 = pk.id','left')
			->where("r.tanggal_rencana_kerja between '$date_2_awal' and '$date_2_akhir'")
			->get()->result_array();

			$total_volume_semen_300_2 = 0;
			$total_volume_pasir_300_2 = 0;
			$total_volume_batu1020_300_2 = 0;
			$total_volume_batu2030_300_2 = 0;

			foreach ($komposisi_300_2 as $x){
				$total_volume_semen_300_2 = $x['komposisi_semen_300_2'];
				$total_volume_pasir_300_2 = $x['komposisi_pasir_300_2'];
				$total_volume_batu1020_300_2 = $x['komposisi_batu1020_300_2'];
				$total_volume_batu2030_300_2 = $x['komposisi_batu2030_300_2'];
			}

			$total_volume_semen_2 = $total_volume_semen_125_2 + $total_volume_semen_225_2 + $total_volume_semen_250_2 + $total_volume_semen_250_2_2 + $total_volume_semen_300_2;
			$total_volume_pasir_2 = $total_volume_pasir_125_2 + $total_volume_pasir_225_2 + $total_volume_pasir_250_2 + $total_volume_pasir_250_2_2 + $total_volume_pasir_300_2;
			$total_volume_batu1020_2 = $total_volume_batu1020_125_2 + $total_volume_batu1020_225_2 + $total_volume_batu1020_250_2 + $total_volume_batu1020_250_2_2 + $total_volume_batu1020_300_2;
			$total_volume_batu2030_2 = $total_volume_batu2030_125_2 + $total_volume_batu2030_225_2 + $total_volume_batu2030_250_2 + $total_volume_batu2030_250_2_2 + $total_volume_batu2030_300_2;

			$nilai_semen_2 = $total_volume_semen_2 * $rencana_kerja_2['harga_semen'];
			$nilai_pasir_2 = $total_volume_pasir_2 * $rencana_kerja_2['harga_pasir'];
			$nilai_batu1020_2 = $total_volume_batu1020_2 * $rencana_kerja_2['harga_batu1020'];
			$nilai_batu2030_2 = $total_volume_batu2030_2 * $rencana_kerja_2['harga_batu2030'];

			//BIAYA ALAT
			$rak_alat_2 = $this->db->select('r.*, (r.vol_produk_a + r.vol_produk_b + r.vol_produk_c + r.vol_produk_d) as total_produksi')
			->from('rak r')
			->where("r.tanggal_rencana_kerja between '$date_2_awal' and '$date_2_akhir'")
			->get()->row_array();

			$rak_alat_bp_2 = $rak_alat_2['penawaran_id_bp'];
			$rak_alat_bp_2_2 = $rak_alat_2['penawaran_id_bp_2'];
			$rak_alat_bp_3_2 = $rak_alat_2['penawaran_id_bp_3'];

			$rak_alat_tm_2 = $rak_alat_2['penawaran_id_tm'];
			$rak_alat_tm_2_2 = $rak_alat_2['penawaran_id_tm_2'];
			$rak_alat_tm_3_2 = $rak_alat_2['penawaran_id_tm_3'];
			$rak_alat_tm_4_2 = $rak_alat_2['penawaran_id_tm_4'];

			$rak_alat_wl_2 = $rak_alat_2['penawaran_id_wl'];
			$rak_alat_wl_2_2 = $rak_alat_2['penawaran_id_wl_2'];
			$rak_alat_wl_3_2 = $rakrak_alat_2_alat['penawaran_id_wl_3'];

			$rak_alat_tr_2 = $rak_alat_2['penawaran_id_tr'];
			$rak_alat_tr_2_2 = $rak_alat_2['penawaran_id_tr_2'];
			$rak_alat_tr_3_2 = $rak_alat_2['penawaran_id_tr_3'];

			$rak_alat_exc_2 = $rak_alat_2['penawaran_id_exc'];
			$rak_alat_dmp_4m3_2 = $rak_alat_2['penawaran_id_dmp_4m3'];
			$rak_alat_dmp_10m3_2 = $rak_alat_2['penawaran_id_dmp_10m3'];
			$rak_alat_sc_2 = $rak_alat_2['penawaran_id_sc'];
			$rak_alat_gns_2 = $rak_alat_2['penawaran_id_gns'];
			$rak_alat_wl_sc_2 = $rak_alat_2['penawaran_id_wl_sc'];

			$produk_bp_2 = $this->db->select('p.nama_produk, ppd.price, ppd.qty, pm.measure_name, ps.nama, sum(vol_produk_a + vol_produk_b + vol_produk_c + vol_produk_d) as total_vol_produksi')
			->from('pmm_penawaran_pembelian ppp')
			->join('pmm_penawaran_pembelian_detail ppd', 'ppp.id = ppd.penawaran_pembelian_id','left')
			->join('produk p', 'ppd.material_id = p.id','left')
			->join('pmm_measures pm', 'ppd.measure = pm.id','left')
			->join('penerima ps', 'ppp.supplier_id = ps.id','left')
			->join('rak r', 'ppp.id = r.penawaran_id_bp','left')
			->where("r.tanggal_rencana_kerja between '$date_2_awal' and '$date_2_akhir'")
			->where("ppp.id = '$rak_alat_bp_2'")
			->group_by('ppd.id')
			->order_by('p.nama_produk','asc')
			->get()->result_array();

			$total_price_bp_2 = 0;
			foreach ($produk_bp_2 as $x){
				$total_price_bp_2 += $x['total_vol_produksi'] * $x['price'];
			}

			$produk_bp_2_2 = $this->db->select('p.nama_produk, ppd.price, ppd.qty, pm.measure_name, ps.nama, sum(vol_produk_a + vol_produk_b + vol_produk_c + vol_produk_d) as total_vol_produksi')
			->from('pmm_penawaran_pembelian ppp')
			->join('pmm_penawaran_pembelian_detail ppd', 'ppp.id = ppd.penawaran_pembelian_id','left')
			->join('produk p', 'ppd.material_id = p.id','left')
			->join('pmm_measures pm', 'ppd.measure = pm.id','left')
			->join('penerima ps', 'ppp.supplier_id = ps.id','left')
			->join('rak r', 'ppp.id = r.penawaran_id_bp','left')
			->where("r.tanggal_rencana_kerja between '$date_2_awal' and '$date_2_akhir'")
			->where("ppp.id = '$rak_alat_bp_2_2'")
			->group_by('ppd.id')
			->order_by('p.nama_produk','asc')
			->get()->result_array();

			$total_price_bp_2_2 = 0;
			foreach ($produk_bp_2_2 as $x){
				$total_price_bp_2_2 += $x['total_vol_produksi'] * $x['price'];
			}

			$produk_bp_3_2 = $this->db->select('p.nama_produk, ppd.price, ppd.qty, pm.measure_name, ps.nama, sum(vol_produk_a + vol_produk_b + vol_produk_c + vol_produk_d) as total_vol_produksi')
			->from('pmm_penawaran_pembelian ppp')
			->join('pmm_penawaran_pembelian_detail ppd', 'ppp.id = ppd.penawaran_pembelian_id','left')
			->join('produk p', 'ppd.material_id = p.id','left')
			->join('pmm_measures pm', 'ppd.measure = pm.id','left')
			->join('penerima ps', 'ppp.supplier_id = ps.id','left')
			->join('rak r', 'ppp.id = r.penawaran_id_bp','left')
			->where("r.tanggal_rencana_kerja between '$date_2_awal' and '$date_2_akhir'")
			->where("ppp.id = '$rak_alat_bp_3_2'")
			->group_by('ppd.id')
			->order_by('p.nama_produk','asc')
			->get()->result_array();

			$total_price_bp_3_2 = 0;
			foreach ($produk_bp_3_2 as $x){
				$total_price_bp_3_2 += $x['total_vol_produksi'] * $x['price'];
			}

			$produk_tm_2 = $this->db->select('p.nama_produk, ppd.price, ppd.qty, pm.measure_name')
			->from('pmm_penawaran_pembelian ppp')
			->join('pmm_penawaran_pembelian_detail ppd', 'ppp.id = ppd.penawaran_pembelian_id','left')
			->join('produk p', 'ppd.material_id = p.id','left')
			->join('pmm_measures pm', 'ppd.measure = pm.id','left')
			->where("ppp.id = '$rak_alat_tm_2'")
			->group_by('ppd.id')
			->order_by('p.nama_produk','asc')
			->get()->result_array();

			$total_price_tm_2 = 0;
			foreach ($produk_tm_2 as $x){
				$total_price_tm_2 += $x['qty'] * $x['price'];
			}

			$produk_tm_2_2 = $this->db->select('p.nama_produk, ppd.price, ppd.qty, pm.measure_name')
			->from('pmm_penawaran_pembelian ppp')
			->join('pmm_penawaran_pembelian_detail ppd', 'ppp.id = ppd.penawaran_pembelian_id','left')
			->join('produk p', 'ppd.material_id = p.id','left')
			->join('pmm_measures pm', 'ppd.measure = pm.id','left')
			->where("ppp.id = '$rak_alat_tm_2_2'")
			->group_by('ppd.id')
			->order_by('p.nama_produk','asc')
			->get()->result_array();

			$total_price_tm_2_2 = 0;
			foreach ($produk_tm_2_2 as $x){
				$total_price_tm_2_2 += $x['qty'] * $x['price'];
			}

			$produk_tm_3_2 = $this->db->select('p.nama_produk, ppd.price, ppd.qty, pm.measure_name')
			->from('pmm_penawaran_pembelian ppp')
			->join('pmm_penawaran_pembelian_detail ppd', 'ppp.id = ppd.penawaran_pembelian_id','left')
			->join('produk p', 'ppd.material_id = p.id','left')
			->join('pmm_measures pm', 'ppd.measure = pm.id','left')
			->where("ppp.id = '$rak_alat_tm_3_2'")
			->group_by('ppd.id')
			->order_by('p.nama_produk','asc')
			->get()->result_array();

			$total_price_tm_3_2 = 0;
			foreach ($produk_tm_3_2 as $x){
				$total_price_tm_3_2 += $x['qty'] * $x['price'];
			}

			$produk_tm_4_2 = $this->db->select('p.nama_produk, ppd.price, ppd.qty, pm.measure_name')
			->from('pmm_penawaran_pembelian ppp')
			->join('pmm_penawaran_pembelian_detail ppd', 'ppp.id = ppd.penawaran_pembelian_id','left')
			->join('produk p', 'ppd.material_id = p.id','left')
			->join('pmm_measures pm', 'ppd.measure = pm.id','left')
			->where("ppp.id = '$rak_alat_tm_4_2'")
			->group_by('ppd.id')
			->order_by('p.nama_produk','asc')
			->get()->result_array();

			$total_price_tm_4_2 = 0;
			foreach ($produk_tm_4_2 as $x){
				$total_price_tm_4_2 += $x['qty'] * $x['price'];
			}

			$produk_wl_2 = $this->db->select('p.nama_produk, ppd.price, ppd.qty, pm.measure_name')
			->from('pmm_penawaran_pembelian ppp')
			->join('pmm_penawaran_pembelian_detail ppd', 'ppp.id = ppd.penawaran_pembelian_id','left')
			->join('produk p', 'ppd.material_id = p.id','left')
			->join('pmm_measures pm', 'ppd.measure = pm.id','left')
			->where("ppp.id = '$rak_alat_wl_2'")
			->group_by('ppd.id')
			->order_by('p.nama_produk','asc')
			->get()->result_array();

			$total_price_wl_2 = 0;
			foreach ($produk_wl_2 as $x){
				$total_price_wl_2 += $x['qty'] * $x['price'];
			}

			$produk_wl_2_2 = $this->db->select('p.nama_produk, ppd.price, ppd.qty, pm.measure_name')
			->from('pmm_penawaran_pembelian ppp')
			->join('pmm_penawaran_pembelian_detail ppd', 'ppp.id = ppd.penawaran_pembelian_id','left')
			->join('produk p', 'ppd.material_id = p.id','left')
			->join('pmm_measures pm', 'ppd.measure = pm.id','left')
			->where("ppp.id = '$rak_alat_wl_2_2'")
			->group_by('ppd.id')
			->order_by('p.nama_produk','asc')
			->get()->result_array();

			$total_price_wl_2_2 = 0;
			foreach ($produk_wl_2_2 as $x){
				$total_price_wl_2_2 += $x['qty'] * $x['price'];
			}

			$produk_wl_3_2 = $this->db->select('p.nama_produk, ppd.price, ppd.qty, pm.measure_name')
			->from('pmm_penawaran_pembelian ppp')
			->join('pmm_penawaran_pembelian_detail ppd', 'ppp.id = ppd.penawaran_pembelian_id','left')
			->join('produk p', 'ppd.material_id = p.id','left')
			->join('pmm_measures pm', 'ppd.measure = pm.id','left')
			->where("ppp.id = '$rak_alat_wl_3_2'")
			->group_by('ppd.id')
			->order_by('p.nama_produk','asc')
			->get()->result_array();

			$total_price_wl_3_2 = 0;
			foreach ($produk_wl_3_2 as $x){
				$total_price_wl_3_2 += $x['qty'] * $x['price'];
			}

			$produk_tr_2 = $this->db->select('p.nama_produk, ppd.price, ppd.qty, pm.measure_name, ps.nama')
			->from('pmm_penawaran_pembelian ppp')
			->join('pmm_penawaran_pembelian_detail ppd', 'ppp.id = ppd.penawaran_pembelian_id','left')
			->join('produk p', 'ppd.material_id = p.id','left')
			->join('pmm_measures pm', 'ppd.measure = pm.id','left')
			->join('penerima ps', 'ppp.supplier_id = ps.id','left')
			->where("ppp.id = '$rak_alat_tr_2'")
			->group_by('ppd.id')
			->order_by('p.nama_produk','asc')
			->get()->result_array();

			$total_price_tr_2 = 0;
			foreach ($produk_tr_2 as $x){
				$total_price_tr_2 += $x['qty'] * $x['price'];
			}

			$produk_tr_2_2 = $this->db->select('p.nama_produk, ppd.price, ppd.qty, pm.measure_name, ps.nama')
			->from('pmm_penawaran_pembelian ppp')
			->join('pmm_penawaran_pembelian_detail ppd', 'ppp.id = ppd.penawaran_pembelian_id','left')
			->join('produk p', 'ppd.material_id = p.id','left')
			->join('pmm_measures pm', 'ppd.measure = pm.id','left')
			->join('penerima ps', 'ppp.supplier_id = ps.id','left')
			->where("ppp.id = '$rak_alat_tr_2_2'")
			->group_by('ppd.id')
			->order_by('p.nama_produk','asc')
			->get()->result_array();

			$total_price_tr_2_2 = 0;
			foreach ($produk_tr_2_2 as $x){
				$total_price_tr_2_2 += $x['qty'] * $x['price'];
			}

			$produk_tr_3_2 = $this->db->select('p.nama_produk, ppd.price, ppd.qty, pm.measure_name, ps.nama')
			->from('pmm_penawaran_pembelian ppp')
			->join('pmm_penawaran_pembelian_detail ppd', 'ppp.id = ppd.penawaran_pembelian_id','left')
			->join('produk p', 'ppd.material_id = p.id','left')
			->join('pmm_measures pm', 'ppd.measure = pm.id','left')
			->join('penerima ps', 'ppp.supplier_id = ps.id','left')
			->where("ppp.id = '$rak_alat_tr_3_2'")
			->group_by('ppd.id')
			->order_by('p.nama_produk','asc')
			->get()->result_array();

			$total_price_tr_3_2 = 0;
			foreach ($produk_tr_3_2 as $x){
				$total_price_tr_3_2 += $x['qty'] * $x['price'];
			}

			$produk_exc_2 = $this->db->select('p.nama_produk, ppd.price, ppd.qty, pm.measure_name, ps.nama')
			->from('pmm_penawaran_pembelian ppp')
			->join('pmm_penawaran_pembelian_detail ppd', 'ppp.id = ppd.penawaran_pembelian_id','left')
			->join('produk p', 'ppd.material_id = p.id','left')
			->join('pmm_measures pm', 'ppd.measure = pm.id','left')
			->join('penerima ps', 'ppp.supplier_id = ps.id','left')
			->where("ppp.id = '$rak_alat_exc_2'")
			->group_by('ppd.id')
			->order_by('p.nama_produk','asc')
			->get()->result_array();

			$total_price_exc_2 = 0;
			foreach ($produk_exc_2 as $x){
				$total_price_exc_2 += $x['qty'] * $x['price'];
			}

			$produk_dmp_4m3_2 = $this->db->select('p.nama_produk, ppd.price, ppd.qty, pm.measure_name, ps.nama')
			->from('pmm_penawaran_pembelian ppp')
			->join('pmm_penawaran_pembelian_detail ppd', 'ppp.id = ppd.penawaran_pembelian_id','left')
			->join('produk p', 'ppd.material_id = p.id','left')
			->join('pmm_measures pm', 'ppd.measure = pm.id','left')
			->join('penerima ps', 'ppp.supplier_id = ps.id','left')
			->where("ppp.id = '$rak_alat_dmp_4m3_2'")
			->group_by('ppd.id')
			->order_by('p.nama_produk','asc')
			->get()->result_array();

			$total_price_dmp_4m3_2 = 0;
			foreach ($produk_dmp_4m3_2 as $x){
				$total_price_dmp_4m3_2 += $x['qty'] * $x['price'];
			}

			$produk_dmp_10m3_2 = $this->db->select('p.nama_produk, ppd.price, ppd.qty, pm.measure_name, ps.nama')
			->from('pmm_penawaran_pembelian ppp')
			->join('pmm_penawaran_pembelian_detail ppd', 'ppp.id = ppd.penawaran_pembelian_id','left')
			->join('produk p', 'ppd.material_id = p.id','left')
			->join('pmm_measures pm', 'ppd.measure = pm.id','left')
			->join('penerima ps', 'ppp.supplier_id = ps.id','left')
			->where("ppp.id = '$rak_alat_dmp_10m3_2'")
			->group_by('ppd.id')
			->order_by('p.nama_produk','asc')
			->get()->result_array();

			$total_price_dmp_10m3_2 = 0;
			foreach ($produk_dmp_10m3_2 as $x){
				$total_price_dmp_10m3_2 += $x['qty'] * $x['price'];
			}

			$produk_sc_2 = $this->db->select('p.nama_produk, ppd.price, ppd.qty, pm.measure_name, ps.nama')
			->from('pmm_penawaran_pembelian ppp')
			->join('pmm_penawaran_pembelian_detail ppd', 'ppp.id = ppd.penawaran_pembelian_id','left')
			->join('produk p', 'ppd.material_id = p.id','left')
			->join('pmm_measures pm', 'ppd.measure = pm.id','left')
			->join('penerima ps', 'ppp.supplier_id = ps.id','left')
			->where("ppp.id = '$rak_alat_sc_2'")
			->group_by('ppd.id')
			->order_by('p.nama_produk','asc')
			->get()->result_array();

			$total_price_sc_2 = 0;
			foreach ($produk_sc_2 as $x){
				$total_price_sc_2 += $x['qty'] * $x['price'];
			}

			$produk_gns_2 = $this->db->select('p.nama_produk, ppd.price, ppd.qty, pm.measure_name, ps.nama')
			->from('pmm_penawaran_pembelian ppp')
			->join('pmm_penawaran_pembelian_detail ppd', 'ppp.id = ppd.penawaran_pembelian_id','left')
			->join('produk p', 'ppd.material_id = p.id','left')
			->join('pmm_measures pm', 'ppd.measure = pm.id','left')
			->join('penerima ps', 'ppp.supplier_id = ps.id','left')
			->where("ppp.id = '$rak_alat_gns_2'")
			->group_by('ppd.id')
			->order_by('p.nama_produk','asc')
			->get()->result_array();

			$total_price_gns_2 = 0;
			foreach ($produk_gns_2 as $x){
				$total_price_gns_2 += $x['qty'] * $x['price'];
			}

			$produk_wl_sc_2 = $this->db->select('p.nama_produk, ppd.price, ppd.qty, pm.measure_name, ps.nama')
			->from('pmm_penawaran_pembelian ppp')
			->join('pmm_penawaran_pembelian_detail ppd', 'ppp.id = ppd.penawaran_pembelian_id','left')
			->join('produk p', 'ppd.material_id = p.id','left')
			->join('pmm_measures pm', 'ppd.measure = pm.id','left')
			->join('penerima ps', 'ppp.supplier_id = ps.id','left')
			->where("ppp.id = '$rak_alat_wl_sc_2'")
			->group_by('ppd.id')
			->order_by('p.nama_produk','asc')
			->get()->result_array();

			$total_price_wl_sc_2 = 0;
			foreach ($produk_wl_sc_2 as $x){
				$total_price_wl_sc_2 += $x['qty'] * $x['price'];
			}

			$total_volume_solar_2 = $rak_alat_2['vol_bbm_solar'];

			$total_2_biaya_bahan = $nilai_semen_2 + $nilai_pasir_2 + $nilai_batu1020_2 + $nilai_batu2030_2;
			$total_2_biaya_alat = ($total_price_bp_2 + $total_price_bp_2_2 + $total_price_bp_3_2) + ($total_price_tm_2 + $total_price_tm_2_2 + $total_price_tm_3_2 + $total_price_tm_4_2) + ($total_price_wl_2 + $total_price_wl_2_2 + $total_price_wl_3_2) + ($total_price_tr_2 + $total_price_tr_2_2 + $total_price_tr_3_2) + ($total_volume_solar_2 * $rak_alat_2['harga_solar']) + $rak_alat_2['insentif']+ $total_price_exc_2 + $total_price_dmp_4m3_2 + $total_price_dmp_10m3_2 + $total_price_sc_2 + $total_price_gns_2 + $total_price_wl_sc_2;
			$total_2_overhead = $rencana_kerja_2['overhead'];
			$total_2_diskonto =  ($total_2_nilai * 3) /100;
			$total_biaya_2_biaya = $total_2_biaya_bahan + $total_2_biaya_alat + $total_2_overhead + $total_2_diskonto;
			?>

			<?php
			$date_3_awal = date('Y-m-d', strtotime('+1 days', strtotime($date_2_akhir)));
			$date_3_akhir = date('Y-m-d', strtotime('-1 days +1 months', strtotime($date_3_awal)));

			$rencana_kerja_3 = $this->db->select('r.*')
			->from('rak r')
			->where("r.tanggal_rencana_kerja between '$date_3_awal' and '$date_3_akhir'")
			->get()->row_array();

			$volume_3_produk_a = $rencana_kerja_3['vol_produk_a'];
			$volume_3_produk_b = $rencana_kerja_3['vol_produk_b'];
			$volume_3_produk_c = $rencana_kerja_3['vol_produk_c'];
			$volume_3_produk_d = $rencana_kerja_3['vol_produk_d'];
			$volume_3_produk_e = $rencana_kerja_3['vol_produk_e'];

			$total_3_volume = $volume_3_produk_a + $volume_3_produk_b + $volume_3_produk_c + $volume_3_produk_d + $volume_3_produk_e;

			$nilai_jual_125_3 = $volume_3_produk_a * $rencana_kerja_3['price_a'];
			$nilai_jual_225_3 = $volume_3_produk_b * $rencana_kerja_3['price_b'];
			$nilai_jual_250_3 = $volume_3_produk_c * $rencana_kerja_3['price_c'];
			$nilai_jual_250_18_3 = $volume_3_produk_d * $rencana_kerja_3['price_d'];
			$nilai_jual_300_3 = $volume_3_produk_e * $rencana_kerja_3['price_e'];
			$nilai_jual_all_3 = $nilai_jual_125_3 + $nilai_jual_225_3 + $nilai_jual_250_3 + $nilai_jual_250_18_3 + $nilai_jual_300_3;

			$total_3_nilai = $nilai_jual_all_3;

			//VOLUME
			$volume_rencana_kerja_3_produk_a = $rencana_kerja_3['vol_produk_a'];
			$volume_rencana_kerja_3_produk_b = $rencana_kerja_3['vol_produk_b'];
			$volume_rencana_kerja_3_produk_c = $rencana_kerja_3['vol_produk_c'];
			$volume_rencana_kerja_3_produk_d = $rencana_kerja_3['vol_produk_d'];
			$volume_rencana_kerja_3_produk_e = $rencana_kerja_3['vol_produk_e'];

			//BIAYA

			//BIAYA BAHAN
			$komposisi_125_3 = $this->db->select('(r.vol_produk_a * pk.presentase_a) as komposisi_semen_125, (vol_produk_a * pk.presentase_b) as komposisi_pasir_125, (vol_produk_a * pk.presentase_c) as komposisi_batu1020_125, (vol_produk_a * pk.presentase_d) as komposisi_batu2030_125')
			->from('rak r')
			->join('pmm_agregat pk', 'r.komposisi_125 = pk.id','left')
			->where("r.tanggal_rencana_kerja between '$date_3_awal' and '$date_3_akhir'")
			->get()->result_array();

			$total_volume_semen_125_3 = 0;
			$total_volume_pasir_125_3 = 0;
			$total_volume_batu1020_125_3 = 0;
			$total_volume_batu2030_125_3 = 0;

			foreach ($komposisi_125_3 as $x){
				$total_volume_semen_125_3 = $x['komposisi_semen_125'];
				$total_volume_pasir_125_3 = $x['komposisi_pasir_125'];
				$total_volume_batu1020_125_3 = $x['komposisi_batu1020_125'];
				$total_volume_batu2030_125_3 = $x['komposisi_batu2030_125'];
			}

			$komposisi_225_3 = $this->db->select('(r.vol_produk_b * pk.presentase_a) as komposisi_semen_225, (vol_produk_b * pk.presentase_b) as komposisi_pasir_225, (vol_produk_b * pk.presentase_c) as komposisi_batu1020_225, (vol_produk_b * pk.presentase_d) as komposisi_batu2030_225')
			->from('rak r')
			->join('pmm_agregat pk', 'r.komposisi_225 = pk.id','left')
			->where("r.tanggal_rencana_kerja between '$date_3_awal' and '$date_3_akhir'")
			->get()->result_array();

			$total_volume_semen_225_3 = 0;
			$total_volume_pasir_225_3 = 0;
			$total_volume_batu1020_225_3 = 0;
			$total_volume_batu2030_225_3 = 0;

			foreach ($komposisi_225_3 as $x){
				$total_volume_semen_225_3 = $x['komposisi_semen_225'];
				$total_volume_pasir_225_3 = $x['komposisi_pasir_225'];
				$total_volume_batu1020_225_3 = $x['komposisi_batu1020_225'];
				$total_volume_batu2030_225_3 = $x['komposisi_batu2030_225'];
			}

			$komposisi_250_3 = $this->db->select('(r.vol_produk_c * pk.presentase_a) as komposisi_semen_250, (vol_produk_c * pk.presentase_b) as komposisi_pasir_250, (vol_produk_c * pk.presentase_c) as komposisi_batu1020_250, (vol_produk_c * pk.presentase_d) as komposisi_batu2030_250')
			->from('rak r')
			->join('pmm_agregat pk', 'r.komposisi_250 = pk.id','left')
			->where("r.tanggal_rencana_kerja between '$date_3_awal' and '$date_3_akhir'")
			->get()->result_array();

			$total_volume_semen_250_3 = 0;
			$total_volume_pasir_250_3 = 0;
			$total_volume_batu1020_250_3 = 0;
			$total_volume_batu2030_250_3 = 0;

			foreach ($komposisi_250_3 as $x){
				$total_volume_semen_250_3 = $x['komposisi_semen_250'];
				$total_volume_pasir_250_3 = $x['komposisi_pasir_250'];
				$total_volume_batu1020_250_3 = $x['komposisi_batu1020_250'];
				$total_volume_batu2030_250_3 = $x['komposisi_batu2030_250'];
			}

			$komposisi_250_2_3 = $this->db->select('(r.vol_produk_d * pk.presentase_a) as komposisi_semen_250_2, (vol_produk_d * pk.presentase_b) as komposisi_pasir_250_2, (vol_produk_d * pk.presentase_c) as komposisi_batu1020_250_2, (vol_produk_d * pk.presentase_d) as komposisi_batu2030_250_2')
			->from('rak r')
			->join('pmm_agregat pk', 'r.komposisi_250_2 = pk.id','left')
			->where("r.tanggal_rencana_kerja between '$date_3_awal' and '$date_3_akhir'")
			->get()->result_array();

			$total_volume_semen_250_2_3 = 0;
			$total_volume_pasir_250_2_3 = 0;
			$total_volume_batu1020_250_2_3 = 0;
			$total_volume_batu2030_250_2_3 = 0;

			foreach ($komposisi_250_2_3 as $x){
				$total_volume_semen_250_2_3 = $x['komposisi_semen_250_2'];
				$total_volume_pasir_250_2_3 = $x['komposisi_pasir_250_2'];
				$total_volume_batu1020_250_2_3 = $x['komposisi_batu1020_250_2'];
				$total_volume_batu2030_250_2_3 = $x['komposisi_batu2030_250_2'];
			}

			$komposisi_300_3 = $this->db->select('(r.vol_produk_e * pk.presentase_a) as komposisi_semen_300_3, (vol_produk_e * pk.presentase_b) as komposisi_pasir_300_3, (vol_produk_e * pk.presentase_c) as komposisi_batu1020_300_3, (vol_produk_e * pk.presentase_d) as komposisi_batu2030_300_3')
			->from('rak r')
			->join('pmm_agregat pk', 'r.komposisi_300 = pk.id','left')
			->where("r.tanggal_rencana_kerja between '$date_3_awal' and '$date_3_akhir'")
			->get()->result_array();

			$total_volume_semen_300_3 = 0;
			$total_volume_pasir_300_3 = 0;
			$total_volume_batu1020_300_3 = 0;
			$total_volume_batu2030_300_3 = 0;

			foreach ($komposisi_300_3 as $x){
				$total_volume_semen_300_3 = $x['komposisi_semen_300_3'];
				$total_volume_pasir_300_3 = $x['komposisi_pasir_300_3'];
				$total_volume_batu1020_300_3 = $x['komposisi_batu1020_300_3'];
				$total_volume_batu2030_300_3 = $x['komposisi_batu2030_300_3'];
			}

			$total_volume_semen_3 = $total_volume_semen_125_3 + $total_volume_semen_225_3 + $total_volume_semen_250_3 + $total_volume_semen_250_2_3 + $total_volume_semen_300_3;
			$total_volume_pasir_3 = $total_volume_pasir_125_3 + $total_volume_pasir_225_3 + $total_volume_pasir_250_3 + $total_volume_pasir_250_2_3 + $total_volume_pasir_300_3;
			$total_volume_batu1020_3 = $total_volume_batu1020_125_3 + $total_volume_batu1020_225_3 + $total_volume_batu1020_250_3 + $total_volume_batu1020_250_2_3 + $total_volume_batu1020_300_3;
			$total_volume_batu2030_3 = $total_volume_batu2030_125_3 + $total_volume_batu2030_225_3 + $total_volume_batu2030_250_3 + $total_volume_batu2030_250_2_3 + $total_volume_batu2030_300_3;

			$nilai_semen_3 = $total_volume_semen_3 * $rencana_kerja_3['harga_semen'];
			$nilai_pasir_3 = $total_volume_pasir_3 * $rencana_kerja_3['harga_pasir'];
			$nilai_batu1020_3 = $total_volume_batu1020_3 * $rencana_kerja_3['harga_batu1020'];
			$nilai_batu2030_3 = $total_volume_batu2030_3 * $rencana_kerja_3['harga_batu2030'];

			//BIAYA ALAT
			$rak_alat_3 = $this->db->select('r.*, (r.vol_produk_a + r.vol_produk_b + r.vol_produk_c + r.vol_produk_d) as total_produksi')
			->from('rak r')
			->where("r.tanggal_rencana_kerja between '$date_3_awal' and '$date_3_akhir'")
			->get()->row_array();

			$rak_alat_bp_3 = $rak_alat_3['penawaran_id_bp'];
			$rak_alat_bp_2_3 = $rak_alat_3['penawaran_id_bp_2'];
			$rak_alat_bp_3_3 = $rak_alat_3['penawaran_id_bp_3'];

			$rak_alat_tm_3 = $rak_alat_3['penawaran_id_tm'];
			$rak_alat_tm_2_3 = $rak_alat_3['penawaran_id_tm_2'];
			$rak_alat_tm_3_3 = $rak_alat_3['penawaran_id_tm_3'];
			$rak_alat_tm_4_3 = $rak_alat_4['penawaran_id_tm_4'];

			$rak_alat_wl_3 = $rak_alat_3['penawaran_id_wl'];
			$rak_alat_wl_2_3 = $rak_alat_3['penawaran_id_wl_2'];
			$rak_alat_wl_3_3 = $rakrak_alat_3_alat['penawaran_id_wl_3'];

			$rak_alat_tr_3 = $rak_alat_3['penawaran_id_tr'];
			$rak_alat_tr_2_3 = $rak_alat_3['penawaran_id_tr_2'];
			$rak_alat_tr_3_3 = $rak_alat_3['penawaran_id_tr_3'];

			$rak_alat_exc_3 = $rak_alat_3['penawaran_id_exc'];
			$rak_alat_dmp_4m3_3 = $rak_alat_3['penawaran_id_dmp_4m3'];
			$rak_alat_dmp_10m3_3 = $rak_alat_3['penawaran_id_dmp_10m3'];
			$rak_alat_sc_3 = $rak_alat_3['penawaran_id_sc'];
			$rak_alat_gns_3 = $rak_alat_3['penawaran_id_gns'];
			$rak_alat_wl_sc_3 = $rak_alat_3['penawaran_id_wl_sc'];

			$produk_bp_3 = $this->db->select('p.nama_produk, ppd.price, ppd.qty, pm.measure_name, ps.nama, sum(vol_produk_a + vol_produk_b + vol_produk_c + vol_produk_d) as total_vol_produksi')
			->from('pmm_penawaran_pembelian ppp')
			->join('pmm_penawaran_pembelian_detail ppd', 'ppp.id = ppd.penawaran_pembelian_id','left')
			->join('produk p', 'ppd.material_id = p.id','left')
			->join('pmm_measures pm', 'ppd.measure = pm.id','left')
			->join('penerima ps', 'ppp.supplier_id = ps.id','left')
			->join('rak r', 'ppp.id = r.penawaran_id_bp','left')
			->where("r.tanggal_rencana_kerja between '$date_3_awal' and '$date_3_akhir'")
			->where("ppp.id = '$rak_alat_bp_3'")
			->group_by('ppd.id')
			->order_by('p.nama_produk','asc')
			->get()->result_array();

			$total_price_bp_3 = 0;
			foreach ($produk_bp_3 as $x){
				$total_price_bp_3 += $x['total_vol_produksi'] * $x['price'];
			}

			$produk_bp_2_3 = $this->db->select('p.nama_produk, ppd.price, ppd.qty, pm.measure_name, ps.nama, sum(vol_produk_a + vol_produk_b + vol_produk_c + vol_produk_d) as total_vol_produksi')
			->from('pmm_penawaran_pembelian ppp')
			->join('pmm_penawaran_pembelian_detail ppd', 'ppp.id = ppd.penawaran_pembelian_id','left')
			->join('produk p', 'ppd.material_id = p.id','left')
			->join('pmm_measures pm', 'ppd.measure = pm.id','left')
			->join('penerima ps', 'ppp.supplier_id = ps.id','left')
			->join('rak r', 'ppp.id = r.penawaran_id_bp','left')
			->where("r.tanggal_rencana_kerja between '$date_3_awal' and '$date_3_akhir'")
			->where("ppp.id = '$rak_alat_bp_2_3'")
			->group_by('ppd.id')
			->order_by('p.nama_produk','asc')
			->get()->result_array();

			$total_price_bp_2_3 = 0;
			foreach ($produk_bp_2_3 as $x){
				$total_price_bp_2_3 += $x['total_vol_produksi'] * $x['price'];
			}

			$produk_bp_3_3 = $this->db->select('p.nama_produk, ppd.price, ppd.qty, pm.measure_name, ps.nama, sum(vol_produk_a + vol_produk_b + vol_produk_c + vol_produk_d) as total_vol_produksi')
			->from('pmm_penawaran_pembelian ppp')
			->join('pmm_penawaran_pembelian_detail ppd', 'ppp.id = ppd.penawaran_pembelian_id','left')
			->join('produk p', 'ppd.material_id = p.id','left')
			->join('pmm_measures pm', 'ppd.measure = pm.id','left')
			->join('penerima ps', 'ppp.supplier_id = ps.id','left')
			->join('rak r', 'ppp.id = r.penawaran_id_bp','left')
			->where("r.tanggal_rencana_kerja between '$date_3_awal' and '$date_3_akhir'")
			->where("ppp.id = '$rak_alat_bp_3_3'")
			->group_by('ppd.id')
			->order_by('p.nama_produk','asc')
			->get()->result_array();

			$total_price_bp_3_3 = 0;
			foreach ($produk_bp_3_3 as $x){
				$total_price_bp_3_3 += $x['total_vol_produksi'] * $x['price'];
			}

			$produk_tm_3 = $this->db->select('p.nama_produk, ppd.price, ppd.qty, pm.measure_name')
			->from('pmm_penawaran_pembelian ppp')
			->join('pmm_penawaran_pembelian_detail ppd', 'ppp.id = ppd.penawaran_pembelian_id','left')
			->join('produk p', 'ppd.material_id = p.id','left')
			->join('pmm_measures pm', 'ppd.measure = pm.id','left')
			->where("ppp.id = '$rak_alat_tm_3'")
			->group_by('ppd.id')
			->order_by('p.nama_produk','asc')
			->get()->result_array();

			$total_price_tm_3 = 0;
			foreach ($produk_tm_3 as $x){
				$total_price_tm_3 += $x['qty'] * $x['price'];
			}

			$produk_tm_2_3 = $this->db->select('p.nama_produk, ppd.price, ppd.qty, pm.measure_name')
			->from('pmm_penawaran_pembelian ppp')
			->join('pmm_penawaran_pembelian_detail ppd', 'ppp.id = ppd.penawaran_pembelian_id','left')
			->join('produk p', 'ppd.material_id = p.id','left')
			->join('pmm_measures pm', 'ppd.measure = pm.id','left')
			->where("ppp.id = '$rak_alat_tm_2_3'")
			->group_by('ppd.id')
			->order_by('p.nama_produk','asc')
			->get()->result_array();

			$total_price_tm_2_3 = 0;
			foreach ($produk_tm_2_3 as $x){
				$total_price_tm_2_3 += $x['qty'] * $x['price'];
			}

			$produk_tm_3_3 = $this->db->select('p.nama_produk, ppd.price, ppd.qty, pm.measure_name')
			->from('pmm_penawaran_pembelian ppp')
			->join('pmm_penawaran_pembelian_detail ppd', 'ppp.id = ppd.penawaran_pembelian_id','left')
			->join('produk p', 'ppd.material_id = p.id','left')
			->join('pmm_measures pm', 'ppd.measure = pm.id','left')
			->where("ppp.id = '$rak_alat_tm_3_3'")
			->group_by('ppd.id')
			->order_by('p.nama_produk','asc')
			->get()->result_array();

			$total_price_tm_3_3 = 0;
			foreach ($produk_tm_3_3 as $x){
				$total_price_tm_3_3 += $x['qty'] * $x['price'];
			}

			$produk_tm_4_3 = $this->db->select('p.nama_produk, ppd.price, ppd.qty, pm.measure_name')
			->from('pmm_penawaran_pembelian ppp')
			->join('pmm_penawaran_pembelian_detail ppd', 'ppp.id = ppd.penawaran_pembelian_id','left')
			->join('produk p', 'ppd.material_id = p.id','left')
			->join('pmm_measures pm', 'ppd.measure = pm.id','left')
			->where("ppp.id = '$rak_alat_tm_4_3'")
			->group_by('ppd.id')
			->order_by('p.nama_produk','asc')
			->get()->result_array();

			$total_price_tm_4_3 = 0;
			foreach ($produk_tm_4_3 as $x){
				$total_price_tm_4_3 += $x['qty'] * $x['price'];
			}

			$produk_wl_3 = $this->db->select('p.nama_produk, ppd.price, ppd.qty, pm.measure_name')
			->from('pmm_penawaran_pembelian ppp')
			->join('pmm_penawaran_pembelian_detail ppd', 'ppp.id = ppd.penawaran_pembelian_id','left')
			->join('produk p', 'ppd.material_id = p.id','left')
			->join('pmm_measures pm', 'ppd.measure = pm.id','left')
			->where("ppp.id = '$rak_alat_wl_3'")
			->group_by('ppd.id')
			->order_by('p.nama_produk','asc')
			->get()->result_array();

			$total_price_wl_3 = 0;
			foreach ($produk_wl_3 as $x){
				$total_price_wl_3 += $x['qty'] * $x['price'];
			}

			$produk_wl_2_3 = $this->db->select('p.nama_produk, ppd.price, ppd.qty, pm.measure_name')
			->from('pmm_penawaran_pembelian ppp')
			->join('pmm_penawaran_pembelian_detail ppd', 'ppp.id = ppd.penawaran_pembelian_id','left')
			->join('produk p', 'ppd.material_id = p.id','left')
			->join('pmm_measures pm', 'ppd.measure = pm.id','left')
			->where("ppp.id = '$rak_alat_wl_2_3'")
			->group_by('ppd.id')
			->order_by('p.nama_produk','asc')
			->get()->result_array();

			$total_price_wl_2_3 = 0;
			foreach ($produk_wl_2_3 as $x){
				$total_price_wl_2_3 += $x['qty'] * $x['price'];
			}

			$produk_wl_3_3 = $this->db->select('p.nama_produk, ppd.price, ppd.qty, pm.measure_name')
			->from('pmm_penawaran_pembelian ppp')
			->join('pmm_penawaran_pembelian_detail ppd', 'ppp.id = ppd.penawaran_pembelian_id','left')
			->join('produk p', 'ppd.material_id = p.id','left')
			->join('pmm_measures pm', 'ppd.measure = pm.id','left')
			->where("ppp.id = '$rak_alat_wl_3_3'")
			->group_by('ppd.id')
			->order_by('p.nama_produk','asc')
			->get()->result_array();

			$total_price_wl_3_3 = 0;
			foreach ($produk_wl_3_3 as $x){
				$total_price_wl_3_3 += $x['qty'] * $x['price'];
			}

			$produk_tr_3 = $this->db->select('p.nama_produk, ppd.price, ppd.qty, pm.measure_name, ps.nama')
			->from('pmm_penawaran_pembelian ppp')
			->join('pmm_penawaran_pembelian_detail ppd', 'ppp.id = ppd.penawaran_pembelian_id','left')
			->join('produk p', 'ppd.material_id = p.id','left')
			->join('pmm_measures pm', 'ppd.measure = pm.id','left')
			->join('penerima ps', 'ppp.supplier_id = ps.id','left')
			->where("ppp.id = '$rak_alat_tr_3'")
			->group_by('ppd.id')
			->order_by('p.nama_produk','asc')
			->get()->result_array();

			$total_price_tr_3 = 0;
			foreach ($produk_tr_3 as $x){
				$total_price_tr_3 += $x['qty'] * $x['price'];
			}

			$produk_tr_2_3 = $this->db->select('p.nama_produk, ppd.price, ppd.qty, pm.measure_name, ps.nama')
			->from('pmm_penawaran_pembelian ppp')
			->join('pmm_penawaran_pembelian_detail ppd', 'ppp.id = ppd.penawaran_pembelian_id','left')
			->join('produk p', 'ppd.material_id = p.id','left')
			->join('pmm_measures pm', 'ppd.measure = pm.id','left')
			->join('penerima ps', 'ppp.supplier_id = ps.id','left')
			->where("ppp.id = '$rak_alat_tr_2_3'")
			->group_by('ppd.id')
			->order_by('p.nama_produk','asc')
			->get()->result_array();

			$total_price_tr_2_3 = 0;
			foreach ($produk_tr_2_3 as $x){
				$total_price_tr_2_3 += $x['qty'] * $x['price'];
			}

			$produk_tr_3_3 = $this->db->select('p.nama_produk, ppd.price, ppd.qty, pm.measure_name, ps.nama')
			->from('pmm_penawaran_pembelian ppp')
			->join('pmm_penawaran_pembelian_detail ppd', 'ppp.id = ppd.penawaran_pembelian_id','left')
			->join('produk p', 'ppd.material_id = p.id','left')
			->join('pmm_measures pm', 'ppd.measure = pm.id','left')
			->join('penerima ps', 'ppp.supplier_id = ps.id','left')
			->where("ppp.id = '$rak_alat_tr_3_3'")
			->group_by('ppd.id')
			->order_by('p.nama_produk','asc')
			->get()->result_array();

			$total_price_tr_3_3 = 0;
			foreach ($produk_tr_3_3 as $x){
				$total_price_tr_3_3 += $x['qty'] * $x['price'];
			}

			$produk_exc_3 = $this->db->select('p.nama_produk, ppd.price, ppd.qty, pm.measure_name, ps.nama')
			->from('pmm_penawaran_pembelian ppp')
			->join('pmm_penawaran_pembelian_detail ppd', 'ppp.id = ppd.penawaran_pembelian_id','left')
			->join('produk p', 'ppd.material_id = p.id','left')
			->join('pmm_measures pm', 'ppd.measure = pm.id','left')
			->join('penerima ps', 'ppp.supplier_id = ps.id','left')
			->where("ppp.id = '$rak_alat_exc_3'")
			->group_by('ppd.id')
			->order_by('p.nama_produk','asc')
			->get()->result_array();

			$total_price_exc_3 = 0;
			foreach ($produk_exc_3 as $x){
				$total_price_exc_3 += $x['qty'] * $x['price'];
			}

			$produk_dmp_4m3_3 = $this->db->select('p.nama_produk, ppd.price, ppd.qty, pm.measure_name, ps.nama')
			->from('pmm_penawaran_pembelian ppp')
			->join('pmm_penawaran_pembelian_detail ppd', 'ppp.id = ppd.penawaran_pembelian_id','left')
			->join('produk p', 'ppd.material_id = p.id','left')
			->join('pmm_measures pm', 'ppd.measure = pm.id','left')
			->join('penerima ps', 'ppp.supplier_id = ps.id','left')
			->where("ppp.id = '$rak_alat_dmp_4m3_3'")
			->group_by('ppd.id')
			->order_by('p.nama_produk','asc')
			->get()->result_array();

			$total_price_dmp_4m3_3 = 0;
			foreach ($produk_dmp_4m3_3 as $x){
				$total_price_dmp_4m3_3 += $x['qty'] * $x['price'];
			}

			$produk_dmp_10m3_3 = $this->db->select('p.nama_produk, ppd.price, ppd.qty, pm.measure_name, ps.nama')
			->from('pmm_penawaran_pembelian ppp')
			->join('pmm_penawaran_pembelian_detail ppd', 'ppp.id = ppd.penawaran_pembelian_id','left')
			->join('produk p', 'ppd.material_id = p.id','left')
			->join('pmm_measures pm', 'ppd.measure = pm.id','left')
			->join('penerima ps', 'ppp.supplier_id = ps.id','left')
			->where("ppp.id = '$rak_alat_dmp_10m3_3'")
			->group_by('ppd.id')
			->order_by('p.nama_produk','asc')
			->get()->result_array();

			$total_price_dmp_10m3_3 = 0;
			foreach ($produk_dmp_10m3_3 as $x){
				$total_price_dmp_10m3_3 += $x['qty'] * $x['price'];
			}

			$produk_sc_3 = $this->db->select('p.nama_produk, ppd.price, ppd.qty, pm.measure_name, ps.nama')
			->from('pmm_penawaran_pembelian ppp')
			->join('pmm_penawaran_pembelian_detail ppd', 'ppp.id = ppd.penawaran_pembelian_id','left')
			->join('produk p', 'ppd.material_id = p.id','left')
			->join('pmm_measures pm', 'ppd.measure = pm.id','left')
			->join('penerima ps', 'ppp.supplier_id = ps.id','left')
			->where("ppp.id = '$rak_alat_sc_3'")
			->group_by('ppd.id')
			->order_by('p.nama_produk','asc')
			->get()->result_array();

			$total_price_sc_3 = 0;
			foreach ($produk_sc_3 as $x){
				$total_price_sc_3 += $x['qty'] * $x['price'];
			}

			$produk_gns_3 = $this->db->select('p.nama_produk, ppd.price, ppd.qty, pm.measure_name, ps.nama')
			->from('pmm_penawaran_pembelian ppp')
			->join('pmm_penawaran_pembelian_detail ppd', 'ppp.id = ppd.penawaran_pembelian_id','left')
			->join('produk p', 'ppd.material_id = p.id','left')
			->join('pmm_measures pm', 'ppd.measure = pm.id','left')
			->join('penerima ps', 'ppp.supplier_id = ps.id','left')
			->where("ppp.id = '$rak_alat_gns_3'")
			->group_by('ppd.id')
			->order_by('p.nama_produk','asc')
			->get()->result_array();

			$total_price_gns_3 = 0;
			foreach ($produk_gns_3 as $x){
				$total_price_gns_3 += $x['qty'] * $x['price'];
			}

			$produk_wl_sc_3 = $this->db->select('p.nama_produk, ppd.price, ppd.qty, pm.measure_name, ps.nama')
			->from('pmm_penawaran_pembelian ppp')
			->join('pmm_penawaran_pembelian_detail ppd', 'ppp.id = ppd.penawaran_pembelian_id','left')
			->join('produk p', 'ppd.material_id = p.id','left')
			->join('pmm_measures pm', 'ppd.measure = pm.id','left')
			->join('penerima ps', 'ppp.supplier_id = ps.id','left')
			->where("ppp.id = '$rak_alat_wl_sc_3'")
			->group_by('ppd.id')
			->order_by('p.nama_produk','asc')
			->get()->result_array();

			$total_price_wl_sc_3 = 0;
			foreach ($produk_wl_sc_3 as $x){
				$total_price_wl_sc_3 += $x['qty'] * $x['price'];
			}

			$total_volume_solar_3 = $rak_alat_3['vol_bbm_solar'];

			$total_3_biaya_bahan = $nilai_semen_3 + $nilai_pasir_3 + $nilai_batu1020_3 + $nilai_batu2030_3;
			$total_3_biaya_alat = ($total_price_bp_3 + $total_price_bp_2_3 + $total_price_bp_3_3) + ($total_price_tm_3 + $total_price_tm_2_3 + $total_price_tm_3_3 + $total_price_tm_4_3) + ($total_price_wl_3 + $total_price_wl_2_3 + $total_price_wl_3_3) + ($total_price_tr_3 + $total_price_tr_2_3 + $total_price_tr_3_3) + ($total_volume_solar_3 * $rak_alat_3['harga_solar']) + $rak_alat_3['insentif'] + $total_price_exc_3 + $total_price_dmp_4m3_3 + $total_price_dmp_10m3_3 + $total_price_sc_3 + $total_price_gns_3 + $total_price_wl_sc_3;
			$total_3_overhead = $rencana_kerja_3['overhead'];
			$total_3_diskonto =  ($total_3_nilai * 3) /100;
			$total_biaya_3_biaya = $total_3_biaya_bahan + $total_3_biaya_alat + $total_3_overhead + $total_3_diskonto;
			?>

			<?php
			//BULAN 4
			$date_4_awal = date('Y-m-d', strtotime('+1 days', strtotime($date_3_akhir)));
			$date_4_akhir = date('Y-m-d', strtotime('-1 days +1 months', strtotime($date_4_awal)));

			$rencana_kerja_4 = $this->db->select('r.*')
			->from('rak r')
			->where("r.tanggal_rencana_kerja between '$date_4_awal' and '$date_4_akhir'")
			->get()->row_array();

			$volume_4_produk_a = $rencana_kerja_4['vol_produk_a'];
			$volume_4_produk_b = $rencana_kerja_4['vol_produk_b'];
			$volume_4_produk_c = $rencana_kerja_4['vol_produk_c'];
			$volume_4_produk_d = $rencana_kerja_4['vol_produk_d'];
			$volume_4_produk_e = $rencana_kerja_4['vol_produk_e'];

			$total_4_volume = $volume_4_produk_a + $volume_4_produk_b + $volume_4_produk_c + $volume_4_produk_d + $volume_4_produk_e;

			$nilai_jual_125_4 = $volume_4_produk_a * $rencana_kerja_4['price_a'];
			$nilai_jual_225_4 = $volume_4_produk_b * $rencana_kerja_4['price_b'];
			$nilai_jual_250_4 = $volume_4_produk_c * $rencana_kerja_4['price_c'];
			$nilai_jual_250_18_4 = $volume_4_produk_d * $rencana_kerja_4['price_d'];
			$nilai_jual_300_4 = $volume_4_produk_e * $rencana_kerja_4['price_e'];
			$nilai_jual_all_4 = $nilai_jual_125_4 + $nilai_jual_225_4 + $nilai_jual_250_4 + $nilai_jual_250_18_4 + $nilai_jual_300_4;

			$total_4_nilai = $nilai_jual_all_4;

			//VOLUME
			$volume_rencana_kerja_4_produk_a = $rencana_kerja_4['vol_produk_a'];
			$volume_rencana_kerja_4_produk_b = $rencana_kerja_4['vol_produk_b'];
			$volume_rencana_kerja_4_produk_c = $rencana_kerja_4['vol_produk_c'];
			$volume_rencana_kerja_4_produk_d = $rencana_kerja_4['vol_produk_d'];
			$volume_rencana_kerja_4_produk_e = $rencana_kerja_4['vol_produk_e'];

			//BIAYA

			//BIAYA BAHAN
			$komposisi_125_4 = $this->db->select('(r.vol_produk_a * pk.presentase_a) as komposisi_semen_125, (vol_produk_a * pk.presentase_b) as komposisi_pasir_125, (vol_produk_a * pk.presentase_c) as komposisi_batu1020_125, (vol_produk_a * pk.presentase_d) as komposisi_batu2030_125')
			->from('rak r')
			->join('pmm_agregat pk', 'r.komposisi_125 = pk.id','left')
			->where("r.tanggal_rencana_kerja between '$date_4_awal' and '$date_4_akhir'")
			->get()->result_array();

			$total_volume_semen_125_4 = 0;
			$total_volume_pasir_125_4 = 0;
			$total_volume_batu1020_125_4 = 0;
			$total_volume_batu2030_125_4 = 0;

			foreach ($komposisi_125_4 as $x){
				$total_volume_semen_125_4 = $x['komposisi_semen_125'];
				$total_volume_pasir_125_4 = $x['komposisi_pasir_125'];
				$total_volume_batu1020_125_4 = $x['komposisi_batu1020_125'];
				$total_volume_batu2030_125_4 = $x['komposisi_batu2030_125'];
			}

			$komposisi_225_4 = $this->db->select('(r.vol_produk_b * pk.presentase_a) as komposisi_semen_225, (vol_produk_b * pk.presentase_b) as komposisi_pasir_225, (vol_produk_b * pk.presentase_c) as komposisi_batu1020_225, (vol_produk_b * pk.presentase_d) as komposisi_batu2030_225')
			->from('rak r')
			->join('pmm_agregat pk', 'r.komposisi_225 = pk.id','left')
			->where("r.tanggal_rencana_kerja between '$date_4_awal' and '$date_4_akhir'")
			->get()->result_array();

			$total_volume_semen_225_4 = 0;
			$total_volume_pasir_225_4 = 0;
			$total_volume_batu1020_225_4 = 0;
			$total_volume_batu2030_225_4 = 0;

			foreach ($komposisi_225_4 as $x){
				$total_volume_semen_225_4 = $x['komposisi_semen_225'];
				$total_volume_pasir_225_4 = $x['komposisi_pasir_225'];
				$total_volume_batu1020_225_4 = $x['komposisi_batu1020_225'];
				$total_volume_batu2030_225_4 = $x['komposisi_batu2030_225'];
			}

			$komposisi_250_4 = $this->db->select('(r.vol_produk_c * pk.presentase_a) as komposisi_semen_250, (vol_produk_c * pk.presentase_b) as komposisi_pasir_250, (vol_produk_c * pk.presentase_c) as komposisi_batu1020_250, (vol_produk_c * pk.presentase_d) as komposisi_batu2030_250')
			->from('rak r')
			->join('pmm_agregat pk', 'r.komposisi_250 = pk.id','left')
			->where("r.tanggal_rencana_kerja between '$date_4_awal' and '$date_4_akhir'")
			->get()->result_array();

			$total_volume_semen_250_4 = 0;
			$total_volume_pasir_250_4 = 0;
			$total_volume_batu1020_250_4 = 0;
			$total_volume_batu2030_250_4 = 0;

			foreach ($komposisi_250_4 as $x){
				$total_volume_semen_250_4 = $x['komposisi_semen_250'];
				$total_volume_pasir_250_4 = $x['komposisi_pasir_250'];
				$total_volume_batu1020_250_4 = $x['komposisi_batu1020_250'];
				$total_volume_batu2030_250_4 = $x['komposisi_batu2030_250'];
			}

			$komposisi_250_2_4 = $this->db->select('(r.vol_produk_d * pk.presentase_a) as komposisi_semen_250_2, (vol_produk_d * pk.presentase_b) as komposisi_pasir_250_2, (vol_produk_d * pk.presentase_c) as komposisi_batu1020_250_2, (vol_produk_d * pk.presentase_d) as komposisi_batu2030_250_2')
			->from('rak r')
			->join('pmm_agregat pk', 'r.komposisi_250_2 = pk.id','left')
			->where("r.tanggal_rencana_kerja between '$date_4_awal' and '$date_4_akhir'")
			->get()->result_array();

			$total_volume_semen_250_2_4 = 0;
			$total_volume_pasir_250_2_4 = 0;
			$total_volume_batu1020_250_2_4 = 0;
			$total_volume_batu2030_250_2_4 = 0;

			foreach ($komposisi_250_2_4 as $x){
				$total_volume_semen_250_2_4 = $x['komposisi_semen_250_2'];
				$total_volume_pasir_250_2_4 = $x['komposisi_pasir_250_2'];
				$total_volume_batu1020_250_2_4 = $x['komposisi_batu1020_250_2'];
				$total_volume_batu2030_250_2_4 = $x['komposisi_batu2030_250_2'];
			}

			$komposisi_300_4 = $this->db->select('(r.vol_produk_e * pk.presentase_a) as komposisi_semen_300_4, (vol_produk_e * pk.presentase_b) as komposisi_pasir_300_4, (vol_produk_e * pk.presentase_c) as komposisi_batu1020_300_4, (vol_produk_e * pk.presentase_d) as komposisi_batu2030_300_4')
			->from('rak r')
			->join('pmm_agregat pk', 'r.komposisi_300 = pk.id','left')
			->where("r.tanggal_rencana_kerja between '$date_4_awal' and '$date_4_akhir'")
			->get()->result_array();

			$total_volume_semen_300_4 = 0;
			$total_volume_pasir_300_4 = 0;
			$total_volume_batu1020_300_4 = 0;
			$total_volume_batu2030_300_4 = 0;

			foreach ($komposisi_300_4 as $x){
				$total_volume_semen_300_4 = $x['komposisi_semen_300_4'];
				$total_volume_pasir_300_4 = $x['komposisi_pasir_300_4'];
				$total_volume_batu1020_300_4 = $x['komposisi_batu1020_300_4'];
				$total_volume_batu2030_300_4 = $x['komposisi_batu2030_300_4'];
			}

			$total_volume_semen_4 = $total_volume_semen_125_4 + $total_volume_semen_225_4 + $total_volume_semen_250_4 + $total_volume_semen_250_2_4 + $total_volume_semen_300_4;
			$total_volume_pasir_4 = $total_volume_pasir_125_4 + $total_volume_pasir_225_4 + $total_volume_pasir_250_4 + $total_volume_pasir_250_2_4 + $total_volume_pasir_300_4;
			$total_volume_batu1020_4 = $total_volume_batu1020_125_4 + $total_volume_batu1020_225_4 + $total_volume_batu1020_250_4 + $total_volume_batu1020_250_2_4 + $total_volume_batu1020_300_4;
			$total_volume_batu2030_4 = $total_volume_batu2030_125_4 + $total_volume_batu2030_225_4 + $total_volume_batu2030_250_4 + $total_volume_batu2030_250_2_4 + $total_volume_batu2030_300_4;

			$nilai_semen_4 = $total_volume_semen_4 * $rencana_kerja_4['harga_semen'];
			$nilai_pasir_4 = $total_volume_pasir_4 * $rencana_kerja_4['harga_pasir'];
			$nilai_batu1020_4 = $total_volume_batu1020_4 * $rencana_kerja_4['harga_batu1020'];
			$nilai_batu2030_4 = $total_volume_batu2030_4 * $rencana_kerja_4['harga_batu2030'];

			//BIAYA ALAT
			$rak_alat_4 = $this->db->select('r.*, (r.vol_produk_a + r.vol_produk_b + r.vol_produk_c + r.vol_produk_d) as total_produksi')
			->from('rak r')
			->where("r.tanggal_rencana_kerja between '$date_4_awal' and '$date_4_akhir'")
			->get()->row_array();

			$rak_alat_bp_4 = $rak_alat_4['penawaran_id_bp'];
			$rak_alat_bp_2_4 = $rak_alat_4['penawaran_id_bp_2'];
			$rak_alat_bp_3_4 = $rak_alat_4['penawaran_id_bp_3'];

			$rak_alat_tm_4 = $rak_alat_4['penawaran_id_tm'];
			$rak_alat_tm_2_4 = $rak_alat_4['penawaran_id_tm_2'];
			$rak_alat_tm_3_4 = $rak_alat_4['penawaran_id_tm_3'];
			$rak_alat_tm_4_4 = $rak_alat_4['penawaran_id_tm_4'];

			$rak_alat_wl_4 = $rak_alat_4['penawaran_id_wl'];
			$rak_alat_wl_2_4 = $rak_alat_4['penawaran_id_wl_2'];
			$rak_alat_wl_3_4 = $rakrak_alat_4_alat['penawaran_id_wl_3'];

			$rak_alat_tr_4 = $rak_alat_4['penawaran_id_tr'];
			$rak_alat_tr_2_4 = $rak_alat_4['penawaran_id_tr_2'];
			$rak_alat_tr_3_4 = $rak_alat_4['penawaran_id_tr_3'];

			$rak_alat_exc_4 = $rak_alat_4['penawaran_id_exc'];
			$rak_alat_dmp_4m3_4 = $rak_alat_4['penawaran_id_dmp_4m3'];
			$rak_alat_dmp_10m3_4 = $rak_alat_4['penawaran_id_dmp_10m3'];
			$rak_alat_sc_4 = $rak_alat_4['penawaran_id_sc'];
			$rak_alat_gns_4 = $rak_alat_4['penawaran_id_gns'];
			$rak_alat_wl_sc_4 = $rak_alat_4['penawaran_id_wl_sc'];

			$produk_bp_4 = $this->db->select('p.nama_produk, ppd.price, ppd.qty, pm.measure_name, ps.nama, sum(vol_produk_a + vol_produk_b + vol_produk_c + vol_produk_d) as total_vol_produksi')
			->from('pmm_penawaran_pembelian ppp')
			->join('pmm_penawaran_pembelian_detail ppd', 'ppp.id = ppd.penawaran_pembelian_id','left')
			->join('produk p', 'ppd.material_id = p.id','left')
			->join('pmm_measures pm', 'ppd.measure = pm.id','left')
			->join('penerima ps', 'ppp.supplier_id = ps.id','left')
			->join('rak r', 'ppp.id = r.penawaran_id_bp','left')
			->where("r.tanggal_rencana_kerja between '$date_4_awal' and '$date_4_akhir'")
			->where("ppp.id = '$rak_alat_bp_4'")
			->group_by('ppd.id')
			->order_by('p.nama_produk','asc')
			->get()->result_array();

			$total_price_bp_4 = 0;
			foreach ($produk_bp_4 as $x){
				$total_price_bp_4 += $x['total_vol_produksi'] * $x['price'];
			}

			$produk_bp_2_4 = $this->db->select('p.nama_produk, ppd.price, ppd.qty, pm.measure_name, ps.nama, sum(vol_produk_a + vol_produk_b + vol_produk_c + vol_produk_d) as total_vol_produksi')
			->from('pmm_penawaran_pembelian ppp')
			->join('pmm_penawaran_pembelian_detail ppd', 'ppp.id = ppd.penawaran_pembelian_id','left')
			->join('produk p', 'ppd.material_id = p.id','left')
			->join('pmm_measures pm', 'ppd.measure = pm.id','left')
			->join('penerima ps', 'ppp.supplier_id = ps.id','left')
			->join('rak r', 'ppp.id = r.penawaran_id_bp','left')
			->where("r.tanggal_rencana_kerja between '$date_4_awal' and '$date_4_akhir'")
			->where("ppp.id = '$rak_alat_bp_2_4'")
			->group_by('ppd.id')
			->order_by('p.nama_produk','asc')
			->get()->result_array();

			$total_price_bp_2_4 = 0;
			foreach ($produk_bp_2_4 as $x){
				$total_price_bp_2_4 += $x['total_vol_produksi'] * $x['price'];
			}

			$produk_bp_3_4 = $this->db->select('p.nama_produk, ppd.price, ppd.qty, pm.measure_name, ps.nama, sum(vol_produk_a + vol_produk_b + vol_produk_c + vol_produk_d) as total_vol_produksi')
			->from('pmm_penawaran_pembelian ppp')
			->join('pmm_penawaran_pembelian_detail ppd', 'ppp.id = ppd.penawaran_pembelian_id','left')
			->join('produk p', 'ppd.material_id = p.id','left')
			->join('pmm_measures pm', 'ppd.measure = pm.id','left')
			->join('penerima ps', 'ppp.supplier_id = ps.id','left')
			->join('rak r', 'ppp.id = r.penawaran_id_bp','left')
			->where("r.tanggal_rencana_kerja between '$date_4_awal' and '$date_4_akhir'")
			->where("ppp.id = '$rak_alat_bp_3_4'")
			->group_by('ppd.id')
			->order_by('p.nama_produk','asc')
			->get()->result_array();

			$total_price_bp_3_4 = 0;
			foreach ($produk_bp_3_4 as $x){
				$total_price_bp_3_4 += $x['total_vol_produksi'] * $x['price'];
			}

			$produk_tm_4 = $this->db->select('p.nama_produk, ppd.price, ppd.qty, pm.measure_name')
			->from('pmm_penawaran_pembelian ppp')
			->join('pmm_penawaran_pembelian_detail ppd', 'ppp.id = ppd.penawaran_pembelian_id','left')
			->join('produk p', 'ppd.material_id = p.id','left')
			->join('pmm_measures pm', 'ppd.measure = pm.id','left')
			->where("ppp.id = '$rak_alat_tm_4'")
			->group_by('ppd.id')
			->order_by('p.nama_produk','asc')
			->get()->result_array();

			$total_price_tm_4 = 0;
			foreach ($produk_tm_4 as $x){
				$total_price_tm_4 += $x['qty'] * $x['price'];
			}

			$produk_tm_2_4 = $this->db->select('p.nama_produk, ppd.price, ppd.qty, pm.measure_name')
			->from('pmm_penawaran_pembelian ppp')
			->join('pmm_penawaran_pembelian_detail ppd', 'ppp.id = ppd.penawaran_pembelian_id','left')
			->join('produk p', 'ppd.material_id = p.id','left')
			->join('pmm_measures pm', 'ppd.measure = pm.id','left')
			->where("ppp.id = '$rak_alat_tm_2_4'")
			->group_by('ppd.id')
			->order_by('p.nama_produk','asc')
			->get()->result_array();

			$total_price_tm_2_4 = 0;
			foreach ($produk_tm_2_4 as $x){
				$total_price_tm_2_4 += $x['qty'] * $x['price'];
			}

			$produk_tm_3_4 = $this->db->select('p.nama_produk, ppd.price, ppd.qty, pm.measure_name')
			->from('pmm_penawaran_pembelian ppp')
			->join('pmm_penawaran_pembelian_detail ppd', 'ppp.id = ppd.penawaran_pembelian_id','left')
			->join('produk p', 'ppd.material_id = p.id','left')
			->join('pmm_measures pm', 'ppd.measure = pm.id','left')
			->where("ppp.id = '$rak_alat_tm_3_4'")
			->group_by('ppd.id')
			->order_by('p.nama_produk','asc')
			->get()->result_array();

			$total_price_tm_3_4 = 0;
			foreach ($produk_tm_3_4 as $x){
				$total_price_tm_3_4 += $x['qty'] * $x['price'];
			}

			$produk_tm_4_4 = $this->db->select('p.nama_produk, ppd.price, ppd.qty, pm.measure_name')
			->from('pmm_penawaran_pembelian ppp')
			->join('pmm_penawaran_pembelian_detail ppd', 'ppp.id = ppd.penawaran_pembelian_id','left')
			->join('produk p', 'ppd.material_id = p.id','left')
			->join('pmm_measures pm', 'ppd.measure = pm.id','left')
			->where("ppp.id = '$rak_alat_tm_4_4'")
			->group_by('ppd.id')
			->order_by('p.nama_produk','asc')
			->get()->result_array();

			$total_price_tm_4_4 = 0;
			foreach ($produk_tm_4_4 as $x){
				$total_price_tm_4_4 += $x['qty'] * $x['price'];
			}

			$produk_wl_4 = $this->db->select('p.nama_produk, ppd.price, ppd.qty, pm.measure_name')
			->from('pmm_penawaran_pembelian ppp')
			->join('pmm_penawaran_pembelian_detail ppd', 'ppp.id = ppd.penawaran_pembelian_id','left')
			->join('produk p', 'ppd.material_id = p.id','left')
			->join('pmm_measures pm', 'ppd.measure = pm.id','left')
			->where("ppp.id = '$rak_alat_wl_4'")
			->group_by('ppd.id')
			->order_by('p.nama_produk','asc')
			->get()->result_array();

			$total_price_wl_4 = 0;
			foreach ($produk_wl_4 as $x){
				$total_price_wl_4 += $x['qty'] * $x['price'];
			}

			$produk_wl_2_4 = $this->db->select('p.nama_produk, ppd.price, ppd.qty, pm.measure_name')
			->from('pmm_penawaran_pembelian ppp')
			->join('pmm_penawaran_pembelian_detail ppd', 'ppp.id = ppd.penawaran_pembelian_id','left')
			->join('produk p', 'ppd.material_id = p.id','left')
			->join('pmm_measures pm', 'ppd.measure = pm.id','left')
			->where("ppp.id = '$rak_alat_wl_2_4'")
			->group_by('ppd.id')
			->order_by('p.nama_produk','asc')
			->get()->result_array();

			$total_price_wl_2_4 = 0;
			foreach ($produk_wl_2_4 as $x){
				$total_price_wl_2_4 += $x['qty'] * $x['price'];
			}

			$produk_wl_3_4 = $this->db->select('p.nama_produk, ppd.price, ppd.qty, pm.measure_name')
			->from('pmm_penawaran_pembelian ppp')
			->join('pmm_penawaran_pembelian_detail ppd', 'ppp.id = ppd.penawaran_pembelian_id','left')
			->join('produk p', 'ppd.material_id = p.id','left')
			->join('pmm_measures pm', 'ppd.measure = pm.id','left')
			->where("ppp.id = '$rak_alat_wl_3_4'")
			->group_by('ppd.id')
			->order_by('p.nama_produk','asc')
			->get()->result_array();

			$total_price_wl_3_4 = 0;
			foreach ($produk_wl_3_4 as $x){
				$total_price_wl_3_4 += $x['qty'] * $x['price'];
			}

			$produk_tr_4 = $this->db->select('p.nama_produk, ppd.price, ppd.qty, pm.measure_name, ps.nama')
			->from('pmm_penawaran_pembelian ppp')
			->join('pmm_penawaran_pembelian_detail ppd', 'ppp.id = ppd.penawaran_pembelian_id','left')
			->join('produk p', 'ppd.material_id = p.id','left')
			->join('pmm_measures pm', 'ppd.measure = pm.id','left')
			->join('penerima ps', 'ppp.supplier_id = ps.id','left')
			->where("ppp.id = '$rak_alat_tr_4'")
			->group_by('ppd.id')
			->order_by('p.nama_produk','asc')
			->get()->result_array();

			$total_price_tr_4 = 0;
			foreach ($produk_tr_4 as $x){
				$total_price_tr_4 += $x['qty'] * $x['price'];
			}

			$produk_tr_2_4 = $this->db->select('p.nama_produk, ppd.price, ppd.qty, pm.measure_name, ps.nama')
			->from('pmm_penawaran_pembelian ppp')
			->join('pmm_penawaran_pembelian_detail ppd', 'ppp.id = ppd.penawaran_pembelian_id','left')
			->join('produk p', 'ppd.material_id = p.id','left')
			->join('pmm_measures pm', 'ppd.measure = pm.id','left')
			->join('penerima ps', 'ppp.supplier_id = ps.id','left')
			->where("ppp.id = '$rak_alat_tr_2_4'")
			->group_by('ppd.id')
			->order_by('p.nama_produk','asc')
			->get()->result_array();

			$total_price_tr_2_4 = 0;
			foreach ($produk_tr_2_4 as $x){
				$total_price_tr_2_4 += $x['qty'] * $x['price'];
			}

			$produk_tr_3_4 = $this->db->select('p.nama_produk, ppd.price, ppd.qty, pm.measure_name, ps.nama')
			->from('pmm_penawaran_pembelian ppp')
			->join('pmm_penawaran_pembelian_detail ppd', 'ppp.id = ppd.penawaran_pembelian_id','left')
			->join('produk p', 'ppd.material_id = p.id','left')
			->join('pmm_measures pm', 'ppd.measure = pm.id','left')
			->join('penerima ps', 'ppp.supplier_id = ps.id','left')
			->where("ppp.id = '$rak_alat_tr_3_4'")
			->group_by('ppd.id')
			->order_by('p.nama_produk','asc')
			->get()->result_array();

			$total_price_tr_3_4 = 0;
			foreach ($produk_tr_3_4 as $x){
				$total_price_tr_3_4 += $x['qty'] * $x['price'];
			}

			$produk_exc_4 = $this->db->select('p.nama_produk, ppd.price, ppd.qty, pm.measure_name, ps.nama')
			->from('pmm_penawaran_pembelian ppp')
			->join('pmm_penawaran_pembelian_detail ppd', 'ppp.id = ppd.penawaran_pembelian_id','left')
			->join('produk p', 'ppd.material_id = p.id','left')
			->join('pmm_measures pm', 'ppd.measure = pm.id','left')
			->join('penerima ps', 'ppp.supplier_id = ps.id','left')
			->where("ppp.id = '$rak_alat_exc_4'")
			->group_by('ppd.id')
			->order_by('p.nama_produk','asc')
			->get()->result_array();

			$total_price_exc_4 = 0;
			foreach ($produk_exc_4 as $x){
				$total_price_exc_4 += $x['qty'] * $x['price'];
			}

			$produk_dmp_4m3_4 = $this->db->select('p.nama_produk, ppd.price, ppd.qty, pm.measure_name, ps.nama')
			->from('pmm_penawaran_pembelian ppp')
			->join('pmm_penawaran_pembelian_detail ppd', 'ppp.id = ppd.penawaran_pembelian_id','left')
			->join('produk p', 'ppd.material_id = p.id','left')
			->join('pmm_measures pm', 'ppd.measure = pm.id','left')
			->join('penerima ps', 'ppp.supplier_id = ps.id','left')
			->where("ppp.id = '$rak_alat_dmp_4m3_4'")
			->group_by('ppd.id')
			->order_by('p.nama_produk','asc')
			->get()->result_array();

			$total_price_dmp_4m3_4 = 0;
			foreach ($produk_dmp_4m3_4 as $x){
				$total_price_dmp_4m3_4 += $x['qty'] * $x['price'];
			}

			$produk_dmp_10m3_4 = $this->db->select('p.nama_produk, ppd.price, ppd.qty, pm.measure_name, ps.nama')
			->from('pmm_penawaran_pembelian ppp')
			->join('pmm_penawaran_pembelian_detail ppd', 'ppp.id = ppd.penawaran_pembelian_id','left')
			->join('produk p', 'ppd.material_id = p.id','left')
			->join('pmm_measures pm', 'ppd.measure = pm.id','left')
			->join('penerima ps', 'ppp.supplier_id = ps.id','left')
			->where("ppp.id = '$rak_alat_dmp_10m3_4'")
			->group_by('ppd.id')
			->order_by('p.nama_produk','asc')
			->get()->result_array();

			$total_price_dmp_10m3_4 = 0;
			foreach ($produk_dmp_10m3_4 as $x){
				$total_price_dmp_10m3_4 += $x['qty'] * $x['price'];
			}

			$produk_sc_4 = $this->db->select('p.nama_produk, ppd.price, ppd.qty, pm.measure_name, ps.nama')
			->from('pmm_penawaran_pembelian ppp')
			->join('pmm_penawaran_pembelian_detail ppd', 'ppp.id = ppd.penawaran_pembelian_id','left')
			->join('produk p', 'ppd.material_id = p.id','left')
			->join('pmm_measures pm', 'ppd.measure = pm.id','left')
			->join('penerima ps', 'ppp.supplier_id = ps.id','left')
			->where("ppp.id = '$rak_alat_sc_4'")
			->group_by('ppd.id')
			->order_by('p.nama_produk','asc')
			->get()->result_array();

			$total_price_sc_4 = 0;
			foreach ($produk_sc_4 as $x){
				$total_price_sc_4 += $x['qty'] * $x['price'];
			}

			$produk_gns_4 = $this->db->select('p.nama_produk, ppd.price, ppd.qty, pm.measure_name, ps.nama')
			->from('pmm_penawaran_pembelian ppp')
			->join('pmm_penawaran_pembelian_detail ppd', 'ppp.id = ppd.penawaran_pembelian_id','left')
			->join('produk p', 'ppd.material_id = p.id','left')
			->join('pmm_measures pm', 'ppd.measure = pm.id','left')
			->join('penerima ps', 'ppp.supplier_id = ps.id','left')
			->where("ppp.id = '$rak_alat_gns_4'")
			->group_by('ppd.id')
			->order_by('p.nama_produk','asc')
			->get()->result_array();

			$total_price_gns_4 = 0;
			foreach ($produk_gns_4 as $x){
				$total_price_gns_4 += $x['qty'] * $x['price'];
			}

			$produk_wl_sc_4 = $this->db->select('p.nama_produk, ppd.price, ppd.qty, pm.measure_name, ps.nama')
			->from('pmm_penawaran_pembelian ppp')
			->join('pmm_penawaran_pembelian_detail ppd', 'ppp.id = ppd.penawaran_pembelian_id','left')
			->join('produk p', 'ppd.material_id = p.id','left')
			->join('pmm_measures pm', 'ppd.measure = pm.id','left')
			->join('penerima ps', 'ppp.supplier_id = ps.id','left')
			->where("ppp.id = '$rak_alat_wl_sc_4'")
			->group_by('ppd.id')
			->order_by('p.nama_produk','asc')
			->get()->result_array();

			$total_price_wl_sc_4 = 0;
			foreach ($produk_wl_sc_4 as $x){
				$total_price_wl_sc_4 += $x['qty'] * $x['price'];
			}

			$total_volume_solar_4 = $rak_alat_4['vol_bbm_solar'];

			$total_4_biaya_bahan = $nilai_semen_4 + $nilai_pasir_4 + $nilai_batu1020_4 + $nilai_batu2030_4;
			$total_4_biaya_alat = ($total_price_bp_4 + $total_price_bp_2_4 + $total_price_bp_3_4) + ($total_price_tm_4 + $total_price_tm_2_4 + $total_price_tm_3_4 + $total_price_tm_4_4) + ($total_price_wl_4 + $total_price_wl_2_4 + $total_price_wl_3_4) + ($total_price_tr_4 + $total_price_tr_2_4 + $total_price_tr_3_4) + ($total_volume_solar_4 * $rak_alat_4['harga_solar']) + $rak_alat_4['insentif'] + $total_price_exc_4 + $total_price_dmp_4m3_4 + $total_price_dmp_10m3_4 + $total_price_sc_4 + $total_price_gns_4 + $total_price_wl_sc_4;
			$total_4_overhead = $rencana_kerja_4['overhead'];
			$total_4_diskonto =  ($total_4_nilai * 3) /100;
			$total_biaya_4_biaya = $total_4_biaya_bahan + $total_4_biaya_alat + $total_4_overhead + $total_4_diskonto;
			?>

			<?php
			//BULAN 5
			$date_5_awal = date('Y-m-d', strtotime('+1 days', strtotime($date_4_akhir)));
			$date_5_akhir = date('Y-m-d', strtotime('-1 days +1 months', strtotime($date_5_awal)));

			$rencana_kerja_5 = $this->db->select('r.*')
			->from('rak r')
			->where("r.tanggal_rencana_kerja between '$date_5_awal' and '$date_5_akhir'")
			->get()->row_array();

			$volume_5_produk_a = $rencana_kerja_5['vol_produk_a'];
			$volume_5_produk_b = $rencana_kerja_5['vol_produk_b'];
			$volume_5_produk_c = $rencana_kerja_5['vol_produk_c'];
			$volume_5_produk_d = $rencana_kerja_5['vol_produk_d'];
			$volume_5_produk_e = $rencana_kerja_5['vol_produk_e'];

			$total_5_volume = $volume_5_produk_a + $volume_5_produk_b + $volume_5_produk_c + $volume_5_produk_d + $volume_5_produk_e;

			$nilai_jual_125_5 = $volume_5_produk_a * $rencana_kerja_5['price_a'];
			$nilai_jual_225_5 = $volume_5_produk_b * $rencana_kerja_5['price_b'];
			$nilai_jual_250_5 = $volume_5_produk_c * $rencana_kerja_5['price_c'];
			$nilai_jual_250_18_5 = $volume_5_produk_d * $rencana_kerja_5['price_d'];
			$nilai_jual_300_5 = $volume_5_produk_e * $rencana_kerja_5['price_e'];
			$nilai_jual_all_5 = $nilai_jual_125_5 + $nilai_jual_225_5 + $nilai_jual_250_5 + $nilai_jual_250_18_5 + $nilai_jual_300_5;

			$total_5_nilai = $nilai_jual_all_5;

			//VOLUME
			$volume_rencana_kerja_5_produk_a = $rencana_kerja_5['vol_produk_a'];
			$volume_rencana_kerja_5_produk_b = $rencana_kerja_5['vol_produk_b'];
			$volume_rencana_kerja_5_produk_c = $rencana_kerja_5['vol_produk_c'];
			$volume_rencana_kerja_5_produk_d = $rencana_kerja_5['vol_produk_d'];

			//BIAYA

			//BIAYA BAHAN
			$komposisi_125_5 = $this->db->select('(r.vol_produk_a * pk.presentase_a) as komposisi_semen_125, (vol_produk_a * pk.presentase_b) as komposisi_pasir_125, (vol_produk_a * pk.presentase_c) as komposisi_batu1020_125, (vol_produk_a * pk.presentase_d) as komposisi_batu2030_125')
			->from('rak r')
			->join('pmm_agregat pk', 'r.komposisi_125 = pk.id','left')
			->where("r.tanggal_rencana_kerja between '$date_5_awal' and '$date_5_akhir'")
			->get()->result_array();

			$total_volume_semen_125_5 = 0;
			$total_volume_pasir_125_5 = 0;
			$total_volume_batu1020_125_5 = 0;
			$total_volume_batu2030_125_5 = 0;

			foreach ($komposisi_125_5 as $x){
				$total_volume_semen_125_5 = $x['komposisi_semen_125'];
				$total_volume_pasir_125_5 = $x['komposisi_pasir_125'];
				$total_volume_batu1020_125_5 = $x['komposisi_batu1020_125'];
				$total_volume_batu2030_125_5 = $x['komposisi_batu2030_125'];
			}

			$komposisi_225_5 = $this->db->select('(r.vol_produk_b * pk.presentase_a) as komposisi_semen_225, (vol_produk_b * pk.presentase_b) as komposisi_pasir_225, (vol_produk_b * pk.presentase_c) as komposisi_batu1020_225, (vol_produk_b * pk.presentase_d) as komposisi_batu2030_225')
			->from('rak r')
			->join('pmm_agregat pk', 'r.komposisi_225 = pk.id','left')
			->where("r.tanggal_rencana_kerja between '$date_5_awal' and '$date_5_akhir'")
			->get()->result_array();

			$total_volume_semen_225_5 = 0;
			$total_volume_pasir_225_5 = 0;
			$total_volume_batu1020_225_5 = 0;
			$total_volume_batu2030_225_5 = 0;

			foreach ($komposisi_225_5 as $x){
				$total_volume_semen_225_5 = $x['komposisi_semen_225'];
				$total_volume_pasir_225_5 = $x['komposisi_pasir_225'];
				$total_volume_batu1020_225_5 = $x['komposisi_batu1020_225'];
				$total_volume_batu2030_225_5 = $x['komposisi_batu2030_225'];
			}

			$komposisi_250_5 = $this->db->select('(r.vol_produk_c * pk.presentase_a) as komposisi_semen_250, (vol_produk_c * pk.presentase_b) as komposisi_pasir_250, (vol_produk_c * pk.presentase_c) as komposisi_batu1020_250, (vol_produk_c * pk.presentase_d) as komposisi_batu2030_250')
			->from('rak r')
			->join('pmm_agregat pk', 'r.komposisi_250 = pk.id','left')
			->where("r.tanggal_rencana_kerja between '$date_5_awal' and '$date_5_akhir'")
			->get()->result_array();

			$total_volume_semen_250_5 = 0;
			$total_volume_pasir_250_5 = 0;
			$total_volume_batu1020_250_5 = 0;
			$total_volume_batu2030_250_5 = 0;

			foreach ($komposisi_250_5 as $x){
				$total_volume_semen_250_5 = $x['komposisi_semen_250'];
				$total_volume_pasir_250_5 = $x['komposisi_pasir_250'];
				$total_volume_batu1020_250_5 = $x['komposisi_batu1020_250'];
				$total_volume_batu2030_250_5 = $x['komposisi_batu2030_250'];
			}

			$komposisi_250_2_5 = $this->db->select('(r.vol_produk_d * pk.presentase_a) as komposisi_semen_250_2, (vol_produk_d * pk.presentase_b) as komposisi_pasir_250_2, (vol_produk_d * pk.presentase_c) as komposisi_batu1020_250_2, (vol_produk_d * pk.presentase_d) as komposisi_batu2030_250_2')
			->from('rak r')
			->join('pmm_agregat pk', 'r.komposisi_250_2 = pk.id','left')
			->where("r.tanggal_rencana_kerja between '$date_5_awal' and '$date_5_akhir'")
			->get()->result_array();

			$total_volume_semen_250_2_5 = 0;
			$total_volume_pasir_250_2_5 = 0;
			$total_volume_batu1020_250_2_5 = 0;
			$total_volume_batu2030_250_2_5 = 0;

			foreach ($komposisi_250_2_5 as $x){
				$total_volume_semen_250_2_5 = $x['komposisi_semen_250_2'];
				$total_volume_pasir_250_2_5 = $x['komposisi_pasir_250_2'];
				$total_volume_batu1020_250_2_5 = $x['komposisi_batu1020_250_2'];
				$total_volume_batu2030_250_2_5 = $x['komposisi_batu2030_250_2'];
			}

			$komposisi_300_5 = $this->db->select('(r.vol_produk_e * pk.presentase_a) as komposisi_semen_300_5, (vol_produk_e * pk.presentase_b) as komposisi_pasir_300_5, (vol_produk_e * pk.presentase_c) as komposisi_batu1020_300_5, (vol_produk_e * pk.presentase_d) as komposisi_batu2030_300_5')
			->from('rak r')
			->join('pmm_agregat pk', 'r.komposisi_300 = pk.id','left')
			->where("r.tanggal_rencana_kerja between '$date_5_awal' and '$date_5_akhir'")
			->get()->result_array();

			$total_volume_semen_300_5 = 0;
			$total_volume_pasir_300_5 = 0;
			$total_volume_batu1020_300_5 = 0;
			$total_volume_batu2030_300_5 = 0;

			foreach ($komposisi_300_5 as $x){
				$total_volume_semen_300_5 = $x['komposisi_semen_300_5'];
				$total_volume_pasir_300_5 = $x['komposisi_pasir_300_5'];
				$total_volume_batu1020_300_5 = $x['komposisi_batu1020_300_5'];
				$total_volume_batu2030_300_5 = $x['komposisi_batu2030_300_5'];
			}

			$total_volume_semen_5 = $total_volume_semen_125_5 + $total_volume_semen_225_5 + $total_volume_semen_250_5 + $total_volume_semen_250_2_5 + $total_volume_semen_300_5;
			$total_volume_pasir_5 = $total_volume_pasir_125_5 + $total_volume_pasir_225_5 + $total_volume_pasir_250_5 + $total_volume_pasir_250_2_5 + $total_volume_pasir_300_5;
			$total_volume_batu1020_5 = $total_volume_batu1020_125_5 + $total_volume_batu1020_225_5 + $total_volume_batu1020_250_5 + $total_volume_batu1020_250_2_5 + $total_volume_batu1020_300_5;
			$total_volume_batu2030_5 = $total_volume_batu2030_125_5 + $total_volume_batu2030_225_5 + $total_volume_batu2030_250_5 + $total_volume_batu2030_250_2_5 + $total_volume_batu2030_300_5;

			$nilai_semen_5 = $total_volume_semen_5 * $rencana_kerja_5['harga_semen'];
			$nilai_pasir_5 = $total_volume_pasir_5 * $rencana_kerja_5['harga_pasir'];
			$nilai_batu1020_5 = $total_volume_batu1020_5 * $rencana_kerja_5['harga_batu1020'];
			$nilai_batu2030_5 = $total_volume_batu2030_5 * $rencana_kerja_5['harga_batu2030'];

			//BIAYA ALAT
			$rak_alat_5 = $this->db->select('r.*, (r.vol_produk_a + r.vol_produk_b + r.vol_produk_c + r.vol_produk_d) as total_produksi')
			->from('rak r')
			->where("r.tanggal_rencana_kerja between '$date_5_awal' and '$date_5_akhir'")
			->get()->row_array();

			$rak_alat_bp_5 = $rak_alat_5['penawaran_id_bp'];
			$rak_alat_bp_2_5 = $rak_alat_5['penawaran_id_bp_2'];
			$rak_alat_bp_3_5 = $rak_alat_5['penawaran_id_bp_3'];

			$rak_alat_tm_5 = $rak_alat_5['penawaran_id_tm'];
			$rak_alat_tm_2_5 = $rak_alat_5['penawaran_id_tm_2'];
			$rak_alat_tm_3_5 = $rak_alat_5['penawaran_id_tm_3'];
			$rak_alat_tm_4_5 = $rak_alat_5['penawaran_id_tm_4'];

			$rak_alat_wl_5 = $rak_alat_5['penawaran_id_wl'];
			$rak_alat_wl_2_5 = $rak_alat_5['penawaran_id_wl_2'];
			$rak_alat_wl_3_5 = $rakrak_alat_5_alat['penawaran_id_wl_3'];

			$rak_alat_tr_5 = $rak_alat_5['penawaran_id_tr'];
			$rak_alat_tr_2_5 = $rak_alat_5['penawaran_id_tr_2'];
			$rak_alat_tr_3_5 = $rak_alat_5['penawaran_id_tr_3'];

			$rak_alat_exc_5 = $rak_alat_5['penawaran_id_exc'];
			$rak_alat_dmp_4m3_5 = $rak_alat_5['penawaran_id_dmp_4m3'];
			$rak_alat_dmp_10m3_5 = $rak_alat_5['penawaran_id_dmp_10m3'];
			$rak_alat_sc_5 = $rak_alat_5['penawaran_id_sc'];
			$rak_alat_gns_5 = $rak_alat_5['penawaran_id_gns'];
			$rak_alat_wl_sc_5 = $rak_alat_5['penawaran_id_wl_sc'];

			$produk_bp_5 = $this->db->select('p.nama_produk, ppd.price, ppd.qty, pm.measure_name, ps.nama, sum(vol_produk_a + vol_produk_b + vol_produk_c + vol_produk_d) as total_vol_produksi')
			->from('pmm_penawaran_pembelian ppp')
			->join('pmm_penawaran_pembelian_detail ppd', 'ppp.id = ppd.penawaran_pembelian_id','left')
			->join('produk p', 'ppd.material_id = p.id','left')
			->join('pmm_measures pm', 'ppd.measure = pm.id','left')
			->join('penerima ps', 'ppp.supplier_id = ps.id','left')
			->join('rak r', 'ppp.id = r.penawaran_id_bp','left')
			->where("r.tanggal_rencana_kerja between '$date_5_awal' and '$date_5_akhir'")
			->where("ppp.id = '$rak_alat_bp_5'")
			->group_by('ppd.id')
			->order_by('p.nama_produk','asc')
			->get()->result_array();

			$total_price_bp_5 = 0;
			foreach ($produk_bp_5 as $x){
				$total_price_bp_5 += $x['total_vol_produksi'] * $x['price'];
			}

			$produk_bp_2_5 = $this->db->select('p.nama_produk, ppd.price, ppd.qty, pm.measure_name, ps.nama, sum(vol_produk_a + vol_produk_b + vol_produk_c + vol_produk_d) as total_vol_produksi')
			->from('pmm_penawaran_pembelian ppp')
			->join('pmm_penawaran_pembelian_detail ppd', 'ppp.id = ppd.penawaran_pembelian_id','left')
			->join('produk p', 'ppd.material_id = p.id','left')
			->join('pmm_measures pm', 'ppd.measure = pm.id','left')
			->join('penerima ps', 'ppp.supplier_id = ps.id','left')
			->join('rak r', 'ppp.id = r.penawaran_id_bp','left')
			->where("r.tanggal_rencana_kerja between '$date_5_awal' and '$date_5_akhir'")
			->where("ppp.id = '$rak_alat_bp_2_5'")
			->group_by('ppd.id')
			->order_by('p.nama_produk','asc')
			->get()->result_array();

			$total_price_bp_2_5 = 0;
			foreach ($produk_bp_2_5 as $x){
				$total_price_bp_2_5 += $x['total_vol_produksi'] * $x['price'];
			}

			$produk_bp_3_5 = $this->db->select('p.nama_produk, ppd.price, ppd.qty, pm.measure_name, ps.nama, sum(vol_produk_a + vol_produk_b + vol_produk_c + vol_produk_d) as total_vol_produksi')
			->from('pmm_penawaran_pembelian ppp')
			->join('pmm_penawaran_pembelian_detail ppd', 'ppp.id = ppd.penawaran_pembelian_id','left')
			->join('produk p', 'ppd.material_id = p.id','left')
			->join('pmm_measures pm', 'ppd.measure = pm.id','left')
			->join('penerima ps', 'ppp.supplier_id = ps.id','left')
			->join('rak r', 'ppp.id = r.penawaran_id_bp','left')
			->where("r.tanggal_rencana_kerja between '$date_5_awal' and '$date_5_akhir'")
			->where("ppp.id = '$rak_alat_bp_3_5'")
			->group_by('ppd.id')
			->order_by('p.nama_produk','asc')
			->get()->result_array();

			$total_price_bp_3_5 = 0;
			foreach ($produk_bp_3_5 as $x){
				$total_price_bp_3_5 += $x['total_vol_produksi'] * $x['price'];
			}

			$produk_tm_5 = $this->db->select('p.nama_produk, ppd.price, ppd.qty, pm.measure_name')
			->from('pmm_penawaran_pembelian ppp')
			->join('pmm_penawaran_pembelian_detail ppd', 'ppp.id = ppd.penawaran_pembelian_id','left')
			->join('produk p', 'ppd.material_id = p.id','left')
			->join('pmm_measures pm', 'ppd.measure = pm.id','left')
			->where("ppp.id = '$rak_alat_tm_5'")
			->group_by('ppd.id')
			->order_by('p.nama_produk','asc')
			->get()->result_array();

			$total_price_tm_5 = 0;
			foreach ($produk_tm_5 as $x){
				$total_price_tm_5 += $x['qty'] * $x['price'];
			}

			$produk_tm_2_5 = $this->db->select('p.nama_produk, ppd.price, ppd.qty, pm.measure_name')
			->from('pmm_penawaran_pembelian ppp')
			->join('pmm_penawaran_pembelian_detail ppd', 'ppp.id = ppd.penawaran_pembelian_id','left')
			->join('produk p', 'ppd.material_id = p.id','left')
			->join('pmm_measures pm', 'ppd.measure = pm.id','left')
			->where("ppp.id = '$rak_alat_tm_2_5'")
			->group_by('ppd.id')
			->order_by('p.nama_produk','asc')
			->get()->result_array();

			$total_price_tm_2_5 = 0;
			foreach ($produk_tm_2_5 as $x){
				$total_price_tm_2_5 += $x['qty'] * $x['price'];
			}

			$produk_tm_3_5 = $this->db->select('p.nama_produk, ppd.price, ppd.qty, pm.measure_name')
			->from('pmm_penawaran_pembelian ppp')
			->join('pmm_penawaran_pembelian_detail ppd', 'ppp.id = ppd.penawaran_pembelian_id','left')
			->join('produk p', 'ppd.material_id = p.id','left')
			->join('pmm_measures pm', 'ppd.measure = pm.id','left')
			->where("ppp.id = '$rak_alat_tm_3_5'")
			->group_by('ppd.id')
			->order_by('p.nama_produk','asc')
			->get()->result_array();

			$total_price_tm_3_5 = 0;
			foreach ($produk_tm_3_5 as $x){
				$total_price_tm_3_5 += $x['qty'] * $x['price'];
			}

			$produk_tm_4_5 = $this->db->select('p.nama_produk, ppd.price, ppd.qty, pm.measure_name')
			->from('pmm_penawaran_pembelian ppp')
			->join('pmm_penawaran_pembelian_detail ppd', 'ppp.id = ppd.penawaran_pembelian_id','left')
			->join('produk p', 'ppd.material_id = p.id','left')
			->join('pmm_measures pm', 'ppd.measure = pm.id','left')
			->where("ppp.id = '$rak_alat_tm_4_5'")
			->group_by('ppd.id')
			->order_by('p.nama_produk','asc')
			->get()->result_array();

			$total_price_tm_4_5 = 0;
			foreach ($produk_tm_4_5 as $x){
				$total_price_tm_4_5 += $x['qty'] * $x['price'];
			}

			$produk_wl_5 = $this->db->select('p.nama_produk, ppd.price, ppd.qty, pm.measure_name')
			->from('pmm_penawaran_pembelian ppp')
			->join('pmm_penawaran_pembelian_detail ppd', 'ppp.id = ppd.penawaran_pembelian_id','left')
			->join('produk p', 'ppd.material_id = p.id','left')
			->join('pmm_measures pm', 'ppd.measure = pm.id','left')
			->where("ppp.id = '$rak_alat_wl_5'")
			->group_by('ppd.id')
			->order_by('p.nama_produk','asc')
			->get()->result_array();

			$total_price_wl_5 = 0;
			foreach ($produk_wl_5 as $x){
				$total_price_wl_5 += $x['qty'] * $x['price'];
			}

			$produk_wl_2_5 = $this->db->select('p.nama_produk, ppd.price, ppd.qty, pm.measure_name')
			->from('pmm_penawaran_pembelian ppp')
			->join('pmm_penawaran_pembelian_detail ppd', 'ppp.id = ppd.penawaran_pembelian_id','left')
			->join('produk p', 'ppd.material_id = p.id','left')
			->join('pmm_measures pm', 'ppd.measure = pm.id','left')
			->where("ppp.id = '$rak_alat_wl_2_5'")
			->group_by('ppd.id')
			->order_by('p.nama_produk','asc')
			->get()->result_array();

			$total_price_wl_2_5 = 0;
			foreach ($produk_wl_2_5 as $x){
				$total_price_wl_2_5 += $x['qty'] * $x['price'];
			}

			$produk_wl_3_5 = $this->db->select('p.nama_produk, ppd.price, ppd.qty, pm.measure_name')
			->from('pmm_penawaran_pembelian ppp')
			->join('pmm_penawaran_pembelian_detail ppd', 'ppp.id = ppd.penawaran_pembelian_id','left')
			->join('produk p', 'ppd.material_id = p.id','left')
			->join('pmm_measures pm', 'ppd.measure = pm.id','left')
			->where("ppp.id = '$rak_alat_wl_3_5'")
			->group_by('ppd.id')
			->order_by('p.nama_produk','asc')
			->get()->result_array();

			$total_price_wl_3_5 = 0;
			foreach ($produk_wl_3_5 as $x){
				$total_price_wl_3_5 += $x['qty'] * $x['price'];
			}

			$produk_tr_5 = $this->db->select('p.nama_produk, ppd.price, ppd.qty, pm.measure_name, ps.nama')
			->from('pmm_penawaran_pembelian ppp')
			->join('pmm_penawaran_pembelian_detail ppd', 'ppp.id = ppd.penawaran_pembelian_id','left')
			->join('produk p', 'ppd.material_id = p.id','left')
			->join('pmm_measures pm', 'ppd.measure = pm.id','left')
			->join('penerima ps', 'ppp.supplier_id = ps.id','left')
			->where("ppp.id = '$rak_alat_tr_5'")
			->group_by('ppd.id')
			->order_by('p.nama_produk','asc')
			->get()->result_array();

			$total_price_tr_5 = 0;
			foreach ($produk_tr_5 as $x){
				$total_price_tr_5 += $x['qty'] * $x['price'];
			}

			$produk_tr_2_5 = $this->db->select('p.nama_produk, ppd.price, ppd.qty, pm.measure_name, ps.nama')
			->from('pmm_penawaran_pembelian ppp')
			->join('pmm_penawaran_pembelian_detail ppd', 'ppp.id = ppd.penawaran_pembelian_id','left')
			->join('produk p', 'ppd.material_id = p.id','left')
			->join('pmm_measures pm', 'ppd.measure = pm.id','left')
			->join('penerima ps', 'ppp.supplier_id = ps.id','left')
			->where("ppp.id = '$rak_alat_tr_2_5'")
			->group_by('ppd.id')
			->order_by('p.nama_produk','asc')
			->get()->result_array();

			$total_price_tr_2_5 = 0;
			foreach ($produk_tr_2_5 as $x){
				$total_price_tr_2_5 += $x['qty'] * $x['price'];
			}

			$produk_tr_3_5 = $this->db->select('p.nama_produk, ppd.price, ppd.qty, pm.measure_name, ps.nama')
			->from('pmm_penawaran_pembelian ppp')
			->join('pmm_penawaran_pembelian_detail ppd', 'ppp.id = ppd.penawaran_pembelian_id','left')
			->join('produk p', 'ppd.material_id = p.id','left')
			->join('pmm_measures pm', 'ppd.measure = pm.id','left')
			->join('penerima ps', 'ppp.supplier_id = ps.id','left')
			->where("ppp.id = '$rak_alat_tr_3_5'")
			->group_by('ppd.id')
			->order_by('p.nama_produk','asc')
			->get()->result_array();

			$total_price_tr_3_5 = 0;
			foreach ($produk_tr_3_5 as $x){
				$total_price_tr_3_5 += $x['qty'] * $x['price'];
			}

			$produk_exc_5 = $this->db->select('p.nama_produk, ppd.price, ppd.qty, pm.measure_name, ps.nama')
			->from('pmm_penawaran_pembelian ppp')
			->join('pmm_penawaran_pembelian_detail ppd', 'ppp.id = ppd.penawaran_pembelian_id','left')
			->join('produk p', 'ppd.material_id = p.id','left')
			->join('pmm_measures pm', 'ppd.measure = pm.id','left')
			->join('penerima ps', 'ppp.supplier_id = ps.id','left')
			->where("ppp.id = '$rak_alat_exc_5'")
			->group_by('ppd.id')
			->order_by('p.nama_produk','asc')
			->get()->result_array();

			$total_price_exc_5 = 0;
			foreach ($produk_exc_5 as $x){
				$total_price_exc_5 += $x['qty'] * $x['price'];
			}

			$produk_dmp_4m3_5 = $this->db->select('p.nama_produk, ppd.price, ppd.qty, pm.measure_name, ps.nama')
			->from('pmm_penawaran_pembelian ppp')
			->join('pmm_penawaran_pembelian_detail ppd', 'ppp.id = ppd.penawaran_pembelian_id','left')
			->join('produk p', 'ppd.material_id = p.id','left')
			->join('pmm_measures pm', 'ppd.measure = pm.id','left')
			->join('penerima ps', 'ppp.supplier_id = ps.id','left')
			->where("ppp.id = '$rak_alat_dmp_4m3_5'")
			->group_by('ppd.id')
			->order_by('p.nama_produk','asc')
			->get()->result_array();

			$total_price_dmp_4m3_5 = 0;
			foreach ($produk_dmp_4m3_5 as $x){
				$total_price_dmp_4m3_5 += $x['qty'] * $x['price'];
			}

			$produk_dmp_10m3_5 = $this->db->select('p.nama_produk, ppd.price, ppd.qty, pm.measure_name, ps.nama')
			->from('pmm_penawaran_pembelian ppp')
			->join('pmm_penawaran_pembelian_detail ppd', 'ppp.id = ppd.penawaran_pembelian_id','left')
			->join('produk p', 'ppd.material_id = p.id','left')
			->join('pmm_measures pm', 'ppd.measure = pm.id','left')
			->join('penerima ps', 'ppp.supplier_id = ps.id','left')
			->where("ppp.id = '$rak_alat_dmp_10m3_5'")
			->group_by('ppd.id')
			->order_by('p.nama_produk','asc')
			->get()->result_array();

			$total_price_dmp_10m3_5 = 0;
			foreach ($produk_dmp_10m3_5 as $x){
				$total_price_dmp_10m3_5 += $x['qty'] * $x['price'];
			}

			$produk_sc_5 = $this->db->select('p.nama_produk, ppd.price, ppd.qty, pm.measure_name, ps.nama')
			->from('pmm_penawaran_pembelian ppp')
			->join('pmm_penawaran_pembelian_detail ppd', 'ppp.id = ppd.penawaran_pembelian_id','left')
			->join('produk p', 'ppd.material_id = p.id','left')
			->join('pmm_measures pm', 'ppd.measure = pm.id','left')
			->join('penerima ps', 'ppp.supplier_id = ps.id','left')
			->where("ppp.id = '$rak_alat_sc_5'")
			->group_by('ppd.id')
			->order_by('p.nama_produk','asc')
			->get()->result_array();

			$total_price_sc_5 = 0;
			foreach ($produk_sc_5 as $x){
				$total_price_sc_5 += $x['qty'] * $x['price'];
			}

			$produk_gns_5 = $this->db->select('p.nama_produk, ppd.price, ppd.qty, pm.measure_name, ps.nama')
			->from('pmm_penawaran_pembelian ppp')
			->join('pmm_penawaran_pembelian_detail ppd', 'ppp.id = ppd.penawaran_pembelian_id','left')
			->join('produk p', 'ppd.material_id = p.id','left')
			->join('pmm_measures pm', 'ppd.measure = pm.id','left')
			->join('penerima ps', 'ppp.supplier_id = ps.id','left')
			->where("ppp.id = '$rak_alat_gns_5'")
			->group_by('ppd.id')
			->order_by('p.nama_produk','asc')
			->get()->result_array();

			$total_price_gns_5 = 0;
			foreach ($produk_gns_5 as $x){
				$total_price_gns_5 += $x['qty'] * $x['price'];
			}

			$produk_wl_sc_5 = $this->db->select('p.nama_produk, ppd.price, ppd.qty, pm.measure_name, ps.nama')
			->from('pmm_penawaran_pembelian ppp')
			->join('pmm_penawaran_pembelian_detail ppd', 'ppp.id = ppd.penawaran_pembelian_id','left')
			->join('produk p', 'ppd.material_id = p.id','left')
			->join('pmm_measures pm', 'ppd.measure = pm.id','left')
			->join('penerima ps', 'ppp.supplier_id = ps.id','left')
			->where("ppp.id = '$rak_alat_wl_sc_5'")
			->group_by('ppd.id')
			->order_by('p.nama_produk','asc')
			->get()->result_array();

			$total_price_wl_sc_5 = 0;
			foreach ($produk_wl_sc_5 as $x){
				$total_price_wl_sc_5 += $x['qty'] * $x['price'];
			}

			$total_volume_solar_5 = $rak_alat_5['vol_bbm_solar'];

			$total_5_biaya_bahan = $nilai_semen_5 + $nilai_pasir_5 + $nilai_batu1020_5 + $nilai_batu2030_5;
			$total_5_biaya_alat = ($total_price_bp_5 + $total_price_bp_2_5 + $total_price_bp_3_5) + ($total_price_tm_5 + $total_price_tm_2_5 + $total_price_tm_3_5 + $total_price_tm_4_5) + ($total_price_wl_5 + $total_price_wl_2_5 + $total_price_wl_3_5) + ($total_price_tr_5 + $total_price_tr_2_5 + $total_price_tr_3_5) + ($total_volume_solar_5 * $rak_alat_5['harga_solar']) + $rak_alat_5['insentif'] + $total_price_exc_5 + $total_price_dmp_4m3_5 + $total_price_dmp_10m3_5 + $total_price_sc_5 + $total_price_gns_5 + $total_price_wl_sc_5;
			$total_5_overhead = $rencana_kerja_5['overhead'];
			$total_5_diskonto =  ($total_5_nilai * 3) /100;
			$total_biaya_5_biaya = $total_5_biaya_bahan + $total_5_biaya_alat + $total_5_overhead + $total_5_diskonto;
			?>

			<?php
			//BULAN 6
			$date_6_awal = date('Y-m-d', strtotime('+1 days', strtotime($date_5_akhir)));
			$date_6_akhir = date('Y-m-d', strtotime('-1 days +1 months', strtotime($date_6_awal)));

			$rencana_kerja_6 = $this->db->select('r.*')
			->from('rak r')
			->where("r.tanggal_rencana_kerja between '$date_6_awal' and '$date_6_akhir'")
			->get()->row_array();

			$volume_6_produk_a = $rencana_kerja_6['vol_produk_a'];
			$volume_6_produk_b = $rencana_kerja_6['vol_produk_b'];
			$volume_6_produk_c = $rencana_kerja_6['vol_produk_c'];
			$volume_6_produk_d = $rencana_kerja_6['vol_produk_d'];
			$volume_6_produk_e = $rencana_kerja_6['vol_produk_e'];

			$total_6_volume = $volume_6_produk_a + $volume_6_produk_b + $volume_6_produk_c + $volume_6_produk_d + $volume_6_produk_e;

			$nilai_jual_125_6 = $volume_6_produk_a * $rencana_kerja_6['price_a'];
			$nilai_jual_225_6 = $volume_6_produk_b * $rencana_kerja_6['price_b'];
			$nilai_jual_250_6 = $volume_6_produk_c * $rencana_kerja_6['price_c'];
			$nilai_jual_250_18_6 = $volume_6_produk_d * $rencana_kerja_6['price_d'];
			$nilai_jual_300_6 = $volume_6_produk_e * $rencana_kerja_6['price_e'];
			$nilai_jual_all_6 = $nilai_jual_125_6 + $nilai_jual_225_6 + $nilai_jual_250_6 + $nilai_jual_250_18_6 + $nilai_jual_300_6;

			$total_6_nilai = $nilai_jual_all_6;

			//VOLUME
			$volume_rencana_kerja_6_produk_a = $rencana_kerja_6['vol_produk_a'];
			$volume_rencana_kerja_6_produk_b = $rencana_kerja_6['vol_produk_b'];
			$volume_rencana_kerja_6_produk_c = $rencana_kerja_6['vol_produk_c'];
			$volume_rencana_kerja_6_produk_d = $rencana_kerja_6['vol_produk_d'];
			$volume_rencana_kerja_6_produk_e = $rencana_kerja_6['vol_produk_e'];

			//BIAYA

			//BIAYA BAHAN
			$komposisi_125_6 = $this->db->select('(r.vol_produk_a * pk.presentase_a) as komposisi_semen_125, (vol_produk_a * pk.presentase_b) as komposisi_pasir_125, (vol_produk_a * pk.presentase_c) as komposisi_batu1020_125, (vol_produk_a * pk.presentase_d) as komposisi_batu2030_125')
			->from('rak r')
			->join('pmm_agregat pk', 'r.komposisi_125 = pk.id','left')
			->where("r.tanggal_rencana_kerja between '$date_6_awal' and '$date_6_akhir'")
			->get()->result_array();

			$total_volume_semen_125_6 = 0;
			$total_volume_pasir_125_6 = 0;
			$total_volume_batu1020_125_6 = 0;
			$total_volume_batu2030_125_6 = 0;

			foreach ($komposisi_125_6 as $x){
				$total_volume_semen_125_6 = $x['komposisi_semen_125'];
				$total_volume_pasir_125_6 = $x['komposisi_pasir_125'];
				$total_volume_batu1020_125_6 = $x['komposisi_batu1020_125'];
				$total_volume_batu2030_125_6 = $x['komposisi_batu2030_125'];
			}

			$komposisi_225_6 = $this->db->select('(r.vol_produk_b * pk.presentase_a) as komposisi_semen_225, (vol_produk_b * pk.presentase_b) as komposisi_pasir_225, (vol_produk_b * pk.presentase_c) as komposisi_batu1020_225, (vol_produk_b * pk.presentase_d) as komposisi_batu2030_225')
			->from('rak r')
			->join('pmm_agregat pk', 'r.komposisi_225 = pk.id','left')
			->where("r.tanggal_rencana_kerja between '$date_6_awal' and '$date_6_akhir'")
			->get()->result_array();

			$total_volume_semen_225_6 = 0;
			$total_volume_pasir_225_6 = 0;
			$total_volume_batu1020_225_6 = 0;
			$total_volume_batu2030_225_6 = 0;

			foreach ($komposisi_225_6 as $x){
				$total_volume_semen_225_6 = $x['komposisi_semen_225'];
				$total_volume_pasir_225_6 = $x['komposisi_pasir_225'];
				$total_volume_batu1020_225_6 = $x['komposisi_batu1020_225'];
				$total_volume_batu2030_225_6 = $x['komposisi_batu2030_225'];
			}

			$komposisi_250_6 = $this->db->select('(r.vol_produk_c * pk.presentase_a) as komposisi_semen_250, (vol_produk_c * pk.presentase_b) as komposisi_pasir_250, (vol_produk_c * pk.presentase_c) as komposisi_batu1020_250, (vol_produk_c * pk.presentase_d) as komposisi_batu2030_250')
			->from('rak r')
			->join('pmm_agregat pk', 'r.komposisi_250 = pk.id','left')
			->where("r.tanggal_rencana_kerja between '$date_6_awal' and '$date_6_akhir'")
			->get()->result_array();

			$total_volume_semen_250_6 = 0;
			$total_volume_pasir_250_6 = 0;
			$total_volume_batu1020_250_6 = 0;
			$total_volume_batu2030_250_6 = 0;

			foreach ($komposisi_250_6 as $x){
				$total_volume_semen_250_6 = $x['komposisi_semen_250'];
				$total_volume_pasir_250_6 = $x['komposisi_pasir_250'];
				$total_volume_batu1020_250_6 = $x['komposisi_batu1020_250'];
				$total_volume_batu2030_250_6 = $x['komposisi_batu2030_250'];
			}

			$komposisi_250_2_6 = $this->db->select('(r.vol_produk_d * pk.presentase_a) as komposisi_semen_250_2, (vol_produk_d * pk.presentase_b) as komposisi_pasir_250_2, (vol_produk_d * pk.presentase_c) as komposisi_batu1020_250_2, (vol_produk_d * pk.presentase_d) as komposisi_batu2030_250_2')
			->from('rak r')
			->join('pmm_agregat pk', 'r.komposisi_250_2 = pk.id','left')
			->where("r.tanggal_rencana_kerja between '$date_6_awal' and '$date_6_akhir'")
			->get()->result_array();

			$total_volume_semen_250_2_6 = 0;
			$total_volume_pasir_250_2_6 = 0;
			$total_volume_batu1020_250_2_6 = 0;
			$total_volume_batu2030_250_2_6 = 0;

			foreach ($komposisi_250_2_6 as $x){
				$total_volume_semen_250_2_6 = $x['komposisi_semen_250_2'];
				$total_volume_pasir_250_2_6 = $x['komposisi_pasir_250_2'];
				$total_volume_batu1020_250_2_6 = $x['komposisi_batu1020_250_2'];
				$total_volume_batu2030_250_2_6 = $x['komposisi_batu2030_250_2'];
			}

			$komposisi_300_6 = $this->db->select('(r.vol_produk_e * pk.presentase_a) as komposisi_semen_300_6, (vol_produk_e * pk.presentase_b) as komposisi_pasir_300_6, (vol_produk_e * pk.presentase_c) as komposisi_batu1020_300_6, (vol_produk_e * pk.presentase_d) as komposisi_batu2030_300_6')
			->from('rak r')
			->join('pmm_agregat pk', 'r.komposisi_300 = pk.id','left')
			->where("r.tanggal_rencana_kerja between '$date_6_awal' and '$date_6_akhir'")
			->get()->result_array();

			$total_volume_semen_300_6 = 0;
			$total_volume_pasir_300_6 = 0;
			$total_volume_batu1020_300_6 = 0;
			$total_volume_batu2030_300_6 = 0;

			foreach ($komposisi_300_6 as $x){
				$total_volume_semen_300_6 = $x['komposisi_semen_300_6'];
				$total_volume_pasir_300_6 = $x['komposisi_pasir_300_6'];
				$total_volume_batu1020_300_6 = $x['komposisi_batu1020_300_6'];
				$total_volume_batu2030_300_6 = $x['komposisi_batu2030_300_6'];
			}

			$total_volume_semen_6 = $total_volume_semen_125_6 + $total_volume_semen_225_6 + $total_volume_semen_250_6 + $total_volume_semen_250_2_6 + $total_volume_semen_300_6;
			$total_volume_pasir_6 = $total_volume_pasir_125_6 + $total_volume_pasir_225_6 + $total_volume_pasir_250_6 + $total_volume_pasir_250_2_6 + $total_volume_pasir_300_6;
			$total_volume_batu1020_6 = $total_volume_batu1020_125_6 + $total_volume_batu1020_225_6 + $total_volume_batu1020_250_6 + $total_volume_batu1020_250_2_6 + $total_volume_batu1020_300_6;
			$total_volume_batu2030_6 = $total_volume_batu2030_125_6 + $total_volume_batu2030_225_6 + $total_volume_batu2030_250_6 + $total_volume_batu2030_250_2_6 + $total_volume_batu2030_300_6;

			$nilai_semen_6 = $total_volume_semen_6 * $rencana_kerja_6['harga_semen'];
			$nilai_pasir_6 = $total_volume_pasir_6 * $rencana_kerja_6['harga_pasir'];
			$nilai_batu1020_6 = $total_volume_batu1020_6 * $rencana_kerja_6['harga_batu1020'];
			$nilai_batu2030_6 = $total_volume_batu2030_6 * $rencana_kerja_6['harga_batu2030'];

			//BIAYA ALAT
			$rak_alat_6 = $this->db->select('r.*, (r.vol_produk_a + r.vol_produk_b + r.vol_produk_c + r.vol_produk_d) as total_produksi')
			->from('rak r')
			->where("r.tanggal_rencana_kerja between '$date_6_awal' and '$date_6_akhir'")
			->get()->row_array();

			$rak_alat_bp_6 = $rak_alat_6['penawaran_id_bp'];
			$rak_alat_bp_2_6 = $rak_alat_6['penawaran_id_bp_2'];
			$rak_alat_bp_3_6 = $rak_alat_6['penawaran_id_bp_3'];

			$rak_alat_tm_6 = $rak_alat_6['penawaran_id_tm'];
			$rak_alat_tm_2_6 = $rak_alat_6['penawaran_id_tm_2'];
			$rak_alat_tm_3_6 = $rak_alat_6['penawaran_id_tm_3'];
			$rak_alat_tm_4_6 = $rak_alat_6['penawaran_id_tm_4'];

			$rak_alat_wl_6 = $rak_alat_6['penawaran_id_wl'];
			$rak_alat_wl_2_6 = $rak_alat_6['penawaran_id_wl_2'];
			$rak_alat_wl_3_6 = $rakrak_alat_6_alat['penawaran_id_wl_3'];

			$rak_alat_tr_6 = $rak_alat_6['penawaran_id_tr'];
			$rak_alat_tr_2_6 = $rak_alat_6['penawaran_id_tr_2'];
			$rak_alat_tr_3_6 = $rak_alat_6['penawaran_id_tr_3'];

			$rak_alat_exc_6 = $rak_alat_6['penawaran_id_exc'];
			$rak_alat_dmp_4m3_6 = $rak_alat_6['penawaran_id_dmp_4m3'];
			$rak_alat_dmp_10m3_6 = $rak_alat_6['penawaran_id_dmp_10m3'];
			$rak_alat_sc_6 = $rak_alat_6['penawaran_id_sc'];
			$rak_alat_gns_6 = $rak_alat_6['penawaran_id_gns'];
			$rak_alat_wl_sc_6 = $rak_alat_6['penawaran_id_wl_sc'];

			$produk_bp_6 = $this->db->select('p.nama_produk, ppd.price, ppd.qty, pm.measure_name, ps.nama, sum(vol_produk_a + vol_produk_b + vol_produk_c + vol_produk_d) as total_vol_produksi')
			->from('pmm_penawaran_pembelian ppp')
			->join('pmm_penawaran_pembelian_detail ppd', 'ppp.id = ppd.penawaran_pembelian_id','left')
			->join('produk p', 'ppd.material_id = p.id','left')
			->join('pmm_measures pm', 'ppd.measure = pm.id','left')
			->join('penerima ps', 'ppp.supplier_id = ps.id','left')
			->join('rak r', 'ppp.id = r.penawaran_id_bp','left')
			->where("r.tanggal_rencana_kerja between '$date_6_awal' and '$date_6_akhir'")
			->where("ppp.id = '$rak_alat_bp_6'")
			->group_by('ppd.id')
			->order_by('p.nama_produk','asc')
			->get()->result_array();

			$total_price_bp_6 = 0;
			foreach ($produk_bp_6 as $x){
				$total_price_bp_6 += $x['total_vol_produksi'] * $x['price'];
			}

			$produk_bp_2_6 = $this->db->select('p.nama_produk, ppd.price, ppd.qty, pm.measure_name, ps.nama, sum(vol_produk_a + vol_produk_b + vol_produk_c + vol_produk_d) as total_vol_produksi')
			->from('pmm_penawaran_pembelian ppp')
			->join('pmm_penawaran_pembelian_detail ppd', 'ppp.id = ppd.penawaran_pembelian_id','left')
			->join('produk p', 'ppd.material_id = p.id','left')
			->join('pmm_measures pm', 'ppd.measure = pm.id','left')
			->join('penerima ps', 'ppp.supplier_id = ps.id','left')
			->join('rak r', 'ppp.id = r.penawaran_id_bp','left')
			->where("r.tanggal_rencana_kerja between '$date_6_awal' and '$date_6_akhir'")
			->where("ppp.id = '$rak_alat_bp_2_6'")
			->group_by('ppd.id')
			->order_by('p.nama_produk','asc')
			->get()->result_array();

			$total_price_bp_2_6 = 0;
			foreach ($produk_bp_2_6 as $x){
				$total_price_bp_2_6 += $x['total_vol_produksi'] * $x['price'];
			}

			$produk_bp_3_6 = $this->db->select('p.nama_produk, ppd.price, ppd.qty, pm.measure_name, ps.nama, sum(vol_produk_a + vol_produk_b + vol_produk_c + vol_produk_d) as total_vol_produksi')
			->from('pmm_penawaran_pembelian ppp')
			->join('pmm_penawaran_pembelian_detail ppd', 'ppp.id = ppd.penawaran_pembelian_id','left')
			->join('produk p', 'ppd.material_id = p.id','left')
			->join('pmm_measures pm', 'ppd.measure = pm.id','left')
			->join('penerima ps', 'ppp.supplier_id = ps.id','left')
			->join('rak r', 'ppp.id = r.penawaran_id_bp','left')
			->where("r.tanggal_rencana_kerja between '$date_6_awal' and '$date_6_akhir'")
			->where("ppp.id = '$rak_alat_bp_3_6'")
			->group_by('ppd.id')
			->order_by('p.nama_produk','asc')
			->get()->result_array();

			$total_price_bp_3_6 = 0;
			foreach ($produk_bp_3_6 as $x){
				$total_price_bp_3_6 += $x['total_vol_produksi'] * $x['price'];
			}

			$produk_tm_6 = $this->db->select('p.nama_produk, ppd.price, ppd.qty, pm.measure_name')
			->from('pmm_penawaran_pembelian ppp')
			->join('pmm_penawaran_pembelian_detail ppd', 'ppp.id = ppd.penawaran_pembelian_id','left')
			->join('produk p', 'ppd.material_id = p.id','left')
			->join('pmm_measures pm', 'ppd.measure = pm.id','left')
			->where("ppp.id = '$rak_alat_tm_6'")
			->group_by('ppd.id')
			->order_by('p.nama_produk','asc')
			->get()->result_array();

			$total_price_tm_6 = 0;
			foreach ($produk_tm_6 as $x){
				$total_price_tm_6 += $x['qty'] * $x['price'];
			}

			$produk_tm_2_6 = $this->db->select('p.nama_produk, ppd.price, ppd.qty, pm.measure_name')
			->from('pmm_penawaran_pembelian ppp')
			->join('pmm_penawaran_pembelian_detail ppd', 'ppp.id = ppd.penawaran_pembelian_id','left')
			->join('produk p', 'ppd.material_id = p.id','left')
			->join('pmm_measures pm', 'ppd.measure = pm.id','left')
			->where("ppp.id = '$rak_alat_tm_2_6'")
			->group_by('ppd.id')
			->order_by('p.nama_produk','asc')
			->get()->result_array();

			$total_price_tm_2_6 = 0;
			foreach ($produk_tm_2_6 as $x){
				$total_price_tm_2_6 += $x['qty'] * $x['price'];
			}

			$produk_tm_3_6 = $this->db->select('p.nama_produk, ppd.price, ppd.qty, pm.measure_name')
			->from('pmm_penawaran_pembelian ppp')
			->join('pmm_penawaran_pembelian_detail ppd', 'ppp.id = ppd.penawaran_pembelian_id','left')
			->join('produk p', 'ppd.material_id = p.id','left')
			->join('pmm_measures pm', 'ppd.measure = pm.id','left')
			->where("ppp.id = '$rak_alat_tm_3_6'")
			->group_by('ppd.id')
			->order_by('p.nama_produk','asc')
			->get()->result_array();

			$total_price_tm_3_6 = 0;
			foreach ($produk_tm_3_6 as $x){
				$total_price_tm_3_6 += $x['qty'] * $x['price'];
			}

			$produk_tm_4_6 = $this->db->select('p.nama_produk, ppd.price, ppd.qty, pm.measure_name')
			->from('pmm_penawaran_pembelian ppp')
			->join('pmm_penawaran_pembelian_detail ppd', 'ppp.id = ppd.penawaran_pembelian_id','left')
			->join('produk p', 'ppd.material_id = p.id','left')
			->join('pmm_measures pm', 'ppd.measure = pm.id','left')
			->where("ppp.id = '$rak_alat_tm_4_6'")
			->group_by('ppd.id')
			->order_by('p.nama_produk','asc')
			->get()->result_array();

			$total_price_tm_4_6 = 0;
			foreach ($produk_tm_4_6 as $x){
				$total_price_tm_4_6 += $x['qty'] * $x['price'];
			}

			$produk_wl_6 = $this->db->select('p.nama_produk, ppd.price, ppd.qty, pm.measure_name')
			->from('pmm_penawaran_pembelian ppp')
			->join('pmm_penawaran_pembelian_detail ppd', 'ppp.id = ppd.penawaran_pembelian_id','left')
			->join('produk p', 'ppd.material_id = p.id','left')
			->join('pmm_measures pm', 'ppd.measure = pm.id','left')
			->where("ppp.id = '$rak_alat_wl_6'")
			->group_by('ppd.id')
			->order_by('p.nama_produk','asc')
			->get()->result_array();

			$total_price_wl_6 = 0;
			foreach ($produk_wl_6 as $x){
				$total_price_wl_6 += $x['qty'] * $x['price'];
			}

			$produk_wl_2_6 = $this->db->select('p.nama_produk, ppd.price, ppd.qty, pm.measure_name')
			->from('pmm_penawaran_pembelian ppp')
			->join('pmm_penawaran_pembelian_detail ppd', 'ppp.id = ppd.penawaran_pembelian_id','left')
			->join('produk p', 'ppd.material_id = p.id','left')
			->join('pmm_measures pm', 'ppd.measure = pm.id','left')
			->where("ppp.id = '$rak_alat_wl_2_6'")
			->group_by('ppd.id')
			->order_by('p.nama_produk','asc')
			->get()->result_array();

			$total_price_wl_2_6 = 0;
			foreach ($produk_wl_2_6 as $x){
				$total_price_wl_2_6 += $x['qty'] * $x['price'];
			}

			$produk_wl_3_6 = $this->db->select('p.nama_produk, ppd.price, ppd.qty, pm.measure_name')
			->from('pmm_penawaran_pembelian ppp')
			->join('pmm_penawaran_pembelian_detail ppd', 'ppp.id = ppd.penawaran_pembelian_id','left')
			->join('produk p', 'ppd.material_id = p.id','left')
			->join('pmm_measures pm', 'ppd.measure = pm.id','left')
			->where("ppp.id = '$rak_alat_wl_3_6'")
			->group_by('ppd.id')
			->order_by('p.nama_produk','asc')
			->get()->result_array();

			$total_price_wl_3_6 = 0;
			foreach ($produk_wl_3_6 as $x){
				$total_price_wl_3_6 += $x['qty'] * $x['price'];
			}

			$produk_tr_6 = $this->db->select('p.nama_produk, ppd.price, ppd.qty, pm.measure_name, ps.nama')
			->from('pmm_penawaran_pembelian ppp')
			->join('pmm_penawaran_pembelian_detail ppd', 'ppp.id = ppd.penawaran_pembelian_id','left')
			->join('produk p', 'ppd.material_id = p.id','left')
			->join('pmm_measures pm', 'ppd.measure = pm.id','left')
			->join('penerima ps', 'ppp.supplier_id = ps.id','left')
			->where("ppp.id = '$rak_alat_tr_6'")
			->group_by('ppd.id')
			->order_by('p.nama_produk','asc')
			->get()->result_array();

			$total_price_tr_6 = 0;
			foreach ($produk_tr_6 as $x){
				$total_price_tr_6 += $x['qty'] * $x['price'];
			}

			$produk_tr_2_6 = $this->db->select('p.nama_produk, ppd.price, ppd.qty, pm.measure_name, ps.nama')
			->from('pmm_penawaran_pembelian ppp')
			->join('pmm_penawaran_pembelian_detail ppd', 'ppp.id = ppd.penawaran_pembelian_id','left')
			->join('produk p', 'ppd.material_id = p.id','left')
			->join('pmm_measures pm', 'ppd.measure = pm.id','left')
			->join('penerima ps', 'ppp.supplier_id = ps.id','left')
			->where("ppp.id = '$rak_alat_tr_2_6'")
			->group_by('ppd.id')
			->order_by('p.nama_produk','asc')
			->get()->result_array();

			$total_price_tr_2_6 = 0;
			foreach ($produk_tr_2_6 as $x){
				$total_price_tr_2_6 += $x['qty'] * $x['price'];
			}

			$produk_tr_3_6 = $this->db->select('p.nama_produk, ppd.price, ppd.qty, pm.measure_name, ps.nama')
			->from('pmm_penawaran_pembelian ppp')
			->join('pmm_penawaran_pembelian_detail ppd', 'ppp.id = ppd.penawaran_pembelian_id','left')
			->join('produk p', 'ppd.material_id = p.id','left')
			->join('pmm_measures pm', 'ppd.measure = pm.id','left')
			->join('penerima ps', 'ppp.supplier_id = ps.id','left')
			->where("ppp.id = '$rak_alat_tr_3_6'")
			->group_by('ppd.id')
			->order_by('p.nama_produk','asc')
			->get()->result_array();

			$total_price_tr_3_6 = 0;
			foreach ($produk_tr_3_6 as $x){
				$total_price_tr_3_6 += $x['qty'] * $x['price'];
			}

			$produk_exc_6 = $this->db->select('p.nama_produk, ppd.price, ppd.qty, pm.measure_name, ps.nama')
			->from('pmm_penawaran_pembelian ppp')
			->join('pmm_penawaran_pembelian_detail ppd', 'ppp.id = ppd.penawaran_pembelian_id','left')
			->join('produk p', 'ppd.material_id = p.id','left')
			->join('pmm_measures pm', 'ppd.measure = pm.id','left')
			->join('penerima ps', 'ppp.supplier_id = ps.id','left')
			->where("ppp.id = '$rak_alat_exc_6'")
			->group_by('ppd.id')
			->order_by('p.nama_produk','asc')
			->get()->result_array();

			$total_price_exc_6 = 0;
			foreach ($produk_exc_6 as $x){
				$total_price_exc_6 += $x['qty'] * $x['price'];
			}

			$produk_dmp_4m3_6 = $this->db->select('p.nama_produk, ppd.price, ppd.qty, pm.measure_name, ps.nama')
			->from('pmm_penawaran_pembelian ppp')
			->join('pmm_penawaran_pembelian_detail ppd', 'ppp.id = ppd.penawaran_pembelian_id','left')
			->join('produk p', 'ppd.material_id = p.id','left')
			->join('pmm_measures pm', 'ppd.measure = pm.id','left')
			->join('penerima ps', 'ppp.supplier_id = ps.id','left')
			->where("ppp.id = '$rak_alat_dmp_4m3_6'")
			->group_by('ppd.id')
			->order_by('p.nama_produk','asc')
			->get()->result_array();

			$total_price_dmp_4m3_6 = 0;
			foreach ($produk_dmp_4m3_6 as $x){
				$total_price_dmp_4m3_6 += $x['qty'] * $x['price'];
			}

			$produk_dmp_10m3_6 = $this->db->select('p.nama_produk, ppd.price, ppd.qty, pm.measure_name, ps.nama')
			->from('pmm_penawaran_pembelian ppp')
			->join('pmm_penawaran_pembelian_detail ppd', 'ppp.id = ppd.penawaran_pembelian_id','left')
			->join('produk p', 'ppd.material_id = p.id','left')
			->join('pmm_measures pm', 'ppd.measure = pm.id','left')
			->join('penerima ps', 'ppp.supplier_id = ps.id','left')
			->where("ppp.id = '$rak_alat_dmp_10m3_6'")
			->group_by('ppd.id')
			->order_by('p.nama_produk','asc')
			->get()->result_array();

			$total_price_dmp_10m3_6 = 0;
			foreach ($produk_dmp_10m3_6 as $x){
				$total_price_dmp_10m3_6 += $x['qty'] * $x['price'];
			}

			$produk_sc_6 = $this->db->select('p.nama_produk, ppd.price, ppd.qty, pm.measure_name, ps.nama')
			->from('pmm_penawaran_pembelian ppp')
			->join('pmm_penawaran_pembelian_detail ppd', 'ppp.id = ppd.penawaran_pembelian_id','left')
			->join('produk p', 'ppd.material_id = p.id','left')
			->join('pmm_measures pm', 'ppd.measure = pm.id','left')
			->join('penerima ps', 'ppp.supplier_id = ps.id','left')
			->where("ppp.id = '$rak_alat_sc_6'")
			->group_by('ppd.id')
			->order_by('p.nama_produk','asc')
			->get()->result_array();

			$total_price_sc_6 = 0;
			foreach ($produk_sc_6 as $x){
				$total_price_sc_6 += $x['qty'] * $x['price'];
			}

			$produk_gns_6 = $this->db->select('p.nama_produk, ppd.price, ppd.qty, pm.measure_name, ps.nama')
			->from('pmm_penawaran_pembelian ppp')
			->join('pmm_penawaran_pembelian_detail ppd', 'ppp.id = ppd.penawaran_pembelian_id','left')
			->join('produk p', 'ppd.material_id = p.id','left')
			->join('pmm_measures pm', 'ppd.measure = pm.id','left')
			->join('penerima ps', 'ppp.supplier_id = ps.id','left')
			->where("ppp.id = '$rak_alat_gns_6'")
			->group_by('ppd.id')
			->order_by('p.nama_produk','asc')
			->get()->result_array();

			$total_price_gns_6 = 0;
			foreach ($produk_gns_6 as $x){
				$total_price_gns_6 += $x['qty'] * $x['price'];
			}

			$produk_wl_sc_6 = $this->db->select('p.nama_produk, ppd.price, ppd.qty, pm.measure_name, ps.nama')
			->from('pmm_penawaran_pembelian ppp')
			->join('pmm_penawaran_pembelian_detail ppd', 'ppp.id = ppd.penawaran_pembelian_id','left')
			->join('produk p', 'ppd.material_id = p.id','left')
			->join('pmm_measures pm', 'ppd.measure = pm.id','left')
			->join('penerima ps', 'ppp.supplier_id = ps.id','left')
			->where("ppp.id = '$rak_alat_wl_sc_6'")
			->group_by('ppd.id')
			->order_by('p.nama_produk','asc')
			->get()->result_array();

			$total_price_wl_sc_6 = 0;
			foreach ($produk_wl_sc_6 as $x){
				$total_price_wl_sc_6 += $x['qty'] * $x['price'];
			}

			$total_volume_solar_6 = $rak_alat_6['vol_bbm_solar'];

			$total_6_biaya_bahan = $nilai_semen_6 + $nilai_pasir_6 + $nilai_batu1020_6 + $nilai_batu2030_6;
			$total_6_biaya_alat = ($total_price_bp_6 + $total_price_bp_2_6 + $total_price_bp_3_6) + ($total_price_tm_6 + $total_price_tm_2_6 + $total_price_tm_3_6 + $total_price_tm_4_6) + ($total_price_wl_6 + $total_price_wl_2_6 + $total_price_wl_3_6) + ($total_price_tr_6 + $total_price_tr_2_6 + $total_price_tr_3_6) + ($total_volume_solar_6 * $rak_alat_6['harga_solar']) + $rak_alat_6['insentif'] + $total_price_exc_6 + $total_price_dmp_4m3_6 + $total_price_dmp_10m3_6 + $total_price_sc_6 + $total_price_gns_6 + $total_price_wl_sc_6;
			$total_6_overhead = $rencana_kerja_6['overhead'];
			$total_6_diskonto =  ($total_6_nilai * 3) /100;
			$total_biaya_6_biaya = $total_6_biaya_bahan + $total_6_biaya_alat + $total_6_overhead + $total_6_diskonto;
			?>

			<?php
			//BULAN 7
			$date_7_awal = date('Y-m-d', strtotime('+1 days', strtotime($date_6_akhir)));
			$date_7_akhir = date('Y-m-d', strtotime('-1 days +1 months', strtotime($date_7_awal)));

			$rencana_kerja_7 = $this->db->select('r.*')
			->from('rak r')
			->where("r.tanggal_rencana_kerja between '$date_7_awal' and '$date_7_akhir'")
			->get()->row_array();

			$volume_7_produk_a = $rencana_kerja_7['vol_produk_a'];
			$volume_7_produk_b = $rencana_kerja_7['vol_produk_b'];
			$volume_7_produk_c = $rencana_kerja_7['vol_produk_c'];
			$volume_7_produk_d = $rencana_kerja_7['vol_produk_d'];
			$volume_7_produk_e = $rencana_kerja_7['vol_produk_e'];

			$total_7_volume = $volume_7_produk_a + $volume_7_produk_b + $volume_7_produk_c + $volume_7_produk_d + $volume_7_produk_e;

			$nilai_jual_125_7 = $volume_7_produk_a * $rencana_kerja_7['price_a'];
			$nilai_jual_225_7 = $volume_7_produk_b * $rencana_kerja_7['price_b'];
			$nilai_jual_250_7 = $volume_7_produk_c * $rencana_kerja_7['price_c'];
			$nilai_jual_250_18_7 = $volume_7_produk_d * $rencana_kerja_7['price_d'];
			$nilai_jual_300_7 = $volume_7_produk_e * $rencana_kerja_7['price_e'];
			$nilai_jual_all_7 = $nilai_jual_125_7 + $nilai_jual_225_7 + $nilai_jual_250_7 + $nilai_jual_250_18_7 + $nilai_jual_300_7;

			$total_7_nilai = $nilai_jual_all_7;

			//VOLUME
			$volume_rencana_kerja_7_produk_a = $rencana_kerja_7['vol_produk_a'];
			$volume_rencana_kerja_7_produk_b = $rencana_kerja_7['vol_produk_b'];
			$volume_rencana_kerja_7_produk_c = $rencana_kerja_7['vol_produk_c'];
			$volume_rencana_kerja_7_produk_d = $rencana_kerja_7['vol_produk_d'];
			$volume_rencana_kerja_7_produk_e = $rencana_kerja_7['vol_produk_e'];

			//BIAYA

			//BIAYA BAHAN
			$komposisi_125_7 = $this->db->select('(r.vol_produk_a * pk.presentase_a) as komposisi_semen_125, (vol_produk_a * pk.presentase_b) as komposisi_pasir_125, (vol_produk_a * pk.presentase_c) as komposisi_batu1020_125, (vol_produk_a * pk.presentase_d) as komposisi_batu2030_125')
			->from('rak r')
			->join('pmm_agregat pk', 'r.komposisi_125 = pk.id','left')
			->where("r.tanggal_rencana_kerja between '$date_7_awal' and '$date_7_akhir'")
			->get()->result_array();

			$total_volume_semen_125_7 = 0;
			$total_volume_pasir_125_7 = 0;
			$total_volume_batu1020_125_7 = 0;
			$total_volume_batu2030_125_7 = 0;

			foreach ($komposisi_125_7 as $x){
				$total_volume_semen_125_7 = $x['komposisi_semen_125'];
				$total_volume_pasir_125_7 = $x['komposisi_pasir_125'];
				$total_volume_batu1020_125_7 = $x['komposisi_batu1020_125'];
				$total_volume_batu2030_125_7 = $x['komposisi_batu2030_125'];
			}

			$komposisi_225_7 = $this->db->select('(r.vol_produk_b * pk.presentase_a) as komposisi_semen_225, (vol_produk_b * pk.presentase_b) as komposisi_pasir_225, (vol_produk_b * pk.presentase_c) as komposisi_batu1020_225, (vol_produk_b * pk.presentase_d) as komposisi_batu2030_225')
			->from('rak r')
			->join('pmm_agregat pk', 'r.komposisi_225 = pk.id','left')
			->where("r.tanggal_rencana_kerja between '$date_7_awal' and '$date_7_akhir'")
			->get()->result_array();

			$total_volume_semen_225_7 = 0;
			$total_volume_pasir_225_7 = 0;
			$total_volume_batu1020_225_7 = 0;
			$total_volume_batu2030_225_7 = 0;

			foreach ($komposisi_225_7 as $x){
				$total_volume_semen_225_7 = $x['komposisi_semen_225'];
				$total_volume_pasir_225_7 = $x['komposisi_pasir_225'];
				$total_volume_batu1020_225_7 = $x['komposisi_batu1020_225'];
				$total_volume_batu2030_225_7 = $x['komposisi_batu2030_225'];
			}

			$komposisi_250_7 = $this->db->select('(r.vol_produk_c * pk.presentase_a) as komposisi_semen_250, (vol_produk_c * pk.presentase_b) as komposisi_pasir_250, (vol_produk_c * pk.presentase_c) as komposisi_batu1020_250, (vol_produk_c * pk.presentase_d) as komposisi_batu2030_250')
			->from('rak r')
			->join('pmm_agregat pk', 'r.komposisi_250 = pk.id','left')
			->where("r.tanggal_rencana_kerja between '$date_7_awal' and '$date_7_akhir'")
			->get()->result_array();

			$total_volume_semen_250_7 = 0;
			$total_volume_pasir_250_7 = 0;
			$total_volume_batu1020_250_7 = 0;
			$total_volume_batu2030_250_7 = 0;

			foreach ($komposisi_250_7 as $x){
				$total_volume_semen_250_7 = $x['komposisi_semen_250'];
				$total_volume_pasir_250_7 = $x['komposisi_pasir_250'];
				$total_volume_batu1020_250_7 = $x['komposisi_batu1020_250'];
				$total_volume_batu2030_250_7 = $x['komposisi_batu2030_250'];
			}

			$komposisi_250_2_7 = $this->db->select('(r.vol_produk_d * pk.presentase_a) as komposisi_semen_250_2, (vol_produk_d * pk.presentase_b) as komposisi_pasir_250_2, (vol_produk_d * pk.presentase_c) as komposisi_batu1020_250_2, (vol_produk_d * pk.presentase_d) as komposisi_batu2030_250_2')
			->from('rak r')
			->join('pmm_agregat pk', 'r.komposisi_250_2 = pk.id','left')
			->where("r.tanggal_rencana_kerja between '$date_7_awal' and '$date_7_akhir'")
			->get()->result_array();

			$total_volume_semen_250_2_7 = 0;
			$total_volume_pasir_250_2_7 = 0;
			$total_volume_batu1020_250_2_7 = 0;
			$total_volume_batu2030_250_2_7 = 0;

			foreach ($komposisi_250_2_7 as $x){
				$total_volume_semen_250_2_7 = $x['komposisi_semen_250_2'];
				$total_volume_pasir_250_2_7 = $x['komposisi_pasir_250_2'];
				$total_volume_batu1020_250_2_7 = $x['komposisi_batu1020_250_2'];
				$total_volume_batu2030_250_2_7 = $x['komposisi_batu2030_250_2'];
			}

			$komposisi_300_7 = $this->db->select('(r.vol_produk_e * pk.presentase_a) as komposisi_semen_300_7, (vol_produk_e * pk.presentase_b) as komposisi_pasir_300_7, (vol_produk_e * pk.presentase_c) as komposisi_batu1020_300_7, (vol_produk_e * pk.presentase_d) as komposisi_batu2030_300_7')
			->from('rak r')
			->join('pmm_agregat pk', 'r.komposisi_300 = pk.id','left')
			->where("r.tanggal_rencana_kerja between '$date_7_awal' and '$date_7_akhir'")
			->get()->result_array();

			$total_volume_semen_300_7 = 0;
			$total_volume_pasir_300_7 = 0;
			$total_volume_batu1020_300_7 = 0;
			$total_volume_batu2030_300_7 = 0;

			foreach ($komposisi_300_7 as $x){
				$total_volume_semen_300_7 = $x['komposisi_semen_300_7'];
				$total_volume_pasir_300_7 = $x['komposisi_pasir_300_7'];
				$total_volume_batu1020_300_7 = $x['komposisi_batu1020_300_7'];
				$total_volume_batu2030_300_7 = $x['komposisi_batu2030_300_7'];
			}

			$total_volume_semen_7 = $total_volume_semen_125_7 + $total_volume_semen_225_7 + $total_volume_semen_250_7 + $total_volume_semen_250_2_7 + $total_volume_semen_300_7;
			$total_volume_pasir_7 = $total_volume_pasir_125_7 + $total_volume_pasir_225_7 + $total_volume_pasir_250_7 + $total_volume_pasir_250_2_7 + $total_volume_pasir_300_7;
			$total_volume_batu1020_7 = $total_volume_batu1020_125_7 + $total_volume_batu1020_225_7 + $total_volume_batu1020_250_7 + $total_volume_batu1020_250_2_7 + $total_volume_batu1020_300_7;
			$total_volume_batu2030_7 = $total_volume_batu2030_125_7 + $total_volume_batu2030_225_7 + $total_volume_batu2030_250_7 + $total_volume_batu2030_250_2_7 + $total_volume_batu2030_300_7;

			$nilai_semen_7 = $total_volume_semen_7 * $rencana_kerja_7['harga_semen'];
			$nilai_pasir_7 = $total_volume_pasir_7 * $rencana_kerja_7['harga_pasir'];
			$nilai_batu1020_7 = $total_volume_batu1020_7 * $rencana_kerja_7['harga_batu1020'];
			$nilai_batu2030_7 = $total_volume_batu2030_7 * $rencana_kerja_7['harga_batu2030'];

			//BIAYA ALAT
			$rak_alat_7 = $this->db->select('r.*, (r.vol_produk_a + r.vol_produk_b + r.vol_produk_c + r.vol_produk_d) as total_produksi')
			->from('rak r')
			->where("r.tanggal_rencana_kerja between '$date_7_awal' and '$date_7_akhir'")
			->get()->row_array();

			$rak_alat_bp_7 = $rak_alat_7['penawaran_id_bp'];
			$rak_alat_bp_2_7 = $rak_alat_7['penawaran_id_bp_2'];
			$rak_alat_bp_3_7 = $rak_alat_7['penawaran_id_bp_3'];

			$rak_alat_tm_7 = $rak_alat_7['penawaran_id_tm'];
			$rak_alat_tm_2_7 = $rak_alat_7['penawaran_id_tm_2'];
			$rak_alat_tm_3_7 = $rak_alat_7['penawaran_id_tm_3'];
			$rak_alat_tm_4_7 = $rak_alat_7['penawaran_id_tm_4'];

			$rak_alat_wl_7 = $rak_alat_7['penawaran_id_wl'];
			$rak_alat_wl_2_7 = $rak_alat_7['penawaran_id_wl_2'];
			$rak_alat_wl_3_7 = $rakrak_alat_7_alat['penawaran_id_wl_3'];

			$rak_alat_tr_7 = $rak_alat_7['penawaran_id_tr'];
			$rak_alat_tr_2_7 = $rak_alat_7['penawaran_id_tr_2'];
			$rak_alat_tr_3_7 = $rak_alat_7['penawaran_id_tr_3'];

			$rak_alat_exc_7 = $rak_alat_7['penawaran_id_exc'];
			$rak_alat_dmp_4m3_7 = $rak_alat_7['penawaran_id_dmp_4m3'];
			$rak_alat_dmp_10m3_7 = $rak_alat_7['penawaran_id_dmp_10m3'];
			$rak_alat_sc_7 = $rak_alat_7['penawaran_id_sc'];
			$rak_alat_gns_7 = $rak_alat_7['penawaran_id_gns'];
			$rak_alat_wl_sc_7 = $rak_alat_7['penawaran_id_wl_sc'];

			$produk_bp_7 = $this->db->select('p.nama_produk, ppd.price, ppd.qty, pm.measure_name, ps.nama, sum(vol_produk_a + vol_produk_b + vol_produk_c + vol_produk_d) as total_vol_produksi')
			->from('pmm_penawaran_pembelian ppp')
			->join('pmm_penawaran_pembelian_detail ppd', 'ppp.id = ppd.penawaran_pembelian_id','left')
			->join('produk p', 'ppd.material_id = p.id','left')
			->join('pmm_measures pm', 'ppd.measure = pm.id','left')
			->join('penerima ps', 'ppp.supplier_id = ps.id','left')
			->join('rak r', 'ppp.id = r.penawaran_id_bp','left')
			->where("r.tanggal_rencana_kerja between '$date_7_awal' and '$date_7_akhir'")
			->where("ppp.id = '$rak_alat_bp_7'")
			->group_by('ppd.id')
			->order_by('p.nama_produk','asc')
			->get()->result_array();

			$total_price_bp_7 = 0;
			foreach ($produk_bp_7 as $x){
				$total_price_bp_7 += $x['total_vol_produksi'] * $x['price'];
			}

			$produk_bp_2_7 = $this->db->select('p.nama_produk, ppd.price, ppd.qty, pm.measure_name, ps.nama, sum(vol_produk_a + vol_produk_b + vol_produk_c + vol_produk_d) as total_vol_produksi')
			->from('pmm_penawaran_pembelian ppp')
			->join('pmm_penawaran_pembelian_detail ppd', 'ppp.id = ppd.penawaran_pembelian_id','left')
			->join('produk p', 'ppd.material_id = p.id','left')
			->join('pmm_measures pm', 'ppd.measure = pm.id','left')
			->join('penerima ps', 'ppp.supplier_id = ps.id','left')
			->join('rak r', 'ppp.id = r.penawaran_id_bp','left')
			->where("r.tanggal_rencana_kerja between '$date_7_awal' and '$date_7_akhir'")
			->where("ppp.id = '$rak_alat_bp_2_7'")
			->group_by('ppd.id')
			->order_by('p.nama_produk','asc')
			->get()->result_array();

			$total_price_bp_2_7 = 0;
			foreach ($produk_bp_2_7 as $x){
				$total_price_bp_2_7 += $x['total_vol_produksi'] * $x['price'];
			}

			$produk_bp_3_7 = $this->db->select('p.nama_produk, ppd.price, ppd.qty, pm.measure_name, ps.nama, sum(vol_produk_a + vol_produk_b + vol_produk_c + vol_produk_d) as total_vol_produksi')
			->from('pmm_penawaran_pembelian ppp')
			->join('pmm_penawaran_pembelian_detail ppd', 'ppp.id = ppd.penawaran_pembelian_id','left')
			->join('produk p', 'ppd.material_id = p.id','left')
			->join('pmm_measures pm', 'ppd.measure = pm.id','left')
			->join('penerima ps', 'ppp.supplier_id = ps.id','left')
			->join('rak r', 'ppp.id = r.penawaran_id_bp','left')
			->where("r.tanggal_rencana_kerja between '$date_7_awal' and '$date_7_akhir'")
			->where("ppp.id = '$rak_alat_bp_3_7'")
			->group_by('ppd.id')
			->order_by('p.nama_produk','asc')
			->get()->result_array();

			$total_price_bp_3_7 = 0;
			foreach ($produk_bp_3_7 as $x){
				$total_price_bp_3_7 += $x['total_vol_produksi'] * $x['price'];
			}

			$produk_tm_7 = $this->db->select('p.nama_produk, ppd.price, ppd.qty, pm.measure_name')
			->from('pmm_penawaran_pembelian ppp')
			->join('pmm_penawaran_pembelian_detail ppd', 'ppp.id = ppd.penawaran_pembelian_id','left')
			->join('produk p', 'ppd.material_id = p.id','left')
			->join('pmm_measures pm', 'ppd.measure = pm.id','left')
			->where("ppp.id = '$rak_alat_tm_7'")
			->group_by('ppd.id')
			->order_by('p.nama_produk','asc')
			->get()->result_array();

			$total_price_tm_7 = 0;
			foreach ($produk_tm_7 as $x){
				$total_price_tm_7 += $x['qty'] * $x['price'];
			}

			$produk_tm_2_7 = $this->db->select('p.nama_produk, ppd.price, ppd.qty, pm.measure_name')
			->from('pmm_penawaran_pembelian ppp')
			->join('pmm_penawaran_pembelian_detail ppd', 'ppp.id = ppd.penawaran_pembelian_id','left')
			->join('produk p', 'ppd.material_id = p.id','left')
			->join('pmm_measures pm', 'ppd.measure = pm.id','left')
			->where("ppp.id = '$rak_alat_tm_2_7'")
			->group_by('ppd.id')
			->order_by('p.nama_produk','asc')
			->get()->result_array();

			$total_price_tm_2_7 = 0;
			foreach ($produk_tm_2_7 as $x){
				$total_price_tm_2_7 += $x['qty'] * $x['price'];
			}

			$produk_tm_3_7 = $this->db->select('p.nama_produk, ppd.price, ppd.qty, pm.measure_name')
			->from('pmm_penawaran_pembelian ppp')
			->join('pmm_penawaran_pembelian_detail ppd', 'ppp.id = ppd.penawaran_pembelian_id','left')
			->join('produk p', 'ppd.material_id = p.id','left')
			->join('pmm_measures pm', 'ppd.measure = pm.id','left')
			->where("ppp.id = '$rak_alat_tm_3_7'")
			->group_by('ppd.id')
			->order_by('p.nama_produk','asc')
			->get()->result_array();

			$total_price_tm_3_7 = 0;
			foreach ($produk_tm_3_7 as $x){
				$total_price_tm_3_7 += $x['qty'] * $x['price'];
			}

			$produk_tm_4_7 = $this->db->select('p.nama_produk, ppd.price, ppd.qty, pm.measure_name')
			->from('pmm_penawaran_pembelian ppp')
			->join('pmm_penawaran_pembelian_detail ppd', 'ppp.id = ppd.penawaran_pembelian_id','left')
			->join('produk p', 'ppd.material_id = p.id','left')
			->join('pmm_measures pm', 'ppd.measure = pm.id','left')
			->where("ppp.id = '$rak_alat_tm_4_7'")
			->group_by('ppd.id')
			->order_by('p.nama_produk','asc')
			->get()->result_array();

			$total_price_tm_4_7 = 0;
			foreach ($produk_tm_4_7 as $x){
				$total_price_tm_4_7 += $x['qty'] * $x['price'];
			}

			$produk_wl_7 = $this->db->select('p.nama_produk, ppd.price, ppd.qty, pm.measure_name')
			->from('pmm_penawaran_pembelian ppp')
			->join('pmm_penawaran_pembelian_detail ppd', 'ppp.id = ppd.penawaran_pembelian_id','left')
			->join('produk p', 'ppd.material_id = p.id','left')
			->join('pmm_measures pm', 'ppd.measure = pm.id','left')
			->where("ppp.id = '$rak_alat_wl_7'")
			->group_by('ppd.id')
			->order_by('p.nama_produk','asc')
			->get()->result_array();

			$total_price_wl_7 = 0;
			foreach ($produk_wl_7 as $x){
				$total_price_wl_7 += $x['qty'] * $x['price'];
			}

			$produk_wl_2_7 = $this->db->select('p.nama_produk, ppd.price, ppd.qty, pm.measure_name')
			->from('pmm_penawaran_pembelian ppp')
			->join('pmm_penawaran_pembelian_detail ppd', 'ppp.id = ppd.penawaran_pembelian_id','left')
			->join('produk p', 'ppd.material_id = p.id','left')
			->join('pmm_measures pm', 'ppd.measure = pm.id','left')
			->where("ppp.id = '$rak_alat_wl_2_7'")
			->group_by('ppd.id')
			->order_by('p.nama_produk','asc')
			->get()->result_array();

			$total_price_wl_2_7 = 0;
			foreach ($produk_wl_2_7 as $x){
				$total_price_wl_2_7 += $x['qty'] * $x['price'];
			}

			$produk_wl_3_7 = $this->db->select('p.nama_produk, ppd.price, ppd.qty, pm.measure_name')
			->from('pmm_penawaran_pembelian ppp')
			->join('pmm_penawaran_pembelian_detail ppd', 'ppp.id = ppd.penawaran_pembelian_id','left')
			->join('produk p', 'ppd.material_id = p.id','left')
			->join('pmm_measures pm', 'ppd.measure = pm.id','left')
			->where("ppp.id = '$rak_alat_wl_3_7'")
			->group_by('ppd.id')
			->order_by('p.nama_produk','asc')
			->get()->result_array();

			$total_price_wl_3_7 = 0;
			foreach ($produk_wl_3_7 as $x){
				$total_price_wl_3_7 += $x['qty'] * $x['price'];
			}

			$produk_tr_7 = $this->db->select('p.nama_produk, ppd.price, ppd.qty, pm.measure_name, ps.nama')
			->from('pmm_penawaran_pembelian ppp')
			->join('pmm_penawaran_pembelian_detail ppd', 'ppp.id = ppd.penawaran_pembelian_id','left')
			->join('produk p', 'ppd.material_id = p.id','left')
			->join('pmm_measures pm', 'ppd.measure = pm.id','left')
			->join('penerima ps', 'ppp.supplier_id = ps.id','left')
			->where("ppp.id = '$rak_alat_tr_7'")
			->group_by('ppd.id')
			->order_by('p.nama_produk','asc')
			->get()->result_array();

			$total_price_tr_7 = 0;
			foreach ($produk_tr_7 as $x){
				$total_price_tr_7 += $x['qty'] * $x['price'];
			}

			$produk_tr_2_7 = $this->db->select('p.nama_produk, ppd.price, ppd.qty, pm.measure_name, ps.nama')
			->from('pmm_penawaran_pembelian ppp')
			->join('pmm_penawaran_pembelian_detail ppd', 'ppp.id = ppd.penawaran_pembelian_id','left')
			->join('produk p', 'ppd.material_id = p.id','left')
			->join('pmm_measures pm', 'ppd.measure = pm.id','left')
			->join('penerima ps', 'ppp.supplier_id = ps.id','left')
			->where("ppp.id = '$rak_alat_tr_2_7'")
			->group_by('ppd.id')
			->order_by('p.nama_produk','asc')
			->get()->result_array();

			$total_price_tr_2_7 = 0;
			foreach ($produk_tr_2_7 as $x){
				$total_price_tr_2_7 += $x['qty'] * $x['price'];
			}

			$produk_tr_3_7 = $this->db->select('p.nama_produk, ppd.price, ppd.qty, pm.measure_name, ps.nama')
			->from('pmm_penawaran_pembelian ppp')
			->join('pmm_penawaran_pembelian_detail ppd', 'ppp.id = ppd.penawaran_pembelian_id','left')
			->join('produk p', 'ppd.material_id = p.id','left')
			->join('pmm_measures pm', 'ppd.measure = pm.id','left')
			->join('penerima ps', 'ppp.supplier_id = ps.id','left')
			->where("ppp.id = '$rak_alat_tr_3_7'")
			->group_by('ppd.id')
			->order_by('p.nama_produk','asc')
			->get()->result_array();

			$total_price_tr_3_7 = 0;
			foreach ($produk_tr_3_7 as $x){
				$total_price_tr_3_7 += $x['qty'] * $x['price'];
			}

			$produk_exc_7 = $this->db->select('p.nama_produk, ppd.price, ppd.qty, pm.measure_name, ps.nama')
			->from('pmm_penawaran_pembelian ppp')
			->join('pmm_penawaran_pembelian_detail ppd', 'ppp.id = ppd.penawaran_pembelian_id','left')
			->join('produk p', 'ppd.material_id = p.id','left')
			->join('pmm_measures pm', 'ppd.measure = pm.id','left')
			->join('penerima ps', 'ppp.supplier_id = ps.id','left')
			->where("ppp.id = '$rak_alat_exc_7'")
			->group_by('ppd.id')
			->order_by('p.nama_produk','asc')
			->get()->result_array();

			$total_price_exc_7 = 0;
			foreach ($produk_exc_7 as $x){
				$total_price_exc_7 += $x['qty'] * $x['price'];
			}

			$produk_dmp_4m3_7 = $this->db->select('p.nama_produk, ppd.price, ppd.qty, pm.measure_name, ps.nama')
			->from('pmm_penawaran_pembelian ppp')
			->join('pmm_penawaran_pembelian_detail ppd', 'ppp.id = ppd.penawaran_pembelian_id','left')
			->join('produk p', 'ppd.material_id = p.id','left')
			->join('pmm_measures pm', 'ppd.measure = pm.id','left')
			->join('penerima ps', 'ppp.supplier_id = ps.id','left')
			->where("ppp.id = '$rak_alat_dmp_4m3_7'")
			->group_by('ppd.id')
			->order_by('p.nama_produk','asc')
			->get()->result_array();

			$total_price_dmp_4m3_7 = 0;
			foreach ($produk_dmp_4m3_7 as $x){
				$total_price_dmp_4m3_7 += $x['qty'] * $x['price'];
			}

			$produk_dmp_10m3_7 = $this->db->select('p.nama_produk, ppd.price, ppd.qty, pm.measure_name, ps.nama')
			->from('pmm_penawaran_pembelian ppp')
			->join('pmm_penawaran_pembelian_detail ppd', 'ppp.id = ppd.penawaran_pembelian_id','left')
			->join('produk p', 'ppd.material_id = p.id','left')
			->join('pmm_measures pm', 'ppd.measure = pm.id','left')
			->join('penerima ps', 'ppp.supplier_id = ps.id','left')
			->where("ppp.id = '$rak_alat_dmp_10m3_7'")
			->group_by('ppd.id')
			->order_by('p.nama_produk','asc')
			->get()->result_array();

			$total_price_dmp_10m3_7 = 0;
			foreach ($produk_dmp_10m3_7 as $x){
				$total_price_dmp_10m3_7 += $x['qty'] * $x['price'];
			}

			$produk_sc_7 = $this->db->select('p.nama_produk, ppd.price, ppd.qty, pm.measure_name, ps.nama')
			->from('pmm_penawaran_pembelian ppp')
			->join('pmm_penawaran_pembelian_detail ppd', 'ppp.id = ppd.penawaran_pembelian_id','left')
			->join('produk p', 'ppd.material_id = p.id','left')
			->join('pmm_measures pm', 'ppd.measure = pm.id','left')
			->join('penerima ps', 'ppp.supplier_id = ps.id','left')
			->where("ppp.id = '$rak_alat_sc_7'")
			->group_by('ppd.id')
			->order_by('p.nama_produk','asc')
			->get()->result_array();

			$total_price_sc_7 = 0;
			foreach ($produk_sc_7 as $x){
				$total_price_sc_7 += $x['qty'] * $x['price'];
			}

			$produk_gns_7 = $this->db->select('p.nama_produk, ppd.price, ppd.qty, pm.measure_name, ps.nama')
			->from('pmm_penawaran_pembelian ppp')
			->join('pmm_penawaran_pembelian_detail ppd', 'ppp.id = ppd.penawaran_pembelian_id','left')
			->join('produk p', 'ppd.material_id = p.id','left')
			->join('pmm_measures pm', 'ppd.measure = pm.id','left')
			->join('penerima ps', 'ppp.supplier_id = ps.id','left')
			->where("ppp.id = '$rak_alat_gns_7'")
			->group_by('ppd.id')
			->order_by('p.nama_produk','asc')
			->get()->result_array();

			$total_price_gns_7 = 0;
			foreach ($produk_gns_7 as $x){
				$total_price_gns_7 += $x['qty'] * $x['price'];
			}

			$produk_wl_sc_7 = $this->db->select('p.nama_produk, ppd.price, ppd.qty, pm.measure_name, ps.nama')
			->from('pmm_penawaran_pembelian ppp')
			->join('pmm_penawaran_pembelian_detail ppd', 'ppp.id = ppd.penawaran_pembelian_id','left')
			->join('produk p', 'ppd.material_id = p.id','left')
			->join('pmm_measures pm', 'ppd.measure = pm.id','left')
			->join('penerima ps', 'ppp.supplier_id = ps.id','left')
			->where("ppp.id = '$rak_alat_wl_sc_7'")
			->group_by('ppd.id')
			->order_by('p.nama_produk','asc')
			->get()->result_array();

			$total_price_wl_sc_7 = 0;
			foreach ($produk_wl_sc_7 as $x){
				$total_price_wl_sc_7 += $x['qty'] * $x['price'];
			}

			$total_volume_solar_7 = $rak_alat_7['vol_bbm_solar'];

			$total_7_biaya_bahan = $nilai_semen_7 + $nilai_pasir_7 + $nilai_batu1020_7 + $nilai_batu2030_7;
			$total_7_biaya_alat = ($total_price_bp_7 + $total_price_bp_2_7 + $total_price_bp_3_7) + ($total_price_tm_7 + $total_price_tm_2_7 + $total_price_tm_3_7 + $total_price_tm_4_7) + ($total_price_wl_7 + $total_price_wl_2_7 + $total_price_wl_3_7) + ($total_price_tr_7 + $total_price_tr_2_7 + $total_price_tr_3_7) + ($total_volume_solar_7 * $rak_alat_7['harga_solar']) + $rak_alat_7['insentif'] + $total_price_exc_7 + $total_price_dmp_4m3_7 + $total_price_dmp_10m3_7 + $total_price_sc_7 + $total_price_gns_7 + $total_price_wl_sc_7;
			$total_7_overhead = $rencana_kerja_7['overhead'];
			$total_7_diskonto =  ($total_7_nilai * 3) /100;
			$total_biaya_7_biaya = $total_7_biaya_bahan + $total_7_biaya_alat + $total_7_overhead + $total_7_diskonto;
			?>

			<?php
			//BULAN 8
			$date_8_awal = date('Y-m-d', strtotime('+1 days', strtotime($date_7_akhir)));
			$date_8_akhir = date('Y-m-d', strtotime('-1 days +1 months', strtotime($date_8_awal)));

			$rencana_kerja_8 = $this->db->select('r.*')
			->from('rak r')
			->where("r.tanggal_rencana_kerja between '$date_8_awal' and '$date_8_akhir'")
			->get()->row_array();

			$volume_8_produk_a = $rencana_kerja_8['vol_produk_a'];
			$volume_8_produk_b = $rencana_kerja_8['vol_produk_b'];
			$volume_8_produk_c = $rencana_kerja_8['vol_produk_c'];
			$volume_8_produk_d = $rencana_kerja_8['vol_produk_d'];
			$volume_8_produk_e = $rencana_kerja_8['vol_produk_e'];

			$total_8_volume = $volume_8_produk_a + $volume_8_produk_b + $volume_8_produk_c + $volume_8_produk_d + $volume_8_produk_e;

			$nilai_jual_125_8 = $volume_8_produk_a * $rencana_kerja_8['price_a'];
			$nilai_jual_225_8 = $volume_8_produk_b * $rencana_kerja_8['price_b'];
			$nilai_jual_250_8 = $volume_8_produk_c * $rencana_kerja_8['price_c'];
			$nilai_jual_250_18_8 = $volume_8_produk_d * $rencana_kerja_8['price_d'];
			$nilai_jual_300_8 = $volume_8_produk_e * $rencana_kerja_8['price_e'];
			$nilai_jual_all_8 = $nilai_jual_125_8 + $nilai_jual_225_8 + $nilai_jual_250_8 + $nilai_jual_250_18_8 + $nilai_jual_300_8;

			$total_8_nilai = $nilai_jual_all_8;

			//VOLUME
			$volume_rencana_kerja_8_produk_a = $rencana_kerja_8['vol_produk_a'];
			$volume_rencana_kerja_8_produk_b = $rencana_kerja_8['vol_produk_b'];
			$volume_rencana_kerja_8_produk_c = $rencana_kerja_8['vol_produk_c'];
			$volume_rencana_kerja_8_produk_d = $rencana_kerja_8['vol_produk_d'];

			//BIAYA

			//BIAYA BAHAN
			$komposisi_125_8 = $this->db->select('(r.vol_produk_a * pk.presentase_a) as komposisi_semen_125, (vol_produk_a * pk.presentase_b) as komposisi_pasir_125, (vol_produk_a * pk.presentase_c) as komposisi_batu1020_125, (vol_produk_a * pk.presentase_d) as komposisi_batu2030_125')
			->from('rak r')
			->join('pmm_agregat pk', 'r.komposisi_125 = pk.id','left')
			->where("r.tanggal_rencana_kerja between '$date_8_awal' and '$date_8_akhir'")
			->get()->result_array();

			$total_volume_semen_125_8 = 0;
			$total_volume_pasir_125_8 = 0;
			$total_volume_batu1020_125_8 = 0;
			$total_volume_batu2030_125_8 = 0;

			foreach ($komposisi_125_8 as $x){
				$total_volume_semen_125_8 = $x['komposisi_semen_125'];
				$total_volume_pasir_125_8 = $x['komposisi_pasir_125'];
				$total_volume_batu1020_125_8 = $x['komposisi_batu1020_125'];
				$total_volume_batu2030_125_8 = $x['komposisi_batu2030_125'];
			}

			$komposisi_225_8 = $this->db->select('(r.vol_produk_b * pk.presentase_a) as komposisi_semen_225, (vol_produk_b * pk.presentase_b) as komposisi_pasir_225, (vol_produk_b * pk.presentase_c) as komposisi_batu1020_225, (vol_produk_b * pk.presentase_d) as komposisi_batu2030_225')
			->from('rak r')
			->join('pmm_agregat pk', 'r.komposisi_225 = pk.id','left')
			->where("r.tanggal_rencana_kerja between '$date_8_awal' and '$date_8_akhir'")
			->get()->result_array();

			$total_volume_semen_225_8 = 0;
			$total_volume_pasir_225_8 = 0;
			$total_volume_batu1020_225_8 = 0;
			$total_volume_batu2030_225_8 = 0;

			foreach ($komposisi_225_8 as $x){
				$total_volume_semen_225_8 = $x['komposisi_semen_225'];
				$total_volume_pasir_225_8 = $x['komposisi_pasir_225'];
				$total_volume_batu1020_225_8 = $x['komposisi_batu1020_225'];
				$total_volume_batu2030_225_8 = $x['komposisi_batu2030_225'];
			}

			$komposisi_250_8 = $this->db->select('(r.vol_produk_c * pk.presentase_a) as komposisi_semen_250, (vol_produk_c * pk.presentase_b) as komposisi_pasir_250, (vol_produk_c * pk.presentase_c) as komposisi_batu1020_250, (vol_produk_c * pk.presentase_d) as komposisi_batu2030_250')
			->from('rak r')
			->join('pmm_agregat pk', 'r.komposisi_250 = pk.id','left')
			->where("r.tanggal_rencana_kerja between '$date_8_awal' and '$date_8_akhir'")
			->get()->result_array();

			$total_volume_semen_250_8 = 0;
			$total_volume_pasir_250_8 = 0;
			$total_volume_batu1020_250_8 = 0;
			$total_volume_batu2030_250_8 = 0;

			foreach ($komposisi_250_8 as $x){
				$total_volume_semen_250_8 = $x['komposisi_semen_250'];
				$total_volume_pasir_250_8 = $x['komposisi_pasir_250'];
				$total_volume_batu1020_250_8 = $x['komposisi_batu1020_250'];
				$total_volume_batu2030_250_8 = $x['komposisi_batu2030_250'];
			}

			$komposisi_250_2_8 = $this->db->select('(r.vol_produk_d * pk.presentase_a) as komposisi_semen_250_2, (vol_produk_d * pk.presentase_b) as komposisi_pasir_250_2, (vol_produk_d * pk.presentase_c) as komposisi_batu1020_250_2, (vol_produk_d * pk.presentase_d) as komposisi_batu2030_250_2')
			->from('rak r')
			->join('pmm_agregat pk', 'r.komposisi_250_2 = pk.id','left')
			->where("r.tanggal_rencana_kerja between '$date_8_awal' and '$date_8_akhir'")
			->get()->result_array();

			$total_volume_semen_250_2_8 = 0;
			$total_volume_pasir_250_2_8 = 0;
			$total_volume_batu1020_250_2_8 = 0;
			$total_volume_batu2030_250_2_8 = 0;

			foreach ($komposisi_250_2_8 as $x){
				$total_volume_semen_250_2_8 = $x['komposisi_semen_250_2'];
				$total_volume_pasir_250_2_8 = $x['komposisi_pasir_250_2'];
				$total_volume_batu1020_250_2_8 = $x['komposisi_batu1020_250_2'];
				$total_volume_batu2030_250_2_8 = $x['komposisi_batu2030_250_2'];
			}

			$komposisi_300_8 = $this->db->select('(r.vol_produk_e * pk.presentase_a) as komposisi_semen_300_8, (vol_produk_e * pk.presentase_b) as komposisi_pasir_300_8, (vol_produk_e * pk.presentase_c) as komposisi_batu1020_300_8, (vol_produk_e * pk.presentase_d) as komposisi_batu2030_300_8')
			->from('rak r')
			->join('pmm_agregat pk', 'r.komposisi_300 = pk.id','left')
			->where("r.tanggal_rencana_kerja between '$date_8_awal' and '$date_8_akhir'")
			->get()->result_array();

			$total_volume_semen_300_8 = 0;
			$total_volume_pasir_300_8 = 0;
			$total_volume_batu1020_300_8 = 0;
			$total_volume_batu2030_300_8 = 0;

			foreach ($komposisi_300_8 as $x){
				$total_volume_semen_300_8 = $x['komposisi_semen_300_8'];
				$total_volume_pasir_300_8 = $x['komposisi_pasir_300_8'];
				$total_volume_batu1020_300_8 = $x['komposisi_batu1020_300_8'];
				$total_volume_batu2030_300_8 = $x['komposisi_batu2030_300_8'];
			}

			$total_volume_semen_8 = $total_volume_semen_125_8 + $total_volume_semen_225_8 + $total_volume_semen_250_8 + $total_volume_semen_250_2_8 + $total_volume_semen_300_8;
			$total_volume_pasir_8 = $total_volume_pasir_125_8 + $total_volume_pasir_225_8 + $total_volume_pasir_250_8 + $total_volume_pasir_250_2_8 + $total_volume_pasir_300_8;
			$total_volume_batu1020_8 = $total_volume_batu1020_125_8 + $total_volume_batu1020_225_8 + $total_volume_batu1020_250_8 + $total_volume_batu1020_250_2_8 + $total_volume_batu1020_300_8;
			$total_volume_batu2030_8 = $total_volume_batu2030_125_8 + $total_volume_batu2030_225_8 + $total_volume_batu2030_250_8 + $total_volume_batu2030_250_2_8 + $total_volume_batu2030_300_8;

			$nilai_semen_8 = $total_volume_semen_8 * $rencana_kerja_8['harga_semen'];
			$nilai_pasir_8 = $total_volume_pasir_8 * $rencana_kerja_8['harga_pasir'];
			$nilai_batu1020_8 = $total_volume_batu1020_8 * $rencana_kerja_8['harga_batu1020'];
			$nilai_batu2030_8 = $total_volume_batu2030_8 * $rencana_kerja_8['harga_batu2030'];

			//BIAYA ALAT
			$rak_alat_8 = $this->db->select('r.*, (r.vol_produk_a + r.vol_produk_b + r.vol_produk_c + r.vol_produk_d) as total_produksi')
			->from('rak r')
			->where("r.tanggal_rencana_kerja between '$date_8_awal' and '$date_8_akhir'")
			->get()->row_array();

			$rak_alat_bp_8 = $rak_alat_8['penawaran_id_bp'];
			$rak_alat_bp_2_8 = $rak_alat_8['penawaran_id_bp_2'];
			$rak_alat_bp_3_8 = $rak_alat_8['penawaran_id_bp_3'];

			$rak_alat_tm_8 = $rak_alat_8['penawaran_id_tm'];
			$rak_alat_tm_2_8 = $rak_alat_8['penawaran_id_tm_2'];
			$rak_alat_tm_3_8 = $rak_alat_8['penawaran_id_tm_3'];
			$rak_alat_tm_4_8 = $rak_alat_8['penawaran_id_tm_4'];

			$rak_alat_wl_8 = $rak_alat_8['penawaran_id_wl'];
			$rak_alat_wl_2_8 = $rak_alat_8['penawaran_id_wl_2'];
			$rak_alat_wl_3_8 = $rakrak_alat_8_alat['penawaran_id_wl_3'];

			$rak_alat_tr_8 = $rak_alat_8['penawaran_id_tr'];
			$rak_alat_tr_2_8 = $rak_alat_8['penawaran_id_tr_2'];
			$rak_alat_tr_3_8 = $rak_alat_8['penawaran_id_tr_3'];

			$rak_alat_exc_8 = $rak_alat_8['penawaran_id_exc'];
			$rak_alat_dmp_4m3_8 = $rak_alat_8['penawaran_id_dmp_4m3'];
			$rak_alat_dmp_10m3_8 = $rak_alat_8['penawaran_id_dmp_10m3'];
			$rak_alat_sc_8 = $rak_alat_8['penawaran_id_sc'];
			$rak_alat_gns_8 = $rak_alat_8['penawaran_id_gns'];
			$rak_alat_wl_sc_8 = $rak_alat_8['penawaran_id_wl_sc'];

			$produk_bp_8 = $this->db->select('p.nama_produk, ppd.price, ppd.qty, pm.measure_name, ps.nama, sum(vol_produk_a + vol_produk_b + vol_produk_c + vol_produk_d) as total_vol_produksi')
			->from('pmm_penawaran_pembelian ppp')
			->join('pmm_penawaran_pembelian_detail ppd', 'ppp.id = ppd.penawaran_pembelian_id','left')
			->join('produk p', 'ppd.material_id = p.id','left')
			->join('pmm_measures pm', 'ppd.measure = pm.id','left')
			->join('penerima ps', 'ppp.supplier_id = ps.id','left')
			->join('rak r', 'ppp.id = r.penawaran_id_bp','left')
			->where("r.tanggal_rencana_kerja between '$date_8_awal' and '$date_8_akhir'")
			->where("ppp.id = '$rak_alat_bp_8'")
			->group_by('ppd.id')
			->order_by('p.nama_produk','asc')
			->get()->result_array();

			$total_price_bp_8 = 0;
			foreach ($produk_bp_8 as $x){
				$total_price_bp_8 += $x['total_vol_produksi'] * $x['price'];
			}

			$produk_bp_2_8 = $this->db->select('p.nama_produk, ppd.price, ppd.qty, pm.measure_name, ps.nama, sum(vol_produk_a + vol_produk_b + vol_produk_c + vol_produk_d) as total_vol_produksi')
			->from('pmm_penawaran_pembelian ppp')
			->join('pmm_penawaran_pembelian_detail ppd', 'ppp.id = ppd.penawaran_pembelian_id','left')
			->join('produk p', 'ppd.material_id = p.id','left')
			->join('pmm_measures pm', 'ppd.measure = pm.id','left')
			->join('penerima ps', 'ppp.supplier_id = ps.id','left')
			->join('rak r', 'ppp.id = r.penawaran_id_bp','left')
			->where("r.tanggal_rencana_kerja between '$date_8_awal' and '$date_8_akhir'")
			->where("ppp.id = '$rak_alat_bp_2_8'")
			->group_by('ppd.id')
			->order_by('p.nama_produk','asc')
			->get()->result_array();

			$total_price_bp_2_8 = 0;
			foreach ($produk_bp_2_8 as $x){
				$total_price_bp_2_8 += $x['total_vol_produksi'] * $x['price'];
			}

			$produk_bp_3_8 = $this->db->select('p.nama_produk, ppd.price, ppd.qty, pm.measure_name, ps.nama, sum(vol_produk_a + vol_produk_b + vol_produk_c + vol_produk_d) as total_vol_produksi')
			->from('pmm_penawaran_pembelian ppp')
			->join('pmm_penawaran_pembelian_detail ppd', 'ppp.id = ppd.penawaran_pembelian_id','left')
			->join('produk p', 'ppd.material_id = p.id','left')
			->join('pmm_measures pm', 'ppd.measure = pm.id','left')
			->join('penerima ps', 'ppp.supplier_id = ps.id','left')
			->join('rak r', 'ppp.id = r.penawaran_id_bp','left')
			->where("r.tanggal_rencana_kerja between '$date_8_awal' and '$date_8_akhir'")
			->where("ppp.id = '$rak_alat_bp_3_8'")
			->group_by('ppd.id')
			->order_by('p.nama_produk','asc')
			->get()->result_array();

			$total_price_bp_3_8 = 0;
			foreach ($produk_bp_3_8 as $x){
				$total_price_bp_3_8 += $x['total_vol_produksi'] * $x['price'];
			}

			$produk_tm_8 = $this->db->select('p.nama_produk, ppd.price, ppd.qty, pm.measure_name')
			->from('pmm_penawaran_pembelian ppp')
			->join('pmm_penawaran_pembelian_detail ppd', 'ppp.id = ppd.penawaran_pembelian_id','left')
			->join('produk p', 'ppd.material_id = p.id','left')
			->join('pmm_measures pm', 'ppd.measure = pm.id','left')
			->where("ppp.id = '$rak_alat_tm_8'")
			->group_by('ppd.id')
			->order_by('p.nama_produk','asc')
			->get()->result_array();

			$total_price_tm_8 = 0;
			foreach ($produk_tm_8 as $x){
				$total_price_tm_8 += $x['qty'] * $x['price'];
			}

			$produk_tm_2_8 = $this->db->select('p.nama_produk, ppd.price, ppd.qty, pm.measure_name')
			->from('pmm_penawaran_pembelian ppp')
			->join('pmm_penawaran_pembelian_detail ppd', 'ppp.id = ppd.penawaran_pembelian_id','left')
			->join('produk p', 'ppd.material_id = p.id','left')
			->join('pmm_measures pm', 'ppd.measure = pm.id','left')
			->where("ppp.id = '$rak_alat_tm_2_8'")
			->group_by('ppd.id')
			->order_by('p.nama_produk','asc')
			->get()->result_array();

			$total_price_tm_2_8 = 0;
			foreach ($produk_tm_2_8 as $x){
				$total_price_tm_2_8 += $x['qty'] * $x['price'];
			}

			$produk_tm_3_8 = $this->db->select('p.nama_produk, ppd.price, ppd.qty, pm.measure_name')
			->from('pmm_penawaran_pembelian ppp')
			->join('pmm_penawaran_pembelian_detail ppd', 'ppp.id = ppd.penawaran_pembelian_id','left')
			->join('produk p', 'ppd.material_id = p.id','left')
			->join('pmm_measures pm', 'ppd.measure = pm.id','left')
			->where("ppp.id = '$rak_alat_tm_3_8'")
			->group_by('ppd.id')
			->order_by('p.nama_produk','asc')
			->get()->result_array();

			$total_price_tm_3_8 = 0;
			foreach ($produk_tm_3_8 as $x){
				$total_price_tm_3_8 += $x['qty'] * $x['price'];
			}

			$produk_tm_4_8 = $this->db->select('p.nama_produk, ppd.price, ppd.qty, pm.measure_name')
			->from('pmm_penawaran_pembelian ppp')
			->join('pmm_penawaran_pembelian_detail ppd', 'ppp.id = ppd.penawaran_pembelian_id','left')
			->join('produk p', 'ppd.material_id = p.id','left')
			->join('pmm_measures pm', 'ppd.measure = pm.id','left')
			->where("ppp.id = '$rak_alat_tm_4_8'")
			->group_by('ppd.id')
			->order_by('p.nama_produk','asc')
			->get()->result_array();

			$total_price_tm_4_8 = 0;
			foreach ($produk_tm_4_8 as $x){
				$total_price_tm_4_8 += $x['qty'] * $x['price'];
			}

			$produk_wl_8 = $this->db->select('p.nama_produk, ppd.price, ppd.qty, pm.measure_name')
			->from('pmm_penawaran_pembelian ppp')
			->join('pmm_penawaran_pembelian_detail ppd', 'ppp.id = ppd.penawaran_pembelian_id','left')
			->join('produk p', 'ppd.material_id = p.id','left')
			->join('pmm_measures pm', 'ppd.measure = pm.id','left')
			->where("ppp.id = '$rak_alat_wl_8'")
			->group_by('ppd.id')
			->order_by('p.nama_produk','asc')
			->get()->result_array();

			$total_price_wl_8 = 0;
			foreach ($produk_wl_8 as $x){
				$total_price_wl_8 += $x['qty'] * $x['price'];
			}

			$produk_wl_2_8 = $this->db->select('p.nama_produk, ppd.price, ppd.qty, pm.measure_name')
			->from('pmm_penawaran_pembelian ppp')
			->join('pmm_penawaran_pembelian_detail ppd', 'ppp.id = ppd.penawaran_pembelian_id','left')
			->join('produk p', 'ppd.material_id = p.id','left')
			->join('pmm_measures pm', 'ppd.measure = pm.id','left')
			->where("ppp.id = '$rak_alat_wl_2_8'")
			->group_by('ppd.id')
			->order_by('p.nama_produk','asc')
			->get()->result_array();

			$total_price_wl_2_8 = 0;
			foreach ($produk_wl_2_8 as $x){
				$total_price_wl_2_8 += $x['qty'] * $x['price'];
			}

			$produk_wl_3_8 = $this->db->select('p.nama_produk, ppd.price, ppd.qty, pm.measure_name')
			->from('pmm_penawaran_pembelian ppp')
			->join('pmm_penawaran_pembelian_detail ppd', 'ppp.id = ppd.penawaran_pembelian_id','left')
			->join('produk p', 'ppd.material_id = p.id','left')
			->join('pmm_measures pm', 'ppd.measure = pm.id','left')
			->where("ppp.id = '$rak_alat_wl_3_8'")
			->group_by('ppd.id')
			->order_by('p.nama_produk','asc')
			->get()->result_array();

			$total_price_wl_3_8 = 0;
			foreach ($produk_wl_3_8 as $x){
				$total_price_wl_3_8 += $x['qty'] * $x['price'];
			}

			$produk_tr_8 = $this->db->select('p.nama_produk, ppd.price, ppd.qty, pm.measure_name, ps.nama')
			->from('pmm_penawaran_pembelian ppp')
			->join('pmm_penawaran_pembelian_detail ppd', 'ppp.id = ppd.penawaran_pembelian_id','left')
			->join('produk p', 'ppd.material_id = p.id','left')
			->join('pmm_measures pm', 'ppd.measure = pm.id','left')
			->join('penerima ps', 'ppp.supplier_id = ps.id','left')
			->where("ppp.id = '$rak_alat_tr_8'")
			->group_by('ppd.id')
			->order_by('p.nama_produk','asc')
			->get()->result_array();

			$total_price_tr_8 = 0;
			foreach ($produk_tr_8 as $x){
				$total_price_tr_8 += $x['qty'] * $x['price'];
			}

			$produk_tr_2_8 = $this->db->select('p.nama_produk, ppd.price, ppd.qty, pm.measure_name, ps.nama')
			->from('pmm_penawaran_pembelian ppp')
			->join('pmm_penawaran_pembelian_detail ppd', 'ppp.id = ppd.penawaran_pembelian_id','left')
			->join('produk p', 'ppd.material_id = p.id','left')
			->join('pmm_measures pm', 'ppd.measure = pm.id','left')
			->join('penerima ps', 'ppp.supplier_id = ps.id','left')
			->where("ppp.id = '$rak_alat_tr_2_8'")
			->group_by('ppd.id')
			->order_by('p.nama_produk','asc')
			->get()->result_array();

			$total_price_tr_2_8 = 0;
			foreach ($produk_tr_2_8 as $x){
				$total_price_tr_2_8 += $x['qty'] * $x['price'];
			}

			$produk_tr_3_8 = $this->db->select('p.nama_produk, ppd.price, ppd.qty, pm.measure_name, ps.nama')
			->from('pmm_penawaran_pembelian ppp')
			->join('pmm_penawaran_pembelian_detail ppd', 'ppp.id = ppd.penawaran_pembelian_id','left')
			->join('produk p', 'ppd.material_id = p.id','left')
			->join('pmm_measures pm', 'ppd.measure = pm.id','left')
			->join('penerima ps', 'ppp.supplier_id = ps.id','left')
			->where("ppp.id = '$rak_alat_tr_3_8'")
			->group_by('ppd.id')
			->order_by('p.nama_produk','asc')
			->get()->result_array();

			$total_price_tr_3_8 = 0;
			foreach ($produk_tr_3_8 as $x){
				$total_price_tr_3_8 += $x['qty'] * $x['price'];
			}

			$produk_exc_8 = $this->db->select('p.nama_produk, ppd.price, ppd.qty, pm.measure_name, ps.nama')
			->from('pmm_penawaran_pembelian ppp')
			->join('pmm_penawaran_pembelian_detail ppd', 'ppp.id = ppd.penawaran_pembelian_id','left')
			->join('produk p', 'ppd.material_id = p.id','left')
			->join('pmm_measures pm', 'ppd.measure = pm.id','left')
			->join('penerima ps', 'ppp.supplier_id = ps.id','left')
			->where("ppp.id = '$rak_alat_exc_8'")
			->group_by('ppd.id')
			->order_by('p.nama_produk','asc')
			->get()->result_array();

			$total_price_exc_8 = 0;
			foreach ($produk_exc_8 as $x){
				$total_price_exc_8 += $x['qty'] * $x['price'];
			}

			$produk_dmp_4m3_8 = $this->db->select('p.nama_produk, ppd.price, ppd.qty, pm.measure_name, ps.nama')
			->from('pmm_penawaran_pembelian ppp')
			->join('pmm_penawaran_pembelian_detail ppd', 'ppp.id = ppd.penawaran_pembelian_id','left')
			->join('produk p', 'ppd.material_id = p.id','left')
			->join('pmm_measures pm', 'ppd.measure = pm.id','left')
			->join('penerima ps', 'ppp.supplier_id = ps.id','left')
			->where("ppp.id = '$rak_alat_dmp_4m3_8'")
			->group_by('ppd.id')
			->order_by('p.nama_produk','asc')
			->get()->result_array();

			$total_price_dmp_4m3_8 = 0;
			foreach ($produk_dmp_4m3_8 as $x){
				$total_price_dmp_4m3_8 += $x['qty'] * $x['price'];
			}

			$produk_dmp_10m3_8 = $this->db->select('p.nama_produk, ppd.price, ppd.qty, pm.measure_name, ps.nama')
			->from('pmm_penawaran_pembelian ppp')
			->join('pmm_penawaran_pembelian_detail ppd', 'ppp.id = ppd.penawaran_pembelian_id','left')
			->join('produk p', 'ppd.material_id = p.id','left')
			->join('pmm_measures pm', 'ppd.measure = pm.id','left')
			->join('penerima ps', 'ppp.supplier_id = ps.id','left')
			->where("ppp.id = '$rak_alat_dmp_10m3_8'")
			->group_by('ppd.id')
			->order_by('p.nama_produk','asc')
			->get()->result_array();

			$total_price_dmp_10m3_8 = 0;
			foreach ($produk_dmp_10m3_8 as $x){
				$total_price_dmp_10m3_8 += $x['qty'] * $x['price'];
			}

			$produk_sc_8 = $this->db->select('p.nama_produk, ppd.price, ppd.qty, pm.measure_name, ps.nama')
			->from('pmm_penawaran_pembelian ppp')
			->join('pmm_penawaran_pembelian_detail ppd', 'ppp.id = ppd.penawaran_pembelian_id','left')
			->join('produk p', 'ppd.material_id = p.id','left')
			->join('pmm_measures pm', 'ppd.measure = pm.id','left')
			->join('penerima ps', 'ppp.supplier_id = ps.id','left')
			->where("ppp.id = '$rak_alat_sc_8'")
			->group_by('ppd.id')
			->order_by('p.nama_produk','asc')
			->get()->result_array();

			$total_price_sc_8 = 0;
			foreach ($produk_sc_8 as $x){
				$total_price_sc_8 += $x['qty'] * $x['price'];
			}

			$produk_gns_8 = $this->db->select('p.nama_produk, ppd.price, ppd.qty, pm.measure_name, ps.nama')
			->from('pmm_penawaran_pembelian ppp')
			->join('pmm_penawaran_pembelian_detail ppd', 'ppp.id = ppd.penawaran_pembelian_id','left')
			->join('produk p', 'ppd.material_id = p.id','left')
			->join('pmm_measures pm', 'ppd.measure = pm.id','left')
			->join('penerima ps', 'ppp.supplier_id = ps.id','left')
			->where("ppp.id = '$rak_alat_gns_8'")
			->group_by('ppd.id')
			->order_by('p.nama_produk','asc')
			->get()->result_array();

			$total_price_gns_8 = 0;
			foreach ($produk_gns_8 as $x){
				$total_price_gns_8 += $x['qty'] * $x['price'];
			}

			$produk_wl_sc_8 = $this->db->select('p.nama_produk, ppd.price, ppd.qty, pm.measure_name, ps.nama')
			->from('pmm_penawaran_pembelian ppp')
			->join('pmm_penawaran_pembelian_detail ppd', 'ppp.id = ppd.penawaran_pembelian_id','left')
			->join('produk p', 'ppd.material_id = p.id','left')
			->join('pmm_measures pm', 'ppd.measure = pm.id','left')
			->join('penerima ps', 'ppp.supplier_id = ps.id','left')
			->where("ppp.id = '$rak_alat_wl_sc_8'")
			->group_by('ppd.id')
			->order_by('p.nama_produk','asc')
			->get()->result_array();

			$total_price_wl_sc_8 = 0;
			foreach ($produk_wl_sc_8 as $x){
				$total_price_wl_sc_8 += $x['qty'] * $x['price'];
			}

			$total_volume_solar_8 = $rak_alat_8['vol_bbm_solar'];

			$total_8_biaya_bahan = $nilai_semen_8 + $nilai_pasir_8 + $nilai_batu1020_8 + $nilai_batu2030_8;
			$total_8_biaya_alat = ($total_price_bp_8 + $total_price_bp_2_8 + $total_price_bp_3_8) + ($total_price_tm_8 + $total_price_tm_2_8 + $total_price_tm_3_8 + $total_price_tm_4_8) + ($total_price_wl_8 + $total_price_wl_2_8 + $total_price_wl_3_8) + ($total_price_tr_8 + $total_price_tr_2_8 + $total_price_tr_3_8) + ($total_volume_solar_8 * $rak_alat_8['harga_solar']) + $rak_alat_8['insentif'] + $total_price_exc_8 + $total_price_dmp_4m3_8 + $total_price_dmp_10m3_8 + $total_price_sc_8 + $total_price_gns_8 + $total_price_wl_sc_8;
			$total_8_overhead = $rencana_kerja_8['overhead'];
			$total_8_diskonto =  ($total_8_nilai * 3) /100;
			$total_biaya_8_biaya = $total_8_biaya_bahan + $total_8_biaya_alat + $total_8_overhead + $total_8_diskonto;
			?>

			<?php
			//BULAN 9
			$date_9_awal = date('Y-m-d', strtotime('+1 days', strtotime($date_8_akhir)));
			$date_9_akhir = date('Y-m-d', strtotime('-1 days +1 months', strtotime($date_9_awal)));

			$rencana_kerja_9 = $this->db->select('r.*')
			->from('rak r')
			->where("r.tanggal_rencana_kerja between '$date_9_awal' and '$date_9_akhir'")
			->get()->row_array();

			$volume_9_produk_a = $rencana_kerja_9['vol_produk_a'];
			$volume_9_produk_b = $rencana_kerja_9['vol_produk_b'];
			$volume_9_produk_c = $rencana_kerja_9['vol_produk_c'];
			$volume_9_produk_d = $rencana_kerja_9['vol_produk_d'];
			$volume_9_produk_e = $rencana_kerja_9['vol_produk_e'];

			$total_9_volume = $volume_9_produk_a + $volume_9_produk_b + $volume_9_produk_c + $volume_9_produk_d + $volume_9_produk_e;

			$nilai_jual_125_9 = $volume_9_produk_a * $rencana_kerja_9['price_a'];
			$nilai_jual_225_9 = $volume_9_produk_b * $rencana_kerja_9['price_b'];
			$nilai_jual_250_9 = $volume_9_produk_c * $rencana_kerja_9['price_c'];
			$nilai_jual_250_18_9 = $volume_9_produk_d * $rencana_kerja_9['price_d'];
			$nilai_jual_300_9 = $volume_9_produk_e * $rencana_kerja_9['price_e'];
			$nilai_jual_all_9 = $nilai_jual_125_9 + $nilai_jual_225_9 + $nilai_jual_250_9 + $nilai_jual_250_18_9 + $nilai_jual_300_9;

			$total_9_nilai = $nilai_jual_all_9;

			//VOLUME
			$volume_rencana_kerja_9_produk_a = $rencana_kerja_9['vol_produk_a'];
			$volume_rencana_kerja_9_produk_b = $rencana_kerja_9['vol_produk_b'];
			$volume_rencana_kerja_9_produk_c = $rencana_kerja_9['vol_produk_c'];
			$volume_rencana_kerja_9_produk_d = $rencana_kerja_9['vol_produk_d'];

			//BIAYA

			//BIAYA BAHAN
			$komposisi_125_9 = $this->db->select('(r.vol_produk_a * pk.presentase_a) as komposisi_semen_125, (vol_produk_a * pk.presentase_b) as komposisi_pasir_125, (vol_produk_a * pk.presentase_c) as komposisi_batu1020_125, (vol_produk_a * pk.presentase_d) as komposisi_batu2030_125')
			->from('rak r')
			->join('pmm_agregat pk', 'r.komposisi_125 = pk.id','left')
			->where("r.tanggal_rencana_kerja between '$date_9_awal' and '$date_9_akhir'")
			->get()->result_array();

			$total_volume_semen_125_9 = 0;
			$total_volume_pasir_125_9 = 0;
			$total_volume_batu1020_125_9 = 0;
			$total_volume_batu2030_125_9 = 0;

			foreach ($komposisi_125_9 as $x){
				$total_volume_semen_125_9 = $x['komposisi_semen_125'];
				$total_volume_pasir_125_9 = $x['komposisi_pasir_125'];
				$total_volume_batu1020_125_9 = $x['komposisi_batu1020_125'];
				$total_volume_batu2030_125_9 = $x['komposisi_batu2030_125'];
			}

			$komposisi_225_9 = $this->db->select('(r.vol_produk_b * pk.presentase_a) as komposisi_semen_225, (vol_produk_b * pk.presentase_b) as komposisi_pasir_225, (vol_produk_b * pk.presentase_c) as komposisi_batu1020_225, (vol_produk_b * pk.presentase_d) as komposisi_batu2030_225')
			->from('rak r')
			->join('pmm_agregat pk', 'r.komposisi_225 = pk.id','left')
			->where("r.tanggal_rencana_kerja between '$date_9_awal' and '$date_9_akhir'")
			->get()->result_array();

			$total_volume_semen_225_9 = 0;
			$total_volume_pasir_225_9 = 0;
			$total_volume_batu1020_225_9 = 0;
			$total_volume_batu2030_225_9 = 0;

			foreach ($komposisi_225_9 as $x){
				$total_volume_semen_225_9 = $x['komposisi_semen_225'];
				$total_volume_pasir_225_9 = $x['komposisi_pasir_225'];
				$total_volume_batu1020_225_9 = $x['komposisi_batu1020_225'];
				$total_volume_batu2030_225_9 = $x['komposisi_batu2030_225'];
			}

			$komposisi_250_9 = $this->db->select('(r.vol_produk_c * pk.presentase_a) as komposisi_semen_250, (vol_produk_c * pk.presentase_b) as komposisi_pasir_250, (vol_produk_c * pk.presentase_c) as komposisi_batu1020_250, (vol_produk_c * pk.presentase_d) as komposisi_batu2030_250')
			->from('rak r')
			->join('pmm_agregat pk', 'r.komposisi_250 = pk.id','left')
			->where("r.tanggal_rencana_kerja between '$date_9_awal' and '$date_9_akhir'")
			->get()->result_array();

			$total_volume_semen_250_9 = 0;
			$total_volume_pasir_250_9 = 0;
			$total_volume_batu1020_250_9 = 0;
			$total_volume_batu2030_250_9 = 0;

			foreach ($komposisi_250_9 as $x){
				$total_volume_semen_250_9 = $x['komposisi_semen_250'];
				$total_volume_pasir_250_9 = $x['komposisi_pasir_250'];
				$total_volume_batu1020_250_9 = $x['komposisi_batu1020_250'];
				$total_volume_batu2030_250_9 = $x['komposisi_batu2030_250'];
			}

			$komposisi_250_2_9 = $this->db->select('(r.vol_produk_d * pk.presentase_a) as komposisi_semen_250_2, (vol_produk_d * pk.presentase_b) as komposisi_pasir_250_2, (vol_produk_d * pk.presentase_c) as komposisi_batu1020_250_2, (vol_produk_d * pk.presentase_d) as komposisi_batu2030_250_2')
			->from('rak r')
			->join('pmm_agregat pk', 'r.komposisi_250_2 = pk.id','left')
			->where("r.tanggal_rencana_kerja between '$date_9_awal' and '$date_9_akhir'")
			->get()->result_array();

			$total_volume_semen_250_2_9 = 0;
			$total_volume_pasir_250_2_9 = 0;
			$total_volume_batu1020_250_2_9 = 0;
			$total_volume_batu2030_250_2_9 = 0;

			foreach ($komposisi_250_2_9 as $x){
				$total_volume_semen_250_2_9 = $x['komposisi_semen_250_2'];
				$total_volume_pasir_250_2_9 = $x['komposisi_pasir_250_2'];
				$total_volume_batu1020_250_2_9 = $x['komposisi_batu1020_250_2'];
				$total_volume_batu2030_250_2_9 = $x['komposisi_batu2030_250_2'];
			}

			$komposisi_300_9 = $this->db->select('(r.vol_produk_e * pk.presentase_a) as komposisi_semen_300_9, (vol_produk_e * pk.presentase_b) as komposisi_pasir_300_9, (vol_produk_e * pk.presentase_c) as komposisi_batu1020_300_9, (vol_produk_e * pk.presentase_d) as komposisi_batu2030_300_9')
			->from('rak r')
			->join('pmm_agregat pk', 'r.komposisi_300 = pk.id','left')
			->where("r.tanggal_rencana_kerja between '$date_9_awal' and '$date_9_akhir'")
			->get()->result_array();

			$total_volume_semen_300_9 = 0;
			$total_volume_pasir_300_9 = 0;
			$total_volume_batu1020_300_9 = 0;
			$total_volume_batu2030_300_9 = 0;

			foreach ($komposisi_300_9 as $x){
				$total_volume_semen_300_9 = $x['komposisi_semen_300_9'];
				$total_volume_pasir_300_9 = $x['komposisi_pasir_300_9'];
				$total_volume_batu1020_300_9 = $x['komposisi_batu1020_300_9'];
				$total_volume_batu2030_300_9 = $x['komposisi_batu2030_300_9'];
			}

			$total_volume_semen_9 = $total_volume_semen_125_9 + $total_volume_semen_225_9 + $total_volume_semen_250_9 + $total_volume_semen_250_2_9 + $total_volume_semen_300_9;
			$total_volume_pasir_9 = $total_volume_pasir_125_9 + $total_volume_pasir_225_9 + $total_volume_pasir_250_9 + $total_volume_pasir_250_2_9 + $total_volume_pasir_300_9;
			$total_volume_batu1020_9 = $total_volume_batu1020_125_9 + $total_volume_batu1020_225_9 + $total_volume_batu1020_250_9 + $total_volume_batu1020_250_2_9 + $total_volume_batu1020_300_9;
			$total_volume_batu2030_9 = $total_volume_batu2030_125_9 + $total_volume_batu2030_225_9 + $total_volume_batu2030_250_9 + $total_volume_batu2030_250_2_9 + $total_volume_batu2030_300_9;

			$nilai_semen_9 = $total_volume_semen_9 * $rencana_kerja_9['harga_semen'];
			$nilai_pasir_9 = $total_volume_pasir_9 * $rencana_kerja_9['harga_pasir'];
			$nilai_batu1020_9 = $total_volume_batu1020_9 * $rencana_kerja_9['harga_batu1020'];
			$nilai_batu2030_9 = $total_volume_batu2030_9 * $rencana_kerja_9['harga_batu2030'];

			//BIAYA ALAT
			$rak_alat_9 = $this->db->select('r.*, (r.vol_produk_a + r.vol_produk_b + r.vol_produk_c + r.vol_produk_d) as total_produksi')
			->from('rak r')
			->where("r.tanggal_rencana_kerja between '$date_9_awal' and '$date_9_akhir'")
			->get()->row_array();

			$rak_alat_bp_9 = $rak_alat_9['penawaran_id_bp'];
			$rak_alat_bp_2_9 = $rak_alat_9['penawaran_id_bp_2'];
			$rak_alat_bp_3_9 = $rak_alat_9['penawaran_id_bp_3'];

			$rak_alat_tm_9 = $rak_alat_9['penawaran_id_tm'];
			$rak_alat_tm_2_9 = $rak_alat_9['penawaran_id_tm_2'];
			$rak_alat_tm_3_9 = $rak_alat_9['penawaran_id_tm_3'];
			$rak_alat_tm_4_9 = $rak_alat_9['penawaran_id_tm_4'];

			$rak_alat_wl_9 = $rak_alat_9['penawaran_id_wl'];
			$rak_alat_wl_2_9 = $rak_alat_9['penawaran_id_wl_2'];
			$rak_alat_wl_3_9 = $rakrak_alat_9_alat['penawaran_id_wl_3'];

			$rak_alat_tr_9 = $rak_alat_9['penawaran_id_tr'];
			$rak_alat_tr_2_9 = $rak_alat_9['penawaran_id_tr_2'];
			$rak_alat_tr_3_9 = $rak_alat_9['penawaran_id_tr_3'];

			$rak_alat_exc_9 = $rak_alat_9['penawaran_id_exc'];
			$rak_alat_dmp_4m3_9 = $rak_alat_9['penawaran_id_dmp_4m3'];
			$rak_alat_dmp_10m3_9 = $rak_alat_9['penawaran_id_dmp_10m3'];
			$rak_alat_sc_9 = $rak_alat_9['penawaran_id_sc'];
			$rak_alat_gns_9 = $rak_alat_9['penawaran_id_gns'];
			$rak_alat_wl_sc_9 = $rak_alat_9['penawaran_id_wl_sc'];

			$produk_bp_9 = $this->db->select('p.nama_produk, ppd.price, ppd.qty, pm.measure_name, ps.nama, sum(vol_produk_a + vol_produk_b + vol_produk_c + vol_produk_d) as total_vol_produksi')
			->from('pmm_penawaran_pembelian ppp')
			->join('pmm_penawaran_pembelian_detail ppd', 'ppp.id = ppd.penawaran_pembelian_id','left')
			->join('produk p', 'ppd.material_id = p.id','left')
			->join('pmm_measures pm', 'ppd.measure = pm.id','left')
			->join('penerima ps', 'ppp.supplier_id = ps.id','left')
			->join('rak r', 'ppp.id = r.penawaran_id_bp','left')
			->where("r.tanggal_rencana_kerja between '$date_9_awal' and '$date_9_akhir'")
			->where("ppp.id = '$rak_alat_bp_9'")
			->group_by('ppd.id')
			->order_by('p.nama_produk','asc')
			->get()->result_array();

			$total_price_bp_9 = 0;
			foreach ($produk_bp_9 as $x){
				$total_price_bp_9 += $x['total_vol_produksi'] * $x['price'];
			}

			$produk_bp_2_9 = $this->db->select('p.nama_produk, ppd.price, ppd.qty, pm.measure_name, ps.nama, sum(vol_produk_a + vol_produk_b + vol_produk_c + vol_produk_d) as total_vol_produksi')
			->from('pmm_penawaran_pembelian ppp')
			->join('pmm_penawaran_pembelian_detail ppd', 'ppp.id = ppd.penawaran_pembelian_id','left')
			->join('produk p', 'ppd.material_id = p.id','left')
			->join('pmm_measures pm', 'ppd.measure = pm.id','left')
			->join('penerima ps', 'ppp.supplier_id = ps.id','left')
			->join('rak r', 'ppp.id = r.penawaran_id_bp','left')
			->where("r.tanggal_rencana_kerja between '$date_9_awal' and '$date_9_akhir'")
			->where("ppp.id = '$rak_alat_bp_2_9'")
			->group_by('ppd.id')
			->order_by('p.nama_produk','asc')
			->get()->result_array();

			$total_price_bp_2_9 = 0;
			foreach ($produk_bp_2_9 as $x){
				$total_price_bp_2_9 += $x['total_vol_produksi'] * $x['price'];
			}

			$produk_bp_3_9 = $this->db->select('p.nama_produk, ppd.price, ppd.qty, pm.measure_name, ps.nama, sum(vol_produk_a + vol_produk_b + vol_produk_c + vol_produk_d) as total_vol_produksi')
			->from('pmm_penawaran_pembelian ppp')
			->join('pmm_penawaran_pembelian_detail ppd', 'ppp.id = ppd.penawaran_pembelian_id','left')
			->join('produk p', 'ppd.material_id = p.id','left')
			->join('pmm_measures pm', 'ppd.measure = pm.id','left')
			->join('penerima ps', 'ppp.supplier_id = ps.id','left')
			->join('rak r', 'ppp.id = r.penawaran_id_bp','left')
			->where("r.tanggal_rencana_kerja between '$date_9_awal' and '$date_9_akhir'")
			->where("ppp.id = '$rak_alat_bp_3_9'")
			->group_by('ppd.id')
			->order_by('p.nama_produk','asc')
			->get()->result_array();

			$total_price_bp_3_9 = 0;
			foreach ($produk_bp_3_9 as $x){
				$total_price_bp_3_9 += $x['total_vol_produksi'] * $x['price'];
			}

			$produk_tm_9 = $this->db->select('p.nama_produk, ppd.price, ppd.qty, pm.measure_name')
			->from('pmm_penawaran_pembelian ppp')
			->join('pmm_penawaran_pembelian_detail ppd', 'ppp.id = ppd.penawaran_pembelian_id','left')
			->join('produk p', 'ppd.material_id = p.id','left')
			->join('pmm_measures pm', 'ppd.measure = pm.id','left')
			->where("ppp.id = '$rak_alat_tm_9'")
			->group_by('ppd.id')
			->order_by('p.nama_produk','asc')
			->get()->result_array();

			$total_price_tm_9 = 0;
			foreach ($produk_tm_9 as $x){
				$total_price_tm_9 += $x['qty'] * $x['price'];
			}

			$produk_tm_2_9 = $this->db->select('p.nama_produk, ppd.price, ppd.qty, pm.measure_name')
			->from('pmm_penawaran_pembelian ppp')
			->join('pmm_penawaran_pembelian_detail ppd', 'ppp.id = ppd.penawaran_pembelian_id','left')
			->join('produk p', 'ppd.material_id = p.id','left')
			->join('pmm_measures pm', 'ppd.measure = pm.id','left')
			->where("ppp.id = '$rak_alat_tm_2_9'")
			->group_by('ppd.id')
			->order_by('p.nama_produk','asc')
			->get()->result_array();

			$total_price_tm_2_9 = 0;
			foreach ($produk_tm_2_9 as $x){
				$total_price_tm_2_9 += $x['qty'] * $x['price'];
			}

			$produk_tm_3_9 = $this->db->select('p.nama_produk, ppd.price, ppd.qty, pm.measure_name')
			->from('pmm_penawaran_pembelian ppp')
			->join('pmm_penawaran_pembelian_detail ppd', 'ppp.id = ppd.penawaran_pembelian_id','left')
			->join('produk p', 'ppd.material_id = p.id','left')
			->join('pmm_measures pm', 'ppd.measure = pm.id','left')
			->where("ppp.id = '$rak_alat_tm_3_9'")
			->group_by('ppd.id')
			->order_by('p.nama_produk','asc')
			->get()->result_array();

			$total_price_tm_3_9 = 0;
			foreach ($produk_tm_3_9 as $x){
				$total_price_tm_3_9 += $x['qty'] * $x['price'];
			}

			$produk_tm_4_9 = $this->db->select('p.nama_produk, ppd.price, ppd.qty, pm.measure_name')
			->from('pmm_penawaran_pembelian ppp')
			->join('pmm_penawaran_pembelian_detail ppd', 'ppp.id = ppd.penawaran_pembelian_id','left')
			->join('produk p', 'ppd.material_id = p.id','left')
			->join('pmm_measures pm', 'ppd.measure = pm.id','left')
			->where("ppp.id = '$rak_alat_tm_4_9'")
			->group_by('ppd.id')
			->order_by('p.nama_produk','asc')
			->get()->result_array();

			$total_price_tm_4_9 = 0;
			foreach ($produk_tm_4_9 as $x){
				$total_price_tm_4_9 += $x['qty'] * $x['price'];
			}

			$produk_wl_9 = $this->db->select('p.nama_produk, ppd.price, ppd.qty, pm.measure_name')
			->from('pmm_penawaran_pembelian ppp')
			->join('pmm_penawaran_pembelian_detail ppd', 'ppp.id = ppd.penawaran_pembelian_id','left')
			->join('produk p', 'ppd.material_id = p.id','left')
			->join('pmm_measures pm', 'ppd.measure = pm.id','left')
			->where("ppp.id = '$rak_alat_wl_9'")
			->group_by('ppd.id')
			->order_by('p.nama_produk','asc')
			->get()->result_array();

			$total_price_wl_9 = 0;
			foreach ($produk_wl_9 as $x){
				$total_price_wl_9 += $x['qty'] * $x['price'];
			}

			$produk_wl_2_9 = $this->db->select('p.nama_produk, ppd.price, ppd.qty, pm.measure_name')
			->from('pmm_penawaran_pembelian ppp')
			->join('pmm_penawaran_pembelian_detail ppd', 'ppp.id = ppd.penawaran_pembelian_id','left')
			->join('produk p', 'ppd.material_id = p.id','left')
			->join('pmm_measures pm', 'ppd.measure = pm.id','left')
			->where("ppp.id = '$rak_alat_wl_2_9'")
			->group_by('ppd.id')
			->order_by('p.nama_produk','asc')
			->get()->result_array();

			$total_price_wl_2_9 = 0;
			foreach ($produk_wl_2_9 as $x){
				$total_price_wl_2_9 += $x['qty'] * $x['price'];
			}

			$produk_wl_3_9 = $this->db->select('p.nama_produk, ppd.price, ppd.qty, pm.measure_name')
			->from('pmm_penawaran_pembelian ppp')
			->join('pmm_penawaran_pembelian_detail ppd', 'ppp.id = ppd.penawaran_pembelian_id','left')
			->join('produk p', 'ppd.material_id = p.id','left')
			->join('pmm_measures pm', 'ppd.measure = pm.id','left')
			->where("ppp.id = '$rak_alat_wl_3_9'")
			->group_by('ppd.id')
			->order_by('p.nama_produk','asc')
			->get()->result_array();

			$total_price_wl_3_9 = 0;
			foreach ($produk_wl_3_9 as $x){
				$total_price_wl_3_9 += $x['qty'] * $x['price'];
			}

			$produk_tr_9 = $this->db->select('p.nama_produk, ppd.price, ppd.qty, pm.measure_name, ps.nama')
			->from('pmm_penawaran_pembelian ppp')
			->join('pmm_penawaran_pembelian_detail ppd', 'ppp.id = ppd.penawaran_pembelian_id','left')
			->join('produk p', 'ppd.material_id = p.id','left')
			->join('pmm_measures pm', 'ppd.measure = pm.id','left')
			->join('penerima ps', 'ppp.supplier_id = ps.id','left')
			->where("ppp.id = '$rak_alat_tr_9'")
			->group_by('ppd.id')
			->order_by('p.nama_produk','asc')
			->get()->result_array();

			$total_price_tr_9 = 0;
			foreach ($produk_tr_9 as $x){
				$total_price_tr_9 += $x['qty'] * $x['price'];
			}

			$produk_tr_2_9 = $this->db->select('p.nama_produk, ppd.price, ppd.qty, pm.measure_name, ps.nama')
			->from('pmm_penawaran_pembelian ppp')
			->join('pmm_penawaran_pembelian_detail ppd', 'ppp.id = ppd.penawaran_pembelian_id','left')
			->join('produk p', 'ppd.material_id = p.id','left')
			->join('pmm_measures pm', 'ppd.measure = pm.id','left')
			->join('penerima ps', 'ppp.supplier_id = ps.id','left')
			->where("ppp.id = '$rak_alat_tr_2_9'")
			->group_by('ppd.id')
			->order_by('p.nama_produk','asc')
			->get()->result_array();

			$total_price_tr_2_9 = 0;
			foreach ($produk_tr_2_9 as $x){
				$total_price_tr_2_9 += $x['qty'] * $x['price'];
			}

			$produk_tr_3_9 = $this->db->select('p.nama_produk, ppd.price, ppd.qty, pm.measure_name, ps.nama')
			->from('pmm_penawaran_pembelian ppp')
			->join('pmm_penawaran_pembelian_detail ppd', 'ppp.id = ppd.penawaran_pembelian_id','left')
			->join('produk p', 'ppd.material_id = p.id','left')
			->join('pmm_measures pm', 'ppd.measure = pm.id','left')
			->join('penerima ps', 'ppp.supplier_id = ps.id','left')
			->where("ppp.id = '$rak_alat_tr_3_9'")
			->group_by('ppd.id')
			->order_by('p.nama_produk','asc')
			->get()->result_array();

			$total_price_tr_3_9 = 0;
			foreach ($produk_tr_3_9 as $x){
				$total_price_tr_3_9 += $x['qty'] * $x['price'];
			}

			$produk_exc_9 = $this->db->select('p.nama_produk, ppd.price, ppd.qty, pm.measure_name, ps.nama')
			->from('pmm_penawaran_pembelian ppp')
			->join('pmm_penawaran_pembelian_detail ppd', 'ppp.id = ppd.penawaran_pembelian_id','left')
			->join('produk p', 'ppd.material_id = p.id','left')
			->join('pmm_measures pm', 'ppd.measure = pm.id','left')
			->join('penerima ps', 'ppp.supplier_id = ps.id','left')
			->where("ppp.id = '$rak_alat_exc_9'")
			->group_by('ppd.id')
			->order_by('p.nama_produk','asc')
			->get()->result_array();

			$total_price_exc_9 = 0;
			foreach ($produk_exc_9 as $x){
				$total_price_exc_9 += $x['qty'] * $x['price'];
			}

			$produk_dmp_4m3_9 = $this->db->select('p.nama_produk, ppd.price, ppd.qty, pm.measure_name, ps.nama')
			->from('pmm_penawaran_pembelian ppp')
			->join('pmm_penawaran_pembelian_detail ppd', 'ppp.id = ppd.penawaran_pembelian_id','left')
			->join('produk p', 'ppd.material_id = p.id','left')
			->join('pmm_measures pm', 'ppd.measure = pm.id','left')
			->join('penerima ps', 'ppp.supplier_id = ps.id','left')
			->where("ppp.id = '$rak_alat_dmp_4m3_9'")
			->group_by('ppd.id')
			->order_by('p.nama_produk','asc')
			->get()->result_array();

			$total_price_dmp_4m3_9 = 0;
			foreach ($produk_dmp_4m3_9 as $x){
				$total_price_dmp_4m3_9 += $x['qty'] * $x['price'];
			}

			$produk_dmp_10m3_9 = $this->db->select('p.nama_produk, ppd.price, ppd.qty, pm.measure_name, ps.nama')
			->from('pmm_penawaran_pembelian ppp')
			->join('pmm_penawaran_pembelian_detail ppd', 'ppp.id = ppd.penawaran_pembelian_id','left')
			->join('produk p', 'ppd.material_id = p.id','left')
			->join('pmm_measures pm', 'ppd.measure = pm.id','left')
			->join('penerima ps', 'ppp.supplier_id = ps.id','left')
			->where("ppp.id = '$rak_alat_dmp_10m3_9'")
			->group_by('ppd.id')
			->order_by('p.nama_produk','asc')
			->get()->result_array();

			$total_price_dmp_10m3_9 = 0;
			foreach ($produk_dmp_10m3_9 as $x){
				$total_price_dmp_10m3_9 += $x['qty'] * $x['price'];
			}

			$produk_sc_9 = $this->db->select('p.nama_produk, ppd.price, ppd.qty, pm.measure_name, ps.nama')
			->from('pmm_penawaran_pembelian ppp')
			->join('pmm_penawaran_pembelian_detail ppd', 'ppp.id = ppd.penawaran_pembelian_id','left')
			->join('produk p', 'ppd.material_id = p.id','left')
			->join('pmm_measures pm', 'ppd.measure = pm.id','left')
			->join('penerima ps', 'ppp.supplier_id = ps.id','left')
			->where("ppp.id = '$rak_alat_sc_9'")
			->group_by('ppd.id')
			->order_by('p.nama_produk','asc')
			->get()->result_array();

			$total_price_sc_9 = 0;
			foreach ($produk_sc_9 as $x){
				$total_price_sc_9 += $x['qty'] * $x['price'];
			}

			$produk_gns_9 = $this->db->select('p.nama_produk, ppd.price, ppd.qty, pm.measure_name, ps.nama')
			->from('pmm_penawaran_pembelian ppp')
			->join('pmm_penawaran_pembelian_detail ppd', 'ppp.id = ppd.penawaran_pembelian_id','left')
			->join('produk p', 'ppd.material_id = p.id','left')
			->join('pmm_measures pm', 'ppd.measure = pm.id','left')
			->join('penerima ps', 'ppp.supplier_id = ps.id','left')
			->where("ppp.id = '$rak_alat_gns_9'")
			->group_by('ppd.id')
			->order_by('p.nama_produk','asc')
			->get()->result_array();

			$total_price_gns_9 = 0;
			foreach ($produk_gns_9 as $x){
				$total_price_gns_9 += $x['qty'] * $x['price'];
			}

			$produk_wl_sc_9 = $this->db->select('p.nama_produk, ppd.price, ppd.qty, pm.measure_name, ps.nama')
			->from('pmm_penawaran_pembelian ppp')
			->join('pmm_penawaran_pembelian_detail ppd', 'ppp.id = ppd.penawaran_pembelian_id','left')
			->join('produk p', 'ppd.material_id = p.id','left')
			->join('pmm_measures pm', 'ppd.measure = pm.id','left')
			->join('penerima ps', 'ppp.supplier_id = ps.id','left')
			->where("ppp.id = '$rak_alat_wl_sc_9'")
			->group_by('ppd.id')
			->order_by('p.nama_produk','asc')
			->get()->result_array();

			$total_price_wl_sc_9 = 0;
			foreach ($produk_wl_sc_9 as $x){
				$total_price_wl_sc_9 += $x['qty'] * $x['price'];
			}

			$total_volume_solar_9 = $rak_alat_9['vol_bbm_solar'];

			$total_9_biaya_bahan = $nilai_semen_9 + $nilai_pasir_9 + $nilai_batu1020_9 + $nilai_batu2030_9;
			$total_9_biaya_alat = ($total_price_bp_9 + $total_price_bp_2_9 + $total_price_bp_3_9) + ($total_price_tm_9 + $total_price_tm_2_9 + $total_price_tm_3_9 + $total_price_tm_4_9) + ($total_price_wl_9 + $total_price_wl_2_9 + $total_price_wl_3_9) + ($total_price_tr_9 + $total_price_tr_2_9 + $total_price_tr_3_9) + ($total_volume_solar_9 * $rak_alat_9['harga_solar']) + $rak_alat_9['insentif'] + $total_price_exc_9 + $total_price_dmp_4m3_9 + $total_price_dmp_10m3_9 + $total_price_sc_9 + $total_price_gns_9 + $total_price_wl_sc_9;
			$total_9_overhead = $rencana_kerja_9['overhead'];
			$total_9_diskonto =  ($total_9_nilai * 3) /100;
			$total_biaya_9_biaya = $total_9_biaya_bahan + $total_9_biaya_alat + $total_9_overhead + $total_9_diskonto;
			?>

			<?php
			//TOTAL
			$total_all_produk_a = $volume_akumulasi_produk_a + $volume_1_produk_a + $volume_2_produk_a + $volume_3_produk_a + $volume_4_produk_a + $volume_5_produk_a + $volume_6_produk_a + $volume_7_produk_a + $volume_8_produk_a + $volume_9_produk_a;
			$total_all_produk_b = $volume_akumulasi_produk_b + $volume_1_produk_b + $volume_2_produk_b + $volume_3_produk_b + $volume_4_produk_b + $volume_5_produk_b + $volume_6_produk_b + $volume_7_produk_b + $volume_8_produk_b + $volume_9_produk_b;
			$total_all_produk_c = $volume_akumulasi_produk_c + $volume_1_produk_c + $volume_2_produk_c + $volume_3_produk_c + $volume_4_produk_c + $volume_5_produk_c + $volume_6_produk_c + $volume_7_produk_c + $volume_8_produk_c + $volume_9_produk_c;
			$total_all_produk_d = $volume_akumulasi_produk_d + $volume_1_produk_d + $volume_2_produk_d + $volume_3_produk_d + $volume_4_produk_d + $volume_5_produk_d + $volume_6_produk_d + $volume_7_produk_d + $volume_8_produk_d + $volume_9_produk_d;
			$total_all_produk_e = $volume_akumulasi_produk_e + $volume_1_produk_e + $volume_2_produk_e + $volume_3_produk_e + $volume_4_produk_e + $volume_5_produk_e + $volume_6_produk_e + $volume_7_produk_e + $volume_8_produk_e + $volume_9_produk_e;

			$total_all_volume = $total_akumulasi_volume + $total_1_volume + $total_2_volume + $total_3_volume + $total_4_volume + $total_5_volume + $total_6_volume + $total_7_volume + $total_8_volume + $total_9_volume;
			$total_all_nilai = $total_akumulasi_nilai  + $total_1_nilai + $total_2_nilai + $total_3_nilai + $total_4_nilai + $total_5_nilai + $total_6_nilai + $total_7_nilai + $total_8_nilai + $total_9_nilai;

			$total_all_biaya_bahan = $total_bahan_akumulasi + $total_1_biaya_bahan + $total_2_biaya_bahan + $total_3_biaya_bahan + $total_4_biaya_bahan + $total_5_biaya_bahan + $total_6_biaya_bahan + $total_7_biaya_bahan + $total_8_biaya_bahan + $total_9_biaya_bahan;
			$total_all_biaya_alat = $total_alat_akumulasi + $total_1_biaya_alat + $total_2_biaya_alat + $total_3_biaya_alat + $total_4_biaya_alat + $total_5_biaya_alat + $total_6_biaya_alat + $total_7_biaya_alat + $total_8_biaya_alat + $total_9_biaya_alat;
			$total_all_overhead = $total_overhead_akumulasi + $total_1_overhead + $total_2_overhead + $total_3_overhead + $total_4_overhead + $total_5_overhead + $total_6_overhead + $total_7_overhead + $total_8_overhead + $total_9_overhead;
			$total_all_diskonto = $total_diskonto_akumulasi + $total_1_diskonto + $total_2_diskonto + $total_3_diskonto + $total_4_diskonto + $total_5_diskonto + $total_6_diskonto + $total_7_diskonto + $total_8_diskonto + $total_9_diskonto;
			
			$total_biaya_all_biaya = $total_all_biaya_bahan + $total_all_biaya_alat + $total_all_overhead + $total_all_diskonto;

			$total_laba_rap_2022 = $total_rap_nilai_2022 - $total_biaya_rap_2022_biaya;
			$total_laba_saat_ini = $total_akumulasi_nilai - $total_biaya_akumulasi;
			$total_laba_1 = $total_1_nilai - $total_biaya_1_biaya;
			$total_laba_2 = $total_2_nilai - $total_biaya_2_biaya;
			$total_laba_3 = $total_3_nilai - $total_biaya_3_biaya;
			$total_laba_4 = $total_4_nilai - $total_biaya_4_biaya;
			$total_laba_5 = $total_5_nilai - $total_biaya_5_biaya;
			$total_laba_6 = $total_6_nilai - $total_biaya_6_biaya;
			$total_laba_7 = $total_7_nilai - $total_biaya_7_biaya;
			$total_laba_8 = $total_8_nilai - $total_biaya_8_biaya;
			$total_laba_9 = $total_9_nilai - $total_biaya_9_biaya;
			$total_laba_all = $total_all_nilai - $total_biaya_all_biaya;
			?>
			
			<tr class="table-active4-rak">
				<th width="5%" class="text-center" rowspan="3" style="vertical-align:middle">NO.</th>
				<th class="text-center" rowspan="3" style="vertical-align:middle">URAIAN</th>
				<th class="text-center" rowspan="3" style="vertical-align:middle">SATUAN</th>
				<th class="text-center" rowspan="3" style="vertical-align:middle">ADEDENDUM RAP</th>
				<th class="text-center" rowspan="3" style="vertical-align:middle;text-transform:uppercase;">REALISASI SD. <br /><?php echo $last_opname = date('F Y', strtotime('0 days', strtotime($last_opname)));?></th>
				<th class="text-center" colspan="7">PROGNOSA</th>
				<th class="text-center" rowspan="3" style="vertical-align:middle">TOTAL</th>
	        </tr>
			<tr class="table-active4-rak">
				<th class="text-center" style="text-transform:uppercase;"><?php echo $date_1_awal = date("F");?></th>
				<th class="text-center" style="text-transform:uppercase;"><?php echo $date_2_awal = date('F', strtotime('+1 days', strtotime($date_1_akhir)));?></th>
				<th class="text-center" style="text-transform:uppercase;"><?php echo $date_3_awal = date('F', strtotime('+1 days', strtotime($date_2_akhir)));?></th>
				<th class="text-center" style="text-transform:uppercase;"><?php echo $date_4_awal = date('F', strtotime('+1 days', strtotime($date_3_akhir)));?></th>
				<th class="text-center" style="text-transform:uppercase;"><?php echo $date_5_awal = date('F', strtotime('+1 days', strtotime($date_4_akhir)));?></th>
				<th class="text-center" style="text-transform:uppercase;"><?php echo $date_6_awal = date('F', strtotime('+1 days', strtotime($date_5_akhir)));?></th>
				<th class="text-center" style="text-transform:uppercase;"><?php echo $date_7_awal = date('F', strtotime('+1 days', strtotime($date_6_akhir)));?> - <?php echo $date_8_awal = date('F', strtotime('+1 days', strtotime($date_7_akhir)));?></th>
	        </tr>
			<tr class="table-active4-rak">
				<th class="text-center"><?php echo $date_1_awal = date('Y');?></th>
				<th class="text-center"><?php echo $date_2_awal = date('Y', strtotime('+1 days', strtotime($date_1_akhir)));?></th>
				<th class="text-center"><?php echo $date_3_awal = date('Y', strtotime('+1 days', strtotime($date_2_akhir)));?></th>
				<th class="text-center"><?php echo $date_4_awal = date('Y', strtotime('+1 days', strtotime($date_3_akhir)));?></th>
				<th class="text-center"><?php echo $date_5_awal = date('Y', strtotime('+1 days', strtotime($date_4_akhir)));?></th>
				<th class="text-center"><?php echo $date_6_awal = date('Y', strtotime('+1 days', strtotime($date_5_akhir)));?></th>
				<th class="text-center"><?php echo $date_7_awal = date('Y', strtotime('+1 days', strtotime($date_6_akhir)));?></th>
	        </tr>
			<tr class="table-active2-rak">
				<th class="text-left" colspan="13">RENCANA PRODUKSI & PENDAPATAN USAHA</th>
			</tr>
			<tr class="table-active3-rak">
				<th class="text-center">1.</th>
				<th class="text-left">Beton K 125 (10±2)</th>
				<th class="text-center">M3</th>
				<th class="text-right"><?php echo number_format($volume_rap_2022_produk_a,2,',','.');?></th>
				<th class="text-right"><?php echo number_format($volume_akumulasi_produk_a,2,',','.');?></th>
				<th class="text-right"><?php echo number_format($volume_1_produk_a,2,',','.');?></th>
				<th class="text-right"><?php echo number_format($volume_2_produk_a,2,',','.');?></th>
				<th class="text-right"><?php echo number_format($volume_3_produk_a,2,',','.');?></th>
				<th class="text-right"><?php echo number_format($volume_4_produk_a,2,',','.');?></th>
				<th class="text-right"><?php echo number_format($volume_5_produk_a,2,',','.');?></th>
				<th class="text-right"><?php echo number_format($volume_6_produk_a,2,',','.');?></th>
				<th class="text-right"><?php echo number_format($volume_7_produk_a + $volume_8_produk_a + $volume_9_produk_a,2,',','.');?></th>
				<th class="text-right"><?php echo number_format($total_all_produk_a,2,',','.');?></th>
			</tr>
			<tr class="table-active3-rak">
				<th class="text-center">2.</th>
				<th class="text-left">Beton K 225 (10±2)</th>
				<th class="text-center">M3</th>
				<th class="text-right"><?php echo number_format($volume_rap_2022_produk_b,2,',','.');?></th>
				<th class="text-right"><?php echo number_format($volume_akumulasi_produk_b,2,',','.');?></th>
				<th class="text-right"><?php echo number_format($volume_1_produk_b,2,',','.');?></th>
				<th class="text-right"><?php echo number_format($volume_2_produk_b,2,',','.');?></th>
				<th class="text-right"><?php echo number_format($volume_3_produk_b,2,',','.');?></th>
				<th class="text-right"><?php echo number_format($volume_4_produk_b,2,',','.');?></th>
				<th class="text-right"><?php echo number_format($volume_5_produk_b,2,',','.');?></th>
				<th class="text-right"><?php echo number_format($volume_6_produk_b,2,',','.');?></th>
				<th class="text-right"><?php echo number_format($volume_7_produk_b + $volume_8_produk_b + $volume_9_produk_b,2,',','.');?></th>
				<th class="text-right"><?php echo number_format($total_all_produk_b,2,',','.');?></th>
			</tr>
			<tr class="table-active3-rak">
				<th class="text-center">3.</th>
				<th class="text-left">Beton K 250 (10±2)</th>
				<th class="text-center">M3</th>
				<th class="text-right"><?php echo number_format($volume_rap_2022_produk_c,2,',','.');?></th>
				<th class="text-right"><?php echo number_format($volume_akumulasi_produk_c,2,',','.');?></th>
				<th class="text-right"><?php echo number_format($volume_1_produk_c,2,',','.');?></th>
				<th class="text-right"><?php echo number_format($volume_2_produk_c,2,',','.');?></th>
				<th class="text-right"><?php echo number_format($volume_3_produk_c,2,',','.');?></th>
				<th class="text-right"><?php echo number_format($volume_4_produk_c,2,',','.');?></th>
				<th class="text-right"><?php echo number_format($volume_5_produk_c,2,',','.');?></th>
				<th class="text-right"><?php echo number_format($volume_6_produk_c,2,',','.');?></th>
				<th class="text-right"><?php echo number_format($volume_7_produk_c + $volume_8_produk_c + $volume_9_produk_c,2,',','.');?></th>
				<th class="text-right"><?php echo number_format($total_all_produk_c,2,',','.');?></th>	
			</tr>
			<tr class="table-active3-rak">
				<th class="text-center">4.</th>
				<th class="text-left">Beton K 250 (18±2)</th>
				<th class="text-center">M3</th>
				<th class="text-right"><?php echo number_format($volume_rap_2022_produk_d,2,',','.');?></th>
				<th class="text-right"><?php echo number_format($volume_akumulasi_produk_d,2,',','.');?></th>
				<th class="text-right"><?php echo number_format($volume_1_produk_d,2,',','.');?></th>
				<th class="text-right"><?php echo number_format($volume_2_produk_d,2,',','.');?></th>
				<th class="text-right"><?php echo number_format($volume_3_produk_d,2,',','.');?></th>
				<th class="text-right"><?php echo number_format($volume_4_produk_d,2,',','.');?></th>
				<th class="text-right"><?php echo number_format($volume_5_produk_d,2,',','.');?></th>
				<th class="text-right"><?php echo number_format($volume_6_produk_d,2,',','.');?></th>
				<th class="text-right"><?php echo number_format($volume_7_produk_d + $volume_8_produk_d + $volume_9_produk_d,2,',','.');?></th>
				<th class="text-right"><?php echo number_format($total_all_produk_d,2,',','.');?></th>
			</tr>
			<tr class="table-active3-rak">
				<th class="text-center">5.</th>
				<th class="text-left">Beton K 300 (10±2)</th>
				<th class="text-center">M3</th>
				<th class="text-right"><?php echo number_format($volume_rap_2022_produk_e,2,',','.');?></th>
				<th class="text-right"><?php echo number_format($volume_akumulasi_produk_e,2,',','.');?></th>
				<th class="text-right"><?php echo number_format($volume_1_produk_e,2,',','.');?></th>
				<th class="text-right"><?php echo number_format($volume_2_produk_e,2,',','.');?></th>
				<th class="text-right"><?php echo number_format($volume_3_produk_e,2,',','.');?></th>
				<th class="text-right"><?php echo number_format($volume_4_produk_e,2,',','.');?></th>
				<th class="text-right"><?php echo number_format($volume_5_produk_e,2,',','.');?></th>
				<th class="text-right"><?php echo number_format($volume_6_produk_e,2,',','.');?></th>
				<th class="text-right"><?php echo number_format($volume_7_produk_e + $volume_8_produk_e + $volume_9_produk_e,2,',','.');?></th>
				<th class="text-right"><?php echo number_format($total_all_produk_e,2,',','.');?></th>
			</tr>
			<tr class="table-active2-rak">
				<th class="text-right" colspan="2">TOTAL VOLUME</th>
				<th class="text-center">M3</th>
				<th class="text-right"><?php echo number_format($total_rap_volume_2022,2,',','.');?></th>
				<th class="text-right"><?php echo number_format($total_akumulasi_volume,2,',','.');?></th>
				<th class="text-right"><?php echo number_format($total_1_volume,2,',','.');?></th>
				<th class="text-right"><?php echo number_format($total_2_volume,2,',','.');?></th>
				<th class="text-right"><?php echo number_format($total_3_volume,2,',','.');?></th>
				<th class="text-right"><?php echo number_format($total_4_volume,2,',','.');?></th>
				<th class="text-right"><?php echo number_format($total_5_volume,2,',','.');?></th>
				<th class="text-right"><?php echo number_format($total_6_volume,2,',','.');?></th>
				<th class="text-right"><?php echo number_format($total_7_volume + $total_8_volume + $total_9_volume,2,',','.');?></th>
				<th class="text-right"><?php echo number_format($total_all_volume,2,',','.');?></th>
			</tr>
			<tr class="table-active2-rak">
				<th class="text-right" colspan="2">PENDAPATAN USAHA</th>
				<th class="text-center"></th>
				<th class="text-right"><?php echo number_format($total_rap_nilai_2022,0,',','.');?></th>
				<th class="text-right"><?php echo number_format($total_akumulasi_nilai,0,',','.');?></th>
				<th class="text-right"><?php echo number_format($total_1_nilai,0,',','.');?></th>
				<th class="text-right"><?php echo number_format($total_2_nilai,0,',','.');?></th>
				<th class="text-right"><?php echo number_format($total_3_nilai,0,',','.');?></th>
				<th class="text-right"><?php echo number_format($total_4_nilai,0,',','.');?></th>
				<th class="text-right"><?php echo number_format($total_5_nilai,0,',','.');?></th>
				<th class="text-right"><?php echo number_format($total_6_nilai,0,',','.');?></th>
				<th class="text-right"><?php echo number_format($total_7_nilai + $total_8_nilai + $total_9_nilai,0,',','.');?></th>
				<th class="text-right"><?php echo number_format($total_all_nilai,0,',','.');?></th>
			</tr>
			<tr class="table-active2-rak">
				<th class="text-left" colspan="13">BIAYA</th>
			</tr>
			<tr class="table-active3-rak">
				<th class="text-center">1.</th>
				<th class="text-left">Bahan</th>
				<th class="text-center">LS</th>
				<th class="text-right"><?php echo number_format($total_rap_2022_biaya_bahan,0,',','.');?></th>
				<th class="text-right"><?php echo number_format($total_bahan_akumulasi,0,',','.');?></th>
				<th class="text-right"><?php echo number_format($total_1_biaya_bahan,0,',','.');?></th>
				<th class="text-right"><?php echo number_format($total_2_biaya_bahan,0,',','.');?></th>
				<th class="text-right"><?php echo number_format($total_3_biaya_bahan,0,',','.');?></th>
				<th class="text-right"><?php echo number_format($total_4_biaya_bahan,0,',','.');?></th>
				<th class="text-right"><?php echo number_format($total_5_biaya_bahan,0,',','.');?></th>
				<th class="text-right"><?php echo number_format($total_6_biaya_bahan,0,',','.');?></th>
				<th class="text-right"><?php echo number_format($total_7_biaya_bahan + $total_8_biaya_bahan + $total_9_biaya_bahan,0,',','.');?></th>
				<th class="text-right"><?php echo number_format($total_all_biaya_bahan,0,',','.');?></th>
			</tr>
			<tr class="table-active3-rak">
				<th class="text-center">2.</th>
				<th class="text-left">Alat</th>
				<th class="text-center">LS</th>
				<th class="text-right"><?php echo number_format($total_rap_2022_biaya_alat,0,',','.');?></th>
				<th class="text-right"><?php echo number_format($total_alat_akumulasi,0,',','.');?></th>
				<th class="text-right"><?php echo number_format($total_1_biaya_alat,0,',','.');?></th>
				<th class="text-right"><?php echo number_format($total_2_biaya_alat,0,',','.');?></th>
				<th class="text-right"><?php echo number_format($total_3_biaya_alat,0,',','.');?></th>
				<th class="text-right"><?php echo number_format($total_4_biaya_alat,0,',','.');?></th>
				<th class="text-right"><?php echo number_format($total_5_biaya_alat,0,',','.');?></th>
				<th class="text-right"><?php echo number_format($total_6_biaya_alat,0,',','.');?></th>
				<th class="text-right"><?php echo number_format($total_7_biaya_alat + $total_8_biaya_alat + $total_9_biaya_alat,0,',','.');?></th>
				<th class="text-right"><?php echo number_format($total_all_biaya_alat,0,',','.');?></th>
			</tr>
			<tr class="table-active3-rak">
				<th class="text-center">3.</th>
				<th class="text-left">Overhead</th>
				<th class="text-center">LS</th>
				<th class="text-right"><?php echo number_format($total_rap_2022_overhead,0,',','.');?></th>
				<th class="text-right"><?php echo number_format($total_overhead_akumulasi,0,',','.');?></th>
				<th class="text-right"><?php echo number_format($total_1_overhead,0,',','.');?></th>
				<th class="text-right"><?php echo number_format($total_2_overhead,0,',','.');?></th>
				<th class="text-right"><?php echo number_format($total_3_overhead,0,',','.');?></th>
				<th class="text-right"><?php echo number_format($total_4_overhead,0,',','.');?></th>
				<th class="text-right"><?php echo number_format($total_5_overhead,0,',','.');?></th>
				<th class="text-right"><?php echo number_format($total_6_overhead,0,',','.');?></th>
				<th class="text-right"><?php echo number_format($total_7_overhead + $total_8_overhead + $total_9_overhead,0,',','.');?></th>
				<th class="text-right"><?php echo number_format($total_all_overhead,0,',','.');?></th>
			</tr>
			<tr class="table-active3-rak">
				<th class="text-center">4.</th>
				<th class="text-left">Diskonto</th>
				<th class="text-center">LS</th>
				<th class="text-right"><?php echo number_format($total_rap_2022_diskonto,0,',','.');?></th>
				<th class="text-right"><?php echo number_format($total_diskonto_akumulasi,0,',','.');?></th>
				<th class="text-right"><?php echo number_format($total_1_diskonto,0,',','.');?></th>
				<th class="text-right"><?php echo number_format($total_2_diskonto,0,',','.');?></th>
				<th class="text-right"><?php echo number_format($total_3_diskonto,0,',','.');?></th>
				<th class="text-right"><?php echo number_format($total_4_diskonto,0,',','.');?></th>
				<th class="text-right"><?php echo number_format($total_5_diskonto,0,',','.');?></th>
				<th class="text-right"><?php echo number_format($total_6_diskonto,0,',','.');?></th>
				<th class="text-right"><?php echo number_format($total_7_diskonto + $total_8_diskonto + $total_9_diskonto,0,',','.');?></th>
				<th class="text-right"><?php echo number_format($total_all_diskonto,0,',','.');?></th>
			</tr>
			<tr class="table-active2-rak">
				<th class="text-right" colspan="2">JUMLAH</th>
				<th class="text-center"></th>
				<th class="text-right"><?php echo number_format($total_biaya_rap_2022_biaya,0,',','.');?></th>
				<th class="text-right"><?php echo number_format($total_biaya_akumulasi,0,',','.');?></th>
				<th class="text-right"><?php echo number_format($total_biaya_1_biaya,0,',','.');?></th>
				<th class="text-right"><?php echo number_format($total_biaya_2_biaya,0,',','.');?></th>
				<th class="text-right"><?php echo number_format($total_biaya_3_biaya,0,',','.');?></th>
				<th class="text-right"><?php echo number_format($total_biaya_4_biaya,0,',','.');?></th>
				<th class="text-right"><?php echo number_format($total_biaya_5_biaya,0,',','.');?></th>
				<th class="text-right"><?php echo number_format($total_biaya_6_biaya,0,',','.');?></th>
				<th class="text-right"><?php echo number_format($total_biaya_7_biaya + $total_biaya_8_biaya + $total_biaya_9_biaya,0,',','.');?></th>
				<th class="text-right"><?php echo number_format($total_biaya_all_biaya,0,',','.');?></th>
			</tr>
			<tr class="table-active2-rak">
				<th class="text-right" colspan="2">LABA</th>
				<th class="text-center"></th>
				<th class="text-right"><?php echo number_format($total_laba_rap_2022,0,',','.');?></th>
				<th class="text-right"><?php echo number_format($total_laba_saat_ini,0,',','.');?></th>
				<th class="text-right"><?php echo number_format($total_laba_1,0,',','.');?></th>
				<th class="text-right"><?php echo number_format($total_laba_2,0,',','.');?></th>
				<th class="text-right"><?php echo number_format($total_laba_3,0,',','.');?></th>
				<th class="text-right"><?php echo number_format($total_laba_4,0,',','.');?></th>
				<th class="text-right"><?php echo number_format($total_laba_5,0,',','.');?></th>
				<th class="text-right"><?php echo number_format($total_laba_6,0,',','.');?></th>
				<th class="text-right"><?php echo number_format($total_laba_7 + $total_laba_8 + $total_laba_9,0,',','.');?></th>
				<th class="text-right"><?php echo number_format($total_laba_all,0,',','.');?></th>
			</tr>
			
	    </table>
		<?php
	}

	public function rencana_kerja($arr_date)
	{
		$data = array();
		
		$arr_date = $this->input->post('filter_date');
		$arr_filter_date = explode(' - ', $arr_date);
		$date1 = '';
		$date2 = '';

		if(count($arr_filter_date) == 2){
			$date1 	= date('Y-m-d',strtotime($arr_filter_date[0]));
			$date2 	= date('Y-m-d',strtotime($arr_filter_date[1]));
			$filter_date = date('d F Y',strtotime($arr_filter_date[0])).' - '.date('d F Y',strtotime($arr_filter_date[1]));
		}
		
		?>
		
		<table class="table table-bordered" width="100%">
			<style type="text/css">
				table tr.table-judul{
					background-color: #666666;
					font-size: 10px;
					font-weight: bold;
					color: white;
				}
					
				table tr.table-baris{
					background-color: #F0F0F0;
					font-size: 10px;
					font-weight: bold;
				}

				table tr.table-total{
					background-color: #E8E8E8;
					font-size: 10px;
					font-weight: bold;
				}
			</style>

			<?php
			$date_now = date('Y-m-d');

			//BULAN 1
			$date_1_awal = date('Y-m-01', (strtotime($date_now)));
			$date_1_akhir = date('Y-m-d', strtotime('-1 days +1 months', strtotime($date_1_awal)));
			
			$rencana_kerja_1 = $this->db->select('r.*, SUM(r.vol_produk_a) as vol_produk_a, SUM(r.vol_produk_b) as vol_produk_b, SUM(r.vol_produk_c) as vol_produk_c, SUM(r.vol_produk_d) as vol_produk_d, SUM(r.vol_produk_e) as vol_produk_e')
			->from('rak r')
			->where("r.tanggal_rencana_kerja between '$date_1_awal' and '$date_1_akhir'")
			->get()->row_array();

			$volume_1_produk_a = $rencana_kerja_1['vol_produk_a'];
			$volume_1_produk_b = $rencana_kerja_1['vol_produk_b'];
			$volume_1_produk_c = $rencana_kerja_1['vol_produk_c'];
			$volume_1_produk_d = $rencana_kerja_1['vol_produk_d'];
			$volume_1_produk_e = $rencana_kerja_1['vol_produk_e'];

			$total_1_volume = $volume_1_produk_a + $volume_1_produk_b + $volume_1_produk_c + $volume_1_produk_d + $volume_1_produk_e;
				
			$nilai_jual_125_1 = $volume_1_produk_a * $rencana_kerja_1['price_a'];
			$nilai_jual_225_1 = $volume_1_produk_b * $rencana_kerja_1['price_b'];
			$nilai_jual_250_1 = $volume_1_produk_c * $rencana_kerja_1['price_c'];
			$nilai_jual_250_18_1 = $volume_1_produk_d * $rencana_kerja_1['price_d'];
			$nilai_jual_300_1 = $volume_1_produk_e * $rencana_kerja_1['price_e'];
			$nilai_jual_all_1 = $nilai_jual_125_1 + $nilai_jual_225_1 + $nilai_jual_250_1 + $nilai_jual_250_18_1 +  + $nilai_jual_300_1;

			//BULAN 2
			$date_2_awal = date('Y-m-d', strtotime('+1 days', strtotime($date_1_akhir)));
			$date_2_akhir = date('Y-m-d', strtotime('-1 days +1 months', strtotime($date_2_awal)));

			$rencana_kerja_2 = $this->db->select('r.*, SUM(r.vol_produk_a) as vol_produk_a, SUM(r.vol_produk_b) as vol_produk_b, SUM(r.vol_produk_c) as vol_produk_c, SUM(r.vol_produk_d) as vol_produk_d, SUM(r.vol_produk_e) as vol_produk_e')
			->from('rak r')
			->where("r.tanggal_rencana_kerja between '$date_2_awal' and '$date_2_akhir'")
			->get()->row_array();

			$volume_2_produk_a = $rencana_kerja_2['vol_produk_a'];
			$volume_2_produk_b = $rencana_kerja_2['vol_produk_b'];
			$volume_2_produk_c = $rencana_kerja_2['vol_produk_c'];
			$volume_2_produk_d = $rencana_kerja_2['vol_produk_d'];
			$volume_2_produk_e = $rencana_kerja_2['vol_produk_e'];

			$total_2_volume = $volume_2_produk_a + $volume_2_produk_b + $volume_2_produk_c + $volume_2_produk_d + $volume_2_produk_e;
				
			$nilai_jual_125_2 = $volume_2_produk_a * $rencana_kerja_2['price_a'];
			$nilai_jual_225_2 = $volume_2_produk_b * $rencana_kerja_2['price_b'];
			$nilai_jual_250_2 = $volume_2_produk_c * $rencana_kerja_2['price_c'];
			$nilai_jual_250_18_2 = $volume_2_produk_d * $rencana_kerja_2['price_d'];
			$nilai_jual_300_2 = $volume_2_produk_e * $rencana_kerja_2['price_e'];
			$nilai_jual_all_2 = $nilai_jual_125_2 + $nilai_jual_225_2 + $nilai_jual_250_2 + $nilai_jual_250_18_2 + $nilai_jual_300_2;

			//BULAN 3
			$date_3_awal = date('Y-m-d', strtotime('+1 days', strtotime($date_2_akhir)));
			$date_3_akhir = date('Y-m-d', strtotime('-1 days +1 months', strtotime($date_3_awal)));

			$rencana_kerja_3 = $this->db->select('r.*, SUM(r.vol_produk_a) as vol_produk_a, SUM(r.vol_produk_b) as vol_produk_b, SUM(r.vol_produk_c) as vol_produk_c, SUM(r.vol_produk_d) as vol_produk_d, SUM(r.vol_produk_e) as vol_produk_e')
			->from('rak r')
			->where("r.tanggal_rencana_kerja between '$date_3_awal' and '$date_3_akhir'")
			->get()->row_array();

			$volume_3_produk_a = $rencana_kerja_3['vol_produk_a'];
			$volume_3_produk_b = $rencana_kerja_3['vol_produk_b'];
			$volume_3_produk_c = $rencana_kerja_3['vol_produk_c'];
			$volume_3_produk_d = $rencana_kerja_3['vol_produk_d'];
			$volume_3_produk_e = $rencana_kerja_3['vol_produk_e'];

			$total_3_volume = $volume_3_produk_a + $volume_3_produk_b + $volume_3_produk_c + $volume_3_produk_d + $volume_3_produk_e;
				
			$nilai_jual_125_3 = $volume_3_produk_a * $rencana_kerja_3['price_a'];
			$nilai_jual_225_3 = $volume_3_produk_b * $rencana_kerja_3['price_b'];
			$nilai_jual_250_3 = $volume_3_produk_c * $rencana_kerja_3['price_c'];
			$nilai_jual_250_18_3 = $volume_3_produk_d * $rencana_kerja_3['price_d'];
			$nilai_jual_300_3 = $volume_3_produk_e * $rencana_kerja_3['price_e'];
			$nilai_jual_all_3 = $nilai_jual_125_3 + $nilai_jual_225_3 + $nilai_jual_250_3 + $nilai_jual_250_18_3 + $nilai_jual_300_3;

			//BULAN 4
			$date_4_awal = date('Y-m-d', strtotime('+1 days', strtotime($date_3_akhir)));
			$date_4_akhir = date('Y-m-d', strtotime('-1 days +1 months', strtotime($date_4_awal)));

			$rencana_kerja_4 = $this->db->select('r.*, SUM(r.vol_produk_a) as vol_produk_a, SUM(r.vol_produk_b) as vol_produk_b, SUM(r.vol_produk_c) as vol_produk_c, SUM(r.vol_produk_d) as vol_produk_d, SUM(r.vol_produk_e) as vol_produk_e')
			->from('rak r')
			->where("r.tanggal_rencana_kerja between '$date_4_awal' and '$date_4_akhir'")
			->get()->row_array();

			$volume_4_produk_a = $rencana_kerja_4['vol_produk_a'];
			$volume_4_produk_b = $rencana_kerja_4['vol_produk_b'];
			$volume_4_produk_c = $rencana_kerja_4['vol_produk_c'];
			$volume_4_produk_d = $rencana_kerja_4['vol_produk_d'];
			$volume_4_produk_e = $rencana_kerja_4['vol_produk_e'];

			$total_4_volume = $volume_4_produk_a + $volume_4_produk_b + $volume_4_produk_c + $volume_4_produk_d + $volume_4_produk_e;
				
			$nilai_jual_125_4 = $volume_4_produk_a * $rencana_kerja_4['price_a'];
			$nilai_jual_225_4 = $volume_4_produk_b * $rencana_kerja_4['price_b'];
			$nilai_jual_250_4 = $volume_4_produk_c * $rencana_kerja_4['price_c'];
			$nilai_jual_250_18_4 = $volume_4_produk_d * $rencana_kerja_4['price_d'];
			$nilai_jual_300_4 = $volume_4_produk_d * $rencana_kerja_4['price_e'];
			$nilai_jual_all_4 = $nilai_jual_125_4 + $nilai_jual_225_4 + $nilai_jual_250_4 + $nilai_jual_250_18_4 + $nilai_jual_300_4;

			//BULAN 5
			$date_5_awal = date('Y-m-d', strtotime('+1 days', strtotime($date_4_akhir)));
			$date_5_akhir = date('Y-m-d', strtotime('-1 days +1 months', strtotime($date_5_awal)));

			$rencana_kerja_5 = $this->db->select('r.*, SUM(r.vol_produk_a) as vol_produk_a, SUM(r.vol_produk_b) as vol_produk_b, SUM(r.vol_produk_c) as vol_produk_c, SUM(r.vol_produk_d) as vol_produk_d, SUM(r.vol_produk_e) as vol_produk_e')
			->from('rak r')
			->where("r.tanggal_rencana_kerja between '$date_5_awal' and '$date_5_akhir'")
			->get()->row_array();

			$volume_5_produk_a = $rencana_kerja_5['vol_produk_a'];
			$volume_5_produk_b = $rencana_kerja_5['vol_produk_b'];
			$volume_5_produk_c = $rencana_kerja_5['vol_produk_c'];
			$volume_5_produk_d = $rencana_kerja_5['vol_produk_d'];
			$volume_5_produk_e = $rencana_kerja_5['vol_produk_e'];

			$total_5_volume = $volume_5_produk_a + $volume_5_produk_b + $volume_5_produk_c + $volume_5_produk_d+ $volume_5_produk_e;
				
			$nilai_jual_125_5 = $volume_5_produk_a * $rencana_kerja_5['price_a'];
			$nilai_jual_225_5 = $volume_5_produk_b * $rencana_kerja_5['price_b'];
			$nilai_jual_250_5 = $volume_5_produk_c * $rencana_kerja_5['price_c'];
			$nilai_jual_250_18_5 = $volume_5_produk_d * $rencana_kerja_5['price_d'];
			$nilai_jual_300 = $volume_5_produk_e * $rencana_kerja_5['price_e'];
			$nilai_jual_all_5 = $nilai_jual_125_5 + $nilai_jual_225_5 + $nilai_jual_250_5 + $nilai_jual_250_18_5 + $nilai_jual_300;

			//BULAN 6
			$date_6_awal = date('Y-m-d', strtotime('+1 days', strtotime($date_5_akhir)));
			$date_6_akhir = date('Y-m-d', strtotime('-1 days +1 months', strtotime($date_6_awal)));

			$rencana_kerja_6 = $this->db->select('r.*, SUM(r.vol_produk_a) as vol_produk_a, SUM(r.vol_produk_b) as vol_produk_b, SUM(r.vol_produk_c) as vol_produk_c, SUM(r.vol_produk_d) as vol_produk_d, SUM(r.vol_produk_e) as vol_produk_e')
			->from('rak r')
			->where("r.tanggal_rencana_kerja between '$date_6_awal' and '$date_6_akhir'")
			->get()->row_array();

			$volume_6_produk_a = $rencana_kerja_6['vol_produk_a'];
			$volume_6_produk_b = $rencana_kerja_6['vol_produk_b'];
			$volume_6_produk_c = $rencana_kerja_6['vol_produk_c'];
			$volume_6_produk_d = $rencana_kerja_6['vol_produk_d'];
			$volume_6_produk_e = $rencana_kerja_6['vol_produk_e'];

			$total_6_volume = $volume_6_produk_a + $volume_6_produk_b + $volume_6_produk_c + $volume_6_produk_d + $volume_6_produk_e;
				
			$nilai_jual_125_6 = $volume_6_produk_a * $rencana_kerja_6['price_a'];
			$nilai_jual_225_6 = $volume_6_produk_b * $rencana_kerja_6['price_b'];
			$nilai_jual_250_6 = $volume_6_produk_c * $rencana_kerja_6['price_c'];
			$nilai_jual_250_18_6 = $volume_6_produk_d * $rencana_kerja_6['price_d'];
			$nilai_jual_300_6 = $volume_6_produk_e * $rencana_kerja_6['price_e'];
			$nilai_jual_all_6 = $nilai_jual_125_6 + $nilai_jual_225_6 + $nilai_jual_250_6 + $nilai_jual_250_18_6 + $nilai_jual_300_6;
			?>

			
			<?php
			
			//BULAN 1
			$komposisi_125_1 = $this->db->select('(r.vol_produk_a * pk.presentase_a) as komposisi_semen_125, (vol_produk_a * pk.presentase_b) as komposisi_pasir_125, (vol_produk_a * pk.presentase_c) as komposisi_batu1020_125, (vol_produk_a * pk.presentase_d) as komposisi_batu2030_125')
			->from('rak r')
			->join('pmm_agregat pk', 'r.komposisi_125 = pk.id','left')
			->where("r.tanggal_rencana_kerja between '$date_1_awal' and '$date_1_akhir'")
			->get()->result_array();

			$total_volume_semen_125_1 = 0;
			$total_volume_pasir_125_1 = 0;
			$total_volume_batu1020_125_1 = 0;
			$total_volume_batu2030_125_1 = 0;

			foreach ($komposisi_125_1 as $x){
				$total_volume_semen_125_1 = $x['komposisi_semen_125'];
				$total_volume_pasir_125_1 = $x['komposisi_pasir_125'];
				$total_volume_batu1020_125_1 = $x['komposisi_batu1020_125'];
				$total_volume_batu2030_125_1 = $x['komposisi_batu2030_125'];
			}

			$komposisi_225_1 = $this->db->select('(r.vol_produk_b * pk.presentase_a) as komposisi_semen_225, (vol_produk_b * pk.presentase_b) as komposisi_pasir_225, (vol_produk_b * pk.presentase_c) as komposisi_batu1020_225, (vol_produk_b * pk.presentase_d) as komposisi_batu2030_225')
			->from('rak r')
			->join('pmm_agregat pk', 'r.komposisi_225 = pk.id','left')
			->where("r.tanggal_rencana_kerja between '$date_1_awal' and '$date_1_akhir'")
			->get()->result_array();

			$total_volume_semen_225_1 = 0;
			$total_volume_pasir_225_1 = 0;
			$total_volume_batu1020_225_1 = 0;
			$total_volume_batu2030_225_1 = 0;

			foreach ($komposisi_225_1 as $x){
				$total_volume_semen_225_1 = $x['komposisi_semen_225'];
				$total_volume_pasir_225_1 = $x['komposisi_pasir_225'];
				$total_volume_batu1020_225_1 = $x['komposisi_batu1020_225'];
				$total_volume_batu2030_225_1 = $x['komposisi_batu2030_225'];
			}

			$komposisi_250_1 = $this->db->select('(r.vol_produk_c * pk.presentase_a) as komposisi_semen_250, (vol_produk_c * pk.presentase_b) as komposisi_pasir_250, (vol_produk_c * pk.presentase_c) as komposisi_batu1020_250, (vol_produk_c * pk.presentase_d) as komposisi_batu2030_250')
			->from('rak r')
			->join('pmm_agregat pk', 'r.komposisi_250 = pk.id','left')
			->where("r.tanggal_rencana_kerja between '$date_1_awal' and '$date_1_akhir'")
			->get()->result_array();

			$total_volume_semen_250_1 = 0;
			$total_volume_pasir_250_1 = 0;
			$total_volume_batu1020_250_1 = 0;
			$total_volume_batu2030_250_1 = 0;

			foreach ($komposisi_250_1 as $x){
				$total_volume_semen_250_1 = $x['komposisi_semen_250'];
				$total_volume_pasir_250_1 = $x['komposisi_pasir_250'];
				$total_volume_batu1020_250_1 = $x['komposisi_batu1020_250'];
				$total_volume_batu2030_250_1 = $x['komposisi_batu2030_250'];
			}

			$komposisi_250_2_1 = $this->db->select('(r.vol_produk_d * pk.presentase_a) as komposisi_semen_250_2, (vol_produk_d * pk.presentase_b) as komposisi_pasir_250_2, (vol_produk_d * pk.presentase_c) as komposisi_batu1020_250_2, (vol_produk_d * pk.presentase_d) as komposisi_batu2030_250_2')
			->from('rak r')
			->join('pmm_agregat pk', 'r.komposisi_250_2 = pk.id','left')
			->where("r.tanggal_rencana_kerja between '$date_1_awal' and '$date_1_akhir'")
			->get()->result_array();

			$total_volume_semen_250_2_1 = 0;
			$total_volume_pasir_250_2_1 = 0;
			$total_volume_batu1020_250_2_1 = 0;
			$total_volume_batu2030_250_2_1 = 0;

			foreach ($komposisi_250_2_1 as $x){
				$total_volume_semen_250_2_1 = $x['komposisi_semen_250_2'];
				$total_volume_pasir_250_2_1 = $x['komposisi_pasir_250_2'];
				$total_volume_batu1020_250_2_1 = $x['komposisi_batu1020_250_2'];
				$total_volume_batu2030_250_2_1 = $x['komposisi_batu2030_250_2'];
			}

			$komposisi_300_1 = $this->db->select('(r.vol_produk_e * pk.presentase_a) as komposisi_semen_300_1, (vol_produk_e * pk.presentase_b) as komposisi_pasir_300_1, (vol_produk_e * pk.presentase_c) as komposisi_batu1020_300_1, (vol_produk_e * pk.presentase_d) as komposisi_batu2030_300_1')
			->from('rak r')
			->join('pmm_agregat pk', 'r.komposisi_300 = pk.id','left')
			->where("r.tanggal_rencana_kerja between '$date_1_awal' and '$date_1_akhir'")
			->get()->result_array();

			$total_volume_semen_300_1 = 0;
			$total_volume_pasir_300_1 = 0;
			$total_volume_batu1020_300_1 = 0;
			$total_volume_batu2030_300_1 = 0;

			foreach ($komposisi_300_1 as $x){
				$total_volume_semen_300_1 = $x['komposisi_semen_300'];
				$total_volume_pasir_300_1 = $x['komposisi_pasir_300'];
				$total_volume_batu1020_300_1 = $x['komposisi_batu1020_300'];
				$total_volume_batu2030_300_1 = $x['komposisi_batu2030_300'];
			}

			$total_volume_semen_1 = $total_volume_semen_125_1 + $total_volume_semen_225_1 + $total_volume_semen_250_1 + $total_volume_semen_250_2_1 + $total_volume_semen_300_1;
			$total_volume_pasir_1 = $total_volume_pasir_125_1 + $total_volume_pasir_225_1 + $total_volume_pasir_250_1 + $total_volume_pasir_250_2_1 + $total_volume_pasir_300_1;
			$total_volume_batu1020_1 = $total_volume_batu1020_125_1 + $total_volume_batu1020_225_1 + $total_volume_batu1020_250_1 + $total_volume_batu1020_250_2_1 + $total_volume_batu1020_300_1;
			$total_volume_batu2030_1 = $total_volume_batu2030_125_1 + $total_volume_batu2030_225_1 + $total_volume_batu2030_250_1 + $total_volume_batu2030_250_2_1 + $total_volume_batu2030_300_1;

			//BULAN 2
			$komposisi_125_2 = $this->db->select('(r.vol_produk_a * pk.presentase_a) as komposisi_semen_125, (vol_produk_a * pk.presentase_b) as komposisi_pasir_125, (vol_produk_a * pk.presentase_c) as komposisi_batu1020_125, (vol_produk_a * pk.presentase_d) as komposisi_batu2030_125')
			->from('rak r')
			->join('pmm_agregat pk', 'r.komposisi_125 = pk.id','left')
			->where("r.tanggal_rencana_kerja between '$date_2_awal' and '$date_2_akhir'")
			->get()->result_array();

			$total_volume_semen_125_2 = 0;
			$total_volume_pasir_125_2 = 0;
			$total_volume_batu1020_125_2 = 0;
			$total_volume_batu2030_125_2 = 0;

			foreach ($komposisi_125_2 as $x){
				$total_volume_semen_125_2 = $x['komposisi_semen_125'];
				$total_volume_pasir_125_2 = $x['komposisi_pasir_125'];
				$total_volume_batu1020_125_2 = $x['komposisi_batu1020_125'];
				$total_volume_batu2030_125_2 = $x['komposisi_batu2030_125'];
			}

			$komposisi_225_2 = $this->db->select('(r.vol_produk_b * pk.presentase_a) as komposisi_semen_225, (vol_produk_b * pk.presentase_b) as komposisi_pasir_225, (vol_produk_b * pk.presentase_c) as komposisi_batu1020_225, (vol_produk_b * pk.presentase_d) as komposisi_batu2030_225')
			->from('rak r')
			->join('pmm_agregat pk', 'r.komposisi_225 = pk.id','left')
			->where("r.tanggal_rencana_kerja between '$date_2_awal' and '$date_2_akhir'")
			->get()->result_array();

			$total_volume_semen_225_2 = 0;
			$total_volume_pasir_225_2 = 0;
			$total_volume_batu1020_225_2 = 0;
			$total_volume_batu2030_225_2 = 0;

			foreach ($komposisi_225_2 as $x){
				$total_volume_semen_225_2 = $x['komposisi_semen_225'];
				$total_volume_pasir_225_2 = $x['komposisi_pasir_225'];
				$total_volume_batu1020_225_2 = $x['komposisi_batu1020_225'];
				$total_volume_batu2030_225_2 = $x['komposisi_batu2030_225'];
			}

			$komposisi_250_2 = $this->db->select('(r.vol_produk_c * pk.presentase_a) as komposisi_semen_250, (vol_produk_c * pk.presentase_b) as komposisi_pasir_250, (vol_produk_c * pk.presentase_c) as komposisi_batu1020_250, (vol_produk_c * pk.presentase_d) as komposisi_batu2030_250')
			->from('rak r')
			->join('pmm_agregat pk', 'r.komposisi_250 = pk.id','left')
			->where("r.tanggal_rencana_kerja between '$date_2_awal' and '$date_2_akhir'")
			->get()->result_array();

			$total_volume_semen_250_2 = 0;
			$total_volume_pasir_250_2 = 0;
			$total_volume_batu1020_250_2 = 0;
			$total_volume_batu2030_250_2 = 0;

			foreach ($komposisi_250_2 as $x){
				$total_volume_semen_250_2 = $x['komposisi_semen_250'];
				$total_volume_pasir_250_2 = $x['komposisi_pasir_250'];
				$total_volume_batu1020_250_2 = $x['komposisi_batu1020_250'];
				$total_volume_batu2030_250_2 = $x['komposisi_batu2030_250'];
			}

			$komposisi_250_2_2 = $this->db->select('(r.vol_produk_d * pk.presentase_a) as komposisi_semen_250_2, (vol_produk_d * pk.presentase_b) as komposisi_pasir_250_2, (vol_produk_d * pk.presentase_c) as komposisi_batu1020_250_2, (vol_produk_d * pk.presentase_d) as komposisi_batu2030_250_2')
			->from('rak r')
			->join('pmm_agregat pk', 'r.komposisi_250_2 = pk.id','left')
			->where("r.tanggal_rencana_kerja between '$date_2_awal' and '$date_2_akhir'")
			->get()->result_array();

			$total_volume_semen_250_2_2 = 0;
			$total_volume_pasir_250_2_2 = 0;
			$total_volume_batu1020_250_2_2 = 0;
			$total_volume_batu2030_250_2_2 = 0;

			foreach ($komposisi_250_2_2 as $x){
				$total_volume_semen_250_2_2 = $x['komposisi_semen_250_2'];
				$total_volume_pasir_250_2_2 = $x['komposisi_pasir_250_2'];
				$total_volume_batu1020_250_2_2 = $x['komposisi_batu1020_250_2'];
				$total_volume_batu2030_250_2_2 = $x['komposisi_batu2030_250_2'];
			}

			$komposisi_300_2 = $this->db->select('(r.vol_produk_e * pk.presentase_a) as komposisi_semen_300_2, (vol_produk_e * pk.presentase_b) as komposisi_pasir_300_2, (vol_produk_e * pk.presentase_c) as komposisi_batu1020_300_2, (vol_produk_e * pk.presentase_d) as komposisi_batu2030_300_2')
			->from('rak r')
			->join('pmm_agregat pk', 'r.komposisi_300 = pk.id','left')
			->where("r.tanggal_rencana_kerja between '$date_2_awal' and '$date_2_akhir'")
			->get()->result_array();

			$total_volume_semen_300_2 = 0;
			$total_volume_pasir_300_2 = 0;
			$total_volume_batu1020_300_2 = 0;
			$total_volume_batu2030_300_2 = 0;

			foreach ($komposisi_300_2 as $x){
				$total_volume_semen_300_2 = $x['komposisi_semen_300'];
				$total_volume_pasir_300_2 = $x['komposisi_pasir_300'];
				$total_volume_batu1020_300_2 = $x['komposisi_batu1020_300'];
				$total_volume_batu2030_300_2 = $x['komposisi_batu2030_300'];
			}

			$total_volume_semen_2 = $total_volume_semen_125_2 + $total_volume_semen_225_2 + $total_volume_semen_250_2 + $total_volume_semen_250_2_2  + $total_volume_semen_300_2;
			$total_volume_pasir_2 = $total_volume_pasir_125_2 + $total_volume_pasir_225_2 + $total_volume_pasir_250_2 + $total_volume_pasir_250_2_2 + $total_volume_pasir_300_2;
			$total_volume_batu1020_2 = $total_volume_batu1020_125_2 + $total_volume_batu1020_225_2 + $total_volume_batu1020_250_2 + $total_volume_batu1020_250_2_2 + $total_volume_batu1020_300_2;
			$total_volume_batu2030_2 = $total_volume_batu2030_125_2 + $total_volume_batu2030_225_2 + $total_volume_batu2030_250_2 + $total_volume_batu2030_250_2_2 + $total_volume_batu2030_300_2;

			//BULAN 3
			$komposisi_125_3 = $this->db->select('(r.vol_produk_a * pk.presentase_a) as komposisi_semen_125, (vol_produk_a * pk.presentase_b) as komposisi_pasir_125, (vol_produk_a * pk.presentase_c) as komposisi_batu1020_125, (vol_produk_a * pk.presentase_d) as komposisi_batu2030_125')
			->from('rak r')
			->join('pmm_agregat pk', 'r.komposisi_125 = pk.id','left')
			->where("r.tanggal_rencana_kerja between '$date_3_awal' and '$date_3_akhir'")
			->get()->result_array();

			$total_volume_semen_125_3 = 0;
			$total_volume_pasir_125_3 = 0;
			$total_volume_batu1020_125_3 = 0;
			$total_volume_batu2030_125_3 = 0;

			foreach ($komposisi_125_3 as $x){
				$total_volume_semen_125_3 = $x['komposisi_semen_125'];
				$total_volume_pasir_125_3 = $x['komposisi_pasir_125'];
				$total_volume_batu1020_125_3 = $x['komposisi_batu1020_125'];
				$total_volume_batu2030_125_3 = $x['komposisi_batu2030_125'];
			}

			$komposisi_225_3 = $this->db->select('(r.vol_produk_b * pk.presentase_a) as komposisi_semen_225, (vol_produk_b * pk.presentase_b) as komposisi_pasir_225, (vol_produk_b * pk.presentase_c) as komposisi_batu1020_225, (vol_produk_b * pk.presentase_d) as komposisi_batu2030_225')
			->from('rak r')
			->join('pmm_agregat pk', 'r.komposisi_225 = pk.id','left')
			->where("r.tanggal_rencana_kerja between '$date_3_awal' and '$date_3_akhir'")
			->get()->result_array();

			$total_volume_semen_225_3 = 0;
			$total_volume_pasir_225_3 = 0;
			$total_volume_batu1020_225_3 = 0;
			$total_volume_batu2030_225_3 = 0;

			foreach ($komposisi_225_3 as $x){
				$total_volume_semen_225_3 = $x['komposisi_semen_225'];
				$total_volume_pasir_225_3 = $x['komposisi_pasir_225'];
				$total_volume_batu1020_225_3 = $x['komposisi_batu1020_225'];
				$total_volume_batu2030_225_3 = $x['komposisi_batu2030_225'];
			}

			$komposisi_250_3 = $this->db->select('(r.vol_produk_c * pk.presentase_a) as komposisi_semen_250, (vol_produk_c * pk.presentase_b) as komposisi_pasir_250, (vol_produk_c * pk.presentase_c) as komposisi_batu1020_250, (vol_produk_c * pk.presentase_d) as komposisi_batu2030_250')
			->from('rak r')
			->join('pmm_agregat pk', 'r.komposisi_250 = pk.id','left')
			->where("r.tanggal_rencana_kerja between '$date_3_awal' and '$date_3_akhir'")
			->get()->result_array();

			$total_volume_semen_250_3 = 0;
			$total_volume_pasir_250_3 = 0;
			$total_volume_batu1020_250_3 = 0;
			$total_volume_batu2030_250_3 = 0;

			foreach ($komposisi_250_3 as $x){
				$total_volume_semen_250_3 = $x['komposisi_semen_250'];
				$total_volume_pasir_250_3 = $x['komposisi_pasir_250'];
				$total_volume_batu1020_250_3 = $x['komposisi_batu1020_250'];
				$total_volume_batu2030_250_3 = $x['komposisi_batu2030_250'];
			}

			$komposisi_250_2_3 = $this->db->select('(r.vol_produk_d * pk.presentase_a) as komposisi_semen_250_2, (vol_produk_d * pk.presentase_b) as komposisi_pasir_250_2, (vol_produk_d * pk.presentase_c) as komposisi_batu1020_250_2, (vol_produk_d * pk.presentase_d) as komposisi_batu2030_250_2')
			->from('rak r')
			->join('pmm_agregat pk', 'r.komposisi_250_2 = pk.id','left')
			->where("r.tanggal_rencana_kerja between '$date_3_awal' and '$date_3_akhir'")
			->get()->result_array();

			$total_volume_semen_250_2_3 = 0;
			$total_volume_pasir_250_2_3 = 0;
			$total_volume_batu1020_250_2_3 = 0;
			$total_volume_batu2030_250_2_3 = 0;

			foreach ($komposisi_250_2_3 as $x){
				$total_volume_semen_250_2_3 = $x['komposisi_semen_250_2'];
				$total_volume_pasir_250_2_3 = $x['komposisi_pasir_250_2'];
				$total_volume_batu1020_250_2_3 = $x['komposisi_batu1020_250_2'];
				$total_volume_batu2030_250_2_3 = $x['komposisi_batu2030_250_2'];
			}

			$komposisi_300_3 = $this->db->select('(r.vol_produk_e * pk.presentase_a) as komposisi_semen_300_3, (vol_produk_e * pk.presentase_b) as komposisi_pasir_300_3, (vol_produk_e * pk.presentase_c) as komposisi_batu1020_300_3, (vol_produk_e * pk.presentase_d) as komposisi_batu2030_300_3')
			->from('rak r')
			->join('pmm_agregat pk', 'r.komposisi_300 = pk.id','left')
			->where("r.tanggal_rencana_kerja between '$date_3_awal' and '$date_3_akhir'")
			->get()->result_array();

			$total_volume_semen_300_3 = 0;
			$total_volume_pasir_300_3 = 0;
			$total_volume_batu1020_300_3 = 0;
			$total_volume_batu2030_300_3 = 0;

			foreach ($komposisi_300_3 as $x){
				$total_volume_semen_300_3 = $x['komposisi_semen_300'];
				$total_volume_pasir_300_3 = $x['komposisi_pasir_300'];
				$total_volume_batu1020_300_3 = $x['komposisi_batu1020_300'];
				$total_volume_batu2030_300_3 = $x['komposisi_batu2030_300'];
			}

			$total_volume_semen_3 = $total_volume_semen_125_3 + $total_volume_semen_225_3 + $total_volume_semen_250_3 + $total_volume_semen_250_2_3 + $total_volume_semen_300_3;
			$total_volume_pasir_3 = $total_volume_pasir_125_3 + $total_volume_pasir_225_3 + $total_volume_pasir_250_3 + $total_volume_pasir_250_2_3 + $total_volume_pasir_300_3;
			$total_volume_batu1020_3 = $total_volume_batu1020_125_3 + $total_volume_batu1020_225_3 + $total_volume_batu1020_250_3 + $total_volume_batu1020_250_2_3 + $total_volume_batu1020_300_3;
			$total_volume_batu2030_3 = $total_volume_batu2030_125_3 + $total_volume_batu2030_225_3 + $total_volume_batu2030_250_3 + $total_volume_batu2030_250_2_3 + $total_volume_batu2030_300_3;

			//BULAN 4
			$komposisi_125_4 = $this->db->select('(r.vol_produk_a * pk.presentase_a) as komposisi_semen_125, (vol_produk_a * pk.presentase_b) as komposisi_pasir_125, (vol_produk_a * pk.presentase_c) as komposisi_batu1020_125, (vol_produk_a * pk.presentase_d) as komposisi_batu2030_125')
			->from('rak r')
			->join('pmm_agregat pk', 'r.komposisi_125 = pk.id','left')
			->where("r.tanggal_rencana_kerja between '$date_4_awal' and '$date_4_akhir'")
			->get()->result_array();

			$total_volume_semen_125_4 = 0;
			$total_volume_pasir_125_4 = 0;
			$total_volume_batu1020_125_4 = 0;
			$total_volume_batu2030_125_4 = 0;

			foreach ($komposisi_125_4 as $x){
				$total_volume_semen_125_4 = $x['komposisi_semen_125'];
				$total_volume_pasir_125_4 = $x['komposisi_pasir_125'];
				$total_volume_batu1020_125_4 = $x['komposisi_batu1020_125'];
				$total_volume_batu2030_125_4 = $x['komposisi_batu2030_125'];
			}

			$komposisi_225_4 = $this->db->select('(r.vol_produk_b * pk.presentase_a) as komposisi_semen_225, (vol_produk_b * pk.presentase_b) as komposisi_pasir_225, (vol_produk_b * pk.presentase_c) as komposisi_batu1020_225, (vol_produk_b * pk.presentase_d) as komposisi_batu2030_225')
			->from('rak r')
			->join('pmm_agregat pk', 'r.komposisi_225 = pk.id','left')
			->where("r.tanggal_rencana_kerja between '$date_4_awal' and '$date_4_akhir'")
			->get()->result_array();

			$total_volume_semen_225_4 = 0;
			$total_volume_pasir_225_4 = 0;
			$total_volume_batu1020_225_4 = 0;
			$total_volume_batu2030_225_4 = 0;

			foreach ($komposisi_225_4 as $x){
				$total_volume_semen_225_4 = $x['komposisi_semen_225'];
				$total_volume_pasir_225_4 = $x['komposisi_pasir_225'];
				$total_volume_batu1020_225_4 = $x['komposisi_batu1020_225'];
				$total_volume_batu2030_225_4 = $x['komposisi_batu2030_225'];
			}

			$komposisi_250_4 = $this->db->select('(r.vol_produk_c * pk.presentase_a) as komposisi_semen_250, (vol_produk_c * pk.presentase_b) as komposisi_pasir_250, (vol_produk_c * pk.presentase_c) as komposisi_batu1020_250, (vol_produk_c * pk.presentase_d) as komposisi_batu2030_250')
			->from('rak r')
			->join('pmm_agregat pk', 'r.komposisi_250 = pk.id','left')
			->where("r.tanggal_rencana_kerja between '$date_4_awal' and '$date_4_akhir'")
			->get()->result_array();

			$total_volume_semen_250_4 = 0;
			$total_volume_pasir_250_4 = 0;
			$total_volume_batu1020_250_4 = 0;
			$total_volume_batu2030_250_4 = 0;

			foreach ($komposisi_250_4 as $x){
				$total_volume_semen_250_4 = $x['komposisi_semen_250'];
				$total_volume_pasir_250_4 = $x['komposisi_pasir_250'];
				$total_volume_batu1020_250_4 = $x['komposisi_batu1020_250'];
				$total_volume_batu2030_250_4 = $x['komposisi_batu2030_250'];
			}

			$komposisi_250_2_4 = $this->db->select('(r.vol_produk_d * pk.presentase_a) as komposisi_semen_250_2, (vol_produk_d * pk.presentase_b) as komposisi_pasir_250_2, (vol_produk_d * pk.presentase_c) as komposisi_batu1020_250_2, (vol_produk_d * pk.presentase_d) as komposisi_batu2030_250_2')
			->from('rak r')
			->join('pmm_agregat pk', 'r.komposisi_250_2 = pk.id','left')
			->where("r.tanggal_rencana_kerja between '$date_4_awal' and '$date_4_akhir'")
			->get()->result_array();

			$total_volume_semen_250_2_4 = 0;
			$total_volume_pasir_250_2_4 = 0;
			$total_volume_batu1020_250_2_4 = 0;
			$total_volume_batu2030_250_2_4 = 0;

			foreach ($komposisi_250_2_4 as $x){
				$total_volume_semen_250_2_4 = $x['komposisi_semen_250_2'];
				$total_volume_pasir_250_2_4 = $x['komposisi_pasir_250_2'];
				$total_volume_batu1020_250_2_4 = $x['komposisi_batu1020_250_2'];
				$total_volume_batu2030_250_2_4 = $x['komposisi_batu2030_250_2'];
			}

			$komposisi_300_4 = $this->db->select('(r.vol_produk_e * pk.presentase_a) as komposisi_semen_300_4, (vol_produk_e * pk.presentase_b) as komposisi_pasir_300_4, (vol_produk_e * pk.presentase_c) as komposisi_batu1020_300_4, (vol_produk_e * pk.presentase_d) as komposisi_batu2030_300_4')
			->from('rak r')
			->join('pmm_agregat pk', 'r.komposisi_300 = pk.id','left')
			->where("r.tanggal_rencana_kerja between '$date_4_awal' and '$date_4_akhir'")
			->get()->result_array();

			$total_volume_semen_300_4 = 0;
			$total_volume_pasir_300_4 = 0;
			$total_volume_batu1020_300_4 = 0;
			$total_volume_batu2030_300_4 = 0;

			foreach ($komposisi_300_4 as $x){
				$total_volume_semen_300_4 = $x['komposisi_semen_300'];
				$total_volume_pasir_300_4 = $x['komposisi_pasir_300'];
				$total_volume_batu1020_300_4 = $x['komposisi_batu1020_300'];
				$total_volume_batu2030_300_4 = $x['komposisi_batu2030_300'];
			}

			$total_volume_semen_4 = $total_volume_semen_125_4 + $total_volume_semen_225_4 + $total_volume_semen_250_4 + $total_volume_semen_250_2_4 + $total_volume_semen_300_4;
			$total_volume_pasir_4 = $total_volume_pasir_125_4 + $total_volume_pasir_225_4 + $total_volume_pasir_250_4 + $total_volume_pasir_250_2_4 + $total_volume_pasir_300_4;
			$total_volume_batu1020_4 = $total_volume_batu1020_125_4 + $total_volume_batu1020_225_4 + $total_volume_batu1020_250_4 + $total_volume_batu1020_250_2_4 + $total_volume_batu1020_300_4;
			$total_volume_batu2030_4 = $total_volume_batu2030_125_4 + $total_volume_batu2030_225_4 + $total_volume_batu2030_250_4 + $total_volume_batu2030_250_2_4 + $total_volume_batu2030_300_4;

			//BULAN 5
			$komposisi_125_5 = $this->db->select('(r.vol_produk_a * pk.presentase_a) as komposisi_semen_125, (vol_produk_a * pk.presentase_b) as komposisi_pasir_125, (vol_produk_a * pk.presentase_c) as komposisi_batu1020_125, (vol_produk_a * pk.presentase_d) as komposisi_batu2030_125')
			->from('rak r')
			->join('pmm_agregat pk', 'r.komposisi_125 = pk.id','left')
			->where("r.tanggal_rencana_kerja between '$date_5_awal' and '$date_5_akhir'")
			->get()->result_array();

			$total_volume_semen_125_5 = 0;
			$total_volume_pasir_125_5 = 0;
			$total_volume_batu1020_125_5 = 0;
			$total_volume_batu2030_125_5 = 0;

			foreach ($komposisi_125_5 as $x){
				$total_volume_semen_125_5 = $x['komposisi_semen_125'];
				$total_volume_pasir_125_5 = $x['komposisi_pasir_125'];
				$total_volume_batu1020_125_5 = $x['komposisi_batu1020_125'];
				$total_volume_batu2030_125_5 = $x['komposisi_batu2030_125'];
			}

			$komposisi_225_5 = $this->db->select('(r.vol_produk_b * pk.presentase_a) as komposisi_semen_225, (vol_produk_b * pk.presentase_b) as komposisi_pasir_225, (vol_produk_b * pk.presentase_c) as komposisi_batu1020_225, (vol_produk_b * pk.presentase_d) as komposisi_batu2030_225')
			->from('rak r')
			->join('pmm_agregat pk', 'r.komposisi_225 = pk.id','left')
			->where("r.tanggal_rencana_kerja between '$date_5_awal' and '$date_5_akhir'")
			->get()->result_array();

			$total_volume_semen_225_5 = 0;
			$total_volume_pasir_225_5 = 0;
			$total_volume_batu1020_225_5 = 0;
			$total_volume_batu2030_225_5 = 0;

			foreach ($komposisi_225_5 as $x){
				$total_volume_semen_225_5 = $x['komposisi_semen_225'];
				$total_volume_pasir_225_5 = $x['komposisi_pasir_225'];
				$total_volume_batu1020_225_5 = $x['komposisi_batu1020_225'];
				$total_volume_batu2030_225_5 = $x['komposisi_batu2030_225'];
			}

			$komposisi_250_5 = $this->db->select('(r.vol_produk_c * pk.presentase_a) as komposisi_semen_250, (vol_produk_c * pk.presentase_b) as komposisi_pasir_250, (vol_produk_c * pk.presentase_c) as komposisi_batu1020_250, (vol_produk_c * pk.presentase_d) as komposisi_batu2030_250')
			->from('rak r')
			->join('pmm_agregat pk', 'r.komposisi_250 = pk.id','left')
			->where("r.tanggal_rencana_kerja between '$date_5_awal' and '$date_5_akhir'")
			->get()->result_array();

			$total_volume_semen_250_5 = 0;
			$total_volume_pasir_250_5 = 0;
			$total_volume_batu1020_250_5 = 0;
			$total_volume_batu2030_250_5 = 0;

			foreach ($komposisi_250_5 as $x){
				$total_volume_semen_250_5 = $x['komposisi_semen_250'];
				$total_volume_pasir_250_5 = $x['komposisi_pasir_250'];
				$total_volume_batu1020_250_5 = $x['komposisi_batu1020_250'];
				$total_volume_batu2030_250_5 = $x['komposisi_batu2030_250'];
			}

			$komposisi_250_2_5 = $this->db->select('(r.vol_produk_d * pk.presentase_a) as komposisi_semen_250_2, (vol_produk_d * pk.presentase_b) as komposisi_pasir_250_2, (vol_produk_d * pk.presentase_c) as komposisi_batu1020_250_2, (vol_produk_d * pk.presentase_d) as komposisi_batu2030_250_2')
			->from('rak r')
			->join('pmm_agregat pk', 'r.komposisi_250_2 = pk.id','left')
			->where("r.tanggal_rencana_kerja between '$date_5_awal' and '$date_5_akhir'")
			->get()->result_array();

			$total_volume_semen_250_2_5 = 0;
			$total_volume_pasir_250_2_5 = 0;
			$total_volume_batu1020_250_2_5 = 0;
			$total_volume_batu2030_250_2_5 = 0;

			foreach ($komposisi_250_2_5 as $x){
				$total_volume_semen_250_2_5 = $x['komposisi_semen_250_2'];
				$total_volume_pasir_250_2_5 = $x['komposisi_pasir_250_2'];
				$total_volume_batu1020_250_2_5 = $x['komposisi_batu1020_250_2'];
				$total_volume_batu2030_250_2_5 = $x['komposisi_batu2030_250_2'];
			}

			$komposisi_300_5 = $this->db->select('(r.vol_produk_e * pk.presentase_a) as komposisi_semen_300_5, (vol_produk_e * pk.presentase_b) as komposisi_pasir_300_5, (vol_produk_e * pk.presentase_c) as komposisi_batu1020_300_5, (vol_produk_e * pk.presentase_d) as komposisi_batu2030_300_5')
			->from('rak r')
			->join('pmm_agregat pk', 'r.komposisi_300 = pk.id','left')
			->where("r.tanggal_rencana_kerja between '$date_5_awal' and '$date_5_akhir'")
			->get()->result_array();

			$total_volume_semen_300_5 = 0;
			$total_volume_pasir_300_5 = 0;
			$total_volume_batu1020_300_5 = 0;
			$total_volume_batu2030_300_5 = 0;

			foreach ($komposisi_300_5 as $x){
				$total_volume_semen_300_5 = $x['komposisi_semen_300'];
				$total_volume_pasir_300_5 = $x['komposisi_pasir_300'];
				$total_volume_batu1020_300_5 = $x['komposisi_batu1020_300'];
				$total_volume_batu2030_300_5 = $x['komposisi_batu2030_300'];
			}

			$total_volume_semen_5 = $total_volume_semen_125_5 + $total_volume_semen_225_5 + $total_volume_semen_250_5 + $total_volume_semen_250_2_5 + $total_volume_semen_300_5;
			$total_volume_pasir_5 = $total_volume_pasir_125_5 + $total_volume_pasir_225_5 + $total_volume_pasir_250_5 + $total_volume_pasir_250_2_5 + $total_volume_pasir_300_5;
			$total_volume_batu1020_5 = $total_volume_batu1020_125_5 + $total_volume_batu1020_225_5 + $total_volume_batu1020_250_5 + $total_volume_batu1020_250_2_5 + $total_volume_batu1020_300_5;
			$total_volume_batu2030_5 = $total_volume_batu2030_125_5 + $total_volume_batu2030_225_5 + $total_volume_batu2030_250_5 + $total_volume_batu2030_250_2_5 + $total_volume_batu2030_300_5;

			//BULAN 6
			$komposisi_125_6 = $this->db->select('(r.vol_produk_a * pk.presentase_a) as komposisi_semen_125, (vol_produk_a * pk.presentase_b) as komposisi_pasir_125, (vol_produk_a * pk.presentase_c) as komposisi_batu1020_125, (vol_produk_a * pk.presentase_d) as komposisi_batu2030_125')
			->from('rak r')
			->join('pmm_agregat pk', 'r.komposisi_125 = pk.id','left')
			->where("r.tanggal_rencana_kerja between '$date_6_awal' and '$date_6_akhir'")
			->get()->result_array();

			$total_volume_semen_125_6 = 0;
			$total_volume_pasir_125_6 = 0;
			$total_volume_batu1020_125_6 = 0;
			$total_volume_batu2030_125_6 = 0;

			foreach ($komposisi_125_6 as $x){
				$total_volume_semen_125_6 = $x['komposisi_semen_125'];
				$total_volume_pasir_125_6 = $x['komposisi_pasir_125'];
				$total_volume_batu1020_125_6 = $x['komposisi_batu1020_125'];
				$total_volume_batu2030_125_6 = $x['komposisi_batu2030_125'];
			}

			$komposisi_225_6 = $this->db->select('(r.vol_produk_b * pk.presentase_a) as komposisi_semen_225, (vol_produk_b * pk.presentase_b) as komposisi_pasir_225, (vol_produk_b * pk.presentase_c) as komposisi_batu1020_225, (vol_produk_b * pk.presentase_d) as komposisi_batu2030_225')
			->from('rak r')
			->join('pmm_agregat pk', 'r.komposisi_225 = pk.id','left')
			->where("r.tanggal_rencana_kerja between '$date_6_awal' and '$date_6_akhir'")
			->get()->result_array();

			$total_volume_semen_225_6 = 0;
			$total_volume_pasir_225_6 = 0;
			$total_volume_batu1020_225_6 = 0;
			$total_volume_batu2030_225_6 = 0;

			foreach ($komposisi_225_6 as $x){
				$total_volume_semen_225_6 = $x['komposisi_semen_225'];
				$total_volume_pasir_225_6 = $x['komposisi_pasir_225'];
				$total_volume_batu1020_225_6 = $x['komposisi_batu1020_225'];
				$total_volume_batu2030_225_6 = $x['komposisi_batu2030_225'];
			}

			$komposisi_250_6 = $this->db->select('(r.vol_produk_c * pk.presentase_a) as komposisi_semen_250, (vol_produk_c * pk.presentase_b) as komposisi_pasir_250, (vol_produk_c * pk.presentase_c) as komposisi_batu1020_250, (vol_produk_c * pk.presentase_d) as komposisi_batu2030_250')
			->from('rak r')
			->join('pmm_agregat pk', 'r.komposisi_250 = pk.id','left')
			->where("r.tanggal_rencana_kerja between '$date_6_awal' and '$date_6_akhir'")
			->get()->result_array();

			$total_volume_semen_250_6 = 0;
			$total_volume_pasir_250_6 = 0;
			$total_volume_batu1020_250_6 = 0;
			$total_volume_batu2030_250_6 = 0;

			foreach ($komposisi_250_6 as $x){
				$total_volume_semen_250_6 = $x['komposisi_semen_250'];
				$total_volume_pasir_250_6 = $x['komposisi_pasir_250'];
				$total_volume_batu1020_250_6 = $x['komposisi_batu1020_250'];
				$total_volume_batu2030_250_6 = $x['komposisi_batu2030_250'];
			}

			$komposisi_250_2_6 = $this->db->select('(r.vol_produk_d * pk.presentase_a) as komposisi_semen_250_2, (vol_produk_d * pk.presentase_b) as komposisi_pasir_250_2, (vol_produk_d * pk.presentase_c) as komposisi_batu1020_250_2, (vol_produk_d * pk.presentase_d) as komposisi_batu2030_250_2')
			->from('rak r')
			->join('pmm_agregat pk', 'r.komposisi_250_2 = pk.id','left')
			->where("r.tanggal_rencana_kerja between '$date_6_awal' and '$date_6_akhir'")
			->get()->result_array();

			$total_volume_semen_250_2_6 = 0;
			$total_volume_pasir_250_2_6 = 0;
			$total_volume_batu1020_250_2_6 = 0;
			$total_volume_batu2030_250_2_6 = 0;

			foreach ($komposisi_250_2_6 as $x){
				$total_volume_semen_250_2_6 = $x['komposisi_semen_250_2'];
				$total_volume_pasir_250_2_6 = $x['komposisi_pasir_250_2'];
				$total_volume_batu1020_250_2_6 = $x['komposisi_batu1020_250_2'];
				$total_volume_batu2030_250_2_6 = $x['komposisi_batu2030_250_2'];
			}

			$komposisi_300_6 = $this->db->select('(r.vol_produk_e * pk.presentase_a) as komposisi_semen_300_6, (vol_produk_e * pk.presentase_b) as komposisi_pasir_300_6, (vol_produk_e * pk.presentase_c) as komposisi_batu1020_300_6, (vol_produk_e * pk.presentase_d) as komposisi_batu2030_300_6')
			->from('rak r')
			->join('pmm_agregat pk', 'r.komposisi_300 = pk.id','left')
			->where("r.tanggal_rencana_kerja between '$date_5_awal' and '$date_5_akhir'")
			->get()->result_array();

			$total_volume_semen_300_6 = 0;
			$total_volume_pasir_300_6 = 0;
			$total_volume_batu1020_300_6 = 0;
			$total_volume_batu2030_300_6 = 0;

			foreach ($komposisi_300_6 as $x){
				$total_volume_semen_300_6 = $x['komposisi_semen_300'];
				$total_volume_pasir_300_6 = $x['komposisi_pasir_300'];
				$total_volume_batu1020_300_6 = $x['komposisi_batu1020_300'];
				$total_volume_batu2030_300_6 = $x['komposisi_batu2030_300'];
			}

			$total_volume_semen_6 = $total_volume_semen_125_6 + $total_volume_semen_225_6 + $total_volume_semen_250_6 + $total_volume_semen_250_2_6 + $total_volume_semen_300_6;
			$total_volume_pasir_6 = $total_volume_pasir_125_6 + $total_volume_pasir_225_6 + $total_volume_pasir_250_6 + $total_volume_pasir_250_2_6 + $total_volume_pasir_300_6;
			$total_volume_batu1020_6 = $total_volume_batu1020_125_6 + $total_volume_batu1020_225_6 + $total_volume_batu1020_250_6 + $total_volume_batu1020_250_2_6 + $total_volume_batu1020_300_6;
			$total_volume_batu2030_6 = $total_volume_batu2030_125_6 + $total_volume_batu2030_225_6 + $total_volume_batu2030_250_6 + $total_volume_batu2030_250_2_6 + $total_volume_batu2030_300_6;
			
			//SOLAR
			$rap_solar = $this->db->select('rap.*')
			->from('rap_alat rap')
			->where('rap.status','PUBLISH')
			->order_by('rap.id','desc')->limit(1)
			->get()->row_array();
			
			$total_volume_solar_1 = $total_1_volume * $rap_solar['vol_bbm_solar'];
			$total_volume_solar_2 = $total_2_volume * $rap_solar['vol_bbm_solar'];
			$total_volume_solar_3 = $total_3_volume * $rap_solar['vol_bbm_solar'];
			$total_volume_solar_4 = $total_4_volume * $rap_solar['vol_bbm_solar'];
			$total_volume_solar_5 = $total_5_volume * $rap_solar['vol_bbm_solar'];
			$total_volume_solar_6 = $total_6_volume * $rap_solar['vol_bbm_solar'];
			?>

			<tr class="table-judul">
				<th width="5%" class="text-center">NO.</th>
				<th class="text-center">URAIAN</th>
				<th class="text-center">SATUAN</th>
				<th class="text-center" style="text-transform:uppercase;"><?php echo $date_1_awal = date('F Y');?></th>
				<th class="text-center" style="text-transform:uppercase;"><?php echo $date_2_awal = date('F Y', strtotime('+1 days', strtotime($date_1_akhir)));?></th>
				<th class="text-center" style="text-transform:uppercase;"><?php echo $date_3_awal = date('F Y', strtotime('+1 days', strtotime($date_2_akhir)));?></th>
				<th class="text-center" style="text-transform:uppercase;"><?php echo $date_4_awal = date('F Y', strtotime('+1 days', strtotime($date_3_akhir)));?></th>
				<th class="text-center" style="text-transform:uppercase;"><?php echo $date_5_awal = date('F Y', strtotime('+1 days', strtotime($date_4_akhir)));?></th>
				<th class="text-center" style="text-transform:uppercase;"><?php echo $date_6_awal = date('F Y', strtotime('+1 days', strtotime($date_5_akhir)));?></th>
	        </tr>
			<tr class="table-baris">
				<th class="text-center">1.</th>
				<th class="text-left">Beton K 125 (10±2)</th>
				<th class="text-center">M3</th>
				<th class="text-right"><?php echo number_format($volume_1_produk_a,2,',','.');?></th>
				<th class="text-right"><?php echo number_format($volume_2_produk_a,2,',','.');?></th>
				<th class="text-right"><?php echo number_format($volume_3_produk_a,2,',','.');?></th>
				<th class="text-right"><?php echo number_format($volume_4_produk_a,2,',','.');?></th>
				<th class="text-right"><?php echo number_format($volume_5_produk_a,2,',','.');?></th>
				<th class="text-right"><?php echo number_format($volume_6_produk_a,2,',','.');?></th>
			</tr>
			<tr class="table-baris">
				<th class="text-center">2.</th>
				<th class="text-left">Beton K 225 (10±2)</th>
				<th class="text-center">M3</th>
				<th class="text-right"><?php echo number_format($volume_1_produk_b,2,',','.');?></th>
				<th class="text-right"><?php echo number_format($volume_2_produk_b,2,',','.');?></th>
				<th class="text-right"><?php echo number_format($volume_3_produk_b,2,',','.');?></th>
				<th class="text-right"><?php echo number_format($volume_4_produk_b,2,',','.');?></th>
				<th class="text-right"><?php echo number_format($volume_5_produk_b,2,',','.');?></th>
				<th class="text-right"><?php echo number_format($volume_6_produk_b,2,',','.');?></th>
			</tr>
			<tr class="table-baris">
				<th class="text-center">3.</th>
				<th class="text-left">Beton K 250 (10±2)</th>
				<th class="text-center">M3</th>
				<th class="text-right"><?php echo number_format($volume_1_produk_c,2,',','.');?></th>
				<th class="text-right"><?php echo number_format($volume_2_produk_c,2,',','.');?></th>
				<th class="text-right"><?php echo number_format($volume_3_produk_c,2,',','.');?></th>
				<th class="text-right"><?php echo number_format($volume_4_produk_c,2,',','.');?></th>
				<th class="text-right"><?php echo number_format($volume_5_produk_c,2,',','.');?></th>
				<th class="text-right"><?php echo number_format($volume_6_produk_c,2,',','.');?></th>
			</tr>
			<tr class="table-baris">
				<th class="text-center">4.</th>
				<th class="text-left">Beton K 250 (18±2)</th>
				<th class="text-center">M3</th>
				<th class="text-right"><?php echo number_format($volume_1_produk_d,2,',','.');?></th>
				<th class="text-right"><?php echo number_format($volume_2_produk_d,2,',','.');?></th>
				<th class="text-right"><?php echo number_format($volume_3_produk_d,2,',','.');?></th>
				<th class="text-right"><?php echo number_format($volume_4_produk_d,2,',','.');?></th>
				<th class="text-right"><?php echo number_format($volume_5_produk_d,2,',','.');?></th>
				<th class="text-right"><?php echo number_format($volume_6_produk_d,2,',','.');?></th>
			</tr>
			<tr class="table-baris">
				<th class="text-center">4.</th>
				<th class="text-left">Beton K 300 (10±2)</th>
				<th class="text-center">M3</th>
				<th class="text-right"><?php echo number_format($volume_1_produk_e,2,',','.');?></th>
				<th class="text-right"><?php echo number_format($volume_2_produk_e,2,',','.');?></th>
				<th class="text-right"><?php echo number_format($volume_3_produk_e,2,',','.');?></th>
				<th class="text-right"><?php echo number_format($volume_4_produk_e,2,',','.');?></th>
				<th class="text-right"><?php echo number_format($volume_5_produk_e,2,',','.');?></th>
				<th class="text-right"><?php echo number_format($volume_6_produk_e,2,',','.');?></th>
			</tr>
			<tr class="table-total">
				<th class="text-right" colspan="3">TOTAL VOLUME</th>
				<th class="text-right"><a target="_blank" href="<?= base_url('laporan/cetak_kebutuhan_bahan_alat/'.$rencana_kerja_1['id']) ?>"><?php echo number_format($total_1_volume,2,',','.');?></a></th>
				<th class="text-right"><a target="_blank" href="<?= base_url('laporan/cetak_kebutuhan_bahan_alat/'.$rencana_kerja_2['id']) ?>"><?php echo number_format($total_2_volume,2,',','.');?></a></th>
				<th class="text-right"><a target="_blank" href="<?= base_url('laporan/cetak_kebutuhan_bahan_alat/'.$rencana_kerja_3['id']) ?>"><?php echo number_format($total_3_volume,2,',','.');?></a></th>
				<th class="text-right"><a target="_blank" href="<?= base_url('laporan/cetak_kebutuhan_bahan_alat/'.$rencana_kerja_4['id']) ?>"><?php echo number_format($total_4_volume,2,',','.');?></a></th>
				<th class="text-right"><a target="_blank" href="<?= base_url('laporan/cetak_kebutuhan_bahan_alat/'.$rencana_kerja_5['id']) ?>"><?php echo number_format($total_5_volume,2,',','.');?></a></th>
				<th class="text-right"><a target="_blank" href="<?= base_url('laporan/cetak_kebutuhan_bahan_alat/'.$rencana_kerja_6['id']) ?>"><?php echo number_format($total_6_volume,2,',','.');?></a></th>
			</tr>
			<tr class="table-total">
				<th class="text-right" colspan="3">PENDAPATAN USAHA</th>
				<th class="text-right"><?php echo number_format($nilai_jual_all_1,0,',','.');?></th>
				<th class="text-right"><?php echo number_format($nilai_jual_all_2,0,',','.');?></th>
				<th class="text-right"><?php echo number_format($nilai_jual_all_3,0,',','.');?></th>
				<th class="text-right"><?php echo number_format($nilai_jual_all_4,0,',','.');?></th>
				<th class="text-right"><?php echo number_format($nilai_jual_all_5,0,',','.');?></th>
				<th class="text-right"><?php echo number_format($nilai_jual_all_6,0,',','.');?></th>
			</tr>
			<tr class="table-judul">
				<th width="5%" class="text-center" style="vertical-align:middle">NO.</th>
				<th class="text-center" style="vertical-align:middle">KEBUTUHAN BAHAN</th>
				<th class="text-center" style="vertical-align:middle">SATUAN</th>
				<th class="text-center" style="vertical-align:middle">PENGADAAN</th>
				<th class="text-center" style="vertical-align:middle">PENGADAAN</th>
				<th class="text-center" style="vertical-align:middle">PENGADAAN</th>
				<th class="text-center" style="vertical-align:middle">PENGADAAN</th>
				<th class="text-center" style="vertical-align:middle">PENGADAAN</th>
				<th class="text-center" style="vertical-align:middle">PENGADAAN</th>
	        </tr>
			<tr class="table-baris">
				<th class="text-center">1.</th>
				<th class="text-right">Semen</th>
				<th class="text-center">Ton</th>
				<th class="text-right"><a target="_blank" href="<?= site_url('laporan/pesanan_pembelian/'.$rencana_kerja_1['penawaran_id_semen'].'/'.$date_1_awal = date('Y-m-d',strtotime($date_1_awal)).'/'.$date_1_akhir = date('Y-m-d',strtotime($date_1_akhir)).'').'/'.$kebutuhan = $total_volume_semen_1.'/'.$material_id = 4 ?>"><?php echo number_format($total_volume_semen_1,2,',','.');?></a></th>
				<th class="text-right"><?php echo number_format($total_volume_semen_2,2,',','.');?></th>
				<th class="text-right"><?php echo number_format($total_volume_semen_3,2,',','.');?></th>
				<th class="text-right"><?php echo number_format($total_volume_semen_4,2,',','.');?></th>
				<th class="text-right"><?php echo number_format($total_volume_semen_5,2,',','.');?></th>
				<th class="text-right"><?php echo number_format($total_volume_semen_6,2,',','.');?></th>
			</tr>
			<tr class="table-baris">
				<th class="text-center">2.</th>
				<th class="text-right">Pasir</th>
				<th class="text-center">M3</th>
				<th class="text-right"><a target="_blank" href="<?= site_url('laporan/pesanan_pembelian/'.$rencana_kerja_1['penawaran_id_pasir'].'/'.$date_1_awal = date('Y-m-d',strtotime('-2 days -1 months', strtotime($date_1_akhir))).'/'.$date_1_akhir = date('Y-m-d',strtotime($date_1_akhir)).'').'/'.$kebutuhan = $total_volume_pasir_1.'/'.$material_id = 5 ?>"><?php echo number_format($total_volume_pasir_1,2,',','.');?></a></th>
				<th class="text-right"><?php echo number_format($total_volume_pasir_2,2,',','.');?></th>
				<th class="text-right"><?php echo number_format($total_volume_pasir_3,2,',','.');?></th>
				<th class="text-right"><?php echo number_format($total_volume_pasir_4,2,',','.');?></th>
				<th class="text-right"><?php echo number_format($total_volume_pasir_5,2,',','.');?></th>
				<th class="text-right"><?php echo number_format($total_volume_pasir_6,2,',','.');?></th>
			</tr>
			<tr class="table-baris">
				<th class="text-center">3.</th>
				<th class="text-right">Batu Split 10-20</th>
				<th class="text-center">M3</th>
				<th class="text-right"><a target="_blank" href="<?= site_url('laporan/pesanan_pembelian/'.$rencana_kerja_1['penawaran_id_batu1020'].'/'.$date_1_awal = date('Y-m-d',strtotime('-2 days -1 months', strtotime($date_1_akhir))).'/'.$date_1_akhir = date('Y-m-d',strtotime($date_1_akhir)).'').'/'.$kebutuhan = $total_volume_batu1020_1.'/'.$material_id = 6 ?>"><?php echo number_format($total_volume_batu1020_1,2,',','.');?></a></th>
				<th class="text-right"><?php echo number_format($total_volume_batu1020_2,2,',','.');?></th>
				<th class="text-right"><?php echo number_format($total_volume_batu1020_3,2,',','.');?></th>
				<th class="text-right"><?php echo number_format($total_volume_batu1020_4,2,',','.');?></th>
				<th class="text-right"><?php echo number_format($total_volume_batu1020_5,2,',','.');?></th>
				<th class="text-right"><?php echo number_format($total_volume_batu1020_6,2,',','.');?></th>
			</tr>
			<tr class="table-baris">
				<th class="text-center">4.</th>
				<th class="text-right">Batu Split 20-30</th>
				<th class="text-center">M3</th>
				<th class="text-right"><a target="_blank" href="<?= site_url('laporan/pesanan_pembelian/'.$rencana_kerja_1['penawaran_id_batu2030'].'/'.$date_1_awal = date('Y-m-d',strtotime('-2 days -1 months', strtotime($date_1_akhir))).'/'.$date_1_akhir = date('Y-m-d',strtotime($date_1_akhir)).'').'/'.$kebutuhan = $total_volume_batu2030_1.'/'.$material_id = 7 ?>"><?php echo number_format($total_volume_batu2030_1,2,',','.');?></a></th>
				<th class="text-right"><?php echo number_format($total_volume_batu2030_2,2,',','.');?></th>
				<th class="text-right"><?php echo number_format($total_volume_batu2030_3,2,',','.');?></th>
				<th class="text-right"><?php echo number_format($total_volume_batu2030_4,2,',','.');?></th>
				<th class="text-right"><?php echo number_format($total_volume_batu2030_5,2,',','.');?></th>
				<th class="text-right"><?php echo number_format($total_volume_batu2030_6,2,',','.');?></th>
			</tr>
			<tr class="table-baris">
				<th class="text-center">5.</th>
				<th class="text-right">Solar</th>
				<th class="text-center">Liter</th>
				<th class="text-right"><a target="_blank" href="<?= site_url('laporan/pesanan_pembelian/'.$rencana_kerja_1['penawaran_id_solar'].'/'.$date_1_awal = date('Y-m-d',strtotime('-2 days -1 months', strtotime($date_1_akhir))).'/'.$date_1_akhir = date('Y-m-d',strtotime($date_1_akhir)).'').'/'.$kebutuhan = $total_volume_solar_1.'/'.$material_id = 8 ?>"><?php echo number_format($total_volume_solar_1,2,',','.');?></a></th>
				<th class="text-right"><?php echo number_format($total_volume_solar_2,2,',','.');?></th>
				<th class="text-right"><?php echo number_format($total_volume_solar_3,2,',','.');?></th>
				<th class="text-right"><?php echo number_format($total_volume_solar_4,2,',','.');?></th>
				<th class="text-right"><?php echo number_format($total_volume_solar_5,2,',','.');?></th>
				<th class="text-right"><?php echo number_format($total_volume_solar_6,2,',','.');?></th>
			</tr>
	    </table>
		<?php
	}

	public function detail_notification()
    {
        $check = $this->m_admin->check_login();
        if($check == true){

            $this->db->select('ppo.*');
			$this->db->where("ppo.status = 'WAITING'");
			$this->db->group_by('ppo.id');
			$this->db->order_by('ppo.created_on','desc');
            $query = $this->db->get('pmm_purchase_order ppo');
            $data['row'] = $query->result_array();
            $this->load->view('admin/detail_notification',$data);
            
        }else {
            redirect('admin');
        }
    }

	public function detail_notification_2()
    {
        $check = $this->m_admin->check_login();
        if($check == true){
			
            $this->load->view('admin/detail_notification_2',$data);
            
        }else {
            redirect('admin');
        }
    }

	public function buku_besar($arr_date)
	{
		$data = array();
		
		$arr_date = $this->input->post('filter_date');
		$arr_filter_date = explode(' - ', $arr_date);
		$date1 = '';
		$date2 = '';
		$date3 = '';
		$date4 = '';

		if(count($arr_filter_date) == 2){
			$date_now = date('Y-m-d',strtotime($arr_filter_date[0]));
			$date_now = date('Y-m-d', strtotime('-1 days -0 months ', strtotime($date_now)));
			$date4 	= date('Y-m-d',strtotime($date_now));
			$date_now2 = date('2023-01-01');
			$date3 	= date('Y-m-d',strtotime($date_now2));
			$date1 	= date('Y-m-d',strtotime($arr_filter_date[0]));
			$date2 	= date('Y-m-d',strtotime($arr_filter_date[1]));
			$filter_date = date('d/m/Y',strtotime($arr_filter_date[0])).' - '.date('d/m/Y',strtotime($arr_filter_date[1]));
			$filter_date_2 = date('Y-m-d',strtotime($date3)).' - '.date('Y-m-d',strtotime($arr_filter_date[1]));
		}
		
		?>
		
		<table class="table table-bordered" width="100%">
		 <style type="text/css">
			body {
				font-family: helvetica;
				font-size: 11px;
			}

			table tr.table-active{
				background: linear-gradient(90deg, #fdcd3b 20%, #fdcd3b 40%, #e69500 80%);
				font-size: 11px;
				font-weight: bold;
			}
				
			table tr.table-active2{
				background-color: #e69500;
				font-size: 11px;
				font-weight: bold;
				color: white;
			}
				
			table tr.table-active3{
				font-size: 11px;
			}
				
			table tr.table-active4{
				background: linear-gradient(90deg, #eeeeee 5%, #cccccc 50%, #cccccc 100%);
				font-weight: bold;
				font-size: 11px;
				color: black;
			}

			.spoiler{
			display:none
			}

			.show{
			display:block
			}

			th{
			padding: 5px;
			}
		 </style>
		 <script>
			function myFunction() {
			var x = document.getElementById("myDIV");
			if (x.style.display === "none") {
				x.style.display = "block";
			} else {
				x.style.display = "none";
			}
			}

			function myFunction2() {
			var x = document.getElementById("myDIV2");
			if (x.style.display === "none") {
				x.style.display = "block";
			} else {
				x.style.display = "none";
			}
			}

			function myFunction3() {
			var x = document.getElementById("myDIV3");
			if (x.style.display === "none") {
				x.style.display = "block";
			} else {
				x.style.display = "none";
			}
			}

			function myFunction4() {
			var x = document.getElementById("myDIV4");
			if (x.style.display === "none") {
				x.style.display = "block";
			} else {
				x.style.display = "none";
			}
			}

			function myFunction5() {
			var x = document.getElementById("myDIV5");
			if (x.style.display === "none") {
				x.style.display = "block";
			} else {
				x.style.display = "none";
			}
			}

			function myFunction6() {
			var x = document.getElementById("myDIV6");
			if (x.style.display === "none") {
				x.style.display = "block";
			} else {
				x.style.display = "none";
			}
			}
		 </script>
	        <tr class="table-active2">
	            <th colspan="3">PERIODE</th>
				<th class="text-center" colspan="4"><?php echo $filter_date = $filter_date = date('d/m/Y',strtotime($arr_filter_date[0])).' - '.date('d/m/Y',strtotime($arr_filter_date[1]));?></th>
	        </tr>

			<tr class="table-active">
	            <th width="100%" class="text-left" colspan="7">BUKU BESAR</th>
	        </tr>
			<tr class="table-active3">
				<th class="text-left" colspan="7">
					<table width="100% "border="1">
						<tr style="background-color: #cccccc;">
							<th class="text-left" width="10%">NAMA AKUN / TGL</th>
							<th class="text-left" width="10%">TRANSAKSI</th>
							<th class="text-left" width="20%">NO.</th>
							<th class="text-left" width="30%">DESKRIPSI</th>
							<th class="text-right" width="10%">DEBIT</th>
							<th class="text-right" width="10%">KREDIT</th>
							<th class="text-right" width="10%">SALDO</th>
						</tr>
					</table>
					<br />
					<div>
						<?php
						$akun_110001_biaya_lalu = $this->db->select('sum(pdb.jumlah) as kredit')
						->from('pmm_biaya b')
						->join('pmm_detail_biaya pdb', 'b.id = pdb.biaya_id','left')
						->where("b.tanggal_transaksi between '$date3' and '$date4'")
						->where("b.bayar_dari = 1")
						->get()->row_array();

						$akun_110001_jurnal_lalu = $this->db->select('sum(pdj.debit), sum(pdj.kredit)')
						->from('pmm_jurnal_umum j')
						->join('pmm_detail_jurnal pdj','j.id = pdj.jurnal_id','left')
						->where("j.tanggal_transaksi between '$date3' and '$date4'")
						->where("pdj.akun = 1")
						->get()->row_array();

						$terima_uang_lalu = $this->db->select('sum(jumlah) as debit')
						->from('pmm_terima_uang')
						->where("tanggal_transaksi between '$date3' and '$date4'")
						->where("setor_ke = 1")
						->get()->row_array();
						$akun_110001_lalu = ($terima_uang_lalu['debit'] + $akun_110001_jurnal_lalu['debit']) - ($akun_110001_biaya_lalu['kredit'] + $akun_110001_jurnal_lalu['kredit']);

						$akun_110001_biaya = $this->db->select('b.*, pdb.deskripsi, pdb.jumlah as kredit')
						->from('pmm_biaya b')
						->join('pmm_detail_biaya pdb', 'b.id = pdb.biaya_id','left')
						->where("b.tanggal_transaksi between '$date1' and '$date2'")
						->where("b.bayar_dari = 1")
						->group_by('pdb.id')
						->order_by('b.tanggal_transaksi','asc')
						->order_by('b.created_on','asc')
						->get()->result_array();

						$akun_110001_jurnal = $this->db->select('j.*,pdj.deskripsi, pdj.debit, pdj.kredit')
						->from('pmm_jurnal_umum j')
						->join('pmm_detail_jurnal pdj','j.id = pdj.jurnal_id','left')
						->where("j.tanggal_transaksi between '$date1' and '$date2'")
						->where("pdj.akun = 1")
						->group_by('pdj.id')
						->order_by('j.tanggal_transaksi','asc')
						->order_by('j.created_on','asc')
						->get()->result_array();

						$terima_uang = $this->db->select('*, memo as deskripsi, jumlah as debit')
						->from('pmm_terima_uang')
						->where("tanggal_transaksi between '$date1' and '$date2'")
						->where("setor_ke = 1")
						->group_by('id')
						->order_by('tanggal_transaksi','asc')
						->order_by('created_on','asc')
						->get()->result_array();

						$akun_110001 = array_merge($akun_110001_biaya,$akun_110001_jurnal,$terima_uang);

						function sortByOrder($akun_110001_biaya, $akun_110001_jurnal) {
							if ($akun_110001_biaya['tanggal_transaksi'] > $akun_110001_jurnal['tanggal_transaksi']) {
								return 1;
							} elseif ($akun_110001_biaya['tanggal_transaksi'] < $akun_110001_jurnal['tanggal_transaksi']) {
								return -1;
							}
							return 0;
						}
						
						usort($akun_110001, 'sortByOrder');
						
						?>
						<button onclick="myFunction()" class="btn btn-info"><b>(1-10001) Kas<b></button>
						<div id="myDIV" style="display:none;">
							<table width="100% "border="1">
								<tr>
									<th class="text-left" colspan="6" width="90%">Saldo Awal</th>
									<?php
									$styleColor = $akun_110001_lalu < 0 ? 'color:red' : 'color:black';
									?>
									<th class="text-right" style="<?php echo $styleColor ?>"><?php echo $akun_110001_lalu < 0 ? "(".number_format(-$akun_110001_lalu,0,',','.').")" : number_format($akun_110001_lalu,0,',','.');?></th>
								</tr>
							</table>
							<?php
							$saldo = $akun_110001_lalu;
							$total_debit = 0;
							$total_kredit = 0;
							foreach ($akun_110001 as $x): 
							if ($x['debit']==0) { $saldo = $saldo + $x['debit'] - $x['kredit'] ;} else
							{$saldo = $saldo + $x['debit'];}

							$total_debit += $x['debit'];
							$total_kredit += $x['kredit'];
							$total_saldo = $total_debit - $total_kredit;
							?>
							<table width="100% "border="1">
								<tr>
									<th width="10%" class="text-left"><?php echo $x['tanggal_transaksi'];?></th>
									<th width="10%" class="text-left"><?php echo $x['transaksi'];?></th>
									<th width="20%" class="text-left"><?php echo $x['nomor_transaksi'];?></th>
									<th width="30%" class="text-left"><?php echo $x['deskripsi'];?></th>
									<th width="10%" class="text-right"><?php echo number_format($x['debit'],0,',','.');?></th>
									<th width="10%" class="text-right"><?php echo number_format($x['kredit'],0,',','.');?></th>
									<?php
									$styleColor = $saldo < 0 ? 'color:red' : 'color:black';
									?>
									<th width="10%" class="text-right" style="<?php echo $styleColor ?>"><?php echo $saldo < 0 ? "(".number_format(-$saldo,0,',','.').")" : number_format($saldo,0,',','.');?></th>
								</tr>
								<?php endforeach; ?>
							</table>
						</div>
						<div>
							<table width="100% "border="0">
								<tr>
									<th class="text-right" width="70%">(1-10001) Kas | Saldo Akhir</th>
									<th class="text-right" width="10%"><?php echo number_format($akun_110001_lalu + $total_debit,0,',','.');?></th>
									<th class="text-right" width="10%"><?php echo number_format($total_kredit,0,',','.');?></th>
									<?php
									$saldo_110001 = ($akun_110001_lalu + $total_debit) - $total_kredit;
									$styleColor = $saldo_110001 < 0 ? 'color:red' : 'color:black';
									?>
									<th class="text-right" width="10%" style="<?php echo $styleColor ?>"><?php echo $saldo_110001 < 0 ? "(".number_format(-$saldo_110001,0,',','.').")" : number_format($saldo_110001,0,',','.');?></th>
								</tr>
							</table>
						</div>
					</div>
					<br />
					<div>
						<?php
						$akun_110100_lalu = $this->db->select('ppp.total as debit,pm.total as kredit')
						->from('pmm_penagihan_penjualan ppp')
						->join('pmm_pembayaran pm', 'ppp.id = pm.penagihan_id','left')
						->where("ppp.tanggal_invoice between '$date3' and '$date4'")
						->get()->row_array();
						$akun_110100_lalu = $akun_110100_lalu['debit'] - $akun_110100_lalu['kredit'];

						$akun_110100 = $this->db->select('ppp.tanggal_invoice as tanggal_transaksi,ppp.nomor_invoice as nomor_transaksi,ppp.nomor_kontrak as deskripsi,ppp.total as debit,pm.total as kredit')
						->from('pmm_penagihan_penjualan ppp')
						->join('pmm_pembayaran pm', 'ppp.id = pm.penagihan_id','left')
						->where("ppp.tanggal_invoice between '$date1' and '$date2'")
						->group_by('ppp.id')
						->get()->result_array();
						?>
						<button onclick="myFunction2()" class="btn btn-info"><b>(1-10100) Piutang Usaha<b></button>
						<div id="myDIV2" style="display:none;">
							<table width="100% "border="1">
								<tr>
									<th class="text-left" colspan="6" width="90%">Saldo Awal</th>
									<?php
									$styleColor = $akun_110100_lalu < 0 ? 'color:red' : 'color:black';
									?>
									<th class="text-right" style="<?php echo $styleColor ?>"><?php echo $akun_110100_lalu < 0 ? "(".number_format(-$akun_110100_lalu,0,',','.').")" : number_format($akun_110100_lalu,0,',','.');?></th>
								</tr>
							</table>
							<?php
							$saldo = $akun_110100_lalu;
							$total_debit = 0;
							$total_kredit = 0;
							foreach ($akun_110100 as $x): 
							if ($x['debit']==0) { $saldo = $saldo + $x['debit'] - $x['kredit'] ;} else
							{$saldo = $saldo + $x['debit'];}

							$total_debit += $x['debit'];
							$total_kredit += $x['kredit'];
							$total_saldo = $total_debit - $total_kredit;
							 
							?>
							<table width="100% "border="1">
								<tr>
									<th width="10%" class="text-left"><?php echo $x['tanggal_transaksi'];?></th>
									<th width="10%" class="text-left">Receive Payment</th>
									<th width="20%" class="text-left"><?php echo $x['nomor_transaksi'];?></th>
									<th width="30%" class="text-left">Sales Invoice #<?php echo $x['deskripsi'];?></th>
									<th width="10%" class="text-right"><?php echo number_format($x['debit'],0,',','.');?></th>
									<th width="10%" class="text-right"><?php echo number_format($x['kredit'],0,',','.');?></th>
									<?php
									$styleColor = $saldo < 0 ? 'color:red' : 'color:black';
									?>
									<th width="10%" class="text-right" style="<?php echo $styleColor ?>"><?php echo $saldo < 0 ? "(".number_format(-$saldo,0,',','.').")" : number_format($saldo,0,',','.');?></th>
								</tr>
							</table>
							<?php endforeach; ?>
						</div>
						<div>
							<table width="100% "border="0">
								<tr>
									<th class="text-right" width="70%">(1-10100) Piutang Usaha | Saldo Akhir</th>
									<th class="text-right" width="10%"><?php echo number_format($akun_110100_lalu + $total_debit,0,',','.');?></th>
									<th class="text-right" width="10%"><?php echo number_format($total_kredit,0,',','.');?></th>
									<?php
									$saldo_110100 = ($akun_110100_lalu + $total_debit) - $total_kredit;
									$styleColor = $saldo_110100 < 0 ? 'color:red' : 'color:black';
									?>
									<th class="text-right" width="10%" style="<?php echo $styleColor ?>"><?php echo $saldo_110100 < 0 ? "(".number_format(-$saldo_110100,0,',','.').")" : number_format($saldo_110100,0,',','.');?></th>
								</tr>
							</table>
						</div>
					</div>
					<br />
					<div>
						<?php
						$akun_110101_lalu = $this->db->select('ppp.total as debit,pm.total as kredit')
						->from('pmm_penagihan_penjualan ppp')
						->join('pmm_pembayaran pm', 'ppp.id = pm.penagihan_id','left')
						->where("ppp.tanggal_invoice between '$date3' and '$date4'")
						->get()->row_array();
						$akun_110101_lalu = $akun_110101_lalu['debit'] - $akun_110101_lalu['kredit'];

						$akun_110101 = $this->db->select('pp.date_production as tanggal_transaksi,pp.no_production as nomor_transaksi,ppo.contract_number as deskripsi,pp.display_price as debit')
						->from('pmm_productions pp')
						->join('pmm_sales_po ppo', 'pp.salesPo_id = ppo.id','left')
						->where("pp.date_production between '$date1' and '$date2'")
						->where("pp.status = 'PUBLISH'")
						->where("pp.status_payment = 'UNCREATED'")
						->where("ppo.status in ('OPEN','CLOSED')")
						->group_by('pp.id')
						->get()->result_array();
						?>
						<button onclick="myFunction3()" class="btn btn-info"><b>(1-10101) Piutang Belum Ditagih<b></button>
						<div id="myDIV3" style="display:none;">
							<table width="100% "border="1">
								<tr>
									<th class="text-left" colspan="6" width="90%">Saldo Awal</th>
									<?php
									$styleColor = $akun_110101_lalu < 0 ? 'color:red' : 'color:black';
									?>
									<th class="text-right" style="<?php echo $styleColor ?>"><?php echo $akun_110101_lalu < 0 ? "(".number_format(-$akun_110101_lalu,0,',','.').")" : number_format($akun_110101_lalu,0,',','.');?></th>
								</tr>
							</table>
							<?php
							$saldo = $akun_110101_lalu;
							$total_debit = 0;
							$total_kredit = 0;
							foreach ($akun_110101 as $x): 
							if ($x['debit']==0) { $saldo = $saldo + $x['debit'] - $x['kredit'] ;} else
							{$saldo = $saldo + $x['debit'];}

							$total_debit += $x['debit'];
							$total_kredit += $x['kredit'];
							$total_saldo = $total_debit - $total_kredit;
							 
							?>
							<table width="100% "border="1">
								<tr>
									<th width="10%" class="text-left"><?php echo $x['tanggal_transaksi'];?></th>
									<th width="10%" class="text-left">Sales Delivery</th>
									<th width="20%" class="text-left"><?php echo $x['nomor_transaksi'];?></th>
									<th width="30%" class="text-left">Sales Order #<?php echo $x['deskripsi'];?></th>
									<th width="10%" class="text-right"><?php echo number_format($x['debit'],0,',','.');?></th>
									<th width="10%" class="text-right"><?php echo number_format($x['kredit'],0,',','.');?></th>
									<?php
									$styleColor = $saldo < 0 ? 'color:red' : 'color:black';
									?>
									<th width="10%" class="text-right" style="<?php echo $styleColor ?>"><?php echo $saldo < 0 ? "(".number_format(-$saldo,0,',','.').")" : number_format($saldo,0,',','.');?></th>
								</tr>
							</table>
							<?php endforeach; ?>
						</div>
						<div>
							<table width="100% "border="0">
								<tr>
									<th class="text-right" width="70%">(1-10101) Piutang Belum Ditagih | Saldo Akhir</th>
									<th class="text-right" width="10%"><?php echo number_format($akun_110101_lalu + $total_debit,0,',','.');?></th>
									<th class="text-right" width="10%"><?php echo number_format($total_kredit,0,',','.');?></th>
									<?php
									$saldo_110101 = ($akun_110101_lalu + $total_debit) - $total_kredit;
									$styleColor = $saldo_110101 < 0 ? 'color:red' : 'color:black';
									?>
									<th class="text-right" width="10%" style="<?php echo $styleColor ?>"><?php echo $saldo_110101 < 0 ? "(".number_format(-$saldo_110101,0,',','.').")" : number_format($saldo_110101,0,',','.');?></th>
								</tr>
							</table>
						</div>
					</div>
					<br />
					<div>
					<?php
						$pembelian_semen = $this->db->select('prm.display_measure as satuan, SUM(prm.display_volume) as volume, (prm.display_price / prm.display_volume) as harga, SUM(prm.display_price) as nilai')
						->from('pmm_receipt_material prm')
						->join('pmm_purchase_order po', 'prm.purchase_order_id = po.id','left')
						->join('produk p', 'prm.material_id = p.id','left')
						->where("prm.date_receipt between '$date1' and '$date2'")
						->where("p.kategori_bahan = 1")
						->get()->row_array();

						$pembelian_pasir = $this->db->select('prm.display_measure as satuan, SUM(prm.display_volume) as volume, (prm.display_price / prm.display_volume) as harga, SUM(prm.display_price) as nilai')
						->from('pmm_receipt_material prm')
						->join('pmm_purchase_order po', 'prm.purchase_order_id = po.id','left')
						->join('produk p', 'prm.material_id = p.id','left')
						->where("prm.date_receipt between '$date1' and '$date2'")
						->where("p.kategori_bahan = 2")
						->get()->row_array();

						$pembelian_1020 = $this->db->select('prm.display_measure as satuan, SUM(prm.display_volume) as volume, (prm.display_price / prm.display_volume) as harga, SUM(prm.display_price) as nilai')
						->from('pmm_receipt_material prm')
						->join('pmm_purchase_order po', 'prm.purchase_order_id = po.id','left')
						->join('produk p', 'prm.material_id = p.id','left')
						->where("prm.date_receipt between '$date1' and '$date2'")
						->where("p.kategori_bahan = 3")
						->get()->row_array();

						$pembelian_2030 = $this->db->select('prm.display_measure as satuan, SUM(prm.display_volume) as volume, (prm.display_price / prm.display_volume) as harga, SUM(prm.display_price) as nilai')
						->from('pmm_receipt_material prm')
						->join('pmm_purchase_order po', 'prm.purchase_order_id = po.id','left')
						->join('produk p', 'prm.material_id = p.id','left')
						->where("prm.date_receipt between '$date1' and '$date2'")
						->where("p.kategori_bahan = 4")
						->get()->row_array();

						$pembelian_additive = $this->db->select('prm.display_measure as satuan, SUM(prm.display_volume) as volume, (prm.display_price / prm.display_volume) as harga, SUM(prm.display_price) as nilai')
						->from('pmm_receipt_material prm')
						->join('pmm_purchase_order po', 'prm.purchase_order_id = po.id','left')
						->join('produk p', 'prm.material_id = p.id','left')
						->where("prm.date_receipt between '$date1' and '$date2'")
						->where("p.kategori_bahan = 6")
						->get()->row_array();
						$akun_110201_pembelian = $pembelian_semen['nilai'] + $pembelian_pasir['nilai'] + $pembelian_1020['nilai'] + $pembelian_2030['nilai'] + $pembelian_additive['nilai'];

						$stock_opname = $this->db->select('date')
						->from('pmm_remaining_materials_cat')
						->where("date between '$date1' and '$date2'")
						->where("material_id = 1")
						->order_by('id','desc')->limit(1)
						->get()->row_array();
						
						$stock_opname_semen = $this->db->select('cat.*, (cat.total) as nilai')
						->from('pmm_remaining_materials_cat cat')
						->where("cat.date between '$date1' and '$date2'")
						->where("cat.material_id = 1")
						->where("cat.status = 'PUBLISH'")
						->group_by('cat.id')
						->order_by('cat.id','desc')->limit(1)
						->get()->result_array();
						$nilai_semen = 0;
						foreach ($stock_opname_semen as $x){
							$nilai_semen += $x['nilai'];
						}
				
						$stock_opname_pasir = $this->db->select('cat.*, (cat.total) as nilai')
						->from('pmm_remaining_materials_cat cat')
						->where("cat.date between '$date1' and '$date2'")
						->where("cat.material_id = 2")
						->where("cat.status = 'PUBLISH'")
						->group_by('cat.id')
						->order_by('cat.id','desc')->limit(1)
						->get()->result_array();
						$nilai_pasir = 0;
						foreach ($stock_opname_pasir as $x){
							$nilai_pasir += $x['nilai'];
						}
				
						$stock_opname_batu1020 = $this->db->select('cat.*, (cat.total) as nilai')
						->from('pmm_remaining_materials_cat cat')
						->where("cat.date between '$date1' and '$date2'")
						->where("cat.material_id = 3")
						->where("cat.status = 'PUBLISH'")
						->group_by('cat.id')
						->order_by('cat.id','desc')->limit(1)
						->get()->result_array();
						$nilai_batu1020 = 0;
						foreach ($stock_opname_batu1020 as $x){
							$nilai_batu1020 += $x['nilai'];
						}
				
						$stock_opname_batu2030 = $this->db->select('cat.*, (cat.total) as nilai')
						->from('pmm_remaining_materials_cat cat')
						->where("cat.date between '$date1' and '$date2'")
						->where("cat.material_id = 4")
						->where("cat.status = 'PUBLISH'")
						->group_by('cat.id')
						->order_by('cat.id','desc')->limit(1)
						->get()->result_array();
						$nilai_batu2030 = 0;
						foreach ($stock_opname_batu2030 as $x){
							$nilai_batu2030 += $x['nilai'];
						}
				
						$stock_opname_solar = $this->db->select('cat.*, (cat.total) as nilai')
						->from('pmm_remaining_materials_cat cat')
						->where("cat.date between '$date1' and '$date2'")
						->where("cat.material_id = 5")
						->where("cat.status = 'PUBLISH'")
						->group_by('cat.id')
						->order_by('cat.id','desc')->limit(1)
						->get()->result_array();
						$nilai_solar = 0;
						foreach ($stock_opname_solar as $x){
							$nilai_solar += $x['nilai'];
						}
				
						$stock_opname_additive = $this->db->select('cat.*, (cat.total) as nilai')
						->from('pmm_remaining_materials_cat cat')
						->where("cat.date between '$date1' and '$date2'")
						->where("cat.material_id = 19")
						->where("cat.status = 'PUBLISH'")
						->group_by('cat.id')
						->order_by('cat.id','desc')->limit(1)
						->get()->result_array();
						$nilai_additive = 0;
						foreach ($stock_opname_additive as $x){
							$nilai_additive += $x['nilai'];
						}
						
						$akun_110201_stock_opname = $nilai_semen + $nilai_pasir + $nilai_batu1020 + $nilai_batu2030 + $nilai_solar + $nilai_additive;
						
						$akun_110201_pemakaian = $akun_110201_pembelian - $akun_110201_stock_opname;
						?>
						<button onclick="myFunction4()" class="btn btn-info"><b>(1-10201) Persediaan Bahan Baku<b></button>
						<div id="myDIV4" style="display:none;">
							<table width="100% "border="1">
								<tr>
									<th class="text-left" colspan="6" width="90%">Saldo Awal</th>
									<?php
									$styleColor = $akun_110201_lalu < 0 ? 'color:red' : 'color:black';
									?>
									<th class="text-right" style="<?php echo $styleColor ?>"><?php echo $akun_110201_lalu < 0 ? "(".number_format(-$akun_110201_lalu,0,',','.').")" : number_format($akun_110201_lalu,0,',','.');?></th>
								</tr>
							</table>
							<table width="100% "border="1">
								<tr>
									<th width="10%" class="text-left"><?php echo $stock_opname['date'];?></th>
									<th width="10%" class="text-left">Stock Opname</th>
									<th width="20%" class="text-left"></th>
									<th width="30%" class="text-left">Stock Opname Terakhir (<?php echo $stock_opname['date'];?>)</th>
									<th width="10%" class="text-right"><?php echo number_format($akun_110201_pembelian,0,',','.');?></th>
									<th width="10%" class="text-right"><?php echo number_format($akun_110201_pemakaian,0,',','.');?></th>
									<?php
									$saldo = $akun_110201_stock_opname;
									$styleColor = $saldo < 0 ? 'color:red' : 'color:black';
									?>
									<th width="10%" class="text-right" style="<?php echo $styleColor ?>"><?php echo $saldo < 0 ? "(".number_format(-$saldo,0,',','.').")" : number_format($saldo,0,',','.');?></th>
								</tr>
							</table>
						</div>
						<div>
							<table width="100% "border="0">
								<tr>
									<th class="text-right" width="70%">(1-10201) Persediaan Bahan Baku | Saldo Akhir</th>
									<th class="text-right" width="10%"><?php echo number_format($akun_110201_pembelian,0,',','.');?></th>
									<th class="text-right" width="10%"><?php echo number_format($akun_110201_pemakaian,0,',','.');?></th>
									<?php
									$saldo_110201 = $saldo;
									$styleColor = $saldo_110201 < 0 ? 'color:red' : 'color:black';
									?>
									<th class="text-right" width="10%" style="<?php echo $styleColor ?>"><?php echo $saldo_110201 < 0 ? "(".number_format(-$saldo_110201,0,',','.').")" : number_format($saldo_110201,0,',','.');?></th>
								</tr>
							</table>
						</div>
					</div>
					<br />
					<div>
						<?php
						$akun_110403_lalu =$this->db->select('sum(ppp.uang_muka) as debit')
						->from('pmm_penagihan_pembelian ppp')
						->join('pmm_penagihan_pembelian_detail ppd','ppp.id = ppd.penagihan_pembelian_id','left')
						->join('pmm_purchase_order ppo', 'ppp.purchase_order_id = ppo.id','left')
						->where("ppp.tanggal_invoice between '$date3' and '$date4'")
						->get()->row_array();
						$akun_110403_lalu = $akun_110403_lalu['debit'];

						$akun_110403 = $this->db->select('ppp.tanggal_invoice as tanggal_transaksi,ppp.nomor_invoice as nomor_transaksi,ppo.no_po as deskripsi,ppp.uang_muka as debit')
						->from('pmm_penagihan_pembelian ppp')
						->join('pmm_penagihan_pembelian_detail ppd','ppp.id = ppd.penagihan_pembelian_id','left')
						->join('pmm_purchase_order ppo', 'ppp.purchase_order_id = ppo.id','left')
						->where("ppp.tanggal_invoice between '$date1' and '$date2'")
						->where("ppp.uang_muka >= 1")
						->group_by('ppp.id')
						->order_by('ppp.tanggal_invoice','asc')
						->order_by('ppp.created_on','asc')
						->get()->result_array();
						?>
						<button onclick="myFunction5()" class="btn btn-info"><b>(1-10403) Uang Muka<b></button>
						<div id="myDIV5" style="display:none;">
							<table width="100% "border="1">
								<tr>
									<th class="text-left" colspan="6" width="90%">Saldo Awal</th>
									<?php
									$styleColor = $akun_110403_lalu < 0 ? 'color:red' : 'color:black';
									?>
									<th class="text-right" style="<?php echo $styleColor ?>"><?php echo $akun_110403_lalu < 0 ? "(".number_format(-$akun_110403_lalu,0,',','.').")" : number_format($akun_110403_lalu,0,',','.');?></th>
								</tr>
							</table>
							<?php
							$saldo = $akun_110403_lalu;
							$total_debit = 0;
							$total_kredit = 0;
							foreach ($akun_110403 as $x): 
							if ($x['debit']==0) { $saldo = $saldo + $x['debit'] - $x['kredit'] ;} else
							{$saldo = $saldo + $x['debit'];}

							$total_debit += $x['debit'];
							$total_kredit += $x['kredit'];
							$total_saldo = $total_debit - $total_kredit;
							 
							?>
							<table width="100% "border="1">
								<tr>
									<th width="10%" class="text-left"><?php echo $x['tanggal_transaksi'];?></th>
									<th width="10%" class="text-left">Purchase Delivery</th>
									<th width="20%" class="text-left"><?php echo $x['nomor_transaksi'];?></th>
									<th width="30%" class="text-left"><?php echo $x['deskripsi'];?></th>
									<th width="10%" class="text-right"><?php echo number_format($x['debit'],0,',','.');?></th>
									<th width="10%" class="text-right"><?php echo number_format($x['kredit'],0,',','.');?></th>
									<?php
									$styleColor = $saldo < 0 ? 'color:red' : 'color:black';
									?>
									<th width="10%" class="text-right" style="<?php echo $styleColor ?>"><?php echo $saldo < 0 ? "(".number_format(-$saldo,0,',','.').")" : number_format($saldo,0,',','.');?></th>
								</tr>
							</table>
							<?php endforeach; ?>
						</div>
						<div>
							<table width="100% "border="0">
								<tr>
									<th class="text-right" width="70%">(1-10403) Uang Muka | Saldo Akhir</th>
									<th class="text-right" width="10%"><?php echo number_format($akun_110403_lalu + $total_debit,0,',','.');?></th>
									<th class="text-right" width="10%"><?php echo number_format($total_kredit,0,',','.');?></th>
									<?php
									$saldo_110403 = ($akun_110403_lalu + $total_debit) - $total_kredit;
									$styleColor = $saldo_110403 < 0 ? 'color:red' : 'color:black';
									?>
									<th class="text-right" width="10%" style="<?php echo $styleColor ?>"><?php echo $saldo_110403 < 0 ? "(".number_format(-$saldo_110403,0,',','.').")" : number_format($saldo_110403,0,',','.');?></th>
								</tr>
							</table>
						</div>
					</div>
					<br />
					<div>
						<?php
						$akun_110500_lalu =$this->db->select('sum(ppd.tax) as kredit')
						->from('pmm_penagihan_pembelian ppp')
						->join('pmm_penagihan_pembelian_detail ppd','ppp.id = ppd.penagihan_pembelian_id','left')
						->join('pmm_purchase_order ppo', 'ppp.purchase_order_id = ppo.id','left')
						->where("ppp.tanggal_invoice between '$date3' and '$date4'")
						->get()->row_array();
						$akun_110500_lalu = $akun_110500_lalu['debit'];

						$akun_110500 = $this->db->select('ppp.tanggal_invoice as tanggal_transaksi,ppp.nomor_invoice as nomor_transaksi,ppo.no_po as deskripsi,sum(ppd.tax) as kredit')
						->from('pmm_penagihan_pembelian ppp')
						->join('pmm_penagihan_pembelian_detail ppd','ppp.id = ppd.penagihan_pembelian_id','left')
						->join('pmm_purchase_order ppo', 'ppp.purchase_order_id = ppo.id','left')
						->where("ppp.tanggal_invoice between '$date1' and '$date2'")
						->where("ppd.tax >= 1")
						->group_by('ppp.id')
						->order_by('ppp.tanggal_invoice','asc')
						->order_by('ppp.created_on','asc')
						->get()->result_array();
						?>
						<button onclick="myFunction6()" class="btn btn-info"><b>(1-10500) PPN Masukan<b></button>
						<div id="myDIV6" style="display:none;">
							<table width="100% "border="1">
								<tr>
									<th class="text-left" colspan="6" width="90%">Saldo Awal</th>
									<?php
									$styleColor = $akun_110500_lalu < 0 ? 'color:red' : 'color:black';
									?>
									<th class="text-right" style="<?php echo $styleColor ?>"><?php echo $akun_110500_lalu < 0 ? "(".number_format(-$akun_110500_lalu,0,',','.').")" : number_format($akun_110500_lalu,0,',','.');?></th>
								</tr>
							</table>
							<?php
							$saldo = $akun_110500_lalu;
							$total_debit = 0;
							$total_kredit = 0;
							foreach ($akun_110500 as $x): 
							if ($x['debit']==0) { $saldo = $saldo + $x['debit'] - $x['kredit'] ;} else
							{$saldo = $saldo + $x['debit'];}

							$total_debit += $x['debit'];
							$total_kredit += $x['kredit'];
							$total_saldo = $total_debit - $total_kredit;
							 
							?>
							<table width="100% "border="1">
								<tr>
									<th width="10%" class="text-left"><?php echo $x['tanggal_transaksi'];?></th>
									<th width="10%" class="text-left">Purchase Delivery</th>
									<th width="20%" class="text-left"><?php echo $x['nomor_transaksi'];?></th>
									<th width="30%" class="text-left"><?php echo $x['deskripsi'];?></th>
									<th width="10%" class="text-right"><?php echo number_format($x['debit'],0,',','.');?></th>
									<th width="10%" class="text-right"><?php echo number_format($x['kredit'],0,',','.');?></th>
									<?php
									$styleColor = $saldo < 0 ? 'color:red' : 'color:black';
									?>
									<th width="10%" class="text-right" style="<?php echo $styleColor ?>"><?php echo $saldo < 0 ? "(".number_format(-$saldo,0,',','.').")" : number_format($saldo,0,',','.');?></th>
								</tr>
							</table>
							<?php endforeach; ?>
						</div>
						<div>
							<table width="100% "border="0">
								<tr>
									<th class="text-right" width="70%">(1-10500) PPN Masukan | Saldo Akhir</th>
									<th class="text-right" width="10%"><?php echo number_format($akun_110500_lalu + $total_debit,0,',','.');?></th>
									<th class="text-right" width="10%"><?php echo number_format($total_kredit,0,',','.');?></th>
									<?php
									$saldo_110500 = ($akun_110500_lalu + $total_debit) - $total_kredit;
									$styleColor = $saldo_110500 < 0 ? 'color:red' : 'color:black';
									?>
									<th class="text-right" width="10%" style="<?php echo $styleColor ?>"><?php echo $saldo_110500 < 0 ? "(".number_format(-$saldo_110500,0,',','.').")" : number_format($saldo_110500,0,',','.');?></th>
								</tr>
							</table>
						</div>
					</div>
				</th>
			</tr>
	    </table>
		<?php
	}

	public function neraca($arr_date)
	{
		$data = array();
		
		$arr_date = $this->input->post('filter_date');
		$arr_filter_date = explode(' - ', $arr_date);
		$date1 = '';
		$date2 = '';
		$date3 = '';
		$date4 = '';


		if(count($arr_filter_date) == 2){
			$date_now = date('Y-m-d',strtotime($arr_filter_date[0]));
			$date_now = date('Y-m-d', strtotime('-1 days -0 months ', strtotime($date_now)));
			$date4 	= date('Y-m-d',strtotime($date_now));
			$date_now2 = date('2023-01-01');
			$date3 	= date('Y-m-d',strtotime($date_now2));
			$date1 	= date('Y-m-d',strtotime($arr_filter_date[0]));
			$date2 	= date('Y-m-d',strtotime($arr_filter_date[1]));
			$filter_date = date('d/m/Y',strtotime($arr_filter_date[0])).' - '.date('d/m/Y',strtotime($arr_filter_date[1]));
			$filter_date_2 = date('Y-m-d',strtotime($date3)).' - '.date('Y-m-d',strtotime($arr_filter_date[1]));
		}
		
		?>
		
		<table class="table table-bordered" width="100%">
		 <style type="text/css">
			body {
				font-family: helvetica;
				font-size: 11px;
			}

			table tr.table-active{
				background: linear-gradient(90deg, #fdcd3b 20%, #fdcd3b 40%, #e69500 80%);
				font-size: 11px;
				font-weight: bold;
			}
				
			table tr.table-active2{
				background-color: #e69500;
				font-size: 11px;
				font-weight: bold;
				color: white;
			}
				
			table tr.table-active3{
				font-size: 11px;
			}
				
			table tr.table-active4{
				background: linear-gradient(90deg, #eeeeee 5%, #cccccc 50%, #cccccc 100%);
				font-weight: bold;
				font-size: 11px;
				color: black;
			}
		 </style>
	        <tr class="table-active2">
	            <th colspan="2">PERIODE</th>
				<th class="text-center"><?php echo $filter_date = $filter_date = date('d/m/Y',strtotime($arr_filter_date[0])).' - '.date('d/m/Y',strtotime($arr_filter_date[1]));?></th>
	        </tr>

			<tr class="table-active">
	            <th width="100%" class="text-left" colspan="3">ASET</th>
	        </tr>
			<tr class="table-active4">
	            <th width="100%" class="text-left" colspan="3">&nbsp;&nbsp;ASET LANCAR</th>
	        </tr>
				<?php
				$akun_110002 = $this->pmm_model->get110002($date1,$date2);
				$akun_110001 = $this->pmm_model->get110001($date1,$date2);
				$akun_110001 = $akun_110002 - $akun_110001;
				?>
			<tr class="table-active3">
	            <th width="10%" class="text-center">1-10001</th>
				<th class="text-left">Kas</th>
				<!--<th class="text-right"><a target="_blank" href="<?= base_url("pmm/reports/detail_transaction/".$date1."/".$date2."/".'1'."") ?>"><?php echo $akun_1_10001 < 0 ? "(".number_format(-$akun_1_10001,0,',','.').")" : number_format($akun_1_10001,0,',','.');?></a></th>-->
				<?php
				$styleColor = $akun_110001 < 0 ? 'color:red' : 'color:black';
				?>
				<th class="text-right" style="<?php echo $styleColor ?>"><?php echo $akun_110001 < 0 ? "(".number_format(-$akun_110001,0,',','.').")" : number_format($akun_110001,0,',','.');?></th>
			</tr>
				<?php
				$akun_110100 = $this->pmm_model->get110100($date1,$date2);
				?>
			<tr class="table-active3">
				<th width="10%" class="text-center">1-10100</th>
				<th class="text-left">Piutang Usaha</th>
				<?php
				$styleColor = $akun_110100 < 0 ? 'color:red' : 'color:black';
				?>
				<th class="text-right" style="<?php echo $styleColor ?>"><?php echo $akun_110100 < 0 ? "(".number_format(-$akun_110100,0,',','.').")" : number_format($akun_110100,0,',','.');?></th>
	        </tr>
				<?php
				$akun_110101 = $this->pmm_model->get110101($date1,$date2);
				?>
			<tr class="table-active3">
				<th width="10%" class="text-center">1-10101</th>
				<th class="text-left">Piutang Belum Ditagih</th>
				<?php
				$styleColor = $akun_110101 < 0 ? 'color:red' : 'color:black';
				?>
				<th class="text-right" style="<?php echo $styleColor ?>"><?php echo $akun_110101 < 0 ? "(".number_format(-$akun_110101,0,',','.').")" : number_format($akun_110101,0,',','.');?></th>
	        </tr>
				<?php
				$akun_110201 = $this->pmm_model->get110201($date1,$date2);
				?>
			<tr class="table-active3">
				<th width="10%" class="text-center">1-10201</th>
				<th class="text-left">Persediaan Bahan Baku</th>
				<?php
				$styleColor = $akun_110201 < 0 ? 'color:red' : 'color:black';
				?>
				<th class="text-right" style="<?php echo $styleColor ?>"><?php echo $akun_110201 < 0 ? "(".number_format(-$akun_110201,0,',','.').")" : number_format($akun_110201,0,',','.');?></th>
	        </tr>
				<?php
				$akun_110403 = $this->pmm_model->get110403($date1,$date2);
				?>
			<tr class="table-active3">
				<th width="10%" class="text-center">1-10403</th>
				<th class="text-left">Uang Muka</th>
				<?php
				$styleColor = $akun_110403 < 0 ? 'color:red' : 'color:black';
				?>
				<th class="text-right" style="<?php echo $styleColor ?>"><?php echo $akun_110403 < 0 ? "(".number_format(-$akun_110403,0,',','.').")" : number_format($akun_110403,0,',','.');?></th>
	        </tr>
				<?php
				$akun_110500 = $this->pmm_model->get110500($date1,$date2);
				?>
			<tr class="table-active3">
				<th width="10%" class="text-center">1-10500</th>
				<th class="text-left">PPN Masukan</th>
				<?php
				$styleColor = $akun_110500 < 0 ? 'color:red' : 'color:black';
				?>
				<th class="text-right" style="<?php echo $styleColor ?>"><?php echo $akun_110500 < 0 ? "(".number_format(-$akun_110500,0,',','.').")" : number_format($akun_110500,0,',','.');?></th>
	        </tr>
				<?php
				$total_aset_lancar = $akun_110001 + $akun_110100 + $akun_110101 + $akun_110201 + $akun_110403 + $akun_110500;
				$styleColor = $total_aset_lancar < 0 ? 'color:red' : 'color:black';
				?>
			<tr class="table-active3">
	            <th class="text-right" colspan="2">TOTAL ASET LANCAR</th>
				<th class="text-right" style="<?php echo $styleColor ?>"><?php echo $total_aset_lancar < 0 ? "(".number_format(-$total_aset_lancar,0,',','.').")" : number_format($total_aset_lancar,0,',','.');?></th>
	        </tr>
			<tr class="table-active4">
	            <th width="100%" class="text-left" colspan="3">&nbsp;&nbsp;ASET TETAP</th>
	        </tr>
				<?php
				$akun_110703 = $this->pmm_model->get110703($date1,$date2);
				?>
			<tr class="table-active3">
				<th width="10%" class="text-center">1-10703</th>
				<th class="text-left">Aset Tetap - Kendaraan</th>
				<?php
				$styleColor = $akun_110703 < 0 ? 'color:red' : 'color:black';
				?>
				<th class="text-right" style="<?php echo $styleColor ?>"><?php echo $akun_110703 < 0 ? "(".number_format(-$akun_110703,0,',','.').")" : number_format($akun_110703,0,',','.');?></th>
	        </tr>
				<?php
				$akun_110704 = $this->pmm_model->get110704($date1,$date2);
				?>
			<tr class="table-active3">
				<th width="10%" class="text-center">1-10704</th>
				<th class="text-left">Aset Tetap - Mesin & Peralatan</th>
				<?php
				$styleColor = $akun_110704 < 0 ? 'color:red' : 'color:black';
				?>
				<th class="text-right" style="<?php echo $styleColor ?>"><?php echo $akun_110704 < 0 ? "(".number_format(-$akun_110704,0,',','.').")" : number_format($akun_110704,0,',','.');?></th>
	        </tr>
				<?php
				$akun_110705 = $this->pmm_model->get110705($date1,$date2);
				?>
			<tr class="table-active3">
				<th width="10%" class="text-center">1-10705</th>
				<th class="text-left">Aset Tetap - Perlengkapan Kantor</th>
				<?php
				$styleColor = $akun_110705 < 0 ? 'color:red' : 'color:black';
				?>
				<th class="text-right" style="<?php echo $styleColor ?>"><?php echo $akun_110705 < 0 ? "(".number_format(-$akun_110705,0,',','.').")" : number_format($akun_110705,0,',','.');?></th>
	        </tr>
				<?php
				$total_aset_tetap = $akun_110703 + $akun_110704 + $akun_110705;
				$styleColor = $total_aset_tetap < 0 ? 'color:red' : 'color:black';
				?>
			<tr class="table-active3">
	            <th class="text-right" colspan="2">TOTAL ASET TETAP</th>
				<th class="text-right" style="<?php echo $styleColor ?>"><?php echo $total_aset_tetap < 0 ? "(".number_format(-$total_aset_tetap,0,',','.').")" : number_format($total_aset_tetap,0,',','.');?></th>
	        </tr>
			<tr class="table-active4">
	            <th width="100%" class="text-left" colspan="3">&nbsp;&nbsp;DEPRESIASI & AMORTISASI</th>
	        </tr>
				<?php
				$akun_110753 = $this->pmm_model->get110753($date1,$date2);
				?>
			<tr class="table-active3">
				<th width="10%" class="text-center">1-10753</th>
				<th class="text-left">Akumulasi Penyusutan - Kendaraan</th>
				<?php
				$styleColor = $akun_110753 < 0 ? 'color:red' : 'color:black';
				?>
				<th class="text-right" style="<?php echo $styleColor ?>"><?php echo $akun_110753 < 0 ? "(".number_format(-$akun_110753,0,',','.').")" : number_format($akun_110753,0,',','.');?></th>
	        </tr>
				<?php
				$akun_110754 = $this->pmm_model->get110754($date1,$date2);
				?>
			<tr class="table-active3">
				<th width="10%" class="text-center">1-10754</th>
				<th class="text-left">Akumulasi Penyusutan - Mesin & Peralatan</th>
				<?php
				$styleColor = $akun_110754 < 0 ? 'color:red' : 'color:black';
				?>
				<th class="text-right" style="<?php echo $styleColor ?>"><?php echo $akun_110754 < 0 ? "(".number_format(-$akun_110754,0,',','.').")" : number_format($akun_110754,0,',','.');?></th>
	        </tr>
				<?php
				$akun_110755 = $this->pmm_model->get110755($date1,$date2);
				?>
			<tr class="table-active3">
				<th width="10%" class="text-center">1-10755</th>
				<th class="text-left">Akumulasi Penyusutan - Peralatan Kantor</th>
				<?php
				$styleColor = $akun_110755 < 0 ? 'color:red' : 'color:black';
				?>
				<th class="text-right" style="<?php echo $styleColor ?>"><?php echo $akun_110755 < 0 ? "(".number_format(-$akun_110755,0,',','.').")" : number_format($akun_110755,0,',','.');?></th>
	        </tr>
				<?php
				$total_depresiasi_amortisasi = $akun_110753 + $akun_110755 + $akun_110755;
				$styleColor = $total_depresiasi_amortisasi < 0 ? 'color:red' : 'color:black';
				?>
			<tr class="table-active3">
	            <th class="text-right" colspan="2">TOTAL DEPRESIASI & AMORTISASI</th>
				<th class="text-right" style="<?php echo $styleColor ?>"><?php echo $total_depresiasi_amortisasi < 0 ? "(".number_format(-$total_depresiasi_amortisasi,0,',','.').")" : number_format($total_depresiasi_amortisasi,0,',','.');?></th>
	        </tr>
			<tr class="table-active4">
	            <th width="100%" class="text-left" colspan="3">&nbsp;&nbsp;LAIN-LAIN</th>
	        </tr>
			<tr class="table-active3">
	            <th class="text-right" colspan="2">TOTAL ASET LAIN-LAIN</th>
				<th class="text-right"><?php echo number_format(0,0,',','.');?></th>
	        </tr>
				<?php
				$total_aset = $total_aset_lancar + $total_aset_tetap + $total_depresiasi_amortisasi;
				$styleColor = $total_aset < 0 ? 'color:red' : 'color:black';
				?>
			<tr class="table-active3">
	            <th class="text-right" colspan="2">TOTAL ASET</th>
				<th class="text-right" style="<?php echo $styleColor ?>"><?php echo $total_aset < 0 ? "(".number_format(-$total_aset,0,',','.').")" : number_format($total_aset,0,',','.');?></th>
	        </tr>
			<tr class="table-active4">
	            <th width="100%" class="text-left" colspan="3">&nbsp;&nbsp;LIABILITAS & MODAL</th>
	        </tr>
			<tr class="table-active4">
	            <th width="100%" class="text-left" colspan="3">&nbsp;&nbsp;&nbsp;&nbsp;LIABILITAS JANGKA PENDEK</th>
	        </tr>
				<?php
				$akun_220100 = $this->pmm_model->get220100($date1,$date2);
				?>
			<tr class="table-active3">
				<th width="10%" class="text-center">2-20100</th>
				<th class="text-left">Hutang Usaha</th>
				<?php
				$styleColor = $akun_220100 < 0 ? 'color:red' : 'color:black';
				?>
				<th class="text-right" style="<?php echo $styleColor ?>"><?php echo $akun_220100 < 0 ? "(".number_format(-$akun_220100,0,',','.').")" : number_format($akun_220100,0,',','.');?></th>
	        </tr>
				<?php
				$akun_220101 = $this->pmm_model->get220101($date1,$date2);
				?>
			<tr class="table-active3">
				<th width="10%" class="text-center">2-20101</th>
				<th class="text-left">Hutang Belum Ditagih</th>
				<?php
				$styleColor = $akun_220101 < 0 ? 'color:red' : 'color:black';
				?>
				<th class="text-right" style="<?php echo $styleColor ?>"><?php echo $akun_220101 < 0 ? "(".number_format(-$akun_220101,0,',','.').")" : number_format($akun_220101,0,',','.');?></th>
	        </tr>
				<?php
				$akun_220200 = $this->pmm_model->get220200($date1,$date2);
				?>
			<tr class="table-active3">
				<th width="10%" class="text-center">2-20200</th>
				<th class="text-left">Hutang Lain Lain</th>
				<?php
				$styleColor = $akun_220200 < 0 ? 'color:red' : 'color:black';
				?>
				<th class="text-right" style="<?php echo $styleColor ?>"><?php echo $akun_220200 < 0 ? "(".number_format(-$akun_220200,0,',','.').")" : number_format($akun_220200,0,',','.');?></th>
	        </tr>
				<?php
				$akun_220205 = $this->pmm_model->get110002($date1,$date2);
				?>
			<tr class="table-active3">
				<th width="10%" class="text-center">2-20205</th>
				<th class="text-left">Hutang Modal</th>
				<?php
				$styleColor = $akun_220205 < 0 ? 'color:red' : 'color:black';
				?>
				<th class="text-right" style="<?php echo $styleColor ?>"><?php echo $akun_220205 < 0 ? "(".number_format(-$akun_220205,0,',','.').")" : number_format($akun_220205,0,',','.');?></th>
	        </tr>

				<?php
				$akun_220500 = $this->pmm_model->get220500($date1,$date2);
				?>
			<tr class="table-active3">
				<th width="10%" class="text-center">2-20500</th>
				<th class="text-left">PPN Keluaran</th>
				<?php
				$styleColor = $akun_220500 < 0 ? 'color:red' : 'color:black';
				?>
				<th class="text-right" style="<?php echo $styleColor ?>"><?php echo $akun_220500 < 0 ? "(".number_format(-$akun_220500,0,',','.').")" : number_format($akun_220500,0,',','.');?></th>
	        </tr>
				<?php
				$total_liabilitas_jangka_pendek = $akun_220100 + $akun_220101 + $akun_220200 + $akun_220205 + $akun_220500;
				$styleColor = $total_liabilitas_jangka_pendek < 0 ? 'color:red' : 'color:black';
				?>
			<tr class="table-active3">
	            <th class="text-right" colspan="2">TOTAL LIABILITAS JANGKA PENDEK</th>
				<th class="text-right" style="<?php echo $styleColor ?>"><?php echo $total_liabilitas_jangka_pendek < 0 ? "(".number_format(-$total_liabilitas_jangka_pendek,0,',','.').")" : number_format($total_liabilitas_jangka_pendek,0,',','.');?></th>
	        </tr>
			<tr class="table-active4">
	            <th width="100%" class="text-left" colspan="3">&nbsp;&nbsp;&nbsp;&nbsp;LIABILITAS JANGKA PANJANG</th>
	        </tr>
				<?php
				$total_liabilitas = $total_liabilitas_jangka_pendek;
				$styleColor = $total_liabilitas < 0 ? 'color:red' : 'color:black';
				?>
			<tr class="table-active3">
	            <th class="text-right" colspan="2">TOTAL LIABILITAS</th>
				<th class="text-right" style="<?php echo $styleColor ?>"><?php echo $total_liabilitas < 0 ? "(".number_format(-$total_liabilitas,0,',','.').")" : number_format($total_liabilitas,0,',','.');?></th>
	        </tr>
			<tr class="table-active4">
	            <th width="100%" class="text-left" colspan="3">&nbsp;&nbsp;MODAL PEMILIK</th>
	        </tr>
				<?php
				$akun_330000 = $this->pmm_model->get330000($date1,$date2);
				?>
			<tr class="table-active3">
				<th width="10%" class="text-center">3-30000</th>
				<th class="text-left">Modal Saham</th>
				<?php
				$styleColor = $akun_330000 < 0 ? 'color:red' : 'color:black';
				?>
				<th class="text-right" style="<?php echo $styleColor ?>"><?php echo $akun_330000 < 0 ? "(".number_format(-$akun_330000,0,',','.').")" : number_format($akun_330000,0,',','.');?></th>
	        </tr>
				<?php
				$akun_330999 = $this->pmm_model->get330999($date1,$date2);
				?>
			<tr class="table-active3">
				<th width="10%" class="text-center">3-30999</th>
				<th class="text-left">Ekuitas Saldo Awal</th>
				<?php
				$styleColor = $akun_330999 < 0 ? 'color:red' : 'color:black';
				?>
				<th class="text-right" style="<?php echo $styleColor ?>"><?php echo $akun_330999 < 0 ? "(".number_format(-$akun_330999,0,',','.').")" : number_format($akun_330999,0,',','.');?></th>
	        </tr>
				<?php
				$pendapatan_tahun_lalu = $this->pmm_model->getpendapatantahunlalu($date1,$date2);
				$styleColor = $pendapatan_tahun_lalu < 0 ? 'color:red' : 'color:black';
				?>
			<tr class="table-active3">
				<th width="10%" class="text-center"></th>
				<th class="text-left">Pendapatan sampai Tahun Lalu</th>
				<th class="text-right" style="<?php echo $styleColor ?>"><?php echo $pendapatan_tahun_lalu < 0 ? "(".number_format(-$pendapatan_tahun_lalu,0,',','.').")" : number_format($pendapatan_tahun_lalu,0,',','.');?></th>
	        </tr>
				<?php
				$pendapatan_periode_ini = $this->pmm_model->getpendapatanperiodeini($date3,$date2);
				$styleColor = $pendapatan_periode_ini < 0 ? 'color:red' : 'color:black';
				?>
			<tr class="table-active3">
				<th width="10%" class="text-center"></th>
				<th class="text-left">Pendapatan Periode Ini</th>
				<th class="text-right" style="<?php echo $styleColor ?>"><?php echo $pendapatan_periode_ini < 0 ? "(".number_format(-$pendapatan_periode_ini,0,',','.').")" : number_format($pendapatan_periode_ini,0,',','.');?></th>
	        </tr>
				<?php
				$total_modal_pemilik = $akun_330000 + $akun_330999 + $pendapatan_tahun_lalu + $pendapatan_periode_ini;
				$styleColor = $total_modal_pemilik < 0 ? 'color:red' : 'color:black';
				?>
			<tr class="table-active3">
	            <th class="text-right" colspan="2">TOTAL MODAL PEMILIK</th>
				<th class="text-right" style="<?php echo $styleColor ?>"><?php echo $total_modal_pemilik < 0 ? "(".number_format(-$total_modal_pemilik,0,',','.').")" : number_format($total_modal_pemilik,0,',','.');?></th>
	        </tr>
			<?php
				$total_liabilitas_modal = $total_liabilitas + $total_modal_pemilik;
				$styleColor = $total_modal_pemilik < 0 ? 'color:red' : 'color:black';
				?>
			<tr class="table-active3">
	            <th class="text-right" colspan="2">TOTAL LIABILITAS & MODAL</th>
				<th class="text-right" style="<?php echo $styleColor ?>"><?php echo $total_liabilitas_modal < 0 ? "(".number_format(-$total_liabilitas_modal,0,',','.').")" : number_format($total_liabilitas_modal,0,',','.');?></th>
	        </tr>
	    </table>
		<?php
	}

	public function detail_transaction($date1,$date2,$id)
    {
        $check = $this->m_admin->check_login();
        if($check == true){

			$this->db->select('t.*, pb.nomor_transaksi as trx_biaya, pj.nomor_transaksi as trx_jurnal, tu.nomor_transaksi as trx_terima, tr.nomor_transaksi as trx_transfer, sum(t.debit) as debit, sum(t.kredit) as kredit');
			$this->db->join('pmm_biaya pb','t.biaya_id = pb.id','left');
			$this->db->join('pmm_jurnal_umum pj','t.jurnal_id = pj.id','left');
			$this->db->join('pmm_terima_uang tu','t.terima_id = tu.id','left');
			$this->db->join('pmm_transfer tr','t.transfer_id = tr.id','left');
			$this->db->where('t.tanggal_transaksi >=',$date1);
            $this->db->where('t.tanggal_transaksi <=',$date2);
            $this->db->where('t.akun',$id);
			$this->db->group_by('t.id');
			$this->db->order_by('t.tanggal_transaksi','asc');
			$this->db->order_by('t.id','asc');
            $query = $this->db->get('transactions t');
            $data['row'] = $query->result_array();
            $this->load->view('laporan_keuangan/detail_transaction',$data);
            
        }else {
            redirect('admin');
        }
    }

	public function detail_transaction2($date1,$date2,$id)
    {
        $check = $this->m_admin->check_login();
        if($check == true){

			$this->db->select('t.*, pb.nomor_transaksi as trx_biaya, pj.nomor_transaksi as trx_jurnal, tu.nomor_transaksi as trx_terima, tr.nomor_transaksi as trx_transfer, sum(t.debit) as debit, sum(t.kredit) as kredit');
			$this->db->join('pmm_biaya pb','t.biaya_id = pb.id','left');
			$this->db->join('pmm_jurnal_umum pj','t.jurnal_id = pj.id','left');
			$this->db->join('pmm_terima_uang tu','t.terima_id = tu.id','left');
			$this->db->join('pmm_transfer tr','t.transfer_id = tr.id','left');
			$this->db->where('t.tanggal_transaksi >=',$date1);
            $this->db->where('t.tanggal_transaksi <=',$date2);
            $this->db->where('t.akun',$id);
			$this->db->group_by('t.id');
			$this->db->order_by('t.tanggal_transaksi','asc');
			$this->db->order_by('t.id','asc');
            $query = $this->db->get('transactions t');
            $data['row'] = $query->result_array();

			$this->db->select('t.*, sum(t.debit) as debit, sum(t.kredit) as kredit');
			$this->db->where('t.tanggal_transaksi >=',$date1);
            $this->db->where('t.tanggal_transaksi <=',$date2);
            $this->db->where('t.akun',1);
			$this->db->group_by('t.id');
			$this->db->order_by('t.tanggal_transaksi','asc');
			$this->db->order_by('t.id','asc');
            $query = $this->db->get('transactions t');
            $data['row2'] = $query->result_array();
            $this->load->view('laporan_keuangan/detail_transaction2',$data);
            
        }else {
            redirect('admin');
        }
    }

	public function laporan_evaluasi_biaya_produksi($arr_date)
	{
		$data = array();
		
		$arr_date = $this->input->post('filter_date');
		$arr_filter_date = explode(' - ', $arr_date);
		$date3 = '';
		$date1 = '';
		$date2 = '';

		if(count($arr_filter_date) == 2){
			$date3 	= date('2023-08-01',strtotime($date3));
			$date1 	= date('Y-m-d',strtotime($arr_filter_date[0]));
			$date2 	= date('Y-m-d',strtotime($arr_filter_date[1]));
			$filter_date = date('d/m/Y',strtotime($arr_filter_date[0])).' - '.date('d/m/Y',strtotime($arr_filter_date[1]));
			$filter_date_2 = date('Y-m-d',strtotime($date3)).' - '.date('Y-m-d',strtotime($arr_filter_date[1]));
		}
		
		?>
		
		<table class="table table-bordered" width="100%">
		 <style type="text/css">
			body {
				font-family: helvetica;
				font-size: 11px;
			}

			table tr.table-active{
				background-color: #e69500;
				font-size: 11px;
				font-weight: bold;
			}
				
			table tr.table-active2{
				background-color: #e69500;
				font-size: 11px;
				font-weight: bold;
				color: white;
			}
				
			table tr.table-active3{
				font-size: 11px;
			}
				
			table tr.table-active4{
				background: linear-gradient(90deg, #eeeeee 5%, #cccccc 50%, #cccccc 100%);
				font-weight: bold;
				font-size: 11px;
				color: black;
			}
		 </style>
			<?php
			$komposisi = $this->db->select('pp.date_production, (pp.display_volume) * pk.presentase_a as volume_a, (pp.display_volume) * pk.presentase_b as volume_b, (pp.display_volume) * pk.presentase_c as volume_c, (pp.display_volume) * pk.presentase_d as volume_d, (pp.display_volume) * pk.presentase_e as volume_e, (pp.display_volume * pk.presentase_a) * pk.price_a as nilai_a, (pp.display_volume * pk.presentase_b) * pk.price_b as nilai_b, (pp.display_volume * pk.presentase_c) * pk.price_c as nilai_c, (pp.display_volume * pk.presentase_d) * pk.price_d as nilai_d, (pp.display_volume * pk.presentase_e) * pk.price_e as nilai_e')
			->from('pmm_productions pp')
			->join('pmm_agregat pk', 'pp.komposisi_id = pk.id','left')
			->where("pp.date_production between '$date1' and '$date2'")
			->get()->result_array();

			$total_volume_a = 0;
			$total_volume_b = 0;
			$total_volume_c = 0;
			$total_volume_d = 0;
			$total_volume_e = 0;

			$total_nilai_a = 0;
			$total_nilai_b = 0;
			$total_nilai_c = 0;
			$total_nilai_d = 0;
			$total_nilai_e = 0;

			foreach ($komposisi as $x){
				$total_volume_a += $x['volume_a'];
				$total_volume_b += $x['volume_b'];
				$total_volume_c += $x['volume_c'];
				$total_volume_d += $x['volume_d'];
				$total_volume_e += $x['volume_e'];
				$total_nilai_a += $x['nilai_a'];
				$total_nilai_b += $x['nilai_b'];
				$total_nilai_c += $x['nilai_c'];
				$total_nilai_d += $x['nilai_d'];
				$total_nilai_e += $x['nilai_e'];
				
			}

			$volume_a = $total_volume_a;
			$volume_b = $total_volume_b;
			$volume_c = $total_volume_c;
			$volume_d = $total_volume_d;
			$volume_e = $total_volume_e;

			$nilai_a = $total_nilai_a;
			$nilai_b = $total_nilai_b;
			$nilai_c = $total_nilai_c;
			$nilai_d = $total_nilai_d;
			$nilai_e = $total_nilai_e;

			$price_a = ($total_volume_a!=0)?$total_nilai_a / $total_volume_a * 1:0;
			$price_b = ($total_volume_b!=0)?$total_nilai_b / $total_volume_b * 1:0;
			$price_c = ($total_volume_c!=0)?$total_nilai_c / $total_volume_c * 1:0;
			$price_d = ($total_volume_d!=0)?$total_nilai_d / $total_volume_d * 1:0;
			$price_e = ($total_volume_e!=0)?$total_nilai_e / $total_volume_e * 1:0;

			$total_volume_komposisi = $volume_a + $volume_b + $volume_c + $volume_d + $volume_e;
			$total_nilai_komposisi = $nilai_a + $nilai_b + $nilai_c + $nilai_d + $nilai_e;
			
			
			$date1_ago = date('2020-01-01');
			$date2_ago = date('Y-m-d', strtotime('-1 days', strtotime($date1)));
			$date3_ago = date('Y-m-d', strtotime('-1 months', strtotime($date1)));
			$tanggal_opening_balance = date('Y-m-d', strtotime('-1 days', strtotime($date1)));

			$stock_opname_semen_ago = $this->db->select('cat.volume as volume, cat.total as nilai')
			->from('pmm_remaining_materials_cat cat ')
			->where("(cat.date <= '$tanggal_opening_balance')")
			->where("cat.material_id = 1")
			->where("cat.status = 'PUBLISH'")
			->order_by('date','desc')->limit(1)
			->get()->row_array();

			$stok_volume_semen_lalu = $stock_opname_semen_ago['volume'];
			$stok_nilai_semen_lalu = $stock_opname_semen_ago['nilai'];
			$stok_harsat_semen_lalu = (round($stok_volume_semen_lalu,2)!=0)?$stok_nilai_semen_lalu / round($stok_volume_semen_lalu,2) * 1:0;

			$pembelian_semen = $this->db->select('prm.display_measure as satuan, SUM(prm.display_volume) as volume, (prm.display_price / prm.display_volume) as harga, SUM(prm.display_price) as nilai')
			->from('pmm_receipt_material prm')
			->join('pmm_purchase_order po', 'prm.purchase_order_id = po.id','left')
			->join('produk p', 'prm.material_id = p.id','left')
			->where("prm.date_receipt between '$date1' and '$date2'")
			->where("p.kategori_bahan = 1")
			->get()->row_array();
		
			$pembelian_volume_semen = $pembelian_semen['volume'];
			$pembelian_nilai_semen = $pembelian_semen['nilai'];
			$pembelian_harga_semen = (round($pembelian_volume_semen,2)!=0)?$pembelian_nilai_semen / round($pembelian_volume_semen,2) * 1:0;

			$total_stok_volume_semen = $stok_volume_semen_lalu + $pembelian_volume_semen;
			$total_stok_nilai_semen = $stok_nilai_semen_lalu + $pembelian_nilai_semen;

			$stock_opname_semen_now = $this->db->select('cat.volume as volume, cat.total as nilai, cat.pemakaian_custom, cat.reset, cat.reset_pemakaian')
			->from('pmm_remaining_materials_cat cat')
			->where("(cat.date <= '$date2')")
			->where("cat.material_id = 1")
			->where("cat.status = 'PUBLISH'")
			->order_by('date','desc')->limit(1)
			->get()->row_array();

			$volume_stock_opname_semen_now = $stock_opname_semen_now['volume'];
			$nilai_stock_opname_semen_now = $stock_opname_semen_now['nilai'];

			$vol_pemakaian_semen_now = ($stok_volume_semen_lalu + $pembelian_volume_semen) - $volume_stock_opname_semen_now;
			$nilai_pemakaian_semen_now = $stock_opname_semen_now['nilai'];

			$pemakaian_volume_semen = $vol_pemakaian_semen_now;
			$pemakaian_nilai_semen = (($total_stok_nilai_semen - $nilai_stock_opname_semen_now) * $stock_opname_semen_now['reset']) + ($stock_opname_semen_now['pemakaian_custom'] * $stock_opname_semen_now['reset_pemakaian']);
			$pemakaian_harsat_semen = $pemakaian_nilai_semen / $pemakaian_volume_semen;
			
			$stock_opname_pasir_ago = $this->db->select('cat.volume as volume, cat.total as nilai')
			->from('pmm_remaining_materials_cat cat ')
			->where("(cat.date <= '$tanggal_opening_balance')")
			->where("cat.material_id = 2")
			->where("cat.status = 'PUBLISH'")
			->order_by('date','desc')->limit(1)
			->get()->row_array();

			$stok_volume_pasir_lalu = $stock_opname_pasir_ago['volume'];
			$stok_nilai_pasir_lalu = $stock_opname_pasir_ago['nilai'];
			$stok_harsat_pasir_lalu = (round($stok_volume_pasir_lalu,2)!=0)?$stok_nilai_pasir_lalu / round($stok_volume_pasir_lalu,2) * 1:0;

			$pembelian_pasir = $this->db->select('prm.display_measure as satuan, SUM(prm.display_volume) as volume, (prm.display_price / prm.display_volume) as harga, SUM(prm.display_price) as nilai')
			->from('pmm_receipt_material prm')
			->join('pmm_purchase_order po', 'prm.purchase_order_id = po.id','left')
			->join('produk p', 'prm.material_id = p.id','left')
			->where("prm.date_receipt between '$date1' and '$date2'")
			->where("p.kategori_bahan = 2")
			->get()->row_array();
		
			$pembelian_volume_pasir = $pembelian_pasir['volume'];
			$pembelian_nilai_pasir = $pembelian_pasir['nilai'];
			$pembelian_harga_pasir = (round($pembelian_volume_pasir,2)!=0)?$pembelian_nilai_pasir / round($pembelian_volume_pasir,2) * 1:0;

			$total_stok_volume_pasir = $stok_volume_pasir_lalu + $pembelian_volume_pasir;
			$total_stok_nilai_pasir = $stok_nilai_pasir_lalu + $pembelian_nilai_pasir;

			$stock_opname_pasir_now = $this->db->select('cat.volume as volume, cat.total as nilai, cat.pemakaian_custom, cat.reset, cat.reset_pemakaian')
			->from('pmm_remaining_materials_cat cat ')
			->where("(cat.date <= '$date2')")
			->where("cat.material_id = 2")
			->where("cat.status = 'PUBLISH'")
			->order_by('date','desc')->limit(1)
			->get()->row_array();

			$volume_stock_opname_pasir_now = $stock_opname_pasir_now['volume'];
			$nilai_stock_opname_pasir_now = $stock_opname_pasir_now['nilai'];

			$vol_pemakaian_pasir_now = ($stok_volume_pasir_lalu + $pembelian_volume_pasir) - $volume_stock_opname_pasir_now;
			$nilai_pemakaian_pasir_now = $stock_opname_pasir_now['nilai'];

			$pemakaian_volume_pasir = $vol_pemakaian_pasir_now;
			$pemakaian_nilai_pasir = (($total_stok_nilai_pasir - $nilai_stock_opname_pasir_now) * $stock_opname_pasir_now['reset']) + ($stock_opname_pasir_now['pemakaian_custom'] * $stock_opname_pasir_now['reset_pemakaian']);
			$pemakaian_harsat_pasir = $pemakaian_nilai_pasir / $pemakaian_volume_pasir;

			$stock_opname_1020_ago = $this->db->select('cat.volume as volume, cat.total as nilai')
			->from('pmm_remaining_materials_cat cat ')
			->where("(cat.date <= '$tanggal_opening_balance')")
			->where("cat.material_id = 3")
			->where("cat.status = 'PUBLISH'")
			->order_by('date','desc')->limit(1)
			->get()->row_array();

			$stok_volume_1020_lalu = $stock_opname_1020_ago['volume'];
			$stok_nilai_1020_lalu = $stock_opname_1020_ago['nilai'];
			$stok_harsat_1020_lalu = (round($stok_volume_1020_lalu,2)!=0)?$stok_nilai_1020_lalu / round($stok_volume_1020_lalu,2) * 1:0;

			$pembelian_1020 = $this->db->select('prm.display_measure as satuan, SUM(prm.display_volume) as volume, (prm.display_price / prm.display_volume) as harga, SUM(prm.display_price) as nilai')
			->from('pmm_receipt_material prm')
			->join('pmm_purchase_order po', 'prm.purchase_order_id = po.id','left')
			->join('produk p', 'prm.material_id = p.id','left')
			->where("prm.date_receipt between '$date1' and '$date2'")
			->where("p.kategori_bahan = 3")
			->get()->row_array();
		
			$pembelian_volume_1020 = $pembelian_1020['volume'];
			$pembelian_nilai_1020 = $pembelian_1020['nilai'];
			$pembelian_harga_1020 = (round($pembelian_volume_1020,2)!=0)?$pembelian_nilai_1020 / round($pembelian_volume_1020,2) * 1:0;

			$total_stok_volume_1020 = $stok_volume_1020_lalu + $pembelian_volume_1020;
			$total_stok_nilai_1020 = $stok_nilai_1020_lalu + $pembelian_nilai_1020;

			$stock_opname_1020_now = $this->db->select('cat.volume as volume, cat.total as nilai, cat.pemakaian_custom, cat.reset, cat.reset_pemakaian')
			->from('pmm_remaining_materials_cat cat ')
			->where("(cat.date <= '$date2')")
			->where("cat.material_id = 3")
			->where("cat.status = 'PUBLISH'")
			->order_by('date','desc')->limit(1)
			->get()->row_array();

			$volume_stock_opname_1020_now = $stock_opname_1020_now['volume'];
			$nilai_stock_opname_1020_now = $stock_opname_1020_now['nilai'];

			$vol_pemakaian_1020_now = ($stok_volume_1020_lalu + $pembelian_volume_1020) - $volume_stock_opname_1020_now;
			$nilai_pemakaian_1020_now = $stock_opname_1020_now['nilai'];

			$pemakaian_volume_1020 = $vol_pemakaian_1020_now;
			$pemakaian_nilai_1020 = (($total_stok_nilai_1020 - $nilai_stock_opname_1020_now) * $stock_opname_1020_now['reset']) + ($stock_opname_1020_now['pemakaian_custom'] * $stock_opname_1020_now['reset_pemakaian']);
			$pemakaian_harsat_1020 = $pemakaian_nilai_1020 / $pemakaian_volume_1020;

			$stock_opname_2030_ago = $this->db->select('cat.volume as volume, cat.total as nilai')
			->from('pmm_remaining_materials_cat cat ')
			->where("(cat.date <= '$tanggal_opening_balance')")
			->where("cat.material_id = 4")
			->where("cat.status = 'PUBLISH'")
			->order_by('date','desc')->limit(1)
			->get()->row_array();

			$stok_volume_2030_lalu = $stock_opname_2030_ago['volume'];
			$stok_nilai_2030_lalu = $stock_opname_2030_ago['nilai'];
			$stok_harsat_2030_lalu = (round($stok_volume_2030_lalu,2)!=0)?$stok_nilai_2030_lalu / round($stok_volume_2030_lalu,2) * 1:0;

			$pembelian_2030 = $this->db->select('prm.display_measure as satuan, SUM(prm.display_volume) as volume, (prm.display_price / prm.display_volume) as harga, SUM(prm.display_price) as nilai')
			->from('pmm_receipt_material prm')
			->join('pmm_purchase_order po', 'prm.purchase_order_id = po.id','left')
			->join('produk p', 'prm.material_id = p.id','left')
			->where("prm.date_receipt between '$date1' and '$date2'")
			->where("p.kategori_bahan = 4")
			->get()->row_array();
		
			$pembelian_volume_2030 = $pembelian_2030['volume'];
			$pembelian_nilai_2030 = $pembelian_2030['nilai'];
			$pembelian_harga_2030 = (round($pembelian_volume_2030,2)!=0)?$pembelian_nilai_2030 / round($pembelian_volume_2030,2) * 1:0;

			$total_stok_volume_2030 = $stok_volume_2030_lalu + $pembelian_volume_2030;
			$total_stok_nilai_2030 = $stok_nilai_2030_lalu + $pembelian_nilai_2030;

			$stock_opname_2030_now = $this->db->select('cat.volume as volume, cat.total as nilai, cat.pemakaian_custom, cat.reset, cat.reset_pemakaian')
			->from('pmm_remaining_materials_cat cat ')
			->where("(cat.date <= '$date2')")
			->where("cat.material_id = 4")
			->where("cat.status = 'PUBLISH'")
			->order_by('date','desc')->limit(1)
			->get()->row_array();

			$volume_stock_opname_2030_now = $stock_opname_2030_now['volume'];
			$nilai_stock_opname_2030_now = $stock_opname_2030_now['nilai'];

			$vol_pemakaian_2030_now = ($stok_volume_2030_lalu + $pembelian_volume_2030) - $volume_stock_opname_2030_now;
			$nilai_pemakaian_2030_now = $stock_opname_2030_now['nilai'];

			$pemakaian_volume_2030 = $vol_pemakaian_2030_now;
			$pemakaian_nilai_2030 = (($total_stok_nilai_2030 - $nilai_stock_opname_2030_now) * $stock_opname_2030_now['reset']) + ($stock_opname_2030_now['pemakaian_custom'] * $stock_opname_2030_now['reset_pemakaian']);
			$pemakaian_harsat_2030 = $pemakaian_nilai_2030 / $pemakaian_volume_2030;

			$stock_opname_additive_ago = $this->db->select('cat.volume as volume, cat.total as nilai')
			->from('pmm_remaining_materials_cat cat ')
			->where("(cat.date <= '$tanggal_opening_balance')")
			->where("cat.material_id = 19")
			->where("cat.status = 'PUBLISH'")
			->order_by('date','desc')->limit(1)
			->get()->row_array();

			$stok_volume_additive_lalu = $stock_opname_additive_ago['volume'];
			$stok_nilai_additive_lalu = $stock_opname_additive_ago['nilai'];
			$stok_harsat_additive_lalu = (round($stok_volume_additive_lalu,2)!=0)?$stok_nilai_additive_lalu / round($stok_volume_additive_lalu,2) * 1:0;

			$pembelian_additive = $this->db->select('prm.display_measure as satuan, SUM(prm.display_volume) as volume, (prm.display_price / prm.display_volume) as harga, SUM(prm.display_price) as nilai')
			->from('pmm_receipt_material prm')
			->join('pmm_purchase_order po', 'prm.purchase_order_id = po.id','left')
			->join('produk p', 'prm.material_id = p.id','left')
			->where("prm.date_receipt between '$date1' and '$date2'")
			->where("p.kategori_bahan = 6")
			->get()->row_array();
		
			$pembelian_volume_additive = $pembelian_additive['volume'];
			$pembelian_nilai_additive = $pembelian_additive['nilai'];
			$pembelian_harga_additive = (round($pembelian_volume_additive,2)!=0)?$pembelian_nilai_additive / round($pembelian_volume_additive,2) * 1:0;

			$total_stok_volume_additive = $stok_volume_additive_lalu + $pembelian_volume_additive;
			$total_stok_nilai_additive = $stok_nilai_additive_lalu + $pembelian_nilai_additive;

			$stock_opname_additive_now = $this->db->select('cat.volume as volume, cat.total as nilai, cat.pemakaian_custom, cat.reset, cat.reset_pemakaian')
			->from('pmm_remaining_materials_cat cat ')
			->where("(cat.date <= '$date2')")
			->where("cat.material_id = 19")
			->where("cat.status = 'PUBLISH'")
			->order_by('date','desc')->limit(1)
			->get()->row_array();

			$volume_stock_opname_additive_now = $stock_opname_additive_now['volume'];
			$nilai_stock_opname_additive_now = $stock_opname_additive_now['nilai'];

			$vol_pemakaian_additive_now = ($stok_volume_additive_lalu + $pembelian_volume_additive) - $volume_stock_opname_additive_now;
			$nilai_pemakaian_additive_now = $stock_opname_additive_now['nilai'];

			$pemakaian_volume_additive = $vol_pemakaian_additive_now;
			$pemakaian_nilai_additive = (($total_stok_nilai_additive - $nilai_stock_opname_additive_now) * $stock_opname_additive_now['reset']) + ($stock_opname_additive_now['pemakaian_custom'] * $stock_opname_additive_now['reset_pemakaian']);
			$pemakaian_harsat_additive = $pemakaian_nilai_additive / $pemakaian_volume_additive;

			$total_volume_realisasi = $pemakaian_volume_semen + $pemakaian_volume_pasir + $pemakaian_volume_1020 + $pemakaian_volume_2030 +  $pemakaian_volume_additive;
			$total_nilai_realisasi = $pemakaian_nilai_semen + $pemakaian_nilai_pasir + $pemakaian_nilai_1020 + $pemakaian_nilai_2030 + $pemakaian_nilai_additive;
			
			$evaluasi_volume_a = round($volume_a - $pemakaian_volume_semen,2);
			$evaluasi_volume_b = round($volume_b - $pemakaian_volume_pasir,2);
			$evaluasi_volume_c = round($volume_c - $pemakaian_volume_1020,2);
			$evaluasi_volume_d = round($volume_d - $pemakaian_volume_2030,2);
			$evaluasi_volume_e = round($volume_e - $pemakaian_volume_additive,2);

			$evaluasi_nilai_a = round($nilai_a - $pemakaian_nilai_semen,0);
			$evaluasi_nilai_b = round($nilai_b - $pemakaian_nilai_pasir,0);
			$evaluasi_nilai_c = round($nilai_c - $pemakaian_nilai_1020,0);
			$evaluasi_nilai_d = round($nilai_d - $pemakaian_nilai_2030,0);
			$evaluasi_nilai_e = round($nilai_e - $pemakaian_nilai_additive,0);

			$total_volume_evaluasi = round($total_volume_komposisi - $total_volume_realisasi,2);
			$total_nilai_evaluasi = round($evaluasi_nilai_a + $evaluasi_nilai_b + $evaluasi_nilai_c + $evaluasi_nilai_d + $evaluasi_nilai_e,0);
	        ?>

			<?php
			$pembelian_batching_plant = $this->db->select('
			pn.nama, po.no_po, po.subject, prm.measure, SUM(prm.volume) as volume, SUM(prm.price) / SUM(prm.volume) as harga_satuan, SUM(prm.price) as price')
			->from('pmm_receipt_material prm')
			->join('pmm_purchase_order po', 'prm.purchase_order_id = po.id','left')
			->join('produk p', 'prm.material_id = p.id','left')
			->join('penerima pn', 'po.supplier_id = pn.id','left')
			->where("prm.date_receipt between '$date1' and '$date2'")
			->where("p.kategori_alat = '1'")
			->where("po.status in ('PUBLISH','CLOSED')")
			->group_by('prm.harga_satuan')
			->order_by('pn.nama','asc')
			->get()->result_array();

			$total_nilai_batching_plant = 0;
			foreach ($pembelian_batching_plant as $x){
				$total_nilai_batching_plant += $x['price'];
			}

			$pemeliharaan_batching_plant_biaya = $this->db->select('sum(pdb.jumlah) as total')
			->from('pmm_biaya pb ')
			->join('pmm_detail_biaya pdb','pb.id = pdb.biaya_id','left')
			->join('pmm_coa c','pdb.akun = c.id','left')
			->where("pdb.akun = 138")
			->where("pb.status = 'PAID'")
			->where("(pb.tanggal_transaksi between '$date1' and '$date2')")
			->get()->row_array();
	
			$pemeliharaan_batching_plant_jurnal = $this->db->select('sum(pdb.debit) as total')
			->from('pmm_jurnal_umum pb ')
			->join('pmm_detail_jurnal pdb','pb.id = pdb.jurnal_id','left')
			->join('pmm_coa c','pdb.akun = c.id','left')
			->where("pdb.akun = 138")
			->where("pb.status = 'PAID'")
			->where("(pb.tanggal_transaksi between '$date1' and '$date2')")
			->get()->row_array();
			$total_nilai_pemeliharaan_batching_plant = $pemeliharaan_batching_plant_biaya['total'] + $pemeliharaan_batching_plant_jurnal['total'];

			$penyusutan_batching_plant_biaya = $this->db->select('sum(pdb.jumlah) as total')
			->from('pmm_biaya pb ')
			->join('pmm_detail_biaya pdb','pb.id = pdb.biaya_id','left')
			->join('pmm_coa c','pdb.akun = c.id','left')
			->where("pdb.akun = 137")
			->where("pb.status = 'PAID'")
			->where("(pb.tanggal_transaksi between '$date1' and '$date2')")
			->get()->row_array();
	
			$penyusutan_batching_plant_jurnal = $this->db->select('sum(pdb.debit) as total')
			->from('pmm_jurnal_umum pb ')
			->join('pmm_detail_jurnal pdb','pb.id = pdb.jurnal_id','left')
			->join('pmm_coa c','pdb.akun = c.id','left')
			->where("pdb.akun = 137")
			->where("pb.status = 'PAID'")
			->where("(pb.tanggal_transaksi between '$date1' and '$date2')")
			->get()->row_array();
			$total_nilai_penyusutan_batching_plant = $penyusutan_batching_plant_biaya['total'] + $penyusutan_batching_plant_jurnal['total'];
			$total_nilai_batching_plant = $total_nilai_batching_plant + $total_nilai_pemeliharaan_batching_plant + $total_nilai_penyusutan_batching_plant;
			
			$pembelian_truck_mixer = $this->db->select('
			pn.nama, po.no_po, po.subject, prm.measure, SUM(prm.volume) as volume, SUM(prm.price) / SUM(prm.volume) as harga_satuan, SUM(prm.price) as price')
			->from('pmm_receipt_material prm')
			->join('pmm_purchase_order po', 'prm.purchase_order_id = po.id','left')
			->join('produk p', 'prm.material_id = p.id','left')
			->join('penerima pn', 'po.supplier_id = pn.id','left')
			->where("prm.date_receipt between '$date1' and '$date2'")
			->where("p.kategori_alat = '2'")
			->where("po.status in ('PUBLISH','CLOSED')")
			->group_by('prm.harga_satuan')
			->order_by('pn.nama','asc')
			->get()->result_array();

			$total_nilai_truck_mixer = 0;
			foreach ($pembelian_truck_mixer as $x){
				$total_nilai_truck_mixer += $x['price'];
			}

			$pembelian_wheel_loader = $this->db->select('
			pn.nama, po.no_po, po.subject, prm.measure, SUM(prm.volume) as volume, SUM(prm.price) / SUM(prm.volume) as harga_satuan, SUM(prm.price) as price')
			->from('pmm_receipt_material prm')
			->join('pmm_purchase_order po', 'prm.purchase_order_id = po.id','left')
			->join('produk p', 'prm.material_id = p.id','left')
			->join('penerima pn', 'po.supplier_id = pn.id','left')
			->where("prm.date_receipt between '$date1' and '$date2'")
			->where("p.kategori_alat = '3'")
			->where("po.status in ('PUBLISH','CLOSED')")
			->group_by('prm.harga_satuan')
			->order_by('pn.nama','asc')
			->get()->result_array();

			$total_nilai_wheel_loader = 0;
			foreach ($pembelian_wheel_loader as $x){
				$total_nilai_wheel_loader += $x['price'];
			}

			$pemeliharaan_wheel_loader_biaya = $this->db->select('sum(pdb.jumlah) as total')
			->from('pmm_biaya pb ')
			->join('pmm_detail_biaya pdb','pb.id = pdb.biaya_id','left')
			->join('pmm_coa c','pdb.akun = c.id','left')
			->where("pdb.akun = 140")
			->where("pb.status = 'PAID'")
			->where("(pb.tanggal_transaksi between '$date1' and '$date2')")
			->get()->row_array();
	
			$pemeliharaan_wheel_loader_jurnal = $this->db->select('sum(pdb.debit) as total')
			->from('pmm_jurnal_umum pb ')
			->join('pmm_detail_jurnal pdb','pb.id = pdb.jurnal_id','left')
			->join('pmm_coa c','pdb.akun = c.id','left')
			->where("pdb.akun = 140")
			->where("pb.status = 'PAID'")
			->where("(pb.tanggal_transaksi between '$date1' and '$date2')")
			->get()->row_array();
			$total_nilai_pemeliharaan_wheel_loader = $pemeliharaan_wheel_loader_biaya['total'] + $pemeliharaan_wheel_loader_jurnal['total'];

			$penyusutan_wheel_loader_biaya = $this->db->select('sum(pdb.jumlah) as total')
			->from('pmm_biaya pb ')
			->join('pmm_detail_biaya pdb','pb.id = pdb.biaya_id','left')
			->join('pmm_coa c','pdb.akun = c.id','left')
			->where("pdb.akun = 139")
			->where("pb.status = 'PAID'")
			->where("(pb.tanggal_transaksi between '$date1' and '$date2')")
			->get()->row_array();
	
			$penyusutan_wheel_loader_jurnal = $this->db->select('sum(pdb.debit) as total')
			->from('pmm_jurnal_umum pb ')
			->join('pmm_detail_jurnal pdb','pb.id = pdb.jurnal_id','left')
			->join('pmm_coa c','pdb.akun = c.id','left')
			->where("pdb.akun = 136")
			->where("pb.status = 'PAID'")
			->where("(pb.tanggal_transaksi between '$date1' and '$date2')")
			->get()->row_array();
			$total_nilai_penyusutan_wheel_loader = $penyusutan_wheel_loader_biaya['total'] + $penyusutan_wheel_loader_jurnal['total'];
			$total_nilai_wheel_loader = $total_nilai_wheel_loader + $total_nilai_pemeliharaan_wheel_loader + $total_nilai_penyusutan_wheel_loader;

			$pembelian_truck_mixer = $this->db->select('
			pn.nama, po.no_po, po.subject, prm.measure, SUM(prm.volume) as volume, SUM(prm.price) / SUM(prm.volume) as harga_satuan, SUM(prm.price) as price')
			->from('pmm_receipt_material prm')
			->join('pmm_purchase_order po', 'prm.purchase_order_id = po.id','left')
			->join('produk p', 'prm.material_id = p.id','left')
			->join('penerima pn', 'po.supplier_id = pn.id','left')
			->where("prm.date_receipt between '$date1' and '$date2'")
			->where("p.kategori_alat = '2'")
			->where("po.status in ('PUBLISH','CLOSED')")
			->group_by('prm.harga_satuan')
			->order_by('pn.nama','asc')
			->get()->result_array();

			$total_nilai_truck_mixer = 0;
			$total_vol_truck_mixer = 0;
			foreach ($pembelian_truck_mixer as $x){
				$total_nilai_truck_mixer += $x['price'];
				$total_vol_truck_mixer += $x['volume'];
			}

			$pembelian_transfer_semen = $this->db->select('
			pn.nama, po.no_po, po.subject, prm.measure, SUM(prm.volume) as volume, SUM(prm.price) / SUM(prm.volume) as harga_satuan, SUM(prm.price) as price')
			->from('pmm_receipt_material prm')
			->join('pmm_purchase_order po', 'prm.purchase_order_id = po.id','left')
			->join('produk p', 'prm.material_id = p.id','left')
			->join('penerima pn', 'po.supplier_id = pn.id','left')
			->where("prm.date_receipt between '$date1' and '$date2'")
			->where("p.kategori_alat = '4'")
			->where("po.status in ('PUBLISH','CLOSED')")
			->group_by('prm.harga_satuan')
			->order_by('pn.nama','asc')
			->get()->result_array();

			$total_nilai_transfer_semen = 0;
			foreach ($pembelian_transfer_semen as $x){
				$total_nilai_transfer_semen += $x['price'];
			}

			$pembelian_excavator = $this->db->select('
			pn.nama, po.no_po, po.subject, prm.measure, SUM(prm.volume) as volume, SUM(prm.price) / SUM(prm.volume) as harga_satuan, SUM(prm.price) as price')
			->from('pmm_receipt_material prm')
			->join('pmm_purchase_order po', 'prm.purchase_order_id = po.id','left')
			->join('produk p', 'prm.material_id = p.id','left')
			->join('penerima pn', 'po.supplier_id = pn.id','left')
			->where("prm.date_receipt between '$date1' and '$date2'")
			->where("p.kategori_alat = '5'")
			->where("po.status in ('PUBLISH','CLOSED')")
			->group_by('prm.harga_satuan')
			->order_by('pn.nama','asc')
			->get()->result_array();

			$total_nilai_excavator = 0;
			foreach ($pembelian_excavator as $x){
				$total_nilai_excavator += $x['price'];
			}

			$date1_ago = date('2020-01-01');
			$date2_ago = date('Y-m-d', strtotime('-1 days', strtotime($date1)));
			$date3_ago = date('Y-m-d', strtotime('-1 months', strtotime($date1)));
			$tanggal_opening_balance = date('Y-m-d', strtotime('-1 days', strtotime($date1)));

			$stock_opname_solar_ago = $this->db->select('cat.volume as volume, cat.total as nilai')
			->from('pmm_remaining_materials_cat cat ')
			->where("(cat.date <= '$tanggal_opening_balance')")
			->where("cat.material_id = 5")
			->where("cat.status = 'PUBLISH'")
			->order_by('date','desc')->limit(1)
			->get()->row_array();

			$stok_volume_solar_lalu = $stock_opname_solar_ago['volume'];
			$stok_nilai_solar_lalu = $stock_opname_solar_ago['nilai'];
			$stok_harsat_solar_lalu = (round($stok_volume_solar_lalu,2)!=0)?$stok_nilai_solar_lalu / round($stok_volume_solar_lalu,2) * 1:0;

			$pembelian_solar = $this->db->select('prm.display_measure as satuan, SUM(prm.display_volume) as volume, (prm.display_price / prm.display_volume) as harga, SUM(prm.display_price) as nilai')
			->from('pmm_receipt_material prm')
			->join('pmm_purchase_order po', 'prm.purchase_order_id = po.id','left')
			->join('produk p', 'prm.material_id = p.id','left')
			->where("prm.date_receipt between '$date1' and '$date2'")
			->where("p.kategori_bahan = 5")
			->get()->row_array();
		
			$pembelian_volume_solar = $pembelian_solar['volume'];
			$pembelian_nilai_solar = $pembelian_solar['nilai'];
			$pembelian_harga_solar = (round($pembelian_volume_solar,2)!=0)?$pembelian_nilai_solar / round($pembelian_volume_solar,2) * 1:0;

			$total_stok_volume_solar = $stok_volume_solar_lalu + $pembelian_volume_solar;
			$total_stok_nilai_solar = $stok_nilai_solar_lalu + $pembelian_nilai_solar;

			$stock_opname_solar_now = $this->db->select('cat.volume as volume, cat.total as nilai, cat.pemakaian_custom, cat.reset, cat.reset_pemakaian')
			->from('pmm_remaining_materials_cat cat ')
			->where("(cat.date <= '$date2')")
			->where("cat.material_id = 5")
			->where("cat.status = 'PUBLISH'")
			->order_by('date','desc')->limit(1)
			->get()->row_array();

			$volume_stock_opname_solar_now = $stock_opname_solar_now['volume'];
			$nilai_stock_opname_solar_now = $stock_opname_solar_now['nilai'];

			$vol_pemakaian_solar_now = ($stok_volume_solar_lalu + $pembelian_volume_solar) - $volume_stock_opname_solar_now;
			$nilai_pemakaian_solar_now = $stock_opname_solar_now['nilai'];

			$pemakaian_volume_solar = $vol_pemakaian_solar_now;
			$pemakaian_nilai_solar = (($total_stok_nilai_solar - $nilai_stock_opname_solar_now) * $stock_opname_solar_now['reset']) + ($stock_opname_solar_now['pemakaian_custom'] * $stock_opname_solar_now['reset_pemakaian']);
			$pemakaian_harsat_solar = $pemakaian_nilai_solar / $pemakaian_volume_solar;	

			$total_vol_excavator = $pembelian_excavator['volume'];
			$total_vol_transfer_semen = $pembelian_transfer_semen['volume'];

			$penjualan = $this->db->select('p.nama, pp.client_id, SUM(pp.display_price) as price, SUM(pp.display_volume) as volume, pp.convert_measure as measure')
			->from('pmm_productions pp')
			->join('penerima p', 'pp.client_id = p.id','left')
			->join('pmm_sales_po ppo', 'pp.salesPo_id = ppo.id','left')
			->where("pp.date_production between '$date1' and '$date2'")
			->where("pp.status = 'PUBLISH'")
			->where("ppo.status in ('OPEN','CLOSED')")
			->group_by("pp.client_id")
			->get()->result_array();
			
			$total_volume = 0;
			foreach ($penjualan as $x){
				$total_volume += $x['volume'];
			}

			$rap_alat = $this->db->select('rap.*')
			->from('rap_alat rap')
			->where("rap.tanggal_rap_alat <= '$date2'")
			->where('rap.status','PUBLISH')
			->get()->result_array();

			foreach ($rap_alat as $x){
				$vol_rap_batching_plant = $x['vol_batching_plant'];
				$vol_rap_pemeliharaan_batching_plant = $x['vol_pemeliharaan_batching_plant'];
				$vol_rap_wheel_loader = $x['vol_wheel_loader'];
				$vol_rap_pemeliharaan_wheel_loader = $x['vol_pemeliharaan_wheel_loader'];
				$vol_rap_truck_mixer = $x['vol_truck_mixer'];
				$vol_rap_excavator = $x['vol_excavator'];
				$vol_rap_transfer_semen = $x['vol_transfer_semen'];
				$vol_rap_bbm_solar = $x['vol_bbm_solar'];
				$harsat_batching_plant = $x['batching_plant'];
				$harsat_pemeliharaan_batching_plant = $x['pemeliharaan_batching_plant'];
				$harsat_penyusutan_batching_plant = $x['batching_plant'] - $x['pemeliharaan_batching_plant'];
				$harsat_pemeliharaan_wheel_loader = $x['pemeliharaan_wheel_loader'];
				$harsat_penyusutan_wheel_loader = $x['wheel_loader'] - $x['pemeliharaan_wheel_loader'];
				$harsat_wheel_loader = $x['wheel_loader'];
				$harsat_truck_mixer = $x['truck_mixer'];
				$harsat_excavator = $x['excavator'];
				$harsat_transfer_semen = $x['transfer_semen'];
				$harsat_bbm_solar = $x['vol_bbm_solar'] * $x['harsat_bbm_solar'];
				
			}

			$vol_batching_plant = $total_volume;
			$vol_pemeliharaan_batching_plant = $total_volume;
			$vol_penyusutan_batching_plant = $total_volume;
			$vol_wheel_loader = $total_volume;
			$vol_pemeliharaan_wheel_loader = $total_volume;
			$vol_penyusutan_wheel_loader = $total_volume;
			$vol_truck_mixer = $total_volume;
			$vol_excavator = $total_volume;
			$vol_transfer_semen = $total_volume;
			$vol_bbm_solar = $total_volume;

			$batching_plant = $harsat_batching_plant * $total_volume;
			$pemeliharaan_batching_plant = $harsat_pemeliharaan_batching_plant * $total_volume;
			$penyusutan_batching_plant = $batching_plant - $pemeliharaan_batching_plant;
			$wheel_loader = ($harsat_wheel_loader * $vol_wheel_loader) + $total_nilai_pemeliharaan_wheel_loader;
			$pemeliharaan_wheel_loader = $harsat_pemeliharaan_wheel_loader * $vol_pemeliharaan_wheel_loader;
			$penyusutan_wheel_loader = $wheel_loader - $pemeliharaan_wheel_loader;
			$truck_mixer = $harsat_truck_mixer * $total_volume;
			$excavator = $harsat_excavator * $total_volume;
			$transfer_semen = $harsat_transfer_semen * $total_volume;
			$bbm_solar = $harsat_bbm_solar * $vol_bbm_solar;

			$harsat_batching_plant = ($vol_batching_plant!=0)?$batching_plant / $vol_batching_plant * 1:0;
			$harsat_truck_mixer = ($vol_truck_mixer!=0)?$truck_mixer / $vol_truck_mixer * 1:0;
			$harsat_wheel_loader = ($wheel_loader!=0)?$wheel_loader / $vol_wheel_loader * 1:0;
			$harsat_excavator = ($excavator!=0)?$excavator / $vol_excavator * 1:0;
			$harsat_transfer_semen = ($transfer_semen!=0)?$transfer_semen / $vol_transfer_semen * 1:0;
			$harsat_bbm_solar = ($vol_bbm_solar!=0)?$bbm_solar / $vol_bbm_solar * 1:0;
			$total_nilai_rap_alat = $batching_plant + $truck_mixer + $wheel_loader + $excavator + $transfer_semen + $bbm_solar;

			$pemakaian_vol_batching_plant = 0;
			$pemakaian_vol_pemeliharaan_batching_plant = 0;
			$pemakaian_vol_penyusutan_batching_plant = $total_volume;
			$pemakaian_vol_truck_mixer = $total_vol_truck_mixer;
			$pemakaian_vol_wheel_loader = 0;
			$pemakaian_vol_pemeliharaan_wheel_loader = 0;
			$pemakaian_vol_penyusutan_wheel_loader = $pemakaian_vol_pemeliharaan_wheel_loader;
			$pemakaian_vol_excavator = $total_vol_excavator;
			$pemakaian_vol_transfer_semen = $total_vol_transfer_semen;
			$pemakaian_vol_bbm_solar = $total_volume_pemakaian_solar;
			
			//SPESIAL//
			$total_pemakaian_pemeliharaan_batching_plant = $total_nilai_pemeliharaan_batching_plant;
			$total_pemakaian_penyusutan_batching_plant = $penyusutan_batching_plant;
			$total_pemakaian_batching_plant = $total_nilai_batching_plant + $total_pemakaian_penyusutan_batching_plant;
			$total_pemakaian_truck_mixer = $total_nilai_truck_mixer;
			$total_pemakaian_pemeliharaan_wheel_loader = $total_nilai_pemeliharaan_wheel_loader;
			$total_pemakaian_penyusutan_wheel_loader = $penyusutan_wheel_loader;
			$total_pemakaian_wheel_loader = $total_nilai_wheel_loader + $total_pemakaian_penyusutan_wheel_loader;
			$total_pemakaian_excavator = $total_nilai_excavator;
			$total_pemakaian_transfer_semen = $total_nilai_transfer_semen;
			$total_pemakaian_bbm_solar = $total_akumulasi_bbm;
			//SPESIAL//
	
			$total_vol_evaluasi_batching_plant = ($pemakaian_vol_batching_plant!=0)?$vol_batching_plant - $pemakaian_vol_batching_plant * 1:0;
			$total_nilai_evaluasi_batching_plant = ($total_pemakaian_batching_plant!=0)?$batching_plant - $total_pemakaian_batching_plant * 1:0;
			$total_vol_evaluasi_pemeliharaan_batching_plant = ($pemakaian_vol_pemeliharaan_batching_plant!=0)?$vol_pemeliharaan_batching_plant - $pemakaian_vol_pemeliharaan_batching_plant * 1:0;
			$total_nilai_evaluasi_pemeliharaan_batching_plant = ($total_pemakaian_pemeliharaan_batching_plant!=0)?$pemeliharaan_batching_plant - $total_pemakaian_pemeliharaan_batching_plant * 1:0;
			$total_vol_evaluasi_penyusutan_batching_plant = ($pemakaian_vol_penyusutan_batching_plant!=0)?$vol_penyusutan_batching_plant - $pemakaian_vol_penyusutan_batching_plant * 1:0;
			$total_nilai_evaluasi_penyusutan_batching_plant = ($total_pemakaian_penyusutan_batching_plant!=0)?$penyusutan_batching_plant - $total_pemakaian_penyusutan_batching_plant * 1:0;
			$total_vol_evaluasi_truck_mixer = ($pemakaian_vol_truck_mixer!=0)?$vol_truck_mixer - $pemakaian_vol_truck_mixer * 1:0;
			$total_nilai_evaluasi_truck_mixer = ($total_pemakaian_truck_mixer!=0)?$truck_mixer - $total_pemakaian_truck_mixer * 1:0;
			$total_vol_evaluasi_wheel_loader = ($pemakaian_vol_wheel_loader!=0)?$vol_wheel_loader - $pemakaian_vol_wheel_loader * 1:0;
			$total_nilai_evaluasi_wheel_loader = ($total_pemakaian_wheel_loader!=0)?$wheel_loader - $total_pemakaian_wheel_loader * 1:0;
			$total_vol_evaluasi_pemeliharaan_wheel_loader = ($pemakaian_vol_pemeliharaan_wheel_loader!=0)?$vol_pemeliharaan_wheel_loader - $pemakaian_vol_pemeliharaan_wheel_loader * 1:0;
			$total_nilai_evaluasi_pemeliharaan_wheel_loader = ($total_pemakaian_pemeliharaan_wheel_loader!=0)?$pemeliharaan_wheel_loader - $total_pemakaian_pemeliharaan_wheel_loader * 1:0;
			$total_vol_evaluasi_penyusutan_wheel_loader = ($pemakaian_vol_penyusutan_wheel_loader!=0)?$vol_penyusutan_wheel_loader - $pemakaian_vol_penyusutan_wheel_loader * 1:0;
			$total_nilai_evaluasi_penyusutan_wheel_loader = ($total_pemakaian_penyusutan_wheel_loader!=0)?$penyusutan_wheel_loader - $total_pemakaian_penyusutan_wheel_loader * 1:0;
			$total_vol_evaluasi_excavator = ($pemakaian_vol_excavator!=0)?$vol_excavator - $pemakaian_vol_excavator * 1:0;
			$total_nilai_evaluasi_excavator = ($total_pemakaian_excavator!=0)?$excavator - $total_pemakaian_excavator * 1:0;
			$total_vol_evaluasi_transfer_semen = ($pemakaian_vol_transfer_semen!=0)?$vol_transfer_semen - $pemakaian_vol_transfer_semen * 1:0;
			$total_nilai_evaluasi_transfer_semen = ($total_pemakaian_transfer_semen!=0)?$transfer_semen - $total_pemakaian_transfer_semen * 1:0;
			$total_vol_evaluasi_bbm_solar = ($pemakaian_volume_solar!=0)?($vol_rap_bbm_solar * $total_volume) - $pemakaian_volume_solar * 1:0;
			$total_nilai_evaluasi_bbm_solar = ($pemakaian_nilai_solar!=0)?$bbm_solar - $pemakaian_nilai_solar * 1:0;

			$total_vol_rap_alat = $total_volume;
			$total_nilai_rap_alat = $batching_plant + $truck_mixer + $wheel_loader + $excavator + $transfer_semen + $bbm_solar;
			$total_vol_realisasi_alat = $pemakaian_vol_batching_plant + $pemakaian_vol_truck_mixer + $pemakaian_vol_wheel_loader + $pemakaian_vol_excavator + $pemakaian_vol_transfer_semen + $pemakaian_volume_solar;
			$total_nilai_realisasi_alat = $total_pemakaian_batching_plant + $total_pemakaian_truck_mixer + $total_pemakaian_wheel_loader + $total_pemakaian_excavator + $total_nilai_transfer_semen + $pemakaian_nilai_solar;
			$total_vol_evaluasi_alat = $total_vol_evaluasi_batching_plant + $total_vol_evaluasi_truck_mixer + $total_vol_evaluasi_wheel_loader + $total_vol_evaluasi_excavator + $total_vol_evaluasi_transfer_semen + $total_vol_evaluasi_bbm_solar;
			$total_nilai_evaluasi_alat = $total_nilai_evaluasi_batching_plant + $total_nilai_evaluasi_truck_mixer + $total_nilai_evaluasi_wheel_loader + $total_nilai_evaluasi_excavator + $total_nilai_evaluasi_transfer_semen + $total_nilai_evaluasi_bbm_solar;
			?>

			<?php
			$rap_gaji_upah = $this->db->select('rap.*,sum(det.jumlah) as total')
			->from('rap_bua rap')
			->join('rap_bua_detail det','rap.id = det.rap_bua_id','left')
			->where("rap.status = 'PUBLISH'")
			->where("det.coa in ('114','115')")
			->where("rap.tanggal_rap_bua < '$date2'")
			->order_by('rap.tanggal_rap_bua','asc')->limit(1)
			->get()->row_array();

			$rap_konsumsi = $this->db->select('rap.*,sum(det.jumlah) as total')
			->from('rap_bua rap')
			->join('rap_bua_detail det','rap.id = det.rap_bua_id','left')
			->where("rap.status = 'PUBLISH'")
			->where("det.coa = 116")
			->where("rap.tanggal_rap_bua < '$date2'")
			->order_by('rap.tanggal_rap_bua','asc')->limit(1)
			->get()->row_array();

			$rap_biaya_sewa_mess = $this->db->select('rap.*,sum(det.jumlah) as total')
			->from('rap_bua rap')
			->join('rap_bua_detail det','rap.id = det.rap_bua_id','left')
			->where("rap.status = 'PUBLISH'")
			->where("det.coa = 119")
			->where("rap.tanggal_rap_bua < '$date2'")
			->order_by('rap.tanggal_rap_bua','asc')->limit(1)
			->get()->row_array();

			$rap_listrik_internet = $this->db->select('rap.*,sum(det.jumlah) as total')
			->from('rap_bua rap')
			->join('rap_bua_detail det','rap.id = det.rap_bua_id','left')
			->where("rap.status = 'PUBLISH'")
			->where("det.coa = 118")
			->where("rap.tanggal_rap_bua < '$date2'")
			->order_by('rap.tanggal_rap_bua','asc')->limit(1)
			->get()->row_array();

			$rap_pengujian_material_laboratorium = $this->db->select('rap.*,sum(det.jumlah) as total')
			->from('rap_bua rap')
			->join('rap_bua_detail det','rap.id = det.rap_bua_id','left')
			->where("rap.status = 'PUBLISH'")
			->where("det.coa = 120")
			->where("rap.tanggal_rap_bua < '$date2'")
			->order_by('rap.tanggal_rap_bua','asc')->limit(1)
			->get()->row_array();

			$rap_keamanan_kebersihan = $this->db->select('rap.*,sum(det.jumlah) as total')
			->from('rap_bua rap')
			->join('rap_bua_detail det','rap.id = det.rap_bua_id','left')
			->where("rap.status = 'PUBLISH'")
			->where("det.coa = 97")
			->where("rap.tanggal_rap_bua < '$date2'")
			->order_by('rap.tanggal_rap_bua','asc')->limit(1)
			->get()->row_array();

			$rap_pengobatan = $this->db->select('rap.*,sum(det.jumlah) as total')
			->from('rap_bua rap')
			->join('rap_bua_detail det','rap.id = det.rap_bua_id','left')
			->where("rap.status = 'PUBLISH'")
			->where("det.coa = 70")
			->where("rap.tanggal_rap_bua < '$date2'")
			->order_by('rap.tanggal_rap_bua','asc')->limit(1)
			->get()->row_array();

			$rap_donasi = $this->db->select('rap.*,sum(det.jumlah) as total')
			->from('rap_bua rap')
			->join('rap_bua_detail det','rap.id = det.rap_bua_id','left')
			->where("rap.status = 'PUBLISH'")
			->where("det.coa = 76")
			->where("rap.tanggal_rap_bua < '$date2'")
			->order_by('rap.tanggal_rap_bua','asc')->limit(1)
			->get()->row_array();

			$rap_bensin_tol_parkir = $this->db->select('rap.*,sum(det.jumlah) as total')
			->from('rap_bua rap')
			->join('rap_bua_detail det','rap.id = det.rap_bua_id','left')
			->where("rap.status = 'PUBLISH'")
			->where("det.coa = 78")
			->where("rap.tanggal_rap_bua < '$date2'")
			->order_by('rap.tanggal_rap_bua','asc')->limit(1)
			->get()->row_array();

			$rap_perjalanan_dinas_penjualan = $this->db->select('rap.*,sum(det.jumlah) as total')
			->from('rap_bua rap')
			->join('rap_bua_detail det','rap.id = det.rap_bua_id','left')
			->where("rap.status = 'PUBLISH'")
			->where("det.coa = 62")
			->where("rap.tanggal_rap_bua < '$date2'")
			->order_by('rap.tanggal_rap_bua','asc')->limit(1)
			->get()->row_array();

			$rap_pakaian_dinas = $this->db->select('rap.*,sum(det.jumlah) as total')
			->from('rap_bua rap')
			->join('rap_bua_detail det','rap.id = det.rap_bua_id','left')
			->where("rap.status = 'PUBLISH'")
			->where("det.coa = 87")
			->where("rap.tanggal_rap_bua < '$date2'")
			->order_by('rap.tanggal_rap_bua','asc')->limit(1)
			->get()->row_array();

			$rap_alat_tulis_kantor = $this->db->select('rap.*,sum(det.jumlah) as total')
			->from('rap_bua rap')
			->join('rap_bua_detail det','rap.id = det.rap_bua_id','left')
			->where("rap.status = 'PUBLISH'")
			->where("det.coa = 96")
			->where("rap.tanggal_rap_bua < '$date2'")
			->order_by('rap.tanggal_rap_bua','asc')->limit(1)
			->get()->row_array();

			$rap_perlengkapan_kantor = $this->db->select('rap.*,sum(det.jumlah) as total')
			->from('rap_bua rap')
			->join('rap_bua_detail det','rap.id = det.rap_bua_id','left')
			->where("rap.status = 'PUBLISH'")
			->where("det.coa = 98")
			->where("rap.tanggal_rap_bua < '$date2'")
			->order_by('rap.tanggal_rap_bua','asc')->limit(1)
			->get()->row_array();

			$rap_beban_kirim = $this->db->select('rap.*,sum(det.jumlah) as total')
			->from('rap_bua rap')
			->join('rap_bua_detail det','rap.id = det.rap_bua_id','left')
			->where("rap.status = 'PUBLISH'")
			->where("det.coa = 93")
			->where("rap.tanggal_rap_bua < '$date2'")
			->order_by('rap.tanggal_rap_bua','asc')->limit(1)
			->get()->row_array();

			$rap_beban_lain_lain = $this->db->select('rap.*,sum(det.jumlah) as total')
			->from('rap_bua rap')
			->join('rap_bua_detail det','rap.id = det.rap_bua_id','left')
			->where("rap.status = 'PUBLISH'")
			->where("det.coa = 94")
			->where("rap.tanggal_rap_bua < '$date2'")
			->order_by('rap.tanggal_rap_bua','asc')->limit(1)
			->get()->row_array();

			$rap_biaya_sewa_kendaraan = $this->db->select('rap.*,sum(det.jumlah) as total')
			->from('rap_bua rap')
			->join('rap_bua_detail det','rap.id = det.rap_bua_id','left')
			->where("rap.status = 'PUBLISH'")
			->where("det.coa = 100")
			->where("rap.tanggal_rap_bua < '$date2'")
			->order_by('rap.tanggal_rap_bua','asc')->limit(1)
			->get()->row_array();

			$rap_thr_bonus = $this->db->select('rap.*,sum(det.jumlah) as total')
			->from('rap_bua rap')
			->join('rap_bua_detail det','rap.id = det.rap_bua_id','left')
			->where("rap.status = 'PUBLISH'")
			->where("det.coa = 117")
			->where("rap.tanggal_rap_bua < '$date2'")
			->order_by('rap.tanggal_rap_bua','asc')->limit(1)
			->get()->row_array();

			$rap_biaya_admin_bank = $this->db->select('rap.*,sum(det.jumlah) as total')
			->from('rap_bua rap')
			->join('rap_bua_detail det','rap.id = det.rap_bua_id','left')
			->where("rap.status = 'PUBLISH'")
			->where("det.coa = 91")
			->where("rap.tanggal_rap_bua < '$date2'")
			->order_by('rap.tanggal_rap_bua','asc')->limit(1)
			->get()->row_array();
			
			//REALISASI
			$gaji_upah_biaya = $this->db->select('sum(pdb.jumlah) as total')
			->from('pmm_biaya pb ')
			->join('pmm_detail_biaya pdb','pb.id = pdb.biaya_id','left')
			->join('pmm_coa c','pdb.akun = c.id','left')
			->where("pdb.akun in ('114','115')")
			->where("pb.status = 'PAID'")
			->where("(pb.tanggal_transaksi between '$date1' and '$date2')")
			->get()->row_array();

			$gaji_upah_jurnal = $this->db->select('sum(pdb.debit) as total')
			->from('pmm_jurnal_umum pb ')
			->join('pmm_detail_jurnal pdb','pb.id = pdb.jurnal_id','left')
			->join('pmm_coa c','pdb.akun = c.id','left')
			->where("pdb.akun in ('114','115')")
			->where("pb.status = 'PAID'")
			->where("(pb.tanggal_transaksi between '$date1' and '$date2')")
			->get()->row_array();
			$gaji_upah = $gaji_upah_biaya['total'] + $gaji_upah_jurnal['total'];

			$konsumsi_biaya = $this->db->select('sum(pdb.jumlah) as total')
			->from('pmm_biaya pb ')
			->join('pmm_detail_biaya pdb','pb.id = pdb.biaya_id','left')
			->join('pmm_coa c','pdb.akun = c.id','left')
			->where("pdb.akun = 116")
			->where("pb.status = 'PAID'")
			->where("(pb.tanggal_transaksi between '$date1' and '$date2')")
			->get()->row_array();

			$konsumsi_jurnal = $this->db->select('sum(pdb.debit) as total')
			->from('pmm_jurnal_umum pb ')
			->join('pmm_detail_jurnal pdb','pb.id = pdb.jurnal_id','left')
			->join('pmm_coa c','pdb.akun = c.id','left')
			->where("pdb.akun = 116")
			->where("pb.status = 'PAID'")
			->where("(pb.tanggal_transaksi between '$date1' and '$date2')")
			->get()->row_array();
			$konsumsi = $konsumsi_biaya['total'] + $konsumsi_jurnal['total'];

			$biaya_sewa_mess_biaya = $this->db->select('sum(pdb.jumlah) as total')
			->from('pmm_biaya pb ')
			->join('pmm_detail_biaya pdb','pb.id = pdb.biaya_id','left')
			->join('pmm_coa c','pdb.akun = c.id','left')
			->where("pdb.akun = 119")
			->where("pb.status = 'PAID'")
			->where("(pb.tanggal_transaksi between '$date1' and '$date2')")
			->get()->row_array();

			$biaya_sewa_mess_jurnal = $this->db->select('sum(pdb.debit) as total')
			->from('pmm_jurnal_umum pb ')
			->join('pmm_detail_jurnal pdb','pb.id = pdb.jurnal_id','left')
			->join('pmm_coa c','pdb.akun = c.id','left')
			->where("pdb.akun = 119")
			->where("pb.status = 'PAID'")
			->where("(pb.tanggal_transaksi between '$date1' and '$date2')")
			->get()->row_array();
			$biaya_sewa_mess = $biaya_sewa_mess_biaya['total'] + $biaya_sewa_mess_jurnal['total'];

			$listrik_internet_biaya = $this->db->select('sum(pdb.jumlah) as total')
			->from('pmm_biaya pb ')
			->join('pmm_detail_biaya pdb','pb.id = pdb.biaya_id','left')
			->join('pmm_coa c','pdb.akun = c.id','left')
			->where("pdb.akun = 118")
			->where("pb.status = 'PAID'")
			->where("(pb.tanggal_transaksi between '$date1' and '$date2')")
			->get()->row_array();

			$listrik_internet_jurnal = $this->db->select('sum(pdb.debit) as total')
			->from('pmm_jurnal_umum pb ')
			->join('pmm_detail_jurnal pdb','pb.id = pdb.jurnal_id','left')
			->join('pmm_coa c','pdb.akun = c.id','left')
			->where("pdb.akun = 118")
			->where("pb.status = 'PAID'")
			->where("(pb.tanggal_transaksi between '$date1' and '$date2')")
			->get()->row_array();
			$listrik_internet = $listrik_internet_biaya['total'] + $listrik_internet_jurnal['total'];

			$pengujian_material_laboratorium_biaya = $this->db->select('sum(pdb.jumlah) as total')
			->from('pmm_biaya pb ')
			->join('pmm_detail_biaya pdb','pb.id = pdb.biaya_id','left')
			->join('pmm_coa c','pdb.akun = c.id','left')
			->where("pdb.akun = 120")
			->where("pb.status = 'PAID'")
			->where("(pb.tanggal_transaksi between '$date1' and '$date2')")
			->get()->row_array();

			$pengujian_material_laboratorium_jurnal = $this->db->select('sum(pdb.debit) as total')
			->from('pmm_jurnal_umum pb ')
			->join('pmm_detail_jurnal pdb','pb.id = pdb.jurnal_id','left')
			->join('pmm_coa c','pdb.akun = c.id','left')
			->where("pdb.akun = 120")
			->where("pb.status = 'PAID'")
			->where("(pb.tanggal_transaksi between '$date1' and '$date2')")
			->get()->row_array();
			$pengujian_material_laboratorium = $pengujian_material_laboratorium_biaya['total'] + $pengujian_material_laboratorium_jurnal['total'];

			$keamanan_kebersihan_biaya = $this->db->select('sum(pdb.jumlah) as total')
			->from('pmm_biaya pb ')
			->join('pmm_detail_biaya pdb','pb.id = pdb.biaya_id','left')
			->join('pmm_coa c','pdb.akun = c.id','left')
			->where("pdb.akun = 97")
			->where("pb.status = 'PAID'")
			->where("(pb.tanggal_transaksi between '$date1' and '$date2')")
			->get()->row_array();

			$keamanan_kebersihan_jurnal = $this->db->select('sum(pdb.debit) as total')
			->from('pmm_jurnal_umum pb ')
			->join('pmm_detail_jurnal pdb','pb.id = pdb.jurnal_id','left')
			->join('pmm_coa c','pdb.akun = c.id','left')
			->where("pdb.akun = 97")
			->where("pb.status = 'PAID'")
			->where("(pb.tanggal_transaksi between '$date1' and '$date2')")
			->get()->row_array();
			$keamanan_kebersihan = $keamanan_kebersihan_biaya['total'] + $keamanan_kebersihan_jurnal['total'];

			$pengobatan_biaya = $this->db->select('sum(pdb.jumlah) as total')
			->from('pmm_biaya pb ')
			->join('pmm_detail_biaya pdb','pb.id = pdb.biaya_id','left')
			->join('pmm_coa c','pdb.akun = c.id','left')
			->where("pdb.akun = 70")
			->where("pb.status = 'PAID'")
			->where("(pb.tanggal_transaksi between '$date1' and '$date2')")
			->get()->row_array();

			$pengobatan_jurnal = $this->db->select('sum(pdb.debit) as total')
			->from('pmm_jurnal_umum pb ')
			->join('pmm_detail_jurnal pdb','pb.id = pdb.jurnal_id','left')
			->join('pmm_coa c','pdb.akun = c.id','left')
			->where("pdb.akun = 70")
			->where("pb.status = 'PAID'")
			->where("(pb.tanggal_transaksi between '$date1' and '$date2')")
			->get()->row_array();
			$pengobatan = $pengobatan_biaya['total'] + $pengobatan_jurnal['total'];

			$donasi_biaya = $this->db->select('sum(pdb.jumlah) as total')
			->from('pmm_biaya pb ')
			->join('pmm_detail_biaya pdb','pb.id = pdb.biaya_id','left')
			->join('pmm_coa c','pdb.akun = c.id','left')
			->where("pdb.akun = 76")
			->where("pb.status = 'PAID'")
			->where("(pb.tanggal_transaksi between '$date1' and '$date2')")
			->get()->row_array();

			$donasi_jurnal = $this->db->select('sum(pdb.debit) as total')
			->from('pmm_jurnal_umum pb ')
			->join('pmm_detail_jurnal pdb','pb.id = pdb.jurnal_id','left')
			->join('pmm_coa c','pdb.akun = c.id','left')
			->where("pdb.akun = 76")
			->where("pb.status = 'PAID'")
			->where("(pb.tanggal_transaksi between '$date1' and '$date2')")
			->get()->row_array();
			$donasi = $donasi_biaya['total'] + $donasi_jurnal['total'];

			$bensin_tol_parkir_biaya = $this->db->select('sum(pdb.jumlah) as total')
			->from('pmm_biaya pb ')
			->join('pmm_detail_biaya pdb','pb.id = pdb.biaya_id','left')
			->join('pmm_coa c','pdb.akun = c.id','left')
			->where("pdb.akun = 78")
			->where("pb.status = 'PAID'")
			->where("(pb.tanggal_transaksi between '$date1' and '$date2')")
			->get()->row_array();

			$bensin_tol_parkir_jurnal = $this->db->select('sum(pdb.debit) as total')
			->from('pmm_jurnal_umum pb ')
			->join('pmm_detail_jurnal pdb','pb.id = pdb.jurnal_id','left')
			->join('pmm_coa c','pdb.akun = c.id','left')
			->where("pdb.akun = 78")
			->where("pb.status = 'PAID'")
			->where("(pb.tanggal_transaksi between '$date1' and '$date2')")
			->get()->row_array();
			$bensin_tol_parkir = $bensin_tol_parkir_biaya['total'] + $bensin_tol_parkir_jurnal['total'];

			$perjalanan_dinas_penjualan_biaya = $this->db->select('sum(pdb.jumlah) as total')
			->from('pmm_biaya pb ')
			->join('pmm_detail_biaya pdb','pb.id = pdb.biaya_id','left')
			->join('pmm_coa c','pdb.akun = c.id','left')
			->where("pdb.akun = 62")
			->where("pb.status = 'PAID'")
			->where("(pb.tanggal_transaksi between '$date1' and '$date2')")
			->get()->row_array();

			$perjalanan_dinas_penjualan_jurnal = $this->db->select('sum(pdb.debit) as total')
			->from('pmm_jurnal_umum pb ')
			->join('pmm_detail_jurnal pdb','pb.id = pdb.jurnal_id','left')
			->join('pmm_coa c','pdb.akun = c.id','left')
			->where("pdb.akun = 62")
			->where("pb.status = 'PAID'")
			->where("(pb.tanggal_transaksi between '$date1' and '$date2')")
			->get()->row_array();
			$perjalanan_dinas_penjualan = $perjalanan_dinas_penjualan_biaya['total'] + $perjalanan_dinas_penjualan_jurnal['total'];

			$pakaian_dinas_biaya = $this->db->select('sum(pdb.jumlah) as total')
			->from('pmm_biaya pb ')
			->join('pmm_detail_biaya pdb','pb.id = pdb.biaya_id','left')
			->join('pmm_coa c','pdb.akun = c.id','left')
			->where("pdb.akun = 87")
			->where("pb.status = 'PAID'")
			->where("(pb.tanggal_transaksi between '$date1' and '$date2')")
			->get()->row_array();

			$pakaian_dinas_jurnal = $this->db->select('sum(pdb.debit) as total')
			->from('pmm_jurnal_umum pb ')
			->join('pmm_detail_jurnal pdb','pb.id = pdb.jurnal_id','left')
			->join('pmm_coa c','pdb.akun = c.id','left')
			->where("pdb.akun = 87")
			->where("pb.status = 'PAID'")
			->where("(pb.tanggal_transaksi between '$date1' and '$date2')")
			->get()->row_array();
			$pakaian_dinas = $pakaian_dinas_biaya['total'] + $pakaian_dinas_jurnal['total'];

			$alat_tulis_kantor_biaya = $this->db->select('sum(pdb.jumlah) as total')
			->from('pmm_biaya pb ')
			->join('pmm_detail_biaya pdb','pb.id = pdb.biaya_id','left')
			->join('pmm_coa c','pdb.akun = c.id','left')
			->where("pdb.akun = 96")
			->where("pb.status = 'PAID'")
			->where("(pb.tanggal_transaksi between '$date1' and '$date2')")
			->get()->row_array();

			$alat_tulis_kantor_jurnal = $this->db->select('sum(pdb.debit) as total')
			->from('pmm_jurnal_umum pb ')
			->join('pmm_detail_jurnal pdb','pb.id = pdb.jurnal_id','left')
			->join('pmm_coa c','pdb.akun = c.id','left')
			->where("pdb.akun = 96")
			->where("pb.status = 'PAID'")
			->where("(pb.tanggal_transaksi between '$date1' and '$date2')")
			->get()->row_array();
			$alat_tulis_kantor = $alat_tulis_kantor_biaya['total'] + $alat_tulis_kantor_jurnal['total'];

			$perlengkapan_kantor_biaya = $this->db->select('sum(pdb.jumlah) as total')
			->from('pmm_biaya pb ')
			->join('pmm_detail_biaya pdb','pb.id = pdb.biaya_id','left')
			->join('pmm_coa c','pdb.akun = c.id','left')
			->where("pdb.akun = 98")
			->where("pb.status = 'PAID'")
			->where("(pb.tanggal_transaksi between '$date1' and '$date2')")
			->get()->row_array();

			$perlengkapan_kantor_jurnal = $this->db->select('sum(pdb.debit) as total')
			->from('pmm_jurnal_umum pb ')
			->join('pmm_detail_jurnal pdb','pb.id = pdb.jurnal_id','left')
			->join('pmm_coa c','pdb.akun = c.id','left')
			->where("pdb.akun = 98")
			->where("pb.status = 'PAID'")
			->where("(pb.tanggal_transaksi between '$date1' and '$date2')")
			->get()->row_array();
			$perlengkapan_kantor = $perlengkapan_kantor_biaya['total'] + $perlengkapan_kantor_jurnal['total'];

			$beban_kirim_biaya = $this->db->select('sum(pdb.jumlah) as total')
			->from('pmm_biaya pb ')
			->join('pmm_detail_biaya pdb','pb.id = pdb.biaya_id','left')
			->join('pmm_coa c','pdb.akun = c.id','left')
			->where("pdb.akun = 93")
			->where("pb.status = 'PAID'")
			->where("(pb.tanggal_transaksi between '$date1' and '$date2')")
			->get()->row_array();

			$beban_kirim_jurnal = $this->db->select('sum(pdb.debit) as total')
			->from('pmm_jurnal_umum pb ')
			->join('pmm_detail_jurnal pdb','pb.id = pdb.jurnal_id','left')
			->join('pmm_coa c','pdb.akun = c.id','left')
			->where("pdb.akun = 93")
			->where("pb.status = 'PAID'")
			->where("(pb.tanggal_transaksi between '$date1' and '$date2')")
			->get()->row_array();
			$beban_kirim = $beban_kirim_biaya['total'] + $beban_kirim_jurnal['total'];

			$beban_lain_lain_biaya = $this->db->select('sum(pdb.jumlah) as total')
			->from('pmm_biaya pb ')
			->join('pmm_detail_biaya pdb','pb.id = pdb.biaya_id','left')
			->join('pmm_coa c','pdb.akun = c.id','left')
			->where("pdb.akun = 94")
			->where("pb.status = 'PAID'")
			->where("(pb.tanggal_transaksi between '$date1' and '$date2')")
			->get()->row_array();

			$beban_lain_lain_jurnal = $this->db->select('sum(pdb.debit) as total')
			->from('pmm_jurnal_umum pb ')
			->join('pmm_detail_jurnal pdb','pb.id = pdb.jurnal_id','left')
			->join('pmm_coa c','pdb.akun = c.id','left')
			->where("pdb.akun = 94")
			->where("pb.status = 'PAID'")
			->where("(pb.tanggal_transaksi between '$date1' and '$date2')")
			->get()->row_array();
			$beban_lain_lain = $beban_lain_lain_biaya['total'] + $beban_lain_lain_jurnal['total'];

			$biaya_sewa_kendaraan_biaya = $this->db->select('sum(pdb.jumlah) as total')
			->from('pmm_biaya pb ')
			->join('pmm_detail_biaya pdb','pb.id = pdb.biaya_id','left')
			->join('pmm_coa c','pdb.akun = c.id','left')
			->where("pdb.akun = 100")
			->where("pb.status = 'PAID'")
			->where("(pb.tanggal_transaksi between '$date1' and '$date2')")
			->get()->row_array();

			$biaya_sewa_kendaraan_jurnal = $this->db->select('sum(pdb.debit) as total')
			->from('pmm_jurnal_umum pb ')
			->join('pmm_detail_jurnal pdb','pb.id = pdb.jurnal_id','left')
			->join('pmm_coa c','pdb.akun = c.id','left')
			->where("pdb.akun = 100")
			->where("pb.status = 'PAID'")
			->where("(pb.tanggal_transaksi between '$date1' and '$date2')")
			->get()->row_array();
			$biaya_sewa_kendaraan = $biaya_sewa_kendaraan_biaya['total'] + $biaya_sewa_kendaraan_jurnal['total'];

			$thr_bonus_biaya = $this->db->select('sum(pdb.jumlah) as total')
			->from('pmm_biaya pb ')
			->join('pmm_detail_biaya pdb','pb.id = pdb.biaya_id','left')
			->join('pmm_coa c','pdb.akun = c.id','left')
			->where("pdb.akun = 117")
			->where("pb.status = 'PAID'")
			->where("(pb.tanggal_transaksi between '$date1' and '$date2')")
			->get()->row_array();

			$thr_bonus_jurnal = $this->db->select('sum(pdb.debit) as total')
			->from('pmm_jurnal_umum pb ')
			->join('pmm_detail_jurnal pdb','pb.id = pdb.jurnal_id','left')
			->join('pmm_coa c','pdb.akun = c.id','left')
			->where("pdb.akun = 117")
			->where("pb.status = 'PAID'")
			->where("(pb.tanggal_transaksi between '$date1' and '$date2')")
			->get()->row_array();
			$thr_bonus = $thr_bonus_biaya['total'] + $thr_bonus_jurnal['total'];

			$biaya_admin_bank_biaya = $this->db->select('sum(pdb.jumlah) as total')
			->from('pmm_biaya pb ')
			->join('pmm_detail_biaya pdb','pb.id = pdb.biaya_id','left')
			->join('pmm_coa c','pdb.akun = c.id','left')
			->where("pdb.akun = 91")
			->where("pb.status = 'PAID'")
			->where("(pb.tanggal_transaksi between '$date1' and '$date2')")
			->get()->row_array();

			$biaya_admin_bank_jurnal = $this->db->select('sum(pdb.debit) as total')
			->from('pmm_jurnal_umum pb ')
			->join('pmm_detail_jurnal pdb','pb.id = pdb.jurnal_id','left')
			->join('pmm_coa c','pdb.akun = c.id','left')
			->where("pdb.akun = 91")
			->where("pb.status = 'PAID'")
			->where("(pb.tanggal_transaksi between '$date1' and '$date2')")
			->get()->row_array();
			$biaya_admin_bank = $biaya_admin_bank_biaya['total'] + $biaya_admin_bank_jurnal['total'];

			$biaya_persiapan_biaya = $this->db->select('sum(pdb.jumlah) as total')
			->from('pmm_biaya pb ')
			->join('pmm_detail_biaya pdb','pb.id = pdb.biaya_id','left')
			->join('pmm_coa c','pdb.akun = c.id','left')
			->where("pdb.akun = 131")
			->where("pb.status = 'PAID'")
			->where("(pb.tanggal_transaksi between '$date1' and '$date2')")
			->get()->row_array();

			$biaya_persiapan_jurnal = $this->db->select('sum(pdb.debit) as total')
			->from('pmm_jurnal_umum pb ')
			->join('pmm_detail_jurnal pdb','pb.id = pdb.jurnal_id','left')
			->join('pmm_coa c','pdb.akun = c.id','left')
			->where("pdb.akun = 131")
			->where("pb.status = 'PAID'")
			->where("(pb.tanggal_transaksi between '$date1' and '$date2')")
			->get()->row_array();
			$biaya_persiapan = $biaya_persiapan_biaya['total'] + $biaya_persiapan_jurnal['total'];

			$total_volume_rap_bua = $total_volume;
			$total_nilai_rap_bua = ($rap_gaji_upah['total'] + $rap_konsumsi['total'] + $rap_biaya_sewa_mess['total'] + $rap_listrik_internet['total'] + $rap_pengujian_material_laboratorium['total'] + $rap_keamanan_kebersihan['total'] + $rap_pengobatan['total'] + $rap_donasi['total'] + $rap_bensin_tol_parkir['total'] + $rap_perjalanan_dinas_penjualan['total'] + $rap_pakaian_dinas['total'] + $rap_alat_tulis_kantor['total'] + $rap_perlengkapan_kantor['total'] + $rap_beban_kirim['total'] + $rap_beban_lain_lain['total'] + $rap_biaya_sewa_kendaraan['total'] + $rap_thr_bonus['total'] + $rap_biaya_admin_bank['total']) / 24;
			$total_harsat_rap_bua = $total_nilai_rap_bua / $total_volume_rap_bua;
			
			$total_volume_realisasi_bua = $total_volume;
			$total_nilai_realisasi_bua = $gaji_upah + $konsumsi + $biaya_sewa_mess + $listrik_internet + $pengujian_material_laboratorium + $keamanan_kebersihan + $pengobatan + $donasi + $bensin_tol_parkir + $perjalanan_dinas_penjualan + $pakaian_dinas + $alat_tulis_kantor + $perlengkapan_kantor + $beban_kirim + $beban_lain_lain + $biaya_sewa_kendaraan + $thr_bonus + $biaya_admin_bank + $biaya_persiapan;
			$total_harsat_realisasi_bua = $total_nilai_realisasi_bua / $total_volume_realisasi_bua;

			$total_volume_evaluasi_bua = $total_volume_rap_bua - $total_volume_realisasi_bua;
			$total_nilai_evaluasi_bua = $total_nilai_rap_bua - $total_nilai_realisasi_bua;
			?>

			<tr class="table-active">
	            <th class="text-center" rowspan="2" style="vertical-align:middle;">NO.</th>
				<th class="text-center" rowspan="2" style="vertical-align:middle;">URAIAN</th>
				<th class="text-center" colspan="3">RAP</th>
				<th class="text-center" colspan="3">REALISASI</th>
				<th class="text-center" colspan="3">DEVIASI</th>
	        </tr>
			<tr class="table-active">
	            <th class="text-center">VOL.</th>
				<th class="text-center">HARSAT</th>
				<th class="text-center">NILAI</th>
				<th class="text-center">VOL.</th>
				<th class="text-center">HARSAT</th>
				<th class="text-center">NILAI</th>
				<th class="text-center">VOL.</th>
				<th class="text-center">NILAI</th>
	        </tr>
			<tr class="table-active3">
	            <th class="text-center">1</th>
				<th class="text-left">BAHAN</th>
				<th class="text-right"><?php echo number_format($total_volume_komposisi,2,',','.');?></th>
				<th class="text-right"><?php echo number_format($total_nilai_komposisi / $total_volume_komposisi,0,',','.');?></th>
				<th class="text-right"><a target="_blank" href="<?= base_url("laporan/cetak_evaluasi_bahan?filter_date=".$filter_date = date('d-m-Y',strtotime($date1)).' - '.date('d-m-Y',strtotime($date2))) ?>"><?php echo number_format($total_nilai_komposisi,0,',','.');?></a></th>
				<th class="text-right"><?php echo number_format($total_volume_realisasi,2,',','.');?></th>
				<th class="text-right"><?php echo number_format($total_nilai_realisasi / $total_volume_realisasi,0,',','.');?></th>
				<th class="text-right"><a target="_blank" href="<?= base_url("laporan/cetak_evaluasi_bahan?filter_date=".$filter_date = date('d-m-Y',strtotime($date1)).' - '.date('d-m-Y',strtotime($date2))) ?>"><?php echo number_format($total_nilai_realisasi,0,',','.');?></a></th>
				<?php
				$styleColor = $total_volume_evaluasi < 0 ? 'color:red' : 'color:black';
				?>
				<th class="text-right" style="<?php echo $styleColor ?>"><?php echo $total_volume_evaluasi < 0 ? "(".number_format(-$total_volume_evaluasi,2,',','.').")" : number_format($total_volume_evaluasi,2,',','.');?></th>
				<?php
				$styleColor = $total_nilai_evaluasi < 0 ? 'color:red' : 'color:black';
				?>
				<th class="text-right" style="<?php echo $styleColor ?>"><?php echo $total_nilai_evaluasi < 0 ? "(".number_format(-$total_nilai_evaluasi,0,',','.').")" : number_format($total_nilai_evaluasi,0,',','.');?></th>
	        </tr>
			<tr class="table-active3">
	            <th class="text-center">2</th>
				<th class="text-left">ALAT</th>
				<th class="text-right"><?php echo number_format($total_vol_rap_alat,2,',','.');?></th>
				<?php
				$total_harsat_rap_alat = (round($total_vol_rap_alat,2)!=0)?($total_nilai_rap_alat / $total_vol_rap_alat) * 1:0;
				?>
				<th class="text-right"><?php echo number_format($total_harsat_rap_alat ,0,',','.');?></th>
				<th class="text-right"><a target="_blank" href="<?= base_url("laporan/cetak_laporan_evaluasi_alat?filter_date=".$filter_date = date('d-m-Y',strtotime($date1)).' - '.date('d-m-Y',strtotime($date2))) ?>"><?php echo number_format($total_nilai_rap_alat,0,',','.');?></a></th>
				<th class="text-right"><?php echo number_format($total_vol_realisasi_alat,2,',','.');?></th>
				<?php
				$total_harsat_realisasi_alat = (round($total_vol_realisasi_alat,2)!=0)?($total_nilai_realisasi_alat / $total_vol_realisasi_alat) * 1:0;
				?>
				<th class="text-right"><?php echo number_format($total_harsat_realisasi_alat,0,',','.');?></th>
				<th class="text-right"><a target="_blank" href="<?= base_url("laporan/cetak_laporan_evaluasi_alat?filter_date=".$filter_date = date('d-m-Y',strtotime($date1)).' - '.date('d-m-Y',strtotime($date2))) ?>"><?php echo number_format($total_nilai_realisasi_alat,0,',','.');?></a></th>
				<?php
				$styleColor = $total_vol_evaluasi_alat < 0 ? 'color:red' : 'color:black';
				?>
				<th class="text-right" style="<?php echo $styleColor ?>"><?php echo $total_vol_evaluasi_alat < 0 ? "(".number_format(-$total_vol_evaluasi_alat,2,',','.').")" : number_format($total_vol_evaluasi_alat,2,',','.');?></th>
				<?php
				$styleColor = $total_nilai_evaluasi_alat < 0 ? 'color:red' : 'color:black';
				?>
				<th class="text-right" style="<?php echo $styleColor ?>"><?php echo $total_nilai_evaluasi_alat < 0 ? "(".number_format(-$total_nilai_evaluasi_alat,0,',','.').")" : number_format($total_nilai_evaluasi_alat,0,',','.');?></th>
	        </tr>
			<tr class="table-active3">
	            <th class="text-center">3</th>
				<th class="text-left">BUA</th>
				<th class="text-right"><?php echo number_format($total_volume_rap_bua,2,',','.');?></th>
				<th class="text-right"><?php echo number_format($total_harsat_rap_bua,0,',','.');?></th>
				<th class="text-right"><a target="_blank" href="<?= base_url("rap/cetak_rap_bua/".'1') ?>"><?php echo number_format($total_nilai_rap_bua,0,',','.');?></a></th>
				<th class="text-right"><?php echo number_format($total_volume_realisasi_bua,2,',','.');?></th>
				<th class="text-right"><?php echo number_format($total_harsat_realisasi_bua,0,',','.');?></th>
				<th class="text-right"><a target="_blank" href="<?= base_url("laporan/cetak_evaluasi_bua?filter_date=".$filter_date = date('d-m-Y',strtotime($date1)).' - '.date('d-m-Y',strtotime($date2))) ?>"><?php echo number_format($total_nilai_realisasi_bua,0,',','.');?></a></th>
				<?php
				$styleColor = $total_volume_evaluasi_bua < 0 ? 'color:red' : 'color:black';
				?>
				<th class="text-right" style="<?php echo $styleColor ?>"><?php echo number_format($total_volume_evaluasi_bua,2,',','.');?></th>
				<?php
				$styleColor = $total_nilai_evaluasi_bua < 0 ? 'color:red' : 'color:black';
				?>
				<th class="text-right" style="<?php echo $styleColor ?>"><?php echo $total_nilai_evaluasi_bua < 0 ? "(".number_format(-$total_nilai_evaluasi_bua,0,',','.').")" : number_format($total_nilai_evaluasi_bua,0,',','.');?></th>
	        </tr>
			<tr class="table-active4">
				<th class="text-right" colspan="2">TOTAL</th>
				<th class="text-right"></th>
				<th class="text-right"></th>
				<?php
				$total_nilai_rap = $total_nilai_komposisi + $total_nilai_rap_alat;
				?>
				<th class="text-right"><?php echo number_format($total_nilai_rap,0,',','.');?></th>
				<th class="text-right"></th>
				<th class="text-right"></th>
				<?php
				$total_nilai_realisasi = $total_nilai_realisasi + $total_nilai_realisasi_alat + $total_nilai_realisasi_bua;
				?>
				<th class="text-right"><?php echo number_format($total_nilai_realisasi,0,',','.');?></th>
				<th class="text-right"></th>
				<?php
				$total_nilai_evaluasi = $total_nilai_evaluasi + $total_nilai_evaluasi_alat + $total_nilai_evaluasi_bua;
				$styleColor = $total_nilai_evaluasi < 0 ? 'color:red' : 'color:black';
				?>
				<th class="text-right" style="<?php echo $styleColor ?>"><?php echo $total_nilai_evaluasi < 0 ? "(".number_format(-$total_nilai_evaluasi,0,',','.').")" : number_format($total_nilai_evaluasi,0,',','.');?></th>
			</tr>
	    </table>
		<?php
	}

}