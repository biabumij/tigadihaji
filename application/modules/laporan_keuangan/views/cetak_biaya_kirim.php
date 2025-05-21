<!DOCTYPE html>
<html>
	<head>
	  <title>BUA</title>
	  
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
		table tr.table-active{
            background-color: #e69500;
        }
        table tr.table-active2{
            background-color: #b5b5b5;
        }
        table tr.table-active3{
            background-color: #eeeeee;
        }
		table tr.table-active4{
            font-weight: bold;
        }
		hr{
			margin-top:0;
			margin-bottom:30px;
		}
		h3{
			margin-top:0;
		}
		.table-lap tr td, .table-lap tr th{
			border-bottom: 1px solid #000000;
		}
	  </style>

	</head>
	<body>
		<div align="center" style="display: block;font-weight: bold;font-size: 11px;">TRANSAKSI</div>
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
		
		<table class="table-lap" width="98%" border="0" cellpadding="3">
			<?php
			$row_biaya = $this->db->select('b.*, c.coa_number, c.coa, pdb.deskripsi, pdb.jumlah as total')
			->from('pmm_biaya b')
			->join('pmm_detail_biaya pdb', 'b.id = pdb.biaya_id','left')
			->join('pmm_coa c','pdb.akun = c.id','left')
			->where("b.tanggal_transaksi between '$date1' and '$date2'")
			->where("pdb.akun = 180")
			->group_by('pdb.id')
			->order_by('b.tanggal_transaksi','asc')
			->order_by('b.created_on','asc')
			->get()->result_array();

			$row_jurnal = $this->db->select('j.*, c.coa_number, c.coa, pdj.deskripsi, pdj.debit as total')
			->from('pmm_jurnal_umum j')
			->join('pmm_detail_jurnal pdj','j.id = pdj.jurnal_id','left')
			->where("j.tanggal_transaksi between '$date1' and '$date2'")
			->join('pmm_coa c','pdj.akun = c.id','left')
			->where("pdj.akun = 180")
			->group_by('pdj.id')
			->order_by('j.tanggal_transaksi','asc')
			->order_by('j.created_on','asc')
			->get()->result_array();

			$total_biaya = array_merge($row_biaya,$row_jurnal);

			function sortByOrder($row_biaya, $row_jurnal) {
				if ($row_biaya['tanggal_transaksi'] > $row_jurnal['tanggal_transaksi']) {
					return 1;
				} elseif ($row_biaya['tanggal_transaksi'] < $row_jurnal['tanggal_transaksi']) {
					return -1;
				}
				return 0;
			}
			
			usort($total_biaya, 'sortByOrder');
			?>
			<tr class="table-active">
				<th align="center" width="6%"><b>TANGGAL</b></th>
				<th align="center" width="20%"><b>NOMOR BUKTI</b></th>
				<th align="center" width="10%"><b>PENERIMA</b></th>
				<th align="center" width="24%"><b>URAIAN</b></th>
				<th align="center" width="10%"><b>KODE AKUN</b></th>
				<th align="center" width="10%"><b>NAMA AKUN</b></th>
				<th align="center" width="10%"><b>KATEGORI AKUN</b></th>
				<th align="right" width="10%"><b>JUMLAH</b></th>
			</tr>
			<?php
			if(!empty($total_biaya)){
				foreach ($total_biaya as $key => $x) {
					?>
					<tr>
						<td align="center"><?= date('d-m-Y',strtotime($x["tanggal_transaksi"])) ?></td>
						<td align="left"><?= $x['nomor_transaksi'];?></td>
						<td align="left"><?= $this->crud_global->GetField('penerima',array('id'=>$x['penerima']),'nama');?></td>
						<td align="left"><?= $x['deskripsi'];?></td>
						<td align="center"><?= $x['coa_number'];?></td>
						<td align="center"><?= $x['coa'];?></td>
						<td align="center"><?= $x['transaksi'];?></td>
						<td align="right"><?php echo number_format($x['total'],0,',','.');?></td>
					</tr>
					<?php
					$total += $x['total'];
				}
			}
			?>
			<tr class="table-active2">
				<td colspan="7" align="right"><b>TOTAL</b></td>
				<td align="right"><b><?php echo number_format($total,0,',','.');?></b></td>
			</tr>
		</table>
	</body>
</html>