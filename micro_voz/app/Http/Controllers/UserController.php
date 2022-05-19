<?php

namespace App\Http\Controllers;

use App\Http\Requests\UserRequest;
use App\Models\User;
use Carbon\Carbon;
use Exception;
use Illuminate\Http\Request;

class UserController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        $user = auth()->user();
        try {
            //Filters
            $name = $request->input('name');
            $email = $request->input('email');
            $includes = $request->input('includes');
            $start_date = $request->input('start_date');
            $end_date = $request->input('end_date');

            $query = User::where('id', '!=', $user->id);

            if ($start_date) {
                $start_date = Carbon::parse($start_date);
                $query->whereDate('created_at', '>=', $start_date);
            }
            if ($end_date) {
                $end_date = Carbon::parse($end_date);
                $query->whereDate('created_at', '<=', $end_date);
            }

            if ($name) {
                $query->where('name', 'like', '%' . $name . '%');
            }
            if ($email) {
                $query->where('email', 'like', '%' . $email . '%');
            }
            if ($includes) {
                $query->with($includes);
            }

            $this->addFilters($request, $query);

            $response = $this->getSuccessResponse(
                $query,
                'Listado de usuarios',
                $request->input('page')
            );
        } catch (\Exception $e) {
            $code = $this->getCleanCode($e);
            $response = $this->getErrorResponse($e, 'Error al Listar los usuarios');
        }
        return $this->response($response, $code ?? 200);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(UserRequest $request)
    {
        try {
            $user_data = $request->only([
                'name',
                'last_name',
                'company',
                'phone_number',
                'email',
                'type_desc',
                'password',
                'type_client_id'
            ]);
            $user = User::create($user_data);
            $response = $this->getSuccessResponse();
            $code = 201;
        } catch (Exception $e) {
            $code = $this->getCleanCode($e);
            $response = $this->getErrorResponse($e, 'Hubo un error al intentar guardar el registro');
        }

        return $this->response($response,$code);
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show(User $user)
    {
        $user->load('type');

        return $this->success($user);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(UserRequest $request, User $user)
    {
        try {
            $user_data = $request->only([
                'name',
                'last_name',
                'company',
                'phone_number',
                'email',
                'type_desc',
                'password',
                'type_client_id'
            ]);
            $user->update($user_data);
            $response = $this->getSuccessResponse();
            $code = 201;
        } catch (Exception $e) {
            $code = $this->getCleanCode($e);
            $response = $this->getErrorResponse($e, 'Hubo un error al intentar guardar el registro');
        }

        return $this->response($response,$code);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy(User $user)
    {
        try {
            $user->delete();
            $response = $this->getSuccessResponse(null,'El registro se ha eliminado');
            $code = 204;
        } catch (Exception $e) {
            $response = $this->getErrorResponse($e,'Ocurrio un error al intentar eliminar el registro', [$e->getLine()]);
            $code = $this->getCleanCode($e);
        }
        return $this->response($response,$code);
    }
}
