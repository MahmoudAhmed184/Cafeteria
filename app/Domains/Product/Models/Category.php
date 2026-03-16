<?php

require_once ROOT . "app/Models/Model.php";

class Category extends Model
{
    protected $table = "categories";
    protected $columns = ["id", "name"];
}
