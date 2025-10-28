<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\State;
use Maatwebsite\Excel\Facades\Excel;
use App\Imports\StatesImport;
use Illuminate\Support\Facades\DB;

class StateController extends Controller
{

    public function index()
    {
        $states = State::paginate(20);
        return view('backend.states.index', compact('states'));
    }

    public function apiIndex(): \Illuminate\Http\JsonResponse
    {
        $states = State::pluck('name', 'state_code')->all();
        return response()->json($states);
    }

    public function create()
    {
        return view('backend.states.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'state_code' => 'required|string|max:255|unique:states,state_code',
            'name' => 'required|string|max:255',
        ]);

        try {
            State::create([
                'state_code' => $request->input('state_code'),
                'name' => $request->input('name'),
            ]);

            return redirect()->route('states.index')->with('success', 'State added successfully!');
        } catch (\Exception $e) {
            return redirect()->back()->with('error', 'An error occurred while adding the state: ' . $e->getMessage())->withInput();
        }
    }


    public function showUploadForm()
    {
        return view('backend.states.upload');
    }


    public function import(Request $request)
    {
        $request->validate([
            'excel_file' => 'required|mimes:xlsx,xls,csv|max:2048', // Max 2MB file size
        ]);

        try {
            // Disable foreign key checks before truncating (if needed, depends on your FK setup)
            DB::statement('SET FOREIGN_KEY_CHECKS=0;');
            State::truncate(); // Truncate the table before importing new data
            DB::statement('SET FOREIGN_KEY_CHECKS=1;');

            // Import the data using your StatesImport class
            Excel::import(new StatesImport, $request->file('excel_file'));

            return redirect()->back()->with('success', 'State data imported successfully!');
        } catch (\Maatwebsite\Excel\Validators\ValidationException $e) {
            $failures = $e->failures();
            $errors = [];
            foreach ($failures as $failure) {
                $errors[] = 'Row ' . $failure->row() . ': ' . implode(', ', $failure->errors());
            }
            return redirect()->back()->withErrors($errors)->withInput();
        } catch (\Exception $e) {
            return redirect()->back()->with('error', 'An error occurred during import: ' . $e->getMessage());
        }
    }


    public function destroyAll()
    {
        try {
            // Disable foreign key checks if you have related tables
            DB::statement('SET FOREIGN_KEY_CHECKS=0;');
            State::truncate(); // Delete all records from the states table
            DB::statement('SET FOREIGN_KEY_CHECKS=1;');

            return redirect()->back()->with('success', 'All state data deleted successfully!');
        } catch (\Exception $e) {
            return redirect()->back()->with('error', 'An error occurred while deleting state data: ' . $e->getMessage());
        }
    }

    public function edit(State $state)
    {
        return view('backend.states.edit', compact('state'));
    }

    public function update(Request $request, State $state)
    {
        $request->validate([
            'name' => 'required|string|max:255|unique:states,name,' . $state->state_code . ',state_code',
        ]);

        try {
            $state->update([
                'name' => $request->input('name'),
            ]);

            return redirect()->route('states.index')->with('success', 'State updated successfully!');
        } catch (\Exception $e) {
            return redirect()->back()->with('error', 'An error occurred while updating the state: ' . $e->getMessage());
        }
    }

    public function destroy(State $state)
    {
        try {
            $state->delete();
            return redirect()->back()->with('success', 'State deleted successfully!');
        } catch (\Exception $e) {
            return redirect()->back()->with('error', 'An error occurred while deleting the state: ' . $e->getMessage());
        }
    }
}
