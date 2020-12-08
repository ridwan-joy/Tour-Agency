<?php
  require "includes/db_connect.inc.php";

  session_start();
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

  if($_SERVER["REQUEST_METHOD"]=="POST")
  {
        if ($row["user_type"] == "admin") {
          header("Location: admin_home.php");
        }
        elseif ($row["user_type"] == "agent") {
          header("Location: agent_create_package.php");
        }
        elseif ($row["user_type"] == "customer") {
          header("Location: home.php");
        }
  }

    /*while($row = mysqli_fetch_assoc($results)){
      echo $row['id'] . "<br>" . $row['username'] . "<br>" . $row['blood_type'];
      echo "<hr>";
    }*/
?>


<!DOCTYPE html>
<html lang="en" dir="ltr">
  <head>
    <link href="https://fonts.googleapis.com/css?family=Raleway:100,200,300,400,500,600,700,800,900" rel="stylesheet">
    <link rel="stylesheet" type="text/css" href="style/error-access.css">
    <meta charset="utf-8">
    <title>Login</title>

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

    <section>
    </section>
  </body>
</html>
