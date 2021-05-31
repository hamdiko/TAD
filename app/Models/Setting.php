<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Setting extends Model
{
    use HasFactory;

    protected $guarded = [];

    public function creator()
    {
        return $this->belongsTo(User::class, 'added_by', 'id');
    }

    public function getValueAttribute($value)
    {
        return \json_decode($value, true) ?? $value;
    }

    public function setValueAttribute($value)
    {
        $this->attributes['value'] = \is_array($value) ? \json_encode($value) : $value;
    }

    /**
     * Get the route key for the model.
     *
     * @return string
     */
    public function getRouteKeyName()
    {
        return "key";
    }
}
