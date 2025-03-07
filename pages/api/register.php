<?php


require "tools.php";

if (
    isset($_POST['fname']) && !empty($_POST['fname'])
    &&
    isset($_POST['lname']) && !empty($_POST['lname'])
    &&
    isset($_POST['email']) && !empty($_POST['email'])
    &&
    isset($_POST['password']) && !empty($_POST['password'])
    &&
    isset($_POST['team']) && !empty($_POST['team'])
) {
    $fname = $_POST['fname'];
    $lname = $_POST['lname'];
    $email = $_POST['email'];
    $password = $_POST['password'];
    $team = $_POST['team'];


    $query1 = "SELECT * FROM users WHERE UserEmail = :email";
    $result = send_query($query1, true, false, ['email' => $email]);

    if ($result) {

        http_response_code(403);
        echo json_encode(['success' => false, 'error' => 'Email already exists.']);
        return;

    }


    $query2 = "INSERT INTO users (UserFirstName, UserLastName, UserTeam, UserEmail, UserPassword, UserAccess) VALUES (:fname, :lname, :team, :uemail, :upass, :uaccess)";
    $params = [
        'fname' => $fname,
        'lname' => $lname,
        'team' => $team,
        'uemail' => $email,
        'upass' => $password,
        'uaccess' => 2
    ];
    send_query($query2, false, false, $params);

    // $query1 = $query1 . " AND userPassword = '$password'";
    $result = send_query($query1, true, false, ['email' => $email]);


    $userid = $result['UserID'];

    session_start();
    $_SESSION['UserID'] = $userid;

    $query3 = "INSERT INTO trial (UserID, IsTrial) VALUES (:userid, :trial)";
    $params = [
        'userid' => $userid,
        'trial' => 1
    ];
    send_query($query3, false, false, $params);

    $query4 = "INSERT INTO wallet (UserID, CompanyIDs, Funds) VALUES (:userid, :cids, :funds)";
    $params = [
        'userid' => $userid,
        'cids' => '',
        'funds' => 0.0
    ];
    send_query($query4, false, false, $params);


    http_response_code(200);
    echo json_encode(["success" => true, 'userid' => $userid]);
    return;

}
http_response_code(405);
return;
?>