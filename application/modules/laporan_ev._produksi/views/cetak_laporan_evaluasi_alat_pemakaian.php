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
			background-color: #cccccc;
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
		<div align="center" style="display: block;font-weight: bold;font-size: 11px;">Evaluasi Biaya Peralatan</div>
		<div align="center" style="display: block;font-weight: bold;font-size: 11px;">Proyek Bendungan Tiga Dihaji</div>
		<div align="center" style="display: block;font-weight: bold;font-size: 11px;">Periode <?php echo str_replace($search, $replace, $subject);?></div>
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

			$angsuran_batching_plant_biaya = $this->db->select('sum(pdb.jumlah) as total')
			->from('pmm_biaya pb ')
			->join('pmm_detail_biaya pdb','pb.id = pdb.biaya_id','left')
			->join('pmm_coa c','pdb.akun = c.id','left')
			->where("pdb.akun = 159")
			->where("pb.status = 'PAID'")
			->where("(pb.tanggal_transaksi between '$date1' and '$date2')")
			->get()->row_array();
	
			$angsuran_batching_plant_jurnal = $this->db->select('sum(pdb.debit) as total')
			->from('pmm_jurnal_umum pb ')
			->join('pmm_detail_jurnal pdb','pb.id = pdb.jurnal_id','left')
			->join('pmm_coa c','pdb.akun = c.id','left')
			->where("pdb.akun = 159")
			->where("pb.status = 'PAID'")
			->where("(pb.tanggal_transaksi between '$date1' and '$date2')")
			->get()->row_array();
			$total_nilai_angsuran_batching_plant = $angsuran_batching_plant_biaya['total'] + $angsuran_batching_plant_jurnal['total'];

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
			->where("pdb.akun = 139")
			->where("pb.status = 'PAID'")
			->where("(pb.tanggal_transaksi between '$date1' and '$date2')")
			->get()->row_array();
			$total_nilai_penyusutan_wheel_loader = $penyusutan_wheel_loader_biaya['total'] + $penyusutan_wheel_loader_jurnal['total'];

			$angsuran_wheel_loader_biaya = $this->db->select('sum(pdb.jumlah) as total')
			->from('pmm_biaya pb ')
			->join('pmm_detail_biaya pdb','pb.id = pdb.biaya_id','left')
			->join('pmm_coa c','pdb.akun = c.id','left')
			->where("pdb.akun = 160")
			->where("pb.status = 'PAID'")
			->where("(pb.tanggal_transaksi between '$date1' and '$date2')")
			->get()->row_array();
	
			$angsuran_wheel_loader_jurnal = $this->db->select('sum(pdb.debit) as total')
			->from('pmm_jurnal_umum pb ')
			->join('pmm_detail_jurnal pdb','pb.id = pdb.jurnal_id','left')
			->join('pmm_coa c','pdb.akun = c.id','left')
			->where("pdb.akun = 160")
			->where("pb.status = 'PAID'")
			->where("(pb.tanggal_transaksi between '$date1' and '$date2')")
			->get()->row_array();
			$total_nilai_angsuran_wheel_loader = $angsuran_wheel_loader_biaya['total'] + $angsuran_wheel_loader_jurnal['total'];

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

			$alat_truck_mixer_biaya = $this->db->select('sum(pdb.jumlah) as total')
			->from('pmm_biaya pb ')
			->join('pmm_detail_biaya pdb','pb.id = pdb.biaya_id','left')
			->join('pmm_coa c','pdb.akun = c.id','left')
			->where("pdb.akun = 124")
			->where("pb.status = 'PAID'")
			->where("(pb.tanggal_transaksi between '$date1' and '$date2')")
			->get()->row_array();
	
			$alat_truck_mixer_jurnal = $this->db->select('sum(pdb.debit) as total')
			->from('pmm_jurnal_umum pb ')
			->join('pmm_detail_jurnal pdb','pb.id = pdb.jurnal_id','left')
			->where("pdb.akun = 124")
			->where("pb.status = 'PAID'")
			->where("(pb.tanggal_transaksi between '$date1' and '$date2')")
			->get()->row_array();
			$total_nilai_alat_truck_mixer = $alat_truck_mixer_biaya['total'] + $alat_truck_mixer_jurnal['total'];

			$pemeliharaan_truck_mixer_biaya = $this->db->select('sum(pdb.jumlah) as total')
			->from('pmm_biaya pb ')
			->join('pmm_detail_biaya pdb','pb.id = pdb.biaya_id','left')
			->join('pmm_coa c','pdb.akun = c.id','left')
			->where("pdb.akun = 161")
			->where("pb.status = 'PAID'")
			->where("(pb.tanggal_transaksi between '$date1' and '$date2')")
			->get()->row_array();
	
			$pemeliharaan_truck_mixer_jurnal = $this->db->select('sum(pdb.debit) as total')
			->from('pmm_jurnal_umum pb ')
			->join('pmm_detail_jurnal pdb','pb.id = pdb.jurnal_id','left')
			->where("pdb.akun = 161")
			->where("pb.status = 'PAID'")
			->where("(pb.tanggal_transaksi between '$date1' and '$date2')")
			->get()->row_array();
			$total_nilai_pemeliharaan_truck_mixer = $pemeliharaan_truck_mixer_biaya['total'] + $pemeliharaan_truck_mixer_jurnal['total'];

			$insentif_truck_mixer_biaya = $this->db->select('sum(pdb.jumlah) as total')
			->from('pmm_biaya pb ')
			->join('pmm_detail_biaya pdb','pb.id = pdb.biaya_id','left')
			->join('pmm_coa c','pdb.akun = c.id','left')
			->where("pdb.akun = 186")
			->where("pb.status = 'PAID'")
			->where("(pb.tanggal_transaksi between '$date1' and '$date2')")
			->get()->row_array();

			$insentif_truck_mixer_jurnal = $this->db->select('sum(pdb.debit) as total')
			->from('pmm_jurnal_umum pb ')
			->join('pmm_detail_jurnal pdb','pb.id = pdb.jurnal_id','left')
			->where("pdb.akun = 186")
			->where("pb.status = 'PAID'")
			->where("(pb.tanggal_transaksi between '$date1' and '$date2')")
			->get()->row_array();
			$total_nilai_insentif_truck_mixer = $insentif_truck_mixer_biaya['total'] + $insentif_truck_mixer_jurnal['total'];

			$mobilisasi_truck_mixer_biaya = $this->db->select('sum(pdb.jumlah) as total')
			->from('pmm_biaya pb ')
			->join('pmm_detail_biaya pdb','pb.id = pdb.biaya_id','left')
			->join('pmm_coa c','pdb.akun = c.id','left')
			->where("pdb.akun = 187")
			->where("pb.status = 'PAID'")
			->where("(pb.tanggal_transaksi between '$date1' and '$date2')")
			->get()->row_array();

			$mobilisasi_truck_mixer_jurnal = $this->db->select('sum(pdb.debit) as total')
			->from('pmm_jurnal_umum pb ')
			->join('pmm_detail_jurnal pdb','pb.id = pdb.jurnal_id','left')
			->where("pdb.akun = 187")
			->where("pb.status = 'PAID'")
			->where("(pb.tanggal_transaksi between '$date1' and '$date2')")
			->get()->row_array();
			$total_nilai_mobilisasi_truck_mixer = $mobilisasi_truck_mixer_biaya['total'] + $mobilisasi_truck_mixer_jurnal['total'];

			$pemakaian_solar = $this->db->select('sum(volume) as volume, sum(nilai) as nilai')
			->from('pemakaian_bahan')
			->where("date between '$date1' and '$date2'")
			->where("material_id = 5")
			->where("status = 'PUBLISH'")
			->get()->row_array();

			$pemakaian_volume_solar = $pemakaian_solar['volume'];
			$pemakaian_nilai_solar = $pemakaian_solar['nilai'];
			$pemakaian_harsat_solar = ($pemakaian_volume_solar!=0)?$pemakaian_nilai_solar / $pemakaian_volume_solar * 1:0;

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
			->group_by("rap.id")
			->order_by('rap.tanggal_rap_alat','desc')->limit(1)
			->get()->result_array();

			foreach ($rap_alat as $x){
				$vol_rap_batching_plant = $x['vol_batching_plant'];
				$vol_rap_wheel_loader = $x['vol_wheel_loader'];
				$vol_rap_truck_mixer = $x['vol_truck_mixer'];
				$vol_rap_bbm_solar = $x['vol_bbm_solar'];
				$harsat_batching_plant = $x['batching_plant'];
				$harsat_wheel_loader = $x['wheel_loader'];
				$harsat_truck_mixer = $x['truck_mixer'];
				$harsat_bbm_solar = $x['vol_bbm_solar'] * $x['harsat_bbm_solar'];
				
			}

			$vol_batching_plant = $total_volume;
			$vol_wheel_loader = $total_volume;
			$vol_truck_mixer = $total_volume;
			$vol_bbm_solar = $total_volume;

			$batching_plant = $harsat_batching_plant * $total_volume;
			$wheel_loader = $harsat_wheel_loader * $total_volume;
			$truck_mixer = $harsat_truck_mixer * $total_volume;
			$bbm_solar = $harsat_bbm_solar * $vol_bbm_solar;

			$harsat_batching_plant = ($vol_batching_plant!=0)?$batching_plant / $vol_batching_plant * 1:0;
			$harsat_truck_mixer = ($vol_truck_mixer!=0)?$truck_mixer / $vol_truck_mixer * 1:0;
			$harsat_wheel_loader = ($wheel_loader!=0)?$wheel_loader / $vol_wheel_loader * 1:0;
			$harsat_bbm_solar = ($vol_bbm_solar!=0)?$bbm_solar / $vol_bbm_solar * 1:0;
			$total_nilai_rap_alat = $batching_plant + $truck_mixer + $wheel_loader + $bbm_solar;

			$pemakaian_vol_batching_plant = 0;
			$pemakaian_vol_pemeliharaan_batching_plant = 0;
			$pemakaian_vol_penyusutan_batching_plant = $total_volume;
			$pemakaian_vol_truck_mixer = $total_vol_truck_mixer;
			$pemakaian_vol_wheel_loader = 0;
			$pemakaian_vol_pemeliharaan_wheel_loader = 0;
			$pemakaian_vol_penyusutan_wheel_loader = $total_volume;
			$pemakaian_vol_bbm_solar = $total_volume_pemakaian_solar;
			
			//SPESIAL//
			$total_pemakaian_pemeliharaan_batching_plant = $total_nilai_pemeliharaan_batching_plant;
			$total_pemakaian_penyusutan_batching_plant = $total_nilai_penyusutan_batching_plant;
			$total_pemakaian_angsuran_batching_plant = $total_nilai_angsuran_batching_plant;
			$total_pemakaian_batching_plant = $total_nilai_batching_plant + $total_pemakaian_pemeliharaan_batching_plant + $total_nilai_angsuran_batching_plant + $total_nilai_penyusutan_batching_plant;
			$total_pemakaian_truck_mixer = $total_nilai_truck_mixer + $total_nilai_alat_truck_mixer + $total_nilai_pemeliharaan_truck_mixer + $total_nilai_insentif_truck_mixer + $total_nilai_mobilisasi_truck_mixer;
			$total_pemakaian_pemeliharaan_truck_mixer = $total_nilai_pemeliharaan_truck_mixer;
			$total_pemakaian_pemeliharaan_wheel_loader = $total_nilai_pemeliharaan_wheel_loader;
			$total_pemakaian_insentif_truck_mixer =  $total_nilai_insentif_truck_mixer;
			$total_pemakaian_mobilisasi_truck_mixer = $total_nilai_mobilisasi_truck_mixer;
			$total_pemakaian_penyusutan_wheel_loader = $total_nilai_penyusutan_wheel_loader;
			$total_pemakaian_angsuran_wheel_loader = $total_nilai_angsuran_wheel_loader;
			$total_pemakaian_wheel_loader = $total_nilai_wheel_loader + $total_pemakaian_pemeliharaan_wheel_loader + $total_nilai_angsuran_wheel_loader + $total_nilai_penyusutan_wheel_loader;
			$total_pemakaian_bbm_solar = $total_akumulasi_bbm;
			//SPESIAL//
	
			$total_vol_evaluasi_batching_plant = ($pemakaian_vol_batching_plant!=0)?$vol_batching_plant - $pemakaian_vol_batching_plant * 1:0;
			$total_nilai_evaluasi_batching_plant = ($total_pemakaian_batching_plant!=0)?$batching_plant - $total_pemakaian_batching_plant * 1:0;
			$total_vol_evaluasi_pemeliharaan_batching_plant = ($pemakaian_vol_pemeliharaan_batching_plant!=0)?$vol_pemeliharaan_batching_plant - $pemakaian_vol_pemeliharaan_batching_plant * 1:0;
			$total_nilai_evaluasi_pemeliharaan_batching_plant = ($total_pemakaian_pemeliharaan_batching_plant!=0)?$pemeliharaan_batching_plant - $total_pemakaian_pemeliharaan_batching_plant * 1:0;
			$total_vol_evaluasi_penyusutan_batching_plant = ($pemakaian_vol_penyusutan_batching_plant!=0)?$vol_penyusutan_batching_plant - $pemakaian_vol_penyusutan_batching_plant * 1:0;
			$total_nilai_evaluasi_penyusutan_batching_plant = ($total_pemakaian_penyusutan_batching_plant!=0)?$penyusutan_batching_plant - $total_pemakaian_penyusutan_batching_plant * 1:0;
			$total_vol_evaluasi_angsuran_batching_plant = ($pemakaian_vol_angsuran_batching_plant!=0)?$vol_angsuran_batching_plant - $pemakaian_vol_angsuran_batching_plant * 1:0;
			$total_nilai_evaluasi_angsuran_batching_plant = ($total_pemakaian_angsuran_batching_plant!=0)?$angsuran_batching_plant - $total_pemakaian_angsuran_batching_plant * 1:0;
			$total_vol_evaluasi_truck_mixer = ($pemakaian_vol_truck_mixer!=0)?$vol_truck_mixer - $pemakaian_vol_truck_mixer * 1:0;
			$total_nilai_evaluasi_truck_mixer = ($total_pemakaian_truck_mixer!=0)?$truck_mixer - $total_pemakaian_truck_mixer * 1:0;
			$total_vol_evaluasi_wheel_loader = ($pemakaian_vol_wheel_loader!=0)?$vol_wheel_loader - $pemakaian_vol_wheel_loader * 1:0;
			$total_nilai_evaluasi_wheel_loader = ($total_pemakaian_wheel_loader!=0)?$wheel_loader - $total_pemakaian_wheel_loader * 1:0;
			$total_vol_evaluasi_pemeliharaan_wheel_loader = ($pemakaian_vol_pemeliharaan_wheel_loader!=0)?$vol_pemeliharaan_wheel_loader - $pemakaian_vol_pemeliharaan_wheel_loader * 1:0;
			$total_nilai_evaluasi_pemeliharaan_wheel_loader = ($total_pemakaian_pemeliharaan_wheel_loader!=0)?$pemeliharaan_wheel_loader - $total_pemakaian_pemeliharaan_wheel_loader * 1:0;
			$total_vol_evaluasi_penyusutan_wheel_loader = ($pemakaian_vol_penyusutan_wheel_loader!=0)?$vol_penyusutan_wheel_loader - $pemakaian_vol_penyusutan_wheel_loader * 1:0;
			$total_nilai_evaluasi_penyusutan_wheel_loader = ($total_pemakaian_penyusutan_wheel_loader!=0)?$penyusutan_wheel_loader - $total_pemakaian_penyusutan_wheel_loader * 1:0;
			$total_vol_evaluasi_angsuran_wheel_loader = ($pemakaian_vol_angsuran_wheel_loader!=0)?$vol_angsuran_wheel_loader - $pemakaian_vol_angsuran_wheel_loader * 1:0;
			$total_nilai_evaluasi_angsuran_wheel_loader = ($total_pemakaian_angsuran_wheel_loader!=0)?$angsuran_wheel_loader - $total_pemakaian_angsuran_wheel_loader * 1:0;
			$total_vol_evaluasi_bbm_solar = ($pemakaian_volume_solar!=0)?($vol_rap_bbm_solar * $total_volume) - $pemakaian_volume_solar * 1:0;
			$total_nilai_evaluasi_bbm_solar = ($pemakaian_nilai_solar!=0)?$bbm_solar - $pemakaian_nilai_solar * 1:0;

			$total_vol_rap_alat = $total_volume;
			$total_nilai_rap_alat = $batching_plant + $truck_mixer + $wheel_loader + $bbm_solar;
			$total_vol_realisasi_alat = $pemakaian_vol_batching_plant + $pemakaian_vol_truck_mixer + $pemakaian_vol_wheel_loader + $pemakaian_volume_solar;
			$total_nilai_realisasi_alat = $total_pemakaian_batching_plant + $total_pemakaian_truck_mixer + $total_pemakaian_wheel_loader + $pemakaian_nilai_solar;
			$total_vol_evaluasi_alat = $total_vol_evaluasi_batching_plant + $total_vol_evaluasi_truck_mixer + $total_vol_evaluasi_wheel_loader + $total_vol_evaluasi_bbm_solar;
			$total_nilai_evaluasi_alat = $total_nilai_evaluasi_batching_plant + $total_nilai_evaluasi_truck_mixer + $total_nilai_evaluasi_wheel_loader + $total_nilai_evaluasi_bbm_solar;
			?>
			
			<tr class="table-judul">
				<th width="5%" align="center" rowspan="2" style="background-color:#e69500; border-top:1px solid black; border-left:1px solid black; border-bottom:1px solid black;">&nbsp;<br>NO.</th>
				<th width="20%" align="center" rowspan="2" style="background-color:#e69500; border-top:1px solid black; border-bottom:1px solid black;">&nbsp;<br>URAIAN</th>
				<th width="8%" align="center" rowspan="2" style="background-color:#e69500; border-top:1px solid black; border-bottom:1px solid black; border-right:1px solid black;">&nbsp;<br>SATUAN</th>
				<th width="25%" align="center" colspan="3" style="background-color:#e69500; border-top:1px solid black; border-left:1px solid black; border-bottom:1px solid black; border-right:1px solid black;">RAP</th>
				<th width="25%" align="center" colspan="3" style="background-color:#e69500; border-top:1px solid black; border-bottom:1px solid black; border-right:1px solid black;">REALISASI</th>
				<th width="17%" align="center" colspan="2" style="background-color:#e69500; border-top:1px solid black; border-right:1px solid black; border-bottom:1px solid black;">DEVIASI</th>
			</tr>
			<tr class="table-judul">
				<th width="7%" align="center" style="background-color:#e69500; border-left:1px solid black; border-bottom:1px solid black;">VOLUME</th>
				<th width="8%" align="center" style="background-color:#e69500; border-bottom:1px solid black;">HARSAT</th>
				<th width="10%" align="center" style="background-color:#e69500; border-bottom:1px solid black; border-right:1px solid black;">NILAI</th>
				<th width="7%" align="center" style="background-color:#e69500; border-bottom:1px solid black;">VOLUME</th>
				<th width="8%" align="center" style="background-color:#e69500; border-bottom:1px solid black;">HARSAT</th>
				<th width="10%" align="center" style="background-color:#e69500; border-bottom:1px solid black; border-right:1px solid black;">NILAI</th>
				<th width="7%" align="center" style="background-color:#e69500; border-bottom:1px solid black;">VOLUME</th>
				<th width="10%" align="center" style="background-color:#e69500; border-bottom:1px solid black; border-right:1px solid black;">NILAI</th>
			</tr>
			<tr class="table-baris1">
				<th align="center" style="border-left:1px solid black;">1.</th>			
				<th align="left">Batching Plant + Genset</th>
				<th align="center" style="border-right:1px solid black;">M3</th>
				<th align="right"><?php echo number_format($vol_batching_plant,2,',','.');?></th>
				<th align="right"><?php echo number_format($harsat_batching_plant,0,',','.');?></th>
				<th align="right" style="border-right:1px solid black;"><?php echo number_format($batching_plant,0,',','.');?></th>
				<th align="right"><?php echo number_format($pemakaian_vol_batching_plant,2,',','.');?></th>
				<th align="right"><?php echo number_format(0,0,',','.');?></th>
				<th align="right" style="border-right:1px solid black;"><a target="_blank" href="<?= base_url("laporan/cetak_detail_bp?filter_date=".$filter_date = date('d-m-Y',strtotime($date1)).' - '.date('d-m-Y',strtotime($date2))) ?>"><?php echo number_format($total_pemakaian_batching_plant,0,',','.');?></a></th>
				<?php
				$styleColor = $total_vol_evaluasi_batching_plant < 0 ? 'color:red' : 'color:black';
				?>
				<th align="right" style="<?php echo $styleColor ?>"><?php echo $total_vol_evaluasi_batching_plant < 0 ? "(".number_format(-$total_vol_evaluasi_batching_plant,2,',','.').")" : number_format($total_vol_evaluasi_batching_plant,2,',','.');?></th>
				<?php
				$styleColor = $total_nilai_evaluasi_batching_plant < 0 ? 'color:red' : 'color:black';
				?>
				<th align="right" style="<?php echo $styleColor ?>; border-right:1px solid black;"><?php echo $total_nilai_evaluasi_batching_plant < 0 ? "(".number_format(-$total_nilai_evaluasi_batching_plant,0,',','.').")" : number_format($total_nilai_evaluasi_batching_plant,0,',','.');?></th>
	        </tr>
			<tr class="table-baris1">
				<th align="center" style="border-left:1px solid black;">2.</th>			
				<th align="left">Wheel Loader</th>
				<th align="center" style="border-right:1px solid black;">M3</th>
				<th align="right"><?php echo number_format($vol_wheel_loader,2,',','.');?></th>
				<th align="right"><?php echo number_format($harsat_wheel_loader,0,',','.');?></th>
				<th align="right" style="border-right:1px solid black;"><?php echo number_format($wheel_loader,0,',','.');?></th>
				<th align="right"><?php echo number_format($pemakaian_vol_wheel_loader,2,',','.');?></th>
				<th align="right"><?php echo number_format(0,0,',','.');?></th>
				<th align="right" style="border-right:1px solid black;"><a target="_blank" href="<?= base_url("laporan/cetak_detail_wl?filter_date=".$filter_date = date('d-m-Y',strtotime($date1)).' - '.date('d-m-Y',strtotime($date2))) ?>"><?php echo number_format($total_pemakaian_wheel_loader,0,',','.');?></a></th>
				<?php
				$styleColor = $total_vol_evaluasi_wheel_loader < 0 ? 'color:red' : 'color:black';
				?>
				<th align="right" style="<?php echo $styleColor ?>"><?php echo $total_vol_evaluasi_wheel_loader < 0 ? "(".number_format(-$total_vol_evaluasi_wheel_loader,2,',','.').")" : number_format($total_vol_evaluasi_wheel_loader,2,',','.');?></th>
				<?php
				$styleColor = $total_nilai_evaluasi_wheel_loader < 0 ? 'color:red' : 'color:black';
				?>
				<th align="right" style="<?php echo $styleColorF ?>; border-right:1px solid black;"><?php echo $total_nilai_evaluasi_wheel_loader < 0 ? "(".number_format(-$total_nilai_evaluasi_wheel_loader,0,',','.').")" : number_format($total_nilai_evaluasi_wheel_loader,0,',','.');?></th>
	        </tr>
			<tr class="table-baris1">
				<th align="center" style="border-left:1px solid black;">2.</th>			
				<th align="left">Truck Mixer</th>
				<th align="center" style="border-right:1px solid black;">M3</th>
				<th align="right"><?php echo number_format($vol_truck_mixer,2,',','.');?></th>
				<th align="right"><?php echo number_format($harsat_truck_mixer,0,',','.');?></th>
				<th align="right" style="border-right:1px solid black;"><?php echo number_format($truck_mixer,0,',','.');?></th>
				<th align="right"><?php echo number_format($pemakaian_vol_truck_mixer,2,',','.');?></th>
				<?php
				$harsat_pemakaian_truck_mixer = ($pemakaian_vol_truck_mixer!=0)?$total_pemakaian_truck_mixer / $pemakaian_vol_truck_mixer * 1:0;
				?>
				<th align="right"><?php echo number_format($harsat_pemakaian_truck_mixer,0,',','.');?></th>
				<th align="right" style="border-right:1px solid black;"><a target="_blank" href="<?= base_url("laporan/cetak_detail_tm?filter_date=".$filter_date = date('d-m-Y',strtotime($date1)).' - '.date('d-m-Y',strtotime($date2))) ?>"><?php echo number_format($total_pemakaian_truck_mixer,0,',','.');?></a></th>
				<?php
				$styleColor = $total_vol_evaluasi_truck_mixer < 0 ? 'color:red' : 'color:black';
				?>
				<th align="right" style="<?php echo $styleColor ?>"><?php echo $total_vol_evaluasi_truck_mixer < 0 ? "(".number_format(-$total_vol_evaluasi_truck_mixer,2,',','.').")" : number_format($total_vol_evaluasi_truck_mixer,2,',','.');?></th>
				<?php
				$styleColor = $total_nilai_evaluasi_truck_mixer < 0 ? 'color:red' : 'color:black';
				?>
				<th align="right" style="<?php echo $styleColor ?>; border-right:1px solid black;"><?php echo $total_nilai_evaluasi_truck_mixer < 0 ? "(".number_format(-$total_nilai_evaluasi_truck_mixer,0,',','.').")" : number_format($total_nilai_evaluasi_truck_mixer,0,',','.');?></th>
	        </tr>
			<tr class="table-baris1">
				<th align="center" style="border-left:1px solid black;">4.</th>			
				<th align="left">BBM Solar</th>
				<th align="center" style="border-right:1px solid black;">Liter</th>
				<th align="right"><?php echo number_format($vol_bbm_solar,2,',','.');?></th>
				<th align="right"><?php echo number_format($harsat_bbm_solar,0,',','.');?></th>
				<th align="right" style="border-right:1px solid black;"><?php echo number_format($bbm_solar,0,',','.');?></th>
				<th align="right"><?php echo number_format($pemakaian_volume_solar,2,',','.');?></th>
				<th align="right"><?php echo number_format($pemakaian_harsat_solar,0,',','.');?></th>
				<th align="right" style="border-right:1px solid black;"><a target="_blank" href="<?= base_url("laporan/cetak_detail_solar?filter_date=".$filter_date = date('d-m-Y',strtotime($date1)).' - '.date('d-m-Y',strtotime($date2))) ?>"><?php echo number_format($pemakaian_nilai_solar,0,',','.');?></a></th>
				<?php
				$styleColor = $total_vol_evaluasi_bbm_solar < 0 ? 'color:red' : 'color:black';
				?>
				<th align="right" style="<?php echo $styleColor ?>"><?php echo $total_vol_evaluasi_bbm_solar < 0 ? "(".number_format(-$total_vol_evaluasi_bbm_solar,2,',','.').")" : number_format($total_vol_evaluasi_bbm_solar,2,',','.');?></th>
				<?php
				$styleColor = $total_nilai_evaluasi_bbm_solar < 0 ? 'color:red' : 'color:black';
				?>
				<th align="right" style="<?php echo $styleColor ?>; border-right:1px solid black;"><?php echo $total_nilai_evaluasi_bbm_solar < 0 ? "(".number_format(-$total_nilai_evaluasi_bbm_solar,0,',','.').")" : number_format($total_nilai_evaluasi_bbm_solar,0,',','.');?></th>
	        </tr>
			<tr class="table-total">		
				<th align="right" colspan="3" style="border:1px solid black;">TOTAL</th>
				<th align="right" style="border-top:1px solid black; border-bottom:1px solid black;"></th>
				<th align="right" style="border-top:1px solid black; border-bottom:1px solid black;"></th>
				<th align="right" style="border-top:1px solid black; border-bottom:1px solid black; border-right:1px solid black;"><?php echo number_format($total_nilai_rap_alat,0,',','.');?></th>
				<th align="right" style="border-top:1px solid black; border-bottom:1px solid black;"></th>
				<th align="right" style="border-top:1px solid black; border-bottom:1px solid black;"></th>
				<th align="right" style="border-top:1px solid black; border-bottom:1px solid black; border-right:1px solid black;"><?php echo number_format($total_nilai_realisasi_alat,0,',','.');?></th>
				<th align="right" style="border-top:1px solid black; border-bottom:1px solid black;"></th>
				<?php
				$styleColor = $total_nilai_evaluasi_alat < 0 ? 'color:red' : 'color:black';
				?>
				<th align="right" style="<?php echo $styleColor ?>;border-right:1px solid black; border-top:1px solid black; border-bottom:1px solid black;"><?php echo $total_nilai_evaluasi_alat < 0 ? "(".number_format(-$total_nilai_evaluasi_alat,0,',','.').")" : number_format($total_nilai_evaluasi_alat,0,',','.');?></th>
	        </tr>
	    </table>
		<br /><br />
		<?php
		if(in_array($this->session->userdata('admin_id'), array(1,3))){
		?>
		<table width="98%" border="0" cellpadding="3" border="0">
			<?php
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

			$pembelian_solar = $this->db->select('prm.display_measure as satuan, SUM(prm.display_volume) as volume, (prm.display_price / prm.display_volume) as harga, SUM(prm.display_price) as nilai')
			->from('pmm_receipt_material prm')
			->join('pmm_purchase_order po', 'prm.purchase_order_id = po.id','left')
			->join('produk p', 'prm.material_id = p.id','left')
			->where("prm.date_receipt between '$date1' and '$date2'")
			->where("p.kategori_bahan = 5")
			->get()->row_array();
			
			$total_volume_solar = $pembelian_solar['volume'];
			$total_harsat_solar = $pembelian_solar['harga'];
			$total_nilai_solar = $pembelian_solar['nilai'];


			$vol_bbm_solar = $total_volume_solar;
			$bbm_solar = $total_nilai_solar;
			$harsat_bbm_solar = $total_harsat_solar;
			$total_nilai_rap_alat = $bbm_solar;
			$pemakaian_vol_bbm_solar = $total_volume_pemakaian_solar;
			
			//SPESIAL//
			$total_pemakaian_bbm_solar = $total_akumulasi_bbm;
			//SPESIAL//

			$total_vol_evaluasi_bbm_solar = ($pemakaian_volume_solar!=0)?($vol_bbm_solar) - $pemakaian_volume_solar * 1:0;
			$total_nilai_evaluasi_bbm_solar = ($pemakaian_nilai_solar!=0)?$bbm_solar - $pemakaian_nilai_solar * 1:0;

			$total_vol_rap_alat = $total_volume;
			$total_nilai_rap_alat = $bbm_solar;
			$total_vol_realisasi_alat = $pemakaian_volume_solar;
			$total_nilai_realisasi_alat = $pemakaian_nilai_solar;
			$total_vol_evaluasi_alat = $total_vol_evaluasi_bbm_solar;
			$total_nilai_evaluasi_alat = $total_nilai_evaluasi_bbm_solar;
			?>
			
			<tr class="table-judul">
				<th width="5%" align="center" rowspan="2" style="background-color:#e69500; border-top:1px solid black; border-left:1px solid black; border-bottom:1px solid black;">&nbsp;<br>NO.</th>
				<th width="20%" align="center" rowspan="2" style="background-color:#e69500; border-top:1px solid black; border-bottom:1px solid black;">&nbsp;<br>URAIAN</th>
				<th width="8%" align="center" rowspan="2" style="background-color:#e69500; border-top:1px solid black; border-bottom:1px solid black; border-right:1px solid black;">&nbsp;<br>SATUAN</th>
				<th width="25%" align="center" colspan="3" style="background-color:#e69500; border-top:1px solid black; border-left:1px solid black; border-bottom:1px solid black; border-right:1px solid black;">PEMBELIAN</th>
				<th width="25%" align="center" colspan="3" style="background-color:#e69500; border-top:1px solid black; border-bottom:1px solid black; border-right:1px solid black;">REALISASI</th>
				<th width="17%" align="center" colspan="2" style="background-color:#e69500; border-top:1px solid black; border-right:1px solid black; border-bottom:1px solid black;">DEVIASI</th>
			</tr>
			<tr class="table-judul">
				<th width="7%" align="center" style="background-color:#e69500; border-left:1px solid black; border-bottom:1px solid black;">VOLUME</th>
				<th width="8%" align="center" style="background-color:#e69500; border-bottom:1px solid black;">HARSAT</th>
				<th width="10%" align="center" style="background-color:#e69500; border-bottom:1px solid black; border-right:1px solid black;">NILAI</th>
				<th width="7%" align="center" style="background-color:#e69500; border-bottom:1px solid black;">VOLUME</th>
				<th width="8%" align="center" style="background-color:#e69500; border-bottom:1px solid black;">HARSAT</th>
				<th width="10%" align="center" style="background-color:#e69500; border-bottom:1px solid black; border-right:1px solid black;">NILAI</th>
				<th width="7%" align="center" style="background-color:#e69500; border-bottom:1px solid black;">VOLUME</th>
				<th width="10%" align="center" style="background-color:#e69500; border-bottom:1px solid black; border-right:1px solid black;">NILAI</th>
			</tr>
			<tr class="table-baris1">
				<th align="center" style="border-left:1px solid black;">4.</th>			
				<th align="left">BBM Solar</th>
				<th align="center" style="border-right:1px solid black;">Liter</th>
				<th align="right"><?php echo number_format($vol_bbm_solar,2,',','.');?></th>
				<th align="right"><?php echo number_format($harsat_bbm_solar,0,',','.');?></th>
				<th align="right" style="border-right:1px solid black;"><?php echo number_format($bbm_solar,0,',','.');?></th>
				<th align="right"><?php echo number_format($pemakaian_volume_solar,2,',','.');?></th>
				<th align="right"><?php echo number_format($pemakaian_harsat_solar,0,',','.');?></th>
				<th align="right" style="border-right:1px solid black;"><?php echo number_format($pemakaian_nilai_solar,0,',','.');?></th>
				<?php
				$styleColor = $total_vol_evaluasi_bbm_solar < 0 ? 'color:red' : 'color:black';
				?>
				<th align="right" style="<?php echo $styleColor ?>"><?php echo $total_vol_evaluasi_bbm_solar < 0 ? "(".number_format(-$total_vol_evaluasi_bbm_solar,2,',','.').")" : number_format($total_vol_evaluasi_bbm_solar,2,',','.');?></th>
				<?php
				$styleColor = $total_nilai_evaluasi_bbm_solar < 0 ? 'color:red' : 'color:black';
				?>
				<th align="right" style="<?php echo $styleColor ?>; border-right:1px solid black;"><?php echo $total_nilai_evaluasi_bbm_solar < 0 ? "(".number_format(-$total_nilai_evaluasi_bbm_solar,0,',','.').")" : number_format($total_nilai_evaluasi_bbm_solar,0,',','.');?></th>
			</tr>
			<tr class="table-total">		
				<th align="right" colspan="3" style="border:1px solid black;">TOTAL</th>
				<th align="right" style="border-top:1px solid black; border-bottom:1px solid black;"></th>
				<th align="right" style="border-top:1px solid black; border-bottom:1px solid black;"></th>
				<th align="right" style="border-top:1px solid black; border-bottom:1px solid black; border-right:1px solid black;"><?php echo number_format($total_nilai_rap_alat,0,',','.');?></th>
				<th align="right" style="border-top:1px solid black; border-bottom:1px solid black;"></th>
				<th align="right" style="border-top:1px solid black; border-bottom:1px solid black;"></th>
				<th align="right" style="border-top:1px solid black; border-bottom:1px solid black; border-right:1px solid black;"><?php echo number_format($total_nilai_realisasi_alat,0,',','.');?></th>
				<th align="right" style="border-top:1px solid black; border-bottom:1px solid black;"></th>
				<?php
				$styleColor = $total_nilai_evaluasi_alat < 0 ? 'color:red' : 'color:black';
				?>
				<th align="right" style="<?php echo $styleColor ?>;border-right:1px solid black; border-top:1px solid black; border-bottom:1px solid black;"><?php echo $total_nilai_evaluasi_alat < 0 ? "(".number_format(-$total_nilai_evaluasi_alat,0,',','.').")" : number_format($total_nilai_evaluasi_alat,0,',','.');?></th>
			</tr>
		</table>
		<?php
		}
		?>
		<br /><br /><br /><br /><br /><br /><br /><br /><br />
		<table width="98%" border="0" cellpadding="30">
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
							<td align="center" height="55px">
							
							</td>
							<td align="center">
								<img src="uploads/ttd_novel.png" width="50px">
							</td>
						</tr>
						<tr>
							<td align="center">
								<b><u>Deddy Sarwobiso</u><br />
								Direktur Utama</b>
							</td>
							<td align="center">
								<b><u>Novel Joko Tri Laksono</u><br />
								Ka. Plant</b>
							</td>
						</tr>
					</table>
				</td>
				<td width="5%"></td>
			</tr>
		</table>
	</body>
</html>