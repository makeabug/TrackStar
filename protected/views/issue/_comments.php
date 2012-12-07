<?php foreach ($comments as $comment): ?>
<div class='comment'>
    <div class='auth'>
        <?php echo $comment->author->username ;?>
    </div>
    
    <div class='time'>
        <?php echo date('F j, Y \a\t h:i a', strtotime($comment->create_time));?>
    </div>
    
    <div class='content'>
        <?php echo nl2br(CHTML::encode($comment->content));?>
    </div>
    <hr>
</div>
<?php endforeach; ?>