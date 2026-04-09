<?php
error_reporting(E_ALL);
ini_set('display_errors', '1');

$conn = new mysqli('localhost', 'root', '', 'attendance');
$db_error = false;
if ($conn->connect_error) {
    $db_error = true;
} else {
    $conn->set_charset('utf8mb4');
}

$message = '';
$allowed_statuses = ['มา', 'สาย', 'ลา', 'ขาด'];

if (isset($_POST['save']) && !$db_error) {
    $sid = isset($_POST['student']) ? (int) $_POST['student'] : 0;
    $sub = isset($_POST['subject']) ? (int) $_POST['subject'] : 0;
    $status = isset($_POST['status']) ? trim($_POST['status']) : '';

    if ($sid > 0 && $sub > 0 && in_array($status, $allowed_statuses, true)) {
        $stmt = $conn->prepare(
            'INSERT INTO attendance (student_id, subject_id, status, date, time)
             VALUES (?, ?, ?, CURDATE(), CURTIME())'
        );
        $stmt->bind_param('iis', $sid, $sub, $status);
        if ($stmt->execute()) {
            $message = 'บันทึกเรียบร้อย';
        } else {
            $message = 'เกิดข้อผิดพลาดในการบันทึกข้อมูล';
        }
        $stmt->close();
    } else {
        $message = 'กรุณาเลือกข้อมูลให้ครบถ้วน';
    }
}

$students_result = $db_error ? null : $conn->query('SELECT id, name FROM students ORDER BY name');
$subjects_result = $db_error ? null : $conn->query('SELECT id, subject_name FROM subjects ORDER BY subject_name');
?>
<!DOCTYPE html>
<html lang="th">
<head>
<meta charset="utf-8">
<title>ระบบเช็กชื่อ (เวอร์ชันเสถียร)</title>
<style>
body{font-family:sans-serif;background:#f2f2f2;padding:20px}
.box{background:#fff;padding:20px;border-radius:8px;width:350px}
.notice{margin:0 0 12px;color:#0b5f2a;font-weight:bold}
.error{margin:0 0 12px;color:#9c2f2f;font-weight:bold}
</style>
</head>
<body>

<h2>📘 ระบบเช็กชื่อ</h2>
<div class="box">
  <?php if ($db_error): ?>
    <p class="error">ไม่สามารถเชื่อมต่อฐานข้อมูลได้</p>
    <hr>
  <?php endif; ?>
  <?php if ($message !== ''): ?>
    <p class="<?php echo ($message === 'บันทึกเรียบร้อย') ? 'notice' : 'error'; ?>">
      <?php echo htmlspecialchars($message, ENT_QUOTES, 'UTF-8'); ?>
    </p>
    <hr>
  <?php endif; ?>
  <form method="post">
    <label>นักเรียน</label><br>
    <select name="student" required>
      <option value="">เลือกนักเรียน</option>
      <?php if ($students_result): ?>
        <?php while ($s = $students_result->fetch_assoc()): ?>
          <option value="<?php echo (int) $s['id']; ?>">
            <?php echo htmlspecialchars($s['name'], ENT_QUOTES, 'UTF-8'); ?>
          </option>
        <?php endwhile; ?>
      <?php endif; ?>
    </select><br><br>

    <label>รายวิชา</label><br>
    <select name="subject" required>
      <option value="">เลือกรายวิชา</option>
      <?php if ($subjects_result): ?>
        <?php while ($s = $subjects_result->fetch_assoc()): ?>
          <option value="<?php echo (int) $s['id']; ?>">
            <?php echo htmlspecialchars($s['subject_name'], ENT_QUOTES, 'UTF-8'); ?>
          </option>
        <?php endwhile; ?>
      <?php endif; ?>
    </select><br><br>

    <label>สถานะ</label><br>
    <select name="status" required>
      <?php foreach ($allowed_statuses as $status_option): ?>
        <option value="<?php echo htmlspecialchars($status_option, ENT_QUOTES, 'UTF-8'); ?>">
          <?php echo htmlspecialchars($status_option, ENT_QUOTES, 'UTF-8'); ?>
        </option>
      <?php endforeach; ?>
    </select><br><br>

    <button name="save">เช็กชื่อ</button>
  </form>
</div>

</body>
</html>
