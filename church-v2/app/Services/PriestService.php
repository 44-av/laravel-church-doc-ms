<?php

namespace App\Services;

use App\Constant\MyConstant;
use App\Jobs\PriestJob;
use App\Models\Notification;
use App\Models\Priest;
use Illuminate\Database\QueryException;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;

class PriestService
{
    private $validator;

    public function __construct(useValidator $validator)
    {
        $this->validator = $validator;
    }

    public function store($request)
    {
        $validator = Validator::make($request->all(), $this->validator->priestValidator());

        if ($validator->fails()) {
            return [
                'error_code' => MyConstant::FAILED_CODE,
                'status_code' => MyConstant::BAD_REQUEST,
                'message' => $validator->errors()->first(),
            ];
        }

        try {
            $data = $validator->validated();

            if ($request->hasFile('image')) {
                $file = $request->file('image');
                $fileName = 'priest_' . time() . '.' . $file->getClientOriginalExtension();
                $filePath = public_path('assets/priests/' . $fileName);
                $file->move(public_path('assets/priests'), $fileName);
                $data['image'] = $fileName;
            }

            $priest = Priest::create($data);
            $priest->save();

            Notification::create([
                'type' => 'Priest',
                'message' => 'A new priest has been added by ' . Auth::user()->name,
                'is_read' => '0',
            ]);

            session()->flash('success', 'Priest created successfully');
            return [
                'error_code' => MyConstant::SUCCESS_CODE,
                'status_code' => MyConstant::OK,
                'message' => 'Priest created successfully',
            ];
        } catch (QueryException $e) {
            session()->flash('error', 'Internal server error');
            return [
                'error_code' => MyConstant::FAILED_CODE,
                'status_code' => MyConstant::BAD_REQUEST,
                'message' => $e->getMessage(),
            ];
        }
    }

    public function update($request, $id)
    {
        $validator = Validator::make($request->all(), $this->validator->priestValidator());

        if ($validator->fails()) {
            return [
                'error_code' => MyConstant::FAILED_CODE,
                'status_code' => MyConstant::BAD_REQUEST,
                'message' => $validator->errors()->first(),
            ];
        }

        try {
            $priest = Priest::find($id);

            if (!$priest) {
                return [
                    'error_code' => MyConstant::FAILED_CODE,
                    'status_code' => MyConstant::NOT_FOUND,
                    'message' => 'Priest not found',
                ];
            }

            $data = $validator->validated();

            if ($request->hasFile('image')) {
                if (file_exists(public_path('assets/priests/' . $priest->image))) {
                    unlink(public_path('assets/priests/' . $priest->image));
                }
            }

            $priest->update($data);

            session()->flash('success', 'Priest updated successfully');
            return [
                'error_code' => MyConstant::SUCCESS_CODE,
                'status_code' => MyConstant::OK,
                'message' => 'Priest updated successfully',
            ];
        } catch (QueryException $e) {
            session()->flash('error', 'Internal server error');
            return [
                'error_code' => MyConstant::FAILED_CODE,
                'status_code' => MyConstant::BAD_REQUEST,
                'message' => $e->getMessage(),
            ];
        }
    }

    public function destroy($id)
    {
        try {
            $priest = Priest::find($id);

            if (!$priest) {
                return [
                    'error_code' => MyConstant::FAILED_CODE,
                    'status_code' => MyConstant::NOT_FOUND,
                    'message' => 'Priest not found',
                ];
            }

            if (file_exists(public_path('assets/img/priest/' . $priest->image))) {
                unlink(public_path('assets/img/priest/' . $priest->image));
            }

            $priest->delete();

            session()->flash('success', 'Priest deleted successfully');
            return [
                'error_code' => MyConstant::SUCCESS_CODE,
                'status_code' => MyConstant::OK,
                'message' => 'Priest deleted successfully',
            ];
        } catch (QueryException $e) {
            return [
                'error_code' => MyConstant::FAILED_CODE,
                'status_code' => MyConstant::BAD_REQUEST,
                'message' => $e->getMessage(),
            ];
        }
    }
}
