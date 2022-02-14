<?php

session_start();
if (!isset($_SESSION["login"])) {
    header("Location: index.php");
    exit;
}

require 'functions.php';
$id_user = $_SESSION["id_user"];
$income = query("SELECT max(code_income) as kode_terbesar FROM income")[0];
$code_income = $income["kode_terbesar"];
$urutan = (int) substr($code_income, 1, 4);

$urutan++;
$huruf = "I";
$code_income = $huruf . sprintf("%04s", $urutan);


$budget = query("SELECT * FROM budget WHERE id_user = '$id_user'")[0];
$category_incomes = query("SELECT * FROM category_income WHERE NOT code_category_income = 'C0004' ORDER BY (name_category_income) ASC");

if (isset($_POST["submit"])) {

    if (addIncome($_POST) > 0) {
        echo "

      <script>
        alert('Add income successfully');
        document.location.href = 'home.php';
      </script>

    ";
    } else {

        echo "

      <script>
        alert('Add income failed');
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

    <section id="addIncome" class="addIncome">
        <div class="container">
            <div class="row d-flex justify-content-center">
                <div class=" col-xl-6 mt-5 mb-5">
                    <div class="card">
                        <div class="card-body">
                            <h3 class="card-title">Add Income</h3>
                            <form class="mt-3" action="" method="post">
                                <input class="form-control" type="hidden" id="code_income" name="code_income" value="<?= $code_income; ?>" required>
                                <input class="form-control" type="hidden" id="id_user" name="id_user" value="<?= $id_user; ?>" required>
                                <input class="form-control" type="hidden" id="code_budget" name="code_budget" value="<?= $budget["code_budget"]; ?>" required>
                                <div class="mb-3">
                                    <label for="date_income" class="form-label">Income Date</label>
                                    <input type="date" class="form-control" id="date_income" name="date_income" autofocus required>
                                </div>
                                <div class="mb-3">
                                    <label for="category_income" class="form-label">Income Category</label>
                                    <select class="form-select" id="category_income" name="category_income">
                                        <?php foreach ($category_incomes as $category_income) : ?>
                                            <option value="<?= $category_income["code_category_income"]; ?>"><?= $category_income["name_category_income"]; ?></option>
                                        <?php endforeach; ?>
                                    </select>
                                </div>
                                <div class="mb-3">
                                    <label for="note_income" class="form-label">Income Note</label>
                                    <textarea class="form-control" id="note_income" name="note_income" rows="3" required></textarea>
                                </div>
                                <div class="mb-3">
                                    <label for="budget_income" class="form-label">Income Budget</label>
                                    <input type="number" class="form-control" id="budget_income" name="budget_income" required>
                                </div>
                                <div class="row">
                                    <div class="col">
                                        <div class="d-grid gap-2">
                                            <a href="home.php" class="btn btn-light">Back</a>
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