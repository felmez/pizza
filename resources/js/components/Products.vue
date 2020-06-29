<template>
  <div class="row">
    <!-- products start -->
    <div class="col-md-9 left">
      <div class="row">
        <section class="products">
          <div v-for="product in products" :key="product.id" class="card card-body col-md-4">
            <h3 class="product__header">{{ product.name }}</h3>
            <p class="product__description">{{ product.description }}</p>
            <div class="row">
              <div class="col-md-6">$ {{ product.price }}</div>
            </div>
            <div class="cart">
              <button @click="updateCart(product, 'subtract')" class="cart__button">-</button>
              <span class="cart__quantity">{{ product.quantity }}</span>
              <button @click="updateCart(product, 'add')" class="cart__button">+</button>
            </div>
          </div>
        </section>
      </div>
    </div>
    <!-- products end -->

    <!-- cart start -->
    <div class="col-md-3 right">
      <nav class="nav">
        <h2 class="nav__header">Cart</h2>
        <div class="nav__cart">
          <!-- badge start -->
          <button class="btn btn-primary" data-toggle="modal" data-target="#cart">
            <i class="fas fa-shopping-cart"></i>
            <span class="total-quantity">{{ totalQuantity }}</span>
          </button>
          <!-- badge end -->
        </div>
      </nav>
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
                    <td>({{ product.quantity }}) x {{product.name}}</td>
                    <td>{{ product.quantity }} x ${{ product.price }}</td>
                    <td>
                      <button @click="updateCart(product, 'subtract')">-</button>

                      <span class="cart__quantity">{{ product.quantity }}</span>

                      <button @click="updateCart(product, 'add')">+</button>
                    </td>
                  </tr>
                </tbody>
              </table>
            </div>
            <div class="modal-footer">Subtotal: $ {{subTotal}} &nbsp;</div>
            <div class="modal-footer">Delivery Cost: $ {{deliveryCost}} &nbsp;</div>
            <div class="modal-footer">Total Price: EUR {{ totalPrice * 0.89 }} / USD {{totalPrice }}&nbsp;</div>
            <div class="modal-footer">
              <button data-toggle="modal" data-dismiss="modal" data-target="#checkout" class="btn btn-primary">Checkout</button>
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
              <form role="form">
            <div class="form-group">
              <label for="name">Name</label>
              <input type="text" class="form-control" id="name">
            </div>
            <div class="form-group">
              <label for="phone">Phone</label>
              <input type="tel" class="form-control" id="phone">
            </div>
            <div class="form-group">
              <label for="address">Address</label>
              <input type="text" class="form-control" id="address">
            </div>
          </form>
            </div>
            <div class="modal-footer">
              <button data-toggle="modal" data-dismiss="modal" data-target="#cart" class="btn btn-warning" style="left: 0;">Back to cart</button>
              <button class="btn btn-success" type="submit">Order EUR {{ totalPrice * 0.89 }} / USD {{totalPrice }}</button>
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
      products: []
    };
  },
  created() {
    this.viewProduct();
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
            }
          } else {
            this.products[i].quantity++;
          }
          break;
        }
      }
    }
  }
};
</script>

<style lang="scss">
.nav {
  align-items: center;
  background: salmon;
  color: white;
  display: flex;
  justify-content: space-between;
  padding: 2rem;

  &__header {
    font-size: 2.5rem;
  }

  &__cart {
    position: relative;

    button {
      background: none;
      border: 0;
      color: white;
      cursor: pointer;
    }

    i {
      font-size: 2rem;
    }

    .total-quantity {
      align-items: center;
      background: lightblue;
      border-radius: 50%;
      display: flex;
      font-weight: bold;
      height: 2rem;
      justify-content: center;
      padding: 0.5rem;
      position: absolute;
      right: -10px;
      top: -10px;
      width: 2rem;
    }
  }
}

.products {
  display: flex;
  flex-wrap: wrap;
  justify-content: center;

  .product {
    border: 1px solid lightgray;
    border-radius: 10px;
    margin: 2rem;
    padding: 1rem;

    &__header {
      font-size: 2rem;
      text-align: center;
    }

    &__image {
      display: block;
      margin: 1rem auto;
    }

    &__description {
      font-size: 1.3rem;
      margin-top: 1rem;
    }
  }
}

.cart {
  margin-top: 2rem;
  text-align: center;

  &__button {
    background: lightblue;
    border: 0;
    color: white;
    cursor: pointer;
    font-size: 1.5rem;
    font-weight: bold;
    height: 2.5rem;
    width: 2.5rem;
  }

  &__quantity {
    font-size: 1.5rem;
    margin: 0 1rem;
  }
}
</style>