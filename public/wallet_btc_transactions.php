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
$pz_coin_name = 'Bitcoin';
$pz_coin_initl = 'btc';
$pz_coin_initu = 'BTC';

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
      <ul>
         <li><a href="wallet_<?php echo $pz_coin_initl; ?>_overview.php" target="_parent">Overview</a></li>
      </ul>
      <h2>Transactions</h2>
      <fieldset>
      <div class="row">
         <table style="width: 100%;">
            <tr>
               <td align="left" style="padding: 11px; font-weight: bold;">Confirmations</td>
               <td align="right" style="padding: 11px; font-weight: bold;">Amount</td>
            </tr>
         </table>
      </div>
            <?php
            $bgcol = "1";
            $useclass = 'txtdb';
            $json_url = 'http://bdrf.info/api_'.$pz_coin_initl.'.php?puk=jCM8kKazKMOcUDyhP80vIYYjy5DdGixnhr&prk=FsDCfGc8tUUDnoyjwezqxHQOJ9lXOiYUz8ScD&act=listtransactions&acnt='.$udb_email.'&sid=BDRFM';
            $ch = curl_init();
            curl_setopt($ch, CURLOPT_URL, $json_url);
            curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
            $json_feed = curl_exec($ch);
            curl_close($ch);
            $tx_array = json_decode($json_feed, true);
            $transactions = $tx_array['transactions'];
            function invenDescSort($item1,$item2) {
               if ($item1['time'] == $item2['time']) return 0;
               return ($item1['time'] < $item2['time']) ? 1 : -1;
            }
            usort($transactions,'invenDescSort');
            foreach($transactions as $key => $value) {
               $dtx_confirmations = $transactions[$key]['confirmations'];
               $dtx_address = $transactions[$key]['address'];
               $dtx_amount = $transactions[$key]['amount'];
               $dtx_timestamp = $transactions[$key]['time'];
               if($dtx_timestamp!="") {
                  $dtx_time = date("n/j/Y G:i",$dtx_timestamp);
                  if($dtx_confirmations>"6") { $dtx_confirmations = "&#8730;"; }
                  echo '<div class="row">
                        <table onclick="alert(\'Address: '.$dtx_address.'\');" style="width: 100%;">
                           <tr>
                              <td align="left" style="padding: 11px;">'.$dtx_confirmations.'</td>
                              <td align="right" style="padding: 11px;">'.$dtx_amount.'</td>
                           </tr>
                        </table>
                        </div>';
               }
            }
            ?>
      </fieldset>
      <h2>Menu</h2>
      <ul>
         <li><a href="index.php" target="_parent">Home</a></li>
         <li><a href="index.php?logout=logout" target="_parent">Logout</a></li>
      </ul>
   </div>
</body>
</html>