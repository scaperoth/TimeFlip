<style>
    .img{
        width: 554px;
        height: 298px;
        background-size: cover;
        background-position: center;
    }

    img.full_img{
        width:50%;
        height:auto;
    }

    .from_name{
        padding-top:10px;
    }

</style>


<?php if ($this->facebook->get_user()): ?>

    <?php
    $data = $this->facebook->get_user();
    ?>
    <header>
        <section id="portfolio">
            <div class="container-fluid" >
                <div class="row no-gutter" >
                    <?php foreach ($data["data"] as $item): ?>

                        <?php $unique_id = $item->id; ?>

                        <div class="col-lg-4 col-sm-6">
                            <a href="#" class="portfolio-box" data-toggle="modal" data-target="#myModal<?= $unique_id ?>">

                                <?php if (property_exists($item, 'object_id')): ?>
                                    <div class="img" style="background-image:url('https://graph.facebook.com/<?php echo $item->object_id ?>/picture?type=normal&access_token=<?php echo $this->facebook->session->getAccessToken(); ?>') ">
                                    </div>
                                <?php elseif (property_exists($item, 'picture')): ?>
                                    <div class="img" style="background-image:url('<?php echo $item->picture ?>') ">
                                    </div>
                                <?php elseif (property_exists($item, 'message')): ?>
                                    <div class="img text-left" style="background:#fff ">
                                        <?php if (property_exists($item, 'from')): ?>
                                            <div class="row">
                                                <div class="col-lg-12">
                                                    <div class="col-lg-2 ">
                                                        <div class="profile_picture pull-left">
                                                            <img src="https://graph.facebook.com/<?= $item->from->id ?>/picture"/>
                                                        </div>
                                                    </div>
                                                    <div class="col-lg-10 pull-left ">
                                                        <div class="from_name">
                                                            <p><b><?= $item->from->name ?></b></p>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        <?php endif; ?>
                                        <div class="col-lg-12">
                                            <?php
                                            $message = explode("\n", $item->message);
                                            foreach ($message as $line)
                                            {
                                                echo "<p>";
                                                echo $line;
                                                echo "</p>";
                                            }
                                            ?>
                                        </div>
                                    </div>
                                <?php endif; ?>

                                <div class="portfolio-box-caption">
                                    <div class="portfolio-box-caption-content">
                                        <div class="project-category text-faded">
                                            <?php echo $item->updated_time ?>
                                        </div>
                                        <div class="project-name">
                                        </div>
                                    </div>
                                </div>
                            </a>
                        </div>
                    <?php endforeach; ?>
                </div>
            </div>
        </section>
    </header>

    <?php foreach ($data["data"] as $item): ?>
        <?php $unique_id = $item->id; ?>
        <?php if (property_exists($item, 'updated_time')): ?>
            <!-- Modal-->
            <div class="modal fade" id="myModal<?= $unique_id ?>" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
                <div class="modal-dialog">
                    <div class="modal-content">
                        <div class="modal-header">
                            <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                            <h4 class="modal-title" id="myModalLabel">Modal title</h4>
                        </div>
                        <div class="modal-body">
                            <?php if (property_exists($item, 'from')): ?>
                                <div class="row">
                                    <div class="col-lg-12">
                                        <div class="col-lg-2 ">
                                            <div class="profile_picture pull-left">
                                                <img src="https://graph.facebook.com/<?= $item->from->id ?>/picture"/>
                                            </div>
                                        </div>
                                        <div class="col-lg-10 pull-left ">
                                            <div class="from_name">
                                                <p><b><?= $item->from->name ?></b></p>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            <?php endif; ?>
                            <div class="row">
                                <?php if (property_exists($item, 'object_id')): ?>

                                    <img class="full_img" src="https://graph.facebook.com/<?php echo $item->object_id ?>/picture?type=normal&access_token=<?php echo $this->facebook->session->getAccessToken(); ?>"/>
                                <?php elseif (property_exists($item, 'picture')): ?>
                                    <img class="full_img" src="<?php echo $item->picture ?>"/>


                                <?php elseif (property_exists($item, 'message')): ?>
                                    <div class="img text-left" style="background:#fff ">
                                        <div class="col-lg-12">
                                            <?php
                                            $message = explode("\n", $item->message);
                                            foreach ($message as $line)
                                            {
                                                echo "<p>";
                                                echo $line;
                                                echo "</p>";
                                            }
                                            ?>
                                        </div>
                                    </div>
                                <?php endif; ?>
                            </div>

                        </div>
                        <div class="modal-footer">
                            <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
                            <button type="button" class="btn btn-primary">Save changes</button>
                        </div>
                    </div>
                </div>
            </div> 
        <?php endif; ?>
    <?php endforeach; ?>
<?php else: ?>    
    <header>
        <div class="header-content">
            <div class="header-content-inner">
                <h1>Sorry, we couldn't find any content. </h1>
                <hr>
                <p>Please go back and try again </p>
                <a href="home" class="btn btn-primary btn-xl page-scroll ">Head on home...</a>
            </div>
        </div>

    </header>
<?php endif; ?>

<?php
echo "<pre>";
var_dump($this->facebook->get_user());
echo "</pre>";
?>