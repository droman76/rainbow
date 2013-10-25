<?php

class DynamicHandler {


}


class PageHandler {

	protected $page;
	protected $root;

	function __construct( $inpage ) {
		global $CONFIG;

		$this->root = $CONFIG->home;

		if ( $inpage != '' )
			$page_loc = "page/".$inpage."/".$inpage.".json";
		else
			$page_loc = "page/master/master.json";

		


		$this->page = json_decode( file_get_contents( $CONFIG->home .$page_loc ) );

		/*
	 		* PAGE INHERITANCE HANDLING
	 	*/
	 	//ilog("Processing page ".$this->page->content);	
		// TODO: support chained inheritance parent of parent etc..
		if ( property_exists( $this->page , 'inherit' ) ) {
			$page_parent = "page/".$this->page->inherit."/".$this->page->inherit.".json";
			//ilog("Parent = $page_parent <br>");
			$parent = json_decode( file_get_contents( $CONFIG->home . $page_parent ) );
			// go through the page and fill in missing entries with parent entries
			if ( !property_exists( $this->page, 'title' ) ) $this->page->title = $parent->title;
			if ( !property_exists( $this->page , 'head' ) ) $this->page->head = $parent->head;
			if ( !property_exists( $this->page , 'header' ) ) $this->page->header = $parent->header;
			if ( !property_exists( $this->page , 'content' ) ) $this->page->content = $parent->content;
			if ( !property_exists( $this->page , 'sidebar_left' ) ) $this->page->sidebar_left = $parent->sidebar_left;
			if ( !property_exists( $this->page , 'sidebar_right' ) ) $this->page->sidebar_right = $parent->sidebar_right;
			if ( !property_exists( $this->page , 'top_menu' ) ) $this->page->top_menu = $parent->top_menu;
			if ( !property_exists( $this->page , 'sidebar_menu' ) ) $this->page->sidebar_menu = $parent->sidebar_menu;
			if ( !property_exists( $this->page , 'footer' ) ) $this->page->footer = $parent->footer;
		}



		// Now call the dynamic handling capabilities if any of the object
		if ( property_exists( $this->page , 'handler' ) ) {
			ilog("Dynamic handler found! ... processing ".$this->page->handler);
			include ($this->root . 'page/' . $this->page->handler);
			$this->page = pre_processor($this->page); 

		}


	}

	public function getTemplate() {

		return $this->page->template;

	}

	public function getHeader() {
		return $this->root . 'page/' . $this->page->header;

	}
	public function getContent() {
		return $this->root . 'page/' . $this->page->content;


	}
	public function getFooter() {
		return $this->root . 'page/' . $this->page->footer;
	}
	public function getHead() {
		return $this->root . 'page/' . $this->page->head;
	}
	public function getLeftSidebar() {
		return $this->root . 'page/' . $this->page->sidebar_left;
	}
	public function getRightSidebar() {
		return $this->root . 'page/' . $this->page->sidebar_right;
	}
	public function getTopMenu() {
		return $this->root . 'page/' . $this->page->top_menu;
	}
	public function getSideMenu() {
		return $this->root . 'page/' . $this->page->sidebar_menu;
	}

	public function getLayout() {
		return $this->page->layout;
	}






}







?>
