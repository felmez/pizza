<template>
  <div class="row">
    <!-- products start -->
    <div class="col-md-8">
      <h5 class="card-title">Menu Products</h5>
      <div class="row">
        <div v-for="product in products" v-bind:key="product.id" class="card card-body col-md-4">
          <img class="w-100 rounded-circle" :src="product.image" alt />
          <br />
          <h4>{{ product.name }}</h4>
          <p>{{ product.description }}</p>
          <div class="row">
            <div class="col-md-6">$ {{ product.price }}</div>
          </div>
          <p class="text-right mt-2">
            <button @click="editProduct(product)" class="btn btn-warning">Edit</button>
            <button @click="deleteProduct(product.id)" class="btn btn-danger">Delete</button>
          </p>
        </div>
      </div>
    </div>
    <!-- products end -->

    <!--  add form start -->
    <div class="col-md-4">
      <br />
      <form>
        <div class="form-group">
          <button
            type="button"
            class="btn btn-success btn-lg btn-block"
            data-toggle="modal"
            data-target="#orders"
          >Orders History</button>
        </div>
        <div class="form-group">
          <label>Name</label>
          <input type="text" class="form-control" v-model="product.name" />
        </div>
        <div class="form-group">
          <label>Description</label>
          <textarea class="form-control" v-model="product.description"></textarea>
        </div>
        <div class="form-group">
          <label>Price</label>
          <input type="number" class="form-control" v-model="product.price" />
        </div>
        <div class="form-group">
          <label>Image URL</label>
          <input
            type="text"
            class="form-control"
            v-model="product.image"
            placeholder="https://i.ibb.co/jMmCHMd/pizza.jpg"
          />
        </div>
        <button v-if="add" @click.prevent="addProduct()" class="btn btn-primary">Add Product</button>
        <button
          v-if="edit"
          @click.prevent="updateProduct(product.id)"
          class="btn btn-warning"
        >Edit Product</button>
        <button @click.prevent="clearProduct()" class="btn">Clear</button>
      </form>
    </div>
    <!-- add form end -->

    <!-- orders history modal start -->
    <div class="modal fade" id="orders">
      <div class="modal-dialog modal-dialog-centered modal-lg">
        <div class="modal-content">
          <div class="modal-header">
            <h5 class="modal-title">Orders History</h5>
            <button class="close" data-dismiss="modal">&times;</button>
          </div>
          <div class="modal-body">
            <table class="table-bordered">
              <thead>
                <tr>
                  <th>Customer Name</th>
                  <th>Customer Phone</th>
                  <th>Customer Address</th>
                  <th>Ordered Products</th>
                  <th>Products Quantities</th>
                  <th>Total Price</th>
                  <th>Order Date</th>
                  <th>Details</th>
                </tr>
              </thead>
              <tbody>
                <tr v-for="order in orders" :key="order.id">
                  <td>{{ order.name }}</td>
                  <td>{{ order.phone }}</td>
                  <td>{{ order.address }}</td>
                  <td>{{ order.product_id }}</td>
                  <td>{{ order.product_quantity }}</td>
                  <td>{{ order.total_price }}</td>
                  <td>{{ order.created_at }}</td>
                  <td>
                    <a href="/menu" target="_blank">Order {{ order.id }}</a>
                  </td>
                </tr>
              </tbody>
            </table>
          </div>
          <div class="modal-footer">
            <button data-dismiss="modal" class="btn btn-primary">Ok</button>
          </div>
        </div>
      </div>
    </div>
    <!-- orders history modal end -->
  </div>
</template>

<script>
export default {
  data() {
    return {
      products: [],
      product: {
        id: "",
        name: "",
        description: "",
        image: "",
        price: ""
      },
      add: true,
      edit: false,
      orders: []
    };
  },
  created() {
    this.viewProduct();
    this.viewOrder();
  },
  methods: {
    viewOrder() {
      fetch("api/orders")
        .then(res => res.json())
        .then(res => {
          this.orders = res.data;
        })
        .catch(err => console.log(err));
    },
    viewProduct() {
      fetch("api/products")
        .then(res => res.json())
        .then(res => {
          this.products = res.data;
        })
        .catch(err => console.log(err));
    },
    addProduct() {
      fetch("api/products", {
        method: "post",
        body: JSON.stringify(this.product),
        headers: {
          "content-type": "application/json"
        }
      })
        .then(res => res.json())
        .then(data => {
          swal("Successful", "Product has been added", "success");
          this.product.name = "";
          this.product.description = "";
          this.product.image = "";
          this.product.price = "";
          this.viewProduct();
        })
        .catch(err => {
          swal("Failed", "Product fail to add", "error");
        });
    },
    editProduct(pro) {
      this.add = false;
      this.edit = true;
      this.product.id = pro.id;
      this.product.name = pro.name;
      this.product.description = pro.description;
      this.product.image = pro.image;
      this.product.price = pro.price;
    },
    updateProduct(id) {
      fetch(`api/products/${id}`, {
        method: "put",
        body: JSON.stringify(this.product),
        headers: {
          "content-type": "application/json"
        }
      })
        .then(res => res.json())
        .then(data => {
          swal("Successful", "Product has been updated", "success");
          this.add = true;
          this.edit = false;
          this.product.name = "";
          this.product.description = "";
          this.product.image = "";
          this.product.price = "";
          this.viewProduct();
        })
        .catch(err => {
          swal("Failed", "Product fail to update", "error");
        });
    },
    deleteProduct(id) {
      swal({
        title: "Are you sure?",
        text: "Product will be delete",
        icon: "warning",
        buttons: true,
        dangerMode: true
      }).then(willdelete => {
        if (willdelete) {
          fetch(`api/products/${id}`, {
            method: "delete"
          })
            .then(res => res.json())
            .then(data => {
              swal("Successful", "Product has been deleted", "success");
              this.viewProduct();
            })
            .catch(err => {
              swal("Failed", "Product fail to delete", "error");
            });
        }
      });
    },
    clearProduct() {
      this.add = true;
      this.edit = false;
      this.product.id = "";
      this.product.name = "";
      this.product.description = "";
      this.product.image = "";
      this.product.price = "";
    }
  }
};
</script>