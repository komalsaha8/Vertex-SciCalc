<?php
session_start();
include 'db.php';
$error = "";
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $name = trim($_POST['name']);
    $email = trim($_POST['email']);
    $password = password_hash($_POST['password'], PASSWORD_DEFAULT);
    
    $check = $conn->prepare("SELECT id FROM users WHERE email = ?");
    $check->bind_param("s", $email);
    $check->execute();
    if($check->get_result()->num_rows > 0){
        $error = "Email already exists!";
    } else {
        $stmt = $conn->prepare("INSERT INTO users (name, email, password) VALUES (?, ?, ?)");
        $stmt->bind_param("sss", $name, $email, $password);
        if($stmt->execute()){
            header("Location: login.php");
            exit();
        }
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>VERTEX SciCalc - Register</title>
<style>
  @import url('https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&display=swap');
  *{margin:0;padding:0;box-sizing:border-box}
  body{background:#FFF6E9;font-family:'Inter',sans-serif;min-height:100vh;display:flex;flex-direction:column;align-items:center;padding:20px;}
  .top-title{font-size:52px;font-weight:800;color:#2D1B12;margin:40px 0 60px 0;letter-spacing:-1px;text-align:center;}
  .card{background:#FFFEFB;border:1.5px solid #C9B9A6;border-radius:22px;width:100%;max-width:580px;padding:36px 32px 28px 32px;box-shadow:0 4px 18px rgba(0,0,0,0.05);}
  .card-header{display:flex;align-items:center;justify-content:center;gap:10px;margin-bottom:22px;}
  .brand{font-weight:600;font-size:22px;color:#2D1B12;}
  .welcome{text-align:center;font-size:32px;font-weight:700;color:#2D1B12;margin-bottom:6px;}
  .subtitle{text-align:center;font-size:14px;color:#2D1B12;margin-bottom:26px;}
  .input-group{position:relative;display:flex;align-items:center;background:#FFF8ED;border:1.2px solid #C9B9A6;border-radius:12px;height:54px;margin-bottom:14px;overflow:hidden;}
  .icon-box{width:56px;height:100%;background:#F5E8D5;display:flex;align-items:center;justify-content:center;border-right:1px solid #E5D5C0;}
  .input-group input{flex:1;border:none;background:transparent;outline:none;font-size:15px;padding:0 14px;color:#2D1B12;}
  .eye{padding-right:16px;cursor:pointer;}
  .login-btn{width:100%;height:52px;background:#2D1B12;color:#FFF6E9;border:none;border-radius:12px;font-size:17px;font-weight:600;cursor:pointer;margin-top:10px;}
  .bottom-text{text-align:center;font-size:14px;color:#2D1B12;margin-top:18px;}
  .bottom-text a{color:#B07A6B;text-decoration:underline;font-weight:500;}
  .error{text-align:center;color:#B00020;font-size:13px;margin-bottom:12px;}
  .features{display:flex;gap:60px;margin-top:36px;}
  .feat{text-align:center;}
  .feat-circle{width:62px;height:62px;border-radius:50%;display:grid;place-items:center;margin:0 auto 8px auto;font-size:26px;}
  .feat:nth-child(1) .feat-circle,.feat:nth-child(3) .feat-circle{background:#DDE8C6}
  .feat:nth-child(2) .feat-circle{background:#F6DDD0}
  .feat p{font-weight:600;color:#2D1B12;font-size:15px;}
</style>
</head>
<body>
<h1 class="top-title">VERTEX SciCalc</h1>
<div class="card">
  <div class="card-header">
    <svg width="28" height="28" viewBox="0 0 24 24" fill="none" stroke="#2D1B12" stroke-width="2"><rect x="3" y="3" width="18" height="18" rx="3"/><rect x="6" y="5" width="12" height="4" rx="1"/><path d="M7 12h2M11 12h2M15 12h2M7 15h2M11 15h2M15 15h2M7 18h2M11 18h2M15 18h2"/></svg>
    <span class="brand">VERTEX SciCalc</span>
  </div>
  <h2 class="welcome">Welcome!</h2>
  <p class="subtitle">Create your account</p>
  <?php if($error){ echo "<p class='error'>$error</p>"; } ?>
  <form method="POST">
    <div class="input-group"><div class="icon-box">👤</div><input type="text" name="name" placeholder="Name" required></div>
    <div class="input-group"><div class="icon-box">✉️</div><input type="email" name="email" placeholder="Email" required></div>
    <div class="input-group"><div class="icon-box">🔒</div><input type="password" name="password" id="pass" placeholder="Password" required><span class="eye" onclick="togglePass()">👁️</span></div>
    <button type="submit" class="login-btn">Register</button>
    <div class="bottom-text">Already have account? <a href="login.php">Login</a></div>
  </form>
</div>
<div class="features">
  <div class="feat"><div class="feat-circle">⌨</div><p>Calculate</p></div>
  <div class="feat"><div class="feat-circle">📈</div><p>Visualise</p></div>
  <div class="feat"><div class="feat-circle">📖</div><p>Remember</p></div>
</div>
<script>
function togglePass(){
 const p=document.getElementById('pass');
 const eye=document.querySelector('.eye');
 if(p.type==='password'){p.type='text'; eye.textContent='🙈';} else {p.type='password'; eye.textContent='👁️';}
}
</script>
</body>
</html>