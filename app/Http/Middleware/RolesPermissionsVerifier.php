<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Contracts\Auth\Guard;

class RolesPermissionsVerifier
{
    const TYPES_DELIMITER  = '|';
    const VALUES_DELIMITER = ';';

    protected $auth;

    /**
     * Creates a new instance of the middleware.
     *
     * @param Guard $auth
     */
    public function __construct(Guard $auth)
    {
        $this->auth = $auth;
    }
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle($request, Closure $next, string $elements)
    {
        if ($this->auth->guest()){
            abort(403);
        }

        $criteries = explode(self::TYPES_DELIMITER, $elements);
        $result = ["roles"=>[], "permissions"=>[]];

        foreach ($criteries as $v)
        {
            $result[substr($v, 0, strpos($v, ":"))] =
                explode(self::VALUES_DELIMITER,substr($v, strpos($v, ":")+1));
        }

        if($request->user()->ability($result["roles"],$result["permissions"])){
            return $next($request);
        }
        abort(403);
    }
}