<?php 

namespace Opemedios\Http\Controllers;

use Illuminate\Routing\Controller;
use Opemedios\Http\Views\View;

class BaseController extends Controller{

	protected function head(){

		$view = new View('head');

		$response = $view->render();		
		$response->send();
	}

	protected function footer(){

		$view = new View('footer');

		$response = $view->render();		
		$response->send();
	}
}

