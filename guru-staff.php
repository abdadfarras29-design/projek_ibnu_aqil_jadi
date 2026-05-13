<?php
require_once 'koneksi.php';
$res_guru = mysqli_query($KONEKSI, "SELECT * FROM `nama guru & staff` ORDER BY id ASC");
$daftar_guru = [];
if ($res_guru) {
    while ($row = mysqli_fetch_assoc($res_guru)) {
        $daftar_guru[] = $row;
    }
}
?>
<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Guru & Staff - Website Sekolah</title>
    <link rel="stylesheet" href="style.css">
    <!-- Boxicons -->
    <link href='https://unpkg.com/boxicons@2.1.4/css/boxicons.min.css' rel='stylesheet'>
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
                    <a href="#" class="dropbtn active">Tentang ▾</a>
                    <ul class="dropdown-content">
                        <li><a href="profile.php">Profil Sekolah</a></li>
                        <li><a href="visi-misi.php">Visi & Misi</a></li>
                        <li><a href="guru-staff.php" class="active">Guru & Staff</a></li>
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
                <li><a href="ppdb.php" class="nav-ppdb-btn">PPDB</a></li>
                <li><a href="login.php" class="nav-login-btn">Login</a></li>
            </ul>
            <div class="hamburger" onclick="toggleMenu()">
                <span></span>
                <span></span>
                <span></span>
            </div>
        </div>
    </nav>

    <style>
        .premium-hero {
            background: linear-gradient(135deg, var(--dark-green), var(--primary-green));
            padding: 8rem 2rem 5rem;
            text-align: center;
            color: white;
            border-radius: 0 0 50px 50px;
            margin-bottom: 4rem;
            box-shadow: 0 10px 30px rgba(16, 185, 129, 0.2);
            position: relative;
            overflow: hidden;
        }

        .premium-hero::after {
            content: '';
            position: absolute;
            top: -50%;
            left: -50%;
            width: 200%;
            height: 200%;
            background: radial-gradient(circle, rgba(255, 255, 255, 0.1) 0%, transparent 60%);
            pointer-events: none;
        }

        .premium-hero h2 {
            font-size: 3rem;
            margin-bottom: 1.5rem;
            font-weight: 800;
            letter-spacing: -1px;
            position: relative;
            z-index: 1;
        }

        .premium-hero p {
            font-size: 1.25rem;
            opacity: 0.95;
            max-width: 700px;
            margin: 0 auto;
            line-height: 1.6;
            position: relative;
            z-index: 1;
            font-weight: 300;
        }

        .guru-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(380px, 1fr));
            gap: 1.5rem;
            padding: 0 2rem;
            max-width: 1250px;
            margin: 0 auto 6rem;
        }

        .guru-card {
            background: #fff;
            border: 1px solid #e5e7eb;
            display: flex;
            align-items: stretch;
            box-shadow: 0 2px 4px rgba(0, 0, 0, 0.05);
            transition: all 0.3s ease;
        }

        .guru-card:hover {
            box-shadow: 0 8px 15px rgba(0, 0, 0, 0.1);
        }

        .guru-foto {
            width: 140px;
            min-height: 180px;
            object-fit: cover;
            flex-shrink: 0;
            border-right: 1px solid #e5e7eb;
        }

        .guru-info {
            padding: 1.5rem;
            flex-grow: 1;
            display: flex;
            flex-direction: column;
            justify-content: center;
        }

        .info-row {
            display: flex;
            justify-content: space-between;
            align-items: center;
            padding: 0.6rem 0;
            border-bottom: 1px solid #f3f4f6;
        }

        .info-row:last-child {
            border-bottom: none;
        }

        .info-label {
            color: #9ca3af;
            font-size: 0.9rem;
        }

        .info-value {
            color: #6b7280;
            font-size: 0.9rem;
            text-align: right;
            font-weight: 500;
        }
    </style>

    <!-- Guru Section -->
    <section>
        <div class="premium-hero">
            <h2>Guru & Staff</h2>
            <p>Tenaga pendidik dan kependidikan profesional yang berdedikasi untuk mencetak generasi cerdas dan
                berkarakter unggul.</p>
        </div>

        <div class="guru-grid">
            <?php if (empty($daftar_guru)): ?>
                <div style="text-align:center; padding: 2rem; color: #6b7280; grid-column: 1/-1;">
                    <p>Belum ada data guru & staff.</p>
                </div>
            <?php else: ?>
                <?php foreach ($daftar_guru as $gr): ?>
                    <div class="guru-card">
                        <img src="<?php echo htmlspecialchars(!empty($gr['foto']) ? $gr['foto'] : 'https://via.placeholder.com/150x200?text=No+Img'); ?>"
                            onerror="this.src='https://via.placeholder.com/150x200?text=No+Img'"
                            alt="<?php echo htmlspecialchars($gr['nama guru']); ?>" class="guru-foto">
                        <div class="guru-info">
                            <div class="info-row">
                                <span class="info-label">Nama Lengkap</span>
                                <span class="info-value"><?php echo htmlspecialchars($gr['nama guru']); ?></span>
                            </div>

                            <div class="info-row">
                                <span class="info-label">Jenis GTK</span>
                                <span class="info-value"><?php echo htmlspecialchars($gr['mapel guru']); ?></span>
                            </div>
                        </div>
                    </div>
                <?php endforeach; ?>
            <?php endif; ?>
        </div>
    </section>

    <!-- Footer -->
    <footer>
        <p>&copy; 2026 SMP IBNU AQIL. All Rights Reserved.</p>
        <p style="margin-top: 0.5rem; font-size: 0.9rem;">Membentuk Generasi Cerdas & Berkarakter</p>
    </footer>

    <script src="script.js"></script>
</body>

</html>