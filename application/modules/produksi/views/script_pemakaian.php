<script type="text/javascript">
    var form_control = '';
</script>
<script type="text/javascript">
    <?php
    $kunci_rakor = $this->db->select('date')->order_by('date','desc')->limit(1)->get_where('kunci_rakor')->row_array();
    $last_opname = date('d-m-Y', strtotime('+1 days', strtotime($kunci_rakor['date'])));
    ?>
    $('input.numberformat').number( true, 2,',','.' );
    $('input.nilaiformat').number( true, 0,',','.' );
    $('.dtpicker').daterangepicker({
        singleDatePicker: true,
        showDropdowns : false,
        locale: {
            format: 'DD-MM-YYYY'
        },
        minDate: '<?php echo $last_opname;?>',
        //maxDate: moment().add(+0, 'd').toDate(),
        //minDate: moment().startOf('month').toDate(),
        maxDate: moment().endOf('month').toDate(),
    });

    $('.dtpicker').on('apply.daterangepicker', function(ev, picker) {
        $(this).val(picker.startDate.format('DD-MM-YYYY'));
        // table.ajax.reload();
    });

    $('.dtpickerangepemakaian').daterangepicker({
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

    var table_pemakaian = $('#table-pemakaian').DataTable( {"bAutoWidth": false,
        ajax: {
            processing: true,
            serverSide: true,
            url: '<?php echo site_url('produksi/table_pemakaian'); ?>',
            type: 'POST',
            data: function(d) {
                d.filter_date = $('#filter_date_pemakaian').val();
            }
        },
        columns: [{
                "data": "no"
            },
            {
                "data": "date"
            },
            {
                "data": "jumlah_bahan"
            },
            {
                "data": "jumlah_solar"
            },
            {
                "data": "actions"
            }
        ],
        pageLength: 25,
        "columnDefs": [
            { "width": "5%", "targets": 0, "className": 'text-center'},
            { "width": "5%", "targets": 4, "className": 'text-center'},
        ],
    });

    $('.dtpickerangepemakaian').on('apply.daterangepicker', function(ev, picker) {
        $(this).val(picker.startDate.format('DD-MM-YYYY') + ' - ' + picker.endDate.format('DD-MM-YYYY'));
        table_pemakaian.ajax.reload();
    });

    function DeleteDataPemakaian(id) {
        bootbox.confirm("Apakah Anda yakin untuk menghapus data ini ?", function(result) {
            // console.log('This was logged in the callback: ' + result); 
            if (result) {
                $.ajax({
                    type: "POST",
                    url: "<?php echo site_url('produksi/delete_pemakaian'); ?>",
                    dataType: 'json',
                    data: {
                        id: id
                    },
                    success: function(result) {
                        if (result.output) {
                            table_pemakaian.ajax.reload();
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