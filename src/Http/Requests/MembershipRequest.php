<?php

namespace Botble\Membership\Http\Requests;

use Botble\Support\Http\Requests\Request;
use Illuminate\Validation\Rule;

class MembershipRequest extends Request
{
    public function rules(): array
    {
        return [
            'user_id'    => 'required|integer',
            'plan_id'    => 'required|integer',
            'start_date' => 'required|date',
            'end_date'   => 'required|date|after_or_equal:start_date',
            'status'     => Rule::in(['active', 'inactive', 'pending_payment']),
        ];
    }
}
