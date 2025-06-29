<?php
session_start();

// Enable error reporting for debugging
error_reporting(E_ALL);
ini_set('display_errors', 1);

// Check if user is logged in and is a mentee
if (!isset($_SESSION['user_id']) || $_SESSION['user_type'] !== 'Mentee') {
    // Add debugging information
    error_log("Settings.php access denied. Session data: " . print_r($_SESSION, true));
    header("Location: ../landingpage/landingpage.php");
    exit();
}

$user_id = $_SESSION['user_id'];
$username = $_SESSION['username'];
$email = $_SESSION['email'];
$user_type = $_SESSION['user_type'];
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>MindCraft - Pengaturan</title>
    <link rel="stylesheet" href="/MindCraft-Project/assets/css/mentee-dashboard.css">
    <link rel="stylesheet" href="/MindCraft-Project/assets/css/header-mentee.css">
    <link rel="stylesheet" href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700&display=swap">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <style>
        :root {
            --primary: #6366f1;
            --primary-light: #a5b4fc;
            --dark: #1e293b;
            --light: #f8fafc;
            --gray: #94a3b8;
            --success: #10b981;
            --warning: #f59e0b;
            --danger: #ef4444;
        }
        
        body {
            font-family: 'Poppins', sans-serif;
            background-color: #f1f5f9;
            color: var(--dark);
            line-height: 1.6;
        }
        
        .header {
            background: white;
            box-shadow: 0 2px 10px rgba(0,0,0,0.1);
            position: sticky;
            top: 0;
            z-index: 100;
        }
        
        .header-content {
            display: flex;
            align-items: center;
            justify-content: space-between;
            padding: 1rem 0;
        }
        
        .logo {
            display: flex;
            align-items: center;
            gap: 0.75rem;
        }
        
        .logo img {
            height: 40px;
        }
        
        .logo h1 {
            font-size: 1.5rem;
            font-weight: 700;
            color: var(--dark);
        }
        
        .logo h1 span {
            color: var(--primary);
        }
        
        .main-nav ul {
            display: flex;
            gap: 1.5rem;
        }
        
        .main-nav a {
            color: var(--gray);
            font-weight: 500;
            transition: all 0.3s ease;
            position: relative;
        }
        
        .main-nav a:hover, .main-nav a.active {
            color: var(--primary);
        }
        
        .main-nav a.active:after {
            content: '';
            position: absolute;
            bottom: -6px;
            left: 0;
            width: 100%;
            height: 3px;
            background: var(--primary);
            border-radius: 3px;
        }
        
        .user-menu {
            display: flex;
            align-items: center;
            gap: 0.75rem;
            cursor: pointer;
        }
        
        .user-avatar {
            width: 40px;
            height: 40px;
            border-radius: 50%;
            background: linear-gradient(135deg, var(--primary), #8b5cf6);
            color: white;
            display: flex;
            align-items: center;
            justify-content: center;
            font-weight: 600;
            font-size: 1.1rem;
        }
        
        .username {
            font-weight: 500;
            color: var(--dark);
        }
        
        .container {
            width: 90%;
            max-width: 1200px;
            margin: 0 auto;
        }
        
        .settings-section {
            padding: 2.5rem 0;
        }
        
        .settings-section h2 {
            font-size: 2rem;
            font-weight: 600;
            margin-bottom: 2rem;
            color: var(--dark);
        }
        
        .settings-content {
            max-width: 600px;
            margin: 0 auto;
            background: white;
            padding: 2.5rem;
            border-radius: 16px;
            box-shadow: 0 10px 15px -3px rgba(0,0,0,0.1);
        }
        
        .settings-form .form-group {
            margin-bottom: 1.75rem;
        }
        
        .settings-form label {
            display: block;
            margin-bottom: 0.75rem;
            font-weight: 500;
            color: var(--dark);
            font-size: 0.95rem;
        }
        
        .settings-form input {
            width: 100%;
            padding: 0.85rem 1rem;
            border: 1px solid #e2e8f0;
            border-radius: 10px;
            font-size: 1rem;
            transition: all 0.3s ease;
        }
        
        .settings-form input:focus {
            border-color: var(--primary);
            box-shadow: 0 0 0 3px rgba(99,102,241,0.2);
            outline: none;
        }
        
        .settings-form input:read-only {
            background: #f8fafc;
            color: var(--gray);
            cursor: not-allowed;
        }
        
        .form-actions {
            display: flex;
            gap: 1rem;
            margin-top: 2.5rem;
        }
        
        .btn {
            padding: 0.75rem 1.5rem;
            border-radius: 8px;
            font-weight: 500;
            transition: all 0.3s ease;
            display: inline-flex;
            align-items: center;
            gap: 0.5rem;
            cursor: pointer;
            text-decoration: none;
            border: none;
            font-size: 0.95rem;
        }
        
        .btn-primary {
            background: var(--primary);
            color: white;
        }
        
        .btn-primary:hover {
            background: #4f46e5;
            transform: translateY(-2px);
            box-shadow: 0 4px 6px -1px rgba(0,0,0,0.1);
        }
        
        .btn-secondary {
            background: white;
            color: var(--primary);
            border: 1px solid var(--primary);
        }
        
        .btn-secondary:hover {
            background: #f1f5ff;
            transform: translateY(-2px);
            box-shadow: 0 4px 6px -1px rgba(0,0,0,0.1);
        }
        
        .password-input-container {
            position: relative;
        }
        
        .password-toggle {
            position: absolute;
            right: 12px;
            top: 50%;
            transform: translateY(-50%);
            color: var(--gray);
            cursor: pointer;
        }
        
        @media (max-width: 768px) {
            .form-actions {
                flex-direction: column;
            }
            
            .btn {
                width: 100%;
                justify-content: center;
            }
        }
    </style>
</head>
<body>
    <header class="header">
        <div class="container">
            <div class="header-content">
                <div class="logo">
                    <img src="/MindCraft-Project/assets/img/20250502_083014.png" alt="MindCraft Logo" id="logo-img">
                    <h1>Mind<span>Craft</span></h1>
                </div>
                <nav class="main-nav">
                    <ul>
                        <li><a href="dashboard.php">Beranda</a></li>
                        <li><a href="kursus.php">Kursus</a></li>
                        <li><a href="ai_assistant.php">🤖 MindBot</a></li>
                        <li><a href="#" class="active">Pengaturan</a></li>
                    </ul>
                </nav>
                <div class="header-actions">
                    <div class="user-menu">
                        <div class="user-avatar">
                            <?php echo strtoupper(substr($username, 0, 1)); ?>
                        </div>
                        <span class="username"><?php echo htmlspecialchars($username); ?></span>
                    </div>
                </div>
            </div>
        </div>
    </header>

    <section class="settings-section">
        <div class="container">
            <h2>Pengaturan Akun</h2>
            <div class="settings-content">
                <form class="settings-form">
                    <div class="form-group">
                        <label for="username">Nama Lengkap</label>
                        <input type="text" id="username" value="<?php echo htmlspecialchars($username); ?>" readonly>
                    </div>

                    <div class="form-group">
                        <label for="email">Email</label>
                        <input type="email" id="email" value="<?php echo htmlspecialchars($email); ?>" readonly>
                    </div>

                    <div class="form-group">
                        <label for="user_type">Tipe Pengguna</label>
                        <input type="text" id="user_type" value="<?php echo htmlspecialchars($user_type); ?>" readonly>
                    </div>

                    <div class="form-group">
                        <label for="new_password">Kata Sandi Baru</label>
                        <div class="password-input-container">
                            <input type="password" id="new_password" placeholder="Masukkan kata sandi baru">
                            <i class="fas fa-eye password-toggle" onclick="togglePassword('new_password', this)"></i>
                        </div>
                    </div>

                    <div class="form-group">
                        <label for="confirm_password">Konfirmasi Kata Sandi</label>
                        <div class="password-input-container">
                            <input type="password" id="confirm_password" placeholder="Konfirmasi kata sandi baru">
                            <i class="fas fa-eye password-toggle" onclick="togglePassword('confirm_password', this)"></i>
                        </div>
                    </div>

                    <div class="form-actions">
                        <button type="button" class="btn btn-primary" onclick="updatePassword()">
                            <i class="fas fa-key"></i> Ubah Kata Sandi
                        </button>
                        <a href="profile.php" class="btn btn-secondary">
                            <i class="fas fa-arrow-left"></i> Kembali ke Profil
                        </a>
                    </div>
                </form>
            </div>
        </div>
    </section>

    <script>
        function updatePassword() {
            const newPassword = document.getElementById('new_password').value;
            const confirmPassword = document.getElementById('confirm_password').value;

            if (!newPassword) {
                showAlert('error', 'Mohon masukkan kata sandi baru');
                return;
            }

            if (newPassword.length < 6) {
                showAlert('error', 'Kata sandi minimal 6 karakter');
                return;
            }

            if (newPassword !== confirmPassword) {
                showAlert('error', 'Konfirmasi kata sandi tidak cocok');
                return;
            }

            // Here you would typically send the request to update password
            showAlert('success', 'Kata sandi berhasil diubah!');
        }
        
        function togglePassword(id, icon) {
            const input = document.getElementById(id);
            if (input.type === 'password') {
                input.type = 'text';
                icon.classList.remove('fa-eye');
                icon.classList.add('fa-eye-slash');
            } else {
                input.type = 'password';
                icon.classList.remove('fa-eye-slash');
                icon.classList.add('fa-eye');
            }
        }
        
        function showAlert(type, message) {
            const alert = document.createElement('div');
            alert.className = `alert alert-${type}`;
            alert.innerHTML = `
                <i class="fas ${type === 'success' ? 'fa-check-circle' : 'fa-exclamation-circle'}"></i>
                ${message}
            `;
            
            document.body.appendChild(alert);
            
            setTimeout(() => {
                alert.classList.add('show');
            }, 10);
            
            setTimeout(() => {
                alert.classList.remove('show');
                setTimeout(() => {
                    document.body.removeChild(alert);
                }, 300);
            }, 3000);
        }
    </script>
    
    <style>
        .alert {
            position: fixed;
            top: 20px;
            right: 20px;
            padding: 1rem 1.5rem;
            border-radius: 8px;
            display: flex;
            align-items: center;
            gap: 0.75rem;
            font-weight: 500;
            opacity: 0;
            transform: translateY(-20px);
            transition: all 0.3s ease;
            z-index: 1000;
            box-shadow: 0 10px 15px -3px rgba(0,0,0,0.1);
        }
        
        .alert.show {
            opacity: 1;
            transform: translateY(0);
        }
        
        .alert-success {
            background: #ecfdf5;
            color: #065f46;
            border-left: 4px solid #10b981;
        }
        
        .alert-error {
            background: #fef2f2;
            color: #991b1b;
            border-left: 4px solid #ef4444;
        }
    </style>
</body>
</html>