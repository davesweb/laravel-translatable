<?php

namespace Davesweb\LaravelTranslatable\Tests\Models;

use Illuminate\Database\Eloquent\Model;
use Davesweb\LaravelTranslatable\Traits\HasTranslations;

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
