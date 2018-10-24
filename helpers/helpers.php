<?php

function array_filter_nulls(array $array) : array
{
    return array_filter($array, function ($value) {
        return $value !== null;
    });
}
