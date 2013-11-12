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
$pz_coin_name = 'Megacoin';
$pz_coin_initl = 'mec';
$pz_coin_initu = 'MEC';

if(isset($_POST['newaddress'])) {
   $json_url = 'http://bdrf.info/api_'.$pz_coin_initl.'.php?puk=jCM8kKazKMOcUDyhP80vIYYjy5DdGixnhr&prk=FsDCfGc8tUUDnoyjwezqxHQOJ9lXOiYUz8ScD&act=getnewaddress&acnt='.$udb_email.'&sid=BDRFM';
   $ch = curl_init();
   curl_setopt($ch, CURLOPT_URL, $json_url);
   curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
   $json_feed = curl_exec($ch);
   curl_close($ch);
   $address_array = json_decode($json_feed, true);
   $address = $address_array['address'];
   $onloader = $address;
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
      <h2><?php echo $pz_coin_name.' Wallet'; ?></h2>
      <ul>
         <li><a href="wallet_<?php echo $pz_coin_initl; ?>_overview.php" target="_parent">Overview</a></li>
      </ul>
      <h2>Receiveing Addresses</h2>
      <fieldset>
            <?php
               $json_url = 'http://bdrf.info/api_'.$pz_coin_initl.'.php?puk=jCM8kKazKMOcUDyhP80vIYYjy5DdGixnhr&prk=FsDCfGc8tUUDnoyjwezqxHQOJ9lXOiYUz8ScD&act=getaccountaddresses&acnt='.$udb_email.'&sid=BDRFM';
               $ch = curl_init();
               curl_setopt($ch, CURLOPT_URL, $json_url);
               curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
               $json_feed = curl_exec($ch);
               curl_close($ch);
               $addressbook_array = json_decode($json_feed, true);
               $addresses = $addressbook_array['addresses'];
               if($addresses=="") {
                  $json_url = 'http://bdrf.info/api_'.$pz_coin_initl.'.php?puk=jCM8kKazKMOcUDyhP80vIYYjy5DdGixnhr&prk=FsDCfGc8tUUDnoyjwezqxHQOJ9lXOiYUz8ScD&act=getnewaddress&acnt='.$udb_email.'&sid=BDRFM';
                  $ch = curl_init();
                  curl_setopt($ch, CURLOPT_URL, $json_url);
                  curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
                  $json_feed = curl_exec($ch);
                  $addressbook_array = json_decode($json_feed, true);
                  $address = $addressbook_array['address'];
                  curl_close($ch);
                  echo '<div class="row">
                        <table style="width: 100%;">
                           <tr>
                              <td align="left" style="padding: 11px;">'.$address.'</td>
                           </tr>
                        </table>
                        </div>';
               } else {
                  foreach($addresses as $address) {
                  echo '<div class="row">
                        <table style="width: 100%;">
                           <tr>
                              <td align="left" style="padding: 11px;">'.$address.'</td>
                           </tr>
                        </table>
                        </div>';
                  }
               }
            ?>
      </fieldset>
      <div class="row">
      <form action="wallet_<?php echo $pz_coin_initl; ?>_receive.php" method="POST" target="_parent">
      <input type="hidden" name="newaddress" value="go">
      <input type="submit" name="buttonnewaddress" value="Create New Address" style="height: 30px; background: #333333; color: #FFFFFF; border: 0px none #333333; font-size: 16px; font-weight: bold;">
      </form>
      </div>
      <h2>Menu</h2>
      <ul>
         <li><a href="index.php" target="_parent">Home</a></li>
         <li><a href="index.php?logout=logout" target="_parent">Logout</a></li>
      </ul>
   </div>
</body>
</html>