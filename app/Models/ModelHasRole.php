<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Concerns\HasUuids;

use Illuminate\Database\Eloquent\Model;

class ModelHasRole extends Model
{
    use HasFactory, HasUuids;

    protected $table = 'model_has_roles';

    public $incrementing = false;

    protected $keyType = 'string';

    protected $fillable = [
        'model_id',
        'role_id',
        'model_type',
        'team_id',
    ];

    public function teams(){
        return $this->hasMany(Team::class);
    }

}
