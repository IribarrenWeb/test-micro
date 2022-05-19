<?php

namespace App\Traits;
use Exception;
use Illuminate\Http\Request;

trait ResponseTrait {

    protected function addFilters(Request $request, &$query){

        if ($query) {
            $page  = $request->input('page');
            $take = $request->input('take');
            $order_by= $request->input('order_by');
            $order_direction= $request->input('order_direction');
            if ($order_by && $order_direction) {
                $query->orderBy($order_by, $order_direction);
            } else {
                $query->orderBy("created_at", "DESC");
            }
            //Pagination
            if ($page) $query=$query->paginate($take??12);
            else{
              $take ? $query->take($take) : false;//Take
              $query=$query->get();
            }
        }
    }

    protected function response($response=[], $code=200){
        return response()->json($response,$code);
    }

    protected function getCleanCode(Exception $e){
        $code =$e->getCode() ? (is_numeric($e->getCode()) ? $e->getCode() : 500) : 500;
        $code = ($code >500) ? 500 : $code;
        return $code;
    }

    protected function getErrorResponse(Exception $e , $message,$errors = []){
        $return["status"] = 'Error';
        $return['message'] = $e->getMessage() ?? $message;
        if(!empty($errors)) $return['errors'] = $errors;

        return $return;
    }

    protected function executeMessageError($message , $code = 400){
        throw new \Exception($message, $code);
    }

    protected function getSuccessResponse($query = null, $message='Peticion exitosa.' , $page=false){
         //Response-
         $response["status"] = 'Success';
         $response["message"] = $message;

         if (!empty($query))
            $response['data'] = $page ? $query->items() : $query;

        if($page) //If request pagination add meta-page
            $response['meta'] = [
                'page' => [
                    "total" => $query->total(),
                    "lastPage" => $query->lastPage(),
                    "perPage" => $query->perPage(),
                    "currentPage" => $query->currentPage()
                ]
            ];
        return $response;
    }

    /**
     * Return an error JSON response.
     *
     * @param  string  $message
     * @param  int  $code
     * @param  array|string|null  $data
     * @return \Illuminate\Http\JsonResponse
     */
	protected function error(string $message = 'Tuvimos un error', int $code = 400, $data = null)
	{
        $return = [
            'status' => 'Error',
            'success' => false,
			'message' => $message,
            'data' => $data ?? []
        ];

        return response()->json($return, $code);
	}

    /**
     * Return a success JSON response.
     *
     * @param  array|string  $data
     * @param  string  $message
     * @param  int|null  $code
     * @return \Illuminate\Http\JsonResponse
     */
    protected function success($data = null, int $code = 200, string $message = 'Peticion exitosa, todo salio bien!',array $appendData = null)
	{
        $response = [
            'status' => 'Success',
            'data' => $data,
            'message' => $message,
        ];

        if ($appendData && is_array($appendData)) {
            $response = array_merge($response,$appendData);
        }

		return response()->json($response, $code);
	}

}