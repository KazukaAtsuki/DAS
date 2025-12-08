<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class StackConfig extends Model
{
    use HasFactory;

    protected $table = 'stack_configs';
    protected $guarded = []; // Izinkan semua kolom diisi
}
