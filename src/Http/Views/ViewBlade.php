<?php 

namespace Opemedios\Http\Views;

use Windwalker\Renderer\BladeRenderer;

class ViewBlade{

	public function renderBlade(){
			$path = $this->loadBladeTemplate();
			
			$renderer = new BladeRenderer($path, 
							array('cache_path' => dirname(dirname(dirname(__DIR__))).'/resources/cache')
						);

			return $renderer;
			
			// $renderer->render($this->template, $this->params );
	}

	private function loadBladeTemplate(){
			// return "/resources/views/$this->template.blade.php";

			return dirname(dirname(dirname(__DIR__))).
			'/resources/views';
	}
}