<?php


namespace App\Models;


use Illuminate\Database\Eloquent\Model;

class MenuItem extends Model
{

    public function parent() {
        return $this->belongsTo(self::class,'parent_id')->where('parent_id',0);
    }

    public function children() {
        return $this->hasMany(self::class, 'parent_id', 'id')->with('children');
    }
}
