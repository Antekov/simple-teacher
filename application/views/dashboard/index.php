<?php
defined('BASEPATH') OR exit('No direct script access allowed');
$this->load->view('header', $this->stash);
$this->load->view('left_menu', $this->stash);
?>
<div class="body-container">
    <div class="content j-content">
        <?php $this->load->view('top_header', $this->stash); ?>

        <!-- Main component for a primary marketing message or call to action -->
        <div class="">
            <div class="col-sm-4 col-sm-offset-2">
                <div class="">
                    <h1>Расписание</h1>


                    <p>В этом разделе можно:</p>

                    <ul>
                        <li><a href="/lesson/">Просмативать</a> недельное расписание
                            <p class="small">По неделям можно перемещаться с помощью календаря</p>
                        </li>
                        <li><a href="/lesson/edit/0/">Создавать</a> занятия
                            <p class="small">В верхнем меню можно нажать кнопку <i class="fa fa-plus"></i></p>
                        </li>
                        <li>Редактировать занятия
                            <p class="small">Можно перетащить занятие по полю расписания на другую дату/время в пределах
                                текущей недели.
                            </p>
                            <p class="small">При клике на занятии показывается меню возможных действий.
                                Можно менять статус занятия, открыть его для редактитования или перейти к профилю
                                ученика.
                            </p>
                        </li>

                    </ul>

                    <p>
                        <a class="" href="/lesson" role="button">Открыть расписание »</a>
                    </p>
                </div>
            </div>
            <div class="col-sm-4">
                <div class="">
                    <h1>Ученики</h1>

                    <p>В этом разделе можно:</p>

                    <ul>
                        <li><a href="/client/">Просмативать</a> список учеников
                            <p class="small">
                                Ученики сортируются по статусам &mdash; сначала активные, потом завершенные или
                                поставленные на паузу.<br>
                                Внутри одного статуса &mdash; по дате создания.
                            </p>
                        </li>
                        <li><a href="/client/edit/0/">Создавать</a> учеников
                            <p class="small">В верхнем меню можно нажать кнопку <i class="fa fa-plus"></i></p>
                        </li>
                        <li>Редактировать учеников
                            <p class="small">Если указать номер заказа на ВР, то после сохранения появится поле для
                                ввода комиссии заказа.
                            </p>

                        </li>

                    </ul>

                    <p>
                        <a class="" href="/client" role="button">Перейти к ученикам »</a>
                    </p>
                </div>
            </div>
        </div>
    </div>
</div>
</div>



<?php
$this->load->view('footer');
?>