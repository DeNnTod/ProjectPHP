<?php
session_start();
$conn = mysqli_connect("localhost", "root", "", "stockbarang");

// Menangani form submission untuk menambah stok baru
if (isset($_POST['addnewbarang'])) {
    $namabarang = $_POST['namabarang'];
    $deskripsi = $_POST['deskripsi'];
    $stock = $_POST['stock'];

    $addtotable = mysqli_query($conn, "INSERT INTO stock (namabarang, deskripsi, stock) VALUES ('$namabarang', '$deskripsi', '$stock')");
    if ($addtotable) {
        header('location:index.php');
    } else {
        echo 'gagal';
        header('location:index.php');
    }
};



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
    $penerimanya = $_POST['penerima'];
    $qty = $_POST['qty'];

    $cekstockbarang = mysqli_query($conn, "SELECT * FROM stock WHERE idbarang='$barangnya'");
    $ambildatanya = mysqli_fetch_array($cekstockbarang);

    $stocksekarang = $ambildatanya["stock"];
    $menambahkanstockskrg = $stocksekarang - $qty;

    $addtokeluar = mysqli_query($conn, "INSERT INTO keluar (idbarang, penerima, qty) 
    VALUES ('$barangnya', '$penerimanya', '$qty')");
    $updatestockmasuk = mysqli_query($conn, "UPDATE stock SET stock='$menambahkanstockskrg' WHERE idbarang='$barangnya'");

    if ($addtokeluar && $updatestockmasuk) {
        echo "<script>alert('Telah Ditambahkan');</script>";
        header('location: keluar.php');
    } else {
        echo "<script>alert('Gagal Ditambahkan');</script>";
        header('location: keluar.php');
    }
}

// Update info barang
if(isset($_POST['updatebarang'])){
    $idb = $_POST['idb'];
    $namabarang = $_POST['namabarang'];
    $deskripsi = $_POST['deskripsi'];

    $update = mysqli_query($conn, "update stock set namabarang='$namabarang', deskripsi='$deskripsi' where idbarang='$idb'");
    if($update){
        header('location:index.php');
    } else {
        echo 'Gagal';
        header('location:index.php');
    }
}

// Menghapus barang dari stock
if(isset($_POST['hapusbarang'])){
    $idb = $_POST['idb'];

    $hapus = mysqli_query($conn, "delete from stock where idbarang='$idb'");
    if($update){
        header('location:index.php');
    } else {
        echo 'Gagal';
        header('location:index.php');
    }
}

// Update info barang masuk (EDIT)
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
        $stockbaru = $stocksaatini + $selisih; // Tambahkan selisih ke stock saat ini
    } else {
        $selisih = $qtysaatini - $qtybaru;
        $stockbaru = $stocksaatini - $selisih; // Kurangi selisih dari stock saat ini
    }

    // Update stock dan data barang masuk
    $updatestock = mysqli_query($conn, "UPDATE stock SET stock='$stockbaru' WHERE idbarang='$idbarang'");
    // Pastikan kolom penerima ada di tabel masuk
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

// Menghapus info barang masuk (DELETE)
if (isset($_POST['deletebarangmasuk'])) {
    $idbarang = $_POST['idbarang'];
    $qty = $_POST['kty']; 
    $idmasuk = $_POST['idm'];

    // Ambil data stock saat ini
    $querystocksaatini = mysqli_query($conn, "SELECT * FROM stock WHERE idbarang='$idbarang'");
    if ($querystocksaatini) {
        $datastocksaatini = mysqli_fetch_array($querystocksaatini);
        $stocksaatini = $datastocksaatini['stock'];

        // Kurangi quantity yang dihapus dari stock saat ini
        $stockbaru = $stocksaatini - $qty;

        // Update stock dan hapus data dari tabel masuk
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

// update info barang keluar (EDIT)
if (isset($_POST['updatebarangkeluar'])) {
    $idkeluar = $_POST['idk'];
    $idbarang = $_POST['idbarang'];
    $pengirim = $_POST['pengirim'];
    $quantitybaru = $_POST['qty'];

    // Ambil data stock saat ini
    $querystocksaatini = mysqli_query($conn, "SELECT * FROM stock WHERE idbarang='$idbarang'");
    $datastocksaatini = mysqli_fetch_array($querystocksaatini);
    $stocksaatini = $datastocksaatini['stock'];

    // Ambil data qty saat ini dari tabel keluar
    $queryqtysaatini = mysqli_query($conn, "SELECT * FROM keluar WHERE idkeluar='$idkeluar'");
    $dataqtysaatini = mysqli_fetch_array($queryqtysaatini);
    $qtysaatini = $dataqtysaatini['qty'];

    if ($quantitybaru > $qtysaatini) {
        $selisih = $quantitybaru - $qtysaatini;
        $stockbaru = $stocksaatini - $selisih; // Kurangi selisih dari stock saat ini
    } else {
        $selisih = $qtysaatini - $quantitybaru;
        $stockbaru = $stocksaatini + $selisih; // Tambahkan selisih ke stock saat ini
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

// Menghapus info barang keluar (DELETE)
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





?>
