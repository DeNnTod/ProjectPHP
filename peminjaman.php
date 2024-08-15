<?php 
require 'function.php';
require 'cek.php';


// Ambil jumlah total data peminjaman yang ada
$get1 = mysqli_query($conn,"SELECT * FROM peminjaman");
$count1 =  mysqli_num_rows($get1);

// Ambil jumlah data yang statusnya dipinjam
$get2 = mysqli_query($conn,"SELECT * FROM peminjaman WHERE status='Dipinjam'");
$count2 =  mysqli_num_rows($get2);

// Ambil jumlah data yang statusnya dikembalikan
$get3 = mysqli_query($conn,"SELECT * FROM peminjaman WHERE status='Kembali'");
$count3 =  mysqli_num_rows($get3);

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8" />
    <meta http-equiv="X-UA-Compatible" content="IE=edge" />
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no" />
    <meta name="description" content="" />
    <meta name="author" content="" />
    <title>Peminjaman Barang</title>
    <link href="css/styles.css" rel="stylesheet" />
    <link href="https://cdn.datatables.net/1.10.20/css/dataTables.bootstrap4.min.css" rel="stylesheet" crossorigin="anonymous" />
    <script src="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.1/js/all.min.js" crossorigin="anonymous"></script>
    <style>
        .zoomable {
            width: 150px;
        }
    </style>
</head>
<body class="sb-nav-fixed">
    <nav class="sb-topnav navbar navbar-expand navbar-dark bg-dark">
        <a class="navbar-brand" href="index.html">Jual Devon</a>
        <button class="btn btn-link btn-sm order-1 order-lg-0" id="sidebarToggle" href="#"><i class="fas fa-bars"></i></button>
        <ul class="navbar-nav ml-auto ml-md-0">
            <li class="nav-item dropdown">
                <a class="nav-link dropdown-toggle" id="userDropdown" href="#" role="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false"><i class="fas fa-user fa-fw"></i></a>
                <div class="dropdown-menu dropdown-menu-right" aria-labelledby="userDropdown">
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
                        <div class="sb-sidenav-menu-heading">Stock</div>
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
                        <a class="nav-link" href="peminjaman.php">
                            <div class="sb-nav-link-icon"><i class="fas fa-tachometer-alt"></i></div>
                            Peminjaman Barang
                        </a>
                        <div class="sb-sidenav-menu-heading">Fitur Khusus Admin</div>
                        <a class="nav-link" href="admin.php">
                            <div class="sb-nav-link-icon"><i class="fas fa-tachometer-alt"></i></div>
                            Kelola Admin
                        </a>
                    </div>
                </div>
                <div class="sb-sidenav-footer">
                    <div class="small">Logged in as:</div>
                    DeNn
                </div>
            </nav>
        </div>
        <div id="layoutSidenav_content">
            <main>
                <div class="container-fluid">
                    <h1 class="mt-4">Halaman Peminjaman Barang</h1>
                    <ol class="breadcrumb mb-4">
                        <li class="breadcrumb-item active">Barang Terpinjam dan yang sudah selesai</li>
                    </ol>
                    <div class="card mb-4">
                        <div class="card-header d-flex justify-content-between align-items-center">
                            <div>
                                <i class="fas fa-table mr-1"></i>
                                Data Table Peminjaman Barang
                            </div>
                            <!-- Button untuk membuka form insert-->
                            <button type="button" class="btn btn-primary btn-sm" data-toggle="modal" data-target="#myModal">
                                Tambahkan
                            </button>
                        </div>
                        <div class="card-body">
                            <div class="row mb-3">
                                <div class="col">
                                    <form method="post" class="form-inline">
                                        <div class="form-group">
                                            <label for="tgl_mulai" class="mr-2">Tanggal Mulai :</label>
                                            <input type="date" id="tgl_mulai" name="tgl_mulai" class="form-control mr-2">
                                        </div>
                                        <div class="form-group">
                                            <label for="tgl_selesai" class="mr-2">Tanggal Selesai :</label>
                                            <input type="date" id="tgl_selesai" name="tgl_selesai" class="form-control mr-2">
                                        </div>
                                        <button type="submit" name="filter_tgl" class="btn btn-info">Filter</button>
                                    </form>
                                </div>
                            </div>
                            <div class="row mb-4">
                                <div class="col-auto">
                                    <div class="card bg-info text-white p-2">
                                        <h6 class="m-0">Total Data: <?=$count1;?></h6>
                                    </div>
                                </div>
                                <div class="col-auto">
                                    <div class="card bg-danger text-white p-2">
                                        <h6 class="m-0">Dipinjam: <?=$count2;?></h6>
                                    </div>
                                </div>
                                <div class="col-auto">
                                    <div class="card bg-success text-white p-2">
                                        <h6 class="m-0">Dikembalikan: <?=$count3;?></h6>
                                    </div>
                                </div>
                            </div>
                            <div class="table-responsive">
                                <table class="table table-bordered" id="dataTable" width="100%" cellspacing="0">
                                    <thead>
                                        <tr>
                                            <th>Tanggal</th>
                                            <th>Nama Barang</th>
                                            <th>Gambar</th>
                                            <th>Jumlah</th>
                                            <th>Kepada</th>
                                            <th>Status</th>  
                                            <th>Aksi</th>
                                        </tr>
                                    </thead>
                                    <!-- Menampilkan data yang ada pada tabel database ke dalam tabel peminjaman barang -->
                                    <tbody>
                                        <!-- filter data -->
                                    <?php
                                    if(isset($_POST['filter_tgl'])){
                                        $mulai = $_POST['tgl_mulai'];
                                        $selesai = $_POST['tgl_selesai'];

                                        if($mulai!=null || $selesai!=null) {
                                            $result = mysqli_query($conn, "SELECT * FROM peminjaman m JOIN stock s ON s.idbarang = m.idbarang 
                                            WHERE m.tanggalpinjam BETWEEN '$mulai' AND DATE_ADD('$selesai', INTERVAL 1 DAY) ORDER BY m.idpeminjaman DESC");
                                        } else {
                                            $result = mysqli_query($conn, "SELECT * FROM peminjaman m, stock s WHERE s.idbarang = m.idbarang");
                                        }
                                    } else {
                                        $result = mysqli_query($conn, "SELECT * FROM peminjaman p JOIN stock s ON s.idbarang = p.idbarang ORDER BY p.idpeminjaman DESC");
                                    }

                                    while($row = mysqli_fetch_assoc($result)) {
                                        $idpeminjaman = $row['idpeminjaman'];
                                        $idbarang = $row['idbarang'];
                                        $namabarang = $row['namabarang'];
                                        $tanggal = $row['tanggalpinjam'];
                                        $quantity = $row['qty'];
                                        $peminjam = $row['peminjam'];
                                        $status = $row['status'];
                                        $unik_id = $idbarang . '-' . $idpeminjaman;

                                        // Cek ada gambar atau tidak
                                        $image = $row['image'];
                                        if($image==null){
                                            $img = 'No Photo';
                                        } else {
                                            $img = '<img src="images/'.$image.'" class="zoomable">';
                                        }
                                    ?>
                                        <tr>
                                            <td><?=$tanggal;?></td>
                                            <td><?=$namabarang;?></td>
                                            <td><?=$img;?></td>
                                            <td><?=$quantity;?></td>
                                            <td><?=$peminjam;?></td>
                                            <td><?=$status;?></td>
                                            <td>
                                                <?php
                                                if($status=='Dipinjam') {
                                                ?>
                                                    <button type="button" class="btn btn-success btn-sm" data-toggle="modal" data-target="#kembalikan<?=$unik_id;?>">
                                                        Kembalikan
                                                    </button>
                                                <?php
                                                }
                                                ?>
                                            </td>
                                        </tr>

                                        <!-- Modal untuk memproses kembalikan -->
                                        <div class="modal fade" id="kembalikan<?=$unik_id;?>">
                                            <div class="modal-dialog">
                                                <div class="modal-content">
                                                    <!-- Header -->
                                                    <div class="modal-header">
                                                        <h4 class="modal-title">Kembalikan Barang?</h4>
                                                        <button type="button" class="close" data-dismiss="modal">&times;</button>
                                                    </div>
                                                    <!-- Body -->
                                                    <form method="post">
                                                        <div class="modal-body">
                                                            Apakah Barang <?=$namabarang;?> Sudah Dikembalikan?
                                                            <input type="hidden" name="idpeminjaman" value="<?=$idpeminjaman;?>">
                                                            <input type="hidden" name="idbarang" value="<?=$idbarang;?>">
                                                            <input type="hidden" name="qty" value="<?=$quantity;?>">
                                                            <br>
                                                            <button type="submit" class="btn btn-success" name="barangkembali">Ya</button>
                                                            <button type="button" class="btn btn-danger" data-dismiss="modal">Batal</button>
                                                        </div>
                                                    </form>
                                                </div>
                                            </div>
                                        </div>

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
            <footer class="py-4 bg-light mt-auto">
                <div class="container-fluid">
                    <div class="d-flex align-items-center justify-content-between small">
                        <div class="text-muted">Kelompok 1</div>
                    </div>
                </div>
            </footer>
        </div>
    </div>

    <!-- The Modal untuk insert data pinjaman barang -->
    <div class="modal fade" id="myModal">
        <div class="modal-dialog">
            <div class="modal-content">
                <!-- Modal Header -->
                <div class="modal-header">
                    <h4 class="modal-title">Tambah Peminjaman Barang</h4>
                    <button type="button" class="close" data-dismiss="modal">&times;</button>
                </div>
                <!-- Modal body -->
                <form method="post">
                    <div class="modal-body">
                        <select name="barangnya" class="form-control">
                            <?php
                                $ambilsemuadatanya = mysqli_query($conn,"SELECT * FROM stock");
                                while($fetcharray = mysqli_fetch_array($ambilsemuadatanya)) {
                                    $namabarangnya = $fetcharray['namabarang'];
                                    $idbarangnya = $fetcharray['idbarang'];
                            ?>
                                <option value="<?=$idbarangnya;?>"><?=$namabarangnya;?></option>
                            <?php
                                }
                            ?>
                        </select>
                        <br>
                        <input type="number" name="qty" placeholder="Quantity" class="form-control" required>
                        <br>
                        <input type="text" name="peminjam" placeholder="Peminjam" class="form-control" required>
                        <br>
                        <button type="submit" class="btn btn-primary" name="pinjam">Submit</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <script src="https://code.jquery.com/jquery-3.5.1.min.js" crossorigin="anonymous"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@4.5.3/dist/js/bootstrap.bundle.min.js" crossorigin="anonymous"></script>
    <script src="js/scripts.js"></script>
    <script src="https://cdn.datatables.net/1.10.20/js/jquery.dataTables.min.js" crossorigin="anonymous"></script>
    <script src="https://cdn.datatables.net/1.10.20/js/dataTables.bootstrap4.min.js" crossorigin="anonymous"></script>
    <script src="js/datatables-demo.js"></script>
</body>
</html>
