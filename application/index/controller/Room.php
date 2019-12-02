<?php

namespace app\index\controller;

use app\index\model\CourseTable;
use app\index\model\Order;
use think\App;

class Room extends Container
{
    public function __construct(App $app = null)
    {
        parent::__construct($app, new \app\index\model\Room());
    }

    /**
     * 显示创建资源表单页.
     *
     * @return \think\Response
     */
    public function create()
    {
        //
    }

    /**
     * 显示编辑资源表单页.
     *
     * @param int $id
     * @return \think\Response
     */
    public function edit($id)
    {
        //
    }

    /**
     * @param $start
     * @param $end
     * @param $weekTimes
     * @param $weekNum
     * @throws \think\exception\DbException
     */
    public function _filter($start, $end, $weekTimes, $weekNum, $bid, $limit = 10, $page = 1)
    {

        $emptyHouse = $this->getEmptyHouse($start, $end, $weekTimes, $weekNum, $bid, $limit, $page);
        return $this->response($emptyHouse[0], "ok", $emptyHouse[1]);
    }

    /**
     * 获取空房间
     * @param $start
     * @param $end
     * @param $weekTimes
     * @param $weekNum
     * @param $city
     * @param int $limit
     * @param int $page
     * @return array|\PDOStatement|string|\think\Collection
     * @throws \think\db\exception\DataNotFoundException
     * @throws \think\db\exception\ModelNotFoundException
     * @throws \think\exception\DbException
     */
    private function getEmptyHouse($start, $end, $weekTimes, $weekNum, $bid, $limit = 10, $page = 1)
    {
//        $cids = \app\index\model\Room::field("room_id")->where("bid", $bid)->buildSql();
        $subQuery0 = CourseTable::where("startTime", "<", $end)
            ->field("cid")
//            ->where("cid in $cids")
            ->where("endTime", ">", $start)
            ->where("weekNum", $weekNum)
            ->where("weekTimes", $weekTimes)
            ->buildSql();
        $subQuery1 = Order::where("startTime", "<", $end)
            ->field("cid")
//            ->where("cid in $cids")
            ->where("endTime", ">", $start)
            ->where("weekNum", $weekNum)
            ->where("weekTimes", $weekTimes)
            ->buildSql();
        $emptyHouse = $this->model
            ->where("room_id", "not in", "( $subQuery0 union $subQuery1)")
            ->where("bid", $bid)
            ->where("limitTime", ">", $end)
            ->field(["room_id as rid", "location as houseNum"])
            ->page($page, $limit)
            ->select();
        $count = $this->model
            ->where("room_id", "not in", "( $subQuery0 union $subQuery1)")
            ->where("bid", $bid)
            ->where("limitTime", ">", $end)
            ->field(["room_id as rid", "location as houseNum"])
            ->count();
        return [$emptyHouse->toArray(), $count];
    }

    public function getRandHouse($start, $end, $weekTimes, $weekNum, $bid, $limit = 5)
    {
        $randHouse = $this->getEmptyHouse($start, $end, $weekTimes, $weekNum, $bid, 100, 1);
        $houseData = $randHouse[0];
        if (!is_int($limit) && $limit > 100) return Error::paramFail("数量限制（ $limit ） 参数错误");
        $data = [];
        $randKey = array_rand($houseData, $limit);
        foreach ($randKey as $key) {
            array_push($data, $houseData[$key]);
        }
        return $this->response($data, "ok", $randHouse[1]);
    }

    public function getTimeTable($cid, $weekTimes)
    {
//        $room = \app\index\model\Room::get($cid);
//        if (is_null($room)) return Error::notFoundField("教室编号出错");
//        $room->getData("limit");
        $courseTable = CourseTable::where("cid", $cid)
            ->field(["*", "'grey' as color", "TIMESTAMPDIFF(MINUTE,startTime,endTime) as length", "((startTime/10000-8)-(startTime/10000-8)mod 1)*60+(startTime/10000-8)mod 1*100  as startHeight", "((endTime/10000-8)-(endTime/10000-8)mod 1)*60+(endTime/10000-8)mod 1*100 as endHeight"])
            ->where("weekTimes", $weekTimes)->buildSql();
        $order = Order::where("cid", $cid)
            ->hidden(["uid"])
            ->field(["*", "'grey' as color", "TIMESTAMPDIFF(MINUTE,startTime,endTime) as length", "((startTime/10000-8)-(startTime/10000-8)mod 1)*60+(startTime/10000-8)mod 1*100  as startHeight", "((endTime/10000-8)-(endTime/10000-8)mod 1)*60+(endTime/10000-8)mod 1*100 as endHeight"])
            ->where("weekTimes", $weekTimes)
            ->union($courseTable)
            ->order("startHeight", "asc")
            ->select();
        return $this->response($order, "ok");
    }
    public function getHouseList($bid, $page = 1, $limit = 20)
    {
        $data = $this->model->field(["room_id as rid", "location as houseNum"])->where("bid", $bid)->page($page, $limit)->select();
        $count = $this->model->where("bid", $bid)->count();
        return $this->response($data, "ok", $count);
    }
}
