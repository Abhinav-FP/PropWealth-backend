<?php

namespace App\Http\Controllers;

use App\Models\UserDownloadLimit;
use Illuminate\Http\Request;

class UserDownloadLimitController extends Controller
{

    public function index()
    {
        $limit = UserDownloadLimit::firstOrCreate(
            [],
            [
                'daily_limit' => 5,
                'lifetime_limit' => 20
            ]
        );

        return view('backend.download.download-limit', compact('limit'));
    }

    /**
     * Update the single user download limit in storage.
     * This will update the existing record or create it if it doesn't exist.
     */
    public function update(Request $request)
    {
        $validatedData = $request->validate([
            'daily_limit' => 'required|integer|min:0',    // Validation for daily limit
            'lifetime_limit' => 'required|integer|min:0|gte:daily_limit', // Validation for lifetime limit
        ]);

        // Find the first record (assumed to be the single global limit)
        // If it doesn't exist, create it.
        $limit = UserDownloadLimit::firstOrCreate([]);

        // Update the found or newly created record
        $limit->update($validatedData);

        return redirect()->back()->with('success', 'Download limit updated successfully!');
    }
}
