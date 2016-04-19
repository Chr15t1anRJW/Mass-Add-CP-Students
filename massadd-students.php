<?php
/**
* Plugin Name: Add all users as Students to Coursepress.
* Description: This is a quick and dirty way of adding all useres as Students to a Coursepress Class. ***Set course ID in php script***
* Author: RJW
*/

/** Step 2 Hook your admin_menu function. */
add_action( 'admin_menu', 'my_plugin_menu' );

/** Step 1. create admin_menu function */
function my_plugin_menu() {
add_options_page( 'My Plugin Options', 'My Plugin', 'manage_options', 'my-unique-identifier', 'my_plugin_options' );
}

/** Step 3 Do your work. */
function my_plugin_options() {
if ( !current_user_can( 'manage_options' ) ) {
wp_die( __( 'You do not have sufficient permissions to access this page.' ) );
}

?>

<?php

// The Array that holds and passes all user ids to the Coursepress Student Class
$importedUsers = array();
// The course I want to add Students to
$course_id = 205;

// array or arguments I am passing to the Database class
$args = array( 'fields' => 'all', );

// The Database Query
$user_query = new WP_User_Query( $args );

// User Loop
if ( ! empty( $user_query->results ) ) {
foreach ( $user_query->results as $user ) {
//Prints name
echo '<p>' . $user->display_name . '</p>';
//Prints User ID
echo '<p>' . $user->id . '</p>';

//Adds User ID to array
$importedUsers[] = $user->id;

}
} else {
echo 'No users found.';
}

//Prints array for fun.
print_r ($importedUsers);

//Checks if array is empty
if ( ! empty($importedUsers )) {

//loops through array and adds students to class
foreach($importedUsers as &$newEnroll){
$student = new Student( $newEnroll );
$student->enroll_in_course( $course_id );
}

} else {
echo 'No users added to course.';
}

}


?>
