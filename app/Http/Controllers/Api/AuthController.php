<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Api\CoreController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Crypt;
use App\Services\SendMail as SendMailService;

use JWTAuth;
use Tymon\JWTAuth\Exceptions\JWTException;
use Sentinel;
use Reminder;
use App\Models\User;

use Mail;
use App\Mail\RecoveryPassword;
use Illuminate\Mail\Markdown;

class AuthController extends CoreController
{
  public function doLogin(Request $request)
  {
    $input = $request->all();

    if ( str_contains( $input['username'], '@') ) :
        $credentials = [
            'email' => $input['username'],
            'password' => $input['password']
        ];
    else :
        $credentials = [
            'username' => $input['username'],
            'password' => $input['password']
        ];
    endif;
    $token = JWTAuth::attempt( $credentials );
    try {
        $user = Sentinel::authenticateAndRemember( $credentials );
        $this->addSuccessMessage('Credenciales correctas','Inicio de sesión correcto');
        $this->setActivityLog([
          'user_id'     => $user->id,
          'action'      => 'sign_in',
          'module'      => 'Auth',
          'description' => 'Inicio de sesión',
          'oldData'     => null,
          'newData'     => null,
        ]);
    } catch( ThrottlingException $e ) {
        $delay = $e->getDelay();
        $this->addErrorMessage('Bloqueado por muchos intentos','Has sido bloqueado por '.$delay.' segundos', 429, '4.0.2');
        return $this->result();
    }
      if ( $user ):
        $userDto = User::with(['mall'])->find($user->id);
        $userAccount = User::with(['roles', 'mall'])->find($user->id);
        if ( !$userAccount->status  ) :
            $this->addErrorMessage('Usuario desactivado.', 'No tienes permisos para acceder.', 403, '4.0.3');
            return $this->result();
        endif;
        $this->addData('user',$userDto);
        $this->addData('token', $token);
        $this->addData('role',$userAccount->roles[0]);
      else:
        $this->addErrorMessage('Inicio de sesión','Cédula o clave incorrecta', 401, '4.0.1');
      endif;
    return $this->result();
  }
  public function doSign(Request $request)
  {
    $toSign = $request->input('data'); // <-- AQUÍ EL CAMBIO

    if (!$toSign) {
        return response("Missing data", 400);
    }

    $privateKey = openssl_pkey_get_private(file_get_contents(storage_path('certs/private.key')));

    // openssl_sign($toSign, $signature, $privateKey, OPENSSL_ALGO_SHA512);
    openssl_sign($toSign, $signature, $privateKey, OPENSSL_ALGO_SHA1);

    openssl_free_key($privateKey);

    return response(base64_encode($signature))->header('Content-Type', 'text/plain');
  }
}
