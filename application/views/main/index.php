<?php
defined('BASEPATH') OR exit('No direct script access allowed');
$this->load->view('header', $this->stash);
?>
<div class="body-container">
    <div class="content j-content">
        <div style="">
            <div class="row">
                <div class="col-sm-8 col-sm-offset-2 jumbotron">

                    <!-- Main component for a primary marketing message or call to action -->
                    <div class="row">
                        <div class="col-xs-10 col-xs-offset-1 ta-center">
                            <h1>Просто-репетитор</h1>

                            <p>Расписание и ученики</p>

                            <p>
                                <a class="btn btn-lg btn-primary col-xs-8 col-xs-offset-2" href="/dashboard/" role="button">Открыть</a>
                                
                            </p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<?php
$this->load->view('footer');
?>