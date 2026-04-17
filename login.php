<?php
session_start();
include 'koneksi.php';

if (isset($_SESSION['username'])) {
    header("Location: dashboard-superadmin.php");
    exit();
}

$error = '';

if (isset($_POST['login'])) {
    $username = $_POST['username'];
    $password = $_POST['password'];

    // Query menyesuaikan dengan struktur database di gambar (Username, Password)
    $query = "SELECT * FROM admin WHERE Username = '$username' AND Password = '$password'";
    $result = mysqli_query($KONEKSI, $query);

    if (mysqli_num_rows($result) > 0) {
        $row = mysqli_fetch_assoc($result);
        $_SESSION['username'] = $row['Username'];
        $_SESSION['role'] = 'super_admin'; // Default role

        header("Location: dashboard-superadmin.php");
        exit();
    } else {
        $error = "Username atau password salah.";
    }
}
?>
<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login - Website Sekolah</title>
    <link rel="stylesheet" href="style.css">
    <style>
        .login-container {
            min-height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
            background: linear-gradient(135deg, var(--light-green) 0%, var(--primary-green) 50%, var(--dark-green) 100%);
            padding: 2rem;
        }

        .login-box {
            background: var(--white);
            border-radius: 20px;
            padding: 3rem;
            box-shadow: 0 20px 60px rgba(0, 0, 0, 0.3);
            max-width: 450px;
            width: 100%;
            animation: fadeInUp 0.5s ease;
        }

        .login-header {
            text-align: center;
            margin-bottom: 2rem;
        }

        .login-header .logo-img-login {
            height: 100px;
            width: auto;
            margin-bottom: 1rem;
        }

        .login-header h2 {
            color: var(--dark-green);
            font-size: 2rem;
            margin-bottom: 0.5rem;
        }

        .login-header p {
            color: var(--text-gray);
            font-size: 0.95rem;
        }

        .form-group {
            margin-bottom: 1.5rem;
        }

        .form-group label {
            display: block;
            color: var(--text-dark);
            font-weight: 600;
            margin-bottom: 0.5rem;
            font-size: 0.95rem;
        }

        .form-control {
            width: 100%;
            padding: 0.9rem 1rem;
            border: 2px solid #e5e7eb;
            border-radius: 10px;
            font-size: 1rem;
            transition: all 0.3s;
            box-sizing: border-box;
        }

        .form-control:focus {
            outline: none;
            border-color: #3b82f6;
            box-shadow: 0 0 0 3px rgba(59, 130, 246, 0.1);
        }

        .form-control.error {
            border-color: #ef4444;
        }

        .password-wrapper {
            position: relative;
        }

        .toggle-password {
            position: absolute;
            right: 1rem;
            top: 50%;
            transform: translateY(-50%);
            cursor: pointer;
            display: flex;
            align-items: center;
            justify-content: center;
            color: #1f2937;
            user-select: none;
            height: 24px;
            width: 24px;
        }

        .toggle-password:hover {
            color: #3b82f6;
        }

        .remember-forgot {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 1.5rem;
            font-size: 0.9rem;
        }

        .remember-me {
            display: flex;
            align-items: center;
            gap: 0.5rem;
            color: var(--text-gray);
        }

        .remember-me input[type="checkbox"] {
            width: 18px;
            height: 18px;
            cursor: pointer;
            accent-color: var(--primary-green);
        }

        .login-button {
            width: 100%;
            padding: 1rem;
            background: linear-gradient(135deg, var(--primary-green), var(--dark-green));
            color: var(--white);
            border: none;
            border-radius: 10px;
            font-size: 1.1rem;
            font-weight: bold;
            cursor: pointer;
            transition: all 0.3s;
            margin-bottom: 1rem;
        }

        .login-button:hover {
            transform: translateY(-2px);
            box-shadow: 0 10px 25px rgba(16, 185, 129, 0.3);
        }

        .login-button:active {
            transform: translateY(0);
            box-shadow: none;
        }

        .back-home {
            text-align: center;
            margin-top: 1.5rem;
        }

        .back-home a {
            color: var(--primary-green);
            text-decoration: none;
            font-weight: 500;
            display: inline-flex;
            align-items: center;
            gap: 0.5rem;
        }

        .back-home a:hover {
            text-decoration: underline;
        }

        .alert {
            padding: 1rem;
            border-radius: 10px;
            margin-bottom: 1.5rem;
            font-size: 0.9rem;
            display: none;
        }

        .alert.error {
            background: #fee2e2;
            border: 1px solid #fca5a5;
            color: #991b1b;
        }

        .alert.show {
            display: block;
            animation: slideDown 0.3s ease;
        }

        @keyframes slideDown {
            from {
                opacity: 0;
                transform: translateY(-10px);
            }

            to {
                opacity: 1;
                transform: translateY(0);
            }
        }

        @media (max-width: 480px) {
            .login-box {
                padding: 2rem 1.5rem;
            }

            .login-header h2 {
                font-size: 1.5rem;
            }
        }
    </style>
</head>

<body>
    <div class="login-container">
        <div class="login-box">
            <div class="login-header">
                <img src="Screenshot_2026-02-22-13-16-05-58_1c337646f29875672b5a61192b9010f9.png"
                    alt="Logo SMP Ibnu Aqil" class="logo-img-login">
                <h2>SMP IBNU AQIL</h2>
                <p>Silakan login untuk mengakses dashboard</p>
            </div>

            <?php if ($error): ?>
                <div class="alert error show">
                    <?php echo $error; ?>
                </div>
            <?php endif; ?>

            <form method="POST" action="">
                <div class="form-group">
                    <label for="username">Username</label>
                    <input type="text" id="username" name="username" class="form-control"
                        placeholder="Masukkan username" required autocomplete="off">
                </div>

                <div class="form-group">
                    <label for="password">Password</label>
                    <div class="password-wrapper">
                        <input type="password" id="password" name="password" class="form-control"
                            placeholder="Masukkan password" required autocomplete="off" style="padding-right: 3rem;">
                        <span class="toggle-password" id="togglePassword" onclick="togglePassword()">
                            <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24"
                                fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round"
                                stroke-linejoin="round">
                                <path
                                    d="M17.94 17.94A10.07 10.07 0 0 1 12 20c-7 0-11-8-11-8a18.45 18.45 0 0 1 5.06-5.94M9.9 4.24A9.12 9.12 0 0 1 12 4c7 0 11 8 11 8a18.5 18.5 0 0 1-2.16 3.19m-6.72-1.07a3 3 0 1 1-4.24-4.24">
                                </path>
                                <line x1="1" y1="1" x2="23" y2="23"></line>
                            </svg>
                        </span>
                    </div>
                </div>

                <div class="remember-forgot">
                    <label class="remember-me">
                        <input type="checkbox" id="rememberMe" name="remember">
                        <span>Ingat saya</span>
                    </label>
                </div>

                <button type="submit" name="login" class="login-button">
                    Login
                </button>
            </form>

            <div class="back-home">
                <a href="index.php">
                    ← Kembali ke Website
                </a>
            </div>
        </div>
    </div>

    <!-- Inline script instead of auth.js to avoid conflict -->
    <script>
        function togglePassword() {
            const passwordField = document.getElementById('password');
            const toggleIcon = document.getElementById('togglePassword');

            if (passwordField.type === 'password') {
                passwordField.type = 'text';
                toggleIcon.innerHTML = `
                    <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                        <path d="M1 12s4-8 11-8 11 8 11 8-4 8-11 8-11-8-11-8z"></path>
                        <circle cx="12" cy="12" r="3"></circle>
                    </svg>
                `;
            } else {
                passwordField.type = 'password';
                toggleIcon.innerHTML = `
                    <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                        <path d="M17.94 17.94A10.07 10.07 0 0 1 12 20c-7 0-11-8-11-8a18.45 18.45 0 0 1 5.06-5.94M9.9 4.24A9.12 9.12 0 0 1 12 4c7 0 11 8 11 8a18.5 18.5 0 0 1-2.16 3.19m-6.72-1.07a3 3 0 1 1-4.24-4.24"></path>
                        <line x1="1" y1="1" x2="23" y2="23"></line>
                    </svg>
                `;
            }
        }
    </script>
</body>

</html>