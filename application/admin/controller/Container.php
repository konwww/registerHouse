<?php

namespace app\admin\controller;

use app\index\controller\Error;
use think\App;
use think\Controller;
use think\Request;
use think\Response;

class Container extends Controller
{
    public $model;
    public $map = [];

    public function __construct(App $app = null)
    {
        parent::__construct($app);
    }

    /**
     * 校验待修改字段是否合法
     * @param $field_list
     * @return mixed|null
     */
    public function fieldValidate($field_list)
    {
        $table_fields_list = $this->model->getTableFields();
        foreach ($field_list as $val) {
            if (!array_key_exists($val, $table_fields_list)) return $this->map[$val];
        }
        return null;
    }

    /**
     * 统一输出
     * @param $data
     * @param $msg
     * @param int $count
     * @return Response
     */
    public function response($data, $msg, $count = 0)
    {
        return Response::create(["status" => 0, "data" => $data, "msg" => $msg, "error" => false, "count" => $count], "json");
    }


    /**
     * 显示资源列表
     *
     * @param null $exp
     * @param int $page
     * @param int $limit
     * @return \think\Response
     */
    public function index($exp = null, $page = 1, $limit = 20)
    {
        //
        $exp = is_null($exp) ? null : json_decode($exp, true);
        if ($page >= 1) {
            $result = $this->model->where($exp)->page($page, $limit)->select();
            $count = $this->model->count("*");
        } else {
            $result = $this->model->where($exp)->select();
            $count = count($result);
        }
        return $this->response($result, "ok", $count);

    }

    /**
     * 保存新建的资源
     *
     * @param \think\Request $request
     * @return \think\Response
     */
    public function save(Request $request)
    {
        //
        $data = $request->get();
        $result = $this->fieldValidate(array_keys($data));
        if (!is_null($result)) return Error::notFoundField("$result 错误");
        $result = $this->model->save($data);
        return $this->response($result, "ok");
    }

    /**
     * 显示指定的资源
     *
     * @param int $id
     * @return \think\Response
     */
    public function read($id)
    {
        //
        $result = $this->model->find($id);
        if (is_null($result)) return Error::objectIsNull("数据未找到");
        return Response::create(["status" => 0, "data" => $result->getData(), "msg" => "ok", "error" => false], "json");
    }

    /**
     * 保存更新的资源
     *
     * @param \think\Request $request
     * @param int $id
     * @return \think\Response
     */
    public function update(Request $request, $id)
    {
        //
        $data = $request->post();
        $result = $this->fieldValidate(array_keys($data));
        if ($result) return Error::notFoundField("$result 错误");
        $result = $this->model->save($data, $id);
        if ($result) return $this->response($result, "更新成功");
        else return Error::operationFailed("update", "参数错误，更新失败");
    }

    /**
     * 删除指定资源
     *
     * @param int $id
     * @return \think\Response
     */
    public function delete($id)
    {
        $result = $this->model->delete($id);
        if ($result) return $this->response($result, "删除成功");
        else return Error::operationFailed("delete", "记录不存在或者记录已删除");
    }

}
