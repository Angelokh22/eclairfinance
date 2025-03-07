<?php


require "../tools.php";

header('Content-Type: application/json');

if (
    isset($_POST['userid']) && !empty($_POST['userid'])
    &&
    isset($_POST['to']) && !empty($_POST['to'])
    &&
    isset($_POST['amount']) && !empty($_POST['amount'])
    &&
    isset($_POST['companyid']) && !empty($_POST['companyid'])
) {

    $userid = $_POST['userid'];
    $to = $_POST['to'];
    $amount = $_POST['amount'];
    $companyid = $_POST['companyid'];


    $query1 = "SELECT ShareAmount FROM shares WHERE UserID = :userid AND CompanyID = :cid";
    $params1 = [
        'userid' => $userid,
        'cid' => $companyid
    ];

    $result = send_query($query1, true, false, $params1);
    if ($result) {

        $shareAmount = $result['ShareAmount'];
        if ($amount > $shareAmount) {

            echo json_encode(['success' => false, 'error' => 'Insufficient Shares!']);
            return;

        }

        $query2 = "UPDATE shares 
                    SET ShareAmount = ShareAmount + :amount 
                    WHERE UserID = :userid AND CompanyID = :cid;

                    INSERT INTO shares (UserID, CompanyID, ShareAmount) 
                    SELECT :userid, :cid, :amount 
                    WHERE NOT EXISTS (SELECT 1 FROM shares WHERE UserID = :userid AND CompanyID = :cid);";
        $params2 = [
            "amount" => $amount,
            "userid" => $to,
            "cid" => $companyid
        ];

        send_query($query2, false, false, $params2);

        $query3 = "UPDATE shares SET ShareAmount = ShareAmount - :amount WHERE UserID = :userid";
        $params3 = [
            "amount" => $amount,
            "userid" => $userid
        ];

        send_query($query3, false, false, $params3);

        echo json_encode(['success' => true]);
        return;

    }

} else {

    echo json_encode(['success' => false, 'error' => 'Missing Informations!']);
    return;

}


?>