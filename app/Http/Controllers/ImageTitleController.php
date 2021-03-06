<?php

namespace App\Http\Controllers;

use App\Http\Requests\CreateImageTitleRequest;
use App\Http\Requests\UpdateImageTitleRequest;
use App\Repositories\ImageTitleRepository;
use App\Http\Controllers\AppBaseController;
use Illuminate\Http\Request;
use App\Models\ImageTitle; 
use App\Models\Image;
use App\Models\Title;
use Flash;
use Response;

class ImageTitleController extends AppBaseController
{
    /** @var  ImageTitleRepository */
    private $imageTitleRepository;

    public function __construct(ImageTitleRepository $imageTitleRepo)
    {
        $this->imageTitleRepository = $imageTitleRepo;
    }

    /**
     * Display a listing of the ImageTitle.
     *
     * @param Request $request
     *
     * @return Response
     */
    public function index(Request $request)
    { 

        $imageTitles = ImageTitle::orderBy("row_num","asc")->get();

        // si la solicitud es a tavés de ajax
        if ($request->ajax()) {
            foreach ($imageTitles as $key) {
                $titles[$key->row_num] = $key->title;
                $images[$key->row_num] = $key->image;
            }
            return response()->json(["titles" => $titles, "images" => $images]);
        }
        
        return view('image_titles.index')
            ->with('imageTitles', $imageTitles);
    }

    /**
     * Show the form for creating a new ImageTitle.
     *
     * @return Response
     */
    public function create()
    {
        $titles = Title::pluck('description', 'id');
        $images = Image::pluck('path', 'id');

        return view('image_titles.create', compact('titles', 'images'));
    }

    /**
     * Store a newly created ImageTitle in storage.
     *
     * @param CreateImageTitleRequest $request
     *
     * @return Response
     */
    public function store(CreateImageTitleRequest $request)
    {
        // validar si la fila seleccionada ya está en uso
        $row_num = ImageTitle::where('row_num',$request{'row_num'})->get();
        if (count($row_num) >= 1) {
            Flash::error('La fila seleccionada ya está en uso por otra relación.');

            return redirect(route('imageTitles.index'));
        }
        
        $input = $request->all();

        $imageTitle = $this->imageTitleRepository->create($input);

        Flash::success('Relación registrada exitósamente.');

        return redirect(route('imageTitles.index'));
    }

    /**
     * Display the specified ImageTitle.
     *
     * @param int $id
     *
     * @return Response
     */
    public function show($id)
    {
        $imageTitle = $this->imageTitleRepository->find($id);

        if (empty($imageTitle)) {
            Flash::error('Relación no encontrada');

            return redirect(route('imageTitles.index'));
        }

        return view('image_titles.show')->with('imageTitle', $imageTitle);
    }

    /**
     * Show the form for editing the specified ImageTitle.
     *
     * @param int $id
     *
     * @return Response
     */
    public function edit($id)
    {
        $imageTitle = $this->imageTitleRepository->find($id);

        if (empty($imageTitle)) {
            Flash::error('Relación no encontrada');

            return redirect(route('imageTitles.index'));
        }

        $titles = Title::pluck('description', 'id');
        $images = Image::pluck('path', 'id');

        return view('image_titles.edit', compact('imageTitle', 'titles', 'images'));
    }

    /**
     * Update the specified ImageTitle in storage.
     *
     * @param int $id
     * @param UpdateImageTitleRequest $request
     *
     * @return Response
     */
    public function update($id, UpdateImageTitleRequest $request)
    {
        $imageTitle = $this->imageTitleRepository->find($id);

        if (empty($imageTitle)) {
            Flash::error('Relación no encontrada');

            return redirect(route('imageTitles.index'));
        }
        
        // validar si la fila seleccionada ya está en uso
        if ($request{'row_num'} != $imageTitle->row_num) {
            $row_num = ImageTitle::where('row_num',$request{'row_num'})->get();
            if (count($row_num) >= 1) {
                Flash::error('La fila seleccionada ya está en uso por otra relación.');

                return redirect(route('imageTitles.index'));
            }
        }

        $imageTitle = $this->imageTitleRepository->update($request->all(), $id);

        Flash::success('Relación actualizada exitósamente.');

        return redirect(route('imageTitles.index'));
    }

    /**
     * Remove the specified ImageTitle from storage.
     *
     * @param int $id
     *
     * @throws \Exception
     *
     * @return Response
     */
    public function destroy($id)
    {
        $imageTitle = $this->imageTitleRepository->find($id);

        if (empty($imageTitle)) {
            Flash::error('Relación no encontrada');

            return redirect(route('imageTitles.index'));
        }

        $this->imageTitleRepository->delete($id);

        Flash::success('Relación borrada exitósamente');

        return redirect(route('imageTitles.index'));
    }
}
