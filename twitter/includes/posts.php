<?php include_once('functions.php'); ?>
<?php if($posts):?>
<section class="wrapper">
    <ul class="tweet-list">
        <? foreach ($posts as $post):?>

        <li>
            <article class="tweet">
                <div class="row">
                    <img class="avatar" src="<?= get_url($post['avatar'])?>" alt="Аватар пользователя <?=$post['name']?>">
                    <div class="tweet__wrapper">
                        <header class="tweet__header">
                            <h3 class="tweet-author"><?=$post['name']?>
                                <a href="<?= get_url("user_posts.php?user=" . $post['user_id'])?>" class="tweet-author__add tweet-author__nickname">@<?=$post['login']?></a>
                                <time class="tweet-author__add tweet__date"><?= date('Y.m.d H:i',strtotime($post['date'])) ?></time>
                            </h3>
                            <? if(isset($_SESSION['user']['id']) && $post['user_id'] === $_SESSION['user']['id']):?>
                            <a href="<?= get_url("includes/delete_post.php?id=" . $post['id'])?>" class="tweet__delete-button chest-icon"></a>
                            <? endif;?>
                        </header>
                        <div class="tweet-post">
                            <p class="tweet-post__text"><?= $post['text'] ?></p>
                            <? if (!is_null($post['image']) && $post['image'] !== ''):?>
                            <figure class="tweet-post__image">
                                <img src="<?= $post['image']?>" alt="<?= $post['text'] ?>">
                            </figure>
                            <? endif; ?>
                        </div>
                    </div>
                </div>
                <footer>
                    <?
                    $likes_count = get_likes_count($post['id']);
                    if (logged_in()){
                        if (is_post_liked($post['id'])){
                                ?>
                                <a href="<?= get_url('includes/delete_like.php?id=' . $post['id'])?>" class="tweet__like tweet__like_active"><?= $likes_count?></a>
                                <?
                        }else {
                                ?>
                                <a href="<?= get_url('includes/add_like.php?id=' . $post['id'])?>" class="tweet__like"><?= $likes_count?></a>
                                <?
                        }
                    }else{
                        ?>
                        <div class="tweet__like"><?= $likes_count?></div>
                        <?
                    }
                    ?>

                </footer>
            </article>
        </li>
        <? endforeach; ?>
    </ul>
</section>
<?php else:?>
<h2 align="center">Здесь пока нет твитов...</h2>
<?php endif;?>
