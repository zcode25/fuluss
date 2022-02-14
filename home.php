<?php

session_start();
if (!isset($_SESSION["login"])) {
    header("Location: index.php");
    exit;
}

require 'functions.php';
$id_user = $_SESSION["id_user"];
$budget = query("SELECT * FROM budget WHERE id_user = '$id_user'")[0];
$incomes = query("SELECT * FROM income JOIN category_income USING(code_category_income) WHERE id_user = '$id_user' ORDER BY (date_income) DESC, (code_income) DESC LIMIT 6");
$expenses = query("SELECT * FROM expense JOIN category_expense USING(code_category_expense) WHERE id_user = '$id_user' ORDER BY (date_expense) DESC, (code_expense) DESC LIMIT 6");
if (isset($_POST["viewIncome"])) {
    $incomes = query("SELECT * FROM income JOIN category_income USING(code_category_income) WHERE id_user = '$id_user' ORDER BY (date_income) DESC");
}
if (isset($_POST["viewExpense"])) {
    $expenses = query("SELECT * FROM expense JOIN category_expense USING(code_category_expense) WHERE id_user = '$id_user' ORDER BY (date_expense) DESC");
}
$budget_income = query("SELECT SUM(budget_income) AS budget_income FROM income WHERE id_user = '$id_user'")[0];
$budget_expense = query("SELECT SUM(budget_expense) AS budget_expense FROM expense WHERE id_user = '$id_user'")[0];

if (isset($_POST["submitBudget"])) {

    if (setBudget($_POST) > 0) {
        echo "

      <script>
        alert('Set budget successfully');
        document.location.href = 'home.php';
      </script>

    ";
    } else {

        echo "

      <script>
        alert('Set budget failed');
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
                    <a class="nav-link actives" href="home.php">Home</a>
                    <a class="nav-link" href="analysis.php">Analysis</a>
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
                <a href="home.php" class="nav-link active">
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
            <li class="nav-item">
                <a href="profile.php" class="nav-link">
                    <span>
                        <i class="fas fa-user"></i>
                    </span>
                </a>
            </li>
        </ul>
    </nav>

    <section id="budget" class="budget">
        <div class="container">
            <div class="row">
                <div class="col-xl-4 mt-3">
                    <div class="card balance">
                        <div class="card-body">
                            <p class="card-text text-center">My Balance </p>
                            <h3 class="card-text mt-3 text-center"><b>IDR <?= number_format($budget["money"], 2, ',', '.'); ?></b> <button type="button" class="btn btn-light btn-sm ms-1" data-bs-toggle="modal" data-bs-target="#noteSetBudget"><i class="fas fa-plus"></i></button></h3>
                        </div>
                    </div>
                </div>
                <div class="col-xl-4 mt-3">
                    <div class="card">
                        <div class="card-body">
                            <p class="card-text text-center">Income</p>
                            <h3 class="card-text mt-3 text-success text-center"><b><i class="fas fa-angle-double-up"></i> IDR <?= number_format($budget_income["budget_income"], 2, ',', '.'); ?></b></h3>
                        </div>
                    </div>
                </div>
                <div class="col-xl-4 mt-3">
                    <div class="card">
                        <div class="card-body">
                            <p class="card-text text-center">Expense</p>
                            <h3 class="card-text mt-3 text-danger text-center"><b><i class="fas fa-angle-double-down"></i> IDR <?= number_format($budget_expense["budget_expense"], 2, ',', '.'); ?></b></h3>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <section id="incomes" class="incomes mt-5">
        <div class="container">
            <div class="row">
                <div class="col">
                    <h3>Incomes</h3>
                </div>
                <div class="col text-end">
                    <a href="addIncome.php" class="btn btn-success"><i class="fas fa-angle-double-up"></i></a>
                </div>
            </div>
            <div class="row list mt-3">
                <?php if (count($incomes) >= 1) : ?>
                    <?php foreach ($incomes as $income) : ?>
                        <div class="col-xl-6 mb-3">
                            <div class="card list-incomes" onclick="document.location.href = 'detailIncome.php?code_income=<?= $income['code_income']; ?>';">
                                <div class="card-body">
                                    <div class="row d-flex align-items-center">
                                        <div class="col-2 text-center">
                                            <span>
                                                <i class="fas fa-<?= $income['icon_category_income']; ?>"></i>
                                            </span>
                                        </div>
                                        <div class="col-5">
                                            <h6 class="card-text text-start"><?= $income['name_category_income']; ?></h6>
                                            <?php $tgl = date_create($income['date_income']); ?>
                                            <p class="card-text text-start text-secondary" style="font-size: 14px;"><?= date_format($tgl, 'd F Y') ?></p>
                                        </div>
                                        <div class="col-5">
                                            <p class="card-text text-success text-end"><b>IDR <?= number_format($income['budget_income'], 2, ',', '.'); ?></b></p>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    <?php endforeach; ?>
                    <?php if (!isset($_POST["viewIncome"]) && count($incomes) >= 6) : ?>
                        <form action="" method="post" class="text-center">
                            <button class="mt-3 btn btn-link text-dark" type="submit" name="viewIncome">View all income</button>
                        </form>
                    <?php endif; ?>
                <?php else : ?>
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
                <?php endif; ?>
            </div>
        </div>
    </section>

    <section id="expense" class="expense mt-5">
        <div class="container">
            <div class="row">
                <div class="col">
                    <h3>Expenses</h3>
                </div>
                <div class="col text-end">
                    <a href="addExpense.php" class="btn btn-danger"><i class="fas fa-angle-double-down"></i></a>
                </div>
            </div>
            <div class="row list mt-3">
                <?php if (count($expenses) >= 1) : ?>
                    <?php foreach ($expenses as $expense) : ?>
                        <div class="col-xl-6 mb-3">
                            <div class="card list-expense" onclick="document.location.href = 'detailExpense.php?code_expense=<?= $expense['code_expense']; ?>';">
                                <div class="card-body">
                                    <div class="row d-flex align-items-center">
                                        <div class="col-2 text-center">
                                            <span>
                                                <i class="fas fa-<?= $expense['icon_category_expense']; ?>"></i>
                                            </span>
                                        </div>
                                        <div class="col-5">
                                            <h6 class="card-text text-start"><?= $expense['name_category_expense']; ?></h6>
                                            <?php $tgl = date_create($expense['date_expense']); ?>
                                            <p class="card-text text-start text-secondary" style="font-size: 14px;"><?= date_format($tgl, 'd F Y') ?></p>
                                        </div>
                                        <div class="col-5">
                                            <p class="card-text text-danger text-end"><b>IDR <?= number_format($expense['budget_expense'], 2, ',', '.'); ?></b></p>
                                        </div>
                                    </div>

                                </div>
                            </div>
                        </div>
                    <?php endforeach; ?>
                    <?php if (!isset($_POST["viewExpense"]) && count($expenses) >= 6) : ?>
                        <form action="" method="post" class="text-center">
                            <button class="mt-3 btn btn-link text-dark" type="submit" name="viewExpense">View all expense</button>
                        </form>
                    <?php endif; ?>
                <?php else : ?>
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
                <?php endif; ?>
            </div>
        </div>
    </section>

    <div class="modal fade" id="noteSetBudget" class="noteSetBudget" tabindex="-1">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="exampleModalToggleLabel">Note</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <p class="justify">Enter the current budget correctly and<span class="fw-bold"> if you make a budget change, your data will be deleted</span>, for the sake of balancing the data on your account.</p>
                </div>
                <div class="modal-footer">
                    <button class="btn btn-primary" data-bs-target="#setBudget" data-bs-toggle="modal">Set Budget</button>
                </div>
            </div>
        </div>
    </div>
    <div class="modal fade" id="setBudget" class="setBudget" tabindex="-1">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Set Budget</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <form action="" method="post">
                    <div class="modal-body">
                        <input type="hidden" class="form-control" id="code_budget" name="code_budget" value="<?= $budget["code_budget"]; ?>">
                        <input type="hidden" class="form-control" id="id_user" name="id_user" value="<?= $budget["id_user"]; ?>">
                        <div class="mb-3">
                            <label for="inputBudget" class="col-form-label">Budget</label>
                            <input type="number" class="form-control" id="inputBudget" name="inputBudget" autofocus required>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="submit" name="submitBudget" class="btn btn-primary">Submit</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.bundle.min.js" integrity="sha384-ka7Sk0Gln4gmtz2MlQnikT1wXgYsOg+OMhuP+IlRH9sENBO0LRn5q+8nbTov4+1p" crossorigin="anonymous"></script>

</html>