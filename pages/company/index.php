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

                    <a class="navbar-item" href="./live.php">
                        <strong> <i class="fa-duotone fa-solid fa-signal-stream"></i></i> &nbsp Live</strong>
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

    <section class="">
        <div class="container">
            <div class="total mt-5 ml-2 mb-5">
                <p class="">Hello, <span style="color: white;"> <?php echo $userfirstname; ?>!
                    </span></p>
            </div>
        </div>
    </section>

    <section class="currency" style="border-bottom: none;">
        <div class="container">
            <div class="total mt-5 ml-2 mb-5">
                <p class="">Your current Company Share Price <i class="fas fa-chevron-down"
                        style="font-size: small;"></i>
                </p>
                <p class="current-bal mt-2 has-text-success" style="font-size: xx-large; font-weight: 600;"></p>
            </div>
        </div>
    </section>

    <section>
        <div class="container">
            <div class="table-container">
                <table class="table is-narrow is-hoverable is-fullwidth">
                    <thead>
                        <th>Player Name</th>
                        <th>Player Company Shares</th>
                        <th>Player Shares Price</th>
                        <th>Prize</th>
                        <th>Action Buttons</th>
                    </thead>
                    <tbody id="playersBody">

                    </tbody>
                </table>
            </div>
        </div>
    </section>

    <div id="notification-container" class="notification is-success is-hidden"
        style="position: fixed; top: 10px; right: 10px; z-index: 1000;">
    </div>





    <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.7.1/jquery.js"></script>
    <script src="../../assets/js/bulma.navbar.js"></script>
    <script src="../../assets/js/bulma.modal.js"></script>
    <script src="../../assets/js/index-prices.js"></script>
    <script src="../../assets/js/index-buy-modal.js"></script>
    <script src="../../assets/js/index-prices.js"></script>

    <script>


        var playersBody = document.getElementById("playersBody");

        function getSharePrice() {

            fetch(
                "../api/company/get_share_price.php?companyid=<?php echo $companyID; ?>",
            )
                .then(response => response.json())
                .then(response => {
                    if (response.success) {

                        document.getElementsByClassName("current-bal")[0].innerHTML = "$ " + truncateNumber(response.sharePrice, 2);

                    }
                })

        }


        function getPlayers() {

            fetch(
                "../api/company/get_player.php?companyid=<?php echo $companyID; ?>",
            )
                .then(response => response.json())
                .then(response => {

                    if (response.success) {

                        var players = response.players
                        var prizes = response.prizes

                        var sendBTN = "";

                        prizes.forEach(prize => {

                            sendBTN += `<button class="button is-success" onclick="sendReward(this, ${prize})">Send ${truncateNumber(prize, 2)}$</button>`;

                        });


                        players.forEach(player => {


                            var playerid = player.userid;

                            if (!document.getElementById(playerid)) {

                                var name = player.name;
                                var shares = player.shares;
                                var sharesprice = player.sharesprice;

                                var template = `<tr id="${playerid}">
                                <td class="has-text-warning">${name}</td>
                                <td class="has-text-link">${truncateNumber(shares, 2)}%</td>
                                <td class="has-text-primary">$${truncateNumber(sharesprice, 2)}</td>
                                <td>
                                    ${sendBTN}
                                </td>
                                <td>
                                    <button class="button is-danger" onclick="kickPlayer(this)">Kick</button>
                                </td>
                            </tr>`

                                playersBody.innerHTML += template;
                            }

                        });



                    }

                })
        }


        setInterval(() => {
            getSharePrice()
            getPlayers()
        }, 800)





        function sendReward(e, prize) {

            const userid = e.parentElement.parentElement.id;
            var notif = document.getElementById("notification-container");

            fetch(`../api/company/pay_player.php?userid=${userid}&companyid=${<?php echo $companyID ?>}&prize=${prize}`)
                .then(response => response.json())
                .then(response => {

                    if (response.success) {

                        notif.innerHTML = "Payment Sent!";
                        notif.classList.add("is-success");
                        notif.classList.remove("is-hidden");
                        setTimeout(() => {
                            notif.classList.add("is-hidden");
                        }, 3000);
                    }
                    else {
                        notif.innerHTML = response.error;
                        notif.classList.add("is-danger");
                        notif.classList.remove("is-hidden");

                        setTimeout(() => {
                            notif.classList.add("is-hidden");
                        }, 3000);
                    }

                })

        }
        function kickPlayer(e) {

            const element = e.parentElement.parentElement;
            const userid = element.id;

            let formData = new FormData();
            formData.append("userid", userid);

            fetch("../api/company/kick_player.php", {
                method: "POST",
                body: formData
            })
                .then(response => response.json())
                .then(response => {

                    var notif = document.getElementById("notification-container");

                    if (response.success) {
                        element.remove()
                        notif.innerHTML = "Player Kicked!";
                        notif.classList.add("is-success");
                        notif.classList.remove("is-hidden");

                        setTimeout(() => {
                            notif.classList.add("is-hidden");
                        }, 3000);
                    } else {
                        notif.innerHTML = response.error;
                        notif.classList.add("is-danger");
                        notif.classList.remove("is-hidden");

                        setTimeout(() => {
                            notif.classList.add("is-hidden");
                        }, 3000);
                    }

                })

        }

    </script>



</body>

</html>