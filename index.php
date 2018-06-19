<?php

if (array_key_exists("submit-signup", $_POST)) {
    print_r($_POST);
} else {
    echo "Nope!";
}

?>

<html>
  <head>
  <title>Secret Diary</title>
  <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0/css/bootstrap.min.css" integrity="sha384-Gn5384xqQ1aoWXA+058RXPxPg6fy4IWvTNh0E263XmFcJlSAwiGgFAW/dAiS6JXm" crossorigin="anonymous">
  </head>

  <body>
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
