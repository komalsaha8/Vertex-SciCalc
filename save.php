<?php include 'db.php';
if(isset($_SESSION['user_id'])){
 $expr=$_POST['expr']; $result=$_POST['result']; $uid=$_SESSION['user_id'];
 $conn->query("INSERT INTO calculations(user_id, expression, result) VALUES('$uid','$expr','$result')");
 echo "saved";
}
?>