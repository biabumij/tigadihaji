<!doctype html>
<html lang="en" class="fixed">
<head>
    <?php echo $this->Templates->Header();?>

    <style type="text/css">
        body {
            font-family: helvetica;
        }
    </style>
</head>

<body>
<div class="wrap">
    
    <?php echo $this->Templates->PageHeader();?>
    

    <div class="page-body">
        <?php echo $this->Templates->LeftBar();?>
        <div class="content">
            <div class="content-header">
                <div class="leftside-content-header">
                    <ul class="breadcrumbs">
                        <li><i class="fa fa-home" aria-hidden="true"></i><a href="<?php echo base_url();?>">Dashboard</a></li>
                        <li><a >Measures</a></li>
                    </ul>
                </div>
            </div>
            <div class="row animated fadeInUp">
                <div class="col-sm-12 col-lg-12">
                    <div class="panel" style="background: linear-gradient(90deg, #f8f8f8 20%, #dddddd 40%, #f8f8f8 80%);">
                        <div class="panel-header">
                            <h3 class="section-subtitle">Satuan</h3>
                        </div>
                        <div class="panel-content">
                            <div class="row">
                                <div class="col-sm-2">
                                    <a href="javascript:void(0);" onclick="OpenForm()" class="btn btn-primary" style="border-radius:10px; font-weight:bold;"><i class="fa fa-plus"></i> Buat Satuan</a>
                                </div>
                                <!--<form method="GET" target="_blank" action="<?php echo site_url('pmm/reports/measures_print');?>">
                                    <div class="col-sm-2">
                                        <button type="submit" class="btn btn-info"><i class="fa fa-print"></i> Print</button>
                                    </div>  
                                </form>-->
                            </div>
                            <div class="table-responsive">
                                <table class="table table-striped table-hover table-center" id="guest-table">
                                    <thead>
                                        <tr>
                                            <th>No.</th>
                                            <th>Satuan</th>
                                            <th>Status</th>
                                            <th>Edit</th>
                                            <th>Hapus</th>
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
    

    
    <script type="text/javascript">
        var form_control = '';
    </script>
	<?php echo $this->Templates->Footer();?>

    

    <div class="modal fade bd-example-modal-lg" id="modalForm" tabindex="-1" role="dialog">
        <div class="modal-dialog" role="document" >
            <div class="modal-content">
                <div class="modal-header">
                    <span class="modal-title">Buat Satuan</span>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <form class="form-horizontal" style="padding: 0 10px 0 20px;" >
                        <input type="hidden" name="id" id="id">
                        <div class="form-group">
                            <label>Nama Satuan<span class="required" aria-required="true">*</span></label>
                            <input type="text" id="measure_name" name="measure_name" class="form-control" required="" autocomplete="off" />
                        </div>
                        <div class="form-group">
                            <label>Status<span class="required" aria-required="true">*</span></label>
                            <select id="status" name="status" class="form-control" required="">
                                <option value="PUBLISH">PUBLISH</option>
                                <option value="UNPUBLISH">UNPUBLISH</option>
                            </select>
                        </div>
                        <div class="form-group">
                            <button type="submit" class="btn btn-success" id="btn-form" style="font-weight:bold; border-radius:10px;"><i class="fa fa-send"></i> Kirim</button>
                        </div>
                    </form>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal" style="font-weight:bold; border-radius:10px;">Close</button>
                </div>
            </div>
        </div>
    </div>

	<script src="<?php echo base_url();?>assets/back/theme/vendor/bootbox.min.js"></script>
    <script src="<?php echo base_url();?>assets/back/theme/vendor/jquery.number.min.js"></script>
    <script type="text/javascript">
        $('input.numberformat').number( true, 6,',','.' );
        var table = $('#guest-table').DataTable( {"bAutoWidth": false,
            ajax: {
                processing: true,
                serverSide: true,
                url: '<?php echo site_url('pmm/table_measure');?>',
                type : 'POST',
            },
            columns: [
                { "data": "no" },
                { "data": "measure_name" },
                { "data": "status" },
                { "data": "edit" },
                { "data": "delete" }
            ],
            "columnDefs": [
                { "width": "5%", "targets": 0, "className": 'text-center'},
            ],
            responsive: true,
        });

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
                url     : "<?php echo site_url('pmm/form_measure'); ?>/"+Math.random(),
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
                url     : "<?php echo site_url('pmm/get_measure'); ?>",
                dataType : 'json',
                data: {id:id},
                success : function(result){
                    if(result.output){
                        $('#id').val(result.output.id);
                        $('#measure_name').val(result.output.measure_name);
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
                        url     : "<?php echo site_url('pmm/delete_measure'); ?>",
                        dataType : 'json',
                        data: {id:id},
                        success : function(result){
                            if(result.output){
                                table.ajax.reload();
                                bootbox.alert('Berhasil menghapus!!');
                            }else if(result.err){
                                bootbox.alert(result.err);
                            }
                        }
                    });
                }
            });
        }

    </script>

</body>
</html>
