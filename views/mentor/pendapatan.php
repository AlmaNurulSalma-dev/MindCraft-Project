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

// Get data for this page
$revenueData = $controller->getRevenuePageData($mentorId);
$recentEarnings = $controller->getRecentEarnings($mentorId, 5);

function formatRupiah($number) {
    return 'Rp ' . number_format($number, 0, ',', '.');
}
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Pendapatan - Mentor Dashboard</title>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="/MindCraft-Project/assets/css/mentor_dashboard.css">
    <link rel="stylesheet" href="/MindCraft-Project/assets/css/mentor_pendapatan.css">
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
                <li><a href="/MindCraft-Project/views/mentor/kursus-saya.php">Kursus Saya</a></li>
                <li><a href="/MindCraft-Project/views/mentor/buat-kursus-baru.php">Buat Kursus Baru</a></li>
                <li><a href="/MindCraft-Project/views/mentor/pendapatan.php" class="active">Pendapatan</a></li>
                <li><a href="/MindCraft-Project/views/mentor/analitik.php">Analitik</a></li>
                <li><a href="/MindCraft-Project/views/mentor/pengaturan.php">Pengaturan</a></li>
                <li><a href="/MindCraft-Project/views/landingpage/logout.php">Logout</a></li>
            </ul>
        </aside>

        <main class="main-content">
            <div class="content-header">
                <h1>Pendapatan</h1>
            </div>
            <div class="content-body">

                <div class="stats-grid">
                    <div class="stat-card fade-in-up">
                        <div class="stat-title">Saldo Tersedia</div>
                        <div class="stat-number"><?php echo formatRupiah($revenueData['available_balance']); ?></div>
                        <div class="stat-label">Dapat ditarik</div>
                        <div class="stat-badge">▲ AKTIF</div>
                    </div>
                    
                    <div class="stat-card fade-in-up" style="animation-delay: 0.1s;">
                        <div class="stat-title">Total Pendapatan</div>
                        <div class="stat-number"><?php echo formatRupiah($revenueData['total_revenue']); ?></div>
                        <div class="stat-label">Pendapatan Kotor</div>
                        <div class="stat-badge" style="background-color: #10B981;">▲ NAIK</div>
                    </div>
                    
                    <div class="stat-card fade-in-up" style="animation-delay: 0.2s;">
                        <div class="stat-title">Total Penarikan</div>
                        <div class="stat-number"><?php echo formatRupiah($revenueData['total_withdrawals']); ?></div>
                        <div class="stat-label">Dana Ditarik</div>
                        <div class="stat-badge" style="background-color: #6B7280;">▼ KELUAR</div>
                    </div>
                </div>

                <div class="transaction-history-card fade-in-up" style="animation-delay: 0.3s;">
                    <h3 class="card-title">Riwayat Pendapatan Terbaru</h3>
                    <div class="transaction-table-wrapper">
                        <table class="transaction-table">
                            <thead>
                                <tr>
                                    <th>Tanggal</th>
                                    <th>Deskripsi</th>
                                    <th style="text-align: right;">Jumlah</th>
                                    <th style="text-align: center;">Aksi</th> 
                                </tr>
                            </thead>
                            <tbody>
                                <?php if (empty($recentEarnings)): ?>
                                    <tr>
                                        <td colspan="4" style="text-align: center; padding: 2rem;">Belum ada riwayat pendapatan.</td>
                                    </tr>
                                <?php else: ?>
                                    <?php foreach ($recentEarnings as $earning): ?>
                                        <tr>
                                            <td><?php echo date('d M Y', strtotime($earning['created_at'])); ?></td>
                                            <td>
                                                Penjualan Kursus: 
                                                <span class="description"><?php echo htmlspecialchars($earning['course_title']); ?></span>
                                            </td>
                                            <td class="amount positive" style="text-align: right;">
                                                + <?php echo formatRupiah($earning['net_amount']); ?>
                                            </td>
                                            <td style="text-align: center;">
                                                <a href="/MindCraft-Project/views/mentor/pendapatan-detail.php?id=<?php echo $earning['transaction_id']; ?>" class="btn-detail">
                                                    Lihat Detail
                                                </a>
                                            </td>
                                        </tr>
                                    <?php endforeach; ?>
                                <?php endif; ?>
                            </tbody>
                        </table>
                    </div>
                </div>

            </div> 
        </main> 
    </div> 

    <script src="/MindCraft-Project/assets/js/mentor_dashboard.js"></script>
</body>
</html>