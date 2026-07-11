<?php

namespace App\Http\Controllers\Admin;

use Illuminate\Http\Request;
use App\User;
use App\Blog;
use Storage;
use DB;

class BlogController extends Controller {

    public function __construct() {
        
    }

    /**
     * Display a listing of the resource - Blog.
     *
     * @return \Illuminate\Http\Response
     */
    public function index() {
        $data = array();
        $param['order'] = "DESC";
        $param['orderby'] = "id";
        $data['blogs'] = Blog::get_data($param);
        return view("admin.blog.index", $data);
    }

    /**
     * Show the form for creating a new Blog.
     *
     * @return \Illuminate\Http\Response
     */
    public function create() {
        $data = array();
        return view('admin.blog.create', $data);
    }

    /**
     * Store a newly created Blog in storage.
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
        $meta_title = (empty($request['meta_title'])) ? $name : $request['meta_title'];
        $meta_keywords = $request['meta_keywords'];
        $meta_description = $request['meta_description'];
        $tags = $request['tags'];
        $is_draft = $request['is_draft'];
        $description = $request['description'];
        $popup_heading = $request['popup_heading'];
        $popup_description = $request['popup_description'];
        $popup_url = $request['popup_url'];
        if ($request->file('banner_image')) {
            $imageUrl = $this->upload_image($request->file("banner_image"));
            $imageTitle = $request->file('banner_image')->getClientOriginalName();
        }
        if ($request->file('sidebar_adv_image')) {
            $sidebar_adv_image = $this->upload_image($request->file("sidebar_adv_image"));
        }
        if ($request->file('topbar_adv_image')) {
            $topbar_adv_image = $this->upload_image($request->file("topbar_adv_image"));
        }
        $image_galleries = (!empty($request['image_gallery_ids'])) ? $request['image_gallery_ids'] : "";
        if (!empty($request['id'])) {
            $objBlog = Blog::find($request['id']);
            if ($objBlog) {
                $objBlog->name = $name;
                $objBlog->slug = $slug;
                $objBlog->meta_title = $meta_title;
                $objBlog->meta_keywords = $meta_keywords;
                $objBlog->meta_description = $meta_description;
                $objBlog->tags = $tags;
                $objBlog->popup_heading = $popup_heading;
                $objBlog->popup_description = $popup_description;
                $objBlog->popup_url = $popup_url;
                $objBlog->is_draft = $is_draft;
                $objBlog->description = $description;
                if ($request['banner_image']) {
                    $objBlog->banner_image_title = $imageTitle;
                    $objBlog->banner_image_url = $imageUrl;
                }
                if ($request['sidebar_adv_image']) {
                    $objBlog->sidebar_adv_image = $sidebar_adv_image;
                }
                if ($request['topbar_adv_image']) {
                    $objBlog->topbar_adv_image = $topbar_adv_image;
                }
                
                try {
                    $objBlog->save();
                    $blog_id = $request['id'];

                    // Move Images from tmp to src
                    if (!empty(@$image_galleries)) {
                        $image_galleries_array = explode("|@|@|", @$image_galleries);
                        foreach ($image_galleries_array as $galimage) {
                            $dest = str_replace("tmp/", "", $galimage);
                            Storage::disk('s3')->move($galimage, $dest);
                            $objBlogImageGallery = new \App\BlogImageGallery();
                            $objBlogImageGallery->blog_id = $blog_id;
                            $objBlogImageGallery->image_title = basename($dest);
                            $objBlogImageGallery->image_url = $dest;
                            $objBlogImageGallery->save();
                        }
                    }
                } catch (Exception $e) {
                    return redirect('bbadmin/blogs')
                                    ->with('flash_error_message', 'Something went wrong');
                }
            } else {
                return redirect('bbadmin/blogs')
                                ->with('flash_error_message', 'Something went wrong');
            }
            return redirect('bbadmin/blogs')
                            ->with('flash_message', 'Blog ' . $objBlog->name . ' updated');
        } else {
            $objBlog = new Blog();
            $objBlog->name = $name;
            $objBlog->slug = $slug;
            $objBlog->meta_title = $meta_title;
            $objBlog->meta_keywords = $meta_keywords;
            $objBlog->meta_description = $meta_description;
            $objBlog->description = $description;
            $objBlog->tags = $tags;
            $objBlog->popup_heading = $popup_heading;
            $objBlog->popup_description = $popup_description;
            $objBlog->popup_url = $popup_url;
            $objBlog->is_draft = $is_draft;
            if ($request['banner_image']) {
                $objBlog->banner_image_title = $imageTitle;
                $objBlog->banner_image_url = $imageUrl;
            }
            if ($request['sidebar_adv_image']) {
                $objBlog->sidebar_adv_image = $sidebar_adv_image;
            }
            if ($request['topbar_adv_image']) {
                $objBlog->topbar_adv_image = $topbar_adv_image;
            }
            try {
                $objBlog->save();
                $blog_id = $objBlog->id;

                // Move Images from tmp to src
                if (!empty(@$image_galleries)) {
                    $image_galleries_array = explode("|@|@|", @$image_galleries);
                    foreach ($image_galleries_array as $galimage) {
                        $dest = str_replace("tmp/", "", $galimage);
                        Storage::disk('s3')->move($galimage, $dest);
                        $objBlogImageGallery = new \App\BlogImageGallery();
                        $objBlogImageGallery->blog_id = $blog_id;
                        $objBlogImageGallery->image_title = basename($dest);
                        $objBlogImageGallery->image_url = $dest;
                        $objBlogImageGallery->save();
                    }
                }
            } catch (Exception $e) {
                return redirect('bbadmin/blogs')
                                ->with('flash_error_message', 'Something went wrong');
            }

            return redirect('bbadmin/blogs')
                            ->with('flash_message', 'Blog ' . $objBlog->name . ' created');
        }
    }

    /**
     * Show the form for ediing a Blog.
     *
     * @return \Illuminate\Http\Response
     */
    public function edit($id = '') {
        $data = array();
        $param = array();
        if (!$id) {
            redirect("bbadmin/blogs");
        }
        
        $param = array("where" => array("id" => $id), "limit" => 1);
        $data['eblog'] = Blog::get_data($param);
        $data['eblog'] = $data['eblog'][0];

        // Get Blog Gallery Images
        $paramAGI['select'] = array('id', 'blog_id', 'image_url', 'image_title');
        $paramAGI['where'] = array("blog_id" => $id);
        $data['imagegalleries'] = \App\BlogImageGallery::get_data($paramAGI);
        return view('admin.blog.edit', $data);
    }

    /**
     * Remove the specified Blog from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy(Request $request) {
        $id = $request['id'];
        try {
            $objBlog = Blog::find($id);
            if (!empty($objBlog)) {
                if ($objBlog->topbar_adv_image) {
                    Storage::disk('s3')->delete($objBlog->topbar_adv_image);
                }
                if ($objBlog->sidebar_adv_image) {
                    Storage::disk('s3')->delete($objBlog->sidebar_adv_image);
                }
                if ($objBlog->banner_image_title) {
                    Storage::disk('s3')->delete($objBlog->banner_image_title);
                }
                $objBlog->delete();
            } else {
                return redirect('bbadmin/blogs')
                                ->with('flash_error_message', 'Something went wrong.');
            }
        } catch (Exception $e) {
            return redirect('bbadmin/blogs')
                            ->with('flash_error_message', 'Something went wrong.');
        }
        return redirect('bbadmin/blogs')
                        ->with('flash_message', 'Blog deleted successfully');
    }

    public function upload_image($file) {
        // check mime type
        if ($file->getClientMimeType() == ( "image/png" ) ||
                $file->getClientMimeType() == ( "image/jpeg" ) ||
                $file->getClientMimeType() == ( "image/gif" ) ||
                $file->getClientMimeType() == ( "image/jpg" ) ||
                $file->getClientMimeType() == ( "image/webp" )) {
            // feel free to change this logic, that is an example
            $baseFileName = strtolower($file->getClientOriginalName());
            $ext = strtolower($file->getClientOriginalExtension());
            $filenameWithoutExt = preg_replace("~\." . $ext . "$~i", '', $baseFileName);
            $renamefile = $filenameWithoutExt . time() . "." . $ext;
            // folder name in container, could be empty
            $folderName = 'blogs' . '/' . date("Y") . "/" . date("m") . "/" . date("d");
            // store file on s3
            $file->storeAs($folderName, $renamefile, ['disk' => 's3']);
            // save file name somewhere
            return $saveFileName = $folderName . "/" . $renamefile;
        }
    }

    public function delete_image(Request $request) {
        try {
            $id = $request['id'];
            $field = $request['field'];
            $objBlog = Blog::find($id);
            if (!empty($objBlog)) {
                Storage::disk('s3')->delete($objBlog->$field);
                if ($field == "banner_image_url") {
                    $objBlog->banner_image_title = null;
                    $objBlog->banner_image_url = null;
                }
                $objBlog->save();
                echo true;
            } else {
                echo 'Something went wrong.';
            }
        } catch (Exception $e) {
            echo 'Something went wrong.';
        }
    }

    /**
     * Upload Image Gallery
     *
     * @return \Illuminate\Http\Response
     */
    public function upload_gallery_image(Request $request) {
        $file = $request->file('file');
        if ($file->getClientMimeType() == ( "image/png" ) ||
                $file->getClientMimeType() == ( "image/jpeg" ) ||
                $file->getClientMimeType() == ( "image/gif" ) ||
                $file->getClientMimeType() == ( "image/jpg" ) ||
                $file->getClientMimeType() == ( "image/webp" )) {
            // feel free to change this logic, that is an example
            $baseFileName = strtolower($file->getClientOriginalName());
            $ext = strtolower($file->getClientOriginalExtension());
            $filenameWithoutExt = preg_replace("~\." . $ext . "$~i", '', $baseFileName);
            $renamefile = $filenameWithoutExt . time() . "." . $ext;
            // folder name in container, could be empty
            $folderName = 'tmp/blogs' . '/' . date("Y") . "/" . date("m") . "/" . date("d");
            // store file on s3
            $file->storeAs($folderName, $renamefile, ['disk' => 's3']);
            // save file name somewhere
            $saveFileName = $folderName . "/" . $renamefile;
            echo (json_encode(array('success' => true, 'filename' => $saveFileName)));
        } else {
            echo (json_encode(array('success' => false, 'message' => 'Either file is not valid or file not found')));
        }
    }

    /**
     * Delete Image Gallery
     *
     * @return \Illuminate\Http\Response
     */
    public function delete_gallery_image(Request $request) {
        try {
            $id = $request['id'];
            $objBlogImageGallery = \App\BlogImageGallery::find($id);
            if (!empty($objBlogImageGallery)) {
                Storage::disk('s3')->delete($objBlogImageGallery->image_url);
                $objBlogImageGallery->delete();
                echo true;
            } else {
                echo 'Something went wrong.';
            }
        } catch (Exception $e) {
            echo 'Something went wrong.';
        }
    }

}
