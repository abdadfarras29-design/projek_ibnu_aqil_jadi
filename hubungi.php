<?php
require_once 'koneksi.php';

$success_message = "";
$error_message = "";
$comment_success = "";
$comment_error = "";

// Handle Pesan (Contact Form)
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['submit_pesan'])) {
    $nama = trim($_POST['nama'] ?? '');
    $email = trim($_POST['email'] ?? '');
    $subjek = trim($_POST['subjek'] ?? '');
    $pesan = trim($_POST['pesan'] ?? '');

    if (empty($nama) || empty($email) || empty($subjek) || empty($pesan)) {
        $error_message = "Semua field harus diisi!";
    } else {
        $stmt = mysqli_prepare(
            $KONEKSI,
            "INSERT INTO pesan (username, email, judul, deskripsi) VALUES (?, ?, ?, ?)"
        );
        mysqli_stmt_bind_param($stmt, "ssss", $nama, $email, $subjek, $pesan);

        if (mysqli_stmt_execute($stmt)) {
            $success_message = "Terima kasih <strong>$nama</strong>, pesan Anda telah kami terima.";
        } else {
            $error_message = "Gagal mengirim pesan.";
        }
        mysqli_stmt_close($stmt);
    }
}

// Handle Komentar (Comment Form)
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['submit_komentar'])) {
    $nama_komentar = trim($_POST['nama_komentar'] ?? '');
    $isi_komentar = trim($_POST['isi_komentar'] ?? '');

    if (empty($nama_komentar) || empty($isi_komentar)) {
        $comment_error = "Nama dan komentar tidak boleh kosong!";
    } else {
        $stmt = mysqli_prepare(
            $KONEKSI,
            "INSERT INTO komentar (nama, komentar, status) VALUES (?, ?, 'pending')"
        );
        mysqli_stmt_bind_param($stmt, "ss", $nama_komentar, $isi_komentar);

        if (mysqli_stmt_execute($stmt)) {
            $comment_success = "Komentar Anda telah terkirim dan menunggu moderasi admin.";
        } else {
            $comment_error = "Gagal mengirim komentar.";
        }
        mysqli_stmt_close($stmt);
    }
}

// Fetch Approved Comments
$res_komentar = mysqli_query($KONEKSI, "SELECT * FROM komentar WHERE status = 'approved' ORDER BY created_ad DESC");
$daftar_komentar = [];
if ($res_komentar) {
    while ($row = mysqli_fetch_assoc($res_komentar)) {
        $daftar_komentar[] = $row;
    }
}
?>
<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Hubungi & Komentar - SMP IBNU AQIL</title>
    <link rel="stylesheet" href="style.css">
    <link href='https://unpkg.com/boxicons@2.1.4/css/boxicons.min.css' rel='stylesheet'>
    <style>
        .contact-wrapper {
            display: grid;
            grid-template-columns: 1fr 1fr;
            gap: 2rem;
            margin-top: 2rem;
        }

        @media (max-width: 768px) {
            .contact-wrapper { grid-template-columns: 1fr; }
        }

        .form-container, .comment-section {
            background: var(--white);
            padding: 2.5rem;
            border-radius: 24px;
            box-shadow: 0 10px 40px rgba(0, 0, 0, 0.08);
        }

        .comment-section {
            margin-top: 4rem;
            max-width: 1000px;
            margin-left: auto;
            margin-right: auto;
        }

        .form-group { margin-bottom: 1.2rem; }
        .form-group label {
            display: block;
            color: #374151;
            font-weight: 600;
            margin-bottom: 0.5rem;
            font-size: 0.9rem;
        }

        .form-control {
            width: 100%;
            padding: 0.9rem 1.2rem;
            border: 2px solid #e5e7eb;
            border-radius: 12px;
            font-size: 1rem;
            transition: all 0.3s;
            font-family: inherit;
        }

        .form-control:focus {
            outline: none;
            border-color: var(--primary-green);
            box-shadow: 0 0 0 4px rgba(16, 185, 129, 0.1);
        }

        .btn-submit {
            width: 100%;
            padding: 1rem;
            background: linear-gradient(135deg, var(--primary-green), var(--dark-green));
            color: white;
            border: none;
            border-radius: 12px;
            font-size: 1rem;
            font-weight: 700;
            cursor: pointer;
            transition: all 0.3s;
            display: flex;
            justify-content: center;
            align-items: center;
            gap: 0.6rem;
        }

        .btn-submit:hover {
            transform: translateY(-2px);
            box-shadow: 0 10px 25px rgba(16, 185, 129, 0.3);
        }

        .info-card {
            background: white;
            padding: 1.5rem;
            border-radius: 15px;
            box-shadow: 0 5px 15px rgba(0, 0, 0, 0.05);
            display: flex;
            align-items: flex-start;
            gap: 1rem;
        }

        .info-icon {
            font-size: 1.5rem;
            background: rgba(16, 185, 129, 0.1);
            width: 50px;
            height: 50px;
            display: flex;
            align-items: center;
            justify-content: center;
            border-radius: 12px;
            color: var(--primary-green);
        }

        /* Comment Display Styles */
        .comment-list {
            margin-top: 2rem;
            display: grid;
            gap: 1.5rem;
        }

        .comment-item {
            background: #f9fafb;
            padding: 1.5rem;
            border-radius: 16px;
            border-left: 4px solid var(--primary-green);
        }

        .comment-header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 0.8rem;
        }

        .comment-user {
            font-weight: 700;
            color: #111827;
            display: flex;
            align-items: center;
            gap: 0.5rem;
        }

        .comment-date {
            font-size: 0.8rem;
            color: #6b7280;
        }

        .comment-text {
            color: #4b5563;
            line-height: 1.6;
            font-size: 1rem;
        }

        .alert {
            padding: 1rem;
            border-radius: 12px;
            margin-bottom: 1.5rem;
            font-size: 0.9rem;
        }

        .alert-success { background: #d1fae5; color: #065f46; border: 1px solid #6ee7b7; }
        .alert-danger { background: #fee2e2; color: #991b1b; border: 1px solid #fca5a5; }
    </style>
</head>

<body>
    <!-- Navigation -->
    <nav class="navbar">
        <div class="nav-container">
            <div class="logo">
                <img src="Screenshot_2026-02-22-13-16-05-58_1c337646f29875672b5a61192b9010f9.png" alt="Logo SMP IBNU AQIL" class="logo-img">
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
                <li><a href="hubungi.php" class="active">Hubungi</a></li>
                <li><a href="ppdb.php" class="nav-ppdb-btn">PPDB</a></li>
                <li><a href="login.php" class="nav-login-btn">Login</a></li>
            </ul>
            <div class="hamburger" onclick="toggleMenu()"><span></span><span></span><span></span></div>
        </div>
    </nav>

    <!-- Contact Section -->
    <section class="section active" style="margin-top: 100px; padding-bottom: 2rem;">
        <h2 class="section-title">Hubungi Kami</h2>
        
        <div class="contact-wrapper" style="max-width: 1100px; margin-left: auto; margin-right: auto;">
            <!-- Left: Info -->
            <div class="contact-info-section">
                <div class="info-card">
                    <div class="info-icon"><i class='bx bx-envelope'></i></div>
                    <div class="info-content">
                        <h4>Email</h4>
                        <a href="mailto:info@ibnuaqil.sch.id">info@ibnuaqil.sch.id</a>
                    </div>
                </div>
                <div class="info-card">
                    <div class="info-icon"><i class='bx bxl-whatsapp'></i></div>
                    <div class="info-content">
                        <h4>WhatsApp</h4>
                        <a href="https://wa.me/6285810183161" target="_blank">+62 858-1018-3161</a>
                    </div>
                </div>
                <div class="info-card">
                    <div class="info-icon"><i class='bx bx-time-five'></i></div>
                    <div class="info-content">
                        <h4>Jam Operasional</h4>
                        <p>Senin - Jumat: 07:00 - 16:00 WIB</p>
                    </div>
                </div>
            </div>

            <!-- Right: Contact Form -->
            <div class="form-container">
                <h3 style="margin-bottom: 1.5rem;">Kirim Pesan</h3>
                <?php if ($success_message): ?><div class="alert alert-success"><?php echo $success_message; ?></div><?php endif; ?>
                <?php if ($error_message): ?><div class="alert alert-danger"><?php echo $error_message; ?></div><?php endif; ?>

                <form action="" method="POST">
                    <div class="form-group">
                        <label>Nama Lengkap</label>
                        <input type="text" name="nama" class="form-control" placeholder="Nama Anda" required>
                    </div>
                    <div class="form-group">
                        <label>Email</label>
                        <input type="email" name="email" class="form-control" placeholder="Email Anda" required>
                    </div>
                    <div class="form-group">
                        <label>Subjek</label>
                        <input type="text" name="subjek" class="form-control" placeholder="Judul Pesan" required>
                    </div>
                    <div class="form-group">
                        <label>Pesan</label>
                        <textarea name="pesan" class="form-control" placeholder="Tulis pesan Anda..." required></textarea>
                    </div>
                    <button type="submit" name="submit_pesan" class="btn-submit">
                        <i class='bx bx-send'></i> Kirim Pesan
                    </button>
                </form>
            </div>
        </div>

        <!-- Comment Section -->
        <div class="comment-section">
            <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 3rem;">
                <!-- Form Komentar -->
                <div>
                    <h3 style="margin-bottom: 1.5rem; display: flex; align-items: center; gap: 0.7rem;">
                        <i class='bx bx-message-rounded-dots' style="color: var(--primary-green); font-size: 1.8rem;"></i>
                        Tulis Komentar
                    </h3>
                    <p style="color: #6b7280; font-size: 0.9rem; margin-bottom: 1.5rem;">Berikan kesan, pesan, atau pertanyaan Anda mengenai sekolah kami.</p>
                    
                    <?php if ($comment_success): ?><div class="alert alert-success"><?php echo $comment_success; ?></div><?php endif; ?>
                    <?php if ($comment_error): ?><div class="alert alert-danger"><?php echo $comment_error; ?></div><?php endif; ?>

                    <form action="" method="POST">
                        <div class="form-group">
                            <label>Nama Anda</label>
                            <input type="text" name="nama_komentar" class="form-control" placeholder="Nama lengkap atau samaran" required>
                        </div>
                        <div class="form-group">
                            <label>Isi Komentar</label>
                            <textarea name="isi_komentar" class="form-control" placeholder="Tulis komentar Anda di sini..." style="min-height: 150px;" required></textarea>
                        </div>
                        <button type="submit" name="submit_komentar" class="btn-submit">
                            <i class='bx bx-comment-add'></i> Kirim Komentar
                        </button>
                    </form>
                </div>

                <!-- Daftar Komentar -->
                <div>
                    <h3 style="margin-bottom: 1.5rem; display: flex; align-items: center; gap: 0.7rem;">
                        <i class='bx bx-group' style="color: #6366f1; font-size: 1.8rem;"></i>
                        Apa Kata Mereka?
                    </h3>
                    <div class="comment-list">
                        <?php if (empty($daftar_komentar)): ?>
                            <div style="text-align: center; padding: 3rem 1rem; color: #9ca3af; background: #f9fafb; border-radius: 16px;">
                                <i class='bx bx-ghost' style="font-size: 3rem; margin-bottom: 1rem; opacity: 0.5;"></i>
                                <p>Belum ada komentar yang disetujui.</p>
                            </div>
                        <?php else: ?>
                            <?php foreach ($daftar_komentar as $k): ?>
                                <div class="comment-item">
                                    <div class="comment-header">
                                        <div class="comment-user">
                                            <i class='bx bxs-user-circle' style="font-size: 1.2rem; color: var(--primary-green);"></i>
                                            <?php echo htmlspecialchars($k['nama']); ?>
                                        </div>
                                        <div class="comment-date">
                                            <?php echo date('d M Y', strtotime($k['created_ad'])); ?>
                                        </div>
                                    </div>
                                    <div class="comment-text">
                                        "<?php echo nl2br(htmlspecialchars($k['komentar'])); ?>"
                                    </div>
                                </div>
                            <?php endforeach; ?>
                        <?php endif; ?>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- Footer -->
    <footer>
        <p>&copy; 2026 SMP IBNU AQIL. All Rights Reserved.</p>
        <p style="margin-top: 0.5rem; font-size: 0.9rem;">Membentuk Generasi Cerdas & Berkarakter</p>
    </footer>

    <script>
        function toggleMenu() {
            const menu = document.getElementById('navMenu');
            menu.classList.toggle('active');
        }
    </script>
</body>
</html>