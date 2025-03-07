<?php


require "../tools.php";

header('Content-Type: application/json');


if (
    isset($_GET['userid']) && !empty($_GET['userid'])
    &&
    isset($_GET['companyid']) && !empty($_GET['companyid'])
    &&
    isset($_GET['amount']) && !empty($_GET['amount'])
) {

    $uid = $_GET['userid'];
    $cid = $_GET['companyid'];
    $amount = $_GET['amount'];


    $query1 = "SELECT Funds FROM wallet WHERE UserID = :userid";
    $params1 = [
        "userid" => $uid,
    ];

    $resultFunds = send_query($query1, true, false, $params1);
    if ($resultFunds) {

        if ($resultFunds['Funds'] < $amount) {
            echo json_encode(["success" => false, "error" => "Insufficient Funds!"]);
        } else {

            $query2 = "SELECT CompanySharePrice FROM companies WHERE CompanyID = :cid";
            $params2 = [
                'cid' => $cid,
            ];

            $resultCompanySharePrice = send_query($query2, true, false, $params2);
            if ($resultCompanySharePrice) {

                $SharePrice = $resultCompanySharePrice['CompanySharePrice'];

                // $query3 = "INSERT INTO shares VALUES (:userid, :cid, :amount)";
                // $params3 = [
                //     'userid' => $uid,
                //     'cid' => $cid,
                //     'amount' => $amount / $SharePrice,
                // ];
                $query3 = "UPDATE shares 
SET ShareAmount = ShareAmount + :amount 
WHERE UserID = :userid AND CompanyID = :cid;

INSERT INTO shares (UserID, CompanyID, ShareAmount) 
SELECT :userid, :cid, :amount 
WHERE NOT EXISTS (SELECT 1 FROM shares WHERE UserID = :userid AND CompanyID = :cid);";
                $params3 = [
                    "amount" => $amount / $SharePrice,
                    "userid" => $uid,
                    "cid" => $cid

                ];

                send_query($query3, false, false, $params3);


                $query4 = "UPDATE wallet SET Funds = :funds WHERE UserID = :userid";
                $params4 = [
                    "funds" => $resultFunds['Funds'] - $amount,
                    "userid" => $uid,
                ];

                send_query($query4, false, false, $params4);

                echo json_encode(['success' => true]);
            } else {
                echo json_encode(['success' => false]);
            }

        }

    } else {
        echo json_encode(['success' => false]);
    }

} else {
    echo json_encode(['success' => false]);
}




?>