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
			->group_by('prm.material_id')
			->get()->row_array();
		
			$pembelian_volume_semen = $pembelian_semen['volume'];
			$pembelian_nilai_semen = $pembelian_semen['nilai'];
			$pembelian_harga_semen = (round($pembelian_volume_semen,2)!=0)?$pembelian_nilai_semen / round($pembelian_volume_semen,2) * 1:0;

			$total_stok_volume_semen = $stok_volume_semen_lalu + $pembelian_volume_semen;
			$total_stok_nilai_semen = $stok_nilai_semen_lalu + $pembelian_nilai_semen;

			$stock_opname_semen_now = $this->db->select('cat.volume as volume, cat.total as nilai, cat.pemakaian_custom, cat.reset, cat.reset_pemakaian')
			->from('pmm_remaining_materials_cat cat ')
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
			->group_by('prm.material_id')
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
			->group_by('prm.material_id')
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
			->group_by('prm.material_id')
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

			$total_volume_realisasi = $pemakaian_volume_semen + $pemakaian_volume_pasir + $pemakaian_volume_1020 + $pemakaian_volume_2030;
			$total_nilai_realisasi = $pemakaian_nilai_semen + $pemakaian_nilai_pasir + $pemakaian_nilai_1020 + $pemakaian_nilai_2030;
			
			$evaluasi_volume_a = round($volume_a - $pemakaian_volume_semen,2);
			$evaluasi_volume_b = round($volume_b - $pemakaian_volume_pasir,2);
			$evaluasi_volume_c = round($volume_c - $pemakaian_volume_1020,2);
			$evaluasi_volume_d = round($volume_d - $pemakaian_volume_2030,2);

			$evaluasi_nilai_a = round($nilai_a - $pemakaian_nilai_semen,0);
			$evaluasi_nilai_b = round($nilai_b - $pemakaian_nilai_pasir,0);
			$evaluasi_nilai_c = round($nilai_c - $pemakaian_nilai_1020,0);
			$evaluasi_nilai_d = round($nilai_d - $pemakaian_nilai_2030,0);

			$total_volume_evaluasi = round($total_volume_komposisi - $total_volume_realisasi,2);
			$total_nilai_evaluasi = round($evaluasi_nilai_a + $evaluasi_nilai_b + $evaluasi_nilai_c + $evaluasi_nilai_d,0);
	        ?>
			
			<tr class="table-judul">
				<th width="5%" align="center" rowspan="2">&nbsp;<br/>NO.</th>
				<th width="12%" align="center" rowspan="2">&nbsp;<br/>URAIAN</th>
				<th width="7%" align="center" rowspan="2">&nbsp;<br/>SATUAN</th>
				<th width="29%" align="center" colspan="3">KOMPOSISI</th>
				<th width="29%" align="center" colspan="3">REALISASI</th>
				<th width="19%" align="center" colspan="2">DEVIASI</th>
	        </tr>
			<tr class="table-judul">
				<th width="8%" align="right">VOLUME</th>
				<th width="9%" align="right">HARSAT</th>
				<th width="12%" align="right">NILAI</th>
				<th width="8%" align="right">VOLUME</th>
				<th width="9%" align="right">HARSAT</th>
				<th width="12%" align="right">NILAI</th>
				<th width="8%" align="right">VOLUME</th>
				<th width="11%" align="right">NILAI</th>
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
				<th align="right"><?php echo number_format($pemakaian_volume_semen,2,',','.');?></th>
				<th align="right"><?php echo number_format($pemakaian_nilai_semen / $pemakaian_volume_semen,0,',','.');?></th>
				<th align="right"><?php echo number_format($pemakaian_nilai_semen,0,',','.');?></th>
				<th align="right" style="<?php echo $styleColorA ?>"><?php echo $evaluasi_volume_a < 0 ? "(".number_format(-$evaluasi_volume_a,2,',','.').")" : number_format($evaluasi_volume_a,2,',','.');?></th>
				<th align="right" style="<?php echo $styleColorAA ?>"><?php echo $evaluasi_nilai_a < 0 ? "(".number_format(-$evaluasi_nilai_a,0,',','.').")" : number_format($evaluasi_nilai_a,0,',','.');?></th>
	        </tr>
			<tr class="table-baris1">
				<th align="center" style="vertical-align:middle">2.</th>			
				<th align="left">Pasir</th>
				<th align="center">M3</th>
				<th align="right"><?php echo number_format($volume_b,2,',','.');?></th>
				<th align="right"><?php echo number_format($price_b,2,',','.');?></th>
				<th align="right"><?php echo number_format($nilai_b,0,',','.');?></th>
				<th align="right"><?php echo number_format($pemakaian_volume_pasir,2,',','.');?></th>
				<th align="right"><?php echo number_format($pemakaian_nilai_pasir / $pemakaian_volume_pasir,0,',','.');?></th>
				<th align="right"><?php echo number_format($pemakaian_nilai_pasir,0,',','.');?></th>
				<th align="right" style="<?php echo $styleColorB ?>"><?php echo $evaluasi_volume_b < 0 ? "(".number_format(-$evaluasi_volume_b,2,',','.').")" : number_format($evaluasi_volume_b,2,',','.');?></th>
				<th align="right" style="<?php echo $styleColorBB ?>"><?php echo $evaluasi_nilai_b < 0 ? "(".number_format(-$evaluasi_nilai_b,0,',','.').")" : number_format($evaluasi_nilai_b,0,',','.');?></th>
	        </tr>
			<tr class="table-baris1">
				<th align="center" style="vertical-align:middle">3.</th>			
				<th align="left">Batu Split 10-20</th>
				<th align="center">M3</th>
				<th align="right"><?php echo number_format($volume_c,2,',','.');?></th>
				<th align="right"><?php echo number_format($price_c,0,',','.');?></th>
				<th align="right"><?php echo number_format($nilai_c,0,',','.');?></th>
				<th align="right"><?php echo number_format($pemakaian_volume_1020,2,',','.');?></th>
				<th align="right"><?php echo number_format($pemakaian_nilai_1020 / $pemakaian_volume_1020,0,',','.');?></th>
				<th align="right"><?php echo number_format($pemakaian_nilai_1020,0,',','.');?></th>
				<th align="right" style="<?php echo $styleColorC ?>"><?php echo $evaluasi_volume_c < 0 ? "(".number_format(-$evaluasi_volume_c,2,',','.').")" : number_format($evaluasi_volume_c,2,',','.');?></th>
				<th align="right" style="<?php echo $styleColorCC ?>"><?php echo $evaluasi_nilai_c < 0 ? "(".number_format(-$evaluasi_nilai_c,0,',','.').")" : number_format($evaluasi_nilai_c,0,',','.');?></th>
	        </tr>
			<tr class="table-baris1">
				<th align="center" style="vertical-align:middle">4.</th>			
				<th align="left">Batu Split 20-30</th>
				<th align="center">M3</th>
				<th align="right"><?php echo number_format($volume_d,2,',','.');?></th>
				<th align="right"><?php echo number_format($price_d,2,',','.');?></th>
				<th align="right"><?php echo number_format($nilai_d,0,',','.');?></th>
				<th align="right"><?php echo number_format($pemakaian_volume_2030,2,',','.');?></th>
				<th align="right"><?php echo number_format($pemakaian_nilai_2030 / $pemakaian_volume_2030,0,',','.');?></th>
				<th align="right"><?php echo number_format($pemakaian_nilai_2030,0,',','.');?></th>
				<th align="right" style="<?php echo $styleColorD ?>"><?php echo $evaluasi_volume_d < 0 ? "(".number_format(-$evaluasi_volume_d,2,',','.').")" : number_format($evaluasi_volume_d,2,',','.');?></th>
				<th align="right" style="<?php echo $styleColorDD ?>"><?php echo $evaluasi_nilai_d < 0 ? "(".number_format(-$evaluasi_nilai_d,0,',','.').")" : number_format($evaluasi_nilai_d,0,',','.');?></th>
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
				<th align="right" class="table-border-spesial-kanan" style="<?php echo $styleColorEE ?>"><?php echo $total_nilai_evaluasi < 0 ? "(".number_format(-$total_nilai_evaluasi,0,',','.').")" : number_format($total_nilai_evaluasi,0,',','.');?></th>
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
							<td align="center">
								
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