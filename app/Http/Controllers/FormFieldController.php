<?php

namespace App\Http\Controllers;

use App\Models\FormField;
use Illuminate\Http\Request;
use Inertia\Inertia;

class FormFieldController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $fields = FormField::where('user_id', request()->user_id)->get();
        return response()->json(['data'=>$fields]);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $formFields = auth()->user()->form_fields;
        return Inertia::render('CreateFormField', compact('formFields'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        foreach($request->form_fields as $field){
            $data = [
                'name' => $field['name'],
                'label'=>$field['label'],
                'required' => $field['required'],
                'index' => $field['index'],
                'is_identifier' => $field['is_identifier'],
            ];
            array_key_exists('id', $field)
            ? FormField::find($field['id'])->update($data)
            : FormField::create(array_merge($data, ['user_id'=>auth()->id()]));
        }

        return to_route('form_fields.create');
    }

    /**
     * Display the specified resource.
     */
    public function show(FormField $formField)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(FormField $formField)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, FormField $formField)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(FormField $formField)
    {
        $formField->delete();
        return to_route('form_fields.create');
    }
}
