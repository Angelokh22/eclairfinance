<?php


require "../tools.php";

header('Content-Type: application/json');

if (
    isset($_POST['userid']) && !empty($_POST['userid'])
    &&
    isset($_POST['companyid']) && !empty($_POST['companyid'])
    &&
    isset($_POST['amount']) && !empty($_POST['amount'])
) {

    $userid = $_POST['userid'];
    $companyid = $_POST['companyid'];
    $amount = $_POST['amount'];


    $query1 = "SELECT ShareAmount FROM shares WHERE UserID = :userid AND CompanyID = :cid";
    $params1 = [
        "userid" => $userid,
        "cid" => $companyid
    ];

    $result = send_query($query1, true, false, $params1);
    if ($result) {

        $shareAmount = $result['ShareAmount'];

        if ($amount > $shareAmount) {

            echo json_encode(['success' => false, 'error' => 'Insufficient Shares!']);
            return;

        }

        $query2 = "UPDATE shares SET ShareAmount = ShareAmount - :amount WHERE UserID = :userid AND CompanyID = :cid";
        $params2 = [
            'amount' => $amount,
            'userid' => $userid,
            'cid' => $companyid
        ];

        send_query($query2, false, false, $params2);

        $query3 = "SELECT CompanySharePrice FROM companies WHERE CompanyID = :cid";
        $params3 = [
            'cid' => $companyid,
        ];

        $result = send_query($query3, true, false, $params3);
        if ($result) {

            $companySharePrice = $result['CompanySharePrice'];

            $finalAmount = $amount * $companySharePrice;


            $query4 = "UPDATE wallet SET Funds = Funds + :amount WHERE UserID = :userid";
            $params4 = [
                'amount' => $finalAmount,
                'userid' => $userid,
            ];

            send_query($query4, false, false, $params4);

            echo json_encode(['success' => true]);
            return;
        }

        echo json_encode(['success' => false, 'error' => 'Server Error!']);
        return;


    }

    echo json_encode(['success' => false, 'error' => 'No Shares Available!']);
    return;

}

echo json_encode(['success' => false, 'error' => 'Missing Information!']);