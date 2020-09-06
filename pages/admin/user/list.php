<?php
$no_of_records_per_page = 10;
$page_no = in('page_no', 1);
if ( in('page_no', 1) < 1 ) $page_no = 1;

$users = get_users( [ 'fields' => 'all_with_meta', 'number' => $no_of_records_per_page, 'paged' => $page_no] );
foreach($users as $user){
    echo <<<EOH
<div>
{$user->nickname}
</div>
EOH;
}


include widget('pagination', [
    'total_rows' => count_users()['total_users'],
    'no_of_records_per_page' => $no_of_records_per_page,
    'url' => '/?page=admin.user.list&page_no={page_no}',
    'page_no' => $page_no,
]);



