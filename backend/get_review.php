<?php
  $ROOT_PATH = '.';

  require_once($ROOT_PATH . '/helpers/review.php');

  if(
    !isset($_POST['type']) ||
    !in_array($_POST['type'], array('get', 'set')) ||
    !isset($_POST['movie'])
  ) {
    echo json_encode(array(
      'Message' => 'ERROR: INVALID DATA'
    ));
  } else {
    if(
      $_POST['type'] == 'set' &&
      isset($_POST['user'])
    ) {
      $user_name = decodeJWT($_POST['user']);
      if($user_name != false) {
        $user_name = $user_name->data->username;
        $user_id = getUserId($user_name);
        if($user_id != false) {
          echo addReview($user_id, $_POST['movie'], $_POST['review']);
        } else {
          echo json_encode(array(
            'Message' => 'ERROR: INVALID DATA.'
          ));
        }
      }
    } else if(
      $_POST['type'] == 'get'
    ) {
      $user_id = null;
      if(isset($_POST['user'])) {
        $user_name = decodeJWT($_POST['user']);
        if($user_name != false) {
          $user_name = $user_name->data->username;
          $id = getUserId($user_name);
          if($id != false) {
            $user_id = $id;
          }
        }
      }

      echo getReviews($_POST['movie'], $user_id);
    } else {
      echo json_encode(array(
        'Message' => 'ERROR: INVALID DATA.'
      ));
    }
  }
?>
