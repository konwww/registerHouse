<?php

namespace app\admin\controller;

use think\App;

class CourseTable extends Container
{
    public function __construct(App $app = null)
    {
        parent::__construct($app);
        $this->model = new \app\common\model\CourseTable();
    }
}
