-- Tambahan tabel untuk konten website sekolah

USE sekolah_kita;

-- Tabel untuk Profil Sekolah
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
);

-- Tabel untuk Visi & Misi
CREATE TABLE IF NOT EXISTS visi_misi (
    id INT AUTO_INCREMENT PRIMARY KEY,
    visi TEXT NOT NULL,
    misi TEXT NOT NULL,
    tujuan TEXT,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
);

-- Tabel untuk Fasilitas
CREATE TABLE IF NOT EXISTS fasilitas (
    id INT AUTO_INCREMENT PRIMARY KEY,
    nama_fasilitas VARCHAR(100) NOT NULL,
    deskripsi TEXT,
    icon VARCHAR(50),
    gambar_url VARCHAR(255),
    urutan INT DEFAULT 0,
    status ENUM('aktif', 'nonaktif') DEFAULT 'aktif',
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
);

-- Tabel untuk Ekstrakurikuler
CREATE TABLE IF NOT EXISTS ekstrakurikuler (
    id INT AUTO_INCREMENT PRIMARY KEY,
    nama_ekskul VARCHAR(100) NOT NULL,
    deskripsi TEXT,
    pembina VARCHAR(100),
    jadwal VARCHAR(100),
    icon VARCHAR(50),
    gambar_url VARCHAR(255),
    status ENUM('aktif', 'nonaktif') DEFAULT 'aktif',
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
);

-- Tabel untuk Lokasi
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
);

-- Tabel untuk Kontak
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
);

-- Insert data awal untuk Profil Sekolah
INSERT INTO profil_sekolah (nama_sekolah, deskripsi, sejarah, akreditasi, kepala_sekolah, npsn, status) VALUES
('SEKOLAH KITA', 
'Sekolah yang berkomitmen untuk memberikan pendidikan berkualitas dan membentuk karakter siswa yang berakhlak mulia.', 
'Didirikan pada tahun 2000, Sekolah Kita telah menjadi institusi pendidikan terkemuka yang menghasilkan lulusan berprestasi.',
'A',
'Dr. Budi Santoso, M.Pd',
'12345678',
'Negeri');

-- Insert data awal untuk Visi & Misi
INSERT INTO visi_misi (visi, misi, tujuan) VALUES
('Menjadi sekolah unggulan yang menghasilkan lulusan berprestasi, berakhlak mulia, dan berwawasan global.',
'1. Menyelenggarakan pembelajaran berkualitas yang inovatif dan kreatif
2. Mengembangkan potensi siswa secara optimal
3. Membentuk karakter siswa yang religius dan berakhlak mulia
4. Membekali siswa dengan keterampilan abad 21',
'1. Meningkatkan prestasi akademik dan non-akademik siswa
2. Menciptakan lingkungan belajar yang kondusif
3. Menghasilkan lulusan yang siap bersaing di era global');

-- Insert data awal untuk Fasilitas
INSERT INTO fasilitas (nama_fasilitas, deskripsi, icon, urutan) VALUES
('Laboratorium Komputer', 'Dilengkapi dengan 40 unit komputer modern dan koneksi internet cepat', '💻', 1),
('Perpustakaan', 'Koleksi lebih dari 5000 buku dan ruang baca yang nyaman', '📚', 2),
('Laboratorium IPA', 'Fasilitas praktikum lengkap untuk Fisika, Kimia, dan Biologi', '🔬', 3),
('Lapangan Olahraga', 'Lapangan basket, voli, dan futsal yang memadai', '⚽', 4),
('Ruang Multimedia', 'Peralatan audio visual modern untuk pembelajaran interaktif', '🎥', 5),
('Kantin Sehat', 'Menyediakan makanan bergizi dan sehat untuk siswa', '🍽️', 6);

-- Insert data awal untuk Ekstrakurikuler
INSERT INTO ekstrakurikuler (nama_ekskul, deskripsi, pembina, jadwal) VALUES
('Pramuka', 'Kegiatan kepramukaan untuk membentuk karakter dan leadership', 'Budi Santoso, S.Pd', 'Jumat, 14:00-16:00'),
('Basket', 'Ekstrakurikuler olahraga basket', 'Ahmad Rizki, S.Pd', 'Selasa & Kamis, 15:00-17:00'),
('English Club', 'Meningkatkan kemampuan berbahasa Inggris', 'Siti Nurhaliza, S.Pd', 'Rabu, 14:00-15:30'),
('Robotika', 'Belajar pemrograman dan robotika', 'Dedi Kurniawan, S.Kom', 'Sabtu, 09:00-12:00'),
('Seni Tari', 'Mengembangkan bakat seni tari tradisional dan modern', 'Dewi Lestari, S.Sn', 'Kamis, 14:00-16:00'),
('Paduan Suara', 'Melatih vokal dan kerjasama tim', 'Rina Marlina, S.Pd', 'Rabu, 15:00-17:00');

-- Insert data awal untuk Lokasi
INSERT INTO lokasi (alamat, kelurahan, kecamatan, kota, provinsi, kode_pos, latitude, longitude) VALUES
('Jl. Pendidikan No. 123', 
'Depok', 
'Pancoran Mas', 
'Depok', 
'Jawa Barat', 
'16431',
-6.402484,
106.794243,
'https://maps.google.com/?q=-6.402484,106.794243');

-- Insert data awal untuk Kontak
INSERT INTO kontak (telepon, email, website, instagram, facebook) VALUES
('(021) 12345678',
'info@sekolahkita.sch.id',
'www.sekolahkita.sch.id',
'@sekolahkita',
'SekolahKitaOfficial');
