<?php
namespace PLGLib;

/**
 * Helpers
 */
class Helpers
{
    public static function date_format($with_time = true)
    {
        return $with_time ? get_option('date_format') . ' ' . get_option('time_format') : get_option('date_format');
    }

    public static function share_buttons($url, $text)
    {
        $subj = __('Email subject', 'pooling');
        // $text = urlencode($text);
        $url  = urlencode($url);
        ?>
    	<div class="socal-buttons d-inline-block ml-3">
	    	<a target="_blank" href="https://twitter.com/intent/tweet?text=<?php echo $text; ?>&url=<?php esc_html($url);?>" class="social-button twitter-button d-inline-block pl-2 text-secondary"><i class="fab fa-twitter-square"></i></a>
			<a target="_blank" href="https://www.facebook.com/sharer/sharer.php?p[summary]=<?php echo esc_html($text); ?>&u=<?php echo esc_html($url); ?>" class="social-button facebook-button d-inline-block pl-2 text-secondary"><i class="fab fa-facebook-square"></i></a>
			<a target="_blank" href="mailto:YourFriends@example.com?subject=<?php echo $subj; ?>&body=<?php echo $text; ?>" class="social-button email-button d-inline-block pl-2 text-secondary" title="Send an E-Mail"><i class="fas fa-envelope"></i></a>
		</div>
		<?php
    }

    public static function needs_to_label_array($needs)
    {
        return array_map(function($id){ return StaticOptions::get_needs()[$id]; }, $needs);
    }

    public static function secs_to_hours($secs)
    {
        return $secs / 3600;
    }
}
?>