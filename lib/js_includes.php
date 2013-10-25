<?php
include($_SESSION['home'].'config.php');
include($_SESSION['home'].'lib/babelfish.php'); 

$rpage = page_controller();
$babel = new BabelFish($rpage);


?>
