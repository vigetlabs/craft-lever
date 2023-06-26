<?php

namespace viget\lever\models;

use craft\base\Model;

class Settings extends Model
{
    public $apiKey = '';
    public $site = '';

    public function rules(): array
    {
        return [
            [['apiKey', 'site'], 'required'],
        ];
    }
}
