<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class BundleItem extends Model
{
    use HasFactory;

    protected $guarded = ['id'];

    public function bundle()
    {
        return $this->belongsTo(Bundle::class);
    }

    public function menu()
    {
        return $this->belongsTo(Menu::class);
    }
}
