<?php

function link_db()
{
    static $link;

    $link = new mysqli(HOST, USER, PASSWORD, DATABASE);

    if ($link->connect_errno) {
        echo "Failed to connect to MySQL:" . $link->connect_errno . " - " . $link->connect_error;
    } else {
        $link->set_charset('utf8');
        return $link;
    }
}
