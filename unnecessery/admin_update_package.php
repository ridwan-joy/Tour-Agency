<?php
  session_start();
  require "includes/db_connect.inc.php";
  $i = 0;
  $j = 10;
  $count = 0;
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

  if(isset($_POST['update_button'])){
    $_SESSION["packID"] = $_POST['package_id'];
    header("Location: admin_edit_package.php");
  }

  if(isset($_POST['prev_btn'])){
    $i = $_POST['prev_btn'];
  }elseif(isset($_POST['next_btn'])){
    $i = $_POST['next_btn'];
  }elseif(isset($_POST['pg_btn'])){
    $i = $_POST['pg_btn'];
  }elseif (isset($_POST['perpg_btn'])) {
    $_SESSION['per_page'] = $_POST['records_per_page'];
  }

  if(isset($_POST['update_user'])){
    header("Location: admin_users.php");
  }

  if(isset($_POST['profile'])){
    header("Location: profile.php");
  }

  if(isset($_POST['packages'])){
    header("Location: agent_packages.php");
  }

  if(isset($_POST['logout'])){
    session_unset();
    session_destroy();
    header("Location: login.php");
  }
}

  $sql = "select package_id, destination, date, price from package LIMIT $i, $j;";
  $result = mysqli_query($conn, $sql);

  $sqlTotalRows = "select count(*) as total_rows from package;";
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
  <body>
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
              <td class="td" colspan="4">Update Packages</td>
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
              <td><button class="button" type="submit" name="update_user">User</button></td>
              <td><button class="button" type="submit" name="profile">Profile</button></td>
              <td><button class="button" type="submit" name="all_packages">All Packages</button></td>
              <td><button class="button" type="submit" name="logout">Log Out</button></td>
            </tr>
          </table>
        </form>
      </div>
    </section>

    <section class="">
      <div align="center">
        <form action="<?php echo $_SERVER['PHP_SELF']; ?>" method="post">
          <table class="update_package">
            <tr class="" align="center">
              <td rowspan="2"><input class="input_box" type="text" value="" name="package_id" placeholder="Package ID" required></td>
              <td><button class="button" type="submit" name="update_button">Update Package</button></td>
              <td><button class="button" type="submit" name="delete_button">Delete Package</button></td>
            </tr>

          </table>
        </form>
      </div>
    </section>

    <section class="white_section">
      <div align="center">
        <form action="<?php echo $_SERVER['PHP_SELF']; ?>" method="post">
        <table class="packages" align="center" cellspacing="30">
            <?php while($row = mysqli_fetch_assoc($result)){ ?>
              <?php
                if ($count%2==0) {
                  ?>
                    <tr>
                  <?php
                }
               ?>
                  <td class="position_bottom" style="background-image: url(media/paris.jpg); background-size: cover; background-position: right;">
                    <div class="image_box">
                      <table>
                        <tr>
                          <td colspan="2"><?php echo $row['destination']; ?> </td>
                        </tr>
                        <tr>
                          <td class="package_info" colspan="2">Package ID: <?php echo $row['package_id']; ?> </td>
                        </tr>
                        <tr>
                          <td class="package_info" align="left"><?php echo $row['date']; ?> </td>
                          <td class="package_info" align="right">BDT <?php echo $row['price']; ?></td>
                        </tr>
                        <tr>
                          <td></td> <td align="right"> <button type="button" name="button" class="package_button" value="<?php echo $row['package_id']; ?>">Confirm</button> </td>
                        </tr>
                      </table>
                    </div>
                  </td>
                  <?php
                    if ($count%2!=0) {
                      ?>
                        </tr>
                      <?php
                    }
                   ?>
                   <?php $count=$count+1; ?>
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
