<?php

session_start();
if (!isset($_SESSION["login"])) {
    header("Location: index.php");
    exit;
}

require 'functions.php';
$id_user = $_SESSION["id_user"];
$code_loan = $_GET["code_loan"];
$loan = query("SELECT * FROM loan WHERE code_loan = '$code_loan'")[0];
$budget = query("SELECT * FROM budget WHERE id_user = '$id_user'")[0];
if (isset($loan["code_income"])) {
    $date = query("SELECT date_income FROM loan JOIN income USING (code_income) WHERE code_loan = '$code_loan'")[0];
}

if (isset($_POST["submit"])) {

    if (editAcceptLoan($_POST) > 0) {
        echo "

      <script>
        alert('Paid off accept loan successfully');
        document.location.href = 'loan2.php';
      </script>

    ";
    } else {

        echo "

      <script>
        alert('Paid off accept loan failed');
      </script>

    ";
    }
}

if (isset($_POST["delete"])) {

    if (deleteAcceptLoan($_POST) > 0) {
        echo "

      <script>
        alert('Delete loan successfully');
        document.location.href = 'loan2.php';
      </script>

    ";
    } else {

        echo "

      <script>
        alert('Delete loan failed');
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
                            <h3 class="card-title">Detail Accept Loan</h3>
                            <form class="mt-3" action="" method="post">
                                <input class="form-control" type="hidden" id="code_loan" name="code_loan" value="<?= $code_loan; ?>" required>
                                <input class="form-control" type="hidden" id="id_user" name="id_user" value="<?= $id_user; ?>" required>
                                <input class="form-control" type="hidden" id="code_budget" name="code_budget" value="<?= $budget["code_budget"]; ?>" required>
                                <?php if (isset($date["date_income"])) : ?>
                                    <div class="mb-3">
                                        <label for="date_income" class="form-label">Paid Off Date</label>
                                        <input type="date" class="form-control" id="date_income" name="date_income" value="<?= $date["date_income"]; ?>" readonly required>
                                    </div>
                                <?php endif; ?>
                                <div class="mb-3">
                                    <label for="date_loan" class="form-label">Loan Date</label>
                                    <input type="date" class="form-control" id="date_loan" name="date_loan" value="<?= $loan["date_loan"]; ?>" readonly required>
                                </div>
                                <div class="mb-3">
                                    <label for="due_date_loan" class="form-label">Loan Due Date</label>
                                    <input type="date" class="form-control" id="due_date_loan" name="due_date_loan" value="<?= $loan["due_date_loan"]; ?>" readonly required>
                                </div>
                                <div class="mb-3">
                                    <label for="name_loan" class="form-label">Loan Name</label>
                                    <input type="text" class="form-control" id="name_loan" name="name_loan" value="<?= $loan["name_loan"]; ?>" readonly required>
                                </div>
                                <div class="mb-3">
                                    <label for="tel_loan" class="form-label">Telephone</label>
                                    <input type="text" class="form-control" id="tel_loan" name="tel_loan" value="<?= $loan["tel_loan"]; ?>" readonly required>
                                </div>
                                <div class="mb-3">
                                    <label for="note_loan" class="form-label">Loan Note</label>
                                    <textarea class="form-control" id="note_loan" name="note_loan" rows="3" readonly required><?= $loan["note_loan"]; ?></textarea>
                                </div>
                                <div class="mb-3">
                                    <label for="amount_loan" class="form-label">Loan Amount</label>
                                    <input type="number" class="form-control" id="amount_loan" name="amount_loan" value="<?= $loan["amount_loan"]; ?>" readonly required>
                                </div>
                                <div class="row">
                                    <?php if ($loan["status_loan"] != "Paid Off") : ?>
                                        <div class="col-12 mb-3">
                                            <div class="d-grid gap-2">
                                                <button class="btn btn-danger" type="submit" name="submit" onclick=" return confirm ('Are you sure?');">Paid Off</button>
                                            </div>
                                        </div>
                                        <div class="col-6">
                                            <div class="d-grid gap-2">
                                                <button class="btn btn-light text-danger" type="submit" name="delete" onclick=" return confirm ('Are you sure?');">Delete</button>
                                            </div>
                                        </div>
                                        <div class="col-6">
                                            <div class="d-grid gap-2">
                                                <?php $tgl = date_create($loan['due_date_loan']); ?>
                                                <a target="_blank" href="https://api.whatsapp.com/send?phone=<?= $loan["tel_loan"]; ?>&text=Hello%20<?= $loan["name_loan"]; ?>,%20i%20have%20a%20loan%20bill%20to%20you%20of%20IDR%20<?= number_format($loan['amount_loan'], 2, ',', '.'); ?>%20with%20a%20deadline%20of%20<?= date_format($tgl, 'd F Y') ?>.%20I%20will%20pay%20it%20off%20immediately." class="btn btn-outline-danger" type="submit" name="Share">Share</a>
                                            </div>
                                        </div>
                                    <?php endif; ?>
                                    <div class="col mt-3">
                                        <div class="d-grid gap-2">
                                            <a href="loan2.php" class="btn btn-light">Back</a>
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