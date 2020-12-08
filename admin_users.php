<?php
  session_start();
  require "includes/db_connect.inc.php";
  $i = 0;
  $j = 10;
  $count = 0;
  $cond = "a";
  if (empty($_SESSION["userID"])) {
    header("Location: login.php");
  }
  

  $userID = $_SESSION["userID"];
  $userType = $_SESSION["userType"];
  $headButton = $headButtonLink = "";
  if(empty($userID)){
    $headButton = "Login / Registration";
  }else{
    $headButton = "My Account";
  }

  if(empty($userType)){
    $headButtonLink = "login.php";
  }
  elseif ($userType=="admin") {
    $headButtonLink = "admin_home.php";
  }
  elseif ($userType=="agent") {
    $headButtonLink = "agent_home.php";
  }
  else {
    $headButtonLink = "customer_home.php";
  }


if($_SERVER["REQUEST_METHOD"] == "POST"){

  if(isset($_POST['prev_btn'])){
    $i = $_POST['prev_btn'];
  }elseif(isset($_POST['next_btn'])){
    $i = $_POST['next_btn'];
  }elseif(isset($_POST['pg_btn'])){
    $i = $_POST['pg_btn'];
  }elseif (isset($_POST['perpg_btn'])) {
    $_SESSION['per_page'] = $_POST['records_per_page'];
  }

  if(isset($_POST['all_packages'])){
    header("Location: admin_all_packages.php");
  }

  if(isset($_POST['profile'])){
    header("Location: profile.php");
  }

  if(isset($_POST['users'])){
    header("Location: admin_users.php");
  }

  if(isset($_POST['logout'])){
    session_unset();
    session_destroy();
    header("Location: login.php");
  }

  if(isset($_POST['update_button'])){
    $_SESSION["edit_user_id"] = $_POST['update_button'];
    header("Location: admin_update_user.php");
  }

  if(isset($_POST['delete_button'])){
    $_SESSION["edit_user_id"] = $_POST['delete_button'];
    $cond = "";
    $uid = $_POST['delete_button'];
  }

  if(isset($_POST['delete_confirm_button'])){
    $uid = $_SESSION["edit_user_id"];
    $sqld = "DELETE FROM user_info WHERE user_id = $uid";
    if (mysqli_query($conn, $sqld)) {
      $cond = "done";;
    } else {
      echo "Error: " . $sql . "<br>" . mysqli_error($conn);
    }
  }
}

$sql = "select user_id, name, email, phone, user_type from user_info;";
$result = mysqli_query($conn, $sql);

  /*
  $sqlPkg = "select * from purchase where package_id = $packageID;";
  $resultPackage = mysqli_query($conn, $sqlPkg);
  */

  $sqlTotalRows = "select count(*) as total_rows from user_info;";
  $rowCount = mysqli_fetch_assoc(mysqli_query($conn, $sqlTotalRows));
  $pgNmbr = ceil($rowCount['total_rows'] / $j);
?>


<!DOCTYPE html>
<html lang="en" dir="ltr">
  <head>
    <meta charset="utf-8">
    <link href="https://fonts.googleapis.com/css?family=Raleway:100,200,300,400,500,600,700,800,900" rel="stylesheet">
    <link rel="stylesheet" type="text/css" href="style/search.css">
    <title></title>
  </head>
  <body onload="toggle<?php echo $cond ?>Popup()">

    <script>
    function togglePopup(){
      document.getElementById("popup-user-delete-warning").classList.toggle("active");
    }
    function toggledonePopup(){
      document.getElementById("popup-user-delete-done").classList.toggle("active");
    }
    </script>

    <header>
      <a class="logo" href="/projects/web_tech_final_project/"><img src="media/logo.png" alt="logo" width="120px"></a>
      <nav>
          <ul class="nav__links">
              <li><a href="/projects/web_tech_final_project/">Home</a></li>
              <li><a href="/projects/web_tech_final_project/">Find Package</a></li>
              <li><a href="#">About</a></li>
          </ul>
      </nav>
      <a class="cta" href="/projects/web_tech_final_project/<?php echo $headButtonLink; ?>"><?php echo $headButton; ?></a>
    </header>

    <section class="search_top">
      <div align="center">
        <form action="<?php echo $_SERVER['PHP_SELF']; ?>" method="POST">
          <table>
            <tr class="top_section_head" align="center">
              <td class="td" colspan="4">Users</td>
            </tr>
          </table>
        </form>
      </div>
    </section>

    <section class="">
      <div align="center">
        <form action="<?php echo $_SERVER['PHP_SELF']; ?>" method="post">
          <table class="search_again">
            <tr class="" align="center">
              <td><button class="button" type="submit" name="users">Users</button></td>
              <td><button class="button" type="submit" name="profile">Profile</button></td>
              <td><button class="button" type="submit" name="all_packages">Packages</button></td>
              <td><button class="button" type="submit" name="logout">Log Out</button></td>
            </tr>
          </table>
        </form>
      </div>
    </section>

    <section class="">
      <div align="center">
        <form action="<?php echo $_SERVER['PHP_SELF']; ?>" method="post">
            <div class="popup" id="popup-user-delete-warning">
              <div class="overlay"></div>
              <div class="content">
                <div class="close-btn" onclick="togglePopup()">&times;</div>
                <h1>Delete User?</h1> <br>
                <img src="media/cancel.png" width="80px" alt=""> <br> <br>
                <p> Are you sure to delete this user? It can not be undone! </p>
                <button class="button" type="submit" name="delete_confirm_button">Yes</button>
                <button class="button" type="submit" name="delete_cancel_button">NO</button>
              </div>
            </div>

            <div class="popup" id="popup-user-delete-done">
              <div class="overlay"></div>
              <div class="content">
                <div class="close-btn" onclick="togglePopup()">&times;</div>
                <h1>User Deleted</h1> <br>
                <img src="media/check.png" width="80px" alt=""> <br> <br>
                <p> User removed successfully! </p>
                <button class="button" type="submit" name="ok">OK</button>
              </div>
            </div>
        </form>
      </div>
    </section>



    <section class="white_section">
      <div align="center">
        <form action="<?php echo $_SERVER['PHP_SELF']; ?>" method="post">
        <table class="packages" align="center" cellspacing="10">
          <tr>
            <td class="package_info" > User ID </td>
            <td class="package_info" > Name </td>
            <td class="package_info" > Email </td>
            <td class="package_info" > Phone </td>
          </tr>
            <?php while($row = mysqli_fetch_assoc($result)){ ?>
              <tr>
                <td class="package_info" > <?php echo $row['user_id']; ?> </td>
                <td class="package_info" > <?php echo $row['name']; ?> </td>
                <td class="package_info" > <?php echo $row['email']; ?></td>
                <td class="package_info" > <?php echo $row['phone']; ?></td>
                <td align="right"> <button type="submit" name="update_button" class="package_button" value="<?php echo $row['user_id']; ?>">Update</button> </td>
                <td align="right"> <button type="submit" name="delete_button" class="package_button" value="<?php echo $row['user_id']; ?>">Delete</button> </td>
              </tr>
            <?php } ?>
        </table>
        <div class="pagination_btns" align="center">

        </div>
      </form>

      </div>
    </section>

    <section class="">
      <div align="center">
        <form action="<?php echo $_SERVER['PHP_SELF']; ?>" method="post">
          <table class="pagination_section">
            <tr class="" align="center">
              <td colspan="3">
                <?php if($i > 0){ ?>
                  <button type="submit" class="pagination_button" name="prev_btn" value="<?php echo ($i-$j); ?>" >Previous</button>
                <?php } ?>

                <?php if($pgNmbr > 1) { for ($p=0; $p<$pgNmbr; $p++) { ?>
                  <button type="submit" class="pagination_button" name="pg_btn" value="<?php echo ($p*$j); ?>" ><?php echo ($p+1); ?></button>
                <?php } } ?>

                <?php if($i < ($rowCount['total_rows']-$j)){ ?>
                  <button type="submit" class="pagination_button" name="next_btn" value="<?php echo ($i+$j); ?>" >Next</button>
                <?php } ?>
              </td>
            </tr>
          </table>
        </form>
      </div>
    </section>

  </body>
</html>
