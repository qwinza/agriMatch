<?php

namespace App\Models\Traits;

use Illuminate\Support\Facades\Crypt;

trait HasEncryptionId
{
    /**
     * Encrypt ID
     */
    public function getEncryptedIdAttribute()
    {
        return Crypt::encrypt($this->id);
    }

    /**
     * Find by encrypted ID
     */
    public static function findByEncryptedId($encryptedId)
    {
        try {
            $id = Crypt::decrypt($encryptedId);
            return static::find($id);
        } catch (\Exception $e) {
            return null;
        }
    }

    /**
     * Find or fail by encrypted ID
     */
    public static function findByEncryptedIdOrFail($encryptedId)
    {
        $model = static::findByEncryptedId($encryptedId);
        
        if (!$model) {
            abort(404, class_basename(static::class) . ' tidak ditemukan');
        }
        
        return $model;
    }

    /**
     * Check if string is encrypted ID
     */
    public static function isEncryptedId($value)
    {
        if (!is_string($value)) {
            return false;
        }
        
        return preg_match('/^eyJpdiI6/', $value);
    }
}