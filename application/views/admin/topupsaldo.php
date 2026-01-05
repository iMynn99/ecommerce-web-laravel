<?php
  if($saldo->num_rows() > 0){
      $namatoko = $this->func->getSetting("nama");
?>
  <div class="table-responsive section p-all-24 m-b-20">
    <table class="table table-hover">
      <tr>
        <th>Tanggal</th>
        <th>Keterangan</th>
        <th>Status</th>
        <th>Jumlah Dana</th>
        <th>Aksi</th>
      </tr>
        <?php
          foreach($saldo->result() as $res){
            $status = ($res->status == 1) ? "<span class='text-success'><i class='fas fa-check-circle'></i> Berhasil</span>" : "<i class='text-warning'><i class='fas fa-clock'></i> Belum Dibayar</i>";
            $status = ($res->status == 2) ? "<span class='text-danger'><i class='fas fa-times-circle'></i> Dibatalkan</span>" : $status;
            $jumlah = $this->func->formUang($res->total);
            $idbayar = $this->func->arrEnc(array("trxid"=>$res->trxid),"encode");
        ?>
        <tr>
          <td><?php echo $this->func->ubahTgl("d M Y H:i",$res->tgl); ?></td>
          <td>
            <p>TopUp Saldo <?=$namatoko?></p>
          </td>
          <td><?php echo $status; ?></td>
          <td>Rp &nbsp;<?php echo $jumlah; ?></td>
          <td>
              <?php if($res->status == 0){ ?>
                  <a href="<?=site_url("home/topupsaldo?inv=".$idbayar)?>" class="btn btn-sm btn-success" ><i class="fas fa-check-circle"></i> Bayar</a>&nbsp;
                  <a href="javascript:void(0)" onclick="batalTopup(<?=$res->id?>)" class="btn btn-sm btn-danger"><i class="fas fa-times"></i></a>
              <?php } ?>
          </td>
        </tr>
        <?php
          }
        ?>
    </table>
  </div>
<?php
    echo $this->func->createPagination($rows,$page,$perpage,"getopupSaldo");
  }else{
    echo "
      <div class='w-full text-center section p-tb-30 m-t-10'>
        <i class='fas fa-exchange-alt fs-40 m-b-10 text-danger'></i><br/>
        <h5>BELUM ADA TRANSAKSI</h5>
      </div>
    ";
  }
?>
