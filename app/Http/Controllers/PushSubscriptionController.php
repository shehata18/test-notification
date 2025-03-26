<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use NotificationChannels\WebPush\PushSubscription;

class PushSubscriptionController extends Controller
{
    /**
     * Store the PushSubscription.
     *
     * @param \Illuminate\Http\Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function store(Request $request)
    {
        $this->validate($request, [
            'endpoint' => 'required',
            'keys.auth' => 'required',
            'keys.p256dh' => 'required',
        ]);

        $endpoint = $request->endpoint;
        $token = $request->keys['auth'];
        $key = $request->keys['p256dh'];
        $user = $request->user();

        $subscription = PushSubscription::findOrCreate(
            [
                'endpoint' => $endpoint,
            ],
            [
                'user_id' => $user->id,
                'endpoint' => $endpoint,
                'public_key' => $key,
                'auth_token' => $token,
            ],
        );

        return response()->json(['success' => true]);
    }

    /**
     * Delete the specified subscription.
     *
     * @param \Illuminate\Http\Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function destroy(Request $request)
    {
        $this->validate($request, [
            'endpoint' => 'required',
        ]);

        $endpoint = $request->endpoint;

        PushSubscription::where('endpoint', $endpoint)->delete();

        return response()->json(['success' => true]);
    }
}
