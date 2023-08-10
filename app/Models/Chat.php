<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Spatie\MediaLibrary\HasMedia;
use Spatie\MediaLibrary\InteractsWithMedia;
use Laravel\Scout\Searchable;
use Illuminate\Database\Eloquent\SoftDeletes;

class Chat extends Model implements HasMedia
{
    use InteractsWithMedia, SoftDeletes, Searchable, HasFactory;

    protected $fillable = [
        'from_user_id',
        'to_user_id',
        'message',
        'message_status',
        'deleted_by_user_id'
    ];

    protected $guarded = [];
    public function toSearchableArray()
    {
        return [
            'message' => $this->message,
        ];
    }
}
