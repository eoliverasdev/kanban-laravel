<?php 

namespace App\Models;

use App\Models\User;
use App\Models\Note;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Board extends Model
{
    protected $fillable = ['owner_id', 'title', 'description'];

    /**
     * Un tablero pertenece a un usuario (owner).
     */
    public function owner(): BelongsTo
    {
        return $this->belongsTo(User::class, 'owner_id');
    }

    /**
     * Un tablero tiene muchas notas.
     */
    public function notes(): HasMany
    {
        return $this->hasMany(Note::class);
    }
}