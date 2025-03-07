<?php

require "../tools.php";

header('Content-Type: application/json');


$Companies = [];


// Company Info
$query1 = "SELECT * FROM companies";
$query2 = "SELECT SUM(ShareAmount) FROM shares WHERE CompanyID = :companyid";
$query3 = "SELECT COUNT(UserID) FROM gameregister WHERE CompanyID = :companyid";

// Player Info
$query4 = "SELECT * FROM users WHERE UserID IN (SELECT UserID FROM gameregister WHERE CompanyID = :companyid)";
$query5 = "SELECT * FROM wallet WHERE UserID = :userid";
$query6 = "SELECT * FROM shares WHERE UserID = :userid AND CompanyID = :companyid";


$result1 = send_query($query1, true, true, []);
if ($result1) {
    foreach ($result1 as $company) {

        $CompanyID = $company['CompanyID'];
        $CompanyName = $company['CompanyName'];
        $CompanyLogoName = $company['CompanyLogoName'];
        $CompanySharePrice = $company['CompanySharePrice'];

        // Add company data to the array
        $Companies[$CompanyID] = [
            "CompanyID" => $CompanyID,
            "CompanyName" => $CompanyName,
            "CompanyLogoName" => $CompanyLogoName,
            "CompanySharePrice" => truncateNumber($CompanySharePrice, 2)
        ];
    }
} else {
    echo json_encode(['success' => false]);
    return;
}


foreach ($Companies as $comps => $company) {
    $CompanyID = $company['CompanyID'];

    $result2 = send_query($query2, true, false, ['companyid' => $CompanyID]);
    if ($result2) {
        $ShareAmount = $result2[0];
        if ($ShareAmount) {
            $Companies[$CompanyID]['SoldShares'] = $ShareAmount;
        } else {
            $Companies[$CompanyID]['SoldShares'] = 0;
        }
    } else {
        echo json_encode(['success' => false]);
        return;
    }

    $CompanyNetWorth = $ShareAmount * DeTruncateNumber($Companies[$CompanyID]['CompanySharePrice']);
    $Companies[$CompanyID]['CompanyNetWorth'] = truncateNumber($CompanyNetWorth, 2);

    $result3 = send_query($query3, true, false, ['companyid' => $CompanyID]);
    if ($result3) {
        $UserCount = $result3[0];
        $Companies[$CompanyID]['PlayersCount'] = $UserCount;
    } else {
        echo json_encode(['success' => false]);
        return;
    }

    $result4 = send_query($query4, true, true, ['companyid' => $CompanyID]);
    if ($result4) {

        foreach ($result4 as $_ => $value) {

            $UserID = $value['UserID'];
            $UserFirstName = $value['UserFirstName'];
            $UserLastName = $value['UserLastName'];

            $result5 = send_query($query5, true, true, ['userid' => $UserID]);
            if ($result5) {
                $Funds = $result5[0]['Funds'];
            } else {
                $Funds = 0;
            }

            $result6 = send_query($query6, true, true, ['userid' => $UserID, 'companyid' => $CompanyID]);
            if ($result6) {
                $ShareAmountUser = $result6[0]['ShareAmount'];
            } else {
                $ShareAmountUser = 0;
            }

            $Companies[$CompanyID]['Players'][$UserID] = [
                "UserID" => $UserID,
                "UserFirstName" => $UserFirstName,
                "UserLastName" => $UserLastName,
                "Funds" => truncateNumber($Funds, 2),
                "CompanyReturnUser" => truncateNumber($Companies[$CompanyID]['CompanySharePrice'] * $ShareAmountUser, 2),
                "ShareAmountUser" => truncateNumber($ShareAmountUser, 2)
            ];
        }



    } else {
        $Companies[$CompanyID]['Players'] = [];
    }
}


echo json_encode(["success" => true, "companies" => $Companies]);
return;
// print_r($Companies);
?>