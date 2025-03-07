<?php


require "../tools.php";

header('Content-Type: application/json');


if (isset($_POST['userid']) && !empty($_POST['userid'])) {

    $userid = $_POST['userid'];

    $sql = "DELETE FROM gameregister WHERE UserID = :userid";
    $params = [
        'userid' => $userid,
    ];

    send_query($sql, false, false, $params);

    echo json_encode(['success' => true]);
    return;

} else {

    echo json_encode(['success' => false, 'error' => 'Missing Informations!']);
    return;

}


?>