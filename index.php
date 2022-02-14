<?php

session_start();
if (isset($_SESSION["login"])) {
    header("Location: home.php");
    exit;
}

require 'functions.php';
if (isset($_POST["submit"])) {

    $email_user = $_POST["email_user"];
    $pass_user = sha1($_POST["pass_user"]);

    $result = mysqli_query($conn, "SELECT * FROM user WHERE email_user = '$email_user'");

    if (mysqli_num_rows($result) === 1) {
        $row = mysqli_fetch_assoc($result);

        if ($pass_user == $row["pass_user"]) {

            $_SESSION["id_user"] = $row["id_user"];
            $_SESSION["name_user"] = $row["name_user"];
            $_SESSION["login"] = true;

            header("Location: home.php");
            exit;
        }
    }

    $error = true;
}

?>

<!doctype html>
<html lang="en">

<head>
    <!-- Required meta tags -->
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-1BmE4kWBq78iYhFldvKuhfTAU6auU8tT94WrHftjDbrCEXSU1oBoqyl2QvZ6jIW3" crossorigin="anonymous">
    <link href="fontawesome/css/all.css" rel="stylesheet">
    <link rel="stylesheet" href="css/style.css">
    <link rel="shortcut icon" href="img/logo.png">

    <title>Budget Tracker</title>
</head>

<body>


    <section id="main" class="main">
        <div class="container">
            <div class="row">
                <div class="col-xl-6 mt-5">
                    <div class="card">
                        <div class="card-body">
                            <h1 class="card-title fw-bolder">FULUSS</h1>
                            <p class="card-text mt-3 mb-4 justify">Fuluss is a website based application that can help you record all income and expenses. A Fuluss can also help you manage your finances well. let's use a Fuluss in your daily activities.</p>
                            <a href="https://www.instagram.com/zcode25/" class="btn btn-success" target="_blank">Follow Us</a>
                            <a href="https://saweria.co/azein25" class="btn btn-outline-success" target="_blank">Donate</a>
                            <hr>
                            <p style="font-size: 14px;" class="card-text text-secondary">Production by <span class="text-dark fw-bold">ZCODE</span></p>
                            <p style="font-size: 14px;" class="card-text text-secondary">© 2022 FULUSS. All rights reserved.</p>
                        </div>
                    </div>
                </div>
                <div class="col-xl-6 mt-5 mb-5">
                    <div class="card">
                        <div class="card-body">
                            <h3 class="card-title">Login</h3>
                            <?php if (isset($error)) : ?>
                                <div class="alert alert-danger alert-dismissible fade show mt-3" role="alert">
                                    The email and password you entered is <strong>wrong.</strong>
                                    <button type="button" class="btn btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                                </div>
                            <?php endif; ?>
                            <form class="mt-3" action="" method="post">
                                <div class="mb-3">
                                    <label for="email_user" class="form-label">Email address</label>
                                    <input type="email" class="form-control" id="email_user" name="email_user" aria-describedby="emailHelp" autofocus required>
                                </div>
                                <div class="mb-3">
                                    <label for="pass_user" class="form-label">Password</label>
                                    <input type="password" class="form-control" id="pass_user" name="pass_user" required>
                                </div>
                                <div class="d-grid gap-2">
                                    <button class="btn btn-primary" type="submit" name="submit">Login</button>
                                </div>
                            </form>
                            <p class="card-text mt-5 text-center">Don't have an account? <a href="signUp.php" class="link-primary"><strong>Sign Up</strong></a></p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>



    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.bundle.min.js" integrity="sha384-ka7Sk0Gln4gmtz2MlQnikT1wXgYsOg+OMhuP+IlRH9sENBO0LRn5q+8nbTov4+1p" crossorigin="anonymous"></script>

</html>