<?php


require "../tools.php";

header('Content-Type: application/json');

if (
    isset($_POST['userid']) && !empty($_POST['userid'])
) {
    $userid = $_POST['userid'];

    $funds = 0.00;

    $query6 = "SELECT NFTPrice FROM nft WHERE UserID = :userid";
    $params6 = [
        "userid" => $userid
    ];

    $result = send_query($query6, true, true, $params6);
    if ($result) {

        foreach ($result as $nft) {

            $nftPrice = $nft['NFTPrice'];
            $funds = $funds + $nftPrice;

        }

    }

    $query4 = "SELECT CompanyID, ShareAmount FROM shares WHERE UserID = :userid";
    $params4 = [
        "userid" => $userid,
    ];

    $resultShareAmount = send_query($query4, true, true, $params4);
    if ($resultShareAmount) {

        // print_r($resultShareAmount);
        foreach ($resultShareAmount as $ShareAmount) {

            $companyid = $ShareAmount['CompanyID'];
            $amount = $ShareAmount['ShareAmount'];

            $query5 = "SELECT CompanySharePrice FROM companies WHERE CompanyID = :cid";
            $params5 = [
                "cid" => $companyid,
            ];

            $resultCompanySharePrice = send_query($query5, true, false, $params5);
            if ($resultCompanySharePrice) {

                $CompanySharePrice = $resultCompanySharePrice['CompanySharePrice'];

                // echo truncateNumber($amount * $CompanySharePrice, 2);
                $funds = $funds + ($amount * $CompanySharePrice);

            }
        }

        http_response_code(200);
        echo json_encode(['success' => true, 'sharefunds' => $funds]);
        return;

    } else {
        http_response_code(200);
        echo json_encode(['success' => false, 'sharefunds' => $funds]);
        return;
    }
} else {
    http_response_code(403);
    echo json_encode(['success' => false, 'error' => 'userid is needed!']);
    return;

}
?>