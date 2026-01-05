<?php
  if($saldo->num_rows() > 0){
?>
  <div class="table-responsive section p-all-24 m-b-20">
    <table class="table table-hover">
      <tr>
        <th>Tanggal</th>
        <th style="width:40%;">Keterangan</th>
        <th>Status</th>
        <th>Jumlah Dana</th>
        <th>Saldo Akhir</th>
      </tr>
      <?php
        foreach($saldo->result() as $res){
          $st = $this->func->getSaldotarik($res->sambung,"semua");
          $old = ($res->darike != 2) ? "[invoice]" : "[rekening]";
          switch($res->darike){
            case '1':
              $new = $this->func->getTransaksi($res->sambung,"orderid");
            break;
            case '2':
              $new = $st->idrek;
              $new = $this->func->getRekening($new,"semua");
              $bank = $this->func->getBank($new->idbank,"nama");
              $new = $bank." a/n ".$new->atasnama." (".$new->norek.")";
            break;
            case '3':
              $new = $this->func->getBayar($res->sambung,"invoice");
            break;
            case '4':
              $new = $this->func->getTransaksi($res->sambung,"orderid");
            break;
            default:
              $new = "";
            break;
          }
          $statush = ($res->darike == 2) ? $st->status : 1;
          $status = ($statush == 1) ? "<span class='text-success'><i class='fas fa-check-circle'></i> Berhasil</span><br/><small>".$this->func->ubahTgl("d M Y H:i",$res->tgl)."WIB</small>" : "<span class='text-warning'><i class='fas fa-clock'></i> Sedang Diproses</span>";
          $status = ($statush == 2) ? "<span class='text-success'><i class='fas fa-times-circle'></i> Dibatalkan</span><br/><small>".$this->func->ubahTgl("d M Y H:i",$st->selesai)."WIB</small>" : $status;
          $jumlah = $this->func->formUang($res->jumlah);
          $jumlah = ($res->darike != 2 AND $res->darike != 3) ? "<span class='text-success'>Rp ".$jumlah."</span>" : "<span class='text-danger'>Rp ".$jumlah."</span>";
      ?>
      <tr>
        <td><?php echo $this->func->ubahTgl("d M Y H:i",$res->tgl); ?></td>
        <td><?php echo str_replace($old,$new,$this->func->getSaldodarike($res->darike,"keterangan")); ?></td>
        <td><?php echo $status; ?></td>
        <td><?php echo $jumlah; ?></td>
        <td>Rp <?php echo $this->func->formUang($res->saldoakhir); ?></td>
      </tr>
      <?php
        }
      ?>
    </table>
  </div>
<?php
    echo $this->func->createPagination($rows,$page,$perpage,"historySaldo");
  }else{
    echo "
      <div class='w-full text-center section p-tb-30 m-t-10'>
        <i class='fas fa-exchange-alt fs-40 m-b-10 text-danger'></i><br/>
        <h5>BELUM ADA TRANSAKSI</h5>
      </div>
    ";
  }
?>
