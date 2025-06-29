<?php
// Pastikan semua model yang dibutuhkan sudah di-require
require_once __DIR__ . '/../model/UserModel.php';
require_once __DIR__ . '/../model/CourseModel.php';
require_once __DIR__ . '/../model/StatsModel.php';
require_once __DIR__ . '/../config/Database.php';

class MentorController {
    private $db;
    private $userModel;
    private $courseModel;
    private $statsModel;

    // TERIMA OBJEK DATABASE SAAT DIBUAT
    public function __construct(Database $database) {
        // Simpan koneksi database
        $this->db = $database->connect();

        // BERIKAN KONEKSI DATABASE KE SETIAP MODEL
        $this->userModel = new UserModel($this->db);
        $this->courseModel = new CourseModel($this->db);
        $this->statsModel = new StatsModel($this->db);
    }

    /**
     * Mengambil data dasar mentor
     */
    public function getMentorData($mentorId) {
        return $this->userModel->getMentorById($mentorId);
    }

    /**
     * Mengambil semua data yang dibutuhkan untuk halaman Dashboard
     */
    public function getDashboardData($mentorId) {
        // ... (Fungsi ini dan lainnya tidak perlu diubah)
        $totalRevenue = $this->statsModel->getTotalRevenueByMentorId($mentorId);
        $totalWithdrawals = $this->statsModel->getTotalWithdrawalsByMentorId($mentorId);

        return [
            'totalCourses' => $this->courseModel->getCourseCountByMentorId($mentorId),
            'totalMentees' => $this->statsModel->getTotalStudentsByMentorId($mentorId),
            'totalEarnings' => $totalRevenue,
            'availableBalance' => $totalRevenue - $totalWithdrawals,
            'monthlyChartData' => $this->statsModel->getMonthlyRevenueByMentorId($mentorId),
        ];
    }
    
    /**
     * Mengambil data untuk halaman Kursus Saya
     */
    public function getCoursesPageData($mentorId) {
        return [
            'courses' => $this->courseModel->getCoursesByMentorId($mentorId)
        ];
    }

    /**
     * Mengambil data untuk halaman Pendapatan
     */
    public function getRevenuePageData($mentorId) {
        $totalRevenue = $this->statsModel->getTotalRevenueByMentorId($mentorId);
        $totalWithdrawals = $this->statsModel->getTotalWithdrawalsByMentorId($mentorId);
        return [
            'total_revenue' => $totalRevenue,
            'total_withdrawals' => $totalWithdrawals,
            'available_balance' => $totalRevenue - $totalWithdrawals
        ];
    }

    /**
     * Mengambil data untuk halaman Riwayat Penarikan
     */
    public function getPayoutHistoryPageData($mentorId) {
        return [
            'payouts' => $this->statsModel->getPayoutHistoryByMentorId($mentorId)
        ];
    }

    /**
     * Mengambil data untuk halaman Analitik
     */
     public function getAnalyticsPageData($mentorId) {
        return [
            'totalCourses' => $this->courseModel->getCourseCountByMentorId($mentorId),
            'totalStudents' => $this->statsModel->getTotalStudentsByMentorId($mentorId),
            'monthlyRevenueData' => $this->statsModel->getMonthlyRevenueByMentorId($mentorId)
        ];
     }

     public function getRecentEarnings($mentorId, $limit = 5) {
        // Memanggil method yang ada di StatsModel
        return $this->statsModel->getRecentEarningsByMentorId($mentorId, $limit);
    }
}
?>