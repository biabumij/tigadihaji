<!doctype html>
<html lang="en" class="fixed">
<head>
    <?php echo $this->Templates->Header();?>
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
    </style>
</head>

<body>
<div class="wrap">
    
    <?php echo $this->Templates->PageHeader();?>

    <div class="page-body">
        <div class="content">
            <div class="row animated fadeInUp">
                <div class="col-sm-12 col-lg-12">
                    <div class="panel">
                        <div class="panel-header">
                            <h3 class="section-subtitle">
                            	<b>PRODUK</b>
                        	</h3>
                            <div class="text-left">
                                <a href="<?php echo site_url('admin');?>">
                                <button class="ripple"><b><i class="fa-solid fa-rotate-left"></i> KEMBALI</b></button></a>
                            </div>
                        </div>
                        <div class="panel-content">
                            <ul class="nav nav-tabs" role="tablist" style="border-bottom:2px solid #e69500; padding-bottom:10px;">
                                <li role="presentation" class="active"><a href="#bahanbaku" aria-controls="bahanbaku" role="tab" data-toggle="tab" style="border-radius:5px; font-weight:bold;">BAHAN BAKU</a></li>
                                <li role="presentation"><a href="#betonreadymix" aria-controls="betonreadymix" role="tab" data-toggle="tab" style="border-radius:5px; font-weight:bold;">BETON READY MIX</a></li>
                                <li role="presentation"><a href="#jasa" aria-controls="jasa" role="tab" data-toggle="tab" style="border-radius:5px; font-weight:bold;">JASA</a></li>
                                <li role="presentation"><a href="#peralatan" aria-controls="peralatan" role="tab" data-toggle="tab" style="border-radius:5px; font-weight:bold;">PERALATAN</a></li>
                            </ul>
                            <div class="tab-content">
                                <div role="tabpanel" class="tab-pane active" id="bahanbaku">
                                    <br />
                                    <button style="background-color:#88b93c; border-radius:5px; line-height:30px;"><a href="<?php echo site_url('produk/buat_baru'); ?>"><b style="color:white;">BUAT PRODUK</b></a></button>
                                    <br /><br />
                                    <div class="table-responsive">
                                        <table class="table table-striped table-hover table-center" id="table-bahanbaku" style="width:100%;">
                                            <thead>
                                                <tr>
                                                    <th>No.</th>
                                                    <th>Nama</th>
                                                    <th>Satuan</th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                               
                                            </tbody>
                                        </table>
                                    </div>
                                </div>
                            
                                
                                <div role="tabpanel" class="tab-pane" id="betonreadymix">
                                    <br />
                                    <button style="background-color:#88b93c; border-radius:5px; line-height:30px;"><a href="<?php echo site_url('produk/buat_baru'); ?>"><b style="color:white;">BUAT PRODUK</b></a></button>
                                	<br /><br />
                                    <div class="table-responsive">
                                        <table class="table table-striped table-hover table-center" id="table-betonreadymix" style="width:100%;">
                                            <thead>
                                                <tr>
                                                    <th>No.</th>
                                                    <th>Nama</th>
                                                    <th>Satuan</th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                               
                                            </tbody>
                                        </table>
                                    </div>
                                </div>
                                
                                <div role="tabpanel" class="tab-pane" id="jasa">
                                    <br />
                                    <button style="background-color:#88b93c; border-radius:5px; line-height:30px;"><a href="<?php echo site_url('produk/buat_baru'); ?>"><b style="color:white;">BUAT PRODUK</b></a></button>
                                	<br /><br />
                                    <div class="table-responsive">
                                        <table class="table table-striped table-hover table-center" id="table-jasa" style="width:100%;">
                                            <thead>
                                                <tr>
                                                    <th>No.</th>
                                                    <th>Nama</th>
                                                    <th>Satuan</th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                               
                                            </tbody>
                                        </table>
                                    </div>
                                </div>
                                
                                <div role="tabpanel" class="tab-pane" id="peralatan">
                                    <br />
                                    <button style="background-color:#88b93c; border-radius:5px; line-height:30px;"><a href="<?php echo site_url('produk/buat_baru'); ?>"><b style="color:white;">BUAT PRODUK</b></a></button>
                                	<br /><br />
                                    <div class="table-responsive">
                                        <table class="table table-striped table-hover table-center" id="table-peralatan" style="width:100%;">
                                            <thead>
                                                <tr>
                                                    <th>No.</th>
                                                    <th>Nama</th>
                                                    <th>Satuan</th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                               
                                            </tbody>
                                        </table>
                                    </div>
                                </div>
                        
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        
    </div>
</div>
    

    <script type="text/javascript">
        var form_control = '';
    </script>
    
	<?php echo $this->Templates->Footer();?>
	<script src="<?php echo base_url();?>assets/back/theme/vendor/bootbox.min.js"></script>
    <script src="<?php echo base_url();?>assets/back/theme/vendor/jquery.number.min.js"></script>
    <script src="https://kit.fontawesome.com/591a1bf2f6.js" crossorigin="anonymous"></script>

    <script type="text/javascript">
        $('input.numberformat').number( true, 4,',','.' );
        $('input#contract_price, input#price_value, .total').number( true, 2,',','.' );
      
        var table_bahanbaku = $('#table-bahanbaku').DataTable( {"bAutoWidth": false,
            ajax: {
                processing: true,
                serverSide: true,
                url: '<?php echo site_url('produk/table_product');?>',
                type : 'POST',
                data: function ( d ) {
                    d.tipe = 1
                }
            },
            columns: [
                { "data": "no" },
                { "data": "nama_produk" },
                { "data": "satuan" },
            ],
            "columnDefs": [
                { "width": "5%", "targets": 0, "className": 'text-center'},
            ],
            responsive: true,
        });
        
        var table_betonreadymix = $('#table-betonreadymix').DataTable( {"bAutoWidth": false,
            ajax: {
                processing: true,
                serverSide: true,
                url: '<?php echo site_url('produk/table_product');?>',
                type : 'POST',
                data: function ( d ) {
                    d.tipe = 2
                }
            },
            columns: [
                { "data": "no" },
                { "data": "nama_produk" },
                { "data": "satuan" },
            ],
            "columnDefs": [
                { "width": "5%", "targets": 0, "className": 'text-center'},
            ],
            responsive: true,
        });
        
        var table_jasa = $('#table-jasa').DataTable( {"bAutoWidth": false,
            ajax: {
                processing: true,
                serverSide: true,
                url: '<?php echo site_url('produk/table_product');?>',
                type : 'POST',
                data: function ( d ) {
                    d.tipe = 4
                }
            },
            columns: [
                { "data": "no" },
                { "data": "nama_produk" },
                { "data": "satuan" },
            ],
            "columnDefs": [
                { "width": "5%", "targets": 0, "className": 'text-center'},
            ],
            responsive: true,
        });
        
        
        var table_peralatan = $('#table-peralatan').DataTable( {"bAutoWidth": false,
            ajax: {
                processing: true,
                serverSide: true,
                url: '<?php echo site_url('produk/table_product');?>',
                type : 'POST',
                data: function ( d ) {
                    d.tipe = 5
                }
            },
            columns: [
                { "data": "no" },
                { "data": "nama_produk" },
                { "data": "satuan" },
            ],
            "columnDefs": [
                { "width": "5%", "targets": 0, "className": 'text-center'},
            ],
            responsive: true,
        });

    </script>

</body>
</html>