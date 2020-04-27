<?php

namespace app\admin\controller;

use think\App;

class Building extends Container
{
    public function __construct(App $app = null)
    {
        parent::__construct($app);
        $this->model = new \app\common\model\Building();
    }
}
