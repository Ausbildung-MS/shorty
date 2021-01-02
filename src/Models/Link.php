<?php

namespace AusbildungMS\Shorty\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;

class Link extends Model
{
    use HasFactory;

    public $table = 'shorty_links';

    protected $fillable = ['uuid', 'short', 'destination', 'description', 'title', 'redirect_status'];

    public function visits()
    {
        return $this->hasMany(LinkVisit::class);
    }

    public function domain()
    {
        return $this->belongsTo(Domain::class);
    }

    public function owner()
    {
        return $this->morphTo();
    }

    public function getNameAttribute()
    {
        return $this->title ?? $this->destination;
    }

    public function getFullUrlAttribute()
    {
        $domain = $this->domain;

        if ($domain) {
            return $domain->domain . '/' . $this->short;
        }

        return config('shorty.root_domain') . Str::replaceFirst('{link:short}', $this->short, config('shorty.root_route'));
    }

    public function setShortAttribute($val) {
        $this->attributes['short'] = Str::slug($val);
    }

    public static function createForDestination(string $destination): self
    {
        return self::create([
            'destination' => $destination
        ]);
    }
}