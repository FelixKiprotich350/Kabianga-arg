<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreProposalRequest extends FormRequest
{
    public function authorize()
    {
        return true;
    }

    public function rules()
    {
        $rules = [
            'proposaltype' => 'required|in:research,innovation',
            'grantnofk' => 'required|integer',
            'departmentidfk' => 'required|string',
            'themefk' => 'required|integer',
            'proposaltitle' => 'nullable|string',
            'commencingdate' => 'nullable|date',
            'terminationdate' => 'nullable|date',
            'objectives' => 'nullable|string',
            'hypothesis' => 'nullable|string',
            'significance' => 'nullable|string',
            'ethicals' => 'nullable|string',
            'expoutput' => 'nullable|string',
            'socio_impact' => 'nullable|string',
        ];

        if ($this->proposaltype === 'innovation') {
            $rules = array_merge($rules, [
                'gap' => 'required|string',
                'solution' => 'required|string',
                'targetcustomers' => 'required|string',
                'valueproposition' => 'required|string',
                'competitors' => 'required|string',
                'attraction' => 'required|string',
                'innovation_teams' => 'required|array|min:1',
                'innovation_teams.*.name' => 'required|string',
                'innovation_teams.*.contacts' => 'required|string',
                'innovation_teams.*.role' => 'required|string',
            ]);
        } else {
            $rules = array_merge($rules, [
                'gap' => 'nullable',
                'solution' => 'nullable',
                'targetcustomers' => 'nullable',
                'valueproposition' => 'nullable',
                'competitors' => 'nullable',
                'attraction' => 'nullable',
            ]);
        }

        return $rules;
    }

    public function messages()
    {
        return [
            'gap.required' => 'Gap field is required for innovation proposals.',
            'solution.required' => 'Solution field is required for innovation proposals.',
            'targetcustomers.required' => 'Target customers field is required for innovation proposals.',
            'valueproposition.required' => 'Value proposition field is required for innovation proposals.',
            'competitors.required' => 'Competitors field is required for innovation proposals.',
            'attraction.required' => 'Attraction field is required for innovation proposals.',
            'innovation_teams.required' => 'Innovation team is required for innovation proposals.',
            'innovation_teams.min' => 'At least one team member is required for innovation proposals.',
        ];
    }
}