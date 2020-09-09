<?php

namespace app\index\controller;

use app\index\model\CourseTable;
use think\App;
use think\console\command\make\Model;
use think\facade\Session;
use think\response\Json;

class Order extends Container
{
    public function __construct(App $app = null)
    {
        parent::__construct($app);
        $this->model = new \app\common\model\Order();
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
    public function availableHouseByUser($page = 1, $limit = 10)
    {
        $uid = Session::get("user_id");
//        如果endTime小于当前时间，即视为该预约提前结束
        $data = $this->model->where(["uid" => $uid])->where("`startTime` >NOW() and `endTime`>NOW()")->order("endTime", "desc")->page($page, $limit)->select();
        $count = $this->model->where(["uid" => $uid])->where("`startTime` >NOW() and `endTime`>NOW()")->count();
        $this->response($data, "ok", $count);
    }

    /**
     * 失效的预约
     * @throws \think\db\exception\DataNotFoundException
     * @throws \think\db\exception\ModelNotFoundException
     * @throws \think\exception\DbException
     */
    public function unavailableHouseByUser($page = 1, $limit = 10)
    {
        $uid = Session::get("user_id");
//        如果endTime小于当前时间，即视为该预约提前结束
        $count = $this->model->where(["uid" => $uid])->where("`startTime` <=NOW() or `endTime`<=NOW()")->count();
        $data = $this->model->where(["uid" => $uid])->where("`startTime` <=NOW() or `endTime`<=NOW()")->order("endTime", "desc")->page($page, $limit)->select();
        $this->response($data, "ok", $count);
    }

    /**
     * 终止订单
     * @param $id int 订单id
     * @return \think\Response
     */
    public function breakUp($id)
    {
        $uid = Session::get("user_id");
        $result = $this->model->save(["endTime" => date("Y-m-d H:i:s", time())], ["id" => $id, "uid" => $uid]);
        return $this->response($result, "ok");
    }

    /**
     * 定位 签到 or 签退
     * @param $oid
     * @param $latitude
     * @param $longitude
     * @param $token
     */
    public function signByGPS($oid, $latitude, $longitude, $token)
    {
        $data = $this->model->get($oid);
        $method = $data->getData("method");
        if (empty($method)) {
            //未打卡 签到
            $this->model->save(["signInGPSData" => [$latitude, $longitude], "signInTime" => date("Y-m-d H:i:s"),"method"=>"GPS"], ["id" => $oid]);
        } else {
            //已打卡 签退
            $this->model->save(["signOutGPSData" => [$latitude, $longitude],"signOutTime" => date("Y-m-d H:i:s")], ["id" => $oid]);
        }
        return $this->response([""],"");
    }

    /**
     * 照片 签到 or 签退
     * @param $oid
     * @param $token
     */
    public function signByPicture($oid, $token)
    {
        $uid = $this->request->session("user_id");
    }

}
