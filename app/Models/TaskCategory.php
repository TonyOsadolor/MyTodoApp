<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Enums\TaskTypeEnum;
use Illuminate\Support\Str;

class TaskCategory extends Model
{
    /**
     * The attributes that are not mass assignable.
     *
     * @var array
     */
    protected $guarded = ['id'];

    /**
     * Assigning UUIDs & Code to New Task Categories
     */
    protected static function boot()
    {
        parent::boot();

        static::creating(function ($model) {
            $model->uuid = Str::uuid();
            $model->code = self::generateCode($model->name);
        });
    }

    public function scopeActive()
    {
        return $this->where('is_active', true);
    }

    /**
     * Generate CODE for Category Creation
     */
    public static function generateCode($modelName)
    {
        $random = rand(1111, 9999);
        $complete = match(true){
            strtolower($modelName) === 'event' => 'EVT'.$random,
            strtolower($modelName) === 'todo'=> 'TOD'.$random,
            strtolower($modelName) === 'task' => 'TSK'.$random,
            strtolower($modelName) === 'reminder' => 'RMR'.$random,
            default => 'DFT'.$random,
        }; 
        
        return strtoupper($complete);
    }
}
