<?php
$page_title = "Riwayat Penarikan";
$page_css = "mentor_riwayat-penarikan.css"; // CSS spesifik
include_once __DIR__ . '/../../templates/header_mentor.php'; 

if (!function_exists('format_rupiah')) { function format_rupiah($number) { $number = $number ?? 0; return 'Rp ' . number_format($number, 0, ',', '.'); } }
?>

<div class="history-table-container">
    <table>
        <thead><tr><th>ID Penarikan</th><th>Tanggal</th><th>Jumlah</th><th>Status</th></tr></thead>
        <tbody>
            <?php if (empty($payouts)): ?>
                <tr><td colspan="4">Belum ada riwayat penarikan dana.</td></tr>
            <?php else: ?>
                <?php foreach ($payouts as $payout): ?>
                    <tr>
                        <td>#<?php echo htmlspecialchars($payout['payout_id']); ?></td>
                        <td><?php echo date('d F Y, H:i', strtotime($payout['payout_date'])); ?></td>
                        <td><?php echo format_rupiah($payout['amount']); ?></td>
                        <td><span class="status-badge status-<?php echo htmlspecialchars(strtolower($payout['status'])); ?>"><?php echo htmlspecialchars(ucfirst($payout['status'])); ?></span></td>
                    </tr>
                <?php endforeach; ?>
            <?php endif; ?>
        </tbody>
    </table>
</div>

<script src="/MindCraft-Project/assets/js/mentor_riwayat-penarikan.js"></script>