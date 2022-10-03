<?php

namespace Src\Http\Controllers\SystemWorker;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Src\Http\Controllers\Controller;

class ValidatorController extends Controller
{
    public function unique(Request $request)
    {
        $request->validate([
           'table' => 'required',
           'col' => 'required',
           'primary' => 'required',
           'primary_value' => 'nullable'
        ]);

        $validator = Validator::make(
            $request->all(),
            [
                $request->col => 'unique:'.$request->table.','.$request->col.','.$request->primary_value.','.$request->primary
            ]
        );

        if ($validator->fails()) {
            return response(
                [
                    'valid' => false,
                    'data' => ['message' => $validator->errors()->first($request->col)]
                ]
            );
        }

        return response(['valid' => true]);
    }
}
