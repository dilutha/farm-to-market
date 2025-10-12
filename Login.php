<?php
session_start();
include 'db.php';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $email = $_POST['email'];
    $password = $_POST['password'];

    $stmt = $conn->prepare("SELECT * FROM users WHERE email=?");
    $stmt->bind_param("s", $email);
    $stmt->execute();
    $res = $stmt->get_result();

    if ($res->num_rows > 0) {
        $user = $res->fetch_assoc();
        if (password_verify($password, $user['password'])) {
            $_SESSION['user_id'] = $user['id'];
            $_SESSION['role'] = $user['role'];
            
            // Redirect by role
            if ($user['role'] === 'farmer') {
                header("Location: farmer_dashboard.php");
            } else {
                header("Location: buyer_dashboard.php");
            }
            exit();
        } else {
            $error = "Invalid password!";
        }
    } else {
        $error = "No user found!";
    }
}
?>
<!DOCTYPE html>
<html>
<head>
  <title>Login</title>
  <link rel="stylesheet" href="style.css">
</head>
<body>
<div class="form-container">
  <h2>Login</h2>
  <?php if(isset($error)) echo "<p class='error'>$error</p>"; ?>
  <form method="POST" onsubmit="return validateLogin();">
    <input type="email" name="email" id="email" placeholder="Email" required>
    <input type="password" name="password" id="password" placeholder="Password" required>
    <button type="submit">Login</button>
  </form>
  <p>New user? <a href="register_farmer.php">Register as Farmer</a> | <a href="register_buyer.php">Register as Buyer</a></p>
</div>
<script>
function validateLogin(){
  let email = document.getElementById("email").value;
  let pass = document.getElementById("password").value;
  if(email.trim() === "" || pass.trim() === ""){
    alert("All fields are required!");
    return false;
  }
  return true;
}
</script>
</body>
</html>
