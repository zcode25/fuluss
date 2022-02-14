<?php

session_start();
if (!isset($_SESSION["login"])) {
    header("Location: index.php");
    exit;
}

require 'functions.php';
$id_user = $_SESSION["id_user"];
$goals = query("SELECT * FROM goal WHERE id_user = '$id_user' ORDER BY (code_goal) DESC");
$total = 0;
if (count($goals) >= 1) {
    $go = query("SELECT SUM(amount2_goal) AS amount2_goal,  SUM(amount_goal) AS amount_goal FROM goal WHERE id_user = '$id_user'")[0];
    $total = round($go['amount2_goal'] / $go['amount_goal'] * 100, 0);
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
    <script src="https://cdn.jsdelivr.net/npm/chart.js@3.6.0/dist/chart.min.js"></script>
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
                    <a class="nav-link actives" href="goal.php">Goal</a>
                    <a class="nav-link" href="loan.php">Loan</a>
                    <a class="nav-link" href="profile.php">Profile</a>
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
                <a href="goal.php" class="nav-link active">
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
            <li class="nav-item">
                <a href="profile.php" class="nav-link">
                    <span>
                        <i class="fas fa-user"></i>
                    </span>
                </a>
            </li>
        </ul>
    </nav>

    <section id="info-budget" class="info-budget goal">
        <div class="container">
            <div class="row">
                <div class="col-xl-12 mt-3">
                    <div class="card">
                        <div class="card-body">
                            <p class="card-text text-center">Goal</p>
                            <h3 class="card-text mt-3 text-success text-center"><b><?= $total; ?>%</b></h3>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <section id="incomes" class="incomes buttom mt-5">
        <div class="container">
            <div class="row">
                <div class="col">
                    <h3>Goal</h3>
                </div>
                <div class="col text-end">
                    <a href="addGoal.php" class="btn btn-success"><i class="fas fa-plus"></i></a>
                </div>
            </div>
            <div class="row list mt-3">
                <?php if (count($goals) >= 1) : ?>
                    <?php foreach ($goals as $goal) : ?>
                        <div class="col-xl-6 mb-3">
                            <div class="card list-incomes" onclick="document.location.href = 'detailGoal.php?code_goal=<?= $goal['code_goal']; ?>';">
                                <div class="card-body">
                                    <div class="row d-flex align-items-center">
                                        <div class="col-2 text-center">
                                            <span>
                                                <i class="fas fa-bullseye"></i>
                                            </span>
                                        </div>
                                        <div class="col-5">
                                            <h6 class="card-text text-start"><?= $goal['name_goal']; ?></h6>
                                            <div class="progress" style="height: 5px; width: 100%">
                                                <div class="progress-bar bg-primary" role="progressbar" style="width: <?= round($goal['amount2_goal'] / $goal['amount_goal'] * 100, 0); ?>%" aria-valuenow="<?= round($goal['amount2_goal'] / $goal['amount_goal'] * 100, 0); ?>" aria-valuemin="0" aria-valuemax="100"></div>
                                            </div>
                                        </div>
                                        <div class="col-5">
                                            <div class="mb-2 text-end">
                                                <span class="card-text status"><?= $goal['status_goal']; ?></span>
                                            </div>
                                            <p class="card-text text-success text-end"><b>IDR <?= number_format($goal['amount_goal'], 2, ',', '.'); ?></b></p>

                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    <?php endforeach; ?>
                <?php else : ?>
                    <div class="col-xl-12 mt-3">
                        <div class="card list-incomes empty" onclick="document.location.href = 'addGoal.php';">
                            <div class="card-body text-center mt-3">
                                <span style="font-size: 50px;">
                                    <i class="fas fa-bullseye"></i>
                                </span>
                                <h3 class="card-title mt-4">Goal Empty</h3>
                                <p class="text-secondary">Oops! Your goal data is empty. Please add your goal data.</p>
                            </div>
                        </div>
                    </div>
                <?php endif; ?>
            </div>
        </div>
    </section>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.bundle.min.js" integrity="sha384-ka7Sk0Gln4gmtz2MlQnikT1wXgYsOg+OMhuP+IlRH9sENBO0LRn5q+8nbTov4+1p" crossorigin="anonymous"></script>

</html>