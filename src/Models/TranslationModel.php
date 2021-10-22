<?php

namespace Davesweb\LaravelTranslatable\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Support\Str;

class TranslationModel extends Model
{
    public function translatesModel(): BelongsTo
    {
        return $this->belongsTo(
            $this->translates,
            $this->primaryKey,
            $this->getTranslatesOwnerKey()
        );
    }
    
    public function getTranslatesModel(): Model
    {
        return $this->translates_model;
    }
    
    private function getTranslatesObject(): Model
    {
        return resolve($this->translates);
    }
    
    private function getTranslatesOwnerKey(): string
    {
        return Str::of($this->translates)->classBasename()->snake() . '_' . $this->getTranslatesObject()->primaryKey;
    }
}
