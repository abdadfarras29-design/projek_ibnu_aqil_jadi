-- ============================================================
-- SQL Database untuk Website SMP IBNU AQIL
-- Sesuaikan nama database sebelum import!
-- Untuk hosting: USE website3_usersmp;
-- Untuk lokal:   USE ibnu_aqil;
-- ============================================================

-- Tabel untuk Admin Login
CREATE TABLE IF NOT EXISTS admin (
    id INT AUTO_INCREMENT PRIMARY KEY,
    username VARCHAR(50) NOT NULL,
    password VARCHAR(255) NOT NULL,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- Tabel untuk Berita
CREATE TABLE IF NOT EXISTS berita (
    id INT(11) NOT NULL AUTO_INCREMENT,
    judul VARCHAR(200) NOT NULL,
    foto VARCHAR(200) NOT NULL DEFAULT '',
    kategori ENUM('pengumuman','prestasi','pilihan utama') NOT NULL,
    tanggal DATE NOT NULL,
    deskripsi VARCHAR(500) NOT NULL,
    PRIMARY KEY (id)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- Tabel untuk Fasilitas
-- Kolom sesuai dashboard-superadmin.php: foto, `nama fasilitas`, deskripsi
CREATE TABLE IF NOT EXISTS fasilitas (
    id INT AUTO_INCREMENT PRIMARY KEY,
    foto VARCHAR(255) DEFAULT '',
    `nama fasilitas` VARCHAR(100) NOT NULL,
    deskripsi TEXT
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- Tabel untuk Ekstrakurikuler
-- Nama tabel = esktrakulikuler (sesuai database & dashboard-superadmin.php)
CREATE TABLE IF NOT EXISTS esktrakulikuler (
    id INT AUTO_INCREMENT PRIMARY KEY,
    foto VARCHAR(255) DEFAULT '',
    nama VARCHAR(100) NOT NULL,
    deskripsi TEXT
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- Tabel untuk Galeri
CREATE TABLE IF NOT EXISTS galery (
    id INT AUTO_INCREMENT PRIMARY KEY,
    foto VARCHAR(255) DEFAULT '',
    kategori VARCHAR(100) DEFAULT '',
    judul VARCHAR(200) DEFAULT '',
    deskripsi TEXT
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- Tabel untuk Guru & Staff
CREATE TABLE IF NOT EXISTS `nama guru & staff` (
    id INT AUTO_INCREMENT PRIMARY KEY,
    foto VARCHAR(255) DEFAULT '',
    `nama guru` VARCHAR(100) NOT NULL,
    `mapel guru` VARCHAR(100) DEFAULT ''
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- Tabel untuk Statistik Sekolah
CREATE TABLE IF NOT EXISTS `jumlah siswa dll` (
    id INT AUTO_INCREMENT PRIMARY KEY,
    siswa VARCHAR(20) DEFAULT '0',
    guru VARCHAR(20) DEFAULT '0',
    prestasi VARCHAR(20) DEFAULT '0',
    `rombongan belajar` VARCHAR(20) DEFAULT '0'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- Tabel untuk Pesan dari pengunjung (hubungi.php)
-- Kolom: username, email, judul, deskripsi (sesuai hubungi.php)
CREATE TABLE IF NOT EXISTS pesan (
    id INT AUTO_INCREMENT PRIMARY KEY,
    username VARCHAR(100) NOT NULL,
    email VARCHAR(100),
    judul VARCHAR(200),
    deskripsi TEXT,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- Tabel untuk Komentar (hubungi.php)
-- Kolom: nama, komentar, status, created_ad (typo di database asli)
CREATE TABLE IF NOT EXISTS komentar (
    id INT AUTO_INCREMENT PRIMARY KEY,
    nama VARCHAR(100) NOT NULL,
    komentar TEXT,
    status ENUM('pending', 'approved', 'rejected') DEFAULT 'pending',
    created_ad TIMESTAMP DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- Tabel untuk Pendaftaran PPDB (ppdb.php)
-- Kolom: nama, `asal sekolah`, email, `wa/nomer` (sesuai ppdb.php)
CREATE TABLE IF NOT EXISTS pendaftaran (
    id INT AUTO_INCREMENT PRIMARY KEY,
    nama VARCHAR(100) NOT NULL,
    `asal sekolah` VARCHAR(200) DEFAULT '',
    email VARCHAR(100) DEFAULT '',
    `wa/nomer` VARCHAR(50) DEFAULT '',
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- Tabel untuk Profil Sekolah (kelola-konten.php)
CREATE TABLE IF NOT EXISTS profil_sekolah (
    id INT AUTO_INCREMENT PRIMARY KEY,
    nama_sekolah VARCHAR(200) NOT NULL,
    logo_url VARCHAR(255),
    deskripsi TEXT,
    sejarah TEXT,
    akreditasi VARCHAR(10),
    kepala_sekolah VARCHAR(100),
    npsn VARCHAR(20),
    status VARCHAR(50),
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- Tabel untuk Visi & Misi (kelola-konten.php)
CREATE TABLE IF NOT EXISTS visi_misi (
    id INT AUTO_INCREMENT PRIMARY KEY,
    visi TEXT NOT NULL,
    misi TEXT NOT NULL,
    tujuan TEXT,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- Tabel untuk Lokasi (kelola-konten.php)
CREATE TABLE IF NOT EXISTS lokasi (
    id INT AUTO_INCREMENT PRIMARY KEY,
    alamat TEXT NOT NULL,
    kelurahan VARCHAR(100),
    kecamatan VARCHAR(100),
    kota VARCHAR(100),
    provinsi VARCHAR(100),
    kode_pos VARCHAR(10),
    latitude DECIMAL(10, 8),
    longitude DECIMAL(11, 8),
    google_maps_url TEXT,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- Tabel untuk Kontak (kelola-konten.php)
CREATE TABLE IF NOT EXISTS kontak (
    id INT AUTO_INCREMENT PRIMARY KEY,
    telepon VARCHAR(20),
    fax VARCHAR(20),
    email VARCHAR(100),
    website VARCHAR(100),
    instagram VARCHAR(100),
    facebook VARCHAR(100),
    twitter VARCHAR(100),
    youtube VARCHAR(100),
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- ============================================================
-- INSERT DATA AWAL
-- ============================================================

-- Admin default (username: admin, password: admin123)
INSERT INTO admin (username, password) VALUES
('admin', 'admin123');

-- Data awal Berita
INSERT INTO berita (judul, foto, kategori, tanggal, deskripsi) VALUES
('SMP IBNU AQIL Meresmikan Laboratorium Komputer Generasi Terbaru',
 'https://images.unsplash.com/photo-1580582932707-520aed937b7b?q=80&w=2069&auto=format&fit=crop',
 'pilihan utama', '2026-02-22',
 'Dalam upaya meningkatkan literasi digital siswa, SMP IBNU AQIL resmi membuka fasilitas laboratorium komputer tercanggih yang dilengkapi dengan 40 unit PC terbaru.'),
('Siswa SMP IBNU AQIL Juara 1 Olimpiade Matematika Nasional',
 'https://images.unsplash.com/photo-1546410531-bb4caa6b424d?q=80&w=2071&auto=format&fit=crop',
 'prestasi', '2026-02-20',
 'Prestasi membanggakan kembali diraih oleh salah satu siswa didik kami yang berhasil menyabet medali emas dalam ajang Olimpiade Matematika tingkat Nasional.'),
('Kegiatan Field Trip: Mengenal Ekosistem Hutan Mangrove',
 'https://images.unsplash.com/photo-1523050335456-cbb6e0b20152?q=80&w=2070&auto=format&fit=crop',
 'pengumuman', '2026-02-15',
 'Siswa kelas VIII melakukan kunjungan edukatif ke kawasan konservasi mangrove untuk menanamkan kepedulian terhadap lingkungan sejak dini.');

-- Data awal Fasilitas (kolom: foto, `nama fasilitas`, deskripsi)
INSERT INTO fasilitas (foto, `nama fasilitas`, deskripsi) VALUES
('', 'Laboratorium Komputer', 'Dilengkapi dengan 40 unit komputer modern dan koneksi internet cepat'),
('', 'Perpustakaan', 'Koleksi lebih dari 5000 buku dan ruang baca yang nyaman'),
('', 'Laboratorium IPA', 'Fasilitas praktikum lengkap untuk Fisika, Kimia, dan Biologi'),
('', 'Lapangan Olahraga', 'Lapangan basket, voli, dan futsal yang memadai'),
('', 'Ruang Multimedia', 'Peralatan audio visual modern untuk pembelajaran interaktif'),
('', 'Kantin Sehat', 'Menyediakan makanan bergizi dan sehat untuk siswa');

-- Data awal Ekstrakurikuler (tabel: esktrakulikuler, kolom: foto, nama, deskripsi)
INSERT INTO esktrakulikuler (foto, nama, deskripsi) VALUES
('', 'Pramuka', 'Kegiatan kepramukaan untuk membentuk karakter dan leadership'),
('', 'Basket', 'Ekstrakurikuler olahraga basket'),
('', 'English Club', 'Meningkatkan kemampuan berbahasa Inggris'),
('', 'Robotika', 'Belajar pemrograman dan robotika'),
('', 'Seni Tari', 'Mengembangkan bakat seni tari tradisional dan modern'),
('', 'Paduan Suara', 'Melatih vokal dan kerjasama tim');

-- Data awal Statistik
INSERT INTO `jumlah siswa dll` (siswa, guru, prestasi, `rombongan belajar`) VALUES
('1250', '75', '150', '30');

-- Data awal Profil Sekolah
INSERT INTO profil_sekolah (nama_sekolah, deskripsi, sejarah, akreditasi, kepala_sekolah, npsn, status) VALUES
('SMP IBNU AQIL', 
'Sekolah yang berkomitmen untuk memberikan pendidikan berkualitas dan membentuk karakter siswa yang berakhlak mulia.', 
'Didirikan pada tahun 2000, SMP Ibnu Aqil telah menjadi institusi pendidikan terkemuka yang menghasilkan lulusan berprestasi.',
'A', 'Dr. Budi Santoso, M.Pd', '12345678', 'Swasta');

-- Data awal Visi & Misi
INSERT INTO visi_misi (visi, misi, tujuan) VALUES
('Menjadi sekolah unggulan yang menghasilkan lulusan berprestasi, berakhlak mulia, dan berwawasan global.',
'1. Menyelenggarakan pembelajaran berkualitas yang inovatif dan kreatif
2. Mengembangkan potensi siswa secara optimal
3. Membentuk karakter siswa yang religius dan berakhlak mulia
4. Membekali siswa dengan keterampilan abad 21',
'1. Meningkatkan prestasi akademik dan non-akademik siswa
2. Menciptakan lingkungan belajar yang kondusif
3. Menghasilkan lulusan yang siap bersaing di era global');

-- Data awal Lokasi
INSERT INTO lokasi (alamat, kelurahan, kecamatan, kota, provinsi, kode_pos, latitude, longitude, google_maps_url) VALUES
('Jl. Pendidikan No. 123', 'Depok', 'Pancoran Mas', 'Depok', 'Jawa Barat', '16431',
-6.402484, 106.794243, 'https://maps.google.com/?q=-6.402484,106.794243');

-- Data awal Kontak
INSERT INTO kontak (telepon, email, website, instagram, facebook) VALUES
('(021) 12345678', 'info@smpibnuaqil.sch.id', 'www.smpibnuaqil.sch.id', '@smpibnuaqil', 'SMPIbnuAqilOfficial');
