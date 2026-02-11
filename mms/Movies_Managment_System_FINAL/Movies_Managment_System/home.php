<?php 
include_once('config.php');
session_start();
$is_admin = !empty($_SESSION['is_admin']);

$sql = "SELECT * FROM movies";
$selectMovies = $conn->prepare($sql);
$selectMovies->execute();
$movies_data = $selectMovies->fetchAll();
?>

<!DOCTYPE html>
<html>
<head>
  <title>Home</title>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet">
  <meta name="viewport" content="width=device-width, initial-scale=1">
</head>
<body>

<header>

  <div class="navbar navbar-dark bg-dark shadow-sm">
    <div class="container">
      <a href="#" class="navbar-brand d-flex align-items-center">
        <strong>Album</strong>
      </a>

      <a href="dashboard.php" class="navbar-toggler">
        <span class="navbar-toggler-icon"></span>
      </a>

    </div>
  </div>

</header>

<section class="py-5 text-center container">
  <div class="row py-lg-5">
    <div class="col-lg-6 col-md-8 mx-auto">
      <h1 class="fw-light">Album example</h1>
      <p class="lead text-muted">Movie collection</p>
    </div>
  </div>
</section>

<div class="album py-5 bg-light">
  <div class="container">
    <div class="row row-cols-1 row-cols-sm-2 row-cols-md-3 g-3">

      <?php foreach ($movies_data as $movie_data) { ?>

      <div class="col">
        <div class="card shadow-sm">
          <img src="movie_images/<?php echo $movie_data['movie_image']; ?>" height="350">

          <div class="card-body">
            <h4><?php echo $movie_data['movie_name']; ?></h4>
            <p class="card-text"><?php echo $movie_data['movie_desc']; ?></p>

            <div class="d-flex justify-content-between align-items-center">
              <div class="btn-group">
                <a href="details.php?id=<?php echo $movie_data['id']; ?>" class="btn btn-sm btn-outline-secondary">View</a>
                <?php if ($is_admin) { ?>
                  <a href="edit.php?id=<?php echo $movie_data['id']; ?>" class="btn btn-sm btn-outline-secondary">Edit</a>
                <?php } ?>
              </div>
              <small class="text-muted">Rating: <?php echo $movie_data['movie_rating']; ?></small>
              <small class="text-muted"><?php echo $movie_data['movie_quality']; ?></small>
            </div>

          </div>
        </div>
      </div>

      <?php } ?>

    </div>
  </div>
</div>

</body>
</html>
