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
    //TODO: review login with username or email
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
        if ( $user ) :
          $this->addSuccessMessage('Credenciales correctas','Inicio de sesión correcto');
          $this->setActivityLog([
            'user_id'     => $user->id,
            'action'      => 'sign_in',
            'module'      => 'Auth',
            'title'       => 'Inicio de sesión',
            'description' => 'El usuario ha iniciado sesión correctamente.',
          ]);
        endif;
    } catch( ThrottlingException $e ) {
        $delay = $e->getDelay();
        $this->addErrorMessage('Bloqueado por muchos intentos','Has sido bloqueado por '.$delay.' segundos', 429, '4.0.2');
        return $this->result();
    }
      if ( $user ):
        $userDto = User::find($user->id);
        $userAccount = User::with(['roles'])->find($user->id);
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
}
