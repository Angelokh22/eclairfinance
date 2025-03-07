<?php


require "../tools.php";

header('Content-Type: application/json');

$uploadDir = '../../../assets/img/company_logos/';

$response = ["success" => false];


if (
    isset($_FILES["logo"])
    &&
    isset($_POST['companyName']) && !empty($_POST['companyName'])
    &&
    isset($_POST['maxMembers']) && !empty($_POST['maxMembers'])
    &&
    isset($_POST['fullName']) && !empty($_POST['fullName'])
    &&
    isset($_POST['email']) && !empty($_POST['email'])
    &&
    isset($_POST['password']) && !empty($_POST['password'])
    &&
    isset($_POST['sharePrice']) && !empty($_POST['sharePrice'])
    &&
    isset($_POST['Prizes']) && !empty($_POST['Prizes'])
) {

    $logo = $_FILES["logo"];
    $companyName = $_POST['companyName'];
    $maxMembers = $_POST['maxMembers'];
    $fullName = $_POST['fullName'];
    $email = $_POST['email'];
    $password = $_POST['password'];
    $sharePrice = $_POST['sharePrice'];
    $Prizes = $_POST['Prizes'];

    $splitname = explode(" ", $fullName);
    $fname = $splitname[0];
    $lname = $splitname[1];

    if ($logo['error'] === UPLOAD_ERR_OK) {
        $fileName = basename($logo["name"]);
        $filePath = $uploadDir . $fileName;

        if (move_uploaded_file($logo["tmp_name"], $filePath)) {

            $query1 = "INSERT INTO users (UserFirstName, UserLastName, UserTeam, UserEmail, UserPassword, UserAccess) VALUES (:fname, :lname, :team, :uemail, :upass, :uaccess)";
            $params1 = [
                'fname' => $fname,
                'lname' => $lname,
                'team' => "Eclair",
                'uemail' => $email,
                'upass' => $password,
                'uaccess' => 1
            ];
            send_query($query1, false, false, $params1);

            $query2 = "SELECT UserID FROM users WHERE UserEmail = :uemail";
            $params2 = [
                'uemail' => $email
            ];
            $result = send_query($query2, true, false, $params2);
            $userID = $result['UserID'];

            $query3 = "INSERT INTO companies (UserID, CompanyName, CompanySharePrice, CompanyLogoName, MaxPlayer) VALUES (:userid, :companyname, :companyshareprice, :companylogoname, :maxplayer)";
            $params3 = [
                "userid" => $userID,
                "companyname" => $companyName,
                "companyshareprice" => $sharePrice,
                "companylogoname" => $fileName,
                "maxplayer" => $maxMembers
            ];
            send_query($query3, false, false, $params3);

            $query4 = "SELECT CompanyID FROM companies WHERE UserID = :userid";
            $params4 = [
                "userid" => $userID
            ];
            $result = send_query($query4, true, false, $params4);
            $companyid = $result['CompanyID'];

            foreach ($Prizes as $prize) {

                $query5 = "INSERT INTO prizes VALUES (:companyid, :prize)";
                $params5 = [
                    "companyid" => $companyid,
                    "prize" => $prize
                ];

                send_query($query5, false, false, $params5);

            }

            echo json_encode(["success" => true, "message" => "Company created successfully."]);

        } else {
            echo json_encode(["success" => false, "message" => "Failed to upload logo."]);
        }
    } else {
        echo json_encode(["success" => false, "message" => "Failed to upload logo."]);
    }
}
?>