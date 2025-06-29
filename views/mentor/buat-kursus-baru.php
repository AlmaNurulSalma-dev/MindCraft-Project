<?php
session_start(); 

// Include database connection
require_once __DIR__ . '/../../config/Database.php';
error_reporting(E_ALL & ~E_WARNING & ~E_NOTICE);



try {
    // Initialize database
    $database = new Database();
    $db = $database->connect();
    
    // Ensure database connection is successful
    if (!$db) {
        throw new Exception("Database connection failed");
    }
    
    // Test connection
    $db->query("SELECT 1");
    
    // Create tables if they don't exist
    try {
        // Create courses table
        $db->exec("CREATE TABLE IF NOT EXISTS courses (
            id INT PRIMARY KEY AUTO_INCREMENT,
            mentor_id INT NOT NULL,
            title VARCHAR(255) NOT NULL,
            slug VARCHAR(255) UNIQUE NOT NULL,
            category VARCHAR(100) NOT NULL,
            difficulty ENUM('Pemula', 'Menengah', 'Mahir') NOT NULL,
            description TEXT NOT NULL,
            cover_image VARCHAR(500) DEFAULT NULL,
            price DECIMAL(10,2) DEFAULT 0.00,
            is_premium TINYINT(1) DEFAULT 1,
            status ENUM('Draft', 'Published', 'Archived') DEFAULT 'Draft',
            created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
            updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
            INDEX idx_mentor_id (mentor_id),
            INDEX idx_category (category),
            INDEX idx_status (status)
        )");
        
        // Create course_categories table
        $db->exec("CREATE TABLE IF NOT EXISTS course_categories (
            id INT PRIMARY KEY AUTO_INCREMENT,
            name VARCHAR(100) NOT NULL,
            slug VARCHAR(100) UNIQUE NOT NULL,
            is_active TINYINT(1) DEFAULT 1,
            sort_order INT DEFAULT 1,
            created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
            updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
        )");
        
    } catch (Exception $e) {
        error_log("Table creation error: " . $e->getMessage());
    }
    
    // CRITICAL: Validate mentor exists and fix if needed
    $mentorCheck = $db->prepare("SELECT id, username, email, user_type FROM users WHERE id = ?");
    $mentorCheck->execute([$mentorId]);
    $mentorData = $mentorCheck->fetch(PDO::FETCH_ASSOC);
    
    if (!$mentorData) {
        // Try to find any mentor and use it
        $anyMentor = $db->prepare("SELECT id FROM users WHERE user_type = 'Mentor' LIMIT 1");
        $anyMentor->execute();
        $mentorFound = $anyMentor->fetch(PDO::FETCH_ASSOC);
        
        if ($mentorFound) {
            // Update session with valid mentor ID
            $mentorId = $mentorFound['id'];
            $_SESSION['mentor_id'] = $mentorId;
        } else {
            // Create a default mentor if none exists
            $createMentor = $db->prepare("
                INSERT INTO users (username, email, password, user_type, gender, created_at, updated_at) 
                VALUES (?, ?, ?, 'Mentor', 'Laki-laki', NOW(), NOW())
            ");
            $defaultPassword = password_hash('password123', PASSWORD_DEFAULT);
            $createMentor->execute(['mentor_default', 'mentor@mindcraft.com', $defaultPassword]);
            
            $mentorId = $db->lastInsertId();
            $_SESSION['mentor_id'] = $mentorId;
        }
    }
    
    // Get categories from database
    $stmt = $db->prepare("SELECT name, slug FROM course_categories WHERE is_active = 1 ORDER BY sort_order, name");
    $stmt->execute();
    $categoriesFromDb = $stmt->fetchAll(PDO::FETCH_ASSOC);
    
    $categories = [];
    foreach ($categoriesFromDb as $cat) {
        $categories[$cat['slug']] = $cat['name'];
    }
    
    // If no categories in database, use default ones and insert them
    if (empty($categories)) {
        $categories = [
            'pendidikan' => 'Pendidikan',
            'ui-ux' => 'UI/UX Design', 
            'programming' => 'Programming',
            'bisnis' => 'Bisnis & Marketing',
            'kerajinan' => 'Kerajinan & Seni',
            'kesehatan' => 'Kesehatan & Kebugaran',
            'musik' => 'Musik & Audio',
            'fotografi' => 'Fotografi & Video',
            'bahasa' => 'Bahasa',
            'hobi' => 'Hobi & Lifestyle'
        ];
        
        // Insert default categories
        try {
            $sortOrder = 1;
            foreach ($categories as $slug => $name) {
                $insertCat = $db->prepare("
                    INSERT IGNORE INTO course_categories (name, slug, is_active, sort_order, created_at, updated_at) 
                    VALUES (?, ?, 1, ?, NOW(), NOW())
                ");
                $insertCat->execute([$name, $slug, $sortOrder++]);
            }
        } catch (Exception $e) {
            error_log("Failed to insert default categories: " . $e->getMessage());
        }
    }
    
} catch (Exception $e) {
    error_log("Database error in buat-kursus-baru.php: " . $e->getMessage());
    // Use default categories if database fails
    $categories = [
        'pendidikan' => 'Pendidikan',
        'ui-ux' => 'UI/UX Design', 
        'programming' => 'Programming',
        'bisnis' => 'Bisnis & Marketing',
        'kerajinan' => 'Kerajinan & Seni',
        'kesehatan' => 'Kesehatan & Kebugaran',
        'musik' => 'Musik & Audio',
        'fotografi' => 'Fotografi & Video',
        'bahasa' => 'Bahasa',
        'hobi' => 'Hobi & Lifestyle'
    ];
}

// Handle form submission
$successMessage = '';
$errorMessage = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $action = $_POST['action'] ?? 'draft';
    
    try {
        // Validasi basic
        $title = trim($_POST['title'] ?? '');
        $category = $_POST['category'] ?? '';
        $difficulty = $_POST['difficulty'] ?? '';
        $description = trim($_POST['description'] ?? '');
        $price = str_replace(['.', ',', 'Rp', ' '], '', $_POST['price'] ?? '0');
        $freemium = isset($_POST['freemium']);
        
        $errors = [];
        
        // Double-check mentor exists
        $mentorValidation = $db->prepare("SELECT id FROM users WHERE id = ?");
        $mentorValidation->execute([$mentorId]);
        if ($mentorValidation->rowCount() === 0) {
            $errors[] = "Error: Session mentor tidak valid. Silakan login kembali.";
        }
        
        // Validasi required fields
        if (empty($title)) {
            $errors[] = 'Judul kursus wajib diisi';
        } elseif (strlen($title) < 5) {
            $errors[] = 'Judul kursus minimal 5 karakter';
        }
        
        if (empty($category)) {
            $errors[] = 'Kategori kursus wajib dipilih';
        }
        
        if (empty($difficulty)) {
            $errors[] = 'Tingkat kesulitan wajib dipilih';
        }
        
        if (empty($description)) {
            $errors[] = 'Deskripsi kursus wajib diisi';
        } elseif (strlen($description) < 20) {
            $errors[] = 'Deskripsi kursus minimal 20 karakter';
        }
        
        if (!$freemium && (empty($price) || $price <= 0)) {
            $errors[] = 'Harga kursus wajib diisi untuk kursus berbayar';
        }
        
        // Handle file upload
        $coverImage = '';
        if (isset($_FILES['cover_image']) && $_FILES['cover_image']['error'] === UPLOAD_ERR_OK) {
            $uploadDir = '../../uploads/course-covers/';
            
            // Create directory if not exists
            if (!is_dir($uploadDir)) {
                if (!mkdir($uploadDir, 0755, true)) {
                    $errors[] = 'Gagal membuat folder upload';
                }
            }
            
            // Check if directory is writable
            if (!is_writable($uploadDir)) {
                $errors[] = 'Folder upload tidak dapat ditulis';
            } else {
                $fileInfo = pathinfo($_FILES['cover_image']['name']);
                
                if (!isset($fileInfo['extension'])) {
                    $errors[] = 'File tidak memiliki extension';
                } else {
                    $fileName = uniqid() . '_' . time() . '.' . strtolower($fileInfo['extension']);
                    $uploadPath = $uploadDir . $fileName;
                    
                    // Validate file type and size
                    $allowedTypes = ['jpg', 'jpeg', 'png', 'gif', 'webp'];
                    $maxSize = 5 * 1024 * 1024; // 5MB
                    
                    // Check MIME type
                    $finfo = finfo_open(FILEINFO_MIME_TYPE);
                    $mimeType = finfo_file($finfo, $_FILES['cover_image']['tmp_name']);
                    finfo_close($finfo);
                    
                    $allowedMimes = ['image/jpeg', 'image/png', 'image/gif', 'image/webp'];
                    
                    if (!in_array(strtolower($fileInfo['extension']), $allowedTypes)) {
                        $errors[] = 'Format file cover tidak didukung';
                    } elseif (!in_array($mimeType, $allowedMimes)) {
                        $errors[] = 'Tipe file tidak didukung';
                    } elseif ($_FILES['cover_image']['size'] > $maxSize) {
                        $errors[] = 'Ukuran file cover terlalu besar (maksimal 5MB)';
                    } elseif (!move_uploaded_file($_FILES['cover_image']['tmp_name'], $uploadPath)) {
                        $errors[] = 'Gagal mengunggah file cover';
                    } else {
                        $coverImage = '/MindCraft-Project/uploads/course-covers/' . $fileName;
                    }
                }
            }
        }
        
        if (empty($errors) && $db) {
            try {
                // Generate unique slug
                $slug = generateSlug($title);
                $checkSlug = $db->prepare("SELECT id FROM courses WHERE slug = ?");
                $checkSlug->execute([$slug]);
                if ($checkSlug->rowCount() > 0) {
                    $slug = $slug . '-' . time();
                }
                
                $status = $action === 'publish' ? 'Published' : 'Draft';
                $isPremium = $freemium ? 0 : 1; // Freemium = 0, Premium = 1
                $finalPrice = $freemium ? 0.00 : (float)$price;
                
                // Insert course - simplified without foreign key temporarily
                $stmt = $db->prepare("
                    INSERT INTO courses (
                        mentor_id, title, slug, category, difficulty, description, 
                        cover_image, price, is_premium, status, created_at, updated_at
                    ) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, NOW(), NOW())
                ");
                
                $result = $stmt->execute([
                    (int)$mentorId,
                    $title, 
                    $slug, 
                    $category, 
                    $difficulty, 
                    $description,
                    $coverImage, 
                    $finalPrice,
                    (int)$isPremium, 
                    $status
                ]);
                
                if ($result) {
                    $courseId = $db->lastInsertId();
                    
                    if ($action === 'publish') {
                        $successMessage = 'Kursus berhasil dipublikasikan! 🎉';
                        header('Location: /MindCraft-Project/views/mentor/kursus-saya.php?success=' . urlencode($successMessage));
                        exit();
                    } elseif ($action === 'preview') {
                        $successMessage = 'Pratinjau kursus akan dibuka...';
                        header('Location: /MindCraft-Project/views/mentor/preview-kursus.php?id=' . $courseId);
                        exit();
                    } else {
                        $successMessage = 'Draft kursus berhasil disimpan! 💾';
                        $_POST = [];
                    }
                } else {
                    $errorInfo = $stmt->errorInfo();
                    $errors[] = 'Gagal menyimpan kursus: ' . ($errorInfo[2] ?? 'Unknown error');
                }
                
            } catch (PDOException $e) {
                $errors[] = 'Database error: ' . $e->getMessage();
            }
        }
        
        if (!empty($errors)) {
            $errorMessage = implode('<br>', $errors);
        }
        
    } catch (Exception $e) {
        $errorMessage = 'Terjadi kesalahan: ' . $e->getMessage();
    }
}

// Helper function to generate slug
function generateSlug($text) {
    if (empty($text)) {
        return 'untitled-' . time();
    }
    
    $text = strtolower(trim($text));
    $text = preg_replace('/[^a-z0-9\s-]/', '', $text);
    $text = preg_replace('/\s+/', '-', $text);
    $text = preg_replace('/-+/', '-', $text);
    return trim($text, '-') ?: 'untitled-' . time();
}
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>MindCraft - Buat Kursus Baru</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="/MindCraft-Project/assets/css/mentor_buat-kursus-baru.css">
</head>
<body>
    <!-- Top Header -->
    <header class="top-header">
        <div class="logo">MindCraft</div>
        <button class="mobile-menu-toggle" id="mobileMenuToggle">☰</button>
    </header>

    <div class="dashboard-container">
        <!-- Sidebar -->
        <aside class="sidebar" id="sidebar">
            <ul class="sidebar-menu">
                <li><a href="/MindCraft-Project/views/mentor/dashboard.php">Dashboard</a></li>
                <li><a href="/MindCraft-Project/views/mentor/kursus-saya.php">Kursus Saya</a></li>
                <li><a href="/MindCraft-Project/views/mentor/buat-kursus-baru.php" class="active">Buat Kursus Baru</a></li>
                <li><a href="/MindCraft-Project/views/mentor/pendapatan.php">Pendapatan</a></li>
                <li><a href="/MindCraft-Project/views/mentor/analitik.php">Analitik</a></li>
                <li><a href="/MindCraft-Project/views/mentor/pengaturan.php">Pengaturan</a></li>
                <li><a href="/MindCraft-Project/views/mentor/logout.php">Logout</a></li>
            </ul>
        </aside>

        <!-- Main Content -->
        <main class="main-content">
            <div class="content-header">
                <h1>Buat Kursus Baru</h1>
            </div>
            
            <div class="content-body">
                <?php if ($successMessage): ?>
                    <div class="alert alert-success" style="background: #e6ffed; border: 1px solid #2B992B; color: #2B992B; padding: 12px 16px; border-radius: 8px; margin-bottom: 24px;">
                        <?php echo htmlspecialchars($successMessage); ?>
                    </div>
                <?php endif; ?>
                
                <?php if ($errorMessage): ?>
                    <div class="alert alert-error" style="background: #fed7d7; border: 1px solid #E53E3E; color: #E53E3E; padding: 12px 16px; border-radius: 8px; margin-bottom: 24px;">
                        <?php echo $errorMessage; ?>
                    </div>
                <?php endif; ?>

                <div class="form-container fade-in-up">
                    <form id="createCourseForm" method="POST" enctype="multipart/form-data">
                        <div class="form-grid">
                            <!-- Left Column -->
                            <div class="form-column">
                                <!-- Judul Kursus -->
                                <div class="form-group">
                                    <label for="title">Judul Kursus</label>
                                    <input 
                                        type="text" 
                                        id="title" 
                                        name="title" 
                                        placeholder="Masukkan judul kursus"
                                        value="<?php echo htmlspecialchars($_POST['title'] ?? ''); ?>"
                                        maxlength="100"
                                        required
                                    >
                                </div>

                                <!-- Kategori -->
                                <div class="form-group">
                                    <label for="category">Kategori</label>
                                    <div class="custom-select">
                                        <select id="category" name="category" required>
                                            <option value="">Pilih kategori</option>
                                            <?php foreach ($categories as $value => $label): ?>
                                                <option value="<?php echo htmlspecialchars($value); ?>" 
                                                        <?php echo (($_POST['category'] ?? '') === $value) ? 'selected' : ''; ?>>
                                                    <?php echo htmlspecialchars($label); ?>
                                                </option>
                                            <?php endforeach; ?>
                                        </select>
                                    </div>
                                </div>

                                <!-- Tingkat Kesulitan -->
                                <div class="form-group">
                                    <label>Tingkat Kesulitan</label>
                                    <div class="difficulty-options">
                                        <div class="difficulty-option">
                                            <input type="radio" id="pemula" name="difficulty" value="Pemula" 
                                                <?php echo (($_POST['difficulty'] ?? '') === 'Pemula') ? 'checked' : ''; ?>>
                                            <label for="pemula">Pemula</label>
                                        </div>
                                        <div class="difficulty-option">
                                            <input type="radio" id="menengah" name="difficulty" value="Menengah"
                                                <?php echo (($_POST['difficulty'] ?? '') === 'Menengah') ? 'checked' : ''; ?>>
                                            <label for="menengah">Menengah</label>
                                        </div>
                                        <div class="difficulty-option">
                                            <input type="radio" id="mahir" name="difficulty" value="Mahir"
                                                <?php echo (($_POST['difficulty'] ?? '') === 'Mahir') ? 'checked' : ''; ?>>
                                            <label for="mahir">Mahir</label>
                                        </div>
                                    </div>
                                </div>

                                <!-- Harga Kursus -->
                                <div class="form-group">
                                    <label for="price">Harga Kursus</label>
                                    <div class="price-input">
                                        <input 
                                            type="text" 
                                            id="price" 
                                            name="price" 
                                            placeholder="Masukkan harga kursus"
                                            value="<?php echo htmlspecialchars($_POST['price'] ?? ''); ?>"
                                        >
                                    </div>
                                    <div class="checkbox-group">
                                        <input type="checkbox" id="freemium" name="freemium" value="1"
                                            <?php echo isset($_POST['freemium']) ? 'checked' : ''; ?>>
                                        <label for="freemium">Aktifkan model Freemium (beberapa konten gratis)</label>
                                    </div>
                                </div>
                            </div>

                            <!-- Right Column -->
                            <div class="form-column">
                                <!-- Foto/Gambar Cover -->
                                <div class="form-group">
                                    <label>Foto/Gambar Cover</label>
                                    
                                    <!-- Upload Area -->
                                    <div class="file-upload">
                                        <input type="file" id="coverImage" name="cover_image" accept="image/*">
                                        <div class="upload-icon">📸</div>
                                        <div class="upload-text">Klik untuk pilih gambar atau drag & drop</div>
                                        <div class="upload-hint">Format: JPG, PNG, GIF, WebP (Max: 5MB) • Resolusi minimal: 300×200</div>
                                    </div>
                                    
                                    <!-- Image Preview Container -->
                                    <div class="image-preview-container">
                                        <!-- Konten preview akan diisi oleh JavaScript -->
                                    </div>
                                    
                                    <!-- File Info (fallback untuk non-image files) -->
                                    <div class="file-preview" style="display: none;">
                                        <div class="file-icon">🖼️</div>
                                        <div class="file-info">
                                            <div class="file-name">filename.jpg</div>
                                            <div class="file-size">2.5 MB</div>
                                        </div>
                                        <button type="button" class="file-remove">✕</button>
                                    </div>
                                </div>
                            </div>

                            <!-- Full Width - Deskripsi -->
                            <div class="form-group full-width">
                                <label for="description">Deskripsi Kursus</label>
                                <textarea 
                                    id="description" 
                                    name="description" 
                                    placeholder="Jelaskan tentang kursus ini, apa yang akan dipelajari, untuk siapa kursus ini ditujukan, dan manfaat yang akan didapat..."
                                    maxlength="1000"
                                    rows="6"
                                    required
                                ><?php echo htmlspecialchars($_POST['description'] ?? ''); ?></textarea>
                                <div style="font-size: 12px; color: var(--text-muted); margin-top: 4px; text-align: right;">
                                    <span id="descriptionCount">0</span>/1000 karakter
                                </div>
                            </div>

                            <!-- Action Buttons -->
                            <div class="form-actions">
                                <button type="submit" name="action" value="draft" class="btn btn-secondary" data-action="draft">
                                    Simpan Draft
                                </button>
                                <button type="submit" name="action" value="preview" class="btn btn-outline" data-action="preview">
                                    Pratinjau
                                </button>
                                <button type="submit" name="action" value="publish" class="btn btn-primary" data-action="publish">
                                    Publikasikan
                                </button>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </main>
    </div>

    <!-- Scripts -->
    <script src="/MindCraft-Project/assets/js/mentor_buat-kursus-baru.js"></script>
    <script>
        // Pass PHP data to JavaScript
        window.courseData = {
            categories: <?php echo json_encode($categories); ?>,
            mentorId: <?php echo (int)$mentorId; ?>,
            maxFileSize: 5242880, // 5MB in bytes
            allowedTypes: ['image/jpeg', 'image/png', 'image/gif', 'image/webp']
        };

        // Character counter for description
        document.addEventListener('DOMContentLoaded', function() {
            const description = document.getElementById('description');
            const counter = document.getElementById('descriptionCount');
            
            function updateCounter() {
                const length = description.value.length;
                counter.textContent = length;
                
                if (length > 1000) {
                    counter.style.color = '#E53E3E';
                } else if (length > 900) {
                    counter.style.color = '#DD6B20';
                } else {
                    counter.style.color = '#666';
                }
            }
            
            description.addEventListener('input', updateCounter);
            updateCounter(); // Initial count
        });

        // Show notifications
        <?php if ($successMessage): ?>
            setTimeout(() => {
                if (typeof showNotification === 'function') {
                    showNotification('<?php echo addslashes($successMessage); ?>', 'success');
                } else {
                    alert('<?php echo addslashes($successMessage); ?>');
                }
            }, 500);
        <?php endif; ?>

        <?php if ($errorMessage): ?>
            setTimeout(() => {
                if (typeof showNotification === 'function') {
                    showNotification('Terdapat kesalahan dalam form', 'error');
                }
            }, 500);
        <?php endif; ?>
    </script>
</body>
</html>