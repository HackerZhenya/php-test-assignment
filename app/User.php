<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Carbon;

class User extends Model
{
    protected $table = 'users';

    protected $attributes = [
        'data' => '{}',
        'geo_location' => '{}'
    ];

    protected $casts = [
        'data' => 'array',
        'geo_location' => 'array',
    ];

    public function getGenderAttribute()
    {
        return $this->attributes['data']['gender'];
    }

    public function setGenderAttribute($value)
    {
        if ($value != null)
            $this->data = array_merge($this->data, ['gender' => $value]);
    }


    public function getDateOfBirthAttribute()
    {
        return Carbon::parse($this->attributes['data']['date_of_birth']);
    }

    public function setDateOfBirthAttribute($value)
    {
        if ($value != null)
            $this->data = array_merge($this->data, ['date_of_birth' => Carbon::parse($value)->format('Y-m-d')]);
    }


    public function getHobbyAttribute()
    {
        return $this->attributes['data']['hobby'];
    }

    public function setHobbyAttribute($value)
    {
        if ($value != null)
            $this->data = array_merge($this->data, ['hobby' => $value]);
    }


    public function getGeoPositionAttribute() {
        return $this->attributes['geo_location'];
    }

    public function setGeoPositionAttribute($value)
    {
        if ($value != null)
            $this->geo_location = array_merge($this->geo_location, $value);
    }


    public function scopeByBirthdayRange(Builder $query, string $from = null, string $to = null)
    {
        if ($from != null && $to != null)
            return $query->whereRaw("to_date(data->>'date_of_birth', 'YYYY-MM-DD') BETWEEN :from AND :to", ['from' => $from, 'to' => $to]);

        if ($from != null && $to == null)
            return $query->whereRaw("to_date(data->>'date_of_birth', 'YYYY-MM-DD') >= :from", ['from' => $from]);

        if ($from == null && $to != null)
            return $query->whereRaw("to_date(data->>'date_of_birth', 'YYYY-MM-DD') <= :to", ['to' => $to]);

        return $query;
    }

    public function scopeByAgeRange(Builder $query, int $from = null, int $to = null)
    {
        $fromDate = $to != null ? Carbon::now()->subYears($to)->format('Y-m-d') : null;
        $toDate = $from != null ? Carbon::now()->subYears($from)->format('Y-m-d') : null;

        return $query->byBirthdayRange($fromDate, $toDate);
    }

    public function scopeByGender(Builder $query, string $gender = null)
    {
        if ($gender == null) return $query;

        return $query->whereRaw("data->>'gender' = :gender", ['gender' => $gender]);
    }

    public function scopeByHobby(Builder $query, array $hobbies = null)
    {
        if ($hobbies == null) return $query;

        $hobbies = '{' . implode(', ', $hobbies) . '}';
        return $query->whereRaw("jsonb_exists_any(data->'hobby', :hobbies) ", ['hobbies' => $hobbies]);
    }

    public function scopeByGeoLocation(Builder $query, array $nw = null, array $se = null) {
        // check for right square
        if ($nw == null && $se == null ||
            $nw['lat'] <= $se['lat'] ||
            $nw['lng'] >= $se['lng'])
            return $query;

        return $query->whereRaw("((geo_location->>'lat')::numeric BETWEEN :se_lat AND :nw_lat) AND ((geo_location->>'lng')::numeric BETWEEN :nw_lng AND :se_lng)", [
            'se_lat' => (double) $se['lat'], 'nw_lat' => (double) $nw['lat'],
            'nw_lng' => (double) $nw['lng'], 'se_lng' => (double) $se['lng'],
        ]);
    }
}