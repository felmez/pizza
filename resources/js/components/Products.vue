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
          <button @click="addToCart()" class="btn btn-primary">Add to Cart</button>

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

    <!-- cart popup start -->
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
            
                  <tr v-for="product in cart" :key="product.id">
                    <td>{{product.name}} ({{ product.quantity }})</td>
                    <td>$ {{product.price}}</td>
                    
                      
                     

          <button @click="updateCart(product, 'subtract')">-</button>

          <span class="cart__quantity">{{ product.quantity }}</span>

          <button @click="updateCart(product, 'add')" >+</button>

      
              
                    <td width="60">
                      <button @click="removeCart(n)" class="btn btn-danger btn-sm">
                        <i class="fas fa-times-circle"></i>
                      </button>
                    </td>
                  </tr>

            

                </tbody>
              </table>
            </div>
            <div class="modal-footer">
              Total Price: $ {{totalprice}} &nbsp;
              <button
                data-dismiss="modal"
                class="btn btn-primary"
              >Checkout</button>
            </div>
          </div>
        </div>
      </div>
      <!-- cart popup end -->







   </div>
  <!-- cart end -->



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
        price: "",
        quantity: ""
      },
      showCart: false,
      totalprice: "0",
    };
  },
  created() {
    this.viewProduct();
    this.viewCart();
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
    viewCart() {
      if (localStorage.getItem("carts")) {
        this.carts = JSON.parse(localStorage.getItem("carts"));
        this.totalprice = this.carts.reduce((total, item) => {
          return total + this.quantity * item.price;
        }, 0);
      }
    },
    addToCart(){
      this.$store.commit('addToCart', this.product)
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
    
    .cart-dropdown {
      background: white;
      border: 1px solid lightgray;
      border-radius: 10px;
      box-shadow: 0 0 2px rgba(0, 0, 0, 0.2);
      color: #333;
      font-size: 1.3rem;
      overflow: auto;
      padding: 0 1rem;
      position: absolute;
      right: 0;
      width: 12rem;
      
      .cart-dropdown__list {
        list-style: none;
        
        li {
          margin: 1rem 0;
        }
      }
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