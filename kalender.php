<?php
/* DB und CONFIG einbinden */
@require_once ("include/connect_db.php");
@require_once ("include/config.inc.php");

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
  
//Sprechstunden zu aktuellen Monat lesen
$arr_sprechstunden=$arr_sprechstunden_details=array();
$a=$d=0;
if ($stmt = $mysqli -> prepare("SELECT a.id, a.anfang, a.ende, a.datum, b.anfang, b.ende, b.id_details, b.reserviert FROM tblSprechstunden as a left join tblSprechstundenDetails as b on a.id=b.id_termin where month(a.datum) = ? AND year(a.datum) = ? ORDER BY a.datum, a.anfang, b.anfang ASC")) {
  $stmt -> bind_param('ii',$cur_month,$cur_year);
  $stmt -> execute();
  $stmt -> bind_result($id, $startzeit, $endzeit, $datum, $anfang_detail, $ende_detail, $id_detail, $detail_reserviert);
  while ($stmt -> fetch()) {
    $arr_sprechstunden[$a][0]=$id;
    $arr_sprechstunden[$a][1]=date("H:i",strtotime($startzeit));
    $arr_sprechstunden[$a][2]=date("H:i",strtotime($endzeit));
    $arr_sprechstunden[$a][3]=date("d.m.Y",strtotime($datum));
    $a++;

    $arr_sprechstunden_details[$d][0]=$id;
    $arr_sprechstunden_details[$d][1]=date("H:i",strtotime($anfang_detail));
    $arr_sprechstunden_details[$d][2]=date("H:i",strtotime($ende_detail));
    $arr_sprechstunden_details[$d][3]=date("d.m.Y",strtotime($datum));
    $arr_sprechstunden_details[$d][4]=$id_detail;
    $arr_sprechstunden_details[$d][5]=$detail_reserviert;     
    $d++;
  }
  $stmt->close();
}
//echo '<pre>'.print_r($arr_sprechstunden_details,true).'</pre>';

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
	
  //id des tages holen aus array
  $id_datum=0;
  for ($a=0;$a<count($arr_sprechstunden);$a++){
    if ($arr_sprechstunden[$a][3]==$d){
      $id_datum=$arr_sprechstunden[$a][0];
    }
  }
  $anz_anfragen=0;
  for ($a=0;$a<count($arr_sprechstunden_details);$a++){
    if ($arr_sprechstunden_details[$a][3]==$d && $arr_sprechstunden_details[$a][5]==1){
      $anz_anfragen++;
    }
  }
  
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
  $std_display=$anz_anfragen." Anfrage(n)";
  
  if (strtotime($d)>=strtotime($heute)){
    if (strtotime($d)==strtotime($heute)){
      ?>
      <td style="background-color:yellow">
      <?php
    } else {
      ?>
      <td>
      <?php
    }
    ?>
  	<?=sprintf("%02d",$i);?>
  	<div style="float:right">
	   <button type="button" class="btn btn-info btn-sm" data-bs-toggle="modal" data-bs-target="#BearbeitenModal" data-bs-datum="<?=$d;?>" data-bs-id="<?=$id_datum;?>">
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
      <form method="post" action="terminanfrage.php">
        <input type="hidden" name="datum" id="datum">
        <input type="hidden" name="id_datum" id="id_datum">
        <div class="modal-header">
          <h5 class="modal-title" id="BearbeitenModalLabel">Bearbeiten</h5>
          <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Schliessen"></button>
        </div>
        <div class="modal-body">
          <label>Uhrzeit</label>
          <select name="startzeit" id="startzeit" class="form-control" required> 
          	<option value="">--- Bitte wählen ---</option>
          </select>
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
    var arr_details=<?php echo json_encode($arr_sprechstunden_details);?>;
    
    var myModal = document.getElementById('BearbeitenModal');
    myModal.addEventListener('show.bs.modal', function (event) {
        // Get the button that triggered the modal
        var button = event.relatedTarget;
        // Extract value from the custom data-* attribute
        var titleData = button.getAttribute("data-bs-datum");
        var idDate= button.getAttribute("data-bs-id");
        myModal.querySelector(".modal-title").innerText = "Terminanfrage am " + titleData;
        document.getElementById('datum').value = titleData;
        document.getElementById('id_datum').value = idDate;

        var select = document.getElementById('startzeit');
        for(var i = 0; i < arr_details.length; i++) {
          //alle nicht reservierten Eintraege anzeigen
          if (arr_details[i][0]==idDate && arr_details[i][5]==0){
            var el = document.createElement("option");
            el.textContent = arr_details[i][1] + '-' + arr_details[i][2];
            el.value = arr_details[i][4];
            select.appendChild(el);
          }
        }
    });
</script>
</body>
</html>  
