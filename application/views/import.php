<div class="container">
    <div class="m-r-auto m-l-auto m-b-40 m-t-60">
        <h2 class="font-black text-primary text-center">
            Impor data user
        </h2>
    </div>
    <div class="section col-md-6 text-center m-lr-auto p-all-24 m-b-80">
        <div class="p-tb-24">
            <form method="POST" enctype='multipart/form-data' action="<?=site_url("importuser/import")?>">
                <input type="hidden" name="<?=$this->security->get_csrf_token_name()?>" value="<?=$this->security->get_csrf_hash();?>" />
                <div class="form-group">
                    <label>Pilih file</label>
                    <input type="file" class="form-control" name="file" required />
                </div>
                <div class="form-group m-b-0">
                    <button id="ceksubmit" type="submit" class="btn btn-success"><i class="fas fa-check"></i> &nbsp;Impor Sekarang</button>
                </div>
            </form>
        </div>
    </div>
</div>