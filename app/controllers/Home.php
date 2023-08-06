<?php

if(!defined("ROOT")) die ("direct script access denied");

/**
 * home class
 */
class Home extends Controller
{
	
	public function index()
	{

		$data['title'] = "Home";

		$this->view('home',$data);
	}
	
}