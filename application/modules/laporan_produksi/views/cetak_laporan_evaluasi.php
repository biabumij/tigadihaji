<!DOCTYPE html>
<html>
	<head>
	  <title>LAPORAN EVALUASI PEMAKAIAN BAHAN</title>
	  
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
			background-color: #F0F0F0;
			font-size: 7px;
		}

		table tr.table-baris1-bold{
			background-color: #F0F0F0;
			font-size: 7px;
			font-weight: bold;
		}
			
		table tr.table-baris2{
			font-size: 7px;
			background-color: #E8E8E8;
		}
			
		table tr.table-total{
			background-color: #cccccc;
			font-weight: bold;
			font-size: 7px;
			color: black;
		}
	  </style>

	</head>
	<body>
		<div align="center" style="display: block;font-weight: bold;font-size: 11px;">EVALUASI PEMAKAIAN BAHAN BAKU</div>
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
		
		<table width="98%" cellpadding="5">
		
			<!-- TOTAL PEMAKAIAN KOMPOSISI -->

			<?php

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
			
			?>

			<!-- END TOTAL PEMAKAIAN KOMPOSISI -->

			<?php
			$date1_ago = date('2020-01-01');
			$date2_ago = date('Y-m-d', strtotime('-1 days', strtotime($date1)));
			$date3_ago = date('Y-m-d', strtotime('-1 months', strtotime($date1)));
			$tanggal_opening_balance = date('Y-m-d', strtotime('-1 days', strtotime($date1)));

			$stock_opname_semen_ago = $this->db->select('pp.vol_nilai_semen as volume')
			->from('kunci_bahan_baku pp')
			->where("(pp.date = '$tanggal_opening_balance')")
			->order_by('pp.date','desc')->limit(1)
			->get()->row_array();
			
			$harga_semen = $this->db->select('pp.nilai_semen as nilai_semen')
			->from('kunci_bahan_baku pp')
			->where("(pp.date between '$date3_ago' and '$date2_ago')")
			->order_by('pp.date','desc')->limit(1)
			->get()->row_array();

			$pembelian_semen = $this->db->select('prm.display_measure as satuan, SUM(prm.display_volume) as volume, (prm.display_price / prm.display_volume) as harga, SUM(prm.display_price) as nilai')
			->from('pmm_receipt_material prm')
			->join('pmm_purchase_order po', 'prm.purchase_order_id = po.id','left')
			->join('produk p', 'prm.material_id = p.id','left')
			->where("prm.date_receipt between '$date1' and '$date2'")
			->where("prm.material_id = 1")
			->group_by('prm.material_id')
			->get()->row_array();

			$stok_volume_semen_lalu = $stock_opname_semen_ago['volume'];
			$stok_nilai_semen_lalu = $harga_semen['nilai_semen'];
			$stok_harsat_semen_lalu = (round($stok_volume_semen_lalu,2)!=0)?$stok_nilai_semen_lalu / round($stok_volume_semen_lalu,2) * 1:0;
		
			$pembelian_volume_semen = $pembelian_semen['volume'];
			$pembelian_nilai_semen = $pembelian_semen['nilai'];
			$pembelian_harga_semen = (round($pembelian_volume_semen,2)!=0)?$pembelian_nilai_semen / round($pembelian_volume_semen,2) * 1:0;

			$total_stok_volume_semen = $stok_volume_semen_lalu + $pembelian_volume_semen;
			$total_stok_nilai_semen = $stok_nilai_semen_lalu + $pembelian_nilai_semen;

			$stock_opname_semen_now = $this->db->select('pp.vol_nilai_semen as volume, pp.nilai_semen as nilai, pp.vol_pemakaian_semen as volume_pemakaian, pp.vol_pemakaian_semen2 as volume_pemakaian2, pp.nilai_pemakaian_semen as nilai_pemakaian, pp.nilai_pemakaian_semen2 as nilai_pemakaian2')
			->from('kunci_bahan_baku pp')
			->where("(pp.date <= '$date2')")
			->order_by('pp.date','desc')->limit(1)
			->get()->row_array();
			$volume_stock_opname_semen_now = $stock_opname_semen_now['volume'];
			$nilai_stock_opname_semen_now = $stock_opname_semen_now['nilai'];
			$vol_pemakaian_semen_now = $stock_opname_semen_now['volume_pemakaian'];
			$nilai_pemakaian_semen_now = $stock_opname_semen_now['nilai_pemakaian'];
			$vol_pemakaian_semen_now2 = $stock_opname_semen_now['volume_pemakaian2'];
			$nilai_pemakaian_semen_now2 = $stock_opname_semen_now['nilai_pemakaian2'];

			$pemakaian_volume_semen = $vol_pemakaian_semen_now;
			$pemakaian_harsat_semen = $nilai_pemakaian_semen_now / $vol_pemakaian_semen_now;
			$pemakaian_nilai_semen = $nilai_pemakaian_semen_now;

			$pemakaian_volume_semen2 = $vol_pemakaian_semen_now2;
			$pemakaian_harsat_semen2 = $nilai_pemakaian_semen_now2 / $vol_pemakaian_semen_now2;
			$pemakaian_nilai_semen2 = $nilai_pemakaian_semen_now2;

			$total_pemakaian_volume_semen = $pemakaian_volume_semen + $pemakaian_volume_semen2;
			$total_pemakaian_nilai_semen = $pemakaian_nilai_semen + $pemakaian_nilai_semen2;

			$stok_akhir_volume_semen = $volume_stock_opname_semen_now;
			$stok_akhir_nilai_semen = $nilai_stock_opname_semen_now;
			?>

			<?php
			$date1_ago = date('2020-01-01');
			$date2_ago = date('Y-m-d', strtotime('-1 days', strtotime($date1)));
			$date3_ago = date('Y-m-d', strtotime('-1 months', strtotime($date1)));
			$tanggal_opening_balance = date('Y-m-d', strtotime('-1 days', strtotime($date1)));

			$stock_opname_pasir_ago = $this->db->select('pp.vol_nilai_pasir as volume')
			->from('kunci_bahan_baku pp')
			->where("(pp.date = '$tanggal_opening_balance')")
			->order_by('pp.date','desc')->limit(1)
			->get()->row_array();
			
			$harga_pasir = $this->db->select('pp.nilai_pasir as nilai_pasir')
			->from('kunci_bahan_baku pp')
			->where("(pp.date between '$date3_ago' and '$date2_ago')")
			->order_by('pp.date','desc')->limit(1)
			->get()->row_array();

			$pembelian_pasir = $this->db->select('prm.display_measure as satuan, SUM(prm.display_volume) as volume, (prm.display_price / prm.display_volume) as harga, SUM(prm.display_price) as nilai')
			->from('pmm_receipt_material prm')
			->join('pmm_purchase_order po', 'prm.purchase_order_id = po.id','left')
			->join('produk p', 'prm.material_id = p.id','left')
			->where("prm.date_receipt between '$date1' and '$date2'")
			->where("prm.material_id = 2")
			->group_by('prm.material_id')
			->get()->row_array();

			$stok_volume_pasir_lalu = $stock_opname_pasir_ago['volume'];
			$stok_nilai_pasir_lalu = $harga_pasir['nilai_pasir'];
			$stok_harsat_pasir_lalu = (round($stok_volume_pasir_lalu,2)!=0)?$stok_nilai_pasir_lalu / round($stok_volume_pasir_lalu,2) * 1:0;
		
			$pembelian_volume_pasir = $pembelian_pasir['volume'];
			$pembelian_nilai_pasir = $pembelian_pasir['nilai'];
			$pembelian_harga_pasir = (round($pembelian_volume_pasir,2)!=0)?$pembelian_nilai_pasir / round($pembelian_volume_pasir,2) * 1:0;

			$total_stok_volume_pasir = $stok_volume_pasir_lalu + $pembelian_volume_pasir;
			$total_stok_nilai_pasir = $stok_nilai_pasir_lalu + $pembelian_nilai_pasir;

			$stock_opname_pasir_now = $this->db->select('pp.vol_nilai_pasir as volume, pp.nilai_pasir as nilai, pp.vol_pemakaian_pasir as volume_pemakaian, pp.vol_pemakaian_pasir2 as volume_pemakaian2, pp.nilai_pemakaian_pasir as nilai_pemakaian, pp.nilai_pemakaian_pasir2 as nilai_pemakaian2')
			->from('kunci_bahan_baku pp')
			->where("(pp.date <= '$date2')")
			->order_by('pp.date','desc')->limit(1)
			->get()->row_array();
			$volume_stock_opname_pasir_now = $stock_opname_pasir_now['volume'];
			$nilai_stock_opname_pasir_now = $stock_opname_pasir_now['nilai'];
			$vol_pemakaian_pasir_now = $stock_opname_pasir_now['volume_pemakaian'];
			$nilai_pemakaian_pasir_now = $stock_opname_pasir_now['nilai_pemakaian'];
			$vol_pemakaian_pasir_now2 = $stock_opname_pasir_now['volume_pemakaian2'];
			$nilai_pemakaian_pasir_now2 = $stock_opname_pasir_now['nilai_pemakaian2'];

			$pemakaian_volume_pasir = $vol_pemakaian_pasir_now;
			$pemakaian_harsat_pasir = $nilai_pemakaian_pasir_now / $vol_pemakaian_pasir_now;
			$pemakaian_nilai_pasir = $nilai_pemakaian_pasir_now;

			$pemakaian_volume_pasir2 = $vol_pemakaian_pasir_now2;
			$pemakaian_harsat_pasir2 = $nilai_pemakaian_pasir_now2 / $vol_pemakaian_pasir_now2;
			$pemakaian_nilai_pasir2 = $nilai_pemakaian_pasir_now2;

			$total_pemakaian_volume_pasir = $pemakaian_volume_pasir + $pemakaian_volume_pasir2;
			$total_pemakaian_nilai_pasir = $pemakaian_nilai_pasir + $pemakaian_nilai_pasir2;

			$stok_akhir_volume_pasir = $volume_stock_opname_pasir_now;
			$stok_akhir_nilai_pasir = $nilai_stock_opname_pasir_now;
			?>

			<?php
			$date1_ago = date('2020-01-01');
			$date2_ago = date('Y-m-d', strtotime('-1 days', strtotime($date1)));
			$date3_ago = date('Y-m-d', strtotime('-1 months', strtotime($date1)));
			$tanggal_opening_balance = date('Y-m-d', strtotime('-1 days', strtotime($date1)));

			$stock_opname_1020_ago = $this->db->select('pp.vol_nilai_1020 as volume')
			->from('kunci_bahan_baku pp')
			->where("(pp.date = '$tanggal_opening_balance')")
			->order_by('pp.date','desc')->limit(1)
			->get()->row_array();
			
			$harga_1020 = $this->db->select('pp.nilai_1020 as nilai_1020')
			->from('kunci_bahan_baku pp')
			->where("(pp.date between '$date3_ago' and '$date2_ago')")
			->order_by('pp.date','desc')->limit(1)
			->get()->row_array();

			$pembelian_1020 = $this->db->select('prm.display_measure as satuan, SUM(prm.display_volume) as volume, (prm.display_price / prm.display_volume) as harga, SUM(prm.display_price) as nilai')
			->from('pmm_receipt_material prm')
			->join('pmm_purchase_order po', 'prm.purchase_order_id = po.id','left')
			->join('produk p', 'prm.material_id = p.id','left')
			->where("prm.date_receipt between '$date1' and '$date2'")
			->where("prm.material_id = 3")
			->group_by('prm.material_id')
			->get()->row_array();

			$stok_volume_1020_lalu = $stock_opname_1020_ago['volume'];
			$stok_nilai_1020_lalu = $harga_1020['nilai_1020'];
			$stok_harsat_1020_lalu = (round($stok_volume_1020_lalu,2)!=0)?$stok_nilai_1020_lalu / round($stok_volume_1020_lalu,2) * 1:0;
		
			$pembelian_volume_1020 = $pembelian_1020['volume'];
			$pembelian_nilai_1020 = $pembelian_1020['nilai'];
			$pembelian_harga_1020 = (round($pembelian_volume_1020,2)!=0)?$pembelian_nilai_1020 / round($pembelian_volume_1020,2) * 1:0;

			$total_stok_volume_1020 = $stok_volume_1020_lalu + $pembelian_volume_1020;
			$total_stok_nilai_1020 = $stok_nilai_1020_lalu + $pembelian_nilai_1020;

			$stock_opname_1020_now = $this->db->select('pp.vol_nilai_1020 as volume, pp.nilai_1020 as nilai, pp.vol_pemakaian_1020 as volume_pemakaian, pp.vol_pemakaian_10202 as volume_pemakaian2, pp.nilai_pemakaian_1020 as nilai_pemakaian, pp.nilai_pemakaian_10202 as nilai_pemakaian2')
			->from('kunci_bahan_baku pp')
			->where("(pp.date <= '$date2')")
			->order_by('pp.date','desc')->limit(1)
			->get()->row_array();
			$volume_stock_opname_1020_now = $stock_opname_1020_now['volume'];
			$nilai_stock_opname_1020_now = $stock_opname_1020_now['nilai'];
			$vol_pemakaian_1020_now = $stock_opname_1020_now['volume_pemakaian'];
			$nilai_pemakaian_1020_now = $stock_opname_1020_now['nilai_pemakaian'];
			$vol_pemakaian_1020_now2 = $stock_opname_1020_now['volume_pemakaian2'];
			$nilai_pemakaian_1020_now2 = $stock_opname_1020_now['nilai_pemakaian2'];

			$pemakaian_volume_1020 = $vol_pemakaian_1020_now;
			$pemakaian_harsat_1020 = $nilai_pemakaian_1020_now / $vol_pemakaian_1020_now;
			$pemakaian_nilai_1020 = $nilai_pemakaian_1020_now;

			$pemakaian_volume_10202 = $vol_pemakaian_1020_now2;
			$pemakaian_harsat_10202 = $nilai_pemakaian_1020_now2 / $vol_pemakaian_1020_now2;
			$pemakaian_nilai_10202 = $nilai_pemakaian_1020_now2;

			$total_pemakaian_volume_1020 = $pemakaian_volume_1020 + $pemakaian_volume_10202;
			$total_pemakaian_nilai_1020 = $pemakaian_nilai_1020 + $pemakaian_nilai_10202;

			$stok_akhir_volume_1020 = $volume_stock_opname_1020_now;
			$stok_akhir_nilai_1020 = $nilai_stock_opname_1020_now;
			?>

			<?php
			$date1_ago = date('2020-01-01');
			$date2_ago = date('Y-m-d', strtotime('-1 days', strtotime($date1)));
			$date3_ago = date('Y-m-d', strtotime('-1 months', strtotime($date1)));
			$tanggal_opening_balance = date('Y-m-d', strtotime('-1 days', strtotime($date1)));

			$stock_opname_2030_ago = $this->db->select('pp.vol_nilai_2030 as volume')
			->from('kunci_bahan_baku pp')
			->where("(pp.date = '$tanggal_opening_balance')")
			->order_by('pp.date','desc')->limit(1)
			->get()->row_array();
			
			$harga_2030 = $this->db->select('pp.nilai_2030 as nilai_2030')
			->from('kunci_bahan_baku pp')
			->where("(pp.date between '$date3_ago' and '$date2_ago')")
			->order_by('pp.date','desc')->limit(1)
			->get()->row_array();

			$pembelian_2030 = $this->db->select('prm.display_measure as satuan, SUM(prm.display_volume) as volume, (prm.display_price / prm.display_volume) as harga, SUM(prm.display_price) as nilai')
			->from('pmm_receipt_material prm')
			->join('pmm_purchase_order po', 'prm.purchase_order_id = po.id','left')
			->join('produk p', 'prm.material_id = p.id','left')
			->where("prm.date_receipt between '$date1' and '$date2'")
			->where("prm.material_id = 4")
			->group_by('prm.material_id')
			->get()->row_array();

			$stok_volume_2030_lalu = $stock_opname_2030_ago['volume'];
			$stok_nilai_2030_lalu = $harga_2030['nilai_2030'];
			$stok_harsat_2030_lalu = (round($stok_volume_2030_lalu,2)!=0)?$stok_nilai_2030_lalu / round($stok_volume_2030_lalu,2) * 1:0;
		
			$pembelian_volume_2030 = $pembelian_2030['volume'];
			$pembelian_nilai_2030 = $pembelian_2030['nilai'];
			$pembelian_harga_2030 = (round($pembelian_volume_2030,2)!=0)?$pembelian_nilai_2030 / round($pembelian_volume_2030,2) * 1:0;

			$total_stok_volume_2030 = $stok_volume_2030_lalu + $pembelian_volume_2030;
			$total_stok_nilai_2030 = $stok_nilai_2030_lalu + $pembelian_nilai_2030;

			$stock_opname_2030_now = $this->db->select('pp.vol_nilai_2030 as volume, pp.nilai_2030 as nilai, pp.vol_pemakaian_2030 as volume_pemakaian, pp.vol_pemakaian_20302 as volume_pemakaian2, pp.nilai_pemakaian_2030 as nilai_pemakaian, pp.nilai_pemakaian_20302 as nilai_pemakaian2')
			->from('kunci_bahan_baku pp')
			->where("(pp.date <= '$date2')")
			->order_by('pp.date','desc')->limit(1)
			->get()->row_array();
			$volume_stock_opname_2030_now = $stock_opname_2030_now['volume'];
			$nilai_stock_opname_2030_now = $stock_opname_2030_now['nilai'];
			$vol_pemakaian_2030_now = $stock_opname_2030_now['volume_pemakaian'];
			$nilai_pemakaian_2030_now = $stock_opname_2030_now['nilai_pemakaian'];
			$vol_pemakaian_2030_now2 = $stock_opname_2030_now['volume_pemakaian2'];
			$nilai_pemakaian_2030_now2 = $stock_opname_2030_now['nilai_pemakaian2'];

			$pemakaian_volume_2030 = $vol_pemakaian_2030_now;
			$pemakaian_harsat_2030 = $nilai_pemakaian_2030_now / $vol_pemakaian_2030_now;
			$pemakaian_nilai_2030 = $nilai_pemakaian_2030_now;

			$pemakaian_volume_20302 = $vol_pemakaian_2030_now2;
			$pemakaian_harsat_20302 = $nilai_pemakaian_2030_now2 / $vol_pemakaian_2030_now2;
			$pemakaian_nilai_20302 = $nilai_pemakaian_2030_now2;

			$total_pemakaian_volume_2030 = $pemakaian_volume_2030 + $pemakaian_volume_20302;
			$total_pemakaian_nilai_2030 = $pemakaian_nilai_2030 + $pemakaian_nilai_20302;

			$stok_akhir_volume_2030 = $volume_stock_opname_2030_now;
			$stok_akhir_nilai_2030 = $nilai_stock_opname_2030_now;
			?>

			<?php
			//TOTAL
			$total_volume_realisasi = $total_volume_pemakaian_semen + $total_volume_pemakaian_pasir + $total_volume_pemakaian_batu1020 + $total_volume_pemakaian_batu2030;
			$total_nilai_realisasi = $total_pemakaian_nilai_semen + $total_pemakaian_nilai_pasir + $total_pemakaian_nilai_1020 + $total_pemakaian_nilai_2030;

			$evaluasi_volume_a = round($volume_a - $total_pemakaian_volume_semen,2);
			$evaluasi_volume_b = round($volume_b - $total_pemakaian_volume_pasir,2);
			$evaluasi_volume_c = round($volume_c - $total_pemakaian_volume_1020,2);
			$evaluasi_volume_d = round($volume_d - $total_pemakaian_volume_2030,2);

			$evaluasi_nilai_a = round($nilai_a - $total_pemakaian_nilai_semen,0);
			$evaluasi_nilai_b = round($nilai_b - $total_pemakaian_nilai_pasir,0);
			$evaluasi_nilai_c = round($nilai_c - $total_pemakaian_nilai_1020,0);
			$evaluasi_nilai_d = round($nilai_d - $total_pemakaian_nilai_2030,0);

			$total_nilai_evaluasi = round($evaluasi_nilai_a + $evaluasi_nilai_b + $evaluasi_nilai_c + $evaluasi_nilai_d,0);
	        ?>
			
			<tr class="table-judul">
				<th width="5%" align="center" rowspan="2">&nbsp;<br/>NO.</th>
				<th width="12%" align="center" rowspan="2">&nbsp;<br/>URAIAN</th>
				<th width="7%" align="center" rowspan="2">&nbsp;<br/>SATUAN</th>
				<th width="29%" align="center" colspan="3">KOMPOSISI</th>
				<th width="29%" align="center" colspan="3">REALISASI</th>
				<th width="19%" align="center" colspan="2">EVALUASI</th>
	        </tr>
			<tr class="table-judul">
				<th width="8%" align="center">VOLUME</th>
				<th width="9%" align="center">HARSAT</th>
				<th width="12%" align="center">NILAI</th>
				<th width="8%" align="center">VOLUME</th>
				<th width="9%" align="center">HARSAT</th>
				<th width="12%" align="center">NILAI</th>
				<th width="8%" align="center">VOLUME</th>
				<th width="11%" align="center">NILAI</th>
	        </tr>
			<?php
				$styleColorA = $evaluasi_volume_a < 0 ? 'color:red' : 'color:black';
				$styleColorB = $evaluasi_volume_b < 0 ? 'color:red' : 'color:black';
				$styleColorC = $evaluasi_volume_c < 0 ? 'color:red' : 'color:black';
				$styleColorD = $evaluasi_volume_d < 0 ? 'color:red' : 'color:black';

				$styleColorAA = $evaluasi_nilai_a < 0 ? 'color:red' : 'color:black';
				$styleColorBB = $evaluasi_nilai_b < 0 ? 'color:red' : 'color:black';
				$styleColorCC = $evaluasi_nilai_c < 0 ? 'color:red' : 'color:black';
				$styleColorDD = $evaluasi_nilai_d < 0 ? 'color:red' : 'color:black';
				$styleColorEE = $total_nilai_evaluasi < 0 ? 'color:red' : 'color:black';
			?>
			<tr class="table-baris1">
				<th align="center" style="vertical-align:middle">1.</th>			
				<th align="left">Semen</th>
				<th align="center">Ton</th>
				<th align="right"><?php echo number_format($volume_a,2,',','.');?></th>
				<th align="right"><?php echo number_format($price_a,0,',','.');?></th>
				<th align="right"><?php echo number_format($nilai_a,0,',','.');?></th>
				<th align="right"><?php echo number_format($total_pemakaian_volume_semen,2,',','.');?></th>
				<th align="right"><?php echo number_format($total_pemakaian_nilai_semen / $total_pemakaian_volume_semen,0,',','.');?></th>
				<th align="right"><?php echo number_format($total_pemakaian_nilai_semen,0,',','.');?></th>
				<th align="right" style="<?php echo $styleColorA ?>"><?php echo number_format($evaluasi_volume_a,2,',','.');?></th>
				<th align="right" style="<?php echo $styleColorAA ?>"><?php echo number_format($evaluasi_nilai_a,0,',','.');?></th>
	        </tr>
			<tr class="table-baris1">
				<th align="center" style="vertical-align:middle">2.</th>			
				<th align="left">Pasir</th>
				<th align="center">M3</th>
				<th align="right"><?php echo number_format($volume_b,2,',','.');?></th>
				<th align="right"><?php echo number_format($price_b,2,',','.');?></th>
				<th align="right"><?php echo number_format($nilai_b,0,',','.');?></th>
				<th align="right"><?php echo number_format($total_pemakaian_volume_pasir,2,',','.');?></th>
				<th align="right"><?php echo number_format($total_pemakaian_nilai_pasir / $total_pemakaian_volume_pasir,0,',','.');?></th>
				<th align="right"><?php echo number_format($total_pemakaian_nilai_pasir,0,',','.');?></th>
				<th align="right" style="<?php echo $styleColorB ?>"><?php echo number_format($evaluasi_volume_b,2,',','.');?></th>
				<th align="right" style="<?php echo $styleColorBB ?>"><?php echo number_format($evaluasi_nilai_b,0,',','.');?></th>
	        </tr>
			<tr class="table-baris1">
				<th align="center" style="vertical-align:middle">3.</th>			
				<th align="left">Batu Split 10-20</th>
				<th align="center">M3</th>
				<th align="right"><?php echo number_format($volume_c,2,',','.');?></th>
				<th align="right"><?php echo number_format($price_c,0,',','.');?></th>
				<th align="right"><?php echo number_format($nilai_c,0,',','.');?></th>
				<th align="right"><?php echo number_format($total_pemakaian_volume_1020,2,',','.');?></th>
				<th align="right"><?php echo number_format($total_pemakaian_nilai_1020 / $total_pemakaian_volume_1020,0,',','.');?></th>
				<th align="right"><?php echo number_format($total_pemakaian_nilai_1020,0,',','.');?></th>
				<th align="right" style="<?php echo $styleColorC ?>"><?php echo number_format($evaluasi_volume_c,2,',','.');?></th>
				<th align="right" style="<?php echo $styleColorCC ?>"><?php echo number_format($evaluasi_nilai_c,0,',','.');?></th>
	        </tr>
			<tr class="table-baris1">
				<th align="center" style="vertical-align:middle">4.</th>			
				<th align="left">Batu Split 20-30</th>
				<th align="center">M3</th>
				<th align="right"><?php echo number_format($volume_d,2,',','.');?></th>
				<th align="right"><?php echo number_format($price_d,2,',','.');?></th>
				<th align="right"><?php echo number_format($nilai_d,0,',','.');?></th>
				<th align="right"><?php echo number_format($total_pemakaian_volume_2030,2,',','.');?></th>
				<th align="right"><?php echo number_format($total_pemakaian_nilai_2030 / $total_pemakaian_volume_2030,0,',','.');?></th>
				<th align="right"><?php echo number_format($total_pemakaian_nilai_2030,0,',','.');?></th>
				<th align="right" style="<?php echo $styleColorD ?>"><?php echo number_format($evaluasi_volume_d,2,',','.');?></th>
				<th align="right" style="<?php echo $styleColorDD ?>"><?php echo number_format($evaluasi_nilai_d,0,',','.');?></th>
	        </tr>
			<tr class="table-total">		
				<th align="right" colspan="3">TOTAL</th>
				<th align="right"></th>
				<th align="right"></th>
				<th align="right"><?php echo number_format($total_nilai_komposisi,0,',','.');?></th>
				<th align="right"></th>
				<th align="right"></th>
				<th align="right"><?php echo number_format($total_nilai_realisasi,0,',','.');?></th>
				<th align="right"></th>
				<th align="right" class="table-border-spesial-kanan" style="<?php echo $styleColorEE ?>"><?php echo number_format($total_nilai_evaluasi,0,',','.');?></th>
	        </tr>
	    </table>
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
							<?php
								$create = $this->db->select('*')
								->from('kunci_bahan_baku')
								->where("(date = '$end_date')")
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
								<img src="<?= $unit_head['admin_ttd']?>" width="90px">
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