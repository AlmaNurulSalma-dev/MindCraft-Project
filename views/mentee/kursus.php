<?php
session_start();

// Enable error reporting for debugging
error_reporting(E_ALL);
ini_set('display_errors', 1);

// Check if user is logged in and is a mentee
if (!isset($_SESSION['user_id']) || $_SESSION['user_type'] !== 'Mentee') {
    // Add debugging information
    error_log("Courses.php access denied. Session data: " . print_r($_SESSION, true));
    header("Location: ../landingpage/landingpage.php");
    exit();
}

// Get user information from session
$user_id = $_SESSION['user_id'];
$username = $_SESSION['username'];
$user_type = $_SESSION['user_type'];
?>

<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Kursus - MindCraft Platform E-Lifestyle</title>
    <link rel="stylesheet" href="/MindCraft-Project/assets/css/mentee-dashboard.css">
    <link rel="stylesheet" href="/MindCraft-Project/assets/css/header-mentee.css">
    <link rel="stylesheet" href="/MindCraft-Project/assets/css/mentee-kursus.css">
    <link rel="stylesheet" href="/MindCraft-Project/assets/css/mentee-kursus-tambahan.css">
    <link rel="stylesheet" href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;700&display=swap">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
</head>

<body>
    <!-- Header dengan user menu untuk logged in users -->
    <header class="header">
        <div class="container">
            <div class="header-content">
                <div class="logo">
                    <img src="../../assets/img/20250502_083014.png" alt="MindCraft Logo" id="logo-img">
                    <h1>Mind<span>Craft</span></h1>
                </div>
                <nav class="main-nav">
                    <ul>
                        <li><a href="dashboard.php">Beranda</a></li>
                        <li><a href="kursus.php" class="active">Kursus</a></li>
                        <li><a href="ai_assistant.php">🤖 MindBot</a></li>
                    </ul>
                </nav>
                <div class="header-actions">
                    <!-- User menu for logged in users -->
                    <div class="user-menu">
                        <div class="user-avatar" id="user-avatar">
                            <?php echo strtoupper(substr($username, 0, 1)); ?>
                        </div>
                        <span class="username"><?php echo htmlspecialchars($username); ?></span>
                        <div class="dropdown-menu" id="dropdown-menu">
                            <a href="profile.php">Profil</a>
                            <a href="settings.php">Pengaturan</a>
                            <a href="../landingpage/logout.php">Logout</a>
                        </div>
                    </div>
                    <button class="btn btn-menu" id="menu-toggle">
                        <i class="fas fa-bars"></i>
                    </button>
                </div>
            </div>
        </div>
    </header>

    <!-- Welcome message for logged in user -->
    <div class="welcome-banner" style="background: linear-gradient(135deg, #667eea 0%, #764ba2 100%); color: white; padding: 15px 0; text-align: center;">
        <div class="container">
            <p>Selamat datang, <strong><?php echo htmlspecialchars($username); ?></strong>! Temukan kursus yang tepat untuk Anda 🎓</p>
        </div>
    </div>

    <!-- Page Hero Section untuk Kursus -->
    <section class="page-hero">
        <div class="container">
            <div class="page-hero-content">
                <div class="breadcrumb">
                    <a href="index.php">Beranda</a>
                    <i class="fas fa-chevron-right"></i>
                    <span>Kursus</span>
                </div>
                <h1>Jelajahi Kursus Digital</h1>
                <p>Temukan kursus berkualitas tinggi yang dirancang untuk mengembangkan keterampilan digital Anda. Dari pemula hingga mahir, kami menyediakan pembelajaran yang komprehensif.</p>
                <div class="stats-overview">
                    <div class="stat-item">
                        <div class="stat-number">50+</div>
                        <div class="stat-label">Kursus Tersedia</div>
                    </div>
                    <div class="stat-item">
                        <div class="stat-number">5,000+</div>
                        <div class="stat-label">Siswa Aktif</div>
                    </div>
                    <div class="stat-item">
                        <div class="stat-number">25+</div>
                        <div class="stat-label">Mentor Berpengalaman</div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- Search and Filter Section -->
    <section class="course-filters">
        <div class="container">
            <div class="filter-bar">
                <div class="search-wrapper">
                    <div class="search-input-wrapper">
                        <i class="fas fa-search"></i>
                        <input type="text" id="course-search" placeholder="Cari kursus yang Anda inginkan...">
                    </div>
                    <button class="btn btn-primary search-btn" id="search-btn">Cari</button>
                </div>

                <div class="filter-options">
                    <div class="filter-group">
                        <label for="category-filter">Kategori:</label>
                        <select id="category-filter">
                            <option value="all">Semua Kategori</option>
                            <option value="design">Pendidikan</option>
                            <option value="programming">Pemrograman</option>
                            <option value="marketing">Bisnis & Marketing</option>
                            <option value="business">UI/UX Design</option>
                            <option value="photography">Kerajinan & Seni</option>
                            <option value="ai-ml">Musik & Audio</option>
                            <option value="ai-ml">Kesehatan & Kebugaran</option>
                            <option value="ai-ml">Fotografi & Video</option>
                            <option value="ai-ml">Bahasa</option>
                            <option value="ai-ml">Hobi & Lifestyle</option>
                        </select>
                    </div>

                    <div class="filter-group">
                        <label for="level-filter">Level:</label>
                        <select id="level-filter">
                            <option value="all">Semua Level</option>
                            <option value="beginner">Pemula</option>
                            <option value="intermediate">Menengah</option>
                            <option value="advanced">Mahir</option>
                        </select>
                    </div>

                    <div class="filter-group">
                        <label for="price-filter">Harga:</label>
                        <select id="price-filter">
                            <option value="all">Semua Harga</option>
                            <option value="free">Gratis</option>
                            <option value="premium">Premium</option>
                        </select>
                    </div>

                    <div class="filter-group">
                        <label for="sort-filter">Urutkan:</label>
                        <select id="sort-filter">
                            <option value="newest">Terbaru</option>
                            <option value="popular">Terpopuler</option>
                            <option value="rating">Rating Tertinggi</option>
                            <option value="price-low">Harga Terendah</option>
                            <option value="price-high">Harga Tertinggi</option>
                        </select>
                    </div>

                    <button class="btn btn-outline reset-btn" id="reset-filters">
                        <i class="fas fa-undo"></i>
                        Reset
                    </button>
                </div>
            </div>
        </div>
    </section>

    <!-- Course Categories Quick Access -->
    <section class="course-categories">
        <div class="container">
            <h2>Kategori Populer</h2>
            <div class="category-grid">
                <div class="category-card" data-category="design">
                    <div class="category-icon">
                        <i class="fas fa-palette"></i>
                    </div>
                    <h3>Pendidikan</h3>
                    <p>Pendidikan Karakter, Bimbingan Belajar, Pendidikan Inklusif </p>
                    <span class="course-count">12 Kursus</span>
                </div>

                <div class="category-card" data-category="programming">
                    <div class="category-icon">
                        <i class="fas fa-code"></i>
                    </div>
                    <h3>Pemrograman</h3>
                    <p>Web, Mobile, Backend</p>
                    <span class="course-count">18 Kursus</span>
                </div>

                <div class="category-card" data-category="marketing">
                    <div class="category-icon">
                        <i class="fas fa-bullhorn"></i>
                    </div>
                    <h3>Bisnis Marketing</h3>
                    <p>SEO, Social Media, Digital Marketing, Manajemen Bisnis, Kewirausahaan</p>
                    <span class="course-count">8 Kursus</span>
                </div>

                <div class="category-card" data-category="business">
                    <div class="category-icon">
                        <i class="fas fa-chart-line"></i>
                    </div>
                    <h3>UI/UX Design</h3>
                    <p>Desain Antarmuka (UI), Pengalaman Pengguna (UX), Prototyping & Wireframing, Design Thinking</p>
                    <span class="course-count">6 Kursus</span>
                </div>

                <div class="category-card" data-category="ai-ml">
                    <div class="category-icon">
                        <i class="fas fa-brain"></i>
                    </div>
                    <h3>Kerajinan & Seni</h3>
                    <p>Lukis & Menggambar, DIY & Kerajinan Tangan, Seni Tradisional, Kaligrafi</p>
                    <span class="course-count">4 Kursus</span>
                </div>

                <div class="category-card" data-category="photography">
                    <div class="category-icon">
                        <i class="fas fa-camera"></i>
                    </div>
                    <h3>Musik & Audio</h3>
                    <p>Produksi Musik Digital, Bermain Alat Musik, Vokal & Menyanyi, Sound Design</p>
                    <span class="course-count">6 Kursus</span>
                </div>

                <div class="category-card" data-category="photography">
                    <div class="category-icon">
                        <i class="fas fa-camera"></i>
                    </div>
                    <h3>Kesehatan & Kebugaran</h3>
                    <p>Olahraga & Workout, Yoga & Meditasi, Gizi & Diet, Kesehatan Mental</p>
                    <span class="course-count">3 Kursus</span>
                </div>

                <div class="category-card" data-category="photography">
                    <div class="category-icon">
                        <i class="fas fa-camera"></i>
                    </div>
                    <h3>Fotografi & Video</h3>
                    <p>Fotografi Dasar, Videografi, Editing Foto, editing Video</p>
                    <span class="course-count">6 Kursus</span>
                </div>

                <div class="category-card" data-category="photography">
                    <div class="category-icon">
                        <i class="fas fa-camera"></i>
                    </div>
                    <h3>Bahasa</h3>
                    <p>Bahasa Inggris, Bahasa Jepang, Bahasa Korea, Bahasa Arab</p>
                    <span class="course-count">5 Kursus</span>
                </div>

                <div class="category-card" data-category="photography">
                    <div class="category-icon">
                        <i class="fas fa-camera"></i>
                    </div>
                    <h3>Hobi & Lifestyle</h3>
                    <p>Memasak & Baking, Berkebun, Fashion & Beauty</p>
                    <span class="course-count">2 Kursus</span>
                </div>
            </div>
        </div>
    </section>

    <section class="tabs">
        <div class="container">
            <div class="tab-links">
                <button class="tab-link active" data-tab="all-courses">Semua Kursus</button>
                <button class="tab-link" data-tab="my-courses">Kursus Saya</button>
            </div>
        </div>
    </section>

    <main>
        <div class="container">
            <section id="all-courses" class="tab-content active">
                <div class="section-header">
                    <h2>Jelajahi Kursus</h2>
                    <div class="filter-wrapper">
                        <div class="filter">
                            <label for="category-filter">Kategori:</label>
                            <select id="category-filter">
                                <option value="all">Semua</option>
                                <option value="design">Desain</option>
                                <option value="programming">Pemrograman</option>
                                <option value="marketing">Marketing</option>
                                <option value="business">Bisnis</option>
                            </select>
                        </div>
                        <div class="filter">
                            <label for="level-filter">Level:</label>
                            <select id="level-filter">
                                <option value="all">Semua</option>
                                <option value="beginner">Pemula</option>
                                <option value="intermediate">Menengah</option>
                                <option value="advanced">Mahir</option>
                            </select>
                        </div>
                        <div class="filter">
                            <label for="price-filter">Harga:</label>
                            <select id="price-filter">
                                <option value="all">Semua</option>
                                <option value="free">Gratis</option>
                                <option value="premium">Premium</option>
                            </select>
                        </div>
                    </div>
                </div>

                <div class="courses-grid" id="courses-container">
                    <!-- Course cards will be inserted here by JavaScript -->
                </div>
            </section>

            <section id="my-courses" class="tab-content">
                <div class="section-header">
                    <h2>Kursus Saya</h2>
                </div>
                <div class="enrolled-courses" id="enrolled-courses-container">
                    <!-- Enrolled courses will be inserted here by JavaScript -->
                    <div class="empty-state">
                        <img src="img/empty-courses.svg" alt="Belum ada kursus">
                        <h3>Anda belum mengikuti kursus apapun</h3>
                        <p>Jelajahi katalog kursus kami dan mulai perjalanan belajar Anda</p>
                        <button class="btn btn-primary">Jelajahi Kursus</button>
                    </div>
                </div>
            </section>
        </div>
    </main>

    <!-- Course Detail Modal -->
    <div class="modal" id="course-detail-modal">
        <div class="modal-content">
            <div class="modal-header">
                <h2 id="modal-course-title">Judul Kursus</h2>
                <button class="modal-close">&times;</button>
            </div>
            <div class="modal-body">
                <div class="course-detail-info">
                    <div class="course-detail-image">
                        <img id="modal-course-image" src="" alt="Course Image">
                        <div class="course-badges">
                            <span class="badge badge-level" id="modal-course-level">Pemula</span>
                            <span class="badge badge-price" id="modal-course-price">Gratis</span>
                        </div>
                    </div>
                    <div class="course-meta">
                        <div class="meta-item">
                            <i class="fas fa-clock"></i>
                            <span id="modal-course-duration">8 Jam</span>
                        </div>
                        <div class="meta-item">
                            <i class="fas fa-film"></i>
                            <span id="modal-course-lessons">24 Pelajaran</span>
                        </div>
                        <div class="meta-item">
                            <i class="fas fa-users"></i>
                            <span id="modal-course-students">1,234 Siswa</span>
                        </div>
                        <div class="meta-item">
                            <i class="fas fa-star"></i>
                            <span id="modal-course-rating">4.8</span>
                        </div>
                    </div>
                    <div class="course-instructor">
                        <img id="modal-instructor-avatar" src="" alt="Instructor Avatar">
                        <div>
                            <p>Instruktur</p>
                            <h4 id="modal-instructor-name">Nama Instruktur</h4>
                        </div>
                    </div>
                    <p id="modal-course-description" class="course-description"></p>
                    <div class="course-actions">
                        <button class="btn btn-primary btn-full" id="enroll-btn">Ikuti Kursus</button>
                    </div>
                </div>

                <div class="course-detail-tabs">
                    <button class="course-tab-link active" data-tab="syllabus">Deskripsi</button>
                </div>

                <div id="syllabus-content" class="course-tab-content active">
                    <h3>Silabus Kursus</h3>
                    <div class="module-list" id="modal-course-modules">
                        <!-- Modules will be inserted here -->
                    </div>
                </div>

                <div id="forum-content" class="course-tab-content">
                    <h3>Forum Diskusi</h3>
                    <div class="forum-container">
                        <div class="comment-form">
                            <textarea placeholder="Tulis pertanyaan atau komentar Anda di sini..."></textarea>
                            <button class="btn btn-primary">Kirim</button>
                        </div>
                        <div class="comments-list" id="modal-comments-list">
                            <!-- Comments will be inserted here -->
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Support Instructor Modal -->
    <div class="modal" id="support-instructor-modal">
        <div class="modal-content">
            <div class="modal-header">
                <h2>Dukung Mentor</h2>
                <button class="modal-close">&times;</button>
            </div>
            <div class="modal-body">
                <div class="instructor-profile">
                    <img id="support-instructor-avatar" src="" alt="Instructor Avatar">
                    <div>
                        <h3 id="support-instructor-name">Nama Instruktur</h3>
                        <p id="support-instructor-bio">Bio Instruktur</p>
                    </div>
                </div>

                <div class="support-options">
                    <h4>Pilih Jumlah Donasi</h4>
                    <div class="amount-options">
                        <button class="amount-option">Rp 50.000</button>
                        <button class="amount-option">Rp 100.000</button>
                        <button class="amount-option">Rp 200.000</button>
                        <button class="amount-option custom">Jumlah Lain</button>
                    </div>
                    <div class="custom-amount">
                        <input type="number" placeholder="Masukkan jumlah" min="10000">
                    </div>
                    <div class="payment-methods">
                        <h4>Metode Pembayaran</h4>
                        <div class="payment-options">
                            <label class="payment-option">
                                <input type="radio" name="payment" value="transfer">
                                <span>Transfer Bank</span>
                            </label>
                            <label class="payment-option">
                                <input type="radio" name="payment" value="ewallet">
                                <span>E-Wallet</span>
                            </label>
                            <label class="payment-option">
                                <input type="radio" name="payment" value="card">
                                <span>Kartu Kredit/Debit</span>
                            </label>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <footer class="footer">
        <div class="container">
            <div class="footer-content">
                <div class="footer-logo">
                    <img src="img/mindcraft-logo.png" alt="MindCraft Logo">
                    <h2>Mind<span>Craft</span></h2>
                    <p>Platform E-Lifestyle untuk Mengembangkan Keterampilan Digital dan Membangun Ekosistem Kreatif</p>
                </div>
                <div class="footer-links">
                    <div class="footer-section">
                        <h3>MindCraft</h3>
                        <ul>
                            <li><a href="#">Tentang Kami</a></li>
                            <li><a href="#">Karir</a></li>
                            <li><a href="#">Blog</a></li>
                            <li><a href="#">Afiliasi</a></li>
                        </ul>
                    </div>
                    <div class="footer-section">
                        <h3>Layanan</h3>
                        <ul>
                            <li><a href="courses.php">Kursus</a></li>
                            <li><a href="#">Mentoring</a></li>
                            <li><a href="#">Menjadi Mentor</a></li>
                            <li><a href="#">Crowdfunding</a></li>
                        </ul>
                    </div>
                    <div class="footer-section">
                        <h3>Bantuan</h3>
                        <ul>
                            <li><a href="#">FAQ</a></li>
                            <li><a href="#">Hubungi Kami</a></li>
                            <li><a href="#">Kebijakan Privasi</a></li>
                            <li><a href="#">Syarat & Ketentuan</a></li>
                        </ul>
                    </div>
                </div>
                <div class="footer-newsletter">
                    <h3>Berlangganan Newsletter</h3>
                    <p>Dapatkan update tentang kursus baru dan tips pengembangan skill</p>
                    <form class="newsletter-form">
                        <input type="email" placeholder="Alamat Email Anda">
                        <button class="btn btn-primary">Langganan</button>
                    </form>
                    <div class="social-links">
                        <a href="#"><i class="fab fa-facebook-f"></i></a>
                        <a href="#"><i class="fab fa-twitter"></i></a>
                        <a href="#"><i class="fab fa-instagram"></i></a>
                        <a href="#"><i class="fab fa-linkedin-in"></i></a>
                        <a href="#"><i class="fab fa-youtube"></i></a>
                    </div>
                </div>
            </div>
            <div class="footer-bottom">
                <p>&copy; 2025 MindCraft. Hak Cipta Dilindungi.</p>
                <div class="language-selector">
                    <select>
                        <option value="id">Bahasa Indonesia</option>
                        <option value="en">English</option>
                    </select>
                </div>
            </div>
        </div>
    </footer>

    <style>
        /* Additional styles for user menu */
        .user-menu {
            position: relative;
            display: flex;
            align-items: center;
            gap: 0.5rem;
            cursor: pointer;
            padding: 0.5rem 1rem;
            background: var(--gray-100);
            border-radius: var(--rounded-lg);
        }

        .user-avatar {
            width: 32px;
            height: 32px;
            background: var(--primary-600);
            color: white;
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            font-weight: 600;
            font-size: 14px;
        }

        .username {
            font-weight: 500;
            color: var(--gray-700);
        }

        .dropdown-menu {
            position: absolute;
            top: 100%;
            right: 0;
            background: white;
            border-radius: var(--rounded-lg);
            box-shadow: var(--shadow-lg);
            min-width: 150px;
            display: none;
            z-index: 1000;
            border: 1px solid var(--gray-200);
        }

        .dropdown-menu a {
            display: block;
            padding: 0.75rem 1rem;
            color: var(--gray-700);
            text-decoration: none;
            transition: background-color var(--transition-fast);
            border-bottom: 1px solid var(--gray-100);
        }

        .dropdown-menu a:hover {
            background-color: var(--gray-50);
        }

        .dropdown-menu a:last-child {
            border-bottom: none;
        }

        .user-menu:hover .dropdown-menu {
            display: block;
        }

        .welcome-banner {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            color: white;
            padding: 15px 0;
            text-align: center;
        }

        .welcome-banner p {
            margin: 0;
            font-size: 16px;
        }
    </style>

    <script src="/MindCraft-Project/assets/js/mentee-dashboard.js"></script>
    

    <script>
        // Category card functionality
        document.querySelectorAll('.category-card').forEach(card => {
            card.addEventListener('click', function() {
                const category = this.dataset.category;
                console.log('Selected category:', category);
                // Here you can add functionality to filter courses by category
            });
        });

        // User menu toggle
        const userAvatar = document.getElementById('user-avatar');
        const dropdownMenu = document.getElementById('dropdown-menu');

        if (userAvatar) {
            userAvatar.addEventListener('click', function(e) {
                e.stopPropagation();
                dropdownMenu.style.display = dropdownMenu.style.display === 'block' ? 'none' : 'block';
            });
        }

        // Close dropdown when clicking outside
        document.addEventListener('click', function() {
            if (dropdownMenu) {
                dropdownMenu.style.display = 'none';
            }
        });
    </script>
</body>

</html>