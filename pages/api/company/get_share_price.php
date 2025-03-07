<?php


require "../tools.php";

header('Content-Type: application/json');


if (
    $_GET['companyid'] && !empty($_GET['companyid'])
) {

    $sql = "SELECT CompanySharePrice FROM companies WHERE CompanyID = :companyid";
    $params = [
        'companyid' => $_GET['companyid']
    ];

    $result = send_query($sql, true, false, $params);
    if ($result) {

        $sharePrice = $result['CompanySharePrice'];
        echo json_encode(['success' => true, 'sharePrice' => $sharePrice]);

    } else {
        echo json_encode(['success' => false]);
    }

} else {
    echo json_encode(['success' => false]);
}

?>