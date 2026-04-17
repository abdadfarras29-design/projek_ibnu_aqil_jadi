<?php
session_start();
if (!isset($_SESSION['username'])) {
    header("Location: login.php");
    exit();
}
// Prevent browser caching
header("Cache-Control: no-store, no-cache, must-revalidate, max-age=0");
header("Cache-Control: post-check=0, pre-check=0", false);
header("Pragma: no-cache");
?>
<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Kelola Konten - Super Admin</title>
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        body {
            font-family: 'Poppins', Tahoma, Geneva, sans-serif;
            background: #f7fafc;
        }

        .navbar {
            background: linear-gradient(135deg, #10b981 0%, #059669 100%);
            color: white;
            padding: 15px 30px;
            display: flex;
            justify-content: space-between;
            align-items: center;
            box-shadow: 0 2px 10px rgba(0, 0, 0, 0.1);
        }

        .navbar-brand {
            font-size: 20px;
            font-weight: bold;
            display: flex;
            align-items: center;
            gap: 10px;
        }

        .logo-img {
            height: 40px;
            width: auto;
            object-fit: contain;
        }

        .navbar-user {
            display: flex;
            align-items: center;
            gap: 15px;
        }

        .badge {
            background: #fbbf24;
            color: #1f2937;
            padding: 6px 16px;
            border-radius: 20px;
            font-size: 13px;
            font-weight: 600;
        }

        .logout-btn {
            background: transparent;
            border: 2px solid white;
            color: white;
            padding: 8px 20px;
            border-radius: 8px;
            cursor: pointer;
            font-size: 14px;
            font-weight: 500;
            transition: all 0.3s;
        }

        .logout-btn:hover {
            background: white;
            color: #10b981;
        }

        .container {
            display: flex;
            min-height: calc(100vh - 60px);
        }

        .sidebar {
            width: 250px;
            background: white;
            box-shadow: 2px 0 10px rgba(0, 0, 0, 0.05);
        }

        .sidebar-item {
            padding: 15px 25px;
            cursor: pointer;
            border-left: 4px solid transparent;
            transition: all 0.3s;
            display: flex;
            align-items: center;
            gap: 12px;
            color: #4b5563;
        }

        .sidebar-item:hover {
            background: #f3f4f6;
        }

        .sidebar-item.active {
            background: #d1fae5;
            border-left-color: #10b981;
            color: #059669;
            font-weight: 600;
        }

        .main-content {
            flex: 1;
            padding: 30px;
        }

        .page-header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 30px;
        }

        .page-title {
            font-size: 28px;
            color: #1f2937;
            font-weight: bold;
        }

        .edit-link {
            color: #10b981;
            text-decoration: none;
            font-size: 14px;
        }

        .card-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(280px, 1fr));
            gap: 20px;
        }

        .content-card {
            background: white;
            border-radius: 12px;
            padding: 25px;
            box-shadow: 0 2px 8px rgba(0, 0, 0, 0.08);
            transition: all 0.3s;
        }

        .content-card:hover {
            transform: translateY(-5px);
            box-shadow: 0 5px 20px rgba(0, 0, 0, 0.12);
        }

        .card-icon {
            font-size: 32px;
            margin-bottom: 12px;
        }

        .card-title {
            font-size: 18px;
            font-weight: 600;
            color: #1f2937;
            margin-bottom: 8px;
        }

        .card-desc {
            font-size: 13px;
            color: #6b7280;
            margin-bottom: 20px;
        }

        .edit-btn {
            background: #10b981;
            color: white;
            border: none;
            padding: 10px 24px;
            border-radius: 8px;
            cursor: pointer;
            font-size: 14px;
            font-weight: 500;
            transition: all 0.3s;
            width: 100%;
        }

        .edit-btn:hover {
            background: #059669;
        }

        /* Modal Styles */
        .modal {
            display: none;
            position: fixed;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            background: rgba(0, 0, 0, 0.5);
            z-index: 1000;
            align-items: center;
            justify-content: center;
        }

        .modal.active {
            display: flex;
        }

        .modal-content {
            background: white;
            border-radius: 12px;
            width: 90%;
            max-width: 700px;
            max-height: 90vh;
            overflow-y: auto;
            padding: 30px;
        }

        .modal-header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 25px;
        }

        .modal-title {
            font-size: 24px;
            font-weight: bold;
            color: #1f2937;
        }

        .close-btn {
            background: none;
            border: none;
            font-size: 28px;
            cursor: pointer;
            color: #9ca3af;
        }

        .form-group {
            margin-bottom: 20px;
        }

        .form-label {
            display: block;
            margin-bottom: 8px;
            font-weight: 500;
            color: #374151;
        }

        .form-input,
        .form-textarea,
        .form-select {
            width: 100%;
            padding: 12px;
            border: 2px solid #e5e7eb;
            border-radius: 8px;
            font-size: 14px;
            transition: border-color 0.3s;
        }

        .form-input:focus,
        .form-textarea:focus,
        .form-select:focus {
            outline: none;
            border-color: #10b981;
        }

        .form-textarea {
            min-height: 100px;
            resize: vertical;
        }

        .btn-group {
            display: flex;
            gap: 10px;
            margin-top: 25px;
        }

        .btn-primary {
            flex: 1;
            background: #10b981;
            color: white;
            border: none;
            padding: 12px 24px;
            border-radius: 8px;
            cursor: pointer;
            font-size: 14px;
            font-weight: 600;
        }

        .btn-primary:hover {
            background: #059669;
        }

        .btn-secondary {
            flex: 1;
            background: #e5e7eb;
            color: #374151;
            border: none;
            padding: 12px 24px;
            border-radius: 8px;
            cursor: pointer;
            font-size: 14px;
            font-weight: 600;
        }

        .btn-secondary:hover {
            background: #d1d5db;
        }

        .alert {
            padding: 12px 16px;
            border-radius: 8px;
            margin-bottom: 20px;
            font-size: 14px;
        }

        .alert-success {
            background: #d1fae5;
            color: #065f46;
            border: 1px solid #a7f3d0;
        }

        .alert-error {
            background: #fee2e2;
            color: #991b1b;
            border: 1px solid #fecaca;
        }

        /* List items for Fasilitas & Ekskul */
        .list-item {
            background: #f9fafb;
            border: 1px solid #e5e7eb;
            border-radius: 8px;
            padding: 15px;
            margin-bottom: 10px;
            display: flex;
            justify-content: space-between;
            align-items: center;
        }

        .list-item-content {
            flex: 1;
        }

        .list-item-title {
            font-weight: 600;
            color: #1f2937;
            margin-bottom: 4px;
        }

        .list-item-desc {
            font-size: 13px;
            color: #6b7280;
        }

        .list-item-actions {
            display: flex;
            gap: 8px;
        }

        .btn-small {
            padding: 6px 12px;
            border-radius: 6px;
            border: none;
            cursor: pointer;
            font-size: 12px;
            font-weight: 500;
        }

        .btn-edit {
            background: #3b82f6;
            color: white;
        }

        .btn-delete {
            background: #ef4444;
            color: white;
        }

        .btn-add {
            background: #10b981;
            color: white;
            border: none;
            padding: 10px 20px;
            border-radius: 8px;
            cursor: pointer;
            font-size: 14px;
            font-weight: 500;
            margin-bottom: 15px;
        }
    </style>
</head>

<body>
    <nav class="navbar">
        <div class="navbar-brand">
            <img src="Screenshot_2026-02-22-13-16-05-58_1c337646f29875672b5a61192b9010f9.png" alt="Logo"
                class="logo-img">
            SUPER ADMIN PANEL
        </div>
        <div class="navbar-user">
            <span>Super Administrator</span>
            <span class="badge" id="adminName">Loading...</span>
            <button class="logout-btn" onclick="window.location.href='menanyakan logout.php'">Logout</button>
        </div>
    </nav>

    <div class="container">
        <aside class="sidebar">
            <div class="sidebar-item">
                <span></span> Overview
            </div>
            <div class="sidebar-item">
                <span></span> Kelola User
            </div>
            <div class="sidebar-item active">
                <span></span> Kelola Konten
            </div>
            <div class="sidebar-item">
                <span></span> Pengaturan
            </div>
            <div class="sidebar-item">
                <span></span> Activity Logs
            </div>
            <div class="sidebar-item">
                <span></span> Backup & Restore
            </div>
            <div class="sidebar-item">
                <span></span> Lihat Website
            </div>
        </aside>

        <main class="main-content">
            <div class="page-header">
                <h1 class="page-title">Kelola Konten</h1>
                <a href="#" class="edit-link">Edit konten website sekolah</a>
            </div>

            <div class="card-grid">
                <div class="content-card">
                    <div class="card-icon"></div>
                    <div class="card-title">Profil Sekolah</div>
                    <div class="card-desc">Edit informasi profil sekolah</div>
                    <button class="edit-btn" onclick="openProfilModal()">Edit</button>
                </div>

                <div class="content-card">
                    <div class="card-icon"></div>
                    <div class="card-title">Visi & Misi</div>
                    <div class="card-desc">Edit visi dan misi sekolah</div>
                    <button class="edit-btn" onclick="openVisiMisiModal()">Edit</button>
                </div>

                <div class="content-card">
                    <div class="card-icon"></div>
                    <div class="card-title">Fasilitas</div>
                    <div class="card-desc">Kelola fasilitas sekolah</div>
                    <button class="edit-btn" onclick="openFasilitasModal()">Edit</button>
                </div>

                <div class="content-card">
                    <div class="card-icon"></div>
                    <div class="card-title">Ekstrakurikuler</div>
                    <div class="card-desc">Kelola ekstrakurikuler</div>
                    <button class="edit-btn" onclick="openEkskulModal()">Edit</button>
                </div>

                <div class="content-card">
                    <div class="card-icon"></div>
                    <div class="card-title">Lokasi</div>
                    <div class="card-desc">Edit alamat dan peta</div>
                    <button class="edit-btn" onclick="openLokasiModal()">Edit</button>
                </div>

                <div class="content-card">
                    <div class="card-icon"></div>
                    <div class="card-title">Kontak</div>
                    <div class="card-desc">Edit informasi kontak</div>
                    <button class="edit-btn" onclick="openKontakModal()">Edit</button>
                </div>
            </div>
        </main>
    </div>

    <!-- Modal Profil Sekolah -->
    <div id="modalProfil" class="modal">
        <div class="modal-content">
            <div class="modal-header">
                <h2 class="modal-title">Edit Profil Sekolah</h2>
                <button class="close-btn" onclick="closeModal('modalProfil')">&times;</button>
            </div>
            <div id="alertProfil"></div>
            <form id="formProfil">
                <div class="form-group">
                    <label class="form-label">Nama Sekolah</label>
                    <input type="text" class="form-input" name="nama_sekolah" required>
                </div>
                <div class="form-group">
                    <label class="form-label">Tentang Kami</label>
                    <textarea class="form-textarea" name="deskripsi" required style="min-height: 120px;"></textarea>
                </div>
                <div class="form-group">
                    <label class="form-label">Sejarah</label>
                    <textarea class="form-textarea" name="sejarah" required></textarea>
                </div>
                <div class="form-group">
                    <label class="form-label">Tahun Berdiri</label>
                    <input type="text" class="form-input" name="tahun_berdiri" placeholder="Contoh: 1992" required>
                </div>
                <div class="form-group">
                    <label class="form-label">Akreditasi</label>
                    <select class="form-select" name="akreditasi" required>
                        <option value="A">A</option>
                        <option value="B">B</option>
                        <option value="C">C</option>
                    </select>
                </div>
                <div class="form-group">
                    <label class="form-label">Kepala Sekolah</label>
                    <input type="text" class="form-input" name="kepala_sekolah" required>
                </div>
                <div class="form-group">
                    <label class="form-label">NPSN</label>
                    <input type="text" class="form-input" name="npsn" required>
                </div>
                <div class="form-group">
                    <label class="form-label">Status</label>
                    <select class="form-select" name="status" required>
                        <option value="Negeri">Negeri</option>
                        <option value="Swasta">Swasta</option>
                    </select>
                </div>
                <div class="btn-group">
                    <button type="button" class="btn-secondary" onclick="closeModal('modalProfil')">Batal</button>
                    <button type="submit" class="btn-primary">Simpan</button>
                </div>
            </form>
        </div>
    </div>

    <!-- Modal Visi Misi -->
    <div id="modalVisiMisi" class="modal">
        <div class="modal-content">
            <div class="modal-header">
                <h2 class="modal-title">Edit Visi & Misi</h2>
                <button class="close-btn" onclick="closeModal('modalVisiMisi')">&times;</button>
            </div>
            <div id="alertVisiMisi"></div>
            <form id="formVisiMisi">
                <div class="form-group">
                    <label class="form-label">Visi</label>
                    <textarea class="form-textarea" name="visi" required></textarea>
                </div>
                <div class="form-group">
                    <label class="form-label">Misi</label>
                    <textarea class="form-textarea" name="misi" required style="min-height: 150px;"></textarea>
                </div>
                <div class="form-group">
                    <label class="form-label">Tujuan</label>
                    <textarea class="form-textarea" name="tujuan" required style="min-height: 150px;"></textarea>
                </div>
                <div class="btn-group">
                    <button type="button" class="btn-secondary" onclick="closeModal('modalVisiMisi')">Batal</button>
                    <button type="submit" class="btn-primary">Simpan</button>
                </div>
            </form>
        </div>
    </div>

    <!-- Modal Fasilitas -->
    <div id="modalFasilitas" class="modal">
        <div class="modal-content">
            <div class="modal-header">
                <h2 class="modal-title">Kelola Fasilitas</h2>
                <button class="close-btn" onclick="closeModal('modalFasilitas')">&times;</button>
            </div>
            <div id="alertFasilitas"></div>
            <button class="btn-add" onclick="showAddFasilitas()">+ Tambah Fasilitas</button>
            <div id="listFasilitas"></div>
        </div>
    </div>

    <!-- Modal Add/Edit Fasilitas -->
    <div id="modalFormFasilitas" class="modal">
        <div class="modal-content">
            <div class="modal-header">
                <h2 class="modal-title" id="titleFormFasilitas">Tambah Fasilitas</h2>
                <button class="close-btn" onclick="closeModal('modalFormFasilitas')">&times;</button>
            </div>
            <form id="formFasilitas">
                <input type="hidden" name="id">
                <div class="form-group">
                    <label class="form-label">Nama Fasilitas</label>
                    <input type="text" class="form-input" name="nama_fasilitas" required>
                </div>
                <div class="form-group">
                    <label class="form-label">Deskripsi</label>
                    <textarea class="form-textarea" name="deskripsi" required></textarea>
                </div>
                <div class="form-group">
                    <label class="form-label">Urutan</label>
                    <input type="number" class="form-input" name="urutan" value="0">
                </div>
                <div class="form-group">
                    <label class="form-label">Status</label>
                    <select class="form-select" name="status">
                        <option value="aktif">Aktif</option>
                        <option value="nonaktif">Non-aktif</option>
                    </select>
                </div>
                <div class="btn-group">
                    <button type="button" class="btn-secondary"
                        onclick="closeModal('modalFormFasilitas')">Batal</button>
                    <button type="submit" class="btn-primary">Simpan</button>
                </div>
            </form>
        </div>
    </div>

    <!-- Modal Ekstrakurikuler -->
    <div id="modalEkskul" class="modal">
        <div class="modal-content">
            <div class="modal-header">
                <h2 class="modal-title">Kelola Ekstrakurikuler</h2>
                <button class="close-btn" onclick="closeModal('modalEkskul')">&times;</button>
            </div>
            <div id="alertEkskul"></div>
            <button class="btn-add" onclick="showAddEkskul()">+ Tambah Ekstrakurikuler</button>
            <div id="listEkskul"></div>
        </div>
    </div>

    <!-- Modal Add/Edit Ekskul -->
    <div id="modalFormEkskul" class="modal">
        <div class="modal-content">
            <div class="modal-header">
                <h2 class="modal-title" id="titleFormEkskul">Tambah Ekstrakurikuler</h2>
                <button class="close-btn" onclick="closeModal('modalFormEkskul')">&times;</button>
            </div>
            <form id="formEkskul">
                <input type="hidden" name="id">
                <div class="form-group">
                    <label class="form-label">Nama Ekstrakurikuler</label>
                    <input type="text" class="form-input" name="nama_ekskul" required>
                </div>
                <div class="form-group">
                    <label class="form-label">Deskripsi</label>
                    <textarea class="form-textarea" name="deskripsi" required></textarea>
                </div>
                <div class="form-group">
                    <label class="form-label">Pembina</label>
                    <input type="text" class="form-input" name="pembina">
                </div>
                <div class="form-group">
                    <label class="form-label">Jadwal</label>
                    <input type="text" class="form-input" name="jadwal" placeholder="contoh: Senin, 14:00-16:00">
                </div>
                <div class="form-group">
                    <label class="form-label">Status</label>
                    <select class="form-select" name="status">
                        <option value="aktif">Aktif</option>
                        <option value="nonaktif">Non-aktif</option>
                    </select>
                </div>
                <div class="btn-group">
                    <button type="button" class="btn-secondary" onclick="closeModal('modalFormEkskul')">Batal</button>
                    <button type="submit" class="btn-primary">Simpan</button>
                </div>
            </form>
        </div>
    </div>

    <!-- Modal Lokasi -->
    <div id="modalLokasi" class="modal">
        <div class="modal-content">
            <div class="modal-header">
                <h2 class="modal-title">Edit Lokasi</h2>
                <button class="close-btn" onclick="closeModal('modalLokasi')">&times;</button>
            </div>
            <div id="alertLokasi"></div>
            <form id="formLokasi">
                <div class="form-group">
                    <label class="form-label">Alamat Lengkap</label>
                    <textarea class="form-textarea" name="alamat" required></textarea>
                </div>
                <div class="form-group">
                    <label class="form-label">Kelurahan</label>
                    <input type="text" class="form-input" name="kelurahan">
                </div>
                <div class="form-group">
                    <label class="form-label">Kecamatan</label>
                    <input type="text" class="form-input" name="kecamatan">
                </div>
                <div class="form-group">
                    <label class="form-label">Kota</label>
                    <input type="text" class="form-input" name="kota">
                </div>
                <div class="form-group">
                    <label class="form-label">Provinsi</label>
                    <input type="text" class="form-input" name="provinsi">
                </div>
                <div class="form-group">
                    <label class="form-label">Kode Pos</label>
                    <input type="text" class="form-input" name="kode_pos">
                </div>
                <div class="form-group">
                    <label class="form-label">Latitude</label>
                    <input type="text" class="form-input" name="latitude" placeholder="-6.402484">
                </div>
                <div class="form-group">
                    <label class="form-label">Longitude</label>
                    <input type="text" class="form-input" name="longitude" placeholder="106.794243">
                </div>
                <div class="form-group">
                    <label class="form-label">Google Maps URL</label>
                    <input type="text" class="form-input" name="google_maps_url">
                </div>
                <div class="btn-group">
                    <button type="button" class="btn-secondary" onclick="closeModal('modalLokasi')">Batal</button>
                    <button type="submit" class="btn-primary">Simpan</button>
                </div>
            </form>
        </div>
    </div>

    <!-- Modal Kontak -->
    <div id="modalKontak" class="modal">
        <div class="modal-content">
            <div class="modal-header">
                <h2 class="modal-title">Edit Kontak</h2>
                <button class="close-btn" onclick="closeModal('modalKontak')">&times;</button>
            </div>
            <div id="alertKontak"></div>
            <form id="formKontak">
                <div class="form-group">
                    <label class="form-label">Telepon</label>
                    <input type="text" class="form-input" name="telepon">
                </div>
                <div class="form-group">
                    <label class="form-label">Fax</label>
                    <input type="text" class="form-input" name="fax">
                </div>
                <div class="form-group">
                    <label class="form-label">Email</label>
                    <input type="email" class="form-input" name="email">
                </div>
                <div class="form-group">
                    <label class="form-label">Website</label>
                    <input type="text" class="form-input" name="website">
                </div>
                <div class="form-group">
                    <label class="form-label">Instagram</label>
                    <input type="text" class="form-input" name="instagram" placeholder="@username">
                </div>
                <div class="form-group">
                    <label class="form-label">Facebook</label>
                    <input type="text" class="form-input" name="facebook">
                </div>
                <div class="form-group">
                    <label class="form-label">Twitter</label>
                    <input type="text" class="form-input" name="twitter" placeholder="@username">
                </div>
                <div class="form-group">
                    <label class="form-label">YouTube</label>
                    <input type="text" class="form-input" name="youtube">
                </div>
                <div class="btn-group">
                    <button type="button" class="btn-secondary" onclick="closeModal('modalKontak')">Batal</button>
                    <button type="submit" class="btn-primary">Simpan</button>
                </div>
            </form>
        </div>
    </div>

    <script src="kelola-konten.js"></script>
</body>

</html>