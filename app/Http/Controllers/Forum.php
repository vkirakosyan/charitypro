<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\{Forum as MyModel, ForumComments, ForumViews, ForumCommentsLikes};

class Forum extends Controller
{
    use \App\Enum\Traits\CheckAdminUser;
    use \App\Enum\Traits\UserData;

    public function home()
    {
        $id            = \Auth::id();
        $scriptsStyles = [
            'styles' => [
                'css/forum.css'
            ],
            'scripts' => [
                '/js/forum.js'
            ],
            'userId'    => $id ? $id : 0,
            'isAdmin'   => $this->isAdmin(),
            'user_data' => $this->userData(),
        ];

        return \View::make('forum', $scriptsStyles);
    }

    public function getByFilter(Request $request)
    {
        $page = $request->get('page', 1);

        return MyModel::getByFilter($page);
    }

    public function getMembersCount()
    {
        return ForumComments::select(\DB::raw('count(distinct user_id) members_count'))->first();
    }

    public function addToViews(Request $request)
    {
        $validator = \Validator::make($request->all(), [
            'forum_id' => 'required',
        ]);

        if ($validator->fails()) {
            return [
                'errors' => $validator->errors()
            ];
        }

        $forumId = $request->get('forum_id');
        $ip      = array_key_exists('HTTP_X_FORWARDED_FOR', $_SERVER) ? mb_split(',', $_SERVER['HTTP_X_FORWARDED_FOR'])[0] : $request->getClientIp();

        ForumViews::addView($forumId, $ip);
    }

    public function getCommentsByForumId(Request $request)
    {
        $validator = \Validator::make($request->all(), [
            'forum_id' => 'required',
        ]);

        if ($validator->fails()) {
            return [
                'errors' => $validator->errors()
            ];
        }

        $forumId  = (int) $request->get('forum_id');
        $comments = ForumComments::getCommentsById($forumId);
        $userData = MyModel::select([
            'users.name as user_name',
            \DB::raw("(case when users.img is not null then users.img else case when users.gender = 'M' then 'male.png' else 'female.png' end end) as avatar"),
            'forums.title',
            'forums.created_at',
            'forums.description'
        ])
            ->leftJoin('users', 'users.id', '=', 'forums.user_id')
            ->where('forums.id', '=', $forumId)
            ->first();

        return [
            'comments'   => $comments,
            'forum_data' => $userData
        ]; 
    }

    public function add(Request $request)
    {
        $userId = \Auth::id();

        if (!$userId) {
            return [
                'errors' => ['Please login or register.'],
            ];
        }

        $validator = \Validator::make($request->all(), [
            'title'       => 'required|min:2',
            'description' => 'required|min:10',
        ]);

        if ($validator->fails()) {
            return [
                'errors' => $validator->errors()
            ];
        }

        $forum = new MyModel();

        $forum->title       = $request->get('title');
        $forum->description = $request->get('description');
        $forum->user_id     = $userId;

        $forum->save();

        return $forum;
    }

    public function likesDislikes(Request $request)
    {
        $userId = \Auth::id();

        if ($userId) {
            $likeOrDislike = intval($request->get('like_or_dislike')) === 1;
            $commentId     = (int) $request->get('comment_id');

            return json_encode(ForumCommentsLikes::add($userId, $likeOrDislike, $commentId));
        } else {
            return [
                'error'   => true,
                'message' => 'Please'
            ];
        }
    }

    public function getCountItems()
    {
        return [
            'items_count' => MyModel::count(),
        ];
    }

    public function addComment(Request $request)
    {
        $message = trim($request->get('message'));
        $forumId = (int) $request->get('forum_id');

        if (!\Auth::id() || strlen($message) == 0 || !$forumId) {
            return [
                'status' => false
            ];
        }

        $comment = new ForumComments();

        $comment->comment  = $message;
        $comment->forum_id = $forumId;
        $comment->user_id  = \Auth::id();

        $comment->save();

        return [
            'status' => true
        ];
    }

    public function getCommentsStartedWith(Request $request)
    {
        $forumId          = (int) $request->get('forum_id');
        $startedCommentId = (int) $request->get('comment_id');

        if (!$forumId && !$startedCommentId) {
            return [];
        }

        $data = ForumComments::getCommentsStartedWith($forumId, $startedCommentId);

        return [
            'forum_id' => $forumId,
            'data'     => $data
        ];
    }
}
 