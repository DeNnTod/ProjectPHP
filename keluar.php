<?php 
require 'function.php';
require 'cek.php';
?>

<!DOCTYPE html>
<html lang="en">
    <head>
        <meta charset="utf-8" />
        <meta http-equiv="X-UA-Compatible" content="IE=edge" />
        <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no" />
        <meta name="description" content="" />
        <meta name="author" content="" />
        <title>Barang Keluar</title>
        <link href="css/styles.css" rel="stylesheet" />
        <link href="https://cdn.datatables.net/1.10.20/css/dataTables.bootstrap4.min.css" rel="stylesheet" crossorigin="anonymous" />
        <script src="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.1/js/all.min.js" crossorigin="anonymous"></script>
        <style>
            .zoomable{
                width: 100px;
            }
            .tambahkan{
                margin-right: 230px;
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
                        <div class="sb-sidenav-menu-heading">Core</div>
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
                    <h1 class="mt-4">Barang Keluar</h1>
                    <ol class="breadcrumb mb-4">
                        <li class="breadcrumb-item active">Stock barang yang keluar</li>
                    </ol>

                    <!-- Button untuk membuka form insert-->
                    <div class="mb-4">
                        <button type="button" class="btn btn-primary btn-sm" data-toggle="modal" data-target="#myModal">
                            Tambah Barang Keluar
                        </button>
                    </div>

                    <!-- Filter Form -->
                    <div class="card mb-4">
                        <div class="card-header">
                            <i class="fas fa-filter mr-1"></i>
                            Filter Data
                        </div>
                        <div class="card-body">
                            <form method="post" class="form-inline">
                                <div class="form-group mr-2">
                                    <label for="tgl_mulai" class="mr-2">Tanggal Awal :</label>
                                    <input type="date" id="tgl_mulai" name="tgl_mulai" class="form-control">
                                </div>
                                <div class="form-group mr-2">
                                    <label for="tgl_selesai" class="mr-2">Tanggal Akhir :</label>
                                    <input type="date" id="tgl_selesai" name="tgl_selesai" class="form-control">
                                </div>
                                <button type="submit" name="filter_tgl" class="btn btn-info">Filter</button>
                            </form>
                        </div>
                    </div>

                    <div class="card mb-4">
                        <div class="card-header">
                            <i class="fas fa-table mr-1"></i>
                            Data Table Barang Masuk
                        </div>
                        <div class="card-body">
                            <div class="table-responsive">
                                <table class="table table-bordered" id="dataTable" width="100%" cellspacing="0">
                                    <thead>
                                        <tr>
                                            <th>No</th>
                                            <th>Gambar</th>
                                            <th>Nama Barang</th>
                                            <th>Tanggal</th>
                                            <th>Stock</th>
                                            <th>Pengirim Stock</th>  
                                            <th>Aksi</th>
                                        </tr>
                                    </thead>
                                    <!-- Menampilkan data yang ada pada tabel database ke dalam tabel barang keluar -->
                                    <tbody>
                                    <?php
                                        if(isset($_POST['filter_tgl'])){
                                            $mulai = $_POST['tgl_mulai'];
                                            $selesai = $_POST['tgl_selesai'];

                                            if($mulai!=null || $selesai!=null) {
                                                $result = mysqli_query($conn, "SELECT * FROM keluar m JOIN stock s ON s.idbarang = m.idbarang 
                                                WHERE m.tanggal BETWEEN '$mulai' AND DATE_ADD('$selesai', INTERVAL 1 DAY) ORDER BY m.idkeluar DESC");
                                            } else {
                                                $result = mysqli_query($conn, "select * from keluar m, stock s where s.idbarang = m.idbarang");
                                            }
                                        } else {
                                            $result = mysqli_query($conn, "select * from keluar m, stock s where s.idbarang = m.idbarang");
                                        }

                                        $i= 1;
                                        while($row = mysqli_fetch_assoc($result)) {
                                            $idk = $row['idkeluar'];
                                            $idbarang = $row['idbarang'];
                                            $namabarang = $row['namabarang'];
                                            $tanggal = $row['tanggal'];
                                            $quantity = $row['qty'];
                                            $pengirim = $row['pengirim'];
                                            $unik_id = $idbarang . '-' . $idk;
                                            
                                            // Cek ada gambar atau tidak
                                            $image = $row['image'];
                                            if($image==null){
                                                // jika tidak ada gambar
                                                $img = 'No Photo';
                                            } else {
                                                // jika ada gambar
                                                $img = '<img src="images/'.$image.'" class="zoomable">';
                                            }
                                        ?>

                                        <tr>
                                            <td><?=$i++;?></td>
                                            <td><?=$img;?></td>
                                            <td><?=$namabarang;?></td>
                                            <td><?=$tanggal;?></td>
                                            <td><?=$quantity;?></td>
                                            <td><?=$pengirim;?></td>
                                            <td>
                                                <button type="button" class="btn btn-warning" data-toggle="modal" data-target="#edit<?=$unik_id;?>">
                                                    Edit
                                                </button>
                                                <button type="button" class="btn btn-danger" data-toggle="modal" data-target="#delete<?=$unik_id;?>">
                                                    Delete
                                                </button>
                                            </td>
                                        </tr>

                                        <!-- Edit Modal -->
                                        <div class="modal fade" id="edit<?=$unik_id;?>">
                                            <div class="modal-dialog">
                                                <div class="modal-content">
                                                    
                                                    <div class="modal-header">
                                                        <h4 class="modal-title">Edit Barang</h4>
                                                        <button type="button" class="close" data-dismiss="modal">&times;</button>
                                                    </div>
                                                    
                                                    <form method="post">
                                                    <div class="modal-body">
                                                        <input type="number" name="qty" value="<?=$quantity;?>" class="form-control" required>
                                                        <br>
                                                        <input type="text" name="pengirim" value="<?=$pengirim;?>" class="form-control" required>
                                                        <br>
                                                        <input type="hidden" name="idbarang" value="<?=$idbarang;?>">
                                                        <input type="hidden" name="idk" value="<?=$idk;?>">
                                                        <button type="submit" class="btn btn-primary" name="updatebarangkeluar">Submit</button>
                                                    </div>
                                                    </form>
                                                    
                                                </div>
                                            </div>
                                        </div>

                                        <!-- Delete Modal -->
                                        <div class="modal fade" id="delete<?=$unik_id;?>">
                                            <div class="modal-dialog">
                                                <div class="modal-content">
                                                    
                                                    <div class="modal-header">
                                                        <h4 class="modal-title">Hapus Barang</h4>
                                                        <button type="button" class="close" data-dismiss="modal">&times;</button>
                                                    </div>
                                                    
                                                    <form method="post">
                                                    <div class="modal-body">
                                                        Apakah Anda yakin ingin menghapus <?=$namabarang;?>?
                                                        <input type="hidden" name="idbarang" value="<?=$idbarang;?>">
                                                        <input type="hidden" name="idk" value="<?=$idk;?>">
                                                        <br>
                                                        <br>
                                                        <button type="submit" class="btn btn-danger" name="hapusbarangkeluar">Hapus</button>
                                                    </div>
                                                    </form>
                                                    
                                                </div>
                                            </div>
                                        </div>

                                        <?php
                                        };
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
                        <div class="text-muted">Copyright &copy; Your Website 2021</div>
                        <div>
                            <a href="#">Privacy Policy</a>
                            &middot;
                            <a href="#">Terms &amp; Conditions</a>
                        </div>
                    </div>
                </div>
            </footer>
        </div>
    </div>

    <!-- Modal Form -->
    <div class="modal fade" id="myModal">
        <div class="modal-dialog">
            <div class="modal-content">
                
                <!-- Modal Header -->
                <div class="modal-header">
                    <h4 class="modal-title">Tambah Barang Keluar</h4>
                    <button type="button" class="close" data-dismiss="modal">&times;</button>
                </div>
                
                <!-- Modal Body -->
                <form method="post" enctype="multipart/form-data">
                    <div class="modal-body">
                        <select name="barangnya" class="form-control">
                            <?php
                                $ambilsemuadatanya = mysqli_query($conn, "select * from stock");
                                while($fetcharray = mysqli_fetch_array($ambilsemuadatanya)){
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
                        <input type="text" name="pengirim" placeholder="Pengirim" class="form-control" required>
                        <br>
                        <button type="submit" class="btn btn-primary" name="addbarangkeluar">Submit</button>
                    </div>
                </form>
                
            </div>
        </div>
    </div>

    <script src="https://code.jquery.com/jquery-3.5.1.slim.min.js" crossorigin="anonymous"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@4.5.3/dist/js/bootstrap.bundle.min.js" crossorigin="anonymous"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.1/js/all.min.js" crossorigin="anonymous"></script>
    <script src="https://cdn.datatables.net/1.10.20/js/jquery.dataTables.min.js" crossorigin="anonymous"></script>
    <script src="https://cdn.datatables.net/1.10.20/js/dataTables.bootstrap4.min.js" crossorigin="anonymous"></script>
    <script src="js/scripts.js"></script>
    <script>
        $(document).ready(function() {
            $('#dataTable').DataTable();
        });
    </script>
    </body>
</html>
