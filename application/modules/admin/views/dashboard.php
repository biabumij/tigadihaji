<!doctype html>
<html lang="en" class="fixed">
<head>
<?php echo $this->Templates->Header();?>
<script type = "text/JavaScript">
    function AutoRefresh( t ) {
        setTimeout("location.reload(true);", t);
    }
</script>
</head>
<style type="text/css">
    body {
        font-family: helvetica;
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
        <?php echo $this->Templates->LeftBar();?>
        <div class="content">
            <div class="content-header">
                <div class="leftside-content-header">
                    <ul class="breadcrumbs">
                        <li><i class="fa fa-home" aria-hidden="true"></i><a href="#">Dashboard</a></li>
                    </ul>
                </div>
            </div>

            <?php include_once("script_dashboard.php"); ?>

            <div class="content-body">
                <div class="row animated fadeInUp">

                    <div class="col-sm-12">
                        <figure class="highcharts-figure">
                            <div id="container" style="border-radius:10px;"></div>
                        
                        </figure>
                        <br />
                    </div>
            
                    <div class="col-sm-12">
                        <figure class="highcharts-figure">
                            <div id="container_laba_rugi" style="border-radius:10px;"></div>
                            
                        </figure>
                        <br />
                    </div>
                    
                    <div class="col-sm-12">
                        <figure class="highcharts-figure">
                            <div id="container_rencana_kerja_perminggu" style="border-radius:10px;"></div>
                            
                        </figure>
                        <br />
                    </div>
            
                    <div role="tabpanel" class="tab-pane" id="laporan_rap">
                        <div class="col-sm-8">
                            <div class="panel" style="border-radius:10px;">
                                    <div class="panel-heading">
                                        <center><h3 class="panel-title"><b>Presentase Prognosa Terhadap RAP</b></h3></center>
                                    </div>
                                    <div style="margin: 20px">
                                        <div id="wait-rap" style=" text-align: center; align-content: center; display: none;">	
                                            <div>Please Wait</div>
                                            <div class="fa-3x">
                                                <i class="fa fa-spinner fa-spin"></i>
                                            </div>
                                        </div>				
                                        <div class="table-responsive" id="box-rap">													
                                        
                                        </div>
                                    </div>
                            </div>
                        </div>
                    </div>
        
                    <div role="tabpanel" class="tab-pane" id="laporan_evaluasi">
                        <div class="col-sm-8">
                            <div class="panel" style="border-radius:10px;">
                                <div class="panel-heading">
                                    <center><h3 class="panel-title"><b>Evaluasi Pemakaian Bahan Baku</b></h3></center>
                                </div>
                                <div style="margin: 20px">
                                    <!--<div class="row"> 
                                        <div class="col-sm-4">
                                            <input type="text" id="filter_date_evaluasi" name="filter_date" class="form-control dtpicker"  autocomplete="off" placeholder="Filter By Date">
                                        </div>   
                                    </div>
                                    <br />-->
                                    <div id="wait-evaluasi" style=" text-align: center; align-content: center; display: none;">	
                                        <div>Please Wait</div>
                                        <div class="fa-3x">
                                            <i class="fa fa-spinner fa-spin"></i>
                                        </div>
                                    </div>				
                                    <div class="table-responsive" id="box-ajax-evaluasi">													
                                    

                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!--<div class="col-sm-10">
                        <div class="panel panel-default">
                            <div class="panel-heading">
                                <h3 class="panel-title">Laba Rugi</h3>
                            </div>
                            <div style="margin: 20px">
                                <div class="row">
                                    <div class="col-sm-4">
                                        <input type="text" name="" id="filter_lost_profit" class="form-control dtpicker" placeholder="Filter">
                                    </div>
                                </div>
                                <br />
                                <div id="wait" style=" text-align: center; align-content: center; display: none;">	
                                    <div>Please Wait</div>
                                        <div class="fa-3x">
                                            <i class="fa fa-spinner fa-spin"></i>
                                        </div>
                                </div>		
                                <div id="parent-lost-profit" class="chart-container">
                                    <canvas id="canvas"></canvas>
                                </div>
                            </div>
                        </div>
                    </div>-->

                </div>  
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

</body>
</html>
