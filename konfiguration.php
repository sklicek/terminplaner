<?php
/* DB und CONFIG einbinden */
require_once ("include/connect_db.php");
require ("include/config.inc.php");

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
function get_anz_termine($startzeit='00:00',$endzeit='00:00',$raster_mins=15){
  $anz=0;
  $startz=strtotime($startzeit); 
  $endz=strtotime($endzeit); 
  if ($startz>0 && $endz>0){
    $anz=(($endz-$startz)/60)/$raster_mins;
  }
  return $anz;
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
$arr_sprechstunden=array();
$a=0;
if ($stmt = $mysqli -> prepare("SELECT id, anfang, ende, datum FROM tblSprechstunden where month(datum) = ? AND year(datum) = ? ORDER BY datum, anfang")) {
  $stmt -> bind_param('ii',$cur_month,$cur_year);
  $stmt -> execute();
  $stmt -> bind_result($id, $startzeit, $endzeit, $datum);
  while ($stmt -> fetch()) {
    $arr_sprechstunden[$a][0]=$id;
    $arr_sprechstunden[$a][1]=date("H:i",strtotime($startzeit));
    $arr_sprechstunden[$a][2]=date("H:i",strtotime($endzeit));
    $arr_sprechstunden[$a][3]=$datum;
    $a++;
  }
  $stmt->close();
}

//***************************
//daten speichern
//****************************/
  $msg="";
  if (isset($_POST['submit'])){
    $startzeit=$_POST['startzeit'];
    $endzeit=$_POST['endzeit'];
    $datum=date("Y-m-d",strtotime($_POST['datum']));
    
    if (strtotime($endzeit)<=strtotime($startzeit)){
      ?>
      <script>
          alert('Die Endzeit darf nicht vor der Startzeit liegen!');
          window.location.href="konfiguration.php";
      </script>
      <?php
      exit;
    }

    if ($startzeit && $endzeit && $datum){
      //pruefen ob bereits vorhanden
      $vorhanden=0;
        
      if ($stmt = $mysqli -> prepare("SELECT id FROM tblSprechstunden WHERE datum = ? AND ende > ?")) {
          $stmt -> bind_param("ss", $datum,$startzeit);
          $stmt -> execute();
          $stmt -> bind_result($vorhanden);
          $stmt -> fetch();
          $stmt -> close();
	   }      
    
      //neu eintragen
      if ($vorhanden==0){
	      if ($stmt = $mysqli -> prepare("INSERT INTO tblSprechstunden (datum, anfang, ende) " .
	      " VALUES (?, ?, ?)")) {
		  $stmt -> bind_param("sss", $datum,$startzeit,$endzeit);
		  if($stmt -> execute()) {
		  } else {
		      $msg="Fehler beim Speichern.";
		  }
		  $stmt->close();
		  
		  if ($stmt = $mysqli -> prepare("SELECT LAST_INSERT_ID() FROM tblSprechstunden")) {
          	$stmt -> execute();
          	$stmt -> bind_result($vorhanden);
          	$stmt -> fetch();
            $stmt -> close();
        }
		  ?>
		  <script>
		    if ('<?=$msg?>') {
		      alert('<?=$msg;?>');
		    }
		    /*window.location.href="konfiguration.php";*/
		  </script>
		  <?php
		  //exit;
	      }
      } else {
	 		  $msg="Info: Es besteht bereits ein Eintrag.";
         ?>
          <script>
            if ('<?=$msg?>') {
              alert('<?=$msg;?>');
            }
            /*window.location.href="konfiguration.php";*/
          </script>
          <?php
          //exit;
      }

      $id_details=0;
      if ($vorhanden>0){
      	//details generieren wenn noch nicht vorhanden
      	if ($stmt = $mysqli -> prepare("SELECT id_details FROM tblSprechstundenDetails WHERE id_termin = ?")) {
          $stmt -> bind_param("i", $vorhanden);
          $stmt -> execute();
          $stmt -> bind_result($id_details);
          $stmt -> fetch();
          $stmt -> close();
	   	  }
        //var_dump($id_details);
	   	
        if ($id_details==0){
          //keine details vorhanden: erstellen
          $anz_termine=get_anz_termine($startzeit,$endzeit,RASTER);
          if ($anz_termine>0){
            for ($x=0;$x<$anz_termine;$x++){
              if ($stmt = $mysqli -> prepare("INSERT INTO tblSprechstundenDetails (id_termin) VALUES (?)")) {
                  $stmt -> bind_param("i", $vorhanden);
                  if($stmt -> execute()) {
                  } else {
                    $msg="Fehler beim Speichern.";
                  }
                  $stmt->close();
              }
            }	   				
          }
        } else {
          //details bestehen bereits
        }
        ?>
          <script>
            window.location.href="konfiguration.php";
          </script>
          <?php
          exit;
      }
    }
  }

//***************************
//daten entfernen
//****************************/
$msg="";
if (isset($_GET['id']) && $_GET['id']){
    if ($stmt = $mysqli -> prepare("DELETE FROM tblSprechstunden WHERE id = ? ")) {
        $stmt -> bind_param("i", $_GET['id']);
        if($stmt -> execute()) {
        
        } else {
            $msg="Fehler beim Entfernen.";
        }
        $stmt->close();
        ?>
        <script>
          if ('<?=$msg?>') {
            alert('<?=$msg;?>');
          }
          window.location.href="konfiguration.php";
        </script>
        <?php
        exit;
    }
  
}


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
<?php include("menue.php");?>
<div class="container">
<h1><?php echo $arrMonth[date('F',$date)];?> <?php echo date('Y',$date); ?>
&nbsp;&nbsp;&nbsp;
<small>Sprechstunden-Konfiguration</small></h1>
<p>
  <a href="?timestamp=<?php echo monthBack($date); ?>" class="btn btn-info btn-sm" ><-</a>
  <a href="?timestamp=<?php echo monthForward($date); ?>" class="btn btn-info btn-sm">-></a>
  <a class="btn btn-primary btn-sm" href="<?php echo $_SERVER["PHP_SELF"];?>">Heute</a>
  <div style="float: right;">
    <i>Einteilung: <?=RASTER;?> Minuten</i>
  </div>
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
  if (strtotime($d)>=strtotime($heute)){
    if (strtotime($d)==strtotime($heute)){
      ?>
      <td style="background-color: yellow;">
      <?php  
    } else {
      ?>
      <td>
      <?php  
    }
    ?>
        <h3><?=sprintf("%02d",$i);?>
        <div style="float:right">
          <button type="button" class="btn btn-info btn-sm" data-bs-toggle="modal" data-bs-target="#BearbeitenModal" data-bs-datum="<?=$d;?>">
            <img src="css/icons/pencil.svg" alt="Hinzufuegen">
          </button>
        </div>
        </h3>
        <ul class="list-group">
        <?php
          //sprechstunden lesen
          for ($a=0;$a<count($arr_sprechstunden);$a++){
            if (strtotime($arr_sprechstunden[$a][3])==strtotime($d)){
              $lst_id=$arr_sprechstunden[$a][0];
              $lst_startzeit=$arr_sprechstunden[$a][1];
              $lst_endzeit=$arr_sprechstunden[$a][2];

              $max_termine=get_anz_termine($lst_startzeit,$lst_endzeit,RASTER);
              ?>
              <li class="list-group-item"><?=$lst_startzeit.' - '.$lst_endzeit;?>
              <span class="badge bg-secondary"><?=$max_termine;?> Termine max.</span>
              <div style="float:right">
                <a href="?id=<?=$lst_id;?>" title="Entfernen" onclick="return confirm('Diese Sprechzeiten wirklich entfernen?');"><img src="css/icons/trash.svg" alt="Entfernen"></a>
              </div>
              </li> 
              <?php
            }
          }
        ?>
        </ul>
    </td>
    <?php
  } else {
    ?>
    <td><h3><?=sprintf("%02d",$i);?></h3></td>
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
          <label>Startzeit</label>
          <input type="time" name="startzeit" id="startzeit" class="form-control" required> 
          <label>Endzeit</label>
          <input type="time" name="endzeit" id="endzeit" class="form-control" required> 
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
        myModal.querySelector(".modal-title").innerText = "Bearbeiten vom " + titleData;
        document.getElementById('datum').value = titleData;
    });

</script>
</body>
</html>  
