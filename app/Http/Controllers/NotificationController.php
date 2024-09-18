<?php

namespace App\Http\Controllers;

use App\Events\UserEvent;
use Illuminate\Http\Request;

class NotificationController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        try {

            $notifications = auth()->user()->notifications;

            return response()->json([
                'notifications' => $notifications,
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
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     */
    public function show($id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit($id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update($id)
    {
        try {

            $notification = auth()->user()->notifications->where('id', $id)->first();
            $notification->markAsRead();
            
            event(new UserEvent(auth()->id(), 'notification'));
            
            return response()->json([
                'notification' => $notification,
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
    public function destroy($id)
    {
        try {

            $notification = auth()->user()->notifications->where('id', $id)->first();

            if ($notification) {
                $notification->delete();
            }else{
                return response()->json([
                    'message' => 'Notification not found!',
                    'status' => false,
                ], 404);
            }

            event(new UserEvent(auth()->id(), 'notification'));
            
            return response()->json([
                'message' => "Notification deleted successfully!",
                'status' => true,
            ]);
            
        } catch (\Exception $e) {
            return response()->json([
                'message' => $e->getMessage().$e->getLine(),
                'status' => false,
            ], 400);
        }
    }

    public function notificationsCount()
    {
        try {

            $notifications_count = auth()->user()->unreadNotifications->count();

            return response()->json([
                'notifications_count' => $notifications_count,
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
