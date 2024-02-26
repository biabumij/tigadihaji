<!doctype html>
<html lang="en" class="fixed">

<head>
    <?php echo $this->Templates->Header(); ?>
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
    </style>
</head>

<body>
    <div class="wrap">

        <?php echo $this->Templates->PageHeader(); ?>

        <div class="page-body">
            <?php echo $this->Templates->LeftBar(); ?>
            <div class="content">
                <div class="content-header">
                    <div class="leftside-content-header">
                        <ul class="breadcrumbs">
                            <li><i class="fa fa-bar-chart" aria-hidden="true"></i>Laporan</li>
                            <li><a><?php echo $row[0]->menu_name; ?></a></li>
                        </ul>
                    </div>
                </div>
                <div class="row animated fadeInUp">
                    <div class="col-sm-12 col-lg-12">
                        <div class="panel" style="background: linear-gradient(90deg, #f8f8f8 20%, #dddddd 40%, #f8f8f8 80%);">
                            <div class="panel-content">
								<div class="panel-header">
									<h3 class="section-subtitle"><?php echo $row[0]->menu_name; ?></h3>
								</div>
                                <div class="tab-content">
				
								
                                    <div role="tabpanel" class="tab-pane active" id="bisnis">
                                        
										<div role="tabpanel" class="tab-pane active" id="pembelian">
                                        <br />
											<div class="row">
												<div width="100%">
													<div class="panel panel-default">                                            
                                                        <!--<div class="col-sm-5">
                                                            <p><h5>Biaya (Alat)</h5></p>
                                                            <a href="#biaya_alat" aria-controls="biaya_alat" role="tab" data-toggle="tab" class="btn btn-primary" style="border-radius:10px; font-weight:bold;">Lihat Laporan</a>
														</div>-->
														<div class="col-sm-5">
															<p><h5>Pergerakan Bahan Baku (Solar)</h5></p>
															<a href="#pergerakan_bahan_baku_solar" aria-controls="pergerakan_bahan_baku_solar" role="tab" data-toggle="tab" class="btn btn-primary" style="border-radius:10px; font-weight:bold;">Lihat Laporan</a>
														</div>												
													</div>
												</div>
											</div>
										</div>
                                    </div>

                                    <!-- Laporan Pemakaian Peralatan Produksi -->

                                    <div role="tabpanel" class="tab-pane" id="biaya_alat">
                                        <div class="col-sm-15">
											<div class="panel panel-default">
                                                <div class="panel-heading">
                                                    <h3 class="panel-title">Biaya (Alat)</h3>
													<a href="biaya_alat">Kembali</a>
                                                </div>
												<div style="margin: 20px">
													<div class="row">
														<form action="<?php echo site_url('laporan/cetak_biaya_alat');?>" target="_blank">
															<div class="col-sm-3">
																<input type="text" id="filter_date_biaya_alat" name="filter_date" class="form-control dtpicker"  autocomplete="off" placeholder="Filter By Date">
															</div>
															<div class="col-sm-3">
																<button type="submit" class="btn btn-default" style="border-radius:10px; font-weight:bold;"><i class="fa fa-print"></i>  Print</button>
															</div>
														</form>
														
													</div>
													<br />
													<div id="wait" style=" text-align: center; align-content: center; display: none;">	
														<div>Please Wait</div>
														<div class="fa-3x">
														  <i class="fa fa-spinner fa-spin"></i>
														</div>
													</div>				
													<div class="table-responsive" id="biaya-alat">
													</div>
												</div>
										</div>
										
										</div>
                                    </div>

									<!-- Pergerakan Bahan Baku Solar -->
                                    <div role="tabpanel" class="tab-pane" id="pergerakan_bahan_baku_solar">
                                        <div class="col-sm-15">
										<div class="panel panel-default">
                                                <div class="panel-heading">
                                                    <h3 class="panel-title">Pergerakan Bahan Baku (Solar)</h3>
													<a href="biaya_alat">Kembali</a>
                                                </div>
												<div style="margin: 20px">
													<div class="row">
														<form action="<?php echo site_url('laporan/cetak_pergerakan_bahan_baku_solar');?>" target="_blank">
															<div class="col-sm-3">
																<input type="text" id="filter_date_bahan_baku_solar" name="filter_date" class="form-control dtpicker"  autocomplete="off" placeholder="Filter By Date">
															</div>
															<div class="col-sm-3">
																<button type="submit" class="btn btn-default" style="border-radius:10px; font-weight:bold;"><i class="fa fa-print"></i>  Print</button>
															</div>
														</form>
														
													</div>
													<br />
													<div id="wait" style=" text-align: center; align-content: center; display: none;">	
														<div>Please Wait</div>
														<div class="fa-3x">
														  <i class="fa fa-spinner fa-spin"></i>
														</div>
													</div>				
													<div class="table-responsive" id="pergerakan-bahan-baku-solar">													
													
                    
													</div>
												</div>
										</div>
										
										</div>
                                    </div>
                                    															
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
        
		<!-- Script Biaya Alat -->
		<script type="text/javascript">
			$('#filter_date_biaya_alat').daterangepicker({
            autoUpdateInput : false,
			showDropdowns: true,
            locale: {
              format: 'DD-MM-YYYY'
            },
            ranges: {
               'Today': [moment(), moment()],
               'Yesterday': [moment().subtract(1, 'days'), moment().subtract(1, 'days')],
               'Last 7 Days': [moment().subtract(6, 'days'), moment()],
               'Last 30 Days': [moment().subtract(30, 'days'), moment()],
               'This Month': [moment().startOf('month'), moment().endOf('month')],
               'Last Month': [moment().subtract(1, 'month').startOf('month'), moment().subtract(1, 'month').endOf('month')]
            }
			});

			$('#filter_date_biaya_alat').on('apply.daterangepicker', function(ev, picker) {
				  $(this).val(picker.startDate.format('DD-MM-YYYY') + ' - ' + picker.endDate.format('DD-MM-YYYY'));
				  BiayaAlat();
			});


			function BiayaAlat()
			{
				$('#wait').fadeIn('fast');   
				$.ajax({
					type    : "POST",
					url     : "<?php echo site_url('pmm/reports/biaya_alat'); ?>/"+Math.random(),
					dataType : 'html',
					data: {
						filter_date : $('#filter_date_biaya_alat').val(),
					},
					success : function(result){
						$('#biaya-alat').html(result);
						$('#wait').fadeOut('fast');
					}
				});
			}

			//BiayaAlat();

        </script>

		<!-- Script Pergerakan Bahan Baku Solar -->
		<script type="text/javascript">
			$('#filter_date_bahan_baku_solar').daterangepicker({
            autoUpdateInput : false,
			showDropdowns: true,
            locale: {
              format: 'DD-MM-YYYY'
            },
            ranges: {
               'Today': [moment(), moment()],
               'Yesterday': [moment().subtract(1, 'days'), moment().subtract(1, 'days')],
               'Last 7 Days': [moment().subtract(6, 'days'), moment()],
               'Last 30 Days': [moment().subtract(30, 'days'), moment()],
               'This Month': [moment().startOf('month'), moment().endOf('month')],
               'Last Month': [moment().subtract(1, 'month').startOf('month'), moment().subtract(1, 'month').endOf('month')]
            }
			});

			$('#filter_date_bahan_baku_solar').on('apply.daterangepicker', function(ev, picker) {
				  $(this).val(picker.startDate.format('DD-MM-YYYY') + ' - ' + picker.endDate.format('DD-MM-YYYY'));
				  PergerakanBahanBakuSolar();
			});


			function PergerakanBahanBakuSolar()
			{
				$('#wait').fadeIn('fast');   
				$.ajax({
					type    : "POST",
					url     : "<?php echo site_url('pmm/reports/pergerakan_bahan_baku_solar'); ?>/"+Math.random(),
					dataType : 'html',
					data: {
						filter_date : $('#filter_date_bahan_baku_solar').val(),
					},
					success : function(result){
						$('#pergerakan-bahan-baku-solar').html(result);
						$('#wait').fadeOut('fast');
					}
				});
			}

			//PergerakanBahanBakuSolar();

            </script>

</body>

</html>