<?php
  require_once($ROOT_PATH . '/helpers/database.php');

  function addMovie($movie_data) {
    $res = true;
    if(
      checkIfRowExists('movie_data', array('imdb_code' => $movie_data['imdb_code']))
    ) {
      $res = updateValues(
        'movie_data',
        $movie_data,
        array('imdb_code' => $movie_data['imdb_code'])
      ) !== false;
    } else {
      $res = insertValues(
        'movie_data',
        $movie_data
      ) !== false;
    }
    if($res) {
      return json_encode(array(
        'Message' => 'SUCCESS'
      ));
    } else {
      return json_encode(array(
        'Message' => 'ERROR: DATABASE INSERT FAILED'
      ));
    }
  }

  function deleteMovie($id) {
    $res = deleteValues(
      'movie_data',
      array('id' => $id)
    );
    if($res) {
      return json_encode(array(
        'Message' => 'SUCCESS'
      ));
    } else {
      return json_encode(array(
        'Message' => 'ERROR: DATABASE DELETE FAILED'
      ));
    }
  }

  function getMovieId($imdb_code) {
    $movie_id = getValues('movie_data', array('id'), array('imdb_code' => array('type' => '=', 'value' => $imdb_code)));

    if($movie_id !== false) {
      if(count($movie_id) == 0) {
        return json_encode(array(
          'Message' => 'ERROR: INVALID CODE'
        ));
      } else {
        return json_encode(array(
          'Message' => 'SUCCESS',
          'MovieID' => $movie_id[0]['id']
        ));
      }
    } else {
      return json_encode(array(
        'Message' => 'ERROR: DATABASE FETCH FAILED'
      ));
    }
  }
?>
