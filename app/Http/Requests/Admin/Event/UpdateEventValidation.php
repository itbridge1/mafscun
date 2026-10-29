<?php

namespace App\Http\Requests\Admin\Event;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Str;

class UpdateEventValidation extends FormRequest
{
    public function rules(): array
    {
        $eventId = $this->route('event');
        $event   = \App\Models\Event::find($eventId);
        $minSeats = $event ? max(1, $event->bookedSeatsCount()) : 1;

        return [
            'title'             => ['required', 'max:255'],
            'slug'              => ['nullable', 'string', 'max:255'],
            'total_seats'       => ['required', 'integer', 'min:' . $minSeats],
            'google_map'        => ['nullable'],
            'remarks'           => ['nullable'],
            'main_photo'        => ['nullable', 'file', 'mimes:jpg,jpeg,png,gif', 'max:2048'],
            'description'       => ['nullable'],
            'short_description' => ['nullable', 'max:500'],
            'status'            => ['nullable', 'boolean'],
            'event_price'            => ['required', 'numeric', 'min:0'],
            'non_member_price'       => ['nullable', 'numeric', 'min:0'],
            'price_type'             => ['required', 'in:per_person,per_group'],
            'has_group_pricing'      => ['nullable', 'boolean'],
            'group_size'             => ['nullable', 'integer', 'min:1'],
            'group_price'            => ['nullable', 'numeric', 'min:0'],
            'non_member_group_price' => ['nullable', 'numeric', 'min:0'],
            'start_date'             => ['nullable', 'date'],
            'end_date'               => ['nullable', 'date', 'after_or_equal:start_date'],
            'registration_deadline'  => ['nullable', 'date', 'before_or_equal:end_date'],
            'start_date_bs'          => ['nullable', 'string', 'regex:/^[0-9]{4}-[0-9]{2}-[0-9]{2}$/'],
            'end_date_bs'            => ['nullable', 'string', 'regex:/^[0-9]{4}-[0-9]{2}-[0-9]{2}$/'],
            // Routines
            'routines'                  => ['nullable', 'array'],
            'routines.*.date'           => [
                'nullable', 
                'date', 
                'after_or_equal:start_date',
                function ($attribute, $value, $fail) {
                    if (request('end_date')) {
                        if (strtotime($value) > strtotime(request('end_date'))) {
                            $fail('Routine dates cannot be after the event end date.');
                        }
                    }
                }
            ],
            'routines.*.id'                      => ['nullable', 'integer'],
            'routines.*.start_time'              => ['required_unless:routines.*.is_all_day,1'],
            'routines.*.end_time'                => ['required_unless:routines.*.is_all_day,1'],
            'routines.*.about'                   => ['nullable'],
            'routines.*.price'                   => ['required', 'numeric', 'min:0'],
            'routines.*.non_member_price'        => ['nullable', 'numeric', 'min:0'],
            'routines.*.group_size'              => ['nullable', 'integer', 'min:1'],
            'routines.*.group_price'             => ['nullable', 'numeric', 'min:0'],
            'routines.*.non_member_group_price'  => ['nullable', 'numeric', 'min:0'],
            // Custom Questions (array)
            'questions'                 => ['nullable', 'array'],
            'questions.*.id'            => ['nullable', 'integer'],
            'questions.*.title'         => ['required', 'string', 'max:255'],
            'questions.*.type'          => ['required', 'string', 'in:text,number,radio,select,checkbox'],
            'questions.*.options'       => ['nullable', 'string'],
            'questions.*.is_required'   => ['nullable'],
            'questions.*.order'         => ['nullable', 'integer'],
        ];
    }

    public function prepareForValidation(): void
    {
        $eventId = (int)$this->route('event');
        $this->merge([
            'slug' => \App\Models\Event::generateUniqueSlug($this->title ?? '', $eventId),
        ]);
    }

    public function messages(): array
    {
        $eventId = $this->route('event');
        $event   = \App\Models\Event::find($eventId);
        $booked  = $event ? $event->bookedSeatsCount() : 0;
        
        return [
            'total_seats.min' => "Cannot set total seats less than $booked, as $booked seats are already occupied.",
            'start_date_bs.regex' => 'The start date (BS) must be in YYYY-MM-DD format (e.g. 2080-04-15).',
            'end_date_bs.regex' => 'The end date (BS) must be in YYYY-MM-DD format (e.g. 2080-04-20).',
        ];
    }
}
