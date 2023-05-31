<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Inertia\Inertia;

class EmployeeController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $employees = auth()->user()->employees()->latest()->orderBy('name')->get();
        return Inertia::render('Employees', compact('employees'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $services = auth()->user()->services;
        return Inertia::render('CreateEmployee', compact('services'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required',
            'email' => 'required',
            'password' => 'required',
        ]);

        $data = array_merge($request->except('id'), [
            'user_id' => auth()->id(),
            'password' => bcrypt($request->password)
        ]);
        $employee = User::create($data)->assignRole('employee');
        $employee->employments()->sync($request->employments);
        $employee->load('employments');
        return Inertia::render('CreateEmployee', compact('employee'));
    }

    /**
     * Display the specified resource.
     */
    public function show(User $user)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(User $employee)
    {
        $services = $employee->admin->services;
        $employee->load('employments');
        return Inertia::render('CreateEmployee', compact('employee', 'services'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, User $employee)
    {
        $request->validate([
            'name' => 'required',
            'email' => 'required'
        ]);

        $data = array_merge($request->except(['id', 'jobs']), [
            'password' => $request->password ? bcrypt($request->password) : $employee->password
        ]);
        $employee->update($data);
        $employee->employments()->sync($request->employments);
        return to_route('employees.edit', $employee);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(User $user)
    {
        //
    }
}
