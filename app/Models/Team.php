<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Concerns\HasUuids;


class Team extends Model
{
    use HasFactory, HasUuids;

    protected $primaryKey = 'id';

    protected $fillable = [
        'name',
        'description',
    ];

    public $incrementing = false;
}
