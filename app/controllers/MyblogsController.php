<?php

class MyblogsController extends BaseController {

    /**
     * User Model
     * @var User
     */
    protected $user;
    protected $post;

    /**
     * Inject the models.
     * @param User $user
     */
    public function __construct(User $user, Post $post)
    {
        parent::__construct();
        $this->user = $user;
        $this->post = $post;
    }


    /**
     * Show a list of all the blog posts.
     *
     * @return View
     */
    public function getIndex()
    {
        // Title
        $title = Lang::get('admin/blogs/title.blog_management');

        // Grab all the blog posts
        $posts = Post::where("user_id", "=", Auth::user()->id);

        // Show the page
        return View::make('site/user/blog/index', compact('posts', 'title'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return Response
     */
    public function getCreate()
    {
        // Title
        $title = Lang::get('admin/blogs/title.create_a_new_blog');

        // Show the page
        return View::make('site/user/blog/create_edit', compact('title'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @return Response
     */
    public function postCreate()
    {
        // Declare the rules for the form validation
        $rules = array(
            'title'   => 'required|min:3',
            'content' => 'required|min:3'
        );

        // Validate the inputs
        $validator = Validator::make(Input::all(), $rules);

        // Check if the form validates with success
        if ($validator->passes())
        {
            // Create a new blog post
            $user = Auth::user();

            // Update the blog post data
            $this->post->title            = Input::get('title');
            $this->post->slug             = Str::slug(Input::get('title'));
            $this->post->content          = Input::get('content');
            $this->post->meta_title       = Input::get('meta-title');
            $this->post->meta_description = Input::get('meta-description');
            $this->post->meta_keywords    = Input::get('meta-keywords');
            $this->post->user_id          = $user->id;
            $this->post->visibility       = 1;

            // Was the blog post created?
            if($this->post->save())
            {
                // Redirect to the new blog post page
                return Redirect::to('blogs/' . $this->post->id . '/edit')->with('success', Lang::get('admin/blogs/messages.create.success'));
            }

            // Redirect to the blog post create page
            return Redirect::to('blogs/create')->with('error', Lang::get('admin/blogs/messages.create.error'));
        }

        // Form validation failed
        return Redirect::to('blogs/create')->withInput()->withErrors($validator);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param $post
     * @return Response
     */
    public function getEdit($post)
    {
        // Title
        $title = Lang::get('admin/blogs/title.blog_update');

        // Show the page
        return View::make('site/user/blog/create_edit', compact('post', 'title'));
    }


    /**
     * Update the specified resource in storage.
     *
     * @param $post
     * @return Response
     */
    public function postEdit($post)
    {

        // Declare the rules for the form validation
        $rules = array(
            'title'   => 'required|min:3',
            'content' => 'required|min:3'
        );

        // Validate the inputs
        $validator = Validator::make(Input::all(), $rules);

        // Check if the form validates with success
        if ($validator->passes())
        {
            // Update the blog post data
            $post->title            = Input::get('title');
            $post->slug             = Str::slug(Input::get('title'));
            $post->content          = Input::get('content');
            $post->meta_title       = Input::get('meta-title');
            $post->meta_description = Input::get('meta-description');
            $post->meta_keywords    = Input::get('meta-keywords');

            // Was the blog post updated?
            if($post->save())
            {
                // Redirect to the new blog post page
                return Redirect::to('blogs/' . $post->id . '/edit')->with('success', Lang::get('admin/blogs/messages.update.success'));
            }

            // Redirect to the blogs post management page
            return Redirect::to('blogs/' . $post->id . '/edit')->with('error', Lang::get('admin/blogs/messages.update.error'));
        }

        // Form validation failed
        return Redirect::to('blogs/' . $post->id . '/edit')->withInput()->withErrors($validator);
    }


    /**
     * Remove the specified resource from storage.
     *
     * @param $post
     * @return Response
     */
    public function getDelete($post)
    {
        // Title
        $title = Lang::get('admin/blogs/title.blog_delete');

        // Show the page
        return View::make('site/user/blog/delete', compact('post', 'title'));
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param $post
     * @return Response
     */
    public function postDelete($post)
    {
        // Declare the rules for the form validation
        $rules = array(
            'id' => 'required|integer'
        );

        // Validate the inputs
        $validator = Validator::make(Input::all(), $rules);

        // Check if the form validates with success
        if ($validator->passes())
        {
            $id = $post->id;
            $post->delete();

            // Was the blog post deleted?
            $post = Post::find($id);
            if(empty($post))
            {
                // Redirect to the blog posts management page
                return Lang::get('admin/blogs/messages.delete.success');
            }
        }
        // There was a problem deleting the blog post
        return Redirect::to('blogs/'.$post->id.'/delete')->with('error', Lang::get('admin/blogs/messages.delete.error'));
    }














    /**
     * Remove the specified resource from storage.
     *
     * @param $post
     * @return Response
     */
    public function getVisibility($post)
    {
        // Title
        $title = "Set post visibility";

        // Show the page
        return View::make('site/user/blog/visibility', compact('post', 'title'));
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param $post
     * @return Response
     */
    public function postVisibility($post)
    {
        // Declare the rules for the form validation
        $rules = array(
            'visibility' => 'required|integer'
        );

        // Validate the inputs
        $validator = Validator::make(Input::all(), $rules);

        // Check if the form validates with success
        if ($validator->passes())
        {
            if (Input::get('visibility') == 0 ){
                $post->visibility = 1;
                $post->save();
            } else {
                $post->visibility = 0;
                $post->save();            
            }

            // Was the blog post visibility changed?
            if ($post->visibility != Input::get('visibility') )
            {
                // Redirect to the blog posts management page
                return Redirect::to('blogs/'.$post->id.'/visibility')->with('success', 'Post visibility changed');
            }
        }
        // There was a problem deleting the blog post
        return Redirect::to('blogs/'.$post->id.'/visibility')->with('error', 'Could not change post visibility');
    }













   /**
     * Show a list of all the blog posts formatted for Datatables.
     *
     * @return Datatables JSON
     */
    public function getData()
    {
        $posts = Post::where("user_id","=", Auth::user()->id )->select(array('posts.id', 'posts.title', 'posts.id as comments', 'posts.created_at'));

        return Datatables::of($posts)

        ->edit_column('comments', '{{ DB::table(\'comments\')->where(\'post_id\', \'=\', $id)->count() }}')
        ->edit_column('visibility', '{{ DB::table(\'posts\')->where(\'id\', \'=\', $id)->pluck(\'visibility\') }}')

        ->add_column('actions', '<a href="{{{ URL::to(\'blogs/\' . $id . \'/edit\' ) }}}" class="btn btn-mini iframe" >{{{ Lang::get(\'button.edit\') }}}</a>
                <a href="{{{ URL::to(\'blogs/\' . $id . \'/delete\' ) }}}" class="btn btn-mini btn-danger iframe">{{{ Lang::get(\'button.delete\') }}}</a>
                <a href="{{{ URL::to(\'blogs/\' . $id . \'/visibility\' ) }}}" class="btn btn-mini btn-danger iframe">visibility</a>
            ')

        ->remove_column('id')

        ->make();
    }


}
