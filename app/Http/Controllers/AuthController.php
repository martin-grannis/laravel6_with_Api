<?php
namespace App\Http\Controllers;
use App\Http\Controllers\Controller;
use Illuminate\Foundation\Auth\AuthenticatesUsers;
use Illuminate\Http\Request;
use App\Models\User;
use Auth;
use Cookie;
use Validator;

class AuthController extends Controller
{
    use AuthenticatesUsers;

    public function __construct()
    {
        //$this->middleware('guest')->except('logout');
    }


    public function register(Request $request)
    {

        $validator = Validator::make($request->all(), [
            'name' => 'required|min:3',
            'email' => 'required|email|unique:users',
            'password' => 'required|min:6',
        ]);

        if ($validator->fails()) {

            // build string of errors
            $errors = $validator->errors();
            $retStr="";
            foreach ($errors->all() as $message) {
                $retStr .= $message.",";
            }
            // drop last comma
            $retStr= $retStr.substr(0,strlen($retStr)-1);


            return response()->json(
                ['error' => $retStr],
                401
            );

            //     return redirect('post/create')
        //                 ->withErrors($validator)
        //                 ->withInput();
        // }
        //$this->validate($request, [
        //     $request->validate( [
        //         'name' => 'required|min:3',
        //         'email' => 'required|email|unique:users',
        //         'password' => 'required|min:6',
        // ]);
        }
        $user = User::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => bcrypt($request->password)
        ]);
 
 //       $token = $user->createToken('TutsForWeb')->accessToken;
 
          return $this->login($request);

 
     //   return response()->json(['message' => "registered ok thanks"], 200);
    }
 

    public function login(Request $request)
    {
        $request->validate([
            'email' => 'required|string|email',
            'password' => 'required|string',
        ]);
        $credentials = request(['email', 'password']);
        if (Auth::attempt($credentials)) {
            $user = Auth::user();
            $token =  $user->createToken('Personal Access Token')->accessToken;
            $cookie = $this->getCookieDetails($token);
           // $cookie['name'].='-'.$user->email.rand(10,1000000); // belt and braces on cookie names
            return response()
                ->json([
                    'logged_in_user' => $user,
                    'token' => $token,
                ], 200)
                ->cookie($cookie['name'], $cookie['value'], $cookie['minutes'], $cookie['path'], $cookie['domain'], $cookie['secure'], $cookie['httponly'], $cookie['samesite']);
        } else {
            return response()->json(
                ['error' => 'invalid-credentials'], 422);
        }
    }
    private function getCookieDetails($token)
    {
        return [
            'name' => '_token',
            'value' => $token,
            'minutes' => 1440,
            'path' => null,
            'domain' => null,
            //'secure' => true, // for production
            'secure' => null, // for localhost
            'httponly' => true,
            'samesite' => true,
        ];
    }
    public function logout(Request $request)
    {
        $request->user()->token()->revoke();
        $cookie = Cookie::forget('_token');
        return response()->json([
            'message' => 'successful-logout'
        ])->withCookie($cookie);
    }

    public function userDetails(Request $request)
    {
        return response()->json(['user' => auth()->user()], 200);
    }

}