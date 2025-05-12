<?php

namespace App\Http\Controllers;

use App\Kanban\TaskKanban;
use Illuminate\Http\Request;

class WorkflowController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    public function getKanban(TaskKanban $kanban)
    {
        // dd($kanban);
        return $kanban->render('kanban');
    }
}
