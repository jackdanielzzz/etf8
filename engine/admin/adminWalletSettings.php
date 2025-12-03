<?php
    include('adminHeader.php');

    if (($_SESSION['user_id'] == '1') || ($_SESSION['user_id'] == '2')) {

    } else die("you are baby admin. not allowed to change wallet");

    ?>

        <div class="page-body">
          <div class="container-fluid">
            <div class="page-header">
              <div class="row">
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
                          <h5>Настройки кошельков</h5>
                      </div>

                      <div class="col-sm-6">

                      </div>

                      <div class="card-body">
                          <form class="row" enctype="multipart/form-data" method="post" action="/admin/save-wallet">
                              <div class="col-sm-12">
                                  <div class="mb-3">
                                      <label>Bitcoin(BTC)</label>
                                      <input class="form-control" name="bitcoin_wallet" type="text" value="<?= getBitcoinWallet($connect) ?>">
                                  </div>
                              </div>


                              <div class="col-sm-12">
                                  <div class="mb-3">
                                      <label>Tether(TRC-20)</label>
                                      <input class="form-control" name="tether_wallet" type="text" value="<?= getTetherWallet($connect) ?>">
                                  </div>
                              </div>

                              <div class="col-sm-12">
                                  <div class="mb-3">
                                      <label>Ethereum (ETH)</label>
                                      <input class="form-control" name="ethereum_wallet" type="text" value="<?= getEthereumWallet($connect) ?>">
                                  </div>
                              </div>

                              <div class="col-sm-12">
                                  <div class="mb-3">
                                      <label>Tron (TRX)</label>
                                      <input class="form-control" name="tron_wallet" type="text" value="<?= getTronWallet($connect) ?>">
                                  </div>
                              </div>

                              <div class="col-sm-12">
                                  <div class="mb-3">
                                      <label>Litecoin (LTC)</label>
                                      <input class="form-control" name="litecoin_wallet" type="text" value="<?= getLitecoinWallet($connect) ?>">
                                  </div>
                              </div>

                              <div class="col-sm-12">
                                  <div class="mb-3">
                                      <label>TonCoin (TON)</label>
                                      <input class="form-control" name="toncoin_wallet" type="text" value="<?= getToncoinWallet($connect) ?>">
                                  </div>
                              </div>

<!--                              <div class="col-sm-12">-->
<!--                                  <div class="mb-3">-->
<!--                                      <label>PayPal (USD)</label>-->
<!--                                      <input class="form-control" name="paypal_wallet" type="text" value="--><?//= getPaypalWallet($connect) ?><!--">-->
<!--                                  </div>-->
<!--                              </div>-->

                              <div class="col-sm-12">
                                    <input class="btn btn-primary" type="submit" name="accept" id="accept" value="Сохранить">
                              </div>
                          </form>
                      </div>


                  </div>
              </div>
            </div>
          </div>
          <!-- Container-fluid Ends-->
        </div>
<?php
include('adminFooter.php');