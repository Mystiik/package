<?php require_once($_SERVER['DOCUMENT_ROOT'] . '/include/init.php'); ?>

<!DOCTYPE html>
<html lang="en-US" dir="ltr">

<head>
  <meta charset="utf-8">
  <meta http-equiv="X-UA-Compatible" content="IE=edge">
  <meta name="viewport" content="width=device-width, initial-scale=1">


  <!-- ===============================================-->
  <!--    Document Title-->
  <!-- ===============================================-->
  <title>Admin | Dashboard</title>


  <!-- ===============================================-->
  <!--    Favicons-->
  <!-- ===============================================-->
  <link rel="icon" type="image/svg" href="/admin/assets/img/icons/admin.svg">
  <meta name="theme-color" content="#ffffff">
  <script src="/admin/assets/js/config.navbar-vertical.js"></script>


  <!-- ===============================================-->
  <!--    Stylesheets-->
  <!-- ===============================================-->
  <link href="/admin/assets/css/bootstrap.css" rel="stylesheet" />
  <link href="/admin/assets/css/falcon/theme.css" rel="stylesheet" />
  <link href="/admin/assets/css/upload_files.css" rel="stylesheet" />
</head>


<body>

  <!-- ===============================================-->
  <!--    Main Content-->
  <!-- ===============================================-->
  <main class="main" id="top">
    <div class="container">
      <?php include(ROOT . "/admin/include/navbar-top.php"); ?>
      <?php include(ROOT . "/admin/include/navbar-left.php"); ?>

      <div class="content">
        <div id="ckeditor-flex" class="d-flex flex-wrap">

          <div class="col-12 p-2" style="order:-10000;">
            <div class="card">
              <div class="bg-holder d-none d-lg-block bg-card" style="background-image:url(/admin/assets/img/illustrations/corner-4.png);">
              </div>
              <!--/.bg-holder-->

              <div class="d-flex flex-wrap card-body z-index-1">
                <div class="col-12 col-lg-8 p-2">
                  <h3>Import d'images</h3>
                  <p class="mb-0">Les images importées sont automatiquement optimisées pour être affichée le plus rapidement possible à vos clients tout en gardant une résolution maximale.</p>
                  <a class="btn btn-link btn-sm pl-0 mt-2" href="/admin/article/image/gestion">
                    Gérer les images
                    <span class="fas fa-chevron-right ml-1 fs--2"></span>
                  </a>
                </div>

                <!-- Upload zone -->
                <div class="col-12 p-2 mb-2">
                  <div id="upload-zone" class="upload-zone">
                    <img class="mr-2" src="/admin/assets/img/icons/cloud-upload.svg" width="25" alt="" />
                    Drop your files here
                    <input type="file" id="input-file" name="file[]" class="d-none" multiple />
                  </div>
                </div>

                <div class="col-12 col-md-6 col-lg-4 col-xl-4 col-xxl-3 p-2 order-4 d-flex flex-column">
                  <label class="form-label">Upload</label>
                  <div onclick="upload_files();" class="d-flex btn btn-falcon-primary btn-block flex-grow-1" style="margin: 0.2rem 0 0.1rem 0;">
                    <span class="m-auto">Upload files</span>
                  </div>
                </div>

                <div class="col-12 col-md-6 col-lg-4 col-xl-4 col-xxl-3 p-2 order-4 d-flex flex-column">
                  <label class="form-label">Unselect</label>
                  <div onclick="unselect();" class="d-flex btn btn-falcon-primary btn-block flex-grow-1" style="margin: 0.2rem 0 0.1rem 0;">
                    <span class="m-auto">Unselect all files</span>
                  </div>
                </div>

              </div>
            </div>
          </div>

          <!-- Upload preview -->
          <div id="upload-preview" class="d-flex flex-wrap col-12" style="order:0;">
          </div>

          <!-- Modal -->
          <div id="upload-modal" class="modal fade">
            <div class="modal-dialog modal-md">
              <div class="modal-content card-body">
                <div class="modal-body">
                  <h2>Upload en cours</h2>
                  <h4>- veuillez laisser la fenêtre ouverte</h4><br>
                  <p><span id="upload-number">1</span> fichier(s) envoyés</p>
                </div>
                <div class="modal-footer">
                  <button type="button" class="btn btn-secondary" data-dismiss="modal">Fermer</button>
                  <a class="btn btn-primary" href="/admin/article/image/gestion">Gérer</a>
                </div>
              </div>
            </div>
          </div>


        </div>


        <div class="col-12 p-2" style="min-height: 5.43rem;"></div>
        <div class="col-12 p-2" style="min-height: 5.43rem;"></div>

      </div>
    </div>
  </main>

  <script src="/admin/vendors/bootstrap/bootstrap.min.js"></script>
  <script src="/admin/assets/js/upload-files.js"></script>
  <script src="/admin/vendors/fontawesome/all.min.js"></script>

</body>

</html>