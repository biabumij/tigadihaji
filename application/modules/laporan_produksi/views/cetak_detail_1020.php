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
		<div align="center" style="display: block;font-weight: bold;font-size: 11px;">RINCIAN PEMAKAIAN BATU SPLIT 10 - 20</div>
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

		?>
		<table width="98%" style="font-size:7px;">
			<tr>
				<th class="text-left" width="30%" style="background-color:grey; color:white;">&nbsp;&nbsp;Stok 1020 Bulan Lalu</th>
				<th class="text-right" width="15%" style="background-color:grey; color:white;"><?php echo number_format($stok_volume_1020_lalu,2,',','');?> (Ton)</th>
				<th class="text-right" width="15%" style="background-color:grey; color:white;"><?php echo number_format($stok_harsat_1020_lalu,0,',','.');?></th>
				<th class="text-right" width="15%" style="background-color:grey; color:white;"><?php echo number_format($stok_nilai_1020_lalu,0,',','.');?>&nbsp;&nbsp;</th>
			</tr>
			<tr>
				<th class="text-left" style="background-color:yellow; color:black;">&nbsp;&nbsp;Pembelian 1020 Bulan Ini</th>
				<th class="text-right" style="background-color:yellow; color:black;"><?php echo number_format($pembelian_volume_1020,2,',','');?> (Ton)</th>
				<th class="text-right" style="background-color:yellow; color:black;"><?php echo number_format($pembelian_harga_1020,0,',','.');?></th>
				<th class="text-right" style="background-color:yellow; color:black;"><?php echo number_format($pembelian_nilai_1020,0,',','.');?>&nbsp;&nbsp;</th>
			</tr>
			<tr>
				<th class="text-left" style="background-color:grey; color:white;">&nbsp;&nbsp;Total Stok 1020 Bulan Ini</th>
				<th class="text-right" style="background-color:grey; color:white;"><?php echo number_format($total_stok_volume_1020,2,',','');?> (Ton)</th>
				<th class="text-right" style="background-color:grey; color:white;"></th>
				<th class="text-right" style="background-color:grey; color:white;"><?php echo number_format($total_stok_nilai_1020,0,',','.');?>&nbsp;&nbsp;</th>
			</tr>
			<tr>
				<th class="text-left" style="background-color:yellow; color:black;">&nbsp;&nbsp;Stok 1020 Akhir</th>
				<th class="text-right" style="background-color:yellow; color:black;"><?php echo number_format($volume_stock_opname_1020_now,2,',','');?> (Ton)</th>
				<th class="text-right" style="background-color:yellow; color:black;"></th>
				<th class="text-right" style="background-color:yellow; color:black;"><?php echo number_format($nilai_stock_opname_1020_now,0,',','.');?>&nbsp;&nbsp;</th>
			</tr>
			<tr>
				<th class="text-left" style="background-color:blue; color:white;">&nbsp;&nbsp;Pemakaian 1020 Bulan Ini</th>
				<th class="text-right" style="background-color:blue; color:white;"><?php echo number_format($pemakaian_volume_1020,2,',','');?> (Ton)</th>
				<th class="text-right" style="background-color:blue; color:white;"><?php echo number_format($pemakaian_harsat_1020,0,',','.');?></th>
				<th class="text-right" style="background-color:blue; color:white;"><?php echo number_format($pemakaian_nilai_1020,0,',','.');?>&nbsp;&nbsp;</th>
			</tr>
		</table>
	</body>
</html>