<?php

namespace App\Http\Controllers;

use App\Models\Feedback;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class FeedbackController extends Controller
{
    public function store(Request $request)
    {
        $validated = $request->validate([
            'category' => 'required|in:bug,ux,feature',
            'message' => 'required|string|max:1000',
            'severity' => 'nullable|in:low,medium,high',
            'route' => 'nullable|string|max:255',
            'route_name' => 'nullable|string|max:255',
            'url_path' => 'required|string|max:500',
            'viewport_width' => 'nullable|integer|min:1',
            'viewport_height' => 'nullable|integer|min:1',
            'user_agent' => 'nullable|string|max:500',
            'click_x' => 'nullable|integer|min:0',
            'click_y' => 'nullable|integer|min:0',
            'page_x' => 'nullable|integer|min:0',
            'page_y' => 'nullable|integer|min:0',
            'scroll_x' => 'nullable|integer|min:0',
            'scroll_y' => 'nullable|integer|min:0',
            'element_tag' => 'nullable|string|max:50',
            'element_id' => 'nullable|string|max:255',
            'element_name' => 'nullable|string|max:255',
            'element_classes' => 'nullable|string|max:500',
            'element_text' => 'nullable|string|max:255',
            'element_aria_label' => 'nullable|string|max:255',
            'element_placeholder' => 'nullable|string|max:255',
            'element_selector' => 'nullable|string|max:1000',
            'element_path' => 'nullable|string|max:1000',
        ]);

        $user = Auth::user();

        // Create feedback record
        $feedback = Feedback::create([
            'user_id' => $user->id,
            'org_id' => $user->org_id,
            'user_role' => $user->role,
            'category' => $validated['category'],
            'message' => $validated['message'],
            'severity' => $validated['severity'] ?? null,
            'status' => 'new',
            'route' => $validated['route'] ?? null,
            'route_name' => $validated['route_name'] ?? null,
            'url_path' => $validated['url_path'],
            'viewport_width' => $validated['viewport_width'] ?? null,
            'viewport_height' => $validated['viewport_height'] ?? null,
            'user_agent' => $validated['user_agent'] ?? null,
            'click_x' => $validated['click_x'] ?? null,
            'click_y' => $validated['click_y'] ?? null,
            'page_x' => $validated['page_x'] ?? null,
            'page_y' => $validated['page_y'] ?? null,
            'scroll_x' => $validated['scroll_x'] ?? null,
            'scroll_y' => $validated['scroll_y'] ?? null,
            'element_tag' => $validated['element_tag'] ?? null,
            'element_id' => $validated['element_id'] ?? null,
            'element_name' => $validated['element_name'] ?? null,
            'element_classes' => $validated['element_classes'] ?? null,
            'element_text' => $validated['element_text'] ?? null,
            'element_aria_label' => $validated['element_aria_label'] ?? null,
            'element_placeholder' => $validated['element_placeholder'] ?? null,
            'element_selector' => $validated['element_selector'] ?? null,
            'element_path' => $validated['element_path'] ?? null,
        ]);

        return response()->json([
            'success' => true,
            'message' => 'Feedback submitted successfully',
            'feedback_id' => $feedback->id,
        ]);
    }
}
