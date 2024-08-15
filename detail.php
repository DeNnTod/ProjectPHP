<?php 
require 'function.php';
require 'cek.php';

$idbarang = $_GET['id'];

$get = mysqli_query($conn, "SELECT * FROM stock WHERE idbarang='$idbarang'");
$fetch = mysqli_fetch_array($get);

$nama = $fetch['namabarang'];
$deskripsi = $fetch['deskripsi'];
$stock = $fetch['stock'];
$image = $fetch['image'];

?>

<!DOCTYPE html>
<html lang="en">
    <head>
        <meta charset="utf-8" />
        <meta http-equiv="X-UA-Compatible" content="IE=edge" />
        <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no" />
        <meta name="description" content="" />
        <meta name="author" content="" />
        <title>Detail Barang</title>
        <link href="css/styles.css" rel="stylesheet" />
        <link href="https://cdn.datatables.net/1.10.20/css/dataTables.bootstrap4.min.css" rel="stylesheet" crossorigin="anonymous" />
        <script src="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.1/js/all.min.js" crossorigin="anonymous"></script>
        <style>
            .zoomable {
                width: 150px;
            }
            a {
                text-decoration: none;
                color: black;
            }
        </style>
    </head>
    <body class="sb-nav-fixed">
        <nav class="sb-topnav navbar navbar-expand navbar-dark bg-dark">
            <a class="navbar-brand" href="index.php">Jual Devon.</a>
            <button class="btn btn-link btn-sm order-1 order-lg-0" id="sidebarToggle" href="#"><i class="fas fa-bars"></i></button>
            <ul class="navbar-nav ml-auto ml-md-0">
            <li class="nav-item dropdown">
                <a class="nav-link dropdown-toggle" id="userDropdown" href="#" role="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false"><i class="fas fa-user fa-fw"></i></a>
                <div class="dropdown-menu dropdown-menu-right" aria-labelledby="userDropdown">
                    <a class="dropdown-item" href="#">Settings</a>
                    <a class="dropdown-item" href="#">Activity Log</a>
                    <div class="dropdown-divider"></div>
                    <a class="dropdown-item" href="logout.php">Logout</a>
                </div>
            </li>
        </ul>
        </nav>
        <div id="layoutSidenav">
            <div id="layoutSidenav_nav">
                <nav class="sb-sidenav accordion sb-sidenav-dark" id="sidenavAccordion">
                    <div class="sb-sidenav-menu">
                        <div class="nav">
                            <div class="sb-sidenav-menu-heading">Fitur</div>
                            <a class="nav-link" href="index.php">
                                <div class="sb-nav-link-icon"><i class="fas fa-tachometer-alt"></i></div>
                                Stock Barang
                            </a>
                            <a class="nav-link" href="masuk.php">
                                <div class="sb-nav-link-icon"><i class="fas fa-tachometer-alt"></i></div>
                                Barang Masuk
                            </a>
                            <a class="nav-link" href="keluar.php">
                                <div class="sb-nav-link-icon"><i class="fas fa-tachometer-alt"></i></div>
                                Barang Keluar
                            </a>
                            <a class="nav-link" href="admin.php">
                                <div class="sb-nav-link-icon"><i class="fas fa-tachometer-alt"></i></div>
                                Kelola Admin
                            </a>
                        </div>
                    </div>
                    <div class="sb-sidenav-footer">
                        <div class="small">Logged in as</div>
                        DeNn
                    </div>
                </nav>
            </div>
            <div id="layoutSidenav_content">
                <main>
                    <div class="container-fluid">
                        <h1 class="mt-4">Detail Barang</h1>
                        <div class="card mb-4">
                            <div class="card-body">
                                <div class="row">
                                    <div class="col"><h2><?=$nama?></h2></div>
                                </div>
                                <br>
                                <div class="row">
                                    <div class="col">
                                        <?php
                                        if ($image == null) {
                                            echo 'No Photo';
                                        } else {
                                            echo '<img src="images/'.$image.'" class="zoomable">';
                                        }
                                        ?>
                                    </div>
                                </div>
                                <br>
                                <div class="row">
                                    <div class="col">Deskripsi</div>    
                                    <div class="col">: <?=$deskripsi?></div>
                                </div>
                                <div class="row">
                                    <div class="col">Stock</div>
                                    <div class="col">: <?=$stock?></div>
                                </div>
                            </div>
                        </div>

                        <br>
                        <h2>Barang Masuk</h2>
                        <br>
                        <div class="card mb-4">
                            <div class="card-header">
                                <i class="fas fa-table mr-1"></i>
                                Data Tabel Barang Masuk
                            </div>
                            <div class="card-body">
                                <div class="table-responsive">
                                    <table class="table table-bordered" id="dataTable" width="100%" cellspacing="0">
                                        <thead>
                                            <tr>
                                                <th>No</th>
                                                <th>Tanggal</th>
                                                <th>Penerima</th>
                                                <th>Quantity</th>  
                                            </tr>
                                        </thead>
                                        <tbody>
                                            <?php
                                            $ambildatamasuk = mysqli_query($conn, "SELECT m.tanggal, m.penerima, m.qty FROM masuk m WHERE m.idbarang='$idbarang'");
                                            $i = 1;
                                            while ($fetch = mysqli_fetch_array($ambildatamasuk)) {
                                                $tanggal = $fetch['tanggal'];
                                                $penerima = $fetch['penerima'];
                                                $quantity = $fetch['qty'];
                                            ?>
                                            <tr>
                                                <td><?=$i++;?></td>
                                                <td><?=$tanggal;?></td>
                                                <td><?=$penerima;?></td>
                                                <td><?=$quantity;?></td>
                                            </tr>
                                            <?php
                                            }
                                            ?>
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                        </div>
                        
                        <br>
                        <h2>Barang Keluar</h2>
                        <br>
                        <div class="card mb-4">
                            <div class="card-header">
                                <i class="fas fa-table mr-1"></i>
                                Data Tabel Barang Keluar
                            </div>
                            <div class="card-body">
                                <div class="table-responsive">
                                    <table class="table table-bordered" id="dataTable" width="100%" cellspacing="0">
                                        <thead>
                                            <tr>
                                                <th>No</th>
                                                <th>Tanggal</th>
                                                <th>Pengirim</th>
                                                <th>Quantity</th>  
                                            </tr>
                                        </thead>
                                        <tbody>
                                            <?php
                                            $ambildatamasuk = mysqli_query($conn, "SELECT m.tanggal, m.pengirim, m.qty FROM keluar m WHERE m.idbarang='$idbarang'");
                                            $i = 1;
                                            while ($fetch = mysqli_fetch_array($ambildatamasuk)) {
                                                $tanggal = $fetch['tanggal'];
                                                $pengirim = $fetch['pengirim'];
                                                $quantity = $fetch['qty'];
                                            ?>
                                            <tr>
                                                <td><?=$i++;?></td>
                                                <td><?=$tanggal;?></td>
                                                <td><?=$pengirim;?></td>
                                                <td><?=$quantity;?></td>
                                            </tr>
                                            <?php
                                            }
                                            ?>
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                        </div>
                    </div>
                </main>
            </div>
        </div>
        <script src="https://code.jquery.com/jquery-3.5.1.slim.min.js" crossorigin="anonymous"></script>
        <script src="https://cdn.jsdelivr.net/npm/bootstrap@4.5.3/dist/js/bootstrap.bundle.min.js" crossorigin="anonymous"></script>
        <script src="js/scripts.js"></script>
        <script src="https://cdnjs.cloudflare.com/ajax/libs/Chart.js/2.8.0/Chart.min.js" crossorigin="anonymous"></script>
        <script src="assets/demo/chart-area-demo.js"></script>
        <script src="assets/demo/chart-bar-demo.js"></script>
        <script src="https://cdn.datatables.net/1.10.20/js/jquery.dataTables.min.js" crossorigin="anonymous"></script>
        <script src="https://cdn.datatables.net/1.10.20/js/dataTables.bootstrap4.min.js" crossorigin="anonymous"></script>
        <script src="assets/demo/datatables-demo.js"></script>
    </body>
</html>
