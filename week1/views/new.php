<!doctype html>
<html lang="en">
    <head>
        <!-- Required meta tags -->
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">

        <!-- Bootstrap CSS -->
        <!--
        <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap/4.6.1/css/bootstrap.min.css" integrity="sha512-T584yQ/tdRR5QwOpfvDfVQUidzfgc2339Lc8uBDtcp/wYu80d7jwBgAxbyMh0a9YM9F8N3tdErpFI8iaGx6x5g==" crossorigin="anonymous" referrerpolicy="no-referrer" />
        -->
        <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.1.3/css/bootstrap.min.css" integrity="sha384-MCw98/SFnGE8fJT3GXwEOngsV7Zt27NXFoaoApmYm81iuXoPkFOJwJ8ERdknLPMO" crossorigin="anonymous">

        <!-- Own CSS -->
        <link rel="stylesheet" href="/DDWT21/week1/css/main.css">

        <title><?= $page_title ?></title>
    </head>
    <body>
        <!-- Menu -->
        <?= $navigation ?>

        <!-- Content -->
        <div class="container">
            <!-- Breadcrumbs -->
            <div class="pd-15">&nbsp;</div>
            <?= $breadcrumbs ?>

            <div class="row">

                <!-- Left column -->
                <div class="col-md-8">
                    <!-- Error message -->
                    <?php if (isset($error_msg)){echo $error_msg;} ?>

                    <h1><?= $page_title ?></h1>
                    <h5><?= $page_subtitle ?></h5>
                    <p><?= $page_content ?></p>
                    <!-- Put your form here -->
                    <form action=<?= $form_action ?> method="POST">
                        <div class="form-group row">
                            <label for="inputName" class="col-sm-2 col-form-label">Name</label>
                            <div class="col-sm-10">
                                <input type="text" class="form-control" id="inputName" name="Name" placeholder="Enter series name" value="<?php if (isset($series_info_exp)){echo $series_info_exp['name'];} ?>" required>
                            </div>
                        </div>
                        <div class="form-group row">
                            <label for="inputCreator" class="col-sm-2 col-form-label">Creator</label>
                            <div class="col-sm-10">
                                <input type="text" class="form-control" id="inputCreator" name="Creator" placeholder="Enter series creator" value="<?php if (isset($series_info_exp)){echo $series_info_exp['creator'];} ?>" required>
                            </div>
                        </div>
                        <div class="form-group row">
                            <label for="inputSeasons" class="col-sm-2 col-form-label">Seasons</label>
                            <div class="col-sm-10">
                                <input type="number" class="form-control" id="inputSeasons" name="Seasons" placeholder="Enter number of seasons" value="<?php if (isset($series_info_exp)){echo $series_info_exp['seasons'];} ?>" required>
                            </div>
                        </div>
                        <div class="form-group row">
                            <label for="inputAbstract" class="col-sm-2 col-form-label">Abstract</label>
                            <div class="col-sm-10">
                                <textarea class="form-control" id="inputAbstract" name="Abstract" placeholder="Enter series abstract"  required><?php if (isset($series_info_exp)){echo $series_info_exp['abstract'];} ?></textarea>
                            </div>
                        </div>
                        <div class="form-group row">
                            <div class="col-sm-10">
                                <input type="hidden" id="series_id" name="series_id" value="<?= $series_id?>">
                                <button type="submit" class="btn btn-primary"><?= $submit_btn ?></button>
                            </div>
                        </div>
                    </form>
                </div>

                <!-- Right column -->
                <div class="col-md-4">

                    <?php include $right_column ?>

                </div>

            </div>
        </div>


        <!-- Optional JavaScript -->
        <!-- jQuery first, then Popper.js, then Bootstrap JS -->
        <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.5.1/jquery.slim.min.js" integrity="sha512-/DXTXr6nQodMUiq+IUJYCt2PPOUjrHJ9wFrqpJ3XkgPNOZVfMok7cRw6CSxyCQxXn6ozlESsSh1/sMCTF1rL/g==" crossorigin="anonymous" referrerpolicy="no-referrer"></script>
        <script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.16.1/umd/popper.min.js" integrity="sha512-ubuT8Z88WxezgSqf3RLuNi5lmjstiJcyezx34yIU2gAHonIi27Na7atqzUZCOoY4CExaoFumzOsFQ2Ch+I/HCw==" crossorigin="anonymous" referrerpolicy="no-referrer"></script>
        <script src="https://cdnjs.cloudflare.com/ajax/libs/bootstrap/4.6.1/js/bootstrap.min.js" integrity="sha512-UR25UO94eTnCVwjbXozyeVd6ZqpaAE9naiEUBK/A+QDbfSTQFhPGj5lOR6d8tsgbBk84Ggb5A3EkjsOgPRPcKA==" crossorigin="anonymous" referrerpolicy="no-referrer"></script>
    </body>
</html>
