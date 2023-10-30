<?php

namespace common\components;

use libphonenumber\NumberParseException;
use libphonenumber\PhoneNumberFormat;
use libphonenumber\PhoneNumberUtil;
use Yii;
use yii\base\Model;
use yii\base\NotSupportedException;
use yii\validators\Validator;

class PhoneValidator extends Validator
{
    public int $format = PhoneNumberFormat::E164;

    public function init(): void
    {
        parent::init();
        if ($this->message === null) {
            $this->message = Yii::t('app', 'Значение «{attribute}» не является правильным номером телефона.');
        }
    }

    public function validate($value, &$error = null, mixed &$newValue = null): bool
    {
        $result = $this->validateValue($value, $newValue);
        if (empty($result)) {
            return true;
        }

        list($message, $params) = $result;
        $params['attribute'] = Yii::t('yii', 'the input value');
        if (is_array($value)) {
            $params['value'] = 'array()';
        } elseif (is_object($value)) {
            $params['value'] = 'object';
        } else {
            $params['value'] = $value;
        }
        $error = $this->formatMessage($message, $params);

        return false;
    }

    /**
     * @param Model $model
     * @param string $attribute
     * @return void
     * @throws NotSupportedException
     */
    public function validateAttribute($model, $attribute): void
    {
        $result = $this->validateValue($model->$attribute, $newValue);
        if ($result !== null) {
            $this->addError($model, $attribute, $result[0], $result[1]);
            return;
        }
        $model->$attribute = $newValue;
    }

    /**
     * @param string $value
     * @param string|null &$newValue
     * @return array|null
     */
    protected function validateValue($value, mixed &$newValue = null): ?array
    {
        if (!is_string($value)) {
            return [$this->message, []];
        }

        $newValue = $value;

        $phoneUtil = PhoneNumberUtil::getInstance();
        try {
            $proto = $phoneUtil->parse($value, 'RU');

            if (!$phoneUtil->isValidNumber($proto)) {
                return [$this->message, []];
            }

            $regionCode = $phoneUtil->getRegionCodeForNumber($proto);
            if ($regionCode !== 'RU') {
                return [$this->message, []];
            }

            $formatted = $phoneUtil->format($proto, $this->format);

            $newValue = $formatted;
        } catch (NumberParseException $e) {
            return [$this->message, []];
        }

        return null;
    }
}