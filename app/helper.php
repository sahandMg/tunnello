<?php

function channelId($num1, $num2) {
    if ($num1 > $num2) {
        return \Vinkla\Hashids\Facades\Hashids::encode("$num2$num1");
    }
    else {
        return \Vinkla\Hashids\Facades\Hashids::encode("$num1$num2");
    }
}