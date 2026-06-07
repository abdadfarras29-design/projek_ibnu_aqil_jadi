<?php
session_start();
header("Cache-Control: no-store, no-cache, must-revalidate, max-age=0");
header("Pragma: no-cache");
require_once 'koneksi.php';

// Fetch all OSIS/MPK members
$res = mysqli_query($KONEKSI, "SELECT * FROM intrakulikuler ORDER BY id ASC");
$osis_members = [];
$mpk_members = [];

if ($res) {
    while ($row = mysqli_fetch_assoc($res)) {
        // Parse role_jabatan to check organization
        $org = 'OSIS';
        $jabatan_raw = $row['role_jabatan'] ?? '';
        $jabatan = $jabatan_raw;
        if (strpos($jabatan_raw, ' - ') !== false) {
            $parts = explode(' - ', $jabatan_raw, 2);
            $org = trim($parts[0]);
            $jabatan = trim($parts[1]);
        } else if (stripos($jabatan_raw, 'MPK') === 0) {
            $org = 'MPK';
            $jabatan = trim(substr($jabatan_raw, 3));
        } else if (stripos($jabatan_raw, 'OSIS') === 0) {
            $org = 'OSIS';
            $jabatan = trim(substr($jabatan_raw, 4));
        }
        
        $row['parsed_org'] = $org;
        $row['parsed_jabatan'] = $jabatan;
        
        if (strtoupper($org) === 'MPK') {
            $mpk_members[] = $row;
        } else {
            $osis_members[] = $row;
        }
    }
}
?>
<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>OSIS & MPK - Website Sekolah</title>
    <link rel="stylesheet" href="style.css">
    <!-- Boxicons -->
    <link href='https://unpkg.com/boxicons@2.1.4/css/boxicons.min.css' rel='stylesheet'>
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

        /* Tab Navigation */
        .tab-nav {
            display: flex;
            gap: 12px;
            justify-content: center;
            margin-bottom: 2rem;
            flex-wrap: wrap;
        }

        .tab-btn {
            border: none;
            outline: none;
            padding: 0.75rem 1.5rem;
            border-radius: 12px;
            font-family: 'Poppins', sans-serif;
            font-size: 1rem;
            font-weight: 600;
            color: var(--text-gray);
            background: #f3f4f6;
            cursor: pointer;
            transition: all 0.3s ease;
            text-decoration: none;
        }

        .tab-btn.active {
            background: var(--primary-green);
            color: white;
        }

        .tab-btn:hover {
            background: var(--primary-green);
            color: white;
        }

        /* Member Grid */
        .member-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(380px, 1fr));
            gap: 1.5rem;
            padding: 0 2rem;
            max-width: 1250px;
            margin: 0 auto 6rem;
        }

        .member-card {
            background: #fff;
            border: 1px solid #e5e7eb;
            display: flex;
            align-items: stretch;
            box-shadow: 0 2px 4px rgba(0, 0, 0, 0.05);
            transition: all 0.3s ease;
        }

        .member-card:hover {
            box-shadow: 0 8px 15px rgba(0, 0, 0, 0.1);
        }

        .member-foto {
            width: 140px;
            min-height: 180px;
            object-fit: cover;
            flex-shrink: 0;
            border-right: 1px solid #e5e7eb;
        }

        .member-info {
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

        .empty-state {
            grid-column: 1 / -1;
            text-align: center;
            padding: 3rem 2rem;
            color: #6b7280;
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
                    <a href="#" class="dropbtn active">Tentang ▾</a>
                    <ul class="dropdown-content">
                        <li><a href="profile.php">Profil Sekolah</a></li>
                        <li><a href="visi-misi.php">Visi & Misi</a></li>
                        <li><a href="guru-staff.php">Guru & Staff</a></li>
                        <li><a href="osis-mpk.php">OSIS & MPK</a></li>
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
                <li><a href="hubungi.php">Kontak</a></li>
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

    <!-- Hero Section -->
    <div class="premium-hero">
        <h2>Organisasi Siswa Intra Sekolah (OSIS) & Majelis Perwakilan Kelas (MPK)</h2>
        <p>Wadah pengembangan kepemimpinan, kepribadian, dan kreativitas siswa SMP IBNU AQIL untuk berkolaborasi dan berkarya nyata.</p>
    </div>

    <!-- Sections: OSIS & MPK (separate sections on same page) -->
    <div style="max-width:1200px;margin:0 auto;padding:0 1.5rem;">
        <nav class="tab-nav">
            <button class="tab-btn active" onclick="switchSection('osis')">Lihat OSIS</button>
            <button class="tab-btn" onclick="switchSection('mpk')">Lihat MPK</button>
        </nav>

        <!-- OSIS Section -->
        <section id="osis-section" class="section-content active">
            <div class="member-grid">
                <?php if (empty($osis_members)): ?>
                    <div class="empty-state">
                        <p>Belum ada data pengurus OSIS saat ini.</p>
                    </div>
                <?php else: ?>
                    <?php foreach ($osis_members as $m): ?>
                        <div class="member-card">
                            <img src="<?php echo htmlspecialchars(!empty($m['foto']) ? $m['foto'] : 'https://via.placeholder.com/140x180?text=No+Img'); ?>"
                                onerror="this.src='https://via.placeholder.com/140x180?text=No+Img'"
                                alt="<?php echo htmlspecialchars($m['nama']); ?>" class="member-foto">
                            <div class="member-info">
                                <div class="info-row">
                                    <span class="info-label">Nama Lengkap</span>
                                    <span class="info-value"><?php echo htmlspecialchars($m['nama']); ?></span>
                                </div>
                                <div class="info-row">
                                    <span class="info-label">Jabatan</span>
                                    <span class="info-value"><?php echo htmlspecialchars($m['parsed_jabatan']); ?></span>
                                </div>
                            </div>
                        </div>
                    <?php endforeach; ?>
                <?php endif; ?>
            </div>
        </section>

        <!-- MPK Section -->
        <section id="mpk-section" class="section-content">
            <div class="member-grid">
                <?php if (empty($mpk_members)): ?>
                    <div class="empty-state">
                        <p>Belum ada data pengurus MPK saat ini.</p>
                    </div>
                <?php else: ?>
                    <?php foreach ($mpk_members as $m): ?>
                        <div class="member-card">
                            <img src="<?php echo htmlspecialchars(!empty($m['foto']) ? $m['foto'] : 'https://via.placeholder.com/140x180?text=No+Img'); ?>"
                                onerror="this.src='https://via.placeholder.com/140x180?text=No+Img'"
                                alt="<?php echo htmlspecialchars($m['nama']); ?>" class="member-foto">
                            <div class="member-info">
                                <div class="info-row">
                                    <span class="info-label">Nama Lengkap</span>
                                    <span class="info-value"><?php echo htmlspecialchars($m['nama']); ?></span>
                                </div>
                                <div class="info-row">
                                    <span class="info-label">Jabatan</span>
                                    <span class="info-value"><?php echo htmlspecialchars($m['parsed_jabatan']); ?></span>
                                </div>
                            </div>
                        </div>
                    <?php endforeach; ?>
                <?php endif; ?>
            </div>
        </section>
    </div>

    <!-- Footer -->
    <footer>
        <p>&copy; 2026 SMP IBNU AQIL. All Rights Reserved.</p>
        <p style="margin-top: 0.5rem; font-size: 0.9rem;">Membentuk Generasi Cerdas & Berkarakter</p>
    </footer>

    <script src="script.js"></script>
    <script>
        function switchSection(sectionId) {
            // Hide all sections
            document.querySelectorAll('.section-content').forEach(section => {
                section.classList.remove('active');
            });
            
            // Remove active class from all buttons
            document.querySelectorAll('.tab-btn').forEach(btn => {
                btn.classList.remove('active');
            });
            
            // Show selected section
            document.getElementById(sectionId + '-section').classList.add('active');
            
            // Add active class to clicked button
            event.target.classList.add('active');
        }
    </script>
    <style>
        .section-content {
            display: none;
            animation: fadeIn 0.5s ease;
        }

        .section-content.active {
            display: block;
        }

        @keyframes fadeIn {
            from {
                opacity: 0;
                transform: translateY(10px);
            }
            to {
                opacity: 1;
                transform: translateY(0);
            }
        }
    </style>
</body>

</html>
