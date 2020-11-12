<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\{StoryCategories, Stories as MyModel, StoryComments, StoryCommentsLikes};
use App\Enum\ImgPath;

class Stories extends Controller
{
    use \App\Enum\Traits\CheckAdminUser;
    use \App\Enum\Traits\UserData;

    public function home(Request $request)
    {
        $items = MyModel::getByFilter();
        $data  = array_merge($this->requiredData(), [
            'categories' => self::getCategories(),
            'data'       => $items['data'],
            'pagination' => [
                'current_page' => $items['current_page'],
                'per_page'     => $items['per_page'],
                'total'        => $items['total'],
            ]
        ]);

        return \View::make('stories', $data);
    }

    public function getByCategory($id)
    {
        $items = MyModel::getByFilter(['cat_id' => $id]);
        $data  = array_merge($this->requiredData(), [
            'categories' => self::getCategories(),
            'data'       => $items['data'],
            'cat_id'     => $id,
            'pagination' => [
                'current_page' => $items['current_page'],
                'per_page'     => $items['per_page'],
                'total'        => $items['total'],
            ]
        ]);

        return \View::make('stories', $data);
    }

    public function details($id)
    {
        $item = MyModel::getStoriDetailes($id);

        if (is_null($item)) {
            abort(404);
        }
        $comments = StoryComments::getCommentsByStoryId($id);

        $data = array_merge($this->requiredData(), [
            'item'      => $item,
            'prevUrl'   => \URL::previous(),
            'pageTitle' => $item->title,
            'comments' =>  $comments,
            'og'        => [
                'title' => $item->title,
                'url'   => \URL('stories/details', $item->id),
                'image' => \URL(ImgPath::STORIES . json_decode($item->images)[0])
            ]
        ]);

        return \View::make('stories_more', $data);
    }

    private function requiredData()
    {
        $userId = \Auth::id();

        return [
            'styles' => [
                'css/storiesAndDetails.css',
                'css/swiper.min.css'
            ],
            'scripts' => [
                'js/storiesAndDetails.js',
                'js/swiper.min.js'
            ],
            'userId'    => $userId ? $userId : 0,
            'isAdmin'   => $this->isAdmin(),
            'user_data' => $this->userData(),
            'pageTitle' => 'CharityPro - Պատմություններ'
        ];
    }

    private function getCategories()
    {
        return StoryCategories::get();
    }

    public function getCommentsById(Request $request) {
        $storyId  = $request->get('story_id');

        return StoryComments::getCommentsByStoryId($storyId);
    }

    public function likesDislikes(Request $request)
    {
        $userId = \Auth::id();

        if ($userId) {
            $likeOrDislike = intval($request->get('like_or_dislike')) === 1;
            $commentId     = (int) $request->get('comment_id');

            return json_encode(StoryCommentsLikes::add($userId, $likeOrDislike, $commentId));
        } else {
            return [
                'error'   => true,
                'message' => 'Please'
            ];
        }
    }

    public function addComment(Request $request)
    {
        $userId = \Auth::id();

        if ($userId) {
            $message = trim($request->get('message'));
            $storyId = $request->get('story_id');

            $comment = new StoryComments;

            $comment->description = $message;
            $comment->story_id    = $storyId;
            $comment->user_id     = $userId;

            $comment->save();

           return redirect()->back();
        } else {
           return redirect()->back();
        }
    }

    public function getCommentsStartedWith(Request $request)
    {
        $storyId          = (int) $request->get('story_id');
        $startedCommentId = (int) $request->get('comment_id');

        if (!$storyId) {
            return [];
        }

        $data = StoryComments::getCommentsStartedWith($storyId, $startedCommentId);

        return [
            'story_id' => $storyId,
            'data'     => $data
        ];
    }
}