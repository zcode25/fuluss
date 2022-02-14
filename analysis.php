<?php

session_start();
if (!isset($_SESSION["login"])) {
    header("Location: index.php");
    exit;
}

require 'functions.php';
$id_user = $_SESSION["id_user"];
$date_income = query("SELECT DATE_FORMAT(date_income,'%M %Y') AS tanggal FROM income WHERE id_user = '$id_user' GROUP BY YEAR (date_income), MONTH (date_income) ORDER BY YEAR (date_income) DESC, MONTH (date_income) DESC");

if (count($date_income) >= 1) {
    $date = $date_income[0]['tanggal'];
    $tgl = date_create($date);
    $bulan = DATE_FORMAT($tgl, 'm');
    $tahun = DATE_FORMAT($tgl, 'Y');
}


if (isset($_POST["filter"])) {
    $date = $_POST['date_income'];
    $tgl = date_create($date);
    $bulan = DATE_FORMAT($tgl, 'm');
    $tahun = DATE_FORMAT($tgl, 'Y');
}

if (count($date_income) >= 1) {
    $incomes = query("SELECT DATE_FORMAT(date_income,'%d') AS tanggal, SUM(budget_income) AS budget_income FROM income WHERE id_user = '$id_user' AND month(date_income)='$bulan' AND year(date_income) = '$tahun' GROUP BY (date_income)");
    $category_income = query("SELECT icon_category_income, name_category_income, SUM(budget_income) AS budget_income FROM income JOIN category_income USING(code_category_income) WHERE id_user = '$id_user' AND month(date_income)='$bulan' AND year(date_income) = '$tahun' GROUP BY (code_category_income) ORDER BY( SUM(budget_income)) DESC");
}

if (count($date_income) >= 1) {
    foreach ($incomes as $inc) {
        $label[] = $inc["tanggal"];
        $data[] = $inc["budget_income"];
    }

    $total = array_sum($data);
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
                    <a class="nav-link actives" href="analysis.php">Analysis</a>
                    <a class="nav-link" href="goal.php">Goal</a>
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
                <a href="analysis.php" class="nav-link active">
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
            <li class="nav-item">
                <a href="profile.php" class="nav-link">
                    <span>
                        <i class="fas fa-user"></i>
                    </span>
                </a>
            </li>
        </ul>
    </nav>


    <section id="analysis" class="analysis">
        <div class="container">
            <div class="row">
                <div class="col-xl-12 mt-3">
                    <div class="card">
                        <div class="card-body">
                            <div class="row">
                                <div class="col">
                                    <div class="d-grid gap-2">
                                        <button class="btn btn-primary" type="submit" name="submit" onclick="document.location.href='analysis.php'">Income</button>
                                    </div>
                                </div>
                                <div class="col">
                                    <div class="d-grid gap-2">
                                        <button class="btn btn-light" type="submit" name="submit" onclick="document.location.href='analysis2.php'">Expense</button>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <?php if (count($date_income) >= 1) : ?>
        <section id="filter" class="filter">
            <div class="container">
                <div class="row">
                    <div class="col-xl-12 mt-3">
                        <div class="card">
                            <div class="card-body">
                                <form action="" method="POST">
                                    <div class="row">
                                        <div class="col-8">
                                            <select class="form-select" id="date_income" name="date_income">
                                                <?php foreach ($date_income as $date_inc) : ?>
                                                    <option value="<?= $date_inc["tanggal"]; ?>" <?php if ($date_inc["tanggal"] == $date) {
                                                                                                        echo 'selected';
                                                                                                    } ?>><?= $date_inc["tanggal"]; ?></option>
                                                <?php endforeach; ?>
                                            </select>
                                        </div>
                                        <div class="col">
                                            <div class="d-grid gap-2">
                                                <button class="btn btn-light" type="submit" name="filter">Filter</button>
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

        <section id="info-budget" class="info-budget">
            <div class="container">
                <div class="row">
                    <div class="col-xl-12 mt-3">
                        <div class="card">
                            <div class="card-body">
                                <p class="card-text text-center">Income</p>
                                <h3 class="card-text mt-3 text-success text-center"><b><i class="fas fa-angle-double-up"></i> IDR <?= number_format($total, 2, ',', '.'); ?></b></h3>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </section>

        <section id="chart" class="chart">
            <div class="container">
                <div class="row">
                    <div class="col-xl-12 mt-3">
                        <div class="card">
                            <div class="card-body">
                                <div>
                                    <canvas id="myChart"></canvas>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </section>

        <section id="incomes" class="incomes mt-5 buttom">
            <div class="container">
                <div class="row">
                    <div class="col">
                        <h3>Income Details</h3>
                    </div>
                </div>
                <div class="row list mt-3">
                    <?php foreach ($category_income as $income) : ?>
                        <div class="col-xl-6 mb-3">
                            <div class="card list-incomes detail">
                                <div class="card-body">
                                    <div class="row d-flex align-items-center">
                                        <div class="col-2 text-center">
                                            <span>
                                                <i class="fas fa-<?= $income['icon_category_income']; ?>"></i>
                                            </span>
                                        </div>
                                        <div class="col-5">
                                            <h6 class="card-text text-start"><?= $income['name_category_income']; ?></h6>
                                            <div class="progress" style="height: 5px; width: 100%">
                                                <div class="progress-bar bg-primary" role="progressbar" style="width: <?= round($income['budget_income'] / $total * 100, 0); ?>%" aria-valuenow="<?= round($income['budget_income'] / $total * 100, 0); ?>" aria-valuemin="0" aria-valuemax="100"></div>
                                            </div>
                                        </div>
                                        <div class="col-5">
                                            <p class="card-text text-success text-end"><b>IDR <?= number_format($income['budget_income'], 2, ',', '.'); ?></b></p>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    <?php endforeach; ?>
                </div>
            </div>
        </section>
    <?php else : ?>
        <section id="incomes" class="incomes buttom">
            <div class="container">
                <div class="row">
                    <div class="col-xl-12 mt-3">
                        <div class="card list-incomes empty" onclick="document.location.href = 'addIncome.php';">
                            <div class="card-body text-center mt-3">
                                <span style="font-size: 50px;">
                                    <i class="fas fa-angle-double-up"></i>
                                </span>
                                <h3 class="card-title mt-4">Income Empty</h3>
                                <p class="text-secondary">Oops! Your income data is empty. Please add your income data.</p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </section>
    <?php endif; ?>

    <script>
        const labels = <?= json_encode($label); ?>;
        const data = {
            labels: labels,
            datasets: [{
                label: '<?= $date; ?>',
                data: <?= json_encode($data); ?>,
                backgroundColor: '#00b3ff1e',
                borderColor: '#00B1FF',
                pointBackgroundColor: '#00B1FF',
                pointHitRadius: '30',
                fill: true,
                tension: 0.3
            }]
        };

        const config = {
            type: 'line',
            data: data,
            options: {
                responsive: true,
                maintainAspectRatio: false
            }
        };

        const myChart = new Chart(
            document.getElementById('myChart'),
            config
        );
    </script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.bundle.min.js" integrity="sha384-ka7Sk0Gln4gmtz2MlQnikT1wXgYsOg+OMhuP+IlRH9sENBO0LRn5q+8nbTov4+1p" crossorigin="anonymous"></script>

</html>