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
                  <h3>Gestion des images</h3>
                  <p class="mb-0">Retrouvez toutes les images non utilisées dans vos articles ainsi que leurs informations. Les images utilisées ne peuvent pas être modifiées ou supprimées sans risque d'endommager les articles qui leurs sont liés, cette fonctionnalité est donc désactivée.
                  </p>
                  <a class="btn btn-link btn-sm pl-0 mt-2" href="/admin/article/image/import">
                    Importer des images
                    <span class="fas fa-chevron-right ml-1 fs--2"></span>
                  </a>
                </div>

              </div>
            </div>
          </div>
        </div>


        <div class="col-12 p-2" style="order:-1;">
          <div class="card">

            <div class="d-flex flex-wrap card-body z-index-1">
              <div class="col-12 col-lg-8 p-2">
                <h4>Non utilisées</h4>
              </div>
            </div>

          </div>
        </div>

        <div id="upload-preview" class="d-flex flex-wrap col-12" style="order:0;">
          <?php
          $fileList = GN\Srcset::getFileList($_SERVER['DOCUMENT_ROOT'] . GN\Srcset::DIR_SAVE_IMG . "/upload/src");
          foreach ($fileList as $file) :
          ?>
          <div class="preview_element col-12 col-md-6 col-lg-4 col-xxl-3 p-2 flex-flow-wrap" id="0">
            <div class="card h-100 d-flex flex-flow-wrap">
              <img class="object-fit-cover img-preview m-2" src="<?= str_replace("src", "250", $file['path']); ?>">
              <div class="col-5 m-2" style="align-self: flex-end;">
                <h6>ID: <?= str_replace(".jpg", "", $file['name']); ?></h6>
                <h6 class="mb-2 fs--1 text-400 lh-1"><?= $file['size']; ?></h6>
                <a class="mb-2 dropdown-item" onclick="remove_element(this);" style="text-align: center; background-color: aliceblue; cursor: pointer;">Remove File</a>
              </div>
            </div>
          </div>
          <?php endforeach; ?>

        </div>

        <div class="col-12 p-2" style="min-height: 5.43rem; order:1;"></div>

        <div class="col-12 p-2" style="order:2;">
          <div class="card">

            <div class="d-flex flex-wrap card-body z-index-1">
              <div class="col-12 col-lg-8 p-2">
                <h4>Utilisées</h4>
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
  <script src="/admin/vendors/fontawesome/all.min.js"></script>
</body>

</html>