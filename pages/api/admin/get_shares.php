<?php

require "../tools.php";

header('Content-Type: application/json');

$companies = [];

$sql = "SELECT * FROM companies";
$result = send_query($sql);
if ($result) {
    foreach ($result as $company) {

        $query = "SELECT UserFirstName, UserLastName FROM users WHERE UserID = :userid";
        $params = [
            'userid' => $company['UserID']
        ];

        $result = send_query($query, true, false, $params);
        if ($result) {
            $fullName = $result['UserFirstName'] . ' ' . $result['UserLastName'];
            $query = "SELECT * FROM companies WHERE UserID = :userid";
            $params = [
                'userid' => $company['UserID']
            ];
            $result = send_query($query, true, false, $params);
            $companyid = $result['CompanyID'];
            $companyName = $result['CompanyName'];
            $sharePrice = truncateNumber($result['CompanySharePrice'], 2);
            $logo = $result['CompanyLogoName'];

            $company = [
                "companyid" => $companyid,
                "companyName" => $companyName,
                "fullName" => $fullName,
                "sharePrice" => $sharePrice,
                "logo" => $logo
            ];

            array_push($companies, $company);
        }
    }

    echo json_encode(["success" => true, "companies" => $companies]);
} else {
    echo json_encode(["success" => false, "message" => "Failed to retrieve companies."]);
}

?>