<?php include 'db.php'; ?>
<?php
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $name = $_POST['name'];
    $email = $_POST['email'];
    $contact = $_POST['contact'];
    $password = password_hash($_POST['password'], PASSWORD_BCRYPT);
    $role = "farmer";

    $stmt = $conn->prepare("INSERT INTO users (name,email,password,role,contact) VALUES (?,?,?,?,?)");
    $stmt->bind_param("sssss", $name,$email,$password,$role,$contact);

    if($stmt->execute()){
        header("Location: login.php?success=Farmer registered!");
    } else {
        $error = "Email already exists!";
    }
}
?>
<!DOCTYPE html>
<html>
<head>
  <title>Farmer Registration</title>
  <link rel="stylesheet" href="style.css">
</head>
<body>
<div class="form-container">
  <h2>Register as Farmer</h2>
  <?php if(isset($error)) echo "<p class='error'>$error</p>"; ?>
  <form method="POST" onsubmit="return validateRegister();">
    <input type="text" name="name" id="name" placeholder="Full Name" required>
    <input type="email" name="email" id="email" placeholder="Email" required>
    <input type="text" name="contact" id="contact" placeholder="Contact Number">
    <input type="password" name="password" id="password" placeholder="Password" required>
    <input type="password" id="confirmPassword" placeholder="Confirm Password" required>
    <button type="submit">Register</button>
  </form>
</div>
<script>
function validateRegister(){
  let pass = document.getElementById("password").value;
  let confirm = document.getElementById("confirmPassword").value;
  if(pass !== confirm){
    alert("Passwords do not match!");
    return false;
  }
  return true;
}
</script>
</body>
</html>
