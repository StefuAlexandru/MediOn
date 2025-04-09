<?php
require_once "connection.php";
session_start();
$userName="";
$email ="";
$phoneNumber="";
$profilePicture = "";
if(!isset($_SESSION['logged_in'])){
    header("Location: login.php");
    exit();
}else{
    $id = $_SESSION['user_id'];
    $sql = "SELECT userName,email,phoneNumber,profilePicture FROM clients WHERE id='$id'";
    $result = mysqli_query($con,$sql);
    if($result){
        $row = mysqli_fetch_assoc($result);
        $userName = $row['userName'];
        $email = $row['email'];
        $phoneNumber = $row['phoneNumber'];
        $profilePicture = $row['profilePicture'];
    }
    if(isset($_POST['back'])){
        header("Location:index.php");
        exit();
    }




    $newUserName=$userName;
    $newEmail =$email;
    $newPhoneNumber = $phoneNumber;
    $img_error ="";

    if(isset($_FILES['profile_picture']) && $_FILES['profile_picture']['error'] === UPLOAD_ERR_OK && $_FILES['profile_picture']['size'] > 0){

        $old_profile_picture = $profilePicture;  
        if ($old_profile_picture != "") { 
            $delete_path = "uploads/" . $old_profile_picture;
            if (file_exists($delete_path)) {
                unlink($delete_path); 
            }
        }

        $fileName = basename($_FILES['profile_picture']['name']);
        $fileSize = $_FILES['profile_picture']['size'];
        $tmpName = $_FILES['profile_picture']['tmp_name'];
        if($fileSize > 10 *1024 * 1024){
            $img_error = "File size should not exceed 10MB";
        }
        else {
            $validExtensions=['jpg' ,'png' ,'jpeg'];
            $imageExtension = explode(".",$fileName);
            $imageExtension = strtolower(end($imageExtension));
    
            if(!in_array($imageExtension,$validExtensions)){
                $img_error="Invalid extension file";
            }
            else {
                $newImageName = uniqid() . "." . $imageExtension;
                $target_dir = "uploads/";
                $target_file = $target_dir . $newImageName;
                move_uploaded_file($tmpName, $target_file);
                $sql = "UPDATE clients SET profilePicture ='$newImageName' WHERE id='$id'";
                mysqli_query($con,$sql)or die(mysqli_error($con));
                header('location:image-succes.php');
            }
        }
    }




    if(isset($_POST['save'])){
        if(!empty($_POST['username'])){
            $newUserName = $_POST['username'];
        }
        if(!empty($_POST['email'])){
            $newEmail = $_POST['email'];
        }
        if(!empty($_POST['phoneNumber'])){
            $newPhoneNumber = $_POST['phoneNumber'];
        }
        $sql = "UPDATE clients SET userName='$newUserName',email='$newEmail',phoneNumber='$newPhoneNumber' WHERE id='$id'";
        mysqli_query($con,$sql) or die(mysqli_error($con));
        header("Location: account.php");
    }

    if(isset($_POST['delete'])){
        
        $sql = "DELETE FROM clients WHERE id='$id'";
        mysqli_query($con,$sql) or die(mysqli_error($con));
        header("Location: login.php");
    }
}

?>






<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Setări cont</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Pacifico&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css">
    <link rel="stylesheet" href="account.css">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">

<script src='https://cdn.jsdelivr.net/npm/bootstrap@4.5.0/dist/js/bootstrap.bundle.min.js'></script>
</head>
<body>
<div class="wrapper bg-white mt-sm-5">
    <h4 class="pb-4 border-bottom">Setări cont</h4>
    <div class="d-flex align-items-start py-3 border-bottom">

        <img src="<?php echo $profilePicture != "" ? 'uploads/' . $profilePicture : 'assets/images/profile.png'; ?>"
            class="img" alt="">
        <form method="post" action ="<?php echo $_SERVER['PHP_SELF'];?> " enctype="multipart/form-data">
            <div class="pl-sm-4 pl-2" id="img-section" >
                <b>Poză de profil</b><br>
                <en style="font-size:15px">Fotografia trebuie sa fie in format .png .jpg sau .jpeg ,iar dimensiunea de maxim 10MB.</p>
                <input type="file" name="profile_picture" accept=".png, .jpg, .jpeg">
                <button class="btn button border" type="submit"><b>Încarcă</b></button>
                <div style = "display:flex; align-items:center; color:red;">
                    <i style="display:<?php if($img_error!="") echo "block"; else echo "none";?>" class="bi bi-exclamation-circle"></i>
                    <a style="padding-left:15px;"><?php echo $img_error;?></a>
                </div>
            </div>
        </form>
    </div>
    <div class="py-2">
        <form method="post" action ="<?php echo $_SERVER['PHP_SELF'];?>">
        <div class="row py-2">
            <div class="col-md-6">
                <label for="firstname">Nume utilizator</label>
                <input type="text" name="username"class="bg-light form-control" placeholder="<?php echo $userName;?>">
            </div>
            <div class="col-md-6">
                <label for="email">Adresă de e-mail</label>
                <input type="text" name="email" class="bg-light form-control" placeholder="<?php echo $email;?>">
            </div>
            <div class="col-md-6 pt-md-0 pt-3">
                <label for="phone">Număr de telefon</label>
                <input type="tel" name="phoneNumber" class="bg-light form-control" placeholder="<?php echo $phoneNumber;?>">
            </div>
        </div>
        <div class="py-3 pb-4 border-bottom">
            <button class="btn-primary" name="save">Salvează modificările</button>
            <button class="btn border button" name ="back">Inapoi la pagina principala</button>
        </div>
        <div class="dez" id="deactivate">
            <div>
                <b>Dezactivați contul</b>
                <p>Detaliile despre contul dumneavoastra vor fi sterse!</p>
            </div>
            <div class="ml-auto">
                <button class="btn danger" name="delete" onclick="return confirm('Sigur doriti sa va dezactivati contul?')" >Dezactivați</button>
            </div>
        </div>
        </form>
    </div>
    
</div>
</body>
</html>
