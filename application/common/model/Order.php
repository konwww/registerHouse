<?php

namespace app\common\model;

use think\Model;
use think\model\concern\SoftDelete;
use think\response\Json;

class Order extends Model
{
    use SoftDelete;
    protected $table = "c_order";
    protected $autoWriteTimestamp = "datetime";
    protected $updateTime = "updateTime";
    protected $createTime = "createTime";
    protected $json=["GPS"];
    public $bid;
    protected $deleteTime = "deleteTime";
    protected $jsonAssoc=true;

    public $floor;
    public $usage;
    public $weekNum;
    public $weekTimes;
    public $startTime;
    public $editTime;
    public $fieldNum;
    public $signInTime;
    public $signOutTime;
    /**
     * @var enum(GPS,Picture)
     */
    public $method;
    public $imgPath;
    /**
     * @var json [latitude float,longitude float]
     */
    public $GPS;
}
