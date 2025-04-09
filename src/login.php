<?php
include "connection.php"; 

$username = "";
$password = "";
$us_error = "";
$ps_error = "";
$captcha_error = "";
$remember_me = false;
setcookie('isLike', 'false', time() + (86400 * 30));

function generateMathProblem() {
  $operation = rand(1, 4); 
  $num1 = rand(10, 99);
  $num2 = rand(1, $num1 - 1);

  switch ($operation) {
    case 1:
      $symbol = '+';
      $result = $num1 + $num2;
      break;
    case 2:
      $symbol = '-';
      $result = $num1 - $num2;
      break;
    case 3:
      $symbol = 'x'; 
      $result = $num1 * $num2;
      break;
    case 4:
      while ($num1 % $num2 != 0) {
        $num1 = rand(10, 99);
      }
      $symbol = '/';
      $result = $num1 / $num2;
      break;
  }

  return array('num1' => $num1, 'num2' => $num2, 'symbol' => $symbol, 'result' => $result);
}

$captchaData = generateMathProblem();
if(isset($_COOKIE['remember_me'])){
  Header("Location:index.php");
  exit();
}

if (isset($_POST['submit'])) {
  $captcha_answer = $_POST['captcha'];
  $username = trim($_POST['username']);
  $password = $_POST['password'];
  $remember_me = isset($_POST['remember_me']);

  if ($captcha_answer != $_POST['captcha_answer']) {
    $captcha_error = "Incorrect CAPTCHA answer. Please try again.";
  } else {
    if (empty($username) || empty($password)) {
      $us_error = "Username cannot be empty.";
      $ps_error = "Password cannot be empty.";
    } else {
      $sql = "SELECT id,username,password,admin FROM clients WHERE username = '$username'";
      $result = mysqli_query($con, $sql);

      if ($result) {
        $row = mysqli_fetch_assoc($result);

        if ($row == null) {
          $us_error = "Invalid username!";
        } else {
          $uncode_password = base64_decode($row['password']);
          if ($password != $uncode_password) {
            $ps_error = "Incorrect password!";
          } else {
            $expire_time = time() + 3600 * 24 * 7;
            session_start();
            $_SESSION['user_id'] = $row['id'] ;
            $_SESSION['logged_in'] = true;
            $_SESSION['isAdmin'] = $row['admin'];
            if ($remember_me) {
              setcookie('remember_me', $row['id'], $expire_time, '/');
            } else {
              setcookie('remember_me', '', time() - 3600, "/");
            }

            header("Location: index.php");
            exit();
          }
        }
      } else {
        echo "Error: " . mysqli_error($con); 
      }
      mysqli_free_result($result);
    }
  }
}
?>



  <!DOCTYPE html>
  <html lang="en">
  <head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Log In</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Pacifico&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">
    <link rel="stylesheet" href="login.css"/>
    <script src="password_visibility.js"></script>
  </head>
  <body>
    
  <div class="login-container">
      <h1>Welcome Back!</h1>
      
      <form name="login" method="post" action="<?php echo $_SERVER['PHP_SELF'];?>">
        <div class="form-group">
          <label for="username">Username</label>
          <input type="text" name="username" placeholder="Enter your username" value="<?php if(isset($_COOKIE['remember_me'])) echo $username; else echo "";?>">
          <div style = "display:flex; align-items:center; color:red;">
              <i style="display:<?php if($us_error!="") echo "block"; else echo "none";?>" class="bi bi-exclamation-circle"></i>
              <a style="padding-left:15px;"><?php echo $us_error;?></a>
            </div>
        </div>
        <div class="form-group">
          <label for="password">Password</label>
          <div class="password-container">
              <input type="password" name="password" id="password" placeholder="Enter your password" value="<?php if(isset($_COOKIE['remember_me'])) echo $password; else echo "";?>">
              <i class="bi bi-eye-slash" id="togglePassword" onclick="togglePasswordVisibility()"></i>
          </div>
          <div style = "display:flex; align-items:center; color:red;">
              <i style="display:<?php if($ps_error!="") echo "block"; else echo "none";?>" class="bi bi-exclamation-circle"></i>
              <a style="padding-left:15px;"><?php echo $ps_error;?></a>
          </div>
        </div>
        <div class="form-group">
          <label for="captcha">What is <?php echo $captchaData['num1'].$captchaData['symbol'].$captchaData['num2']."=";?> ?</label>
          <input type="text" name="captcha" id="captcha" placeholder="Enter your answer" >
          <input type="hidden" name="captcha_answer" value="<?php echo $captchaData['result']; ?>">
          <div style = "display:flex; align-items:center; color:red;">
              <i style="display:<?php if($captcha_error!="") echo "block"; else echo "none";?>" class="bi bi-exclamation-circle"></i>
              <a style="padding-left:15px;"><?php echo $captcha_error;?></a>
          </div>
        </div>
        <div class="form-group remember-me">
          <input type="checkbox" name="remember_me" id="remember_me" <?php if(isset($_COOKIE['remember_me'])) echo 'checked'; ?>>
          <label for="remember_me">Remember Me!</label>
        
        </div>
        <div class="form-group-submit">
          <input type="submit" name="submit" value="Log In!" >
          <p>Don't have an account? <a href="signup.php">Sign Up!</a></p>
        
        </div>
      </form>
    </div>
  </body>
  </html>