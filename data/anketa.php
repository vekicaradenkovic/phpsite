<?php
session_start();
header("Content-type: application/json");
$code = 404;
$data = null;
$glasa=0;


include '../connection.php';

if(isset($_POST['odgovor'])){
    $odgovor=$_POST['odgovor'];
    $userid=$_SESSION['id'];
    $id_pitanja=$_POST['id_pitanja'];

    try {
        $sql="SELECT * FROM rezultat WHERE id_user=? and id_pitanja=?";
        $stmt=$conn->prepare($sql);
        $code = $stmt->execute(array($userid, $id_pitanja)) ? 201 : 500;
        $res=$stmt->fetchObject();

        if($stmt->rowCount()==0){
            try {
                $sql="INSERT INTO rezultat(id_odgovor, id_user, id_pitanja) VALUES (?,?,?)";
                $stmt=$conn->prepare($sql);
                $code=$stmt->execute(array($odgovor, $userid, $id_pitanja)) ? 201 : 500;
                $sql2="UPDATE odgovor SET broj=+1 WHERE id =:id";
                $stmt2=$conn->prepare($sql2);
                $stmt2->bindParam(":id",$odgovor);
                $stmt2->execute();

            } catch (PDOException $e) {
                $code = 500;
            }
        }

        else {
            $code = 409;
            $glasao=1;
        }


    } catch (PDOException $e) {
        $code = 500;
    }


}
if(isset($_POST['odgovor'])){
    $id_pitanja=$_POST['id_pitanja'];
    try {
        $sql="SELECT COUNT(id_odgovor) as prosek FROM rezultat WHERE id_pitanja=?";
        $stmt=$conn->prepare($sql);
        $code=$stmt->execute(array($id_pitanja)) ? 201 : 500;
        $res=$stmt->fetchObject();
        $upit="SELECT * FROM odgovor where id_pitanja=:id_pitanja";
        $stmtOdg=$conn->prepare($upit);
        $stmtOdg->bindParam(":id_pitanja",$id_pitanja);
        $stmtOdg->execute();
        $odgovori=$stmtOdg->fetchAll();
        $broj=$res->prosek;
    } catch (PDOException $e) {
        $code = 500;
    }
}
http_response_code($code);
echo json_encode([$broj,$odgovori,$glasao]);
?>