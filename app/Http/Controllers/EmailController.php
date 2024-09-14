<?php

namespace App\Http\Controllers;

use App\Http\Requests\EmailRequest;
use App\Http\Requests\UploadEmailCsvRequest;
use App\Jobs\VerifyUploadEmailCsvJob;
use App\Models\Email;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class EmailController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        try {

            $emails = Email::loginUser()->paginate($request->page_rows);

            return response()->json([
                'emails' => $emails,
                'status' => true,
            ]);
            
        } catch (\Exception $e) {
            return response()->json([
                'message' => $e->getMessage().$e->getLine(),
                'status' => false,
            ], 400);
        }
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(EmailRequest $request)
    {
        try {

            $email = Email::createWithLoginUser($request->all());

            return response()->json([
                'email' => $email,
                'message' => 'Email added successfully!',
                'status' => true,
            ]);
            
        } catch (\Exception $e) {
            return response()->json([
                'message' => $e->getMessage().$e->getLine(),
                'status' => false,
            ], 400);
        }
    }

    /**
     * Display the specified resource.
     */
    public function show(Request $request, $id)
    {
        try {

            $email = Email::loginUser()->find($id);

            return response()->json([
                'email' => $email,
                'status' => true,
            ]);
            
        } catch (\Exception $e) {
            return response()->json([
                'message' => $e->getMessage().$e->getLine(),
                'status' => false,
            ], 400);
        }
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Email $email)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(EmailRequest $request, $id)
    {
        try {

            $email = Email::loginUser()->find($id);
            $email->update($request->except('user_id'));

            return response()->json([
                'email' => $email,
                'message' => 'Email updated successfully!',
                'status' => true,
            ]);
            
        } catch (\Exception $e) {
            return response()->json([
                'message' => $e->getMessage().$e->getLine(),
                'status' => false,
            ], 400);
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Request $request, $id)
    {
        try {

            $ids = explode(",", $id);
            
            $emails = Email::loginUser()->findMany($ids);

            if($emails->isNotEmpty()){
                $emails->each(function ($email) {
                    $email->delete();
                });
                $msg = count($emails) > 1 ? "(" . count($emails) . ") Emails" : "Email";
            }else{
                return response()->json([
                    'message' => 'Email not found!'
                ], 404);
            };

            return response()->json([
                'message' => "$msg deleted successfully!",
                'status' => true,
            ]);
            
        } catch (\Exception $e) {
            return response()->json([
                'message' => $e->getMessage().$e->getLine(),
                'status' => false,
            ], 400);
        }
    }

    public function uploadEmailCsv(UploadEmailCsvRequest $request)
    {
        try {
            
            saveFile($request, 'csv');
            
            $request->merge([
                'user_id' => auth()->id(),
            ]);
            
            dispatch(new VerifyUploadEmailCsvJob($request->except(['file', 'hash'])));

            return response()->json([
                'message' => 'CSV Uploaded successfully!',
                'status' => true,
            ]);
            
        } catch (\Exception $e) {
            return response()->json([
                'message' => $e->getMessage().$e->getLine(),
                'status' => false,
            ], 400);
        }
    }
}
