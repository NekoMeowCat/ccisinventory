<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\DB;

class Equipment extends Model
{
    use HasFactory;

    const ITEM_PREFIX = 'ITEM';
    const ITEM_COLUMN = 'item_number';
    const PROPERTY_PREFIX = 'PROP';
    const PROPERTY_COLUMN = 'property_number';
    const CONTROL_PREFIX = 'CTRL';
    const CONTROL_COLUMN = 'control_number';

    protected $fillable = [
        'unit_id',
        'brand_name',
        'item_number',
        'property_number',
        'control_number',
        'category_id',
        'status',
        'date_acquired',
        'supplier',
        'quantity',
        'specification',
        'user_id',
        'facility_id',
    ];

    public function category()
    {
        return $this->belongsTo(Category::class);
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function facility()
    {
        return $this->belongsTo(Facility::class);
    }

    // public static function generateItemNumber()
    // {
    //     $prefix = 'ITEM'; // Customize the prefix
    //     $year = date('Y'); // Current year

    //     try {
    //         // Use a transaction to ensure atomicity and prevent race conditions
    //         return DB::transaction(function () use ($prefix, $year) {
    //             // Retrieve the latest item number for the current year
    //             $latestNumber = self::whereYear('created_at', $year)
    //                 ->where('item_number', 'LIKE', "$prefix-$year-%")
    //                 ->orderBy('item_number', 'desc')
    //                 ->value('item_number'); // Efficiently get the single value

    //             // Default to '001' if no previous number exists
    //             $nextNumber = '001';
    //             if ($latestNumber) {
    //                 // Extract the numeric part and increment
    //                 $latestNumberPart = explode('-', $latestNumber)[2];
    //                 $nextNumber = str_pad((int)$latestNumberPart + 1, 3, '0', STR_PAD_LEFT);
    //             }

    //             return "$prefix-$year-$nextNumber";
    //         });
    //     } catch (\Exception $e) {
    //         // Log the error for debugging
    //         Log::error('Failed to generate item number: ' . $e->getMessage());
    //         // Handle the error (e.g., by returning a fallback value or null)
    //         return null;
    //     }
    // }
    public static function generateUniqueUnitID(): string
    {
        $date = Carbon::now()->format('Ymd'); // Current date in YYYYMMDD format
        $timestamp = Carbon::now()->timestamp; // Current timestamp

        $attempts = 0;
        $maxAttempts = 100; // Define a reasonable maximum number of attempts to avoid infinite loop

        do {
            $sequence = rand(1000, 9999); // Random 4-digit number
            $unitID = sprintf('%s-%d-%d', $date, $timestamp, $sequence);

            $exists = self::where('unit_id', $unitID)->exists();

            $attempts++;
        } while ($exists && $attempts < $maxAttempts);

        if ($attempts >= $maxAttempts) {
            throw new \Exception('Failed to generate a unique unit ID after multiple attempts.');
        }

        return $unitID;
    }

    public static function generateUniqueItemNumber()
    {
        return self::generateUniqueNumber(self::ITEM_PREFIX, self::ITEM_COLUMN);
    }

    public static function generateUniquePropertyNumber()
    {
        return self::generateUniqueNumber(self::PROPERTY_PREFIX, self::PROPERTY_COLUMN);
    }

    public static function generateUniqueControlNumber()
    {
        return self::generateUniqueNumber(self::CONTROL_PREFIX, self::CONTROL_COLUMN);
    }

    private static function generateUniqueNumber($prefix, $columnName)
    {
        $year = date('Y'); // Current year

        try {
            // Use a transaction to ensure atomicity and prevent race conditions
            return DB::transaction(function () use ($prefix, $columnName, $year) {
                // Retrieve the latest number for the current year
                $latestNumber = self::whereYear('created_at', $year)
                    ->where($columnName, 'LIKE', "$prefix-$year-%")
                    ->orderBy($columnName, 'desc')
                    ->value($columnName); // Efficiently get the single value

                // Default to '001' if no previous number exists
                $nextNumber = '001';
                if ($latestNumber) {
                    // Extract the numeric part and increment
                    $latestNumberPart = explode('-', $latestNumber)[2];
                    $nextNumber = str_pad((int)$latestNumberPart + 1, 3, '0', STR_PAD_LEFT);
                }

                return "$prefix-$year-$nextNumber";
            });
        } catch (\Exception $e) {
            // Log the error for debugging
            Log::error('Failed to generate unique number: ' . $e->getMessage());
            // Handle the error (e.g., by returning a fallback value or null)
            return null;
        }
    }
}
