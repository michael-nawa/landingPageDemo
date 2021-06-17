<?php

require_once 'includes/starter.php';
require_once 'includes/scripts.php';

if (isset($_GET['apicall'])) {
    if (isset($_GET['page_num'])) {
        $page_num = $_GET['page_num'];
    }

    switch ($_GET['apicall']) {

        case 'account':
            $src = $_GET['src'];
            $user_id = 0;

            function userList($stmt)
            {
                $Id = null;
                $Names = null;
                $Email = null;
                $Address = null;
                $Roles = null;
                $Password = null;

                $stmt->execute();
                $stmt->bind_result($Id, $Names, $Email, $Password, $Address, $Roles);
                $results = array();
                while ($stmt->fetch()) {
                    $temp = array();
                    $temp['Id'] = $Id;
                    $temp['Names'] = $Names;
                    $temp['Email'] = $Email;
                    $temp['Address'] = $Address;
                    $temp['Roles'] = $Roles;
                    $temp['Password'] = $Password;
                    array_push($results, $temp);
                }
                $response['error'] = false;
                $response['results'] = $results;

                Response($response);
            }

            switch ($src) {
                case 'login':
                    $Email = $_POST['Email'];
                    $Password = md5($_POST['Password']);

                    $stmt = $connection->prepare("SELECT *
                    from users  
                    WHERE Email = ? and Password = ?");
                    $stmt->bind_param("ss", $Email, $Password);
                    $stmt->execute();
                    $stmt->store_result();
                    if ($stmt->num_rows == 0) {
                        $response['error'] = true;
                        $response['message'] = 'Account Does Not Exist';
                        Response($response);
                        $stmt->close();
                    } else {
                        userList($stmt);
                        $stmt->close();
                    }
                    break;

                case 'AdminLogin':
                    $Email = $_POST['Email'];
                    $Password = md5($_POST['Password']);

                    $stmt = $connection->prepare("SELECT  *
                    from users  
                    WHERE Email = ? and Password = ? and Roles='admin'");
                    $stmt->bind_param("ss", $Email, $Password);
                    $stmt->execute();
                    $stmt->store_result();
                    if ($stmt->num_rows == 0) {
                        $response['error'] = true;
                        $response['message'] = 'Account Does Not Exist';
                        Response($response);
                        $stmt->close();
                    } else {
                        userList($stmt);
                        $stmt->close();
                    }
                    break;

                case 'create':
                    $Email = $_POST['Email'];
                    $Roles = $_POST['Roles'];
                    $Password = md5($_POST['Password']);
                    $Names = null;
                    $Address = null;

                    if (isset($_POST['Names'])) {
                        $Names = $_POST['Names'];
                    }
                    if (isset($_POST['Address'])) {
                        $Address = $_POST['Address'];
                    }

                    //check if account exist
                    $stmt = $connection->prepare("SELECT * 
                    FROM users WHERE Email = ?");
                    $stmt->bind_param("s", $Email);
                    $stmt->execute();
                    $stmt->store_result();

                    //if account doesnt exist
                    if ($stmt->num_rows == 0) {

                        //add user account
                        $stmt = $connection->prepare("INSERT INTO users (Names,Email,Password,Address,Roles)
                        VALUES (?,?,?,?,?)");
                        $stmt->bind_param("sssss", $Names, $Email, $Password, $Address, $Roles);
                        if (!$stmt->execute()) {
                            $state = false;
                            $response['error'] = true;
                            $response['message'] = $connection->errno . ' ' . $connection->error;
                            Response($response);
                        } else {
                            // //login
                            $stmt = $connection->prepare("SELECT *
                            from users  
                            WHERE Email = ? and Password = ?");
                            $stmt->bind_param("ss", $Email, $Password);
                            $stmt->execute();
                            $stmt->store_result();
                            userList($stmt);
                        };
                    }

                    //if account exist
                    else {
                        $response['error'] = true;
                        $response['message'] = 'Account already exist';
                        Response($response);
                    }
                    $stmt->close();
                    break;

                case 'update':
                    $Email = $_POST['Email'];
                    $Id = $_POST['Id'];
                    $Names = null;
                    $Address = null;

                    if (isset($_POST['Names'])) {
                        $Names = $_POST['Names'];
                    }
                    if (isset($_POST['Address'])) {
                        $Address = $_POST['Address'];
                    }

                    $stmt = $connection->prepare("UPDATE users 
                    SET Names = ?,Email = ?,Address = ? WHERE Id = ?");
                    $stmt->bind_param("ssss", $Names, $Email, $Address, $Id);
                    if (!$stmt->execute()) {
                        $state = false;
                        $response['error'] = true;
                        $response['message'] = $connection->errno . ' ' . $connection->error;
                        Response($response);
                    } else {
                        //login
                        $stmt = $connection->prepare("SELECT *
                        from users  
                        WHERE Email = ?");
                        $stmt->bind_param("s", $Email);
                        $stmt->execute();
                        $stmt->store_result();
                        userList($stmt);
                    };
                    break;

                case 'delete':
                    $Id = $_GET['Id'];
                    $stmt = $connection->prepare("DELETE from users 
                    WHERE Id = ?");
                    $stmt->bind_param("s", $Id);
                    getResponse($stmt);
                    break;

                case 'get':
                    $Id = $_GET['Id'];

                    $stmt = $connection->prepare("SELECT *
                    from users  
                    WHERE Id = ?");
                    $stmt->bind_param("s", $Id);
                    userList($stmt);
                    break;

                case 'getAll':
                    $stmt = $connection->prepare("SELECT *
                    from users");
                    userList($stmt);
                    break;
            }
            break;

            //hanlde Category
        case 'ProductCategory':
            $src = $_GET['src'];

            function GetCategory($stmt)
            {
                $ImgPath = null;
                $Title = null;
                $Id = null;

                $stmt->execute();
                $stmt->bind_result($Id, $Title, $ImgPath);
                $results = array();
                while ($stmt->fetch()) {
                    $temp = array();
                    $temp['Id'] = $Id;
                    $temp['Title'] = $Title;
                    $temp['ImgPath'] = GET_IMG_Path . $ImgPath;
                    array_push($results, $temp);
                }
                $response['error'] = false;
                $response['results'] = $results;

                Response($response);
            }

            switch ($src) {
                case "create":
                    $Title = $_POST['Title'];

                    //uploading image 
                    $ImgName =  getFileName('productcategory', 0, 'ImgPath');

                    $stmt = $connection->prepare("INSERT INTO productcategory (Title,ImgPath)
                        VALUES (?,?)");
                    $stmt->bind_param("ss", $Title, $ImgName);
                    if (getResponse($stmt)) {
                        if ($ImgName != null) {
                            uploadFile($ImgName, 'ImgPath');
                        }
                    };
                    break;

                case 'update':
                    $Title = $_POST['Title'];
                    $Id = $_POST['Id'];

                    //uploading image 
                    $ImgName =  getFileName('productcategory', $Id, 'ImgPath');

                    if ($ImgName != null) {
                        //remove old image
                        unlinkFile('productcategory', $Id, 'ImgPath');
                    }

                    $stmt = $connection->prepare("UPDATE productcategory 
                        SET Title = ?, ImgPath = ?
                        WHERE Id = ?");
                    $stmt->bind_param("sss", $Title, $ImgName, $Id);
                    if (getResponse($stmt)) {
                        if ($ImgName != null) {
                            uploadFile($ImgName, 'ImgPath');
                        }
                    };
                    break;

                case 'get':
                    $Id = $_GET['Id'];
                    $stmt = $connection->prepare("SELECT * from productcategory
                        WHERE Id = ?");
                    $stmt->bind_param("s", $Id);
                    GetCategory($stmt, $connection);
                    break;

                case 'getAll':
                    $stmt = $connection->prepare("SELECT * from productcategory
                        Order by Id DESC");
                    GetCategory($stmt, $connection);
                    break;

                case 'delete':
                    $Id = $_GET['Id'];
                    unlinkFile('productcategory', $Id, 'ImgPath');
                    $stmt = $connection->prepare("DELETE from productcategory 
                        WHERE Id = ?");
                    $stmt->bind_param("s", $Id);
                    getResponse($stmt);
                    break;
            }
            break;

            //handle Products
        case 'Products':
            $src = $_GET['src'];
            $TableName = 'products';

            function GetProducts($stmt)
            {
                $Id  = null;
                $CategoryId  = null;
                $CategoryName  = null;
                $Title = null;
                $Description = null;
                $Price = null;
                $Quantity = null;
                $ImgPath = null;
                $Timestamp = null;

                $stmt->execute();
                $stmt->bind_result($Id, $CategoryId, $CategoryName, $Title, $Description, $Price, $Quantity, $ImgPath, $Timestamp);
                $results = array();
                while ($stmt->fetch()) {
                    $temp = array();
                    $temp['Id'] = $Id;
                    $temp['CategoryId'] = $CategoryId;
                    $temp['CategoryName'] = $CategoryName;
                    $temp['Description'] = $Description;
                    $temp['Price'] = $Price;
                    $temp['Title'] = $Title;
                    $temp['Quantity'] = $Quantity;
                    $temp['Timestamp'] = $Timestamp;
                    $temp['ImgPath'] = GET_IMG_Path . $ImgPath;
                    array_push($results, $temp);
                }
                $response['error'] = false;
                $response['results'] = $results;

                Response($response);
            }

            switch ($src) {
                case "create":
                    $CategoryId  =  $_POST['CategoryId'];
                    $Title =  $_POST['Title'];
                    $Description =  $_POST['Description'];
                    $Price =  $_POST['Price'];
                    $Quantity =  $_POST['Quantity'];
                    $indexTitle = indexTitle($Title);

                    //uploading image 
                    $ImgName =  getFileName($TableName, 0, 'ImgPath');

                    $stmt = $connection->prepare("INSERT INTO $TableName (Title,Description,CategoryId,Price,Quantity,ImgPath,Indexing)
                        VALUES (?,?,?,?,?,?,?)");
                    $stmt->bind_param("sssssss", $Title, $Description, $CategoryId, $Price, $Quantity, $ImgName, $indexTitle);
                    if (getResponse($stmt)) {
                        if ($ImgName != null) {
                            uploadFile($ImgName, 'ImgPath');
                        }
                    };
                    break;

                case 'update':
                    $CategoryId  =  $_POST['CategoryId'];
                    $Title =  $_POST['Title'];
                    $Description =  $_POST['Description'];
                    $Price =  $_POST['Price'];
                    $Quantity =  $_POST['Quantity'];
                    $Id = $_POST['Id'];
                    $indexTitle = indexTitle($Title);

                    //uploading image 
                    $ImgName =  getFileName($TableName, $Id, 'ImgPath');

                    if ($ImgName != null) {
                        //remove old image
                        unlinkFile($TableName, $Id, 'ImgPath');
                    }

                    $stmt = $connection->prepare("UPDATE $TableName 
                        SET Title = ?,Description = ?,ImgPath = ?,Price = ?,Quantity = ?,Indexing = ?
                        WHERE Id = ?");
                    $stmt->bind_param("sssssss", $Title, $Description, $ImgName, $Price, $Quantity, $indexTitle, $Id);
                    if (getResponse($stmt)) {
                        if ($ImgName != null) {
                            uploadFile($ImgName, 'ImgPath');
                        }
                    };
                    break;

                case 'get':
                    $Id = $_GET['Id'];
                    $stmt = $connection->prepare("SELECT p.Id,pc.Id,pc.Title,p.Title,p.Description,p.Price,p.Quantity,p.ImgPath,p.Timestamp 
                        from $TableName p
                        INNER JOIN productcategory pc
                        ON pc.Id = p.CategoryId 
                        WHERE p.Id = ?
                        Order by p.Id DESC");
                    $stmt->bind_param("s", $Id);
                    GetProducts($stmt, $connection);
                    break;

                case 'getAll':
                    $stmt = $connection->prepare("SELECT p.Id,pc.Id,pc.Title,p.Title,p.Description,p.Price,p.Quantity,p.ImgPath,p.Timestamp 
                    from $TableName p
                    INNER JOIN productcategory pc
                    ON pc.Id = p.CategoryId  
                    Order by rand()");
                    GetProducts($stmt, $connection);
                    break;

                case 'search':
                    $search_string = indexTitle($_GET['term']);

                    $stmt = $connection->prepare("SELECT p.Id,pc.Id,pc.Title,p.Title,p.Description,p.Price,p.Quantity,p.ImgPath,p.Timestamp 
                            from $TableName p
                            INNER JOIN productcategory pc
                            ON pc.Id = p.CategoryId 
                            where p.indexing like '%$search_string%'
                            Order by p.Id DESC");
                    GetProducts($stmt, $connection);
                    break;

                case 'delete':
                    $Id = $_GET['Id'];
                    unlinkFile($TableName, $Id, 'ImgPath');
                    $stmt = $connection->prepare("DELETE from $TableName 
                        WHERE Id = ?");
                    $stmt->bind_param("s", $Id);
                    getResponse($stmt);
                    break;
            }
            break;

        case 'UserPurchases':

            $src = $_GET['src'];
            $TableName = 'userpurchases';

            function GetUserPurchases($stmt)
            {
                $Id  = null;
                $ProductId = null;
                $UserId = null;
                $Total = null;
                $Address = null;
                $Timestamp = null;
                $ProductName = null;
                $ImgPath = null;
                $CategoryName = null;

                $stmt->execute();
                $stmt->bind_result($Id, $ProductId, $UserId, $Total, $Address, $Timestamp, $ProductName, $ImgPath, $CategoryName);
                $results = array();
                while ($stmt->fetch()) {
                    $temp = array();
                    $temp['Id'] = $Id;
                    $temp['ProductId'] = $ProductId;
                    $temp['UserId'] = $UserId;
                    $temp['Total'] = $Total;
                    $temp['Address'] = $Address;
                    $temp['Timestamp'] = $Timestamp;
                    $temp['ProductName'] = $ProductName;
                    $temp['CategoryName'] = $CategoryName;
                    $temp['ImgPath'] = GET_IMG_Path . $ImgPath;
                    array_push($results, $temp);
                }
                $response['error'] = false;
                $response['results'] = $results;

                Response($response);
            }

            switch ($src) {
                case "create":
                    $UserId =  $_POST['UserId'];;
                    $Total =  $_POST['Total'];;
                    $Address =  $_POST['Address'];

                    $array_size = $_GET['array_size'];
                    $trans_id = $_POST['trans_id'];

                    for ($i = 0; $i < $array_size; $i++) {
                        $ProductId = $_POST["productId($i)"];

                        $stmt = $connection->prepare("INSERT INTO $TableName (ProductId,UserId,Total,Address)
                        VALUES (?,?,?,?)");
                        $stmt->bind_param("ssss", $ProductId, $UserId, $Total, $Address);
                        getResponse($stmt);
                    }

                    break;

                case 'get':
                    $Id = $_GET['Id'];
                    $stmt = $connection->prepare("SELECT up.*,p.Title as ProductName,p.ImgPath,c.Title as CategoryName
                        from $TableName up
                        INNER JOIN products p
                        On p.Id = up.ProductId 
                        INNER JOIN productcategory c
                        ON c.Id = p.CategoryId 
                        WHERE up.Id = ?");
                    $stmt->bind_param("s", $Id);
                    GetUserPurchases($stmt, $connection);

                    break;

                case 'getAll':
                    $stmt = $connection->prepare("SELECT up.*,p.Title as ProductName,p.ImgPath,c.Title as CategoryName
                        from $TableName up
                        INNER JOIN products p
                        On p.Id = up.ProductId 
                        INNER JOIN productcategory c
                        ON c.Id = p.CategoryId 
                        Order by Id DESC");
                    GetUserPurchases($stmt, $connection);
                    break;

                case 'delete':
                    $Id = $_GET['Id'];
                    unlinkFile($TableName, $Id, 'ImgPath');
                    $stmt = $connection->prepare("DELETE from $TableName 
                        WHERE Id = ?");
                    $stmt->bind_param("s", $Id);
                    getResponse($stmt);
                    break;
            }
            break;


        case 'sample':

            $src = $_GET['src'];
            $TableName = '';

            function GetFaculty($stmt)
            {
                $Name = null;
                $ImgPath = null;
                $Description = null;
                $Id = null;

                $stmt->execute();
                $stmt->bind_result($Id, $Name, $Description, $ImgPath);
                $results = array();
                while ($stmt->fetch()) {
                    $temp = array();
                    $temp['Id'] = $Id;
                    $temp['Name'] = $Name;
                    $temp['Description'] = $Description;
                    $temp['ImgPath'] = GET_IMG_Path . $ImgPath;
                    array_push($results, $temp);
                }
                $response['error'] = false;
                $response['results'] = $results;

                Response($response);
            }

            switch ($src) {
                case "create":
                    $Name = $_POST['Name'];
                    $Description = $_POST['Description'];

                    //uploading image 
                    $ImgName =  getFileName($TableName, 0, 'ImgPath');

                    $stmt = $connection->prepare("INSERT INTO $TableName (Name,Description,ImgPath)
                    VALUES (?,?,?)");
                    $stmt->bind_param("sss", $Name, $Description, $ImgName);
                    if (getResponse($stmt)) {
                        if ($ImgName != null) {
                            uploadFile($ImgName, 'ImgPath');
                        }
                    };
                    break;

                case 'update':
                    $Name = $_POST['Name'];
                    $Description = $_POST['Description'];
                    $Id = $_POST['Id'];

                    //uploading image 
                    $ImgName =  getFileName($TableName, $Id, 'ImgPath');

                    if ($ImgName != null) {
                        //remove old image
                        unlinkFile($TableName, $Id, 'ImgPath');
                    }

                    $stmt = $connection->prepare("UPDATE $TableName 
                    SET Name = ?,Description = ?,ImgPath = ?
                    WHERE Id = ?");
                    $stmt->bind_param("ssss", $Name, $Description, $ImgName, $Id);
                    if (getResponse($stmt)) {
                        if ($ImgName != null) {
                            uploadFile($ImgName, 'ImgPath');
                        }
                    };
                    break;

                case 'get':
                    $Id = $_GET['Id'];
                    $stmt = $connection->prepare("SELECT * from $TableName
                    WHERE Id = ?");
                    $stmt->bind_param("s", $Id);
                    GetFaculty($stmt, $connection);
                    break;

                case 'getAll':
                    $stmt = $connection->prepare("SELECT * from $TableName
                    Order by Id DESC");
                    GetFaculty($stmt, $connection);
                    break;

                case 'delete':
                    $Id = $_GET['Id'];
                    unlinkFile($TableName, $Id, 'ImgPath');
                    $stmt = $connection->prepare("DELETE from $TableName 
                    WHERE Id = ?");
                    $stmt->bind_param("s", $Id);
                    getResponse($stmt);
                    break;
            }
            break;
            //end

    }
} else {
    $response['error'] = true;
    $response['message'] = 'Invalid API Call';
}
//displaying the response in json structure
// echo json_encode($response, JSON_UNESCAPED_SLASHES);
