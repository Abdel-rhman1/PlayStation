<?php

namespace App\Core\Traits;

use App\Models\Shift;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Auth;

trait HasShift
{
    /**
     * Boot the trait to automatically assign shift_id.
     */
    protected static function bootHasShift()
    {
        static::creating(function (Model $model) {
            if (Auth::check() && !$model->shift_id) {
                $activeShift = Auth::user()->shifts()->active()->first();
                if ($activeShift) {
                    $model->shift_id = $activeShift->id;
                }
            }
        });
    }

    /**
     * Relationship to shift.
     */
    public function shift()
    {
        return $this->belongsTo(Shift::class);
    }
}
