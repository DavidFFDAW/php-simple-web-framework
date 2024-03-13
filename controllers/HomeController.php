<?php

class HomeController extends BaseController
{
    public function __construct(Request $request)
    {
        parent::__construct($request);
    }

    public function index()
    {
        return View::render('home.index', ['title' => 'Home']);
    }

    public function admin()
    {
        // dd($this->getRequest());
        return View::render('home.index', ['title' => 'Admin']);
    }
}
