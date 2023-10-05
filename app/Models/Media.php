<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

class Media extends Model
{
    use HasFactory;

    protected $fillable = [
        'media_link'
    ];

    public function feedbacks() : BelongsToMany
    {
        return $this->belongsToMany(Feedback::class, 'feedback_media', 'media_id', 'feedbacks_id');
    }

    public function reports() : BelongsToMany
    {
        return $this->belongsToMany(Report::class, 'report_media', 'media_id', 'reports_id');
    }
}
