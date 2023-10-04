<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;

class Report extends Model
{
    use HasFactory;

    // Có thể điền vào
    protected $fillable = [
        'title',
        'description',
        'location_api',
        'location_text',
        'status',
        'users_id'
    ];

    public function user() : BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function medias() : BelongsToMany
    {
        return $this->belongsToMany(Media::class);
    }

    public function feedbacks() : HasMany
    {
        return $this->hasMany(Feedback::class, 'reports_id', 'id');
    }

    public function assignments() : HasOne
    {
        return $this->hasOne(Assignment::class);
    }
}
