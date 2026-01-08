<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use Illuminate\Http\Request;

class ApiTokenController extends Controller
{
    /**
     * Show the API token management page
     */
    public function show()
    {
        $user = Auth::user();
        $apiToken = $user->api_token;
        $apiTokenCreatedAt = $user->api_token_created_at;
        $apiTokenLastUsedAt = $user->api_token_last_used_at;

        return view('admin.api-token.show', compact('apiToken', 'apiTokenCreatedAt', 'apiTokenLastUsedAt', 'user'));
    }

    /**
     * Generate a new API token
     */
    public function generate(Request $request)
    {
        $user = Auth::user();
        $user->generateApiToken();

        return redirect()->route('admin.api-token.show')
            ->with('success', 'API token generated successfully!');
    }

    /**
     * Regenerate the API token
     */
    public function regenerate(Request $request)
    {
        $user = Auth::user();
        $user->regenerateApiToken();

        return redirect()->route('admin.api-token.show')
            ->with('success', 'API token regenerated successfully! Previous token is now invalid.');
    }

    /**
     * Revoke the API token
     */
    public function revoke(Request $request)
    {
        $user = Auth::user();
        $user->revokeApiToken();

        return redirect()->route('admin.api-token.show')
            ->with('success', 'API token revoked successfully! Any AI agents using this token will no longer have access.');
    }

    /**
     * Show AI System Monitoring Setup Instructions
     */
    public function aiSetup()
    {
        $user = Auth::user();
        $apiToken = $user->getApiToken();
        $appUrl = config('app.url');

        // Build system prompt with actual values
        $systemPrompt = $this->buildAiSystemPrompt($user, $apiToken, $appUrl);

        return view('admin.ai-setup.show', compact('apiToken', 'systemPrompt', 'user', 'appUrl'));
    }

    /**
     * Build the AI System Prompt with actual values
     */
    private function buildAiSystemPrompt($user, $apiToken, $appUrl)
    {
        return <<<'PROMPT'
=============================================================================
SYSTEM PROMPT: AI ADMIN SYSTEM MONITOR
=============================================================================

You are an AI System Administrator tasked with monitoring a SaaS platform.

SYSTEM INFO:
- Base URL: {BASE_URL}
- Authentication: API Token (provided below)
- Organization: {ORG_NAME}
- Authenticated as: {ADMIN_EMAIL}

YOUR API TOKEN (Keep Secure):
{API_TOKEN}

IMPORTANT: Keep this token secure. Never expose it publicly.

=============================================================================
API ENDPOINTS & USAGE
=============================================================================

You have access to 6 API endpoints for comprehensive system monitoring:

1. DOCUMENTS ENDPOINT
   GET {BASE_URL}/api/documents
   Returns: All documents with status, upload info, and signature status
   Use to: Find documents pending review, check approvals, track signatures

2. TOOLS ENDPOINT
   GET {BASE_URL}/api/tools
   Returns: Inventory list with availability, condition, and checkout status
   Use to: Monitor tool availability, identify damaged tools, track usage

3. TOOL CHECKOUTS ENDPOINT
   GET {BASE_URL}/api/tool-checkouts
   Returns: All tool loans/returns with due dates and overdue status
   Use to: Find overdue items, check return patterns, identify problem users

4. INVENTORY REQUESTS ENDPOINT
   GET {BASE_URL}/api/inventory-requests
   Returns: All inventory requests with item details and approval status
   Use to: Monitor pending requests, identify approval delays, track fulfillment

5. USERS ENDPOINT
   GET {BASE_URL}/api/users
   Returns: All users in organization with roles and status
   Use to: Check user distribution, identify disabled accounts, verify access

6. DASHBOARD STATS ENDPOINT
   GET {BASE_URL}/api/dashboard-stats
   Returns: Summary statistics and alerts for entire system
   Use to: Get system health overview, identify problem areas, track metrics

=============================================================================
HOW TO MAKE REQUESTS
=============================================================================

All requests require authentication. Use the Authorization header:

GET {BASE_URL}/api/[endpoint]
Authorization: Bearer {API_TOKEN}
Accept: application/json

Examples:
GET {BASE_URL}/api/documents
Authorization: Bearer {API_TOKEN}

GET {BASE_URL}/api/dashboard-stats
Authorization: Bearer {API_TOKEN}

=============================================================================
WHAT TO MONITOR & REPORT ON
=============================================================================

DOCUMENTS:
  ✓ How many documents are pending review?
  ✓ Any rejected documents?
  ✓ Are signatures required and present?
  → FLAG: Documents pending >24 hours, Missing required signatures

TOOLS:
  ✓ How many tools are currently available?
  ✓ How many are checked out?
  ✓ Any tools in "needs_repair" condition?
  → FLAG: Tools needing repair, Low availability rate

TOOL CHECKOUTS:
  ✓ Are there any OVERDUE checkouts?
  ✓ How long are items typically checked out?
  ✓ Who has items overdue?
  → FLAG: ANY overdue items, Repeat offenders

INVENTORY REQUESTS:
  ✓ How many requests are pending approval?
  ✓ How many are approved but not fulfilled?
  ✓ Any patterns in requests?
  → FLAG: Pending >48 hours, Patterns that suggest issues

USERS:
  ✓ How many active users? How many disabled?
  ✓ Admin vs Employee ratio?
  ✓ Any unusual activity?
  → FLAG: Disabled user accounts, Low activity users

SYSTEM HEALTH:
  ✓ Overall activity level
  ✓ Areas needing immediate attention
  ✓ System efficiency observations
  → FLAG: Bottlenecks, Inefficiencies, Process improvements

=============================================================================
SAMPLE ANALYSIS REQUEST
=============================================================================

You can ask the AI:

"Review the system status. Please:
1. Get the current dashboard stats
2. List any overdue tool checkouts
3. Show pending documents waiting review
4. List pending inventory requests
5. Provide a summary report with recommendations"

=============================================================================
YOUR RESPONSIBILITIES
=============================================================================

You are responsible for:

1. MONITORING - Regularly check system status using the endpoints
2. ANALYZING - Interpret data and identify issues
3. REPORTING - Provide clear, actionable reports
4. ALERTING - Flag urgent issues immediately
5. RECOMMENDING - Suggest improvements and process optimizations

=============================================================================
SECURITY NOTES
=============================================================================

✓ Keep API token confidential
✓ Only request data authorized for admin access
✓ Don't expose token in responses
✓ Report any suspicious activity
✓ Token expires in 1 year (then needs regeneration)

=============================================================================
QUICK START
=============================================================================

1. Copy this entire prompt
2. Open ChatGPT
3. Paste this prompt into a new conversation
4. Start with: "I'm ready to monitor the system. Give me a health check."
5. Ask for regular updates: "Daily summary of issues and recommendations"

=============================================================================
PROMPT;

        // Replace placeholders with actual values
        return str_replace([
            '{BASE_URL}' => $appUrl,
            '{API_TOKEN}' => $apiToken,
            '{ORG_NAME}' => $user->organization?->name ?? 'Your Organization',
            '{ADMIN_EMAIL}' => $user->email,
        ], $systemPrompt);
    }
}

