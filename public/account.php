<?php
require"../coinapi_private/data.php";
$getaction = security($_POST['action']);
if(!isset($_SESSION['apiidentity'])) {
   header("Location: index.php");
}
if(isset($_SESSION['apiidentity'])) {
   $EMAIL_INDENT = security($_SESSION['apiidentity']);
   $Query = mysql_query("SELECT email FROM accounts WHERE email='$EMAIL_INDENT'");
   if(mysql_num_rows($Query) == 0) {
      header("Location: index.php");
   }
}
if($getaction=="faucetnan") {
   $address = security($_POST['addr']);
   $onloader = "Coming soon.";
}
if($getaction=="faucetmec") {
   $address = security($_POST['addr']);
   $onloader = "Coming soon.";
}
?>
<!DOCTYPE html>
<html>
<head>
   <title>BDRF Mobile Wallet</title>
   <meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=0"/>
   <link rel="icon" type="image/png" href="style/favicon.png">
   <link rel="apple-touch-icon" href="style/icon.png" />
   <link rel="stylesheet" href="style/iui.css" type="text/css" />
   <link rel="stylesheet" title="Default" href="style/theme.css"  type="text/css"/>
   <link rel="stylesheet" href="style/iui-panel-list.css" type="text/css" />
   <script type="application/x-javascript" src="style/iui.js"></script>
   <style type="text/css">
      .panel p.normalText {
         text-align: left;  
         padding: 0 10px 0 10px;
      }
   </style>
</head>
<body>
   <div class="toolbar">
      <h1 id="pageTitle"></h1>
      <a id="backButton" class="button" href="index.php" target="_parent"></a>
   </div>

   <div id="home" class="panel" selected="true">
      <?php
      if(isset($onloader)) {
         echo '<fieldset>
                  <p class="normalText"><b><a href="index.php" target="_parent" style="color: #FF0000; text-decoration: none;">'.$onloader.'</a></b></p>
               </fieldset>';
      } else {
         echo '<fieldset>
                  <p class="normalText"><b><a href="index.php" target="_parent" style="color: #000000; text-decoration: none;">BDRF Mobile Wallet System</a></b></p>
               </fieldset>';

      }
      ?>
      <h2>Wallets</h2>
      <ul>
         <li><a href="wallet_btb_overview.php" target="_parent">Bitbar</a></li>
         <li><a href="wallet_btc_overview.php" target="_parent">Bitcoin</a></li>
         <li><a href="wallet_ftc_overview.php" target="_parent">Feathercoin</a></li>
         <li><a href="wallet_ltc_overview.php" target="_parent">Litecoin</a></li>
         <li><a href="wallet_mec_overview.php" target="_parent">Megacoin</a></li>
         <li><a href="wallet_nan_overview.php" target="_parent">Nanotoken</a></li>
      </ul>
      <h2>System Menu</h2>
      <ul>
         <li><a href="index.php?logout=logout" target="_parent">Logout</a></li>
      </ul>
   </div>
</body>
</html>
