<?php

namespace WorkLogger\Http\Controllers\Project;

use Illuminate\Http\Request;
use WorkLogger\Domain\Project\Project;
use WorkLogger\Http\Controllers\Controller;
use WorkLogger\Http\Response\JsonResponse;

class ProjectApiController extends Controller
{
    public function getList()
    {
        $user = \Auth::user();
        return new JsonResponse($user->projects->all());
    }
}
