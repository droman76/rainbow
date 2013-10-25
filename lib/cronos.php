<?php

class Cronos {
	

}

function cronos() {
	global $CONFIG;
	$t_file = $CONFIG->userdata.$_SESSION['username'].'/timestamps';
	$cronos = null;
	if (!file_exists($t_file) ){
		ilog('Encoding new cronos file !!!');
		$cronos = new Cronos();
		$cronos->continent = time();
		$cronos->country = time();
		$cronos->region = time();
		$cronos->city = time();
		$cronos->world = time();
		$cronos->a = time();
		$cronos->b = time();
		$cronos->c = time();
		$cronos->d = time();
		$cronos->e = time();
		$cronos->f = time();
		$cronos->g = time();
		$cronos->h = time();
		$cronos->i = time();
		$cronos->j = time();
		$cronos->k = time();
		$cronos->l = time();
		$cronos->m = time();
		$cronos->n = time();
		$cronos->o = time();
		$cronos->p = time();
		$cronos->q = time();
		$cronos->r = time();
		$cronos->s = time();
		$cronos->t = time();
		$cronos->u = time();
		$cronos->v = time();
		$cronos->w = time();
		$cronos->x = time();
		$cronos->y = time();
		$cronos->z = time();
		$cronos->mail = time();
		$cronos->notifications = time();
		
		file_put_contents($t_file, json_encode($cronos));
	}
	else {
		//$cronos = json_decode(file_get_contents($t_file));
	}
	
}


function cronos_update($view){
	global $CONFIG;

	$t_file = $CONFIG->userdata.$_SESSION['username'].'/timestamps';

	
	// update cronos view
	$cronos = json_decode(file_get_contents($t_file));
	//elog("updating cronos $view value: ".$cronos->$view. " to ". time());
	$cronos->$view = time();
	file_put_contents($t_file,json_encode($cronos));

}

function retrieve_cronos() {
	global $CONFIG;
	$t_file = $CONFIG->userdata.$_SESSION['username'].'/timestamps';
	$cronos = json_decode(file_get_contents($t_file));
	return $cronos;
}

function cronos_get($view){
	global $CONFIG;

	$t_file = $CONFIG->userdata.$_SESSION['username'].'/timestamps';

		// update cronos view
	if (is_file($t_file)){
		$cronos = json_decode(file_get_contents($t_file));
		if (isset($cronos->$view))
			return $cronos->$view;
		else {
			// the view does not exist.. update it
			cronos_update($view);
			return time();
		}
	}

}
?>