<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreUserRequest;
use App\Http\Requests\UpdateUserRequest;
use App\Http\Resources\UserResource;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Password;

class AuthController extends Controller
{
    /**
     * Register a new user.
     */
    public function register(StoreUserRequest $request)
    {
        $validated = $request->validated();

        $user = User::create([
            'name' => $validated['name'],
            'email' => $validated['email'],
            'password' => bcrypt($validated['password']),
        ]);

        $token = $user->createToken('auth_token')->plainTextToken;

        return (new UserResource($user))->additional([
            'message' => 'User registered successfully.',
            'access_token' => $token,
            'token_type' => 'Bearer',
        ])->response()
        ->setStatusCode(Response::HTTP_CREATED);
    }

    /**
     * Login user and create token.
     */
    public function login(Request $request)
    {
        $request->validate([
            'email' => 'required|email',
            'password' => 'required',
        ]);

        $user = User::where('email', $request->email)->first();

        if (!$user || !$user->checkPassword($request->password)) {
            return response()->json(['message' => 'Invalid credentials.'], Response::HTTP_UNAUTHORIZED);
        }

        $token = $user->createToken('auth_token')->plainTextToken;

        return (new UserResource($user))
            ->additional([
                'message' => 'User logged in successfully.',
                'access_token' => $token,
                'token_type' => 'Bearer',
            ])->response()
            ->setStatusCode(Response::HTTP_OK);
    }

    /**
     * Logout user (revoke tokens).
     */
    public function logout(Request $request)
    {
        $request->user()->tokens()->delete();
        return response()->json(['message' => 'User logged out successfully.'], Response::HTTP_OK);
    }

    /**
     * Forget password (send reset link).
     */
    public function forgotPassword(Request $request)
    {
        $request->validate(['email' => 'required|email']);

        $user = User::where('email', $request->email)->first();

        if (!$user) {
            return response()->json(['message' => 'User not found.'], Response::HTTP_NOT_FOUND);
        }

        $status = Password::sendResetLink($user->email);

        if ($status !== Password::RESET_LINK_SENT) {
            return response()->json(['message' => 'Failed to send reset link.'], Response::HTTP_INTERNAL_SERVER_ERROR);
        }

        return response()->json(['message' => 'Password reset link sent to your email.'], Response::HTTP_OK);
    }

    /**
     * Verify email.
     */
    public function verifyEmail(Request $request)
    {
        $request->validate(['email' => 'required|email']);
        $user = User::where('email', $request->email)->first();

        if (!$user) {
            return response()->json(['message' => 'User not found.'], Response::HTTP_NOT_FOUND);
        }

        if ($user->hasVerifiedEmail()) {
            return response()->json(['message' => 'Email already verified.'], Response::HTTP_OK);
        }

        if (!$user->markEmailAsVerified()) {
            return response()->json(['message' => 'Failed to verify email.'], Response::HTTP_INTERNAL_SERVER_ERROR);
        }

        return response()->json(['message' => 'Email verified successfully.'], Response::HTTP_OK);
    }

    /**
     * resend verification email.
     */
    public function resendVerificationEmail(Request $request)
    {
        $request->validate(['email' => 'required|email']);
        $user = User::where('email', $request->email)->first();

        if (!$user) {
            return response()->json(['message' => 'User not found.'], Response::HTTP_NOT_FOUND);
        }

        if ($user->hasVerifiedEmail()) {
            return response()->json(['message' => 'Email already verified.'], Response::HTTP_OK);
        }

        $status = Password::sendResetLink($user->email);

        if ($status !== Password::RESET_LINK_SENT) {
            return response()->json(['message' => 'Failed to send verification email.'], Response::HTTP_INTERNAL_SERVER_ERROR);
        }

        return response()->json(['message' => 'Verification email resent successfully.'], Response::HTTP_OK);
    }

    /**
     * Update the authenticated user's profile.
     */
    public function update(UpdateUserRequest $request)
    {
        $user = $request->user();
        $user->update($request->validated());

        return (new UserResource($user))
            ->additional([
                'message' => 'Profile updated successfully.',
            ])->response()
            ->setStatusCode(Response::HTTP_OK);
    }
}
