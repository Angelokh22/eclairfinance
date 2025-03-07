<?php


require "../tools.php";

header('Content-Type: application/json');


if (
    isset($_POST['userid']) && !empty($_POST['userid'])
    &&
    isset($_POST['companyid']) && !empty($_POST['companyid'])
) {

    $userid = $_POST['userid'];
    $companyid = $_POST['companyid'];


    $query1 = "SELECT ShareAmount FROM shares WHERE UserID = :userid AND CompanyID = :cid";
    $params1 = [
        'userid' => $userid,
        'cid' => $companyid
    ];

    $result = send_query($query1, true, false, $params1);
    if ($result) {

        $shareAmount = $result['ShareAmount'];

        echo json_encode(['success' => true, 'availableShares' => $shareAmount]);
        return;

    } else {

        echo json_encode(['success' => false, 'error' => 'No Shares Available']);
        return;

    }

} else {

    echo json_encode(['success' => false, 'error' => 'Missing Informations']);
    return;

}


?>