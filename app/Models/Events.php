<?php

namespace App\Models;

use Database\Factories\EventsFactory;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Events extends Model
{
    /** @use HasFactory<EventsFactory> */
    use HasFactory;

    protected $fillable = [
        'title',
        'slug',
        'description',
        'image',
        'location',
        'start_at',
        'end_at',
        'status',
    ];
}
