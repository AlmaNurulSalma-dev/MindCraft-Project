<?php
class CourseModel {
    private $db;

    public function __construct($db) {
        $this->db = $db;
    }

    // Ambil kursus yang berstatus Published
    public function getPublishedCourses($limit = null) {
        $sql = "SELECT c.*, u.username, mp.full_name, mp.profile_picture
                FROM courses c
                JOIN users u ON c.mentor_id = u.id
                LEFT JOIN mentor_profiles mp ON mp.user_id = u.id
                WHERE c.status = 'Published'
                ORDER BY c.created_at DESC";

        if ($limit) {
            $sql .= " LIMIT ?";
            $stmt = $this->db->prepare($sql);
            $stmt->bind_param("i", $limit);
        } else {
            $stmt = $this->db->prepare($sql);
        }

        $stmt->execute();
        $result = $stmt->get_result();
        return $result->fetch_all(MYSQLI_ASSOC);
    }

    // Ambil detail kursus berdasarkan ID
    public function getCourseById($id) {
        $sql = "SELECT c.*, u.username, mp.full_name, mp.profile_picture
                FROM courses c
                JOIN users u ON c.mentor_id = u.id
                LEFT JOIN mentor_profiles mp ON mp.user_id = u.id
                WHERE c.id = ? AND c.status = 'Published'";
        $stmt = $this->db->prepare($sql);
        $stmt->bind_param("i", $id);
        $stmt->execute();
        $result = $stmt->get_result();
        return $result->fetch_assoc();
    }

    // Ambil modul berdasarkan course_id
    public function getModulesByCourseId($course_id) {
        $sql = "SELECT * FROM course_modules WHERE course_id = ? ORDER BY order_index ASC";
        $stmt = $this->db->prepare($sql);
        $stmt->bind_param("i", $course_id);
        $stmt->execute();
        $result = $stmt->get_result();
        return $result->fetch_all(MYSQLI_ASSOC);
    }

    // Ambil pelajaran berdasarkan module_id
    public function getLessonsByModuleId($module_id) {
        $sql = "SELECT * FROM course_lessons WHERE module_id = ? ORDER BY order_index ASC";
        $stmt = $this->db->prepare($sql);
        $stmt->bind_param("i", $module_id);
        $stmt->execute();
        $result = $stmt->get_result();
        return $result->fetch_all(MYSQLI_ASSOC);
    }

    // Ambil daftar semua kategori aktif
    public function getActiveCategories() {
        $sql = "SELECT * FROM course_categories WHERE is_active = 1 ORDER BY sort_order ASC";
        $result = $this->db->query($sql);
        return $result->fetch_all(MYSQLI_ASSOC);
    }

    // Ambil kursus berdasarkan mentee (untuk Kursus Saya)
    public function getCoursesByMentee($student_id) {
        $sql = "SELECT c.*, e.enrollment_date
                FROM enrollments e
                JOIN courses c ON e.course_id = c.id
                WHERE e.student_id = ? AND c.status = 'Published'";
        $stmt = $this->db->prepare($sql);
        $stmt->bind_param("i", $student_id);
        $stmt->execute();
        $result = $stmt->get_result();
        return $result->fetch_all(MYSQLI_ASSOC);
    }

    public function getMentorByCourseId($courseId) {
        $sql = "SELECT mp.* FROM mentor_profiles mp
                JOIN courses c ON c.mentor_id = mp.user_id
                WHERE c.id = ?";
        $stmt = $this->db->prepare($sql);
        $stmt->execute([$courseId]);
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }
    
    public function getLessonsGroupedByModule($courseId) {
        $sql = "SELECT l.*, m.id as module_id 
                FROM course_lessons l
                JOIN course_modules m ON m.id = l.module_id
                WHERE m.course_id = ?
                ORDER BY m.order_index, l.order_index";
        $stmt = $this->db->prepare($sql);
        $stmt->execute([$courseId]);
    
        $grouped = [];
        while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
            $grouped[$row['module_id']][] = $row;
        }
        return $grouped;
    }

    public function isAlreadyEnrolled($studentId, $courseId) {
        $stmt = $this->db->prepare("SELECT COUNT(*) FROM enrollments WHERE student_id = ? AND course_id = ?");
        $stmt->execute([$studentId, $courseId]);
        return $stmt->fetchColumn() > 0;
    }
    
    public function enrollStudent($studentId, $courseId) {
        $stmt = $this->db->prepare("INSERT INTO enrollments (student_id, course_id, enrollment_date) VALUES (?, ?, NOW())");
        return $stmt->execute([$studentId, $courseId]);
    }
    
    
}
