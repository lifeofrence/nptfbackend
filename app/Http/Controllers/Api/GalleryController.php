<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Gallery;
use App\Services\CloudinaryService;
use Illuminate\Http\Request;

class GalleryController extends Controller
{
    /**
     * Display all gallery items.
     */
    public function index(Request $request)
    {
        $query = Gallery::query();

        // Search filter
        if ($request->has('search') && !empty($request->search)) {
            $search = $request->search;
            $query->where(function ($q) use ($search) {
                $q->where('title', 'like', "%{$search}%")
                    ->orWhere('event_name', 'like', "%{$search}%");
            });
        }

        // Filter by event
        if ($request->has('event') && !empty($request->event) && $request->event !== 'all') {
            $query->where('event_name', $request->event);
        }

        // Filter by type
        if ($request->has('type') && !empty($request->type) && $request->type !== 'all') {
            $query->where('type', $request->type);
        }

        $perPage = $request->get('per_page', 12);
        $gallery = $query->orderBy('date', 'desc')->orderBy('id', 'desc')->paginate($perPage);

        return response()->json($gallery);
    }

    /**
     * Display a single gallery item.
     */
    public function show($id)
    {
        $item = Gallery::findOrFail($id);
        return response()->json($item);
    }

    /**
     * Store a new gallery item.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'event_name' => 'required|string|max:255',
            'title' => 'required|string|max:255',
            'type' => 'required|in:image,video',
            'media' => 'nullable|file|max:20480', // Max 20MB
            'media_url' => 'nullable|string|max:500', // For YouTube or external links
            'thumbnail' => 'nullable|image|max:2048', // For videos
            'thumbnail_url' => 'nullable|string|max:500', // For YouTube thumbnails
            'date' => 'required|date',
        ]);

        $gallery = new Gallery($validated);

        // Handle media upload
        if ($request->hasFile('media')) {
            try {
                $cloudinary = app(CloudinaryService::class);

                if ($validated['type'] === 'image') {
                    $result = $cloudinary->uploadImage($request->file('media'), 'gallery');
                    $gallery->media_url = $result['url'];
                    $gallery->media_public_id = $result['public_id'];
                } else {
                    // Upload video
                    $result = $cloudinary->uploadVideo($request->file('media'), 'gallery');
                    $gallery->media_url = $result['url'];
                    $gallery->media_public_id = $result['public_id'];
                }
            } catch (\Exception $e) {
                return response()->json([
                    'message' => 'Failed to upload media',
                    'error' => $e->getMessage()
                ], 500);
            }
        } elseif ($request->has('media_url')) {
            $gallery->media_url = $request->media_url;
        }

        // Handle thumbnail for videos
        if ($request->hasFile('thumbnail')) {
            try {
                $cloudinary = app(CloudinaryService::class);
                $result = $cloudinary->uploadImage($request->file('thumbnail'), 'gallery/thumbnails');
                $gallery->thumbnail_url = $result['url'];
                $gallery->thumbnail_public_id = $result['public_id'];
            } catch (\Exception $e) {
                return response()->json([
                    'message' => 'Failed to upload thumbnail',
                    'error' => $e->getMessage()
                ], 500);
            }
        } elseif ($request->has('thumbnail_url')) {
            $gallery->thumbnail_url = $request->thumbnail_url;
        }

        $gallery->save();

        return response()->json([
            'message' => 'Gallery item uploaded successfully',
            'data' => $gallery
        ], 201);
    }

    /**
     * Update a gallery item.
     */
    public function update(Request $request, $id)
    {
        $item = Gallery::findOrFail($id);

        $validated = $request->validate([
            'event_name' => 'sometimes|required|string|max:255',
            'title' => 'sometimes|required|string|max:255',
            'type' => 'sometimes|required|in:image,video',
            'media' => 'nullable|file|max:20480',
            'media_url' => 'nullable|string|max:500',
            'thumbnail' => 'nullable|image|max:2048',
            'thumbnail_url' => 'nullable|string|max:500',
            'date' => 'sometimes|required|date',
        ]);

        $item->fill($request->only(['event_name', 'title', 'type', 'date']));

        $cloudinary = app(CloudinaryService::class);

        // Handle media update
        if ($request->hasFile('media')) {
            // Delete old media
            if ($item->media_public_id) {
                if ($item->type === 'video') {
                    $cloudinary->deleteVideo($item->media_public_id);
                } else {
                    $cloudinary->deleteImage($item->media_public_id);
                }
            }

            try {
                if ($validated['type'] === 'image') {
                    $result = $cloudinary->uploadImage($request->file('media'), 'gallery');
                } else {
                    $result = $cloudinary->uploadVideo($request->file('media'), 'gallery');
                }
                $item->media_url = $result['url'];
                $item->media_public_id = $result['public_id'];
            } catch (\Exception $e) {
                return response()->json(['message' => 'Failed to upload media', 'error' => $e->getMessage()], 500);
            }
        } elseif ($request->has('media_url') && $request->media_url !== $item->media_url) {
            // If switching to a URL, delete old Cloudinary media if exists
            if ($item->media_public_id) {
                if ($item->type === 'video') {
                    $cloudinary->deleteVideo($item->media_public_id);
                } else {
                    $cloudinary->deleteImage($item->media_public_id);
                }
                $item->media_public_id = null;
            }
            $item->media_url = $request->media_url;
        }

        // Handle thumbnail update
        if ($request->hasFile('thumbnail')) {
            if ($item->thumbnail_public_id) {
                $cloudinary->deleteImage($item->thumbnail_public_id);
            }

            try {
                $result = $cloudinary->uploadImage($request->file('thumbnail'), 'gallery/thumbnails');
                $item->thumbnail_url = $result['url'];
                $item->thumbnail_public_id = $result['public_id'];
            } catch (\Exception $e) {
                return response()->json(['message' => 'Failed to upload thumbnail', 'error' => $e->getMessage()], 500);
            }
        } elseif ($request->has('thumbnail_url') && $request->thumbnail_url !== $item->thumbnail_url) {
            if ($item->thumbnail_public_id) {
                $cloudinary->deleteImage($item->thumbnail_public_id);
                $item->thumbnail_public_id = null;
            }
            $item->thumbnail_url = $request->thumbnail_url;
        }

        $item->save();

        return response()->json([
            'message' => 'Gallery item updated successfully',
            'data' => $item
        ]);
    }

    /**
     * Delete a gallery item.
     */
    public function destroy($id)
    {
        $item = Gallery::findOrFail($id);

        // Delete media from Cloudinary
        if ($item->media_public_id) {
            try {
                $cloudinary = app(CloudinaryService::class);
                if ($item->type === 'video') {
                    $cloudinary->deleteVideo($item->media_public_id);
                } else {
                    $cloudinary->deleteImage($item->media_public_id);
                }
            } catch (\Exception $e) {
                \Log::error('Failed to delete media from Cloudinary: ' . $e->getMessage());
            }
        }

        // Delete thumbnail from Cloudinary if exists
        if ($item->thumbnail_public_id) {
            try {
                $cloudinary = app(CloudinaryService::class);
                $cloudinary->deleteImage($item->thumbnail_public_id);
            } catch (\Exception $e) {
                \Log::error('Failed to delete thumbnail from Cloudinary: ' . $e->getMessage());
            }
        }

        $item->delete();

        return response()->json([
            'message' => 'Gallery item deleted successfully'
        ]);
    }

    /**
     * Get unique event names.
     */
    public function events()
    {
        $events = Gallery::select('event_name')
            ->distinct()
            ->orderBy('event_name')
            ->pluck('event_name');

        return response()->json($events);
    }
}
