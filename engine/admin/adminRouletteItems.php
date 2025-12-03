<?php
    include('adminHeader.php');

//    $rouletteItems = getAllRouletteItems();
?>

        <div class="page-body">
          <div class="container-fluid">
            <div class="page-header">
              <div class="row">
                <div class="col-sm-6">
                    <a href="/admin/roulette/new">
                        <button class="btn btn-outline-primary">Добавить приз</button>
                    </a>
                </div>
                <div class="col-sm-6">

                </div>
              </div>
            </div>
          </div>
          <!-- Container-fluid starts-->
          <div class="container-fluid">
            <div class="row">
              <!-- Zero Configuration  Starts-->
              <div class="col-sm-12">
                  <div class="card">
                      <div class="card-header">
                          <h5>Призы рулетки</h5>
                      </div>

                      <div class="col-sm-6">

                      </div>

                      <div class="card-body">
                          <div class="table-responsive">
                              <table class="display" id="basic-2-col-sort-desk">
                                  <thead>
                                  <tr>
                                      <th>ID</th>
                                      <th>Токен</th>
                                      <th>Название</th>
                                      <th>Шанс (user)</th>
                                      <th>Шанс (guest)</th>
                                      <th>Сортировка</th>
                                      <th>Статус</th>
                                  </tr>
                                  </thead>
                                  <tbody>

                                  <?php
                                  foreach($allNews as $oneNews ):
                                      if ((new DateTime()) < new DateTime($oneNews['news_date']))
                                          $color = 'red';
                                      else $color = 'black'; ?>
                                      <tr>
                                          <td><?= $oneNews['id'] ?></td>
                                          <td><a href="/admin/promo/edit?id=<?=$oneNews['id']?>"><?= $oneNews['news_title_ru'] ?></a></td>
                                          <td style="color: <?= $color ?>">
                                              <?php echo $oneNews['news_date'] . "<br>";
                                              if ($color == 'red') echo "<small>(будущее)</small>" ?>
                                          </td>
                                      </tr>
                                  <?php endforeach;?>

                                  </tbody>
                              </table>
                          </div>
                      </div>
                  </div>
              </div>
            </div>
          </div>
          <!-- Container-fluid Ends-->
        </div>
<?php
include('adminFooter.php');