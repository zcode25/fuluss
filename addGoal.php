<?php

session_start();
if (!isset($_SESSION["login"])) {
    header("Location: index.php");
    exit;
}

require 'functions.php';
$id_user = $_SESSION["id_user"];
$goal = query("SELECT max(code_goal) as kode_terbesar FROM goal")[0];
$code_goal = $goal["kode_terbesar"];
$urutan = (int) substr($code_goal, 1, 4);

$urutan++;
$huruf = "G";
$code_goal = $huruf . sprintf("%04s", $urutan);

$budget = query("SELECT * FROM budget WHERE id_user = '$id_user'")[0];

if (isset($_POST["submit"])) {

    if (addGoal($_POST) > 0) {
        echo "

      <script>
        alert('Add goal successfully');
        document.location.href = 'goal.php';
      </script>

    ";
    } else {

        echo "

      <script>
        alert('Add goal failed');
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

    <section id="addGoal" class="addGoal">
        <div class="container">
            <div class="row d-flex justify-content-center">
                <div class=" col-xl-6 mt-5 mb-5">
                    <div class="card">
                        <div class="card-body">
                            <h3 class="card-title">Add Goal</h3>
                            <form class="mt-3" action="" method="post">
                                <input class="form-control" type="hidden" id="code_goal" name="code_goal" value="<?= $code_goal; ?>" required>
                                <input class="form-control" type="hidden" id="id_user" name="id_user" value="<?= $id_user; ?>" required>
                                <input class="form-control" type="hidden" id="code_budget" name="code_budget" value="<?= $budget["code_budget"]; ?>" required>
                                <input class="form-control" type="hidden" id="status_goal" name="status_goal" value="Not Achieved" required>
                                <div class="mb-3">
                                    <label for="date_goal" class="form-label">Goal Date</label>
                                    <input type="date" class="form-control" id="date_goal" name="date_goal" autofocus required>
                                </div>
                                <div class="mb-3">
                                    <label for="name_goal" class="form-label">Goal Name</label>
                                    <input type="text" class="form-control" id="name_goal" name="name_goal" required>
                                </div>
                                <div class="mb-3">
                                    <label for="note_goal" class="form-label">Goal Note</label>
                                    <textarea class="form-control" id="note_goal" name="note_goal" rows="3" required></textarea>
                                </div>
                                <div class="mb-3">
                                    <label for="amount_goal" class="form-label">Goal Amount</label>
                                    <input type="number" class="form-control" id="amount_goal" name="amount_goal" required>
                                </div>
                                <div class="row">
                                    <div class="col">
                                        <div class="d-grid gap-2">
                                            <a href="goal.php" class="btn btn-light">Back</a>
                                        </div>
                                    </div>
                                    <div class="col">
                                        <div class="d-grid gap-2">
                                            <button class="btn btn-success" type="submit" name="submit">Submit</button>
                                        </div>
                                    </div>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.bundle.min.js" integrity="sha384-ka7Sk0Gln4gmtz2MlQnikT1wXgYsOg+OMhuP+IlRH9sENBO0LRn5q+8nbTov4+1p" crossorigin="anonymous"></script>

</html>