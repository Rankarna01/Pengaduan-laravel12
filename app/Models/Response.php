<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Response extends Model
{
    protected $fillable = ['report_id', 'admin_id', 'message', 'photo_repair'];

    public function report()
    {
        return $this->belongsTo(Report::class);
    }

    public function admin()
    {
        return $this->belongsTo(User::class, 'admin_id');
    }

    public function getPhotoRepairUrlAttribute(): ?string
    {
        return $this->photo_repair ? asset('storage/' . $this->photo_repair) : null;
    }
}
