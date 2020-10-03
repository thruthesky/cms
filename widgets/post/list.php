<?php
/**
 * @file list.php
 * @widget-type post_list_theme
 * @widget-name Default post list
 */

$forum = get_forum_setting();

$page_no = get_page_no();

$posts = post()->postSearch( [ 'slug'        => $forum['slug'],
                               'numberposts' => $forum[ NO_OF_POSTS_PER_PAGE ],
                               'paged'       => $page_no
] );

//if ( isBackendError($posts) ) {
//    return include page('error.wrong-input', 'forum slut not exists');
//}
?>
    <section class="p-3">
        <a class="btn btn-secondary" href="/?page=post.edit&slug=<?= $forum['slug'] ?>">Create</a>
		<?php if ( ! $posts || empty( $posts ) ) { ?>
            <div class="text-center roboto">
                <img class="w-100 mx-auto mw-60" src="<?= theme_url() ?>/tmp/no_posts.png">
                <div class="fs-27"><?= tr( NO_POSTS_YET_1 ) ?></div>
                <div class="fs-19"><?= tr( NO_POSTS_YET_2 ) ?></div>
                <div><?= tr( NO_POSTS_YET_3 ) ?></div>
            </div>
		<?php } else {
			foreach ( $posts as $post ) {
				?>
                <div style="display: flex; margin-top: 1em;">
                    <div class="" style="width: 60px; height: 60px;">
                        <img class='' src="<?= getPostProfilePhotoUrl( $post ) ?>" style="width: 100%; height: 100%; border-radius: 50%;" alt='user photo'>
                    </div>
                    <div class="" style="padding-left: 1em;">
                        <div style="font-size: .75em;">
							<?php if ( ! empty( $post['files'] ) )
								echo "<i class='fa fa-images'></i>" ?>
                            <span><?= $post['author_name'] ?></span>
                            <span>Date: <?= $post['short_date_time'] ?></span>
                            <span>View: <?= $post['view_count'] ?? '' ?></span>
                        </div>
                        <a class="card-title fs-lg"
                           href="<?= $post['guid'] ?><?= post_list_query() ?>"><?= $post['post_title'] ?></a>
                    </div>
                </div>
				<?php
			}
		}
		?>
    </section>
<?php
include widget('pagination', [
	'total_rows'             => post()->count_cat_post( $forum['cat_ID'] ),
	'no_of_records_per_page' => $forum[ NO_OF_POSTS_PER_PAGE ],
	'url'                    => '/?page=post.list&slug=' . $forum['slug'] . '&page_no={page_no}',
	'page_no'                => $page_no,
]);
?>
