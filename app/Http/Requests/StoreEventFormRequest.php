<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Http\JsonResponse;

class StoreEventFormRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [
            'event.title' => 'required',
            'event.type_id' => 'required',
            'event.status_id' => 'required',
            'event.start_at' => 'required',
            'event.duration' => 'required',
            'venue.name' => 'required',
            'venue.latlng.lat' => 'required',
            'venue.latlng.lng' => 'required'
        ];
    }

    /**
     * Get custom messages for validator errors.
     *
     * @return array
     */
    public function messages()
    {
        return [
            'event.title.required' => 'The title cannot be blank.',
            'event.type_id.required' => 'The sport cannot be blank.',
            'event.status_id.required' => 'The status cannot be blank.',
            'event.start_at.required' => 'The start date and time cannot be blank.',
            'event.duration.required' => 'The duration cannot be blank.',
            'venue.name.required' => 'The venue cannot be blank.'
        ];
    }

    /**
     * Get the proper failed validation response for the request.
     *
     * @param  array  $errors
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function response(array $errors)
    {
        // Check if venue name was provided but latitude or longitude is missing
        if (!array_key_exists('venue.name', $errors) &&
            (array_key_exists('venue.latlng.lat', $errors) || array_key_exists('venue.latlng.lng', $errors))) {
                $errors['venue.name'] = 'We didn\'t understand your venue, please select from suggestions that appear when typing.';
        }

        // Don't wanna show these errors
        unset($errors['venue.latlng.lat']);
        unset($errors['venue.latlng.lng']);

        if ($this->expectsJson()) {
            return new JsonResponse($errors, 422);
        }

        return $this->redirector->to($this->getRedirectUrl())
                                        ->withInput($this->except($this->dontFlash))
                                        ->withErrors($errors, $this->errorBag);
    }
}
