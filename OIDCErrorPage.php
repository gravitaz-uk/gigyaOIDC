<?php
    require_once 'config.php';
?>
<html>
<head>
<title>OIDC Error Page | demo.gigya.com</title>
<style>
        /* ** --snipped-- ** */
</style>
</head>
<body>
    <div class="" id="logoDiv">
        <img src="images/GigyaLogo_2016-05-04.png" />
    </div>
    <h2>Gigya OIDC OP Error Page</h2>
<div class="responseDiv" id="responseDiv" style="width: 95%; height: 50%; overflow: auto; text-align: left; margin: 0px auto;">
</div>
<script>
    thisUri = window.location.href;
    thisUriToDelete= window.location.protocol + "//" + window.location.hostname + "/oidc/OIDCErrorPage.php?";
    decodedUri = decodeURIComponent(thisUri);
    decodedUri = decodedUri.replace(thisUriToDelete, "");
    decodedUri = decodedUri.replace(/,/g, ",<br />    ");
    decodedUri = decodedUri.replace(/{/g, "{<br />    ");
    decodedUri = decodedUri.replace(/}/g, "<br />}");
    decodedUri = decodedUri.replace(/&/g, "<br />");
    document.getElementById('responseDiv').innerHTML = decodedUri;
</script>
</body>
</html>