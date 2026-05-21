<?php

namespace App\Http\Controllers\Admin;

use Illuminate\Http\Request;
use App\Category;

class CategoryController extends Controller {

    public function __construct() {
        
    }

    /**
     * Display a listing of the resource - Category.
     *
     * @return \Illuminate\Http\Response
     */
    public function index() {
        $data = array();
        $param['order'] = "ASC";
        $param['orderby'] = "parent";
        $categories = Category::get_data($param);
        if ($categories) {
            foreach ($categories as $category) {
                if ($category->parent > 0) {
                    $data['categories'][$category->parent]['subcategory'][] = $category->toArray();
                } else {
                    $data['categories'][$category->id] = $category->toArray();
                }
            }
        }
        return view("admin.category.index", $data);
    }

    /**
     * Show the form for creating a new Category.
     *
     * @return \Illuminate\Http\Response
     */
    public function create() {
        $data = array();
        $param = array();
        $param['where'] = array("parent" => 0);
        $data['categories'] = Category::get_data($param);
        return view('admin.category.create', $data);
    }

    /**
     * Store a newly created category in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request) {
        $this->validate($request, [
            'name' => 'required',
            'slug' => 'required',
        ]);

        $name = $request['name'];
        $slug = $request['slug'];
        $type = $request['type'];
        $parent = $request['parent'];
        $meta_title = $request['meta_title'];
        $keywords = $request['keywords'];
        $meta_description = $request['meta_description'];
        $display_on_home = $request['display_on_home'];
        $description = $request['description'];
        if ($request->file('category_image')) {
            $imageUrl = $this->upload_image($request);
            $imageTitle = $request->file('category_image')->getClientOriginalName();
        }
        if ($request->file('category_banner_image')) {
            $imageBannerUrl = $this->upload_banner_image($request);
            $imageBannerTitle = $request->file('category_banner_image')->getClientOriginalName();
        }
        if (!empty($request['id'])) {
            $category = Category::find($request['id']);
            if ($category) {
                $category->name = $name;
                $category->slug = $slug;
                $category->type = $type;
                $category->parent = $parent;
                $category->meta_title = $meta_title;
                $category->keywords = $keywords;
                $category->meta_description = $meta_description;
                $category->display_on_home = $display_on_home;
                $category->description = $description;
                if ($request['category_image']) {
                    $category->image_title = $imageTitle;
                    $category->image_url = $imageUrl;
                }
                if ($request->file('category_banner_image')) {
                    $category->banner_image_title = $imageBannerTitle;
                    $category->banner_image_url = $imageBannerUrl;
                }
                try {
                    $category->save();
                } catch (Exception $e) {
                    return redirect('bbadmin/category')
                                    ->with('flash_error_message', 'Something went wrong');
                }
            } else {
                return redirect('bbadmin/category')
                                ->with('flash_error_message', 'Something went wrong');
            }
            return redirect('bbadmin/category')
                            ->with('flash_message', 'Category ' . $category->name . ' updated');
        } else {
            $objCategory = new \App\Category();
            $objCategory->name = $name;
            $objCategory->slug = $slug;
            $objCategory->type = $type;
            $objCategory->parent = $parent;
            $objCategory->meta_title = $meta_title;
            $objCategory->keywords = $keywords;
            $objCategory->meta_description = $meta_description;
            $objCategory->display_on_home = $display_on_home;
            $objCategory->description = $description;
            if ($request['category_image']) {
                $objCategory->image_title = $imageTitle;
                $objCategory->image_url = $imageUrl;
            }
            if ($request->file('category_banner_image')) {
                $category->banner_image_title = $imageBannerTitle;
                $category->banner_image_url = $imageBannerUrl;
            }
            try {
                $objCategory->save();
            } catch (Exception $e) {
                return redirect('bbadmin/category')
                                ->with('flash_error_message', 'Something went wrong');
            }

            return redirect('bbadmin/category')
                            ->with('flash_message', 'Category ' . $objCategory->name . ' created');
        }
    }

    /**
     * Show the form for ediing a Category.
     *
     * @return \Illuminate\Http\Response
     */
    public function edit($id = '') {
        $data = array();
        $param = array();
        if (!$id) {
            redirect("bbadmin/category");
        }
        $param['where'] = array("parent" => 0);
        $data['categories'] = Category::get_data($param);
        $param = array("where" => array("id" => $id), "limit" => 1);
        $data['ecategory'] = Category::get_data($param);
        $data['ecategory'] = $data['ecategory'][0];
        return view('admin.category.edit', $data);
    }

    /**
     * Remove the specified category from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy(Request $request) {
        $id = $request['id'];
        try {
            $category = Category::find($id);
            if (!empty($category)) {
                $category->delete();
            } else {
                return redirect('bbadmin/category')
                                ->with('flash_error_message', 'Something went wrong.');
            }
        } catch (Exception $e) {
            return redirect('bbadmin/category')
                            ->with('flash_error_message', 'Something went wrong.');
        }
        return redirect('bbadmin/category')
                        ->with('flash_message', 'Category deleted successfully');
    }

    public function upload_image($request) {
        $file = $request->file("category_image");
        // check mime type
        if ($file->getClientMimeType() == ( "image/png" ) ||
                $file->getClientMimeType() == ( "image/jpeg" ) ||
                $file->getClientMimeType() == ( "image/gif" ) ||
                $file->getClientMimeType() == ( "image/jpg" )) {
            // feel free to change this logic, that is an example
            $baseFileName = strtolower($file->getClientOriginalName());
            $ext = strtolower($file->getClientOriginalExtension());
            $filenameWithoutExt = preg_replace("~\." . $ext . "$~i", '', $baseFileName);
            $renamefile = $filenameWithoutExt . time() . "." . $ext;
            // folder name in container, could be empty
            $folderName = 'categories' . '/' . date("Y") . "/" . date("m") . "/" . date("d");
            // store file on azure blob
            $file->storeAs($folderName, $renamefile, ['disk' => 'azure']);
            // save file name somewhere
            return $saveFileName = $folderName . "/" . $renamefile;
        }
    }

    public function upload_banner_image($request) {
        $file = $request->file("category_banner_image");
        // check mime type
        if ($file->getClientMimeType() == ( "image/png" ) ||
                $file->getClientMimeType() == ( "image/jpeg" ) ||
                $file->getClientMimeType() == ( "image/gif" ) ||
                $file->getClientMimeType() == ( "image/jpg" )) {
            // feel free to change this logic, that is an example
            $baseFileName = strtolower($file->getClientOriginalName());
            $ext = strtolower($file->getClientOriginalExtension());
            $filenameWithoutExt = preg_replace("~\." . $ext . "$~i", '', $baseFileName);
            $renamefile = $filenameWithoutExt . time() . "." . $ext;
            // folder name in container, could be empty
            $folderName = 'categories' . '/' . date("Y") . "/" . date("m") . "/" . date("d");
            // store file on azure blob
            $file->storeAs($folderName, $renamefile, ['disk' => 'azure']);
            // save file name somewhere
            return $saveFileName = $folderName . "/" . $renamefile;
        }
    }

    public function delete_image(Request $request) {
        try {
            $id = $request['id'];
            $objCategory = Category::find($id);
            if (!empty($objCategory)) {
                \Illuminate\Support\Facades\Storage::disk('azure')->delete($objCategory->image_url);
                $objCategory->image_title = null;
                $objCategory->image_url = null;
                $objCategory->save();
                echo true;
            } else {
                echo 'Something went wrong.';
            }
        } catch (Exception $e) {
            echo 'Something went wrong.';
        }
    }

    public function delete_banner_image(Request $request) {
        try {
            $id = $request['id'];
            $objCategory = Category::find($id);
            if (!empty($objCategory)) {
                \Illuminate\Support\Facades\Storage::disk('azure')->delete($objCategory->banner_image_url);
                $objCategory->banner_image_title = null;
                $objCategory->banner_image_url = null;
                $objCategory->save();
                echo true;
            } else {
                echo 'Something went wrong.';
            }
        } catch (Exception $e) {
            echo 'Something went wrong.';
        }
    }

}
