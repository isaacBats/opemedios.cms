<?php

namespace Opemedios\Http\Controllers;



use Illuminate\Http\Request;
use Opemedios\Http\Views\ViewBlade;


class HomeController extends BaseController{

	public function index(Request $request){

		$view = new ViewBlade();
		
		$renderer = $view->render();
		
		return $renderer->render('index');
	}
}
