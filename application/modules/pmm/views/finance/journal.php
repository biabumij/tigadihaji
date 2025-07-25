<!doctype html>
<html lang="en" class="fixed">
<head>
    <?php echo $this->Templates->Header();?>
    <style>
        body {
            font-family: helvetica;
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

<link type="text/css" href="//gyrocode.github.io/jquery-datatables-checkboxes/1.2.12/css/dataTables.checkboxes.css" rel="stylesheet" />

<body>
<div class="wrap">
    
    <?php echo $this->Templates->PageHeader();?>

    <div class="page-body">
        <div class="content">
            <div class="row animated fadeInUp">
                <div class="col-sm-12 col-lg-12">
                    <div class="panel">
                        <div class="panel-header">
                            <h3 class="section-subtitle"><b>JURNAL UMUM</b></h3>
                            <div class="text-left">
                                <a href="<?php echo site_url('admin');?>">
                                <button class="ripple"><b><i class="fa-solid fa-rotate-left"></i> KEMBALI</b></button></a>
                            </div>
                        </div>
                        <div class="panel-content">
                            <?php
                            $admin_id = $this->session->userdata('admin_id');
                            $approval = $this->db->select('*')
                            ->from('tbl_admin')
                            ->where("admin_id = $admin_id ")
                            ->get()->row_array();
                            $akun_pusat =  $approval['akun_pusat'];
                            $akun_proyek =  $approval['akun_proyek'];
                            ?>
                            <ul class="nav nav-tabs" role="tablist" style="border-bottom:2px solid #e69500; padding-bottom:10px;">
                                <?php
                                if($akun_pusat == 1){
                                ?>
                                <li role="presentation" class="active"><a href="#home" aria-controls="home" role="tab" data-toggle="tab" style="border-radius:5px; font-weight:bold;">JURNAL UMUM</a></li>
                                <?php
                                }
                                ?>

                                <?php
                                if($akun_proyek == 1){
                                ?>
                                <li role="presentation" class="active"><a href="#home_2" aria-controls="home_2" role="tab" data-toggle="tab" style="border-radius:5px; font-weight:bold; text-transform:uppercase;">BULAN INI</a></li>
                                <li role="presentation"><a href="#home_3" aria-controls="home_3" role="tab" data-toggle="tab" style="border-radius:5px; font-weight:bold; text-transform:uppercase;">SEMUA</a></li>
                                <?php
                                }
                                ?>
                            </ul>
                            <div class="tab-content">
                                <?php
                                if($akun_pusat == 1){
                                ?>
                                <div role="tabpanel" class="tab-pane active" id="home">
                                    <br />
                                    <div class="col-sm-3">
                                        <input type="text" id="filter_date_jurnal" name="filter_date" class="form-control dtpickerange" autocomplete="off" placeholder="Filter By Date">
                                    </div>
                                    <button style="background-color:#88b93c; border-radius:5px; line-height:30px;"><a href="<?php echo site_url('pmm/jurnal_umum/tambah_jurnal'); ?>"><b style="color:white;">BUAT JURNAL UMUM</b></a></button>
                                    <br />
                                    <br />
                                    <h3 class="text-center"></h3>
                                    <div class="table-responsive">
                                        <table class="table table-striped table-hover" id="table_jurnal" style="width:100%">
                                            <thead>
                                                <tr>
                                                    <th>NO.</th>
                                                    <th>TANGGAL</th>
                                                    <th>NOMOR TRANSAKSI</th>
                                                    <th>DEBIT</th>
                                                    <th>KREDIT</th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                            
                                            </tbody>
                                        </table>
                                    </div>
                                </div>
                                <?php
                                }
                                ?>

                                <?php
                                if($akun_proyek == 1){
                                ?>
                                <div role="tabpanel" class="tab-pane active" id="home_2">
                                    <br />
                                    <div class="col-sm-3">
                                        <input type="text" id="filter_date_jurnal_2" name="filter_date" class="form-control dtpickerange" autocomplete="off" placeholder="Filter By Date">
                                    </div>
                                    <button style="background-color:#88b93c; border-radius:5px; line-height:30px;"><a href="<?php echo site_url('pmm/jurnal_umum/tambah_jurnal'); ?>"><b style="color:white;">BUAT JURNAL UMUM</b></a></button>
                                    <br />
                                    <br />
                                    <h3 class="text-center"></h3>
                                    <div class="table-responsive">
                                        <table class="table table-striped table-hover" id="table_jurnal_2" style="width:100%">
                                            <thead>
                                                <tr>
                                                    <th>NO.</th>
                                                    <th>TANGGAL</th>
                                                    <th>NOMOR TRANSAKSI</th>
                                                    <th>DEBIT</th>
                                                    <th>KREDIT</th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                            
                                            </tbody>
                                        </table>
                                    </div>
                                </div>

                                <div role="tabpanel" class="tab-pane" id="home_3">
                                    <br />
                                    <div class="col-sm-3">
                                        <input type="text" id="filter_date_jurnal_3" name="filter_date" class="form-control dtpickerange" autocomplete="off" placeholder="Filter By Date">
                                    </div>
                                    <button style="background-color:#88b93c; border-radius:5px; line-height:30px;"><a href="<?php echo site_url('pmm/jurnal_umum/tambah_jurnal'); ?>"><b style="color:white;">BUAT JURNAL UMUM</b></a></button>
                                    <br />
                                    <br />
                                    <h3 class="text-center"></h3>
                                    <div class="table-responsive">
                                        <table class="table table-striped table-hover" id="table_jurnal_3" style="width:100%">
                                            <thead>
                                                <tr>
                                                    <th>NO.</th>
                                                    <th>TANGGAL</th>
                                                    <th>NOMOR TRANSAKSI</th>
                                                    <th>DEBIT</th>
                                                    <th>KREDIT</th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                            
                                            </tbody>
                                        </table>
                                    </div>
                                </div>
                                <?php
                                }
                                ?>
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
    
	<?php echo $this->Templates->Footer(); ?>

    <script src="<?php echo base_url(); ?>assets/back/theme/vendor/jquery.number.min.js"></script>
    
    <script src="<?php echo base_url(); ?>assets/back/theme/vendor/bootbox.min.js"></script>
    <link rel="stylesheet" type="text/css" href="https://cdn.datatables.net/select/1.3.1/css/select.dataTables.min.css">
    <script type="text/javascript" src="https://cdn.datatables.net/select/1.3.1/js/dataTables.select.min.js"></script>
    <script src="<?php echo base_url(); ?>assets/back/theme/vendor/daterangepicker/moment.min.js"></script>
    <script src="<?php echo base_url(); ?>assets/back/theme/vendor/daterangepicker/daterangepicker.js"></script>
    <link rel="stylesheet" type="text/css" href="<?php echo base_url(); ?>assets/back/theme/vendor/daterangepicker/daterangepicker.css">
    <script src="https://kit.fontawesome.com/591a1bf2f6.js" crossorigin="anonymous"></script>
	
    <script>
    let po = [];
    let dataPo = 0;
    function cekPO(th){
        let lengthDataChecked = $('input:checkbox:checked').length;
        if(lengthDataChecked == 0){
            po = [];
        }
        if($(th).is(":checked")){
            po.push($(th).attr('data-po'));
            if($(th).attr('data-po') != po[0] ){
                $(th).prop("checked", false);
                alert("Nomor PO yang anda pilih tidak sama !");
            }
        }
        else if($(th).is(":not(:checked)")){
                
        }
    }
    // }, 1000);
    </script>
    <script type="text/javascript">

        $('input#contract').number( true, 2,',','.' );
        $('td.numberformat').number( true, 2,',','.' );
		
		$('.dtpicker').daterangepicker({
        singleDatePicker: true,
        locale: {
            format: 'DD-MM-YYYY'
        }
		});
		$('.dtpicker').on('apply.daterangepicker', function(ev, picker) {
			$(this).val(picker.startDate.format('DD-MM-YYYY'));
			// table.ajax.reload();
		});

		$('.dtpickerange').daterangepicker({
			autoUpdateInput: false,
			locale: {
				format: 'DD-MM-YYYY'
			},
			ranges: {
				'Today': [moment(), moment()],
				'Yesterday': [moment().subtract(1, 'days'), moment().subtract(1, 'days')],
				'Last 7 Days': [moment().subtract(6, 'days'), moment()],
				'Last 30 Days': [moment().subtract(29, 'days'), moment()],
				'This Month': [moment().startOf('month'), moment().endOf('month')],
				'Last Month': [moment().subtract(1, 'month').startOf('month'), moment().subtract(1, 'month').endOf('month')]
			},
            showDropdowns: true,
		});	
		
        var table_jurnal = $('#table_jurnal').DataTable( {"bAutoWidth": false,
            ajax: {
                processing: true,
                serverSide: true,
                url: '<?php echo site_url('pmm/jurnal_umum/table_jurnal');?>',
                type : 'POST',
				data: function(d) {
                    d.filter_date = $('#filter_date_jurnal').val();
                }
            },
            columns: [
                { "data": "no" },
                { "data": "tanggal"},
                { "data": "nomor" },
                { "data": "total_debit"},
                { "data": "total_kredit"},
            ],
            "columnDefs": [
                { "width": "5%", "targets": 0, "className": 'text-center'},
                { "targets": [3, 4], "className": 'text-right'},
            ],
            responsive: true,
            pageLength: 25,
        });

		$('#filter_date_jurnal').on('apply.daterangepicker', function(ev, picker) {
        $(this).val(picker.startDate.format('DD-MM-YYYY') + ' - ' + picker.endDate.format('DD-MM-YYYY'));
        table_jurnal.ajax.reload();
		});

        $(function (){
            $("#btn_production").click(function(e){
                var checked = $("#table-production input[type=checkbox]:checked").length;
                
                if (checked > 0) {
                    let data = "";
                    $('input[type=checkbox]').each(function () {
                        data += (this.checked ? ($(this).val()+",") : "");
                    });
                    cekLenth = data.length-1;
                    data = data.substring(0,cekLenth);
                    window.location = "<?= base_url('pmm/finance/penagihan_penjualan/') ?>"+data;
                } else {
                    alert("Anda harus memilih data terlebih dahulu");
                    return false;
                }
               
            });
        });

        // alert("oke");
        


        function OpenForm(id='')
        {   
            
            $('#modalForm').modal('show');
            $('#id').val('');
            // table_detail.ajax.reload();
            if(id !== ''){
                $('#id').val(id);
                getData(id);
            }
        }

        $('#modalForm form').submit(function(event){
            $('#btn-form').button('loading');
            $.ajax({
                type    : "POST",
                url     : "<?php echo site_url('pmm/form_client'); ?>/"+Math.random(),
                dataType : 'json',
                data: $(this).serialize(),
                success : function(result){
                    $('#btn-form').button('reset');
                    if(result.output){
                        $("#modalForm form").trigger("reset");
                        table.ajax.reload();
                        $('#modalForm').modal('hide');
                    }else if(result.err){
                        bootbox.alert(result.err);
                    }
                }
            });

            event.preventDefault();
            
        });

        function getData(id)
        {
            $.ajax({
                type    : "POST",
                url     : "<?php echo site_url('pmm/get_client'); ?>",
                dataType : 'json',
                data: {id:id},
                success : function(result){
                    if(result.output){
                        $('#id').val(result.output.id);
                        $('#client_name').val(result.output.client_name);
                        $('#contract').val(result.output.contract);
                        $('#status').val(result.output.status);
                    }else if(result.err){
                        bootbox.alert(result.err);
                    }
                }
            });
        }

        function DeleteData(id)
        {
            bootbox.confirm("Apakah Anda yakin untuk menghapus data ini ?", function(result){ 
                // console.log('This was logged in the callback: ' + result); 
                if(result){
                    $.ajax({
                        type    : "POST",
                        url     : "<?php echo site_url('pmm/delete_client'); ?>",
                        dataType : 'json',
                        data: {id:id},
                        success : function(result){
                            if(result.output){
                                table.ajax.reload();
                                bootbox.alert('<b>DELETED</b>');
                            }else if(result.err){
                                bootbox.alert(result.err);
                            }
                        }
                    });
                }
            });
        }

        var table_jurnal_2 = $('#table_jurnal_2').DataTable( {"bAutoWidth": false,
            ajax: {
                processing: true,
                serverSide: true,
                url: '<?php echo site_url('pmm/jurnal_umum/table_jurnal_2');?>',
                type : 'POST',
				data: function(d) {
                    d.filter_date = $('#filter_date_jurnal_2').val();
                }
            },
            columns: [
                { "data": "no" },
                { "data": "tanggal"},
                { "data": "nomor" },
                { "data": "total_debit"},
                { "data": "total_kredit"}
            ],
            "columnDefs": [
                { "width": "5%", "targets": 0, "className": 'text-center'},
                { "targets": [3, 4], "className": 'text-right'},
            ],
            responsive: true,
            pageLength: 25,
        });

		$('#filter_date_jurnal_2').on('apply.daterangepicker', function(ev, picker) {
        $(this).val(picker.startDate.format('DD-MM-YYYY') + ' - ' + picker.endDate.format('DD-MM-YYYY'));
        table_jurnal_2.ajax.reload();
		});

        var table_jurnal_3 = $('#table_jurnal_3').DataTable( {"bAutoWidth": false,
            ajax: {
                processing: true,
                serverSide: true,
                url: '<?php echo site_url('pmm/jurnal_umum/table_jurnal_3');?>',
                type : 'POST',
				data: function(d) {
                    d.filter_date = $('#filter_date_jurnal_3').val();
                }
            },
            columns: [
                { "data": "no" },
                { "data": "tanggal"},
                { "data": "nomor" },
                { "data": "total_debit"},
                { "data": "total_kredit"}
            ],
            "columnDefs": [
                { "width": "5%", "targets": 0, "className": 'text-center'},
                { "targets": [3, 4], "className": 'text-right'},
            ],
            responsive: true,
            pageLength: 25,
        });

		$('#filter_date_jurnal_3').on('apply.daterangepicker', function(ev, picker) {
        $(this).val(picker.startDate.format('DD-MM-YYYY') + ' - ' + picker.endDate.format('DD-MM-YYYY'));
        table_jurnal_3.ajax.reload();
		});
    </script>

</body>
</html>
