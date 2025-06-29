<?php
require_once 'models/CourseModel.php';

class StudentController {
    private $courseModel;

    public function __construct($db) {
        $this->courseModel = new CourseModel($db);
    }

    public function dashboard() {
        $userId = $_SESSION['user_id'];
        $myCourses = $this->courseModel->getCoursesByMentee($userId);
        $recommendations = $this->courseModel->getRandomCourses();
        include 'views/mentee/dashboard.php';
    }

    public function kursus() {
        $courses = $this->courseModel->getAllPublishedCourses();
        include 'views/mentee/katalog.php';
    }

    public function kursusSaya() {
        $userId = $_SESSION['user_id'];
        $myCourses = $this->courseModel->getCoursesByMentee($userId);
        include 'views/mentee/kursus_saya.php';
    }

    public function detailKursus($id) {
        $course = $this->courseModel->getCourseById($id);
        $modules = $this->courseModel->getModulesByCourseId($id);
        include 'views/mentee/detail-kursus.php';
    }

    public function enroll($courseId) {
        session_start();
        $studentId = $_SESSION['user_id'];
    
        // Cek apakah sudah terdaftar
        $alreadyEnrolled = $this->courseModel->isAlreadyEnrolled($studentId, $courseId);
    
        if (!$alreadyEnrolled) {
            $this->courseModel->enrollStudent($studentId, $courseId);
        }
    
        // Redirect kembali ke halaman detail
        header("Location: index.php?page=detailKursus&id=" . $courseId);
        exit();
    }
    
}
