<?php
/* DB und CONFIG einbinden */
@require_once ("include/connect_db.php");
@require_once ("include/config.inc.php");

$id_startzeit=0;
if (isset($_POST['startzeit'])) $id_startzeit=$_POST['startzeit'];

$msg="";
$res=1;
//termin als reserviert markieren
if ($stmt = $mysqli -> prepare("UPDATE tblSprechstundenDetails SET reserviert = ? WHERE id_details = ?")) {
    $stmt -> bind_param("ii", $res, $id_startzeit);

    if($stmt -> execute()) {
    $msg="Alles gespeichert.";
    } else {
        $msg="Fehler beim Speichern.";
    }
    $stmt->close();
}
?>
<script>
    alert('<?=$msg;?>');
    window.location.href="kalender.php";
</script>