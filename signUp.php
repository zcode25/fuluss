<?php
require 'functions.php';

$user = query("SELECT max(id_user) as kode_terbesar FROM user")[0];
$id_user = $user["kode_terbesar"];
$urutan = (int) substr($id_user, 1, 4);

$urutan++;
$huruf = "U";
$id_user = $huruf . sprintf("%04s", $urutan);

if (isset($_POST["submit"])) {

    $email_user = $_POST["email_user"];
    $result = query("SELECT * FROM user WHERE email_user = '$email_user'");
    if (count($result) === 1) {
        echo "

        <script>
          alert('The email you entered is already registered');
          document.location.href = 'signUp.php';
        </script>
  
      ";
    }

    if (signUp($_POST) > 0) {
        addBudget($_POST["id_user"]);
        echo "

      <script>
        alert('Sign up successfully');
        document.location.href = 'index.php';
      </script>

    ";
    } else {

        echo "

      <script>
        alert('Sign up failed');
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


    <section id="signUp" class="signUp">
        <div class="container">
            <div class="row d-flex justify-content-center">
                <div class=" col-xl-6 mt-5 mb-5">
                    <div class="card">
                        <div class="card-body">
                            <h3 class="card-title">Sign Up</h3>
                            <form class="mt-3" action="" method="post">
                                <input class="form-control" type="hidden" id="id_user" name="id_user" value="<?= $id_user; ?>" required>
                                <div class="mb-3">
                                    <label for="name_user" class="form-label">Name</label>
                                    <input type="text" class="form-control" id="name_user" name="name_user" autofocus required>
                                </div>
                                <div class="mb-3">
                                    <label for="email_user" class="form-label">Email address</label>
                                    <input type="email" class="form-control" id="email_user" name="email_user" aria-describedby="emailHelp" required>
                                </div>
                                <div class="mb-3">
                                    <label for="tel_user" class="form-label">Telephone Number</label>
                                    <input type="tel" class="form-control" id="tel_user" name="tel_user" required>
                                </div>
                                <div class="mb-3">
                                    <label for="pass_user" class="form-label">Password</label>
                                    <input type="password" class="form-control" id="pass_user" name="pass_user" required>
                                </div>
                                <div class="d-grid gap-2">
                                    <button class="btn btn-primary" type="submit" name="submit">Sign Up</button>
                                </div>
                            </form>
                            <p class="card-text mt-5 text-center">Already have an account? <a href="index.php" class="link-primary"><strong>Login</strong></a></p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>



    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.bundle.min.js" integrity="sha384-ka7Sk0Gln4gmtz2MlQnikT1wXgYsOg+OMhuP+IlRH9sENBO0LRn5q+8nbTov4+1p" crossorigin="anonymous"></script>

</html>