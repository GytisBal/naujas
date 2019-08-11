<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class PagesController extends Controller
{
    public function admin(){
        $data = array(
            'page_title'=>'AdminLTE Dashboard',
            'page_description'=>"description"
        );
        return view("layouts.app")->with($data);
    }
}
