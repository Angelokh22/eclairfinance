<?php


require "../tools.php";

header('Content-Type: application/json');


if (
    isset($_GET['userid']) && !empty($_GET['userid'])
) {

    $userid = $_GET['userid'];

    $query1 = "SELECT * FROM gameregister WHERE UserID = :userid";
    $params1 = [
        "userid" => $userid
    ];

    $result = send_query($query1, true, false, $params1);
    if ($result) {

        $cid = $result['CompanyID'];

        $query2 = "SELECT CompanyName FROM companies WHERE CompanyID = :cid";
        $params2 = [
            'cid' => $cid,
        ];
        $result = send_query($query2, true, false, $params2);
        if ($result) {

            $companyname = $result['CompanyName'];

            echo json_encode(['success' => true, 'company' => $companyname]);
            return;
        }

    }

    echo json_encode(['success' => false]);
    return;

}


?>