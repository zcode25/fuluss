<?php

session_start();
if (!isset($_SESSION["login"])) {
    header("Location: index.php");
    exit;
}

require 'functions.php';
$id_user = $_SESSION["id_user"];
$loan = query("SELECT max(code_loan) as kode_terbesar FROM loan")[0];
$code_loan = $loan["kode_terbesar"];
$urutan = (int) substr($code_loan, 1, 4);

$urutan++;
$huruf = "L";
$code_loan = $huruf . sprintf("%04s", $urutan);


$budget = query("SELECT * FROM budget WHERE id_user = '$id_user'")[0];

if (isset($_POST["submit"])) {

    if (addAcceptLoan($_POST) > 0) {
        echo "

      <script>
        alert('Add accept loan successfully');
        document.location.href = 'loan2.php';
      </script>

    ";
    } else {

        echo "

      <script>
        alert('Add accept loan failed');
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

    <section id="addLoan" class="addLoan">
        <div class="container">
            <div class="row d-flex justify-content-center">
                <div class=" col-xl-6 mt-5 mb-5">
                    <div class="card">
                        <div class="card-body">
                            <h3 class="card-title">Add Accept Loan</h3>
                            <form class="mt-3" action="" method="post">
                                <input class="form-control" type="hidden" id="code_loan" name="code_loan" value="<?= $code_loan; ?>" required>
                                <input class="form-control" type="hidden" id="id_user" name="id_user" value="<?= $id_user; ?>" required>
                                <input class="form-control" type="hidden" id="code_budget" name="code_budget" value="<?= $budget["code_budget"]; ?>" required>
                                <input class="form-control" type="hidden" id="category_loan" name="category_loan" value="Accept" required>
                                <input class="form-control" type="hidden" id="status_loan" name="status_loan" value="Not Yet Paid Off" required>
                                <div class="mb-3">
                                    <label for="date_loan" class="form-label">Loan Date</label>
                                    <input type="date" class="form-control" id="date_loan" name="date_loan" autofocus required>
                                </div>
                                <div class="mb-3">
                                    <label for="due_date_loan" class="form-label">Loan Due Date</label>
                                    <input type="date" class="form-control" id="due_date_loan" name="due_date_loan" required>
                                </div>
                                <div class="mb-3">
                                    <label for="name_loan" class="form-label">Loan Name</label>
                                    <input type="text" class="form-control" id="name_loan" name="name_loan" required>
                                </div>
                                <div class="mb-3">
                                    <label for="tel_loan" class="form-label">Telephone</label>
                                    <input type="tel" class="form-control" id="tel_loan" name="tel_loan" required>
                                </div>
                                <div class="mb-3">
                                    <label for="note_loan" class="form-label">Loan Note</label>
                                    <textarea class="form-control" id="note_loan" name="note_loan" rows="3" required></textarea>
                                </div>
                                <div class="mb-3">
                                    <label for="amount_loan" class="form-label">Loan Amount</label>
                                    <input type="number" class="form-control" id="amount_loan" name="amount_loan" required>
                                </div>
                                <div class="row">
                                    <div class="col">
                                        <div class="d-grid gap-2">
                                            <a href="loan2.php" class="btn btn-light">Back</a>
                                        </div>
                                    </div>
                                    <div class="col">
                                        <div class="d-grid gap-2">
                                            <button class="btn btn-danger" type="submit" name="submit">Submit</button>
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