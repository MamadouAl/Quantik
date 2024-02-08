<?php
session_start();
$_SESSION['pieceBlanche'] = $_POST['piece'];
$_SESSION['posePieceBlanche'] = $_POST['posePiece'];


$piece = $_POST['piece'];
echo "Piece selectionnée : " . $piece . "<br>";
echo $_SESSION['pageChoisirBlanche'];
//$_SESSION[''] = $_SERVER['REQUEST_URI'];

if(isset($_SESSION['pageChoisirBlanche'])){
    header('Location: PagePosePieceBlanche.php');
}
/*
if(isset($_SESSION['pagePoseBlanche'])){
    header('Location: PageChoisirPieceBlanche.php');
}
*/