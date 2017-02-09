<?php 

namespace Opemedios\Http\Views;

use Windwalker\Renderer\BladeRenderer;

class ViewBlade{

	public function render(){
		
		$path = $this->loadBladeTemplate();
			
		$renderer = new BladeRenderer($path, array('cache_path' => dirname(dirname(dirname(__DIR__))).'/resources/cache'));

		return $renderer;
			
	}

	private function loadBladeTemplate(){
			
		return dirname(dirname(dirname(__DIR__))).'/resources/views';
	}
}