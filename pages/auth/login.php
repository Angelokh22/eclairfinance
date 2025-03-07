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
        <input id="email" class="input" type="email" placeholder="Email" oninput="checkMail()">
        <span class="icon is-small is-left">
          <i class="fas fa-envelope"></i>
        </span>
        <span id="emailStatusSpan" class="icon is-small is-right" style="display: none;">
          <i id="emailStatus" class="fas fa-check"></i>
        </span>
      </p>
    </div>
    <div class="field">
      <p class="control has-icons-left">
        <input id="password" class="input" type="password" placeholder="Password">
        <span class="icon is-small is-left">
          <i class="fas fa-lock"></i>
        </span>
      </p>
    </div>
    <div class="field">
      <p class="control">
        <button class="button is-white" onclick="login()">
          Login
        </button>
      </p>
    </div>
    <div class="field">
      <p class="control">
        <a href="./register.php">Register</a>
      </p>
    </div>
  </div>


  <script src="../../assets/js/jquery.js"></script>
  <script src="../../assets/js/bulma.navbar.js"></script>
  <script>

    function checkMail() {

      email = document.getElementById("email").value;

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

      remele("password", "is-success");
      addele("password", "is-danger");
    }


    function login() {
      email = document.getElementById("email").value;
      password = document.getElementById("password").value;

      fetch(
        "../api/login.php",
        {
          "method": "POST",
          headers: {
            'Content-Type': 'application/x-www-form-urlencoded'
          },
          body: new URLSearchParams({
            email: email,
            password: password
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
  </script>
</body>

</html>