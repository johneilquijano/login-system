<?php

namespace App\Http\Controllers;

use App\Models\Tool;
use App\Models\ToolCheckout;
use App\Services\AuditLogService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class ToolController extends Controller
{
    /**
     * Display the tool checkout page
     */
    public function index(Request $request)
    {
        $user = Auth::user();
        $orgId = $user->org_id;

        $search = $request->query('search', '');
        $tab = $request->query('tab', 'available');

        if ($tab === 'available') {
            // Get available tools
            $tools = Tool::forOrganization($orgId)
                ->active()
                ->get()
                ->filter(function ($tool) {
                    return $tool->isAvailable();
                });

            // Apply search
            if ($search) {
                $tools = $tools->filter(function ($tool) use ($search) {
                    $searchLower = strtolower($search);
                    return str_contains(strtolower($tool->name), $searchLower) ||
                           str_contains(strtolower($tool->category), $searchLower);
                });
            }

            $items = $tools;
        } elseif ($tab === 'checked_out') {
            // Get checked-out items for the user
            $items = ToolCheckout::where('user_id', $user->id)
                ->where('org_id', $orgId)
                ->whereNull('returned_at')
                ->with('tool')
                ->get();

            // Apply search
            if ($search) {
                $searchLower = strtolower($search);
                $items = $items->filter(function ($checkout) use ($searchLower) {
                    return str_contains(strtolower($checkout->tool_name ?? $checkout->tool->name ?? ''), $searchLower) ||
                           str_contains(strtolower($checkout->tool->category ?? ''), $searchLower);
                });
            }
        } else {
            // Get all checkouts (history) for the user
            $items = ToolCheckout::where('user_id', $user->id)
                ->where('org_id', $orgId)
                ->with('tool')
                ->orderBy('checked_out_at', 'desc')
                ->get();

            // Apply search
            if ($search) {
                $searchLower = strtolower($search);
                $items = $items->filter(function ($checkout) use ($searchLower) {
                    return str_contains(strtolower($checkout->tool_name ?? $checkout->tool->name ?? ''), $searchLower) ||
                           str_contains(strtolower($checkout->tool->category ?? ''), $searchLower);
                });
            }
        }

        return view('employee.tools.index', [
            'tab' => $tab,
            'search' => $search,
            'items' => $items,
        ]);
    }

    /**
     * Checkout a tool
     */
    public function checkout(Tool $tool, Request $request)
    {
        $user = Auth::user();

        // Verify ownership
        if ($tool->org_id !== $user->org_id) {
            return response()->json(['error' => 'Unauthorized'], 403);
        }

        // Check if available
        if (!$tool->isAvailable()) {
            return response()->json(['error' => 'Tool is not available'], 422);
        }

        // Create checkout record
        ToolCheckout::create([
            'user_id' => $user->id,
            'org_id' => $user->org_id,
            'tool_id' => $tool->id,
            'tool_name' => $tool->name,
            'description' => $tool->description,
            'serial_number' => $tool->serial_number,
            'status' => 'checked_out',
            'checked_out_at' => now(),
            'return_due_date' => now()->addDays(7), // Default 7-day checkout period
        ]);

        // Log the tool checkout
        AuditLogService::logAction(
            $user,
            'claim',
            'tool_checkout',
            $tool->id,
            "Checked out tool: " . $tool->name,
            metadata: [
                'tool_name' => $tool->name,
                'serial_number' => $tool->serial_number,
                'category' => $tool->category,
                'due_date' => now()->addDays(7)->toDateString()
            ]
        );

        return response()->json([
            'success' => true,
            'message' => 'Tool checked out successfully',
        ]);
    }

    /**
     * Return a checked-out tool
     */
    public function return(ToolCheckout $checkout, Request $request)
    {
        $user = Auth::user();

        // Verify ownership
        if ($checkout->user_id !== $user->id || $checkout->org_id !== $user->org_id) {
            return response()->json(['error' => 'Unauthorized'], 403);
        }

        // Check if already returned
        if ($checkout->returned_at) {
            return response()->json(['error' => 'Item already returned'], 422);
        }

        // Mark as returned
        $checkout->update([
            'returned_at' => now(),
            'status' => 'returned',
        ]);

        // Log the tool return
        AuditLogService::logAction(
            $user,
            'complete',
            'tool_checkout',
            $checkout->tool_id,
            "Returned tool: " . ($checkout->tool_name ?? $checkout->tool->name ?? 'Tool'),
            metadata: [
                'tool_name' => $checkout->tool_name ?? $checkout->tool->name ?? 'Unknown',
                'serial_number' => $checkout->serial_number,
                'checked_out_at' => $checkout->checked_out_at->toDateTimeString(),
                'returned_at' => now()->toDateTimeString(),
                'checkout_duration_days' => now()->diffInDays($checkout->checked_out_at)
            ]
        );

        return response()->json([
            'success' => true,
            'message' => 'Tool returned successfully',
        ]);
    }
}
