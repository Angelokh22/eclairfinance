<?php

ini_set('display_errors', 1);
error_reporting(E_ALL);

require "../api/tools.php";

session_start();

if (isset($_SESSION['UserID']) && !empty($_SESSION['UserID'])) {
    header("Location: ../../index.php");
}

?>

<!DOCTYPE html>
<html>

<head>
    <meta charset="utf-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1" />
    <title>Éclair Finance</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/bulma/1.0.2/css/bulma.css" />
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.7.2/css/all.css" />
    <link rel="stylesheet" href="../../assets/css/login.css">
    <style>
        .container {
            transform: translateY(45%) !important;
        }
    </style>
</head>

<body>

    <div class="container">
        <div class="field">
            <p class="control">
                <img src="../../assets/img/clair-finance-high-resolution-logo__1_-removebg-preview-resized.png" alt="">
            </p>
        </div>
        <div class="field">
            <p class="control has-icons-left has-icons-right">
                <input id="fname" class="input" type="text" placeholder="First Name" oninput="checkfname()">
                <span class="icon is-small is-left">
                    <i class="fas fa-user"></i>
                </span>
                <span id="fnameStatusSpan" class="icon is-small is-right" style="display: none">
                    <i id="fnameStatus" class="fas fa-check"></i>
                </span>
            </p>
        </div>
        <div class="field">
            <p class="control has-icons-left has-icons-right">
                <input id="lname" class="input" type="text" placeholder="Last Name" oninput="checklname()">
                <span class="icon is-small is-left">
                    <i class="fas fa-user-tag"></i>
                </span>
                <span id="lnameStatusSpan" class="icon is-small is-right" style="display: none">
                    <i id="lnameStatus" class="fas fa-check"></i>
                </span>
            </p>
        </div>
        <div class="field select">
            <p class="control has-icons-left has-icons-right">

                <select id="Team" style="width: 100vw;">
                    <option value="Aurore">Aurore</option>
                    <option value="Cime">Cime</option>
                    <option value="Eclair">Eclair</option>
                    <option value="Flame">Flame</option>
                </select>
            </p>
        </div>
        <div class="field">
            <p class="control has-icons-left has-icons-right">
                <input id="email" class="input" type="email" placeholder="Email" oninput="checkMail()">
                <span class="icon is-small is-left">
                    <i class="fas fa-envelope"></i>
                </span>
                <span id="emailStatusSpan" class="icon is-small is-right" style="display: none">
                    <i id="emailStatus" class="fas fa-check"></i>
                </span>
            </p>
        </div>
        <div class="field">
            <p class="control has-icons-left has-icons-right">
                <input id="password" class="input" type="password" placeholder="Password" oninput="checkPass()">
                <span class="icon is-small is-left">
                    <i class="fas fa-lock"></i>
                </span>
                <span id="passwordStatusSpan" class="icon is-small is-right" style="display: none">
                    <i id="passwordStatus" class="fas fa-check"></i>
                </span>
            </p>
        </div>
        <div class="field">
            <p class="control">
                <button class="button is-white" onclick="login()">
                    Register
                </button>
            </p>
        </div>
        <div class="field">
            <p class="control">
                <a href="./login.php">Login</a>
            </p>
        </div>
    </div>


    <script src="../../assets/js/jquery.js"></script>
    <script src="../../assets/js/bulma.navbar.js"></script>
    <script>

        function checkAll() {

            var fname = document.getElementById("fname").value;
            var lname = document.getElementById("lname").value;
            var email = document.getElementById("email").value;
            var pass = document.getElementById("password").value;


            const fnamePattern = /^[A-Za-z]{3,20}$/;
            const lnamePattern = /^[A-Za-z\s-]{1,10}$/;
            const emailPattern = /^[\w-\.]+@([\w-]+\.)+[\w-]{2,4}$/;
            const passPattern = /^\S{8,}$/;

            if (
                fnamePattern.test(fname)
                &&
                lnamePattern.test(lname)
                &&
                emailPattern.test(email)
                &&
                passPattern.test(pass)
            ) {
                return true;
            }
            return false;

        }
        function checkfname() {

            var fname = document.getElementById("fname").value;

            const fnamePattern = /^[A-Za-z]{3,20}$/;

            showele("fnameStatusSpan")
            if (fnamePattern.test(fname)) {
                remele("fname", "is-danger");
                addele("fname", "is-success");
                remele("fnameStatus", "fa-x");
                addele("fnameStatus", "fa-check");
            }
            else {
                remele("fname", "is-success");
                addele("fname", "is-danger");
                remele("fnameStatus", "fa-check");
                addele("fnameStatus", "fa-x");
            }

        }

        function checklname() {

            var lname = document.getElementById("lname").value;

            const lnamePattern = /^[A-Za-z\s-]{1,10}$/;

            showele("lnameStatusSpan")
            if (lnamePattern.test(lname)) {
                remele("lname", "is-danger");
                addele("lname", "is-success");
                remele("lnameStatus", "fa-x");
                addele("lnameStatus", "fa-check");
            }
            else {
                remele("lname", "is-success");
                addele("lname", "is-danger");
                remele("lnameStatus", "fa-check");
                addele("lnameStatus", "fa-x");
            }

        }

        function checkMail() {

            var email = document.getElementById("email").value;

            const emailPattern = /^[\w-\.]+@([\w-]+\.)+[\w-]{2,4}$/;

            showele("emailStatusSpan")
            if (emailPattern.test(email)) {
                remele("email", "is-danger");
                addele("email", "is-success");
                remele("emailStatus", "fa-x");
                addele("emailStatus", "fa-check");
            }
            else {
                remele("email", "is-success");
                addele("email", "is-danger");
                remele("emailStatus", "fa-check");
                addele("emailStatus", "fa-x");
            }
        }

        function checkPass() {

            var pass = document.getElementById("password").value;

            const passPattern = /^\S{8,}$/;

            showele("passwordStatusSpan")
            if (passPattern.test(pass)) {
                remele("password", "is-danger");
                addele("password", "is-success");
                remele("passwordStatus", "fa-x");
                addele("passwordStatus", "fa-check");
            }
            else {
                remele("password", "is-success");
                addele("password", "is-danger");
                remele("passwordStatus", "fa-check");
                addele("passwordStatus", "fa-x");
            }

        }

        function showele(elid) {
            document.getElementById(elid).style.display = '';
        }
        function hideele(eleid) {
            document.getElementById(eleid).style.display = 'none';
        }

        function remele(eleid, classname) {
            document.getElementById(eleid).classList.remove(classname);
        }
        function addele(eleid, classname) {
            document.getElementById(eleid).classList.add(classname);
        }

        function error() {
            remele("email", "is-success");
            addele("email", "is-danger");
            remele("emailStatus", "fa-check");
            addele("emailStatus", "fa-x");
        }


        function login() {
            var fname = document.getElementById("fname").value;
            var lname = document.getElementById("lname").value;
            var email = document.getElementById("email").value;
            var password = document.getElementById("password").value;
            var team = document.getElementById("Team").value;

            if (checkAll()) {
                fetch(
                    "../api/register.php",
                    {
                        "method": "POST",
                        headers: {
                            'Content-Type': 'application/x-www-form-urlencoded'
                        },
                        body: new URLSearchParams({
                            fname: fname,
                            lname: lname,
                            email: email,
                            password: password,
                            team: team
                        })
                    }
                )
                    .then((response) => response.json())
                    .then(response => {
                        if (response['success'] != true) {
                            error()
                        }
                        else {
                            window.location.href = "../../index.php"
                        }
                    })
            }
        }
    </script>
</body>

</html>