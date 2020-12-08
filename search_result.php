<?php
  session_start();
  $userID = $_SESSION["userID"];
  $userType = $_SESSION["userType"];
  $headButton = $headButtonLink = $packid = "";
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
  require "includes/db_connect.inc.php";
  $i = 0;
  $j = 10;
  $count = 0;

  if(empty($_SESSION['date'])){
    $_SESSION['date'] = '2050-01-01';
  }
  if(empty($_SESSION['budget'])){
    $_SESSION['budget'] = 9999999;
  }

if($_SERVER["REQUEST_METHOD"] == "POST"){

  if (isset($_POST['search'])) {
    $_SESSION['destination'] = $_POST['destination'];
    $_SESSION['date'] = $_POST['date'];
    $_SESSION['budget'] = $_POST['budget'];

    if(empty($_SESSION['date'])){
      $_SESSION['date'] = '2050-01-01';
    }
    if(empty($_SESSION['budget'])){
      $_SESSION['budget'] = 9999999;
    }
  }

  if(isset($_POST['pack_button'])){
    $packid = $_POST['pack_button'];
    $_SESSION["purspackID"] = $packid;
    header("Location: confirm_purchase.php");
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
}

  $destination = $_SESSION['destination'];
  $date = $_SESSION['date'];
  $budget = $_SESSION['budget'];

  $sql = "select package_id, destination, date, price from package LIMIT $i, $j;";
  $result = mysqli_query($conn, $sql);

  $sqlTotalRows = "select count(*) as total_rows from package;";
  $rowCount = mysqli_fetch_assoc(mysqli_query($conn, $sqlTotalRows));
  $pgNmbr = ceil($rowCount['total_rows'] / $j);

    if(empty($_SESSION['destination']))
    {
      if (!empty($_POST['price_sort']) and !empty($_POST['date_sort'])) {
        $sql = "select package_id, destination, date, price from package where date <= '$date' and price < $budget LIMIT $i, $j order by price, date;";
        $result = mysqli_query($conn, $sql);
      }
      elseif (!empty($_POST['price_sort'])) {
        $sql = "select package_id, destination, date, price from package where date <= '$date' and price < $budget LIMIT $i, $j order by price;";
        $result = mysqli_query($conn, $sql);
      }
      elseif (!empty($_POST['date_sort'])) {
        $sql = "select package_id, destination, date, price from package where date <= '$date' and price < $budget LIMIT $i, $j order by date;";
        $result = mysqli_query($conn, $sql);
      }
      else{
        $sql = "select package_id, destination, date, price from package where date <= '$date' and price < $budget LIMIT $i, $j;";
        $result = mysqli_query($conn, $sql);
      }
    }



    elseif (!empty($_SESSION['destination']))
    {
      if (!empty($_POST['price_sort']) and !empty($_POST['date_sort'])) {
        $sql = "select package_id, destination, date, price from package where destination = '$destination' and date <= '$date' and price < $budget LIMIT $i, $j order by price, date;";
        $result = mysqli_query($conn, $sql);
      }
      elseif (!empty($_POST['price_sort'])) {
        $sql = "select package_id, destination, date, price from package where destination = '$destination' and date <= '$date' and price < $budget LIMIT $i, $j order by price;";
        $result = mysqli_query($conn, $sql);
      }
      elseif (!empty($_POST['date_sort'])) {
        $sql = "select package_id, destination, date, price from package where destination = '$destination' and date <= '$date' and price < $budget LIMIT $i, $j order by date;";
        $result = mysqli_query($conn, $sql);
      }

      else{
        $sql = "select package_id, destination, date, price from package where destination = '$destination' and date <= '$date' and price < $budget LIMIT $i, $j;";
        $result = mysqli_query($conn, $sql);
      }
    }
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
              <td class="td" colspan="4">Packages</td>
            </tr>
            <!-- <tr class="top_section_input" align="center">
              <td>
                <input type="text" class="input_box" name="destination" placeholder="Destination, city" value="">
              </td>
              <td>
                <input type="text" onfocus="(this.type='date')" class="input_box" name="date" placeholder="Tour date" value="">
              </td>
              <td>
                <input type="number" class="input_box" name="budget" placeholder="Maximum Budget" value="">
              </td>
              <td>
                <input type="submit" class="button" name="search" value="Search">
              </td>
            </tr> -->
          </table>
        </form>
      </div>
    </section>

    <section class="">
      <div align="center">
        <form action="<?php echo $_SERVER['PHP_SELF']; ?>" method="post">
          <table class="search_again">
            <tr class="" align="center">
              <td>
                <input type="text" class="input_boxS" name="destination" placeholder="Destination, city" value="">
              </td>
              <td>
                <input type="text" onfocus="(this.type='date')" class="input_boxS" name="date" placeholder="Tour date" value="">
              </td>
              <td>
                <input type="number" class="input_boxS" name="budget" placeholder="Maximum Budget" value="">
              </td>
              <td>
                <input type="submit" class="button" name="search" value="Search">
              </td>
            </tr>
          </table>
        </form>
      </div>
    </section>

    <div class="">
      <?php echo $packid; ?>
    </div>

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
                          <td class="package_info" align="left"><?php echo $row['date']; ?> </td>
                          <td class="package_info" align="right">BDT <?php echo $row['price']; ?></td>
                        </tr>
                        <tr>
                          <td></td> <td align="right"> <button type="submit" name="pack_button" class="package_button" value="<?php echo $row['package_id']; ?>">Confirm</button> </td>
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
