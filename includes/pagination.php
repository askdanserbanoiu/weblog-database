<?php

class Pagination {
	public $current_page;
	public $per_page;
	public $total_count;
	
	public function __construct($page=1, $per_page=20, $total_count=0) {
		$this->current_page = (int)$page;
		$this->per_page = (int)$per_page;
		$this->total_count = (int)$total_count;
	}
	
	public function offset() {
		/*assuming 20 items per page:
		 * page 1 has an offset of 0  (1-1)*20
		 * page 2 has an offset of 20 (2-1)*20
		 */
		return ($this->current_page - 1)*$this->per_page;
	}
	
	public function total_pages() {
		return ceil($this->total_count/$this->per_page);
	}
	
	public function previous_page() {
		return $this->current_page - 1;
	}
	
	public function next_page() {
		return $this->current_page + 1;
	}
	
	public function has_previous_page() {
		return $this->previous_page() >= 1 ? true : false;
	}
	
	public function has_next_page() {
		return $this->next_page() <= $this->total_pages()? true : false;
	}
	
	public function view_page_slider($author=null, $subject=null, $month=null, $year=null, $user=false) {
		$path = "articles.php";
		if ($user) $path = "manage_articles.php";
		$html = "";
		for ($i=1; $i <= $this->total_pages(); $i++){
			if ($i == $this->current_page) {
				$html .= "<span class=\"selected\">{$i}</span>";
			} else {
				$html .= "<a href=\"{$path}?page={$i}";
				if ($author != null) $html .= "&&author={$author}";
				if ($subject != null) $html .= "&&subject={$subject}";
				if ($month != null) $html .= "&&month={$month}";
				if ($year != null) $html .= "&&year={$year}";
				$html .= "\">{$i}</a>";
			}
		}
		return $html;
	}
	
	
}

?>
