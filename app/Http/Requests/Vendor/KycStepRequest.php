<?php

namespace App\Http\Requests\Vendor;

use Illuminate\Foundation\Http\FormRequest;

class KycStepRequest extends FormRequest
{
    public function authorize(): bool
    {
        return auth('vendor')->check();
    }

    public function rules(): array
    {
        $step = (int) $this->input('step', 1);

        return match ($step) {
            1 => [
                'company_name'        => 'required|string|max:300',
                'business_type'       => 'required|in:manufacturer,wholesaler,trader,retailer,distributor',
                'year_established'    => 'nullable|integer|min:1900|max:' . date('Y'),
                'company_website'     => 'nullable|url|max:300',
                'company_description' => 'nullable|string|max:2000',
            ],
            2 => [
                'gstin'      => ['required', 'string', 'max:20', 'regex:/^[0-9]{2}[A-Z]{5}[0-9]{4}[A-Z]{1}[1-9A-Z]{1}Z[0-9A-Z]{1}$/i'],
                'pan_number' => ['required', 'string', 'max:20', 'regex:/^[A-Z]{5}[0-9]{4}[A-Z]{1}$/i'],
            ],
            3 => [
                'account_holder_name' => 'required|string|max:200',
                'account_number'      => 'required|string|min:9|max:30',
                'bank_name'           => 'required|string|max:200',
                'branch_name'         => 'nullable|string|max:200',
                'ifsc_code'           => ['required', 'string', 'max:20', 'regex:/^[A-Z]{4}0[A-Z0-9]{6}$/i'],
                'account_type'        => 'required|in:current,savings',
            ],
            4 => [
                'pan_document'               => 'nullable|file|mimes:pdf,jpg,jpeg,png|max:5120',
                'gstin_document'             => 'nullable|file|mimes:pdf,jpg,jpeg,png|max:5120',
                'cancelled_cheque_document'  => 'nullable|file|mimes:pdf,jpg,jpeg,png|max:5120',
                'trade_license_document'     => 'nullable|file|mimes:pdf,jpg,jpeg,png|max:5120',
            ],
            default => [],
        };
    }

    public function messages(): array
    {
        return [
            'gstin.regex'      => 'Please enter a valid 15-character Indian GSTIN (e.g., 27AAAAA0000A1Z5).',
            'pan_number.regex' => 'Please enter a valid 10-character Permanent Account Number (e.g., ABCDE1234F).',
            'ifsc_code.regex'  => 'Please enter a valid 11-character Indian Bank IFSC Code (e.g., HDFC0001234).',
            '*.max'            => 'Uploaded files must not exceed 5MB in size.',
            '*.mimes'          => 'Uploaded documents must be in PDF, JPG, JPEG, or PNG format.',
        ];
    }
}
