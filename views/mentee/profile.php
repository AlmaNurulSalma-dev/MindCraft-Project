<?php
session_start();
require_once 'config.php';

// Check if user is logged in and is a mentee
if (!isset($_SESSION['user_id'])) {
    header("Location: ../landingpage/landingpage.php");
    exit();
}

$user_id = $_SESSION['user_id'];
$username = $_SESSION['username'];
$user_type = $_SESSION['user_type'];
$email = $_SESSION['email']; 

// Ambil data statistik pengguna
function getUserStats($pdo, $user_id) {
    $stats = [
        'courses_taken' => 0,
        'courses_completed' => 0,
        'certificates' => 0
    ];
    
    // Jumlah kursus yang diikuti
    $stmt = $pdo->prepare("SELECT COUNT(*) FROM enrollments WHERE student_id = ?");
    $stmt->execute([$user_id]);
    $stats['courses_taken'] = $stmt->fetchColumn();
    
    // Jumlah kursus yang selesai
    $stmt = $pdo->prepare("SELECT COUNT(*) FROM enrollments WHERE student_id = ? AND progress_percentage = 100");
    $stmt->execute([$user_id]);
    $stats['courses_completed'] = $stmt->fetchColumn();
    
    // Jumlah sertifikat
    $stmt = $pdo->prepare("SELECT COUNT(*) FROM enrollments WHERE student_id = ? AND certificate_issued = 1");
    $stmt->execute([$user_id]);
    $stats['certificates'] = $stmt->fetchColumn();
    
    return $stats;
}

$userStats = getUserStats($pdo, $user_id);
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>MindCraft - Profil Mentee</title>
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
        
        .profile-section {
            padding: 2.5rem 0;
        }
        
        .profile-section h2 {
            font-size: 2rem;
            font-weight: 600;
            margin-bottom: 2rem;
            color: var(--dark);
        }
        
        .profile-content {
            display: grid;
            grid-template-columns: 280px 1fr;
            gap: 2.5rem;
            background: white;
            padding: 2.5rem;
            border-radius: 16px;
            box-shadow: 0 10px 15px -3px rgba(0,0,0,0.1);
        }
        
        .profile-avatar {
            display: flex;
            flex-direction: column;
            align-items: center;
            gap: 1.5rem;
        }
        
        .avatar-large {
            width: 160px;
            height: 160px;
            border-radius: 50%;
            background: linear-gradient(135deg, var(--primary), #8b5cf6);
            color: white;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 4rem;
            font-weight: bold;
            box-shadow: 0 10px 15px -3px rgba(99,102,241,0.3);
        }
        
        .profile-info h3 {
            font-size: 1.8rem;
            margin-bottom: 0.5rem;
            color: var(--dark);
        }
        
        .user-type {
            background: var(--primary);
            color: white;
            padding: 0.35rem 1rem;
            border-radius: 20px;
            display: inline-block;
            margin-bottom: 1rem;
            font-size: 0.9rem;
            font-weight: 500;
        }
        
        .email {
            color: var(--gray);
            margin-bottom: 2rem;
            display: flex;
            align-items: center;
            gap: 0.5rem;
        }
        
        .email i {
            font-size: 1.1rem;
        }
        
        .profile-stats {
            display: grid;
            grid-template-columns: repeat(3, 1fr);
            gap: 1.5rem;
            margin-bottom: 2.5rem;
        }
        
        .stat-item {
            background: #f8fafc;
            padding: 1.5rem;
            border-radius: 12px;
            text-align: center;
            transition: all 0.3s ease;
            border: 1px solid #e2e8f0;
        }
        
        .stat-item:hover {
            transform: translateY(-5px);
            box-shadow: 0 10px 15px -3px rgba(0,0,0,0.1);
        }
        
        .stat-item strong {
            display: block;
            font-size: 0.95rem;
            color: var(--gray);
            margin-bottom: 0.5rem;
        }
        
        .stat-item span {
            font-size: 1.75rem;
            font-weight: 600;
            color: var(--primary);
            display: block;
        }
        
        .profile-actions {
            display: flex;
            gap: 1rem;
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
        }
        
        .btn-secondary {
            background: white;
            color: var(--primary);
            border: 1px solid var(--primary);
        }
        
        .btn-secondary:hover {
            background: #f1f5ff;
            transform: translateY(-2px);
        }
        
        @media (max-width: 768px) {
            .profile-content {
                grid-template-columns: 1fr;
            }
            
            .profile-stats {
                grid-template-columns: 1fr;
            }
            
            .profile-actions {
                flex-direction: column;
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
                        <li><a href="#" class="active">Profil</a></li>
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

    <section class="profile-section">
        <div class="container">
            <h2>Profil Saya</h2>
            <div class="profile-content">
                <div class="profile-avatar">
                    <div class="avatar-large">
                        <?php echo strtoupper(substr($username, 0, 1)); ?>
                    </div>
                    <div class="profile-actions">
                        <a href="settings.php" class="btn btn-primary">
                            <i class="fas fa-user-edit"></i> Edit Profil
                        </a>
                    </div>
                </div>
                <div class="profile-info">
                    <h3><?php echo htmlspecialchars($username); ?></h3>
                    <p class="user-type"><?php echo htmlspecialchars($user_type); ?></p>
                    <p class="email">
                        <i class="fas fa-envelope"></i> <?php echo htmlspecialchars($email); ?>
                    </p>
                    
                    <div class="profile-stats">
                        <div class="stat-item">
                            <strong>Kursus Diikuti</strong>
                            <span><?= $userStats['courses_taken'] ?></span>
                        </div>
                        <div class="stat-item">
                            <strong>Kursus Selesai</strong>
                            <span><?= $userStats['courses_completed'] ?></span>
                        </div>
                        <div class="stat-item">
                            <strong>Sertifikat</strong>
                            <span><?= $userStats['certificates'] ?></span>
                        </div>
                    </div>
                                        
                    <div class="profile-actions">
                        <a href="../landingpage/logout.php" class="btn btn-secondary">
                            <i class="fas fa-sign-out-alt"></i> Logout
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </section>
</body>
</html>