<?php

namespace App\Http\Middleware;

use Closure;
use App\Utils\Errors;
use Illuminate\Validation\ValidationException;

class CheckPagination {
    use Errors;
    private const MAXIMUM_PAGINATION = 50;
    public function handle($request, Closure $next) {
        if(!preg_match('/^[0-9]*$/', $request->query('paginate')) || $request->query('paginate') > self::MAXIMUM_PAGINATION) {
            throw ValidationException::withMessages([
                'paginate' => [
                    $this->badRequest,
                ],
            ]);
        }
        $response = $next($request);
        return $response;
    }
}
