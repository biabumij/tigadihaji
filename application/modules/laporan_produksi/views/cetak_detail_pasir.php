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
			font-size: 8px;
		}

		table, th, td {
			border: 0.5px solid white;
		}
		
		table tr.table-judul{
			background-color: #e69500;
			font-weight: bold;
			font-size: 8px;
			color: black;
		}
			
		table tr.table-baris1{
			background-color: #F0F0F0;
			font-size: 8px;
		}

		table tr.table-baris1-bold{
			background-color: #F0F0F0;
			font-size: 8px;
			font-weight: bold;
		}
			
		table tr.table-baris2{
			font-size: 8px;
			background-color: #E8E8E8;
		}
			
		table tr.table-total{
			background-color: #cccccc;
			font-weight: bold;
			font-size: 8px;
			color: black;
		}
	  </style>

	</head>
	<body>
		<div align="center" style="display: block;font-weight: bold;font-size: 11px;">KARTU STOCK PASIR</div>
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
			$date1_ago = date('2020-01-01');
			$date2_ago = date('Y-m-d', strtotime('-1 days', strtotime($date1)));
			$date3_ago = date('Y-m-d', strtotime('-1 months', strtotime($date1)));
			$tanggal_opening_balance = date('Y-m-d', strtotime('-1 days', strtotime($date1)));

			$stock_opname_ago = $this->db->select('cat.volume as volume, cat.total as nilai')
			->from('pmm_remaining_materials_cat cat ')
			->where("(cat.date <= '$tanggal_opening_balance')")
			->where("cat.material_id = 2")
			->where("cat.status = 'PUBLISH'")
			->order_by('date','desc')->limit(1)
			->get()->row_array();

			$stok_volume_lalu = $stock_opname_ago['volume'];
			$stok_nilai_lalu = $stock_opname_ago['nilai'];

			$pembelian = $this->db->select('SUM(prm.display_volume) as volume, SUM(prm.display_price) as nilai')
			->from('pmm_receipt_material prm')
			->join('pmm_purchase_order po', 'prm.purchase_order_id = po.id','left')
			->join('produk p', 'prm.material_id = p.id','left')
			->where("(prm.date_receipt between '$date1' and '$date2')")
			->where("p.kategori_bahan = '2'")
			->where("po.status in ('PUBLISH','CLOSED')")
			->get()->row_array();

			$pembelian_volume = $pembelian['volume'];
			$pembelian_nilai = $pembelian['nilai'];
			
			$pemakaian = $this->db->select('sum(volume) as volume, sum(nilai) as nilai')
			->from('pemakaian_bahan')
			->where("date between '$date1' and '$date2'")
			->where("material_id = 2")
			->where("status = 'PUBLISH'")
			->group_by("id")
			->get()->row_array();

			$pemakaian_volume = $pemakaian['volume'];
			$pemakaian_nilai = $pemakaian['nilai'];

			$stock_volume = ($stok_volume_lalu + $pembelian_volume) - $pemakaian_volume;
			$stock_nilai = ($stok_nilai_lalu + $pembelian_nilai) - $pemakaian_nilai;
			?>
		<table cellpadding="3" width="98%">
			<tr class="table-judul">
				<th align="center" colspan="2">PERSEDIAAN AWAL</th>
				<th align="center" colspan="2">PEMBELIAN</th>
				<th align="center" colspan="2">PEMAKAIAN</th>
				<th align="center" colspan="2">STOCK OPNAME</th>
			</tr>
			<tr class="table-judul">
				<th align="right">VOLUME</th>
				<th align="right">NILAI</th>
				<th align="right">VOLUME</th>
				<th align="right">NILAI</th>
				<th align="right">VOLUME</th>
				<th align="right">NILAI</th>
				<th align="right">VOLUME</th>
				<th align="right">NILAI</th>
			</tr>
			<tr class="table-baris1">
				<td align="right"><?php echo number_format($stok_volume_lalu,2,',','.');?></td>
				<td align="right"><?php echo number_format($stok_nilai_lalu,0,',','.');?></td>
				<td align="right"><?php echo number_format($pembelian_volume,2,',','.');?></td>
				<td align="right"><?php echo number_format($pembelian_nilai,0,',','.');?></td>
				<td align="right"><?php echo number_format($pemakaian_volume,2,',','.');?></td>
				<td align="right"><?php echo number_format($pemakaian_nilai,0,',','.');?></td>
				<td align="right"><?php echo number_format($stock_volume,2,',','.');?></td>
				<td align="right"><?php echo number_format($stock_nilai,0,',','.');?></td>
			</tr>
			
		</table>
	</body>
</html>