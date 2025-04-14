<!DOCTYPE html>
<html>
	<head>
	  <title>LAPORAN RENCANA KERJA PRODUKSI</title>
	  
	  <style type="text/css">
		body {
			font-family: helvetica;
			font-size: 5px;
		}

		table.table-border-pojok-kiri, th.table-border-pojok-kiri, td.table-border-pojok-kiri {
			border-top: 1px solid black;
			border-bottom: 1px solid black;
			border-right: 1px solid #cccccc;
			border-left: 1px solid black;
		}

		table.table-border-pojok-tengah, th.table-border-pojok-tengah, td.table-border-pojok-tengah {
			border-top: 1px solid black;
			border-bottom: 1px solid black;
			border-right: 1px solid #cccccc;
		}

		table.table-border-pojok-kanan, th.table-border-pojok-kanan, td.table-border-pojok-kanan {
			border-top: 1px solid black;
			border-bottom: 1px solid black;
			border-right: 1px solid black;
		}

		table.table-border-spesial, th.table-border-spesial, td.table-border-spesial {
			border-left: 1px solid black;
			border-right: 1px solid black;
		}

		table.table-border-spesial-kiri, th.table-border-spesial-kiri, td.table-border-spesial-kiri {
			border-left: 1px solid black;
			border-top: 2px solid black;
			border-bottom: 2px solid black;
		}

		table.table-border-spesial-tengah, th.table-border-spesial-tengah, td.table-border-spesial-tengah {
			border-left: 1px solid #cccccc;
			border-right: 1px solid #cccccc;
			border-top: 2px solid black;
			border-bottom: 2px solid black;
		}

		table.table-border-spesial-kanan, th.table-border-spesial-kanan, td.table-border-spesial-kanan {
			border-left: 1px solid #cccccc;
			border-right: 1px solid black;
			border-top: 2px solid black;
			border-bottom: 2px solid black;
		}

		table tr.table-judul{
			border: 1px solid;
			background-color: #e69500;
			font-weight: bold;
			color: black;
		}
			
		table tr.table-baris1{
			background-color: none;
		}

		table tr.table-baris1-bold{
			background-color: none;
			font-weight: bold;
		}
			
		table tr.table-total{
			background-color: #FFFF00;
			font-weight: bold;
			color: black;
		}

		table tr.table-total2{
			background-color: #eeeeee;
			font-weight: bold;
			color: black;
		}
	  </style>

	</head>
	<body>
		<div align="center" style="display: block;font-weight: bold;font-size: 8px;">Laporan Rencana Kerja</div>
		<div align="center" style="display: block;font-weight: bold;font-size: 8px;">Proyek Bendungan Tiga Dihaji</div>
		<div align="center" style="display: block;font-weight: bold;font-size: 8px;">PT. Bia Bumi Jayendra</div>
		<br /><br />
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
			/*$date_juni24_awal = date('2024-06-01');
			$date_juni24_akhir = date('2024-06-30');
			$date_juli24_awal = date('2024-07-01');
			$date_juli24_akhir = date('2024-07-31');
			$date_agustus24_awal = date('2024-08-01');
			$date_agustus24_akhir = date('2024-08-31');
			$date_september24_awal = date('2024-09-01');
			$date_september24_akhir = date('2024-09-30');
			$date_oktober24_awal = date('2024-10-01');
			$date_oktober24_akhir = date('2024-10-31');
			$date_november24_awal = date('2024-11-01');
			$date_november24_akhir = date('2024-11-30');
			$date_desember24_awal = date('2024-12-01');
			$date_desember24_akhir = date('2024-12-31');*/

			$date_juni24_awal = date('2025-01-01');
			$date_juni24_akhir = date('2025-01-30');
			$date_juli24_awal = date('2025-02-01');
			$date_juli24_akhir = date('2025-02-28');
			$date_agustus24_awal = date('2025-03-01');
			$date_agustus24_akhir = date('2025-03-31');
			$date_september24_awal = date('2025-04-01');
			$date_september24_akhir = date('2025-04-30');
			$date_oktober24_awal = date('2025-05-01');
			$date_oktober24_akhir = date('2025-05-31');
			$date_november24_awal = date('2025-06-01');
			$date_november24_akhir = date('2025-06-30');
			$date_desember24_awal = date('2025-07-01');
			$date_desember24_akhir = date('2025-07-31');

			//BETON K-125 SLUMP 10
			$rak_1_K125 = $this->db->select('*, SUM(vol_produk_a) as vol_produk_a')
			->from('rak')
			->where("tanggal_rencana_kerja between '$date_juni24_awal' and '$date_juni24_akhir'")
			->get()->row_array();
			$rak_1_vol_K125 = $rak_1_K125['vol_produk_a'];
			$rak_1_nilai_K125 = $rak_1_vol_K125 * $rak_1_K125['price_a'];

			$rak_2_K125 = $this->db->select('*, SUM(vol_produk_a) as vol_produk_a')
			->from('rak')
			->where("tanggal_rencana_kerja between '$date_juli24_awal' and '$date_juli24_akhir'")
			->get()->row_array();
			$rak_2_vol_K125 = $rak_2_K125['vol_produk_a'];
			$rak_2_nilai_K125 = $rak_2_vol_K125 * $rak_2_K125['price_a'];

			$rak_3_K125 = $this->db->select('*, SUM(vol_produk_a) as vol_produk_a')
			->from('rak')
			->where("tanggal_rencana_kerja between '$date_agustus24_awal' and '$date_agustus24_akhir'")
			->get()->row_array();
			$rak_3_vol_K125 = $rak_3_K125['vol_produk_a'];
			$rak_3_nilai_K125 = $rak_3_vol_K125 * $rak_3_K125['price_a'];

			$rak_4_K125 = $this->db->select('*, SUM(vol_produk_a) as vol_produk_a')
			->from('rak')
			->where("tanggal_rencana_kerja between '$date_september24_awal' and '$date_september24_akhir'")
			->get()->row_array();
			$rak_4_vol_K125 = $rak_4_K125['vol_produk_a'];
			$rak_4_nilai_K125 = $rak_4_vol_K125 * $rak_4_K125['price_a'];

			$rak_5_K125 = $this->db->select('*, SUM(vol_produk_a) as vol_produk_a')
			->from('rak')
			->where("tanggal_rencana_kerja between '$date_oktober24_awal' and '$date_oktober24_akhir'")
			->get()->row_array();
			$rak_5_vol_K125 = $rak_5_K125['vol_produk_a'];
			$rak_5_nilai_K125 = $rak_5_vol_K125 * $rak_5_K125['price_a'];

			$rak_6_K125 = $this->db->select('*, SUM(vol_produk_a) as vol_produk_a')
			->from('rak')
			->where("tanggal_rencana_kerja between '$date_november24_awal' and '$date_november24_akhir'")
			->get()->row_array();
			$rak_6_vol_K125 = $rak_6_K125['vol_produk_a'];
			$rak_6_nilai_K125 = $rak_6_vol_K125 * $rak_6_K125['price_a'];

			$rak_7_K125 = $this->db->select('*, SUM(vol_produk_a) as vol_produk_a')
			->from('rak')
			->where("tanggal_rencana_kerja between '$date_desember24_awal' and '$date_desember24_akhir'")
			->get()->row_array();
			$rak_7_vol_K125 = $rak_7_K125['vol_produk_a'];
			$rak_7_nilai_K125 = $rak_7_vol_K125 * $rak_7_K125['price_a'];

			$jumlah_vol_K125 = $rak_1_vol_K125 + $rak_2_vol_K125 + $rak_3_vol_K125 + $rak_4_vol_K125 + $rak_5_vol_K125 + $rak_6_vol_K125 + $rak_7_vol_K125;
			$jumlah_nilai_K125 = $rak_1_nilai_K125 + $rak_2_nilai_K125 + $rak_3_nilai_K125 + $rak_4_nilai_K125 + $rak_5_nilai_K125 + $rak_6_nilai_K125 + $rak_7_nilai_K125;
			
			//KOMPOSISI BAHAN K-125 SLUMP 10
			$komposisi_125_1 = $this->db->select('(r.vol_produk_a * pk.presentase_a) as vol_komposisi_semen, (r.vol_produk_a * pk.presentase_a) * pk.price_a as nilai_komposisi_semen, (r.vol_produk_a * pk.presentase_b) as vol_komposisi_pasir, (r.vol_produk_a * pk.presentase_b) * pk.price_b as nilai_komposisi_pasir, (r.vol_produk_a * pk.presentase_c) as vol_komposisi_batu1020, (r.vol_produk_a * pk.presentase_c) * pk.price_c as nilai_komposisi_batu1020, (r.vol_produk_a * pk.presentase_d) as vol_komposisi_batu2030, (r.vol_produk_a * pk.presentase_d) * pk.price_d as nilai_komposisi_batu2030, (r.vol_produk_a * pk.presentase_e) as vol_komposisi_additive, (r.vol_produk_a * pk.presentase_e) * pk.price_e as nilai_komposisi_additive')
			->from('rak r')
			->join('pmm_agregat pk', 'r.komposisi_125 = pk.id','left')
			->where("r.tanggal_rencana_kerja between '$date_juni24_awal' and '$date_juni24_akhir'")
			->get()->result_array();

			$total_volume_semen_125_1 = 0;
			$total_nilai_semen_125_1 = 0;

			foreach ($komposisi_125_1 as $x){
				$total_volume_semen_125_1 = $x['vol_komposisi_semen'];
				$total_nilai_semen_125_1 = $x['nilai_komposisi_semen'];
				$total_volume_pasir_125_1 = $x['vol_komposisi_pasir'];
				$total_nilai_pasir_125_1 = $x['nilai_komposisi_pasir'];
				$total_volume_batu1020_125_1 = $x['vol_komposisi_batu1020'];
				$total_nilai_batu1020_125_1 = $x['nilai_komposisi_batu1020'];
				$total_volume_batu2030_125_1 = $x['vol_komposisi_batu2030'];
				$total_nilai_batu2030_125_1 = $x['nilai_komposisi_batu2030'];
				$total_volume_additive_125_1 = $x['vol_komposisi_additive'];
				$total_nilai_additive_125_1 = $x['nilai_komposisi_additive'];
			}

			$komposisi_125_2 = $this->db->select('(r.vol_produk_a * pk.presentase_a) as vol_komposisi_semen, (r.vol_produk_a * pk.presentase_a) * pk.price_a as nilai_komposisi_semen, (r.vol_produk_a * pk.presentase_b) as vol_komposisi_pasir, (r.vol_produk_a * pk.presentase_b) * pk.price_b as nilai_komposisi_pasir, (r.vol_produk_a * pk.presentase_c) as vol_komposisi_batu1020, (r.vol_produk_a * pk.presentase_c) * pk.price_c as nilai_komposisi_batu1020, (r.vol_produk_a * pk.presentase_d) as vol_komposisi_batu2030, (r.vol_produk_a * pk.presentase_d) * pk.price_d as nilai_komposisi_batu2030, (r.vol_produk_a * pk.presentase_e) as vol_komposisi_additive, (r.vol_produk_a * pk.presentase_e) * pk.price_e as nilai_komposisi_additive')
			->from('rak r')
			->join('pmm_agregat pk', 'r.komposisi_125 = pk.id','left')
			->where("r.tanggal_rencana_kerja between '$date_juli24_awal' and '$date_juli24_akhir'")
			->get()->result_array();

			$total_volume_semen_125_2 = 0;
			$total_nilai_semen_125_2 = 0;

			foreach ($komposisi_125_2 as $x){
				$total_volume_semen_125_2 = $x['vol_komposisi_semen'];
				$total_nilai_semen_125_2 = $x['nilai_komposisi_semen'];
				$total_volume_pasir_125_2 = $x['vol_komposisi_pasir'];
				$total_nilai_pasir_125_2 = $x['nilai_komposisi_pasir'];
				$total_volume_batu1020_125_2 = $x['vol_komposisi_batu1020'];
				$total_nilai_batu1020_125_2 = $x['nilai_komposisi_batu1020'];
				$total_volume_batu2030_125_2 = $x['vol_komposisi_batu2030'];
				$total_nilai_batu2030_125_2 = $x['nilai_komposisi_batu2030'];
				$total_volume_additive_125_2 = $x['vol_komposisi_additive'];
				$total_nilai_additive_125_2 = $x['nilai_komposisi_additive'];
			}

			$komposisi_125_3 = $this->db->select('(r.vol_produk_a * pk.presentase_a) as vol_komposisi_semen, (r.vol_produk_a * pk.presentase_a) * pk.price_a as nilai_komposisi_semen, (r.vol_produk_a * pk.presentase_b) as vol_komposisi_pasir, (r.vol_produk_a * pk.presentase_b) * pk.price_b as nilai_komposisi_pasir, (r.vol_produk_a * pk.presentase_c) as vol_komposisi_batu1020, (r.vol_produk_a * pk.presentase_c) * pk.price_c as nilai_komposisi_batu1020, (r.vol_produk_a * pk.presentase_d) as vol_komposisi_batu2030, (r.vol_produk_a * pk.presentase_d) * pk.price_d as nilai_komposisi_batu2030, (r.vol_produk_a * pk.presentase_e) as vol_komposisi_additive, (r.vol_produk_a * pk.presentase_e) * pk.price_e as nilai_komposisi_additive')
			->from('rak r')
			->join('pmm_agregat pk', 'r.komposisi_125 = pk.id','left')
			->where("r.tanggal_rencana_kerja between '$date_agustus24_awal' and '$date_agustus24_akhir'")
			->get()->result_array();

			$total_volume_semen_125_3 = 0;
			$total_nilai_semen_125_3 = 0;

			foreach ($komposisi_125_3 as $x){
				$total_volume_semen_125_3 = $x['vol_komposisi_semen'];
				$total_nilai_semen_125_3 = $x['nilai_komposisi_semen'];
				$total_volume_pasir_125_3 = $x['vol_komposisi_pasir'];
				$total_nilai_pasir_125_3 = $x['nilai_komposisi_pasir'];
				$total_volume_batu1020_125_3 = $x['vol_komposisi_batu1020'];
				$total_nilai_batu1020_125_3 = $x['nilai_komposisi_batu1020'];
				$total_volume_batu2030_125_3 = $x['vol_komposisi_batu2030'];
				$total_nilai_batu2030_125_3 = $x['nilai_komposisi_batu2030'];
				$total_volume_additive_125_3 = $x['vol_komposisi_additive'];
				$total_nilai_additive_125_3 = $x['nilai_komposisi_additive'];
			}

			$komposisi_125_4 = $this->db->select('(r.vol_produk_a * pk.presentase_a) as vol_komposisi_semen, (r.vol_produk_a * pk.presentase_a) * pk.price_a as nilai_komposisi_semen, (r.vol_produk_a * pk.presentase_b) as vol_komposisi_pasir, (r.vol_produk_a * pk.presentase_b) * pk.price_b as nilai_komposisi_pasir, (r.vol_produk_a * pk.presentase_c) as vol_komposisi_batu1020, (r.vol_produk_a * pk.presentase_c) * pk.price_c as nilai_komposisi_batu1020, (r.vol_produk_a * pk.presentase_d) as vol_komposisi_batu2030, (r.vol_produk_a * pk.presentase_d) * pk.price_d as nilai_komposisi_batu2030, (r.vol_produk_a * pk.presentase_e) as vol_komposisi_additive, (r.vol_produk_a * pk.presentase_e) * pk.price_e as nilai_komposisi_additive')
			->from('rak r')
			->join('pmm_agregat pk', 'r.komposisi_125 = pk.id','left')
			->where("r.tanggal_rencana_kerja between '$date_september24_awal' and '$date_september24_akhir'")
			->get()->result_array();

			$total_volume_semen_125_4 = 0;
			$total_nilai_semen_125_4 = 0;

			foreach ($komposisi_125_4 as $x){
				$total_volume_semen_125_4 = $x['vol_komposisi_semen'];
				$total_nilai_semen_125_4 = $x['nilai_komposisi_semen'];
				$total_volume_pasir_125_4 = $x['vol_komposisi_pasir'];
				$total_nilai_pasir_125_4 = $x['nilai_komposisi_pasir'];
				$total_volume_batu1020_125_4 = $x['vol_komposisi_batu1020'];
				$total_nilai_batu1020_125_4 = $x['nilai_komposisi_batu1020'];
				$total_volume_batu2030_125_4 = $x['vol_komposisi_batu2030'];
				$total_nilai_batu2030_125_4 = $x['nilai_komposisi_batu2030'];
				$total_volume_additive_125_4 = $x['vol_komposisi_additive'];
				$total_nilai_additive_125_4 = $x['nilai_komposisi_additive'];
			}

			$komposisi_125_5 = $this->db->select('(r.vol_produk_a * pk.presentase_a) as vol_komposisi_semen, (r.vol_produk_a * pk.presentase_a) * pk.price_a as nilai_komposisi_semen, (r.vol_produk_a * pk.presentase_b) as vol_komposisi_pasir, (r.vol_produk_a * pk.presentase_b) * pk.price_b as nilai_komposisi_pasir, (r.vol_produk_a * pk.presentase_c) as vol_komposisi_batu1020, (r.vol_produk_a * pk.presentase_c) * pk.price_c as nilai_komposisi_batu1020, (r.vol_produk_a * pk.presentase_d) as vol_komposisi_batu2030, (r.vol_produk_a * pk.presentase_d) * pk.price_d as nilai_komposisi_batu2030, (r.vol_produk_a * pk.presentase_e) as vol_komposisi_additive, (r.vol_produk_a * pk.presentase_e) * pk.price_e as nilai_komposisi_additive')
			->from('rak r')
			->join('pmm_agregat pk', 'r.komposisi_125 = pk.id','left')
			->where("r.tanggal_rencana_kerja between '$date_oktober24_awal' and '$date_oktober24_akhir'")
			->get()->result_array();

			$total_volume_semen_125_5 = 0;
			$total_nilai_semen_125_5 = 0;

			foreach ($komposisi_125_5 as $x){
				$total_volume_semen_125_5 = $x['vol_komposisi_semen'];
				$total_nilai_semen_125_5 = $x['nilai_komposisi_semen'];
				$total_volume_pasir_125_5 = $x['vol_komposisi_pasir'];
				$total_nilai_pasir_125_5 = $x['nilai_komposisi_pasir'];
				$total_volume_batu1020_125_5 = $x['vol_komposisi_batu1020'];
				$total_nilai_batu1020_125_5 = $x['nilai_komposisi_batu1020'];
				$total_volume_batu2030_125_5 = $x['vol_komposisi_batu2030'];
				$total_nilai_batu2030_125_5 = $x['nilai_komposisi_batu2030'];
				$total_volume_additive_125_5 = $x['vol_komposisi_additive'];
				$total_nilai_additive_125_5 = $x['nilai_komposisi_additive'];
			}

			$komposisi_125_6 = $this->db->select('(r.vol_produk_a * pk.presentase_a) as vol_komposisi_semen, (r.vol_produk_a * pk.presentase_a) * pk.price_a as nilai_komposisi_semen, (r.vol_produk_a * pk.presentase_b) as vol_komposisi_pasir, (r.vol_produk_a * pk.presentase_b) * pk.price_b as nilai_komposisi_pasir, (r.vol_produk_a * pk.presentase_c) as vol_komposisi_batu1020, (r.vol_produk_a * pk.presentase_c) * pk.price_c as nilai_komposisi_batu1020, (r.vol_produk_a * pk.presentase_d) as vol_komposisi_batu2030, (r.vol_produk_a * pk.presentase_d) * pk.price_d as nilai_komposisi_batu2030, (r.vol_produk_a * pk.presentase_e) as vol_komposisi_additive, (r.vol_produk_a * pk.presentase_e) * pk.price_e as nilai_komposisi_additive')
			->from('rak r')
			->join('pmm_agregat pk', 'r.komposisi_125 = pk.id','left')
			->where("r.tanggal_rencana_kerja between '$date_november24_awal' and '$date_november24_akhir'")
			->get()->result_array();

			$total_volume_semen_125_6 = 0;
			$total_nilai_semen_125_6 = 0;

			foreach ($komposisi_125_6 as $x){
				$total_volume_semen_125_6 = $x['vol_komposisi_semen'];
				$total_nilai_semen_125_6 = $x['nilai_komposisi_semen'];
				$total_volume_pasir_125_6 = $x['vol_komposisi_pasir'];
				$total_nilai_pasir_125_6 = $x['nilai_komposisi_pasir'];
				$total_volume_batu1020_125_6 = $x['vol_komposisi_batu1020'];
				$total_nilai_batu1020_125_6 = $x['nilai_komposisi_batu1020'];
				$total_volume_batu2030_125_6 = $x['vol_komposisi_batu2030'];
				$total_nilai_batu2030_125_6 = $x['nilai_komposisi_batu2030'];
				$total_volume_additive_125_6 = $x['vol_komposisi_additive'];
				$total_nilai_additive_125_6 = $x['nilai_komposisi_additive'];
			}

			$komposisi_125_7 = $this->db->select('(r.vol_produk_a * pk.presentase_a) as vol_komposisi_semen, (r.vol_produk_a * pk.presentase_a) * pk.price_a as nilai_komposisi_semen, (r.vol_produk_a * pk.presentase_b) as vol_komposisi_pasir, (r.vol_produk_a * pk.presentase_b) * pk.price_b as nilai_komposisi_pasir, (r.vol_produk_a * pk.presentase_c) as vol_komposisi_batu1020, (r.vol_produk_a * pk.presentase_c) * pk.price_c as nilai_komposisi_batu1020, (r.vol_produk_a * pk.presentase_d) as vol_komposisi_batu2030, (r.vol_produk_a * pk.presentase_d) * pk.price_d as nilai_komposisi_batu2030, (r.vol_produk_a * pk.presentase_e) as vol_komposisi_additive, (r.vol_produk_a * pk.presentase_e) * pk.price_e as nilai_komposisi_additive')
			->from('rak r')
			->join('pmm_agregat pk', 'r.komposisi_125 = pk.id','left')
			->where("r.tanggal_rencana_kerja between '$date_desember24_awal' and '$date_desember24_akhir'")
			->get()->result_array();

			$total_volume_semen_125_7 = 0;
			$total_nilai_semen_125_7 = 0;

			foreach ($komposisi_125_7 as $x){
				$total_volume_semen_125_7 = $x['vol_komposisi_semen'];
				$total_nilai_semen_125_7 = $x['nilai_komposisi_semen'];
				$total_volume_pasir_125_7 = $x['vol_komposisi_pasir'];
				$total_nilai_pasir_125_7 = $x['nilai_komposisi_pasir'];
				$total_volume_batu1020_125_7 = $x['vol_komposisi_batu1020'];
				$total_nilai_batu1020_125_7 = $x['nilai_komposisi_batu1020'];
				$total_volume_batu2030_125_7 = $x['vol_komposisi_batu2030'];
				$total_nilai_batu2030_125_7 = $x['nilai_komposisi_batu2030'];
				$total_volume_additive_125_7 = $x['vol_komposisi_additive'];
				$total_nilai_additive_125_7 = $x['nilai_komposisi_additive'];
			}

			//BETON K-175 SLUMP 10
			$rak_1_K175 = $this->db->select('*, SUM(vol_produk_b) as vol_produk_b')
			->from('rak')
			->where("tanggal_rencana_kerja between '$date_juni24_awal' and '$date_juni24_akhir'")
			->get()->row_array();
			$rak_1_vol_K175 = $rak_1_K175['vol_produk_b'];
			$rak_1_nilai_K175 = $rak_1_vol_K175 * $rak_1_K175['price_b'];

			$rak_2_K175 = $this->db->select('*, SUM(vol_produk_b) as vol_produk_b')
			->from('rak')
			->where("tanggal_rencana_kerja between '$date_juli24_awal' and '$date_juli24_akhir'")
			->get()->row_array();
			$rak_2_vol_K175 = $rak_2_K175['vol_produk_b'];
			$rak_2_nilai_K175 = $rak_2_vol_K175 * $rak_2_K175['price_b'];

			$rak_3_K175 = $this->db->select('*, SUM(vol_produk_b) as vol_produk_b')
			->from('rak')
			->where("tanggal_rencana_kerja between '$date_agustus24_awal' and '$date_agustus24_akhir'")
			->get()->row_array();
			$rak_3_vol_K175 = $rak_3_K175['vol_produk_b'];
			$rak_3_nilai_K175 = $rak_3_vol_K175 * $rak_3_K175['price_b'];

			$rak_4_K175 = $this->db->select('*, SUM(vol_produk_b) as vol_produk_b')
			->from('rak')
			->where("tanggal_rencana_kerja between '$date_september24_awal' and '$date_september24_akhir'")
			->get()->row_array();
			$rak_4_vol_K175 = $rak_4_K175['vol_produk_b'];
			$rak_4_nilai_K175 = $rak_4_vol_K175 * $rak_4_K175['price_b'];

			$rak_5_K175 = $this->db->select('*, SUM(vol_produk_b) as vol_produk_b')
			->from('rak')
			->where("tanggal_rencana_kerja between '$date_oktober24_awal' and '$date_oktober24_akhir'")
			->get()->row_array();
			$rak_5_vol_K175 = $rak_5_K175['vol_produk_b'];
			$rak_5_nilai_K175 = $rak_5_vol_K175 * $rak_5_K175['price_b'];

			$rak_6_K175 = $this->db->select('*, SUM(vol_produk_b) as vol_produk_b')
			->from('rak')
			->where("tanggal_rencana_kerja between '$date_november24_awal' and '$date_november24_akhir'")
			->get()->row_array();
			$rak_6_vol_K175 = $rak_6_K175['vol_produk_b'];
			$rak_6_nilai_K175 = $rak_6_vol_K175 * $rak_6_K175['price_b'];

			$rak_7_K175 = $this->db->select('*, SUM(vol_produk_b) as vol_produk_b')
			->from('rak')
			->where("tanggal_rencana_kerja between '$date_desember24_awal' and '$date_desember24_akhir'")
			->get()->row_array();
			$rak_7_vol_K175 = $rak_7_K175['vol_produk_b'];
			$rak_7_nilai_K175 = $rak_7_vol_K175 * $rak_7_K175['price_b'];

			$jumlah_vol_K175 = $rak_1_vol_K175 + $rak_2_vol_K175 + $rak_3_vol_K175 + $rak_4_vol_K175 + $rak_5_vol_K175 + $rak_6_vol_K175 + $rak_7_vol_K175;
			$jumlah_nilai_K175 = $rak_1_nilai_K175 + $rak_2_nilai_K175 + $rak_3_nilai_K175 + $rak_4_nilai_K175 + $rak_5_nilai_K175 + $rak_6_nilai_K175 + $rak_7_nilai_K175;
			
			//KOMPOSISI BAHAN K-175 SLUMP 10
			$komposisi_175_1 = $this->db->select('(r.vol_produk_b * pk.presentase_a) as vol_komposisi_semen, (r.vol_produk_b * pk.presentase_a) * pk.price_a as nilai_komposisi_semen, (r.vol_produk_b * pk.presentase_b) as vol_komposisi_pasir, (r.vol_produk_b * pk.presentase_b) * pk.price_b as nilai_komposisi_pasir, (r.vol_produk_b * pk.presentase_c) as vol_komposisi_batu1020, (r.vol_produk_b * pk.presentase_c) * pk.price_c as nilai_komposisi_batu1020, (r.vol_produk_b * pk.presentase_d) as vol_komposisi_batu2030, (r.vol_produk_b * pk.presentase_d) * pk.price_d as nilai_komposisi_batu2030, (r.vol_produk_b * pk.presentase_e) as vol_komposisi_additive, (r.vol_produk_b * pk.presentase_e) * pk.price_e as nilai_komposisi_additive')
			->from('rak r')
			->join('pmm_agregat pk', 'r.komposisi_175 = pk.id','left')
			->where("r.tanggal_rencana_kerja between '$date_juni24_awal' and '$date_juni24_akhir'")
			->get()->result_array();

			$total_volume_semen_175_1 = 0;
			$total_nilai_semen_175_1 = 0;

			foreach ($komposisi_175_1 as $x){
				$total_volume_semen_175_1 = $x['vol_komposisi_semen'];
				$total_nilai_semen_175_1 = $x['nilai_komposisi_semen'];
				$total_volume_pasir_175_1 = $x['vol_komposisi_pasir'];
				$total_nilai_pasir_175_1 = $x['nilai_komposisi_pasir'];
				$total_volume_batu1020_175_1 = $x['vol_komposisi_batu1020'];
				$total_nilai_batu1020_175_1 = $x['nilai_komposisi_batu1020'];
				$total_volume_batu2030_175_1 = $x['vol_komposisi_batu2030'];
				$total_nilai_batu2030_175_1 = $x['nilai_komposisi_batu2030'];
				$total_volume_additive_175_1 = $x['vol_komposisi_additive'];
				$total_nilai_additive_175_1 = $x['nilai_komposisi_additive'];
			}

			$komposisi_175_2 = $this->db->select('(r.vol_produk_b * pk.presentase_a) as vol_komposisi_semen, (r.vol_produk_b * pk.presentase_a) * pk.price_a as nilai_komposisi_semen, (r.vol_produk_b * pk.presentase_b) as vol_komposisi_pasir, (r.vol_produk_b * pk.presentase_b) * pk.price_b as nilai_komposisi_pasir, (r.vol_produk_b * pk.presentase_c) as vol_komposisi_batu1020, (r.vol_produk_b * pk.presentase_c) * pk.price_c as nilai_komposisi_batu1020, (r.vol_produk_b * pk.presentase_d) as vol_komposisi_batu2030, (r.vol_produk_b * pk.presentase_d) * pk.price_d as nilai_komposisi_batu2030, (r.vol_produk_b * pk.presentase_e) as vol_komposisi_additive, (r.vol_produk_b * pk.presentase_e) * pk.price_e as nilai_komposisi_additive')
			->from('rak r')
			->join('pmm_agregat pk', 'r.komposisi_175 = pk.id','left')
			->where("r.tanggal_rencana_kerja between '$date_juli24_awal' and '$date_juli24_akhir'")
			->get()->result_array();

			$total_volume_semen_175_2 = 0;
			$total_nilai_semen_175_2 = 0;

			foreach ($komposisi_175_2 as $x){
				$total_volume_semen_175_2 = $x['vol_komposisi_semen'];
				$total_nilai_semen_175_2 = $x['nilai_komposisi_semen'];
				$total_volume_pasir_175_2 = $x['vol_komposisi_pasir'];
				$total_nilai_pasir_175_2 = $x['nilai_komposisi_pasir'];
				$total_volume_batu1020_175_2 = $x['vol_komposisi_batu1020'];
				$total_nilai_batu1020_175_2 = $x['nilai_komposisi_batu1020'];
				$total_volume_batu2030_175_2 = $x['vol_komposisi_batu2030'];
				$total_nilai_batu2030_175_2 = $x['nilai_komposisi_batu2030'];
				$total_volume_additive_175_2 = $x['vol_komposisi_additive'];
				$total_nilai_additive_175_2 = $x['nilai_komposisi_additive'];
			}

			$komposisi_175_3 = $this->db->select('(r.vol_produk_b * pk.presentase_a) as vol_komposisi_semen, (r.vol_produk_b * pk.presentase_a) * pk.price_a as nilai_komposisi_semen, (r.vol_produk_b * pk.presentase_b) as vol_komposisi_pasir, (r.vol_produk_b * pk.presentase_b) * pk.price_b as nilai_komposisi_pasir, (r.vol_produk_b * pk.presentase_c) as vol_komposisi_batu1020, (r.vol_produk_b * pk.presentase_c) * pk.price_c as nilai_komposisi_batu1020, (r.vol_produk_b * pk.presentase_d) as vol_komposisi_batu2030, (r.vol_produk_b * pk.presentase_d) * pk.price_d as nilai_komposisi_batu2030, (r.vol_produk_b * pk.presentase_e) as vol_komposisi_additive, (r.vol_produk_b * pk.presentase_e) * pk.price_e as nilai_komposisi_additive')
			->from('rak r')
			->join('pmm_agregat pk', 'r.komposisi_175 = pk.id','left')
			->where("r.tanggal_rencana_kerja between '$date_agustus24_awal' and '$date_agustus24_akhir'")
			->get()->result_array();

			$total_volume_semen_175_3 = 0;
			$total_nilai_semen_175_3 = 0;

			foreach ($komposisi_175_3 as $x){
				$total_volume_semen_175_3 = $x['vol_komposisi_semen'];
				$total_nilai_semen_175_3 = $x['nilai_komposisi_semen'];
				$total_volume_pasir_175_3 = $x['vol_komposisi_pasir'];
				$total_nilai_pasir_175_3 = $x['nilai_komposisi_pasir'];
				$total_volume_batu1020_175_3 = $x['vol_komposisi_batu1020'];
				$total_nilai_batu1020_175_3 = $x['nilai_komposisi_batu1020'];
				$total_volume_batu2030_175_3 = $x['vol_komposisi_batu2030'];
				$total_nilai_batu2030_175_3 = $x['nilai_komposisi_batu2030'];
				$total_volume_additive_175_3 = $x['vol_komposisi_additive'];
				$total_nilai_additive_175_3 = $x['nilai_komposisi_additive'];
			}

			$komposisi_175_4 = $this->db->select('(r.vol_produk_b * pk.presentase_a) as vol_komposisi_semen, (r.vol_produk_b * pk.presentase_a) * pk.price_a as nilai_komposisi_semen, (r.vol_produk_b * pk.presentase_b) as vol_komposisi_pasir, (r.vol_produk_b * pk.presentase_b) * pk.price_b as nilai_komposisi_pasir, (r.vol_produk_b * pk.presentase_c) as vol_komposisi_batu1020, (r.vol_produk_b * pk.presentase_c) * pk.price_c as nilai_komposisi_batu1020, (r.vol_produk_b * pk.presentase_d) as vol_komposisi_batu2030, (r.vol_produk_b * pk.presentase_d) * pk.price_d as nilai_komposisi_batu2030, (r.vol_produk_b * pk.presentase_e) as vol_komposisi_additive, (r.vol_produk_b * pk.presentase_e) * pk.price_e as nilai_komposisi_additive')
			->from('rak r')
			->join('pmm_agregat pk', 'r.komposisi_175 = pk.id','left')
			->where("r.tanggal_rencana_kerja between '$date_september24_awal' and '$date_september24_akhir'")
			->get()->result_array();

			$total_volume_semen_175_4 = 0;
			$total_nilai_semen_175_4 = 0;

			foreach ($komposisi_175_4 as $x){
				$total_volume_semen_175_4 = $x['vol_komposisi_semen'];
				$total_nilai_semen_175_4 = $x['nilai_komposisi_semen'];
				$total_volume_pasir_175_4 = $x['vol_komposisi_pasir'];
				$total_nilai_pasir_175_4 = $x['nilai_komposisi_pasir'];
				$total_volume_batu1020_175_4 = $x['vol_komposisi_batu1020'];
				$total_nilai_batu1020_175_4 = $x['nilai_komposisi_batu1020'];
				$total_volume_batu2030_175_4 = $x['vol_komposisi_batu2030'];
				$total_nilai_batu2030_175_4 = $x['nilai_komposisi_batu2030'];
				$total_volume_additive_175_4 = $x['vol_komposisi_additive'];
				$total_nilai_additive_175_4 = $x['nilai_komposisi_additive'];
			}

			$komposisi_175_5 = $this->db->select('(r.vol_produk_b * pk.presentase_a) as vol_komposisi_semen, (r.vol_produk_b * pk.presentase_a) * pk.price_a as nilai_komposisi_semen, (r.vol_produk_b * pk.presentase_b) as vol_komposisi_pasir, (r.vol_produk_b * pk.presentase_b) * pk.price_b as nilai_komposisi_pasir, (r.vol_produk_b * pk.presentase_c) as vol_komposisi_batu1020, (r.vol_produk_b * pk.presentase_c) * pk.price_c as nilai_komposisi_batu1020, (r.vol_produk_b * pk.presentase_d) as vol_komposisi_batu2030, (r.vol_produk_b * pk.presentase_d) * pk.price_d as nilai_komposisi_batu2030, (r.vol_produk_b * pk.presentase_e) as vol_komposisi_additive, (r.vol_produk_b * pk.presentase_e) * pk.price_e as nilai_komposisi_additive')
			->from('rak r')
			->join('pmm_agregat pk', 'r.komposisi_175 = pk.id','left')
			->where("r.tanggal_rencana_kerja between '$date_oktober24_awal' and '$date_oktober24_akhir'")
			->get()->result_array();

			$total_volume_semen_175_5 = 0;
			$total_nilai_semen_175_5 = 0;

			foreach ($komposisi_175_5 as $x){
				$total_volume_semen_175_5 = $x['vol_komposisi_semen'];
				$total_nilai_semen_175_5 = $x['nilai_komposisi_semen'];
				$total_volume_pasir_175_5 = $x['vol_komposisi_pasir'];
				$total_nilai_pasir_175_5 = $x['nilai_komposisi_pasir'];
				$total_volume_batu1020_175_5 = $x['vol_komposisi_batu1020'];
				$total_nilai_batu1020_175_5 = $x['nilai_komposisi_batu1020'];
				$total_volume_batu2030_175_5 = $x['vol_komposisi_batu2030'];
				$total_nilai_batu2030_175_5 = $x['nilai_komposisi_batu2030'];
				$total_volume_additive_175_5 = $x['vol_komposisi_additive'];
				$total_nilai_additive_175_5 = $x['nilai_komposisi_additive'];
			}

			//BETON K-225 SLUMP 10
			$rak_1_K225 = $this->db->select('*, SUM(vol_produk_c) as vol_produk_c')
			->from('rak')
			->where("tanggal_rencana_kerja between '$date_juni24_awal' and '$date_juni24_akhir'")
			->get()->row_array();
			$rak_1_vol_K225 = $rak_1_K225['vol_produk_c'];
			$rak_1_nilai_K225 = $rak_1_vol_K225 * $rak_1_K225['price_c'];

			$rak_2_K225 = $this->db->select('*, SUM(vol_produk_c) as vol_produk_c')
			->from('rak')
			->where("tanggal_rencana_kerja between '$date_juli24_awal' and '$date_juli24_akhir'")
			->get()->row_array();
			$rak_2_vol_K225 = $rak_2_K225['vol_produk_c'];
			$rak_2_nilai_K225 = $rak_2_vol_K225 * $rak_2_K225['price_c'];

			$rak_3_K225 = $this->db->select('*, SUM(vol_produk_c) as vol_produk_c')
			->from('rak')
			->where("tanggal_rencana_kerja between '$date_agustus24_awal' and '$date_agustus24_akhir'")
			->get()->row_array();
			$rak_3_vol_K225 = $rak_3_K225['vol_produk_c'];
			$rak_3_nilai_K225 = $rak_3_vol_K225 * $rak_3_K225['price_c'];

			$rak_4_K225 = $this->db->select('*, SUM(vol_produk_c) as vol_produk_c')
			->from('rak')
			->where("tanggal_rencana_kerja between '$date_september24_awal' and '$date_september24_akhir'")
			->get()->row_array();
			$rak_4_vol_K225 = $rak_4_K225['vol_produk_c'];
			$rak_4_nilai_K225 = $rak_4_vol_K225 * $rak_4_K225['price_c'];

			$rak_5_K225 = $this->db->select('*, SUM(vol_produk_c) as vol_produk_c')
			->from('rak')
			->where("tanggal_rencana_kerja between '$date_oktober24_awal' and '$date_oktober24_akhir'")
			->get()->row_array();
			$rak_5_vol_K225 = $rak_5_K225['vol_produk_c'];
			$rak_5_nilai_K225 = $rak_5_vol_K225 * $rak_5_K225['price_c'];

			$rak_6_K225 = $this->db->select('*, SUM(vol_produk_c) as vol_produk_c')
			->from('rak')
			->where("tanggal_rencana_kerja between '$date_november24_awal' and '$date_november24_akhir'")
			->get()->row_array();
			$rak_6_vol_K225 = $rak_6_K225['vol_produk_c'];
			$rak_6_nilai_K225 = $rak_6_vol_K225 * $rak_6_K225['price_c'];

			$rak_7_K225 = $this->db->select('*, SUM(vol_produk_c) as vol_produk_c')
			->from('rak')
			->where("tanggal_rencana_kerja between '$date_desember24_awal' and '$date_desember24_akhir'")
			->get()->row_array();
			$rak_7_vol_K225 = $rak_7_K225['vol_produk_c'];
			$rak_7_nilai_K225 = $rak_7_vol_K225 * $rak_7_K225['price_c'];

			$jumlah_vol_K225 = $rak_1_vol_K225 + $rak_2_vol_K225 + $rak_3_vol_K225 + $rak_4_vol_K225 + $rak_5_vol_K225 + $rak_6_vol_K225 + $rak_7_vol_K225;
			$jumlah_nilai_K225 = $rak_1_nilai_K225 + $rak_2_nilai_K225 + $rak_3_nilai_K225 + $rak_4_nilai_K225 + $rak_5_nilai_K225 + $rak_6_nilai_K225 + $rak_7_nilai_K225;
			
			//KOMPOSISI BAHAN K-225 SLUMP 10
			$komposisi_225_1 = $this->db->select('(r.vol_produk_c * pk.presentase_a) as vol_komposisi_semen, (r.vol_produk_c * pk.presentase_a) * pk.price_a as nilai_komposisi_semen, (r.vol_produk_c * pk.presentase_b) as vol_komposisi_pasir, (r.vol_produk_c * pk.presentase_b) * pk.price_b as nilai_komposisi_pasir, (r.vol_produk_c * pk.presentase_c) as vol_komposisi_batu1020, (r.vol_produk_c * pk.presentase_c) * pk.price_c as nilai_komposisi_batu1020, (r.vol_produk_c * pk.presentase_d) as vol_komposisi_batu2030, (r.vol_produk_c * pk.presentase_d) * pk.price_d as nilai_komposisi_batu2030, (r.vol_produk_c * pk.presentase_e) as vol_komposisi_additive, (r.vol_produk_c * pk.presentase_e) * pk.price_e as nilai_komposisi_additive')
			->from('rak r')
			->join('pmm_agregat pk', 'r.komposisi_225 = pk.id','left')
			->where("r.tanggal_rencana_kerja between '$date_juni24_awal' and '$date_juni24_akhir'")
			->get()->result_array();

			$total_volume_semen_225_1 = 0;
			$total_nilai_semen_225_1 = 0;

			foreach ($komposisi_225_1 as $x){
				$total_volume_semen_225_1 = $x['vol_komposisi_semen'];
				$total_nilai_semen_225_1 = $x['nilai_komposisi_semen'];
				$total_volume_pasir_225_1 = $x['vol_komposisi_pasir'];
				$total_nilai_pasir_225_1 = $x['nilai_komposisi_pasir'];
				$total_volume_batu1020_225_1 = $x['vol_komposisi_batu1020'];
				$total_nilai_batu1020_225_1 = $x['nilai_komposisi_batu1020'];
				$total_volume_batu2030_225_1 = $x['vol_komposisi_batu2030'];
				$total_nilai_batu2030_225_1 = $x['nilai_komposisi_batu2030'];
				$total_volume_additive_225_1 = $x['vol_komposisi_additive'];
				$total_nilai_additive_225_1 = $x['nilai_komposisi_additive'];
			}

			$komposisi_225_2 = $this->db->select('(r.vol_produk_c * pk.presentase_a) as vol_komposisi_semen, (r.vol_produk_c * pk.presentase_a) * pk.price_a as nilai_komposisi_semen, (r.vol_produk_c * pk.presentase_b) as vol_komposisi_pasir, (r.vol_produk_c * pk.presentase_b) * pk.price_b as nilai_komposisi_pasir, (r.vol_produk_c * pk.presentase_c) as vol_komposisi_batu1020, (r.vol_produk_c * pk.presentase_c) * pk.price_c as nilai_komposisi_batu1020, (r.vol_produk_c * pk.presentase_d) as vol_komposisi_batu2030, (r.vol_produk_c * pk.presentase_d) * pk.price_d as nilai_komposisi_batu2030, (r.vol_produk_c * pk.presentase_e) as vol_komposisi_additive, (r.vol_produk_c * pk.presentase_e) * pk.price_e as nilai_komposisi_additive')
			->from('rak r')
			->join('pmm_agregat pk', 'r.komposisi_225 = pk.id','left')
			->where("r.tanggal_rencana_kerja between '$date_juli24_awal' and '$date_juli24_akhir'")
			->get()->result_array();

			$total_volume_semen_225_2 = 0;
			$total_nilai_semen_225_2 = 0;

			foreach ($komposisi_225_2 as $x){
				$total_volume_semen_225_2 = $x['vol_komposisi_semen'];
				$total_nilai_semen_225_2 = $x['nilai_komposisi_semen'];
				$total_volume_pasir_225_2 = $x['vol_komposisi_pasir'];
				$total_nilai_pasir_225_2 = $x['nilai_komposisi_pasir'];
				$total_volume_batu1020_225_2 = $x['vol_komposisi_batu1020'];
				$total_nilai_batu1020_225_2 = $x['nilai_komposisi_batu1020'];
				$total_volume_batu2030_225_2 = $x['vol_komposisi_batu2030'];
				$total_nilai_batu2030_225_2 = $x['nilai_komposisi_batu2030'];
				$total_volume_additive_225_2 = $x['vol_komposisi_additive'];
				$total_nilai_additive_225_2 = $x['nilai_komposisi_additive'];
			}

			$komposisi_225_3 = $this->db->select('(r.vol_produk_c * pk.presentase_a) as vol_komposisi_semen, (r.vol_produk_c * pk.presentase_a) * pk.price_a as nilai_komposisi_semen, (r.vol_produk_c * pk.presentase_b) as vol_komposisi_pasir, (r.vol_produk_c * pk.presentase_b) * pk.price_b as nilai_komposisi_pasir, (r.vol_produk_c * pk.presentase_c) as vol_komposisi_batu1020, (r.vol_produk_c * pk.presentase_c) * pk.price_c as nilai_komposisi_batu1020, (r.vol_produk_c * pk.presentase_d) as vol_komposisi_batu2030, (r.vol_produk_c * pk.presentase_d) * pk.price_d as nilai_komposisi_batu2030, (r.vol_produk_c * pk.presentase_e) as vol_komposisi_additive, (r.vol_produk_c * pk.presentase_e) * pk.price_e as nilai_komposisi_additive')
			->from('rak r')
			->join('pmm_agregat pk', 'r.komposisi_225 = pk.id','left')
			->where("r.tanggal_rencana_kerja between '$date_agustus24_awal' and '$date_agustus24_akhir'")
			->get()->result_array();

			$total_volume_semen_225_3 = 0;
			$total_nilai_semen_225_3 = 0;

			foreach ($komposisi_225_3 as $x){
				$total_volume_semen_225_3 = $x['vol_komposisi_semen'];
				$total_nilai_semen_225_3 = $x['nilai_komposisi_semen'];
				$total_volume_pasir_225_3 = $x['vol_komposisi_pasir'];
				$total_nilai_pasir_225_3 = $x['nilai_komposisi_pasir'];
				$total_volume_batu1020_225_3 = $x['vol_komposisi_batu1020'];
				$total_nilai_batu1020_225_3 = $x['nilai_komposisi_batu1020'];
				$total_volume_batu2030_225_3 = $x['vol_komposisi_batu2030'];
				$total_nilai_batu2030_225_3 = $x['nilai_komposisi_batu2030'];
				$total_volume_additive_225_3 = $x['vol_komposisi_additive'];
				$total_nilai_additive_225_3 = $x['nilai_komposisi_additive'];
			}

			$komposisi_225_4 = $this->db->select('(r.vol_produk_c * pk.presentase_a) as vol_komposisi_semen, (r.vol_produk_c * pk.presentase_a) * pk.price_a as nilai_komposisi_semen, (r.vol_produk_c * pk.presentase_b) as vol_komposisi_pasir, (r.vol_produk_c * pk.presentase_b) * pk.price_b as nilai_komposisi_pasir, (r.vol_produk_c * pk.presentase_c) as vol_komposisi_batu1020, (r.vol_produk_c * pk.presentase_c) * pk.price_c as nilai_komposisi_batu1020, (r.vol_produk_c * pk.presentase_d) as vol_komposisi_batu2030, (r.vol_produk_c * pk.presentase_d) * pk.price_d as nilai_komposisi_batu2030, (r.vol_produk_c * pk.presentase_e) as vol_komposisi_additive, (r.vol_produk_c * pk.presentase_e) * pk.price_e as nilai_komposisi_additive')
			->from('rak r')
			->join('pmm_agregat pk', 'r.komposisi_225 = pk.id','left')
			->where("r.tanggal_rencana_kerja between '$date_september24_awal' and '$date_september24_akhir'")
			->get()->result_array();

			$total_volume_semen_225_4 = 0;
			$total_nilai_semen_225_4 = 0;

			foreach ($komposisi_225_4 as $x){
				$total_volume_semen_225_4 = $x['vol_komposisi_semen'];
				$total_nilai_semen_225_4 = $x['nilai_komposisi_semen'];
				$total_volume_pasir_225_4 = $x['vol_komposisi_pasir'];
				$total_nilai_pasir_225_4 = $x['nilai_komposisi_pasir'];
				$total_volume_batu1020_225_4 = $x['vol_komposisi_batu1020'];
				$total_nilai_batu1020_225_4 = $x['nilai_komposisi_batu1020'];
				$total_volume_batu2030_225_4 = $x['vol_komposisi_batu2030'];
				$total_nilai_batu2030_225_4 = $x['nilai_komposisi_batu2030'];
				$total_volume_additive_225_4 = $x['vol_komposisi_additive'];
				$total_nilai_additive_225_4 = $x['nilai_komposisi_additive'];
			}

			$komposisi_225_5 = $this->db->select('(r.vol_produk_c * pk.presentase_a) as vol_komposisi_semen, (r.vol_produk_c * pk.presentase_a) * pk.price_a as nilai_komposisi_semen, (r.vol_produk_c * pk.presentase_b) as vol_komposisi_pasir, (r.vol_produk_c * pk.presentase_b) * pk.price_b as nilai_komposisi_pasir, (r.vol_produk_c * pk.presentase_c) as vol_komposisi_batu1020, (r.vol_produk_c * pk.presentase_c) * pk.price_c as nilai_komposisi_batu1020, (r.vol_produk_c * pk.presentase_d) as vol_komposisi_batu2030, (r.vol_produk_c * pk.presentase_d) * pk.price_d as nilai_komposisi_batu2030, (r.vol_produk_c * pk.presentase_e) as vol_komposisi_additive, (r.vol_produk_c * pk.presentase_e) * pk.price_e as nilai_komposisi_additive')
			->from('rak r')
			->join('pmm_agregat pk', 'r.komposisi_225 = pk.id','left')
			->where("r.tanggal_rencana_kerja between '$date_oktober24_awal' and '$date_oktober24_akhir'")
			->get()->result_array();

			$total_volume_semen_225_5 = 0;
			$total_nilai_semen_225_5 = 0;

			foreach ($komposisi_225_5 as $x){
				$total_volume_semen_225_5 = $x['vol_komposisi_semen'];
				$total_nilai_semen_225_5 = $x['nilai_komposisi_semen'];
				$total_volume_pasir_225_5 = $x['vol_komposisi_pasir'];
				$total_nilai_pasir_225_5 = $x['nilai_komposisi_pasir'];
				$total_volume_batu1020_225_5 = $x['vol_komposisi_batu1020'];
				$total_nilai_batu1020_225_5 = $x['nilai_komposisi_batu1020'];
				$total_volume_batu2030_225_5 = $x['vol_komposisi_batu2030'];
				$total_nilai_batu2030_225_5 = $x['nilai_komposisi_batu2030'];
				$total_volume_additive_225_5 = $x['vol_komposisi_additive'];
				$total_nilai_additive_225_5 = $x['nilai_komposisi_additive'];
			}

			$komposisi_225_6 = $this->db->select('(r.vol_produk_c * pk.presentase_a) as vol_komposisi_semen, (r.vol_produk_c * pk.presentase_a) * pk.price_a as nilai_komposisi_semen, (r.vol_produk_c * pk.presentase_b) as vol_komposisi_pasir, (r.vol_produk_c * pk.presentase_b) * pk.price_b as nilai_komposisi_pasir, (r.vol_produk_c * pk.presentase_c) as vol_komposisi_batu1020, (r.vol_produk_c * pk.presentase_c) * pk.price_c as nilai_komposisi_batu1020, (r.vol_produk_c * pk.presentase_d) as vol_komposisi_batu2030, (r.vol_produk_c * pk.presentase_d) * pk.price_d as nilai_komposisi_batu2030, (r.vol_produk_c * pk.presentase_e) as vol_komposisi_additive, (r.vol_produk_c * pk.presentase_e) * pk.price_e as nilai_komposisi_additive')
			->from('rak r')
			->join('pmm_agregat pk', 'r.komposisi_225 = pk.id','left')
			->where("r.tanggal_rencana_kerja between '$date_november24_awal' and '$date_november24_akhir'")
			->get()->result_array();

			$total_volume_semen_225_6 = 0;
			$total_nilai_semen_225_6 = 0;

			foreach ($komposisi_225_6 as $x){
				$total_volume_semen_225_6 = $x['vol_komposisi_semen'];
				$total_nilai_semen_225_6 = $x['nilai_komposisi_semen'];
				$total_volume_pasir_225_6 = $x['vol_komposisi_pasir'];
				$total_nilai_pasir_225_6 = $x['nilai_komposisi_pasir'];
				$total_volume_batu1020_225_6 = $x['vol_komposisi_batu1020'];
				$total_nilai_batu1020_225_6 = $x['nilai_komposisi_batu1020'];
				$total_volume_batu2030_225_6 = $x['vol_komposisi_batu2030'];
				$total_nilai_batu2030_225_6 = $x['nilai_komposisi_batu2030'];
				$total_volume_additive_225_6 = $x['vol_komposisi_additive'];
				$total_nilai_additive_225_6 = $x['nilai_komposisi_additive'];
			}

			$komposisi_225_7 = $this->db->select('(r.vol_produk_c * pk.presentase_a) as vol_komposisi_semen, (r.vol_produk_c * pk.presentase_a) * pk.price_a as nilai_komposisi_semen, (r.vol_produk_c * pk.presentase_b) as vol_komposisi_pasir, (r.vol_produk_c * pk.presentase_b) * pk.price_b as nilai_komposisi_pasir, (r.vol_produk_c * pk.presentase_c) as vol_komposisi_batu1020, (r.vol_produk_c * pk.presentase_c) * pk.price_c as nilai_komposisi_batu1020, (r.vol_produk_c * pk.presentase_d) as vol_komposisi_batu2030, (r.vol_produk_c * pk.presentase_d) * pk.price_d as nilai_komposisi_batu2030, (r.vol_produk_c * pk.presentase_e) as vol_komposisi_additive, (r.vol_produk_c * pk.presentase_e) * pk.price_e as nilai_komposisi_additive')
			->from('rak r')
			->join('pmm_agregat pk', 'r.komposisi_225 = pk.id','left')
			->where("r.tanggal_rencana_kerja between '$date_desember24_awal' and '$date_desember24_akhir'")
			->get()->result_array();

			$total_volume_semen_225_7 = 0;
			$total_nilai_semen_225_7 = 0;

			foreach ($komposisi_225_7 as $x){
				$total_volume_semen_225_7 = $x['vol_komposisi_semen'];
				$total_nilai_semen_225_7 = $x['nilai_komposisi_semen'];
				$total_volume_pasir_225_7 = $x['vol_komposisi_pasir'];
				$total_nilai_pasir_225_7 = $x['nilai_komposisi_pasir'];
				$total_volume_batu1020_225_7 = $x['vol_komposisi_batu1020'];
				$total_nilai_batu1020_225_7 = $x['nilai_komposisi_batu1020'];
				$total_volume_batu2030_225_7 = $x['vol_komposisi_batu2030'];
				$total_nilai_batu2030_225_7 = $x['nilai_komposisi_batu2030'];
				$total_volume_additive_225_7 = $x['vol_komposisi_additive'];
				$total_nilai_additive_225_7 = $x['nilai_komposisi_additive'];
			}

			//BETON K-250 SLUMP 10
			$rak_1_K250 = $this->db->select('*, SUM(vol_produk_d) as vol_produk_d')
			->from('rak')
			->where("tanggal_rencana_kerja between '$date_juni24_awal' and '$date_juni24_akhir'")
			->get()->row_array();
			$rak_1_vol_K250 = $rak_1_K250['vol_produk_d'];
			$rak_1_nilai_K250 = $rak_1_vol_K250 * $rak_1_K250['price_d'];

			$rak_2_K250 = $this->db->select('*, SUM(vol_produk_d) as vol_produk_d')
			->from('rak')
			->where("tanggal_rencana_kerja between '$date_juli24_awal' and '$date_juli24_akhir'")
			->get()->row_array();
			$rak_2_vol_K250 = $rak_2_K250['vol_produk_d'];
			$rak_2_nilai_K250 = $rak_2_vol_K250 * $rak_2_K250['price_d'];

			$rak_3_K250 = $this->db->select('*, SUM(vol_produk_d) as vol_produk_d')
			->from('rak')
			->where("tanggal_rencana_kerja between '$date_agustus24_awal' and '$date_agustus24_akhir'")
			->get()->row_array();
			$rak_3_vol_K250 = $rak_3_K250['vol_produk_d'];
			$rak_3_nilai_K250 = $rak_3_vol_K250 * $rak_3_K250['price_d'];

			$rak_4_K250 = $this->db->select('*, SUM(vol_produk_d) as vol_produk_d')
			->from('rak')
			->where("tanggal_rencana_kerja between '$date_september24_awal' and '$date_september24_akhir'")
			->get()->row_array();
			$rak_4_vol_K250 = $rak_4_K250['vol_produk_d'];
			$rak_4_nilai_K250 = $rak_4_vol_K250 * $rak_4_K250['price_d'];

			$rak_5_K250 = $this->db->select('*, SUM(vol_produk_d) as vol_produk_d')
			->from('rak')
			->where("tanggal_rencana_kerja between '$date_oktober24_awal' and '$date_oktober24_akhir'")
			->get()->row_array();
			$rak_5_vol_K250 = $rak_5_K250['vol_produk_d'];
			$rak_5_nilai_K250 = $rak_5_vol_K250 * $rak_5_K250['price_d'];

			$rak_6_K250 = $this->db->select('*, SUM(vol_produk_d) as vol_produk_d')
			->from('rak')
			->where("tanggal_rencana_kerja between '$date_november24_awal' and '$date_november24_akhir'")
			->get()->row_array();
			$rak_6_vol_K250 = $rak_6_K250['vol_produk_d'];
			$rak_6_nilai_K250 = $rak_6_vol_K250 * $rak_6_K250['price_d'];

			$rak_7_K250 = $this->db->select('*, SUM(vol_produk_d) as vol_produk_d')
			->from('rak')
			->where("tanggal_rencana_kerja between '$date_desember24_awal' and '$date_desember24_akhir'")
			->get()->row_array();
			$rak_7_vol_K250 = $rak_7_K250['vol_produk_d'];
			$rak_7_nilai_K250 = $rak_7_vol_K250 * $rak_7_K250['price_d'];

			$jumlah_vol_K250 = $rak_1_vol_K250 + $rak_2_vol_K250 + $rak_3_vol_K250 + $rak_4_vol_K250 + $rak_5_vol_K250 + $rak_6_vol_K250 + $rak_7_vol_K250;
			$jumlah_nilai_K250 = $rak_1_nilai_K250 + $rak_2_nilai_K250 + $rak_3_nilai_K250 + $rak_4_nilai_K250 + $rak_5_nilai_K250 + $rak_6_nilai_K250 + $rak_7_nilai_K250;
			
			//KOMPOSISI BAHAN K-250 SLUMP 10
			$komposisi_250_1 = $this->db->select('(r.vol_produk_d * pk.presentase_a) as vol_komposisi_semen, (r.vol_produk_d * pk.presentase_a) * pk.price_a as nilai_komposisi_semen, (r.vol_produk_d * pk.presentase_b) as vol_komposisi_pasir, (r.vol_produk_d * pk.presentase_b) * pk.price_b as nilai_komposisi_pasir, (r.vol_produk_d * pk.presentase_c) as vol_komposisi_batu1020, (r.vol_produk_d * pk.presentase_c) * pk.price_c as nilai_komposisi_batu1020, (r.vol_produk_d * pk.presentase_d) as vol_komposisi_batu2030, (r.vol_produk_d * pk.presentase_d) * pk.price_d as nilai_komposisi_batu2030, (r.vol_produk_d * pk.presentase_e) as vol_komposisi_additive, (r.vol_produk_d * pk.presentase_e) * pk.price_e as nilai_komposisi_additive')
			->from('rak r')
			->join('pmm_agregat pk', 'r.komposisi_250 = pk.id','left')
			->where("r.tanggal_rencana_kerja between '$date_juni24_awal' and '$date_juni24_akhir'")
			->get()->result_array();

			$total_volume_semen_250_1 = 0;
			$total_nilai_semen_250_1 = 0;

			foreach ($komposisi_250_1 as $x){
				$total_volume_semen_250_1 = $x['vol_komposisi_semen'];
				$total_nilai_semen_250_1 = $x['nilai_komposisi_semen'];
				$total_volume_pasir_250_1 = $x['vol_komposisi_pasir'];
				$total_nilai_pasir_250_1 = $x['nilai_komposisi_pasir'];
				$total_volume_batu1020_250_1 = $x['vol_komposisi_batu1020'];
				$total_nilai_batu1020_250_1 = $x['nilai_komposisi_batu1020'];
				$total_volume_batu2030_250_1 = $x['vol_komposisi_batu2030'];
				$total_nilai_batu2030_250_1 = $x['nilai_komposisi_batu2030'];
				$total_volume_additive_250_1 = $x['vol_komposisi_additive'];
				$total_nilai_additive_250_1 = $x['nilai_komposisi_additive'];
			}

			$komposisi_250_2 = $this->db->select('(r.vol_produk_d * pk.presentase_a) as vol_komposisi_semen, (r.vol_produk_d * pk.presentase_a) * pk.price_a as nilai_komposisi_semen, (r.vol_produk_d * pk.presentase_b) as vol_komposisi_pasir, (r.vol_produk_d * pk.presentase_b) * pk.price_b as nilai_komposisi_pasir, (r.vol_produk_d * pk.presentase_c) as vol_komposisi_batu1020, (r.vol_produk_d * pk.presentase_c) * pk.price_c as nilai_komposisi_batu1020, (r.vol_produk_d * pk.presentase_d) as vol_komposisi_batu2030, (r.vol_produk_d * pk.presentase_d) * pk.price_d as nilai_komposisi_batu2030, (r.vol_produk_d * pk.presentase_e) as vol_komposisi_additive, (r.vol_produk_d * pk.presentase_e) * pk.price_e as nilai_komposisi_additive')
			->from('rak r')
			->join('pmm_agregat pk', 'r.komposisi_250 = pk.id','left')
			->where("r.tanggal_rencana_kerja between '$date_juli24_awal' and '$date_juli24_akhir'")
			->get()->result_array();

			$total_volume_semen_250_2 = 0;
			$total_nilai_semen_250_2 = 0;

			foreach ($komposisi_250_2 as $x){
				$total_volume_semen_250_2 = $x['vol_komposisi_semen'];
				$total_nilai_semen_250_2 = $x['nilai_komposisi_semen'];
				$total_volume_pasir_250_2 = $x['vol_komposisi_pasir'];
				$total_nilai_pasir_250_2 = $x['nilai_komposisi_pasir'];
				$total_volume_batu1020_250_2 = $x['vol_komposisi_batu1020'];
				$total_nilai_batu1020_250_2 = $x['nilai_komposisi_batu1020'];
				$total_volume_batu2030_250_2 = $x['vol_komposisi_batu2030'];
				$total_nilai_batu2030_250_2 = $x['nilai_komposisi_batu2030'];
				$total_volume_additive_250_2 = $x['vol_komposisi_additive'];
				$total_nilai_additive_250_2 = $x['nilai_komposisi_additive'];
			}

			$komposisi_250_3 = $this->db->select('(r.vol_produk_d * pk.presentase_a) as vol_komposisi_semen, (r.vol_produk_d * pk.presentase_a) * pk.price_a as nilai_komposisi_semen, (r.vol_produk_d * pk.presentase_b) as vol_komposisi_pasir, (r.vol_produk_d * pk.presentase_b) * pk.price_b as nilai_komposisi_pasir, (r.vol_produk_d * pk.presentase_c) as vol_komposisi_batu1020, (r.vol_produk_d * pk.presentase_c) * pk.price_c as nilai_komposisi_batu1020, (r.vol_produk_d * pk.presentase_d) as vol_komposisi_batu2030, (r.vol_produk_d * pk.presentase_d) * pk.price_d as nilai_komposisi_batu2030, (r.vol_produk_d * pk.presentase_e) as vol_komposisi_additive, (r.vol_produk_d * pk.presentase_e) * pk.price_e as nilai_komposisi_additive')
			->from('rak r')
			->join('pmm_agregat pk', 'r.komposisi_250 = pk.id','left')
			->where("r.tanggal_rencana_kerja between '$date_agustus24_awal' and '$date_agustus24_akhir'")
			->get()->result_array();

			$total_volume_semen_250_3 = 0;
			$total_nilai_semen_250_3 = 0;

			foreach ($komposisi_250_3 as $x){
				$total_volume_semen_250_3 = $x['vol_komposisi_semen'];
				$total_nilai_semen_250_3 = $x['nilai_komposisi_semen'];
				$total_volume_pasir_250_3 = $x['vol_komposisi_pasir'];
				$total_nilai_pasir_250_3 = $x['nilai_komposisi_pasir'];
				$total_volume_batu1020_250_3 = $x['vol_komposisi_batu1020'];
				$total_nilai_batu1020_250_3 = $x['nilai_komposisi_batu1020'];
				$total_volume_batu2030_250_3 = $x['vol_komposisi_batu2030'];
				$total_nilai_batu2030_250_3 = $x['nilai_komposisi_batu2030'];
				$total_volume_additive_250_3 = $x['vol_komposisi_additive'];
				$total_nilai_additive_250_3 = $x['nilai_komposisi_additive'];
			}

			$komposisi_250_4 = $this->db->select('(r.vol_produk_d * pk.presentase_a) as vol_komposisi_semen, (r.vol_produk_d * pk.presentase_a) * pk.price_a as nilai_komposisi_semen, (r.vol_produk_d * pk.presentase_b) as vol_komposisi_pasir, (r.vol_produk_d * pk.presentase_b) * pk.price_b as nilai_komposisi_pasir, (r.vol_produk_d * pk.presentase_c) as vol_komposisi_batu1020, (r.vol_produk_d * pk.presentase_c) * pk.price_c as nilai_komposisi_batu1020, (r.vol_produk_d * pk.presentase_d) as vol_komposisi_batu2030, (r.vol_produk_d * pk.presentase_d) * pk.price_d as nilai_komposisi_batu2030, (r.vol_produk_d * pk.presentase_e) as vol_komposisi_additive, (r.vol_produk_d * pk.presentase_e) * pk.price_e as nilai_komposisi_additive')
			->from('rak r')
			->join('pmm_agregat pk', 'r.komposisi_250 = pk.id','left')
			->where("r.tanggal_rencana_kerja between '$date_september24_awal' and '$date_september24_akhir'")
			->get()->result_array();

			$total_volume_semen_250_4 = 0;
			$total_nilai_semen_250_4 = 0;

			foreach ($komposisi_250_4 as $x){
				$total_volume_semen_250_4 = $x['vol_komposisi_semen'];
				$total_nilai_semen_250_4 = $x['nilai_komposisi_semen'];
				$total_volume_pasir_250_4 = $x['vol_komposisi_pasir'];
				$total_nilai_pasir_250_4 = $x['nilai_komposisi_pasir'];
				$total_volume_batu1020_250_4 = $x['vol_komposisi_batu1020'];
				$total_nilai_batu1020_250_4 = $x['nilai_komposisi_batu1020'];
				$total_volume_batu2030_250_4 = $x['vol_komposisi_batu2030'];
				$total_nilai_batu2030_250_4 = $x['nilai_komposisi_batu2030'];
				$total_volume_additive_250_4 = $x['vol_komposisi_additive'];
				$total_nilai_additive_250_4 = $x['nilai_komposisi_additive'];
			}

			$komposisi_250_5 = $this->db->select('(r.vol_produk_d * pk.presentase_a) as vol_komposisi_semen, (r.vol_produk_d * pk.presentase_a) * pk.price_a as nilai_komposisi_semen, (r.vol_produk_d * pk.presentase_b) as vol_komposisi_pasir, (r.vol_produk_d * pk.presentase_b) * pk.price_b as nilai_komposisi_pasir, (r.vol_produk_d * pk.presentase_c) as vol_komposisi_batu1020, (r.vol_produk_d * pk.presentase_c) * pk.price_c as nilai_komposisi_batu1020, (r.vol_produk_d * pk.presentase_d) as vol_komposisi_batu2030, (r.vol_produk_d * pk.presentase_d) * pk.price_d as nilai_komposisi_batu2030, (r.vol_produk_d * pk.presentase_e) as vol_komposisi_additive, (r.vol_produk_d * pk.presentase_e) * pk.price_e as nilai_komposisi_additive')
			->from('rak r')
			->join('pmm_agregat pk', 'r.komposisi_250 = pk.id','left')
			->where("r.tanggal_rencana_kerja between '$date_oktober24_awal' and '$date_oktober24_akhir'")
			->get()->result_array();

			$total_volume_semen_250_5 = 0;
			$total_nilai_semen_250_5 = 0;

			foreach ($komposisi_250_5 as $x){
				$total_volume_semen_250_5 = $x['vol_komposisi_semen'];
				$total_nilai_semen_250_5 = $x['nilai_komposisi_semen'];
				$total_volume_pasir_250_5 = $x['vol_komposisi_pasir'];
				$total_nilai_pasir_250_5 = $x['nilai_komposisi_pasir'];
				$total_volume_batu1020_250_5 = $x['vol_komposisi_batu1020'];
				$total_nilai_batu1020_250_5 = $x['nilai_komposisi_batu1020'];
				$total_volume_batu2030_250_5 = $x['vol_komposisi_batu2030'];
				$total_nilai_batu2030_250_5 = $x['nilai_komposisi_batu2030'];
				$total_volume_additive_250_5 = $x['vol_komposisi_additive'];
				$total_nilai_additive_250_5 = $x['nilai_komposisi_additive'];
			}

			$komposisi_250_6 = $this->db->select('(r.vol_produk_d * pk.presentase_a) as vol_komposisi_semen, (r.vol_produk_d * pk.presentase_a) * pk.price_a as nilai_komposisi_semen, (r.vol_produk_d * pk.presentase_b) as vol_komposisi_pasir, (r.vol_produk_d * pk.presentase_b) * pk.price_b as nilai_komposisi_pasir, (r.vol_produk_d * pk.presentase_c) as vol_komposisi_batu1020, (r.vol_produk_d * pk.presentase_c) * pk.price_c as nilai_komposisi_batu1020, (r.vol_produk_d * pk.presentase_d) as vol_komposisi_batu2030, (r.vol_produk_d * pk.presentase_d) * pk.price_d as nilai_komposisi_batu2030, (r.vol_produk_d * pk.presentase_e) as vol_komposisi_additive, (r.vol_produk_d * pk.presentase_e) * pk.price_e as nilai_komposisi_additive')
			->from('rak r')
			->join('pmm_agregat pk', 'r.komposisi_250 = pk.id','left')
			->where("r.tanggal_rencana_kerja between '$date_november24_awal' and '$date_november24_akhir'")
			->get()->result_array();

			$total_volume_semen_250_6 = 0;
			$total_nilai_semen_250_6 = 0;

			foreach ($komposisi_250_6 as $x){
				$total_volume_semen_250_6 = $x['vol_komposisi_semen'];
				$total_nilai_semen_250_6 = $x['nilai_komposisi_semen'];
				$total_volume_pasir_250_6 = $x['vol_komposisi_pasir'];
				$total_nilai_pasir_250_6 = $x['nilai_komposisi_pasir'];
				$total_volume_batu1020_250_6 = $x['vol_komposisi_batu1020'];
				$total_nilai_batu1020_250_6 = $x['nilai_komposisi_batu1020'];
				$total_volume_batu2030_250_6 = $x['vol_komposisi_batu2030'];
				$total_nilai_batu2030_250_6 = $x['nilai_komposisi_batu2030'];
				$total_volume_additive_250_6 = $x['vol_komposisi_additive'];
				$total_nilai_additive_250_6 = $x['nilai_komposisi_additive'];
			}

			$komposisi_250_7 = $this->db->select('(r.vol_produk_d * pk.presentase_a) as vol_komposisi_semen, (r.vol_produk_d * pk.presentase_a) * pk.price_a as nilai_komposisi_semen, (r.vol_produk_d * pk.presentase_b) as vol_komposisi_pasir, (r.vol_produk_d * pk.presentase_b) * pk.price_b as nilai_komposisi_pasir, (r.vol_produk_d * pk.presentase_c) as vol_komposisi_batu1020, (r.vol_produk_d * pk.presentase_c) * pk.price_c as nilai_komposisi_batu1020, (r.vol_produk_d * pk.presentase_d) as vol_komposisi_batu2030, (r.vol_produk_d * pk.presentase_d) * pk.price_d as nilai_komposisi_batu2030, (r.vol_produk_d * pk.presentase_e) as vol_komposisi_additive, (r.vol_produk_d * pk.presentase_e) * pk.price_e as nilai_komposisi_additive')
			->from('rak r')
			->join('pmm_agregat pk', 'r.komposisi_250 = pk.id','left')
			->where("r.tanggal_rencana_kerja between '$date_desember24_awal' and '$date_desember24_akhir'")
			->get()->result_array();

			$total_volume_semen_250_7 = 0;
			$total_nilai_semen_250_7 = 0;

			foreach ($komposisi_250_7 as $x){
				$total_volume_semen_250_7 = $x['vol_komposisi_semen'];
				$total_nilai_semen_250_7 = $x['nilai_komposisi_semen'];
				$total_volume_pasir_250_7 = $x['vol_komposisi_pasir'];
				$total_nilai_pasir_250_7 = $x['nilai_komposisi_pasir'];
				$total_volume_batu1020_250_7 = $x['vol_komposisi_batu1020'];
				$total_nilai_batu1020_250_7 = $x['nilai_komposisi_batu1020'];
				$total_volume_batu2030_250_7 = $x['vol_komposisi_batu2030'];
				$total_nilai_batu2030_250_7 = $x['nilai_komposisi_batu2030'];
				$total_volume_additive_250_7 = $x['vol_komposisi_additive'];
				$total_nilai_additive_250_7 = $x['nilai_komposisi_additive'];
			}

			//BETON K-300 SLUMP 10
			$rak_1_K300 = $this->db->select('*, SUM(vol_produk_e) as vol_produk_e')
			->from('rak')
			->where("tanggal_rencana_kerja between '$date_juni24_awal' and '$date_juni24_akhir'")
			->get()->row_array();
			$rak_1_vol_K300 = $rak_1_K300['vol_produk_e'];
			$rak_1_nilai_K300 = $rak_1_vol_K300 * $rak_1_K300['price_e'];

			$rak_2_K300 = $this->db->select('*, SUM(vol_produk_e) as vol_produk_e')
			->from('rak')
			->where("tanggal_rencana_kerja between '$date_juli24_awal' and '$date_juli24_akhir'")
			->get()->row_array();
			$rak_2_vol_K300 = $rak_2_K300['vol_produk_e'];
			$rak_2_nilai_K300 = $rak_2_vol_K300 * $rak_2_K300['price_e'];

			$rak_3_K300 = $this->db->select('*, SUM(vol_produk_e) as vol_produk_e')
			->from('rak')
			->where("tanggal_rencana_kerja between '$date_agustus24_awal' and '$date_agustus24_akhir'")
			->get()->row_array();
			$rak_3_vol_K300 = $rak_3_K300['vol_produk_e'];
			$rak_3_nilai_K300 = $rak_3_vol_K300 * $rak_3_K300['price_e'];

			$rak_4_K300 = $this->db->select('*, SUM(vol_produk_e) as vol_produk_e')
			->from('rak')
			->where("tanggal_rencana_kerja between '$date_september24_awal' and '$date_september24_akhir'")
			->get()->row_array();
			$rak_4_vol_K300 = $rak_4_K300['vol_produk_e'];
			$rak_4_nilai_K300 = $rak_4_vol_K300 * $rak_4_K300['price_e'];

			$rak_5_K300 = $this->db->select('*, SUM(vol_produk_e) as vol_produk_e')
			->from('rak')
			->where("tanggal_rencana_kerja between '$date_oktober24_awal' and '$date_oktober24_akhir'")
			->get()->row_array();
			$rak_5_vol_K300 = $rak_5_K300['vol_produk_e'];
			$rak_5_nilai_K300 = $rak_5_vol_K300 * $rak_5_K300['price_e'];

			$rak_6_K300 = $this->db->select('*, SUM(vol_produk_e) as vol_produk_e')
			->from('rak')
			->where("tanggal_rencana_kerja between '$date_november24_awal' and '$date_november24_akhir'")
			->get()->row_array();
			$rak_6_vol_K300 = $rak_6_K300['vol_produk_e'];
			$rak_6_nilai_K300 = $rak_6_vol_K300 * $rak_6_K300['price_e'];

			$rak_7_K300 = $this->db->select('*, SUM(vol_produk_e) as vol_produk_e')
			->from('rak')
			->where("tanggal_rencana_kerja between '$date_desember24_awal' and '$date_desember24_akhir'")
			->get()->row_array();
			$rak_7_vol_K300 = $rak_7_K300['vol_produk_e'];
			$rak_7_nilai_K300 = $rak_7_vol_K300 * $rak_7_K300['price_e'];

			$jumlah_vol_K300 = $rak_1_vol_K300 + $rak_2_vol_K300 + $rak_3_vol_K300 + $rak_4_vol_K300 + $rak_5_vol_K300 + $rak_6_vol_K300 + $rak_7_vol_K300;
			$jumlah_nilai_K300 = $rak_1_nilai_K300 + $rak_2_nilai_K300 + $rak_3_nilai_K300 + $rak_4_nilai_K300 + $rak_5_nilai_K300 + $rak_6_nilai_K300 + $rak_7_nilai_K300;
			
			//KOMPOSISI BAHAN K-300 SLUMP 10
			$komposisi_300_1 = $this->db->select('(r.vol_produk_e * pk.presentase_a) as vol_komposisi_semen, (r.vol_produk_e * pk.presentase_a) * pk.price_a as nilai_komposisi_semen, (r.vol_produk_e * pk.presentase_b) as vol_komposisi_pasir, (r.vol_produk_e * pk.presentase_b) * pk.price_b as nilai_komposisi_pasir, (r.vol_produk_e * pk.presentase_c) as vol_komposisi_batu1020, (r.vol_produk_e * pk.presentase_c) * pk.price_c as nilai_komposisi_batu1020, (r.vol_produk_e * pk.presentase_d) as vol_komposisi_batu2030, (r.vol_produk_e * pk.presentase_d) * pk.price_d as nilai_komposisi_batu2030, (r.vol_produk_e * pk.presentase_e) as vol_komposisi_additive, (r.vol_produk_e * pk.presentase_e) * pk.price_e as nilai_komposisi_additive')
			->from('rak r')
			->join('pmm_agregat pk', 'r.komposisi_300 = pk.id','left')
			->where("r.tanggal_rencana_kerja between '$date_juni24_awal' and '$date_juni24_akhir'")
			->get()->result_array();

			$total_volume_semen_300_1 = 0;
			$total_nilai_semen_300_1 = 0;

			foreach ($komposisi_300_1 as $x){
				$total_volume_semen_300_1 = $x['vol_komposisi_semen'];
				$total_nilai_semen_300_1 = $x['nilai_komposisi_semen'];
				$total_volume_pasir_300_1 = $x['vol_komposisi_pasir'];
				$total_nilai_pasir_300_1 = $x['nilai_komposisi_pasir'];
				$total_volume_batu1020_300_1 = $x['vol_komposisi_batu1020'];
				$total_nilai_batu1020_300_1 = $x['nilai_komposisi_batu1020'];
				$total_volume_batu2030_300_1 = $x['vol_komposisi_batu2030'];
				$total_nilai_batu2030_300_1 = $x['nilai_komposisi_batu2030'];
				$total_volume_additive_300_1 = $x['vol_komposisi_additive'];
				$total_nilai_additive_300_1 = $x['nilai_komposisi_additive'];
			}

			$komposisi_300_2 = $this->db->select('(r.vol_produk_e * pk.presentase_a) as vol_komposisi_semen, (r.vol_produk_e * pk.presentase_a) * pk.price_a as nilai_komposisi_semen, (r.vol_produk_e * pk.presentase_b) as vol_komposisi_pasir, (r.vol_produk_e * pk.presentase_b) * pk.price_b as nilai_komposisi_pasir, (r.vol_produk_e * pk.presentase_c) as vol_komposisi_batu1020, (r.vol_produk_e * pk.presentase_c) * pk.price_c as nilai_komposisi_batu1020, (r.vol_produk_e * pk.presentase_d) as vol_komposisi_batu2030, (r.vol_produk_e * pk.presentase_d) * pk.price_d as nilai_komposisi_batu2030, (r.vol_produk_e * pk.presentase_e) as vol_komposisi_additive, (r.vol_produk_e * pk.presentase_e) * pk.price_e as nilai_komposisi_additive')
			->from('rak r')
			->join('pmm_agregat pk', 'r.komposisi_300 = pk.id','left')
			->where("r.tanggal_rencana_kerja between '$date_juli24_awal' and '$date_juli24_akhir'")
			->get()->result_array();

			$total_volume_semen_300_2 = 0;
			$total_nilai_semen_300_2 = 0;

			foreach ($komposisi_300_2 as $x){
				$total_volume_semen_300_2 = $x['vol_komposisi_semen'];
				$total_nilai_semen_300_2 = $x['nilai_komposisi_semen'];
				$total_volume_pasir_300_2 = $x['vol_komposisi_pasir'];
				$total_nilai_pasir_300_2 = $x['nilai_komposisi_pasir'];
				$total_volume_batu1020_300_2 = $x['vol_komposisi_batu1020'];
				$total_nilai_batu1020_300_2 = $x['nilai_komposisi_batu1020'];
				$total_volume_batu2030_300_2 = $x['vol_komposisi_batu2030'];
				$total_nilai_batu2030_300_2 = $x['nilai_komposisi_batu2030'];
				$total_volume_additive_300_2 = $x['vol_komposisi_additive'];
				$total_nilai_additive_300_2 = $x['nilai_komposisi_additive'];
			}

			$komposisi_300_3 = $this->db->select('(r.vol_produk_e * pk.presentase_a) as vol_komposisi_semen, (r.vol_produk_e * pk.presentase_a) * pk.price_a as nilai_komposisi_semen, (r.vol_produk_e * pk.presentase_b) as vol_komposisi_pasir, (r.vol_produk_e * pk.presentase_b) * pk.price_b as nilai_komposisi_pasir, (r.vol_produk_e * pk.presentase_c) as vol_komposisi_batu1020, (r.vol_produk_e * pk.presentase_c) * pk.price_c as nilai_komposisi_batu1020, (r.vol_produk_e * pk.presentase_d) as vol_komposisi_batu2030, (r.vol_produk_e * pk.presentase_d) * pk.price_d as nilai_komposisi_batu2030, (r.vol_produk_e * pk.presentase_e) as vol_komposisi_additive, (r.vol_produk_e * pk.presentase_e) * pk.price_e as nilai_komposisi_additive')
			->from('rak r')
			->join('pmm_agregat pk', 'r.komposisi_300 = pk.id','left')
			->where("r.tanggal_rencana_kerja between '$date_agustus24_awal' and '$date_agustus24_akhir'")
			->get()->result_array();

			$total_volume_semen_300_3 = 0;
			$total_nilai_semen_300_3 = 0;

			foreach ($komposisi_300_3 as $x){
				$total_volume_semen_300_3 = $x['vol_komposisi_semen'];
				$total_nilai_semen_300_3 = $x['nilai_komposisi_semen'];
				$total_volume_pasir_300_3 = $x['vol_komposisi_pasir'];
				$total_nilai_pasir_300_3 = $x['nilai_komposisi_pasir'];
				$total_volume_batu1020_300_3 = $x['vol_komposisi_batu1020'];
				$total_nilai_batu1020_300_3 = $x['nilai_komposisi_batu1020'];
				$total_volume_batu2030_300_3 = $x['vol_komposisi_batu2030'];
				$total_nilai_batu2030_300_3 = $x['nilai_komposisi_batu2030'];
				$total_volume_additive_300_3 = $x['vol_komposisi_additive'];
				$total_nilai_additive_300_3 = $x['nilai_komposisi_additive'];
			}

			$komposisi_300_4 = $this->db->select('(r.vol_produk_e * pk.presentase_a) as vol_komposisi_semen, (r.vol_produk_e * pk.presentase_a) * pk.price_a as nilai_komposisi_semen, (r.vol_produk_e * pk.presentase_b) as vol_komposisi_pasir, (r.vol_produk_e * pk.presentase_b) * pk.price_b as nilai_komposisi_pasir, (r.vol_produk_e * pk.presentase_c) as vol_komposisi_batu1020, (r.vol_produk_e * pk.presentase_c) * pk.price_c as nilai_komposisi_batu1020, (r.vol_produk_e * pk.presentase_d) as vol_komposisi_batu2030, (r.vol_produk_e * pk.presentase_d) * pk.price_d as nilai_komposisi_batu2030, (r.vol_produk_e * pk.presentase_e) as vol_komposisi_additive, (r.vol_produk_e * pk.presentase_e) * pk.price_e as nilai_komposisi_additive')
			->from('rak r')
			->join('pmm_agregat pk', 'r.komposisi_300 = pk.id','left')
			->where("r.tanggal_rencana_kerja between '$date_september24_awal' and '$date_september24_akhir'")
			->get()->result_array();

			$total_volume_semen_300_4 = 0;
			$total_nilai_semen_300_4 = 0;

			foreach ($komposisi_300_4 as $x){
				$total_volume_semen_300_4 = $x['vol_komposisi_semen'];
				$total_nilai_semen_300_4 = $x['nilai_komposisi_semen'];
				$total_volume_pasir_300_4 = $x['vol_komposisi_pasir'];
				$total_nilai_pasir_300_4 = $x['nilai_komposisi_pasir'];
				$total_volume_batu1020_300_4 = $x['vol_komposisi_batu1020'];
				$total_nilai_batu1020_300_4 = $x['nilai_komposisi_batu1020'];
				$total_volume_batu2030_300_4 = $x['vol_komposisi_batu2030'];
				$total_nilai_batu2030_300_4 = $x['nilai_komposisi_batu2030'];
				$total_volume_additive_300_4 = $x['vol_komposisi_additive'];
				$total_nilai_additive_300_4 = $x['nilai_komposisi_additive'];
			}

			$komposisi_300_5 = $this->db->select('(r.vol_produk_e * pk.presentase_a) as vol_komposisi_semen, (r.vol_produk_e * pk.presentase_a) * pk.price_a as nilai_komposisi_semen, (r.vol_produk_e * pk.presentase_b) as vol_komposisi_pasir, (r.vol_produk_e * pk.presentase_b) * pk.price_b as nilai_komposisi_pasir, (r.vol_produk_e * pk.presentase_c) as vol_komposisi_batu1020, (r.vol_produk_e * pk.presentase_c) * pk.price_c as nilai_komposisi_batu1020, (r.vol_produk_e * pk.presentase_d) as vol_komposisi_batu2030, (r.vol_produk_e * pk.presentase_d) * pk.price_d as nilai_komposisi_batu2030, (r.vol_produk_e * pk.presentase_e) as vol_komposisi_additive, (r.vol_produk_e * pk.presentase_e) * pk.price_e as nilai_komposisi_additive')
			->from('rak r')
			->join('pmm_agregat pk', 'r.komposisi_300 = pk.id','left')
			->where("r.tanggal_rencana_kerja between '$date_oktober24_awal' and '$date_oktober24_akhir'")
			->get()->result_array();

			$total_volume_semen_300_5 = 0;
			$total_nilai_semen_300_5 = 0;

			foreach ($komposisi_300_5 as $x){
				$total_volume_semen_300_5 = $x['vol_komposisi_semen'];
				$total_nilai_semen_300_5 = $x['nilai_komposisi_semen'];
				$total_volume_pasir_300_5 = $x['vol_komposisi_pasir'];
				$total_nilai_pasir_300_5 = $x['nilai_komposisi_pasir'];
				$total_volume_batu1020_300_5 = $x['vol_komposisi_batu1020'];
				$total_nilai_batu1020_300_5 = $x['nilai_komposisi_batu1020'];
				$total_volume_batu2030_300_5 = $x['vol_komposisi_batu2030'];
				$total_nilai_batu2030_300_5 = $x['nilai_komposisi_batu2030'];
				$total_volume_additive_300_5 = $x['vol_komposisi_additive'];
				$total_nilai_additive_300_5 = $x['nilai_komposisi_additive'];
			}

			$komposisi_300_6 = $this->db->select('(r.vol_produk_e * pk.presentase_a) as vol_komposisi_semen, (r.vol_produk_e * pk.presentase_a) * pk.price_a as nilai_komposisi_semen, (r.vol_produk_e * pk.presentase_b) as vol_komposisi_pasir, (r.vol_produk_e * pk.presentase_b) * pk.price_b as nilai_komposisi_pasir, (r.vol_produk_e * pk.presentase_c) as vol_komposisi_batu1020, (r.vol_produk_e * pk.presentase_c) * pk.price_c as nilai_komposisi_batu1020, (r.vol_produk_e * pk.presentase_d) as vol_komposisi_batu2030, (r.vol_produk_e * pk.presentase_d) * pk.price_d as nilai_komposisi_batu2030, (r.vol_produk_e * pk.presentase_e) as vol_komposisi_additive, (r.vol_produk_e * pk.presentase_e) * pk.price_e as nilai_komposisi_additive')
			->from('rak r')
			->join('pmm_agregat pk', 'r.komposisi_300 = pk.id','left')
			->where("r.tanggal_rencana_kerja between '$date_november24_awal' and '$date_november24_akhir'")
			->get()->result_array();

			$total_volume_semen_300_6 = 0;
			$total_nilai_semen_300_6 = 0;

			foreach ($komposisi_300_6 as $x){
				$total_volume_semen_300_6 = $x['vol_komposisi_semen'];
				$total_nilai_semen_300_6 = $x['nilai_komposisi_semen'];
				$total_volume_pasir_300_6 = $x['vol_komposisi_pasir'];
				$total_nilai_pasir_300_6 = $x['nilai_komposisi_pasir'];
				$total_volume_batu1020_300_6 = $x['vol_komposisi_batu1020'];
				$total_nilai_batu1020_300_6 = $x['nilai_komposisi_batu1020'];
				$total_volume_batu2030_300_6 = $x['vol_komposisi_batu2030'];
				$total_nilai_batu2030_300_6 = $x['nilai_komposisi_batu2030'];
				$total_volume_additive_300_6 = $x['vol_komposisi_additive'];
				$total_nilai_additive_300_6 = $x['nilai_komposisi_additive'];
			}

			$komposisi_300_7 = $this->db->select('(r.vol_produk_e * pk.presentase_a) as vol_komposisi_semen, (r.vol_produk_e * pk.presentase_a) * pk.price_a as nilai_komposisi_semen, (r.vol_produk_e * pk.presentase_b) as vol_komposisi_pasir, (r.vol_produk_e * pk.presentase_b) * pk.price_b as nilai_komposisi_pasir, (r.vol_produk_e * pk.presentase_c) as vol_komposisi_batu1020, (r.vol_produk_e * pk.presentase_c) * pk.price_c as nilai_komposisi_batu1020, (r.vol_produk_e * pk.presentase_d) as vol_komposisi_batu2030, (r.vol_produk_e * pk.presentase_d) * pk.price_d as nilai_komposisi_batu2030, (r.vol_produk_e * pk.presentase_e) as vol_komposisi_additive, (r.vol_produk_e * pk.presentase_e) * pk.price_e as nilai_komposisi_additive')
			->from('rak r')
			->join('pmm_agregat pk', 'r.komposisi_300 = pk.id','left')
			->where("r.tanggal_rencana_kerja between '$date_desember24_awal' and '$date_desember24_akhir'")
			->get()->result_array();

			$total_volume_semen_300_7 = 0;
			$total_nilai_semen_300_7 = 0;

			foreach ($komposisi_300_7 as $x){
				$total_volume_semen_300_7 = $x['vol_komposisi_semen'];
				$total_nilai_semen_300_7 = $x['nilai_komposisi_semen'];
				$total_volume_pasir_300_7 = $x['vol_komposisi_pasir'];
				$total_nilai_pasir_300_7 = $x['nilai_komposisi_pasir'];
				$total_volume_batu1020_300_7 = $x['vol_komposisi_batu1020'];
				$total_nilai_batu1020_300_7 = $x['nilai_komposisi_batu1020'];
				$total_volume_batu2030_300_7 = $x['vol_komposisi_batu2030'];
				$total_nilai_batu2030_300_7 = $x['nilai_komposisi_batu2030'];
				$total_volume_additive_300_7 = $x['vol_komposisi_additive'];
				$total_nilai_additive_300_7 = $x['nilai_komposisi_additive'];
			}

			//BETON K-350 SLUMP 10
			$rak_1_K350 = $this->db->select('*, SUM(vol_produk_f) as vol_produk_f')
			->from('rak')
			->where("tanggal_rencana_kerja between '$date_juni24_awal' and '$date_juni24_akhir'")
			->get()->row_array();
			$rak_1_vol_K350 = $rak_1_K350['vol_produk_f'];
			$rak_1_nilai_K350 = $rak_1_vol_K350 * $rak_1_K350['price_f'];

			$rak_2_K350 = $this->db->select('*, SUM(vol_produk_f) as vol_produk_f')
			->from('rak')
			->where("tanggal_rencana_kerja between '$date_juli24_awal' and '$date_juli24_akhir'")
			->get()->row_array();
			$rak_2_vol_K350 = $rak_2_K350['vol_produk_f'];
			$rak_2_nilai_K350 = $rak_2_vol_K350 * $rak_2_K350['price_f'];

			$rak_3_K350 = $this->db->select('*, SUM(vol_produk_f) as vol_produk_f')
			->from('rak')
			->where("tanggal_rencana_kerja between '$date_agustus24_awal' and '$date_agustus24_akhir'")
			->get()->row_array();
			$rak_3_vol_K350 = $rak_3_K350['vol_produk_f'];
			$rak_3_nilai_K350 = $rak_3_vol_K350 * $rak_3_K350['price_f'];

			$rak_4_K350 = $this->db->select('*, SUM(vol_produk_f) as vol_produk_f')
			->from('rak')
			->where("tanggal_rencana_kerja between '$date_september24_awal' and '$date_september24_akhir'")
			->get()->row_array();
			$rak_4_vol_K350 = $rak_4_K350['vol_produk_f'];
			$rak_4_nilai_K350 = $rak_4_vol_K350 * $rak_4_K350['price_f'];

			$rak_5_K350 = $this->db->select('*, SUM(vol_produk_f) as vol_produk_f')
			->from('rak')
			->where("tanggal_rencana_kerja between '$date_oktober24_awal' and '$date_oktober24_akhir'")
			->get()->row_array();
			$rak_5_vol_K350 = $rak_5_K350['vol_produk_f'];
			$rak_5_nilai_K350 = $rak_5_vol_K350 * $rak_5_K350['price_f'];

			$rak_6_K350 = $this->db->select('*, SUM(vol_produk_f) as vol_produk_f')
			->from('rak')
			->where("tanggal_rencana_kerja between '$date_november24_awal' and '$date_november24_akhir'")
			->get()->row_array();
			$rak_6_vol_K350 = $rak_6_K350['vol_produk_f'];
			$rak_6_nilai_K350 = $rak_6_vol_K350 * $rak_6_K350['price_f'];

			$rak_7_K350 = $this->db->select('*, SUM(vol_produk_f) as vol_produk_f')
			->from('rak')
			->where("tanggal_rencana_kerja between '$date_desember24_awal' and '$date_desember24_akhir'")
			->get()->row_array();
			$rak_7_vol_K350 = $rak_7_K350['vol_produk_f'];
			$rak_7_nilai_K350 = $rak_7_vol_K350 * $rak_7_K350['price_f'];

			$jumlah_vol_K350 = $rak_1_vol_K350 + $rak_2_vol_K350 + $rak_3_vol_K350 + $rak_4_vol_K350 + $rak_5_vol_K350 + $rak_6_vol_K350 + $rak_7_vol_K350;
			$jumlah_nilai_K350 = $rak_1_nilai_K350 + $rak_2_nilai_K350 + $rak_3_nilai_K350 + $rak_4_nilai_K350 + $rak_5_nilai_K350 + $rak_6_nilai_K350 + $rak_7_nilai_K350;
			
			//KOMPOSISI BAHAN K-350 SLUMP 10
			$komposisi_350_1 = $this->db->select('(r.vol_produk_f * pk.presentase_a) as vol_komposisi_semen, (r.vol_produk_f * pk.presentase_a) * pk.price_a as nilai_komposisi_semen, (r.vol_produk_f * pk.presentase_b) as vol_komposisi_pasir, (r.vol_produk_f * pk.presentase_b) * pk.price_b as nilai_komposisi_pasir, (r.vol_produk_f * pk.presentase_c) as vol_komposisi_batu1020, (r.vol_produk_f * pk.presentase_c) * pk.price_c as nilai_komposisi_batu1020, (r.vol_produk_f * pk.presentase_d) as vol_komposisi_batu2030, (r.vol_produk_f * pk.presentase_d) * pk.price_d as nilai_komposisi_batu2030, (r.vol_produk_f * pk.presentase_e) as vol_komposisi_additive, (r.vol_produk_f * pk.presentase_e) * pk.price_e as nilai_komposisi_additive')
			->from('rak r')
			->join('pmm_agregat pk', 'r.komposisi_350 = pk.id','left')
			->where("r.tanggal_rencana_kerja between '$date_juni24_awal' and '$date_juni24_akhir'")
			->get()->result_array();

			$total_volume_semen_350_1 = 0;
			$total_nilai_semen_350_1 = 0;

			foreach ($komposisi_350_1 as $x){
				$total_volume_semen_350_1 = $x['vol_komposisi_semen'];
				$total_nilai_semen_350_1 = $x['nilai_komposisi_semen'];
				$total_volume_pasir_350_1 = $x['vol_komposisi_pasir'];
				$total_nilai_pasir_350_1 = $x['nilai_komposisi_pasir'];
				$total_volume_batu1020_350_1 = $x['vol_komposisi_batu1020'];
				$total_nilai_batu1020_350_1 = $x['nilai_komposisi_batu1020'];
				$total_volume_batu2030_350_1 = $x['vol_komposisi_batu2030'];
				$total_nilai_batu2030_350_1 = $x['nilai_komposisi_batu2030'];
				$total_volume_additive_350_1 = $x['vol_komposisi_additive'];
				$total_nilai_additive_350_1 = $x['nilai_komposisi_additive'];
			}

			$komposisi_350_2 = $this->db->select('(r.vol_produk_f * pk.presentase_a) as vol_komposisi_semen, (r.vol_produk_f * pk.presentase_a) * pk.price_a as nilai_komposisi_semen, (r.vol_produk_f * pk.presentase_b) as vol_komposisi_pasir, (r.vol_produk_f * pk.presentase_b) * pk.price_b as nilai_komposisi_pasir, (r.vol_produk_f * pk.presentase_c) as vol_komposisi_batu1020, (r.vol_produk_f * pk.presentase_c) * pk.price_c as nilai_komposisi_batu1020, (r.vol_produk_f * pk.presentase_d) as vol_komposisi_batu2030, (r.vol_produk_f * pk.presentase_d) * pk.price_d as nilai_komposisi_batu2030, (r.vol_produk_f * pk.presentase_e) as vol_komposisi_additive, (r.vol_produk_f * pk.presentase_e) * pk.price_e as nilai_komposisi_additive')
			->from('rak r')
			->join('pmm_agregat pk', 'r.komposisi_350 = pk.id','left')
			->where("r.tanggal_rencana_kerja between '$date_juli24_awal' and '$date_juli24_akhir'")
			->get()->result_array();

			$total_volume_semen_350_2 = 0;
			$total_nilai_semen_350_2 = 0;

			foreach ($komposisi_350_2 as $x){
				$total_volume_semen_350_2 = $x['vol_komposisi_semen'];
				$total_nilai_semen_350_2 = $x['nilai_komposisi_semen'];
				$total_volume_pasir_350_2 = $x['vol_komposisi_pasir'];
				$total_nilai_pasir_350_2 = $x['nilai_komposisi_pasir'];
				$total_volume_batu1020_350_2 = $x['vol_komposisi_batu1020'];
				$total_nilai_batu1020_350_2 = $x['nilai_komposisi_batu1020'];
				$total_volume_batu2030_350_2 = $x['vol_komposisi_batu2030'];
				$total_nilai_batu2030_350_2 = $x['nilai_komposisi_batu2030'];
				$total_volume_additive_350_2 = $x['vol_komposisi_additive'];
				$total_nilai_additive_350_2 = $x['nilai_komposisi_additive'];
			}

			$komposisi_350_3 = $this->db->select('(r.vol_produk_f * pk.presentase_a) as vol_komposisi_semen, (r.vol_produk_f * pk.presentase_a) * pk.price_a as nilai_komposisi_semen, (r.vol_produk_f * pk.presentase_b) as vol_komposisi_pasir, (r.vol_produk_f * pk.presentase_b) * pk.price_b as nilai_komposisi_pasir, (r.vol_produk_f * pk.presentase_c) as vol_komposisi_batu1020, (r.vol_produk_f * pk.presentase_c) * pk.price_c as nilai_komposisi_batu1020, (r.vol_produk_f * pk.presentase_d) as vol_komposisi_batu2030, (r.vol_produk_f * pk.presentase_d) * pk.price_d as nilai_komposisi_batu2030, (r.vol_produk_f * pk.presentase_e) as vol_komposisi_additive, (r.vol_produk_f * pk.presentase_e) * pk.price_e as nilai_komposisi_additive')
			->from('rak r')
			->join('pmm_agregat pk', 'r.komposisi_350 = pk.id','left')
			->where("r.tanggal_rencana_kerja between '$date_agustus24_awal' and '$date_agustus24_akhir'")
			->get()->result_array();

			$total_volume_semen_350_3 = 0;
			$total_nilai_semen_350_3 = 0;

			foreach ($komposisi_350_3 as $x){
				$total_volume_semen_350_3 = $x['vol_komposisi_semen'];
				$total_nilai_semen_350_3 = $x['nilai_komposisi_semen'];
				$total_volume_pasir_350_3 = $x['vol_komposisi_pasir'];
				$total_nilai_pasir_350_3 = $x['nilai_komposisi_pasir'];
				$total_volume_batu1020_350_3 = $x['vol_komposisi_batu1020'];
				$total_nilai_batu1020_350_3 = $x['nilai_komposisi_batu1020'];
				$total_volume_batu2030_350_3 = $x['vol_komposisi_batu2030'];
				$total_nilai_batu2030_350_3 = $x['nilai_komposisi_batu2030'];
				$total_volume_additive_350_3 = $x['vol_komposisi_additive'];
				$total_nilai_additive_350_3 = $x['nilai_komposisi_additive'];
			}

			$komposisi_350_4 = $this->db->select('(r.vol_produk_f * pk.presentase_a) as vol_komposisi_semen, (r.vol_produk_f * pk.presentase_a) * pk.price_a as nilai_komposisi_semen, (r.vol_produk_f * pk.presentase_b) as vol_komposisi_pasir, (r.vol_produk_f * pk.presentase_b) * pk.price_b as nilai_komposisi_pasir, (r.vol_produk_f * pk.presentase_c) as vol_komposisi_batu1020, (r.vol_produk_f * pk.presentase_c) * pk.price_c as nilai_komposisi_batu1020, (r.vol_produk_f * pk.presentase_d) as vol_komposisi_batu2030, (r.vol_produk_f * pk.presentase_d) * pk.price_d as nilai_komposisi_batu2030, (r.vol_produk_f * pk.presentase_e) as vol_komposisi_additive, (r.vol_produk_f * pk.presentase_e) * pk.price_e as nilai_komposisi_additive')
			->from('rak r')
			->join('pmm_agregat pk', 'r.komposisi_350 = pk.id','left')
			->where("r.tanggal_rencana_kerja between '$date_september24_awal' and '$date_september24_akhir'")
			->get()->result_array();

			$total_volume_semen_350_4 = 0;
			$total_nilai_semen_350_4 = 0;

			foreach ($komposisi_350_4 as $x){
				$total_volume_semen_350_4 = $x['vol_komposisi_semen'];
				$total_nilai_semen_350_4 = $x['nilai_komposisi_semen'];
				$total_volume_pasir_350_4 = $x['vol_komposisi_pasir'];
				$total_nilai_pasir_350_4 = $x['nilai_komposisi_pasir'];
				$total_volume_batu1020_350_4 = $x['vol_komposisi_batu1020'];
				$total_nilai_batu1020_350_4 = $x['nilai_komposisi_batu1020'];
				$total_volume_batu2030_350_4 = $x['vol_komposisi_batu2030'];
				$total_nilai_batu2030_350_4 = $x['nilai_komposisi_batu2030'];
				$total_volume_additive_350_4 = $x['vol_komposisi_additive'];
				$total_nilai_additive_350_4 = $x['nilai_komposisi_additive'];
			}

			$komposisi_350_5 = $this->db->select('(r.vol_produk_f * pk.presentase_a) as vol_komposisi_semen, (r.vol_produk_f * pk.presentase_a) * pk.price_a as nilai_komposisi_semen, (r.vol_produk_f * pk.presentase_b) as vol_komposisi_pasir, (r.vol_produk_f * pk.presentase_b) * pk.price_b as nilai_komposisi_pasir, (r.vol_produk_f * pk.presentase_c) as vol_komposisi_batu1020, (r.vol_produk_f * pk.presentase_c) * pk.price_c as nilai_komposisi_batu1020, (r.vol_produk_f * pk.presentase_d) as vol_komposisi_batu2030, (r.vol_produk_f * pk.presentase_d) * pk.price_d as nilai_komposisi_batu2030, (r.vol_produk_f * pk.presentase_e) as vol_komposisi_additive, (r.vol_produk_f * pk.presentase_e) * pk.price_e as nilai_komposisi_additive')
			->from('rak r')
			->join('pmm_agregat pk', 'r.komposisi_350 = pk.id','left')
			->where("r.tanggal_rencana_kerja between '$date_oktober24_awal' and '$date_oktober24_akhir'")
			->get()->result_array();

			$total_volume_semen_350_5 = 0;
			$total_nilai_semen_350_5 = 0;

			foreach ($komposisi_350_5 as $x){
				$total_volume_semen_350_5 = $x['vol_komposisi_semen'];
				$total_nilai_semen_350_5 = $x['nilai_komposisi_semen'];
				$total_volume_pasir_350_5 = $x['vol_komposisi_pasir'];
				$total_nilai_pasir_350_5 = $x['nilai_komposisi_pasir'];
				$total_volume_batu1020_350_5 = $x['vol_komposisi_batu1020'];
				$total_nilai_batu1020_350_5 = $x['nilai_komposisi_batu1020'];
				$total_volume_batu2030_350_5 = $x['vol_komposisi_batu2030'];
				$total_nilai_batu2030_350_5 = $x['nilai_komposisi_batu2030'];
				$total_volume_additive_350_5 = $x['vol_komposisi_additive'];
				$total_nilai_additive_350_5 = $x['nilai_komposisi_additive'];
			}

			$komposisi_350_6 = $this->db->select('(r.vol_produk_f * pk.presentase_a) as vol_komposisi_semen, (r.vol_produk_f * pk.presentase_a) * pk.price_a as nilai_komposisi_semen, (r.vol_produk_f * pk.presentase_b) as vol_komposisi_pasir, (r.vol_produk_f * pk.presentase_b) * pk.price_b as nilai_komposisi_pasir, (r.vol_produk_f * pk.presentase_c) as vol_komposisi_batu1020, (r.vol_produk_f * pk.presentase_c) * pk.price_c as nilai_komposisi_batu1020, (r.vol_produk_f * pk.presentase_d) as vol_komposisi_batu2030, (r.vol_produk_f * pk.presentase_d) * pk.price_d as nilai_komposisi_batu2030, (r.vol_produk_f * pk.presentase_e) as vol_komposisi_additive, (r.vol_produk_f * pk.presentase_e) * pk.price_e as nilai_komposisi_additive')
			->from('rak r')
			->join('pmm_agregat pk', 'r.komposisi_350 = pk.id','left')
			->where("r.tanggal_rencana_kerja between '$date_november24_awal' and '$date_november24_akhir'")
			->get()->result_array();

			$total_volume_semen_350_6 = 0;
			$total_nilai_semen_350_6 = 0;

			foreach ($komposisi_350_6 as $x){
				$total_volume_semen_350_6 = $x['vol_komposisi_semen'];
				$total_nilai_semen_350_6 = $x['nilai_komposisi_semen'];
				$total_volume_pasir_350_6 = $x['vol_komposisi_pasir'];
				$total_nilai_pasir_350_6 = $x['nilai_komposisi_pasir'];
				$total_volume_batu1020_350_6 = $x['vol_komposisi_batu1020'];
				$total_nilai_batu1020_350_6 = $x['nilai_komposisi_batu1020'];
				$total_volume_batu2030_350_6 = $x['vol_komposisi_batu2030'];
				$total_nilai_batu2030_350_6 = $x['nilai_komposisi_batu2030'];
				$total_volume_additive_350_6 = $x['vol_komposisi_additive'];
				$total_nilai_additive_350_6 = $x['nilai_komposisi_additive'];
			}

			$komposisi_350_7 = $this->db->select('(r.vol_produk_f * pk.presentase_a) as vol_komposisi_semen, (r.vol_produk_f * pk.presentase_a) * pk.price_a as nilai_komposisi_semen, (r.vol_produk_f * pk.presentase_b) as vol_komposisi_pasir, (r.vol_produk_f * pk.presentase_b) * pk.price_b as nilai_komposisi_pasir, (r.vol_produk_f * pk.presentase_c) as vol_komposisi_batu1020, (r.vol_produk_f * pk.presentase_c) * pk.price_c as nilai_komposisi_batu1020, (r.vol_produk_f * pk.presentase_d) as vol_komposisi_batu2030, (r.vol_produk_f * pk.presentase_d) * pk.price_d as nilai_komposisi_batu2030, (r.vol_produk_f * pk.presentase_e) as vol_komposisi_additive, (r.vol_produk_f * pk.presentase_e) * pk.price_e as nilai_komposisi_additive')
			->from('rak r')
			->join('pmm_agregat pk', 'r.komposisi_350 = pk.id','left')
			->where("r.tanggal_rencana_kerja between '$date_desember24_awal' and '$date_desember24_akhir'")
			->get()->result_array();

			$total_volume_semen_350_7 = 0;
			$total_nilai_semen_350_7 = 0;

			foreach ($komposisi_350_7 as $x){
				$total_volume_semen_350_7 = $x['vol_komposisi_semen'];
				$total_nilai_semen_350_7 = $x['nilai_komposisi_semen'];
				$total_volume_pasir_350_7 = $x['vol_komposisi_pasir'];
				$total_nilai_pasir_350_7 = $x['nilai_komposisi_pasir'];
				$total_volume_batu1020_350_7 = $x['vol_komposisi_batu1020'];
				$total_nilai_batu1020_350_7 = $x['nilai_komposisi_batu1020'];
				$total_volume_batu2030_350_7 = $x['vol_komposisi_batu2030'];
				$total_nilai_batu2030_350_7 = $x['nilai_komposisi_batu2030'];
				$total_volume_additive_350_7 = $x['vol_komposisi_additive'];
				$total_nilai_additive_350_7 = $x['nilai_komposisi_additive'];
			}
			?>

			<tr class="table-judul">
				<th width="5%" align="center" rowspan="2" style="vertical-align:middle;">NO.</th>
				<th align="center" rowspan="2" style="vertical-align:middle;">URAIAN</th>
				<th align="center" rowspan="2" style="vertical-align:middle;">HARSAT</th>
				<th align="center" rowspan="2" style="vertical-align:middle;">SATUAN</th>
				<!--<th align="center" colspan="2" style="text-transform:uppercase;">JUNI 2024</th>
				<th align="center" colspan="2" style="text-transform:uppercase;">JULI 2024</th>
				<th align="center" colspan="2" style="text-transform:uppercase;">AGUSTUS 2024</th>
				<th align="center" colspan="2" style="text-transform:uppercase;">SEPTEMBER 2024</th>
				<th align="center" colspan="2" style="text-transform:uppercase;">OKTOBER 2024</th>
				<th align="center" colspan="2" style="text-transform:uppercase;">NOVEMBER 2024</th>
				<th align="center" colspan="2" style="text-transform:uppercase;">DESEMBER 2024</th>-->
				<th align="center" colspan="2" style="text-transform:uppercase;">JANUARI 2025</th>
				<th align="center" colspan="2" style="text-transform:uppercase;">FEBRUARI 2025</th>
				<th align="center" colspan="2" style="text-transform:uppercase;">MARET 2025</th>
				<th align="center" colspan="2" style="text-transform:uppercase;">APRIL 2025</th>
				<th align="center" colspan="2" style="text-transform:uppercase;">MEI 2025</th>
				<th align="center" colspan="2" style="text-transform:uppercase;">JUNI 2025</th>
				<th align="center" colspan="2" style="text-transform:uppercase;">JULI 2025</th>
				<th align="center" colspan="2">JUMLAH</th>
	        </tr>
			<tr class="table-judul">
			<th align="center" ><b>VOL.</b></th>
				<th align="center" ><b>NILAI</b></th>
				<th align="center" ><b>VOL.</b></th>
				<th align="center" ><b>NILAI</b></th>
				<th align="center" ><b>VOL.</b></th>
				<th align="center" ><b>NILAI</b></th>
				<th align="center" ><b>VOL.</b></th>
				<th align="center" ><b>NILAI</b></th>
				<th align="center" ><b>VOL.</b></th>
				<th align="center" ><b>NILAI</b></th>
				<th align="center" ><b>VOL.</b></th>
				<th align="center" ><b>NILAI</b></th>
				<th align="center" ><b>VOL.</b></th>
				<th align="center" ><b>NILAI</b></th>
				<th align="center" ><b>VOL.</b></th>
				<th align="center" ><b>NILAI</b></th>
			</tr>
			<tr class="table-baris">
				<th align="left" colspan="20"><b>A. PENDAPATAN USAHA</b></th>
			</tr>
			<tr class="table-baris">
				<th align="center">1.</th>
				<th align="left">Beton K 125 (10±2)</th>
				<th align="right"><?php echo number_format(1127140,0,',','.');?></th>
				<th align="center">M3</th>
				<th align="right"><?php echo number_format($rak_1_vol_K125,2,',','.');?></th>
				<th align="right"><?php echo number_format($rak_1_nilai_K125,0,',','.');?></th>
				<th align="right"><?php echo number_format($rak_2_vol_K125,2,',','.');?></th>
				<th align="right"><?php echo number_format($rak_2_nilai_K125,0,',','.');?></th>
				<th align="right"><?php echo number_format($rak_3_vol_K125,2,',','.');?></th>
				<th align="right"><?php echo number_format($rak_3_nilai_K125,0,',','.');?></th>
				<th align="right"><?php echo number_format($rak_4_vol_K125,2,',','.');?></th>
				<th align="right"><?php echo number_format($rak_4_nilai_K125,0,',','.');?></th>
				<th align="right"><?php echo number_format($rak_5_vol_K125,2,',','.');?></th>
				<th align="right"><?php echo number_format($rak_5_nilai_K125,0,',','.');?></th>
				<th align="right"><?php echo number_format($rak_6_vol_K125,2,',','.');?></th>
				<th align="right"><?php echo number_format($rak_6_nilai_K125,0,',','.');?></th>
				<th align="right"><?php echo number_format($rak_7_vol_K125,2,',','.');?></th>
				<th align="right"><?php echo number_format($rak_7_nilai_K125,0,',','.');?></th>
				<th align="right"><?php echo number_format($jumlah_vol_K125,2,',','.');?></th>
				<th align="right"><?php echo number_format($jumlah_nilai_K125,0,',','.');?></th>
			</tr>
			<tr class="table-baris">
				<th align="center">2.</th>
				<th align="left">Beton K 175 (10±2)</th>
				<th align="right"><?php echo number_format(1250000,0,',','.');?></th>
				<th align="center">M3</th>
				<th align="right"><?php echo number_format($rak_1_vol_K175,2,',','.');?></th>
				<th align="right"><?php echo number_format($rak_1_nilai_K175,0,',','.');?></th>
				<th align="right"><?php echo number_format($rak_2_vol_K175,2,',','.');?></th>
				<th align="right"><?php echo number_format($rak_2_nilai_K175,0,',','.');?></th>
				<th align="right"><?php echo number_format($rak_3_vol_K175,2,',','.');?></th>
				<th align="right"><?php echo number_format($rak_3_nilai_K175,0,',','.');?></th>
				<th align="right"><?php echo number_format($rak_4_vol_K175,2,',','.');?></th>
				<th align="right"><?php echo number_format($rak_4_nilai_K175,0,',','.');?></th>
				<th align="right"><?php echo number_format($rak_5_vol_K175,2,',','.');?></th>
				<th align="right"><?php echo number_format($rak_5_nilai_K175,0,',','.');?></th>
				<th align="right"><?php echo number_format($rak_6_vol_K175,2,',','.');?></th>
				<th align="right"><?php echo number_format($rak_6_nilai_K175,0,',','.');?></th>
				<th align="right"><?php echo number_format($rak_7_vol_K175,2,',','.');?></th>
				<th align="right"><?php echo number_format($rak_7_nilai_K175,0,',','.');?></th>
				<th align="right"><?php echo number_format($jumlah_vol_K175,2,',','.');?></th>
				<th align="right"><?php echo number_format($jumlah_nilai_K175,0,',','.');?></th>
			</tr>
			<tr class="table-baris">
				<th align="center">3.</th>
				<th align="left">Beton K 225 (10±2)</th>
				<th align="right"><?php echo number_format(1065000,0,',','.');?></th>
				<th align="center">M3</th>
				<th align="right"><?php echo number_format($rak_1_vol_K225,2,',','.');?></th>
				<th align="right"><?php echo number_format($rak_1_nilai_K225,0,',','.');?></th>
				<th align="right"><?php echo number_format($rak_2_vol_K225,2,',','.');?></th>
				<th align="right"><?php echo number_format($rak_2_nilai_K225,0,',','.');?></th>
				<th align="right"><?php echo number_format($rak_3_vol_K225,2,',','.');?></th>
				<th align="right"><?php echo number_format($rak_3_nilai_K225,0,',','.');?></th>
				<th align="right"><?php echo number_format($rak_4_vol_K225,2,',','.');?></th>
				<th align="right"><?php echo number_format($rak_4_nilai_K225,0,',','.');?></th>
				<th align="right"><?php echo number_format($rak_5_vol_K225,2,',','.');?></th>
				<th align="right"><?php echo number_format($rak_5_nilai_K225,0,',','.');?></th>
				<th align="right"><?php echo number_format($rak_6_vol_K225,2,',','.');?></th>
				<th align="right"><?php echo number_format($rak_6_nilai_K225,0,',','.');?></th>
				<th align="right"><?php echo number_format($rak_7_vol_K225,2,',','.');?></th>
				<th align="right"><?php echo number_format($rak_7_nilai_K225,0,',','.');?></th>
				<th align="right"><?php echo number_format($jumlah_vol_K225,2,',','.');?></th>
				<th align="right"><?php echo number_format($jumlah_nilai_K225,0,',','.');?></th>
			</tr>
			<tr class="table-baris">
				<th align="center">4.</th>
				<th align="left">Beton K 250 (10±2)</th>
				<th align="right"><?php echo number_format(1390000,0,',','.');?></th>
				<th align="center">M3</th>
				<th align="right"><?php echo number_format($rak_1_vol_K250,2,',','.');?></th>
				<th align="right"><?php echo number_format($rak_1_nilai_K250,0,',','.');?></th>
				<th align="right"><?php echo number_format($rak_2_vol_K250,2,',','.');?></th>
				<th align="right"><?php echo number_format($rak_2_nilai_K250,0,',','.');?></th>
				<th align="right"><?php echo number_format($rak_3_vol_K250,2,',','.');?></th>
				<th align="right"><?php echo number_format($rak_3_nilai_K250,0,',','.');?></th>
				<th align="right"><?php echo number_format($rak_4_vol_K250,2,',','.');?></th>
				<th align="right"><?php echo number_format($rak_4_nilai_K250,0,',','.');?></th>
				<th align="right"><?php echo number_format($rak_5_vol_K250,2,',','.');?></th>
				<th align="right"><?php echo number_format($rak_5_nilai_K250,0,',','.');?></th>
				<th align="right"><?php echo number_format($rak_6_vol_K250,2,',','.');?></th>
				<th align="right"><?php echo number_format($rak_6_nilai_K250,0,',','.');?></th>
				<th align="right"><?php echo number_format($rak_7_vol_K250,2,',','.');?></th>
				<th align="right"><?php echo number_format($rak_7_nilai_K250,0,',','.');?></th>
				<th align="right"><?php echo number_format($jumlah_vol_K250,2,',','.');?></th>
				<th align="right"><?php echo number_format($jumlah_nilai_K250,0,',','.');?></th>
			</tr>
			<tr class="table-baris">
				<th align="center">5.</th>
				<th align="left">Beton K 300 (10±2)</th>
				<th align="right"><?php echo number_format(1370000,0,',','.');?></th>
				<th align="center">M3</th>
				<th align="right"><?php echo number_format($rak_1_vol_K300,2,',','.');?></th>
				<th align="right"><?php echo number_format($rak_1_nilai_K300,0,',','.');?></th>
				<th align="right"><?php echo number_format($rak_2_vol_K300,2,',','.');?></th>
				<th align="right"><?php echo number_format($rak_2_nilai_K300,0,',','.');?></th>
				<th align="right"><?php echo number_format($rak_3_vol_K300,2,',','.');?></th>
				<th align="right"><?php echo number_format($rak_3_nilai_K300,0,',','.');?></th>
				<th align="right"><?php echo number_format($rak_4_vol_K300,2,',','.');?></th>
				<th align="right"><?php echo number_format($rak_4_nilai_K300,0,',','.');?></th>
				<th align="right"><?php echo number_format($rak_5_vol_K300,2,',','.');?></th>
				<th align="right"><?php echo number_format($rak_5_nilai_K300,0,',','.');?></th>
				<th align="right"><?php echo number_format($rak_6_vol_K300,2,',','.');?></th>
				<th align="right"><?php echo number_format($rak_6_nilai_K300,0,',','.');?></th>
				<th align="right"><?php echo number_format($rak_7_vol_K300,2,',','.');?></th>
				<th align="right"><?php echo number_format($rak_7_nilai_K300,0,',','.');?></th>
				<th align="right"><?php echo number_format($jumlah_vol_K300,2,',','.');?></th>
				<th align="right"><?php echo number_format($jumlah_nilai_K300,0,',','.');?></th>
			</tr>
			<tr class="table-baris">
				<th align="center">6.</th>
				<th align="left">Beton K 350 (10±2)</th>
				<th align="right"><?php echo number_format(1455000,0,',','.');?></th>
				<th align="center">M3</th>
				<th align="right"><?php echo number_format($rak_1_vol_K350,2,',','.');?></th>
				<th align="right"><?php echo number_format($rak_1_nilai_K350,0,',','.');?></th>
				<th align="right"><?php echo number_format($rak_2_vol_K350,2,',','.');?></th>
				<th align="right"><?php echo number_format($rak_2_nilai_K350,0,',','.');?></th>
				<th align="right"><?php echo number_format($rak_3_vol_K350,2,',','.');?></th>
				<th align="right"><?php echo number_format($rak_3_nilai_K350,0,',','.');?></th>
				<th align="right"><?php echo number_format($rak_4_vol_K350,2,',','.');?></th>
				<th align="right"><?php echo number_format($rak_4_nilai_K350,0,',','.');?></th>
				<th align="right"><?php echo number_format($rak_5_vol_K350,2,',','.');?></th>
				<th align="right"><?php echo number_format($rak_5_nilai_K350,0,',','.');?></th>
				<th align="right"><?php echo number_format($rak_6_vol_K350,2,',','.');?></th>
				<th align="right"><?php echo number_format($rak_6_nilai_K350,0,',','.');?></th>
				<th align="right"><?php echo number_format($rak_7_vol_K350,2,',','.');?></th>
				<th align="right"><?php echo number_format($rak_7_nilai_K350,0,',','.');?></th>
				<th align="right"><?php echo number_format($jumlah_vol_K350,2,',','.');?></th>
				<th align="right"><?php echo number_format($jumlah_nilai_K350,0,',','.');?></th>
			</tr>
			<?php
			$jumlah_1 = $rak_1_vol_K125 + $rak_1_vol_K175 + $rak_1_vol_K225 + $rak_1_vol_K250 + $rak_1_vol_K300 + $rak_1_vol_K350;
			$jumlah_2 = $rak_1_nilai_K125 + $rak_1_nilai_K175 + $rak_1_nilai_K225 + $rak_1_nilai_K250 + $rak_1_nilai_K300 + $rak_1_nilai_K350;
			$jumlah_3 = $rak_2_vol_K125 + $rak_2_vol_K175 + $rak_2_vol_K225 + $rak_2_vol_K250 + $rak_2_vol_K300 + $rak_2_vol_K350;
			$jumlah_4 = $rak_2_nilai_K125 + $rak_2_nilai_K175 + $rak_2_nilai_K225 + $rak_2_nilai_K250 + $rak_2_nilai_K300 + $rak_2_nilai_K350;
			$jumlah_5 = $rak_3_vol_K125 + $rak_3_vol_K175 + $rak_3_vol_K225 + $rak_3_vol_K250 + $rak_3_vol_K300 + $rak_3_vol_K350;
			$jumlah_6 = $rak_3_nilai_K125 + $rak_3_nilai_K175 + $rak_3_nilai_K225 + $rak_3_nilai_K250 + $rak_3_nilai_K300 + $rak_3_nilai_K350;
			$jumlah_7 = $rak_4_vol_K125 + $rak_4_vol_K175 + $rak_4_vol_K225 + $rak_4_vol_K250 + $rak_4_vol_K300 + $rak_4_vol_K350;
			$jumlah_8 = $rak_4_nilai_K125 + $rak_4_nilai_K175 + $rak_4_nilai_K225 + $rak_4_nilai_K250 + $rak_4_nilai_K300 + $rak_4_nilai_K350;
			$jumlah_9 = $rak_5_vol_K125 + $rak_5_vol_K175 + $rak_5_vol_K225 + $rak_5_vol_K250 + $rak_5_vol_K300 + $rak_5_vol_K350;
			$jumlah_10 = $rak_5_nilai_K125 + $rak_5_nilai_K175 + $rak_5_nilai_K225 + $rak_5_nilai_K250 + $rak_5_nilai_K300 + $rak_5_nilai_K350;
			$jumlah_11 = $rak_6_vol_K125 + $rak_6_vol_K175 + $rak_6_vol_K225 + $rak_6_vol_K250 + $rak_6_vol_K300 + $rak_6_vol_K350;
			$jumlah_12 = $rak_6_nilai_K125 + $rak_6_nilai_K175 + $rak_6_nilai_K225 + $rak_6_nilai_K250 + $rak_6_nilai_K300 + $rak_6_nilai_K350;
			$jumlah_13 = $rak_7_vol_K125 + $rak_7_vol_K175 + $rak_7_vol_K225 + $rak_7_vol_K250 + $rak_7_vol_K300 + $rak_7_vol_K350;
			$jumlah_14 = $rak_7_nilai_K125 + $rak_7_nilai_K175 + $rak_7_nilai_K225 + $rak_7_nilai_K250 + $rak_7_nilai_K300 + $rak_7_nilai_K350;
			$jumlah_15 = $jumlah_vol_K125 + $jumlah_vol_K175 + $jumlah_vol_K225 + $jumlah_vol_K250 + $jumlah_vol_K300 + $jumlah_vol_K350;
			$jumlah_16 = $jumlah_nilai_K125 + $jumlah_nilai_K175 + $jumlah_nilai_K225 + $jumlah_nilai_K250 + $jumlah_nilai_K300 + $jumlah_nilai_K350;
			?>
			<tr class="table-total">
				<th align="right" colspan="4">JUMLAH</th>
				<th align="right"><?php echo number_format($jumlah_1,2,',','.');?></th>
				<th align="right"><?php echo number_format($jumlah_2,0,',','.');?></th>
				<th align="right"><?php echo number_format($jumlah_3,2,',','.');?></th>
				<th align="right"><?php echo number_format($jumlah_4,0,',','.');?></th>
				<th align="right"><?php echo number_format($jumlah_5,2,',','.');?></th>
				<th align="right"><?php echo number_format($jumlah_6,0,',','.');?></th>
				<th align="right"><?php echo number_format($jumlah_7,2,',','.');?></th>
				<th align="right"><?php echo number_format($jumlah_8,0,',','.');?></th>
				<th align="right"><?php echo number_format($jumlah_9,2,',','.');?></th>
				<th align="right"><?php echo number_format($jumlah_10,0,',','.');?></th>
				<th align="right"><?php echo number_format($jumlah_11,2,',','.');?></th>
				<th align="right"><?php echo number_format($jumlah_12,0,',','.');?></th>
				<th align="right"><?php echo number_format($jumlah_13,2,',','.');?></th>
				<th align="right"><?php echo number_format($jumlah_14,0,',','.');?></th>
				<th align="right"><?php echo number_format($jumlah_15,2,',','.');?></th>
				<th align="right"><?php echo number_format($jumlah_16,0,',','.');?></th>
			</tr>
			<?php
			$total_volume_semen_125 = $total_volume_semen_125_1 + $total_volume_semen_125_2 + $total_volume_semen_125_3 + $total_volume_semen_125_4 + $total_volume_semen_125_5 + $total_volume_semen_125_6 + $total_volume_semen_125_7;
			$total_nilai_semen_125 = $total_nilai_semen_125_1 + $total_nilai_semen_125_2 + $total_nilai_semen_125_3 + $total_nilai_semen_125_4 + $total_nilai_semen_125_5 + $total_nilai_semen_125_6 + $total_nilai_semen_125_7;
			
			//HARSAT RAP
			$harsat_rap_beton = $this->db->select('*')
			->from('pmm_agregat')
			->order_by('id','desc')->limit(1)
			->get()->row_array();
			$harsat_rap_beton_semen = $harsat_rap_beton['price_a'];
			$harsat_rap_beton_pasir = $harsat_rap_beton['price_b'];
			$harsat_rap_beton_batu1020 = $harsat_rap_beton['price_c'];
			$harsat_rap_beton_batu2030 = $harsat_rap_beton['price_d'];
			$harsat_rap_beton_additive = $harsat_rap_beton['price_e'];
				
			$total_volume_pasir_125 = $total_volume_pasir_125_1 + $total_volume_pasir_125_2 + $total_volume_pasir_125_3 + $total_volume_pasir_125_4 + $total_volume_pasir_125_5 + $total_volume_pasir_125_6 + $total_volume_pasir_125_7;
			$total_nilai_pasir_125 = $total_nilai_pasir_125_1 + $total_nilai_pasir_125_2 + $total_nilai_pasir_125_3 + $total_nilai_pasir_125_4 + $total_nilai_pasir_125_5 + $total_nilai_pasir_125_6 + $total_nilai_pasir_125_7;
			
			$total_volume_batu1020_125 = $total_volume_batu1020_125_1 + $total_volume_batu1020_125_2 + $total_volume_batu1020_125_3 + $total_volume_batu1020_125_4 + $total_volume_batu1020_125_5 + $total_volume_batu1020_125_6 + $total_volume_batu1020_125_7;
			$total_nilai_batu1020_125 = $total_nilai_batu1020_125_1 + $total_nilai_batu1020_125_2 + $total_nilai_batu1020_125_3 + $total_nilai_batu1020_125_4 + $total_nilai_batu1020_125_5 + $total_nilai_batu1020_125_6 + $total_nilai_batu1020_125_7;
			
			$total_volume_batu2030_125 = $total_volume_batu2030_125_1 + $total_volume_batu2030_125_2 + $total_volume_batu2030_125_3 + $total_volume_batu2030_125_4 + $total_volume_batu2030_125_5 + $total_volume_batu2030_125_6 + $total_volume_batu2030_125_7;
			$total_nilai_batu2030_125 = $total_nilai_batu2030_125_1 + $total_nilai_batu2030_125_2 + $total_nilai_batu2030_125_3 + $total_nilai_batu2030_125_4 + $total_nilai_batu2030_125_5 + $total_nilai_batu2030_125_6 + $total_nilai_batu2030_125_7;
			
			$total_volume_additive_125 = $total_volume_additive_125_1 + $total_volume_additive_125_2 + $total_volume_additive_125_3 + $total_volume_additive_125_4 + $total_volume_additive_125_5 + $total_volume_additive_125_6 + $total_volume_additive_125_7;
			$total_nilai_additive_125 = $total_nilai_additive_125_1 + $total_nilai_additive_125_2 + $total_nilai_additive_125_3 + $total_nilai_additive_125_4 + $total_nilai_additive_125_5 + $total_nilai_additive_125_6 + $total_nilai_additive_125_7;
			
			$jumlah_bahan_1 = $total_nilai_semen_125_1 + $total_nilai_pasir_125_1 + $total_nilai_batu1020_125_1 + $total_nilai_batu2030_125_1 + $total_nilai_additive_125_1;
			$jumlah_bahan_2 = $total_nilai_semen_125_2 + $total_nilai_pasir_125_2 + $total_nilai_batu1020_125_2 + $total_nilai_batu2030_125_2 + $total_nilai_additive_125_2;
			$jumlah_bahan_3 = $total_nilai_semen_125_3 + $total_nilai_pasir_125_3 + $total_nilai_batu1020_125_3 + $total_nilai_batu2030_125_3 + $total_nilai_additive_125_3;
			$jumlah_bahan_4 = $total_nilai_semen_125_4 + $total_nilai_pasir_125_4 + $total_nilai_batu1020_125_4 + $total_nilai_batu2030_125_4 + $total_nilai_additive_125_4;
			$jumlah_bahan_5 = $total_nilai_semen_125_5 + $total_nilai_pasir_125_5 + $total_nilai_batu1020_125_5 + $total_nilai_batu2030_125_5 + $total_nilai_additive_125_5;
			$jumlah_bahan_6 = $total_nilai_semen_125_6 + $total_nilai_pasir_125_6 + $total_nilai_batu1020_125_6 + $total_nilai_batu2030_125_6 + $total_nilai_additive_125_6;
			$jumlah_bahan_7 = $total_nilai_semen_125_7 + $total_nilai_pasir_125_7 + $total_nilai_batu1020_125_7 + $total_nilai_batu2030_125_7 + $total_nilai_additive_125_7;
			$jumlah_bahan_125 = $total_nilai_semen_125 + $total_nilai_pasir_125 + $total_nilai_batu1020_125 + $total_nilai_batu2030_125 + $total_nilai_additive_125;
			
			$total_volume_semen_175 = $total_volume_semen_175_1 + $total_volume_semen_175_2 + $total_volume_semen_175_3 + $total_volume_semen_175_4 + $total_volume_semen_175_5 + $total_volume_semen_175_6 + $total_volume_semen_175_7;
			$total_nilai_semen_175 = $total_nilai_semen_175_1 + $total_nilai_semen_175_2 + $total_nilai_semen_175_3 + $total_nilai_semen_175_4 + $total_nilai_semen_175_5 + $total_nilai_semen_175_6 + $total_nilai_semen_175_7;
			
			$total_volume_pasir_175 = $total_volume_pasir_175_1 + $total_volume_pasir_175_2 + $total_volume_pasir_175_3 + $total_volume_pasir_175_4 + $total_volume_pasir_175_5 + $total_volume_pasir_175_6 + $total_volume_pasir_175_7;
			$total_nilai_pasir_175 = $total_nilai_pasir_175_1 + $total_nilai_pasir_175_2 + $total_nilai_pasir_175_3 + $total_nilai_pasir_175_4 + $total_nilai_pasir_175_5 + $total_nilai_pasir_175_6 + $total_nilai_pasir_175_7;
			
			$total_volume_batu1020_175 = $total_volume_batu1020_175_1 + $total_volume_batu1020_175_2 + $total_volume_batu1020_175_3 + $total_volume_batu1020_175_4 + $total_volume_batu1020_175_5 + $total_volume_batu1020_175_6 + $total_volume_batu1020_175_7;
			$total_nilai_batu1020_175 = $total_nilai_batu1020_175_1 + $total_nilai_batu1020_175_2 + $total_nilai_batu1020_175_3 + $total_nilai_batu1020_175_4 + $total_nilai_batu1020_175_5 + $total_nilai_batu1020_175_6 + $total_nilai_batu1020_175_7;
			
			$total_volume_batu2030_175 = $total_volume_batu2030_175_1 + $total_volume_batu2030_175_2 + $total_volume_batu2030_175_3 + $total_volume_batu2030_175_4 + $total_volume_batu2030_175_5 + $total_volume_batu2030_175_6 + $total_volume_batu2030_175_7;
			$total_nilai_batu2030_175 = $total_nilai_batu2030_175_1 + $total_nilai_batu2030_175_2 + $total_nilai_batu2030_175_3 + $total_nilai_batu2030_175_4 + $total_nilai_batu2030_175_5 + $total_nilai_batu2030_175_6 + $total_nilai_batu2030_175_7;
			
			$total_volume_additive_175 = $total_volume_additive_175_1 + $total_volume_additive_175_2 + $total_volume_additive_175_3 + $total_volume_additive_175_4 + $total_volume_additive_175_5 + $total_volume_additive_175_6 + $total_volume_additive_175_7;
			$total_nilai_additive_175 = $total_nilai_additive_175_1 + $total_nilai_additive_175_2 + $total_nilai_additive_175_3 + $total_nilai_additive_175_4 + $total_nilai_additive_175_5 + $total_nilai_additive_175_6 + $total_nilai_additive_175_7;
			
			$jumlah_bahan2_1 = $total_nilai_semen_175_1 + $total_nilai_pasir_175_1 + $total_nilai_batu1020_175_1 + $total_nilai_batu2030_175_1 + $total_nilai_additive_175_1;
			$jumlah_bahan2_2 = $total_nilai_semen_175_2 + $total_nilai_pasir_175_2 + $total_nilai_batu1020_175_2 + $total_nilai_batu2030_175_2 + $total_nilai_additive_175_2;
			$jumlah_bahan2_3 = $total_nilai_semen_175_3 + $total_nilai_pasir_175_3 + $total_nilai_batu1020_175_3 + $total_nilai_batu2030_175_3 + $total_nilai_additive_175_3;
			$jumlah_bahan2_4 = $total_nilai_semen_175_4 + $total_nilai_pasir_175_4 + $total_nilai_batu1020_175_4 + $total_nilai_batu2030_175_4 + $total_nilai_additive_175_4;
			$jumlah_bahan2_5 = $total_nilai_semen_175_5 + $total_nilai_pasir_175_5 + $total_nilai_batu1020_175_5 + $total_nilai_batu2030_175_5 + $total_nilai_additive_175_5;
			$jumlah_bahan2_6 = $total_nilai_semen_175_6 + $total_nilai_pasir_175_6 + $total_nilai_batu1020_175_6 + $total_nilai_batu2030_175_6 + $total_nilai_additive_175_6;
			$jumlah_bahan2_7 = $total_nilai_semen_175_7 + $total_nilai_pasir_175_7 + $total_nilai_batu1020_175_7 + $total_nilai_batu2030_175_7 + $total_nilai_additive_175_7;
			$jumlah_bahan_175 = $total_nilai_semen_175 + $total_nilai_pasir_175 + $total_nilai_batu1020_175 + $total_nilai_batu2030_175 + $total_nilai_additive_175;
			
			$total_volume_semen_225 = $total_volume_semen_225_1 + $total_volume_semen_225_2 + $total_volume_semen_225_3 + $total_volume_semen_225_4 + $total_volume_semen_225_5 + $total_volume_semen_225_6 + $total_volume_semen_225_7;
			$total_nilai_semen_225 = $total_nilai_semen_225_1 + $total_nilai_semen_225_2 + $total_nilai_semen_225_3 + $total_nilai_semen_225_4 + $total_nilai_semen_225_5 + $total_nilai_semen_225_6 + $total_nilai_semen_225_7;
			
			$total_volume_pasir_225 = $total_volume_pasir_225_1 + $total_volume_pasir_225_2 + $total_volume_pasir_225_3 + $total_volume_pasir_225_4 + $total_volume_pasir_225_5 + $total_volume_pasir_225_6 + $total_volume_pasir_225_7;
			$total_nilai_pasir_225 = $total_nilai_pasir_225_1 + $total_nilai_pasir_225_2 + $total_nilai_pasir_225_3 + $total_nilai_pasir_225_4 + $total_nilai_pasir_225_5 + $total_nilai_pasir_225_6 + $total_nilai_pasir_225_5 + $total_nilai_pasir_225_7;
			
			$total_volume_batu1020_225 = $total_volume_batu1020_225_1 + $total_volume_batu1020_225_2 + $total_volume_batu1020_225_3 + $total_volume_batu1020_225_4 + $total_volume_batu1020_225_5 + $total_volume_batu1020_225_6 + $total_volume_batu1020_225_7;
			$total_nilai_batu1020_225 = $total_nilai_batu1020_225_1 + $total_nilai_batu1020_225_2 + $total_nilai_batu1020_225_3 + $total_nilai_batu1020_225_4 + $total_nilai_batu1020_225_5 + $total_nilai_batu1020_225_6 + $total_nilai_batu1020_225_7;
			
			$total_volume_batu2030_225 = $total_volume_batu2030_225_1 + $total_volume_batu2030_225_2 + $total_volume_batu2030_225_3 + $total_volume_batu2030_225_4 + $total_volume_batu2030_225_5 + $total_volume_batu2030_225_6 + $total_volume_batu2030_225_7;
			$total_nilai_batu2030_225 = $total_nilai_batu2030_225_1 + $total_nilai_batu2030_225_2 + $total_nilai_batu2030_225_3 + $total_nilai_batu2030_225_4 + $total_nilai_batu2030_225_5 + $total_nilai_batu2030_225_6 + $total_nilai_batu2030_225_7;
			
			$total_volume_additive_225 = $total_volume_additive_225_1 + $total_volume_additive_225_2 + $total_volume_additive_225_3 + $total_volume_additive_225_4 + $total_volume_additive_225_5 + $total_volume_additive_225_6 + $total_volume_additive_225_7;
			$total_nilai_additive_225 = $total_nilai_additive_225_1 + $total_nilai_additive_225_2 + $total_nilai_additive_225_3 + $total_nilai_additive_225_4 + $total_nilai_additive_225_5 + $total_nilai_additive_225_6 + $total_nilai_additive_225_7;
			
			$jumlah_bahan3_1 = $total_nilai_semen_225_1 + $total_nilai_pasir_225_1 + $total_nilai_batu1020_225_1 + $total_nilai_batu2030_225_1 + $total_nilai_additive_225_1;
			$jumlah_bahan3_2 = $total_nilai_semen_225_2 + $total_nilai_pasir_225_2 + $total_nilai_batu1020_225_2 + $total_nilai_batu2030_225_2 + $total_nilai_additive_225_2;
			$jumlah_bahan3_3 = $total_nilai_semen_225_3 + $total_nilai_pasir_225_3 + $total_nilai_batu1020_225_3 + $total_nilai_batu2030_225_3 + $total_nilai_additive_225_3;
			$jumlah_bahan3_4 = $total_nilai_semen_225_4 + $total_nilai_pasir_225_4 + $total_nilai_batu1020_225_4 + $total_nilai_batu2030_225_4 + $total_nilai_additive_225_4;
			$jumlah_bahan3_5 = $total_nilai_semen_225_5 + $total_nilai_pasir_225_5 + $total_nilai_batu1020_225_5 + $total_nilai_batu2030_225_5 + $total_nilai_additive_225_5;
			$jumlah_bahan3_6 = $total_nilai_semen_225_6 + $total_nilai_pasir_225_6 + $total_nilai_batu1020_225_6 + $total_nilai_batu2030_225_6 + $total_nilai_additive_225_6;
			$jumlah_bahan3_7 = $total_nilai_semen_225_7 + $total_nilai_pasir_225_7 + $total_nilai_batu1020_225_7 + $total_nilai_batu2030_225_7 + $total_nilai_additive_225_7;
			$jumlah_bahan_225 = $total_nilai_semen_225 + $total_nilai_pasir_225 + $total_nilai_batu1020_225 + $total_nilai_batu2030_225 + $total_nilai_additive_225;
			
			$total_volume_semen_250 = $total_volume_semen_250_1 + $total_volume_semen_250_2 + $total_volume_semen_250_3 + $total_volume_semen_250_4 + $total_volume_semen_250_5 + $total_volume_semen_250_6 + $total_volume_semen_250_7;
			$total_nilai_semen_250 = $total_nilai_semen_250_1 + $total_nilai_semen_250_2 + $total_nilai_semen_250_3 + $total_nilai_semen_250_4 + $total_nilai_semen_250_5 + $total_nilai_semen_250_6 + $total_nilai_semen_250_7;
			
			$total_volume_pasir_250 = $total_volume_pasir_250_1 + $total_volume_pasir_250_2 + $total_volume_pasir_250_3 + $total_volume_pasir_250_4 + $total_volume_pasir_250_5 + $total_volume_pasir_250_6 + $total_volume_pasir_250_7;
			$total_nilai_pasir_250 = $total_nilai_pasir_250_1 + $total_nilai_pasir_250_2 + $total_nilai_pasir_250_3 + $total_nilai_pasir_250_4 + $total_nilai_pasir_250_5 + $total_nilai_pasir_250_6 + $total_nilai_pasir_250_7;
			
			$total_volume_batu1020_250 = $total_volume_batu1020_250_1 + $total_volume_batu1020_250_2 + $total_volume_batu1020_250_3 + $total_volume_batu1020_250_4 + $total_volume_batu1020_250_5 + $total_volume_batu1020_250_6 + $total_volume_batu1020_250_7;
			$total_nilai_batu1020_250 = $total_nilai_batu1020_250_1 + $total_nilai_batu1020_250_2 + $total_nilai_batu1020_250_3 + $total_nilai_batu1020_250_4 + $total_nilai_batu1020_250_5 + $total_nilai_batu1020_250_6 + $total_nilai_batu1020_250_7;
			
			$total_volume_batu2030_250 = $total_volume_batu2030_250_1 + $total_volume_batu2030_250_2 + $total_volume_batu2030_250_3 + $total_volume_batu2030_250_4 + $total_volume_batu2030_250_5 + $total_volume_batu2030_250_6 + $total_volume_batu2030_250_7;
			$total_nilai_batu2030_250 = $total_nilai_batu2030_250_1 + $total_nilai_batu2030_250_2 + $total_nilai_batu2030_250_3 + $total_nilai_batu2030_250_4 + $total_nilai_batu2030_250_5 + $total_nilai_batu2030_250_6 + $total_nilai_batu2030_250_7;
			
			$total_volume_additive_250 = $total_volume_additive_250_1 + $total_volume_additive_250_2 + $total_volume_additive_250_3 + $total_volume_additive_250_4 + $total_volume_additive_250_5 + $total_volume_additive_250_6 + $total_volume_additive_250_7;
			$total_nilai_additive_250 = $total_nilai_additive_250_1 + $total_nilai_additive_250_2 + $total_nilai_additive_250_3 + $total_nilai_additive_250_4 + $total_nilai_additive_250_5 + $total_nilai_additive_250_6 + $total_nilai_additive_250_7;
			
			$jumlah_bahan4_1 = $total_nilai_semen_250_1 + $total_nilai_pasir_250_1 + $total_nilai_batu1020_250_1 + $total_nilai_batu2030_250_1 + $total_nilai_additive_250_1;
			$jumlah_bahan4_2 = $total_nilai_semen_250_2 + $total_nilai_pasir_250_2 + $total_nilai_batu1020_250_2 + $total_nilai_batu2030_250_2 + $total_nilai_additive_250_2;
			$jumlah_bahan4_3 = $total_nilai_semen_250_3 + $total_nilai_pasir_250_3 + $total_nilai_batu1020_250_3 + $total_nilai_batu2030_250_3 + $total_nilai_additive_250_3;
			$jumlah_bahan4_4 = $total_nilai_semen_250_4 + $total_nilai_pasir_250_4 + $total_nilai_batu1020_250_4 + $total_nilai_batu2030_250_4 + $total_nilai_additive_250_4;
			$jumlah_bahan4_5 = $total_nilai_semen_250_5 + $total_nilai_pasir_250_5 + $total_nilai_batu1020_250_5 + $total_nilai_batu2030_250_5 + $total_nilai_additive_250_5;
			$jumlah_bahan4_6 = $total_nilai_semen_250_6 + $total_nilai_pasir_250_6 + $total_nilai_batu1020_250_6 + $total_nilai_batu2030_250_6 + $total_nilai_additive_250_6;
			$jumlah_bahan4_7 = $total_nilai_semen_250_7 + $total_nilai_pasir_250_7 + $total_nilai_batu1020_250_7 + $total_nilai_batu2030_250_7 + $total_nilai_additive_250_7;
			$jumlah_bahan_250 = $total_nilai_semen_250 + $total_nilai_pasir_250 + $total_nilai_batu1020_250 + $total_nilai_batu2030_250 + $total_nilai_additive_250;
			
			$total_volume_semen_300 = $total_volume_semen_300_1 + $total_volume_semen_300_2 + $total_volume_semen_300_3 + $total_volume_semen_300_4 + $total_volume_semen_300_5 + $total_volume_semen_300_6 + $total_volume_semen_300_7;
			$total_nilai_semen_300 = $total_nilai_semen_300_1 + $total_nilai_semen_300_2 + $total_nilai_semen_300_3 + $total_nilai_semen_300_4 + $total_nilai_semen_300_5 + $total_nilai_semen_300_6 + $total_nilai_semen_300_7;
			
			$total_volume_pasir_300 = $total_volume_pasir_300_1 + $total_volume_pasir_300_2 + $total_volume_pasir_300_3 + $total_volume_pasir_300_4 + $total_volume_pasir_300_5 + $total_volume_pasir_300_6 + $total_volume_pasir_300_7;
			$total_nilai_pasir_300 = $total_nilai_pasir_300_1 + $total_nilai_pasir_300_2 + $total_nilai_pasir_300_3 + $total_nilai_pasir_300_4 + $total_nilai_pasir_300_5 + $total_nilai_pasir_300_6 + $total_nilai_pasir_300_7;
			
			$total_volume_batu1020_300 = $total_volume_batu1020_300_1 + $total_volume_batu1020_300_2 + $total_volume_batu1020_300_3 + $total_volume_batu1020_300_4 + $total_volume_batu1020_300_5 + $total_volume_batu1020_300_6 + $total_volume_batu1020_300_7;
			$total_nilai_batu1020_300 = $total_nilai_batu1020_300_1 + $total_nilai_batu1020_300_2 + $total_nilai_batu1020_300_3 + $total_nilai_batu1020_300_4 + $total_nilai_batu1020_300_5 + $total_nilai_batu1020_300_6 + $total_nilai_batu1020_300_7;
			
			$total_volume_batu2030_300 = $total_volume_batu2030_300_1 + $total_volume_batu2030_300_2 + $total_volume_batu2030_300_3 + $total_volume_batu2030_300_4 + $total_volume_batu2030_300_5 + $total_volume_batu2030_300_6 + $total_volume_batu2030_300_7;
			$total_nilai_batu2030_300 = $total_nilai_batu2030_300_1 + $total_nilai_batu2030_300_2 + $total_nilai_batu2030_300_3 + $total_nilai_batu2030_300_4 + $total_nilai_batu2030_300_5 + $total_nilai_batu2030_300_6 + $total_nilai_batu2030_300_7;
			
			$total_volume_additive_300 = $total_volume_additive_300_1 + $total_volume_additive_300_2 + $total_volume_additive_300_3 + $total_volume_additive_300_4 + $total_volume_additive_300_5 + $total_volume_additive_300_6 + $total_volume_additive_300_7;
			$total_nilai_additive_300 = $total_nilai_additive_300_1 + $total_nilai_additive_300_2 + $total_nilai_additive_300_3 + $total_nilai_additive_300_4 + $total_nilai_additive_300_5 + $total_nilai_additive_300_6 + $total_nilai_additive_300_7;
			
			$jumlah_bahan5_1 = $total_nilai_semen_300_1 + $total_nilai_pasir_300_1 + $total_nilai_batu1020_300_1 + $total_nilai_batu2030_300_1 + $total_nilai_additive_300_1;
			$jumlah_bahan5_2 = $total_nilai_semen_300_2 + $total_nilai_pasir_300_2 + $total_nilai_batu1020_300_2 + $total_nilai_batu2030_300_2 + $total_nilai_additive_300_2;
			$jumlah_bahan5_3 = $total_nilai_semen_300_3 + $total_nilai_pasir_300_3 + $total_nilai_batu1020_300_3 + $total_nilai_batu2030_300_3 + $total_nilai_additive_300_3;
			$jumlah_bahan5_4 = $total_nilai_semen_300_4 + $total_nilai_pasir_300_4 + $total_nilai_batu1020_300_4 + $total_nilai_batu2030_300_4 + $total_nilai_additive_300_4;
			$jumlah_bahan5_5 = $total_nilai_semen_300_5 + $total_nilai_pasir_300_5 + $total_nilai_batu1020_300_5 + $total_nilai_batu2030_300_5 + $total_nilai_additive_300_5;
			$jumlah_bahan5_6 = $total_nilai_semen_300_6 + $total_nilai_pasir_300_6 + $total_nilai_batu1020_300_6 + $total_nilai_batu2030_300_6 + $total_nilai_additive_300_6;
			$jumlah_bahan5_7 = $total_nilai_semen_300_7 + $total_nilai_pasir_300_7 + $total_nilai_batu1020_300_7 + $total_nilai_batu2030_300_7 + $total_nilai_additive_300_7;
			$jumlah_bahan_300 = $total_nilai_semen_300 + $total_nilai_pasir_300 + $total_nilai_batu1020_300 + $total_nilai_batu2030_300 + $total_nilai_additive_300;
			
			$total_volume_semen_350 = $total_volume_semen_350_1 + $total_volume_semen_350_2 + $total_volume_semen_350_3 + $total_volume_semen_350_4 + $total_volume_semen_350_5 + $total_volume_semen_350_6 + $total_volume_semen_350_7;
			$total_nilai_semen_350 = $total_nilai_semen_350_1 + $total_nilai_semen_350_2 + $total_nilai_semen_350_3 + $total_nilai_semen_350_4 + $total_nilai_semen_350_5 + $total_nilai_semen_350_6 + $total_nilai_semen_350_7;
			
			$total_volume_pasir_350 = $total_volume_pasir_350_1 + $total_volume_pasir_350_2 + $total_volume_pasir_350_3 + $total_volume_pasir_350_4 + $total_volume_pasir_350_5 + $total_volume_pasir_350_6 + $total_volume_pasir_350_7;
			$total_nilai_pasir_350 = $total_nilai_pasir_350_1 + $total_nilai_pasir_350_2 + $total_nilai_pasir_350_3 + $total_nilai_pasir_350_4 + $total_nilai_pasir_350_5 + $total_nilai_pasir_350_6 + $total_nilai_pasir_350_7;
			
			$total_volume_batu1020_350 = $total_volume_batu1020_350_1 + $total_volume_batu1020_350_2 + $total_volume_batu1020_350_3 + $total_volume_batu1020_350_4 + $total_volume_batu1020_350_5 + $total_volume_batu1020_350_6 + $total_volume_batu1020_350_7;
			$total_nilai_batu1020_350 = $total_nilai_batu1020_350_1 + $total_nilai_batu1020_350_2 + $total_nilai_batu1020_350_3 + $total_nilai_batu1020_350_4 + $total_nilai_batu1020_350_5 + $total_nilai_batu1020_350_6 + $total_nilai_batu1020_350_7;
			
			$total_volume_batu2030_350 = $total_volume_batu2030_350_1 + $total_volume_batu2030_350_2 + $total_volume_batu2030_350_3 + $total_volume_batu2030_350_4 + $total_volume_batu2030_350_5 + $total_volume_batu2030_350_6 + $total_volume_batu2030_350_7;
			$total_nilai_batu2030_350 = $total_nilai_batu2030_350_1 + $total_nilai_batu2030_350_2 + $total_nilai_batu2030_350_3 + $total_nilai_batu2030_350_4 + $total_nilai_batu2030_350_5 + $total_nilai_batu2030_350_6 + $total_nilai_batu2030_350_7;
			
			$total_volume_additive_350 = $total_volume_additive_350_1 + $total_volume_additive_350_2 + $total_volume_additive_350_3 + $total_volume_additive_350_4 + $total_volume_additive_350_5 + $total_volume_additive_350_6 + $total_volume_additive_350_7;
			$total_nilai_additive_350 = $total_nilai_additive_350_1 + $total_nilai_additive_350_2 + $total_nilai_additive_350_3 + $total_nilai_additive_350_4 + $total_nilai_additive_350_5 + $total_nilai_additive_350_6 + $total_nilai_additive_350_7;
			
			$jumlah_bahan6_1 = $total_nilai_semen_350_1 + $total_nilai_pasir_350_1 + $total_nilai_batu1020_350_1 + $total_nilai_batu2030_350_1 + $total_nilai_additive_350_1;
			$jumlah_bahan6_2 = $total_nilai_semen_350_2 + $total_nilai_pasir_350_2 + $total_nilai_batu1020_350_2 + $total_nilai_batu2030_350_2 + $total_nilai_additive_350_2;
			$jumlah_bahan6_3 = $total_nilai_semen_350_3 + $total_nilai_pasir_350_3 + $total_nilai_batu1020_350_3 + $total_nilai_batu2030_350_3 + $total_nilai_additive_350_3;
			$jumlah_bahan6_4 = $total_nilai_semen_350_4 + $total_nilai_pasir_350_4 + $total_nilai_batu1020_350_4 + $total_nilai_batu2030_350_4 + $total_nilai_additive_350_4;
			$jumlah_bahan6_5 = $total_nilai_semen_350_5 + $total_nilai_pasir_350_5 + $total_nilai_batu1020_350_5 + $total_nilai_batu2030_350_5 + $total_nilai_additive_350_5;
			$jumlah_bahan6_6 = $total_nilai_semen_350_6 + $total_nilai_pasir_350_6 + $total_nilai_batu1020_350_6 + $total_nilai_batu2030_350_6 + $total_nilai_additive_350_6;
			$jumlah_bahan6_7 = $total_nilai_semen_350_7 + $total_nilai_pasir_350_7 + $total_nilai_batu1020_350_7 + $total_nilai_batu2030_350_7 + $total_nilai_additive_350_7;
			$jumlah_bahan_350 = $total_nilai_semen_350 + $total_nilai_pasir_350 + $total_nilai_batu1020_350 + $total_nilai_batu2030_350 + $total_nilai_additive_350;
			?>
			<tr class="table-baris">
				<th align="left" colspan="120"><b>B. TOTAL KEBUTUHAN BAHAN</b></th>
			</tr>
			<tr class="table-baris">
				<?php
				$realisasi_1 = $this->db->select('*')
				->from('rak')
				->where("tanggal_rencana_kerja between '$date_juni24_awal' and '$date_juni24_akhir'")
				->get()->row_array();
				$vol_realisasi_semen_1 = $realisasi_1['vol_realisasi_a'];
				$nilai_realisasi_semen_1 = $realisasi_1['nilai_realisasi_a'];
				$total_volume_semen_300_1_all = (($total_volume_semen_125_1 + $total_volume_semen_175_1 + $total_volume_semen_225_1 + $total_volume_semen_250_1 + $total_volume_semen_300_1 + $total_volume_semen_350_1) * $realisasi_1['realisasi']) + $vol_realisasi_semen_1;
				$total_nilai_semen_300_1_all = (($total_nilai_semen_125_1 + $total_nilai_semen_175_1 + $total_nilai_semen_225_1 + $total_nilai_semen_250_1 + $total_nilai_semen_300_1 + $total_nilai_semen_350_1) * $realisasi_1['realisasi']) + $nilai_realisasi_semen_1;
				
				$vol_realisasi_pasir_1 = $realisasi_1['vol_realisasi_b'];
				$nilai_realisasi_pasir_1 = $realisasi_1['nilai_realisasi_b'];
				$total_volume_pasir_300_1_all = (($total_volume_pasir_125_1 + $total_volume_pasir_175_1 + $total_volume_pasir_225_1 + $total_volume_pasir_250_1 + $total_volume_pasir_300_1 + $total_volume_pasir_350_1) * $realisasi_1['realisasi']) + $vol_realisasi_pasir_1;
				$total_nilai_pasir_300_1_all = (($total_nilai_pasir_125_1 + $total_nilai_pasir_175_1 + $total_nilai_pasir_225_1 + $total_nilai_pasir_250_1 + $total_nilai_pasir_300_1 + $total_nilai_pasir_350_1) * $realisasi_1['realisasi']) + $nilai_realisasi_pasir_1;

				$vol_realisasi_batu1020_1 = $realisasi_1['vol_realisasi_c'];
				$nilai_realisasi_batu1020_1 = $realisasi_1['nilai_realisasi_c'];
				$total_volume_batu1020_300_1_all = (($total_volume_batu1020_125_1 + $total_volume_batu1020_175_1 + $total_volume_batu1020_225_1 + $total_volume_batu1020_250_1 + $total_volume_batu1020_300_1 + $total_volume_batu1020_350_1) * $realisasi_1['realisasi']) + $vol_realisasi_batu1020_1;
				$total_nilai_batu1020_300_1_all = (($total_nilai_batu1020_125_1 + $total_nilai_batu1020_175_1 + $total_nilai_batu1020_225_1 + $total_nilai_batu1020_250_1 + $total_nilai_batu1020_300_1 + $total_nilai_batu1020_350_1) * $realisasi_1['realisasi']) + $nilai_realisasi_batu1020_1;

				$vol_realisasi_batu2030_1 = $realisasi_1['vol_realisasi_d'];
				$nilai_realisasi_batu2030_1 = $realisasi_1['nilai_realisasi_d'];
				$total_volume_batu2030_300_1_all = (($total_volume_batu2030_125_1 + $total_volume_batu2030_175_1 + $total_volume_batu2030_225_1 + $total_volume_batu2030_250_1 + $total_volume_batu2030_300_1 + $total_volume_batu2030_350_1) * $realisasi_1['realisasi']) + $vol_realisasi_batu2030_1;
				$total_nilai_batu2030_300_1_all = (($total_nilai_batu2030_125_1 + $total_nilai_batu2030_175_1 + $total_nilai_batu2030_225_1 + $total_nilai_batu2030_250_1 + $total_nilai_batu2030_300_1 + $total_nilai_batu2030_350_1) * $realisasi_1['realisasi']) + $nilai_realisasi_batu2030_1;

				$vol_realisasi_additive_1 = $realisasi_1['vol_realisasi_e'];
				$nilai_realisasi_additive_1 = $realisasi_1['nilai_realisasi_e'];
				$total_volume_additive_300_1_all = (($total_volume_additive_125_1 + $total_volume_additive_175_1 + $total_volume_additive_225_1 + $total_volume_additive_250_1 + $total_volume_additive_300_1 + $total_volume_additive_350_1) * $realisasi_1['realisasi']) + $vol_realisasi_additive_1;
				$total_nilai_additive_300_1_all = (($total_nilai_additive_125_1 + $total_nilai_additive_175_1 + $total_nilai_additive_225_1 + $total_nilai_additive_250_1 + $total_nilai_additive_300_1 + $total_nilai_additive_350_1) * $realisasi_1['realisasi']) + $nilai_realisasi_additive_1;

				$realisasi_2 = $this->db->select('*')
				->from('rak')
				->where("tanggal_rencana_kerja between '$date_juli24_awal' and '$date_juli24_akhir'")
				->get()->row_array();
				$vol_realisasi_semen_2 = $realisasi_2['vol_realisasi_a'];
				$nilai_realisasi_semen_2 = $realisasi_2['nilai_realisasi_a'];
				$total_volume_semen_300_2_all = (($total_volume_semen_125_2 + $total_volume_semen_175_2 + $total_volume_semen_225_2 + $total_volume_semen_250_2 + $total_volume_semen_300_2 + $total_volume_semen_350_2) * $realisasi_2['realisasi']) + $vol_realisasi_semen_2;
				$total_nilai_semen_300_2_all = (($total_nilai_semen_125_2 + $total_nilai_semen_175_2 + $total_nilai_semen_225_2 + $total_nilai_semen_250_2 + $total_nilai_semen_300_2 + $total_nilai_semen_350_2) * $realisasi_2['realisasi']) + $nilai_realisasi_semen_2;

				$vol_realisasi_pasir_2 = $realisasi_2['vol_realisasi_b'];
				$nilai_realisasi_pasir_2 = $realisasi_2['nilai_realisasi_b'];
				$total_volume_pasir_300_2_all = (($total_volume_pasir_125_2 + $total_volume_pasir_175_2 + $total_volume_pasir_225_2 + $total_volume_pasir_250_2 + $total_volume_pasir_300_2 + $total_volume_pasir_350_2) * $realisasi_2['realisasi']) + $vol_realisasi_pasir_2;
				$total_nilai_pasir_300_2_all = (($total_nilai_pasir_125_2 + $total_nilai_pasir_175_2 + $total_nilai_pasir_225_2 + $total_nilai_pasir_250_2 + $total_nilai_pasir_300_2 + $total_nilai_pasir_350_2) * $realisasi_2['realisasi']) + $nilai_realisasi_pasir_2;

				$vol_realisasi_batu1020_2 = $realisasi_2['vol_realisasi_c'];
				$nilai_realisasi_batu1020_2 = $realisasi_2['nilai_realisasi_c'];
				$total_volume_batu1020_300_2_all = (($total_volume_batu1020_125_2 + $total_volume_batu1020_175_2 + $total_volume_batu1020_225_2 + $total_volume_batu1020_250_2 + $total_volume_batu1020_300_2 + $total_volume_batu1020_350_2) * $realisasi_2['realisasi']) + $vol_realisasi_batu1020_2;
				$total_nilai_batu1020_300_2_all = (($total_nilai_batu1020_125_2 + $total_nilai_batu1020_175_2 + $total_nilai_batu1020_225_2 + $total_nilai_batu1020_250_2 + $total_nilai_batu1020_300_2 + $total_nilai_batu1020_350_2) * $realisasi_2['realisasi']) + $nilai_realisasi_batu1020_2;

				$vol_realisasi_batu2030_2 = $realisasi_2['vol_realisasi_d'];
				$nilai_realisasi_batu2030_2 = $realisasi_2['nilai_realisasi_d'];
				$total_volume_batu2030_300_2_all = (($total_volume_batu2030_125_2 + $total_volume_batu2030_175_2 + $total_volume_batu2030_225_2 + $total_volume_batu2030_250_2 + $total_volume_batu2030_300_2 + $total_volume_batu2030_350_2) * $realisasi_2['realisasi']) + $vol_realisasi_batu2030_2;
				$total_nilai_batu2030_300_2_all = (($total_nilai_batu2030_125_2 + $total_nilai_batu2030_175_2 + $total_nilai_batu2030_225_2 + $total_nilai_batu2030_250_2 + $total_nilai_batu2030_300_2 + $total_nilai_batu2030_350_2) * $realisasi_2['realisasi']) + $nilai_realisasi_batu2030_2;

				$vol_realisasi_additive_2 = $realisasi_2['vol_realisasi_e'];
				$nilai_realisasi_additive_2 = $realisasi_2['nilai_realisasi_e'];
				$total_volume_additive_300_2_all = (($total_volume_additive_125_2 + $total_volume_additive_175_2 + $total_volume_additive_225_2 + $total_volume_additive_250_2 + $total_volume_additive_300_2 + $total_volume_additive_350_2) * $realisasi_2['realisasi']) + $vol_realisasi_additive_2;
				$total_nilai_additive_300_2_all = (($total_nilai_additive_125_2 + $total_nilai_additive_175_2 + $total_nilai_additive_225_2 + $total_nilai_additive_250_2 + $total_nilai_additive_300_2 + $total_nilai_additive_350_2) * $realisasi_2['realisasi']) + $nilai_realisasi_additive_2;
		
				$realisasi_3 = $this->db->select('*')
				->from('rak')
				->where("tanggal_rencana_kerja between '$date_agustus24_awal' and '$date_agustus24_akhir'")
				->get()->row_array();
				$vol_realisasi_semen_3 = $realisasi_3['vol_realisasi_a'];
				$nilai_realisasi_semen_3 = $realisasi_3['nilai_realisasi_a'];
				$total_volume_semen_300_3_all = (($total_volume_semen_125_3 + $total_volume_semen_175_3 + $total_volume_semen_225_3 + $total_volume_semen_250_3 + $total_volume_semen_300_3 + $total_volume_semen_350_3) * $realisasi_3['realisasi']) + $vol_realisasi_semen_3;
				$total_nilai_semen_300_3_all = (($total_nilai_semen_125_3 + $total_nilai_semen_175_3 + $total_nilai_semen_225_3 + $total_nilai_semen_250_3 + $total_nilai_semen_300_3 + $total_nilai_semen_350_3) * $realisasi_3['realisasi']) + $nilai_realisasi_semen_3;

				$vol_realisasi_pasir_3 = $realisasi_3['vol_realisasi_b'];
				$nilai_realisasi_pasir_3 = $realisasi_3['nilai_realisasi_b'];
				$total_volume_pasir_300_3_all = (($total_volume_pasir_125_3 + $total_volume_pasir_175_3 + $total_volume_pasir_225_3 + $total_volume_pasir_250_3 + $total_volume_pasir_300_3 + $total_volume_pasir_350_3) * $realisasi_3['realisasi']) + $vol_realisasi_pasir_3;
				$total_nilai_pasir_300_3_all = (($total_nilai_pasir_125_3 + $total_nilai_pasir_175_3 + $total_nilai_pasir_225_3 + $total_nilai_pasir_250_3 + $total_nilai_pasir_300_3 + $total_nilai_pasir_350_3) * $realisasi_3['realisasi']) + $nilai_realisasi_pasir_3;

				$vol_realisasi_batu1020_3 = $realisasi_3['vol_realisasi_c'];
				$nilai_realisasi_batu1020_3 = $realisasi_3['nilai_realisasi_c'];
				$total_volume_batu1020_300_3_all = (($total_volume_batu1020_125_3 + $total_volume_batu1020_175_3 + $total_volume_batu1020_225_3 + $total_volume_batu1020_250_3 + $total_volume_batu1020_300_3 + $total_volume_batu1020_350_3) * $realisasi_3['realisasi']) + $vol_realisasi_batu1020_3;
				$total_nilai_batu1020_300_3_all = (($total_nilai_batu1020_125_3 + $total_nilai_batu1020_175_3 + $total_nilai_batu1020_225_3 + $total_nilai_batu1020_250_3 + $total_nilai_batu1020_300_3 + $total_nilai_batu1020_350_3) * $realisasi_3['realisasi']) + $nilai_realisasi_batu1020_3;

				$vol_realisasi_batu2030_3 = $realisasi_3['vol_realisasi_d'];
				$nilai_realisasi_batu2030_3 = $realisasi_3['nilai_realisasi_d'];
				$total_volume_batu2030_300_3_all = (($total_volume_batu2030_125_3 + $total_volume_batu2030_175_3 + $total_volume_batu2030_225_3 + $total_volume_batu2030_250_3 + $total_volume_batu2030_300_3 + $total_volume_batu2030_350_3) * $realisasi_3['realisasi']) + $vol_realisasi_batu2030_3;
				$total_nilai_batu2030_300_3_all = (($total_nilai_batu2030_125_3 + $total_nilai_batu2030_175_3 + $total_nilai_batu2030_225_3 + $total_nilai_batu2030_250_3 + $total_nilai_batu2030_300_3 + $total_nilai_batu2030_350_3) * $realisasi_3['realisasi']) + $nilai_realisasi_batu2030_3;

				$vol_realisasi_additive_3 = $realisasi_3['vol_realisasi_e'];
				$nilai_realisasi_additive_3 = $realisasi_3['nilai_realisasi_e'];
				$total_volume_additive_300_3_all = (($total_volume_additive_125_3 + $total_volume_additive_175_3 + $total_volume_additive_225_3 + $total_volume_additive_250_3 + $total_volume_additive_300_3 + $total_volume_additive_350_3) * $realisasi_3['realisasi']) + $vol_realisasi_additive_3;
				$total_nilai_additive_300_3_all = (($total_nilai_additive_125_3 + $total_nilai_additive_175_3 + $total_nilai_additive_225_3 + $total_nilai_additive_250_3 + $total_nilai_additive_300_3 + $total_nilai_additive_350_3) * $realisasi_3['realisasi']) + $nilai_realisasi_additive_3;

				$realisasi_4 = $this->db->select('*')
				->from('rak')
				->where("tanggal_rencana_kerja between '$date_september24_awal' and '$date_september24_akhir'")
				->get()->row_array();
				$vol_realisasi_semen_4 = $realisasi_4['vol_realisasi_a'];
				$nilai_realisasi_semen_4 = $realisasi_4['nilai_realisasi_a'];
				$total_volume_semen_300_4_all = (($total_volume_semen_125_4 + $total_volume_semen_175_4 + $total_volume_semen_225_4 + $total_volume_semen_250_4 + $total_volume_semen_300_4 + $total_volume_semen_350_4) * $realisasi_4['realisasi']) + $vol_realisasi_semen_4;
				$total_nilai_semen_300_4_all = (($total_nilai_semen_125_4 + $total_nilai_semen_175_4 + $total_nilai_semen_225_4 + $total_nilai_semen_250_4 + $total_nilai_semen_300_4 + $total_nilai_semen_350_4) * $realisasi_4['realisasi']) + $nilai_realisasi_semen_4;

				$vol_realisasi_pasir_4 = $realisasi_4['vol_realisasi_b'];
				$nilai_realisasi_pasir_4 = $realisasi_4['nilai_realisasi_b'];
				$total_volume_pasir_300_4_all = (($total_volume_pasir_125_4 + $total_volume_pasir_175_4 + $total_volume_pasir_225_4 + $total_volume_pasir_250_4 + $total_volume_pasir_300_4 + $total_volume_pasir_350_4) * $realisasi_4['realisasi']) + $vol_realisasi_pasir_4;
				$total_nilai_pasir_300_4_all = (($total_nilai_pasir_125_4 + $total_nilai_pasir_175_4 + $total_nilai_pasir_225_4 + $total_nilai_pasir_250_4 + $total_nilai_pasir_300_4 + $total_nilai_pasir_350_4) * $realisasi_4['realisasi']) + $nilai_realisasi_pasir_4;

				$vol_realisasi_batu1020_4 = $realisasi_4['vol_realisasi_c'];
				$nilai_realisasi_batu1020_4 = $realisasi_4['nilai_realisasi_c'];
				$total_volume_batu1020_300_4_all = (($total_volume_batu1020_125_4 + $total_volume_batu1020_175_4 + $total_volume_batu1020_225_4 + $total_volume_batu1020_250_4 + $total_volume_batu1020_300_4 + $total_volume_batu1020_350_4) * $realisasi_4['realisasi']) + $vol_realisasi_batu1020_4;
				$total_nilai_batu1020_300_4_all = (($total_nilai_batu1020_125_4 + $total_nilai_batu1020_175_4 + $total_nilai_batu1020_225_4 + $total_nilai_batu1020_250_4 + $total_nilai_batu1020_300_4 + $total_nilai_batu1020_350_4) * $realisasi_4['realisasi']) + $nilai_realisasi_batu1020_4;

				$vol_realisasi_batu2030_4 = $realisasi_4['vol_realisasi_d'];
				$nilai_realisasi_batu2030_4 = $realisasi_4['nilai_realisasi_d'];
				$total_volume_batu2030_300_4_all = (($total_volume_batu2030_125_4 + $total_volume_batu2030_175_4 + $total_volume_batu2030_225_4 + $total_volume_batu2030_250_4 + $total_volume_batu2030_300_4 + $total_volume_batu2030_350_4) * $realisasi_4['realisasi']) + $vol_realisasi_batu2030_4;
				$total_nilai_batu2030_300_4_all = (($total_nilai_batu2030_125_4 + $total_nilai_batu2030_175_4 + $total_nilai_batu2030_225_4 + $total_nilai_batu2030_250_4 + $total_nilai_batu2030_300_4 + $total_nilai_batu2030_350_4) * $realisasi_4['realisasi']) + $nilai_realisasi_batu2030_4;

				$vol_realisasi_additive_4 = $realisasi_4['vol_realisasi_e'];
				$nilai_realisasi_additive_4 = $realisasi_4['nilai_realisasi_e'];
				$total_volume_additive_300_4_all = (($total_volume_additive_125_4 + $total_volume_additive_175_4 + $total_volume_additive_225_4 + $total_volume_additive_250_4 + $total_volume_additive_300_4 + $total_volume_additive_350_4) * $realisasi_4['realisasi']) + $vol_realisasi_additive_4;
				$total_nilai_additive_300_4_all = (($total_nilai_additive_125_4 + $total_nilai_additive_175_4 + $total_nilai_additive_225_4 + $total_nilai_additive_250_4 + $total_nilai_additive_300_4 + $total_nilai_additive_350_4) * $realisasi_4['realisasi']) + $nilai_realisasi_additive_4;

				$realisasi_5 = $this->db->select('*')
				->from('rak')
				->where("tanggal_rencana_kerja between '$date_oktober24_awal' and '$date_oktober24_akhir'")
				->get()->row_array();
				$vol_realisasi_semen_5 = $realisasi_5['vol_realisasi_a'];
				$nilai_realisasi_semen_5 = $realisasi_5['nilai_realisasi_a'];
				$total_volume_semen_300_5_all = (($total_volume_semen_125_5 + $total_volume_semen_175_5 + $total_volume_semen_225_5 + $total_volume_semen_250_5 + $total_volume_semen_300_5 + $total_volume_semen_350_5) * $realisasi_5['realisasi']) + $vol_realisasi_semen_5;
				$total_nilai_semen_300_5_all = (($total_nilai_semen_125_5 + $total_nilai_semen_175_5 + $total_nilai_semen_225_5 + $total_nilai_semen_250_5 + $total_nilai_semen_300_5 + $total_nilai_semen_350_5) * $realisasi_5['realisasi']) + $nilai_realisasi_semen_5;

				$vol_realisasi_pasir_5 = $realisasi_5['vol_realisasi_b'];
				$nilai_realisasi_pasir_5 = $realisasi_5['nilai_realisasi_b'];
				$total_volume_pasir_300_5_all = (($total_volume_pasir_125_5 + $total_volume_pasir_175_5 + $total_volume_pasir_225_5 + $total_volume_pasir_250_5 + $total_volume_pasir_300_5 + $total_volume_pasir_350_5) * $realisasi_5['realisasi']) + $vol_realisasi_pasir_5;
				$total_nilai_pasir_300_5_all = (($total_nilai_pasir_125_5 + $total_nilai_pasir_175_5 + $total_nilai_pasir_225_5 + $total_nilai_pasir_250_5 + $total_nilai_pasir_300_5 + $total_nilai_pasir_350_5) * $realisasi_5['realisasi']) + $nilai_realisasi_pasir_5;
				
				$vol_realisasi_batu1020_5 = $realisasi_5['vol_realisasi_c'];
				$nilai_realisasi_batu1020_5 = $realisasi_5['nilai_realisasi_c'];
				$total_volume_batu1020_300_5_all = (($total_volume_batu1020_125_5 + $total_volume_batu1020_175_5 + $total_volume_batu1020_225_5 + $total_volume_batu1020_250_5 + $total_volume_batu1020_300_5 + $total_volume_batu1020_350_5) * $realisasi_5['realisasi']) + $vol_realisasi_batu1020_5;
				$total_nilai_batu1020_300_5_all = (($total_nilai_batu1020_125_5 + $total_nilai_batu1020_175_5 + $total_nilai_batu1020_225_5 + $total_nilai_batu1020_250_5 + $total_nilai_batu1020_300_5 + $total_nilai_batu1020_350_5) * $realisasi_5['realisasi']) + $nilai_realisasi_batu1020_5;

				$vol_realisasi_batu2030_5 = $realisasi_5['vol_realisasi_d'];
				$nilai_realisasi_batu2030_5 = $realisasi_5['nilai_realisasi_d'];
				$total_volume_batu2030_300_5_all = (($total_volume_batu2030_125_5 + $total_volume_batu2030_175_5 + $total_volume_batu2030_225_5 + $total_volume_batu2030_250_5 + $total_volume_batu2030_300_5 + $total_volume_batu2030_350_5) * $realisasi_5['realisasi']) + $vol_realisasi_batu2030_5;
				$total_nilai_batu2030_300_5_all = (($total_nilai_batu2030_125_5 + $total_nilai_batu2030_175_5 + $total_nilai_batu2030_225_5 + $total_nilai_batu2030_250_5 + $total_nilai_batu2030_300_5 + $total_nilai_batu2030_350_5) * $realisasi_5['realisasi']) + $nilai_realisasi_batu2030_5;

				$vol_realisasi_additive_5 = $realisasi_5['vol_realisasi_e'];
				$nilai_realisasi_additive_5 = $realisasi_5['nilai_realisasi_e'];
				$total_volume_additive_300_5_all = (($total_volume_additive_125_5 + $total_volume_additive_175_5 + $total_volume_additive_225_5 + $total_volume_additive_250_5 + $total_volume_additive_300_5 + $total_volume_additive_350_5) * $realisasi_5['realisasi']) + $vol_realisasi_additive_5;
				$total_nilai_additive_300_5_all = (($total_nilai_additive_125_5 + $total_nilai_additive_175_5 + $total_nilai_additive_225_5 + $total_nilai_additive_250_5 + $total_nilai_additive_300_5 + $total_nilai_additive_350_5) * $realisasi_5['realisasi']) + $nilai_realisasi_additive_5;
				
				$realisasi_6 = $this->db->select('*')
				->from('rak')
				->where("tanggal_rencana_kerja between '$date_november24_awal' and '$date_november24_akhir'")
				->get()->row_array();
				$vol_realisasi_semen_6 = $realisasi_6['vol_realisasi_a'];
				$nilai_realisasi_semen_6 = $realisasi_6['nilai_realisasi_a'];
				$total_volume_semen_300_6_all = (($total_volume_semen_125_6 + $total_volume_semen_175_6 + $total_volume_semen_225_6 + $total_volume_semen_250_6 + $total_volume_semen_300_6 + $total_volume_semen_350_6) * $realisasi_6['realisasi']) + $vol_realisasi_semen_6;
				$total_nilai_semen_300_6_all = (($total_nilai_semen_125_6 + $total_nilai_semen_175_6 + $total_nilai_semen_225_6 + $total_nilai_semen_250_6 + $total_nilai_semen_300_6 + $total_nilai_semen_350_6) * $realisasi_6['realisasi']) + $nilai_realisasi_semen_6;

				$vol_realisasi_pasir_6 = $realisasi_6['vol_realisasi_b'];
				$nilai_realisasi_pasir_6 = $realisasi_6['nilai_realisasi_b'];
				$total_volume_pasir_300_6_all = (($total_volume_pasir_125_6 + $total_volume_pasir_175_6 + $total_volume_pasir_225_6 + $total_volume_pasir_250_6 + $total_volume_pasir_300_6 + $total_volume_pasir_350_6) * $realisasi_6['realisasi']) + $vol_realisasi_pasir_6;
				$total_nilai_pasir_300_6_all = (($total_nilai_pasir_125_6 + $total_nilai_pasir_175_6 + $total_nilai_pasir_225_6 + $total_nilai_pasir_250_6 + $total_nilai_pasir_300_6 + $total_nilai_pasir_350_6) * $realisasi_6['realisasi']) + $nilai_realisasi_pasir_6;
				
				$vol_realisasi_batu1020_6 = $realisasi_6['vol_realisasi_c'];
				$nilai_realisasi_batu1020_6 = $realisasi_6['nilai_realisasi_c'];
				$total_volume_batu1020_300_6_all = (($total_volume_batu1020_125_6 + $total_volume_batu1020_175_6 + $total_volume_batu1020_225_6 + $total_volume_batu1020_250_6 + $total_volume_batu1020_300_6 + $total_volume_batu1020_350_6) * $realisasi_6['realisasi']) + $vol_realisasi_batu1020_6;
				$total_nilai_batu1020_300_6_all = (($total_nilai_batu1020_125_6 + $total_nilai_batu1020_175_6 + $total_nilai_batu1020_225_6 + $total_nilai_batu1020_250_6 + $total_nilai_batu1020_300_6 + $total_nilai_batu1020_350_6) * $realisasi_6['realisasi']) + $nilai_realisasi_batu1020_6;

				$vol_realisasi_batu2030_6 = $realisasi_6['vol_realisasi_d'];
				$nilai_realisasi_batu2030_6 = $realisasi_6['nilai_realisasi_d'];
				$total_volume_batu2030_300_6_all = (($total_volume_batu2030_125_6 + $total_volume_batu2030_175_6 + $total_volume_batu2030_225_6 + $total_volume_batu2030_250_6 + $total_volume_batu2030_300_6 + $total_volume_batu2030_350_6) * $realisasi_6['realisasi']) + $vol_realisasi_batu2030_6;
				$total_nilai_batu2030_300_6_all = (($total_nilai_batu2030_125_6 + $total_nilai_batu2030_175_6 + $total_nilai_batu2030_225_6 + $total_nilai_batu2030_250_6 + $total_nilai_batu2030_300_6 + $total_nilai_batu2030_350_6) * $realisasi_6['realisasi']) + $nilai_realisasi_batu2030_6;

				$vol_realisasi_additive_6 = $realisasi_6['vol_realisasi_e'];
				$nilai_realisasi_additive_6 = $realisasi_6['nilai_realisasi_e'];
				$total_volume_additive_300_6_all = (($total_volume_additive_125_6 + $total_volume_additive_175_6 + $total_volume_additive_225_6 + $total_volume_additive_250_6 + $total_volume_additive_300_6 + $total_volume_additive_350_6) * $realisasi_6['realisasi']) + $vol_realisasi_additive_6;
				$total_nilai_additive_300_6_all = (($total_nilai_additive_125_6 + $total_nilai_additive_175_6 + $total_nilai_additive_225_6 + $total_nilai_additive_250_6 + $total_nilai_additive_300_6 + $total_nilai_additive_350_6) * $realisasi_6['realisasi']) + $nilai_realisasi_additive_6;
				
				$realisasi_7 = $this->db->select('*')
				->from('rak')
				->where("tanggal_rencana_kerja between '$date_desember24_awal' and '$date_desember24_akhir'")
				->get()->row_array();
				$vol_realisasi_semen_7 = $realisasi_7['vol_realisasi_a'];
				$nilai_realisasi_semen_7 = $realisasi_7['nilai_realisasi_a'];
				$total_volume_semen_300_7_all = (($total_volume_semen_125_7 + $total_volume_semen_175_7 + $total_volume_semen_225_7 + $total_volume_semen_250_7 + $total_volume_semen_300_7 + $total_volume_semen_350_7) * $realisasi_7['realisasi']) + $vol_realisasi_semen_7;
				$total_nilai_semen_300_7_all = (($total_nilai_semen_125_7 + $total_nilai_semen_175_7 + $total_nilai_semen_225_7 + $total_nilai_semen_250_7 + $total_nilai_semen_300_7 + $total_nilai_semen_350_7) * $realisasi_7['realisasi']) + $nilai_realisasi_semen_7;

				$vol_realisasi_pasir_7 = $realisasi_7['vol_realisasi_b'];
				$nilai_realisasi_pasir_7 = $realisasi_7['nilai_realisasi_b'];
				$total_volume_pasir_300_7_all = (($total_volume_pasir_125_7 + $total_volume_pasir_175_7 + $total_volume_pasir_225_7 + $total_volume_pasir_250_7 + $total_volume_pasir_300_7 + $total_volume_pasir_350_7) * $realisasi_7['realisasi']) + $vol_realisasi_pasir_7;
				$total_nilai_pasir_300_7_all = (($total_nilai_pasir_125_7 + $total_nilai_pasir_175_7 + $total_nilai_pasir_225_7 + $total_nilai_pasir_250_7 + $total_nilai_pasir_300_7 + $total_nilai_pasir_350_7) * $realisasi_7['realisasi']) + $nilai_realisasi_pasir_7;
				
				$vol_realisasi_batu1020_7 = $realisasi_7['vol_realisasi_c'];
				$nilai_realisasi_batu1020_7 = $realisasi_7['nilai_realisasi_c'];
				$total_volume_batu1020_300_7_all = (($total_volume_batu1020_125_7 + $total_volume_batu1020_175_7 + $total_volume_batu1020_225_7 + $total_volume_batu1020_250_7 + $total_volume_batu1020_300_7 + $total_volume_batu1020_350_7) * $realisasi_7['realisasi']) + $vol_realisasi_batu1020_7;
				$total_nilai_batu1020_300_7_all = (($total_nilai_batu1020_125_7 + $total_nilai_batu1020_175_7 + $total_nilai_batu1020_225_7 + $total_nilai_batu1020_250_7 + $total_nilai_batu1020_300_7 + $total_nilai_batu1020_350_7) * $realisasi_7['realisasi']) + $nilai_realisasi_batu1020_7;

				$vol_realisasi_batu2030_7 = $realisasi_7['vol_realisasi_d'];
				$nilai_realisasi_batu2030_7 = $realisasi_7['nilai_realisasi_d'];
				$total_volume_batu2030_300_7_all = (($total_volume_batu2030_125_7 + $total_volume_batu2030_175_7 + $total_volume_batu2030_225_7 + $total_volume_batu2030_250_7 + $total_volume_batu2030_300_7 + $total_volume_batu2030_350_7) * $realisasi_7['realisasi']) + $vol_realisasi_batu2030_7;
				$total_nilai_batu2030_300_7_all = (($total_nilai_batu2030_125_7 + $total_nilai_batu2030_175_7 + $total_nilai_batu2030_225_7 + $total_nilai_batu2030_250_7 + $total_nilai_batu2030_300_7 + $total_nilai_batu2030_350_7) * $realisasi_7['realisasi']) + $nilai_realisasi_batu2030_7;

				$vol_realisasi_additive_7 = $realisasi_7['vol_realisasi_e'];
				$nilai_realisasi_additive_7 = $realisasi_7['nilai_realisasi_e'];
				$total_volume_additive_300_7_all = (($total_volume_additive_125_7 + $total_volume_additive_175_7 + $total_volume_additive_225_7 + $total_volume_additive_250_7 + $total_volume_additive_300_7 + $total_volume_additive_350_7) * $realisasi_7['realisasi']) + $vol_realisasi_additive_7;
				$total_nilai_additive_300_7_all = (($total_nilai_additive_125_7 + $total_nilai_additive_175_7 + $total_nilai_additive_225_7 + $total_nilai_additive_250_7 + $total_nilai_additive_300_7 + $total_nilai_additive_350_7) * $realisasi_7['realisasi']) + $nilai_realisasi_additive_7;
				?>
				<th align="center"></th>
				<th align="left">Semen</th>
				<th align="right"><?php echo number_format($harsat_rap_beton_semen,0,',','.');?></th>
				<th align="center">Ton</th>
				<th align="right"><?php echo number_format($total_volume_semen_300_1_all,2,',','.');?></th>
				<th align="right"><?php echo number_format($total_nilai_semen_300_1_all,0,',','.');?></th>
				<th align="right"><?php echo number_format($total_volume_semen_300_2_all,2,',','.');?></th>
				<th align="right"><?php echo number_format($total_nilai_semen_300_2_all,0,',','.');?></th>
				<th align="right"><?php echo number_format($total_volume_semen_300_3_all,2,',','.');?></th>
				<th align="right"><?php echo number_format($total_nilai_semen_300_3_all,0,',','.');?></th>
				<th align="right"><?php echo number_format($total_volume_semen_300_4_all,2,',','.');?></th>
				<th align="right"><?php echo number_format($total_nilai_semen_300_4_all,0,',','.');?></th>
				<th align="right"><?php echo number_format($total_volume_semen_300_5_all,2,',','.');?></th>
				<th align="right"><?php echo number_format($total_nilai_semen_300_5_all,0,',','.');?></th>
				<th align="right"><?php echo number_format($total_volume_semen_300_6_all,2,',','.');?></th>
				<th align="right"><?php echo number_format($total_nilai_semen_300_6_all,0,',','.');?></th>
				<th align="right"><?php echo number_format($total_volume_semen_300_7_all,2,',','.');?></th>
				<th align="right"><?php echo number_format($total_nilai_semen_300_7_all,0,',','.');?></th>
				<?php
				$total_volume_semen_300 = $total_volume_semen_300_1_all + $total_volume_semen_300_2_all + $total_volume_semen_300_3_all + $total_volume_semen_300_4_all + $total_volume_semen_300_5_all + $total_volume_semen_300_6_all + $total_volume_semen_300_7_all;
				$total_nilai_semen_300 = $total_nilai_semen_300_1_all + $total_nilai_semen_300_2_all + $total_nilai_semen_300_3_all + $total_nilai_semen_300_4_all + $total_nilai_semen_300_5_all + $total_nilai_semen_300_6_all + $total_nilai_semen_300_7_all;
				?>
				<th align="right"><?php echo number_format($total_volume_semen_300,2,',','.');?></th>
				<th align="right"><?php echo number_format($total_nilai_semen_300,0,',','.');?></th>
			</tr>
			<tr class="table-baris">
				<th align="center"></th>
				<th align="left">Pasir</th>
				<th align="right"><?php echo number_format($harsat_rap_beton_pasir,0,',','.');?></th>
				<th align="center">M3</th>
				<th align="right"><?php echo number_format($total_volume_pasir_300_1_all,2,',','.');?></th>
				<th align="right"><?php echo number_format($total_nilai_pasir_300_1_all,0,',','.');?></th>
				<th align="right"><?php echo number_format($total_volume_pasir_300_2_all,2,',','.');?></th>
				<th align="right"><?php echo number_format($total_nilai_pasir_300_2_all,0,',','.');?></th>
				<th align="right"><?php echo number_format($total_volume_pasir_300_3_all,2,',','.');?></th>
				<th align="right"><?php echo number_format($total_nilai_pasir_300_3_all,0,',','.');?></th>
				<th align="right"><?php echo number_format($total_volume_pasir_300_4_all,2,',','.');?></th>
				<th align="right"><?php echo number_format($total_nilai_pasir_300_4_all,0,',','.');?></th>
				<th align="right"><?php echo number_format($total_volume_pasir_300_5_all,2,',','.');?></th>
				<th align="right"><?php echo number_format($total_nilai_pasir_300_5_all,0,',','.');?></th>
				<th align="right"><?php echo number_format($total_volume_pasir_300_6_all,2,',','.');?></th>
				<th align="right"><?php echo number_format($total_nilai_pasir_300_6_all,0,',','.');?></th>
				<th align="right"><?php echo number_format($total_volume_pasir_300_7_all,2,',','.');?></th>
				<th align="right"><?php echo number_format($total_nilai_pasir_300_7_all,0,',','.');?></th>
				<?php
				$total_volume_pasir_300 = $total_volume_pasir_300_1_all + $total_volume_pasir_300_2_all + $total_volume_pasir_300_3_all + $total_volume_pasir_300_4_all + $total_volume_pasir_300_5_all + $total_volume_pasir_300_6_all + $total_volume_pasir_300_7_all;
				$total_nilai_pasir_300 = $total_nilai_pasir_300_1_all + $total_nilai_pasir_300_2_all + $total_nilai_pasir_300_3_all + $total_nilai_pasir_300_4_all + $total_volume_pasir_300_5_all + $total_volume_pasir_300_6_all + $total_volume_pasir_300_7_all;
				?>
				<th align="right"><?php echo number_format($total_volume_pasir_300,2,',','.');?></th>
				<th align="right"><?php echo number_format($total_nilai_pasir_300,0,',','.');?></th>
			</tr>
			<tr class="table-baris">
				<th align="center"></th>
				<th align="left">Batu Split 10 - 20</th>
				<th align="right"><?php echo number_format($harsat_rap_beton_batu1020,0,',','.');?></th>
				<th align="center">M3</th>
				<th align="right"><?php echo number_format($total_volume_batu1020_300_1_all,2,',','.');?></th>
				<th align="right"><?php echo number_format($total_nilai_batu1020_300_1_all,0,',','.');?></th>
				<th align="right"><?php echo number_format($total_volume_batu1020_300_2_all,2,',','.');?></th>
				<th align="right"><?php echo number_format($total_nilai_batu1020_300_2_all,0,',','.');?></th>
				<th align="right"><?php echo number_format($total_volume_batu1020_300_3_all,2,',','.');?></th>
				<th align="right"><?php echo number_format($total_nilai_batu1020_300_3_all,0,',','.');?></th>
				<th align="right"><?php echo number_format($total_volume_batu1020_300_4_all,2,',','.');?></th>
				<th align="right"><?php echo number_format($total_nilai_batu1020_300_4_all,0,',','.');?></th>
				<th align="right"><?php echo number_format($total_volume_batu1020_300_5_all,2,',','.');?></th>
				<th align="right"><?php echo number_format($total_nilai_batu1020_300_5_all,0,',','.');?></th>
				<th align="right"><?php echo number_format($total_volume_batu1020_300_6_all,2,',','.');?></th>
				<th align="right"><?php echo number_format($total_nilai_batu1020_300_6_all,0,',','.');?></th>
				<th align="right"><?php echo number_format($total_volume_batu1020_300_7_all,2,',','.');?></th>
				<th align="right"><?php echo number_format($total_nilai_batu1020_300_7_all,0,',','.');?></th>
				<?php
				$total_volume_batu1020_300 = $total_volume_batu1020_300_1_all + $total_volume_batu1020_300_2_all + $total_volume_batu1020_300_3_all + $total_volume_batu1020_300_4_all + $total_volume_batu1020_300_5_all + $total_volume_batu1020_300_6_all + $total_volume_batu1020_300_7_all;
				$total_nilai_batu1020_300 = $total_nilai_batu1020_300_1_all + $total_nilai_batu1020_300_2_all + $total_nilai_batu1020_300_3_all + $total_nilai_batu1020_300_4_all + $total_volume_batu1020_300_5_all + $total_volume_batu1020_300_6_all + $total_volume_batu1020_300_7_all;
				?>
				<th align="right"><?php echo number_format($total_volume_batu1020_300,2,',','.');?></th>
				<th align="right"><?php echo number_format($total_nilai_batu1020_300,0,',','.');?></th>			
			</tr>
			<tr class="table-baris">
				<th align="center"></th>
				<th align="left">Batu Split 20 - 30</th>
				<th align="right"><?php echo number_format($harsat_rap_beton_batu2030,0,',','.');?></th>
				<th align="center">M3</th>
				<th align="right"><?php echo number_format($total_volume_batu2030_300_1_all,2,',','.');?></th>
				<th align="right"><?php echo number_format($total_nilai_batu2030_300_1_all,0,',','.');?></th>
				<th align="right"><?php echo number_format($total_volume_batu2030_300_2_all,2,',','.');?></th>
				<th align="right"><?php echo number_format($total_nilai_batu2030_300_2_all,0,',','.');?></th>
				<th align="right"><?php echo number_format($total_volume_batu2030_300_3_all,2,',','.');?></th>
				<th align="right"><?php echo number_format($total_nilai_batu2030_300_3_all,0,',','.');?></th>
				<th align="right"><?php echo number_format($total_volume_batu2030_300_4_all,2,',','.');?></th>
				<th align="right"><?php echo number_format($total_nilai_batu2030_300_4_all,0,',','.');?></th>
				<th align="right"><?php echo number_format($total_volume_batu2030_300_5_all,2,',','.');?></th>
				<th align="right"><?php echo number_format($total_nilai_batu2030_300_5_all,0,',','.');?></th>
				<th align="right"><?php echo number_format($total_volume_batu2030_300_6_all,2,',','.');?></th>
				<th align="right"><?php echo number_format($total_nilai_batu2030_300_6_all,0,',','.');?></th>
				<th align="right"><?php echo number_format($total_volume_batu2030_300_7_all,2,',','.');?></th>
				<th align="right"><?php echo number_format($total_nilai_batu2030_300_7_all,0,',','.');?></th>
				<?php
				$total_volume_batu2030_300 = $total_volume_batu2030_300_1_all + $total_volume_batu2030_300_2_all + $total_volume_batu2030_300_3_all + $total_volume_batu2030_300_4_all + $total_volume_batu2030_300_5_all + $total_volume_batu2030_300_6_all + $total_volume_batu2030_300_7_all;
				$total_nilai_batu2030_300 = $total_nilai_batu2030_300_1_all + $total_nilai_batu2030_300_2_all + $total_nilai_batu2030_300_3_all + $total_nilai_batu2030_300_4_all + $total_volume_batu2030_300_5_all + $total_volume_batu2030_300_6_all + $total_volume_batu2030_300_7_all;
				?>
				<th align="right"><?php echo number_format($total_volume_batu2030_300,2,',','.');?></th>
				<th align="right"><?php echo number_format($total_nilai_batu2030_300,0,',','.');?></th>
			</tr>
			<tr class="table-baris">
				<th align="center"></th>
				<th align="left">Additive</th>
				<th align="right"><?php echo number_format($harsat_rap_beton_additive,0,',','.');?></th>
				<th align="center">Liter</th>
				<th align="right"><?php echo number_format($total_volume_additive_300_1_all,2,',','.');?></th>
				<th align="right"><?php echo number_format($total_nilai_additive_300_1_all,0,',','.');?></th>
				<th align="right"><?php echo number_format($total_volume_additive_300_2_all,2,',','.');?></th>
				<th align="right"><?php echo number_format($total_nilai_additive_300_2_all,0,',','.');?></th>
				<th align="right"><?php echo number_format($total_volume_additive_300_3_all,2,',','.');?></th>
				<th align="right"><?php echo number_format($total_nilai_additive_300_3_all,0,',','.');?></th>
				<th align="right"><?php echo number_format($total_volume_additive_300_4_all,2,',','.');?></th>
				<th align="right"><?php echo number_format($total_nilai_additive_300_4_all,0,',','.');?></th>
				<th align="right"><?php echo number_format($total_volume_additive_300_5_all,2,',','.');?></th>
				<th align="right"><?php echo number_format($total_nilai_additive_300_5_all,0,',','.');?></th>
				<th align="right"><?php echo number_format($total_volume_additive_300_6_all,2,',','.');?></th>
				<th align="right"><?php echo number_format($total_nilai_additive_300_6_all,0,',','.');?></th>
				<th align="right"><?php echo number_format($total_volume_additive_300_7_all,2,',','.');?></th>
				<th align="right"><?php echo number_format($total_nilai_additive_300_7_all,0,',','.');?></th>
				<?php
				$total_volume_additive_300 = $total_volume_additive_300_1_all + $total_volume_additive_300_2_all + $total_volume_additive_300_3_all + $total_volume_additive_300_4_all + $total_volume_additive_300_5_all + $total_volume_additive_300_6_all + $total_volume_additive_300_7_all;
				$total_nilai_additive_300 = $total_nilai_additive_300_1_all + $total_nilai_additive_300_2_all + $total_nilai_additive_300_3_all + $total_nilai_additive_300_4_all + $total_volume_additive_300_5_all + $total_volume_additive_300_6_all + $total_volume_additive_300_7_all;
				?>
				<th align="right"><?php echo number_format($total_volume_additive_300,2,',','.');?></th>
				<th align="right"><?php echo number_format($total_nilai_additive_300,0,',','.');?></th>
			</tr>
			<tr class="table-total">
				<?php
				$jumlah_kebutuhan_bahan_1 = $total_nilai_semen_300_1_all + $total_nilai_pasir_300_1_all + $total_nilai_batu1020_300_1_all +  $total_nilai_batu2030_300_1_all + $total_nilai_additive_300_1_all;
				$jumlah_kebutuhan_bahan_2 = $total_nilai_semen_300_2_all + $total_nilai_pasir_300_2_all + $total_nilai_batu1020_300_2_all +  $total_nilai_batu2030_300_2_all + $total_nilai_additive_300_2_all;
				$jumlah_kebutuhan_bahan_3 = $total_nilai_semen_300_3_all + $total_nilai_pasir_300_3_all + $total_nilai_batu1020_300_3_all +  $total_nilai_batu2030_300_3_all + $total_nilai_additive_300_3_all;
				$jumlah_kebutuhan_bahan_4 = $total_nilai_semen_300_4_all + $total_nilai_pasir_300_4_all + $total_nilai_batu1020_300_4_all +  $total_nilai_batu2030_300_4_all + $total_nilai_additive_300_4_all;
				$jumlah_kebutuhan_bahan_5 = $total_nilai_semen_300_5_all + $total_nilai_pasir_300_5_all + $total_nilai_batu1020_300_5_all +  $total_nilai_batu2030_300_5_all + $total_nilai_additive_300_5_all;
				$jumlah_kebutuhan_bahan_6 = $total_nilai_semen_300_6_all + $total_nilai_pasir_300_6_all + $total_nilai_batu1020_300_6_all +  $total_nilai_batu2030_300_6_all + $total_nilai_additive_300_6_all;
				$jumlah_kebutuhan_bahan_7 = $total_nilai_semen_300_7_all + $total_nilai_pasir_300_7_all + $total_nilai_batu1020_300_7_all +  $total_nilai_batu2030_300_7_all + $total_nilai_additive_300_7_all;
				$jumlah_kebutahan_bahan_all = $total_nilai_semen_300 + $total_nilai_pasir_300 + $total_nilai_batu1020_300 + $total_nilai_batu2030_300 + $total_nilai_additive_300;
				?>
				<th align="right" colspan="4">JUMLAH</th>
				<th align="right"></th>
				<th align="right"><?php echo number_format($jumlah_kebutuhan_bahan_1,0,',','.');?></th>
				<th align="right"></th>
				<th align="right"><?php echo number_format($jumlah_kebutuhan_bahan_2,0,',','.');?></th>
				<th align="right"></th>
				<th align="right"><?php echo number_format($jumlah_kebutuhan_bahan_3,0,',','.');?></th>
				<th align="right"></th>
				<th align="right"><?php echo number_format($jumlah_kebutuhan_bahan_4,0,',','.');?></th>
				<th align="right"></th>
				<th align="right"><?php echo number_format($jumlah_kebutuhan_bahan_5,0,',','.');?></th>
				<th align="right"></th>
				<th align="right"><?php echo number_format($jumlah_kebutuhan_bahan_6,0,',','.');?></th>
				<th align="right"></th>
				<th align="right"><?php echo number_format($jumlah_kebutuhan_bahan_7,0,',','.');?></th>
				<th align="right"></th>
				<th align="right"><?php echo number_format($jumlah_kebutahan_bahan_all,0,',','.');?></th>
			</tr>
			<?php
			$volume_rak_1 = $this->db->select('*, sum(vol_produk_a + vol_produk_b + vol_produk_c + vol_produk_d + vol_produk_e + vol_produk_f) as volume')
			->from('rak')
			->where("tanggal_rencana_kerja between '$date_juni24_awal' and '$date_juni24_akhir'")
			->get()->row_array();

			$volume_rak_2 = $this->db->select('*, sum(vol_produk_a + vol_produk_b + vol_produk_c + vol_produk_d + vol_produk_e + vol_produk_f) as volume')
			->from('rak')
			->where("tanggal_rencana_kerja between '$date_juli24_awal' and '$date_juli24_akhir'")
			->get()->row_array();

			$volume_rak_3 = $this->db->select('*, sum(vol_produk_a + vol_produk_b + vol_produk_c + vol_produk_d + vol_produk_e + vol_produk_f) as volume')
			->from('rak')
			->where("tanggal_rencana_kerja between '$date_agustus24_awal' and '$date_agustus24_akhir'")
			->get()->row_array();

			$volume_rak_4 = $this->db->select('*, sum(vol_produk_a + vol_produk_b + vol_produk_c + vol_produk_d + vol_produk_e + vol_produk_f) as volume')
			->from('rak')
			->where("tanggal_rencana_kerja between '$date_september24_awal' and '$date_september24_akhir'")
			->get()->row_array();

			$volume_rak_5 = $this->db->select('*, sum(vol_produk_a + vol_produk_b + vol_produk_c + vol_produk_d + vol_produk_e + vol_produk_f) as volume')
			->from('rak')
			->where("tanggal_rencana_kerja between '$date_oktober24_awal' and '$date_oktober24_akhir'")
			->get()->row_array();

			$volume_rak_6 = $this->db->select('*, sum(vol_produk_a + vol_produk_b + vol_produk_c + vol_produk_d + vol_produk_e + vol_produk_f) as volume')
			->from('rak')
			->where("tanggal_rencana_kerja between '$date_november24_awal' and '$date_november24_akhir'")
			->get()->row_array();

			$volume_rak_7 = $this->db->select('*, sum(vol_produk_a + vol_produk_b + vol_produk_c + vol_produk_d + vol_produk_e + vol_produk_f) as volume')
			->from('rak')
			->where("tanggal_rencana_kerja between '$date_desember24_awal' and '$date_desember24_akhir'")
			->get()->row_array();

			$harsat_rap_alat = $this->db->select('*')
			->from('rap_alat')
			->order_by('id','desc')->limit(1)
			->get()->row_array();
			$harsat_rap_alat_bp = $harsat_rap_alat['batching_plant'];
			$harsat_rap_alat_tm = $harsat_rap_alat['truck_mixer'];
			$harsat_rap_alat_wl = $harsat_rap_alat['wheel_loader'];
			$harsat_rap_alat_solar = $harsat_rap_alat['bbm_solar'];

			$total_volume_bp_1 = $volume_rak_1['volume'];
			$total_volume_bp_2 = $volume_rak_2['volume'];
			$total_volume_bp_3 = $volume_rak_3['volume'];
			$total_volume_bp_4 = $volume_rak_4['volume'];
			$total_volume_bp_5 = $volume_rak_5['volume'];
			$total_volume_bp_6 = $volume_rak_6['volume'];
			$total_volume_bp_7 = $volume_rak_7['volume'];
			
			$total_nilai_bp_1 = $volume_rak_1['volume'] * $harsat_rap_alat_bp;
			$total_nilai_bp_2 = $volume_rak_2['volume'] * $harsat_rap_alat_bp;
			$total_nilai_bp_3 = $volume_rak_3['volume'] * $harsat_rap_alat_bp;
			$total_nilai_bp_4 = $volume_rak_4['volume'] * $harsat_rap_alat_bp;
			$total_nilai_bp_5 = $volume_rak_5['volume'] * $harsat_rap_alat_bp;
			$total_nilai_bp_6 = $volume_rak_6['volume'] * $harsat_rap_alat_bp;
			$total_nilai_bp_7 = $volume_rak_7['volume'] * $harsat_rap_alat_bp;

			$total_volume_tm_1 = $volume_rak_1['volume'];
			$total_volume_tm_2 = $volume_rak_2['volume'];
			$total_volume_tm_3 = $volume_rak_3['volume'];
			$total_volume_tm_4 = $volume_rak_4['volume'];
			$total_volume_tm_5 = $volume_rak_5['volume'];
			$total_volume_tm_6 = $volume_rak_6['volume'];
			$total_volume_tm_7 = $volume_rak_7['volume'];

			$total_nilai_tm_1 = $volume_rak_1['volume'] * $harsat_rap_alat_tm;
			$total_nilai_tm_2 = $volume_rak_2['volume'] * $harsat_rap_alat_tm;
			$total_nilai_tm_3 = $volume_rak_3['volume'] * $harsat_rap_alat_tm;
			$total_nilai_tm_4 = $volume_rak_4['volume'] * $harsat_rap_alat_tm;
			$total_nilai_tm_5 = $volume_rak_5['volume'] * $harsat_rap_alat_tm;
			$total_nilai_tm_6 = $volume_rak_6['volume'] * $harsat_rap_alat_tm;
			$total_nilai_tm_7 = $volume_rak_7['volume'] * $harsat_rap_alat_tm;

			$total_volume_wl_1 = $volume_rak_1['volume'];
			$total_volume_wl_2 = $volume_rak_2['volume'];
			$total_volume_wl_3 = $volume_rak_3['volume'];
			$total_volume_wl_4 = $volume_rak_4['volume'];
			$total_volume_wl_5 = $volume_rak_5['volume'];
			$total_volume_wl_6 = $volume_rak_6['volume'];
			$total_volume_wl_7 = $volume_rak_7['volume'];

			$total_nilai_wl_1 = $volume_rak_1['volume'] * $harsat_rap_alat_wl;
			$total_nilai_wl_2 = $volume_rak_2['volume'] * $harsat_rap_alat_wl;
			$total_nilai_wl_3 = $volume_rak_3['volume'] * $harsat_rap_alat_wl;
			$total_nilai_wl_4 = $volume_rak_4['volume'] * $harsat_rap_alat_wl;
			$total_nilai_wl_5 = $volume_rak_5['volume'] * $harsat_rap_alat_wl;
			$total_nilai_wl_6 = $volume_rak_6['volume'] * $harsat_rap_alat_wl;
			$total_nilai_wl_7 = $volume_rak_7['volume'] * $harsat_rap_alat_wl;

			$total_volume_solar_1 = $volume_rak_1['volume'];
			$total_volume_solar_2 = $volume_rak_2['volume'];
			$total_volume_solar_3 = $volume_rak_3['volume'];
			$total_volume_solar_4 = $volume_rak_4['volume'];
			$total_volume_solar_5 = $volume_rak_5['volume'];
			$total_volume_solar_6 = $volume_rak_6['volume'];
			$total_volume_solar_7 = $volume_rak_7['volume'];

			$total_nilai_solar_1 = $volume_rak_1['volume'] * $harsat_rap_alat_solar;
			$total_nilai_solar_2 = $volume_rak_2['volume'] * $harsat_rap_alat_solar;
			$total_nilai_solar_3 = $volume_rak_3['volume'] * $harsat_rap_alat_solar;
			$total_nilai_solar_4 = $volume_rak_4['volume'] * $harsat_rap_alat_solar;
			$total_nilai_solar_5 = $volume_rak_5['volume'] * $harsat_rap_alat_solar;
			$total_nilai_solar_6 = $volume_rak_6['volume'] * $harsat_rap_alat_solar;
			$total_nilai_solar_7 = $volume_rak_7['volume'] * $harsat_rap_alat_solar;
			?>
			<tr class="table-baris">
				<th align="left" colspan="20"><b>C. TOTAL BIAYA PERALATAN</b></th>
			</tr>
			<?php
			$realisasi_1 = $this->db->select('*')
			->from('rak')
			->where("tanggal_rencana_kerja between '$date_juni24_awal' and '$date_juni24_akhir'")
			->get()->row_array();
			$vol_realisasi_bp_1 = $realisasi_1['vol_realisasi_bp'];
			$nilai_realisasi_bp_1 = $realisasi_1['nilai_realisasi_bp'];
			$vol_realisasi_bp_1 = ($total_volume_bp_1 * $realisasi_1['realisasi']) + $vol_realisasi_bp_1;
			$nilai_realisasi_bp_1 = ($total_nilai_bp_1 * $realisasi_1['realisasi']) + $nilai_realisasi_bp_1;

			$vol_realisasi_tm_1 = $realisasi_1['vol_realisasi_tm'];
			$nilai_realisasi_tm_1 = $realisasi_1['nilai_realisasi_tm'];
			$vol_realisasi_tm_1 = ($total_volume_tm_1 * $realisasi_1['realisasi']) + $vol_realisasi_tm_1;
			$nilai_realisasi_tm_1 = ($total_nilai_tm_1 * $realisasi_1['realisasi']) + $nilai_realisasi_tm_1;

			$vol_realisasi_wl_1 = $realisasi_1['vol_realisasi_wl'];
			$nilai_realisasi_wl_1 = $realisasi_1['nilai_realisasi_wl'];
			$vol_realisasi_wl_1 = ($total_volume_wl_1 * $realisasi_1['realisasi']) + $vol_realisasi_wl_1;
			$nilai_realisasi_wl_1 = ($total_nilai_wl_1 * $realisasi_1['realisasi']) + $nilai_realisasi_wl_1;

			$vol_realisasi_solar_1 = $realisasi_1['vol_realisasi_solar'];
			$nilai_realisasi_solar_1 = $realisasi_1['nilai_realisasi_solar'];
			$vol_realisasi_solar_1 = ($total_volume_solar_1 * $realisasi_1['realisasi']) + $vol_realisasi_solar_1;
			$nilai_realisasi_solar_1 = ($total_nilai_solar_1 * $realisasi_1['realisasi']) + $nilai_realisasi_solar_1;

			$realisasi_2 = $this->db->select('*')
			->from('rak')
			->where("tanggal_rencana_kerja between '$date_juli24_awal' and '$date_juli24_akhir'")
			->get()->row_array();
			$vol_realisasi_bp_2 = $realisasi_2['vol_realisasi_bp'];
			$nilai_realisasi_bp_2 = $realisasi_2['nilai_realisasi_bp'];
			$vol_realisasi_bp_2 = ($total_volume_bp_2 * $realisasi_2['realisasi']) + $vol_realisasi_bp_2;
			$nilai_realisasi_bp_2 = ($total_nilai_bp_2 * $realisasi_2['realisasi']) + $nilai_realisasi_bp_2;

			$vol_realisasi_tm_2 = $realisasi_2['vol_realisasi_tm'];
			$nilai_realisasi_tm_2 = $realisasi_2['nilai_realisasi_tm'];
			$vol_realisasi_tm_2 = ($total_volume_tm_2 * $realisasi_2['realisasi']) + $vol_realisasi_tm_2;
			$nilai_realisasi_tm_2 = ($total_nilai_tm_2 * $realisasi_2['realisasi']) + $nilai_realisasi_tm_2;

			$vol_realisasi_wl_2 = $realisasi_2['vol_realisasi_wl'];
			$nilai_realisasi_wl_2 = $realisasi_2['nilai_realisasi_wl'];
			$vol_realisasi_wl_2 = ($total_volume_wl_2 * $realisasi_2['realisasi']) + $vol_realisasi_wl_2;
			$nilai_realisasi_wl_2 = ($total_nilai_wl_2 * $realisasi_2['realisasi']) + $nilai_realisasi_wl_2;

			$vol_realisasi_solar_2 = $realisasi_2['vol_realisasi_solar'];
			$nilai_realisasi_solar_2 = $realisasi_2['nilai_realisasi_solar'];
			$vol_realisasi_solar_2 = ($total_volume_solar_2 * $realisasi_2['realisasi']) + $vol_realisasi_solar_2;
			$nilai_realisasi_solar_2 = ($total_nilai_solar_2 * $realisasi_2['realisasi']) + $nilai_realisasi_solar_2;

			$realisasi_3 = $this->db->select('*')
			->from('rak')
			->where("tanggal_rencana_kerja between '$date_agustus24_awal' and '$date_agustus24_akhir'")
			->get()->row_array();
			$vol_realisasi_bp_3 = $realisasi_3['vol_realisasi_bp'];
			$nilai_realisasi_bp_3 = $realisasi_3['nilai_realisasi_bp'];
			$vol_realisasi_bp_3 = ($total_volume_bp_3 * $realisasi_3['realisasi']) + $vol_realisasi_bp_3;
			$nilai_realisasi_bp_3 = ($total_nilai_bp_3 * $realisasi_3['realisasi']) + $nilai_realisasi_bp_3;

			$vol_realisasi_tm_3 = $realisasi_3['vol_realisasi_tm'];
			$nilai_realisasi_tm_3 = $realisasi_3['nilai_realisasi_tm'];
			$vol_realisasi_tm_3 = ($total_volume_tm_3 * $realisasi_3['realisasi']) + $vol_realisasi_tm_3;
			$nilai_realisasi_tm_3 = ($total_nilai_tm_3 * $realisasi_3['realisasi']) + $nilai_realisasi_tm_3;

			$vol_realisasi_wl_3 = $realisasi_3['vol_realisasi_wl'];
			$nilai_realisasi_wl_3 = $realisasi_3['nilai_realisasi_wl'];
			$vol_realisasi_wl_3 = ($total_volume_wl_3 * $realisasi_3['realisasi']) + $vol_realisasi_wl_3;
			$nilai_realisasi_wl_3 = ($total_nilai_wl_3 * $realisasi_3['realisasi']) + $nilai_realisasi_wl_3;

			$vol_realisasi_solar_3 = $realisasi_3['vol_realisasi_solar'];
			$nilai_realisasi_solar_3 = $realisasi_3['nilai_realisasi_solar'];
			$vol_realisasi_solar_3 = ($total_volume_solar_3 * $realisasi_3['realisasi']) + $vol_realisasi_solar_3;
			$nilai_realisasi_solar_3 = ($total_nilai_solar_3 * $realisasi_3['realisasi']) + $nilai_realisasi_solar_3;

			$realisasi_4 = $this->db->select('*')
			->from('rak')
			->where("tanggal_rencana_kerja between '$date_september24_awal' and '$date_september24_akhir'")
			->get()->row_array();
			$vol_realisasi_bp_4 = $realisasi_4['vol_realisasi_bp'];
			$nilai_realisasi_bp_4 = $realisasi_4['nilai_realisasi_bp'];
			$vol_realisasi_bp_4 = ($total_volume_bp_4 * $realisasi_4['realisasi']) + $vol_realisasi_bp_4;
			$nilai_realisasi_bp_4 = ($total_nilai_bp_4 * $realisasi_4['realisasi']) + $nilai_realisasi_bp_4;

			$vol_realisasi_tm_4 = $realisasi_4['vol_realisasi_tm'];
			$nilai_realisasi_tm_4 = $realisasi_4['nilai_realisasi_tm'];
			$vol_realisasi_tm_4 = ($total_volume_tm_4 * $realisasi_4['realisasi']) + $vol_realisasi_tm_4;
			$nilai_realisasi_tm_4 = ($total_nilai_tm_4 * $realisasi_4['realisasi']) + $nilai_realisasi_tm_4;

			$vol_realisasi_wl_4 = $realisasi_4['vol_realisasi_wl'];
			$nilai_realisasi_wl_4 = $realisasi_4['nilai_realisasi_wl'];
			$vol_realisasi_wl_4 = ($total_volume_wl_4 * $realisasi_4['realisasi']) + $vol_realisasi_wl_4;
			$nilai_realisasi_wl_4 = ($total_nilai_wl_4 * $realisasi_4['realisasi']) + $nilai_realisasi_wl_4;

			$vol_realisasi_solar_4 = $realisasi_4['vol_realisasi_solar'];
			$nilai_realisasi_solar_4 = $realisasi_4['nilai_realisasi_solar'];
			$vol_realisasi_solar_4 = ($total_volume_solar_4 * $realisasi_4['realisasi']) + $vol_realisasi_solar_4;
			$nilai_realisasi_solar_4 = ($total_nilai_solar_4 * $realisasi_4['realisasi']) + $nilai_realisasi_solar_4;

			$realisasi_5 = $this->db->select('*')
			->from('rak')
			->where("tanggal_rencana_kerja between '$date_oktober24_awal' and '$date_oktober24_akhir'")
			->get()->row_array();
			$vol_realisasi_bp_5 = $realisasi_5['vol_realisasi_bp'];
			$nilai_realisasi_bp_5 = $realisasi_5['nilai_realisasi_bp'];
			$vol_realisasi_bp_5 = ($total_volume_bp_5 * $realisasi_5['realisasi']) + $vol_realisasi_bp_5;
			$nilai_realisasi_bp_5 = ($total_nilai_bp_5 * $realisasi_5['realisasi']) + $nilai_realisasi_bp_5;

			$vol_realisasi_tm_5 = $realisasi_5['vol_realisasi_tm'];
			$nilai_realisasi_tm_5 = $realisasi_5['nilai_realisasi_tm'];
			$vol_realisasi_tm_5 = ($total_volume_tm_5 * $realisasi_5['realisasi']) + $vol_realisasi_tm_5;
			$nilai_realisasi_tm_5 = ($total_nilai_tm_5 * $realisasi_5['realisasi']) + $nilai_realisasi_tm_5;

			$vol_realisasi_wl_5 = $realisasi_5['vol_realisasi_wl'];
			$nilai_realisasi_wl_5 = $realisasi_5['nilai_realisasi_wl'];
			$vol_realisasi_wl_5 = ($total_volume_wl_5 * $realisasi_5['realisasi']) + $vol_realisasi_wl_5;
			$nilai_realisasi_wl_5 = ($total_nilai_wl_5 * $realisasi_5['realisasi']) + $nilai_realisasi_wl_5;

			$vol_realisasi_solar_5 = $realisasi_5['vol_realisasi_solar'];
			$nilai_realisasi_solar_5 = $realisasi_5['nilai_realisasi_solar'];
			$vol_realisasi_solar_5 = ($total_volume_solar_5 * $realisasi_5['realisasi']) + $vol_realisasi_solar_5;
			$nilai_realisasi_solar_5 = ($total_nilai_solar_5 * $realisasi_5['realisasi']) + $nilai_realisasi_solar_5;

			$realisasi_6 = $this->db->select('*')
			->from('rak')
			->where("tanggal_rencana_kerja between '$date_november24_awal' and '$date_november24_akhir'")
			->get()->row_array();
			$vol_realisasi_bp_6 = $realisasi_6['vol_realisasi_bp'];
			$nilai_realisasi_bp_6 = $realisasi_6['nilai_realisasi_bp'];
			$vol_realisasi_bp_6 = ($total_volume_bp_6 * $realisasi_6['realisasi']) + $vol_realisasi_bp_6;
			$nilai_realisasi_bp_6 = ($total_nilai_bp_6 * $realisasi_6['realisasi']) + $nilai_realisasi_bp_6;

			$vol_realisasi_tm_6 = $realisasi_6['vol_realisasi_tm'];
			$nilai_realisasi_tm_6 = $realisasi_6['nilai_realisasi_tm'];
			$vol_realisasi_tm_6 = ($total_volume_tm_6 * $realisasi_6['realisasi']) + $vol_realisasi_tm_6;
			$nilai_realisasi_tm_6 = ($total_nilai_tm_6 * $realisasi_6['realisasi']) + $nilai_realisasi_tm_6;

			$vol_realisasi_wl_6 = $realisasi_6['vol_realisasi_wl'];
			$nilai_realisasi_wl_6 = $realisasi_6['nilai_realisasi_wl'];
			$vol_realisasi_wl_6 = ($total_volume_wl_6 * $realisasi_6['realisasi']) + $vol_realisasi_wl_6;
			$nilai_realisasi_wl_6 = ($total_nilai_wl_6 * $realisasi_6['realisasi']) + $nilai_realisasi_wl_6;

			$vol_realisasi_solar_6 = $realisasi_6['vol_realisasi_solar'];
			$nilai_realisasi_solar_6 = $realisasi_6['nilai_realisasi_solar'];
			$vol_realisasi_solar_6 = ($total_volume_solar_6 * $realisasi_6['realisasi']) + $vol_realisasi_solar_6;
			$nilai_realisasi_solar_6 = ($total_nilai_solar_6 * $realisasi_6['realisasi']) + $nilai_realisasi_solar_6;

			$realisasi_7 = $this->db->select('*')
			->from('rak')
			->where("tanggal_rencana_kerja between '$date_desember24_awal' and '$date_desember24_akhir'")
			->get()->row_array();
			$vol_realisasi_bp_7 = $realisasi_7['vol_realisasi_bp'];
			$nilai_realisasi_bp_7 = $realisasi_7['nilai_realisasi_bp'];
			$vol_realisasi_bp_7 = ($total_volume_bp_7 * $realisasi_7['realisasi']) + $vol_realisasi_bp_7;
			$nilai_realisasi_bp_7 = ($total_nilai_bp_7 * $realisasi_7['realisasi']) + $nilai_realisasi_bp_7;

			$vol_realisasi_tm_7 = $realisasi_7['vol_realisasi_tm'];
			$nilai_realisasi_tm_7 = $realisasi_7['nilai_realisasi_tm'];
			$vol_realisasi_tm_7 = ($total_volume_tm_7 * $realisasi_7['realisasi']) + $vol_realisasi_tm_7;
			$nilai_realisasi_tm_7 = ($total_nilai_tm_7 * $realisasi_7['realisasi']) + $nilai_realisasi_tm_7;

			$vol_realisasi_wl_7 = $realisasi_7['vol_realisasi_wl'];
			$nilai_realisasi_wl_7 = $realisasi_7['nilai_realisasi_wl'];
			$vol_realisasi_wl_7 = ($total_volume_wl_7 * $realisasi_7['realisasi']) + $vol_realisasi_wl_7;
			$nilai_realisasi_wl_7 = ($total_nilai_wl_7 * $realisasi_7['realisasi']) + $nilai_realisasi_wl_7;

			$vol_realisasi_solar_7 = $realisasi_7['vol_realisasi_solar'];
			$nilai_realisasi_solar_7 = $realisasi_7['nilai_realisasi_solar'];
			$vol_realisasi_solar_7 = ($total_volume_solar_7 * $realisasi_7['realisasi']) + $vol_realisasi_solar_7;
			$nilai_realisasi_solar_7 = ($total_nilai_solar_7 * $realisasi_7['realisasi']) + $nilai_realisasi_solar_7;
			?>
			<tr class="table-baris">
				<th align="center"></th>
				<th align="left">Batching Plant</th>
				<th align="right"><?php echo number_format($harsat_rap_alat_bp,0,',','.');?></th>
				<th align="center">M3</th>
				<th align="right"><?php echo number_format($vol_realisasi_bp_1,2,',','.');?></th>
				<th align="right"><?php echo number_format($nilai_realisasi_bp_1,0,',','.');?></th>
				<th align="right"><?php echo number_format($vol_realisasi_bp_2,2,',','.');?></th>
				<th align="right"><?php echo number_format($nilai_realisasi_bp_2,0,',','.');?></th>
				<th align="right"><?php echo number_format($vol_realisasi_bp_3,2,',','.');?></th>
				<th align="right"><?php echo number_format($nilai_realisasi_bp_3,0,',','.');?></th>
				<th align="right"><?php echo number_format($vol_realisasi_bp_4,2,',','.');?></th>
				<th align="right"><?php echo number_format($nilai_realisasi_bp_4,0,',','.');?></th>
				<th align="right"><?php echo number_format($vol_realisasi_bp_5,2,',','.');?></th>
				<th align="right"><?php echo number_format($nilai_realisasi_bp_5,0,',','.');?></th>
				<th align="right"><?php echo number_format($vol_realisasi_bp_6,2,',','.');?></th>
				<th align="right"><?php echo number_format($nilai_realisasi_bp_6,0,',','.');?></th>
				<th align="right"><?php echo number_format($vol_realisasi_bp_7,2,',','.');?></th>
				<th align="right"><?php echo number_format($nilai_realisasi_bp_7,0,',','.');?></th>
				<?php
				$total_volume_bp = $vol_realisasi_bp_1 + $vol_realisasi_bp_2 + $vol_realisasi_bp_3 + $vol_realisasi_bp_4 + $vol_realisasi_bp_5 + $vol_realisasi_bp_6 + $vol_realisasi_bp_7;
				$total_nilai_bp = $nilai_realisasi_bp_1 + $nilai_realisasi_bp_2 + $nilai_realisasi_bp_3 + $nilai_realisasi_bp_4 + $nilai_realisasi_bp_5 + $nilai_realisasi_bp_6 + $nilai_realisasi_bp_7;
				?>
				<th align="right"><?php echo number_format($total_volume_bp,2,',','.');?></th>
				<th align="right"><?php echo number_format($total_nilai_bp,0,',','.');?></th>
			</tr>
			<tr class="table-baris">
				<th align="center"></th>
				<th align="left">Truck Mixer</th>
				<th align="right"><?php echo number_format($harsat_rap_alat_tm,0,',','.');?></th>
				<th align="center">M3</th>
				<th align="right"><?php echo number_format($vol_realisasi_tm_1,2,',','.');?></th>
				<th align="right"><?php echo number_format($nilai_realisasi_tm_1,0,',','.');?></th>
				<th align="right"><?php echo number_format($vol_realisasi_tm_2,2,',','.');?></th>
				<th align="right"><?php echo number_format($nilai_realisasi_tm_2,0,',','.');?></th>
				<th align="right"><?php echo number_format($vol_realisasi_tm_3,2,',','.');?></th>
				<th align="right"><?php echo number_format($nilai_realisasi_tm_3,0,',','.');?></th>
				<th align="right"><?php echo number_format($vol_realisasi_tm_4,2,',','.');?></th>
				<th align="right"><?php echo number_format($nilai_realisasi_tm_4,0,',','.');?></th>
				<th align="right"><?php echo number_format($vol_realisasi_tm_5,2,',','.');?></th>
				<th align="right"><?php echo number_format($nilai_realisasi_tm_5,0,',','.');?></th>
				<th align="right"><?php echo number_format($vol_realisasi_tm_6,2,',','.');?></th>
				<th align="right"><?php echo number_format($nilai_realisasi_tm_6,0,',','.');?></th>
				<th align="right"><?php echo number_format($vol_realisasi_tm_7,2,',','.');?></th>
				<th align="right"><?php echo number_format($nilai_realisasi_tm_7,0,',','.');?></th>
				<?php
				$total_volume_tm = $vol_realisasi_tm_1 + $vol_realisasi_tm_2 + $vol_realisasi_tm_3 + $vol_realisasi_tm_4 + $vol_realisasi_tm_5 + $vol_realisasi_tm_6 + $vol_realisasi_tm_7;
				$total_nilai_tm = $nilai_realisasi_tm_1 + $nilai_realisasi_tm_2 + $nilai_realisasi_tm_3+ $nilai_realisasi_tm_4 + $nilai_realisasi_tm_5 + $nilai_realisasi_tm_6 + $nilai_realisasi_tm_7;
				?>
				<th align="right"><?php echo number_format($total_volume_tm,2,',','.');?></th>
				<th align="right"><?php echo number_format($total_nilai_tm,0,',','.');?></th>
			</tr>
			<tr class="table-baris">
				<th align="center"></th>
				<th align="left">Wheel Loader</th>
				<th align="right"><?php echo number_format($harsat_rap_alat_wl,0,',','.');?></th>
				<th align="center">M3</th>
				<th align="right"><?php echo number_format($vol_realisasi_wl_1,2,',','.');?></th>
				<th align="right"><?php echo number_format($nilai_realisasi_wl_1,0,',','.');?></th>
				<th align="right"><?php echo number_format($vol_realisasi_wl_2,2,',','.');?></th>
				<th align="right"><?php echo number_format($nilai_realisasi_wl_2,0,',','.');?></th>
				<th align="right"><?php echo number_format($vol_realisasi_wl_3,2,',','.');?></th>
				<th align="right"><?php echo number_format($nilai_realisasi_wl_3,0,',','.');?></th>
				<th align="right"><?php echo number_format($vol_realisasi_wl_4,2,',','.');?></th>
				<th align="right"><?php echo number_format($nilai_realisasi_wl_4,0,',','.');?></th>
				<th align="right"><?php echo number_format($vol_realisasi_wl_5,2,',','.');?></th>
				<th align="right"><?php echo number_format($nilai_realisasi_wl_5,0,',','.');?></th>
				<th align="right"><?php echo number_format($vol_realisasi_wl_6,2,',','.');?></th>
				<th align="right"><?php echo number_format($nilai_realisasi_wl_6,0,',','.');?></th>
				<th align="right"><?php echo number_format($vol_realisasi_wl_7,2,',','.');?></th>
				<th align="right"><?php echo number_format($nilai_realisasi_wl_7,0,',','.');?></th>
				<?php
				$total_volume_wl = $vol_realisasi_wl_1 + $vol_realisasi_wl_2 + $vol_realisasi_wl_3 + $vol_realisasi_wl_4 + $vol_realisasi_wl_5 + $vol_realisasi_wl_6 + $vol_realisasi_wl_7;
				$total_nilai_wl = $nilai_realisasi_wl_1 + $nilai_realisasi_wl_2 + $nilai_realisasi_wl_3 + $nilai_realisasi_wl_4 + $nilai_realisasi_wl_5 + $nilai_realisasi_wl_6 + $nilai_realisasi_wl_7;
				?>
				<th align="right"><?php echo number_format($total_volume_wl,2,',','.');?></th>
				<th align="right"><?php echo number_format($total_nilai_wl,0,',','.');?></th>
			</tr>
			<tr class="table-baris">
				<th align="center"></th>
				<th align="left">Solar</th>
				<th align="right"><?php echo number_format($harsat_rap_alat_solar,0,',','.');?></th>
				<th align="center">M3</th>
				<th align="right"><?php echo number_format($vol_realisasi_solar_1,2,',','.');?></th>
				<th align="right"><?php echo number_format($nilai_realisasi_solar_1,0,',','.');?></th>
				<th align="right"><?php echo number_format($vol_realisasi_solar_2,2,',','.');?></th>
				<th align="right"><?php echo number_format($nilai_realisasi_solar_2,0,',','.');?></th>
				<th align="right"><?php echo number_format($vol_realisasi_solar_3,2,',','.');?></th>
				<th align="right"><?php echo number_format($nilai_realisasi_solar_3,0,',','.');?></th>
				<th align="right"><?php echo number_format($vol_realisasi_solar_4,2,',','.');?></th>
				<th align="right"><?php echo number_format($nilai_realisasi_solar_4,0,',','.');?></th>
				<th align="right"><?php echo number_format($vol_realisasi_solar_5,2,',','.');?></th>
				<th align="right"><?php echo number_format($nilai_realisasi_solar_5,0,',','.');?></th>
				<th align="right"><?php echo number_format($vol_realisasi_solar_6,2,',','.');?></th>
				<th align="right"><?php echo number_format($nilai_realisasi_solar_6,0,',','.');?></th>
				<th align="right"><?php echo number_format($vol_realisasi_solar_7,2,',','.');?></th>
				<th align="right"><?php echo number_format($nilai_realisasi_solar_7,0,',','.');?></th>
				<?php
				$total_volume_solar = $vol_realisasi_solar_1 + $vol_realisasi_solar_2 + $vol_realisasi_solar_3 + $vol_realisasi_solar_4 + $vol_realisasi_solar_5 + $vol_realisasi_solar_6 + $vol_realisasi_solar_7;
				$total_nilai_solar = $nilai_realisasi_solar_1 + $nilai_realisasi_solar_2 + $nilai_realisasi_solar_3 + $nilai_realisasi_solar_4 + $nilai_realisasi_solar_5 + $nilai_realisasi_solar_6 + $nilai_realisasi_solar_7;
				?>
				<th align="right"><?php echo number_format($total_volume_solar,2,',','.');?></th>
				<th align="right"><?php echo number_format($total_nilai_solar,0,',','.');?></th>
			</tr>
			<?php
			$jumlah_alat_1 = $nilai_realisasi_bp_1 + $nilai_realisasi_tm_1 + $nilai_realisasi_wl_1 + $nilai_realisasi_solar_1;
			$jumlah_alat_2 = $nilai_realisasi_bp_2 + $nilai_realisasi_tm_2 + $nilai_realisasi_wl_2 + $nilai_realisasi_solar_2;
			$jumlah_alat_3 = $nilai_realisasi_bp_3 + $nilai_realisasi_tm_3 + $nilai_realisasi_wl_3 + $nilai_realisasi_solar_3;
			$jumlah_alat_4 = $nilai_realisasi_bp_4 + $nilai_realisasi_tm_4 + $nilai_realisasi_wl_4 + $nilai_realisasi_solar_4;
			$jumlah_alat_5 = $nilai_realisasi_bp_5 + $nilai_realisasi_tm_5 + $nilai_realisasi_wl_5 + $nilai_realisasi_solar_5;
			$jumlah_alat_6 = $nilai_realisasi_bp_6 + $nilai_realisasi_tm_6 + $nilai_realisasi_wl_6 + $nilai_realisasi_solar_6;
			$jumlah_alat_7 = $nilai_realisasi_bp_7 + $nilai_realisasi_tm_7 + $nilai_realisasi_wl_7 + $nilai_realisasi_solar_7;
			$jumlah_alat = $total_nilai_bp + $total_nilai_tm + $total_nilai_wl + $total_nilai_solar;
			?>
			<tr class="table-total">
				<th align="right" colspan="4">JUMLAH</th>
				<th align="right"></th>
				<th align="right"><?php echo number_format($jumlah_alat_1,0,',','.');?></th>
				<th align="right"></th>
				<th align="right"><?php echo number_format($jumlah_alat_2,0,',','.');?></th>
				<th align="right"></th>
				<th align="right"><?php echo number_format($jumlah_alat_3,0,',','.');?></th>
				<th align="right"></th>
				<th align="right"><?php echo number_format($jumlah_alat_4,0,',','.');?></th>
				<th align="right"></th>
				<th align="right"><?php echo number_format($jumlah_alat_5,0,',','.');?></th>
				<th align="right"></th>
				<th align="right"><?php echo number_format($jumlah_alat_6,0,',','.');?></th>
				<th align="right"></th>
				<th align="right"><?php echo number_format($jumlah_alat_7,0,',','.');?></th>
				<th align="right"></th>
				<th align="right"><?php echo number_format($jumlah_alat,0,',','.');?></th>
			</tr>
			<?php
			$overhead_rak_1 = $this->db->select('sum(overhead) as nilai')
			->from('rak')
			->where("tanggal_rencana_kerja between '$date_juni24_awal' and '$date_juni24_akhir'")
			->get()->row_array();
			
			$overhead_rak_2 = $this->db->select('sum(overhead) as nilai')
			->from('rak')
			->where("tanggal_rencana_kerja between '$date_juli24_awal' and '$date_juli24_akhir'")
			->get()->row_array();

			$overhead_rak_3 = $this->db->select('sum(overhead) as nilai')
			->from('rak')
			->where("tanggal_rencana_kerja between '$date_agustus24_awal' and '$date_agustus24_akhir'")
			->get()->row_array();

			$overhead_rak_4 = $this->db->select('sum(overhead) as nilai')
			->from('rak')
			->where("tanggal_rencana_kerja between '$date_september24_awal' and '$date_september24_akhir'")
			->get()->row_array();
			
			$overhead_rak_5 = $this->db->select('sum(overhead) as nilai')
			->from('rak')
			->where("tanggal_rencana_kerja between '$date_oktober24_awal' and '$date_oktober24_akhir'")
			->get()->row_array();

			$overhead_rak_6 = $this->db->select('sum(overhead) as nilai')
			->from('rak')
			->where("tanggal_rencana_kerja between '$date_november24_awal' and '$date_november24_akhir'")
			->get()->row_array();

			$overhead_rak_7 = $this->db->select('sum(overhead) as nilai')
			->from('rak')
			->where("tanggal_rencana_kerja between '$date_desember24_awal' and '$date_desember24_akhir'")
			->get()->row_array();

			$total_nilai_overhead_1 = $overhead_rak_1['nilai'];
			$total_nilai_overhead_2 = $overhead_rak_2['nilai'];
			$total_nilai_overhead_3 = $overhead_rak_3['nilai'];
			$total_nilai_overhead_4 = $overhead_rak_4['nilai'];
			$total_nilai_overhead_5 = $overhead_rak_5['nilai'];
			$total_nilai_overhead_6 = $overhead_rak_6['nilai'];
			$total_nilai_overhead_7 = $overhead_rak_7['nilai'];
			?>
			<tr class="table-baris">
				<th align="left" colspan="20"><b>D. OVERHEAD</b></th>
			</tr>
			<?php
			$total_nilai_overhead = $total_nilai_overhead_1 + $total_nilai_overhead_2 + $total_nilai_overhead_3 + $total_nilai_overhead_4 + $total_nilai_overhead_5 + $total_nilai_overhead_6 + $total_nilai_overhead_7;
			?>
			<tr class="table-baris">
				<th align="center"></th>
				<th align="left">Overhead</th>
				<th align="right"></th>
				<th align="center"></th>
				<th align="right"><?php echo number_format($total_volume_overhead_1,2,',','.');?></th>
				<th align="right"><?php echo number_format($total_nilai_overhead_1,0,',','.');?></th>
				<th align="right"><?php echo number_format($total_volume_overhead_2,2,',','.');?></th>
				<th align="right"><?php echo number_format($total_nilai_overhead_2,0,',','.');?></th>
				<th align="right"><?php echo number_format($total_volume_overhead_3,2,',','.');?></th>
				<th align="right"><?php echo number_format($total_nilai_overhead_3,0,',','.');?></th>
				<th align="right"><?php echo number_format($total_volume_overhead_4,2,',','.');?></th>
				<th align="right"><?php echo number_format($total_nilai_overhead_4,0,',','.');?></th>
				<th align="right"><?php echo number_format($total_volume_overhead_5,2,',','.');?></th>
				<th align="right"><?php echo number_format($total_nilai_overhead_5,0,',','.');?></th>
				<th align="right"><?php echo number_format($total_volume_overhead_6,2,',','.');?></th>
				<th align="right"><?php echo number_format($total_nilai_overhead_6,0,',','.');?></th>
				<th align="right"><?php echo number_format($total_volume_overhead_7,2,',','.');?></th>
				<th align="right"><?php echo number_format($total_nilai_overhead_7,0,',','.');?></th>
				<th align="right"><?php echo number_format($total_volume_overhead,2,',','.');?></th>
				<th align="right"><?php echo number_format($total_nilai_overhead,0,',','.');?></th>
			</tr>
			<?php
			$diskonto_rak_1 = $this->db->select('sum(diskonto) as nilai')
			->from('rak')
			->where("tanggal_rencana_kerja between '$date_juni24_awal' and '$date_juni24_akhir'")
			->get()->row_array();

			$diskonto_rak_2 = $this->db->select('sum(diskonto) as nilai')
			->from('rak')
			->where("tanggal_rencana_kerja between '$date_juli24_awal' and '$date_juli24_akhir'")
			->get()->row_array();
			
			$diskonto_rak_3 = $this->db->select('sum(diskonto) as nilai')
			->from('rak')
			->where("tanggal_rencana_kerja between '$date_agustus24_awal' and '$date_agustus24_akhir'")
			->get()->row_array();

			$diskonto_rak_4 = $this->db->select('sum(diskonto) as nilai')
			->from('rak')
			->where("tanggal_rencana_kerja between '$date_september24_awal' and '$date_september24_akhir'")
			->get()->row_array();
			
			$diskonto_rak_5 = $this->db->select('sum(diskonto) as nilai')
			->from('rak')
			->where("tanggal_rencana_kerja between '$date_oktober24_awal' and '$date_oktober24_akhir'")
			->get()->row_array();

			$diskonto_rak_6 = $this->db->select('sum(diskonto) as nilai')
			->from('rak')
			->where("tanggal_rencana_kerja between '$date_november24_awal' and '$date_november24_akhir'")
			->get()->row_array();

			$diskonto_rak_7 = $this->db->select('sum(diskonto) as nilai')
			->from('rak')
			->where("tanggal_rencana_kerja between '$date_desember24_awal' and '$date_desember24_akhir'")
			->get()->row_array();

			$total_nilai_diskonto_1 = $diskonto_rak_1['nilai'];
			$total_nilai_diskonto_2 = $diskonto_rak_2['nilai'];
			$total_nilai_diskonto_3 = $diskonto_rak_3['nilai'];
			$total_nilai_diskonto_4 = $diskonto_rak_4['nilai'];
			$total_nilai_diskonto_5 = $diskonto_rak_5['nilai'];
			$total_nilai_diskonto_6 = $diskonto_rak_6['nilai'];
			$total_nilai_diskonto_7 = $diskonto_rak_7['nilai'];
			?>
			<tr class="table-baris">
				<th align="left" colspan="20"><b>E. DISKONTO</b></th>
			</tr>
			<?php
			$total_nilai_diskonto = $total_nilai_diskonto_1 + $total_nilai_diskonto_2 + $total_nilai_diskonto_3 + $total_nilai_diskonto_4 + $total_nilai_diskonto_5 + $total_nilai_diskonto_6 + $total_nilai_diskonto_7;
			?>
			<tr class="table-baris">
				<th align="center"></th>
				<th align="left">Diskonto</th>
				<th align="right"></th>
				<th align="center"></th>
				<th align="right"><?php echo number_format($total_volume_diskonto_1,2,',','.');?></th>
				<th align="right"><?php echo number_format($total_nilai_diskonto_1,0,',','.');?></th>
				<th align="right"><?php echo number_format($total_volume_diskonto_2,2,',','.');?></th>
				<th align="right"><?php echo number_format($total_nilai_diskonto_2,0,',','.');?></th>
				<th align="right"><?php echo number_format($total_volume_diskonto_3,2,',','.');?></th>
				<th align="right"><?php echo number_format($total_nilai_diskonto_3,0,',','.');?></th>
				<th align="right"><?php echo number_format($total_volume_diskonto_4,2,',','.');?></th>
				<th align="right"><?php echo number_format($total_nilai_diskonto_4,0,',','.');?></th>
				<th align="right"><?php echo number_format($total_volume_diskonto_5,2,',','.');?></th>
				<th align="right"><?php echo number_format($total_nilai_diskonto_5,0,',','.');?></th>
				<th align="right"><?php echo number_format($total_volume_diskonto_6,2,',','.');?></th>
				<th align="right"><?php echo number_format($total_nilai_diskonto_6,0,',','.');?></th>
				<th align="right"><?php echo number_format($total_volume_diskonto_7,2,',','.');?></th>
				<th align="right"><?php echo number_format($total_nilai_diskonto_7,0,',','.');?></th>
				<th align="right"><?php echo number_format($total_volume_diskonto,2,',','.');?></th>
				<th align="right"><?php echo number_format($total_nilai_diskonto,0,',','.');?></th>
			</tr>
			<?php
			$jumlah_biaya_1 = $jumlah_kebutuhan_bahan_1 + $jumlah_alat_1 + $total_nilai_overhead_1 + $total_nilai_diskonto_1;
			$jumlah_biaya_2 = $jumlah_kebutuhan_bahan_2 + $jumlah_alat_2 + $total_nilai_overhead_2 + $total_nilai_diskonto_2;
			$jumlah_biaya_3 = $jumlah_kebutuhan_bahan_3 + $jumlah_alat_3 + $total_nilai_overhead_3 + $total_nilai_diskonto_3;
			$jumlah_biaya_4 = $jumlah_kebutuhan_bahan_4 + $jumlah_alat_4 + $total_nilai_overhead_4 + $total_nilai_diskonto_4;
			$jumlah_biaya_5 = $jumlah_kebutuhan_bahan_5 + $jumlah_alat_5 + $total_nilai_overhead_5 + $total_nilai_diskonto_5;
			$jumlah_biaya_6 = $jumlah_kebutuhan_bahan_6 + $jumlah_alat_6 + $total_nilai_overhead_6 + $total_nilai_diskonto_6;
			$jumlah_biaya_7 = $jumlah_kebutuhan_bahan_7 + $jumlah_alat_7 + $total_nilai_overhead_7 + $total_nilai_diskonto_7;
			$jumlah_biaya = $jumlah_kebutahan_bahan_all + $jumlah_alat + $total_nilai_overhead + $total_nilai_diskonto;
			?>
			<tr class="table-total">
				<th align="right" colspan="4">JUMLAH BAHAN + ALAT + OVERHEAD + DISKONTO</th>
				<th align="right"></th>
				<th align="right"><?php echo number_format($jumlah_biaya_1,0,',','.');?></th>
				<th align="right"></th>
				<th align="right"><?php echo number_format($jumlah_biaya_2,0,',','.');?></th>
				<th align="right"></th>
				<th align="right"><?php echo number_format($jumlah_biaya_3,0,',','.');?></th>
				<th align="right"></th>
				<th align="right"><?php echo number_format($jumlah_biaya_4,0,',','.');?></th>
				<th align="right"></th>
				<th align="right"><?php echo number_format($jumlah_biaya_5,0,',','.');?></th>
				<th align="right"></th>
				<th align="right"><?php echo number_format($jumlah_biaya_6,0,',','.');?></th>
				<th align="right"></th>
				<th align="right"><?php echo number_format($jumlah_biaya_7,0,',','.');?></th>
				<th align="right"></th>
				<th align="right"><?php echo number_format($jumlah_biaya,0,',','.');?></th>
			</tr>
		</table>
		<table width="98%" border="0" cellpadding="5">
			<tr >
				<td width="5%"></td>
				<td width="90%">
					<table width="100%" border="0" cellpadding="2">
						<tr>
							<td align="center" >
								Dibuat Oleh
							</td>
							<td align="center">
								Diperiksa Oleh
							</td>
							<td align="center">
								Disetujui Oleh
							</td>
						</tr>
						<tr class="">
							<td align="center" height="30px">
								<img src="uploads/ttd_novel.png" width="30px">
							</td>
							<td align="center">
								<img src="uploads/ttd_erika.png" width="30px">
							</td>
							<td align="center">
								<img src="uploads/ttd_deddy.png" width="30px">
							</td>
						</tr>
						<tr>
							<td align="center">
								<b><u>Novel Joko Tri Laksono</u><br />
								Ka. Plant</b>
							</td>
							<td align="center">
								<b><u>Erika Sinaga</u><br />
								Dir. Keuangan & SDM</b>
							</td>
							<td align="center">
								<b><u>Deddy Sarwobiso</u><br />
								Direktur Utama</b>
							</td>
						</tr>
					</table>
				</td>
				<td width="5%"></td>
			</tr>
		</table>
	</body>
</html>