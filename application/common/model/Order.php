<?php

namespace app\common\model;

use think\Model;
use think\model\concern\SoftDelete;

class Order extends Model
{
    use SoftDelete;
    protected $table = "c_order";
    protected $autoWriteTimestamp = "datetime";
    protected $updateTime = "updateTime";
    protected $createTime = "createTime";

    public $bid;
    protected $deleteTime = "deleteTime";

    public $floor;
    public $usage;
    public $weekNum;
    public $weekTimes;
    public $startTime;
    public $editTime;
    public $fieldNum;
}
