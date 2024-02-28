<!doctype html>
<html lang="en" class="fixed">
<head>
<link href='http://fonts.googleapis.com/css?family=Roboto:400,300,700' rel='stylesheet' type='text/css'>
<link href="//maxcdn.bootstrapcdn.com/font-awesome/4.2.0/css/font-awesome.min.css" rel="stylesheet">
<link rel="stylesheet" href="erp/assets/bootstrap/css/bootstrap.min.css" />
<link rel="stylesheet" href="erp/assets/animate/animate.css" />
<link rel="stylesheet" href="erp/assets/animate/set.css" />
<link rel="stylesheet" href="erp/assets/gallery/blueimp-gallery.min.css">
<link rel="stylesheet" href="erp/assets/style.css">
<script src="https://kit.fontawesome.com/591a1bf2f6.js" crossorigin="anonymous"></script>
<?php echo $this->Templates->Header();?>
<script type = "text/JavaScript">
    function AutoRefresh( t ) {
        setTimeout("location.reload(true);", t);
    }
</script>
<script src="https://kit.fontawesome.com/a076d05399.js" crossorigin="anonymous"></script>
</head>
<style type="text/css">
    body {
        font-family: helvetica;
        background: #fffdd0;
    }

    .chart-container{
        position: relative; max-width: 100%; height:350px; background: #fff;
    }
    
    .highcharts-figure,
    .highcharts-data-table table {
    min-width: 65%;
    max-width: 100%;
    }

    .highcharts-data-table table {
    font-family: Verdana, sans-serif;
    border-collapse: collapse;
    border: 1px solid #ebebeb;
    margin: 10px auto;
    text-align: center;
    width: 100%;
    max-width: 500px;
    }

    .highcharts-data-table caption {
    padding: 1em 0;
    font-size: 1.2em;
    color: #555;
    }

    .highcharts-data-table th {
    font-weight: 600;
    padding: 0.5em;
    }

    .highcharts-data-table td,
    .highcharts-data-table th,
    .highcharts-data-table caption {
    padding: 0.5em;
    }

    .highcharts-data-table thead tr,
    .highcharts-data-table tr:nth-child(even) {
    background: #f8f8f8;
    }

    .highcharts-data-table tr:hover {
    background: #f1f7ff;
    }
</style>
<body onload = "JavaScript:AutoRefresh(360000);">
<div class="wrap">
    
    <?php echo $this->Templates->PageHeader();?>
    <div class="page-body">
        <div id="about" class="container spacer about">
            <div class="process">
                <table width="100%" style="margin-top:100px;">
                    <tr>
                        <th width="25%" class="text-center" data-toggle="collapse" data-target="#produksi" aria-expanded="false" aria-controls="sc">
                            <ul class="row text-center list-inline  wowload bounceIn">
                                <li style="background: linear-gradient(110deg, #40c9fe 20%, #40c9fe 40%, #2ea4d7 80%);">
                                    <a>
                                    <span style="color:#fffdd0;"><i class="fa-solid fa-person-digging"></i><b>PRODUKSI</b></span></a>
                                </li>
                            </ul>
                        </th>
                        <th width="25%" class="text-center" data-toggle="collapse" data-target="#keuangan" aria-expanded="false" aria-controls="kp">
                            <ul class="row text-center list-inline  wowload bounceIn">
                                <li style="background: linear-gradient(110deg, #8dea57 20%, #8dea57 40%, #57d762 80%);">
                                    <a>
                                    <span style="color:#fffdd0;"><i class="fa-solid fa-money-bill"></i><b>KEUANGAN</b></span></a>
                                </li>
                            </ul>
                        </th>
                        <th width="25%" class="text-center" data-toggle="collapse" data-target="#rencana" aria-expanded="false" aria-controls="beton">
                            <ul class="row text-center list-inline  wowload bounceIn">
                                <li class="text-center" style="background: linear-gradient(110deg, #cf9231 20%, #cf9231 40%, #ca861b 80%);">
                                    <a>
                                    <span style="color:#fffdd0;"><i class="fa-solid fa-calendar-week"></i><b>RENCANA</b></span></a>
                                </li>
                            </ul>
                        </th>
                        <th width="25%" class="text-center" data-toggle="collapse" data-target="#master" aria-expanded="false" aria-controls="beton">
                            <ul class="row text-center list-inline  wowload bounceIn">
                                <li style="background: linear-gradient(110deg, #d11212 20%, #d11212 40%, #b30f15 80%);">
                                    <a>
                                    <span style="color:#fffdd0;"><i class="fa-solid fa-database"></i><b>MASTER<br />DATA</b></span></a>
                                </li>
                            </ul>
                        </th>
                    </tr>
                </table>
                <br />
                <br />
                <table width="100%">
                    <tr>
                        <th width="25%" class="text-center">
                            <ul class="row text-center list-inline  wowload bounceInUp collapse" id="produksi">
                                <li class="text-center" style="background: linear-gradient(110deg, #40c9fe 20%, #40c9fe 40%, #2ea4d7 80%);">
                                    <a href="<?php echo site_url('admin/pembelian');?>">
                                    <span style="color:#fffdd0;"><i class="fa-solid fa-cart-shopping"></i><b>PEMBELIAN</b></span></a>
                                </li>
                                <li class="text-center" style="background: linear-gradient(110deg, #40c9fe 20%, #40c9fe 40%, #2ea4d7 80%);">
                                    <a href="<?php echo site_url('admin/penjualan');?>">
                                    <span style="color:#fffdd0;"><i class="fa-solid fa-bag-shopping"></i><b>PENJUALAN</b></span></a>
                                </li>
                                <br />
                                <br />
                                <li class="text-center" style="background: linear-gradient(110deg, #40c9fe 20%, #40c9fe 40%, #2ea4d7 80%);">
                                    <a href="<?php echo site_url('admin/stock_opname');?>">
                                    <span style="color:#fffdd0;"><i class="fa-solid fa-bag-shopping"></i><b>STOCK<br />OPNAME</b></span></a>
                                </li>
                            </ul>
                        </th>
                        <th width="25%" class="text-center">
                            <ul class="row text-center list-inline  wowload bounceInUp collapse" id="keuangan">
                                <li class="text-center" style="background: linear-gradient(110deg, #8dea57 20%, #8dea57 40%, #57d762 80%);">
                                    <a href="<?php echo site_url('admin/biaya_bua');?>">
                                    <span style="color:#fffdd0;"><i class="fa-solid fa-dollar-sign"></i><b>BUA</b></span></a>
                                </li>
                                <li class="text-center" style="background: linear-gradient(110deg, #8dea57 20%, #8dea57 40%, #57d762 80%);">
                                    <a href="<?php echo site_url('admin/jurnal_umum');?>">
                                    <span style="color:#fffdd0;"><i class="fa-solid fa-scale-balanced"></i><b>JURNAL</b></span></a>
                                </li>
                            </ul>
                        </th>
                        <th width="25%" class="text-center">
                            <ul class="row text-center list-inline  wowload bounceInUp collapse" id="rencana">
                                <li class="text-center" class="text-center" style="background: linear-gradient(110deg, #cf9231 20%, #cf9231 40%, #ca861b 80%);">
                                    <a href="<?php echo site_url('admin/rap');?>">
                                    <span style="color:#fffdd0;"><i class="fa-regular fa-calendar-check"></i><b>RAP</b></span></a>
                                </li>
                                <li class="text-center" class="text-center" style="background: linear-gradient(110deg, #cf9231 20%, #cf9231 40%, #ca861b 80%);">
                                    <a href="<?php echo site_url('admin/rencana_kerja');?>">
                                    <span style="color:#fffdd0;"><i class="fa-solid fa-calendar-days"></i><b>RENCANA<br />KERJA</b></span></a>
                                </li>
                                <br />
                                <br />
                                <li class="text-center" class="text-center" style="background: linear-gradient(110deg, #cf9231 20%, #cf9231 40%, #ca861b 80%);">
                                    <a href="<?php echo site_url('admin/rencana_cash_flow');?>">
                                    <span style="color:#fffdd0;"><i class="fa-solid fa-cash-register"></i><b>RENCANA<br />CASH FLOW</b></span></a>
                                </li>
                            </ul>
                        </th>
                        <th width="25%" class="text-center">
                            <ul class="row text-center list-inline  wowload bounceInUp collapse" id="master">
                                <li class="text-center" style="background: linear-gradient(110deg, #d11212 20%, #d11212 40%, #b30f15 80%);">
                                    <a href="<?php echo site_url('admin/kontak');?>">
                                    <span style="color:#fffdd0;"><i class="fa-solid fa-address-book"></i><b>KONTAK</b></span></a>
                                </li>
                                <li class="text-center" style="background: linear-gradient(110deg, #d11212 20%, #d11212 40%, #b30f15 80%);">
                                    <a href="<?php echo site_url('admin/produk');?>">
                                    <span style="color:#fffdd0;"><i class="fa-solid fa-cart-flatbed-suitcase"></i><b>PRODUK</b></span></a>
                                </li>
                                <br />
                                <br />
                                <li class="text-center" style="background: linear-gradient(110deg, #d11212 20%, #d11212 40%, #b30f15 80%);">
                                    <a href="<?php echo site_url('admin/daftar_akun');?>">
                                    <span style="color:#fffdd0;"><i class="fa-solid fa-hand-holding-dollar"></i><b>DAFTAR<br />AKUN</b></span></a>
                                </li>
                                <li class="text-center" style="background: linear-gradient(110deg, #d11212 20%, #d11212 40%, #b30f15 80%);">
                                    <a href="<?php echo site_url('admin/satuan');?>">
                                    <span style="color:#fffdd0;"><i class="fa-solid fa-ruler-vertical"></i><b>SATUAN</b></span></a>
                                </li>
                                <br />
                                <br />
                                <li class="text-center" style="background: linear-gradient(110deg, #d11212 20%, #d11212 40%, #b30f15 80%);">
                                    <a href="<?php echo site_url('admin/users');?>">
                                    <span style="color:#fffdd0;"><i class="fa-solid fa-users"></i><b>USER</b></span></a>
                                </li>
                                <li class="text-center" style="background: linear-gradient(110deg, #d11212 20%, #d11212 40%, #b30f15 80%);">
                                    <a href="<?php echo site_url('admin/perusahaan');?>">
                                    <span style="color:#fffdd0;"><i class="fa-solid fa-building"></i><b>PERUSAHAAN</b></span></a>
                                </li>
                            </ul>
                        </th>
                    </tr>
                </table>

                <table width="100%" style="margin-top:50px;">
                    <tr>
                        <th width="25%" class="text-center" data-toggle="collapse" data-target="#laporan" aria-expanded="false" aria-controls="beton">
                            <ul class="row text-center list-inline  wowload bounceIn">
                                <li style="background: linear-gradient(110deg, #ce7c56 20%, #ce7c56 40%, #b96f4d 80%);">
                                    <a>
                                    <span style="color:#fffdd0;"><i class="fa-solid fa-chart-line"></i><b>LAPORAN</b></span></a>
                                </li>
                            </ul>
                        </th>
                        <th width="25%" class="text-center" data-toggle="collapse" data-target="#pemakaian" aria-expanded="false" aria-controls="beton">
                            <ul class="row text-center list-inline  wowload bounceIn">
                                <li style="background: linear-gradient(110deg, #f28111 20%, #f28111 40%, #f28111 80%);">
                                    <a>
                                    <span style="color:#fffdd0;"><i class="fa-solid fa-credit-card"></i><b>PEMAKAIAN</b></span></a>
                                </li>
                            </ul>
                        </th>
                        <th width="25%" class="text-center" data-toggle="collapse" data-target="#form" aria-expanded="false" aria-controls="beton">
                            <ul class="row text-center list-inline  wowload bounceIn">
                                <li style="background: linear-gradient(110deg, #7163b8 20%, #7163b8 40%, #6252b1 80%);">
                                    <a>
                                    <span style="color:#fffdd0;"><i class="fa-solid fa-chart-line"></i><b>FORM</b></span></a>
                                </li>
                            </ul>
                        </th>
                        <?php
                        if(in_array($this->session->userdata('admin_group_id'), array(1))){
                        ?>
                        <th width="25%" class="text-center" data-toggle="collapse" data-target="#settings" aria-expanded="false" aria-controls="beton">
                            <ul class="row text-center list-inline  wowload bounceIn">
                                <li class="text-center" style="background: linear-gradient(110deg, #848484 20%, #757575 40%, #666666 80%);">
                                    <a>
                                    <span style="color:#fffdd0;"><i class="fa-solid fa-gear"></i><b>SETTINGS</b></span></a>
                                </li>
                            </ul>
                        </th>
                        <?php
                        }
                        ?>
                    </tr>
                </table>
                <br />
                <br />
                <table width="100%">
                    <tr>
                        <th width="25%" class="text-center">
                            <ul class="row text-center list-inline  wowload bounceInUp collapse" id="laporan">
                                <li class="text-center" style="background: linear-gradient(110deg, #ce7c56 20%, #ce7c56 40%, #b96f4d 80%);">
                                    <a href="<?php echo site_url('admin/laporan_keuangan');?>">
                                    <span style="color:#fffdd0;"><i class="fa-solid fa-chart-simple"></i><b>KEUANGAN</b></span></a>
                                </li>
                                <li class="text-center" style="background: linear-gradient(110deg, #ce7c56 20%, #ce7c56 40%, #b96f4d 80%);">
                                    <a href="<?php echo site_url('admin/laporan_pembelian');?>">
                                    <span style="color:#fffdd0;"><i class="fa-solid fa-chart-simple"></i><b>PEMBELIAN</b></span></a>
                                </li>
                                <br />
                                <br />
                                <li class="text-center" style="background: linear-gradient(110deg, #ce7c56 20%, #ce7c56 40%, #b96f4d 80%);">
                                    <a href="<?php echo site_url('admin/laporan_penjualan');?>">
                                    <span style="color:#fffdd0;"><i class="fa-solid fa-chart-simple"></i><b>PENJUALAN</b></span></a>
                                </li>
                                <li class="text-center" style="background: linear-gradient(110deg, #ce7c56 20%, #ce7c56 40%, #b96f4d 80%);">
                                    <a href="<?php echo site_url('admin/laporan_ev._produksi');?>">
                                    <span style="color:#fffdd0;"><i class="fa-solid fa-chart-simple"></i><b>EVALUASI</b></span></a>
                                </li>
                                <br />
                                <br />
                                <li class="text-center" style="background: linear-gradient(110deg, #ce7c56 20%, #ce7c56 40%, #b96f4d 80%);">
                                    <a href="<?php echo site_url('admin/laporan_rencana_kerja');?>">
                                    <span style="color:#fffdd0;"><i class="fa-solid fa-chart-simple"></i><b>RENCANA KERJA</b></span></a>
                                </li>
                            </ul>
                        </th>
                        <th width="25%" class="text-center">
                            <ul class="row text-center list-inline  wowload bounceInUp collapse" id="pemakaian">
                                <li class="text-center" style="background: linear-gradient(110deg, #f28111 20%, #f28111 40%, #f28111 80%);">
                                    <a href="<?php echo site_url('admin/biaya_bahan');?>">
                                    <span style="color:#fffdd0;"><i class="fa-solid fa-cart-shopping"></i><b>BIAYA<br />BAHAN</b></span></a>
                                </li>
                                <li class="text-center" style="background: linear-gradient(110deg, #f28111 20%, #f28111 40%, #f28111 80%);">
                                    <a href="<?php echo site_url('admin/biaya_alat');?>">
                                    <span style="color:#fffdd0;"><i class="fa-solid fa-screwdriver-wrench"></i><b>BIAYA<br />ALAT</b></span></a>
                                </li>
                            </ul>
                        </th>
                        <th width="25%" class="text-center">
                            <ul class="row text-center list-inline  wowload bounceInUp collapse" id="form">
                                <li class="text-center" style="background: linear-gradient(110deg, #7163b8 20%, #7163b8 40%, #6252b1 80%);">
                                    <a href="<?php echo site_url('admin/form');?>">
                                    <span style="color:#fffdd0;"><i class="fa-solid fa-file"></i><b>PERUBAHAN<br />SISTEM</b></span></a>
                                </li>
                            </ul>
                        </th>
                        <?php
                        if(in_array($this->session->userdata('admin_group_id'), array(1))){
                        ?>
                        <th width="25%" class="text-center">
                            <ul class="row text-center list-inline  wowload bounceInUp collapse" id="settings">
                                <li class="text-center" class="text-center" style="background: linear-gradient(110deg, #848484 20%, #757575 40%, #666666 80%);">
                                    <a href="<?php echo site_url('admin/menu');?>">
                                    <span style="color:#fffdd0;"><i class="fa-solid fa-bars"></i><b>MENU</b></span></a>
                                </li>
                                <li class="text-center" class="text-center" style="background: linear-gradient(110deg, #848484 20%, #757575 40%, #666666 80%);">
                                    <a href="<?php echo site_url('admin/admin_access');?>">
                                    <span style="color:#fffdd0;"><i class="fa-solid fa-eye"></i><b>ADMIN<br />ACCESS</b></span></a>
                                </li>
                                <br />
                                <br />
                                <li class="text-center" class="text-center" style="background: linear-gradient(110deg, #848484 20%, #757575 40%, #666666 80%);">
                                    <a href="<?php echo site_url('admin/admin');?>">
                                    <span style="color:#fffdd0;"><i class="fa-solid fa-user-secret"></i><b>ADMIN</b></span></a>
                                </li>
                            </ul>
                        </th>
                        <?php
                        }
                        ?>
                    </tr>
                </table>
            </div>
        </div>
    </div>
    
</div>

<?php echo $this->Templates->Footer();?>
<script src="<?php echo base_url();?>assets/back/theme/vendor/toastr/toastr.min.js"></script>
<script src="<?php echo base_url();?>assets/back/theme/vendor/chart-js/chart.min.js"></script>
<script src="<?php echo base_url();?>assets/back/theme/vendor/magnific-popup/jquery.magnific-popup.min.js"></script>

<link rel="stylesheet" type="text/css" href="https://cdn.datatables.net/buttons/1.5.1/css/buttons.dataTables.min.css">
<script type="text/javascript" src="https://cdn.datatables.net/buttons/1.5.1/js/dataTables.buttons.min.js"></script>
<script type="text/javascript" src="https://cdn.datatables.net/buttons/1.5.1/js/buttons.flash.min.js"></script>
<script type="text/javascript" src="https://cdnjs.cloudflare.com/ajax/libs/jszip/3.1.3/jszip.min.js"></script>
<script type="text/javascript" src="https://cdnjs.cloudflare.com/ajax/libs/pdfmake/0.1.32/pdfmake.min.js"></script>
<script type="text/javascript" src="https://cdnjs.cloudflare.com/ajax/libs/pdfmake/0.1.32/vfs_fonts.js"></script>
<script type="text/javascript" src="https://cdn.datatables.net/buttons/1.5.1/js/buttons.print.min.js"></script>
<script type="text/javascript" src="https://cdn.datatables.net/buttons/1.5.1/js/buttons.html5.min.js"></script>
<script src="<?php echo base_url();?>assets/back/theme/vendor/daterangepicker/moment.min.js"></script>
<script src="<?php echo base_url();?>assets/back/theme/vendor/number_format.js"></script>
<script src="<?php echo base_url();?>assets/back/theme/vendor/daterangepicker/daterangepicker.js"></script>
<link rel="stylesheet" type="text/css" href="<?php echo base_url();?>assets/back/theme/vendor/daterangepicker/daterangepicker.css">
<script type="text/javascript" src="<?php echo base_url();?>assets/back/theme/vendor/chart-js/chart.min.js"></script>

<script src="https://code.highcharts.com/highcharts.js"></script>
<script src="https://code.highcharts.com/modules/series-label.js"></script>
<script src="https://code.highcharts.com/modules/exporting.js"></script>
<script src="https://code.highcharts.com/modules/export-data.js"></script>
<script src="https://code.highcharts.com/modules/accessibility.js"></script>

<script type="text/javascript">
    $(function () {
        var chart;
        $(document).ready(function() {
            chart = new Highcharts.Chart({
                chart: {
                    renderTo: 'container',
                    type: 'line',
                    marginRight: 130,
                    marginBottom: 75,
                    backgroundColor: {
                        linearGradient: [0, 0, 700, 500],
                        stops: [
                            [0, 'rgb(182,150,119)'],
                            [1, 'rgb(182,150,119)']
                        ]
                    },
                },
                title: {
                    style: {
                        color: '#ffffff',
                        fontWeight: 'bold',
                        fontSize: '14px',
                        fontFamily: 'helvetica'
                    },
                    text: 'REALISASI PRODUKSI',
                    x: -20 //center text
                },
                subtitle: {
                    style: {
                        color: '#ffffff',
                        fontWeight: 'bold',
                        fontSize: '14px',
                        fontFamily: 'helvetica'
                    },
                    text: 'PT. BIA BUMI JAYENDRA - TIGA DIHAJI',
                    x: -20 //center text
                },
                xAxis: { //data bulan
                    labels: {
                        style: {
                            color: '#ffffff',
                            fontWeight: 'bold',
                            fontSize: '10px',
                            fontFamily: 'helvetica'
                        }
                    },
                    //categories: ['Feb 22','Mar 22','Apr 22','Mei 22','Jun 22','Jul 22','Agu 22','Sep 22','Okt 22','Nov 22','Des 22','Jan 23','Feb 23','Mar 23','Apr 23','Mei 23','Jun 23','Jul 23','Agu 23','Sep 23','Okt 23','Nov 23','Des 23']
                    categories: ['Jan 23','Feb 23','Mar 23','Apr 23','Mei 23','Jun 23','Jul 23','Agu 23','Sep 23','Okt 23','Nov 23','Des 23']
                },
                yAxis: {
                    //title: {  //label yAxis
                        //text: 'RAP <br /><?php echo number_format(0,0,',','.'); ?>'
                        //text: 'Presentase'
                    //},
                    title: {
                        style: {
                            color: '#ffffff',
                            fontWeight: 'bold',
                            fontSize: '10px',
                            fontFamily: 'helvetica'
                        },
                        text: 'Presentase'           
                    },
                    plotLines: [{
                        value: 0,
                        width: 1,
                        color: '#808080' //warna dari grafik line
                    }],
                    labels: {
                        style: {
                            color: '#ffffff',
                            fontWeight: 'bold',
                            fontSize: '10px',
                            fontFamily: 'helvetica'
                        },
                        format: '{value} %'
                    },
                    min: 0,
                    max: 110,
                    tickInterval: 10,
                },
                tooltip: {
                //fungsi tooltip, ini opsional, kegunaan dari fungsi ini 
                //akan menampikan data di titik tertentu di grafik saat mouseover
                    formatter: function() {
                            return '<b>'+ this.series.name +'</b><br/>'+ 
                            ''+ 'Presentase' +': '+ this.y + '%<br/>';
                            //''+ 'Vol' +': '+ this.x + '';

                            //'<b>'+ 'Presentase' +': '+ this.y +'%'</b><br/>'+ 
                            //'<b>'+ 'Penjualan' +': '+ this.y +'</b><br/>';
                    }
                },
                legend: {
                    layout: 'vertical',
                    align: 'right',
                    verticalAlign: 'top',
                    x: -10,
                    y: 100,
                    borderWidth: 0
                },

                plotOptions: {
                    spline: {
                        lineWidth: 4,
                        states: {
                            hover: {
                                lineWidth: 5
                            }
                        },
                        marker: {
                            enabled: true
                        }
                    }
                },
        
                series: [{  
                    name: 'Rencana %',  
                    
                    //data: [<?php echo json_encode($net_februari_rap, JSON_NUMERIC_CHECK); ?>,<?php echo json_encode($net_maret_rap, JSON_NUMERIC_CHECK); ?>,<?php echo json_encode($net_april_rap, JSON_NUMERIC_CHECK); ?>,<?php echo json_encode($net_mei_rap, JSON_NUMERIC_CHECK); ?>,<?php echo json_encode($net_juni_rap, JSON_NUMERIC_CHECK); ?>,<?php echo json_encode($net_juli_rap, JSON_NUMERIC_CHECK); ?>,<?php echo json_encode($net_agustus_rap, JSON_NUMERIC_CHECK); ?>,<?php echo json_encode($net_september_rap, JSON_NUMERIC_CHECK); ?>,<?php echo json_encode($net_oktober_rap, JSON_NUMERIC_CHECK); ?>,<?php echo json_encode($net_november_rap, JSON_NUMERIC_CHECK); ?>,<?php echo json_encode($net_desember_rap, JSON_NUMERIC_CHECK); ?>,<?php echo json_encode($net_januari23_rap, JSON_NUMERIC_CHECK); ?>,<?php echo json_encode($net_februari23_rap, JSON_NUMERIC_CHECK); ?>,<?php echo json_encode($net_maret23_rap, JSON_NUMERIC_CHECK); ?>,<?php echo json_encode($net_april23_rap, JSON_NUMERIC_CHECK); ?>,<?php echo json_encode($net_mei23_rap, JSON_NUMERIC_CHECK); ?>,<?php echo json_encode($net_juni23_rap, JSON_NUMERIC_CHECK); ?>,<?php echo json_encode($net_juli23_rap, JSON_NUMERIC_CHECK); ?>,<?php echo json_encode($net_agustus23_rap, JSON_NUMERIC_CHECK); ?>,<?php echo json_encode($net_september23_rap, JSON_NUMERIC_CHECK); ?>,<?php echo json_encode($net_oktober23_rap, JSON_NUMERIC_CHECK); ?>,<?php echo json_encode($net_november23_rap, JSON_NUMERIC_CHECK); ?>,<?php echo json_encode($net_desember23_rap, JSON_NUMERIC_CHECK); ?>],
                    data: [<?php echo json_encode($net_januari23_rap, JSON_NUMERIC_CHECK); ?>,<?php echo json_encode($net_februari23_rap, JSON_NUMERIC_CHECK); ?>,<?php echo json_encode($net_maret23_rap, JSON_NUMERIC_CHECK); ?>,<?php echo json_encode($net_april23_rap, JSON_NUMERIC_CHECK); ?>,<?php echo json_encode($net_mei23_rap, JSON_NUMERIC_CHECK); ?>,<?php echo json_encode($net_juni23_rap, JSON_NUMERIC_CHECK); ?>,<?php echo json_encode($net_juli23_rap, JSON_NUMERIC_CHECK); ?>,<?php echo json_encode($net_agustus23_rap, JSON_NUMERIC_CHECK); ?>,<?php echo json_encode($net_september23_rap, JSON_NUMERIC_CHECK); ?>,<?php echo json_encode($net_oktober23_rap, JSON_NUMERIC_CHECK); ?>,<?php echo json_encode($net_november23_rap, JSON_NUMERIC_CHECK); ?>,<?php echo json_encode($net_desember23_rap, JSON_NUMERIC_CHECK); ?>],

                    color: '#000000',
                    fontWeight: 'bold',
                    fontSize: '10px',
                    fontFamily: 'helvetica'
                },
                {  
                    name: 'Realisasi %',  
                    
                    //data: [<?php echo json_encode($net_februari, JSON_NUMERIC_CHECK); ?>,<?php echo json_encode($net_maret, JSON_NUMERIC_CHECK); ?>,<?php echo json_encode($net_april, JSON_NUMERIC_CHECK); ?>,<?php echo json_encode($net_mei, JSON_NUMERIC_CHECK); ?>,<?php echo json_encode($net_juni, JSON_NUMERIC_CHECK); ?>,<?php echo json_encode($net_juli, JSON_NUMERIC_CHECK); ?>,<?php echo json_encode($net_agustus, JSON_NUMERIC_CHECK); ?>,<?php echo json_encode($net_september, JSON_NUMERIC_CHECK); ?>,<?php echo json_encode($net_oktober, JSON_NUMERIC_CHECK); ?>,<?php echo json_encode($net_november, JSON_NUMERIC_CHECK); ?>,<?php echo json_encode($net_desember, JSON_NUMERIC_CHECK); ?>,<?php echo json_encode($net_januari23, JSON_NUMERIC_CHECK); ?>,<?php echo json_encode($net_februari23, JSON_NUMERIC_CHECK); ?>,<?php echo json_encode($net_maret23, JSON_NUMERIC_CHECK); ?>,<?php echo json_encode($net_april23, JSON_NUMERIC_CHECK); ?>,<?php echo json_encode($net_mei23, JSON_NUMERIC_CHECK); ?>,<?php echo json_encode($net_juni23, JSON_NUMERIC_CHECK); ?>,<?php echo json_encode($net_juli23, JSON_NUMERIC_CHECK); ?>,<?php echo json_encode($net_agustus23, JSON_NUMERIC_CHECK); ?>,<?php echo json_encode($net_september23, JSON_NUMERIC_CHECK); ?>,<?php echo json_encode($net_oktober23, JSON_NUMERIC_CHECK); ?>,<?php echo json_encode($net_november23, JSON_NUMERIC_CHECK); ?>,<?php echo json_encode($net_desember23, JSON_NUMERIC_CHECK); ?>],
                    data: [<?php echo json_encode($net_januari23, JSON_NUMERIC_CHECK); ?>,<?php echo json_encode($net_februari23, JSON_NUMERIC_CHECK); ?>,<?php echo json_encode($net_maret23, JSON_NUMERIC_CHECK); ?>,<?php echo json_encode($net_april23, JSON_NUMERIC_CHECK); ?>,<?php echo json_encode($net_mei23, JSON_NUMERIC_CHECK); ?>,<?php echo json_encode($net_juni23, JSON_NUMERIC_CHECK); ?>,<?php echo json_encode($net_juli23, JSON_NUMERIC_CHECK); ?>,<?php echo json_encode($net_agustus23, JSON_NUMERIC_CHECK); ?>,<?php echo json_encode($net_september23, JSON_NUMERIC_CHECK); ?>,<?php echo json_encode($net_oktober23, JSON_NUMERIC_CHECK); ?>,<?php echo json_encode($net_november23, JSON_NUMERIC_CHECK); ?>,<?php echo json_encode($net_desember23, JSON_NUMERIC_CHECK); ?>],

                    color: '#FF0000',
                    fontWeight: 'bold',
                    fontSize: '10px',
                    fontFamily: 'helvetica',

                    zones: [{
                        value: [<?php echo json_encode($net_desember23, JSON_NUMERIC_CHECK); ?>],
                    }, {
                        dashStyle: 'dot'
                    }]
                }
                ]
            });
        });
        
    });
</script>
<script>
    (function(d, s, id) {
        if (d.getElementById(id)) {
            if (window.__TOMORROW__) {
                window.__TOMORROW__.renderWidget();
            }
            return;
        }
        const fjs = d.getElementsByTagName(s)[0];
        const js = d.createElement(s);
        js.id = id;
        js.src = "https://www.tomorrow.io/v1/widget/sdk/sdk.bundle.min.js";

        fjs.parentNode.insertBefore(js, fjs);
    })(document, 'script', 'tomorrow-sdk');
</script>

<script type="text/javascript">
    $(function () {
        var chart;
        $(document).ready(function() {
            chart = new Highcharts.Chart({
                chart: {
                    renderTo: 'container_laba_rugi',
                    type: 'line',
                    marginRight: 130,
                    marginBottom: 75,
                    backgroundColor: {
                        //linearGradient: [500, 0, 0, 700],
                        linearGradient: [0, 0, 700, 500],
                        stops: [
                            [0, 'rgb(204,204,204)'],
                            [1, 'rgb(204,204,204)']
                        ]
                    },
                },
                title: {
                    style: {
                        color: '#000000',
                        fontWeight: 'bold',
                        fontSize: '14px',
                        fontFamily: 'helvetica'
                    },
                    text: 'LABA RUGI',
                    x: -20 //center            
                },
                subtitle: {
                    style: {
                        color: '#000000',
                        fontWeight: 'bold',
                        fontSize: '14px',
                        fontFamily: 'helvetica'
                    },
                    text: 'PT. BIA BUMI JAYENDRA - TIGA DIHAJI',
                    x: -20
                },
                xAxis: { //X axis menampilkan data bulan
                    labels: {
                        style: {
                            color: '#000000',
                            fontWeight: 'bold',
                            fontSize: '10px',
                            fontFamily: 'helvetica'
                        }
                    },
                    //categories: ['Feb 22','Mar 22','Apr 22','Mei 22','Jun 22','Jul 22','Agu 22','Sep 22','Okt 22','Nov 22','Des 22','Jan 23','Feb 23','Mar 23','Apr 23','Mei 23','Jun 23','Jul 23','Agu 23','Sep 23','Okt 23','Nov 23','Des 23','Akumulasi']
                    categories: ['Jan 23','Feb 23','Mar 23','Apr 23','Mei 23','Jun 23','Jul 23','Agu 23','Sep 23','Okt 23','Nov 23','Des 23','Akumulasi']
                },
                yAxis: {
                    //title: {  //label yAxis
                        //text: 'RAP <br /><?php echo number_format(0,0,',','.'); ?>'
                        //text: 'Presentase'
                    //},
                    title: {
                        style: {
                            color: '#000000',
                            fontWeight: 'bold',
                            fontSize: '10px',
                            fontFamily: 'helvetica'
                        },
                        text: 'Presentase'           
                    },
                    plotLines: [{
                        value: 0,
                        width: 1,
                        color: '#808080' //warna dari grafik line
                    }],
                    labels: {
                        style: {
                            color: '#000000',
                            fontWeight: 'bold',
                            fontSize: '10px',
                            fontFamily: 'helvetica'
                        },
                        format: '{value} %'
                    },
                    min: -100,
                    max: 100,
                    tickInterval: 10,
                },
                tooltip: { 
                //fungsi tooltip, ini opsional, kegunaan dari fungsi ini 
                //akan menampikan data di titik tertentu di grafik saat mouseover
                    formatter: function() {
                            return '<b>'+ this.series.name +'</b><br/>'+ 
                            ''+ 'Presentase' +': '+ this.y + '%<br/>';
                            //''+ 'Vol' +': '+ this.x + '';

                            //'<b>'+ 'Presentase' +': '+ this.y +'%'</b><br/>'+ 
                            //'<b>'+ 'Penjualan' +': '+ this.y +'</b><br/>';
                    }
                },
                legend: {
                    layout: 'vertical',
                    align: 'right',
                    verticalAlign: 'top',
                    x: -10,
                    y: 100,
                    borderWidth: 0
                },

                plotOptions: {
                    spline: {
                        lineWidth: 4,
                        states: {
                            hover: {
                                lineWidth: 5
                            }
                        },
                        marker: {
                            enabled: true
                        }
                    }
                },
        
                series: [{  
                    name: 'Target %',  
                    
                    //data: [2.96,2.96,2.96,2.96,2.96,2.96,2.96,2.96,2.96,2.96,2.96,2.96,2.96,2.96,2.96,2.96,2.96,2.96,2.96,2.96,2.96,2.96,2.96,5.11],
                    data: [2.96,2.96,2.96,2.96,2.96,2.96,2.96,2.96,2.96,2.96,2.96,2.96,5.11],

                    color: '#000000',
                    fontWeight: 'bold',
                    fontSize: '10px',
                    fontFamily: 'helvetica'
                },
                {  
                    name: 'Laba Rugi %',  
                    
                    //data: [ <?php echo json_encode($presentase_laba_rugi_februari_fix, JSON_NUMERIC_CHECK); ?>,<?php echo json_encode($presentase_laba_rugi_maret_fix, JSON_NUMERIC_CHECK); ?>,<?php echo json_encode($presentase_laba_rugi_april_fix, JSON_NUMERIC_CHECK); ?>,<?php echo json_encode($presentase_laba_rugi_mei_fix, JSON_NUMERIC_CHECK); ?>,<?php echo json_encode($presentase_laba_rugi_juni_fix, JSON_NUMERIC_CHECK); ?>,<?php echo json_encode($presentase_laba_rugi_juli_fix, JSON_NUMERIC_CHECK); ?>,<?php echo json_encode($presentase_laba_rugi_agustus_fix, JSON_NUMERIC_CHECK); ?>,<?php echo json_encode($presentase_laba_rugi_september_fix, JSON_NUMERIC_CHECK); ?>,<?php echo json_encode($presentase_laba_rugi_oktober_fix, JSON_NUMERIC_CHECK); ?>,<?php echo json_encode($presentase_laba_rugi_november_fix, JSON_NUMERIC_CHECK); ?>,<?php echo json_encode($presentase_laba_rugi_desember_fix, JSON_NUMERIC_CHECK); ?>,<?php echo json_encode($presentase_laba_rugi_januari23_fix, JSON_NUMERIC_CHECK); ?>,<?php echo json_encode($presentase_laba_rugi_februari23_fix, JSON_NUMERIC_CHECK); ?>,<?php echo json_encode($presentase_laba_rugi_maret23_fix, JSON_NUMERIC_CHECK); ?>,<?php echo json_encode($presentase_laba_rugi_april23_fix, JSON_NUMERIC_CHECK); ?>,<?php echo json_encode($presentase_laba_rugi_mei23_fix, JSON_NUMERIC_CHECK); ?>,<?php echo json_encode($presentase_laba_rugi_juni23_fix, JSON_NUMERIC_CHECK); ?>,<?php echo json_encode($presentase_laba_rugi_juli23_fix, JSON_NUMERIC_CHECK); ?>,<?php echo json_encode($presentase_laba_rugi_agustus23_fix, JSON_NUMERIC_CHECK); ?>,<?php echo json_encode($presentase_laba_rugi_september23_fix, JSON_NUMERIC_CHECK); ?>,<?php echo json_encode($presentase_laba_rugi_oktober23_fix, JSON_NUMERIC_CHECK); ?>,<?php echo json_encode($presentase_laba_rugi_november23_fix, JSON_NUMERIC_CHECK); ?>,<?php echo json_encode($presentase_laba_rugi_desember23_fix, JSON_NUMERIC_CHECK); ?>,<?php echo json_encode($presentase_laba_rugi_akumulasi_fix, JSON_NUMERIC_CHECK); ?>],
                    data: [ <?php echo json_encode($presentase_laba_rugi_januari23_fix, JSON_NUMERIC_CHECK); ?>,<?php echo json_encode($presentase_laba_rugi_februari23_fix, JSON_NUMERIC_CHECK); ?>,<?php echo json_encode($presentase_laba_rugi_maret23_fix, JSON_NUMERIC_CHECK); ?>,<?php echo json_encode($presentase_laba_rugi_april23_fix, JSON_NUMERIC_CHECK); ?>,<?php echo json_encode($presentase_laba_rugi_mei23_fix, JSON_NUMERIC_CHECK); ?>,<?php echo json_encode($presentase_laba_rugi_juni23_fix, JSON_NUMERIC_CHECK); ?>,<?php echo json_encode($presentase_laba_rugi_juli23_fix, JSON_NUMERIC_CHECK); ?>,<?php echo json_encode($presentase_laba_rugi_agustus23_fix, JSON_NUMERIC_CHECK); ?>,<?php echo json_encode($presentase_laba_rugi_september23_fix, JSON_NUMERIC_CHECK); ?>,<?php echo json_encode($presentase_laba_rugi_oktober23_fix, JSON_NUMERIC_CHECK); ?>,<?php echo json_encode($presentase_laba_rugi_november23_fix, JSON_NUMERIC_CHECK); ?>,<?php echo json_encode($presentase_laba_rugi_desember23_fix, JSON_NUMERIC_CHECK); ?>,<?php echo json_encode($presentase_laba_rugi_akumulasi_fix, JSON_NUMERIC_CHECK); ?>],

                    color: '#FF0000',
                    fontWeight: 'bold',
                    fontSize: '10px',
                    fontFamily: 'helvetica',

                    zones: [{
                        
                    }, {
                        dashStyle: 'dot'
                    }]
                }
                ]
            });
        });
        
    });
</script>

<script type="text/javascript">
    $(function () {
        var chart;
        $(document).ready(function() {
            chart = new Highcharts.Chart({
                chart: {
                    renderTo: 'container_rencana_kerja_perminggu',
                    type: 'column',
                    marginRight: 130,
                    marginBottom: 75,
                    backgroundColor: {
                        linearGradient: [0, 0, 700, 500],
                        stops: [
                            [0, 'rgb(182,150,119)'],
                            [1, 'rgb(182,150,119)']
                        ]
                    },
                },
                title: {
                    style: {
                        color: '#000000',
                        fontWeight: 'bold',
                        fontSize: '14px',
                        fontFamily: 'helvetica'
                    },
                    text: 'REALISASI RENCANA KERJA PERMINGGU',
                    x: -20 //center            
                },
                subtitle: {
                    style: {
                        color: '#000000',
                        fontWeight: 'bold',
                        fontSize: '14px',
                        fontFamily: 'helvetica'
                    },
                    text: '(<?php echo tgl_indo(date('-m-'));?>)'.toUpperCase(),
                    x: -20
                },
                xAxis: { //X axis menampilkan data bulan
                    labels: {
                        style: {
                            color: '#000000',
                            fontWeight: 'bold',
                            fontSize: '10px',
                            fontFamily: 'helvetica'
                        }
                    },
                    categories: ['Minggu 1 <br /><?php echo $date_minggu_1_awal = date('01 F Y', strtotime($date_now));?> - <?php echo $date_minggu_1_akhir = date('d F Y', strtotime($date_minggu_1_akhir));?>','Minggu 2 <br /><?php echo $date_minggu_2_awal = date('d F Y', strtotime('+1 days', strtotime($date_minggu_1_akhir)));?> - <?php echo $date_minggu_2_akhir = date('d F Y', strtotime($date_minggu_2_akhir));?>','Minggu 3 <br /><?php echo $date_minggu_3_awal = date('d F Y', strtotime('+1 days', strtotime($date_minggu_2_akhir)));?> - <?php echo $date_minggu_3_akhir = date('d F Y', strtotime($date_minggu_3_akhir));?>','Minggu 4 <br /><?php echo $date_minggu_4_awal = date('d F Y', strtotime('+1 days', strtotime($date_minggu_3_akhir)));?> - <?php echo $date_minggu_4_akhir = date('d F Y', strtotime($date_minggu_4_akhir));?>']
                },
                yAxis: {
                    //title: {  //label yAxis
                        //text: 'RAP <br /><?php echo number_format(0,0,',','.'); ?>'
                        //text: 'Presentase'
                    //},
                    title: {
                        style: {
                            color: '#000000',
                            fontWeight: 'bold',
                            fontSize: '10px',
                            fontFamily: 'helvetica'
                        },
                        text: 'Volume Produksi (M3)'           
                    },
                    plotLines: [{
                        value: 0,
                        width: 1,
                        color: '#808080' //warna dari grafik line
                    }],
                    labels: {
                        style: {
                            color: '#000000',
                            fontWeight: 'bold',
                            fontSize: '10px',
                            fontFamily: 'helvetica'
                        },
                        format: '{value}'
                    },
                    min: 0,
                    max: 2500,
                    tickInterval: 500,
                },
                tooltip: { 
                //fungsi tooltip, ini opsional, kegunaan dari fungsi ini 
                //akan menampikan data di titik tertentu di grafik saat mouseover
                    formatter: function() {
                            return '<b>'+ this.series.name +'</b><br/>'+ 
                            ''+ 'Volume Produksi' +': '+ this.y + ' M3<br/>';
                            //''+ 'Vol' +': '+ this.x + '';

                            //'<b>'+ 'Presentase' +': '+ this.y +'%'</b><br/>'+ 
                            //'<b>'+ 'Penjualan' +': '+ this.y +'</b><br/>';
                    }
                },
                legend: {
                    layout: 'vertical',
                    align: 'right',
                    verticalAlign: 'top',
                    x: -10,
                    y: 100,
                    borderWidth: 0
                },

                plotOptions: {
                    spline: {
                        lineWidth: 4,
                        states: {
                            hover: {
                                lineWidth: 5
                            }
                        },
                        marker: {
                            enabled: true
                        }
                    }
                },
        
                series: [{  
                    name: 'Rencana Kerja',  
                
                    data: [<?php echo json_encode($rencana_kerja_perminggu_fix, JSON_NUMERIC_CHECK); ?>,<?php echo json_encode($rencana_kerja_perminggu_fix, JSON_NUMERIC_CHECK); ?>,<?php echo json_encode($rencana_kerja_perminggu_fix, JSON_NUMERIC_CHECK); ?>,<?php echo json_encode($rencana_kerja_perminggu_fix, JSON_NUMERIC_CHECK); ?>],

                    color: '#e69500 ',
                    fontWeight: 'bold',
                    fontSize: '10px',
                    fontFamily: 'helvetica'
                },
                {  
                    name: 'Realisasi',  
                    
                    data: [ <?php echo json_encode($penjualan_minggu_1_fix, JSON_NUMERIC_CHECK); ?>,<?php echo json_encode($penjualan_minggu_2_fix, JSON_NUMERIC_CHECK); ?>,<?php echo json_encode($penjualan_minggu_3_fix, JSON_NUMERIC_CHECK); ?>,<?php echo json_encode($penjualan_minggu_4_fix, JSON_NUMERIC_CHECK); ?>],

                    color: '#2986CC',
                    fontWeight: 'bold',
                    fontSize: '10px',
                    fontFamily: 'helvetica',

                    zones: [{
                        
                    }, {
                        dashStyle: 'dot'
                    }]
                }
                ]
            });
        });
        
    });
</script>

<!-- Script RAP x Prognosa -->
<script type="text/javascript">

    function TableRAP()
    {
        $('#wait-rap').fadeIn('fast');   
        $.ajax({
            type    : "POST",
            url     : "<?php echo site_url('pmm/reports/dashboard_rap'); ?>/"+Math.random(),
            dataType : 'html',
            data: {
                filter_date : $('#filter_date_rap').val(),
            },
            success : function(result){
                $('#box-rap').html(result);
                $('#wait-rap').fadeOut('fast');
            }
        });
    }

    TableRAP();
</script>

<!-- Script Evaluasi -->
<script type="text/javascript">
    $('#filter_date_evaluasi').daterangepicker({
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

    $('#filter_date_evaluasi').on('apply.daterangepicker', function(ev, picker) {
        $(this).val(picker.startDate.format('DD-MM-YYYY') + ' - ' + picker.endDate.format('DD-MM-YYYY'));
        TableEvaluasi();
    });


    function TableEvaluasi()
    {
        $('#wait-evaluasi').fadeIn('fast');   
        $.ajax({
            type    : "POST",
            url     : "<?php echo site_url('pmm/reports/dashboard_evaluasi_bahan'); ?>/"+Math.random(),
            dataType : 'html',
            data: {
                filter_date : $('#filter_date_evaluasi').val(),
            },
            success : function(result){
                $('#box-ajax-evaluasi').html(result);
                $('#wait-evaluasi').fadeOut('fast');
            }
        });
    }

    TableEvaluasi();
</script>

<!-- Script Laba Rugi -->
<script type="text/javascript">
    $('.dtpicker').daterangepicker({
        autoUpdateInput : false,
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
        }
    });

    

    function LostProfit(CharData)
    {
        var ctx = document.getElementById('canvas').getContext('2d');
        window.myBar = new Chart(ctx, {
            type: 'line',
            data: CharData,
            options: {
                title: {
                    display: true,
                },
                responsive: true,
                scales: {
                    xAxes: [{
                        stacked: true
                        
                    }],
                    yAxes: [{
                        stacked: true,
                        ticks: {
                            beginAtZero: true,
                            //min: -1500,
                            //max: 1500
                        },
                    }]
                },
                legend: {
                    display: true,
                    position : 'bottom'
                },
                responsive: true,
                maintainAspectRatio: false,
                hoverMode: 'index',
                tooltips: {
                    callbacks: {
                    title: function(tooltipItem, data) {
                        return data['labels'][tooltipItem[0]['index']];
                    },
                    beforeLabel : function(tooltipItem, data) {
                        return 'Pendapatan = '+data['datasets'][0]['data_revenue'][tooltipItem['index']];
                    },
                    label: function(tooltipItem, data) {
                        return 'Biaya = '+data['datasets'][0]['data_revenuecost'][tooltipItem['index']];
                    },
                    afterLabel : function(tooltipItem, data) {
                        return 'Laba Rugi = '+data['datasets'][0]['data_laba'][tooltipItem['index']]+ ' ('+data['datasets'][0]['data'][tooltipItem['index']]+'%)';
                    },
                    },
                }
            }
        });

    }


    function getLostProfit()
    {
        $.ajax({
            type    : "POST",
            url     : "<?php echo base_url();?>pmm/db_lost_profit/"+Math.random(),
            dataType : 'json',
            data: {arr_date : $('#filter_lost_profit').val()},
            beforeSend : function(){
                $('#wait-1').show();
            },
            success : function(result){
                $('#canvas').remove();
                $('#parent-lost-profit').append('<canvas id="canvas"></canvas>');
                LostProfit(result);
                $('#wait-1').hide();
            }
        });
    }
    getLostProfit();
    $('#filter_lost_profit').on('apply.daterangepicker', function(ev, picker) {
            $(this).val(picker.startDate.format('DD-MM-YYYY') + ' - ' + picker.endDate.format('DD-MM-YYYY'));
            getLostProfit();
    });
</script>

<!--Start of Tawk.to Script-->
<script type="text/javascript">
    var Tawk_API=Tawk_API||{}, Tawk_LoadStart=new Date();
    (function(){
    var s1=document.createElement("script"),s0=document.getElementsByTagName("script")[0];
    s1.async=true;
    s1.src='https://embed.tawk.to/64e6c76694cf5d49dc6c2cd7/1h8inlra1';
    s1.charset='UTF-8';
    s1.setAttribute('crossorigin','*');
    s0.parentNode.insertBefore(s1,s0);
    })();
</script>
<!--End of Tawk.to Script-->

<script src="erp/assets/jquery.js"></script>
<script src="erp/assets/wow/wow.min.js"></script>
<script src="erp/assets/bootstrap/js/bootstrap.js" type="text/javascript"></script>
<script src="erp/assets/mobile/touchSwipe.min.js"></script>
<script src="erp/assets/respond/respond.js"></script>
<script src="erp/assets/gallery/jquery.blueimp-gallery.min.js"></script>\
<script src="erp/assets/script.js"></script>

</body>
</html>
