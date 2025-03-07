<?php


require "tools.php";

if (
    isset($_POST['email']) && !empty($_POST['email'])
    &&
    isset($_POST['password']) && !empty($_POST['password'])
) {
    $email = $_POST['email'];
    $password = $_POST['password'];

    $query = "SELECT * FROM users WHERE UserEmail = '$email' AND UserPassword = '$password'";
    $result = send_query($query, true, false, []);

    if ($result) {

        $userid = $result['UserID'];

        session_start();
        $_SESSION['UserID'] = $userid;

        http_response_code(200);
        echo json_encode(["success" => true]);
        return;

    }

    http_response_code(403);
    echo json_encode(['success' => false, 'error' => 'Email or Password incorrect.']);
    return;
}
http_response_code(405);
return;
?>