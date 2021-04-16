<?php

$arrs = [['phone' => 1], ['phone' => 2], ['phone' => 3], ['phone' => 4], ['phone' => 5], ['phone' => 6]];

function _data_res($arrobj, $num)
{
    $data = [];
    function ttt($i, $arr, $num, &$data)
    {
        $tmp = array_slice($arr, $i, $num);
        if (count($tmp) < $num) {
            if (count($tmp) != 0) $data[] = $tmp;
            return;
        } else {
            echo 1;
            $data[] = $tmp;
            $i = $i + $num;
            ttt($i, $arr, $num, $data);
        }
    }
    ttt($i = 0, $arrobj, $num, $data);
    return $data;
}
$ttt = _data_res($arrs, $num = 2);
print_r($ttt);
