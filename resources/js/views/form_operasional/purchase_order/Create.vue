<template>
  <div class="row mt-5">
    <div class="col col-xl-12 stretch-card">
      <div class="card">
        <div class="card-body">
          <router-link :to="{ name: 'formOperasional.purchaseOrder' }">
            <i class="fas fa-backspace"></i>
            Kembali
          </router-link>
          <div class="card-title">Purchase Order Baru</div>
          <form>
            <div class="row">
              <div class="col col-md-6">
                <div class="form-group">
                  <label>Cabang</label>
                  <select class="form-control">
                    
                  </select>
                </div>
                <div class="form-group">
                  <label>Supplier</label>
                  <select class="form-control">
                    
                  </select>
                </div>
              </div>
              <div class="col col-md-6">
                <div class="form-group">
                  <label>Keterangan</label>
                  <textarea class="form-control"></textarea>
                </div>
              </div>
            </div>
            <div class="row mt-3">
              <table class="table table-hover">
                <thead>
                  <th>#</th>
                  <th>Barang</th>
                  <th>Satuan</th>
                  <th>Qty</th>
                  <th>Harga Barang</th>
                </thead>
                <tbody>
                  <tr>
                    <td>1</td>
                    <td>
                      <i class="text-danger mr-3">Barang belum dipilih</i>
                      <barang-component/>
                    </td>
                    <td>
                      <select class="form-control">
                        
                      </select>
                    </td>
                    <td>
                      <input type="number" class="form-control">
                    </td>
                    <td>
                      <input type="number" class="form-control">
                    </td>
                  </tr>
                </tbody>
              </table>
            </div>
          </form>
        </div>
      </div>
    </div>
  </div>
</template>