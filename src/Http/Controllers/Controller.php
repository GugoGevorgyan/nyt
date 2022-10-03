<?php

declare(strict_types=1);

namespace Src\Http\Controllers;

use Illuminate\Contracts\Foundation\Application;
use Illuminate\Contracts\Routing\ResponseFactory;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Foundation\Bus\DispatchesJobs;
use Illuminate\Foundation\Validation\ValidatesRequests;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Routing\Controller as BaseController;
use JsonException;

use function count;
use function redis;

/**
 * Class Controller
 * @package Src\Http\Controllers
 */
class Controller extends BaseController
{
    use AuthorizesRequests;
    use DispatchesJobs;
    use ValidatesRequests;

    ////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
    ///////////////////////////////DEV ONLY/////////////////////////////////////////////////////////
    /**
     * @param  Request  $request
     * @return
     * @throws JsonException
     */
    public function stores(Request $request): Response|Application|ResponseFactory
    {
        $datum = redis()->hgetall('tester:1');

        if ($datum) {
            $iterate = 0;

            foreach ($datum as $key => $data) {
                ++$iterate;
                if ($iterate === count($datum)) {
                    redis()->hmset('tester:1', [++$iterate => json_encode($request->all(), JSON_THROW_ON_ERROR | JSON_THROW_ON_ERROR)]);
                }
            }
        } else {
            redis()->hmset('tester:1', (array)json_encode($request->all(), JSON_THROW_ON_ERROR | JSON_THROW_ON_ERROR));
        }

        $response = redis()->hgetall('tester:1');

        return response(['message' => 'ok', '_payload' => $response]);
    }

    /**
     * @return Application|ResponseFactory|Response
     */
    public function gets(): Response|Application|ResponseFactory
    {
        $response = redis()->hgetall('tester:1');

        return response(['message' => 'ok', '_payload' => $response]);
    }

    /**
     * @return Application|ResponseFactory|Response
     */
    public function deletes(): Response|Application|ResponseFactory
    {
        $datum = redis()->hgetall('tester:1');

        foreach ($datum as $key => $data) {
            redis()->hDel('tester:1', $key);
        }

        return response(['message' => 'all deleted']);
    }
}
