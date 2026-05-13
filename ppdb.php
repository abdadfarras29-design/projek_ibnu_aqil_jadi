<?php
require_once 'koneksi.php';

$message = "";
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['daftar'])) {
    $nama = mysqli_real_escape_string($KONEKSI, $_POST['nama']);
    $asal_sekolah = mysqli_real_escape_string($KONEKSI, $_POST['asal_sekolah']);
    $email = mysqli_real_escape_string($KONEKSI, $_POST['email']);
    $wa_nomer = mysqli_real_escape_string($KONEKSI, $_POST['wa_nomer']);

    // Menggunakan kolom sesuai database (asal sekolah, wa/nomer)
    $sql = "INSERT INTO pendaftaran (nama, `asal sekolah`, email, `wa/nomer`) VALUES ('$nama', '$asal_sekolah', '$email', '$wa_nomer')";
    
    if (mysqli_query($KONEKSI, $sql)) {
        $whatsapp_msg = "Halo Admin PPDB SMP Ibnu Aqil, saya ingin mendaftar:%0A" .
                        "Nama: $nama%0A" .
                        "Asal Sekolah: $asal_sekolah%0A" .
                        "Email: $email%0A" .
                        "No. WA: $wa_nomer";
        $wa_link = "https://wa.me/6285810183161?text=$whatsapp_msg"; // Nomor admin diperbarui
        
        $message = "success";
        $success_wa_link = $wa_link;
    } else {
        $message = "error";
    }
}
?>
<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>PPDB - SMP IBNU AQIL</title>
    <link rel="stylesheet" href="style.css">
    <link href='https://unpkg.com/boxicons@2.1.4/css/boxicons.min.css' rel='stylesheet'>
    <style>
        .ppdb-container {
            max-width: 1000px;
            margin: 100px auto 50px;
            padding: 0 1.5rem;
        }

        /* Sub-Navbar / Tabs */
        .ppdb-nav {
            display: flex;
            justify-content: center;
            gap: 1rem;
            margin-bottom: 2.5rem;
            background: #f3f4f6;
            padding: 0.5rem;
            border-radius: 50px;
            box-shadow: inset 0 2px 4px rgba(0,0,0,0.05);
        }

        .ppdb-nav-btn {
            padding: 0.8rem 2rem;
            border-radius: 50px;
            border: none;
            background: transparent;
            color: #4b5563;
            font-weight: 600;
            cursor: pointer;
            transition: all 0.3s ease;
            display: flex;
            align-items: center;
            gap: 0.5rem;
        }

        .ppdb-nav-btn.active {
            background: var(--primary-green);
            color: white;
            box-shadow: 0 4px 12px rgba(16, 185, 129, 0.2);
        }

        .ppdb-content-section {
            display: none;
            animation: fadeIn 0.4s ease;
        }

        .ppdb-content-section.active {
            display: block;
        }

        @keyframes fadeIn {
            from { opacity: 0; transform: translateY(10px); }
            to { opacity: 1; transform: translateY(0); }
        }

        /* Brochure Styling */
        .brochure-card {
            background: white;
            padding: 2.5rem;
            border-radius: 24px;
            box-shadow: 0 15px 40px rgba(0,0,0,0.08);
            text-align: center;
        }

        .brochure-img {
            width: 100%;
            max-width: 600px;
            border-radius: 12px;
            box-shadow: 0 5px 20px rgba(0,0,0,0.1);
            margin: 1.5rem 0;
        }

        /* Form Styling */
        .registration-card {
            background: white;
            padding: 2.5rem;
            border-radius: 24px;
            box-shadow: 0 15px 40px rgba(0,0,0,0.08);
            border-top: 6px solid #f59e0b;
        }

        .form-group {
            margin-bottom: 1.2rem;
        }

        .form-group label {
            display: block;
            margin-bottom: 0.5rem;
            font-weight: 500;
            color: #374151;
        }

        .form-control {
            width: 100%;
            padding: 0.8rem 1.2rem;
            border: 2px solid #e5e7eb;
            border-radius: 12px;
            font-family: inherit;
            transition: all 0.3s;
        }

        .form-control:focus {
            outline: none;
            border-color: var(--primary-green);
            box-shadow: 0 0 0 4px rgba(16, 185, 129, 0.1);
        }

        .submit-btn {
            width: 100%;
            background: #f59e0b;
            color: white;
            padding: 1rem;
            border: none;
            border-radius: 12px;
            font-size: 1.1rem;
            font-weight: 700;
            cursor: pointer;
            transition: all 0.3s;
            display: flex;
            align-items: center;
            justify-content: center;
            gap: 0.6rem;
            margin-top: 1rem;
        }

        .submit-btn:hover {
            background: #d97706;
            transform: translateY(-2px);
        }

        .download-btn {
            display: inline-flex;
            align-items: center;
            gap: 0.5rem;
            background: var(--primary-green);
            color: white;
            padding: 0.8rem 2rem;
            border-radius: 50px;
            text-decoration: none;
            font-weight: 700;
            transition: all 0.3s;
        }

        .download-btn:hover {
            background: var(--dark-green);
            transform: translateY(-2px);
        }

        /* Success Modal Styles */
        .success-modal-overlay {
            display: none;
            position: fixed;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            background: rgba(0, 0, 0, 0.6);
            backdrop-filter: blur(8px);
            z-index: 10000;
            justify-content: center;
            align-items: center;
            animation: fadeIn 0.3s ease;
        }

        .success-modal {
            background: white;
            padding: 3rem 2rem;
            border-radius: 30px;
            text-align: center;
            max-width: 450px;
            width: 90%;
            box-shadow: 0 25px 50px rgba(0, 0, 0, 0.2);
            transform: scale(0.9);
            animation: popIn 0.4s cubic-bezier(0.34, 1.56, 0.64, 1) forwards;
        }

        @keyframes popIn {
            to { transform: scale(1); opacity: 1; }
        }

        .success-icon {
            width: 80px;
            height: 80px;
            background: #10b981;
            color: white;
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 40px;
            margin: 0 auto 1.5rem;
            box-shadow: 0 10px 20px rgba(16, 185, 129, 0.3);
        }

        .success-modal h2 {
            color: #111827;
            font-size: 1.8rem;
            margin-bottom: 1rem;
        }

        .success-modal p {
            color: #4b5563;
            line-height: 1.6;
            margin-bottom: 2rem;
        }

        .modal-wa-btn {
            background: #10b981;
            color: white;
            padding: 1rem 2rem;
            border-radius: 15px;
            text-decoration: none;
            font-weight: 700;
            display: inline-flex;
            align-items: center;
            gap: 0.8rem;
            transition: all 0.3s;
            box-shadow: 0 10px 20px rgba(16, 185, 129, 0.2);
        }

        .modal-wa-btn:hover {
            background: #059669;
            transform: translateY(-3px);
            box-shadow: 0 15px 30px rgba(16, 185, 129, 0.3);
        }
    </style>
</head>

<body>
    <!-- Navigation -->
    <nav class="navbar">
        <div class="nav-container">
            <div class="logo">
                <img src="Screenshot_2026-02-22-13-16-05-58_1c337646f29875672b5a61192b9010f9.png"
                    alt="Logo SMP IBNU AQIL" class="logo-img">
                SMP IBNU AQIL
            </div>
            <ul class="nav-menu" id="navMenu">
                <li><a href="index.php">Home</a></li>
                <li class="dropdown">
                    <a href="#" class="dropbtn">Tentang ▾</a>
                    <ul class="dropdown-content">
                        <li><a href="profile.php">Profil Sekolah</a></li>
                        <li><a href="visi-misi.php">Visi & Misi</a></li>
                        <li><a href="guru-staff.php">Guru & Staff</a></li>
                    </ul>
                </li>
                <li class="dropdown">
                    <a href="#" class="dropbtn">Kegiatan ▾</a>
                    <ul class="dropdown-content">
                        <li><a href="berita.php">Berita</a></li>
                        <li><a href="fasilitas.php">Fasilitas</a></li>
                        <li><a href="ekskul.php">Ekstrakulikuler</a></li>
                        <li><a href="galeri.php">Galeri</a></li>
                    </ul>
                </li>
                <li><a href="lokasi.php">Lokasi</a></li>
                <li><a href="hubungi.php">Hubungi</a></li>
                <li><a href="ppdb.php" class="active nav-ppdb-btn">PPDB</a></li>
                <li><a href="login.php" class="nav-login-btn">Login</a></li>
            </ul>
            <div class="hamburger" onclick="toggleMenu()">
                <span></span>
                <span></span>
                <span></span>
            </div>
        </div>
    </nav>

    <div class="ppdb-container">
        <h1 class="section-title">Penerimaan Peserta Didik Baru (PPDB)</h1>
        
        <!-- PPDB Sub-Navbar -->
        <div class="ppdb-nav">
            <button class="ppdb-nav-btn active" onclick="switchPPDB('brosur')">
                <i class='bx bx-book-open'></i> Brosur PPDB
            </button>
            <button class="ppdb-nav-btn" onclick="switchPPDB('daftar')">
                <i class='bx bx-edit'></i> Pendaftaran Online
            </button>
        </div>

        <!-- Section: Brosur -->
        <div id="section-brosur" class="ppdb-content-section active">
            <div class="brochure-card">
                <h2 style="color: #1f2937;">Informasi Pendaftaran</h2>
                <p style="color: #6b7280; margin-bottom: 1.5rem;">Silakan lihat brosur di bawah ini untuk mengetahui persyaratan dan alur pendaftaran.</p>
                <img src="https://via.placeholder.com/600x848?text=Brosur+PPDB+SMP+Ibnu+Aqil" alt="Brosur PPDB" class="brochure-img">
                <div style="margin-top: 1.5rem;">
                    <a href="https://s.id/brosur_PPDB_IBNU_AQIL_BOGOR_2024_2025" target="_blank" class="download-btn">
                        <i class='bx bx-download'></i> Download Brosur Lengkap
                    </a>
                </div>
            </div>
        </div>

        <!-- Section: Pendaftaran -->
        <div id="section-daftar" class="ppdb-content-section">
            <div class="registration-card">
                <h2 style="color: #1f2937;">Formulir Pendaftaran</h2>
                <p style="color: #6b7280; margin-bottom: 2rem;">Lengkapi data di bawah ini. Admin akan segera menghubungi Anda setelah formulir dikirim.</p>
                
                <form method="POST">
                    <div class="form-group">
                        <label for="nama">Nama Lengkap Siswa</label>
                        <input type="text" id="nama" name="nama" class="form-control" placeholder="Masukkan nama lengkap siswa" required>
                    </div>
                    <div class="form-group">
                        <label for="asal_sekolah">Asal Sekolah (SD/MI)</label>
                        <input type="text" id="asal_sekolah" name="asal_sekolah" class="form-control" placeholder="Contoh: SDN 01 Bogor" required>
                    </div>
                    <div class="form-group">
                        <label for="email">Alamat Email</label>
                        <input type="email" id="email" name="email" class="form-control" placeholder="siswa@email.com" required>
                    </div>
                    <div class="form-group">
                        <label for="wa_nomer">Nomor WhatsApp Aktif</label>
                        <input type="tel" id="wa_nomer" name="wa_nomer" class="form-control" placeholder="Contoh: 08123456789" required>
                    </div>
                    
                    <button type="submit" name="daftar" class="submit-btn">
                        <i class='bx bxl-whatsapp'></i> Daftar & Kirim ke WhatsApp
                    </button>
                </form>
            </div>
        </div>
    </div>

    <!-- Success Modal -->
    <div id="successModal" class="success-modal-overlay">
        <div class="success-modal">
            <div class="success-icon">
                <i class='bx bx-check-circle'></i>
            </div>
            <h2>Pendaftaran Berhasil!</h2>
            <p>Data Anda telah berhasil disimpan di sistem kami. Silakan klik tombol di bawah untuk melanjutkan pendaftaran melalui WhatsApp Admin.</p>
            <a href="<?php echo $success_wa_link ?? '#'; ?>" target="_blank" class="modal-wa-btn" onclick="closeSuccessModal()">
                <i class='bx bxl-whatsapp'></i> Lanjut ke WhatsApp
            </a>
        </div>
    </div>

    <!-- Footer -->
    <footer style="margin-top: 5rem;">
        <p>&copy; 2026 SMP IBNU AQIL. All Rights Reserved.</p>
        <p style="margin-top: 0.5rem; font-size: 0.9rem;">Membentuk Generasi Cerdas & Berkarakter</p>
    </footer>

    <script src="script.js"></script>
    <script>
        function toggleMenu() {
            const menu = document.getElementById('navMenu');
            menu.classList.toggle('active');
        }

        function switchPPDB(target) {
            // Update buttons
            document.querySelectorAll('.ppdb-nav-btn').forEach(btn => btn.classList.remove('active'));
            const activeBtn = Array.from(document.querySelectorAll('.ppdb-nav-btn')).find(btn => btn.getAttribute('onclick').includes(target));
            if(activeBtn) activeBtn.classList.add('active');

            // Update sections
            document.querySelectorAll('.ppdb-content-section').forEach(sec => sec.classList.remove('active'));
            document.getElementById('section-' + target).classList.add('active');
        }

        function closeSuccessModal() {
            document.getElementById('successModal').style.display = 'none';
        }

        // Show modal if success
        <?php if ($message == "success"): ?>
        document.addEventListener('DOMContentLoaded', function() {
            document.getElementById('successModal').style.display = 'flex';
        });
        <?php endif; ?>
    </script>
</body>

</html>