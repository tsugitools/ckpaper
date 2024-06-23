<?php

$REGISTER_LTI2 = array(
"name" => "Document Annotator",
"FontAwesome" => "fa-align-left",
"short_name" => "Annotator",
"description" => "A tool to write and turn in a document / paper using a browser editor(CKEditor 5.0), and allow private interactions around the documents including shared annotation and grading.",
    // By default, accept launch messages..
    "messages" => array("launch"),
    "privacy_level" => "name_only",  // anonymous, name_only, public
    "license" => "Apache",
    "languages" => array(
        "English",
    ),
    "source_url" => "https://github.com/tsugitools/ckpaper",
    // For now Tsugi tools delegate this to /lti/store
    "placements" => array(
        /*
        "course_navigation", "homework_submission",
        "course_home_submission", "editor_button",
        "link_selection", "migration_selection", "resource_selection",
        "tool_configuration", "user_navigation"
        */
    ),
    "video" => "https://www.youtube.com/watch?v=n2qz6XpxZ1Q",
    "submissionReview" => array(
        "reviewableStatus" => array("InProgress", "Submitted", "Completed"),
        "label" => "Annotator",
        "url" => "grade-detail.php",  // A relative URL in this context
        "custom" => array(
            "review_towel" => "42",
        ),
    ),
    "screen_shots" => array(
        "store/screen-01.png",
        "store/screen-02.png",
        "store/screen-03.png",
    )

);
