<?php
if (session_status() === PHP_SESSION_NONE) { session_start(); }

// Check if user is logged in and is a mentor
if (!isset($_SESSION['user_id']) || !isset($_SESSION['user_type']) || $_SESSION['user_type'] !== 'Mentor') {
    header("Location: /MindCraft-Project/views/landingpage/login.php");
    exit();
}

// Include database connection dan controller
require_once __DIR__ . '/../../config/Database.php';
require_once __DIR__ . '/../../controller/MentorController.php';

// Initialize database dan controller
$database = new Database();
$controller = new MentorController($database);
$mentorId = $_SESSION['user_id'];
    
// Mengambil data dari method yang sama dengan dashboard
$dashboardData = $controller->getDashboardData($mentorId);
$mentorData = $controller->getMentorData($mentorId);
$mentorName = $mentorData['username'] ?? 'Mentor';

// Ekstrak semua data yang dibutuhkan, dengan nilai default jika tidak ada
$totalCourses = $dashboardData['totalCourses'] ?? 0;
$totalMentees = $dashboardData['totalMentees'] ?? 0;
$totalEarnings = $dashboardData['totalEarnings'] ?? 0;
// Data-data lain dari dashboard bisa ditambahkan di sini jika sudah diimplementasikan di controller
$averageRating = $dashboardData['averageRating'] ?? 0;
$totalReviews = $dashboardData['totalReviews'] ?? 0;
$completionRate = $dashboardData['completionRate'] ?? 0;
$videoHours = $dashboardData['videoHours'] ?? 0;
$moduleCount = $dashboardData['moduleCount'] ?? 0;
$monthlyRegistrations = $dashboardData['monthlyChartData'] ?? []; // Menggunakan data dari controller
$recentActivities = $dashboardData['recentActivities'] ?? []; // Placeholder

function formatRupiah($number) {
    if ($number >= 1000000) {
        return 'Rp ' . number_format($number / 1000000, 1) . ' jt';
    } elseif ($number >= 1000) {
        return 'Rp ' . number_format($number / 1000, 0) . 'k';
    }
    return 'Rp ' . number_format($number);
}
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Analitik - Mentor Dashboard</title>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="/MindCraft-Project/assets/css/mentor_dashboard.css">
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
                <li><a href="/MindCraft-Project/views/mentor/kursus-saya.php">Kursus Saya</a></li>
                <li><a href="/MindCraft-Project/views/mentor/buat-kursus-baru.php">Buat Kursus Baru</a></li>
                <li><a href="/MindCraft-Project/views/mentor/pendapatan.php">Pendapatan</a></li>
                <li><a href="/MindCraft-Project/views/mentor/analitik.php" class="active">Analitik</a></li>
                <li><a href="/MindCraft-Project/views/mentor/pengaturan.php">Pengaturan</a></li>
                <li><a href="/MindCraft-Project/views/landingpage/logout.php">Logout</a></li>
            </ul>
        </aside>

        <main class="main-content">
            <div class="content-header">
                <h1>Analitik Kinerja</h1>
                <p>Selamat datang di pusat data kinerja Anda, <?php echo htmlspecialchars($mentorName); ?>!</p>
            </div>
            <div class="content-body">
                
                <div class="stats-grid">
                    <div class="stat-card fade-in-up">
                        <div class="stat-title">Total Kursus</div>
                        <div class="stat-number"><?php echo number_format($totalCourses); ?></div>
                        <div class="stat-label">Kursus Aktif</div>
                    </div>
                    
                    <div class="stat-card fade-in-up" style="animation-delay: 0.1s;">
                        <div class="stat-title">Total Mentee</div>
                        <div class="stat-number"><?php echo number_format($totalMentees); ?></div>
                        <div class="stat-label">Siswa Terdaftar</div>
                    </div>
                    
                    <div class="stat-card fade-in-up" style="animation-delay: 0.2s;">
                        <div class="stat-title">Rating Rata-rata</div>
                        <div class="stat-number"><?php echo number_format($averageRating, 1); ?></div>
                        <div class="stat-label">Dari <?php echo number_format($totalReviews); ?> Ulasan</div>
                    </div>
                </div>

                <div class="summary-bar fade-in-up" style="animation-delay: 0.3s;">
                    <div class="summary-item">
                        <div class="summary-title">Penyelesaian</div>
                        <div class="summary-value"><?php echo $completionRate; ?>%</div>
                    </div>
                    <div class="summary-item">
                        <div class="summary-title">Konten Video</div>
                        <div class="summary-value"><?php echo $videoHours; ?> Jam</div>
                    </div>
                    <div class="summary-item">
                        <div class="summary-title">Modul</div>
                        <div class="summary-value"><?php echo number_format($moduleCount); ?></div>
                    </div>
                    <div class="summary-item">
                        <div class="summary-title">Total Ulasan</div>
                        <div class="summary-value"><?php echo number_format($totalReviews); ?></div>
                    </div>
                    <div class="summary-item">
                        <div class="summary-title">Total Pendapatan</div>
                        <div class="summary-value"><?php echo formatRupiah($totalEarnings); ?></div>
                    </div>
                </div>

                <div class="bottom-grid">
                     <div class="chart-card fade-in-up" style="animation-delay: 0.4s;">
                        <h3 class="card-title">Grafik Pendapatan (12 Bulan Terakhir)</h3>
                        <div class="chart-container">
                             <canvas id="revenueChart"></canvas>
                        </div>
                    </div>

                    <div class="activity-card fade-in-up" style="animation-delay: 0.5s;">
                        <h3 class="card-title">Aktivitas Terbaru</h3>
                        <div class="activity-list">
                            <?php if (!empty($recentActivities)): ?>
                                <?php else: ?>
                                <div class="activity-item">
                                    <div class="activity-content">
                                        <div class="activity-text">Belum ada aktivitas terbaru</div>
                                    </div>
                                </div>
                            <?php endif; ?>
                        </div>
                    </div>
                </div>

            </div> 
        </main> 
    </div> 

    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <script src="/MindCraft-Project/assets/js/mentor_dashboard.js"></script>
    <script>
        document.addEventListener('DOMContentLoaded', function () {
            // Mengambil data JSON dari PHP
            const monthlyData = <?php echo json_encode($monthlyRegistrations); ?>;

            const labels = monthlyData.map(item => {
                const date = new Date(item.month + '-01');
                return date.toLocaleString('id-ID', { month: 'short' });
            });
            const dataValues = monthlyData.map(item => item.monthly_revenue);

            const ctx = document.getElementById('revenueChart').getContext('2d');
            new Chart(ctx, {
                type: 'bar', // Menggunakan bar chart untuk variasi
                data: {
                    labels: labels,
                    datasets: [{
                        label: 'Pendapatan Bulanan',
                        data: dataValues,
                        backgroundColor: 'rgba(79, 70, 229, 0.8)',
                        borderColor: 'rgba(79, 70, 229, 1)',
                        borderWidth: 1,
                        borderRadius: 5,
                    }]
                },
                options: {
                    responsive: true,
                    maintainAspectRatio: false,
                    scales: {
                        y: {
                            beginAtZero: true,
                            ticks: {
                                callback: function(value) { return 'Rp ' + (value/1000) + 'k'; }
                            }
                        }
                    },
                    plugins: {
                        legend: { display: false }
                    }
                }
            });
        });
    </script>
</body>
</html>