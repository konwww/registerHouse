<?php

namespace app\admin\controller;

use think\App;
use think\Controller;
use think\Request;

class City extends Container
{
    public function __construct(App $app = null)
    {
        parent::__construct($app);
        $this->model=new \app\common\model\City();
    }
}
