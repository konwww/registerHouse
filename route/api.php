<?php
return [
    "[public]" => [
        "validate" => ["index/Order/validateTime", ["method" => "get"]],
        "register" => ["index/Order/register", ["method" => "get"]],
        "filter" => ["index/Room/_filter"],
        "randHouse" => ["index/Room/getRandHouse"],
        "get-time-table" => ["index/Room/getTimeTable", ["method" => "get"]],
        "get-house-list" => ["index/Room/getHouseList"],
    ],
    "[building]" => [
        "index" => "index/Building/index"
    ]
    , "[user]" => [
        "login" => "index/User/login",
        "available-house" => "index/Order/availableHouseByUser",
        "unavailable-house" => "index/Order/unavailableHouseByUser",
        "change-pwd" => "index/User/changePwd",
        "set-email" => "index/User/setEmail",
        "set-username" => "index/User/setUsername",
        "break-up" => "index/Order/breakUp"
    ]
    , "[oauth]" => [
        "yiban" => "index/User/autoLoginByYiBan",
        "test" => "index/User/test"
    ]
];
