<?php
session_start();
require_once 'config.php';

// Set default user data (no login required)
$user_id = $_SESSION['user_id'] ?? 1;
$username = $_SESSION['username'] ?? 'Demo User';
$email = $_SESSION['email'] ?? 'demo@mindcraft.com';
$_SESSION['user_type'] = $_SESSION['user_type'] ?? 'Mentee';
$user_type = $_SESSION['user_type'];

// Fungsi untuk mengambil kursus yang diikuti oleh user
function getEnrolledCourses($pdo, $user_id) {
    $stmt = $pdo->prepare("SELECT c.*, 
                                  e.enrollment_date,
                                  e.progress_percentage,
                                  (SELECT AVG(rating) FROM reviews r WHERE r.course_id = c.id) AS avg_rating
                           FROM enrollments e
                           JOIN courses c ON e.course_id = c.id
                           WHERE e.student_id = ? AND e.status = 'active'");
    $stmt->execute([$user_id]);
    return $stmt->fetchAll(PDO::FETCH_ASSOC);
}

$enrolledCourses = getEnrolledCourses($pdo, $user_id);
?>
<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashboard Mentee - MindCraft Platform E-Lifestyle</title>
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
                        <li><a href="dashboard.php" class="active">Beranda</a></li>
                        <li><a href="kursus.php">Kursus</a></li>
                        <li><a href="ai_assistant.php">🤖 MindBot</a></li>
                    </ul>
                </nav>
                <div class="header-actions">
                    <!-- Show login buttons if not logged in, or user menu if logged in -->
                    <?php if (!isset($_SESSION['username']) || $_SESSION['username'] === 'Demo User'): ?>
                        <button class="btn btn-secondary" onclick="window.location.href='../landingpage/landingpage.php'">Masuk</button>
                        <button class="btn btn-primary" onclick="window.location.href='../landingpage/landingpage.php'">Daftar</button>
                    <?php else: ?>
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
                    <?php endif; ?>
                    <button class="btn btn-menu" id="menu-toggle">
                        <i class="fas fa-bars"></i>
                    </button>
                </div>
            </div>
        </div>
    </header>

    <!-- Welcome message for logged in user (optional, only if real login) -->
    <?php if (isset($_SESSION['username']) && $_SESSION['username'] !== 'Demo User'): ?>
        <div class="welcome-banner" style="background: linear-gradient(135deg, #667eea 0%, #764ba2 100%); color: white; padding: 15px 0; text-align: center;">
            <div class="container">
                <p>Selamat datang kembali, <strong><?php echo htmlspecialchars($username); ?></strong>! Mari lanjutkan perjalanan belajar Anda 🎓</p>
            </div>
        </div>
    <?php endif; ?>

    <!-- Hero Section -->
    <section class="hero">
        <div class="container">
            <div class="hero-content">
                <h2>Kembangkan Keterampilan Digital Anda</h2>
                <p>Akses kursus berkualitas dan bergabunglah dengan komunitas kreatif untuk belajar dan berkembang bersama</p>
                <div class="hero-cta">
                    <button class="btn btn-outline btn-lg"><a href="kursus.php">Mulai Belajar</a></button>
                    <button class="btn btn-outline btn-lg"><a href="kursus.php">Jelajahi Kursus</a></button>
                </div>
            </div>
            <div class="hero-image">
                <img src="https://images.pexels.com/photos/7516347/pexels-photo-7516347.jpeg?auto=compress&cs=tinysrgb&w=1260&h=750&dpr=1" alt="Student Learning">
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
        <?php if (empty($enrolledCourses)): ?>
            <div class="empty-state">
                <img src="img/empty-courses.svg" alt="Belum ada kursus">
                <h3>Anda belum mengikuti kursus apapun</h3>
                <p>Jelajahi katalog kursus kami dan mulai perjalanan belajar Anda</p>
                <button class="btn btn-primary" onclick="window.location.href='kursus.php'">Jelajahi Kursus</button>
            </div>
        <?php else: ?>
            <div class="enrolled-courses-grid">
                <?php foreach ($enrolledCourses as $course): ?>
                <div class="enrolled-course-card">
                    <div class="course-image">
                        <img src="<?= htmlspecialchars($course['cover_image'] ?? 'default-course.jpg') ?>" alt="<?= htmlspecialchars($course['title']) ?>">
                    </div>
                    <div class="course-info">
                        <h3><?= htmlspecialchars($course['title']) ?></h3>
                        <div class="progress-container">
                            <div class="progress-bar" style="width: <?= $course['progress_percentage'] ?>%"></div>
                        </div>
                        <span class="progress-text"><?= $course['progress_percentage'] ?>% selesai</span>
                        <div class="course-meta">
                            <span><i class="fas fa-star"></i> <?= number_format($course['avg_rating'] ?? 0, 1) ?></span>
                            <span><i class="fas fa-clock"></i> <?= $course['duration_hours'] ?> Jam</span>
                        </div>
                    </div>
                    <div class="course-actions">
                        <button class="btn btn-primary continue-btn" data-course-id="<?= $course['id'] ?>">Lanjutkan</button>
                    </div>
                </div>
                <?php endforeach; ?>
            </div>
        <?php endif; ?>
    </div>
</section>

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
        // Tab functionality
        document.addEventListener('DOMContentLoaded', function() {
            const tabLinks = document.querySelectorAll('.tab-link');
            const tabContents = document.querySelectorAll('.tab-content');

            tabLinks.forEach(link => {
                link.addEventListener('click', function() {
                    const targetTab = this.getAttribute('data-tab');

                    // Remove active class from all tabs and contents
                    tabLinks.forEach(l => l.classList.remove('active'));
                    tabContents.forEach(c => c.classList.remove('active'));

                    // Add active class to clicked tab and corresponding content
                    this.classList.add('active');
                    document.getElementById(targetTab).classList.add('active');
                });
            });

            // User menu toggle for mobile
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

            // Modal functionality
            const modals = document.querySelectorAll('.modal');
            const modalCloses = document.querySelectorAll('.modal-close');

            modalCloses.forEach(close => {
                close.addEventListener('click', function() {
                    this.closest('.modal').style.display = 'none';
                });
            });

            modals.forEach(modal => {
                modal.addEventListener('click', function(e) {
                    if (e.target === this) {
                        this.style.display = 'none';
                    }
                });
            });
        });
    </script>
</body>

</html>