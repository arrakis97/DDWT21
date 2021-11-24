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
$db = connect_db('localhost', 'ddwt21_week1', 'ddwt21','ddwt21');

/* Landing page */
if (new_route('/DDWT21/week1/', 'get')) {
    $series_amount = count_series($db);
    /* Page info */
    $page_title = 'Home';
    $breadcrumbs = get_breadcrumbs([
        'DDWT21' => na('/DDWT21/', False),
        'Week 1' => na('/DDWT21/week1/', False),
        'Home' => na('/DDWT21/week1/', True)
    ]);
    $navigation = get_navigation([
        'Home' => na('/DDWT21/week1/', True),
        'Overview' => na('/DDWT21/week1/overview/', False),
        'Add Series' => na('/DDWT21/week1/add/', False)
    ]);

    /* Page content */
    $right_column = use_template('cards');
    $page_subtitle = 'The online platform to list your favorite series';
    $page_content = 'On Series Overview you can list your favorite series. You can see the favorite series of all Series Overview users. By sharing your favorite series, you can get inspired by others and explore new series.';

    /* Choose Template */
    include use_template('main');
}

/* Overview page */
elseif (new_route('/DDWT21/week1/overview/', 'get')) {
    $series_amount = count_series($db);
    $series = get_series($db);
    $series = get_series_table($series);
    /* Page info */
    $page_title = 'Overview';
    $breadcrumbs = get_breadcrumbs([
        'DDWT21' => na('/DDWT21/', False),
        'Week 1' => na('/DDWT21/week1/', False),
        'Overview' => na('/DDWT21/week1/overview', True)
    ]);
    $navigation = get_navigation([
        'Home' => na('/DDWT21/week1/', False),
        'Overview' => na('/DDWT21/week1/overview', True),
        'Add Series' => na('/DDWT21/week1/add/', False)
    ]);

    /* Page content */
    $right_column = use_template('cards');
    $page_subtitle = 'The overview of all series';
    $page_content = 'Here you find all series listed on Series Overview.';
    $left_content = $series;
    /*
    $left_content = '
    <table class="table table-hover">
        <thead>
        <tr>
            <th scope="col">Series</th>
            <th scope="col"></th>
        </tr>
        </thead>
        <tbody>
        <tr>
            <th scope="row">House of Cards</th>
            <td><a href="/DDWT21/week1/series/" role="button" class="btn btn-primary">More info</a></td>
        </tr>

        <tr>
            <th scope="row">Game of Thrones</th>
            <td><a href="/DDWT21/week1/series/" role="button" class="btn btn-primary">More info</a></td>
        </tr>

        </tbody>
    </table>';
    */

    /* Choose Template */
    include use_template('main');
}

/* Single series */
elseif (new_route('/DDWT21/week1/series/', 'get')) {
    $series_amount = count_series($db);
    $series_info_exp = get_series_info($db, htmlspecialchars($_GET['series_id']));
    /* Get series from db */
    $series_name = $series_info_exp['name'];
    $series_abstract = $series_info_exp['abstract'];
    $nbr_seasons = $series_info_exp['seasons'];
    $creators = $series_info_exp['creator'];
    $series_id = $series_info_exp['id'];

    /* Page info */
    $page_title = $series_name;
    $breadcrumbs = get_breadcrumbs([
        'DDWT21' => na('/DDWT21/', False),
        'Week 1' => na('/DDWT21/week1/', False),
        'Overview' => na('/DDWT21/week1/overview/', False),
        $series_name => na('/DDWT21/week1/series/', True)
    ]);
    $navigation = get_navigation([
        'Home' => na('/DDWT21/week1/', False),
        'Overview' => na('/DDWT21/week1/overview', True),
        'Add Series' => na('/DDWT21/week1/add/', False)
    ]);

    /* Page content */
    $right_column = use_template('cards');
    $page_subtitle = sprintf('Information about %s', $series_name);
    $page_content = $series_abstract;

    /* Choose Template */
    include use_template('series');
}

/* Add series GET */
elseif (new_route('/DDWT21/week1/add/', 'get')) {
    $series_amount = count_series($db);
    /* Page info */
    $page_title = 'Add Series';
    $breadcrumbs = get_breadcrumbs([
        'DDWT21' => na('/DDWT21/', False),
        'Week 1' => na('/DDWT21/week1/', False),
        'Add Series' => na('/DDWT21/week1/new/', True)
    ]);
    $navigation = get_navigation([
        'Home' => na('/DDWT21/week1/', False),
        'Overview' => na('/DDWT21/week1/overview', False),
        'Add Series' => na('/DDWT21/week1/add/', True)
    ]);

    /* Page content */
    $right_column = use_template('cards');
    $page_subtitle = 'Add your favorite series';
    $page_content = 'Fill in the details of you favorite series.';
    $submit_btn = 'Add Series';
    $form_action = '/DDWT21/week1/add/';

    /* Choose Template */
    include use_template('new');
}

/* Add series POST */
elseif (new_route('/DDWT21/week1/add/', 'post')) {
    $series_amount = count_series($db);
    $feedback = add_series($db, $_POST);
    $error_msg = get_error($feedback);
    /* Page info */
    $page_title = 'Add Series';
    $breadcrumbs = get_breadcrumbs([
        'DDWT21' => na('/DDWT21/', False),
        'Week 1' => na('/DDWT21/week1/', False),
        'Add Series' => na('/DDWT21/week1/add/', True)
    ]);
    $navigation = get_navigation([
        'Home' => na('/DDWT21/week1/', False),
        'Overview' => na('/DDWT21/week1/overview', False),
        'Add Series' => na('/DDWT21/week1/add/', True)
    ]);

    /* Page content */
    $right_column = use_template('cards');
    $page_subtitle = 'Add your favorite series';
    $page_content = 'Fill in the details of you favorite series.';
    $submit_btn = 'Add Series';
    $form_action = '/DDWT21/week1/add/';

    include use_template('new');
}

/* Edit series GET */
elseif (new_route('/DDWT21/week1/edit/', 'get')) {
    $series_amount = count_series($db);
    $series_id = $_GET['series_id'];
    $series_info_exp = get_series_info($db, $series_id);
    /* Get series info from db */
    $series_name = $series_info_exp['name'];
    $series_abstract = $series_info_exp['abstract'];
    $nbr_seasons = $series_info_exp['seasons'];
    $creators = $series_info_exp['creator'];

    /* Page info */
    $page_title = 'Edit Series';
    $breadcrumbs = get_breadcrumbs([
        'DDWT21' => na('/DDWT21/', False),
        'Week 1' => na('/DDWT21/week1/', False),
        sprintf('Edit Series %s', $series_name) => na('/DDWT21/week1/new/', True)
    ]);
    $navigation = get_navigation([
        'Home' => na('/DDWT21/week1/', False),
        'Overview' => na('/DDWT21/week1/overview', False),
        'Add Series' => na('/DDWT21/week1/add/', False)
    ]);

    /* Page content */
    $right_column = use_template('cards');
    $page_subtitle = sprintf('Edit %s', $series_name);
    $page_content = 'Edit the series below.';
    $submit_btn = 'Submit edit';
    $form_action = '/DDWT21/week1/edit/';

    /* Choose Template */
    include use_template('new');
}

/* Edit series POST */
elseif (new_route('/DDWT21/week1/edit/', 'post')) {
    $series_amount = count_series($db);
    $series_info_exp = get_series_info($db, htmlspecialchars($_POST['series_id']));
    $feedback = update_series($db, $_POST);
    $error_msg = get_error($feedback);
    /* Get series info from db */
    $series_id = $_POST['series_id'];
    $series_name = $series_info_exp['name'];
    $series_abstract = $series_info_exp['abstract'];
    $nbr_seasons = $series_info_exp['seasons'];
    $creators = $series_info_exp['creator'];

    /* Page info */
    $page_title = $series_info_exp['name'];
    $breadcrumbs = get_breadcrumbs([
        'DDWT21' => na('/DDWT21/', False),
        'Week 1' => na('/DDWT21/week1/', False),
        'Overview' => na('/DDWT21/week1/overview/', False),
        $series_name => na('/DDWT21/week1/series/', True)
    ]);
    $navigation = get_navigation([
        'Home' => na('/DDWT21/week1/', False),
        'Overview' => na('/DDWT21/week1/overview', False),
        'Add Series' => na('/DDWT21/week1/add/', False)
    ]);

    /* Page content */
    $right_column = use_template('cards');
    $page_subtitle = sprintf('Information about %s', $series_name);
    $page_content = $series_info_exp['abstract'];
    $submit_btn = 'Submit edit';
    $form_action = 'DDWT21/week1/edit/';

    /* Choose Template */
    include use_template('series');
}

/* Remove series */
elseif (new_route('/DDWT21/week1/remove/', 'post')) {
    /* Remove series in database */
    $series_id = $_POST['series_id'];
    $feedback = remove_series($db, $series_id);
    $error_msg = get_error($feedback);
    $series_amount = count_series($db);
    $series = get_series($db);
    $series = get_series_table($series);

    /* Page info */
    $page_title = 'Overview';
    $breadcrumbs = get_breadcrumbs([
        'DDWT21' => na('/DDWT21/', False),
        'Week 1' => na('/DDWT21/week1/', False),
        'Overview' => na('/DDWT21/week1/overview', True)
    ]);
    $navigation = get_navigation([
        'Home' => na('/DDWT21/week1/', False),
        'Overview' => na('/DDWT21/week1/overview', True),
        'Add Series' => na('/DDWT21/week1/add/', False)
    ]);

    /* Page content */
    $right_column = use_template('cards');
    $page_subtitle = 'The overview of all series';
    $page_content = 'Here you find all series listed on Series Overview.';
    $left_content = $series;
        /*'
    <table class="table table-hover">
        <thead>
        <tr>
            <th scope="col">Series</th>
            <th scope="col"></th>
        </tr>
        </thead>
        <tbody>
        <tr>
            <th scope="row">House of Cards</th>
            <td><a href="/DDWT21/week1/series/" role="button" class="btn btn-primary">More info</a></td>
        </tr>

        <tr>
            <th scope="row">Game of Thrones</th>
            <td><a href="/DDWT21/week1/series/" role="button" class="btn btn-primary">More info</a></td>
        </tr>

        </tbody>
    </table>';
        */

    /* Choose Template */
    include use_template('main');
}

else {
    http_response_code(404);
    echo '404 Not Found';
}