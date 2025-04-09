<?php
    include "connection.php";
    include "client.php";
    $client = new Client();
    $fn_error = "";
    $ln_error = "";
    $us_error = "";
    $em_error = "";
    $ps_error = "";
    $cps_error = "";
    $ph_error = "";
    if(isset($_POST['firstName'])){
      $fn_error = $client->setFirstName($_POST['firstName']);
      if(empty($_POST['firstName'])){
        $fn_error = "Please enter your first name.";
      }
    }
    if(isset($_POST['lastName'])){
      $ln_error = $client->setLastName($_POST['lastName']);
      if(empty($_POST['lastName'])){
        $ln_error = "Please enter your last name.";
      }
    }
    if(isset($_POST['username'])){
        $username = trim($_POST['username']);
        if (empty($username)) {
          $us_error = "Please enter a username.";
        }
        else{
          $sql = "SELECT userName FROM clients WHERE userName = '$username'";
          $result = mysqli_query($con, $sql);
          if (mysqli_fetch_row($result)) {
            $us_error = "Username already exists!";
          } else {
            $client->username = $username;
          }
          mysqli_free_result($result);
        }
    }
    if(isset($_POST['email'])){
      $email = $_POST['email'];
      if (empty($email)) {
        $em_error = "Please enter an email address.";
      }
      else{
        $sql = "SELECT email FROM clients WHERE email = '$email'";
        $result = mysqli_query($con, $sql);
        if(mysqli_fetch_row($result)){
          $em_error = "Email already exists!";
        }
        else{
          $em_error = $client->setEmail($email);
        }
        mysqli_free_result($result);
      }
    }
    if(isset($_POST["password"])){
      $password = $_POST["password"];
      if (empty($password)) {
        $ps_error = "Please enter a password!";
      }
      else{
        $client->setPassword($password);

      }
    }
    if(isset($_POST['confirmPassword'])){
      $confirmPassword = $_POST['confirmPassword'];
      if (empty($confirmPassword)) {
        $cps_error = "Please confirm your password!";
      }
      else{
        if($password != $confirmPassword){
          $cps_error = "Passwords do not match!";
        }
      }
    }
    if(isset($_POST['phone'])){
      $phone = $_POST['phone'];
      if (empty($phone)) {
        $ph_error = "Please enter your phone number!";
      }
      else{
        $sql = "SELECT phoneNumber FROM clients WHERE phoneNumber = '$phone'";
        $result = mysqli_query($con, $sql);
        if(mysqli_fetch_row($result)){
          $ph_error = "This phone number already exist!";
        }
        else{
          $ph_error = $client->setPhoneNumber($phone);
        }
        mysqli_free_result($result);
      }
    }
    $all_valid = true; 

    if (isset($_POST['submit'])) {
      if ($fn_error || $ln_error || $us_error || $em_error || $ps_error || $cps_error || $ph_error) {
        $all_valid = false;
      }
      if ($all_valid) {
          $result = $client->addClientInDatabase($con);
          if ($result) {
            Header("Location: login.php");
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
    <title>Join Us!</title>
    <link rel="stylesheet" href="login.css"/>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Pacifico&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">
    <script src="password_strength.js"></script>
    <script src="password_visibility.js"></script>
     <style>
      .password-strength-bar {
      width: 100%;
      height: 10px;
      background-color: lightgreen;
      border-radius: 5px;
      margin-left: 10px;
      margin-top:10px;
    }
    .password-strength-fill {
      height: 100%;
      width:0%;
      background-color: green; 
      border-radius: 5px;
      transition: width 0.5s ease-in-out; 
    }
      </style>
  </head> 
  <body>
  <div class="login-container">
      <h1>Join the Community!</h1>
      <form name="signup" method="post" action="<?php echo $_SERVER['PHP_SELF'];?>">
          <div class="form-group">
            <label for="firstName" >First Name</label>
            <input type="text" name="firstName"   placeholder="Enter your first name">
            <div style = "display:flex; align-items:center; color:red;">
            <i style="display:<?php if($fn_error!="") echo "block"; else echo "none";?>" class="bi bi-exclamation-circle"></i>
            <a style="padding-left:15px;"><?php echo $fn_error;?></a>
            </div>
          </div>
        <div class="form-group">
          <label for="lastName">Last Name</label>
          <input type="text" name="lastName" placeholder="Enter your last name">
          <div style = "display:flex; align-items:center; color:red;">
            <i style="display:<?php if($ln_error!="") echo "block"; else echo "none";?>" class="bi bi-exclamation-circle"></i>
            <a style="padding-left:15px;"><?php echo $ln_error;?></a>
            </div>
        </div>
        <div class="form-group">
          <label for="username">Username</label>
          <input type="text" name="username" placeholder="Create your username">
          <div style = "display:flex; align-items:center; color:red;">
            <i style="display:<?php if($us_error!="") echo "block"; else echo "none";?>" class="bi bi-exclamation-circle"></i>
            <a style="padding-left:15px;"><?php echo $us_error;?></a>
            </div>
        </div>
        <div class="form-group">
          <label for="email">Email Address</label>
          <input type="text" name="email" placeholder="Enter your email address" >
          <div style = "display:flex; align-items:center; color:red;">
            <i style="display:<?php if($em_error!="") echo "block"; else echo "none";?>" class="bi bi-exclamation-circle"></i>
            <a style="padding-left:15px;"><?php echo $em_error;?></a>
          </div>
        </div>
        <div class="form-group">
          <label for="password">Password</label>
          <div class="password-container">
            <input type="password" name="password" id="password"  placeholder="Enter a strong password" onkeyup="checkPasswordStrength()">
            <i class="bi bi-eye-slash" id="togglePassword" onclick="togglePasswordVisibility()"></i>
          </div>
          <div class="password-strength-bar">
            <div class="password-strength-fill" id="strength-bar"></div>
          </div>
          <div style = "display:flex; align-items:center; color:red;">
            <i style="display:<?php if($ps_error!="") echo "block"; else echo "none";?>" class="bi bi-exclamation-circle"></i>
            <a style="padding-left:15px;"><?php echo $ps_error;?></a>
          </div>
        </div>
        <div class="form-group">
          <label for="confirmPassword">Confirm Password</label>
          <input type="password" name="confirmPassword" placeholder="Retype your password">
          <div style = "display:flex; align-items:center; color:red;">
            <i style="display:<?php if($cps_error!="") echo "block"; else echo "none";?>" class="bi bi-exclamation-circle"></i>
            <a style="padding-left:15px;"><?php echo $cps_error;?></a>
          </div>
        </div>
        <div class="form-group">
          <label for="phone">Phone Number</label>
          <input type="text" name="phone" placeholder="Enter your phone number">
          <div style = "display:flex; align-items:center; color:red;">
            <i style="display:<?php if($ph_error!="") echo "block"; else echo "none";?>" class="bi bi-exclamation-circle"></i>
            <a style="padding-left:15px;"><?php echo $ph_error;?></a>
          </div>
         
        </div>
        <div class="form-group-submit">
          <input type="submit" name="submit" value="Sign Up!">
          <p>Already a member? <a href="login.php">Log In!</a></p>
        </div>
      </form>
    </div>
  
  </body>
  </html>
