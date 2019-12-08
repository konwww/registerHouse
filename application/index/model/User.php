<?php

namespace app\index\model;

use think\Model;
use think\model\concern\SoftDelete;

class User extends Model
{
    //
    use SoftDelete;
    protected $table = "c_user";
    public $email;
    public $password;
    public $username;
    public $wx_openid;
    public $yiban_openid;
    protected $deleteTime="deleteTime";
    protected $createTime = "createTime";
    protected $updateTime = "updateTime";
    protected $autoWriteTimestamp = "datetime";
}
