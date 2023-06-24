<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Property;
use Property as GlobalProperty;

class PropertyController extends Controller
{
    // Method to list all properties
    public function index()
    {
        $properties = GlobalProperty::all();
        return view('properties.index', ['properties' => $properties]);
    }

    // Method to show a specific property
    public function show($id)
    {
        $property = GlobalProperty::find($id);
        return view('properties.show', ['property' => $property]);
    }

    // Method to create a new property
    public function create()
    {
        return view('properties.create');
    }

    // Method to store the newly created property
    public function store(Request $request)
    {
        $property = new GlobalProperty;
        $property->name = $request->input('name');
        $property->description = $request->input('description');
        $property->price = $request->input('price');
        $property->save();

        return redirect()->route('properties.index')->with('success', 'Property created successfully!');
    }

    // Method to edit an existing property
    public function edit($id)
    {
        $property = GlobalProperty::find($id);
        return view('properties.edit', ['property' => $property]);
    }

    // Method to update an existing property
    public function update(Request $request, $id)
    {
        $property = GlobalProperty::find($id);
        $property->name = $request->input('name');
        $property->description = $request->input('description');
        $property->price = $request->input('price');
        $property->save();

        return redirect()->route('properties.index')->with('success', 'Property updated successfully!');
    }

    // Method to delete an existing property
    public function destroy($id)
    {
        $property = GlobalProperty::find($id);
        $property->delete();

        return redirect()->route('properties.index')->with('success', 'Property deleted successfully!');
    }
}
