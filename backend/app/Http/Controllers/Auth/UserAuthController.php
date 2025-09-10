<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\User;
use Exception;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Database\QueryException;
use Illuminate\Validation\ValidationException;
use Illuminate\Support\Facades\Log;


class UserAuthController extends Controller
{
    // Function for the User Registration
    public function register(Request $request) {
        try {
            $validated = $request->validate([
                'name' => 'required|string|min:3',
                'email' => 'required|string|email|unique:users',
                'password'=>'required|min:8'
            ]);

            $user = User::create([
                'name' => $validated['name'],
                'email' => $validated['email'],
                'password' => Hash::make($validated['password']),
            ]);

            return response()->json([
                'success' => true,
                'message' => 'User Created',
                'data' => $user->id,
            ], 201);

        } catch (ValidationException $e) {
            Log::warning('Registration Validation failed', [
                'ip' => $request->ip(),
                'errors' => $e->errors(),
            ]);

            return response()->json([
                'success' => false,
                'message' => 'Les données fournies sont invalides.',
                'all_errors' => $e->errors()
            ], 422);

        } catch (QueryException $e) {
            Log::error('User Creation Database error', [
                'ip' => $request->ip(),
                'message' => $e->getMessage(),
            ]);

            return response()->json([
                'success' => false,
                'message' => 'Une erreur est survenue. Veuillez réessayer plus tard.',
                'query_error' => $e->getMessage()
            ], 500);

        } catch (\Exception $e) {
            Log::error('Registration Creation Unexpected error', [
                'ip' => $request->ip(),
                'message' => $e->getMessage(),
            ]);

            return response()->json([
                'success' => false,
                'message' => 'Une erreur inattendue est survenue. Veuillez réessayer plus tard.',
                'exception_error' => $e->getMessage()
            ], 500);
        }
    }

    // Function for the Login
    public function login(Request $request) {
        try {
            $validated = $request->validate([
                'email' => 'required|string|email',
                'password' => 'required|string'
            ]);

            $user = User::findOrFail($validated['email']);

            if(!$user) {
                return response()->json([
                    'success' => false,
                    'message' => 'Compte n\'existe pas dans notre système'
                ], 404);
            }

            $token = $user->createToken($user->name.'-AuthToken')->plainTextToken;

            return response()->json([
                'success' => true,
                'access_token' => $token,
            ], 200);

        } catch (ValidationException $e) {

            return response()->json([
                'success' => false,
                'message' => 'Les données fournies sont invalides.',
                'all_errors' => $e->errors()
            ], 422);

        } catch(ModelNotFoundException $e) {

            return response()->json([
                'success' => false,
                'message' => 'Ce compte n\'existe pas dans notre système',
            ], 404);

        } catch (QueryException $e) {

            return response()->json([
                'success' => false,
                'message' => 'Une erreur est survenue. Veuillez réessayer plus tard.',
                'query_error' => $e->getMessage()
            ], 500);

        } catch (\Exception $e) {

            return response()->json([
                'success' => false,
                'message' => 'Une erreur est survenue. Veuillez réessayer plus tard.',
                'exception_error' => $e->getMessage()
            ], 500);
        }
    }


    // Function for the Logout
    public function logout(){
        try {
            $currentUser = Auth::user();

            $currentUser->tokens()->delete();

            return response()->json([
                'success' => true,
                "message"=>"logged out"
            ], 200);

        } catch (\Exception $e) {
            Log::error('Logout Unexpected error', [
                'message' => $e->getMessage(),
            ]);

            return response()->json([
                'success' => false,
                'message' => 'Une erreur est survenue. Veuillez réessayer plus tard.',
                'exception_error' => $e->getMessage()
            ], 500);
        }
    }
}
