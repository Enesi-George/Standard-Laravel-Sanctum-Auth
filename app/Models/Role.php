<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Spatie\Permission\Models\Role as SpatieRole;

class Role extends SpatieRole
{
    use HasFactory;
    use HasUuids;

    protected $primaryKey = 'id';

    public $incrementing = false;

    protected $keyType = 'string';

    public function team()
    {
        return $this->belongsTo(Team::class); //changed to Many depending on the business logic
    }
}
