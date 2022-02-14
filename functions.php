<?php

$conn = mysqli_connect("localhost", "root", "", "budget_tracker");

function query($query)
{
    global $conn;
    $result = mysqli_query($conn, $query);
    $rows = [];
    while ($row = mysqli_fetch_assoc($result)) {
        $rows[] = $row;
    }
    return $rows;
}

function tel($tel_num)
{
    // kadang ada penulisan no hp 0811 239 345
    $tel_num = str_replace(" ", "", $tel_num);
    // kadang ada penulisan no hp (0274) 778787
    $tel_num = str_replace("(", "", $tel_num);
    // kadang ada penulisan no hp (0274) 778787
    $tel_num = str_replace(")", "", $tel_num);
    // kadang ada penulisan no hp 0811.239.345
    $tel_num = str_replace(".", "", $tel_num);

    // cek apakah no hp mengandung karakter + dan 0-9
    if (!preg_match('/[^+0-9]/', trim($tel_num))) {
        // cek apakah no hp karakter 1-3 adalah +62
        if (substr(trim($tel_num), 0, 2) == '62') {
            $tel = trim($tel_num);
        }
        // cek apakah no hp karakter 1 adalah 0
        elseif (substr(trim($tel_num), 0, 1) == '0') {
            $tel = '62' . substr(trim($tel_num), 1);
        }
    }

    return $tel;
}

function signUp($data)
{
    global $conn;

    $id_user = htmlspecialchars($data["id_user"]);
    $name_user = htmlspecialchars($data["name_user"]);
    $email_user = htmlspecialchars($data["email_user"]);
    $tel_user = htmlspecialchars($data["tel_user"]);
    $tel_user =  tel($tel_user);
    $pass_user = sha1($data["pass_user"]);

    $query = "INSERT INTO user VALUES ('$id_user', '$name_user', '$email_user', '$tel_user', '$pass_user')";

    mysqli_query($conn, $query);

    return mysqli_affected_rows($conn);
}

function addBudget($data)
{
    global $conn;
    $budget = query("SELECT max(code_budget) as kode_terbesar FROM budget")[0];
    $code_budget = $budget["kode_terbesar"];
    $urutan = (int) substr($code_budget, 1, 4);

    $urutan++;
    $huruf = "B";
    $code_budget = $huruf . sprintf("%04s", $urutan);

    $id_user = $data;
    $money = 0;

    $query = "INSERT INTO budget VALUES ('$code_budget', '$id_user', $money)";

    mysqli_query($conn, $query);
}

function setBudget($data)
{
    global $conn;
    $code_budget = htmlspecialchars($data["code_budget"]);
    $id_user = htmlspecialchars($data["id_user"]);
    $inputBudget = htmlspecialchars($data["inputBudget"]);

    $query2 = "DELETE FROM income WHERE id_user = '$id_user'";
    mysqli_query($conn, $query2);
    $query3 = "DELETE FROM expense WHERE id_user = '$id_user'";
    mysqli_query($conn, $query3);
    $query4 = "DELETE FROM loan WHERE id_user = '$id_user'";
    mysqli_query($conn, $query4);
    $query5 = "DELETE FROM goal WHERE id_user = '$id_user'";
    mysqli_query($conn, $query5);

    $query = "UPDATE budget SET 
				money = $inputBudget

				WHERE code_budget = '$code_budget' AND id_user = '$id_user'

			";

    mysqli_query($conn, $query);

    return mysqli_affected_rows($conn);
}

function addIncome($data)
{
    global $conn;
    $code_income = htmlspecialchars($data["code_income"]);
    $id_user = htmlspecialchars($data["id_user"]);
    $code_budget = htmlspecialchars($data["code_budget"]);
    $date_income = htmlspecialchars($data["date_income"]);
    $category_income = htmlspecialchars($data["category_income"]);
    $note_income = htmlspecialchars($data["note_income"]);
    $budget_income = htmlspecialchars($data["budget_income"]);

    $query = "INSERT INTO income VALUES ('$code_income', '$date_income', '$category_income', '$note_income', $budget_income, '$id_user')";
    mysqli_query($conn, $query);

    $budget = query("SELECT * FROM budget WHERE id_user = '$id_user'")[0];
    $setBudget = $budget["money"] + $budget_income;

    $query2 = "UPDATE budget SET 
				money = $setBudget

				WHERE code_budget = '$code_budget' AND id_user = '$id_user'

			";

    mysqli_query($conn, $query2);

    return mysqli_affected_rows($conn);
}

function editIncome($data)
{
    global $conn;
    $code_income = htmlspecialchars($data["code_income"]);
    $id_user = htmlspecialchars($data["id_user"]);
    $code_budget = htmlspecialchars($data["code_budget"]);
    $date_income = htmlspecialchars($data["date_income"]);
    $category_income = htmlspecialchars($data["category_income"]);
    $note_income = htmlspecialchars($data["note_income"]);
    $budget_income = htmlspecialchars($data["budget_income"]);

    $budget_income_last = query("SELECT budget_income FROM income WHERE code_income = '$code_income'")[0];

    $budget = query("SELECT * FROM budget WHERE id_user = '$id_user'")[0];
    $setBudget = $budget["money"] - $budget_income_last["budget_income"];

    $query2 = "UPDATE budget SET 
				money = $setBudget

				WHERE code_budget = '$code_budget' AND id_user = '$id_user'

			";

    mysqli_query($conn, $query2);

    $query = "UPDATE income SET 
				date_income = '$date_income',
				code_category_income = '$category_income',
				note_income = '$note_income',
				budget_income = $budget_income

				WHERE code_income = '$code_income' AND id_user = '$id_user'

			";
    mysqli_query($conn, $query);

    $budget2 = query("SELECT * FROM budget WHERE id_user = '$id_user'")[0];
    $setBudget2 = $budget2["money"] + $budget_income;

    $query3 = "UPDATE budget SET 
				money = $setBudget2

				WHERE code_budget = '$code_budget' AND id_user = '$id_user'

			";

    mysqli_query($conn, $query3);

    return mysqli_affected_rows($conn);
}

function deleteIncome($data)
{
    global $conn;
    $code_income = htmlspecialchars($data["code_income"]);
    $id_user = htmlspecialchars($data["id_user"]);
    $code_budget = htmlspecialchars($data["code_budget"]);

    $budget_income_last = query("SELECT budget_income FROM income WHERE code_income = '$code_income'")[0];

    $budget = query("SELECT * FROM budget WHERE id_user = '$id_user'")[0];
    $setBudget = $budget["money"] - $budget_income_last["budget_income"];

    $query2 = "UPDATE budget SET 
				money = $setBudget

				WHERE code_budget = '$code_budget' AND id_user = '$id_user'

			";

    mysqli_query($conn, $query2);

    $query = "DELETE FROM income WHERE code_income = '$code_income' AND id_user = '$id_user'";
    mysqli_query($conn, $query);

    return mysqli_affected_rows($conn);
}


function addExpense($data)
{
    global $conn;
    $code_expense = htmlspecialchars($data["code_expense"]);
    $id_user = htmlspecialchars($data["id_user"]);
    $code_budget = htmlspecialchars($data["code_budget"]);
    $date_expense = htmlspecialchars($data["date_expense"]);
    $category_expense = htmlspecialchars($data["category_expense"]);
    $note_expense = htmlspecialchars($data["note_expense"]);
    $budget_expense = htmlspecialchars($data["budget_expense"]);

    $query = "INSERT INTO expense VALUES ('$code_expense', '$date_expense', '$category_expense', '$note_expense', $budget_expense, '$id_user')";
    mysqli_query($conn, $query);

    $budget = query("SELECT * FROM budget WHERE id_user = '$id_user'")[0];
    $setBudget = $budget["money"] - $budget_expense;

    $query2 = "UPDATE budget SET 
				money = $setBudget

				WHERE code_budget = '$code_budget' AND id_user = '$id_user'

			";

    mysqli_query($conn, $query2);

    return mysqli_affected_rows($conn);
}


function editExpense($data)
{
    global $conn;
    $code_expense = htmlspecialchars($data["code_expense"]);
    $id_user = htmlspecialchars($data["id_user"]);
    $code_budget = htmlspecialchars($data["code_budget"]);
    $date_expense = htmlspecialchars($data["date_expense"]);
    $category_expense = htmlspecialchars($data["category_expense"]);
    $note_expense = htmlspecialchars($data["note_expense"]);
    $budget_expense = htmlspecialchars($data["budget_expense"]);

    $budget_expense_last = query("SELECT budget_expense FROM expense WHERE code_expense = '$code_expense'")[0];

    $budget = query("SELECT * FROM budget WHERE id_user = '$id_user'")[0];
    $setBudget = $budget["money"] + $budget_expense_last["budget_expense"];

    $query2 = "UPDATE budget SET 
				money = $setBudget

				WHERE code_budget = '$code_budget' AND id_user = '$id_user'

			";

    mysqli_query($conn, $query2);

    $query = "UPDATE expense SET 
				date_expense = '$date_expense',
				code_category_expense = '$category_expense',
				note_expense = '$note_expense',
				budget_expense = $budget_expense

				WHERE code_expense = '$code_expense' AND id_user = '$id_user'

			";
    mysqli_query($conn, $query);

    $budget2 = query("SELECT * FROM budget WHERE id_user = '$id_user'")[0];
    $setBudget2 = $budget2["money"] - $budget_expense;

    $query3 = "UPDATE budget SET 
				money = $setBudget2

				WHERE code_budget = '$code_budget' AND id_user = '$id_user'

			";

    mysqli_query($conn, $query3);

    return mysqli_affected_rows($conn);
}

function deleteExpense($data)
{
    global $conn;
    $code_expense = htmlspecialchars($data["code_expense"]);
    $id_user = htmlspecialchars($data["id_user"]);
    $code_budget = htmlspecialchars($data["code_budget"]);

    $budget_expense_last = query("SELECT budget_expense FROM expense WHERE code_expense = '$code_expense'")[0];

    $budget = query("SELECT * FROM budget WHERE id_user = '$id_user'")[0];
    $setBudget = $budget["money"] + $budget_expense_last["budget_expense"];

    $query2 = "UPDATE budget SET 
				money = $setBudget

				WHERE code_budget = '$code_budget' AND id_user = '$id_user'

			";

    mysqli_query($conn, $query2);

    $query = "DELETE FROM expense WHERE code_expense = '$code_expense' AND id_user = '$id_user'";
    mysqli_query($conn, $query);

    return mysqli_affected_rows($conn);
}

function editProfile($data)
{
    global $conn;

    $id_user = htmlspecialchars($data["id_user"]);
    $name_user = htmlspecialchars($data["name_user"]);
    $email_user = htmlspecialchars($data["email_user"]);
    $tel_user = htmlspecialchars($data["tel_user"]);
    $tel_user =  tel($tel_user);

    $query = "UPDATE user SET 
                name_user = '$name_user',
                email_user = '$email_user',
                tel_user = '$tel_user'

                WHERE id_user = '$id_user'
            ";

    mysqli_query($conn, $query);

    return mysqli_affected_rows($conn);
}

function editPassword($data)
{
    global $conn;

    $id_user = htmlspecialchars($data["id_user"]);
    $pass_user = sha1($data["pass_user"]);

    $query = "UPDATE user SET 
                pass_user = '$pass_user'

                WHERE id_user = '$id_user'
            ";

    mysqli_query($conn, $query);

    return mysqli_affected_rows($conn);
}

function deleteAccount($id_user)
{
    global $conn;
    $query2 = "DELETE FROM income WHERE id_user = '$id_user'";
    mysqli_query($conn, $query2);

    $query3 = "DELETE FROM expense WHERE id_user = '$id_user'";
    mysqli_query($conn, $query3);

    $query4 = "DELETE FROM budget WHERE id_user = '$id_user'";
    mysqli_query($conn, $query4);

    $query5 = "DELETE FROM goal WHERE id_user = '$id_user'";
    mysqli_query($conn, $query5);

    $query5 = "DELETE FROM loan WHERE id_user = '$id_user'";
    mysqli_query($conn, $query5);

    $query = "DELETE FROM user WHERE id_user = '$id_user'";
    mysqli_query($conn, $query);

    return mysqli_affected_rows($conn);
}

function addGiveLoan($data)
{

    $expense = query("SELECT max(code_expense) as kode_terbesar FROM expense")[0];
    $code_expense = $expense["kode_terbesar"];
    $urutan = (int) substr($code_expense, 1, 4);

    $urutan++;
    $huruf = "E";
    $code_expense = $huruf . sprintf("%04s", $urutan);

    global $conn;
    $code_loan = htmlspecialchars($data["code_loan"]);
    $id_user = htmlspecialchars($data["id_user"]);
    $code_budget = htmlspecialchars($data["code_budget"]);
    $category_loan = htmlspecialchars($data["category_loan"]);
    $status_loan = htmlspecialchars($data["status_loan"]);
    $date_loan = htmlspecialchars($data["date_loan"]);
    $due_date_loan = htmlspecialchars($data["due_date_loan"]);
    $name_loan = htmlspecialchars($data["name_loan"]);
    $tel_loan = htmlspecialchars($data["tel_loan"]);
    $tel_loan =  tel($tel_loan);
    $note_loan = htmlspecialchars($data["note_loan"]);
    $amount_loan = htmlspecialchars($data["amount_loan"]);

    $query2 = "INSERT INTO expense VALUES ('$code_expense', '$date_loan', 'D0010', '$note_loan', $amount_loan, '$id_user')";
    mysqli_query($conn, $query2);

    $query = "INSERT INTO loan VALUES ('$code_loan', '$note_loan', $amount_loan, '$date_loan', '$due_date_loan', '$name_loan', '$tel_loan', '$category_loan', '$status_loan', '$id_user', NULL, '$code_expense')";
    mysqli_query($conn, $query);

    $budget = query("SELECT * FROM budget WHERE id_user = '$id_user'")[0];
    $setBudget = $budget["money"] - $amount_loan;

    $query3 = "UPDATE budget SET 
    			money = $setBudget

    			WHERE code_budget = '$code_budget' AND id_user = '$id_user'

    		";

    mysqli_query($conn, $query3);

    return mysqli_affected_rows($conn);
}

function editGiveLoan($data)
{
    global $conn;
    $income = query("SELECT max(code_income) as kode_terbesar FROM income")[0];
    $code_income = $income["kode_terbesar"];
    $urutan = (int) substr($code_income, 1, 4);

    $urutan++;
    $huruf = "I";
    $code_income = $huruf . sprintf("%04s", $urutan);

    $code_loan = htmlspecialchars($data["code_loan"]);
    $id_user = htmlspecialchars($data["id_user"]);
    $code_budget = htmlspecialchars($data["code_budget"]);
    $status_loan = "Paid Off";
    $tel_loan = htmlspecialchars($data["tel_loan"]);
    $tel_loan =  tel($tel_loan);
    $note_loan = htmlspecialchars($data["note_loan"]);
    $amount_loan = htmlspecialchars($data["amount_loan"]);
    $date =  date("Y-m-d");

    $query2 = "INSERT INTO income VALUES ('$code_income', '$date', 'C0004', '$note_loan', $amount_loan, '$id_user')";
    mysqli_query($conn, $query2);

    $query = "UPDATE loan SET 
    			status_loan = '$status_loan',
                code_income = '$code_income'

    			WHERE code_loan = '$code_loan' AND id_user = '$id_user'

    		";

    mysqli_query($conn, $query);

    $budget = query("SELECT * FROM budget WHERE id_user = '$id_user'")[0];
    $setBudget = $budget["money"] + $amount_loan;

    $query3 = "UPDATE budget SET 
    			money = $setBudget

    			WHERE code_budget = '$code_budget' AND id_user = '$id_user'

    		";

    mysqli_query($conn, $query3);

    return mysqli_affected_rows($conn);
}

function deleteGiveLoan($data)
{
    global $conn;
    $code_loan = htmlspecialchars($data["code_loan"]);
    $loan = query("SELECT * FROM loan WHERE code_loan = '$code_loan'")[0];
    $code_expense = $loan["code_expense"];
    $id_user = htmlspecialchars($data["id_user"]);
    $code_budget = htmlspecialchars($data["code_budget"]);

    $budget_expense_last = query("SELECT budget_expense FROM expense WHERE code_expense = '$code_expense'")[0];

    $budget = query("SELECT * FROM budget WHERE id_user = '$id_user'")[0];
    $setBudget = $budget["money"] + $budget_expense_last["budget_expense"];

    $query2 = "UPDATE budget SET 
				money = $setBudget

				WHERE code_budget = '$code_budget' AND id_user = '$id_user'

			";

    mysqli_query($conn, $query2);

    $query3 = "DELETE FROM loan WHERE code_loan = '$code_loan' AND id_user = '$id_user'";
    mysqli_query($conn, $query3);

    $query = "DELETE FROM expense WHERE code_expense = '$code_expense' AND id_user = '$id_user'";
    mysqli_query($conn, $query);

    return mysqli_affected_rows($conn);
}

function addAcceptLoan($data)
{

    $income = query("SELECT max(code_income) as kode_terbesar FROM income")[0];
    $code_income = $income["kode_terbesar"];
    $urutan = (int) substr($code_income, 1, 4);

    $urutan++;
    $huruf = "I";
    $code_income = $huruf . sprintf("%04s", $urutan);

    global $conn;
    $code_loan = htmlspecialchars($data["code_loan"]);
    $id_user = htmlspecialchars($data["id_user"]);
    $code_budget = htmlspecialchars($data["code_budget"]);
    $category_loan = htmlspecialchars($data["category_loan"]);
    $status_loan = htmlspecialchars($data["status_loan"]);
    $date_loan = htmlspecialchars($data["date_loan"]);
    $due_date_loan = htmlspecialchars($data["due_date_loan"]);
    $name_loan = htmlspecialchars($data["name_loan"]);
    $tel_loan = htmlspecialchars($data["tel_loan"]);
    $tel_loan =  tel($tel_loan);
    $note_loan = htmlspecialchars($data["note_loan"]);
    $amount_loan = htmlspecialchars($data["amount_loan"]);

    $query2 = "INSERT INTO income VALUES ('$code_income', '$date_loan', 'C0004', '$note_loan', $amount_loan, '$id_user')";
    mysqli_query($conn, $query2);

    $query = "INSERT INTO loan VALUES ('$code_loan', '$note_loan', $amount_loan, '$date_loan', '$due_date_loan', '$name_loan', '$tel_loan', '$category_loan', '$status_loan', '$id_user', '$code_income', NULL)";
    mysqli_query($conn, $query);

    $budget = query("SELECT * FROM budget WHERE id_user = '$id_user'")[0];
    $setBudget = $budget["money"] + $amount_loan;

    $query3 = "UPDATE budget SET 
    			money = $setBudget

    			WHERE code_budget = '$code_budget' AND id_user = '$id_user'

    		";

    mysqli_query($conn, $query3);

    return mysqli_affected_rows($conn);
}

function editAcceptLoan($data)
{
    global $conn;
    $expense = query("SELECT max(code_expense) as kode_terbesar FROM expense")[0];
    $code_expense = $expense["kode_terbesar"];
    $urutan = (int) substr($code_expense, 1, 4);

    $urutan++;
    $huruf = "E";
    $code_expense = $huruf . sprintf("%04s", $urutan);

    $code_loan = htmlspecialchars($data["code_loan"]);
    $id_user = htmlspecialchars($data["id_user"]);
    $code_budget = htmlspecialchars($data["code_budget"]);
    $status_loan = "Paid Off";
    $tel_loan = htmlspecialchars($data["tel_loan"]);
    $tel_loan =  tel($tel_loan);
    $note_loan = htmlspecialchars($data["note_loan"]);
    $amount_loan = htmlspecialchars($data["amount_loan"]);
    $date =  date("Y-m-d");

    $query2 = "INSERT INTO expense VALUES ('$code_expense', '$date', 'D0010', '$note_loan', $amount_loan, '$id_user')";
    mysqli_query($conn, $query2);

    $query = "UPDATE loan SET 
    			status_loan = '$status_loan',
                code_expense = '$code_expense'

    			WHERE code_loan = '$code_loan' AND id_user = '$id_user'

    		";

    mysqli_query($conn, $query);

    $budget = query("SELECT * FROM budget WHERE id_user = '$id_user'")[0];
    $setBudget = $budget["money"] - $amount_loan;

    $query3 = "UPDATE budget SET 
    			money = $setBudget

    			WHERE code_budget = '$code_budget' AND id_user = '$id_user'

    		";

    mysqli_query($conn, $query3);

    return mysqli_affected_rows($conn);
}

function deleteAcceptLoan($data)
{
    global $conn;
    $code_loan = htmlspecialchars($data["code_loan"]);
    $loan = query("SELECT * FROM loan WHERE code_loan = '$code_loan'")[0];
    $code_income = $loan["code_income"];
    $id_user = htmlspecialchars($data["id_user"]);
    $code_budget = htmlspecialchars($data["code_budget"]);

    $budget_income_last = query("SELECT budget_income FROM income WHERE code_income = '$code_income'")[0];

    $budget = query("SELECT * FROM budget WHERE id_user = '$id_user'")[0];
    $setBudget = $budget["money"] - $budget_income_last["budget_income"];

    $query2 = "UPDATE budget SET 
				money = $setBudget

				WHERE code_budget = '$code_budget' AND id_user = '$id_user'

			";

    mysqli_query($conn, $query2);

    $query3 = "DELETE FROM loan WHERE code_loan = '$code_loan' AND id_user = '$id_user'";
    mysqli_query($conn, $query3);

    $query = "DELETE FROM income WHERE code_income = '$code_income' AND id_user = '$id_user'";
    mysqli_query($conn, $query);

    return mysqli_affected_rows($conn);
}

function addGoal($data)
{
    global $conn;
    $code_goal = htmlspecialchars($data["code_goal"]);
    $id_user = htmlspecialchars($data["id_user"]);
    $date_goal = htmlspecialchars($data["date_goal"]);
    $name_goal = htmlspecialchars($data["name_goal"]);
    $note_goal = htmlspecialchars($data["note_goal"]);
    $status_goal = htmlspecialchars($data["status_goal"]);
    $amount_goal = htmlspecialchars($data["amount_goal"]);
    $amount2_goal = 0;

    $query = "INSERT INTO goal VALUES ('$code_goal', '$name_goal', '$date_goal', '$note_goal', $amount_goal, $amount2_goal, '$status_goal', '$id_user', NULL)";
    mysqli_query($conn, $query);

    return mysqli_affected_rows($conn);
}

function addAmountGoal($data)
{
    global $conn;
    $code_goal = htmlspecialchars($data["code_goal"]);
    $id_user = htmlspecialchars($data["id_user"]);
    $amount2_goal = htmlspecialchars($data["amount2_goal"]);
    $add_amount_goal = htmlspecialchars($data["add_amount_goal"]);

    $setAmount2Goal = $amount2_goal + $add_amount_goal;
    $query = "UPDATE goal SET 
				amount2_goal = $setAmount2Goal

				WHERE code_goal = '$code_goal' AND id_user = '$id_user'

			";

    mysqli_query($conn, $query);

    return mysqli_affected_rows($conn);
}


function achieved($data)
{
    $expense = query("SELECT max(code_expense) as kode_terbesar FROM expense")[0];
    $code_expense = $expense["kode_terbesar"];
    $urutan = (int) substr($code_expense, 1, 4);

    $urutan++;
    $huruf = "E";
    $code_expense = $huruf . sprintf("%04s", $urutan);

    global $conn;
    $code_goal = htmlspecialchars($data["code_goal"]);
    $id_user = htmlspecialchars($data["id_user"]);
    $note_goal = htmlspecialchars($data["note_goal"]);
    $amount2_goal = htmlspecialchars($data["amount2_goal"]);
    $code_budget = htmlspecialchars($data["code_budget"]);
    $status_goal = "Achieved";
    $date =  date("Y-m-d");

    $query2 = "INSERT INTO expense VALUES ('$code_expense', '$date', 'D0021', '$note_goal', $amount2_goal, '$id_user')";
    mysqli_query($conn, $query2);

    $query = "UPDATE goal SET 
				status_goal = '$status_goal',
                code_expense = '$code_expense'

				WHERE code_goal = '$code_goal' AND id_user = '$id_user'

			";

    mysqli_query($conn, $query);

    $budget = query("SELECT * FROM budget WHERE id_user = '$id_user'")[0];
    $setBudget = $budget["money"] - $amount2_goal;

    $query3 = "UPDATE budget SET 
            money = $setBudget

            WHERE code_budget = '$code_budget' AND id_user = '$id_user'

        ";

    mysqli_query($conn, $query3);

    return mysqli_affected_rows($conn);
}

function deleteGoal($data)
{

    global $conn;
    $code_goal = htmlspecialchars($data["code_goal"]);
    $id_user = htmlspecialchars($data["id_user"]);

    $query = "DELETE FROM goal WHERE code_goal = '$code_goal' AND id_user = '$id_user'";
    mysqli_query($conn, $query);

    return mysqli_affected_rows($conn);
}
