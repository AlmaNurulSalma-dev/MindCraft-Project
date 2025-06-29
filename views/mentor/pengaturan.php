<?php
if (session_status() === PHP_SESSION_NONE) { session_start(); }

if (!isset($_SESSION['user_id']) || !isset($_SESSION['user_type']) || $_SESSION['user_type'] !== 'Mentor') {
    header("Location: /MindCraft-Project/views/landingpage/login.php");
    exit();
}

require_once __DIR__ . '/../../config/Database.php';
require_once __DIR__ . '/../../model/UserModel.php';

$database = new Database();
$db = $database->connect();
$userModel = new UserModel($db);
$mentorId = $_SESSION['user_id'];

$user = $userModel->getMentorById($mentorId);
$profile = $userModel->getMentorProfile($mentorId);
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Pengaturan Akun - Mentor Dashboard</title>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="/MindCraft-Project/assets/css/mentor_dashboard.css">
    <link rel="stylesheet" href="/MindCraft-Project/assets/css/mentor_pengaturan.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.1.1/css/all.min.css">
</head>
<body>
    <header class="top-header">
        <div class="logo">MindCraft</div>
        <button class="mobile-menu-toggle" id="mobileMenuToggle">☰</button>
    </header>

    <div class="dashboard-container">
        <aside class="sidebar" id="sidebar">
            <ul class="sidebar-menu">
                <li><a href="dashboard.php">Dashboard</a></li>
                <li><a href="kursus-saya.php">Kursus Saya</a></li>
                <li><a href="buat-kursus-baru.php">Buat Kursus Baru</a></li>
                <li><a href="pendapatan.php">Pendapatan</a></li>
                <li><a href="analitik.php">Analitik</a></li>
                <li><a href="pengaturan.php" class="active">Pengaturan</a></li>
                <li><a href="../landingpage/logout.php">Logout</a></li>
            </ul>
        </aside>

        <main class="main-content">
            <div class="content-header">
                <h1>Pengaturan Akun</h1>
                <p>Kelola informasi profil, akun, dan keamanan Anda.</p>
            </div>
            <div class="content-body">
                <div class="settings-grid">
                    <div class="settings-card">
                        <div class="settings-card-header"><h3>Informasi Profesional</h3></div>
                        <div class="settings-card-body">
                            <form action="/MindCraft-Project/controller/ProfileController.php?action=update_settings" method="POST">
                                <input type="hidden" name="action" value="update_profile">
                                <div class="form-group">
                                    <label for="full_name">Nama Lengkap</label>
                                    <input type="text" id="full_name" name="full_name" value="<?php echo htmlspecialchars($profile['full_name']); ?>" placeholder="Masukkan nama lengkap Anda">
                                </div>
                                <div class="form-group">
                                    <label for="specialization">Spesialisasi</label>
                                    <input type="text" id="specialization" name="specialization" value="<?php echo htmlspecialchars($profile['specialization']); ?>" placeholder="Contoh: Web Developer, UI/UX Designer">
                                </div>
                                 <div class="form-group">
                                    <label for="experience_years">Pengalaman (Tahun)</label>
                                    <input type="number" id="experience_years" name="experience_years" value="<?php echo htmlspecialchars($profile['experience_years']); ?>" min="0" placeholder="0">
                                </div>
                                <div class="form-group">
                                    <label for="bio">Bio Singkat</label>
                                    <textarea id="bio" name="bio" placeholder="Ceritakan sedikit tentang keahlian Anda..."><?php echo htmlspecialchars($profile['bio']); ?></textarea>
                                </div>
                                <hr style="border: none; border-top: 1px solid #E5E7EB; margin: 1.5rem 0;">
                                <div class="form-group">
                                    <label for="phone">Nomor Telepon</label>
                                    <input type="text" id="phone" name="phone" value="<?php echo htmlspecialchars($profile['phone']); ?>" placeholder="08...">
                                </div>
                                <div class="form-group">
                                    <label for="website">Website atau Portofolio</label>
                                    <div class="input-with-icon">
                                        <i class="fas fa-globe input-icon"></i>
                                        <input type="url" id="website" name="website" value="<?php echo htmlspecialchars($profile['website']); ?>" placeholder="https://domain-anda.com">
                                    </div>
                                </div>
                                 <div class="form-group">
                                    <label for="linkedin">Profil LinkedIn</label>
                                     <div class="input-with-icon">
                                        <i class="fab fa-linkedin input-icon"></i>
                                        <input type="url" id="linkedin" name="linkedin" value="<?php echo htmlspecialchars($profile['linkedin']); ?>" placeholder="https://linkedin.com/in/username">
                                    </div>
                                </div>
                                <div class="form-group">
                                    <label for="instagram">Instagram</label>
                                     <div class="input-with-icon">
                                        <i class="fab fa-instagram input-icon"></i>
                                        <input type="text" id="instagram" name="instagram" value="<?php echo htmlspecialchars($profile['instagram']); ?>" placeholder="username_ig">
                                    </div>
                                </div>
                                <div class="form-group">
                                    <label for="youtube">Channel YouTube</label>
                                    <div class="input-with-icon">
                                        <i class="fab fa-youtube input-icon"></i>
                                        <input type="url" id="youtube" name="youtube" value="<?php echo htmlspecialchars($profile['youtube']); ?>" placeholder="https://youtube.com/channel/...">
                                    </div>
                                </div>
                                <button type="submit" class="btn-save">Simpan Perubahan Profil</button>
                            </form>
                        </div>
                    </div>

                    <div class="settings-card">
                        <div class="settings-card-header"><h3>Keamanan Akun</h3></div>
                        <div class="settings-card-body">
                             <div class="form-group">
                                <label for="username">Username</label>
                                <input type="text" id="username" name="username" value="<?php echo htmlspecialchars($user['username']); ?>" disabled>
                                <small style="font-size: 0.8rem; color: #6B7280;">Username tidak dapat diubah.</small>
                            </div>
                            <div class="form-group">
                                <label for="email">Email</label>
                                <input type="text" id="email" name="email" value="<?php echo htmlspecialchars($user['email']); ?>" disabled>
                            </div>
                            <hr style="border: none; border-top: 1px solid #E5E7EB; margin: 1.5rem 0;">
                            <form action="/MindCraft-Project/controller/ProfileController.php?action=update_settings" method="POST">
                                <input type="hidden" name="action" value="update_password">
                                <div class="form-group">
                                    <label for="current_password">Password Saat Ini</label>
                                    <input type="password" id="current_password" name="current_password" required>
                                </div>
                                <div class="form-group">
                                    <label for="new_password">Password Baru</label>
                                    <input type="password" id="new_password" name="new_password" required>
                                </div>
                                <div class="form-group">
                                    <label for="confirm_password">Konfirmasi Password Baru</label>
                                    <input type="password" id="confirm_password" name="confirm_password" required>
                                </div>
                                <button type="submit" class="btn-save">Ubah Password</button>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </main> 
    </div>
    <script src="/MindCraft-Project/assets/js/mentor_dashboard.js"></script>
</body>
</html>