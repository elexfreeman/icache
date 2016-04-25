<?php
/**
 * Created by PhpStorm.
 * User: cod_llo
 * Date: 11.03.16
 * Time: 18:11
 * Главная страница
 */

?>
<div class="boxed">

    <!--CONTENT CONTAINER-->
    <!--===================================================-->
    <div id="content-container">

        <!--Page Title-->
        <!--~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~-->
        <div id="page-title">
            <h1 class="page-header text-overflow">Рабочий стол</h1>

            <!--Searchbox-->
            <div class="searchbox">
                <div class="input-group custom-search-form">
                    <input type="text" class="form-control" placeholder="Поиск..">
							<span class="input-group-btn">
								<button class="text-muted" type="button"><i class="fa fa-search"></i></button>
							</span>
                </div>
            </div>
        </div>
        <!--~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~-->
        <!--End page title-->


        <!--Breadcrumb-->
        <!--~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~-->
        <ol class="breadcrumb">
            <li><a href="<?php echo $desctop_link; ?>">Рабочий стол</a></li>
            <li>Пациенты</li>
        </ol>
        <!--~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~-->
        <!--End breadcrumb-->

        <?php echo $search_form; ?>


        <!--Page content-->
        <!--===================================================-->
        <div id="page-content">
            <div class="row">

                <div class="col-md-6 col-lg-3">

                    <a href="/lpu">
                        <div class="panel media pad-all">
                            <div class="media-left">
									<span class="icon-wrap icon-wrap-sm icon-circle bg-success">
									<i class="fa fa-hospital-o fa-2x"></i>
									</span>
                            </div>

                            <div class="media-body">
                                <p class="text-2x mar-no text-thin">Участки</p>

                                <p class="text-muted mar-no">Список участков</p>
                            </div>
                        </div>
                    </a>

                </div>
<?php print_r($lpu_list); ?>
                <div class="col-md-6 col-lg-3">

                    <a href="/patients">
                        <div class="panel media pad-all">
                            <div class="media-left">
									<span class="icon-wrap icon-wrap-sm icon-circle bg-warning">
									<i class="fa fa-wheelchair fa-2x"></i>
									</span>
                            </div>

                            <div class="media-body">
                                <p class="text-2x mar-no text-thin">Пациенты</p>

                                <p class="text-muted mar-no">Список пациентов</p>
                            </div>
                        </div>
                    </a>

                </div>

            </div>
        </div>
    </div>
</div>


