<?php

namespace App\Models;

use App\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

/**
 * Columns
 * @property int id
 * @property Carbon|null created_at
 * @property Carbon|null updated_at
 *
 * Relationships
 *
 * Getters
 *
 */
class Database extends Model
{
    use HasFactory;

    public function users(): BelongsToMany
    {
        return $this->belongsToMany(DatabaseUser::class, 'database_access');
    }
}
