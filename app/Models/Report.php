<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Casts\Attribute;

class Report extends Model
{
    protected $fillable = [
        'user_id', 'category_id', 'code', 'title',
        'description', 'location', 'latitude', 'longitude',
        'photo_damage', 'village_head_letter', 'status',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function category()
    {
        return $this->belongsTo(Category::class);
    }

    public function responses()
    {
        return $this->hasMany(Response::class);
    }

    public function budget()
    {
        return $this->hasOne(Budget::class);
    }

    public function getStatusLabelAttribute(): string
    {
        return match($this->status) {
            'pending'   => 'Menunggu',
            'process'   => 'Diproses',
            'completed' => 'Selesai',
            'rejected'  => 'Ditolak',
            default     => 'Unknown',
        };
    }

    public function getStatusColorAttribute(): string
    {
        return match($this->status) {
            'pending'   => 'yellow',
            'process'   => 'blue',
            'completed' => 'green',
            'rejected'  => 'red',
            default     => 'gray',
        };
    }

    public function getPhotoDamageUrlAttribute(): ?string
    {
        return $this->photo_damage ? asset('storage/' . $this->photo_damage) : null;
    }

    /**
     * Generate unique ticket code: RPT-YYYYMMDD-XXXX
     */
    public static function generateCode(): string
    {
        $date = now()->format('Ymd');
        $count = static::whereDate('created_at', today())->count() + 1;
        return 'RPT-' . $date . '-' . str_pad($count, 4, '0', STR_PAD_LEFT);
    }
}
