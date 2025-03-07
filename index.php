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


    <link rel="stylesheet" href="./assets/css/bulma.css">
    <link rel="stylesheet" href="./assets/css/cf.fontawesome.all.css">
    <link rel="stylesheet" href="./assets/css/fontawesome.all.css">
    <link rel="stylesheet" href="./assets/css/all.css">
    <link rel="stylesheet" href="./assets/css/sharp-thin.css">
    <link rel="stylesheet" href="./assets/css/sharp-solid.css">
    <link rel="stylesheet" href="./assets/css/sharp-regular.css">
    <link rel="stylesheet" href="./assets/css/sharp-light.css">

    <link rel="stylesheet" href="./assets/css/index.css">

    <!-- --bulma-scheme-main -->

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

                    <a class="navbar-item" href="#">
                        <strong class="js-modal-trigger" data-target="redeem-secret"> <i
                                class="fa-duotone fa-solid fa-lock"></i>
                            &nbsp Redeem Secret</strong>
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


    <section class="">
        <div class="container">
            <div class="total mt-5 ml-2 mb-5">
                <p class="">Hello, <span style="color: white;">
                        <?php
                        $query = "SELECT userFirstName from users where userID = '$userid'";
                        $result = send_query($query, true, false, []);
                        echo $result['userFirstName'];
                        ?>
                        !
                    </span></p>
            </div>
        </div>
    </section>


    <section class="currency">
        <div class="container">
            <div class="total mt-5 ml-2 mb-5">
                <p class="">Current USD value from Shares <i class="fas fa-chevron-down" style="font-size: small;"></i>
                </p>
                <p class="current-bal mt-2 has-text-success" style="font-size: xx-large; font-weight: 600;"></p>
            </div>
        </div>
    </section>

    <section class="action-btn">
        <div class="container mt-3 mb-3">
            <!-- <button class="button is-primary js-modal-trigger" data-target="buy-crypto-modal" onclick="buyCrypto(0)"><i
                    class="fa fa-shopping-cart" aria-hidden="true"></i> &nbsp Buy</button> -->
            <button class="button is-warning js-modal-trigger" data-target="sell-crypto-modal"
                onclick="sellShares(<?php echo $userid; ?>)"><i class="fa fa-sack-dollar"></i> &nbsp Sell</button>
            <button class="button is-link js-modal-trigger" data-target="transfere-crypto-modal"
                onclick="sendShares(<?php echo $userid; ?>)"><i class="fas fa-exchange-alt"></i> &nbsp Send</button>
            <button class="button is-danger js-modal-trigger" data-target="register-game-entrance"><i
                    class="fa fa-gamepad"></i> &nbsp Play</button>
        </div>
        </div>
    </section>

    <section class="cryptos">
        <div class="container">
            <table class="table is-fullwidth mt-4">
                <tbody>


                    <?php

                    $sql = "SELECT * FROM companies";
                    $resultCompanies = send_query($sql);
                    $companyOptions = "";

                    if ($resultCompanies) {

                        foreach ($resultCompanies as $company) {
                            $name = $company['CompanyName'];
                            $logo = $company['CompanyLogoName'];
                            $id = $company['CompanyID'];
                            $price = truncateNumber($company['CompanySharePrice'], 2);

                            $companyOptions .= '<option value="' . $id . '">' . $name . '</option>';

                            echo '<tr>
                                    <th class="crypto-logo"><img src="./assets/img/company_logos/' . $logo . '" alt="' . $id . '"></th>
                                    <td class="crypto-name">' . $name . '</td>
                                    <p class="trx-last" hidden>0</p>
                                    <td class="crypto-price has-text-success trx-price">$' . $price . '</td>
                                    <td class="crypto-buy-btn"><button class="button is-warning"
                                            onclick="buyCrypto(this)">Buy</button></td>
                                </tr>';

                        }

                    }

                    ?>


                </tbody>
            </table>
        </div>
    </section>

    <div id="notification-container" class="notification is-success is-hidden"
        style="position: fixed; top: 10px; right: 10px; z-index: 1000;">
    </div>

    <div class="modal no-close" id="buy-crypto-modal">
        <div class="modal-background"></div>
        <div class="modal-card" style="width: 90%;">
            <header class="modal-card-head">
                <p class="modal-card-title">Buy Shares</p>
                <button class="delete" aria-label="close"></button>
            </header>
            <section class="modal-card-body">
                <div class="field control has-icons-left">
                    <div class="select is-rounded">
                        <select name="" id="cryptoChoice" style="width: 100vw;">
                            <?php echo $companyOptions; ?>
                        </select>
                    </div>
                    <div class="icon is-small is-left">
                        <i class="fa-solid fa-coin"></i>
                    </div>
                </div>

                <div class="field">
                    <p class="control has-icons-left has-icons-right">
                        <input id="priceInput" class="input" type="number" placeholder="Price"
                            oninput="updatePrice()" />
                        <span class="icon is-small is-left">
                            <i class="fa-dollar-sign fa-solid fa-list"></i>
                        </span>
                    </p>
                </div>

                <div class="field">
                    <p class="control has-icons-left has-icons-right">
                        <input id="buy_quantity" class="input" type="text" placeholder="Quantity" disabled />
                        <span class="icon is-small is-left">
                            <i class="fa-duotone fa-solid fa-list"></i>
                        </span>
                    </p>
                </div>

            </section>
            <footer class="modal-card-foot">
                <div class="buttons">
                    <button class="button is-success" onclick="buyShare(<?php echo $userid; ?>)">Buy</button>
                    <button class="button is-pulled-right">Cancel</button>
                </div>
            </footer>
        </div>
    </div>

    <div class="modal" id="sell-crypto-modal">
        <div class="modal-background"></div>
        <div class="modal-card" style="width: 90%;">
            <header class="modal-card-head">
                <p class="modal-card-title">Sell Shares</p>
                <button class="delete" aria-label="close"></button>
            </header>
            <section class="modal-card-body">
                <div class="field control has-icons-left">
                    <div class="select is-rounded">
                        <select name="" id="shareSellChoice" style="width: 100vw;">
                            <?php echo $companyOptions; ?>

                        </select>
                    </div>
                    <div class="icon is-small is-left">
                        <i class="fa-solid fa-coin"></i>
                    </div>
                </div>

                <div class="field">
                    <p class="control has-icons-left has-icons-right">
                        <input id="sellAvailable" class="input" type="text" placeholder="Available" disabled />
                        <span class="icon is-small is-left">
                            <i class="fa-th-large fa-solid fa-list"></i>
                        </span>
                    </p>
                </div>

                <div class="field">
                    <p class="control has-icons-left has-icons-right">
                        <input id="priceInput-sell" class="input" type="number" placeholder="Sell Amount"
                            oninput="updateSellingPrice()" />
                        <span class="icon is-small is-left">
                            <i class="fa-dollar-sign fa-solid fa-list"></i>
                        </span>
                    </p>
                </div>

                <div class="field">
                    <p class="control has-icons-left has-icons-right">
                        <input id="priceSellOutput" class="input" type="text" placeholder="Selling Price" disabled />
                        <span class="icon is-small is-left">
                            <i class="fa-duotone fa-solid fa-list"></i>
                        </span>
                    </p>
                </div>

                <!-- <div class="field">
                    <p class="control has-icons-left has-icons-right">
                        When Selling Shares, there will be a 4% transaction fees.
                    </p>
                </div> -->

            </section>
            <footer class="modal-card-foot">
                <div class="buttons">
                    <button class="button is-warning" onclick="SubmitSell()">Sell</button>
                    <button class="button is-pulled-right">Cancel</button>
                </div>
            </footer>
        </div>
    </div>

    <div class="modal" id="transfere-crypto-modal">
        <div class="modal-background"></div>
        <div class="modal-card" style="width: 90%;">
            <header class="modal-card-head">
                <p class="modal-card-title">Transfer Shares</p>
                <button class="delete" aria-label="close"></button>
            </header>
            <section class="modal-card-body">
                <div class="field control has-icons-left">
                    <div class="select is-rounded">
                        <select name="" id="shareSendChoice" style="width: 100vw;">
                            <?php echo $companyOptions; ?>

                        </select>
                    </div>
                    <div class="icon is-small is-left">
                        <i class="fa-solid fa-coin"></i>
                    </div>
                </div>

                <div class="field">
                    <p class="control has-icons-left has-icons-right">
                        <input id="sendAvailable" class="input" type="text" placeholder="Available" disabled />
                        <span class="icon is-small is-left">
                            <i class="fa-th-large fa-solid fa-list"></i>
                        </span>
                    </p>
                </div>

                <div class="field">
                    <p class="control has-icons-left has-icons-right">
                        <!-- <input id="priceInput-transfer" class="input" type="number" placeholder="Price" /> -->
                        <input id="priceInput-send" class="input" type="number" placeholder="Send Number of Shares"
                            oninput="updateSendingPrice()" />
                        <!-- <span class="icon is-small is-left">
                            <i class="fa-dollar-sign fa-solid fa-list"></i>
                        </span> -->
                        <span class="icon is-small is-left">
                            <i class="fa-duotone fa-solid fa-list"></i>
                        </span>
                    </p>
                </div>

                <div class="field control has-icons-left">
                    <div class="select is-rounded">
                        <select name="" id="transfer-to" style="width: 100vw;">
                            <option value="0">Transfer to:</option>
                            <?php

                            $sql = "SELECT * FROM users WHERE UserAccess = 2 AND UserID <> :userid";
                            $resultUsers = send_query($sql, true, true, ['userid' => $userid]);
                            if ($resultUsers) {

                                foreach ($resultUsers as $user) {

                                    $uid = $user['UserID'];
                                    $username = $user['UserFirstName'] . " " . $user['UserLastName'];

                                    echo '<option value="' . $uid . '">' . $username . '</option>';

                                }

                            }

                            ?>
                        </select>
                    </div>
                    <div class="icon is-small is-left">
                        <i class="fa-solid fa-users"></i>
                    </div>
                </div>

            </section>
            <footer class="modal-card-foot">
                <div class="buttons">
                    <button class="button is-success" onclick="SubmitSend()">Transfer</button>
                    <button class="button is-pulled-right">Cancel</button>
                </div>
            </footer>
        </div>
    </div>

    <div class="modal" id="redeem-secret">
        <div class="modal-background"></div>
        <div class="modal-card" style="width: 90%;">
            <header class="modal-card-head">
                <p class="modal-card-title">Redeem Secret Code</p>
                <button class="delete" aria-label="close"></button>
            </header>
            <section class="modal-card-body">

                <div class="field">
                    <p class="control has-icons-left has-icons-right">
                        <input id="secretCodeInput" class="input" type="text" placeholder="Secret Code" />
                        <span class="icon is-small is-left">
                            <i class="fa-duotone fa-solid fa-lock"></i>
                        </span>
                    </p>
                </div>

            </section>
            <footer class="modal-card-foot">
                <div class="buttons">
                    <button class="button is-success" onclick="redeemSecret(<?php echo $userid; ?>)">Redeem</button>
                    <button class="button is-pulled-right">Cancel</button>
                </div>
            </footer>
        </div>
    </div>

    <div class="modal" id="register-game-entrance">
        <div class="modal-background"></div>
        <div class="modal-card" style="width: 90%;">
            <header class="modal-card-head">
                <p class="modal-card-title">Register a game</p>
                <button class="delete" aria-label="close"></button>
            </header>
            <section id="playSelection" class="modal-card-body">
                <div class="field">
                    <p>When you choose a company to play with, you won't be able to exit without the approval of the
                        company owner.</p>
                </div>

                <div class="field">
                    <div class="select is-rounded">
                        <select id="companyPlay" style="width: 100vw;">
                            <option value="">Choose a Company</option>
                            <?php echo $companyOptions; ?>
                        </select>
                    </div>
                </div>
            </section>
            <footer class="modal-card-foot">
                <div id="playBtn" class="buttons">
                    <button class="button is-link" onclick="gameRegister()">Enter</button>
                    <button class="button is-pulled-right" onclick="closeModal()">Cancel</button>
                </div>
            </footer>
        </div>
    </div>

    <script src="./assets/js/jquery.js"></script>
    <!-- <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.7.1/jquery.js"></script> -->
    <script src="./assets/js/bulma.navbar.js"></script>
    <script src="./assets/js/bulma.modal.js"></script>
    <script src="./assets/js/index-prices.js"></script>
    <script src="./assets/js/index-buy-modal.js"></script>
    <script src="./assets/js/index-sell.js"></script>
    <script src="./assets/js/index-send.js"></script>
    <script src="./assets/js/index-secret.js"></script>

    <script>


        function closeModal() {
            document.getElementById("register-game-entrance").classList.remove("is-active")
        }


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
                    var currBal = document.getElementsByClassName("current-bal")[0]
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
            checkPlaying();
        }, 800)



        function checkPlaying() {

            const section = document.getElementById("playSelection");
            const hasSelect = section ? section.querySelector("select") !== null : false;
            const playBtn = document.getElementById("playBtn");

            fetch("./pages/api/player/checkPlaying.php?userid=<?php echo $userid; ?>")
                .then(response => response.json())
                .then(response => {

                    if (response.success) {

                        if (hasSelect) {

                            section.innerHTML = `<div class="field">
                                <p>You're Playing at: <strong>${response.company}</strong></p>
                            </div>`;
                            playBtn.innerHTML = `<button class="button" onclick="closeModal()">Cancel</button>`;

                        }
                    }
                    else {

                        if (!hasSelect) {

                            section.innerHTML = `<div class="field">
                                <p>When you choose a company to play with, you won't be able to exit without the approval of the
                                    company owner.</p>
                            </div>

                            <div class="field">
                                <div class="select is-rounded">
                                    <select id="companyPlay" style="width: 100vw;">
                                        <option value="">Choose a Company</option>
                                        <?php echo $companyOptions; ?>
                                    </select>
                                </div>
                            </div>`;
                            playBtn.innerHTML = `<button class="button is-link" onclick="gameRegister()">Enter</button>
                                <button class="button is-pulled-right">Cancel</button>`;

                        }

                    }

                })
                .catch(error => console.error("Error fetching playing status:", error));
        }

        function gameRegister() {
            var companyPlay = document.getElementById("companyPlay").value;
            var notif = document.getElementById("notification-container");

            if (!companyPlay) return; // Prevent empty selections

            let formData = new FormData();
            formData.append("userid", <?php echo $userid ?>);
            formData.append("companyid", companyPlay);

            fetch("./pages/api/player/setPlay.php", {
                method: "POST",
                body: formData
            })
                .then(response => response.json())
                .then(response => {
                    if (response.success) {
                        isPlaying = 1; // Mark as playing
                        notif.innerHTML = "Game Joined!";
                        notif.classList.add("is-success");
                        notif.classList.remove("is-hidden");

                        setTimeout(() => {
                            notif.classList.add("is-hidden");
                        }, 3000);
                    } else {
                        isPlaying = 0;
                        notif.innerHTML = response.error;
                        notif.classList.add("is-danger");
                        notif.classList.remove("is-hidden");

                        setTimeout(() => {
                            notif.classList.add("is-hidden");
                        }, 3000);
                    }
                    checkPlaying(); // Immediately refresh UI
                })
                .catch(error => console.error("Error registering game:", error));
        }



    </script>

</body>

</html>