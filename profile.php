<?php

session_start();
if (!isset($_SESSION["login"])) {
    header("Location: index.php");
    exit;
}

require 'functions.php';
$id_user = $_SESSION["id_user"];
$profile = query("SELECT * FROM user WHERE id_user = '$id_user'")[0];

if (isset($_POST["submit"])) {

    if (editProfile($_POST) > 0) {
        echo "

      <script>
        alert('Edit profile successfully');
        document.location.href = 'profile.php';
      </script>

    ";
    } else {

        echo "

      <script>
        alert('Edit profile failed');
      </script>

    ";
    }
}

if (isset($_POST["change"])) {

    if (editPassword($_POST) > 0) {
        echo "

      <script>
        alert('Change password successfully');
        document.location.href = 'logout.php';
      </script>

    ";
    } else {

        echo "

      <script>
        alert('Change password failed');
      </script>

    ";
    }
}

if (isset($_POST["delete"])) {

    if (deleteAccount($id_user) > 0) {
        echo "

      <script>
        alert('Delete account successfully');
        document.location.href = 'logout.php';
      </script>

    ";
    } else {

        echo "

      <script>
        alert('Delete account failed');
      </script>

    ";
    }
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

    <nav class="navbar navbar-expand-lg navbar-light d-none d-md-block bg-light fixed-top">
        <div class="container-md">
            <a class="navbar-brand" href="home.php">
                <img src="img/logo.png" alt="" width="35" height="35" class="me-2"><span class="fw-bold">FULUSS</span>
            </a>
            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNavAltMarkup" aria-controls="navbarNavAltMarkup" aria-expanded="false" aria-label="Toggle navigation">
                <span class="navbar-toggler-icon"></span>
            </button>
            <div class="collapse navbar-collapse" id="navbarNavAltMarkup">
                <div class="navbar-nav ms-auto">
                    <a class="nav-link" href="home.php">Home</a>
                    <a class="nav-link" href="analysis.php">Analysis</a>
                    <a class="nav-link" href="goal.php">Goal</a>
                    <a class="nav-link" href="loan.php">Loan</a>
                    <a class="nav-link actives" href="profile.php">Profile</a>
                </div>
            </div>
        </div>
    </nav>
    <!-- Bottom Navbar -->
    <nav class="navbar navbar-light bg-light border-top navbar-expand d-md-none d-lg-none d-xl-none fixed-bottom">
        <ul class="navbar-nav nav-justified w-100">
            <li class="nav-item">
                <a href="home.php" class="nav-link">
                    <span>
                        <i class="fas fa-home"></i>
                    </span>
                </a>
            </li>
            <li class="nav-item">
                <a href="analysis.php" class="nav-link">
                    <span>
                        <i class="fas fa-chart-area"></i>
                    </span>
                </a>
            </li>
            <li class="nav-item">
                <a href="goal.php" class="nav-link">
                    <span>
                        <i class="fas fa-bullseye"></i>
                    </span>
                </a>
            </li>
            <li class="nav-item">
                <a href="loan.php" class="nav-link">
                    <span>
                        <i class="fas fa-money-check-alt"></i>
                    </span>
                </a>
            </li>
            <li class=" nav-item">
                <a href="profile.php" class="nav-link active">
                    <span>
                        <i class="fas fa-user"></i>
                    </span>
                </a>
            </li>
        </ul>
    </nav>

    <section id="profile" class="profile">
        <div class="container">
            <div class="row">
                <div class="col-xl-6 mt-3">
                    <div class="card">
                        <div class="card-body">
                            <h3 class="card-title">Profile</h3>
                            <form class="mt-3" action="" method="post">
                                <input class="form-control" type="hidden" id="id_user" name="id_user" value="<?= $id_user; ?>" required>
                                <div class="mb-3">
                                    <label for="name_user" class="form-label">Name</label>
                                    <input type="text" class="form-control" id="name_user" name="name_user" value="<?= $profile["name_user"]; ?>" required>
                                </div>
                                <div class="mb-3">
                                    <label for="email_user" class="form-label">Email address</label>
                                    <input type="email" class="form-control" id="email_user" name="email_user" aria-describedby="emailHelp" value="<?= $profile["email_user"]; ?>" required>
                                </div>
                                <div class="mb-3">
                                    <label for="tel_user" class="form-label">Telephone Number</label>
                                    <input type="tel" class="form-control" id="tel_user" name="tel_user" value="<?= $profile["tel_user"]; ?>" required>
                                </div>
                                <div class="d-grid gap-2">
                                    <button class="btn btn-primary" type="submit" name="submit">Edit</button>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
                <div class="col-xl-6 mt-3 mb-3">
                    <div class="card">
                        <div class="card-body">
                            <h3 class="card-title">Change Password</h3>
                            <form class="mt-3" action="" method="post">
                                <input class="form-control" type="hidden" id="id_user" name="id_user" value="<?= $id_user; ?>" required>
                                <div class="mb-3">
                                    <label for="pass_user" class="form-label">Password</label>
                                    <input type="password" class="form-control" id="pass_user" name="pass_user" required>
                                </div>
                                <div class="d-grid gap-2">
                                    <button class="btn btn-primary" type="submit" name="change">Change</button>
                                </div>
                            </form>
                        </div>
                    </div>
                    <div class="card mt-3">
                        <div class="card-body text-center">
                            <div class="d-grid gap-2 mb-3">
                                <button class="btn btn-outline-danger" type="button" name="logout" onclick="document.location.href='logout.php'">Logout</button>
                            </div>
                            <form action="" method="post">
                                <button class="btn btn-link text-danger" type="submit" name="delete" name="delete" onclick=" return confirm ('Are you sure?');">Delete Account</button>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>


    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.bundle.min.js" integrity="sha384-ka7Sk0Gln4gmtz2MlQnikT1wXgYsOg+OMhuP+IlRH9sENBO0LRn5q+8nbTov4+1p" crossorigin="anonymous"></script>

</html>