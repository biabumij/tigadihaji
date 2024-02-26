<!DOCTYPE html>
<html>
	<head>
	  <title>LAPORAN PENERIMAAN PEMBELIAN</title>
	  
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

		table tr.table-baris2-bold{
			font-size: 8px;
			background-color: #E8E8E8;
			font-weight: bold;
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
		<table width="98%" border="0" cellpadding="15">
			<tr>
				<td width="100%" align="center">
					<div style="display: block;font-weight: bold;font-size: 11px;">LAPORAN PENERIMAAN PEMBELIAN</div>
				    <div style="display: block;font-weight: bold;font-size: 11px;">PROYEK BENDUNGAN TIGA DIHAJI</div>
				    <div style="display: block;font-weight: bold;font-size: 11px;">PT. BIA BUMI JAYENDRA</div>
					<div style="display: block;font-weight: bold;font-size: 11px; text-transform: uppercase;">PERIODE <?php echo str_replace($search, $replace, $subject);?></div>
				</td>
			</tr>
		</table>	
		<table cellpadding="2" width="98%">
			<tr class="table-judul">
                <th align="center" width="5%" rowspan="2">&nbsp; <br />NO.</th>
                <th align="center" width="30%">URAIAN</th>
                <th align="center" width="10%" rowspan="2">&nbsp; <br />SATUAN</th>
                <th align="center" width="15%" rowspan="2">&nbsp; <br />VOLUME</th>
				<th align="center" width="20%" rowspan="2">&nbsp; <br />HARGA SATUAN</th>
                <th align="center" width="20%" rowspan="2">&nbsp; <br />NILAI</th>
            </tr>
			<tr class="table-judul">
				<th align="center">PRODUK</th>
			</tr>
            <?php
			$total_vol = 0;
            if(!empty($data)){
            	foreach ($data as $key => $row) {
            		?>
            		<tr class="table-baris1-bold">
            			<td align="center"><?php echo $key + 1;?></td>
            			<td align="left"><?php echo $row['name'];?></td>
            			<td align="center"><?php echo $row['measure'];?></td>
            			<td align="right"><?php echo $row['volume'];?></td>
						<td align="right"></td>
            			<td align="right">
            				<table cellpadding="0" width="100%" border="0">
		    					<tr>
		    						<td width="20%" align="left">Rp.</td>
		    						<td width="80%" align="right"><?php echo $row['total_price'];?></td>
		    					</tr>
		    				</table>
            			</td>
						<?php
						$total_vol += str_replace(['.', ','], ['', '.'], $row['volume']);
						?>
            		</tr>
            		<?php
            		foreach ($row['mats'] as $mat) {
            			?>
            			<tr class="table-baris1">
	            			<td align="center"></td>
	            			<td align="left">&nbsp;&nbsp;&nbsp;<?php echo $mat['nama_produk'];?></td>
	            			<td align="center"><?php echo $mat['measure'];?></td>
	            			<td align="right"><?php echo $mat['volume'];?></td>
							<td align="right">
	            				<table cellpadding="0" width="100%" border="0">
			    					<tr>
			    						<td width="20%" align="left">Rp.</td>
			    						<td width="80%" align="right"><?php echo $mat['price'];?></td>
			    					</tr>
			    				</table>
	            			</td>
	            			<td align="right">
	            				<table cellpadding="0" width="100%" border="0">
			    					<tr>
			    						<td width="20%" align="left">Rp.</td>
			    						<td width="80%" align="right"><?php echo $mat['total_price'];?></td>
			    					</tr>
			    				</table>
	            			</td>
	            		</tr>
            			<?php
            		}
            		
            		
            	}
            }else {
            	?>
            	<tr>
            		<td width="100%" colspan="7" align="center">Tidak Ada Data</td>
            	</tr>
            	<?php
            }
            ?>	
            <tr class="table-total">
				<th align="right" colspan="3">TOTAL</th>
				<th align="right"><?php echo number_format($total_vol,2,',','.');?></th>
				<th align="right"></th>
            	<th align="right">
            		<table cellpadding="0" width="100%" border="0">
    					<tr>
    						<td width="20%" align="left">Rp.</td>
    						<td width="80%" align="right"><?php echo number_format($total,0,',','.');?></td>
    					</tr>
    				</table>
            	</th>
            </tr>   
		</table>
		<br /><br /><br /><br /><br /><br /><br /><br /><br /><br /><br /><br /><br />
		<table width="98%">
			<tr >
				<td width="5%"></td>
				<td width="90%">
					<table width="100%" border="0" cellpadding="2">
						<tr>
							<td align="center">
								Disetujui Oleh
							</td>
							<td align="center" colspan="2">
								Diperiksa Oleh
							</td>
							<td align="center" >
								Dibuat Oleh
							</td>	
						</tr>
						<tr class="">
							<?php
								$create = $this->db->select('*')
								->from('akumulasi')
								->where("(date_akumulasi = '$end_date')")
								->order_by('id','desc')->limit(1)
								->get()->row_array();

                                $this->db->select('g.admin_group_name, a.admin_ttd, a.admin_name');
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
							<td align="center" height="55px">
								<img src="<?= $unit_head['admin_ttd']?>" width="70px">
							</td>
							<td align="center">
								
							</td>
							<td align="center">
								<img src="<?= $unit_head['admin_ttd']?>" width="70px">
							</td>
							<td align="center">
								<img src="<?= $logistik['admin_ttd']?>" width="70px">
								<!--<img src="<?= $admin['admin_ttd']?>" width="20px">-->
							</td>
						</tr>
						<tr>
							<td align="center">
								<b><u><?= $unit_head['admin_name']?></u><br />
								Kepala Unit Proyek</b>
							</td>
							<td align="center">
								<b><u></u><br />
								M. Keu & SDM</b>
							</td>
							<td align="center">
								<b><u>Agustinus P</u><br />
								M. Teknik</b>
							</td>
							<td align="center">
								<b><u>Agustinus P</u><br />
								Pj. Logistik</b>
							</td>
						</tr>
					</table>
				</td>
				<td width="5%"></td>
			</tr>
		</table>
	</body>
</html>