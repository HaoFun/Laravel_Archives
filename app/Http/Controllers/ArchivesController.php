<?php

namespace App\Http\Controllers;

use App\Repositoies\ArchivesRepository;
use Illuminate\Http\Request;
use Auth;

class ArchivesController extends Controller
{
    protected $archivesRepository;

    public function __construct(ArchivesRepository $archivesRepository)
    {
        $this->middleware(['auth']);
        $this->archivesRepository = $archivesRepository;
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        return 'index';
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        return view('Archives.create');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $data = [
            'title'   => $request->get('title'),
            'body'    => $request->get('body'),
            'user_id' => Auth::user()->id
        ];
        $archive = $this->archivesRepository->create($data);
        return redirect()->route('archives.show',['id' => $archive->id]);
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $archive = $this->archivesRepository->ByID($id);
        return view('archives.show',compact('archive'));
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        $archive = $this->archivesRepository->ByID($id);
        return view('archives.edit',compact('archive'));
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
        $archive = $this->archivesRepository->ByID($id);
        $archive->update([
            'title' => $request->get('title'),
            'body'  => $request->get('body')
        ]);
        return view('archives.show',compact('archive'));
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $archive = $this->archivesRepository->ByID($id);
        if($archive !== null)
        {
            $archive->delete();
            return action('ArchivesController@index');
        }
        flash('刪除失敗，請重試')->error();
        return back();
    }
}
