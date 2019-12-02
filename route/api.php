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
];
