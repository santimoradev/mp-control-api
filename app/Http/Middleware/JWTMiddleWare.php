<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use JWTAuth;
use Tymon\JWTAuth\Exceptions\TokenExpiredException;
use Tymon\JWTAuth\Exceptions\TokenInvalidException;
use Exception;

class JWTMiddleWare
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
      try {
        $user = JWTAuth::parseToken()->authenticate();
      } catch (TokenExpiredException $e) {
        $reason = 'Su sesión ha expirado';
        $message = 'Por favor, vuelva a autenticarse.';
        return response()->json([
          'errorCode' => 'TOKEN_EXPIRED',
          'message' => [
            'title' => $reason,
            'type' => 'error',
            'description' => $message
          ]
        ], 401);
      } catch (TokenInvalidException $e) {
        $reason = 'Token inválido';
        $message = 'Por favor, vuelva a autenticarse.';
        return response()->json([
          'errorCode' => 'TOKEN_INVALIDATE',
          'message' => [
            'title' => $reason,
            'type' => 'error',
            'description' => $message
          ]
        ], 401);
      } catch (Exception $e) {
        $reason = 'Token no encontrado';
        $message = 'Por favor, vuelva a autenticarse.';
        return response()->json([
          'errorCode' => 'TOKEN_NOT_FOUND',
          'message' => [
            'title' => $reason,
            'type' => 'error',
            'description' => $message
          ]
        ], 403);
      }
      return $next($request);
    }
}
