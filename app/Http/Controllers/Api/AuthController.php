<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Http\Requests\Api\RegisterRequest;
use App\Http\Requests\Api\LoginRequest;
use App\Services\UserService;
use Illuminate\Support\Facades\Auth;
use App\Http\Resources\User as UserResource;

class AuthController extends Controller
{
    protected $userService;

    public function __construct(UserService $userService)
    {
        $this->userService = $userService;
    }

    /**
     * @OA\Post(
     *      path="/api/register",
     *      operationId="registerUser",
     *      tags={"User"},
     *      summary="Register New User",
     *      description="Create new User",
     *      @OA\RequestBody(
     *         @OA\MediaType(
     *             mediaType="application/json",
     *             @OA\Schema(
     *                 @OA\Property(
     *                     property="name",
     *                     type="string"
     *                 ),
     *                 @OA\Property(
     *                     property="email",
     *                     type="string"
     *                 ),
     *                 @OA\Property(
     *                     property="password",
     *                     type="string"
     *                 ),
     *                 example={"name": "Jonathan", "email": "jonathan@gmail.com", "password": "jSuds(*#@*"}
     *             )
     *         )
     *     ),
     *      @OA\Response(
     *          response=200,
     *          description="Successful operation",
     *          content={
     *             @OA\MediaType(
     *                 mediaType="application/json",
     *                 @OA\Schema(
     *                     @OA\Property(
     *                         property="access_token",
     *                         type="string",
     *                         description="Authorization Token"
     *                     ),
     *                     @OA\Property(
     *                         property="token_type",
     *                         type="string",
     *                         description="Type of Token"
     *                     ),
     *                     example={
     *                         "access_token": "eyJ0eXAiOiJKV1QiLCJhbGciOiJSUzI1NiJ9.eyJhdWQiOiIxIiwianRpIjoiNWY2YmI2N2QzZDRiNTNkODFkZDZkN2ZiOTk1YmZhNzBmMzgwODNmMDk4MGVjYzI2MTQ4MjhiNzQ1ODcyY2UxOTM3NDI3NThhZDVlNzIwMmQiLCJpYXQiOjE2MDc0NDYxODUsIm5iZiI6MTYwNzQ0NjE4NSwiZXhwIjoxNjM4OTgyMTg1LCJzdWIiOiIyIiwic2NvcGVzIjpbXX0.fcnYNE8v8KQX_K_1WZZB6MWWTS3IASu1CV8RmyBXKc4i0aAw_3OfU_91InzLw3PJrPRJm-pmSPFGb19E9J_QyOGOPBwrQoaUIF6Ir3PTFBEpT8P9geswFz3w53HQcqF954MgwM9-JI7aZYsqHRdJu1blUd8vquuhPsLVT0EKvKT7kIZir1IutuArQq1-xTtb_myh7I_r0-s1GhFEh-wBYU7R5GHFyAT_qmYDns9M_J-suLWMeXcugBDiYOBTrWFv_54TwJIW8L-RYrRXdetekn0ndxXqn4StPvAUpXTBnpqTphPHF2o8lIy0wptCgzrQsV5VXzkj80pbWPZDY01O-I1aMpHSCDwzU4BH3It2LLxLezsln881NkPRD5d-hXQeDCJ88ZnTGk5H3pcUxGrhHs8dYFU9CQ9C1EeKuVYt3XsT1Cbo9Ha-qN-xly_4WbIbNnltnE_AOo92PvWeOXrvSoFCbi6yTKR_ygJskFDW5menTIzEbKbmcvwhfdDPU6iN_V7nA2uQXGt6ApKzj5WsXwUiTaZpPkivEc7n1dF_nriz93AsaVtrB_dmuEsS28q8zIMt3F9uYSwwFS0jL3vWvGhxUBmWy440I6RePWEnTc_fTW-o_BC-iBvhNspwGyR4QySAnP48aMPD0muHij_Q-ZEMafKR_26kFSUC08UXFOA",
     *                         "token_type": "Bearer"
     *                     }
     *                 )
     *             )
     *         }
     *       ),
     *      @OA\Response(
     *          response=400,
     *          description="Bad Request"
     *      ),
     *      @OA\Response(
     *          response=401,
     *          description="Unauthenticated",
     *      ),
     *      @OA\Response(
     *          response=403,
     *          description="Forbidden"
     *      )
     * )
     */

    public function register(RegisterRequest $request)
    {
        $user = $this->userService->create($request->all());
        $token = $user->createToken('Laravel Password Grant Client')->accessToken;

        return response()->json([
            'access_token' => $token,
            'token_type' => 'Bearer',
        ], 200);
    }
    /**
     * @OA\Post(
     *      path="/api/login",
     *      operationId="loginUser",
     *      tags={"User"},
     *      summary="Login User",
     *      description="Login user to Generate Token",
     *      @OA\RequestBody(
     *         @OA\MediaType(
     *             mediaType="application/json",
     *             @OA\Schema(
     *                 @OA\Property(
     *                     property="name",
     *                     type="string"
     *                 ),
     *                 @OA\Property(
     *                     property="email",
     *                     type="string"
     *                 ),
     *                 @OA\Property(
     *                     property="password",
     *                     type="string"
     *                 ),
     *                 example={"name": "Jonathan", "email": "jonathan@gmail.com", "password": "jSuds(*#@*"}
     *             )
     *         )
     *     ),
     *      @OA\Response(
     *          response=200,
     *          description="Successful operation",
     *          content={
     *             @OA\MediaType(
     *                 mediaType="application/json",
     *                 @OA\Schema(
     *                     @OA\Property(
     *                         property="access_token",
     *                         type="string",
     *                         description="Authorization Token"
     *                     ),
     *                     @OA\Property(
     *                         property="token_type",
     *                         type="string",
     *                         description="Type of Token"
     *                     ),
     *                     example={
     *                         "access_token": "eyJ0eXAiOiJKV1QiLCJhbGciOiJSUzI1NiJ9.eyJhdWQiOiIxIiwianRpIjoiNWY2YmI2N2QzZDRiNTNkODFkZDZkN2ZiOTk1YmZhNzBmMzgwODNmMDk4MGVjYzI2MTQ4MjhiNzQ1ODcyY2UxOTM3NDI3NThhZDVlNzIwMmQiLCJpYXQiOjE2MDc0NDYxODUsIm5iZiI6MTYwNzQ0NjE4NSwiZXhwIjoxNjM4OTgyMTg1LCJzdWIiOiIyIiwic2NvcGVzIjpbXX0.fcnYNE8v8KQX_K_1WZZB6MWWTS3IASu1CV8RmyBXKc4i0aAw_3OfU_91InzLw3PJrPRJm-pmSPFGb19E9J_QyOGOPBwrQoaUIF6Ir3PTFBEpT8P9geswFz3w53HQcqF954MgwM9-JI7aZYsqHRdJu1blUd8vquuhPsLVT0EKvKT7kIZir1IutuArQq1-xTtb_myh7I_r0-s1GhFEh-wBYU7R5GHFyAT_qmYDns9M_J-suLWMeXcugBDiYOBTrWFv_54TwJIW8L-RYrRXdetekn0ndxXqn4StPvAUpXTBnpqTphPHF2o8lIy0wptCgzrQsV5VXzkj80pbWPZDY01O-I1aMpHSCDwzU4BH3It2LLxLezsln881NkPRD5d-hXQeDCJ88ZnTGk5H3pcUxGrhHs8dYFU9CQ9C1EeKuVYt3XsT1Cbo9Ha-qN-xly_4WbIbNnltnE_AOo92PvWeOXrvSoFCbi6yTKR_ygJskFDW5menTIzEbKbmcvwhfdDPU6iN_V7nA2uQXGt6ApKzj5WsXwUiTaZpPkivEc7n1dF_nriz93AsaVtrB_dmuEsS28q8zIMt3F9uYSwwFS0jL3vWvGhxUBmWy440I6RePWEnTc_fTW-o_BC-iBvhNspwGyR4QySAnP48aMPD0muHij_Q-ZEMafKR_26kFSUC08UXFOA",
     *                         "token_type": "Bearer"
     *                     }
     *                 )
     *             )
     *         }
     *       ),
     *      @OA\Response(
     *          response=400,
     *          description="Bad Request"
     *      ),
     *      @OA\Response(
     *          response=401,
     *          description="Unauthenticated",
     *      ),
     *      @OA\Response(
     *          response=403,
     *          description="Forbidden"
     *      )
     * )
     */

    public function login(LoginRequest $request)
    {
        $credentials = request(['email', 'password']);

        if (!Auth::attempt($credentials))
            return response()->json([
                'message' => 'Unauthorized'
            ], 401);

        $user = $request->user();
        $tokenResult = $user->createToken('Laravel Personal Access Client');
        $token = $tokenResult->token;

        $token->save();
        return response()->json([
            'access_token' => $tokenResult->accessToken,
            'token_type' => 'Bearer'
        ]);
    }

    public function logout(Request $request)
    {
        $request->user()->token()->revoke();
        return response('Token desabilitado!', 200);
    }

    public function user()
    {
        $user = new UserResource(request()->user());

        return response($user, 200);
    }
}
