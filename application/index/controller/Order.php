<?php

namespace app\index\controller;

use app\index\model\CourseTable;
use think\App;
use think\facade\Session;
use think\Model;

class Order extends Container
{
    public function __construct(App $app = null, Model $model = null)
    {
        parent::__construct($app, $model);
        $this->model = new \app\index\model\Order();
    }

    public function register($cid, $weekNum, $weekTimes, $startTime, $endTime, $usage)
    {
        $result = $this->validateTime($cid, $weekNum, $weekTimes, $startTime, $endTime);
        if (count($result) != 0) return Error::operationFailed("save", "预约时间冲突，请另择时间");
        $result = $this->model->save(["cid" => $cid, "weekNum" => $weekNum - 1, "usage" => $usage, "weekTimes" => $weekTimes, "startTime" => $startTime, "endTime" => $endTime]);
        return $this->response($result, "ok", 0);
    }

    private function validateTime($cid, $weekNum, $weekTimes, $start, $end)
    {
        $limit = \app\index\model\Room::where("limitTime", ">", $end)->where("room_id", $cid)->find();
        if (!is_null($limit)) Error::notFoundField("你的使用时间超过了教室使用限制时间");
        $course = CourseTable::where("cid", $cid)
            ->where("weekNum", $weekNum)
            ->where("weekTimes", $weekTimes)
            ->where("startTime", "<", $end)
            ->where("endTime", ">", "startTime")
            ->buildSql();
        $order = $this->model->where("cid", $cid)
            ->hidden(["uid"])
            ->where("weekNum", $weekNum)
            ->where("weekTimes", $weekTimes)
            ->where("startTime", "<", $end)
            ->where("endTime", ">", $start)
            ->union($course)
            ->select();
        return $order;
    }

    /**
     * 有效的预约
     * @throws \think\db\exception\DataNotFoundException
     * @throws \think\db\exception\ModelNotFoundException
     * @throws \think\exception\DbException
     */
    public function availableHouseByUser()
    {
        $uid = Session::get("user_id");
//        如果endTime小于当前时间，即视为该预约提前结束
        $data = $this->model->where(["uid" => $uid])->where("`startTime` >NOW() and `endTime`>NOW()")->select();
        $this->response($data, "ok", count($data));
    }

    /**
     * 失效的预约
     * @throws \think\db\exception\DataNotFoundException
     * @throws \think\db\exception\ModelNotFoundException
     * @throws \think\exception\DbException
     */
    public function unavailableHouseByUser()
    {
        $uid = Session::get("user_id");
//        如果endTime小于当前时间，即视为该预约提前结束
        $data = $this->model->where(["uid" => $uid])->where("`startTime` <=NOW() or `endTime`<=NOW()")->select();
        $this->response($data, "ok", count($data));
    }
}
