<?php
/**
 * Model
 *
 * Database-driven Webtechnology
 * Taught by Stijn Eikelboom
 * Based on code by Reinard van Dalen
 */

/* Enable error reporting */
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

/**
 * Check if the route exists
 * @param string $route_uri URI to be matched
 * @param string $request_type Request method
 * @return bool
 *
 */

function connect_db($host, $db, $user, $pass) {
    $charset = 'utf8mb4';

    $dsn = "mysql:host=$host;dbname=$db;charset=$charset";
    $options = [
        PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
        PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
    ];
    try {
        $pdo = new PDO($dsn, $user, $pass, $options);
    } catch (PDOException $e) {
        echo sprintf("Failed to connect. %s", $e->getMessage());
    }
    return $pdo;
}


function new_route($route_uri, $request_type){
    $route_uri_expl = array_filter(explode('/', $route_uri));
    $current_path_expl = array_filter(explode('/',parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH)));
    if ($route_uri_expl == $current_path_expl && $_SERVER['REQUEST_METHOD'] == strtoupper($request_type)) {
        return True;
    } else {
        return False;
    }
}

/**
 * Creates a new navigation array item using URL and active status
 * @param string $url The URL of the navigation item
 * @param bool $active Set the navigation item to active or inactive
 * @return array
 */
function na($url, $active){
    return [$url, $active];
}

/**
 * Creates filename to the template
 * @param string $template Filename of the template without extension
 * @return string
 */
function use_template($template){
    return sprintf("views/%s.php", $template);
}

/**
 * Creates breadcrumbs HTML code using given array
 * @param array $breadcrumbs Array with as Key the page name and as Value the corresponding URL
 * @return string HTML code that represents the breadcrumbs
 */
function get_breadcrumbs($breadcrumbs) {
    $breadcrumbs_exp = '<nav aria-label="breadcrumb">';
    $breadcrumbs_exp .= '<ol class="breadcrumb">';
    foreach ($breadcrumbs as $name => $info) {
        if ($info[1]){
            $breadcrumbs_exp .= '<li class="breadcrumb-item active" aria-current="page">'.$name.'</li>';
        } else {
            $breadcrumbs_exp .= '<li class="breadcrumb-item"><a href="'.$info[0].'">'.$name.'</a></li>';
        }
    }
    $breadcrumbs_exp .= '</ol>';
    $breadcrumbs_exp .= '</nav>';
    return $breadcrumbs_exp;
}

/**
 * Creates navigation bar HTML code using given array
 * @param array $navigation Array with as Key the page name and as Value the corresponding URL
 * @return string HTML code that represents the navigation bar
 */
function get_navigation($navigation){
    $navigation_exp = '<nav class="navbar navbar-expand-lg navbar-light bg-light">';
    $navigation_exp .= '<a class="navbar-brand">Series Overview</a>';
    $navigation_exp .= '<button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarSupportedContent" aria-controls="navbarSupportedContent" aria-expanded="false" aria-label="Toggle navigation">';
    $navigation_exp .= '<span class="navbar-toggler-icon"></span>';
    $navigation_exp .= '</button>';
    $navigation_exp .= '<div class="collapse navbar-collapse" id="navbarSupportedContent">';
    $navigation_exp .= '<ul class="navbar-nav mr-auto">';
    foreach ($navigation as $name => $info) {
        if ($info[1]){
            $navigation_exp .= '<li class="nav-item active">';
        } else {
            $navigation_exp .= '<li class="nav-item">';
        }
        $navigation_exp .= '<a class="nav-link" href="'.$info[0].'">'.$name.'</a>';

        $navigation_exp .= '</li>';
    }
    $navigation_exp .= '</ul>';
    $navigation_exp .= '</div>';
    $navigation_exp .= '</nav>';
    return $navigation_exp;
}

/**
 * Pretty Print Array
 * @param $input
 */
function p_print($input){
    echo '<pre>';
    print_r($input);
    echo '</pre>';
}

/**
 * Creates HTML alert code with information about the success or failure
 * @param array $feedback Associative array with keys type and message
 * @return string
 */
function get_error($feedback){
    return '
        <div class="alert alert-'.$feedback['type'].'" role="alert">
            '.$feedback['message'].'
        </div>';
}

/**
 * Count how many series there are in the database
 * @param $pdo
 * @return mixed
 */
function count_series($pdo) {
    $stmt = $pdo->prepare('SELECT id FROM series');
    $stmt->execute();
    $series = $stmt->fetchAll();
    return $series;
}

/**
 * Get an array with all series listed from the database
 * @param $pdo
 * @return array
 */
function get_series($pdo) {
    $stmt = $pdo->prepare('SELECT * FROM series');
    $stmt->execute();
    $series = $stmt->fetchAll();
    $series_exp = Array();

    foreach ($series as $key => $value) {
        foreach ($value as $user_key => $user_input) {
            $series_exp[$key][$user_key] = htmlspecialchars($user_input);
        }
    }
    return $series_exp;
}

/**
 * Create a table with all the series from the database
 * @param $series
 * @return string
 */
function get_series_table($series) {
    $table_exp = '
    <table class="table table-hover">
    <thead>
    <tr>
    <th scope="col">Series</th>
    <th scope="col"></th>
    </tr>
    </thead>
    <tbody>
    ';
    foreach ($series as $key => $value) {
        $table_exp .= '
        <tr>
        <th scope="row">'.$value['name'].'</th>
        <td><a href="/DDWT21/week1/series/?series_id='.$value['id'].'" role="button" class="btn btn-primary">More info</a></td>
        </tr>
        ';
    }
    $table_exp .= '
    </tbody>
    </table>
    ';
    return $table_exp;
}

/**
 * Find all the information a specific series
 * @param $pdo
 * @param $series_id
 * @return array
 */
function get_series_info($pdo, $series_id) {
    $stmt = $pdo->prepare('SELECT * FROM series WHERE id = ?');
    $stmt->execute([$series_id]);
    $series_info = $stmt->fetch();
    $series_info_exp = Array();

    foreach ($series_info as $key => $value) {
        $series_info_exp[$key] = htmlspecialchars($value);
    }
    return $series_info_exp;
}

/**
 * Add a series to the database and check if all fields are filled in, check if seasons is a number and if the series already exists
 * If everything checks out, add the series to the database
 * @param $pdo
 * @param $series_info
 * @return array|string[]
 */
function add_series ($pdo, $series_info) {
    $stmt = $pdo->prepare('SELECT * FROM series WHERE name = ?');
    $stmt->execute([$series_info['Name']]);
    $series = $stmt->rowCount();
    if ($series == 1) {
        return [
            'type' => 'danger',
            'message' => 'This series was already added.'
        ];
    }
    if (
        empty($series_info['Name']) or
        empty($series_info['Creator']) or
        empty($series_info['Seasons']) or
        empty($series_info['Abstract'])
    ) {
        return [
            'type' => 'danger',
            'message' => 'There was an error. Not all fields were filled in.'
        ];
    }
    if (!is_numeric($series_info['Seasons'])) {
        return [
            'type' => 'danger',
            'message' => 'There was an error. You should enter a number in the field Seasons.'
        ];
    }
    else {
        $stmt = $pdo->prepare('INSERT INTO series (name, creator, seasons, abstract) VALUES (?, ?, ?, ?)');
        $stmt->execute([
            $series_info['Name'],
            $series_info['Creator'],
            $series_info['Seasons'],
            $series_info['Abstract']
        ]);
        $inserted = $stmt->rowCount();
        if ($inserted == 1) {
            return [
                'type' => 'success',
                'message' => sprintf("Series '%s' added to Series Overview.", $series_info['Name'])
            ];
        }
        else {
            return [
                'type' => 'danger',
                'message' => 'There was an error. The series was not added. Try again.'
            ];
        }
    }
}

/**
 * Update an existing series. Check if all fields are filled in, check if seasons is a number and check if the series already exists
 * If everything checks out, update the series
 * @param $pdo
 * @param $series_info
 * @return array|string[]
 */
function update_series($pdo, $series_info) {
    if (
        empty($series_info['Name']) or
        empty($series_info['Creator']) or
        empty($series_info['Seasons']) or
        empty($series_info['Abstract']) or
        empty($series_info['series_id'])
    ) {
        return [
            'type' => 'danger',
            'message' => 'There was an error. Not all fields were filled in.'
        ];
    }
    if (!is_numeric($series_info['Seasons'])) {
        return [
            'type' => 'danger',
            'message' => 'There was an error. You should enter a number in the field Seasons.'
        ];
    }
    $stmt = $pdo->prepare('SELECT * FROM series WHERE id = ?');
    $stmt->execute([$series_info['series_id']]);
    $series = $stmt->fetch();
    $current_name = $series['name'];

    $stmt = $pdo->prepare('SELECT * FROM series WHERE name = ?');
    $stmt->execute([$series_info['Name']]);
    $series = $stmt->fetch();
    if ($series_info['Name'] == $series['name'] and $series['name'] != $current_name) {
        return [
            'type' => 'danger',
            'message' => sprintf("The name of the series cannot be changed. '%s' already exists.", $series_info['Name'])
        ];
    }
    else {
        $stmt = $pdo->prepare('UPDATE series SET name = ?, creator = ?, seasons = ?, abstract = ? WHERE id = ?');
        $stmt->execute([
            $series_info['Name'],
            $series_info['Creator'],
            $series_info['Seasons'],
            $series_info['Abstract'],
            $series_info['series_id']
        ]);
        $updated = $stmt->rowCount();
        if ($updated == 1) {
            return [
                'type' => 'success',
                'message' => sprintf("Series '%s' was edited!", $series_info['Name'])
            ];
        }
        else {
            return [
                'type' => 'warning',
                'message' => 'The series was not edited. No changes were detected.'
            ];
        }
    }
}

/**
 * Remove a series from the database
 * @param $pdo
 * @param $series_id
 * @return array|string[]
 */
function remove_series($pdo, $series_id) {
    $series_info = get_series_info($pdo, $series_id);

    $stmt = $pdo->prepare('DELETE FROM series WHERE id = ?');
    $stmt->execute([$series_id]);
    $deleted = $stmt->rowCount();
    if ($deleted == 1) {
        return [
            'type' => 'success',
            'message' => sprintf("Series '%s' was removed!", $series_info['name'])
        ];
    }
    else {
        return [
            'type' => 'warning',
            'message' => 'An error occurred. The series was not removed.'
        ];
    }
}
