<?php
function pr($array) {
    echo "<pre>";
    print_r($array);
    echo "</pre>";
}

function prx($array) {
    echo "<pre>";
    print_r($array);
    exit;
}