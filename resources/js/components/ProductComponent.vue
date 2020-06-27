<template>
  <div class="row">
    <div class="col-md-9 left">
      <div class="row">
        <div v-for="product in products" v-bind:key="product.id" class="card card-body col-md-4">
          <h4>{{ product.name }}</h4>
          <p>{{ product.description }}</p>
          <div class="row">
            
            <div class="col-md-6">$ {{ product.price }}</div>
          </div>
          <p class="text-right mt-2">
            <input type="number" v-model="quantity" style="width:60px"/>
            <button @click="addCart(product)" class="btn btn-success">Add to Cart</button>
          </p>
        </div>
      </div>
    </div>
    <div class="col-md-3 right">
      <button class="btn btn-primary" data-toggle="modal" data-target="#cart">
        <i class="fas fa-shopping-cart"></i>
        <span class="badge badge-light">{{badge}}</span>
      </button>
      <div class="modal fade" id="cart">
                <div class="modal-dialog modal-dialog-centered modal-lg">
                    <div class="modal-content">
                        <div class="modal-header">
                            <h5 class="modal-title">Your Cart</h5>
                            <button class="close" data-dismiss="modal">&times;</button>
                        </div>
                        <div class="modal-body">
                            <table class="table table-striped text-left">
                                <tbody>
                                    <tr v-for="(cart, n) in carts" v-bind:key="cart.id">
                                        <td>{{cart.name}}</td>
                                        <td>$ {{cart.price}}</td>
                                        <td width="100">
                                          <input type="text" readonly class="form-control" v-model="cart.amount"/>
                                          </td>
                                        <td width="60">
                                            <button @click="removeCart(n)" class="btn btn-danger btn-sm">
                                              <i class="fas fa-times-circle"></i></button>
                                        </td>
                                    </tr>
                                </tbody>
                            </table>
                        </div>
                        <div class="modal-footer">
                            Total Price: $ {{totalprice}} &nbsp;
                            <a href="/checkout" class="btn btn-primary">Checkout</a>
                        </div>
                    </div>
                </div>
            </div>
    </div>
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
        price: ""
      },
      add: true,
      edit: false,
      carts: [],
      cartadd: {
        id: "",
        name: "",
        price: ""
      },
      badge: "0",
      quantity: "0",
      totalprice: "0"
    };
  },
  created() {
    this.viewProduct();
    this.viewCart();
  },
  methods: {
    viewCart() {
      if (localStorage.getItem("carts")) {
        this.carts = JSON.parse(localStorage.getItem("carts"));
        this.badge = this.carts.length;
        this.totalprice = this.carts.reduce((total, item) => {
          return total + item.amount * item.price;
        }, 0);
      }
    },
    addCart(pro) {
      this.cartadd.id = pro.id;
      this.cartadd.name = pro.name;
      this.cartadd.price = pro.price;
      this.cartadd.amount = this.quantity;
      this.carts.push(this.cartadd);
      this.cartadd = {};
      this.storeCart();
    },
    removeCart(pro) {
      this.carts.splice(pro, 1);
      this.storeCart();
    },
    storeCart() {
      let parsed = JSON.stringify(this.carts);
      localStorage.setItem("carts", parsed);
      this.viewCart();
    },
    viewProduct() {
      fetch("api/products")
        .then(res => res.json())
        .then(res => {
          this.products = res.data;
        })
        .catch(err => console.log(err));
    }
  }
};
</script>