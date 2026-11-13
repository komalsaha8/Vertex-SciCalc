<?php
session_start();
if (!isset($_SESSION['user_id'])) { header("Location: login.php"); exit(); }
include 'db.php'; 
if(!isset($conn) && isset($con)){ $conn = $con; }

$user_id = $_SESSION['user_id'];
$user = $_SESSION['user_name'] ?? 'Komal Saha';

$conn->query("CREATE TABLE IF NOT EXISTS calculations (
  id INT AUTO_INCREMENT PRIMARY KEY,
  user_id VARCHAR(100) NOT NULL,
  expression VARCHAR(500) NOT NULL,
  result VARCHAR(500) NOT NULL,
  created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
)");

if(isset($_POST['save_hist'])){
  $exp = $conn->real_escape_string($_POST['exp']);
  $res = $conn->real_escape_string($_POST['res']);
  $conn->query("INSERT INTO calculations (user_id, expression, result) VALUES ('$user_id', '$exp', '$res')");
  exit();
}
if(isset($_GET['get_hist'])){
  header('Content-Type: application/json');
  $q = $conn->query("SELECT expression, result FROM calculations WHERE user_id='$user_id' ORDER BY id DESC LIMIT 50");
  $data=[]; 
  if($q){ while($r=$q->fetch_assoc()){ $data[]=$r; } }
  echo json_encode($data); exit();
}
if(isset($_POST['clear_hist'])){
  $conn->query("DELETE FROM calculations WHERE user_id='$user_id'"); exit();
}
?>
<!DOCTYPE html>
<html>
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>VERTEX SciCalc</title>
<style>
*{margin:0;padding:0;box-sizing:border-box;font-family:Arial}
body{background:#f2ece1;display:flex;justify-content:center;padding:8px}
.box{width:100%;max-width:1100px;background:#fffdf5;border:1.5px solid #d5cdb8;border-radius:14px;padding:12px}
.head{display:flex;justify-content:space-between;font-size:13px;margin-bottom:8px}
.screen{width:100%;height:80px;background:#d6e7c7;border-radius:12px;text-align:right;font-size:30px;padding:14px;margin-bottom:10px;border:1.5px solid #b6c7a3}
.main{display:grid;grid-template-columns:1.8fr 1fr;gap:10px}
.panel{border:1px solid #e0d9c5;border-radius:10px;padding:8px;background:#fffef7}
.t{font-size:11px;font-weight:800;margin-bottom:6px}
.fgrid{display:grid;grid-template-columns:repeat(6,1fr);gap:5px}
.ngrid{display:grid;grid-template-columns:repeat(5,1fr);gap:5px}
button{height:42px;border:none;border-radius:8px;font-weight:700;cursor:pointer;font-size:10.5px}
.bf{background:#211410;color:#f5e8c9}.bn{background:#fdfbf1;border:1.2px solid #d8d0b6;color:#222;font-size:14px}
.bd{background:#b93232;color:#fff}.ba{background:#d67a32;color:#fff}.bg{background:#3a9e40;color:#fff;font-size:20px}.bo{background:#fffef7;border:1.2px solid #d8d0b6;font-size:14px}
</style>
</head>
<body>
<div class="box">
<div class="head"><b>VERTEX SciCalc - Advance</b><span>Hi, <?php echo htmlspecialchars($user); ?> | <a href="logout.php" style="color:green;text-decoration:none">Logout</a></span></div>
<input id="dis" class="screen" value="0" readonly>
<div class="main">
<div class="panel"><div class="t">FUNCTIONS</div><div class="fgrid">
<button class="bf" onclick="addF('1/(')">x⁻¹</button><button class="bf" onclick="addF('PI')">CONST</button><button class="bf" onclick="frac()">a b/c</button><button class="bf" onclick="addF('sqrt(')">√</button><button class="bf" onclick="addF('**2')">x²</button><button class="bf" onclick="addF('**')">^</button>
<button class="bf" onclick="addF('log10(')">log</button><button class="bf" onclick="addF('ln(')">ln</button><button class="bf" onclick="neg()">(-)</button><button class="bf" onclick="addF('dms(')">°'"</button><button class="bf" onclick="addF('sinh(')">hyp</button><button class="bf" onclick="addF('sin(')">sin</button>
<button class="bf" onclick="addF('cos(')">cos</button><button class="bf" onclick="addF('tan(')">tan</button><button class="bf" onclick="addF('asin(')">sin⁻¹</button><button class="bf" onclick="addF('acos(')">cos⁻¹</button><button class="bf" onclick="addF('atan(')">tan⁻¹</button><button class="bf" onclick="store()">STO</button>
<button class="bf" onclick="recall()">RCL</button><button class="bf" onclick="eng()">ENG</button><button class="bf" onclick="addF('(')">(</button><button class="bf" onclick="addF(')')">)</button><button class="bf" onclick="addF(',')">,</button><button class="bf" onclick="memAdd()">M+</button>
<button class="bf" onclick="addF('cbrt(')">³√</button><button class="bf" onclick="addF('**3')">x³</button><button class="bf" onclick="addF('ran()')">Ran#</button><button class="bf" onclick="addF('fact(')">n!</button><button class="bf" onclick="addF('P(')">nPr</button><button class="bf" onclick="addF('C(')">nCr</button>
<button class="bf" onclick="calcIntegral()">∫dx</button><button class="bf" onclick="calcDeriv()">d/dx</button><button class="bf" onclick="solve()">SOLVE</button><button class="bf" onclick="addF('10**(')">10ˣ</button><button class="bf" onclick="addF('exp(')">eˣ</button><button class="bf" onclick="toggleDeg()" id="degBtn">DEG</button>
</div></div>
<div class="panel"><div class="t">NUMBERS</div><div class="ngrid">
<button class="bn" onclick="addF('7')">7</button><button class="bn" onclick="addF('8')">8</button><button class="bn" onclick="addF('9')">9</button><button class="bd" onclick="back()">DEL</button><button class="ba" onclick="clr()">AC</button>
<button class="bn" onclick="addF('4')">4</button><button class="bn" onclick="addF('5')">5</button><button class="bn" onclick="addF('6')">6</button><button class="bo" onclick="addF('*')">×</button><button class="bo" onclick="addF('/')">÷</button>
<button class="bn" onclick="addF('1')">1</button><button class="bn" onclick="addF('2')">2</button><button class="bn" onclick="addF('3')">3</button><button class="bo" onclick="addF('+')">+</button><button class="bo" onclick="addF('-')">-</button>
<button class="bn" onclick="addF('0')">0</button><button class="bn" onclick="addF('.')">.</button><button class="bn" onclick="addF('*10**')">×10ˣ</button><button class="ba" onclick="useAns()">Ans</button><button class="bg" onclick="calculate()">=</button>
</div></div>
</div>
<div style="margin-top:10px;border:1px solid #ddd;border-radius:10px;padding:8px;background:#fff;font-size:12px"><b>HISTORY</b><div id="hList" style="margin-top:6px">Loading...</div><div style="text-align:right"><button onclick="clearHist()" style="background:#b93232;color:#fff;padding:6px 14px;border-radius:6px;border:none;margin-top:6px">Clear</button></div></div>
</div>
<script>
let dis=document.getElementById('dis'), ans=0, mem=0, sto=0, isDeg=true;
function addF(v){ if(dis.value=='0'||dis.value=='Error') dis.value=''; dis.value+=v; }
function clr(){ dis.value='0'; } function back(){ dis.value=dis.value.slice(0,-1); if(dis.value=='') dis.value='0'; }
function useAns(){ addF(ans); } function neg(){ if(dis.value.startsWith('-')) dis.value=dis.value.slice(1); else dis.value='-'+dis.value; }
function toggleDeg(){ isDeg=!isDeg; document.getElementById('degBtn').innerText=isDeg?'DEG':'RAD'; }
function _eval(expr){ return Function('PI','E','Ans','"use strict";return('+expr+')')(Math.PI, Math.E, ans); }
function calculate(){ try{ let o=dis.value; let r=Function('sin','cos','tan','sqrt','log10','ln','PI','E','Ans','"use strict";return('+o.replace(/log\(/g,'log10(').replace(/PI/g,'3.14159')+')')(Math.sin,Math.cos,Math.tan,Math.sqrt,Math.log10,Math.log,Math.PI,Math.E,ans); r=parseFloat(Number(r).toPrecision(12)); ans=r; dis.value=r; fetch('',{method:'POST',headers:{'Content-Type':'application/x-www-form-urlencoded'},body:'save_hist=1&exp='+encodeURIComponent(o)+'&res='+encodeURIComponent(r)}).then(()=>loadHist()); }catch(e){ dis.value='Error'; } }
function loadHist(){ fetch('?get_hist=1').then(r=>r.json()).then(data=>{ if(data.length==0){ document.getElementById('hList').innerHTML='No calculations yet'; return; } document.getElementById('hList').innerHTML=data.map(d=>`<div style='padding:3px 0;border-bottom:1px dashed #eee'>${d.expression} = ${d.result}</div>`).join(''); }); }
function clearHist(){ if(!confirm('Clear all history?')) return; fetch('',{method:'POST',headers:{'Content-Type':'application/x-www-form-urlencoded'},body:'clear_hist=1'}).then(()=>loadHist()); }
loadHist();
</script>
</body>
</html>