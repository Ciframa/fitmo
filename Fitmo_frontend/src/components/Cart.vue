<template>
  <a href="/objednavka/kosik" class="cart">
    <div class="cart__icon__wrapper">
      <img src="../../public/assets/icons/cart.svg" alt="Cart icon" />
      <span class="cart__number">{{ cartAmount }}</span>
    </div>
    <span v-if="cartAmount === 0" class="cart__text__white"
      >Košík je<br />prázdný</span
    >
  </a>
</template>
<script>
export default {
  methods: {
    loadData() {
      const cart = JSON.parse(localStorage.getItem("cart"));
      this.$store.commit("updateCart", cart);
    },
  },
  mounted() {
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
  gap: 1.5rem;

  &__icon__wrapper {
    position: relative;
  }

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

    span:first-child {
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

@media screen and (min-width: $screen-xl-min) {
  .cart {
    margin-right: 2rem;

    img {
      height: 4.3rem;
    }
  }
}
</style>
