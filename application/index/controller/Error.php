<?php


namespace app\index\controller;


use think\Controller;
use think\facade\Response;

class Error extends Controller
{

    static $error = [
        "object_is_null" => ["title" => "对象为空", "code" => 1001],
        "fields_not_found" => ["title" => "错误的键名", "code" => 1002],
        "update_failed" => ["title" => "更新错误", "code" => 1003],
        "save_failed" => ["title" => "保存错误", "code" => 1004],
        "delete_failed" => ["title" => "删除错误", "code" => 1005],
        "login" => ["title" => "未登录", "code" => 1006],
    ];

    private static function response($key, $msg, $status)
    {
        return Response::create(["msg" => $msg, "error" => true, "title" => self::$error[$key]["title"], "status" => $status, "errCode" => self::$error[$key]["code"]], "json");

    }

    public static function objectIsNull($msg)
    {
        return self::response("object_is_null", $msg, 4);
    }

    public static function notFoundField($msg)
    {
        return self::response("fields_not_found", $msg, 4);
    }

    public static function operationFailed($type, $msg)
    {
        return self::response($type . "_failed", $msg, 4);
    }

    public static function paramFail($msg)
    {
        return self::response("param", $msg, 4);
    }

    public static function login($msg)
    {
        return self::response("login", $msg, 4);
    }
}
