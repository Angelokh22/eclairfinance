<?php


require "../tools.php";

header('Content-Type: application/json');


if
(
    isset($_POST['userid']) && !empty($_POST['userid'])
    &&
    isset($_POST['companyid']) && !empty($_POST['companyid'])
) {

    $sql = "SELECT ShareAmount FROM shares WHERE UserID = :userid AND CompanyID = :cid";
    $params = [
        "userid" => $_POST['userid'],
        "cid" => $_POST['companyid']
    ];

    $result = send_query($sql, true, false, $params);
    if ($result) {

        $shareAmount = $result['ShareAmount'];
        echo json_encode(['success' => true, 'shares' => $shareAmount]);
        return;
    }

    echo json_encode(['success' => true, 'shares' => 0]);
    return;

}
echo json_encode(['success' => false, 'error' => 'Missing Informatons!']);
return;


?>