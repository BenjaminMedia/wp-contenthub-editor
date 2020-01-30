<?php


namespace Bonnier\WP\ContentHub\Editor\Models;


use Bonnier\WP\ContentHub\Editor\Database\DB;
use Illuminate\Database\Eloquent\Model;

class EloquentModel extends Model
{
    public function __construct(array $attributes = [])
    {
        $this->connection = DB::instance()->getName();
        parent::__construct($attributes);
    }
}