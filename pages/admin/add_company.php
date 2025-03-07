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

    <style>
        .input {
            background-color: #06663b !important;
        }

        table td {
            border-bottom: none !important;
            padding-bottom: 3rem !important;
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

    <section>
        <div class="container">
            <table class="table is-fullwidth" style="border: none; margin-top: 25vh;">
                <tr>
                    <td>
                        <input id="logo" type="file" class="input">
                    </td>
                    <td>
                        <input id="companyName" type="text" class="input" placeholder="Company Name">
                    </td>
                    <td>
                        <input id="maxMembers" type="text" class="input" placeholder="Max Members">
                    </td>
                </tr>
                <tr>
                    <td>
                        <input id="fullName" type="text" class="input" placeholder="Full Name">
                    </td>
                    <td>
                        <input id="email" type="text" class="input" placeholder="Email">
                    </td>
                    <td>
                        <input id="password" type="text" class="input" placeholder="Password">
                    </td>
                </tr>
                <tr>
                    <td>
                        <input id="sharePrice" type="text" class="input" placeholder="Share Price">
                    </td>
                    <!-- <td>
                        <input id="prizeCount" type="text" class="input" placeholder="Prize Count">
                    </td> -->
                    <td>
                        <input id="prizes" type="text" class="input" placeholder="Prizes separated by ','">
                    </td>
                    <td>
                        <button class="button" onclick="add_company()">Create Company</button>

                    </td>
                </tr>
                <!-- <tr>
                    <td></td>
                    <td>
                        <button class="button" onclick="add_company()">Create Company</button>
                    </td>
                    <td></td>
                </tr> -->
            </table>
        </div>
    </section>


    <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.7.1/jquery.js"></script>
    <script src="../../assets/js/bulma.navbar.js"></script>

    <script>

        function add_company() {
            let logo = document.getElementById("logo").files[0];
            let companyName = document.getElementById("companyName").value;
            let maxMembers = document.getElementById("maxMembers").value;
            let fullName = document.getElementById("fullName").value;
            let email = document.getElementById("email").value;
            let password = document.getElementById("password").value;
            let sharePrice = document.getElementById("sharePrice").value;
            // let prizeCount = document.getElementById("prizeCount").value;
            let prizes = document.getElementById("prizes").value.split(",");
            console.log(prizes)


            let formData = new FormData();
            formData.append("logo", logo);
            formData.append("companyName", companyName);
            formData.append("maxMembers", maxMembers);
            formData.append("fullName", fullName);
            formData.append("email", email);
            formData.append("password", password);
            formData.append("sharePrice", sharePrice);
            prizes.forEach(prize => {
                formData.append("Prizes[]", prize.trim()); // Trim to remove extra spaces
            });

            fetch("../api/admin/add_company.php", {
                method: "POST",
                body: formData
            })
                .then(response => response.json())
                .then(response => {
                    if (response["success"] == true) {
                        alert("Company created successfully");
                    }
                    else {
                        alert("Error creating company");
                    }
                })
        }

    </script>

</body>

</html>