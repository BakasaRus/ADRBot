<?php

namespace App\Http\Controllers;

use App\Subscriber;
use Illuminate\Http\RedirectResponse;
use Illuminate\View\View;

class SubscriberController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return View
     */
    public function index()
    {
        $subscribers = Subscriber::withCount('messages')->get();
        return view('subscribers.index') ->with('subscribers', $subscribers);
    }

    /**
     * Display the specified resource.
     *
     * @param  Subscriber $subscriber
     * @return View
     */
    public function show(Subscriber $subscriber)
    {
        $subscriber->load('messages');
        return view('subscribers.show')->with('subscriber', $subscriber);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  Subscriber  $subscriber
     * @return RedirectResponse
     * @throws \Exception
     */
    public function destroy(Subscriber $subscriber)
    {
        $subscriber->delete();
        return redirect()->back();
    }
}
