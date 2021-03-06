<?php
/**
 * Controller
 *
 * Database-driven Webtechnology
 * Taught by Stijn Eikelboom
 * Based on code by Reinard van Dalen
 */

include 'model.php';

/* Connect to DB */
$db = connect_db('localhost', 'ddwt21_week2', 'ddwt21','ddwt21');

/* Get number of series */
$nbr_series = count_series($db);

/* Get number of users */
$nbr_users = count_users($db);

/* Standard template for $right_column */
$right_column = use_template('cards');

/* Array with all the standard views */
$navigation_array = Array (
    1 => Array (
        'name' => 'Home',
        'url' => '/DDWT21/week2/'
    ),
    2 => Array (
        'name' => 'Overview',
        'url' => '/DDWT21/week2/overview/'
    ),
    3 => Array (
        'name' => 'Add series',
        'url' => '/DDWT21/week2/add/'
    ),
    4 => Array (
        'name' => 'My Account',
        'url' => '/DDWT21/week2/myaccount/'
    ),
    5 => Array (
        'name' => 'Registration',
        'url' => '/DDWT21/week2/register/'
    )
);

/* Landing page */
if (new_route('/DDWT21/week2/', 'get')) {
    /* Page info */
    $page_title = 'Home';
    $breadcrumbs = get_breadcrumbs([
        'DDWT21' => na('/DDWT21/', False),
        'Week 2' => na('/DDWT21/week2/', False),
        'Home' => na('/DDWT21/week2/', True)
    ]);
    /* Check which page is the active page */
    $navigation = get_navigation($navigation_array, 1);

    /* Page content */
    $page_subtitle = 'The online platform to list your favorite series';
    $page_content = 'On Series Overview you can list your favorite series. You can see the favorite series of all Series Overview users. By sharing your favorite series, you can get inspired by others and explore new series.';

    /* Check if an error message is set and display it if available */
    if (isset($_GET['error_msg'])) {
        $error_msg = get_error($_GET['error_msg']);
    }

    /* Choose Template */
    include use_template('main');
}

/* Overview page */
elseif (new_route('/DDWT21/week2/overview/', 'get')) {
    /* Page info */
    $page_title = 'Overview';
    $breadcrumbs = get_breadcrumbs([
        'DDWT21' => na('/DDWT21/', False),
        'Week 2' => na('/DDWT21/week2/', False),
        'Overview' => na('/DDWT21/week2/overview', True)
    ]);
    /* Check which page is the active page */
    $navigation = get_navigation($navigation_array, 2);

    /* Page content */
    $page_subtitle = 'The overview of all series';
    $page_content = 'Here you find all series listed on Series Overview.';
    $left_content = get_series_table(get_series($db), $db);

    /* Check if an error message is set and display it if available */
    if (isset($_GET['error_msg'])) {
        $error_msg = get_error($_GET['error_msg']);
    }

    /* Choose Template */
    include use_template('main');
}

/* Single Series */
elseif (new_route('/DDWT21/week2/series/', 'get')) {
    session_start();
    /* Get series from db */
    $series_id = $_GET['series_id'];
    $series_info = get_series_info($db, $series_id);

    /* Check if currently logged-in user is also the creator of the series */
    if (isset($_SESSION['user_id'])) {
        if ($series_info['user'] == $_SESSION['user_id']) {
            $display_buttons = True;
        }
        else {
            $display_buttons = False;
        }
    }
    else {
        $display_buttons = False;
    }

    /* Page info */
    $page_title = $series_info['name'];
    $breadcrumbs = get_breadcrumbs([
        'DDWT21' => na('/DDWT21/', False),
        'Week 2' => na('/DDWT21/week2/', False),
        'Overview' => na('/DDWT21/week2/overview/', False),
        $series_info['name'] => na('/DDWT21/week2/series/?series_id='.$series_id, True)
    ]);
    /* Check which page is the active page */
    $navigation = get_navigation($navigation_array, 2);

    /* Page content */
    $page_subtitle = sprintf("Information about %s", $series_info['name']);
    $page_content = $series_info['abstract'];
    $nbr_seasons = $series_info['seasons'];
    $creators = $series_info['creator'];
    $added_by = display_user($db, $series_info['user']);

    /* Check if an error message is set and display it if available */
    if (isset($_GET['error_msg'])) {
        $error_msg = get_error($_GET['error_msg']);
    }

    /* Choose Template */
    include use_template('series');
}

/* Add series GET */
elseif (new_route('/DDWT21/week2/add/', 'get')) {
    /* Check if logged in */
    if (!check_login()) {
        redirect('/DDWT21/week2/login/');
    }
    $display_buttons = True;

    /* Page info */
    $page_title = 'Add Series';
    $breadcrumbs = get_breadcrumbs([
        'DDWT21' => na('/DDWT21/', False),
        'Week 2' => na('/DDWT21/week2/', False),
        'Add Series' => na('/DDWT21/week2/new/', True)
    ]);
    /* Check which page is the active page */
    $navigation = get_navigation($navigation_array, 3);

    /* Page content */
    $page_subtitle = 'Add your favorite series';
    $page_content = 'Fill in the details of you favorite series.';
    $submit_btn = "Add Series";
    $form_action = '/DDWT21/week2/add/';

    /* Check if an error message is set and display it if available */
    if (isset($_GET['error_msg'])) {
        $error_msg = get_error($_GET['error_msg']);
    }

    /* Choose Template */
    include use_template('new');
}

/* Add series POST */
elseif (new_route('/DDWT21/week2/add/', 'post')) {
    /* Check if logged in */
    if (!check_login()) {
        redirect('/DDWT21/week2/login/');
    }

    /* Add series to database */
    $feedback = add_series($db, $_POST);
    $error_msg = get_error($feedback);

    /* Redirect to the correct page with an error or a success message */
    redirect(sprintf('/DDWT21/week2/add/?error_msg=%s', json_encode($feedback)));

    /* Choose template */
    include use_template('new');
}

/* Edit series GET */
elseif (new_route('/DDWT21/week2/edit/', 'get')) {
    /* Check if logged in */
    if (!check_login()) {
        redirect('/DDWT21/week2/login/');
    }

    /* Get series info from db */
    $series_id = $_GET['series_id'];
    $series_info = get_series_info($db, $series_id);

    /* Check if currently logged-in user is also the creator of the series */
    if ($series_info['user'] == $_SESSION['user_id']) {
        $display_buttons = True;
    }
    else {
        $display_buttons = False;
    }

    /* Page info */
    $page_title = 'Edit Series';
    $breadcrumbs = get_breadcrumbs([
        'DDWT21' => na('/DDWT21/', False),
        'Week 2' => na('/DDWT21/week2/', False),
        sprintf("Edit Series %s", $series_info['name']) => na('/DDWT21/week2/new/', True)
    ]);
    /* Check which page is the active page */
    $navigation = get_navigation($navigation_array, 0);

    /* Page content */
    $page_subtitle = sprintf("Edit %s", $series_info['name']);
    $page_content = 'Edit the series below.';
    $submit_btn = "Edit Series";
    $form_action = '/DDWT21/week2/edit/';

    /* Check if an error message is set and display it if available */
    if (isset($_GET['error_msg'])) {
        $error_msg = get_error($_GET['error_msg']);
    }

    /* Choose Template */
    include use_template('new');
}

/* Edit series POST */
elseif (new_route('/DDWT21/week2/edit/', 'post')) {
    /* Check if logged in */
    if (!check_login()) {
        redirect('/DDWT21/week2/login/');
    }

    /* Update series in database */
    $feedback = update_series($db, $_POST);
    $error_msg = get_error($feedback);

    /* Redirect to the correct page with an error or a success message */
    if ($feedback['type'] == 'danger') {
        redirect(sprintf('/DDWT21/week2/edit/?error_msg=%s', json_encode($feedback)));
    }
    else {
        redirect(sprintf('/DDWT21/week2/series/?error_msg=%s&series_id=%s', json_encode($feedback), $_POST['series_id']));
    }

    /* Choose Template */
    include use_template('series');
}

/* Remove series */
elseif (new_route('/DDWT21/week2/remove/', 'post')) {
    /* Check if logged in */
    if (!check_login()) {
        redirect('/DDWT21/week2/login/');
    }

    /* Get series info from db */
    $series_id = $_POST['series_id'];
    $series_info = get_series_info($db, $series_id);

    /* Remove series in database */
    $feedback = remove_series($db, $series_id);
    $error_msg = get_error($feedback);

    /* Redirect to the correct page with an error or a success message */
    redirect(sprintf('/DDWT21/week2/overview/?error_msg=%s', json_encode($feedback)));

    /* Choose Template */
    include use_template('main');
}

/* My account */
elseif (new_route('/DDWT21/week2/myaccount/', 'get')) {
    /* Check if logged in */
    if (!check_login()) {
        redirect('/DDWT21/week2/login/');
    }

    /* Page info */
    $page_title = 'My Account';
    $breadcrumbs = get_breadcrumbs([
        'DDWT21' => na('/DDWT21/', False),
        'Week 2' => na('/DDWT21/week2/', False),
        'My Account' => na('/DDWT21/week2/myaccount/', True)
    ]);
    /* Check which page is the active page */
    $navigation = get_navigation($navigation_array, 4);

    /* Page content */
    $page_subtitle = 'Your account';
    $page_content = 'Here you can see information about your account';
    $user = display_user($db, $_SESSION['user_id'])['firstname'];

    /* Check if an error message is set and display it if available */
    if (isset($_GET['error_msg'])) {
        $error_msg = get_error($_GET['error_msg']);
    }

    /* Choose template */
    include use_template('account');
}

/* Register GET */
elseif (new_route('/DDWT21/week2/register/', 'get')) {
    /* Page info */
    $page_title = 'Register';
    $breadcrumbs = get_breadcrumbs([
        'DDWT21' => na('/DDWT21/', False),
        'Week 2' => na('/DDWT21/week2/', False),
        'Registration' => na('/DDWT21/week2/register', True)
    ]);
    /* Check which page is the active page */
    $navigation = get_navigation($navigation_array, 5);

    /* Page content */
    $page_subtitle = 'Here you can register your own Series Overview account!';

    /* Check if an error message is set and display it if available */
    if (isset($_GET['error_msg'])) {
        $error_msg = get_error($_GET['error_msg']);
    }

    /* Choose template */
    include use_template('register');
}

/* Register POST */
elseif (new_route('/DDWT21/week2/register', 'post')) {
    /* Register user */
    $feedback = register_user($db, $_POST);

    /* Redirect to the correct page with an error or a success message */
    if ($feedback['type'] == 'danger') {
        /* Redirect to register form */
        redirect(sprintf('/DDWT21/week2/register/?error_msg=%s', json_encode($feedback)));
    }
    else {
        /* Redirect to My Account page */
        redirect(sprintf('/DDWT21/week2/myaccount/?error_msg=%s', json_encode($feedback)));
    }
}

/* Login GET */
elseif (new_route('/DDWT21/week2/login', 'get')) {
    if (check_login()) {
        redirect(sprintf('/DDWT21/week2/myaccount'));
    }

    /* Page info */
    $page_title = 'Login';
    $breadcrumbs = get_breadcrumbs([
        'DDWT21' => na('/DDWT21/', False),
        'Week 2' => na('/DDWT21/week2/', False),
        'Login' => na('/DDWT21/week2/login', True)
    ]);
    /* Check which page is the active page */
    $navigation = get_navigation($navigation_array, 0);

    /* Page content */
    $page_subtitle = 'Login to you Series Overview account';

    /* Check if an error message is set and display it if available */
    if (isset($_GET['error_msg'])) {
        $error_msg = get_error($_GET['error_msg']);
    }

    /* Choose template */
    include use_template('login');
}

/* Login POST */
elseif (new_route('/DDWT21/week2/login', 'post')) {
    /* Login user */
    $feedback = login_user($db, $_POST);

    /* Redirect to the correct page with an error or a success message */
    if ($feedback['type'] == 'danger') {
        /* Redirect to login screen */
        redirect(sprintf('/DDWT21/week2/login/?error_msg=%s', json_encode($feedback)));
    }
    else {
        /* Redirect to My Account page */
        redirect(sprintf('/DDWT21/week2/myaccount/?error_msg=%s', json_encode($feedback)));
    }
}

elseif (new_route('/DDWT21/week2/logout/', 'get')) {
    $feedback = logout_user();
    redirect(sprintf('/DDWT21/week2/?error_msg=%s', json_encode($feedback)));
}

else {
    http_response_code(404);
    echo '404 Not Found';
}
