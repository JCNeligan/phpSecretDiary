<?php

session_start();

$error = "";

if (array_key_exists("logout", $_GET)) {
    unset($_SESSION);
    setcookie("id", "", time() - 60 * 60);
    $_COOKIE["id"] = "";
} else if (array_key_exists("id", $_SESSION) or array_key_exists("id", $_COOKIE)) {
    header("Location: loggedinpage.php");
}

if (array_key_exists("submit", $_POST)) {

    $link = mysqli_connect("localhost", "root", "", "secret_diary");
    if (mysqli_connect_error()) {
        die("Error connecting to database");
    }

    if (!$_POST["email"]) {
        $error = "Email address is required<br>";
    }

    if (!$_POST["password"]) {
        $error = "Password is required<br>";
    }

    if ($error != "") {
        $error = "<p>There were error(s) in your form:</p>" . $error;
    } else {
        if ($_POST["signUp"] == "1") {
            $query = "SELECT `id` FROM `users` WHERE `email` = '" . mysqli_real_escape_string($link, $_POST['email']) . "' LIMIT 1";
            $result = mysqli_query($link, $query);
            if (mysqli_num_rows($result) > 0) {
                $error = "<p>That email address has already been taken.</p>";
            } else {
                $query = "INSERT INTO `users` (`email`, `password`) VALUES ('" . mysqli_real_escape_string($link, $_POST['email']) . "', '" . mysqli_real_escape_string($link, $_POST['password']) . "')";

                if (!mysqli_query($link, $query)) {
                    $error = "<p>Could not complete signup at this time, please try again later</p>";
                } else {
                    $query = "UPDATE `users` SET password = '" . md5(md5(mysqli_insert_id($link)) . $_POST['password']) . "' WHERE id = " . mysqli_insert_id($link) . " LIMIT 1";
                    mysqli_query($link, $query);
                    $_SESSION["id"] = mysqli_insert_id($link);
                    if ($_POST["persist" == 1]) {
                        setcookie("id", mysqli_insert_id($link), time() + 60 * 60 * 48);
                    }
                    header("Location: loggedinpage.php");

                }

            }
        } else {
            print_r($_POST);
        }
    }
}

?>

<html>
  <head>
  <title>Secret Diary</title>
  <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0/css/bootstrap.min.css" integrity="sha384-Gn5384xqQ1aoWXA+058RXPxPg6fy4IWvTNh0E263XmFcJlSAwiGgFAW/dAiS6JXm" crossorigin="anonymous">
  </head>

  <body>

  <div id="error"><?php echo $error; ?></div>

    <form method="post">
      <input type="email" name="email" placeholder="Your Email"/>
      <input type="password" name="password" placeholder="Your Password"/>
      <input type="checkbox" name="persist"/>
      <input type="hidden" name="signUp" value="1"/>
      <input type="submit" name="submit" value="Sign Up!"/>
    </form>
    <form method="post">
      <input type="text" name="email" placeholder="Your Email"/>
      <input type="password" name="password" placeholder="Your Password"/>
      <input type="checkbox" name="persist"/>
      <input type="hidden" name="signUp" value="0"/>
      <input type="submit" name="submit" value="Log In!"/>
    </form>
  </body>
</html>
