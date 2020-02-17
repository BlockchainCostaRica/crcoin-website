<?php
namespace App\Repository;
use App\Model\Blog;
use App\Model\BlogCategory;
use App\Model\UserBlog;
use App\Services\CommonService;
use App\User;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;

class BlogRepository
{
// blog category save process
    public function blogCategorySaveProcess($request)
    {
        $response = ['success' => false, 'message' => __('Invalid request')];
        try {
            $data = [
                'title' => $request->title,
                'status' => $request->status,
            ];

            if(!empty($request->edit_id)) {
                    $update = BlogCategory::where(['id' => $request->edit_id])->update($data);
                    if ($update) {
                        $response = [
                            'success' => true,
                            'message' => __('Category updated successfully')
                        ];
                    } else {
                        $response = [
                            'success' => false,
                            'message' => __('Failed to update')
                        ];
                    }
            } else {
                $saveData= BlogCategory::create($data);
                if ($saveData) {
                    $response = [
                        'success' => true,
                        'message' => __('Category created successfully.')
                    ];
                } else {
                    $response = [
                        'success' => false,
                        'message' => __('Failed to save')
                    ];
                }
            }

        } catch (\Exception $e) {
            $response = [
                'success' => false,
                'message' => $e->getMessage()
//                'message' => __('Something Went wrong !')
            ];
            return $response;
        }

        return $response;
    }

    // delete blog category
    public function deleteBlogCategory($id)
    {
        $response = ['success' => false, 'message' => __('Invalid request')];
        DB::beginTransaction();
        try {
            $item = BlogCategory::where('id',$id)->first();
            if (isset($item)) {
                $posts = Blog::where('category_id', $id)->get();
                if(isset($posts[0])) {
                    $response = [
                        'success' => false,
                        'message' => __('This item has used at post, You can not delete this.')
                    ];
                    return $response;
                }
                $delete = $item->delete();
                if ($delete) {
                    $response = [
                        'success' => true,
                        'message' => __('Category deleted successfully.')
                    ];
                } else {
                    DB::rollBack();
                    $response = [
                        'success' => false,
                        'message' => __('Operation failed.')
                    ];
                }
            } else {
                $response = [
                    'success' => false,
                    'message' => __('Item not found.')
                ];
            }

        } catch (\Exception $e) {
            DB::rollBack();
            $response = [
                'success' => false,
                'message' => $e->getMessage()
            ];
            return $response;
        }
        DB::commit();
        return $response;
    }


// blog  save process
    public function blogSaveProcess($request)
    {
        $response = ['success' => false, 'message' => __('Invalid request')];
        try {
            $data = [
                'title' => $request->title,
                'category_id' => $request->category_id,
                'description' => $request->description,
                'status' => $request->status,
            ];
            if (isset($request->edit_id)) {
                $item = Blog::where('id', $request->edit_id)->first();
            }
            if (!empty($request->image)) {
                $old_img = '';
                if (!empty($item->image)) {
                    $old_img = $item->image;
                }
                $data['image'] = fileUpload($request->image, path_image(), $old_img);
            }
            if(empty($request->edit_id)) {
                $data['user_id'] = Auth::user()->id;
            }

            if(!empty($request->edit_id)) {
                $update = Blog::where(['id' => $request->edit_id])->update($data);
                if ($update) {
                    $response = [
                        'success' => true,
                        'message' => __('Post updated successfully')
                    ];
                } else {
                    $response = [
                        'success' => false,
                        'message' => __('Failed to update')
                    ];
                }
            } else {
                $saveData= Blog::create($data);
                if ($saveData) {
                    $response = [
                        'success' => true,
                        'message' => __('New post created successfully.')
                    ];
                } else {
                    $response = [
                        'success' => false,
                        'message' => __('Failed to create')
                    ];
                }
            }

        } catch (\Exception $e) {
            $response = [
                'success' => false,
                'message' => $e->getMessage()
//                'message' => __('Something Went wrong !')
            ];
            return $response;
        }

        return $response;
    }
// delete post
    public function deleteBlog($id)
    {
        $response = ['success' => false, 'message' => __('Invalid request')];
        DB::beginTransaction();
        try {
            $item = Blog::where('id',$id)->first();
            if (isset($item)) {
                if (!empty($item->image)) {
                    $img = get_image_name($item->image);
                    removeImage(path_image(),$img);
                }
                $delete = $item->delete();
                if ($delete) {
                    $response = [
                        'success' => true,
                        'message' => __('Post deleted successfully.')
                    ];
                } else {
                    DB::rollBack();
                    $response = [
                        'success' => false,
                        'message' => __('Operation failed.')
                    ];
                }
            } else {
                $response = [
                    'success' => false,
                    'message' => __('Post not found.')
                ];
            }

        } catch (\Exception $e) {
            DB::rollBack();
            $response = [
                'success' => false,
                'message' => $e->getMessage()
            ];
            return $response;
        }
        DB::commit();
        return $response;
    }

    // blog list

    public function blogList($request)
    {
        $response = ['success'=> false, 'message' => __('Something went wrong')];

        $categorieList = BlogCategory::where(['status'=> STATUS_ACTIVE])->get();

        $categories = [];
        if (isset($categorieList[0])) {
            foreach ($categorieList as $cat) {
                $categories[] = [
                    'id' => $cat->id,
                    'encrypt_id' => encrypt($cat->id),
                    'title' => $cat->title,
                    'total_blogs' => get_post_count($cat->id),
                    'created_at' => date('d M y', strtotime($cat->created_at))
                ];
            }
        }
        if (isset($request->cat)) {
            $id = app(CommonService::class)->checkValidId($request->cat);
            if (is_array($id)) {
                $response = ['success' => false,  'message' => __('Item not found')];
                response()->json($response);
            }
            $items = Blog::where(['status'=> STATUS_ACTIVE, 'category_id' => $id])->orderBy('id', 'desc')->get();
        } else {
            $items = Blog::where(['status'=> STATUS_ACTIVE])->orderBy('id', 'desc')->get();
        }

        $lists = [];
        if (isset($items[0])) {
            foreach ($items as $item) {
                $lists[] = [
                    'id' => $item->id,
                    'encrypt_id' => encrypt($item->id),
                    'category_id' => $item->category_id,
                    'category_name' => isset($item->category->title) ? $item->category->title : '',
                    'author_name' => isset($item->user->name) ? $item->user->name : '',
                    'title' => $item->title,
                    'description' => $item->description,
                    'image' => $item->image,
                    'created_at' => date('d M y', strtotime($item->created_at))
                ];
            }
        }
        $recent_posts = [];
        $recent_post = Blog::where(['status' => STATUS_ACTIVE])->orderBy('id', 'desc')->limit(5)->get();
        if (isset($recent_post[0])) {
            $recent_posts = $recent_post;
        }
        $response['success'] = true;
        $response['message'] = __('Data get successfully');
        $response['cat_list'] = $categories;
        $response['post_list'] = $lists;
        $response['recent_posts'] = $recent_posts;

        return $response;
    }

    // blog details

    public function blogDetails($request,$id)
    {
        $response = ['success'=> false, 'message' => __('Something went wrong')];
        if (isset($id)) {
            $id = app(CommonService::class)->checkValidId($id);
            if (is_array($id)) {
                $response = ['success' => false, 'message' => __('Item not found')];
                response()->json($response);
            }
            $item = Blog::where(['status' => STATUS_ACTIVE, 'id' => $id])->first();

            $categorieList = BlogCategory::where(['status' => STATUS_ACTIVE])->get();

            $categories = [];
            if (isset($categorieList[0])) {
                foreach ($categorieList as $cat) {
                    $categories[] = [
                        'id' => $cat->id,
                        'encrypt_id' => encrypt($cat->id),
                        'title' => $cat->title,
                        'total_blogs' => get_post_count($cat->id),
                        'created_at' => date('d M y', strtotime($cat->created_at))
                    ];
                }
            }
            $list = (object)[];

            if (isset($item)) {
                $list = [
                    'id' => $item->id,
                    'encrypt_id' => encrypt($item->id),
                    'category_id' => $item->category_id,
                    'category_name' => isset($item->category->title) ? $item->category->title : '',
                    'author_name' => isset($item->user->name) ? $item->user->name : '',
                    'title' => $item->title,
                    'description' => $item->description,
                    'image' => $item->image,
                    'created_at' => date('d M y', strtotime($item->created_at))
                ];
            }

            $recent_posts = [];
            $recent_post = Blog::where(['status' => STATUS_ACTIVE])->orderBy('id', 'desc')->limit(5)->get();
            if (isset($recent_post[0])) {
                $recent_posts = $recent_post;
            }
            if (isset($request->ip)) {
                $this->setUserBlog($request->ip,$id);
            }
            $response['success'] = true;
            $response['message'] = __('Data get successfully');
            $response['cat_list'] = $categories;
            $response['post'] = $list;
            $response['recent_posts'] = $recent_posts;
        }

        return $response;
    }

    public function setUserBlog($ip,$blog_id) {
        try {
            $blog = Blog::where('id', $blog_id)->first();
            if (isset($blog)) {
                $userBlog = UserBlog::where(['ip' => $ip, 'blog_id' => $blog_id])->first();
                if (isset($userBlog)) {
                    return true;
                } else {
                    $create = UserBlog::create(['ip' => $ip, 'blog_id' => $blog_id]);
                    if($create) {
                        return true;
                    } else {
                        return false;
                    }
                }
            } else {
                return false;
            }

        } catch (\Exception $e) {
            return false;
        }
    }
}
