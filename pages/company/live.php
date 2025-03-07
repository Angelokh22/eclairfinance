<?php

ini_set('display_errors', 1);
error_reporting(E_ALL);

require "../../pages/api/tools.php";

session_start();

if (isset($_SESSION['UserID']) && !empty($_SESSION['UserID'])) {

    $userid = $_SESSION['UserID'];

    $query = "SELECT UserFirstName, UserAccess FROM users WHERE UserID = :userid";
    $params = [
        'userid' => $userid
    ];

    $response = send_query($query, true, false, $params);
    if ($response) {

        $useraccess = $response['UserAccess'];
        $userfirstname = $response['UserFirstName'];


        if ($useraccess == 0) {
            header("Location: ../admin/shares.php");
        }
        if ($useraccess == 2) {
            header("Location: ../../index.php");
        }

        $query2 = "SELECT CompanyID FROM companies WHERE UserID = :userid";
        $params2 = [
            "userid" => $userid
        ];

        $response = send_query($query2, true, false, $params2);
        if ($response) {

            $companyID = $response['CompanyID'];

        }

    }

} else {

    header('Location: ../auth/login.php');

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
        th,
        td {
            text-align: center !important;
        }

        .container {
            display: flex;
            justify-content: center;
            /* Centers horizontally */
            align-items: center;
            /* Centers vertically */
            height: 90vh;
            /* Full viewport height */
        }

        h1 {
            font-size: 15vw;
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

                    <a class="navbar-item" href="./index.php">
                        <strong> <i class="fa-duotone fa-solid fa-house"></i></i> &nbsp Home</strong>
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

            <h1 class="has-text-success" id="livePrice"></h1>

        </div>

    </section>




    <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.7.1/jquery.js"></script>
    <script src="../../assets/js/bulma.navbar.js"></script>
    <script src="../../assets/js/bulma.modal.js"></script>
    <script src="../../assets/js/index-prices.js"></script>

    <script>

        var lastPrice = 0;
        var isLow = false;

        function getSharePrice() {

            const livePrice = document.getElementById("livePrice");

            fetch(
                "../api/company/get_share_price.php?companyid=<?php echo $companyID; ?>"
            )
                .then(response => response.json())
                .then(response => {

                    if (response.success) {

                        sharePrice = response.sharePrice;

                        if (lastPrice > sharePrice) {
                            lastPrice = sharePrice
                            livePrice.classList.remove("has-text-success")
                            livePrice.classList.add("has-text-danger")
                        }
                        else if (lastPrice < sharePrice) {
                            lastPrice = sharePrice
                            livePrice.classList.add("has-text-success")
                            livePrice.classList.remove("has-text-danger")
                        }



                        livePrice.innerText = "$ " + truncateNumber(sharePrice, 2);

                    }


                })


        }

        setInterval(() => {
            getSharePrice()
        }, 800)

    </script>



</body>

</html>