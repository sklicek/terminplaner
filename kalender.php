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
	
    <title>KALENDER</title>

    <!-- Bootstrap core CSS -->
    <link href="css/bootstrap.min.css" rel="stylesheet">
</head>
<body>

<?php include("menue.php");?>

<div class="container">

<h1><?php echo $arrMonth[date('F',$date)];?> <?php echo date('Y',$date); ?></h1>
<p>
  <a href="?timestamp=<?php echo monthBack($date); ?>" class="btn btn-info btn-sm" ><<</a>
  <a href="?timestamp=<?php echo monthForward($date); ?>" class="btn btn-info btn-sm">>></a>
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
$heute=date("d.m.Y",strtotime('now'));

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
  
  if (strtotime($d)>=strtotime($heute)){
  ?>
  <td>
  	<?=sprintf("%02d",$i);?>
  	<div style="float:right">
	   <button type="button" class="btn btn-info btn-sm" data-bs-toggle="modal" data-bs-target="#BearbeitenModal" data-bs-datum="<?=$d;?>">
             <img src="css/icons/pencil.svg" alt="Hinzufuegen">
           </button>
        </div>   
  	<br><span class="badge bg-secondary"><?=$std_display;?></span>
  </td>
  <?php
  } else {
  ?>
  <td>
  	<?=sprintf("%02d",$i);?>
  	</td>
  	<?php
  }
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

<!-- Modal -->
<div class="modal fade" id="BearbeitenModal" tabindex="-1" aria-labelledby="BearbeitenModalLabel" aria-hidden="true">
  <div class="modal-dialog">
    <div class="modal-content">
      <form method="post">
        <input type="hidden" name="datum" id="datum">
        <div class="modal-header">
          <h5 class="modal-title" id="BearbeitenModalLabel">Bearbeiten</h5>
          <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Schliessen"></button>
        </div>
        <div class="modal-body">
          <label>Uhrzeit</label>
          <input type="time" name="startzeit" id="startzeit" class="form-control" required> 
        </div>
        <div class="modal-footer">
          <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Schliessen</button>
          <input type="submit" name="submit" class="btn btn-primary" value="Speichern" />
        </div>
      </form>
    </div>
  </div>
</div>

<!-- Bootstrap Javascript -->
<script src="js/bootstrap.min.js"></script>
<script>
    var myModal = document.getElementById('BearbeitenModal');
    myModal.addEventListener('show.bs.modal', function (event) {
        // Get the button that triggered the modal
        var button = event.relatedTarget;
        // Extract value from the custom data-* attribute
        var titleData = button.getAttribute("data-bs-datum");
        myModal.querySelector(".modal-title").innerText = "Terminanfrage am " + titleData;
        document.getElementById('datum').value = titleData;
    });

</script>
</body>
</html>  
