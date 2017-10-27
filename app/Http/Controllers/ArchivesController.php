<?php

namespace App\Http\Controllers;

use App\Http\Requests\ArchivesRequest;
use App\Repositoies\ArchivesRepository;
use Auth;

class ArchivesController extends Controller
{
    protected $archivesRepository;

    public function __construct(ArchivesRepository $archivesRepository)
    {
        $this->middleware(['auth'])->except('index','show');
        $this->archivesRepository = $archivesRepository;
    }

    public function index()
    {
        return 'index';
    }

    public function create()
    {
        return view('Archives.create');
    }

    public function store(ArchivesRequest $request)
    {
        $data = [
            'title'   => $request->get('title'),
            'body'    => $request->get('body'),
            'user_id' => Auth::user()->id
        ];
        $archive = $this->archivesRepository->create($data);
        return redirect()->route('archives.show',['id' => $archive->id]);
    }

    public function show($id)
    {
        $archive = $this->archivesRepository->ByID($id);
        return view('archives.show',compact('archive'));
    }

    public function edit($id)
    {
        $archive = $this->archivesRepository->ByID($id);
        return view('archives.edit',compact('archive'));
    }

    public function update(ArchivesRequest $request, $id)
    {
        $archive = $this->archivesRepository->ByID($id);
        $archive->update([
            'title' => $request->get('title'),
            'body'  => $request->get('body')
        ]);
        return view('archives.show',compact('archive'));
    }

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
