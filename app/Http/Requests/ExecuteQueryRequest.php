<?php

namespace App\Http\Requests;


use App\Http\Services\QueryService;
use Illuminate\Foundation\Http\FormRequest;

class ExecuteQueryRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'db_host' => 'required|string',
            'db_database' => 'required|string',
            'db_username' => 'required|string',
            'db_password' => 'required|string',
            'sql_query' => 'required|string',
        ];
    }

    public function withValidator($validator)
    {
        $validator->after(function ($validator) {
            $dbHost = $this->input('db_host');
            $dbDatabase = $this->input('db_database');
            $dbUsername = $this->input('db_username');
            $dbPassword = $this->input('db_password');

            $queryService = new QueryService();
            if (!$queryService->testConnection($dbHost, $dbDatabase, $dbUsername, $dbPassword)) {
                $validator->errors()->add('db_connection', 'Failed to connect to the database.');
            }
        });
    }
}