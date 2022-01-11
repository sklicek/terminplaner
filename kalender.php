<?php
$cur_month=date("n");
$cur_year=date("Y");

$arr_month=array("Januar","Februar","März","April","Mai","Juni","Juli","August","September","Oktober","November","Dezember");
$headline = array('Mon','Die','Mit','Don','Fre','Sam','Son');

//current date
$date = time();
$sum_days = date('t',$date);
$LastMonthSum = date('t',mktime(0,0,0,(date('m',$date)-1),0,date('Y',$date)));
$mo=date("m",$date);
$counter=0;
?>
<!DOCTYPE html>
<html lang="de">
<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1">
    <meta name="description" content="">
    <meta name="author" content="Steve Klicek" >
    <link rel="shortcut icon" href="../images/bm.ico">
	
    <title>BEST WEB OFFICE</title>

    <!-- Bootstrap core CSS -->
    <link href="css/bootstrap.min.css" rel="stylesheet">
</head>
<body>
<div class="container">
<h1><?=$arr_month[$cur_month-1].' '.$cur_year;?></h1>
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
  $std_display="0:00h";
  echo "<td>".sprintf("%02d",$i)."<br>".$std_display."</td>";
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