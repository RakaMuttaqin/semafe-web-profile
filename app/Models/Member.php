<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Member extends Model
{
    /** @use HasFactory<\Database\Factories\MemberFactory> */
    use HasFactory;

    protected $fillable = ['division_id', 'nim', 'name', 'position', 'photos', 'status'];

    public function divisions()
    {
        return $this->belongsTo(Division::class);
    }
}
