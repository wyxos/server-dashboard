<?php

namespace App\Models;

use App\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

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
class DatabaseUser extends Model
{
    use HasFactory;
}
