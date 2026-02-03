<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Api\CoreController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Http\Resources\BaseCollection;
use Sentinel;

use App\Models\User;

class UserController extends CoreController
{
  public function index(Request $request)
  {
    $take = [
      's'
    ];
    $input = $request->only($take);
    $query = User::query();

    if ( $request->has('s') AND $request->s) :
      $query->where( function($subquery) use ($input) {
        $subquery->orWhere('username','LIKE','%'.$input['s'].'%');
      });
    endif;
    $query->with(['roles']);
    $query->orderBy('id','Desc');
    $rows = $query->paginate(10);
    $this->setData(new BaseCollection($rows) );

    return $this->result();
  }
  public function store(Request $request)
  {
    DB::beginTransaction();
    try {
      $take = [
        'first_name','last_name','username','email', 'mall_id', 'status'
      ];
      $input = $request->only($take);
      $roleId = $request->get('role_id');
      $role = Sentinel::findRoleById( $roleId );

      $dataUser = [
        'username' => $input['username'],
        'password' => $input['username'],
        'email' => $input['email'],
        'first_name' => $input['first_name'],
        'last_name' => $input['last_name'],
        'mall_id' => $input['mall_id'],
        'status' => $input['status']
      ];

      $user = Sentinel::registerAndActivate($dataUser);
      if ( $role ) $user->roles()->attach( $role );

      $this->setData($user->id);
      $this->addSuccessMessage('Usuario creado', 'Se ha creado un nuevo usuario');
      DB::commit();
    } catch (\Exception $e) {
      $this->addErrorMessage('Ha ocurrido un error', $e->getMessage() );
      DB::rollBack();
    }
    return $this->result();
  }
  public function update(Request $request, $id)
  {
    DB::beginTransaction();
    try {
      $take = [
        'first_name','last_name','username','email', 'mall_id', 'status'
      ];
      $input = $request->only($take);
      $roleId = $request->get('role_id');
      $user = User::find($id);
      $userData = [
        'first_name' => $input['first_name'],
        'last_name' => $input['last_name'],
        'username' => $input['username'],
        'email' => $input['email'],
        'mall_id' => $input['mall_id'],
        'status' => $input['status'],
      ];
      $user->update($userData);

      $role = Sentinel::findRoleById( $roleId);
      if ( $role ) $user->roles()->sync( $role );

      $this->setData($user->id);
      $this->addSuccessMessage('Cliente actualizado', 'Se ha actualizado el cliente');
      DB::commit();
    } catch (\Exception $e) {
      $this->addErrorMessage('Ha ocurrido un error', $e->getMessage() );
      DB::rollBack();
    }
    return $this->result();
  }
  public function password(Request $request, $id)
  {
    DB::beginTransaction();
    try {
      $take = ['password'];
      $input = $request->only($take);

      $user = Sentinel::findById($id);

      $credentials = [
          'password' => $input['password']
      ];
      $user = Sentinel::update($user, $credentials);
      $this->addSuccessMessage( 'Contraseña cambiada' , 'Tu contraseña ha sido cambiada exitosamente.' );

      $this->addData('user', $user);
      DB::commit();
    } catch (\Exception $e) {
      $this->addErrorMessage('Ha ocurrido un error', $e->getMessage() );
      DB::rollBack();
    }
    return $this->result();

  }
  public function status(Request $request, $id)
  {
    DB::beginTransaction();
    try {
      $take = ['status'];
      $input = $request->only($take);
      $userId = (int)$id;
      if ( $userId === $this->user->id ) :
        $this->addSuccessMessage( 'Datos actualizados' , 'El estado del Usuario ha sido actualizado.' );
        return $this->result();
      endif;

      $user = User::find($userId);

      $user->status = $input['status'];
      $user->save();

      $this->addSuccessMessage( 'Datos actualizados' , 'El estado del Usuario ha sido actualizado.' );

      $this->addData('user', $user);
      DB::commit();
    } catch (\Exception $e) {
      $this->addErrorMessage('Ha ocurrido un error', $e->getMessage() );
      DB::rollBack();
    }
    return $this->result();

  }
}
