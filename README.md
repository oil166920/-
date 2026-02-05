<!DOCTYPE html>
<html lang="th">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>ระบบเช็คชื่อด้วยคิวอาร์โค้ด</title>
  <style>
    :root {
      --bg: #0f172a;
      --panel: #111827;
      --accent: #38bdf8;
      --text: #e2e8f0;
      --muted: #94a3b8;
      --danger: #f97316;
    }
    * { box-sizing: border-box; }
    body {
      margin: 0;
      font-family: "Segoe UI", Tahoma, Geneva, Verdana, sans-serif;
      background: var(--bg);
      color: var(--text);
    }
    .wrapper {
      max-width: 960px;
      margin: 40px auto;
      padding: 20px;
    }
    h1, h2, h3 { margin-top: 0; }
    .panel {
      background: var(--panel);
      border-radius: 16px;
      padding: 24px;
      margin-bottom: 20px;
      box-shadow: 0 18px 40px rgba(15, 23, 42, 0.4);
    }
    label {
      display: block;
      font-weight: 600;
      margin-top: 12px;
      color: var(--muted);
    }
    input, select, textarea, button {
      width: 100%;
      padding: 12px 14px;
      margin-top: 6px;
      border-radius: 12px;
      border: 1px solid #1f2937;
      background: #0b1220;
      color: var(--text);
      font-size: 15px;
    }
    button {
      background: var(--accent);
      color: #0b1220;
      font-weight: 700;
      cursor: pointer;
      border: none;
      margin-top: 16px;
    }
    button.secondary {
      background: #1e293b;
      color: var(--text);
      border: 1px solid #334155;
    }
    .grid {
      display: grid;
      gap: 16px;
    }
    .grid.two { grid-template-columns: repeat(auto-fit, minmax(240px, 1fr)); }
    .grid.three { grid-template-columns: repeat(auto-fit, minmax(200px, 1fr)); }
    .pill {
      display: inline-block;
      padding: 6px 12px;
      border-radius: 999px;
      background: rgba(56, 189, 248, 0.15);
      color: var(--accent);
      font-size: 13px;
      font-weight: 600;
    }
    .list {
      margin: 0;
      padding-left: 20px;
      color: var(--muted);
    }
    .notice {
      background: rgba(249, 115, 22, 0.1);
      border: 1px solid rgba(249, 115, 22, 0.3);
      padding: 14px;
      border-radius: 12px;
      color: var(--danger);
      margin-top: 12px;
    }
    .hidden { display: none; }
    .card {
      border: 1px solid #1f2937;
      padding: 16px;
      border-radius: 16px;
      background: #0b1220;
    }
    .card strong { color: var(--accent); }
    .subject-tag {
      display: inline-flex;
      align-items: center;
      gap: 6px;
      padding: 6px 10px;
      border-radius: 999px;
      background: rgba(148, 163, 184, 0.2);
      font-size: 13px;
      margin: 4px 6px 0 0;
    }
  </style>
</head>
<body>
  <div class="wrapper">
    <h1>ระบบเช็คชื่อด้วยสแกนคิวอาร์โค้ด</h1>
    <p class="pill">รองรับ 3 ระบบ: ครู • นักเรียน • ผู้ดูแลระบบ</p>

    <div class="panel" id="loginPanel">
      <h2>เข้าสู่ระบบ</h2>
      <label for="roleSelect">เลือกบทบาท</label>
      <select id="roleSelect">
        <option value="teacher">ระบบครู</option>
        <option value="student">ระบบนักเรียน</option>
        <option value="admin">ระบบหลังบ้าน (ผู้ดูแลระบบ)</option>
      </select>
      <label for="accessCode">รหัสเข้าใช้งาน</label>
      <input type="password" id="accessCode" placeholder="ครู/แอดมินใช้ 1669 | นักเรียนใช้เลขประจำตัว">
      <button id="loginBtn">ยืนยันการเข้าสู่ระบบ</button>
      <div class="notice">
        ตัวอย่างนักเรียน: นาย ณัฐวัตร ชูปรารมย์ เลขที่ 11 ม.4 ห้อง 1 เลขประจำ 7526
      </div>
    </div>

    <div class="panel hidden" id="studentPanel">
      <h2>บัตรนักเรียน</h2>
      <div id="studentCard" class="card"></div>
      <button class="secondary" id="logoutStudent">ออกจากระบบ</button>
    </div>

    <div class="panel hidden" id="staffPanel">
      <h2>ศูนย์จัดการข้อมูลนักเรียน</h2>
      <p class="pill" id="staffRoleLabel"></p>
      <div class="grid two">
        <div class="card">
          <h3>เพิ่มรายชื่อนักเรียนด้วย QR</h3>
          <label for="qrInput">ข้อมูลจาก QR (รูปแบบ: ชื่อ|เลขที่|ระดับชั้น|ห้อง|เลขประจำตัว)</label>
          <textarea id="qrInput" rows="3" placeholder="ตัวอย่าง: นาย ณัฐวัตร ชูปรารมย์|11|ม.4|1|7526"></textarea>
          <button id="addStudentBtn">สแกนและเพิ่มรายชื่อ</button>
          <p class="notice">การเพิ่มรายชื่อทำได้เฉพาะครู/ผู้ดูแลระบบ</p>
        </div>
        <div class="card">
          <h3>ลบรายชื่อนักเรียน</h3>
          <label for="deleteId">ระบุเลขประจำตัวที่ต้องการลบ</label>
          <input type="text" id="deleteId" placeholder="เช่น 7526">
          <button id="deleteStudentBtn">ลบรายชื่อ</button>
        </div>
      </div>

      <div class="grid two" style="margin-top: 16px;">
        <div class="card">
          <h3>เพิ่มวิชาเรียน</h3>
          <label for="subjectName">ชื่อวิชา</label>
          <input type="text" id="subjectName" placeholder="เช่น คณิตศาสตร์พื้นฐาน">
          <label for="subjectCode">รหัสยืนยัน (1669)</label>
          <input type="password" id="subjectCode" placeholder="ใส่รหัสเพื่อยืนยัน">
          <button id="addSubjectBtn">เพิ่มวิชา</button>
        </div>
        <div class="card">
          <h3>รายการวิชา</h3>
          <div id="subjectList"></div>
        </div>
      </div>

      <div class="card" style="margin-top: 16px;">
        <h3>รายชื่อนักเรียนทั้งหมด</h3>
        <div id="studentList"></div>
      </div>
      <button class="secondary" id="logoutStaff">ออกจากระบบ</button>
    </div>
  </div>

  <script>
    const ACCESS_CODE = "1669";
    const students = [
      { name: "นาย ณัฐวัตร ชูปรารมย์", number: "11", grade: "ม.4", room: "1", id: "7526" }
    ];
    const subjects = ["ภาษาไทย", "คณิตศาสตร์", "วิทยาศาสตร์"];

    const loginPanel = document.getElementById("loginPanel");
    const studentPanel = document.getElementById("studentPanel");
    const staffPanel = document.getElementById("staffPanel");
    const studentCard = document.getElementById("studentCard");
    const studentList = document.getElementById("studentList");
    const subjectList = document.getElementById("subjectList");
    const staffRoleLabel = document.getElementById("staffRoleLabel");

    function renderStudents() {
      studentList.innerHTML = "";
      students.forEach((student) => {
        const card = document.createElement("div");
        card.className = "card";
        card.style.marginBottom = "12px";
        card.innerHTML = `
          <strong>${student.name}</strong><br>
          เลขที่ ${student.number} • ${student.grade} ห้อง ${student.room}<br>
          เลขประจำตัว: ${student.id}
        `;
        studentList.appendChild(card);
      });
    }

    function renderSubjects() {
      subjectList.innerHTML = "";
      subjects.forEach((subject) => {
        const span = document.createElement("span");
        span.className = "subject-tag";
        span.textContent = subject;
        subjectList.appendChild(span);
      });
    }

    function showStudentCard(student) {
      studentCard.innerHTML = `
        <h3>${student.name}</h3>
        <p>เลขที่ ${student.number}</p>
        <p>${student.grade} ห้อง ${student.room}</p>
        <p>เลขประจำตัว: <strong>${student.id}</strong></p>
        <p class="pill">สิทธิ์: นักเรียน (ดูบัตรเท่านั้น)</p>
      `;
    }

    document.getElementById("loginBtn").addEventListener("click", () => {
      const role = document.getElementById("roleSelect").value;
      const code = document.getElementById("accessCode").value.trim();

      if (role === "student") {
        const student = students.find((item) => item.id === code);
        if (!student) {
          alert("ไม่พบเลขประจำตัวนี้ในระบบ");
          return;
        }
        showStudentCard(student);
        loginPanel.classList.add("hidden");
        studentPanel.classList.remove("hidden");
        return;
      }

      if (code !== ACCESS_CODE) {
        alert("รหัสไม่ถูกต้อง (ครู/ผู้ดูแลระบบใช้ 1669)");
        return;
      }

      staffRoleLabel.textContent = role === "teacher" ? "สิทธิ์: ครู" : "สิทธิ์: ผู้ดูแลระบบ";
      loginPanel.classList.add("hidden");
      staffPanel.classList.remove("hidden");
      renderStudents();
      renderSubjects();
    });

    document.getElementById("addStudentBtn").addEventListener("click", () => {
      const raw = document.getElementById("qrInput").value.trim();
      if (!raw) {
        alert("กรุณาวางข้อมูลจาก QR");
        return;
      }
      const [name, number, grade, room, id] = raw.split("|").map((item) => item.trim());
      if (!name || !number || !grade || !room || !id) {
        alert("รูปแบบไม่ถูกต้อง กรุณาใช้: ชื่อ|เลขที่|ระดับชั้น|ห้อง|เลขประจำตัว");
        return;
      }
      if (students.some((student) => student.id === id)) {
        alert("เลขประจำตัวนี้มีอยู่แล้ว");
        return;
      }
      students.push({ name, number, grade, room, id });
      document.getElementById("qrInput").value = "";
      renderStudents();
      alert(`เพิ่ม ${name} เรียบร้อย`);
    });

    document.getElementById("deleteStudentBtn").addEventListener("click", () => {
      const id = document.getElementById("deleteId").value.trim();
      if (!id) {
        alert("กรุณากรอกเลขประจำตัว");
        return;
      }
      const index = students.findIndex((student) => student.id === id);
      if (index === -1) {
        alert("ไม่พบเลขประจำตัวนี้");
        return;
      }
      const removed = students.splice(index, 1)[0];
      document.getElementById("deleteId").value = "";
      renderStudents();
      alert(`ลบ ${removed.name} เรียบร้อย`);
    });

    document.getElementById("addSubjectBtn").addEventListener("click", () => {
      const subject = document.getElementById("subjectName").value.trim();
      const code = document.getElementById("subjectCode").value.trim();
      if (!subject) {
        alert("กรุณากรอกชื่อวิชา");
        return;
      }
      if (code !== ACCESS_CODE) {
        alert("รหัสยืนยันไม่ถูกต้อง (ต้องใช้ 1669)");
        return;
      }
      subjects.push(subject);
      document.getElementById("subjectName").value = "";
      document.getElementById("subjectCode").value = "";
      renderSubjects();
      alert(`เพิ่มวิชา ${subject} เรียบร้อย`);
    });

    document.getElementById("logoutStudent").addEventListener("click", () => {
      studentPanel.classList.add("hidden");
      loginPanel.classList.remove("hidden");
      document.getElementById("accessCode").value = "";
    });

    document.getElementById("logoutStaff").addEventListener("click", () => {
      staffPanel.classList.add("hidden");
      loginPanel.classList.remove("hidden");
      document.getElementById("accessCode").value = "";
      document.getElementById("qrInput").value = "";
      document.getElementById("deleteId").value = "";
    });
  </script>
</body>
</html>
