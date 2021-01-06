<?php

require_once('config.php');
require_once('functions.php');

session_start();

if (empty($_SESSION['me'])) {
    header('Location: '.SITE_URL.'login.php');
    exit;
}

$me = $_SESSION['me'];
$myname = h($me['name']);

require 'Calendar.php';

$cal = new \MyApp\Calendar();
$cal->user($myname);
$month = h($cal->month);

?>
<!DOCTYPE html>
<html lang="ja">
<head>
  <meta charset="utf-8">
  <title>Calendar</title>
  <link rel="stylesheet" href="styles.css">
  <script src="https://ajax.googleapis.com/ajax/libs/jquery/1.12.4/jquery.min.js"></script>
  <link rel="stylesheet" href="https://ajax.googleapis.com/ajax/libs/jqueryui/1.12.1/themes/smoothness/jquery-ui.css">
  <script src="https://ajax.googleapis.com/ajax/libs/jqueryui/1.12.1/jquery-ui.min.js"></script>
<?php switch ($month) : ?>
<?php case (1) : ?> <style> table{background-color:#ffcccc;} .week{background-color:#ff7777;} </style> <?php break; ?>
<?php case (2) : ?> <style> table{background-color:#ffe6cc;} .week{background-color:#ffbb77;} </style> <?php break; ?>
<?php case (3) : ?> <style> table{background-color:#ffffcc;} .week{background-color:#ffff77;} </style> <?php break; ?>
<?php case (4) : ?> <style> table{background-color:#e6ffcc;} .week{background-color:#bbff77;} </style> <?php break; ?>
<?php case (5) : ?> <style> table{background-color:#ccffcc;} .week{background-color:#77ff77;} </style> <?php break; ?>
<?php case (6) : ?> <style> table{background-color:#ccffe6;} .week{background-color:#77ffbb;} </style> <?php break; ?>
<?php case (7) : ?> <style> table{background-color:#ccffff;} .week{background-color:#77ffff;} </style> <?php break; ?>
<?php case (8) : ?> <style> table{background-color:#cce6ff;} .week{background-color:#77bbff;} </style> <?php break; ?>
<?php case (9) : ?> <style> table{background-color:#ccccff;} .week{background-color:#7777ff;} </style> <?php break; ?>
<?php case (10) : ?><style> table{background-color:#e6ccff;} .week{background-color:#bb77ff;} </style> <?php break; ?>
<?php case (11) : ?><style> table{background-color:#ffccff;} .week{background-color:#ff77ff;} </style> <?php break; ?>
<?php case (12) : ?><style> table{background-color:#ffcce6;} .week{background-color:#ff77bb;} </style> <?php break; ?>
<?php endswitch; ?>
</head>
<body>
  <p class='welcome'>ようこそ<?php echo $myname; ?>さん <a href="logout.php">[logout]</a></p>
  <table border="1">
    <thead>
      <tr>
        <th><a href="<?php print SITE_URL; ?>/?t=<?php echo h($cal->prev); ?>">&laquo;</a></th>
        <th colspan="5"><?php echo h($cal->yearMonth); ?></th>
        <th><a href="<?php print SITE_URL; ?>/?t=<?php echo h($cal->next); ?>">&raquo;</a></th>
      </tr>
    </thead>
    <tbody>
      <tr class="week" align="middle">
        <td>日</td>
        <td>月</td>
        <td>火</td>
        <td>水</td>
        <td>木</td>
        <td>金</td>
        <td>土</td>
      </tr>
      <?php $cal->show(); ?>
    </tbody>
    <tfoot>
      <tr>
        <th colspan="7"><a href="<?php print SITE_URL; ?>">Today</a></th>
      </tr>
    </tfoot>
  </table>
  <script src="main.js"></script>
</body>
</html>
