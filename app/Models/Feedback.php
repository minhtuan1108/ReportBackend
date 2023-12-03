<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

class Feedback extends Model
{
    use HasFactory;

    protected $table = 'feedbacks';
    protected $fillable = [
        'note',
        'users_id',
        'reports_id'
    ];

    public function medias() : BelongsToMany
    {
        return $this->belongsToMany(Media::class, 'feedback_media', 'feedbacks_id', 'media_id');
    }

    public function report() : BelongsTo
    {
        return $this->belongsTo(Report::class, 'reports_id', 'id');
    }

    public function user() : BelongsTo
    {
        return $this->belongsTo(User::class, 'users_id', 'id');
    }

    public function hardDelete(){
        $medias = $this->medias();
        foreach($medias->get() as $media){
            $media->deleteLocalFile();
        }
        $medias->detach();
        $this->delete();
    }
}
