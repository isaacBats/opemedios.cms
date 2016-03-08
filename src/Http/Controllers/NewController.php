<?php 

namespace Opemedios\Http\Controllers;

use Opemedios\Http\Views\ViewBlade;

class NewController extends BaseController{

	public function add(){

		$view = new ViewBlade();
		
		$renderer = $view->render();

		return $renderer->render('addNew');
	}

}

