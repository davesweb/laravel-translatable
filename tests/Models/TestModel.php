<?php

namespace Davesweb\LaravelTranslatable\Tests\Models;

use Davesweb\LaravelTranslatable\Traits\HasTranslations;
use Illuminate\Database\Eloquent\Model;

/**
 * @property string $untranslated
 */
class TestModel extends Model
{
    use HasTranslations;

    /**
     * {@inheritdoc}
     */
    public $timestamps = false;
}