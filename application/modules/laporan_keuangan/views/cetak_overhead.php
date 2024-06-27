<!DOCTYPE html>
<html>
	<head>
	  <title>OVERHEAD</title>

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
	  	table.minimalistBlack {
		  border: 0px solid #000000;
		  width: 100%;
		  text-align: left;
		}
		table.minimalistBlack td, table.minimalistBlack th {
		  border: 1px solid #000000;
		  /*padding: 10px 4px;*/
		}
		table.minimalistBlack tr th {
		  /*font-size: 14px;*/
		  font-weight: bold;
		  color: #000000;
		  text-align: center;
		}
		table tr.table-active{
            background-color: #e69500;
        }
        table tr.table-active2{
            background-color: #b5b5b5;
        }
        table tr.table-active3{
            background-color: #eee;
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
			border-bottom: 1px solid #000;
		}
	  </style>

	</head>
	<body>
		<table width="98%" border="0" cellpadding="3">
			<tr>
				<td align="center">
					<div style="display: block;font-weight: bold;font-size: 11px;">LAPORAN BIAYA UMUM & ADMINISTRATIF</div>
					<div align="center" style="display: block;font-weight:bold; font-size: 11px;">PROYEK BENDUNGAN TIGA DIHAJI</div>
					<div align="center" style="display: block;font-weight:bold; font-size: 11px;">PT. BIA BUMI JAYENDRA</div>
					<div align="center" style="display: block;font-weight:bold; font-size: 11px; text-transform: uppercase;">PERIODE : <?php echo str_replace($search, $replace, $subject);?></div>
				</td>
			</tr>
		</table>
		<br />
		<br />
		<br />
		<table class="table-lap" width="98%" border="0" cellpadding="3">
			<tr class="table-active">
				<th align="center" width="20%"><b>KODE AKUN</b></th>
				<th align="center" width="50%"><b>NAMA AKUN</b></th>
				<th align="center" width="30%" align="right"><b>JUMLAH</b></th>
			</tr>
			<?php
			if(!empty($biaya_langsung_parent)){
				foreach ($biaya_langsung_parent as $key => $bl) {
					?>
					<tr class="table-active4">
						<td width="20%" align="center"><?= $bl['coa_number'] = $this->crud_global->GetField('pmm_coa',array('id'=>$bl['coa_parent']),'coa_number');?></td>
						<td width="50%"><?= $bl['coa'] = $this->crud_global->GetField('pmm_coa',array('id'=>$bl['coa_parent']),'coa');?></td>
						<td width="30%" align="right"></td>
					</tr>
					<?php
				}
			}
			$total_biaya_langsung  = 0;
			if(!empty($biaya_langsung)){
				foreach ($biaya_langsung as $key => $bl) {
					?>
					<tr>
						<td width="20%" align="center"><?= $bl['coa_number'];?></td>
						<td width="2%"></td>
						<td width="48%"><?= $bl['coa'];?></td>
						<td width="30%" align="right"><?php echo number_format($bl['total'],0,',','.');?></td>
					</tr>
					<?php
					$total_biaya_langsung += $bl['total'];	
				}
			}

			if(!empty($biaya_langsung_jurnal_parent)){
				foreach ($biaya_langsung_jurnal_parent as $key => $blj) {
					?>	
					<tr class="table-active4">
						<td width="20%" align="center"><?= $blj['coa_number'] = $this->crud_global->GetField('pmm_coa',array('id'=>$blj['coa_parent']),'coa_number');?></td>
						<td width="50%"><?= $blj['coa'] = $this->crud_global->GetField('pmm_coa',array('id'=>$blj['coa_parent']),'coa');?></td>
						<td width="30%" align="right"></td>
					</tr>
					<?php				
				}
			}

			$total_biaya_langsung_jurnal  = 0;
			$grand_total_biaya_langsung = $total_biaya_langsung;
				if(!empty($biaya_langsung_jurnal)){
					foreach ($biaya_langsung_jurnal as $key => $blj) {
						?>	
						<tr>
							<td width="20%" align="center"><?= $blj['coa_number'];?></td>
							<td width="2%"></td>
							<td width="48%"><?= $blj['coa'];?></td>
							<td width="30%" align="right"><?php echo number_format($blj['total'],0,',','.');?></td>
						</tr>
						<?php
						$total_biaya_langsung_jurnal += $blj['total'];					
					}
			}
			$total_a = $grand_total_biaya_langsung + $total_biaya_langsung_jurnal;
			?>
			<tr class="table-active2">
				<td width="80%" style="padding-left:20px;"><b>Total Biaya Operasional Produksi</b></td>
				<td width="20%" align="right"><b><?php echo number_format($total_a,0,',','.');?></b></td>
			</tr>
		</table>
		<br />
		<br />
		<table width="98%" border="0" cellpadding="15">
			<tr >
				<td width="10%"></td>
				<td width="80%">
					<table width="100%" border="0" cellpadding="2">
						<tr>
							<td align="center" >
								Diperiksa & Disetujui Oleh
							</td>
							<td align="center" >
								Dibuat Oleh
							</td>
						</tr>
						<?php
							$create = $this->db->select('id, unit_head, logistik, admin')
							->from('akumulasi')
							->where("(date_akumulasi between '$start_date' and '$end_date')")
							->order_by('id','desc')->limit(1)
							->get()->row_array();

							$this->db->select('g.admin_group_name, a.admin_ttd');
							$this->db->join('tbl_admin_group g','a.admin_group_id = g.admin_group_id','left');
							$this->db->where('a.admin_id',$create['unit_head']);
							$unit_head = $this->db->get('tbl_admin a')->row_array();

							$this->db->select('g.admin_group_name, a.admin_ttd');
							$this->db->join('tbl_admin_group g','a.admin_group_id = g.admin_group_id','left');
							$this->db->where('a.admin_id',$create['logistik']);
							$logistik = $this->db->get('tbl_admin a')->row_array();

							$this->db->select('g.admin_group_name, a.admin_ttd');
							$this->db->join('tbl_admin_group g','a.admin_group_id = g.admin_group_id','left');
							$this->db->where('a.admin_id',$create['admin']);
							$admin = $this->db->get('tbl_admin a')->row_array();
						?>
						<tr>
							<td align="center" height="40px">
								<!--<img src="<?= $unit_head['admin_ttd']?>" width="90px">-->
							</td>
							<td align="center">
								<!--<img src="<?= $admin['admin_ttd']?>" width="90px">-->
							</td>
						</tr>
						<tr>
							<td align="center" >
								<b><u><?php echo $this->crud_global->GetField('tbl_admin',array('admin_id'=>$create['unit_head']),'admin_name');?></u><br />
								<?= $unit_head['admin_group_name']?></b>
							</td>
							<td align="center" >
								<b><u><?php echo $this->crud_global->GetField('tbl_admin',array('admin_id'=>$create['admin']),'admin_name');?></u><br />
								<?= $admin['admin_group_name']?></b>
							</td>
						</tr>
					</table>
				</td>
				<td width="10%"></td>
			</tr>
		</table>
	</body>
</html>