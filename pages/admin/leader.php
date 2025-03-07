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


    <section class="currency">
        <div class="fixed-grid">
            <div class="grid mt-5 mb-5">
                <div class="cell">
                    <div class="container">
                        <div class="" style="text-align: center; align-item: center">
                            <p class="has-text-success">Wining Person</p>
                            <p class="current-bal mt-2 has-text-white" id="winningPerson"></p>
                            <p class="mt-2 has-text-success" id="winningPersonAmount"></p>
                        </div>
                    </div>
                </div>
                <div class="cell">
                    <div class="container">
                        <div class="" style="text-align: center; align-item: center">
                            <p class="has-text-warning">Winning Team</p>
                            <p class="current-bal mt-2 has-text-white" id="winningTeam"></p>
                            <p class="mt-2 has-text-success" id="winningTeamAmount"></p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>


    <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.7.1/jquery.js"></script>
    <script src="../../assets/js/bulma.navbar.js"></script>
    <script src="../../assets/js/index-prices.js"></script>


    <script>

        function getShares() {

            fetch("../api/admin/getWinners.php")
                .then(response => response.json())
                .then(response => {

                    document.getElementById("winningPerson").innerText = response.person
                    document.getElementById("winningTeam").innerText = response.team
                    document.getElementById("winningPersonAmount").innerText = "$ " + truncateNumber(response.personAmount, 2)
                    document.getElementById("winningTeamAmount").innerText = "$ " + truncateNumber(response.teamAmount, 2)

                })

        }

        setInterval(() => {
            getShares();
        }, 800)

    </script>

</body>

</html>