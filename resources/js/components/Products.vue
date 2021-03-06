<template>
  <div class="row">
    <!-- cart badge start -->
    <div class="col text-center">
      <button class="btn btn-primary" data-toggle="modal" data-target="#cart">
        <i class="fas fa-shopping-cart"></i>
        <span class="total-quantity">Cart {{ totalQuantity }}</span>
      </button>
    </div>
    <!-- cart badge end -->

    <!-- products start -->
    <div class="col-md-12">
      <div class="row">
        <div v-for="product in products" :key="product.id" class="card card-body col-md-3">
          <img class="w-100 rounded-circle" :src="product.image" alt />
          <br />
          <h4>{{ product.name }}</h4>
          <p>{{ product.description }}</p>
          <p>${{ product.price }}</p>

          <div class="align-middle mt-2">
            <button @click="updateCart(product, 'subtract')" class="btn btn-warning">-</button>
            &nbsp;
            <span>{{ product.quantity }}</span>
            &nbsp;
            <button @click="updateCart(product, 'add')" class="btn btn-warning">+</button>
          </div>
        </div>
      </div>
    </div>
    <!-- products end -->

    <!-- cart start -->
    <div class="col-md-3">
      <!-- cart modal start -->
      <div class="modal fade" id="cart" data-backdrop="static" data-keyboard="false">
        <div class="modal-dialog modal-dialog-centered modal-lg">
          <div class="modal-content">
            <div class="modal-header">
              <h5 class="modal-title">Shopping Cart</h5>
              <button class="close" data-dismiss="modal">&times;</button>
            </div>
            <div class="modal-body">
              <table class="table">
                <tbody>
                  <tr v-for="(product) in cart" :key="product.id">
                    <td>{{product.name}} (x{{product.quantity }})</td>
                    <td>${{ product.price }}</td>
                    <td>
                      <button @click="updateCart(product, 'subtract')" class="btn btn-warning">-</button>
                      <span>{{ product.quantity }}</span>
                      <button @click="updateCart(product, 'add')" class="btn btn-warning">+</button>
                    </td>
                  </tr>
                </tbody>
              </table>
            </div>
            <div class="modal-footer">Subtotal: $ {{subTotal}} &nbsp;</div>
            <div class="modal-footer">Delivery Cost: $ {{deliveryCost}} &nbsp;</div>
            <div
              class="modal-footer"
            >Total Price: EUR {{ totalPrice * 0.89 }} / USD {{totalPrice }}&nbsp;</div>
            <div class="modal-footer">
              <button
                data-toggle="modal"
                data-dismiss="modal"
                data-target="#checkout"
                class="btn btn-primary"
              >Checkout</button>
            </div>
          </div>
        </div>
      </div>
      <!-- cart modal end -->
    </div>
    <!-- cart end -->

    <!-- checkout modal start -->
    <div class="modal fade" id="checkout" data-backdrop="static" data-keyboard="false">
      <div class="modal-dialog modal-dialog-centered modal-lg">
        <div class="modal-content">
          <div class="modal-header">
            <h5 class="modal-title">Checkout</h5>
            <button class="close" data-dismiss="modal">&times;</button>
          </div>
          <div class="modal-body">
            <form>
              <div class="form-group">
                <label>Name</label>
                <input type="text" class="form-control" v-model="order.name" />
              </div>
              <div class="form-group">
                <label>Phone</label>
                <input type="tel" class="form-control" v-model="order.phone" />
              </div>
              <div class="form-group">
                <label>Address</label>
                <input type="text" class="form-control" v-model="order.address" />
              </div>
            </form>
          </div>
          <div class="modal-footer">
            <button
              data-toggle="modal"
              data-dismiss="modal"
              data-target="#cart"
              class="btn btn-warning"
              style="left: 0;"
            >Back to cart</button>
            <button
              class="btn btn-success"
              type="submit"
              @click.prevent="updateOrder()"
              data-dismiss="modal"
            >Order EUR {{ totalPrice * 0.89 }} / USD {{totalPrice }}</button>
          </div>
        </div>
      </div>
    </div>
    <!-- checkout modal end -->
  </div>
</template>

<script>
export default {
  data() {
    return {
      products: [],
      orders: [],
      order: {
        id: "",
        product_id: "",
        product_quantity: "",
        total_price: "",
        name: "",
        phone: "",
        address: ""
      },
    };
  },
  created() {
    this.viewProduct();
    this.viewOrder();
  },

  computed: {
    cart() {
      return this.products.filter(product => product.quantity > 0);
    },
    totalQuantity() {
      return this.products.reduce(
        (total, product) => total + product.quantity,
        0
      );
    },
    deliveryCost() {
      let deliveryCost = 4;
      return deliveryCost;
    },
    subTotal() {
      let subTotal = 0;
      for (let p of this.products) {
        subTotal += p.price * p.quantity;
      }
      return subTotal;
    },
    totalPrice() {
      let totalPrice = this.subTotal + this.deliveryCost;
      return totalPrice;
    }
  },

  methods: {
    viewProduct() {
      fetch("api/products")
        .then(res => res.json())
        .then(res => {
          this.products = res.data;
        })
        .catch(err => console.log(err));
    },

    updateCart(product, updateType) {
      for (let i = 0; i < this.products.length; i++) {
        if (this.products[i].id === product.id) {
          if (updateType === "subtract") {
            if (this.products[i].quantity !== 0) {
              this.products[i].quantity--;
              swal("Oops!", "pizza removed", "error");
            }
          } else {
            this.products[i].quantity++;
            swal("Whoopee!", "pizza added", "success");
          }
          break;
        }
      }
    },

    viewOrder() {
      fetch("api/orders")
        .then(res => res.json())
        .then(res => {
          this.orders = res.data;
        })
        .catch(err => console.log(err));
    },

    updateOrder() {
      fetch("api/orders", {
        method: "post",
        body: JSON.stringify(this.order),
        headers: {
          "content-type": "application/json"
        }
      })
        .then(res => res.json())
        .then(data => {
          swal("Awesome!", "thanks for your order", "success");
          this.order.id = "";
          this.order.product_id = "";
          this.order.product_quantity = "";
          this.order.total_price = "";
          this.order.name = "";
          this.order.phone = "";
          this.order.address = "";
          this.viewOrder();
        })
        .catch(err => {
          swal("Oops!", "order failed, please check your info", "error");
        });
    }
  }
};
</script>

<style lang="scss">
.card {
  border: none;
}
</style>