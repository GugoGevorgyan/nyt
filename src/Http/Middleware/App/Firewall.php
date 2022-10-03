<?php

declare(strict_types=1);

namespace Src\Http\Middleware\App;

use Closure;
use Illuminate\Contracts\Foundation\Application;
use Illuminate\Contracts\Routing\ResponseFactory;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Collection;
use Src\Repositories\Firewall\FirewallContract;

/**
 *
 */
class Firewall
{
    /**
     * @param  FirewallContract  $firewallContract
     */
    public function __construct(protected FirewallContract $firewallContract)
    {
    }

    /**
     * Handle an incoming request.
     *
     * @param  Request  $request
     * @param  Closure  $next
     * @return mixed
     */
    public function handle(Request $request, Closure $next): mixed
    {
        $ip = $request->ip();
        $path = $request->path();

        $ips = $this->firewallContract
            ->where('ip', '=', ip2long($ip))
            ->findAll(['ip', 'url', 'blocked']);

        $urls = $this->firewallContract
            ->where('url', '=', $path)
            ->findAll(['ip', 'url', 'blocked']);

        if (!$ips->count() && !$urls->count()) {
            return $next($request);
        }

        if (!$this->checked($ips, $urls, $request)) {
            return $this->handling($request);
        }

        return $next($request);
    }

    /**
     * @param  Collection  $ips
     * @param  Collection  $urls
     * @param  Request  $request
     * @return bool
     */
    protected function checked(Collection $ips, Collection $urls, Request $request): bool
    {
        $result = true;

        foreach ($ips as $ip) {
            if ($ip->blocked && $ip->url === $request->path()) {
                $result = false;
                break;
            }

            if ($ip->blocked && null === $ip->url) {
                $result = false;
                break;
            }
        }

        foreach ($urls as $url) {
            if ($url === $request->path() && ip2long($request->ip()) !== $url->ip) {
                $result = false;
                break;
            }
        }


        return $result;
    }

    /**
     * @param  Request  $request
     * @return Application|ResponseFactory|Response|void
     */
    protected function handling(Request $request)
    {
        if ($request->wantsJson()) {
            return response('Undefined', 404);
        }

        abort(404);
    }
}
