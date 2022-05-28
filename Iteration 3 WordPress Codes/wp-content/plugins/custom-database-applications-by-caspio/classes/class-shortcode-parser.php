<?php
    class Shortcode_Parser{
        //default values
        const DEFAULT_METHOD = 'embed';
        const DEFAULT_FORCE_SUBDOMAIN = false;
        const DEFAULT_NAME = 'Caspio DataPage';
        const DEFAULT_STYLE = 'i';
        const DEFAULT_IFRAME_STYLE = 'width:100%; height:300px';

        //private fields
        private $method = '';
        private $url = '';
        private $appkey = '';
        private $force_subdomain = false;
        private $style = '';
        private $name = '';
        private $atts = null;
        private $content = '';
		private $async = false;

        //constructor
        function __construct($atts, $content){
            $this->atts = $atts;
            $this->content = $content;
        }

        //private methods
        private function check_shortcode_format_style(){
            if(!empty($this->atts) && empty($this->content)){
                return is_array($this->atts) && !empty($this->atts["method"]);
            }
            return false;
        }
        private function parse_shortcode_by_new_format(){
            $attr = $this->atts;

            $this->method = empty($attr['method']) ? self::DEFAULT_METHOD : $attr['method'];
            $this->url = $this->clean_url($attr['url']);
            $this->appkey = $attr['appkey'];
            switch($this->method){
                case 'embed':
                    $this->force_subdomain = empty($attr['subdomain']) ? self::DEFAULT_FORCE_SUBDOMAIN : strtolower($attr['subdomain']) == 'true';
					$this->async = strtolower($attr['async']) == 'true';
                    break;
                case 'iframe':
                    $this->style = isset($attr['style']) && is_string($attr['style']) ? $attr['style'] : self::DEFAULT_IFRAME_STYLE;
                    break;
                case 'seo':
                    $this->style = empty($attr['style']) ? self::DEFAULT_STYLE : $attr['style'];
                    break;
            }
            $this->name = empty($attr['name']) ? self::DEFAULT_NAME : $attr['name'];
        }
        private function parse_shortcode_by_old_format(){
            $this->parse_content();
            $this->parse_attributes();
        }
        private function clean_url($current_url){
            return preg_replace('/[\/]+$/', '', $current_url);
        }
        private function parse_attributes(){
            $attr = $this->atts;

            if(!empty($attr) && is_array($attr)){
                if(!empty($attr['embed'])){
                    $this->method = 'embed';
                    $this->url = $this->clean_url($attr['embed']);
                    $this->appkey = $attr['key'];
                }
                if(!empty($attr['force_subdomains'])){
                    $this->force_subdomain = strtolower($attr['force_subdomains']) == 'true';
                }
                if(!empty($attr['seo']) || !empty($attr['url'])){
                    $this->method = 'seo';
                    $this->style = empty($attr['style']) ? self::DEFAULT_STYLE : $attr['style'];
                }
                if(!empty($attr['url'])){
                    $this->url = $this->clean_url($attr['url']);
                    $this->appkey = $attr['key'];
                }
                if(!empty($attr['seo'])){
                    $regex = '/(http[a-z0-9\/:]+)\.caspio\.(com|net|ua)\/dp\.asp\?AppKey=([A-Za-z0-9]+)/';
                    if ( preg_match( $regex, $attr['seo'], $matches ) ) {
                        $this->url = $this->clean_url($matches[1] . '.caspio.' . $matches[2]);
                        $this->appkey = $matches[3];
                    }
                }
            }
        }
        private function parse_content(){
            $content = $this->content;
            if(!empty($content)){
                if(strpos( $content, 'AppKey' )){
                    // try to extract variables from javascript snippet or direct deployment link
                    $regex = '/(http[a-z0-9\/:]+)\.caspio\.(com|net)\/dp\.asp\?AppKey=([A-Za-z0-9]+)/';
                    preg_match( $regex, $content, $matches );

                    $this->method = 'embed';
                    $this->url = $this->clean_url($matches[1] . '.caspio.' . $matches[2]);
                    $this->appkey = $matches[3];
                }else{
                    //hack for html entities replacing single quote
                    $content = preg_replace( '/<[\/]*code>/', '', $content );
                    $content = preg_replace( '/&[^;]+;/', '\'', $content );
                    // hack to extract parameters from php code snippet
                    $regex = '/\'(http[^\']+)\',\'([^\']+)\',\'([^\'])\'/';
                    if ( preg_match( $regex, $content, $matches ) ) {
                        $this->method = "seo";
                        $this->url = $this->clean_url($matches[1]);
                        $this->appkey = $matches[2];
                        $this->style = empty($matches[3]) ? self::DEFAULT_STYLE : $matches[3];
                    }
                }
            }
        }
        private function load_embed_datapage(){
            if(empty($this->url) || empty($this->appkey)){
                return '';
            }else{
                $caspio_servers_regex = '/https?:\/\/(?:(?:(?:b|au|eu|sa|f|k)\d+)|bridge|bn|bp).caspio.(?:com|net)/i';
                $use_subdomain = ($this->force_subdomain) || preg_match($caspio_servers_regex, $this->url) !== 1;
                $secure = false !== strpos( $this->url, 'https:' );

                $function_call = $use_subdomain ?
                    'f_cbload('.($secure ? 'true' : 'false').', "'.esc_attr(preg_replace('/https?:\/\//i', '', $this->url)).'", "'.esc_attr($this->appkey).'", false);' :
                    'f_cbload("'.esc_attr($this->appkey).'", "'.($secure ? 'https' : 'http').':");';

				if ($this->async){
					return '<script type="text/javascript" src="'.esc_attr($this->url).'/dp/'.esc_attr($this->appkey).'/emb"></script>'
							.'<div id="cxkg"><a href="'.esc_attr($this->url).'/dp/'.esc_attr($this->appkey).'">Click here</a> to load this Caspio <a href="http://www.caspio.com" title="Online Database">Online Database</a>.</div>';
				}else{
                return '<script type="text/javascript" src="'.esc_attr($this->url).'/scripts/'.esc_attr($use_subdomain ? 'embed' : 'e1').'.js"></script>'
                .'<script type="text/javascript" language="javascript">try{'.$function_call.'}catch(v_e){;}</script>'
					.'<div id="cxkg"><a href="'.esc_attr($this->url).'/dp/'.esc_attr($this->appkey).'">Click here</a> to load this Caspio <a href="http://www.caspio.com" title="Online Database">Online Database</a>.</div>';
				}
            }
        }
        private function load_iframe_datapage(){
            if(empty($this->url) || empty($this->appkey)){
                return '';
            }else{
                return sprintf('<iframe name="%s" title="%s" src="%s/dp.asp?AppKey=%s" style="%s">Sorry, but your browser does not support frames.</iframe>',
                    esc_attr($this->name), esc_attr($this->name), esc_attr($this->url), esc_attr($this->appkey), esc_attr($this->style));
            }
        }
        private function load_seo_datapage(){
            if(empty($this->url) || empty($this->appkey) || empty($this->style)){
                return '';
            }else{
                ob_start();
                $this->dpload( $this->url, $this->appkey, $this->style );
                return ob_get_clean();
            }
        }
        private function post_request ( $url, $data, $request_method ) {
            return $this->post_request_ex( $url, $data, $request_method, 3 );
        }
        // Post request function
        private function post_request_ex ( $request_url, $data, $request_method, $allowed_redirects_count ) {

            reset( $data ); // prepare array for iteration
            // convert variables array to string:
            $data_str = array();
            while ( list($n, $v) = each( $data ) ) {
                $data_str[] = str_replace( '+', '%20', urlencode( $n ) ) . '=' . str_replace( '+', '%20', urlencode( $v ) );
            }
            // format --> key1=val1&key2=val2 etc.
            $data_str = implode( '&', $data_str );

            // parse the given URL
            $url = parse_url( $request_url );
            if ( 'http' != $url['scheme'] && 'https' != $url['scheme'] ) {
                die( 'Invalid protocol specified!' );
            }

            // if DP is on https and user is not enforce DP http
            if ( 'https' == $url['scheme'] && 'on' != $_SERVER['HTTPS'] ) {
                $url['scheme'] = 'http';
            }

            // extract host and path:
            $host = $url['host'];
            $path = '/';
            if ( isset( $url['path'] ) ) {
                $path = $url['path'];
            }

            // prepare url settings
            $port = '';
            if ( isset( $url['port'] ) ) {
                $port = $url['port'];
            }
            $host_prefix = '';
            if ( '' == $port ) {
                $port = 80;
                if ( 'https' == $url['scheme'] ) {
                    $port = 443;
                    $host_prefix = 'ssl://';
                }
            }

            // prepare referrer settings
            $referrer = '';
            if ( isset( $request_method ) && 'post' === $request_method && isset( $_SERVER['HTTP_REFERER'] ) ) {
                $referrer = $_SERVER['HTTP_REFERER'];
            } else {
                $ref_protocol = 'http://';
                if ( isset( $_SERVER['HTTPS'] ) && 'on' == $_SERVER['HTTPS'] ) {
                    $ref_protocol = 'https://';
                }
                $ref_port = '';
                if ( '80' != $_SERVER['SERVER_PORT'] && '443' != $_SERVER['SERVER_PORT'] && 0 == strlen( strstr( $_SERVER['HTTP_HOST'], ':' ) ) ) {
                    $ref_port = ':' . $_SERVER['SERVER_PORT'];
                }
                $referrer = $ref_protocol . $_SERVER['HTTP_HOST'] . $ref_port . $_SERVER['PHP_SELF'];
                if ( isset( $_SERVER['QUERY_STRING'] ) && strlen( $_SERVER['QUERY_STRING'] ) > 0 ) {
                    $referrer = $referrer . '?' . $_SERVER['QUERY_STRING'];
                }
            }

            $result = $this->get_content($host_prefix, $host, $referrer, $port, $path, $data_str, $request_method);

            // split the result header from the content
            $result = explode( "\r\n\r\n", $result, 2 );

            if(is_array($result)){
                if(sizeof($result) == 1){
                    $header = '';
                    $content = isset( $result[0] ) ? $result[0] : '';
                }elseif(sizeof($result) == 2){
                    $header = isset( $result[0] ) ? $result[0] : '';
                    $content = isset( $result[1] ) ? $result[1] : '';
                }else{
                    $header = '';
                    $content = '';
                }
            }

            $header_params = explode( "\r\n", $header );

            // if redirection allowed
            if ( isset( $allowed_redirects_count ) && $allowed_redirects_count > 0 ) {

                // if object moved go to location
                if ( isset( $header_params[0] ) && (strlen( strstr( $header_params[0], 'HTTP/1.0 302' ) ) > 0 || strlen( strstr( $header_params[0], 'HTTP/1.1 302' ) ) > 0) && ( strpos( $header_params[0], 'HTTP/1.0 302' ) == 0 || strpos( $header_params[0], 'HTTP/1.1 302' ) == 0) ) {

                    while ( list($n, $v) = each( $header_params ) ) {
                        $header_item = explode( ': ', $v, 2 );
                        if ( isset( $header_item[0] ) && 'Location' == $header_item[0] ) {

                            if ( isset( $header_item[1] ) ) {

                                reset( $data );

                                $tmp_url = strval( $header_item[1] );
                                $tmp_data = array();
                                $new_appkey = '';

                                if ( strlen( strstr( $header_item[1], 'dp.asp' ) ) > 0 && strlen( strstr( $header_item[1], 'AppKey=' ) ) > 0 ) { // if redirect to datapage
                                    $tmp_url = $request_url;

                                    $url_items = explode( '?', $header_item[1], 2 );
                                    if ( isset( $url_items[1] ) ) {
                                        $get_items = explode( '&', $url_items[1] );
                                        while ( list($an, $av) = each( $get_items ) ) {

                                            if ( strlen( strstr( $av, 'AppKey=' ) ) > 0 ) {
                                                $appkey_items = explode( '=', $av );
                                                if ( isset( $appkey_items[1] ) ) {
                                                    $new_appkey = $appkey_items[1]; // redirection AppKey found
                                                }
                                            }
                                        }
                                    }
                                }

                                // if AppKeys not found or they are identical
                                if ( ! isset( $new_appkey ) || ! isset( $data['AppKey'] ) || (isset( $new_appkey ) && isset( $data['AppKey'] ) && $new_appkey == $data['AppKey']) ) {
                                    $tmp_data = $data; // pass the same data as input
                                } else if ( isset( $new_appkey ) ) {
                                    $tmp_data['AppKey'] = $new_appkey; // add new AppKey
                                }

                                $allowed_redirects_count = $allowed_redirects_count - 1;

                                // try request new localtion
                                return post_request_ex( $tmp_url, $tmp_data, $request_method, $allowed_redirects_count ); // post request without further redirection
                            } /** if (isset($header_item[1])) * */
                        } /** if (isset($header_item[0]) && 'Location' == $header_item[0]) * */
                    } /** while(list($n,$v) = each($header_params)) * */
                } /** if (isset($header_params[0])... * */
            } /** if(isset($allowed_redirects_count) && $allowed_redirects_count > 0) * */
            // if page is OK
            // remove extra characters on start and end of content
            $content = preg_replace( '/^[0-9a-fA-F]+(\r\n|\r|\n)/', '', $content );
            $content = preg_replace( '/(\r\n|\r|\n)0?\s*(\r\n|\r|\n)$/', '', $content );

            return $content;
        }
        //Get content function
        private function get_content($hostPrefix, $host, $referrer, $port, $path, $data, $_do_post){
            if (function_exists('fsockopen')){
                $result='';
                // open a socket connection on port
                $fp = fsockopen($hostPrefix.$host, $port);
                if (isset($fp) && $fp != '') { // if fp is OK

                    // send the request headers:

                    if ($_do_post) {
                        // as POST
                        fputs($fp, "POST $path HTTP/1.1\r\n");
                    }
                    else {
                        // as GET
                        $qPrefix = ""; if ($data != "") $qPrefix = "?";
                        fputs($fp, "GET $path$qPrefix$data HTTP/1.1\r\n");
                    }
                    fputs($fp, "Host: $host\r\n");
                    fputs($fp, "Referer: $referrer\r\n");
                    if ($_do_post) {
                        fputs($fp, "Content-type: application/x-www-form-urlencoded\r\n");
                        fputs($fp, "Content-length: ". strlen($data) ."\r\n");
                    }
                    fputs($fp, "Connection: close\r\n\r\n");
                    if ($_do_post) {
                        fputs($fp, $data);
                    }

                    while(!feof($fp)) {
                        // receive the results of the request
                        $result .= fgets($fp, 128);
                    }

                    // close the socket connection:
                    fclose($fp);
                    return $result;
                }
            }else if (function_exists('curl_version')){
                try{

                }catch(Exception $e){

                }
                $curl = curl_init();
                curl_setopt($curl, CURLOPT_URL, $hostPrefix.$host.$path);
                curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
                curl_setopt($curl, CURLOPT_REFERER, $referrer);
                curl_setopt($curl, CURLOPT_HEADER, true);
                if ($_do_post){
                    $header = array("POST $path HTTP/1.1\r\n");
                    curl_setopt($curl, CURLOPT_POST, true);
                    curl_setopt($curl, CURLOPT_POSTFIELDS, $data);
                } else {
                    $qPrefix = ""; if ($data != "") $qPrefix = "?";
                    curl_setopt($curl, CURLOPT_URL, $hostPrefix.$host.$path.$qPrefix.$data);
                    $header = array("GET $path$qPrefix$data HTTP/1.1\r\n",
                        "Cache-Control: no-cache\r\n",
                        "Pragma: no-cache\r\n");
                }
                curl_setopt($curl, CURLOPT_HTTPHEADER, $header);
                $content = curl_exec($curl);
                curl_close($curl);
                return $content;
            }
            else if(function_exists('file_get_contents') && ini_get('allow_url_fopen')=='1') {
                if ($_do_post){
                    $context = stream_context_create(array(
                        'http' => array(
                            'method' => "POST",
                            'header' => "Content-Type: application/x-www-form-urlencoded" .
                                "Content-length: " . strlen($data) ."\r\n",
                            'content' => $data
                        )
                    ));
                    return file_get_contents("http://".$hostPrefix.$host.$path, false, $context);
                }
                else {
                    $context = stream_context_create(array(
                        'http' => array(
                            'method' => "GET",
                            'header'=> ""
                        )
                    ));
                    $qPrefix = ""; if ($data != "") $qPrefix = "?";
                    return file_get_contents("http://".$hostPrefix.$host.$path.$qPrefix.$data, false, $context);
                }
            }
            else{echo 'Please enable fsockopen function!';}
        }
        // Load DP function
        private function dpload ( $url, $app_key, $dp_cbstyle ) {
            $e_string = '<div id="cxkg">Click <a href="' . esc_attr( $url ) . '/dp/' . esc_attr( $app_key ) . '">here</a> to load this <a href="http://caspio.com">Caspio Bridge DataPage</a>.</div>';

            // output error message if other SEO DP alredy deployed on this page
            if ( isset( $GLOBALS['V_CB_SEO_DP_USED'] ) && true == $GLOBALS['V_CB_SEO_DP_USED'] ) {
                echo $e_string;
                return;
            } else {
                $GLOBALS['V_CB_SEO_DP_USED'] = true;
            }

            $params = array( 'AppKey' => $app_key );
            $params['ServerDeploy'] = 'true';
            $params['cbstyle'] = $dp_cbstyle;

            $do_post_add_params = false;
            $request_method = ''; // by defaulr GET

            if ( ! isset( $_SESSION[$app_key]['v_cb_sesions_list'] ) ) {
                $_SESSION[$app_key]['v_cb_sesions_list'] = '';
            }
            // if we have anything in the query string which is related to this DP
            if (
                (isset( $_GET['appSession'] ) && strlen( strstr( $_SESSION[$app_key]['v_cb_sesions_list'], $_GET['appSession'] ) ) > 0 ) ||
                (isset( $_POST['AppKey'] ) && $_POST['AppKey'] == $app_key) ) {

                if ( isset( $_POST ) && isset( $_POST['AppKey'] ) ) {
                    $params = array_merge( $params, $_POST );
                } else if ( isset( $_REQUEST ) ) {
                    $params = array_merge( $params, $_REQUEST );
                }

                if ( isset( $_POST['AppKey'] ) ) {
                    $do_post_add_params = true;
                    $request_method = 'post'; // should be sent as POST
                }
            } else {
                $do_post_add_params = true;
            }

            $add_query = '';
            $add_post = '';
            $do_add_params = false;

            // params support
            if ( isset( $_SERVER['QUERY_STRING'] ) && strlen( $_SERVER['QUERY_STRING'] ) > 0 ) {

                $as_pos = strpos( $_SERVER['QUERY_STRING'], 'appSession' );
                if ( $as_pos > 0 ) { // if appSession not first param
                    $add_query = substr( $_SERVER['QUERY_STRING'], 0, $as_pos );
                } else if ( is_bool( $as_pos ) && ! $as_pos ) { // if no appSession at all
                    $add_query = $_SERVER['QUERY_STRING'] . '&';
                }

                $unique_param_names = array();
                while ( list($k, $v) = each( $params ) ) {
                    if ( ! isset( $unique_param_names[strtolower( $k )] ) ) {
                        $unique_param_names[strtolower( $k )] = true;
                    }
                }

                if ( strlen( $add_query ) > 0 ) {
                    $query_array = explode( '&', $add_query );
                    while ( list($k, $v) = each( $query_array ) ) {
                        if ( strlen( $v ) > 0 ) {
                            $val_array = explode( '=', $v );
                            if ( ! isset( $unique_param_names[strtolower( $val_array[0] )] ) ) {
                                if ( isset( $val_array[1] ) ) {
                                    $params[$val_array[0]] = urldecode( $val_array[1] );
                                }
                            }
                        }
                    }
                }

                $add_query = str_replace( ' ', '%20', $add_query );
                if ( '' != $add_query ) {
                    $add_post = '?' . substr( $add_query, 0, strlen( $add_query ) - 1 );
                }
            }

            $content = $this->post_request( $url . '/dp.asp', $params, $request_method );

            // remove outstanding app session cache
            if ( isset( $_GET['appSession'] ) && '' != $_SESSION[$app_key]['v_cb_sesions_list'] ) {
                $sessions = explode( '_', $_SESSION[$app_key]['v_cb_sesions_list'] );
                $limit = 50; //cache size
                if ( count( $sessions ) > $limit ) {
                    $_SESSION[$app_key]['v_cb_sesions_list'] = '';
                    for ( $i = count( $sessions ) - $limit; $i < count( $sessions ); $i ++ ) {
                        if ( '' != $sessions[$i] ) {
                            $_SESSION[$app_key]['v_cb_sesions_list'] = $_SESSION[$app_key]['v_cb_sesions_list'] . '_' . $sessions[$i];
                        }
                    }
                }
            }

            // App Session search
            $app_sess_str = '/href\s*=\s*"[^"]*dp.asp\?([^"]*(&|&amp;))?appSession\s*=\s*([A-Za-z0-9]*)/';
            if ( preg_match( $app_sess_str, $content ) ) {
                preg_match( $app_sess_str, $content, $matches );
                $app_session = $matches[3];

                $_SESSION[$app_key]['v_cb_sesions_list'] = $_SESSION[$app_key]['v_cb_sesions_list'] . '_' . $app_session;
            }

            // skip query string params if already present in DP links
            if ( '' != $add_query ) {
                $rgx_str = '/(href\s*=\s*"[^"]*dp.asp)\?([^"]*)(appSession)/';
                preg_match( $rgx_str, $content, $matches );
                if ( isset( $matches ) && isset( $matches[2] ) ) {
                    $dp_add_query = $matches[2];
                    if ( '' != $dp_add_query && str_replace( '&amp;', '&', $dp_add_query ) == $add_query ) {
                        $add_query = '';
                    }
                }
            }

            // a href replacement
            $rgx_str = '/href\s*=\s*"[^"]*dp.asp\?([^"]*)"/';
            $content = preg_replace( $rgx_str, 'href="?' . $add_query . '\\1"', $content );

            // a href with download param restore
            $rgx_str = '/href\s*=\s*"([^"]*)(&|&amp;)download=1([^"]*)"/';
            $content = preg_replace( $rgx_str, 'href="' . $url . '/dp.asp\\1\\2download=1\\3"', $content );

            // form action replacement
            $protocol = 'http://';
            if ( isset( $_SERVER['HTTPS'] ) && 'on' == $_SERVER['HTTPS'] ) {
                $protocol = 'https://';
            }
            $port = '';
            if ( '80' != $_SERVER['SERVER_PORT'] && 443 != $_SERVER['SERVER_PORT'] && strlen( strstr( $_SERVER['HTTP_HOST'], ':' ) == 0 ) ) {
                $port = ':' . $_SERVER['SERVER_PORT'];
            }
            $uri_path = '';
            if ( isset( $_SERVER['REQUEST_URI'] ) ) {
                $uri_path = $_SERVER['REQUEST_URI'];
            } else {
                $uri_path = $_SERVER['PHP_SELF'];
            }
            $qst_pos = strpos( $uri_path, '?' );
            if ( false !== $qst_pos ) {
                $uri_path = substr( $uri_path, 0, $qst_pos );
            }
            $rgx_str = '/action\s*=\s*"[^"]*dp.asp[^"]*"/';
            $content = preg_replace( $rgx_str, 'action="' . $protocol . $_SERVER['HTTP_HOST'] . $port . $uri_path . $add_post . '"', $content );

            echo empty($content) ? $e_string : $content;
        }

        //public methods
        public function parse_shortcode(){
            if(empty($this->atts) && empty($this->content)){
                return false;
            }
            if($this->check_shortcode_format_style()){
                $this->parse_shortcode_by_new_format();
            }else{
                $this->parse_shortcode_by_old_format();
            }

            return true;
        }
        public function load_datapage(){
            switch($this->method){
                case 'embed':
                    return $this->load_embed_datapage();
                case 'iframe':
                    return $this->load_iframe_datapage();
                case 'seo':
                    return $this->load_seo_datapage();
                default:
                    return $this->load_embed_datapage();
            }
        }
    }
?>