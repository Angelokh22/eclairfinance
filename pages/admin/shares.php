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



    <section class="mt-6">
        <table class="table is-fullwidth">
            <thead>
                <th>Logo</th>
                <th>Company</th>
                <th>Owner</th>
                <th>Price/Share</th>
                <th>Change %</th>
                <th></th>
            </thead>

            <tbody id="tbody"></tbody>
        </table>
    </section>


    <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.7.1/jquery.js"></script>
    <script src="../../assets/js/bulma.navbar.js"></script>
    <script src="../../assets/js/index-prices.js"></script>

    <script>

        var template = `<tr id="cid">
                    <td><img src="../../assets/img/company_logos/logo_name" alt="alt_company_name" width="65"></td>
                    <td>company_name</td>
                    <td>owner_name</td>
                    <td id="priceOut" class="has-text-success">$ shareprice</td>
                    <td>
                        <div class="field">
                            <button class="button" style="background-color: rgb(0, 150, 0);" onclick="changeSharePrice(cid,0.10,'+')">+10%</button>
                            <button class="button" style="background-color: rgb(0, 130, 0);" onclick="changeSharePrice(cid,0.15,'+')">+15%</button>
                            <button class="button" style="background-color: rgb(0, 110, 0);" onclick="changeSharePrice(cid,0.30,'+')">+30%</button>
                            <button class="button" style="background-color: rgb(0, 90, 0);" onclick="changeSharePrice(cid,0.35,'+')">+35%</button>
                            <button class="button" style="background-color: rgb(0, 70, 0);" onclick="changeSharePrice(cid,0.50,'+')">+50%</button>
                            <button class="button" style="background-color: rgb(0, 50, 0);" onclick="changeSharePrice(cid,0.55,'+')">+55%</button>
                        </div>
                        <div class="field">
                            <button class="button" style="background-color: rgb(150, 0, 0);" onclick="changeSharePrice(cid,0.10,'-')">-10%</button>
                            <button class="button" style="background-color: rgb(130, 0, 0);" onclick="changeSharePrice(cid,0.15,'-')">-15%</button>
                            <button class="button" style="background-color: rgb(110, 0, 0);" onclick="changeSharePrice(cid,0.30,'-')">-30%</button>
                            <button class="button" style="background-color: rgb(90, 0, 0);" onclick="changeSharePrice(cid,0.35,'-')">-35%</button>
                            <button class="button" style="background-color: rgb(70, 0, 0);" onclick="changeSharePrice(cid,0.50,'-')">-50%</button>
                            <button class="button" style="background-color: rgb(50, 0, 0);" onclick="changeSharePrice(cid,0.55,'-')">-55%</button>
                        </div>
                    </td>
                </tr >`;



        async function get_companies() {
            try {
                let response = await fetch("../api/admin/get_shares.php", {
                    method: "POST"
                });

                let data = await response.json();

                if (data.success) {
                    return data.companies;
                } else {
                    throw new Error("Failed to fetch companies");
                }
            } catch (error) {
                console.error("Error:", error);
                return []; // Return an empty array in case of an error
            }
        }

        get_companies().then(companies => {

            var tbody = document.getElementById("tbody")

            companies.forEach(company => {

                var cid = company['companyid'];
                var cname = company['companyName'];
                var fname = company['fullName'];
                var shareprice = company['sharePrice'];
                var logo = company['logo'];

                tbody.innerHTML += template
                    .replace(/cid/g, cid)
                    .replace("logo_name", logo)
                    .replace("alt_company_name", cname)
                    .replace("company_name", cname)
                    .replace("owner_name", fname)
                    .replace("shareprice", shareprice)

            });

        });


        function update_prices() {

            get_companies().then(companies => {

                companies.forEach(company => {

                    var cid = company['companyid'];
                    var cname = company['companyName'];
                    var fname = company['fullName'];
                    var shareprice = company['sharePrice'];
                    var logo = company['logo'];

                    var tableRow = document.getElementById(cid);
                    if (tableRow) {

                        tableRow.querySelector("#priceOut").innerHTML = "$ " + shareprice;

                    }

                });

            });

        }

        setInterval(() => {
            update_prices();
        }, 800)


        // get_companies()
        //         var tbody = document.getElementById("tbody")

        //         response['companies'].forEach(company => {

        //             var cid = company['companyid'];
        //             var cname = company['companyName'];
        //             var fname = company['fullName'];
        //             var shareprice = company['sharePrice'];
        //             var logo = company['logo'];

        //             tbody.innerHTML += template
        //                 .replace(/cid/g, cid)
        //                 .replace("logo_name", logo)
        //                 .replace("alt_company_name", cname)
        //                 .replace("company_name", cname)
        //                 .replace("owner_name", fname)
        //                 .replace("shareprice", shareprice)

        //         });

        //     }
        // })



        function changeSharePrice(cid, value, option) {

            let formData = new FormData();
            formData.append("cid", cid);
            formData.append("value", value);
            formData.append("option", option);


            fetch(
                "../api/admin/change_share_price.php",
                {
                    "method": "POST",
                    body: formData

                }
            )
                .then(response => response.json())
                .then(response => {
                    if (response['success'] != true) {

                        alert(response['msg'])

                    }
                })

        }

    </script>

</body>

</html>