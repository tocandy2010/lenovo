<?php

namespace App\Http\Models;

use Illuminate\Database\Eloquent\Model;

class GoodsModel extends Model
{
    protected $table = 'goods';
    protected $primarykey = 'id';
    public $timestamps = false;

    public function orders() {
        return $this->hasMany(OrdersModel::class,'gid');
    }

    public function scopeNumber($query)
    {
        return $query->where('number', 90);
    }
}
