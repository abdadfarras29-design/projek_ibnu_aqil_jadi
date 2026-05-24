<?php
session_start();
if (!isset($_SESSION['username'])) {
    header("Location: login.php");
    exit();
}

require_once 'koneksi.php';

$uploadDir = 'uploads/ekskul/';
if (!is_dir($uploadDir)) {
    mkdir($uploadDir, 0755, true);
}

$message = '';
$messageType = 'success';

// Handle Add / Edit
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $nama = mysqli_real_escape_string($KONEKSI, trim($_POST['nama'] ?? ''));
    $organisasi = ($_POST['organisasi'] === 'MPK') ? 'MPK' : 'OSIS';
    $jabatan = mysqli_real_escape_string($KONEKSI, trim($_POST['jabatan'] ?? ''));
    $role_field = $organisasi . ' - ' . $jabatan;

    // Handle upload if present
    $fotoPath = '';
    if (!empty($_FILES['foto']['name'])) {
        $fname = basename($_FILES['foto']['name']);
        $ext = pathinfo($fname, PATHINFO_EXTENSION);
        $newName = uniqid('ekskul_') . '.' . $ext;
        $target = $uploadDir . $newName;
        if (move_uploaded_file($_FILES['foto']['tmp_name'], $target)) {
            $fotoPath = $target;
        }
    }

    if (!empty($_POST['id'])) {
        // Update
        $id = (int)$_POST['id'];
        $sql = "SELECT `foto` FROM intrakulikuler WHERE id = $id";
        $r = mysqli_query($KONEKSI, $sql);
        if ($r && $row = mysqli_fetch_assoc($r)) {
            if ($fotoPath !== '' && !empty($row['foto']) && file_exists($row['foto'])) {
                @unlink($row['foto']);
            }
        }

        $updateFoto = $fotoPath !== '' ? "`foto` = '" . mysqli_real_escape_string($KONEKSI, $fotoPath) . "'," : '';
        $updateSql = "UPDATE intrakulikuler SET $updateFoto `nama` = '" . $nama . "', `role/jabatan` = '" . mysqli_real_escape_string($KONEKSI, $role_field) . "' WHERE id = $id";
        if (mysqli_query($KONEKSI, $updateSql)) {
            $message = 'Data berhasil diupdate!';
            $messageType = 'success';
        } else {
            $message = 'Gagal update: ' . mysqli_error($KONEKSI);
            $messageType = 'error';
        }
    } else {
        // Insert
        $fotoSql = $fotoPath !== '' ? "'" . mysqli_real_escape_string($KONEKSI, $fotoPath) . "'" : "''";
        $insertSql = "INSERT INTO intrakulikuler (`foto`, `nama`, `role/jabatan`) VALUES ($fotoSql, '" . $nama . "', '" . mysqli_real_escape_string($KONEKSI, $role_field) . "')";
        if (mysqli_query($KONEKSI, $insertSql)) {
            $message = 'Data berhasil ditambahkan!';
            $messageType = 'success';
        } else {
            $message = 'Gagal tambah: ' . mysqli_error($KONEKSI);
            $messageType = 'error';
        }
    }
}

// Handle delete
if (isset($_GET['action']) && $_GET['action'] === 'delete' && isset($_GET['id'])) {
    $id = (int)$_GET['id'];
    $q = mysqli_query($KONEKSI, "SELECT `foto` FROM intrakulikuler WHERE id = $id");
    if ($q && $r = mysqli_fetch_assoc($q)) {
        if (!empty($r['foto']) && file_exists($r['foto'])) {
            @unlink($r['foto']);
        }
    }
    mysqli_query($KONEKSI, "DELETE FROM intrakulikuler WHERE id = $id");
    header('Location: kelola-osis-mpk.php?deleted=1');
    exit;
}

// Fetch list
$res = mysqli_query($KONEKSI, "SELECT * FROM intrakulikuler ORDER BY id DESC");

// For edit form prefill
$edit = null;
if (isset($_GET['action']) && $_GET['action'] === 'edit' && isset($_GET['id'])) {
    $id = (int)$_GET['id'];
    $r = mysqli_query($KONEKSI, "SELECT * FROM intrakulikuler WHERE id = $id LIMIT 1");
    if ($r) $edit = mysqli_fetch_assoc($r);
}

if (isset($_GET['deleted'])) {
    $message = 'Data berhasil dihapus.';
    $messageType = 'info';
}
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width,initial-scale=1">
    <title>Kelola OSIS & MPK — Admin Panel</title>
    <meta name="description" content="Kelola data pengurus OSIS dan MPK sekolah">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700;800&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="dashboard-style.css">
    <style>
        /* ======= ROOT & BASE ======= */
        :root {
            --primary: #10b981;
            --primary-dark: #059669;
            --primary-light: #d1fae5;
            --accent: #6366f1;
            --accent-dark: #4f46e5;
            --danger: #ef4444;
            --danger-dark: #dc2626;
            --warning: #f59e0b;
            --info: #3b82f6;
            --bg: #f0fdf4;
            --surface: #ffffff;
            --surface2: #f8fafc;
            --border: #e2e8f0;
            --text: #0f172a;
            --text-muted: #64748b;
            --shadow-sm: 0 1px 3px rgba(0,0,0,0.06), 0 1px 2px rgba(0,0,0,0.04);
            --shadow: 0 4px 16px rgba(0,0,0,0.08);
            --shadow-lg: 0 20px 40px rgba(0,0,0,0.12);
            --radius: 16px;
            --radius-sm: 10px;
        }

        * { box-sizing: border-box; margin: 0; padding: 0; }

        body {
            font-family: 'Poppins', sans-serif;
            background: var(--bg);
            color: var(--text);
            min-height: 100vh;
            margin: 0;
            padding: 0;
        }

        /* ======= ADMIN NAVBAR ======= */
        .admin-navbar {
            background: linear-gradient(135deg, #10b981 0%, #059669 100%);
            color: white;
            padding: 14px 28px;
            display: flex;
            justify-content: space-between;
            align-items: center;
            box-shadow: 0 2px 12px rgba(0,0,0,0.12);
            position: sticky;
            top: 0;
            z-index: 100;
        }

        .admin-navbar-brand {
            font-size: 18px;
            font-weight: 700;
            display: flex;
            align-items: center;
            gap: 10px;
            letter-spacing: -0.02em;
        }

        .admin-navbar-brand img {
            height: 38px;
            width: auto;
            object-fit: contain;
            border-radius: 6px;
        }

        .admin-navbar-right {
            display: flex;
            align-items: center;
            gap: 14px;
        }

        .admin-navbar-right span {
            font-size: 0.9rem;
            opacity: 0.9;
        }

        .admin-badge {
            background: #fbbf24;
            color: #1f2937;
            padding: 5px 14px;
            border-radius: 20px;
            font-size: 0.8rem;
            font-weight: 700;
        }

        .admin-logout-btn {
            background: transparent;
            border: 2px solid rgba(255,255,255,0.7);
            color: white;
            padding: 7px 18px;
            border-radius: 8px;
            cursor: pointer;
            font-size: 0.85rem;
            font-weight: 600;
            transition: all 0.25s;
            text-decoration: none;
        }

        .admin-logout-btn:hover {
            background: white;
            color: #059669;
            border-color: white;
        }

        /* ======= ADMIN LAYOUT ======= */
        .admin-layout {
            display: flex;
            min-height: calc(100vh - 67px);
        }

        /* ======= SIDEBAR ======= */
        .admin-sidebar {
            width: 240px;
            background: white;
            box-shadow: 2px 0 10px rgba(0,0,0,0.06);
            flex-shrink: 0;
            padding: 16px 0;
        }

        .sidebar-item {
            display: flex;
            align-items: center;
            gap: 10px;
            padding: 13px 22px;
            cursor: pointer;
            border-left: 4px solid transparent;
            transition: all 0.25s;
            color: #4b5563;
            text-decoration: none;
            font-size: 0.9rem;
            font-weight: 500;
        }

        .sidebar-item:hover {
            background: #f3f4f6;
            color: #10b981;
        }

        .sidebar-item.active {
            background: #d1fae5;
            border-left-color: #10b981;
            color: #059669;
            font-weight: 700;
        }

        .sidebar-item svg {
            flex-shrink: 0;
            opacity: 0.7;
        }

        .sidebar-item.active svg,
        .sidebar-item:hover svg {
            opacity: 1;
        }

        .sidebar-divider {
            height: 1px;
            background: #f1f5f9;
            margin: 8px 16px;
        }

        /* ======= MAIN CONTENT ======= */
        .admin-main {
            flex: 1;
            padding: 28px 28px 40px;
            background: var(--bg);
            overflow-x: hidden;
        }

        /* ======= PAGE HEADER ======= */
        .page-top {
            display: flex;
            align-items: center;
            gap: 14px;
            margin-bottom: 28px;
            animation: slideDown 0.5s cubic-bezier(0.34,1.56,0.64,1) both;
        }

        .page-icon {
            width: 52px; height: 52px;
            border-radius: 14px;
            background: linear-gradient(135deg, var(--primary), var(--accent));
            display: flex; align-items: center; justify-content: center;
            font-size: 1.6rem;
            box-shadow: 0 8px 20px rgba(16,185,129,0.35);
            flex-shrink: 0;
        }

        .page-top h1 {
            font-size: 1.7rem;
            font-weight: 800;
            color: var(--text);
            letter-spacing: -0.03em;
        }

        .page-top p {
            color: var(--text-muted);
            font-size: 0.9rem;
            margin-top: 2px;
        }

        /* ======= ALERT ======= */
        .alert {
            display: flex;
            align-items: center;
            gap: 12px;
            padding: 14px 18px;
            border-radius: var(--radius-sm);
            font-weight: 500;
            font-size: 0.95rem;
            margin-bottom: 22px;
            animation: slideDown 0.4s ease both;
        }

        .alert.success { background: #ecfdf5; border: 1px solid #6ee7b7; color: #065f46; }
        .alert.error   { background: #fef2f2; border: 1px solid #fca5a5; color: #991b1b; }
        .alert.info    { background: #eff6ff; border: 1px solid #93c5fd; color: #1e40af; }

        .alert-close {
            margin-left: auto;
            background: none; border: none;
            cursor: pointer; font-size: 1.1rem;
            color: inherit; opacity: 0.6;
            transition: opacity 0.2s;
        }
        .alert-close:hover { opacity: 1; }

        /* ======= GRID LAYOUT ======= */
        .main-grid {
            display: grid;
            grid-template-columns: 1fr 380px;
            gap: 22px;
            align-items: start;
        }

        @media (max-width: 900px) {
            .main-grid { grid-template-columns: 1fr; }
        }

        /* ======= CARD ======= */
        .card {
            background: var(--surface);
            border-radius: var(--radius);
            box-shadow: var(--shadow);
            border: 1px solid var(--border);
            overflow: hidden;
            animation: fadeUp 0.5s ease both;
        }

        .card:nth-child(2) { animation-delay: 0.1s; }

        .card-header {
            display: flex;
            align-items: center;
            justify-content: space-between;
            padding: 20px 24px;
            border-bottom: 1px solid var(--border);
            background: linear-gradient(135deg, #f8fafc 0%, #f0fdf4 100%);
        }

        .card-header-left {
            display: flex;
            align-items: center;
            gap: 10px;
        }

        .card-header-icon {
            width: 36px; height: 36px;
            border-radius: 9px;
            background: linear-gradient(135deg, var(--primary), var(--primary-dark));
            display: flex; align-items: center; justify-content: center;
            font-size: 1rem;
            color: white;
        }

        .card-header h2 {
            font-size: 1.05rem;
            font-weight: 700;
            color: var(--text);
        }

        .card-header .count-badge {
            background: var(--primary-light);
            color: var(--primary-dark);
            font-size: 0.78rem;
            font-weight: 700;
            padding: 3px 10px;
            border-radius: 20px;
        }

        .card-body { padding: 0; }
        .card-body-padded { padding: 24px; }

        /* ======= TABLE ======= */
        .osis-table {
            width: 100%;
            border-collapse: collapse;
        }

        .osis-table thead {
            background: linear-gradient(90deg, #f0fdf4, #eff6ff);
        }

        .osis-table th {
            padding: 13px 16px;
            font-size: 0.78rem;
            font-weight: 700;
            text-transform: uppercase;
            letter-spacing: 0.06em;
            color: var(--text-muted);
            text-align: left;
        }

        .osis-table td {
            padding: 14px 16px;
            border-bottom: 1px solid #f1f5f9;
            vertical-align: middle;
        }

        .osis-table tbody tr {
            transition: background 0.2s;
        }

        .osis-table tbody tr:hover {
            background: #f8fafc;
        }

        .osis-table tbody tr:last-child td { border-bottom: none; }

        /* Avatar */
        .member-avatar {
            width: 46px; height: 46px;
            border-radius: 12px;
            object-fit: cover;
            box-shadow: var(--shadow-sm);
            transition: transform 0.2s;
        }

        .member-avatar:hover { transform: scale(1.08); }

        .avatar-placeholder {
            width: 46px; height: 46px;
            border-radius: 12px;
            background: linear-gradient(135deg, #e2e8f0, #cbd5e1);
            display: flex; align-items: center; justify-content: center;
            font-size: 1.4rem;
        }

        /* Name cell */
        .member-name {
            font-weight: 600;
            color: var(--text);
            font-size: 0.95rem;
        }

        /* Role badge */
        .role-badge {
            display: inline-flex;
            align-items: center;
            gap: 5px;
            padding: 5px 12px;
            border-radius: 20px;
            font-size: 0.78rem;
            font-weight: 700;
            letter-spacing: 0.02em;
        }

        .role-badge.osis {
            background: linear-gradient(135deg, #d1fae5, #a7f3d0);
            color: #065f46;
        }

        .role-badge.mpk {
            background: linear-gradient(135deg, #dbeafe, #bfdbfe);
            color: #1e40af;
        }

        .role-dot {
            width: 6px; height: 6px;
            border-radius: 50%;
            background: currentColor;
        }

        /* Row number */
        .row-num {
            width: 32px; height: 32px;
            background: var(--surface2);
            border-radius: 8px;
            display: flex; align-items: center; justify-content: center;
            font-weight: 600;
            font-size: 0.82rem;
            color: var(--text-muted);
        }

        /* Action buttons */
        .action-group {
            display: flex;
            gap: 6px;
        }

        .btn-icon {
            display: inline-flex;
            align-items: center;
            gap: 5px;
            padding: 6px 14px;
            border-radius: 8px;
            font-size: 0.8rem;
            font-weight: 600;
            text-decoration: none;
            cursor: pointer;
            border: none;
            transition: all 0.2s cubic-bezier(0.34,1.56,0.64,1);
        }

        .btn-icon:hover { transform: translateY(-2px); }

        .btn-edit {
            background: linear-gradient(135deg, #dbeafe, #bfdbfe);
            color: #1e40af;
        }
        .btn-edit:hover { background: linear-gradient(135deg, #3b82f6, #6366f1); color: white; box-shadow: 0 4px 12px rgba(99,102,241,0.35); }

        .btn-delete {
            background: linear-gradient(135deg, #fee2e2, #fecaca);
            color: #991b1b;
        }
        .btn-delete:hover { background: linear-gradient(135deg, #ef4444, #dc2626); color: white; box-shadow: 0 4px 12px rgba(239,68,68,0.35); }

        /* Empty state */
        .empty-state {
            text-align: center;
            padding: 48px 24px;
            color: var(--text-muted);
        }
        .empty-state .empty-icon { font-size: 3rem; margin-bottom: 12px; }
        .empty-state p { font-size: 0.95rem; }

        /* ======= FORM CARD ======= */
        .form-card-header {
            background: linear-gradient(135deg, var(--primary) 0%, var(--accent) 100%);
            padding: 20px 24px;
        }

        .form-card-header h2 {
            color: white;
            font-size: 1.05rem;
            font-weight: 700;
            display: flex;
            align-items: center;
            gap: 8px;
        }

        .form-card-header p {
            color: rgba(255,255,255,0.75);
            font-size: 0.82rem;
            margin-top: 4px;
        }

        .form-field {
            margin-bottom: 18px;
        }

        .form-field label {
            display: block;
            font-size: 0.82rem;
            font-weight: 600;
            color: var(--text-muted);
            text-transform: uppercase;
            letter-spacing: 0.05em;
            margin-bottom: 7px;
        }

        .form-input {
            width: 100%;
            padding: 10px 14px;
            border: 2px solid var(--border);
            border-radius: 10px;
            font-size: 0.95rem;
            font-family: 'Poppins', sans-serif;
            color: var(--text);
            background: var(--surface);
            transition: all 0.25s;
            outline: none;
        }

        .form-input:focus {
            border-color: var(--primary);
            box-shadow: 0 0 0 4px rgba(16,185,129,0.12);
        }

        .form-input::placeholder { color: #cbd5e1; }

        select.form-input {
            cursor: pointer;
            appearance: none;
            background-image: url("data:image/svg+xml,%3Csvg xmlns='http://www.w3.org/2000/svg' width='12' height='12' viewBox='0 0 24 24'%3E%3Cpath fill='%2364748b' d='M7 10l5 5 5-5z'/%3E%3C/svg%3E");
            background-repeat: no-repeat;
            background-position: right 12px center;
            padding-right: 36px;
        }

        .file-upload-wrapper {
            position: relative;
        }

        .file-upload-label {
            display: flex;
            align-items: center;
            gap: 10px;
            padding: 12px 16px;
            border: 2px dashed var(--border);
            border-radius: 10px;
            cursor: pointer;
            transition: all 0.25s;
            color: var(--text-muted);
            font-size: 0.88rem;
        }

        .file-upload-label:hover {
            border-color: var(--primary);
            background: var(--primary-light);
            color: var(--primary-dark);
        }

        .file-upload-label .upload-icon { font-size: 1.3rem; }

        input[type="file"] { display: none; }

        .preview-img {
            margin-top: 10px;
            border-radius: 10px;
            width: 90px; height: 90px;
            object-fit: cover;
            box-shadow: var(--shadow);
            border: 2px solid var(--primary-light);
        }

        .btn-submit {
            width: 100%;
            padding: 13px;
            border-radius: 12px;
            border: none;
            font-family: 'Poppins', sans-serif;
            font-size: 0.95rem;
            font-weight: 700;
            cursor: pointer;
            transition: all 0.3s cubic-bezier(0.34,1.56,0.64,1);
            background: linear-gradient(135deg, var(--primary), var(--accent));
            color: white;
            box-shadow: 0 4px 15px rgba(16,185,129,0.3);
            display: flex;
            align-items: center;
            justify-content: center;
            gap: 8px;
            margin-top: 4px;
        }

        .btn-submit:hover {
            transform: translateY(-3px);
            box-shadow: 0 8px 25px rgba(16,185,129,0.45);
        }

        .btn-cancel {
            width: 100%;
            padding: 11px;
            border-radius: 12px;
            border: 2px solid var(--border);
            font-family: 'Poppins', sans-serif;
            font-size: 0.9rem;
            font-weight: 600;
            cursor: pointer;
            transition: all 0.2s;
            background: white;
            color: var(--text-muted);
            text-decoration: none;
            display: flex;
            align-items: center;
            justify-content: center;
            gap: 6px;
            margin-top: 8px;
        }

        .btn-cancel:hover {
            border-color: var(--danger);
            color: var(--danger);
            background: #fef2f2;
        }

        /* ======= ANIMATIONS ======= */
        @keyframes slideDown {
            from { opacity: 0; transform: translateY(-14px); }
            to   { opacity: 1; transform: translateY(0); }
        }

        @keyframes fadeUp {
            from { opacity: 0; transform: translateY(20px); }
            to   { opacity: 1; transform: translateY(0); }
        }

        /* ======= EDIT MODE highlight ======= */
        .editing-mode .form-card-header {
            background: linear-gradient(135deg, var(--warning) 0%, #f97316 100%);
        }

        .editing-mode .btn-submit {
            background: linear-gradient(135deg, var(--warning), #f97316);
            box-shadow: 0 4px 15px rgba(245,158,11,0.35);
        }
        .editing-mode .btn-submit:hover {
            box-shadow: 0 8px 25px rgba(245,158,11,0.5);
        }

        .editing-mode .form-input:focus {
            border-color: var(--warning);
            box-shadow: 0 0 0 4px rgba(245,158,11,0.12);
        }
    </style>
</head>
<body>

    <!-- Admin Navbar -->
    <nav class="admin-navbar">
        <div class="admin-navbar-brand">
            <img src="Screenshot_2026-02-22-13-16-05-58_1c337646f29875672b5a61192b9010f9.png" alt="Logo">
            ADMIN PANEL
        </div>
        <div class="admin-navbar-right">
            <span>Selamat datang, <strong><?= htmlspecialchars($_SESSION['username'] ?? 'Admin') ?></strong></span>
            <span class="admin-badge">Admin</span>
            <a href="menanyakan logout.php" class="admin-logout-btn">Logout</a>
        </div>
    </nav>

    <!-- Admin Layout -->
    <div class="admin-layout">

        <!-- Sidebar -->
        <aside class="admin-sidebar">
            <a href="dashboard-superadmin.php" class="sidebar-item">
                <svg width="18" height="18" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><rect x="3" y="3" width="7" height="7"/><rect x="14" y="3" width="7" height="7"/><rect x="3" y="14" width="7" height="7"/><rect x="14" y="14" width="7" height="7"/></svg>
                Overview
            </a>
            <a href="kelola-konten.php" class="sidebar-item">
                <svg width="18" height="18" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path d="M14 2H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V8z"/><polyline points="14 2 14 8 20 8"/></svg>
                Kelola Konten
            </a>
            <a href="kelola-osis-mpk.php" class="sidebar-item active">
                <svg width="18" height="18" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path d="M17 21v-2a4 4 0 0 0-4-4H5a4 4 0 0 0-4 4v2"/><circle cx="9" cy="7" r="4"/><path d="M23 21v-2a4 4 0 0 0-3-3.87"/><path d="M16 3.13a4 4 0 0 1 0 7.75"/></svg>
                Kelola OSIS & MPK
            </a>
            <div class="sidebar-divider"></div>
            <a href="menanyakan logout.php" class="sidebar-item">
                <svg width="18" height="18" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path d="M9 21H5a2 2 0 0 1-2-2V5a2 2 0 0 1 2-2h4"/><polyline points="16 17 21 12 16 7"/><line x1="21" y1="12" x2="9" y2="12"/></svg>
                Logout
            </a>
        </aside>

        <!-- Main Content -->
        <main class="admin-main">

    <!-- Page Header -->
    <div class="page-top">
        <div class="page-icon">
            <svg width="26" height="26" viewBox="0 0 24 24" fill="none" stroke="white" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M3 9l9-7 9 7v11a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2z"/><polyline points="9 22 9 12 15 12 15 22"/></svg>
        </div>
        <div>
            <h1>Kelola Pengurus OSIS & MPK</h1>
            <p>Manajemen data pengurus organisasi siswa</p>
        </div>
    </div>

    <!-- Alert Message -->
    <?php if ($message): ?>
    <div class="alert <?= $messageType ?>" id="alertBox">
        <span><?= htmlspecialchars($message) ?></span>
        <button class="alert-close" onclick="document.getElementById('alertBox').remove()">✕</button>
    </div>
    <?php endif; ?>

    <div class="main-grid">

        <!-- ===== LEFT: Table ===== -->
        <div class="card">
            <div class="card-header">
                <div class="card-header-left">
                    <div class="card-header-icon">
                        <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="white" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M14 2H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V8z"/><polyline points="14 2 14 8 20 8"/><line x1="16" y1="13" x2="8" y2="13"/><line x1="16" y1="17" x2="8" y2="17"/><polyline points="10 9 9 9 8 9"/></svg>
                    </div>
                    <h2>Daftar Pengurus</h2>
                </div>
                <?php
                    $countRes = mysqli_query($KONEKSI, "SELECT COUNT(*) as total FROM intrakulikuler");
                    $countRow = mysqli_fetch_assoc($countRes);
                    $total = $countRow['total'] ?? 0;
                ?>
                <span class="count-badge"><?= $total ?> anggota</span>
            </div>
            <div class="card-body">
                <?php if ($total == 0): ?>
                <div class="empty-state">
                    <div class="empty-icon">
                        <svg width="48" height="48" viewBox="0 0 24 24" fill="none" stroke="#cbd5e1" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"><path d="M17 21v-2a4 4 0 0 0-4-4H5a4 4 0 0 0-4 4v2"/><circle cx="9" cy="7" r="4"/><path d="M23 21v-2a4 4 0 0 0-3-3.87"/><path d="M16 3.13a4 4 0 0 1 0 7.75"/></svg>
                    </div>
                    <p>Belum ada data pengurus.<br>Tambahkan dari form di samping.</p>
                </div>
                <?php else: ?>
                <div style="overflow-x:auto;">
                    <table class="osis-table">
                        <thead>
                            <tr>
                                <th>#</th>
                                <th>Foto</th>
                                <th>Nama</th>
                                <th>Peran & Jabatan</th>
                                <th>Aksi</th>
                            </tr>
                        </thead>
                        <tbody>
                        <?php $i = 1; while ($row = mysqli_fetch_assoc($res)): 
                            $roleJabatan = $row['role/jabatan'] ?? '';
                            $isOSIS = stripos($roleJabatan, 'OSIS') === 0;
                            $badgeClass = $isOSIS ? 'osis' : 'mpk';
                            $orgLabel   = $isOSIS ? 'OSIS' : 'MPK';
                            $roleParts  = explode(' - ', $roleJabatan, 2);
                            $jabatanLabel = $roleParts[1] ?? $roleJabatan;
                        ?>
                            <tr>
                                <td><div class="row-num"><?= $i++ ?></div></td>
                                <td>
                                    <?php if (!empty($row['foto']) && file_exists($row['foto'])): ?>
                                        <img src="<?= htmlspecialchars($row['foto']) ?>" class="member-avatar" alt="Foto">
                                    <?php else: ?>
                                        <div class="avatar-placeholder">
                                            <svg width="22" height="22" viewBox="0 0 24 24" fill="none" stroke="#94a3b8" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M20 21v-2a4 4 0 0 0-4-4H8a4 4 0 0 0-4 4v2"/><circle cx="12" cy="7" r="4"/></svg>
                                        </div>
                                    <?php endif; ?>
                                </td>
                                <td><span class="member-name"><?= htmlspecialchars($row['nama']) ?></span></td>
                                <td>
                                    <span class="role-badge <?= $badgeClass ?>">
                                        <span class="role-dot"></span>
                                        <?= htmlspecialchars($orgLabel) ?> · <?= htmlspecialchars($jabatanLabel) ?>
                                    </span>
                                </td>
                                <td>
                                    <div class="action-group">
                                        <a class="btn-icon btn-edit" href="kelola-osis-mpk.php?action=edit&id=<?= $row['id'] ?>">Edit</a>
                                        <a class="btn-icon btn-delete" href="kelola-osis-mpk.php?action=delete&id=<?= $row['id'] ?>" onclick="return confirm('Yakin ingin menghapus pengurus ini?')">Hapus</a>
                                    </div>
                                </td>
                            </tr>
                        <?php endwhile; ?>
                        </tbody>
                    </table>
                </div>
                <?php endif; ?>
            </div>
        </div>

        <!-- ===== RIGHT: Form ===== -->
        <div class="card <?= $edit ? 'editing-mode' : '' ?>">
            <div class="form-card-header">
                <h2><?php echo $edit ? 'Edit Pengurus' : 'Tambah Pengurus'; ?></h2>
                <p><?php echo $edit ? 'Perbarui data pengurus yang sudah ada' : 'Isi form untuk menambah pengurus baru'; ?></p>
            </div>
            <div class="card-body-padded">
                <form method="post" enctype="multipart/form-data" id="mainForm">
                    <?php if ($edit): ?>
                        <input type="hidden" name="id" value="<?= intval($edit['id']) ?>">
                    <?php endif; ?>

                    <div class="form-field">
                        <label for="nama">Nama Lengkap</label>
                        <input
                            type="text"
                            id="nama"
                            name="nama"
                            class="form-input"
                            placeholder="Masukkan nama lengkap…"
                            value="<?= htmlspecialchars($edit['nama'] ?? '') ?>"
                            required
                        >
                    </div>

                    <div class="form-field">
                        <label for="organisasi">Organisasi</label>
                        <select id="organisasi" name="organisasi" class="form-input">
                            <option value="OSIS" <?= (isset($edit['role/jabatan']) && stripos($edit['role/jabatan'], 'OSIS') === 0) ? 'selected' : '' ?>>OSIS</option>
                            <option value="MPK"  <?= (isset($edit['role/jabatan']) && stripos($edit['role/jabatan'], 'MPK')  === 0) ? 'selected' : '' ?>>MPK</option>
                        </select>
                    </div>

                    <div class="form-field">
                        <label for="jabatan">Jabatan</label>
                        <input
                            type="text"
                            id="jabatan"
                            name="jabatan"
                            class="form-input"
                            placeholder="Contoh: Ketua, Wakil, Sekretaris…"
                            value="<?php
                                if (isset($edit['role/jabatan'])) {
                                    $parts = explode(' - ', $edit['role/jabatan'], 2);
                                    echo htmlspecialchars($parts[1] ?? '');
                                }
                            ?>"
                            required
                        >
                    </div>

                    <div class="form-field">
                        <label>Foto (jpg/png)</label>
                        <div class="file-upload-wrapper">
                            <label class="file-upload-label" for="foto" id="fileLabel">
                                <span class="upload-icon">
                                    <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M21 15v4a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2v-4"/><polyline points="17 8 12 3 7 8"/><line x1="12" y1="3" x2="12" y2="15"/></svg>
                                </span>
                                <span id="fileName">Klik untuk pilih foto…</span>
                            </label>
                            <input type="file" id="foto" name="foto" accept="image/*" onchange="previewFoto(this)">
                        </div>
                        <?php if ($edit && !empty($edit['foto'])): ?>
                            <img src="<?= htmlspecialchars($edit['foto']) ?>" class="preview-img" id="imgPreview" alt="Preview">
                        <?php else: ?>
                            <img id="imgPreview" class="preview-img" style="display:none;" alt="Preview">
                        <?php endif; ?>
                    </div>

                    <button type="submit" class="btn-submit">
                        <?php echo $edit ? 'Simpan Perubahan' : 'Tambah Pengurus'; ?>
                    </button>

                    <?php if ($edit): ?>
                        <a href="kelola-osis-mpk.php" class="btn-cancel">Batal Edit</a>
                    <?php endif; ?>
                </form>
            </div>
        </div>

    </div><!-- /.main-grid -->

        </main>
    </div><!-- /.admin-layout -->

    <script>
        // Preview foto before upload
        function previewFoto(input) {
            const preview = document.getElementById('imgPreview');
            const label   = document.getElementById('fileName');
            if (input.files && input.files[0]) {
                const file = input.files[0];
                label.textContent = file.name;
                const reader = new FileReader();
                reader.onload = e => {
                    preview.src = e.target.result;
                    preview.style.display = 'block';
                };
                reader.readAsDataURL(file);
            }
        }

        // Auto-dismiss alert after 4s
        const alertBox = document.getElementById('alertBox');
        if (alertBox) {
            setTimeout(() => {
                alertBox.style.transition = 'opacity 0.4s';
                alertBox.style.opacity = '0';
                setTimeout(() => alertBox.remove(), 400);
            }, 4000);
        }
    </script>
</body>
</html>
