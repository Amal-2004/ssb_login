<?php
//include "D:/xamp loc/htdocs/Ssbaide/ssbaide_users/ssb_login/ssb_login.php";

include('connection.php');

function base64UrlEncode($data)
{
    $urlSafeData = strtr(base64_encode($data), '+/', '-_');
    return rtrim($urlSafeData, '=');
}

function createJWT($payload, $key)
{
    $header = [
        'typ' => 'JWT',
        'alg' => 'HS256'
    ];

    $headerEncoded = base64UrlEncode(json_encode($header));
    $payloadEncoded = base64UrlEncode(json_encode($payload));

    $signature = hash_hmac('sha256', "$headerEncoded.$payloadEncoded", $key, true);
    $signatureEncoded = base64UrlEncode($signature);

    return "$headerEncoded.$payloadEncoded.$signatureEncoded";
}

function verifyJWT($token, $key)
{
    $token_parts = explode('.', $token);

    if (count($token_parts) !== 3) {
        return false;
    }

    $header = json_decode(base64_decode(strtr($token_parts[0], '-_', '+/')), true);
    $payload = json_decode(base64_decode(strtr($token_parts[1], '-_', '+/')), true);
    $signature = base64UrlEncode(hash_hmac('sha256', "$token_parts[0].$token_parts[1]", $key, true));

    if ($signature !== $token_parts[2]) {
        return false;
    }

    if (isset($payload['expire']) && $payload['expire'] < time()) {
        return false;
    }

    return $payload;
}

$key = "passkey"; 

if (isset($_POST['email']) && isset($_POST['password'])) {
    $email = $_POST['email'];
    $password = md5($_POST['password']);

    $query = "SELECT * FROM ssbaide_users WHERE Email_ID = '$email' AND Password = '$password'";
    $result = $con->query($query);


    if ($result->num_rows > 0) {
        $user = $result->fetch_assoc();
    
        // Fetch schedule data for the user
        $scheduleQuery = "SELECT Class_ID, Day_Order, Sub_Name, Hr, Staff_ID, SUB_Code FROM ssb_schedule WHERE Staff_ID = '{$user['Staff_ID']}'";
        $scheduleResult = $con->query($scheduleQuery);
    
        $scheduleData = [];
        if ($scheduleResult->num_rows > 0) {
            while ($row = $scheduleResult->fetch_assoc()) {
                $scheduleData[] = $row;
            }
        }
    
        $payload = [
            'S_NO' => $user['S_NO'],
            'Staff_ID' => $user['Staff_ID'],
            'Staff_Name' => $user['Staff_Name'],
            'Department' => $user['Department'],
            'email' => $user['Email_ID'],
            'Password' => $user['Password'],
            'fullname' => $user['Fullname'],
            'designation' => $user['Designation'],
            'expire' => time() + 3660,
            'Class_ID' => $user['Class_ID'],
            'schedule' => $scheduleData, // Include schedule data in the payload
        ];
    
        $jwt = createJWT($payload, $key);
    
        setcookie('session_id', $jwt, time() + 3660, '/');
    
        header('Location: ClassRoom/myClass.php');
        exit;
    } else {
        //echo "Login failed. Please check your email and password.";
    }
} else {
    //echo "Please enter both email and password.";
}

$key = "passkey";

if (isset($_COOKIE['session_id'])) {
    $decoded_payload = verifyJWT($_COOKIE['session_id'], $key);

    if ($decoded_payload) {
        // Session is valid, you can use $decoded_payload
        $staff_id = $decoded_payload['Staff_ID'];

        $selectQuery = "SELECT Class_ID, Day_Order, Sub_Name, Hr, Staff_ID, SUB_Code FROM ssb_schedule WHERE Staff_ID = '$staff_id'";

        $result = $con->query($selectQuery);

        $scheduleData = [];
        if ($result->num_rows > 0) {
            while ($row = $result->fetch_assoc()) {
                $row['ClassName'] = getClassFromID($row['Class_ID']);
                $scheduleData[] = $row;
            }
        }
    } else {
        // Invalid session, redirect to login
        header('Location:../ssb_login/ssb_login.php');
        exit;
    }
} else {
    // Cookie not set, redirect to login
    header('Location:../ssb_login/ssb_login.php');
    exit;
}
function getClassFromID($classID) {
  switch ($classID) {
    case 1:
        return '1 BCA';
    case 2:
        return '2 BCA';
    case 3:
        return '3 BCA';
    case 4:
        return '1 BSCIT';
    case 5:
        return '2 BSCIT';
    case 6:
        return '3 BSCIT';
    case 7:
        return '1 BCOM';
    case 8:
        return '2 BCOM 1';
    case 9:
        return '2 BCOM 2';
    case 10:
        return '3 BCOM 1';
    case 11:
        return '2 BCOM 2';
    case 12:
        return '1 BA.ENG';
    case 13:
        return '2 BA.ENG';
    case 14:
        return '3 BA.ENG';
    case 15:
        return '1 BBA';
    case 16:
        return '2 BBA';
    case 17:
        return '3 BBA';
    case 18:
        return '2 Maths';
    default:
        return 'Unknown';
}

}

?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <title>ClassRoom</title>
    <link rel="stylesheet" href="myClass.css">
</head>
<body>
  <nav class="navbar bg-primary" >
    <div class="container-fluid" id="nav">
      <a class="navbar-brand" id="name" href="#">SSB Class Room</a>
    </div>
  </nav>
  <div id="containers">
  <div class="container-fluid" id="box">
      <p id="title">My Classes</p>    
          <?php if (!empty($scheduleData)): ?>
                <?php foreach ($scheduleData as $row): ?>
                  <div class="card"  id="classCard">
                    <div class="card-body" id="classBody">
                       <div id="Header"> <h4 id="className" ><?php echo $row['ClassName']; ?></h4>
                            <p id="subjectCode"><?php echo $row['SUB_Code']; ?></p></div>
                               <div id="buttons"> 
                               <?php
                              
                                $classID = $row['Class_ID'];
                                echo "<a class='btn btn-primary' id='view'  href=\"model.php?class_id=$classID\">View</a>";
                                ?>  <button id="hour"type="button" class="btn">
                                Hour <span class="badge badge-light"><?php echo $row['Hr']?></span>
                            </button></div>
                    </div>
                </div>
                <?php endforeach; ?>
    </div>
            <?php else: ?>
                <p>No schedule available for this staff.</p>
            <?php endif; ?>
  
<!-- 
            <div class="container-fluid"  id="departmentBox">
        <p id="myDep">My Department</p>
            <div class="card" id="departmentCard">
                <div class="card-body" id="departmentCardBody">
                    <div class="className">   <h4 >BCA-1</h4></div>
                    <div class="icons">
                        <div class="icon-1">
                            <img src="male.png" alt="Male" id="maleIcon">
                            <div id="maleCount">
                                100
                            </div>
                        </div>
                        <div class="icon-2">
                            <img src="female.png" alt="Female" id="femaleIcon">
                            <div id="femaleCount">
                                100
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="card" id="departmentCard">
                <div class="card-body" id="departmentCardBody">
                    <div class="className">   <h4 >BCA-1</h4></div>
                    <div class="icons">
                        <div class="icon-1">
                            <img src="male.png" alt="Male" id="maleIcon">
                            <div id="maleCount">
                                100
                            </div>
                        </div>
                        <div class="icon-2">
                            <img src="female.png" alt="Female" id="femaleIcon">
                            <div id="femaleCount">
                                100
                            </div>
                        </div>
                    </div>
                </div>
            </div>
    </div>
</div>         
  

 -->         <!--      
<div class="card" id="classCard">
        <div class="card-body">
          <h4 id="header">IT-1</h4>
          <div class="figure">
          <p id="subject">HTML</p>  
          <div class="activeIcon " id="icon"></div>
          </div>
        </div>
      </div>
           
       <script src="myClass.js"></script> -->
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>