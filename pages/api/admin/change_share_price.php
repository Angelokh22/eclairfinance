<?php

require "../tools.php";

header('Content-Type: application/json');

if (
    isset($_POST['cid']) && !empty($_POST['cid'])
    &&
    isset($_POST['value']) && !empty($_POST['value'])
    &&
    isset($_POST['option']) && !empty($_POST['option'])
) {

    $cid = $_POST['cid'];
    $value = $_POST['value'];
    $option = $_POST['option'];

    $query = "SELECT CompanySharePrice FROM companies WHERE CompanyID = :cid";
    $params = [
        'cid' => $cid
    ];

    $result = send_query($query, true, false, $params);
    if ($result) {

        $shareprice = $result['CompanySharePrice'];
        $sharepricepercent = $shareprice * $value;


        if ($option == "+") {
            $shareprice += $sharepricepercent;
        } else if ($option == "-") {
            $shareprice -= $sharepricepercent;
        }
        $query = "UPDATE companies SET CompanySharePrice = :shareprice WHERE CompanyID = :cid";
        $params = [
            'shareprice' => $shareprice,
            'cid' => $cid
        ];
        send_query($query, false, false, $params);

        echo json_encode(['success' => true, "msg" => "Share Price Updated!"]);
        return;

    } else {
        echo json_encode(['success' => false, "msg" => "Error updating share price."]);
        return;
    }

} else {
    echo json_encode(['success' => false, "msg" => "Invalid request."]);
    return;
}

?>