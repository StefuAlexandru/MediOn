<?php
include "connection.php";
session_start();




if (isset($_POST['search'])) {
    $username = $_POST['username'];
    $sql = "SELECT * FROM clients WHERE userName = '$username'";
} else {
    $sql = "SELECT * FROM clients";
}
$result = mysqli_query($con, $sql) or die(mysqli_error($con));
if(isset($_GET['id'])){
    $id = $_GET['id'];
    $sql ="DELETE FROM clients where id='$id'";
    mysqli_query($con, $sql) or die(mysqli_error($con));
}

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Pacifico&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">
    <link rel="stylesheet" href="admin.css"/>  </head>
<body>
    <div class="wrapper">  
    <h4>Clients List</h4>
    <div class="search">
    <form method="post" class="d-flex mb-3 justify-content-between">
  <input class="form-control" type="text" name="username" placeholder="Search by Username">
  <button class="btn btn-primary custom-search-btn" name="search" type="submit">Search</button>
</form>
    </div>
    <table class="table" >
        <thead>
            <tr>
                <th>First Name</th>
                <th>Last Name</th>
                <th>Username</th>
                <th>Email</th>
                <th>Phone Number</th>
                <th>Subscription</th>
                <th>Profile Picture</th>
                <th></th>
            </tr>
        </thead>
        <tbody>
            <?php
            if ($result->num_rows > 0) {
                while ($row = $result->fetch_assoc()) {
                    echo "<tr>";
                    echo "<td>" . $row['firstName'] . "</td>";
                    echo "<td>" . $row['lastName'] . "</td>";
                    echo "<td>" . $row['userName'] . "</td>";
                    echo "<td>" . $row['email'] . "</td>";
                    echo "<td>" . $row['phoneNumber'] . "</td>";
                    echo "<td>" . $row['subscription'] . "</td>";
                    $profile_picture_path = "uploads/" . $row['profilePicture'];
                    if (file_exists($profile_picture_path)) {
                        echo "<td><img src='$profile_picture_path' alt='Profile Picture' width='50' height='50'></td>";
                    } else {
                        echo "<td><img src='assets/images/profile.png' alt='Default Profile Picture' width='50' height='50'></td>"; 
                    }
                    $client_id = $row['id'];
                    echo "<td>";
                    echo "<a href='admin.php?id=$client_id' class='btn btn-danger btn-sm' onclick='return confirmDelete()'><i class='bi bi-trash'></i> Delete</a>";
                    echo "</td>";
                    echo "</tr>";
                }
            } else {
                echo "<tr><td colspan='8'>No clients found.</td></tr>";
            }
            ?>
        </tbody>
    </table>
    <form method = "post" action ="index.php">
    <button class="btn border button" name ="back">Inapoi la pagina principala</button>
    </div>
    <script>
  function confirmDelete() {
    return confirm("Are you sure you want to delete this client?");
  }
  </script>
</body>

</html>
