<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

/**
 * Eloquent model for GuestBook
 *
 * @author brooksie
 */
class GuestBook extends Eloquent
{
    public $timestamps = false;
    
    /**
     * Override boot() to add functionality to add created_at timestamp, as oppose
     * to setting $this->timestamps = true which will also try to set updated_at
     */
    public static function boot()
    {
        parent::boot();
        
        static::creating(function($model) {
            $model->created_at = $model->freshTimestamp();
        });
    }
    
    /*
     * @var $guearded array, prevent this field being set manually
     */
     protected $guarded = ['created_at'];      
        
    
}
