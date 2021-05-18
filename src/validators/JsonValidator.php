<?php

namespace augmentations\craft\json\validators;

use craft\helpers\Json;
use Throwable;
use yii\validators\Validator;

/**
 * Class JsonValidator
 *
 * @package augmentations\craft\json\validators
 */
class JsonValidator extends Validator
{
    /**
     * @param mixed $value
     *
     * @return array|null
     */
    protected function validateValue($value): ?array
    {
        if (empty($value)) {
            return null;
        }

        if (!is_string($value)) {
            return null;
        }

        try {
            Json::decode($value);
        } catch (Throwable $exception) {
            // throw $exception;
            return [
                'Invalid JSON input for "{attribute}". {exception}',
                ["exception" => $exception->getMessage()],
            ];
        }

        return null;
    }
}
