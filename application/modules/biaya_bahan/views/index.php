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
            <div class="content">
                <div class="row animated fadeInUp">
                    <div class="col-sm-12 col-lg-12">
                        <div class="panel">
                            <div class="panel-content">
								<h3 class="section-subtitle" style="font-weight:bold; text-transform:uppercase;"><?php echo $row[0]->menu_name; ?></h3>
								<div class="text-left">
									<a href="<?php echo site_url('admin');?>">
									<button style="color:white; background-color:#5bc0de; border:1px solid black; border-radius:10px; line-height:30px;"><b>KEMBALI KE DASHBOARD</b></button></a>
								</div>
                                <div class="tab-content">
								
                                    <div role="tabpanel" class="tab-pane active" id="bisnis">
                                        
										<div role="tabpanel" class="tab-pane active" id="pembelian">
                                        <br />
											<div class="row">
												<div width="100%">
													<div class="panel panel-default">
														<div class="col-sm-5">
															<p><h5><b>Pergerakan Bahan Baku</b></h5></p>
															<a href="#pergerakan_bahan_baku" aria-controls="pergerakan_bahan_baku" role="tab" data-toggle="tab" class="btn btn-primary" style="border-radius:10px; font-weight:bold;">Lihat Laporan</a>
														</div>											
													</div>
												</div>
											</div>
										</div>
                                    </div>

									<!-- Pergerakan Bahan Baku -->
                                    <div role="tabpanel" class="tab-pane" id="pergerakan_bahan_baku">
                                        <div class="col-sm-15">
											<div class="panel panel-default">
                                                <div class="panel-heading">
                                                    <h3 class="panel-title"><b>Pergerakan Bahan Baku</b></h3>
													<a href="biaya_bahan">Kembali</a>
                                                </div>
												<div style="margin: 20px">
													<div class="row">
														<form action="<?php echo site_url('laporan/cetak_pergerakan_bahan_baku');?>" target="_blank">
															<div class="col-sm-3">
																<input type="text" id="filter_date_pergerakan_bahan_baku" name="filter_date" class="form-control dtpicker"  autocomplete="off" placeholder="Filter By Date">
															</div>
															<div class="col-sm-3">
																<button type="submit" class="btn btn-default" style="border-radius:10px; font-weight:bold;">PRINT</button>
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
													<div class="table-responsive" id="pergerakan-bahan-baku">													
													
                    
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

			<!-- Script Pergerakan Bahan Baku -->
			<script type="text/javascript">
			$('#filter_date_pergerakan_bahan_baku').daterangepicker({
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

			$('#filter_date_pergerakan_bahan_baku').on('apply.daterangepicker', function(ev, picker) {
				  $(this).val(picker.startDate.format('DD-MM-YYYY') + ' - ' + picker.endDate.format('DD-MM-YYYY'));
				  PergerakanBahanBaku();
			});


			function PergerakanBahanBaku()
			{
				$('#wait').fadeIn('fast');   
				$.ajax({
					type    : "POST",
					url     : "<?php echo site_url('pmm/reports/pergerakan_bahan_baku'); ?>/"+Math.random(),
					dataType : 'html',
					data: {
						filter_date : $('#filter_date_pergerakan_bahan_baku').val(),
					},
					success : function(result){
						$('#pergerakan-bahan-baku').html(result);
						$('#wait').fadeOut('fast');
					}
				});
			}

			//PergerakanBahanBaku();

        </script>

</body>
</html>