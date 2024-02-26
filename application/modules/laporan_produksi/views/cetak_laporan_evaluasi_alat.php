<!DOCTYPE html>
<html>
	<head>
	  <title>LAPORAN EVALUASI PEMAKAIAN PERALATAN</title>
	  
	  <?php
		$search = array(
		'January',
		'February',
		'March',
		'April',
		'May',
		'June',
		'July',
		'August',
		'September',
		'October',
		'November',
		'December'
		);
		
		$replace = array(
		'Januari',
		'Februari',
		'Maret',
		'April',
		'Mei',
		'Juni',
		'Juli',
		'Agustus',
		'September',
		'Oktober',
		'November',
		'Desember'
		);
		
		$subject = "$filter_date";

		echo str_replace($search, $replace, $subject);

	  ?>
	  
	  <style type="text/css">
		 body {
			font-family: helvetica;
			font-size: 7px;
		}

		table tr.table-judul{
			background-color: #e69500;
			font-weight: bold;
			font-size: 7px;
			color: black;
		}
			
		table tr.table-baris1{
			background-color: none;
			font-size: 7px;
		}

		table tr.table-baris1-bold{
			background-color: none;
			font-size: 7px;
			font-weight: bold;
		}
			
		table tr.table-total{
			font-weight: bold;
			font-size: 7px;
			color: black;
		}

		table tr.table-total2{
			background-color: #cccccc;
			font-weight: bold;
			font-size: 7px;
			color: black;
		}
	  </style>

	</head>
	<body>
		<div align="center" style="display: block;font-weight: bold;font-size: 11px;">EVALUASI BIAYA PERALATAN</div>
		<div align="center" style="display: block;font-weight: bold;font-size: 11px;">PROYEK BENDUNGAN TIGA DIHAJI</div>
		<div align="center" style="display: block;font-weight: bold;font-size: 11px;">PT. BIA BUMI JAYENDRA</div>
		<div align="center" style="display: block;font-weight: bold;font-size: 11px; text-transform: uppercase;">PERIODE : <?php echo str_replace($search, $replace, $subject);?></div>
		<br /><br /><br />
		<?php
		$data = array();
		
		$arr_date = $this->input->get('filter_date');
		$arr_filter_date = explode(' - ', $arr_date);
		$date1 = '';
		$date2 = '';

		if(count($arr_filter_date) == 2){
			$date1 	= date('Y-m-d',strtotime($arr_filter_date[0]));
			$date2 	= date('Y-m-d',strtotime($arr_filter_date[1]));
			$filter_date = date('d F Y',strtotime($arr_filter_date[0])).' - '.date('d F Y',strtotime($arr_filter_date[1]));
		}
		
		?>
		
		<table width="98%" border="0" cellpadding="3" border="0">
		
			<!-- Pemakaian Peralatan -->
			
			<?php
			//Batching Plant
			$pembelian_batching_plant = $this->db->select('
			pn.nama, po.no_po, po.subject, prm.measure, SUM(prm.volume) as volume, SUM(prm.price) / SUM(prm.volume) as harga_satuan, SUM(prm.price) as price')
			->from('pmm_receipt_material prm')
			->join('pmm_purchase_order po', 'prm.purchase_order_id = po.id','left')
			->join('produk p', 'prm.material_id = p.id','left')
			->join('penerima pn', 'po.supplier_id = pn.id','left')
			->where("prm.date_receipt between '$date1' and '$date2'")
			->where("kategori_alat = '1'")
			->where("po.status in ('PUBLISH','CLOSED')")
			->group_by('prm.harga_satuan')
			->order_by('pn.nama','asc')
			->get()->result_array();

			$total_nilai_batching_plant = 0;
			foreach ($pembelian_batching_plant as $x){
				$total_nilai_batching_plant += $x['price'];
			}

			//Truck Mixer
			$pembelian_truck_mixer = $this->db->select('
			pn.nama, po.no_po, po.subject, prm.measure, SUM(prm.volume) as volume, SUM(prm.price) / SUM(prm.volume) as harga_satuan, SUM(prm.price) as price')
			->from('pmm_receipt_material prm')
			->join('pmm_purchase_order po', 'prm.purchase_order_id = po.id','left')
			->join('produk p', 'prm.material_id = p.id','left')
			->join('penerima pn', 'po.supplier_id = pn.id','left')
			->where("prm.date_receipt between '$date1' and '$date2'")
			->where("kategori_alat = '2'")
			->where("po.status in ('PUBLISH','CLOSED')")
			->group_by('prm.harga_satuan')
			->order_by('pn.nama','asc')
			->get()->result_array();

			$total_nilai_truck_mixer = 0;
			foreach ($pembelian_truck_mixer as $x){
				$total_nilai_truck_mixer += $x['price'];
			}

			$insentif_tm = $this->db->select('pb.memo as memo, sum(pdb.debit) as total')
			->from('pmm_jurnal_umum pb')
			->join('pmm_detail_jurnal pdb','pb.id = pdb.jurnal_id','left')
			->where("pdb.akun = 220")
			->where("pb.status = 'PAID'")
			->where("(pb.tanggal_transaksi between '$date1' and '$date2')")
			->group_by('pdb.id')
			->get()->result_array();

			$total_insentif_tm = 0;
			foreach ($insentif_tm as $y){
				$total_insentif_tm += $y['total'];
			}

			//Wheel Loader
			$pembelian_wheel_loader = $this->db->select('
			pn.nama, po.no_po, po.subject, prm.measure, SUM(prm.volume) as volume, SUM(prm.price) / SUM(prm.volume) as harga_satuan, SUM(prm.price) as price')
			->from('pmm_receipt_material prm')
			->join('pmm_purchase_order po', 'prm.purchase_order_id = po.id','left')
			->join('produk p', 'prm.material_id = p.id','left')
			->join('penerima pn', 'po.supplier_id = pn.id','left')
			->where("prm.date_receipt between '$date1' and '$date2'")
			->where("kategori_alat = '3'")
			->where("po.status in ('PUBLISH','CLOSED')")
			->group_by('prm.harga_satuan')
			->order_by('pn.nama','asc')
			->get()->result_array();

			$total_nilai_wheel_loader = 0;
			foreach ($pembelian_wheel_loader as $x){
				$total_nilai_wheel_loader += $x['price'];
			}

			$insentif_wl = $this->db->select('pb.memo as memo, sum(pdb.debit) as total')
			->from('pmm_jurnal_umum pb')
			->join('pmm_detail_jurnal pdb','pb.id = pdb.jurnal_id','left')
			->where("pdb.akun = 221")
			->where("pb.status = 'PAID'")
			->where("pb.memo <> 'SC' ")
			->where("(pb.tanggal_transaksi between '$date1' and '$date2')")
			->group_by('pdb.id')
			->get()->result_array();

			$total_insentif_wl = 0;
			foreach ($insentif_wl as $y){
				$total_insentif_wl += $y['total'];
			}

			//Transfer Semen
			$pembelian_transfer_semen = $this->db->select('
			pn.nama, po.no_po, po.subject, prm.measure, SUM(prm.volume) as volume, SUM(prm.price) / SUM(prm.volume) as harga_satuan, SUM(prm.price) as price')
			->from('pmm_receipt_material prm')
			->join('pmm_purchase_order po', 'prm.purchase_order_id = po.id','left')
			->join('produk p', 'prm.material_id = p.id','left')
			->join('penerima pn', 'po.supplier_id = pn.id','left')
			->where("prm.date_receipt between '$date1' and '$date2'")
			->where("kategori_alat = '4'")
			->where("po.status in ('PUBLISH','CLOSED')")
			->group_by('prm.harga_satuan')
			->order_by('pn.nama','asc')
			->get()->result_array();

			$total_nilai_transfer_semen = 0;
			foreach ($pembelian_transfer_semen as $x){
				$total_nilai_transfer_semen += $x['price'];
			}

			//BBM SOLAR
			$date1_ago = date('2020-01-01');
			$date2_ago = date('Y-m-d', strtotime('-1 days', strtotime($date1)));
			$date3_ago = date('Y-m-d', strtotime('-1 months', strtotime($date1)));

			$harga_hpp_bahan_baku = $this->db->select('pp.date_hpp, pp.solar')
			->from('hpp_bahan_baku pp')
			->where("(pp.date_hpp < '$date1')")
			->order_by('pp.date_hpp','desc')->limit(1)
			->get()->row_array();

			//PEMBELIAN SOLAR AGO
			$pembelian_solar_ago = $this->db->select('
			p.nama_produk, 
			prm.display_measure as satuan, 
			SUM(prm.display_volume) as volume, 
			SUM(prm.display_price) / SUM(prm.display_volume) as harga, 
			SUM(prm.display_price) as nilai')
			->from('pmm_receipt_material prm')
			->join('pmm_purchase_order po', 'prm.purchase_order_id = po.id','left')
			->join('produk p', 'prm.material_id = p.id','left')
			->where("prm.date_receipt between '$date1_ago' and '$date2_ago'")
			->where("prm.material_id = 8")
			->group_by('prm.material_id')
			->get()->row_array();

			$total_volume_pembelian_solar_ago = $pembelian_solar_ago['volume'];
			$total_volume_pembelian_solar_akhir_ago  = $total_volume_pembelian_solar_ago;
			
			$stock_opname_solar_ago = $this->db->select('(cat.display_volume) as volume')
			->from('pmm_remaining_materials_cat cat ')
			->where("(cat.date < '$date1')")
			->where("cat.material_id = 8")
			->where("cat.status = 'PUBLISH'")
			->order_by('cat.date','desc')->limit(1)
			->get()->row_array();

			$total_volume_stock_solar_ago = $stock_opname_solar_ago['volume'];
			
			$volume_opening_balance_solar = round($total_volume_stock_solar_ago,2);
			$harga_opening_balance_solar = $harga_hpp_bahan_baku['solar'];
			$nilai_opening_balance_solar = $volume_opening_balance_solar * $harga_opening_balance_solar ;

			//PEMBELIAN SOLAR
			$pembelian_solar = $this->db->select('
			p.nama_produk, 
			prm.display_measure as satuan, 
			SUM(prm.display_volume) as volume, 
			SUM(prm.display_price) / SUM(prm.display_volume) as harga, 
			SUM(prm.display_price) as nilai')
			->from('pmm_receipt_material prm')
			->join('pmm_purchase_order po', 'prm.purchase_order_id = po.id','left')
			->join('produk p', 'prm.material_id = p.id','left')
			->where("prm.date_receipt between '$date1' and '$date2'")
			->where("prm.material_id = 8")
			->group_by('prm.material_id')
			->get()->row_array();
			
			$total_volume_pembelian_solar = $pembelian_solar['volume'];
			$total_nilai_pembelian_solar =  $pembelian_solar['nilai'];
			$total_harga_pembelian_solar = ($total_volume_pembelian_solar!=0)?$total_nilai_pembelian_solar / $total_volume_pembelian_solar * 1:0;

			$total_volume_pembelian_solar_akhir  = $volume_opening_balance_solar + $total_volume_pembelian_solar;
			$total_harga_pembelian_solar_akhir = ($total_volume_pembelian_solar_akhir!=0)?($nilai_opening_balance_solar + $total_nilai_pembelian_solar) / $total_volume_pembelian_solar_akhir* 1:0;
			$total_nilai_pembelian_solar_akhir =  $total_volume_pembelian_solar_akhir * $total_harga_pembelian_solar_akhir;			
			
			$stock_opname_solar = $this->db->select('(cat.display_volume) as volume')
			->from('pmm_remaining_materials_cat cat ')
			->where("cat.date between '$date1' and '$date2'")
			->where("cat.material_id = 8")
			->where("cat.status = 'PUBLISH'")
			->order_by('cat.date','desc')->limit(1)
			->get()->row_array();
			
			$total_volume_stock_solar_akhir = $stock_opname_solar['volume'];

			$total_volume_pemakaian_solar = $total_volume_pembelian_solar_akhir - $stock_opname_solar['volume'];
			$total_harga_pemakaian_solar = round($total_harga_pembelian_solar_akhir,0);
			$total_nilai_pemakaian_solar = $total_volume_pemakaian_solar * $total_harga_pemakaian_solar;

			$total_harga_stock_solar_akhir = $total_harga_pemakaian_solar;
			$total_nilai_stock_solar_akhir = $total_volume_stock_solar_akhir * $total_harga_stock_solar_akhir;

			//TOTAL
			$total_nilai_pembelian = $total_nilai_pembelian_solar;
			$total_nilai_pemakaian = $total_nilai_pemakaian_solar;
			$total_nilai_akhir = $total_nilai_stock_solar_akhir;

			$akumulasi_bbm = $this->db->select('pp.date_akumulasi, pp.total_nilai_keluar_2 as total_nilai_keluar_2')
			->from('akumulasi pp')
			->where("(pp.date_akumulasi between '$date1' and '$date2')")
			->get()->result_array();

			$total_akumulasi_bbm = 0;
			foreach ($akumulasi_bbm as $b){
				$total_akumulasi_bbm += $b['total_nilai_keluar_2'];
			}
			
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
			
			$total_volume = 0;
			foreach ($penjualan as $x){
				$total_volume += $x['volume'];
			}

			//Excavator
			$pembelian_exc = $this->db->select('
			p.nama_produk, pn.nama, po.no_po, po.subject, prm.measure, SUM(prm.volume) as volume, SUM(prm.price) / SUM(prm.volume) as harga_satuan, SUM(prm.price) as price')
			->from('pmm_receipt_material prm')
			->join('pmm_purchase_order po', 'prm.purchase_order_id = po.id','left')
			->join('produk p', 'prm.material_id = p.id','left')
			->join('penerima pn', 'po.supplier_id = pn.id','left')
			->where("prm.date_receipt between '$date1' and '$date2'")
			->where("kategori_alat = '5'")
			->where("po.status in ('PUBLISH','CLOSED')")
			->group_by('prm.material_id')
			->order_by('pn.nama','asc')
			->get()->result_array();

			$total_nilai_exc = 0;
			$total_vol_exc = 0;
			foreach ($pembelian_exc as $x){
				$total_nilai_exc += $x['price'];
				$total_vol_exc += $x['volume'];
				$pembelian_exc_measure = $x['measure'];
			}

			//Dump Truck 4M3
			$pembelian_dmp_4m3 = $this->db->select('
			p.nama_produk, pn.nama, po.no_po, po.subject, prm.measure, SUM(prm.volume) as volume, SUM(prm.price) / SUM(prm.volume) as harga_satuan, SUM(prm.price) as price')
			->from('pmm_receipt_material prm')
			->join('pmm_purchase_order po', 'prm.purchase_order_id = po.id','left')
			->join('produk p', 'prm.material_id = p.id','left')
			->join('penerima pn', 'po.supplier_id = pn.id','left')
			->where("prm.date_receipt between '$date1' and '$date2'")
			->where("kategori_alat = '6'")
			->where("po.status in ('PUBLISH','CLOSED')")
			->group_by('prm.material_id')
			->order_by('pn.nama','asc')
			->get()->result_array();

			$total_nilai_dmp_4m3 = 0;
			$total_vol_dmp_4m3 = 0;
			foreach ($pembelian_dmp_4m3 as $x){
				$total_nilai_dmp_4m3 += $x['price'];
				$total_vol_dmp_4m3 += $x['volume'];
				$pembelian_dmp_4m3_measure = $x['measure'];
			}

			//Dump Truck 10M3
			$pembelian_dmp_10m3 = $this->db->select('
			p.nama_produk, pn.nama, po.no_po, po.subject, prm.measure, SUM(prm.volume) as volume, SUM(prm.price) / SUM(prm.volume) as harga_satuan, SUM(prm.price) as price')
			->from('pmm_receipt_material prm')
			->join('pmm_purchase_order po', 'prm.purchase_order_id = po.id','left')
			->join('produk p', 'prm.material_id = p.id','left')
			->join('penerima pn', 'po.supplier_id = pn.id','left')
			->where("prm.date_receipt between '$date1' and '$date2'")
			->where("kategori_alat = '7'")
			->where("po.status in ('PUBLISH','CLOSED')")
			->group_by('prm.material_id')
			->order_by('pn.nama','asc')
			->get()->result_array();

			$total_nilai_dmp_10m3 = 0;
			$total_vol_dmp_10m3 = 0;
			foreach ($pembelian_dmp_10m3 as $x){
				$total_nilai_dmp_10m3 += $x['price'];
				$total_vol_dmp_10m3 += $x['volume'];
				$pembelian_dmp_10m3_measure = $x['measure'];
			}

			//SC
			$pembelian_sc = $this->db->select('
			pn.nama, po.no_po, po.subject, prm.measure, SUM(prm.volume) as volume, SUM(prm.price) / SUM(prm.volume) as harga_satuan, SUM(prm.price) as price')
			->from('pmm_receipt_material prm')
			->join('pmm_purchase_order po', 'prm.purchase_order_id = po.id','left')
			->join('produk p', 'prm.material_id = p.id','left')
			->join('penerima pn', 'po.supplier_id = pn.id','left')
			->where("prm.date_receipt between '$date1' and '$date2'")
			->where("kategori_alat = '8'")
			->where("po.status in ('PUBLISH','CLOSED')")
			->group_by('prm.harga_satuan')
			->order_by('pn.nama','asc')
			->get()->result_array();

			$total_nilai_sc = 0;
			$total_vol_sc = 0;
			foreach ($pembelian_sc as $x){
				$total_nilai_sc += $x['price'];
				$total_vol_sc += $x['volume'];
				$pembelian_sc_measure = $x['measure'];
			}

			//Genset
			$pembelian_gns = $this->db->select('
			pn.nama, po.no_po, po.subject, prm.measure, SUM(prm.volume) as volume, SUM(prm.price) / SUM(prm.volume) as harga_satuan, SUM(prm.price) as price')
			->from('pmm_receipt_material prm')
			->join('pmm_purchase_order po', 'prm.purchase_order_id = po.id','left')
			->join('produk p', 'prm.material_id = p.id','left')
			->join('penerima pn', 'po.supplier_id = pn.id','left')
			->where("prm.date_receipt between '$date1' and '$date2'")
			->where("kategori_alat = '9'")
			->where("po.status in ('PUBLISH','CLOSED')")
			->group_by('prm.harga_satuan')
			->order_by('pn.nama','asc')
			->get()->result_array();

			$total_nilai_gns = 0;
			$total_vol_gns = 0;
			foreach ($pembelian_gns as $x){
				$total_nilai_gns += $x['price'];
				$total_vol_gns += $x['volume'];
				$pembelian_gns_measure = $x['measure'];
			}

			//Wheel Loader SC
			$pembelian_wl_sc = $this->db->select('
			pn.nama, po.no_po, po.subject, prm.measure, SUM(prm.volume) as volume, SUM(prm.price) / SUM(prm.volume) as harga_satuan, SUM(prm.price) as price')
			->from('pmm_receipt_material prm')
			->join('pmm_purchase_order po', 'prm.purchase_order_id = po.id','left')
			->join('produk p', 'prm.material_id = p.id','left')
			->join('penerima pn', 'po.supplier_id = pn.id','left')
			->where("prm.date_receipt between '$date1' and '$date2'")
			->where("kategori_alat = '10'")
			->where("po.status in ('PUBLISH','CLOSED')")
			->group_by('prm.harga_satuan')
			->order_by('pn.nama','asc')
			->get()->result_array();

			$total_nilai_wl_sc = 0;
			$total_vol_wl_sc = 0;
			foreach ($pembelian_wl_sc as $x){
				$total_nilai_wl_sc += $x['price'];
				$total_vol_wl_sc += $x['volume'];
				$pembelian_wl_sc_measure = $x['measure'];
			}

			$insentif_wl_sc = $this->db->select('pb.memo as memo, sum(pdb.debit) as total')
			->from('pmm_jurnal_umum pb ')
			->join('pmm_detail_jurnal pdb','pb.id = pdb.jurnal_id','left')
			->where("pdb.akun = 221")
			->where("pb.status = 'PAID'")
			->where("pb.status = 'SC'")
			->where("(pb.tanggal_transaksi between '$date1' and '$date2')")
			->group_by('pdb.id')
			->get()->result_array();

			$total_insentif_wl_sc = 0;

			foreach ($insentif_wl_sc as $y){
				$total_insentif_wl_sc += $y['total'];
			}

			//BBM Solar SC
			$pembelian_bbm_sc = $this->db->select('
			pn.nama, po.no_po, po.subject, prm.measure, SUM(prm.volume) as volume, SUM(prm.price) / SUM(prm.volume) as harga_satuan, SUM(prm.price) as price')
			->from('pmm_receipt_material prm')
			->join('pmm_purchase_order po', 'prm.purchase_order_id = po.id','left')
			->join('produk p', 'prm.material_id = p.id','left')
			->join('penerima pn', 'po.supplier_id = pn.id','left')
			->where("prm.date_receipt between '$date1' and '$date2'")
			->where("p.id = '59'")
			->where("po.status in ('PUBLISH','CLOSED')")
			->group_by('prm.harga_satuan')
			->order_by('pn.nama','asc')
			->get()->result_array();

			$total_nilai_bbm_sc = 0;
			$total_vol_bbm_sc = 0;
			foreach ($pembelian_bbm_sc as $x){
				$total_nilai_bbm_sc += $x['price'];
				$total_vol_bbm_sc += $x['volume'];
				$pembelian_bbm_sc_measure = $x['measure'];
			}

			//BBM Solar QUARRY
			$pembelian_bbm_q = $this->db->select('
			pn.nama, po.no_po, po.subject, prm.measure, SUM(prm.volume) as volume, SUM(prm.price) / SUM(prm.volume) as harga_satuan, SUM(prm.price) as price')
			->from('pmm_receipt_material prm')
			->join('pmm_purchase_order po', 'prm.purchase_order_id = po.id','left')
			->join('produk p', 'prm.material_id = p.id','left')
			->join('penerima pn', 'po.supplier_id = pn.id','left')
			->where("prm.date_receipt between '$date1' and '$date2'")
			->where("p.id = '60'")
			->where("po.status in ('PUBLISH','CLOSED')")
			->group_by('prm.harga_satuan')
			->order_by('pn.nama','asc')
			->get()->result_array();

			$total_nilai_bbm_q = 0;
			$total_vol_bbm_q = 0;
			foreach ($pembelian_bbm_q as $x){
				$total_nilai_bbm_q += $x['price'];
				$total_vol_bbm_q += $x['volume'];
				$pembelian_bbm_q_measure = $x['measure'];
			}

			$total_vol_batching_plant = $total_volume;
			$total_vol_truck_mixer = $total_volume;
			$total_vol_wheel_loader = $total_volume;
			$total_vol_transfer_semen = $total_volume;
			$total_vol_bbm_solar = $total_volume;

			$total_pemakaian_vol_batching_plant = $total_vol_batching_plant;
			$total_pemakaian_vol_truck_mixer = $total_vol_truck_mixer;
			$total_pemakaian_vol_wheel_loader = $total_vol_wheel_loader;
			$total_pemakaian_vol_bbm_solar = $total_volume_pemakaian_solar;

			$total_pemakaian_batching_plant = $total_nilai_batching_plant;
			$total_pemakaian_truck_mixer = $total_nilai_truck_mixer + $total_insentif_tm;
			$total_pemakaian_wheel_loader = $total_nilai_wheel_loader + $total_insentif_wl;
			$total_pemakaian_transfer_semen = $total_nilai_transfer_semen;
			$total_pemakaian_bbm_solar = $total_akumulasi_bbm;
			$total_pemakaian_exc = $total_nilai_exc;
			$total_pemakaian_dmp_4m3 = $total_nilai_dmp_4m3;
			$total_pemakaian_dmp_10m3 = $total_nilai_dmp_10m3;
			$total_pemakaian_sc = $total_nilai_sc;
			$total_pemakaian_gns = $total_nilai_gns;
			$total_pemakaian_wl_sc = $total_nilai_wl_sc + $total_insentif_wl_sc;
			
			//Rumus BBM SC & QUARRY
			$harsat_bbm_sc = ($total_pemakaian_bbm_solar / $total_volume_pemakaian_solar);
			$total_nilai_bbm_sc = $total_vol_bbm_sc * $harsat_bbm_sc;

			$harsat_bbm_q = ($total_pemakaian_bbm_solar / $total_volume_pemakaian_solar);
			$total_nilai_bbm_q = $total_vol_bbm_q * $harsat_bbm_q;
			?>

			<!-- RAP Alat -->

			<?php

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

			$vol_batching_plant = $total_vol_rap_batching_plant * $total_pemakaian_vol_batching_plant;
			$vol_truck_mixer = $total_vol_rap_truck_mixer * $total_pemakaian_vol_truck_mixer;
			$vol_wheel_loader = $total_vol_rap_wheel_loader * $total_pemakaian_vol_wheel_loader;
			$vol_bbm_solar = $total_vol_rap_bbm_solar * $total_vol_bbm_solar;

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
			
			?>

			<!-- Evaluasi -->
			<?php
			$total_vol_evaluasi_batching_plant = ($total_pemakaian_vol_batching_plant!=0)?$vol_batching_plant - $total_pemakaian_vol_batching_plant * 1:0;
			$total_nilai_evaluasi_batching_plant = ($total_pemakaian_batching_plant!=0)?$batching_plant - $total_pemakaian_batching_plant * 1:0;

			$total_vol_evaluasi_truck_mixer = ($total_pemakaian_vol_truck_mixer!=0)?$vol_truck_mixer - $total_pemakaian_vol_truck_mixer * 1:0;
			$total_nilai_evaluasi_truck_mixer = ($total_pemakaian_truck_mixer!=0)?$truck_mixer - $total_pemakaian_truck_mixer * 1:0;

			$total_vol_evaluasi_wheel_loader = ($total_pemakaian_vol_wheel_loader!=0)?$vol_wheel_loader - $total_pemakaian_vol_wheel_loader * 1:0;
			$total_nilai_evaluasi_wheel_loader = ($total_pemakaian_wheel_loader!=0)?$wheel_loader - $total_pemakaian_wheel_loader * 1:0;

			$total_nilai_evaluasi_transfer_semen = ($total_pemakaian_transfer_semen!=0)?$transfer_semen - $total_pemakaian_transfer_semen * 1:0;

			$total_vol_evaluasi_bbm_solar = ($total_pemakaian_vol_bbm_solar!=0)?$vol_bbm_solar - ($total_pemakaian_vol_bbm_solar  - $total_vol_bbm_sc - $total_vol_bbm_q) * 1:0;
			$total_nilai_evaluasi_bbm_solar = ($total_pemakaian_bbm_solar!=0)?$bbm_solar - ($total_pemakaian_bbm_solar - $total_nilai_bbm_sc - $total_nilai_bbm_q) * 1:0;

			$total_nilai_evaluasi_exc = (0-$total_pemakaian_exc);
			$total_nilai_evaluasi_dmp_4m3 = (0-$total_pemakaian_dmp_4m3);
			$total_nilai_evaluasi_dmp_10m3 = (0-$total_pemakaian_dmp_10m3);
			$total_nilai_evaluasi_sc = (0-$total_pemakaian_sc);
			$total_nilai_evaluasi_gns = (0-$total_pemakaian_gns);
			$total_nilai_evaluasi_wl_sc = (0-$total_pemakaian_wl_sc);
			$total_nilai_evaluasi_bbm_sc = (0-$total_pemakaian_bbm_sc);
			$total_nilai_evaluasi_bbm_q = (0-$total_pemakaian_bbm_q);
			?>

			<!-- TOTAL -->
			<?php
			$total_nilai_rap_bp = $batching_plant + $truck_mixer + $wheel_loader + $bbm_solar;
			$total_nilai_realisasi_bp = $total_pemakaian_batching_plant + $total_pemakaian_truck_mixer + $total_pemakaian_wheel_loader + $total_nilai_transfer_semen + $total_pemakaian_bbm_solar - $total_nilai_bbm_sc - $total_nilai_bbm_q;
			$total_nilai_evaluasi_bp = $total_nilai_evaluasi_batching_plant + $total_nilai_evaluasi_truck_mixer + $total_nilai_evaluasi_wheel_loader + $total_nilai_evaluasi_transfer_semen + $total_nilai_evaluasi_bbm_solar;

			$total_nilai_rap_sc = 0;
			$total_nilai_realisasi_sc = $total_pemakaian_dmp_10m3 + $total_pemakaian_sc + $total_pemakaian_gns + $total_pemakaian_wl_sc + $total_nilai_bbm_sc;
			$total_nilai_evaluasi_sc = 0;

			$total_nilai_rap_q = 0;
			$total_nilai_realisasi_q  = $total_pemakaian_exc + $total_pemakaian_dmp_4m3 + $total_nilai_bbm_q;
			$total_nilai_evaluasi_q = 0;

			$total_nilai_rap_all = $total_nilai_rap_bp;
			$total_nilai_realisasi_all = $total_nilai_realisasi_bp + $total_nilai_realisasi_sc + $total_nilai_realisasi_q;
			$total_nilai_evaluasi_all = $total_nilai_evaluasi_bp;
			?>
			
			<tr class="table-judul">
				<th width="5%" align="center" rowspan="2" style="background-color:#e69500; border-top:1px solid black; border-left:1px solid black; border-bottom:1px solid black;">&nbsp;<br>NO.</th>
				<th width="22%" align="center" rowspan="2" style="background-color:#e69500; border-top:1px solid black; border-bottom:1px solid black;">&nbsp;<br>URAIAN</th>
				<th width="8%" align="center" rowspan="2" style="background-color:#e69500; border-top:1px solid black; border-bottom:1px solid black;">&nbsp;<br>SATUAN</th>
				<th width="24%" align="center" colspan="3" style="background-color:#e69500; border-top:1px solid black; border-left:1px solid black; border-bottom:1px solid black;">RAP</th>
				<th width="24%" align="center" colspan="3" style="background-color:#e69500; border-top:1px solid black; border-bottom:1px solid black;">REALISASI</th>
				<th width="17%" align="center" colspan="2" style="background-color:#e69500; border-top:1px solid black; border-right:1px solid black; border-bottom:1px solid black;">EVALUASI</th>
	        </tr>
			<tr class="table-judul">
				<th width="7%" align="center" style="background-color:#e69500; border-left:1px solid black; border-bottom:1px solid black;">VOLUME</th>
				<th width="7%" align="center" style="background-color:#e69500; border-bottom:1px solid black;">HARSAT</th>
				<th width="10%" align="center" style="background-color:#e69500; border-bottom:1px solid black;">NILAI</th>
				<th width="7%" align="center" style="background-color:#e69500; border-bottom:1px solid black;">VOLUME</th>
				<th width="7%" align="center" style="background-color:#e69500; border-bottom:1px solid black;">HARSAT</th>
				<th width="10%" align="center" style="background-color:#e69500; border-bottom:1px solid black;">NILAI</th>
				<th width="7%" align="center" style="background-color:#e69500; border-bottom:1px solid black;">VOLUME</th>
				<th width="10%" align="center" style="background-color:#e69500; border-bottom:1px solid black; border-right:1px solid black;">NILAI</th>
	        </tr>
			<?php
				$styleColorA = $total_vol_evaluasi_batching_plant < 0 ? 'color:red' : 'color:black';
				$styleColorB = $total_nilai_evaluasi_batching_plant < 0 ? 'color:red' : 'color:black';
				$styleColorC = $total_vol_evaluasi_truck_mixer < 0 ? 'color:red' : 'color:black';
				$styleColorD = $total_nilai_evaluasi_truck_mixer < 0 ? 'color:red' : 'color:black';
				$styleColorE = $total_vol_evaluasi_wheel_loader < 0 ? 'color:red' : 'color:black';
				$styleColorF = $total_nilai_evaluasi_wheel_loader < 0 ? 'color:red' : 'color:black';
				$styleColorG = $total_nilai_evaluasi_transfer_semen < 0 ? 'color:red' : 'color:black';
				$styleColorH = $total_vol_evaluasi_bbm_solar < 0 ? 'color:red' : 'color:black';
				$styleColorI = $total_nilai_evaluasi_bbm_solar < 0 ? 'color:red' : 'color:black';
				$styleColorJ = $total_nilai_evaluasi_bp < 0 ? 'color:red' : 'color:black';
				$styleColorK = $total_nilai_evaluasi_all < 0 ? 'color:red' : 'color:black';
			?>
			<tr class="table-total">
				<th align="center" colspan="3" style="border-left:1px solid black; border-right:1px solid black;">
				<div align="left" style="display:block; font-weight: bold;text-transform:uppercase;">A. GROUP BP</div>
				</th>
				<th align="center" colspan="8" style="border-right:1px solid black;"></th>	
			</tr>
			<tr class="table-baris1">
				<th align="center" style="border-left:1px solid black;">1.</th>			
				<th align="left">Batching Plant</th>
				<th align="center" style="border-right:1px solid black;">M3</th>
				<th align="right"><?php echo number_format($vol_batching_plant,2,',','.');?></th>
				<th align="right"><?php echo number_format($total_batching_plant,0,',','.');?></th>
				<th align="right"><?php echo number_format($batching_plant,0,',','.');?></th>
				<th align="right"><?php echo number_format($total_pemakaian_vol_batching_plant,2,',','.');?></th>
				<th align="right"><?php echo number_format($total_pemakaian_batching_plant / $total_pemakaian_vol_batching_plant,0,',','.');?></th>
				<th align="right"><?php echo number_format($total_pemakaian_batching_plant,0,',','.');?></th>
				<th align="right" style="<?php echo $styleColorA ?>"><?php echo number_format($total_vol_evaluasi_batching_plant,0,',','.');?></th>
				<th align="right" style="<?php echo $styleColorB ?>; border-right:1px solid black;"><?php echo number_format($total_nilai_evaluasi_batching_plant,0,',','.');?></th>
	        </tr>
			<tr class="table-baris1">
				<th align="center" style="border-left:1px solid black;">2.</th>			
				<th align="left">Truck Mixer + Insentif</th>
				<th align="center" style="border-right:1px solid black;">M3</th>
				<th align="right"><?php echo number_format($vol_truck_mixer,2,',','.');?></th>
				<th align="right"><?php echo number_format($total_truck_mixer,0,',','.');?></th>
				<th align="right"><?php echo number_format($truck_mixer,0,',','.');?></th>
				<th align="right"><?php echo number_format($total_pemakaian_vol_truck_mixer,2,',','.');?></th>
				<th align="right"><?php echo number_format($total_pemakaian_truck_mixer / $total_pemakaian_vol_truck_mixer,0,',','.');?></th>
				<th align="right"><?php echo number_format($total_pemakaian_truck_mixer,0,',','.');?></th>
				<th align="right" style="<?php echo $styleColorC ?>"><?php echo number_format($total_vol_evaluasi_truck_mixer,0,',','.');?></th>
				<th align="right" style="<?php echo $styleColorD ?>; border-right:1px solid black;"><?php echo number_format($total_nilai_evaluasi_truck_mixer,0,',','.');?></th>
	        </tr>
			<tr class="table-baris1">
				<th align="center" style="border-left:1px solid black;">3.</th>			
				<th align="left">Wheel Loader + Insentif</th>
				<th align="center" style="border-right:1px solid black;">M3</th>
				<th align="right"><?php echo number_format($vol_wheel_loader,2,',','.');?></th>
				<th align="right"><?php echo number_format($total_wheel_loader,0,',','.');?></th>
				<th align="right"><?php echo number_format($wheel_loader,0,',','.');?></th>
				<th align="right"><?php echo number_format($total_pemakaian_vol_wheel_loader,2,',','.');?></th>
				<th align="right"><?php echo number_format($total_pemakaian_wheel_loader / $total_pemakaian_vol_wheel_loader,0,',','.');?></th>
				<th align="right"><?php echo number_format($total_pemakaian_wheel_loader,0,',','.');?></th>
				<th align="right" style="<?php echo $styleColorE ?>"><?php echo number_format($total_vol_evaluasi_wheel_loader,0,',','.');?></th>
				<th align="right" style="<?php echo $styleColorF ?>; border-right:1px solid black;"><?php echo number_format($total_nilai_evaluasi_wheel_loader,0,',','.');?></th>
	        </tr>
			<tr class="table-baris1">
				<th align="center" style="border-left:1px solid black;">4.</th>			
				<th align="left">Transfer Semen</th>
				<th align="center" style="border-right:1px solid black;">Unit / Bulan</th>
				<th align="right"><?php echo number_format(0,2,',','.');?></th>
				<th align="right"><?php echo number_format(0,0,',','.');?></th>
				<th align="right"><?php echo number_format(0,0,',','.');?></th>
				<th align="right"><?php echo number_format(0,2,',','.');?></th>
				<th align="right"><?php echo number_format(0,0,',','.');?></th>
				<th align="right"><?php echo number_format($total_nilai_transfer_semen,0,',','.');?></th>
				<th align="right"><?php echo number_format(0,2,',','.');?></th>
				<th align="right" style="<?php echo $styleColorG ?>; border-right:1px solid black;"><?php echo number_format($total_nilai_evaluasi_transfer_semen,0,',','.');?></th>
	        </tr>
			<tr class="table-baris1">
				<th align="center" style="border-left:1px solid black;">5.</th>			
				<th align="left">BBM Solar</th>
				<th align="center" style="border-right:1px solid black;">Liter</th>
				<th align="right"><?php echo number_format($vol_bbm_solar,2,',','.');?></th>
				<th align="right"><?php echo number_format($total_bbm_solar,0,',','.');?></th>
				<th align="right"><?php echo number_format($bbm_solar,0,',','.');?></th>
				<th align="right"><?php echo number_format($total_volume_pemakaian_solar - $total_vol_bbm_sc - $total_vol_bbm_q,2,',','.');?></th>
				<th align="right"><?php echo number_format($total_pemakaian_bbm_solar / $total_volume_pemakaian_solar,0,',','.');?></th>
				<th align="right"><?php echo number_format($total_pemakaian_bbm_solar - $total_nilai_bbm_sc - $total_nilai_bbm_q,0,',','.');?></th>
				<th align="right" style="<?php echo $styleColorH ?>"><?php echo number_format($total_vol_evaluasi_bbm_solar,0,',','.');?></th>
				<th align="right" style="<?php echo $styleColorI ?>; border-right:1px solid black;"><?php echo number_format($total_nilai_evaluasi_bbm_solar,0,',','.');?></th>
	        </tr>
			<tr class="table-total">		
				<th align="right" colspan="3" style="background-color:#FFFF00; border:1px solid black;">TOTAL GROUP BP</th>
				<th align="right" style="background-color:#FFFF00; border-top:1px solid black; border-bottom:1px solid black;"></th>
				<th align="right" style="background-color:#FFFF00; border-top:1px solid black; border-bottom:1px solid black;"></th>
				<th align="right" style="background-color:#FFFF00; border-top:1px solid black; border-bottom:1px solid black;"><?php echo number_format($total_nilai_rap_bp,0,',','.');?></th>
				<th align="right" style="background-color:#FFFF00; border-top:1px solid black; border-bottom:1px solid black;"></th>
				<th align="right" style="background-color:#FFFF00; border-top:1px solid black; border-bottom:1px solid black;"></th>
				<th align="right" style="background-color:#FFFF00; border-top:1px solid black; border-bottom:1px solid black;"><?php echo number_format($total_nilai_realisasi_bp,0,',','.');?></th>
				<th align="right" style="background-color:#FFFF00; border-top:1px solid black; border-bottom:1px solid black;"></th>
				<th align="right" style="<?php echo $styleColorJ ?>;background-color:#FFFF00; border-right:1px solid black; border-top:1px solid black; border-bottom:1px solid black;"><?php echo number_format($total_nilai_evaluasi_bp,0,',','.');?></th>
	        </tr>
			<tr class="table-total">
				<th align="center" colspan="3" style="border-left:1px solid black; border-right:1px solid black;">
				<div align="left" style="display: block;font-weight: bold;text-transform:uppercase;">B. GROUP SC</div>
				</th>
				<th align="center" colspan="8" style="border-right:1px solid black;"></th>
			</tr>
			<tr class="table-baris1">
				<th align="center" style="border-left:1px solid black;">1.</th>			
				<th align="left" colspan="2" style="border-right:1px solid black;">Dump Truck 10 M3</th>
				<th align="left" colspan="8" style="border-right:1px solid black;"></th>
	        </tr>
			<?php
			$alpha = array('a','b','c','d','e','f','g','h','i','j','k','l','m','n','o','p','q','r','s','t','u','v','w','x','y','z');
			$ev_dmp_10m3_price = 0;
			foreach ($pembelian_dmp_10m3 as $key => $x):
			$ev_dmp_10m3_price = 0 - $x['price'];
			$styleColorK = $ev_dmp_10m3_price < 0 ? 'color:red' : 'color:black';
			?>
			<tr class="table-baris1">
				<th align="center" style="border-left:1px solid black;"></th>			
				<th align="left"><?php echo $alpha[$key++];?>. <?= $x['nama_produk'] ?></th>
				<th align="center" style="border-right:1px solid black;"><?= $x['measure'] ?></th>
				<th align="right"><?php echo number_format(0,2,',','.');?></th>
				<th align="right"><?php echo number_format(0,0,',','.');?></th>
				<th align="right"><?php echo number_format(0,0,',','.');?></th>
				<th align="right"><?php echo number_format($x['volume'],2,',','.');?></th>
				<th align="right"><?php echo number_format($x['price'] / $x['volume'],0,',','.');?></th>
				<th align="right"><?php echo number_format($x['price'],0,',','.');?></th>
				<th align="right"><?php echo number_format(0,2,',','.');?></th>
				<th align="right" style="border-right:1px solid black;"><?php echo number_format(0,0,',','.');?></th>
	        </tr>
			<?php endforeach; ?>
			<tr class="table-baris1">
				<th align="center" style="border-left:1px solid black;">2.</th>			
				<th align="left">Stone Crusher</th>
				<th align="center" style="border-right:1px solid black;"><?php
				$null = '-';
				if (!empty($pembelian_sc_measure)) {
					echo $pembelian_sc_measure;
				} else {    
					echo $null;
				}
				?></th>
				<th align="right"><?php echo number_format(0,2,',','.');?></th>
				<th align="right"><?php echo number_format(0,0,',','.');?></th>
				<th align="right"><?php echo number_format(0,0,',','.');?></th>
				<th align="right"><?php echo number_format($total_vol_sc,2,',','.');?></th>
				<?php
				$harsat_sc = ($total_vol_sc!=0)?$total_nilai_sc / $total_vol_sc * 1:0;
				?>
				<th align="right"><?php echo number_format($harsat_sc,0,',','.');?></th>
				<th align="right"><?php echo number_format($total_nilai_sc,0,',','.');?></th>
				<th align="right"><?php echo number_format(0,2,',','.');?></th>
				<th align="right" style="border-right:1px solid black;"><?php echo number_format(0,0,',','.');?></th>
	        </tr>
			<tr class="table-baris1">
				<th align="center" style="border-left:1px solid black;">3.</th>			
				<th align="left">Genset</th>
				<th align="center" style="border-right:1px solid black;"><?php
				$null = '-';
				if (!empty($pembelian_gns_measure)) {
					echo $pembelian_gns_measure;
				} else {    
					echo $null;
				}
				?></th>
				<th align="right"><?php echo number_format(0,2,',','.');?></th>
				<th align="right"><?php echo number_format(0,0,',','.');?></th>
				<th align="right"><?php echo number_format(0,0,',','.');?></th>
				<th align="right"><?php echo number_format($total_vol_gns,2,',','.');?></th>
				<?php
				$harsat_gns = ($total_vol_gns!=0)?$total_nilai_gns / $total_vol_gns * 1:0;
				?>
				<th align="right"><?php echo number_format($harsat_gns,0,',','.');?></th>
				<th align="right"><?php echo number_format($total_nilai_gns,0,',','.');?></th>
				<th align="right"><?php echo number_format(0,2,',','.');?></th>
				<th align="right" style="border-right:1px solid black;"><?php echo number_format(0,0,',','.');?></th>
	        </tr>
			<tr class="table-baris1">
				<th align="center" style="border-left:1px solid black;">4.</th>			
				<th align="left">Wheel Loader + Insentif</th>
				<th align="center" style="border-right:1px solid black;"><?php
				$null = '-';
				if (!empty($pembelian_wl_sc_measure)) {
					echo $pembelian_wl_sc_measure;
				} else {    
					echo $null;
				}
				?></th>
				<th align="right"><?php echo number_format(0,2,',','.');?></th>
				<th align="right"><?php echo number_format(0,0,',','.');?></th>
				<th align="right"><?php echo number_format(0,0,',','.');?></th>
				<th align="right"><?php echo number_format($total_vol_wl_sc,2,',','.');?></th>
				<?php
				$harsat_wl_sc = ($total_vol_wl_sc!=0)?$total_nilai_wl_sc / $total_vol_wl_sc * 1:0;
				?>
				<th align="right"><?php echo number_format($harsat_wl_sc,0,',','.');?></th>
				<th align="right"><?php echo number_format($total_nilai_wl_sc,0,',','.');?></th>
				<th align="right"><?php echo number_format(0,2,',','.');?></th>
				<th align="right" style="border-right:1px solid black;"><?php echo number_format(0,0,',','.');?></th>
	        </tr>
			<tr class="table-baris1">
				<th align="center" style="border-left:1px solid black;">5.</th>			
				<th align="left">BBM Solar (SC)</th>
				<th align="center" style="border-right:1px solid black;"><?php
				$null = '-';
				if (!empty($pembelian_bbm_sc_measure)) {
					echo $pembelian_bbm_sc_measure;
				} else {    
					echo $null;
				}
				?></th>
				<th align="right"><?php echo number_format(0,2,',','.');?></th>
				<th align="right"><?php echo number_format(0,0,',','.');?></th>
				<th align="right"><?php echo number_format(0,0,',','.');?></th>
				<th align="right"><?php echo number_format($total_vol_bbm_sc,2,',','.');?></th>
				<th align="right"><?php echo number_format($harsat_bbm_sc,0,',','.');?></th>
				<th align="right"><?php echo number_format($total_nilai_bbm_sc,0,',','.');?></th>
				<th align="right"><?php echo number_format(0,2,',','.');?></th>
				<th align="right" style="border-right:1px solid black;"><?php echo number_format(0,0,',','.');?></th>
	        </tr>
			<tr class="table-total">		
				<th align="right" colspan="3" style="background-color:#FFFF00; border:1px solid black;">TOTAL GROUP SC</th>
				<th align="right" style="background-color:#FFFF00; border-top:1px solid black; border-bottom:1px solid black;"></th>
				<th align="right" style="background-color:#FFFF00; border-top:1px solid black; border-bottom:1px solid black;"></th>
				<th align="right" style="background-color:#FFFF00; border-top:1px solid black; border-bottom:1px solid black;"><?php echo number_format($total_nilai_rap_sc,0,',','.');?></th>
				<th align="right" style="background-color:#FFFF00; border-top:1px solid black; border-bottom:1px solid black;"></th>
				<th align="right" style="background-color:#FFFF00; border-top:1px solid black; border-bottom:1px solid black;"></th>
				<th align="right" style="background-color:#FFFF00; border-top:1px solid black; border-bottom:1px solid black;"><?php echo number_format($total_nilai_realisasi_sc,0,',','.');?></th>
				<th align="right" style="background-color:#FFFF00; border-top:1px solid black; border-bottom:1px solid black;"></th>
				<th align="right" style="background-color:#FFFF00; border-right:1px solid black; border-top:1px solid black; border-bottom:1px solid black;"><?php echo number_format($total_nilai_evaluasi_sc,0,',','.');?></th>
	        </tr>
			<tr class="table-total">
				<th align="center" colspan="3" style="border-left:1px solid black; border-right:1px solid black;">
				<div align="left" style="display: block;font-weight: bold;text-transform:uppercase;">C. GROUP QUARRY</div>
				</th>
				<th align="center" colspan="8" style="border-right:1px solid black;"></th>
			</tr>
			<tr class="table-baris1">
				<th align="center" style="border-left:1px solid black;">1.</th>
				<th align="left" colspan="2" style="border-right:1px solid black;">Excavator</th>
				<th align="left" colspan="8" style="border-right:1px solid black;"></th>
	        </tr>
			<?php
			$alpha = array('a','b','c','d','e','f','g','h','i','j','k','l','m','n','o','p','q','r','s','t','u','v','w','x','y','z');
			$ev_exc_price = 0;
			foreach ($pembelian_exc as $key => $x):
			$ev_exc_price = 0 - $x['price'];
			$styleColorP = $ev_exc_price < 0 ? 'color:red' : 'color:black';
			?>
			<tr class="table-baris1">
				<th align="center" style="border-left:1px solid black;"></th>			
				<th align="left"><?php echo $alpha[$key++];?>. <?= $x['nama_produk'] ?></th>
				<th align="center" style="border-right:1px solid black;"><?= $x['measure'] ?></th>
				<th align="right"><?php echo number_format(0,2,',','.');?></th>
				<th align="right"><?php echo number_format(0,0,',','.');?></th>
				<th align="right"><?php echo number_format(0,0,',','.');?></th>
				<th align="right"><?php echo number_format($x['volume'],2,',','.');?></th>
				<th align="right"><?php echo number_format($x['price'] / $x['volume'],0,',','.');?></th>
				<th align="right"><?php echo number_format($x['price'],0,',','.');?></th>
				<th align="right"><?php echo number_format(0,2,',','.');?></th>
				<th align="right" style="border-right:1px solid black;"><?php echo number_format(0,0,',','.');?></th>
	        </tr>
			<?php endforeach; ?>
			<tr class="table-baris1">
				<th align="center" style="border-left:1px solid black;">2.</th>
				<th align="left" colspan="2" style="border-right:1px solid black;">Dump Truck 4M3</th>
				<th align="left" colspan="8" style="border-right:1px solid black;"></th>
	        </tr>
			<?php
			$alpha = array('a','b','c','d','e','f','g','h','i','j','k','l','m','n','o','p','q','r','s','t','u','v','w','x','y','z');
			$ev_dmp_4m3_price = 0;
			foreach ($pembelian_dmp_4m3 as $key => $x):
			$ev_dmp_4m3_price = 0 - $x['price'];
			$styleColorQ = $ev_dmp_4m3_price < 0 ? 'color:red' : 'color:black';
			?>
			<tr class="table-baris1">
				<th align="center" style="border-left:1px solid black;"></th>			
				<th align="left"><?php echo $alpha[$key++];?>. <?= $x['nama_produk'] ?></th>
				<th align="center" style="border-right:1px solid black;"><?= $x['measure'] ?></th>
				<th align="right"><?php echo number_format(0,2,',','.');?></th>
				<th align="right"><?php echo number_format(0,0,',','.');?></th>
				<th align="right"><?php echo number_format(0,0,',','.');?></th>
				<th align="right"><?php echo number_format($x['volume'],2,',','.');?></th>
				<th align="right"><?php echo number_format($x['price'] / $x['volume'],0,',','.');?></th>
				<th align="right"><?php echo number_format($x['price'],0,',','.');?></th>
				<th align="right"><?php echo number_format(0,2,',','.');?></th>
				<th align="right" style="border-right:1px solid black;"><?php echo number_format(0,0,',','.');?></th>
	        </tr>
			<?php endforeach; ?>
			<tr class="table-baris1">
				<th align="center" style="border-left:1px solid black;">3.</th>			
				<th align="left">BBM Solar (QUARRY)</th>
				<th align="center" style="border-right:1px solid black;"><?php
				$null = '-';
				if (!empty($pembelian_bbm_q_measure)) {
					echo $pembelian_bbm_q_measure;
				} else {    
					echo $null;
				}
				?></th>
				<th align="right"><?php echo number_format(0,2,',','.');?></th>
				<th align="right"><?php echo number_format(0,0,',','.');?></th>
				<th align="right"><?php echo number_format(0,0,',','.');?></th>
				<th align="right"><?php echo number_format($total_vol_bbm_q,2,',','.');?></th>
				<th align="right"><?php echo number_format($harsat_bbm_q,0,',','.');?></th>
				<th align="right"><?php echo number_format($total_nilai_bbm_q,0,',','.');?></th>
				<th align="right"><?php echo number_format(0,2,',','.');?></th>
				<th align="right" style="border-right:1px solid black;"><?php echo number_format(0,0,',','.');?></th>
	        </tr>
			<tr class="table-total">		
				<th align="right" colspan="3" style="background-color:#FFFF00; border-left:1px solid black; border-right:1px solid black; border-top:1px solid black;">TOTAL GROUP QUARRY</th>
				<th align="right" style="background-color:#FFFF00; border-top:1px solid black;"></th>
				<th align="right" style="background-color:#FFFF00; border-top:1px solid black;"></th>
				<th align="right" style="background-color:#FFFF00; border-top:1px solid black;"><?php echo number_format($total_nilai_rap_q,0,',','.');?></th>
				<th align="right" style="background-color:#FFFF00; border-top:1px solid black;"></th>
				<th align="right" style="background-color:#FFFF00; border-top:1px solid black;"></th>
				<th align="right" style="background-color:#FFFF00; border-top:1px solid black;"><?php echo number_format($total_nilai_realisasi_q,0,',','.');?></th>
				<th align="right" style="background-color:#FFFF00; border-top:1px solid black;"></th>
				<th align="right" style="background-color:#FFFF00; border-right:1px solid black; border-top:1px solid black;"><?php echo number_format($total_nilai_evaluasi_q,0,',','.');?></th>
	        </tr>
			<tr class="table-total">		
				<th align="right" colspan="3" style="background-color:#FFFF00; border:1px solid black;">TOTAL GROUP A + B + C</th>
				<th align="right" style="background-color:#FFFF00; border-bottom:1px solid black; border-top:1px solid black;"></th>
				<th align="right" style="background-color:#FFFF00; border-bottom:1px solid black; border-top:1px solid black;"></th>
				<th align="right" style="background-color:#FFFF00; border-bottom:1px solid black; border-top:1px solid black;"><?php echo number_format($total_nilai_rap_all,0,',','.');?></th>
				<th align="right" style="background-color:#FFFF00; border-bottom:1px solid black; border-top:1px solid black;"></th>
				<th align="right" style="background-color:#FFFF00; border-bottom:1px solid black; border-top:1px solid black;"></th>
				<th align="right" style="background-color:#FFFF00; border-bottom:1px solid black; border-top:1px solid black;"><?php echo number_format($total_nilai_realisasi_all,0,',','.');?></th>
				<th align="right" style="background-color:#FFFF00; border-bottom:1px solid black; border-top:1px solid black;"></th>
				<th align="right" style="<?php echo $styleColorK ?>; background-color:#FFFF00; border-bottom:1px solid black; border-right:1px solid black; border-top:1px solid black;"><?php echo number_format($total_nilai_evaluasi_all,0,',','.');?></th>
	        </tr>
	    </table>
		<br /><br /><br /><br /><br /><br /><br /><br /><br />
		<table width="98%" border="0" cellpadding="0">
			<tr >
				<td width="5%"></td>
				<td width="90%">
					<table width="100%" border="0" cellpadding="2">
						<tr>
							<td align="center" >
								Disetujui Oleh
							</td>
							<td align="center">
								Dibuat Oleh
							</td>
						</tr>
						<tr class="">
							<td align="center">
							
							</td>
							<?php
								$create = $this->db->select('*')
								->from('akumulasi')
								->where("(date_akumulasi = '$end_date')")
								->get()->row_array();

                                $this->db->select('g.admin_group_name, a.admin_ttd, a.admin_name');
                                $this->db->join('tbl_admin_group g','a.admin_group_id = g.admin_group_id','left');
                                $this->db->where('a.admin_id',$create['unit_head']);
                                $unit_head = $this->db->get('tbl_admin a')->row_array();

								$this->db->select('g.admin_group_name, a.admin_ttd');
                                $this->db->join('tbl_admin_group g','a.admin_group_id = g.admin_group_id','left');
                                $this->db->where('a.admin_id',$create['logistik']);
                                $logistik = $this->db->get('tbl_admin a')->row_array();
                            ?>
							<td align="center">
								<img src="<?= $unit_head['admin_ttd']?>" width="70px">
								<!--<img src="<?= $logistik['admin_ttd']?>" width="20px">-->
							</td>
						</tr>
						<tr>
							<td align="center">
								<b><u>Deddy Sarwobiso</u><br />
								Direktur Utama</b>
							</td>
							<td align="center">
								<b><u><?= $unit_head['admin_name']?></u><br />
								Kepala Unit Proyek</b>
							</td>
						</tr>
					</table>
				</td>
				<td width="5%"></td>
			</tr>
		</table>
	</body>
</html>