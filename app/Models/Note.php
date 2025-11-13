<?php 

namespace App\Models;

use App\Models\Board;
use App\Models\User;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Note extends Model
{
    protected $fillable = ['board_id', 'title', 'description', 'status', 'position'];

    /**
     * Una nota pertenece a un tablero (board).
     */
    public function board(): BelongsTo
    {
        return $this->belongsTo(Board::class);
    }
}
