<?php

namespace App\Http\Controllers\Admin;

use App\Http\Requests\Admin\Gallery\StoreGalleryValidation;
use App\Http\Requests\Admin\Gallery\UpdateGalleryValidation;
use App\Models\Gallery;
use App\Models\GalleryImage;
use Illuminate\Http\Request;

class GalleryController  extends BaseController
{
    //properties
    protected $base_route = 'admin.gallery';
    protected $view_path = 'admin.gallery';
    protected $panel = 'Gallery';
    protected $folder = 'gallery';
    protected $folder_path;


    public function __construct(Gallery $model)
    {
        $this->model = $model;
        $this->folder_path = 'images' . DIRECTORY_SEPARATOR . $this->folder;
        $this->generateAllMiddlewareByPermission();
    }
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
//        Session::put('success_message','Successfully Loaded '. $this->panel);
        $data = [];
        $data['rows'] = $this->model->rank()->paginated();

        return view(parent::loadCommonDataToView($this->view_path . '.index'), compact('data'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {

        return view(parent::loadCommonDataToView($this->view_path . '.create'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(StoreGalleryValidation $request)
    {
//        dd($request->validated());
        $data = $request->validated();
        if($request->hasFile('main_photo')){
            $this->processImage($data['main_photo'], config('helper.gallery_image'));
            $data['cover_photo'] = $this->file_name;
        }

        $gallery = $this->model->create($data);

        $this->storeGalleryImage($request, $gallery);

        $request->session()->flash('success_message', $this->panel.' '.$gallery->name. ' added Successfully');
        return redirect()->route($this->base_route.'.index');
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $data = [];
        $data['row'] = $this->model->find($id);
        parent::rowExist($data['row']);

        return view(parent::loadCommonDataToView($this->view_path . '.show'), compact('data'));
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        $data = [];
        $data ['row'] = $this->model->find($id);
        parent::rowExist($data['row']);

        return view(parent::loadCommonDataToView($this->view_path . '.edit'), compact('data'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(UpdateGalleryValidation $request, $id)
    {
        $row = $this->model->find($id);
        $data = $request->validated();
        if($request->hasFile('main_photo')){
            $this->processImage($data['main_photo'], config('helper.gallery_image'));
            $data['cover_photo'] = $this->file_name;
            //remove old photo
            @$this->removeFile($this->folder_path.DIRECTORY_SEPARATOR.$row->cover_photo);
            @$this->removeFile($this->folder_path.DIRECTORY_SEPARATOR.'1600_1200_'.$row->cover_photo);
            @$this->removeFile($this->folder_path.DIRECTORY_SEPARATOR.'200_150_'.$row->cover_photo);
        }
        $row->update($data);
        $this->storeGalleryImagesEdit($request, $row);

        $request->session()->flash('success_message',$this->panel.' '. $row->title . ' updated Successfully');
        return redirect()->route($this->base_route.'.index');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $row = $this->model->find($id);
        parent::rowExist($row);

        //remove old photo
        @$this->removeFile($this->folder_path.DIRECTORY_SEPARATOR.$row->cover_photo);
        @$this->removeFile($this->folder_path.DIRECTORY_SEPARATOR.'1600_1200_'.$row->cover_photo);
        @$this->removeFile($this->folder_path.DIRECTORY_SEPARATOR.'200_150_'.$row->cover_photo);

        //remove old images
        if($row->images && $row->images->count() > 0){
            foreach ($row->images as $image){
                @$this->removeFile($this->folder_path.DIRECTORY_SEPARATOR.$image->photo);
                @$this->removeFile($this->folder_path.DIRECTORY_SEPARATOR.'1600_1200_'.$image->photo);
                @$this->removeFile($this->folder_path.DIRECTORY_SEPARATOR.'200_150_'.$image->photo);
            }
        }
        //delete now
        $row->delete();

        return redirect()->route($this->base_route.'.index')
            ->with('success_message', $this->panel . ' deleted Successfully');
    }

    protected function storeGalleryImage(Request $request, Gallery $gallery)
    {
        if($request->has('gallery')) {
            foreach ($request->get('gallery') as $key => $item){
                $file = $request->file('gallery.'.$key.'.gallery_image');
                $this->processImage($file, config('helper.gallery_image'));

                GalleryImage::create([
                    'gallery_id'        => $gallery->id,
                    'photo'             => $this->file_name,
                    'rank'              => $key+1,
                    'alt_text'          => $item['alt_text'],
                    'caption'           => $item['caption'],
                    'status'            => $item['status'],
                ]);
            }
        }
    }

    protected function storeGalleryImagesEdit(Request $request, Gallery $gallery)
    {
//        dd($request->all());
        //$image_thumb_config = config('broadway.product.image-dimensions.gallery-image');
        // upload galleries
        $gallery_image_ids = [];

        if($request->has('gallery')){
            $rank_key=0;
            foreach ($request->get('gallery') as $key => $item) {
                $rank_key++;
                //checking condition either add or update
                $gallery_image_id =  isset($item['id']) ?$item['id']:false ;
                if($gallery_image_id) {
                    //update
                    $gallery_image = GalleryImage::find($gallery_image_id);

                    $file = $request->file('gallery.'.$key.'.gallery_image');
                    if($file) {
                        //upload new image
                        $this->processImage($file, config('helper.gallery_image'));
                        //remove old image and thumbnails
                        @$this->removeFile($this->folder_path.DIRECTORY_SEPARATOR.$gallery_image->photo);
                        @$this->removeFile($this->folder_path.DIRECTORY_SEPARATOR.'1600_1200_'.$gallery_image->photo);
                        @$this->removeFile($this->folder_path.DIRECTORY_SEPARATOR.'200_150_'.$gallery_image->photo);
                    }

                    $gallery_image->update([
                        'photo'      => $file ? $this->file_name : $gallery_image->photo,
                        'alt_text'   => $item['alt_text'],
                        'caption'    => $item['caption'],
                        'rank'       => $rank_key,
                        'status'     =>$item['status'],
                    ]);

                    $gallery_image_ids[] = $gallery_image_id;

                } else {
                    //add
                    $file = $request->file('gallery.'.$key.'.gallery_image');
                    if($file) {
//                        $image_thumb_config = config('broadway.product.image-dimensions.gallery-image');
                        $this->processImage($file,config('helper.gallery_image'));

                        $gallery_image = GalleryImage::create([
                            'gallery_id' =>$gallery->id,
                            'photo'      => $this->file_name,
                            'alt_text'   => $item['alt_text'],
                            'caption'    => $item['caption'],
                            'rank'       => $rank_key,
                            'status'     =>$item['status'],
                        ]);

                        $gallery_image_ids[] = $gallery_image->id;
                    }
                }

            }
            $builder = GalleryImage::where('gallery_id', $gallery->id)
                ->whereNotIn('id',$gallery_image_ids);
            //remove image
            foreach ($builder->get() as $removing_row) {
                @$this->removeFile($this->folder_path.DIRECTORY_SEPARATOR.$removing_row->photo);
                @$this->removeFile($this->folder_path.DIRECTORY_SEPARATOR.'1600_1200_'.$removing_row->photo);
                @$this->removeFile($this->folder_path.DIRECTORY_SEPARATOR.'200_150_'.$removing_row->photo);
            }
            //remove row form table
            $builder->delete();

        }  else {
            $removing_rows = GalleryImage::where('gallery_id', $gallery->id)
                ->get();
            //remove image
            foreach ($removing_rows as $removing_row) {
                if($removing_row->photo) {
                    @$this->removeFile($this->folder_path.DIRECTORY_SEPARATOR.$removing_row->photo);
                    @$this->removeFile($this->folder_path.DIRECTORY_SEPARATOR.'1600_1200_'.$removing_row->photo);
                    @$this->removeFile($this->folder_path.DIRECTORY_SEPARATOR.'200_150_'.$removing_row->photo);
                }

            }
            //remove row form table
            GalleryImage::where('gallery_id', $gallery->id)
                ->delete();
        }


    }

    protected function removeGalleries($id){
        $removing_rows = GalleryImage::where('gallery_id', $id)
            ->get();
        //remove image
        foreach ($removing_rows as $removing_row) {
            if($removing_row->photo) {
               @$this->removeFile($this->folder_path.DIRECTORY_SEPARATOR.$removing_row->photo);
               @$this->removeFile($this->folder_path.DIRECTORY_SEPARATOR.'1600_1200_'.$removing_row->photo);
               @$this->removeFile($this->folder_path.DIRECTORY_SEPARATOR.'200_150_'.$removing_row->photo);
            }

        }
        //remove row form table
        GalleryImage::where('gallery_id', $id)
            ->delete();
    }

    /*
     * Sort the rank of gallery
     */
    public function sort(Request $request)
    {
        if($request->has('ids')){
            foreach ($request->get('ids') as $key => $id){
                $row = $this->model->find($id);
//                dd($row);
                $row->rank = $key + 1;
                $row->save();
            }
        }
        return redirect()->route($this->base_route.'.index')
            ->with('success_message', $this->panel . ' sorted Successfully');
    }

}
