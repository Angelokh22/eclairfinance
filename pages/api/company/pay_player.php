<?php


require "../tools.php";

header('Content-Type: application/json');


if (
    isset($_GET['userid']) && !empty($_GET['userid'])
    &&
    isset($_GET['companyid']) && !empty($_GET['companyid'])
    &&
    isset($_GET['prize']) && !empty($_GET['prize'])
) {

    $userid = $_GET['userid'];
    $companyid = $_GET['companyid'];
    $prize = $_GET['prize'];

    $query1 = "INSERT INTO payment VALUES (:cid, :userid, :amount)";
    $params1 = [
        "cid" => $companyid,
        "userid" => $userid,
        "amount" => $prize
    ];

    send_query($query1, false, false, $params1);

    $query2 = "UPDATE wallet SET Funds = Funds + :amount WHERE UserID = :userid";
    $params2 = [
        "amount" => $prize,
        "userid" => $userid
    ];

    send_query($query2, false, false, $params2);

    echo json_encode(['success' => true]);
    return;

}
echo json_encode(['success' => false]);
return;

?>