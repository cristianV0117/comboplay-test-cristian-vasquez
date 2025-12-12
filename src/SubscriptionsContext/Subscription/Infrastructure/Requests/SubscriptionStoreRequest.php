<?php

declare(strict_types=1);

namespace Src\SubscriptionsContext\Subscription\Infrastructure\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Contracts\Validation\Validator;
use Src\SubscriptionsContext\Subscription\Domain\Exceptions\SubscriptionStoreFailedException;
use Src\Shared\Infrastructure\Helpers\RequestHelper;

final class SubscriptionStoreRequest extends FormRequest
{
    use RequestHelper;

    public function authorize(): bool
    {
        return true;
    }

    protected function prepareForValidation(): void
    {
        $this->merge([
            'user_id' => $this->input('user_id') ? (int) $this->input('user_id') : null,
            'plan_id' => $this->input('plan_id') ? (int) $this->input('plan_id') : null,
        ]);
    }

    public function rules(): array
    {
        return [
            'user_id' => ['required', 'integer', 'min:1'],
            'plan_id' => ['required', 'integer', 'min:1']
        ];
    }

    protected function failedValidation(Validator $validator): void
    {
        $errors = $validator->errors()->all();
        $message = $this->formatErrorsRequest($errors);
        
        throw new SubscriptionStoreFailedException($message, 400);
    }
}