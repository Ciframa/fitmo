<template>
  <a href="/objednavka/kosik" class="cart">
    <span v-if="cartAmount === 0" class="cart__text__white"
      >Košík je<br />prázdný</span
    >
    <img src="../../public/assets/icons/cart.svg" alt="Cart icon" />
    <span class="cart__number">{{ cartAmount }}</span>
  </a>
</template>
<script>
export default {
  methods: {
    loadData() {
      const cart = JSON.parse(sessionStorage.getItem("cart"));
      this.$store.commit("updateCart", cart);
    },
  },
  created() {
    this.loadData();
  },
  computed: {
    cartAmount() {
      let amount = 0;
      if (this.$store.state.cart) {
        this.$store.state.cart.forEach((element) => {
          amount += element.count;
        });
      }
      return amount;
    },
  },
};
</script>
<style lang="scss" scoped>
.cart {
  display: flex;
  justify-content: center;
  align-items: center;
  position: relative;
  padding: 0;
  img {
    height: 3.5rem;
  }
  &__text {
    display: flex;
    flex-direction: column;
    font-weight: 100;
    font-size: 1.5rem;
    text-align: right;
    margin-right: 0.6rem;

    span:first-child() {
      line-height: 1.2rem;
      margin-bottom: 0.4rem;
    }

    &__white {
      line-height: 1.6rem;
      font-size: 1.4rem;
      color: $white;
    }
  }
  &__number {
    background: $white;
    position: absolute;
    top: -1rem;
    right: -1rem;
    border-radius: 50%;
    height: 2rem;
    width: 2rem;
    font-size: 1.2rem;
    display: flex;
    justify-content: center;
    align-items: center;
    font-weight: 400;
  }
}
@media screen and (max-width: $screen-lg-min - 1px) {
  .cart__text__white {
    display: none;
  }
}

@media screen and (min-width: $screen-lg-min) {
  .cart {
    margin-right: 2rem;
  }
}
</style>
