<?php
require"../coinapi_private/data.php";
$getaction = security($_POST['action']);
$logout = security($_GET['logout']);
if($logout=="logout") {
   session_destroy();
   setcookie("identa", '', time()-1000);
   setcookie("identa", '', time()-1000, '/');
   setcookie("identb", '', time()-1000);
   setcookie("identb", '', time()-1000, '/');
   header("Location: index.php");
}
if(isset($_SESSION['apiidentity'])) {
   $EMAIL_INDENT = security($_SESSION['apiidentity']);
   $Query = mysql_query("SELECT email FROM accounts WHERE email='$EMAIL_INDENT'");
   if(mysql_num_rows($Query) != 0) {
      header("Location: account.php");
   }
}
if($getaction=="login") {
   $login_email = security($_POST['email']);
   $login_password = security($_POST['password']);
   if($login_email) {
      if($login_password) {
         $login_password = substr($login_password, 0, 30);
         $login_password = md5($login_password);
         $Query = mysql_query("SELECT email FROM accounts WHERE email='$login_email'");
         if(mysql_num_rows($Query) == 1) {
            $Query = mysql_query("SELECT email, password, status, banned, pub_key, priv_key FROM accounts WHERE email='$login_email'");
            while($Row = mysql_fetch_assoc($Query)) {
               $login_db_email = $Row['email'];
               $login_db_password = $Row['password'];
               $login_db_status = $Row['status'];
               $login_db_banned = $Row['banned'];
               $login_db_pub_key = $Row['pub_key'];
               $login_db_priv_key = $Row['priv_key'];
               if($login_password==$login_db_password) {
                  if($login_db_status=="1") {
                     if($login_db_banned!="1") {
                        $_SESSION['apiidentity'] = $login_db_email;
                        setcookie("identa",$login_db_pub_key,time() + (10 * 365 * 24 * 60 * 60));
                        setcookie("identb",$login_db_priv_key,time() + (10 * 365 * 24 * 60 * 60));
                        header('Location: account.php');
                        $onloader = 'Logged in!';
                     } else {
                        $onloader = 'That account has been banned.';
                     }
                  } else {
                     $onloader = 'You have not activated your account using the activation email.';
                  }
               } else {
                  $onloader = 'Invalid password!';
               }
            }
         } else {
            $onloader = 'Account does not exist!';
         }
      } else {
         $onloader = 'No password was entered!';
      }
   } else {
      $onloader = 'No email was entered!';
   }
}
if($getaction=="register") {
   $register_email = security($_POST['email']);
   $register_password = security($_POST['password']);
   $register_conpassword = security($_POST['conpassword']);
   if($register_email) {
      if($register_password) {
         if($register_password==$register_conpassword) {
            $register_password = substr($register_password, 0, 30);
            $register_password = md5($register_password);
            $register_pub_key = pubkeygen();
            $register_priv_key = pubkeygen();
            $Query = mysql_query("SELECT email FROM accounts WHERE email='$register_email'");
            if(mysql_num_rows($Query) == 0) {
               $sql = mysql_query("INSERT INTO accounts (id,date,ip,email,password,status,banned,pub_key,priv_key) VALUES ('','$date','$ip','$register_email','$register_password','1','0','$register_pub_key','$register_priv_key')");
               $onloader = 'Account created! You can login now.';
            } else {
               $onloader = 'There is already an account using that email!';
            }
         } else {
            $onloader = 'Passwords do not match!';
         }
      } else {
         $onloader = 'No password was entered!';
      }
   } else {
      $onloader = 'No email was entered!"';
   }
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
      <h2>System Menu</h2>
      <ul>
         <li><a href="#login">Login</a></li>
         <li><a href="#register">Register</a></li>
         <li><a href="http://bdrf.info/index.php?mobile=no" target="_parent">Full Site</a></li>
      </ul>
   </div>

   <div id="login" class="panel">
      <h2>Login</h2>
      <form action="index.php" method="POST" target="_parent">
      <input type="hidden" name="action" value="login">
      <fieldset>
         <div class="row">
            <label>Email</label>
            <input type="text" name="email" placeholder="email@example.com"/>
         </div>
         <div class="row">
            <label>Password</label>
            <input type="password" name="password" placeholder="password"/>
         </div>
      </fieldset>
      <div class="row">
      <input type="submit" name="submit" value="Login" style="height: 30px; background: #333333; color: #FFFFFF; border: 0px none #333333; font-size: 16px; font-weight: bold;"/>
      </div>
      </form>
   </div>

   <div id="register" class="panel">
      <h2>Register</h2>
      <form action="index.php" method="POST" target="_parent">
      <input type="hidden" name="action" value="register">
      <fieldset>
         <div class="row">
            <label>Email</label>
            <input type="text" name="email" placeholder="email@example.com"/>
         </div>
         <div class="row">
            <label>Password</label>
            <input type="password" name="password" placeholder="password"/>
         </div>
         <div class="row">
            <label>Repeat</label>
            <input type="password" name="conpassword" placeholder="password"/>
         </div>
      </fieldset>
      <div class="row">
      <input type="submit" name="submit" value="Register" style="height: 30px; background: #333333; color: #FFFFFF; border: 0px none #333333; font-size: 16px; font-weight: bold;"/>
      </form>
      </div>
   </div>
</body>
</html>
