<?php
  require "includes/db_connect.inc.php";

  session_start();
  $_SESSION["userID"] = "";
  $_SESSION["userType"] = "";

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

  $userNameErr = $passwordErr = "";
  $userName = $userPassword = "";
  $loginSuccess = "";

  if($_SERVER["REQUEST_METHOD"]=="POST"){

    if(empty($_POST['user_name'])){
      $userNameErr = "Username cannot be empty!";
    }else{
      $userName = $_POST['user_name'];
    }

    if(empty($_POST['password'])){
      $passwordErr = "Password cannot be empty!";
    }else{
      $userPassword = $_POST['password'];
    }

    $sqlUsers = "select name from user_info where name = '$userName'";
    $results = mysqli_query($conn, $sqlUsers);

    $rowCount = mysqli_num_rows($results);

    if($rowCount < 1 ){
      $userNameErr = "User doesn't exists!";
    }
    else{
      $userPasswordH = hash('sha512', $userPassword);
      $sqlLogin = "select name from user_info where name = '$userName' and password = '$userPasswordH';";
      $results = mysqli_query($conn, $sqlLogin);

      $rowCount = mysqli_num_rows($results);

      if($rowCount < 1 ){
        $passwordErr = "Password doesn't match!";
      }
      else {
        $sql = "SELECT user_id, user_type FROM user_info where name = '$userName'";
        $result = $conn->query($sql);
        $row = $result->fetch_assoc();

        session_start();
        $_SESSION["userID"] = $row["user_id"];
        $_SESSION["userType"] = $row["user_type"];
        $loginSuccess = "Login successfull!";

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
    <link rel="stylesheet" type="text/css" href="style/style.css">
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

    <section align="">
      <form action="<?php echo $_SERVER['PHP_SELF']; ?>" method="POST">
        <table align="center" class="section" cellspacing="0" cellpadding="0">
          <tr cellspacing="0" cellpadding="0">

            <td class="left_table" cellspacing="0" cellpadding="0">
              <table>
                <h1>WELCOME</h1>
                <p style="font-size:12px;">Lorem ipsum dolor amar sit amet, <br>
                  sed do eiusmod tempor lola aliqua.</p>
              </table>
            </td>

            <td class="right_table" align="center" cellspacing="0" cellpadding="0">
                <table>
                  <tr>
                    <td align="center">
                      <h2 style="color:#FF4A52;" >Log In</h2>
                      <div class="error">
                        <?php echo $userNameErr ?>
                        <?php echo $passwordErr ?>
                      </div>
                      <div class="success">
                        <?php echo $loginSuccess ?>
                      </div>
                      <input type="text" name="user_name" placeholder="User name" value="" class="text_input" required> <br>
                      <input type="password" name="password" placeholder="Password" value="" class="text_input" required> <br>
                      <input type="submit" name="login_button" value="Log In" class="login_button"> <br>
                      <button onclick="document.location='registration.php'" class="create_acc_button">New User? Create An Account.</button>
                    </td>
                  </tr>
                </table>
            </td>
          </tr>
        </table>
      </form>
    </section>


  </body>
</html>
