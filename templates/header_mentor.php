<?php
// Menyiapkan variabel dengan nilai default untuk menghindari error
$username = isset($mentor['username']) ? htmlspecialchars($mentor['username']) : 'Mentor';
$profile_picture_path = isset($mentor['profile_picture']) && !empty($mentor['profile_picture']) ? '/MindCraft-Project/assets/images/profile/' . htmlspecialchars($mentor['profile_picture']) : '/MindCraft-Project/assets/images/profile/default.png';
$page_title = $page_title ?? 'Mentor Dashboard';
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?php echo htmlspecialchars($page_title); ?> - MindCraft</title>
    
    <link rel="stylesheet" href="/MindCraft-Project/assets/css/mentor_dashboard.css">
    
    <?php if (isset($page_css)): ?>
    <link rel="stylesheet" href="/MindCraft-Project/assets/css/<?php echo $page_css; ?>">
    <?php endif; ?>
    
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
</head>
<body>
<div class="sidebar">
    <div class="logo">
        <h2>Mind<span>Craft</span></h2>
    </div>
    <ul class="nav">
        <li><a href="/MindCraft-Project/mentor/dashboard"><i class="fas fa-home"></i> Dashboard</a></li>
        <li><a href="/MindCraft-Project/mentor/kursus-saya"><i class="fas fa-book"></i> Kursus Saya</a></li>
        <li><a href="/MindCraft-Project/mentor/pendapatan"><i class="fas fa-wallet"></i> Pendapatan</a></li>
        <li><a href="/MindCraft-Project/mentor/analitik"><i class="fas fa-chart-line"></i> Analitik</a></li>
        <li><a href="/MindCraft-Project/mentor/pengaturan"><i class="fas fa-cog"></i> Pengaturan</a></li>
        <li><a href="/MindCraft-Project/mentor/logout"><i class="fas fa-sign-out-alt"></i> Logout</a></li>
    </ul>
</div>
<div class="main-content">
    <header>
        <div class="header-title">
            <h3><?php echo htmlspecialchars($page_title); ?></h3>
        </div>
        <div class="user-info">
            <p>Halo, <?php echo $username; ?></p>
            <img src="<?php echo $profile_picture_path; ?>" alt="Avatar" class="avatar">
        </div>
    </header>
    <main>