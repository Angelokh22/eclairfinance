<?php
ini_set('display_errors', 1);
error_reporting(E_ALL);

require "../api/tools.php";

session_start();

if (isset($_SESSION['UserID']) && !empty($_SESSION['UserID'])) {

    $userid = $_SESSION['UserID'];

    $query = "SELECT UserAccess FROM users WHERE UserID = :userid";
    $params = [
        'userid' => $userid
    ];

    $response = send_query($query, true, false, $params);
    if ($response) {

        $useraccess = $response['UserAccess'];

        switch ($useraccess) {
            case 1:
                header("Location: ../company/index.php");
            case 2:
                header("Location: ../../index.php");
        }

    }

} else {

    header('Location: ./pages/auth/login.php');

}
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="utf-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1" />
    <title>Éclair Finance</title>


    <link rel="stylesheet" href="../../assets/css/bulma.css">
    <link rel="stylesheet" href="../../assets/css/cf.fontawesome.all.css">
    <link rel="stylesheet" href="../../assets/css/fontawesome.all.css">
    <link rel="stylesheet" href="../../assets/css/all.css">
    <link rel="stylesheet" href="../../assets/css/sharp-thin.css">
    <link rel="stylesheet" href="../../assets/css/sharp-solid.css">
    <link rel="stylesheet" href="../../assets/css/sharp-regular.css">
    <link rel="stylesheet" href="../../assets/css/sharp-light.css">

    <link rel="stylesheet" href="../../assets/css/index.css">

    <!-- --bulma-scheme-main -->

    <style>
        th,
        td {
            text-align: center !important;
        }
    </style>

</head>

<body>

    <section class="top-navbar">
        <nav class="navbar" role="navigation" aria-label="main navigation">
            <div class="navbar-brand">
                <a class="navbar-item" href="#">
                    <img src="../../assets/img/clair-finance-high-resolution-logo__1_-removebg-preview-resized.png"
                        alt="">

                </a>

                <a role="button" class="navbar-burger" aria-label="menu" aria-expanded="false"
                    data-target="navbarBasicExample">
                    <span aria-hidden="true"></span>
                    <span aria-hidden="true"></span>
                    <span aria-hidden="true"></span>
                    <span aria-hidden="true"></span>
                </a>
            </div>

            <div id="navbarBasicExample" class="navbar-menu">
                <div class="navbar-start">
                    <a class="navbar-item" href="./shares.php">
                        <strong> <i class="fa-duotone fa-solid fa-dollar"></i></i> &nbsp Shares</strong>
                    </a>

                    <a class="navbar-item" href="./companies.php">
                        <strong> <i class="fa-duotone fa-solid fa-buildings"></i> &nbsp Companies</strong>
                    </a>

                    <a class="navbar-item" href="./users.php">
                        <strong> <i class="fa-duotone fa-solid fa-user"></i> &nbsp Users</strong>
                    </a>

                    <a class="navbar-item" href="./pay.php">
                        <strong> <i class="fa-duotone fa-solid fa-dollar"></i> &nbsp Pay</strong>
                    </a>

                    <a class="navbar-item" href="./add_company.php">
                        <strong> <i class="fa-duotone fa-solid fa-plus"></i> &nbsp New Company</strong>
                    </a>

                    <a class="navbar-item" href="./leader.php">
                        <strong> <i class="fa-duotone fa-solid fa-trophy"></i> &nbsp Leader Board</strong>
                    </a>


                </div>

                <div class="navbar-end">
                    <div class="navbar-item">
                        <div class="buttons">
                            <a class="button is-primary" href="../api/logout.php">
                                <strong>Log Out</strong>
                            </a>
                        </div>
                    </div>
                </div>
            </div>
        </nav>
    </section>



    <section style="display: flex; justify-content: space-evenly; align-items: center; height: 85vh;">

        <div class="select is-rounded is-success">
            <select id="users" style="width: 25vw;">
                <option value="0">Choose a user</option>
                <!-- <option value="">Angelo Khairallah</option>
                <option value="">Eddy Badran</option>
                <option value="">Theo Khalil</option>
                <option value="">Charbel Khairallah</option>
                <option value="">Joseph Karam</option>
                <option value="">Marco</option>
                <option value="">Jimmy Zouein</option>
                <option value="">Sergio Kassis</option>
                <option value="">Freddy Khabaz</option> -->
                <?php

                $sql = "SELECT * FROM users WHERE UserAccess = 2";
                $result = send_query($sql);
                if ($result) {

                    foreach ($result as $user) {

                        $userid = $user['UserID'];
                        $name = $user['UserFirstName'] . " " . $user['UserLastName'];

                        echo '<option value="' . $userid . '">' . $name . '</option>';

                    }

                }

                ?>
            </select>
        </div>
        <input class="input is-success" type="text" placeholder="Amount $" style="width: 30vw;" />
        <input class="button is-danger" type="submit" value="Pay Up" onclick="payUser()" />

    </section>


    <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.7.1/jquery.js"></script>
    <script src="../../assets/js/bulma.navbar.js"></script>

    <script>

        function payUser() {

            amount = document.getElementsByClassName("input")[0].value;
            userid = document.getElementById("users").value;

            if (userid == 0) {
                alert("Select a user");
            }
            else {

                let formData = new FormData();
                formData.append("userid", userid);
                formData.append("amount", amount);

                fetch(
                    "../api/admin/pay_user.php",
                    {
                        method: "POST",
                        body: formData
                    }
                )
                    .then(response => response.json())
                    .then(response => {
                        if (response.success) {
                            alert("User Paied!")
                        }
                        else {
                            alert("User Not Paied!")
                        }
                    })

            }

        }

    </script>

</body>

</html>