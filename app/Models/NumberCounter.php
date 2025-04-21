<?php

namespace App\Models;


use Illuminate\Support\Facades\Log;
use MongoDB\Laravel\Eloquent\Model;

class NumberCounter extends Model
{
    protected $connection = 'mongodb';

    protected $fillable = [
        'table_name',
        'current_number',
    ];

    public $timestamps = false;

    /**
     * Increment the current number for the given table name.
     *
     * @param string $table_name
     * @return int
     */
    public static function incrementNumber(string $table_name): void
    {
        try {
            $counter = self::where('table_name', $table_name)->first();

            if (!$counter) {
                $counter = new self();
                $counter->table_name = $table_name;
                $counter->current_number = 1;
            }

            $counter->current_number++;
            $counter->save();
        } catch (\Throwable $th) {
            Log::error("message: {$th->getMessage()}");
        }
    }

    /**
     * Get the current number for the given table name.
     *
     * @param string $table_name
     * @return int
     */
    public static function getCurrentNumber(string $table_name): int
    {
        try {
            $counter = self::where('table_name', $table_name)->first();

            if (!$counter) {
                $counter = new self();
                $counter->table_name = $table_name;
                $counter->current_number = 0;
                $counter->save();
            }

            return $counter->current_number;
        } catch (\Throwable $th) {
            Log::error("message: {$th->getMessage()}");
            return 0;
        }
    }
    /**
     * Get the current number for the given table name and increment it
     *
     * @param string $table_name
     * @return int
     */
    public static function getCurrentIncrementNumber(string $table_name): int
    {
        try {
            $counter = self::where('table_name', $table_name)->first();

            if (!$counter) {
                $counter = new self();
                $counter->table_name = $table_name;
                $counter->current_number = 1;
                $counter->save();
            }
            $current = $counter->current_number;
            $counter->current_number++;
            $counter->save();
            return $current;
        } catch (\Throwable $th) {
            Log::error("message: {$th->getMessage()}");
            return 0;
        }
    }
}
