<?php

//get json response
function Response($response)
{
    echo json_encode($response, JSON_UNESCAPED_SLASHES);
}

//execute command and get response
function getResponse($stmt)
{
    $state = false;
    global $connection;
    if (!$stmt->execute()) {
        $state = false;
        $response['error'] = true;
        $response['message'] = $connection->errno . ' ' . $connection->error;
    } else {
        $response['error'] = false;
        $state = true;
        //fetch stored id
        $response['Id'] = $connection->insert_id;
    }
    Response($response);
    return $state;
}

//upload image
function uploadFile($fileName, $fileType)
{
    error_reporting(0);
    if ($fileType == 'ImgPath') {
        $ImgSaveName = IMG_PATH . $fileName;
        compressImage($_FILES['ImgPath']['tmp_name'], $ImgSaveName, 30);
    } else if ($fileType == 'PdfPath') {
        $PdfSaveName = PDF_PATH . $fileName;
        move_uploaded_file($_FILES['PdfPath']['tmp_name'], $PdfSaveName);;
    }
}

//index title
function indexTitle($title)
{
    $sound = null;
    $words = explode(" ", $title);
    foreach ($words as $word) {
        $sound .= metaphone($word) . " ";
    }
    return  $sound;
}

//get file name
function getFileName($table, $Id, $FileType)
{
    global $connection;
    $File = null;
    if (isset($_FILES[$FileType]['name'])) {
        $path = $_FILES[$FileType]['name'];
        $extension = getFileExtension($path);
        $File = round(microtime(true) * 1000) . '.' . $extension;
    } else {
        //get current name
        $stmt = $connection->prepare("SELECT $FileType FROM $table WHERE Id = ?");
        $stmt->bind_param("s", $Id);
        if ($stmt->execute()) {
            $stmt->bind_result($File);
            $stmt->store_result();
            $stmt->fetch();
        } else {
            $File = null;
        }
        $stmt->close();
    }
    return  $File;
}

// Compress image
function compressImage($source, $destination, $quality)
{
    $info = getimagesize($source);

    if ($info['mime'] == 'image/jpeg') {
        $image = imagecreatefromjpeg($source);
    } elseif ($info['mime'] == 'image/gif') {
        $image = imagecreatefromgif($source);
    } elseif ($info['mime'] == 'image/png') {
        $image = imagecreatefrompng($source);
    }

    imagejpeg($image, $destination, $quality);
}

//get image extension
function getFileExtension($file)
{
    $path_parts = pathinfo($file);
    return $path_parts['extension'];
}

//unlink file
function unlinkFile($table, $Id, $Column)
{
    error_reporting(0);
    global $connection;
    $File = null;

    $stmt = $connection->prepare("SELECT $Column FROM $table WHERE Id = ?");
    $stmt->bind_param("s", $Id);
    if ($stmt->execute()) {
        $stmt->bind_result($File);
        $stmt->store_result();
        $stmt->fetch();
        $stmt->close();

        //delete if image is available
        if ($File != null) {
            unlink(IMG_PATH . $File);
            unlink(PDF_PATH . $File);
        }
    };
}

//sanitize
function sanitize($z)
{
    $z = strtolower($z);
    $z = preg_replace('/[^a-z0-9 -]+/', '', $z);
    $z = str_replace(' ', ' ', $z);
    return trim($z, '-');
}

function isTheseParametersAvailable($params)
{
    //traversing through all the parameters
    foreach ($params as $param) {
        //if the paramter is not available
        if (!isset($_POST[$param])) {
            //return false
            return false;
        }
    }
    //return true if every param is available
    return true;
}
