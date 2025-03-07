<?php


require "../tools.php";

header('Content-Type: application/json');


if (
    isset($_POST['userid']) && !empty($_POST['userid'])
    &&
    isset($_POST['companyid']) && !empty($_POST['companyid'])
) {

    $query2 = "SELECT * FROM gameregister WHERE UserID = :userid";
    $params2 = [
        "userid" => $_POST['userid'],
    ];

    $result = send_query($query2, true, false, $params2);
    if ($result) {

        echo json_encode(['success' => false, 'error' => 'Already in a game!']);
        return;

    }

    $query1 = "SELECT COUNT(*) FROM gameregister WHERE CompanyID = :cid";
    $params1 = [
        'cid' => $_POST['companyid']
    ];

    $result = send_query($query1, true, false, $params1);
    if ($result) {

        $playerCount = $result[0];

        $query2 = "SELECT MaxPlayer FROM companies WHERE CompanyID = :cid";
        $params2 = [
            'cid' => $_POST['companyid']
        ];

        $result = send_query($query2, true, false, $params2);
        if ($result) {

            $maxPlayer = $result['MaxPlayer'];

            if ($playerCount < $maxPlayer) {

                $query1 = "INSERT INTO gameregister VALUES (:cid, :userid)";
                $params1 = [
                    "cid" => $_POST['companyid'],
                    "userid" => $_POST['userid']
                ];

                send_query($query1, false, false, $params1);

                echo json_encode(['success' => true]);
                return;

            } else {

                echo json_encode(['success' => false, 'error' => 'Max Players Reached!']);
                return;

            }

        }

    }



}

echo json_encode(['success' => false, 'error' => 'Server Problem!']);
return;

?>