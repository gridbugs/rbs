<?
/* Contains definitions of common functions used by the internal site */

function internal_print_header($title) {
?><html>
<head>
<title><?=$title?></title>

</head><body><?
}

function internal_print_footer() {
?></body></html><?
}

/* Use this function when there is a form that should post
 * to the same url to handle the form data. */
function internal_get_post_mplex($on_get, $on_post) {
    if ($_SERVER['REQUEST_METHOD'] == 'GET') {
        return $on_get();
    } else if ($_SERVER['REQUEST_METHOD'] == 'POST') {
        $args = array();
        $i = 0;
        foreach($_POST as $key => $value) {
            if ($value != 'Submit') {
                $args[$i] = $value;
                $i++;
            }
        }
        call_user_func_array($on_post, $args);
    }
}
function internal_get_post_mplex_simple($on_get, $on_post) {
    if ($_SERVER['REQUEST_METHOD'] == 'GET') {
        return $on_get();
    } else if ($_SERVER['REQUEST_METHOD'] == 'POST') {
        return $on_post();
    }
}
