<?php
session_start();
$conn = mysqli_connect("localhost", "root", "", "stockbarang");

// Tambah Barang Baru Pada Stock
if (isset($_POST['addnewbarang'])) {
    $namabarang = $_POST['namabarang'];
    $deskripsi = $_POST['deskripsi'];
    $stock = $_POST['stock'];
    
    // Gambar
    $allowed_extension = array('png','jpg');
    $nama = $_FILES['file']['name']; // ambil nama gambar
    $dot = explode('.' , $nama);
    $ekstensi = strtolower(end($dot)); // ambil ekstensinya
    $ukuran = $_FILES['file']['size']; // ambil size filenya
    $file_tmp = $_FILES['file']['tmp_name']; // ambil lokasi filenya

    // enkripsi nama file
    $image = md5(uniqid($nama,true) . time()).'.'.$ekstensi; //menggabungkan nama file yg dienskripsi dengan ekstensinya


    $cek = mysqli_query($conn,"SELECT * FROM stock where namabarang='$namabarang'");
    $hitung = mysqli_num_rows($cek);

    // Validasi doang
    if ($hitung < 1) {
        // barang sudah terdaftar
        // Proses upload gambar
        if (in_array($ekstensi, $allowed_extension) === true) {
            // validasi ukuran filenya
            if ($ukuran < 1000000) {
                move_uploaded_file($file_tmp, 'images/' . $image);
                $addtotable = mysqli_query($conn, "INSERT INTO stock (namabarang, deskripsi, stock, image) VALUES ('$namabarang', '$deskripsi', '$stock', '$image')");

                if ($addtotable) {
                    echo "<script>alert('Telah Ditambahkan');</script>";
                    header('Location: index.php');
                    exit;
                } else {
                    echo "<script>alert('Gagal Ditambahkan');</script>";
                    header('Location: index.php');
                    exit;
                }
            } else {
                // kalo filenya lebih dari 10mb
                echo "
                <script>
                    alert('Ukuran file terlalu besar');
                    window.location.href='index.php';
                </script>";
            }
        } else {
            // kalau file tidak png/ jpg
            echo "
            <script>
                alert('Dimohon untuk menginput file type png/jpg');
                window.location.href='index.php';
            </script>";
        }
    } else {
        // jika sudah ada
        echo "
        <script>
            alert('Nama barang sudah terdaftar');
            window.location.href='index.php';
        </script>";
    }
}



// menambah barang masuk
if (isset($_POST['addbarang'])) {
    $barangnya = $_POST['barangnya'];
    $penerimanya = $_POST['penerima'];
    $qty = $_POST['qty'];

    $cekstockbarang = mysqli_query($conn, "SELECT * FROM stock WHERE idbarang='$barangnya'");
    $ambildatanya = mysqli_fetch_array($cekstockbarang);

    $stocksekarang = $ambildatanya["stock"];
    $menambahkanstockskrg = $stocksekarang + $qty;

    $addtomasuk = mysqli_query($conn, "INSERT INTO masuk (idbarang, penerima, qty) 
    VALUES ('$barangnya', '$penerimanya', '$qty')");
    $updatestockmasuk = mysqli_query($conn, "UPDATE stock SET stock='$menambahkanstockskrg' WHERE idbarang='$barangnya'");

    if ($addtomasuk && $updatestockmasuk) {
        echo "<script>alert('Telah Ditambahkan');</script>";
        header('location: masuk.php');
    } else {
        echo "<script>alert('Gagal Ditambahkan');</script>";
        header('location: masuk.php');
    }
}
// menambah barang keluar
if (isset($_POST['addbarangkeluar'])) {
    $barangnya = $_POST['barangnya'];
    $pengirimnya = $_POST['pengirim'];
    $qty = $_POST['qty'];

    $cekstockbarang = mysqli_query($conn, "SELECT * FROM stock WHERE idbarang='$barangnya'");
    $ambildatanya = mysqli_fetch_array($cekstockbarang);

    $stocksekarang = $ambildatanya["stock"];

    if($stocksekarang >= $qty){
        // kalau barangnya cukup
        $mengurangistockskrg = $stocksekarang - $qty;

        $addtokeluar = mysqli_query($conn, "INSERT INTO keluar (idbarang, pengirim, qty) 
        VALUES ('$barangnya', '$pengirimnya', '$qty')");
        $updatestockkeluar = mysqli_query($conn, "UPDATE stock SET stock='$mengurangistockskrg' WHERE idbarang='$barangnya'");

        if ($addtokeluar && $updatestockkeluar) {
            echo "<script>alert('Telah Dikurangi');</script>";
            header('location: keluar.php');
        } else {
            echo "<script>alert('Gagal Dikurangi');</script>";
            header('location: keluar.php');
        }
    } else {
        // kalo stok kureng
        echo 
        "<script>
        alert('stocke kurang rek');
        window.location.href='keluar.php';
        </script>";
    }
}

// update info barang pada tabel stock barang atau edit
if (isset($_POST['updatebarang'])) {
    $idbarang = $_POST['idbarang'];
    $namabarang = $_POST['namabarang'];
    $deskripsi = $_POST['deskripsi'];
    $stock = $_POST['stock'];

    // Gambar
    $allowed_extension = array('png','jpg');
    $nama = $_FILES['file']['name']; // Mengambil nama gambar
    $dot = explode('.' , $nama);
    $ekstensi = strtolower(end($dot)); // Mengambil ekstensinya
    $ukuran = $_FILES['file']['size']; // Mengambil size filenya
    $file_tmp = $_FILES['file']['tmp_name']; // Mengambil lokasi filenya

    // pengenskripsian nama file
    $image = md5(uniqid($nama,true) . time()).'.'.$ekstensi; //menggabungkan nama file yg dienskripsi dengan ekstensinya

    if($ukuran == 0){
        // jika tidak ingin upload gambar
        $update = mysqli_query($conn, "UPDATE stock SET namabarang='$namabarang', deskripsi='$deskripsi', stock='$stock' WHERE idbarang='$idbarang'");

        if ($update) {
            echo "<script>alert('Data Berhasil Diubah');</script>";
            header('location: index.php');
        } else {
            echo "<script>alert('Data Gagal Diubah');</script>";
            header('location: index.php');
        }
    } else {
        // jika ingin upload gambar
        move_uploaded_file($file_tmp, 'images/' . $image);
        $update = mysqli_query($conn, "UPDATE stock SET image='$image', namabarang='$namabarang', deskripsi='$deskripsi', stock='$stock' WHERE idbarang='$idbarang'");

        if ($update) {
            echo "<script>alert('Data Berhasil Diubah');</script>";
            header('location: index.php');
        } else {
            echo "<script>alert('Data Gagal Diubah');</script>";
            header('location: index.php');
        }
    }
}

// delete info barang pada tabel stock barang
if (isset($_POST['deletebarang'])) {
    $idbarang = $_POST['idbarang'];

    $image = mysqli_query($conn,"SELECT * FROM stock WHERE idbarang = '$idbarang'");
    $get = mysqli_fetch_array($image);
    $img = 'images/'.$get['image'];
    unlink($img);

    $delete = mysqli_query($conn, "DELETE FROM stock WHERE idbarang = '$idbarang'");

    if ($delete) {
        echo "<script>alert('Data Berhasil Dihapus');</script>";
        header('location: index.php');
    } else {
        echo "<script>alert('Data Gagal Dihapus');</script>";
        header('location: index.php');
    }
}

// Update info barang masuk atau edit
if (isset($_POST['updatebarangmasuk'])) {
    $idmasuk = $_POST['idm'];
    $idbarang = $_POST['idbarang'];
    $penerima = $_POST['penerima'];
    $qtybaru = $_POST['qty'];

    // Ambil data stock saat ini
    $querystocksaatini = mysqli_query($conn, "SELECT * FROM stock WHERE idbarang='$idbarang'");
    $datastocksaatini = mysqli_fetch_array($querystocksaatini);
    $stocksaatini = $datastocksaatini['stock'];

    // Ambil data qty saat ini dari tabel masuk
    $queryqtysaatini = mysqli_query($conn, "SELECT * FROM masuk WHERE idmasuk='$idmasuk'");
    $dataqtysaatini = mysqli_fetch_array($queryqtysaatini);
    $qtysaatini = $dataqtysaatini['qty'];

    if ($qtybaru > $qtysaatini) {
        $selisih = $qtybaru - $qtysaatini;
        $stockbaru = $stocksaatini + $selisih; // Tambah selisih ke stock saat ini
    } else {
        $selisih = $qtysaatini - $qtybaru;
        $stockbaru = $stocksaatini - $selisih; // Kurangi selisih dari stock saat ini
    }

    // Update stock dan data barang masuk
    $updatestock = mysqli_query($conn, "UPDATE stock SET stock='$stockbaru' WHERE idbarang='$idbarang'");
    $updatebarangmasuk = mysqli_query($conn, "UPDATE masuk SET qty='$qtybaru', penerima='$penerima' WHERE idmasuk='$idmasuk'");

    if ($updatestock && $updatebarangmasuk) {
        echo "<script>alert('Data Berhasil Diubah');</script>";
        header('Location: masuk.php');
        exit;
    } else {
        echo "<script>alert('Data Gagal Diubah');</script>";
        header('Location: masuk.php');
        exit;
    }
}

// hapus info barang masuk atau delete
if (isset($_POST['deletebarangmasuk'])) {
    $idbarang = $_POST['idbarang'];
    $qty = $_POST['kty']; 
    $idmasuk = $_POST['idm'];

    // Ambil data stock saat ini
    $querystocksaatini = mysqli_query($conn, "SELECT * FROM stock WHERE idbarang='$idbarang'");
    if ($querystocksaatini) {
        $datastocksaatini = mysqli_fetch_array($querystocksaatini);
        $stocksaatini = $datastocksaatini['stock'];

        // Kurangi qty yang dihapus dari stock saat ini
        $stockbaru = $stocksaatini - $qty;

        // Update stock lalu hapus data dari tabel masuk
        $updatestock = mysqli_query($conn, "UPDATE stock SET stock='$stockbaru' WHERE idbarang='$idbarang'");
        $hapusdata = mysqli_query($conn, "DELETE FROM masuk WHERE idmasuk='$idmasuk'");

        if ($updatestock && $hapusdata) {
            header('Location: masuk.php');
        } else {
            echo "<script>alert('Gagal menghapus data');</script>";
            header('Location: masuk.php');
        }
    } else {
        echo "<script>alert('Gagal mengambil data stock');</script>";
        header('Location: masuk.php');
    }
}

// update info barang keluar
if (isset($_POST['updatebarangkeluar'])) {
    $idkeluar = $_POST['idk'];
    $idbarang = $_POST['idbarang'];
    $pengirim = $_POST['pengirim'];
    $quantitybaru = $_POST['qty'];

    // Ambil data saat ini
    $querystocksaatini = mysqli_query($conn, "SELECT * FROM stock WHERE idbarang='$idbarang'");
    $datastocksaatini = mysqli_fetch_array($querystocksaatini);
    $stocksaatini = $datastocksaatini['stock'];

    // Ambil data qty saat ini dari tabel keluar
    $queryqtysaatini = mysqli_query($conn, "SELECT * FROM keluar WHERE idkeluar='$idkeluar'");
    $dataqtysaatini = mysqli_fetch_array($queryqtysaatini);
    $qtysaatini = $dataqtysaatini['qty'];

    if ($quantitybaru > $qtysaatini) {
        $selisih = $quantitybaru - $qtysaatini;
        $stockbaru = $stocksaatini - $selisih; 
    } else {
        $selisih = $qtysaatini - $quantitybaru;
        $stockbaru = $stocksaatini + $selisih; 
    }

    // Update stock dan data barang keluar
    $updatestock = mysqli_query($conn, "UPDATE stock SET stock='$stockbaru' WHERE idbarang='$idbarang'");
    $updatebarangkeluar = mysqli_query($conn, "UPDATE keluar SET qty='$quantitybaru', pengirim='$pengirim' WHERE idkeluar='$idkeluar'");

    if ($updatestock && $updatebarangkeluar) {
        echo "<script>alert('Data Berhasil Diubah');</script>";
        header('location: keluar.php');
    } else {
        echo "<script>alert('Data Gagal Diubah');</script>";
        header('location: keluar.php');
    }
}

// hapus info barang keluar
if (isset($_POST['deletebarangkeluar'])) {
    $idbarang = $_POST['idbarang'];
    $quantity = $_POST['kty']; 
    $idkeluar = $_POST['idk'];

    // Ambil data stock saat ini
    $querystocksaatini = mysqli_query($conn, "SELECT * FROM stock WHERE idbarang='$idbarang'");
    if ($querystocksaatini) {
        $datastocksaatini = mysqli_fetch_array($querystocksaatini);
        $stocksaatini = $datastocksaatini['stock'];

        // Tambahi quantity yang dihapus ke stock saat ini
        $stockbaru = $stocksaatini + $quantity;

        // Update stock dan hapus data dari tabel keluar
        $updatestock = mysqli_query($conn, "UPDATE stock SET stock='$stockbaru' WHERE idbarang='$idbarang'");
        $hapusdatakeluar = mysqli_query($conn, "DELETE FROM keluar WHERE idkeluar='$idkeluar'");

        if ($updatestock && $hapusdatakeluar) {
            header('Location: keluar.php');
        } else {
            echo "<script>alert('Gagal menghapus data');</script>";
            header('Location: keluar.php');
        }
    } else {
        echo "<script>alert('Gagal mengambil data stock');</script>";
        header('Location: keluar.php');
    }
}

// Tambah Admin Baru
if (isset($_POST['addnewadmin'])) {
    $email= $_POST['email'];
    $password = $_POST['password'];

    $addtotable = mysqli_query($conn, "INSERT INTO login (email, password) VALUES ('$email', '$password')");

    if ($addtotable) {
        echo "<script>alert('Admin Telah Ditambahkan');</script>";
        header('location: Admin.php');
    } else {
        echo "<script>alert('Admin Gagal Ditambahkan');</script>";
        header('location: Admin.php');
    }
}


// Update info Admin
if (isset($_POST['updateadmin'])) {
    $iduser = $_POST['id'];
    $email= $_POST['email'];
    $password = $_POST['password'];

    $update = mysqli_query($conn, "UPDATE login SET email='$email', password='$password' WHERE iduser='$iduser'");

    if ($update) {
        echo "<script>alert('Data Admin Berhasil Diubah');</script>";
        header('location: Admin.php');
    } else {
        echo "<script>alert('Data Admin Gagal Diubah');</script>";
        header('location: Admin.php');
    }
}

// peminjaman barang
if(isset($_POST['pinjam'])){
    $idbarang = $_POST['barangnya'];
    $peminjam = $_POST['peminjam'];
    $qty = $_POST['qty'];

    // ambil stock sekarang
    $stok_saat_ini = mysqli_query($conn,"SELECT * FROM stock WHERE idbarang='$idbarang'");
    $stok_nya = mysqli_fetch_array($stok_saat_ini);
    $stok = $stok_nya['stock']; 

    // kurangi stocknya
    $new_stock = $stok - $qty;

    // mulai query insert
    $insertpinjam = mysqli_query($conn,"INSERT INTO peminjaman(idbarang, peminjam, qty) values
    ('$idbarang','$peminjam','$qty')");

    //  Mengurangi stock di table stock
    $kurangistok = mysqli_query($conn,"UPDATE stock SET stock='$new_stock' WHERE idbarang='$idbarang'");

    if($insertpinjam && $kurangistok){
        // jika berhasil
        echo 
        "<script>
        alert('Telah berhasil ditambahkan');
        window.location.href='peminjaman.php';
        </script>";
    } else {
        // jika gagal
        "<script>
        alert('Gagal ditambahkan');
        window.location.href='peminjaman.php';
        </script>";
    }
}

// menyelesaikan pinjaman
if(isset($_POST['barangkembali'])){
    $idpeminjaman = $_POST['idpeminjaman']; 
    $idbarang = $_POST['idbarang'];

    // Update status menjadi 'kembali'
    $updatestatus = mysqli_query($conn, "UPDATE peminjaman SET status='Kembali' WHERE idpeminjaman='$idpeminjaman'");

    // Ambil stok sekarang
    $stoksaatini = mysqli_query($conn, "SELECT * FROM stock WHERE idbarang='$idbarang'");
    $datastok = mysqli_fetch_array($stoksaatini);
    $stoksekarang = $datastok['stock'];

    // Ambil qty dari idpeminjaman sekarang
    $peminjamansaatini = mysqli_query($conn, "SELECT * FROM peminjaman WHERE idpeminjaman='$idpeminjaman'");
    $datapeminjaman = mysqli_fetch_array($peminjamansaatini);
    $qtydipinjam = $datapeminjaman['qty'];

    // Tambahkan stoknya
    $stokbaru = $stoksekarang + $qtydipinjam;

    // Kembalikan stoknya
    $kembalikanstok = mysqli_query($conn, "UPDATE stock SET stock='$stokbaru' WHERE idbarang='$idbarang'");

    if($updatestatus && $kembalikanstok){
        // Jika berhasil
        echo 
        "<script>
        alert('Telah berhasil dikembalikan');
        window.location.href='peminjaman.php';
        </script>";
    } else {
        // Jika gagal
        echo 
        "<script>
        alert('Gagal dikembalikan');
        window.location.href='peminjaman.php';
        </script>";
    }
} 



?>
