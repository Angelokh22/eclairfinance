<?php

require "../tools.php";

header('Content-Type: application/json');



$winningPersonSQL = "SELECT 
    u.UserID,
    u.UserFirstName,
    u.UserLastName,
    (IFNULL(w.Funds, 0) +
        SUM(IFNULL(s.ShareAmount * c.CompanySharePrice, 0)) +
        SUM(IFNULL(n.NFTPrice, 0))) AS TotalAmount
FROM 
    users u
LEFT JOIN 
    wallet w ON u.UserID = w.UserID
LEFT JOIN 
    shares s ON u.UserID = s.UserID
LEFT JOIN 
    companies c ON s.CompanyID = c.CompanyID
LEFT JOIN 
    nft n ON u.UserID = n.UserID
GROUP BY 
    u.UserID
ORDER BY 
    TotalAmount DESC
LIMIT 1;
";



$winningTeamsql = "SELECT 
    u.UserTeam,
    SUM(w.Funds + IFNULL(s.ShareAmount * c.CompanySharePrice, 0) + IFNULL(n.NFTPrice, 0)) AS TotalMoney
FROM 
    users u
LEFT JOIN 
    wallet w ON u.UserID = w.UserID
LEFT JOIN 
    shares s ON u.UserID = s.UserID
LEFT JOIN 
    companies c ON s.CompanyID = c.CompanyID
LEFT JOIN 
    nft n ON u.UserID = n.UserID
GROUP BY 
    u.UserTeam
ORDER BY 
    TotalMoney DESC
LIMIT 1;
";



$winningPersonResult = send_query($winningPersonSQL, true, false, []);

$winningTeamResult = send_query($winningTeamsql, true, false, []);

// print_r($winningTeamResult);

if ($winningPersonResult && $winningTeamResult) {


    $winningPerson = $winningPersonResult['UserFirstName'] . " " . $winningPersonResult['UserLastName'];
    $winningPersonAmount = $winningPersonResult['TotalAmount'];

    $winningTeam = $winningTeamResult['UserTeam'];
    $winningTeamAmount = $winningTeamResult['TotalMoney'];

    // echo $winningPerson . " " . $winningPersonAmount . " " . $winningTeam . " " . $winningTeamAmount;
    echo json_encode(['success' => true, 'person' => $winningPerson, 'personAmount' => $winningPersonAmount, 'team' => $winningTeam, 'teamAmount' => $winningTeamAmount]);
    return;

}

echo json_encode(['success' => false]);
return;

?>