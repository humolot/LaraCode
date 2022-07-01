<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

if ( ! function_exists('old')) {
    function old($key, $default = "") {
        $ci =& get_instance();

        if($ci->session->flashdata('old')) {
            return $ci->session->flashdata('old')[$key];
        } else {
            return $default;
        }
    }
}

if ( ! function_exists('domain')) {
	function domain() {
		
		$url = $_SERVER['HTTP_HOST'];
		$url = str_replace('www.','',$url); // Remove www.
		$domain = explode(':',$url); // Remove Ports
		return $domain[0];
		
	}
}



if ( ! function_exists('google_tag_manager_header')) {
	function google_tag_manager_header($id) {
		echo "<!-- Google Tag Manager -->
		<script>(function(w,d,s,l,i){w[l]=w[l]||[];w[l].push({'gtm.start':
		new Date().getTime(),event:'gtm.js'});var f=d.getElementsByTagName(s)[0],
		j=d.createElement(s),dl=l!='dataLayer'?'&l='+l:'';j.async=true;j.src=
		'https://www.googletagmanager.com/gtm.js?id='+i+dl;f.parentNode.insertBefore(j,f);
		})(window,document,'script','dataLayer','".$id."');</script>
		<!-- End Google Tag Manager -->";
	}
}

if ( ! function_exists('google_tag_manager_footer')) {
	function google_tag_manager_footer($id) {
		echo '<!-- Google Tag Manager (noscript) -->
		<noscript><iframe src="https://www.googletagmanager.com/ns.html?id='.$id.'"
		height="0" width="0" style="display:none;visibility:hidden"></iframe></noscript>
		<!-- End Google Tag Manager (noscript) -->';
	}
}


if ( ! function_exists('google_analytics')) {
	function google_analytics($GA_MEASUREMENT_ID) {
		$script='<!-- Global site tag (gtag.js) - Google Analytics -->
		<script async src="https://www.googletagmanager.com/gtag/js?id='.$GA_MEASUREMENT_ID.'"></script>
		<script>
		  window.dataLayer = window.dataLayer || [];
		  function gtag(){dataLayer.push(arguments);}
		  gtag("js", new Date());

		  gtag("config", "'.$GA_MEASUREMENT_ID.'");
		</script>';

		return $script;
	}
}

if ( ! function_exists('facebook_pixel')) {
	function facebook_pixel($pixel_id) {
		$script="<!-- Facebook Pixel Code -->
		<script>
		  !function(f,b,e,v,n,t,s)
		  {if(f.fbq)return;n=f.fbq=function(){n.callMethod?
		  n.callMethod.apply(n,arguments):n.queue.push(arguments)};
		  if(!f._fbq)f._fbq=n;n.push=n;n.loaded=!0;n.version='2.0';
		  n.queue=[];t=b.createElement(e);t.async=!0;
		  t.src=v;s=b.getElementsByTagName(e)[0];
		  s.parentNode.insertBefore(t,s)}(window, document,'script',
		  'https://connect.facebook.net/en_US/fbevents.js');
		  fbq('init', '{$pixel_id}');
		  fbq('track', 'PageView');
		</script>
		<noscript>
		<img height='1' width='1' style='display:none' src='https://www.facebook.com/tr?id='{$pixel_id}'&ev=PageView&noscript=1'/>
		</noscript>
		<!-- End Facebook Pixel Code -->";

		return $script;
	}
}
