<?php

namespace AusbildungMS\Shorty\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Domain extends Model
{
    use HasFactory;

    public $table = 'shorty_domains';

    public function links()
    {
        return $this->hasMany(Link::class);
    }
}