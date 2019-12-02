<?php


namespace app\index\controller;


use think\App;
use think\Controller;

class OAuth extends Controller
{
    public function __construct(App $app = null)
    {
        parent::__construct($app);
    }
}