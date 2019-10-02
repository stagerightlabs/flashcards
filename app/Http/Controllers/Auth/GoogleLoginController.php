<?php

namespace App\Http\Controllers\Auth;

use App\User;
use App\Tenant;
use App\Mail\UserRegistered;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Mail;
use Laravel\Socialite\Facades\Socialite;

class GoogleLoginController extends Controller
{
    /**
     * Redirect the user to the GitHub authentication page.
     *
     * @return \Illuminate\Http\Response
     */
    public function redirectToProvider()
    {
        return Socialite::driver('google')->redirect();
    }

    /**
     * Obtain the user information from GitHub.
     *
     * @return \Illuminate\Http\Response
     */
    public function handleProviderCallback()
    {
        // Ask Socialite to process the OAuth callback
        $oauth = Socialite::driver('google')->user();

        // Does this user account exist?
        if ($user = User::where('email', $oauth->user['email'])->first()) {

            // Check for an updated avatar
            $user->avatar = $oauth->avatar;
            $user->save();

            // Initiate the user session
            return $this->loginUserAndRedirect($user);
        }

        // The user account does not exist

        // Fetch the tenant
        $tenant = Tenant::firstOrCreate(['name' => $oauth->user['hd']]);

        // Create the user
        $user = User::create([
            'name' => $oauth->name,
            'email' => $oauth->user['email'],
            'avatar' => $oauth->avatar,
            'tenant_id' => $tenant->id,
        ]);

        // Send an admin notification.
        if ($notify = config('flashcards.admin.notify')) {
            Mail::to($notify)->queue(new UserRegistered($user));
        }

        // Initiate the user session
        return $this->loginUserAndRedirect($user);
    }

    /**
     * Initiate a user session and redirect to the dashboard.
     *
     * @param User $user
     * @return \Illuminate\Http\Response
     */
    protected function loginUserAndRedirect(User $user)
    {
        Auth::login($user);

        return redirect()->route('home');
    }
}
