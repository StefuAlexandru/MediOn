<?php 
include "connection.php";
$cvv_error ="";
$cn_error ="";
date_default_timezone_set('Europe/Bucharest');
session_start();
if(!$_SESSION['logged_in'])
{
    header("location:login.php");
    exit();
}
if(isset($_POST['cvv'])){
    $cvv = $_POST['cvv'];
    if(strlen($cvv) !== 3 && strlen($cvv) !==4){
        $cvv_error = "Codul CVV trebuie sa fie format din 3 sau 4 cifre";
    }
}
if(isset($_POST['card_number'])){
    $cn = $_POST['card_number'];
    if(strlen($cn) != 16){
        $cn_error = "Numarul cardului trebuie sa fie format din 12 cifre";
    }
}

if (isset($_POST['submit'])) {
    if ($cvv_error|| $cn_error) {
        
    }
    else{
        $current_time = date('Y-m-d H:i:s');
        $one_month_later = strtotime('+1 months', strtotime($current_time)); 
        $formatted_expiration_time = date('Y-m-d H:i:s', $one_month_later);  


        $id = $_SESSION['user_id'];
        $sql ="UPDATE clients SET subscription = '$formatted_expiration_time' WHERE id='$id'";
        mysqli_query($con,$sql);
        Header("Location:subscription-succes.php");
    }
    
  }


?>



<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Pagina Abonamentului</title>
  <link rel="preconnect" href="https://fonts.googleapis.com">
  <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
  <link href="https://fonts.googleapis.com/css2?family=Pacifico&display=swap" rel="stylesheet">
  <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">
  <link rel="stylesheet" href="subscription.css"/>
</head>
<body>
<div class="subscription-container">
    <h1>Fă-ți Upgrade Contului!</h1>
    <p>Alege un plan de abonament care se potrivește nevoilor tale.</p>
    <form method="post" action="<?php echo $_SERVER['PHP_SELF'];?>">
      <div class="plan-options">
        <div class="plan-card">
          <h3>Plan Basic</h3>
          <p>$5 pe lună</p>
          <ul>
            <li>Funcționalități limitate</li>
            <li>Suport standard</li>
          </ul>
          <input type="radio" name="plan" value="basic" required> Alege Planul
        </div>
        <div class="plan-card">
          <h3>Plan Premium</h3>
          <p>$10 pe lună</p>
          <ul>
            <li>Acces complet la funcționalități</li>
            <li>Suport prioritar</li>
          </ul>
          <input type="radio" name="plan" value="premium" required> Alege Planul
        </div>
      </div>
      <div class="payment-details">
        <h2>Detalii Plată</h2>
        <label for="card_number">Număr Card</label>
        <input type="text" id="card_number" name="card_number" placeholder="Introdu numărul cardului" required>
        <div style = "display:flex; align-items:center; color:red;">
            <i style="display:<?php if($cn_error!="") echo "block"; else echo "none";?>" class="bi bi-exclamation-circle"></i>
            <a style="padding-left:15px;"><?php echo $cn_error;?></a>
          </div>
        <div class="payment-details-row">
          <div>
            <label for="expiry_month">Luna Expirării</label>
            <input type="number" id="expiry_month" name="expiry_month" min="1" max="12" required>
          </div>
          <div>
            <label for="expiry_year">Anul Expirării</label>
            <input type="number" id="expiry_year" name="expiry_year" min="2024" required>
          </div>
        </div>
        <label for="cvv">CVV</label>
        <input type="text" id="cvv" name="cvv" placeholder="Introdu codul CVV" required>
        <div style = "display:flex; align-items:center; color:red;">
            <i style="display:<?php if($cvv_error!="") echo "block"; else echo "none";?>" class="bi bi-exclamation-circle"></i>
            <a style="padding-left:15px;"><?php echo $cvv_error;?></a>
          </div>
      </div>
      <div class="form-group-submit">
        <input type="submit" name="submit" value="Abonează-te Acum" >
      </div>
    </form>
  </div>
</body>
</html>
