<?php 
  require_once 'auth.php';
  require_once 'install.php';
  if(!isLoggedIn())
  	redirectAndExit('index.php');
  $username = $_SESSION['logged_in_username'];
  $pdo = new PDO($dsn);
  $sql = "SELECT balance, location FROM account WHERE username == :username";
  $stmt = $pdo->prepare($sql);
  $stmt->execute(array('username' => $username, ));
  $row=$stmt->fetch(PDO::FETCH_ASSOC);
?>

<!DOCTYPE html>
<html lang="en">
  <head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <!-- The above 3 meta tags *must* come first in the head; any other head content must come *after* these tags -->
    <title>Internet Banking System</title>
    <!-- Stylesheet -->
    <link href="./bulma.css" rel="stylesheet" type="text/css" />

  </head>

  <body>

  <section class="hero is-success">
    <div class="hero-body">
      <div class="container">
        <a class="button is-danger logOut is-pulled-right" href="/logout.php">Log out</a>
        <h1 class="title">
          You're logged in!
        </h1>
        <h2 class="subtitle">
          Hello there <b>@<?php echo $_SESSION['logged_in_username'] ?></b>
        </h2>
      </div>
    </div>
  </section>

  <div class="columns">
    <div class="column is-half is-offset-one-quarter">

      <div class="tabs is-centered">
        <ul>
          <li class="is-active">
            <a>
              <span class="icon is-small"><i class="fa fa-image"></i></span>
              <span><b>Overview</b></span>
            </a>
          </li>
          <li>
            <a href="/history.php">
              <span class="icon is-small"><i class="fa fa-music"></i></span>
              <span>Full History</span>
            </a>
          </li>
          <li>
            <a href="/credit.php">
              <span class="icon is-small"><i class="fa fa-film"></i></span>
              <span>Credit</span>
            </a>
          </li>
          <li>
            <a href="/debit.php">
              <span class="icon is-small"><i class="fa fa-file-text-o"></i></span>
              <span>Withdraw</span>
            </a>
          </li>
        </ul>
      </div>

      <h1 class="title">Your balance:  ₹<?php echo $row['balance'] ?></h1>
      <h2 class="subtitle">Branch: <?php echo $row['location'] ?></h2>

      <h2 class="is-size-6 has-text-grey"><b>Your recent 5 transactions</b></h2>

      <?php 
        $pdo = new PDO($dsn);
        $sql = "SELECT * FROM trans WHERE user == :username";
        $stmt = $pdo->prepare($sql);
        $stmt->execute(array('username' => $username, ));
        $row=$stmt->fetchAll(PDO::FETCH_ASSOC);
        $row=array_reverse($row)
      ?>

      <table class="table is-fullwidth is-striped">
        <thead>
          <tr class="is-selected">
            <th>Amount</th>
            <th>Type of transaction</th>
            <th>Date</th>
          </tr>
        </thead>

        <?php  
          for($count=0; $count<5; $count++)
          {
        ?>

        <tr>
          <th>₹<?php echo $row[$count]['amount'] ?></th>
          <th><?php echo $row[$count]['mode'] ?></th>
          <th><?php echo $row[$count]['transDate'] ?></th>
        </tr>

        <?php } ?>

      </table>

    </div>
  </div>

  <script src="./jq.js"></script>
  <script>
    $(".button").click(function(){
      $(".button").addClass("is-loading");
    });
  </script>
 

  </body>
</html>
