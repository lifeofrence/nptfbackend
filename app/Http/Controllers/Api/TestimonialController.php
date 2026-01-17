<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Testimonial;
use Illuminate\Http\Request;

class TestimonialController extends Controller
{
    /**
     * Display approved testimonials.
     */
    public function index()
    {
        $testimonials = Testimonial::approved()
            ->orderBy('created_at', 'desc')
            ->get();

        return response()->json($testimonials);
    }

    /**
     * Store a new testimonial (requires approval).
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|max:255',
            'position' => 'nullable|string|max:255',
            'organization' => 'nullable|string|max:255',
            'content' => 'required|string',
            'rating' => 'required|integer|min:1|max:5',
        ]);

        $testimonial = Testimonial::create($validated);

        return response()->json([
            'message' => 'Testimonial submitted successfully. It will be reviewed before publishing.',
            'data' => $testimonial
        ], 201);
    }

    /**
     * Get all testimonials including pending (Admin only).
     */
    public function all(Request $request)
    {
        $query = Testimonial::query();

        if ($request->has('status')) {
            $query->where('status', $request->status);
        }

        $testimonials = $query->orderBy('created_at', 'desc')->get();

        return response()->json($testimonials);
    }

    /**
     * Update testimonial (Admin only - for approval/rejection).
     */
    public function update(Request $request, $id)
    {
        $testimonial = Testimonial::findOrFail($id);

        $validated = $request->validate([
            'name' => 'sometimes|required|string|max:255',
            'email' => 'sometimes|required|email|max:255',
            'position' => 'sometimes|nullable|string|max:255',
            'organization' => 'sometimes|nullable|string|max:255',
            'content' => 'sometimes|required|string',
            'rating' => 'sometimes|required|integer|min:1|max:5',
            'status' => 'sometimes|required|in:pending,approved,rejected',
        ]);

        $testimonial->update($validated);

        return response()->json([
            'message' => 'Testimonial updated successfully',
            'data' => $testimonial
        ]);
    }

    /**
     * Delete testimonial (Admin only).
     */
    public function destroy($id)
    {
        $testimonial = Testimonial::findOrFail($id);
        $testimonial->delete();

        return response()->json([
            'message' => 'Testimonial deleted successfully'
        ]);
    }
}
