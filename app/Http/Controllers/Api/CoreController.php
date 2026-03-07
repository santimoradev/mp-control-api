<?php

namespace App\Http\Controllers\Api;

use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

use JWTAuth;
use Sentinel;

use Carbon\Carbon;
use App\Models\ActivityLog;

use Exception;
use Tymon\JWTAuth\Exceptions\TokenExpiredException;
use Tymon\JWTAuth\Exceptions\TokenInvalidException;

class CoreController extends Controller
{
  protected $data = [];
  private $message = [];
  protected $status = true;
  protected $statusCode = 200;
  protected $errorCode = '';
  public $user;
  public $role;

	public function __construct( Request $request )
	{
    $hasError = false;
    try {
      $token = JWTAuth::getToken();
      if ( $token ) :
        $user = JWTAuth::toUser( $token );
        $this->user = $user;
        $this->role = Sentinel::findById( $user->id )->roles()->get()->first();
      endif;
    } catch (TokenExpiredException $e) {
      $hasError = true;
      $reason = 'El token ha expirado';
      $message = 'Por favor, vuelva a autenticarse.';
      $this->addErrorMessage($reason, $message, 401);
      return $this->result();
    } catch (TokenInvalidException $e) {
      return response()->json([
        'error' => 'El token es inválido.',
      ], 401);
    } catch (Exception $e) {
      return response()->json([
        'error' => 'Token no encontrado o no enviado.',
      ], 401);
    }
    if ( $hasError ) return $this->result();
	}
  public function isAdmin()
  {
    return $this->role->id === 1;
  }
  public function isOperator()
  {
    return $this->role->id === 3;
  }
  public function setActivityLog( $data )
  {
    ActivityLog::create([
        'user_id'         => $data['user_id'],
        'action_name'     => $data['action'],
        'module_name'     => $data['module'],
        'title'           => $data['title'],
        'description'     => $data['description'],
        'before_data'     => $data['oldData'] ?? null,
        'after_data'      => $data['newData'] ?? null,
        'ip_address'      => request()->ip(),
        'user_agent'      => request()->userAgent(),
    ]);
  }
  public function setData( $data )
  {
    $this->data = $data;
  }
  public function addData( $field , $value )
  {
    $this->data[$field] = $value;
  }
	public function addInfoMessage( $reason , $message )
	{
		$this->status = true;
		$this->setMessage('info',$reason,$message);
	}
	public function addSuccessMessage( $reason , $message )
	{
		$this->status = true;
		$this->setMessage('success',$reason,$message);
	}
	public function addWarningMessage( $reason , $message )
	{
		$this->status = false;
		$this->setMessage('warning',$reason,$message);
	}
	public function addErrorMessage( $reason , $message, $statusCode = 500, $errorCode = '1.0.1' )
	{
		$this->status = false;
		$this->setMessage('error',$reason,$message);
    $this->statusCode = $statusCode;
    $this->errorCode = $errorCode;
	}
	public function setMessage($type,$reason,$message){
		$this->message = [
			'title' => $reason,
			'type' => $type,
			'description' => $message
		];
	}
  public function result()
  {
    return response()->json([
      'data' => $this->data,
      'status' => $this->status,
      'errorCode' => $this->errorCode,
      'message' => $this->message,
      'timestamp' => Carbon::now()->format('Y-m-d H:i:s')
    ], $this->statusCode);
  }
}
