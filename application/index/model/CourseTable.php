<?php

namespace app\index\model;

use think\Model;
use think\model\concern\SoftDelete;

class CourseTable extends Model
{
    //
    use SoftDelete;
    protected $table = "c_course_table";
    protected $autoWriteTimestamp = "datetime";
    protected $updateTime = "updateTime";
    protected $createTime = "createTime";
    protected $deleteTime = "deleteTime";
    public $bid;
    public $floor;
    public $usage;
    public $weekNum;
    public $weekTimes;
    public $startTime;
    public $editTime;
    public $fieldNum;
}
