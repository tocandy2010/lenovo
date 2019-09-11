<?php

namespace App\Http\Models;

use Illuminate\Database\Eloquent\Model;

class OrdersModel extends Model
{
    protected $table = 'orders';
    protected $primarykey = 'id';
    public $timestamps = false;

    public function goods() {
        return $this->belongsTo(GoodsModel::class,'gid');
    }
}
