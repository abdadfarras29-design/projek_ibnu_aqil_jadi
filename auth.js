// ===========================
// USER DATABASE
// ===========================

// Database pengguna (dalam aplikasi nyata, ini dari backend/database)
const users = [
    {
        id: 1,
        username: 'koboyadmin',
        password: 'koboy67',
        role: 'super_admin',
        name: 'Super Administrator',
        email: 'superadmin@sekolahkita.sch.id'
    },
    {
        id: 2,
        username: 'admin',
        password: 'admin123',
        role: 'admin',
        name: 'Administrator',
        email: 'admin@sekolahkita.sch.id'
    }
];

// ===========================
// AUTHENTICATION FUNCTIONS
// ===========================

/**
 * Handle login form submission
 */
function handleLogin(event) {
    event.preventDefault();
    
    const username = document.getElementById('username').value.trim();
    const password = document.getElementById('password').value;
    const rememberMe = document.getElementById('rememberMe').checked;
    
    // Reset error states
    document.getElementById('username').classList.remove('error');
    document.getElementById('password').classList.remove('error');
    
    // Validasi input
    if (!username || !password) {
        showAlert('Username dan password harus diisi!', 'error');
        return;
    }
    
    // Cari user di database
    const user = users.find(u => u.username === username && u.password === password);
    
    if (user) {
        // Login berhasil
        const sessionData = {
            userId: user.id,
            username: user.username,
            role: user.role,
            name: user.name,
            email: user.email,
            loginTime: new Date().toISOString()
        };
        
        // Simpan session
        if (rememberMe) {
            localStorage.setItem('userSession', JSON.stringify(sessionData));
        } else {
            sessionStorage.setItem('userSession', JSON.stringify(sessionData));
        }
        
        // Tampilkan pesan sukses
        showAlert('Login berhasil! Mengalihkan...', 'success');
        
        // Redirect ke dashboard sesuai role
        setTimeout(() => {
            if (user.role === 'super_admin') {
                window.location.href = 'dashboard-superadmin.php';
            } else {
                window.location.href = 'dashboard-admin.php';
            }
        }, 1000);
        
    } else {
        // Login gagal
        showAlert('Username atau password salah!', 'error');
        document.getElementById('username').classList.add('error');
        document.getElementById('password').classList.add('error');
        document.getElementById('password').value = '';
    }
}

/**
 * Toggle password visibility
 */
function togglePassword() {
    const passwordField = document.getElementById('password');
    const toggleIcon = document.querySelector('.toggle-password');
    
    if (passwordField.type === 'password') {
        passwordField.type = 'text';
        toggleIcon.textContent = '🙈';
    } else {
        passwordField.type = 'password';
        toggleIcon.textContent = '👁️';
    }
}

/**
 * Handle forgot password
 */
function handleForgotPassword(event) {
    event.preventDefault();
    alert('Silakan hubungi administrator sekolah untuk reset password.\n\nEmail: admin@sekolahkita.sch.id\nWhatsApp: +62 812-3456-7890');
}

/**
 * Show alert message
 */
function showAlert(message, type) {
    const alertBox = document.getElementById('alertBox');
    alertBox.textContent = message;
    alertBox.className = `alert ${type} show`;
    
    // Auto hide after 5 seconds
    setTimeout(() => {
        alertBox.classList.remove('show');
    }, 5000);
}

/**
 * Check if user is already logged in
 */
function checkExistingSession() {
    const sessionData = localStorage.getItem('userSession') || sessionStorage.getItem('userSession');
    
    if (sessionData) {
        const user = JSON.parse(sessionData);
        // Redirect to appropriate dashboard
        if (user.role === 'super_admin') {
            window.location.href = 'dashboard-superadmin.php';
        } else {
            window.location.href = 'dashboard-admin.php';
        }
    }
}

/**
 * Get current user session
 */
function getCurrentUser() {
    const sessionData = localStorage.getItem('userSession') || sessionStorage.getItem('userSession');
    return sessionData ? JSON.parse(sessionData) : null;
}

/**
 * Check if user has specific role
 */
function hasRole(requiredRole) {
    const user = getCurrentUser();
    if (!user) return false;
    
    if (requiredRole === 'super_admin') {
        return user.role === 'super_admin';
    } else if (requiredRole === 'admin') {
        return user.role === 'admin' || user.role === 'super_admin';
    }
    return false;
}

/**
 * Require authentication
 */
function requireAuth() {
    const user = getCurrentUser();
    if (!user) {
        window.location.href = 'login.php';
        return false;
    }
    return true;
}

/**
 * Require specific role
 */
function requireRole(role) {
    if (!requireAuth()) return false;
    
    if (!hasRole(role)) {
        alert('Anda tidak memiliki akses ke halaman ini!');
        window.location.href = 'login.php';
        return false;
    }
    return true;
}

/**
 * Logout function
 */
function logout() {
    if (confirm('Apakah Anda yakin ingin logout?')) {
        localStorage.removeItem('userSession');
        sessionStorage.removeItem('userSession');
        window.location.href = 'login.php';
    }
}

// ===========================
// AUTO-CHECK SESSION
// ===========================

// Check existing session when login page loads
if (window.location.pathname.includes('login.php')) {
    checkExistingSession();
}

// ===========================
// KEYBOARD SHORTCUTS
// ===========================

// Enter key to submit
document.addEventListener('DOMContentLoaded', () => {
    const loginForm = document.getElementById('loginForm');
    if (loginForm) {
        const inputs = loginForm.querySelectorAll('input');
        inputs.forEach(input => {
            input.addEventListener('keypress', (e) => {
                if (e.key === 'Enter') {
                    e.preventDefault();
                    loginForm.dispatchEvent(new Event('submit'));
                }
            });
        });
    }
});
