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

        .main-table {
            margin-top: 7rem;
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



    <section class="mt-4 mb-6" id="companies_section">
        <!-- <table class="table is-fullwidth">
            <thead>
                <th>Logo</th>
                <th>Company</th>
                <th>Price/Share</th>
                <th>Sold Shares %</th>
                <th>Company Net Worth</th>
                <th>Players</th>
            </thead>

            <tbody>

                <tr>
                    <td><img src="../../assets/img/crypto/btc.png" alt="1" width="35"></td>
                    <td>Angelo Khairallah</td>
                    <td class="has-text-link">$12,500.00</td>
                    <td class="has-text-warning">20/100</td>
                    <td class="has-text-success">$250,000.00</td>
                    <td class="has-text-danger">8 Memebers</td>
                </tr>

                <table class="table is-fullwidth is-bordered">

                    <thead>
                        <th>Name</th>
                        <th>Flexible Funds</th>
                        <th>Company Return</th>
                        <th>Company Shares</th>
                    </thead>

                    <tbody>

                        <tr>
                            <td>Angelo Khairallah</td>
                            <td>$155.25</td>
                            <td>$0.00</td>
                            <td>0.00%</td>
                        </tr>

                    </tbody>
                </table>

            </tbody>
        </table> -->

    </section>


    <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.7.1/jquery.js"></script>
    <script src="../../assets/js/bulma.navbar.js"></script>

    <script>
        document.addEventListener("DOMContentLoaded", function () {
            fetch("../api/admin/get_companies.php", {
                method: 'POST',
            })
                .then(response => response.json())
                .then(response => {
                    if (response.success) {
                        let companies_section = document.getElementById("companies_section");

                        Object.values(response.companies).forEach(company => {

                            let main_table = document.createElement("table");
                            main_table.classList.add("table", "is-fullwidth");
                            main_table.innerHTML = `<thead>
                                                        <th>Logo</th>
                                                        <th>Company</th>
                                                        <th>Price/Share</th>
                                                        <th>Sold Shares %</th>
                                                        <th>Company Net Worth</th>
                                                        <th>Players</th>
                                                    </thead>
                                                    <tbody></tbody>`;

                            let tbody = main_table.querySelector("tbody")

                            let company_info = document.createElement("tr");
                            company_info.innerHTML = `<td><img src="../../assets/img/company_logos/${company.CompanyLogoName}" alt="1" width="35"></td>
                                                        <td>${company.CompanyName}</td>
                                                        <td class="has-text-link">$${company.CompanySharePrice}</td>
                                                        <td class="has-text-warning">${company.SoldShares}/100</td>
                                                        <td class="has-text-success">$${company.CompanyNetWorth}</td>
                                                        <td class="has-text-danger">${company.PlayersCount} Memebers</td>`;
                            tbody.appendChild(company_info);

                            let player_table = document.createElement("table")
                            player_table.classList.add("table", "is-fullwidth", "is-bordered")
                            player_table.innerHTML = `<thead>
                                                            <th>Name</th>
                                                            <th>Flexible Funds</th>
                                                            <th>Company Return</th>
                                                            <th>Company Shares</th>
                                                        </thead>
                                                        <tbody></tbody>`;

                            let player_body = player_table.querySelector("tbody");


                            Object.values(company.Players).forEach(player => {

                                player_body.innerHTML += `<tr>
                                                                <td>${player.UserFirstName} ${player.UserLastName}</td>
                                                                <td>$${player.Funds}</td>
                                                                <td>$${player.CompanyReturnUser}</td>
                                                                <td>${player.ShareAmountUser}%</td>
                                                            </tr>`;

                            });


                            companies_section.appendChild(main_table);
                            companies_section.appendChild(player_table);
                        });
                        // response.companies.forEach(company => {


                        //     console.log(company);
                        // });

                    }
                })
                .catch(error => console.error("Error fetching companies:", error));
        });

    </script>

</body>

</html>