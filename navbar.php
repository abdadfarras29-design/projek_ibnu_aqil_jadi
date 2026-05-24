<?php
// navbar.php - Komponen navbar yang dapat di-reuse
if (!isset($_SESSION)) {
    session_start();
}
$isLoggedIn = isset($_SESSION['username']);
$username = $_SESSION['username'] ?? '';
?>
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
            <?php if ($isLoggedIn): ?>
                <li class="dropdown">
                    <a href="#" class="dropbtn nav-login-btn" style="background: var(--primary-green); color: white;">
                        👤 <?php echo htmlspecialchars(strlen($username) > 10 ? substr($username, 0, 10) . '...' : $username); ?> ▾
                    </a>
                    <ul class="dropdown-content" style="min-width: 180px;">
                        <li><a href="profile-user.php">👤 Profil Saya</a></li>
                        <li><a href="logout.php" style="color: #ef4444;">🚪 Logout</a></li>
                    </ul>
                </li>
            <?php else: ?>
                <li><a href="login.php" class="nav-login-btn">Login</a></li>
            <?php endif; ?>
        </ul>
        <div class="hamburger" onclick="toggleMenu()">
            <span></span>
            <span></span>
            <span></span>
        </div>
    </div>
</nav>
