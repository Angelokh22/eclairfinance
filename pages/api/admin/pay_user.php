<?php

require "../tools.php";

header('Content-Type: application/json');


if (
    $_POST['userid'] && !empty($_POST['userid'])
    &&
    $_POST['amount'] && !empty($_POST['amount'])
) {

    $userid = $_POST['userid'];

    $sql = "SELECT Funds FROM wallet WHERE UserID = :userid";
    $result = send_query($sql, true, false, ['userid' => $userid]);
    if ($result) {

        $oldBal = $result['Funds'];
        $newBal = $oldBal + $_POST['amount'];

        $sql = "UPDATE wallet SET Funds = :funds WHERE UserID = :userid";
        send_query($sql, false, false, ['funds' => $newBal, "userid" => $userid]);

        echo json_encode(['success' => true]);
    } else {
        echo json_encode(['success' => false]);
    }

} else {
    echo json_encode(['success' => false]);
}

?>