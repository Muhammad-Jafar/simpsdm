
    <div class="content-wrapper">
      <div class="row">
        <div class="col-lg-12 grid-margin stretch-card">
          <div class="card card-rounded">
            <div class="card-body">
              <h4 class="card-title"><?=$title_page;?></h4>
              <?php if($this->session->flashdata('msg_alert')) { ?>

              <div class="alert alert-info">
                <label style="font-size: 13px;"><?=$this->session->flashdata('msg_alert');?></label>
              </div>
              <?php } ?>
              <?=form_open('data_master/edit/keluhan/' . $data_keluhan->id_keluhan, array('method'=>'post'));?>
                <input type="hidden" name="id_keluhan" value="<?=$data_keluhan->id_keluhan;?>">
                <!-- <div class="row">
                  <div class="col-md-6">
                    <div class="form-group row">
                      <label class="col-sm-3 col-form-label">Jenis Keluhan</label>
                      <div class="col-sm-9">
                        <select name="type" class="form-control">
                          <option disabled selected>-- Pilih --</option>
                          <?php
                            $izin = array(
                                array(
                                    'id'    => 'Hubungan kerja',
                                    'nama'  => 'Hubungan kerja'
                                  ),
                                  array(
                                    'id'    => 'Penggajian',
                                    'nama'  => 'Penggajian'
                                  )
                            );
                            foreach($izin as $iz) {
                          ?>
                          <option value="<?=$iz['id'];?>" <?=( ($iz['id']==$data_namaizin->type) ? 'selected' : '');?>><?=$iz['nama'];?></option>
                          <?php
                            }
                          ?>
                        </select>
                      </div>
                    </div>
                  </div>
                </div> -->
                <div class="row">
                  <div class="col-md-6">
                    <div class="form-group row">
                      <label class="col-sm-3 col-form-label">Jenis Keluhan</label>
                      <div class="col-sm-9">
                        <input type="text" value="<?=$data_keluhan->type;?>" name="type" class="form-control" />
                      </div>
                    </div>
                  </div>
                </div>
                <div class="row">
                  <div class="col-md-6">
                    <div class="form-group row">
                      <button type="submit" class="btn btn-success mr-2">Submit</button>
                      <button class="btn btn-light" type="reset">Reset</button>
                    </div>
                  </div>
                </div>
              <?=form_close();?>
            </div>
          </div>
        </div>
    </div>
  </div>