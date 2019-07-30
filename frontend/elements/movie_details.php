<!-- Button trigger modal -->
<button type="button" id="modal-button" class="d-none" data-toggle="modal" data-target="#detailsModal">
  Launch demo modal
</button>

<!-- Modal -->
<div class="modal fade" id="detailsModal" tabindex="-1" role="dialog" aria-labelledby="detailsModal" aria-hidden="true">
  <div class="modal-dialog modal-dialog-centered modal-xl" role="document">
    <div class="modal-content bg-dark text-light">
      <div class="modal-header border-success">
        <h5 class="modal-title" id="detailsModal">Movie Details</h5>
        <button type="button" id="details-modal-close" class="close text-light" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>
      <div class="modal-body">
        <div class="container-fluid">
          <div class="row d-flex">
            <div class="col-12 col-lg-4 col-xl-3 order-lg-1">
              <img src="https://img.yts.lt/assets/images/movies/9th_company_2005/medium-cover.jpg" class="img-thumbnail" alt="Poster Unavailable." id="modal-poster">
            </div>
            <div class="col-12 col-lg-8 col-xl-6 d-flex flex-column order-lg-2">
              <span class="mb-4">
                <h1 class="d-inline" id="modal-details-title">9th Company</h1>
              </span>
              <h5 id="modal-details-year">2005</h5>
              <h5 class="mb-4" id="modal-details-genres">Action / Drama / History / War</h5>
              <span class="mb-4 d-flex flex-column align-items-center flex-lg-row align-items-center">
                <span class="d-flex flex-row flex-lg-column justify-content-around col-12 col-lg-3 pr-lg-2 px-0">
                  <span class="col-6 col-sm-4 col-lg-12 p-0 mb-2 d-inline-flex d-lg-flex align-items-center justify-content-between">
                    <img src="assets/imdb_logo.png" style="height:24px;" alt="">
                    <span><span id="modal-details-imdb-rating" class="mr-2">7.1</span><i class="fas fa-star"></i></span>
                  </span>
                  <span class="col-6 col-sm-4 col-lg-12 p-0 mb-2 mb-lg-0 d-inline-flex d-lg-flex align-items-center justify-content-between">
                    <img src="assets/imdb_logo.png" style="height:24px;" alt="">
                    <span><span id="modal-details-mmdb-rating" class="mr-2">7.1</span><i class="fas fa-star"></i></span>
                  </span>
                </span>
                <span class="d-flex flex-column align-items-center flex-sm-row">
                  <span id="rating-toggler" class="row m-0 text-center" onclick="toggleRating()">
                    <i class="far fa-star fa-3x p-2" id="rating-star"></i>
                    <span class="d-flex flex-column justify-content-center" id="rating-star-text"><span>Rate</span><span>This</span></span>
                  </span>
                  <span class="d-none ml-2 p-2 align-items-center border border-success rounded" id="rating-toggle">
                    <span id="rating-container"></span>
                  </span>
                </span>
              </span>
              <span class="mb-4 d-flex flex-column flex-sm-row align-items-center">
                <h5 class="d-inline my-auto mr-2"><em>Download in:</em></h5>
                <div id="modal-details-download-links" class="btn-group">
                  <a class="btn btn-outline-success btn-sm">720p Bluray</a>
                  <a class="btn btn-outline-success btn-sm">1080p Bluray</a>
                  <a class="btn btn-outline-success btn-sm">3D Bluray</a>
                </div>
              </span>
              <span>
                <h5>Synopsis: </h5>
                <p id="modal-details-synopsis" class="text-justify">The film is based on a true story of the 9th company during the Soviet invasion in Afghanistan in the 1980's. Young Soviet Army recruits are sent from a boot camp into the middle of the war in Afghanistan. The action is not like a boot camp at all. It is very bloody and dirty. The 9th company is defending the hill 3234. They are hopelessly calling for help.</p>                  </span>
            </div>
            <div class="col-12 mb-4 order-lg-0">
              <h5>Trailer</h5>
              <div id="modal-video" class="embed-responsive embed-responsive-16by9">
                <iframe id="modal-details-trailer" class="embed-responsive-item" src="https://www.youtube.com/embed/c991IDTFr0g?rel=0" allowfullscreen></iframe>
              </div>
            </div>
            <div class="col-12 col-xl-3 order-lg-3">
              <h6>Similar Movies:</h6>
              <div id="similar-movies-list" class="row">
                <a class="col-6 col-sm-3 col-xl-6">
                  <img src="" alt="">
                </a>
              </div>
            </div>
          </div>
        </div>
      </div>
    </div>
  </div>
</div>

<script type="text/javascript">
  let current_movie = 0;
  let current_movie_rating = 0;

  function get_user_rating() {
    return new Promise(function(resolve, reject) {
      let xhttp = new XMLHttpRequest();
      xhttp.onreadystatechange = function() {
        if (this.readyState == 4 && this.status == 200) {
          let response = JSON.parse(this.responseText);
          if(response.Message == 'SUCCESS') {
            resolve(response.Rating);
          } else {
            resolve(0);
          }
        }
      };
      xhttp.open("POST", "<?php echo BACKEND . 'get_rating.php';?>", true);
      xhttp.setRequestHeader("Content-type", "application/x-www-form-urlencoded");
      xhttp.send('type=get&user=' + localStorage.getItem('JWT') + '&movie=' + current_movie);
    });
  }

  function get_movie_rating() {
    return new Promise(function(resolve, reject) {
      let xhttp = new XMLHttpRequest();
      xhttp.onreadystatechange = function() {
        if (this.readyState == 4 && this.status == 200) {
          console.log(this.responseText);
          let response = JSON.parse(this.responseText);
          if(response.Message == 'SUCCESS') {
            resolve(response.Rating);
          } else {
            resolve(0);
          }
        }
      };
      xhttp.open("POST", "<?php echo BACKEND . 'get_rating.php';?>", true);
      xhttp.setRequestHeader("Content-type", "application/x-www-form-urlencoded");
      xhttp.send('type=get&movie=' + current_movie);
    });
  }

  function rate_movie() {
    let xhttp = new XMLHttpRequest();
    xhttp.onreadystatechange = function() {
      if (this.readyState == 4 && this.status == 200) {
        let response = JSON.parse(this.responseText);
        if(response.Message != 'SUCCESS') {
          console.log(response);
        }
      }
    };
    xhttp.open("POST", "<?php echo BACKEND . 'get_rating.php';?>", true);
    xhttp.setRequestHeader("Content-type", "application/x-www-form-urlencoded");
    xhttp.send('type=set&user=' + localStorage.getItem('JWT') + '&movie=' + current_movie + '&rating=' + current_movie_rating);
  }

  let construct_item_thumb = function(item) {
    // Create Outer Container.
    let outerContainer = document.createElement('div');
    outerContainer.setAttribute('class', 'col-12 col-sm-6 col-md-4 col-lg-3 p-1 d-flex justify-content-center');

    // Create item wrapper
    let itemWrapper = document.createElement('div');
    itemWrapper.setAttribute('class', 'item-wrapper d-inline-block');

    // Poster Image.
    let posterImage = document.createElement('img');
    posterImage.setAttribute('class', 'poster-image d-inline');
    posterImage.setAttribute('src', item.poster);
    posterImage.setAttribute('alt', item.title);
    itemWrapper.appendChild(posterImage);

    // hover
    let posterHover = document.createElement('div');
    posterHover.setAttribute('class', 'poster-hover');

    let itemTitle = document.createElement('span');
    itemTitle.setAttribute('class', 'item-title');
    itemTitle.innerHTML = item.title;
    posterHover.appendChild(itemTitle);

    let itemDescription = document.createElement('span');
    itemDescription.setAttribute('class', 'item-description');
    itemDescription.innerHTML = item.synopsis;
    posterHover.appendChild(itemDescription);

    let itemRelease = document.createElement('span');
    itemRelease.setAttribute('class', 'item-release');
    itemRelease.innerHTML = item.year;
    posterHover.appendChild(itemRelease);

    let itemGenres = document.createElement('span');
    itemGenres.setAttribute('class', 'item-genre');
    itemGenres.innerHTML = item.genres;
    posterHover.appendChild(itemGenres);

    let viewButton = document.createElement('button');
    viewButton.setAttribute('class', 'btn btn-success');
    viewButton.onclick = function() {
      show_movie_details(item);
      document.querySelector('#modal-button').click();
    }
    viewButton.innerHTML = 'View More';
    posterHover.appendChild(viewButton);

    itemWrapper.appendChild(posterHover);
    outerContainer.appendChild(itemWrapper);

    return outerContainer;
  }

  function toggleRating() {
    document.querySelector('#rating-toggle').classList.toggle('d-flex');
    document.querySelector('#rating-toggle').classList.toggle('d-none');
  }

  function setRating(rating = 0) {
    let e = document.querySelector('#rating-container').firstElementChild;
    for(let i = 1; i <= 10; i++) {
      if(i <= rating) {
        e.classList.remove('far');
        e.classList.add('fas');
      } else {
        e.classList.remove('fas');
        e.classList.add('far');
      }
      e = e.nextElementSibling;
    }
    if(current_movie_rating == 0) {
      document.querySelector('#rating-star').classList.remove('fas');
      document.querySelector('#rating-star').classList.add('far');
      document.querySelector('#rating-star-text').innerHTML = '<span>Rate</span><span>This</span>';
    } else {
      document.querySelector('#rating-star').classList.remove('far');
      document.querySelector('#rating-star').classList.add('fas');
      document.querySelector('#rating-star-text').innerHTML = '<h2 class="m-0">' + current_movie_rating + '</h2><span>You</span>';
    }
  }

  function initRating() {
    let ratingContainer = document.querySelector('#rating-container');
    ratingContainer.innerHTML = '';
    for(let i = 1; i <= 10; i++) {
      let ratingStar = document.createElement('button');
      ratingStar.setAttribute('type', 'button');
      ratingStar.setAttribute('class', 'btn p-0 border-0 fa-star text-light');
      ratingStar.onmouseover = function() {
        setRating(i);
      }
      ratingStar.onmouseout = function() {
        setRating(current_movie_rating);
      }
      ratingStar.onclick = function() {
        current_movie_rating = i;
        setRating(i);
        rate_movie();
      }
      ratingContainer.appendChild(ratingStar);
    }
  }

  initRating();

  function capitalizeFirstLetter(string) {
    return string.charAt(0).toUpperCase() + string.slice(1);
  }

  async function show_movie_details(item) {
    current_movie = item.id;

    document.querySelector('#modal-details-title').innerHTML = item.title;
    document.querySelector('#modal-details-synopsis').innerHTML = item.synopsis;
    document.querySelector('#modal-details-year').innerHTML = item.year;
    document.querySelector('#modal-details-imdb-rating').innerHTML = item.imdb_rating;
    document.querySelector('#modal-details-mmdb-rating').innerHTML = await get_movie_rating();
    document.querySelector('#modal-details-trailer').setAttribute('src', "https://www.youtube.com/embed/" + item.yt_trailer_code + "?rel=0");
    document.querySelector('#modal-poster').setAttribute('src', item.poster);

    let downloadButtons = document.querySelector('#modal-details-download-links');
    downloadButtons.innerHTML = '';
    for(let torrent of item.torrents) {
      let downloadButton = document.createElement('a');
      downloadButton.setAttribute('class', 'btn btn-outline-success btn-sm');
      downloadButton.setAttribute('href', torrent.url);
      downloadButton.innerHTML = torrent.quality + " " + capitalizeFirstLetter(torrent.type);
      downloadButtons.appendChild(downloadButton);
    }

    current_movie_rating = await get_user_rating();

    setRating(current_movie_rating);

    get_similar_movies();
  }

  function generate_similar_movie_thumb(item) {
    // Container;
    let container = document.createElement('div');
    container.setAttribute('class', 'col-6 col-sm-3 col-xl-6');

    let thumbnail = document.createElement('img');
    thumbnail.setAttribute('class', 'img-thumbnail m-2 cursor-pointer');
    thumbnail.setAttribute('src', item.poster);

    container.appendChild(thumbnail);
    container.onclick = function() {
      show_movie_details(item);
      document.querySelector('#detailsModal').scrollTop = 0;
    }

    return container;
  }

  function process_similar_movies(json_response) {
    let response = JSON.parse(json_response);
    if(response['Message'] != 'SUCCESS') {
        console.log(response);
        return;
    }
    let similar_movies_list = document.querySelector('#similar-movies-list');
    similar_movies_list.innerHTML = '';
    for(let item of response['Similar']) {
      similar_movies_list.appendChild(generate_similar_movie_thumb(item));
    }
  }

  function get_similar_movies() {
    return new Promise(function(resolve, reject) {
      let xhttp = new XMLHttpRequest();
      xhttp.onreadystatechange = function() {
        if (this.readyState == 4 && this.status == 200) {
          process_similar_movies(this.responseText);
        }
      };
      xhttp.open("GET", "<?php echo BACKEND;?>similar_movies.php?movie_id=" + current_movie, true);
      xhttp.send();
    });
  }
</script>
