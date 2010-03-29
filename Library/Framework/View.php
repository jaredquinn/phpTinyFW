<?

class Framework_View {

	protected $content;

	protected $contentType;

	protected $styles = array();

	protected $scripts = array();
	
	protected $meta = array();

	protected $viewPath;

	protected $viewScript;

	protected $layoutPath;

	protected $layout;

	public function __construct() {
		$this->setContentType('text/html; charset=utf-8');
	}

	public function jsonResult($result) {
//		header('Content-type: application/json');
		die(json_encode($result));
	}

	public function setView($viewScript) {
		$this->viewScript = $viewScript;
	}

	public function setViewPath($viewPath) {
		$this->viewPath = $viewPath;
	}

	public function setLayoutPath($layoutPath) {
		$this->layoutPath = $layoutPath;
	}

	public function setLayout($layout) {
		$this->layout = $layout;
	}

	public function htmlResult($result) {
		die($result);
	}


	public function render() {
		ob_start();
		include $this->viewPath . '/' . $this->viewScript . '.html';
		$this->content = ob_get_clean();

		header($_SERVER['SERVER_PROTOCOL'] . ' 200');
		header('Status: 200 Successful');

		include $this->layoutPath . '/' . $this->layout . '.html';
	}

	public function setContentType($contentType) {
		$this->contentType = $contentType;
	}

	public function setMetaData($meta, $data) {
		$this->meta[$meta] = $data;
	}

	public function addStyleSheet($location) {
		$this->styles[] = $location;
	}

	public function addScript($location) {
		$this->scripts[] = $location;
	}

	public function htmlContentType() {
		return '<meta http-equiv="Content-type" content="' . $this->getContentType . '" />' . "\n";
	}	

	public function htmlStyleSheets() {
		$output = '';
		foreach($this->styles as $s) {
		   $output .= '<link href="' . $s . '" rel="stylesheet" type="text/css" />' . "\n";
		}
		return $output;
	}

	public function htmlScripts() {
		$output = '';
		foreach($this->scripts as $s) {
		   $output .= '<script type="text/javascript" src="' . $s . '"></script>' . "\n";
		}
		return $output;
	}

	public function htmlMetaData() {
		$output = "";
		foreach($this->meta as $k=>$v) {
			$output .= '<meta name="' . $k . '" content="' . $v . '" />' . "\n";
		}
		return $output;
	}

	public function getContent() {
		return $this->content;
	}

	public function getInstance() {
		global $__view;
		if(!$__view) { $__view = new Framework_View(); }
		return $__view;
	}

}

