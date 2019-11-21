<?php
/*
Template Name: stream
Template Post Type: post, page, product
*/
?>

<?php get_header(); ?>


<div class="container">
    <div class="row">
        <div class="col-12">
            <h1 class="entry-title"><?php the_title(); ?></h1>
        </div>
    </div>

    <div class="row">
        <div class="col">
            <div class="stream-container">
                <?php if (get_post_meta( $post->ID, 'youtube_id', true ) != ''): ?>
                <div id="player">
                    <div id="ytplayer"></div>
                </div>
                <?php else: ?>
                <div class="stream-container__empty">
                    Трансляция еще не началась, приходите позже :)
                </div>
                <?php endif; ?>
            </div>
        </div>
    </div>

    <div class="row">
        <div class="col-12">
            <?php storefront_display_comments(); ?>
        </div>
    </div>
</div>

<?php if (get_post_meta( $post->ID, 'youtube_id', true ) != ''): ?>
    <script>
        // Load the IFrame Player API code asynchronously.
        var tag = document.createElement('script');
        tag.src = "https://www.youtube.com/player_api";
        var firstScriptTag = document.getElementsByTagName('script')[0];
        firstScriptTag.parentNode.insertBefore(tag, firstScriptTag);

        // Replace the 'ytplayer' element with an <iframe> and
        // YouTube player after the API code downloads.
        var player;
        var height = 638;
        jQuery('#player').css('height', height);

        function onYouTubePlayerAPIReady() {
            player = new YT.Player('ytplayer', {
                height: height + 'px',
                width: '100%',
                videoId: '<?= get_post_meta( $post->ID, 'youtube_id', true ); ?>'
            });
        }

        jQuery('#youtubeModal').on('shown.bs.modal', function () {
            player.playVideo();
        });

        jQuery('#youtubeModal').on('hidden.bs.modal', function (e) {
            player.stopVideo();
        })
    </script>
<?php endif; ?>



<?php get_footer(); ?>
