<?php

session_start();

$error = "";

if (array_key_exists("logout", $_GET)) {
    unset($_SESSION);
    setcookie("id", "", time() - 60 * 60);
    $_COOKIE["id"] = "";
} else if ((array_key_exists("id", $_SESSION) and $_SESSION['id']) or (array_key_exists("id", $_COOKIE) and $_COOKIE['id'])) {
    header("Location: loggedinpage.php");
}

if (array_key_exists("submit", $_POST)) {

    include "connection.php";

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
            $query = "SELECT * FROM `users` WHERE email = '" . mysqli_real_escape_string($link, $_POST['email']) . "'";
            $result = mysqli_query($link, $query);
            $row = mysqli_fetch_array($result);

            if (isset($row)) {
                $hashedPassword = md5(md5($row['id']) . $_POST['password']);

                if ($hashedPassword == $row['password']) {
                    $_SESSION['id'] = $row['id'];

                    if ($_POST["persist" == 1]) {
                        setcookie("id", $row['id'], time() + 60 * 60 * 48);
                    }
                    header("Location: loggedinpage.php");
                } else {
                    $error = "Cannot login! Incorrect password.";
                }
            } else {
                $error = "That email/password combination could not be found.";
            }
        }
    }
}

?>

<?php include "header.php";?>

<div class="container" id="homePageContainer">
    <h1>Secret Diary</h1>
    <p><strong>Store your diary permanently and securely.</strong></p>
    <div id="error"><?php echo $error; ?></div>

    <form method="post" id="signUp">
        <p>Sign up now!</p>
        <div class="form-group">
            <input class="form-control" type="email" name="email" placeholder="Your Email"/>
        </div>
        <div class="form-group">
            <input class="form-control" type="password" name="password" placeholder="Your Password"/>
        </div>
        <div class="form-check">
            <input class="form-check-input" type="checkbox" name="persist" id="check1"/>
            <label class="form-check-label" for="check1">Stay logged in?</label>
        </div>
        <div class="form-group">
            <input type="hidden" name="signUp" value="1"/>
            <input class="btn btn-success" type="submit" name="submit" value="Sign Up!"/>
        </div>
        <p><a href="#" id="showLogIn" class="toggleForms">Log In</a></p>
    </form>
    <form method="post" id="logIn">
        <p>Log in with your username and password.</p>
        <div class="form-group">
            <input class="form-control" type="text" name="email" placeholder="Your Email"/>
        </div>
        <div class="form-group">
            <input class="form-control" type="password" name="password" placeholder="Your Password"/>
        </div>
        <div class="form-check">
            <input class="form-check-input" type="checkbox" name="persist" id="check2"/>
            <label class="form-check-label" for="check2">Stay logged in?</label>
        </div>
        <div class="form-group">
            <input type="hidden" name="signUp" value="0"/>
            <input class="btn btn-success" type="submit" name="submit" value="Log In!"/>
        </div>
        <p><a href="#" id="showLogIn" class="toggleForms">Sign Up</a></p>
    </form>
</div>

<?php include "footer.php";?>
