<?php
session_start();
if (!isset($_SESSION['user_id']) || $_SESSION['user_type'] !== 'Mentor') {
    header("Location: /MindCraft-Project/views/landingpage/login.php");
    exit();
}

require_once __DIR__ . '/../../config/Database.php';
require_once __DIR__ . '/../../controller/MentorController.php';

$database = new Database();
$db = $database->connect();
$mentorController = new MentorController($database);

$mentorId = $_SESSION['user_id'];
$mentor = $mentorController->getMentorData($mentorId);

// Mengambil daftar kategori dari database untuk dropdown
$categories = [];
try {
    $stmt = $db->query("SELECT name FROM course_categories WHERE is_active = 1 ORDER BY name ASC");
    $categories = $stmt->fetchAll(PDO::FETCH_COLUMN);
} catch (PDOException $e) {
    // handle error
}

?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Buat Kursus Baru - Mentor Dashboard</title>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="/MindCraft-Project/assets/css/mentor_dashboard.css">
    <link rel="stylesheet" href="/MindCraft-Project/assets/css/mentor_buat-kursus-baru.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
</head>
<body>
    <header class="top-header">
        <div class="logo">MindCraft</div>
        <button class="mobile-menu-toggle" id="mobileMenuToggle">☰</button>
    </header>

    <div class="dashboard-container">
        <aside class="sidebar" id="sidebar">
            <div class="user-profile">
                <img src="<?php echo htmlspecialchars($mentor['profile_picture'] ?? '/MindCraft-Project/assets/images/profile/default.png'); ?>" alt="Avatar" class="avatar">
                <span class="username"><?php echo htmlspecialchars($mentor['username'] ?? 'Mentor'); ?></span>
            </div>
            <ul class="sidebar-menu">
                <li><a href="dashboard.php">Dashboard</a></li>
                <li><a href="kursus-saya.php" >Kursus Saya</a></li>
                <li><a href="buat-kursus-baru.php" class="active">Buat Kursus Baru</a></li>
                <li><a href="pendapatan.php">Pendapatan</a></li>
                <li><a href="analitik.php">Analitik</a></li>
                <li><a href="pengaturan.php">Pengaturan</a></li>
                <li><a href="../landingpage/logout.php">Logout</a></li>
            </ul>
        </aside>

        <main class="main-content">
            <div class="content-header">
                <h1>Buat Kursus Baru</h1>
                <p>Isi detail kursus Anda di bawah ini.</p>
            </div>
            <div class="content-body">
                <form class="course-form" action="/MindCraft-Project/controller/course.php?action=create" method="POST" enctype="multipart/form-data">
                    <div class="form-section">
                        <div class="form-group">
                            <label for="title">Judul Kursus <span class="required">*</span></label>
                            <input type="text" id="title" name="title" placeholder="Contoh: Belajar PHP Dasar untuk Pemula" required>
                        </div>
                        <div class="form-group">
                            <label for="description">Deskripsi Kursus <span class="required">*</span></label>
                            <textarea id="description" name="description" rows="6" placeholder="Jelaskan secara singkat tentang kursus ini..." required></textarea>
                        </div>
                    </div>
                    
                    <div class="form-grid">
                        <div class="form-group">
                            <label for="category">Kategori <span class="required">*</span></label>
                            <select id="category" name="category" required>
                                <option value="" disabled selected>Pilih Kategori</option>
                                <?php foreach ($categories as $category): ?>
                                    <option value="<?php echo htmlspecialchars($category); ?>"><?php echo htmlspecialchars($category); ?></option>
                                <?php endforeach; ?>
                            </select>
                        </div>
                        <div class="form-group">
                            <label for="difficulty">Tingkat Kesulitan</label>
                            <select id="difficulty" name="difficulty">
                                <option value="Pemula">Pemula</option>
                                <option value="Menengah">Menengah</option>
                                <option value="Mahir">Mahir</option>
                            </select>
                        </div>
                        <div class="form-group">
                            <label for="price">Harga (Rp)</label>
                            <input type="number" id="price" name="price" placeholder="Contoh: 150000" min="0" value="0">
                        </div>
                    </div>

                    <div class="form-section">
                        <div class="form-group">
                            <label for="cover_image">Gambar Sampul (Cover) <span class="required">*</span></label>
                            <input type="file" id="cover_image" name="cover_image" accept="image/png, image/jpeg, image/webp" required>
                            <small>Rekomendasi ukuran: 1280x720px. Format: JPG, PNG, WEBP.</small>
                        </div>
                    </div>

                    <div class="form-actions">
                        <button type="submit" name="draft" class="btn btn-secondary">Simpan sebagai Draft</button>
                        <button type="submit" name="publish" class="btn btn-primary">Publikasikan</button>
                    </div>
                </form>
            </div> 
        </main> 
    </div> 

    <script src="/MindCraft-Project/assets/js/mentor_dashboard.js"></script>
    </body>
</html>