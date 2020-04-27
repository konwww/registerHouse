<?php

namespace app\common\model;

use think\Model;

class Room extends Model
{
    //
    protected $table = "c_classroom";
    protected $autoWriteTimestamp = "datetime";
    protected $updateTime = "updateTime";
    protected $createTime = "createTime";
    public $id;
    public $location;
    public $classroomAlias;
    public $limitTime;
    public $room_id;
    public $city;

}
