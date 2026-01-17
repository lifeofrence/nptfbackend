<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Project;
use App\Services\CloudinaryService;
use Illuminate\Http\Request;

class ProjectController extends Controller
{
    /**
     * Display all projects.
     */
    public function index(Request $request)
    {
        $query = Project::query();

        // Filter by category
        if ($request->has('category')) {
            $query->where('category', $request->category);
        }

        // Filter by status
        if ($request->has('status')) {
            $query->where('status', $request->status);
        }

        $projects = $query->orderBy('start_date', 'desc')->get();

        return response()->json($projects);
    }

    /**
     * Store a new project.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'description' => 'required|string',
            'category' => 'required|string|max:255',
            'status' => 'required|in:completed,ongoing,upcoming',
            'location' => 'required|string|max:255',
            'start_date' => 'required|date',
            'end_date' => 'nullable|date|after_or_equal:start_date',
            'image' => 'nullable|image|max:5120',
            'impact' => 'nullable|string|max:255',
        ]);

        $project = new Project($validated);

        // Handle image upload to Cloudinary
        if ($request->hasFile('image')) {
            try {
                $cloudinary = app(CloudinaryService::class);
                $result = $cloudinary->uploadImage($request->file('image'), 'projects');
                $project->image_url = $result['url'];
                $project->image_public_id = $result['public_id'];
            } catch (\Exception $e) {
                return response()->json([
                    'message' => 'Failed to upload image',
                    'error' => $e->getMessage()
                ], 500);
            }
        }

        $project->save();

        return response()->json([
            'message' => 'Project created successfully',
            'data' => $project
        ], 201);
    }

    /**
     * Display a single project.
     */
    public function show($id)
    {
        $project = Project::findOrFail($id);
        return response()->json($project);
    }

    /**
     * Update a project.
     */
    public function update(Request $request, $id)
    {
        $project = Project::findOrFail($id);

        $validated = $request->validate([
            'title' => 'sometimes|required|string|max:255',
            'description' => 'sometimes|required|string',
            'category' => 'sometimes|required|string|max:255',
            'status' => 'sometimes|required|in:completed,ongoing,upcoming',
            'location' => 'sometimes|required|string|max:255',
            'start_date' => 'sometimes|required|date',
            'end_date' => 'nullable|date|after_or_equal:start_date',
            'image' => 'nullable|image|max:5120',
            'impact' => 'nullable|string|max:255',
        ]);

        $project->fill($validated);

        // Handle image upload to Cloudinary
        if ($request->hasFile('image')) {
            try {
                // Delete old image if exists
                $cloudinary = app(CloudinaryService::class);

                if ($project->image_public_id) {
                    $cloudinary->deleteImage($project->image_public_id);
                }

                // Upload new image
                $result = $cloudinary->uploadImage($request->file('image'), 'projects');
                $project->image_url = $result['url'];
                $project->image_public_id = $result['public_id'];
            } catch (\Exception $e) {
                return response()->json([
                    'message' => 'Failed to upload image',
                    'error' => $e->getMessage()
                ], 500);
            }
        }

        $project->save();

        return response()->json([
            'message' => 'Project updated successfully',
            'data' => $project
        ]);
    }

    /**
     * Delete a project.
     */
    public function destroy($id)
    {
        $project = Project::findOrFail($id);

        // Delete image from Cloudinary if exists
        if ($project->image_public_id) {
            try {
                $cloudinary = app(CloudinaryService::class);
                $cloudinary->deleteImage($project->image_public_id);
            } catch (\Exception $e) {
                \Log::error('Failed to delete image from Cloudinary: ' . $e->getMessage());
            }
        }

        $project->delete();

        return response()->json([
            'message' => 'Project deleted successfully'
        ]);
    }
}
