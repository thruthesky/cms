<?php


if ( in( 'mode' ) == 'send' ) {


    if (!in('topic')) jsAlert('Topic is Empty');
    else if (!in('title')) jsAlert('Topic is Title');
    else if (!in('body')) jsAlert('Topic is Body');
    else messageToTopic(in('topic'), in('title'), in('body'), in('url'), in('iconUrl'), in('data'));
}
?>

<div class="p-5">
    <h1>Push Notification</h1>

    Send Notification via Topic
    <form  action="?">

        <input type="hidden" name="page" value="admin.notification.push">
        <input type="hidden" name="mode" value="send">

        <div><label>Topic: <input type="text" name="topic" value="<?=in('topic', 'allTopic')?>"></label></div>
        <div><label>Title: <input type="text" name="title" value="<?=in('title')?>" placeholder="Input Title"></label></div>
        <div><label>Body: <input type="text" name="body" value="<?=in('body')?>" placeholder="Input body"></label></div>
        <div><label>Click_action: <input type="text" name="click_action" value="<?=in('click_action')?>"></label></div>
        <div><label>iconUrl: <input type="text" name="iconUrl" value="<?=in('iconUrl')?>"></label></div>
        <div><label>Data: <input type="text" name="data" value="<?=in('data')?>"></label></div>

        <button type="submit">Send Notification</button>
    </form>

</div>

