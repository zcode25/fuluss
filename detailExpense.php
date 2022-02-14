<?php

session_start();
if (!isset($_SESSION["login"])) {
    header("Location: index.php");
    exit;
}

require 'functions.php';
$id_user = $_SESSION["id_user"];
$code_expense = $_GET["code_expense"];
$expense = query("SELECT * FROM expense JOIN category_expense USING(code_category_expense) WHERE code_expense = '$code_expense'")[0];


if ($expense["code_category_expense"] == 'D0010') {
    $loan = query("SELECT * FROM loan WHERE code_expense = '$code_expense'")[0];
    if ($loan["category_loan"] == "Give") {

        echo "
        
        <script>
        document.location.href = 'detailLoan.php?code_loan=" . $loan['code_loan'] . "';
        </script>
        
        ";
    } else {
        echo "
        
        <script>
        document.location.href = 'detailLoan2.php?code_loan=" . $loan['code_loan'] . "';
        </script>
        
        ";
    }
}

if ($expense["code_category_expense"] == 'D0021') {
    $goal = query("SELECT * FROM goal WHERE code_expense = '$code_expense'")[0];
    echo "
        
        <script>
        document.location.href = 'detailGoal.php?code_goal=" . $goal['code_goal'] . "';
        </script>
        
        ";
}

$budget = query("SELECT * FROM budget WHERE id_user = '$id_user'")[0];
$category_expenses = query("SELECT * FROM category_expense WHERE NOT code_category_expense = 'D0010' AND NOT code_category_expense = 'D0021' ORDER BY (name_category_expense) ASC");

if (isset($_POST["submit"])) {

    if (editExpense($_POST) > 0) {
        echo "

      <script>
        alert('Edit expense successfully');
        document.location.href = 'home.php';
      </script>

    ";
    } else {

        echo "

      <script>
        alert('Edit expense failed');
      </script>

    ";
    }
}

if (isset($_POST["delete"])) {

    if (deleteExpense($_POST) > 0) {
        echo "

      <script>
        alert('Delete expense successfully');
        document.location.href = 'home.php';
      </script>

    ";
    } else {

        echo "

      <script>
        alert('Delete expense failed');
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

    <section id="detailExpense" class="detailExpense">
        <div class="container">
            <div class="row d-flex justify-content-center">
                <div class=" col-xl-6 mt-5 mb-5">
                    <div class="card">
                        <div class="card-body">
                            <h3 class="card-title">Detail Expense</h3>
                            <form class="mt-3" action="" method="post">
                                <input class="form-control" type="hidden" id="code_expense" name="code_expense" value="<?= $code_expense; ?>" required>
                                <input class="form-control" type="hidden" id="id_user" name="id_user" value="<?= $id_user; ?>" required>
                                <input class="form-control" type="hidden" id="code_budget" name="code_budget" value="<?= $budget["code_budget"]; ?>" required>
                                <div class="mb-3">
                                    <label for="date_expense" class="form-label">Expense Date</label>
                                    <input type="date" class="form-control" id="date_expense" name="date_expense" value="<?= $expense["date_expense"]; ?>" autofocus required>
                                </div>
                                <div class="mb-3">
                                    <label for="category_expense" class="form-label">Expense Category</label>
                                    <select class="form-select" id="category_expense" name="category_expense">
                                        <?php foreach ($category_expenses as $category_expense) : ?>
                                            <option value="<?= $category_expense["code_category_expense"]; ?>" <?php if ($category_expense["code_category_expense"] == $expense['code_category_expense']) {
                                                                                                                    echo 'selected';
                                                                                                                } ?>><?= $category_expense["name_category_expense"]; ?></option>
                                        <?php endforeach; ?>
                                    </select>
                                </div>
                                <div class="mb-3">
                                    <label for="note_expense" class="form-label">Expense Note</label>
                                    <textarea class="form-control" id="note_expense" name="note_expense" rows="3" required><?= $expense["note_expense"]; ?></textarea>
                                </div>
                                <div class="mb-3">
                                    <label for="budget_expense" class="form-label">Expense Budget</label>
                                    <input type="number" class="form-control" id="budget_expense" name="budget_expense" value="<?= $expense["budget_expense"]; ?>" required>
                                </div>
                                <div class="row">
                                    <div class="col-6">
                                        <div class="d-grid gap-2">
                                            <button class="btn btn-outline-danger" type="submit" name="delete" onclick=" return confirm ('Are you sure?');">Delete</button>
                                        </div>
                                    </div>
                                    <div class="col-6">
                                        <div class="d-grid gap-2">
                                            <button class="btn btn-danger" type="submit" name="submit">Edit</button>
                                        </div>
                                    </div>
                                    <div class="col mt-3">
                                        <div class="d-grid gap-2">
                                            <a href="home.php" class="btn btn-light">Back</a>
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