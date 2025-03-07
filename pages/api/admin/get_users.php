<?php

require "../tools.php";

header('Content-Type: application/json');

$sql = "SELECT * FROM users WHERE UserAccess = 2";
$result = send_query($sql);

$users = [];

if ($result) {
    foreach ($result as $user) {

        $userId = $user['UserID'];
        $userFname = $user['UserFirstName'];
        $userLname = $user['UserLastName'];


        $wallet = "SELECT Funds FROM wallet WHERE UserID = :userid";
        $walletResult = send_query($wallet, true, false, ["userid" => $userId]);
        $funds = $walletResult['Funds'];

        $totalShares = 0.0;
        $totalValue = 0.0;
        $shares = "SELECT * FROM shares WHERE UserID = :userid";
        $sharesResult = send_query($shares, true, true, ["userid" => $userId]);
        foreach ($sharesResult as $share) {
            $totalShares += $share['ShareAmount'];

            $companyId = $share['CompanyID'];
            $companySharePrice = "SELECT CompanySharePrice FROM companies WHERE CompanyID = :companyid";
            $companySharePriceResult = send_query($companySharePrice, true, false, ["companyid" => $companyId]);
            $sharePrice = $companySharePriceResult['CompanySharePrice'];
            $totalValue += $sharePrice * $share['ShareAmount'];
        }

        $playing = "-";
        $gameregister = "SELECT CompanyID FROM gameregister WHERE UserID = :userid";
        $gameregisterResult = send_query($gameregister, true, false, ["userid" => $userId]);
        if ($gameregisterResult) {

            $companyId = $gameregisterResult['CompanyID'];

            $companyResult = "SELECT UserID FROM companies WHERE CompanyID = :companyid";
            $companyResult = send_query($companyResult, true, false, ["companyid" => $companyId]);
            $CompanyOwnerID - $companyResult['UserID'];

            $CompanyOwner = "SELECT * FROM users WHERE UserID = :userid";
            $CompanyOwner = send_query($CompanyOwner, true, false, ["userid" => $CompanyOwnerID]);
            $playing = $CompanyOwner['UserFirstName'] . " " . $CompanyOwner['UserLastName'];

        }

        $userInfo = [
            "name" => $userFname . " " . $userLname,
            "funds" => $funds,
            "value" => $totalValue,
            "shares" => $totalShares,
            "playing" => $playing
        ];

        array_push($users, $userInfo);

    }
}

echo json_encode(["success" => true, "users" => $users]);


?>