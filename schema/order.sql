CREATE TABLE orders (
    id INT PRIMARY KEY AUTO_INCREMENT,
    nama_pembeli VARCHAR(255) NOT NULL,
    tanggal_order DATE NOT NULL,
    nama_produk VARCHAR(255) NOT NULL,
    qty INT NOT NULL,
    total_harga FLOAT NOT NULL,
    alamat_pengiriman VARCHAR(255) NOT NULL
);
