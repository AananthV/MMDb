<?php

  require_once($ROOT_PATH . '/config.php');
  require_once($ROOT_PATH . '/helpers/database.php');
  require_once($ROOT_PATH . '/helpers/user.php');
  require_once($ROOT_PATH . '/helpers/jwt.php');

  function addReview($user_id, $movie_id, $review) {
    // Validate Data.
    if(
      !checkIfRowExists('users', array('id' => $user_id)) ||
      !checkIfRowExists('movie_data', array('id' => $movie_id)) ||
      !is_string($review) ||
      strlen($review) > 1024
    ) {
      return json_encode(array(
        'Message' => 'ERROR: INVALID DATA.'
      ));
    }

    $res = true;

    // Add Activity
    $activity_id = insertValues(
      'user_activity',
      array(
        'user_id' => $user_id,
        'movie_id' => $movie_id,
        'type' => 1
      )
    );

    $res = true;

    if(
      checkIfRowExists('user_reviews', array('user_id' => $user_id, 'movie_id' => $movie_id))
    ) {
      $res = updateValues(
        'user_reviews',
        array('activity_id' => $activity_id, 'review' => $review),
        array('user_id' => $user_id, 'movie_id' => $movie_id)
      ) !== false;
    } else {
      $res = insertValues(
        'user_reviews',
        array(
          'activity_id' => $activity_id,
          'user_id' => $user_id,
          'movie_id' => $movie_id,
          'review' => $review
        )
      ) !== false;
    }

    if($res == true) {
      return json_encode(array(
        'Message' => 'SUCCESS'
      ));
    } else {
      return json_encode(array(
        'Message' => 'ERROR: DATABASE UPDATE FAILED'
      ));
    }
  }

  function getReviews($movie_id, $user_id = null) {
    $keys = array(
      'movie_id' => array('type' => '=', 'value' => $movie_id)
    )

    if(
      !is_null($user_id)
    ) {
      $keys['user_id'] = array('type' => '=', 'value' => $user_id)
    }

    $reviews = getValues(
      'user_reviews',
      array('*'),
      $keys
    );

    if($reviews === false) {
      return json_encode(array(
        'Message' => 'ERROR: DATABASE FETCH FAILED'
      ));
    }

    return json_encode(array(
      'Message' => 'SUCCESS',
      'Reivews' => $reviews
    ));
  }

  function addVote($user_id, $review_id, $type) {
    // Validate Data.
    if(
      !checkIfRowExists('users', array('id' => $user_id)) ||
      !checkIfRowExists('user_reviews', array('id' => $review_id)) ||
      !is_numeric($type) ||
      !($type >= -1 && $type <= 1)
    ) {
      return json_encode(array(
        'Message' => 'ERROR: INVALID DATA.'
      ));
    }

    $res = true;

    // Add Activity
    $activity_id = insertValues(
      'user_activity',
      array(
        'user_id' => $user_id,
        'type' => 2
      )
    );

    $res = true;

    if(
      checkIfRowExists('user_upvotes', array('user_id' => $user_id, 'review_id' => $review_id))
    ) {
      $res = updateValues(
        'user_upvotes',
        array('activity_id' => $activity_id, 'type' => intval($type)),
        array('user_id' => $user_id, 'review_id' => $review_id)
      ) !== false;
    } else {
      $res = insertValues(
        'user_upvotes',
        array(
          'activity_id' => $activity_id,
          'user_id' => $user_id,
          'review_id' => $review_id,
          'type' => intval($type)
        )
      ) !== false;
    }

    if($res == true) {
      return json_encode(array(
        'Message' => 'SUCCESS'
      ));
    } else {
      return json_encode(array(
        'Message' => 'ERROR: DATABASE UPDATE FAILED'
      ));
    }
  }

  function getUserVote($user_id, $review_id) {
    $vote = getValues(
      'user_upvotes',
      array('type'),
      array(
        'user_id' => array('type' => '=', 'value' => $user_id),
        'review_id' => array('type' => '=', 'value' => $review_id)
      )
    );
    if($vote === false) {
      return json_encode(array(
        'Message' => 'ERROR: DATABASE FETCH FAILED'
      ));
    }
    if(count($vote) == 0) {
      return json_encode(array(
        'Message' => 'SUCCESS',
        'Vote' => 0
      ));
    } else {
      return json_encode(array(
        'Message' => 'SUCCESS',
        'Vote' => $vote[0]['type']
      ));
    }
  }

  function getVotes($review_id) {
    $votes = getValues(
      'user_upvotes',
      array('type'),
      array(
        'review_id' => array('type' => '=', 'value' => $review_id)
      )
    );

    if($votes === false) {
      return json_encode(array(
        'Message' => 'ERROR: DATABASE FETCH FAILED'
      ));
    }

    $upvotes = 0;
    foreach ($votes as $vote) {
      $upvotes += $vote['type'];
    }

    return json_encode(array(
      'Message' => 'SUCCESS',
      'Upvotes' => $upvotes
    ));
  }

?>
