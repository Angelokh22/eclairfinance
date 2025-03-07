<?php

ini_set('display_errors', 1);
error_reporting(E_ALL);

require "./pages/api/tools.php";

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


        if ($useraccess == 0) {
            header("Location: ./pages/admin/shares.php");
        }
        if ($useraccess == 1) {
            header("Location: ./pages/company/index.php");
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

    <!-- <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/bulma/1.0.2/css/bulma.css" />
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.7.2/css/all.css" /> -->


    <link rel="stylesheet" href="./assets/css/bulma.css">
    <link rel="stylesheet" href="./assets/css/cf.fontawesome.all.css">
    <link rel="stylesheet" href="./assets/css/fontawesome.all.css">
    <link rel="stylesheet" href="./assets/css/all.css">
    <link rel="stylesheet" href="./assets/css/sharp-thin.css">
    <link rel="stylesheet" href="./assets/css/sharp-solid.css">
    <link rel="stylesheet" href="./assets/css/sharp-regular.css">
    <link rel="stylesheet" href="./assets/css/sharp-light.css">

    <link rel="stylesheet" href="./assets/css/wallet.css">
</head>

<body>

    <section class="top-navbar">
        <nav class="navbar" role="navigation" aria-label="main navigation">
            <div class="navbar-brand">
                <a class="navbar-item" href="#">
                    <img src="./assets/img/clair-finance-high-resolution-logo__1_-removebg-preview-resized.png" alt="">

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
                    <a class="navbar-item" href="./index.php">
                        <strong> <i class="fa-duotone fa-solid fa-house"></i></i> &nbsp Home</strong>
                    </a>

                    <a class="navbar-item" href="./wallet.php">
                        <strong> <i class="fa-duotone fa-solid fa-wallet"></i> &nbsp Wallet</strong>
                    </a>


                </div>

                <div class="navbar-end">
                    <div class="navbar-item">
                        <div class="buttons">
                            <a class="button is-primary" href="./pages/api/logout.php">
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
                            <p class="has-text-success">Flexible funds <i class="fas fa-chevron-down"
                                    style="font-size: medium;"></i>
                            </p>
                            <p class="current-bal mt-2 has-text-white">
                                $ <?php
                                $sql = "SELECT Funds FROM wallet WHERE UserID = :userid";
                                $params = [
                                    "userid" => $userid,
                                ];

                                $result = send_query($sql, true, false, $params);
                                if ($result) {
                                    $funds = $result['Funds'];
                                    echo truncateNumber($funds, 2);
                                } else {
                                    echo "0";
                                }
                                ?></p>
                            <!-- <p class="last-bal has-text-danger">$9,997,156.33</p> -->
                        </div>
                    </div>
                </div>
                <div class="cell">
                    <div class="container">
                        <div class="" style="text-align: center; align-item: center">
                            <p class="has-text-warning">Share Revenue <i class="fas fa-chevron-down"
                                    style="font-size: medium;"></i>
                            </p>
                            <p class="current-bal mt-2 has-text-white" id="companyShares"></p>
                            <!-- <p class="last-bal has-text-danger">$9,997,156.33</p> -->
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>


    <section>
        <table class="table is-hoverable is-fullwidth mt-5">
            <tbody>

                <thead>
                    <!-- <th></th> -->
                    <td>Company</td>
                    <!-- <td>Quantity</td> -->
                    <td>Price</td>
                </thead>

                <!-- <tr>
                    <th><img src="./assets/img/crypto/btc.png" alt="" width="25"></th>
                    <td>Bitcoin</td>
                    <td>0.225487</td>
                    <td>2548.24854</td>
                </tr>

                <tr>
                    <th><img src="./assets/img/crypto/etherium.png" alt="" width="25"></th>
                    <td>Etherium</td>
                    <td>0.225487</td>
                    <td>2548.24854</td>
                </tr>

                <tr>
                    <th><img src="./assets/img/crypto/bnb.png" alt="" width="25"></th>
                    <td>BNB</td>
                    <td>0.225487</td>
                    <td>2548.24854</td>
                </tr>

                <tr>
                    <th><img src="./assets/img/crypto/xrp.png" alt="" width="25"></th>
                    <td>XRP</td>
                    <td>0.225487</td>
                    <td>2548.24854</td>
                </tr>

                <tr>
                    <th><img src="./assets/img/crypto/vana.png" alt="" width="25"></th>
                    <td>VANA</td>
                    <td>0.225487</td>
                    <td>2548.24854</td>
                </tr>

                <tr>
                    <th><img src="./assets/img/crypto/1000cat.png" alt="" width="25"></th>
                    <td>1000CAT</td>
                    <td>0.225487</td>
                    <td>2548.24854</td>
                </tr> -->

                <?php

                $query1 = "SELECT CompanyID, PaymentAmount FROM payment WHERE UserID = :userid";
                $params1 = [
                    "userid" => $userid
                ];

                $result = send_query($query1, true, true, $params1);
                if ($result) {

                    foreach ($result as $transaction) {

                        $companyid = $transaction['CompanyID'];
                        $amount = $transaction['PaymentAmount'];

                        $query2 = "SELECT CompanyName FROM companies WHERE CompanyID = :cid";
                        $params2 = [
                            "cid" => $companyid
                        ];

                        $result = send_query($query2, true, false, $params2);
                        if ($result) {

                            $companyName = $result['CompanyName'];


                            echo '<tr>
                                    <td class="has-text-primary">' . $companyName . '</td>
                                    <td>$ ' . truncateNumber($amount, 2) . '</td>
                                </tr>';


                        }

                    }

                }

                ?>

            </tbody>
        </table>
    </section>


    <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.7.1/jquery.js"></script>
    <script src="./assets/js/bulma.navbar.js"></script>
    <script src="./assets/js/bulma.modal.js"></script>
    <script src="./assets/js/index-prices.js"></script>


    <script>

        var lastPrice = 0.00

        function getFunds() {

            fetch(
                "./pages/api/player/getShareReturn.php",
                {
                    "method": "POST",
                    headers: {
                        'Content-Type': 'application/x-www-form-urlencoded'
                    },
                    body: new URLSearchParams({
                        userid: <?php echo $userid; ?>,
                    })
                }
            )
                .then((response) => response.json())
                .then(response => {
                    var finalFunds = response['sharefunds']
                    var currBal = document.getElementById("companyShares")
                    currBal.innerHTML = "$ " + truncateNumber(finalFunds, 2);

                    if (truncateNumber(finalFunds, 2) < lastPrice) {
                        currBal.classList.remove("has-text-success")
                        currBal.classList.add("has-text-danger")
                    }
                    else if (truncateNumber(finalFunds, 2) > lastPrice) {
                        currBal.classList.remove("has-text-danger")
                        currBal.classList.add("has-text-success")
                    }
                })

        }
        setInterval(() => {
            getFunds();
        }, 800)

    </script>

</body>

</html>