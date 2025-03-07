<?php

require 'connection.php';

function send_query($query, $fetching = true, $fetch_all = true, $params = [])
{
    global $pdo;
    $stmt = $pdo->prepare($query);
    if ($fetching) {
        $stmt->execute($params);
        if ($fetch_all) {
            return $stmt->fetchAll();
        }
        return $stmt->fetch();
    } else {
        $stmt->execute($params);
        return "Query Sent!";
    }
}

function truncateNumber($num, $precision)
{
    // Convert number to string
    $numStr = strval($num);

    if (strpos($numStr, '.') !== false) {
        list($integerPart, $decimalPart) = explode('.', $numStr, 2);
        // Format the integer part with commas
        $formattedInteger = number_format((int) $integerPart);
        // Truncate the decimal part to the specified precision
        $truncatedDecimal = substr($decimalPart, 0, $precision);
        return $formattedInteger . ($precision > 0 ? "." . $truncatedDecimal : '');
    }

    // If there's no decimal part, just format the integer part
    return number_format((int) $numStr);
}

function DeTruncateNumber($formattedNum)
{
    // Remove commas
    $numStr = str_replace(',', '', $formattedNum);

    // Convert to float or integer based on presence of a decimal
    return strpos($numStr, '.') !== false ? (float) $numStr : (int) $numStr;
}


?>