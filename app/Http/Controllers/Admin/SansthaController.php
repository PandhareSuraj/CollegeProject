<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Sanstha;

class SansthaController extends Controller
{
    public function create()
    {
        return view('admin.sansthas.create');
    }

    public function store(Request $request)
    {
        // If multi_add is enabled, parse textarea lines and create multiple records
        if ($request->input('multi_add')) {
            $lines = preg_split('/\r?\n/', $request->input('multi_names', ''));
            $created = 0;
            foreach ($lines as $line) {
                $line = trim($line);
                if (empty($line)) continue;

                // allow optional description after a '|' character
                $parts = explode('|', $line, 2);
                $name = trim($parts[0]);
                $desc = isset($parts[1]) ? trim($parts[1]) : null;

                if (empty($name)) continue;

                Sanstha::create(['name' => $name, 'description' => $desc]);
                $created++;
            }

            return redirect()->route('admin.college-section.sanstha')->with('success', "$created sanstha(s) created");
        }

        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'description' => 'nullable|string',
        ]);

        Sanstha::create($validated);
        return redirect()->route('admin.college-section.sanstha')->with('success','Sanstha created');
    }
}
