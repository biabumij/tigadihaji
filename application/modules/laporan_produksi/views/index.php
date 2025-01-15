<!doctype html>
<html lang="en" class="fixed">

<head>
    <?php echo $this->Templates->Header(); ?>
    <script src="https://kit.fontawesome.com/591a1bf2f6.js" crossorigin="anonymous"></script>
	<style type="text/css">
        body {
            font-family: helvetica;
        }
        
		.mytable thead th {
		  background-color:	#e69500;
		  color: #ffffff;
		  text-align: center;
		  vertical-align: middle;
		  padding: 5px;
		}
		
		.mytable tbody td {
		  padding: 5px;
		}
		
		.mytable tfoot td {
		  background-color:	#e69500;
		  color: #FFFFFF;
		  padding: 5px;
		}

        button {
			border: none;
			border-radius: 5px;
			padding: 5px;
			font-size: 12px;
			text-transform: uppercase;
			cursor: pointer;
			color: white;
			background-color: #2196f3;
			box-shadow: 0 0 4px #999;
			outline: none;
		}

		.ripple {
			background-position: center;
			transition: background 0.8s;
		}
		.ripple:hover {
			background: #47a7f5 radial-gradient(circle, transparent 1%, #47a7f5 1%) center/15000%;
		}
		.ripple:active {
			background-color: #6eb9f7;
			background-size: 100%;
			transition: background 0s;
		}

		.ripple2 {
			background-position: center;
			transition: background 0.8s;
            background-color: #5f914a;
            padding: 20px;
            border-radius: 20px;
            font-size: 14px;
		}
		.ripple2:hover {
			background: #6aa84f radial-gradient(circle, transparent 1%, #6aa84f 1%) center/15000%;
		}
		.ripple2:active {
			background-color: #a5ca95;
			background-size: 100%;
			transition: background 0s;
		}
        
    </style>
</head>
<body>
    <div class="wrap">
        <?php echo $this->Templates->PageHeader(); ?>
        <div class="page-body">
            <div class="content">
                <div class="row animated fadeInUp">
                    <div class="col-sm-12 col-lg-12">
                        <div class="panel">
                            <div class="panel-content">
                                <div class="panel-header">
                                    <h3 class="section-subtitle" style="font-weight:bold; text-transform:uppercase;"><?php echo $row[0]->menu_name; ?></h3>
                                    <div class="text-left">
                                        <a href="<?php echo site_url('admin');?>">
                                        <button class="ripple"><b><i class="fa-solid fa-rotate-left"></i> KEMBALI</b></button></a>
                                    </div>
                                </div>
                                <div class="tab-content">
                                    <table width="100%" border="0" style="margin-top:100px; margin-bottom:100px;">
                                        <tr>
                                            <th width="10%" class="text-center">
                                            </th>
                                            <th width="20%" class="text-center">
                                                <a href="<?php echo site_url('admin/laporan_penjualan');?>">
                                                <button class="ripple2"><b></i> LAPORAN<br />PENJUALAN</b></button>
                                                </a>
                                            </th>
                                            <th width="20%" class="text-center">
                                                <a href="<?php echo site_url('admin/laporan_pembelian');?>">
                                                <button class="ripple2"><b></i> LAPORAN<br />PEMBELIAN</b></button>
                                                </a>
                                            </th>
                                            <th width="20%" class="text-center">
                                                <a href="<?php echo site_url('admin/laporan_ev._produksi');?>">
                                                <button class="ripple2"><b></i> LAPORAN<br />EVALUASI</b></button>
                                                </a>
                                            </th>
                                            <th width="20%" class="text-center">
                                                <a href="<?php echo site_url('admin/laporan_rencana_kerja');?>">
                                                <button class="ripple2"><b></i> LAPORAN<br />RENCANA KERJA</b></button>
                                                </a>
                                            </th>
                                            <th width="10%" class="text-center">
                                            </th>
                                        </tr>
                                    </table>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>   
    <?php echo $this->Templates->Footer(); ?>
    <script src="<?php echo base_url(); ?>assets/back/theme/vendor/daterangepicker/moment.min.js"></script>
    <script src="<?php echo base_url(); ?>assets/back/theme/vendor/daterangepicker/daterangepicker.js"></script>
    <link rel="stylesheet" type="text/css" href="<?php echo base_url(); ?>assets/back/theme/vendor/daterangepicker/daterangepicker.css">
    <script src="<?php echo base_url(); ?>assets/back/theme/vendor/bootbox.min.js"></script>
    <script src="<?php echo base_url(); ?>assets/back/theme/vendor/jquery.number.min.js"></script>
    <script type="text/javascript" src="//gyrocode.github.io/jquery-datatables-checkboxes/1.2.12/js/dataTables.checkboxes.min.js"></script>
    <script src="https://kit.fontawesome.com/591a1bf2f6.js" crossorigin="anonymous"></script>

</body>
</html>