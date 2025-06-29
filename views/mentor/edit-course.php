<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

if (!isset($_SESSION['user_id']) || !isset($_SESSION['user_type']) || $_SESSION['user_type'] !== 'Mentor') {
    header("Location: /MindCraft-Project/views/landingpage/login.php");
    exit();
}

// Validasi ID kursus dari URL
if (!isset($_GET['id']) || !filter_var($_GET['id'], FILTER_VALIDATE_INT)) {
    header("Location: /MindCraft-Project/views/mentor/kursus-saya.php?error=invalid_id");
    exit();
}
$course_id_to_edit = $_GET['id'];

require_once __DIR__ . '/../../config/Database.php';
require_once __DIR__ . '/../../model/CourseModel.php';
require_once __DIR__ . '/../../model/UserModel.php';


$database = new Database();
$db = $database->connect();
$courseModel = new CourseModel($db);
$userModel = new UserModel($db);


// Ambil data kursus yang akan diedit
$course = $courseModel->getCourseById($course_id_to_edit);
$mentor = $userModel->getMentorById($_SESSION['user_id']);


// Security Check: pastikan kursus ada & milik mentor yang login
if (!$course || $course['mentor_id'] != $_SESSION['user_id']) {
    header("Location: /MindCraft-Project/views/mentor/kursus-saya.php?error=not_found_or_unauthorized");
    exit();
}

// Mengambil daftar kategori dari database untuk dropdown
$categories = $db->query("SELECT name FROM course_categories WHERE is_active = 1 ORDER BY name ASC")->fetchAll(PDO::FETCH_COLUMN);
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Edit Kursus - <?php echo htmlspecialchars($course['title']); ?></title>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="/MindCraft-Project/assets/css/mentor_dashboard.css">
    <link rel="stylesheet" href="/MindCraft-Project/assets/css/mentor_buat-kursus-baru.css">
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
                <li><a href="/MindCraft-Project/views/mentor/dashboard.php">Dashboard</a></li>
                <li><a href="/MindCraft-Project/views/mentor/kursus-saya.php" class="active">Kursus Saya</a></li>
                <li><a href="/MindCraft-Project/views/mentor/buat-kursus-baru.php">Buat Kursus Baru</a></li>
                <li><a href="/MindCraft-Project/views/mentor/pendapatan.php">Pendapatan</a></li>
                <li><a href="/MindCraft-Project/views/mentor/analitik.php">Analitik</a></li>
                <li><a href="/MindCraft-Project/views/mentor/pengaturan.php">Pengaturan</a></li>
                <li><a href="/MindCraft-Project/views/landingpage/logout.php">Logout</a></li>
            </ul>
        </aside>
        
        <main class="main-content">
            <div class="content-header">
                <h1>Edit Kursus</h1>
                <p>Perbarui detail untuk kursus "<?php echo htmlspecialchars($course['title']); ?>"</p>
            </div>
            <div class="content-body">
                <form class="course-form" action="/MindCraft-Project/controller/course.php?action=update" method="POST" enctype="multipart/form-data">
                    <input type="hidden" name="course_id" value="<?php echo $course['id']; ?>">
                    
                    <div class="form-section">
                        <div class="form-group">
                            <label for="title">Judul Kursus</label>
                            <input type="text" id="title" name="title" value="<?php echo htmlspecialchars($course['title']); ?>" required>
                        </div>
                        <div class="form-group">
                            <label for="description">Deskripsi Kursus</label>
                            <textarea id="description" name="description" rows="6" required><?php echo htmlspecialchars($course['description']); ?></textarea>
                        </div>
                    </div>
                    
                    <div class="form-grid">
                        <div class="form-group">
                            <label for="category">Kategori</label>
                            <select id="category" name="category" required>
                                <?php foreach ($categories as $category): ?>
                                    <option value="<?php echo htmlspecialchars($category); ?>" <?php echo ($course['category'] == $category) ? 'selected' : ''; ?>>
                                        <?php echo htmlspecialchars($category); ?>
                                    </option>
                                <?php endforeach; ?>
                            </select>
                        </div>
                        <div class="form-group">
                            <label for="difficulty">Tingkat Kesulitan</label>
                            <select id="difficulty" name="difficulty">
                                <option value="Pemula" <?php echo ($course['difficulty'] == 'Pemula') ? 'selected' : ''; ?>>Pemula</option>
                                <option value="Menengah" <?php echo ($course['difficulty'] == 'Menengah') ? 'selected' : ''; ?>>Menengah</option>
                                <option value="Mahir" <?php echo ($course['difficulty'] == 'Mahir') ? 'selected' : ''; ?>>Mahir</option>
                            </select>
                        </div>
                        <div class="form-group">
                            <label for="price">Harga (Rp)</label>
                            <input type="number" id="price" name="price" value="<?php echo htmlspecialchars($course['price']); ?>" min="0">
                        </div>
                    </div>

                    <div class="form-section">
                        <div class="form-group">
                            <label for="cover_image">Ganti Gambar Sampul</label>
                            <input type="file" id="cover_image" name="cover_image" accept="image/png, image/jpeg, image/webp">
                            <small>Kosongkan jika tidak ingin mengubah gambar. Gambar saat ini:</small>
                            <img src="<?php echo htmlspecialchars($course['cover_image']); ?>" alt="Cover saat ini" style="max-width: 200px; margin-top: 10px; border-radius: 8px;">
                        </div>
                    </div>

                    <div class="form-actions">
                        <button type="submit" name="draft" class="btn btn-secondary">Simpan sebagai Draft</button>
                        <button type="submit" name="publish" class="btn btn-primary">Simpan dan Publikasikan</button>
                    </div>
                </form>
            </div> 
        </main> 
    </div> 
    <script src="/MindCraft-Project/assets/js/mentor_dashboard.js"></script>
</body>
</html>