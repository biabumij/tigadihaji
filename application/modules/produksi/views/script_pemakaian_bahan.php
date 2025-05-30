<script type="text/javascript">
    var form_control = '';
</script>
<script type="text/javascript">
    $('input.numberformat').number( true, 2,',','.' );
    $('input.nilaiformat').number( true, 0,',','.' );
    $('.dtpicker').daterangepicker({
        singleDatePicker: true,
        showDropdowns: true,
        locale: {
            format: 'DD-MM-YYYY'
        }
    });

    $('.dtpicker').on('apply.daterangepicker', function(ev, picker) {
        $(this).val(picker.startDate.format('DD-MM-YYYY'));
        //table.ajax.reload();
    });

    $('.dtpickerangepemakaianbahan').daterangepicker({
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

    var table_pemakaian_bahan = $('#table-pemakaian-bahan').DataTable( {"bAutoWidth": false,
        ajax: {
            processing: true,
            serverSide: true,
            url: '<?php echo site_url('produksi/table_pemakaian_bahan'); ?>',
            type: 'POST',
            data: function(d) {
                d.filter_date = $('#filter_date_pemakaian_bahan').val();
            }
        },
        columns: [{
                "data": "no"
            },
            {
                "data": "date"
            },
            {
                "data": "material_id"
            },
            {
                "data": "volume"
            },
            {
                "data": "nilai"
            },
            {
                "data": "actions"
            }
        ],
        pageLength: 25,
        "columnDefs": [
            { "width": "5%", "targets": 0, "className": 'text-center'},
        ],
    });

    $('.dtpickerangepemakaianbahan').on('apply.daterangepicker', function(ev, picker) {
        $(this).val(picker.startDate.format('DD-MM-YYYY') + ' - ' + picker.endDate.format('DD-MM-YYYY'));
        table_pemakaian_bahan.ajax.reload();
    });

    function DeleteDataPemakaianBahan(id) {
        bootbox.confirm("Apakah Anda yakin untuk menghapus data ini ?", function(result) {
            // console.log('This was logged in the callback: ' + result); 
            if (result) {
                $.ajax({
                    type: "POST",
                    url: "<?php echo site_url('produksi/delete_pemakaian_bahan'); ?>",
                    dataType: 'json',
                    data: {
                        id: id
                    },
                    success: function(result) {
                        if (result.output) {
                            table_pemakaian_bahan.ajax.reload();
                            bootbox.alert('<b>DELETED</b>');
                        } else if (result.err) {
                            bootbox.alert(result.err);
                        }
                    }
                });
            }
        });
    }
</script>