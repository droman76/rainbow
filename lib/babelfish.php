<?php
class DynamicProperties { }
class BabelFish {

	private $data;
	private $page;
	private $lan_file;
	
	function __construct($page) {
		// load the language files for this page
		global $CONFIG;
		if (!isset($CONFIG)) $CONFIG = $_SESSION['CONFIG'];	
		$this->page = $page;
		$lan_file = $CONFIG->home . "language/". $CONFIG->language . "/" .$page . ".".$CONFIG->language;
		$this->lan_file = $lan_file;
		//ilog("retriving language file: $lan_file");

		if (file_exists($lan_file)){
			$this->data = json_decode(file_get_contents($lan_file));
			if (json_last_error() === JSON_ERROR_NONE) return;
			else elog("Invalid Json formated language file. Please check $lan_file");
		}
		else {
			// no language file available! create it!!
			
			$object = new DynamicProperties;
			file_put_contents($lan_file, json_encode($object));
			chmod($lan_file, 0775);
			$this->data = $object;
			
		}
		
		


	}
	public function set($key,$translation){
		if ($key == null || !isset($key)) {
			//elog("BableFish: Tried to set empty property for translation: $translation");
		}
		else {
			$this->data->$key = $translation;
		}
	}

	public function save() {
		//ilog("Storing language file ".$this->lan_file);
		file_put_contents($this->lan_file, json_encode($this->data));
	}

	public function say($key,$translator='on') {

		$translation = '';
		
		if (isset($this->data) && property_exists($this->data, $key)) $translation = $this->data->$key;
		else {
			$translation =  $key;
			$this->set($key,$key);
			$this->save();
		}
		$page = $this->page;

		// check if user is a translator, and if so add translation capabilities
		//if ((isset($_SESSION['translator']) && $_SESSION['translator'] == 'on')&& $translator=='on') {
		if (is_translator() && $translator == 'on') {
			global $CONFIG;
			$language = $_SESSION['user_language'];
			include($CONFIG->home.'/action/translator/language.php');
			$html = "<span id='".$page."-".$key."' onmouseover='ttranslate(this)'>$translation</span>";
			$html .="<span id='".$page."-".$key."-h' style='display:none;z-index:1000' class='rainbow-translator'>".$language_codes[$language].":<br></br><input onkeypress='translate_enter_key()' onblur='ttranslate_post(this)' type='text' value='$translation' id='".$page."-".$key."-n'></input></span>";
			return $html;
		}
		else return $translation;

	}	


}

?>