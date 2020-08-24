<?php


class PostRoute extends ApiBase
{


    /// 여기서 부터.
    function getPosts()
    {
        if (!in('slug')) $this->error(ERROR_CATEGORY_NOT_PROVIDED);

        $posts = get_posts(in());
        $rets = [];
        foreach ($posts as $p) {
            $rets[] = $this->postResponse($p, ['with_autop' => true]);
        }

        $this->success($rets);
    }

}

