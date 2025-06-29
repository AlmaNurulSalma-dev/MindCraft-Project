<?php
session_start();
if (!isset($_SESSION['user_id']) || !isset($_SESSION['user_type']) || $_SESSION['user_type'] !== 'Mentor') {
    header("Location: /MindCraft-Project/views/landingpage/login.php");
    exit();
}

require_once __DIR__ . '/../../config/Database.php';
require_once __DIR__ . '/../../controller/MentorController.php';

$database = new Database();
$controller = new MentorController($database);
$mentorId = $_SESSION['user_id'];

$mentor = $controller->getMentorData($mentorId);
$pageData = $controller->getCoursesPageData($mentorId);
$courses = $pageData['courses'];
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Kursus Saya - Mentor Dashboard</title>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="/MindCraft-Project/assets/css/mentor_dashboard.css">
    <link rel="stylesheet" href="/MindCraft-Project/assets/css/mentor_kursus-saya.css">
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
                <h1>Kursus Saya</h1>
            </div>
            <div class="content-body">
                
                <div class="course-grid-container">
                    <?php if (empty($courses)): ?>
                        <div class="empty-state-container">
                            <i class="fas fa-book-open"></i>
                            <p>Anda belum memiliki kursus.</p>
                        </div>
                    <?php else: ?>
                        <?php foreach ($courses as $course): ?>
                            <div class="course-card">
                                <div class="card-thumbnail">
                                    <img src="<?php echo htmlspecialchars($course['thumbnail']); ?>" alt="Thumbnail Kursus">
                                    <span class="card-status status-<?php echo strtolower(htmlspecialchars($course['status'] ?? 'draft')); ?>">
                                        <?php echo htmlspecialchars($course['status'] ?? 'Draft'); ?>
                                    </span>
                                </div>
                                <div class="card-body">
                                    <span class="card-category"><?php echo htmlspecialchars($course['category']); ?></span>
                                    <h3 class="card-title"><?php echo htmlspecialchars($course['course_name']); ?></h3>

                                    <div class="card-details">
                                        <span><i class="fas fa-signal"></i> <?php echo htmlspecialchars($course['difficulty'] ?? 'N/A'); ?></span>
                                        <span><i class="fas fa-tag"></i> Rp <?php echo number_format($course['price'] ?? 0, 0, ',', '.'); ?></span>
                                    </div>

                                    <div class="card-meta">
                                        <div class="meta-item">
                                            <i class="fas fa-users"></i>
                                            <span><?php echo number_format($course['student_count']); ?> Siswa</span>
                                        </div>
                                        <div class="meta-item">
                                            <a href="/MindCraft-Project/views/mentor/edit-course.php?id=<?php echo $course['course_id']; ?>" title="Edit Kursus">
                                                <i class="fas fa-pen-to-square"></i>
                                            </a>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        <?php endforeach; ?>
                    <?php endif; ?>
                </div>

            </div> 
        </main> 
    </div> 

    <script src="/MindCraft-Project/assets/js/mentor_dashboard.js"></script>
</body>
</html>