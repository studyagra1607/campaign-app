<?php

namespace App\Http\Controllers;

use App\Http\Requests\CampaignRequest;
use App\Jobs\SendEmailsToUsersJob;
use App\Models\Campaign;
use Illuminate\Http\Request;

class CampaignController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        try {

            $campaigns = Campaign::loginUser()->paginate($request->page_rows);

            return response()->json([
                'campaigns' => $campaigns,
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
    public function store(CampaignRequest $request)
    {
        try {

            $campaign = Campaign::createWithLoginUser($request->all());

            return response()->json([
                'campaign' => $campaign,
                'message' => 'Campaign added successfully!',
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

            $campaign = Campaign::loginUser()->find($id);

            return response()->json([
                'campaign' => $campaign,
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
    public function edit(Campaign $campaign)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(CampaignRequest $request, $id)
    {
        try {

            $campaign = Campaign::loginUser()->find($id);
            $campaign->update($request->except('user_id'));

            return response()->json([
                'campaign' => $campaign,
                'message' => 'Campaign updated successfully!',
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

            $ids = explode(',', $id);

            $campaigns = Campaign::loginUser()->findMany($ids);

            if ($campaigns->isNotEmpty()) {
                $campaigns->each(function ($campaign) {
                    $campaign->delete();
                });
                $msg = count($campaigns) > 1 ? '('.count($campaigns).') Campaigns' : 'Campaign';
            } else {
                return response()->json([
                    'message' => 'Campaign not found!',
                    'status' => false,
                ], 404);
            }

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

    /**
     * There custom functions =========================================
     */
    public function runCampaign(Request $request, $id)
    {
        try {

            $campaign = Campaign::loginUser()->active()->find($id);

            if ($campaign) {
                $campaign->update([
                    'progress_status' => 'running',
                    'last_run' => now(),
                ]);
                dispatch(new SendEmailsToUsersJob($campaign));
            } else {
                return response()->json([
                    'message' => 'Campaign is not active!',
                    'status' => false,
                ], 400);
            }

            return response()->json([
                'message' => 'Campaign start successfully!',
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
