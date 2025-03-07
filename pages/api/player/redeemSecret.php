<?php


require "../tools.php";

header('Content-Type: application/json');


if (
    isset($_POST['userid']) && !empty($_POST['userid'])
    &&
    isset($_POST['code']) && !empty($_POST['code'])
) {

    $userid = $_POST['userid'];
    $code = $_POST['code'];

    $query1 = "SELECT * FROM nft WHERE BINARY NFTCode = :code";
    $params1 = [
        'code' => $code,
    ];

    $result = send_query($query1, true, false, $params1);
    if ($result) {

        $nftUserRedeem = $result['UserID'];
        if ($nftUserRedeem != 0) {
            echo json_encode(['success' => false, 'error' => 'NFT already Redeemed!']);
            return;
        }

        $query2 = "UPDATE nft SET UserID = :userid WHERE NFTCode = :code";
        $params2 = [
            "userid" => $userid,
            "code" => $code
        ];

        send_query($query2, false, false, $params2);

        echo json_encode(['success' => true]);
        return;

    } else {
        echo json_encode(['success' => false, 'error' => 'Wrong Secret Code!']);
        return;
    }

} else {
    echo json_encode(['success' => false, 'error' => 'Missing Informations!']);
    return;
}

?>