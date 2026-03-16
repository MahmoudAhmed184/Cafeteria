<?php

require_once ROOT . "app/Models/Model.php";

class Room extends Model
{
    protected $table = "rooms";
    protected $columns = ["id", "room_number"];
}
