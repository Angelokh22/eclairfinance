<?php


require "./tools.php";

header('Content-Type: application/json');


if (
    isset($_GET['companyid']) && !empty($_GET['companyid'])
) {

    $sql = "SELECT CompanySharePrice FROM companies WHERE CompanyID = :cid";
    $params = [
        "cid" => $_GET['companyid']
    ];

    $result = send_query($sql, true, false, $params);
    if ($result) {

        $companySharePrice = $result['CompanySharePrice'];

        echo json_encode(['success' => true, "sharePrice" => $companySharePrice]);

    } else {
        echo json_encode(['success' => false]);
    }

} else {
    echo json_encode(['success' => false]);
}


?>