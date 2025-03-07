<?php


require "../tools.php";

header('Content-Type: application/json');



if (
    $_GET['companyid'] && !empty($_GET['companyid'])
) {

    $Players = "SELECT UserID FROM gameregister WHERE CompanyID = :companyid";
    $countPlayers = "SELECT COUNT(UserID) FROM gameregister WHERE CompanyID = :companyid";


    $countPlayersResult = send_query($countPlayers, true, true, ["companyid" => $_GET['companyid']]);

    if ($countPlayersResult[0] <= 0) {
        echo json_encode(["success" => false]);
    } else {
        $PlayersResult = send_query($Players, true, true, ["companyid" => $_GET['companyid']]);
        if ($PlayersResult) {

            $players = [];

            foreach ($PlayersResult as $player) {

                $userid = $player['UserID'];
                $companyid = $_GET['companyid'];

                $query1 = "SELECT UserID, UserFirstName, UserLastName FROM users WHERE UserID = :userid";
                $query2 = "SELECT ShareAmount FROM shares WHERE CompanyID = :companyid AND UserID = :userid";
                $query3 = "SELECT CompanySharePrice FROM companies WHERE CompanyID = :companyid";
                $query4 = "SELECT Prize FROM prizes WHERE CompanyID = :companyid";

                $params1 = ["userid" => $userid];
                $params2 = ["companyid" => $companyid, "userid" => $userid];
                $params3 = ["companyid" => $companyid];
                $params4 = ["companyid" => $companyid];

                $result1 = send_query($query1, true, false, $params1);
                $result2 = send_query($query2, true, false, $params2);
                $result3 = send_query($query3, true, false, $params3);
                $result4 = send_query($query4, true, true, $params4);

                if ($result1) {

                    $fullName = $result1['UserFirstName'] . " " . $result1['UserLastName'];
                    $userid = $result1['UserID'];

                }

                if ($result2) {

                    $playerShares = $result2['ShareAmount'];

                } else {
                    $playerShares = 0;
                }

                if ($result3) {

                    $playerSharesPrice = $playerShares * $result3['CompanySharePrice'];

                } else {
                    $playerSharesPrice = 0;
                }

                if ($result4) {

                    $prizes = [];
                    foreach ($result4 as $prize) {
                        $prizes[] = $prize['Prize'];
                    }

                } else {
                    $prizes = [];
                }

                $players[] = [
                    "userid" => $userid,
                    "name" => $fullName,
                    "shares" => $playerShares,
                    "sharesprice" => $playerSharesPrice
                ];
            }

            echo json_encode(["success" => true, "players" => $players, "prizes" => $prizes]);

        } else {
            echo json_encode(["success" => false]);
        }
    }

}

?>