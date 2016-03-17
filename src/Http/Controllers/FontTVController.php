<?php 

namespace Opemedios\Http\Controllers;

use Opemedios\Http\Views\ViewBlade;

class FontTVController extends BaseController{

	public function add(){

		$view = new ViewBlade();
		
		$renderer = $view->render();

		return $renderer->render('addFontTV');
	}

	public function save(){

		/*print_r($_POST);*/
		echo "hola";
	}

}

