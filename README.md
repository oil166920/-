<!DOCTYPE html>
<html lang="th">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>ฝากเงิน - ระบบพ่อครู</title>
<style>
body {font-family: Arial, sans-serif; background-color: #1a1a1a; color: #FFD700; margin:0; padding:0;}
.container {max-width: 500px; margin:50px auto; padding:20px; background-color:#222; border-radius:10px; box-shadow:0 0 20px rgba(255,215,0,0.5);}
h2 {color:#FFD700; text-align:center;}
input, select, button {width:100%; padding:10px; margin:10px 0; border:none; border-radius:5px;}
button {background-color:#FFD700; color:#1a1a1a; font-weight:bold; cursor:pointer;}
button:hover {background-color:#e6c200;}
.history {margin-top:20px; background-color:#111; padding:10px; border-radius:5px; max-height:200px; overflow-y:auto;}
</style>
</head>
<body>

<div class="container" id="loginDiv">
  <h2>เข้าสู่ระบบ</h2>
  <input type="text" id="idCard" placeholder="กรอกเลขบัตรประชาชน">
  <button id="loginBtn">เข้าสู่ระบบ</button>
</div>

<div class="container" id="depositDiv" style="display:none;">
  <h2>ฝากเงิน</h2>

  <select id="accountSelect"></select>
  <input type="number" id="depositAmount" placeholder="จำนวนเงินฝาก">
  <button id="depositBtn">ฝากเงิน</button>

  <div class="history" id="historyDiv">
    <strong>ประวัติการฝาก:</strong>
    <ul id="historyList"></ul>
  </div>

  <div id="adminDiv" style="display:none;">
    <h3>ผู้จัดการระบบ - เพิ่มบัญชี</h3>
    <input type="text" id="newAccountName" placeholder="ชื่อบัญชี / ช่องทาง">
    <button id="addAccountBtn">เพิ่มบัญชี</button>
  </div>
</div>

<script>
let accounts = [
  "TrueMoney 063765934 (ณัฐวัตร ชูปรารมย์)",
  "กรุงเทพ 977-0-270164 / พร้อมเพย์ 063-769-5934 (ณัฐวัตร ชูปรารมย์)",
  "กรุงไทย 1220586870 / พร้อมเพย์ 115-9900-534708 (ณัฐวัตร ชูปรารมย์)"
];

const managerId = "1159900534708";

function populateAccounts() {
  const select = document.getElementById('accountSelect');
  select.innerHTML = "";
  accounts.forEach(acc => {
    const option = document.createElement('option');
    option.value = acc;
    option.textContent = acc;
    select.appendChild(option);
  });
}

document.getElementById('loginBtn').addEventListener('click', function() {
  const id = document.getElementById('idCard').value.trim();
  if(!id) { alert("กรุณากรอกเลขบัตรประชาชน"); return; }
  document.getElementById('loginDiv').style.display = 'none';
  document.getElementById('depositDiv').style.display = 'block';
  populateAccounts();

  if(id === managerId) {
    document.getElementById('adminDiv').style.display = 'block';
    alert("เข้าสู่ระบบผู้จัดการเรียบร้อย");
  } else {
    alert("เข้าสู่ระบบเรียบร้อย");
  }
});

document.getElementById('depositBtn').addEventListener('click', function() {
  const amount = parseFloat(document.getElementById('depositAmount').value);
  const account = document.getElementById('accountSelect').value;
  if(amount > 0) {
    alert(`ฝากเงิน ${amount} บาท เข้า ${account} เรียบร้อย`);
    const li = document.createElement('li');
    li.textContent = `ฝาก ${amount} บาท เข้า ${account}`;
    document.getElementById('historyList').prepend(li);
    document.getElementById('depositAmount').value = '';
  } else {
    alert("กรุณากรอกจำนวนเงินที่ถูกต้อง");
  }
});

document.getElementById('addAccountBtn').addEventListener('click', function() {
  const newAcc = document.getElementById('newAccountName').value.trim();
  if(newAcc) {
    accounts.push(newAcc);
    populateAccounts();
    document.getElementById('newAccountName').value = '';
    alert(`เพิ่มบัญชี "${newAcc}" เรียบร้อย`);
  } else {
    alert("กรุณากรอกชื่อบัญชีใหม่");
  }
});
</script>

</body>
</html>
