<?php
$cur_month=date("n");
$cur_year=date("Y");

$headline = array('Mon','Die','Mit','Don','Fre','Sam','Son');

//current date
$date = time();
if( isset($_REQUEST['timestamp'])) $date = $_REQUEST['timestamp'];

$sum_days = date('t',$date);
$LastMonthSum = date('t',mktime(0,0,0,(date('m',$date)-1),0,date('Y',$date)));
$mo=date("m",$date);
$counter=0;

function monthBack( $timestamp ){
  return mktime(0,0,0, date("m",$timestamp)-1,date("d",$timestamp),date("Y",$timestamp) );
}
function monthForward( $timestamp ){
  return mktime(0,0,0, date("m",$timestamp)+1,date("d",$timestamp),date("Y",$timestamp) );
}

$arrMonth = array(
  "January" => "Januar",
  "February" => "Februar",
  "March" => "M&auml;rz",
  "April" => "April",
  "May" => "Mai",
  "June" => "Juni",
  "July" => "Juli",
  "August" => "August",
  "September" => "September",
  "October" => "Oktober",
  "November" => "November",
  "December" => "Dezember"
  );
?>
<!DOCTYPE html>
<html lang="de">
<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1">
    <meta name="description" content="">
    <meta name="author" content="Steve Klicek" >
    <link rel="shortcut icon" href="images/favicon.jpg">
	
    <title>KONFIGURATION</title>

    <!-- Bootstrap core CSS -->
    <link href="css/bootstrap.min.css" rel="stylesheet">
</head>
<body>
<div class="container">
<h1><?php echo $arrMonth[date('F',$date)];?> <?php echo date('Y',$date); ?>
&nbsp;&nbsp;&nbsp;
<small>Konfiguration</small></h1>
<p>
  <a href="kalender.php" class="btn btn-success btn-sm">Kalender</a>
  <a href="?timestamp=<?php echo monthBack($date); ?>" class="btn btn-info btn-sm" ><-</a>
  <a href="?timestamp=<?php echo monthForward($date); ?>" class="btn btn-info btn-sm">-></a>
  <a class="btn btn-primary btn-sm" href="<?php echo $_SERVER["PHP_SELF"];?>">Heute</a>
</p>
<table class="table table-bordered">
<tr>
<?php
foreach( $headline as $key => $value ) {
  echo "<td style='background-color:silver'>".$value."</td>";
}
?>
</tr>
<tr>
<?php
for( $i = 1; $i <= $sum_days; $i++ ) {
  $day_name = date('D',mktime(0,0,0,date('m',$date),$i,date('Y',$date)));
	$day_number = date('w',mktime(0,0,0,date('m',$date),$i,date('Y',$date)));
	$d=date("d.m.Y",strtotime($i.".".$mo.".".$cur_year));	
				
	//letzte Tage des Vormonats
  if( $i == 1) {
	  $s = array_search($day_name,array('Mon','Tue','Wed','Thu','Fri','Sat','Sun'));
		for( $b = $s; $b > 0; $b-- ) {
			$x = $LastMonthSum-$b;
			echo "<td></td>";
      $counter++;
		}
	}
  //aktueller Monat
  $counter++;
  $std_display="0 Anfragen";
  ?>
  <td><a href="#"><?=sprintf("%02d",$i);?></a></td>
  <?php
  if ($counter%7==0){
    ?>
    </tr><tr>
    <?php
  }
}
?>
</tr>
</table>
</div>
</body>
</html>  