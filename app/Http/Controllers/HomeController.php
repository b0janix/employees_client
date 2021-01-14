<?php

namespace App\Http\Controllers;

use App\Models\Token;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;

class HomeController extends Controller
{

    public function index(Request $request)
    {
        if (!$request->session()->has('token')) {
            $request->session()->put('token',false);
        }
        $tokenObj = new Token();
        $tokenObj = $tokenObj->latest()->first();
        if (!empty($tokenObj)) {
            $expires = new \DateTime($tokenObj->expires_at);
            $now = new \DateTime(date('Y-m-d H:i:s'));
            if ($expires > $now) {
                $request->session()->put('token',true);
            }
            else {
                $request->session()->put('token',false);
            }
        }
        else {
            $request->session()->put('token',false);
        }
        if ($data = session()->get('data')) {
            return view('home')->with('data', $data);
        }
        return view('home');
    }

    public function redirect()
    {
        $query = http_build_query(
            [
                'client_id' => env('CLIENT_ID'),
                'client_name' => env('CLIENT_NAME'),
                'redirect_uri' => env('REDIRECT_URI'),
            ]
        );

        return redirect('http://localhost:8080/oauth/authorize?' . $query);
    }

    public function callback(Request $request)
    {
            $params = $request->only('auth_code','ip');
            $code = $params['auth_code'];
            $ip = $params['ip'];

            if (empty($code) || empty($ip)) {
                return redirect('http://127.0.0.1:8081')
                    ->withErrors('Invalid parameters.');
            }

            $arr = explode(':', base64_decode($code));
            $username = $arr[0];
            if(!password_verify(env('PASSWORD'), $arr[1])) {
                return redirect('http://127.0.0.1:8081')
                    ->withErrors('Invalid credentials.');
            }
            $password = env('PASSWORD');

            $response = Http::withHeaders(['Authorization' => $code])
            ->post("http://$ip:8080/api/oauth/token",[
                'username' => $username,
                'password' => $password,
                'client_id' => env('CLIENT_ID'),
                'client_secret' => env('CLIENT_SECRET'),
                'grant_type' => 'password'
            ]);

            return $this->processResponse($request, $response->json());
    }

    private function processResponse($request, $response) {
        if(isset($response['status']) && $response['status'] === 'success') {
            $token = $response['data']['access_token'];
            $expires = $response['data']['expires_at'];
            $tokenObj = Token::where('access_token', $token)->first();
            if(empty($tokenObj)) {
                $tokenObj = new Token();
                $this->saveToken($tokenObj, $token, $expires);
            }
            else {
                $expired = new \DateTime($tokenObj->expires_at);
                $now = new \DateTime(date('Y-m-d H:i:s'));
                if ($now > $expired) {
                    $this->saveToken($tokenObj, $token, $expires);
                }
            }
            $request->session()->put('token',true);
            return redirect('http://127.0.0.1:8081');
        }
        return redirect('http://127.0.0.1:8081')->withErrors('Token missing');

    }

    private function saveToken($tokenObj, $token, $expires)
    {
        try {
            $tokenObj->create(['access_token' => $token, 'expires_at' => $expires]);
        }
        catch(\Exception $e) {
            return redirect('http://127.0.0.1:8081')
                ->withErrors($e->getMessage());
        }
    }

    public function getEmployees(Request $request)
    {
        $tokenObj = new Token();
        $tokenObj = $tokenObj->latest()->first();
        if (!empty($tokenObj)) {
            $expires = new \DateTime($tokenObj->expires_at);
            $now = new \DateTime(date('Y-m-d H:i:s'));
            if ($expires > $now) {
                $response = Http::withHeaders(['Access-Token' => $tokenObj->access_token])
                    ->get("http://technical_test.client.cosmicdevelopment.com/api/employee/list/")
                    ->json();

                return $this->processResponse2($request, $response);
            }
            else {
                $request->session()->put('token',false);
            }
        }
        return redirect('http://127.0.0.1:8081');
    }

    private function processResponse2($request, $response)
    {
        $data = [];
        if(isset($response['status'])) {
            if ($response['status'] === 'error') {
                $request->session()->put('token',false);
                return redirect('http://127.0.0.1:8081')->withErrors('There was a problem, data could not be obtained.');
            }
            if ($response['status'] === 'success') {
                $data = $response['data'];
            }
        }

        return redirect('/')->with('data', $data);
    }

    public function deleteTokens(Request $request)
    {
        Token::truncate();
        $request->session()->put('token',false);
        return redirect('http://127.0.0.1:8081');
    }
}
