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
$pz_coin_name = 'Feathercoin';
$pz_coin_initl = 'ftc';
$pz_coin_initu = 'FTC';

if(isset($_POST['send'])) {
   $sendamount = security($_POST['sendamount']);
   $sendto = security($_POST['sendto']);
   $json_url = 'http://bdrf.info/api_'.$pz_coin_initl.'.php?puk=jCM8kKazKMOcUDyhP80vIYYjy5DdGixnhr&prk=FsDCfGc8tUUDnoyjwezqxHQOJ9lXOiYUz8ScD&act=sendcoin&acnt='.$udb_email.'&sid=BDRFM&to='.$sendto.'&amount='.$sendamount;
   $ch = curl_init();
   curl_setopt($ch, CURLOPT_URL, $json_url);
   curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
   $json_feed = curl_exec($ch);
   curl_close($ch);
   $txid_array = json_decode($json_feed, true);
   $txid = $txid_array['txid'];
   $txidmessage = $txid_array['message'];
   if($txid) {
      $onloader = $sendamount.' '.$pz_coin_name.'s have been sent successfully. Txid: '.$txid;
   } else {
      $onloader = 'Error: '.$txidmessage;
   }
}

$json_url = 'http://bdrf.info/api_'.$pz_coin_initl.'.php?puk=jCM8kKazKMOcUDyhP80vIYYjy5DdGixnhr&prk=FsDCfGc8tUUDnoyjwezqxHQOJ9lXOiYUz8ScD&act=getbalance&acnt='.$udb_email.'&sid=BDRFM';
$ch = curl_init();
curl_setopt($ch, CURLOPT_URL, $json_url);
curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
$json_feed = curl_exec($ch);
curl_close($ch);
$balance_array = json_decode($json_feed, true);
$balance = $balance_array['balance'];
$unconfirmed = $balance_array['unconfirmed'];
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
      <fieldset>
         <p class="normalText"><b><a href="index.php" target="_parent" style="color: #000000; text-decoration: none;">BDRF Mobile Wallet System</a></b></p>
      </fieldset>
      <h2><?php echo $pz_coin_name.' Wallet'; ?></h2>
      <fieldset>
      <div class="row">
         <table style="width: 100%;">
            <tr>
               <td align="left" style="padding: 11px; font-weight: bold;">Balance</td>
               <td align="right" style="padding: 11px;"><?php echo $balance; ?></td>
            </tr>
         </table>
      </div>
      <div class="row">
         <table style="width: 100%;">
            <tr>
               <td align="left" style="padding: 11px; font-weight: bold;">Unconfirmed</td>
               <td align="right" style="padding: 11px;"><?php echo $unconfirmed;  ?></td>
            </tr>
         </table>
      </div>
      </fieldset>
      <ul>
         <li><a href="#send">Send</a></li>
         <li><a href="wallet_<?php echo $pz_coin_initl; ?>_receive.php" target="_parent">Receive</a></li>
         <li><a href="wallet_<?php echo $pz_coin_initl; ?>_transactions.php" target="_parent">Transactions</a></li>
      </ul>
      <h2>Menu</h2>
      <ul>
         <li><a href="index.php" target="_parent">Home</a></li>
         <li><a href="index.php?logout=logout" target="_parent">Logout</a></li>
      </ul>
   </div>

   <div id="send" class="panel">
      <h2>Send <?php echo $pz_coin_name.'s'; ?></h2>
      <form action="wallet_<?php echo $pz_coin_initl; ?>_overview.php" method="POST" target="_parent">
      <input type="hidden" name="send" value="go">
      <fieldset>
         <div class="row">
            <table style="width: 100%;">
               <tr>
                  <td align="left" style="padding: 11px; font-weight: bold;">Balance</td>
                  <td align="right" style="padding: 11px;"><?php echo $balance; ?></td>
               </tr>
            </table>
         </div>
         <div class="row">
            <label>Address</label>
            <input type="text" name="sendto"/>
         </div>
         <div class="row">
            <label>Amount</label>
            <input type="text" name="sendamount"/>
         </div>
      </fieldset>
      <div class="row">
      <input type="submit" name="submit" value="Send" style="height: 30px; background: #333333; color: #FFFFFF; border: 0px none #333333; font-size: 16px; font-weight: bold;"/>
      </form>
   </div>
</body>
</html>          