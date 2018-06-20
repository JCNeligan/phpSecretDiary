<?php

if (array_key_exists("submit-signup", $_POST)) {

    $link = mysqli_connect("localhost", "root", "", "secret_diary");
    if (mysqli_connect_error()) {
        die("Error connecting to database");
    }

    $error = "";

    if (!$_POST["email-signup"]) {
        $error = "Email address is required<br>";
    }

    if (!$_POST["password-signup"]) {
        $error = "Password is required<br>";
    }

    if ($error != "") {
        $error = "<p>There were error(s) in your form:</p>" . $error;
    } else {
        $query = "SELECT `id` FROM `users` WHERE `email` = '" . mysqli_real_escape_string($link, $_POST['email-signup']) . "' LIMIT 1";
        $result = mysqli_query($link, $query);
        if (mysqli_num_rows($result) > 0) {
            $error = "<p>That email address has already been taken.</p>";
        } else {
            $query = "INSERT INTO `users` (`email`, `password`) VALUES ('" . mysqli_real_escape_string($link, $_POST['email-signup']) . "', '" . mysqli_real_escape_string($link, $_POST['password-signup']) . "')";

            if (!mysqli_query($link, $query)) {
                $error = "<p>Could not complete signup at this time, please try again later</p>";
            } else {
                $query = "UPDATE `users` SET password = '" . md5(md5(mysqli_insert_id($link)) . $_POST['password-signup']) . "' WHERE id = " . mysqli_insert_id($link) . " LIMIT 1";
                mysqli_query($link, $query);
                echo "Sign Up Complete!";

            }

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
      <input type="email" name="email-signup" placeholder="Your Email"/>
      <input type="password" name="password-signup" placeholder="Your Password"/>
      <input type="checkbox" name="persist-signup"/>
      <input type="submit" name="submit-signup" value="Sign Up!"/>
    </form>
    <form method="post">
      <input type="text" name="email-login" placeholder="Your Email"/>
      <input type="password" name="password-login" placeholder="Your Password"/>
      <input type="checkbox" name="persist-login"/>
      <input type="submit" name="submit-login" value="Log In!"/>
    </form>
  </body>
</html>
