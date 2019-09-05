<?php
//ini_set('display_errors', 1);
//ini_set('display_startup_errors', 1);

//error_reporting(E_ALL);
global $curlResult;
global $curlResult2;
global $json;
global $json0;
global $json1;
global $json2;
global $json3;
global $userInfoResult;
global $userInfojson;
global $reqResStatus;
global $fullUIInfo;
require_once 'config.php';

?>

<!DOCTYPE html>
<html>
<script>
//Convert hashed fragment to PHP GETable query string in return URI from Gigya
// id_token flow would normally be used with mobile devices, and would require the fragment
// for security reasons
if(window.location.hash !=="") {
        torip=window.location.href;
        torip=torip.replace('#', '?');
        window.location.href=torip;
    }
var curlResult = {};
</script>
    <head>
        <title>Gigya RP Demo Site</title>
        <link rel="shortcut icon" href="favicon.ico?v=2" type="image/x-icon"/>
        <script src="//ajax.googleapis.com/ajax/libs/jquery/1.10.2/jquery.min.js"></script>
        <script src="//ajax.googleapis.com/ajax/libs/jqueryui/1.9.2/jquery-ui.min.js"></script>
        <script type="text/javascript" lang="javascript" src="https://cdns.gigya.com/JS/gigya.js?apiKey=<?php echo $apiKey?>"></script>

        <script src="/scripts/beautify.js"></script>
        <link href="/css/maincss.css" rel="stylesheet" type="text/css"/>
        <style type='text/css'>
        /* ** --snipped-- ** */   
        </style>
    </head>
    <?php
        $errors='';
        $jsonSize=0;
        global $validUser;
        if (isset($_GET['id_token']) && $_GET['id_token'] !== "") {
            $id_token = $_GET['id_token'];
        } else {
            $id_token = 'undefined';
            $errors .='id_token was undefined.<br />';
        }
        if (isset($_GET['code']) && $_GET['code'] !== "") {
            $code = $_GET['code'];
        } else {
            $code = 'undefined';
            $errors .='code was undefined.<br />';
        }
        // Do a simple check to determine if the user arrived directly to the page (via copy/paste)
        // Does not actually determine if user is authorized
        // A production environment would require additonal logic after validating the code/token is authentic
        if (($id_token !== 'undefined') && ($code ==='undefined')) {
            $oidcResponseType = "id_token";
            $validUser="true";
        } else if (($id_token === 'undefined') && ($code !=='undefined')) {
            $oidcResponseType = "code";
            $validUser="true";
        } else {
            $oidcResponseType = "<span style=\"color: #e76468;\">No authorized user detected.</span>";
            $validUser="false";
        }
    ?>
    <body>
        <div class="" id="main_wrapper">
        <div class="" id="logoDiv">
            <a href="<?php echo $urlPrefix . '/ RPLogin.php'?>">
                <img src="images/GigyaLogo_2016-05-04.png" />
            </a>
        </div>
            <input type="hidden" id="validUser" value='<?php echo $validUser; ?>' />
        <h2>Gigya OIDC Demo RP Site</h2>
        <h3 id="loginSuccessful">Login Successful</h3><h3 id="invalidLogin">Error</h3>
        <!-- When the flow is detected as being id_token -->
        <h4>Response Type: <?php echo $oidcResponseType ?></h4>
        <div class="" id="logoutDiv">
            <button class="rpBtn" id="logoutBtn" />Logout</button>
        </div>
        
        <script>
            // This section has the same caveat as the validUser param above
            validUser=document.getElementById('validUser').value;
            if ((validUser == "true")) {
                document.getElementById('loginSuccessful').style.display='block';
                document.getElementById('invalidLogin').style.display='none';
                document.getElementById('logoutDiv').style.display='block';
            } else {
                document.getElementById('loginSuccessful').style.display='none';
                document.getElementById('invalidLogin').style.display='block';
                document.getElementById('logoutDiv').style.display='none';
            }
        </script>

        <div class="" id="idTokenDiv">
            Complete JWT:<br />
            <textarea id="successJWT" rows="8" style="width: 100%" readonly></textarea><br />
            Part One:<br />
            <textarea id="qrystrJWT1" rows="2" style="width: 100%" readonly></textarea><br />
            Part One Decoded:<br />
            <textarea id="qrystrJWT1b" rows="2" style="width: 100%" readonly></textarea><br />
            Part Two:<br />
            <textarea id="qrystrJWT2" rows="5" style="width: 100%" readonly></textarea><br />
            Part Two Decoded:<br />
            <textarea id="qrystrJWT2b" rows="6" style="width: 100%" readonly></textarea><br />
            Part Three (signature):<br />
            <textarea id="qrystrJWT3" rows="3" style="width: 100%" readonly></textarea><br />
             
        </div>
 
        <!-- When the flow is detected as being code -->
        <div class="" id="codeFlowDiv">
            This flow would normally be handled entirely on the server. These values are only echoed here for reference.<br /><br />
            Code received from authorize endpoint (send this to the token endpoint):<br />
            <textarea id="codeFlowCode" rows="2" style="width: 100%" readonly></textarea><br />
            Access token (received from token endpoint; response[0]):<br />
            <textarea id="qrystrJWT4" rows="2" style="width: 100%" readonly></textarea><br />
            Decoded id_token data (received from token endpoint; response[3]):<br />
            <textarea id="qrystrJWT5" rows="12" style="width: 100%" readonly></textarea><br />
            Decoded userinfo response (returned from userinfo endpoint in exchange for access_token (above)):<br />
            <textarea class="" id="userInfoResponse" rows="12" style="width: 100%" readonly></textarea><br />
             
        </div>
 
        <div class="" id="serverRequestResponseDiv">
        </div>
        <script>
        $(document).ready(function() {
            // hide logout button until we know we're logged in
            document.getElementById('logoutDiv').style.display='none';
        })

        var oidcResponseType = '<?php echo $oidcResponseType ?>';
        var clientSecret="";
        var qrystrJWT;
        var splitJWT = {};
        var qrystrJWT1;
        var qrystrJWT2;
        var qrystrJWT3;
        var qrystrJWT1b;
        var qrystrJWT2b;
        var qrystrJWT5;

        // If id_token flow
        if (oidcResponseType === "id_token") {
            document.getElementById('idTokenDiv').style.display='block';
            document.getElementById('codeFlowDiv').style.display='none';
            $(document).ready(function() {

                qrystrJWT = '<?php echo $id_token ?>';
                splitJWT = qrystrJWT.split('.');
                document.getElementById('successJWT').value = qrystrJWT;
                if (splitJWT.length === 3) {
                    qrystrJWT1 = splitJWT[0];
                    qrystrJWT2 = splitJWT[1];
                    qrystrJWT3 = splitJWT[2];
                    document.getElementById('qrystrJWT1').value=qrystrJWT1;
                    document.getElementById('qrystrJWT2').value=qrystrJWT2;
                    document.getElementById('qrystrJWT3').value=qrystrJWT3;
                    document.getElementById('qrystrJWT1b').value=url_base64_decode(qrystrJWT1);
                    document.getElementById('qrystrJWT2b').value=url_base64_decode(qrystrJWT2);
                    document.getElementById('logoutDiv').style.display='block';

                } else {
                    console.warn('The query string does not contain a valid JWT.');
                }
                 
                 
            });
        // Else if code flow   
        } else if (oidcResponseType === "code") {
            document.getElementById('idTokenDiv').style.display='none';
            document.getElementById('codeFlowDiv').style.display='block';
            $(document).ready(function() {
                qrystrAuthCode = '<?php echo $code ?>';
                splitJWT = qrystrAuthCode.split('.');
                document.getElementById('codeFlowCode').value=qrystrAuthCode;
                document.getElementById('logoutDiv').style.display='block';
            });
        }
         
        </script>
<?php
// The rest of the code below pertains almost entirely to the code flow
 
/* startCodeFlow CODE from the query string and sends it back to the token endpoint
   in order to receive an access_token
*********************************************************************************** */
function startCodeFlow() {
    require 'config.php';
    $url = $tokenEndpoint;
    $authHeader="Basic ";
    $hashedAuthString=base64_encode($clientID . ":" . $clientSecret);
    $authHeader .= $hashedAuthString;
    // echo $authHeader;  //EXPOSING THIS EXPOSES THE CLIENT SECRET TO THE CLIENT!!!
    $fields = array(
        'code'=>$_GET['code'],
        'grant_type'=>'authorization_code',
        'redirect_uri'=> $redirectURI
    );
    $postvars='';
    $sep='';
    foreach($fields as $key=>$value)
    {
        $postvars.= $sep.urlencode($key).'='.urlencode($value);
        $sep='&';
    }
    echo '<br /><br />Request string:<br />' . $postvars;
    $ch = curl_init();
 
    curl_setopt($ch,CURLOPT_URL,$url);
    curl_setopt($ch,CURLOPT_HTTPHEADER, array('Authorization: ' . $authHeader));
    curl_setopt($ch,CURLOPT_POST,count($fields));
    curl_setopt($ch,CURLOPT_POSTFIELDS,$postvars);
    curl_setopt($ch,CURLOPT_RETURNTRANSFER,true);
    $result = curl_exec($ch);
    curl_close($ch);
    global $curlResult;
    $curlResult = json_encode($result);

    $result2 = $result;
    global $json;
    $json = json_decode($result2, true);
    echo '<br /><br />Token Response:';
    var_dump($json); 
    echo '<br />';
}
 
/* THIS NOW RETURNS THE ACCESS TOKEN TO THE USER_INFO ENDPOINT TO GET THE USER'S INFO
*********************************************************************************** */
 
function getUserInfoEndpointData($accessToken) {
    include 'config.php';
    echo $accessToken;
    $url = $userinfoEndpoint;
    $bearAccessToken="Bearer " . $accessToken;
    $ch = curl_init();
    curl_setopt($ch,CURLOPT_URL,$url);
    curl_setopt($ch, CURLOPT_HTTPHEADER, array('Authorization: ' . $bearAccessToken));
    curl_setopt($ch,CURLOPT_RETURNTRANSFER,true);
 
    $resultUI = curl_exec($ch);
 
    // CHECK STATUS TEST
    $resultStatus = curl_getinfo($ch, CURLINFO_HTTP_CODE);
    global $reqResStatus;
    $reqResStatus = $resultStatus;
    //Echo errors to the page - for testing purposes
    if ($resultStatus == 200) {
        echo '<br />statusCode: 200<br />' . $resultStatus . '<br />';
    } else {
        echo '<br />Request failed: HTTP status code: ' . $resultStatus . '<br />';
        //die('Request failed: HTTP status code: ' . $resultStatus);
    }

    // END CHECK STATUS
    curl_close($ch);
    global $userInfoResult;
    $userInfoResult = json_encode($resultUI);
    $result3 = $resultUI;
    global $userInfojson;
    $userInfojson = json_decode($result3, true); // returns array()
    global $fullUIInfo;
    $fullUIInfo = $userInfojson;
}
 
/* END OF USER_INFO ENDPOINT CODE
*********************************************************************************** */
 
/* Now we echo everything out to the client for demo purposes, in production
   the RP would now add/update their database with the user's info and proceed to
   deliver their content.
*********************************************************************************** */
if($oidcResponseType=='code' && $code !=='undefined') {
    startCodeFlow();
    $jsonSize=count($json);

    if ($jsonSize>=4) {
        global $json0;
        global $json1;
        global $json2;
        global $json3;
         
        $json0 = $json['access_token'];
        $json1 = $json['token_type'];
        $json2 = $json['expires_in'];
        $json3 = $json['id_token'];
        getUserInfoEndpointData($json0);
    }
}

 
?>
<script>
 
// Necessary to decode the URL encoded base64 data received from Gigya
function url_base64_decode(str) {
  var output = str.replace(/-/g, '+').replace(/_/g, '/');
  switch (output.length % 4) {
    case 0:
      break;
    case 2:
      output += '==';
      break;
    case 3:
      output += '=';
      break;
    default:
      throw 'Invalid base64!';
  }
  var result = window.atob(output);
  try{
    return decodeURIComponent(escape(result));
  } catch (err) {
    return result;
  }
}
 
var json0;
var json1;
var json2;
var json3;
var jsonFull;
var jsonIdTokenData;
var codeFlowCodeStr;
if (oidcResponseType === "code") {
    var jsonSize = '<?php echo $jsonSize ?>';
    if (jsonSize>=4) {
        codeFlowCodeStr = '<?php echo $code ?>';
        json0 = '<?php echo $json0 ?>';
        json1 = '<?php echo $json1 ?>';
        json2 = '<?php echo $json2 ?>';
        json3 = '<?php echo $json3 ?>';
        jsonFull = json0 + json1 + json2 + json3;
        jsonIdTokenData = json3.split('.');
        document.getElementById('qrystrJWT4').value=json0;
        document.getElementById('qrystrJWT5').value=js_beautify(url_base64_decode(jsonIdTokenData[1]));
        var reqResStatus = '<?php echo $reqResStatus ?>';
        document.getElementById('serverRequestResponseDiv').innerHTML = reqResStatus;
        if ((reqResStatus !== "") && (reqResStatus !== "200")) {
            document.getElementById('serverRequestResponseDiv').style.display='block';
        } else {
            document.getElementById('serverRequestResponseDiv').style.display='none';
        }
        var fullUIInfo;
        if (reqResStatus === "200") {
            fullUIInfo = '<?php echo json_encode($fullUIInfo); ?>';
            UIjson= JSON.stringify(JSON.parse(fullUIInfo));
            document.getElementById('userInfoResponse').value = js_beautify(UIjson);
        }

    } else {
        codeFlowCodeStr = 'Your request timed out and the token is no longer valid. Please start again.';
    }
    document.getElementById('codeFlowCode').value=codeFlowCodeStr;
}

function printResponse(response) {    
    if ( response.errorCode == 0 ) {               	 
        alert('User has logged out');  
    }  
    else {  
        alert('Error :' + response.errorMessage);  
    }  
} 
$(document).on("click", "#logoutBtn", function() {
    gigya.accounts.logout({callback:printResponse});
    document.getElementById('logoutDiv').style.display='none';
})
 
</script>
    <br /><br /><a href="http://developers.gigya.com/display/GD/OpenID+Provider+Setup#OpenIDProviderSetup-LiveDemo" target="_blank" class="gigyaLink">Documentation and Code</a><br /><br />
    </div><!-- /main_wrapper -->
</body>
</html>