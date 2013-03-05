<?php
// this is the file where the votes are sent
// if the vote is successfull it should return 'ok'
// else anything else :)

if($_POST) {
	include "rating_system.php";

	$rating = new rating_system($_SERVER['REMOTE_ADDR'], 10);

	$rating->set_rating($_POST['item'], $_POST['score']);
	
	header("Location: ".$_SERVER["HTTP_REFERER"]);
}