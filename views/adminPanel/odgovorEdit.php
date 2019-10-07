<?php
/**
 * Created by PhpStorm.
 * User: Luke
 * Date: 6/12/2018
 * Time: 2:55 AM
 */include("../../connection.php");
if(isset($_REQUEST['Edit'])){
    $id=$_REQUEST['id'];
    $name=$_REQUEST['name'];
    $upit="UPDATE odgovor SET tekst_odgovora=:name where id=:id";
    $stmt=$conn->prepare($upit);
    $stmt->bindParam(":id",$id);
    $stmt->bindParam("name",$name);
    try{
        $stmt->execute();
        header("Location:".$_SERVER['HTTP_REFERER']);
    }catch (PDOException $ex){
        echo $ex->getMessage();
    }
}