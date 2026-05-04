<?php

declare(strict_types=1);

namespace App\Core;

class Validator
{
    private array $errors = [];

    public function validate(array $data, array $rules): bool
    {
        $this->errors = [];

        foreach ($rules as $field => $ruleSet) {
            $fieldRules = is_string($ruleSet) ? explode('|', $ruleSet) : $ruleSet;
            $value = $data[$field] ?? null;

            foreach ($fieldRules as $rule) {
                $params = [];
                if (str_contains($rule, ':')) {
                    [$rule, $paramStr] = explode(':', $rule, 2);
                    $params = explode(',', $paramStr);
                }

                $methodName = 'validate' . ucfirst($rule);
                if (method_exists($this, $methodName)) {
                    if (!$this->$methodName($field, $value, $params, $data)) {
                        break;
                    }
                }
            }
        }

        return empty($this->errors);
    }

    public function getErrors(): array
    {
        return $this->errors;
    }

    public function getFirstError(string $field): ?string
    {
        return $this->errors[$field][0] ?? null;
    }

    private function addError(string $field, string $message): void
    {
        $this->errors[$field][] = $message;
    }

    private function validateRequired(string $field, mixed $value): bool
    {
        if ($value === null || $value === '' || $value === []) {
            $this->addError($field, __("validation.required", ['field' => $field]));
            return false;
        }
        return true;
    }

    private function validateEmail(string $field, mixed $value): bool
    {
        if ($value && !filter_var($value, FILTER_VALIDATE_EMAIL)) {
            $this->addError($field, __("validation.email", ['field' => $field]));
            return false;
        }
        return true;
    }

    private function validateMin(string $field, mixed $value, array $params): bool
    {
        $min = (int) $params[0];
        if (is_string($value) && strlen($value) < $min) {
            $this->addError($field, __("validation.min", ['field' => $field, 'min' => $min]));
            return false;
        }
        return true;
    }

    private function validateMax(string $field, mixed $value, array $params): bool
    {
        $max = (int) $params[0];
        if (is_string($value) && strlen($value) > $max) {
            $this->addError($field, __("validation.max", ['field' => $field, 'max' => $max]));
            return false;
        }
        return true;
    }

    private function validateConfirmed(string $field, mixed $value, array $params, array $data): bool
    {
        $confirmField = $field . '_confirmation';
        if ($value !== ($data[$confirmField] ?? null)) {
            $this->addError($field, __("validation.confirmed", ['field' => $field]));
            return false;
        }
        return true;
    }

    private function validateNumeric(string $field, mixed $value): bool
    {
        if ($value && !is_numeric($value)) {
            $this->addError($field, __("validation.numeric", ['field' => $field]));
            return false;
        }
        return true;
    }

    private function validateUrl(string $field, mixed $value): bool
    {
        if ($value && !filter_var($value, FILTER_VALIDATE_URL)) {
            $this->addError($field, __("validation.url", ['field' => $field]));
            return false;
        }
        return true;
    }

    private function validateIn(string $field, mixed $value, array $params): bool
    {
        if ($value && !in_array($value, $params, true)) {
            $this->addError($field, __("validation.in", ['field' => $field]));
            return false;
        }
        return true;
    }

    private function validateUnique(string $field, mixed $value, array $params): bool
    {
        if (!$value) {
            return true;
        }
        $table = $params[0];
        $column = $params[1] ?? $field;
        $exceptId = $params[2] ?? null;

        $app = App::getInstance();
        $db = $app->getDb();

        $sql = "SELECT COUNT(*) as cnt FROM {$table} WHERE {$column} = ?";
        $queryParams = [$value];

        if ($exceptId) {
            $sql .= " AND id != ?";
            $queryParams[] = $exceptId;
        }

        $result = $db->queryOne($sql, $queryParams);
        if (($result['cnt'] ?? 0) > 0) {
            $this->addError($field, __("validation.unique", ['field' => $field]));
            return false;
        }
        return true;
    }
}
