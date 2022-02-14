<?php

session_start();
if (!isset($_SESSION["login"])) {
    header("Location: index.php");
    exit;
}

require 'functions.php';
$id_user = $_SESSION["id_user"];
$loans = query("SELECT * FROM loan WHERE category_loan = 'Give' AND id_user = '$id_user' ORDER BY (date_loan) DESC, (code_loan) DESC");
$total = query("SELECT SUM(amount_loan) AS total FROM loan  WHERE category_loan = 'Give' AND id_user = '$id_user'")[0];

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
                    <a class="nav-link" href="goal.php">Goal</a>
                    <a class="nav-link actives" href="loan.php">Loan</a>
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
                <a href="goal.php" class="nav-link">
                    <span>
                        <i class="fas fa-bullseye"></i>
                    </span>
                </a>
            </li>
            <li class="nav-item">
                <a href="loan.php" class="nav-link active">
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


    <section id="loan" class="loan">
        <div class="container">
            <div class="row">
                <div class="col-xl-12 mt-3">
                    <div class="card">
                        <div class="card-body">
                            <div class="row">
                                <div class="col">
                                    <div class="d-grid gap-2">
                                        <button class="btn btn-primary" type="submit" name="submit" onclick="document.location.href='loan.php'">Give</button>
                                    </div>
                                </div>
                                <div class="col">
                                    <div class="d-grid gap-2">
                                        <button class="btn btn-light" type="submit" name="submit" onclick="document.location.href='loan2.php'">Accept</button>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <?php if (count($loans) >= 1) : ?>
        <section id="info-budget" class="info-budget">
            <div class="container">
                <div class="row">
                    <div class="col-xl-12 mt-3">
                        <div class="card">
                            <div class="card-body">
                                <p class="card-text text-center">Give Loan</p>
                                <h3 class="card-text mt-3 text-success text-center"><b>IDR <?= number_format($total['total'], 2, ',', '.'); ?></b></h3>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </section>
    <?php endif; ?>

    <section id="incomes" class="incomes buttom mt-5">
        <div class="container">
            <div class="row">
                <div class="col">
                    <h3>Give Loan</h3>
                </div>
                <div class="col text-end">
                    <a href="addLoan.php" class="btn btn-success"><i class="fas fa-plus"></i></a>
                </div>
            </div>
            <div class="row list mt-3">
                <?php if (count($loans) >= 1) : ?>
                    <?php foreach ($loans as $loan) : ?>
                        <div class="col-xl-6 mb-3">
                            <div class="card list-incomes" onclick="document.location.href = 'detailLoan.php?code_loan=<?= $loan['code_loan']; ?>';">
                                <div class="card-body">
                                    <div class="row d-flex align-items-center">
                                        <div class="col-2 text-center">
                                            <span>
                                                <i class="fas fa-money-check-alt"></i>
                                            </span>
                                        </div>
                                        <div class="col-5">

                                            <h6 class="card-text text-start"><?= $loan['name_loan']; ?></h6>
                                            <?php $tgl = date_create($loan['date_loan']); ?>
                                            <p class="card-text text-start text-secondary" style="font-size: 14px;"><?= date_format($tgl, 'd F Y') ?></p>
                                        </div>
                                        <div class="col-5">
                                            <div class="mb-2 text-end">
                                                <span class="card-text status"><?= $loan['status_loan']; ?></span>
                                            </div>
                                            <p class="card-text text-success text-end"><b>IDR <?= number_format($loan['amount_loan'], 2, ',', '.'); ?></b></p>

                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    <?php endforeach; ?>
                <?php else : ?>
                    <div class="col-xl-12 mt-3">
                        <div class="card list-incomes empty" onclick="document.location.href = 'addLoan.php';">
                            <div class="card-body text-center mt-3">
                                <span style="font-size: 50px;">
                                    <i class="fas fa-money-check-alt"></i>
                                </span>
                                <h3 class="card-title mt-4">Give Loan Empty</h3>
                                <p class="text-secondary">Oops! Your give loan data is empty. Please add your give loan data.</p>
                            </div>
                        </div>
                    </div>
                <?php endif; ?>
            </div>
        </div>
    </section>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.bundle.min.js" integrity="sha384-ka7Sk0Gln4gmtz2MlQnikT1wXgYsOg+OMhuP+IlRH9sENBO0LRn5q+8nbTov4+1p" crossorigin="anonymous"></script>

</html>