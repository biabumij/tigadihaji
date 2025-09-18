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
    </head>
    <style type="text/css">
        body {
            font-family: helvetica;
            /*background-color: #ffffff !important;*/
        }

        a:hover {
            color: white;
        }

        th, td {
            padding: 15px;
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

        blink {
        -webkit-animation: 2s linear infinite kedip; /* for Safari 4.0 - 8.0 */
        animation: 2s linear infinite kedip;
        }
        /* for Safari 4.0 - 8.0 */
        @-webkit-keyframes kedip { 
        0% {
            visibility: hidden;
        }
        50% {
            visibility: hidden;
        }
        100% {
            visibility: visible;
        }
        }
        @keyframes kedip {
        0% {
            visibility: hidden;
        }
        50% {
            visibility: hidden;
        }
        100% {
            visibility: visible;
        }
        }

        /* Animation */
        @keyframes Gradient{0%{background-position:0 50%}50%{background-position:100% 50%}100%{background-position:0 50%}}
        #flippy{text-align:center;margin:auto;display:inline}#flippy button{border-color:rgba(0,0,0,0.0);background:#fff;background-image:linear-gradient(to right,#FF0000 0%,#333333 51%,#FF0000 100%);background-size:200% auto;color:#fff;display:block;width:80%;padding:15px;font-weight:700;font-size:14px;text-align:center;text-transform:uppercase;letter-spacing:0.5px;margin:10px auto;border-radius:10px;box-shadow:0 2px 3px rgba(0,0,0,0.06),0 2px 3px rgba(0,0,0,0.1);transition:all .3s}#flippy button:hover,#flippy button:focus{background-position: right center;outline:none;opacity:1;color:#fff}#flippanel{display:none;padding:10px 0;text-align:left;background:#fff;margin:10px 0 0 0}#flippanel img{background:#f5f5f5;margin:10px auto}

        /* Animation */
        @keyframes Gradient{0%{background-position:0 50%}50%{background-position:100% 50%}100%{background-position:0 50%}}
        #flippy_menu{text-align:center;margin:auto;display:inline}#flippy_menu button{border-color:rgba(0,0,0,0.0);background:#fff;background-image:linear-gradient(to right,#0b5394 0%,#333333 51%,#0b5394 100%);background-size:200% auto;color:#fff;display:block;width:80%;padding:15px;font-weight:700;font-size:14px;text-align:center;text-transform:uppercase;letter-spacing:0.5px;margin:10px auto;border-radius:10px;box-shadow:0 2px 3px rgba(0,0,0,0.06),0 2px 3px rgba(0,0,0,0.1);transition:all .3s}#flippy_menu button:hover,#flippy_menu button:focus{background-position: right center;outline:none;opacity:1;color:#fff}#flippanel{display:none;padding:10px 0;text-align:left;background:#fff;margin:10px 0 0 0}#flippanel img{background:#f5f5f5;margin:10px auto}
    
        button {
			border: none;
			border-radius: 5px;
			padding: 5px;
			font-size: 12px;
			text-transform: uppercase;
			cursor: pointer;
			color: white;
			background-color: none;
			box-shadow: 0 0 4px #999;
			outline: none;
		}
    </style>
    <body onload = "JavaScript:AutoRefresh(360000);">
    <body>
        <div class="wrap-dashboard">
            <?php echo $this->Templates->PageHeader();?>
            <div class="page-body">
                <div id="about" class="container spacer about">
                    <!--<div class="col-sm-12" style="background-color: rgba(56,56,56, 0.8); font-size:18px; border-radius: 5px; padding:10px; margin-bottom:50px; color:white;">
                        <center><b>PROYEK BENDUNGAN TIGA DIHAJI</b></center>
                        <?php
                        if(in_array($this->session->userdata('admin_group_id'), array(1,2,3))){
                        ?>
                        <figure class="highcharts-figure">
                            <?php
                            $query1 = $this->db->select('COUNT(pvp.id) as id')
                            ->from('pmm_verifikasi_penagihan_pembelian pvp')
                            ->where("pvp.approve_unit_head = 'TIDAK DISETUJUI'")
                            ->get()->row_array();

                            $query2 = $this->db->select('COUNT(ppo.id) as id')
                            ->from('pmm_purchase_order ppo')
                            ->where("ppo.status = 'WAITING'")
                            ->get()->row_array();
                            
                            $query = $query1['id'] + $query2['id'];
                            ?>
                                <center><b><a target="_blank" href="<?= base_url("pmm/reports/detail_notification/") ?>"><i class="fa-solid fa-clipboard-check"></i> VERIFIKASI PEMBELIAN (<blink><?php echo number_format($query,0,',','.');?></blink>)</a><b></center>
                            <?php
                            }
                            ?>

                            <?php
                            if(in_array($this->session->userdata('admin_group_id'), array(1,4))){
                            ?>
                            <?php
                            $query = $this->db->select('COUNT(req.id) as id')
                            ->from('pmm_request_materials req')
                            ->where("req.status = 'WAITING'")
                            ->get()->row_array();
                            
                            $query = $query['id'];
                            ?>
                                <center><b><a target="_blank" href="<?= base_url("pmm/reports/detail_notification_2/") ?>"><i class="fa-solid fa-clipboard-check"></i> BUTUH PERSETUJUAN KA. PLANT (<blink><?php echo number_format($query,0,',','.');?></blink>)</a><b></center>
                            <?php
                            }
                            ?>
                            <?php
                            if(in_array($this->session->userdata('admin_group_id'), array(1))){
                            ?>
                            <?php
                            $query = $this->db->select('COUNT(id) as id')
                            ->from('perubahan_sistem')
                            ->where("status = 'UNPUBLISH'")
                            ->get()->row_array();
                            
                            $query = $query['id'];
                            ?>
                                <center><a target="_blank" href="<?= base_url("pmm/reports/detail_notification_3/") ?>"><i class="fa-solid fa-clipboard-check"></i> BUTUH PERSETUJUAN TI & SISTEM (<blink><?php echo number_format($query,0,',','.');?></blink>)</a></center>
                            <?php
                            }
                            ?>
                        </figure>
                    </div>--> 
                    <div id="flippy">
                        <button title="Click to show/hide content" type="button" onclick="if(document.getElementById('spoiler') .style.display=='none') {document.getElementById('spoiler') .style.display=''}else{document.getElementById('spoiler') .style.display='none'}"><i class="fa-regular fa-hand-point-right"></i> GRAFIK</button>
                    </div>
                    <!--<div id="spoiler" style="display:none">-->
                    <div id="spoiler" style="display:block">
                        <?php include_once("script_dashboard.php"); ?>
                        <div class="row animated fadeInUp">
                            <table width="100%" border="0" cellpadding="100px">
                                <tr>
                                    <th width="50%" class="text-center">
                                        <div class="col-sm-12">
                                            <figure class="highcharts-figure">
                                                <div id="container" style="border-radius:10px;"></div>
                                            </figure>
                                        </div>
                                    </th>
                                </tr>
                                <tr>
                                    <th width="50%" class="text-center">
                                        <div class="col-sm-12">
                                            <figure class="highcharts-figure">
                                                <div id="container_laba_rugi" style="border-radius:10px;"></div>
                                            </figure>
                                        </div>
                                    </th>
                                </tr>
                                <tr>
                                    <th width="50%" class="text-center">
                                        <?php
                                        $admin_name = $this->db->select('admin_name')
                                        ->from('tbl_admin')
                                        ->where("admin_id <> '1'")
                                        ->where("admin_id <> '5'")
                                        ->where("admin_id <> '12'")
                                        ->group_by('admin_id')
                                        ->order_by('admin_name','asc')
                                        ->get()->result_array();

                                        $jenis_transaksi = $this->db->select('transaksi')
                                        ->from('transactions')
                                        ->group_by('transaksi')
                                        ->order_by('id','asc')
                                        ->get()->result_array();
                                        ?>
                                        <div class="col-sm-12" style="background:rgb(242, 242, 242, 0.8); border-radius:5px;">
                                            <br />
                                            <center>LAST TRANSACTIONS</center>
                                            <br />
                                            <div class="col-sm-4">
                                                <input type="text" id="filter_date_transactions" name="filter_date" class="form-control dtpicker input-sm" value="" placeholder="Pilih Tanggal Input" autocomplete="off">
                                            </div>
                                            <div class="col-sm-4">
                                                <select id="filter_jenis_transaksi" name="filter_jenis_transaksi" class="form-control select2">
                                                    <option value="">Pilih Jenis Transkasi</option>
                                                    <?php
                                                    foreach ($jenis_transaksi as $key => $x) {
                                                    ?>
                                                        <option value="<?php echo $x['transaksi']; ?>"><?php echo $x['transaksi']; ?></option>
                                                    <?php
                                                    }
                                                    ?>
                                                </select>
                                            </div>
                                            <div class="col-sm-4">
                                                <select id="filter_admin_name" name="filter_admin_name" class="form-control select2">
                                                    <option value="">Pilih Penginput</option>
                                                    <?php
                                                    foreach ($admin_name as $key => $x) {
                                                    ?>
                                                        <option value="<?php echo $x['admin_name']; ?>"><?php echo $x['admin_name']; ?></option>
                                                    <?php
                                                    }
                                                    ?>
                                                </select>
                                            </div>
                                            <table class="table table-striped table-hover" id="table-transactions" style="width:100%;">
                                                <thead>
                                                    <tr>
                                                        <th>No.</th>
                                                        <th>Dibuat Tanggal</th>
                                                        <th>Dibuat Oleh</th>
                                                        <th>Jenis Transaksi</th>
                                                        <th>Tanggal Transaksi</th>
                                                        <th>Nomor Transaksi</th> 
                                                        <th>Debit</th>
                                                        <th>Kredit</th>                                               
                                                    </tr>
                                                </thead>
                                                <tbody>

                                                </tbody>
                                            </table>
                                        </div>
                                    </th>
                                </tr>
                            </table>
                        </div>
                    </div>

                    <?php
                    $admin_id = $this->session->userdata('admin_id');
                    $approval = $this->db->select('*')
                    ->from('tbl_admin')
                    ->where("admin_id = $admin_id ")
                    ->get()->row_array();
                    $menu_admin =  $approval['menu_admin'];
                    $kunci_rakor =  $approval['kunci_rakor'];
                    ?>
                    <div id="flippy_menu">
                        <button title="Click to show/hide content" type="button" onclick="if(document.getElementById('spoiler_menu') .style.display=='none') {document.getElementById('spoiler_menu') .style.display=''}else{document.getElementById('spoiler_menu') .style.display='none'}"><i class="fa-regular fa-hand-point-right"></i> MENU</button>
                    </div>
                    <div id="spoiler_menu" style="display:block">        
                        <div class="process">
                            <table width="100%" style="margin-top:100px;">
                                <tr>
                                    <th width="50%" class="text-center" data-toggle="collapse" data-target="#produksi" aria-expanded="false" aria-controls="sc">
                                        <ul class="row text-center list-inline  wowload bounceIn" style="border-radius:20px;">
                                            <li style="background: linear-gradient(110deg, #3b75a9 20%, #3b75a9 20%, #0B5394 80%);">
                                                <a>
                                                <span style="color:#fffdd0;"><i class="fa-solid fa-person-digging"></i><b>PRODUKSI</b></span></a>
                                            </li>
                                        </ul>
                                    </th>
                                    <th width="50%" class="text-center" data-toggle="collapse" data-target="#keuangan" aria-expanded="false" aria-controls="kp">
                                        <ul class="row text-center list-inline  wowload bounceIn" style="border-radius:20px;">
                                            <li style="background: linear-gradient(110deg, #c37e37 20%, #c37e37 20%, #B45F06 80%);">
                                                <a>
                                                <span style="color:#fffdd0;"><i class="fa-solid fa-money-bill"></i><b>KEUANGAN</b></span></a>
                                            </li>
                                        </ul>
                                    </th>
                                </tr>
                            </table>
                            <table width="100%">
                                <tr>
                                    <th width="50%" class="text-center">
                                        <ul class="row text-center list-inline  wowload bounceInUp collapse" id="produksi">
                                            <li class="text-center" style="background: linear-gradient(110deg, #3b75a9 20%, #3b75a9 20%, #0B5394 80%);">
                                                <a href="<?php echo site_url('admin/rap');?>">
                                                <span style="color:#fffdd0;"><i class="fa-regular fa-calendar-check"></i><b>RAP</b></span></a>
                                            </li>
                                            <li class="text-center" style="background: linear-gradient(110deg, #3b75a9 20%, #3b75a9 20%, #0B5394 80%);">
                                                <a href="<?php echo site_url('admin/rencana_kerja');?>">
                                                <span style="color:#fffdd0;"><i class="fa-solid fa-calendar-days"></i><b>R. KERJA</b></span></a>
                                            </li>
                                            <li class="text-center" style="background: linear-gradient(110deg, #3b75a9 20%, #3b75a9 20%, #0B5394 80%);">
                                                <a href="<?php echo site_url('admin/stock_opname');?>">
                                                <span style="color:#fffdd0;"><i class="fa-solid fa-cubes"></i><b>STOK</b></span></a>
                                            </li>
                                            <br />
                                            <br />
                                            <li class="text-center" style="background: linear-gradient(110deg, #3b75a9 20%, #3b75a9 20%, #0B5394 80%);">
                                                <a href="<?php echo site_url('admin/penjualan');?>">
                                                <span style="color:#fffdd0;"><i class="fa-solid fa-bag-shopping"></i><b>PENJUALAN</b></span></a>
                                            </li>
                                            <li class="text-center" style="background: linear-gradient(110deg, #3b75a9 20%, #3b75a9 20%, #0B5394 80%);">
                                                <a href="<?php echo site_url('admin/pembelian');?>">
                                                <span style="color:#fffdd0;"><i class="fa-solid fa-cart-shopping"></i><b>PEMBELIAN</b></span></a>
                                            </li>
                                        </ul>
                                    </th>
                                    <th width="50%" class="text-center">
                                        <ul class="row text-center list-inline  wowload bounceInUp collapse" id="keuangan">
                                            <li class="text-center" style="background: linear-gradient(110deg, #c37e37 20%, #c37e37 20%, #B45F06 80%);">
                                                <a href="<?php echo site_url('admin/biaya_bua');?>">
                                                <span style="color:#fffdd0;"><i class="fa-solid fa-dollar-sign"></i><b>BUA</b></span></a>
                                            </li>
                                            <li class="text-center" style="background: linear-gradient(110deg, #c37e37 20%, #c37e37 20%, #B45F06 80%);">
                                                <a href="<?php echo site_url('admin/jurnal_umum');?>">
                                                <span style="color:#fffdd0;"><i class="fa-solid fa-scale-balanced"></i><b>JURNAL</b></span></a>
                                            </li>
                                            <br />
                                            <br />
                                            <li class="text-center" style="background: linear-gradient(110deg, #c37e37 20%, #c37e37 20%, #B45F06 80%);">
                                                <a href="<?php echo site_url('admin/rencana_cash_flow');?>">
                                                <span style="color:#fffdd0;"><i class="fa-solid fa-cash-register"></i><b>CASH FLOW</b></span></a>
                                            </li>
                                            <li class="text-center" style="background: linear-gradient(110deg, #c37e37 20%, #c37e37 20%, #B45F06 80%);">
                                                <a href="<?php echo site_url('admin/kas_&_bank');?>">
                                                <span style="color:#fffdd0;"><i class="fa-solid fa-building-columns"></i><b>KAS & BANK</b></span></a>
                                            </li>
                                        </ul>
                                    </th>
                                </tr>
                            </table>
                            <table width="100%">
                                <tr>
                                    <th width="50%" class="text-center" data-toggle="collapse" data-target="#laporan" aria-expanded="false" aria-controls="beton">
                                        <ul class="row text-center list-inline  wowload bounceIn" style="border-radius:20px;">
                                            <li style="background: linear-gradient(110deg, #5f914a 20%, #5f914a 20%, #38761D 80%);">
                                                <a>
                                                <span style="color:#fffdd0;"><i class="fa-solid fa-chart-line"></i><b>LAPORAN</b></span></a>
                                            </li>
                                        </ul>
                                    </th>
                                    <th width="50%" class="text-center" data-toggle="collapse" data-target="#master" aria-expanded="false" aria-controls="beton">
                                        <ul class="row text-center list-inline  wowload bounceIn" style="border-radius:20px;">
                                            <li style="background: linear-gradient(110deg, #ad3232 20%, #ad3232 20%, #990000 80%);">
                                                <a>
                                                <span style="color:#fffdd0;"><i class="fa-solid fa-database"></i><b>MASTER<br />DATA</b></span></a>
                                            </li>
                                        </ul>
                                    </th>
                                </tr>
                            </table>
                            <table width="100%">
                                <tr>
                                    <th width="25%" class="text-center">
                                        <ul class="row text-center list-inline  wowload bounceInUp collapse" id="laporan">
                                            <li class="text-center" style="background: linear-gradient(110deg, #5f914a 20%, #5f914a 20%, #38761D 80%);">
                                                <a href="<?php echo site_url('admin/laporan_produksi');?>">
                                                <span style="color:#fffdd0;"><i class="fa-solid fa-person-digging"></i><b>PRODUKSI</b></span></a>
                                            </li>
                                            <li class="text-center" style="background: linear-gradient(110deg, #5f914a 20%, #5f914a 20%, #38761D 80%);">
                                                <a href="<?php echo site_url('admin/laporan_keuangan');?>">
                                                <span style="color:#fffdd0;"><i class="fa-solid fa-money-bill"></i><b>KEUANGAN</b></span></a>
                                            </li>
                                        </ul>
                                    </th>
                                    
                                    <th width="25%" class="text-center">
                                        <ul class="row text-center list-inline  wowload bounceInUp collapse" id="master">
                                            <li class="text-center" style="background: linear-gradient(110deg, #ad3232 20%, #ad3232 20%, #990000 80%);">
                                                <a href="<?php echo site_url('admin/kontak');?>">
                                                <span style="color:#fffdd0;"><i class="fa-solid fa-address-book"></i><b>KONTAK</b></span></a>
                                            </li>
                                            <li class="text-center" style="background: linear-gradient(110deg, #ad3232 20%, #ad3232 20%, #990000 80%);">
                                                <a href="<?php echo site_url('admin/produk');?>">
                                                <span style="color:#fffdd0;"><i class="fa-solid fa-cart-flatbed-suitcase"></i><b>PRODUK</b></span></a>
                                            </li>
                                            <br />
                                            <br />
                                            <li class="text-center" style="background: linear-gradient(110deg, #ad3232 20%, #ad3232 20%, #990000 80%);">
                                                <a href="<?php echo site_url('admin/daftar_akun');?>">
                                                <span style="color:#fffdd0;"><i class="fa-solid fa-hand-holding-dollar"></i><b>AKUN</b></span></a>
                                            </li>
                                            <li class="text-center" style="background: linear-gradient(110deg, #ad3232 20%, #ad3232 20%, #990000 80%);">
                                                <a href="<?php echo site_url('admin/satuan');?>">
                                                <span style="color:#fffdd0;"><i class="fa-solid fa-ruler-vertical"></i><b>SATUAN</b></span></a>
                                            </li>
                                            <br />
                                            <br />
                                            <?php
                                            if($menu_admin == 1){
                                            ?>
                                            <li class="text-center" style="background: linear-gradient(110deg, #ad3232 20%, #ad3232 20%, #990000 80%);">
                                                <a href="<?php echo site_url('admin/perusahaan');?>">
                                                <span style="color:#fffdd0;"><i class="fa-solid fa-building"></i><b>PERUSAHAAN</b></span></a>
                                            </li>
                                            <?php
                                            }
                                            ?>
                                        </ul>
                                    </th>
                                </tr>
                            </table>
                            <table width="100%">
                                <tr>
                                    <?php
                                    if($kunci_rakor == 1){
                                    ?>
                                    <th width="25%" class="text-center" data-toggle="collapse" data-target="#kunci" aria-expanded="false" aria-controls="beton">
                                        <ul class="row text-center list-inline  wowload bounceIn" style="border-radius:20px;">
                                            <li class="text-center" style="background: linear-gradient(110deg, #696969 20%, #696969 20%, #444444 80%);">
                                                <a href="<?php echo site_url('admin/kunci');?>">
                                                <span style="color:#fffdd0;"><i class="fa-solid fa-key"></i><b>KUNCI DATA & AKSES</b></span></a>
                                            </li>
                                        </ul>
                                    </th>
                                    <?php
                                    }
                                    ?>

                                    <?php
                                    if($menu_admin == 1){
                                    ?>
                                    <th width="25%" class="text-center" data-toggle="collapse" data-target="#settings" aria-expanded="false" aria-controls="beton">
                                        <ul class="row text-center list-inline  wowload bounceIn" style="border-radius:20px;">
                                            <li class="text-center" style="background: linear-gradient(110deg, #696969 20%, #696969 20%, #444444 80%);">
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
                            <table width="100%">
                                <tr>
                                    <th width="25%" class="text-center"></th>
                                    <th width="25%" class="text-center">
                                        <ul class="row text-center list-inline  wowload bounceInUp collapse" id="settings">
                                            <li class="text-center" style="background: linear-gradient(110deg, #696969 20%, #696969 20%, #444444 80%);">
                                                <a href="<?php echo site_url('admin/menu');?>">
                                                <span style="color:#fffdd0;"><i class="fa-solid fa-bars"></i><b>MENU</b></span></a>
                                            </li>
                                            <li class="text-center" style="background: linear-gradient(110deg, #696969 20%, #696969 20%, #444444 80%);">
                                                <a href="<?php echo site_url('admin/admin_access');?>">
                                                <span style="color:#fffdd0;"><i class="fa-solid fa-eye"></i><b>ACCESS</b></span></a>
                                            </li>
                                            <li class="text-center" style="background: linear-gradient(110deg, #696969 20%, #696969 20%, #444444 80%);">
                                                <a href="<?php echo site_url('admin/admin');?>">
                                                <span style="color:#fffdd0;"><i class="fa-solid fa-user-secret"></i><b>ADMIN</b></span></a>
                                            </li>
                                        </ul>
                                    </th>
                                </tr>
                            </table>
                        </div>
                    </div>
                    <br /><br /><br /><br /><br />
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
                            type: 'column',
                            marginRight: 130,
                            marginBottom: 75,
                            backgroundColor: {
                                linearGradient: [0, 0, 700, 500],
                                stops: [
                                    [0, 'rgb(242, 242, 242, 0.8)'],
                                    [1, 'rgb(242, 242, 242, 0.8)']
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
                            text: 'REALISASI PRODUKSI',
                            x: -20 //center text
                        },
                        subtitle: {
                            style: {
                                color: '#000000',
                                fontWeight: 'bold',
                                fontSize: '14px',
                                fontFamily: 'helvetica'
                            },
                            text: 'PT. BIA BUMI JAYENDRA - TIGA DIHAJI (PAKET 5)',
                            x: -20 //center text
                        },
                        xAxis: { //data bulan
                            labels: {
                                style: {
                                    color: '#000000',
                                    fontWeight: 'bold',
                                    fontSize: '10px',
                                    fontFamily: 'helvetica'
                                }
                            },
                            categories: ['Februari 25','Maret 25','April 25','Mei 25','Juni 25','Juli 25','Agustus 25','September 25','Oktober 25','November 25','Desember 25',]
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
                                format: '{value} M3'
                            },
                            min: 0,
                            max: 4500,
                            tickInterval:500,
                        },
                        tooltip: {
                        //fungsi tooltip, ini opsional, kegunaan dari fungsi ini 
                        //akan menampikan data di titik tertentu di grafik saat mouseover
                            formatter: function() {
                                    return '<b>'+ this.series.name +'</b><br/>'+ 
                                    ''+ 'Volume' +': '+ this.y + ' (M3)<br/>';
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
                            name: 'Rencana',  
                            
                            data: [<?php echo json_encode($rencana_produksi_februari25, JSON_NUMERIC_CHECK); ?>,<?php echo json_encode($rencana_produksi_maret25, JSON_NUMERIC_CHECK); ?>,<?php echo json_encode($rencana_produksi_april25, JSON_NUMERIC_CHECK); ?>,<?php echo json_encode($rencana_produksi_mei25, JSON_NUMERIC_CHECK); ?>,<?php echo json_encode($rencana_produksi_juni25, JSON_NUMERIC_CHECK); ?>,<?php echo json_encode($rencana_produksi_juli25, JSON_NUMERIC_CHECK); ?>,<?php echo json_encode($rencana_produksi_agustus25, JSON_NUMERIC_CHECK); ?>,<?php echo json_encode($rencana_produksi_september25, JSON_NUMERIC_CHECK); ?>,<?php echo json_encode($rencana_produksi_oktober25, JSON_NUMERIC_CHECK); ?>,<?php echo json_encode($rencana_produksi_november25, JSON_NUMERIC_CHECK); ?>,<?php echo json_encode($rencana_produksi_desember25, JSON_NUMERIC_CHECK); ?>],

                            color: '#000000',
                            fontWeight: 'bold',
                            fontSize: '10px',
                            fontFamily: 'helvetica'
                        },
                        {  
                            name: 'Realisasi',  
                            
                            data: [<?php echo json_encode($realisasi_produksi_februari25, JSON_NUMERIC_CHECK); ?>,<?php echo json_encode($realisasi_produksi_maret25, JSON_NUMERIC_CHECK); ?>,<?php echo json_encode($realisasi_produksi_april25, JSON_NUMERIC_CHECK); ?>,<?php echo json_encode($realisasi_produksi_mei25, JSON_NUMERIC_CHECK); ?>,<?php echo json_encode($realisasi_produksi_juni25, JSON_NUMERIC_CHECK); ?>,<?php echo json_encode($realisasi_produksi_juli25, JSON_NUMERIC_CHECK); ?>,<?php echo json_encode($realisasi_produksi_agustus25, JSON_NUMERIC_CHECK); ?>,<?php echo json_encode($realisasi_produksi_september25, JSON_NUMERIC_CHECK); ?>,<?php echo json_encode($realisasi_produksi_oktober25, JSON_NUMERIC_CHECK); ?>,<?php echo json_encode($realisasi_produksi_november25, JSON_NUMERIC_CHECK); ?>,<?php echo json_encode($realisasi_produksi_desember25, JSON_NUMERIC_CHECK); ?>],

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
                            type: 'column',
                            marginRight: 130,
                            marginBottom: 75,
                            backgroundColor: {
                                //linearGradient: [500, 0, 0, 700],
                                linearGradient: [0, 0, 700, 500],
                                stops: [
                                    [0, 'rgb(242, 242, 242, 0.8)'],
                                    [1, 'rgb(242, 242, 242, 0.8)']
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
                            text: 'PT. BIA BUMI JAYENDRA - TIGA DIHAJI (PAKET 5)',
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
                            categories: ['Februari 25','Maret 25','April 25','Mei 25','Juni 25','Juli 25','Agustus 25','September 25','Oktober 25','November 25','Desember 25',]
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
                            min: -50,
                            max: 120,
                            tickInterval: 20,
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
                            name: 'Laba Rugi (RAP) %',  
                            data: [<?php echo json_encode($persentase_rak_februari25, JSON_NUMERIC_CHECK); ?>,<?php echo json_encode($persentase_rak_maret25, JSON_NUMERIC_CHECK); ?>,<?php echo json_encode($persentase_rak_april25, JSON_NUMERIC_CHECK); ?>,<?php echo json_encode($persentase_rak_mei25, JSON_NUMERIC_CHECK); ?>,<?php echo json_encode($persentase_rak_juni25, JSON_NUMERIC_CHECK); ?>,<?php echo json_encode($persentase_rak_juli25, JSON_NUMERIC_CHECK); ?>,<?php echo json_encode($persentase_rak_agustus25, JSON_NUMERIC_CHECK); ?>,<?php echo json_encode($persentase_rak_september25, JSON_NUMERIC_CHECK); ?>,<?php echo json_encode($persentase_rak_oktober25, JSON_NUMERIC_CHECK); ?>,<?php echo json_encode($persentase_rak_november25, JSON_NUMERIC_CHECK); ?>,<?php echo json_encode($persentase_rak_desember25, JSON_NUMERIC_CHECK); ?>],
                            color: '#000000',
                            fontWeight: 'bold',
                            fontSize: '10px',
                            fontFamily: 'helvetica'
                        },
                        {  
                            name: 'Laba Rugi (Realisasi)%',
                            data: [<?php echo json_encode($persentase_laba_rugi_februari25, JSON_NUMERIC_CHECK); ?>,<?php echo json_encode($persentase_laba_rugi_maret25, JSON_NUMERIC_CHECK); ?>,<?php echo json_encode($persentase_laba_rugi_april25, JSON_NUMERIC_CHECK); ?>,<?php echo json_encode($persentase_laba_rugi_mei25, JSON_NUMERIC_CHECK); ?>,<?php echo json_encode($persentase_laba_rugi_juni25, JSON_NUMERIC_CHECK); ?>,<?php echo json_encode($persentase_laba_rugi_juli25, JSON_NUMERIC_CHECK); ?>,<?php echo json_encode($persentase_laba_rugi_agustus25, JSON_NUMERIC_CHECK); ?>,<?php echo json_encode($persentase_laba_rugi_september25, JSON_NUMERIC_CHECK); ?>,<?php echo json_encode($persentase_laba_rugi_oktober25, JSON_NUMERIC_CHECK); ?>,<?php echo json_encode($persentase_laba_rugi_november25, JSON_NUMERIC_CHECK); ?>,<?php echo json_encode($persentase_laba_rugi_desember25, JSON_NUMERIC_CHECK); ?>],
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

            $('.dtpicker').daterangepicker({
                autoUpdateInput: false,
                locale: {
                    format: 'DD/MM/YYYY'
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

            var table_transactions = $('#table-transactions').DataTable( {"bAutoWidth": false,
            ajax: {
                processing: true,
                serverSide: true,
                url: '<?php echo site_url('pmm/reports/table_transactions'); ?>',
                type: 'POST',
                data: function(d) {
                    d.filter_date = $('#filter_date_transactions').val();
                    d.filter_admin_name = $('#filter_admin_name').val();
                    d.filter_jenis_transaksi = $('#filter_jenis_transaksi').val();
                }
            },
            "language": {
                processing: '<i class="fa fa-spinner fa-spin fa-3x fa-fw"></i><span class="sr-only">Loading...</span> '
            },
            columns: [{
                    "data": "no"
                },
                {
                    "data": "created_on"
                },
                {
                    "data": "admin_name"
                },
                {
                    "data": "jenis_transaksi"
                },
                {
                    "data": "tanggal_transaksi"
                },
                {
                    "data": "nomor_transaksi"
                },
                {
                    "data": "debit"
                },
                {
                    "data": "kredit"
                },
            ],
            "columnDefs": [
                { "width": "5%", "targets": [0], "className": 'text-left'},
                { "targets": [1,2,3,4,5], "className": 'text-left'},
                { "targets": [6,7], "className": 'text-right'},
            ],
            responsive: true,
            pageLength: 10,
            searching: false,
            lengthChange: false,
        });

        $('#filter_date_transactions').on('apply.daterangepicker', function(ev, picker) {
            $(this).val(picker.startDate.format('DD-MM-YYYY') + ' - ' + picker.endDate.format('DD-MM-YYYY'));
            table_transactions.ajax.reload();
        });

        $('#filter_admin_name').change(function() {
            table_transactions.ajax.reload();
        });

        $('#filter_jenis_transaksi').change(function() {
            table_transactions.ajax.reload();
        });

        </script>
    </body>
</html>
