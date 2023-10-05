<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

class Feedback extends Model
{
    use HasFactory;

    protected $table = 'Feedbacks';
    protected $fillable = [
        'note',
        'users_id',
        'reports_id'
    ];

    public function medias() : BelongsToMany
    {
        return $this->belongsToMany(Media::class, 'feedback_media', 'feedbacks_id', 'media_id');
    }
}
