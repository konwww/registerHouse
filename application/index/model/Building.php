<?php

namespace app\index\model;

use think\Model;

class Building extends Model
{
    //
    protected $table = "c_building";
    protected $autoWriteTimestamp = "datetime";
    protected $updateTime = "updateTime";
    protected $createTime = "createTime";
    public $buildingAlias;
    public $id;
    public $city;
    public $prefix;
    public $floorTotal;//楼层总数
}
