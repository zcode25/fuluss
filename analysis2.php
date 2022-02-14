<?php

session_start();
if (!isset($_SESSION["login"])) {
    header("Location: index.php");
    exit;
}

require 'functions.php';
$id_user = $_SESSION["id_user"];
$date_expense = query("SELECT DATE_FORMAT(date_expense,'%M %Y') AS tanggal FROM expense WHERE id_user = '$id_user' GROUP BY YEAR (date_expense), MONTH (date_expense) ORDER BY YEAR (date_expense) DESC, MONTH (date_expense) DESC");

if (count($date_expense) >= 1) {
    $date = $date_expense[0]['tanggal'];
    $tgl = date_create($date);
    $bulan = DATE_FORMAT($tgl, 'm');
    $tahun = DATE_FORMAT($tgl, 'Y');
}

if (isset($_POST["filter"])) {
    $date = $_POST['date_expense'];
    $tgl = date_create($date);
    $bulan = DATE_FORMAT($tgl, 'm');
    $tahun = DATE_FORMAT($tgl, 'Y');
}

if (count($date_expense) >= 1) {
    $expenses = query("SELECT DATE_FORMAT(date_expense,'%d') AS tanggal, SUM(budget_expense) AS budget_expense FROM expense WHERE id_user = '$id_user' AND month(date_expense)='$bulan' AND year(date_expense) = '$tahun' GROUP BY (date_expense)");
    $category_expense = query("SELECT icon_category_expense, name_category_expense, SUM(budget_expense) AS budget_expense FROM expense JOIN category_expense USING(code_category_expense) WHERE id_user = '$id_user' AND month(date_expense)='$bulan' AND year(date_expense) = '$tahun' GROUP BY (code_category_expense) ORDER BY( SUM(budget_expense)) DESC");
}

if (count($date_expense) >= 1) {
    foreach ($expenses as $exp) {
        $label[] = $exp["tanggal"];
        $data[] = $exp["budget_expense"];
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
                                        <button class="btn btn-light" type="submit" name="submit" onclick="document.location.href='analysis.php'">Income</button>
                                    </div>
                                </div>
                                <div class="col">
                                    <div class="d-grid gap-2">
                                        <button class="btn btn-danger" type="submit" name="submit" onclick="document.location.href='analysis2.php'">Expense</button>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <?php if (count($date_expense) >= 1) : ?>
        <section id="filter" class="filter">
            <div class="container">
                <div class="row">
                    <div class="col-xl-12 mt-3">
                        <div class="card">
                            <div class="card-body">
                                <form action="" method="POST">
                                    <div class="row">
                                        <div class="col-8">
                                            <select class="form-select" id="date_expense" name="date_expense">
                                                <?php foreach ($date_expense as $date_exp) : ?>
                                                    <option value="<?= $date_exp["tanggal"]; ?>" <?php if ($date_exp["tanggal"] == $date) {
                                                                                                        echo 'selected';
                                                                                                    } ?>><?= $date_exp["tanggal"]; ?></option>
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
                                <p class="card-text text-center">Expense</p>
                                <h3 class="card-text mt-3 text-danger text-center"><b><i class="fas fa-angle-double-down"></i> IDR <?= number_format($total, 2, ',', '.'); ?></b></h3>
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

        <section id="expense" class="expense mt-5 buttom">
            <div class="container">
                <div class="row">
                    <div class="col">
                        <h3>Expense Details</h3>
                    </div>
                </div>
                <div class="row list mt-3">
                    <?php foreach ($category_expense as $expense) : ?>
                        <div class="col-xl-6 mb-3">
                            <div class="card list-expense detail">
                                <div class="card-body">
                                    <div class="row d-flex align-items-center">
                                        <div class="col-2 text-center">
                                            <span>
                                                <i class="fas fa-<?= $expense['icon_category_expense']; ?>"></i>
                                            </span>
                                        </div>
                                        <div class="col-5">
                                            <h6 class="card-text text-start"><?= $expense['name_category_expense']; ?></h6>
                                            <div class="progress" style="height: 5px; width: 100%">
                                                <div class="progress-bar bg-danger" role="progressbar" style="width: <?= round($expense['budget_expense'] / $total * 100, 0); ?>%" aria-valuenow="<?= round($expense['budget_expense'] / $total * 100, 0); ?>" aria-valuemin="0" aria-valuemax="100"></div>
                                            </div>
                                        </div>
                                        <div class="col-5">
                                            <p class="card-text text-danger text-end"><b>IDR <?= number_format($expense['budget_expense'], 2, ',', '.'); ?></b></p>
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
        <section id="expense" class="expense">
            <div class="container">
                <div class="row">
                    <div class="col-xl-12 mt-3">
                        <div class="card list-expense empty" onclick="document.location.href = 'addExpense.php';">
                            <div class="card-body text-center mt-3">
                                <span style="font-size: 50px;">
                                    <i class="fas fa-angle-double-down"></i>
                                </span>
                                <h3 class="card-title mt-4">Expense Empty</h3>
                                <p class="text-secondary">Oops! Your expense data is empty. Please add your expense data.</p>
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
                backgroundColor: '#ff006f1c',
                borderColor: '#FF006F',
                pointBackgroundColor: '#FF006F',
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