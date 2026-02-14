<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Str;
use App\Models\AuditLog;

class LogPageViews
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure(\Illuminate\Http\Request): (\Illuminate\Http\Response|\Illuminate\Http\RedirectResponse)  $next
     * @return \Illuminate\Http\Response|\Illuminate\Http\RedirectResponse
     */
    public function handle(Request $request, Closure $next)
    {
        $response = $next($request);

        // Only log authenticated users
        if (Auth::check()) {
            $user = Auth::user();
            
            // Exclude certain routes from logging (API, webhooks, etc.)
            $excludedRoutes = ['api.*', 'notifications.*', 'password.*'];
            $currentRoute = $request->route()?->getName() ?? '';
            
            $shouldExclude = false;
            foreach ($excludedRoutes as $excluded) {
                if ($currentRoute === $excluded || str_contains($currentRoute, str_replace('.*', '', $excluded))) {
                    $shouldExclude = true;
                    break;
                }
            }

            // Only log GET requests to actual pages (not API calls or form submissions)
            if (!$shouldExclude && $request->method() === 'GET' && !$request->wantsJson()) {
                AuditLog::create([
                    'org_id' => $user->org_id,
                    'user_id' => $user->id,
                    'user_role' => $user->role,
                    'action' => 'page_view',
                    'entity_type' => 'page',
                    'entity_id' => null,
                    'description' => 'Viewed page: ' . $this->formatPageName($request->route()?->getName(), $request->path()),
                    'url_path' => $request->path(),
                    'route_name' => $request->route()?->getName(),
                    'method' => $request->method(),
                    'ip_address' => $request->ip(),
                    'user_agent' => $request->userAgent(),
                    'metadata' => [
                        'full_url' => $request->url(),
                        'query_string' => $request->getQueryString(),
                    ]
                ]);
            }
        }

        return $response;
    }

    private function formatPageName(?string $routeName, string $path): string
    {
        $label = $routeName ?: $path;

        if ($routeName) {
            $segments = explode('.', $routeName);
            $action = end($segments);
            $removableActions = ['index', 'show', 'create', 'edit', 'update', 'store', 'destroy'];

            if (in_array($action, $removableActions, true)) {
                array_pop($segments);
            }

            $label = implode(' ', $segments);
        }

        $label = str_replace(['-', '_', '/'], ' ', $label);
        $label = preg_replace('/\s+/', ' ', $label);

        return Str::title(trim($label));
    }
}
