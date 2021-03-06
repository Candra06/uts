<?php

namespace App\Http\Controllers;

use DateTime;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class ArtikelController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {

        $path = storage_path() . '/app/public/data.json';
        // dd($path);
        $res = file_get_contents($path);
        $data = json_decode($res, true);
        // return $data;
        return view('index', compact('data'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        return view('create');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {

        $request->validate([
            'title' => 'required',
            'author' => 'required',
            'content' => 'required',
        ]);
        // return $request;
        $ds = file_get_contents(storage_path() . '/app/public/data.json');
        $datas = json_decode($ds);
        $id = count($datas) + 1;
        foreach ($datas as $key) {
            if ($key->id == $id) {
                $id = $id+1;
            }
        }
        // return count($datas);
        $data = [
            'id' => $id,
            'title' => $request['title'],
            'author' => $request['author'],
            'content' => $request['content'],
            'created_at' => now(),
            'updated_at' => null,
        ];

        if ($datas != null) {
            array_push($datas, $data);
        } else {
            $datas = $data;
        }
        try {
            $save = json_encode($datas, JSON_PRETTY_PRINT);
            file_put_contents(storage_path() . '/app/public/data.json', stripslashes($save));
            $path = storage_path() . '/app/public/data.json';
            $res = file_get_contents($path);
            $data = json_decode($res, true);

            return view('index', compact('data'));
        } catch (\Throwable $th) {
            return $th;
            return view('create');
        }
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $ds = file_get_contents(storage_path() . '/app/public/data.json');
        $datas = json_decode($ds);
        $data = array();
        foreach ($datas as $item) {
            if ($item->id == $id) {
                $data['id'] = $item->id;
                $data['title'] = $item->title;
                $data['content'] = $item->content;
                $data['author'] = $item->author;
                $data['created_at'] = $item->created_at;
            }
        }
        return view('show', compact('data'));
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        $ds = file_get_contents(storage_path() . '/app/public/data.json');
        $datas = json_decode($ds);
        $data = array();
        foreach ($datas as $item) {
            if ($item->id == $id) {
                $data['id'] = $item->id;
                $data['title'] = $item->title;
                $data['content'] = $item->content;
                $data['author'] = $item->author;
                $data['created_at'] = $item->created_at;
            }
        }
        return view('edit', compact('data'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        $request->validate([
            'title' => 'required',
            'author' => 'required',
            'content' => 'required'
        ]);

        $ds = file_get_contents(storage_path() . '/app/public/data.json');
        $datas = json_decode($ds);
        $input['title'] = $request['title'];
        $input['author'] = $request['author'];
        $input['content'] = $request['content'];
        $input['updated_at'] = now();
        foreach ($datas as $item) {
            if ($item->id == $id) {

                $item->title = $input['title'];
                $item->content = $input['content'];
                $item->author = $input['author'];
                $item->updated_at = $input['updated_at'];
            }
        }

        try {
            $save = json_encode($datas, JSON_PRETTY_PRINT);
            file_put_contents(storage_path() . '/app/public/data.json', stripslashes($save));
            $path = storage_path() . '/app/public/data.json';
            $res = file_get_contents($path);
            $data = json_decode($res, true);
            return view('index', compact('data'));
        } catch (\Throwable $th) {
            return $th;
        }
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {

        $ds = file_get_contents(storage_path() . '/app/public/data.json');
        $datas = json_decode($ds);
        $index =0;
        foreach ($datas as $key) {
            if ($key->id == $id) {
                unset($datas[$index]);
            }
            $index++;
        }
        try {
            $save = json_encode(array_values($datas), JSON_PRETTY_PRINT);
            file_put_contents(storage_path() . '/app/public/data.json', stripslashes($save));
            $path = storage_path() . '/app/public/data.json';
            return redirect('/artikel');
        } catch (\Throwable $th) {
            return $th;
        }
    }
}
