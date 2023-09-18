<?php

namespace App\Http\Controllers;

use App\Models\NewsLetter;
use Exception;
use Illuminate\Contracts\View\Factory;
use Illuminate\Http\Request;
use Illuminate\View\View;

/**
 * Class SubscriberController
 */
class SubscriberController extends AppBaseController
{
    /**
     * @param  Request  $request
     * @return Factory|View
     *
     * @throws Exception
     */
    public function index()
    {
        return view('subscribers.index');
    }

    /**
     * Remove the specified NewsLetter from storage.
     *
     * @param  NewsLetter  $newsLetter
     * @return NewsLetter
     *
     * @throws Exception
     */
    public function destroy(NewsLetter $newsLetter)
    {
        $newsLetter->delete();

        return $this->sendSuccess(__('messages.flash.newsletter_delete'));
    }
}
